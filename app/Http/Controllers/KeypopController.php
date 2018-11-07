<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class KeypopController extends Controller
{
	
	public function testing()
	{
		$date_query = Lookup::date_query();
    	$groupby = session('filter_groupby', 1);

		$rows = DB::table('m_keypop')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_keypop.facility')
			->selectRaw("SUM(tested) AS tests, SUM(positive) as pos")
			->when(true, $this->get_callback('tests'))
			->whereRaw($date_query)
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Positive Tests";
		$data['outcomes'][1]['name'] = "Negative Tests";
		$data['outcomes'][2]['name'] = "Positivity";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "spline";

		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');

		if($groupby < 10){
			$data['outcomes'][2]['lineWidth'] = 0;
			$data['outcomes'][2]['marker'] = ['enabled' => true, 'radius' => 4];
			$data['outcomes'][2]['states'] = ['hover' => ['lineWidthPlus' => 0]];
		}

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			if($row->tests < $row->pos) $row->tests += $row->pos;

			$data["outcomes"][0]["data"][$key] = (int) $row->pos;
			$data["outcomes"][1]["data"][$key] = (int) $row->tests - $row->pos;
			if(!$row->tests) $data["outcomes"][1]["data"][$key] = 0;

			$data["outcomes"][2]["data"][$key] = Lookup::get_percentage($row->pos, $row->tests);
			if(!$row->tests) $data["outcomes"][2]["data"][$key] = 100;

		}
		return view('charts.dual_axis', $data);
	}

	public function current_tx()
	{		
		$date_query = Lookup::date_query();
		$groupby = session('filter_groupby', 1);

		if($groupby != 12) $date_query = Lookup::year_month_query();


		$rows = DB::table('m_keypop')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_keypop.facility')
			->selectRaw("SUM(current_tx) AS total")
			->when(true, $this->get_callback('total'))
			->whereRaw($date_query)
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Current On Treatment";
		$data['outcomes'][0]['type'] = "column";

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->total;
		}
		return view('charts.bar_graph', $data);
	}

	public function summary()
	{
		$date_query = Lookup::date_query();
		$data = Lookup::table_data();

		$data['rows'] = DB::table('m_keypop')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_keypop.facility')
			->selectRaw("SUM(tested) AS tests, SUM(positive) AS pos, SUM(new_tx) AS new_tx")
			->when(true, $this->get_callback('tests'))
			->whereRaw($date_query)
			->get();

		return view('tables.keypop_summary', $data);
	}	



}
