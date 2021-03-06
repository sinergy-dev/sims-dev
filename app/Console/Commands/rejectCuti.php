<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use App\Mail\CutiKaryawan;
use Mail;
use App\Cuti;

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
                    ->select(DB::raw('MAX(date_off) as date_off'),'users.nik','tb_cuti.id_cuti','users.email','decline_reason')
                    ->where('tb_cuti.status','n')
                    ->groupby('tb_cuti.id_cuti')
                    ->having(DB::raw("DATEDIFF(date_off, now())"), '=', '0')
                    ->get();

        print_r($max_date);

        foreach ($max_date as $data) {
            $update = Cuti::where('id_cuti',$data->id_cuti)->first();
            $update->status = 'd';
            $update->decline_reason = 'Di Reject oleh sistem karena hari cuti telah kadaluwarsa';
            $update->update();

            $name_cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->select('users.name')
                    ->where('id_cuti', $data->id_cuti)->first();

            $hari = DB::table('tb_cuti')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->select(db::raw('count(tb_cuti_detail.id_cuti) as days'),'tb_cuti.date_req','tb_cuti.reason_leave','decline_reason','tb_cuti.status',DB::raw('group_concat(date_off) as dates'),DB::raw("(CASE WHEN (status = 'd') THEN 'c' ELSE status END) as status"))
                ->groupby('tb_cuti_detail.id_cuti')
                ->where('tb_cuti.id_cuti', $data->id_cuti)
                ->first();

            $ardetil = explode(',',$hari->dates);

            $ardetil_after = "";

            Mail::to($data->email)->send(new CutiKaryawan($name_cuti,$hari,$ardetil,$ardetil_after,'[SIMS-App] Permohonan Cuti (Rejected by Sistem)'));
        }
    }
}
