<?php
class db{

	function __construct() {
   //public function set_db(){
	$this->db_config = new DB_Config_Inc; 
	$this->conn = $this->db_config->get_db_config();
	//$this->db = new db();   
	$this->log = new logs(); 
}
/*构造方法	 */
   public function get_select($sql){
	$this -> log -> log_work("result_select函数执行的sql：$sql\n");
	$result = $this -> conn->query($sql);
	$row=mysqli_fetch_array($result);
	return $row;
	mysqli_free_result($result);  //释放结果集
}
	
   public function get_resultselect($sql){
	$this -> log -> log_work("get_resultselect函数执行的sql：$sql\n");
	$result = $this -> conn->query($sql);
	return $result;
	mysqli_free_result($result);  //释放结果集
}
	
   public function set_update($sql){
	$this -> log -> log_work("set_update函数执行的sql：$sql\n");
	if ($this->conn->query($sql) === TRUE) {
		$this->log -> log_work("set_update更新成功$sql");
	} 
	else {
		$this->log -> log_work("set_update-Error: " . $sql . $this->conn->error."\n");
	}   
   }

   public function set_insert($sql){
	$this -> log -> log_work("set_insert函数执行的sql：$sql\n");
	if ($this->conn->query($sql) === TRUE) {
		$this->log -> log_work("new inser into success $sql\n");    
	} else {
		$this->log -> log_work("Error: " . $sql . $this->conn->error."\n");   
	}    
   }	

    public function get_id($table_name) {   
	$sql = "select id from $table_name order by id desc limit 1;"; //where status=0 and stat_date='$stat_date'
	$this->log -> log_work("查询下一个插入数据的id，sql语句为：$sql\n");
	$result = $this->conn->query($sql);
	$row = $result->fetch_assoc();
	return $row[id]+1;
	mysqli_free_result($result);  //释放结果集
}

}
?>
