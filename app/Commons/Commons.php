<?php

namespace App\Commons;

use Illuminate\Http\Request;
use DB;
use App\Lookup;
use App\HfrSubmission;
use App\Partner;
use App\Period;
use App\Week;
use Dotenv\Regex\Result;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use function Symfony\Component\VarDumper\Dumper\esc;


trait Commons
{
    private $my_table = 'd_hfr_submission';
	private $my_target_table = 't_county_target';
	private $my_hfr_facility_target_table = 't_facility_hfr_target';
	private $my_floating = 'floating_target';	
 
}

trait get_hfr_sum
{
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
}

trait get_hfr_sum_prev{
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
}

trait get_joins_callback_weeks_hfr{
    public function get_joins_callback_weeks_hfr($table_name)
    {
        $active_partner_query = Lookup::active_partner_hfr_query();
        return function($query) use($table_name, $active_partner_query){
            // return $query->join('view_facilitys', 'view_facilitys.id', '=', "{$table_name}.facility")
            return $query->join('view_facilities', 'view_facilities.id', '=', "{$table_name}.facility")
                ->join('weeks', 'weeks.id', '=', "{$table_name}.week_id")
                ->whereRaw($active_partner_query);
        };        
    }
}

trait  get_callback{
    public function get_callback($order_by=null, $having_null=null, $prepension='', $force_filter=null)
    {
    	$groupby = session('filter_groupby', 1);
        if($force_filter) $groupby = $force_filter;
    	$divisions_query = Lookup::divisions_query();
        $date_query = Lookup::date_query(false, $prepension);
    	if($groupby > 9){
    		if($groupby == 10) return $this->year_callback($divisions_query, $date_query, $prepension);
    		if($groupby == 11) return $this->financial_callback($divisions_query, $date_query);
    		if($groupby == 12) return $this->year_month_callback($divisions_query, $date_query, $prepension);
    		if($groupby == 13) return $this->year_quarter_callback($divisions_query, $date_query);
            if($groupby == 14) return $this->week_callback($divisions_query, $date_query);
    	}
    	else{
    		$var = Lookup::groupby_query(true, $force_filter);
    		return $this->divisions_callback($divisions_query, $date_query, $var, $groupby, $order_by, $having_null);
    	}
    }
}

trait divisions_callback{

    public function divisions_callback($divisions_query, $date_query, $var, $groupby, $order_by=null, $having_null=null)
    {
    	$raw = DB::raw($var['select_query']);
        $unshowable = Lookup::get_unshowable();

    	if($order_by){
	    	return function($query) use($divisions_query, $date_query, $var, $groupby, $raw, $unshowable, $order_by, $having_null){
                if($groupby == 5) $query->whereNotIn('view_facilities.id', $unshowable);
                if($groupby == 1) $query->where('partner', '!=', 69);

                if($having_null){
                    return $query->addSelect($raw)
                        ->whereRaw($divisions_query)
                        ->whereRaw($date_query)
                        ->groupBy($var['group_query'])
                        ->having($having_null, '>', 0)
                        ->orderBy($order_by, 'desc');                    
                }
	    		return $query->addSelect($raw)
					->whereRaw($divisions_query)
                    ->whereRaw($date_query)
	    			->groupBy($var['group_query'])
	    			->orderBy($order_by, 'desc');
	    	};
    	}
    	else{
	    	return function($query) use($divisions_query, $date_query, $var, $groupby, $raw, $unshowable){
                if($groupby == 5) $query->whereNotIn('view_facilities.id', $unshowable);
                if($groupby == 1) $query->where('partner', '!=', 69);
                
	    		return $query->addSelect($raw)
					->whereRaw($divisions_query)
                    ->whereRaw($date_query)
	    			->groupBy($var['group_query']);
	    	};
    	}
    }

}





trait testingServiceRoutine{
    public function testingServiceRoutine()
	{
		Cache::forget('testingServiceRoutine');

		// DB::enableQueryLog();
		$tests = HfrSubmission::columns(true, 'hts_tst'); 
		$pos = HfrSubmission::columns(true, 'hts_tst_pos');
		$sql = $this->get_hfr_sum($tests, 'tests') . ', ' . $this->get_hfr_sum($pos, 'pos');

		// dd($sql);

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('tests'))
			->get();

		// dd($rows);
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
		foreach ($rows as $row){
			if(!$row->tests) continue;

			$data['categories'][$i] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$i] = (int) $row->pos;
			$data["outcomes"][1]["data"][$i] = (int) ($row->tests - $row->pos);
			$data["outcomes"][2]["data"][$i] = Lookup::get_percentage($row->pos, $row->tests);
			$i++;
		}
		return $data;
	}
}

trait linkageServiceRoutine{
	public function linkageServiceRoutine()
	{
		Cache::forget("linkageServiceRoutine");

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

		return $data;
	}

}

trait tx_curr_oldServiceRoutines{
	public function tx_curr_oldServiceRoutines()
	{
		Cache::forget("tx_curr_oldServiceRoutines");

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

		return $data;
	}
}

trait tx_currServiceRoutine{
	public function tx_currServiceRoutine()
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

            $rows = DB::table($this->my_table)
                ->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
                ->selectRaw($sql)
                ->when(true, $this->get_callback_tx_curr('tx_curr'))
                ->when(($groupby < 10), function ($query) use ($week_id) {
                    return $query->where(['week_id' => $week_id]);
                })
                ->orderby("div_id", 'asc')
                ->get();
				
            if ($groupby == 1 || $groupby == 2) {
                $target = DB::table($this->my_target_table)
                    ->join('countys', 'countys.id', '=', $this->my_target_table . '.county_id')
                    ->join('partners', 'partners.id', '=', $this->my_target_table . '.partner_id')
                    ->selectRaw($sql_test)
                    ->when(($groupby == 1), function ($query) {
                        return $query->addSelect(DB::raw("partners.name as partner_name,partners.id as div_id"));
                    })
                    ->when(($groupby == 2), function ($query) {
                        return $query->addSelect(DB::raw(" countys.name as county_name, countys.id as div_id"));
                    })
                    ->whereRaw(Lookup::county_target_query())
                    ->when(($groupby == 1), function ($query) {
                        return $query->groupby('partner_name');
                    })
                    ->when(($groupby == 2), function ($query) {
                        return $query->groupby('county_name');
                    })
                    ->orderby("div_id", 'asc')
                    ->get();
            } else {
                    $target = DB::table($this->my_hfr_facility_target_table)
                        ->join('view_facilities', 'view_facilities.id', '=', $this->my_hfr_facility_target_table . '.facility_id')
                        ->selectRaw($sql_test)
                        ->when(($groupby == 3), function ($query) {
                            return $query->addSelect(DB::raw("view_facilities.subcounty as subcounty_name, view_facilities.county as county_id , view_facilities.subcounty_id as div_id"));
                        })
                        ->when(($groupby == 5), function ($query) {
                            return $query->addSelect(DB::raw("view_facilities.name as facility_name,view_facilities.id as div_id"));
                        })
                        ->whereRaw(Lookup::facility_target_query())
                        ->when(($groupby == 3), function ($query) {
                            return $query->groupby(DB::raw("div_id,subcounty_name"));
                        })
                        ->when(($groupby == 5), function ($query) {
                            return $query->groupby('facility_name')->first();
                        })
                        ->orderby("div_id", 'asc')
                        ->get();


                }

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
				->selectRaw($sql_test)
				->whereRaw(Lookup::county_target_query())				
				->get();

			}
			// Group By quarter
			else if($groupby == 13){
				$periods = Period::select('financial_year', 'quarter')
				->whereRaw(Lookup::date_query())
				->groupBy('financial_year', 'quarter')
				->get();
				$target = DB::table($this->my_target_table)
				->selectRaw($sql_test)
				->whereRaw(Lookup::county_target_query())			
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
			if(!isset($groupbypartner)){
				$rows = DB::table($this->my_table)
				->when(true, $this->get_predefined_joins_callback_weeks($this->my_table))
				->selectRaw($sql)
				->whereIn('week_id', $current_week)
				->groupBy('year', 'month')
				->orderby('year','asc')
				->orderby('month','asc')
				->get();
			}else {
			$rows = DB::table($this->my_table)
				->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
				->selectRaw($sqlpartner)
				->when(true, $this->get_callback('tx_curr'))
				->get();
			}
			$target = DB::table($this->my_target_table)
				->selectRaw($sql_test)
				->whereRaw(Lookup::county_target_query())				
				->get();
		}

		$i = 0;
		$data['yAxis'] = '';

		foreach ($rows as $key => $row){
			$data['categories'][$i] = Lookup::get_category($row);
			$data["outcomes"][0]["data"][$i] = (int) $row->tx_curr;
			if($groupby < 10 || $groupby == 14){	

				if(isset($target[$i])) {
					$data["outcomes"][1]["data"][$i] = (int)  $target[$i]->val;
				}
			}else{
			$data["outcomes"][1]["data"][$i] = (int) $target[0]->val;
			}

			
			$i++;
		}	

		return $data;
	}

}

trait tx_newServiceRoutine{
	public function tx_newServiceRoutine()
	{
		Cache::forget("tx_newServiceRoutine");
		
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

            if ($groupby == 1 || $groupby == 2) {
                //  DB::enableQueryLog();
                $target = DB::table($this->my_floating)
                    ->join('countys', 'countys.id', '=', $this->my_floating . '.county_id')
                    ->join('partners', 'partners.id', '=', $this->my_floating . '.partner_id')
                    ->selectRaw($sql_target)
                    ->when(($groupby == 1), function ($query) {
                        return $query->addSelect(DB::raw(" partners.name as partner_name,partner_id as div_id"));
                    })
                    ->when(($groupby == 2), function ($query) {
                        return $query->addSelect(DB::raw("countys.name as county_name, countys.id as div_id"));
                    })

                    // ->when(true, $this->get_predefined_groupby_callback('tx_curr'))
                    ->whereRaw(Lookup::county_target_query())
                    ->where($this->my_floating . '.month', '<=', $today)
                    // ->when(($groupby == 1), $this->get_callback('partner_name'))
                    // ->when(($groupby == 2), $this->get_callback('county_name'))
                    ->when(($groupby == 1), function ($query) {
                        return $query->groupby('partner_name');
                    })
                    ->when(($groupby == 2), function ($query) {
                        return $query->groupby('county_name');
                    })
                    ->orderby("div_id", 'asc')
                    ->get();
                // return DB::getQueryLog();
            }

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

		return $data;
	}
}


trait tx_curr_trendServiceRoutine{
	public function tx_curr_trendServiceRoutine()
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
		// DB::enableQueryLog();
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
				// return DB::getQueryLog();
		
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

		return $data;
	}
}

trait modelparams{
	protected $connection = 'mysql_wr';
    protected $table = 'view_facilitys';
    public $data;
}

trait linkageDisServiceRoutineRows{
	public function linkageDisServiceRoutineRows()
	{
		$pos = HfrSubmission::columns(true, 'hts_tst_pos');
		$tx_new = HfrSubmission::columns(true, 'tx_new');
		$sql = $this->get_hfr_sum($pos, 'pos') . ', ' . $this->get_hfr_sum($tx_new, 'tx_new');

		$rows = DB::table($this->my_table)
			->when(true, $this->get_predefined_joins_callback_weeks_hfr($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_predefined_groupby_callback('pos'))
			->get();
		
		return $rows;

	}

	
}

trait linkageDisServiceRoutineTarget{

	public function linkageDisServiceRoutineTarget()
	{
		$modality = 'hts_tst_pos';
		$groupby_partner = session('filter_partner');
		$tests = HfrSubmission::columns(true, $modality);
		$sql_test = $this->get_hfr_sum($tests, 'val');

		if($groupby_partner != null){
			$grouping = 'countys.name';
		}else{
			$grouping = 'partners.name';
		}

		$target = DB::table($this->my_target_table)
			->join('countys', 'countys.id', '=', $this->my_target_table . '.county_id')
			->join('partners', 'partners.id', '=', $this->my_target_table . '.partner_id')
			->selectRaw($sql_test)
			->addSelect(DB::raw("partners.id as div_id, partners.name as partner_name,countys.name as county_name, countys.id as county_id"))
			// ->when(true, $this->get_predefined_groupby_callback('tx_curr'))
			->whereRaw(Lookup::county_target_query_by_partner())
			->groupBy($grouping)				
			->get();

		return $target;
	}
}

trait prep_new_last_rpt_period_serviceRoutine_rows{
	public function prep_new_last_rpt_period_serviceRoutine_rows()
	{
		
        $prep_new = HfrSubmission::columns(true, 'prep_new');
        $sql = $this->get_hfr_sum($prep_new, 'prep_new');

		$rows = DB::table($this->my_table)
		->when(true, $this->get_predefined_joins_callback_weeks_hfr($this->my_table))
		->selectRaw($sql)
		->when(true, $this->get_predefined_groupby_callback('prep_new'))
		->get();

		return $rows;
	}
}

trait prep_new_last_rpt_period_serviceRoutine_target{
	public function prep_new_last_rpt_period_serviceRoutine_target()
	{
	
		$groupby_partner = session('filter_partner');
		$modality = 'prep_new';
		$tests = HfrSubmission::columns(true, $modality);
		$sql_test = $this->get_hfr_sum($tests, 'val');

		if($groupby_partner != null){
			$grouping = 'countys.name';
		}else{
			$grouping = 'partners.name';
		}

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

		return $target;
	}

}

trait vmmc_circ_details_serveceRoutine_target{
	public function vmmc_circ_details_serveceRoutine_target()
	{	
		$modality = 'vmmc_circ';
		$tests = HfrSubmission::columns(true, $modality);
		$sql_test = $this->get_hfr_sum($tests, 'val');
		$groupby_partner = session('filter_partner');


		if($groupby_partner != null){
			$grouping = 'countys.name';
		}else{
			$grouping = 'partners.name';
		}

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

		return $target;
	}
}

trait vmmc_circ_details_serviceRoutineRows{
	public function vmmc_circ_details_serviceRoutineRows()
	{
		$vmmc_circ = HfrSubmission::columns(true, 'vmmc_circ');
		$sql = $this->get_hfr_sum($vmmc_circ, 'vmmc_circ');

		$rows = DB::table($this->my_table)
			->when(true, $this->get_predefined_joins_callback_weeks_hfr($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_predefined_groupby_callback('vmmc_circ'))
			->get();

		return $rows;
	}

}