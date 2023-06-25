<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Acara extends Model
{

    protected $table = 'acara';
    protected $primaryKey = 'id_acara';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nama_acara', 'deskripsi_acara', 'alamat_acara', 'gambar_acara', 'id_user'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function galeriacaras()
    {
        return $this->hasMany(GaleriAcara::class, 'id_acara');
    }
}
