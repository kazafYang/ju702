<?php
include 'config_inc.php';
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
$sql = "select * from trade_history where  status=1 and vifi_status=1 and connecttion_id<>0 and trade_type>20 order by id desc;";
$result = $conn->query($sql);
while($row=mysqli_fetch_array($result)){
    $sql_sell="select * from trade_history where  id=$row[connecttion_id];";
    $result_sell = $conn->query($sql_sell);
    $row_sell=mysqli_fetch_array($result_sell);
    if($row_sell[id]>0 and $row_sell[number]==$row[number]){
      //echo $row_sell[trade_sell_price]."~".$row_sell[number]."~".$row[trade_buy_price]."~".$row[number]."\n";
      $history_make_money=($row_sell[trade_sell_price]*$row_sell[number]*100)-($row[trade_buy_price]*$row[number]*100);
      $history_make_money=Round($history_make_money,3);
      $sql_update="update trade_history set history_make_money=$history_make_money where id in ($row[id],$row[connecttion_id]);";
      $conn->query($sql_update);
        }
    mysqli_free_result($result_sell);  //释放结果集
    echo $history_make_money."~".$sql_update."<p>";
}
mysqli_free_result($result);  //释放结果集

?>
