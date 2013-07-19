CREATE TABLE IF NOT EXISTS `zellesfaucet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(500) NOT NULL,
  `datec` varchar(24) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `email` text NOT NULL,
  `address` varchar(500) NOT NULL,
  `txid` varchar(500) NOT NULL,
  `amount` varchar(30) NOT NULL,
  `paid` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;