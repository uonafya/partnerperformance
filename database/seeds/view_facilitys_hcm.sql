
CREATE OR REPLACE VIEW  `view_facilitys` AS
 (select `facilitys`.`id`,`facilitys`.`originalID`,`facilitys`.`longitude`,`facilitys`.`latitude`,
 	`facilitys`.`DHIScode`,`facilitys`.`facilitycode`,`facilitys`.`name`,`facilitys`.`burden`,
 	`facilitys`.`totalartmar` AS `totalartmar`,`facilitys`.`totalartsep17` AS `totalartsep17`,`facilitys`.`asofdate` AS `asofdate`,`facilitys`.`totalartsep15` AS `totalartsep15`,
 	`facilitys`.`smsprinter`,`facilitys`.`Flag`,`facilitys`.`ART`,`facilitys`.`district`, `facilitys`.`subcounty_id`,
 	`districts`.`name` AS `subcounty`,`facilitys`.`partner` AS `partner`,`partners`.`name` AS `partnername`,
 	`facilitys`.`partner2` AS `partner2`,`districts`.`county` AS `county`,`countys`.`name` AS `countyname`,`districts`.`province` AS `province`
 	FROM `facilitys` 
 	LEFT JOIN `partners` on `facilitys`.`partner` = `partners`.`id`
 	LEFT JOIN `districts` on `facilitys`.`subcounty_id` = `districts`.`id`
 	LEFT JOIN `wards` on `facilitys`.`ward_id` = `wards`.`id`
 	LEFT JOIN `countys` on `districts`.`county` = `countys`.`id`
 	WHERE `facilitys`.`Flag` = 1);
 