<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

// current on treatment
// new on treatment

class TableController extends Controller
{


	public function summary()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();
		$q = Lookup::groupby_query();

		$sql = $q['select_query'] .  ",
			SUM(`tested_1-9_hv01-01` + `tested_10-14_(m)_hv01-02` + `tested_10-14(f)_hv01-03` + `tested_15-19_(m)_hv01-04` + `tested_15-19(f)_hv01-05` + `tested_20-24(m)_hv01-06` + `tested_20-24(f)_hv01-07` + `tested_25pos_(m)_hv01-08` + `tested_25pos_(f)_hv01-09`) AS `tested_total`,
			SUM(`positive_1-9_hv01-17` + `positive_10-14(m)_hv01-18` + `positive_10-14(f)_hv01-19` + `positive_15-19(m)_hv01-20` + `positive_15-19(f)_hv01-21` + `positive_20-24(m)_hv01-22` + `positive_20-24(f)_hv01-23` + `positive_25pos(m)_hv01-24` + `positive_25pos(f)_hv01-25`) AS `positive_total`,
			SUM(`linked_1-9_yrs_hv01-30` + `linked_10-14_hv01-31` + `linked_15-19_hv01-32` + `linked_20-24_hv01-33` + `linked_25pos_hv01-34`) AS `linked_total`
		";

		$data['div'] = str_random(15);

		// DB::enableQueryLog();

		$data['rows'] = DB::table('d_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		// return DB::getQueryLog();

		return view('dynamic_tables.testing_summary', $data);
	}

	public function art_new()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();
		$q = Lookup::groupby_query();

		$sql = $q['select_query'] . ', ' . $this->new_art_query();

		$data['div'] = str_random(15);

		// DB::enableQueryLog();

		$data['rows'] = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		// return DB::getQueryLog();

		return view('dynamic_tables.art_totals', $data);

	}
}
