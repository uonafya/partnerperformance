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


	public function modality_reported()
	{
		$period = Period::where(['financial_year' => 2021])->first();

		$completed_pep = SurgeColumnView::where('modality', 'completed_pep')
			->when(true, $this->surge_columns_callback(false))
			->get();

		$completed_pep_sql = $this->get_sum($completed_pep, 'completed_pep');


		$sql = "
			SELECT v.partnername, v.partner, COUNT(s.facility) AS total_facilities_reported
			FROM (
				SELECT facility, {$completed_pep_sql}
				FROM d_gender_based_violence
				WHERE period_id >= {$period->id}
				GROUP BY facility
				HAVING completed_pep > 0
			) s
			JOIN view_facilitys v ON v.id=s.facility
			WHERE v.funding_agency_id=1
			GROUP BY v.partner
		";

		$rows = DB::select($sql);

		dd($rows);
	}

	public function pep_reported()
	{
		$pep_number = SurgeColumnView::where('modality', 'pep_number')
			->when(true, $this->surge_columns_callback(false))
			->get();

		$completed_pep = SurgeColumnView::where('modality', 'completed_pep')
			->when(true, $this->surge_columns_callback(false))
			->get();

		$sql = $this->get_sum($pep_number, 'pep_number') . ', ' . $this->get_sum($completed_pep, 'completed_pep');

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('pep_number', null, '', 1))
			->get();


		$data['div'] = \Str::random(15);
		$data['suffix'] = '';
		$data['yAxis'] = 'PEP Numbers';
		// $data['stacking'] = true;
		// $data['point_percentage'] = true;
		Lookup::bars($data, ['Started PEP', 'Completed PEP']);

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = $row->name;
			$data["outcomes"][0]["data"][$key] = (int) $row->pep_number;
			$data["outcomes"][1]["data"][$key] = (int) $row->completed_pep;
		}
		return view('charts.line_graph', $data);

	} 


	public function new_reporting()
	{
		$periods = Period::whereRaw(Lookup::date_query())->get();

		$partners = DB::table('partners')->where(['funding_agency_id' => 1, 'flag' => 1])->get();

		$data['div'] = \Str::random(15);
		$data['yAxis'] = 'Number of Facilities Reported';
		$data['suffix'] = '';
		// $data['chart_title'] = "Reporting For {$period->year}, {$period->month_name} " ;


		foreach ($periods as $key => $period) {
			$data['categories'][$key] = $period->name;

			if(!$key){
				$partners_array = [];
				foreach ($partners as $partner) {
					$partners_array[] = $partner->name;
				}
				Lookup::bars($data, $partners_array, 'spline');
			}

			$rows = DB::table($this->my_table)
			->join('view_facilities', 'view_facilities.id', '=', "{$this->my_table}.facility")
            // ->join('periods', 'periods.id', '=', "{$this->my_table}.period_id")
			->selectRaw("partner as div_id, COUNT(DISTINCT facility) AS total ")
			->whereNotNull('dateupdated')
            ->whereRaw(Lookup::active_partner_query($period->active_date))
			->where('period_id', $period->id)
			->groupBy('partner')
			->get();

			foreach($partners as $partner_key => $partner) {
				$data["outcomes"][$partner_key]["data"][$key] = (int) ($rows->where('div_id', $partner->id)->first()->total ?? 0);
			}
		}
		return view('charts.line_graph', $data);
	}


	public function reporting()
	{
		$period = Period::lastMonth()->first();

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw("partner as div_id, partnername as name, COUNT(DISTINCT facility) AS total ")
			->whereNotNull('dateupdated')
			->where('period_id', $period->id)
			->groupBy('partner')
			->get();

		$partners = DB::table('partners')->where(['funding_agency_id' => 1, 'flag' => 1])->get();

		$data['div'] = \Str::random(15);
		$data['yAxis'] = 'Number of Facilities Reported';
		$data['suffix'] = '';
		$data['chart_title'] = "Reporting For {$period->year}, {$period->month_name} " ;

		Lookup::bars($data, ['Facilities Reported']);

		foreach ($partners as $key => $partner) {
			$data['categories'][$key] = $partner->name;
			$data["outcomes"][0]["data"][$key] = (int) ($rows->where('div_id', $partner->id)->first()->total ?? 0);
		}

		/*foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);
			$data["outcomes"][0]["data"][$key] = (int) $row->total;
		}*/

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
			->join('view_facilities', 'view_facilities.id', '=', 't_facility_target.facility')
			->selectRaw("SUM(gbv) AS gbv")
			->whereRaw($divisions_query)
			->whereRaw(Lookup::date_query(true))
			->whereRaw(Lookup::active_partner_query())
			->first();

		$wards_target_obj = DB::table('t_ward_target')
			->join('view_wards', 'view_wards.id', '=', 't_ward_target.ward_id')
			->selectRaw("SUM(gbv) AS gbv")
			->whereRaw(Lookup::divisions_query(true))
			->whereRaw(Lookup::date_query(true))
			->first();

		$county_target_obj = DB::table('t_county_target')
			->join('countys', 'countys.id', '=', 't_county_target.county_id')
			->join('partners', 'partners.id', '=', 't_county_target.partner_id')
			->selectRaw("SUM(sexual_violence) AS sexual_violence, SUM(physical_emotional_violence) AS physical_emotional_violence")
			->whereRaw(Lookup::county_target_query())
			->whereRaw(Lookup::date_query(true))
			->first();

		$periods = Period::achievement()->get()->count();

		$time_percentage = Lookup::get_percentage($periods, 12, 0);
		if($time_percentage > 100) $time_percentage = 100;
		$data['chart_title'] = "Cumulative Achievement at {$time_percentage}% of time";

		$data['div'] = \Str::random(15);

		$data['outcomes']['name'] = "";
		$data['outcomes']['colorByPoint'] = true;

		$data['outcomes']['innerSize'] = '50%';

		$data['outcomes']['data'][0]['name'] = "Results";
		$data['outcomes']['data'][1]['name'] = "Gap";

		$data['outcomes']['data'][0]['color'] = "#00ff00";
		$data['outcomes']['data'][1]['color'] = "#ff0000";

		$gap = ($target_obj->gbv + $wards_target_obj->gbv + $county_target_obj->sexual_violence + $county_target_obj->physical_emotional_violence) - $row->violence;
		if($gap < 0) $gap = 0;

		$data['outcomes']['data'][0]['y'] = (int) $row->violence;
		// $data['outcomes']['data'][1]['y'] = (int) ($target_obj->gbv - $row->violence);
		$data['outcomes']['data'][1]['y'] = (int) $gap;

		/*$data['logs'] = [
			'wards_target' => $wards_target_obj->gbv,
			'facility_target' => $target_obj->gbv,
			'divisions_query' => $divisions_query,
			'date_query' => Lookup::date_query(true),
		];*/

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
			->join('view_facilities', 'view_facilities.id', '=', 't_facility_target.facility')
			->selectRaw("SUM(gbv) AS gbv")
			->when(true, $this->target_callback())
			->whereRaw(Lookup::active_partner_query())
			->get();

		$wards_target_obj = DB::table('t_ward_target')
			->join('view_wards', 'view_wards.id', '=', 't_ward_target.ward_id')
			->selectRaw("SUM(gbv) AS gbv")
			->when(true, $this->target_callback(null, true))
			->get();

		$county_target_obj = DB::table('t_county_target')
			->join('countys', 'countys.id', '=', 't_county_target.county_id')
			->join('partners', 'partners.id', '=', 't_county_target.partner_id')
			->selectRaw("(SUM(sexual_violence) + SUM(physical_emotional_violence)) AS gbv")
			->when(true, $this->target_callback(null, false, true))
			->get();

		$groupby = session('filter_groupby', 1);
		$divisor = Lookup::get_target_divisor();

		if($groupby > 9){
			$t = ($target_obj->first()->gbv ?? 0) + ($wards_target_obj->first()->gbv ?? 0) + ($county_target_obj->first()->gbv ?? 0);
			$target = round(($t / $divisor), 0);
		}


		$data['div'] = \Str::random(15);
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
				$t1 = $target_obj->where('div_id', $row->div_id)->first()->gbv ?? 0;
				$t2 = $wards_target_obj->where('div_id', $row->div_id)->first()->gbv ?? 0;
				$t = $t1 + $t2;
				$data["outcomes"][1]["data"][$key] = round(($t / $divisor), 0);
			}
		}

		$total_gender_gbv = DB::table('t_facility_target')->selectRaw('SUM(total_gender_gbv) AS value')->where('financial_year', date('Y'))->first()->value / $divisor;

		array_unshift($data['categories'], 'Baseline');
		array_unshift($data["outcomes"][0]["data"], (int) $total_gender_gbv);
		array_unshift($data["outcomes"][1]["data"], 0);
		$data["outcomes"][1]["data"][0] = $data["outcomes"][1]["data"][1];
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
			->join('view_facilities', 'view_facilities.id', '=', 't_facility_target.facility')
			->selectRaw("SUM(gbv) AS gbv")
			->when(true, $this->target_callback(1))
			->whereRaw(Lookup::active_partner_query())
			->get();

		$groupby = 1;
		$divisor = Lookup::get_target_divisor(1);

		$periods = Period::achievement()->get()->count();

		$time_percentage = Lookup::get_percentage($periods, 12, 0);
		if($time_percentage > 100) $time_percentage = 100;
		$data['chart_title'] = "Performance at {$time_percentage}% of time";

		$data['div'] = \Str::random(15);
		$data['suffix'] = '%';
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
				$ta = round(($t / $divisor), 0);
			}

			$percentage = Lookup::get_percentage($row->violence, $ta, 0);
			if($percentage > 100) $percentage = 100;

			$data["outcomes"][1]["data"][$key] = $percentage;
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

		$data['div'] = \Str::random(15);
		$data['div_class'] = 'col-md-6';
		$data['suffix'] = '';
		$data['yAxis'] = 'Gender Based Violence Cases';
		$data['stacking'] = true;
		$data['data_labels'] = true;

		Lookup::bars($data, ['Sexual Violence', 'Physical/Emotional Violence'], 'spline');
		// Lookup::splines($data, 2);

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);
			$data["outcomes"][0]["data"][$key] = (int) $row->sexual;
			$data["outcomes"][1]["data"][$key] = (int) $row->physical;
		}

		$divisor = Lookup::get_target_divisor();
		
		$physical_baseline = DB::table('t_facility_target')->selectRaw('SUM(physical_emotional_violence) AS value')->where('financial_year', date('Y'))->first()->value / $divisor;
		$sexual_baseline = DB::table('t_facility_target')->selectRaw('SUM(sexual_violence_post_rape_care) AS value')->where('financial_year', date('Y'))->first()->value / $divisor;
		
		$physical_baseline += DB::table('t_ward_target')->selectRaw('SUM(physical_emotional_violence) AS value')->where('financial_year', date('Y'))->first()->value / $divisor;
		$sexual_baseline += DB::table('t_ward_target')->selectRaw('SUM(sexual_violence_post_rape_care) AS value')->where('financial_year', date('Y'))->first()->value / $divisor;

		if(isset($data['categories']) && $data['categories']){

			array_unshift($data['categories'], 'Baseline');
			array_unshift($data["outcomes"][0]["data"], (int) $sexual_baseline);
			array_unshift($data["outcomes"][1]["data"], (int) $physical_baseline);

		}

		$view_data = view('charts.line_graph', $data)->render() . ' ';

		Lookup::bars($data, ['Sexual Violence', 'Physical/Emotional Violence'], 'column');
		$data['div'] = \Str::random(15);	
		unset($data['stacking']);
		$data['suffix'] = '%';
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

		$data['div'] = \Str::random(15);
		$data['data_labels'] = true;
		$data['suffix'] = '%';
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
			$data["outcomes"][2]["data"][$key] = Lookup::get_percentage($row->pep, $row->sexual, 0);
		}

		$divisor = Lookup::get_target_divisor();
		
		$pep_baseline = (int) (DB::table('t_facility_target')->selectRaw('SUM(pep) AS value')->where('financial_year', date('Y'))->first()->value / $divisor);
		$sexual_baseline = (int) (DB::table('t_facility_target')->selectRaw('SUM(sexual_violence_post_rape_care) AS value')->where('financial_year', date('Y'))->first()->value / $divisor);
		
		$pep_baseline += (int) (DB::table('t_ward_target')->selectRaw('SUM(pep) AS value')->where('financial_year', date('Y'))->first()->value / $divisor);
		$sexual_baseline += (int) (DB::table('t_ward_target')->selectRaw('SUM(sexual_violence_post_rape_care) AS value')->where('financial_year', date('Y'))->first()->value / $divisor);

		array_unshift($data['categories'], 'Baseline');
		array_unshift($data["outcomes"][0]["data"], (int) $pep_baseline);
		array_unshift($data["outcomes"][1]["data"], (int) ($sexual_baseline - $pep_baseline));
		array_unshift($data["outcomes"][2]["data"], Lookup::get_percentage($pep_baseline, $sexual_baseline, 0));

		return view('charts.dual_axis', $data);
	}


	// 4
	public function age_gender()
	{
		$groupby = session('filter_groupby', 1);
		$data['div'] = \Str::random(15);
		$data['yAxis'] = "Gender Based Violence By Age";
		$data['suffix'] = '%';
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
