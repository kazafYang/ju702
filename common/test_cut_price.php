<?php
class Cut_Price{

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
	$this->cut_Price= new Cut_Price();
	$this->cut_Price->test_cut_price();
}

function test_cut_price() { 	
$log -> log_work("comming test_cut_price\n");
$sql="select * from trade_history where code=$code and vifi_status=0 and status=1 and trade_type>20 order by id desc;";
//echo $sql;
$result = $conn->query($sql);
	    while($row=mysqli_fetch_array($result)){
	    //更新交易历史表中目标价格信息
           if($begin_point>$row[cut_price]){
             $sql="update trade_history set cut_price=$begin_point where id=$row[id];";
             $conn->query($sql); 
           }
	    //计算单笔交易的盈亏情况
	   if($begin_point <> "0" and $row[number] <> "0" and $row[trade_buy_price] <> "0"){	    
	   $history_make_money=(($begin_point-0.001)*$row[number]-$row[trade_buy_price]*$row[number])*100; 
	   $history_make_money=round($history_make_money,3);
	   $log -> log_work("history_make_money:$history_make_money~number:$row[number]~~trade_buy_price:$row[trade_buy_price]");	    
           $sql="update trade_history set history_make_money=$history_make_money where id=$row[id];";
           $conn->query($sql);
	   }
		else{
		$log -> log_work("不符合条件不更新数据，请排查：begin_point:$begin_point~row[number]:$row[number]~row[trade_buy_price]:$row[trade_buy_price]");
		}    
	}
 mysqli_free_result($result);  //释放结果集	  
}
}
?>
