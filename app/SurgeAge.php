<?php

namespace App;

use App\BaseModel;

class SurgeAge extends BaseModel
{

    public function surge_column()
    {
        return $this->hasMany('App\SurgeColumn', 'age_id');
    }

    public function category()
    {
        return $this->belongsTo('App\AgeCategory', 'age_category_id');
    }


    public function scopeSurge($query)
    {
        // return $query->where(['for_surge' => 1])->orderBy('age_category_id', 'asc')->orderBy('id', 'asc');
        return $query->where(['for_surge' => 1])->orderBy('max_age', 'asc');
    }
	
    public function scopeVmmcCirc($query)
    {
        return $query->where(['for_vmmc' => 1])->orderBy('max_age', 'asc');
    }
	
    public function scopeTx($query)
    {
        return $query->where(['for_tx_curr' => 1])->orderBy('max_age', 'asc');
    }
    
    public function scopePrepNew($query)
    {
        return $query->where('age_category_id', '!=', 2)->orderBy('max_age', 'asc');
    }
    
    public function scopeGbv($query)
    {
        return $query->where(['for_gbv' => 1])->orderBy('max_age', 'asc');
    }
    
    public function scopeCervicalCancer($query)
    {
        return $query->where(['for_cervical_cancer' => 1])->orderBy('max_age', 'asc');
    }
}
