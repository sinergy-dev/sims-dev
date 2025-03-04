<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Mail\CutiKaryawan;
use Mail;

class FollowUpCuti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'FollowUpCuti:followupcuti';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Follow up cuti';

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
        $max_date = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join("tb_cuti_detail",'tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->select(DB::raw('MAX(date_off) as date_off'),'users.nik','tb_cuti.id_cuti','users.id_territory','users.id_division')
                    ->groupby('tb_cuti.id_cuti')
                    ->where("tb_cuti.status","n")
                    ->having(DB::raw("DATEDIFF(now(), date_off)"), '=', '-3')
                    ->orhaving(DB::raw("DATEDIFF(now(), date_off)"), '=', '-1')
                    ->get();

        print_r($max_date);

        foreach ($max_date as $data) {
            $nik = $data->nik;
            $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
            $ter = $territory->id_territory;
            $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
            $div = $division->id_division;
            $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
            $pos = $position->id_position; 
            $company = DB::table('users')->select('id_company')->where('nik',$nik)->first();
            $com = $company->id_company;

            $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role')->where('user_id',$nik)->first();

            if ($ter != NULL) {
                if ($pos == 'MANAGER' || $pos == 'ENGINEER MANAGER' || $pos == 'OPERATION DIRECTOR') {
                    if ($div == 'PMO') {
                        $nik_kirim = DB::table('users')->select('users.email')->where('email','nabil@sinergy.co.id')->where('id_company','1')->first();
                    }else if ($div == 'FINANCE' || $div == 'SALES' || $div == 'OPERATION') {
                        $nik_kirim = DB::table('users')->select('users.email')->where('email','rony@sinergy.co.id')->where('id_company','1')->first();
                    }else{
                        $nik_kirim = DB::table('users')->select('users.email')->where('email','nabil@sinergy.co.id')->where('id_company','1')->first();
                    }
                }else if ($ter == 'DPG') {
                    $nik_kirim = DB::table('users')->select('users.email')->where('id_position','ENGINEER MANAGER')->where('id_company','1')->first();
                }else if ($div == 'BCD'){
                    $nik_kirim = DB::table('users')->select('users.email')->where('id_position','MANAGER')->where('id_division', 'BCD')->where('id_company','1')->first();
                }else if($cek_role->name_role == 'Chief Operating Officer'){
                    $nik_kirim = DB::table('users')->select('users.email')->where('email','rony@sinergy.co.id')->where('id_company','1')->first();
                }else{
                    $nik_kirim = DB::table('users')->select('users.email')->where('id_territory',$data->id_territory)->where('id_position','MANAGER')->where('id_division',$data->id_division)->where('id_company','1')->first();
                }
                
                $kirim = DB::table('users')->where('email', $nik_kirim->email)->first()->email;

                $name_cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->select('users.name')
                    ->where('id_cuti', $data->id_cuti)->first();

                $hari = DB::table('tb_cuti')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->select(db::raw('count(tb_cuti_detail.id_cuti) as days'),'tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.status',DB::raw('group_concat(date_off) as dates'))
                    ->groupby('tb_cuti_detail.id_cuti')
                    ->where('tb_cuti.id_cuti', $data->id_cuti)
                    ->first();

                $ardetil = explode(',',$hari->dates);

                $ardetil_after = "";

                Mail::to($kirim)->send(new CutiKaryawan($name_cuti,$hari,$ardetil,$ardetil_after,'[SIMS-App] Permohonan Cuti (Follow Up)'));
                
                
            }else{
                if ($div == 'HR') {
                    if($pos == 'HR MANAGER'){
                        $nik_kirim = DB::table('users')->select('users.email')->where('email','rony@sinergy.co.id')->where('id_company','1')->first();
                    }else{
                        $nik_kirim = DB::table('users')->select('users.email')->where('id_position','HR MANAGER')->where('id_division',Auth::User()->id_division)->where('id_company','1')->first();
                    }
                }else if($div == 'MANAGER'){
                    $nik_kirim = DB::table('users')->select('users.email')->where('email','rony@sinergy.co.id')->where('id_company','1')->first();
                }else{
                    $nik_kirim = DB::table('users')->select('users.email')->where('id_position','MANAGER')->where('id_division',$data->id_division)->where('id_company','1')->first();
                }
                
                $kirim = DB::table('users')->where('email', $nik_kirim->email)->first()->email;

                $name_cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->select('users.name')
                    ->where('id_cuti', $data->id_cuti)->first();

                $hari = DB::table('tb_cuti')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->select(db::raw('count(tb_cuti_detail.id_cuti) as days'),'tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.status',DB::raw('group_concat(date_off) as dates'))
                    ->groupby('tb_cuti_detail.id_cuti')
                    ->where('tb_cuti.id_cuti', $data->id_cuti)
                    ->first();

                $ardetil = explode(',',$hari->dates);

                $ardetil_after = "";

                Mail::to($kirim)->send(new CutiKaryawan($name_cuti,$hari,$ardetil,$ardetil_after,'[SIMS-App] Permohonan Cuti (Follow Up)'));

            }

        } 
    }
}
