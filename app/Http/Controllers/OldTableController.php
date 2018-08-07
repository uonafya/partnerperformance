<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class OldTableController extends Controller
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

		$sql = $q['select_query'] . ",
			SUM(`under_1yr_starting_on_art`) as below_1,
			SUM(`start_art_10-14(m)_hv03-018` + `female_under_15yrs_starting_on_art`) as below_15,
			SUM(`female_above_15yrs_starting_on_art`) as above_15,
			SUM(`total_starting_on_art`) as total
		";

		$data['div'] = str_random(15);

		// DB::enableQueryLog();

		$data['rows'] = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		// return DB::getQueryLog();

		return view('dynamic_tables.art_totals_old', $data);
	}

	public function art_current()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();
		$q = Lookup::groupby_query();

		$current_query = "
			MAX(`currently_on_art_-_below_1_year`) as below_1,
			MAX(`currently_on_art_-_male_below_15_years` + `female_under_15yrs_currently_in_care`) as below_15,
			MAX(`currently_on_art_-_male_above_15_years` + `currently_on_art_-_female_above_15_years`) as above_15,
			MAX(`total_currently_on_art`) as total
		";

		$sql = $q['select_query'] . ",
			SUM(below_1) as below_1, SUM(below_15) AS below_15, SUM(above_15) AS above_15, SUM(total) AS total
		";

		$data['div'] = str_random(15);

		// DB::enableQueryLog();

		$subquery = "(
			SELECT facility, {$current_query}
			FROM `d_care_and_treatment`
			JOIN `view_facilitys` ON `view_facilitys`.`id`=`d_care_and_treatment`.`facility`
			WHERE {$divisions_query} AND {$date_query}
			GROUP BY facility
		) qu";

		$data['rows'] = DB::table(DB::raw($subquery))
			->join('view_facilitys', 'view_facilitys.id', '=', 'qu.facility')
			->selectRaw($sql)
			->groupBy($q['group_query'])
			->get();

		// return DB::getQueryLog();

		return view('dynamic_tables.art_totals_old', $data);
	}
}
