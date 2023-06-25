<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GaleriAcara extends Model
{

    protected $table = 'galeri_acara';

    protected $primaryKey = 'id_galeri_acara';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'galeri_acara', 'id_acara'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
    ];

    public function acaras()
    {
        return $this->belongsTo(Acara::class, 'id_acara');
    } 
}
