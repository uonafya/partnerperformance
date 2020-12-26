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

}
