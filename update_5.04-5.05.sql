# UPDATE to 5.05

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `sm1_content_file2content_filecategory` ADD `content_file2content_filecategory__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL FIRST, ADD  INDEX (`content_file2content_filecategory__id`);
UPDATE `sm1_content_file2content_filecategory` SET `content_file2content_filecategory__id` = UUID();

ALTER TABLE `sm1_content_mailtemplate2content_user` ADD `content_mailtemplate2content_user__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL FIRST, ADD  INDEX (`content_mailtemplate2content_user__id`);
UPDATE `sm1_content_mailtemplate2content_user` SET `content_mailtemplate2content_user__id` = UUID();

ALTER TABLE `sm1_content_news2content_newsgroup` ADD `content_news2content_newsgroup__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL FIRST, ADD  INDEX (`content_news2content_newsgroup__id`);
UPDATE `sm1_content_news2content_newsgroup` SET `content_news2content_newsgroup__id` = UUID();

ALTER TABLE `sm1_content_section2content_page` ADD `content_section2content_page__id` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL FIRST, ADD  INDEX (`content_section2content_page__id`);
UPDATE `sm1_content_section2content_page` SET `content_section2content_page__id` = UUID();

ALTER TABLE `sm1_content_user2content_usergroup` ADD content_user2content_usergroup__id CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL FIRST, ADD  INDEX (`content_user2content_usergroup__id`);
UPDATE `sm1_content_user2content_usergroup` SET `content_user2content_usergroup__id` = UUID();

ALTER TABLE `sm1_content_useracl` ADD content_useracl__id CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL FIRST, ADD  INDEX (`content_useracl__id`);
UPDATE `sm1_content_useracl` SET `content_useracl__id` = UUID();

ALTER TABLE `sm1_content_usergroupacl` ADD content_usergroupacl__id CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL FIRST, ADD  INDEX (`content_usergroupacl__id`);
UPDATE `sm1_content_usergroupacl` SET `content_usergroupacl__id` = UUID();
