<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function testDownloadPNS()
    {
        $response = $this->post('/download/pns', [
            'items' => ['screened', 'contacts_identified'],

        ]);
        $response->assertOk();
    }

    public function testDownloadSurge()
    {
        $response = $this->post('/download/surge', [
            'week' => 40,

        ]);
        $response->assertOk();
    }


}
