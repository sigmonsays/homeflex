# create table generated on Thu, 12 Feb 2004 16:01:07 -0500



# table announcements
CREATE TABLE `announcements` (
  `id` bigint(20) NOT NULL auto_increment,
  `subject` varchar(128) NOT NULL default '',
  `message` text NOT NULL,
  KEY `id` (`id`)
) TYPE=MyISAM;


# table away_messages
CREATE TABLE `away_messages` (
  `id` bigint(20) NOT NULL auto_increment,
  `ip` varchar(16) NOT NULL default '',
  `person` varchar(255) NOT NULL default '',
  `message` text NOT NULL,
  KEY `id` (`id`)
) TYPE=MyISAM;


# table blog-comments
CREATE TABLE `blog-comments` (
  `id` bigint(20) NOT NULL auto_increment,
  `blogid` bigint(20) NOT NULL default '0',
  `when` timestamp(14) NOT NULL,
  `from` varchar(128) NOT NULL default '',
  `subject` varchar(128) NOT NULL default '',
  `text` text NOT NULL,
  KEY `id` (`id`)
) TYPE=MyISAM COMMENT='my blog comments';


# table blog
CREATE TABLE `blog` (
  `id` bigint(20) NOT NULL auto_increment,
  `when` timestamp(14) NOT NULL,
  `subject` varchar(128) NOT NULL default '',
  `text` text NOT NULL,
  KEY `id` (`id`)
) TYPE=MyISAM COMMENT='My SImple Blog';


# table blurbBar
CREATE TABLE `blurbBar` (
  `id` bigint(20) NOT NULL auto_increment,
  `when` timestamp(14) NOT NULL,
  `subject` varchar(128) NOT NULL default '',
  `message` text NOT NULL,
  `url` varchar(255) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table bugs
CREATE TABLE `bugs` (
  `id` bigint(20) NOT NULL auto_increment,
  `when` timestamp(14) NOT NULL,
  `ip` varchar(16) NOT NULL default '',
  `priority` int(11) NOT NULL default '0',
  `subject` varchar(128) NOT NULL default '',
  `description` text NOT NULL,
  `contact` varchar(128) NOT NULL default '',
  `userid` bigint(20) NOT NULL default '0',
  `status` enum('OPEN','CLOSED','READ') NOT NULL default 'OPEN',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table changelog
CREATE TABLE `changelog` (
  `id` bigint(20) NOT NULL auto_increment,
  `when` timestamp(14) NOT NULL,
  `subject` varchar(128) NOT NULL default '',
  `text` text NOT NULL,
  KEY `id` (`id`)
) TYPE=MyISAM;


# table contacts
CREATE TABLE `contacts` (
  `id` bigint(20) NOT NULL auto_increment,
  `full_name` varchar(128) NOT NULL default '',
  `home_phone` varchar(128) NOT NULL default '',
  `mobile_phone` varchar(128) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `notes` text NOT NULL,
  `email` varchar(128) NOT NULL default '',
  `aim` varchar(64) NOT NULL default '',
  `chick` smallint(6) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table cotd
CREATE TABLE `cotd` (
  `day` bigint(20) NOT NULL default '0',
  `command` text NOT NULL,
  `description` text NOT NULL,
  UNIQUE KEY `day` (`day`)
) TYPE=MyISAM;


# table countdown
CREATE TABLE `countdown` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` timestamp(14) NOT NULL,
  `subject` varchar(128) NOT NULL default '',
  `message` text NOT NULL,
  `link` varchar(255) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table faq
CREATE TABLE `faq` (
  `id` bigint(20) NOT NULL auto_increment,
  `category` int(11) NOT NULL default '0',
  `question` text NOT NULL,
  `answer` text NOT NULL,
  KEY `id` (`id`)
) TYPE=MyISAM;


# table faqCategories
CREATE TABLE `faqCategories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table fileCategories
CREATE TABLE `fileCategories` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` char(255) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table files
CREATE TABLE `files` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `size` bigint(20) NOT NULL default '0',
  `filesid` bigint(20) NOT NULL default '0',
  `file` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `downloads` bigint(20) NOT NULL default '0',
  `mimetype` int(11) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table foo
CREATE TABLE `foo` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  `file` varchar(128) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table fortunes
CREATE TABLE `fortunes` (
  `id` bigint(20) NOT NULL auto_increment,
  `fortune` text NOT NULL,
  KEY `id` (`id`)
) TYPE=MyISAM;


# table groups
CREATE TABLE `groups` (
  `id` bigint(20) NOT NULL auto_increment,
  `group` char(128) NOT NULL default '',
  `members` char(255) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table images
CREATE TABLE `images` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  `image` varchar(128) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table levels
CREATE TABLE `levels` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table linkCategories
CREATE TABLE `linkCategories` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table links
CREATE TABLE `links` (
  `id` bigint(20) NOT NULL auto_increment,
  `category` bigint(20) NOT NULL default '0',
  `active` tinyint(4) NOT NULL default '1',
  `when` timestamp(14) NOT NULL,
  `url` char(255) NOT NULL default '',
  `title` char(255) NOT NULL default '',
  `priority` int(11) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table mb_categories
CREATE TABLE `mb_categories` (
  `id` bigint(20) NOT NULL auto_increment,
  `created` timestamp(14) NOT NULL,
  `name` varchar(128) NOT NULL default '',
  `description` text NOT NULL,
  `parent` bigint(20) NOT NULL default '0',
  `post_count` bigint(20) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table mb_posts
CREATE TABLE `mb_posts` (
  `id` bigint(20) NOT NULL auto_increment,
  `created` timestamp(14) NOT NULL,
  `category` bigint(20) NOT NULL default '0',
  `ip` varchar(16) NOT NULL default '',
  `subject` varchar(128) NOT NULL default '',
  `message` text NOT NULL,
  `user` int(11) NOT NULL default '0',
  `parent` bigint(20) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table mimetypes
CREATE TABLE `mimetypes` (
  `id` int(11) NOT NULL auto_increment,
  `type` char(128) NOT NULL default '',
  `extension` char(8) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table movies
CREATE TABLE `movies` (
  `id` bigint(20) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `year` int(11) default NULL,
  `rated` enum('NR','G','PG','PG-13','R','Adult') NOT NULL default 'NR',
  `director` varchar(255) NOT NULL default '',
  `starring1` varchar(128) default NULL,
  `starring2` varchar(128) default NULL,
  `starring3` varchar(128) default NULL,
  `datecreated` timestamp(14) NOT NULL,
  `summary` text NOT NULL,
  `genre` varchar(128) default NULL,
  `size` bigint(20) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM;


# table news
CREATE TABLE `news` (
  `id` bigint(20) NOT NULL auto_increment,
  `when` timestamp(14) NOT NULL,
  `subject` varchar(128) NOT NULL default '',
  `post` text NOT NULL,
  `user` varchar(32) NOT NULL default '',
  `picture` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `id_2` (`id`)
) TYPE=MyISAM;


# table pictureCategories
CREATE TABLE `pictureCategories` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  `picture` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table pictures
CREATE TABLE `pictures` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  `category` bigint(20) NOT NULL default '0',
  `when` timestamp(14) NOT NULL,
  `picture` varchar(255) NOT NULL default '',
  `thumbnail` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `width` int(11) NOT NULL default '0',
  `height` int(11) NOT NULL default '0',
  `vwidth` int(11) NOT NULL default '0',
  `vheight` int(11) NOT NULL default '0',
  `viewCount` bigint(20) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table preferences
CREATE TABLE `preferences` (
  `id` bigint(20) NOT NULL auto_increment,
  `userid` bigint(20) NOT NULL default '0',
  `variable` char(128) NOT NULL default '',
  `value` char(128) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table projectCategories
CREATE TABLE `projectCategories` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` char(128) NOT NULL default '',
  `weight` int(11) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM COMMENT='Project categories';


# table projects
CREATE TABLE `projects` (
  `id` bigint(20) NOT NULL auto_increment,
  `when` timestamp(14) NOT NULL,
  `categoryID` bigint(20) NOT NULL default '0',
  `name` varchar(128) NOT NULL default '',
  `description` text NOT NULL,
  `filesid` bigint(20) NOT NULL default '0',
  `views` bigint(20) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM;

# table sections
CREATE TABLE `sections` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` char(255) NOT NULL default '',
  `active` tinyint(4) NOT NULL default '0',
  `include` char(255) NOT NULL default '',
  `url` char(255) NOT NULL default '',
  `description` char(128) NOT NULL default '',
  `regular` tinyint(4) NOT NULL default '0',
  `admin` tinyint(4) NOT NULL default '0',
  `logo` char(255) NOT NULL default '',
  `accessed` timestamp(14) NOT NULL,
  `inToolbar` tinyint(4) NOT NULL default '1',
  KEY `id` (`id`)
) TYPE=MyISAM;

# table services
CREATE TABLE `services` (
  `id` bigint(20) NOT NULL auto_increment,
  `service` char(64) NOT NULL default '',
  `port` int(11) NOT NULL default '0',
  `protocol` char(3) NOT NULL default '',
  `description` char(128) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table skins
CREATE TABLE `skins` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` char(128) NOT NULL default '',
  `directory` char(255) NOT NULL default '',
  `active` tinyint(4) NOT NULL default '0',
  `good` int(11) NOT NULL default '0',
  `bad` int(11) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table think_geek
CREATE TABLE `think_geek` (
  `id` bigint(11) NOT NULL auto_increment,
  `quote` text NOT NULL,
  PRIMARY KEY  (`quote`(128)),
  KEY `id` (`id`)
) TYPE=MyISAM;


# table todo
CREATE TABLE `todo` (
  `id` bigint(20) NOT NULL auto_increment,
  `when` timestamp(14) NOT NULL,
  `subject` varchar(25) NOT NULL default '',
  `text` text NOT NULL,
  `complete` tinyint(4) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM;


# table users
CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `created` timestamp(14) NOT NULL,
  `user` varchar(64) NOT NULL default '',
  `password` varchar(64) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `website` varchar(255) NOT NULL default '',
  `aim` varchar(32) NOT NULL default '',
  `level` int(11) NOT NULL default '0',
  `active` tinyint(4) NOT NULL default '0',
  `hash` varchar(255) NOT NULL default '',
  UNIQUE KEY `user` (`user`),
  KEY `id` (`id`)
) TYPE=MyISAM;


# table whois_history
CREATE TABLE `whois_history` (
  `id` bigint(20) NOT NULL auto_increment,
  `when` timestamp(14) NOT NULL,
  `ip` varchar(16) NOT NULL default '',
  `query` varchar(255) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM;


# end of dump
