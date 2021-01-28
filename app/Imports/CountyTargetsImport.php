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
    private $inserted_rows;

    function __construct()
    {
    	$this->table_name = 't_county_target';
        $this->inserted_rows = [];
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

        $unidentified = [];
        // DB::enableQueryLog();

        foreach ($collection as $key => $value) {
            if($value[0] == 'mech_code') continue;

            $county_name = explode(' ', $value[1])[0];
            $county_name = explode('-', $county_name)[0];

            $mech_id = ((int) $value[0]);
            // if($mech_id == 84476) $mech_id = 84475;

            $p = Partner::where('mech_id', $mech_id)->first();
            $c = County::where('name', 'like', $county_name . '%')->first();

            if(!$p){
                $value[10] = 'Partner Not Found';
                $value[11] = $key;
                $unidentified[] = $value;
                if($partner) $this->insertRow($locator, $data);
                continue;
                dd($value);
            }

            if(!$c){
                $value[10] = 'County Not Found';
                dd($value);
            }

            if($p != $partner || $c != $county){

                if($partner){
                    $this->insertRow($locator, $data);
                }

                $partner = $p;
                $county = $c;

                // $locator = $data = ['county' => $county->name, 'partner' => $partner->name,
                $locator = $data = ['county_id' => $county->id, 'partner_id' => $partner->id, 'financial_year' => 2021];

                foreach ($hfr_columns as $hfr_column) {
                    $data[$hfr_column['column_name']] = 0;
                }
            }

            $gender = strtolower($value[2]);
            $age = 'above_15';
            if(Str::contains($value[3], ['1-', '5-9', '10', '<01'])) $age = 'below_15';

            foreach ($modalities as $modality_key => $modality) {
                if($gender == 'female' && $modality == 'vmmc_circ') continue;
                $data["{$modality}_{$age}_{$gender}"] += (int) $value[4 + $modality_key]; 
                // dd("{$modality}_{$age}_{$gender} is " . ((int) $value[4 + $modality_key]));
            }
        }
        // $this->insertRow($locator, $data);
        // DB::enableQueryLog();

        // DB::table($this->table_name)->insert($this->inserted_rows);   
        
        // dd(json_encode($this->inserted_rows));     

        // dd(DB::getQueryLog());

        // if($unidentified) dd($unidentified);
    }


    public function insertRow($locator, $data)
    {
        $row = DB::table($this->table_name)->where($locator)->first();
        // if(!$row) dd($data);
        // $row = null;

        if($row){
            $updated = DB::table($this->table_name)->where('id', $row->id)->update($data);
            // dd("updated is {$updated} ");
        }else{
            $inserted = DB::table($this->table_name)->insert($data);
            // $inserted = DB::table($this->table_name)->insertGetId($data);
            // dd("inserted is {$inserted} " . json_encode($data));
            $this->inserted_rows[] = $data;
        }
    }
}
