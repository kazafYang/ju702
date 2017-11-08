//批量同步数据
<?php  
include 'config_inc.php'; 
$info="begin:";
$code=array("point_number","point_number_sz","point_number_sz100","point_number_zxb","point_number_hs","point_number_zq","point_number_jg","point_number_yh");                                                                                                                                                                                           
$conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);  
foreach ($code as $value)                                                                                                                                                     
{
    //获取需要插入数据总数
    $sql = "select count(*) from $value;"; 
    $result=mysqli_query($conn,$sql);
    $row=mysqli_fetch_row($result);
    $data_number=$row[0];
    //循环获取表中的字段值
    for($i=1;$i<=$data_number;$i++){
    $sql = "select * from $value where id=$i;";
    $result = $conn->query($sql);     
    $row=$result->fetch_assoc();
    $min60_d=$row[min60_d];
    //开始同步数据    
    $ch = curl_init('http://www.baidu.com');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_exec($ch);
    $status=curl_getinfo($ch, CURLINFO_HTTP_CODE); // 200
    echo "insert into id:".$status."\n";    
    curl_close($ch);    
    }    
}
echo $info."end";
?>  



