<?php

namespace App\Etl\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityEtl extends Model
{
    //
    protected $connection = 'mysql';

    protected $table = 'facility_etl';


    protected $guarded = [];
}
