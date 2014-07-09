ALTER TABLE `bike`.`rent` ADD COLUMN `store_start` INT NULL  AFTER `time_end` , ADD COLUMN `store_finish` INT NULL  AFTER `store_start` ;
