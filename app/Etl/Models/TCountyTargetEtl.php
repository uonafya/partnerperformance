<?php

namespace App\Etl\Models;

use Illuminate\Database\Eloquent\Model;

class TCountyTargetEtl extends Model
{
    //
    protected $connection = 'mysql_etl';
    protected $table = 't_county_target';
    protected $guarded = [];
}
