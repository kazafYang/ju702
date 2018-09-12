<?php
class Cut_Price{

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
	//$this->cut_Price= new Cut_Price();
}

function test_cut_price() { 	
$this->log -> log_work("comming test_cut_price\n");
$data=$this->MachiningPrice->get_machining_price();	
$sql="select * from trade_history where code=$this->code and vifi_status=0 and status=1 and trade_type>20 order by id desc;";
//echo $sql;
$result = $this->db->get_resultselect($sql);
	    while($row=mysqli_fetch_array($result)){
	    //更新交易历史表中目标价格信息
           if($data[begin_point]>$row[cut_price]){
             $sql="update trade_history set cut_price=$data[begin_point] where id=$row[id];";
             $this->db->set_update($sql);
           }
	    //计算单笔交易的盈亏情况
	   if($data[begin_point] <> "0" and $row[number] <> "0" and $row[trade_buy_price] <> "0"){	    
	   $history_make_money=(($data[begin_point]-0.001)*$row[number]-$row[trade_buy_price]*$row[number])*100; 
	   $history_make_money=round($history_make_money,3);
	   $this->log -> log_work("history_make_money:$history_make_money~number:$row[number]~trade_buy_price:$row[trade_buy_price]");	    
           $sql="update trade_history set history_make_money=$history_make_money where id=$row[id];";
           $this->db->set_update($sql);
	   }
	   else{
		$this->log -> log_work("不符合条件不更新数据，请排查：begin_point:$data[begin_point]~row[number]:$row[number]~row[trade_buy_price]:$row[trade_buy_price]");
		} 
	}
}
}
?>
