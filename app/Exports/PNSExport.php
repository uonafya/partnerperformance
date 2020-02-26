<?php

namespace App\Exports;

use DB;
use \App\PNS;

class PNSExport extends BaseExport
{
	protected $items;
	protected $months;
	protected $financial_year;


    function __construct($request)
    {
    	parent::__construct();
		$this->items = $request->input('items');
		$this->months = $request->input('months');
		$this->financial_year = $request->input('financial_year', date('Y'));


		$this->fileName = $this->partner->download_name . '_FY_' . $this->financial_year . '_pns' . '.xlsx';


		$sql = "countyname as County, Subcounty,
		facilitycode AS `MFL Code`, name AS `Facility`,
		financial_year AS `Financial Year`, year AS `Calendar Year`, month AS `Month`, 
		MONTHNAME(concat(year, '-', month, '-01')) AS `Month Name` ";
		$pns = new PNS;

		foreach ($this->items as $item) {
			foreach ($pns->ages_array as $key => $value) {
				$sql .= ", {$item}_{$key} AS `" . $pns->item_array[$item] . " {$value}` ";
			}
		}
		$this->sql = $sql;
    }

    public function headings() : array
    {
		$row = DB::table('d_pns')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_pns.facility')
			->join('periods', 'periods.id', '=', 'd_pns.period_id')
			->selectRaw($this->sql)
			->where('financial_year', $this->financial_year)
			->where('partner', $this->partner->id)
			->first();

		return collect($row)->keys()->all();
    }



    public function query()
    {		
    	$months = $this->months;

		return DB::table('d_pns')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_pns.facility')
			->join('periods', 'periods.id', '=', 'd_pns.period_id')
			->selectRaw($this->sql)
			->when($months, function($query) use ($months){
				return $query->whereIn('month', $months);
			})
			->where('financial_year', $this->financial_year)
			->where('partner', $this->partner->id)
			->orderBy('name', 'asc')
			->orderBy('d_pns.id', 'asc');
    }
}
