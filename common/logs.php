<?php
$Boy = new log();
    class log{
        var $time;
         
        function log_work($text){
			date_default_timezone_set('prc');
			$time=date('Y-m-d H:i:s');
			echo $time." - - ".$text;
        }
    }
?>
