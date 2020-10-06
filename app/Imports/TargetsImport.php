<?php

namespace App\Imports;

use App\Facility;
use App\Ward;
use DB;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Str;

class TargetsImport implements OnEachRow, WithHeadingRow
{

    private $table_name;
    private $financial_year;

    function __construct()
    {
    	$this->table_name = 't_facility_target';
    	$financial_year = date('Y');
		// if(date('m') > 9) $financial_year++;
		$this->financial_year = $financial_year;
    }


    public function onRow(Row $row)
    {
    	$row = json_decode(json_encode($row->toArray()));
        $table_name = $this->table_name;
        $column_name = 'facility';

        $site_name = strtolower($row->site_name);

        if(Str::contains($site_name, ['ward'])){
            $table_name = 't_ward_target';
            $column_name = 'ward_id';
            $compass = Str::contains($site_name, ['east', 'west', 'north', 'south', 'central']);
            $a = explode(' ', $site_name);
            $compass_direction = null;
            if($compass){
                foreach ($a as $value) {
                    if(in_array($value, ['east', 'west', 'north', 'south', 'central'])){
                        $compass_direction = $value;
                        break;
                    }
                }
            }
            $fac = Ward::where('name', 'like', $a[0] . '%')
                ->when($compass_direction, function($query) use($compass_direction){
                    return $query->where('name', 'like', "%{$compass_direction}%");
                })
                ->first();
            if(!$fac) return;
        }else{
            return;
            if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) return;

            $fac = Facility::where('facilitycode', $row->mfl_code)->first();
            if(!$fac) return;
        }

        $update_data = [];
        $columns = ['gbv', 'pep', 'physical_emotional_violence', 'sexual_violence_post_rape_care', 'total_gender_gbv'];

        foreach ($columns as $column) {
            if(isset($row->$column) && is_numeric($row->$column)) $update_data[$column] = $row->$column;
        }        

		if(env('APP_ENV') != 'testing') {
			DB::connection('mysql_wr')->table($table_name)
			->where([$column_name => $fac->id, 'financial_year' => $this->financial_year ])
			->update($update_data);
            // ->update(['gbv' => $row->gbv]);
		}
	}
}
