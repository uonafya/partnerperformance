<?php

namespace App;

use App\BaseModel;

class FundingAgency extends BaseModel
{
    
    public function partner()
	{
		return $this->hasMany('App\Partner');
	}
}
