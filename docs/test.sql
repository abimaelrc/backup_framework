-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 21, 2013 at 11:56 AM
-- Server version: 5.1.50
-- PHP Version: 5.3.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
  `notes_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1' COMMENT '0 => disabled, 1 => active',
  `created_by` int(11) unsigned NOT NULL,
  `created_datetime` datetime NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`notes_id`),
  KEY `active` (`active`),
  KEY `created_by` (`created_by`),
  KEY `created_datetime` (`created_datetime`),
  KEY `updated_by` (`updated_by`),
  KEY `updated_datetime` (`updated_datetime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `notes`
--


-- --------------------------------------------------------

--
-- Table structure for table `urls`
--

DROP TABLE IF EXISTS `urls`;
CREATE TABLE IF NOT EXISTS `urls` (
  `urls_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(40) NOT NULL,
  `controller` varchar(40) NOT NULL,
  `action` varchar(40) NOT NULL,
  `params` varchar(40) DEFAULT NULL,
  `alias` varchar(40) NOT NULL,
  `parent` int(11) unsigned NOT NULL,
  `created_by` int(11) unsigned NOT NULL,
  `created_datetime` datetime NOT NULL,
  `updated_by` int(11) unsigned DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`urls_id`),
  KEY `module` (`module`),
  KEY `controller` (`controller`),
  KEY `action` (`action`),
  KEY `params` (`params`),
  KEY `alias` (`alias`),
  KEY `parent` (`parent`),
  KEY `created_by` (`created_by`),
  KEY `created_datetime` (`created_datetime`),
  KEY `updated_by` (`updated_by`),
  KEY `updated_datetime` (`updated_datetime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `urls`
--

INSERT INTO `urls` (`urls_id`, `module`, `controller`, `action`, `params`, `alias`, `parent`, `created_by`, `created_datetime`, `updated_by`, `updated_datetime`) VALUES
(1, 'default', 'index', 'index', NULL, 'default-index', 1, 1, '2011-07-27 00:00:00', NULL, NULL),
(2, 'default', 'error', 'error', NULL, 'error', 1, 1, '2011-07-27 00:00:00', NULL, NULL),
(3, 'authentication', 'index', 'index', NULL, 'login', 3, 1, '2011-07-27 00:00:00', NULL, NULL),
(4, 'authentication', 'index', 'logout', NULL, 'logout', 3, 1, '2011-07-27 00:00:00', NULL, NULL),
(5, 'configure', 'index', 'index', NULL, 'configure', 5, 1, '2011-07-27 00:00:00', NULL, NULL),
(6, 'configure', 'users', 'index', NULL, 'configure-users', 5, 1, '2011-07-27 00:00:00', NULL, NULL),
(7, 'configure', 'edit', 'index', NULL, 'configure-edit', 5, 1, '2011-07-27 00:00:00', NULL, NULL),
(8, 'configure', 'edit', 'edit', NULL, 'configure-edit-edit', 5, 1, '2011-07-27 00:00:00', NULL, NULL),
(9, 'configure', 'edit', 'delete', NULL, 'configure-edit-delete', 5, 1, '2011-07-27 00:00:00', NULL, NULL),
(10, 'notes', 'index', 'index', NULL, 'notes', 10, 1, '2011-07-27 00:00:00', NULL, NULL),
(11, 'notes', 'ajax', 'index', NULL, 'notes-ajax', 10, 1, '2011-07-27 00:00:00', NULL, NULL),
(12, 'notes', 'ajax', 'set-inactive-notes', NULL, 'notes-ajax-set-inactive-notes', 10, 1, '2011-07-27 00:00:00', NULL, NULL),
(13, 'statistics', 'index', 'index', 'from_hour/to_hour/from/to/type/users_id', 'statistics', 13, 1, '2012-11-07 14:41:47', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `users_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `num_empl` varchar(10) NOT NULL,
  `pwd` varchar(70) NOT NULL,
  `role` varchar(30) NOT NULL,
  `access` varchar(250) DEFAULT NULL COMMENT 'json',
  `change_pwd` int(1) NOT NULL DEFAULT '1',
  `in_charge` int(11) unsigned DEFAULT NULL,
  `created_by` int(11) unsigned NOT NULL,
  `created_datetime` datetime NOT NULL,
  `created_by_remote_addr` varchar(15) NOT NULL,
  `block_access` int(1) DEFAULT NULL,
  `block_by` int(11) unsigned DEFAULT NULL,
  `block_datetime` datetime DEFAULT NULL,
  `block_by_remote_addr` varchar(15) DEFAULT NULL,
  `updated_by` int(11) unsigned DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `updated_by_remote_addr` varchar(15) DEFAULT NULL,
  `deleted_account` int(1) DEFAULT NULL,
  `deleted_by` int(11) unsigned DEFAULT NULL,
  `deleted_datetime` datetime DEFAULT NULL,
  `deleted_by_remote_addr` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`users_id`),
  KEY `name` (`name`),
  KEY `num_empl` (`num_empl`),
  KEY `pwd` (`pwd`),
  KEY `role` (`role`),
  KEY `change_pwd` (`change_pwd`),
  KEY `in_charge` (`in_charge`),
  KEY `created_by` (`created_by`),
  KEY `created_datetime` (`created_datetime`),
  KEY `created_by_remote_addr` (`created_by_remote_addr`),
  KEY `block_access` (`block_access`),
  KEY `block_by` (`block_by`),
  KEY `block_datetime` (`block_datetime`),
  KEY `block_by_remote_addr` (`block_by_remote_addr`),
  KEY `updated_by` (`updated_by`),
  KEY `updated_datetime` (`updated_datetime`),
  KEY `updated_by_remote_addr` (`updated_by_remote_addr`),
  KEY `deleted_account` (`deleted_account`),
  KEY `deleted_by` (`deleted_by`),
  KEY `deleted_datetime` (`deleted_datetime`),
  KEY `deleted_by_remote_addr` (`deleted_by_remote_addr`),
  KEY `access` (`access`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Table structure for table `users_role`
--

DROP TABLE IF EXISTS `users_role`;
CREATE TABLE IF NOT EXISTS `users_role` (
  `users_role_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(30) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `role_order` int(11) NOT NULL,
  PRIMARY KEY (`users_role_id`),
  KEY `role` (`role`),
  KEY `role_name` (`role_name`),
  KEY `role_order` (`role_order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users_role`
--

INSERT INTO `users_role` (`users_role_id`, `role`, `role_name`, `role_order`) VALUES
(1, 'admin', 'Administrador', 1),
(2, 'supervisor', 'Supervisor', 2),
(3, 'user', 'Usuario', 3);
