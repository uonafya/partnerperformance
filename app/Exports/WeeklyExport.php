<?php

namespace App\Exports;

use DB;

use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class WeeklyExport implements FromQuery, Responsable
{
	use Exportable;

	private $fileName;	
	private $writerType = Excel::XLSX;
	private $week_id;
	private $modality_name;
	private $gender_id;
	private $age_category_id;
	private $partner;

    function __construct($request)
    {
		$this->week_id = $request->input('week');
		$this->modality_name = $request->input('modality');
		$this->gender_id = $request->input('gender_id');
		$this->age_category_id = $request->input('age_category_id');
		$this->partner = auth()->user()->partner;

		$week = \App\Week::findOrFail($this->week_id);
		$this->fileName = $this->partner->download_name . '_' . $this->modality_name . '_for_' . $week->start_date . '_to_' . $week->end_date;
    }


    public function query()
    {
    	$gender_id = $this->gender_id;
    	$age_category_id = $this->age_category_id;

		$sql = "countyname as County, Subcounty,
		financial_year AS `Financial Year`, year AS `Calendar Year`, week_number as `Week Number`, 
		facilitycode AS `MFL Code`, name AS `Facility`,
		alias_name AS `Column Name`, value AS `Value`";

		return DB::table('d_weeklies')
			->join('view_facilitys', 'view_facilitys.id', '=', "{$table_name}.facility")
            ->join('weeks', 'weeks.id', '=', "d_weeklies.week_id")
			->join('surge_columns_view', "{$this->my_table}.column_id", '=', 'surge_columns_view.id')
			->selectRaw($sql)
			->where(['partner' => $this->partner->id, 'week_id' => $this->week_id, 'modality' => $this->modality_name])
			->when($age_category_id, function($query) use ($age_category_id){
				return $query->where('age_category_id', $age_category_id);
			})
			->when($gender_id, function($query) use ($gender_id){
				return $query->where('gender_id', $gender_id);
			})
			->orderBy('view_facilitys.name', 'asc')
			->orderBy('column_id', 'asc');
    }
}
