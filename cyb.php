<?php
include 'config_inc.php';
include 'public_function.php';
$table_name="point_number";
$code="159915";
$begin_point="";
$stat_date="";
$time_hour="";
$time_min="";
$time_second="";
$time_length=0;

$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
// Check connection
if ($conn->connect_error) {
    die("defult: " . $conn->connect_error);
} 
#code begin
while(1==1) {    
machining_price();
    if ($time_hour<9 or ($time_hour==9 and $time_min<30)) {
      echo "comming mainwhile if--9\n";
    sleep_time();
    }elseif ($time_hour=="11" and $time_min>="30") {
    sleep_time();     
    }elseif ($time_hour>="15") {
      echo "3point!\n";
      exit(0);    
    }
  
    $time_out_begin=($time_hour*3600)+($time_min*60)+900;    
    $sql = "select id,stat_time_min from $table_name order by id desc limit 1;";    
    $result = $conn->query($sql);
    $row=$result->fetch_assoc();
    echo "stat_time_min:$row[stat_time_min]\n";
    $stat_time_min=$row[stat_time_min];
    if ($stat_time_min<>$time_min and ($time_min==0 or $time_min==15 or $time_min==30 or $time_min==45)){
   echo "table:$table_name\n";
   $row[id]=$row[id]+1;   
   $sql = "insert into $table_name (id,stat_date,stat_time_hour,stat_time_min,begin_point) VALUES ('$row[id]','$stat_date','$time_hour','$time_min','$begin_point');";    
   if ($conn->query($sql) === TRUE) {
    echo "new inser into\n";
} else {
    echo "Error: " . $sql . $conn->error."\n";
}
   nine_count();     
    }
} 
$conn->close();
?>
