<?php
function kdjfifteen () {
global $begin_point,$conn,$table_name;
machining_price();
$sql="select max(min15_point_max) from (select * from $table_name order by id desc limit 9) as a;";
$result=mysqli_query($conn,$sql);
$row=mysqli_fetch_row($result);
$min15_point_max=$row[0];
$sql="select min(min15_point_min) from (select * from $table_name order by id desc limit 9) as a;";
$result=mysqli_query($conn,$sql);
$row=mysqli_fetch_row($result);
$min15_point_min=$row[0];
echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~".$min15_point_min;
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
?>
