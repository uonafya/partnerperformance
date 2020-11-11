<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class CircumcisionController extends Controller
{
	private $my_table = 'm_circumcision';

	public function testing()
	{
		$groupby = session('filter_groupby', 1);

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw("SUM(circumcised_neg) AS neg, SUM(circumcised_pos) as pos, SUM(circumcised_nk) as unknown,
				(SUM(circumcised_neg) + SUM(circumcised_pos) + SUM(circumcised_nk)) AS total
				")
			->when(true, $this->get_callback('total'))
			->get();

		$data['div'] = str_random(15);

		Lookup::bars($data, ["Positive", "Negative", "Unknown Status", "Positivity"], "column");

		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;
		$data['outcomes'][2]['yAxis'] = 1;

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][3]['tooltip'] = array("valueSuffix" => ' %');


		Lookup::splines($data, [3]);

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->pos;
			$data["outcomes"][1]["data"][$key] = (int) $row->neg;
			$data["outcomes"][2]["data"][$key] = (int) $row->unknown;

			$data["outcomes"][3]["data"][$key] = Lookup::get_percentage($row->pos, $row->total);

		}
		return view('charts.dual_axis', $data);
	}

	public function summary()
	{		
		$data = Lookup::table_data();

		$sql = "SUM(circumcised_below1) AS below1, SUM(circumcised_below10) AS below10, 
			SUM(circumcised_below15) AS below15, SUM(circumcised_below20) AS below20, 
			SUM(circumcised_below25) AS below25, SUM(circumcised_above25) AS above25, 
			SUM(circumcised_total) AS total";

		$data['rows'] = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('circumcised_total'))
			->get();

		return view('tables.circumcision_summary', $data);
	}

	public function adverse()
	{		
		$data = Lookup::table_data();

		$sql = "SUM(ae_during_moderate) AS ae_during_moderate, SUM(ae_during_severe) AS ae_during_severe, 
			SUM(ae_post_moderate) AS ae_post_moderate, SUM(ae_post_severe) AS ae_post_severe, 
			(SUM(ae_during_moderate) + SUM(ae_during_severe) + SUM(ae_post_moderate) + SUM(ae_post_severe)) as total";

		$data['rows'] = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('total'))
			->get();

		return view('tables.circumcision_ae', $data);
	}
}
