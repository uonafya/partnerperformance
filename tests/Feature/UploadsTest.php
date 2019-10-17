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
        $path = public_path('test/ampath_plus_non_mer_indicators_2017.xlsx');
        $file = new UploadedFile($path, $name, filesize($path), 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
        $response = $this->call('POST', '/upload/non_mer', [], [], ['upload' => $file]);
        $response->assertStatus(200);
    }  

    public function testUploadIndicator()
    {
        $path = public_path('test/ampath_plus_2017_early_warning_indicators_monthly_data.xlsx');
        $file = new UploadedFile($path, $name, filesize($path), 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
        $response = $this->call('POST', '/upload/indicator', [], [], ['upload' => $file]);
        $response->assertStatus(200);
    } 

    public function testUploadDispensing()
    {
        $path = public_path('test/AMPATH_Plus_FY_2019_Sep_dispensing.xlsx');
        $file = new UploadedFile($path, $name, filesize($path), 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
        $response = $this->call('POST', '/upload/dispensing', [], [], ['upload' => $file]);
        $response->assertStatus(200);
    }

    public function testUploadTxCurr()
    {
        $path = public_path('test/AMPATH_Plus_FY_2019_Sep_tx_curr.xlsx');
        $file = new UploadedFile($path, $name, filesize($path), 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
        $response = $this->call('POST', '/upload/tx_curr', [], [], ['upload' => $file]);
        $response->assertStatus(200);
    }

    public function testUploadPNS()
    {
        $path = public_path('test/ampath_plus_2018_pns.xlsx');
        $file = new UploadedFile($path, $name, filesize($path), 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
        $response = $this->call('POST', '/upload/pns', [], [], ['upload' => $file]);
        $response->assertStatus(200);
    }

    public function testUploadSurge()
    {
        $path = public_path('test/ampath_plus_surge_data_for_2019-04-28_to_2019-05-04.xlsx');
        $file = new UploadedFile($path, $name, filesize($path), 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
        $response = $this->call('POST', '/upload/surge', [], [], ['upload' => $file]);
        $response->assertStatus(200);
    }

    public function testUploadWeeklyPrepNew()
    {
        $path = public_path('test/AMPATH_Plus_prep_new_for_2019-04-28_to_2019-05-04.xlsx');
        $file = new UploadedFile($path, $name, filesize($path), 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
        $response = $this->call('POST', '/upload/weekly', ['modality' => 'prep_new'], [], ['upload' => $file]);
        $response->assertStatus(200);
    }

    public function testUploadWeeklyVmmc()
    {
        $path = public_path('test/AMPATH_Plus_vmmc_circ_for_2019-04-28_to_2019-05-04.xlsx');
        $file = new UploadedFile($path, $name, filesize($path), 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
        $response = $this->call('POST', '/upload/weekly', ['modality' => 'vmmc_circ'], [], ['upload' => $file]);
        $response->assertStatus(200);
    }
}
