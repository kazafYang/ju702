CREATE TABLE IF not EXISTS `hive_number` (
  id int(8)  primary key,
  `code` int(8) DEFAULT "0",
  total_money  double DEFAULT "0",
  useable_money double DEFAULT "0",
  double DEFAULT "0",
  useable_sell_number int(8) DEFAULT "0",
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
