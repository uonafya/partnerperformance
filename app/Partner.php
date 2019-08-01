<?php

namespace App;

use App\BaseModel;

class Partner extends BaseModel
{

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope('hjf', function(Builder $builder){
    //         $builder->where('id', '!=', 69);
    //     });
    // }

	public function facility()
	{
		return $this->hasMany('App\Facility', 'partner');
	}

	public function funding_agency()
	{
		return $this->belongsTo('App\FundingAgency');
	}


}
