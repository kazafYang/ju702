<?php
include 'config_inc.php';
include 'public_function.php';
include 'common/logs.php';
$table_name="point_number_yh";
$code="512800";
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
/* 
$sql = "SELECT id,begin_point,stat_time_hour FROM ". $table_name." limit 1";
$result = $conn->query($sql);
// echo $result;
if ($result->num_rows > 0) {
    // 输出数据
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["begin_point"]. " " . $row["stat_time_hour"]. "<br>";
    }
} else {
    echo "0 defult";
}
*/
#code begin
while(1==1) {
$log -> log_work("开始执行程序-------------->");      
machining_price();
    if ($time_hour<9 or ($time_hour==9 and $time_min<30)) {
      $log -> log_work("comming mainwhile if--9\n");
    sleep_time();
    }elseif (($time_hour=="11" and $time_min>="30") or ($time_hour>="12" and $time_hour<"13")){
    sleep_time();     
    }elseif ($time_hour>="15") {
      $log -> log_work("3point!\n");
    exit(0);    
    }
    
/*    if ($time_hour==9 and $time_min==30) {
    $time_length=960-$time_second;
    }elseif ($time_hour=="13" and $time_min=="0") {
    $time_length=960-$time_second;  
    }else{
    $time_length=900-$time_second;    
    } */
$sql = "select id,stat_time_min from $table_name order by id desc limit 1;";    
    $result = $conn->query($sql);
    $row=$result->fetch_assoc();
    $log -> log_work("stat_time_min:$row[stat_time_min]\n");
    $stat_time_min=$row[stat_time_min];
    if ($stat_time_min<>$time_min and ($time_min==0 or $time_min==15 or $time_min==30 or $time_min==45)){
        $time_out_begin=($time_hour*3600)+($time_min*60)+900;    
   $log -> log_work("table:$table_name\n");
   $row[id]=$row[id]+1;   
   $sql = "insert into $table_name (id,stat_date,stat_time_hour,stat_time_min,begin_point,min15_k,min15_d,min30_k,min30_d,min60_k,min60_d,min120_k,min120_d,kdjday_k,kdjday_d) VALUES ('$row[id]','$stat_date','$time_hour','$time_min','$begin_point',50,50,50,50,50,50,50,50,50,50);";    
   if ($conn->query($sql) === TRUE) {
    $log -> log_work("new inser into\n");
} else {
    $log -> log_work("Error: " . $sql . $conn->error."\n");
}
   nine_count();     
    }
} 


$conn->close();

?>
