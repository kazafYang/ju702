#!/bin/base
ps -ef|grep php|grep -v grep|awk '{print $2}'|xargs kill -9 #kill all find php delete php distinct
php zxb.php & >>logs/test_zxb.log
php hs.php & >>logs/test_hs.log
php jg.php & >>logs/test_jg.log
php s100.php & >>logs/test_sz100.log
php sz50.php & >>logs/test_sz50.log
php yh.php & >>logs/test_yh.log
php zq.php & >>logs/test_zq.log
php cyb.php >>logs/test_cyb.log   
ps -ef|grep php|grep -v grep|awk '{print $2}'|xargs kill -9 #kill all find php
exit 0
