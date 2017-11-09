<?php                                                                                                                                                                         
include 'config_inc.php';  
$stat_date=date("Y-m-d");
//select id,code,number,trade_type from trade_history where status=1 order by id asc limit 1; 
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);                                                                                                                                                                                                                                      
    $sql = "select id,code,number,trade_type,trade_buy_price,trade_sell_price from trade_history where status=0 and stat_date='$stat_date' order by id asc limit 1;";                                                                  
    //echo $sql."\n";
    $result = $conn->query($sql);                                                                                                                                             
    $row = $result->fetch_assoc();
    if($row[id]>0){
    echo $row[id].",".$row[code].",".$row[trade_type].",".$row[number].",".$row[trade_buy_price].",".$row[trade_sell_price];
    } else{
    echo "0";
    }    
?> 
