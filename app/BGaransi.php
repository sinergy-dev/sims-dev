<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BGaransi extends Model
{
    protected $table = 'tb_bank_garansi';
    protected $primaryKey = 'id_bank_garansi';
    protected $fillable = ['kode_proyek','nama_proyek','no_proyek', 'perusahaan', 'division', 'alamat', 'kota', 'kode_pos', 'jenis', 'penerbit', 'tgl_mulai', 'tgl_selesai', 'dok_ref', 'valuta', 'nik', 'note', 'nominal', 'jangka_waktu', 'no_dok', 'status', 'file'];
}
