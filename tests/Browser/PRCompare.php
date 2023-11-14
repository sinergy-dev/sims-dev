<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;
use App\PRDraft;
use App\Http\Controllers\PrDraftController;
use Illuminate\Http\Request;

class PRCompare extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */

    public $url = "admin/draftPR";
    public $user = "albert@sinergy.co.id";
    public $admin_procruement = "tito@sinergy.co.id";
    public $type_pr = "";
    public $wait_time = 20;
    public $latest_pr = 0;
    public $verifyData;
    public $countProduct = 0;

    public $dataSupplier = array(
        'to' => 'PT Central Data Technology',
        'email' => 'rama.cahya@cdt.co.id',
        'phone' => "0823402474440",
        'fax' => "123456789000",
        'attention' => "Rama Cahya",
        'subject' => "Pembelian 4 Netscout nGeniusEDGE Server - Test EPR Verify",
        'address' => "Centennial Tower 12th Floor, Jl. Gatot Subroto No.Kav. 24-25, RT.2/RW.2, Kuningan, Karet Semanggi, Setiabudi, South Jakarta City, Jakarta 12950",
        'note_pembanding' => 'Leadtime lebih bagus, cuman 1 bulan saja'
    );

    public $dataProduct = array(
        'product' => "NETSCOUT nGeniusEDGE Server",
        'description' => "Processor Single 22-Core processor\nMemory 6x 32GB DDR4 (192GB Total)\nStorage 32TB (RAID 5) 4x 8TB, 3.5” NL-SAS HDD, 512e sector size, 12 Gb/s\nManagement Port Two onboard Gigabit/10 Gigabit Ethernet LAN RJ45 ports (eth0)",
        'serial' => "-",
        'part' => "-",
        'qty' => "5",
        'type' => "pcs",
        'price' => "21000000"
    );

    public $dataProject = array(
        'quo' =>  __DIR__.'/source/Quotation_Pembanding.pdf'
    );

    public $dataDokumenPendukung = array(
        'dokumen_pendukung' => __DIR__.'/source/Penawaran_harga_tokopedia.png',
        // 'dokumen_pendukung' => __DIR__.'/source/Penawaran_harga.pdf',
    );

    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Login');
        });
    }

    public function test_PR_Compare_Open_Details($invoke = false) {
        $pr = PRDraft::orderBy('id','DESC')->first();
        $this->latest_pr = $pr->id;
        $this->type_pr = $pr->type_of_letter;

        $this->latest_pr = 34;

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
                    ->click('.btnDetailDusk_' . $this->latest_pr)
                    ->pause(1000)
                    ->assertPathBeginsWith('/admin/detail/draftPR/')
                    ->assertSee('Pembanding');
            });
        }
    }

    public function test_PR_Compare_Open_Button_Compare($invoke = false) {
        if(!$invoke){
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->admin_procruement)->first())
                    ->visit($this->url)
                    ->assertSee('Draft List Purchase Request');
            });
        } else {
            $this->test_PR_Compare_Open_Details(true);

            $this->browse(function (Browser $browser) {
                $browser->click('#btnPembanding')
                    ->pause(1000)
                    ->assertVisible('#btnAddPembanding');
            });
        }
    }

    public function test_PR_Compare_Modal_Input($invoke = false) {
        if(!$invoke){
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->admin_procruement)->first())
                    ->visit($this->url)
                    ->assertSee('Draft List Purchase Request');
            });
        } else {
            $this->test_PR_Compare_Open_Button_Compare(true);

            $this->browse(function (Browser $browser) {
                $browser->click('#btnAddPembanding')
                    ->pause(1000)
                    ->assertPresent('#ModalDraftPr')
                    ->assertSee('Information Supplier');
            });
        }
    }

    public function test_PR_Compare_Input_Supplier($invoke = false) {
        if(!$invoke){
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->admin_procruement)->first())
                    ->visit($this->url)
                    ->assertSee('Draft List Purchase Request');
            });
        } else {
            $this->test_PR_Compare_Modal_Input(true);

            $this->browse(function (Browser $browser) {
                $browser->pause(2000)
                    ->type('input#inputTo.form-control',$this->dataSupplier['to'])
                    ->type('input#inputEmail.form-control',$this->dataSupplier['email'])
                    ->type('#inputPhone',$this->dataSupplier['phone'])
                    ->type('#inputFax',$this->dataSupplier['fax'])
                    ->type('#inputAttention',$this->dataSupplier['attention'])
                    ->type('#inputSubject',$this->dataSupplier['subject'] . " - ". time())
                    ->type('#inputAddress',$this->dataSupplier['address'])
                    ->type('#note_pembanding',$this->dataSupplier['note_pembanding'])
                    ->click('#nextBtnAdd')
                    ->waitFor(".swal2-confirm.swal2-styled",$this->wait_time)
                    ->click('.swal2-confirm');
            });
        }
    }

    public function test_PR_Compare_Input_Product($invoke = false) {
        if(!$invoke){
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->admin_procruement)->first())
                    ->visit($this->url)
                    ->assertSee('Draft List Purchase Request');
            });
        } else {
            $this->test_PR_Compare_Input_Supplier(true);
            
            $this->browse(function (Browser $browser) {
                $browser->pause('1000')
                    ->assertSee('Information Product')
                    ->type('#inputNameProduct',$this->dataProduct['product'])
                    ->type('#inputDescProduct',$this->dataProduct['description'])
                    ->type('#inputSerialNumber',$this->dataProduct['serial'])
                    ->type('#inputPartNumber',$this->dataProduct['part'])
                    ->type('#inputQtyProduct',$this->dataProduct['qty'])
                    ->select('#selectTypeProduct',$this->dataProduct['type'])
                    ->type('#inputPriceProduct',$this->dataProduct['price'])
                    ->click('#nextBtnAdd')
                    ->pause('3000')
                    ->assertSee('Product');
            });
        }
    }

    public function test_PR_Compare_Input_Before_TOP($invoke = false){
        if(!$invoke){
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->admin_procruement)->first())
                    ->visit($this->url)
                    ->assertSee('Draft List Purchase Request');
            });
        } else {
            $this->test_PR_Compare_Input_Product(true);

            if($this->type_pr == "EPR"){
                $this->browse(function (Browser $browser){
                    $browser->pause('1000')
                        ->click('#nextBtnAdd')
                        ->assertPresent('#inputQuoteSupplier')
                        ->attach('#inputQuoteSupplier',$this->dataProject['quo'])
                        ->click('#nextBtnAdd')
                        ->waitFor('.wysihtml5-sandbox',$this->wait_time);
                        // ->pause('3000');
                });
            } else {
                $this->browse(function (Browser $browser) {
                    $browser->pause('1000')
                        ->click('#nextBtnAdd')
                        ->attach('#inputPenawaranHarga', $this->dataDokumenPendukung['dokumen_pendukung'])
                        ->pause('5000')
                        ->click('#nextBtnAdd')
                        ->waitFor('.wysihtml5-sandbox',$this->wait_time);
                });
            }
        }

    }

    public function test_PR_Compare_Input_At_TOP($invoke = false){
        if(!$invoke){
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->user)->first())
                    ->visit($this->url)
                    ->assertSee('Draft List Purchase Request');
            });
        } else {
            $this->test_PR_Compare_Input_Before_TOP(true);

            $this->browse(function (Browser $browser){
                $browser->pause('1000')
                    // ->type('#textAreaTOP',"1. The prices quoted here are franco Jakarta, delivery charges outside Jakarta will be borne by buyer.<br>2. The prices quoted are Included 10% VAT and Excluded all other applicable government taxes.<br>3. Payment shall be made according to the following terms:<br>    - 100% of total Project - signed on PO.<br>    - If the goods are ready and there are no instruction to deliver after 14 days, the buyer still have to pay the final payment, and penalty will be charged amounting 1,5% per month for any late payment.<br>4. Payment shall be made in IDR currency by bank transfer.<br>5. Delivery time: 8-10 weeks after PO signed & Payment Received.<br>6. Purchase Orders from buyer have to put the quotation number and term of payment given by VTI.<br>7. Purchase Orders from buyer are non cancellable, any payments are non-refundable and goods delivered are non-returnable.<br>8. All orders are subject to acceptance by VTI. VTI may terminate all order under specific circumstances prior to written notice.")
                    ->keys('.wysihtml5-sandbox',"1. The prices quoted here are franco Jakarta, delivery charges outside Jakarta will be borne by buyer.","{enter}","2. The prices quoted are Included 10% VAT and Excluded all other applicable government taxes.","{enter}","3. Payment shall be made according to the following terms:","{enter}","    - 100% of total Project - signed on PO.","{enter}","    - If the goods are ready and there are no instruction to deliver after 14 days, the buyer still have to pay the final payment, and penalty will be charged amounting 1,5% per month for any late payment.","{enter}","4. Payment shall be made in IDR currency by bank transfer.","{enter}","5. Delivery time: 8-10 weeks after PO signed & Payment Received.","{enter}","6. Purchase Orders from buyer have to put the quotation number and term of payment given by VTI.","{enter}","7. Purchase Orders from buyer are non cancellable, any payments are non-refundable and goods delivered are non-returnable.","{enter}","8. All orders are subject to acceptance by VTI. VTI may terminate all order under specific circumstances prior to written notice.")
                    // ->script('$("#textAreaTOP").val("1. The prices quoted here are franco Jakarta, delivery charges outside Jakarta will be borne by buyer.<br>2. The prices quoted are Included 10% VAT and Excluded all other applicable government taxes.<br>3. Payment shall be made according to the following terms:<br>    - 100% of total Project - signed on PO.<br>    - If the goods are ready and there are no instruction to deliver after 14 days, the buyer still have to pay the final payment, and penalty will be charged amounting 1,5% per month for any late payment.<br>4. Payment shall be made in IDR currency by bank transfer.<br>5. Delivery time: 8-10 weeks after PO signed & Payment Received.<br>6. Purchase Orders from buyer have to put the quotation number and term of payment given by VTI.<br>7. Purchase Orders from buyer are non cancellable, any payments are non-refundable and goods delivered are non-returnable.<br>8. All orders are subject to acceptance by VTI. VTI may terminate all order under specific circumstances prior to written notice.")')
                    ->click('#nextBtnAdd')
                    ->waitFor('#headerPreviewFinal')
                    ->assertPresent("#headerPreviewFinal");
            });
        }
    }

    public function test_PR_Compare_Input_Review($invoke = true){
        if(!$invoke){
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->user)->first())
                    ->visit($this->url)
                    ->assertSee('Draft List Purchase Request');
            });
        } else {
            $this->test_PR_Compare_Input_At_TOP(true);

            $this->browse(function (Browser $browser){
                $browser->pause('1000')
                    ->click('#nextBtnAdd')
                    ->waitFor(".swal2-popup")
                    ->click('.swal2-confirm')
                    ->waitFor(".swal2-confirm.swal2-styled")
                    ->assertSee('Verify PR Successfully.')
                    ->click('.swal2-confirm');
            });
        }
    }
}
