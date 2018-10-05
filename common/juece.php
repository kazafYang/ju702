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
	    $this -> decide_runner();
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
	
    function decide_number(){
         $row=$this->db->get_select("select * from hive_number where code='$this->code' order by stat_date desc limit 1;");
	 print_r($row);
	 $switched=$row[switched];$sell_switched=$row[sell_switched];$return_switched=$row[return_switched];$buy_switched=$row[buy_switched];$total_money=$row[total_money];$useable_money=$row[useable_money]; $total_number=$row[total_number];$useable_sell_number=$row[useable_sell_number];$total_sell_number=$row[$total_sell_number];$cost_price=$row[cost_price];
         $this->log->log_work($switched);
	 return $row;   
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
