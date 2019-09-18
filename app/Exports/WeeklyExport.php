<?php

namespace App\Exports;

use DB;

use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class WeeklyExport implements FromQuery, Responsable
{
	use Exportable;

	private $fileName;	
	private $writerType = Excel::XLSX;
	private $week_id;
	private $modality;
	private $gender_id;
	private $age_category_id;
	private $partner;

    function __construct($request)
    {
		$this->week_id = $request->input('week');
		$this->modality = $request->input('modality');
		$this->gender_id = $request->input('gender_id');
		$this->age_category_id = $request->input('age_category_id');
		$this->partner = auth()->user()->partner;

		$week = \App\Week::findOrFail($this->week_id);
		$this->fileName = str_replace(' ', '_', $partner->name) . '_' . $m_name . '_for_' . $week->start_date . '_to_' . $week->end_date;
    }

}
