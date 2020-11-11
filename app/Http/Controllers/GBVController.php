<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

use App\SurgeAge;
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

		$target_obj = DB::table('t_facility_target')
			->join('view_facilities', 'view_facilities.id', '=', 't_facility_target.facility')
			->selectRaw("SUM(gbv) AS gbv")
			->whereRaw(Lookup::active_partner_query())
			->when(true, $this->target_callback())
			->get();

		$groupby = session('filter_groupby', 1);
		$divisor = Lookup::get_target_divisor();

		if($groupby > 9){
			$t = $target_obj->first()->gbv;
			$target = round(($t / $divisor), 2);
		}


		$data['div'] = str_random(15);
		$data['suffix'] = '';
		$data['yAxis'] = 'Gender Based Violence Cases';
		$data['stacking'] = true;

		Lookup::bars($data, ['Sexual', 'Physical', 'Target']);
		Lookup::splines($data, 2);

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);
			$data["outcomes"][0]["data"][$key] = (int) $row->sexual;
			$data["outcomes"][1]["data"][$key] = (int) $row->physical;

			if(isset($target)) $data["outcomes"][2]["data"][$key] = $target;
			else{
				$t = $target_obj->where('div_id', $row->div_id)->first()->gbv ?? 0;
				$data["outcomes"][2]["data"][$key] = round(($t / $divisor), 2);
			}
		}

		$view_data = view('charts.line_graph', $data)->render() . '<br /><br /><br /> ';

		Lookup::bars($data, ['Sexual', 'Physical'], 'spline');
		$data['div'] = str_random(15);	
		unset($data['outcomes'][2]);	

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

	public function gender()
	{
		$male = SurgeColumnView::where('gender_id', 1)
			->whereIn('modality', ['gbv_sexual', 'gbv_physical'])
			->when(true, $this->surge_columns_callback(true, false))
			->get();

		$female = SurgeColumnView::where('gender_id', 2)
			->whereIn('modality', ['gbv_sexual', 'gbv_physical'])
			->when(true, $this->surge_columns_callback(true, false))
			->get();

		$sql = $this->get_sum($male, 'male') . ', ' . $this->get_sum($female, 'female') . ' ';

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('male'))
			->get();

		$data['div'] = str_random(15);
		$data['suffix'] = '';
		$data['yAxis'] = 'Gender Based Violence By Gender';
		$data['stacking'] = true;
		$data['point_percentage'] = true;
		Lookup::bars($data, ['Male', 'Female']);

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);
			$data["outcomes"][0]["data"][$key] = (int) $row->male;
			$data["outcomes"][1]["data"][$key] = (int) $row->female;
		}
		return view('charts.line_graph', $data);
	}

	public function age()
	{
		$groupby = session('filter_groupby', 1);
		$data['div'] = str_random(15);
		$data['yAxis'] = "Gender Based Violence By Age";
		$data['suffix'] = '';
		$data['stacking'] = true;
		// $data['extra_tooltip'] = true;
		$data['point_percentage'] = true;


		$ages = SurgeAge::gbv()->get();
		$sql = '';

		foreach ($ages as $key => $age) {

			$gbv_columns = SurgeColumnView::where('age_id', $age->id)
				->whereIn('modality', ['gbv_sexual', 'gbv_physical'])
				->when(true, $this->surge_columns_callback(true, true, false))
				->get();

			$sql .= $this->get_sum($gbv_columns, $age->age) . ', ';

			$data['outcomes'][$key]['name'] = $age->age_name;
			$data['outcomes'][$key]['type'] = "column";
		}

		$sql = substr($sql, 0, -2);

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback())
			->get();

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			foreach ($ages as $age_key => $age) {
				$p = $age->age;
				$data["outcomes"][$age_key]["data"][$key]['y'] = (int) $row->$p;
				// $data["outcomes"][$age_key]["data"][$key]['z'] = ', yield of ' .  Lookup::get_percentage($row->$p, $row->$t) . '%';
			}
		}
		return view('charts.line_graph', $data);

	}


}
