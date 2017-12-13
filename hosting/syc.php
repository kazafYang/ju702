<?php
include 'config_inc.php';
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
$code=array("point_number","point_number_sz","point_number_sz100","point_number_zxb","point_number_hs","point_number_zq","point_number_jg","point_number_yh","trade_history","trade_bate","hive_number");                                                           
//$code=array("trade_bate","hive_number");                                                           
     
  date_default_timezone_set('PRC');
       $hour= date("H");
      // echo  $hour;
        if( $hour>=9 and $hour<16){
foreach ($code as $value){
   //获取当前数据库已存在条数
    $sql = "select id from $value order by id desc limit 1;";    
      $result=mysqli_query($conn,$sql);
      $row=mysqli_fetch_row($result);
      $trade_id=$row[0]+1;  
     echo "当前数据条数".$trade_id."\n";
   //获取目标数据库已存在条数
     $url='http://ju70-ju70.193b.starter-ca-central-1.openshiftapps.com/page/update.php?type=3&sql=select~id~from~'.$value.'~order~by~id~desc~limit~1'; 
     $html = file_get_contents($url); 
   //  $mubiao_number= substr($str,0,strlen($str)-0); 
     $mubiao_number=str_replace("'","", $html);
     echo "目标数据条数".$mubiao_number."\n";
   //对比数据差异，开始执行同步
     if($mubiao_number>$trade_id){
     echo "开始同步数据\n";   
     for($i=$trade_id;$i<=$mubiao_number;$i++){
        $a=rand(1,100000);
         $str_ca="";
        $url_ca='http://ju70-ju70.193b.starter-ca-central-1.openshiftapps.com/page/update.php?type=3&sql=select~*~from~'.$value.'~where~id='.$i.'&a='.$a; 
        $html_ca = file_get_contents($url_ca); 
        if($html_ca==""){
        echo "kong!!!!!!!!!!!!!!!!!!!!!!";
        $html_ca = file_get_contents( $url_ca); 
          }
        $sql = "insert into $value values ($html_ca);"; 
        echo  $sql."@@@@@@@@@@@@@@";
        $result=mysqli_query($conn,$sql);
       mysqli_free_result($result);  //释放结果集 
     }      
     } 
    echo "完成：".$value."\n";   
   }  
$conn->close();
}
?>
