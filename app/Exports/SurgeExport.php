<?php

namespace App\Exports;

use DB;

use App\Lookup;

class SurgeExport extends BaseExport
{
	protected $week;
	protected $modalities;
	protected $gender_id;
	protected $ages;

    function __construct($request)
    {
    	parent::__construct();
		$this->modalities = $request->input('modalities');
		$this->gender_id = $request->input('gender_id');
		$this->ages = $request->input('ages');

    // function __construct()
  //   {
		// $this->week_id = 35;
		// $this->modalities = [1,2];
		// $this->gender_id = null;
		// $this->ages = null;
		// $this->partner = \App\Partner::find(55);


		$this->week = \App\Week::findOrFail($request->input('week_id'));
		$this->fileName = $this->partner->download_name . '_surge_data_for_' . $this->week->start_date . '_to_' . $this->week->end_date . '.xlsx';


    	$modalities = $this->modalities;
    	$gender_id = $this->gender_id;
    	$ages = $this->ages;
    	$partner = $this->partner;

		$columns = \App\SurgeColumn::when(true, function($query) use ($modalities){
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
			->orderBy('age_id', 'asc')
			->orderBy('id', 'asc')
			->get();

		$sql = "countyname as County, Subcounty, facilitycode AS `MFL Code`, name AS `Facility`, financial_year AS `Financial Year`, week_number as `Week Number`, start_date AS `Start Date`";

		foreach ($columns as $column) {
			$sql .= ", `{$column->column_name}` AS `{$column->alias_name}`";
		}
		$this->sql = $sql;
    }

    public function headings() : array
    {
		$row = DB::table('d_surge')
			->join('view_facilities', 'view_facilities.id', '=', 'd_surge.facility')
			->join('weeks', 'weeks.id', '=', 'd_surge.week_id')
			->selectRaw($this->sql)
			->where('week_id', $this->week->id)
			->where('partner', $this->partner->id)
			->whereRaw(Lookup::get_active_partner_query($this->week->start_date))
			->first();

		return collect($row)->keys()->all();
    }


    public function query()
    {
		$facilities = \App\Facility::select('id')->where(['is_surge' => 1, 'partner' => $this->partner->id])->get()->pluck('id')->toArray();
		
		return DB::table('d_surge')
			->join('view_facilities', 'view_facilities.id', '=', 'd_surge.facility')
			->join('weeks', 'weeks.id', '=', 'd_surge.week_id')
			->selectRaw($this->sql)
			->where(['week_id' => $this->week->id, 'partner' => $this->partner->id])
			->when($facilities, function($query) use ($facilities){
				return $query->whereIn('view_facilities.id', $facilities);
			})
			->whereRaw(Lookup::get_active_partner_query($this->week->start_date))
			->orderBy('name', 'asc');
    }
}
