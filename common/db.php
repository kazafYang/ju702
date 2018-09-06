<?php
class db{
  var $conn=new mysqli($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
  var $log=new logs();
  
  function result_select($sql){
	$log -> log_work("result_select函数执行的sql：$sql\n");
	$result = $conn->query($sql);
	$row=mysqli_fetch_array($result);
	return $row;
	mysqli_free_result($result);  //释放结果集
}

function table_id ($table_name) {
	$sql = "select id from $table_name order by id desc limit 1;"; //where status=0 and stat_date='$stat_date'
	$log -> log_work("查询下一个插入数据的id，sql语句为：$sql\n");
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	return $row[id]+1;
	mysqli_free_result($result);  //释放结果集		  
}

}
?>
