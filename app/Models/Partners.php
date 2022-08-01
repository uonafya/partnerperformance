<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partners extends Model
{
    public $data;
    protected $connection = 'mysql_wr';

    public static function transform($partners)
    {
        return $load->map(function ($item) {
            // return $item;

            return [
                // 'id' => $item->id,
                'name' => $item->name,
                'partnerDHISCode' => $item->partnerDHISCode,
                'mech_id' => $item->mech_id,
                'fundingagency' => $item->fundingagency,
                'funding_agency_id' => $item->funding_agency_id,
                'logo' => $item->logo,
                'country' => $item->country,
                'flag' => $item->flag,
                'orderno' => $item->orderno,
                'unknown2013' => $item->unknown2013,
                'unknown2014' => $item->unknown2014,
                'unknown2015' => $item->unknown2015,
            ];
        });
    }
}
