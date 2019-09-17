<?php

namespace App;

use App\BaseModel;

class SurgeColumn extends BaseModel
{


    public function age()
    {
        return $this->belongsTo('App\SurgeColumn', 'age_id');
    }

    public function gender()
    {
        return $this->belongsTo('App\SurgeColumn', 'gender_id');
    }

    public function modality()
    {
        return $this->belongsTo('App\SurgeColumn', 'modality_id');
    }
}
