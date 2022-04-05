<?php

namespace App\Exports;

use DB;
use App\Lookup;

class GBVExport extends BaseExport
{
	protected $table_name;
	protected $period_id;
	protected $gender_id;
	protected $ages;
	protected $active_date;

    function __construct($request)
    {
    	parent::__construct();
    	$this->table_name = 'd_gender_based_violence';
		$this->period_id = $request->input('period_id');
		$modalities = $request->input('modalities');
		$this->gender_id = $request->input('gender_id');
		$ages = $request->input('ages');


		$period = \App\Period::findOrFail($this->period_id);
		$this->fileName = "{$this->partner->download_name}_gbv_data_FY_{$period->yr}_month_{$period->month_name}.xlsx";

		/*$y = $this->financial_year;
		$m = $this->month;
		if($month > 9) $y--;
		$this->active_date = "{$period->year}-{$period->month}-01";*/

		$this->active_date = $period->active_date;

		if(!$modalities) $modalities = \App\SurgeModality::where(['tbl_name' => $this->table_name])->get()->pluck('id')->toArray();
    	// $modalities = $this->modalities;
    	$gender_id = $this->gender_id;
    	$partner = $this->partner;
    	$period_id = $this->period_id;

		$columns = \App\SurgeColumnView::when($modalities, function($query) use ($modalities){
				if(is_array($modalities)) return $query->whereIn('modality_id', $modalities);
				return $query->where('modality_id', $modalities);
			})->when($gender_id, function($query) use ($gender_id){
				return $query->where('gender_id', $gender_id);
			})->when($ages, function($query) use ($ages){
				if(is_array($ages)) return $query->whereIn('age_id', $ages);
				return $query->where('age_id', $ages);
			})
			->orderBy('modality_id', 'asc')
			->orderBy('gender_id', 'asc')
			// ->orderBy('max_age', 'asc')
			->orderBy('id', 'asc')
			->get();

		$sql = "countyname as County, Subcounty, facilitycode AS `MFL Code`, name AS `Facility`, financial_year AS `Financial Year`,  year AS `Calendar Year`, month AS `Month`, MONTHNAME(concat(year, '-', month, '-01')) AS `Month Name` ";

		foreach ($columns as $column) {
			$sql .= ", `{$column->column_name}` AS `{$column->alias_name}`";
		}
		$this->sql = $sql;
    }

    public function headings() : array
    {
		$row = DB::table($this->table_name)
			->join('view_facilities', 'view_facilities.id', '=', $this->table_name . '.facility')
			->join('periods', 'periods.id', '=', $this->table_name . '.period_id')
			->selectRaw($this->sql)
			->where(['period_id' => $this->period_id, 'partner' => $this->partner->id])
			->whereRaw(Lookup::get_active_partner_query($this->active_date))
			->first();

		return collect($row)->keys()->all();
    }


    public function query()
    {
		return DB::table($this->table_name)
			->join('view_facilities', 'view_facilities.id', '=', $this->table_name . '.facility')
			->join('periods', 'periods.id', '=', $this->table_name . '.period_id')
			->selectRaw($this->sql)
			->where(['period_id' => $this->period_id, 'partner' => $this->partner->id])
			->whereRaw(Lookup::get_active_partner_query($this->active_date))
			->orderBy('name', 'asc');
    }
}
