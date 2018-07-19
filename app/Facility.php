<?php

namespace App;

use App\BaseModel;

class Facility extends BaseModel
{
	protected $table = 'facilitys';

	public function ward()
	{
		return $this->belongsTo('App\Ward');
	}

	public function subcounty()
	{
		return $this->belongsTo('App\Subcounty');
	}
}
