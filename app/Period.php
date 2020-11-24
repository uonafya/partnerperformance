<?php

namespace App;

use App\BaseModel;

class Period extends BaseModel
{

	public function getMonthNameAttribute()
	{
		return Lookup::resolve_month($this->month);
	}

    public function getYrAttribute()
    {
        return substr($this->financial_year, 2, 2);
    }

    public function getActiveDateAttribute()
    {
        return $this->year . '-' . $this->month . '-01';
    }

    public function getNameAttribute()
    {
        return Lookup::resolve_month($this->month) . ', ' . $this->year;
    }

    public function getFullNameAttribute()
    {
        return  'FY ' . $this->yr . ', ' . Lookup::resolve_month($this->month);
    }


    public function scopeAchievement($query)
    {
    	$date_query = Lookup::date_query();

        $quarter = session('filter_quarter');
        $month = session('filter_month');

        return $query->whereRaw($date_query)
            ->when((!$quarter && !$month), function($query){
                return $query->whereRaw("(year < ". date('Y') ." OR (year = ". date('Y') ." AND month < ". date('m') ."))  ");
            });
    }

    public function scopeLastMonth($query)
    {
        $y = date('Y');
        $m = date('m');

        if($m == 1){
            return $query->where('year', ($y-1))->where('month', 12);
        }else{
            return $query->where('year', $y)->where('month', ($m-1));            
        }
    }
}
