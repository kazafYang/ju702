<?php
include 'config_inc.php';
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);  
$table_name="point_number_zxb"; 
$a=0;    
for ($i=0;$i<=31;$i++){
   $sql = "select id from $table_name limit $i,1;";  //max  //find id
   $result = $conn->query($sql);
   $row=$result->fetch_assoc();
   $id=$row[id];
    
    $a=$i+1;
	$sql = "update $table_name set id=$a where id=$id;";  //batch id  
	$conn->query($sql);  
 if ($conn->query($sql) === TRUE) 
   {
    echo $i."ok\n";
     } 
  else {
    echo $i."Error: " . $sql . $conn->error."\n";
}  	
}
?>
