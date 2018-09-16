<?php
include 'common/logs1.php';
include 'common/machining_price.php';
include 'common/db_config_inc.php';
include 'common/db.php';
include 'common/kdj.php';
include 'common/analyse.php';
include 'common/decide.php';
include 'common/trade.php';
include 'common/test_cut_price.php';
include 'common/nine_count.php';




class Runner{
	
  public $config = array();
  public $data= array();
	
    function __construct() {
	//获取code，table_name配置信息	
	  $this->table_name=$this->get_config()['table_name'];
	  $this->code=$this->get_config()['code'];
	$this->db = new db();  
	//初始化log对象  
	$this->log = new logs();
	//获取实时数据  
	$this->MachiningPrice= new MachiningPrice();
}
  
  public function get_config(){
  $config['table_name']="point_number_sz";
  $config['code']="510050";  
  return $config;  
  }
  
  public function sleep_time () {
	$this->log -> log_work("comming sleep_time\n");
	while(($data[time_hour]==9 and $data[time_min]<30) or ($data[time_hour]<13 and $data[time_hour]>=11) or $data[time_hour]<9) {
	$data=$this->MachiningPrice->get_machining_price();	
  }
  }	  
  
 public function run () {	
 #code begin
//Runner=new Runner();
while(1==1) {
    $this->log -> log_work("开始执行程序-------------->");
    $data=$this->MachiningPrice->get_machining_price();	
    if ($data[time_hour]<9 or ($data[time_hour]==9 and $data[time_min]<30)) {
      $this->log -> log_work("comming mainwhile if--9\n");
      $this->sleep_time();
    }elseif (($data[time_hour]=="11" and $data[time_min]>="30") or ($data[time_hour]>="12" and $data[time_hour]<"13")){
      $this->sleep_time();  
    }elseif ($data[time_hour]>="15") {
      $this->log -> log_work("3point!\n");
      //$a=$runoob->Runday_Point();  
      exit(0);    
    }
      
    $sql = "select id,stat_time_min from $this->table_name order by id desc limit 1;";    
    $row=$this->db->get_select($sql);
    $this->log -> log_work("stat_time_min:$row[stat_time_min]\n");
    $stat_time_min=$row[stat_time_min];
        if ($stat_time_min<>$data[time_min] and ($data[time_min]==0 or $data[time_min]==15 or $data[time_min]==30 or $data[time_min]==45)){
           $this->log -> log_work("table:$this->table_name\n");    
           $time_out_begin=($data[time_hour]*3600)+($data[time_min]*60)+900;
           $time_out_now=($data[time_hour]*3600)+($data[time_min]*60);
           $this->log -> log_work($time_out_now."\n");     
           $this->log -> log_work("当前时间".(intval($time_out_now/3600)).":".(($time_out_now%3600)/60)."\n");
           $this->log -> log_work( $time_out_begin."\n");    
           $this->log -> log_work("结束时间".(intval($time_out_begin/3600)).":".(($time_out_begin%3600)/60)."\n");
           $row[id]=$row[id]+1;   
           $sql = "insert into $this->table_name (id,stat_date,stat_time_hour,stat_time_min,begin_point,min15_k,min15_d,min30_k,min30_d,min60_k,min60_d,min120_k,min120_d,kdjday_k,kdjday_d) VALUES ('$row[id]','$data[stat_date]','$data[time_hour]','$data[time_min]','$data[begin_point]',50,50,50,50,50,50,50,50,50,50);";    
           $this->db->set_insert($sql);
	     $nine = new Nine_Count();	
             $nine->nine_count($time_out_begin,$time_out_now);     
         }
      } 
 }	 
}

//$decide=new Decide();
//$decide->main();
//$Runner=new Runner();
//$Runner->run();
$trade=new Trade();
$trade->buy_action(10,1);
?>
