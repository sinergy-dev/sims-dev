<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;
use App\PRDraft;
use App\Http\Controllers\PrDraftController;
use Illuminate\Http\Request;

class PRDraftVerifyTest extends DuskTestCase
{

    public $url = "admin/draftPR";
    public $user = "albert@sinergy.co.id";
    public $admin_procruement = "tito@sinergy.co.id";
    public $wait_time = 20;
    public $latest_pr = 0;
    public $verifyData;
    public $countProduct = 0;
    
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Login');
        });
    }

    public function test_Draft_PR_Modal_Verify_Information_Supplier_Input($invoke = false) {
        $this->latest_pr = PRDraft::orderBy('id','DESC')->first()->id;
        // $this->latest_pr = 27;

        $request = new Request();
        $request->no_pr = $this->latest_pr;

        $controller = new PrDraftController();
        $this->verifyData = $controller->getPreviewPr($request);
        $this->countProduct = count($this->verifyData["product"]);

        if(!$invoke){
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->admin_procruement)->first())
                    ->visit($this->url)
                    ->assertSee('Draft List Purchase Request');
            });
        } else {
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->admin_procruement)->first())
                    ->visit($this->url)
                    ->pause(2000)
                    ->waitFor('#draftPr_length',$this->wait_time)
                    ->select("select[name='draftPr_length']",100)
                    ->click('.btnCekDraftDusk_' . $this->latest_pr)
                    ->waitFor('#ModalDraftPrAdmin',$this->wait_time)
                    ->pause(1000)
                    ->click("#to_cek ~ ins")
                    ->click("#type_cek ~ ins")
                    ->click("#email_cek ~ ins")
                    ->click("#category_Cek ~ ins")
                    ->click("#phone_cek ~ ins")
                    ->click("#attention_cek ~ ins")
                    ->click("#subject_cek ~ ins")
                    ->click("#address_cek ~ ins")
                    ->click("#methode_cek ~ ins")
                    ->assertValue("#to_cek","on")
                    ->assertValue("#type_cek","on")
                    ->assertValue("#email_cek","on")
                    ->assertValue("#category_Cek","on")
                    ->assertValue("#phone_cek","on")
                    ->assertValue("#attention_cek","on")
                    ->assertValue("#subject_cek","on")
                    ->assertValue("#address_cek","on")
                    ->assertValue("#methode_cek","on");
            });
        }
    }

    public function test_Draft_PR_Modal_Verify_Information_Product_Input($invoke = false) {

        if(!$invoke){
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->admin_procruement)->first())
                    ->visit($this->url)
                    ->assertSee('Draft List Purchase Request');
            });
        } else {
            $this->test_Draft_PR_Modal_Verify_Information_Supplier_Input(true);

            $this->browse(function (Browser $browser) {
                $browser->click('#nextBtnAddAdmin')
                    ->pause(1000)
                    ->assertPresent('.iCheck-helper');

                for ($i=0; $i < $this->countProduct; $i++) { 
                    $browser->click("#product_" . ($i+1) . "_cek ~ ins");
                }

                for ($i=0; $i < $this->countProduct; $i++) { 
                    $browser->assertValue("#product_" . ($i+1) . "_cek","on");
                }
            });
        }
    }

    public function test_Draft_PR_Modal_Verify_Before_TOP_Input($invoke = false) {
        if(!$invoke){
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->admin_procruement)->first())
                    ->visit($this->url)
                    ->assertSee('Draft List Purchase Request');
            });
        } else {
            $this->test_Draft_PR_Modal_Verify_Information_Product_Input(true);

            if($this->verifyData['pr']['type_of_letter'] == "EPR"){
                $this->browse(function (Browser $browser){
                    $browser->click('#nextBtnAddAdmin')
                        ->pause(1000);

                    $browser->click("#lead_cek ~ ins");
                    $browser->click("#pid_cek ~ ins");
                    $browser->click("#spk_cek ~ ins");
                    $browser->click("#sbe_cek ~ ins");
                    $browser->click("#quoSup_cek ~ ins");
                    $browser->click("#quoNum_cek ~ ins");
                    $browser->assertValue("#lead_cek","on");
                    $browser->assertValue("#pid_cek","on");
                    $browser->assertValue("#spk_cek","on");
                    $browser->assertValue("#sbe_cek","on");
                    $browser->assertValue("#quoSup_cek","on");
                    $browser->assertValue("#quoNum_cek","on");

                });
            } else {
                $this->browse(function (Browser $browser) {
                    $browser->click('#nextBtnAddAdmin')
                        ->pause(1000);

                    foreach($this->verifyData['dokumen'] as $data){
                        $browser->click("#doc_" . $data->id_dokumen . "_pendukung ~ ins");
                    }

                    foreach($this->verifyData['dokumen'] as $data){
                        $browser->assertValue("#doc_" . $data->id_dokumen . "_pendukung","on");
                    }
                });
            }
        }
    }

    public function test_Draft_PR_Modal_Verify_At_TOP_Input($invoke = false) {
        if(!$invoke){
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->admin_procruement)->first())
                    ->visit($this->url)
                    ->assertSee('Draft List Purchase Request');
            });
        } else {
            $this->test_Draft_PR_Modal_Verify_Before_TOP_Input(true);
            $this->browse(function (Browser $browser){
                $browser->click('#nextBtnAddAdmin')
                    ->pause(1000);

                $browser->click("#textarea_top_cek ~ ins");
            });
        }
    }

    public function test_Draft_PR_Modal_Verify_Review_Input($invoke = false) {
        if(!$invoke){
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->admin_procruement)->first())
                    ->visit($this->url)
                    ->assertSee('Draft List Purchase Request');
            });
        } else {
            $this->test_Draft_PR_Modal_Verify_At_TOP_Input(true);
            $this->browse(function (Browser $browser){
                $browser->click('#nextBtnAddAdmin')
                    ->pause(1000);
            });
        }
    }

    public function test_Draft_PR_Modal_Verify_Confirm_Input($invoke = false) {
        if(!$invoke){
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->admin_procruement)->first())
                    ->visit($this->url)
                    ->assertSee('Draft List Purchase Request');
            });
        } else {
            $this->test_Draft_PR_Modal_Verify_Review_Input(true);
            $this->browse(function (Browser $browser){
                $browser->click('#nextBtnAddAdmin')
                    ->pause(1000);

                $browser->click("#cbAllChecked ~ ins");

                $browser->click('#nextBtnAddAdmin')
                    ->waitFor(".swal2-popup")
                    ->click('button.swal2-confirm.swal2-styled.swal2-default-outline')
                    ->waitFor(".swal2-confirm.swal2-styled",$this->wait_time)
                    ->assertSee('Verify PR Successfully.')
                    ->click('.swal2-confirm.swal2-styled');
            });
        }
    }

    public function test_Draft_PR_Verify_All(){
        $this->test_Draft_PR_Modal_Verify_Confirm_Input(true);
    }
}
