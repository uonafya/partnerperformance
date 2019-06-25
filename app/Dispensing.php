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
            $columns = DB::select("show columns from " . $row->Tables_in_hcm);
            echo "Table is {$row->Tables_in_hcm} \n";
            dd($columns);
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
        $modality = SurgeModality::where(['tbl_name' => 'd_dispensing'])->first();
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

    public static function insert_dispensing($year=null,$table_name = 'd_dispensing')
    {
        if(!$year) $year = date('Y');

        $modality = SurgeModality::where(['tbl_name' => $table_name])->first();
        $columns  = SurgeColumn::where(['modality_id' => $modality->id])->get();
        $facilities = Facility::select('id')->get();

        $i=0;
        $data_array = [];

        for ($month=1; $month < 13; $month++) { 
            foreach ($facilities as $k => $facility) {
                foreach ($columns as $column) {
                    $data = ['year' => $year, 'month' => $month, 'facility' => $facility->id, 'column_id' => $column->id];
                    $data = array_merge($data, \App\Synch::get_financial_year_quarter($year, $month) );
                    $data_array[$i] = $data;
                    $i++;

                    if ($i == 200) {
                        DB::connection('mysql_wr')->table($table_name)->insert($data_array);
                        $data_array=null;
                        $i=0;
                    }
                }
            }
        }
        if($data_array) DB::connection('mysql_wr')->table($table_name)->insert($data_array);

        echo 'Completed entry for ' . $table_name . " \n";
    }



}
