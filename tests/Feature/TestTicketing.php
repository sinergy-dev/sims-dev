<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class TestTicketing extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function testExample()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function testTicketingAsHelpdeskPage() {
        $response = $this->actingAs(User::where('email','herta@sinergy.co.id')->first())
            ->get('/ticketing');

        $response->assertStatus(200);
    }

    public function testTicketingAsLeadHelpdeskPage() {
        $response = $this->actingAs(User::where('email','bayu@sinergy.co.id')->first())
            ->get('/ticketing');

        $response->assertStatus(200);
    }

    public function testTicketingAsMsmManagerPage() {
        $response = $this->actingAs(User::where('email','brillyan@sinergy.co.id')->first())
            ->get('/ticketing');

        $response->assertStatus(200);
    }

    public function testTicketingAsTechnicalSupportPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Technical Support')->first())
            ->get('/ticketing');

        $response->assertStatus(200);
    }

    public function testTicketingAsPmoPcPage() {
        $response = $this->actingAs(User::where('email','qharla@sinergy.co.id')->first())
            ->get('/ticketing');

        $response->assertStatus(200);
    }

    public function testTicketingAsDirectorPage() {
        $response = $this->actingAs(User::where('email','nabil@sinergy.co.id')->first())
            ->get('/ticketing');

        $response->assertStatus(200);
    }

    public function testTicketingAsBcdManagerPage() {
        $response = $this->actingAs(User::where('email','endraw@sinergy.co.id')->first())
            ->get('/ticketing');

        $response->assertStatus(200);
    }
}
