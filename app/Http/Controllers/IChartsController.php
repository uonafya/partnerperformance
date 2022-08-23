<?php

namespace App\Http\Controllers;

use App\Commons\Commons;
use App\Commons\get_hfr_sum;
use App\Commons\get_hfr_sum_prev;
use App\Commons\linkageServiceRoutine;
use App\Commons\testingServiceRoutine;
use App\Commons\tx_curr_oldServiceRoutines;
use App\Commons\tx_curr_trendServiceRoutine;
use App\Commons\tx_currServiceRoutine;
use App\Commons\tx_newServiceRoutine;
use App\Commons\targetDonutServiceRoutine;
use App\HfrSubmission;
use App\Lookup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class IChartsController extends Controller
{

	use Commons, get_hfr_sum,get_hfr_sum_prev;

	use testingServiceRoutine, linkageServiceRoutine,
	tx_curr_oldServiceRoutines, tx_currServiceRoutine,
	tx_newServiceRoutine, tx_curr_trendServiceRoutine, targetDonutServiceRoutine;

	public function themeit()
	{
		return view("layouts.master2");
	}


    public function testing()
	{
		Cache::forget("testingServiceRoutine");
		
		Cache::rememberForever('testingServiceRoutine' ,function(){
			return $this->testingServiceRoutine();
		});

		$data = Cache::get("testingServiceRoutine");

		// return view('charts.dual_axis', $data);
		return view("iCharts.dual_axes_and_column", $data);
	}

	public function linkage()
	{
		Cache::forget("linkageServiceRoutine");

		Cache::rememberForever("linkageServiceRoutine", function(){
			return $this->linkageServiceRoutine();
		});

		$data = Cache::get("linkageServiceRoutine");

		return view('iCharts.dual_axes_and_column', $data);
	}


	public function tx_new()
	{
		Cache::forget("tx_newServiceRoutine");

		Cache::rememberForever("tx_newServiceRoutine", function(){
			return $this->tx_newServiceRoutine();
		});

		$data = Cache::get("tx_newServiceRoutine");

		return view('iCharts.dual_axes_and_column', $data);
	}

	public function tx_curr()
	{
		Cache::forget("tx_currServiceRoutine");

		Cache::rememberForever("tx_currServiceRoutine", function(){
			return $this->tx_currServiceRoutine();
		});

		$data = Cache::get("tx_currServiceRoutine");

		return view('iCharts.dual_axes_and_column', $data);
	}

	public function testing_target()
	{
		Cache::forget("targetDonutServiceRoutine");

		Cache::rememberForever("targetDonutServiceRoutine", function(){
			return $this->targetDonutServiceRoutine();
		});

		$data = Cache::get("targetDonutServiceRoutine");

		return view('iCharts.pie_chart', $data);
	}
	
}
