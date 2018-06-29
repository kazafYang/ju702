<html>
<head>
</head>
<body>
<a href="http://ju70-ju70.193b.starter-ca-central-1.openshiftapps.com/run.php">运行抓取</a>
<p>
tarde_history
<p>
<?php
include 'config_inc.php';
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
// Check connection
if ($conn->connect_error) {
    die("defult: " . $conn->connect_error);
}

    $sql = "select id,code,number,trade_type,trade_buy_price,trade_sell_price,stat_date from trade_history order by id desc limit 5;"; //where status=0 and stat_date='$stat_date'
    //echo $sql."\n";
    $result = $conn->query($sql);
//    $row = $result->fetch_assoc();
//	$i=count($row);
//	echo "i".$i;
    //if($row[id]>0){
   // echo $row[id].",".$row[code].",".$row[trade_type].",".$row[number].",".$row[trade_buy_price].",".$row[trade_sell_price];
    //} else{
    //echo "0";
    //}
	//$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	echo '<table border="1"><tr><th>id</th><th>code</th><th>trade_type</th><th>number</th><th>trade_buy_price</th><th>trade_sell_price</th><th>stat_date</th><</tr>';
   // while($row = $result->fetch_assoc($result)){
	while($row=mysqli_fetch_array($result)){
	echo '<tr><td>'.$row[id].'</td><td>'.$row[code].'</td><td>'.$row[trade_type].'</td><td>'.$row[number].'</td><td>'.$row[trade_buy_price].'</td><td>'.$row[trade_sell_price].'</td><td>'.$row[stat_date].'</td></tr>';
	}
?>
</body>
</html>

