DROP PROCEDURE IF EXISTS `proc_get_duplicate_multiple`;
DELIMITER //
CREATE PROCEDURE `proc_get_duplicate_multiple`
(IN old_table VARCHAR(100), new_table VARCHAR(100), select_query VARCHAR(255), new_column VARCHAR(100), divisions_query VARCHAR(150), date_query VARCHAR(100), groupby_query VARCHAR(100))
BEGIN

  SET @QUERY = CONCAT("SELECT ", select_query, " FROM ", old_table, " d JOIN view_facilitys ON d.facility=view_facilitys.id ");
  SET @QUERY = CONCAT(@QUERY, "WHERE `year`=", filter_year, " AND `month`=", filter_month, " AND `facility` IN ");
  SET @QUERY = CONCAT(@QUERY, "(SELECT DISTINCT `facility` FROM ", new_table, " dd JOIN view_facilitys ff ON dd.facility=ff.id
    WHERE `year`=", filter_year, " AND `month`=", filter_month, " AND ", divisions_query, " AND ", new_column, " > 0) ");
  SET @QUERY = CONCAT(@QUERY, groupby_query);

    PREPARE stmt FROM @QUERY;
    EXECUTE stmt;
    
END //
DELIMITER ;
