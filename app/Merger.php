<?php

namespace App;

use DB;
use \App\Synch;

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

    public static function art($year=null)
    {
        self::merge_rows($year, 'merge_art', 'd_hiv_and_tb_treatment', 'd_care_and_treatment', 'm_art');
    }

    public static function pmtct($year=null)
    {
        self::merge_rows($year, 'merge_pmtct', 'd_prevention_of_mother-to-child_transmission', 'd_pmtct', 'm_pmtct');
    }

    public static function circumcision($year=null)
    {
        self::merge_rows($year, 'merge_circumcision', 'd_medical_male_circumcision', 'd_voluntary_male_circumcision', 'm_circumcision');
    }

    public static function keypop($year=null)
    {
        self::merge_rows($year, 'merge_keypop_testing', 'd_hiv_testing_and_prevention_services', null, 'm_keypop');
        self::merge_rows($year, 'merge_keypop_art', 'd_hiv_and_tb_treatment', null, 'm_keypop');
        self::merge_rows($year, 'merge_keypop_mat', 'd_methadone_assisted_therapy', null, 'm_keypop');
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

                if($old_table != null){
                    $old_rows = DB::table($old_table)
                            ->where(['year' => $year, 'month' => $month])
                            ->limit($limit)->offset($offset)->get();
                }

                if($rows->isEmpty()) break;

                foreach ($rows as $key => $row) {
                    $old_row=null;
                    if($old_table != null){
                        $old_row = $old_rows[$key];
                        if($row->facility != $old_row->facility) $old_row = $old_rows->where('facility', $row->facility)->first();
                    }

                    $data = self::$function_name($row, $old_row);
			        $data['dateupdated'] = $today;

			        DB::connection('mysql_wr')->table($merged_table)
			        			->where(['facility' => $row->facility, 'year' => $year, 'month' => $month])
			        			->update($data);
                }
                $offset+=$limit;
            }
            echo "Completed merging for {$merged_table} for month {$month} at " . date('Y-m-d H:i:s a') . " \n";
        }
	}

    public static function merge_testing($row, $old_row)
    {                    
        $data['testing_total'] = self::merged_value($row->{'tested_total_(sum_hv01-01_to_hv01-10)_hv01-10'}, $old_row->total_tested_hiv);
        $data['first_test_hiv'] = self::merged_value($row->{'tested_new_hv01-13'}, $old_row->first_testing_hiv );
        $data['repeat_test_hiv'] = self::merged_value($row->{'tested_repeat_hv01-14'}, $old_row->repeat_testing_hiv );
        $data['facility_test_hiv'] = self::merged_value($row->{'tested_facility_hv01-11'}, $old_row->{'static_testing_hiv_(health_facility)'} );
        $data['outreach_test_hiv'] = self::merged_value($row->{'tested_community_hv01-12'}, $old_row->outreach_testing_hiv );

        $data['tested_couples'] = self::merged_value($row->{'tested_couples_hv01-15'}, $old_row->couples_testing);
        $data['discordant_couples'] = self::merged_value($row->{'discordant_hv01-28'}, $old_row->discordant_couples_receiving_results);


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


        $below15 = $old_row->male_below_15_years_screened_for_tb + $old_row->female_under_15_years_screened_for_tb;
        $above15 = $old_row->male_15_years_and_older_screened_for_tb + $old_row->female_15_years_and_older_screened_for_tb;

        $data['tb_screened_below1'] = $row->{'screen_for_tb_<1_hv03-051'};
        $data['tb_screened_below10'] = $row->{'screen_for_tb_1-9_hv03-052'};
        $data['tb_screened_below15'] = self::merged_value($row->{'screen_for_tb_10-14_hv03-053'}, $below15);
        $data['tb_screened_below20'] = $row->{'screen_for_tb_15-19_hv03-054'};
        $data['tb_screened_below25'] = $row->{'screen_for_tb_20-24_hv03-055'};
        $data['tb_screened_above25'] = self::merged_value($row->{'screen_for_tb_25pos_hv03-056'}, $above15); 
        $data['tb_screened_total'] = self::merged_value($row->{'screen_for_tb_total_hv03-057'}, $old_row->{'total_screened_for_tb'});

        $data['tb_starting_art'] = self::merged_value($row->{'tb_start_haart_hv03-083'}, $old_row->{'tb_patient_starting_on_art'}); 
        $data['tb_already_on_art'] = $row->{'tb_already_on_haart_hv03-082'}; 
        $data['tb_art_total'] = $row->{'tb_total_on_haart(hv03-082pos083)_hv03-084'};   

    	return $data;
    }

    public static function merge_pmtct($row, $old_row)
    {
        $initial_tests = $row->{'initial_test_at_anc_hv02-04'} + $row->{'initial_test_at_l&d_hv02-05'} + $row->{'initial_test_at_pnc_pnc<=6wks_hv02-06'};
    	$data['tested_pmtct'] = self::merged_value($initial_tests, $old_row->{'total_tested_(pmtct)'});

    	$data['initial_test_anc'] = self::merged_value($row->{'initial_test_at_anc_hv02-04'}, $old_row->{'antenatal_testing_for_hiv'});
    	$data['initial_test_lnd'] = self::merged_value($row->{'initial_test_at_l&d_hv02-05'}, $old_row->{'labour_and_delivery_testing_for_hiv'});
    	$data['initial_test_pnc'] = self::merged_value($row->{'initial_test_at_pnc_pnc<=6wks_hv02-06'}, $old_row->{'postnatal_(within_72hrs)_testing_for_hiv'});

        $known_pos_new = $row->{'known_positive_at_1st_anc_hv02-03'} ?? $row->{'known_positive_at_1st_anc_hv02-10'};

        $data['known_pos_anc'] = self::merged_value($known_pos_new, $old_row->{'known_positive_status_(at_entry_into_anc)'});

        $data['positives_anc'] = self::merged_value($row->{'positive_results_anc_hv02-11'}, $old_row->{'antenatal_positive_to_hiv_test'});
        $data['positives_lnd'] = self::merged_value($row->{'positive_results_l&d_hv02-12'}, $old_row->{'labour_and_delivery_postive_to_hiv_test'});
        $data['positives_pnc'] = self::merged_value($row->{'positive_results_pnc<=6wks_hv02-13'}, $old_row->{'postnatal_(within_72hrs)_postive_to_hiv_test'});
        $data['positives_pnc6m'] = $row->{'positive_pnc>_6weeks_to_6_months_hv02-14'};

        $data['total_positive_pmtct'] = self::merged_value($row->{'total_positive_(add_hv02-10_-_hv02-14)_hv02-15'}, $old_row->{'total_positive_(pmtct)'});

        $data['total_new_positive_pmtct'] = $data['total_positive_pmtct'] - $data['known_pos_anc'];
        if($data['total_new_positive_pmtct'] < 0) $data['total_new_positive_pmtct'] = $data['total_positive_pmtct'];

        $data['haart_total'] = self::merged_value($row->{'on_maternal_haart_total_hv02-20'}, $old_row->{'haart_(art)'});

        $data['on_haart_anc'] = $row->{'on_haart_at_1st_anc_hv02-16'};
        $data['start_art_anc'] = self::merged_value($row->{'start_haart_anc_hv02-17'}, $old_row->{'started_on_art_during_anc'});
        $data['start_art_lnd'] = $row->{'start_haart_l&d_hv02-18'};
        $data['start_art_pnc'] = $row->{'start_haart_pnc<=6wks_hv02-19'};
        $data['start_art_pnc_6m'] = $row->{'start_haart_pnc>_6weeks_to_6_months_hv02-21'};

        $data['known_status_before_male'] = $row->{'known_status_1st_contact_hv02-29'};
        $data['initial_male_test_anc'] = self::merged_value($row->{'initial_test_at_anc_male_hv02-30'}, $old_row->{'male_partners_tested_-(_anc/l&d)'});
        $data['initial_male_test_lnd'] = $row->{'initial_test_at_l&d_male_hv02-31'};
        $data['initial_male_test_pnc'] = $row->{'initial_test_at_pnc_male_hv02-32'};
        $data['known_status_male'] = $row->{'total_known_status_male_hv02-33'};

        $data['initial_pcr_2m'] = self::merged_value($row->{'initial_pcr_<_8wks_hv02-44'}, $old_row->{'pcr_(within_2_months)_infant_testing_(initial_test_only)'});
        $data['initial_pcr_12m'] = self::merged_value($row->{'initial_pcr_>8wks_-12_mths_hv02-45'}, 
            ($old_row->{'pcr_(from_9_to_12_months)_infant_testing_(initial_test_only)'} + 
                $old_row->{'pcr_(from3_to_8_months)_infant_testing_(initial_test_only)'}));

        $data['confirmed_pos'] = self::merged_value($row->{'infected_24mths_hv02-47'}, $old_row->{'total_confirmed_positive_infant_test_result_by_pcr'});

        return $data;
    }

    public static function merge_circumcision($row, $old_row)
    {
        $data['circumcised_below1'] = $row->{'circumcised_1-9yr_hv04-02'};
        $data['circumcised_below10'] = $row->{'circumcised_1-9yr_hv04-02'};
        $data['circumcised_below15'] = self::merged_value($row->{'circumcised_10-14_hv04-03'}, $old_row->{'circumcised_0-14_yrs'});
        $data['circumcised_below20'] = $row->{'circumcised_15-19_hv04-04'};
        $data['circumcised_below25'] = self::merged_value($row->{'circumcised_20-24_hv04-05'}, $old_row->{'circumcised_15-24_yrs'});
        $data['circumcised_above25'] = self::merged_value($row->{'circumcised_25pos_hv04-06'}, $old_row->{'circumcised_25_yrs_and_above'});
        $data['circumcised_total'] = self::merged_value($row->{'circumcised_total_hv04-07'}, $old_row->{'total_circumcised'});

        $data['circumcised_pos'] = self::merged_value($row->{'circumcised_hivpos_hv04-08'}, $old_row->{'positive_-hiv_status_(at_circumcision)'});
        $data['circumcised_neg'] = self::merged_value($row->{'circumcised_hiv-_hv04-09'}, $old_row->{'negative_-hiv_status_(at_circumcision)'});
        $data['circumcised_nk'] = self::merged_value($row->{'circumcised_hiv_nk_hv04-10'}, $old_row->{'unknown_-hiv_status_(at_circumcision)'});


        $data['circumcised_surgical'] = $row->{'surgical_hv04-11'};
        $data['circumcised_devices'] = $row->{'devices_hv04-12'};

        $data['ae_during_moderate'] = self::merged_value($row->{'ae_during_moderate_hv04-13'}, $old_row->{'during_-_ae(s)_moderate_adverse_events_(circumcision)'});
        $data['ae_during_severe'] = self::merged_value($row->{'ae_during_severe_hv04-14'}, $old_row->{'during_-_ae(s)_severe_adverse_events_(circumcision)'});
        $data['ae_post_moderate'] = self::merged_value($row->{'ae_post_moderate_hv04-15'}, $old_row->{'post_-_ae(s)_moderate_adverse_events_(circumcision)'});
        $data['ae_post_severe'] = self::merged_value($row->{'ae_post_severe_hv04-16'}, $old_row->{'post_-_ae(s)_severe_adverse_events_(circumcision)'});

        return $data;
    }

    public static function merge_keypop_testing($row, $old_row)
    {
        $data['tested'] = $row->{'tested_keypop_hv01-16'};
        $data['positive'] = $row->{'positive_keypop_hv01-29'};

        return $data;
    }

    public static function merge_keypop_art($row, $old_row)
    {
        $data['enrolled'] = $row->{'enrolled_in_care_keypop_hv03-012'};        
        $data['current_tx'] = $row->{'on_art_keypop_(hiv3-038_plus_hiv3-050)_hv03-039'};        
        $data['new_tx'] = $row->{'start_art_keypop_hv03-027'};

        return $data;    
    }

    public static function merge_keypop_mat($row, $old_row)
    {
        $data['mat_total'] = $row->{'keypop_on_mat_hv06-01'}; 
        $data['mat_clients_pos'] = $row->{'mat_clients_hivpos_hv06-02'}; 
        $data['mat_on_art'] = $row->{'hivpos_mat_clients_on_art_hv06-03'}; 
        $data['keypop_pwid'] = $row->{'keypop_who_are_pwid_hv06-04'}; 

        return $data;    
    }


    public static function create_merged_tables()
    {
        // $art = "
        //     current_below1 int(10) DEFAULT NULL,
        //     current_below10 int(10) DEFAULT NULL,
        //     current_below15_m int(10) DEFAULT NULL,
        //     current_below15_f int(10) DEFAULT NULL,
        //     current_below20_m int(10) DEFAULT NULL,
        //     current_below20_f int(10) DEFAULT NULL,
        //     current_below25_m int(10) DEFAULT NULL,
        //     current_below25_f int(10) DEFAULT NULL,
        //     current_above25_m int(10) DEFAULT NULL,
        //     current_above25_f int(10) DEFAULT NULL,
        //     current_total int(10) DEFAULT NULL,

        //     new_below1 int(10) DEFAULT NULL,
        //     new_below10 int(10) DEFAULT NULL,
        //     new_below15_m int(10) DEFAULT NULL,
        //     new_below15_f int(10) DEFAULT NULL,
        //     new_below20_m int(10) DEFAULT NULL,
        //     new_below20_f int(10) DEFAULT NULL,
        //     new_below25_m int(10) DEFAULT NULL,
        //     new_below25_f int(10) DEFAULT NULL,
        //     new_above25_m int(10) DEFAULT NULL,
        //     new_above25_f int(10) DEFAULT NULL,
        //     new_total int(10) DEFAULT NULL,

        //     enrolled_below1 int(10) DEFAULT NULL,
        //     enrolled_below10 int(10) DEFAULT NULL,
        //     enrolled_below15_m int(10) DEFAULT NULL,
        //     enrolled_below15_f int(10) DEFAULT NULL,
        //     enrolled_below20_m int(10) DEFAULT NULL,
        //     enrolled_below20_f int(10) DEFAULT NULL,
        //     enrolled_below25_m int(10) DEFAULT NULL,
        //     enrolled_below25_f int(10) DEFAULT NULL,
        //     enrolled_above25_m int(10) DEFAULT NULL,
        //     enrolled_above25_f int(10) DEFAULT NULL,
        //     enrolled_total int(10) DEFAULT NULL,
        // ";

        // self::table_base('m_art', $art);

        // $testing = "
        //     testing_total int(10) DEFAULT NULL,
        //     first_test_hiv int(10) DEFAULT NULL,
        //     repeat_test_hiv int(10) DEFAULT NULL,
        //     facility_test_hiv int(10) DEFAULT NULL,
        //     outreach_test_hiv int(10) DEFAULT NULL,

                // tested_couples int(10) DEFAULT NULL,
                // discordant_couples int(10) DEFAULT NULL,

        //     positive_below10 int(10) DEFAULT NULL,
        //     positive_below15_m int(10) DEFAULT NULL,
        //     positive_below15_f int(10) DEFAULT NULL,
        //     positive_below20_m int(10) DEFAULT NULL,
        //     positive_below20_f int(10) DEFAULT NULL,
        //     positive_below25_m int(10) DEFAULT NULL,
        //     positive_below25_f int(10) DEFAULT NULL,
        //     positive_above25_m int(10) DEFAULT NULL,
        //     positive_above25_f int(10) DEFAULT NULL,
        //     positive_total int(10) DEFAULT NULL,
        // ";

        // self::table_base('m_testing', $testing);

        // $pmtct = "
        //     tested_pmtct int(10) DEFAULT NULL,

        //     known_pos_anc int(10) DEFAULT NULL,

        //     initial_test_anc int(10) DEFAULT NULL,
        //     initial_test_lnd int(10) DEFAULT NULL,
        //     initial_test_pnc int(10) DEFAULT NULL,

        //     positives_anc int(10) DEFAULT NULL,
        //     positives_lnd int(10) DEFAULT NULL,
        //     positives_pnc int(10) DEFAULT NULL,
        //     positives_pnc6m int(10) DEFAULT NULL,

        //     total_positive_pmtct int(10) DEFAULT NULL,
        //     total_new_positive_pmtct int(10) DEFAULT NULL,

        //     haart_total int(10) DEFAULT NULL,

        //     on_haart_anc int(10) DEFAULT NULL,
        //     start_art_anc int(10) DEFAULT NULL,
        //     start_art_lnd int(10) DEFAULT NULL,
        //     start_art_pnc int(10) DEFAULT NULL,
        //     start_art_pnc_6m int(10) DEFAULT NULL,

        //     known_status_before_male int(10) DEFAULT NULL,

        //     initial_male_test_anc int(10) DEFAULT NULL,
        //     initial_male_test_lnd int(10) DEFAULT NULL,
        //     initial_male_test_pnc int(10) DEFAULT NULL,

        //     known_status_male int(10) DEFAULT NULL,

        //     initial_pcr_2m int(10) DEFAULT NULL,
        //     initial_pcr_12m int(10) DEFAULT NULL,
        //     confirmed_pos int(10) DEFAULT NULL,
        // ";

        // self::table_base('m_pmtct', $pmtct);

        // $circumcision = "
        //     circumcised_below1 int(10) DEFAULT NULL,
        //     circumcised_below10 int(10) DEFAULT NULL,
        //     circumcised_below15 int(10) DEFAULT NULL,
        //     circumcised_below20 int(10) DEFAULT NULL,
        //     circumcised_below25 int(10) DEFAULT NULL,
        //     circumcised_above25 int(10) DEFAULT NULL,
        //     circumcised_total int(10) DEFAULT NULL,

        //     circumcised_pos int(10) DEFAULT NULL,
        //     circumcised_neg int(10) DEFAULT NULL,
        //     circumcised_nk int(10) DEFAULT NULL,

        //     circumcised_surgical int(10) DEFAULT NULL,
        //     circumcised_devices int(10) DEFAULT NULL,

        //     ae_during_moderate int(10) DEFAULT NULL,
        //     ae_during_severe int(10) DEFAULT NULL,
        //     ae_post_moderate int(10) DEFAULT NULL,
        //     ae_post_severe int(10) DEFAULT NULL,
        // ";

        // self::table_base('m_circumcision', $circumcision);

        $keypop = "
            tested int(10) DEFAULT NULL,
            positive int(10) DEFAULT NULL,
            enrolled int(10) DEFAULT NULL,
            current_tx int(10) DEFAULT NULL,
            new_tx int(10) DEFAULT NULL,

            mat_total int(10) DEFAULT NULL,
            mat_clients_pos int(10) DEFAULT NULL,
            mat_on_art int(10) DEFAULT NULL,
            keypop_pwid int(10) DEFAULT NULL,
        ";

        self::table_base('m_keypop', $keypop);
    }

    public static function insert_rows($year=null)
    {        
        if(!$year) $year = date('Y');
        $facilities = \App\Facility::select('id')->get();
        // $tables = ['m_testing', 'm_art', 'm_pmtct', 'm_circumcision', 'm_keypop'];
        $tables = ['m_keypop'];

        foreach ($tables as $table) {

            $i=0;
            $data_array = [];

            for ($month=1; $month < 13; $month++) { 
                foreach ($facilities as $k => $val) {
                    $data = array('year' => $year, 'month' => $month, 'facility' => $val->id);
                    $data = array_merge($data, Synch::get_financial_year_quarter($year, $month) );
                    $data_array[$i] = $data;
                    $i++;

                    if ($i == 200) {
                        DB::connection('mysql_wr')->table($table)->insert($data_array);
                        $data_array=null;
                        $i=0;
                    }
                }
            }
            if($data_array) DB::connection('mysql_wr')->table($table)->insert($data_array);

            echo  'Completed entry for ' . $table . " \n";
        }
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

                    $data['positive_below25_m'] = self::merged_value($row->{'positive_20-24(m)_hv01-22'}, $old_row->{'male_15-24yrs_receiving_hiv_pos_results'} );
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
}
