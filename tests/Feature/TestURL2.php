<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
// use App\Feature;
use DB;

class TestURL2 extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testTicketingPage() {

        $listUserTicketing = User::where('email','bayu@sinergy.co.id')->first();

        $response = $this->actingAs($listUserTicketing)
            ->get('/ticketing')->assertStatus(200);
    }
}
