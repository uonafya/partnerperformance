<?php

namespace App\Http\Controllers\Former;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use DB;
use App\Lookup;

use App\DataSetElement;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function check_null($object, $attr = 'total')
    {
    	if(!$object) return 0;
    	return (int) $object->$attr;
    }

    public function tested_query()
    {
    	return "
			SUM(`tested_1-9_hv01-01`) as below_10,
			(SUM(`tested_10-14_(m)_hv01-02`) + SUM(`tested_10-14(f)_hv01-03`)) as below_15,
			(SUM(`tested_15-19_(m)_hv01-04`) + SUM(`tested_15-19(f)_hv01-05`)) as below_20,
			(SUM(`tested_20-24(m)_hv01-06`) + SUM(`tested_20-24(f)_hv01-07`)) as below_25,
			(SUM(`tested_25pos_(m)_hv01-08`) + SUM(`tested_25pos_(f)_hv01-09`)) as above_25,
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) as total
		";
    }

    public function positives_query()
    {
    	return "
			SUM(`positive_1-9_hv01-17`) as below_10,
			(SUM(`positive_10-14(m)_hv01-18`) + SUM(`positive_10-14(f)_hv01-19`)) as below_15,
			(SUM(`positive_15-19(m)_hv01-20`) + SUM(`positive_15-19(f)_hv01-21`)) as below_20,
			(SUM(`positive_20-24(m)_hv01-22`) + SUM(`positive_20-24(f)_hv01-23`)) as below_25,
			(SUM(`positive_25pos(m)_hv01-24`) + SUM(`positive_25pos(f)_hv01-25`)) as above_25,
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) as total
		";
    }

    public function linked_query()
    {
    	return "
			SUM(`linked_1-9_yrs_hv01-30`) as below_10,
			SUM(`linked_10-14_hv01-31`) as below_15,
			SUM(`linked_15-19_hv01-32`) as below_20,
			SUM(`linked_20-24_hv01-33`) as below_25,
			SUM(`linked_25pos_hv01-34`) as above_25,
			SUM(`linked_total_hv01-35`) as total
		";
    }

    public function pmtct_query()
    {
    	return "
    		SUM(`known_positive_at_1st_anc_hv02-03`) AS `known_positive`,
    		(SUM(`initial_test_at_anc_hv02-04`) + SUM(`initial_test_at_l&d_hv02-05`) + SUM(`initial_test_at_pnc_pnc<=6wks_hv02-06`)) AS `new_pmtct`,
    		(SUM(`positive_results_anc_hv02-11`) + SUM(`positive_results_l&d_hv02-12`) + SUM(`positive_results_pnc<=6wks_hv02-13`)) AS `positive_pmtct`
    	";
    }

    public function new_art_query()
    {
    	return "
			SUM(`start_art_<1_hv03-016`) as below_1,
			SUM(`start_art_1-9_hv03-017`) as below_10,
			(SUM(`start_art_10-14(m)_hv03-018`) + SUM(`start_art_10-14_(f)_hv03-019`)) as below_15,
			(SUM(`start_art_15-19(m)_hv03-020`) + SUM(`start_art_15-19_(f)_hv03-021`)) as below_20,
			(SUM(`start_art_20-24(m)_hv03-022`) + SUM(`start_art_20-24_(f)_hv03-023`)) as below_25,
			(SUM(`start_art_25pos(m)_hv03-024`) + SUM(`start_art_25pos_(f)_hv03-025`)) as above_25,
			SUM(`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026`) as total
		";
    }

    public function former_new_art_query()
    {
    	return "
			SUM(`under_1yr_starting_on_art`) as below_1,
			(SUM(`male_under_15yrs_starting_on_art`) + SUM(`female_under_15yrs_starting_on_art`)) as below_15,
			(SUM(`male_above_15yrs_starting_on_art`) + SUM(`female_above_15yrs_starting_on_art`)) as above_15,
			SUM(`total_starting_on_art`) as total
		";
    }
	public function former_age_current_query()
	{
		return "
			SUM(`currently_on_art_-_below_1_year`) AS `below_1`,
			(SUM(`currently_on_art_-_male_below_15_years`) + SUM(`currently_on_art_-_female_below_15_years`)) AS `below_15`,
			(SUM(`currently_on_art_-_male_above_15_years`) + SUM(`currently_on_art_-_female_above_15_years`)) AS `above_15`,
			SUM(`total_currently_on_art`) AS `total`
		";
	}

	public function former_age_enrolled_query()
	{
		return "
			SUM(`under_1yr_enrolled_in_care`) AS `below_1`,
			(SUM(`male_under_15yrs_enrolled_in_care`) + SUM(`female_under_15yrs_enrolled_in_care`)) AS `below_15`,
			(SUM(`male_above_15yrs_&_older_enrolled_in_care`) + SUM(`female_above_15yrs_enrolled_in_care`)) AS `above_15`,
			SUM(`total_enrolled_in_care`) AS `total`
		";
	}


    public function current_art_query()
    {
    	return "
			SUM(`on_art_<1_hv03-028`) as below_1,
			SUM(`on_art_1-9_hv03-029`) as below_10,
			(SUM(`on_art_10-14(m)_hv03-030`) + SUM(`on_art_10-14_(f)_hv03-031`)) as below_15,
			(SUM(`on_art_15-19(m)_hv03-032`) + SUM(`on_art_15-19_(f)_hv03-033`)) as below_20,
			(SUM(`on_art_20-24(m)_hv03-034`) + SUM(`on_art_20-24_(f)_hv03-035`)) as below_25,
			(SUM(`on_art_25pos(m)_hv03-036`) + SUM(`on_art_25pos_(f)_hv03-037`)) as above_25,
			SUM(`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`) as total
		";
    }


    public function enrolled_art_query()
    {
    	return "
			SUM(`enrolled_<1_hv03-001`) as below_1,
			SUM(`enrolled_1-9_hv03-002`) as below_10,
			(SUM(`enrolled_10-14(m)_hv03-003`) + SUM(`enrolled_10-14_(f)_hv03-004`)) as below_15,
			(SUM(`enrolled_15-19(m)_hv03-005`) + SUM(`enrolled_15-19_(f)_hv03-006`)) as below_20,
			(SUM(`enrolled_20-24(m)_hv03-007`) + SUM(`enrolled_20-24_(f)_hv03-008`)) as below_25,
			(SUM(`enrolled_25pos(m)_hv03-009`) + SUM(`enrolled_25pos_(f)_hv03-010`)) as above_25,
			SUM(`enrolled_total_(sum_hv03-001_to_hv03-010)_hv03-011`) as total
		";
    }

    public function current_art_fixed_query()
    {
    	return "
			MAX(`on_art_<1_hv03-028`) as below_1,
			MAX(`on_art_1-9_hv03-029`) as below_10,
			(MAX(`on_art_10-14(m)_hv03-030`) + MAX(`on_art_10-14_(f)_hv03-031`)) as below_15,
			(MAX(`on_art_15-19(m)_hv03-032`) + MAX(`on_art_15-19_(f)_hv03-033`)) as below_20,
			(MAX(`on_art_20-24(m)_hv03-034`) + MAX(`on_art_20-24_(f)_hv03-035`)) as below_25,
			(MAX(`on_art_25pos(m)_hv03-036`) + MAX(`on_art_25pos_(f)_hv03-037`)) as above_25,
			MAX(`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`) as total
		";
    }

    public function gender_query()
    {
    	return "
			SUM(`tested_1-9_hv01-01`) as below_10_test,
    		(SUM(`tested_10-14_(m)_hv01-02`) + SUM(`tested_15-19_(m)_hv01-04`) + SUM(`tested_20-24(m)_hv01-06`) + SUM(`tested_25pos_(m)_hv01-08`)) AS male_test,
    		(SUM(`tested_10-14(f)_hv01-03`) + SUM(`tested_15-19(f)_hv01-05`) + SUM(`tested_20-24(f)_hv01-07`) + SUM(`tested_25pos_(f)_hv01-09`)) AS female_test,
			SUM(`positive_1-9_hv01-17`) as below_10_pos,
			(SUM(`positive_10-14(m)_hv01-18`) + SUM(`positive_15-19(m)_hv01-20`) + SUM(`positive_20-24(m)_hv01-22`) + SUM(`positive_25pos(m)_hv01-24`)) as male_pos,
			(SUM(`positive_10-14(f)_hv01-19`) + SUM(`positive_15-19(f)_hv01-21`) + SUM(`positive_20-24(f)_hv01-23`) + SUM(`positive_25pos(f)_hv01-25`)) as female_pos
		";
    }

    public function gender_pos_query()
    {
    	return "
    		SUM(`positive_1-9_hv01-17`) AS below_10,
    		(SUM(`positive_10-14(m)_hv01-18`) + SUM(`positive_15-19(m)_hv01-20`) + SUM(`positive_20-24(m)_hv01-22`) + SUM(`positive_25pos(m)_hv01-24`)) AS male_pos,
    		(SUM(`positive_10-14(f)_hv01-19`) + SUM(`positive_15-19(f)_hv01-21`) + SUM(`positive_20-24(f)_hv01-23`) + SUM(`positive_25pos(f)_hv01-25`)) AS female_pos
    	";
    }

    public function old_gender_pos_query()
    {
    	return "
    		(SUM(`male_under_15yrs_receiving_hiv_pos_results`) + SUM(`male_15-24yrs_receiving_hiv_pos_results`) + SUM(`male_above_25yrs_receiving_hiv_pos_results`)) AS male_pos,
    		(SUM(`female_under_15yrs_receiving_hiv_pos_results`) + SUM(`female_15-24yrs_receiving_hiv_pos_results`) + SUM(`female_above_25yrs_receiving_hiv_pos_results`)) AS female_pos
		";
    }

    public function age_query()
    {
    	return "
    		SUM(`tested_1-9_hv01-01`) as below_10,
			(SUM(`tested_10-14_(m)_hv01-02`) + SUM(`tested_10-14(f)_hv01-03`)) as below_15,
			(SUM(`tested_15-19_(m)_hv01-04`) + SUM(`tested_15-19(f)_hv01-05`)) as below_20,
			(SUM(`tested_20-24(m)_hv01-06`) + SUM(`tested_20-24(f)_hv01-07`)) as below_25,
			(SUM(`tested_25pos_(m)_hv01-08`) + SUM(`tested_25pos_(f)_hv01-09`)) as above_25,

			SUM(`positive_1-9_hv01-17`) as below_10_pos,
			(SUM(`positive_10-14(m)_hv01-18`) + SUM(`positive_10-14(f)_hv01-19`)) as below_15_pos,
			(SUM(`positive_15-19(m)_hv01-20`) + SUM(`positive_15-19(f)_hv01-21`)) as below_20_pos,
			(SUM(`positive_20-24(m)_hv01-22`) + SUM(`positive_20-24(f)_hv01-23`)) as below_25_pos,
			(SUM(`positive_25pos(m)_hv01-24`) + SUM(`positive_25pos(f)_hv01-25`)) as above_25_pos
    	";
    }

    public function old_age_query()
    {
    	return "
			(SUM(`male_under_15yrs_receiving_hiv_pos_results`) + SUM(`female_under_15yrs_receiving_hiv_pos_results`)) as below_15,
			(SUM(`male_15-24yrs_receiving_hiv_pos_results`) + SUM(`female_15-24yrs_receiving_hiv_pos_results`)) as below_25,
			(SUM(`male_above_25yrs_receiving_hiv_pos_results`) + SUM(`female_above_25yrs_receiving_hiv_pos_results`)) as above_25
    	";
    }

    public function eid_query()
    {
    	return "
    		SUM(`initial_pcr_<_8wks_hv02-44`) as below_2m,
    		SUM(`initial_pcr_>8wks_-12_mths_hv02-45`) as below_12m
    	";

    }

    public function pre_partners()
    {    	
		$partner = session('filter_partner');
		$where = null;
		$date_query = Lookup::date_query();

		// For a specific partner
		if($partner && is_numeric($partner)){
			$sql = " name, facilitycode, dhiscode, ";
			$division = "facility";
			$groupBy = "view_facilitys.id";
			$where = ['partner' => $partner];
		}
		// For all partners
		else{
			$sql = " partner, partnername, ";
			$division = "partner";
			$groupBy = "view_facilitys.partner";
		}

		return ['sql' => $sql, 'where' => $where, 'division' => $division, 'groupBy' => $groupBy, 'date_query' => $date_query];
    }

	public function data_set_two($function_name)
	{
		$d = $this->pre_partners();
		$where = $d['where'];
		$sql = $d['sql'];

		$sql .= $this->$function_name();

		// DB::enableQueryLog();

		$rows = DB::table('d_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql)
			->when($where, function($query) use ($where){
				return $query->where($where);
			})
			->whereRaw($d['date_query'])
			->groupBy($d['groupBy'])
			->get();

		// return DB::getQueryLog();
		
		return view('tables.hiv_tested', ['rows' => $rows, 'division' => $d['division'], 'div' => str_random(15)]);
	}

	public function data_set_six($function_name)
	{
		$d = $this->pre_partners();
		$where = $d['where'];
		$sql = $d['sql'];

		$sql .= $this->$function_name();

		$rows = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw($sql)
			->when($where, function($query) use ($where){
				return $query->where($where);
			})
			->whereRaw($d['date_query'])
			->groupBy($d['groupBy'])
			->get();

		return view('tables.art_totals', ['rows' => $rows, 'division' => $d['division'], 'div' => str_random(15)]);
	}
	
}
