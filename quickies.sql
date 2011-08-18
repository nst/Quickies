-- 
-- Table structure for table `q_category`
-- 

CREATE TABLE `q_category` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_index` (`name`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `q_note`
-- 

CREATE TABLE `q_note` (
  `id` int(11) NOT NULL auto_increment,
  `category_id` int(11) default NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `title` varchar(255) default NULL,
  `text` text,
  PRIMARY KEY  (`id`),
  KEY `category_id` (`category_id`),
  FULLTEXT KEY `text` (`text`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ;
