<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use stdClass;
use Symfony\Component\VarDumper\Cloner\Data;

use App\Etl\Models\FacilityEtl;

class Facility extends Model
{
    public $data;
    protected $connection = 'mysql_wr';
    protected $table = 'facilitys';

    public static function transform($facility_tr)
    {
        return $facility_tr->map(function ($item) {
            // return $item;

            return [
                'old_id' => $item->id,
                'name' => $item->name,
                'new_name' => $item->new_name,
                'facilitycode' => $item->facility_code,
                'district' => $item->district,
                'subcounty_id' => $item->subcounty_id,
                'ward_id' => $item->ward_id,
                'lab' => $item->lab,
                'partner' => $item->partner,
                'facility_type' => $item->ftype,
                'DHIS_Code' => $item->DHIScode,
                'facility_uid' => $item->facility_uid,
                'community' => $item->community,
                'is_pns' => $item->is_pns,
                'is_viremia' => $item->is_viremia,
                'is_dsd' => $item->is_dsd,
                'is_otz' => $item->is_otz,
                'is_men_clinic' => $item->is_men_clinic,
                'is_surge' => $item->is_surge,
                'longitude' => $item->longitude,
                'latitude' => $item->latitude,
                'burden' => $item->burden,
                'art_patients' => $item->artpatients,
                'pmtctnos' => $item->pmtctnos,
                'Mless15' => $item->Mless15,
                'Mmore15' => $item->Mmore15,
                'Fless15' => $item->Fless15,
                'Fmore15' => $item->Fmore15,
                'total_art_Mar' => $item->totalartmar,
                'total_art_Sep17' => $item->totalartsep17,
                'total_art_Sep15' => $item->totalartsep15,
                'asofdate' => $item->tasofdate,
                'partnerold' => $item->partnerold,
                'partner2' => $item->partner2,
                'partner3' => $item->partner3,
                'partner4' => $item->partner4,
                'partner5' => $item->partner5,
                'partner6' => $item->partner6,
                'telephone' => $item->telephone,
                'telephone2' => $item->telephone2,
                'contact_person' => $item->contactperson,
            ];
        });
    }
}
