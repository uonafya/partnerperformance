<?php

namespace App\Etl\Models;

use Illuminate\Database\Eloquent\Model;

class PartnersETL extends Model
{
    //
    protected $connection = 'mysql_etl';
    protected $table = 'partners_etl';
    protected $guarded = [];
}
