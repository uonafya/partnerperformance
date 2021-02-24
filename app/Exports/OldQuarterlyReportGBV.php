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

class OldQuarterlyReportGBV implements FromArray, Responsable, WithHeadings, ShouldAutoSize
{
	use Exportable;
	// protected $writerType = Excel::CSV;
	protected $writerType = Excel::XLSX;
	protected $fileName;
	protected $reporting_period;
	protected $period;
	protected $periods_array;
    protected $filtered_periods;

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

        $this->modalities = $request->input('modalities');
        $this->ages = $request->input('ages');
        $this->gender = $request->input('gender');
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
    }

    public function headings() : array
    {
        $a = 'Reporting Period (FY & Q)';
        if($this->filtered_periods) $a = 'Reporting Period (FY & Month)';
    	return ['Date', $a, 'Mechanism ID', 'Partner Name', 'OU', 'SNU', 'Age Band', 'Sex', 'Violence Type & PEP Completion', 'Results', 'Target'];
    }


    public function  array(): array
    {
        $modalities = $this->modalities;
        $ages = $this->ages;
        $gender = $this->gender;
        $partners = $this->partners;
        $periods_array = $this->periods_array;

		$gbv = SurgeColumnView::whereIn('modality', ['gbv_sexual', 'gbv_physical', 'pep_number', 'completed_pep'])
            ->when($modalities, function($query) use($modalities){
                return $query->whereIn('modality_id', $modalities);
            })
            ->when($ages, function($query) use($ages){
                return $query->whereIn('age_id', $ages);
            })
            ->when($gender, function($query) use($gender){
                return $query->where('gender_id', $gender);
            })
            ->orderBy('modality_id', 'ASC')
            ->orderBy('gender_id', 'DESC')
            ->orderBy('max_age', 'ASC')
			->get();

        if($this->filtered_periods){
            $actual_periods =  Period::whereIn('id', $this->periods_array)->get();
        }

		$sql = '';

		foreach ($gbv as $key => $column) {
            $alias = $column->column_name;
            // $alias = str_replace('gbv_', '', $alias);
			$sql .= "SUM(`{$column->column_name}`) AS `{$alias}`, ";
		}
        $sql = substr($sql, 0, -2);

        $rows = DB::table($this->table_name)
        	->join('view_facilities', 'view_facilities.id', '=', "{$this->table_name}.facility")
            ->whereRaw(Lookup::get_active_partner_query($this->period->active_date))
            ->whereIn('period_id', $this->periods_array)
            ->when($partners, function($query) use($partners){
                return $query->whereIn('partner', $partners);
            })
            ->where(['funding_agency_id' => 1])
        	->selectRaw($sql)
        	->addSelect('partnername', 'mech_id', 'countyname')
            ->when($this->filtered_periods, function($query) {
                return $query->addSelect('period_id')->groupBy('period_id');
            })
        	->groupBy('partner', 'county')
        	->get();

        $data = [];

        foreach ($rows as $row) {
            $reporting_period = $this->reporting_period;

            if($this->filtered_periods){
                $reporting_period = $actual_periods->where('id', $row->period_id)->first()->full_name ?? ' Period ';
            }

        	foreach ($gbv as $column) {
        		$column_name = $column->column_name;
                $results = $row->$column_name ?? '0';
                $modality_name = str_replace('GBV - ', '', $column->modality_name);
                // if(!is_integer($results)) $results = 0;
        		$data[] = [
        			date('Y-m-d'),
        			$reporting_period,
        			$row->mech_id,
        			$row->partnername,
        			'Kenya',
        			$row->countyname . ' County',
        			$column->age_name,
        			\Str::ucfirst($column->gender),
        			$modality_name,
        			"$results",
        			'',
        		];
        	}
        }
        // dd($data);
        return $data;
    }
}
