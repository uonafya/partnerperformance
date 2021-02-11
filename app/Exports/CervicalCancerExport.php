<?php

namespace App\Exports;

use DB;

use App\Lookup;

class CervicalCancerExport extends BaseExport
{
	protected $table_name;
	protected $period_id;
	protected $period;
	protected $modalities;


    function __construct($request)
    {
    	parent::__construct();
    	$this->table_name = 'd_cervical_cancer';
		$this->modalities = $request->input('modalities');
    	$period_id = $this->period_id;

		$this->period = \App\Period::findOrFail($request->input('period_id'));
		$this->fileName = "{$this->partner->download_name}_cervical_cancer_data_for_FY_{$period->yr}_month_{$period->month_name}.xlsx";

		$columns = \App\SurgeColumn::when(true, function($query) use ($modalities){
				if(is_array($modalities)) return $query->whereIn('modality_id', $modalities);
				return $query->where('modality_id', $modalities);
			})
			// ->orderBy('modality_id', 'asc')
			// ->orderBy('age_id', 'asc')
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
