<?php

namespace App;

use App\BaseModel;

class AgeCategory extends BaseModel
{


    public function age()
    {
        return $this->hasMany('App\SurgeAge', 'age_category_id');
    }

	public function getAgeCatAttribute()
	{
		$s = strtolower($this->age_category);
		$s = str_replace(' ', '_', $s);
		return $s;
	}
}
