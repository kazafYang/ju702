<html>
<head>
</head>
<body>
<form action="" method="get">
<select name="select_date">
  <option value ="volvo">今日订单</option>
  <option value ="saab">一周订单</option>
  <option value="opel">已核销订单</option>
  <option value="audi">未核销订单</option>
  <option value="maket">做T数据</option>
</select>
  <input type="submit" value="Submit" />
</form>
<?php
include 'config_inc.php';
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
date_default_timezone_set('PRC');
	
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
// Check connection
if ($conn->connect_error) {
    die("defult: " . $conn->connect_error);
}       
	    $select_date = $_GET["select_date"];
	    $end_date=date("Y-m-d");
    	      switch ($select_date)
	      {
	       case "volvo":
	       $stat_date=date("Y-m-d");	      
               $sql = "select * from trade_history where  stat_date='$stat_date' order by id desc;";
	       $sql_count = "select sum(history_make_money) from trade_history where  stat_date='$stat_date' and trade_type>20;";		      
	       break;
	       case "saab":
	       $stat_date=date("Y-m-d",strtotime("-7 day"));	      
               $sql = "select * from trade_history where  stat_date>='$stat_date' and stat_date<='$end_date' order by id desc;"; 
               $sql_count = "select sum(history_make_money) from trade_history where  stat_date>='$stat_date' and stat_date<='$end_date' and trade_type>20;";	
	       break;
	       case "opel":
	       $stat_date=date("Y-m-d",strtotime("-31 day"));
               $sql = "select * from trade_history where  trade_type>20 and status=1 and vifi_status=1 order by id desc;"; 
               $sql_count = "select sum(history_make_money) from trade_history where  trade_type>20 and status=1 and vifi_status=1;";		       
	       break;
	       case "audi":
	       $stat_date=date("Y-m-d",strtotime("-90 day"));
               $sql = "select * from trade_history where  trade_type>20 and status=1 and vifi_status=0 order by id desc;"; 
               $sql_count = "select sum(history_make_money) from trade_history where trade_type>20;";		      
	       break;
	       case "maket":
	       $stat_date=date("Y-m-d",strtotime("-90 day"));
               $sql = "select * from hive_number where code=159915 order by stat_date asc;"; 
               //$sql_count = "select sum(history_make_money) from trade_history where trade_type>20;";		      
	       break;
	       default:
               $sql = "select * from trade_history order by id desc;"; 
	       $sql_count = "select sum(history_make_money) from trade_history where trade_type>20;";		      
	       } 	
	
    $sql = $sql;
    //echo $sql;	
    //查询已交易完成订单
    $result = $conn->query($sql);
	if($select_date != "maket"){
	echo '<table border="1"><tr><th>id</th><th>code</th><th>status</th><th>vifi_status</th><th>trade_type</th><th>number</th><th>trade_buy_price</th><th>trade_sell_price</th><th>cut_price</th><th>history_make_money</th><th>connecttion_id</th><th>stat_date</th><</tr>';
	while($row=mysqli_fetch_array($result)){
	echo '<tr><td>'.$row[id].'</td><td>'.$row[code].'</td><td>'.$row[status].'</td><td>'.$row[vifi_status].'</td><td>'.$row[trade_type].'</td><td>'.$row[number].'</td><td>'.$row[trade_buy_price].'</td><td>'.$row[trade_sell_price].'</td><td>'.$row[cut_price].'</td><td>'.$row[history_make_money].'</td><td>'.$row[connecttion_id].'</td><td>'.$row[stat_date].'</td></tr>';
	}
	mysqli_free_result($result);  //释放结果集

        $sql_count = $sql_count; 
        $result = $conn->query($sql_count);
	$row=mysqli_fetch_array($result);
	echo '<table border="1"><tr><th>id</th></tr>';
	echo '<tr><td>'.$row[0].'</td></tr>';
	}
	else{
	echo '<table border="1"><th>code</th><th>total_money</th><th>stat_date</th><th>make_money</th><th>stat_date</th><th>trade_buy_price</th><th>trade_sell_price</th><th>cut_price</th><th>history_make_money</th><th>connecttion_id</th><th>stat_date</th><</tr>';
	$old_total_money=0;
	while($row=mysqli_fetch_array($result)){
	$make_money=$row[total_money]-$old_make_money;
	$old_make_money=$row[total_money];
	echo '<tr><td>'.$row[code].'</td><td>'.$row[total_money].'</td><td>'.$make_money.'</td><td>'.$row[stat_date].'</td></tr>';
	}
	}
	mysqli_free_result($result);  //释放结果集
	$conn->close();
?>
</body>
</html>
