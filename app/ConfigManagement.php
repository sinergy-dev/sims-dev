<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfigManagement extends Model
{
	protected $table = 'dvg_cm';
	protected $primaryKey = 'no';
	protected $fillable = ['tgl', 'nik_pic', 'tanggal_config', 'hostname', 'perangkat', 'perubahan', 'resiko', 'downtime', 'keterangan'];
}