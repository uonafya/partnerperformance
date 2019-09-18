<?php

namespace App\Exports;

use DB;

use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class PNSExport implements FromQuery, Responsable
{
	use Exportable;

	private $fileName;
	private $writerType = Excel::XLSX;
	private $items;
	private $months;
	private $financial_year;


    function __construct($request)
    {
		$items = $request->input('items');
		$months = $request->input('months');
		$financial_year = $request->input('financial_year', date('Y'));

    }



    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }
}
