<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetoranPnbp extends Model
{
    use HasFactory;
    protected $table = 'setoran_pnbp';
    protected $primaryKey = 'id_setoran_pnbp';
    public $timestamps = false;

    protected $fillable = [
        'id_pnbp',
        'kode_pemesanan',
    ];

    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class, 'kode_pemesanan', 'kode_pemesanan');
    }

    public function pnbp()
    {
        return $this->hasOne(Pnbp::class, 'id_pnbp', 'id_pnbp');
    }
}

