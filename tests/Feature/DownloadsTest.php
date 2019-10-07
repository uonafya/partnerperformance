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
        /*parent::setUp();

        $user = new User([
            'name' => 'Test',
            'email' => 'test@email.com',
            'password' => '123456'
        ]);

        $user->save();*/

    }


    public function testDownloadNonMer()
    {
        $user = User::first();
        $partner = $user->partner;
        $response = $this->actingAs($user)->withSession(['session_partner' => $partner])->get('/download/non_mer/2019');

        $response->assertStatus(200);
    }


    public function testDownloadIndicator()
    {
        $user = User::first();
        $partner = $user->partner;
        $response = $this->actingAs($user)->withSession(['session_partner' => $partner])->get('/download/non_mer/2019');

        $response->assertStatus(200);
    }

    public function testDownloadSurge()
    {
        $user = User::first();
        $partner = $user->partner;
        $response = $this->actingAs($user)->withSession(['session_partner' => $partner])->call('POST', '/upload/surge', [
            'week_id' => 40,
        ]);

        $response->assertOk();
    }


}
