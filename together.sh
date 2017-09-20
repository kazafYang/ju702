#!/bin/base
php cyb.php &
php hs.php &
php jg.php &
php s100.php &
php sz50.php &
php yh.php &
php zq.php &
php zxb.php
ps -ef|grep php|grep -v grep|awk '{print $2}'|xargs kill -9 #kill all find php
exit 0
