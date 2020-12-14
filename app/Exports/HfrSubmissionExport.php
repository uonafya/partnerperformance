<?php

namespace App\Exports;

use DB;
use \App\Week;
use \App\HfrSubmission;
use \App\Lookup;

class HfrSubmissionExport extends BaseExport
{
	protected $week;
	protected $excel_headings;


    function __construct($request)
    {
    	parent::__construct();
        $this->week = Week::find($request->input('week_id'));
        $this->table_name = 'd_hfr_submission';


		$this->fileName = $this->partner->download_name . '_hfr_submission_for_' . $this->week->start_date . '_to_' . $this->week->end_date . '.xlsx';


		/*$sql = "countyname as County, Subcounty,
		facilitycode AS `MFL Code`, name AS `Facility`,
		financial_year AS `Financial Year`, year AS `Calendar Year`, month AS `Month`, 
		MONTHNAME(concat(year, '-', month, '-01')) AS `Month Name` ";

		$excel_headings = ['County', 'Subcounty', 'MFL Code', 'Facility', 'Financial Year', 'Calendar Year', 'Month', 'Month Name'];*/

		$sql = "start_date, name, facility_uid, mech_id, country, countyname, financial_year, week_number, facilitycode ";

		$excel_headings = ["HFR Month/Week Start Date", 'Facility or Community Name', 'Facility OR Community UID', 'Mechanism ID', 'OU', 'PSNU', 'Financial Year', 'Week Number', 'MFL Code'];

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
			->join('weeks', 'weeks.id', '=', $this->table_name . '.week_id')
            ->whereRaw(Lookup::get_active_partner_query($this->week->start_date))
			->selectRaw($this->sql)
			->where(['partner' => $this->partner->id, 'week_id' => $this->week->id])
			->orderBy('name', 'asc')
			->orderBy("{$this->table_name}.id", 'asc');
    }
}
