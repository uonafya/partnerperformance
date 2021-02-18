<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class TargetsExport extends BaseExport
{
    function __construct($request)
    {
    	parent::__construct();
		$this->financial_year = $request->input('financial_year', date('Y'));
		if(date('m') > 9 && !$request->input('financial_year')) $this->financial_year++;
    	$this->table_name = 't_facility_target';

		$this->sql = "countyname as County, Subcounty, facilitycode AS `MFL Code`, name AS `Facility`, financial_year AS `Financial Year`, gbv AS `GBV` ";
	}

    public function headings() : array
    {
		$row = DB::table($this->table_name)
			->join('view_facilitys', 'view_facilitys.id', '=', $this->table_name . '.facility')
			->selectRaw($this->sql)
			->where('financial_year', $this->financial_year)
			->where('funding_agency_id', 1)
			->first();

		return collect($row)->keys()->all();
    }

    public function query()
    {		
		return DB::table($this->table_name)
			->join('view_facilitys', 'view_facilitys.id', '=', $this->table_name . '.facility')
			->selectRaw($this->sql)
			->where('financial_year', $this->financial_year)
			->where('funding_agency_id', 1);

    }

}
