<?php
include 'common/logs1.php';
include 'common/machining_price.php';
include 'common/db.php';

$runoob = new MachiningPrice();
$data=$runoob->get_machining_price();
echo $data['time_hour'];
$db=new db();
echo ($db->get_id("point_number"));
?>
