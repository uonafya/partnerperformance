<?php

namespace App;

use App\BaseModel;

class County extends BaseModel
{
	protected $table = 'countys';

	public function subcounty()
	{
		return $this->hasMany('App\Subcounty', 'county');
	}
}
