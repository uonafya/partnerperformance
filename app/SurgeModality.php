<?php

namespace App;

use App\BaseModel;

class SurgeModality extends BaseModel
{

    public function scopeSurge($query)
    {
        return $query->where(['tbl_name' => 'd_surge']);
    }
}
