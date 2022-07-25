<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use stdClass;
use Symfony\Component\VarDumper\Cloner\Data;

use App\Etl\Models\ViewFacilitiesEtl;

class ViewFacilities extends Model
{
    public $data;
    protected $connection = 'mysql_wr';

    public static function transform($vf)
    {
       return $vf->map(function($item){
            // return $item;
            return [
                'old_id' => $item->id,
                'longitude' => $item->longitude ,
                'latitude' => $item->latitude ,
                'DHIScode' => $item->DHIScode ,
                'facilitycode' => $item->facilitycode ,
                'name' => $item->name ,
                'ward_id' => $item->ward_id ,
                'wardname' => $item->wardname ,
                'WardDHISCode' => $item->WardDHISCode ,
                'district' => $item->district ,
                'subcounty' => $item->subcounty ,
                'parteners' => $item->partner ,
                'partnersname' => $item->partnername ,
                'partner2' => $item->partner2 ,
                'start_of_support' => $item->start_of_support ,
                'end_of_support' => $item->end_of_support ,
                'funding_agency_id' => $item->funding_agency_id ,
                'funding_agency' => $item->funding_agency ,
                'county_id' => $item->county ,
                'countyname' => $item->countyname ,
            ];
        });
    } 


}
