<?php

namespace App\Exports;

use DB;

use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class SurgeExport implements FromQuery, Responsable
{
	use Exportable;

	private $fileName;
	private $week_id;
	private $modalities;
	private $gender;
	private $ages;
	private $partner;

  //   function __construct($request)
  //   {
		// $this->week_id = $request->input('week');
		// $this->modalities = $request->input('modalities');
		// $this->gender = $request->input('gender');
		// $this->ages = $request->input('ages');
		// $this->partner = auth()->user()->partner;

    function __construct()
    {
		$this->week_id = 35;
		$this->modalities = null;
		$this->gender = null;
		$this->ages = null;
		$this->partner = 55;

		$week = \App\Week::find($this->week_id);
		$this->fileName = str_replace(' ', '_', strtolower($this->partner->name)) . '_surge_data_for_' . $week->start_date . '_to_' . $week->end_date;
    }


    public function query()
    {
    	$modalities = $this->modalities;
    	$gender = $this->gender;
    	$ages = $this->ages;
    	$partner = $this->partner;

		$columns = \App\SurgeColumn::when(true, function($query) use ($modalities){
				if(is_array($modalities)) return $query->whereIn('modality_id', $modalities);
				return $query->where('modality_id', $modalities);
			})->when($gender, function($query) use ($gender){
				return $query->where('gender_id', $gender);
			})->when($ages, function($query) use ($ages){
				if(is_array($ages)) return $query->whereIn('age_id', $ages);
				return $query->where('age_id', $ages);
			})
			->orderBy('modality_id', 'asc')
			->orderBy('gender_id', 'asc')
			->orderBy('age_id', 'asc')
			->orderBy('id', 'asc')
			->get();

		$sql = "countyname as County, Subcounty, facilitycode AS `MFL Code`, name AS `Facility`, financial_year AS `Financial Year`, week_number as `Week Number`";

		foreach ($columns as $column) {
			$sql .= ", `{$column->column_name}` AS `{$column->alias_name}`";
		}

		$facilities = \AppFacility::select('id')->where(['is_surge' => 1, 'partner' => $partner->id])->get()->pluck('id')->toArray();
		
		return DB::table('d_surge')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_surge.facility')
			->join('weeks', 'weeks.id', '=', 'd_surge.week_id')
			->selectRaw($sql)
			->where('week_id', $week_id)
			->where('partner', $partner->id)
			->when($facilities, function($query) use ($facilities){
				return $query->whereIn('view_facilitys.id', $facilities);
			})
			->orderBy('name', 'asc');
    }
}
