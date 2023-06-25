<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $table = 'user';

    protected $primaryKey = 'id_user';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nama_user', 'email', 'password', 'no_telepon', 'foto_profil', 'id_role_user', 'id_acara', 'id_wisata', 'id_transaksi', 'token'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
    ];

    public function roleusers(){
        return $this->belongsTo(RoleUser::class, 'id_role_user');
    }

    public function wisatas()
    {
        return $this->hasMany(Wisata::class, 'id_wisata');
    }

    public function acaras()
    {
        return $this->hasMany(Acara::class, 'id_acara');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_transaksi');
    }
}
