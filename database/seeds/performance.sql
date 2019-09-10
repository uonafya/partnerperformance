-- MySQL dump 10.13  Distrib 5.7.20, for Linux (x86_64)
--
-- Host: 10.230.50.11    Database: hcm


DROP TABLE IF EXISTS `age_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `age_categories` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `age_category` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `countys`
--

DROP TABLE IF EXISTS `countys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countys` (
  `id` tinyint(3) unsigned NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `CountyDHISCode` varchar(30) DEFAULT NULL,
  `CountyMFLCode` varchar(30) DEFAULT NULL,
  `rawcode` varchar(20) DEFAULT NULL,
  `CountyCoordinates` varchar(3070) DEFAULT NULL,
  `pmtctneed1617` int(50) DEFAULT NULL,
  `letter` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `d_blood_safety`
--

DROP TABLE IF EXISTS `d_blood_safety`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_blood_safety` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `blood_units_screened_for_ttis` int(10) DEFAULT NULL,
  `donated_blood_units` int(10) DEFAULT NULL,
  `blood_units_reactive_to_hiv` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `d_care_and_treatment`
--

DROP TABLE IF EXISTS `d_care_and_treatment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_care_and_treatment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `female_15_years_and_older_screened_for_tb` int(10) DEFAULT NULL,
  `male_under_15yrs_starting_on_art` int(10) DEFAULT NULL,
  `pregnant_women_starting_on_art` int(10) DEFAULT NULL,
  `female_under_15_years_screened_for_tb` int(10) DEFAULT NULL,
  `total_enrolled_in_care` int(10) DEFAULT NULL,
  `male_below_15_years_screened_for_tb` int(10) DEFAULT NULL,
  `currently_on_art_-_male_below_15_years` int(10) DEFAULT NULL,
  `screened_for_sti’s` int(10) DEFAULT NULL,
  `on_alternative_1st_line_at_12_months_survival_and_retention_` int(10) DEFAULT NULL,
  `isoniazid_preventive_therapy_male_<_15_yrs` int(10) DEFAULT NULL,
  `total_screened_for_tb` int(10) DEFAULT NULL,
  `male_under_15yrs_currently_in_care` int(10) DEFAULT NULL,
  `female_under_15yrs_ever_on_art` int(10) DEFAULT NULL,
  `on_ctx_15_yrs_and_older_female` int(10) DEFAULT NULL,
  `male_15_years_and_older_screened_for_tb` int(10) DEFAULT NULL,
  `modern_contraceptive_methods` int(10) DEFAULT NULL,
  `under_1yr_revisit_on_art` int(10) DEFAULT NULL,
  `male_under_15yrs_revisit_on_art` int(10) DEFAULT NULL,
  `male_above_15yrs_currently_in_care` int(10) DEFAULT NULL,
  `screened_for_cervical_cancer_(females_18_years_and_older)` int(10) DEFAULT NULL,
  `currently_provided_with_a_minimum_package_of_pwp_services` int(10) DEFAULT NULL,
  `on_original_1st_line_at_12_months_survival_and_retention_on_` int(10) DEFAULT NULL,
  `female_under_15yrs_enrolled_in_care` int(10) DEFAULT NULL,
  `male_above_15yrs_revisit_on_art` int(10) DEFAULT NULL,
  `female_under_15yrs_starting_on_art` int(10) DEFAULT NULL,
  `total_revisit_on_art` int(10) DEFAULT NULL,
  `on_ctx_below_15_yrs_female` int(10) DEFAULT NULL,
  `total_hiv_care_visit` int(10) DEFAULT NULL,
  `female_above_15yrs_ever_on_art` int(10) DEFAULT NULL,
  `male_under_15yrs_ever_on_art` int(10) DEFAULT NULL,
  `under_1yr_currently_in_care` int(10) DEFAULT NULL,
  `total_on_therapy_at_12_months` int(10) DEFAULT NULL,
  `under_1yr_enrolled_in_care` int(10) DEFAULT NULL,
  `hiv_exposed_infant_(eligible_for_ctx_2_months)` int(10) DEFAULT NULL,
  `hiv_care_visit-_unscheduled` int(10) DEFAULT NULL,
  `female_above_15yrs_enrolled_in_care` int(10) DEFAULT NULL,
  `male_above_15yrs_ever_on_art` int(10) DEFAULT NULL,
  `under_1yr_starting_on_art` int(10) DEFAULT NULL,
  `isoniazid_preventive_therapy_female_>_15_yrs` int(10) DEFAULT NULL,
  `on_ctx_below_15_yrs_male` int(10) DEFAULT NULL,
  `female_under_15yrs_revisit_on_art` int(10) DEFAULT NULL,
  `linked_to_community_based_services5` int(10) DEFAULT NULL,
  `disclosed_their_hiv_status_to_sexual_partners` int(10) DEFAULT NULL,
  `on_ctx_15_y_and_older_male` int(10) DEFAULT NULL,
  `currently_on_art_-_male_above_15_years` int(10) DEFAULT NULL,
  `provided_with_adherence_counselling` int(10) DEFAULT NULL,
  `female_above_15yrs_revisit_on_art` int(10) DEFAULT NULL,
  `currently_on_art_-_below_1_year` int(10) DEFAULT NULL,
  `isoniazid_preventive_therapy_male_>_15_yrs` int(10) DEFAULT NULL,
  `hiv_exposed_infant_(within_2_months)_on_cotrimoxazole_prophy` int(10) DEFAULT NULL,
  `male_under_15yrs_enrolled_in_care` int(10) DEFAULT NULL,
  `hiv_care_visits_females_(18_years_and_older)` int(10) DEFAULT NULL,
  `linked_to_community_based_services2` int(10) DEFAULT NULL,
  `female_above_15yrs_starting_on_art` int(10) DEFAULT NULL,
  `total_ever_on_art` int(10) DEFAULT NULL,
  `female_under_15yrs_currently_in_care` int(10) DEFAULT NULL,
  `isoniazid_preventive_therapy_female_<_15_yrs` int(10) DEFAULT NULL,
  `provided_with_condoms` int(10) DEFAULT NULL,
  `total_starting_on_art` int(10) DEFAULT NULL,
  `knowledge_of_sexual_partners_hiv_status` int(10) DEFAULT NULL,
  `on_2nd_line_(or_higher)_at_12_months_survival_and_retention_` int(10) DEFAULT NULL,
  `currently_on_art_-_female_below_15_years` int(10) DEFAULT NULL,
  `visited_home_by_a_health_care_provider/hiv_clinic_peer_educa` int(10) DEFAULT NULL,
  `hiv_currently_in_care_-_above_15yrs_female` int(10) DEFAULT NULL,
  `currently_on_art_-_female_above_15_years` int(10) DEFAULT NULL,
  `hiv_care_visit_scheduled` int(10) DEFAULT NULL,
  `art_net_cohort_at_12_months_survival_and_retention_on_art` int(10) DEFAULT NULL,
  `total_currently_on_art` int(10) DEFAULT NULL,
  `male_above_15yrs_starting_on_art` int(10) DEFAULT NULL,
  `male_above_15yrs_&_older_enrolled_in_care` int(10) DEFAULT NULL,
  `total_on_ctx` int(10) DEFAULT NULL,
  `tb_patient_starting_on_art` int(10) DEFAULT NULL,
  `hiv_currently_in_care_-_total` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `d_dispensing`
--

DROP TABLE IF EXISTS `d_dispensing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_dispensing` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `age_category_id` tinyint(3) unsigned DEFAULT '0',
  `gender_id` tinyint(3) unsigned DEFAULT '0',
  `dispensed_one` smallint(5) unsigned DEFAULT '0',
  `dispensed_two` smallint(5) unsigned DEFAULT '0',
  `dispensed_three` smallint(5) unsigned DEFAULT '0',
  `dispensed_four` smallint(5) unsigned DEFAULT '0',
  `dispensed_five` smallint(5) unsigned DEFAULT '0',
  `dispensed_six` smallint(5) unsigned DEFAULT '0',
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `age_category_id` (`age_category_id`),
  KEY `gender_id` (`gender_id`),
  KEY `facility` (`facility`),
  KEY `period_id` (`period_id`),
  KEY `identifier` (`facility`,`period_id`,`age_category_id`),
  KEY `identifier_two` (`facility`,`period_id`,`gender_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `d_hiv_and_tb_treatment`
--

DROP TABLE IF EXISTS `d_hiv_and_tb_treatment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_hiv_and_tb_treatment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `start_art_1-9_hv03-017` int(10) DEFAULT NULL,
  `enrolled_25pos_(f)_hv03-010` int(10) DEFAULT NULL,
  `tb_new_hiv_positive_hv03-080` int(10) DEFAULT NULL,
  `nutrition_assess_<15_hv03-067` int(10) DEFAULT NULL,
  `start_art_15-19_(f)_hv03-021` int(10) DEFAULT NULL,
  `screen_for_tb_1-9_hv03-052` int(10) DEFAULT NULL,
  `start_art_keypop_hv03-027` int(10) DEFAULT NULL,
  `on_art_20-24(m)_hv03-034` int(10) DEFAULT NULL,
  `enrolled_total_(sum_hv03-001_to_hv03-010)_hv03-011` int(10) DEFAULT NULL,
  `start_art_20-24_(f)_hv03-023` int(10) DEFAULT NULL,
  `tb_start_haart_hv03-083` int(10) DEFAULT NULL,
  `malnourished_total_hv03-072` int(10) DEFAULT NULL,
  `fbp_provided_total_hv03-075` int(10) DEFAULT NULL,
  `start_art_10-14(m)_hv03-018` int(10) DEFAULT NULL,
  `community_art_current_(f)_hv03-086` int(10) DEFAULT NULL,
  `enrolled_<1_hv03-001` int(10) DEFAULT NULL,
  `start_art_25pos(m)_hv03-024` int(10) DEFAULT NULL,
  `start_art_10-14_(f)_hv03-019` int(10) DEFAULT NULL,
  `on_art_<1_hv03-028` int(10) DEFAULT NULL,
  `tb_cases_new_hv03-076` int(10) DEFAULT NULL,
  `start_ipt_<1_hv03-059` int(10) DEFAULT NULL,
  `screen_for_tb_total_hv03-057` int(10) DEFAULT NULL,
  `enrolled_20-24_(f)_hv03-008` int(10) DEFAULT NULL,
  `completed_ipt_12months_hv03-066` int(10) DEFAULT NULL,
  `enrolled_20-24(m)_hv03-007` int(10) DEFAULT NULL,
  `malnourished_<15_hv03-070` int(10) DEFAULT NULL,
  `on_art_25pos(m)_hv03-036` int(10) DEFAULT NULL,
  `malnourished_15pos_hv03-071` int(10) DEFAULT NULL,
  `nutrition_assess_15pos_hv03-068` int(10) DEFAULT NULL,
  `start_ipt_1-9_hv03-060` int(10) DEFAULT NULL,
  `screen_for_tb_25pos_hv03-056` int(10) DEFAULT NULL,
  `on_art_12mths_hv03-040` int(10) DEFAULT NULL,
  `enrolled_in_care_keypop_hv03-012` int(10) DEFAULT NULL,
  `on_modern_fp_f18pos_hv03-089` int(10) DEFAULT NULL,
  `screen_cacx_new_f18pos_hv03-087` int(10) DEFAULT NULL,
  `on_art_20-24_(f)_hv03-035` int(10) DEFAULT NULL,
  `viral_load_<1000_12mths_hv03-042` int(10) DEFAULT NULL,
  `on_ctx/dds_<1_hv03-044` int(10) DEFAULT NULL,
  `start_ipt_15-19_hv03-062` int(10) DEFAULT NULL,
  `start_ipt_25pos_hv03-064` int(10) DEFAULT NULL,
  `on_ctx/dds_25pos_hv03-049` int(10) DEFAULT NULL,
  `community_art_current(m)_hv03-085` int(10) DEFAULT NULL,
  `on_art_15-19_(f)_hv03-033` int(10) DEFAULT NULL,
  `start_art_20-24(m)_hv03-022` int(10) DEFAULT NULL,
  `start_ipt_total_hv03-065` int(10) DEFAULT NULL,
  `fbp_provided_<15_hv03-073` int(10) DEFAULT NULL,
  `enrolled_1-9_hv03-002` int(10) DEFAULT NULL,
  `on_ctx/dds_1-9_hv03-045` int(10) DEFAULT NULL,
  `start_ipt_10-14_hv03-061` int(10) DEFAULT NULL,
  `enrolled_15-19(m)_hv03-005` int(10) DEFAULT NULL,
  `start_art_25pos_(f)_hv03-025` int(10) DEFAULT NULL,
  `on_art_10-14(m)_hv03-030` int(10) DEFAULT NULL,
  `enrolled_15-19_(f)_hv03-006` int(10) DEFAULT NULL,
  `on_ctx/dds_15-19_hv03-047` int(10) DEFAULT NULL,
  `tb_cases_total_hivpos_(hv03-077pos080)_hv03-081` int(10) DEFAULT NULL,
  `start_ipt_20-24_hv03-063` int(10) DEFAULT NULL,
  `fbp_provided_15pos_hv03-074` int(10) DEFAULT NULL,
  `start_art_15-19(m)_hv03-020` int(10) DEFAULT NULL,
  `enrolled_10-14_(f)_hv03-004` int(10) DEFAULT NULL,
  `viral_load_result_12mths_hv03-043` int(10) DEFAULT NULL,
  `nutrition_assess_total_hv03-069` int(10) DEFAULT NULL,
  `tb_known_status_hv03-079` int(10) DEFAULT NULL,
  `screen_for_tb_10-14_hv03-053` int(10) DEFAULT NULL,
  `on_art_25pos_(f)_hv03-037` int(10) DEFAULT NULL,
  `tb_cases_known_positive(kps)_hv03-077` int(10) DEFAULT NULL,
  `on_art_15-19(m)_hv03-032` int(10) DEFAULT NULL,
  `on_art_10-14_(f)_hv03-031` int(10) DEFAULT NULL,
  `in_pre_art_0-14_hv03-013` int(10) DEFAULT NULL,
  `enrolled_10-14(m)_hv03-003` int(10) DEFAULT NULL,
  `on_art_1-9_hv03-029` int(10) DEFAULT NULL,
  `screen_for_tb_20-24_hv03-055` int(10) DEFAULT NULL,
  `on_ctx/dds_20-24_hv03-048` int(10) DEFAULT NULL,
  `tb_already_on_haart_hv03-082` int(10) DEFAULT NULL,
  `in_pre_art_15pos_hv03-014` int(10) DEFAULT NULL,
  `on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038` int(10) DEFAULT NULL,
  `screen_for_tb_15-19_hv03-054` int(10) DEFAULT NULL,
  `on_ctx/dds_10-14_hv03-046` int(10) DEFAULT NULL,
  `on_art_keypop_(hiv3-038_plus_hiv3-050)_hv03-039` int(10) DEFAULT NULL,
  `tb_cases_tested_hiv_hv03-078` int(10) DEFAULT NULL,
  `clinical_visits_f18pos_hv03-088` int(10) DEFAULT NULL,
  `tb_total_on_haart(hv03-082pos083)_hv03-084` int(10) DEFAULT NULL,
  `presumed_tb_total_hv03-058` int(10) DEFAULT NULL,
  `in_pre_art_total(hv03-13poshv03-14_hv03-015` int(10) DEFAULT NULL,
  `screen_for_tb_<1_hv03-051` int(10) DEFAULT NULL,
  `on_ctx/dds_total_hv03-050` int(10) DEFAULT NULL,
  `net_cohort_12mths_hv03-041` int(10) DEFAULT NULL,
  `start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026` int(10) DEFAULT NULL,
  `start_art_<1_hv03-016` int(10) DEFAULT NULL,
  `enrolled_25pos(m)_hv03-009` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `d_hiv_counselling_and_testing`
--

DROP TABLE IF EXISTS `d_hiv_counselling_and_testing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_hiv_counselling_and_testing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `discordant_couples_receiving_results` int(10) DEFAULT NULL,
  `male_15-24yrs_receiving_hiv_pos_results` int(10) DEFAULT NULL,
  `concordant_couples_receiving_results` int(10) DEFAULT NULL,
  `first_testing_hiv` int(10) DEFAULT NULL,
  `total_received_hivpos_results` int(10) DEFAULT NULL,
  `female_above_25yrs_receiving_hiv_pos_results` int(10) DEFAULT NULL,
  `couples_testing` int(10) DEFAULT NULL,
  `male_under_15yrs_receiving_hiv_pos_results` int(10) DEFAULT NULL,
  `outreach_testing_hiv` int(10) DEFAULT NULL,
  `female_under_15yrs_receiving_hiv_pos_results` int(10) DEFAULT NULL,
  `male_above_25yrs_receiving_hiv_pos_results` int(10) DEFAULT NULL,
  `repeat_testing_hiv` int(10) DEFAULT NULL,
  `total_tested_hiv` int(10) DEFAULT NULL,
  `static_testing_hiv_(health_facility)` int(10) DEFAULT NULL,
  `female_15-24yrs_receiving_hiv_pos_results` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=582097 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `d_hiv_testing_and_prevention_services`
--

DROP TABLE IF EXISTS `d_hiv_testing_and_prevention_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_hiv_testing_and_prevention_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `tested_25pos_(m)_hv01-08` int(10) DEFAULT NULL,
  `assessed_25-29_yrs(m)_hv01-41` int(10) DEFAULT NULL,
  `assesed_15-19_yrs(m)_hv01-37` int(10) DEFAULT NULL,
  `15-24_(m)_hv01-46` int(10) DEFAULT NULL,
  `tested_new_hv01-13` int(10) DEFAULT NULL,
  `linked_10-14_hv01-31` int(10) DEFAULT NULL,
  `tested_facility_hv01-11` int(10) DEFAULT NULL,
  `tested_1-9_hv01-01` int(10) DEFAULT NULL,
  `positive_keypop_hv01-29` int(10) DEFAULT NULL,
  `total_assessded_for_hiv_risk_(_hv01-39_-_hv01-45)_hv01-45` int(10) DEFAULT NULL,
  `tested_15-19(f)_hv01-05` int(10) DEFAULT NULL,
  `total_tested_positive_(3_months_ago)_hv01-36` int(10) DEFAULT NULL,
  `tested_10-14_(m)_hv01-02` int(10) DEFAULT NULL,
  `tested_10-14(f)_hv01-03` int(10) DEFAULT NULL,
  `assessed_20-24_yrs(m)_hv01-39` int(10) DEFAULT NULL,
  `25pos_(m)_hv01-48` int(10) DEFAULT NULL,
  `tested_community_hv01-12` int(10) DEFAULT NULL,
  `assesed_15-19_yrs(f)_hv01-38` int(10) DEFAULT NULL,
  `tested_25pos_(f)_hv01-09` int(10) DEFAULT NULL,
  `tested_20-24(f)_hv01-07` int(10) DEFAULT NULL,
  `positive_1-9_hv01-17` int(10) DEFAULT NULL,
  `linked_1-9_yrs_hv01-30` int(10) DEFAULT NULL,
  `15-24_(f)_hv01-47` int(10) DEFAULT NULL,
  `positive_25pos(f)_hv01-25` int(10) DEFAULT NULL,
  `positive_10-14(m)_hv01-18` int(10) DEFAULT NULL,
  `positive_20-24(m)_hv01-22` int(10) DEFAULT NULL,
  `tested_20-24(m)_hv01-06` int(10) DEFAULT NULL,
  `positive_25pos(m)_hv01-24` int(10) DEFAULT NULL,
  `tested_keypop_hv01-16` int(10) DEFAULT NULL,
  `linked_total_hv01-35` int(10) DEFAULT NULL,
  `25pos_(f)_hv01-49` int(10) DEFAULT NULL,
  `total_hv01-50` int(10) DEFAULT NULL,
  `positive_total_(sum_hv01-18_to_hv01-27)_hv01-26` int(10) DEFAULT NULL,
  `positive_15-19(f)_hv01-21` int(10) DEFAULT NULL,
  `assessed_25-29_yrs(f)_hv01-42` int(10) DEFAULT NULL,
  `positive_15-19(m)_hv01-20` int(10) DEFAULT NULL,
  `positive_10-14(f)_hv01-19` int(10) DEFAULT NULL,
  `discordant_hv01-28` int(10) DEFAULT NULL,
  `assessed_20-24_yrs(f)_hv01-40` int(10) DEFAULT NULL,
  `linked_25pos_hv01-34` int(10) DEFAULT NULL,
  `tested_total_(sum_hv01-01_to_hv01-10)_hv01-10` int(10) DEFAULT NULL,
  `linked_15-19_hv01-32` int(10) DEFAULT NULL,
  `tested_15-19_(m)_hv01-04` int(10) DEFAULT NULL,
  `assessed_30_yrs_&_older(f)_hv01-44` int(10) DEFAULT NULL,
  `negative_total_hv01-27` int(10) DEFAULT NULL,
  `assessed_30_yrs_&_older(m)_hv01-43` int(10) DEFAULT NULL,
  `tested_repeat_hv01-14` int(10) DEFAULT NULL,
  `linked_20-24_hv01-33` int(10) DEFAULT NULL,
  `tested_couples_hv01-15` int(10) DEFAULT NULL,
  `positive_20-24(f)_hv01-23` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=582097 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `d_key_populations_monthly_summary`
--

DROP TABLE IF EXISTS `d_key_populations_monthly_summary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_key_populations_monthly_summary` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `no_diagnosed` int(10) DEFAULT NULL,
  `treated_hcv` int(10) DEFAULT NULL,
  `started_art_20_-_24(onsite)` int(10) DEFAULT NULL,
  `linked_25_-_29` int(10) DEFAULT NULL,
  `active_20_-_24` int(10) DEFAULT NULL,
  `no_of_clients_discontinued_voluntary` int(10) DEFAULT NULL,
  `started_art_20_-_24(offsite)` int(10) DEFAULT NULL,
  `no_mat_clients_on_tb_treatment` int(10) DEFAULT NULL,
  `active_15_-_19` int(10) DEFAULT NULL,
  `positive_30pos` int(10) DEFAULT NULL,
  `negative_hbv_vaccinated` int(10) DEFAULT NULL,
  `turning_hiv_positive_while_on_prep` int(10) DEFAULT NULL,
  `treated_hbv` int(10) DEFAULT NULL,
  `tested_25_-_29` int(10) DEFAULT NULL,
  `on_art_at_12mnths` int(10) DEFAULT NULL,
  `viral_load_<1000_12mths(onsite)` int(10) DEFAULT NULL,
  `number_receiving_needles_&_syringes_per_need` int(10) DEFAULT NULL,
  `started_art_30pos(offsite)` int(10) DEFAULT NULL,
  `receiving_clinical_services` int(10) DEFAULT NULL,
  `currently_on_art_20_-_24(offsite)` int(10) DEFAULT NULL,
  `no_of_clients_on_>_120mg_dose` int(10) DEFAULT NULL,
  `receiving_violence_support` int(10) DEFAULT NULL,
  `initiated_prep` int(10) DEFAULT NULL,
  `number_receiving_pep_<72hrs` int(10) DEFAULT NULL,
  `no_of_clients_on_60-120mg_dose` int(10) DEFAULT NULL,
  `no_mat_clients_on_2nd_line_art` int(10) DEFAULT NULL,
  `number_receiving_condoms_per_need` int(10) DEFAULT NULL,
  `on_pre-art_25_-_29(onsite)` int(10) DEFAULT NULL,
  `currently_on_art_30pos(onsite)` int(10) DEFAULT NULL,
  `no_of_clients_missing_>_5_consecutive_mat_doses` int(10) DEFAULT NULL,
  `number_completed_pep_within_28days` int(10) DEFAULT NULL,
  `positive_15_-_19` int(10) DEFAULT NULL,
  `started_art_15_-_19(offsite)` int(10) DEFAULT NULL,
  `net_cohot_at_12mnths` int(10) DEFAULT NULL,
  `linked_15_-_19` int(10) DEFAULT NULL,
  `on_pre-art_30pos(offsite)` int(10) DEFAULT NULL,
  `active_30pos` int(10) DEFAULT NULL,
  `tb_clients_on_haart` int(10) DEFAULT NULL,
  `number_exposed` int(10) DEFAULT NULL,
  `no_mat_clients_on_1st_line_art` int(10) DEFAULT NULL,
  `current_on_prep` int(10) DEFAULT NULL,
  `on_pre-art_20_-_24(onsite)` int(10) DEFAULT NULL,
  `currently_on_art_15_-_19(offsite)` int(10) DEFAULT NULL,
  `currently_on_art_25_-_29(onsite)` int(10) DEFAULT NULL,
  `on_pre-art_15_-_19(offsite)` int(10) DEFAULT NULL,
  `no_newly_enrolled_mat` int(10) DEFAULT NULL,
  `active_25_-_29` int(10) DEFAULT NULL,
  `positive_hcv` int(10) DEFAULT NULL,
  `number_screened_hbv` int(10) DEFAULT NULL,
  `tested_facility` int(10) DEFAULT NULL,
  `on_pre-art_15_-_19(onsite)` int(10) DEFAULT NULL,
  `tested_repeat` int(10) DEFAULT NULL,
  `on_pre-art_25_-_29(offsite)` int(10) DEFAULT NULL,
  `tested_new_(1st_testers)` int(10) DEFAULT NULL,
  `viral_load_result_12mths(offsite)` int(10) DEFAULT NULL,
  `no_ever_enrolled_on_mat` int(10) DEFAULT NULL,
  `currently_on_art_20_-_24(onsite)` int(10) DEFAULT NULL,
  `currently_on_art_15_-_19(onsite)` int(10) DEFAULT NULL,
  `on_pre-art_20_-_24(offsite)` int(10) DEFAULT NULL,
  `linked_30pos` int(10) DEFAULT NULL,
  `started_art_25_-_29(onsite)` int(10) DEFAULT NULL,
  `linked_20_-_24` int(10) DEFAULT NULL,
  `no_currently_on_mat_(active)` int(10) DEFAULT NULL,
  `number_screened_hcv` int(10) DEFAULT NULL,
  `tested_30pos` int(10) DEFAULT NULL,
  `tested_community` int(10) DEFAULT NULL,
  `dignosed_sti` int(10) DEFAULT NULL,
  `positive_20_-_24` int(10) DEFAULT NULL,
  `average_dose_overall_(mg)` int(10) DEFAULT NULL,
  `number_receiving_condoms` int(10) DEFAULT NULL,
  `started_art_30pos(onsite)` int(10) DEFAULT NULL,
  `experiencing_violence` int(10) DEFAULT NULL,
  `positive_25_-_29` int(10) DEFAULT NULL,
  `started_art_25_-_29(offsite)` int(10) DEFAULT NULL,
  `receiving_peer_education` int(10) DEFAULT NULL,
  `number_receiving_needles_&_syringes` int(10) DEFAULT NULL,
  `started_art_15_-_19(onsite)` int(10) DEFAULT NULL,
  `started_on_tb_tx` int(10) DEFAULT NULL,
  `positive_hbv` int(10) DEFAULT NULL,
  `tested_15_-_19` int(10) DEFAULT NULL,
  `tested_20_-_24` int(10) DEFAULT NULL,
  `currently_on_art_30pos(offsite)` int(10) DEFAULT NULL,
  `viral_load_<1000_12mths(offsite)` int(10) DEFAULT NULL,
  `viral_load_result_12mths(onsite)` int(10) DEFAULT NULL,
  `number_receiving_lubricants_per_need` int(10) DEFAULT NULL,
  `known_positives_(active)` int(10) DEFAULT NULL,
  `no_of_clients_discontinued_involuntary` int(10) DEFAULT NULL,
  `number_receiving_lubricants` int(10) DEFAULT NULL,
  `treated_sti` int(10) DEFAULT NULL,
  `on_pre-art_30pos(onsite)` int(10) DEFAULT NULL,
  `currently_on_art_25_-_29(offsite)` int(10) DEFAULT NULL,
  `number_screened_sti` int(10) DEFAULT NULL,
  `no_of_clients_weaned_off_methadone` int(10) DEFAULT NULL,
  `no_screened` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=582097 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `d_medical_male_circumcision`
--

DROP TABLE IF EXISTS `d_medical_male_circumcision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_medical_male_circumcision` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `circumcised_hiv-_hv04-09` int(10) DEFAULT NULL,
  `circumcised_hivpos_hv04-08` int(10) DEFAULT NULL,
  `follow_up_visit_<14d_hv04-17` int(10) DEFAULT NULL,
  `circumcised_25pos_hv04-06` int(10) DEFAULT NULL,
  `circumcised_hiv_nk_hv04-10` int(10) DEFAULT NULL,
  `circumcised_10-14_hv04-03` int(10) DEFAULT NULL,
  `ae_post_moderate_hv04-15` int(10) DEFAULT NULL,
  `ae_post_severe_hv04-16` int(10) DEFAULT NULL,
  `surgical_hv04-11` int(10) DEFAULT NULL,
  `ae_during_moderate_hv04-13` int(10) DEFAULT NULL,
  `circumcised_total_hv04-07` int(10) DEFAULT NULL,
  `ae_during_severe_hv04-14` int(10) DEFAULT NULL,
  `circumcised_20-24_hv04-05` int(10) DEFAULT NULL,
  `devices_hv04-12` int(10) DEFAULT NULL,
  `circumcised_1-9yr_hv04-02` int(10) DEFAULT NULL,
  `circumcised_<1_hv04-01` int(10) DEFAULT NULL,
  `circumcised_15-19_hv04-04` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=582097 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `d_methadone_assisted_therapy`
--

DROP TABLE IF EXISTS `d_methadone_assisted_therapy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_methadone_assisted_therapy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `keypop_on_mat_hv06-01` int(10) DEFAULT NULL,
  `keypop_who_are_pwid_hv06-04` int(10) DEFAULT NULL,
  `mat_clients_hivpos_hv06-02` int(10) DEFAULT NULL,
  `hivpos_mat_clients_on_art_hv06-03` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=582097 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `d_plus_prep_summary_reporting_tool`
--

DROP TABLE IF EXISTS `d_plus_prep_summary_reporting_tool`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_plus_prep_summary_reporting_tool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `tested_hiv_positive_while_on_prep_pwid` int(10) DEFAULT NULL,
  `continuing_(refills)_prep_fsw` int(10) DEFAULT NULL,
  `diagnosed_with_sti_prep_fsw` int(10) DEFAULT NULL,
  `continuing_(refills)_prep_total` int(10) DEFAULT NULL,
  `restarting_prep_msm` int(10) DEFAULT NULL,
  `eligible_prep_total` int(10) DEFAULT NULL,
  `discontinued_prep_pwid` int(10) DEFAULT NULL,
  `discontinued_prep_discordant_couple` int(10) DEFAULT NULL,
  `diagnosed_with_sti_prep_pwid` int(10) DEFAULT NULL,
  `diagnosed_with_sti_prep_msm` int(10) DEFAULT NULL,
  `diagnosed_with_sti_prep_total` int(10) DEFAULT NULL,
  `eligible_prep_general_popn` int(10) DEFAULT NULL,
  `continuing_(refills)_prep_pwid` int(10) DEFAULT NULL,
  `discontinued_prep_total` int(10) DEFAULT NULL,
  `diagnosed_with_sti_prep_discordant_couple` int(10) DEFAULT NULL,
  `currently_on_prep_(_new_pos_refillpos_restart)_discordant_co` int(10) DEFAULT NULL,
  `continuing_(refills)_prep_general_popn` int(10) DEFAULT NULL,
  `eligible_prep_fsw` int(10) DEFAULT NULL,
  `diagnosed_with_sti_prep_general_popn` int(10) DEFAULT NULL,
  `currently_on_prep_(_new_pos_refillpos_restart)_total` int(10) DEFAULT NULL,
  `continuing_(refills)_prep_discordant_couple` int(10) DEFAULT NULL,
  `initiated_(new)_prep_fsw` int(10) DEFAULT NULL,
  `currently_on_prep_(_new_pos_refillpos_restart)_general_popn` int(10) DEFAULT NULL,
  `currently_on_prep_(_new_pos_refillpos_restart)_pwid` int(10) DEFAULT NULL,
  `tested_hiv_positive_while_on_prep_general_popn` int(10) DEFAULT NULL,
  `continuing_(refills)_prep_msm` int(10) DEFAULT NULL,
  `currently_on_prep_(_new_pos_refillpos_restart)_fsw` int(10) DEFAULT NULL,
  `discontinued_prep_msm` int(10) DEFAULT NULL,
  `eligible_prep_discordant_couple` int(10) DEFAULT NULL,
  `initiated_(new)_prep_msm` int(10) DEFAULT NULL,
  `restarting_prep_discordant_couple` int(10) DEFAULT NULL,
  `discontinued_prep_fsw` int(10) DEFAULT NULL,
  `eligible_prep_pwid` int(10) DEFAULT NULL,
  `tested_hiv_positive_while_on_prep_fsw` int(10) DEFAULT NULL,
  `restarting_prep_pwid` int(10) DEFAULT NULL,
  `restarting_prep_general_popn` int(10) DEFAULT NULL,
  `initiated_(new)_prep_discordant_couple` int(10) DEFAULT NULL,
  `tested_hiv_positive_while_on_prep_msm` int(10) DEFAULT NULL,
  `tested_hiv_positive_while_on_prep_total` int(10) DEFAULT NULL,
  `currently_on_prep_(_new_pos_refillpos_restart)_msm` int(10) DEFAULT NULL,
  `restarting_prep_total` int(10) DEFAULT NULL,
  `tested_hiv_positive_while_on_prep_discordant_couple` int(10) DEFAULT NULL,
  `initiated_(new)_prep_pwid` int(10) DEFAULT NULL,
  `discontinued_prep_general_popn` int(10) DEFAULT NULL,
  `initiated_(new)_prep_total` int(10) DEFAULT NULL,
  `initiated_(new)_prep_general_popn` int(10) DEFAULT NULL,
  `restarting_prep_fsw` int(10) DEFAULT NULL,
  `eligible_prep_msm` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=582097 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `d_pmtct`
--

DROP TABLE IF EXISTS `d_pmtct`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_pmtct` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `not_bf_(12_months)_infant_feeding` int(10) DEFAULT NULL,
  `total_infants_issued_prophylaxis` int(10) DEFAULT NULL,
  `antenatal_positive_to_hiv_test` int(10) DEFAULT NULL,
  `total_exposed_aged_6_months` int(10) DEFAULT NULL,
  `started_on_art_during_anc` int(10) DEFAULT NULL,
  `pnc_(<72hrs)_(infant_arv_prophylaxis)` int(10) DEFAULT NULL,
  `prophylaxis_-_(aztpossdnvp)` int(10) DEFAULT NULL,
  `erf_(6_months)_infant_feeding` int(10) DEFAULT NULL,
  `serology_(from_9_to_12_months)_infant_testing_(initial_test_` int(10) DEFAULT NULL,
  `postnatal_(within_72hrs)_postive_to_hiv_test` int(10) DEFAULT NULL,
  `issued_in_anc_(infant_arv_prophylaxis)` int(10) DEFAULT NULL,
  `total_exposed_12_months` int(10) DEFAULT NULL,
  `total_confirmed_positive_infant_test_result_by_pcr` int(10) DEFAULT NULL,
  `pcr_(within_2_months)_infant_testing_(initial_test_only)` int(10) DEFAULT NULL,
  `discordant_couples_partner_involvement` int(10) DEFAULT NULL,
  `haart_(art)` int(10) DEFAULT NULL,
  `labour_and_delivery_testing_for_hiv` int(10) DEFAULT NULL,
  `known_positive_status_(at_entry_into_anc)` int(10) DEFAULT NULL,
  `antenatal_testing_for_hiv` int(10) DEFAULT NULL,
  `bf_(at_12_months)_infant_feeding` int(10) DEFAULT NULL,
  `total_tested_(pmtct)` int(10) DEFAULT NULL,
  `assessed_eligibility_in_anc` int(10) DEFAULT NULL,
  `pcr_(9_to_12_months)_confirmed_infant_test_results_positive` int(10) DEFAULT NULL,
  `not_known_infant_feeding_(12_months)` int(10) DEFAULT NULL,
  `total_hei_tested_by_12_months` int(10) DEFAULT NULL,
  `postnatal_(within_72hrs)_testing_for_hiv` int(10) DEFAULT NULL,
  `assessed_for_eligibility_in_1st_anc_-_who_staging_done` int(10) DEFAULT NULL,
  `labour_and_delivery_postive_to_hiv_test` int(10) DEFAULT NULL,
  `labour_and_delivery_(infant_arv_prophylaxis)` int(10) DEFAULT NULL,
  `prophylaxis_–_haart` int(10) DEFAULT NULL,
  `male_partners_tested_-(_anc/l&d)` int(10) DEFAULT NULL,
  `ebf_(6_months)_infant_feeding` int(10) DEFAULT NULL,
  `total_pmtct_prophylaxis` int(10) DEFAULT NULL,
  `pcr_(by_2_months)_confirmed_infant_test_results_positive` int(10) DEFAULT NULL,
  `pcr_(3_to_8_months)_confirmed_infant_test_results_positive` int(10) DEFAULT NULL,
  `pcr_(from3_to_8_months)_infant_testing_(initial_test_only)` int(10) DEFAULT NULL,
  `prophylaxis-nvp_only` int(10) DEFAULT NULL,
  `total_positive_(pmtct)` int(10) DEFAULT NULL,
  `mf_(6_months)_infant_feeding` int(10) DEFAULT NULL,
  `prophylaxis_-_interrupted_haart` int(10) DEFAULT NULL,
  `pcr_(from_9_to_12_months)_infant_testing_(initial_test_only)` int(10) DEFAULT NULL,
  `assessed_for_eligibility_in_1st_anc_-_cd4` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=582097 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `d_pns`
--

DROP TABLE IF EXISTS `d_pns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_pns` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `screened_unknown_m` int(10) DEFAULT NULL,
  `screened_unknown_f` int(10) DEFAULT NULL,
  `screened_below_1` int(10) DEFAULT NULL,
  `screened_below_10` int(10) DEFAULT NULL,
  `screened_below_15_m` int(10) DEFAULT NULL,
  `screened_below_15_f` int(10) DEFAULT NULL,
  `screened_below_20_m` int(10) DEFAULT NULL,
  `screened_below_20_f` int(10) DEFAULT NULL,
  `screened_below_25_m` int(10) DEFAULT NULL,
  `screened_below_25_f` int(10) DEFAULT NULL,
  `screened_below_30_m` int(10) DEFAULT NULL,
  `screened_below_30_f` int(10) DEFAULT NULL,
  `screened_below_50_m` int(10) DEFAULT NULL,
  `screened_below_50_f` int(10) DEFAULT NULL,
  `screened_above_50_m` int(10) DEFAULT NULL,
  `screened_above_50_f` int(10) DEFAULT NULL,
  `contacts_identified_unknown_m` int(10) DEFAULT NULL,
  `contacts_identified_unknown_f` int(10) DEFAULT NULL,
  `contacts_identified_below_1` int(10) DEFAULT NULL,
  `contacts_identified_below_10` int(10) DEFAULT NULL,
  `contacts_identified_below_15_m` int(10) DEFAULT NULL,
  `contacts_identified_below_15_f` int(10) DEFAULT NULL,
  `contacts_identified_below_20_m` int(10) DEFAULT NULL,
  `contacts_identified_below_20_f` int(10) DEFAULT NULL,
  `contacts_identified_below_25_m` int(10) DEFAULT NULL,
  `contacts_identified_below_25_f` int(10) DEFAULT NULL,
  `contacts_identified_below_30_m` int(10) DEFAULT NULL,
  `contacts_identified_below_30_f` int(10) DEFAULT NULL,
  `contacts_identified_below_50_m` int(10) DEFAULT NULL,
  `contacts_identified_below_50_f` int(10) DEFAULT NULL,
  `contacts_identified_above_50_m` int(10) DEFAULT NULL,
  `contacts_identified_above_50_f` int(10) DEFAULT NULL,
  `pos_contacts_unknown_m` int(10) DEFAULT NULL,
  `pos_contacts_unknown_f` int(10) DEFAULT NULL,
  `pos_contacts_below_1` int(10) DEFAULT NULL,
  `pos_contacts_below_10` int(10) DEFAULT NULL,
  `pos_contacts_below_15_m` int(10) DEFAULT NULL,
  `pos_contacts_below_15_f` int(10) DEFAULT NULL,
  `pos_contacts_below_20_m` int(10) DEFAULT NULL,
  `pos_contacts_below_20_f` int(10) DEFAULT NULL,
  `pos_contacts_below_25_m` int(10) DEFAULT NULL,
  `pos_contacts_below_25_f` int(10) DEFAULT NULL,
  `pos_contacts_below_30_m` int(10) DEFAULT NULL,
  `pos_contacts_below_30_f` int(10) DEFAULT NULL,
  `pos_contacts_below_50_m` int(10) DEFAULT NULL,
  `pos_contacts_below_50_f` int(10) DEFAULT NULL,
  `pos_contacts_above_50_m` int(10) DEFAULT NULL,
  `pos_contacts_above_50_f` int(10) DEFAULT NULL,
  `eligible_contacts_unknown_m` int(10) DEFAULT NULL,
  `eligible_contacts_unknown_f` int(10) DEFAULT NULL,
  `eligible_contacts_below_1` int(10) DEFAULT NULL,
  `eligible_contacts_below_10` int(10) DEFAULT NULL,
  `eligible_contacts_below_15_m` int(10) DEFAULT NULL,
  `eligible_contacts_below_15_f` int(10) DEFAULT NULL,
  `eligible_contacts_below_20_m` int(10) DEFAULT NULL,
  `eligible_contacts_below_20_f` int(10) DEFAULT NULL,
  `eligible_contacts_below_25_m` int(10) DEFAULT NULL,
  `eligible_contacts_below_25_f` int(10) DEFAULT NULL,
  `eligible_contacts_below_30_m` int(10) DEFAULT NULL,
  `eligible_contacts_below_30_f` int(10) DEFAULT NULL,
  `eligible_contacts_below_50_m` int(10) DEFAULT NULL,
  `eligible_contacts_below_50_f` int(10) DEFAULT NULL,
  `eligible_contacts_above_50_m` int(10) DEFAULT NULL,
  `eligible_contacts_above_50_f` int(10) DEFAULT NULL,
  `contacts_tested_unknown_m` int(10) DEFAULT NULL,
  `contacts_tested_unknown_f` int(10) DEFAULT NULL,
  `contacts_tested_below_1` int(10) DEFAULT NULL,
  `contacts_tested_below_10` int(10) DEFAULT NULL,
  `contacts_tested_below_15_m` int(10) DEFAULT NULL,
  `contacts_tested_below_15_f` int(10) DEFAULT NULL,
  `contacts_tested_below_20_m` int(10) DEFAULT NULL,
  `contacts_tested_below_20_f` int(10) DEFAULT NULL,
  `contacts_tested_below_25_m` int(10) DEFAULT NULL,
  `contacts_tested_below_25_f` int(10) DEFAULT NULL,
  `contacts_tested_below_30_m` int(10) DEFAULT NULL,
  `contacts_tested_below_30_f` int(10) DEFAULT NULL,
  `contacts_tested_below_50_m` int(10) DEFAULT NULL,
  `contacts_tested_below_50_f` int(10) DEFAULT NULL,
  `contacts_tested_above_50_m` int(10) DEFAULT NULL,
  `contacts_tested_above_50_f` int(10) DEFAULT NULL,
  `new_pos_unknown_m` int(10) DEFAULT NULL,
  `new_pos_unknown_f` int(10) DEFAULT NULL,
  `new_pos_below_1` int(10) DEFAULT NULL,
  `new_pos_below_10` int(10) DEFAULT NULL,
  `new_pos_below_15_m` int(10) DEFAULT NULL,
  `new_pos_below_15_f` int(10) DEFAULT NULL,
  `new_pos_below_20_m` int(10) DEFAULT NULL,
  `new_pos_below_20_f` int(10) DEFAULT NULL,
  `new_pos_below_25_m` int(10) DEFAULT NULL,
  `new_pos_below_25_f` int(10) DEFAULT NULL,
  `new_pos_below_30_m` int(10) DEFAULT NULL,
  `new_pos_below_30_f` int(10) DEFAULT NULL,
  `new_pos_below_50_m` int(10) DEFAULT NULL,
  `new_pos_below_50_f` int(10) DEFAULT NULL,
  `new_pos_above_50_m` int(10) DEFAULT NULL,
  `new_pos_above_50_f` int(10) DEFAULT NULL,
  `linked_haart_unknown_m` int(10) DEFAULT NULL,
  `linked_haart_unknown_f` int(10) DEFAULT NULL,
  `linked_haart_below_1` int(10) DEFAULT NULL,
  `linked_haart_below_10` int(10) DEFAULT NULL,
  `linked_haart_below_15_m` int(10) DEFAULT NULL,
  `linked_haart_below_15_f` int(10) DEFAULT NULL,
  `linked_haart_below_20_m` int(10) DEFAULT NULL,
  `linked_haart_below_20_f` int(10) DEFAULT NULL,
  `linked_haart_below_25_m` int(10) DEFAULT NULL,
  `linked_haart_below_25_f` int(10) DEFAULT NULL,
  `linked_haart_below_30_m` int(10) DEFAULT NULL,
  `linked_haart_below_30_f` int(10) DEFAULT NULL,
  `linked_haart_below_50_m` int(10) DEFAULT NULL,
  `linked_haart_below_50_f` int(10) DEFAULT NULL,
  `linked_haart_above_50_m` int(10) DEFAULT NULL,
  `linked_haart_above_50_f` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=436537 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `d_post-exposure_prophylaxis`
--

DROP TABLE IF EXISTS `d_post-exposure_prophylaxis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_post-exposure_prophylaxis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `sexual_assault_male_type_of_exposure` int(10) DEFAULT NULL,
  `occupational_female_provided_with_prophylaxis` int(10) DEFAULT NULL,
  `occupational_female_type_of_exposure` int(10) DEFAULT NULL,
  `other_reasons_female_type_of_exposure` int(10) DEFAULT NULL,
  `other_reasons_–_female_-provided_with_prophylaxis` int(10) DEFAULT NULL,
  `occupational_male_provided_with_prophylaxis` int(10) DEFAULT NULL,
  `total_pep` int(10) DEFAULT NULL,
  `sexual_assault_female_type_of_exposure` int(10) DEFAULT NULL,
  `occupational_male_type_of_exposure` int(10) DEFAULT NULL,
  `total_type_of_exposure` int(10) DEFAULT NULL,
  `other_reasons_–_male_-_provided_with_prophylaxis` int(10) DEFAULT NULL,
  `sexual_assault_female_provided_with_prophylaxis` int(10) DEFAULT NULL,
  `other_reasons_male_type_of_exposure` int(10) DEFAULT NULL,
  `sexual_assault_male_provided_with_prophylaxis` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=582097 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `d_post_exposure_prophylaxis`
--

DROP TABLE IF EXISTS `d_post_exposure_prophylaxis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_post_exposure_prophylaxis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `pep_other_hv05-06` int(10) DEFAULT NULL,
  `pep_occupational_hv05-05` int(10) DEFAULT NULL,
  `exposed_total_hv05-03` int(10) DEFAULT NULL,
  `exposed_other_hv05-02` int(10) DEFAULT NULL,
  `exposed_occupational_hv05-01` int(10) DEFAULT NULL,
  `pep_total_hv05-07` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=582097 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `d_prevention_of_mother-to-child_transmission`
--

DROP TABLE IF EXISTS `d_prevention_of_mother-to-child_transmission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_prevention_of_mother-to-child_transmission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `total_positive_(add_hv02-10_-_hv02-14)_hv02-15` int(10) DEFAULT NULL,
  `erf_(at_6_months)_hv02-54` int(10) DEFAULT NULL,
  `known_exposure_at_penta_1_hv02-37` int(10) DEFAULT NULL,
  `not_bf_(at_18_months)_hv02-59` int(10) DEFAULT NULL,
  `hei_ctx/dds_start_<2months_hv02-43` int(10) DEFAULT NULL,
  `mf_(at_6_months)_hv02-55` int(10) DEFAULT NULL,
  `initial_test_at_l&d_hv02-05` int(10) DEFAULT NULL,
  `positive_results_anc_hv02-11` int(10) DEFAULT NULL,
  `not_bf_(at_12_months)_hv02-57` int(10) DEFAULT NULL,
  `infected_24mths_hv02-47` int(10) DEFAULT NULL,
  `known_status_1st_contact_hv02-29` int(10) DEFAULT NULL,
  `initial_pcr_<_8wks_hv02-44` int(10) DEFAULT NULL,
  `hivpos_on_modern_fp_at_6wks_hv02-27` int(10) DEFAULT NULL,
  `pair_net_cohort_24_months_hv02-52` int(10) DEFAULT NULL,
  `infant_arv_prophyl_l&d_hv02-40` int(10) DEFAULT NULL,
  `known_hiv_status_total_hv02-07` int(10) DEFAULT NULL,
  `known_positive_at_1st_anc_hv02-10` int(10) DEFAULT NULL,
  `infant_arv_prophyl<8wks_pnc_hv02-41` int(10) DEFAULT NULL,
  `syphilis_screened_1st_anc_hv02-24` int(10) DEFAULT NULL,
  `unknown_outcome_hv02-49` int(10) DEFAULT NULL,
  `total_arv_prophylaxis_hv02-42` int(10) DEFAULT NULL,
  `bf_(_at18_months)_hv02-58` int(10) DEFAULT NULL,
  `positive_result_adolescents_total_hv02-35` int(10) DEFAULT NULL,
  `total_known_status_male_hv02-33` int(10) DEFAULT NULL,
  `ebf_(at_6_months)_hv02-53` int(10) DEFAULT NULL,
  `initial_test_at_anc_hv02-04` int(10) DEFAULT NULL,
  `positive_results_pnc<=6wks_hv02-13` int(10) DEFAULT NULL,
  `net_cohort_hei_24_months_hv02-50` int(10) DEFAULT NULL,
  `start_haart_pnc>_6weeks_to_6_months_hv02-21` int(10) DEFAULT NULL,
  `start_haart_l&d_hv02-18` int(10) DEFAULT NULL,
  `delivery_from_hivpos_mothers_hv02-02` int(10) DEFAULT NULL,
  `positive_results_l&d_hv02-12` int(10) DEFAULT NULL,
  `initial_test_at_pnc_pnc<=6wks_hv02-06` int(10) DEFAULT NULL,
  `initial_test_at_anc_male_hv02-30` int(10) DEFAULT NULL,
  `on_maternal_haart_12mths_hv02-22` int(10) DEFAULT NULL,
  `mother-baby_pairs_24mths_hv02-51` int(10) DEFAULT NULL,
  `initial_pcr_>8wks_-12_mths_hv02-45` int(10) DEFAULT NULL,
  `syphilis_treated_hv02-26` int(10) DEFAULT NULL,
  `initial_pcr_test<12mths_total_hv02-46` int(10) DEFAULT NULL,
  `1st_anc_visits_hv02-01` int(10) DEFAULT NULL,
  `1st_anc_kp_adolescents_(10-19)_hv02-34` int(10) DEFAULT NULL,
  `start_haart_anc_hv02-17` int(10) DEFAULT NULL,
  `syphilis_screened_positive_hv02-25` int(10) DEFAULT NULL,
  `on_maternal_haart_total_hv02-20` int(10) DEFAULT NULL,
  `net_cohort_12_mths_hv02-23` int(10) DEFAULT NULL,
  `started_haart_adolescents_total_hv02-36` int(10) DEFAULT NULL,
  `retesting_pnc<=_6_weeks_hv02-08` int(10) DEFAULT NULL,
  `total_tested_positive_(3_months_ago)_hv01-36` int(10) DEFAULT NULL,
  `bf_(at_12_months)_hv02-56` int(10) DEFAULT NULL,
  `total_given_penta_1_hv02-38` int(10) DEFAULT NULL,
  `initial_test_at_l&d_male_hv02-31` int(10) DEFAULT NULL,
  `known_positive_at_1st_anc_hv02-03` int(10) DEFAULT NULL,
  `positive_pnc>_6weeks_to_6_months_hv02-14` int(10) DEFAULT NULL,
  `uninfected_24mths_hv02-48` int(10) DEFAULT NULL,
  `initial_test_at_pnc_male_hv02-32` int(10) DEFAULT NULL,
  `tested_pnc>_6weeks_to_6_months_hv02-09` int(10) DEFAULT NULL,
  `hivpos_pnc_visits_at_6wks_hv02-28` int(10) DEFAULT NULL,
  `infant_arv_prophyl_anc_hv02-39` int(10) DEFAULT NULL,
  `start_haart_pnc<=6wks_hv02-19` int(10) DEFAULT NULL,
  `on_haart_at_1st_anc_hv02-16` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=582097 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `d_regimen_totals`
--

DROP TABLE IF EXISTS `d_regimen_totals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_regimen_totals` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `art` int(10) DEFAULT NULL,
  `pmtct` int(10) DEFAULT NULL,
  `pep` int(10) DEFAULT NULL,
  `prep` int(10) DEFAULT NULL,
  `oi_only` int(10) DEFAULT NULL,
  `hep_b` int(10) DEFAULT NULL,
  `dmap_art` int(10) DEFAULT NULL,
  `dmap_pmtct` int(10) DEFAULT NULL,
  `dmap_pep` int(10) DEFAULT NULL,
  `dmap_prep` int(10) DEFAULT NULL,
  `dmap_oi_only` int(10) DEFAULT NULL,
  `dmap_hep_b` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=582097 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `data_set_elements`
--

DROP TABLE IF EXISTS `data_set_elements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data_set_elements` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dhis` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `table_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `targets_table_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `column_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_set_id` tinyint(3) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=529 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `data_sets`
--

DROP TABLE IF EXISTS `data_sets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data_sets` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dhis` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_dhis` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `districts`
--

DROP TABLE IF EXISTS `districts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `districts` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `SubCountyDHISCode` varchar(50) DEFAULT NULL,
  `SubCountyMFLCode` varchar(50) DEFAULT NULL,
  `SubCountyCoordinates` varchar(3070) DEFAULT NULL,
  `county` tinyint(3) unsigned DEFAULT NULL,
  `province` tinyint(3) unsigned DEFAULT NULL,
  `comment` varchar(32) DEFAULT NULL,
  `flag` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=391 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `divisions`
--

DROP TABLE IF EXISTS `divisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `divisions` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `facilitys`
--

DROP TABLE IF EXISTS `facilitys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facilitys` (
  `id` int(14) unsigned NOT NULL AUTO_INCREMENT,
  `facilitycode` int(10) NOT NULL DEFAULT '0' COMMENT 'Facility Name',
  `district` smallint(5) unsigned DEFAULT '0' COMMENT 'Facility District ID',
  `subcounty_id` smallint(5) unsigned DEFAULT '0' COMMENT 'Facility District ID',
  `ward_id` smallint(5) unsigned DEFAULT '0' COMMENT 'Facility Ward ID',
  `name` varchar(100) DEFAULT NULL COMMENT 'Facility Name',
  `new_name` varchar(100) DEFAULT NULL COMMENT 'Facility Name',
  `lab` int(14) NOT NULL DEFAULT '0',
  `partner` int(14) NOT NULL DEFAULT '0',
  `ftype` varchar(200) DEFAULT NULL,
  `DHIScode` varchar(50) NOT NULL DEFAULT '0' COMMENT 'Facility Name',
  `is_pns` tinyint(1) DEFAULT '0',
  `is_viremia` tinyint(1) DEFAULT NULL,
  `is_dsd` tinyint(1) DEFAULT NULL,
  `is_otz` tinyint(1) DEFAULT NULL,
  `is_men_clinic` tinyint(1) DEFAULT NULL,
  `is_surge` tinyint(1) DEFAULT NULL,
  `longitude` varchar(200) DEFAULT NULL,
  `latitude` varchar(200) DEFAULT NULL,
  `burden` varchar(200) DEFAULT NULL,
  `artpatients` int(200) DEFAULT NULL,
  `pmtctnos` int(200) DEFAULT NULL,
  `Mless15` int(200) DEFAULT NULL,
  `Mmore15` int(200) DEFAULT NULL,
  `Fless15` int(200) DEFAULT NULL,
  `Fmore15` int(200) DEFAULT NULL,
  `totalartmar` int(200) DEFAULT NULL,
  `totalartsep17` int(200) DEFAULT NULL,
  `totalartsep15` int(200) DEFAULT NULL,
  `asofdate` date DEFAULT NULL,
  `partnerold` int(14) DEFAULT '0' COMMENT 'before Aug 2016 Update',
  `partner2` int(14) DEFAULT '0' COMMENT 'for boresha maabara who do m&e',
  `partner3` int(14) DEFAULT '0' COMMENT 'for speed24 who do m&e',
  `partner4` int(14) DEFAULT '0' COMMENT 'for PHASE who do m&e',
  `partner5` int(14) DEFAULT '0' COMMENT 'for JILINDE PARTNER who do m&e',
  `partner6` int(14) DEFAULT '0' COMMENT 'for FHI 360 who do m&e',
  `telephone` varchar(20) DEFAULT NULL COMMENT 'Facility Telephone 1',
  `telephone2` varchar(20) DEFAULT NULL COMMENT 'Facility Telephone 2',
  `telephone3` varchar(20) DEFAULT NULL COMMENT 'Facility Telephone 2',
  `fax` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL COMMENT 'Facility email Address',
  `PostalAddress` varchar(40) DEFAULT NULL COMMENT 'Facility Contact Address',
  `contactperson` varchar(30) DEFAULT NULL COMMENT 'Facility Contact Name',
  `contacttelephone` varchar(20) DEFAULT NULL COMMENT 'Contact Person''s Telephone 1',
  `contacttelephone2` varchar(20) DEFAULT NULL COMMENT 'Contact Person''s Telephone 2',
  `contacttelephone3` varchar(20) DEFAULT NULL COMMENT 'Contact Person''s Telephone 2',
  `physicaladdress` varchar(40) DEFAULT NULL COMMENT 'Facility Physical Address',
  `ContactEmail` varchar(40) DEFAULT NULL COMMENT 'Contact Person''s Email',
  `ContactEmail2` varchar(40) DEFAULT NULL COMMENT 'Contact Person''s Email',
  `ContactEmail3` varchar(40) DEFAULT NULL COMMENT 'Contact Person''s Email',
  `ContactEmail4` varchar(40) DEFAULT NULL COMMENT 'Contact Person''s Email',
  `ContactEmail5` varchar(40) DEFAULT NULL COMMENT 'Contact Person''s Email',
  `ContactEmail6` varchar(40) DEFAULT NULL COMMENT 'Contact Person''s Email',
  `subcountyemail` varchar(40) DEFAULT NULL COMMENT 'Contact Person''s Email',
  `countyemail` varchar(40) DEFAULT NULL COMMENT 'Contact Person''s Email',
  `partneremail` varchar(40) DEFAULT NULL COMMENT 'Contact Person''s Email',
  `originalID` int(14) DEFAULT NULL,
  `partnerlabmail` varchar(25) DEFAULT NULL,
  `partnerpointmail` varchar(25) DEFAULT NULL,
  `dmltemail` varchar(25) DEFAULT NULL,
  `dtlcemail` varchar(25) DEFAULT NULL,
  `serviceprovider` varchar(25) DEFAULT NULL,
  `smsprinterphoneno` varchar(25) DEFAULT NULL,
  `smssecondarycontact` varchar(100) DEFAULT NULL,
  `smsprimarycontact` varchar(100) DEFAULT NULL,
  `smscontactperson` varchar(100) DEFAULT NULL,
  `smsprinter` int(14) DEFAULT '0',
  `G4Sbranchname` varchar(100) DEFAULT NULL,
  `G4Slocation` varchar(100) DEFAULT NULL,
  `G4Sphone1` varchar(100) DEFAULT NULL,
  `G4Sphone2` varchar(100) DEFAULT NULL,
  `G4Sphone3` varchar(100) DEFAULT NULL,
  `G4Sfax` varchar(100) DEFAULT NULL,
  `PMTCT` varchar(5) DEFAULT NULL,
  `ART` varchar(5) DEFAULT NULL,
  `Flag` tinyint(4) NOT NULL DEFAULT '1',
  `sent` int(14) DEFAULT '0',
  `datemodified` date DEFAULT NULL,
  `synched` tinyint(4) DEFAULT '0',
  `invalid_dhis` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `lab` (`lab`),
  KEY `partner` (`partner`),
  KEY `district` (`district`),
  KEY `subcounty_id` (`subcounty_id`),
  KEY `ward_id` (`ward_id`),
  KEY `Flag` (`Flag`),
  KEY `is_surge` (`is_surge`)
) ENGINE=InnoDB AUTO_INCREMENT=62912 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `funding_agencies`
--

DROP TABLE IF EXISTS `funding_agencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `funding_agencies` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `labs`
--

DROP TABLE IF EXISTS `labs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `labs` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(32) DEFAULT NULL,
  `labname` varchar(50) DEFAULT NULL,
  `labdesc` varchar(50) DEFAULT NULL,
  `lablocation` varchar(50) DEFAULT NULL,
  `base_url` varchar(70) DEFAULT NULL,
  `labtel1` varchar(32) DEFAULT NULL,
  `labtel2` varchar(32) DEFAULT NULL,
  `taqman` int(1) DEFAULT '1',
  `abbott` int(1) DEFAULT '1',
  `apikey` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `m_art`
--

DROP TABLE IF EXISTS `m_art`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_art` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `current_below1` int(10) DEFAULT NULL,
  `current_below10` int(10) DEFAULT NULL,
  `current_below15_m` int(10) DEFAULT NULL,
  `current_below15_f` int(10) DEFAULT NULL,
  `current_below20_m` int(10) DEFAULT NULL,
  `current_below20_f` int(10) DEFAULT NULL,
  `current_below25_m` int(10) DEFAULT NULL,
  `current_below25_f` int(10) DEFAULT NULL,
  `current_above25_m` int(10) DEFAULT NULL,
  `current_above25_f` int(10) DEFAULT NULL,
  `current_total` int(10) DEFAULT NULL,
  `new_below1` int(10) DEFAULT NULL,
  `new_below10` int(10) DEFAULT NULL,
  `new_below15_m` int(10) DEFAULT NULL,
  `new_below15_f` int(10) DEFAULT NULL,
  `new_below20_m` int(10) DEFAULT NULL,
  `new_below20_f` int(10) DEFAULT NULL,
  `new_below25_m` int(10) DEFAULT NULL,
  `new_below25_f` int(10) DEFAULT NULL,
  `new_above25_m` int(10) DEFAULT NULL,
  `new_above25_f` int(10) DEFAULT NULL,
  `new_total` int(10) DEFAULT NULL,
  `enrolled_below1` int(10) DEFAULT NULL,
  `enrolled_below10` int(10) DEFAULT NULL,
  `enrolled_below15_m` int(10) DEFAULT NULL,
  `enrolled_below15_f` int(10) DEFAULT NULL,
  `enrolled_below20_m` int(10) DEFAULT NULL,
  `enrolled_below20_f` int(10) DEFAULT NULL,
  `enrolled_below25_m` int(10) DEFAULT NULL,
  `enrolled_below25_f` int(10) DEFAULT NULL,
  `enrolled_above25_m` int(10) DEFAULT NULL,
  `enrolled_above25_f` int(10) DEFAULT NULL,
  `enrolled_total` int(10) DEFAULT NULL,
  `tb_screened_below1` int(10) DEFAULT NULL,
  `tb_screened_below10` int(10) DEFAULT NULL,
  `tb_screened_below15` int(10) DEFAULT NULL,
  `tb_screened_below20` int(10) DEFAULT NULL,
  `tb_screened_below25` int(10) DEFAULT NULL,
  `tb_screened_above25` int(10) DEFAULT NULL,
  `tb_screened_total` int(10) DEFAULT NULL,
  `tb_starting_art` int(10) DEFAULT NULL,
  `tb_already_on_art` int(10) DEFAULT NULL,
  `tb_art_total` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=3055747 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `m_circumcision`
--

DROP TABLE IF EXISTS `m_circumcision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_circumcision` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `circumcised_below1` int(10) DEFAULT NULL,
  `circumcised_below10` int(10) DEFAULT NULL,
  `circumcised_below15` int(10) DEFAULT NULL,
  `circumcised_below20` int(10) DEFAULT NULL,
  `circumcised_below25` int(10) DEFAULT NULL,
  `circumcised_above25` int(10) DEFAULT NULL,
  `circumcised_total` int(10) DEFAULT NULL,
  `circumcised_pos` int(10) DEFAULT NULL,
  `circumcised_neg` int(10) DEFAULT NULL,
  `circumcised_nk` int(10) DEFAULT NULL,
  `circumcised_surgical` int(10) DEFAULT NULL,
  `circumcised_devices` int(10) DEFAULT NULL,
  `ae_during_moderate` int(10) DEFAULT NULL,
  `ae_during_severe` int(10) DEFAULT NULL,
  `ae_post_moderate` int(10) DEFAULT NULL,
  `ae_post_severe` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=3055747 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `m_keypop`
--

DROP TABLE IF EXISTS `m_keypop`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_keypop` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `tested` int(10) DEFAULT NULL,
  `positive` int(10) DEFAULT NULL,
  `enrolled` int(10) DEFAULT NULL,
  `current_tx` int(10) DEFAULT NULL,
  `new_tx` int(10) DEFAULT NULL,
  `mat_total` int(10) DEFAULT NULL,
  `mat_clients_pos` int(10) DEFAULT NULL,
  `mat_on_art` int(10) DEFAULT NULL,
  `keypop_pwid` int(10) DEFAULT NULL,
  `tested_couples` int(10) DEFAULT NULL,
  `discordant_couples` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=3055747 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `m_pmtct`
--

DROP TABLE IF EXISTS `m_pmtct`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_pmtct` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `tested_pmtct` int(10) DEFAULT NULL,
  `known_pos_anc` int(10) DEFAULT NULL,
  `initial_test_anc` int(10) DEFAULT NULL,
  `initial_test_lnd` int(10) DEFAULT NULL,
  `initial_test_pnc` int(10) DEFAULT NULL,
  `positives_anc` int(10) DEFAULT NULL,
  `positives_lnd` int(10) DEFAULT NULL,
  `positives_pnc` int(10) DEFAULT NULL,
  `positives_pnc6m` int(10) DEFAULT NULL,
  `total_positive_pmtct` int(10) DEFAULT NULL,
  `total_new_positive_pmtct` int(10) DEFAULT NULL,
  `haart_total` int(10) DEFAULT NULL,
  `on_haart_anc` int(10) DEFAULT NULL,
  `start_art_anc` int(10) DEFAULT NULL,
  `start_art_lnd` int(10) DEFAULT NULL,
  `start_art_pnc` int(10) DEFAULT NULL,
  `start_art_pnc_6m` int(10) DEFAULT NULL,
  `known_status_before_male` int(10) DEFAULT NULL,
  `initial_male_test_anc` int(10) DEFAULT NULL,
  `initial_male_test_lnd` int(10) DEFAULT NULL,
  `initial_male_test_pnc` int(10) DEFAULT NULL,
  `known_status_male` int(10) DEFAULT NULL,
  `initial_pcr_2m` int(10) DEFAULT NULL,
  `initial_pcr_12m` int(10) DEFAULT NULL,
  `confirmed_pos` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=3055747 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `m_testing`
--

DROP TABLE IF EXISTS `m_testing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_testing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `facility` int(10) unsigned DEFAULT '0',
  `testing_total` int(10) DEFAULT NULL,
  `first_test_hiv` int(10) DEFAULT NULL,
  `repeat_test_hiv` int(10) DEFAULT NULL,
  `facility_test_hiv` int(10) DEFAULT NULL,
  `outreach_test_hiv` int(10) DEFAULT NULL,
  `tested_couples` int(10) DEFAULT NULL,
  `discordant_couples` int(10) DEFAULT NULL,
  `positive_below10` int(10) DEFAULT NULL,
  `positive_below15_m` int(10) DEFAULT NULL,
  `positive_below15_f` int(10) DEFAULT NULL,
  `positive_below20_m` int(10) DEFAULT NULL,
  `positive_below20_f` int(10) DEFAULT NULL,
  `positive_below25_m` int(10) DEFAULT NULL,
  `positive_below25_f` int(10) DEFAULT NULL,
  `positive_above25_m` int(10) DEFAULT NULL,
  `positive_above25_f` int(10) DEFAULT NULL,
  `positive_total` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`),
  KEY `identifier_other` (`facility`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=3055747 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=432 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `modalities`
--

DROP TABLE IF EXISTS `modalities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modalities` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `modality` varchar(30) DEFAULT NULL,
  `modality_name` varchar(60) DEFAULT NULL,
  `table_name` varchar(60) DEFAULT NULL,
  `male` tinyint(1) unsigned DEFAULT '1',
  `female` tinyint(1) unsigned DEFAULT '1',
  `unknown` tinyint(1) unsigned DEFAULT '1',
  `hts` tinyint(1) unsigned DEFAULT '1',
  `target` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `modality` (`modality`),
  KEY `modality_name` (`modality_name`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `p_early_indicators`
--

DROP TABLE IF EXISTS `p_early_indicators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `p_early_indicators` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` smallint(4) unsigned DEFAULT '0',
  `partner` tinyint(3) unsigned DEFAULT '0',
  `county` tinyint(3) unsigned DEFAULT '0',
  `tested` int(10) DEFAULT NULL,
  `positive` int(10) DEFAULT NULL,
  `new_art` int(10) DEFAULT NULL,
  `linkage` double(6,4) DEFAULT NULL,
  `current_tx` int(10) DEFAULT NULL,
  `net_new_tx` int(10) DEFAULT NULL,
  `vl_total` int(10) DEFAULT NULL,
  `eligible_for_vl` int(10) DEFAULT NULL,
  `pmtct` int(10) DEFAULT NULL,
  `pmtct_stat` int(10) DEFAULT NULL,
  `pmtct_new_pos` int(10) DEFAULT NULL,
  `pmtct_known_pos` int(10) DEFAULT NULL,
  `pmtct_total_pos` int(10) DEFAULT NULL,
  `art_pmtct` int(10) DEFAULT NULL,
  `art_uptake_pmtct` int(10) DEFAULT NULL,
  `eid_lt_2m` int(10) DEFAULT NULL,
  `eid_lt_12m` int(10) DEFAULT NULL,
  `eid_total` int(10) DEFAULT NULL,
  `eid_pos` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `specific_identifier` (`partner`,`county`),
  KEY `identifier` (`partner`,`county`),
  KEY `p_identifier` (`partner`),
  KEY `c_identifier` (`county`),
  KEY `partner` (`partner`),
  KEY `county` (`county`)
) ENGINE=InnoDB AUTO_INCREMENT=82909 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `p_early_indicators_view`
--

DROP TABLE IF EXISTS `p_early_indicators_view`;
/*!50001 DROP VIEW IF EXISTS `p_early_indicators_view`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `p_early_indicators_view` AS SELECT 
 1 AS `id`,
 1 AS `partner`,
 1 AS `county`,
 1 AS `year`,
 1 AS `month`,
 1 AS `financial_year`,
 1 AS `quarter`,
 1 AS `tested`,
 1 AS `positive`,
 1 AS `new_art`,
 1 AS `linkage`,
 1 AS `current_tx`,
 1 AS `net_new_tx`,
 1 AS `vl_total`,
 1 AS `eligible_for_vl`,
 1 AS `pmtct`,
 1 AS `pmtct_stat`,
 1 AS `pmtct_new_pos`,
 1 AS `pmtct_known_pos`,
 1 AS `pmtct_total_pos`,
 1 AS `art_pmtct`,
 1 AS `art_uptake_pmtct`,
 1 AS `eid_lt_2m`,
 1 AS `eid_lt_12m`,
 1 AS `eid_total`,
 1 AS `eid_pos`,
 1 AS `dateupdated`,
 1 AS `partnername`,
 1 AS `countyname`,
 1 AS `CountyDHISCode`,
 1 AS `CountyMFLCode`,
 1 AS `funding_agency_id`,
 1 AS `funding_agency`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `p_non_mer`
--

DROP TABLE IF EXISTS `p_non_mer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `p_non_mer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `partner` int(10) unsigned DEFAULT '0',
  `financial_year` smallint(4) unsigned DEFAULT '0',
  `viremia` int(10) DEFAULT NULL,
  `dsd` int(10) DEFAULT NULL,
  `otz` int(10) DEFAULT NULL,
  `men_clinic` int(10) DEFAULT NULL,
  `pns` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`partner`,`financial_year`),
  KEY `partner` (`partner`)
) ENGINE=InnoDB AUTO_INCREMENT=148 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partners` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `partnerDHISCode` varchar(30) DEFAULT NULL,
  `mech_id` varchar(10) DEFAULT NULL,
  `fundingagency` varchar(100) DEFAULT NULL,
  `funding_agency_id` tinyint(3) unsigned DEFAULT '0',
  `logo` varchar(45) DEFAULT NULL,
  `flag` int(45) DEFAULT NULL,
  `orderno` int(45) DEFAULT NULL,
  `unknown2013` double DEFAULT NULL,
  `unknown2014` double DEFAULT NULL,
  `unknown2015` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=172 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `periods`
--

DROP TABLE IF EXISTS `periods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `periods` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `year` smallint(4) unsigned DEFAULT '0',
  `month` tinyint(3) unsigned DEFAULT '0',
  `financial_year` smallint(4) unsigned DEFAULT '0',
  `quarter` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `identifier` (`year`,`month`),
  KEY `identifier_other` (`financial_year`,`quarter`),
  KEY `specific_time` (`financial_year`,`month`)
) ENGINE=InnoDB AUTO_INCREMENT=332 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `subcounties`
--

DROP TABLE IF EXISTS `subcounties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subcounties` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `SubCountyDHISCode` varchar(50) DEFAULT NULL,
  `SubCountyMFLCode` varchar(50) DEFAULT NULL,
  `SubCountyCoordinates` varchar(3070) DEFAULT NULL,
  `county` tinyint(3) unsigned DEFAULT NULL,
  `province` tinyint(3) unsigned DEFAULT NULL,
  `comment` varchar(32) DEFAULT NULL,
  `flag` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=306 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `surge_ages`
--

DROP TABLE IF EXISTS `surge_ages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `surge_ages` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `age` varchar(20) DEFAULT NULL,
  `age_name` varchar(20) DEFAULT NULL,
  `age_category_id` tinyint(3) unsigned NOT NULL DEFAULT '3',
  `max_age` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `no_gender` tinyint(1) unsigned DEFAULT '0',
  `for_surge` tinyint(1) unsigned DEFAULT '1',
  `for_vmmc` tinyint(1) unsigned DEFAULT '1',
  `for_tx_curr` tinyint(1) unsigned DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `age` (`age`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `surge_columns`
--

DROP TABLE IF EXISTS `surge_columns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `surge_columns` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `column_name` varchar(60) DEFAULT NULL,
  `alias_name` varchar(100) DEFAULT NULL,
  `excel_name` varchar(100) DEFAULT NULL,
  `gender_id` tinyint(3) unsigned DEFAULT '0',
  `age_id` tinyint(3) unsigned DEFAULT '0',
  `modality_id` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `column_name` (`column_name`),
  KEY `gender_id` (`gender_id`),
  KEY `age_id` (`age_id`),
  KEY `modality_id` (`modality_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1026 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `surge_columns_view`
--

DROP TABLE IF EXISTS `surge_columns_view`;
/*!50001 DROP VIEW IF EXISTS `surge_columns_view`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `surge_columns_view` AS SELECT 
 1 AS `id`,
 1 AS `column_name`,
 1 AS `alias_name`,
 1 AS `excel_name`,
 1 AS `gender_id`,
 1 AS `age_id`,
 1 AS `modality_id`,
 1 AS `age`,
 1 AS `age_name`,
 1 AS `age_category`,
 1 AS `age_category_id`,
 1 AS `no_gender`,
 1 AS `gender`,
 1 AS `modality`,
 1 AS `modality_name`,
 1 AS `tbl_name`,
 1 AS `hts`,
 1 AS `target`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `surge_genders`
--

DROP TABLE IF EXISTS `surge_genders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `surge_genders` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `gender` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gender` (`gender`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `surge_modalities`
--

DROP TABLE IF EXISTS `surge_modalities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `surge_modalities` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `modality` varchar(30) DEFAULT NULL,
  `modality_name` varchar(60) DEFAULT NULL,
  `tbl_name` varchar(30) DEFAULT NULL,
  `male` tinyint(1) unsigned DEFAULT '1',
  `female` tinyint(1) unsigned DEFAULT '1',
  `unknown` tinyint(1) unsigned DEFAULT '1',
  `hts` tinyint(1) unsigned DEFAULT '1',
  `target` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `modality` (`modality`),
  KEY `modality_name` (`modality_name`),
  KEY `tbl_name` (`tbl_name`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_and_counselling`
--

DROP TABLE IF EXISTS `t_and_counselling`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_and_counselling` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility` int(10) unsigned DEFAULT '0',
  `financial_year` smallint(4) unsigned DEFAULT '0',
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`,`financial_year`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_blood_safety`
--

DROP TABLE IF EXISTS `t_blood_safety`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_blood_safety` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility` int(10) unsigned DEFAULT '0',
  `financial_year` smallint(4) unsigned DEFAULT '0',
  `blood_units_screened_for_ttis` int(10) DEFAULT NULL,
  `donated_blood_units` int(10) DEFAULT NULL,
  `blood_units_reactive_to_hiv` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`,`financial_year`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=48509 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_care_and_treatment`
--

DROP TABLE IF EXISTS `t_care_and_treatment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_care_and_treatment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility` int(10) unsigned DEFAULT '0',
  `financial_year` smallint(4) unsigned DEFAULT '0',
  `female_15_years_and_older_screened_for_tb` int(10) DEFAULT NULL,
  `male_under_15yrs_starting_on_art` int(10) DEFAULT NULL,
  `pregnant_women_starting_on_art` int(10) DEFAULT NULL,
  `female_under_15_years_screened_for_tb` int(10) DEFAULT NULL,
  `total_enrolled_in_care` int(10) DEFAULT NULL,
  `male_below_15_years_screened_for_tb` int(10) DEFAULT NULL,
  `currently_on_art_-_male_below_15_years` int(10) DEFAULT NULL,
  `screened_for_sti’s` int(10) DEFAULT NULL,
  `on_alternative_1st_line_at_12_months_survival_and_retention_` int(10) DEFAULT NULL,
  `isoniazid_preventive_therapy_male_<_15_yrs` int(10) DEFAULT NULL,
  `total_screened_for_tb` int(10) DEFAULT NULL,
  `male_under_15yrs_currently_in_care` int(10) DEFAULT NULL,
  `female_under_15yrs_ever_on_art` int(10) DEFAULT NULL,
  `on_ctx_15_yrs_and_older_female` int(10) DEFAULT NULL,
  `male_15_years_and_older_screened_for_tb` int(10) DEFAULT NULL,
  `modern_contraceptive_methods` int(10) DEFAULT NULL,
  `under_1yr_revisit_on_art` int(10) DEFAULT NULL,
  `male_under_15yrs_revisit_on_art` int(10) DEFAULT NULL,
  `male_above_15yrs_currently_in_care` int(10) DEFAULT NULL,
  `screened_for_cervical_cancer_(females_18_years_and_older)` int(10) DEFAULT NULL,
  `currently_provided_with_a_minimum_package_of_pwp_services` int(10) DEFAULT NULL,
  `on_original_1st_line_at_12_months_survival_and_retention_on_` int(10) DEFAULT NULL,
  `female_under_15yrs_enrolled_in_care` int(10) DEFAULT NULL,
  `male_above_15yrs_revisit_on_art` int(10) DEFAULT NULL,
  `female_under_15yrs_starting_on_art` int(10) DEFAULT NULL,
  `total_revisit_on_art` int(10) DEFAULT NULL,
  `on_ctx_below_15_yrs_female` int(10) DEFAULT NULL,
  `total_hiv_care_visit` int(10) DEFAULT NULL,
  `female_above_15yrs_ever_on_art` int(10) DEFAULT NULL,
  `male_under_15yrs_ever_on_art` int(10) DEFAULT NULL,
  `under_1yr_currently_in_care` int(10) DEFAULT NULL,
  `total_on_therapy_at_12_months` int(10) DEFAULT NULL,
  `under_1yr_enrolled_in_care` int(10) DEFAULT NULL,
  `hiv_exposed_infant_(eligible_for_ctx_2_months)` int(10) DEFAULT NULL,
  `hiv_care_visit-_unscheduled` int(10) DEFAULT NULL,
  `female_above_15yrs_enrolled_in_care` int(10) DEFAULT NULL,
  `male_above_15yrs_ever_on_art` int(10) DEFAULT NULL,
  `under_1yr_starting_on_art` int(10) DEFAULT NULL,
  `isoniazid_preventive_therapy_female_>_15_yrs` int(10) DEFAULT NULL,
  `on_ctx_below_15_yrs_male` int(10) DEFAULT NULL,
  `female_under_15yrs_revisit_on_art` int(10) DEFAULT NULL,
  `linked_to_community_based_services5` int(10) DEFAULT NULL,
  `disclosed_their_hiv_status_to_sexual_partners` int(10) DEFAULT NULL,
  `on_ctx_15_y_and_older_male` int(10) DEFAULT NULL,
  `currently_on_art_-_male_above_15_years` int(10) DEFAULT NULL,
  `provided_with_adherence_counselling` int(10) DEFAULT NULL,
  `female_above_15yrs_revisit_on_art` int(10) DEFAULT NULL,
  `currently_on_art_-_below_1_year` int(10) DEFAULT NULL,
  `isoniazid_preventive_therapy_male_>_15_yrs` int(10) DEFAULT NULL,
  `hiv_exposed_infant_(within_2_months)_on_cotrimoxazole_prophy` int(10) DEFAULT NULL,
  `male_under_15yrs_enrolled_in_care` int(10) DEFAULT NULL,
  `hiv_care_visits_females_(18_years_and_older)` int(10) DEFAULT NULL,
  `linked_to_community_based_services2` int(10) DEFAULT NULL,
  `female_above_15yrs_starting_on_art` int(10) DEFAULT NULL,
  `total_ever_on_art` int(10) DEFAULT NULL,
  `female_under_15yrs_currently_in_care` int(10) DEFAULT NULL,
  `isoniazid_preventive_therapy_female_<_15_yrs` int(10) DEFAULT NULL,
  `provided_with_condoms` int(10) DEFAULT NULL,
  `total_starting_on_art` int(10) DEFAULT NULL,
  `knowledge_of_sexual_partners_hiv_status` int(10) DEFAULT NULL,
  `on_2nd_line_(or_higher)_at_12_months_survival_and_retention_` int(10) DEFAULT NULL,
  `currently_on_art_-_female_below_15_years` int(10) DEFAULT NULL,
  `visited_home_by_a_health_care_provider/hiv_clinic_peer_educa` int(10) DEFAULT NULL,
  `hiv_currently_in_care_-_above_15yrs_female` int(10) DEFAULT NULL,
  `currently_on_art_-_female_above_15_years` int(10) DEFAULT NULL,
  `hiv_care_visit_scheduled` int(10) DEFAULT NULL,
  `art_net_cohort_at_12_months_survival_and_retention_on_art` int(10) DEFAULT NULL,
  `total_currently_on_art` int(10) DEFAULT NULL,
  `male_above_15yrs_starting_on_art` int(10) DEFAULT NULL,
  `male_above_15yrs_&_older_enrolled_in_care` int(10) DEFAULT NULL,
  `total_on_ctx` int(10) DEFAULT NULL,
  `tb_patient_starting_on_art` int(10) DEFAULT NULL,
  `hiv_currently_in_care_-_total` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`,`financial_year`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=48509 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_hiv_and_tb_treatment`
--

DROP TABLE IF EXISTS `t_hiv_and_tb_treatment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_hiv_and_tb_treatment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility` int(10) unsigned DEFAULT '0',
  `financial_year` smallint(4) unsigned DEFAULT '0',
  `start_art_1-9_hv03-017` int(10) DEFAULT NULL,
  `enrolled_25pos_(f)_hv03-010` int(10) DEFAULT NULL,
  `tb_new_hiv_positive_hv03-080` int(10) DEFAULT NULL,
  `nutrition_assess_<15_hv03-067` int(10) DEFAULT NULL,
  `start_art_15-19_(f)_hv03-021` int(10) DEFAULT NULL,
  `screen_for_tb_1-9_hv03-052` int(10) DEFAULT NULL,
  `start_art_keypop_hv03-027` int(10) DEFAULT NULL,
  `on_art_20-24(m)_hv03-034` int(10) DEFAULT NULL,
  `enrolled_total_(sum_hv03-001_to_hv03-010)_hv03-011` int(10) DEFAULT NULL,
  `start_art_20-24_(f)_hv03-023` int(10) DEFAULT NULL,
  `tb_start_haart_hv03-083` int(10) DEFAULT NULL,
  `malnourished_total_hv03-072` int(10) DEFAULT NULL,
  `fbp_provided_total_hv03-075` int(10) DEFAULT NULL,
  `start_art_10-14(m)_hv03-018` int(10) DEFAULT NULL,
  `community_art_current_(f)_hv03-086` int(10) DEFAULT NULL,
  `enrolled_<1_hv03-001` int(10) DEFAULT NULL,
  `start_art_25pos(m)_hv03-024` int(10) DEFAULT NULL,
  `start_art_10-14_(f)_hv03-019` int(10) DEFAULT NULL,
  `on_art_<1_hv03-028` int(10) DEFAULT NULL,
  `tb_cases_new_hv03-076` int(10) DEFAULT NULL,
  `start_ipt_<1_hv03-059` int(10) DEFAULT NULL,
  `screen_for_tb_total_hv03-057` int(10) DEFAULT NULL,
  `enrolled_20-24_(f)_hv03-008` int(10) DEFAULT NULL,
  `completed_ipt_12months_hv03-066` int(10) DEFAULT NULL,
  `enrolled_20-24(m)_hv03-007` int(10) DEFAULT NULL,
  `malnourished_<15_hv03-070` int(10) DEFAULT NULL,
  `on_art_25pos(m)_hv03-036` int(10) DEFAULT NULL,
  `malnourished_15pos_hv03-071` int(10) DEFAULT NULL,
  `nutrition_assess_15pos_hv03-068` int(10) DEFAULT NULL,
  `start_ipt_1-9_hv03-060` int(10) DEFAULT NULL,
  `screen_for_tb_25pos_hv03-056` int(10) DEFAULT NULL,
  `on_art_12mths_hv03-040` int(10) DEFAULT NULL,
  `enrolled_in_care_keypop_hv03-012` int(10) DEFAULT NULL,
  `on_modern_fp_f18pos_hv03-089` int(10) DEFAULT NULL,
  `screen_cacx_new_f18pos_hv03-087` int(10) DEFAULT NULL,
  `on_art_20-24_(f)_hv03-035` int(10) DEFAULT NULL,
  `viral_load_<1000_12mths_hv03-042` int(10) DEFAULT NULL,
  `on_ctx/dds_<1_hv03-044` int(10) DEFAULT NULL,
  `start_ipt_15-19_hv03-062` int(10) DEFAULT NULL,
  `start_ipt_25pos_hv03-064` int(10) DEFAULT NULL,
  `on_ctx/dds_25pos_hv03-049` int(10) DEFAULT NULL,
  `community_art_current(m)_hv03-085` int(10) DEFAULT NULL,
  `on_art_15-19_(f)_hv03-033` int(10) DEFAULT NULL,
  `start_art_20-24(m)_hv03-022` int(10) DEFAULT NULL,
  `start_ipt_total_hv03-065` int(10) DEFAULT NULL,
  `fbp_provided_<15_hv03-073` int(10) DEFAULT NULL,
  `enrolled_1-9_hv03-002` int(10) DEFAULT NULL,
  `on_ctx/dds_1-9_hv03-045` int(10) DEFAULT NULL,
  `start_ipt_10-14_hv03-061` int(10) DEFAULT NULL,
  `enrolled_15-19(m)_hv03-005` int(10) DEFAULT NULL,
  `start_art_25pos_(f)_hv03-025` int(10) DEFAULT NULL,
  `on_art_10-14(m)_hv03-030` int(10) DEFAULT NULL,
  `enrolled_15-19_(f)_hv03-006` int(10) DEFAULT NULL,
  `on_ctx/dds_15-19_hv03-047` int(10) DEFAULT NULL,
  `tb_cases_total_hivpos_(hv03-077pos080)_hv03-081` int(10) DEFAULT NULL,
  `start_ipt_20-24_hv03-063` int(10) DEFAULT NULL,
  `fbp_provided_15pos_hv03-074` int(10) DEFAULT NULL,
  `start_art_15-19(m)_hv03-020` int(10) DEFAULT NULL,
  `enrolled_10-14_(f)_hv03-004` int(10) DEFAULT NULL,
  `viral_load_result_12mths_hv03-043` int(10) DEFAULT NULL,
  `nutrition_assess_total_hv03-069` int(10) DEFAULT NULL,
  `tb_known_status_hv03-079` int(10) DEFAULT NULL,
  `screen_for_tb_10-14_hv03-053` int(10) DEFAULT NULL,
  `on_art_25pos_(f)_hv03-037` int(10) DEFAULT NULL,
  `tb_cases_known_positive(kps)_hv03-077` int(10) DEFAULT NULL,
  `on_art_15-19(m)_hv03-032` int(10) DEFAULT NULL,
  `on_art_10-14_(f)_hv03-031` int(10) DEFAULT NULL,
  `in_pre_art_0-14_hv03-013` int(10) DEFAULT NULL,
  `enrolled_10-14(m)_hv03-003` int(10) DEFAULT NULL,
  `on_art_1-9_hv03-029` int(10) DEFAULT NULL,
  `screen_for_tb_20-24_hv03-055` int(10) DEFAULT NULL,
  `on_ctx/dds_20-24_hv03-048` int(10) DEFAULT NULL,
  `tb_already_on_haart_hv03-082` int(10) DEFAULT NULL,
  `in_pre_art_15pos_hv03-014` int(10) DEFAULT NULL,
  `on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038` int(10) DEFAULT NULL,
  `screen_for_tb_15-19_hv03-054` int(10) DEFAULT NULL,
  `on_ctx/dds_10-14_hv03-046` int(10) DEFAULT NULL,
  `on_art_keypop_(hiv3-038_plus_hiv3-050)_hv03-039` int(10) DEFAULT NULL,
  `tb_cases_tested_hiv_hv03-078` int(10) DEFAULT NULL,
  `clinical_visits_f18pos_hv03-088` int(10) DEFAULT NULL,
  `tb_total_on_haart(hv03-082pos083)_hv03-084` int(10) DEFAULT NULL,
  `presumed_tb_total_hv03-058` int(10) DEFAULT NULL,
  `in_pre_art_total(hv03-13poshv03-14_hv03-015` int(10) DEFAULT NULL,
  `screen_for_tb_<1_hv03-051` int(10) DEFAULT NULL,
  `on_ctx/dds_total_hv03-050` int(10) DEFAULT NULL,
  `net_cohort_12mths_hv03-041` int(10) DEFAULT NULL,
  `start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026` int(10) DEFAULT NULL,
  `start_art_<1_hv03-016` int(10) DEFAULT NULL,
  `enrolled_25pos(m)_hv03-009` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`,`financial_year`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=48509 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_hiv_counselling_and_testing`
--

DROP TABLE IF EXISTS `t_hiv_counselling_and_testing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_hiv_counselling_and_testing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility` int(10) unsigned DEFAULT '0',
  `financial_year` smallint(4) unsigned DEFAULT '0',
  `discordant_couples_receiving_results` int(10) DEFAULT NULL,
  `male_15-24yrs_receiving_hiv_pos_results` int(10) DEFAULT NULL,
  `concordant_couples_receiving_results` int(10) DEFAULT NULL,
  `first_testing_hiv` int(10) DEFAULT NULL,
  `total_received_hivpos_results` int(10) DEFAULT NULL,
  `female_above_25yrs_receiving_hiv_pos_results` int(10) DEFAULT NULL,
  `couples_testing` int(10) DEFAULT NULL,
  `male_under_15yrs_receiving_hiv_pos_results` int(10) DEFAULT NULL,
  `outreach_testing_hiv` int(10) DEFAULT NULL,
  `female_under_15yrs_receiving_hiv_pos_results` int(10) DEFAULT NULL,
  `male_above_25yrs_receiving_hiv_pos_results` int(10) DEFAULT NULL,
  `repeat_testing_hiv` int(10) DEFAULT NULL,
  `total_tested_hiv` int(10) DEFAULT NULL,
  `static_testing_hiv_(health_facility)` int(10) DEFAULT NULL,
  `female_15-24yrs_receiving_hiv_pos_results` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`,`financial_year`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=48509 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_hiv_testing_and_prevention_services`
--

DROP TABLE IF EXISTS `t_hiv_testing_and_prevention_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_hiv_testing_and_prevention_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility` int(10) unsigned DEFAULT '0',
  `financial_year` smallint(4) unsigned DEFAULT '0',
  `tested_25pos_(m)_hv01-08` int(10) DEFAULT NULL,
  `assessed_25-29_yrs(m)_hv01-41` int(10) DEFAULT NULL,
  `assesed_15-19_yrs(m)_hv01-37` int(10) DEFAULT NULL,
  `15-24_(m)_hv01-46` int(10) DEFAULT NULL,
  `tested_new_hv01-13` int(10) DEFAULT NULL,
  `linked_10-14_hv01-31` int(10) DEFAULT NULL,
  `tested_facility_hv01-11` int(10) DEFAULT NULL,
  `tested_1-9_hv01-01` int(10) DEFAULT NULL,
  `positive_keypop_hv01-29` int(10) DEFAULT NULL,
  `total_assessded_for_hiv_risk_(_hv01-39_-_hv01-45)_hv01-45` int(10) DEFAULT NULL,
  `tested_15-19(f)_hv01-05` int(10) DEFAULT NULL,
  `total_tested_positive_(3_months_ago)_hv01-36` int(10) DEFAULT NULL,
  `tested_10-14_(m)_hv01-02` int(10) DEFAULT NULL,
  `tested_10-14(f)_hv01-03` int(10) DEFAULT NULL,
  `assessed_20-24_yrs(m)_hv01-39` int(10) DEFAULT NULL,
  `25pos_(m)_hv01-48` int(10) DEFAULT NULL,
  `tested_community_hv01-12` int(10) DEFAULT NULL,
  `assesed_15-19_yrs(f)_hv01-38` int(10) DEFAULT NULL,
  `tested_25pos_(f)_hv01-09` int(10) DEFAULT NULL,
  `tested_20-24(f)_hv01-07` int(10) DEFAULT NULL,
  `positive_1-9_hv01-17` int(10) DEFAULT NULL,
  `linked_1-9_yrs_hv01-30` int(10) DEFAULT NULL,
  `15-24_(f)_hv01-47` int(10) DEFAULT NULL,
  `positive_25pos(f)_hv01-25` int(10) DEFAULT NULL,
  `positive_10-14(m)_hv01-18` int(10) DEFAULT NULL,
  `positive_20-24(m)_hv01-22` int(10) DEFAULT NULL,
  `tested_20-24(m)_hv01-06` int(10) DEFAULT NULL,
  `positive_25pos(m)_hv01-24` int(10) DEFAULT NULL,
  `tested_keypop_hv01-16` int(10) DEFAULT NULL,
  `linked_total_hv01-35` int(10) DEFAULT NULL,
  `25pos_(f)_hv01-49` int(10) DEFAULT NULL,
  `total_hv01-50` int(10) DEFAULT NULL,
  `positive_total_(sum_hv01-18_to_hv01-27)_hv01-26` int(10) DEFAULT NULL,
  `positive_15-19(f)_hv01-21` int(10) DEFAULT NULL,
  `assessed_25-29_yrs(f)_hv01-42` int(10) DEFAULT NULL,
  `positive_15-19(m)_hv01-20` int(10) DEFAULT NULL,
  `positive_10-14(f)_hv01-19` int(10) DEFAULT NULL,
  `discordant_hv01-28` int(10) DEFAULT NULL,
  `assessed_20-24_yrs(f)_hv01-40` int(10) DEFAULT NULL,
  `linked_25pos_hv01-34` int(10) DEFAULT NULL,
  `tested_total_(sum_hv01-01_to_hv01-10)_hv01-10` int(10) DEFAULT NULL,
  `linked_15-19_hv01-32` int(10) DEFAULT NULL,
  `tested_15-19_(m)_hv01-04` int(10) DEFAULT NULL,
  `assessed_30_yrs_&_older(f)_hv01-44` int(10) DEFAULT NULL,
  `negative_total_hv01-27` int(10) DEFAULT NULL,
  `assessed_30_yrs_&_older(m)_hv01-43` int(10) DEFAULT NULL,
  `tested_repeat_hv01-14` int(10) DEFAULT NULL,
  `linked_20-24_hv01-33` int(10) DEFAULT NULL,
  `tested_couples_hv01-15` int(10) DEFAULT NULL,
  `positive_20-24(f)_hv01-23` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`,`financial_year`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=48509 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_key_populations_monthly_summary`
--

DROP TABLE IF EXISTS `t_key_populations_monthly_summary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_key_populations_monthly_summary` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility` int(10) unsigned DEFAULT '0',
  `financial_year` smallint(4) unsigned DEFAULT '0',
  `no_diagnosed` int(10) DEFAULT NULL,
  `treated_hcv` int(10) DEFAULT NULL,
  `started_art_20_-_24(onsite)` int(10) DEFAULT NULL,
  `linked_25_-_29` int(10) DEFAULT NULL,
  `active_20_-_24` int(10) DEFAULT NULL,
  `no_of_clients_discontinued_voluntary` int(10) DEFAULT NULL,
  `started_art_20_-_24(offsite)` int(10) DEFAULT NULL,
  `no_mat_clients_on_tb_treatment` int(10) DEFAULT NULL,
  `active_15_-_19` int(10) DEFAULT NULL,
  `positive_30pos` int(10) DEFAULT NULL,
  `negative_hbv_vaccinated` int(10) DEFAULT NULL,
  `turning_hiv_positive_while_on_prep` int(10) DEFAULT NULL,
  `treated_hbv` int(10) DEFAULT NULL,
  `tested_25_-_29` int(10) DEFAULT NULL,
  `on_art_at_12mnths` int(10) DEFAULT NULL,
  `viral_load_<1000_12mths(onsite)` int(10) DEFAULT NULL,
  `number_receiving_needles_&_syringes_per_need` int(10) DEFAULT NULL,
  `started_art_30pos(offsite)` int(10) DEFAULT NULL,
  `receiving_clinical_services` int(10) DEFAULT NULL,
  `currently_on_art_20_-_24(offsite)` int(10) DEFAULT NULL,
  `no_of_clients_on_>_120mg_dose` int(10) DEFAULT NULL,
  `receiving_violence_support` int(10) DEFAULT NULL,
  `initiated_prep` int(10) DEFAULT NULL,
  `number_receiving_pep_<72hrs` int(10) DEFAULT NULL,
  `no_of_clients_on_60-120mg_dose` int(10) DEFAULT NULL,
  `no_mat_clients_on_2nd_line_art` int(10) DEFAULT NULL,
  `number_receiving_condoms_per_need` int(10) DEFAULT NULL,
  `on_pre-art_25_-_29(onsite)` int(10) DEFAULT NULL,
  `currently_on_art_30pos(onsite)` int(10) DEFAULT NULL,
  `no_of_clients_missing_>_5_consecutive_mat_doses` int(10) DEFAULT NULL,
  `number_completed_pep_within_28days` int(10) DEFAULT NULL,
  `positive_15_-_19` int(10) DEFAULT NULL,
  `started_art_15_-_19(offsite)` int(10) DEFAULT NULL,
  `net_cohot_at_12mnths` int(10) DEFAULT NULL,
  `linked_15_-_19` int(10) DEFAULT NULL,
  `on_pre-art_30pos(offsite)` int(10) DEFAULT NULL,
  `active_30pos` int(10) DEFAULT NULL,
  `tb_clients_on_haart` int(10) DEFAULT NULL,
  `number_exposed` int(10) DEFAULT NULL,
  `no_mat_clients_on_1st_line_art` int(10) DEFAULT NULL,
  `current_on_prep` int(10) DEFAULT NULL,
  `on_pre-art_20_-_24(onsite)` int(10) DEFAULT NULL,
  `currently_on_art_15_-_19(offsite)` int(10) DEFAULT NULL,
  `currently_on_art_25_-_29(onsite)` int(10) DEFAULT NULL,
  `on_pre-art_15_-_19(offsite)` int(10) DEFAULT NULL,
  `no_newly_enrolled_mat` int(10) DEFAULT NULL,
  `active_25_-_29` int(10) DEFAULT NULL,
  `positive_hcv` int(10) DEFAULT NULL,
  `number_screened_hbv` int(10) DEFAULT NULL,
  `tested_facility` int(10) DEFAULT NULL,
  `on_pre-art_15_-_19(onsite)` int(10) DEFAULT NULL,
  `tested_repeat` int(10) DEFAULT NULL,
  `on_pre-art_25_-_29(offsite)` int(10) DEFAULT NULL,
  `tested_new_(1st_testers)` int(10) DEFAULT NULL,
  `viral_load_result_12mths(offsite)` int(10) DEFAULT NULL,
  `no_ever_enrolled_on_mat` int(10) DEFAULT NULL,
  `currently_on_art_20_-_24(onsite)` int(10) DEFAULT NULL,
  `currently_on_art_15_-_19(onsite)` int(10) DEFAULT NULL,
  `on_pre-art_20_-_24(offsite)` int(10) DEFAULT NULL,
  `linked_30pos` int(10) DEFAULT NULL,
  `started_art_25_-_29(onsite)` int(10) DEFAULT NULL,
  `linked_20_-_24` int(10) DEFAULT NULL,
  `no_currently_on_mat_(active)` int(10) DEFAULT NULL,
  `number_screened_hcv` int(10) DEFAULT NULL,
  `tested_30pos` int(10) DEFAULT NULL,
  `tested_community` int(10) DEFAULT NULL,
  `dignosed_sti` int(10) DEFAULT NULL,
  `positive_20_-_24` int(10) DEFAULT NULL,
  `average_dose_overall_(mg)` int(10) DEFAULT NULL,
  `number_receiving_condoms` int(10) DEFAULT NULL,
  `started_art_30pos(onsite)` int(10) DEFAULT NULL,
  `experiencing_violence` int(10) DEFAULT NULL,
  `positive_25_-_29` int(10) DEFAULT NULL,
  `started_art_25_-_29(offsite)` int(10) DEFAULT NULL,
  `receiving_peer_education` int(10) DEFAULT NULL,
  `number_receiving_needles_&_syringes` int(10) DEFAULT NULL,
  `started_art_15_-_19(onsite)` int(10) DEFAULT NULL,
  `started_on_tb_tx` int(10) DEFAULT NULL,
  `positive_hbv` int(10) DEFAULT NULL,
  `tested_15_-_19` int(10) DEFAULT NULL,
  `tested_20_-_24` int(10) DEFAULT NULL,
  `currently_on_art_30pos(offsite)` int(10) DEFAULT NULL,
  `viral_load_<1000_12mths(offsite)` int(10) DEFAULT NULL,
  `viral_load_result_12mths(onsite)` int(10) DEFAULT NULL,
  `number_receiving_lubricants_per_need` int(10) DEFAULT NULL,
  `known_positives_(active)` int(10) DEFAULT NULL,
  `no_of_clients_discontinued_involuntary` int(10) DEFAULT NULL,
  `number_receiving_lubricants` int(10) DEFAULT NULL,
  `treated_sti` int(10) DEFAULT NULL,
  `on_pre-art_30pos(onsite)` int(10) DEFAULT NULL,
  `currently_on_art_25_-_29(offsite)` int(10) DEFAULT NULL,
  `number_screened_sti` int(10) DEFAULT NULL,
  `no_of_clients_weaned_off_methadone` int(10) DEFAULT NULL,
  `no_screened` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`,`financial_year`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=48509 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_medical_male_circumcision`
--

DROP TABLE IF EXISTS `t_medical_male_circumcision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_medical_male_circumcision` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility` int(10) unsigned DEFAULT '0',
  `financial_year` smallint(4) unsigned DEFAULT '0',
  `circumcised_hiv-_hv04-09` int(10) DEFAULT NULL,
  `circumcised_hivpos_hv04-08` int(10) DEFAULT NULL,
  `follow_up_visit_<14d_hv04-17` int(10) DEFAULT NULL,
  `circumcised_25pos_hv04-06` int(10) DEFAULT NULL,
  `circumcised_hiv_nk_hv04-10` int(10) DEFAULT NULL,
  `circumcised_10-14_hv04-03` int(10) DEFAULT NULL,
  `ae_post_moderate_hv04-15` int(10) DEFAULT NULL,
  `ae_post_severe_hv04-16` int(10) DEFAULT NULL,
  `surgical_hv04-11` int(10) DEFAULT NULL,
  `ae_during_moderate_hv04-13` int(10) DEFAULT NULL,
  `circumcised_total_hv04-07` int(10) DEFAULT NULL,
  `ae_during_severe_hv04-14` int(10) DEFAULT NULL,
  `circumcised_20-24_hv04-05` int(10) DEFAULT NULL,
  `devices_hv04-12` int(10) DEFAULT NULL,
  `circumcised_1-9yr_hv04-02` int(10) DEFAULT NULL,
  `circumcised_<1_hv04-01` int(10) DEFAULT NULL,
  `circumcised_15-19_hv04-04` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`,`financial_year`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=48509 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_methadone_assisted_therapy`
--

DROP TABLE IF EXISTS `t_methadone_assisted_therapy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_methadone_assisted_therapy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility` int(10) unsigned DEFAULT '0',
  `financial_year` smallint(4) unsigned DEFAULT '0',
  `keypop_on_mat_hv06-01` int(10) DEFAULT NULL,
  `keypop_who_are_pwid_hv06-04` int(10) DEFAULT NULL,
  `mat_clients_hivpos_hv06-02` int(10) DEFAULT NULL,
  `hivpos_mat_clients_on_art_hv06-03` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`,`financial_year`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=48509 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_non_mer`
--

DROP TABLE IF EXISTS `t_non_mer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_non_mer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility` int(10) unsigned DEFAULT '0',
  `financial_year` smallint(4) unsigned DEFAULT '0',
  `viremia_beneficiaries` int(10) DEFAULT NULL,
  `viremia_target` int(10) DEFAULT NULL,
  `dsd_beneficiaries` int(10) DEFAULT NULL,
  `dsd_target` int(10) DEFAULT NULL,
  `otz_beneficiaries` int(10) DEFAULT NULL,
  `otz_target` int(10) DEFAULT NULL,
  `men_clinic_beneficiaries` int(10) DEFAULT NULL,
  `men_clinic_target` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`,`financial_year`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=48509 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_plus_prep_summary_reporting_tool`
--

DROP TABLE IF EXISTS `t_plus_prep_summary_reporting_tool`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_plus_prep_summary_reporting_tool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility` int(10) unsigned DEFAULT '0',
  `financial_year` smallint(4) unsigned DEFAULT '0',
  `tested_hiv_positive_while_on_prep_pwid` int(10) DEFAULT NULL,
  `continuing_(refills)_prep_fsw` int(10) DEFAULT NULL,
  `diagnosed_with_sti_prep_fsw` int(10) DEFAULT NULL,
  `continuing_(refills)_prep_total` int(10) DEFAULT NULL,
  `restarting_prep_msm` int(10) DEFAULT NULL,
  `eligible_prep_total` int(10) DEFAULT NULL,
  `discontinued_prep_pwid` int(10) DEFAULT NULL,
  `discontinued_prep_discordant_couple` int(10) DEFAULT NULL,
  `diagnosed_with_sti_prep_pwid` int(10) DEFAULT NULL,
  `diagnosed_with_sti_prep_msm` int(10) DEFAULT NULL,
  `diagnosed_with_sti_prep_total` int(10) DEFAULT NULL,
  `eligible_prep_general_popn` int(10) DEFAULT NULL,
  `continuing_(refills)_prep_pwid` int(10) DEFAULT NULL,
  `discontinued_prep_total` int(10) DEFAULT NULL,
  `diagnosed_with_sti_prep_discordant_couple` int(10) DEFAULT NULL,
  `currently_on_prep_(_new_pos_refillpos_restart)_discordant_co` int(10) DEFAULT NULL,
  `continuing_(refills)_prep_general_popn` int(10) DEFAULT NULL,
  `eligible_prep_fsw` int(10) DEFAULT NULL,
  `diagnosed_with_sti_prep_general_popn` int(10) DEFAULT NULL,
  `currently_on_prep_(_new_pos_refillpos_restart)_total` int(10) DEFAULT NULL,
  `continuing_(refills)_prep_discordant_couple` int(10) DEFAULT NULL,
  `initiated_(new)_prep_fsw` int(10) DEFAULT NULL,
  `currently_on_prep_(_new_pos_refillpos_restart)_general_popn` int(10) DEFAULT NULL,
  `currently_on_prep_(_new_pos_refillpos_restart)_pwid` int(10) DEFAULT NULL,
  `tested_hiv_positive_while_on_prep_general_popn` int(10) DEFAULT NULL,
  `continuing_(refills)_prep_msm` int(10) DEFAULT NULL,
  `currently_on_prep_(_new_pos_refillpos_restart)_fsw` int(10) DEFAULT NULL,
  `discontinued_prep_msm` int(10) DEFAULT NULL,
  `eligible_prep_discordant_couple` int(10) DEFAULT NULL,
  `initiated_(new)_prep_msm` int(10) DEFAULT NULL,
  `restarting_prep_discordant_couple` int(10) DEFAULT NULL,
  `discontinued_prep_fsw` int(10) DEFAULT NULL,
  `eligible_prep_pwid` int(10) DEFAULT NULL,
  `tested_hiv_positive_while_on_prep_fsw` int(10) DEFAULT NULL,
  `restarting_prep_pwid` int(10) DEFAULT NULL,
  `restarting_prep_general_popn` int(10) DEFAULT NULL,
  `initiated_(new)_prep_discordant_couple` int(10) DEFAULT NULL,
  `tested_hiv_positive_while_on_prep_msm` int(10) DEFAULT NULL,
  `tested_hiv_positive_while_on_prep_total` int(10) DEFAULT NULL,
  `currently_on_prep_(_new_pos_refillpos_restart)_msm` int(10) DEFAULT NULL,
  `restarting_prep_total` int(10) DEFAULT NULL,
  `tested_hiv_positive_while_on_prep_discordant_couple` int(10) DEFAULT NULL,
  `initiated_(new)_prep_pwid` int(10) DEFAULT NULL,
  `discontinued_prep_general_popn` int(10) DEFAULT NULL,
  `initiated_(new)_prep_total` int(10) DEFAULT NULL,
  `initiated_(new)_prep_general_popn` int(10) DEFAULT NULL,
  `restarting_prep_fsw` int(10) DEFAULT NULL,
  `eligible_prep_msm` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`,`financial_year`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=48509 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_pmtct`
--

DROP TABLE IF EXISTS `t_pmtct`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_pmtct` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility` int(10) unsigned DEFAULT '0',
  `financial_year` smallint(4) unsigned DEFAULT '0',
  `not_bf_(12_months)_infant_feeding` int(10) DEFAULT NULL,
  `total_infants_issued_prophylaxis` int(10) DEFAULT NULL,
  `antenatal_positive_to_hiv_test` int(10) DEFAULT NULL,
  `total_exposed_aged_6_months` int(10) DEFAULT NULL,
  `started_on_art_during_anc` int(10) DEFAULT NULL,
  `pnc_(<72hrs)_(infant_arv_prophylaxis)` int(10) DEFAULT NULL,
  `prophylaxis_-_(aztpossdnvp)` int(10) DEFAULT NULL,
  `erf_(6_months)_infant_feeding` int(10) DEFAULT NULL,
  `serology_(from_9_to_12_months)_infant_testing_(initial_test_` int(10) DEFAULT NULL,
  `postnatal_(within_72hrs)_postive_to_hiv_test` int(10) DEFAULT NULL,
  `issued_in_anc_(infant_arv_prophylaxis)` int(10) DEFAULT NULL,
  `total_exposed_12_months` int(10) DEFAULT NULL,
  `total_confirmed_positive_infant_test_result_by_pcr` int(10) DEFAULT NULL,
  `pcr_(within_2_months)_infant_testing_(initial_test_only)` int(10) DEFAULT NULL,
  `discordant_couples_partner_involvement` int(10) DEFAULT NULL,
  `haart_(art)` int(10) DEFAULT NULL,
  `labour_and_delivery_testing_for_hiv` int(10) DEFAULT NULL,
  `known_positive_status_(at_entry_into_anc)` int(10) DEFAULT NULL,
  `antenatal_testing_for_hiv` int(10) DEFAULT NULL,
  `bf_(at_12_months)_infant_feeding` int(10) DEFAULT NULL,
  `total_tested_(pmtct)` int(10) DEFAULT NULL,
  `assessed_eligibility_in_anc` int(10) DEFAULT NULL,
  `pcr_(9_to_12_months)_confirmed_infant_test_results_positive` int(10) DEFAULT NULL,
  `not_known_infant_feeding_(12_months)` int(10) DEFAULT NULL,
  `total_hei_tested_by_12_months` int(10) DEFAULT NULL,
  `postnatal_(within_72hrs)_testing_for_hiv` int(10) DEFAULT NULL,
  `assessed_for_eligibility_in_1st_anc_-_who_staging_done` int(10) DEFAULT NULL,
  `labour_and_delivery_postive_to_hiv_test` int(10) DEFAULT NULL,
  `labour_and_delivery_(infant_arv_prophylaxis)` int(10) DEFAULT NULL,
  `prophylaxis_–_haart` int(10) DEFAULT NULL,
  `male_partners_tested_-(_anc/l&d)` int(10) DEFAULT NULL,
  `ebf_(6_months)_infant_feeding` int(10) DEFAULT NULL,
  `total_pmtct_prophylaxis` int(10) DEFAULT NULL,
  `pcr_(by_2_months)_confirmed_infant_test_results_positive` int(10) DEFAULT NULL,
  `pcr_(3_to_8_months)_confirmed_infant_test_results_positive` int(10) DEFAULT NULL,
  `pcr_(from3_to_8_months)_infant_testing_(initial_test_only)` int(10) DEFAULT NULL,
  `prophylaxis-nvp_only` int(10) DEFAULT NULL,
  `total_positive_(pmtct)` int(10) DEFAULT NULL,
  `mf_(6_months)_infant_feeding` int(10) DEFAULT NULL,
  `prophylaxis_-_interrupted_haart` int(10) DEFAULT NULL,
  `pcr_(from_9_to_12_months)_infant_testing_(initial_test_only)` int(10) DEFAULT NULL,
  `assessed_for_eligibility_in_1st_anc_-_cd4` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`,`financial_year`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=48509 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_post-exposure_prophylaxis`
--

DROP TABLE IF EXISTS `t_post-exposure_prophylaxis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_post-exposure_prophylaxis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility` int(10) unsigned DEFAULT '0',
  `financial_year` smallint(4) unsigned DEFAULT '0',
  `sexual_assault_male_type_of_exposure` int(10) DEFAULT NULL,
  `occupational_female_provided_with_prophylaxis` int(10) DEFAULT NULL,
  `occupational_female_type_of_exposure` int(10) DEFAULT NULL,
  `other_reasons_female_type_of_exposure` int(10) DEFAULT NULL,
  `other_reasons_–_female_-provided_with_prophylaxis` int(10) DEFAULT NULL,
  `occupational_male_provided_with_prophylaxis` int(10) DEFAULT NULL,
  `total_pep` int(10) DEFAULT NULL,
  `sexual_assault_female_type_of_exposure` int(10) DEFAULT NULL,
  `occupational_male_type_of_exposure` int(10) DEFAULT NULL,
  `total_type_of_exposure` int(10) DEFAULT NULL,
  `other_reasons_–_male_-_provided_with_prophylaxis` int(10) DEFAULT NULL,
  `sexual_assault_female_provided_with_prophylaxis` int(10) DEFAULT NULL,
  `other_reasons_male_type_of_exposure` int(10) DEFAULT NULL,
  `sexual_assault_male_provided_with_prophylaxis` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`,`financial_year`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=48509 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_post_exposure_prophylaxis`
--

DROP TABLE IF EXISTS `t_post_exposure_prophylaxis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_post_exposure_prophylaxis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility` int(10) unsigned DEFAULT '0',
  `financial_year` smallint(4) unsigned DEFAULT '0',
  `pep_other_hv05-06` int(10) DEFAULT NULL,
  `pep_occupational_hv05-05` int(10) DEFAULT NULL,
  `exposed_total_hv05-03` int(10) DEFAULT NULL,
  `exposed_other_hv05-02` int(10) DEFAULT NULL,
  `exposed_occupational_hv05-01` int(10) DEFAULT NULL,
  `pep_total_hv05-07` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`,`financial_year`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=48509 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_prevention_of_mother-to-child_transmission`
--

DROP TABLE IF EXISTS `t_prevention_of_mother-to-child_transmission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_prevention_of_mother-to-child_transmission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility` int(10) unsigned DEFAULT '0',
  `financial_year` smallint(4) unsigned DEFAULT '0',
  `total_positive_(add_hv02-10_-_hv02-14)_hv02-15` int(10) DEFAULT NULL,
  `erf_(at_6_months)_hv02-54` int(10) DEFAULT NULL,
  `known_exposure_at_penta_1_hv02-37` int(10) DEFAULT NULL,
  `not_bf_(at_18_months)_hv02-59` int(10) DEFAULT NULL,
  `hei_ctx/dds_start_<2months_hv02-43` int(10) DEFAULT NULL,
  `mf_(at_6_months)_hv02-55` int(10) DEFAULT NULL,
  `initial_test_at_l&d_hv02-05` int(10) DEFAULT NULL,
  `positive_results_anc_hv02-11` int(10) DEFAULT NULL,
  `not_bf_(at_12_months)_hv02-57` int(10) DEFAULT NULL,
  `infected_24mths_hv02-47` int(10) DEFAULT NULL,
  `known_status_1st_contact_hv02-29` int(10) DEFAULT NULL,
  `initial_pcr_<_8wks_hv02-44` int(10) DEFAULT NULL,
  `hivpos_on_modern_fp_at_6wks_hv02-27` int(10) DEFAULT NULL,
  `pair_net_cohort_24_months_hv02-52` int(10) DEFAULT NULL,
  `infant_arv_prophyl_l&d_hv02-40` int(10) DEFAULT NULL,
  `known_hiv_status_total_hv02-07` int(10) DEFAULT NULL,
  `known_positive_at_1st_anc_hv02-10` int(10) DEFAULT NULL,
  `infant_arv_prophyl<8wks_pnc_hv02-41` int(10) DEFAULT NULL,
  `syphilis_screened_1st_anc_hv02-24` int(10) DEFAULT NULL,
  `unknown_outcome_hv02-49` int(10) DEFAULT NULL,
  `total_arv_prophylaxis_hv02-42` int(10) DEFAULT NULL,
  `bf_(_at18_months)_hv02-58` int(10) DEFAULT NULL,
  `positive_result_adolescents_total_hv02-35` int(10) DEFAULT NULL,
  `total_known_status_male_hv02-33` int(10) DEFAULT NULL,
  `ebf_(at_6_months)_hv02-53` int(10) DEFAULT NULL,
  `initial_test_at_anc_hv02-04` int(10) DEFAULT NULL,
  `positive_results_pnc<=6wks_hv02-13` int(10) DEFAULT NULL,
  `net_cohort_hei_24_months_hv02-50` int(10) DEFAULT NULL,
  `start_haart_pnc>_6weeks_to_6_months_hv02-21` int(10) DEFAULT NULL,
  `start_haart_l&d_hv02-18` int(10) DEFAULT NULL,
  `delivery_from_hivpos_mothers_hv02-02` int(10) DEFAULT NULL,
  `positive_results_l&d_hv02-12` int(10) DEFAULT NULL,
  `initial_test_at_pnc_pnc<=6wks_hv02-06` int(10) DEFAULT NULL,
  `initial_test_at_anc_male_hv02-30` int(10) DEFAULT NULL,
  `on_maternal_haart_12mths_hv02-22` int(10) DEFAULT NULL,
  `mother-baby_pairs_24mths_hv02-51` int(10) DEFAULT NULL,
  `initial_pcr_>8wks_-12_mths_hv02-45` int(10) DEFAULT NULL,
  `syphilis_treated_hv02-26` int(10) DEFAULT NULL,
  `initial_pcr_test<12mths_total_hv02-46` int(10) DEFAULT NULL,
  `1st_anc_visits_hv02-01` int(10) DEFAULT NULL,
  `1st_anc_kp_adolescents_(10-19)_hv02-34` int(10) DEFAULT NULL,
  `start_haart_anc_hv02-17` int(10) DEFAULT NULL,
  `syphilis_screened_positive_hv02-25` int(10) DEFAULT NULL,
  `on_maternal_haart_total_hv02-20` int(10) DEFAULT NULL,
  `net_cohort_12_mths_hv02-23` int(10) DEFAULT NULL,
  `started_haart_adolescents_total_hv02-36` int(10) DEFAULT NULL,
  `retesting_pnc<=_6_weeks_hv02-08` int(10) DEFAULT NULL,
  `total_tested_positive_(3_months_ago)_hv01-36` int(10) DEFAULT NULL,
  `bf_(at_12_months)_hv02-56` int(10) DEFAULT NULL,
  `total_given_penta_1_hv02-38` int(10) DEFAULT NULL,
  `initial_test_at_l&d_male_hv02-31` int(10) DEFAULT NULL,
  `known_positive_at_1st_anc_hv02-03` int(10) DEFAULT NULL,
  `positive_pnc>_6weeks_to_6_months_hv02-14` int(10) DEFAULT NULL,
  `uninfected_24mths_hv02-48` int(10) DEFAULT NULL,
  `initial_test_at_pnc_male_hv02-32` int(10) DEFAULT NULL,
  `tested_pnc>_6weeks_to_6_months_hv02-09` int(10) DEFAULT NULL,
  `hivpos_pnc_visits_at_6wks_hv02-28` int(10) DEFAULT NULL,
  `infant_arv_prophyl_anc_hv02-39` int(10) DEFAULT NULL,
  `start_haart_pnc<=6wks_hv02-19` int(10) DEFAULT NULL,
  `on_haart_at_1st_anc_hv02-16` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`,`financial_year`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=48509 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_voluntary_male_circumcision`
--

DROP TABLE IF EXISTS `t_voluntary_male_circumcision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_voluntary_male_circumcision` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility` int(10) unsigned DEFAULT '0',
  `financial_year` smallint(4) unsigned DEFAULT '0',
  `circumcised_15-24_yrs` int(10) DEFAULT NULL,
  `post_-_ae(s)_severe_adverse_events_(circumcision)` int(10) DEFAULT NULL,
  `negative_-hiv_status_(at_circumcision)` int(10) DEFAULT NULL,
  `total_circumcised` int(10) DEFAULT NULL,
  `circumcised_0-14_yrs` int(10) DEFAULT NULL,
  `circumcised_25_yrs_and_above` int(10) DEFAULT NULL,
  `positive_-hiv_status_(at_circumcision)` int(10) DEFAULT NULL,
  `post_-_ae(s)_moderate_adverse_events_(circumcision)` int(10) DEFAULT NULL,
  `during_-_ae(s)_severe_adverse_events_(circumcision)` int(10) DEFAULT NULL,
  `total_ae_post` int(10) DEFAULT NULL,
  `unknown_-hiv_status_(at_circumcision)` int(10) DEFAULT NULL,
  `total_ae_during` int(10) DEFAULT NULL,
  `during_-_ae(s)_moderate_adverse_events_(circumcision)` int(10) DEFAULT NULL,
  `dateupdated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`facility`,`financial_year`),
  KEY `facility` (`facility`)
) ENGINE=InnoDB AUTO_INCREMENT=48509 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_category`
--

DROP TABLE IF EXISTS `tbl_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_dhis_elements`
--

DROP TABLE IF EXISTS `tbl_dhis_elements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_dhis_elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dhis_code` varchar(50) NOT NULL,
  `dhis_name` varchar(150) NOT NULL,
  `dhis_report` varchar(20) NOT NULL,
  `target_report` varchar(20) NOT NULL,
  `target_name` varchar(100) NOT NULL,
  `target_category` varchar(10) NOT NULL,
  `target_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=686 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_facility`
--

DROP TABLE IF EXISTS `tbl_facility`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_facility` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `mflcode` varchar(20) NOT NULL,
  `category` varchar(20) DEFAULT 'satellite',
  `dhiscode` varchar(50) DEFAULT '0',
  `longitude` varchar(200) DEFAULT NULL,
  `latitude` varchar(200) DEFAULT NULL,
  `subcounty_id` int(11) DEFAULT NULL,
  `partner_id` int(11) NOT NULL DEFAULT '1',
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mflcode` (`mflcode`),
  KEY `subcounty_id` (`subcounty_id`),
  KEY `name` (`name`),
  KEY `partner_id` (`partner_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9390 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_line`
--

DROP TABLE IF EXISTS `tbl_line`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_line` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_regimen`
--

DROP TABLE IF EXISTS `tbl_regimen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_regimen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `line_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `category_id` (`category_id`),
  KEY `service_id` (`service_id`),
  KEY `line_id` (`line_id`),
  KEY `name` (`name`),
  CONSTRAINT `tbl_regimen_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `tbl_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_regimen_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `tbl_service` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_regimen_ibfk_3` FOREIGN KEY (`line_id`) REFERENCES `tbl_line` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_service`
--

DROP TABLE IF EXISTS `tbl_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `column_name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `partner_id` tinyint(3) unsigned NOT NULL,
  `user_type_id` tinyint(3) unsigned NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_partner_id_index` (`partner_id`),
  KEY `users_user_type_id_index` (`user_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `view_dmap_regimen_dhis`
--

DROP TABLE IF EXISTS `view_dmap_regimen_dhis`;
/*!50001 DROP VIEW IF EXISTS `view_dmap_regimen_dhis`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_dmap_regimen_dhis` AS SELECT 
 1 AS `id`,
 1 AS `dhis_code`,
 1 AS `dhis_name`,
 1 AS `dhis_report`,
 1 AS `target_report`,
 1 AS `target_name`,
 1 AS `target_category`,
 1 AS `target_id`,
 1 AS `category_id`,
 1 AS `service_id`,
 1 AS `line_id`,
 1 AS `service`,
 1 AS `column_name`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_facilitys`
--

DROP TABLE IF EXISTS `view_facilitys`;
/*!50001 DROP VIEW IF EXISTS `view_facilitys`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_facilitys` AS SELECT 
 1 AS `id`,
 1 AS `originalID`,
 1 AS `longitude`,
 1 AS `latitude`,
 1 AS `DHIScode`,
 1 AS `facilitycode`,
 1 AS `name`,
 1 AS `new_name`,
 1 AS `Flag`,
 1 AS `is_pns`,
 1 AS `is_viremia`,
 1 AS `is_dsd`,
 1 AS `is_otz`,
 1 AS `is_men_clinic`,
 1 AS `is_surge`,
 1 AS `ward_id`,
 1 AS `wardname`,
 1 AS `WardDHISCode`,
 1 AS `WardMFLCode`,
 1 AS `district`,
 1 AS `subcounty_id`,
 1 AS `subcounty`,
 1 AS `SubCountyDHISCode`,
 1 AS `SubCountyMFLCode`,
 1 AS `partner`,
 1 AS `partnername`,
 1 AS `partner2`,
 1 AS `mech_id`,
 1 AS `funding_agency_id`,
 1 AS `funding_agency`,
 1 AS `county`,
 1 AS `countyname`,
 1 AS `CountyDHISCode`,
 1 AS `CountyMFLCode`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_regimen_dhis`
--

DROP TABLE IF EXISTS `view_regimen_dhis`;
/*!50001 DROP VIEW IF EXISTS `view_regimen_dhis`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_regimen_dhis` AS SELECT 
 1 AS `id`,
 1 AS `dhis_code`,
 1 AS `dhis_name`,
 1 AS `dhis_report`,
 1 AS `target_report`,
 1 AS `target_name`,
 1 AS `target_category`,
 1 AS `target_id`,
 1 AS `category_id`,
 1 AS `service_id`,
 1 AS `line_id`,
 1 AS `service`,
 1 AS `column_name`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `wards`
--

DROP TABLE IF EXISTS `wards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wards` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `WardDHISCode` varchar(30) DEFAULT NULL,
  `WardMFLCode` varchar(30) DEFAULT NULL,
  `rawcode` varchar(20) DEFAULT NULL,
  `subcounty_id` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1529 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `weeks`
--

DROP TABLE IF EXISTS `weeks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weeks` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `week_number` tinyint(3) unsigned DEFAULT '0',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `year` smallint(4) unsigned DEFAULT '0',
  `month` tinyint(3) unsigned DEFAULT '0',
  `financial_year` smallint(4) unsigned DEFAULT '0',
  `quarter` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `identifier` (`week_number`,`year`,`month`),
  KEY `identifier_other` (`week_number`,`financial_year`,`quarter`),
  KEY `week_number` (`week_number`),
  KEY `specific_time` (`year`,`month`),
  KEY `specific_period` (`financial_year`,`quarter`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Final view structure for view `p_early_indicators_view`
--

/*!50001 DROP VIEW IF EXISTS `p_early_indicators_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `p_early_indicators_view` AS (select `p`.`id` AS `id`,`p`.`partner` AS `partner`,`p`.`county` AS `county`,`pp`.`year` AS `year`,`pp`.`month` AS `month`,`pp`.`financial_year` AS `financial_year`,`pp`.`quarter` AS `quarter`,`p`.`tested` AS `tested`,`p`.`positive` AS `positive`,`p`.`new_art` AS `new_art`,`p`.`linkage` AS `linkage`,`p`.`current_tx` AS `current_tx`,`p`.`net_new_tx` AS `net_new_tx`,`p`.`vl_total` AS `vl_total`,`p`.`eligible_for_vl` AS `eligible_for_vl`,`p`.`pmtct` AS `pmtct`,`p`.`pmtct_stat` AS `pmtct_stat`,`p`.`pmtct_new_pos` AS `pmtct_new_pos`,`p`.`pmtct_known_pos` AS `pmtct_known_pos`,`p`.`pmtct_total_pos` AS `pmtct_total_pos`,`p`.`art_pmtct` AS `art_pmtct`,`p`.`art_uptake_pmtct` AS `art_uptake_pmtct`,`p`.`eid_lt_2m` AS `eid_lt_2m`,`p`.`eid_lt_12m` AS `eid_lt_12m`,`p`.`eid_total` AS `eid_total`,`p`.`eid_pos` AS `eid_pos`,`p`.`dateupdated` AS `dateupdated`,`partners`.`name` AS `partnername`,`countys`.`name` AS `countyname`,`countys`.`CountyDHISCode` AS `CountyDHISCode`,`countys`.`CountyMFLCode` AS `CountyMFLCode`,`partners`.`funding_agency_id` AS `funding_agency_id`,`funding_agencies`.`name` AS `funding_agency` from ((((`p_early_indicators` `p` left join `periods` `pp` on((`pp`.`id` = `p`.`period_id`))) left join `partners` on((`p`.`partner` = `partners`.`id`))) left join `funding_agencies` on((`partners`.`funding_agency_id` = `funding_agencies`.`id`))) left join `countys` on((`p`.`county` = `countys`.`id`)))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `surge_columns_view`
--

/*!50001 DROP VIEW IF EXISTS `surge_columns_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `surge_columns_view` AS (select `c`.`id` AS `id`,`c`.`column_name` AS `column_name`,`c`.`alias_name` AS `alias_name`,`c`.`excel_name` AS `excel_name`,`c`.`gender_id` AS `gender_id`,`c`.`age_id` AS `age_id`,`c`.`modality_id` AS `modality_id`,`a`.`age` AS `age`,`a`.`age_name` AS `age_name`,`ac`.`age_category` AS `age_category`,`a`.`age_category_id` AS `age_category_id`,`a`.`no_gender` AS `no_gender`,`g`.`gender` AS `gender`,`m`.`modality` AS `modality`,`m`.`modality_name` AS `modality_name`,`m`.`tbl_name` AS `tbl_name`,`m`.`hts` AS `hts`,`m`.`target` AS `target` from ((((`surge_columns` `c` left join `surge_ages` `a` on((`a`.`id` = `c`.`age_id`))) left join `age_categories` `ac` on((`ac`.`id` = `a`.`age_category_id`))) left join `surge_genders` `g` on((`g`.`id` = `c`.`gender_id`))) left join `surge_modalities` `m` on((`m`.`id` = `c`.`modality_id`)))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_dmap_regimen_dhis`
--

/*!50001 DROP VIEW IF EXISTS `view_dmap_regimen_dhis`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `view_dmap_regimen_dhis` AS (select `d`.`id` AS `id`,`d`.`dhis_code` AS `dhis_code`,`d`.`dhis_name` AS `dhis_name`,`d`.`dhis_report` AS `dhis_report`,`d`.`target_report` AS `target_report`,`d`.`target_name` AS `target_name`,`d`.`target_category` AS `target_category`,`d`.`target_id` AS `target_id`,`r`.`category_id` AS `category_id`,`r`.`service_id` AS `service_id`,`r`.`line_id` AS `line_id`,`s`.`name` AS `service`,`s`.`column_name` AS `column_name` from ((`tbl_dhis_elements` `d` left join `tbl_regimen` `r` on((`d`.`target_id` = `r`.`id`))) left join `tbl_service` `s` on((`r`.`service_id` = `s`.`id`))) where ((`d`.`target_category` = 'regimen') and (`d`.`dhis_report` like '%729A%'))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_facilitys`
--

/*!50001 DROP VIEW IF EXISTS `view_facilitys`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `view_facilitys` AS (select `facilitys`.`id` AS `id`,`facilitys`.`originalID` AS `originalID`,`facilitys`.`longitude` AS `longitude`,`facilitys`.`latitude` AS `latitude`,`facilitys`.`DHIScode` AS `DHIScode`,`facilitys`.`facilitycode` AS `facilitycode`,`facilitys`.`name` AS `name`,`facilitys`.`new_name` AS `new_name`,`facilitys`.`Flag` AS `Flag`,`facilitys`.`is_pns` AS `is_pns`,`facilitys`.`is_viremia` AS `is_viremia`,`facilitys`.`is_dsd` AS `is_dsd`,`facilitys`.`is_otz` AS `is_otz`,`facilitys`.`is_men_clinic` AS `is_men_clinic`,`facilitys`.`is_surge` AS `is_surge`,`facilitys`.`ward_id` AS `ward_id`,`wards`.`name` AS `wardname`,`wards`.`WardDHISCode` AS `WardDHISCode`,`wards`.`WardMFLCode` AS `WardMFLCode`,`facilitys`.`district` AS `district`,`facilitys`.`district` AS `subcounty_id`,`districts`.`name` AS `subcounty`,`districts`.`SubCountyDHISCode` AS `SubCountyDHISCode`,`districts`.`SubCountyMFLCode` AS `SubCountyMFLCode`,`facilitys`.`partner` AS `partner`,`partners`.`name` AS `partnername`,`facilitys`.`partner2` AS `partner2`,`partners`.`mech_id` AS `mech_id`,`partners`.`funding_agency_id` AS `funding_agency_id`,`funding_agencies`.`name` AS `funding_agency`,`districts`.`county` AS `county`,`countys`.`name` AS `countyname`,`countys`.`CountyDHISCode` AS `CountyDHISCode`,`countys`.`CountyMFLCode` AS `CountyMFLCode` from (((((`facilitys` left join `partners` on((`facilitys`.`partner` = `partners`.`id`))) left join `funding_agencies` on((`partners`.`funding_agency_id` = `funding_agencies`.`id`))) left join `districts` on((`facilitys`.`district` = `districts`.`id`))) left join `wards` on((`facilitys`.`ward_id` = `wards`.`id`))) left join `countys` on((`districts`.`county` = `countys`.`id`))) where (`facilitys`.`Flag` = 1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_regimen_dhis`
--

/*!50001 DROP VIEW IF EXISTS `view_regimen_dhis`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `view_regimen_dhis` AS (select `d`.`id` AS `id`,`d`.`dhis_code` AS `dhis_code`,`d`.`dhis_name` AS `dhis_name`,`d`.`dhis_report` AS `dhis_report`,`d`.`target_report` AS `target_report`,`d`.`target_name` AS `target_name`,`d`.`target_category` AS `target_category`,`d`.`target_id` AS `target_id`,`r`.`category_id` AS `category_id`,`r`.`service_id` AS `service_id`,`r`.`line_id` AS `line_id`,`s`.`name` AS `service`,`s`.`column_name` AS `column_name` from ((`tbl_dhis_elements` `d` left join `tbl_regimen` `r` on((`d`.`target_id` = `r`.`id`))) left join `tbl_service` `s` on((`r`.`service_id` = `s`.`id`))) where ((`d`.`target_category` = 'regimen') and (`d`.`target_report` = 'F-MAPS') and (`d`.`target_name` like '%facility%'))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

