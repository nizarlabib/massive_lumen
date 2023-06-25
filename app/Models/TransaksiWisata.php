<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TransaksiWisata extends Model
{

    protected $table = 'transaksi_wisata';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id_transaksi', 'id_wisata'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
    ];
}
