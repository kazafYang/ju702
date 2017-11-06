<?php
include 'config_inc.php';
echo $_SERVER["QUERY_STRING"]."<br>";                                                                                                                          
$id=$_GET['id'];                                                                                                                                          
$code=$_GET['code']; 
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
$sql = "update trade_history set code=$code,buy_number=$buy_number,sell";
$result = $conn->query($sql);
?>
