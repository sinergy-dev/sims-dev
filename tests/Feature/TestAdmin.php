<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class TestAdmin extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    //Purchase Request
    public function testPurchaseRequestHrManagerPage() {
        $response = $this->actingAs(User::where('email', 'elfi@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testPurchaseRequestOperationDirectorPage() {
        $response = $this->actingAs(User::where('email', 'nabil@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testPurchaseRequestHrGaPage() {
        $response = $this->actingAs(User::where('email', 'verawaty@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testPurchaseRequestHrFlPage() {
        $response = $this->actingAs(User::where('email', 'yudhi@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testPurchaseRequestPmoAdminPage() {
        $response = $this->actingAs(User::where('email', 'yuni@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testPurchaseRequestPresidentDirectorPage() {
        $response = $this->actingAs(User::where('email', 'rony@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testPurchaseRequestFinanceDirectorPage() {
        $response = $this->actingAs(User::where('email', 'yuliane@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testPurchaseRequestMsmManagerPage() {
        $response = $this->actingAs(User::where('email', 'brillyan@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testPurchaseRequestBcdStaffPage() {
        $response = $this->actingAs(User::where('email', 'endraw@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testPurchaseRequestHrWarehousePage() {
        $response = $this->actingAs(User::where('email', 'endraw@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testPurchaseRequestBcdProcurementPage() {
        $response = $this->actingAs(User::where('email', 'rily@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }
    
    //Purchase Order
        public function testPurchaseOrderOperationDirectorPage() {
        $response = $this->actingAs(User::where('email', 'nabil@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testPurchaseOrderFinanceStaffPage() {
        $response = $this->actingAs(User::where('email', 'stefany@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testPurchaseOrderBcdManagerPage() {
        $response = $this->actingAs(User::where('email', 'endraw@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testPurchaseOrderPmoAdminPage() {
        $response = $this->actingAs(User::where('email', 'yuni@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testPurchaseOrderPresidentDirectorPage() {
        $response = $this->actingAs(User::where('email', 'rony@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testPurchaseOrderFinanceDirectorPage() {
        $response = $this->actingAs(User::where('email', 'yuliane@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testPurchaseOrderMsmManagertPage() {
        $response = $this->actingAs(User::where('email', 'brillyan@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }
    
    
    //Letter
    public function testLetterPresidentDirectorPage() {
        $response = $this->actingAs(User::where('email', 'rony@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testLetterOperationDirectorPage() {
        $response = $this->actingAs(User::where('email', 'nabil@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testLetterSalesPage() {
        $response = $this->actingAs(User::where('email', 'rizki@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testLetterBcdManagerPage() {
        $response = $this->actingAs(User::where('email', 'endraw@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testLetterOtherMemberPage() {
        $response = $this->actingAs(User::where('email', 'yuni@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }

    public function testLetterProcurementPage() {
        $response = $this->actingAs(User::where('email', 'rily@sinergy.co.id')->first())
            ->get('/pr');
        $response->assertOk();  
    }
    
    //Quote Number
    
    public function testQuoteNumberPresidentDirectorPage() {
        $response = $this->actingAs(User::where('email', 'rony@sinergy.co.id')->first())
            ->get('/quote');
        $response->assertOk();  
    }

    public function testQuoteNumberOperationDirectorPage() {
        $response = $this->actingAs(User::where('email', 'nabil@sinergy.co.id')->first())
            ->get('/quote');
        $response->assertOk();  
    }

    public function testQuoteNumberSalesPage() {
        $response = $this->actingAs(User::where('email', 'rizki@sinergy.co.id')->first())
            ->get('/quote');
        $response->assertOk();  
    }

    public function testQuoteNumberBcdManagerPage() {
        $response = $this->actingAs(User::where('email', 'endraw@sinergy.co.id')->first())
            ->get('/quote');
        $response->assertOk();  
    }

    public function testQuoteNumberOtherMemberPage() {
        $response = $this->actingAs(User::where('email', 'yuni@sinergy.co.id')->first())
            ->get('/quote');
        $response->assertOk();  
    }

    public function testQuoteNumberProcurementPage() {
        $response = $this->actingAs(User::where('email', 'rily@sinergy.co.id')->first())
            ->get('/quote');
        $response->assertOk();  
    }
}
