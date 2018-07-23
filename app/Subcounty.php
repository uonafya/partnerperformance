<?php

namespace App;

use App\BaseModel;

class Subcounty extends BaseModel
{
	protected $table = 'districts';

	public function county()
	{
		return $this->belongsTo('App\County', 'county');
	}

	public function ward()
	{
		return $this->hasMany('App\Ward', 'subcounty_id');
	}

	public function facility()
	{
		return $this->hasMany('App\Facility', 'district');
	}
}
