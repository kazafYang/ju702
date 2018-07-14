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
	
$table_name=array("point_number","point_number_sz","point_number_sz100","point_number_zxb","point_number_hs","point_number_zq","point_number_jg","point_number_yh");                
foreach ($table_name as $value)                                                                                                                                                     
{    
    $sql = "SELECT code,min15_k,min15_d,min15_j,min30_k,min30_d,min30_j,min60_k,min60_d,min60_j,kdjday_k,kdjday_d,kdjday_j,cci FROM $value order by id desc limit 1";                                                                  
    $result = $conn->query($sql);                                                                                                                                             
    $row = $result->fetch_assoc();
    $row[min15_k]=round($row[min15_k],2);$row[min15_d]=round($row[min15_d],2);$row[min15_j]=round($row[min15_j],2);
    $row[min30_k]=round($row[min30_k],2);$row[min30_d]=round($row[min30_d],2);$row[min30_j]=round($row[min30_j],2);
    $row[min60_k]=round($row[min60_k],2);$row[min60_d]=round($row[min60_d],2);$row[min60_j]=round($row[min60_j],2);
    $row[kdjday_k]=round($row[kdjday_k],2);$row[kdjday_d]=round($row[kdjday_d],2);$row[kdjday_j]=round($row[kdjday_j],2);
    $row[cci]=round($row[cci],2);	
	$sellinfo=$sellinfo."<br>";
    $buyinfo=$buyinfo."<br>";
    $showinfo=$showinfo.",".$row[code].",15mk:".$row[min15_k].",15md:".$row[min15_d].",15mj：".$row[min15_j].",30mk：".$row[min30_k].",30md：".$row[min30_d].",30mj：".$row[min30_j].",60mK：".$row[min60_k].",60md：".$row[min60_d].",60mj：".$row[min60_j].",dayk：".$row[kdjday_k].",dayd：".$row[kdjday_d].",dayj：".$row[kdjday_j].",cci：".$row[cci]."<br>";                         
}                           
echo $showinfo;  
echo "<br>".$sellinfo; 
echo "<br>".$buyinfo;   	

?>
</body>
</html>

