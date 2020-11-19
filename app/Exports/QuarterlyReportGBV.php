<?php

namespace App\Exports;

use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Http\Controllers\Controller;

use App\SurgeColumnView;
use App\Period;
use App\Lookup;

use DB;

class QuarterlyReportGBV implements FromArray, Responsable, WithHeadings
{
	use Exportable;
	// protected $writerType = Excel::CSV;
	protected $writerType = Excel::XLSX;
	protected $fileName;
	protected $reporting_period;
	protected $period;
	protected $periods_array;
	private $my_table = 'd_gender_based_violence';


    public function __construct($request)
    {
        $this->fileName = 'download.xlsx';
        $financial_year = $request->input('financial_year', date('Y'));
        $quarter = $request->input('quarter', 1);

        $periods = Period::where(['financial_year' => $financial_year, 'quarter' => $quarter])->get();
        $this->period = $periods->first();
        $this->reporting_period = 'FY ' . $this->period->yr . ' Q' . $quarter;
        $this->periods_array = $periods->pluck('id')->toArray();
    }

    public function headings() : array
    {
    	return ['Date', 'Reporting Period (FY & Q)', 'Mechanism ID', 'Partner Name', 'OU', 'SNU', 'Age Band', 'Sex', 'Violence Type & PEP Completion', 'Target'];
    }


    public function  array(): array
    {
		$gbv = SurgeColumnView::whereIn('modality', ['gbv_sexual', 'gbv_physical', 'pep_number', 'completed_pep'])
			->orderBy('modality_id', 'ASC')
			->orderBy('gender_id', 'DESC')
			->orderBy('max_age', 'ASC')
			->get();

		$sql = '';

		foreach ($gbv as $key => $column) {
			$sql .= "SUM(`{$column->column_name}`) AS `{$column->column_name}`, ";
		}
        $sql = substr($sql, 0, -2);

        $rows = DB::table($this->my_table)
        	->join('view_facilities', 'view_facilities.id', '=', "{$this->my_table}.facility")
            ->whereRaw(Lookup::get_active_partner_query($this->period->active_date))
            ->whereIn('period_id', $this->periods_array)
            ->where(['funding_agency_id' => 1])
        	->selectRaw($sql)
        	->addSelect('partnername', 'mech_id', 'countyname')
        	->groupBy('partner', 'county')
        	->get();

        $data = [];

        foreach ($rows as $row) {
        	foreach ($gbv as $column) {
        		$column_name = $column->column_name;
        		$data[] = [
        			date('Y-m-d'),
        			$this->reporting_period,
        			$row->mech_id,
        			$row->partnername,
        			'Kenya',
        			$row->countyname . ' County',
        			$column->age_name,
        			$column->gender,
        			$column->modality_name,
        			$row->$column_name,
        			'',
        		];
        	}
        }
        return $data;
    }
}