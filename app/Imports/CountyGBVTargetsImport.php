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

class CountyGBVTargetsImport implements ToCollection
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

        $unidentified = [];
        // DB::enableQueryLog();

        foreach ($collection as $key => $value) {
            if($value[0] == 'mech_code' || $value[0] == 'Placeholder Mechanism Code') continue;

            $county_name = explode(' ', $value[2])[0];
            $county_name = explode('-', $county_name)[0];

            $mech_id = ((int) $value[0]);

            $partner = Partner::where('mech_id', $mech_id)->first();
            $county = County::where('name', 'like', $county_name . '%')->first();

            if(!$partner){
                $value[10] = 'Partner Not Found';
                $value[11] = $key;
                $unidentified[] = $value;
                dd($value);
            }

            if(!$county){
                $value[10] = 'County Not Found';
                dd($value);
            }

            $locator = $data = ['county_id' => $county->id, 'partner_id' => $partner->id, 'financial_year' => 2021];

            if(Str::contains($value[3], 'Sexual')){
                $data['sexual_violence'] = $value[4];
            }else if(Str::contains($value[3], 'Physical')){
                $data['physical_emotional_violence'] = $value[4];
            }

            $this->insertRow($locator, $data);


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
