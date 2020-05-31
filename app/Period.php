<?php

namespace App;

use App\BaseModel;

class Period extends BaseModel
{

	public function getMonthNameAttribute()
	{
		return Lookup::resolve_month($this->month);
	}


    public function scopeAchievement($query)
    {
    	$date_query = Lookup::date_query();

        return $query->whereRaw($date_query)
        	->whereRaw("year < ". date('Y') ." OR (year = ". date('Y') ." AND month < ". date('m') .")  ");
    }
}
