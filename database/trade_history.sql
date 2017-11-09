CREATE TABLE IF not EXISTS `trade_history` (
  `id` int(8) not NULL ,
  `code` int(7) DEFAULT "0",
  'status' int(2) not null default 0 ,
  `number` int(7) not null default 0 ,
   'trade_type' int(2) not null default 0 ,
  'trade_buy_price' double not null default 0,
  'trade_sell_price' double not null default 0,
  `stat_date` date DEFAULT NULL,
  `stat_time_hour` int(2) DEFAULT NULL,
  `stat_time_min` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


