<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partners extends Model
{
    public $data;
    protected $connection = 'mysql_wr';

    public static function transform($load)
    {
       return $load->map(function($item){
            return $item;

            // return [
                
            // ];
        });
    } 
}
