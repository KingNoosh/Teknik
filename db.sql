-- phpMyAdmin SQL Dump
-- version 4.0.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 20, 2014 at 02:32 AM
-- Server version: 5.6.11-log
-- PHP Version: 5.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `teknik`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `date_posted` datetime NOT NULL,
  `title` varchar(140) NOT NULL,
  `tags` varchar(500) NOT NULL,
  `post` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=58 ;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service` varchar(50) NOT NULL,
  `reply_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(140) NOT NULL,
  `post` text NOT NULL,
  `date_posted` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE IF NOT EXISTS `history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(140) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `irc`
--

CREATE TABLE IF NOT EXISTS `irc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cur_nicks` int(11) DEFAULT NULL,
  `max_nicks` int(11) DEFAULT NULL,
  `topic` text,
  `check_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12671 ;

-- --------------------------------------------------------

--
-- Table structure for table `paste`
--

CREATE TABLE IF NOT EXISTS `paste` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) DEFAULT NULL,
  `posted` datetime DEFAULT NULL,
  `code` longtext CHARACTER SET utf8,
  `parent_pid` int(11) DEFAULT '0',
  `format` varchar(16) DEFAULT NULL,
  `codefmt` mediumtext CHARACTER SET utf8,
  `codecss` longtext,
  `expires` datetime DEFAULT NULL,
  `expiry_flag` enum('d','m','f') NOT NULL DEFAULT 'f',
  `password` varchar(250) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=303 ;

-- --------------------------------------------------------

--
-- Table structure for table `podcast`
--

CREATE TABLE IF NOT EXISTS `podcast` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_posted` datetime NOT NULL,
  `title` varchar(140) NOT NULL,
  `tags` varchar(500) NOT NULL,
  `file_name` varchar(140) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `ricehalla`
--

CREATE TABLE IF NOT EXISTS `ricehalla` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tags` varchar(140) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=192 ;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `identifier` varchar(200) NOT NULL,
  `token` varchar(200) NOT NULL,
  `timeout` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=148 ;

-- --------------------------------------------------------

--
-- Table structure for table `support`
--

CREATE TABLE IF NOT EXISTS `support` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `subject` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `takedowns`
--

CREATE TABLE IF NOT EXISTS `takedowns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_requested` date NOT NULL,
  `requester` varchar(50) NOT NULL,
  `reason` mediumtext NOT NULL,
  `action` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` double NOT NULL,
  `currency` varchar(20) NOT NULL,
  `trans_type` varchar(50) NOT NULL,
  `sender` varchar(100) NOT NULL,
  `recipient` varchar(100) NOT NULL,
  `reason` varchar(10000) NOT NULL,
  `date_posted` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE IF NOT EXISTS `uploads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(20) NOT NULL,
  `type` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL,
  `upload_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `filesize` int(50) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `cipher` varchar(20) NOT NULL DEFAULT 'tripleDES',
  `delete_key` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3708 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 NOT NULL,
  `password` varchar(100) NOT NULL,
  `group_name` varchar(50) NOT NULL DEFAULT 'Member',
  `site_admin` int(2) NOT NULL DEFAULT '0',
  `join_date` datetime NOT NULL,
  `last_seen` datetime NOT NULL,
  `public_key` text,
  `theme` varchar(20) NOT NULL DEFAULT 'default',
  `about` text CHARACTER SET utf8,
  `website` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `minecraft_user` varchar(50) DEFAULT NULL,
  `quote` varchar(140) CHARACTER SET utf8 DEFAULT NULL,
  `blog_title` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `blog_desc` varchar(140) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=221 ;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE IF NOT EXISTS `votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(50) NOT NULL,
  `row_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `points` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1334 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
