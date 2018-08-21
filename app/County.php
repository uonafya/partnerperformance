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

	// Facilities reporting in both forms
	// 729 currently art
	// New on art as a % of enrolled
	// Positives with new on art - stacks side by side


}
