<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class TestURL3 extends TestCase
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

    public function testLeavingPermitPage() {
        $response = $this->actingAs(User::where('email','endraw@sinergy.co.id')->first())
            ->get('/show_cuti');

        $response->assertStatus(200);

        $response = $this->actingAs(User::where('email','faiqoh@sinergy.co.id')->first())
            ->get('/show_cuti');

        $response->assertStatus(200);

        $response = $this->actingAs(User::where('email','tito@sinergy.co.id')->first())
            ->get('/show_cuti');

        $response->assertStatus(200);
    }
}
