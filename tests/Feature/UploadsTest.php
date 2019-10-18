<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \App\User;

class UploadsTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $user = User::first();
        $partner = $user->partner;
        $this->actingAs($user)->withSession(['session_partner' => $partner]);
    }    

    public function testLoggedStatus()
    {
        $user = User::first();
        $this->assertAuthenticatedAs($user);
    }

    public function testUploadNonmer()
    {
        $name = 'ampath_plus_non_mer_indicators_2017.xlsx';
        $path = public_path('test/' . $name);
        $file = new UploadedFile($path, $name, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
        $response = $this->call('POST', '/upload/non_mer', [], [], ['upload' => $file]);
        $response->assertStatus(302);
    }  

    public function testUploadIndicator()
    {
        $name = 'ampath_plus_2017_early_warning_indicators_monthly_data.xlsx';
        $path = public_path('test/' . $name);
        $file = new UploadedFile($path, $name, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
        $response = $this->call('POST', '/upload/indicators', [], [], ['upload' => $file]);
        $response->assertStatus(302);
    } 

    public function testUploadDispensing()
    {
        $name = 'AMPATH_Plus_FY_2019_Sep_dispensing.xlsx';
        $path = public_path('test/' . $name);
        $file = new UploadedFile($path, $name, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
        $response = $this->call('POST', '/upload/dispensing', [], [], ['upload' => $file]);
        $response->assertStatus(302);
    }

    public function testUploadTxCurr()
    {
        $name = 'AMPATH_Plus_FY_2019_Sep_tx_curr.xlsx';
        $path = public_path('test/' . $name);
        $file = new UploadedFile($path, $name, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
        $response = $this->call('POST', '/upload/tx_curr', [], [], ['upload' => $file]);
        $response->assertStatus(302);
    }

    public function testUploadPNS()
    {
        $name = 'ampath_plus_2018_pns.xlsx';
        $path = public_path('test/' . $name);
        $file = new UploadedFile($path, $name, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
        $response = $this->call('POST', '/upload/pns', [], [], ['upload' => $file]);
        $response->assertStatus(302);
    }

    public function testUploadSurge()
    {
        $name = 'ampath_plus_surge_data_for_2019-04-28_to_2019-05-04.xlsx';
        $path = public_path('test/' . $name);
        $file = new UploadedFile($path, $name, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
        $response = $this->call('POST', '/upload/surge', [], [], ['upload' => $file]);
        $response->assertStatus(302);
    }

    public function testUploadWeeklyPrepNew()
    {
        $name = 'AMPATH_Plus_prep_new_for_2019-04-28_to_2019-05-04.xlsx';
        $path = public_path('test/' . $name);
        $file = new UploadedFile($path, $name, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
        $response = $this->call('POST', '/upload/weekly', ['modality' => 'prep_new'], [], ['upload' => $file]);
        $response->assertStatus(302);
    }

    public function testUploadWeeklyVmmc()
    {
        $name = 'AMPATH_Plus_vmmc_circ_for_2019-04-28_to_2019-05-04.xlsx';
        $path = public_path('test/' . $name);
        $file = new UploadedFile($path, $name, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
        $response = $this->call('POST', '/upload/weekly', ['modality' => 'vmmc_circ'], [], ['upload' => $file]);
        $response->assertStatus(302);
    }
}
