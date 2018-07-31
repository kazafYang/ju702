<html>
<head>
</head>
<body>
<a href="http://ju70-ju70.193b.starter-ca-central-1.openshiftapps.com/run.php">RUN Script</a>
<p>
<a href="http://ju70-ju70.193b.starter-ca-central-1.openshiftapps.com/page/index2.php">过往记录</a>
<form action="/page/update.php?type=4" method="post">

<p>SQL: <input type="text" name="sql" /></p>

<p><input type="submit" value="提交"></p>
</form>
<p>
<?php
include 'config_inc.php';
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
	
// Check connection
if ($conn->connect_error) {
    die("defult: " . $conn->connect_error);
}
	
  function machining_price ($code) 
  {
 // echo "comming machining_price\n";
  global $stat_date,$time_hour,$time_min;
  if ($code<500000) {
  $url='http://hq.sinajs.cn/list=sz'.$code; 
  }  else{
  $url='http://hq.sinajs.cn/list=sh'.$code; 
  } 
  $html = file_get_contents($url); 
  $pieces = explode(",", $html);
  $begin_point=$pieces[3];
  $buy_one_price=$pieces[6];  //买一价	  
  $sell_one_price=$pieces[7]-0.001; //卖一价 
  $stat_date=$pieces[30];
  $stat_date='"'.$stat_date.'"';
  //echo $stat_date;
  $pieces = explode(":", $pieces[31]);    
  $time_hour=$pieces[0];
  $time_min=$pieces[1];
  $time_second=$pieces[2];
  return $sell_one_price;	  
  } 
	
//	echo "这是我的测试代码：".machining_price($code=159915);
  function trade_history($conn)  
  {
    $sql = "select * from trade_history order by id desc limit 1;"; //where status=0 and stat_date='$stat_date'
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
   // $a= $row[id];
   // echo $a;	
    return $row[id]+1;	  
  }	
 //   echo trade_history($conn);
	
    $sql = "select * from trade_history where trade_type>20 and vifi_status=0 order by id desc;"; //where status=0 and stat_date='$stat_date'
    //查询未交易订单
    $result = $conn->query($sql);
	echo '<table border="1"><tr><th>id</th><th>code</th><th>status</th><th>vifi_status</th><th>trade_type</th><th>number</th><th>trade_buy_price</th><th>trade_sell_price</th><th>cut_price</th><th>history_make_money</th><th>操作</th><th>stat_date</th><</tr>';
	while($row=mysqli_fetch_array($result)){
        $trade_price=machining_price($code=$row[code]);
	//手动干预
	$trade_id=trade_history($conn);	
	$FUrl="http://ju70-ju70.193b.starter-ca-central-1.openshiftapps.com/page/update.php?type=5&sql=insert~into~trade_history~(id,code,status,trade_type,number,trade_buy_price,trade_sell_price,stat_date,stat_time_hour,stat_time_min,trade_number,cut_price,connecttion_id)~values~(".$trade_id.",$row[code],0,11,$row[number],$trade_price,$trade_price,$stat_date,$time_hour,$time_min,0,0,$row[id])";
        $trade_update="&trade_update=update~trade_history~set~status=1,vifi_status=1,connecttion_id=$trade_id~where~id=$row[id];";
        //$trade_sql=$FUrl.$trade_update;
	$FUrl_a="<a href=".$FUrl.$trade_update.">立即操作</a>";	
	echo '<tr><td>'.$row[id].'</td><td>'.$row[code].'</td><td>'.$row[status].'</td><td>'.$row[vifi_status].'</td><td>'.$row[trade_type].'</td><td>'.$row[number].'</td><td>'.$row[trade_buy_price].'</td><td>'.$row[trade_sell_price].'</td><td>'.$row[cut_price].'</td><td>'.$row[history_make_money].'</td><td>'."$FUrl_a".'</td><td>'.$row[stat_date].'</td></tr>';
	}

    $sql = "select id,code,switched,sell_switched,buy_switched,stat_date,useable_money,total_money,make_money,total_number,useable_sell_number from hive_number order by id desc limit 8;"; 
    //查询hive_number数据
    $result = $conn->query($sql);
	echo '<table border="1"><tr><th>id</th><th>code</th><th>total_money</th><th>useable_money</th><th>total_number</th><th>useable_sell_number</th><th>make_money </th><th>switched</th><th>sell_switched</th><th>buy_switched</th><th>stat_date</th><</tr>';
	while($row=mysqli_fetch_array($result)){
	echo '<tr><td>'.$row[id].'</td><td>'.$row[code].'</td><td>'.$row[total_money].'</td><td>'.$row[useable_money].'</td><td>'.$row[total_number].'</td><td>'.$row[useable_sell_number].'</td><td>'.$row[make_money ].'</td><td>'.$row[switched].'</td><td>'.$row[sell_switched].'</td><td>'.$row[buy_switched].'</td><td>'.$row[stat_date].'</td></tr>';
	}

      jincheng();
       function jincheng(){
       date_default_timezone_set('PRC');
       $hour= date("H"); 	       
      // do {	          
        $n2_url='http://ju70-ju70.193b.starter-ca-central-1.openshiftapps.com/page/control.php'; 
        $n2_html = file_get_contents($n2_url);
	//} while ($n2_html != "fail" or $n2_html != "sussess!");       
        echo "&#25235;&#21462;&#31243;&#24207;&#36816;&#34892;&#24773;&#20917;&#65306;".$n2_html."<p>" ;
        echo "buy:type>20,sell:type<20\n";
}	
$table_name=array("point_number","point_number_sz","point_number_sz100","point_number_zxb","point_number_hs","point_number_zq","point_number_jg","point_number_yh");                
	echo '<table border="1"><tr><th>id</th><th>code</th><th>kdjday_k</th><th>kdjday_d</th><th>min15_k</th><th>min15_d</th><th>min30_k</th><th>min30_d</th><th>min60_k</th><th>min60_d</th><th>min120_k</th><th>min120_d</th><th>stat_date</th><</tr>';
	foreach ($table_name as $value)                                                                                                                                                     
{    
    $sql = "SELECT id,code,min15_k,min15_d,min15_j,min30_k,min30_d,min30_j,min60_k,min60_d,min60_j,min120_k,min120_d,kdjday_k,kdjday_d,kdjday_j,cci,stat_date FROM $value order by id desc limit 1";                                                                  
    $result = $conn->query($sql);                                                                                                                                             
    $row = $result->fetch_assoc();
    $row[min15_k]=round($row[min15_k],2);$row[min15_d]=round($row[min15_d],2);$row[min15_j]=round($row[min15_j],2);
    $row[min30_k]=round($row[min30_k],2);$row[min30_d]=round($row[min30_d],2);$row[min30_j]=round($row[min30_j],2);
    $row[min60_k]=round($row[min60_k],2);$row[min60_d]=round($row[min60_d],2);$row[min60_j]=round($row[min60_j],2);		
    $row[kdjday_k]=round($row[kdjday_k],2);$row[kdjday_d]=round($row[kdjday_d],2);$row[kdjday_j]=round($row[kdjday_j],2);
    $row[cci]=round($row[cci],2);$row[min120_k]=round($row[min120_k],2);$row[min120_d]=round($row[min120_d],2);	
    $sellinfo=$sellinfo."<br>";
    $buyinfo=$buyinfo."<br>";
    $showinfo=$showinfo.",".$row[id].",".$row[code].",15mk:".$row[min15_k].",15md:".$row[min15_d].",15mj：".$row[min15_j].",30mk：".$row[min30_k].",30md：".$row[min30_d].",30mj：".$row[min30_j].",60mK：".$row[min60_k].",60md：".$row[min60_d].",60mj：".$row[min60_j].",dayk：".$row[kdjday_k].",dayd：".$row[kdjday_d].",dayj：".$row[kdjday_j].",cci：".$row[cci]."<br>";                         
   echo '<tr><td>'.$row[id].'</td><td>'.$row[code].'</td><td>'.$row[kdjday_k].'</td><td>'.$row[kdjday_d].'</td><td>'.$row[min15_k].'</td><td>'.$row[min15_d].'</td><td>'.$row[min30_k].'</td><td>'.$row[min30_d].'</td><td>'.$row[min60_k].'</td><td>'.$row[min60_d].'</td><td>'.$row[min120_k].'</td><td>'.$row[min120_d].'</td><td>'.$row[stat_date].'</td></tr>';

	}                            	

?>
</body>
</html>

