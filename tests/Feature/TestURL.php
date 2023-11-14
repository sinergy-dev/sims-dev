<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class TestURL extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLoginPage() {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function testDashboardPage() {
        $response = $this->actingAs(User::where('email','endraw@sinergy.co.id')->first())
            ->get('/');

        $response->assertStatus(200);
    }

    public function testPresencePersonalPage() {
        $response = $this->actingAs(User::where('email','endraw@sinergy.co.id')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    // public function testSalesRegisterPage() {
    //     $response = $this->actingAs(User::where('email','endraw@sinergy.co.id')->first())
    //         ->get('/project/index');

    //     $response->assertStatus(200);
    // }

    public function testSalesRegisterAsPresalesManagerPage() {
        $response = $this->actingAs(User::where('email','ganjar@sinergy.co.id')->first())
            ->get('/project/index');

        $response->assertStatus(200);
    }

    public function testSalesRegisterAsPresalesStaffPage() {
        $response = $this->actingAs(User::where('email','johan@sinergy.co.id')->first())
            ->get('/project/index');

        $response->assertStatus(200);
    }

    public function testSalesRegisterAsSalesPage() {
        $response = $this->actingAs(User::where('email','rizki@sinergy.co.id')->first())
            ->get('/project/index');

        $response->assertStatus(200);
    }

    public function testSalesRegisterAsDirectorPage() {
        $response = $this->actingAs(User::where('email','nabil@sinergy.co.id')->first())
            ->get('/project/index');

        $response->assertStatus(200);
    }

    public function testSalesRegisterAsBcdManagerPage() {
        $response = $this->actingAs(User::where('email','endraw@sinergy.co.id')->first())
            ->get('/project/index');

        $response->assertStatus(200);
    }
}
