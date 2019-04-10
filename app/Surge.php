<?php

namespace App;
use Excel;
use DB;

class Surge extends Model
{

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
        	['age' => '', 'age_name' => '', 'no_gender' => 1, ],
        	['age' => '', 'age_name' => '', 'no_gender' => 1, ],
        	['age' => '', 'age_name' => '', 'no_gender' => 1, ],
        ]);

        DB::table($table_name)->insert([
        	['age' => '', 'age_name' => '', ],
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
                    gender tinyint(3) UNSIGNED DEFAULT 0,
                    age tinyint(3) UNSIGNED DEFAULT 0,
                    modality varchar(20) DEFAULT NULL,

                    PRIMARY KEY (`id`),
                    KEY `column_name` (`column_name`),
                    KEY `gender` (`gender`),
                    KEY `modality` (`modality`)
                );
        ";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);


        $table_name = 'surge_columns';
        $sql = "CREATE TABLE `{$table_name}` (
                    id tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
                    facility int(10) UNSIGNED DEFAULT 0,
                    week_id int(10) UNSIGNED DEFAULT 0,
        ";






	}

	public static function modalities()
	{
		DB::table('surge_modalities')->insert([
			['modality' => 'in_patient', 'modality_name' => 'In Patient', 'male' => 1, 'female' => 0, ]
			['modality' => '', 'modality_name' => '', 'male' => 1, 'female' => 0, ]
			['modality' => '', 'modality_name' => '', 'male' => 1, 'female' => 0, ]
			['modality' => '', 'modality_name' => '', 'male' => 1, 'female' => 0, ]
			['modality' => '', 'modality_name' => '', 'male' => 1, 'female' => 0, ]
			['modality' => '', 'modality_name' => '', 'male' => 1, 'female' => 0, ]
			['modality' => '', 'modality_name' => '', 'male' => 1, 'female' => 0, ]
			['modality' => '', 'modality_name' => '', 'male' => 1, 'female' => 0, ]
			['modality' => '', 'modality_name' => '', 'male' => 1, 'female' => 0, ]
			['modality' => '', 'modality_name' => '', 'male' => 1, 'female' => 0, ]
			['modality' => '', 'modality_name' => '', 'male' => 1, 'female' => 0, ]
		]);
	}

	public static function create_surge_table()
	{

	}
}
