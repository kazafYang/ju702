<?php
include 'config_inc.php';
function machining_price () 
{ 
$url='http://hq.sinajs.cn/list=sz399006'; 
$html = file_get_contents($url); 
$pieces = explode(",", $html);
$begin_point=$pieces[3];
$stat_date=$pieces[30];
$pieces = explode(":", $pieces[31]);    
$time_hour=$pieces[0];
$time_min=$pieces[1];
$time_second=$pieces[2];
} 
function sleep_time () {
while(($time_hour<>9 and $time_min<>30) or $time_hour<>13) {
machining_price();
}
                       }
function nine_count () {
$max=$begin_point;
$min=$begin_point;
$time_out_now=($time_hour*3600)+($time_min*60)+$time_second;
while( $time_out_now <= $time_out_begin) {
     machining_price();
$time_out_now=($time_hour*3600)+($time_min*60)+$time_second;
if ($begin_point>=$max){
    $max=$begin_point;
    $sql="update ${TABLENAME} set min15_point_max=$max order by id desc limit 1 ; "
    $conn->query($sql); 
}
if ($begin_point<=$min){
    $min=$begin_point; 
    $sql="update ${TABLENAME} set min15_point_min=$min order by id desc limit 1 ; "
    $conn->query($sql);    
} 
kdjfifteen();        #得到最大最小值以后开始进行kdj的方法计算；
kdjthirty();
kdjsixty();    
} 
}


$table_name="point_number";
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
 
$sql = "SELECT id,begin_point,stat_time_hour FROM ". $table_name." limit 1";
$result = $conn->query($sql);
 echo $result
if ($result->num_rows > 0) {
    // 输出数据
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["begin_point"]. " " . $row["stat_time_hour"]. "<br>";
    }
} else {
    echo "0 defult";
}
#code begin
while(1=2） {    
machining_price();
    if ($time_hour<9 or ($time_hour==9 and $time_min<30)) {
    sleep_time
    }elseif ($time_hour=="11" and $time_min>"30") {
    sleep_time     
    }elseif ($time_hour=="15" and $time_min>"1") {
    exit;    
    }
    
    if ($time_hour==9 and $time_min==30) {
    $time_length=960-$time_second
    }elseif ($time_hour=="13" and $time_min=="0") {
    $time_length=960-$time_second  
    }else{
    $time_length=900-$time_second    
    }
$sql = "select stat_time_min from ".$table_name." order by id desc limit 1;";    
    $result = $conn->query($sql);
    if ($result<>$time_min){
    $time_out_begin=($time_hour*3600)+($time_min*60)+$time_second+$time_length;
$sql = "insert into ${TABLENAME}  (stat_date,stat_time_hour,stat_time_min,begin_point) VALUES ('$stat_date','$time_hour','$time_min','$begin_point')";    
   $conn->query($sql); 
   nine_count();     
    }
} 


$conn->close();

?>
