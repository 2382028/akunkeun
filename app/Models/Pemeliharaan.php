<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Pemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'pemeliharaans';
    protected $primaryKey = 'id_pemeliharaan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pemeliharaan',
        'id_karyawan',
        'bmn_id',
        'bmn_type',
        'id_ref_status_pemeliharaan',
        'id_lokasi',
        'id_data_penyedia',
        'nomor_surat_pesanan',
        'keterangan',
    ];

    public function bmn(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'bmn_type', 'bmn_id');
    }

    public function status()
    {
        return $this->belongsTo(ref_status_pemeliharaan::class, 'id_ref_status_pemeliharaan', 'id_ref_status_pemeliharaan');
    }

    public function penyedia()
    {
        return $this->belongsTo(Data_penyedia::class, 'id_data_penyedia', 'id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }

    public function pesanan()
    {
        return $this->belongsTo(SuratPemeliharaan::class, 'nomor_surat_pesanan', 'nomor_surat');
    }
    public function penolakan()
    {
        return $this->morphMany(Penolakan::class, 'entitas');
    }
}
