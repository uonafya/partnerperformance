<?php

namespace App\Exports;

use DB;

class AllSurgeExport extends BaseExport
{

	public function __construct($partner)
	{
        $columns = \App\SurgeColumnView::where(['tbl_name' => 'd_surge'])->get();
        $sql = "countyname as County, Subcounty, wardname AS `Ward`, facilitycode AS `MFL Code`, partnername as `Partner`, name AS `Facility`, financial_year AS `Financial Year`, week_number as `Week Number`, start_date, end_date ";

        foreach ($columns as $column) {
            $sql .= ", `{$column->column_name}` AS `{$column->alias_name}`";
        }
        $this->sql = $sql;
        $this->partner = $partner;
        $this->fileName = $this->partner->download_name . '_surge_data' . '.xlsx';
	}

    public function headings() : array
    {
    	$row = DB::table('d_surge')
                ->join('view_facilitys', 'view_facilitys.id', '=', 'd_surge.facility')
                ->join('weeks', 'weeks.id', '=', 'd_surge.week_id')
                ->selectRaw($this->sql)
                ->where('week_id', '>', 32)
                ->where(['is_surge' => 1, 'partner' => $this->partner->id])
                ->first();

		return collect($row)->keys()->all();
    }

    public function query()
    {
    	return DB::table('d_surge')
                ->join('view_facilitys', 'view_facilitys.id', '=', 'd_surge.facility')
                ->join('weeks', 'weeks.id', '=', 'd_surge.week_id')
                ->selectRaw($this->sql)
                ->where('week_id', '>', 32)
                ->where(['is_surge' => 1, 'partner' => $this->partner->id]);
    }
}
