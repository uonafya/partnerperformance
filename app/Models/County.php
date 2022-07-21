<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use stdClass;
use Symfony\Component\VarDumper\Cloner\Data;

use App\Etl\Models\CountyEtl;

class County extends Model
{
    public $data;
    protected $connection = 'mysql_wr';
    protected $table = 'countys';

    public static function transform($county_tr)
    {
        return $county_tr->map(function ($item) {
            // return $item;

            return [
                'old_id' => $item->id,
                'name' => $item->name,
                'DHIScode' => $item->DHIScode,
                'MFLcode' => $item->CountyMFLCode,
                'rawcode' => $item->rawcode,
                'Coordinates' => $item->CountyCoordinates,
                'pmtctneed1617' => $item->pmtctneed1617,
                'letter' => $item->letter,
            ];
        });
    }
}
