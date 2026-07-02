<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembatalanSewa extends Model
{
    protected $table = 'pembatalan_sewas';

    protected $fillable = [
        'kode_pemesanan',
        'alasan_pembatalan',
        'metode_refund',
        'bukti_refund',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan');
    }
}
