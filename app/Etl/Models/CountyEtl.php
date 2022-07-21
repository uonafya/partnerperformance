<?php

namespace App\Etl\Models;

use Illuminate\Database\Eloquent\Model;

class CountyEtl extends Model
{
    //
    protected $connection = 'mysql';

    protected $table = 'county_etl';


    protected $guarded = [];
}
