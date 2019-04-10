<?php

namespace App;
use Excel;
use DB;

use App\SurgeAge;
use App\SurgeColumn;
use App\SurgeGender;
use App\SurgeModality;
// use App\Surge;


class Surge
{

	public static function surges()
	{
		self::ages_table();
		self::genders_table();
		self::modalities_table();
		self::surges_table();
	}

	public static function modalities_table()
	{		
        $table_name = 'surge_modalities';
        $sql = "CREATE TABLE `{$table_name}` (
                    id tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
                    modality varchar(20) DEFAULT NULL,
                    modality_name varchar(60) DEFAULT NULL,

                    male tinyint(1) UNSIGNED DEFAULT 1,
                    female tinyint(1) UNSIGNED DEFAULT 1,
                    unknown tinyint(1) UNSIGNED DEFAULT 1,

                    hts tinyint(1) UNSIGNED DEFAULT 1,

                    PRIMARY KEY (`id`),
                    KEY `modality` (`modality`),
                    KEY `modality_name` (`modality_name`)
                );
        ";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);

        DB::table($table_name)->insert([
        	['modality' => 'emergency_ward', 'modality_name' => 'Emergency Ward' ],
        	['modality' => 'facility_index', 'modality_name' => 'Facility Index' ],
        	['modality' => 'community_index', 'modality_name' => 'Community Index' ],
        	['modality' => 'inpatient', 'modality_name' => 'Inpatient' ],
        	['modality' => 'malnutrution', 'modality_name' => 'Malnutrition' ],
        	['modality' => 'community_mobile', 'modality_name' => 'Community Mobile' ],
        	['modality' => 'community_other_services', 'modality_name' => 'Community Other Services' ],
        	['modality' => 'other_pitc', 'modality_name' => 'Other PITC' ],
        	['modality' => 'pediatric', 'modality_name' => 'Pediatric' ],
        	['modality' => 'sti_clinic', 'modality_name' => 'STI Clinic' ],
        	['modality' => 'vct', 'modality_name' => 'VCT' ],
        	['modality' => 'tb_clinic', 'modality_name' => 'TB Clinic' ],
        	// ['modality' => '', 'modality_name' => '' ],
        ]);

        DB::table($table_name)->insert([
        	['modality' => 'pmtct_anc1', 'modality_name' => 'PMTCT ANC1 Only', 'male' => 0, 'female' => 1, 'unknown' => 0, ],
        	['modality' => 'vmmc', 'modality_name' => 'VMMC', 'male' => 1, 'female' => 0, 'unknown' => 0, ],
        	// ['modality' => '', 'modality_name' => '', 'male' => 1, 'female' => 0, 'unknown' => 0, ],
        ]);

        DB::table($table_name)->insert([
        	['modality' => 'contacts_identified', 'modality_name' => 'Contacts Identified', 'hts' => 0, ],
        	['modality' => 'pos_contacts', 'modality_name' => 'Known HIV Positive Contacts', 'hts' => 0, ],
        	['modality' => 'eligible_contacts', 'modality_name' => 'Eligible Contacts', 'hts' => 0, ],
        	['modality' => 'contacts_tested', 'modality_name' => 'Contacts Tested', 'hts' => 0, ],
        	['modality' => 'new_pos', 'modality_name' => 'Newly Identified Positives', 'hts' => 0, ],

        	['modality' => 'tx_new', 'modality_name' => 'New On Treatment', 'hts' => 0, ],
        ]);
	}

	public static function ages_table()
	{		
        $table_name = 'surge_ages';
        $sql = "CREATE TABLE `{$table_name}` (
                    id tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
                    age varchar(20) DEFAULT NULL,
                    age_name varchar(20) DEFAULT NULL,
                    no_gender tinyint(1) UNSIGNED DEFAULT 0,

                    PRIMARY KEY (`id`),
                    KEY `age` (`age`)
                );
        ";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);

        DB::table($table_name)->insert([
        	['age' => 'unknown', 'age_name' => 'Unknown', 'no_gender' => 1, ],
        	['age' => 'below_1', 'age_name' => 'Below 1', 'no_gender' => 1, ],
        	['age' => 'below_10', 'age_name' => '1-9', 'no_gender' => 1, ],
        	// ['age' => '', 'age_name' => '', 'no_gender' => 1, ],
        ]);

        DB::table($table_name)->insert([
        	['age' => 'below_15', 'age_name' => '10-14', ],
        	['age' => 'below_20', 'age_name' => '15-19', ],
        	['age' => 'below_25', 'age_name' => '20-24', ],
        	['age' => 'below_30', 'age_name' => '25-29', ],
        	['age' => 'below_35', 'age_name' => '30-34', ],
        	['age' => 'below_40', 'age_name' => '35-39', ],
        	['age' => 'below_45', 'age_name' => '40-44', ],
        	['age' => 'below_50', 'age_name' => '45-49', ],
        	['age' => 'above_50', 'age_name' => 'Above 50', ],
        	// ['age' => '', 'age_name' => '', ],
        ]);
	}

	public static function genders_table()
	{		
        $table_name = 'surge_genders';
        $sql = "CREATE TABLE `{$table_name}` (
                    id tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
                    gender varchar(20) DEFAULT NULL,

                    PRIMARY KEY (`id`),
                    KEY `gender` (`gender`)
                );
        ";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);

        DB::table($table_name)->insert([
        	['id' => 1, 'gender' => 'male', ],
        	['id' => 2, 'gender' => 'female', ],
        	['id' => 3, 'gender' => 'unknown', ],
        ]);
	}

	public static function surges_table()
	{
        $table_name = 'surge_columns';
        $sql = "CREATE TABLE `{$table_name}` (
                    id tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
                    column_name varchar(30) DEFAULT NULL,
                    gender_id tinyint(3) UNSIGNED DEFAULT 0,
                    age_id tinyint(3) UNSIGNED DEFAULT 0,
                    modality_id varchar(20) DEFAULT NULL,

                    PRIMARY KEY (`id`),
                    KEY `column_name` (`column_name`),
                    KEY `gender_id` (`gender_id`),
                    KEY `age_id` (`age_id`),
                    KEY `modality_id` (`modality_id`)
                );
        ";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);


        $sql = "CREATE OR REPLACE VIEW `{$table_name}_view` (
        			SELECT c.*, a.age, a.age_name, a.no_gender, g.gender, m.modality, m.modality_name, m.hts 

        			FROM surge_columns c
        			JOIN surge_ages a on a.id=c.age_id
        			JOIN surge_genders g on g.id=c.gender_id
        			JOIN surge_modalities m on m.id=c.modality_id
                );
        ";
        DB::statement($sql);


        $table_name = 'd_surge';
        $sql = "CREATE TABLE `{$table_name}` (
                    id tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
                    facility int(10) UNSIGNED DEFAULT 0,
                    week_id int(10) UNSIGNED DEFAULT 0,
        ";

        $modalities = SurgeModality::all();
        $ages = SurgeAge::all();
        $genders = SurgeGender::all();
        $hts = ['tested', 'pos'];

        foreach ($modalities as $modality) {
        	foreach ($ages as $age) {
        		if($modality->hts){
        			foreach ($hts as $h) {
        				$base = $modality->modality . '_' . $h . '_' . $age->age . '_';
	        			self::create_surge_column($sql, $base, $modality, $age, $genders);
        			}
        		}
        		else{
        			$base = $modality->modality . '_' . $age->age . '_';
        			self::create_surge_column($sql, $base, $modality, $age, $genders);
        		}
        	}
        }

        $sql .= "
	        		dateupdated date DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `facility` (`facility`),
                    KEY `week_id` (`week_id`),
                    KEY `specific` (`facility`, `week_id`)
        ";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);
	}

	public static function create_surge_column(&$sql, $base, $modality, $age, $genders)
	{
		foreach ($genders as $gender) {
			if($gender->id == 3 && !$age->no_gender) continue;
			if($modality->{$gender->gender}){
				$col = $base . $gender->gender;
				$sql .= "{$col} int(10) UNSIGNED DEFAULT 0, ";

				$s = SurgeColumn::create([
					'column_name' => $col,
					'age_id' => $age->id,
					'gender_id' => $gender->id,
					'modality_id' => $modality->id,
				]);
			}
		}
	}


}
