<?php
class DB_Config_Inc{
    
    public function get_db_config(){
        $data['mysql_server_name'] = 'mysql.ju70.svc';
        $data['mysql_username'] = 'root';
        $data['mysql_password'] = 'y13551960531';
        $data['mysql_database'] = 'ju70';
        $conn = new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);  
        return $conn;
    }
}
?>
