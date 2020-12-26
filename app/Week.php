<?php

namespace App;

use App\BaseModel;
use Carbon\Carbon;

class Week extends BaseModel
{

    public function getYrAttribute()
    {
        return substr($this->financial_year, 2, 2);
    }

    public function my_date_format($value, $format='d-M-Y')
    {
        if($this->$value) return date($format, strtotime($this->$value));
        return '';
    }

    public function getNameAttribute()
    {
    	return "Week {$this->week_number} - {$this->start_date} TO  {$this->end_date}";
    }


    public static function change_start_of_week()
    {
        $weeks = Week::all();
        foreach ($weeks as $key => $week) {
            $week->start_date = Carbon::create($week->start_date)->addDay()->toDateString();
            $week->end_date = Carbon::create($week->end_date)->addDay()->toDateString();
            $end_date = Carbon::create($week->end_date)->addDay();
            $week->fill(Synch::get_financial_year_quarter($end_date->year, $end_date->month));
            $week->save();
        }
    }
}
