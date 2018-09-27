DROP TABLE IF EXISTS `ks_acl_access`;
CREATE TABLE IF NOT EXISTS `ks_acl_access` (
  `acc_roleid` varchar(32) NOT NULL COMMENT 'Role Id',
  `acc_resid` varchar(32) NOT NULL COMMENT 'Resource Id',
  `acc_privilegeid` varchar(64) DEFAULT NULL COMMENT 'Privilege Id',
  `acc_allow` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Allow (1) or not (0) to use the privilege',
  KEY `acc_roleid` (`acc_roleid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ks_acl_access`
--

INSERT INTO `ks_acl_access` VALUES('ADMIN', 'user', 'change_password', 1);
INSERT INTO `ks_acl_access` VALUES('USER', 'user', 'change_password', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ks_acl_inheritance`
--
DROP TABLE IF EXISTS `ks_acl_inheritance`;
CREATE TABLE IF NOT EXISTS `ks_acl_inheritance` (
  `inh_childid` int(11) NOT NULL,
  `inh_parentid` int(11) NOT NULL,
  `inh_order` varchar(5) NOT NULL,
  UNIQUE KEY `inh_childid` (`inh_childid`,`inh_parentid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Stores ACL inheritance';

-- --------------------------------------------------------

--
-- Table structure for table `ks_acl_resource`
--
DROP TABLE IF EXISTS `ks_acl_resource`;
CREATE TABLE IF NOT EXISTS `ks_acl_resource` (
  `res_id` varchar(32) NOT NULL COMMENT 'Resource Id',
  `res_parentid` varchar(32) DEFAULT NULL COMMENT 'Resource Parent Id, if inherited',
  `res_privilegeid` varchar(64) NOT NULL COMMENT 'Privilege Id',
  `res_desc` text NOT NULL COMMENT 'Description of resource',
  KEY `res_id` (`res_id`),
  KEY `res_parentid` (`res_parentid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Stores resources in the system';

--
-- Dumping data for table `ks_acl_resource`
--

INSERT INTO `ks_acl_resource` VALUES('user', NULL, 'change_password', 'Able to change password');

-- --------------------------------------------------------

--
-- Table structure for table `ks_acl_role`
--
DROP TABLE IF EXISTS `ks_acl_role`;
CREATE TABLE IF NOT EXISTS `ks_acl_role` (
  `role_id` varchar(32) NOT NULL,
  `role_name` varchar(64) NOT NULL COMMENT 'Role name',
  `role_desc` text NOT NULL COMMENT 'Role description',
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Stores system roles';

--
-- Dumping data for table `ks_acl_role`
--

INSERT INTO `ks_acl_role` VALUES('ADMIN', 'Administrator', 'Administrator with full control');
INSERT INTO `ks_acl_role` VALUES('USER', 'User', 'Standard user');
INSERT INTO `ks_acl_role` VALUES('MANAGER', 'Manager', '');

DROP TABLE IF EXISTS `ks_controlpanel_menu`;
CREATE TABLE IF NOT EXISTS `ks_controlpanel_menu` (
  `cpm_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cpm_parentid` int(11) DEFAULT NULL,
  `cpm_label` varchar(100) DEFAULT NULL,
  `cpm_url` varchar(100) DEFAULT NULL,
  `cpm_image` varchar(150) DEFAULT NULL,
  `cpm_tooltip` varchar(100) DEFAULT NULL,
  `cpm_order` tinyint(1) DEFAULT NULL,
  `cpm_status` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`cpm_id`),
  KEY `cpm_parentid` (`cpm_parentid`),
  KEY `cpm_status` (`cpm_status`)
) ENGINE=InnoDB;

INSERT INTO `ks_controlpanel_menu` VALUES(1, 0, 'Option', 'admin-option/', '', 'Option', 0, 1);
INSERT INTO `ks_controlpanel_menu` VALUES(5, 0, 'Menu', 'admin-menu/', '', 'Menu', 0, 1);
INSERT INTO `ks_controlpanel_menu` VALUES(7, 0, 'User', 'admin-user/', '', 'User', 0, 1);
INSERT INTO `ks_controlpanel_menu` VALUES(11, 0, 'Dashboard', 'admin-dashboard/', '', 'Dashboard', 0, 1);
INSERT INTO `ks_controlpanel_menu` VALUES(17, 0, 'News', 'admin-news/', '', 'News', 0, 1);
INSERT INTO `ks_controlpanel_menu` VALUES(26, 5, 'List All', 'admin-menu/list.php', '', 'List All', 1, 1);
INSERT INTO `ks_controlpanel_menu` VALUES(27, 5, 'Add Menu', 'admin-menu/add.php', '', 'Add Menu', 2, 1);
INSERT INTO `ks_controlpanel_menu` VALUES(37, 7, 'List All', 'admin-user/list.php', '', 'List All', 1, 1);
INSERT INTO `ks_controlpanel_menu` VALUES(38, 7, 'Add User', 'admin-user/add.php', '', 'Add User', 2, 1);
INSERT INTO `ks_controlpanel_menu` VALUES(39, 8, 'List All', 'admin-acl/list.php', '', 'List All', 1, 1);
INSERT INTO `ks_controlpanel_menu` VALUES(40, 8, 'Add Role', 'admin-acl/roleadd.php', '', 'Add Role', 2, 1);
INSERT INTO `ks_controlpanel_menu` VALUES(46, 11, 'List All', 'admin-dashboard/list.php', '', 'List All', 1, 1);
INSERT INTO `ks_controlpanel_menu` VALUES(47, 11, 'Add Dashboard', 'admin-dashboard/add.php', '', 'Add Dashboard', 2, 1);
INSERT INTO `ks_controlpanel_menu` VALUES(58, 16, 'Not Sent', 'admin-news/listall.php', '', 'Not Sent', 2, 1);
INSERT INTO `ks_controlpanel_menu` VALUES(60, 16, 'Source Code', 'admin-news/source_code.php', '', 'Source Code', 4, 1);
INSERT INTO `ks_controlpanel_menu` VALUES(61, 16, 'Option', 'admin-news/option.php', '', 'Option', 5, 1);
INSERT INTO `ks_controlpanel_menu` VALUES(62, 17, 'List All', 'admin-news/list.php', '', 'List All', 1, 1);
INSERT INTO `ks_controlpanel_menu` VALUES(63, 17, 'Add News', 'admin-news/add.php', '', 'Add News', 2, 1);

DROP TABLE IF EXISTS `ks_dashboard`;
CREATE TABLE IF NOT EXISTS `ks_dashboard` (
  `dsh_id` int(11) NOT NULL AUTO_INCREMENT,
  `dsh_title` varchar(32) DEFAULT NULL,
  `dsh_desc` varchar(255) DEFAULT NULL,
  `dsh_portlet` longtext,
  `dsh_hide` text,
  `dsh_created_by` varchar(32) DEFAULT NULL,
  `dsh_modified_by` varchar(32) DEFAULT NULL,
  `dsh_created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dsh_modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`dsh_id`)
) ENGINE=InnoDB;

INSERT INTO `ks_dashboard` VALUES(1, 'Dashboard 1', '', 'a:3:{s:4:"col1";a:2:{i:1;s:5:"box-1";i:2;s:5:"box-3";}s:4:"col2";a:1:{i:1;s:5:"box-2";}s:0:"";a:3:{s:5:"box-1";a:3:{s:4:"type";s:4:"HTML";s:7:"content";s:54:"<p align="center">-Welcome to Dynamic Admin Panel-</p>";s:5:"title";s:12:"HTML Content";}s:5:"box-3";a:3:{s:4:"type";s:3:"URL";s:7:"content";s:18:"http://gizmodo.com";s:5:"title";s:12:"External URL";}s:5:"box-2";a:3:{s:4:"type";s:3:"URL";s:7:"content";s:35:"//www.youtube.com/embed/LkOFbXVfcjw";s:5:"title";s:13:"Youtube Video";}}}', NULL, 'admin', 'admin', '2014-04-01 16:39:42', '2014-05-22 00:00:00');
INSERT INTO `ks_dashboard` VALUES(2, 'Dashboard 2', '', 'a:2:{s:4:"col1";a:2:{i:1;s:5:"box-1";i:2;s:5:"box-2";}s:0:"";a:3:{s:5:"box-1";a:3:{s:4:"type";s:4:"HTML";s:7:"content";s:38:"<i>Please put your html code here.</i>";s:5:"title";s:9:"Portlet 1";}s:5:"box-3";a:3:{s:4:"type";s:3:"URL";s:7:"content";s:22:"http://codecanyon.com/";s:5:"title";s:9:"Portlet 3";}s:5:"box-2";a:3:{s:4:"type";s:3:"URL";s:7:"content";s:18:"http://gizmodo.com";s:5:"title";s:9:"Portlet 2";}}}', NULL, 'admin', 'admin', '2014-05-22 16:46:08', '2014-05-22 00:00:00');

DROP TABLE IF EXISTS `ks_lostpassword`;
CREATE TABLE IF NOT EXISTS `ks_lostpassword` (
  `lp_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lp_userid` varchar(50) NOT NULL DEFAULT '',
  `lp_random` int(8) NOT NULL DEFAULT '0',
  `lp_deadline` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`lp_id`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ks_menu`;
CREATE TABLE IF NOT EXISTS `ks_menu` (
  `menu_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(255) NOT NULL DEFAULT '',
  `menu_path` varchar(255) DEFAULT '',
  `menu_jscode` text,
  `menu_option` longtext,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB;

INSERT INTO `ks_menu` VALUES(1, 'Main menu', 'path', '', 'a:1:{s:12:"menuo_layout";s:1:"1";}');

DROP TABLE IF EXISTS `ks_menuitem`;
CREATE TABLE IF NOT EXISTS `ks_menuitem` (
  `mi_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mi_menuid` int(11) NOT NULL,
  `mi_parentid` int(11) NOT NULL DEFAULT '0',
  `mi_notlogin` tinyint(1) NOT NULL DEFAULT '0',
  `mi_label` varchar(100) DEFAULT NULL,
  `mi_urltype` varchar(32) DEFAULT '',
  `mi_url` varchar(100) DEFAULT '',
  `mi_image1` varchar(255) DEFAULT '',
  `mi_image2` varchar(255) DEFAULT '',
  `mi_tooltip` varchar(50) DEFAULT NULL,
  `mi_roles` varchar(255) DEFAULT '',
  `mi_order` int(3) DEFAULT '0',
  `mi_option` longtext,
  PRIMARY KEY (`mi_id`)
) ENGINE=InnoDB;

INSERT INTO `ks_menuitem` VALUES(1, 1, 0, 0, 'Home', 'internal', 'home.php', '', '', '', 'ADMIN;USER;', 1, 'a:1:{s:8:"mio_icon";s:24:"glyphicon glyphicon-home";}');
INSERT INTO `ks_menuitem` VALUES(2, 1, 0, 1, 'News', 'internal', 'ks_builtin/newslist.php', '', '', '', 'ADMIN;USER;', 3, 'a:1:{s:8:"mio_icon";s:24:"glyphicon glyphicon-star";}');
INSERT INTO `ks_menuitem` VALUES(3, 1, 0, 1, 'Login', 'internal', 'ks_user/login.php', '', '', '', '', 6, 'a:1:{s:8:"mio_icon";s:24:"glyphicon glyphicon-link";}');
INSERT INTO `ks_menuitem` VALUES(4, 1, 0, 1, 'Lost Password', 'internal', 'ks_user/lostpassword.php', '', '', '', '', 7, 'a:1:{s:8:"mio_icon";s:23:"glyphicon glyphicon-off";}');
INSERT INTO `ks_menuitem` VALUES(5, 1, 0, 0, 'Logout', 'internal', 'ks_user/logout.php', '', '', '', 'ADMIN;USER;', 5, 'a:1:{s:8:"mio_icon";s:23:"glyphicon glyphicon-off";}');
INSERT INTO `ks_menuitem` VALUES(6, 1, 0, 0, 'Dashboard 2', 'internal', 'ks_builtin/dashboard.php?did=2', '', '', '', 'ADMIN;USER;', 2, 'a:1:{s:8:"mio_icon";s:29:"glyphicon glyphicon-dashboard";}');
INSERT INTO `ks_menuitem` VALUES(7, 1, 0, 0, 'ACL Sample', 'internal', 'sample/sample_acl.php', '', '', 'Sample Access Control List', 'ADMIN;USER;', 4, 'a:1:{s:8:"mio_icon";s:26:"glyphicon glyphicon-random";}');

DROP TABLE IF EXISTS `ks_news`;
CREATE TABLE IF NOT EXISTS `ks_news` (
  `ns_id` int(11) NOT NULL AUTO_INCREMENT,
  `ns_title` varchar(100) DEFAULT NULL,
  `ns_desc` text,
  `ns_start_date` date NOT NULL DEFAULT '0000-00-00',
  `ns_end_date` date NOT NULL DEFAULT '0000-00-00',
  `ns_public` tinyint(1) DEFAULT '0',
  `ns_private` tinyint(1) DEFAULT '0',
  `ns_sender` varchar(32) DEFAULT NULL,
  `ns_receiver` varchar(32) DEFAULT NULL,
  `ns_user_read` longtext,
  `ns_option` longtext,
  `ns_status` tinyint(1) DEFAULT '0',
  `ns_created_by` varchar(25) DEFAULT NULL,
  `ns_modified_by` varchar(25) DEFAULT NULL,
  `ns_created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ns_modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ns_id`)
) ENGINE=InnoDB;

INSERT INTO `ks_news` VALUES(1, 'Welcome to Dynamic Admin Panel!', 'What is Dynamic Admin Panel?\r\n	- Dynamic Admin Panel is a dynamic Control Panel with fully working front-end and back-end. It is created after the author''s frustration with\r\n		static admin templates which is difficult to integrate with back-end (PHP and MySQL).\r\n	- Dynamic Admin Panel uses Bootstrap 3.1, jQuery, jQuery UI, jQuery ValidationEngine, PHP OOP and Zend Framework.\r\n	- It provides several core features, including Installer, User Management, Dashboard, Navigation Menu, Options and News.\r\n	- More features to come in coming months, this is a serious product with a lot of potential.\r\n	- It has 2 sections (Home and Control Panel) both using the same login page\r\n	---- Home for users\r\n	---- Control Panel for admin\r\n	\r\nInstaller\r\n	- Comes with installer, setup is breeze\r\n	- Requires PHP 5.2+\r\n	- Requires PDO_Mysql. \r\n	- Highly configurable config.php\r\n	- Error log mechanism (error.txt) to easily identify back-end problems (PHP, MySQL)\r\n	\r\nDashboard\r\n	- Unlimited dashboard\r\n	- Each dashboard can have 1 or more column\r\n	- Each column can have 1 or more portlet\r\n	- A portlet content can be HTML content or URL via AJAX call\r\n	- Drag &amp; drop feature (both on admin and user side)\r\n	- Personalization - it remembers user preference\r\n	- Integrate with Navigation Menu\r\n	- Embed dashboard into any PHP file using generated ''Source Code''\r\n	\r\nMenu\r\n	- Bootstrap menu\r\n	- Support 4 levels of submenu\r\n	- Menu of 4 types: Internal URL, External URL, Parent Menu, Separator\r\n	- Unlimited number of menu, each can have unlimited number of menu items\r\n	- Supports Glyphicon and Font-awesome\r\n	\r\nUser\r\n	- Unlimited number of users and administrators\r\n	- Login, logout, change password, update own profile\r\n	- User administration - add, display, modify, delete\r\n	- User administration - Search for users\r\n	- Disable user (cannot login, but userid is not deleted)\r\n	- Supports 2 roles: ADMIN &amp; USER (more in the future)\r\n	\r\nOption\r\n	- Configurable option, can use Source Code to use in any PHP file\r\n	- Support Option Group... easier to manage and find options\r\n	- Create new Option using provided form\r\n	- Add, Modify, Delete options\r\n	\r\nNews \r\n	- News administration - add, display, modify, publish news\r\n	- Control news visible to public/ login users / both\r\n	- Control publish date, only appear during publish duration\r\n	- Automatically added into front page', '2014-03-19', '2014-03-28', 0, 1, 'admin', '', 'a:1:{s:4:"read";a:22:{i:1;s:5:"admin";i:2;s:5:"admin";i:3;s:5:"admin";i:4;s:5:"admin";i:5;s:5:"admin";i:6;s:5:"admin";i:7;s:5:"admin";i:8;s:5:"admin";i:9;s:5:"admin";i:10;s:5:"admin";i:11;s:5:"admin";i:12;s:5:"admin";i:13;s:5:"admin";i:14;s:5:"admin";i:15;s:5:"admin";i:16;N;i:17;N;i:18;s:5:"admin";i:19;s:5:"admin";i:20;s:5:"admin";i:21;s:5:"admin";i:22;s:5:"admin";}}', NULL, 1, 'admin', 'admin', NOW(), '0000-00-00 00:00:00');

DROP TABLE IF EXISTS `ks_option`;
CREATE TABLE IF NOT EXISTS `ks_option` (
  `option_code` varchar(64) NOT NULL,
  `option_desc` text,
  `option_group` varchar(64) DEFAULT NULL,
  `option_value` longtext,
  `option_readonly` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`option_code`)
) ENGINE=InnoDB COMMENT='Store options either in string, numbers or serialized';

INSERT INTO `ks_option` VALUES('admin_email', 'Admin Email', 'General', '', 1);
INSERT INTO `ks_option` VALUES('session_timeout', 'Number of seconds before prompt ''session timeout'' alert.', 'General', '1800', 1);
INSERT INTO `ks_option` VALUES('session_timeout_before_forcelogout', 'Number of seconds before user is forced logout (if no action taken after prompt ''session timeout'' alert is displayed).', 'General', '120', 1);
INSERT INTO `ks_option` VALUES('user_password_minlength', 'Minimum length of password in characters', 'General', '8', 0);

DROP TABLE IF EXISTS `t_user`;
CREATE TABLE IF NOT EXISTS `t_user` (
  `usr_id` varchar(32) NOT NULL COMMENT 'Unique User ID',
  `usr_password` varchar(32) NOT NULL COMMENT 'User password',
  `usr_salt` varchar(6) DEFAULT NULL,
  `usr_name` varchar(255) DEFAULT NULL COMMENT 'User fullname',
  `usr_email` varchar(32) DEFAULT NULL COMMENT 'User email address',
  `usr_role` varchar(50) NOT NULL COMMENT 'User role',
  `usr_enabled` tinyint(1) DEFAULT '0' COMMENT 'Enable, 1 for enabled, 0 for disabled',
  `usr_lastlogin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'User last login datetime',
  `usr_phone_office` varchar(20) DEFAULT NULL COMMENT 'Office phone',
  `usr_phone_mobile` varchar(20) DEFAULT NULL COMMENT 'Mobile phone',
  `usr_option` longtext,
  `usr_date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date & time record created.',
  `usr_date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date & time record last modified.',
  `usr_userid_created` varchar(32) DEFAULT NULL COMMENT 'UserID who creates the record, from t_user.usr_userid',
  `usr_userid_modified` varchar(32) DEFAULT NULL COMMENT 'UserID who last modified the record, from t_user.usr_userid',
  PRIMARY KEY (`usr_id`)
) ENGINE=InnoDB COMMENT='System users';
