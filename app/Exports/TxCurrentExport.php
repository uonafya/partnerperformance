<?php

namespace App\Exports;

use DB;

use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class TxCurrentExport implements FromQuery, Responsable
{
	use Exportable;

	private $fileName;
	private $writerType = Excel::XLSX;
	private $month;
	private $financial_year;
	private $age_category_id;
	private $gender_id;



    function __construct($request)
    {
		$this->month = $request->input('month', date('m')-1);
		$this->financial_year = $request->input('financial_year', date('Y'));
		$this->age_category_id = $request->input('age_category_id');
		$this->gender_id = $request->input('gender_id');

		$this->fileName = $this->partner->download_name . '_FY_' . $this->financial_year . '_' . \App\Lookup::resolve_month($this->month) . '_tx_curr';
	}



    public function query()
    {
    	$gender_id = $this->gender_id;
    	$age_category_id = $this->age_category_id;

		$sql = "countyname as County, Subcounty,		
		financial_year AS `Financial Year`, year AS `Calendar Year`, month AS `Month`, 
		MONTHNAME(concat(year, '-', month, '-01')) AS `Month Name`, facilitycode AS `MFL Code`, 
		name AS `Facility`, alias_name AS `Column Name`, value AS `Value`";

		return DB::table('d_tx_curr')
			->join('view_facilitys', 'view_facilitys.id', '=', "d_tx_curr.facility")
            ->join('periods', 'periods.id', '=', "d_tx_curr.period_id")
			->join('surge_columns_view', "d_tx_curr.column_id", '=', 'surge_columns_view.id')
			->selectRaw($sql)
			->where(['partner' => $this->partner->id, 'financial_year' => $this->financial_year, 'month' => $this->month, 'modality' => 'tx_curr'])
			->when($age_category_id, function($query) use ($age_category_id){
				return $query->where('age_category_id', $age_category_id);
			})
			->when($gender_id, function($query) use ($gender_id){
				return $query->where('gender_id', $gender_id);
			})
			->orderBy('view_facilitys.name', 'asc')
			->orderBy('column_id', 'asc');
    }
}
