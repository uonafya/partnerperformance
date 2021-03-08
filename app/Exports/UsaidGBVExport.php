<?php

namespace App\Exports;

use DB;
use App\Lookup;

class UsaidGBVExport extends BaseExport
{
	protected $table_name;
	protected $active_date;
	protected $period;

    function __construct($period, $modality=null)
    {
    	parent::__construct();
    	$this->table_name = 'd_gender_based_violence';
		$this->fileName = "USAID_GBV_data_FY_2020.xlsx";

		// $this->active_date = '2019-10-01';
		$this->period = $period;
		$this->active_date = $period->active_date;

		$modalities = \App\SurgeModality::where(['tbl_name' => $this->table_name])->when($modality, function($query) use($modality){
			return $query->where('id', $modality->id);
		})->get()->pluck('id')->toArray();

		$columns = \App\SurgeColumn::when($modalities, function($query) use ($modalities){
				if(is_array($modalities)) return $query->whereIn('modality_id', $modalities);
				return $query->where('modality_id', $modalities);
			})
			->orderBy('modality_id', 'asc')
			->orderBy('gender_id', 'desc')
			->orderBy('age_id', 'asc')
			->orderBy('id', 'asc')
			->get();

		$this->sql = "countyname as County, Subcounty, partnername AS Partner, facilitycode AS `MFL Code`, name AS `Facility`, financial_year AS `Financial Year`,  year AS `Calendar Year`, month AS `Month`, MONTHNAME(concat(year, '-', month, '-01')) AS `Month Name` ";

		foreach ($columns as $column) {
			$alias = $column->alias_name;
			$alias = str_replace('GBV - ', '', $alias);
			$this->sql .= ", `{$column->column_name}` AS `{$alias}`";
		}
    }

    public function headings() : array
    {
		$row = DB::table($this->table_name)
			->join('view_facilities', 'view_facilities.id', '=', $this->table_name . '.facility')
			->join('periods', 'periods.id', '=', $this->table_name . '.period_id')
			->selectRaw($this->sql)
			->where(['period_id' => $this->period->id, ])
			->whereNotNull('dateupdated')
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
			->where(['period_id' => $this->period->id, ])
			->whereNotNull('dateupdated')
			->whereRaw(Lookup::get_active_partner_query($this->active_date))
			->orderBy('period_id', 'asc')
			->orderBy('name', 'asc');
    }
}
