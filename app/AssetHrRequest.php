<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AssetNotesTransaction;
use DB;

class AssetHrRequest extends Model
{
    //
    protected $table = 'tb_asset_hr_request';
    protected $primaryKey = 'id_request';
    protected $fillable = ['id_request','kategori_request', 'nama', 'qty','nik','status','link','merk','kategori','keperluan','duration','duration_date_time','reason','link_drive','created_at','updated_at'];

    protected $appends = ['notes'];

    public function getNotesAttribute()
    {
        $data = AssetNotesTransaction::select('id_request','users.name','notes','tb_asset_hr_notes_transaction.created_at',
            DB::raw('CASE WHEN (users.avatar) is null THEN users.avatar_original WHEN (users.avatar_original) is null THEN gambar 
                            ELSE (users.avatar) END AS image'))
            ->join('users','users.nik','=','tb_asset_hr_notes_transaction.nik')
            ->where('id_request',$this->id_request)
            ->where('notes','!=',null)
            ->orderBy('tb_asset_hr_notes_transaction.created_at','DESC')->get();

        return $data;
    }
}
