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

        foreach ($collection as $key => $row) {
            $mflcodes[] = $row['mfl_code'];
        }

        DB::table('facilitys')->whereIn('facilitycode', $mflcodes)->update(['partner' => $partner]);
        DB::table('apidb.facilitys')->whereIn('facilitycode', $mflcodes)->update(['partner' => $partner]);
        DB::table('national_db.facilitys')->whereIn('facilitycode', $mflcodes)->update(['partner' => $partner]);

    }
}
