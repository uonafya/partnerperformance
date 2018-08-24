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

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');


Route::post('facility/search', 'FilterController@facility')->name('facility.search');

Route::prefix('filter')->name('filter.')->group(function(){
	Route::post('date', 'FilterController@filter_date')->name('date');
	Route::post('any', 'FilterController@filter_any')->name('any');
	Route::post('partner', 'FilterController@filter_partner')->name('partner');
});

Route::prefix('partner')->name('partner.')->group(function(){

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
});



Route::prefix('testing')->name('testing.')->group(function(){
	Route::get('testing_outcomes', 'TestingController@testing_outcomes')->name('testing_outcomes');
	Route::get('positivity', 'TestingController@positivity')->name('positivity');
	Route::get('summary', 'TestingController@summary')->name('summary');
});

Route::prefix('pmtct')->name('pmtct.')->group(function(){
	Route::get('haart', 'PmtctController@haart')->name('haart');
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
	
	Route::get('treatment', 'ArtController@treatment')->name('treatment');
	Route::get('reporting', 'ArtController@reporting')->name('reporting');
});

Route::prefix('otz')->name('otz.')->group(function(){
	Route::get('facilities_count', 'OtzController@facilities_count')->name('facilities_count');
	Route::get('clinics', 'OtzController@clinics')->name('clinics');
	Route::get('achievement', 'OtzController@achievement')->name('achievement');
	Route::get('breakdown', 'OtzController@breakdown')->name('breakdown');


	Route::get('download/{financial_year}', 'OtzController@download_excel')->name('download');
	Route::post('upload', 'OtzController@upload_excel')->name('upload_excel');
});

Route::prefix('regimen')->name('regimen.')->group(function(){
	Route::get('reporting', 'RegimenController@reporting')->name('reporting');
	Route::get('summary', 'RegimenController@summary')->name('summary');
});

Route::prefix('indicators')->name('indicators.')->group(function(){
	Route::get('download/{financial_year}', 'IndicatorController@download_excel')->name('download');
	Route::post('upload', 'IndicatorController@upload_excel')->name('upload_excel');
});



Route::middleware(['clear_session'])->group(function(){
	Route::get('/', 'GeneralController@dupli_home');
	Route::get('home', 'GeneralController@home');
	Route::get('pmtct', 'GeneralController@pmtct');
	Route::get('art', 'GeneralController@art');
	Route::get('testing', 'GeneralController@testing');
	Route::get('otz', 'GeneralController@otz');
	Route::get('regimen', 'GeneralController@regimen');

	Route::get('guide', 'GeneralController@guide');
});

// Route::middleware(['signed'])->group(function(){
	Route::get('reset/passsword/{user}', 'GeneralController@change_password')->name('reset.password');
// });

Route::middleware(['clear_session', 'auth'])->group(function(){

	Route::prefix('target')->name('target')->group(function(){
		Route::post('get_data', 'OtzController@get_data')->name('get_data');
		Route::post('set_target', 'OtzController@set_target')->name('set_target');

		Route::get('target', 'GeneralController@targets');
	});
	
	Route::get('non_mer', 'GeneralController@non_mer');
	Route::get('indicators', 'GeneralController@indicators');
	Route::resource('user', 'UserController');
});