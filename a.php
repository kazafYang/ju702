<?php
$conn = new mysqli("172.30.254.220", "root", "y13551960531", "ju70");
// Check connection
if ($conn->connect_error) {
    die("defult: " . $conn->connect_error);
} 
 
$sql = "SELECT id,begin_point,stat_time_hour FROM point_number";
$result = $conn->query($sql);
 
if ($result->num_rows > 0) {
    // 输出数据
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["begin_point"]. " " . $row["stat_time_hour"]. "<br>";
    }
} else {
    echo "0 defult";
}
$conn->close();
?>
