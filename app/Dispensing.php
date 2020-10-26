<?php

namespace App;

use DB;
use Carbon\Carbon;

use App\Synch;
use App\Surge;
use App\Period;
use App\Week;
use App\Facility;

use App\AgeCategory;
use App\SurgeAge;
use App\SurgeColumn;
use App\SurgeColumnView;
use App\SurgeGender;
use App\SurgeModality;

// Multi Month Dispensing
// Uses Age Categories

class Dispensing
{

    public static $dispensations = ['Dispensed One', 'Dispensed Two', 'Dispensed Three', 'Dispensed Four', 'Dispensed Five', 'Dispensed Six', ];

    public static function edit_indexes()
    {
        $tables = DB::select("show tables");
        foreach ($tables as $key => $row) {
            $t = $row->Tables_in_hcm;
            if(!\Str::startsWith($row->Tables_in_hcm, ['d_', 'm_']) || in_array($t, ['p_early_indicators', 'd_dispensing'])) continue;
            $columns = collect(DB::select("show columns from `" . $t . '`'));
            $p = $columns->where('Field', 'period_id')->first();
            if($p){
                echo "Table is {$t} \n";
                $indices = collect(DB::select("show index from `{$t}`"));
                if($indices->where('Key_name', 'identifier')->first()) DB::statement("DROP INDEX identifier on `{$t}`");
                if($indices->where('Key_name', 'identifier_other')->first()) DB::statement("DROP INDEX identifier_other on `{$t}`");
                if($indices->where('Key_name', 'period_id')->first()) DB::statement("DROP INDEX period_id on `{$t}`");

                DB::statement("CREATE INDEX period_id on `{$t}` (period_id)");
                DB::statement("CREATE INDEX identifier on `{$t}` (period_id, facility)");
            }
        }
    }

	public static function edit_tables()
	{
		$tables = DB::select("show tables");
		foreach ($tables as $key => $row) {
			if(!\Str::startsWith($row->Tables_in_hcm, ['d_', 'm_']) && $row->Tables_in_hcm != 'p_early_indicators') continue;
            $columns = collect(DB::select("show columns from `" . $row->Tables_in_hcm . '`'));
            $p = $columns->where('Field', 'period_id')->first();
            if(!$p){
                $w = $columns->where('Field', 'week_id')->first();
                if($w) continue;
                echo "Table is {$row->Tables_in_hcm} \n";
                DB::statement("ALTER TABLE `{$row->Tables_in_hcm}` ADD COLUMN `period_id` smallint(4) UNSIGNED DEFAULT 0 after id;");
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
            if(!\Str::startsWith($row->Tables_in_hcm, ['d_', 'm_']) && $row->Tables_in_hcm != 'p_early_indicators') continue;
            $columns = collect(DB::select("show columns from `{$row->Tables_in_hcm}`"));
            $p = $columns->where('Field', 'period_id')->first();
            $c = $columns->where('Field', 'quarter')->first();
            $w = $columns->where('Field', 'week_id')->first();
            if($p && $c && !$w){
                echo "Table is {$row->Tables_in_hcm} \n";
                DB::statement("ALTER TABLE `{$row->Tables_in_hcm}` DROP COLUMN `year`;");
                DB::statement("ALTER TABLE `{$row->Tables_in_hcm}` DROP COLUMN `month`;");
                DB::statement("ALTER TABLE `{$row->Tables_in_hcm}` DROP COLUMN `financial_year`;");
                DB::statement("ALTER TABLE `{$row->Tables_in_hcm}` DROP COLUMN `quarter`;");                
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

    public static function create_tables()
    {
        self::dispensing_table();
        self::tx_curr_table();
        self::weeklies_table();
    }

    public static function create_columns()
    {
        self::tx_curr_columns();
        self::prep_columns();
        self::vmmc_columns();
    }

    public static function insert_rows()
    {
        self::insert_dispensing_rows();
        self::insert_tx_curr_rows();
        self::insert_weekly_rows();
    }



	public static function dispensing_table()
	{		
        $table_name = 'd_dispensing';
        $sql = "CREATE TABLE `{$table_name}` (
                    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,

                    period_id smallint(4) UNSIGNED DEFAULT 0,
                    facility int(10) UNSIGNED DEFAULT 0,

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
                    KEY `age_category_id` (`age_category_id`),
                    KEY `gender_id` (`gender_id`),
                    KEY `facility` (`facility`),
                    KEY `period_id` (`period_id`),
					KEY `identifier`(`facility`, `period_id`, `age_category_id`),
                    KEY `identifier_two`(`facility`, `period_id`, `gender_id`)
                );
        ";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);
	}

    // VMMC
    // PREP New On Treatment
    public static function weeklies_table($table_name='d_weeklies')
    {
        $sql = "CREATE TABLE `{$table_name}` (
                    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,

                    week_id smallint(5) UNSIGNED DEFAULT 0,
                    facility int(10) UNSIGNED DEFAULT 0,

                    column_id smallint(5) UNSIGNED DEFAULT 0,

                    value smallint(5) UNSIGNED DEFAULT 0,

                    dateupdated date DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `column_id` (`column_id`),
                    KEY `facility` (`facility`),
                    KEY `week_id` (`week_id`),
                    KEY `identifier`(`facility`, `week_id`)
                );
        ";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);
    }

    public static function tx_curr_table()
    {       
        $table_name = 'd_tx_curr';
        $sql = "CREATE TABLE `{$table_name}` (
                    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,

                    period_id smallint(5) UNSIGNED DEFAULT 0,
                    facility int(10) UNSIGNED DEFAULT 0,

                    column_id smallint(5) UNSIGNED DEFAULT 0,

                    value smallint(5) UNSIGNED DEFAULT 0,

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



    public static function tx_curr_columns()
    {
        $modality = SurgeModality::where(['modality' => 'tx_curr'])->first();
        $sql = '';

        $ages = SurgeAge::tx()->get();
        $genders = SurgeGender::all();

        foreach ($ages as $key => $age) {
            Surge::create_surge_column($sql, $modality, $age, $genders);
        }
    }

    public static function prep_columns()
    {
        $modality = SurgeModality::where(['modality' => 'prep_new'])->first();
        $sql = '';

        $ages = SurgeAge::prep_new()->get();
        $genders = SurgeGender::all();

        foreach ($ages as $key => $age) {
            Surge::create_surge_column($sql, $modality, $age, $genders);
        }
    }

    public static function vmmc_columns()
    {
        $modality = SurgeModality::where(['modality' => 'vmmc_circ'])->first();
        $sql = '';

        $ages = SurgeAge::vmmc_circ()->get();
        $genders = SurgeGender::all();

        foreach ($ages as $key => $age) {
            Surge::create_surge_column($sql, $modality, $age, $genders);
        }
    }



    public static function gbv_table()
    {
        $table_name = 'd_gender_based_violence';
        $sql = "CREATE TABLE `{$table_name}` (
                    id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                    facility int(10) UNSIGNED DEFAULT 0,
                    period_id smallint(5) UNSIGNED DEFAULT 0, ";

        $modalities = SurgeModality::where(['tbl_name' => $table_name])->get();
        $ages = SurgeAge::gbv()->get();
        $genders = SurgeGender::all();

        foreach ($modalities as $modality) {
            foreach ($ages as $age) {
                Surge::create_surge_column($sql, $modality, $age, $genders);
            }
        }

        $sql .= "        
                    dateupdated date DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `facility` (`facility`),
                    KEY `period_id` (`period_id`),
                    KEY `specific` (`facility`, `period_id`)
        )";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);
    }

    public static function alter_gbv_table()
    {
        $table_name = 'd_gender_based_violence';
        $sql = "ALTER TABLE `{$table_name}` ";

        $modality = SurgeModality::where(['tbl_name' => $table_name])->orderBy('id', 'desc')->first();
        $ages = SurgeAge::gbv()->get();
        $genders = SurgeGender::all();

        $column = SurgeColumnView::where(['tbl_name' => $table_name])->orderBy('id', 'desc')->first();
        session(['previous_column_name' => $column->column_name]);

        foreach ($ages as $age) {
            Surge::create_surge_column($sql, $modality, $age, $genders, null, true);
        }

        $sql = substr($sql, 0, -2);
        $sql .= ';';

        DB::statement($sql);

        // return $sql;
    }




    public static function insert_dispensing_rows($year=null,$table_name = 'd_dispensing')
    {
        if(!$year) $year = date('Y');

        $periods = Period::where(['year' => $year])->get();
        $genders = SurgeGender::all();
        $age_categories = AgeCategory::all();
        $facilities = Facility::select('id')->get();

        $i=0;
        $data_array = [];

        foreach ($periods as $period) {
            foreach ($facilities as $k => $facility) {
                foreach ($age_categories as $age_category) {
                    foreach ($genders as $gender) {
                        $data = ['period_id' => $period->id, 'facility' => $facility->id, 'age_category_id' => $age_category->id, 'gender_id' => $gender->id, ];
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
        }


        if($data_array) DB::connection('mysql_wr')->table($table_name)->insert($data_array);

        echo 'Completed entry for ' . $table_name . " \n";
    }

    public static function insert_tx_curr_rows($year=null,$table_name = 'd_tx_curr')
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



}
