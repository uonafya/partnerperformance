<?php

namespace App\Etl\Models;

use Illuminate\Database\Eloquent\Model;

class CountyETL extends Model
{
    //
    protected $connection = 'mysql_etl';
    protected $table = 'countys';
    protected $guarded = [];

}
