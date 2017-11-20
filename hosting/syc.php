<?php
include 'config_inc.php';
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
//$code=array("point_number","point_number_sz","point_number_sz100","point_number_zxb","point_number_hs","point_number_zq","point_number_jg","point_number_yh");                                                           
//foreach ($code as $value){
//}    
   //获取当前数据库已存在条数
    $sql = "select count(*) from $value;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;  
     echo $trade_id;
   //获取目标数据库已存在条数
     $url='http://ju71-n2.193b.starter-ca-central-1.openshiftapps.com/page/update.php?type=3&sql=select~*~from~".$value."~where~id='.$trade_id; 
     $html = file_get_contents($url); 
      $str = $html; 
     $newstr = substr($str,0,strlen($str)-2); 
   //对比数据差异，开始执行同步
  $url='http://ju71-n2.193b.starter-ca-central-1.openshiftapps.com/page/update.php?type=3&sql=select~*~from~".$value."~where~id='.$trade_id; 
  $html = file_get_contents($url); 
$str = $html; 
$newstr = substr($str,0,strlen($str)-2); 
echo $newstr; 
    $sql = "insert into $value values ($newstr);";    
    $result=mysqli_query($conn,$sql);
$conn->close();
?>
