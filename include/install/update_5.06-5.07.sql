ALTER TABLE `sm_core_task`
CHANGE `core_task___plugin` `core_task__plugin` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `core_task___function` `core_task__function` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `core_task___params` `core_task__params` TEXT NULL DEFAULT NULL ,
CHANGE `core_task___status` `core_task__status` TINYINT( 4 ) NULL DEFAULT NULL ,
CHANGE `core_task___result` `core_task__result` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL 

ALTER TABLE `sm_core_task` ADD `core_task__execution_time` INT NOT NULL AFTER `core_task__result`;

ALTER TABLE `sm_core_changed`
CHANGE `core_deleted__state` `core_changed__state` CHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
