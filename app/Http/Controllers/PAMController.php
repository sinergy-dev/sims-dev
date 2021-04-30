<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\pam;
use App\pamProduk;
use App\pamProgress;
use PDF;
use Excel;
use App\PR;
use App\PONumber;
use App\POAsset;
use App\Inventory;

class PAMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

         if (Auth::User()->id_position == 'ADMIN') {
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'ADMIN')
                            ->get();
        } elseif (Auth::User()->id_position == 'HR MANAGER') {
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'HRD')
                            ->get();
        } elseif (Auth::User()->id_division == 'FINANCE') {
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'FINANCE')
                            ->get();
        }

        if ($div == 'HR' && $pos == 'HR MANAGER' || $div == 'FINANCE' && $pos == 'STAFF') {
            $pam = DB::table('dvg_pam')
                ->join('users','users.nik','=','dvg_pam.personel')
                ->join('tb_pr','tb_pr.no','=','dvg_pam.no_pr')
                ->select('dvg_pam.id_pam','dvg_pam.date','tb_pr.no_pr','dvg_pam.ket_pr','dvg_pam.note_pr','dvg_pam.to_agen','dvg_pam.status','users.name','dvg_pam.subject','dvg_pam.amount')
                ->get();
        } elseif ($pos == 'ADMIN') {
            $pam = DB::table('dvg_pam')
                    ->join('users', 'users.nik', '=', 'dvg_pam.personel')
                    ->join('tb_pr', 'dvg_pam.no_pr', '=', 'tb_pr.no')
                    ->join('tb_po_asset', 'tb_po_asset.id_pr_asset', '=', 'dvg_pam.id_pam')
                    ->select('dvg_pam.id_pam','dvg_pam.date','tb_pr.no_pr','dvg_pam.ket_pr','dvg_pam.note_pr','dvg_pam.to_agen','dvg_pam.status','users.name','dvg_pam.subject','dvg_pam.amount', 'tb_pr.type_of_letter', 'tb_po_asset.id_po_asset')
                    ->where('dvg_pam.nik_admin', $nik)
                    ->get();

        }

        $no_pr = DB::table('tb_pr')
        		->select('result','no_pr','no')
        		->where('result','T')
        		->get();

        $pams = DB::table('dvg_pam')
            ->select('id_pam')
            ->get();

        $produks = DB::table('dvg_pam')
            ->join('dvg_pr_product','dvg_pr_product.id_pam','=','dvg_pam.id_pam')
            ->select('dvg_pr_product.name_product','dvg_pr_product.qty','dvg_pr_product.id_pam','dvg_pr_product.nominal')
            ->get();

        $sum = DB::table('dvg_pam')
            ->select('id_pam')
            ->sum('id_pam');

        $count_product = DB::table('dvg_pr_product')
            ->select('id_product')
            ->sum('id_product');

        $total_amount = DB::table('dvg_pr_product')
                    ->select('nominal')
                    ->sum('nominal');

        $project_id = DB::table('tb_id_project')
                        ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                        ->join('users','users.nik','=','sales_lead_register.nik')
                        ->select('id_project')
                        ->where('id_company', '1')
                        ->get();

        return view('DVG/pam/pam',compact('notifClaim','pam','produks','pams','sum','id_pam','count_product','total_amount','no_pr','total_amount', 'project_id'));
    }


    public function add_pam(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

         if (Auth::User()->id_position == 'ADMIN') {
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'ADMIN')
                            ->get();
        } elseif (Auth::User()->id_position == 'HR MANAGER') {
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'HRD')
                            ->get();
        } elseif (Auth::User()->id_division == 'FINANCE') {
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'FINANCE')
                            ->get();
        }

        $from = DB::table('users')
                ->select('nik', 'name')
                ->where('id_company', '2')
                ->get();

        $project_id = DB::table('tb_id_project')
                        ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                        ->join('users','users.nik','=','sales_lead_register.nik')
                        ->select('id_project')
                        ->where('id_company', '1')
                        ->get();

        $barang = Inventory::select('id_product','nama')->get();

        return view('/admin/add_pam',compact('from', 'project_id', 'barang', 'notifClaim'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function detail_pam($id_pam)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $notifClaim = '';

         if (Auth::User()->id_position == 'ADMIN') {
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'ADMIN')
                            ->get();
        } elseif (Auth::User()->id_position == 'HR MANAGER') {
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'HRD')
                            ->get();
        } elseif (Auth::User()->id_division == 'FINANCE') {
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'FINANCE')
                            ->get();
        }

        $detail_pam = DB::table('dvg_pam_progress')
                    ->join('users', 'dvg_pam_progress.nik', '=', 'users.nik')
                    ->select('dvg_pam_progress.id_progress','dvg_pam_progress.created_at','dvg_pam_progress.keterangan','dvg_pam_progress.status','users.name')
                    ->where('dvg_pam_progress.id_pam',$id_pam)
                    ->get();

        $tampilkan = DB::table('dvg_pam')
                    ->join('users', 'users.nik', '=', 'dvg_pam.personel')
                    ->join('tb_pr','tb_pr.no_pr','=','dvg_pam.no_pr')
                    ->join('dvg_pr_product','dvg_pr_product.id_pam','=','dvg_pam.id_pam')
                    ->select('dvg_pam.id_pam','tb_pr.no_pr','dvg_pam.nik_admin','dvg_pam.date', 'dvg_pam.to_agen', 'dvg_pr_product.nominal','dvg_pam.ket_pr','dvg_pam.status')
                    ->where('dvg_pam.id_pam',$id_pam)
                    ->first();

        $produks = DB::table('dvg_pam')
            ->join('dvg_pr_product','dvg_pr_product.id_pam','=','dvg_pam.id_pam')
            ->select('dvg_pr_product.id_product','dvg_pr_product.name_product','dvg_pr_product.qty','dvg_pr_product.id_pam','dvg_pr_product.nominal','dvg_pr_product.total_nominal','dvg_pr_product.description', 'dvg_pr_product.name_product_customer', 'dvg_pr_product.nominal_customer', 'dvg_pr_product.total_nominal_customer', 'dvg_pr_product.desc_customer', 'dvg_pr_product.qty_customer')
            ->where('dvg_pam.id_pam',$id_pam)
            ->where('dvg_pr_product.name_product', '!=', '')
            ->get();

        $produks_cus = DB::table('dvg_pam')
            ->join('dvg_pr_product','dvg_pr_product.id_pam','=','dvg_pam.id_pam')
            ->join('tb_pr', 'dvg_pam.no_pr', '=', 'tb_pr.no')
            ->select('dvg_pr_product.id_product', 'dvg_pr_product.name_product_customer', 'dvg_pr_product.nominal_customer', 'dvg_pr_product.total_nominal_customer', 'dvg_pr_product.desc_customer', 'dvg_pr_product.qty_customer', 'tb_pr.type_of_letter')
            ->where('dvg_pam.id_pam',$id_pam)
            ->where('dvg_pr_product.name_product_customer', '!=', '')
            ->get();

        $total_produk = DB::table('dvg_pam')
            ->join('dvg_pr_product','dvg_pr_product.id_pam','=','dvg_pam.id_pam')
            ->select('dvg_pr_product.id_product','dvg_pr_product.name_product','dvg_pr_product.qty','dvg_pr_product.id_pam','dvg_pr_product.nominal','dvg_pr_product.total_nominal','dvg_pr_product.description')
            ->where('dvg_pam.id_pam',$id_pam)
            ->count('name_product');


        $total_amount = DB::table('dvg_pr_product')
                    ->select('total_nominal')
                    ->where('id_pam',$id_pam)
                    ->sum('total_nominal');

        $count_pam = DB::table('dvg_pr_product')
                    ->where('id_pam',$id_pam)
                    ->count('name_product');

        return view('DVG/pam/detail_pam',compact('count_pam','total_produk','detail_pam', 'nomor', 'tampilkan','produks','total_amount', 'produks_cus', 'notifClaim'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  
    public function tambah(Request $request)
        {
        $type = $request['type_of_letter'];
        $posti = $request['position'];
        $month_pr = substr($request['date_supplier'],5,2);
        $year_pr = substr($request['date_supplier'],0,4);

        $array_bln = array('01' => "I",
                            '02' => "II",
                            '03' => "III",
                            '04' => "IV",
                            '05' => "V",
                            '06' => "VI",
                            '07' => "VII",
                            '08' => "VIII",
                            '09' => "IX",
                            '10' => "X",
                            '11' => "XI",
                            '12' => "XII");
        $bln = $array_bln[$month_pr];

        $getnumber = PR::orderBy('no', 'desc')->first();
        $getnumber_po = PONumber::orderBy('no', 'desc')->first();


//Penomoran PO
        if($getnumber_po == NULL){
            $getlastnumber_po = 1;
            $lastnumber_po = $getlastnumber_po;
        } else{
            $lastnumber_po = $getnumber_po->no+1;
        }

        if($lastnumber_po < 10){
           $akhirnomor_po = '000' . $lastnumber_po;
        }elseif($lastnumber_po > 9 && $lastnumber_po < 100){
           $akhirnomor_po = '00' . $lastnumber_po;
        }elseif($lastnumber_po >= 100){
           $akhirnomor_po = '0' . $lastnumber_po;
        }

        $no_po = $akhirnomor_po.'/'. 'FA' . '/' . 'PO' .'/'. $bln . '/' . $year_pr;

        $tambah_nopo = new PONumber();
        $tambah_nopo->no = $lastnumber_po;
        $tambah_nopo->no_po = $no_po;
        $tambah_nopo->month = $bln;
        $tambah_nopo->position = 'FA';
        $tambah_nopo->type_of_letter = 'PO';
        $tambah_nopo->date = $request['date_supplier'];
        if ($request['to_agen_supp_intern'] != '') {
            $tambah_nopo->to = $request['to_agen_supp_intern']; 
        } elseif ($request['to_agen_supplier'] != '') {
            $tambah_nopo->to = $request['to_agen_supplier']; 
        }
        if ($request['attention_supp_intern'] != '') {
            $tambah_nopo->attention     = $request['attention_supp_intern'];
        }elseif ($request['attention_supplier'] != '') {
            $tambah_nopo->attention     = $request['attention_supplier'];
        }
        if ($request['project_supp_intern'] != '') {
            $tambah_nopo->project = $request['project_supp_intern'];
        }elseif ($request['attention_supplier'] != '') {
            $tambah_nopo->project = $request['attention_supplier'];
        }
        $tambah_nopo->from = Auth::User()->nik;
        if ($request['project_id_supp_intern'] != '') {
            $tambah_nopo->project_id = $request['project_id_supp_intern'];
        } elseif ($request['project_id_supplier'] != '') {
            $tambah_nopo->project_id = $request['project_id_supplier'];
        }
        $tambah_nopo->save();


// Penomoran PR
        if($getnumber == NULL){
            $getlastnumber = 1;
            $lastnumber = $getlastnumber;
        } else{
            $lastnumber = $getnumber->no+1;
        }

        if($lastnumber < 10){
           $akhirnomor = '000' . $lastnumber;
        }elseif($lastnumber > 9 && $lastnumber < 100){
           $akhirnomor = '00' . $lastnumber;
        }elseif($lastnumber >= 100){
           $akhirnomor = '0' . $lastnumber;
        }

        $no_pr = $akhirnomor.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_pr;
        

        $lastnopo = PONumber::select('no')->orderby('created_at','desc')->first();

        $tambah = new PR();
        $tambah->no = $lastnumber;
        $tambah->no_pr = $no_pr;
        $tambah->no_po = $lastnopo->no;
        $tambah->type_of_letter = $type;
        $tambah->month = $bln;
        $tambah->position = $posti;
        $tambah->date = $request['date_supplier'];
        if ($request['to_agen_supp_intern'] != '') {
            $tambah->to = $request['to_agen_supp_intern']; 
        } elseif ($request['to_agen_supplier'] != '') {
            $tambah->to = $request['to_agen_supplier']; 
        }
        if ($request['attention_supp_intern'] != '') {
            $tambah->attention = $request['attention_supp_intern'];
        }elseif ($request['attention_supplier'] != '') {
            $tambah->attention = $request['attention_supplier'];
        }
        if ($request['project_supp_intern'] != '') {
            $tambah->project = $request['project_supp_intern'];
        }elseif ($request['attention_supplier'] != '') {
            $tambah->project = $request['attention_supplier'];
        }
        $tambah->from = Auth::User()->nik;
        if ($request['project_id_supp_intern'] != '') {
            $tambah->project_id = $request['project_id_supp_intern'];
        } elseif ($request['project_id_supplier'] != '') {
            $tambah->project_id = $request['project_id_supplier'];
        }
        $tambah->result = 'T';
        $tambah->save();


//PR Asset MSP
        $terms = $request['term'];

        $lastnopr = PR::select('no')->orderby('created_at','desc')->first();
        $tambah_pam = new pam();
        $tambah_pam->nik_admin     = Auth::User()->nik;
        $tambah_pam->no_pr         = $lastnopr->no;
        $tambah_pam->date = $request['date_supplier'];
        if ($request['to_agen_supp_intern'] != '') {
            $tambah_pam->to_agen = $request['to_agen_supp_intern']; 
        } elseif ($request['to_agen_supplier'] != '') {
            $tambah_pam->to_agen = $request['to_agen_supplier']; 
        }
        $tambah_pam->ket_pr        = $request['ket'];

        if ($request['owner_pr_supp_intern'] != '') {
            $tambah_pam->personel      = $request['owner_pr_supp_intern'];
        } elseif ($request['owner_pr_supplier'] != '') {
            $tambah_pam->personel      = $request['owner_pr_supplier'];
        }

        if ($request['subject_supp_intern'] != '') {
            $tambah_pam->subject       = $request['subject_supp_intern'];
        } elseif ($request['subject_supplier'] != '') {
            $tambah_pam->subject       = $request['subject_supplier'];
        }
        $tambah_pam->status        = 'NEW';
        if ($request['address_supp_intern'] != '') {
            $tambah_pam->address       = $request['address_supp_intern'];
        } elseif ($request['address_supplier'] != '') {
            $tambah_pam->address       = $request['address_supplier'];
        }
        if ($request['telp_supp_intern'] != '') {
            $tambah_pam->telp          = $request['telp_supp_intern'];
        } elseif ($request['telp_supplier'] != '') {
            $tambah_pam->telp          = $request['telp_supplier'];
        }
        if ($request['fax_supp_intern'] != '') {
            $tambah_pam->fax           = $request['fax_supp_intern'];
        } elseif ($request['fax_supplier'] != '') {
            $tambah_pam->fax           = $request['fax_supplier'];
        }
        if ($request['email_supp_intern'] != '') {
            $tambah_pam->email         = $request['email_supp_intern'];
        } elseif ($request['email_supplier'] != '') {
            $tambah_pam->email         = $request['email_supplier'];
        }
        if ($request['attention_supp_intern'] != '') {
            $tambah_pam->attention     = $request['attention_supp_intern'];
        }elseif ($request['attention_supplier'] != '') {
            $tambah_pam->attention     = $request['attention_supplier'];
        }
        if ($request['project_id_supp_intern'] != '') {
            $tambah_pam->project_id    = $request['project_id_supp_intern'];
        } elseif ($request['project_id_supplier'] != '') {
            $tambah_pam->project_id    = $request['project_id_supplier'];
        }
        if ($request['ppn_internal'] != '') {
            $tambah_pam->ppn           = $request['ppn_internal'];
        } elseif ($request['ppn'] != '') {
            $tambah_pam->ppn           = $request['ppn'];
        }
        if ($request['term_supp_intern'] != '') {
            $tambah_pam->terms         = nl2br($request['term_supp_intern']);
        }elseif ($request['term_supplier'] != '') {
            $tambah_pam->terms         = nl2br($request['term_supplier']);
        }
        $tambah_pam->to_customer   = $request['to_agen_customer'];
        $tambah_pam->addr_customer = $request['address_customer'];
        $tambah_pam->telp_customer = $request['telp_customer'];
        $tambah_pam->fax_customer  = $request['fax_customer'];
        $tambah_pam->email_customer= $request['email_customer'];
        $tambah_pam->attn_customer = $request['attention_customer'];
        $tambah_pam->ppn_customer  = $request['ppn_customer']; 
        $tambah_pam->save();

//PO Asset MSP
        $last_id_pam = pam::select('id_pam')->orderby('created_at','desc')->first();
        $tambah_poasset = new POAsset();
        $tambah_poasset->nik_admin     = Auth::User()->nik;
        $tambah_poasset->no_pr         = $lastnopr->no;
        $tambah_poasset->no_po         = $lastnopo->no;
        $tambah_poasset->id_pr_asset   = $last_id_pam->id_pam;
        if ($request['to_agen_supp_intern'] != '') {
            $tambah_poasset->to_agen = $request['to_agen_supp_intern']; 
        } elseif ($request['to_agen_supplier'] != '') {
            $tambah_poasset->to_agen = $request['to_agen_supplier']; 
        }
        if ($request['subject_supp_intern'] != '') {
            $tambah_poasset->subject       = $request['subject_supp_intern'];
        } elseif ($request['subject_supplier'] != '') {
            $tambah_poasset->subject       = $request['subject_supplier'];
        }
        $tambah_poasset->status_po     = 'NEW';
        if ($request['address_supp_intern'] != '') {
            $tambah_poasset->address       = $request['address_supp_intern'];
        } elseif ($request['address_supplier'] != '') {
            $tambah_poasset->address       = $request['address_supplier'];
        }
        if ($request['telp_supp_intern'] != '') {
            $tambah_poasset->telp          = $request['telp_supp_intern'];
        } elseif ($request['telp_supplier'] != '') {
            $tambah_poasset->telp          = $request['telp_supplier'];
        }
        if ($request['fax_supp_intern'] != '') {
            $tambah_poasset->fax           = $request['fax_supp_intern'];
        } elseif ($request['fax_supplier'] != '') {
            $tambah_poasset->fax           = $request['fax_supplier'];
        }
        if ($request['email_supp_intern'] != '') {
            $tambah_poasset->email         = $request['email_supp_intern'];
        } elseif ($request['email_supplier'] != '') {
            $tambah_poasset->email         = $request['email_supplier'];
        }
        if ($request['attention_supp_intern'] != '') {
            $tambah_poasset->attention     = $request['attention_supp_intern'];
        }elseif ($request['attention_supplier'] != '') {
            $tambah_poasset->attention     = $request['attention_supplier'];
        }
        if ($request['project_id_supp_intern'] != '') {
            $tambah_poasset->project_id    = $request['project_id_supp_intern'];
        } elseif ($request['project_id_supplier'] != '') {
            $tambah_poasset->project_id    = $request['project_id_supplier'];
        }
        $tambah_poasset->save();


//Progress PR Asset
        $lastInsertedId = $tambah_pam->id_pam;
        $tambahprogress = new pamProgress();
        $tambahprogress->id_pam = $lastInsertedId;
        $tambahprogress->nik = Auth::User()->nik;
        $tambahprogress->keterangan = $request['ket'];
        $tambahprogress->status = 'ADMIN';
        $tambahprogress->save();

        return redirect('pr_asset')->with('success', 'Create PR Asset Successfully!');
    }

    public function store_produk(Request $request)
    {
        $id_pam = $request['id_pam_set'];
        
        $produk     = $request->name_product;
        $qty        = $request->qty;
        $nominal    = $request->nominal;
        $ket        = $request->ket;

        if(count($produk) > count($qty))
            $count = count($qty);
        else $count = count($produk);

        for($i = 0; $i < $count; $i++){
            $data = array(
                'id_pam'  => $id_pam,
                'name_product' => $produk[$i],
                'qty' => $qty[$i],
                'nominal'   => str_replace(',', '', $nominal[$i]),
                'total_nominal' => $qty[$i] * str_replace(',', '', $nominal[$i]),
                'description'   => $ket[$i],
            );

            $insertData[] = $data;
        }
        pamProduk::insert($insertData);

        $update = pam::where('id_pam',$id_pam)->first();
        $update->status     = 'ADMIN';
        $update->update();

        return redirect('pr_asset')->with('success', 'Add Product Successfully!');
    }

    public function store_produk_cus(Request $request)
    {
        $id_pam = $request['id_pam_cus'];
        
        $produk     = $request->name_product_cus;
        $qty        = $request->qty_cus;
        $nominal    = $request->nominal_cus;
        $ket        = $request->ket_cus;

        if(count($produk) > count($qty))
            $count = count($qty);
        else $count = count($produk);

        for($i = 0; $i < $count; $i++){
            $data = array(
                'id_pam'  => $id_pam,
                'name_product_customer' => $produk[$i],
                'qty_customer' => $qty[$i],
                'nominal_customer'   => str_replace(',', '', $nominal[$i]),
                'total_nominal_customer' => $qty[$i] * str_replace(',', '', $nominal[$i]),
                'desc_customer'   => $ket[$i],
            );

            $insertData[] = $data;
        }
        pamProduk::insert($insertData);

        // $update = pam::where('id_pam',$id_pam)->first();
        // $update->status     = 'ADMIN';
        // $update->update();

        return redirect('pr_asset')->with('success', 'Add Product Successfully!');
    }

    public function update_produk(Request $request){

        $id_produk  = $request['id_product_update'];
        $produk     = $request->name_product_update;
        $qty        = $request->qty_update;
        $nominal    = $request->nominal_update;

        if(count($produk) > count($qty))
            $count = count($qty);
        else $count = count($produk);

        for($i = 0; $i < $count; $i++){
            $data = array(
                'name_product' => $produk[$i],
                'qty' => $qty[$i],
                'nominal'   => str_replace(',', '', $nominal[$i]),
            );

            $insertData[] = $data;
        }

        DB::table('dvg_pr_product')->whereIn('id_product', $id_produk)->update($insertData[]);

        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id_pam = $request['id_pam'];

        $update = pam::where('id_pam', $id_pam)->first();
        $update->date = $request['date_handover_edit'];
        $update->to_agen       = $request['to_agen_edit'];
        $update->due_date      = $request['due_date_edit'];
        $update->ket_pr        = $request['ket_edit'];
        $update->note_pr       = $request['note_edit'];
        $update->update();

        return redirect('pr_asset');
        //
    }

    public function assign_to_hrd(Request $request){
    	$id_pam = $request['assign_to_hrd_edit'];

        $total_amount = pamProduk::select('total_nominal')
                ->where('id_pam',$id_pam)
                ->sum('total_nominal');

    	$update = pam::where('id_pam',$id_pam)->first();
    	$update->status    = 'HRD';
        $update->amount    = $total_amount;
    	$update->update();

        $tambah = new pamProgress();
        $tambah->id_pam = $id_pam;
        $tambah->nik = Auth::User()->nik;
        $tambah->keterangan = $request['keterangan'];
        $tambah->status = 'HRD';
        $tambah->save();

    	return redirect()->back();
    }

    public function assign_to_fnc(Request $request)
    {
        $id_pam = $request['assign_to_fnc_edit'];
        // $no = $request['no_return_fnc'];
        $id_po_asset = $request['status_po'];
        
        $update = pam::where('id_pam',$id_pam)->first();
        $update->status = 'FINANCE';
        $update->update();

        $tambah = new pamProgress();
        $tambah->id_pam = $id_pam;
        $tambah->nik = Auth::User()->nik;
        $tambah->keterangan = $request['keterangan'];
        $tambah->status = 'FINANCE';
        $tambah->amount = $request['amount'];
        $tambah->save();

        $update_status = POAsset::where('id_po_asset', $id_po_asset)->first();
        $update_status->status_po = 'FINANCE';
        $update_status->update();

        return redirect('pr_asset')->with('success', 'Submit PR Asset Successfully!');
    }

     public function assign_to_adm(Request $request)
    {
        $id_pam = $request['assign_to_adm_edit'];

        $tambah = new pamProgress();
        $tambah->id_pam = $id_pam;
        $tambah->nik = Auth::User()->nik;
        $tambah->keterangan = $request['keterangan'];
        $tambah->status = 'TRANSFER';
        $tambah->amount = $request['amount'];
        $tambah->save();

        $update = pam::where('id_pam', $id_pam)->first();
        $update->status = 'TRANSFER';
        $update->update();

        return redirect()->back();
    }

    public function tambah_return_hr(Request $request)
    {
        $id_pam = $request['no_return_hr'];

        $update = pam::where('id_pam', $id_pam)->first();
        $update->status = 'ADMIN';
        $update->update();

        $tambah = new pamProgress();
        $tambah->id_pam = $id_pam;
        $tambah->nik = Auth::User()->nik;
        $tambah->keterangan = $request['keterangan'];
        $tambah->status = 'ADMIN';
        $tambah->save();

        return redirect()->back();
    }

    public function tambah_return_fnc(Request $request)
    {
        $id_pam = $request['no_return_fnc'];
        $tambah = new pamProgress();
        $tambah->id_pam = $id_pam;
        $tambah->nik = Auth::User()->nik;
        $tambah->keterangan = $request['keterangan'];
        $tambah->status = 'HRD';
        $tambah->save();

        $update = pam::where('id_pam', $id_pam)->first();
        $update->status = 'HRD';
        $update->update();

        return redirect()->back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id_pam = $request['id_pam_asset'];
        $hapus = pam::find($id_pam);
        $hapus->delete();

        $no = $request['no_pr_asset'];
        $update = PR::where('no_pr', $no)->first();
        $update->result = 'T';
        $update->update();

        return redirect()->back();  
    }

    public function downloadPDF()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $datas = DB::table('dvg_pam')
            ->join('users','users.nik','=','dvg_pam.personel')
            ->join('tb_pr','tb_pr.no','=','dvg_pam.no_pr')
            ->select('dvg_pam.id_pam','dvg_pam.date','tb_pr.no_pr','dvg_pam.ket_pr','dvg_pam.note_pr','dvg_pam.to_agen','dvg_pam.status','users.name','dvg_pam.subject','users.name')
            ->where('nik_admin', $nik)
            ->get();

        $produks = DB::table('dvg_pam')
            ->join('dvg_pr_product','dvg_pr_product.id_pam','=','dvg_pam.id_pam')
            ->select('dvg_pr_product.name_product','dvg_pr_product.qty','dvg_pr_product.id_pam','dvg_pr_product.nominal','dvg_pr_product.total_nominal')
            ->get();

        $total_amounts = DB::table('dvg_pr_product')
                    ->select('nominal')
                    ->sum('nominal');

        $total_amount = "Rp " . number_format($total_amounts,2,',','.');

        $pdf = PDF::loadView('DVG.pam.pr_asset_pdf', compact('datas','produks','total_amount'));
        return $pdf->download('Purchase Request Asset Management'.date("d-m-Y").'.pdf');
    }

    public function downloadPDF2($id_pam)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $datas = DB::table('dvg_pam')
            ->join('users','users.nik','=','dvg_pam.personel')
            ->join('tb_pr','tb_pr.no','=','dvg_pam.no_pr')
            ->select('dvg_pam.id_pam','dvg_pam.date','tb_pr.no_pr','dvg_pam.ket_pr','dvg_pam.note_pr','dvg_pam.to_agen','dvg_pam.status','users.name','dvg_pam.subject', 'users.id_division', 'users.id_position', 'dvg_pam.to_customer', 'dvg_pam.addr_customer', 'dvg_pam.telp_customer', 'dvg_pam.fax_customer', 'dvg_pam.attn_customer', 'dvg_pam.email_customer','dvg_pam.address', 'dvg_pam.telp', 'dvg_pam.fax', 'dvg_pam.email', 'dvg_pam.attention', 'dvg_pam.project', 'dvg_pam.project_id', 'dvg_pam.terms')
            ->where('nik_admin', $nik)
            ->where('dvg_pam.id_pam', $id_pam)
            ->first();

        $produks = DB::table('dvg_pam')
            ->join('dvg_pr_product','dvg_pr_product.id_pam','=','dvg_pam.id_pam')
            ->select('dvg_pr_product.name_product','dvg_pr_product.qty','dvg_pr_product.id_pam','dvg_pr_product.nominal','dvg_pr_product.total_nominal', 'dvg_pr_product.description')
            ->where('dvg_pam.id_pam',$id_pam)
            ->get();

        $produks_cus = DB::table('dvg_pam')
            ->join('dvg_pr_product','dvg_pr_product.id_pam','=','dvg_pam.id_pam')
            ->join('tb_pr', 'dvg_pam.no_pr', '=', 'tb_pr.no')
            ->select('dvg_pr_product.id_product', 'dvg_pr_product.name_product_customer', 'dvg_pr_product.nominal_customer', 'dvg_pr_product.total_nominal_customer', 'dvg_pr_product.desc_customer', 'dvg_pr_product.qty_customer', 'tb_pr.type_of_letter')
            ->where('dvg_pam.id_pam',$id_pam)
            ->where('dvg_pr_product.name_product_customer', '!=', '')
            ->get();

        // $nominals = DB::table('dvg_pr_product')
        //             ->select('nominal')
        //             ->where('id_product', $id_product)
        //             ->first();

        // $nominal = "Rp " . number_format($nominals,0,'','.');

        $total_amounts = DB::table('dvg_pr_product')
                    ->select('total_nominal')
                    ->where('id_pam',$id_pam)
                    ->sum('total_nominal');

        $total_amount = "Rp " . number_format($total_amounts,0,'','.');

        $total_amounts_cus = DB::table('dvg_pr_product')
                    ->select('total_nominal_customer')
                    ->where('id_pam',$id_pam)
                    ->sum('total_nominal_customer');

        $total_amount_cus = "Rp " . number_format($total_amounts_cus,0,'','.');

        return view('DVG.pam.pr_pdf', compact('datas','produks','total_amount', 'nominal', 'produks_cus', 'total_amount_cus'));
        // return $pdf->download('Purchase Request '.$datas->no_pr.' '.$datas->subject.'.pdf');
    }

    public function exportExcel(Request $request)
    {
        $nama = 'Purchase Request Asset'.date('Y-m-d');
        Excel::create($nama, function ($excel) use ($request) {
        $excel->sheet('Status Pembayaran PR Intern DVG', function ($sheet) use ($request) {
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A3:I3');/*
        $sheet->setMergeColumn(array(
		    'columns' => array('A','B','C','D','E','F','G','H','I'),
		    'rows' => array(
		    	array(1,2),
		    )
		));*/

       // $sheet->setAllBorders('thin');
        $sheet->row(1, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
            $row->setValignment('center');
        });

        $sheet->row(1, array('Status Pembayaran PR Intern DVG'));

        $datas = pam::join('tb_pr','tb_pr.no','=','dvg_pam.no_pr')
                    ->select('dvg_pam.id_pam','dvg_pam.date_handover','tb_pr.no_pr','dvg_pam.to_agen','dvg_pam.personel','amount')
                    ->get();

        $produks = pamProduk::select('name_product','qty','nominal','description')
        			->get();


	/*	$sheet->cell('A4', function($cell) {
		$cell->setValue('Created Date : ');
		$cell->setFontWeight('bold');
		});
		$sheet->getStyle('A4')->getAlignment()->applyFromArray(
		array('horizontal' => 'center') //left,right,center & vertical
		);

        foreach ($datas as $data) {
            $sheet->cell('B4', function($cell) {
            $cell->setValue($data['date_handover']);
            $cell->setFontWeight('bold');
            });
            $sheet->getStyle('B4')->getAlignment()->applyFromArray(
            array('horizontal' => 'center') //left,right,center & vertical
            );
        }

		$sheet->cell('C4', function($cell) {
		$cell->setValue('No. Purchase Request :');
		$cell->setFontWeight('bold');
		});
		$sheet->getStyle('C4')->getAlignment()->applyFromArray(
		array('horizontal' => 'center') //left,right,center & vertical
		);
*/


/*        $datas = pam::select('id_pam','date_handover','no_pr','due_date','ket_pr','note_pr','to_agen')
                    ->get();

        $produks = pamProduk::select('name_product','qty','nominal')
        			->get();*/
/*
            $sheet->appendRow(array_keys($datas[0]));*/
            $sheet->row($sheet->getHighestRow(), function ($row) {
                $row->setFontWeight('bold');
            });

             $datasheet = array();
             $datasheet[0]  =   array("No","Created Date", "No. PR", "To", "Personel", "Subject","Qty","Description",  "Amount");
             $i=1;

            foreach ($datas as $data) {
                       // $sheet->appendrow($data);
                foreach ($produks as $produk) {
                     $datasheet[$i] = array($i,
                        $data['date_handover'],
                        $data['no_pr'],
                        $data['to_agen'],
                        $data['personel'],
                        $produk['name_product'],
                        $produk['qty'],
                        $produk['description'],                        
                        $produk['nominal'],
                    );
                  $i++;
                }        
            }

            $sheet->fromArray($datasheet);
        });
        
        })->export('xls');
    }
}
