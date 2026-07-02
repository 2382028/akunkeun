<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pemesanan extends Model
{
    protected $table = 'pemesanans';
    protected $primaryKey = 'kode_pemesanan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_pemesanan',
        'id_penyewa',
        'tanggal_checkin',
        'tanggal_checkout',
        'subtotal',
        'status',
        'created_at',
        'updated_at',
    ];

    public function penyewa()
    {
        return $this->belongsTo(Penyewa::class, 'id_penyewa');
    }
    public function penolakan()
    {
        return $this->morphOne(Penolakan::class, 'entitas')->latestOfMany();
    }
        public function setoranPnbp()
    {
        // Satu pemesanan bisa punya satu setoran PNBP
        return $this->belongsTo(SetoranPnbp::class, 'id_setoran_pnbp', 'id_setoran_pnbp');
    }

    public function pnbp()
    {
        // Melalui setoran PNBP
        return $this->hasOneThrough(
            Pnbp::class,
            SetoranPnbp::class,
            'kode_pemesanan',  // FK di SetoranPnbp
            'id_pnbp',          // FK di Pnbp
            'kode_pemesanan',  // Local key di Pemesanan
            'id_pnbp'           // Local key di SetoranPnbp
        );
    }

    public function pembatalanSewa()
    {
        return $this->hasOne(PembatalanSewa::class, 'kode_pemesanan');
    }

        public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'kode_pemesanan', 'kode_pemesanan');
    }

    public function detailKamar()
    {
        return $this->hasMany(DetailPemesananKamar::class, 'kode_pemesanan', 'kode_pemesanan');
    }

}
