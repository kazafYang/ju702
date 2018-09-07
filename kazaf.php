<?php
include 'common/logs1.php';
include 'common/machining_price.php';
include 'common/db_config_inc.php';
include 'common/db.php';
include 'common/kdj.php';

class Runner{
  public $config = array();
  
  public function get_config(){
  $config['table_name']="point_number";
  $config['code']="159915";  
  return $config;  
  }
  
}
$aa=new Runner();
$aa->get_config();
//echo $aa->get_config()['table_name'];
//echo $bb['table_name']."\n";
$bb=new kdj();
$bb->get_kdjfifteen();
$runoob = new MachiningPrice();
$data=$runoob->get_machining_price();
echo $data['time_hour'];
$db=new db();
echo ($db->get_id($aa->get_config()['table_name']));
echo "结束\n";
?>
