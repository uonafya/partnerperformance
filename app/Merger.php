<?php

namespace App;

use DB;

class Merger
{

	public static function merged_value($new_value, $old_value)
	{
		$val = $new_value + $old_value;
		if($new_value && $old_value) $val -= $old_value;
		return $val;
	}

	public static function testing($year=null)
	{
		self::merge_rows($year, 'merge_testing', 'd_hiv_testing_and_prevention_services', 'd_hiv_counselling_and_testing', 'm_testing');
	}

	public static function merge_rows($year, $function_name, $new_table, $old_table, $merged_table)
	{
        if(!$year) $year = date('Y');
        ini_set("memory_limit", "-1");
        $limit=500;
        $today = date('Y-m-d');

        for ($month=1; $month < 13; $month++) { 
            if($year == date('Y') && $month > date('m')) break;
            $offset=0;

            while (true) {
                $rows = DB::table($new_table)
                        ->where(['year' => $year, 'month' => $month])
                        ->limit($limit)->offset($offset)->get();

                $old_rows = DB::table($old_table)
                        ->where(['year' => $year, 'month' => $month])
                        ->limit($limit)->offset($offset)->get();

                if($rows->isEmpty()) break;

                foreach ($rows as $key => $row) {
                    $old_row = $old_rows[$key];
                    if($row->facility != $old_row->facility) $old_row = $old_rows->where('facility', $row->facility)->first();

                    $data = self::$function_name($row, $old_row);
			        $data['dateupdated'] = $today;

			        DB::connection('mysql_wr')->table($merged_table)
			        			->where(['facility' => $row->facility, 'year' => $year, 'month' => $month])
			        			->update($data);
                }
            }
        }
	}

    public static function merge_testing($row, $old_row)
    {                    
        $data['testing_total'] = self::merged_value($row->{'tested_total_(sum_hv01-01_to_hv01-10)_hv01-10'}, $old_row->total_tested_hiv);
        $data['first_test_hiv'] = self::merged_value($row->{'tested_new_hv01-13'}, $old_row->first_testing_hiv );
        $data['repeat_test_hiv'] = self::merged_value($row->{'tested_repeat_hv01-14'}, $old_row->repeat_testing_hiv );
        $data['facility_test_hiv'] = self::merged_value($row->{'tested_facility_hv01-11'}, $old_row->{'static_testing_hiv_(health_facility)'} );
        $data['outreach_test_hiv'] = self::merged_value($row->{'tested_community_hv01-12'}, $old_row->outreach_testing_hiv );


        $data['positive_below10'] = $row->{'positive_1-9_hv01-17'};

        $data['positive_below15_m'] = self::merged_value($row->{'positive_10-14(m)_hv01-18'}, $old_row->male_under_15yrs_receiving_hiv_pos_results);
        $data['positive_below15_f'] = self::merged_value($row->{'positive_10-14(f)_hv01-19'}, $old_row->female_under_15yrs_receiving_hiv_pos_results);

        $data['positive_below20_m'] = $row->{'positive_15-19(m)_hv01-20'};
        $data['positive_below20_f'] = $row->{'positive_15-19(f)_hv01-21'};

        $data['positive_below25_m'] = self::merged_value($row->{'positive_20-24(m)_hv01-22'}, $old_row->{'male_15-24yrs_receiving_hiv_pos_results'}	);
        $data['positive_below25_f'] = self::merged_value($row->{'positive_20-24(f)_hv01-23'}, $old_row->{'female_15-24yrs_receiving_hiv_pos_results'});

        $data['positive_above25_m'] = self::merged_value($row->{'positive_25pos(m)_hv01-24'}, $old_row->{'male_above_25yrs_receiving_hiv_pos_results'});
        $data['positive_above25_f'] = self::merged_value($row->{'positive_25pos(f)_hv01-25'}, $old_row->{'female_above_25yrs_receiving_hiv_pos_results'});

        $data['positive_total'] = self::merged_value($row->{'positive_total_(sum_hv01-18_to_hv01-27)_hv01-26'}, $old_row->total_received_hivpos_results);

        return $data;
    }

    public static function merge_art($row, $old_row)
    {
    	$data['enrolled_below1'] = self::merged_value($row->{'enrolled_<1_hv03-001'}, $old_row->under_1yr_enrolled_in_care);

    	$data['enrolled_below10'] = $row->{'enrolled_1-9_hv03-002'};

    	$data['enrolled_below15_m'] = self::merged_value($row->{'enrolled_10-14(m)_hv03-003'}, $old_row->male_under_15yrs_enrolled_in_care);
    	$data['enrolled_below15_f'] = self::merged_value($row->{'enrolled_10-14_(f)_hv03-004'}, $old_row->female_under_15yrs_enrolled_in_care);

    	$data['enrolled_below20_m'] = $row->{'enrolled_15-19(m)_hv03-005'};
    	$data['enrolled_below20_f'] = $row->{'enrolled_15-19_(f)_hv03-006'};

    	$data['enrolled_below25_m'] = $row->{'enrolled_20-24(m)_hv03-007'};
    	$data['enrolled_below25_f'] = $row->{'enrolled_20-24_(f)_hv03-008'};

    	$data['enrolled_above25_m'] = self::merged_value($row->{'enrolled_25pos(m)_hv03-009'}, $old_row->{'male_above_15yrs_&_older_enrolled_in_care'});
    	$data['enrolled_above25_f'] = self::merged_value($row->{'enrolled_25pos_(f)_hv03-010'}, $old_row->{'female_above_15yrs_enrolled_in_care'});

    	$data['enrolled_total'] = self::merged_value($row->{'enrolled_total_(sum_hv03-001_to_hv03-010)_hv03-011'}, $old_row->total_enrolled_in_care);



    	$data['new_below1'] = self::merged_value($row->{'start_art_<1_hv03-016'}, $old_row->under_1yr_starting_on_art);

    	$data['new_below10'] = $row->{'start_art_1-9_hv03-017'};

    	$data['new_below15_m'] = self::merged_value($row->{'start_art_10-14(m)_hv03-018'}, $old_row->male_under_15yrs_starting_on_art);
    	$data['new_below15_f'] = self::merged_value($row->{'start_art_10-14_(f)_hv03-019'}, $old_row->female_under_15yrs_starting_on_art);

    	$data['new_below20_m'] = $row->{'start_art_15-19(m)_hv03-020'};
    	$data['new_below20_f'] = $row->{'start_art_15-19_(f)_hv03-021'};

    	$data['new_below25_m'] = $row->{'start_art_20-24(m)_hv03-022'};
    	$data['new_below25_f'] = $row->{'start_art_20-24_(f)_hv03-023'};

    	$data['new_above25_m'] = self::merged_value($row->{'start_art_25pos(m)_hv03-024'}, $old_row->{'male_above_15yrs_starting_on_art'});
    	$data['new_above25_f'] = self::merged_value($row->{'start_art_25pos_(f)_hv03-025'}, $old_row->{'female_above_15yrs_starting_on_art'});

    	$data['new_total'] = self::merged_value($row->{'start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026'}, $old_row->total_starting_on_art);



    	$data['current_below1'] = self::merged_value($row->{'on_art_<1_hv03-028'}, $old_row->{'currently_on_art_-_below_1_year'});

    	$data['current_below10'] = $row->{'on_art_1-9_hv03-029'};

    	$data['current_below15_m'] = self::merged_value($row->{'on_art_10-14(m)_hv03-030'}, $old_row->{'currently_on_art_-_male_below_15_years'});
    	$data['current_below15_f'] = self::merged_value($row->{'on_art_10-14_(f)_hv03-031'}, $old_row->{'currently_on_art_-_female_below_15_years'});

    	$data['current_below20_m'] = $row->{'on_art_15-19(m)_hv03-032'};
    	$data['current_below20_f'] = $row->{'on_art_15-19_(f)_hv03-033'};

    	$data['current_below25_m'] = $row->{'on_art_20-24(m)_hv03-034'};
    	$data['current_below25_f'] = $row->{'on_art_20-24_(f)_hv03-035'};

    	$data['current_above25_m'] = self::merged_value($row->{'on_art_25pos(m)_hv03-036'}, $old_row->{'currently_on_art_-_male_above_15_years'});
    	$data['current_above25_f'] = self::merged_value($row->{'on_art_25pos_(f)_hv03-037'}, $old_row->{'currently_on_art_-_female_above_15_years'});

    	$data['current_total'] = self::merged_value($row->{'on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038'}, $old_row->total_currently_on_art);

    	return $data;
    }

    public static function pmtct($row, $old_row)
    {
    	// $data['tested_pmtct'] = self::merged_value($row->{'enrolled_<1_hv03-001'}, $old_row->{'total_tested_(pmtct)'});

    	$known_pos_new = $row->{'known_positive_at_1st_anc_hv02-03'} ?? $row->{'known_positive_at_1st_anc_hv02-10'};

    	$data['known_pos_anc'] = self::merged_value($known_pos_new, $old_row->{'known_positive_status_(at_entry_into_anc)'});

    	$data['initial_test_anc'] = self::merged_value($row->{'initial_test_at_anc_hv02-04'}, $old_row->{'started_on_art_during_anc'});
    	$data['initial_test_lnd'] = $row->{'initial_test_at_l&d_hv02-05'};
    	$data['initial_test_pnc'] = $row->{'initial_test_at_pnc_pnc<=6wks_hv02-06'};
    }


    public static function old_merge_testing($year=null)
    {
        if(!$year) $year = date('Y');
        ini_set("memory_limit", "-1");
        $limit=500;
        $today = date('Y-m-d');

        for ($month=1; $month < 13; $month++) { 
            if($year == date('Y') && $month > date('m')) break;
            $offset=0;

            while (true) {
                $rows = DB::table('d_hiv_testing_and_prevention_services')
                        ->where(['year' => $year, 'month' => $month])
                        ->limit($limit)->offset($offset)->get();

                $old_rows = DB::table('d_hiv_counselling_and_testing')
                        ->where(['year' => $year, 'month' => $month])
                        ->limit($limit)->offset($offset)->get();

                if($rows->isEmpty()) break;

                foreach ($rows as $key => $row) {
                    $old_row = $old_rows[$key];
                    if($row->facility != $old_row->facility) $old_row = $old_rows->where('facility', $row->facility)->first();
                    
                    $data['testing_total'] = self::merged_value($row->{'tested_total_(sum_hv01-01_to_hv01-10)_hv01-10'}, $old_row->total_tested_hiv);
                    $data['first_test_hiv'] = self::merged_value($row->{'tested_new_hv01-13'}, $old_row->first_testing_hiv );
                    $data['repeat_test_hiv'] = self::merged_value($row->{'tested_repeat_hv01-14'}, $old_row->repeat_testing_hiv );
                    $data['facility_test_hiv'] = self::merged_value($row->{'tested_facility_hv01-11'}, $old_row->{'static_testing_hiv_(health_facility)'} );
                    $data['outreach_test_hiv'] = self::merged_value($row->{'tested_community_hv01-12'}, $old_row->outreach_testing_hiv );


                    $data['positive_below10'] = $row->{'positive_1-9_hv01-17'};

                    $data['positive_below15_m'] = self::merged_value($row->{'positive_10-14(m)_hv01-18'}, $old_row->male_under_15yrs_receiving_hiv_pos_results);
                    $data['positive_below15_f'] = self::merged_value($row->{'positive_10-14(f)_hv01-19'}, $old_row->female_under_15yrs_receiving_hiv_pos_results);

                    $data['positive_below20_m'] = $row->{'positive_15-19(m)_hv01-20'};
                    $data['positive_below20_f'] = $row->{'positive_15-19(f)_hv01-21'};

                    $data['positive_below25_m'] = self::merged_value($row->{'positive_20-24(m)_hv01-22'}, $old_row->{'male_15-24yrs_receiving_hiv_pos_results'}	);
                    $data['positive_below25_f'] = self::merged_value($row->{'positive_20-24(f)_hv01-23'}, $old_row->{'female_15-24yrs_receiving_hiv_pos_results'});

                    $data['positive_above25_m'] = self::merged_value($row->{'positive_25pos(m)_hv01-24'}, $old_row->{'male_above_25yrs_receiving_hiv_pos_results'});
                    $data['positive_above25_f'] = self::merged_value($row->{'positive_25pos(f)_hv01-25'}, $old_row->{'female_above_25yrs_receiving_hiv_pos_results'});

                    $data['positive_total'] = self::merged_value($row->{'positive_total_(sum_hv01-18_to_hv01-27)_hv01-26'}, $old_row->total_received_hivpos_results);
                    $data['dateupdated'] = $today;

                    DB::connection('mysql_wr')->table("m_testing")
                    			->where(['facility' => $row->facility, 'year' => $year, 'month' => $month])
                    			->update($data);
                }

                $offset += $limit;
            }
        }
    }

    public static function create_merged_tables()
    {
        $art = "
            current_below1 int(10) DEFAULT NULL,
            current_below10 int(10) DEFAULT NULL,
            current_below15_m int(10) DEFAULT NULL,
            current_below15_f int(10) DEFAULT NULL,
            current_below20_m int(10) DEFAULT NULL,
            current_below20_f int(10) DEFAULT NULL,
            current_below25_m int(10) DEFAULT NULL,
            current_below25_f int(10) DEFAULT NULL,
            current_above25_m int(10) DEFAULT NULL,
            current_above25_f int(10) DEFAULT NULL,
            current_total int(10) DEFAULT NULL,

            new_below1 int(10) DEFAULT NULL,
            new_below10 int(10) DEFAULT NULL,
            new_below15_m int(10) DEFAULT NULL,
            new_below15_f int(10) DEFAULT NULL,
            new_below20_m int(10) DEFAULT NULL,
            new_below20_f int(10) DEFAULT NULL,
            new_below25_m int(10) DEFAULT NULL,
            new_below25_f int(10) DEFAULT NULL,
            new_above25_m int(10) DEFAULT NULL,
            new_above25_f int(10) DEFAULT NULL,
            new_total int(10) DEFAULT NULL,

            enrolled_below1 int(10) DEFAULT NULL,
            enrolled_below10 int(10) DEFAULT NULL,
            enrolled_below15_m int(10) DEFAULT NULL,
            enrolled_below15_f int(10) DEFAULT NULL,
            enrolled_below20_m int(10) DEFAULT NULL,
            enrolled_below20_f int(10) DEFAULT NULL,
            enrolled_below25_m int(10) DEFAULT NULL,
            enrolled_below25_f int(10) DEFAULT NULL,
            enrolled_above25_m int(10) DEFAULT NULL,
            enrolled_above25_f int(10) DEFAULT NULL,
            enrolled_total int(10) DEFAULT NULL,
        ";

        self::table_base('m_art'. $art);

        $testing = "
            testing_total int(10) DEFAULT NULL,
            first_test_hiv int(10) DEFAULT NULL,
            repeat_test_hiv int(10) DEFAULT NULL,
            facility_test_hiv int(10) DEFAULT NULL,
            outreach_test_hiv int(10) DEFAULT NULL,

            positive_below10 int(10) DEFAULT NULL,
            positive_below15_m int(10) DEFAULT NULL,
            positive_below15_f int(10) DEFAULT NULL,
            positive_below20_m int(10) DEFAULT NULL,
            positive_below20_f int(10) DEFAULT NULL,
            positive_below25_m int(10) DEFAULT NULL,
            positive_below25_f int(10) DEFAULT NULL,
            positive_above25_m int(10) DEFAULT NULL,
            positive_above25_f int(10) DEFAULT NULL,
            positive_total int(10) DEFAULT NULL,
        ";

        self::table_base('m_testing'. $testing);

        $pmtct = "
            tested_pmtct int(10) DEFAULT NULL,

            known_pos_anc int(10) DEFAULT NULL,

            initial_test_anc int(10) DEFAULT NULL,
            initial_test_lnd int(10) DEFAULT NULL,
            initial_test_pnc int(10) DEFAULT NULL,

            known_hiv_status int(10) DEFAULT NULL,

            positives_anc int(10) DEFAULT NULL,
            positives_lnd int(10) DEFAULT NULL,
            positives_pnc int(10) DEFAULT NULL,

            total_positive_pmtct int(10) DEFAULT NULL,

            initial_male_test_anc int(10) DEFAULT NULL,
            initial_male_test_lnd int(10) DEFAULT NULL,
            initial_male_test_pnc int(10) DEFAULT NULL,

            haart_total int(10) DEFAULT NULL,

            start_art_anc int(10) DEFAULT NULL,
            start_art_lnd int(10) DEFAULT NULL,
            start_art_pnc int(10) DEFAULT NULL,
            start_art_pnc_g6w int(10) DEFAULT NULL,




        ";

        self::table_base('m_pmtct'. $pmtct);
    }

    public static function table_base($table_name, $columns)
    {
        $sql = "CREATE TABLE `{$table_name}` (
                    id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                    facility int(10) UNSIGNED DEFAULT 0,
                    year smallint(4) UNSIGNED DEFAULT 0,
                    month tinyint(3) UNSIGNED DEFAULT 0,
                    financial_year smallint(4) UNSIGNED DEFAULT 0,
                    quarter tinyint(3) UNSIGNED DEFAULT 0,
        ";

        $sql_end = "
                dateupdated date DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `identifier`(`facility`, `year`, `month`),
                KEY `identifier_other`(`facility`, `financial_year`, `quarter`),
                KEY `facility` (`facility`),
                KEY `specific_time` (`year`, `month`),
                KEY `specific_period` (`financial_year`, `quarter`)
            );
        ";
        DB::connection('mysql_wr')->statement("DROP TABLE IF EXISTS `{$table_name}`;");
        $complete_sql =  $sql . $columns . $sql_end;
        DB::connection('mysql_wr')->statement($complete_sql);
    }
}
