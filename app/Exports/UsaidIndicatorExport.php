<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMapping;

use DB;
use \App\Lookup;

class UsaidIndicatorExport extends BaseExport implements WithMapping
{
	protected $period;
	protected $counties_array;


    function __construct($period, $column = null, $alias = null)
    {
    	parent::__construct();
    	$this->period = $period;
		// $this->fileName = $this->partner->download_name . '_FY_' . $this->financial_year . '_early_warning_indicators' . '.xlsx';

		// $this->counties_array = DB::table('view_facilitys')->select('county')->groupBy('county')->get()->pluck(['county'])->toArray();

		$this->sql = "
		countymflcode AS `County MFL`, countys.name AS `County`, partners.name AS `Partner`,
		financial_year AS `Financial Year`, year AS `Calendar Year`, month AS `Month`, 
		MONTHNAME(concat(year, '-', month, '-01')) AS `Month Name`,
		tested AS `Tested`, positive AS `Positives`, new_art AS `New On ART`, linkage AS `Linkage Percentage`,
		current_tx AS `Current On ART`, net_new_tx AS `Net New On ART`, vl_total AS `VL Total`, 
		eligible_for_vl AS `Eligible For VL`,
		pmtct AS `PMTCT`, pmtct_stat AS `PMTCT STAT`, pmtct_new_pos AS `PMTCT New Positives`,
		pmtct_known_pos AS `PMTCT Known Positives`, pmtct_total_pos AS `PMTCT Total Positives`, 
		art_pmtct AS `ART PMTCT`, art_uptake_pmtct AS `ART Uptake PMTCT`,
		eid_lt_2m AS `EID Less 2 Months`, eid_lt_12m AS `EID Less 12 Months`,
		eid_total AS `EID Total`, eid_pos AS `EID Positives` ";

		$this->sql = "
		countymflcode AS `County MFL`, countys.name AS `County`, partners.name AS `Partner`,
		financial_year AS `Financial Year`, year AS `Calendar Year`, month AS `Month`, 
		MONTHNAME(concat(year, '-', month, '-01')) AS `Month Name`,  ";

		$this->sql .= $column . ' AS ' . $alias;



    }

    public function headings() : array
    {
		$row = DB::table('p_early_indicators')
			->join('countys', 'countys.id', '=', 'p_early_indicators.county')
			->join('partners', 'partners.id', '=', 'p_early_indicators.partner')
			->join('periods', 'periods.id', '=', 'p_early_indicators.period_id')
			->selectRaw($this->sql)
			->where(['funding_agency_id' => 1, 'financial_year' => 2020])
			// ->where(['funding_agency_id' => 1, 'period_id' => $this->period->id])
			// ->whereIn('county', $this->counties_array)
			->first();

		return collect($row)->keys()->all();
    }

    public function map($row): array
    {    	
		$row_array = get_object_vars($row);
		if(isset($row_array['Linkage Percentage']) && $row_array['Linkage Percentage']) $row_array['Linkage Percentage'] *= 100;
		return collect($row_array)->flatten()->all(); 
    }


    public function query()
    {		
		return DB::table('p_early_indicators')
			->join('countys', 'countys.id', '=', 'p_early_indicators.county')
			->join('partners', 'partners.id', '=', 'p_early_indicators.partner')
			->join('periods', 'periods.id', '=', 'p_early_indicators.period_id')
			->selectRaw($this->sql)
			->where(['funding_agency_id' => 1, 'financial_year' => 2020])
			// ->where(['funding_agency_id' => 1, 'period_id' => $this->period->id])
			// ->whereIn('county', $this->counties_array)		
			->orderBy('partners.name', 'asc')
			->orderBy('countys.name', 'asc')
			->orderBy('p_early_indicators.id', 'asc');
    }
}
