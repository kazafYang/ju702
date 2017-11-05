//批量创建表
<?php  
include 'config_inc.php'; 
$info="begin:";
$code=array("point_number","point_number_sz","point_number_sz100","point_number_zxb","point_number_hs","point_number_zq","point_number_jg","point_number_yh");                                                                                                                                                                                           
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);  
//foreach ($code as $value)                                                                                                                                                     
//{
   /* $sql = "alter table $value add now_price int default 0;";                                                                  
    $result = $conn->query($sql);    
    $sql = "alter table $value add buy_one_price int default 0;";                                                                  
    $result = $conn->query($sql);  
    $sql = "alter table $value add sell_one_price int default 0;";                                                                  
    $result = $conn->query($sql);  
    */
   $sql = " CREATE TABLE IF not EXISTS trade_history (
  `id` int(11) not NULL ,
  `code` int(8) DEFAULT NULL,
  `stat_date` date DEFAULT NULL,
  `stat_time_hour` int(2) DEFAULT NULL,
  `stat_time_min` int(2) DEFAULT NULL,
/* `begin_point` double DEFAULT NULL,
  `min15_point_max` double DEFAULT NULL,
  `min15_point_min` double DEFAULT NULL,
  `min15_k` double DEFAULT NULL,
  `min15_d` double DEFAULT NULL,
  `min15_j` double DEFAULT NULL,
  `min30_k` double DEFAULT NULL,
  `min30_d` double DEFAULT NULL,
  `min30_j` double DEFAULT NULL,
  `min60_k` double DEFAULT NULL,
  `min60_d` double DEFAULT NULL,
  `min60_j` double DEFAULT NULL,
  `kdjday_k` double DEFAULT NULL,
  `kdjday_d` double DEFAULT NULL,
  `kdjday_j` double DEFAULT NULL,
  `now_price` double DEFAULT  NULL,
  `cci` double DEFAULT  NULL,
  `buy_one_price` double DEFAULT  NULL,
  `sell_one_price` double DEFAULT  NULL, */
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
$result = $conn->query($sql); 

//}
echo $info."end";
?>  
