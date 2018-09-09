<?php
 function nine_count () {
	  global $log,$stat_time_min,$time_hour,$time_min,$time_second,$begin_point, $table_name,$time_out_begin,$conn,$buy_one_price,$sell_one_price;
	  $log -> log_work("comming nine_count,开始执行~\n");
	  machining_price();   
	  $max=$begin_point;
	  $min=$begin_point;  
	  $sql="update $table_name set min15_point_max=$max,min15_point_min=$min order by id desc limit 1 ;";
	  $conn->query($sql);
	  $time_out_now=($time_hour*3600)+($time_min*60);
	  while($time_out_now < $time_out_begin) {
	  $time_out_now=($time_hour*3600)+($time_min*60);
	  $log -> log_work("结束时间：".intval($time_out_now/3600)."：".($time_out_now%3600)/60); 	  
	  $log -> log_work("开始nice_count循环开始：time_out_now:$time_out_now~time_out_begin:$time_out_begin\n");
	  $row=result_select("select stat_time_min from $table_name order by id desc limit 1;");	  
	  $stat_time_min=$row[0];	  
	  machining_price();
	  $log -> log_work("$max--$begin_point-$stat_time_min-stat_time_min\n");	  
		  if(($time_min%15==0) and $time_min<>$stat_time_min){
		  $log -> log_work("***************************");
		  break;
		  }	  
	  if ($begin_point>=$max)
	  {
	      $max=$begin_point;
	      $log -> log_work("$max\n");
	      $sql="update $table_name set min15_point_max=$max order by id desc limit 1 ;";
	      $log -> log_work($sql."\n");
		      if ($conn->query($sql)=== TRUE)
		     {
		      $log ->log_work("max新纪录更新成功");
		       } 
		      else {
		      $log -> log_work("max新纪录更新Error: " . $sql . $conn->error."\n");
		  }
  }
	  if ($begin_point<=$min)
	  {
	      $min=$begin_point; 
	      $sql="update " .$table_name." set min15_point_min=$min order by id desc limit 1 ; ";
		      if ($conn->query($sql) === TRUE) {
		      $log -> log_work("min:新记录更新成功");
		       } 
		      else {
		      $log -> log_work("min新纪录更新Error: " . $sql . $conn->error."\n");
		  }
  } 
	  //更新买一，卖一实时价格  
	  $sql="update $table_name set now_price=$begin_point,buy_one_price=$buy_one_price,sell_one_price=$sell_one_price order by id desc limit 1 ;";
	  $conn->query($sql);
	  kdjfifteen(); #begin:kdj
	  kdjthirty();
	  kdjsixty(); 
	  two_hour();	  
	  kdjday();
	  test_cut_price();		  
	  analyse();
	  cci();
	  $log -> log_work("本次程序执行完成------------------------->\n");	  
	  } 
?>
