<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use \App\User;
use InteractsWithAuthentication;

class DownloadsTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();


        $user = User::first();
        // Auth::login($user);
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
        // $response->assertHeader('attachment');
        $response->assertStatus(200);
    }


    public function testDownloadIndicator()
    {
        $response = $this->get('/download/indicator/2019');
        $response->assertStatus(200);
    }

    public function testDownloadSurge()
    {
        $response = $this->post('/download/surge', [
            'week' => 40,

        ]);
        $response->assertStatus(200);
    }


}
