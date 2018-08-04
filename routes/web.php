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

// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Route::get('/', 'GeneralController@partner_home');
Route::get('current', 'GeneralController@current');

Route::middleware(['web'])->group(function(){

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

	Route::middleware(['clear_session'])->group(function(){
		Route::get('/', 'GeneralController@home');
		Route::get('partner', 'GeneralController@partner_home');
	});
});