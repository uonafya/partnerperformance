<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['check_live'])->group(function(){
	Auth::routes(['verify' => true]);
});

// Route::get('/home', 'HomeController@index')->name('home');


Route::post('facility/search', 'FilterController@facility')->name('facility.search');

Route::prefix('filter')->name('filter.')->group(function(){
	Route::post('date', 'FilterController@filter_date')->name('date');
	Route::post('any', 'FilterController@filter_any')->name('any');
	Route::post('partner', 'FilterController@filter_partner')->name('partner');
});


Route::prefix('testing')->name('testing.')->group(function(){
	Route::get('testing_outcomes', 'TestingController@testing_outcomes')->name('testing_outcomes');
	Route::get('testing_age', 'TestingController@testing_age')->name('testing_age');
	Route::get('testing_gender', 'TestingController@testing_gender')->name('testing_gender');
	Route::get('pos_age', 'TestingController@pos_age')->name('pos_age');
	Route::get('pos_gender', 'TestingController@pos_gender')->name('pos_gender');
	Route::get('positivity', 'TestingController@positivity')->name('positivity');
	Route::get('discordancy', 'TestingController@discordancy')->name('discordancy');
	Route::get('testing_summary', 'TestingController@testing_summary')->name('testing_summary');
	Route::get('summary', 'TestingController@summary')->name('summary');
});

Route::prefix('pmtct')->name('pmtct.')->group(function(){
	Route::get('haart', 'PmtctController@haart')->name('haart');
	Route::get('testing', 'PmtctController@testing')->name('testing');
	Route::get('starting_point', 'PmtctController@starting_point')->name('starting_point');
	Route::get('discovery_positivity', 'PmtctController@discovery_positivity')->name('discovery_positivity');
	Route::get('eid', 'PmtctController@eid')->name('eid');
	Route::get('male_testing', 'PmtctController@male_testing')->name('male_testing');
});

Route::prefix('art')->name('art.')->group(function(){
	Route::get('current_age_breakdown', 'ArtController@current_age_breakdown')->name('current_age_breakdown');
	Route::get('new_age_breakdown', 'ArtController@new_age_breakdown')->name('new_age_breakdown');
	Route::get('enrolled_age_breakdown', 'ArtController@enrolled_age_breakdown')->name('enrolled_age_breakdown');
	Route::get('new_art', 'ArtController@new_art')->name('new_art');
	Route::get('current_art', 'ArtController@current_art')->name('current_art');
	Route::get('current_suppression', 'ArtController@current_suppression')->name('current_suppression');
	
	Route::get('treatment', 'ArtController@treatment')->name('treatment');
	Route::get('reporting', 'ArtController@reporting')->name('reporting');
});

Route::prefix('vmmc')->name('vmmc.')->group(function(){
	Route::get('testing', 'CircumcisionController@testing')->name('testing');
	Route::get('summary', 'CircumcisionController@summary')->name('summary');
	Route::get('adverse', 'CircumcisionController@adverse')->name('adverse');
});

Route::prefix('tb')->name('tb.')->group(function(){
	Route::get('known_status', 'TBController@known_status')->name('known_status');
	Route::get('newly_tested', 'TBController@newly_tested')->name('newly_tested');
	Route::get('tb_screening', 'TBController@tb_screening')->name('tb_screening');
	Route::get('ipt', 'TBController@ipt')->name('ipt');
});

Route::prefix('keypop')->name('keypop.')->group(function(){
	Route::get('testing', 'KeypopController@testing')->name('testing');
	Route::get('current_tx', 'KeypopController@current_tx')->name('current_tx');
	Route::get('summary', 'KeypopController@summary')->name('summary');
});

Route::prefix('non_mer')->name('non_mer.')->group(function(){
	Route::get('facilities_count', 'OtzController@facilities_count')->name('facilities_count');
	Route::get('clinics', 'OtzController@clinics')->name('clinics');
	Route::get('achievement', 'OtzController@achievement')->name('achievement');
	Route::get('breakdown', 'OtzController@breakdown')->name('breakdown');
	Route::get('clinic_setup', 'OtzController@clinic_setup')->name('clinic_setup');
	Route::get('otz_breakdown', 'OtzController@otz_breakdown')->name('otz_breakdown');
	Route::get('dsd_impact', 'OtzController@dsd_impact')->name('dsd_impact');
	Route::get('mens_impact', 'OtzController@mens_impact')->name('mens_impact');


	Route::get('download/{financial_year}', 'OtzController@download_excel')->name('download');
	Route::post('upload', 'OtzController@upload_excel')->name('upload_excel');
});

Route::prefix('regimen')->name('regimen.')->group(function(){
	Route::get('reporting', 'RegimenController@reporting')->name('reporting');
	Route::get('summary', 'RegimenController@summary')->name('summary');
});

Route::prefix('indicators')->name('indicators.')->group(function(){
	Route::get('testing', 'IndicatorController@testing')->name('testing');
	Route::get('positivity', 'IndicatorController@positivity')->name('positivity');
	Route::get('summary', 'IndicatorController@summary')->name('summary');
	Route::get('currenttx', 'IndicatorController@currenttx')->name('currenttx');
	Route::get('newtx', 'IndicatorController@newtx')->name('newtx');

	Route::get('download/{financial_year}', 'IndicatorController@download_excel')->name('download');
	Route::post('upload', 'IndicatorController@upload_excel')->name('upload_excel');
});

Route::prefix('pns')->name('pns.')->group(function(){
	Route::get('summary_chart', 'PNSController@summary_chart')->name('summary_chart');
	Route::get('pns_contribution', 'PNSController@pns_contribution')->name('pns_contribution');
	Route::get('summary_table', 'PNSController@summary_table')->name('summary_table');
	Route::get('get_table/{item}', 'PNSController@get_table')->name('get_table');
});

Route::prefix('surge')->name('surge.')->group(function(){
	Route::get('testing', 'SurgeController@testing')->name('testing');
	Route::get('linkage', 'SurgeController@linkage')->name('linkage');
	Route::get('modality_yield', 'SurgeController@modality_yield')->name('modality_yield');
	Route::get('gender_yield', 'SurgeController@gender_yield')->name('gender_yield');
	Route::get('age_yield', 'SurgeController@age_yield')->name('age_yield');
	Route::get('pns', 'SurgeController@pns')->name('pns');
	Route::get('tx_sv', 'SurgeController@tx_sv')->name('tx_sv');
	Route::get('tx_btc', 'SurgeController@tx_btc')->name('tx_btc');
	Route::get('targets', 'SurgeController@targets')->name('targets');
});


Route::prefix('dispensing')->name('dispensing.')->group(function(){
	Route::get('summary', 'DispensingController@summary')->name('summary');
});


Route::prefix('tx_curr')->name('tx_curr.')->group(function(){
	Route::get('gender', 'TxCurrentController@gender')->name('gender');
	Route::get('age', 'TxCurrentController@age')->name('age');
});

Route::prefix('weekly')->name('weekly.')->group(function(){
	Route::get('summary', 'WeeklyController@summary')->name('summary');
});


Route::prefix('gbv')->name('gbv.')->group(function(){
	Route::get('violence', 'GBVController@violence')->name('violence');
	Route::get('sexual', 'GBVController@sexual')->name('sexual');
	Route::get('age', 'GBVController@age')->name('age');
	Route::get('gender', 'GBVController@gender')->name('gender');
});

Route::prefix('violence')->name('violence.')->group(function(){
	Route::get('pep_reported', 'ViolenceController@pep_reported')->name('pep_reported');
	Route::get('reporting', 'ViolenceController@reporting')->name('reporting');
	Route::get('cumulative_pie', 'ViolenceController@cumulative_pie')->name('cumulative_pie');
	Route::get('monthly_achievement', 'ViolenceController@monthly_achievement')->name('monthly_achievement');
	Route::get('performance', 'ViolenceController@performance')->name('performance');
	Route::get('monthly_cases', 'ViolenceController@monthly_cases')->name('monthly_cases');
	Route::get('pep', 'ViolenceController@pep')->name('pep');
	Route::get('age_gender', 'ViolenceController@age_gender')->name('age_gender');

	Route::get('new_reporting', 'ViolenceController@new_reporting')->name('new_reporting');
	Route::get('modality_reported', 'ViolenceController@modality_reported')->name('modality_reported');
});
Route::prefix('cervical_cancer')->name('cervical_cancer')->group(function(){
	Route::get('/', 'GeneralController@cervical_cancer_dashboard');
});

Route::prefix('hfr')->name('hfr.')->group(function(){
	Route::get('testing', 'HfrController@testing')->name('testing');
	Route::get('linkage', 'HfrController@linkage')->name('linkage');
	Route::get('tx_curr', 'HfrController@tx_curr')->name('tx_curr');
	Route::get('tx_mmd', 'HfrController@tx_mmd')->name('tx_mmd');
	Route::get('prep_new', 'HfrController@prep_new')->name('prep_new');
	Route::get('vmmc_circ', 'HfrController@vmmc_circ')->name('vmmc_circ');
	Route::get('net_new', 'HfrController@net_new')->name('net_new');
	Route::get('net_new_detail', 'HfrController@net_new_detail')->name('net_new_detail');
	Route::get('tx_crude', 'HfrController@tx_crude')->name('tx_crude');	
	Route::get('tx_new', 'HfrController@tx_new')->name('tx_new');	
	
	Route::get('misassigned_facilities', 'HfrController@misassigned_facilities')->name('misassigned_facilities');
	Route::get('prep_new_last_rpt_period', 'HfrController@prep_new_last_rpt_period')->name('prep_new_last_rpt_period');
	Route::get('testing_dis', 'HfrController@testing_dis')->name('testing_dis');
	Route::get('tx_curr_details', 'HfrController@tx_curr_details')->name('tx_curr_details');
	Route::get('vmmc_circ_details', 'HfrController@vmmc_circ_details')->name('vmmc_circ_details');
	Route::get('linkage_dis', 'HfrController@linkage_dis')->name('linkage_dis');
	Route::get('tx_new_dis', 'HfrController@tx_new_dis')->name('tx_new_dis');
	
	Route::get('tx_mmd_detail', 'HfrController@tx_mmd_detail')->name('tx_mmd_detail');

	// Route::get('tx_curr_two', 'HfrController@tx_curr_two')->name('tx_curr_two');
	// Route::get('tx_mmd_two', 'HfrController@tx_mmd_two')->name('tx_mmd_two');

	Route::get('target_donut/{modality}', 'HfrController@target_donut')->name('target_donut');
});


Route::middleware(['clear_session', 'check_nascop'])->group(function(){
	Route::get('/', 'GeneralController@dupli_home');
	Route::get('/config', 'GeneralController@config');
	Route::get('home', 'GeneralController@home');
	Route::get('pmtct', 'GeneralController@pmtct');
	Route::get('art', 'GeneralController@art');
	Route::get('testing', 'GeneralController@testing');
	Route::get('vmmc', 'GeneralController@vmmc');
	Route::get('tb', 'GeneralController@tb');
	Route::get('keypop', 'GeneralController@keypop');
	Route::get('regimen', 'GeneralController@regimen');
	Route::get('non_mer', 'GeneralController@non_mer');
	Route::get('pns', 'GeneralController@pns');
	Route::get('indicators', 'GeneralController@indicators');
	Route::get('surge', 'GeneralController@surge');
	Route::get('hfr', 'GeneralController@hfr');
	Route::get('hfr-test', 'GeneralController@hfr_test');


		Route::get('gbv', 'GeneralController@gbv');
		Route::get('violence', 'GeneralController@violence');
		Route::get('violence-test', 'GeneralController@violence_test');
	// Route::get('gbv', 'GeneralController@gbv');
	
	Route::get('dispensing', 'GeneralController@dispensing');
	Route::get('tx_curr', 'GeneralController@tx_curr');

	Route::get('guide', 'GeneralController@guide');
});

Route::middleware(['signed'])->group(function(){
	Route::get('reset/password/{user}', 'GeneralController@change_password')->name('reset.password');
});




/*
	Start of routes that require authentication
*/
Route::middleware(['clear_session', 'auth', 'check_live'])->group(function(){

	Route::get('download/{path}', 'ImportsExportsController@export_any');

	Route::prefix('target')->name('target')->group(function(){
		Route::post('get_data', 'OtzController@get_data')->name('get_data');
		Route::post('set_target', 'OtzController@set_target')->name('set_target');

		Route::get('target', 'GeneralController@targets');
	});	

	Route::prefix('facilities')->name('facilities')->group(function(){
		Route::get('upload', 'GeneralController@upload_facilities');
		Route::post('upload', 'PNSController@upload_facilities');
	});

	Route::prefix('pns')->name('pns')->group(function(){
		Route::get('download', 'GeneralController@download_pns');

		Route::post('download', 'PNSController@download_excel')->name('download');
		Route::post('upload', 'PNSController@upload_excel')->name('upload');
	});

	Route::prefix('surge')->name('surge')->group(function(){
		Route::get('download', 'GeneralController@download_surge');
	
		Route::get('set_surge_facilities', 'GeneralController@set_surge_facilities');


		Route::post('set_surge_facilities', 'SurgeController@set_surge_facilities')->name('set_surge_facilities');
		Route::post('download', 'SurgeController@download_excel')->name('download');
		Route::post('upload', 'SurgeController@upload_excel')->name('upload');
	});

	Route::prefix('dispensing')->name('dispensing')->group(function(){
		Route::get('download', 'GeneralController@download_dispensing');

		Route::post('download', 'DispensingController@download_excel')->name('download');
		Route::post('upload', 'DispensingController@upload_excel')->name('upload');
	});

	Route::prefix('tx_curr')->name('tx_curr')->group(function(){
		Route::get('download', 'GeneralController@download_tx_curr');

		Route::post('download', 'TxCurrentController@download_excel')->name('download');
		Route::post('upload', 'TxCurrentController@upload_excel')->name('upload');
	});

	Route::prefix('weekly')->name('weekly')->group(function(){
		Route::get('download/{modality}', 'GeneralController@download_weeklies');

		Route::post('download', 'WeeklyController@download_excel')->name('download');
		Route::post('upload', 'WeeklyController@upload_excel')->name('upload');
	});

	Route::prefix('gbv')->name('gbv')->group(function(){
		Route::get('download', 'GeneralController@download_gbv');
		Route::get('download-report', 'GeneralController@download_gbv_report');
	});

	Route::prefix('hfr')->name('hfr')->group(function(){
		Route::get('download', 'GeneralController@download_hfr');
		Route::get('download-report', 'GeneralController@download_hfr_report');
	});

	Route::prefix('cervical_cancer')->name('cervical_cancer')->group(function(){
		Route::get('download', 'GeneralController@download_cervical_cancer');
	});


	// Upload any Data
	Route::get('upload/facilities', 'GeneralController@upload_facilities');
	Route::get('upload/{path}/{modality?}', 'GeneralController@upload_any');
	// The one with a modality will include it in the post params
	Route::post('upload/{path}', 'ImportsExportsController@upload_any');

	// Download any Template
	Route::get('download/non_mer/{financial_year}', 'ImportsExportsController@export_non_mer');
	Route::get('download/indicator/{financial_year}', 'ImportsExportsController@export_indicator');
	Route::post('download/{path}', 'ImportsExportsController@export_any');
	// Route::post('download/surge', 'ImportsExportsController@export_surge');

	Route::get('user/change_password', 'UserController@change_password');
	Route::resource('user', 'UserController');
});


/*Route::prefix('partner')->name('partner.')->group(function(){

	Route::get('summary', 'PartnerController@summary')->name('summary');

	Route::get('tested', 'PartnerController@tested')->name('tested');
	Route::get('positive', 'PartnerController@positive')->name('positive');
	Route::get('linked', 'PartnerController@linked')->name('linked');

	Route::get('pmtct', 'PartnerController@pmtct')->name('pmtct');

	Route::get('new_art', 'PartnerController@new_art')->name('new_art');
	Route::get('current_art', 'PartnerController@current_art')->name('current_art');
});

Route::prefix('chart')->name('chart.')->group(function(){
	Route::get('treatment', 'ChartController@treatment')->name('treatment');

	Route::get('current', 'ChartController@current')->name('current');
	Route::get('art_new', 'ChartController@art_new')->name('art_new');

	Route::get('testing_gender', 'ChartController@testing_gender')->name('testing_gender');
	Route::get('outcome_gender', 'ChartController@outcome_gender')->name('outcome_gender');
	
	Route::get('testing_age', 'ChartController@testing_age')->name('testing_age');
	Route::get('outcome_age', 'ChartController@outcome_age')->name('outcome_age');

	Route::get('pmtct', 'ChartController@pmtct')->name('pmtct');
	Route::get('eid', 'ChartController@eid')->name('eid');

});

Route::prefix('table')->name('table.')->group(function(){
	Route::get('summary', 'TableController@summary')->name('summary');
	Route::get('art_new', 'TableController@art_new')->name('art_new');
	Route::get('art_current', 'TableController@art_current')->name('art_current');
});

Route::prefix('old/chart')->name('old.chart.')->group(function(){
	Route::get('treatment', 'OldChartController@treatment')->name('treatment');

	Route::get('current', 'OldChartController@current')->name('current');
	Route::get('art_new', 'OldChartController@art_new')->name('art_new');

	Route::get('testing_gender', 'OldChartController@testing_gender')->name('testing_gender');
	Route::get('outcome_gender', 'OldChartController@outcome_gender')->name('outcome_gender');
	
	Route::get('testing_age', 'OldChartController@testing_age')->name('testing_age');
	Route::get('outcome_age', 'OldChartController@outcome_age')->name('outcome_age');

	Route::get('pmtct', 'OldChartController@pmtct')->name('pmtct');
	Route::get('eid', 'OldChartController@eid')->name('eid');

});

Route::prefix('old/table')->name('old.table.')->group(function(){
	Route::get('summary', 'OldTableController@new_summary')->name('summary');
	Route::get('summary_breakdown', 'OldTableController@summary_breakdown')->name('summary_breakdown');
	Route::get('art_new', 'OldTableController@art_new')->name('art_new');
	Route::get('art_current', 'OldTableController@art_current')->name('art_current');
});*/