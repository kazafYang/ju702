<?php
include 'config_inc.php';
echo $_SERVER["QUERY_STRING"]."<br>";                                                                                                                          
$id=$_GET['id'];                                                                                                                                          
$code=$_GET['code']; 
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
$sql = "inser into trade_history ('id','code') values ($id,$code);";
$result = $conn->query($sql);
?>
