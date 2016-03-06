CREATE  TABLE `bike`.`action` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `klient_id` INT NOT NULL ,
  `sms_code` VARCHAR(45) NOT NULL ,
  `time_start` INT NOT NULL ,
  `amount_summ` INT NULL ,
  `renttime_summ` INT NULL ,
  `properties` TEXT NULL ,
  PRIMARY KEY (`id`) )
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'таблица для сохранения данных пользователей, участвующих в а' /* comment truncated */;

CREATE TABLE `bike`.`sms_log` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `sms_id` VARCHAR(128) NULL DEFAULT '',
  `sms_text` VARCHAR(2048) NULL DEFAULT '',
  `sms_recieve` INT NULL DEFAULT 0,
  `sms_error` VARCHAR(256) NULL DEFAULT '',
  `phone` VARCHAR(45) NULL DEFAULT '',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

ALTER TABLE `bike`.`sms_log` 
ADD COLUMN `sms_time` INT NULL DEFAULT 0 AFTER `phone`,
ADD COLUMN `sms_status` INT NULL AFTER `sms_time`;

ALTER TABLE `bike`.`sms_log` 
CHANGE COLUMN `sms_status` `sms_status` INT(11) NULL DEFAULT NULL COMMENT '1 - успешно отправлено, нет подтверждения доставки\n0 - доставка подтверждена\n401 - ошибка при отправке' ;

ALTER TABLE `bike`.`rent` ADD COLUMN `store_start` INT NULL  AFTER `time_end` , ADD COLUMN `store_finish` INT NULL  AFTER `store_start`;