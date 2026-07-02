<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
     protected $table = 'kamar';
    protected $primaryKey = 'id_kamar';
    protected $fillable = ['lantai', 'nomor_kamar', 'harga_per_malam', 'status_kamar', 'id_kategori_kamar'];

    public function fasilitas()
    {
        return $this->belongsToMany(
            FasilitasSewa::class,
            'detail_kamar',
            'id_kamar',           // kolom pivot untuk kamar
            'id_fasilitas_sewa'   // kolom pivot untuk fasilitas
        )
        ->using(DetailKamar::class)
        ->withPivot('jumlah')
        ->withTimestamps();
    }
    public function detailKamar()
{
    return $this->hasMany(DetailPemesananKamar::class, 'id_kamar', 'id_kamar');
}
    public function kategori()
    {
        return $this->belongsTo(KategoriKamar::class, 'id_kategori_kamar', 'id_kategori_kamar');
    }
}
