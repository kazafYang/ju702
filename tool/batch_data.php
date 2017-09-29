//批量将各个表中的id，进行重新排序；
<?php
include 'config_inc.php';
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);  
$code=array("point_number","point_number_sz","point_number_sz100","point_number_zxb","point_number_hs","point_number_zq","point_number_jg","point_number_yh");                                                           
foreach ($code as $value){
$sql = "select count(*) from $value ;";  //max  //find count data number
$result=mysqli_query($conn,$sql);
$row=mysqli_fetch_row($result);
$count=$row[0];
$a=0;
for ($i=0;$i<$count;$i++){
   $sql = "select id from $value limit $i,1;";  //max  //find id
   $result = $conn->query($sql);
   $row=$result->fetch_assoc();
   $id=$row[id];
    
    $a=$i+1;
	$sql = "update $value set id=$a where id=$id;";  //batch id  
	$conn->query($sql);  
 if ($conn->query($sql) === TRUE) 
   {
    echo $i."ok\n";
     } 
  else {
    echo $i."Error: " . $sql . $conn->error."\n";
}  	
}
}
?>
