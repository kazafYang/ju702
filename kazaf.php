include 'logs.php';
include 'machining_price.php';

$runoob = new MachiningPrice();
echo $runoob->get_machining_price();
