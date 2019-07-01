<?php

namespace App;

use App\BaseModel;

class SurgeGender extends BaseModel
{

    public function surge_column()
    {
        return $this->hasMany('App\SurgeColumn', 'gender_id');
    }
}
