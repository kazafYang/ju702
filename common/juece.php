<?php

class Juece{
	
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
    }

    function decide_type($trade_type){
        if($trade_type>20){
	    echo "buy";
	    if($trade_type== 20 or $trade_type==25){ 	
	    echo "回转类型";	    
	    $this -> decide_runner();
	    }else{
	    echo "非回转类型";
	    } 	
        }else{
            echo "sell";
        }
    }
	
    function decide_switched(){
         $row=$this->db->get_select("select * from hive_number where code='$this->code' order by stat_date desc limit 1;");
	 print_r($row);
	 $switched=$row[switched];$sell_switched=$row[sell_switched];$return_switched=$row[return_switched];$buy_switched=$row[buy_switched];$total_money=$row[total_money];$useable_money=$row[useable_money]; $total_number=$row[total_number];$useable_sell_number=$row[useable_sell_number];$total_sell_number=$row[$total_sell_number];$cost_price=$row[cost_price];
         $this->log->log_work($switched);
	 return $row;   
    }
	
    function decide_sell_number(){
	  //获取数量  
          $data=$this->MachiningPrice->get_machining_price();
	  $this->log -> log_work("comming decide_number\n");
	  $sql="select * from trade_history where code=$this->code and vifi_status=0 and status=1 and trade_type>20 and stat_date<'$data[stat_date]' order by id asc;";
	  $result = $this->db->get_resultselect($sql);
	  while($row=mysqli_fetch_array($this->decide_sell_number())){
	      $connecttion_id=$row[id];
              $number=$row[number];   
              $this->log -> log_work("connecttion_id:$connecttion_id\n");    
	      if($begin_point>$row[trade_buy_price] and $row[cut_price] < ($row[trade_buy_price]+$row[trade_buy_price]*3.02/100)){
	          echo "这里就可以调用action了";	      
	      }
	  
	   } 
    }
	
    function decide_runner(){
       $row=$this->decide_switched();
       if ($row[switched] == 1  and $row[buy_switched] == 1){
           $this->log->log_work("进入买开关");	       
       
       }elseif($row[switched] == 1 and $row[sell_switched] == 1){
           $this->log->log_work("进入卖开关");
       }elseif($row[switched] == 1 and $row[return_switched] == 1){
           $this->log->log_work("进入回转开关");
       }else{
           $this->log->log_work("开关处于关闭状态！");
       }
    }
}

//$juece = new Juece();
//$juece -> decide_type(25);   
	
?>
