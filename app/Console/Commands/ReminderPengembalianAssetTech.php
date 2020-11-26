<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Mail;
use App\User;
use App\Mail\MailReminderPengembalianAssetTech;

class ReminderPengembalianAssetTech extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ReminderPengembalianAssetTech:reminderpengembalianassettech';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder pengembalian asset tech';

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
        //
        $max_date = DB::table('tb_asset_transaction')
                    ->join('users','users.nik','=','tb_asset_transaction.nik_peminjam')
                    ->select('id_transaction','qty_akhir','users.email','qty_akhir','id_kat','nik_peminjam','tgl_pengembalian',DB::raw("DATEDIFF(tgl_pengembalian,now())AS date_max"))
                    ->where('status','ACCEPT')
                    ->having(DB::raw("DATEDIFF(tgl_pengembalian, now())"), '=', '-1')
                    ->orHaving(DB::raw("DATEDIFF(tgl_pengembalian, now())"), '=', '1')
                    ->get();

        foreach ($max_date as $max) {
            print_r($max);
            $barang = DB::table('tb_detail_asset_transaction')
               ->join('tb_asset','tb_asset.id_barang','tb_detail_asset_transaction.id_barang')
               ->select('nama_barang','serial_number')
               ->where('id_transaction',$max->id_transaction)
               ->get(); 

            $peminjaman = DB::table('tb_asset_transaction')
                            ->join('users', 'users.nik', '=', 'tb_asset_transaction.nik_peminjam')
                            ->join('tb_kategori_asset','tb_kategori_asset.id_kat','=','tb_asset_transaction.id_kat')
                            ->select('tgl_peminjaman', 'nik_peminjam', 'keterangan', 'name', 'keperluan','tgl_pengembalian','kategori','no_peminjaman','tb_asset_transaction.status','note','tb_asset_transaction.updated_at','no_peminjaman')
                            ->where('id_transaction',$max->id_transaction)
                            ->first();

            $total_barang = DB::table('tb_detail_asset_transaction')->where('id_transaction',$max->id_transaction)->count('id_transaction');

            $users = User::select('email','name')->where('nik',$max->nik_peminjam)->first();

            Mail::to($users)->send(new MailReminderPengembalianAssetTech($max,$users,$peminjaman,$total_barang,$barang,'[SIMS-App] Reminder - Pengembalian Asset Tech'));   
        }
    }
}
