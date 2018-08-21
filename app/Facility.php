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
		return $this->belongsTo('App\Subcounty', 'subcounty_id');
	}


    public function scopeEligible($query, $offset=0)
    {
        return $query->whereNotNull('DHIScode')
        		->where('DHIScode', '!=', '0')
        		->where('invalid_dhis', 0)
        		->limit(50)->offset($offset);
    }
}
