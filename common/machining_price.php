<?php
class MachiningPrice{
  var $data = array();
  /*
  function __construct() {
	//获取code，table_name配置信息
	$this->Runner=new Runner();
	$this->code=$this->Runner->get_config()['code'];	
	//测试代码，测试方法调用  
}
*/  
  function get_machining_price () {
  $code=510050;   
  $log=new logs();  
  $log -> log_work("comming machining_price");  
  if ($code<500000) {
  $url='http://hq.sinajs.cn/list=sz'.$this->code; 
  }  else{
  $url='http://hq.sinajs.cn/list=sh'.$this->code; 
  }
  do {
  $log -> log_work("开始调用接口拿数据\n");  
  $html = file_get_contents($url); 
  $pieces = explode(",", $html);
  $data['begin_point'] = $pieces[3];  //当前 
  $data['buy_one_price'] = $pieces[6]; //买一 
  $data['sell_one_price'] = $pieces[7]; //卖一 
  $data['stat_date'] = $pieces[30];  //日期
  $pieces = explode(":", $pieces[31]); 
  $data['time_hour'] = $pieces[0];  //hour
  $data['time_min'] = $pieces[1];   //min
  $data['time_second'] = $pieces[2];  //second
  } while ($html==false);
  return $data;  
  }
}
?>
