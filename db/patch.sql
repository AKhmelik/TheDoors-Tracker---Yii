ALTER TABLE `geo_log`
	ADD COLUMN `datetime_col` DATETIME NOT NULL AFTER `user_api_id`;
delete from  geo_log where time is NULL;
update geo_log set datetime_col = FROM_UNIXTIME(`time`);