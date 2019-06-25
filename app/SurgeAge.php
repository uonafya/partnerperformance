<?php

namespace App;

use App\BaseModel;

class SurgeAge extends BaseModel
{

    public function scopeSurge($query)
    {
        return $query->where(['for_surge' => 1])->orderBy('age_category_id', 'asc')->orderBy('id', 'asc');
    }
	
    public function scopeVmmc($query)
    {
        return $query->where(['for_vmmc' => 1])->orderBy('age_category_id', 'asc')->orderBy('id', 'asc');
    }
	
    public function scopeTx($query)
    {
        return $query->where(['for_tx_curr' => 1])->orderBy('age_category_id', 'asc')->orderBy('id', 'asc');
    }
    
    public function scopePrep($query)
    {
        return $query->where('age_category_id', '!=', 2)->orderBy('age_category_id', 'asc')->orderBy('id', 'asc');
    }
}
