<?php                                                                                                                                                                         
include 'config_inc.php';                                                                                                                                                     
$showinfo="info";
$sellinfo="information:";
$code=array("point_number","point_number_sz","point_number_sz100","point_number_zxb","point_number_hs","point_number_zq","point_number_jg","point_number_yh");                
                                                                                                                                                                              
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);                                                                                    
foreach ($code as $value)                                                                                                                                                     
{                                                                                                                                                                                                                                                                                                                         
    $sql = "SELECT code,min15_k,min15_d,min15_j,min30_k,min30_d,min30_j,min60_k,min60_d,min60_j FROM $value order by id desc limit 1";                                                                  
    $result = $conn->query($sql);                                                                                                                                             
    $row = $result->fetch_assoc();
    $row[min15_k]=round($row[min15_k],2);$row[min15_d]=round($row[min15_d],2);$row[min15_j]=round($row[min15_j],2);
    $row[min30_k]=round($row[min30_k],2);$row[min30_d]=round($row[min30_d],2);$row[min30_j]=round($row[min30_j],2);
    $row[min60_k]=round($row[min60_k],2);$row[min60_d]=round($row[min60_d],2);$row[min60_j]=round($row[min60_j],2);
    if (($row[min15_k] >=75 and $row[min15_k] <80) or ($row[min15_d] >=75 and $row[min15_k] <80)){
    $sellinfo=$sellinfo.$row[code]."75<=15k/d<=80";
    } 
    elseif($row[min15_k] >=85 or $row[min15_d] >=80){
    $sellinfo=$sellinfo.$row[code]."=15k/d>=85";  
    }
    if ($row[min30_k] >=75 or $row[min30_d] >=75){
    $sellinfo=$sellinfo.$row[code]."=30k/d>=75";
    } 
    elseif($row[min30_k] >=85 or $row[min30_d] >=80){
    $sellinfo=$sellinfo.$row[code]."=30k/d>=85";  
    }
    if ($row[min60_k] >=75) or $row[min60_d] >=75{
    $sellinfo=$sellinfo.$row[code]."=60k/d>=75";
    }
    elseif($row[min60_k] >=85 or $row[min60_d] >=80){
    $sellinfo=$sellinfo.$row[code]."=60k/d>=85";  
    }
    $showinfo=$showinfo.",".$row[code].",15mk:".$row[min15_k].",15md:".$row[min15_d].",15mj：".$row[min15_j].",30mk：".$row[min30_k].",30md：".$row[min30_d].",30mj：".$row[min30_j].",60mK：".$row[min60_k].",60md：".$row[min60_d].",60mj：".$row[min60_j]."<br>";                         
}                                                                                                                                                                             
echo $showinfo;  
echo $sellinfo; 
?> 
