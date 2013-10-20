-- Adminer 3.6.2 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET @adminer_alter = '';

CREATE TABLE IF NOT EXISTS `productstatus` (
  `ProductStatusID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductStatusName` varchar(100) NOT NULL,
  `ProductStatusDescription` varchar(255) NOT NULL,
  PRIMARY KEY (`ProductStatusID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `ProductStatusID` int(11) NOT NULL auto_increment FIRST, ADD `ProductStatusName` varchar(100) COLLATE latin1_swedish_ci NOT NULL AFTER `ProductStatusID`, ADD `ProductStatusDescription` varchar(255) COLLATE latin1_swedish_ci NOT NULL AFTER `ProductStatusName`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'productstatus' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'ProductStatusID' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `ProductStatusID` int(11) NOT NULL auto_increment FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = 'auto_increment' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `ProductStatusID` int(11) NOT NULL auto_increment FIRST'));
				WHEN 'ProductStatusName' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `ProductStatusName` varchar(100) COLLATE latin1_swedish_ci NOT NULL AFTER `ProductStatusID`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> 'latin1_swedish_ci' AND _column_type = 'varchar(100)' AND _extra = '' AND _column_comment = '' AND after = 'ProductStatusID'
					, '', ', MODIFY `ProductStatusName` varchar(100) COLLATE latin1_swedish_ci NOT NULL AFTER `ProductStatusID`'));
				WHEN 'ProductStatusDescription' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `ProductStatusDescription` varchar(255) COLLATE latin1_swedish_ci NOT NULL AFTER `ProductStatusName`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> 'latin1_swedish_ci' AND _column_type = 'varchar(255)' AND _extra = '' AND _column_comment = '' AND after = 'ProductStatusName'
					, '', ', MODIFY `ProductStatusDescription` varchar(255) COLLATE latin1_swedish_ci NOT NULL AFTER `ProductStatusName`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `productstatus`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


SELECT @adminer_alter;
-- 2013-10-20 23:22:09
