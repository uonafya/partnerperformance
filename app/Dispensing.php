<?php

namespace App;

use DB;
use Carbon\Carbon;

use App\AgeCategory;

use App\SurgeAge;
use App\SurgeColumn;
use App\SurgeGender;
use App\SurgeModality;

// Multi Month Dispensing
// Uses Age Categories

class Dispensing
{

	public static function edit_tables()
	{
		$tables = DB::select("show tables");
		foreach ($tables as $key => $row) {
			if(!starts_with($row->Tables_in_hcm, 'd_')) continue;
		}
	}

	public static function periods_table()
	{
        $table_name = 'periods';
        $sql = "CREATE TABLE `{$table_name}` (
                    id tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,

    				year smallint(4) UNSIGNED DEFAULT 0,
    				month tinyint(3) UNSIGNED DEFAULT 0,
    				financial_year smallint(4) UNSIGNED DEFAULT 0,
    				quarter tinyint(3) UNSIGNED DEFAULT 0,

					PRIMARY KEY (`id`),
					KEY `identifier`(`year`, `month`),
					KEY `identifier_other`(`financial_year`, `quarter`),
					KEY `specific_time`(`financial_year`, `month`)
                );
        ";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);
	}


    public static function additional_modalities()
    {       
        $table_name = 'surge_modalities';

        DB::table($table_name)->insert([
            ['modality' => 'mmd', 'modality_name' => 'Multi Month Dispensing', 'hts' => 0, 'tbl_name' => 'd_mmd', ],
            ['modality' => 'prep', 'modality_name' => 'Pre-Exposure Prophylaxis', 'hts' => 0, 'tbl_name' => 'd_prep', ],
            ['modality' => 'tx_curr', 'modality_name' => 'Currently On Treatment', 'hts' => 0, 'tbl_name' => 'd_tx_curr', ],
        ]);

        DB::table($table_name)->insert([
            ['modality' => 'vmmc_circ', 'modality_name' => 'VMMC CIRC', 'hts' => 0, 'tbl_name' => 'd_vmmc_circ', 'female' => 0, 'unknown' => 0, ],
        ]);
    }



	public static function dispensing_table()
	{		
        $table_name = 'd_dispensing';
        $sql = "CREATE TABLE `{$table_name}` (
                    id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,

                    facility int(10) UNSIGNED DEFAULT 0,

    				year smallint(4) UNSIGNED DEFAULT 0,
    				month tinyint(3) UNSIGNED DEFAULT 0,
    				financial_year smallint(4) UNSIGNED DEFAULT 0,
    				quarter tinyint(3) UNSIGNED DEFAULT 0,

                    column_id smallint(5) UNSIGNED DEFAULT 0,

    				-- age_category_id tinyint(3) UNSIGNED DEFAULT 0,
                    -- gender_id tinyint(3) UNSIGNED DEFAULT 0,

    				dispensed_one smallint(5) UNSIGNED DEFAULT 0,
    				dispensed_two smallint(5) UNSIGNED DEFAULT 0,
    				dispensed_three smallint(5) UNSIGNED DEFAULT 0,
    				dispensed_four smallint(5) UNSIGNED DEFAULT 0,
    				dispensed_five smallint(5) UNSIGNED DEFAULT 0,
    				dispensed_six smallint(5) UNSIGNED DEFAULT 0,

	        		dateupdated date DEFAULT NULL,
					PRIMARY KEY (`id`),
                    KEY `column_id` (`column_id`),
					KEY `identifier`(`facility`, `year`, `month`),
					KEY `identifier_other`(`facility`, `financial_year`, `quarter`),
					KEY `facility` (`facility`),
					KEY `specific_time` (`year`, `month`),
					KEY `specific_period` (`financial_year`, `quarter`)
                );
        ";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);
	}

    public static function dispensing_columns()
    {
        $modality = SurgeModality::where(['tbl_name' => 'd_mmd'])->first();
        $sql = '';

        $ages = AgeCategory::all();
        $genders = SurgeGender::all();

        foreach ($ages as $key => $age) {
            foreach ($genders as $key => $gender) {
                $base = $modality->modality . '_' . $age->age_cat . '_';
                $base2 = $modality->modality_name . ' ' . $age->age_category . ' ';
                \App\Surge::create_surge_column($sql, $base, $base2, $modality);
            }
        }
    }

    public static function insert_dispensing($year=null)
    {
        if(!$year) $year = date('Y');
        $table_name = 'd_mmd';
        $modality = SurgeModality::where(['tbl_name' => $table_name])->first();
        $facilities = Facility::select('id')->get();


    }



}
