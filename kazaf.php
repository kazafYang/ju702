<?php
include 'common/logs1.php';
include 'common/machining_price.php';
include 'common/db_config_inc.php';
include 'common/db.php';
include 'common/kdj.php';

class Runner{
  public $config = array();
  
    function __construct() {
	//获取code，table_name配置信息
	//$this->Runner=new Runner();
	//$this->table_name=$this->Runner->get_config()['table_name'];
	//$this->code=$this->Runner->get_config()['code'];	
	  $this->table_name=$this->get_config()['table_name'];
	  $this->code=$this->get_config()['code'];
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
	$this->buy_one_price=$this->MachiningPrice->get_machining_price()['buy_one_price'];
	$this->sell_one_price=$this->MachiningPrice->get_machining_price()['sell_one_price'];  
	$this->stat_date=$this->MachiningPrice->get_machining_price()['stat_date'];
	$this->time_hour=$this->MachiningPrice->get_machining_price()['time_hour']; 
	$this->time_min=$this->MachiningPrice->get_machining_price()['time_min'];  
	//测试代码，测试方法调用  
}
  
  public function get_config(){
  $config['table_name']="point_number";
  $config['code']="159915";  
  return $config;  
  }
  
  public function sleep_time () {
	$this->log -> log_work("comming sleep_time\n");
	while(($this->time_hour==9 and $this->time_min<30) or ($this->time_hour<13 and $this->time_hour>=11) or $this->time_hour<9) {
  }
  }	  
  
 public function run () {	
 #code begin
//Runner=new Runner();
while(1==1) {
    $this->log -> log_work("开始执行程序-------------->");    
    if ($this->time_hour<9 or ($this->time_hour==9 and $this->time_min<30)) {
      $this->log -> log_work("comming mainwhile if--9\n");
      $this->Runner->sleep_time();
    }elseif (($this->time_hour=="11" and $this->time_min>="30") or ($this->time_hour>="12" and $this->time_hour<"13")){
      $this->Runner->sleep_time();  
    }elseif ($this->time_hour>="15") {
      $this->log -> log_work("3point!\n");
      //$a=$runoob->Runday_Point();  
      exit(0);    
    }
      
    $sql = "select id,stat_time_min from $this->table_name order by id desc limit 1;";    
    $result = $this->conn->query($sql);
    $row=$result->fetch_assoc();
    $this->log -> log_work("stat_time_min:$row[stat_time_min]\n");
    $stat_time_min=$row[stat_time_min];
        if ($stat_time_min<>$this->time_min and ($this->time_min==0 or $this->time_min==15 or $this->time_min==30 or $this->time_min==45)){
           $this->log -> log_work("table:$this->table_name\n");    
           $time_out_begin=($this->time_hour*3600)+($this->time_min*60)+900;
           $time_out_now=($this->time_hour*3600)+($this->time_min*60);
           $this->log -> log_work($time_out_now."\n");     
           $this->log -> log_work("当前时间".(intval($time_out_now/3600)).":".(($time_out_now%3600)/60)."\n");
           $this->log -> log_work( $time_out_begin."\n");    
           $this->log -> log_work("结束时间".(intval($time_out_begin/3600)).":".(($time_out_begin%3600)/60)."\n");
           $row[id]=$row[id]+1;   
           $sql = "insert into $this->table_name (id,stat_date,stat_time_hour,stat_time_min,begin_point,min15_k,min15_d,min30_k,min30_d,min60_k,min60_d,min120_k,min120_d,kdjday_k,kdjday_d) VALUES ('$row[id]','$this->stat_date','$this->time_hour','$this->time_min','$this->begin_point',50,50,50,50,50,50,50,50,50,50);";    
               if ($this->conn->query($sql) === TRUE) {
                    $this->log -> log_work("new inser into success $sql\n");    
                } else {
                    $this->log -> log_work("Error: " . $sql . $this->conn->error."\n");   
                }   
           nine_count();     
         }
      } 
$this->conn->close();
 }	 
}


$Runner=new Runner();
$Runner->run();
	
?>
