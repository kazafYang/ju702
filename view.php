<?php                                                                                                                                                                         
include 'config_inc.php';                                                                                                                                                     
$showinfo="info:<br>";
$sellinfo="sell:<br>";
$buyinfo="buy:<br>";
$code=array("point_number","point_number_sz","point_number_sz100","point_number_zxb","point_number_hs","point_number_zq","point_number_jg","point_number_yh");                
                                                                                                                                                                              
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);                                                                                    
foreach ($code as $value)                                                                                                                                                     
{                                                                                                                                                                                                                                                                                                                         
    $sql = "SELECT code,min15_k,min15_d,min15_j,min30_k,min30_d,min30_j,min60_k,min60_d,min60_j,kdjday_k,kdjday_d,kdjday_j,cci FROM $value order by id desc limit 1";                                                                  
    $result = $conn->query($sql);                                                                                                                                             
    $row = $result->fetch_assoc();
    $row[min15_k]=round($row[min15_k],2);$row[min15_d]=round($row[min15_d],2);$row[min15_j]=round($row[min15_j],2);
    $row[min30_k]=round($row[min30_k],2);$row[min30_d]=round($row[min30_d],2);$row[min30_j]=round($row[min30_j],2);
    $row[min60_k]=round($row[min60_k],2);$row[min60_d]=round($row[min60_d],2);$row[min60_j]=round($row[min60_j],2);
    $row[kdjday_k]=round($row[kdjday_k],2);$row[kdjday_d]=round($row[kdjday_d],2);$row[kdjday_j]=round($row[kdjday_j],2);
    $row[cci]=round($row[cci],2);
  //15min  
  if(($row[min15_k] >= 80 and $row[min15_k] < 85 and ($row[min60_k] >= 50 or $row[min60_d] >= 50)) or ($row[min15_d]>=75 and $row[min15_d]<80 and ($row[min60_k] >= 50 or $row[min60_d] >= 50)))
    { 
   $sellinfo=$sellinfo.$row[code]."--15min:K/D值已经达到:". $row[min15_k]."/".$row[min15_d]."小幅抛筹》》》";
    } 
elseif (($row[min15_k] >= 85 and $row[min15_k] < 90 and ($row[min60_k] >= 50 or $row[min60_d] >= 50)) or ($row[min15_d]>=80 and $row[min15_d]<85 and ($row[min60_k] >= 50 or $row[min60_d] >= 50)))  { 
   $sellinfo=$sellinfo.$row[code]."--15min:K/D值已经达到:". $row[min15_k]."/".$row[min15_d]."中幅抛筹》》》"; 
    }
elseif (($row[min15_k] >= 90 and ($row[min60_k] >= 50 or $row[min60_d] >= 50)) or ($row[min15_d]>=85 and ($row[min60_k] >= 50 or $row[min60_d] >= 50)))  { 
   $sellinfo=$sellinfo.$row[code]."--15min:K/D值已经达到:". $row[min15_k]."/".$row[min15_d]."清仓抛筹》》》"; 
     }  
//30min  
   if (($row[min30_k] >=75 and $row[min30_k] <80) or ($row[min30_d] >=75 and $row[min30_d] <80)){
    $sellinfo=$sellinfo.$row[code]."--30min:K/D值已经达到:". $row[min30_k]."/".$row[min30_d]."30超买~~~~"; 
    } 
    elseif(($row[min30_k] >=80 and $row[min30_k] <85) or ($row[min30_d] >=80 and $row[min30_d] <85)) {
     $sellinfo=$sellinfo.$row[code]."--30min:K/D值已经达到:". $row[min30_k]."/".$row[min30_d]."30超买~~~~"; 
    }
    elseif($row[min30_k] >=85  or $row[min30_d] >=85 ){
     $sellinfo=$sellinfo.$row[code]."--30min:K/D值已经达到:". $row[min30_k]."/".$row[min30_d]."30超买~~~~";  
    }  
           
   if (($row[min60_k] >=75 and $row[min60_k]<80) or ($row[min60_d] >=75 and $row[min60_d] <80)) {
    $sellinfo=$sellinfo.$row[code]."--60min:K/D值已经达到:". $row[min60_k]."/".$row[min60_d]."60超买---"; 
    }
    elseif(($row[min60_k] >=80 and $row[min60_k] <85) or ($row[min60_d] >=80 and $row[min60_k] <85)){
    $sellinfo=$sellinfo.$row[code]."--60min:K/D值已经达到:". $row[min60_k]."/".$row[min60_d]."60超买---"; 
    }
    elseif( $row[min60_k] >=85 or $row[min60_d] >=85 ){
    $sellinfo=$sellinfo.$row[code]."--60min:K/D值已经达到:". $row[min60_k]."/".$row[min60_d]."60超买---";   
    } 
  
    if (($row[kdjday_k] >=75 and $row[kdjday_k]<80) or ($row[kdjday_d] >=75 and $row[kdjday_d] <80)) {
    $sellinfo=$sellinfo.$row[code]."--日线:K/D值已经达到:". $row[kdjday_k]."/".$row[kdjday_d]."日线超买关注风险---"; 
    }  
    elseif (($row[kdjday_k] >=80 and $row[kdjday_k] <85) or ($row[kdjday_d] >=80 and $row[kdjday_d] <85)){
    $sellinfo=$sellinfo.$row[code]."--日线:K/D值已经达到:". $row[kdjday_k]."/".$row[kdjday_d]."日线超买撤离#----"; 
    }
    elseif ($row[kdjday_k] >=85 or $row[kdjday_d] >=85){
    $sellinfo=$sellinfo.$row[code]."--日线:K/D值已经达到:". $row[kdjday_k]."/".$row[kdjday_d]."日线超买清理出局#----"; 
    }
  
    if ($row[min15_k] <20 or $row[min15_d] <20 ){
    $buyinfo=$buyinfo.$row[code]."--15min:K/D值已经达到:". $row[min15_k]."/".$row[min15_d]."超卖区域，关注》》》";
    }  
    if ($row[min30_k] <20 or $row[min30_d] <20 ){
    $buyinfo=$buyinfo.$row[code]."--30min:K/D值已经达到:". $row[min30_k]."/".$row[min30_d]."超卖区域，试探~~~~~";
    }   
    if ($row[min60_k] <20 or $row[min60_d] <20 ){
    $buyinfo=$buyinfo.$row[code]."--60min:K/D值已经达到:". $row[min60_k]."/".$row[min60_d]."超卖区域，建仓》》》";
    }
    if ($row[kdjday_k] <20 or $row[kdjday_d] <20 ){
    $buyinfo=$buyinfo.$row[code]."--日线K/D值已经达到:". $row[kdjday_k]."/".$row[kdjday_d]."超卖区域，加杠杆----";
    }
    $sellinfo=$sellinfo."<br>";
    $buyinfo=$buyinfo."<br>";
    $showinfo=$showinfo.",".$row[code].",15mk:".$row[min15_k].",15md:".$row[min15_d].",15mj：".$row[min15_j].",30mk：".$row[min30_k].",30md：".$row[min30_d].",30mj：".$row[min30_j].",60mK：".$row[min60_k].",60md：".$row[min60_d].",60mj：".$row[min60_j].",dayk：".$row[kdjday_k].",dayd：".$row[kdjday_d].",dayj：".$row[kdjday_j].",cci：".$row[cci]."<br>";                         
}                                                                                                                                                                             
echo $showinfo;  
echo "<br>".$sellinfo; 
echo "<br>".$buyinfo;            
?> 
