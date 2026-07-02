<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'pembayaran';

    // Primary key
    protected $primaryKey = 'id_pembayaran';

    // Kalau primary key bukan auto-increment integer, set false
    // public $incrementing = false;

    // Kalau primary key bukan tipe integer, set type
    // protected $keyType = 'string';

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'metode_pembayaran',
        'url_path',
        'kode_pemesanan'
    ];

    /**
     * Relasi ke Pemesanan (Many-to-One)
     */
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'kode_pemesanan', 'kode_pemesanan');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'id_pembayaran', 'id_pembayaran');
    }

}
