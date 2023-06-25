<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{

    protected $table = 'transaksi';

    protected $primaryKey = 'id_transaksi';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'total_transaksi', 'status_transaksi', 'id_user', 'id_wisata'
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

    public function wisatas()
    {
        return $this->belongsToMany(Wisata::class, 'transaksi_wisata', 'id_wisata', 'id_transaksi');
    }
}
