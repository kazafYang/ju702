<?php
include 'config_inc.php';
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
$code=array("point_number","point_number_sz","point_number_sz100","point_number_zxb","point_number_hs","point_number_zq","point_number_jg","point_number_yh","trade_history","trade_bate","hive_number");                                                           
//$code=array("trade_bate","hive_number");                                                           
     
  date_default_timezone_set('PRC');
       $hour= date("H");
      // echo  $hour;
        if( $hour>9 and $hour<16){
foreach ($code as $value){
   //获取当前数据库已存在条数
    $sql = "select count(*) from $value;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;  
     echo "当前数据条数".$trade_id."\n";
   //获取目标数据库已存在条数
     $url='http://kazaf.byethost7.com/update.php?type=3&sql=select~count(*)~from~'.$value; 
     $html = file_get_contents($url); 
     $str = $html; 
     $mubiao_number= substr($str,0,strlen($str)-2); 
     echo "目标数据条数".$mubiao_number."\n";
   //对比数据差异，开始执行同步
     if($mubiao_number>$trade_id){
     echo "开始同步数据\n";   
     for($i=$trade_id;$i<=$mubiao_number;$i++){
        $url='http://kazaf.byethost7.com/update.php?type=3&sql=select~*~from~'.$value.'~where~id='.$i; 
        $html = file_get_contents($url); 
        $str = $html; 
        $newstr = substr($str,0,strlen($str)-2); 
        $sql = "insert into $value values ($newstr);"; 
        $result=mysqli_query($conn,$sql);
       mysqli_free_result($result);  //释放结果集 
       $url_date='http://kazaf.byethost7.com/update.php?type=3&sql=select~stat_date~from~'.$value.'~where~id='.$i; 
       $html_date = file_get_contents($url_date); 
       $str_date =$html_date; 
       $newstr_date = substr($str_date,0,strlen($str_date)-2); 
       $sql = "update $value set stat_date='$newstr_date' where id=".$i;  
       echo $sql;
       $result=mysqli_query($conn,$sql);
       mysqli_free_result($result);  //释放结果集       
     }      
     } 
    echo "完成：".$value."\n";   
   }  
$conn->close();
}
?>
