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
date_default_timezone_set('PRC');
// Check connection
if ($conn->connect_error) {
    die("defult: " . $conn->connect_error);
}       
	    $select_date = $_GET["select_date"];
	    $end_date=date("Y-m-d");
    	      switch ($select_date)
	      {
	       case "volvo":
	       	      
               $sql = "select * from trade_history where  stat_date='$stat_date' order by id desc;"; 
	       break;
	       case "saab":
	       $stat_date=date("Y-m-d",strtotime("-7 day"));	      
               $sql = "select * from trade_history where  stat_date>='$stat_date' and stat_date<='$end_date' order by id desc;"; 

	       break;
	       case "opel":
	       $stat_date=date("Y-m-d",strtotime("-31 day"));
               $sql = "select * from trade_history where  stat_date>='$stat_date' and stat_date<='$end_date' order by id desc;"; 
	       
	       break;
	       case "audi":
	       $stat_date=date("Y-m-d",strtotime("-90 day"));
               $sql = "select * from trade_history where  stat_date>='$stat_date-90' and stat_date<='$end_date' order by id desc;"; 
	      
	       break;
	       default:
               $sql = "select * from trade_history where  stat_date='$stat_date' order by id desc;"; 
	       } 	
	
    $sql = $sql;
    //echo $sql;	
    //查询已交易完成订单
    $result = $conn->query($sql);
	echo '<table border="1"><tr><th>id</th><th>code</th><th>status</th><th>vifi_status</th><th>trade_type</th><th>number</th><th>trade_buy_price</th><th>trade_sell_price</th><th>cut_price</th><th>history_make_money</th><th>connecttion_id</th><th>stat_date</th><</tr>';
	while($row=mysqli_fetch_array($result)){
	echo '<tr><td>'.$row[id].'</td><td>'.$row[code].'</td><td>'.$row[status].'</td><td>'.$row[vifi_status].'</td><td>'.$row[trade_type].'</td><td>'.$row[number].'</td><td>'.$row[trade_buy_price].'</td><td>'.$row[trade_sell_price].'</td><td>'.$row[cut_price].'</td><td>'.$row[history_make_money].'</td><td>'.$row[connecttion_id].'</td><td>'.$row[stat_date].'</td></tr>';
	}
?>
</body>
</html>
