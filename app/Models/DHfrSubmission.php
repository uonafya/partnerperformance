<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DHfrSubmission extends Model
{
    public $data;
    protected $connection = 'mysql_wr';

    public static function transform($vf)
    {
       return $vf->map(function($item){
            return $item;
       });
    }
}
