<?php

namespace App;

use App\BaseModel;

class Period extends BaseModel
{

	public function getMonthNameAttribute()
	{
		return Lookup::resolve_month($this->month);
	}
}
