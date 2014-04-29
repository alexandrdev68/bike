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
