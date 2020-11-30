<?php

namespace App\Imports;

use DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use App\Exports\GenExport;

use App\Lookup;
use App\SurgeColumnView;

class AfyaImport implements ToCollection, WithHeadingRow
{
    private $partner;

    /*function __construct($partner)
    {
        $this->partner = $partner;
    }*/

    public function get_sum($columns, $name)
    {
        $sql = "(";

        foreach ($columns as $column) {
            $sql .= "SUM(`{$column->column_name}`) + ";
        }
        $sql = substr($sql, 0, -3);
        $sql .= ") AS {$name} ";
        return $sql;
    }

    public function surge_columns_callback($modality=true, $gender=true, $age=true)
    {
        $columns_query = Lookup::surge_columns_query($modality, $gender, $age);
        return function($query) use($columns_query){
            return $query->whereRaw($columns_query)
                ->orderBy('modality_id', 'asc')
                ->orderBy('gender_id', 'asc')
                ->orderBy('age_id', 'asc');
        };
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $mflcodes = [];
        $active_date = '2018-01-01';
        // $partner = $this->partner;

        foreach ($collection as $key => $row) {
            if(!in_array($row['mfl_code'], $mflcodes)) $mflcodes[] = $row['mfl_code'];
        }
        $facility_ids = DB::table('facilitys')->whereIn('facilitycode', $mflcodes)->get()->pluck('id')->toArray();

        $afya_facilities = DB::table('view_facilities')
            ->whereRaw("(start_of_support <= '{$active_date}' AND (end_of_support >= '{$active_date}' OR end_of_support IS NULL))")
            ->whereIn('id', $facility_ids)
            ->where('partner', '!=', 22)
            ->get();

        dd($afya_facilities);



        $gbv = SurgeColumnView::whereIn('modality', ['gbv_sexual', 'gbv_physical'])
            // ->when(true, $this->surge_columns_callback(false))
            ->get();

        $gbv_sql = $this->get_sum($gbv, 'gbv');


        $rows = DB::table('d_gender_based_violence')
            ->join('facilitys', 'facilitys.id', '=', 'd_gender_based_violence.facility')
            ->join('periods', 'periods.id', '=', 'd_gender_based_violence.period_id')
            ->selectRaw("facilitys.name, facilitycode, {$gbv_sql}")
            ->where('financial_year', 2020)
            ->where('dateupdated', '2020-11-30')
            ->whereIn('facility', $afya_facilities)
            // ->whereNotIn('facility', $facility_ids)
            ->groupBy('facility')
            ->having('gbv', '>', 0)
            ->get();

        $data = [];

        foreach ($rows as $key => $value) {
            
            $data[] = get_object_vars($value);
        }

        session(['download_rows' => $data]);



        // dd($rows);



        /*if(env('APP_ENV') != 'testing'){
            $facility_ids = DB::table('facilitys')->whereIn('facilitycode', $mflcodes)->get()->pluck('id');


            DB::table('supported_facilities')->whereIn('facility_id', $facility_ids)->update(['partner_id' => $partner]);

            DB::table('apidb.facilitys')->whereIn('facilitycode', $mflcodes)->update(['partner' => $partner]);
            DB::table('national_db.facilitys')->whereIn('facilitycode', $mflcodes)->update(['partner' => $partner]);
        }*/
    }
}
