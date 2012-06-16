CREATE TABLE IF NOT EXISTS `<<table_prefix>>categories` (
  `categoryid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`categoryid`)
);

INSERT INTO `<<table_prefix>>categories` (`categoryid`, `title`, `description`) VALUES
(1, 'Uncategorized', 'A category for posts that haven\'t been categorized.');

CREATE TABLE IF NOT EXISTS `<<table_prefix>>comments` (
  `commentid` int(11) NOT NULL AUTO_INCREMENT,
  `postid` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `userid` int(11) NOT NULL,
  `moderated` tinyint(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`commentid`)
);

CREATE TABLE IF NOT EXISTS `<<table_prefix>>posts` (
  `postid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `categoryid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `comments_enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`postid`)
);

CREATE TABLE IF NOT EXISTS `<<table_prefix>>settings` (
  `settingid` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `friendly_name` varchar(255) NOT NULL,
  `is_bool` tinyint(1) NOT NULL,
  PRIMARY KEY (`settingid`),
  KEY `key` (`key`)
);

INSERT INTO `<<table_prefix>>settings` (`settingid`, `key`, `value`, `friendly_name`, `is_bool`) VALUES
(1, 'blog_title', '<<blog_title>>', 'Blog Title', 0),
(2, 'blog_url', '<<blog_url>>', 'Blog URL', 0),
(3, 'template', 'technologic', 'Template', 0);

CREATE TABLE IF NOT EXISTS `<<table_prefix>>users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(36) NOT NULL,
  `password` char(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `real_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `bio` text NOT NULL,
  `posts_permission` tinyint(1) NOT NULL,
  `comments_permission` tinyint(1) NOT NULL,
  `categories_permission` tinyint(1) NOT NULL,
  `users_permission` tinyint(1) NOT NULL,
  `settings_permission` tinyint(1) NOT NULL,
  `edit_not_own_permission` tinyint(1) NOT NULL,
  `admin_permission` tinyint(1) NOT NULL,
  PRIMARY KEY (`userid`)
);

INSERT INTO `<<table_prefix>>users` (`userid`, `username`, `password`, `email`, `real_name`, `location`, `bio`, `posts_permission`, `comments_permission`, `categories_permission`, `users_permission`, `settings_permission`, `edit_not_own_permission`, `admin_permission`) VALUES
(1, '<<root_username>>', SHA1('<<root_password>>'), '<<root_email>>', '<<root_real_name>>', '', '', 1, 1, 1, 1, 1, 1, 1);
