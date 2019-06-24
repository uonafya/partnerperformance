<?php

namespace App;

use DB;
use Carbon\Carbon;

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


    public static function modalities_table()
    {       
        $table_name = 'modalities';
        $sql = "CREATE TABLE `{$table_name}` (
                    id tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
                    modality varchar(30) DEFAULT NULL,
                    modality_name varchar(60) DEFAULT NULL,
                    table_name varchar(60) DEFAULT NULL,

                    male tinyint(1) UNSIGNED DEFAULT 1,
                    female tinyint(1) UNSIGNED DEFAULT 1,
                    unknown tinyint(1) UNSIGNED DEFAULT 1,

                    hts tinyint(1) UNSIGNED DEFAULT 1,
                    target tinyint(1) UNSIGNED DEFAULT 0,

                    PRIMARY KEY (`id`),
                    KEY `modality` (`modality`),
                    KEY `modality_name` (`modality_name`)
                );
        ";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);

        DB::table($table_name)->insert([
            ['modality' => 'emergency_ward', 'modality_name' => 'Emergency Ward' ],
            // ['modality' => 'facility_index', 'modality_name' => 'Facility Index' ],
            // ['modality' => 'community_index', 'modality_name' => 'Community Index' ],
            ['modality' => 'index', 'modality_name' => 'Facility and Community Index' ],
            ['modality' => 'inpatient', 'modality_name' => 'Inpatient' ],
            ['modality' => 'malnutrution', 'modality_name' => 'Malnutrition' ],
            ['modality' => 'community_mobile', 'modality_name' => 'Community Mobile' ],
            ['modality' => 'community_other_services', 'modality_name' => 'Community Other Services' ],
            ['modality' => 'other_pitc', 'modality_name' => 'Other PITC' ],
            ['modality' => 'pediatric', 'modality_name' => 'Pediatric' ],
            ['modality' => 'sti_clinic', 'modality_name' => 'STI Clinic' ],
            ['modality' => 'vct', 'modality_name' => 'VCT' ],
            ['modality' => 'community_vct', 'modality_name' => 'Community VCT' ],
            ['modality' => 'tb_clinic', 'modality_name' => 'TB Clinic' ],
            // ['modality' => '', 'modality_name' => '' ],
        ]);

        DB::table($table_name)->insert([
            ['modality' => 'pmtct_anc1', 'modality_name' => 'PMTCT ANC1 Only', 'male' => 0, 'female' => 1, 'unknown' => 0, ],
            ['modality' => 'pmtct_post_anc', 'modality_name' => 'PMTCT POST ANC', 'male' => 0, 'female' => 1, 'unknown' => 0, ],
            ['modality' => 'vmmc', 'modality_name' => 'VMMC', 'male' => 1, 'female' => 0, 'unknown' => 0, ],
            // ['modality' => '', 'modality_name' => '', 'male' => 1, 'female' => 0, 'unknown' => 0, ],
        ]);

        DB::table($table_name)->insert([
            // PNS Columns
            ['modality' => 'clients_screened', 'modality_name' => 'Index Clients Screened', 'hts' => 0, ],
            ['modality' => 'contacts_identified', 'modality_name' => 'Contacts Identified', 'hts' => 0, ],
            ['modality' => 'pos_contacts', 'modality_name' => 'Known HIV Positive Contacts', 'hts' => 0, ],
            ['modality' => 'eligible_contacts', 'modality_name' => 'Eligible Contacts', 'hts' => 0, ],
            ['modality' => 'contacts_tested', 'modality_name' => 'Contacts Tested', 'hts' => 0, ],
            ['modality' => 'new_pos', 'modality_name' => 'Newly Identified Positives', 'hts' => 0, ],
            ['modality' => 'linked_to_haart', 'modality_name' => 'Linked To HAART', 'hts' => 0, ],

            ['modality' => 'tx_new', 'modality_name' => 'New On Treatment', 'hts' => 0, ],
            ['modality' => 'tx_sv_d', 'modality_name' => 'New On Treatment Second Visit Due', 'hts' => 0, ],
            ['modality' => 'tx_sv_n', 'modality_name' => 'New On Treatment Second Visit Number', 'hts' => 0, ],
            ['modality' => 'tx_btc_t', 'modality_name' => 'LTFU Restored to Treatment Target', 'hts' => 0, ],
            ['modality' => 'tx_btc_n', 'modality_name' => 'LTFU Restored to Treatment Number', 'hts' => 0, ],
        ]);

        DB::table($table_name)->insert([
            ['modality' => 'target', 'modality_name' => 'Target', 'hts' => 0, 'target' => 1 ],
        ]);

        DB::table($table_name)->update(['table_name' => 'd_surge']);

        
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

    				age_category_id tinyint(3) UNSIGNED DEFAULT 0,
                    gender_id tinyint(3) UNSIGNED DEFAULT 0,

    				dispensed_one smallint(5) UNSIGNED DEFAULT 0,
    				dispensed_two smallint(5) UNSIGNED DEFAULT 0,
    				dispensed_three smallint(5) UNSIGNED DEFAULT 0,
    				dispensed_four smallint(5) UNSIGNED DEFAULT 0,
    				dispensed_five smallint(5) UNSIGNED DEFAULT 0,
    				dispensed_six smallint(5) UNSIGNED DEFAULT 0,

	        		dateupdated date DEFAULT NULL,
					PRIMARY KEY (`id`),
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



}
