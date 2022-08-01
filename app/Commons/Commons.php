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

trait modelparams{
	protected $connection = 'mysql_wr';
    protected $table = 'view_facilitys';
    public $data;
}