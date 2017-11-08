<?php
include 'config_inc.php';
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
echo $_SERVER["QUERY_STRING"]."<br>"; 
$type=$_GET['type'];
$id=$_GET['id'];                                                                                                                                          
$code=$_GET['code'];
//更新trade_history表status
if (type==1){
$sql = "update trade_history set code=$code,buy_number=$buy_number,sell";
$result = $conn->query($sql);
  }
  //同步数据库各表数据，让主从数据库数据保持一致
  elseif(type==2){
  $sql = "update trade_history set code=$code,buy_number=$buy_number,sell";
  $result = $conn->query($sql);
}
  //同步主从数据库trade库交易数据，让数据保持一致
  elseif(type==3){
  $sql = "update trade_history set code=$code,buy_number=$buy_number,sell";
  $result = $conn->query($sql);
}

?>
