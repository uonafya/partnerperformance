<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Lookup;
use App\Facility;

use App\Week;
use App\SurgeAge;
use App\SurgeGender;
use App\SurgeModality;
use App\SurgeColumn;
use App\SurgeColumnView;
// use App\Surge;

class SurgeController extends Controller
{
	private $my_table = 'd_surge';

	public function testing()
	{
		$tested_columns = SurgeColumnView::where('column_name', 'like', '%tested%')
			->where('hts', 1)
			->when(true, $this->surge_columns_callback())
			->get();

		$positive_columns = SurgeColumnView::where('column_name', 'like', '%positive%')
			->where('hts', 1)
			->when(true, $this->surge_columns_callback())
			->get();

		$sql = $this->get_sum($tested_columns, 'tests') . ', ' . $this->get_sum($positive_columns, 'pos') . ', SUM(testing_target) AS testing_target, SUM(pos_target) AS pos_target ';

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('tests'))
			->where('is_surge', 1)
			->get();

		// dd($rows);

		$groupby = session('filter_groupby', 1);
		$data['div'] = str_random(15);
		$data['extra_tooltip'] = true;

		$data['outcomes'][0]['name'] = "Positive Tests";
		$data['outcomes'][1]['name'] = "Negative Tests";
		// $data['outcomes'][2]['name'] = "Targeted Tests";
		$data['outcomes'][2]['name'] = "Yield";
		// $data['outcomes'][4]['name'] = "Targeted Yield";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "spline";
		// $data['outcomes'][3]['type'] = "spline";
		// $data['outcomes'][4]['type'] = "spline";

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		// $data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');
		// $data['outcomes'][4]['tooltip'] = array("valueSuffix" => ' %');

		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;
		// $data['outcomes'][2]['yAxis'] = 1;

		Lookup::splines($data, [2]);

		if($groupby < 10){
			$var = Lookup::groupby_query();
			$raw = DB::raw($var['select_query'] . ', COUNT(id) AS facility_count');
			$facilities = DB::table('view_facilitys')->select($raw)->groupBy($var['group_query'])->where('is_surge', 1)->get();

			// $data['dd'] = $facilities->toJson();
		}

		$i = 0;
		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);
			if($row->tests < $row->pos) $row->tests = $row->pos;
			$data["outcomes"][0]["data"][$key]['y'] = (int) $row->pos;	
			$data["outcomes"][1]["data"][$key]['y'] = (int) ($row->tests - $row->pos);	
			// $data["outcomes"][2]["data"][$key]['y'] = (int) $row->testing_target;
			$data["outcomes"][2]["data"][$key]['y'] = Lookup::get_percentage($row->pos, $row->tests);
			// $data["outcomes"][4]["data"][$key]['y'] = Lookup::get_percentage($row->pos_target, $row->testing_target);

			$data["outcomes"][0]["data"][$key]['z'] = $data["outcomes"][1]["data"][$key]['z'] = $data["outcomes"][2]["data"][$key]['z'] = '';
			if($groupby < 10) $data["outcomes"][2]["data"][$key]['z'] = '  Facility Count ' . Lookup::get_val($row, $facilities, 'facility_count');
		}
		// $data['dd'] = json_encode($data);
		return view('charts.dual_axis', $data);
	}

	public function linkage()
	{
		$positive_columns = SurgeColumnView::where('column_name', 'like', '%positive%')
			->where('hts', 1)
			->when(true, $this->surge_columns_callback(false, true, true))
			->get();

		$tx_new = SurgeColumnView::where('modality', 'tx_new')
			->when(true, $this->surge_columns_callback(false, true, true))
			->get();

		$sql = $this->get_sum($positive_columns, 'pos') . ', ' .  $this->get_sum($tx_new, 'tx_new') . ', SUM(testing_target) AS testing_target, SUM(pos_target) AS pos_target ';

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('pos'))
			->where('is_surge', 1)
			->get();

		$groupby = session('filter_groupby', 1);
		$data['div'] = str_random(15);
		$data['yAxis'] = "New On Treatment";
		$data['yAxis2'] = "Linkage to Treatment (%)";

		$data['outcomes'][0]['name'] = "Positives Not Linked To Treatment";
		$data['outcomes'][1]['name'] = "New on Treatment";
		$data['outcomes'][2]['name'] = "Linkage to Treatment";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "spline";

		$data['outcomes'][0]['color'] = "#ff0000";
		$data['outcomes'][1]['color'] = "#00cc00";
		$data['outcomes'][2]['color'] = "#cc0099";

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');

		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;

		Lookup::splines($data, [2]);

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);
			// if($row->tests < $row->pos) $row->tests = $row->pos;
			$data["outcomes"][0]["data"][$key] = (int) ($row->pos - $row->tx_new);
			if($data["outcomes"][0]["data"][$key] < 0) $data["outcomes"][0]["data"][$key] = 0;
			$data["outcomes"][1]["data"][$key] = (int) $row->tx_new;
			$data["outcomes"][2]["data"][$key] = Lookup::get_percentage(($row->tx_new), $row->pos);
		}
		return view('charts.dual_axis', $data);
	}


	// Yield by modality
	public function modality_yield()
	{
		$sql = '';

		$groupby = session('filter_groupby', 1);
		$data['div'] = str_random(15);
		// $data['yAxis'] = "Yield by Modality (%)";
		$data['yAxis'] = "HTS Pos";
		$data['suffix'] = '';
		$data['stacking'] = true;
		// $data['extra_tooltip'] = true;
		$data['point_percentage'] = true;

		$data2['div'] = str_random(15);
		$data2['yAxis'] = "Yield by Modality (%)";
		$data2['suffix'] = '%';


		$modalities = SurgeModality::where('hts', 1)
			->when(session('filter_gender'), function($query){
				if(session('filter_gender') == 1) return $query->where('male', 1);
				if(session('filter_gender') == 2) return $query->where('female', 1);
				if(session('filter_gender') == 3) return $query->where('unknown', 1);
			})
			->get();

		foreach ($modalities as $key => $modality) {
			$tested_columns = SurgeColumnView::where('modality_id', $modality->id)
				->where('column_name', 'like', '%tested%')
				->when(true, $this->surge_columns_callback(false))
				->get();

			$positive_columns = SurgeColumnView::where('modality_id', $modality->id)
				->where('column_name', 'like', '%positive%')
				->when(true, $this->surge_columns_callback(false))
				->get();

			$sql .= $this->get_sum($tested_columns, $modality->modality . '_tested') . ', ' . $this->get_sum($positive_columns, $modality->modality . '_pos') . ', ';
			// $sql .= $this->get_sum($positive_columns, $modality->modality . '_pos') . ', ';

			$data['outcomes'][$key]['name'] = $modality->modality_name;
			$data['outcomes'][$key]['type'] = "column";

			$data2['outcomes'][$key]['name'] = $modality->modality_name;
		}

		$sql = substr($sql, 0, -2);

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback())
			->where('is_surge', 1)
			->get();


		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);
			$data2['categories'][$key] = Lookup::get_category($row);

			foreach ($modalities as $mod_key => $modality) {
				$t = $modality->modality . '_tested';
				$p = $modality->modality . '_pos';
				$data2["outcomes"][$mod_key]["data"][$key]['y'] = Lookup::get_percentage($row->$p, $row->$t);
				$data["outcomes"][$mod_key]["data"][$key]['y'] = (int) $row->$p;
				// $data["outcomes"][$mod_key]["data"][$key]['z'] = ' of ' . number_format($row->$t) . ' Tests';
			}
		}
		$view_data = view('charts.line_graph', $data)->render() . '<br /><br /><br /> ' . view('charts.line_graph', $data2)->render();
		return $view_data;

		// return view('charts.line_graph', $data);
	}



	// Yield by age
	/*public function age_yield()
	{
		$sql = '';

		$groupby = session('filter_groupby', 1);
		$data['div'] = str_random(15);
		$data['yAxis'] = "Yield by Age (%)";
		$data['suffix'] = '%';

		$ages = SurgeAge::when(session('filter_gender'), function($query){
						if(session('filter_gender') == 3) return $query->where('no_gender', 1);
					})->get();

		foreach ($ages as $key => $age) {
			$tested_columns = SurgeColumnView::where('age_id', $age->id)
				->where('column_name', 'like', '%tested%')
				->when(true, $this->surge_columns_callback(true, true, false))
				->get();

			$positive_columns = SurgeColumnView::where('age_id', $age->id)
				->where('column_name', 'like', '%positive%')
				->when(true, $this->surge_columns_callback(true, true, false))
				->get();

			$sql .= $this->get_sum($tested_columns, $age->age . '_tested') . ', ' . $this->get_sum($positive_columns, $age->age . '_pos') . ', ';

			$data['outcomes'][$key]['name'] = $age->age_name;
			$data['outcomes'][$key]['type'] = "column";
		}

		$sql = substr($sql, 0, -2);

		$rows = DB::table('d_surge')
			->join('weeks', 'weeks.id', '=', 'd_surge.week_id')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_surge.facility')
			->selectRaw($sql)
			->when(true, $this->get_callback())
			->get();


		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			foreach ($ages as $age_key => $age) {
				$t = $age->age . '_tested';
				$p = $age->age . '_pos';
				$data["outcomes"][$age_key]["data"][$key] = Lookup::get_percentage($row->$p, $row->$t);
			}
		}
		return view('charts.line_graph', $data);
	}*/


	// Yield by age
	public function age_yield()
	{
		$sql = '';

		$groupby = session('filter_groupby', 1);
		$data['div'] = str_random(15);
		$data['yAxis'] = "HTS Pos";
		$data['suffix'] = '';
		$data['stacking'] = true;
		// $data['extra_tooltip'] = true;
		$data['point_percentage'] = true;

		$ages = SurgeAge::when(session('filter_gender'), function($query){
						if(session('filter_gender') == 3) return $query->where('no_gender', 1);
					})->surge()->get();

		foreach ($ages as $key => $age) {
			// $tested_columns = SurgeColumnView::where('age_id', $age->id)
			// 	->where('column_name', 'like', '%tested%')
			// 	->when(true, $this->surge_columns_callback(true, true, false))
			// 	->get();

			$positive_columns = SurgeColumnView::where('age_id', $age->id)
				->where('column_name', 'like', '%positive%')
				->when(true, $this->surge_columns_callback(true, true, false))
				->get();

			// $sql .= $this->get_sum($tested_columns, $age->age . '_tested') . ', ' . $this->get_sum($positive_columns, $age->age . '_pos') . ', ';
			$sql .= $this->get_sum($positive_columns, $age->age . '_pos') . ', ';

			$data['outcomes'][$key]['name'] = $age->age_name;
			$data['outcomes'][$key]['type'] = "column";
		}

		$sql = substr($sql, 0, -2);

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback())
			->where('is_surge', 1)
			->get();


		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			foreach ($ages as $age_key => $age) {
				$t = $age->age . '_tested';
				$p = $age->age . '_pos';
				$data["outcomes"][$age_key]["data"][$key]['y'] = (int) $row->$p;
				// $data["outcomes"][$age_key]["data"][$key]['z'] = ', yield of ' .  Lookup::get_percentage($row->$p, $row->$t) . '%';
			}
		}
		return view('charts.line_graph', $data);
	}


	// Yield by gender
	public function gender_yield()
	{
		$sql = '';

		$groupby = session('filter_groupby', 1);
		$data['div'] = str_random(15);
		$data['yAxis'] = "Yield by Gender (%)";
		$data['suffix'] = '%';

		$genders = SurgeGender::where('id', '!=', 3)->get();

		foreach ($genders as $key => $gender) {
			$tested_columns = SurgeColumnView::where('gender_id', $gender->id)
				->where('column_name', 'like', '%tested%')
				->when(true, $this->surge_columns_callback(true, false, true))
				->get();

			$positive_columns = SurgeColumnView::where('gender_id', $gender->id)
				->where('column_name', 'like', '%positive%')
				->when(true, $this->surge_columns_callback(true, false, true))
				->get();

			if(!$tested_columns->isEmpty()){
				$sql .= $this->get_sum($tested_columns, $gender->gender . '_tested') . ', ' . $this->get_sum($positive_columns, $gender->gender . '_pos') . ', ';				
			}


			$data['outcomes'][$key]['name'] = $gender->gender;
			$data['outcomes'][$key]['type'] = "column";
		}

		$sql = substr($sql, 0, -2);

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback())
			->where('is_surge', 1)
			->get();


		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			foreach ($genders as $gender_key => $gender) {
				$t = $gender->gender . '_tested';
				$p = $gender->gender . '_pos';
				if(isset($row->$p)) $data["outcomes"][$gender_key]["data"][$key] = Lookup::get_percentage($row->$p, $row->$t);
				else{
					$data["outcomes"][$gender_key]["data"][$key] = 0;
				}
			}
		}
		return view('charts.line_graph', $data);
	}

	// PNS for surge
	public function pns()
	{
		$sql = '';

		$groupby = session('filter_groupby', 1);
		$data['div'] = str_random(15);
		$data['yAxis'] = "PNS Totals";
		$data['suffix'] = '';
		// $data['stacking_false'] = false;

		$pns_array = ['clients_screened', 'contacts_identified', 'pos_contacts', 'eligible_contacts', 'contacts_tested', 'new_pos', 'linked_to_haart'];

		$pns_modalities = SurgeModality::whereIn('modality', $pns_array)->orderBy('id', 'asc')->get();

		foreach ($pns_modalities as $key => $pns) {
			$sql .= $this->get_pns_sum($pns->modality) . ', ';
			$data['outcomes'][$key]['name'] = $pns->modality_name;
			$data['outcomes'][$key]['type'] = "column";
		}

		$sql = substr($sql, 0, -2);

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback())
			->where('is_surge', 1)
			->get();

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			foreach ($pns_array as $pns_key => $pns) {
				$data["outcomes"][$pns_key]["data"][$key] = (int) $row->$pns;
			}
		}
		return view('charts.line_graph', $data);
	}

	// TX SV for surge
	public function tx_sv()
	{
		$sql = '';

		$data['div'] = str_random(15);
		$data['yAxis'] = "TX New Patients";
		$data['yAxis2'] = "Retention";

		$tx_sv_array = ['tx_sv_d', 'tx_sv_n'];

		$tx_sv_modalities = SurgeModality::whereIn('modality', $tx_sv_array)->orderBy('id', 'asc')->get();

		foreach ($tx_sv_modalities as $key => $tx_sv) {
			$sql .= $this->get_pns_sum($tx_sv->modality) . ', ';
			$data['outcomes'][$key]['type'] = "column";
		}
		$data['outcomes'][2]['type'] = "spline";

		$data['outcomes'][0]['name'] = "TX New Second Visit Due but didn't show up";
		$data['outcomes'][1]['name'] = "TX New Second Visit Number";
		$data['outcomes'][2]['name'] = "Retention";

		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');

		Lookup::splines($data, [2]);

		$sql = substr($sql, 0, -2);

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback())
			->where('is_surge', 1)
			->get();

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) ($row->tx_sv_d - $row->tx_sv_n);
			$data["outcomes"][1]["data"][$key] = (int) $row->tx_sv_n;
			if($data["outcomes"][0]["data"][$key] < 0) $data["outcomes"][0]["data"][$key] = 0;
			$data["outcomes"][2]["data"][$key] = Lookup::get_percentage($row->tx_sv_n, ($data["outcomes"][0]["data"][$key] + $data["outcomes"][1]["data"][$key]));
		}
		return view('charts.dual_axis', $data);
	}

	// TX BTC for surge
	public function tx_btc()
	{
		$sql = '';
		$sql2 = '';

		$groupby = session('filter_groupby', 1);
		if($groupby > 9 && $groupby != 14) return '';

		$data['div'] = str_random(15);
		$data['yAxis'] = "TX BTC";
		$data['yAxis2'] = "Target Achievement";

		// $tx_sv_array = ['tx_btc_t', 'tx_btc_n'];
		$tx_sv_array = ['tx_btc_n'];
		$tx_sv_array2 = ['tx_btc_t'];

		$week_id = Lookup::get_tx_week();
		if(!$week_id) abort(400);

		$tx_sv_modalities = SurgeModality::whereIn('modality', $tx_sv_array)->orderBy('id', 'asc')->get();
		$tx_sv_modalities2 = SurgeModality::whereIn('modality', $tx_sv_array2)->orderBy('id', 'asc')->get();

		foreach ($tx_sv_modalities as $key => $tx_sv) {
			$sql .= $this->get_pns_sum($tx_sv->modality) . ', ';
		}

		foreach ($tx_sv_modalities2 as $key => $tx_sv) {
			$sql2 .= $this->get_pns_sum($tx_sv->modality) . ', ';
		}

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "spline";

		$data['outcomes'][0]['name'] = "LTFU Restored to Treatment Unmet Target";
		$data['outcomes'][1]['name'] = "LTFU Restored to Treatment Number";
		$data['outcomes'][2]['name'] = "Target Achievement";

		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');

		Lookup::splines($data, [2]);

		$sql = substr($sql, 0, -2);
		$sql2 = substr($sql, 0, -2);

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback())
			->where('is_surge', 1)
			->get();

		$rows2 = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql2)
			->when(true, $this->get_callback())
			->where('is_surge', 1)
			->where('week_id', $week_id)
			->get();

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);
			$target = (int) Lookup::get_val($row, $rows2, 'tx_btc_t');

			$data["outcomes"][0]["data"][$key] = (int) ($target- $row->tx_btc_n);
			$data["outcomes"][1]["data"][$key] = (int) $row->tx_btc_n;
			if($data["outcomes"][0]["data"][$key] < 0) $data["outcomes"][0]["data"][$key] = 0;
			$data["outcomes"][2]["data"][$key] = Lookup::get_percentage($row->tx_btc_n, ($data["outcomes"][0]["data"][$key] + $data["outcomes"][1]["data"][$key]));
		}
		return view('charts.dual_axis', $data);
	}


	public function targets()
	{		
		$positive_columns = SurgeColumnView::where('column_name', 'like', '%positive%')
			->where('hts', 1)
			->get();

		$tx_new = SurgeColumnView::where('modality', 'tx_new')->get();

		$sql = $this->get_sum($positive_columns, 'pos') . ', ' .  $this->get_sum($tx_new, 'tx_new') . ', SUM(pos_target) AS pos_target, SUM(tx_new_target) AS tx_new_target ';

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('pos'))
			->where('is_surge', 1)
			->get();

		$groupby = session('filter_groupby', 1);
		$data['div'] = str_random(15);
		$data['yAxis'] = "Number of Clients";

		$data['outcomes'][0]['name'] = "Positives";
		$data['outcomes'][1]['name'] = "Positives Target";
		$data['outcomes'][2]['name'] = "New on Treatment";
		$data['outcomes'][3]['name'] = "New on Treatment Target";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "spline";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "spline";

		$data['outcomes'][0]['stack'] = 'positives';
		$data['outcomes'][1]['stack'] = 'positives';
		$data['outcomes'][2]['stack'] = 'new_tx';
		$data['outcomes'][3]['stack'] = 'new_tx';

		$data['outcomes'][0]['color'] = "#ff0000";
		$data['outcomes'][1]['color'] = "#ff0000";
		$data['outcomes'][2]['color'] = "#00cc00";
		$data['outcomes'][3]['color'] = "#00cc00";

		Lookup::splines($data, [1, 3]);

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);
			$data["outcomes"][0]["data"][$key] = (int) $row->pos;
			$data["outcomes"][1]["data"][$key] = (int) $row->pos_target;
			$data["outcomes"][2]["data"][$key] = (int) $row->tx_new;
			$data["outcomes"][3]["data"][$key] = (int) $row->tx_new_target;
		}
		return view('charts.bar_graph', $data);
	}




	public function get_pns_sum($pns_name)
	{
		$pns_columns = SurgeColumn::where('column_name', 'LIKE', "{$pns_name}%")
			->when(true, $this->surge_columns_callback(false, true, true))
			->get();

		return $this->get_sum($pns_columns, $pns_name);
	}


	
	public function set_surge_facilities(Request $request)
	{
		$partner = session('session_partner');
		if(!$partner){
			$partner = auth()->user()->partner;
			session(['session_partner' => $partner]);
		}

		$facilities = $request->input('facilities');
		Facility::where('partner', $partner->id)->whereNotIn('id', $facilities)->update(['is_surge' => 0]);
		Facility::where('partner', $partner->id)->whereIn('id', $facilities)->update(['is_surge' => 1]);
		session(['toast_message' => 'The selected facilities have been set to surge facilities.']);
		return back();
	}


	public function download_excel(Request $request)
	{
		$partner = session('session_partner');
		if(!$partner){
			$partner = auth()->user()->partner;
			session(['session_partner' => $partner]);
		}
		$data = [];

		$week_id = $request->input('week');
		$modalities = $request->input('modalities');
		$gender = $request->input('gender');
		$ages = $request->input('ages');

		$columns = SurgeColumn::when(true, function($query) use ($modalities){
			if(is_array($modalities)) return $query->whereIn('modality_id', $modalities);
			return $query->where('modality_id', $modalities);
		})->when($gender, function($query) use ($gender){
			return $query->where('gender_id', $gender);
		})->when($ages, function($query) use ($ages){
			if(is_array($ages)) return $query->whereIn('age_id', $ages);
			return $query->where('age_id', $ages);
		})
		->orderBy('modality_id', 'asc')
		->orderBy('gender_id', 'asc')
		->orderBy('age_id', 'asc')
		->orderBy('id', 'asc')
		->get();

		$sql = "countyname as County, Subcounty, facilitycode AS `MFL Code`, name AS `Facility`, financial_year AS `Financial Year`, week_number as `Week Number`";

		foreach ($columns as $column) {
			$sql .= ", `{$column->column_name}` AS `{$column->alias_name}`";
		}

		$week = Week::find($week_id);
		$filename = str_replace(' ', '_', strtolower($partner->name)) . '_surge_data_for_' . $week->start_date . '_to_' . $week->end_date;

		$facilities = Facility::select('id')->where(['is_surge' => 1, 'partner' => $partner->id])->get()->pluck('id')->toArray();
		
		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->where('week_id', $week_id)
			->where('partner', $partner->id)
			->when($facilities, function($query) use ($facilities){
				return $query->whereIn('view_facilities.id', $facilities);
			})
			->orderBy('name', 'asc')
			->get();

		foreach ($rows as $row) {
			$row_array = get_object_vars($row);
			$data[] = $row_array;
		}
    	$path = storage_path('exports/' . $filename . '.xlsx');
    	if(file_exists($path)) unlink($path);

    	Excel::create($filename, function($excel) use($data){
    		$excel->sheet('sheet1', function($sheet) use($data){
    			$sheet->fromArray($data);
    		});

    	})->store('xlsx');

    	return response()->download($path);
	}



	public function upload_excel(Request $request)
	{
		ini_set('memory_limit', '-1');
		if (!$request->hasFile('upload')){
	        session(['toast_error' => 1, 'toast_message' => 'Please select a file before clicking the submit button.']);
			return back();
		}
		$file = $request->upload->path();

		$data = Excel::load($file, function($reader){
			$reader->toArray();
		})->get();

		// dd($data);

		$partner = session('session_partner');
		
		if(!$partner){
			$partner = auth()->user()->partner;
			session(['session_partner' => $partner]);
		}

		$today = date('Y-m-d');

		$surge_columns = SurgeColumn::all();

		$columns = [];
		$week = null;

		foreach ($surge_columns as $key => $value) {
			$columns[$value->excel_name] = $value->column_name;
		}

		foreach ($data as $row_key => $row){
			if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) continue;
			$fac = Facility::where('facilitycode', $row->mfl_code)->first();
			if(!$fac) continue;
			// if(!$fac) dd('Facility not found');

			if(!$week) $week = Week::where(['financial_year' => $row->financial_year, 'week_number' => $row->week_number])->first();

			if(!$week){
				session(['toast_error' => 1, 'toast_message' => 'The week that you are trying to upload data for could not be found.']);
				return back();
			}

			$update_data = ['dateupdated' => $today];

			foreach ($row as $key => $value) {
				if(isset($columns[$key])){
					$update_data[$columns[$key]] = (int) $value;
				}
			}

			// DB::enableQueryLog();

			DB::connection('mysql_wr')->table('d_surge')
				->where(['facility' => $fac->id, 'week_id' => $week->id])
				->update($update_data);

	 		// return DB::getQueryLog();
		}

		session(['toast_message' => "The surge updates have been made."]);
		return back();
	}
}
