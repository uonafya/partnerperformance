<?php

namespace App;

use App\BaseModel;

class Week extends BaseModel
{

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
