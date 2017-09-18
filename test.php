<?php
   ignore_user_abort(); // houtai
   set_time_limit(0); // qu xiao shi jian shang xian
   echo 'start.';
   while(!file_exists('close.txt')){
    $fp = fopen('test.txt','a+');
    fwrite($fp,date("Y-m-d H:i:s") . " sussess rn");
    fclose($fp);
    sleep(10);
   }
   echo 'end.';
?>
