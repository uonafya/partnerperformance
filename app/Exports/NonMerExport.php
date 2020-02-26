<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMapping;

use DB;
use \App\Lookup;

class NonMerExport extends BaseExport implements WithMapping
{
	protected $financial_year;


    function __construct($financial_year)
    {
    	parent::__construct();
		$this->financial_year = $financial_year;

		$this->fileName = $this->partner->download_name . '_FY_' . $this->financial_year . '_non_mer_indicators' . '.xlsx';


		$this->sql = "financial_year AS `Financial Year`, name AS `Facility`, partnername AS `Partner Name`, facilitycode AS `MFL Code`, DHIScode AS `DHIS Code`, 
				subcounty AS `Subcounty Name`, `countyname` AS `County Name`, is_pns AS `Is PNS (YES/NO)`,
				is_viremia AS `Is Viremia (YES/NO)`, is_dsd AS `Is DSD (YES/NO)`, is_otz AS `Is OTZ (YES/NO)`, is_men_clinic AS `Is Men Clinic (YES/NO)`,
				viremia_beneficiaries AS `Viremia Beneficiaries`, dsd_beneficiaries AS `DSD Beneficiaries`, otz_beneficiaries AS `OTZ Beneficiaries`, men_clinic_beneficiaries AS `Men Clinic Beneficiaries` ";
    }


    public function headings() : array
    {
		$row = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw($this->sql)
			->where('partner', $this->partner->id)	
			->first();

		return collect($row)->keys()->all();
    }

    public function map($row): array
    {
		$row_array = get_object_vars($row);
		$row_array['Is PNS (YES/NO)'] = Lookup::get_boolean($row_array['Is PNS (YES/NO)']);
		$row_array['Is Viremia (YES/NO)'] = Lookup::get_boolean($row_array['Is Viremia (YES/NO)']);
		$row_array['Is DSD (YES/NO)'] = Lookup::get_boolean($row_array['Is DSD (YES/NO)']);
		$row_array['Is OTZ (YES/NO)'] = Lookup::get_boolean($row_array['Is OTZ (YES/NO)']);
		$row_array['Is Men Clinic (YES/NO)'] = Lookup::get_boolean($row_array['Is Men Clinic (YES/NO)']);
		return collect($row_array)->flatten()->all();
    }



    public function query()
    {		
    	$financial_year = $this->financial_year;

		return DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw($this->sql)
			->when($financial_year, function($query) use ($financial_year){
				return $query->where('financial_year', $financial_year);
			})
			->where('partner', $this->partner->id)			
			->orderBy('name', 'asc');
    }
}
