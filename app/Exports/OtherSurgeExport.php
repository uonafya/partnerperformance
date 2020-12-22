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
        $sql .= ") AS `{$name}` ";
        return $sql;
    }

    function __construct($week)
    {
    	parent::__construct();

    	$this->week = $week;

		// $this->fileName = 'USAID_surge_data_for_' . $this->week->start_date . '_to_' . $this->week->end_date . '.xlsx';

		$tested = SurgeColumnView::where(['hts' => 1])->where('column_name', 'like', '%tested%')->get();
		$pos = SurgeColumnView::where(['hts' => 1])->where('column_name', 'like', '%pos%')->get();
		$tx_new = SurgeColumnView::where(['modality' => 'tx_new'])->get();
		$pmtct = SurgeColumnView::whereIn('modality', ['pmtct_anc1', 'pmtct_post_anc'])->get();

		$this->sql = "countyname as County, Subcounty, facilitycode AS `MFL Code`, partnername AS Partner, name AS `Facility`, financial_year AS `Financial Year`, week_number, start_date, end_date,  " . $this->get_sum($tested, 'HTS_Tested') . ', ' . $this->get_sum($pos, 'HTS_Positive') . ', ' . $this->get_sum($tx_new, 'tx_new') . ', ' . $this->get_sum($pmtct, 'pmtct');
		// $this->sql = "facility, financial_year AS `Financial Year`, " . $this->get_sum($tested, 'HTS_Tested') . ', ' . $this->get_sum($pos, 'HTS_Positive') . ', ' . $this->get_sum($tx_new, 'tx_new') . ', ' . $this->get_sum($pmtct, 'pmtct');

    }

    public function headings() : array
    {
		/*$row = DB::table('d_surge')
			->join('view_facilities', 'view_facilities.id', '=', 'd_surge.facility')
			->join('weeks', 'weeks.id', '=', 'd_surge.week_id')
			->selectRaw($this->sql)
			->where(['week_number' => 5])
			->whereRaw(Lookup::get_active_partner_query('2020-01-01'))
			->first();

		return collect($row)->keys()->all();*/

		return ['County', 'Subcounty', 'MFL Code', 'Partner', 'Facility', 'Financial Year', 'Week Number', 'Start Date', 'End Date', 'HTS Tested', 'HTS Positive', 'TX NEW', 'PMTCT'];
    }


    public function query()
    {
		return DB::table('d_surge')
			->join('view_facilities', 'view_facilities.id', '=', 'd_surge.facility')
			->join('weeks', 'weeks.id', '=', 'd_surge.week_id')
			->selectRaw($this->sql)
			// ->where(['financial_year' => 2020, ])
			->where(['financial_year' => 2020, 'funding_agency_id' => 1, 'is_surge' => 1, 'week_id' => $this->week->id ])
			// ->whereRaw(Lookup::get_active_partner_query('2020-01-01'))
			->groupBy('d_surge.facility')
			// ->orderBy('name', 'asc');
			->orderBy('d_surge.facility', 'asc');
    }
}
