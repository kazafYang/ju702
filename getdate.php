<?php                                                                                                                                                                         
include 'config_inc.php';                                                                                                                                                     
//select id,code,number,trade_type from trade_history where status=1 order by id asc limit 1; 
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);                                                                                                                                                                                                                                      
    $sql = "select id,code,number,trade_type from trade_history where status=1 order by id asc limit 1;";                                                                  
    $result = $conn->query($sql);                                                                                                                                             
    $row = $result->fetch_assoc();                                                                                                                                            
    echo $row[id].",".$row[code].",".$row[trade_type].",".$row[number];
?> 
