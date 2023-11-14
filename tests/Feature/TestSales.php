<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use DB;

class TestSales extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
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

    public function testSalesRegisterAsSalesManagerPage() {
        $response = $this->actingAs(User::where('email','rizki@sinergy.co.id')->first())
            ->get('/project/index');

        $response->assertStatus(200);
    }

    public function testSalesRegisterAsSalesStaffPage() {
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

    public function testSalesRegisterAsBcdPartnershipPage() {
        $response = $this->actingAs(User::where('email','triza@sinergy.co.id')->first())
            ->get('/project/index');

        $response->assertOk();
    }

    public function testSalesRegisterAsPmoManagerPage() {
        $response = $this->actingAs(User::where('email','angger@sinergy.co.id')->first())
            ->get('/project/index');

        $response->assertOk();
    }

    public function testSalesRegisterAsPmoStaffPage() {
        $response = $this->actingAs(User::where('email','ihsan@sinergy.co.id')->first())
            ->get('/project/index');

        $response->assertOk();
    }

    public function testSalesRegisterAsFinanceDirectorPage() {
        $response = $this->actingAs(User::where('email','Yuliane@sinergy.co.id')->first())
            ->get('/project/index');

        $response->assertOk();
    }

    public function testSalesRegisterAsSidManagerPage() {
        $response = $this->actingAs(User::where('email','dio@sinergy.co.id')->first())
            ->get('/project/index');

        $response->assertOk();
    }

    public function testSalesRegisterAsMsmManagerPage() {
        $response = $this->actingAs(User::where('email','brillyan@sinergy.co.id')->first())
            ->get('/project/index');

        $response->assertOk();
    }

    //report lead
    public function testReportLeadAsOperationDirectorPage() {
        $response = $this->actingAs(User::where('email','nabil@sinergy.co.id')->first())
            ->get('/report_range');

        $response->assertOk();
    }

    public function testReportLeadAsSalesManagerPage() {
        $response = $this->actingAs(User::where('email','rizki@sinergy.co.id')->first())
            ->get('/report_range');

        $response->assertOk();
    }

    public function testReportLeadAsSolManagerPage() {
        $response = $this->actingAs(User::where('email','ganjar@sinergy.co.id')->first())
            ->get('/report_range');

        $response->assertOk();
    }

    public function testReportLeadAsSolStaffPage() {
        $response = $this->actingAs(User::where('email','rizaldo@sinergy.co.id')->first())
            ->get('/report_range');

        $response->assertOk();
    }

    public function testReportLeadAsPresidentDirectorPage() {
        $response = $this->actingAs(User::where('email','rony@sinergy.co.id')->first())
            ->get('/report_range');

        $response->assertOk();
    }

    public function testReportLeadAsFinanceDirectorPage() {
        $response = $this->actingAs(User::where('email','yuliane@sinergy.co.id')->first())
            ->get('/report_range');

        $response->assertOk();
    }

    public function testReportLeadAsBcdManagerPage() {
        $response = $this->actingAs(User::where('email','endraw@sinergy.co.id')->first())
            ->get('/report_range');

        $response->assertOk();
    }

    public function testReportLeadAsMsmManagerPage() {
        $response = $this->actingAs(User::where('email','brillyan@sinergy.co.id')->first())
            ->get('/report_range');

        $response->assertOk();
    }

    //Report Customer
    public function testReportCustomerAsMsmManagerPage() {
        $response = $this->actingAs(User::where('email','brillyan@sinergy.co.id')->first())
            ->get('/report_customer');

        $response->assertOk();
    }

    public function testReportCustomerAsSalesManagerPage() {
        $response = $this->actingAs(User::where('email','rizki@sinergy.co.id')->first())
            ->get('/report_customer');

        $response->assertOk();
    }

    public function testReportCustomerAsSolManagerPage() {
        $response = $this->actingAs(User::where('email','ganjar@sinergy.co.id')->first())
            ->get('/report_customer');

        $response->assertOk();
    }

    public function testReportCustomerAsSolStaffPage() {
        $response = $this->actingAs(User::where('email','rizaldo@sinergy.co.id')->first())
            ->get('/report_customer');

        $response->assertOk();
    }

    public function testReportCustomerAsPresidentDirectorPage() {
        $response = $this->actingAs(User::where('email','rony@sinergy.co.id')->first())
            ->get('/report_customer');

        $response->assertOk();
    }

    public function testReportCustomerAsFinancetDirectorPage() {
        $response = $this->actingAs(User::where('email','yuliane@sinergy.co.id')->first())
            ->get('/report_customer');

        $response->assertOk();
    }

    //report tagging
    public function testReportTanggingMsmManagerPage() {
        $response = $this->actingAs(User::where('email','brillyan@sinergy.co.id')->first())
            ->get('/report_customer');

        $response->assertOk();
    }

    public function testReportTanggingSalesManagerPage() {
        $response = $this->actingAs(User::where('email','rizki@sinergy.co.id')->first())
            ->get('/report_customer');

        $response->assertOk();
    }

    public function testReportTanggingOperationDirectorPage() {
        $response = $this->actingAs(User::where('email','nabil@sinergy.co.id')->first())
            ->get('/report_customer');

        $response->assertOk();
    }

    public function testReportTanggingBcdManagerPage() {
        $response = $this->actingAs(User::where('email','endraw@sinergy.co.id')->first())
            ->get('/report_customer');

        $response->assertOk();
    }

    public function testReportTanggingSolManagerPage() {
        $response = $this->actingAs(User::where('email','ganjar@sinergy.co.id')->first())
            ->get('/report_customer');

        $response->assertOk();
    }

    public function testReportTanggingSolStaffPage() {
        $response = $this->actingAs(User::where('email','rizaldo@sinergy.co.id')->first())
            ->get('/report_customer');

        $response->assertOk();
    }

    public function testReportTanggingPresidentDirectorPage() {
        $response = $this->actingAs(User::where('email','rony@sinergy.co.id')->first())
            ->get('/report_customer');

        $response->assertOk();
    }

    public function testReportTanggingFinanceDirectorPage() {
        $response = $this->actingAs(User::where('email','yuliane@sinergy.co.id')->first())
            ->get('/report_customer');

        $response->assertOk();
    }

    public function testReportTanggingBcdStaffPage() {
        $response = $this->actingAs(User::where('email','triza@sinergy.co.id')->first())
            ->get('/report_customer');

        $response->assertOk();
    }   
    
    //report brand
    public function testReportBrandOperationDirectorPage() {
        $response = $this->actingAs(User::where('email','nabil@sinergy.co.id')->first())
            ->get('/report_product_index');

        $response->assertOk();
    }  

    public function testReportBrandHrGaPage() {
        $response = $this->actingAs(User::where('email','rizki@sinergy.co.id')->first())
            ->get('/report_product_index');

        $response->assertOk();
    }  

    public function testReportBrandBcdManagerPage() {
        $response = $this->actingAs(User::where('email','endraw@sinergy.co.id')->first())
            ->get('/report_product_index');

        $response->assertOk();
    }  

    public function testReportBrandSolManagerPage() {
        $response = $this->actingAs(User::where('email','ganjar@sinergy.co.id')->first())
            ->get('/report_product_index');

        $response->assertOk();
    }

    public function testReportBrandSolStaffPage() {
        $response = $this->actingAs(User::where('email','rizaldo@sinergy.co.id')->first())
            ->get('/report_product_index');

        $response->assertOk();
    }  
    
    public function testReportBrandPresidentDirectorPage() {
        $response = $this->actingAs(User::where('email','rony@sinergy.co.id')->first())
            ->get('/report_product_index');

        $response->assertOk();
    } 

    public function testReportBrandFinancialDirectorPage() {
        $response = $this->actingAs(User::where('email','yuliane@sinergy.co.id')->first())
            ->get('/report_product_index');

        $response->assertOk();
    } 

    public function testReportBrandMsmManagerPage() {
        $response = $this->actingAs(User::where('email','brillyan@sinergy.co.id')->first())
            ->get('/report_product_index');

        $response->assertOk();
    } 
    
    
    public function testReportBrandBcdStaffPage() {
        $response = $this->actingAs(User::where('email','triza@sinergy.co.id')->first())
            ->get('/report_product_index');

        $response->assertOk();
    } 
    
    //report sales

    public function testReportSalesSalesManagerPage() {
        $response = $this->actingAs(User::where('email', 'rizki@sinergy.co.id')->first())
            ->get('/report_sales');

        $response->assertOk();
    }

    public function testReportSalesOperationDirectorPage() {
        $response = $this->actingAs(User::where('email', 'rizki@sinergy.co.id')->first())
            ->get('/report_sales');

        $response->assertOk();
    }
    
    public function testReportSalesBcdManagerPage() {
        $response = $this->actingAs(User::where('email', 'endraw@sinergy.co.id')->first())
            ->get('/report_sales');

        $response->assertOk();
    }

    public function testReportSalesPresidentDirectorPage() {
        $response = $this->actingAs(User::where('email', 'rony@sinergy.co.id')->first())
            ->get('/report_sales');

        $response->assertOk();
    }

    public function testReportSalesFinanceDirectorPage() {
        $response = $this->actingAs(User::where('email', 'yuiane@sinergy.co.id')->first())
            ->get('/report_sales');

        $response->assertOk();
    }

    public function testReportSalesMsmManagerPage() {
        $response = $this->actingAs(User::where('email', 'brillyan@sinergy.co.id')->first())
            ->get('/report_sales');

        $response->assertOk();
    }

    //report presales
    public function testReportPresalesAsSalesManagerPage() {
        $response = $this->actingAs(User::where('email', 'rizki@sinergy.co.id')->first())
            ->get('/report_presales');
        // $report_sales = DB::table('roles_feature')
        //     ->join('features','features.id','=','roles_feature.feature_id')
        //     ->join('roles','roles.id','=','roles_feature.role_id')
        //     ->join('role_user','role_user.role_id','=','roles.id')
        //     ->join('users','users.nik','=','role_user.user_id')
        //     ->select('features.name as feature_name','users.email')
        //     ->where('feature_id',12)
        //     ->distinct()->get();
        $response->assertOk();  
    }

    public function testReportPresalesAsOperationDirectorPage() {
        $response = $this->actingAs(User::where('email', 'nabil@sinergy.co.id')->first())
            ->get('/report_presales');
        $response->assertOk();  
    }

    public function testReportPresalesAsBcdManagerPage() {
        $response = $this->actingAs(User::where('email', 'endraw@sinergy.co.id')->first())
            ->get('/report_presales');
        $response->assertOk();  
    }

    public function testReportPresalesAsSolManagerPage() {
        $response = $this->actingAs(User::where('email', 'ganjar@sinergy.co.id')->first())
            ->get('/report_presales');
        $response->assertOk();  
    }

    public function testReportPresalesAsPresidentDirectorPage() {
        $response = $this->actingAs(User::where('email', 'rony@sinergy.co.id')->first())
            ->get('/report_presales');
        $response->assertOk();  
    }

    public function testReportPresalesAsFinancieDirectorPage() {
        $response = $this->actingAs(User::where('email', 'yuliane@sinergy.co.id')->first())
            ->get('/report_presales');
        $response->assertOk();  
    }

    public function testReportPresalesAsMsmManagerPage() {
        $response = $this->actingAs(User::where('email', 'brillyan@sinergy.co.id')->first())
            ->get('/report_presales');
        $response->assertOk();  
    }

    //partnership
    public function testReportPartnershipAsBcdManagerPage() {
        $response = $this->actingAs(User::where('email', 'endraw@sinergy.co.id')->first())
            ->get('/partnership');
        $response->assertOk();  
    }

    public function testReportPartnershipAsPresidentDirectorPage() {
        $response = $this->actingAs(User::where('email', 'rony@sinergy.co.id')->first())
            ->get('/partnership');
        $response->assertOk();  
    }

    public function testReportPartnershipAsBcdStaffPage() {
        $response = $this->actingAs(User::where('email', 'triza@sinergy.co.id')->first())
            ->get('/partnership');
        $response->assertOk();  
    }

    public function testReportPartnershipAsHrdManagerPage() {
        $response = $this->actingAs(User::where('email', 'elfi@sinergy.co.id')->first())
            ->get('/partnership');
        $response->assertOk();  
    }

    public function testReportPartnershipAsOtherMemberPage() {
        $response = $this->actingAs(User::where('email', 'johan@sinergy.co.id')->first())
            ->get('/partnership');
        $response->assertOk();  
    }
    
    //customer data
    public function testReportCustomerDataAsPresidentDirectorPage() {
        $response = $this->actingAs(User::where('email', 'rony@sinergy.co.id')->first())
            ->get('/customer');
        $response->assertOk();  
    }

    public function testReportCustomerDataAsBcdManagerPage() {
        $response = $this->actingAs(User::where('email', 'endraw@sinergy.co.id')->first())
            ->get('/customer');
        $response->assertOk();  
    }

    public function testReportCustomerDataAsProcurementPage() {
        $response = $this->actingAs(User::where('email', 'rily@sinergy.co.id')->first())
            ->get('/customer');
        $response->assertOk();  
    }

    public function testReportCustomerDataAsOtherMemberPage() {
        $response = $this->actingAs(User::where('email', 'johan@sinergy.co.id')->first())
            ->get('/customer');
        $response->assertOk();  
    }

    //category tagging
    public function testReportCategoryTaggingAsPresidentDirectorPage() {
        $response = $this->actingAs(User::where('email', 'rony@sinergy.co.id')->first())
            ->get('/customer_data');
        $response->assertOk();  
    }

    public function testReportCategoryTaggingAsSalesPage() {
        $response = $this->actingAs(User::where('email', 'rizki@sinergy.co.id')->first())
            ->get('/customer_data');
        $response->assertOk();  
    }

    public function testReportCategoryTaggingAsPresalesPage() {
        $response = $this->actingAs(User::where('email', 'johan@sinergy.co.id')->first())
            ->get('/customer_data');
        $response->assertOk();  
    }

    public function testReportCategoryTaggingAsOtherMemberPage() {
        $response = $this->actingAs(User::where('email', 'rizaldo@sinergy.co.id')->first())
            ->get('/customer_data');
        $response->assertOk();  
    }

    //setting lead
    public function testReportSettingLeadAsSalesMemberPage() {
        $response = $this->actingAs(User::where('email', 'rizki@sinergy.co.id')->first())
            ->get('/customer_data');
        $response->assertOk();  
    }

    public function testReportSettingLeadBcdManagerPage() {
        $response = $this->actingAs(User::where('email', 'endraw@sinergy.co.id')->first())
            ->get('/customer_data');
        $response->assertOk();  
    }

}
