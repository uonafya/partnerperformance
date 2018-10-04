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

		$rows = DB::table('m_testing')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_testing.facility')
			->selectRaw("SUM(testing_total) AS tests, SUM(positive_total) as pos")
			->when(true, $this->get_callback('tests'))
			->whereRaw($date_query)
			->get();

		$sql2 = "
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) AS pos,
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) AS tests
		";

		$target_obj = DB::table('t_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql2)
			->when(true, $this->target_callback())
			->get();

		$groupby = session('filter_groupby', 1);
		$divisor = Lookup::get_target_divisor();

		if($groupby > 9){
			$t = $target_obj->first()->tests;
			$target = round(($t / $divisor), 2);
		}

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Positive Tests";
		$data['outcomes'][1]['name'] = "Negative Tests";
		$data['outcomes'][2]['name'] = "Target";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "spline";

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->pos;
			$data["outcomes"][1]["data"][$key] = (int) ($row->tests - $row->pos);
			if(isset($target)) $data["outcomes"][2]["data"][$key] = $target;
			else{
				$t = $target_obj->where('div_id', $row->div_id)->first()->tests ?? 0;
				$data["outcomes"][2]["data"][$key] = round(($t / $divisor), 2);
			}
		}	
		return view('charts.bar_graph', $data);
	}

	public function positivity()
	{
		$date_query = Lookup::date_query();

		$rows = DB::table('m_testing')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_testing.facility')
			->selectRaw("SUM(testing_total) AS tests, SUM(positive_total) as pos")
			->when(true, $this->get_callback('tests'))
			->whereRaw($date_query)
			->get();

		$sql2 = "
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) AS pos,
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) AS tests
		";

		$target_obj = DB::table('t_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql2)
			->when(true, $this->target_callback())
			->get();

		$groupby = session('filter_groupby', 1);
		$divisor = Lookup::get_target_divisor();

		if($groupby > 9){
			$t = $target_obj->first()->tests;
			$p = $target_obj->first()->pos;
			$target_tests = round(($t / $divisor), 2);
			$target_pos = round(($p / $divisor), 2);

			$target = Lookup::get_percentage($p, $t);
		}

		$data['div'] = str_random(15);
		$data['ytitle'] = 'Percentage';

		$data['outcomes'][0]['name'] = "Positivity";
		$data['outcomes'][1]['name'] = "Targeted Positivity";

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = Lookup::get_percentage($row->pos, $row->tests);
			if(isset($target)) $data["outcomes"][1]["data"][$key] = $target;
			else{
				$obj = $target_obj->where('div_id', $row->div_id)->first();
				$target_tests = round(($obj->tests / $divisor), 2);
				$target_pos = round(($obj->pos / $divisor), 2);
				$data["outcomes"][1]["data"][$key] = Lookup::get_percentage($target_pos, $target_tests);
			}
		}	
		return view('charts.line_graph', $data);
	}

}
