<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HRCrud;
use App\User;
use App\GuideLine;
use App\RoleUser;
use Validator;
use Response;
use Illuminate\Support\Facades\input;
use App\http\Requests;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;

use Notification;
use App\Notifications\CreateUser;
use App\Notifications\DeleteUser;

use Excel;
// use Image;
use Intervention\Image\ImageManagerStatic as Image;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class HRController extends Controller
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

    public function Notification(){
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }else{
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }

        if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
            $notifOpen= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notifOpen= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }else{
            $notifOpen= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }

        if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
            $notifsd= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','SD')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notifsd= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','SD')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'SALES' && $pos == 'MANAGER') {
            $notifsd= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','SD')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'SALES' && $pos == 'STAFF') {
            $notifsd= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','SD')
            ->orderBy('created_at','desc')
            ->get();
        }else{
            $notifsd= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','SD')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }

        if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
            $notiftp= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','TP')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notiftp= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','TP')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'SALES' && $pos == 'MANAGER') {
            $notiftp= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','TP')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'SALES' && $pos == 'STAFF') {
            $notiftp= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','TP')
            ->orderBy('created_at','desc')
            ->get();
        }else{
            $notiftp= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','TP')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }

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
        } else{
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'FINANCE')
                            ->get();
        }

        return collect([
            "notif" => $notif,
            "notifOpen" => $notifOpen,
            "notifsd" => $notifsd,
            "notiftp" => $notiftp,
            "notifClaim" => $notifClaim
        ]);
    }
    
    public function index(Request $request)
    {
        // $hr = HRCrud::all();
        // return view('HR/human_resource')->with('hr', $hr);  
        $notifAll = $this->notification();
        
        $notif = $notifAll["notif"];
        $notifOpen = $notifAll["notifOpen"];
        $notifsd = $notifAll["notifsd"];
        $notiftp = $notifAll["notiftp"]; 
        $notifClaim = $notifAll["notifClaim"];    

        $hr = DB::table('users')
                ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                ->join('role_user','users.nik','=','role_user.user_id')
                ->join('roles','role_user.role_id','=','roles.id')
                ->select('users.nik', 'users.name', 'users.id_position', 'users.id_division', 'users.id_territory', 'tb_company.code_company','users.email','users.date_of_entry','users.date_of_birth','users.address','users.phone','users.password','users.id_company','users.gambar','status_karyawan','users.no_ktp','users.no_kk','users.no_npwp','users.npwp_file','users.bpjs_kes','users.bpjs_ket','users.ktp_file','status_kerja','roles.name as roles','roles.group')
                ->where('users.status_karyawan','!=','dummy')
                ->where('users.email','!=','dev@sinergy.co.id')
                ->where('tb_company.id_company','1')
                ->where('roles.name', '!=', 'PMO Admin')
                ->get();

        $data_resign = DB::table('users')
                ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                ->join('role_user','users.nik','=','role_user.user_id')
                ->join('roles','role_user.role_id','=','roles.id')
                ->select('users.nik', 'users.name', 'users.id_position', 'users.id_division', 'users.id_territory', 'tb_company.code_company','users.email','users.date_of_entry','users.date_of_birth','users.address','users.phone','users.password','users.id_company','users.gambar','status_karyawan','users.no_ktp','users.no_kk','users.no_npwp','users.npwp_file','users.bpjs_kes','users.bpjs_ket','users.ktp_file','status_kerja','roles.name as roles','roles.group')
                ->where('users.email','!=','dev@sinergy.co.id')
                ->where('tb_company.id_company','1')
                ->where('status_delete','D')
                ->where('roles.name', '!=', 'PMO Admin')
                ->get();

        $hr_msp = DB::table('users')
                ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                ->select('users.nik', 'users.name', 'users.id_position', 'users.id_division', 'users.id_territory', 'tb_company.code_company','users.email','users.date_of_entry','users.date_of_birth','users.address','users.phone','users.password','users.id_company','users.gambar','status_karyawan','users.no_ktp','users.no_kk','users.no_npwp','users.npwp_file','users.bpjs_kes','users.bpjs_ket','users.ktp_file','status_kerja')
                ->where('users.status_karyawan','!=','dummy')
                ->where('users.email','!=','dev@sinergy.co.id')
                ->where('tb_company.id_company','2')
                ->get();

        $roles = DB::table('roles')->where('name', '!=', 'PMO Admin')->get();

        $code = $request['code_input'];        

        return view('HR/human_resource', compact('hr','hr_msp','notif','notifOpen','notifsd','notiftp','notifClaim', 'data_resign', 'roles'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('employee')]);
    }

    public function getemps(Request $request)
    {
        $cari = $request['search'];

        $hr = DB::table('users')
                ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                ->select('users.nik', 'users.name', 'users.id_position', 'users.id_division', 'users.id_territory', 'tb_company.code_company','users.email','users.date_of_entry','users.date_of_birth','users.address','users.phone','users.password','users.id_company','users.gambar','users.no_ktp','users.no_kk','users.no_npwp','users.npwp_file','users.bpjs_kes','users.bpjs_ket','users.ktp_file')
                ->where('users.status_karyawan','!=','dummy')
                ->where('users.email','!=','dev@sinergy.co.id')
                ->where('tb_company.id_company','1')
                ->where('name','like','%'.$cari.'%')
                ->paginate();

        $hr_msp = DB::table('users')
                ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                ->select('users.nik', 'users.name', 'users.id_position', 'users.id_division', 'users.id_territory', 'tb_company.code_company','users.email','users.date_of_entry','users.date_of_birth','users.address','users.phone','users.password','users.id_company','users.gambar','users.no_ktp','users.no_kk','users.no_npwp','users.npwp_file','users.bpjs_kes','users.bpjs_ket','users.ktp_file')
                ->where('users.status_karyawan','!=','dummy')
                ->where('users.email','!=','dev@sinergy.co.id')
                ->where('tb_company.id_company','2')
                ->paginate(9);

        return view('HR/human_resource', compact('hr','hr_msp'))->with(['initView'=> $this->initMenuBase()]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function edit_password()
    {
        $pass = DB::table('users')
                ->select('password')
                ->first();
        // $hr = HRCrud::all();
        // return view('HR/human_resource')->with('hr', $hr);

        return view('auth/user_profile')->with('pass',$pass);
    }

    // public function changePassword(Request $request)
    // {
    //     if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
    //         // The passwords matches
    //         return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
    //     }
 
    //     if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
    //         //Current password and new password are same
    //         return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
    //     }
 
    //     $validatedData = $request->validate([
    //         'current-password' => 'required',
    //         'new-password' => 'required|string|min:6',
    //     ]);
 
    //     //Change Password
    //     $user = Auth::user();
    //     $user->password = bcrypt($request->get('new-password'));
    //     $user->save();
 
    //     return redirect('/profile_user')->with('success' , 'Password changed successfully !');

    // }


    public function getdatahu(Request $request)
    {
    	$id_hu = $request['edit_hurec'];

        return array(DB::table('users')
                ->select('nik','name','email','date_of_entry','date_of_birth','address','phone','password','id_division','id_position','id_territory','id_company','no_ktp','no_kk','no_npwp','npwp_file','bpjs_kes','bpjs_ket','ktp_file','status_kerja','akhir_kontrak','pend_terakhir','tempat_lahir','alamat_ktp','email_pribadi')
                ->where('nik',$request->id_hu)
                ->get(),$request->id_hu);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $id_company = $request['company'];
        $year_entry = substr($request['date_of_entry'],2,2);
        $month_entry = substr($request['date_of_entry'],5,2);
        $year_birth = substr($request['date_of_birth'],2,2);
        $month_birth = substr($request['date_of_birth'],5,2);
        $company = DB::table('tb_company')
                    ->select('id_company')
                    ->where('id_company', $id_company)
                    ->first();

        // $inc = DB::table('users')
        //             ->select('nik')
        //             ->get();

        // $inc = DB::table('users')
        //             ->select('nik')
        //             ->orderBy('created_at', 'desc')
        //             ->first();

        // $incs = substr($inc->nik, -3, 3);

        // $increment = count($inc);
        // $nomor = $increment+1;

        // $nomor = $incs+1;   
        // if($nomor < 10){
        //     $nomor = '00' . $nomor;
        // }elseif($nomor > 9 && $nomor < 99){
        //     $nomor = '0' . $nomor;
        // }

        // $nik = $company->id_company . $year_entry. $month_entry . $year_birth. $month_birth. $nomor;

        $bb = $company->id_company . $year_entry. $month_entry . $year_birth. $month_birth;


        $cek = DB::table('users')
                    ->select('nik')
                    ->where('nik','like',$bb.'%')
                    ->count('nik');

        $cek_nik = DB::table('users')
                    ->select('nik')
                    ->where('nik','like',$bb.'%')
                    ->orderBy('name', 'desc')
                    ->first();

        $cek_name = DB::table('users')
                    ->select('name', 'nik')
                    ->where('nik','like',$bb.'%')
                    ->orderBy('name')
                    ->get();

        if ($cek > 0) {
            $niks = substr($cek_nik->nik, -1, 1);
            $nomor = $niks+1;
        }

        if ($cek == 0) {
            $nik = $company->id_company . $year_entry. $month_entry . $year_birth. $month_birth. '0';
        }else{
            $nik = $company->id_company . $year_entry. $month_entry . $year_birth. $month_birth. $nomor;
        }

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed',
            'company' => 'required',
            'date_of_entry' => 'required',
            'date_of_birth' => 'required'
            // 'no_npwp' => 'required',
            // 'npwp_file' => 'required|image|mimes:jpeg,jpg,png',
        ]);

        $tambah = new User();
        $tambah->nik = $nik;
        $tambah->name = $request['name'];
        $tambah->password = Hash::make($request['password']);
        $tambah->email = $request['email'];
        $tambah->id_company = $request['company'];
        $tambah->status_karyawan = 'belum_cuti';
        $tambah->status_delete = '-';
        $tambah->id_presence_setting = '1';
        $tambah->status_kerja = $request['status_kerja'];

        if ($request['status_kerja'] != "") {
            $tambah->status_kerja = $request['status_kerja'];
        }

        if($request['id_sub_division_tech_'] == 'PRESALES'){
            $tambah->id_division = 'TECHNICAL PRESALES';
        } elseif($request['division_sip'] == 'OPERATION'){
            if($request['id_sub_division_operation'] == 'PMO'){
                $tambah->id_division = 'PMO';
            } elseif($request['id_sub_division_operation'] == 'MSM'){
                $tambah->id_division = 'MSM';
            }  
        } elseif($request['division_msp'] == 'SALES_MSP'){
                $tambah->id_division = 'SALES';
        } elseif ($request['division_msp'] == 'TECHNICAL_MSP') {
                $tambah->id_division = 'TECHNICAL';
        } elseif ($request['division_msp'] == 'WAREHOUSE_MSP') {
                $tambah->id_division = 'WAREHOUSE';
        }elseif ($request['division_msp'] == 'OPERATION_MSP') {
                $tambah->id_division = 'PMO';
                $tambah->id_position = 'PM';
                $tambah->id_territory = 'OPERATION';
        }
         elseif($request['division_sip'] == 'NONE'){
                $tambah->id_division = NULL;
        } elseif($request['id_sub_division_tech_msp'] == 'PRESALES'){
                $tambah->id_division = 'TECHNICAL PRESALES';
        }  else {
                $tambah->id_division = $request['division_sip'];
        } 


        if ($request['id_sub_division_finance'] != '') {
            $tambah->id_territory = $request['id_sub_division_finance'];
        }else if ($request['id_sub_division_operation'] != '') {
            if ($request['id_sub_division_operation'] == 'DIR') {
                $tambah->id_territory = NULL;
            } else if ($request['id_sub_division_operation'] == 'PMO' || $request['id_sub_division_operation'] == 'MSM') {
                $tambah->id_territory = 'OPERATION';
            } else{
                $tambah->id_territory = $request['id_sub_division_operation'];
            }
        }else if ($request['territory']!= '') {
            $tambah->id_territory = $request['territory'];
        }else if ($request['id_sub_division_tech'] == 'NONE') {
            $tambah->id_territory = NULL;
        }else if ($request['id_sub_division_tech'] != '') {
            $tambah->id_territory = $request['id_sub_division_tech'];
        }

        // return 'bcl';

        if ($request['pos_tech'] != '') {
        	if($request['id_sub_division_tech'] == 'DPG'){
                if($request['pos_tech'] == 'MANAGER'){
                    $tambah->id_position = 'ENGINEER MANAGER';  
                } elseif($request['pos_tech'] == 'STAFF'){
                    $tambah->id_position = 'ENGINEER STAFF';
                } elseif($request['pos_tech'] == 'HEAD'){
                    $tambah->id_position = 'MANAGER';
                }
            }elseif ($request['id_sub_division_tech'] == 'NONE') {
                if($request['pos_tech'] == 'HEAD'){
                    $tambah->id_position = 'MANAGER';  
                }
            } else {
                $tambah->id_position = $request['pos_tech'];
            }
        } else if($request['pos_finance'] != ''){
           if($request['pos_finance'] == 'HEAD'){
           		$tambah->id_position = 'MANAGER';	
           	} elseif($request['pos_finance'] == 'DIRECTOR'){
           		$tambah->id_position = 'FINANCE DIRECTOR';
           	} else {
           		$tambah->id_position = $request['pos_finance'];
           	}
        } else if($request['pos_dir'] != ''){
           $tambah->id_position = $request['pos_dir'];
        } else if($request['pos_operation'] != ''){
        	if ($request['pos_operation'] == 'DIRECTOR') {
        		$tambah->id_position = 'OPERATION DIRECTOR';
        	}else{
        		$tambah->id_position = $request['pos_operation']; 
        	}
        } else if($request['pos_sales'] != ''){
           $tambah->id_position = $request['pos_sales']; 
        } else if($request['pos_hr'] != ''){
           $tambah->id_position = $request['pos_hr']; 
        } else if($request['pos_expert_sales'] == 'EXPERT SALES'){
           $tambah->id_position = $request['pos_expert_sales']; 
           $tambah->id_territory = $request['territory_expert'];
           $tambah->id_division = 'SALES';
        } else if($request['pos_expert_sales'] == 'EXPERT ENGINEER'){
           $tambah->id_position = $request['pos_expert_sales']; 
           $tambah->id_territory = $request['territory_expert'];
           $tambah->id_division = 'TECHNICAL';
        }else if ($request['pos_tech_msp'] != '') {
           $tambah->id_position = $request['pos_tech_msp'];
        }else if ($request['pos_sales_msp'] != '') {
           $tambah->id_position = $request['pos_sales_msp'];
        }

        $tambah->akhir_kontrak = $request['akhir_kontrak'];
        $tambah->date_of_entry = $request['date_of_entry'];
        $tambah->date_of_birth = $request['date_of_birth'];
        $tambah->address = $request['address'];
        $tambah->phone = substr(str_replace('-', '', $request['phone_number']),6);
        $tambah->no_ktp = $request['no_ktp'];
        $tambah->no_kk = $request['no_kk'];
        $tambah->no_npwp = $request['no_npwp'];
        $tambah->bpjs_kes = $request['bpjs_kes'];
        $tambah->bpjs_ket = $request['bpjs_ket'];
        $tambah->jenis_kelamin = $request['jenis_kelamin'];
        $tambah->pend_terakhir = $request['pend_terakhir'];
        $tambah->email_pribadi = $request['email_personal'];
        $tambah->tempat_lahir = $request['tempat_lahir'];
        $tambah->alamat_ktp = $request['address_ktp'];

        //upload file gambar npwp user

        $file = $request->file('npwp_file');

        if ($file !== null) {
        	$fileName = $nik."_npwp_ver1".".jpg";
        	$request->file('npwp_file')->move("image/", $fileName);
        }else{
        	$fileName = "";
        }

        $tambah->npwp_file = $fileName;

        // $file = $request->file('bpjs_kes');
        // if ($file !== null) {
        //     $fileName = $nik."_bpjs_kes_ver1".".jpg";
        //     $request->file('bpjs_kes')->move("image/", $fileName);
        // }else{
        //     $fileName = "";
        // }

        // $tambah->bpjs_kes = $fileName;

        // $file = $request->file('bpjs_ket');
        // if ($file !== null) {
        //     $fileName = $nik."_bpjs_ket_ver1".".jpg";
        //     $request->file('bpjs_ket')->move("image/", $fileName);
        // }else{
        //     $fileName = "";
        // }

        // $tambah->bpjs_ket = $fileName;
        
        $tambah->save();

        $tambah_roles = new RoleUser(); 
        $tambah_roles->role_id = $request['roles_user'];
        $tambah_roles->user_id = $nik;
        $tambah_roles->save(); 

        $userCompany = DB::table('tb_company')
                            ->select('code_company')
                            ->where('id_company', $tambah->id_company)
                            ->first();

        if($userCompany->code_company == '1') {
            $uCom = 'PT. Sinergy Informasi Pratama';
        } else {
            $uCom = 'PT. Multi Solusindo Perkasa';
        }

        $arr = ['name' => $tambah->name, 'email' => $tambah->email, 'company' => $uCom];

        /*$kirim = User::select('email')->where('email', 'brillyan@sinergy.co.id')->first();
        Notification::send($kirim, new CreateUser($arr));*/
        
        return redirect('hu_rec')->with('success', 'Created Employee Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
    public function update_humanresource(Request $request)
    {
        // return $request->no_npwp;
        $id_company = substr($request['nik_update'],0,1);
        $year_entry = substr($request['date_of_entry_update'],2,2);
        $month_entry = substr($request['date_of_entry_update'],5,2);
        $year_birth = substr($request['date_of_birth_update'],2,2);
        $month_birth = substr($request['date_of_birth_update'],5,2);
        $company = DB::table('tb_company')
                    ->select('id_company')
                    ->where('id_company', $id_company)
                    ->first();

        $nik = $request['nik_update'];

        $check_nik = User::select('date_of_entry','date_of_birth')->where('nik',$nik)->first();

        $bb = $company->id_company . $year_entry. $month_entry . $year_birth. $month_birth;

        $cek = DB::table('users')
                    ->select('nik')
                    ->where('nik','like',$bb.'%')
                    ->count();

        $cek_nik = DB::table('users')
                    ->select('nik')
                    ->where('nik','like',$bb.'%')
                    ->where('updated_at','')
                    ->first();

        $cek_name = DB::table('users')
                    ->select('name', 'nik')
                    ->where('nik','like',$bb.'%')
                    ->orderBy('name')
                    ->get();

        if ($cek < 1) {
        	$nomor = '0';
        }else{
            $nomor = $cek;
        }
        
        $nims = $company->id_company . $year_entry. $month_entry . $year_birth. $month_birth. $nomor;

        $update = User::where('nik',$nik)->first();
        if ($request['date_of_entry_update'] != "" && $request['date_of_birth_update'] != "") {
            if ($check_nik->date_of_entry !=  $request['date_of_entry_update'] && $check_nik->date_of_entry !=  $request['date_of_birth_update']) {
                $update->nik =  $nims;
            }
            $update->date_of_birth = $request['date_of_birth_update'];
            $update->date_of_entry = $request['date_of_entry_update'];
        }else{
            $update->nik =  $nik;
        }
        

        
        if ($request['name_update'] != "") {
            $update->name = $request['name_update'];
        } 
        if ($request['email_update'] != "") {
            $update->email = $request['email_update'];
        } 
        if ($request['pend_terakhir_update'] != "") {
            $update->pend_terakhir = $request['pend_terakhir_update'];
        } 
        if ($request['tempat_lahir_update'] != "") {
            $update->tempat_lahir = $request['tempat_lahir_update'];
        } 
        if ($request['email_personal_update'] != "") {
            $update->email_pribadi = $request['email_personal_update'];
        } 
        if ($request['bpjs_ket_update'] != "") {
            $update->bpjs_ket = $request['bpjs_ket_update'];
        } 
        if ($request['bpjs_kes_update'] != "") {
            $update->bpjs_kes = $request['bpjs_kes_update'];
        } 
        if ($request['address_ktp_update'] != "") {
            $update->alamat_ktp = $request['address_ktp_update'];
        }
        
        
        if ($request['company_update'] != "") {
            $update->id_company = $request['company_update'];

            if ($request['divisi_update'] == 'OPERATION') {
               if ($request['sub_divisi_update'] == 'OPERATION') {
                   $update->id_position = 'OPERATION DIRECTOR';
               }
            }else if ($request['divisi_update'] == 'TECHNICAL') {
                if ($request['sub_divisi_update'] == 'DPG') {
                    if ($request['posisi_update'] == 'MANAGER') {
                       $update->id_position = 'ENGINEER MANAGER';
                    }else{
                       $update->id_position = 'ENGINEER STAFF'; 
                    }
                }if ($request['sub_divisi_update'] == 'PRESALES') {
                    $update->id_territory = 'TECHNICAL PRESALES';
                }else{
                    $update->id_position = $request['posisi_update'];
                }
                
            }else{
                $update->id_position = $request['posisi_update'];
                $update->id_division = $request['divisi_update'];
                $update->id_territory = $request['sub_divisi_update'];
            }
            

            if ($request['sub_divisi_update'] == 'MSM') {
                $update->id_division = 'MSM';
                $update->id_territory = 'OPERATION';
            }else if ($request['sub_divisi_update'] == 'PMO') {
                $update->id_division = 'PMO';
                $update->id_territory = 'OPERATION';
            }else if ($request['sub_divisi_update'] == 'OPERATION') {
                $update->id_division = 'PMO';
                $update->id_territory = 'OPERATION';
            }else if ($request['sub_divisi_update'] == 'WAREHOUSE') {
                $update->id_position = 'WAREHOUSE';
                $update->id_territory = '';
                $update->id_division = '-';
            }
        }
        
        
        

        // if($request['id_sub_division_tech_update'] == 'PRESALES'){
        //     $update->id_division = 'TECHNICAL PRESALES';
        // } elseif($request['division_update'] == 'OPERATION'){
        //     if($request['id_sub_division_operation_update'] == 'PMO'){
        //         $update->id_division = 'PMO';
        //     } elseif($request['id_sub_division_operation_update'] == 'MSM'){
        //         $update->id_division = 'MSM';
        //     }  
        // } elseif($request['division_msp_update'] == 'SALES_MSP'){
        //         $update->id_division = 'SALES';
        // } elseif ($request['division_msp_update'] == 'TECHNICAL_MSP') {
        //         $update->id_division = 'TECHNICAL';
        // } elseif ($request['division_msp_update'] == 'WAREHOUSE_MSP') {
        //         $update->id_division = 'WAREHOUSE';
        // } elseif($request['division_update'] == 'NONE'){
        //         $update->id_division = NULL;
       	// } elseif($request['id_sub_division_tech_msp_update'] == 'PRESALES'){
        //         $update->id_division = 'TECHNICAL PRESALES';
        // }  else {
        //         $update->id_division = $request['division_update'];
        // }

        // if ($request['pos_tech_update'] != '') {
        //     if($request['id_sub_division_tech_update'] == 'DPG'){
        //         if($request['pos_tech_update'] == 'MANAGER'){
        //             $update->id_position = 'ENGINEER MANAGER';  
        //         } elseif($request['pos_tech_update'] == 'STAFF'){
        //             $update->id_position = 'ENGINEER STAFF';
        //         } elseif($request['pos_tech_update'] == 'HEAD'){
        //             $update->id_position = 'MANAGER';
        //         }
        //     }elseif ($request['id_sub_division_tech_update'] == 'NONE') {
        //         if($request['pos_tech_update'] == 'HEAD'){
        //             $update->id_position = 'MANAGER';  
        //         }
        //     } else {
        //         $update->id_position = $request['pos_tech_update'];
        //     }
        // } else if($request['pos_finance_update'] != ''){
        //    if($request['pos_finance_update'] == 'HEAD'){
        //         $update->id_position = 'MANAGER';   
        //     } elseif($request['pos_finance_update'] == 'DIRECTOR'){
        //         $update->id_position = 'FINANCE DIRECTOR';
        //     } else {
        //         $update->id_position = $request['pos_finance_update'];
        //     }
        // } else if($request['pos_dir_update'] != ''){
        //    $update->id_position = $request['pos_dir_update'];
        // } else if($request['pos_operation_update'] != ''){
        //     if ($request['pos_operation_update'] == 'DIRECTOR') {
        //         $update->id_position = 'OPERATION DIRECTOR';
        //     }else{
        //         $update->id_position = $request['pos_operation_update']; 
        //     }
        // } else if($request['pos_sales_update'] != ''){
        //    $update->id_position = $request['pos_sales_update']; 
        // } else if($request['pos_hr_update'] != ''){
        //    $update->id_position = $request['pos_hr_update']; 
        // } else if ($request['pos_tech_msp_update'] != '') {
        //    $update->id_position = $request['pos_tech_msp_update'];
        // } else if ($request['pos_sales_msp_update'] != '') {
        //    $update->id_position = $request['pos_sales_msp_update'];
        // }

        // if ($request['id_sub_division_finance'] != '') {
        //     $update->id_territory = $request['id_sub_division_finance'];
        // }else if ($request['id_sub_division_operation_update'] != '') {
        //     if ($request['id_sub_division_operation_update'] == 'DIR') {
        //         $update->id_territory = NULL;
        //     } else if ($request['id_sub_division_operation_update'] == 'PMO' || $request['id_sub_division_operation_update'] == 'MSM') {
        //         $update->id_territory = 'OPERATION';
        //     } else{
        //         $update->id_territory = $request['id_sub_division_operation_update'];
        //     }
        // }else if ($request['territory_update']!= '') {
        //     $update->id_territory = $request['territory_update'];
        // }else if ($request['id_sub_division_tech_update'] == 'NONE') {
        //     $update->id_territory = NULL;
        // }else if ($request['id_sub_division_tech_update'] != '') {
        //     $update->id_territory = $request['id_sub_division_tech_update'];
        // }


        if ($request['address_update'] != "") {
            $update->address = $request['address_update'];
        }
        if ($request['phone_number_update'] != "") {
            $update->phone = substr(str_replace('-', '', $request['phone_number_update']),6);
        }
        if ($request['no_ktp_update'] != "") {
            $update->no_ktp = $request['no_ktp_update'];
        }
        if ($request['no_kk_update'] != "") {
            $update->no_kk = $request['no_kk_update'];
        }
        if ($request['no_npwp_update'] != "") {
            $update->no_npwp = $request['no_npwp_update'];
        }

        // return ($request['no_npwp'] != "" ? "true" : "false");
        
        $file       = $request->file('npwp_file');

        if ($file == "") {
            
        }else{

            $fileName   = $nik."_npwp_ver1".".jpg";

            $request->file('npwp_file')->move("image/", $fileName);
            $update->npwp_file = $fileName;
        }

        // $file = $request->file('bpjs_kes');
        // if ($file == "") {
            
        // }else{
        //     $fileName = $nik."_bpjs_kes_ver1".".jpg";
        //     $request->file('bpjs_kes')->move("image/", $fileName);
        //     $update->bpjs_kes = $fileName;
        // }

        // $file = $request->file('bpjs_ket');
        // if ($file == "") {
            
        // }else{
        //     $fileName = $nik."_bpjs_ket_ver1".".jpg";
        //     $request->file('bpjs_ket')->move("image/", $fileName);
        //     $update->bpjs_ket = $fileName;
        // }

        if ($request['status_kerja_update'] != "") {
            $update->status_kerja = $request['status_kerja_update'];
        } else if ($request['akhir_kontrak_update'] != "") {
            $update->email = $request['akhir_kontrak_update'];
        }

        // return $update;

        $update->update();

        return redirect('hu_rec')->with('update', 'Updated Employee Data Successfully!');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy_hr($nik)
    {
        $userName = DB::table('users')
                        ->select('name')
                        ->where('nik', $nik)
                        ->first();

        $arr = ['name' => $userName->name];

        // $kirim = User::select('email')->where('email', 'ladinar@sinergy.co.id')->first();
        // Notification::send($kirim, new DeleteUser($arr));

        // if (User::where('nik',$nik)->first()->id_division == 'SALES') {
        //     $update = User::where('nik',$nik)->first();
        //     $update->status_karyawan = 'dummy';
        //     $update->update();
        // }else{
        //     $hapus = HRCrud::find($nik);
        //     $hapus->delete();
        // }

        $update = User::where('nik',$nik)->first();
        $update->status_karyawan = 'dummy';
        $update->status_delete = 'D';
        $update->password = Hash::make("Sinergy".date('dmy'));
        $update->update();

        return redirect()->back()->with('alert', 'Deleted Employee!');
    }

    public function user_profile()
    {
        $notif = '';$notifOpen = '';$notifsd = '';$notiftp = '';$ter = '';$user_profile = '';$notifClaim = '';
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        if ($div == 'SALES' && $ter != ''  || $div == 'TECHNICAL' && $ter == 'DVG' || $ter == 'DPG') {
            $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->join('tb_territory','tb_territory.id_territory','=','users.id_territory')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','tb_territory.name_territory','users.date_of_entry','address','users.no_ktp','users.no_kk','users.no_npwp','users.npwp_file','users.bpjs_kes','users.bpjs_ket','ktp_file','users.bpjs_kes','users.bpjs_ket',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER' || $div == 'TECHNICAL PRESALES' && $pos == 'STAFF'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.id_division','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address','users.no_ktp','users.no_kk','ktp_file','users.no_npwp','users.npwp_file','users.bpjs_kes','users.bpjs_ket',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'TECHNICAL' && $pos == 'MANAGER'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address','users.no_ktp','users.no_kk','ktp_file','users.no_npwp','users.npwp_file','users.bpjs_kes','users.bpjs_ket',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'FINANCE' && $pos == 'MANAGER'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address','users.no_ktp','users.no_kk','ktp_file','users.no_npwp','users.npwp_file','users.bpjs_kes','users.bpjs_ket',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'FINANCE' && $pos == 'STAFF'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address','users.no_ktp','users.no_kk','ktp_file','users.no_npwp','users.npwp_file','users.bpjs_kes','users.bpjs_ket',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'TECHNICAL PRESALES' && $pos == 'STAFF'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.id_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address','users.no_ktp','users.no_kk','ktp_file','users.no_npwp','users.npwp_file','users.bpjs_kes','users.bpjs_ket',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'PMO' && $pos == 'MANAGER'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address','users.no_ktp','ktp_file','users.no_kk','users.no_npwp','users.npwp_file','users.bpjs_kes','users.bpjs_ket',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'PMO' && $pos == 'STAFF'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address','users.no_ktp','ktp_file','users.no_kk','users.no_npwp','users.npwp_file','users.bpjs_kes','users.bpjs_ket',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'PMO' && $pos == 'ADMIN'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address','users.no_ktp','ktp_file','users.no_kk','users.no_npwp','users.npwp_file','users.bpjs_kes','users.bpjs_ket',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'TECHNICAL' && $pos == 'INTERNAL IT'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address','users.no_ktp','ktp_file','users.no_kk','users.no_npwp','users.npwp_file','users.bpjs_kes','users.bpjs_ket',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'SALES' && $ter == ''){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address','users.no_ktp','ktp_file','users.no_kk','users.no_npwp','users.npwp_file','users.bpjs_kes','users.bpjs_ket',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }
        else{
           $user_profile = DB::table('users')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address','users.no_ktp','users.no_kk','users.no_npwp','ktp_file','users.npwp_file','users.bpjs_kes','users.bpjs_ket',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }
        

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }else{
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }

        if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER' ) {
            $notifOpen= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notifOpen= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'SALES' && $pos == 'MANAGER') {
            $notifOpen= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'SALES' && $pos == 'STAFF') {
            $notifOpen= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','')
            ->orderBy('created_at','desc')
            ->get();
        }else{
            $notifOpen= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }

        if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
            $notifsd= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','SD')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notifsd= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','SD')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'SALES' && $pos == 'MANAGER') {
            $notifsd= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','SD')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'SALES' && $pos == 'STAFF') {
            $notifsd= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','SD')
            ->orderBy('created_at','desc')
            ->get();
        }else{
            $notifsd= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','SD')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }

        if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
            $notiftp= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','TP')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notiftp= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','TP')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'SALES' && $pos == 'MANAGER') {
            $notiftp= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','TP')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'SALES' && $pos == 'STAFF') {
            $notiftp= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','TP')
            ->orderBy('created_at','desc')
            ->get();
        }else{
            $notiftp= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','TP')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }

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
         
        return view('HR/profile', compact('notif','notifOpen','notifsd','notiftp','ter','user_profile', 'notifClaim'))->with(['initView'=> $this->initMenuBase()]);
    }

    public function update_profile(Request $req){
        $nik = $req['nik_profile'];

        $niks = DB::table('users')
        ->select('nik')
        ->where('nik',$nik)
        ->first();

        $year_birth = substr($req['date_of_birth'],2,2);
        $month_birth = substr($req['date_of_birth'],5,2);
        $year_entry = substr($req['date_of_entry'],2,2);
        $month_entry = substr($req['date_of_entry'],5,2);

        $nik_births = $year_birth . $month_birth;
        $nik_entries = $year_entry . $month_entry;
        // $nik_inc = substr($niks->nik, -1,1);
        $nik_awal = substr($niks->nik, 0,1);

        $bb = $nik_awal . $nik_entries. $nik_births;

        $cek = DB::table('users')
                    ->select('nik')
                    ->where('nik','like',$bb.'%')
                    ->count();

        $cek_nik = DB::table('users')
                    ->select('nik')
                    ->where('nik','like',$bb.'%')
                    ->orderBy('name', 'desc')
                    ->first();

        if ($cek = 1) {
        	$nomor = '0';
        }else{
        	$niks = substr($cek_nik->nik, -1, 1);
            $nomor = $niks+1;
        }
        
        $nims = $nik_awal . $nik_entries. $nik_births . $nomor;

        // $nim = $nik_awal. $nik_entries  . $nik_births . '/' .$nik_inc;
        // $nims = str_replace('/', '', $nim);

        $update = User::where('nik',$nik)->first();
        // $update->nik            = $nik;
        $update->name           = $req['name'];
        $update->email          = $req['email'];
        $update->date_of_birth  = date("Y-m-d",strtotime(str_replace('/','-',$req['date_of_birth'])));
        $update->date_of_entry  = date("Y-m-d",strtotime(str_replace('/','-',$req['date_of_entry'])));
        $update->phone          = substr(str_replace('.','',$req['phone']),1);
        $update->address        = $req['address'];
        $update->no_ktp         = $req['no_ktp'];
        $update->no_kk          = $req['no_kk'];
        $update->no_npwp        = $req['no_npwp'];
        $update->npwp_file      = $req['npwp_file'];
        $update->bpjs_kes       = $req['bpjs_kes'];
        $update->bpjs_ket       = $req['bpjs_ket'];

        // if($req->file('npwp_file') === null) {
        //     $update->npwp_file = $update->npwp_file;
        // } else {
        //     $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG'];
        //     $file                   = $req->file('npwp_file');
        //     $fileName               = $nik."_npwp_ver1".".jpg";
        //     $extension              = $file->getClientOriginalExtension();
        //     $check                  = in_array($extension,$allowedfileExtension);

        //     if ($check) {
        //         $req->file('npwp_file')->move("image/", $fileName);
        //         $update->npwp_file = $fileName;
        //     } else {
        //         return redirect()->back()->with('alert','Oops! Only jpg, png');
        //     }
            
        // }

        // if($req->file('ktp_file') === null) {
        //     $update->ktp_file = $update->ktp_file;
        // } else {
        //     $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG'];
        //     $file                   = $req->file('ktp_file');
        //     $fileName               = $nik."_ktp_ver1".".jpg";
        //     $extension              = $file->getClientOriginalExtension();
        //     $check                  = in_array($extension,$allowedfileExtension);

        //     if ($check) {
        //         $req->file('ktp_file')->move("image/", $fileName);
        //         $update->npwp_file = $fileName;
        //     } else {
        //         return redirect()->back()->with('alert','Oops! Only jpg, png');
        //     }
            
        // }

        if ($req->file('bpjs_kes') === null) {
            
        } else {
            $fileName = $nik."_bpjs_kes_ver1".".jpg";
            $req->file('bpjs_kes')->move("image/", $fileName);
            $update->bpjs_kes = $fileName;
        }

        if ($req->file('bpjs_ket') === null) {
            
        } else {
            $fileName = $nik."_bpjs_ket_ver1".".jpg";
            $req->file('bpjs_ket')->move("image/", $fileName);
            $update->bpjs_ket = $fileName;
        }

        // Disini proses mendapatkan judul dan memindahkan letak gambar ke folder image
        // $this->validate($request, [
        //   'image'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:'
        // ]);

        if($req->file('gambar') === null) {
            $update->gambar = $update->gambar;
        } else {
            $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG'];
            $file                   = $req->file('gambar');
            $fileName               = $file->getClientOriginalName();
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            if ($check) {
                Image::make($file->getRealPath())->resize(1024, 1024)->save('image/'.$fileName);

                $update->gambar = $fileName;
            } else {
                return redirect()->back()->with('alert','Oops! Only jpg, png');
            }

            
        }
        
        $update->update();

        return redirect()->back()->with('success','Successfully Updated Profile!');

    }

    public function update_profile_npwp(Request $req)
    {
        $nik = $req['nik_profile'];

        $update = User::where('nik',$nik)->first();
        if($req->file('npwp_file') === null) {
            $update->npwp_file = $update->npwp_file;
        } else {
            $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG'];
            $file                   = $req->file('npwp_file');
            $fileName               = $nik."_npwp_ver1".".jpg";
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            if ($check) {
                $req->file('npwp_file')->move("image/", $fileName);
                $update->npwp_file = $fileName;
            } else {
                return redirect()->back()->with('alert','Oops! Only jpg, png');
            }
            
        }

        if($req->file('ktp_file') === null) {
            $update->ktp_file = $update->ktp_file;
        } else {
            $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG'];
            $file                   = $req->file('ktp_file');
            $fileName               = $nik."_ktp_ver1".".jpg";
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            if ($check) {
                $req->file('ktp_file')->move("image/", $fileName);
                $update->ktp_file = $fileName;
            } else {
                return redirect()->back()->with('alert','Oops! Only jpg, png');
            }
            
        }

        if($req->file('bpjs_kes') === null) {
            $update->bpjs_kes = $update->bpjs_kes;
        } else {
            $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG'];
            $file                   = $req->file('bpjs_kes');
            $fileName               = $nik."_bpjs_kes_ver1".".jpg";
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            if ($check) {
                $req->file('bpjs_kes')->move("image/", $fileName);
                $update->bpjs_kes = $fileName;
            } else {
                return redirect()->back()->with('alert','Oops! Only jpg, png');
            }
            
        }

        if($req->file('bpjs_ket') === null) {
            $update->bpjs_ket = $update->bpjs_ket;
        } else {
            $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG'];
            $file                   = $req->file('bpjs_ket');
            $fileName               = $nik."_bpjs_ket_ver1".".jpg";
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            if ($check) {
                $req->file('bpjs_ket')->move("image/", $fileName);
                $update->bpjs_ket = $fileName;
            } else {
                return redirect()->back()->with('alert','Oops! Only jpg, png');
            }
            
        }
        $update->update();
        return redirect()->back()->with('success','Successfully Updated Profile!');

    }

    public function changePassword(Request $req){
        $nik = $req['nik_profile'];

        $update = User::where('nik',$nik)->first();

        if (!(Hash::check($req->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("alert","Your current password does not matches with the password you provided. Please try again.");
        }
 
        if(strcmp($req->get('current-password'), $req->get('password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("alert","New Password cannot be same as your current password. Please choose a different password.");
        }

        $validatedData = $req->validate([
            'current-password'  => ['required'],
            'password'      => ['required', 
               'min:8', 
               'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
               'confirmed']
        ]);


        $update->password = Hash::make($req->get('password'));

        $update->update();

        return redirect()->back()->with('success','Successfully Updated Profile!');

    }

    public function delete_pict(Request $request){
        $nik = $request['pick_nik'];

        $update = User::where('nik',$nik)->first();
        $update->gambar = NULL;

        $update->update();

        return redirect()->back();
    }

    public function getDropdownTech(Request $request)
    {
        if($request->id_assign=='DPG'){
            return array(DB::table('tb_position')
                ->select('name_position')
                ->where('id_position','MANAGER')
                ->orWhere('id_position','STAFF')
                ->get(),$request->id_assign);
        } else if ($request->id_assign == 'PRESALES') {
            return array(DB::table('tb_position')
                ->select('name_position')
                ->where('id_position','MANAGER')
                ->orWhere('id_position','STAFF')
                ->get(),$request->id_assign);
        } else if ($request->id_assign == 'DVG') {
            return array(DB::table('tb_position')
                ->select('name_position')
                ->where('id_position','MANAGER')
                ->orWhere('id_position','STAFF')
                ->get(),$request->id_assign);
        } else if ($request->id_assign == 'NONE') {
            return array(DB::table('tb_position')
                ->select('name_position','id_position')
                ->where('id_position','HEAD')
                ->orwhere('id_position','ADMIN')
                ->orwhere('id_position','INTERNAL IT')
                ->get(),$request->id_assign);
        } else if ($request->id_assign == 'SALES') {
            return array(DB::table('tb_territory')
                ->select('name_territory')
                ->where('id_territory','TERRITORY 1')
                ->orWhere('id_territory','TERRITORY 2')
                ->orWhere('id_territory','TERRITORY 3')
                ->orWhere('id_territory','TERRITORY 4')
                ->orWhere('id_territory','TERRITORY 5')
                ->orWhere('id_territory','TERRITORY 6')
                ->get(),$request->id_assign);
        }else if ($request->id_assign == 'SPECIALIST') {
            return array(DB::table('tb_territory')
                ->select('name_territory')
                ->where('id_territory','SPECIALIST')
                ->get(),$request->id_assign);
        } else if ($request->id_assign == 'FINANCE') {
            return array(DB::table('tb_position')
                ->select('name_position')
                ->where('id_position','STAFF')
                ->orWhere('id_position','DIRECTOR')
                ->orWhere('id_position','COURIER')
                ->get(),$request->id_assign);
        } else if ($request->id_assign == 'ACC') {
            return array(DB::table('tb_position')
                ->select('name_position')
                ->where('id_position','HEAD')
                ->orWhere('id_position','STAFF')
                ->get(),$request->id_assign);
        } else if ($request->id_assign == 'MSM') {
            return array(DB::table('tb_position')
                ->select('name_position')
                ->where('id_position','MANAGER')
                ->orWhere('id_position','HELP DESK')
                ->orWhere('id_position','CALL SO')
                ->orWhere('id_position','SUPPORT ENGINEER(HEAD)')
                ->orWhere('id_position','SUPPORT ENGINEER(STAFF)')
                ->orWhere('id_position','SERVICE PROJECT(HEAD)')
                ->orWhere('id_position','SERVICE PROJECT(STAFF)')
                ->orWhere('id_position','ADMIN')
                ->orWhere('id_position','SERVICE PROJECT')
                ->get(),$request->id_assign);
        }else if ($request->id_assign == 'PMO') {
            return array(DB::table('tb_position')
                ->select('name_position')
                ->where('id_position','MANAGER')
                ->orWhere('id_position','ADMIN')
                ->orWhere('id_position','PM')
                ->get(),$request->id_assign);
        }else if ($request->id_assign == 'DIR') {
            return array(DB::table('tb_position')
                ->select('name_position')
                ->where('id_position','DIRECTOR')
                ->get(),$request->id_assign);
        }else if ($request->id_assign == 'HR') {
            return array(DB::table('tb_position')
                ->select('name_position')
                ->where('id_position','HR MANAGER')
                ->orWhere('id_position','STAFF HR')
                ->orWhere('id_position','STAFF GA')
                ->get(),$request->id_assign);
        } else if ($request->id_assign == 'NULL') {
            return array(DB::table('tb_position')
                ->select('name_position')
                ->where('id_position','DIRECTOR')
                ->get(),$request->id_assign);
        } else if ($request->id_assign == 'ADMIN_MSP') {
            return array(DB::table('tb_position')
                ->select('name_position')
                ->where('id_position','DIRECTOR')
                ->orWhere('id_position','ADMIN')
                ->orWhere('id_position','COURIER')
                ->get(),$request->id_assign);
        } else if ($request->id_assign == 'SALES_MSP') {
            return array(DB::table('tb_position')
                ->select('name_position')
                ->where('id_position','MANAGER')
                ->orWhere('id_position','STAFF')
                ->get(),$request->id_assign);
        } else if ($request->id_assign == 'TECHNICAL_MSP') {
            return array(DB::table('tb_position')
                ->select('name_position')
                ->where('id_position','MANAGER')
                ->orWhere('id_position','STAFF')
                ->get(),$request->id_assign);
        } else if ($request->id_assign == 'NONE_MSP') {
            return array(DB::table('tb_position')
                ->select('name_position')
                ->where('id_position','MANAGER')
                ->orWhere('id_position','STAFF')
                ->orWhere('id_position','COURIER')
                ->get(),$request->id_assign);
        } else if ($request->id_assign == 'WAREHOUSE_MSP') {
            return array(DB::table('tb_position')
                ->select('name_position')
                ->where('id_position','MANAGER')
                ->orWhere('id_position','STAFF')
                ->get(),$request->id_assign);
        } else if ($request->id_assign == 'OPERATION_MSP') {
            return array(DB::table('tb_position')
                ->select('name_position')
                ->orWhere('id_position','PM')
                ->get(),$request->id_assign);
        }
    }

    public function exportExcelEmployee(Request $request){

        $spreadsheet = new Spreadsheet();

        $prSheet = new Worksheet($spreadsheet,'SIP Employee');
        $spreadsheet->addSheet($prSheet);
        $spreadsheet->removeSheetByIndex(0);
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:X1');
        $normalStyle = [
            'font' => [
                'name' => 'Calibri',
                'size' => 11
            ],
        ];

        $titleStyle = $normalStyle;
        $titleStyle['alignment'] = ['horizontal' => Alignment::HORIZONTAL_CENTER];
        $titleStyle['font']['bold'] = true;

        $sheet->getStyle('A1:X1')->applyFromArray($titleStyle);
        $sheet->setCellValue('A1','SIP Employee');

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $headerStyle['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ["argb" => "FFFCD703"]];
        $head['borders'] = ['outline' => ['borderStyle' => Border::BORDER_THIN]];
        $sheet->getStyle('A2:X2')->applyFromArray($headerStyle);;

        $headerContent = ["No", "NIK", "Nama Lengkap", "Status Karyawan" ,"Divisi", "Jabatan" , "Territory", "Tanggal Mulai Tugas", "Tempat Lahir", "Tanggal Lahir", "Jenis Kelamin", "KTP", "Alamat KTP", "KK", "NPWP", "BPJS Kesehatan", "BPJS Ketenagakerjaan", "Pendidikan Terakhir", "Email Pribadi", "Telepon", "Email Kantor", "Nama Emergency Contact", "Telepon Emergency Contact", "Hubungan Emergency Contact"];
        $sheet->fromArray($headerContent,NULL,'A2');  

        $datas = User::join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                    ->select('nik', 'name', 'status_kerja', 'id_division', 'id_position', 'id_territory', 'date_of_entry', 'tempat_lahir', 'date_of_birth', 'jenis_kelamin', 'no_ktp', 'alamat_ktp', 'no_kk', 'no_npwp', 'bpjs_kes', 'bpjs_ket', 'pend_terakhir', 'email_pribadi', 'phone', 'email', 'name_ec', 'phone_ec', 'hubungan_ec')
                    ->where('status_karyawan', 'cuti')
                    ->where('users.id_company', '1')
                    ->where('nik', '<>', '1100463060')
                    ->where('nik', '<>', '1100881060')
                    ->where('nik', '<>', '1050165120')
                    ->where('nik', '<>', '1171294021')
                    ->get();      
                    // return $datas;

        $datas =  $datas->map(function($item,$key) use ($sheet){
            $item->phone = "0" . $item->phone;
            $sheet->fromArray(array_merge([$key + 1],array_values($item->toArray())),NULL,'A' . ($key + 3));
            $sheet->setCellValueExplicit('L'.($key + 3),$item->no_ktp,DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('N'.($key + 3),$item->no_kk,DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('O'.($key + 3),$item->no_npwp,DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('P'.($key + 3),$item->bpjs_kes,DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('Q'.($key + 3),$item->bpjs_ket,DataType::TYPE_STRING);
        });

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->getColumnDimension('X')->setAutoSize(true);

        $fileName = 'SIP Employee ' . date('Y') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        return $writer->save("php://output");
    }

    public function GuideLineIndex(Request $request){
        $notifAll = $this->notification();
        
        $notif = $notifAll["notif"];
        $notifOpen = $notifAll["notifOpen"];
        $notifsd = $notifAll["notifsd"];
        $notiftp = $notifAll["notiftp"]; 
        $notifClaim = $notifAll["notifClaim"];    

        $data = GuideLine::select('id','description','link_url','title','efective_date')->get();

        return view('HR/guideLines',compact('data','notif','notifOpen','notifsd','notiftp','notifClaim'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('bookmark')]);

    }

    public function getGuideIndex(Request $request){
        if ($request->type == null) {
            return array("data"=>GuideLine::select('id','description','link_url','title as title_guide','efective_date')->where('type','kebijakan')->get()); 
        }else{
            return array("data"=>GuideLine::select('id','description','link_url','title as title_guide','efective_date')->where('type',$request->type)->get());
        }

    }

    public function getGuideIndexById(Request $request){

        return GuideLine::select('id','description','link_url','title as title_guide','efective_date')->where('id',$request->id)->get();

    }

    public function storeGuideLine(Request $request){
        $tambah                 = New GuideLine();
        $tambah->title          = $request->title;
        $tambah->efective_date  = $request->efective_date;        
        $tambah->description    = $request->description;
        $tambah->link_url       = $request->link;
        $tambah->type           = $request->type;        
        $tambah->date_add       = date('Y-m-d h:i:s');
        $tambah->save();
    }

    public function updateGuideLine(Request $request){
        $update                 = GuideLine::where('id',$request->id)->first();
        $update->description    = $request->description;
        $update->link_url       = $request->link;
        $update->title          = $request->title;
        $update->save();
    }

    public function deleteGuideLine(Request $request){

        $delete = GuideLine::where('id',$request->id)->first();
        $delete->delete();
    }
}
