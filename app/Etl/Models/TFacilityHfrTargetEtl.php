<?php

namespace App\Etl\Models;

use Illuminate\Database\Eloquent\Model;

class TFacilityHfrTargetEtl extends Model
{
    //
    protected $connection = 'mysql_etl';
    protected $table = 't_facility_hfr_target';
    protected $guarded = [];
}
