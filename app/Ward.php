<?php

namespace App;

use App\BaseModel;

class Ward extends BaseModel
{

	public function subcounty()
	{
		return $this->belongsTo('App\Subcounty');
	}

	public function facility()
	{
		return $this->hasMany('App\Facility');
	}
}
