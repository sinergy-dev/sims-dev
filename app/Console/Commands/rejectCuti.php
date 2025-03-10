<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use App\Mail\CutiKaryawan;
use Mail;
use App\Cuti;
use App\CutiDetil;
use App\User;

class rejectCuti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RejectCuti:rejectCuti';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis reject cuti jika permohonan cuti belum di approved dan melewati hari cuti';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $reject_detail = DB::table('tb_cuti_detail')
                    ->join("tb_cuti",'tb_cuti.id_cuti','=','tb_cuti_detail.id_cuti')
                    ->whereDate("date_off", date('Y-m-d'))
                    ->where('tb_cuti_detail.status', 'NEW')
                    ->get();

        foreach ($reject_detail as $data_reject){
            CutiDetil::where('idtb_cuti_detail',$data_reject->idtb_cuti_detail)->where('tb_cuti_detail.status','NEW')->update(array('status' => 'REJECT'));

            $count = DB::table('tb_cuti_detail')->select('date_off')->where('tb_cuti_detail.id_cuti', $data_reject->id_cuti)->where('tb_cuti_detail.status', 'NEW')->count();

            if ($count == 0) {
                $update_cuti = Cuti::where('tb_cuti.id_cuti',$data_reject->id_cuti)->first();
                $update_cuti->status = 'd';
                $update_cuti->decline_reason = 'Di Reject oleh sistem karena hari cuti telah kadaluwarsa';
                $update_cuti->update();
            } else {
                $update_cuti = Cuti::where('tb_cuti.id_cuti',$data_reject->id_cuti)->first();
                $update_cuti->status = 'n';
                $update_cuti->update();
            }

            $name_cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->select('users.name','users.email','users.nik')
                    ->where('id_cuti', $data_reject->id_cuti)->first();

            $nik = $name_cuti->nik;
            $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->where('id_company','1')->first();
            $ter = $territory->id_territory;
            $division = DB::table('users')->select('id_division')->where('nik', $nik)->where('id_company','1')->first();
            $div = $division->id_division;
            $position = DB::table('users')->select('id_position')->where('nik', $nik)->where('id_company','1')->first();
            $pos = $position->id_position; 
            $company = DB::table('users')->select('id_company')->where('nik',$nik)->where('id_company','1')->first();
            $com = $company->id_company;

            $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

            // if ($ter != NULL) {
            //     if ($pos == 'MANAGER' || $pos == 'ENGINEER MANAGER' || $pos == 'OPERATION DIRECTOR') {
            //         if ($div == 'PMO' || $div == 'MSM') {
            //             $kirim = DB::table('users')->select('users.email')->where('email','nabil@sinergy.co.id')->where('id_company','1')->first();
            //         } else if ($div == 'FINANCE' || $div == 'SALES' || $div == 'OPERATION') {
            //             $kirim = DB::table('users')->select('users.email')->where('email','rony@sinergy.co.id')->where('id_company','1')->first();
            //         }else{
            //             $kirim = DB::table('users')->select('users.email')->where('email','nabil@sinergy.co.id')->where('id_company','1')->first();
            //         }
            //     }else if ($ter == 'DPG') {
            //         $kirim = DB::table('users')->select('users.email')->where('id_position','ENGINEER MANAGER')->where('id_company','1')->first();
            //     }else if ($div == 'WAREHOUSE'){
            //         $kirim = DB::table('users')->select('users.email')->where('email','elfi@sinergy.co.id')->where('id_company','1')->first();
            //     }else if ($div == 'BCD'){
            //         $kirim = DB::table('users')->select('users.email')->where('id_position','MANAGER')->where('id_territory', 'BCD')->where('id_company','1')->first();
            //     }else if($cek_role->name_role == 'Chief Operating Officer'){
            //         $kirim = DB::table('users')->select('users.email')->where('email','rony@sinergy.co.id')->where('id_company','1')->first();
            //     }else{
            //         $kirim = DB::table('users')->select('users.email')->where('id_territory',$ter)->where('id_position','MANAGER')->where('id_division',$div)->where('id_company','1')->where('status_karyawan', '!=', 'dummy')->first();
            //     }     
                
            // }else{
            //     if ($div == 'HR') {
            //         if($pos == 'HR MANAGER'){
            //             $kirim = DB::table('users')->select('users.email')->where('email','nabil@sinergy.co.id')->where('id_company','1')->first();
            //         }else{
            //             $kirim = DB::table('users')->select('users.email')->where('id_position','HR MANAGER')->where('id_division',$div)->where('id_company','1')->first();
            //         }
            //     }else if($pos == 'MANAGER'){
            //         $kirim = DB::table('users')->select('users.email')->where('email','rony@sinergy.co.id')->where('id_company','1')->first();
            //     }else{
            //         $kirim = DB::table('users')->select('users.email')->where('id_position','MANAGER')->where('id_division',$div)->where('id_company','1')->first();
            //     }
            // }

            if(Str::contains($cek_role->name_role, 'VP')){
                $kirim = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.email')->where('roles.name','Chief Operating Officer')->where('status_karyawan','!=','dummy')->where('id_company','1')->first();
            } elseif(Str::contains($cek_role->name_role, 'Manager')){
                if($cek_role->name_role == 'People Operations & Services Manager' &&  $cek_role->name_role == 'Organizational & People Development Manager'){
                    $kirim = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.email')->where('roles.name','VP Program & Project Management')->where('status_karyawan','!=','dummy')->where('id_company','1')->first();
                } elseif ($cek_role->name_role == 'VP Sales' || $cek_role->name_role == 'VP Financial & Accounting'){
                    $kirim = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.email')->where('roles.name','Chief Executive Officer')->where('status_karyawan','!=','dummy')->where('id_company','1')->first();
                } else {
                    $kirim = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.email')->where('roles.name','like', 'VP%')->where('group',$cek_role->group)->where('status_karyawan','!=','dummy')->where('id_company','1')->first();
                }
            } elseif(!Str::contains($cek_role->name_role, 'Manager') && !Str::contains($cek_role->name_role, 'Director')) {
                if ($cek_role->name_role == 'Account Executive') {
                    $kirim = DB::table('users')->select('users.email')->where('id_territory',$ter)->where('id_position','MANAGER')->where('id_division',$div)->where('status_karyawan','!=','dummy')->where('id_company','1')->first();
                } elseif ($cek_role->group == 'Financial And Accounting') {
                    $kirim = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.email')->where('status_karyawan','!=','dummy')->where('id_company','1')->where('roles.name','VP Financial & Accounting')->first();
                } else {
                    if ($cek_role->mini_group == 'Product Development Specialist' || $cek_role->mini_group == 'Supply Chain & IT Support' || $cek_role->mini_group == 'Internal Operation Support') {
                        $kirim = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.email')->where('roles.name','like', 'VP%')->where('group',$cek_role->group)->where('status_karyawan','!=','dummy')->where('id_company','1')->first();
                    } else {
                        if ($cek_role->mini_group == 'Organizational & People Development') {
                            $kirim = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.email')
                            ->whereRaw(
                                "(`roles`.`mini_group` = ? AND `roles`.`name` LIKE ? AND `roles`.`name` != ? OR `roles`.`name` = ?)", 
                                [$cek_role->mini_group, '%Manager', 'Delivery Project Manager', 'VP Program & Project Management']
                            )
                            ->where('status_karyawan','!=','dummy')->where('id_company','1')->get()->pluck('email');
                        } else {
                            $kirim = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.email')
                                ->whereRaw(
                                    "(`roles`.`mini_group` = ? AND `roles`.`name` LIKE ?  AND `roles`.`name` != ?)", 
                                    [$cek_role->mini_group, '%Manager', 'Delivery Project Manager']
                                )
                                ->where('status_karyawan','!=','dummy')->where('id_company','1')->get()->pluck('email');
                        }
                    }
                }
            } elseif($cek_role->name_role == 'Chief Operating Officer'){
                $kirim = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.email')->where('roles.name','Chief Executive Officer')->where('status_karyawan','!=','dummy')->where('id_company','1')->first();
            }

            $hari_rejected = DB::table('tb_cuti')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->select(db::raw('count(tb_cuti_detail.id_cuti) as days'),'tb_cuti.date_req','tb_cuti.reason_leave','decline_reason',DB::raw('group_concat(date_off) as dates'),DB::raw("(CASE WHEN (tb_cuti.status = 'd') THEN 'c' ELSE tb_cuti.status END) as status"))
                ->groupby('tb_cuti_detail.id_cuti')
                ->where('tb_cuti.id_cuti', $data_reject->id_cuti)
                ->first();

            $hari = collect(['cuti_accept'=>$hari_rejected]);

            $ardetil = explode(',', $hari_rejected->dates); 

            $ardetil_after = "";

            Mail::to($name_cuti->email)->cc($kirim->email)->send(new CutiKaryawan($name_cuti,$hari,$ardetil,$ardetil_after,'[SIMS-App] Permohonan Cuti (Rejected by Sistem)'));
        }

        syslog(LOG_ERR, "Reject Cuti");
        syslog(LOG_ERR, "-------------------------");
    }
}
