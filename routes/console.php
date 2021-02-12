<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('synch:subcounties', function () {
	\App\Synch::subcounties();
})->describe('Synch subcounties from DHIS.');

Artisan::command('synch:wards', function () {
	\App\Synch::wards();
})->describe('Synch wards from DHIS.');

Artisan::command('synch:facilities', function () {
	\App\Synch::facilities();
})->describe('Synch facilities from DHIS.');

Artisan::command('synch:datasets', function () {
	\App\Synch::datasets();
})->describe('Synch datasets from DHIS.');

Artisan::command('truncate:tables', function () {
	\App\Synch::truncate_tables();
})->describe('Truncate tables.');

/*Artisan::command('insert:rows {year?}', function ($year=null) {
	\App\Insert::insert_rows($year);

	// \App\Synch::insert_rows($year);
	// \App\Merger::insert_rows($year);
	// \App\Other::partner_indicators_insert($year);
})->describe('Insert rows for data tables.');*/

Artisan::command('populate {year?}', function ($year=null) {
	\App\Synch::populate($year);
})->describe('Populate data tables with values.');

Artisan::command('regimens {year?}', function ($year=null) {
	\App\Synch::populate_regimen($year);
})->describe('Populate regimen tables with values.');

Artisan::command('insert:weekly', function () {
	\App\Insert::create_weeks();
})->describe('Add weeks and create rows.');

Artisan::command('insert:periods', function ($year=null) {
	\App\Insert::insert_periods($year);
})->describe('Add periods.');

Artisan::command('insert:period-rows', function ($year=null) {
	\App\Insert::insert_rows($year);
})->describe('Add periods and create rows.');



Artisan::command('merge:all {year?}', function ($year=null) {
	\App\Merger::testing($year);
	\App\Merger::art($year);
	\App\Merger::pmtct($year);
	\App\Merger::circumcision($year);
	\App\Merger::keypop($year);
})->describe('Merge the testing, art, pmtct, keypop and circumcision records.');


Artisan::command('merge:testing {year?}', function ($year=null) {
	\App\Merger::testing($year);
})->describe('Merge the testing records.');

Artisan::command('merge:art {year?}', function ($year=null) {
	\App\Merger::art($year);
})->describe('Merge the art records.');

Artisan::command('merge:pmtct {year?}', function ($year=null) {
	\App\Merger::pmtct($year);
})->describe('Merge the pmtct records.');

Artisan::command('merge:circumcision {year?}', function ($year=null) {
	\App\Merger::circumcision($year);
})->describe('Merge the circumcision records.');

Artisan::command('merge:keypop {year?}', function ($year=null) {
	\App\Merger::keypop($year);
})->describe('Merge the keypop records.');



Artisan::command('targets {year?}', function ($year=null) {
	\App\TargetInsert::insert($year);
})->describe('Populate target tables with values.');


Artisan::command('resend_link {id}', function ($id) {
	\App\Other::reset_email($id);
})->describe('');


Artisan::command('hfr', function () {
	\App\HfrSubmission::upload_data();
})->describe('Upload HFR data from disk.');

Artisan::command('report', function () {
	\App\Lookup::send_report();
})->describe('Send Duplicates Report.');

Artisan::command('notify', function () {
	\App\Other::send_pns();
})->describe('Send emails to all partners.');

Artisan::command('gbv:full-export', function () {
	\App\Surge::full_gbv_export();
})->describe('GBV export full.');

Artisan::command('indicator:full-export', function () {
	\App\Surge::full_indicator_export();
})->describe('Indicator export full.');

Artisan::command('surges', function () {
	\App\Surge::surges();
})->describe('Surges.');

Artisan::command('surges:export', function () {
	\App\Surge::surge_export();
})->describe('Surges export.');

Artisan::command('surges:full-export', function () {
	\App\Surge::full_surge_export();
})->describe('Surges export full.');

Artisan::command('surges:data', function () {
	$s = new \App\Surge;
	$s->surge_data();
	// \App\Surge::surge_export();
})->describe('Surges data.');


