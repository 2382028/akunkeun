<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_pemeliharaans';
    protected $primaryKey = 'id_pembayaran_pemeliharaan';
    public $incrementing = true; // ubah ke false jika bukan auto increment
    protected $keyType = 'int';  // ubah ke 'string' jika bukan integer

    protected $fillable = [
        'url_pengajuan_pembayaran',
        'total_nilai_pekerjaan',
        'nilai_ppn',
        'nomor_perintah_bayar',
    ];

    public function lampiran()
    {
        return $this->hasMany(
            LampiranPembayaranPemeliharaan::class,
            'id_pembayaran_pemeliharaan',
            'id_pembayaran_pemeliharaan'
        );
    }

    public function penolakan()
    {
        return $this->morphMany(Penolakan::class, 'entitas');
    }

    public function pesanan()
    {
        return $this->hasMany(
            SuratPemeliharaan::class,
            'id_pembayaran_pemeliharaan',
            'id_pembayaran_pemeliharaan'
        );
    }
}
