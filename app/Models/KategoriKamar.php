<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriKamar extends Model
{
    protected $table = 'kategori_kamar';
    protected $primaryKey = 'id_kategori_kamar';
    public $timestamps = true; // kalau tabel ini tidak ada created_at & updated_at

    public function kamar()
    {
        return $this->hasMany(Kamar::class, 'id_kategori_kamar', 'id_kategori_kamar');
    }
}
