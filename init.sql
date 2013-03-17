CREATE TABLE IF NOT EXISTS `%prefix%_content_access` (
  `content_access__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_access__name` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_access__tags` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_access__locked` tinyint(4) NOT NULL,
  `content_access__message` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_access__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_cache` (
  `content_cache__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_cache__table` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_cache__tableid` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_cache__w` int(11) DEFAULT NULL,
  `content_cache__h` int(11) DEFAULT NULL,
  `content_cache__ttl` int(11) DEFAULT NULL,
  `content_cache__contenttype` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_cache__encode` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_cache__data` longblob NOT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_cache__id`),
  KEY `content_cache__table` (`content_cache__table`),
  KEY `content_cache__tableid` (`content_cache__tableid`),
  KEY `content_cache__w` (`content_cache__w`),
  KEY `content_cache__h` (`content_cache__h`),
  KEY `content_cache__ttl` (`content_cache__ttl`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_category` (
  `content_category__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_category__idparent` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `content_category__name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_category__comment` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_category__path` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_category__idtop` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_category__order` int(11) NOT NULL,
  `content_user__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_category__private` tinyint(4) DEFAULT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_category__id`),
  KEY `content_category__idparent` (`content_category__idparent`),
  KEY `content_category__idtop` (`content_category__idtop`),
  KEY `content_user__id` (`content_user__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_crontab` (
  `content_crontab__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_crontab__name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_crontab__active` tinyint(4) DEFAULT NULL,
  `content_crontab__mhdmd` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_crontab__exec` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_crontab__lastrunat` datetime DEFAULT NULL,
  `content_crontab__laststatus` tinyint(4) DEFAULT NULL,
  `content_crontab__lastmessage` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_crontab__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_extra` (
  `content_extra__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extra__object` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extra__name` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extra__dbname` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extra__dbtype` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extra__info` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extra__input` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extra__required` tinyint(4) DEFAULT NULL,
  `content_extra__listview` tinyint(4) DEFAULT NULL,
  `content_extra__relationtable` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_extra__relationname` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_extra__relationfunction` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_extra__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_extralist` (
  `content_extralist__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extra__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extralist__name` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extralist__value` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_extralist__id`),
  UNIQUE KEY `content_extralist__value` (`content_extralist__value`,`content_extra__id`),
  KEY `content_extra__id` (`content_extra__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_file` (
  `content_file__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_category__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_file__filename` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_file__filetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_file__filesize` int(11) DEFAULT NULL,
  `content_file__filepath` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_file__filedata` longblob,
  `content_file__preview` longblob,
  `content_file__infoname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_user__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_file__type` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_file__private` tinyint(4) DEFAULT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_file__id`),
  KEY `content_category__id` (`content_category__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_file2content_filecategory` (
  `content_file__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_filecategory__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_file__id`,`content_filecategory__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_fileassoc` (
  `content_file__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_fileassoc__tableid` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_fileassoc__table` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `content_fileassoc__order` int(11) DEFAULT NULL,
  `content_fileshowtypeitem__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_file__id`,`content_fileassoc__tableid`,`content_fileassoc__table`,`content_fileshowtypeitem__id`),
  KEY `content_fileassoc__tableid` (`content_fileassoc__tableid`),
  KEY `content_fileassoc__table` (`content_fileassoc__table`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_filecategory` (
  `content_filecategory__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_filecategory__idparent` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_filecategory__name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `content_filecategory__comment` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_filecategory__path` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_filecategory__idtop` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_filecategory__order` int(11) NOT NULL,
  `content_user__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_filecategory__private` tinyint(4) DEFAULT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_filecategory__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_fileico` (
  `content_fileico__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_fileico__name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_fileico__40x40` longblob,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_fileico__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_fileshowtype` (
  `content_fileshowtype__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_fileshowtype__name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_fileshowtype__sysname` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_fileshowtype__id`),
  KEY `content_fileshowtype__tag` (`content_fileshowtype__sysname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_fileshowtypeitem` (
  `content_fileshowtypeitem__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_fileshowtype__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_fileshowtypeitem__name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_fileshowtypeitem__sysname` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_fileshowtypeitem__default` tinyint(4) NOT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_fileshowtypeitem__id`),
  KEY `content_fileshowtypeitem__tag` (`content_fileshowtypeitem__sysname`),
  KEY `content_fileshowtype__id` (`content_fileshowtype__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_hostallow` (
  `content_hostallow__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_hostallow__name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_hostallow__hosts` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_hostallow__active` tinyint(4) DEFAULT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_hostallow__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_mailtemplate` (
  `content_mailtemplate__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_mailtemplate__sysname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_mailtemplate__name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_mailtemplate__htmlbody` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_mailtemplate__textbody` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_mailtemplate__subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_mailtemplate__sender_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_mailtemplate__sender_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_mailtemplate__id`),
  UNIQUE KEY `content_mailtemplate__sysname` (`content_mailtemplate__sysname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_mailtemplate2content_user` (
  `content_mailtemplate__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_user__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_mailtemplate__id`,`content_user__id`),
  KEY `content_user__id` (`content_user__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_news` (
  `content_news__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_news__datetime` date NOT NULL DEFAULT '0000-00-00',
  `content_news__published` tinyint(4) DEFAULT NULL,
  `content_news__title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_news__lead` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_news__body` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_news__lang` varchar(3) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_news__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_news2content_newsgroup` (
  `content_news__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_newsgroup__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_news__id`,`content_newsgroup__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_newsgroup` (
  `content_newsgroup__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_newsgroup__name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_newsgroup__tag` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_newsgroup__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_page` (
  `content_page__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_page__idparent` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_page__name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `content_page__info` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_page__title` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_page__url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `content_page__idtop` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_page__order` int(11) DEFAULT NULL,
  `content_page__description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_page__keywords` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_page__lang` char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_page__path` blob,
  `content_template__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_page__params` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_page__hostallow` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_page__requiredaccess` int(11) DEFAULT NULL,
  `content_page__menu_visible` tinyint(4) NOT NULL DEFAULT '1',
  `content_page__sitemap_visible` tinyint(4) NOT NULL DEFAULT '1',
  `content_page__enabled` tinyint(4) NOT NULL DEFAULT '1',
  `content_page__redirect_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_page__redirect_page` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_page__id`),
  KEY `content_page__url` (`content_page__url`),
  KEY `content_template__id` (`content_template__id`),
  KEY `content_page__idtop` (`content_page__idtop`),
  KEY `content_page__lang` (`content_page__lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_plugin` (
  `content_plugin__sysname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_plugin__name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_plugin__version` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_plugin__path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_plugin__enabled` tinyint(4) DEFAULT NULL,
  UNIQUE KEY `content_plugin__sysname` (`content_plugin__sysname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_section` (
  `content_section__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_section__sysname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_section__name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_section__title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_section__id`),
  KEY `content_section__sysname` (`content_section__sysname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_section2content_page` (
  `content_section__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_page__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_section2content_page__column` tinyint(4) DEFAULT NULL,
  `content_section2content_page__order` tinyint(4) DEFAULT NULL,
  `content_section2content_page__requiredaccess` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_section2content_page__data` blob,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_section__id`,`content_page__id`),
  KEY `content_section2content_page__column` (`content_section2content_page__column`),
  KEY `content_section2content_page__order` (`content_section2content_page__order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_tags` (
  `content_tags__tag` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_tags__tableid` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_tags__table` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  KEY `content_tags__id` (`content_tags__tableid`,`content_tags__table`,`content_tags__tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_template` (
  `content_template__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_template__name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_template__srcfile` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_template__lang` char(2) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_template__id`),
  KEY `content_template__lang` (`content_template__lang`),
  KEY `content_template__name` (`content_template__name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_text` (
  `content_text__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_page__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_section__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_text__lang` char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_text__name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_text__title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_text__lead` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_text__body` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_text__order` int(11) DEFAULT NULL,
  `content_text__tableid` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_text__table` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_text__id`),
  KEY `content_text__lang` (`content_text__lang`),
  KEY `content_page__id` (`content_page__id`),
  KEY `content_section__id` (`content_section__id`),
  KEY `content_text__tableid` (`content_text__tableid`),
  KEY `content_text__table` (`content_text__table`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_user2content_usergroup` (
  `content_user__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_usergroup__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_user__id`,`content_usergroup__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_useracl` (
  `content_access__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_user__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_useracl__bit` bigint(20) NOT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_access__id`,`content_user__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_usergroup` (
  `content_usergroup__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_usergroup__name` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_usergroup__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_usergroupacl` (
  `content_access__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_usergroup__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_usergroupacl__bit` bigint(20) NOT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_access__id`,`content_usergroup__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_user_base` (
  `content_user__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_user__username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_user__password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_user__surname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_user__firstname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_user__email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_user__hide_email` tinyint(1) DEFAULT NULL,
  `content_user__phone` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_user__company` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_user__postcode` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_user__city` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_user__street` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_user__country` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_user__confirm_regulation` tinyint(4) NOT NULL,
  `content_user__confirm_userdata` tinyint(4) NOT NULL,
  `content_user__confirm_marketing` tinyint(4) NOT NULL,
  `content_user__status` tinyint(4) DEFAULT NULL,
  `content_user__comment` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_user__admin_hostallow` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_user__login_correct` int(11) DEFAULT NULL,
  `content_user__login_false` int(11) DEFAULT NULL,
  `content_user__login_falsecount` int(11) DEFAULT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_user__id`),
  UNIQUE KEY `content_user__username` (`content_user__username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_user_extra` (
  `content_user__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  UNIQUE KEY `content_user__id` (`content_user__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE ALGORITHM=MERGE VIEW `%prefix%_content_user` AS
SELECT `content_user_base`.* FROM (`%prefix%_content_user_base` `content_user_base` JOIN `%prefix%_content_user_extra` `content_user_extra`)
WHERE (`content_user_extra`.`content_user__id` = `content_user_base`.`content_user__id`);

CREATE TABLE IF NOT EXISTS `%prefix%_core_config` (
  `core_config__name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_config__value` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`core_config__name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_core_configadminview` (
  `core_configadminview__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminview__tag` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_user__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminview__dbname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminview__mainkey` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminview__function` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminview__button_back` tinyint(4) DEFAULT NULL,
  `core_configadminview__button_addnew` tinyint(4) DEFAULT NULL,
  `core_configadminview__rowperpage` smallint(6) DEFAULT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`core_configadminview__id`),
  KEY `core_configadminview__tag` (`core_configadminview__tag`,`content_user__id`),
  KEY `content_user__id` (`content_user__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_core_configadminviewcolumn` (
  `core_configadminviewcolumn__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminview__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminviewcolumn__idcol` smallint(6) NOT NULL,
  `core_configadminviewcolumn__title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminviewcolumn__width` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminviewcolumn__value` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminviewcolumn__align` varchar(1) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_configadminviewcolumn__order` tinyint(4) DEFAULT NULL,
  `core_configadminviewcolumn__peeklist_function` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminviewcolumn__peeklist_array` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminviewcolumn__showas` tinyint(4) DEFAULT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`core_configadminviewcolumn__id`),
  KEY `core_configadminview__id` (`core_configadminview__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_core_relation` (
  `core_relation__srctable` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_relation__srcid` int(11) DEFAULT NULL,
  `core_relation__dsttable` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_relation__dstid` int(11) DEFAULT NULL,
  `core_relation__order` int(11) DEFAULT NULL,
  `core_relation__type` int(11) DEFAULT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` int(11) DEFAULT NULL,
  UNIQUE KEY `core_relation__srctable` (`core_relation__srctable`,`core_relation__srcid`,`core_relation__dsttable`,`core_relation__dstid`,`core_relation__type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_core_session` (
  `core_session__sid` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_session__data` BLOB CHARACTER SET utf8 COLLATE utf8_general_ci,
  `core_session__remoteaddr` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_session__useragent` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_session__lastused` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`core_session__sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_core_sitemanager` (
  `core_sitemanager__module` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_sitemanager__versiondb` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_sitemanager__versionapp` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_sitemanager__dateedit` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_core_task` (
  `core_task__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_task___plugin` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_task___function` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_task___params` blob,
  `core_task___status` tinyint(4) DEFAULT NULL,
  `core_task___result` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`core_task__id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_core_translation` (
  `core_translation__id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_translation__source` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_translation__target` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_translation__module` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_translation__lang` varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`core_translation__id`),
  KEY `core_translation__source` (`core_translation__source`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_core_changed` (
  `core_changed__id` char(36) NOT NULL,
  `core_changed__tableid` char(36) NOT NULL,
  `core_changed__table` varchar(255) DEFAULT NULL,
  `core_changed__olddata` blob,
  `core_deleted__state` char(5) NOT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) DEFAULT NULL,
  PRIMARY KEY (`core_changed__id`),
  KEY `core_changed__tableid` (`core_changed__tableid`),
  KEY `core_changed__table` (`core_changed__table`),
  KEY `record_create_date` (`record_create_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `%prefix%_core_deleted` (
  `core_deleted__id` char(36) NOT NULL,
  `core_deleted__tableid` char(36) NOT NULL,
  `core_deleted__table` varchar(255) DEFAULT NULL,
  `core_deleted__olddata` blob,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) DEFAULT NULL,
  PRIMARY KEY (`core_deleted__id`),
  KEY `core_deleted__tableid` (`core_deleted__tableid`),
  KEY `core_deleted__table` (`core_deleted__table`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;