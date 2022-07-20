<?php

namespace App\Etl\Models;

use Illuminate\Database\Eloquent\Model;

class WeeksETL extends Model
{
    //
    protected $connection = 'mysql_etl';
    protected $table = 'weeks';
    protected $guarded = [];
}
