<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HRCrud;
use App\User;
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
// use Image;
use Intervention\Image\ImageManagerStatic as Image;

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
    
    public function index(Request $request)
    {
        // $hr = HRCrud::all();
        // return view('HR/human_resource')->with('hr', $hr);
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $hr = DB::table('users')
                ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                ->select('users.nik', 'users.name', 'users.id_position', 'users.id_division', 'users.id_territory', 'tb_company.code_company','users.email','users.date_of_entry','users.date_of_birth','users.address','users.phone','users.password','users.id_company','users.gambar','status_karyawan')
                ->where('users.status_karyawan','!=','dummy')
                ->where('users.email','!=','dev@sinergy.co.id')
                ->where('tb_company.id_company','1')
                ->get();

        $hr_msp = DB::table('users')
                ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                ->select('users.nik', 'users.name', 'users.id_position', 'users.id_division', 'users.id_territory', 'tb_company.code_company','users.email','users.date_of_entry','users.date_of_birth','users.address','users.phone','users.password','users.id_company','users.gambar','status_karyawan')
                ->where('users.status_karyawan','!=','dummy')
                ->where('users.email','!=','dev@sinergy.co.id')
                ->where('tb_company.id_company','2')
                ->get();

        $code = $request['code_input'];


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
        }

        return view('HR/human_resource', compact('hr','hr_msp','notif','notifOpen','notifsd','notiftp','ter','code', 'notifClaim'));
    }

    public function getemps(Request $request)
    {
        $cari = $request['search'];

        $hr = DB::table('users')
                ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                ->select('users.nik', 'users.name', 'users.id_position', 'users.id_division', 'users.id_territory', 'tb_company.code_company','users.email','users.date_of_entry','users.date_of_birth','users.address','users.phone','users.password','users.id_company','users.gambar')
                ->where('users.status_karyawan','!=','dummy')
                ->where('users.email','!=','dev@sinergy.co.id')
                ->where('tb_company.id_company','1')
                ->where('name','like','%'.$cari.'%')
                ->paginate();

        $hr_msp = DB::table('users')
                ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                ->select('users.nik', 'users.name', 'users.id_position', 'users.id_division', 'users.id_territory', 'tb_company.code_company','users.email','users.date_of_entry','users.date_of_birth','users.address','users.phone','users.password','users.id_company','users.gambar')
                ->where('users.status_karyawan','!=','dummy')
                ->where('users.email','!=','dev@sinergy.co.id')
                ->where('tb_company.id_company','2')
                ->paginate(9);

        return view('HR/human_resource', compact('hr','hr_msp'));

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
                ->select('nik','name','email','date_of_entry','date_of_birth','address','phone','password','id_division','id_position','id_territory','id_company')
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
            'password' => 'required|string|min:6|confirmed',
            'company' => 'required',
            'date_of_entry' => 'required',
            'date_of_birth' => 'required',
        ]); 


        $tambah = new User();
        $tambah->nik = $nik;
        $tambah->name = $request['name'];
        $tambah->password = Hash::make($request['password']);
        $tambah->email = $request['email'];
        $tambah->id_company = $request['company'];
        $tambah->status_karyawan = 'belum_cuti';

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

        $tambah->date_of_entry = $request['date_of_entry'];
        $tambah->date_of_birth = $request['date_of_birth'];
        $tambah->address = $request['address'];
        $tambah->phone = $request['phone_number'];
        $tambah->save();

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
        $id_company = $request['company_update'];
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

        if ($cek < 2) {
        	$nomor = '0';
        }else{
            $nomor = $cek-1;
        }
        
        $nims = $company->id_company . $year_entry. $month_entry . $year_birth. $month_birth. $nomor;

        $update = User::where('nik',$nik)->first();
        if ($check_nik->date_of_entry !=  $request['date_of_entry_update'] && $check_nik->date_of_entry !=  $request['date_of_birth_update']) {
            $update->nik =  $nims;
        }
        $update->name = $request['name'];
        $update->email = $request['email'];
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

        $update->date_of_birth = $request['date_of_birth_update'];
        $update->date_of_entry = $request['date_of_entry_update'];
        $update->address = $request['address'];
        $update->phone = $request['phone_number'];

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

        $kirim = User::select('email')->where('email', 'ladinar@sinergy.co.id')->first();
        Notification::send($kirim, new DeleteUser($arr));

        $hapus = HRCrud::find($nik);
        $hapus->delete();

        return redirect()->back()->with('alert', 'Deleted!');
    }

    public function user_profile()
    {
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
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','tb_territory.name_territory','users.date_of_entry','address',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER' || $div == 'TECHNICAL PRESALES' && $pos == 'STAFF'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.id_division','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'TECHNICAL' && $pos == 'MANAGER'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'FINANCE' && $pos == 'MANAGER'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'FINANCE' && $pos == 'STAFF'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'TECHNICAL PRESALES' && $pos == 'STAFF'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.id_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'PMO' && $pos == 'MANAGER'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'PMO' && $pos == 'STAFF'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'PMO' && $pos == 'ADMIN'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'TECHNICAL' && $pos == 'INTERNAL IT'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'SALES' && $ter == ''){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                        ->where('users.nik',$nik)
                        ->first();
        }
        else{
           $user_profile = DB::table('users')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry','address',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
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
         
        return view('HR/profile', compact('notif','notifOpen','notifsd','notiftp','ter','user_profile', 'notifClaim'));
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
        $update->nik            = $nims;
        $update->name           = $req['name'];
        $update->email          = $req['email'];
        $update->date_of_birth  = $req['date_of_birth'];
        $update->date_of_entry  = $req['date_of_entry'];
        $update->phone          = $req['phone'];

        // Disini proses mendapatkan judul dan memindahkan letak gambar ke folder image
        // $this->validate($request, [
        //   'image'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:'
        // ]);

        if($req->file('gambar') == "")
        {
            $update->gambar = $update->gambar;
        } 
        else
        {
            
            $file       = $req->file('gambar');
            $fileName   = $file->getClientOriginalName();


            Image::make($file->getRealPath())->resize(1024, 1024)->save('image/'.$fileName);

            // $req->file('gambar')->move("image/", $fileName);
            
            $update->gambar = $fileName;

        }
        
        $update->update();

        return redirect()->back()->with('success','Successfully Updated Profile!');

    }

    public function changePassword(Request $req){

        $nik = $req['nik_profile'];

        $update = User::where('nik',$nik)->first();

        if (!(Hash::check($req->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }
 
        if(strcmp($req->get('current-password'), $req->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }
 
        $validatedData = $req->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6',
        ]);
 
        $update->password = bcrypt($req->get('new-password'));

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
}
