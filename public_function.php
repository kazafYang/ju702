<?php
include 'config_inc.php';
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
$runoob = new Decide();	

//定义决策类
class Decide {	
  /* 成员变量 */
  public $stat_date;	
  public $kdjday_k;
  public $kdjday_d;
  public $Decide_buy_status;	
//  function __construct( $par1, $par2 ) {
//    $this->url = $par1;
//    $this->title = $par2;
//  }
  /* 成员函数 */
  function setkdj($kdyday_k,$kdjday_d,$cci){
     $this->day_kdj($kdyday_k,$kdyday_d);
     $this->cci($cci);	  
  }
  #这个方法主要是统计day_point中的数据，看看近些天的涨跌幅情况；
  function Runday_Point(){
     global $conn,$log,$table_name,$stat_date;
     $log -> log_work("开启day_point数据收集更新");
	$json = file_get_contents("http://money.finance.sina.com.cn/quotes_service/api/json_v2.php/CN_MarketData.getKLineData?symbol=sz159915&scale=60&ma=15&datalen=1024");
        $json = str_replace('day','"day"',$json);
	$json = str_replace('open','"open"',$json); 
	$json = str_replace('high','"high"',$json);
	$json = str_replace('low','"low"',$json);
	$json = str_replace('close','"close"',$json);
	$json = str_replace('ma_volume15','"ma_volume15"',$json);   
	$json = str_replace('volume:','"volume":',$json); 
	$json = str_replace('ma_price15','"ma_price15"',$json);
	//$json = '[{"day":"2018-08-24 14:00:00","open":"1.380","high":"1.394","low":"1.379","close":"1.389","volume":"106915552",ma_price20:1.377,ma_"volume"20:174191587},{"day":"2018-08-24 15:00:00","open":"1.390","high":"1.391","low":"1.381","close":"1.382","volume":"71922914",ma_price20:1.378,ma_"volume"20:165794292}]'  
	$students= json_decode($json, true);//得到的是 array
	for($i=0;$i<count($students);$i++){
	if(strpos($students[$i]['day'],'15:00:00') !== false){
	    $row=result_select("select count(*) from day_point where stat_date='".$students[$i]['day']."';");
	    if($row[0] == 0){
		$row_make_bite=result_select("select * from day_point order by id desc limit 1;");
		$make_point= $students[$i]['close'] - $row_make_bite[close_price];   
		$make_point = round($make_point,3);      
		$make_bite=$make_point/$row_make_bite[close_price]*100;
		$make_bite = round($make_bite,2);         
	    	$sql = "insert into day_point (open_price,high_price,low_price,close_price,make_point,make_bite,stat_date)values (" . $students[$i]['open'] . "," . $students[$i]['high'] .",".$students[$i]['low'].",".$students[$i]['close'].",".$make_point.",".$make_bite.",'".$students[$i]['day']."');";                                                                  
	        $log -> log_work($sql."插入day_point\n");	      
	        $conn->query($sql);      
	    }
     	   else{
		$row_make_bite=result_select("select * from day_point where stat_date<'".$students[$i]['day']."' order by id desc limit 1;");
		$make_point= $students[$i]['close'] - $row_make_bite[close_price];   
		$make_point = round($make_point,3);      
		$make_bite=$make_point/$row_make_bite[close_price]*100;
		$make_bite = round($make_bite,2);  
		$log -> log_work($students[$i]['close']."~$row_make_bite[close_price]~$make_bite");  
	    	$sql = "update day_point set open_price=".$students[$i]['open'].",high_price=".$students[$i]['high'].",low_price=".$students[$i]['low'].",close_price=".$students[$i]['close'].",make_point=$make_point,make_bite=$make_bite where stat_date='".$students[$i]['day']."';";                                                                  
	        $log -> log_work($sql."更新day_point\n");	      
	        $conn->query($sql);  
	   } 
	}
	}  
  }
  	
  function day_kdj($kdjday_k,$kdjday_d){
  global $stat_date,$table_name,$conn,$log,$kdjday_k,$kdjday_d;	  
  $log -> log_work("查找历史相似day_kdj数据收集更新");	  
  //获取今日kdj数据
  //$row=result_select("select count(*) from day_point where stat_date like '2018-08-27%';");
  $sql="select stat_date,kdjday_k,kdjday_d from $table_name where stat_date<'$stat_date' and stat_time_hour=14 and stat_time_min=45 and kdjday_k>=$kdjday_k-2.5 and kdjday_k<=$kdjday_k+2.5 and kdjday_d>=$kdjday_d-2.5 and kdjday_d<=$kdjday_d+2.5 order by id desc;"; 	  
  $log -> log_work("$sql\n");    		   
  $result = $conn->query($sql);	  
 while($row=mysqli_fetch_array($result)){
    $log -> log_work("查找到的日期：$row[stat_date]~查找到的k值：$row[kdjday_k]~查找到的D值：$row[kdjday_d]\n");	 
    $stat_date=$row[stat_date]." 15:00:00";	 
  //$stat_date=strtotime("$row[stat_date] +2 day");
  //$stat_date=date("Y-m-d 15:00:00",$row[stat_date]);
  $row_a=result_select("select count(*) from day_point where stat_date='$stat_date';");	 
   if($row_a[0]==1){
  $row=result_select("select sum(make_bite) from day_point where id IN (select x.id from ( select id from day_point where stat_date>='$stat_date' order by id asc LIMIT 2) as x);");	   
  $row_fast=result_select("select make_bite from day_point where id IN (select x.id from ( select id from day_point where stat_date='$stat_date' order by id asc LIMIT 1) as x);");
  $row_last=result_select("select make_bite from day_point where id IN (select x.id from ( select id from day_point where stat_date>'$stat_date' order by id asc LIMIT 1) as x);");
  $log ->log_work($row_fast[0]."~".$row_last[0]."\n");
  $stat_date_day=date("Y-m-d")." 15:00:00";	   
  $sql = "update day_point set forecast_today=$row_fast[make_bite],forecast_tomorrow=$row_last[make_bite] where stat_date='$stat_date_day';";
  $log ->log_work("开始更新预测的值：$sql\n");
  $conn->query($sql);	   
  $total_bite=$total_bite+$row[0];
  $log ->log_work("总计计算结果：$total_bite\n");
  if($row_fast[0]>=0 and $row_last[0]>=0){
   $log ->log_work("今明两天天气好\n");
  $number=$number+2;	  
  }elseif($row_fast[0]>=0 or $row_last[0]>=0){
   $log ->log_work("今明两天有一天天气好\n");
  $number=$number+1;	  
  }else{
   $log ->log_work("今明两天天气不好\n");
  $number=$number+0;
  }
 }
	 }
 $log ->log_work($number."number\n");	 
 mysqli_free_result($result);  //释放结果集	
 return $total_bite;	  
  }
//这里还需要修改一下  
  function Get_Decide($total_bite){
     global $log;	  
     $log ->log_work("获取数据分析结果\n");	  
     #获取今日cci数据	  
     if($total_bite>0){
     $Decide_buy_status=1;
     }
     //elseif($total_bite>0 and $total_bite<1){
     //echo "total_bite>0 <1";
     //}
     else{
     $Decide_buy_status=0;
     }
     return $Decide_buy_status;	  
  }

  function cci($cci){
  global $stat_date,$table_name,$conn,$log,$cci;	  
  $log -> log_work("进入cci分析\n");	  
  //获取今日kdj数据
  //$row=result_select("select count(*) from day_point where stat_date like '2018-08-27%';");
  $sql="select stat_date from $table_name where stat_date<'$stat_date' and stat_time_hour=14 and stat_time_min=45 and cci>=$cci-10 and cci<=$cci+10 order by id desc limit 5;"; 	  
$log -> log_work($sql."\n");  
$result = $conn->query($sql);	  
 while($row=mysqli_fetch_array($result)){
     print_r($row);	 
    $stat_date=$row[stat_date]." 15:00:00";	 
  //$stat_date=strtotime("$row[stat_date] +2 day");
  //$stat_date=date("Y-m-d 15:00:00",$row[stat_date]);
    $row_a=result_select("select count(*) from day_point where stat_date='$stat_date';");	 
  if($row_a==1){	 
  $row=result_select("select sum(make_bite) from day_point where id IN (select x.id from ( select id from day_point where stat_date>='$stat_date' order by id asc LIMIT 2) as x);");
  //$row=result_select("select sum(make_bite) from day_point where stat_date>='$row[stat_date]' and stat_date<='$stat_date';");	 
  //echo "计算结果：$row[0]\n";
  $total_bite=$total_bite+$row[0];	  
  $log -> log_work("总计cci计算结果：$total_bite\n");	 
  }
	 }
 mysqli_free_result($result);  //释放结果集	
 return $total_bite;	  
}	
	
}  //类结束位置 trend

  function machining_price () 
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

function result_select($sql){
	global $conn,$log;
	$log -> log_work("result_select函数执行的sql：$sql\n");
	$result = $conn->query($sql);
	$row=mysqli_fetch_array($result);
	return $row;
	mysqli_free_result($result);  //释放结果集
}

function table_id ($conn,$table_name) {
	global $log;
	$sql = "select id from $table_name order by id desc limit 1;"; //where status=0 and stat_date='$stat_date'
	$log -> log_work("查询下一个插入数据的id，sql语句为：$sql\n");
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	return $row[id]+1;
	mysqli_free_result($result);  //释放结果集		  
}
function sleep_time () {
	global $log,$time_hour,$time_min; 
	$log -> log_work("comming sleep_time\n");
	while(($time_hour==9 and $time_min<30) or ($time_hour<13 and $time_hour>=11) or $time_hour<9) {
	machining_price();
}
		 }
  function kdjfifteen () {
  global $log,$begin_point,$conn,$table_name;
  machining_price();
  $row=result_select("select max(min15_point_max) from (select * from $table_name order by id desc limit 9) as a;");	  
  $min15_point_max=$row[0];
  $row=result_select("select min(min15_point_min) from (select * from $table_name order by id desc limit 9) as a;");	  
  $min15_point_min=$row[0];
  $log -> log_work("min15_point_min:$min15_point_min");
  $row=result_select("select min15_k,min15_d from $table_name order by id  desc  limit 1,1;");	  
  $min15_k=$row[min15_k];   
  $min15_d=$row[min15_d];
  $log -> log_work("begin_point:$begin_point~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~min15_k:$min15_k~min15_d:$min15_d\n");                      
  $rsv=($begin_point-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
  $log -> log_work("rsv:$rsv");
  $k=2/3*$min15_k+1/3*$rsv;
  $d=2/3*$min15_d+1/3*$k;
  $j=3*$k-2*$d;
  $log -> log_work("15kdj:$k,$d,$j\n");  	  
  $sql="update $table_name set min15_k='$k' , min15_d='$d' , min15_j='$j' order by id desc limit 1 ; ";
     if ($conn->query($sql) === TRUE) 
     {
      $log -> log_work("15kdjupdate:success\n");	     
       } 
    else {
      $log -> log_work("maxError: " . $sql . $conn->error."\n"); 	    
  }  
  }
  function kdjthirty () {
  global $log, $begin_point,$conn,$table_name,$time_hour,$time_min;
  machining_price();
  $row=result_select("select max(min15_point_max) from (select * from $table_name order by id desc limit 18) as a;");
  $min15_point_max=$row[0];
  $row=result_select("select min(min15_point_min) from (select * from $table_name order by id desc limit 18) as a;");	  
  $min15_point_min=$row[0];
  if (($time_hour==9 and $time_min==30) or ($time_hour==10 and $time_min==0) or ($time_hour==10 and $time_min==30) or ($time_hour==11 and $time_min==0) or ($time_hour==13 and $time_min==0) or ($time_hour==13 and $time_min==30) or ($time_hour==14 and $time_min==0)) {
  $row=result_select("select min30_k,min30_d from $table_name order by id  desc  limit 1,1;");	  
  $min30_k=$row[min30_k]; 
  $min30_d=$row[min30_d];
  }
  else {
  $row=result_select("select min30_k,min30_d from $table_name order by id  desc  limit 2,1;");	  
  $min30_k=$row[min30_k]; 
  $min30_d=$row[min30_d];
  }  
  $log -> log_work("begin_point:$begin_point~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~min30_k:$min30_k~min30_d:$min30_d\n");                       
  $rsv=($begin_point-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
  $k=2/3*$min30_k+1/3*$rsv;
  $d=2/3*$min30_d+1/3*$k;
  $j=3*$k-2*$d;
  $log -> log_work("30kdj:$k,$d,$j\n");	  
  $sql="update $table_name set min30_k='$k' , min30_d='$d' , min30_j='$j' order by id desc limit 1 ; ";
     if ($conn->query($sql) === TRUE) 
     {
      $log -> log_work("30kdjupdate:success\n");
       } 
    else {
      $log -> log_work("maxError: " . $sql . $conn->error."\n");
  }  
  }  
  function kdjsixty () {	  
  global $log, $begin_point,$conn,$table_name,$time_hour,$time_min;
  $log -> log_work("comming kdjsixty");		  
  machining_price();
  $row=result_select("select max(min15_point_max) from (select * from $table_name order by id desc limit 36) as a;");	  
  $min15_point_max=$row[0];
  $row=result_select("select min(min15_point_min) from (select * from $table_name order by id desc limit 36) as a;");	  
  $min15_point_min=$row[0];
  $log -> log_work("time_hour:$time_hour\n");
  if (($time_hour==9 and $time_min==30) or ($time_hour==10 and $time_min==30) or ($time_hour==13 and $time_min==0) or ($time_hour==14 and $time_min==0)) {
  $row=result_select("select min60_k,min60_d from $table_name order by id  desc  limit 1,1;");	  
  $min60_k=$row[min60_k];
  $min60_d=$row[min60_d];
  }
  elseif (($time_hour==9 and $time_min==45) or ($time_hour==10 and $time_min==45) or ($time_hour==13 and $time_min==15) or ($time_hour==14 and $time_min==15)) {
  $row=result_select("select min60_k,min60_d from $table_name order by id  desc  limit 2,1;");	  
  $min60_k=$row[min60_k];
  $min60_d=$row[min60_d];
  }
  elseif(($time_hour==10 and $time_min==0) or ($time_hour==11 and $time_min==0) or ($time_hour==13 and $time_min==30) or ($time_hour==14 and $time_min==30)) {
  $row=result_select("select min60_k,min60_d from $table_name order by id  desc  limit 3,1;");	  
  $min60_k=$row[min60_k];
  $min60_d=$row[min60_d];
  }
  else{
  $row=result_select("select min60_k,min60_d from $table_name order by id  desc  limit 4,1;");	  
  $min60_k=$row[min60_k];
  $min60_d=$row[min60_d];
  }
  $log -> log_work("begin_point:$begin_point~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~min60_k:$min60_k~min60_d:$min60_d\n");   
  $rsv=($begin_point-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
  $k=2/3*$min60_k+1/3*$rsv;
  $d=2/3*$min60_d+1/3*$k;
  $j=3*$k-2*$d;
  $log -> log_work("60kdj:$k,$d,$j\n");
  $sql="update $table_name set min60_k='$k' , min60_d='$d' , min60_j='$j' order by id desc limit 1 ; ";
     if ($conn->query($sql) === TRUE) 
     {
      $log -> log_work("60kdjupdate:success\n");
       } 
    else {
      $log -> log_work("60kdjError: " . $sql . $conn->error."\n");
  }  
  }
	//kdj 120min
   function two_hour () {
  global $log, $begin_point,$conn,$table_name,$stat_date,$time_hour,$time_min;
   $log -> log_work("comming two_hour");		   
  machining_price();
  $row=result_select("select max(min15_point_max) from (select * from $table_name order by id desc limit 72) as a;");	   
  $min15_point_max=$row[0];
  $row=result_select("select min(min15_point_min) from (select * from $table_name order by id desc limit 72) as a;");	   
  $min15_point_min=$row[0];
  if ($time_hour<13) {
  $row=result_select("select min120_k,min120_d from $table_name where stat_date<'$stat_date' order by id  desc  limit 0,1;");	  
  $min120_k=$row[min120_k];
  $min120_d=$row[min120_d];
  }
  elseif ($time_hour>=13) {
  $row=result_select("select min120_k,min120_d from $table_name where stat_time_hour='11' and stat_time_min='15' order by id  desc  limit 1;");	  
  $min120_k=$row[min120_k];
  $min120_d=$row[min120_d];
  }
  $log -> log_work("begin_point:$begin_point~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~\n");   
  $rsv=($begin_point-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
  $k=2/3*$min120_k+1/3*$rsv;
  $d=2/3*$min120_d+1/3*$k;
  $j=3*$k-2*$d;
  $log -> log_work("120kdj:$k,$d,$j\n");
  $sql="update $table_name set min120_k='$k' , min120_d='$d' , min120_j='$j' order by id desc limit 1 ; ";
     if ($conn->query($sql) === TRUE) 
     {
      $log -> log_work("120kdjupdate:success\n");
       } 
    else {
      $log -> log_work("120kdjError: " . $sql . $conn->error."\n");
  }  
  }
	  //day kdj
  function kdjday () {
  global $log, $begin_point,$conn,$table_name,$stat_date,$kdjday_k,$kdjday_d;
  machining_price();
  $row=result_select("select max(min15_point_max) from (select * from $table_name order by id desc limit 144) as a;");	  
  $min15_point_max=$row[0];
  $row=result_select("select min(min15_point_min) from (select * from $table_name order by id desc limit 144) as a;");	  
  $min15_point_min=$row[0];
  $row=result_select("select kdjday_k,kdjday_d from $table_name where stat_date<'$stat_date' order by id desc limit 1;");	  
  $kdjday_k=$row[kdjday_k]; 
  $kdjday_d=$row[kdjday_d];
  $log -> log_work("begin_point:$begin_point~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~kdjday_k:$kdjday_k~kdjday_d:$kdjday_d\n");                       
  $rsv=($begin_point-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
  $k=2/3*$kdjday_k+1/3*$rsv;
  $d=2/3*$kdjday_d+1/3*$k;
  $j=3*$k-2*$d;
  $log -> log_work("daykdj:$k,$d,$j\n");
  $sql="update $table_name set kdjday_k='$k' , kdjday_d='$d' , kdjday_j='$j' order by id desc limit 1 ; ";
     if ($conn->query($sql) === TRUE) 
     {
      $log -> log_work("daykdjupdate:success\n");	     
       } 
    else {
      $log -> log_work("daykdj:updateError: " . $sql . $conn->error."\n");	    
  }  
  }
	  // cci count
  function cci () {
  global $log, $conn,$table_name; 
  $log -> log_work("comming cci\n");	  
  $min15_point_max= array();
  $min15_point_min = array();
  $now_price = array();
  
  for ($i=1;$i<15;$i++){
  $row=result_select("select max(min15_point_max) from (select * from $table_name order by id desc limit $i,1) as a;");	  
  $date_point_max=$row[0];
  $row=result_select("select min(min15_point_min) from (select * from $table_name order by id desc limit $i,1) as a;");	  
  $date_point_min=$row[0];
  $row=result_select("select now_price from $table_name order by id desc limit 1,$i;");	  
  $date_now_price=$row[0];
  $min15_point_max[]=$date_point_max;
  $min15_point_min[]=$date_point_min;
  $now_price[]=$date_now_price;
  } 
  $typ1=($min15_point_max[0]+$min15_point_min[0]+$now_price[0])/3;
  $typ2=($min15_point_max[1]+$min15_point_min[1]+$now_price[1])/3;
  $typ3=($min15_point_max[2]+$min15_point_min[2]+$now_price[2])/3;
  $typ4=($min15_point_max[3]+$min15_point_min[3]+$now_price[3])/3;
  $typ5=($min15_point_max[4]+$min15_point_min[4]+$now_price[4])/3;
  $typ6=($min15_point_max[5]+$min15_point_min[5]+$now_price[5])/3;
  $typ7=($min15_point_max[6]+$min15_point_min[6]+$now_price[6])/3;
  $typ8=($min15_point_max[7]+$min15_point_min[7]+$now_price[7])/3;
  $typ9=($min15_point_max[8]+$min15_point_min[8]+$now_price[8])/3;
  $typ10=($min15_point_max[9]+$min15_point_min[9]+$now_price[9])/3;
  $typ11=($min15_point_max[10]+$min15_point_min[10]+$now_price[10])/3;
  $typ12=($min15_point_max[11]+$min15_point_min[11]+$now_price[11])/3;
  $typ13=($min15_point_max[12]+$min15_point_min[12]+$now_price[12])/3;
  $typ14=($min15_point_max[13]+$min15_point_min[13]+$now_price[13])/3;
  $matyp=($typ1+$typ2+$typ3+$typ4+$typ5+$typ6+$typ7+$typ8+$typ9+$typ10+$typ11+$typ12+$typ13+$typ14)/14;
  $aytyp=(abs($typ1-$matyp)+abs($typ2-$matyp)+abs($typ3-$matyp)+abs($typ4-$matyp)+abs($typ5-$matyp)+abs($typ6-$matyp)+abs($typ7-$matyp)+abs($typ8-$matyp)+abs($typ9-$matyp)+abs($typ10-$matyp)+abs($typ11-$matyp)+abs($typ12-$matyp)+abs($typ13-$matyp)+abs($typ14-$matyp))/14;
  $cci=($typ1-$matyp)/$aytyp/0.015;
  $sql="update $table_name set cci='$cci' order by id desc limit 1 ; ";
     if ($conn->query($sql) === TRUE) {
      $log -> log_work("cci:新记录更新成功\n");
       } 
    else {
      $log -> log_work("cci新纪录更新Error: " . $sql . $conn->error."\n");
  }
  }  
function test_cut_price() { 	
global $log,$conn,$code,$begin_point,$stat_date,$time_hour,$time_min;//,$begin_point;	
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
function analyse () {
    global $log,$table_name,$code,$conn,$begin_point,$stat_date,$time_hour,$time_min,$useable_money;
      $log -> log_work("comming analyse\n");
      //五日十日均线数据计算	
      $row=result_select("select avg(now_price) from (select now_price from $table_name order by id desc limit 0,80) as a;");	
      $first_min5_avgprice=$row[0];
      $row=result_select("select avg(now_price) from (select now_price from $table_name order by id desc limit 0,160) as a;");	
      $first_min10_avgprice=$row[0];	   
      $row=result_select("select avg(now_price) from (select now_price from $table_name order by id desc limit 16,80) as a;");	
      $second_min5_avgprice=$row[0];
      $row=result_select("select avg(now_price) from (select now_price from $table_name order by id desc limit 16,160) as a;");	
      $second_min10_avgprice=$row[0];
      //获取分时kdj数据;
      $row=result_select("SELECT code,stat_date,stat_time_hour,stat_time_min,min15_k,min15_d,min15_j,min30_k,min30_d,min30_j,min60_k,min60_d,min60_j,min120_k,min120_d,min120_j,kdjday_k,kdjday_d,kdjday_j,cci,buy_one_price,sell_one_price FROM $table_name order by id desc limit 1");	
      $trade_code=$row[code];$trade_buy_price=$row[buy_one_price];$trade_sell_price=$row[sell_one_price];
      $trade_stat_date=$row[stat_date];$trade_time_hour=$row[stat_time_hour];$trade_time_min=$row[stat_time_min];
      $trade_min15_k=round($row[min15_k],2);$trade_min15_d=round($row[min15_d],2);$trade_min15_j=round($row[min15_j],2);
      $trade_min30_k=round($row[min30_k],2);$trade_min30_d=round($row[min30_d],2);$trade_min30_j=round($row[min30_j],2);
      $trade_min60_k=round($row[min60_k],2);$trade_min60_d=round($row[min60_d],2);$trade_min60_j=round($row[min60_j],2);
      $trade_min120_k=round($row[min120_k],2);$trade_min120_d=round($row[min120_d],2);$trade_min120_j=round($row[min120_j],2);	  
      $trade_day_k=round($row[kdjday_k],2);$trade_day_d=round($row[kdjday_d],2);$trade_day_j=round($row[kdjday_j],2);	  
      //获取上一次分时kdj数据用来判断kdj金叉死叉;
      $row=result_select("SELECT code,stat_date,stat_time_hour,stat_time_min,min15_k,min15_d,min15_j,min30_k,min30_d,min30_j,min60_k,min60_d,min60_j,min120_k,min120_d,min120_j,kdjday_k,kdjday_d,kdjday_j,cci,buy_one_price,sell_one_price FROM $table_name order by id desc limit 1,1");
      $trade_second_min15_k=round($row[min15_k],2);$trade_second_min15_d=round($row[min15_d],2);$trade_second_min15_j=round($row[min15_j],2);
      $trade_second_min30_k=round($row[min30_k],2);$trade_second_min30_d=round($row[min30_d],2);$trade_second_min30_j=round($row[min30_j],2);
      $trade_second_min60_k=round($row[min60_k],2);$trade_second_min60_d=round($row[min60_d],2);$trade_second_min60_j=round($row[min60_j],2);
      $trade_second_min120_k=round($row[min120_k],2);$trade_second_min120_d=round($row[min120_d],2);$trade_second_min120_j=round($row[min120_j],2);	  
      $trade_second_day_k=round($row[kdjday_k],2);$trade_second_day_d=round($row[kdjday_d],2);$trade_second_day_j=round($row[kdjday_j],2); 		  
     //获取bite倍数信息，给各个阶段加倍
      $str="";
      $switched=0; 	  
      $sql = "select bite as a from trade_bate  order by id asc ;";    
      $result=mysqli_query($conn,$sql);
      while ($row=mysqli_fetch_row($result))
    {
	$a = sprintf("%s,%s",$row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13],$row[14],$row[15],$row[16],$row[17],$row[18]);
	$str=$str.$a;    
    }
      $pieces = explode(",", $str);
      $type1=$pieces[0];$type2=$pieces[1];$type3=$pieces[2];$type4=$pieces[3];$type5=$pieces[4];$type6=$pieces[5];$type7=$pieces[6];$type8=$pieces[7];$type9=$pieces[8];$type10=$pieces[9];$type11=$pieces[10];
      $type21=$pieces[11];$type22=$pieces[12];$type23=$pieces[13];$type24=$pieces[14];$type25=$pieces[15];$type26=$pieces[16];$type27=$pieces[17];$type28=$pieces[18];
      $log -> log_work($type1.$type2.$type3.$type4.$type5.$type6.$type7.$type8."trade_bate\n");
      mysqli_free_result($result);  //释放结果集
      //判断当日数据是否已经存在
      $row=result_select("select count(*) from hive_number where code='$trade_code' and stat_date='$trade_stat_date';");	
      $log -> log_work("当日数据是否存在判断\n");	
      if($row[0]==0){
	      $log -> log_work("当日数据不存在，开始新增数据\n");
	      //拿取hive_number表数据条数获得插入的下一个id
	      $hive_number_id=table_id($conn,"hive_number");
	      $log -> log_work("拿取到的hive_number_id：$hive_number_id.\n");	      
	      $row=result_select("select switched,sell_switched,buy_switched,total_money,useable_money,total_number,useable_sell_number,total_sell_number,market_value,cost_price,make_money from hive_number where code='$trade_code' order by stat_date desc limit 1;");	      
	      $switched=$row[switched];$sell_switched=$row[sell_switched];$buy_switched=$row[buy_switched];$total_money=$row[total_money];$useable_money=$row[useable_money]; $total_number=$row[total_number];$useable_sell_number=$row[total_number];$total_sell_number=$row[total_number];$cost_price=$row[cost_price];$make_money=$row[make_money];$market_value=$row[market_value];
	      $log -> log_work($switched.$sell_switched.$buy_switched."开关\n");	      
	      //计算最近2日的平均买入成本   switch
	      $cost_stat_date=date("Y-m-d",strtotime("-2 day"));    
	      $row=result_select("select avg(trade_sell_price) from trade_history where code='$trade_code' and trade_type>=5 and stat_date>='$cost_stat_date';");
	      $cost_price=round($row[0],3);
	      $sql = "insert into hive_number values ('$hive_number_id','$trade_code','$switched','$sell_switched','$buy_switched','$total_money','$useable_money','$total_number','$useable_sell_number','$total_sell_number','$market_value','$cost_price','$make_money','$trade_stat_date');";                                                                  
	      $log -> log_work($sql."插入hive_number\n");	      
	      $conn->query($sql);   
      } 
      else{
      //拿取hive_number的基础属性
	      $log -> log_work("当日hive_number已经存在，开始获取最新hive_number数据\n");		  
	      $switched.$sell_switched.$buy_switched."开关\n";		   
	      $row=result_select("select switched,sell_switched,buy_switched,total_money,useable_money,total_number,useable_sell_number,total_sell_number,cost_price from hive_number where code='$trade_code' order by stat_date desc limit 1;");
	      $switched=$row[switched];$sell_switched=$row[sell_switched];$buy_switched=$row[buy_switched];$total_money=$row[total_money];$useable_money=$row[useable_money]; $total_number=$row[total_number];$useable_sell_number=$row[useable_sell_number];$total_sell_number=$row[$total_sell_number];$cost_price=$row[cost_price];
      }
  $log -> log_work($switched."开始判断\n");	
  if($switched==1){
  $log -> log_work($switched."判断开关开启了\n");		  
      //sell判断
 //判断当前code是否具备卖出资格，后续可以在这里加上开关等限制性的行为；昨日的总数量，就是今日的可卖数量；$switched=1是开关打开状态
    if($useable_sell_number>1 and $sell_switched==1 and ($trade_day_k >= 85 or $trade_day_d >= 80)){ 
    //if($useable_sell_number>=1 and $sell_switched==1 and ($trade_day_k >= 1 or $trade_day_d >= 1)){ 		    
      //超买情况下的15分钟卖出指标
      $log -> log_work("comming $trade_min15_k daykdj $trade_min15_d-sell"."\n");
      if($trade_min15_k>=85 or $trade_min15_d>=80)
    //  if($trade_min15_k>=1 or $trade_min15_d>=1)	      
      {
	$log -> log_work("comming日线超买开始 daykdj-sell-15\n");
	$trade_type=1;    
	sell_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price);  		      
      } 
  //30min  
     if($trade_min30_k>=85 or $trade_min30_d>=80)	 
     {
	$log -> log_work("comming daykdj-sell-30\n");
	$trade_type=2;    
	sell_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price);  		     
      } 
   //60分钟          
     if($trade_min60_k>=85 or $trade_min60_d>=80)
     {
	$log -> log_work("comming daykdj-sell-60\n");
	$trade_type=3;    
	sell_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price);  		     
      }
	    //120分钟          
     if($trade_min120_k>=85 or $trade_min120_d>=80)	 
     {
	$log -> log_work("comming daykdj-sell-120\n");
	$trade_type=4;    
	sell_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price);  		     
      }    
    } //日线超买完成
    //buy,买入开关限制，限制可用金额不足的情况，和标的开关关闭的情况，关闭 switch=0；
  if($useable_money>1000 and $buy_switched==1 and $trade_day_k < 20 and $trade_day_d < 20){
  //if(1==1){
	echo "comming日线超卖开始 switch-buy~~$buy_switched~~~day-$trade_day_k-kdj~~$trade_day_d~~$useable_money"."\n"; 
	  //15分钟条件严格一点
    if ($trade_min15_k <=15 or $trade_min15_d <=20){
    //if ($trade_min15_k <=95 or $trade_min15_d <90){
       $log -> log_work("comming switch-buy~~~~~day-kdj-min15~~$trade_min15_k~$trade_min15_d~"."\n"); 
       $trade_type=21; 
       $trade_bite=$type21;	    
       buy_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price,$trade_bite);
      }  
    if ($trade_min30_k <=15 or $trade_min30_d <=20){
       $log -> log_work("comming switch-buy~~~~~day-kdj-min30~$trade_min30_k~$trade_min30_d~~"."\n"); 
       $trade_type=22; 
       $trade_bite=$type22;	    
       buy_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price,$trade_bite);
      }   
    if ($trade_min60_k <=15 or $trade_min60_d <=20){ 
       $log -> log_work("comming switch-buy~~~~~day-kdj-min60~$trade_min60_k~$trade_min60_d~~"."\n"); 
       $trade_type=23; 
       $trade_bite=$type23;	    
       buy_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price,$trade_bite);
      }
	  //120日线超卖
      if ($trade_min120_k <=15 or $trade_min120_d <=20){
       $log -> log_work("comming switch-buy~~~~~day-kdj-min120~$trade_min120_k~$trade_min120_d~~"."\n"); 
       $trade_type=24; 
       $trade_bite=$type24;	    
       buy_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price,$trade_bite);
      }  	  
  }    //日线超卖完成
  $log -> log_work("回转sell开始\n");	  
  if((($trade_day_k>=40 and $trade_day_k<65) or ($trade_day_d>=40 and $trade_day_d<65)) and $useable_sell_number>1 and $sell_switched==1){
    //回转交易策略的位置,记录回转交易的标志是数据库字段status=2
	//15分钟回转使用死叉交易卖出 switch
	$log -> log_work("comming 回转交易开始-sell~~~~$trade_day_k~~~$trade_day_d~~"."\n");
    if(($trade_min15_k>=75 or $trade_min15_d >= 75) and $trade_min15_j < $trade_min15_k and $trade_min15_j < $trade_min15_d){
	$log -> log_work("comming huizhuan-rel-sell~~dead~~$trade_min15_k~~~$trade_min15_d~$trade_min15_j~"."\n");
	$trade_type=5;    
	huizhuan_sell_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price);     
  }    
	  //回转15分钟超买条件
       if($trade_min15_k>=80 or $trade_min15_d >= 80){
	$log -> log_work("comming huizhuan-rel-sell~~min15~~~~$trade_min15_k~~$trade_min15_d~"."\n");
	$trade_type=6;    
	huizhuan_sell_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price);  		       
  }	  
     if($trade_min30_k >= 80  or $trade_min30_d >= 80){
	$log ->log_work("comming huizhuan-rel-sell~~min30~~~$trade_min30_k~~$trade_min30_d~~"."\n");
	$trade_type=7;    
	huizhuan_sell_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price);  
	  }
    if($trade_min60_k >= 80  or $trade_min60_d >= 80){
	$log -> log_work("comming huizhuan-rel-sell~~min60~~$trade_min60_k~$trade_min60_d~~~~"."\n");
	$trade_type=8;    
	huizhuan_sell_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price);  
	  }
      if($trade_min120_k >= 80  or $trade_min120_d >= 80){
	$log -> log_work("comming huizhuan-rel-sell~~min120~$trade_min120_k~~~$trade_min120_d~~"."\n");
	$trade_type=9;    
	huizhuan_sell_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price);  
	  }
    } //卖出回转结束
	 //回转买入开始 
	//回转60分钟买入
$log -> log_work("回转buy开始\n");	  
 if((($trade_day_k>=20 and $trade_day_k<65) or ($trade_day_d>=20 and $trade_day_d<65)) and $useable_money>1000 and $buy_switched==1){	  
      $log ->log_work("huizhuan_buy:60mink=$trade_min60_k,60mind=$trade_min60_d,dayk:$trade_day_k,dayd=$trade_day_d\n");	  
      if (($trade_min60_k <20 or $trade_min60_d <20) and ($trade_day_k<65 and $trade_day_d<65)){
       $log -> log_work("回转买入开始:60mink=$trade_min60_k,60mind=$trade_min60_d,dayk:$trade_day_k,dayd=$trade_day_d\n");	      
       $log -> log_work("comming -huizhuan-buy~~min60~~~~~~"."\n");  
       $trade_type=25; 
       $trade_bite=$type25;	    
       buy_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price,$trade_bite);
      }
	//回转买入，当前价低于最低卖出价5个点，即可等量/分批加码回收筹码；增加trade_type，标志回转交易，然后沿用status标志，这样比较好；如果这样的话不能判断出数据是否已经被处理了，所以我还需要一个步骤就是将已经对比的status的值=2;
       //判断已经交易完成的，然后处理结束后将status变更为2   
      $row=result_select("select id,number,trade_sell_price,trade_type from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and trade_type in(5,6,7,8,9) and status=1 limit 1;");
      $number=$row[number];
      $number=round($number); 	  
      switch ($row[trade_type])
      {
       case 5:
	     $loser_price=$row[trade_sell_price]-($row[trade_sell_price]*2/100); //回转15分钟死叉
	     $loser_price=round($loser_price,3);
       break;
       case 6:
	     $loser_price=$row[trade_sell_price]-($row[trade_sell_price]*1/100); //回转15分钟超买
	     $loser_price=round($loser_price,3);
       break;
       case 7:
	     $loser_price=$row[trade_sell_price]-($row[trade_sell_price]*1.5/100); //回转30分钟超买
	     $loser_price=round($loser_price,3);	       
       break;
       case 8:
	     $loser_price=$row[trade_sell_price]-($row[trade_sell_price]*2/100); //回转60分钟超买
	     $loser_price=round($loser_price,3);	      
       break;
       case 9:
	     $loser_price=$row[trade_sell_price]-($row[trade_sell_price]*3/100); //回转120分钟超买
	     $loser_price=round($loser_price,3);	      
       break;
       default:
       $log -> log_work("No number 超买");
       }  
      $loser_price_id=$row[id];  
      $log -> log_work($loser_price_id."loser_price".$loser_price."~~~~~".$row[trade_sell_price]."\n");
      //发出回转交易买入信号，向交易库插入交易数据信息，回转交易买入也需要一个trade_type；  
     $log -> log_work($loser_price."comming switch-rel-buy~~$useable_money~~~~~~~".$trade_buy_price."\n"); 
     if ($loser_price>0 and $buy_switched==1 and $useable_money>1000 and ($trade_day_k<80 and $trade_day_d<75)){
       $log -> log_work("coming huizhuan_buy:$loser_price~$buy_switched~$useable_money~$trade_day_k~$trade_day_k\n");
       $trade_type=26; 
       $trade_bite=$type26;
       $trade_id=table_id($conn,"trade_history");
       $cut_price=$loser_price+($loser_price*3/100);	     
       $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,vifi_status,number,trade_type,trade_buy_price,trade_sell_price,cut_price,connecttion_id) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','0','$number','$trade_type','$loser_price','$loser_price','$cut_price','$loser_price_id');";
       $conn->query($sql);
       $sql = "update trade_history set status=2 where id=$loser_price_id;";                                                                  
       $conn->query($sql);    
      }
 }
$log -> log_work("cut_sell_开始\n");		  
//cut_price卖出开始开始	  
if(($trade_day_k>=20 and $trade_day_k<85) or ($trade_day_d>=20 and $trade_day_d<80) and $useable_sell_number>1){
  	
  $sql="select * from trade_history where code=$code and vifi_status=0 and status=1 and trade_type>20 and stat_date<'$stat_date' order by id desc;";
  //$row=result_select("select * from trade_history where code=$code and vifi_status=0 and status=1 and trade_type>20 and stat_date<'$stat_date' order by id desc;");
  $result = $conn->query($sql);
  while($row=mysqli_fetch_array($result)){
  //while($row){	  
   //故意将比例调高到3.02避免cut_price因为四舍五入取整后造成判断失效的问题，也可以将取出来的数据也取整，但是个人感觉这样更简单一点	  
   if($begin_point <= ($row[cut_price]-$row[cut_price]*1/100) and $row[cut_price] > ($row[trade_buy_price]+$row[trade_buy_price]*3.02/100) and $row[trade_buy_price] <> "0" and $begin_point>$row[trade_buy_price]){
	$trade_type=10;
	$log -> log_work ("cut_price开始".$row[id]."~".$row[cut_price]."~".$begin_point."~".$row[trade_buy_price]."~".$row[number]."\n");
	$log -> log_work($code."~".$begin_point."~".$stat_date."\n");
	$trade_id=table_id($conn,"trade_history");
	$log -> log_work("trade_id:$trade_id");	   
	//插入交易历史  
	$sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,vifi_status,number,trade_type,trade_buy_price,trade_sell_price,cut_price,connecttion_id,history_make_money) values ('$trade_id','$code','$stat_date','$time_hour','$time_min','0','0','$row[number]','$trade_type','$row[trade_buy_price]','$begin_point','0','$row[id]',$row[history_make_money]);";                                                                  
	$log -> log_work("$sql:cut_price sell 处理了！！！！\n");
	$conn->query($sql);
	//核销已经处理的前期订单，避免订单再次进入    
	$sql = "update trade_history set connecttion_id='$trade_id',vifi_status='1' where id='$row[id]';";
	$log ->log_work("$sql~cut_price 核销订单sql\n");
	$conn->query($sql);
    }
    else{
         $log -> log_work("不符合cut_price函数条件，不执行!begin_point:$begin_point,cut_price:$row[cut_price],trade_buy_price:$row[trade_buy_price]");
    } 	  
}
   mysqli_free_result($result);  //循环结束释放结果集  
	
}
              
/*	      //5日线非分钟线金叉吸入筹码	  
	      if(($first_min5_avgprice>$first_min10_avgprice) and ($second_min5_avgprice<$second_min10_avgprice) and $trade_day_k<50){
	      $number=11/$trade_buy_price*$type27;
	      $number=round($number); 
	      $row=result_select("select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=27;");
	      if($row[0]==0 and $buy_switched==1 and $useable_money>=($number*100*$trade_buy_price)){
	      $trade_id=table_id($conn,"trade_history");	      
	      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','27','$trade_buy_price','$trade_sell_price');";    
	      echo $sql."comming -min5-avg-buy-sql~~~~~~~~~"."\n";                                                                  
	      $conn->query($sql);
	      }
	      } //金叉结束
		//5日线非分钟线死叉抛出筹码	  
	      if(($first_min5_avgprice<$first_min10_avgprice) and ($second_min5_avgprice>$second_min10_avgprice) and $trade_day_k>60){
	      $number=11/$trade_sell_price*$type10;
	      $number=round($number);   
	      $row=result_select("select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=10;");
	      if($row[0]==0 and $sell_switched==1 and $useable_sell_number>=$number and ($number*$trade_sell_price*100>=1000)){
		      $trade_id=table_id($conn,"trade_history");  	      
		      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','10','$trade_buy_price','$trade_sell_price');";    
		      echo $sql."comming -min5-avg-buy-sql~~~~~~~~~"."\n";                                                                  
		      $conn->query($sql);
	      }
	      } //死叉结束	
		  //金叉+当前价高于五日均线
	     if($trade_day_k>$trade_day_d and $begin_point>$first_min5_avgprice and $trade_day_k<55){
	      $number=11/$trade_buy_price*$type28;
	      $number=round($number);     
	      $row=result_select("select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=28;");   
	      if($row[0]==0 and $buy_switched==1 and $useable_money>=($number*100*$trade_buy_price)){
	      $trade_id=table_id($conn,"trade_history");   
	      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','28','$trade_buy_price','$trade_sell_price');";                                                                  
	      $conn->query($sql);
	      //更新hive_number表数据
	      $total_number=$total_number+$number;  
	      $useable_money=$useable_money-($number*$trade_buy_price*100);  
	      $sql = "update hive_number set total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date' order by id desc limit 1;";                                                                  
	      $conn->query($sql);
	      $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
	      $conn->query($sql);   
		 }      
	      }	
		  //死叉+低于五日均线
	     if($trade_day_k<$trade_day_d and $begin_point<$first_min5_avgprice and $trade_day_k>60){
	      $number=11/$trade_buy_price*$type11;
	      $number=round($number);        
	      $row=result_select("select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=11;");  
	      if($row[0]==0  and $sell_switched==1 and ($number*$trade_sell_price*100>=1000) ){
	      $trade_id=table_id($conn,"trade_history");   
	      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','11','$trade_buy_price','$trade_sell_price');";                                                                  
	      $conn->query($sql);
		    //更新hive_number表数据
	      $total_number=$total_number+$number;  
	      $useable_money=$useable_money-($number*$trade_buy_price*100);  
	      $sql = "update hive_number set total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date' order by id desc limit 1;";                                                                  
	      $conn->query($sql);
	      $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
	      $conn->query($sql);   
		 }      
	      }	
	      */
}//开关结束
	   $log -> log_work($switched."判断开关结束了\n");
  }//方法结束
function sell_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price) {
	  //mysqli_free_result($result);  //释放结果集
	  global $log;
	  $log -> log_work("comming sell_action\n");
	  $sql="select * from trade_history where code=$code and vifi_status=0 and status=1 and trade_type>20 and stat_date<'$stat_date' order by id asc;";
	  //$row=result_select("select * from trade_history where code=$code and vifi_status=0 and status=1 and trade_type>20 and stat_date<'$stat_date' order by id asc;");
	  //$log -> log_work("$sql\n");
	  $result = $conn->query($sql);
	  while($row=mysqli_fetch_array($result)){
	  //while($row){	  
		   $connecttion_id=$row[id];
		   $number=$row[number];   
		   $log -> log_work("connecttion_id:$connecttion_id\n");
		   if($begin_point>$row[trade_buy_price] and $row[cut_price] < ($row[trade_buy_price]+$row[trade_buy_price]*3.02/100)){
			  $log -> log_work("达到条件触发卖出操作，$begin_point，$row[trade_buy_price]，$number,$row[cut_price]\n"); 
			  $trade_id=table_id($conn,"trade_history"); 
			  $log -> log_work("trade_id:$trade_id\n");	   
			  //插入交易历史  
			  $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,vifi_status,number,trade_type,trade_buy_price,trade_sell_price,cut_price,connecttion_id,history_make_money) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','0','$number','$trade_type','$trade_buy_price','$trade_sell_price','0','$connecttion_id',$row[history_make_money]);";                                                                  
			  $log -> log_work("插入交易指令".$sql."\n");
			  $conn->query($sql);
			  //核销已经处理的前期订单，避免订单再次进入
			  $sql = "update trade_history set connecttion_id='$trade_id',vifi_status='1' where id='$connecttion_id';";
			  $log -> log_work("核销已经处理的订单".$sql."\n");
			  $conn->query($sql);
		   }
		   else{
			  $log -> log_work("未达到条件不能触发卖出操作，$begin_point，$row[trade_buy_price]，$number\n"); 
	                }		  	  
	  }
	   mysqli_free_result($result);  //循环结束释放结果集  
	 //######################################################################## 
}
function huizhuan_sell_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price) {
      //####################################################################### 
	global $log;
	  $log -> log_work("comming huizhuan_sell_action\n");
	  $row=result_select("select count(*) from trade_history where code=$code and vifi_status=1 and status=1 and trade_type<20 and stat_date='$stat_date' and stat_time_hour='$trade_time_hour';");
	  $huizhuan_sell_number=$row[0];
	
	  $sql="select * from trade_history where code=$code and vifi_status=0 and status=1 and trade_type>20 and stat_date<'$stat_date' order by id asc;";
	  //$row=result_select("select * from trade_history where code=$code and vifi_status=0 and status=1 and trade_type>20 and stat_date<'$stat_date' order by id asc;");
	  //$log -> log_work($sql."\n");
	  $result = $conn->query($sql);
	  while($row=mysqli_fetch_array($result)){
	  //while($row){	  
		   $connecttion_id=$row[id];
		   $number=$row[number];
		   $cut_price=$trade_buy_price+$trade_buy_price*3/100;
		   $log -> log_work("connecttion_id:"."$connecttion_id~$cut_price\n");
		   if($begin_point>$row[trade_buy_price] and $huizhuan_sell_number==0 and $row[cut_price] < ($row[trade_buy_price]+$row[trade_buy_price]*3.02/100)){
			  $log -> log_work("达到条件触发卖出操作$begin_point，$huizhuan_sell_number，$number，$row[trade_buy_price],$row[cut_price]\n"); 
			  $trade_id=table_id($conn,"trade_history"); 
			  $log -> log_work("trade_id:".$trade_id);	   
			  //插入交易历史  
			  $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,vifi_status,number,trade_type,trade_buy_price,trade_sell_price,cut_price,connecttion_id,history_make_money) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','0','$number','$trade_type','$trade_buy_price','$trade_sell_price','0','$connecttion_id',$row[history_make_money]);";                                                                  
			  $log -> log_work("插入交易指令".$sql."\n");
			  $conn->query($sql);
			  //核销已经处理的前期订单，避免订单再次进入
			  $sql = "update trade_history set connecttion_id='$trade_id',vifi_status='1' where id='$connecttion_id';";
			  $log -> log_work("核销已经处理的订单".$sql."\n");
			  $conn->query($sql);
		   }
		   else{
			  $log -> log_work("未达到条件不能触发回转卖出操作$begin_point，$huizhuan_sell_number，$number，$row[trade_buy_price]\n"); 
	                } 
	  }
	   mysqli_free_result($result);  //循环结束释放结果集  
	 //######################################################################## 
}
function buy_action($code,$trade_code,$conn,$begin_point,$stat_date,$trade_stat_date,$trade_time_hour,$trade_time_min,$trade_type,$trade_buy_price,$trade_sell_price,$trade_bite) {
      global $useable_money,$log,$runoob,$kdjday_k,$kdjday_d,$cci;	
      $log -> log_work("coming buy_action~~~~".$trade_bite."\n");
      $b=$runoob->setkdj($kdjday_k,$kdjday_d,$cci);	
      echo $runoob->Get_Decide($b)."前面是状态\n";   	
      $number=11/$trade_buy_price*$trade_bite;
      $number=round($number);
      //每次发出指令以前都判断一下当前是否有足额可用资金	
      $row=result_select("select useable_money from hive_number where code='$trade_code' order by stat_date desc limit 1;");
      $useable_money=$row[useable_money];	
      //$sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=$trade_type;";
      $row=result_select("select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and trade_type=$trade_type;");
      $log -> log_work("$useable_money~~~~~~$number~~~~$trade_buy_price\n");
      if(($trade_type==5 or $trade_type==6 or $trade_type==7 or $trade_type==8 or $trade_type==9) and ($useable_money>=($number*100*$trade_buy_price))){
	      $log -> log_work("回转达到条件触发买入操作，useable_money:$useable_money，row[0]:$row[0]，number:$number，$trade_buy_price\n");
	      $trade_id=table_id($conn,"trade_history");
	      $cut_price=$trade_buy_price+($trade_buy_price*3/100);
	      $log -> log_work("cut_price:$cut_price\n");
	      //$cut_price=round($cut_price,3);
	      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,vifi_status,number,trade_type,trade_buy_price,trade_sell_price,cut_price,connecttion_id) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','0','$number','$trade_type','$trade_buy_price','$trade_sell_price','$cut_price','0');";
	      $log -> log_work("buy_action交易指令".$sql."\n");
	      $conn->query($sql);
      }else{
		$log -> log_work("回转未达到条件不能触发买入操作，$useable_money，$row[0]，$number，$trade_buy_price\n"); 
      }	
      if(($trade_type!=5 and $trade_type!=6 and $trade_type!=7 and $trade_type!=8 and $trade_type!=9) and $row[0]==0 and $useable_money>=($number*100*$trade_buy_price)){
      //if($row[0]==0 or $useable_money>=($number*100*$trade_buy_price)){	      
	      $log -> log_work("非回转达到条件触发买入操作，useable_money:$useable_money，row[0]:$row[0]，number:$number，$trade_buy_price\n");
	      $trade_id=table_id($conn,"trade_history");
	      $cut_price=$trade_buy_price+($trade_buy_price*3/100);
	      $log -> log_work("cut_price:$cut_price\n");
	      //$cut_price=round($cut_price,3);
	      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,vifi_status,number,trade_type,trade_buy_price,trade_sell_price,cut_price,connecttion_id) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','0','$number','$trade_type','$trade_buy_price','$trade_sell_price','$cut_price','0');";
	      $log -> log_work("buy_action交易指令".$sql."\n");
	      $conn->query($sql);
	      }
	else{
		$log -> log_work("非回转未达到条件不能触发买入操作，$useable_money，$row[0]，$number，$trade_buy_price\n"); 
	    }
}
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
	  }
  ?>
