<?php
class Analyse{
	
function __construct() {
	//获取code，table_name配置信息
	$this->Runner=new Runner();
	$this->table_name=$this->Runner->get_config()['table_name'];
	$this->code=$this->Runner->get_config()['code'];	
	//获取db操作信息
	$this->db = new db();  
	//初始化log对象  
	$this->log = new logs();
	//获取实时数据  
	$this->MachiningPrice= new MachiningPrice();
	$this->trade= new Trade();
	//$this->trade->sell_action("6");
}
	
function set_analyse () {	
      $this->log -> log_work("comming analyse\n");
      //五日十日均线数据计算	
      $row=$this->db->get_select("select avg(now_price) from (select now_price from $this->table_name order by id desc limit 0,80) as a;");	
      $first_min5_avgprice=$row[0];
      $row=$this->db->get_select("select avg(now_price) from (select now_price from $this->table_name order by id desc limit 0,160) as a;");	
      $first_min10_avgprice=$row[0];	   
      $row=$this->db->get_select("select avg(now_price) from (select now_price from $this->table_name order by id desc limit 16,80) as a;");	
      $second_min5_avgprice=$row[0];
      $row=$this->db->get_select("select avg(now_price) from (select now_price from $this->table_name order by id desc limit 16,160) as a;");	
      $second_min10_avgprice=$row[0];
      //获取分时kdj数据;
      $row=$this->db->get_select("SELECT code,stat_date,stat_time_hour,stat_time_min,min15_k,min15_d,min15_j,min30_k,min30_d,min30_j,min60_k,min60_d,min60_j,min120_k,min120_d,min120_j,kdjday_k,kdjday_d,kdjday_j,cci,buy_one_price,sell_one_price FROM $this->table_name order by id desc limit 1");	
      $trade_code=$row[code];$trade_buy_price=$row[buy_one_price];$trade_sell_price=$row[sell_one_price];
      $trade_stat_date=$row[stat_date];$trade_time_hour=$row[stat_time_hour];$trade_time_min=$row[stat_time_min];
      $trade_min15_k=round($row[min15_k],2);$trade_min15_d=round($row[min15_d],2);$trade_min15_j=round($row[min15_j],2);
      $trade_min30_k=round($row[min30_k],2);$trade_min30_d=round($row[min30_d],2);$trade_min30_j=round($row[min30_j],2);
      $trade_min60_k=round($row[min60_k],2);$trade_min60_d=round($row[min60_d],2);$trade_min60_j=round($row[min60_j],2);
      $trade_min120_k=round($row[min120_k],2);$trade_min120_d=round($row[min120_d],2);$trade_min120_j=round($row[min120_j],2);	  
      $trade_day_k=round($row[kdjday_k],2);$trade_day_d=round($row[kdjday_d],2);$trade_day_j=round($row[kdjday_j],2);	  
      //获取上一次分时kdj数据用来判断kdj金叉死叉;
      $row=$this->db->get_select("SELECT code,stat_date,stat_time_hour,stat_time_min,min15_k,min15_d,min15_j,min30_k,min30_d,min30_j,min60_k,min60_d,min60_j,min120_k,min120_d,min120_j,kdjday_k,kdjday_d,kdjday_j,cci,buy_one_price,sell_one_price FROM $this->table_name order by id desc limit 1,1");
      $trade_second_min15_k=round($row[min15_k],2);$trade_second_min15_d=round($row[min15_d],2);$trade_second_min15_j=round($row[min15_j],2);
      $trade_second_min30_k=round($row[min30_k],2);$trade_second_min30_d=round($row[min30_d],2);$trade_second_min30_j=round($row[min30_j],2);
      $trade_second_min60_k=round($row[min60_k],2);$trade_second_min60_d=round($row[min60_d],2);$trade_second_min60_j=round($row[min60_j],2);
      $trade_second_min120_k=round($row[min120_k],2);$trade_second_min120_d=round($row[min120_d],2);$trade_second_min120_j=round($row[min120_j],2);	  
      $trade_second_day_k=round($row[kdjday_k],2);$trade_second_day_d=round($row[kdjday_d],2);$trade_second_day_j=round($row[kdjday_j],2); 		  
     //获取bite倍数信息，给各个阶段加倍
      $str="";
      $switched=0; 	  
      $sql = "select bite as a from trade_bate  order by id asc ;";    
      $result=$this->db->get_resultselect($sql);
      while ($row=mysqli_fetch_row($result))
    {
	$a = sprintf("%s,%s",$row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13],$row[14],$row[15],$row[16],$row[17],$row[18]);
	$str=$str.$a;    
    }
      $pieces = explode(",", $str);
      $type1=$pieces[0];$type2=$pieces[1];$type3=$pieces[2];$type4=$pieces[3];$type5=$pieces[4];$type6=$pieces[5];$type7=$pieces[6];$type8=$pieces[7];$type9=$pieces[8];$type10=$pieces[9];$type11=$pieces[10];
      $type21=$pieces[11];$type22=$pieces[12];$type23=$pieces[13];$type24=$pieces[14];$type25=$pieces[15];$type26=$pieces[16];$type27=$pieces[17];$type28=$pieces[18];
      $this->log -> log_work($type1.$type2.$type3.$type4.$type5.$type6.$type7.$type8."trade_bate\n");
      mysqli_free_result($result);  //释放结果集
      //判断当日数据是否已经存在
      $row=$this->db->get_select("select count(*) from hive_number where code='$trade_code' and stat_date='$trade_stat_date';");	
      $this->log -> log_work("当日数据是否存在判断\n");	
      if($row[0]==0){
	      $this->log -> log_work("当日数据不存在，开始新增数据\n");
	      //拿取hive_number表数据条数获得插入的下一个id
	      $hive_number_id=$this->db->get_id("hive_number");
	      $this->log -> log_work("拿取到的hive_number_id：$hive_number_id.\n");	      
	      $row=$this->db->get_select("select switched,sell_switched,buy_switched,total_money,useable_money,total_number,useable_sell_number,total_sell_number,market_value,cost_price,make_money from hive_number where code='$trade_code' order by stat_date desc limit 1;");	      
	      $switched=$row[switched];$sell_switched=$row[sell_switched];$buy_switched=$row[buy_switched];$total_money=$row[total_money];$useable_money=$row[useable_money]; $total_number=$row[total_number];$useable_sell_number=$row[total_number];$total_sell_number=$row[total_number];$cost_price=$row[cost_price];$make_money=$row[make_money];$market_value=$row[market_value];
	      $this->log -> log_work($switched.$sell_switched.$buy_switched."开关\n");	      
	      //计算最近2日的平均买入成本   switch
	      $cost_stat_date=date("Y-m-d",strtotime("-2 day"));    
	      $row=$this->db->get_select("select avg(trade_sell_price) from trade_history where code='$this->code' and trade_type>=5 and stat_date>='$cost_stat_date';");
	      $cost_price=round($row[0],3);
	      $sql = "insert into hive_number values ('$hive_number_id','$trade_code','$switched','$sell_switched','$buy_switched','$total_money','$useable_money','$total_number','$useable_sell_number','$total_sell_number','$market_value','$cost_price','$make_money','$trade_stat_date');";                                                                  
	      $this->log -> log_work($sql."插入hive_number\n");	      
	      $this->db->set_insert($sql);   
      } 
      else{
      //拿取hive_number的基础属性
	      $this->log -> log_work("当日hive_number已经存在，开始获取最新hive_number数据\n");		  
	      $switched.$sell_switched.$buy_switched."开关\n";		   
	      $row=$this->db->get_select("select switched,sell_switched,buy_switched,total_money,useable_money,total_number,useable_sell_number,total_sell_number,cost_price from hive_number where code='$trade_code' order by stat_date desc limit 1;");
	      $switched=$row[switched];$sell_switched=$row[sell_switched];$buy_switched=$row[buy_switched];$total_money=$row[total_money];$useable_money=$row[useable_money]; $total_number=$row[total_number];$useable_sell_number=$row[useable_sell_number];$total_sell_number=$row[$total_sell_number];$cost_price=$row[cost_price];
      }
  $this->log -> log_work($switched."开始判断\n");	
  if($switched==1){
  $this->log -> log_work($switched."判断开关开启了\n");		  
      //sell判断
 //判断当前code是否具备卖出资格，后续可以在这里加上开关等限制性的行为；昨日的总数量，就是今日的可卖数量；$switched=1是开关打开状态
    if($useable_sell_number>1 and $sell_switched==1 and ($trade_day_k >= 85 or $trade_day_d >= 80)){ 
    //if($useable_sell_number>=1 and $sell_switched==1 and ($trade_day_k >= 1 or $trade_day_d >= 1)){ 		    
      //超买情况下的15分钟卖出指标
      $this->log -> log_work("comming $trade_min15_k daykdj $trade_min15_d-sell"."\n");
      if($trade_min15_k>=85 or $trade_min15_d>=80)
    //  if($trade_min15_k>=1 or $trade_min15_d>=1)	      
      {
	$this->log -> log_work("comming日线超买开始 daykdj-sell-15\n");
	$trade_type=1;    
	sell_action($trade_type);  		      
      } 
  //30min  
     if($trade_min30_k>=85 or $trade_min30_d>=80)	 
     {
	$this->log -> log_work("comming daykdj-sell-30\n");
	$trade_type=2;    
	sell_action($trade_type);  		     
      } 
   //60分钟          
     if($trade_min60_k>=85 or $trade_min60_d>=80)
     {
	$this->log -> log_work("comming daykdj-sell-60\n");
	$trade_type=3;    
	sell_action($trade_type);  		     
      }
	    //120分钟          
     if($trade_min120_k>=85 or $trade_min120_d>=80)	 
     {
	$this->log -> log_work("comming daykdj-sell-120\n");
	$trade_type=4;    
	sell_action($trade_type);  		     
      }    
    } //日线超买完成
    //buy,买入开关限制，限制可用金额不足的情况，和标的开关关闭的情况，关闭 switch=0；
  if($useable_money>1000 and $buy_switched==1 and $trade_day_k < 20 and $trade_day_d < 20){
  //if(1==1){
	echo "comming日线超卖开始 switch-buy~~$buy_switched~~~day-$trade_day_k-kdj~~$trade_day_d~~$useable_money"."\n"; 
	  //15分钟条件严格一点
    if ($trade_min15_k <=15 or $trade_min15_d <=20){
    //if ($trade_min15_k <=95 or $trade_min15_d <90){
       $this->log -> log_work("comming switch-buy~~~~~day-kdj-min15~~$trade_min15_k~$trade_min15_d~"."\n"); 
       $trade_type=21; 
       $trade_bite=$type21;	    
       buy_action($trade_type,$trade_bite);
      }  
    if ($trade_min30_k <=15 or $trade_min30_d <=20){
       $this->log -> log_work("comming switch-buy~~~~~day-kdj-min30~$trade_min30_k~$trade_min30_d~~"."\n"); 
       $trade_type=22; 
       $trade_bite=$type22;	    
       buy_action($trade_type,$trade_bite);
      }   
    if ($trade_min60_k <=15 or $trade_min60_d <=20){ 
       $this->log -> log_work("comming switch-buy~~~~~day-kdj-min60~$trade_min60_k~$trade_min60_d~~"."\n"); 
       $trade_type=23; 
       $trade_bite=$type23;	    
       buy_action($trade_type,$trade_bite);
      }
	  //120日线超卖
      if ($trade_min120_k <=15 or $trade_min120_d <=20){
       $this->log -> log_work("comming switch-buy~~~~~day-kdj-min120~$trade_min120_k~$trade_min120_d~~"."\n"); 
       $trade_type=24; 
       $trade_bite=$type24;	    
       buy_action($trade_type,$trade_bite);
      }  	  
  }    //日线超卖完成
  $this->log -> log_work("回转sell开始\n");	  
  if((($trade_day_k>=40 and $trade_day_k<65) or ($trade_day_d>=40 and $trade_day_d<65)) and $useable_sell_number>1 and $sell_switched==1){
    //回转交易策略的位置,记录回转交易的标志是数据库字段status=2
	//15分钟回转使用死叉交易卖出 switch
	$this->log -> log_work("comming 回转交易开始-sell~~~~$trade_day_k~~~$trade_day_d~~"."\n");
    if(($trade_min15_k>=75 or $trade_min15_d >= 75) and $trade_min15_j < $trade_min15_k and $trade_min15_j < $trade_min15_d){
	$this->log -> log_work("comming huizhuan-rel-sell~~dead~~$trade_min15_k~~~$trade_min15_d~$trade_min15_j~"."\n");
	$trade_type=5;    
	huizhuan_sell_action($trade_type);     
  }    
	  //回转15分钟超买条件
       if($trade_min15_k>=80 or $trade_min15_d >= 80){
	$this->log -> log_work("comming huizhuan-rel-sell~~min15~~~~$trade_min15_k~~$trade_min15_d~"."\n");
	$trade_type=6;    
	huizhuan_sell_action($trade_type);  		       
  }	  
     if($trade_min30_k >= 80  or $trade_min30_d >= 80){
	$this->log ->log_work("comming huizhuan-rel-sell~~min30~~~$trade_min30_k~~$trade_min30_d~~"."\n");
	$trade_type=7;    
	huizhuan_sell_action($trade_type);  
	  }
    if($trade_min60_k >= 80  or $trade_min60_d >= 80){
	$this->log -> log_work("comming huizhuan-rel-sell~~min60~~$trade_min60_k~$trade_min60_d~~~~"."\n");
	$trade_type=8;    
	huizhuan_sell_action($trade_type);  
	  }
      if($trade_min120_k >= 80  or $trade_min120_d >= 80){
	$this->log -> log_work("comming huizhuan-rel-sell~~min120~$trade_min120_k~~~$trade_min120_d~~"."\n");
	$trade_type=9;    
	huizhuan_sell_action($trade_type);  
	  }
    } //卖出回转结束
	 //回转买入开始 
	//回转60分钟买入
$this->log -> log_work("回转buy开始\n");	  
 if((($trade_day_k>=20 and $trade_day_k<65) or ($trade_day_d>=20 and $trade_day_d<65)) and $useable_money>1000 and $buy_switched==1){	  
      $this->log ->log_work("huizhuan_buy:60mink=$trade_min60_k,60mind=$trade_min60_d,dayk:$trade_day_k,dayd=$trade_day_d\n");	  
      if (($trade_min60_k <20 or $trade_min60_d <20) and ($trade_day_k<65 and $trade_day_d<65)){
       $this->log -> log_work("回转买入开始:60mink=$trade_min60_k,60mind=$trade_min60_d,dayk:$trade_day_k,dayd=$trade_day_d\n");	      
       $this->log -> log_work("comming -huizhuan-buy~~min60~~~~~~"."\n");  
       $trade_type=25; 
       $trade_bite=$type25;	    
       buy_action($code,$trade_code,$this->conn,$data[begin_point],$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price,$trade_bite);
      }
	//回转买入，当前价低于最低卖出价5个点，即可等量/分批加码回收筹码；增加trade_type，标志回转交易，然后沿用status标志，这样比较好；如果这样的话不能判断出数据是否已经被处理了，所以我还需要一个步骤就是将已经对比的status的值=2;
       //判断已经交易完成的，然后处理结束后将status变更为2   
      $row=$this->db->get_select("select id,number,trade_sell_price,trade_type from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type in(5,6,7,8,9) and status=1 limit 1;");
      $number=$row[number];
      $number=round($number); 	  
      switch ($row[trade_type])
      {
       case 5:
	     $loser_price=$row[trade_sell_price]-($row[trade_sell_price]*2/100); //回转15分钟死叉
	     $loser_price=round($loser_price,3);
       break;
       case 6:
	     $loser_price=$row[trade_sell_price]-($row[trade_sell_price]*1/100); //回转15分钟超买
	     $loser_price=round($loser_price,3);
       break;
       case 7:
	     $loser_price=$row[trade_sell_price]-($row[trade_sell_price]*1.5/100); //回转30分钟超买
	     $loser_price=round($loser_price,3);	       
       break;
       case 8:
	     $loser_price=$row[trade_sell_price]-($row[trade_sell_price]*2/100); //回转60分钟超买
	     $loser_price=round($loser_price,3);	      
       break;
       case 9:
	     $loser_price=$row[trade_sell_price]-($row[trade_sell_price]*3/100); //回转120分钟超买
	     $loser_price=round($loser_price,3);	      
       break;
       default:
       $this->log -> log_work("No number 超买");
       }  
      $loser_price_id=$row[id];  
      $this->log -> log_work($loser_price_id."loser_price".$loser_price."~~~~~".$row[trade_sell_price]."\n");
      //发出回转交易买入信号，向交易库插入交易数据信息，回转交易买入也需要一个trade_type；  
     $this->log -> log_work($loser_price."comming switch-rel-buy~~$useable_money~~~~~~~".$trade_buy_price."\n"); 
     if ($loser_price>0 and $buy_switched==1 and $useable_money>1000 and ($trade_day_k<80 and $trade_day_d<75)){
       $this->log -> log_work("coming huizhuan_buy:$loser_price~$buy_switched~$useable_money~$trade_day_k~$trade_day_k\n");
       $trade_type=26; 
       $trade_bite=$type26;	    
       buy_action($trade_type,$trade_bite);  
       $sql = "update trade_history set status=2 where id=$loser_price_id;";                                                                  
       $this->db->set_update($sql);    
      }
 }
$this->log -> log_work("cut_sell_开始\n");		  
//cut_price卖出开始开始	  
if(($trade_day_k>=20 and $trade_day_k<85) or ($trade_day_d>=20 and $trade_day_d<80) and $useable_sell_number>1){
  	
  $sql="select * from trade_history where code=$code and vifi_status=0 and status=1 and trade_type>20 and stat_date<'$stat_date' order by id desc;";
  //$row=$this->db->get_select("select * from trade_history where code=$code and vifi_status=0 and status=1 and trade_type>20 and stat_date<'$stat_date' order by id desc;");
  $result = $this->db->resultselect($sql);
  while($row=mysqli_fetch_array($result)){
  //while($row){	  
   //故意将比例调高到3.02避免cut_price因为四舍五入取整后造成判断失效的问题，也可以将取出来的数据也取整，但是个人感觉这样更简单一点	  
   if($data[begin_point] <= ($row[cut_price]-$row[cut_price]*1/100) and $row[cut_price] > ($row[trade_buy_price]+$row[trade_buy_price]*3.02/100) and $row[trade_buy_price] <> "0" and $data[begin_point]>$row[trade_buy_price]){
	$trade_type=10;
	$this->log -> log_work ("cut_price开始".$row[id]."~".$row[cut_price]."~".$data[begin_point]."~".$row[trade_buy_price]."~".$row[number]."\n");
	$this->log -> log_work($code."~".$data[begin_point]."~".$stat_date."\n");
	$trade_id=$this->db->get_id($this->conn,"trade_history");
	$this->log -> log_work("trade_id:$trade_id");	   
	//插入交易历史  
	$sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,vifi_status,number,trade_type,trade_buy_price,trade_sell_price,cut_price,connecttion_id,history_make_money) values ('$trade_id','$code','$stat_date','$time_hour','$time_min','0','0','$row[number]','$trade_type','$row[trade_buy_price]','$data[begin_point]','0','$row[id]',$row[history_make_money]);";                                                                  
	$this->log -> log_work("$sql:cut_price sell 处理了！！！！\n");
	$this->conn->query($sql);
	//核销已经处理的前期订单，避免订单再次进入    
	$sql = "update trade_history set connecttion_id='$trade_id',vifi_status='1' where id='$row[id]';";
	$this->log ->log_work("$sql~cut_price 核销订单sql\n");
	$this->db->set_update($sql);
    }
    else{
         $this->log -> log_work("不符合cut_price函数条件，不执行!begin_point:$data[begin_point],cut_price:$row[cut_price],trade_buy_price:$row[trade_buy_price]");
    } 	  
}
   mysqli_free_result($result);  //循环结束释放结果集  
	
}
              
/*	      //5日线非分钟线金叉吸入筹码	  
	      if(($first_min5_avgprice>$first_min10_avgprice) and ($second_min5_avgprice<$second_min10_avgprice) and $trade_day_k<50){
	      $number=11/$trade_buy_price*$type27;
	      $number=round($number); 
	      $row=$this->db->get_select("select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=27;");
	      if($row[0]==0 and $buy_switched==1 and $useable_money>=($number*100*$trade_buy_price)){
	      $trade_id=$this->db->get_id($this->conn,"trade_history");	      
	      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','27','$trade_buy_price','$trade_sell_price');";    
	      echo $sql."comming -min5-avg-buy-sql~~~~~~~~~"."\n";                                                                  
	      $this->conn->query($sql);
	      }
	      } //金叉结束
		//5日线非分钟线死叉抛出筹码	  
	      if(($first_min5_avgprice<$first_min10_avgprice) and ($second_min5_avgprice>$second_min10_avgprice) and $trade_day_k>60){
	      $number=11/$trade_sell_price*$type10;
	      $number=round($number);   
	      $row=$this->db->get_select("select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=10;");
	      if($row[0]==0 and $sell_switched==1 and $useable_sell_number>=$number and ($number*$trade_sell_price*100>=1000)){
		      $trade_id=$this->db->get_id($this->conn,"trade_history");  	      
		      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','10','$trade_buy_price','$trade_sell_price');";    
		      echo $sql."comming -min5-avg-buy-sql~~~~~~~~~"."\n";                                                                  
		      $this->conn->query($sql);
	      }
	      } //死叉结束	
		  //金叉+当前价高于五日均线
	     if($trade_day_k>$trade_day_d and $data[begin_point]>$first_min5_avgprice and $trade_day_k<55){
	      $number=11/$trade_buy_price*$type28;
	      $number=round($number);     
	      $row=$this->db->get_select("select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=28;");   
	      if($row[0]==0 and $buy_switched==1 and $useable_money>=($number*100*$trade_buy_price)){
	      $trade_id=$this->db->get_id($this->conn,"trade_history");   
	      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','28','$trade_buy_price','$trade_sell_price');";                                                                  
	      $this->conn->query($sql);
	      //更新hive_number表数据
	      $total_number=$total_number+$number;  
	      $useable_money=$useable_money-($number*$trade_buy_price*100);  
	      $sql = "update hive_number set total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date' order by id desc limit 1;";                                                                  
	      $this->conn->query($sql);
	      $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
	      $this->conn->query($sql);   
		 }      
	      }	
		  //死叉+低于五日均线
	     if($trade_day_k<$trade_day_d and $data[begin_point]<$first_min5_avgprice and $trade_day_k>60){
	      $number=11/$trade_buy_price*$type11;
	      $number=round($number);        
	      $row=$this->db->get_select("select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=11;");  
	      if($row[0]==0  and $sell_switched==1 and ($number*$trade_sell_price*100>=1000) ){
	      $trade_id=$this->db->get_id($this->conn,"trade_history");   
	      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','11','$trade_buy_price','$trade_sell_price');";                                                                  
	      $this->conn->query($sql);
		    //更新hive_number表数据
	      $total_number=$total_number+$number;  
	      $useable_money=$useable_money-($number*$trade_buy_price*100);  
	      $sql = "update hive_number set total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date' order by id desc limit 1;";                                                                  
	      $this->conn->query($sql);
	      $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
	      $this->conn->query($sql);   
		 }      
	      }	
	      */
}//开关结束
	   $this->log -> log_work($switched."判断开关结束了\n");
  }//方法结束
}
?>
