-- hcm.facility_floating_target definition

CREATE TABLE `facility_floating_target` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `Partner` varchar(50) NOT NULL,
  `county_id` int NOT NULL,
  `county` varchar(50) NOT NULL,
  `facility_id` int NOT NULL,
  `facility` varchar(150) NOT NULL,
  `month` int NOT NULL,
  `financial_year` int NOT NULL,
  `target` int DEFAULT NULL,
  `Achieved` int DEFAULT NULL,
  `deficit` int DEFAULT NULL,
  `floating_target` int DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ufft` (`partner_id`,`county_id`,`month`,`financial_year`,`facility_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2854 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
