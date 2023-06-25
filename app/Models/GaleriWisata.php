<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GaleriWisata extends Model
{

    protected $table = 'galeri_wisata';

    protected $primaryKey = 'id_galeri_wisata';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'galeri_wisata', 'id_wisata'
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
        return $this->belongsTo(Wisata::class, 'id_wisata');
    } 
}
