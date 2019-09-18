<?php

namespace App\Exports;

use DB;

use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BaseExport implements FromQuery, Responsable, WithHeadings
{
	use Exportable;

	protected $fileName;
	protected $writerType = Excel::CSV;
	protected $sql;
	protected $partner;

	
}
