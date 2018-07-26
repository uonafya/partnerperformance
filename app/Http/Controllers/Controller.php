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

	public function data_set_two($function_name)
	{
		$d = $this->pre_partners();
		$where = $d['where'];
		$sql = $d['sql'];

		$sql .= $this->$function_name();

		// switch ($function_name) {
		// 	case 'tested':
		// 		$sql .= $this->tested_query();
		// 		break;
		// 	case 'positive':
		// 		$sql .= $this->positives_query();
		// 		break;
		// 	case 'linked':
		// 		$sql .= $this->linked_query();
		// 		break;
		// 	default:
		// 		break;
		// }

		$rows = DB::table('d_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql)
			->when($where, function($query) use ($where){
				return $query->where($where);
			})
			->whereRaw($d['date_query'])
			->groupBy($d['groupBy'])
			->get();

		return view('partials.hiv_tested', ['rows' => $rows, 'division' => $d['division'], 'div' => str_random(15)]);
	}
}
