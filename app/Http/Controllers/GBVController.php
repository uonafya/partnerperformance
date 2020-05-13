<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

use App\SurgeColumn;
use App\SurgeColumnView;

class GBVController extends Controller
{
	private $my_table = 'd_gender_based_violence';


	public function violence()
	{
		$sexual = SurgeColumnView::where('modality', 'gbv_sexual')
			->when(true, $this->surge_columns_callback(false))
			->get();

		$physical = SurgeColumnView::where('modality', 'gbv_physical')
			->when(true, $this->surge_columns_callback(false))
			->get();

		$sql = $this->get_sum($sexual, 'sexual') . ', ' . $this->get_sum($physical, 'physical') . ' ';

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('sexual'))
			->get();


		$data['div'] = str_random(15);
		$data['suffix'] = '';
		$data['yAxis'] = 'Gender Based Violence Cases';
		$data['stacking'] = true;

		Lookup::bars($data, ['Sexual', 'Physical']);

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);
			$data["outcomes"][0]["data"][$key] = (int) $row->sexual;
			$data["outcomes"][1]["data"][$key] = (int) $row->physical;
		}

		$view_data = view('charts.line_graph', $data)->render() . '<br /><br /><br /> ';

		Lookup::bars($data, ['Sexual', 'Physical'], 'spline');
		$data['div'] = str_random(15);		

		$view_data .= view('charts.line_graph', $data)->render();
		return $view_data;
	}


	public function sexual()
	{
		$sexual = SurgeColumnView::where('modality', 'gbv_sexual')
			->when(true, $this->surge_columns_callback(false))
			->get();

		$pep = SurgeColumnView::where('modality', 'pep_number')
			->when(true, $this->surge_columns_callback(false))
			->get();

		$sql = $this->get_sum($sexual, 'sexual') . ', ' . $this->get_sum($pep, 'pep') . ' ';

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('sexual'))
			->get();


		$data['div'] = str_random(15);
		$data['yAxis'] = 'PEP';

		Lookup::bars($data, ['No. Receiving PEP', 'No. Not Receiving PEP', 'PEP Coverage (%)']);
		Lookup::splines($data, [2]);

		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;

		$data['outcomes'][2]['tooltip'] = ["valueSuffix" => ' %'];

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);
			$data["outcomes"][0]["data"][$key] = (int) $row->pep;
			$data["outcomes"][1]["data"][$key] = (int) ($row->sexual - $row->pep);
			$data["outcomes"][2]["data"][$key] = Lookup::get_percentage($row->pep, $row->sexual);
		}

		return view('charts.dual_axis', $data);
	}


}
