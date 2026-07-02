<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FasilitasSewa extends Model
{
    protected $table = 'fasilitas_sewa';
    protected $primaryKey = 'id_fasilitas_sewa';

    public function kamars()
    {
        return $this->belongsToMany(
            Kamar::class,
            'detail_kamar',
            'id_fasilitas_sewa',  // kolom pivot untuk fasilitas
            'id_kamar'            // kolom pivot untuk kamar
        )
        ->using(DetailKamar::class)
        ->withPivot('jumlah')
        ->withTimestamps();
    }
}
