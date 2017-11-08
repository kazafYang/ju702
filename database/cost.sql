CREATE TABLE IF not EXISTS `cost` (
  id int(8) auto_increment primary key,
  `code` int(8) DEFAULT "0",
  `cost_price` double DEFAULT "0",
  `total_number` int(7) DEFAULT "0",
  `usa_number` int(7) DEFAULT NULL,
  `freeze_number` int(7) DEFAULT NULL,
  `stat_date` date DEFAULT NULL,
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
