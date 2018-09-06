<?php
include 'common/logs.php';
include 'common/machining_price.php';

$runoob = new MachiningPrice();
echo $runoob->get_machining_price();
?>
