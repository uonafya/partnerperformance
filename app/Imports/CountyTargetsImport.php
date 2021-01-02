<?php

namespace App\Imports;

use App\HfrSubmission;
use App\Partner;
use App\County;
use DB;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Str;

class CountyTargetsImport implements ToCollection
{

    private $table_name;

    function __construct()
    {
    	$this->table_name = 't_county_target';
    }


    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $partner = null;
        $county = null;

        $locator = [];

        $hfr_columns = HfrSubmission::columns(false, null, null, null, true);

        $modalities = [
            'hts_tst', 'hts_tst_pos', 'prep_new', 'tx_curr', 'tx_new', 'vmmc_circ'
        ];

        foreach ($collection as $key => $value) {
            if($value[0] == 'mech_code') continue;

            $p = Partner::where('mech_id', $value[0])->first();
            $c = County::where('name', 'like', (explode(' ', $value[1])[0] ?? 'notfound') . '%')->first();

            if(!$p){
                $value[] = 'Partner Not Found';
                dd($value);
            }

            if(!$c){
                $value[] = 'County Not Found';
                dd($value);
            }

            if($p != $partner || $c != $county){

                if($partner){
                    $row = DB::table($this->table)->where($locator)->first();
                    if($row){
                        DB::table($this->table)->where('id', $row->id)->update($data);
                    }else{
                        DB::table($this->table)->insert($data);
                    }
                }

                $partner = $p;
                $county = $c;

                $locator = $data = ['county_id' => $county->id, 'partner_id' => $partner->id, 'financial_year' => 2021];

                foreach ($hfr_columns as $hfr_column) {
                    $data[$hfr_column['column_name']] = 0;
                }
            }

            $gender = strtolower($value[2]);
            $age = 'above_15';
            if(Str::contains($value[3], ['1-', '5-', '10', '<01'])) $age = 'below_15';

            foreach ($modalities as $modality_key => $modality) {
                $data["{$modality}_{$age}_{$gender}"] += (int) $value[4 + $modality_key]; 
            }
        }

        $row = DB::table($this->table)->where($locator)->first();
        if($row){
            DB::table($this->table)->where('id', $row->id)->update($data);
        }else{
            DB::table($this->table)->insert($data);
        }
    }
}
