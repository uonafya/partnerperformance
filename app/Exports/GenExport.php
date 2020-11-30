<?php

namespace App\Exports;

use DB;

class GenExport
{

	public function csv_save($data, $file_name)
	{
		$fp = fopen($file_name, 'w');

		$first = [];

		foreach ($data[0] as $key => $value) {
			$first[] = $key;
		}
		fputcsv($fp, $first);

		foreach ($data as $key => $value) {
			fputcsv($fp, $value);
		}
		fclose($fp);
	}

	public static function csv_download($data, $file_name='download')
	{
		header('Content-Description: File Transfer');
		header('Content-Type: application/csv');
		header("Content-Disposition: attachment; filename={$file_name}.csv");

		$fp = fopen('php://output', 'w');

		$first = [];

		foreach ($data[0] as $key => $value) {
			$first[] = $key;
		}
		fputcsv($fp, $first);

		foreach ($data as $key => $value) {
			fputcsv($fp, $value);
		}
		
		fclose($fp);
	}

}