<?php
include 'config_inc.php';
function machining_price () 
{ 
$url='http://hq.sinajs.cn/list=sz399006'; 
$html = file_get_contents($url); 
//echo $html; 
//echo $url; 
$pieces = explode(",", $html);
//echo $pieces[31]. "<br>";
$begin_point=$pieces[3];
$stat_date=$pieces[30];
$pieces = explode(":", $pieces[31]);    
$time_hour=$pieces[0];
$time_min=$pieces[1];
$time_second=$pieces[2];
} 
function sleep_time () 
{
    // 从现在起 10 秒后唤醒
//time_sleep_until(time()+10);
}


$begin_point="";
$stat_date="";
$time_hour="";
$time_min="";
$time_second="";
$time_length=0;


$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
// Check connection
if ($conn->connect_error) {
    die("defult: " . $conn->connect_error);
} 
 
$sql = "SELECT id,begin_point,stat_time_hour FROM point_number";
$result = $conn->query($sql);
 
if ($result->num_rows > 0) {
    // 输出数据
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["begin_point"]. " " . $row["stat_time_hour"]. "<br>";
    }
} else {
    echo "0 defult";
}

$conn->close();

?>
