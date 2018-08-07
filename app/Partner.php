<?php

namespace App;

use App\BaseModel;

class Partner extends BaseModel
{

	public function facility()
	{
		return $this->hasMany('App\Facility', 'partner');
	}

	public function funding_agency()
	{
		return $this->belongsTo('App\FundingAgency');
	}
}
