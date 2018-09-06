<?php
class DB_Config_Inc{
    
    public function get_db_config(){
    $mysql_server_name='mysql.ju70.svc'; //改成自己的mysql数据库服务器,n2:10.128.10.14
    $mysql_username='root'; //改成自己的mysql数据库用户名
    $mysql_password='y13551960531'; //改成自己的mysql数据库密码
    $mysql_database='ju70'; //改成自己的mysql数据库名
    $conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);  
    return $conn;
    }
}
?>
