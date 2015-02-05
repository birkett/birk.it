CREATE SCHEMA IF NOT EXISTS `birkit` DEFAULT CHARACTER SET utf8;

DROP TABLE IF EXISTS `urls`;
CREATE TABLE `urls` (
  `original_url` varchar(2048) NOT NULL,
  `short_url` varchar(50) NOT NULL,
  PRIMARY KEY (`short_url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `urls` WRITE;
INSERT INTO `urls` VALUES ('www.a-birkett.co.uk/blog/5','ewgzvbnk'),('www.a-birkett.co.uk/blog/7','i0fz3qz9'),('www.a-birkett.co.uk/blog/8','lg72o6pb'),('www.a-birkett.co.uk/blog/9','bt6h0y53');
UNLOCK TABLES;
