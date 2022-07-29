<?php

namespace App\Etl\Models;

use Illuminate\Database\Eloquent\Model;

class ViewFacilitiesEtl extends Model
{
    //
    protected $connection = 'mysql_etl';
    public $table = 'view_facilitys';
    public $data;

    protected $guarded = [];

}
