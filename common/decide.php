<?php
class Decide{

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
	//测试代码，测试方法调用  
}
  public function get_kdj_score(){
  
  }
  
  public function get_avg_score(){
  
  }
  
  public function get_avg_score(){
  
  }
  
}
?>
