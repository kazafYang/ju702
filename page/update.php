<?php
include 'config_inc.php';
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
//echo $_SERVER["QUERY_STRING"]."<br>"; 
$type=$_GET['type'];
$id=$_GET['id'];                                                                                                                                          
$code=$_GET['code'];
$sql_update=$_GET['sql'];
$sql_update=str_replace("-"," ",$sql_update);
echo $sql;
//更新trade_history表status
if (type==1){
$sql = "select * from trade_history;";
$result = $conn->query($sql);
  }
  //同步数据库各表数据，让主从数据库数据保持一致
  elseif(type==2){
  $sql = "select * from trade_history;";
  $result = $conn->query($sql);
}
  //同步主从数据库trade库交易数据，让数据保持一致
  elseif(type==3){
  $sql = "select * from trade_history;";
  $result = $conn->query($sql);
}
//验证实际插入sql可能存在问题，还是需要组合才行
  elseif(type==4){
  echo "comming/n"
  $sql = "$sql_update;";
   echo "comming/n".$sql  
  $result = $conn->query($sql);
}
?>
