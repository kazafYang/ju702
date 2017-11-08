//批量增加字段
<?php  
include 'config_inc.php'; 
$info="begin:";
$code=array("point_number","point_number_sz","point_number_sz100","point_number_zxb","point_number_hs","point_number_zq","point_number_jg","point_number_yh");                                                                                                                                                                                           
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);  
foreach ($code as $value)                                                                                                                                                     
{
    $sql = "select * from $value order by id asc;";                                                                  
    $result = $conn->query($sql);     
     
}
echo $info."end";
?>  
