<?php

namespace App\Providers;

use App\Commons\Commons;
use App\Commons\controller_trait;
use App\Commons\divisions_callback;
use App\Commons\get_callback;
use App\Commons\get_hfr_sum;
use App\Commons\get_hfr_sum_prev;
use App\Commons\get_joins_callback_weeks_hfr;
use App\HfrSubmission;
use App\Http\Controllers\Former\Controller;
use App\Lookup;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

use Illuminate\Support\Facades\DB;

class HfrServiceProvider extends ServiceProvider
{
    

    use Commons, get_hfr_sum, get_hfr_sum_prev, get_joins_callback_weeks_hfr, get_callback, divisions_callback;
    

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
        // $tests = HfrSubmission::columns(true, 'hts_tst'); 
		// $pos = HfrSubmission::columns(true, 'hts_tst_pos');
		// $sql = $this->get_hfr_sum($tests, 'tests') . ', ' . $this->get_hfr_sum($pos, 'pos');

		// $rows = DB::table($this->my_table)
		// 	->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
		// 	->selectRaw($sql)
		// 	->when(true, $this->get_callback('tests'))
		// 	->get();


		// $data['div'] = str_random(15);
		// $data['yAxis'] = "Total Number Tested";
		// $data['yAxis2'] = "Yield (%)";
		// $data['data_labels'] = true;
		// $data['no_column_label'] = true;
		// $data['suffix'] = '%';


		// Lookup::bars($data, ["Positive", "Negative", "Yield"], "column", ["#ff0000", "#00ff00", "#3023ea"]);
		// Lookup::splines($data, [2]);
		// $data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');
		// Lookup::yAxis($data, 0, 1);

		// $i=0;
		// foreach ($rows as $key => $row){
		// 	if(!$row->tests) continue;

		// 	$data['categories'][$i] = Lookup::get_category($row);

		// 	$data["outcomes"][0]["data"][$i] = (int) $row->pos;
		// 	$data["outcomes"][1]["data"][$i] = (int) ($row->tests - $row->pos);
		// 	$data["outcomes"][2]["data"][$i] = Lookup::get_percentage($row->pos, $row->tests);
		// 	$i++;
		// 	// dump(($row->tests - $row->pos));
		// }

        // Cache::put('key123', $data);
        // Cache::remember('key', 0, function($item){
        //     return $data;
        // });

    }
}
