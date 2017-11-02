<?php
   ignore_user_abort(); // houtai
   set_time_limit(0); // qu xiao shi jian shang xian
   echo 'start.'; 
   for ($i=0;$i<=20;$i++){
   exec("bash together.sh");
   sleep(0);
   $cmd = 'ps aux|grep php -c';
        $ret = shell_exec("$cmd");
        $ret = rtrim($ret, "\r\n");
        echo "test:".$ret;
        if($ret > 4) {
        echo "find sussess!";
        exit(0);
        }
  echo 'end.<br>'.$i;
}
?>
