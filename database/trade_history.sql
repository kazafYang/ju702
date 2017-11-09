CREATE TABLE IF not EXISTS `trade_history` (
  id int(8)  primary key,
  `code` int(8) DEFAULT "0",
  'status' int(2) default NULL,
  trade_type int(2) default NULL,
  'trade_buy_price' double default "0",
  'trade_sell_price' double default "0",
  `number` int(7) DEFAULT "0",
  `stat_date` date DEFAULT NULL,
  `stat_time_hour` int(2) DEFAULT NULL,
  `stat_time_min` int(2) DEFAULT NULL,
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
