<?php

namespace App\Etl\Models;

use Illuminate\Database\Eloquent\Model;

class ViewFacilitiesEtl extends Model
{
    //
<<<<<<< HEAD
    protected $connection = 'mysql';
=======
    protected $connection = 'mysql_etl';
    public $table = 'view_facilitys';
    public $data;
>>>>>>> 97f25db22d3d5e317e55881e5a17a75cd76bde14

    protected $guarded = [];
}
