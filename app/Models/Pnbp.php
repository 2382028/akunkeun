<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pnbp extends Model
{
    protected $table = 'pnbp';
    protected $primaryKey = 'id_pnbp';
    public $timestamps = false;

    protected $fillable = [
        'tanggal_setoran',
        'total_setoran',
        'no_ntb',
        'bukti_pnbp',
        'status_setoran',
    ];

    public function setoran()
    {
        return $this->belongsTo(SetoranPnbp::class, 'id_pnbp', 'id_pnbp');
    }
    public function penolakan()
    {
        return $this->morphOne(Penolakan::class, 'entitas')->latestOfMany();
    }

    public function pemesanans()
    {
        // Melalui setoran PNBP
        return $this->hasManyThrough(
            Pemesanan::class,
            SetoranPnbp::class,
            'id_pnbp', // FK di SetoranPnbp
            'kode_pemesanan', // FK di Pemesanan
            'id_pnbp', // Local key di Pnbp
            'kode_pemesanan'  // Local key di SetoranPnbp
        );
    }
}


