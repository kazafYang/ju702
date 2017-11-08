CREATE TABLE IF not EXISTS `trade_history` (
  id int(8) auto_increment primary key,
  `code` int(8) DEFAULT "0",
  `number` int(7) DEFAULT "0",
  `stat_date` date DEFAULT NULL,
  `stat_time_hour` int(2) DEFAULT NULL,
  `stat_time_min` int(2) DEFAULT NULL,
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
