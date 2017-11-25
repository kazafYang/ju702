  <?php
  function machining_price () 
  {
  echo "comming machining_price\n";
  global $stat_date,$time_hour,$time_min,$time_second,$begin_point,$code,$buy_one_price,$sell_one_price;
  if ($code<500000) {
  $url='http://hq.sinajs.cn/list=sz'.$code; 
  }  else{
  $url='http://hq.sinajs.cn/list=sh'.$code; 
  } 

  $html = file_get_contents($url); 
  $pieces = explode(",", $html);
  $begin_point=$pieces[3];
  $buy_one_price=$pieces[6];  //买一价
  $sell_one_price=$pieces[7]; //卖一价 
  $stat_date=$pieces[30];
  $pieces = explode(":", $pieces[31]);    
  $time_hour=$pieces[0];
  $time_min=$pieces[1];
  $time_second=$pieces[2];
  } 
  function sleep_time () {
  echo "comming sleep_time\n";
  global $time_hour,$time_min;  
  while(($time_hour==9 and $time_min<30) or ($time_hour<13 and $time_hour>=11) or $time_hour<9) {
  machining_price();
  }
                         }

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

  function kdjthirty () {
  global $begin_point,$conn,$table_name,$time_hour,$time_min;
  machining_price();
  $sql="select max(min15_point_max) from (select * from $table_name order by id desc limit 18) as a;";
  $result=mysqli_query($conn,$sql);
  $row=mysqli_fetch_row($result);
  $min15_point_max=$row[0];
  $sql="select min(min15_point_min) from (select * from $table_name order by id desc limit 18) as a;";
  $result=mysqli_query($conn,$sql);
  $row=mysqli_fetch_row($result);
  $min15_point_min=$row[0];

  if (($time_hour==9 and $time_min==30) or ($time_hour==10 and $time_min==0) or ($time_hour==10 and $time_min==30) or ($time_hour==11 and $time_min==0) or ($time_hour==13 and $time_min==0) or ($time_hour==13 and $time_min==30) or ($time_hour==14 and $time_min==0)) {
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
 echo "~~~~~~".$time_hour;	  
  global $begin_point,$conn,$table_name,$time_hour,$time_min;
  machining_price();
  $sql="select max(min15_point_max) from (select * from $table_name order by id desc limit 36) as a;";
  $result=mysqli_query($conn,$sql);
  $row=mysqli_fetch_row($result);
  $min15_point_max=$row[0];
  $sql="select min(min15_point_min) from (select * from $table_name order by id desc limit 36) as a;";
  $result=mysqli_query($conn,$sql);
  $row=mysqli_fetch_row($result);
  $min15_point_min=$row[0];
  echo "---------------".$time_hour;
  if (($time_hour==9 and $time_min==30) or ($time_hour==10 and $time_min==30) or ($time_hour==13 and $time_min==0) or ($time_hour==14 and $time_min==0)) {
  $sql="select min60_k from $table_name order by id  desc  limit 1,1;";
  $result = $conn->query($sql);
  $row=$result->fetch_assoc();
  $min60_k=$row[min60_k];
  $sql="select min60_d from $table_name order by id  desc  limit 1,1;";
  $result = $conn->query($sql);
  $row=$result->fetch_assoc();
  $min60_d=$row[min60_d];
  }
  elseif (($time_hour==9 and $time_min==45) or ($time_hour==10 and $time_min==45) or ($time_hour==13 and $time_min==15) or ($time_hour==14 and $time_min==15)) {
  $sql="select min60_k from $table_name order by id  desc  limit 2,1;";
  $result = $conn->query($sql);
  $row=$result->fetch_assoc();
  $min60_k=$row[min60_k];
  $sql="select min60_d from $table_name order by id  desc  limit 2,1;";
  $result = $conn->query($sql);
  $row=$result->fetch_assoc();
  $min60_d=$row[min60_d];
  }
  elseif(($time_hour==10 and $time_min==0) or ($time_hour==11 and $time_min==0) or ($time_hour==13 and $time_min==30) or ($time_hour==14 and $time_min==30)) {
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
//kdj 120min
   function two_hour () {
  global $begin_point,$conn,$table_name,$stat_date,$time_hour,$time_min;
  machining_price();
  $sql="select max(min15_point_max) from (select * from $table_name order by id desc limit 72) as a;";
  $result=mysqli_query($conn,$sql);
  $row=mysqli_fetch_row($result);
  $min15_point_max=$row[0];
  $sql="select min(min15_point_min) from (select * from $table_name order by id desc limit 72) as a;";
  $result=mysqli_query($conn,$sql);
  $row=mysqli_fetch_row($result);
  $min15_point_min=$row[0];

  if ($time_hour<13) {
  $sql="select min120_k from $table_name where stat_date<'$stat_date' order by id  desc  limit 0,1;";
  $result = $conn->query($sql);
  $row=$result->fetch_assoc();
  $min120_k=$row[min120_k];
  $sql="select min120_d from $table_name where stat_date<'$stat_date' order by id  desc  limit 0,1;";
  $result = $conn->query($sql);
  $row=$result->fetch_assoc();
  $min120_d=$row[min120_d];
  }
  elseif ($time_hour>=13) {
  $sql="select min120_k from $table_name where stat_time_hour='11' and stat_time_min='15' order by id  desc  limit 1;";
  $result = $conn->query($sql);
  $row=$result->fetch_assoc();
  $min120_k=$row[min120_k];
  $sql="select min120_d from $table_name where stat_time_hour='11' and stat_time_min='15' order by id  desc  limit 1;";
  $result = $conn->query($sql);
  $row=$result->fetch_assoc();
  $min120_d=$row[min120_d];
  }
 
  echo "begin_point:$begin_point~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~min60_k:$min60_k~min60_d:$min60_d\n";   
  $rsv=($begin_point-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
  $k=2/3*$min120_k+1/3*$rsv;
  $d=2/3*$min120_d+1/3*$k;
  $j=3*$k-2*$d;
  echo "120kdj:$k,$d,$j\n";
  $sql="update $table_name set min120_k='$k' , min120_d='$d' , min120_j='$j' order by id desc limit 1 ; ";
     if ($conn->query($sql) === TRUE) 
     {
      echo "120kdjupdate:update\n";
       } 
    else {
      echo "120kdjError: " . $sql . $conn->error."\n";
  }  
  }

  //day kdj
  function kdjday () {
  global $begin_point,$conn,$table_name,$stat_date;
  machining_price();
  $sql="select max(min15_point_max) from (select * from $table_name order by id desc limit 144) as a;";
  $result=mysqli_query($conn,$sql);
  $row=mysqli_fetch_row($result);
  $min15_point_max=$row[0];
  $sql="select min(min15_point_min) from (select * from $table_name order by id desc limit 144) as a;";
  $result=mysqli_query($conn,$sql);
  $row=mysqli_fetch_row($result);
  $min15_point_min=$row[0];

  $sql="select kdjday_k from $table_name where stat_date<'$stat_date' order by id desc limit 1;";
  $result = $conn->query($sql);  
  $row=$result->fetch_assoc();
  $kdjday_k=$row[kdjday_k]; 
  $sql="select kdjday_d from $table_name where stat_date<'$stat_date' order by id desc limit 1;";
  $result = $conn->query($sql);  
  $row=$result->fetch_assoc();
  $kdjday_d=$row[kdjday_d];

  echo "begin_point:$begin_point~min15_point_max:$min15_point_max~min15_point_min:$min15_point_min~kdjday_k:$kdjday_k~kdjday_d:$kdjday_d\n";                       
  $rsv=($begin_point-$min15_point_min)/($min15_point_max-$min15_point_min)*100;
  $k=2/3*$kdjday_k+1/3*$rsv;
  $d=2/3*$kdjday_d+1/3*$k;
  $j=3*$k-2*$d;
  echo "daykdj:$k,$d,$j\n";
  $sql="update $table_name set kdjday_k='$k' , kdjday_d='$d' , kdjday_j='$j' order by id desc limit 1 ; ";
     if ($conn->query($sql) === TRUE) 
     {
      echo "daykdjupdate:update\n";
       } 
    else {
      echo "daykdj:updateError: " . $sql . $conn->error."\n";
  }  
  }
  // cci count
  function cci () {
  echo "comming cci\n";
  $min15_point_max= array();
  $min15_point_min = array();
  $now_price = array();
  global $conn,$table_name; 

  for ($i=1;$i<15;$i++){
  $sql="select max(min15_point_max) from (select * from $table_name order by id desc limit $i,1) as a;";
  echo $sql."/n";
  $result=mysqli_query($conn,$sql);
  $row=mysqli_fetch_row($result);
  $date_point_max=$row[0];

  $sql="select min(min15_point_min) from (select * from $table_name order by id desc limit $i,1) as a;";
  $result=mysqli_query($conn,$sql);
  $row=mysqli_fetch_row($result);
  $date_point_min=$row[0];

  $sql="select now_price from $table_name order by id desc limit 1,$i;";
  $result=mysqli_query($conn,$sql);
  $row=mysqli_fetch_row($result);
  $date_now_price=$row[0];

  $min15_point_max[]=$date_point_max;
  $min15_point_min[]=$date_point_min;
  $now_price[]=$date_now_price;
  } 

  $typ1=($min15_point_max[0]+$min15_point_min[0]+$now_price[0])/3;
  $typ2=($min15_point_max[1]+$min15_point_min[1]+$now_price[1])/3;
  $typ3=($min15_point_max[2]+$min15_point_min[2]+$now_price[2])/3;
  $typ4=($min15_point_max[3]+$min15_point_min[3]+$now_price[3])/3;
  $typ5=($min15_point_max[4]+$min15_point_min[4]+$now_price[4])/3;
  $typ6=($min15_point_max[5]+$min15_point_min[5]+$now_price[5])/3;
  $typ7=($min15_point_max[6]+$min15_point_min[6]+$now_price[6])/3;
  $typ8=($min15_point_max[7]+$min15_point_min[7]+$now_price[7])/3;
  $typ9=($min15_point_max[8]+$min15_point_min[8]+$now_price[8])/3;
  $typ10=($min15_point_max[9]+$min15_point_min[9]+$now_price[9])/3;
  $typ11=($min15_point_max[10]+$min15_point_min[10]+$now_price[10])/3;
  $typ12=($min15_point_max[11]+$min15_point_min[11]+$now_price[11])/3;
  $typ13=($min15_point_max[12]+$min15_point_min[12]+$now_price[12])/3;
  $typ14=($min15_point_max[13]+$min15_point_min[13]+$now_price[13])/3;
  $matyp=($typ1+$typ2+$typ3+$typ4+$typ5+$typ6+$typ7+$typ8+$typ9+$typ10+$typ11+$typ12+$typ13+$typ14)/14;
  $aytyp=(abs($typ1-$matyp)+abs($typ2-$matyp)+abs($typ3-$matyp)+abs($typ4-$matyp)+abs($typ5-$matyp)+abs($typ6-$matyp)+abs($typ7-$matyp)+abs($typ8-$matyp)+abs($typ9-$matyp)+abs($typ10-$matyp)+abs($typ11-$matyp)+abs($typ12-$matyp)+abs($typ13-$matyp)+abs($typ14-$matyp))/14;
  $cci=($typ1-$matyp)/$aytyp/0.015;
  $sql="update $table_name set cci='$cci' order by id desc limit 1 ; ";
     if ($conn->query($sql) === TRUE) {
      echo "cci:新记录更新成功\n";
       } 
    else {
      echo "cci新纪录更新Error: " . $sql . $conn->error."\n";
  }
  }  
 
function analyse () {
    echo "comming analyse"."\n";
    global $table_name,$code,$conn,$begin_point;
      //五日十日均线数据计算	
      $sql = "select avg(now_price) from (select now_price from $table_name order by id desc limit 0,80) as a;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result); 
      $first_min5_avgprice=$row[0];
      mysqli_free_result($result);  //释放结果集
      $sql = "select avg(now_price) from (select now_price from $table_name order by id desc limit 0,160) as a;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result); 
      $first_min10_avgprice=$row[0];
      mysqli_free_result($result);  //释放结果集 	  
      $sql = "select avg(now_price) from (select now_price from $table_name order by id desc limit 16,80) as a;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result); 
      $second_min5_avgprice=$row[0];
      mysqli_free_result($result);  //释放结果集
      $sql = "select avg(now_price) from (select now_price from $table_name order by id desc limit 16,160) as a;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result); 
      $second_min10_avgprice=$row[0];
      mysqli_free_result($result);  //释放结果集	
      //获取分时kdj数据;
      $sql = "SELECT code,stat_date,stat_time_hour,stat_time_min,min15_k,min15_d,min15_j,min30_k,min30_d,min30_j,min60_k,min60_d,min60_j,min120_k,min120_d,min120_j,kdjday_k,kdjday_d,kdjday_j,cci,buy_one_price,sell_one_price FROM $table_name order by id desc limit 1";                                                                  
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $trade_code=$row[code];$trade_buy_price=$row[buy_one_price];$trade_sell_price=$row[sell_one_price];
      $trade_stat_date=$row[stat_date];$trade_time_hour=$row[stat_time_hour];$trade_time_min=$row[stat_time_min];
      $trade_min15_k=round($row[min15_k],2);$trade_min15_d=round($row[min15_d],2);$trade_min15_j=round($row[min15_j],2);
      $trade_min30_k=round($row[min30_k],2);$trade_min30_d=round($row[min30_d],2);$trade_min30_j=round($row[min30_j],2);
      $trade_min60_k=round($row[min60_k],2);$trade_min60_d=round($row[min60_d],2);$trade_min60_j=round($row[min60_j],2);
      $trade_min120_k=round($row[min120_k],2);$trade_min120_d=round($row[min120_d],2);$trade_min120_j=round($row[min120_j],2);	  
      $trade_day_k=round($row[kdjday_k],2);$trade_day_d=round($row[kdjday_d],2);$trade_day_j=round($row[kdjday_j],2);
      mysqli_free_result($result);  //释放结果集	  
      //获取上一次分时kdj数据用来判断kdj金叉死叉;
      $sql = "SELECT code,stat_date,stat_time_hour,stat_time_min,min15_k,min15_d,min15_j,min30_k,min30_d,min30_j,min60_k,min60_d,min60_j,min120_k,min120_d,min120_j,kdjday_k,kdjday_d,kdjday_j,cci,buy_one_price,sell_one_price FROM $table_name order by id desc limit 1,1";                                                                  
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $trade_second_min15_k=round($row[min15_k],2);$trade_second_min15_d=round($row[min15_d],2);$trade_second_min15_j=round($row[min15_j],2);
      $trade_second_min30_k=round($row[min30_k],2);$trade_second_min30_d=round($row[min30_d],2);$trade_second_min30_j=round($row[min30_j],2);
      $trade_second_min60_k=round($row[min60_k],2);$trade_second_min60_d=round($row[min60_d],2);$trade_second_min60_j=round($row[min60_j],2);
      $trade_second_min120_k=round($row[min120_k],2);$trade_second_min120_d=round($row[min120_d],2);$trade_second_min120_j=round($row[min120_j],2);	  
      $trade_second_day_k=round($row[kdjday_k],2);$trade_second_day_d=round($row[kdjday_d],2);$trade_second_day_j=round($row[kdjday_j],2); 	
      mysqli_free_result($result);  //释放结果集	  
     //获取bite倍数信息，给各个阶段加倍
      $str="";
      $switched=0; 	  
      $sql = "select bite as a from trade_bate  order by id asc ;";    
      $result=mysqli_query($conn,$sql);
          while ($row=mysqli_fetch_row($result))
    {
        $a = sprintf("%s,%s",$row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9],$row[10],$row[11]);
        $str=$str.$a;    
    }
        $pieces = explode(",", $str);
      $type1=$pieces[0];$type2=$pieces[1];$type3=$pieces[2];$type4=$pieces[3];$type5=$pieces[4];$type6=$pieces[5];
      $type7=$pieces[6];$type8=$pieces[7];$type9=$pieces[8];$type10=$pieces[9];$type11=$pieces[10];$type12=$pieces[11];
      echo $type1.$type2.$type3.$type4.$type5.$type6.$type7.$type8."~~~~~~~~~~~~~~~~~~~";
      mysqli_free_result($result);  //释放结果集
      //判断当日数据是否已经存在
      $sql = "select count(*) from hive_number where code='$trade_code' and stat_date='$trade_stat_date';";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      if($row[0]==0){
      //拿取hive_number表数据条数获得插入的下一个id
      $sql = "select count(*) from hive_number;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $hive_number_id=$row[0]+1;
      mysqli_free_result($result);  //释放结果集
      //拿取hive_number的基础属性
      $sql = "select switched,total_money,useable_money,total_number,useable_sell_number,total_sell_number,cost_price from hive_number where code='$trade_code' order by stat_date desc limit 1;";    
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $switched=$row[switched];$total_money=$row[total_money];$useable_money=$row[useable_money]; $total_number=$row[total_number];$useable_sell_number=$row[total_number];$total_sell_number=$row[total_number];$cost_price=$row[cost_price];
      mysqli_free_result($result);  //释放结果集 
      //计算最近2日的平均买入成本   switch
      $cost_stat_date=date("Y-m-d",strtotime("-2 day"));  
      $sql = "select avg(trade_sell_price) from trade_history where code='$trade_code' and trade_type>=5 and stat_date>='$cost_stat_date';";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $cost_price=round($row[0],3);
      mysqli_free_result($result);  //释放结果集   
      $sql = "insert into hive_number values ('$hive_number_id','$trade_code','$switched','$total_money','$useable_money','$total_number','$useable_sell_number','$total_sell_number','$cost_price','$trade_stat_date');";                                                                  
      $conn->query($sql);   
      } 
	  else{
        //拿取hive_number的基础属性
      $sql = "select switched,total_money,useable_money,total_number,useable_sell_number,total_sell_number,cost_price from hive_number where code='$trade_code' order by stat_date desc limit 1;";    
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
       $switched=$row[switched];$total_money=$row[total_money];$useable_money=$row[useable_money]; $total_number=$row[total_number];$useable_sell_number=$row[useable_sell_number];$total_sell_number=$row[$total_sell_number];$cost_price=$row[cost_price];
      mysqli_free_result($result);  //释放结果集
      }
  echo $switched."开始判断\n";	  
  if($switched==1){
  echo $switched."判断开关开启了\n";	  
      //sell判断
 //判断当前code是否具备卖出资格，后续可以在这里加上开关等限制性的行为；昨日的总数量，就是今日的可卖数量；$switched=1是开关打开状态
    if($useable_sell_number>1 and ($trade_day_k >= 80 or $trade_day_d >= 80)){ 
      //超买情况下的15分钟卖出指标
      echo "comming switch-sell"."\n";
      if($trade_min15_k<$trade_min15_d and $trade_second_min15_k>$trade_second_min15_d)	    
      {
	   echo "comming -sell"."\n";	 
      //提前计算数量，避免导致超出数量限制的问题；
      $number=$useable_sell_number*$type1/100;
      $number=round($number); 
      $sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=1;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      if($row[0]==0 and $useable_sell_number>=$number and ($number*$trade_sell_price*100>=1000)){
      $sql = "select count(*) from trade_history;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;  
      //插入交易历史  
      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','1','$trade_buy_price','$trade_sell_price');";                                                                  
      $conn->query($sql); 
      //更新hive_number表数据
      $useable_sell_number=$useable_sell_number-$number;
      $total_number=$total_number-$number;  
      $useable_money=$useable_money+($number*$trade_sell_price*100);
      $sql = "update hive_number set useable_sell_number='$useable_sell_number',total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);
      $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);  
         }
      } 

  //30min  
     if($trade_min30_k<$trade_min30_d and $trade_second_min30_k>$trade_second_min30_d)	 
     {
         $number=$useable_sell_number*$type2/100;
        $number=round($number);
      $sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=2;";    
      echo $sql."~~~~~~30~~~~~~~/n";
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);   
      if($row[0]==0  and $useable_sell_number>=$number and ($number*$trade_sell_price*100>=1000)){
           $sql = "select count(*) from trade_history;";    
           $result=mysqli_query($conn,$sql);
           $row=mysqli_fetch_row($result);
           $trade_id=$row[0]+1;
           $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','2','$trade_buy_price','$trade_sell_price');";                                                                  
           $result = $conn->query($sql); 
                 //更新hive_number表数据
      $useable_sell_number=$useable_sell_number-$number;
      $total_number=$total_number-$number;  
      $useable_money=$useable_money+($number*$trade_sell_price*100);  
      $sql = "update hive_number set useable_sell_number='$useable_sell_number',total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date' order by id desc limit 1;";                                                                  
      $conn->query($sql);
      $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);  
         }
      } 

   //60分钟          
     if($trade_min60_k<$trade_min60_d and $trade_second_min60_k>$trade_second_min60_d)
     {
      $number=$useable_sell_number*$type3/100;
      $number=round($number);
      $sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=3;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);  
      if($row[0]==0 and $useable_sell_number>=$number and ($number*$trade_sell_price*100>=1000)){
      $sql = "select count(*) from trade_history;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;  
      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','3','$trade_buy_price','$trade_sell_price');";                                                                  
      $result = $conn->query($sql);
            //更新hive_number表数据
      $useable_sell_number=$useable_sell_number-$number;
      $total_number=$total_number-$number;       
      $useable_money=$useable_money+($number*$trade_sell_price*100);  
      $sql = "update hive_number set useable_sell_number='$useable_sell_number',total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date' order by id desc limit 1;";                                                                  
      $conn->query($sql);
      $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);  
         }
      }
	    //120分钟          
     if($trade_min120_k<$trade_min120_d and $trade_second_min120_k>$trade_second_min120_d)	 
     {
      $number=$useable_sell_number*$type3/100;
      $number=round($number);
      $sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=4;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);  
      if($row[0]==0 and $useable_sell_number>=$number and ($number*$trade_sell_price*100>=1000)){
      $sql = "select count(*) from trade_history;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;  
      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','4','$trade_buy_price','$trade_sell_price');";                                                                  
      $result = $conn->query($sql);
            //更新hive_number表数据
      $useable_sell_number=$useable_sell_number-$number;
      $total_number=$total_number-$number;       
      $useable_money=$useable_money+($number*$trade_sell_price*100);  
      $sql = "update hive_number set useable_sell_number='$useable_sell_number',total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date' order by id desc limit 1;";                                                                  
      $conn->query($sql);
      $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);  
         }
      }    
    } //日线超买完成
    
    //buy,买入开关限制，限制可用金额不足的情况，和标的开关关闭的情况，关闭 switch=0；
  if($useable_money>1000  and ($trade_day_k <= 20 or $trade_day_d <= 20)){
	echo "comming switch-buy"."\n"; 
	  //15分钟条件严格一点
    if ($trade_min15_k <=15 or $trade_min15_d <=20){
	echo "comming -buy"."\n";  
        $sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=21;";    
        echo "commingxxxxxxxxxxxxx".$sql;
        $result=mysqli_query($conn,$sql);
        $row=mysqli_fetch_row($result);
        if($row[0]==0){
         $sql = "select count(*) from trade_history;";    
         $result=mysqli_query($conn,$sql);
         $row=mysqli_fetch_row($result);
         $trade_id=$row[0]+1;
         $number=11/$trade_buy_price*$type5;
         $number=round($number);
         $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','21','$trade_buy_price','$trade_sell_price');";                                                                  
         $conn->query($sql);
         //更新hive_number表数据
         $total_number=$total_number+$number;  
          $useable_money=$useable_money-($number*$trade_buy_price*100);  
          $sql = "update hive_number set total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date' order by id desc limit 1;";                                                                  
           $conn->query($sql);
           $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
            $conn->query($sql);  
         }
      }  
    if ($trade_min30_k <=15 or $trade_min30_d <=20){
      $sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=22;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);   
        if($row[0]==0){
        $sql = "select count(*) from trade_history;";    
        $result=mysqli_query($conn,$sql);
        $row=mysqli_fetch_row($result);
        $trade_id=$row[0]+1;
            $number=11/$trade_buy_price*$type6;
        $number=round($number);
        $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','22','$trade_buy_price','$trade_sell_price');";                                                                  
      $conn->query($sql);
            //更新hive_number表数据
      $total_number=$total_number+$number;  
      $useable_money=$useable_money-($number*$trade_buy_price*100);  
      $sql = "update hive_number set total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date' order by id desc limit 1;";                                                                  
      $conn->query($sql);
      $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);   
         }
      }   
    if ($trade_min60_k <=15 or $trade_min60_d <=20){
      $sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=23;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);    
        if($row[0]==0){
      $sql = "select count(*) from trade_history;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;  
      $number=11/$trade_buy_price*$type7;
      $number=round($number);
      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','23','$trade_buy_price','$trade_sell_price');";                                                                  
      $conn->query($sql);
            //更新hive_number表数据
      $total_number=$total_number+$number;  
      $useable_money=$useable_money-($number*$trade_buy_price*100);  
      $sql = "update hive_number set total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date' order by id desc limit 1;";                                                                  
      $conn->query($sql);
      $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);   
         }
      }
	  //120日线超卖
      if ($trade_min120_k <=15 or $trade_min120_d <=20){
      $sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=24;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);    
        if($row[0]==0){
      $sql = "select count(*) from trade_history;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;  
      $number=11/$trade_buy_price*$type7;
      $number=round($number);
      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','24','$trade_buy_price','$trade_sell_price');";                                                                  
      $conn->query($sql);
            //更新hive_number表数据
      $total_number=$total_number+$number;  
      $useable_money=$useable_money-($number*$trade_buy_price*100);  
      $sql = "update hive_number set total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date' order by id desc limit 1;";                                                                  
      $conn->query($sql);
      $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);   
         }
      }  	  
  }    //日线超卖完成
  if(($trade_day_k>40 and $trade_day_k<70) or ($trade_day_d>35 and $trade_day_d<65)){
    //回转交易策略的位置,记录回转交易的标志是数据库字段 huizhuan_status
	//15分钟回转使用死叉交易卖出 switch
	echo "comming switch-rel~~~~~~~~~"."\n";
    if($trade_min15_k>=75 or $trade_min15_d >= 75 and $trade_min15_j < $trade_min15_k and $trade_min15_j < $trade_min15_d and $useable_sell_number>1 ){
	echo "comming -rel-sell~~~~~~~~~"."\n";
        $number=11/$trade_buy_price*$type4;
        $number=round($number); 
        $sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=5;";    
        $result=mysqli_query($conn,$sql);
        $row=mysqli_fetch_row($result);
      if($row[0]<=3 and $useable_sell_number>=$number){
      $sql = "select count(*) from trade_history;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;  
      //插入交易历史  
      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','5','$trade_buy_price','$trade_sell_price');";                                                                  
      $conn->query($sql); 
      //更新hive_number表数据
      $useable_sell_number=$useable_sell_number-$number;
      $total_number=$total_number-$number;  
      $useable_money=$useable_money+($number*$trade_sell_price*100);
      $sql = "update hive_number set useable_sell_number='$useable_sell_number',total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);
      $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);
      }
  }    
	  //回转15分钟超买条件
       if($trade_min15_k>=80 or $trade_min15_d >= 75){
	echo "comming -rel-sell~~~~~~~~~"."\n";
        $number=11/$trade_buy_price*$type11;
        $number=round($number); 
        $sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=6;";    
        $result=mysqli_query($conn,$sql);
        $row=mysqli_fetch_row($result);
      if($row[0]==0 and $useable_sell_number>=$number){
      $sql = "select count(*) from trade_history;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;  
      //插入交易历史  
      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','6','$trade_buy_price','$trade_sell_price');";                                                                  
      $conn->query($sql); 
      //更新hive_number表数据
      $useable_sell_number=$useable_sell_number-$number;
      $total_number=$total_number-$number;  
      $useable_money=$useable_money+($number*$trade_sell_price*100);
      $sql = "update hive_number set useable_sell_number='$useable_sell_number',total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);
      $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);
      }
  }	  
     if($trade_min30_k >= 80  or $trade_min30_d >= 75){
       $number=11/$trade_buy_price*$type4;
      $number=round($number); 
      $sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=7;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
         if($row[0]<=3 and  $useable_sell_number>=$number){
      $sql = "select count(*) from trade_history;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;  
      //插入交易历史  
      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','7','$trade_buy_price','$trade_sell_price');";                                                                  
      $conn->query($sql); 
      //更新hive_number表数据
      $useable_sell_number=$useable_sell_number-$number;
      $total_number=$total_number-$number;  
      $useable_money=$useable_money+($number*$trade_sell_price*100);
      $sql = "update hive_number set useable_sell_number='$useable_sell_number',total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);
      $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);
      }
	  }
    if($trade_min60_k >= 80  or $trade_min60_d >= 75){
      $number=11/$trade_buy_price*$type4;
      $number=round($number); 
      $sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=8;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
         if($row[0]<=3  and $useable_sell_number>=$number){
      $sql = "select count(*) from trade_history;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;  
      //插入交易历史  
      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','8','$trade_buy_price','$trade_sell_price');";                                                                  
      $conn->query($sql); 
      //更新hive_number表数据
      $useable_sell_number=$useable_sell_number-$number;
      $total_number=$total_number-$number;  
      $useable_money=$useable_money+($number*$trade_sell_price*100);
      $sql = "update hive_number set useable_sell_number='$useable_sell_number',total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);
      $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);
      }
	  }
      if($trade_min120_k >= 80  or $trade_min120_d >= 75){
      $number=11/$trade_buy_price*$type4;
      $number=round($number); 
      $sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=9;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
         if($row[0]<=3  and $useable_sell_number>=$number){
      $sql = "select count(*) from trade_history;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;  
      //插入交易历史  
      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','9','$trade_buy_price','$trade_sell_price');";                                                                  
      $conn->query($sql); 
      //更新hive_number表数据
      $useable_sell_number=$useable_sell_number-$number;
      $total_number=$total_number-$number;  
      $useable_money=$useable_money+($number*$trade_sell_price*100);
      $sql = "update hive_number set useable_sell_number='$useable_sell_number',total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);
      $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);
      }
	  }	  
         
       //回转买入，当前价低于最低卖出价5个点，即可等量/分批加码回收筹码；增加trade_type，标志回转交易，然后沿用status标志，这样比较好；如果这样的话不能判断出数据是否已经被处理了，所以我还需要一个步骤就是将已经对比的status的值=2;
       //判断已经交易完成的，然后处理结束后将status变更为2  
	//回转15分钟买入  
      if ($trade_min15_k <=20 or $trade_min15_d <=20){
      $sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=25;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);    
      if($row[0]==0 and $useable_money>=1000){
      $sql = "select count(*) from trade_history;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;  
      $number=11/$trade_buy_price*$type12;
      $number=round($number);
      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','25','$trade_buy_price','$trade_sell_price');";                                                                  
      $conn->query($sql);
            //更新hive_number表数据
      $total_number=$total_number+$number;  
      $useable_money=$useable_money-($number*$trade_buy_price*100);  
      $sql = "update hive_number set total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date' order by id desc limit 1;";                                                                  
      $conn->query($sql);
      $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);   
         }
      }
      $sql = "select id,trade_sell_price,trade_type from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type in(5,6,7,8,9) and status=1 limit 1;";    
      $result = $conn->query($sql); 
      $row = $result->fetch_assoc();
      switch ($row[trade_type])
      {
       case 5:
             $loser_price=$row[trade_sell_price]-0.015; //在卖出最高价的基础上低于5个点位
       break;
       case 6:
             $loser_price=$row[trade_sell_price]-0.005; //在卖出最高价的基础上低于5个点位
       break;
       case 7:
             $loser_price=$row[trade_sell_price]-0.020; //在卖出最高价的基础上低于5个点位
       break;
       case 8:
             $loser_price=$row[trade_sell_price]-0.030; //在卖出最高价的基础上低于5个点位
       break;
       case 9:
             $loser_price=$row[trade_sell_price]-0.040; //在卖出最高价的基础上低于5个点位
       break;
       default:
       echo "No number between 1 and 3";
       }  
      $loser_price_id=$row[id];  
      echo $loser_price_id."loser_price".$loser_price."~~~~~".$row[trade_sell_price]."\n";
      //发出回转交易买入信号，向交易库插入交易数据信息，回转交易买入也需要一个trade_type；  
	echo $loser_price."comming switch-rel-buy~~~~~~~~~".$trade_buy_price."\n"; 
     if ($loser_price>0 and $useable_money>1000){
	echo $loser_price."comming switch-rel-buy~~~~~~~111111111~~"."\n";       
      $number=11/$loser_price*$type8;
      $number=round($number); 
      $sql = "select count(*) from trade_history;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;    
      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','26','$loser_price','$trade_sell_price');";    
      echo $sql."comming -rel-buy-sql~~~~~~~~~"."\n";                                                                  
      $conn->query($sql);
      //交易指令发出后，需要将原来的回转交易记录status的值设置为2，这样就避免了重复发出指令；  
      $sql = "update trade_history set status=2 where id=$loser_price_id;";                                                                  
      $conn->query($sql);  
	  //更新hive_number表数据
         $total_number=$total_number+$number;  
          $useable_money=$useable_money-($number*$loser_price*100);  
          $sql = "update hive_number set total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date' order by id desc limit 1;";                                                                  
           $conn->query($sql);
           $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
           $conn->query($sql);  
      }   
    } //回转结束
      //金叉开始	  
     
      //5日线非分钟线金叉吸入筹码	  
      if(($first_min5_avgprice>$first_min10_avgprice) and ($second_min5_avgprice<$second_min10_avgprice) and $trade_day_k<55){
      $number=11/$trade_buy_price*$type9;
      $number=round($number); 
      $sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=27;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      if($row[0]==0  and $useable_money>1000){
      $sql = "select count(*) from trade_history;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;  	      
      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','27','$trade_buy_price','$trade_sell_price');";    
      echo $sql."comming -min5-avg-buy-sql~~~~~~~~~"."\n";                                                                  
      $conn->query($sql);
      }
      } //金叉结束
        //5日线非分钟线死叉抛出筹码	  
      if(($first_min5_avgprice<$first_min10_avgprice) and ($second_min5_avgprice>$second_min10_avgprice) and $trade_day_k>65){
      $number=11/$trade_sell_price*$type10;
      $number=round($number); 
      $sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=10;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      if($row[0]==0  and $useable_sell_number>=$number){
      $sql = "select count(*) from trade_history;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;  	      
      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','10','$trade_buy_price','$trade_sell_price');";    
      echo $sql."comming -min5-avg-buy-sql~~~~~~~~~"."\n";                                                                  
      $conn->query($sql);
      }
      } //死叉结束	
	  //金叉+当前价高于五日均线
     if($trade_day_k>$trade_day_d and $begin_point>$first_min5_avgprice and $trade_day_k<55){
      	$sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=28;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);    
        if($row[0]==0){
      $sql = "select count(*) from trade_history;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;  
      $number=11/$trade_buy_price*$type7;
      $number=round($number);
      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','28','$trade_buy_price','$trade_sell_price');";                                                                  
      $conn->query($sql);
            //更新hive_number表数据
      $total_number=$total_number+$number;  
      $useable_money=$useable_money-($number*$trade_buy_price*100);  
      $sql = "update hive_number set total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date' order by id desc limit 1;";                                                                  
      $conn->query($sql);
      $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);   
         }      
      }	
	  //死叉+低于五日均线
     if($trade_day_k<$trade_day_d and $begin_point<$first_min5_avgprice and $trade_day_k>60){
      	$sql = "select count(*) from trade_history where code='$trade_code' and stat_date='$trade_stat_date' and stat_time_hour='$trade_time_hour' and stat_time_min='$trade_time_min' and trade_type=11;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);    
        if($row[0]==0){
      $sql = "select count(*) from trade_history;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;  
      $number=11/$trade_buy_price*$type7;
      $number=round($number);
      $sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','11','$trade_buy_price','$trade_sell_price');";                                                                  
      $conn->query($sql);
            //更新hive_number表数据
      $total_number=$total_number+$number;  
      $useable_money=$useable_money-($number*$trade_buy_price*100);  
      $sql = "update hive_number set total_number='$total_number' where code='$trade_code' and stat_date='$trade_stat_date' order by id desc limit 1;";                                                                  
      $conn->query($sql);
      $sql = "update hive_number set useable_money='$useable_money' where stat_date='$trade_stat_date';";                                                                  
      $conn->query($sql);   
         }      
      }		  
}//开关结束
	   echo $switched."判断开关结束了\n";
  }//方法结束


  function nine_count () {
  global $time_hour,$time_min,$time_second,$begin_point,$table_name,$time_out_begin,$conn,$buy_one_price,$sell_one_price;
  echo "comming nine_count\n";
  machining_price();   
  $max=$begin_point;
  $min=$begin_point;
  $time_out_now=($time_hour*3600)+($time_min*60);  
  $sql="update $table_name set min15_point_max=$max,min15_point_min=$min order by id desc limit 1 ;";
  $conn->query($sql);  
  while( $time_out_now < $time_out_begin) {
  echo "time_out_now:$time_out_now~time_out_begin:$time_out_begin\n"; 
  machining_price();
  $time_out_now=($time_hour*3600)+($time_min*60);
    echo "$max--$begin_point\n";  
  if ($begin_point>=$max)
  {
      $max=$begin_point;
      echo "$max\n";
      $sql="update $table_name set min15_point_max=$max order by id desc limit 1 ;";
      echo $sql."\n";
  }
     if ($conn->query($sql)=== TRUE)
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
  //更新买一，卖一实时价格  
  $sql="update $table_name set now_price=$begin_point,buy_one_price=$buy_one_price,sell_one_price=$sell_one_price order by id desc limit 1 ;";
  $conn->query($sql);

  kdjfifteen(); #begin:kdj
  kdjthirty();
  kdjsixty(); 
  two_hour()	  
  kdjday();
  analyse();  
  cci();
  } 
  }
  ?>
