<?php

namespace App\Exports;

use DB;
use \App\Period;
use \App\HfrSubmission;
use \App\Lookup;

class HfrSubmissionExport extends BaseExport
{
	protected $period;
	protected $excel_headings;


    function __construct($request)
    {
    	parent::__construct();
        $this->period = Period::find($request->input('period_id'));
        $this->table_name = 'd_hfr_submission';


		$this->fileName = $this->partner->download_name . '_FY_' . $this->period->yr . '_' . $this->period->month_name . '_hfr_submission.xlsx';


		$sql = "countyname as County, Subcounty,
		facilitycode AS `MFL Code`, name AS `Facility`,
		financial_year AS `Financial Year`, year AS `Calendar Year`, month AS `Month`, 
		MONTHNAME(concat(year, '-', month, '-01')) AS `Month Name` ";

		$excel_headings = ['County', 'Subcounty', 'MFL Code', 'Facility', 'Financial Year', 'Calendar Year', 'Month', 'Month Name'];

		$columns = HfrSubmission::columns();

		foreach ($columns as $key => $column) {
			$sql .= ', ' . $column['column_name'];
			$excel_headings[] = $column['excel_name'];
		}

		$this->sql = $sql;
		$this->excel_headings = $excel_headings;
    }

    public function headings() : array
    {
    	return $this->excel_headings;
    }


    public function query()
    {		
		return DB::table($this->table_name)
        	->join('view_facilities', 'view_facilities.id', '=', "{$this->table_name}.facility")
			->join('periods', 'periods.id', '=', $this->table_name . '.period_id')
            ->whereRaw(Lookup::get_active_partner_query($this->period->active_date))
			->selectRaw($this->sql)
			->where(['partner' => $this->partner->id, 'period_id' => $this->period->id])
			->orderBy('name', 'asc')
			->orderBy("{$this->table_name}.id", 'asc');
    }
}
