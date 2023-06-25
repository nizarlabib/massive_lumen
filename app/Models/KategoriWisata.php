<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class KategoriWisata extends Model
{
    protected $table = 'kategori_wisata';

    protected $primaryKey = 'id_kategori_wisata';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nama_kategori_wisata', 'deskripsi_kategori_wisata'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
    ];

    public function wisatas()
    {
        return $this->hasMany(Wisata::class, 'id_kategori_wisata');
    } 
}
