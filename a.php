<?php
include 'config_inc.php';
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
$url='http://hq.sinajs.cn/list=sz399006'; 
$html = file_get_contents($url); 
//echo $html; 
//echo $url; 
$pieces = explode(",", $html);
echo $pieces[31]. "<br>";
$conn->close();
?>
