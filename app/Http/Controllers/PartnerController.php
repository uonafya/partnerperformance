<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

use App\Lookup;
use App\Partner;

class PartnerController extends Controller
{

	public function tested()
	{
		$partner = session('filter_partner');
		$where = null;
		$date_query = Lookup::date_query();

		if($partner || $partner == 0){
			$sql = " name, facilitycode, dhiscode, ";
			$division = "partner";
			$groupBy = "view_facilitys.id";
			$where = ['partner_id' => $partner];
		}
		else{
			$sql = " partner, partner_id, ";
			$division = "facility";
			$groupBy = "view_facilitys.partner_id";
		}

		$sql .= "
			SUM(`tested_1-9_hv01-01`) as below_10,
			SUM(`tested_10-14_(m)_hv01-02` + `tested_10-14_(f)_hv01-03`) as below_15,
			SUM(`tested_15-19_(m)_hv01-04` + `tested_15-19_(f)_hv01-05`) as below_20,
			SUM(`tested_20-24_(m)_hv01-06` + `tested_20-24_(f)_hv01-07`) as below_25,
			SUM(`tested_25pos_(m)_hv01-08` + `tested_25pos_(f)_hv01-09`) as above_25,
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) as total
		";

		$rows = DB::table('d_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql)
			->when($where, function($query) use ($where){
				return $query->where($where);
			})
			->whereRaw($date_query)
			->groupBy($groupBy)
			->get();

		return view('partials.hiv_tested', ['rows' => $rows, 'division' => $division]);
	}
}
