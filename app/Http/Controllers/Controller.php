<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use DB;
use App\Lookup;

use App\DataSetElement;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function check_null($object, $attr = 'total')
    {
    	if(!$object) return 0;
    	return (int) $object->$attr;
    }

    public function get_joins_callback($table_name)
    {
        return function($query) use($table_name){
            return $query->join('view_facilitys', 'view_facilitys.id', '=', "{$table_name}.facility")
                ->join('periods', 'periods.id', '=', "{$table_name}.period_id");
        };        
    }

    public function get_joins_callback_weeks($table_name)
    {
        return function($query) use($table_name){
            return $query->join('view_facilitys', 'view_facilitys.id', '=', "{$table_name}.facility")
                ->join('weeks', 'weeks.id', '=', "{$table_name}.week_id");
        };        
    }

    // Add Divisions Query Here
    // Also Add Date Query Here

    public function get_callback($order_by=null, $having_null=null, $prepension='')
    {
    	$groupby = session('filter_groupby', 1);
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
    		$var = Lookup::groupby_query();
    		return $this->divisions_callback($divisions_query, $date_query, $var, $order_by, $having_null);
    	}
    }

    public function get_callback_no_dates($order_by=null, $having_null=null)
    {
        $groupby = session('filter_groupby', 1);
        $divisions_query = Lookup::divisions_query();
        $date_query = "1";
        if($groupby > 9){
            if($groupby == 10) return $this->year_callback($divisions_query, $date_query);
            if($groupby == 11) return $this->financial_callback($divisions_query, $date_query);
            if($groupby == 12) return $this->year_month_callback($divisions_query, $date_query);
            if($groupby == 13) return $this->year_quarter_callback($divisions_query, $date_query);
            if($groupby == 14) return $this->week_callback($divisions_query, $date_query);
        }
        else{
            $var = Lookup::groupby_query();
            return $this->divisions_callback($divisions_query, $date_query, $var, $order_by, $having_null);
        }
    }

    public function year_callback($divisions_query, $date_query, $prepension)
    {
    	return function($query) use($divisions_query, $prepension){
    		return $query->addSelect("{$prepension}.year")
				->whereRaw($divisions_query)
                ->whereRaw($date_query)
    			->groupBy("{$prepension}.year")
    			->orderBy("{$prepension}.year", 'asc');
    	};
    }

    public function financial_callback($divisions_query, $date_query)
    {
    	return function($query) use($divisions_query, $date_query){
    		return $query->addSelect('financial_year')
				->whereRaw($divisions_query)
                ->whereRaw($date_query)
    			->groupBy('financial_year')
    			->orderBy('financial_year', 'asc');
    	};
    }

    public function year_month_callback($divisions_query, $date_query, $prepension)
    {
    	return function($query) use($divisions_query, $date_query, $prepension){
    		return $query->addSelect("{$prepension}.year", "{$prepension}.month")
				->whereRaw($divisions_query)
                ->whereRaw($date_query)
    			->groupBy("{$prepension}.year", "{$prepension}.month")
    			->orderBy("{$prepension}.year", 'asc')
    			->orderBy("{$prepension}.month", 'asc');
    	};
    }

    public function year_quarter_callback($divisions_query, $date_query)
    {
    	return function($query) use($divisions_query, $date_query){
    		return $query->addSelect('financial_year', 'quarter')
				->whereRaw($divisions_query)
                ->whereRaw($date_query)
    			->groupBy('financial_year', 'quarter')
    			->orderBy('financial_year', 'asc')
    			->orderBy('quarter', 'asc');
    	};
    }

    public function week_callback($divisions_query, $date_query)
    {
        return function($query) use($divisions_query, $date_query){
            return $query->addSelect('financial_year', 'week_number')
                ->whereRaw($divisions_query)
                ->whereRaw($date_query)
                ->groupBy('financial_year', 'week_number')
                ->orderBy('financial_year', 'asc')
                ->orderBy('week_number', 'asc');
        };
    }

    public function divisions_callback($divisions_query, $date_query, $var, $order_by=null, $having_null=null)
    {
    	$raw = DB::raw($var['select_query']);

    	if($order_by){
	    	return function($query) use($divisions_query, $date_query, $var, $raw, $order_by, $having_null){
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
	    	return function($query) use($divisions_query, $date_query, $var, $raw){
	    		return $query->addSelect($raw)
					->whereRaw($divisions_query)
                    ->whereRaw($date_query)
	    			->groupBy($var['group_query']);
	    	};
    	}
    }

    public function target_callback()
    {    	
		$groupby = session('filter_groupby', 1);
		$date_query = Lookup::date_query(true);
		$divisions_query = Lookup::divisions_query();

		if($groupby > 9){
	    	return function($query) use($date_query, $divisions_query){
	    		return $query->whereRaw($divisions_query)
	    			->whereRaw($date_query);
	    	};
		}
		else{
			$var = Lookup::groupby_query();
			$raw = DB::raw($var['select_query']);

	    	return function($query) use($date_query, $divisions_query, $var, $raw){
	    		return $query->addSelect($raw)
	    			->whereRaw($divisions_query)
	    			->whereRaw($date_query)
	    			->groupBy($var['group_query']);
	    	};			
		}
    }

    public function surge_columns_callback($modality=true, $gender=true, $age=true)
    {
        $columns_query = Lookup::surge_columns_query($modality, $gender, $age);
        return function($query) use($columns_query){
            return $query->whereRaw($columns_query)
                ->orderBy('modality_id', 'asc')
                ->orderBy('gender_id', 'asc')
                ->orderBy('age_id', 'asc');
        };
    }


	
}
