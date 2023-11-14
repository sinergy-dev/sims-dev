<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Login');
        });
    }

    public function testBasicExample2()
    {
    	$this->browse(function (Browser $browser) {
            $user = User::where('email','tech@sinergy.co.id')->first();

            $browser->loginAs($user)
                ->visit('admin/draftPR')
                ->assertAuthenticated();
                // ->assertSee('Draft List Purchase Request ');
        });
    }
}
