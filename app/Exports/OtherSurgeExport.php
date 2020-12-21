<?php

namespace App\Exports;

use DB;

use App\SurgeColumn;
use App\SurgeColumnView;
use App\Lookup;

class OtherSurgeExport extends BaseExport
{
	protected $week;
	protected $modalities;
	protected $gender_id;
	protected $ages;

    public function get_sum($columns, $name)
    {
        $sql = "(";

        foreach ($columns as $column) {
            $sql .= "SUM(`{$column->column_name}`) + ";
        }
        $sql = substr($sql, 0, -3);
        $sql .= ") AS {$name} ";
        return $sql;
    }

    function __construct()
    {
    	parent::__construct();

		// $this->fileName = 'USAID_surge_data_for_' . $this->week->start_date . '_to_' . $this->week->end_date . '.xlsx';

		$hts = SurgeColumnView::where(['hts' => 1])->get();
		$tx_new = SurgeColumnView::where(['modality' => 'tx_new'])->get();
		$pmtct = SurgeColumnView::whereIn('modality', ['pmtct_anc1', 'pmtct_post_anc'])->get();

		// $this->sql = "countyname as County, Subcounty, facilitycode AS `MFL Code`, partnername AS Partner, name AS `Facility`, financial_year AS `Financial Year`, week_number as `Week Number`, " . $this->get_sum($hts, 'hts') . ', ' . $this->get_sum($tx_new, 'tx_new') . ', ' . $this->get_sum($pmtct, 'pmtct');
		$this->sql = "countyname as County, Subcounty, facilitycode AS `MFL Code`, partnername AS Partner, name AS `Facility`, financial_year AS `Financial Year`, week_number as `Week Number`, " . $this->get_sum($hts, 'hts');

    }

    public function headings() : array
    {
		$row = DB::table('d_surge')
			->join('view_facilities', 'view_facilities.id', '=', 'd_surge.facility')
			->join('weeks', 'weeks.id', '=', 'd_surge.week_id')
			->selectRaw($this->sql)
			->where(['week_id' => 0])
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
			->where(['financial_year' => 2020, 'funding_agency_id' => 1, ])
			->whereRaw(Lookup::get_active_partner_query('2020-01-01'))
			->groupBy('d_surge.facility')
			->orderBy('name', 'asc');
    }
}
