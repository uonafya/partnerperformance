<?php

namespace App\Exports;

use DB;

class TxCurrentExport extends BaseExport
{
	protected $month;
	protected $financial_year;
	protected $age_category_id;
	protected $gender_id;



    function __construct($request)
    {
    	parent::__construct();
		$this->month = $request->input('month', date('m')-1);
		$this->financial_year = $request->input('financial_year', date('Y'));
		$this->age_category_id = $request->input('age_category_id');
		$this->gender_id = $request->input('gender_id');

		$this->fileName = $this->partner->download_name . '_FY_' . $this->financial_year . '_' . \App\Lookup::resolve_month($this->month) . '_tx_curr';
		$this->sql = "countyname as County, Subcounty,		
		financial_year AS `Financial Year`, year AS `Calendar Year`, month AS `Month`, 
		MONTHNAME(concat(year, '-', month, '-01')) AS `Month Name`, facilitycode AS `MFL Code`, 
		name AS `Facility`, alias_name AS `Column Name`, value AS `Value`";
	}

    public function headings() : array
    {
		$row = DB::table('d_tx_curr')
			->join('view_facilitys', 'view_facilitys.id', '=', "d_tx_curr.facility")
            ->join('periods', 'periods.id', '=', "d_tx_curr.period_id")
			->join('surge_columns_view', "d_tx_curr.column_id", '=', 'surge_columns_view.id')
			->selectRaw($this->sql)
			->where(['partner' => $this->partner->id, 'financial_year' => $this->financial_year, 'month' => $this->month, 'modality' => 'tx_curr'])
			->first();

		return collect($row)->keys()->all();
    }



    public function query()
    {
    	$gender_id = $this->gender_id;
    	$age_category_id = $this->age_category_id;

		return DB::table('d_tx_curr')
			->join('view_facilitys', 'view_facilitys.id', '=', "d_tx_curr.facility")
            ->join('periods', 'periods.id', '=', "d_tx_curr.period_id")
			->join('surge_columns_view', "d_tx_curr.column_id", '=', 'surge_columns_view.id')
			->selectRaw($this->sql)
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
