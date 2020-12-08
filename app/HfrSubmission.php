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

                    period_id smallint(4) UNSIGNED DEFAULT 0,
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
                    KEY `period_id` (`period_id`),
					KEY `identifier`(`facility`, `period_id`)
                );
        ";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);
    }

    public static function columns()
    {
    	$columns = [];
        foreach (self::$hfr_columns as $hfr_column) {
        	if($hfr_column == 'tx_mmd') continue;
        	foreach (self::$age_groups as $age_group_key => $age_group) {
	        	foreach (self::$genders as $gender) {
	        		if($hfr_column == 'vmmc_circ' && $gender == 'Female') continue;

	        		$column_name = $hfr_column . '_' . $age_group . '_' . strtolower($gender);
	        		$excel_name = strtoupper($hfr_column) . ' ' . $age_group_key . ' ' . $gender;
	        		$alias_name = strtolower(preg_replace("/[\s]/", "_", $excel_name) );

	        		$columns[] = compact('excel_name', 'column_name', 'alias_name');
	        	}
        	}
        }

        $hfr_column = 'tx_mmd';

        foreach (self::$mmd as $mmd_key => $mmd) {
        	foreach (self::$age_groups as $age_group_key => $age_group) {
	        	foreach (self::$genders as $gender) {

	        		$column_name = $hfr_column . '_' . $age_group . '_' . strtolower($gender) . '_' . $mmd;
	        		$excel_name = strtoupper($hfr_column) . ' ' . $age_group_key . ' ' . $gender . ' ' . $mmd_key;
                    $alias_name = strtolower(preg_replace("/[\s-]/", "_", $excel_name) );
                    $alias_name = strtolower(preg_replace("/[<+]/", "", $alias_name) );

	        		$columns[] = compact('excel_name', 'column_name', 'alias_name');
	        	}
        	}
        }
        return $columns;
    }



}
