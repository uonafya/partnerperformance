<?php

namespace App\Etl\Models;

use Illuminate\Database\Eloquent\Model;

class DHfrSubmissionEtl extends Model
{
    //
    protected $connection = 'mysql_etl';
    protected $table = 'd_hfr_submission';
    protected $guarded = [];
}
