<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counties extends Model
{
    public $data;
    protected $connection = 'mysql_wr';
    protected $table = 'countys';

    public static function transform($load)
    {
       return $load->map(function($item){
            // return $item->name;
            
            return [
                'id' => $item->id ,
                'name' => $item->name, 
                'CountyDHISCode' => $item->CountyDHISCode , 
                'CountyMFLCode' => $item->CountyMFLCode , 
                'rawcode' => $item->rawcode , 
                'CountyCoordinates' => $item->CountyCoordinates , 
                'pmtctneed1617' => $item->pmtctneed1617 ,  
                'letter' => $item->letter ,  
            ];
        });
    }
    
}
