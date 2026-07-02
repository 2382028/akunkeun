<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPemesananKamar extends Model
{
    use HasFactory;

    protected $table = 'detail_pemesanan_kamar';
     protected $primaryKey = 'id_detail_pemesanan_kamar';
    protected $fillable = [
        'kode_pemesanan',
        'id_kamar',
        'subtotal',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'kode_pemesanan', 'kode_pemesanan');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar');
    }
}
