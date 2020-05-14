<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class TargetsExport extends BaseExport
{
    function __construct($request)
    {
    	parent::__construct();
		$this->financial_year = $request->input('financial_year', date('Y'));
	}
}
