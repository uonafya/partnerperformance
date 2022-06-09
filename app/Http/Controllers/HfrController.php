<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;
use App\HfrSubmission;
use App\Partner;
use App\Period;
use App\Week;
use Dotenv\Regex\Result;
use Illuminate\Support\Facades\Log;

class HfrController extends Controller
{
	private $my_table = 'd_hfr_submission';
	private $my_target_table = 't_county_target';
	private $my_floating = 'floating_target';


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

	public function get_hfr_sum_prev($columns, $name)
    {
        $sql = "(";

        foreach ($columns as $column) {
            $sql .= "SUM(`{$column['column_name']}`) + ";
        }
        $sql = substr($sql, 0, -3);
        $sql .= ") AS {$name} ";
		$sql .= ", month, year";
		
        return $sql;
    }

	public function misassigned_facilities()
	{
		$tests = HfrSubmission::columns(true, 'hts_tst'); 
		$pos = HfrSubmission::columns(true, 'hts_tst_pos');
		$tx_new = HfrSubmission::columns(true, 'tx_new');
		$sql = $this->get_hfr_sum($tests, 'tests') . ', ' . $this->get_hfr_sum($pos, 'pos') . ', ' . $this->get_hfr_sum($tx_new, 'tx_new');

		$data['rows'] = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
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
			->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
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
			->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
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
			->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
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

	public function tx_new()
	{
		$tx_new = HfrSubmission::columns(true, 'tx_new');
		$sql_test = $this->get_hfr_sum($tx_new, 'target');
		$sql_ftarget = '(SUM(floating_target)) AS target';
		$sql_target = '(SUM(target)) AS target';
		$sql = $this->get_hfr_sum($tx_new, 'tx_new');
		$groupby = session('filter_groupby');
		$groupbypartner = session('filter_partner');
		$groupbycounty = session('filter_county');
		$today =((int)date('m'))+2; 
		// dd($groupbypartner,$groupby);

		if($groupby == 12 ){

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('tx_new'))
			->get();
			// DB::enableQueryLog();
		$target = DB::table($this->my_floating)
			->join('countys', 'countys.id', '=', $this->my_floating . '.county_id')
			->join('partners', 'partners.id', '=', $this->my_floating . '.partner_id')
			->selectRaw($sql_ftarget)
			->when(($groupby == 12 && !isset($groupbypartner) ), function ($query){
				return $query->addSelect(DB::raw("month"));
			})
			->when(($groupby == 12 && isset($groupbypartner) ), function ($query){
				return $query->addSelect(DB::raw(" partners.name as partner_name,partner_id as div_id,month"));
			})
			->when(($groupby == 12 && isset($groupbycounty) ), function ($query){
				return $query->addSelect(DB::raw(" countys.name as county_name, countys.id as county_id,month"));
			})
			// ->addSelect(DB::raw(" partners.name as partner_name,countys.name as county_name, countys.id as county_id,partner_id as div_id,month"))
			->whereRaw(Lookup::county_target_query())
			->when(($groupby == 12  ), function ($query) {
				return $query->groupby('month');
			})
			// ->when(($groupby == 12 && isset($groupbypartner) ), function ($query) use($groupbypartner){
			// 	return $query->where('partner_id', $groupbypartner);
			// })
			->when(($groupby == 12 && isset($groupbycounty) ), function ($query){
				return $query->groupby('county_name');
			})
			->when(($groupby == 12 && isset($groupbycounty) ), function ($query){
				return $query->orderby('month','asc');
			})
			->when(($groupby == 12 && isset($groupbypartner) ), function ($query) {
				return $query->orderby('month','asc');
			})
			->when(($groupby == 12 ), function ($query){
				return $query->orderby('month','asc');
			})
			->when(($groupby == 1), function ($query){
				return $query->groupby('partner_name', 'month');
			})
			->when(($groupby == 2), function ($query){
				return $query->groupby('county_name');
			})
			->when(($groupby == 1), function ($query){
				return $query->orderby('month', 'asc');
			})
			->when(($groupby == 2), function ($query){
				return $query->orderby('county_name');
			})						
			->get();
			// return DB::getQueryLog();/
		
		} elseif($groupby < 10 || $groupby == 14) {
			$week_id = Lookup::get_tx_week(1, true);
			// DB::enableQueryLog();
			$rows = DB::table($this->my_table)
				->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
				->selectRaw($sql)
				->when(true, $this->get_callback_tx_curr('tx_new'))
				->where('partner','!=' ,55)
				// ->when(($groupby < 10), function($query) use($week_id) {
				// 	return $query->where(['week_id' => $week_id]);
				// })
				->orderby("div_id",'asc')
				->get();
				//  DB::enableQueryLog();
			$target = DB::table($this->my_floating)
				->join('countys', 'countys.id', '=', $this->my_floating . '.county_id')
				->join('partners', 'partners.id', '=', $this->my_floating . '.partner_id')
				->selectRaw($sql_target)
				->when(($groupby == 1 ), function ($query){
					return $query->addSelect(DB::raw(" partners.name as partner_name,partner_id as div_id"));
				})
				->when(($groupby == 2 ), function ($query){
					return $query->addSelect(DB::raw("countys.name as county_name, countys.id as div_id"));
				})
				
				// ->when(true, $this->get_predefined_groupby_callback('tx_curr'))
				->whereRaw(Lookup::county_target_query())
				->where($this->my_floating .'.month' ,'<=', $today)
				// ->when(($groupby == 1), $this->get_callback('partner_name'))
				// ->when(($groupby == 2), $this->get_callback('county_name'))
				->when(($groupby == 1), function ($query){
					return $query->groupby('partner_name');
				})
				->when(($groupby == 2), function ($query){
					return $query->groupby('county_name');
				})
				
				->orderby("div_id", 'asc')			
				->get();
				// return DB::getQueryLog();


		}else{
			$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('tx_new'))
			->get();
		}

		$divisor = Lookup::get_target_divisor(12);

		// $target = ((int)($target[0]->val)/$divisor);
		// $target = round($target,0);

		// dd($target,$rows);

		$data['div'] = str_random(15);

		Lookup::bars($data, ["TX New", "Target" ], "column", ["#ff7d33", "#3023ea"]);
		// if(isset($target)){
		Lookup::splines($data, [1]);
		// }

		$i=0;
		foreach ($rows as $key => $row){
			if(!$row->tx_new) continue;
			$data['categories'][$i] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$i] = (int) $row->tx_new;
			if($groupby == 12){
				$data["outcomes"][1]["data"][$i] = round(((int)($target[$i]->target)),0);;
			}else{
			if(isset($target[$i])){
			$targetfinal = round(((int)($target[$i]->target)),0);
				if(isset($targetfinal)){
					$data["outcomes"][1]["data"][$i] = $targetfinal;
				}else{
					$data["outcomes"][1]["data"][$i] = 0;
				}			
			}else{
				$data["outcomes"][1]["data"][$i] = 0;
			}
			}

			$i++;
		}	
		return view('charts.line_graph', $data);
	}

	public function tx_curr()
	{
		
		$tx_curr = HfrSubmission::columns(true, 'tx_curr');
		$sqlpartner = $this->get_hfr_sum($tx_curr, 'tx_curr');
		$sql = $this->get_hfr_sum_prev($tx_curr, 'tx_curr');
		$sql_test = $this->get_hfr_sum($tx_curr, 'val');

		$data['div'] = str_random(15); 

		Lookup::bars($data, ["Tx_Curr", "Target" ], "column", ["#ff7d33", "#3023ea"]);
		Lookup::splines($data, [1]);

		$groupby = session('filter_groupby');
		$groupbypartner = session('filter_partner');
		

		if($groupby < 10 || $groupby == 14){

			$week_id = Lookup::get_tx_week(1, true);
			$grouping = 'partners.name';
			// $data['chart_title'] = Week::find($week_id)->name;
			// DB::enableQueryLog();
			$rows = DB::table($this->my_table)
				->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
				->selectRaw($sql)
				->when(true, $this->get_callback_tx_curr('tx_curr'))
				->when(($groupby < 10), function($query) use($week_id) {
					return $query->where(['week_id' => $week_id]);
				})
				->orderby("div_id",'asc')
				->get();
				// DB::enableQueryLog();
			// 
			$target = DB::table($this->my_target_table)
				->join('countys', 'countys.id', '=', $this->my_target_table . '.county_id')
				->join('partners', 'partners.id', '=', $this->my_target_table . '.partner_id')
				->selectRaw($sql_test)
				->when(($groupby == 1), function ($query){
				return $query->addSelect(DB::raw(" partners.name as partner_name,partners.id as div_id"));
				})
				->when(($groupby == 2), function ($query){
					return $query->addSelect(DB::raw(" countys.name as county_name, countys.id as div_id"));
				})
				
				// ->when(true, $this->get_predefined_groupby_callback('tx_curr'))
				->whereRaw(Lookup::county_target_query())
				->when(($groupby == 1), function ($query){
					return $query->groupby('partner_name');
				})
				
				->when(($groupby == 2), function ($query){
					return $query->groupby('county_name');
				})
				// ->groupBy('partner_name','county_name')	
				->orderby("div_id", 'asc')			
				->get();
				// return DB::getQueryLog();
			
		}
		else{
			$periods = [];
			// Group By month
			if($groupby == 12){
				$periods = Period::select('financial_year', 'month')
				->whereRaw(Lookup::date_query())
				->groupBy('financial_year', 'month')
				->get();
				$target = DB::table($this->my_target_table)
				// ->join('countys', 'countys.id', '=', $this->my_target_table . '.county_id')
				// ->join('partners', 'partners.id', '=', $this->my_target_table . '.partner_id')
				->selectRaw($sql_test)
				// ->addSelect(DB::raw("partners.id as div_id, partners.name as partner_name,countys.name as county_name, countys.id as county_id"))
				// ->when(true, $this->get_predefined_groupby_callback('tx_curr'))
				->whereRaw(Lookup::county_target_query())
				// ->groupBy($grouping)				
				->get();

			}
			// Group By quarter
			else if($groupby == 13){
				$periods = Period::select('financial_year', 'quarter')
				->whereRaw(Lookup::date_query())
				->groupBy('financial_year', 'quarter')
				->get();
				$target = DB::table($this->my_target_table)
				// ->join('countys', 'countys.id', '=', $this->my_target_table . '.county_id')
				// ->join('partners', 'partners.id', '=', $this->my_target_table . '.partner_id')
				->selectRaw($sql_test)
				// ->addSelect(DB::raw("partners.id as div_id, partners.name as partner_name,countys.name as county_name, countys.id as county_id"))
				// ->when(true, $this->get_predefined_groupby_callback('tx_curr'))
				->whereRaw(Lookup::county_target_query())
				// ->groupBy($grouping)				
				->get();
				
			}
			if(!$periods) return null;

			$weeks = $week_ids = [];
			$current_week =[];

			$k =0;
			foreach ($periods as $period) {

				$w = Week::where($period->toArray())->orderBy('id', 'desc')->get();

				$p =  DB::table('weeks') 
				->where('financial_year',$period->financial_year)
				-> where('month', $period->month )
				->orderby('id','asc')
				->get();
				if(isset($w[$k])) $week_ids[] = $w[$k]->id; $weeks[] = $w;
				
			$k++;
			}
			foreach ($weeks as $key => $w ){				
				foreach ($w as $key => $w1 ){
					array_push($current_week,$w1->id);
				}
			}

			sort($current_week);
			// dd($current_week);
			// DB::enableQueryLog();
			if(!isset($groupbypartner)){
				$rows = DB::table($this->my_table)
				->when(true, $this->get_predefined_joins_callback_weeks($this->my_table))
				->selectRaw($sql)
				// ->when(true, $this->get_callback('tx_curr', null, '', 14))
				// ->when(true, $this->get_callback('tx_curr'))
				->whereIn('week_id', $current_week)
				->groupBy('year', 'month')
				->orderby('year','asc')
				->orderby('month','asc')
				->get();
			}else {
			$rows = DB::table($this->my_table)
				->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
				->selectRaw($sqlpartner)
				// ->when(true, $this->get_callback('tx_curr', null, '', 14))
				->when(true, $this->get_callback('tx_curr'))
				//->whereIn('week_id', $week_ids)
				->get();
			}
				// return DB::getQueryLog();
			// DB::enableQueryLog();
			$target = DB::table($this->my_target_table)
				// ->join('countys', 'countys.id', '=', $this->my_target_table . '.county_id')
				// ->join('partners', 'partners.id', '=', $this->my_target_table . '.partner_id')
				->selectRaw($sql_test)
				// ->addSelect(DB::raw("partners.id as div_id, partners.name as partner_name,countys.name as county_name, countys.id as county_id"))
				// ->when(true, $this->get_predefined_groupby_callback('tx_curr'))
				->whereRaw(Lookup::county_target_query())
				// ->groupBy($grouping)				
				->get();
			// return DB::getQueryLog();
		}

		//
		$i = 0;
		$data['yAxis'] = '';
		// dd($rows,$target);

		foreach ($rows as $key => $row){

			/*if($groupby > 9) $data['categories'][$key] = Lookup::get_category($row, 14);
			else{
				$data['categories'][$key] = Lookup::get_category($row);
			}*/
			// // if(!$row->tx_curr) continue;
			// $county_id = $row->div_id;
			// dd($row);
			$data['categories'][$i] = Lookup::get_category($row);
			$data["outcomes"][0]["data"][$i] = (int) $row->tx_curr;
			if($groupby < 10 || $groupby == 14){	

				if(isset($target[$i])) {
					$data["outcomes"][1]["data"][$i] = (int)  $target[$i]->val;
				}else{
					// dd($target,$i);
					$data["outcomes"][1]["data"][$i] = 0;
				}
			}else{
			$data["outcomes"][1]["data"][$i] = (int) $target[0]->val;
			}

			
			$i++;
		}	
		return view('charts.tx_curr', $data);
	}

	public function tx_curr_trend()
	{
		$data['div'] = str_random(15);
		$data['yAxis'] = 'Patients Current on Treatment';
		$data['suffix'] = '';

		$partner_filter = session('filter_partner');
		$groupby = session('filter_groupby');
		$ou = 'partnername';
		if ($groupby == 1 && (isset($partner_filter) || !($partner_filter == 'null' || $partner_filter == null)))
			$ou = 'countyname';

		$tx_curr = HfrSubmission::columns(true, 'tx_curr');
		$sql = "year, financial_year, month, {$ou}, ";
		$sql .= $this->get_hfr_sum($tx_curr, 'tx_curr');

		// Adding the category property in the base data pulled. For this graph the categories are always months of the current filtered year.
		$base_data = DB::table($this->my_table)
				->when(true, $this->get_predefined_joins_callback_weeks($this->my_table))
				->selectRaw($sql)
				->when(true, $this->get_callback())
				->groupBy($ou,'year','financial_year','month')
				->orderBy('year', 'asc')
				->orderBy('month', 'asc')
				->get()
				->whereNotIn('tx_curr', [0, '0', 'null', null])
				->map(function($item, $index) {
					$item->category = date("F", mktime(0, 0, 0, $item->month, 1)) . ", " . $item->year;
					return $item;
				});
		
		// Get the categories from the pulled data
		$categories = $base_data->pluck('category')->unique();

		$data['categories'] = array_values($categories->toArray());
		$data['outcomes'] = [];

		// Grouping by partner
		$base_data = $base_data->groupby($ou);

		foreach($base_data as $key => $grouped_data) {
			$data['outcomes'][] = [
				'name' => $key,
				'data' => $this->foramt_tx_curr_trend($categories, $grouped_data)
			];
		}
		
		return view('charts.line_graph', $data);
	}

	private function foramt_tx_curr_trend($categories, $data)
	{
		$return_data = [];
		foreach ($categories as $key => $category) {
			$ou_data = $data->where('category', $category)->first();
			$return_data[] = (int)($ou_data->tx_curr ?? 0);
		}
		return $return_data;
	}

	public function tx_curr_details()
	{
		// 
		//////////////////////////////////////
		$tx_curr = HfrSubmission::columns(true, 'tx_curr');
		$modality = 'tx_curr';
		$tests = HfrSubmission::columns(true, $modality); 
		$sql = $this->get_hfr_sum($tx_curr, 'tx_curr');
		$sql_test = $this->get_hfr_sum($tests, 'val');
		$data['div'] = str_random(15);


		$groupby = session('filter_groupby');
		$groupby_partner = session('filter_partner');

		if($groupby_partner != null){
			$grouping = 'countys.name';
		}else{
			$grouping = 'partners.name';
		}
		// dd($groupby);
		if($groupby < 10 || $groupby == 14){

			$week_id = Lookup::get_tx_week();
			// $data['chart_title'] = Week::find($week_id)->name;

			$rows = DB::table($this->my_table)
				->when(true, $this->get_predefined_joins_callback_weeks_hfr($this->my_table))
				->selectRaw($sql)
				->when(true, $this->get_predefined_groupby_callback('tx_curr'))
				->when(($groupby < 10), function($query) use($week_id) {
					return $query->where(['week_id' => $week_id]);
				})
				->get();
			$target = DB::table($this->my_target_table)
				->join('countys', 'countys.id', '=', $this->my_target_table . '.county_id')
				->join('partners', 'partners.id', '=', $this->my_target_table . '.partner_id')
				->selectRaw($sql_test)
				->addSelect(DB::raw("partners.id as div_id, partners.name as partner_name,countys.name as county_name, countys.id as county_id"))
				// ->when(true, $this->get_predefined_groupby_callback('tx_curr'))
				->whereRaw(Lookup::county_target_query_by_partner())
				->groupBy($grouping)				
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
			//  DB::enableQueryLog();
			$rows = DB::table($this->my_table)
				->when(true, $this->get_predefined_joins_callback_weeks_hfr($this->my_table))
				->selectRaw($sql)
				// ->when(true, $this->get_callback('tx_curr', null, '', 14))
				->when(true, $this->get_predefined_groupby_callback('tx_curr'))
				// ->whereIn('week_id', $week_ids)
				->get();
			// dd($week_ids);
			// dd($rows);s

				// return DB::getQueryLog();
			$target = DB::table($this->my_target_table)
				->join('countys', 'countys.id', '=', $this->my_target_table . '.county_id')
				->join('partners', 'partners.id', '=', $this->my_target_table . '.partner_id')
				->selectRaw($sql_test)
				->addSelect(DB::raw("partners.id as div_id, partners.name as partner_name,countys.name as county_name, countys.id as county_id"))
				// ->when(true, $this->get_predefined_groupby_callback('tx_curr'))
				->whereRaw(Lookup::county_target_query_by_partner())
				->groupBy($grouping)				
				->get();
				
				
		}
		
		


		$divisor = Lookup::get_target_divisor(1);
		// dd($divisor);
	
		
		// dd($target,$rows);

		return view('tables.tx_curr_details', ['rows' => $rows, 'target' => $target, 'div_id' => 'tx_curr_details', 'divisor' => strval($divisor) ]);
	}

	public function prep_new_last_rpt_period()
    {
		$group_by = session('filter_groupby');
		$groupby_partner = session('filter_partner');
		$modality = 'prep_new';
		$tests = HfrSubmission::columns(true, $modality);
		$sql_test = $this->get_hfr_sum($tests, 'val');

		if($groupby_partner != null){
			$grouping = 'countys.name';
		}else{
			$grouping = 'partners.name';
		}
        $prep_new = HfrSubmission::columns(true, 'prep_new');
        $sql = $this->get_hfr_sum($prep_new, 'prep_new');
		// DB::enableQueryLog();
        $rows = DB::table($this->my_table)
            ->when(true, $this->get_predefined_joins_callback_weeks_hfr($this->my_table))
            ->selectRaw($sql)
            ->when(true, $this->get_predefined_groupby_callback('prep_new'))
            ->get();
			// return DB::getQueryLog();
		$target = DB::table($this->my_target_table)
			->join('countys', 'countys.id', '=', $this->my_target_table . '.county_id')
			->join('partners', 'partners.id', '=', $this->my_target_table . '.partner_id')
			->selectRaw($sql_test)
			->addSelect(DB::raw("partners.id as div_id, partners.name as partner_name,countys.name as county_name, countys.id as county_id"))
			// ->when(true, $this->get_predefined_groupby_callback('tx_curr'))
			->whereRaw(Lookup::county_target_query_by_partner())
			->groupBy($grouping)				
			->get();
		$divisor = Lookup::get_target_divisor(12);
		// dd($target);

        $data['div'] = str_random(15);
        $data['rows'] = $rows;
		$data['groupby'] = $group_by;
		// dd($rows);

		return view('tables.prep_new_last_rpt_period', ['rows' => $rows, 'target' => $target, 'div_id' => 'prep_new_details', 'divisor' => strval($divisor) ]);
    }

	public function vmmc_circ_details()
	{
		$vmmc_circ = HfrSubmission::columns(true, 'vmmc_circ');
		$groupby_partner = session('filter_partner');
		$sql = $this->get_hfr_sum($vmmc_circ, 'vmmc_circ');
		$modality = 'vmmc_circ';
		$tests = HfrSubmission::columns(true, $modality);
		$sql_test = $this->get_hfr_sum($tests, 'val');

		if($groupby_partner != null){
			$grouping = 'countys.name';
		}else{
			$grouping = 'partners.name';
		}

		$rows = DB::table($this->my_table)
			->when(true, $this->get_predefined_joins_callback_weeks_hfr($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_predefined_groupby_callback('vmmc_circ'))
			->get();
					// return DB::getQueryLog();
		$target = DB::table($this->my_target_table)
			->join('countys', 'countys.id', '=', $this->my_target_table . '.county_id')
			->join('partners', 'partners.id', '=', $this->my_target_table . '.partner_id')
			->selectRaw($sql_test)
			->addSelect(DB::raw("partners.id as div_id, partners.name as partner_name,countys.name as county_name, countys.id as county_id"))
			// ->when(true, $this->get_predefined_groupby_callback('tx_curr'))
			->whereRaw(Lookup::county_target_query_by_partner())
			->groupBy($grouping)				
			->get();
		$divisor = Lookup::get_target_divisor(12);

		$data['div'] = str_random(15);
		$data['rows'] = $rows;		
			// dd($rows,$target);
		return view('tables.vmmc_circ_details', ['rows' => $rows, 'target' => $target, 'div_id' => 'vmmc_circ_details', 'divisor' => strval($divisor) ]);
	}

	public function tx_new_dis()
	{
		$tx_new = HfrSubmission::columns(true, 'tx_new');
		$sql = $this->get_hfr_sum($tx_new, 'tx_new');
		$groupby_partner = session('filter_partner');
		$modality = 'tx_new';
		$tests = HfrSubmission::columns(true, $modality);
		$sql_test = $this->get_hfr_sum($tests, 'val');

		if($groupby_partner != null){
			$grouping = 'countys.name';
		}else{
			$grouping = 'partners.name';
		}
		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_predefined_groupby_callback('tx_new'))
			->get();
		$target = DB::table($this->my_target_table)
			->join('countys', 'countys.id', '=', $this->my_target_table . '.county_id')
			->join('partners', 'partners.id', '=', $this->my_target_table . '.partner_id')
			->selectRaw($sql_test)
			->addSelect(DB::raw("partners.id as div_id, partners.name as partner_name,countys.name as county_name, countys.id as county_id"))
			// ->when(true, $this->get_predefined_groupby_callback('tx_curr'))
			->whereRaw(Lookup::county_target_query_by_partner())
			->groupBy($grouping)				
			->get();
		$divisor = Lookup::get_target_divisor(12);

		$data['div'] = str_random(15);

		// Lookup::bars($data, ["Tx New"], "column");

		// $i=0;
		// foreach ($rows as $key => $row){
		// 	if(!$row->tx_new) continue;
		// 	$data['categories'][$i] = Lookup::get_category($row);

		// 	$data["outcomes"][0]["data"][$i] = (int) $row->tx_new;
		// 	$i++;
		// }	
		// dd($rows,$target,$divisor);
		return view('tables.tx_new_details', ['rows' => $rows, 'target' => $target, 'div_id' => 'tx_new_details', 'divisor' => strval($divisor) ]);
	}

	public function testing_dis()
	{
		//DB::enableQueryLog();
		$tests = HfrSubmission::columns(true, 'hts_tst'); 
		$pos = HfrSubmission::columns(true, 'hts_tst_pos');
		$sql = $this->get_hfr_sum($tests, 'tests') . ', ' . $this->get_hfr_sum($pos, 'pos');
		$modality = 'hts_tst';
		$groupby_partner = session('filter_partner');
		$tests = HfrSubmission::columns(true, $modality);
		$sql_test = $this->get_hfr_sum($tests, 'val');

		if($groupby_partner != null){
			$grouping = 'countys.name';
		}else{
			$grouping = 'partners.name';
		}
		// DB::enableQueryLog();
		$rows = DB::table($this->my_table)
			->when(true, $this->get_predefined_joins_callback_weeks_hfr($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_predefined_groupby_callback('tests'))
			->get();
		$target = DB::table($this->my_target_table)
			->join('countys', 'countys.id', '=', $this->my_target_table . '.county_id')
			->join('partners', 'partners.id', '=', $this->my_target_table . '.partner_id')
			->selectRaw($sql_test)
			->addSelect(DB::raw("partners.id as div_id, partners.name as partner_name,countys.name as county_name, countys.id as county_id"))
			// ->when(true, $this->get_predefined_groupby_callback('tx_curr'))
			->whereRaw(Lookup::county_target_query_by_partner())
			->groupBy($grouping)				
			->get();
		$divisor = Lookup::get_target_divisor(12);

		$data['div'] = str_random(15);
		$data['rows'] = $rows;
		
		//  dd($target,$rows);

		// return DB::getQueryLog();
		return view('tables.testing_dis', ['rows' => $rows, 'target' => $target, 'div_id' => 'testing_dis', 'divisor' => strval($divisor) ]);
	}

	public function linkage_dis()
	{
		$pos = HfrSubmission::columns(true, 'hts_tst_pos');
		$tx_new = HfrSubmission::columns(true, 'tx_new');
		$modality = 'hts_tst_pos';
		$groupby_partner = session('filter_partner');
		$tests = HfrSubmission::columns(true, $modality);
		$sql_test = $this->get_hfr_sum($tests, 'val');
		$sql = $this->get_hfr_sum($pos, 'pos') . ', ' . $this->get_hfr_sum($tx_new, 'tx_new');

		if($groupby_partner != null){
			$grouping = 'countys.name';
		}else{
			$grouping = 'partners.name';
		}

		$rows = DB::table($this->my_table)
			->when(true, $this->get_predefined_joins_callback_weeks_hfr($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_predefined_groupby_callback('pos'))
			->get();
		$target = DB::table($this->my_target_table)
			->join('countys', 'countys.id', '=', $this->my_target_table . '.county_id')
			->join('partners', 'partners.id', '=', $this->my_target_table . '.partner_id')
			->selectRaw($sql_test)
			->addSelect(DB::raw("partners.id as div_id, partners.name as partner_name,countys.name as county_name, countys.id as county_id"))
			// ->when(true, $this->get_predefined_groupby_callback('tx_curr'))
			->whereRaw(Lookup::county_target_query_by_partner())
			->groupBy($grouping)				
			->get();

		$data['div'] = str_random(15);
		$divisor = Lookup::get_target_divisor(12);
		
		$data['rows'] = $rows;	

		// dd($rows,$target);
		
		return view('tables.linkage_dis', ['rows' => $rows, 'target' => $target, 'div_id' => 'linkage_dis', 'divisor' => strval($divisor) ]);
	}

	public function net_new()
	{
		$tx_curr = HfrSubmission::columns(true, 'tx_curr');
		$sql = $this->get_hfr_sum_prev($tx_curr, 'tx_curr');
		$sqlpartner = $this->get_hfr_sum($tx_curr, 'tx_curr');

		$data['div'] = str_random(15);

		Lookup::bars($data, ["TX Net New"], "column");

		$groupby = session('filter_groupby');
		$groupbypartner = session('filter_partner');
	
		if($groupby < 10 || $groupby == 14){
			

			$week_id = Lookup::get_tx_week();
			$prev_week_id = Lookup::get_tx_week_prev();
			// $data['chart_title'] = Week::find($week_id)->name;
			// dd($groupby);
			// DB::enableQueryLog();
			$rows = DB::table($this->my_table)
				->when(true, $this->get_predefined_joins_callback_weeks($this->my_table))
				->selectRaw($sql)
				->when(($groupby == 1), $this->get_callback('partner'))
				->when(($groupby == 2), $this->get_callback('county'))
				->when(($groupby < 10), function($query) use($week_id) {
					// return $query;
					return $query->whereIn('week_id', $week_id);
				})
				->get();
				// 
			$p_row = DB::table($this->my_table)
			->when(true, $this->get_predefined_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(($groupby == 1), $this->get_callback('partner'))
			->when(($groupby == 2), $this->get_callback('county'))
			->when(($groupby < 10), function($query) use($prev_week_id) {
				// return $query;
				return $query->whereIn('week_id', $prev_week_id);
			})
			->get();
			// return DB::getQueryLog();
			// dd($rows,$p_rows);
			

		}
		else{
			$periods = [];
			// Group By month
			if($groupby == 12){
				$periods = Period::select('financial_year', 'month')
				->whereRaw(Lookup::date_query())
				->groupBy('financial_year', 'month')
				->get();
				$p_periods = Period::select('financial_year', 'month')
				->whereRaw(Lookup::date_query_previous())
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

			$previous_week = $p_week = [];
			$last_rep_week = [];
			$current_week =[];
			// $tx_weeks = [];
			$k =0;
			// dd($periods);
			foreach ($periods as $period) {

				$w = Week::where($period->toArray())->orderBy('id', 'desc')->get();

				$p =  DB::table('weeks') 
				->where('financial_year',$period->financial_year)
				-> where('month', $period->month )
				->orderby('id','asc')
				->get();
				if(isset($w[$k])) $week_ids[] = $w[$k]->id; $weeks[] = $w;
				
			$k++;
			}

			$j =0; 
			// dd($p_periods);
			foreach ($p_periods as $period){
				$w = Week::where($period->toArray())->orderBy('id', 'desc')->get();

				$p_month =  $period->month ;
				$p_year = $period->financial_year ;
				

				$p =  DB::table('weeks') 
				->where('financial_year',$p_year)
				-> where('month', $p_month )
				->orderby('id','asc')
				->get();
				
				if(isset($p[$j])) $p_week[] = $p[$j]->id; $previous_week[] = $p;
			$j++;

			}

		// dd($previous_week,$weeks);

			foreach ($previous_week as $key => $pw ){				
				foreach ($pw as $key => $pw1 ){
					array_push($last_rep_week,$pw1->id);
				}
			}
			foreach ($weeks as $key => $w ){				
				foreach ($w as $key => $w1 ){
					array_push($current_week,$w1->id);
				}
			}
			sort($last_rep_week);
			sort($current_week);
			
			
			
			// DB::enableQueryLog();

			if(!isset($groupbypartner)){
				$rows = DB::table($this->my_table)
					->when(true, $this->get_predefined_joins_callback_weeks($this->my_table))
					->selectRaw($sql)
					// ->when(true, $this->get_callback('tx_curr', null, '', 14))
					// ->when(true, $this->get_callback('tx_curr'))
					->whereIn('week_id', $current_week)
					->groupBy('year', 'month')
					->orderby('year','asc')
					->orderby('month','asc')
					->get();
				$p_row = DB::table($this->my_table)
					->when(true, $this->get_predefined_joins_callback_weeks($this->my_table))
					->selectRaw($sql)
					// ->when(true, $this->get_callback_previous('tx_curr'))
					->whereIn('week_id', $last_rep_week)
					->groupBy('year', 'month')
					->orderby('year','asc')
					->orderby('month','asc')		
					->get();
				}else {
				$rows = DB::table($this->my_table)
					->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
					->selectRaw($sqlpartner)
					// ->when(true, $this->get_callback('tx_curr', null, '', 14))
					->when(true, $this->get_callback('tx_curr'))
					//->whereIn('week_id', $week_ids)
					->get();
				$p_row = DB::table($this->my_table)
					->when(true, $this->get_predefined_joins_callback_weeks($this->my_table))
					->selectRaw($sql)
					// ->when(true, $this->get_callback_previous('tx_curr'))
					->whereIn('week_id', $last_rep_week)
					->where('partner', $groupbypartner)
					->groupBy('year', 'month')
					->orderby('year','asc')
					->orderby('month','asc')		
					->get();
				
				}
				// return DB::getQueryLog();
			// DB::enableQueryLog();

			
			// return DB::getQueryLog();
			
			
				

		}
		// dd($p_row,$rows);
		$i = 0;
		foreach ($rows as $key => $row){			
			if(!$row->tx_curr) continue;
			$lastkey = $key - 1;
			if ($key < 1 ) $lastkey = 0;
			$data['categories'][$i] = Lookup::get_category($row);
			// if ($key > 2) dd($rows[$lastkey],$row->tx_curr);
			$data["outcomes"][0]["data"][$i] = ($row->tx_curr - $p_row[$key]->tx_curr);
			$i++;
		}	

	

		return view('charts.line_graph', $data);
	}

	public function net_new_detail()
	{
		$tx_curr = HfrSubmission::columns(true, 'tx_curr');
		$sql = $this->get_hfr_sum($tx_curr, 'tx_curr');
		$group_by = session('filter_groupby');

		$data['div'] = str_random(15);
		$data['groupby'] = $group_by;

		$groupby = session('filter_groupby');
	
		if($groupby < 10 || $groupby == 14){
			

			$week_id = Lookup::get_tx_week();
			// $data['chart_title'] = Week::find($week_id)->name;

			$rows = DB::table($this->my_table)
				->when(true, $this->get_predefined_joins_callback_weeks($this->my_table))
				->selectRaw($sql)
				->when(true, $this->get_predefined_groupby_callback('tx_curr'))
				->when(($groupby < 10), function($query) use($week_id) {
					return $query->where(['week_id' => $week_id]);
				})
				->get();

			$data['rows'] = $rows;
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
				->when(true, $this->get_predefined_joins_callback_weeks($this->my_table))
				->selectRaw($sql)
				// ->when(true, $this->get_callback('tx_curr', null, '', 14))
				->when(true, $this->get_predefined_groupby_callback('tx_curr'))
				->whereIn('week_id', $week_ids)
				->get();

			$data['rows'] = $rows;
		}
		// dd($data);
		return view('tables.net_new_detail', $data);
	}

	public function tx_crude()
	{
		$tx_curr = HfrSubmission::columns(true, 'tx_curr');
		$tx_new = HfrSubmission::columns(true, 'tx_new');
		$sqlpartner = $this->get_hfr_sum($tx_curr, 'tx_curr'). ', ' . $this->get_hfr_sum($tx_new, 'tx_new');
		$sql = $this->get_hfr_sum_prev($tx_curr, 'tx_curr'). ', ' . $this->get_hfr_sum($tx_new, 'tx_new');
		
		$data['div'] = str_random(15);
		$data['yAxis'] = 'Percentage';

		// Lookup::bars($data, [" Crude Retention "], "column");
		$data['outcomes'][0]['name'] = "Targeted Crude Retention";
		$data['outcomes'][1]['name'] = " Crude Retention";

		$groupby = session('filter_groupby');
		$groupbypartner = session('filter_partner');

		if($groupby < 10 || $groupby == 14){
			$week_id = Lookup::get_tx_week();
			$prev_week_id = Lookup::get_tx_week_prev();
			// $data['chart_title'] = Week::find($week_id)->name;
			// dd($groupby);
			// DB::enableQueryLog();
			$rows = DB::table($this->my_table)
				->when(true, $this->get_predefined_joins_callback_weeks($this->my_table))
				->selectRaw($sql)
				->when(($groupby == 1), $this->get_callback('partner'))
				->when(($groupby == 2), $this->get_callback('county'))
				->when(($groupby < 10), function($query) use($week_id) {
					// return $query;
					return $query->whereIn('week_id', $week_id);
				})
				->get();
				// 
			$p_row = DB::table($this->my_table)
			->when(true, $this->get_predefined_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(($groupby == 1), $this->get_callback('partner'))
			->when(($groupby == 2), $this->get_callback('county'))
			->when(($groupby < 10), function($query) use($prev_week_id) {
				// return $query;
				return $query->whereIn('week_id', $prev_week_id);
			})
			->get();

			$tx_new_rows  = DB::table($this->my_table)
					->when(true, $this->get_predefined_joins_callback_weeks($this->my_table))
					->selectRaw($sql)
					->when(($groupby == 1), $this->get_callback('partner'))
					->when(($groupby == 2), $this->get_callback('county'))
					->when(($groupby < 10), function($query) use($week_id) {
						// return $query;
						return $query->whereIn('week_id', $week_id);
					})
					->get();
			// dd($rows,$tx_new_rows);
		} else {
			 $periods = [];
			// Group By month
			if($groupby == 12){
			 	$periods = Period::select('financial_year', 'month')
					->whereRaw(Lookup::date_query())
					->groupBy('financial_year', 'month')
					->get();
				$p_periods = Period::select('financial_year', 'month')
					->whereRaw(Lookup::date_query_previous())
					->groupBy('financial_year', 'month')
					->get();
			} else if($groupby == 13) { // Group By quarter
				$periods = Period::select('financial_year', 'quarter')
					->whereRaw(Lookup::date_query())
					->groupBy('financial_year', 'quarter')
					->get();
			}
			if(!$periods) return null;

			$weeks = $week_ids = [];
			$previous_week = $p_week = [];
			$last_rep_week = [];
			$last_rep_week = [];
			$current_week =[];
			// $tx_weeks = [];
			// dd($periods);

			$k =0;
			// dd($periods);
			foreach ($periods as $period) {

				$w = Week::where($period->toArray())->orderBy('id', 'desc')->get();

				$p =  DB::table('weeks') 
				->where('financial_year',$period->financial_year)
				-> where('month', $period->month )
				->orderby('id','asc')
				->get();
				if(isset($w[$k])) $week_ids[] = $w[$k]->id; $weeks[] = $w;
				
				$k++;
			}

			$j =0; 
			// dd($p_periods);
			foreach ($p_periods as $period){
				$w = Week::where($period->toArray())->orderBy('id', 'desc')->get();

				$p_month =  $period->month ;
				$p_year = $period->financial_year ;
				

				$p =  DB::table('weeks') 
				->where('financial_year',$p_year)
				-> where('month', $p_month )
				->orderby('id','asc')
				->get();
				
				if(isset($p[$j])) $p_week[] = $p[$j]->id; $previous_week[] = $p;
				$j++;
			}

			// dd($previous_week,$weeks);

			foreach ($previous_week as $key => $pw ){				
				foreach ($pw as $key => $pw1 ){
					array_push($last_rep_week,$pw1->id);
				}
			}
			foreach ($weeks as $key => $w ){				
				foreach ($w as $key => $w1 ){
					array_push($current_week,$w1->id);
				}
			}
			sort($last_rep_week);
			sort($current_week);
			// DB::enableQueryLog();
			if(!isset($groupbypartner)){
				$rows = DB::table($this->my_table)
					->when(true, $this->get_predefined_joins_callback_weeks($this->my_table))
					->selectRaw($sql)
					// ->when(true, $this->get_callback('tx_curr', null, '', 14))
					// ->when(true, $this->get_callback('tx_curr'))
					->whereIn('week_id', $current_week)
					->groupBy('year', 'month')
					->orderby('year','asc')
					->orderby('month','asc')
					->get();
				$p_row = DB::table($this->my_table)
					->when(true, $this->get_predefined_joins_callback_weeks($this->my_table))
					->selectRaw($sql)
					// ->when(true, $this->get_callback_previous('tx_curr'))
					->whereIn('week_id', $last_rep_week)
					->groupBy('year', 'month')
					->orderby('year','asc')
					->orderby('month','asc')		
					->get();
			} else {
				$rows =DB::table($this->my_table)
				->when(true, $this->get_predefined_joins_callback_weeks($this->my_table))
				->selectRaw($sql)
				// ->when(true, $this->get_callback('tx_curr', null, '', 14))
				// ->when(true, $this->get_callback('tx_curr'))
				->whereIn('week_id', $current_week)
				->where('partner',$groupbypartner)
				->groupBy('year', 'month')
				->orderby('year','asc')
				->orderby('month','asc')
				->get();
				$p_row = DB::table($this->my_table)
					->when(true, $this->get_predefined_joins_callback_weeks($this->my_table))
					->selectRaw($sql)
					// ->when(true, $this->get_callback_previous('tx_curr'))
					->whereIn('week_id', $last_rep_week)
					->where('partner',$groupbypartner)
					->groupBy('year', 'month')
					->orderby('year','asc')
					->orderby('month','asc')		
					->get();
			}
			$tx_new_rows  = DB::table($this->my_table)
			->when(true, $this->get_predefined_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('tx_new'))
			->whereIn('week_id', $current_week)
			->get();
		}
		$i = 0;
		foreach ($rows as $key => $row){
				if(!$row->tx_curr) continue;
				$lastkey = $key - 1;
				if ($key < 1 ) $lastkey = 0;
				 $data['categories'][$i] = Lookup::get_category($row);
				$results = ($row->tx_curr);
				// var_dump($row->tx_curr);
				$results_2 = (($tx_new_rows[$key]->tx_new + $p_row[$key]->tx_curr));
				
				// if ($key > 2) dd($rows[$lastkey],$row->tx_curr,$tx_new_rows[$key]->tx_new);
				$target = 90;
				$data["outcomes"][0]["data"][$key] =  $target;
				$data["outcomes"][1]["data"][$i] = Lookup::get_percentage($results,$results_2);
				// dd($results,$results_2,$data["outcomes"][1]["data"][$i]);
				$i++;
		}
		
		return view('charts.line_graph', $data);
	}

	public function tx_crude_trend()
	{		
		$data['div'] = str_random(15);
		$data['yAxis'] = 'Percentage';

		$groupby = session('filter_groupby');
		$partner_filter = session('filter_partner');

		$ou = 'partnername';
		if ($groupby == 1 && (isset($partner_filter) || !($partner_filter == 'null' || $partner_filter == null)))
			$ou = 'countyname';

		$periods = Period::select('year', 'financial_year', 'month')
					->whereRaw(Lookup::date_query())
					->orderby('year', 'asc')
					->orderby('month', 'asc')
					->get()
					->map(function ($item, $key) {
						$current_period = $item->year . '-' . $item->month . '-01';
						$previous_period = strtotime("-1 Month", (strtotime($current_period)));
						$item->category = date("F", mktime(0, 0, 0, $item->month, 1)) . ", " . $item->year;
						$item->previous_period = (object)[
										'year' => date('Y', $previous_period),
										'month' => date('m', $previous_period)
									];
						return $item;
					});

		// x-Axis is always the months to show the trend in months
		$categories = $periods->pluck('category')->toArray();
		
		$data['categories'] = $categories;

		// Generate the target line
		$data['outcomes'][0] = [
			'name' => 'Targeted Crude Retention',
			"data" => $this->format_tx_crude_trend($periods)
		];
	
		$tx_curr = HfrSubmission::columns(true, 'tx_curr');
		$tx_new = HfrSubmission::columns(true, 'tx_new');
		$sql = $ou . ", ". $this->get_hfr_sum_prev($tx_curr, 'tx_curr'). ', ' . $this->get_hfr_sum($tx_new, 'tx_new');
		$base_data = DB::table($this->my_table)
					->selectRaw($sql)
					->when(true, $this->get_predefined_joins_callback_weeks($this->my_table))
					->when(true, $this->get_callback())
					->whereRaw(Lookup::date_query_previous())
					->groupBy($ou,'year','financial_year','month')
					->orderby('year','asc')
					->orderby('month','asc')
					->get()
					->whereNotIn('tx_curr', [0, '0', 'null', null])
					->groupBy($ou);
		
		foreach ($base_data as $key => $grouped_data) {
			$data['outcomes'][] = [
				'name' => $key,
				'data' => $this->format_tx_crude_trend($periods, $grouped_data)
			];
		}
		
		return view('charts.line_graph', $data);
	}

	private function format_tx_crude_trend($periods, $data = NULL)
	{
		$return_data = [];
		foreach ($periods as $key => $period) {
			$return_value = 95;
			if (isset($data)) {
				$current_ou_data = $data->where('year', $period->year)->where('month', $period->month)->first();
				$previous_ou_data = $data->where('year', $period->previous_period->year)->where('month', $period->previous_period->month)->first();
				
				// Tx Crude retention calculatioin : ((Current On treatment(tx_curr) of the current month) / ((Current On treatment(tx_curr) of the previous month) + (New On treatment(tx_new) of the current month))) * 100 (%)
				$tx_curr = (int)($current_ou_data->tx_curr ?? 0);
				$tx_new = (int)($current_ou_data->tx_new ?? 0);
				$prev_tx_curr = (int)($previous_ou_data->tx_curr ?? 0);
				$numerator = $tx_curr;
				$denominator = $prev_tx_curr + $tx_new;

				$return_value = 0;
				if ($denominator > 0) {
					$return_value = round((($numerator/$denominator) * 100), 2);
				}
			}
			$return_data[] = $return_value;
		}
		return $return_data;
	}

	public function prep_new()
	{
		$prep_new = HfrSubmission::columns(true, 'prep_new');
		$sql = $this->get_hfr_sum($prep_new, 'prep_new');
		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
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
    

    public function vmmc_circ()
	{
		$vmmc_circ = HfrSubmission::columns(true, 'vmmc_circ');
		$sql = $this->get_hfr_sum($vmmc_circ, 'vmmc_circ');

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
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
			->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
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

	public function tx_mmd_detail()
	{
		$group_by = session('filter_groupby');
		
		
		$less_3m = HfrSubmission::columns(true, 'less_3m');
		$less_5m = HfrSubmission::columns(true, '3_5m');
		$above_6m = HfrSubmission::columns(true, 'above_6m');
		
		$sql = $this->get_hfr_sum($less_3m, 'less_3m') . ', ' . $this->get_hfr_sum($less_5m, 'less_5m') . ', ' . $this->get_hfr_sum($above_6m, 'above_6m');

    	$divisions_query = Lookup::divisions_query();
        $date_query = Lookup::date_query();

		$rows = DB::table($this->my_table)
			->when(true, $this->get_predefined_joins_callback_weeks_hfr($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_predefined_groupby_callback('less_5m'))
			->get();

		$data['div'] = str_random(15);
		$data['rows'] = $rows;
		$data['groupby'] = $group_by;
		return view('tables.tx_mmd_detail', $data);
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
			->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
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
			->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
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
		// dd($week_id);
	
		// DB::enableQueryLog();

		$results = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
			->selectRaw($sql)
			->when($modality != 'tx_curr', function($query) use($date_query){
				return $query->whereRaw($date_query);
			})
			->when(($modality == 'tx_curr'), function($query) use($week_id) {
				return $query->whereIn('week_id', $week_id);
			})
			->whereRaw(Lookup::divisions_query())
			->first();

		$target = DB::table($this->my_target_table)
			->join('countys', 'countys.id', '=', $this->my_target_table . '.county_id')
			->join('partners', 'partners.id', '=', $this->my_target_table . '.partner_id')
			->selectRaw($sql)
			->whereRaw(Lookup::county_target_query())
			->first();

			// return DB::getQueryLog();
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
