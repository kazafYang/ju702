<?php
class Nine_Count{
	
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
	//$this->begin_point=$this->MachiningPrice->get_machining_price()['begin_point'];
	//$this->stat_date=$this->MachiningPrice->get_machining_price()['stat_date'];
	//$this->time_hour=$this->MachiningPrice->get_machining_price()['time_hour'];
	//$this->time_min=$this->MachiningPrice->get_machining_price()['time_min'];  
	//初始化kdj
	$this->kdj=new Kdj();   
	//测试代码，测试方法调用  
}	
	
 function nine_count ($time_out_begin,$time_out_now) {
	  $this->log -> log_work("comming nine_count,开始执行~\n");
	 //获取最新数据的数组
	  $data=$this->MachiningPrice->get_machining_price();
	  $max=$data['begin_point'];
	  $min=$data['begin_point'];  
	  $sql="update $this->table_name set min15_point_max=$max,min15_point_min=$min order by id desc limit 1 ;";
	  $this->conn->query($sql);
	  //$time_out_now=($this->time_hour*3600)+($this->time_min*60);
	  while($time_out_now < $time_out_begin) {
	  $data=$this->MachiningPrice->get_machining_price();	  
	  $time_out_now=($data['time_hour']*3600)+($data['time_min']*60);
	  //echo "$time_out_now~$this->time_hour~$this->time_min\n";	  
	  $this->log -> log_work("结束时间：".intval($time_out_now/3600)."：".($time_out_now%3600)/60); 	  
	  $this->log -> log_work("开始nice_count循环开始：time_out_now:$time_out_now~time_out_begin:$time_out_begin\n");
	  //sleep(100000);	  
	  $row=$this->db->get_select("select stat_time_min from $this->table_name order by id desc limit 1;");	  
	  $stat_time_min=$row[0];	  
	  $this->log -> log_work("$max--$data[begin_point]-$stat_time_min-stat_time_min\n");	  
		  if(($data[time_min]%15==0) and $data[time_min]<>$stat_time_min){
		  $this->log -> log_work("***************************");
		  break;
		  }	  
	  if ($data[begin_point]>=$max)
	  {
	      $max=$data[begin_point];
	      $this->log -> log_work("$max\n");
	      $sql="update $this->table_name set min15_point_max=$max order by id desc limit 1 ;";
	      $this->log -> log_work($sql."\n");
		      if ($this->conn->query($sql)=== TRUE)
		     {
		      $this->log ->log_work("max新纪录更新成功");
		       } 
		      else {
		      $this->log -> log_work("max新纪录更新Error: " . $sql . $this->conn->error."\n");
		  }
  }
	  if ($this->begin_point<=$min)
	  {
	      $min=$data[begin_point]; 
	      $sql="update " .$this->table_name." set min15_point_min=$min order by id desc limit 1 ; ";
		      if ($this->conn->query($sql) === TRUE) {
		      $this->log -> log_work("min:新记录更新成功");
		       } 
		      else {
		      $this->log -> log_work("min新纪录更新Error: " . $sql . $this->conn->error."\n");
		  }
  } 
	  //更新买一，卖一实时价格  
	  $sql="update $this->table_name set now_price=$data[begin_point],buy_one_price=$data[buy_one_price],sell_one_price=$data[sell_one_price] order by id desc limit 1 ;";
	  $this->conn->query($sql);
	  $this->kdj->set_kdjfifteen(); #begin:kdj
	  $this->kdj->set_kdjthirty();
	  $this->kdj->set_kdjsixty(); 
	  $this->kdj->set_kdjtwohour();	  
	  $this->kdj->set_kdjday();
	  //test_cut_price();		  
	  //analyse();
	  //cci();
	  $this->log -> log_work("本次程序执行完成------------------------->\n");	  
	  } 
 }
 }	 
?>
