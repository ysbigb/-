-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-12-16 18:15:33
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `xpgame`
--

-- --------------------------------------------------------

--
-- 表的结构 `xpgame_prize_total`
--

CREATE TABLE IF NOT EXISTS `xpgame_prize_total` (
  `id` int(11) NOT NULL,
  `p1` int(8) NOT NULL DEFAULT '0',
  `p2` int(8) NOT NULL DEFAULT '0',
  `p3` int(8) NOT NULL DEFAULT '0',
  `p4` int(8) NOT NULL DEFAULT '0',
  `p5` int(8) NOT NULL DEFAULT '0',
  `p6` int(8) NOT NULL DEFAULT '0',
  `p7` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `xpgame_winner_info`
--

CREATE TABLE IF NOT EXISTS `xpgame_winner_info` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `wx_openid` varchar(64) NOT NULL,
  `prize` varchar(4) NOT NULL,
  `user_name` varchar(64) DEFAULT NULL,
  `mobile` varchar(16) DEFAULT NULL,
  `address` varchar(256) DEFAULT NULL,
  `op_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `xpgame_wx_info`
--

CREATE TABLE IF NOT EXISTS `xpgame_wx_info` (
  `wx_nickname` varchar(128) NOT NULL,
  `roll_count` tinyint(1) NOT NULL DEFAULT '0',
  `wx_openid` varchar(64) NOT NULL,
  `op_date` date DEFAULT NULL,
  `roll_total` tinyint(1) NOT NULL DEFAULT '0',
  `wx_avatar` varchar(256) NOT NULL,
  PRIMARY KEY (`wx_openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
