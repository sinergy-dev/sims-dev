<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\User;
use Mail;
use App\Mail\AcceptPinjamanAssetMSM;
use App\Tech_asset_transaction;
use App\Kategori_Asset;
use App\LogAssetTech;

class ExpirePeminjamanAssetTech extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExpirePeminjamanAssetTech:expirepeminjamanassettech';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire Peminjaman Asset Tech';

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
        // return "sukses";
        $max_date = DB::table('tb_asset_transaction')
                    ->join('users','users.nik','=','tb_asset_transaction.nik_peminjam')
                    ->select('id_transaction','qty_akhir','users.email','qty_akhir','id_kat','nik_peminjam','tgl_peminjaman')
                    ->where('status','PENDING')
                    ->having(DB::raw("DATEDIFF(tgl_peminjaman, now())"), '<', '0')
                    ->get();

        foreach ($max_date as $max) {
            $update         = Tech_asset_transaction::where('id_transaction',$max->id_transaction)->first();
            $update->status = 'REJECT';
            $update->note   = 'Reject By System (Expired date request)';
            $update->update();

            $update_kat = Kategori_Asset::where('id_kat', $max->id_kat)->first();
            $update_kat->qty = $update_kat->qty + $max->qty_akhir;
            $update_kat->update();

            $barang = DB::table('tb_detail_asset_transaction')
                   ->join('tb_asset','tb_asset.id_barang','tb_detail_asset_transaction.id_barang')
                   ->select('nama_barang')
                   ->where('id_transaction',$max->id_transaction)
                   ->get(); 

            $total_barang = DB::table('tb_detail_asset_transaction')->where('id_transaction',$max->id_transaction)->count('id_transaction');

            $peminjaman = DB::table('tb_asset_transaction')
                        ->join('users', 'users.nik', '=', 'tb_asset_transaction.nik_peminjam')
                        ->join('tb_kategori_asset','tb_kategori_asset.id_kat','=','tb_asset_transaction.id_kat')
                        ->select('tgl_peminjaman', 'nik_peminjam', 'keterangan', 'name', 'keperluan','tgl_pengembalian','kategori','no_peminjaman','tb_asset_transaction.status','note','no_peminjaman')
                        ->where('id_transaction',$max->id_transaction)
                        ->first();

            $tambah_log                 = new LogAssetTech();
            $tambah_log->nik            = 'System';
            $tambah_log->keterangan     = "Rejecting Transaksi peminjaman [ ". $peminjaman->no_peminjaman . " ]";
            $tambah_log->save();

            $users = User::select('email','name')->where('nik',$max->nik_peminjam)->first();

            Mail::to($users)->send(new AcceptPinjamanAssetMSM($peminjaman,$users,$barang,$total_barang,'[SIMS-App] Rejecting Request - Peminjaman Barang'));   
        }
        
    }
}
