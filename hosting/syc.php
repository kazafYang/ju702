<?php
include 'config_inc.php';
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
    $sql = "select count(*) from trade_bate;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;  
     echo $trade_id;
  $url='http://ju71-n2.193b.starter-ca-central-1.openshiftapps.com/page/update.php?type=3&sql=select~*~from~trade_bate~where~id='.$trade_id; 
  $html = file_get_contents($url); 
$str = $html; 
$newstr = substr($str,0,strlen($str)-2); 
echo $newstr; 
    $sql = "insert into trade_bate values ($newstr);";    
    $result=mysqli_query($conn,$sql);
$conn->close();
?>
