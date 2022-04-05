<?php

namespace App\Exports;

use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Http\Controllers\Controller;

use App\SurgeColumnView;
use App\Period;
use App\Lookup;

use DB;

class QuarterlyReportGBV implements FromArray, Responsable, WithHeadings, ShouldAutoSize
{
	use Exportable;
	// protected $writerType = Excel::CSV;
	protected $writerType = Excel::XLSX;
	protected $fileName;
	protected $reporting_period;
	protected $period;
	protected $periods_array;
    protected $filtered_periods;

    protected $sql;
    protected $excel_headings;

    protected $modalities;
    protected $ages;
    protected $gender;
    protected $partners;


    public function __construct($request)
    {
        $this->table_name = 'd_gender_based_violence';
        $financial_year = $request->input('financial_year', date('Y'));
        $quarter = $request->input('quarter', 1);
        $filtered_periods = $request->input('periods');

        $modalities = $request->input('modalities');
        $ages = $request->input('ages');
        $gender_id = $request->input('gender');
        $this->partners = $request->input('partners');

        $this->filtered_periods=false;

        if($filtered_periods){
            $this->filtered_periods=true;
            $this->periods_array = $filtered_periods;
            $periods = Period::whereIn('id', $filtered_periods)->get();
            $this->period = $periods->first();
            $this->fileName = 'GBV Monthly Report.xlsx';
        }
        else{
            $periods = Period::where(['financial_year' => $financial_year, 'quarter' => $quarter])->get();
            $this->period = $periods->first();
            $this->reporting_period = 'FY ' . $this->period->yr . ' Q' . $quarter;
            $this->fileName = $this->reporting_period . ' Quarterly Report.xlsx';
            $this->periods_array = $periods->pluck('id')->toArray();
        }
        
        $a = 'CIRG Reporting Period (FY & Q)';
        if($this->filtered_periods) $a = 'CIRG Reporting Period (FY & Month)';

        $sql = "name, facility_uid, mech_id, partnername, country, countyname";


        $excel_headings = ['Date', $a, 'ORG UNIT NAME (facility site, community site, or SNU)', 'ORG UNIT UID', 'MECHANISM ID', 'MECHANISM OR PARTNER NAME', 'OU', 'PSNU', ];

        if(!$modalities){
            $modalities = \App\SurgeModality::where(['tbl_name' => $this->table_name])
            ->where('modality', '!=', 'pep_number')
            ->get()->pluck('id')->toArray();
        }

        $columns = SurgeColumnView::
            when($modalities, function($query) use ($modalities){
                if(is_array($modalities)) return $query->whereIn('modality_id', $modalities);
                return $query->where('modality_id', $modalities);
            })->when($gender_id, function($query) use ($gender_id){
                return $query->where('gender_id', $gender_id);
            })->when($ages, function($query) use ($ages){
                if(is_array($ages)) return $query->whereIn('age_id', $ages);
                return $query->where('age_id', $ages);
            })
            ->orderBy('modality_id', 'desc')
            ->orderBy('age_name', 'asc')
            ->orderBy('gender_id', 'desc')
            ->orderBy('id', 'asc')
            ->get();

        foreach ($columns as $column) {
            $sql .= ", SUM(`{$column->column_name}`) AS `{$column->alias_name}`";

            if($column->modality == 'gbv_sexual') $modality = 'Violence Service Type: Sexual Violence';
            else if($column->modality == 'gbv_physical') $modality = 'Violence Service Type: Physical and/or Emotional Violence';
            else if($column->modality == 'pep_number') $modality = 'PEP: Taking PEP';
            else if($column->modality == 'completed_pep') $modality = 'PEP: Completed PEP';

            if($column->age == 'unknown') $age = 'Unknown Age';
            else if($column->age == 'above_50') $age = '50+';
            else{
                $age = $column->age_name;
            }

            $excel_headings[] = "GEND_GBV {$age} " . \Str::ucfirst($column->gender) . " {$modality} ";
        }
        $this->sql = $sql;
        $this->excel_headings = $excel_headings;
    }

    public function headings() : array
    {
        return $this->excel_headings;
    }

    public function  array(): array
    {
        $partners = $this->partners;
        if($this->filtered_periods) $actual_periods =  Period::whereIn('id', $this->periods_array)->get();

        $rows = DB::table($this->table_name)
        	->join('view_facilities', 'view_facilities.id', '=', "{$this->table_name}.facility")
            ->whereRaw(Lookup::get_active_partner_query($this->period->active_date))
            ->whereIn('period_id', $this->periods_array)
            ->when($partners, function($query) use($partners){
                return $query->whereIn('partner', $partners);
            })
            ->where(['funding_agency_id' => 1])
        	->selectRaw($this->sql)
            ->when($this->filtered_periods, function($query) {
                return $query->addSelect('period_id')->groupBy('period_id');
            })
        	->groupBy('view_facilities.id')
        	->get();

        $data = [];

        foreach ($rows as $row) {
            $reporting_period = $this->reporting_period;

            if($this->filtered_periods){
                $reporting_period = $actual_periods->where('id', $row->period_id)->first()->full_name ?? ' Period ';
            }

            $row = collect($row);
            $arr = $row->toArray();
            if($this->filtered_periods) array_pop($arr);
            array_unshift($arr, $reporting_period);
            array_unshift($arr, date('Y-m-d'));

            $data[] = $arr;
        }
        return $data;
    }
}
