CREATE TABLE IF NOT EXISTS `%prefix%_content_access` (
  `content_access__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_access__name` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_access__tags` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_access__locked` TINYINT(4) NOT NULL,
  `content_access__message` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_access__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_cache` (
  `content_cache__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_cache__table` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_cache__tableid` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_cache__w` INT(11) DEFAULT NULL,
  `content_cache__h` INT(11) DEFAULT NULL,
  `content_cache__ttl` INT(11) DEFAULT NULL,
  `content_cache__contenttype` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_cache__encode` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_cache__data` LONGBLOB NOT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_cache__id`),
  INDEX (`content_cache__table`),
  INDEX (`content_cache__tableid`),
  INDEX (`content_cache__w`),
  INDEX (`content_cache__h`),
  INDEX (`content_cache__ttl`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_category` (
  `content_category__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_category__idparent` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `content_category__name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_category__comment` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_category__path` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_category__idtop` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_category__order` INT(11) NOT NULL,
  `content_user__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_category__private` TINYINT(4) DEFAULT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_category__id`),
  INDEX (`content_category__idparent`),
  INDEX (`content_category__idtop`),
  INDEX (`content_user__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_crontab` (
  `content_crontab__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_crontab__name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_crontab__active` TINYINT(4) DEFAULT NULL,
  `content_crontab__mhdmd` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_crontab__exec` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_crontab__lastrunat` datetime DEFAULT NULL,
  `content_crontab__laststatus` TINYINT(4) DEFAULT NULL,
  `content_crontab__lastmessage` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_crontab__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_extra` (
  `content_extra__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extra__object` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extra__name` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extra__dbname` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extra__dbtype` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extra__info` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extra__input` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extra__required` TINYINT(4) DEFAULT NULL,
  `content_extra__listview` TINYINT(4) DEFAULT NULL,
  `content_extra__relationtable` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_extra__relationname` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_extra__relationfunction` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_extra__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_extralist` (
  `content_extralist__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extra__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extralist__name` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_extralist__value` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_extralist__id`),
  UNIQUE KEY `content_extralist__value` (`content_extralist__value`,`content_extra__id`),
  INDEX (`content_extra__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_file` (
  `content_file__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_category__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_file__filename` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_file__filetype` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_file__filesize` INT(11) DEFAULT NULL,
  `content_file__filepath` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_file__filedata` LONGBLOB,
  `content_file__preview` LONGBLOB,
  `content_file__infoname` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_user__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_file__type` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_file__private` TINYINT(4) DEFAULT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_file__id`),
  INDEX (`content_category__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_file2content_filecategory` (
  `content_file2content_filecategory__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_file__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_filecategory__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_file2content_filecategory__id`),
  INDEX (`content_filecategory__id`),
  INDEX (`content_file__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_fileassoc` (
  `content_file__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_fileassoc__tableid` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_fileassoc__table` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `content_fileassoc__order` INT(11) DEFAULT NULL,
  `content_fileshowtypeitem__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_file__id`,`content_fileassoc__tableid`,`content_fileassoc__table`,`content_fileshowtypeitem__id`),
  INDEX (`content_fileassoc__tableid`),
  INDEX (`content_fileassoc__table`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_filecategory` (
  `content_filecategory__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_filecategory__idparent` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_filecategory__name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `content_filecategory__comment` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_filecategory__path` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_filecategory__idtop` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_filecategory__order` INT(11) NOT NULL,
  `content_user__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_filecategory__private` TINYINT(4) DEFAULT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_filecategory__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_fileico` (
  `content_fileico__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_fileico__name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_fileico__40x40` LONGBLOB,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_fileico__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_fileshowtype` (
  `content_fileshowtype__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_fileshowtype__name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_fileshowtype__sysname` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_fileshowtype__id`),
  INDEX (`content_fileshowtype__sysname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_fileshowtypeitem` (
  `content_fileshowtypeitem__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_fileshowtype__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_fileshowtypeitem__name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_fileshowtypeitem__sysname` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_fileshowtypeitem__default` TINYINT(4) NOT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_fileshowtypeitem__id`),
  INDEX (`content_fileshowtypeitem__sysname`),
  INDEX (`content_fileshowtype__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_hostallow` (
  `content_hostallow__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_hostallow__name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_hostallow__hosts` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_hostallow__active` TINYINT(4) DEFAULT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_hostallow__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_mailtemplate` (
  `content_mailtemplate__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_mailtemplate__sysname` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_mailtemplate__name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_mailtemplate__htmlbody` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_mailtemplate__textbody` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_mailtemplate__subject` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_mailtemplate__sender_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_mailtemplate__sender_email` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_mailtemplate__id`),
  UNIQUE KEY `content_mailtemplate__sysname` (`content_mailtemplate__sysname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_mailtemplate2content_user` (
  `content_mailtemplate2content_user__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_mailtemplate__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_user__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_mailtemplate2content_user__id`),
  INDEX (`content_mailtemplate__id`),
  INDEX (`content_user__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_news` (
  `content_news__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_news__datetime` date NOT NULL DEFAULT '0000-00-00',
  `content_news__published` TINYINT(4) DEFAULT NULL,
  `content_news__title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_news__lead` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_news__body` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_news__lang` VARCHAR(3) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_news__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_news2content_newsgroup` (
  `content_news2content_newsgroup__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_news__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_newsgroup__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_news2content_newsgroup__id`),
  INDEX (`content_news__id`),
  INDEX (`content_newsgroup__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_newsgroup` (
  `content_newsgroup__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_newsgroup__name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_newsgroup__tag` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_newsgroup__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_page` (
  `content_page__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_page__idparent` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_page__name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `content_page__info` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_page__title` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_page__url` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `content_page__idtop` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_page__order` INT(11) DEFAULT NULL,
  `content_page__description` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_page__keywords` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_page__lang` CHAR(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_page__path` BLOB,
  `content_template__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_page__params` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_page__hostallow` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_page__requiredaccess` INT(11) DEFAULT NULL,
  `content_page__menu_visible` TINYINT(4) NOT NULL DEFAULT '1',
  `content_page__sitemap_visible` TINYINT(4) NOT NULL DEFAULT '1',
  `content_page__enabled` TINYINT(4) NOT NULL DEFAULT '1',
  `content_page__redirect_url` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_page__redirect_page` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_page__id`),
  INDEX (`content_page__url`),
  INDEX (`content_template__id`),
  INDEX (`content_page__idtop`),
  INDEX (`content_page__lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `%prefix%_content_peeklist` (
  `content_peeklist__id` CHAR(36) NOT NULL,
  `content_peeklist__plugin` VARCHAR(255) NOT NULL,
  `content_peeklist__name` VARCHAR(255) NOT NULL,
  `content_peeklist__sysname` VARCHAR(255) NOT NULL,
  `content_peeklist__vtitle01` VARCHAR(255) NOT NULL,
  `content_peeklist__vtitle02` VARCHAR(255) NOT NULL,
  `content_peeklist__vtitle03` VARCHAR(255) NOT NULL,
  `content_peeklist__vtitle04` VARCHAR(255) NOT NULL,
  `content_peeklist__vtitle05` VARCHAR(255) NOT NULL,
  `content_peeklist__vtitle06` VARCHAR(255) NOT NULL,
  `content_peeklist__vtitle07` VARCHAR(255) NOT NULL,
  `content_peeklist__vtitle08` VARCHAR(255) NOT NULL,
  `content_peeklist__vtitle09` VARCHAR(255) NOT NULL,
  `content_peeklist__vtitle10` VARCHAR(255) NOT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) NOT NULL,
  PRIMARY KEY (`content_peeklist__id`),
  INDEX (`content_peeklist__plugin`),
  INDEX (`content_peeklist__sysname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `%prefix%_content_peeklistitem` (
  `content_peeklistitem__id` CHAR(36) NOT NULL,
  `content_peeklist__id` CHAR(36) NOT NULL,
  `content_peeklistitem__order` VARCHAR(255) NOT NULL,
  `content_peeklistitem__value01` VARCHAR(255) NOT NULL,
  `content_peeklistitem__value02` VARCHAR(255) NOT NULL,
  `content_peeklistitem__value03` VARCHAR(255) NOT NULL,
  `content_peeklistitem__value04` VARCHAR(255) NOT NULL,
  `content_peeklistitem__value05` VARCHAR(255) NOT NULL,
  `content_peeklistitem__value06` VARCHAR(255) NOT NULL,
  `content_peeklistitem__value07` VARCHAR(255) NOT NULL,
  `content_peeklistitem__value08` VARCHAR(255) NOT NULL,
  `content_peeklistitem__value09` VARCHAR(255) NOT NULL,
  `content_peeklistitem__value10` VARCHAR(255) NOT NULL,
  `record_create_date` int(11) DEFAULT NULL,
  `record_create_id` char(36) NOT NULL,
  `record_modify_date` int(11) DEFAULT NULL,
  `record_modify_id` char(36) NOT NULL,
  PRIMARY KEY (`content_peeklistitem__id`),
  INDEX (`content_peeklist__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_plugin` (
  `content_plugin__sysname` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_plugin__name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_plugin__version` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_plugin__path` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_plugin__enabled` TINYINT(4) DEFAULT NULL,
  UNIQUE KEY `content_plugin__sysname` (`content_plugin__sysname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_section` (
  `content_section__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_section__sysname` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_section__name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_section__title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_section__id`),
  INDEX(`content_section__sysname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_section2content_page` (
  `content_section2content_page__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_section__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_page__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_section2content_page__column` TINYINT(4) DEFAULT NULL,
  `content_section2content_page__order` TINYINT(4) DEFAULT NULL,
  `content_section2content_page__requiredaccess` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_section2content_page__data` BLOB,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_section2content_page__id`),
  INDEX (`content_section__id`),
  INDEX (`content_page__id`),
  INDEX (`content_section2content_page__column`),
  INDEX (`content_section2content_page__order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_tags` (
  `content_tags__tag` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_tags__tableid` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_tags__table` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  INDEX (`content_tags__tableid`,`content_tags__table`,`content_tags__tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_template` (
  `content_template__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_template__name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_template__srcfile` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_template__lang` CHAR(2) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_template__id`),
  INDEX (`content_template__lang`),
  INDEX (`content_template__name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_text` (
  `content_text__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_page__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_section__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_text__lang` CHAR(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_text__name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_text__title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_text__lead` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_text__body` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_text__order` INT(11) DEFAULT NULL,
  `content_text__tableid` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content_text__table` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_text__id`),
  INDEX (`content_text__lang`),
  INDEX (`content_page__id`),
  INDEX (`content_section__id`),
  INDEX (`content_text__tableid`),
  INDEX (`content_text__table`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_user2content_usergroup` (
  `content_user2content_usergroup__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_user__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_usergroup__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_user2content_usergroup__id`),
  INDEX (`content_user__id`),
  INDEX (`content_usergroup__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_user2content_access` (
  `content_user2content_access__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_access__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_user__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_user2content_access__bit` bigINT(20) NOT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_user2content_access__id`),
  INDEX (`content_access__id`),
  INDEX (`content_user__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_usergroup` (
  `content_usergroup__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_usergroup__name` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_usergroup__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_usergroup2content_access` (
  `content_usergroup2content_access__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_access__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_usergroup__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_usergroup2content_access__bit` bigINT(20) NOT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_usergroup2content_access__id`),
  INDEX (`content_access__id`),
  INDEX (`content_usergroup__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_content_user` (
  `content_user__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_user__username` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_user__password` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_user__status` TINYINT(4) DEFAULT NULL,
  `content_user__admin_hostallow` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content_user__login_correct` INT(11) DEFAULT NULL,
  `content_user__login_false` INT(11) DEFAULT NULL,
  `content_user__login_falsecount` INT(11) DEFAULT NULL,
  `content_user__security_token` CHAR(64) DEFAULT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`content_user__id`),
  UNIQUE KEY `content_user__username` (`content_user__username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_core_config` (
  `core_config__name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_config__value` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`core_config__name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_core_configadminview` (
  `core_configadminview__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminview__tag` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_user__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminview__dbname` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminview__mainkey` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminview__function` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminview__button_back` TINYINT(4) DEFAULT NULL,
  `core_configadminview__button_addnew` TINYINT(4) DEFAULT NULL,
  `core_configadminview__rowperpage` smallINT(6) DEFAULT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`core_configadminview__id`),
  INDEX (`core_configadminview__tag`,`content_user__id`),
  INDEX (`content_user__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_core_configadminviewcolumn` (
  `core_configadminviewcolumn__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminview__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminviewcolumn__idcol` smallINT(6) NOT NULL,
  `core_configadminviewcolumn__title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminviewcolumn__width` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminviewcolumn__value` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminviewcolumn__align` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_configadminviewcolumn__order` TINYINT(4) DEFAULT NULL,
  `core_configadminviewcolumn__peeklist_function` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminviewcolumn__peeklist_array` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_configadminviewcolumn__showas` TINYINT(4) DEFAULT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`core_configadminviewcolumn__id`),
  INDEX (`core_configadminview__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_core_relation` (
  `core_relation__srctable` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_relation__srcid` INT(11) DEFAULT NULL,
  `core_relation__dsttable` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_relation__dstid` INT(11) DEFAULT NULL,
  `core_relation__order` INT(11) DEFAULT NULL,
  `core_relation__type` INT(11) DEFAULT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` INT(11) DEFAULT NULL,
  UNIQUE KEY `core_relation__srctable` (`core_relation__srctable`,`core_relation__srcid`,`core_relation__dsttable`,`core_relation__dstid`,`core_relation__type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_core_session` (
  `core_session__sid` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_session__data` BLOB DEFAULT NULL,
  `core_session__remoteaddr` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_session__useragent` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_session__lastused` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`core_session__sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_core_sitemanager` (
  `core_sitemanager__module` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_sitemanager__versiondb` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_sitemanager__versionapp` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_sitemanager__dateedit` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_core_task` (
  `core_task__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_task__plugin` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_task__function` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_task__params` TEXT,
  `core_task__status` TINYINT(4) DEFAULT NULL,
  `core_task__result` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_task__execution_time` INT(11) DEFAULT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`core_task__id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_core_translation` (
  `core_translation__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `core_translation__source` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_translation__target` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_translation__module` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `core_translation__lang` VARCHAR(2) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `record_modify_date` INT(11) DEFAULT NULL,
  `record_modify_id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`core_translation__id`),
  INDEX (`core_translation__source`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_core_changed` (
  `core_changed__id` CHAR(36) NOT NULL,
  `core_changed__tableid` CHAR(36) NOT NULL,
  `core_changed__table` VARCHAR(255) DEFAULT NULL,
  `core_changed__olddata` BLOB,
  `core_deleted__state` CHAR(10) NOT NULL,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) DEFAULT NULL,
  PRIMARY KEY (`core_changed__id`),
  INDEX (`core_changed__tableid`),
  INDEX (`core_changed__table`),
  INDEX (`record_create_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `%prefix%_core_deleted` (
  `core_deleted__id` CHAR(36) NOT NULL,
  `core_deleted__tableid` CHAR(36) NOT NULL,
  `core_deleted__table` VARCHAR(255) DEFAULT NULL,
  `core_deleted__olddata` BLOB,
  `record_create_date` INT(11) DEFAULT NULL,
  `record_create_id` CHAR(36) DEFAULT NULL,
  PRIMARY KEY (`core_deleted__id`),
  INDEX (`core_deleted__tableid`),
  INDEX (`core_deleted__table`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;