<?php
class Trade{

  function __construct() {
	//获取code，table_name配置信息
	$this->Runner=new Runner();
	$this->table_name=$this->Runner->get_config()['table_name'];
	echo "初始化：".$this->table_name=$this->Runner->get_config()['table_name']."\n";
	echo "初始化：".$this->code=$this->Runner->get_config()['code']."\n";  
	$this->code=$this->Runner->get_config()['code'];	
	//获取db配置信息  
	$this->db_config = new DB_Config_Inc(); 
	$this->conn = $this->db_config->get_db_config();
	//获取db操作信息
	$this->db = new db();  
	//初始化log对象  
	$this->log = new logs();
	//获取实时数据  
	$this->MachiningPrice= new MachiningPrice();
	$this->begin_point=$this->MachiningPrice->get_machining_price()['begin_point'];
	$this->stat_date=$this->MachiningPrice->get_machining_price()['stat_date'];  
	//测试代码，测试方法调用  
}
	
function sell_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price) {
	  $this->log -> log_work("comming sell_action\n");
	  $sql="select * from trade_history where code=$this->code and vifi_status=0 and status=1 and trade_type>20 and stat_date<'$this->stat_date' order by id asc;";
	  //$row=result_select("select * from trade_history where code=$code and vifi_status=0 and status=1 and trade_type>20 and stat_date<'$stat_date' order by id asc;");
	  //$log -> log_work("$sql\n");
	  $result = $this->conn->query($sql);
	  while($row=mysqli_fetch_array($result)){
	  //while($row){	  
		   $connecttion_id=$row[id];
		   $number=$row[number];   
		   $this->log -> log_work("connecttion_id:$connecttion_id\n");
		   if($begin_point>$row[trade_buy_price] and $row[cut_price] < ($row[trade_buy_price]+$row[trade_buy_price]*3.02/100)){
			  $this->log -> log_work("达到条件触发卖出操作，$begin_point，$row[trade_buy_price]，$number,$row[cut_price]\n"); 
			  $trade_id=table_id($conn,"trade_history"); 
			  $this->log -> log_work("trade_id:$trade_id\n");	   
			  //插入交易历史  
			  $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,vifi_status,number,trade_type,trade_buy_price,trade_sell_price,cut_price,connecttion_id,history_make_money) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','0','$number','$trade_type','$trade_buy_price','$trade_sell_price','0','$connecttion_id',$row[history_make_money]);";                                                                  
			  $this->log -> log_work("插入交易指令".$sql."\n");
			  $this->conn->query($sql);
			  //核销已经处理的前期订单，避免订单再次进入
			  $sql = "update trade_history set connecttion_id='$trade_id',vifi_status='1' where id='$connecttion_id';";
			  $this->log -> log_work("核销已经处理的订单".$sql."\n");
			  $this->conn->query($sql);
		   }
		   else{
			  $this->log -> log_work("未达到条件不能触发卖出操作，$this->begin_point，$row[trade_buy_price]，$number\n"); 
	                }		  	  
	  }
	   mysqli_free_result($result);  //循环结束释放结果集  
	 //######################################################################## 
}
function huizhuan_sell_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price) {
      //####################################################################### 
	global $log;
	  $log -> log_work("comming huizhuan_sell_action\n");
	  $row=result_select("select count(*) from trade_history where code=$code and vifi_status=1 and status=1 and trade_type<20 and stat_date='$stat_date' and stat_time_hour='$trade_time_hour';");
	  $huizhuan_sell_number=$row[0];
	
	  $sql="select * from trade_history where code=$code and vifi_status=0 and status=1 and trade_type>20 and stat_date<'$stat_date' order by id asc;";
	  //$row=result_select("select * from trade_history where code=$code and vifi_status=0 and status=1 and trade_type>20 and stat_date<'$stat_date' order by id asc;");
	  //$log -> log_work($sql."\n");
	  $result = $conn->query($sql);
	  while($row=mysqli_fetch_array($result)){
	  //while($row){	  
		   $connecttion_id=$row[id];
		   $number=$row[number];
		   $cut_price=$trade_buy_price+$trade_buy_price*3/100;
		   $log -> log_work("connecttion_id:"."$connecttion_id~$cut_price\n");
		   if($begin_point>$row[trade_buy_price] and $huizhuan_sell_number==0 and $row[cut_price] < ($row[trade_buy_price]+$row[trade_buy_price]*3.02/100)){
			  $log -> log_work("达到条件触发卖出操作$begin_point，$huizhuan_sell_number，$number，$row[trade_buy_price],$row[cut_price]\n"); 
			  $trade_id=table_id($conn,"trade_history"); 
			  $log -> log_work("trade_id:".$trade_id);	   
			  //插入交易历史  
			  $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,vifi_status,number,trade_type,trade_buy_price,trade_sell_price,cut_price,connecttion_id,history_make_money) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','0','$number','$trade_type','$trade_buy_price','$trade_sell_price','0','$connecttion_id',$row[history_make_money]);";                                                                  
			  $log -> log_work("插入交易指令".$sql."\n");
			  $conn->query($sql);
			  //核销已经处理的前期订单，避免订单再次进入
			  $sql = "update trade_history set connecttion_id='$trade_id',vifi_status='1' where id='$connecttion_id';";
			  $log -> log_work("核销已经处理的订单".$sql."\n");
			  $conn->query($sql);
		   }
		   else{
			  $log -> log_work("未达到条件不能触发回转卖出操作$begin_point，$huizhuan_sell_number，$number，$row[trade_buy_price]\n"); 
	                } 
	  }
	   mysqli_free_result($result);  //循环结束释放结果集  
	 //######################################################################## 
}
function buy_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price,$trade_bite) {
      global $useable_money,$log,$runoob,$kdjday_k,$kdjday_d,$cci;	
      $log -> log_work("coming buy_action~~~~".$trade_bite."\n");
      $b=$runoob->setkdj($kdjday_k,$kdjday_d,$cci);	
      echo $runoob->Get_Decide($b)."前面是状态\n";   	
      $number=11/$trade_buy_price*$trade_bite;
      $number=round($number);
      //每次发出指令以前都判断一下当前是否有足额可用资金	
      $row=result_select("select useable_money from hive_number where code='$trade_code' order by stat_date desc limit 1;");
      $useable_money=$row[useable_money];	
      //$sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=$trade_type;";
      $row=result_select("select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and trade_type=$trade_type;");
      $log -> log_work("$useable_money~~~~~~$number~~~~$trade_buy_price\n");
      if(($trade_type==5 or $trade_type==6 or $trade_type==7 or $trade_type==8 or $trade_type==9) and ($useable_money>=($number*100*$trade_buy_price))){
	      $log -> log_work("回转达到条件触发买入操作，useable_money:$useable_money，row[0]:$row[0]，number:$number，$trade_buy_price\n");
	      $trade_id=table_id($conn,"trade_history");
	      $cut_price=$trade_buy_price+($trade_buy_price*3/100);
	      $log -> log_work("cut_price:$cut_price\n");
	      //$cut_price=round($cut_price,3);
	      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,vifi_status,number,trade_type,trade_buy_price,trade_sell_price,cut_price,connecttion_id) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','0','$number','$trade_type','$trade_buy_price','$trade_sell_price','$cut_price','0');";
	      $log -> log_work("buy_action交易指令".$sql."\n");
	      $conn->query($sql);
      }else{
		$log -> log_work("回转未达到条件不能触发买入操作，$useable_money，$row[0]，$number，$trade_buy_price\n"); 
      }	
      if(($trade_type!=5 and $trade_type!=6 and $trade_type!=7 and $trade_type!=8 and $trade_type!=9) and $row[0]==0 and $useable_money>=($number*100*$trade_buy_price)){
      //if($row[0]==0 or $useable_money>=($number*100*$trade_buy_price)){	      
	      $log -> log_work("非回转达到条件触发买入操作，useable_money:$useable_money，row[0]:$row[0]，number:$number，$trade_buy_price\n");
	      $trade_id=table_id($conn,"trade_history");
	      $cut_price=$trade_buy_price+($trade_buy_price*3/100);
	      $log -> log_work("cut_price:$cut_price\n");
	      //$cut_price=round($cut_price,3);
	      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,vifi_status,number,trade_type,trade_buy_price,trade_sell_price,cut_price,connecttion_id) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','0','$number','$trade_type','$trade_buy_price','$trade_sell_price','$cut_price','0');";
	      $log -> log_work("buy_action交易指令".$sql."\n");
	      $conn->query($sql);
	      }
	else{
		$log -> log_work("非回转未达到条件不能触发买入操作，$useable_money，$row[0]，$number，$trade_buy_price\n"); 
	    }
}
}
?>
