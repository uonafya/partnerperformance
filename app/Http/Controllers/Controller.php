<?php

namespace App\Http\Controllers;

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

    public function tested_query()
    {
    	return "
			SUM(`tested_1-9_hv01-01`) as below_10,
			SUM(`tested_10-14_(m)_hv01-02` + `tested_10-14(f)_hv01-03`) as below_15,
			SUM(`tested_15-19_(m)_hv01-04` + `tested_15-19(f)_hv01-05`) as below_20,
			SUM(`tested_20-24(m)_hv01-06` + `tested_20-24(f)_hv01-07`) as below_25,
			SUM(`tested_25pos_(m)_hv01-08` + `tested_25pos_(f)_hv01-09`) as above_25,
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) as total
		";
    }

    public function positives_query()
    {
    	return "
			SUM(`positive_1-9_hv01-17`) as below_10,
			SUM(`positive_10-14(m)_hv01-18` + `positive_10-14(f)_hv01-19`) as below_15,
			SUM(`positive_15-19(m)_hv01-20` + `positive_15-19(f)_hv01-21`) as below_20,
			SUM(`positive_20-24(m)_hv01-22` + `positive_20-24(f)_hv01-23`) as below_25,
			SUM(`positive_25pos(m)_hv01-24` + `positive_25pos(f)_hv01-25`) as above_25,
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
    		SUM(`initial_test_at_anc_hv02-04` + `initial_test_at_l&d_hv02-05` + `initial_test_at_pnc_pnc<=6wks_hv02-06`) AS `new_pmtct`,
    		SUM(`positive_results_anc_hv02-11` + `positive_results_l&d_hv02-12` + `positive_results_pnc<=6wks_hv02-13`) AS `positive_pmtct`
    	";
    }

    public function new_art_query()
    {
    	return "
			SUM(`start_art_<1_hv03-016`) as below_1,
			SUM(`start_art_1-9_hv03-017`) as below_10,
			SUM(`start_art_10-14(m)__hv03-018` + `start_art_10-14_(f)__hv03-019`) as below_15,
			SUM(`start_art_15-19(m)__hv03-020` + `start_art_15-19_(f)__hv03-021`) as below_20,
			SUM(`start_art_20-24(m)__hv03-022` + `start_art_20-24_(f)__hv03-023`) as below_25,
			SUM(`start_art_25pos(m)__hv03-024` + `start_art_25pos_(f)__hv03-025`) as above_25,
			SUM(`start_art_total__(sum_hv03-018_to_hv03-029)_hv03-026`) as total
		";
    }

    public function current_art_query()
    {
    	return "
			SUM(`on_art_<1_hv03-028`) as below_1,
			SUM(`on_art_1-9_hv03-029`) as below_10,
			SUM(`on_art_10-14(m)__hv03-030` + `on_art_10-14_(f)__hv03-031`) as below_15,
			SUM(`on_art_15-19(m)__hv03-032` + `on_art_15-19_(f)__hv03-033`) as below_20,
			SUM(`on_art_20-24(m)__hv03-034` + `on_art_20-24_(f)__hv03-035`) as below_25,
			SUM(`on_art_25pos(m)__hv03-036` + `on_art_25pos_(f)__hv03-037`) as above_25,
			SUM(`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`) as total
		";
    }

	public function data_set_two($function_name)
	{
		$d = $this->pre_partners();
		$where = $d['where'];
		$sql = $d['sql'];

		$sql .= $this->$function_name();

		DB::enableQueryLog();

		$rows = DB::table('d_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql)
			->when($where, function($query) use ($where){
				return $query->where($where);
			})
			->whereRaw($d['date_query'])
			->groupBy($d['groupBy'])
			->get();

		return DB::getQueryLog();
		
		return view('partials.hiv_tested', ['rows' => $rows, 'division' => $d['division'], 'div' => str_random(15)]);
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

		return view('partials.art_totals', ['rows' => $rows, 'division' => $d['division'], 'div' => str_random(15)]);
	}
	
}
