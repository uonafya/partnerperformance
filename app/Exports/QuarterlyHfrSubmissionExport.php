<?php

namespace App\Exports;

use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\HfrSubmission;
use App\Week;
use App\Lookup;

use DB;


// class QuarterlyHfrSubmissionExport extends BaseExport
class QuarterlyHfrSubmissionExport implements FromArray, Responsable, WithHeadings, ShouldAutoSize
{
	use Exportable;
	// protected $writerType = Excel::CSV;
	protected $writerType = Excel::XLSX;
	protected $fileName;
	protected $reporting_week;
	protected $week;
	protected $weeks_array;
    protected $filtered_weeks;

    protected $sql;
    protected $excel_headings;
    protected $modalities;
    protected $ages;
    protected $gender;
    protected $partners;


    function __construct($request)
    {
        $this->table_name = 'd_hfr_submission';
        $financial_year = $request->input('financial_year', date('Y'));
        $quarter = $request->input('quarter', 1);
        $filtered_weeks = $request->input('weeks');

        $this->modalities = $request->input('modalities');
        $this->ages = $request->input('ages');
        $this->gender = $request->input('gender');
        $this->partners = $request->input('partners');

        $this->filtered_weeks=false;

        if($filtered_weeks){
            $this->filtered_weeks=true;
            $this->weeks_array = $filtered_weeks;
            $weeks = Week::whereIn('id', $filtered_weeks)->get();
            $this->week = $weeks->first();
            $this->fileName = 'HFR Weekly Report.xlsx';
        }
        else{
            $weeks = Week::where(['financial_year' => $financial_year, 'quarter' => $quarter])->get();
            // $this->week = $weeks->first();
            $this->week = Week::where(['financial_year' => $financial_year, 'quarter' => $quarter])->orderBy('')->first();
            $this->reporting_week = 'FY ' . $this->week->yr . ' Q' . $quarter;
            $this->fileName = $this->reporting_week . ' HFR Quarterly Report.xlsx';
            $this->weeks_array = $weeks->pluck('id')->toArray();

        }

		$sql = "week_id, name, facility_uid, mech_id, country, countyname ";

		$excel_headings = ["HFR Month/Week Start Date", 'Facility or Community Name', 'Facility OR Community UID', 'Mechanism ID', 'OU', 'PSNU', ];

		$columns = HfrSubmission::columns();

		foreach ($columns as $key => $column) {
			$sql .= ', ' . $column['column_name'];
			$excel_headings[] = $column['quarterly_name'];
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
        $modalities = $this->modalities;
        $ages = $this->ages;
        $gender = $this->gender;
        $partners = $this->partners;
        $weeks_array = $this->weeks_array;
        $start_date = $this->week->start_date;

        $columns = HfrSubmission::columns(); 

        if($this->filtered_weeks){
            $actual_weeks =  Week::whereIn('id', $this->weeks_array)->get();
        }

		$sql = '';

		foreach ($columns as $key => $column) {
            $alias = $column['column_name'];
			$sql .= "SUM(`{$column['column_name']}`) AS `{$alias}`, ";
		}
        $sql = substr($sql, 0, -2);

        $rows = DB::table($this->table_name)
        	->join('view_facilities', 'view_facilities.id', '=', "{$this->table_name}.facility")
            ->whereRaw(Lookup::get_active_partner_query($this->week->start_date))
            ->whereIn('week_id', $this->weeks_array)
            ->when($partners, function($query) use($partners){
                return $query->whereIn('partner', $partners);
            })
            ->where(['funding_agency_id' => 1])
        	->selectRaw($sql)
        	->addSelect('partnername', 'mech_id', 'countyname', 'name', 'facility_uid')
            ->when($this->filtered_weeks, function($query) {
                return $query->addSelect('week_id')->groupBy('week_id');
            })
        	// ->groupBy('partner', 'county')
            ->groupBy("{$this->table_name}.facility")
        	->get();

        $data = [];

        foreach ($rows as $row) {
            $reporting_week = $this->reporting_week;

            if($this->filtered_weeks){
                $reporting_week = $actual_weeks->where('id', $row->week_id)->first()->name ?? ' Period ';
                $start_date = $actual_weeks->where('id', $row->week_id)->first()->start_date ?? $start_date;
            }

            $data[] = $row->toArray();
        }
        return $data;
    }

    public function query()
    {		
		return DB::table($this->table_name)
        	->join('view_facilities', 'view_facilities.id', '=', "{$this->table_name}.facility")
			->join('weeks', 'weeks.id', '=', $this->table_name . '.week_id')
            ->whereRaw(Lookup::get_active_partner_query($this->week->start_date))
			->selectRaw($this->sql)
			->where(['partner' => $this->partner->id, 'week_id' => $this->week->id])
			->orderBy('name', 'asc')
			->orderBy("{$this->table_name}.id", 'asc');
    }
}
