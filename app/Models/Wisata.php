<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{

    protected $table = 'wisata';
    protected $primaryKey = 'id_wisata';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nama_wisata', 'deskripsi_wisata', 'gambar_wisata', 'alamat_wisata', 'jam_buka_wisata', 'harga_tiket_wisata', 'id_kategori_wisata', 'id_user', 'id_transaksi'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
    ];

    public function kategoriwisatas(){
        return $this->belongsTo(KategoriWisata::class, 'id_kategori_wisata');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function galeriwisatas()
    {
        return $this->hasMany(GaleriWisata::class, 'id_wisata');
    }

    public function transaksis()
    {
        return $this->belongsToMany(TransaksiWisata::class, 'transaksi_wisata', 'id_wisata', 'id_transaksi');
    }
}
