<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;
use App\Period;

use App\SurgeAge;
use App\SurgeColumn;
use App\SurgeColumnView;

class ViolenceController extends Controller
{
	private $my_table = 'd_gender_based_violence';

	public function reporting()
	{
		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw("COUNT(DISTINCT facility) AS total ")
			->whereNotNull('dateupdated')
			->when(true, $this->get_callback('total'))
			->get();

		$data['div'] = str_random(15);
		$data['yAxis'] = 'Reporting Rates';
		$data['suffix'] = '';

		Lookup::bars($data, ['Facilities Reported']);

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);
			$data["outcomes"][0]["data"][$key] = (int) $row->total;
		}

		return view('charts.line_graph', $data);
	}

	// 1a)
	public function cumulative_pie()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$violence = SurgeColumnView::whereIn('modality', ['gbv_sexual', 'gbv_physical'])
			->when(true, $this->surge_columns_callback(false))
			->get();

		$sql = $this->get_sum($violence, 'violence');

		$row = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$target_obj = DB::table('t_facility_target')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_facility_target.facility')
			->selectRaw("SUM(gbv) AS gbv")
			->whereRaw($divisions_query)
			->whereRaw(Lookup::date_query(true))
			->first();


		$periods = Period::achievement()->get()->count();

		$time_percentage = Lookup::get_percentage($periods, 12);
		if($time_percentage > 100) $time_percentage = 100;
		$data['chart_title'] = "Cumulative Achievement at {$time_percentage}% of time";

		$data['div'] = str_random(15);

		$data['outcomes']['name'] = "";
		$data['outcomes']['colorByPoint'] = true;

		$data['outcomes']['innerSize'] = '50%';

		$data['outcomes']['data'][0]['name'] = "Results";
		$data['outcomes']['data'][1]['name'] = "Gap";

		$data['outcomes']['data'][0]['color'] = "#00ff00";
		$data['outcomes']['data'][1]['color'] = "#ff0000";

		$gap = $target_obj->gbv - $row->violence;
		if($gap < 0) $gap = 0;

		$data['outcomes']['data'][0]['y'] = (int) $row->violence;
		// $data['outcomes']['data'][1]['y'] = (int) ($target_obj->gbv - $row->violence);
		$data['outcomes']['data'][1]['y'] = (int) $gap;

		return view('charts.pie_chart', $data);

	}

	// 1b)
	public function monthly_achievement()
	{
		$violence = SurgeColumnView::whereIn('modality', ['gbv_sexual', 'gbv_physical'])
			->when(true, $this->surge_columns_callback(false))
			->get();

		$sql = $this->get_sum($violence, 'violence');

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('violence'))
			->get();

		$target_obj = DB::table('t_facility_target')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_facility_target.facility')
			->selectRaw("SUM(gbv) AS gbv")
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
		$data['stack_labels'] = true;

		Lookup::bars($data, ['Results', 'Target'], 'column', ['#5c85d6', '#ff3300']);
		Lookup::splines($data, 1);

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);
			$data["outcomes"][0]["data"][$key] = (int) $row->violence;

			if(isset($target)) $data["outcomes"][1]["data"][$key] = $target;
			else{
				$t = $target_obj->where('div_id', $row->div_id)->first()->gbv ?? 0;
				$data["outcomes"][1]["data"][$key] = round(($t / $divisor), 2);
			}
		}

		return view('charts.line_graph', $data);
	}

	// 1c)
	public function performance()
	{
		$violence = SurgeColumnView::whereIn('modality', ['gbv_sexual', 'gbv_physical'])
			->when(true, $this->surge_columns_callback(false))
			->get();

		$sql = $this->get_sum($violence, 'violence');

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('violence', null, '', 1))
			->get();

		$target_obj = DB::table('t_facility_target')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_facility_target.facility')
			->selectRaw("SUM(gbv) AS gbv")
			->when(true, $this->target_callback(1))
			->get();

		$groupby = 1;
		$divisor = Lookup::get_target_divisor(1);

		$periods = Period::achievement()->get()->count();

		$time_percentage = Lookup::get_percentage($periods, 12);
		if($time_percentage > 100) $time_percentage = 100;
		$data['chart_title'] = "Performance at {$time_percentage}% of time";

		$data['div'] = str_random(15);
		$data['suffix'] = '';
		$data['suffix2'] = '%';
		$data['yAxis'] = 'Gender Based Violence Cases';
		$data['yAxis2'] = 'Achievement Percentage';
		$data['stacking'] = true;
		$data['data_labels'] = true;

		$data['outcomes'][0]['yAxis'] = 1;
		// $data['outcomes'][1]['yAxis'] = 1;

		Lookup::bars($data, ['Results', 'Achieved'], 'column', ['#5c85d6', '#ff3300']);
		Lookup::splines($data, 1, 1);

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = $row->name;
			$data["outcomes"][0]["data"][$key] = (int) $row->violence;

			if(isset($target)) $ta = $target;
			else{
				$t = $target_obj->where('div_id', $row->div_id)->first()->gbv ?? 0;
				$ta = round(($t / $divisor), 2);
			}

			$data["outcomes"][1]["data"][$key] = Lookup::get_percentage($row->violence, $ta);
		}

		$data['outcomes'][1]['tooltip'] = ["valueSuffix" => ' %'];

		return view('charts.dual_axis', $data);
	}



	// 2a) 2b)
	public function monthly_cases()
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

		Lookup::bars($data, ['Sexual', 'Physical'], 'spline');
		// Lookup::splines($data, 2);

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);
			$data["outcomes"][0]["data"][$key] = (int) $row->sexual;
			$data["outcomes"][1]["data"][$key] = (int) $row->physical;
		}

		$view_data = view('charts.line_graph', $data)->render() . '<br /><br /><br /> ';

		Lookup::bars($data, ['Sexual', 'Physical'], 'column');
		$data['div'] = str_random(15);	
		$data['stacking_percent'] = true;
		// unset($data['outcomes'][2]);	

		$view_data .= view('charts.line_graph', $data)->render();
		return $view_data;
	}

	// 3
	public function pep()
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
		$data['data_labels'] = true;
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


	// 4
	public function age_gender()
	{
		$groupby = session('filter_groupby', 1);
		$data['div'] = str_random(15);
		$data['yAxis'] = "Gender Based Violence By Age";
		$data['suffix'] = '';
		$data['stacking_percent'] = true;
		// $data['extra_tooltip'] = true;
		$data['point_percentage'] = true;
		$data['data_labels'] = true;
		Lookup::bars($data, ['Male', 'Female']);

		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();


		$ages = SurgeAge::gbv()->get();
		$sql = '';

		foreach ($ages as $key => $age) {

			$male_columns = SurgeColumnView::where('age_id', $age->id)
				->whereIn('modality', ['gbv_sexual', 'gbv_physical'])
				->where('gender_id', 1)
				->when(true, $this->surge_columns_callback(true, true, false))
				->get();

			$female_columns = SurgeColumnView::where('age_id', $age->id)
				->whereIn('modality', ['gbv_sexual', 'gbv_physical'])
				->where('gender_id', 2)
				->when(true, $this->surge_columns_callback(true, true, false))
				->get();

			$sql .= $this->get_sum($male_columns, 'male_' . $age->age) . ', ';
			$sql .= $this->get_sum($female_columns, 'female_' . $age->age) . ', ';
		}

		$sql = substr($sql, 0, -2);

		$row = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		foreach ($ages as $key => $age){
			$data['categories'][$key] = $age->age_name;

			$male_column = 'male_' . $age->age;
			$female_column = 'female_' . $age->age;

			$data["outcomes"][0]["data"][$key] = (int) $row->$male_column;
			$data["outcomes"][1]["data"][$key] = (int) $row->$female_column;
		}
		return view('charts.line_graph', $data);

	}


}
