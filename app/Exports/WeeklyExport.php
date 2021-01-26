<?php

namespace App\Exports;

use DB;

use App\Lookup;

class WeeklyExport extends BaseExport
{
	protected $week;
	protected $modality_id;
	protected $table_name;
	protected $gender_id;
	protected $age_category_id;

    function __construct($request)
    {
    	parent::__construct();
		$modality = $request->input('modality');
		$m = \App\SurgeModality::where(['modality' => $modality])->first();
		$this->modality_id = $m->id;
		$this->table_name = $m->tbl_name;
		$this->gender_id = $request->input('gender_id');
		$this->age_category_id = $request->input('age_category_id');

		$this->week = \App\Week::findOrFail($request->input('week_id'));
		$this->fileName = $this->partner->download_name . '_' . $modality . '_for_' . $this->week->start_date . '_to_' . $this->week->end_date . '.xlsx';

		$this->sql = "countyname as County, Subcounty,
		financial_year AS `Financial Year`, year AS `Calendar Year`, week_number as `Week Number`, start_date AS `Start Date`,
		facilitycode AS `MFL Code`, name AS `Facility`,
		alias_name AS `Column Name`, value AS `Value`";
    }

    public function headings() : array
    {
		$row = DB::table($this->table_name)
			->join('view_facilities', 'view_facilities.id', '=', "{$this->table_name}.facility")
            ->join('weeks', 'weeks.id', '=', "{$this->table_name}.week_id")
			->join('surge_columns_view', "{$this->table_name}.column_id", '=', 'surge_columns_view.id')
			->selectRaw($this->sql)
			->where(['partner' => $this->partner->id, 'week_id' => $this->week->id, 'modality_id' => $this->modality_id])
			->whereRaw(Lookup::get_active_partner_query($this->week->start_date))
			->first();

		return collect($row)->keys()->all();
    }


    public function query()
    {
    	$gender_id = $this->gender_id;
    	$age_category_id = $this->age_category_id;

		return DB::table($this->table_name)
			->join('view_facilities', 'view_facilities.id', '=', "{$this->table_name}.facility")
            ->join('weeks', 'weeks.id', '=', "{$this->table_name}.week_id")
			->join('surge_columns_view', "{$this->table_name}.column_id", '=', 'surge_columns_view.id')
			->selectRaw($this->sql)
			->where(['partner' => $this->partner->id, 'week_id' => $this->week->id, 'modality_id' => $this->modality_id])
			->when($age_category_id, function($query) use ($age_category_id){
				return $query->where('age_category_id', $age_category_id);
			})
			->when($gender_id, function($query) use ($gender_id){
				return $query->where('gender_id', $gender_id);
			})
			->whereRaw(Lookup::get_active_partner_query($this->week->start_date))
			->orderBy('view_facilities.name', 'asc')
			->orderBy('column_id', 'asc');
    }
}
