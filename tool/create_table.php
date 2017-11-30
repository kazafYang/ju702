//批量增加字段
<?php  
include 'config_inc.php'; 
$info="begin:";
$code=array("point_number","point_number_sz","point_number_sz100","point_number_zxb","point_number_hs","point_number_zq","point_number_jg","point_number_yh");                                                                                                                                                                                           
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);  
foreach ($code as $value)                                                                                                                                                     
{
    $sql = "CREATE TABLE IF NOT EXISTS ".$value." (`id` int(11) NOT NULL,`code` int(8) DEFAULT '159915',`stat_date` date DEFAULT NULL,`stat_time_hour` int(2) DEFAULT NULL, `stat_time_min` int(2) DEFAULT NULL,`begin_point` double DEFAULT NULL, `min15_point_max` double DEFAULT NULL, `min15_point_min` double DEFAULT NULL, `min15_k` double DEFAULT NULL, `min15_d` double DEFAULT NULL,`min15_j` double DEFAULT NULL, `min30_k` double DEFAULT NULL,`min30_d` double DEFAULT NULL,`min30_j` double DEFAULT NULL,`min60_k` double DEFAULT NULL, `min60_d` double DEFAULT NULL,  `min60_j` double DEFAULT NULL, `min120_k` double DEFAULT '0', `min120_d` double DEFAULT '0',`min120_j` double DEFAULT '0',`kdjday_k` double DEFAULT NULL,`kdjday_d` double DEFAULT NULL, `kdjday_j` double DEFAULT NULL,`now_price` double DEFAULT '0',`buy_one_price` double DEFAULT '0',`sell_one_price` double DEFAULT '0',`cci` double DEFAULT '0',PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;";                                                                  
    $result = $conn->query($sql);         
}
echo $info."end";
?>  
