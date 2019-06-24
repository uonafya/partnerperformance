<?php

namespace App;

use App\BaseModel;

class SurgeAge extends BaseModel
{


    public function scopeSurge($query)
    {
        return $query->where(['for_surge' => 1])->orderBy('age_category_id', 'asc')->orderBy('id', 'asc');
    }
}
