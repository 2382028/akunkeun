<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokBarangPesanan extends Model
{
    use HasFactory;

    protected $table = 'kelompok_barang_pesanans';
    public $timestamps = false;
    protected $primaryKey = 'id_kelompok_barang_pesanan';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'nomor_surat_pesanan',
        'nama_bmn',
        'jumlah_bmn',
        'nilai_nego_pp',
        'nilai_nego_penyedia',
        'nilai_disepakati',
    ];
    public function pesanan()
    {
        return $this->belongsTo(SuratPemeliharaan::class, 'nomor_surat_pesanan', 'nomor_surat');
    }
}
