ALTER TABLE `sm_content_file2content_filecategory` ADD `content_file2content_filecategory__id` CHAR( 36 ) NOT NULL FIRST;
ALTER TABLE `sm_content_file2content_filecategory` DROP PRIMARY KEY;
UPDATE `sm_content_file2content_filecategory` SET content_file2content_filecategory__id=UUID();
ALTER TABLE `sm_content_file2content_filecategory` ADD PRIMARY KEY(`content_file2content_filecategory__id`);
ALTER TABLE `sm_content_file2content_filecategory` ADD INDEX (`content_file__id`);
ALTER TABLE `sm_content_file2content_filecategory` ADD INDEX (`content_filecategory__id`);

ALTER TABLE `sm_content_mailtemplate2content_user` ADD `content_mailtemplate2content_user__id` CHAR( 36 ) NOT NULL FIRST;
ALTER TABLE `sm_content_mailtemplate2content_user` DROP PRIMARY KEY;
UPDATE `sm_content_mailtemplate2content_user` SET content_mailtemplate2content_user__id=UUID();
ALTER TABLE `sm_content_mailtemplate2content_user` ADD PRIMARY KEY(`content_mailtemplate2content_user__id`);
ALTER TABLE `sm_content_mailtemplate2content_user` ADD INDEX (`content_mailtemplate__id`);

ALTER TABLE `sm_content_news2content_newsgroup` ADD `content_news2content_newsgroup__id` CHAR( 36 ) NOT NULL FIRST;
ALTER TABLE `sm_content_news2content_newsgroup` DROP PRIMARY KEY;
UPDATE `sm_content_news2content_newsgroup` SET content_news2content_newsgroup__id=UUID();
ALTER TABLE `sm_content_news2content_newsgroup` ADD PRIMARY KEY(`content_news2content_newsgroup__id`);
ALTER TABLE `sm_content_news2content_newsgroup` ADD INDEX (`content_news__id`);
ALTER TABLE `sm_content_news2content_newsgroup` ADD INDEX (`content_newsgroup__id`);

ALTER TABLE `sm_content_section2content_page` ADD `content_section2content_page__id` CHAR( 36 ) NOT NULL FIRST;
ALTER TABLE `sm_content_section2content_page` DROP PRIMARY KEY;
UPDATE `sm_content_section2content_page` SET content_section2content_page__id=UUID();
ALTER TABLE `sm_content_section2content_page` ADD PRIMARY KEY(`content_section2content_page__id`);
ALTER TABLE `sm_content_section2content_page` ADD INDEX (`content_section__id`);
ALTER TABLE `sm_content_section2content_page` ADD INDEX (`content_page__id`);

ALTER TABLE `sm_content_user2content_usergroup` ADD `content_user2content_usergroup__id` CHAR( 36 ) NOT NULL FIRST;
ALTER TABLE `sm_content_user2content_usergroup` DROP PRIMARY KEY;
UPDATE `sm_content_user2content_usergroup` SET content_user2content_usergroup__id=UUID();
ALTER TABLE `sm_content_user2content_usergroup` ADD PRIMARY KEY(`content_user2content_usergroup__id`);
ALTER TABLE `sm_content_user2content_usergroup` ADD INDEX (`content_user__id`);
ALTER TABLE `sm_content_user2content_usergroup` ADD INDEX (`content_usergroup__id`);

RENAME TABLE `sm_content_usergroupacl` TO `sm_content_usergroup2content_access`;
ALTER TABLE `sm_content_usergroup2content_access` ENGINE = MYISAM;
ALTER TABLE `sm_content_usergroup2content_access` CHANGE `content_usergroupacl__bit` `content_usergroup2content_access__bit` BIGINT(20) NOT NULL;
ALTER TABLE `sm_content_usergroup2content_access` DROP PRIMARY KEY;
ALTER TABLE `sm_content_usergroup2content_access` ADD `content_usergroup2content_access__id` CHAR( 36 ) NOT NULL FIRST;
UPDATE `sm_content_usergroup2content_access` SET content_usergroup2content_access__id=UUID();
ALTER TABLE sm_content_usergroup2content_access DROP INDEX content_usergroupacl__id;
ALTER TABLE `sm_content_usergroup2content_access` ADD PRIMARY KEY(`content_usergroup2content_access__id`);
ALTER TABLE `sm_content_usergroup2content_access` ADD INDEX (`content_access__id`);
ALTER TABLE `sm_content_usergroup2content_access` ADD INDEX (`content_usergroup__id`);

RENAME TABLE `sm_content_useracl` TO `sm_content_user2content_access`;
ALTER TABLE `sm_content_user2content_access` ENGINE = MYISAM;
ALTER TABLE `sm_content_user2content_access` CHANGE `content_useracl__bit` `content_user2content_access__bit` BIGINT(20) NOT NULL;
ALTER TABLE `sm_content_user2content_access` DROP PRIMARY KEY;
ALTER TABLE `sm_content_user2content_access` ADD `content_user2content_access__id` CHAR( 36 ) NOT NULL FIRST;
UPDATE `sm_content_user2content_access` SET content_user2content_access__id=UUID();
ALTER TABLE `sm_content_user2content_access` ADD PRIMARY KEY(`content_user2content_access__id`);
ALTER TABLE `sm_content_user2content_access` ADD INDEX (`content_access__id`);
ALTER TABLE `sm_content_user2content_access` ADD INDEX (`content_user__id`);

