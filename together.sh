#!/bin/base
ps -ef|grep php|grep -v grep|awk '{print $2}'|xargs kill -9 #kill all find php delete php distinct
d1=`date +%Y%m%d`
php zxb.php & >>logs/test_zxb$d1.log
php hs.php & >>logs/test_hs$d1.log
php jg.php & >>logs/test_jg$d1.log
php s100.php & >>logs/test_sz100$d1.log
php sz50.php & >>logs/test_sz50$d1.log
php yh.php & >>logs/test_yh$d1.log
php zq.php & >>logs/test_zq$d1.log
php cyb.php >>logs/test_cyb$d1.log   
ps -ef|grep php|grep -v grep|awk '{print $2}'|xargs kill -9 #kill all find php
exit 0
