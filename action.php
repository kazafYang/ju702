<?php
include 'config_inc.php';
$showinfo="";
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
$code=array("point_number","point_number_zxb","point_number_sz");
foreach ($code as $value)
{
   // echo $value . "<br>";
    $sql = "SELECT id,begin_point,stat_time_hour FROM $value order by id desc limit 1";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $showinfo=$row[id].$showinfo;
    echo $showinfo;
}
?>
