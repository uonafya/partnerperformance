<?php

namespace App;

use DB;
use Str;

class HfrSubmission 
{

    public static $hfr_columns = [
        'hts_tst', 'hts_tst_pos', 'tx_new', 'vmmc_circ', 'prep_new', 'tx_curr', 'tx_mmd'
    ];

    public static $age_groups = ['Below 15', 'Above 15'];
    public static $genders = ['Female', 'Male'];
    public static $mmd = ['less_3m', '3_5m', 'above_6m'];


    public static function create_table()
    {    	
        $table_name = 'd_hfr_submission';
        $sql = "CREATE TABLE `{$table_name}` (
                    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,

                    period_id smallint(4) UNSIGNED DEFAULT 0,
                    facility int(10) UNSIGNED DEFAULT 0,
                    ";

        foreach (self::$hfr_columns as $hfr_column) {
        	if($hfr_column == 'tx_mmd') continue;
        	foreach (self::$age_groups as $age_group) {
	        	foreach (self::$genders as $gender) {
	        		if($hfr_column == 'vmmc_circ' && $gender == 'Female') continue;

	        		$col = $hfr_column . '_' . strtolower(str_replace(' ', '_', $age_group)) . '_' . strtolower($gender);
	        		$sql .= " `{$col}` smallint(5) UNSIGNED DEFAULT 0, ";
	        	}
        	}
        }

        $hfr_column = 'tx_mmd';

        foreach (self::$mmd as $mmd) {
        	foreach (self::$age_groups as $age_group) {
	        	foreach (self::$genders as $gender) {

	        		$col = $hfr_column . '_' . strtolower(str_replace(' ', '_', $age_group)) . '_' . strtolower($gender) . '_' . $mmd;
	        		$sql .= " `{$col}` smallint(5) UNSIGNED DEFAULT 0, ";
	        	}
        	}
        }


		$sql .= "                    
	        		dateupdated date DEFAULT NULL,
					PRIMARY KEY (`id`),
                    KEY `facility` (`facility`),
                    KEY `period_id` (`period_id`),
					KEY `identifier`(`facility`, `period_id`)
                );
        ";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);
    }
}
