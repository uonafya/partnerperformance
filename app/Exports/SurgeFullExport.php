<?php

namespace App\Exports;

use DB;

use App\SurgeColumn;
use App\SurgeColumnView;
use App\Lookup;

class SurgeFullExport extends BaseExport
{
	protected $week;
	protected $modalities;
	protected $gender_id;
	protected $ages;

    function __construct($week, $modality)
    {
    	parent::__construct();

    	$this->week = $week;

		$columns = SurgeColumnView::where(['modality_id' => $modality->id])->get();

		$this->sql = "countyname as County, Subcounty, facilitycode AS `MFL Code`, partnername AS Partner, name AS `Facility`, start_date, end_date";

		foreach ($columns as $column) {
			$alias = $column->alias_name;
			$alias = str_replace('GBV - ', '', $alias);
			$this->sql .= ", `{$column->column_name}` AS `{$alias}`";
		}
    }

    public function headings() : array
    {
		$row = DB::table('d_surge')
			->join('view_facilities', 'view_facilities.id', '=', 'd_surge.facility')
			->join('weeks', 'weeks.id', '=', 'd_surge.week_id')
			->selectRaw($this->sql)
			// ->where(['week_number' => 5])
			->whereRaw(Lookup::get_active_partner_query('2020-01-01'))
			->first();

		return collect($row)->keys()->all();
    }


    public function query()
    {
		return DB::table('d_surge')
			->join('view_facilities', 'view_facilities.id', '=', 'd_surge.facility')
			->join('weeks', 'weeks.id', '=', 'd_surge.week_id')
			->selectRaw($this->sql)
			->where(['funding_agency_id' => 1, 'is_surge' => 1, 'week_id' => $this->week->id ])
			->whereRaw(Lookup::get_active_partner_query('2020-01-01'))
			->groupBy('d_surge.facility')
			// ->orderBy('name', 'asc');
			->orderBy('d_surge.facility', 'asc');
    }
}
