<?php
class Kdj {
  
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
	//测试代码，测试方法调用  
}
  function set_kdjfifteen () {
    $data=$this->MachiningPrice->get_machining_price();	  
    $row=$this->db->get_select("select max(min15_point_max) from (select * from $this->table_name order by id desc limit 9) as a;");	  
    $min15_point_max=$row[0];
    echo "test:$min15_point_max\n";	  
    $row=$this->db->get_select("select min(min15_point_min) from (select * from $this->table_name order by id desc limit 9) as a;");	  
    $min15_point_min=$row[0];
    $this->log -> log_work("min15_point_min:$min15_point_min");
    $row=$this->db->get_select("select min15_k,min15_d from $this->table_name order by id  desc  limit 1,1;");	  
    $min15_k=$row[min15_k];   
    $min15_d=$row[min15_d];
    $this->log -> log_work("begin_point:$data[begin_point]~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~min15_k:$min15_k~min15_d:$min15_d\n");                      
    $rsv=($data[begin_point]-$min15_point_min)/($min15_point_max-$min15_point_min)*100;  
    $this->log -> log_work("rsv:$rsv");
    $k=2/3*$min15_k+1/3*$rsv;
    $d=2/3*$min15_d+1/3*$k;
    $j=3*$k-2*$d;
    $this->log -> log_work("15kdj:$k,$d,$j\n");  	  
    $sql="update $this->table_name set min15_k='$k' , min15_d='$d' , min15_j='$j' order by id desc limit 1 ; ";
    $this->log -> log_work("开始更新15kdj:$sql\n");	  
    /*暂时注释掉，免得破坏数据，或者把desc 改成asc 这样就不存在了 */ 
    if ($this->conn->query($sql) === TRUE) {
      $this->log -> log_work("15kdjupdate:success\n");	     
    } 
    else {
      $this->log -> log_work("maxError: " . $sql . $this->conn->error."\n"); 	    
    }
  
  }

  function set_kdjthirty () {
    $data=$this->MachiningPrice->get_machining_price();		  
    $row=$this->db->get_select("select max(min15_point_max) from (select * from $this->table_name order by id desc limit 18) as a;");
    $min15_point_max=$row[0];
    $row=$this->db->get_select("select min(min15_point_min) from (select * from $this->table_name order by id desc limit 18) as a;");	  
    $min15_point_min=$row[0];
    if (($time_hour==9 and $time_min==30) or ($time_hour==10 and $time_min==0) or ($time_hour==10 and $time_min==30) or ($time_hour==11 and $time_min==0) or ($time_hour==13 and $time_min==0) or ($time_hour==13 and $time_min==30) or ($time_hour==14 and $time_min==0)) {
      $row=$this->db->get_select("select min30_k,min30_d from $this->table_name order by id  desc  limit 1,1;");	  
      $min30_k=$row[min30_k]; 
      $min30_d=$row[min30_d];
    }
    else {
      $row=$this->db->get_select("select min30_k,min30_d from $this->table_name order by id  desc  limit 2,1;");	  
      $min30_k=$row[min30_k]; 
      $min30_d=$row[min30_d];
    }  
    $this ->log -> log_work("begin_point:$data[begin_point]~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~min30_k:$min30_k~min30_d:$min30_d\n");                       
    $rsv=($data[begin_point]-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
    $k=2/3*$min30_k+1/3*$rsv;
    $d=2/3*$min30_d+1/3*$k;
    $j=3*$k-2*$d;
    $this -> log -> log_work("30kdj:$k,$d,$j\n");	  
    $sql="update $this->table_name set min30_k='$k' , min30_d='$d' , min30_j='$j' order by id desc limit 1 ; ";
    if ($this->conn->query($sql) === TRUE) 
    {
      $this -> log -> log_work("30kdjupdate:success:$sql\n");
     } 
    else {
      $this -> log -> log_work("maxError: " . $sql . $this->conn->error."\n");
    }  
  }

  function set_kdjsixty () {
    $data=$this->MachiningPrice->get_machining_price();		  
    $this->log-> log_work("comming kdjsixty");		  
    $row=$this -> db -> get_select("select max(min15_point_max) from (select * from $this->table_name order by id desc limit 36) as a;");	  
    $min15_point_max=$row[0];
    $row=$this -> db -> get_select("select min(min15_point_min) from (select * from $this->table_name order by id desc limit 36) as a;");	  
    $min15_point_min=$row[0];
    $this->log -> log_work("time_hour:$time_hour\n");
    if (($time_hour==9 and $time_min==30) or ($time_hour==10 and $time_min==30) or ($time_hour==13 and $time_min==0) or ($time_hour==14 and $time_min==0)) {
      $row=$this->db->get_select("select min60_k,min60_d from $this->table_name order by id  desc  limit 1,1;");	  
      $min60_k=$row[min60_k];
      $min60_d=$row[min60_d];
    }
    elseif (($time_hour==9 and $time_min==45) or ($time_hour==10 and $time_min==45) or ($time_hour==13 and $time_min==15) or ($time_hour==14 and $time_min==15)) {
      $row=$this->db->get_select("select min60_k,min60_d from $this->table_name order by id  desc  limit 2,1;");	  
      $min60_k=$row[min60_k];
      $min60_d=$row[min60_d];
    }
    elseif(($time_hour==10 and $time_min==0) or ($time_hour==11 and $time_min==0) or ($time_hour==13 and $time_min==30) or ($time_hour==14 and $time_min==30)) {
      $row=$this->db->get_select("select min60_k,min60_d from $this->table_name order by id  desc  limit 3,1;");	  
      $min60_k=$row[min60_k];
      $min60_d=$row[min60_d];
    }
    else{
      $row=$this->db->get_select("select min60_k,min60_d from $this->table_name order by id  desc  limit 4,1;");	  
      $min60_k=$row[min60_k];
      $min60_d=$row[min60_d];
    }
    $this->log -> log_work("begin_point:$data[begin_point]~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~min60_k:$min60_k~min60_d:$min60_d\n");   
    $rsv=($data[begin_point]-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
    $k=2/3*$min60_k+1/3*$rsv;
    $d=2/3*$min60_d+1/3*$k;
    $j=3*$k-2*$d;
    $this->log -> log_work("60kdj:$k,$d,$j\n");
    $sql="update $this->table_name set min60_k='$k' , min60_d='$d' , min60_j='$j' order by id asc limit 1 ; ";
       if ($this->conn->query($sql) === TRUE) 
       {
        $this->log -> log_work("60kdjupdate:success:$sql\n");
         } 
      else {
        $this->log -> log_work("60kdjError: " . $sql . $this->conn->error."\n");
    }  
  }

  function set_kdjtwohour () {
    $data=$this->MachiningPrice->get_machining_price();		  
    $this->log -> log_work("comming two_hour");		   
    $row=$this->db->get_select("select max(min15_point_max) from (select * from $this->table_name order by id desc limit 72) as a;");	   
    $min15_point_max=$row[0];
    $row=$this->db->get_select("select min(min15_point_min) from (select * from $this->table_name order by id desc limit 72) as a;");	   
    $min15_point_min=$row[0];
    if ($time_hour<13) {
    $row=$this->db->get_select("select min120_k,min120_d from $this->table_name where stat_date<'$this->stat_date' order by id  desc  limit 0,1;");	  
    $min120_k=$row[min120_k];
    $min120_d=$row[min120_d];
    }
    elseif ($time_hour>=13) {
    $row=$this->db->get_select("select min120_k,min120_d from $this->table_name where stat_time_hour='11' and stat_time_min='15' order by id  desc  limit 1;");	  
    $min120_k=$row[min120_k];
    $min120_d=$row[min120_d];
    }
    $this->log -> log_work("begin_point:$data[begin_point]~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~\n");   
    $rsv=($data[begin_point]-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
    $k=2/3*$min120_k+1/3*$rsv;
    $d=2/3*$min120_d+1/3*$k;
    $j=3*$k-2*$d;
    $this->log -> log_work("120kdj:$k,$d,$j\n");
    $sql="update $this->table_name set min120_k='$k' , min120_d='$d' , min120_j='$j' order by id desc limit 1 ; ";
     if ($this->conn->query($sql) === TRUE) 
     {
      $this->log -> log_work("120kdjupdate:success:$sql\n");
       } 
    else {
      $this->log -> log_work("120kdjError: " . $sql . $this->conn->error."\n");
    }  
  }

  function set_kdjday () {
  $data=$this->MachiningPrice->get_machining_price();		  
  $row=$this->db->get_select("select max(min15_point_max) from (select * from $this->table_name order by id desc limit 144) as a;");	  
  $min15_point_max=$row[0];
  $row=$this->db->get_select("select min(min15_point_min) from (select * from $this->table_name order by id desc limit 144) as a;");	  
  $min15_point_min=$row[0];
  $row=$this->db->get_select("select kdjday_k,kdjday_d from $this->table_name where stat_date<'$this->stat_date' order by id desc limit 1;");	  
  $kdjday_k=$row[kdjday_k]; 
  $kdjday_d=$row[kdjday_d];
  $this->log -> log_work("begin_point:$data[begin_point]~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~kdjday_k:$kdjday_k~kdjday_d:$kdjday_d\n");                       
  $rsv=($data[begin_point]-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
  $k=2/3*$kdjday_k+1/3*$rsv;
  $d=2/3*$kdjday_d+1/3*$k;
  $j=3*$k-2*$d;
  $this->log -> log_work("daykdj:$k,$d,$j\n");
  $sql="update $this->table_name set kdjday_k='$k' , kdjday_d='$d' , kdjday_j='$j' order by id desc limit 1 ; ";
   if ($this->conn->query($sql) === TRUE) 
   {
    $this->log -> log_work("daykdjupdate:success:$sql\n");	     
     } 
  else {
    $this->log -> log_work("daykdj:updateError: " . $sql . $this->conn->error."\n");	    
  }  
  }

}
?>
