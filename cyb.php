<?php
include 'config_inc.php';

$table_name="point_number";
$code="sz399006";
$begin_point="";
$stat_date="";
$time_hour="";
$time_min="";
$time_second="";
$time_length=0;

function machining_price () 
{
echo "comming machining_price\n";
global $stat_date,$time_hour,$time_min,$time_second,$begin_point,$code;
$url='http://hq.sinajs.cn/list=$code'; 
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
echo "comming sleep_time\n";
while(($time_hour<>9 and $time_min<>30) or $time_hour<>13) {
machining_price();
}
                       }
function kdjfifteen () {
global $begin_point,$conn,$table_name;
machining_price();
$sql="select min15_point_max from  $table_name where min15_point_max=(select max(min15_point_max) from  $table_name) order by min15_point_max desc limit 9;";
$result = $conn->query($sql);
$row=$result->fetch_assoc();
$min15_point_max=$row[min15_point_max];
$sql="select min15_point_min from  $table_name where min15_point_min=(select min(min15_point_min) from  $table_name) order by min15_point_min desc limit 9;";
$result = $conn->query($sql);
$row=$result->fetch_assoc();
$min15_point_min=$row[min15_point_min];
$sql="select min15_k from $table_name order by id  desc  limit 1,1;"; 
$result = $conn->query($sql);  
$row=$result->fetch_assoc();
$min15_k=$row[min15_k];   
$sql="select min15_d from $table_name order by id  desc  limit 1,1;";
$result = $conn->query($sql);  
$row=$result->fetch_assoc();
$min15_d=$row[min15_d];
echo "begin_point:$begin_point~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~min15_k:$min15_k~min15_d:$min15_d\n";                      
$rsv=($begin_point-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
echo "rsv:$rsv";
$k=2/3*$min15_k+1/3*$rsv;
$d=2/3*$min15_d+1/3*$k;
$j=3*$k
