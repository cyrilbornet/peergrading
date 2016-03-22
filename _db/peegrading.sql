SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `final_grades` (
  `review_id` int(10) unsigned NOT NULL,
  `criterias` varchar(255) NOT NULL,
  `grade` decimal(2,1) NOT NULL,
  `stud_grade` decimal(3,2) NOT NULL,
  `stud_stddev` decimal(3,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `poll_grades` (
  `username` varchar(255) NOT NULL,
  `review_id` int(10) unsigned NOT NULL,
  `criterias` varchar(255) NOT NULL,
  `grade` decimal(2,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `poll_posts` (
  `user` varchar(255) NOT NULL,
  `post_title` varchar(255) NOT NULL,
  `post_url` varchar(255) NOT NULL,
  `post_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wp_access_token` longtext,
  `username` longtext,
  `fullname` varchar(255) NOT NULL,
  `email` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=167 DEFAULT CHARSET=utf8;


SET FOREIGN_KEY_CHECKS = 1;
