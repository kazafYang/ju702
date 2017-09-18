<?php                                                                                                                                                                         
include 'config_inc.php';                                                                                                                                                     
$showinfo="info";                                                                                                                                                             
$code=array("point_number","point_number_sz","point_number_sz100","point_number_zxb","point_number_hs","point_number_zq","point_number_jg","point_number_yh");                
                                                                                                                                                                              
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);                                                                                    
foreach ($code as $value)                                                                                                                                                     
{                                                                                                                                                                             
   // echo $value . "<br>";                                                                                                                                                   
    $sql = "SELECT id,min15_k,min15_d,min30_k,min30_d,min60_k,min60_d FROM $value order by id desc limit 1";                                                                  
    $result = $conn->query($sql);                                                                                                                                             
    $row = $result->fetch_assoc();                                                                                                                                            
    $showinfo=$showinfo.",".$row[id].",".$row[min15_k].",".$row[min15_d].",".$row[min30_k].",".$row[min30_d].",".$row[min60_k].",".$row[min60_d]."<br>";                         
}                                                                                                                                                                             
echo $showinfo;                                                                                                                                                               
?> 
