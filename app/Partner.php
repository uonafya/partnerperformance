<?php

namespace App;

use App\BaseModel;

class Partner extends BaseModel
{

	public function facility()
	{
		return $this->hasMany('App\Facility', 'partner');
	}
}
