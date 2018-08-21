<?php
   ignore_user_abort(); // houtai
   set_time_limit(0); // qu xiao shi jian shang xian
   $cmd = 'ps -ef|grep php|grep -v grep -c'; //查找php进程去掉grep进程
   $ret = shell_exec("$cmd");
   $ret = rtrim($ret, "\r\n");
   if($ret==0){
   exec("bash together.sh");
   sleep(2);
   $cmd = 'ps -ef|grep php|grep -v grep -c';
        $ret = shell_exec("$cmd");
        $ret = rtrim($ret, "\r\n");
        if($ret == 8) {
        echo "启动成功!";
        exit(0);
        }
      else{
      echo "启动失败，请再次尝试！".$ret;
      }
  
   }
?>
