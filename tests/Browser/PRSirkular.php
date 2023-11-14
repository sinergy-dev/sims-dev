<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PRSirkular extends DuskTestCase
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
}
