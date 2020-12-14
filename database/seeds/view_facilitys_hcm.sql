
CREATE OR REPLACE VIEW  `view_facilitys` AS
 (select `facilitys`.`id`,`facilitys`.`originalID`,`facilitys`.`longitude`,`facilitys`.`latitude`,
 	`facilitys`.`DHIScode`,`facilitys`.`facilitycode`,`facilitys`.`name`,`facilitys`.`new_name`,`facilitys`.`burden`,
 	`facilitys`.`totalartmar`,`facilitys`.`totalartsep17`,`facilitys`.`asofdate`,`facilitys`.`totalartsep15` AS `totalartsep15`,
 	`facilitys`.`smsprinter`,`facilitys`.`Flag`,

 	`facilitys`.`is_viremia`, `facilitys`.`is_dsd`, `facilitys`.`is_otz`, `facilitys`.`is_men_clinic`, `facilitys`.`is_pns`, `facilitys`.`is_surge`,

 	`facilitys`.`ward_id`, `wards`.`name` AS `wardname`,`wards`.`WardDHISCode`,`wards`.`WardMFLCode`, 

 	`facilitys`.`district`, `facilitys`.`subcounty_id`,`districts`.`name` AS `subcounty`,
 	`districts`.`SubCountyDHISCode`,`districts`.`SubCountyMFLCode`,

 	`facilitys`.`partner`,`partners`.`name` AS `partnername`,`facilitys`.`partner2`,`partners`.`mech_id`,

 	`partners`.`funding_agency_id`, `funding_agencies`.`name` AS `funding_agency`,

 	`districts`.`county`,`countys`.`name` AS `countyname`,`countys`.`CountyDHISCode`,`countys`.`CountyMFLCode`,
 	`districts`.`province` AS `province`
 	
 	FROM `facilitys` 
 	LEFT JOIN `partners` on `facilitys`.`partner` = `partners`.`id`
 	LEFT JOIN `funding_agencies` on `partners`.`funding_agency_id` = `funding_agencies`.`id`
 	LEFT JOIN `districts` on `facilitys`.`district` = `districts`.`id`
 	LEFT JOIN `wards` on `facilitys`.`ward_id` = `wards`.`id`
 	LEFT JOIN `countys` on `districts`.`county` = `countys`.`id`
 	WHERE `facilitys`.`Flag` = 1);



 CREATE OR REPLACE VIEW  `p_early_indicators_view` AS
 (select  `p`.`*`, `partners`.`name` AS `partnername`, 
 	`countys`.`name` AS `countyname`,`countys`.`CountyDHISCode`,`countys`.`CountyMFLCode`,
 	`partners`.`funding_agency_id`, `funding_agencies`.`name` AS `funding_agency`
 	
 	FROM `p_early_indicators` `p` 
 	LEFT JOIN `partners` on `p`.`partner` = `partners`.`id`
 	LEFT JOIN `funding_agencies` on `partners`.`funding_agency_id` = `funding_agencies`.`id`
 	LEFT JOIN `countys` on `p`.`county` = `countys`.`id`);



CREATE OR REPLACE VIEW  `view_facilities` AS
 (select `facilitys`.`id`,`facilitys`.`originalID`,`facilitys`.`longitude`,`facilitys`.`latitude`,
 	`facilitys`.`DHIScode`,`facilitys`.`facilitycode`,`facilitys`.`facility_uid`,
 	`facilitys`.`name`,`facilitys`.`new_name`,`facilitys`.`Flag`,

 	`facilitys`.`is_viremia`, `facilitys`.`is_dsd`, `facilitys`.`is_otz`, `facilitys`.`is_men_clinic`, `facilitys`.`is_pns`, `facilitys`.`is_surge`,

 	`facilitys`.`ward_id`, `wards`.`name` AS `wardname`,`wards`.`WardDHISCode`,`wards`.`WardMFLCode`, 

 	`facilitys`.`district`, `facilitys`.`subcounty_id`,`districts`.`name` AS `subcounty`,
 	`districts`.`SubCountyDHISCode`,`districts`.`SubCountyMFLCode`,

 	`supported_facilities`.`partner_id` AS `partner`, `partners`.`name` AS `partnername`,`facilitys`.`partner2`,`partners`.`mech_id`,
 	`supported_facilities`.`start_of_support`, `supported_facilities`.`end_of_support`,

 	`partners`.`funding_agency_id`, `funding_agencies`.`name` AS `funding_agency`,`partners`.`country`,

 	`districts`.`county`,`countys`.`name` AS `countyname`,`countys`.`CountyDHISCode`,`countys`.`CountyMFLCode`,
 	`districts`.`province` AS `province`
 	
 	FROM `facilitys` 
 	LEFT JOIN `supported_facilities` on `facilitys`.`id` = `supported_facilities`.`facility_id`
 	LEFT JOIN `partners` on `supported_facilities`.`partner_id` = `partners`.`id`
 	LEFT JOIN `funding_agencies` on `partners`.`funding_agency_id` = `funding_agencies`.`id`
 	LEFT JOIN `districts` on `facilitys`.`district` = `districts`.`id`
 	LEFT JOIN `wards` on `facilitys`.`ward_id` = `wards`.`id`
 	LEFT JOIN `countys` on `districts`.`county` = `countys`.`id`
 	WHERE `facilitys`.`Flag` = 1);
 
 