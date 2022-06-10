<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use DB;

class TempHfrSubmission extends Model
{
    protected $guarded = ['id'];

    public static function updateHfrSubmissionsFromTemp()
    {
        $starting_time = date('Y-m-d H:i:s');
        $progress_report = "Started job at {$starting_time}\n";
        /* Second attempt that will be run as a cron job */
        // Getting temp records
        $temprecords = TempHfrSubmission::get();
        $progress_report .= "-------- No. of records to update {$temprecords->count()}\n";
        $columns = collect(HfrSubmission::columns())->pluck('column_name');
        foreach ($temprecords as $key => $temp) {
            $update_data = collect($temp->toArray())->only($columns)->toArray();
            if(env('APP_ENV') != 'testing') {
                $updated = DB::table('d_hfr_submission')
                    ->where(['facility' => $temp->facility, 'week_id' => $temp->week_id, ])
                    ->update($update_data);
            }
            // Delete the temp Records
            $temp->delete();
        }
        $finish_time = date('Y-m-d H:i:s');
        $progress_report .= "Completed update job at {$finish_time}\n";
        Log::channel('hfrupload')->info($progress_report);
        return true;

        /* First Attempt with update join taking too long */
        /*
        $temp_table = 'temp_hfr_submissions';
        $base_table = 'd_hfr_submission';
        $columns = DB::getSchemaBuilder()->getColumnListing($temp_table); // temp table columns
        $columns_to_ignore = ['id','week_id','facility','created_at','updated_at','deleted_at'];

        // Building update query
        $query = "UPDATE {$base_table}, {$temp_table} JOIN {$temp_table} ON {$temp_table}.week_id = {$base_table}.week_id AND {$temp_table}.facility = {$base_table}.facility SET ";
        
        foreach($columns as $column) {
            if (!in_array($column, $columns_to_ignore)) {
                $query .= "{$base_table}.{$column} = {$temp_table}.{$column},";
            }   
        }
        $query .= " {$temp_table}.deleted_at = '" . now() . "' WHERE {$temp_table}.deleted_at IS NULL";
        $execute = DB::statement(DB::raw($query));
        self::removeUpdated();
        */
    }

    public static function removeUpdated()
    {
        return DB::table('temp_hfr_submissions')->where('deleted_at', '<>', NULL)->delete();
    }
}
