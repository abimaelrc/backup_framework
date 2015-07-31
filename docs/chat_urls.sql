--
-- Dumping data for table `urls`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `chat_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `chat_type` varchar(30) NOT NULL,
  `created_by` int(11) unsigned NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`chat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `chat_private_users`
--

CREATE TABLE IF NOT EXISTS `chat_private_users` (
  `chat_private_users_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(11) unsigned NOT NULL,
  `chat_type` varchar(30) NOT NULL,
  `leader` int(11) unsigned NOT NULL DEFAULT '0',
  `active` int(1) NOT NULL DEFAULT '1',
  `created_datetime` datetime NOT NULL,
  `closed_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`chat_private_users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


INSERT INTO `urls` (`module`, `controller`, `action`, `params`, `alias`, `parent`, `created_by`, `created_datetime`, `updated_by`, `updated_datetime`) VALUES
('chat', 'index', 'index', NULL, 'chat', 14, 1, '2013-08-28 11:04:19', NULL, NULL),
('chat', 'ajax', 'index', NULL, 'chat-ajax', 14, 1, '2013-08-28 14:25:19', NULL, NULL),
('chat', 'ajax', 'count', NULL, 'chat-ajax-count', 14, 1, '2013-08-28 14:25:41', NULL, NULL),
('chat', 'ajax', 'messages', NULL, 'chat-ajax-messages', 14, 1, '2013-08-28 14:33:28', NULL, NULL),
('chat', 'ajax', 'add', NULL, 'chat-ajax-add', 14, 1, '2013-08-29 10:35:49', NULL, NULL),
('chat', 'ajax', 'available-users', NULL, 'chat-ajax-available-users', 14, 1, '2013-08-29 12:04:15', NULL, NULL),
('chat', 'ajax', 'add-private-user', NULL, 'chat-ajax-add-private-user', 14, 1, '2013-09-12 10:20:36', NULL, NULL),
('chat', 'ajax', 'close', NULL, 'chat-ajax-close', 14, 1, '2013-09-16 00:00:00', NULL, NULL);
