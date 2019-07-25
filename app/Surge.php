<?php

namespace App;

use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use Excel;

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
		self::surges_insert();
	}

	public static function modalities_table()
	{		
        $table_name = 'surge_modalities';
        $sql = "CREATE TABLE `{$table_name}` (
                    id tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
                    modality varchar(30) DEFAULT NULL,
                    modality_name varchar(60) DEFAULT NULL,
                    tbl_name varchar(60) DEFAULT NULL,

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
            // ['modality' => 'testing_target', 'modality_name' => 'Testing Target', 'hts' => 0, 'target' => 1 ],
            // ['modality' => 'pos_target', 'modality_name' => 'Pos Target', 'hts' => 0, 'target' => 1 ],
            // ['modality' => 'tx_new_target', 'modality_name' => 'TX New Target', 'hts' => 0, 'target' => 1 ],
        ]);

        DB::table($table_name)->update(['tbl_name' => 'd_surge']);

        DB::table($table_name)->insert([
            ['modality' => 'mmd', 'modality_name' => 'Multi Month Dispensing', 'hts' => 0, 'tbl_name' => 'd_dispensing', ],
            ['modality' => 'tx_curr', 'modality_name' => 'Currently On Treatment', 'hts' => 0, 'tbl_name' => 'd_tx_curr', ],
            ['modality' => 'prep_new', 'modality_name' => 'Pre-Exposure Prophylaxis New Tx', 'hts' => 0, 'tbl_name' => 'd_weeklies', ],
        ]);

        DB::table($table_name)->insert([
            ['modality' => 'vmmc_circ', 'modality_name' => 'VMMC CIRC', 'hts' => 0, 'tbl_name' => 'd_weeklies', 'female' => 0, 'unknown' => 0, ],
        ]);
	}

    public static function age_categories_table()
    {
        $table_name = 'age_categories';
        $sql = "CREATE TABLE `{$table_name}` (
                    id tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
                    age_category varchar(20) DEFAULT NULL,
                    PRIMARY KEY (`id`)
                );
        ";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);   

        DB::table($table_name)->insert([
            ['id' => 1, 'age_category' => 'Unknown' ],
            ['id' => 2, 'age_category' => 'Below 15', ],
            ['id' => 3, 'age_category' => 'Above 15', ],
            // ['age' => '', 'age_name' => '', 'no_gender' => 1, ],
        ]);     
    }

	public static function ages_table()
	{		
        $table_name = 'surge_ages';
        $sql = "CREATE TABLE `{$table_name}` (
                    id tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
                    age varchar(20) DEFAULT NULL,
                    age_name varchar(20) DEFAULT NULL,
                    age_category_id tinyint(1) UNSIGNED DEFAULT 2,                    
                    max_age tinyint(3) UNSIGNED DEFAULT 0,                    
                    no_gender tinyint(1) UNSIGNED DEFAULT 0,
                    for_surge tinyint(1) UNSIGNED DEFAULT 1,
                    for_vmmc tinyint(1) UNSIGNED DEFAULT 1,
                    for_tx_curr tinyint(1) UNSIGNED DEFAULT 1,
                    PRIMARY KEY (`id`),
                    KEY `age` (`age`),
                    KEY `age_category_id` (`age_category_id`)

                );
        ";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);

        DB::table($table_name)->insert([
            ['age' => 'unknown', 'age_name' => 'Unknown', 'age_category_id' => 1, 'no_gender' => 1, ],
        ]);

        // Only VMMC doesn't have
        DB::table($table_name)->insert([
        	['age' => 'below_1', 'age_name' => 'Below 1', 'age_category_id' => 2, 'max_age' => 1, 'no_gender' => 1, 'for_vmmc' => 0, ],
        ]);

        // Only for Surge
        DB::table($table_name)->insert([
            ['age' => 'below_10', 'age_name' => '1-9', 'age_category_id' => 2, 'max_age' => 9, 'no_gender' => 1, 'for_vmmc' => 0, 'for_tx_curr' => 0, ],
        ]);

        // All Have
        DB::table($table_name)->insert([
        	['age' => 'below_15', 'age_name' => '10-14', 'age_category_id' => 2, 'max_age' => 14, ],
        	['age' => 'below_20', 'age_name' => '15-19', 'age_category_id' => 3, 'max_age' => 19, ],
        	['age' => 'below_25', 'age_name' => '20-24', 'age_category_id' => 3, 'max_age' => 24, ],
        	['age' => 'below_30', 'age_name' => '25-29', 'age_category_id' => 3, 'max_age' => 29, ],
        	['age' => 'below_35', 'age_name' => '30-34', 'age_category_id' => 3, 'max_age' => 34, ],
        	['age' => 'below_40', 'age_name' => '35-39', 'age_category_id' => 3, 'max_age' => 39, ],
        	// ['age' => 'below_45', 'age_name' => '40-44', 'age_category_id' => 3, 'max_age' => 44, ],
        	// ['age' => 'below_50', 'age_name' => '45-49', 'age_category_id' => 3, 'max_age' => 49, ],
            ['age' => 'below_50', 'age_name' => '40-49', 'age_category_id' => 3, 'max_age' => 49, ],
        	['age' => 'above_50', 'age_name' => 'Above 50', 'age_category_id' => 3, 'max_age' => 100, ],
        	// ['age' => '', 'age_name' => '', ],
        ]);

        // Only VMMC
        DB::table($table_name)->insert([
            ['age' => 'below_60_d', 'age_name' => '0-60 Days', 'age_category_id' => 2, 'max_age' => 1, 'for_surge' => 0, 'for_tx_curr' => 0, ],
            ['age' => 'below_4', 'age_name' => '2 Months - 4 Years', 'age_category_id' => 2, 'max_age' => 4, 'for_surge' => 0, 'for_tx_curr' => 0, ],
        ]);

        // Only TX Curr
        DB::table($table_name)->insert([
            ['age' => 'below_4', 'age_name' => '1-4', 'no_gender' => 1, 'age_category_id' => 2, 'max_age' => 4, 'for_surge' => 0, 'for_vmmc' => 0, ],
        ]);

        // Only Surge Doesn't have
        DB::table($table_name)->insert([
            ['age' => 'below_10', 'age_name' => '5-9', 'age_category_id' => 2, 'max_age' => 9, 'for_surge' => 0, ],
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
                    id smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
                    column_name varchar(60) DEFAULT NULL,
                    alias_name varchar(100) DEFAULT NULL,
                    excel_name varchar(100) DEFAULT NULL,
                    gender_id tinyint(3) UNSIGNED DEFAULT 0,
                    age_id tinyint(3) UNSIGNED DEFAULT 0,
                    modality_id tinyint(3) UNSIGNED DEFAULT 0,

                    PRIMARY KEY (`id`),
                    KEY `column_name` (`column_name`),
                    KEY `gender_id` (`gender_id`),
                    KEY `age_id` (`age_id`),
                    KEY `modality_id` (`modality_id`)
                );
        ";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);


        $sql = "CREATE OR REPLACE VIEW `{$table_name}_view` AS (
        			SELECT c.*, a.age, a.age_name, ac.age_category, a.age_category_id, a.no_gender, g.gender, m.modality, m.modality_name, m.tbl_name, m.hts, m.target 

        			FROM surge_columns c
        			LEFT JOIN surge_ages a on a.id=c.age_id
                    LEFT JOIN age_categories ac on ac.id=a.age_category_id
        			LEFT JOIN surge_genders g on g.id=c.gender_id
        			LEFT JOIN surge_modalities m on m.id=c.modality_id
                );
        ";
        DB::statement($sql);


        $table_name = 'd_surge';
        $sql = "CREATE TABLE `{$table_name}` (
                    id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                    facility int(10) UNSIGNED DEFAULT 0,
                    week_id smallint(5) UNSIGNED DEFAULT 0, ";

        $modalities = SurgeModality::all();
        $ages = SurgeAge::surge()->get();
        $genders = SurgeGender::all();
        $hts = ['tested', 'positive'];

        foreach ($modalities as $modality) {
        	foreach ($ages as $age) {
        		if($modality->hts){
        			foreach ($hts as $h) {
        				$base = $modality->modality . '_' . $h . '_' . $age->age . '_';
        				$base2 = $modality->modality_name . ' ' . title_case($h) . ' ' . $age->age_name . ' ';
	        			self::create_surge_column($sql, $base, $base2, $modality, $age, $genders);
        			}
        		}
                else if($modality->target){
                    $targets = ['testing' => 'Testing', 'pos' => 'Pos', 'tx_new' => 'TX New'];
                    foreach ($targets as $key => $value) {
                        $base = $key . '_' . $modality->modality;
                        $base2 = $value . ' ' . $modality->modality_name;
                        self::create_surge_target_column($sql, $base, $base2, $modality);
                    }
                    break;
                }
        		else{
        			$base = $modality->modality . '_' . $age->age . '_';
        			$base2 = $modality->modality_name . ' ' . $age->age_name . ' ';
        			self::create_surge_column($sql, $base, $base2, $modality, $age, $genders);
        		}
        	}
        }

        $sql .= "        
	        		dateupdated date DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `facility` (`facility`),
                    KEY `week_id` (`week_id`),
                    KEY `specific` (`facility`, `week_id`)
        )";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);
	}

	public static function create_surge_column(&$sql, $base, $base2, $modality, $age, $genders)
	{
		foreach ($genders as $gender) {
			if($gender->id == 3 && !$age->no_gender) continue;
            if(!$modality->{$gender->gender}) continue;

			$col = $base . $gender->gender;

            $existing_column = SurgeColumn::where(['column_name' => $col])->first();
            if($existing_column) continue;

			$alias = $base2 . title_case($gender->gender);
			$ex = str_replace(' ', '_', strtolower($alias));
			$ex = str_replace('-', '_', strtolower($ex));
			$sql .= " `{$col}` smallint(5) UNSIGNED DEFAULT 0, ";
            
			$s = SurgeColumn::create([
				'column_name' => $col,
				'alias_name' => $alias,
				'excel_name' => $ex,
				'age_id' => $age->id,
				'gender_id' => $gender->id,
				'modality_id' => $modality->id,
			]);
		}
	}

    public static function create_surge_target_column(&$sql, $base, $base2, $modality)
    {
        $sql .= " `{$base}` smallint(5) UNSIGNED DEFAULT 0, ";

        $s = SurgeColumn::create([
            'column_name' => $base,
            'alias_name' => $base2,
            'excel_name' => $base,
            'age_id' => 0,
            'gender_id' => 0,
            'modality_id' => $modality->id,
        ]);
    }



	public static function surges_insert($year=null, $table_name='d_surge')
	{
		if(!$year){
            $year = date('Y');
            if(date('m') > 9) $year++;
        }

		$weeks = Week::where('financial_year', $year)->get();

		$i=0;
		$data_array = [];
		
		$facilities = Facility::select('id')->get();
		foreach ($facilities as $fac) {
			foreach ($weeks as $week) {
				$data_array[$i] = array('week_id' => $week->id, 'facility' => $fac->id);
				$i++;

				if ($i == 200) {
					DB::table($table_name)->insert($data_array);
					$data_array=null;
			    	$i=0;
				}				
			}
		}

		if($data_array) DB::table($table_name)->insert($data_array);
	}

    public static function create_weeks_table()
    {
        $table_name = 'weeks';
        $sql = "CREATE TABLE `{$table_name}` (
                    id smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
                    week_number tinyint(3) UNSIGNED DEFAULT 0,

                    start_date date DEFAULT NULL,
                    end_date date DEFAULT NULL,

                    year smallint(4) UNSIGNED DEFAULT 0,
                    month tinyint(3) UNSIGNED DEFAULT 0,
                    financial_year smallint(4) UNSIGNED DEFAULT 0,
                    quarter tinyint(3) UNSIGNED DEFAULT 0,

                    PRIMARY KEY (`id`),
                    KEY `identifier`(`week_number`, `year`, `month`),
                    KEY `identifier_other`(`week_number`, `financial_year`, `quarter`),
                    KEY `week_number` (`week_number`),
                    KEY `specific_time` (`year`, `month`),
                    KEY `specific_period` (`financial_year`, `quarter`)
                );
        ";
        DB::connection('mysql_wr')->statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::connection('mysql_wr')->statement($sql);
    }

    // Week starts on Sunday
    // Week belongs to the month where the Saturday is
    // ISO 8601 states that the week begins on Monday
    // It also states that the week belongs to the month/year that the Thursday is in
    public static function create_weeks($financial_year)
    {
        $year = $financial_year - 1;
        $dt = Carbon::createFromDate($year, 10, 1);
        $week = 1;

        if($dt->dayOfWeek != 0){

            while(true){
                if($dt->dayOfWeek == 0) break;
                $dt->subDay();
            }

            $data = [
                'week_number' => $week++,
                'start_date' => $dt->toDateString(),
                'end_date' => $dt->addDays(6)->toDateString(),
                'year' => $dt->year,
                'month' => $dt->month,
            ];

            $data = array_merge($data, Synch::get_financial_year_quarter($dt->year, $dt->month));
            $dt->addDay();

            $w = Week::create($data);

        }

        while(true) {
            $data = [
                'week_number' => $week++,
                'start_date' => $dt->toDateString(),
                'end_date' => $dt->addDays(6)->toDateString(),
                'year' => $dt->year,
                'month' => $dt->month,
            ];

            $data = array_merge($data, Synch::get_financial_year_quarter($dt->year, $dt->month));
            $dt->addDay();

            $w = new Week;
            $w->fill($data);
            if($w->financial_year != $financial_year) break;
            $w->save();
        }
        DB::connection('mysql_wr')->statement("DELETE FROM weeks where week_number < 31 and financial_year = 2019;");
    }


    // ISO Version
    /*public static function create_iso_weeks($financial_year)
    {
        $year = $financial_year - 1;
        $dt = Carbon::createFromDate($year, 10, 1);
        $week = 0;

        if($dt->dayOfWeek != 1){
            // if($dt->dayOfWeek == 0) $dt->addDay();
            if($dt->dayOfWeek < 5) $dt->subDays($dt->dayOfWeek)->addDay();
            else if($dt->dayOfWeek == 5) $dt->addDays(3);
            else if($dt->dayOfWeek == 6) $dt->addDays(2);
        }

        while(true) {
            $data = [
                'week_number' => $week++,
                'start_date' => $dt->toDateString(),
                'end_date' => $dt->addDays(6)->toDateString(),
                'year' => $dt->year,
                'month' => $dt->month,
            ];
            $my_copy = $dt->copy()->subDays(3);
            $data = array_merge($data, Synch::get_financial_year_quarter($dt->year, $dt->month));
            $dt->addDay();

            $w = new Week;
            $w->fill($data);
            if($w->financial_year != $financial_year) break;
            $w->save();
        }
        DB::connection('mysql_wr')->statement("DELETE FROM weeks where week_number < 31 and financial_year = 2019;");
    }*/


    public static function surge_export()
    {
        ini_set('memory_limit', -1);
        $partners = \App\Partner::where(['funding_agency_id' => 1, 'flag' => 1])->get();
        $columns = SurgeColumn::where(['tbl_name' => 'd_surge'])->get();

        $paths = $data = [];

        $sql = "countyname as County, Subcounty, wardname AS `Ward`, facilitycode AS `MFL Code`, name AS `Facility`, financial_year AS `Financial Year`, week_number as `Week Number`, start_date, end_date ";

        foreach ($columns as $column) {
            $sql .= ", `{$column->column_name}` AS `{$column->alias_name}`";
        }

        foreach ($partners as $partner) {
            $filename = str_replace(' ', '_', strtolower($partner->name)) . '_surge_data';

            $facilities = Facility::select('id')->where(['is_surge' => 1, 'partner' => $partner->id])->get()->pluck('id')->toArray();
        
            $rows = DB::table('d_surge')
                ->join('view_facilitys', 'view_facilitys.id', '=', 'd_surge.facility')
                ->join('weeks', 'weeks.id', '=', 'd_surge.week_id')
                ->selectRaw($sql)
                ->where('week_id', '>', 32)
                ->where('partner', $partner->id)
                ->whereIn('view_facilitys.id', $facilities)
                ->get();

            foreach ($rows as $row) {
                $data[] = get_object_vars($row);
            }
            $path = storage_path('exports/' . $filename . '.csv');
            if(file_exists($path)) unlink($path);

            Excel::create($filename, function($excel) use($data){
                $excel->sheet('sheet1', function($sheet) use($data){
                    $sheet->fromArray($data);
                });

            })->store('csv');

            $paths[] = $path;
        }

        // Mail::to(['joelkith@gmail.com'])->send(new TestMail($paths, 'Surge Data'));
    }


}
