<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiPengembalian extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [
        'nomor_surat_pesanan',
        'nama_file',
        'url_bukti',
    ];

    public function pesanan()
    {
        return $this->belongsTo(SuratPemeliharaan::class, 'nomor_surat_pesanan', 'nomor_surat');
    }
}
