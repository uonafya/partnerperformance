<?php

namespace App;

use DB;
use Str;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\HfrUsaidSubmissionImport;
use App\Exports\GenExport;

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
                    week_id smallint(5) UNSIGNED DEFAULT 0,
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
                    KEY `week_id` (`week_id`),
					KEY `identifier`(`facility`, `week_id`)
                );
        ";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);
    }

    public static function create_target_table()
    {       
        $table_name = 't_county_target';
        $sql = "CREATE TABLE `{$table_name}` (
                    id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `county_id` TINYINT unsigned DEFAULT '0',
                    `partner_id` int(10) unsigned DEFAULT '0',
                    `financial_year` smallint(4) unsigned DEFAULT '0',
                    `gbv` int(10) unsigned DEFAULT NULL,
                    `pep` int(10) unsigned DEFAULT NULL,
                    `physical_emotional_violence` int(10) unsigned DEFAULT NULL,
                    `sexual_violence_post_rape_care` int(10) unsigned DEFAULT NULL,
                    ";

        foreach (self::$hfr_columns as $hfr_column) {
            if($hfr_column == 'tx_mmd') continue;
            foreach (self::$age_groups as $age_group) {
                foreach (self::$genders as $gender) {
                    if($hfr_column == 'vmmc_circ' && $gender == 'Female') continue;

                    $col = $hfr_column . '_' . $age_group . '_' . strtolower($gender);
                    $sql .= " `{$col}` int(10) UNSIGNED DEFAULT 0, ";
                }
            }
        }

        $sql .= "                    
                    PRIMARY KEY (`id`),
                    KEY `identifier` (`financial_year`,`partner_id`),
                    KEY `county_id` (`county_id`),
                    KEY `partner_id` (`partner_id`)
                    ) ENGINE=INNODB DEFAULT CHARSET=latin1
                ;
        ";


        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);
    }



    public static function columns($use_session=false, $filter_column=null, $filter_age_category=null, $filter_gender=null, $not_mmd=false)
    {
        if($use_session && !$filter_age_category) $filter_age_category = session('filter_age_category_id');
        if($use_session && !$filter_gender) $filter_gender = session('filter_gender');
        
    	$columns = [];
        foreach (self::$hfr_columns as $hfr_column) {
            if($filter_column && $filter_column != $hfr_column) continue;
        	if($hfr_column == 'tx_mmd') continue;
        	foreach (self::$age_groups as $age_group_key => $age_group) {
                if($filter_age_category){
                    if($filter_age_category == 2 && $age_group != 'below_15') continue;
                    if($filter_age_category == 3 && $age_group != 'above_15') continue;
                }
	        	foreach (self::$genders as $gender) {
                    if($filter_gender){
                        if($filter_gender == 2 && $gender != 'below_15') continue;
                        if($filter_gender == 3 && $gender != 'above_15') continue;
                    }
	        		if($hfr_column == 'vmmc_circ' && $gender == 'Female') continue;

	        		$column_name = $hfr_column . '_' . $age_group . '_' . strtolower($gender);
	        		$excel_name = strtoupper($hfr_column) . ' ' . $age_group_key . ' ' . $gender;
	        		$alias_name = strtolower(preg_replace("/[\s]/", "_", $excel_name) );

                    $usaid_name = $hfr_column . ($age_group == 'below_15' ? 'u15' : 'o15') . ($gender == 'Male' ? 'm' : 'f');

                    $modality = $hfr_column;

                    $quarterly_name = strtoupper($hfr_column) . ' ' . ($age_group == 'below_15' ? '<15' : '+15') . ' ' . $gender;
                    $quarterly_name = str_replace('PREP', 'PrEP', $quarterly_name);

	        		$columns[] = compact('excel_name', 'column_name', 'alias_name', 'usaid_name', 'gender', 'age_group', 'modality', 'quarterly_name');
	        	}
        	}
        }

        if($not_mmd) return $columns;

        $hfr_column = 'tx_mmd';

        foreach (self::$mmd as $mmd_key => $mmd) {
            if($filter_column && $filter_column != $hfr_column && $filter_column != $mmd) continue;
        	foreach (self::$age_groups as $age_group_key => $age_group) {
                if($filter_age_category){
                    if($filter_age_category == 2 && $age_group != 'below_15') continue;
                    if($filter_age_category == 3 && $age_group != 'above_15') continue;
                }
	        	foreach (self::$genders as $gender) {
                    if($filter_gender){
                        if($filter_gender == 2 && $gender != 'below_15') continue;
                        if($filter_gender == 3 && $gender != 'above_15') continue;
                    }

	        		$column_name = $hfr_column . '_' . $age_group . '_' . strtolower($gender) . '_' . $mmd;
	        		$excel_name = strtoupper($hfr_column) . ' ' . $age_group_key . ' ' . $gender . ' ' . $mmd_key;
                    $alias_name = strtolower(preg_replace("/[\s-]/", "_", $excel_name) );
                    $alias_name = strtolower(preg_replace("/[<+]/", "", $alias_name) );

                    $usaid_name = $hfr_column . ($age_group == 'below_15' ? 'u15' : 'o15') . ($gender == 'Male' ? 'm' : 'f');
                    if($mmd == 'less_3m') $usaid_name .= 'u3mo';
                    else if($mmd == '3_5m') $usaid_name .= '35mo';
                    else if($mmd == 'above_6m') $usaid_name .= 'o6mo';

                    $modality = $hfr_column . ' ' . $mmd;

                    $quarterly_name = strtoupper($hfr_column) . ' ' . ($age_group == 'below_15' ? '<15' : '+15') . ' ' . $gender . ' ' . $mmd_key;

	        		$columns[] = compact('excel_name', 'column_name', 'alias_name', 'usaid_name', 'gender', 'age_group', 'modality', 'quarterly_name');
	        	}
        	}
        }
        return $columns;
    }
    


    public static function copy_tx_curr_data($partner_id=22, $week_id, $to_week_id)
    {
        $columns = HfrSubmission::columns(false, 'tx_curr');
        $week = Week::find($week_id);


        $table_name = 'd_hfr_submission';

        $rows = DB::table($table_name)
            ->select($table_name . '.*')
            ->join('view_facilities', 'view_facilities.id', '=', "{$table_name}.facility")
            ->whereRaw(Lookup::get_active_partner_query($week->start_date))
            ->where(['partner' => $partner_id, 'week_id' => $week_id])
            ->get();

        DB::beginTransaction();

        foreach ($rows as $row) {
            $data = [];
            foreach ($columns as $column) {
                $column_name = $column['column_name'];
                $data[$column_name] = $row->$column_name;
            }
            DB::table($table_name)->where(['facility' => $row->facility, 'week_id' => $to_week_id])->update($data);
        }
        DB::commit();
    }

    public static function upload_data()
    {
        session(['missing_facilities' => [], 'duplicate_rows' => []]);
        /*$files = [
            public_path('hfr_oct_2020.csv'),
            public_path('hfr_nov_2020.csv'),
            public_path('hfr_dec_2020.csv'),
            public_path('hfr_jan_2021.csv'),
            public_path('hfr_feb_2021.csv'),
        ];*/
        $files = [
            public_path('hfr2201.csv'),
           // public_path('hfr2202.csv'),
           // public_path('hfr2203.csv'),
          // public_path('hfr2204_updated.csv'),
           // public_path('hfr2205.csv'),
        ];
        foreach ($files as $upload) {
            Excel::import(new HfrUsaidSubmissionImport, $upload);
        }
        $exp = new GenExport;
        $exp->csv_save(session('missing_facilities'), public_path('final-missing-uids-2.csv'));
        $exp->csv_save(session('duplicate_rows'), public_path('duplicate-hfr-rows-2.csv'));
    }

    public static function find_misasigned()
    {
        $active_partner_query = Lookup::active_partner_query('2020-10-01');
        $tests = HfrSubmission::columns(true, 'hts_tst'); 
        $sql = self::get_hfr_sum($tests, 'tests');
        $var = Lookup::groupby_query(true, 5);

        $table_name = 'd_hfr_submission';

        $rows = DB::table($table_name)
            ->join('view_facilities', 'view_facilities.id', '=', "{$table_name}.facility")
            ->join('weeks', 'weeks.id', '=', "{$table_name}.week_id")
            ->whereRaw($active_partner_query)
            ->selectRaw($sql)
            ->addSelect(DB::raw("view_facilities.id as div_id, name, new_name, DHIScode as dhis_code, facilitycode as mfl_code, "))
            ->groupBy('view_facilities.id')
            ->get();


        foreach ($rows as $key => $row) {

        }

    }

    public static function delete_extra_supported_facilities()
    {
        $duplicates = \App\SupportedFacility::selectRaw("facility_id, start_of_support, end_of_support, partner_id, COUNT(id) AS repeats ")->groupBy('facility_id', 'start_of_support', 'end_of_support', 'partner_id')->having('repeats', '>', 1)->get();

        $duplicates = \App\SupportedFacility::selectRaw("facility_id, COUNT(id) AS repeats ")->whereRaw("(start_of_support <= '2020-10-01' AND (end_of_support >= '2020-10-01' OR end_of_support IS NULL))")->groupBy('facility_id')->having('repeats', '>', 1)->get();

        foreach ($duplicates as $duplicate) {
            $dup_rows = \App\SupportedFacility::where($duplicate->only(['facility_id', 'start_of_support']))->get();
            foreach ($dup_rows as $key => $dup_row) { if(!$key){ continue; } $dup_row->delete(); }
        }
    }

}