<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;
use App\HfrSubmission;
use App\Period;
use App\Week;
use Dotenv\Regex\Result;

class HfrController extends Controller
{
	private $my_table = 'd_hfr_submission';
	private $my_target_table = 't_county_target';


    public function get_hfr_sum($columns, $name)
    {
        $sql = "(";

        foreach ($columns as $column) {
            $sql .= "SUM(`{$column['column_name']}`) + ";
        }
        $sql = substr($sql, 0, -3);
        $sql .= ") AS {$name} ";
        return $sql;
    }

	public function misassigned_facilities()
	{
		$tests = HfrSubmission::columns(true, 'hts_tst'); 
		$pos = HfrSubmission::columns(true, 'hts_tst_pos');
		$tx_new = HfrSubmission::columns(true, 'tx_new');
		$sql = $this->get_hfr_sum($tests, 'tests') . ', ' . $this->get_hfr_sum($pos, 'pos') . ', ' . $this->get_hfr_sum($tx_new, 'tx_new');

		$data['rows'] = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
            ->addSelect(DB::raw("view_facilities.id as div_id, name, new_name, DHIScode as dhis_code, facilitycode as mfl_code, facility_uid, subcounty, countyname, partnername "))
            ->where('funding_agency_id', '!=', 1)
            ->groupBy('view_facilities.id')
            ->having('tests', '>', 0)
			->get();

		$data['div'] = str_random(15);

		return view('tables.misassigned_facilities', $data);
	}


	public function testing()
	{
		$tests = HfrSubmission::columns(true, 'hts_tst'); 
		$pos = HfrSubmission::columns(true, 'hts_tst_pos');
		$sql = $this->get_hfr_sum($tests, 'tests') . ', ' . $this->get_hfr_sum($pos, 'pos');

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('tests'))
			->get();

		$data['div'] = str_random(15);
		$data['yAxis'] = "Total Number Tested";
		$data['yAxis2'] = "Yield (%)";
		$data['data_labels'] = true;
		$data['no_column_label'] = true;
		$data['suffix'] = '%';


		Lookup::bars($data, ["Positive", "Negative", "Yield"], "column", ["#ff0000", "#00ff00", "#3023ea"]);
		Lookup::splines($data, [2]);
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');
		Lookup::yAxis($data, 0, 1);

		$i=0;
		foreach ($rows as $key => $row){
			if(!$row->tests) continue;

			$data['categories'][$i] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$i] = (int) $row->pos;
			$data["outcomes"][1]["data"][$i] = (int) ($row->tests - $row->pos);
			$data["outcomes"][2]["data"][$i] = Lookup::get_percentage($row->pos, $row->tests);
			$i++;
		}	
		return view('charts.dual_axis', $data);
	}

	public function linkage()
	{
		$pos = HfrSubmission::columns(true, 'hts_tst_pos');
		$tx_new = HfrSubmission::columns(true, 'tx_new');
		$sql = $this->get_hfr_sum($pos, 'pos') . ', ' . $this->get_hfr_sum($tx_new, 'tx_new');

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('pos'))
			->get();

		$data['div'] = str_random(15);
		$data['yAxis'] = '';
		$data['yAxis2'] = "Linkage (%)";
		$data['data_labels'] = true;
		$data['no_column_label'] = true;
		$data['suffix'] = '%';

		Lookup::bars($data, ["Not Linked", "TX New", "Linkage"], "column", ["#ff0000", "#00ff00", "#3023ea"]);
		Lookup::splines($data, [2]);
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');
		Lookup::yAxis($data, 0, 1);


		$i=0;
		foreach ($rows as $key => $row){
			if(!$row->pos) continue;

			$data['categories'][$i] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$i] = (int) ($row->pos - $row->tx_new); 
			$data["outcomes"][1]["data"][$i] = (int) $row->tx_new;
			$data["outcomes"][2]["data"][$i] = Lookup::get_percentage($row->tx_new, $row->pos);
			if($data["outcomes"][0]["data"][$i] < 0) {
				$data["outcomes"][0]["data"][$i] = (int) $row->tx_new;
				$data["outcomes"][2]["data"][$i] = 0;
			}
			$i++;
		}	
		return view('charts.dual_axis', $data);
	}


	public function tx_curr_old()
	{
		$tx_curr = HfrSubmission::columns(true, 'tx_curr');
		$sql = $this->get_hfr_sum($tx_curr, 'tx_curr');

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('tx_curr'))
			->get();

		$data['div'] = str_random(15);

		Lookup::bars($data, ["TX Curr"], "column");

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->tx_curr;
		}	
		return view('charts.line_graph', $data);
	}

	public function tx_curr()
	{
		$tx_curr = HfrSubmission::columns(true, 'tx_curr');
		$sql = $this->get_hfr_sum($tx_curr, 'tx_curr');

		$data['div'] = str_random(15);

		Lookup::bars($data, ["TX Curr"], "column");

		$groupby = session('filter_groupby');

		if($groupby < 10 || $groupby == 14){

			$week_id = Lookup::get_tx_week();
			// $data['chart_title'] = Week::find($week_id)->name;

			$rows = DB::table($this->my_table)
				->when(true, $this->get_joins_callback_weeks($this->my_table))
				->selectRaw($sql)
				->when(true, $this->get_callback('tx_curr'))
				->when(($groupby < 10), function($query) use($week_id) {
					return $query->where(['week_id' => $week_id]);
				})
				->get();
		}
		else{
			$periods = [];
			// Group By month
			if($groupby == 12){
				$periods = Period::select('financial_year', 'month')
				->whereRaw(Lookup::date_query())
				->groupBy('financial_year', 'month')
				->get();
			}
			// Group By quarter
			else if($groupby == 13){
				$periods = Period::select('financial_year', 'quarter')
				->whereRaw(Lookup::date_query())
				->groupBy('financial_year', 'quarter')
				->get();				
			}
			if(!$periods) return null;

			$weeks = $week_ids = [];

			foreach ($periods as $period) {
				$w = Week::where($period->toArray())->orderBy('id', 'desc')->first();
				if($w) $week_ids[] = $w->id; $weeks[] = $w;
			}

			// dd($weeks);

			$rows = DB::table($this->my_table)
				->when(true, $this->get_joins_callback_weeks($this->my_table))
				->selectRaw($sql)
				// ->when(true, $this->get_callback('tx_curr', null, '', 14))
				->when(true, $this->get_callback('tx_curr'))
				->whereIn('week_id', $week_ids)
				->get();
		}

		$i = 0;
		foreach ($rows as $key => $row){

			/*if($groupby > 9) $data['categories'][$key] = Lookup::get_category($row, 14);
			else{
				$data['categories'][$key] = Lookup::get_category($row);
			}*/
			if(!$row->tx_curr) continue;
			$data['categories'][$i] = Lookup::get_category($row);
			$data["outcomes"][0]["data"][$i] = (int) $row->tx_curr;
			$i++;
		}	

		return view('charts.line_graph', $data);
	}

	public function net_new()
	{
		$tx_curr = HfrSubmission::columns(true, 'tx_curr');
		$sql = $this->get_hfr_sum($tx_curr, 'tx_curr');

		$data['div'] = str_random(15);

		Lookup::bars($data, ["TX Net New"], "column");

		$groupby = session('filter_groupby');
	
		if($groupby < 10 || $groupby == 14){
			

			$week_id = Lookup::get_tx_week();
			// $data['chart_title'] = Week::find($week_id)->name;

			$rows = DB::table($this->my_table)
				->when(true, $this->get_joins_callback_weeks($this->my_table))
				->selectRaw($sql)
				->when(true, $this->get_callback('tx_curr'))
				->when(($groupby < 10), function($query) use($week_id) {
					return $query->where(['week_id' => $week_id]);
				})
				->get();
		}
		else{
			$periods = [];
			// Group By month
			if($groupby == 12){
				$periods = Period::select('financial_year', 'month')
				->whereRaw(Lookup::date_query())
				->groupBy('financial_year', 'month')
				->get();
			}
			// Group By quarter
			else if($groupby == 13){
				$periods = Period::select('financial_year', 'quarter')
				->whereRaw(Lookup::date_query())
				->groupBy('financial_year', 'quarter')
				->get();				
			}
			if(!$periods) return null;

			$weeks = $week_ids = [];

			foreach ($periods as $period) {
				$w = Week::where($period->toArray())->orderBy('id', 'desc')->first();
				if($w) $week_ids[] = $w->id; $weeks[] = $w;
			}


			$rows = DB::table($this->my_table)
				->when(true, $this->get_joins_callback_weeks($this->my_table))
				->selectRaw($sql)
				// ->when(true, $this->get_callback('tx_curr', null, '', 14))
				->when(true, $this->get_callback('tx_curr'))
				->whereIn('week_id', $week_ids)
				->get();
		}
		$i = 0;
		foreach ($rows as $key => $row){			
			if(!$row->tx_curr) continue;
			$data['categories'][$i] = Lookup::get_category($row);
			$data["outcomes"][0]["data"][$i] = (int) ($row->tx_curr - 100000);
			$i++;
		}	

		return view('charts.line_graph', $data);
	}

	public function tx_crude()
	{
		$tx_curr = HfrSubmission::columns(true, 'tx_curr');
		$tx_new = HfrSubmission::columns(true, 'tx_new'); 
		$sql = $this->get_hfr_sum($tx_curr, 'tx_curr'). ', ' . $this->get_hfr_sum($tx_new, 'tx_new');;

		$data['div'] = str_random(15);
		$data['yAxis'] = 'Percentage';

		// Lookup::bars($data, [" Crude Retention "], "column");
		$data['outcomes'][0]['name'] = "Targeted Crude Retention";
		$data['outcomes'][1]['name'] = " Crude Retention";

		$groupby = session('filter_groupby');
	
		if($groupby < 10 || $groupby == 14){
			

			$week_id = Lookup::get_tx_week();
			// $data['chart_title'] = Week::find($week_id)->name;

			$rows = DB::table($this->my_table)
				->when(true, $this->get_joins_callback_weeks($this->my_table))
				->selectRaw($sql)
				->when(true, $this->get_callback('tx_curr'))
				->when(($groupby < 10), function($query) use($week_id) {
					return $query->where(['week_id' => $week_id]);
				})
				->get();
		}
		else{
			$periods = [];
			// Group By month
			if($groupby == 12){
				$periods = Period::select('financial_year', 'month')
				->whereRaw(Lookup::date_query())
				->groupBy('financial_year', 'month')
				->get();
			}
			// Group By quarter
			else if($groupby == 13){
				$periods = Period::select('financial_year', 'quarter')
				->whereRaw(Lookup::date_query())
				->groupBy('financial_year', 'quarter')
				->get();				
			}
			if(!$periods) return null;

			$weeks = $week_ids = [];

			foreach ($periods as $period) {
				$w = Week::where($period->toArray())->orderBy('id', 'desc')->first();
				if($w) $week_ids[] = $w->id; $weeks[] = $w;
			}


			$rows = DB::table($this->my_table)
				->when(true, $this->get_joins_callback_weeks($this->my_table))
				->selectRaw($sql)
				// ->when(true, $this->get_callback('tx_curr', null, '', 14))
				->when(true, $this->get_callback('tx_curr'))
				->whereIn('week_id', $week_ids)
				->get();
		}
		$i = 0;
		foreach ($rows as $key => $row){			
			if(!$row->tx_curr) continue;
			$data['categories'][$i] = Lookup::get_category($row);
			$results = ($row->tx_curr);
			$results_2 = (($row->tx_new + 400000));
			// dd($results_2);
			$target = 90;
			$data["outcomes"][0]["data"][$key] =  $target;
			$data["outcomes"][1]["data"][$i] = Lookup::get_percentage($results,$results_2);
			$i++;
		}	

		return view('charts.line_graph', $data);
	}


	public function prep_new()
	{
		$prep_new = HfrSubmission::columns(true, 'prep_new');
		$sql = $this->get_hfr_sum($prep_new, 'prep_new');
		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('prep_new'))
			->get();

		$data['div'] = str_random(15);

		Lookup::bars($data, ["PrEP New"], "column");

		$i=0;
		foreach ($rows as $key => $row){
			if(!$row->prep_new) continue;
			$data['categories'][$i] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$i] = (int) $row->prep_new;
			$i++;
		}	
		return view('charts.line_graph', $data);
	}
    //TODO Make this function take a groupby parameter from ui
    public function prep_new_last_rpt_period()
    {
        $prep_new = HfrSubmission::columns(true, 'prep_new');
        $sql = $this->get_hfr_sum($prep_new, 'prep_new');
        $rows = DB::table($this->my_table)
            ->when(true, $this->get_joins_callback_weeks($this->my_table))
            ->selectRaw($sql)
            ->when(true, $this->get_groupby_callback('prep_new',null,'',1))
            ->get();

        $data['div'] = str_random(15);
        $data['rows'] = $rows;

        return view('tables.prep_new_last_rpt_period', $data);
    }

    public function vmmc_circ()
	{
		$vmmc_circ = HfrSubmission::columns(true, 'vmmc_circ');
		$sql = $this->get_hfr_sum($vmmc_circ, 'vmmc_circ');

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('vmmc_circ'))
			->get();

		$data['div'] = str_random(15);

		Lookup::bars($data, ["VMMC Circ"], "column");

		$i=0;
		foreach ($rows as $key => $row){
			if(!$row->vmmc_circ) continue;
			$data['categories'][$i] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$i] = (int) $row->vmmc_circ;
			$i++;
		}	
		return view('charts.line_graph', $data);
	}

	public function tx_mmd()
	{
		$less_3m = HfrSubmission::columns(true, 'less_3m');
		$less_5m = HfrSubmission::columns(true, '3_5m');
		$above_6m = HfrSubmission::columns(true, 'above_6m');
		$sql = $this->get_hfr_sum($less_3m, 'less_3m') . ', ' . $this->get_hfr_sum($less_5m, 'less_5m') . ', ' . $this->get_hfr_sum($above_6m, 'above_6m');

    	$divisions_query = Lookup::divisions_query();
        $date_query = Lookup::date_query();

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('less_5m'))
			->get();

		$data['div'] = str_random(15);
		$data['data_labels'] = true;
		$data['suffix'] = '%';
		$data['stacking'] = true;
		// $data['point_percentage'] = true;
		$data['extra_tooltip'] = true;

		Lookup::bars($data, ['TX Curr 6+ months', 'TX Curr 3-5 months', 'TX Curr &lt;3 months', ], "column");
		// Lookup::splines($data, [1], 1);
		// $data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' %');
		// Lookup::yAxis($data, 0, 0);

		$i=0;
		foreach ($rows as $key => $row){
			$total = $row->less_3m + $row->less_5m + $row->above_6m;
			if(!$total) continue;

			$data['categories'][$i] = Lookup::get_category($row);

			/*$data["outcomes"][0]["data"][$key] = (int) $row->less_3m;
			$data["outcomes"][1]["data"][$key] = (int) $row->less_5m;
			$data["outcomes"][2]["data"][$key] = (int) $row->above_6m;*/

			$data["outcomes"][0]["data"][$i]['y'] = Lookup::get_percentage($row->above_6m, $total);
			$data["outcomes"][1]["data"][$i]['y'] = Lookup::get_percentage($row->less_5m, $total);
			$data["outcomes"][2]["data"][$i]['y'] = Lookup::get_percentage($row->less_3m, $total);

			$data["outcomes"][0]["data"][$i]['z'] = 'Patients - ' . number_format($row->above_6m);
			$data["outcomes"][1]["data"][$i]['z'] = 'Patients - ' . number_format($row->less_5m);
			$data["outcomes"][2]["data"][$i]['z'] = 'Patients - ' . number_format($row->less_3m);
			$i++;
		}
		return view('charts.line_graph', $data);
	}

	/*public function tx_mmd_old()
	{
		$less_3m = HfrSubmission::columns(true, 'less_3m');
		$less_5m = HfrSubmission::columns(true, '3_5m');
		$above_6m = HfrSubmission::columns(true, 'above_6m');
		$sql = $this->get_hfr_sum($less_3m, 'less_3m') . ', ' . $this->get_hfr_sum($less_5m, 'less_5m') . ', ' . $this->get_hfr_sum($above_6m, 'above_6m');

    	$divisions_query = Lookup::divisions_query();
        $date_query = Lookup::date_query();

		$row = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->whereRaw($divisions_query)
            ->whereRaw($date_query)
			->first();

		$data['div'] = str_random(15);
		$data['yAxis'] = '';
		$data['data_labels'] = true;
		$data['no_column_label'] = true;
		$data['suffix'] = '%';

		Lookup::bars($data, ["TX MMD", '% of TX_CURR'], "column");
		Lookup::splines($data, [1], 1);
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' %');
		Lookup::yAxis($data, 0, 0);


		$data['categories'][0] = 'TX Curr &lt;3 months of ARVs dispensed';
		$data['categories'][1] = 'TX Curr 3-5 months of ARVs dispensed';
		$data['categories'][2] = 'TX Curr 6+ months of ARVs dispensed';

		$data["outcomes"][0]["data"][0] = (int) $row->less_3m;
		$data["outcomes"][0]["data"][1] = (int) $row->less_5m;
		$data["outcomes"][0]["data"][2] = (int) $row->above_6m;

		$total = $row->less_3m + $row->less_5m + $row->above_6m;

		$data["outcomes"][1]["data"][0] = Lookup::get_percentage($row->less_3m, $total);
		$data["outcomes"][1]["data"][1] = Lookup::get_percentage($row->less_5m, $total);
		$data["outcomes"][1]["data"][2] = Lookup::get_percentage($row->above_6m, $total);
		
		return view('charts.dual_axis', $data);
	}*/

	public function tx_mmd_two()
	{
		$less_3m = HfrSubmission::columns(true, 'less_3m');
		$less_5m = HfrSubmission::columns(true, '3_5m');
		$above_6m = HfrSubmission::columns(true, 'above_6m');
		$sql = $this->get_hfr_sum($less_3m, 'less_3m') . ', ' . $this->get_hfr_sum($less_5m, 'less_5m') . ', ' . $this->get_hfr_sum($above_6m, 'above_6m');

    	$divisions_query = Lookup::divisions_query();
        $date_query = Lookup::date_query();

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('less_3m'))
			// ->whereRaw($divisions_query)
            // ->whereRaw($date_query)
            ->limit(12)
			->get();

		$data['div'] = str_random(15);
		$data['yAxis'] = '';
		$data['data_labels'] = true;
		$data['no_column_label'] = true;
		$data['suffix'] = '%';

		// Lookup::bars($data, ["TX MMD", '% of TX_CURR'], "column");
		// Lookup::splines($data, [1], 1);
		// $data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' %');
		// Lookup::yAxis($data, 0, 0);


		$data['categories'][0] = 'TX Curr &lt;3 months of ARVs dispensed';
		$data['categories'][1] = 'TX Curr 3-5 months of ARVs dispensed';
		$data['categories'][2] = 'TX Curr 6+ months of ARVs dispensed';

		/*$data["outcomes"][0]["data"][0] = (int) $row->less_3m;
		$data["outcomes"][0]["data"][1] = (int) $row->less_5m;
		$data["outcomes"][0]["data"][2] = (int) $row->above_6m;

		$total = $row->less_3m + $row->less_5m + $row->above_6m;

		$data["outcomes"][1]["data"][0] = Lookup::get_percentage($row->less_3m, $total);
		$data["outcomes"][1]["data"][1] = Lookup::get_percentage($row->less_5m, $total);
		$data["outcomes"][1]["data"][2] = Lookup::get_percentage($row->above_6m, $total);*/

		$i = $total = $less_3m = $less_5m = $above_6m = 0;
		$stacks = [];

		foreach ($rows as $key => $row) {
			$stacks[] = Lookup::get_category($row);

			$data["outcomes"][$key]["data"][0] = (int) $row->less_3m;
			$data["outcomes"][$key]["data"][1] = (int) $row->less_5m;
			$data["outcomes"][$key]["data"][2] = (int) $row->above_6m;

			$less_3m += $row->less_3m;
			$less_5m += $row->less_5m;
			$above_6m += $row->above_6m;
			$i++;
		}
		$total = $less_3m + $less_5m + $above_6m;

		$stacks[] = '% of TX_CURR';
		$data["outcomes"][$i]["data"][0] = Lookup::get_percentage($less_3m, $total);
		$data["outcomes"][$i]["data"][1] = Lookup::get_percentage($less_5m, $total);
		$data["outcomes"][$i]["data"][2] = Lookup::get_percentage($above_6m, $total);


		Lookup::bars($data, $stacks, "column");
		Lookup::splines($data, [$i], 1);
		$data['outcomes'][$i]['tooltip'] = array("valueSuffix" => ' %');
		Lookup::yAxis($data, 0, $i-1);


		
		return view('charts.dual_axis', $data);
	}

	/*
		Targets
	*/
	public function target_donut($modality = 'hts_tst')
	{
		$tests = HfrSubmission::columns(true, $modality); 
		$sql = $this->get_hfr_sum($tests, 'val');

		$date_query = Lookup::date_query();
		$week_id = Lookup::get_tx_week();

		$results = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when($modality != 'tx_curr', function($query) use($date_query){
				return $query->whereRaw($date_query);
			})
			->when(($modality == 'tx_curr'), function($query) use($week_id) {
				return $query->where(['week_id' => $week_id]);
			})
			->whereRaw(Lookup::divisions_query())
			->first();

		$target = DB::table($this->my_target_table)
			->join('countys', 'countys.id', '=', $this->my_target_table . '.county_id')
			->join('partners', 'partners.id', '=', $this->my_target_table . '.partner_id')
			->selectRaw($sql)
			->whereRaw(Lookup::county_target_query())
			->first();

		$data = Lookup::target_donut();

		$divisor = Lookup::get_target_divisor(1);

		$results = (int) $results->val;
		$target = (int) ($target->val / $divisor);
		$gap = $target - $results;
		if($gap < 0) $gap = 0;

		$data['outcomes']['data'][0]['y'] = $results;
		$data['outcomes']['data'][1]['y'] = $gap;

		return view('charts.pie_chart', $data);
	}

}
