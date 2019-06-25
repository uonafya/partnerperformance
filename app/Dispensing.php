<?php

namespace App;

use DB;
use Carbon\Carbon;

use App\Synch;
use App\Surge;
use App\Period;
use App\Week;

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
			if(!starts_with($row->Tables_in_hcm, ['d_', '_m']) && $row->Tables_in_hcm != 'p_early_indicators') continue;
            $columns = collect(DB::select("show columns from `" . $row->Tables_in_hcm . '`'));
            echo "Table is {$row->Tables_in_hcm} \n";
            $p = $columns->where('Field', 'period_id')->first();
            if(!$p){
                $w = $columns->where('Field', 'week_id')->first();
                if($w) continue;
                DB::statement("ALTER TABLE {$row->Tables_in_hcm} ADD COLUMN `period_id` smallint(4) UNSIGNED DEFAULT 0 after id;");
                self::add_periods($row->Tables_in_hcm);

                /*$c = $columns->where('Field', 'quarter')->first();
                if($c){
                    DB::statement("ALTER TABLE {$row->Tables_in_hcm} DROP COLUMN `year`;");
                    DB::statement("ALTER TABLE {$row->Tables_in_hcm} DROP COLUMN `month`;");
                    DB::statement("ALTER TABLE {$row->Tables_in_hcm} DROP COLUMN `financial_year`;");
                    DB::statement("ALTER TABLE {$row->Tables_in_hcm} DROP COLUMN `quarter`;");
                }*/
            }
		}
	}

    public static function drop_columns()
    {
        $tables = DB::select("show tables");
        foreach ($tables as $key => $row) {
            if(!starts_with($row->Tables_in_hcm, ['d_', '_m']) && $row->Tables_in_hcm != 'p_early_indicators') continue;
            $columns = collect(DB::select("show columns from " . $row->Tables_in_hcm));
            echo "Table is {$row->Tables_in_hcm} \n";
            $p = $columns->where('Field', 'period_id')->first();
            $c = $columns->where('Field', 'quarter')->first();
            $w = $columns->where('Field', 'week_id')->first();
            if($p && $c && !$w){
                DB::statement("ALTER TABLE {$row->Tables_in_hcm} DROP COLUMN `year`;");
                DB::statement("ALTER TABLE {$row->Tables_in_hcm} DROP COLUMN `month`;");
                DB::statement("ALTER TABLE {$row->Tables_in_hcm} DROP COLUMN `financial_year`;");
                DB::statement("ALTER TABLE {$row->Tables_in_hcm} DROP COLUMN `quarter`;");                
            }
        }
    }



    public static function add_periods($table)
    {
        $periods = Period::all();

        foreach ($periods as $key => $period) {
            DB::table($table)->where(['year' => $period->year, 'month' => $period->month])->update(['period_id' => $period->id]);
        }
    }

	public static function periods_table()
	{
        $table_name = 'periods';
        $sql = "CREATE TABLE `{$table_name}` (
                    id smallint(4) UNSIGNED NOT NULL AUTO_INCREMENT,

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

    public static function insert_periods($year)
    {
        if(!$year) $year = date('Y');
        $data_array = [];

        for ($month=1; $month < 13; $month++) { 
            $data = array('year' => $year, 'month' => $month);
            $data = array_merge($data, Synch::get_financial_year_quarter($year, $month) );
            $data_array[] = $data;
        }

        DB::connection('mysql_wr')->table('periods')->insert($data_array);
    }



	public static function dispensing_table()
	{		
        $table_name = 'd_dispensing';
        $sql = "CREATE TABLE `{$table_name}` (
                    id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,

                    period_id smallint(4) UNSIGNED DEFAULT 0,
                    facility int(10) UNSIGNED DEFAULT 0,

                    column_id smallint(5) UNSIGNED DEFAULT 0,

    				dispensed_one smallint(5) UNSIGNED DEFAULT 0,
    				dispensed_two smallint(5) UNSIGNED DEFAULT 0,
    				dispensed_three smallint(5) UNSIGNED DEFAULT 0,
    				dispensed_four smallint(5) UNSIGNED DEFAULT 0,
    				dispensed_five smallint(5) UNSIGNED DEFAULT 0,
    				dispensed_six smallint(5) UNSIGNED DEFAULT 0,

	        		dateupdated date DEFAULT NULL,
					PRIMARY KEY (`id`),
                    KEY `column_id` (`column_id`),
                    KEY `facility` (`facility`),
                    KEY `period_id` (`period_id`),
					KEY `identifier`(`facility`, `period_id`)
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
                Surge::create_surge_column($sql, $base, $base2, $modality);
            }
        }
    }


    public static function dispensing_table()
    {       
        $table_name = 'd_dispensing';
        $sql = "CREATE TABLE `{$table_name}` (
                    id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,

                    period_id smallint(4) UNSIGNED DEFAULT 0,
                    facility int(10) UNSIGNED DEFAULT 0,

                    column_id smallint(5) UNSIGNED DEFAULT 0,

                    dispensed_one smallint(5) UNSIGNED DEFAULT 0,
                    dispensed_two smallint(5) UNSIGNED DEFAULT 0,
                    dispensed_three smallint(5) UNSIGNED DEFAULT 0,
                    dispensed_four smallint(5) UNSIGNED DEFAULT 0,
                    dispensed_five smallint(5) UNSIGNED DEFAULT 0,
                    dispensed_six smallint(5) UNSIGNED DEFAULT 0,

                    dateupdated date DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `column_id` (`column_id`),
                    KEY `facility` (`facility`),
                    KEY `period_id` (`period_id`),
                    KEY `identifier`(`facility`, `period_id`)
                );
        ";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);
    }




    public static function insert_period_rows($year=null,$table_name = 'd_dispensing')
    {
        if(!$year) $year = date('Y');

        $periods = Period::where(['year' => $year])->get();
        $modality = SurgeModality::where(['tbl_name' => $table_name])->first();
        $columns  = SurgeColumn::where(['modality_id' => $modality->id])->get();
        $facilities = Facility::select('id')->get();

        $i=0;
        $data_array = [];

        foreach ($periods as $period) {
            foreach ($facilities as $k => $facility) {
                foreach ($columns as $column) {
                    $data = ['period_id' => $period->id, 'facility' => $facility->id, 'column_id' => $column->id];
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

    public static function insert_week_rows($year=null, $table_name='d_prep')
    {
        if(!$year){
            $year = date('Y');
            if(date('m') > 9) $year++;
        }

        $weeks = Week::where('financial_year', $year)->get();

        $modality = SurgeModality::where(['tbl_name' => $table_name])->first();
        $columns  = SurgeColumn::where(['modality_id' => $modality->id])->get();

        $i=0;
        $data_array = [];
        
        $facilities = Facility::select('id')->get();
        foreach ($facilities as $fac) {
            foreach ($columns as $column) {
                foreach ($weeks as $week) {
                    $data_array[$i] = ['week_id' => $week->id, 'facility' => $fac->id, 'column_id' => $column->id];
                    $i++;

                    if ($i == 200) {
                        DB::table($table_name)->insert($data_array);
                        $data_array=null;
                        $i=0;
                    }               
                }
            }
        }

        if($data_array) DB::table($table_name)->insert($data_array);
    }



}
