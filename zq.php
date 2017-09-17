<?php
include 'config_inc.php';

$table_name="point_number_zq";
$code="512880";
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
$url='http://hq.sinajs.cn/list=sh'.$code; 
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
$j=3*$k-2*$d;
echo "15kdj:$k,$d,$j\n";
$sql="update $table_name set min15_k='$k' , min15_d='$d' , min15_j='$j' order by id desc limit 1 ; ";
   if ($conn->query($sql) === TRUE) 
   {
    echo "15kdjupdate:update\n";
     } 
  else {
    echo "maxError: " . $sql . $conn->error."\n";
}  
}  

function kdjthirty () {
global $begin_point,$conn,$table_name;
machining_price();
$sql="select min15_point_max from  $table_name where min15_point_max=(select max(min15_point_max) from  $table_name) order by min15_point_max desc limit 18;";
$result = $conn->query($sql);
$row=$result->fetch_assoc();
$min15_point_max=$row[min15_point_max];
$sql="select min15_point_min from  $table_name where min15_point_min=(select min(min15_point_min) from  $table_name) order by min15_point_min desc limit 18;";
$result = $conn->query($sql);
$row=$result->fetch_assoc();
$min15_point_min=$row[min15_point_min];

if (($time_hour_begin==9 and $time_min_begin==30) or ($time_hour_begin==10 and $time_min_begin==1) or ($time_hour_begin==10 and $time_min_begin==31) or ($time_hour_begin==11 and $time_min_begin==1) or ($time_hour_begin==13 and $time_min_begin==0) or ($time_hour_begin==13 and $time_min_begin==31) or ($time_hour_begin==14 and $time_min_begin==1)) {
$sql="select min30_k from $table_name order by id  desc  limit 1,1;";
$result = $conn->query($sql);  
$row=$result->fetch_assoc();
$min30_k=$row[min30_k]; 
$sql="select min30_d from $table_name order by id  desc  limit 1,1;";
$result = $conn->query($sql);  
$row=$result->fetch_assoc();
$min30_d=$row[min30_d];
}
else {
$sql="select min30_k from $table_name order by id  desc  limit 2,1;";
$result = $conn->query($sql);  
$row=$result->fetch_assoc();
$min30_k=$row[min30_k]; 
$sql="select min30_d from $table_name order by id  desc  limit 2,1;";
$result = $conn->query($sql);  
$row=$result->fetch_assoc();
$min30_d=$row[min30_d];
}  
echo "begin_point:$begin_point~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~min30_k:$min30_k~min30_d:$min30_d\n";                       
$rsv=($begin_point-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
$k=2/3*$min30_k+1/3*$rsv;
$d=2/3*$min30_d+1/3*$k;
$j=3*$k-2*$d;
echo "30kdj:$k,$d,$j\n";
$sql="update $table_name set min30_k='$k' , min30_d='$d' , min30_j='$j' order by id desc limit 1 ; ";
   if ($conn->query($sql) === TRUE) 
   {
    echo "30kdjupdate:update\n";
     } 
  else {
    echo "maxError: " . $sql . $conn->error."\n";
}  
}  

function kdjsixty () {
global $begin_point,$conn,$table_name;
machining_price();
$sql="select min15_point_max from  $table_name where min15_point_max=(select max(min15_point_max) from  $table_name) order by min15_point_max desc limit 36;";
$result = $conn->query($sql);
$row=$result->fetch_assoc();
$min15_point_max=$row[min15_point_max];
$sql="select min15_point_min from  $table_name where min15_point_min=(select min(min15_point_min) from  $table_name) order by min15_point_min desc limit 36;";
$result = $conn->query($sql);
$row=$result->fetch_assoc();
$min15_point_min=$row[min15_point_min];

if (($time_hour_begin==9 and $time_min_begin==30) or ($time_hour_begin==10 and $time_min_begin==31) or ($time_hour_begin==13 and $time_min_begin==0) or ($time_hour_begin==14 and $time_min_begin==1)) {
$sql="select min60_k from $table_name order by id  desc  limit 1,1;";
$result = $conn->query($sql);
$row=$result->fetch_assoc();
$min60_k=$row[min60_k];
$sql="select min60_d from $table_name order by id  desc  limit 1,1;";
$result = $conn->query($sql);
$row=$result->fetch_assoc();
$min60_d=$row[min60_d];
}
elseif (($time_hour_begin==9 and $time_min_begin==46) or ($time_hour_begin==10 and $time_min_begin==46) or ($time_hour_begin==13 and $time_min_begin==16) or ($time_hour_begin==14 and $time_min_begin==16)) {
$sql="select min60_k from $table_name order by id  desc  limit 2,1;";
$result = $conn->query($sql);
$row=$result->fetch_assoc();
$min60_k=$row[min60_k];
$sql="select min60_d from $table_name order by id  desc  limit 2,1;";
$result = $conn->query($sql);
$row=$result->fetch_assoc();
$min60_d=$row[min60_d];
}
elseif(($time_hour_begin==10 and $time_min_begin==1) or ($time_hour_begin==11 and $time_min_begin==1) or ($time_hour_begin==13 and $time_min_begin==31) or ($time_hour_begin==14 and $time_min_begin==31)) {
$sql="select min60_k from $table_name order by id  desc  limit 3,1;";
$result = $conn->query($sql);
$row=$result->fetch_assoc();
$min60_k=$row[min60_k];
$sql="select min60_d from $table_name order by id  desc  limit 3,1;";
$result = $conn->query($sql);
$row=$result->fetch_assoc();
$min60_d=$row[min60_d];
}
else{
$sql="select min60_k from $table_name order by id  desc  limit 4,1;";
$result = $conn->query($sql);
$row=$result->fetch_assoc();
$min60_k=$row[min60_k];
$sql="select min60_d from $table_name order by id  desc  limit 4,1;";
$result = $conn->query($sql);
$row=$result->fetch_assoc();
$min60_d=$row[min60_d];
}
echo "begin_point:$begin_point~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~min60_k:$min60_k~min60_d:$min60_d\n";   
$rsv=($begin_point-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
$k=2/3*$min60_k+1/3*$rsv;
$d=2/3*$min60_d+1/3*$k;
$j=3*$k-2*$d;
echo "60kdj:$k,$d,$j\n";
$sql="update $table_name set min60_k='$k' , min60_d='$d' , min60_j='$j' order by id desc limit 1 ; ";
   if ($conn->query($sql) === TRUE) 
   {
    echo "60kdjupdate:update\n";
     } 
  else {
    echo "60kdjError: " . $sql . $conn->error."\n";
}  
}  

function nine_count () {
global $time_hour,$time_min,$time_second,$begin_point,$table_name,$time_out_begin,$conn;
echo "comming nine_count\n";
$max=$begin_point;
$min=$begin_point;
$time_out_now=($time_hour*3600)+($time_min*60)+$time_second;
while( $time_out_now <= $time_out_begin) {
echo "time_out_now:$time_out_now~time_out_begin:$time_out_begin\n"; 
machining_price();
$time_out_now=($time_hour*3600)+($time_min*60)+$time_second;
  echo "$max--$begin_point\n";  
if ($begin_point>=$max)
{
    $max=$begin_point;
    echo "$max\n";
    $sql="update $table_name set min15_point_max=$max order by id desc limit 1 ;";
    echo $sql."\n";
}
   if ($conn->query($sql) === TRUE) 
   {
    echo "max新纪录更新成功\n";
     } 
  else {
    echo "max新纪录更新Error: " . $sql . $conn->error."\n";
}
  
if ($begin_point<=$min)
{
    $min=$begin_point; 
    $sql="update " .$table_name." set min15_point_min=$min order by id desc limit 1 ; ";
} 
   if ($conn->query($sql) === TRUE) {
    echo "min:新记录更新成功\n";
     } 
  else {
    echo "min新纪录更新Error: " . $sql . $conn->error."\n";
}
kdjfifteen(); #begin:kdj
kdjthirty();
kdjsixty();    
} 
}
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
machining_price();
    if ($time_hour<9 or ($time_hour==9 and $time_min<30)) {
      echo "comming mainwhile if--9\n";
    sleep_time();
    }elseif ($time_hour=="11" and $time_min>"30") {
    sleep_time();     
    }elseif ($time_hour=="15" and $time_min>"1") {
      echo "3point!\n";
  //  exit(0);    
    }
    
    if ($time_hour==9 and $time_min==30) {
    $time_length=960-$time_second;
    }elseif ($time_hour=="13" and $time_min=="0") {
    $time_length=960-$time_second;  
    }else{
    $time_length=900-$time_second;    
    }
$sql = "select id,stat_time_min from $table_name order by id desc limit 1;";    
    $result = $conn->query($sql);
    $row=$result->fetch_assoc();
    echo "stat_time_min:$row[stat_time_min]\n";
    if ($row[stat_time_min]<>$time_min){
    $time_out_begin=($time_hour*3600)+($time_min*60)+$time_second+$time_length;
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
