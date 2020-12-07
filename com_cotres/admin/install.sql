DROP TABLE IF EXISTS `#__cotres_config`;
DROP TABLE IF EXISTS `#__cotres_cottages`;
DROP TABLE IF EXISTS `#__cotres_orders`;
DROP TABLE IF EXISTS `#__cotres_order_details`;
DROP TABLE IF EXISTS `#__cotres_prices`;
DROP TABLE IF EXISTS `#__cotres_seasons`;

CREATE TABLE IF NOT EXISTS `#__cotres_config` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(50) NOT NULL,
  `payment_info` text,
  `paypal` varchar(50) default NULL,
  `reserv_perc` int(11) default NULL,
  `add_fee_nights` int(11) default NULL,
  `add_fee_perc` int(11) default NULL,
  `reserv_min_nights` int(11) default NULL,
  `online` enum('0','1') default '0',
  `testing` enum('0','1') default '1',
  `cardpay` varchar(50) default NULL,
  `reserved_hours` int(11) default NULL,
  `policy_article_id` int(11) default NULL,
  `cardpay_mid` varchar(50) NOT NULL,
  `cardpay_key` varchar(50) NOT NULL,
  `cardpay_cs` varchar(50) NOT NULL,
  `cardpay_rem` varchar(50) NOT NULL,
  `cardpay_rsms` varchar(50) NOT NULL,
  `cardpay_name` varchar(50) NOT NULL,
  `cardpay_ipc` varchar(50) NOT NULL,
  `conversion` float NOT NULL,
  `pricelist_module_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;


CREATE TABLE IF NOT EXISTS `#__cotres_cottages` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `desc` text,
  `capacity` int(11) default NULL,
  `published` tinyint(3) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;


CREATE TABLE IF NOT EXISTS `#__cotres_orders` (
  `id` int(11) NOT NULL auto_increment,
  `price_total` float NOT NULL,
  `created` datetime NOT NULL,
  `status` tinyint(4) NOT NULL default '0',
  `payment_type` varchar(50) default NULL,
  `user_type` enum('0','1') default NULL,
  `company_name` varchar(50) default NULL,
  `ico` varchar(50) default NULL,
  `dic` varchar(50) default NULL,
  `contact_person` varchar(50) default NULL,
  `fname` varchar(50) default NULL,
  `lname` varchar(50) default NULL,
  `street` varchar(50) default NULL,
  `city` varchar(50) default NULL,
  `zip` varchar(50) default NULL,
  `country` varchar(50) default NULL,
  `phone` varchar(50) default NULL,
  `fax` varchar(50) default NULL,
  `email` varchar(50) default NULL,
  `date_from` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_to` datetime NOT NULL default '0000-00-00 00:00:00',
  `reservation_fee` float default NULL,
  `payment_date` datetime default NULL,
  `price_array` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;


CREATE TABLE IF NOT EXISTS `#__cotres_order_details` (
  `id` int(11) NOT NULL auto_increment,
  `id_order` int(11) NOT NULL,
  `id_cottage` int(11) NOT NULL,
  `cottage_name` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;


CREATE TABLE `#__cotres_prices` (
  `id` int(11) NOT NULL auto_increment,
  `id_cottage` int(11) NOT NULL,
  `id_season` int(11) NOT NULL,
  `price` float NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;


CREATE TABLE `#__cotres_seasons` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `date_from` varchar(7) default NULL,
  `date_to` varchar(7) default NULL,
  `published` tinyint(3) NOT NULL default '1',
  `reserv_min_nights` int(11) default NULL,
  `add_fee_nights` int(11) default NULL,
  `add_fee_perc` int(11) default NULL,
  `year` int(11) default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
