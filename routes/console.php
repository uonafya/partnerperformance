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

Artisan::command('insert:rows {year?}', function ($year=null) {
	\App\Synch::insert_rows($year);
})->describe('Insert rows for data tables.');

Artisan::command('populate {year?}', function ($year=null) {
	\App\Synch::populate($year);
})->describe('Populate data tables with values.');



Artisan::command('target:current', function () {
	\App\TargetInsert::current_2018();
})->describe('Populate target tables with values.');

Artisan::command('target:new', function () {
	\App\TargetInsert::new_2018();
})->describe('Populate target tables with values.');


Artisan::command('report', function () {
	\App\Lookup::send_report();
})->describe('Send Duplicates Report.');


