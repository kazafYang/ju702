<html>
<head>
</head>
<body>
<form action="" method="get">
<select name="select_date">
  <option value ="volvo">当日</option>
  <option value ="saab">一周</option>
  <option value="opel">一月</option>
  <option value="audi">三月</option>
</select>
  <input type="submit" value="Submit" />
</form>
<?php
include 'config_inc.php';
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
// Check connection
if ($conn->connect_error) {
    die("defult: " . $conn->connect_error);
}
    $select_date = $_GET["select_date"];
    echo $select_date."@@@@@@@@@@@@@@@@@@@\n"	
    $sql = "select * from trade_history where status=1 and vifi_status=1 order by id desc;"; //where status=0 and stat_date='$stat_date'
    //查询已交易完成订单
    $result = $conn->query($sql);
	echo '<table border="1"><tr><th>id</th><th>code</th><th>status</th><th>vifi_status</th><th>trade_type</th><th>number</th><th>trade_buy_price</th><th>trade_sell_price</th><th>cut_price</th><th>history_make_money</th><th>操作</th><th>stat_date</th><</tr>';
	while($row=mysqli_fetch_array($result)){
        $trade_price=machining_price($code=$row[code]);
	//手动干预
	$trade_id=trade_history($conn);	
	$FUrl="http://www.ju70.com/page/update.php?type=5&sql=insert~into~trade_history~(id,code,status,trade_type,number,trade_buy_price,trade_sell_price,stat_date,stat_time_hour,stat_time_min,trade_number,cut_price,connecttion_id)~values~(".$trade_id.",$row[code],0,11,$row[number],$trade_price,$trade_price,$stat_date,$time_hour,$time_min,0,0,$row[id])";
        $trade_update="&trade_update=update~trade_history~set~status=1,vifi_status=1,connecttion_id=$trade_id~where~id=$row[id];";
        //$trade_sql=$FUrl.$trade_update;
	$FUrl_a="<a href=".$FUrl.$trade_update.">立即操作</a>";	
	echo '<tr><td>'.$row[id].'</td><td>'.$row[code].'</td><td>'.$row[status].'</td><td>'.$row[vifi_status].'</td><td>'.$row[trade_type].'</td><td>'.$row[number].'</td><td>'.$row[trade_buy_price].'</td><td>'.$row[trade_sell_price].'</td><td>'.$row[cut_price].'</td><td>'.$row[history_make_money].'</td><td>'."$FUrl_a".'</td><td>'.$row[stat_date].'</td></tr>';
	}
?>
</body>
</html>
