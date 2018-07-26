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
		return $this->data_set_two('tested_query');
	}

	public function positive()
	{
		return $this->data_set_two('positives_query');
	}

	public function linked()
	{
		return $this->data_set_two('linked_query');
	}

	public function summary_tests()
	{
		$d = $this->pre_partners();
		$where = $d['where'];
		$base = $d['sql'];

		$sql = $base . $this->tested_query();


	}

	/*
	public function tested()
	{
		$d = $this->pre_partners();
		$where = $d['where'];
		$sql = $d['sql'];

		$sql .= "
			SUM(`tested_1-9_hv01-01`) as below_10,
			SUM(`tested_10-14_(m)_hv01-02` + `tested_10-14(f)_hv01-03`) as below_15,
			SUM(`tested_15-19_(m)_hv01-04` + `tested_15-19(f)_hv01-05`) as below_20,
			SUM(`tested_20-24(m)_hv01-06` + `tested_20-24(f)_hv01-07`) as below_25,
			SUM(`tested_25pos_(m)_hv01-08` + `tested_25pos_(f)_hv01-09`) as above_25,
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) as total
		";

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

		return view('partials.hiv_tested', ['rows' => $rows, 'division' => $d['division'], 'div' => str_random(15)]);
	}

	public function positive()
	{
		$d = $this->pre_partners();
		$where = $d['where'];
		$sql = $d['sql'];

		$sql .= "
			SUM(`positive_1-9_hv01-17`) as below_10,
			SUM(`positive_10-14(m)_hv01-18` + `positive_10-14(f)_hv01-19`) as below_15,
			SUM(`positive_15-19(m)_hv01-20` + `positive_15-19(f)_hv01-21`) as below_20,
			SUM(`positive_20-24(m)_hv01-22` + `positive_20-24(f)_hv01-23`) as below_25,
			SUM(`positive_25pos(m)_hv01-24` + `positive_25pos(f)_hv01-25`) as above_25,
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) as total
		";

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

	public function linked()
	{
		$d = $this->pre_partners();
		$where = $d['where'];
		$sql = $d['sql'];

		$sql .= "
			SUM(`linked_1-9_yrs_hv01-30`) as below_10,
			SUM(`linked_10-14_hv01-31`) as below_15,
			SUM(`linked_15-19_hv01-32`) as below_20,
			SUM(`linked_20-24_hv01-33`) as below_25,
			SUM(`linked_25pos_hv01-34`) as above_25,
			SUM(`linked_total_hv01-35`) as total
		";

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
	*/

}
