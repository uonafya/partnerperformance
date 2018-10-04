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

    // Add Divisions Query Here

    public function get_callback($order_by=null)
    {
    	$groupby = session('filter_groupby', 1);
    	$divisions_query = Lookup::divisions_query();
    	if($groupby > 9){
    		if($groupby == 10) return $this->year_callback($divisions_query);
    		if($groupby == 11) return $this->financial_callback($divisions_query);
    		if($groupby == 12) return $this->year_month_callback($divisions_query);
    		if($groupby == 13) return $this->year_quarter_callback($divisions_query);
    	}
    	else{
    		$var = Lookup::groupby_query();
    		return $this->divisions_callback($divisions_query, $var, $order_by);
    	}
    }

    public function year_callback($divisions_query)
    {
    	return function($query) use($divisions_query){
    		return $query->addSelect('year')
				->whereRaw($divisions_query)
    			->groupBy('year')
    			->orderBy('year', 'asc');
    	};
    }

    public function financial_callback($divisions_query)
    {
    	return function($query) use($divisions_query){
    		return $query->addSelect('financial_year')
				->whereRaw($divisions_query)
    			->groupBy('financial_year')
    			->orderBy('financial_year', 'asc');
    	};
    }

    public function year_month_callback($divisions_query)
    {
    	return function($query) use($divisions_query){
    		return $query->addSelect('year', 'month')
				->whereRaw($divisions_query)
    			->groupBy('year', 'month')
    			->orderBy('year', 'asc')
    			->orderBy('month', 'asc');
    	};
    }

    public function year_quarter_callback($divisions_query)
    {
    	return function($query) use($divisions_query){
    		return $query->addSelect('financial_year', 'quarter')
				->whereRaw($divisions_query)
    			->groupBy('financial_year', 'quarter')
    			->orderBy('financial_year', 'asc')
    			->orderBy('quarter', 'asc');
    	};
    }

    public function divisions_callback($divisions_query, $var, $order_by=null)
    {
    	$raw = DB::raw($var['select_query']);

    	if($order_by){
	    	return function($query) use($divisions_query, $var, $raw, $order_by){
	    		return $query->addSelect($raw)
					->whereRaw($divisions_query)
	    			->groupBy($var['group_query'])
	    			->orderBy($order_by, 'desc');
	    	};
    	}
    	else{
	    	return function($query) use($divisions_query, $var, $raw){
	    		return $query->addSelect($raw)
					->whereRaw($divisions_query)
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


	
}
