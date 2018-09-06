class MachiningPrice{
  function get_machining_price () 
  {  
  global $log, $stat_date,$time_hour,$time_min,$time_second,$begin_point,$code,$buy_one_price,$sell_one_price;
  $log -> log_work("comming machining_price");  
  if ($code<500000) {
  $url='http://hq.sinajs.cn/list=sz'.$code; 
  }  else{
  $url='http://hq.sinajs.cn/list=sh'.$code; 
  }
  do {
  $log -> log_work("开始调用接口拿数据\n");   	  
  $html = file_get_contents($url); 
  $pieces = explode(",", $html);
  $begin_point=$pieces[3];
  $buy_one_price=$pieces[6];  //买一价
  $sell_one_price=$pieces[7]; //卖一价 
  $stat_date=$pieces[30];
  $pieces = explode(":", $pieces[31]);    
  $time_hour=$pieces[0];
  $time_min=$pieces[1];
  $time_second=$pieces[2];
  } while ($html==false);	  
  }
}
