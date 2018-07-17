#!/bin/base
ps -ef|grep php|grep -v grep|awk '{print $2}'|xargs kill -9 #kill all find php delete php distinct
php zxb.php &
php hs.php &
php jg.php &
php s100.php &
php sz50.php &
php yh.php &
php zq.php &
php cyb.php >>logs/test.log   
ps -ef|grep php|grep -v grep|awk '{print $2}'|xargs kill -9 #kill all find php
exit 0
