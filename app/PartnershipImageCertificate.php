<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnershipImageCertificate extends Model
{
    protected $table = 'tb_partner_image_certificate';
    protected $primaryKey = 'id';
    protected $fillable = ['id_partnership', 'nik', 'certificate'];
}
