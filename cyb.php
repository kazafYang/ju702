<?php
include 'config_inc.php';
include 'common/logs.php';
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
    $log -> log_work("开始执行程序-------------->");    
    machining_price();
    if ($time_hour<9 or ($time_hour==9 and $time_min<30)) {
      $log -> log_work("comming mainwhile if--9\n");
    sleep_time();
    }elseif (($time_hour=="11" and $time_min>="30") or ($time_hour>="12" and $time_hour<"13")){
    sleep_time();    
    }elseif ($time_hour>="15") {
      $log -> log_work("3point!\n");
      $a=$runoob->Runday_Point();  
      exit(0);    
    }
      
    $sql = "select id,stat_time_min from $table_name order by id desc limit 1;";    
    $result = $conn->query($sql);
    $row=$result->fetch_assoc();
    $log -> log_work("stat_time_min:$row[stat_time_min]\n");
    $stat_time_min=$row[stat_time_min];
        if ($stat_time_min<>$time_min and ($time_min==0 or $time_min==15 or $time_min==30 or $time_min==45)){
           $log -> log_work("table:$table_name\n");    
           $time_out_begin=($time_hour*3600)+($time_min*60)+900;
           $time_out_now=($time_hour*3600)+($time_min*60);
           $log -> log_work($time_out_now."\n");     
           $log -> log_work("当前时间".(intval($time_out_now/3600)).":".(($time_out_now%3600)/60)."\n");
           $log -> log_work( $time_out_begin."\n");    
           $log -> log_work("结束时间".(intval($time_out_begin/3600)).":".(($time_out_begin%3600)/60)."\n");
           $row[id]=$row[id]+1;   
           $sql = "insert into $table_name (id,stat_date,stat_time_hour,stat_time_min,begin_point,min15_k,min15_d,min30_k,min30_d,min60_k,min60_d,min120_k,min120_d,kdjday_k,kdjday_d) VALUES ('$row[id]','$stat_date','$time_hour','$time_min','$begin_point',50,50,50,50,50,50,50,50,50,50);";    
               if ($conn->query($sql) === TRUE) {
                    $log -> log_work("new inser into success $sql\n");    
                } else {
                    $log -> log_work("Error: " . $sql . $conn->error."\n");   
                }   
           nine_count();     
         }
      } 
$conn->close();
?>
