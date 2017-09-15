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
function kdjfifteen () {
machining_price();
$sql="select min15_point_max from  point_number where min15_point_max=(select max(min15_point_max) from  point_number) order by min15_point_max desc limit 9;"
}  


function nine_count () {
$max=$begin_point;
$min=$begin_point;
$time_out_now=($time_hour*3600)+($time_min*60)+$time_second;
while( $time_out_now <= $time_out_begin) {
     machining_price();
$time_out_now=($time_hour*3600)+($time_min*60)+$time_second;
if ($begin_point>=$max)
{
    $max=$begin_point;
    $sql="update ".$table_name." set min15_point_max=$max order by id desc limit 1 ; ";
    echo $sql;
}
   if ($conn->query($sql) === TRUE) 
   {
    echo "max:新记录插入成功";
     } 
  else {
    echo "maxError: " . $sql . "<br>" . $conn->error;
}
  
if ($begin_point<=$min)
{
    $min=$begin_point; 
    $sql="update " .$table_name." set min15_point_min=$min order by id desc limit 1 ; ";
} 
   if ($conn->query($sql) === TRUE) {
    echo "min:新记录插入成功";
     } 
  else {
    echo "minError: " . $sql . "<br>" . $conn->error;
}
kdjfifteen(); #begin:kdj
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
// echo $result;
if ($result->num_rows > 0) {
    // 输出数据
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["begin_point"]. " " . $row["stat_time_hour"]. "<br>";
    }
} else {
    echo "0 defult";
}
#code begin
while(1==1) {    
machining_price();
    if ($time_hour<9 or ($time_hour==9 and $time_min<30)) {
    sleep_time();
    }elseif ($time_hour=="11" and $time_min>"30") {
    sleep_time();     
    }elseif ($time_hour=="15" and $time_min>"1") {
      echo "3point!";
    exit(0);    
    }
    
    if ($time_hour==9 and $time_min==30) {
    $time_length=960-$time_second;
    }elseif ($time_hour=="13" and $time_min=="0") {
    $time_length=960-$time_second;  
    }else{
    $time_length=900-$time_second;    
    }
$sql = "select stat_time_min from ".$table_name." order by id desc limit 1;";    
    $result = $conn->query($sql);
    if ($result<>$time_min){
    $time_out_begin=($time_hour*3600)+($time_min*60)+$time_second+$time_length;
$sql = "insert into ".$table." (stat_date,stat_time_hour,stat_time_min,begin_point) VALUES ('$stat_date','$time_hour','$time_min','$begin_point')";    
   $conn->query($sql); 
   nine_count();     
    }
} 


$conn->close();

?>
