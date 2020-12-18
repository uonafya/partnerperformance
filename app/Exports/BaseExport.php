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
	// protected $writerType = Excel::CSV;
	protected $writerType = Excel::XLSX;
	protected $sql;
	protected $partner;
    protected $table_name;

    public function __construct()
    {
        $this->fileName = 'download.xlsx';
        $this->partner = auth()->user()->partner ?? null;
    }

    public function headings() : array
    {
    	return [];
    }

    public function query()
    {
    	return null;
    }


}
