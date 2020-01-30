<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';

    protected $primaryKey = 'nik';

    protected $fillable = [
        'nik', 'id_company', 'id_position', 'id_division', 'id_territory', 'name', 'email', 'password', 'date_of_entry', 'date_of_birth', 'address', 'phone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
