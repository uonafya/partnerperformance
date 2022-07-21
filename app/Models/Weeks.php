<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Weeks extends Model
{
    public $data;
    protected $connection = 'mysql_wr';

    public static function transform($load)
    {
        return $load->map(function ($item) {
            // return $item;

            return [
                'old_id' => $item->id,
                'week_number' => $item->week_number,
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
                'year' => $item->year,
                'month' => $item->month,
                'financial_year' => $item->financial_year,
                'quarter' => $item->quarter,
            ];
        });
    }
}
