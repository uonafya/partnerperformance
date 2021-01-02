<?php

namespace App;

use DB;
use Str;

class HfrSubmission 
{

    public static $hfr_columns = [
        'hts_tst', 'hts_tst_pos', 'tx_new', 'vmmc_circ', 'prep_new', 'tx_curr', 'tx_mmd'
    ];

    public static $age_groups = ['Less 15' => 'below_15', 'Above 15' => 'above_15'];
    public static $genders = ['Female', 'Male'];
    public static $mmd = ['<3 months' => 'less_3m', '3-5 months' => '3_5m', '6+ months' => 'above_6m'];


    public static function create_table()
    {    	
        $table_name = 'd_hfr_submission';
        $sql = "CREATE TABLE `{$table_name}` (
                    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,

                    week_id smallint(5) UNSIGNED DEFAULT 0,
                    facility int(10) UNSIGNED DEFAULT 0,
                    ";

        foreach (self::$hfr_columns as $hfr_column) {
        	if($hfr_column == 'tx_mmd') continue;
        	foreach (self::$age_groups as $age_group) {
	        	foreach (self::$genders as $gender) {
	        		if($hfr_column == 'vmmc_circ' && $gender == 'Female') continue;

	        		$col = $hfr_column . '_' . $age_group . '_' . strtolower($gender);
	        		$sql .= " `{$col}` smallint(5) UNSIGNED DEFAULT 0, ";
	        	}
        	}
        }

        $hfr_column = 'tx_mmd';

        foreach (self::$mmd as $mmd) {
        	foreach (self::$age_groups as $age_group) {
	        	foreach (self::$genders as $gender) {

	        		$col = $hfr_column . '_' . $age_group . '_' . strtolower($gender) . '_' . $mmd;
	        		$sql .= " `{$col}` smallint(5) UNSIGNED DEFAULT 0, ";
	        	}
        	}
        }


		$sql .= "                    
	        		dateupdated date DEFAULT NULL,
					PRIMARY KEY (`id`),
                    KEY `facility` (`facility`),
                    KEY `week_id` (`week_id`),
					KEY `identifier`(`facility`, `week_id`)
                );
        ";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);
    }

    public static function create_target_table()
    {       
        $table_name = 't_county_target';
        $sql = "CREATE TABLE `{$table_name}` (
                    id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `county_id` TINYINT unsigned DEFAULT '0',
                    `partner_id` int(10) unsigned DEFAULT '0',
                    `financial_year` smallint(4) unsigned DEFAULT '0',
                    `gbv` int(10) unsigned DEFAULT NULL,
                    `pep` int(10) unsigned DEFAULT NULL,
                    `physical_emotional_violence` int(10) unsigned DEFAULT NULL,
                    `sexual_violence_post_rape_care` int(10) unsigned DEFAULT NULL,
                    ";

        foreach (self::$hfr_columns as $hfr_column) {
            if($hfr_column == 'tx_mmd') continue;
            foreach (self::$age_groups as $age_group) {
                foreach (self::$genders as $gender) {
                    if($hfr_column == 'vmmc_circ' && $gender == 'Female') continue;

                    $col = $hfr_column . '_' . $age_group . '_' . strtolower($gender);
                    $sql .= " `{$col}` int(10) UNSIGNED DEFAULT 0, ";
                }
            }
        }

        $sql .= "                    
                    PRIMARY KEY (`id`),
                    KEY `identifier` (`financial_year`,`partner_id`),
                    KEY `county_id` (`county_id`),
                    KEY `partner_id` (`partner_id`),
                    ) ENGINE=INNODB DEFAULT CHARSET=latin1
                ;
        ";


        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);
    }



    public static function columns($use_session=false, $filter_column=null, $filter_age_category=null, $filter_gender=null, $not_mmd=false)
    {
        if($use_session && !$filter_age_category) $filter_age_category = session('filter_age_category_id');
        if($use_session && !$filter_gender) $filter_gender = session('filter_gender');
        
    	$columns = [];
        foreach (self::$hfr_columns as $hfr_column) {
            if($filter_column && $filter_column != $hfr_column) continue;
        	if($hfr_column == 'tx_mmd') continue;
        	foreach (self::$age_groups as $age_group_key => $age_group) {
                if($filter_age_category){
                    if($filter_age_category == 2 && $age_group != 'below_15') continue;
                    if($filter_age_category == 3 && $age_group != 'above_15') continue;
                }
	        	foreach (self::$genders as $gender) {
                    if($filter_gender){
                        if($filter_gender == 2 && $gender != 'below_15') continue;
                        if($filter_gender == 3 && $gender != 'above_15') continue;
                    }
	        		if($hfr_column == 'vmmc_circ' && $gender == 'Female') continue;

	        		$column_name = $hfr_column . '_' . $age_group . '_' . strtolower($gender);
	        		$excel_name = strtoupper($hfr_column) . ' ' . $age_group_key . ' ' . $gender;
	        		$alias_name = strtolower(preg_replace("/[\s]/", "_", $excel_name) );

                    $usaid_name = $hfr_column . ($age_group == 'below_15' ? 'u15' : 'o15') . ($gender == 'Male' ? 'm' : 'f');

	        		$columns[] = compact('excel_name', 'column_name', 'alias_name', 'usaid_name');
	        	}
        	}
        }

        if(!$not_mmd) return $columns;

        $hfr_column = 'tx_mmd';

        foreach (self::$mmd as $mmd_key => $mmd) {
            if($filter_column && $filter_column != $hfr_column && $filter_column != $mmd) continue;
        	foreach (self::$age_groups as $age_group_key => $age_group) {
                if($filter_age_category){
                    if($filter_age_category == 2 && $age_group != 'below_15') continue;
                    if($filter_age_category == 3 && $age_group != 'above_15') continue;
                }
	        	foreach (self::$genders as $gender) {
                    if($filter_gender){
                        if($filter_gender == 2 && $gender != 'below_15') continue;
                        if($filter_gender == 3 && $gender != 'above_15') continue;
                    }

	        		$column_name = $hfr_column . '_' . $age_group . '_' . strtolower($gender) . '_' . $mmd;
	        		$excel_name = strtoupper($hfr_column) . ' ' . $age_group_key . ' ' . $gender . ' ' . $mmd_key;
                    $alias_name = strtolower(preg_replace("/[\s-]/", "_", $excel_name) );
                    $alias_name = strtolower(preg_replace("/[<+]/", "", $alias_name) );

                    $usaid_name = $hfr_column . ($age_group == 'below_15' ? 'u15' : 'o15') . ($gender == 'Male' ? 'm' : 'f');
                    if($mmd == 'less_3m') $usaid_name .= 'u3mo';
                    else if($mmd == '3_5m') $usaid_name .= '35mo';
                    else if($mmd == 'above_6m') $usaid_name .= 'o6mo';

	        		$columns[] = compact('excel_name', 'column_name', 'alias_name', 'usaid_name');
	        	}
        	}
        }
        return $columns;
    }

    public function create_target_table()
    {
        $sql = "CREATE TABLE `t_county_target` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `county_id` TINYINT unsigned DEFAULT '0',
            `financial_year` smallint(4) unsigned DEFAULT '0',
            `gbv` int(10) unsigned DEFAULT NULL,
            `pep` int(10) unsigned DEFAULT NULL,
            `physical_emotional_violence` int(10) unsigned DEFAULT NULL,
            `sexual_violence_post_rape_care` int(10) unsigned DEFAULT NULL,
            `total_gender_gbv` int(10) unsigned DEFAULT NULL,
            `hts_tst` int(10) unsigned DEFAULT NULL,
            `hts_tst_pos` int(10) unsigned DEFAULT NULL,
            `tx_new` int(10) unsigned DEFAULT NULL,
            `vmmc_circ` int(10) unsigned DEFAULT NULL,
            `prep_new` int(10) unsigned DEFAULT NULL,
            `tx_curr` int(10) unsigned DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `identifier` (`county_id`,`financial_year`),
            KEY `county_id` (`county_id`)
            ) ENGINE=INNODB DEFAULT CHARSET=latin1";

    }



}
