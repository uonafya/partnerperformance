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
        $active_partner_query = Lookup::active_partner_query();
        return function($query) use($table_name, $active_partner_query){
            // return $query->join('view_facilitys', 'view_facilitys.id', '=', "{$table_name}.facility")
            return $query->join('view_facilities', 'view_facilities.id', '=', "{$table_name}.facility")
                ->join('periods', 'periods.id', '=', "{$table_name}.period_id")
                ->whereRaw($active_partner_query);
        };        
    }

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
    public function get_joins_callback_weeks($table_name)
    {
        $active_partner_query = Lookup::active_partner_query();
        return function($query) use($table_name, $active_partner_query){
            // return $query->join('view_facilitys', 'view_facilitys.id', '=', "{$table_name}.facility")
            return $query->join('view_facilities', 'view_facilities.id', '=', "{$table_name}.facility")
                ->join('weeks', 'weeks.id', '=', "{$table_name}.week_id")
                ->whereRaw($active_partner_query);
        };        
    }
    public function get_predefined_joins_callback_weeks($table_name)
    {
        $active_partner_query = Lookup::predefined_active_partner_query();
        return function($query) use($table_name, $active_partner_query){
            // return $query->join('view_facilitys', 'view_facilitys.id', '=', "{$table_name}.facility")
            return $query->join('view_facilities', 'view_facilities.id', '=', "{$table_name}.facility")
                ->join('weeks', 'weeks.id', '=', "{$table_name}.week_id")
                ->whereRaw($active_partner_query);
        };        
    }
    public function get_predefined_joins_callback_weeks_hfr($table_name)
    {
        $active_partner_query = Lookup::predefined_active_partner_hfr_query();
        return function($query) use($table_name, $active_partner_query){
            // return $query->join('view_facilitys', 'view_facilitys.id', '=', "{$table_name}.facility")
            return $query->join('view_facilities', 'view_facilities.id', '=', "{$table_name}.facility")
                ->join('weeks', 'weeks.id', '=', "{$table_name}.week_id")
                ->whereRaw($active_partner_query);
        };        
    }

    // Add Divisions Query Here
    // Also Add Date Query Here

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
    public function get_callback_previous($order_by=null, $having_null=null, $prepension='', $force_filter=null)
    {
    	$groupby = session('filter_groupby', 1);
        if($force_filter) $groupby = $force_filter;
    	$divisions_query = Lookup::divisions_query();
        // $date_query
        $date_query = Lookup::date_query_previous(false, $prepension);
    	// if($groupby > 9){
    		if($groupby == 10) return $this->year_callback($divisions_query, $date_query, $prepension);
    		if($groupby == 11) return $this->financial_callback($divisions_query, $date_query);
    		if($groupby == 12) return $this->year_month_callback($divisions_query, $date_query, $prepension);
    	// 	if($groupby == 13) return $this->year_quarter_callback($divisions_query, $date_query);
        //     if($groupby == 14) return $this->week_callback($divisions_query, $date_query);
    	// }
    	// else{
    		$var = Lookup::groupby_query(true, $force_filter);
    		return $this->divisions_callback($divisions_query, $date_query, $var, $groupby, $order_by, $having_null);
    	// }
    }

    public function get_callback_tx_curr ($order_by=null, $having_null=null, $prepension='', $force_filter=null)
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
    		return $this->divisions_callback($divisions_query, $date_query, $var, $groupby,  $having_null);
    	}
    }
    public function get_predefined_groupby_callback($order_by=null, $having_null=null, $prepension='', $force_filter=null)
    {
        $groupby = 1; //default filter
        if($force_filter) {
            $groupby = 1;
        }elseif (session('filter_partner') != null){
            $groupby = 2;
        }else{
            $groupby = 1;
        }
        // dd($force_filter,session('filter_partner'));
    	$divisions_query = Lookup::divisions_query();
        $date_query = Lookup::predefined_date_query(false, $prepension);
        
        $var = Lookup::predefined_groupby_query($groupby); //$groupby = 1,2...;
        // dd($date_query);
        return $this->divisions_callback($divisions_query, $date_query, $var, $groupby, $order_by, $having_null);
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
            return $this->divisions_callback($divisions_query, $date_query, $var, $groupby, $order_by, $having_null);
        }
    }

    public function year_callback($divisions_query, $date_query, $prepension)
    {
    	return function($query) use($divisions_query, $date_query, $prepension){
    		return $query->addSelect("{$prepension}year")
				->whereRaw($divisions_query)
                ->whereRaw($date_query)
    			->groupBy("{$prepension}year")
    			->orderBy("{$prepension}year", 'asc');
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
    		return $query->addSelect("{$prepension}year", "{$prepension}month")
				->whereRaw($divisions_query)
                ->whereRaw($date_query)
    			->groupBy("{$prepension}year", "{$prepension}month")
    			->orderBy("{$prepension}year", 'asc')
    			->orderBy("{$prepension}month", 'asc');
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
            return $query->addSelect('financial_year', 'week_number', 'start_date', 'end_date')
                ->whereRaw($divisions_query)
                ->whereRaw($date_query)
                ->groupBy('financial_year', 'week_number')
                ->orderBy('financial_year', 'asc')
                ->orderBy('week_number', 'asc');
        };
    }

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

    public function target_callback($force_filter=null,$for_ward=false,$for_county=false)
    {    	
		$groupby = session('filter_groupby', 1);
        if(in_array($groupby, [1,5]) && $for_ward) $force_filter = 2;
        if(in_array($groupby, [3,4,5]) && $for_county) $force_filter = 2;
        if($force_filter) $groupby = $force_filter;
		$date_query = Lookup::date_query(true);
		$divisions_query = Lookup::divisions_query($for_ward);

		if($groupby > 9){
	    	return function($query) use($date_query, $divisions_query){
	    		return $query->whereRaw($divisions_query)
	    			->whereRaw($date_query);
	    	};
		}
		else{
			$var = Lookup::groupby_query(true, $force_filter);
			$raw = DB::raw($var['select_query']);

            if(in_array($groupby, [1,5]) && $for_ward){
                return function($query){
                    return $query->where('ward_id', '<', 0);
                };  
            }

            if(in_array($groupby, [3,4,5]) && $for_county){
                return function($query){
                    return $query->where('county_id', '<', 0);
                };  
            }

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

    public function get_sum($columns, $name)
    {
        $sql = "(";

        foreach ($columns as $column) {
            $sql .= "SUM(`{$column->column_name}`) + ";
        }
        $sql = substr($sql, 0, -3);
        $sql .= ") AS {$name} ";
        return $sql;
    }


	
}
