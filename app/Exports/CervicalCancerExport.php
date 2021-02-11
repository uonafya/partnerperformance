<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

use App\Lookup;

class CervicalCancerExport extends BaseExport implements ShouldAutoSize
{
	protected $table_name;
	protected $period_id;
	protected $period;
	protected $modalities;
	protected $active_date;


    function __construct($request)
    {
    	parent::__construct();
    	$this->table_name = 'd_cervical_cancer';
		$modalities = $request->input('modalities');
		$this->period_id = $request->input('period_id');

		$period = \App\Period::findOrFail($request->input('period_id'));
		$this->active_date = $period->active_date;
		$this->modalities = $modalities;
		$this->fileName = "{$this->partner->download_name}_cervical_cancer_data_for_FY_{$period->yr}_month_{$period->month_name}.xlsx";

		$columns = \App\SurgeColumnView::when($modalities, function($query) use ($modalities){
				if(is_array($modalities)) return $query->whereIn('modality_id', $modalities);
				return $query->where('modality_id', $modalities);
			})
			->where(['tbl_name' => $this->table_name])
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
