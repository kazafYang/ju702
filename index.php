<html>
<head>
</head>
<body>
<a href="http://ju70-ju70.193b.starter-ca-central-1.openshiftapps.com/run.php">RUN Script</a>
<p>
tarde_history

<form action="" method="get">

<p>&#22995;&#21517;: <input type="text" name="switch" /></p>

<p>&#23494;&#30721;: <input type="text" name="password" /></p>

<p><input type="submit" value="&#25552;&#20132;" onclick="javascrtpt:window.location.href='http://www.ju70.com/contrul.php"></p>

</form>
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
	echo '<table border="1"><tr><th>id</th><th>code</th><th>trade_type</th><th>number</th><th>trade_buy_price</th><th>trade_sell_price</th><th>stat_date</th><</tr>';
	while($row=mysqli_fetch_array($result)){
	echo '<tr><td>'.$row[id].'</td><td>'.$row[code].'</td><td>'.$row[trade_type].'</td><td>'.$row[number].'</td><td>'.$row[trade_buy_price].'</td><td>'.$row[trade_sell_price].'</td><td>'.$row[stat_date].'</td></tr>';
	}

    $sql = "select id,code,stat_date,useable_money,total_money,make_money from hive_number order by id desc limit 8;"; 
    //echo $sql."\n";
    $result = $conn->query($sql);
	echo '<table border="1"><tr><th>id</th><th>code</th><th>total_money</th><th>useable_money</th><th>make_money </th><th>stat_date</th><</tr>';
	while($row=mysqli_fetch_array($result)){
	echo '<tr><td>'.$row[id].'</td><td>'.$row[code].'</td><td>'.$row[total_money].'</td><td>'.$row[useable_money].'</td><td>'.$row[make_money ].'</td><td>'.$row[stat_date].'</td></tr>';
	}

      jincheng();
       function jincheng(){
       date_default_timezone_set('PRC');
       $hour= date("H");
 //      $min= date("i");
  //     echo $min%15;
      // echo  $hour;
        $n2_url='http://ju70-ju70.193b.starter-ca-central-1.openshiftapps.com/page/control.php'; 
        $n2_html = file_get_contents($n2_url); 
       echo "&#25235;&#21462;&#31243;&#24207;&#36816;&#34892;&#24773;&#20917;&#65306;".$n2_html."<p>" ;
       echo "buy:type>20,sell:type<20\n";
}
$username=$_GET['name'];
$password=$_GET['password'];
if($password=="121212"){
echo $password."comming!\n";
$password=0;
}

    $sql = "select id,code,number,trade_type,trade_buy_price,trade_sell_price,stat_date from trade_history order by id desc limit 5;"; //where status=0 and stat_date='$stat_date'
    //echo $sql."\n";
    $result = $conn->query($sql);
	echo '<table border="1"><tr><th>id</th><th>code</th><th>trade_type</th><th>number</th><th>trade_buy_price</th><th>trade_sell_price</th><th>stat_date</th><</tr>';
	while($row=mysqli_fetch_array($result)){
	echo '<tr><td>'.$row[id].'</td><td>'.$row[code].'</td><td>'.$row[trade_type].'</td><td>'.$row[number].'</td><td>'.$row[trade_buy_price].'</td><td>'.$row[trade_sell_price].'</td><td>'.$row[stat_date].'</td></tr>';
	}

?>
</body>
</html>

