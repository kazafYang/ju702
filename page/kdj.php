include 'config_inc.php';
class Kdj {
  public $conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);

  function kdjfifteen () {
    global $log,$begin_point,$conn,$table_name;
    machining_price();
    $row=result_select("select max(min15_point_max) from (select * from $table_name order by id desc limit 9) as a;");	  
    $min15_point_max=$row[0];
    $row=result_select("select min(min15_point_min) from (select * from $table_name order by id desc limit 9) as a;");	  
    $min15_point_min=$row[0];
    $log -> log_work("min15_point_min:$min15_point_min");
    $row=result_select("select min15_k,min15_d from $table_name order by id  desc  limit 1,1;");	  
    $min15_k=$row[min15_k];   
    $min15_d=$row[min15_d];
    $log -> log_work("begin_point:$begin_point~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~min15_k:$min15_k~min15_d:$min15_d\n");                      
    $rsv=($begin_point-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
    $log -> log_work("rsv:$rsv");
    $k=2/3*$min15_k+1/3*$rsv;
    $d=2/3*$min15_d+1/3*$k;
    $j=3*$k-2*$d;
    $log -> log_work("15kdj:$k,$d,$j\n");  	  
    $sql="update $table_name set min15_k='$k' , min15_d='$d' , min15_j='$j' order by id desc limit 1 ; ";
    if ($conn->query($sql) === TRUE) {
      $log -> log_work("15kdjupdate:success\n");	     
    } 
    else {
      $log -> log_work("maxError: " . $sql . $conn->error."\n"); 	    
    }
  }

  function kdjthirty () {
    global $log, $begin_point,$conn,$table_name,$time_hour,$time_min;
    machining_price();
    $row=result_select("select max(min15_point_max) from (select * from $table_name order by id desc limit 18) as a;");
    $min15_point_max=$row[0];
    $row=result_select("select min(min15_point_min) from (select * from $table_name order by id desc limit 18) as a;");	  
    $min15_point_min=$row[0];
    if (($time_hour==9 and $time_min==30) or ($time_hour==10 and $time_min==0) or ($time_hour==10 and $time_min==30) or ($time_hour==11 and $time_min==0) or ($time_hour==13 and $time_min==0) or ($time_hour==13 and $time_min==30) or ($time_hour==14 and $time_min==0)) {
      $row=result_select("select min30_k,min30_d from $table_name order by id  desc  limit 1,1;");	  
      $min30_k=$row[min30_k]; 
      $min30_d=$row[min30_d];
    }
    else {
      $row=result_select("select min30_k,min30_d from $table_name order by id  desc  limit 2,1;");	  
      $min30_k=$row[min30_k]; 
      $min30_d=$row[min30_d];
    }  
    $log -> log_work("begin_point:$begin_point~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~min30_k:$min30_k~min30_d:$min30_d\n");                       
    $rsv=($begin_point-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
    $k=2/3*$min30_k+1/3*$rsv;
    $d=2/3*$min30_d+1/3*$k;
    $j=3*$k-2*$d;
    $log -> log_work("30kdj:$k,$d,$j\n");	  
    $sql="update $table_name set min30_k='$k' , min30_d='$d' , min30_j='$j' order by id desc limit 1 ; ";
    if ($conn->query($sql) === TRUE) 
    {
      $log -> log_work("30kdjupdate:success\n");
     } 
    else {
      $log -> log_work("maxError: " . $sql . $conn->error."\n");
    }  
  }

  function kdjsixty () {
    global $log, $begin_point,$conn,$table_name,$time_hour,$time_min;
  machining_price();
  $row=result_select("select max(min15_point_max) from (select * from $table_name order by id desc limit 18) as a;");
  $min15_point_max=$row[0];
  $row=result_select("select min(min15_point_min) from (select * from $table_name order by id desc limit 18) as a;");	  
  $min15_point_min=$row[0];
  if (($time_hour==9 and $time_min==30) or ($time_hour==10 and $time_min==0) or ($time_hour==10 and $time_min==30) or ($time_hour==11 and $time_min==0) or ($time_hour==13 and $time_min==0) or ($time_hour==13 and $time_min==30) or ($time_hour==14 and $time_min==0)) {
  $row=result_select("select min30_k,min30_d from $table_name order by id  desc  limit 1,1;");	  
  $min30_k=$row[min30_k]; 
  $min30_d=$row[min30_d];
  }
  else {
  $row=result_select("select min30_k,min30_d from $table_name order by id  desc  limit 2,1;");	  
  $min30_k=$row[min30_k]; 
  $min30_d=$row[min30_d];
  }  
  $log -> log_work("begin_point:$begin_point~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~min30_k:$min30_k~min30_d:$min30_d\n");                       
  $rsv=($begin_point-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
  $k=2/3*$min30_k+1/3*$rsv;
  $d=2/3*$min30_d+1/3*$k;
  $j=3*$k-2*$d;
  $log -> log_work("30kdj:$k,$d,$j\n");	  
  $sql="update $table_name set min30_k='$k' , min30_d='$d' , min30_j='$j' order by id desc limit 1 ; ";
     if ($conn->query($sql) === TRUE) 
     {
      $log -> log_work("30kdjupdate:success\n");
       } 
    else {
      $log -> log_work("maxError: " . $sql . $conn->error."\n");
  }  
  }  
  function kdjsixty () {	  
    global $log, $begin_point,$conn,$table_name,$time_hour,$time_min;
    $log -> log_work("comming kdjsixty");		  
    machining_price();
    $row=result_select("select max(min15_point_max) from (select * from $table_name order by id desc limit 36) as a;");	  
    $min15_point_max=$row[0];
    $row=result_select("select min(min15_point_min) from (select * from $table_name order by id desc limit 36) as a;");	  
    $min15_point_min=$row[0];
    $log -> log_work("time_hour:$time_hour\n");
    if (($time_hour==9 and $time_min==30) or ($time_hour==10 and $time_min==30) or ($time_hour==13 and $time_min==0) or ($time_hour==14 and $time_min==0)) {
      $row=result_select("select min60_k,min60_d from $table_name order by id  desc  limit 1,1;");	  
      $min60_k=$row[min60_k];
      $min60_d=$row[min60_d];
    }
    elseif (($time_hour==9 and $time_min==45) or ($time_hour==10 and $time_min==45) or ($time_hour==13 and $time_min==15) or ($time_hour==14 and $time_min==15)) {
      $row=result_select("select min60_k,min60_d from $table_name order by id  desc  limit 2,1;");	  
      $min60_k=$row[min60_k];
      $min60_d=$row[min60_d];
    }
    elseif(($time_hour==10 and $time_min==0) or ($time_hour==11 and $time_min==0) or ($time_hour==13 and $time_min==30) or ($time_hour==14 and $time_min==30)) {
      $row=result_select("select min60_k,min60_d from $table_name order by id  desc  limit 3,1;");	  
      $min60_k=$row[min60_k];
      $min60_d=$row[min60_d];
    }
    else{
      $row=result_select("select min60_k,min60_d from $table_name order by id  desc  limit 4,1;");	  
      $min60_k=$row[min60_k];
      $min60_d=$row[min60_d];
    }
    $log -> log_work("begin_point:$begin_point~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~min60_k:$min60_k~min60_d:$min60_d\n");   
    $rsv=($begin_point-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
    $k=2/3*$min60_k+1/3*$rsv;
    $d=2/3*$min60_d+1/3*$k;
    $j=3*$k-2*$d;
    $log -> log_work("60kdj:$k,$d,$j\n");
    $sql="update $table_name set min60_k='$k' , min60_d='$d' , min60_j='$j' order by id desc limit 1 ; ";
       if ($conn->query($sql) === TRUE) 
       {
        $log -> log_work("60kdjupdate:success\n");
         } 
      else {
        $log -> log_work("60kdjError: " . $sql . $conn->error."\n");
    }  
  }

  function kdjtwohour () {
    global $log, $begin_point,$conn,$table_name,$stat_date,$time_hour,$time_min;
    $log -> log_work("comming two_hour");		   
    machining_price();
    $row=result_select("select max(min15_point_max) from (select * from $table_name order by id desc limit 72) as a;");	   
    $min15_point_max=$row[0];
    $row=result_select("select min(min15_point_min) from (select * from $table_name order by id desc limit 72) as a;");	   
    $min15_point_min=$row[0];
    if ($time_hour<13) {
    $row=result_select("select min120_k,min120_d from $table_name where stat_date<'$stat_date' order by id  desc  limit 0,1;");	  
    $min120_k=$row[min120_k];
    $min120_d=$row[min120_d];
    }
    elseif ($time_hour>=13) {
    $row=result_select("select min120_k,min120_d from $table_name where stat_time_hour='11' and stat_time_min='15' order by id  desc  limit 1;");	  
    $min120_k=$row[min120_k];
    $min120_d=$row[min120_d];
    }
    $log -> log_work("begin_point:$begin_point~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~\n");   
    $rsv=($begin_point-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
    $k=2/3*$min120_k+1/3*$rsv;
    $d=2/3*$min120_d+1/3*$k;
    $j=3*$k-2*$d;
    $log -> log_work("120kdj:$k,$d,$j\n");
    $sql="update $table_name set min120_k='$k' , min120_d='$d' , min120_j='$j' order by id desc limit 1 ; ";
     if ($conn->query($sql) === TRUE) 
     {
      $log -> log_work("120kdjupdate:success\n");
       } 
    else {
      $log -> log_work("120kdjError: " . $sql . $conn->error."\n");
    }  
  }

  function kdjday () {
  global $log, $begin_point,$conn,$table_name,$stat_date,$kdjday_k,$kdjday_d;
  machining_price();
  $row=result_select("select max(min15_point_max) from (select * from $table_name order by id desc limit 144) as a;");	  
  $min15_point_max=$row[0];
  $row=result_select("select min(min15_point_min) from (select * from $table_name order by id desc limit 144) as a;");	  
  $min15_point_min=$row[0];
  $row=result_select("select kdjday_k,kdjday_d from $table_name where stat_date<'$stat_date' order by id desc limit 1;");	  
  $kdjday_k=$row[kdjday_k]; 
  $kdjday_d=$row[kdjday_d];
  $log -> log_work("begin_point:$begin_point~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~kdjday_k:$kdjday_k~kdjday_d:$kdjday_d\n");                       
  $rsv=($begin_point-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
  $k=2/3*$kdjday_k+1/3*$rsv;
  $d=2/3*$kdjday_d+1/3*$k;
  $j=3*$k-2*$d;
  $log -> log_work("daykdj:$k,$d,$j\n");
  $sql="update $table_name set kdjday_k='$k' , kdjday_d='$d' , kdjday_j='$j' order by id desc limit 1 ; ";
   if ($conn->query($sql) === TRUE) 
   {
    $log -> log_work("daykdjupdate:success\n");	     
     } 
  else {
    $log -> log_work("daykdj:updateError: " . $sql . $conn->error."\n");	    
  }  
  }

}
