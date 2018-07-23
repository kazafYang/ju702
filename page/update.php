<?php
include 'config_inc.php';
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
$type=$_GET['type'];
$id=$_GET['id'];                                                                                                                                          
$code=$_GET['code'];
$sql_update=$_GET['sql'];
if($sql_update==""){
$sql_update=$_POST['sql'];
}
$sql_update=str_replace("~"," ",$sql_update);

//更新trade_history表status
if ($type==1){
$sql = "select * from trade_history;";
$result = $conn->query($sql);
  }
  //同步数据库各表数据，让主从数据库数据保持一致
  elseif($type==2){
  $sql = "select * from trade_history;";
  $result = $conn->query($sql);
}
  //同步主从数据库trade库交易数据，让数据保持一致
  elseif($type==3){
  $sql = "$sql_update";
  $result = $conn->query($sql);
  $row=mysqli_fetch_row($result);  
 // $fieldcount=mysqli_num_fields($result);
 // echo  $fieldcount;
  $str="'";  
  foreach ($row as $value) {
   $str=$str.$value."','"; 
}
    $str = substr($str,0,strlen($str)-2); 
    echo $str;
}
//验证实际插入sql可能存在问题，还是需要组合才行
  elseif($type==4){
  $sql = "$sql_update";
  if ($conn->query($sql) === TRUE) 
   {
   echo "200";
     }
    else {
    echo "Error: " . $sql . $conn->error."\n";
}
}
//验证实际插入sql可能存在问题，还是需要组合才行
  elseif($type==5){
  $sql = "$sql_update";
  if ($conn->query($sql) === TRUE) 
   {
   echo "200";
     }
    else {
    echo "Error: " . $sql . $conn->error."\n";
}
}
?>
