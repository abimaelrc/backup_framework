--
-- Dumping data for table `urls`
--

-- sixth column (the one that have as value 1000000000) is the urls_id number that /chat/index/index has

INSERT INTO `urls` (`module`, `controller`, `action`, `params`, `alias`, `parent`, `created_by`, `created_datetime`, `updated_by`, `updated_datetime`) VALUES
('chat', 'index', 'index', NULL, 'chat', 1000000000, 1, '2013-08-28 11:04:19', NULL, NULL),
('chat', 'ajax', 'index', NULL, 'chat-ajax', 1000000000, 1, '2013-08-28 14:25:19', NULL, NULL),
('chat', 'ajax', 'count', NULL, 'chat-ajax-count', 1000000000, 1, '2013-08-28 14:25:41', NULL, NULL),
('chat', 'ajax', 'messages', NULL, 'chat-ajax-messages', 1000000000, 1, '2013-08-28 14:33:28', NULL, NULL);
