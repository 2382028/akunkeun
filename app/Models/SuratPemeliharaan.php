<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'surat_pemeliharaans';
    protected $primaryKey = 'nomor_surat';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'nomor_surat',
        'id_pembayaran_pemeliharaan',
        'perihal',
        'url_surat',
        'created_at',
    ];

    public function pembayaranPemeliharaan()
    {
        return $this->belongsTo(
            PembayaranPemeliharaan::class,
            'id_pembayaran_pemeliharaan',
            'id_pembayaran_pemeliharaan'
        );
    }

    public function kelompokBarangPesanan()
    {
        return $this->hasMany(KelompokBarangPesanan::class, 'nomor_surat_pesanan', 'nomor_surat');
    }

    public function buktiPengembalians()
    {
        return $this->hasMany(BuktiPengembalian::class, 'nomor_surat_pesanan', 'nomor_surat');
    }
public function penolakan()
{
    return $this->morphMany(Penolakan::class, 'entitas');
}
}
