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
			->selectRaw($this->new_art_query())
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

		$sql2 = $q['select_query'] .  ",
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) AS pos,
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) AS tests
		";

		$target = DB::table('t_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql2)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->first();

		$t = round(($target->tests / 12), 2);

		$data['outcomes'][0]['name'] = "Positive Tests";
		$data['outcomes'][1]['name'] = "Negative Tests";
		$data['outcomes'][2]['name'] = "Monthly Target";
		$data['outcomes'][3]['name'] = "Positivity";
		$data['outcomes'][4]['name'] = "Targeted Positivity";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "spline";
		$data['outcomes'][4]['type'] = "spline";

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][3]['tooltip'] = array("valueSuffix" => ' %');
		$data['outcomes'][4]['tooltip'] = array("valueSuffix" => ' %');


		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);
			$pos = $row->pos + $rows2[$key]->pos;
			$neg = $row->tests + $rows2[$key]->tests - $pos;

			$data["outcomes"][0]["data"][$key] = (int) $pos;
			$data["outcomes"][1]["data"][$key] = (int) $neg;
			$data["outcomes"][2]["data"][$key] = $t;
			$data["outcomes"][3]["data"][$key] = Lookup::get_percentage($pos, ($pos + $neg));
			$data["outcomes"][4]["data"][$key] = Lookup::get_percentage($target->pos, $target->tests);
		}
		return view('charts.dual_axis', $data);		
	}
}
