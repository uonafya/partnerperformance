<?php

namespace App\Imports;

use DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FacilitiesImport implements ToCollection, WithHeadingRow
{
    private $partner;

    function __construct($partner)
    {
        $this->partner = $partner;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $mflcodes = [];
        $partner = $this->partner;

        foreach ($collection as $key => $row) {
            if(!in_array($row['mfl_code'], $mflcodes)) $mflcodes[] = $row['mfl_code'];
        }

        foreach ($mflcodes as $key => $value) {
            $fac = DB::table('facilitys')->where('facilitycode', $value)->first();
            if(!$fac) dd($value);
        }
        // return;

        // madow -> afya ziwani
        // 

        if(env('APP_ENV') != 'testing'){
            $facility_ids = DB::table('facilitys')->whereIn('facilitycode', $mflcodes)->get()->pluck('id');


            DB::table('supported_facilities')->whereIn('facility_id', $facility_ids)->update(['partner_id' => $partner]);

            DB::table('apidb.facilitys')->whereIn('facilitycode', $mflcodes)->update(['partner' => $partner]);
            DB::table('national_db.facilitys')->whereIn('facilitycode', $mflcodes)->update(['partner' => $partner]);
        }
    }
}
