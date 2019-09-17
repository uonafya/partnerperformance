<?php

namespace App\Http\Controllers\Former;

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
			(SUM(`male_under_15yrs_starting_on_art`) + SUM(`female_under_15yrs_starting_on_art`)) as below_15,
			(SUM(`male_above_15yrs_starting_on_art`) + SUM(`female_above_15yrs_starting_on_art`)) as above_15,
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
			(MAX(`currently_on_art_-_male_below_15_years`) + MAX(`female_under_15yrs_currently_in_care`)) as below_15,
			(MAX(`currently_on_art_-_male_above_15_years`) + MAX(`currently_on_art_-_female_above_15_years`)) as above_15,
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

	public function new_summary()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();
		$q = Lookup::groupby_query();

		$sql = $q['select_query'] .  ",
			SUM(`total_tested_hiv`) AS tests,
			SUM(`total_received_hivpos_results`) AS pos,
			SUM(`first_testing_hiv`) AS first_testing_hiv
		";

		$sql2 = $q['select_query'] .  ",
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) AS pos,
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) AS tests
		";

		$data['div'] = str_random(15);

		// DB::enableQueryLog();

		$data['rows'] = DB::table('d_hiv_counselling_and_testing')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_counselling_and_testing.facility')
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		// return DB::getQueryLog();

		$date_query = Lookup::date_query(true);

		$data['targets'] = DB::table('t_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql2)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		return view('dynamic_tables.test_old_summary', $data);
	}

	public function summary_breakdown()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();
		$q = Lookup::groupby_query();

		$sql = $q['select_query'] .  ",
			SUM(`total_tested_hiv`) AS tests,
			SUM(`first_testing_hiv`) AS first_testing_hiv,
			SUM(`repeat_testing_hiv`) AS repeat_testing_hiv,
			SUM(`outreach_testing_hiv`) AS outreach_testing_hiv,
			SUM(`static_testing_hiv_(health_facility)`) AS static_testing,
			SUM(`couples_testing`) AS couples_testing
		";

		$data['div'] = str_random(15);

		// DB::enableQueryLog();

		$data['rows'] = DB::table('d_hiv_counselling_and_testing')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_counselling_and_testing.facility')
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		// return DB::getQueryLog();

		return view('dynamic_tables.summary_breakdown', $data);
	}


}
