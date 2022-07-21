<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partners extends Model
{
    public $data;
    protected $connection = 'mysql_wr';

    public static function transform($partners)
    {
        return $partners->map(function ($item) {
            // return $item;

            return [
                'old_id' => $item->id,
                'name' => $item->name,
                'partnerDHIScode' => $item->partnerDHIScode,
                'mech_id' => $item->mech_id,
                'funding_agency' => $item->funding_agency,
                'funding_agency_id' => $item->funding_agency_id,
                'logo' => $item->logo,
            ];
        });
    }
}
