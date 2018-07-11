<?php
include 'config_inc.php';
$stat_date=date("Y-m-d");
//select id,code,number,trade_type from trade_history where status=1 order by id asc limit 1;
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
$type=$_GET['type'];
$trade_number=$_GET['trade_number'];
//查询当前收到交易指令
if ($type==1){
    $sql = "select id,code,number,trade_type,trade_buy_price,trade_sell_price from trade_history where status=0 and stat_date='$stat_date' order by id asc limit 1;";
    //echo $sql."\n";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    if($row[id]>0){
    echo $row[id].",".$row[code].",".$row[trade_type].",".$row[number].",".$row[trade_buy_price].",".$row[trade_sell_price];
    } else{
    echo "0";
    }
      mysqli_free_result($result);  //释放结果集  
 }

//查询出当前交易记录是否存在数据库中
if($type==2){
     $sql = "select count(*) from trade_history where trade_number=$trade_number and stat_date='$stat_date';";
     $result = $conn->query($sql);
     $row=mysqli_fetch_row($result);  
     if($row[0]>0){
       echo "200";
 }
     else{
     echo "404";
     }
  mysqli_free_result($result);  //释放结果集    
}
if($type==3){
     $sql = "select id from trade_history order by id desc limit 1;";
     $result = $conn->query($sql);
     $row=mysqli_fetch_row($result); 
     echo $row[0];
      mysqli_free_result($result);  //释放结果集  
}
?>



