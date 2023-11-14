<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;

class PRDraftTest extends DuskTestCase
{

    public $url = "admin/draftPR";
    public $user = "vedro@sinergy.co.id";
    public $type_pr = "EPR"; 
    // public $type_pr = "IPR";
    public $wait_time = 30;
    public $loopProduct = 3;

    public $dataSupplier;
    public $dataProduct;

    public $dataSupplierEPR = array(
        'to' => 'PT. Westcon-Comstor Indonesa',
        'type' => "EPR",
        'email' => 'triza.anzola@westcon-comstore.co.id',
        'category' => 'Barang',
        'phone' => "123456789000",
        'fax' => "123456789000",
        'attention' => "Triza Anzola",
        'subject' => "Faiqoh Cantik",
        'address' => "MD Palace, MD Place Tower, Lt. 1,5, Unit 2, Jl. Setiabudi Selatan No. 7, RT.5/RW.1, Kuningan, Setia Budi, Jakarta Pusat, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12910",
        'methods' => 'purchase_order'
    );

    public $dataProductEPR = array(
        'product' => "NETSCOUT nGeniusEDGE Server",
        'description' => "Processor Single 22-Core processor\nMemory 6x 32GB DDR4 (192GB Total)\nStorage 32TB (RAID 5) 4x 8TB, 3.5” NL-SAS HDD, 512e sector size, 12 Gb/s\nManagement Port Two onboard Gigabit/10 Gigabit Ethernet LAN RJ45 ports (eth0)",
        'serial' => "-",
        'part' => "-",
        'qty' => "5",
        'type' => "pcs",
        'price' => "21000000"
    );

    public $dataSupplierIPR = array(
        'to' => 'Tokopedia - Studio Ponsel',
        'type' => "IPR",
        'email' => 'admin@tokopedia.com',
        'category' => 'Barang',
        'phone' => "123456789000",
        'fax' => "123456789000",
        'attention' => "Studio Ponsel",
        'subject' => "Pembelian Apple Macbook Air 2020 M1 Untuk Rama Agastya",
        'address' => "JL. Letjen Suprapto - Central Jakarta ITC Cempaka Mas 4th Floor\nBlok A No. 2, 10640, RW.8, Sumur Batu, \nKemayoran, Central Jakarta City, Jakarta 13220",
        'methods' => 'reimbursement'
    );

    public $dataProductIPR = array(
        'product' => "Apple Macbook Air 2020 M1 Chip 13 inch 256GB",
        'description' => "Apple M1 chip with 8core CPU, 7core GPU,\n8GB unified memory\n512GB SSD storage\nRetina display with True Tone\nMagic Keyboard\nTouch ID\nForce Touch trackpad\n",
        'serial' => "-",
        'part' => "-",
        'qty' => "1",
        'type' => "unit",
        'price' => "15025000"
    );

    public $dataProject = array(
        'leadId' => "GSPA220601",
        // 'leadId' => "BAFI220602",
        'pid' => "046/GSPA/SIP/VI/2022",
        // 'pid' => "131/BAFI/SIP/XII/2021",
        'spk' =>  __DIR__.'/source/SPK.pdf',
        'sbe' =>  __DIR__.'/source/SBE.pdf',
        'quo' =>  __DIR__.'/source/Quotation.pdf',
        'quo_num' => "0182/TAM/QO/V/2022"
    );

    public $dataDokumenPendukung = array(
        'penawaran' => __DIR__.'/source/Quotation.pdf',
        'dokumen_pendukung' => array(
            ['location' => __DIR__.'/source/IPR_Invoice_Laptop.png','name'=>'Invoice'],
            ['location' => __DIR__.'/source/IPR_Garansi.png','name'=>'Aktifasi Garansi'],
            ['location' => __DIR__.'/source/IPR_Bukti_tranver.jpg','name'=>'Bukti Tranver']
        )
    );

    /**
     * A Dusk test example.
     *
     * @return void
     */
    // public function test_Draft_PR_URL() {
    //     $this->browse(function (Browser $browser) {
    //         $browser->loginAs(User::where('email',$this->user)->first())
    //             ->visit($this->url)
    //             ->assertSee('Draft List Purchase Request');
    //     });
    // }

    // public function test_Draft_PR_Table() {
    //     $this->browse(function (Browser $browser) {
    //         $browser->loginAs(User::where('email',$this->user)->first())
    //             ->visit($this->url)
    //             ->assertPresent('#draftPr');
    //     });
    // }

    // public function test_Draft_PR_Modal_Button_Add() {
    //     $this->browse(function (Browser $browser) {
    //         $browser->loginAs(User::where('email',$this->user)->first())
    //             ->visit($this->url)
    //             ->assertPresent('#addDraftPr');
    //     });
    // }

    // public function test_Draft_PR_Modal_Add() {
    //     $this->browse(function (Browser $browser) {
    //         $browser->loginAs(User::where('email',$this->user)->first())
    //             ->visit($this->url)
    //             ->click('#addDraftPr')
    //             ->assertPresent('#ModalDraftPr');
    //     });
    // }

    public function test_Draft_PR_Modal_Add_Information_Supplier_Input($invoke = false) {
        if(!$invoke){
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->user)->first())
                    ->visit($this->url)
                    ->assertSee('Draft List Purchase Request');
            });
        } else {

            if($this->type_pr == "EPR"){
                $this->dataSupplier = $this->dataSupplierEPR;
                $this->dataProduct = $this->dataProductEPR;
            } else {
                $this->dataSupplier = $this->dataSupplierIPR;
                $this->dataProduct = $this->dataProductIPR;
            }

            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->user)->first())
                    ->visit($this->url)
                    ->click('#addDraftPr')
                    ->pause('1000')
                    ->assertPresent('#ModalDraftPr')
                    ->assertSee('Information Supplier')
                    ->type('input#inputTo.form-control',$this->dataSupplier['to'])
                    ->select('select#selectType.form-control',$this->dataSupplier['type'])
                    ->type('input#inputEmail.form-control',$this->dataSupplier['email'])
                    ->select('select#selectCategory.form-control.select2',$this->dataSupplier['category'])
                    ->type('#inputPhone',$this->dataSupplier['phone'])
                    ->type('#inputFax',$this->dataSupplier['fax'])
                    ->type('#inputAttention',$this->dataSupplier['attention'])
                    ->type('#inputSubject',$this->dataSupplier['subject'])
                    ->type('#inputAddress',$this->dataSupplier['address'])
                    ->select('#selectMethode',$this->dataSupplier['methods'])
                    ->click('#nextBtnAdd')
                    ->pause('1000')
                    ->click('button.swal2-confirm.swal2-styled.swal2-default-outline');
            });
        }
    }

    public function test_Draft_PR_Modal_Add_Information_Product_Input($invoke = false) {
        if(!$invoke){
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->user)->first())
                    ->visit($this->url)
                    ->assertSee('Draft List Purchase Request');
            });
        } else {
            $this->test_Draft_PR_Modal_Add_Information_Supplier_Input(true);
            
            $this->browse(function (Browser $browser) {
                $browser->pause('1000');

                for ($i=0; $i < $this->loopProduct; $i++) {
                    
                    if($i != 0){
                        $browser->click('button#addProduct.btn.btn-sm.btn-primary')
                        ->pause('1000');
                    }
                    
                    $browser->assertSee('Information Product')
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
                }
            });
        }

    }

    // public function test_Draft_PR_Modal_Add_Information_Product_Input_Multiple(){
    //     $this->test_Draft_PR_Modal_Add_Information_Supplier_Input();

    //     $this->browse(function (Browser $browser) {
    //         $browser->pause('1000')
    //             ->assertSee('Information Product')
    //             ->type('#inputNameProduct','NETSCOUT nGeniusEDGE Server')
    //             ->type('#inputDescProduct','Processor Single 22-Core processor\nMemory 6x 32GB DDR4 (192GB Total)\nStorage 32TB (RAID 5) 4x 8TB, 3.5” NL-SAS HDD, 512e sector size, 12 Gb/s\nManagement Port Two onboard Gigabit/10 Gigabit Ethernet LAN RJ45 ports (eth0)')
    //             ->type('#inputSerialNumber','-')
    //             ->type('#inputPartNumber','-')
    //             ->type('#inputQtyProduct','5')
    //             ->select('#selectTypeProduct','pcs')
    //             ->type('#inputPriceProduct','21000000')
    //             ->click('#nextBtnAdd')
    //             ->pause('3000')
    //             ->assertSee('Product')
    //             ->click('button#addProduct.btn.btn-sm.btn-primary')
    //             ->pause('1000')
    //             ->assertSee('Information Product')
    //             ->type('#inputNameProduct','NETSCOUT License')
    //             ->type('#inputDescProduct','nGeniusONE Service Assurance Platform\nRegulatory Model Number: NV51U, FCC Part 15 Class A, \nE Mark (EN55032 Class A, EN 55024, EN 61000')
    //             ->type('#inputSerialNumber','-')
    //             ->type('#inputPartNumber','-')
    //             ->type('#inputQtyProduct','5')
    //             ->select('#selectTypeProduct','unit')
    //             ->type('#inputPriceProduct','10000000')
    //             ->click('#nextBtnAdd')
    //             ->pause('3000')
    //             ->assertSee('Product');
    //     });
    // }

    public function test_Draft_PR_Modal_Add_Before_TOP($invoke = false){
        if(!$invoke){
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->user)->first())
                    ->visit($this->url)
                    ->assertSee('Draft List Purchase Request');
            });
        } else {
            $this->test_Draft_PR_Modal_Add_Information_Product_Input(true);

            if($this->dataSupplier['type'] == "EPR"){
                $this->browse(function (Browser $browser){
                    $browser->pause('1000')
                        ->click('#nextBtnAdd')
                        ->select("#selectLeadId",$this->dataProject['leadId'])
                        ->select("#selectPid",$this->dataProject['pid'])
                        ->attach('#inputSPK',$this->dataProject['spk'])
                        ->attach('#inputSBE',$this->dataProject['sbe'])
                        ->attach('#inputQuoteSupplier',$this->dataProject['quo'])
                        ->select("#selectQuoteNumber",$this->dataProject['quo_num'])
                        ->click('#nextBtnAdd')
                        ->waitFor('.wysihtml5-sandbox',$this->wait_time);
                        // ->pause('3000');
                });
            } else {
                $this->browse(function (Browser $browser) {
                    $browser->pause('1000')
                        ->click('#nextBtnAdd')
                        ->attach('#inputPenawaranHarga', $this->dataDokumenPendukung['penawaran']);

                    foreach($this->dataDokumenPendukung['dokumen_pendukung'] as $key => $doc){
                        $browser->click("#btnAddDocPendukung");
                        $browser->attach(".inputDocPendukung_" . $key,$doc['location']);
                        $browser->type(".inputNameDocPendukung_" . $key,$doc['name']);
                    }
                        
                    $browser->click('#nextBtnAdd')
                        ->waitFor('.wysihtml5-sandbox',$this->wait_time);
                });
            }
        }

    }

    public function test_Draft_PR_Modal_Add_At_TOP($invoke = false){
        if(!$invoke){
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->user)->first())
                    ->visit($this->url)
                    ->assertSee('Draft List Purchase Request');
            });
        } else {
            $this->test_Draft_PR_Modal_Add_Before_TOP(true);

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

    public function test_Draft_PR_Modal_Add_Review($invoke = true){
        if(!$invoke){
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::where('email',$this->user)->first())
                    ->visit($this->url)
                    ->assertSee('Draft List Purchase Request');
            });
        } else {
            $this->test_Draft_PR_Modal_Add_At_TOP(true);

            $this->browse(function (Browser $browser){
                $browser->pause('1000')
                    ->click('#nextBtnAdd')
                    ->waitFor(".swal2-popup")
                    ->click('button.swal2-confirm.swal2-styled.swal2-default-outline')
                    ->waitFor(".swal2-confirm.swal2-styled")
                    ->click('.swal2-confirm.swal2-styled')
                    ->assertSee('Draft List Purchase Request');;
            });
        }
    }
}
