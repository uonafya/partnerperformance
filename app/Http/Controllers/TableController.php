<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class TableController extends Controller
{


	public function summary()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();
		$q = Lookup::groupby_query();

		$sql = $q['select_query'] .  ",
			(SUM(`tested_1-9_hv01-01`) + SUM(`tested_10-14_(m)_hv01-02`) + SUM(`tested_10-14(f)_hv01-03`) + SUM(`tested_15-19_(m)_hv01-04`) + SUM(`tested_15-19(f)_hv01-05`) + SUM(`tested_20-24(m)_hv01-06`) + SUM(`tested_20-24(f)_hv01-07`) + SUM(`tested_25pos_(m)_hv01-08`) + SUM(`tested_25pos_(f)_hv01-09`)) AS `tested_total`,
			(SUM(`positive_1-9_hv01-17`) + SUM(`positive_10-14(m)_hv01-18`) + SUM(`positive_10-14(f)_hv01-19`) + SUM(`positive_15-19(m)_hv01-20`) + SUM(`positive_15-19(f)_hv01-21`) + SUM(`positive_20-24(m)_hv01-22`) + SUM(`positive_20-24(f)_hv01-23`) + SUM(`positive_25pos(m)_hv01-24`) + SUM(`positive_25pos(f)_hv01-25`)) AS `positive_total`,
			(SUM(`linked_1-9_yrs_hv01-30`) + SUM(`linked_10-14_hv01-31`) + SUM(`linked_15-19_hv01-32`) + SUM(`linked_20-24_hv01-33`) + SUM(`linked_25pos_hv01-34`)) AS `linked_total`
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

	public function art_current()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();
		$q = Lookup::groupby_query();

		$current_query = $this->current_art_fixed_query();

		$sql = $q['select_query'] . ",
			SUM(below_1) as below_1, SUM(below_10) AS below_10, SUM(below_15) AS below_15, SUM(below_20) AS below_20, SUM(below_25) AS below_25, SUM(above_25) AS above_25, SUM(total) AS total
		";

		$data['div'] = str_random(15);

		// DB::enableQueryLog();

		$subquery = "(
			SELECT facility, {$current_query}
			FROM `d_hiv_and_tb_treatment`
			JOIN `view_facilitys` ON `view_facilitys`.`id`=`d_hiv_and_tb_treatment`.`facility`
			WHERE {$divisions_query} AND {$date_query}
			GROUP BY facility
		) qu";

		$data['rows'] = DB::table(DB::raw($subquery))
			->join('view_facilitys', 'view_facilitys.id', '=', 'qu.facility')
			->selectRaw($sql)
			->groupBy($q['group_query'])
			->get();

		// return DB::getQueryLog();

		return view('dynamic_tables.art_totals', $data);
	}
}
