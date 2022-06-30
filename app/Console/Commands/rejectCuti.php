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
         $max_date = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join("tb_cuti_detail",'tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->select(DB::raw('MAX(date_off) as date_off'),'users.nik','tb_cuti.id_cuti','users.email','decline_reason','users.id_territory','users.id_division')
                    ->where('tb_cuti.status','n')
                    ->groupby('tb_cuti.id_cuti')
                    ->having(DB::raw("DATEDIFF(date_off, now())"), '=', '0')
                    ->get();

        print_r($max_date);

        syslog(LOG_ERR, "Reject Cuti");
        syslog(LOG_ERR, "-------------------------");

        foreach ($max_date as $data) {
            // $update = Cuti::where('id_cuti',$data->id_cuti)->first();
            // $update->status = 'd';
            // $update->decline_reason = 'Di Reject oleh sistem karena hari cuti telah kadaluwarsa';
            // $update->update();

            syslog(LOG_ERR, "max date". $data->date_off);

            $nik = $data->nik;
            $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
            $ter = $territory->id_territory;
            $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
            $div = $division->id_division;
            $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
            $pos = $position->id_position; 
            $company = DB::table('users')->select('id_company')->where('nik',$nik)->first();
            $com = $company->id_company;

            if ($ter != NULL) {
                if ($pos == 'MANAGER' || $pos == 'ENGINEER MANAGER' || $pos == 'OPERATION DIRECTOR') {
                    if ($div == 'PMO' || $div == 'MSM') {
                        $nik_kirim = DB::table('users')->select('users.email')->where('email','nabil@sinergy.co.id')->where('id_company','1')->first();
                    } else if ($div == 'FINANCE' || $div == 'SALES' || $div == 'OPERATION') {
                        $nik_kirim = DB::table('users')->select('users.email')->where('email','rony@sinergy.co.id')->where('id_company','1')->first();
                    }else{
                        $nik_kirim = DB::table('users')->select('users.email')->where('email','nabil@sinergy.co.id')->where('id_company','1')->first();
                    }
                }else if ($ter == 'DPG') {
                    $nik_kirim = DB::table('users')->select('users.email')->where('id_position','ENGINEER MANAGER')->where('id_company','1')->first();
                }else if ($div == 'WAREHOUSE'){
                    $nik_kirim = DB::table('users')->select('users.email')->where('email','elfi@sinergy.co.id')->where('id_company','1')->first();
                }else if ($div == 'BCD'){
                    $nik_kirim = DB::table('users')->select('users.email')->where('id_position','MANAGER')->where('id_territory', 'BCD')->where('id_company','1')->first();
                }else{
                    $nik_kirim = DB::table('users')->select('users.email')->where('id_territory',$data->id_territory)->where('id_position','MANAGER')->where('id_division',$data->id_division)->where('id_company','1')->first();
                }
                

                if ($pos == "MANAGER" && $div == "MSM"){
                    $kirim = [User::where('email', $nik_kirim->email)->first()->email,'rony@sinergy.co.id'];
                } else {
                    $kirim = User::where('email', $nik_kirim->email)->first()->email;
                }         
                
            }else{
                if ($div == 'HR') {
                    if($pos == 'HR MANAGER'){
                        $kirim = DB::table('users')->select('users.email')->where('email','nabil@sinergy.co.id')->where('id_company','1')->first()->email;
                    }else{
                        $kirim = DB::table('users')->select('users.email')->where('id_position','HR MANAGER')->where('id_division',$data->id_division)->where('id_company','1')->first()->email;
                    }
                }else if($pos == 'MANAGER'){
                    $kirim = DB::table('users')->select('users.email')->where('email','rony@sinergy.co.id')->where('id_company','1')->first()->email;
                }else{
                    $kirim = DB::table('users')->select('users.email')->where('id_position','MANAGER')->where('id_division',$data->id_division)->where('id_company','1')->first()->email;
                }
            }

           
            $decline_id = CutiDetil::where('id_cuti',$data->id_cuti)->get();
            $update_cuti = Cuti::where('id_cuti',$data->id_cuti)->first();
            $update_cuti->status = 'd';
            $update_cuti->update();

            foreach ($decline_id as $decline_id) {
                $udpate_detil = CutiDetil::where('idtb_cuti_detail',$decline_id->idtb_cuti_detail)->update(array('status' => 'REJECT'));            
            }

            $name_cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->select('users.name')
                    ->where('id_cuti', $data->id_cuti)->first();

            $hari_rejected = DB::table('tb_cuti')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->select(db::raw('count(tb_cuti_detail.id_cuti) as days'),'tb_cuti.date_req','tb_cuti.reason_leave','decline_reason',DB::raw('group_concat(date_off) as dates'),DB::raw("(CASE WHEN (tb_cuti.status = 'd') THEN 'c' ELSE tb_cuti.status END) as status"))
                ->groupby('tb_cuti_detail.id_cuti')
                ->where('tb_cuti.id_cuti', $data->id_cuti)
                ->first();

            $hari = collect(['cuti_accept'=>$hari_rejected]);

            $ardetil = explode(',', $hari_rejected->dates); 

            $ardetil_after = "";

            Mail::to($data->email)->cc($kirim)->send(new CutiKaryawan($name_cuti,$hari,$ardetil,$ardetil_after,'[SIMS-App] Permohonan Cuti (Rejected by Sistem)'));

            syslog(LOG_ERR, "Reject cuti for". $name_cuti);
            syslog(LOG_ERR, "cc cuti". $kirim);
            syslog(LOG_ERR, "hari reject". $hari);

        }
    }
}
