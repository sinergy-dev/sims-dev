<?php

namespace App;

use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Session;
class User extends Authenticatable implements HasRoleContract
{
    use Notifiable, HasRole;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';

    protected $primaryKey = 'nik';

    protected $fillable = [
        'nik', 
        'id_company', 
        'id_position', 
        'id_division', 
        'id_territory', 
        'name', 
        'email', 
        'password', 
        'date_of_entry', 
        'date_of_birth', 
        'address', 
        'phone', 
        'no_ktp', 
        'no_kk', 
        'no_npwp', 
        'npwp_file', 
        'ktp_file', 
        'bpjs_kes', 
        'bpjs-ket',
        'id_presence_setting',
        'telegram_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new Notifications\MailResetPasswordNotification($token));
    }

    public function presence_setting(){
        return $this->hasOne(PresenceSetting::class,'id','id_presence_setting');
    }

    public function getIsDefaultPasswordAttribute(){
        return Session::get('isDefaultPassword',null);
    }

    public function setIsDefaultPasswordAttribute($value){
        return Session::put('isDefaultPassword',$value);
    }
}
