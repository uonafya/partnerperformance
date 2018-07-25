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

Route::middleware(['web'])->group(function(){

	Route::prefix('filter')->name('filter.')->group(function(){
		Route::post('date', 'FilterController@filter_date')->name('date');
		Route::post('partner', 'FilterController@filter_partner')->name('partner');
	});

	Route::prefix('partner')->name('partner.')->group(function(){
		Route::get('tested', 'PartnerController@tested')->name('tested');
	});

	Route::middleware(['clear_session'])->group(function(){
		Route::get('/', 'GeneralController@partner_home');
		Route::get('partner_home', 'GeneralController@partner_home');
	});
});