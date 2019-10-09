<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \App\User;

class DownloadsTest extends TestCase
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

    public function testDownloadNonMer()
    {
        $response = $this->get('/download/non_mer/2019');
        $response->assertHeader('content-disposition');
        $response->assertStatus(200);
    }


    public function testDownloadIndicator()
    {
        $response = $this->get('/download/indicator/2019');
        $response->assertStatus(200);
    }



    public function testDownloadDispensing()
    {
        $response = $this->post('/download/dispensing', [
            'month' => 1,
            'gender_id' => 1,
            'age_category_id' => 1,
        ]);
        $response->assertOk();
    }

    public function testDownloadTxCurr()
    {
        $response = $this->post('/download/tx_curr', [
            'gender_id' => 1,
            'age_category_id' => 1,
        ]);
        $response->assertOk();
    }

    public function testDownloadPNS()
    {
        $response = $this->post('/download/pns', [
            'items' => ['screened', 'contacts_identified'],
            'months' => [1],
        ]);
        $response->assertOk();
    }

    public function testDownloadSurge()
    {
        $response = $this->post('/download/surge', [
            'week_id' => 40,
            'modalities' => 1,
            'gender_id' => 1,

        ]);
        $response->assertOk();
    }

    public function testDownloadWeeklyPrepNew()
    {
        $response = $this->post('/download/weekly', [
            'week_id' => 40,
            'modality' => 'prep_new',
            'gender_id' => 1,
            'age_category_id' => 1,

        ]);
        $response->assertOk();
    }

    public function testDownloadWeeklyVmmc()
    {
        $response = $this->post('/download/weekly', [
            'week_id' => 40,
            'modality' => 'vmmc_circ',
            'age_category_id' => 1,
        ]);
        $response->assertOk();
    }


}
