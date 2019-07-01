<?php

namespace App;

use App\BaseModel;

class SurgeModality extends BaseModel
{

    public function surge_column()
    {
        return $this->hasMany('App\SurgeColumn', 'modality_id');
    }

    public function scopeSurge($query)
    {
        return $query->where(['tbl_name' => 'd_surge']);
    }
}
