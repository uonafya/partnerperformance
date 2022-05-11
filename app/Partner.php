<?php

namespace App;

use App\BaseModel;
use Illuminate\Database\Eloquent\Builder;

class Partner extends BaseModel
{

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('hjf', function(Builder $builder){
            $builder->where('funding_agency_id','=', 1);
        });
    }

	public function facility()
	{
		return $this->hasMany('App\Facility', 'partner');
	}

	public function funding_agency()
	{
		return $this->belongsTo('App\FundingAgency');
	}

	public function getDownloadNameAttribute()
	{
		return str_replace(' ', '_', strtolower($this->name));
	}


}
