<?php
include 'common/logs1.php';
include 'common/machining_price.php';

$runoob = new MachiningPrice();
echo $runoob->get_machining_price(159915);
?>
