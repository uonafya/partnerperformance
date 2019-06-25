<?php

namespace App;

use App\BaseModel;

class AgeCategory extends BaseModel
{

	public function getAgeCatAttribute()
	{
		$s = strtolower($this->age_category);
		$s = str_replace(' ', '_', $s);
		return $s;
	}
}
