<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class TestingController extends Controller
{

	public function testing_outcomes()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$sql = "
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) AS tests,
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) AS pos
		";

		$rows = DB::table('d_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql)
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$sql = "
			SUM(`total_tested_hiv`) AS tests,
			SUM(`total_received_hivpos_results`) AS pos
		";

		$rows2 = DB::table('d_hiv_counselling_and_testing')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_counselling_and_testing.facility')
			->selectRaw($sql)
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$sql2 = "
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) AS pos,
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) AS tests
		";
		
		$date_query = Lookup::date_query(true);

		$target_obj = DB::table('t_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql2)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$target = round(($target_obj->tests / 12), 2);

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Positive Tests";
		$data['outcomes'][1]['name'] = "Negative Tests";
		$data['outcomes'][2]['name'] = "Monthly Target";
		// $data['outcomes'][3]['name'] = "Positivity";
		// $data['outcomes'][4]['name'] = "Targeted Positivity";

		// $data['outcomes'][0]['yAxis'] = 1;
		// $data['outcomes'][1]['yAxis'] = 1;
		// $data['outcomes'][2]['yAxis'] = 1;

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "spline";
		// $data['outcomes'][3]['type'] = "spline";
		// $data['outcomes'][4]['type'] = "spline";

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' ');
		// $data['outcomes'][3]['tooltip'] = array("valueSuffix" => ' %');
		// $data['outcomes'][4]['tooltip'] = array("valueSuffix" => ' %');

		$old_table = "`d_hiv_counselling_and_testing`";
		$new_table = "`d_hiv_testing_and_prevention_services`";

		$old_column = "`total_received_hivpos_results`";
		$new_column = "`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`";



		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);

			// $duplicate_pos = DB::table('d_hiv_counselling_and_testing')
			// 				->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_counselling_and_testing.facility')
			// 				->selectRaw("SUM(`total_received_hivpos_results`) AS pos")
			// 				->where(['year' => $row->year, 'month' => $row->month])
			// 				->whereRaw("facility IN (
			// 					SELECT DISTINCT facility
			// 					FROM d_hiv_testing_and_prevention_services d JOIN view_facilitys f ON d.facility=f.id
			// 					WHERE  {$divisions_query} AND `positive_total_(sum_hv01-18_to_hv01-27)_hv01-26` > 0 AND 
			// 					year = {$row->year} AND month = {$row->month}
			// 				)")
			// 				->first();


			// $duplicate_pos = DB::select(
			// 	DB::raw("call proc_get_duplicate_total(?, ?, ?, ?, ?, ?, ?)", 
			// 		[$old_table, $new_table, $old_column, $new_column, $divisions_query, $row->year, $row->month]
			// 	))->first();

			$duplicate_pos = DB::selectRaw("call proc_get_duplicate_total(?, ?, ?, ?, ?, ?, ?)", 
					[$old_table, $new_table, $old_column, $new_column, $divisions_query, $row->year, $row->month]
				)->first();

			$duplicate_tests = DB::table('d_hiv_counselling_and_testing')
							->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_counselling_and_testing.facility')
							->selectRaw("SUM(`total_tested_hiv`) AS tests")
							->where(['year' => $row->year, 'month' => $row->month])
							->whereRaw("facility IN (
								SELECT DISTINCT facility
								FROM d_hiv_testing_and_prevention_services d JOIN view_facilitys f ON d.facility=f.id
								WHERE  {$divisions_query} AND `tested_total_(sum_hv01-01_to_hv01-10)_hv01-10` > 0 AND 
								year = {$row->year} AND month = {$row->month}
							)")
							->first();

			$tests = $row->tests + $rows2[$key]->tests;
			if(is_object($duplicate_tests)) $tests -= $duplicate_tests->tests;

			$pos = $row->pos + $rows2[$key]->pos;
			if(is_object($duplicate_pos)) $pos -= $duplicate_pos->pos;

			$neg = $tests - $pos;

			$data["outcomes"][0]["data"][$key] = (int) $pos;
			$data["outcomes"][1]["data"][$key] = (int) $neg;
			$data["outcomes"][2]["data"][$key] = $target;



			// $data["outcomes"][3]["data"][$key] = Lookup::get_percentage($pos, ($pos + $neg));
			// $data["outcomes"][4]["data"][$key] = Lookup::get_percentage($target->pos, $target->tests);
		}
		// return view('charts.dual_axis', $data);		
		return view('charts.bar_graph', $data);		
	}


	public function positivity()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$sql = "
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) AS tests,
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) AS pos
		";

		$rows = DB::table('d_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql)
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$sql = "
			SUM(`total_tested_hiv`) AS tests,
			SUM(`total_received_hivpos_results`) AS pos
		";

		$rows2 = DB::table('d_hiv_counselling_and_testing')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_counselling_and_testing.facility')
			->selectRaw($sql)
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$sql2 = "
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) AS pos,
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) AS tests
		";

		$date_query = Lookup::date_query(true);
		$target = DB::table('t_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql2)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$t = round(($target->tests / 12), 2);

		$data['div'] = str_random(15);
		$data['ytitle'] = 'Percentage';

		$data['outcomes'][0]['name'] = "Positivity";
		$data['outcomes'][1]['name'] = "Targeted Positivity";


		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);

			$duplicate_pos = DB::table('d_hiv_counselling_and_testing')
							->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_counselling_and_testing.facility')
							->selectRaw("SUM(`total_received_hivpos_results`) AS pos")
							->where(['year' => $row->year, 'month' => $row->month])
							->whereRaw("facility IN (
								SELECT DISTINCT facility
								FROM d_hiv_testing_and_prevention_services d JOIN view_facilitys f ON d.facility=f.id
								WHERE  {$divisions_query} AND `positive_total_(sum_hv01-18_to_hv01-27)_hv01-26` > 0 AND 
								year = {$row->year} AND month = {$row->month}
							)")
							->first();

			$duplicate_tests = DB::table('d_hiv_counselling_and_testing')
							->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_counselling_and_testing.facility')
							->selectRaw("SUM(`total_tested_hiv`) AS tests")
							->where(['year' => $row->year, 'month' => $row->month])
							->whereRaw("facility IN (
								SELECT DISTINCT facility
								FROM d_hiv_testing_and_prevention_services d JOIN view_facilitys f ON d.facility=f.id
								WHERE  {$divisions_query} AND `tested_total_(sum_hv01-01_to_hv01-10)_hv01-10` > 0 AND 
								year = {$row->year} AND month = {$row->month}
							)")
							->first();
							
			$tests = $row->tests + $rows2[$key]->tests;
			if(is_object($duplicate_tests)) $tests -= $duplicate_tests->tests;

			$pos = $row->pos + $rows2[$key]->pos;
			if(is_object($duplicate_pos)) $pos -= $duplicate_pos->pos;

			$data["outcomes"][0]["data"][$key] = Lookup::get_percentage($pos, $tests);
			$data["outcomes"][1]["data"][$key] = Lookup::get_percentage($target->pos, $target->tests);
		}
		return view('charts.line_graph', $data);		
	}

	public function summary()
	{
		$date_query = Lookup::date_query();
		$year_month_query = Lookup::year_month_query();
		$divisions_query = Lookup::divisions_query();
		$q = Lookup::groupby_query();


		$sql = $q['select_query'] . ",
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) AS tests,
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) AS pos
		";

		$data['rows'] = DB::table('d_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		$sql = $q['select_query'] . ",
			SUM(`total_tested_hiv`) AS tests,
			SUM(`total_received_hivpos_results`) AS pos
		";

		$data['rows2'] = DB::table('d_hiv_counselling_and_testing')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_counselling_and_testing.facility')
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		$data['duplicate_tests'] = DB::table('d_hiv_counselling_and_testing')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_counselling_and_testing.facility')
			->selectRaw($q['select_query'] . ", SUM(`total_tested_hiv`) AS tests")
			->whereRaw($date_query)
			->whereRaw("facility IN (
				SELECT DISTINCT facility
				FROM d_hiv_testing_and_prevention_services d JOIN view_facilitys f ON d.facility=f.id
				WHERE  {$divisions_query} AND {$date_query} AND `tested_total_(sum_hv01-01_to_hv01-10)_hv01-10` > 0
			)")
			->groupBy($q['group_query'])
			->get();

		$data['duplicate_pos'] = DB::table('d_hiv_counselling_and_testing')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_counselling_and_testing.facility')
			->selectRaw($q['select_query'] . ", SUM(`total_received_hivpos_results`) AS pos")
			->whereRaw($date_query)
			->whereRaw("facility IN (
				SELECT DISTINCT facility
				FROM d_hiv_testing_and_prevention_services d JOIN view_facilitys f ON d.facility=f.id
				WHERE  {$divisions_query} AND {$date_query} AND `positive_total_(sum_hv01-18_to_hv01-27)_hv01-26` > 0
			)")
			->groupBy($q['group_query'])
			->get();

		$data['linked'] = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw($q['select_query'] . ", SUM(`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026`) as total")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		$data['linked_old'] = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw($q['select_query'] . ", SUM(`total_starting_on_art`) as total")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		$data['duplicate_linked'] = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw($q['select_query'] . ", SUM(`total_starting_on_art`) AS linked")
			->whereRaw($date_query)
			->whereRaw("facility IN (
				SELECT DISTINCT facility
				FROM d_hiv_and_tb_treatment d JOIN view_facilitys f ON d.facility=f.id
				WHERE  {$divisions_query} AND {$date_query} AND `start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026` > 0
			)")
			->groupBy($q['group_query'])
			->get();


		$sql2 = $q['select_query'] . ",
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) AS pos,
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) AS tests
		";

		$date_query = Lookup::date_query(true);
		$data['targets'] = DB::table('t_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql2)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		$data['div'] = str_random(15);

		return view('combined.summary', $data);
	}




}
