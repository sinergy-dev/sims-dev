<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class TestHR extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLeavingPermiteAsHrManagerPage() {
        $response = $this->actingAs(User::where('email', 'elfi@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsSalesManagerPage() {
        $response = $this->actingAs(User::where('email', 'rizki@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsOperationsDirectorPage() {
        $response = $this->actingAs(User::where('email', 'nabil@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsSalesStaffPage() {
        $response = $this->actingAs(User::where('email', 'albert@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsHrStaffPage() {
        $response = $this->actingAs(User::where('email', 'verawaty@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsHrFlPage() {
        $response = $this->actingAs(User::where('email', 'yudhi@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsHrAncillaryPage() {
        $response = $this->actingAs(User::where('email', 'nasooha@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsFinanceStaffPage() {
        $response = $this->actingAs(User::where('email', 'stefany@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsPmoManagerPage() {
        $response = $this->actingAs(User::where('email', 'angger@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsPmoStaffPage() {
        $response = $this->actingAs(User::where('email', 'ihsan@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsBcdManagerPage() {
        $response = $this->actingAs(User::where('email', 'endraw@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsPmoProjectCoordinatorPage() {
        $response = $this->actingAs(User::where('email', 'panca@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }

    public function testLeavingPermiteAsMsmHelpdeskPage() {
        $response = $this->actingAs(User::where('email', 'lailatul@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsSolManagerPage() {
        $response = $this->actingAs(User::where('email', 'ganjar@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsSolStaffPage() {
        $response = $this->actingAs(User::where('email', 'rizaldo@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsSidManagerPage() {
        $response = $this->actingAs(User::where('email', 'dio@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsSidStaffPage() {
        $response = $this->actingAs(User::where('email', 'dicky@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsPmoAdminPage() {
        $response = $this->actingAs(User::where('email', 'yuni@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsPresidentDirectorPage() {
        $response = $this->actingAs(User::where('email', 'rony@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsFinancialDirectorPage() {
        $response = $this->actingAs(User::where('email', 'yuliane@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsMmsLeadHelpdeskPage() {
        $response = $this->actingAs(User::where('email', 'bayu@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsMsmManagerPage() {
        $response = $this->actingAs(User::where('email', 'brillyan@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsBCDStaffPage() {
        $response = $this->actingAs(User::where('email', 'triza@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }
    public function testLeavingPermiteAsBCDProcurementPage() {
        $response = $this->actingAs(User::where('email', 'rily@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }

    public function testEmployeePage() {
        $response = $this->actingAs(User::where('email', 'rily@sinergy.co.id')->first())
            ->get('/show_cuti');
        $response->assertOk();  
    }

    //employee
    public function testEmployeePageHrManager() {
        $response = $this->actingAs(User::where('email', 'elfi@sinergy.co.id')->first())
            ->get('/hu_rec');
        $response->assertOk();  
    }
    public function testEmployeePageOperationDirector() {
        $response = $this->actingAs(User::where('email', 'nabil@sinergy.co.id')->first())
            ->get('/hu_rec');
        $response->assertOk();  
    }
    public function testEmployeePageHrStaff() {
        $response = $this->actingAs(User::where('email', 'verawaty@sinergy.co.id')->first())
            ->get('/hu_rec');
        $response->assertOk();  
    }
    public function testEmployeePageHrFl() {
        $response = $this->actingAs(User::where('email', 'yudhi@sinergy.co.id')->first())
            ->get('/hu_rec');
        $response->assertOk();  
    }
    public function testEmployeePageBcdManager() {
        $response = $this->actingAs(User::where('email', 'endraw@sinergy.co.id')->first())
            ->get('/hu_rec');
        $response->assertOk();  
    }
    public function testEmployeePagePresidentDirector() {
        $response = $this->actingAs(User::where('email', 'rony@sinergy.co.id')->first())
            ->get('/hu_rec');
        $response->assertOk();  
    }
    public function testEmployeePageFinancialDirector() {
        $response = $this->actingAs(User::where('email', 'yuliane@sinergy.co.id')->first())
            ->get('/hu_rec');
        $response->assertOk();  
    }
    public function testEmployeePageMsmManager() {
        $response = $this->actingAs(User::where('email', 'brillyan@sinergy.co.id')->first())
            ->get('/hu_rec');
        $response->assertOk();  
    }
}
