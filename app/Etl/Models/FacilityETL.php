<?php

namespace App\Etl\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityETL extends Model
{
    //
    protected $connection = 'mysql_etl';

    protected $table = 'facility_etl';


}
