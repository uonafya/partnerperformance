<?php

namespace App;

class Lookup
{

	public static function table_name_formatter($raw)
	{
		$raw = strtolower($raw);
		$str = explode(' ', $raw);

		$size = sizeof($str);
		$final = '';

		for ($i=2; $i < $size; $i++) { 
			$final .= $str[$i] . '_';
		}
		$final = str_replace('revision_2018', '', $final);

		if(ends_with($final, '_')) $final = str_replace_last('_', '', $final);
		if(ends_with($final, '_')) $final = str_replace_last('_', '', $final);
		return $final;
	}

	public static function column_name_formatter($raw)
	{
		$raw = strtolower($raw);
		$raw = str_replace('moh 731', '', $raw);
		$raw = str_replace('moh731b', '', $raw);
		$raw = str_replace('.', '', $raw);
		$raw = str_replace('+', 'pos', $raw);

		$raw = str_replace(' ', '_', $raw);
		$raw = str_replace('__', '_', $raw);
		$raw = str_replace('__', '_', $raw);
		$raw = str_replace('__', '_', $raw);
		$raw = str_replace('(couples_only)', '', $raw);

		$final = $raw;

		if(starts_with($final, '_')) $final = str_replace_first('_', '', $final);
		if(starts_with($final, '_')) $final = str_replace_first('_', '', $final);
		if(ends_with($final, '_')) $final = str_replace_last('_', '', $final);
		if(ends_with($final, '_')) $final = str_replace_last('_', '', $final);

		$length = strlen($final);

		if($length > 50) $final = str_limit($final, 50);

		return $final;
	}
}
