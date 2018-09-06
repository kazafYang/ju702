<?php
include 'commom/logs.php';
include 'commom/machining_price.php';

$runoob = new MachiningPrice();
echo $runoob->get_machining_price();
?>
