<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawans';
    protected $primaryKey = 'id_karyawan';
    public $incrementing = true; 
    protected $keyType = 'int';

    protected $fillable = [
        'id_karyawan', 'NIP_NIK', 'email', 'password', 'nama_lengkap', 'jenis_kelamin',
        'status', 'no_telp', 'npwp', 'is_aktif', 'is_dinas',
        'jabatan_id', 'fungsi_id', 'id_ref_golongan_pangkat'
    ];

    public function golongan()
    {
        return $this->belongsTo(RefGolonganPangkat::class, 'id_ref_golongan_pangkat', 'id_ref_golongan_pangkat');
    }

    public function rekening()
    {
        return $this->hasOne(RekeningKaryawan::class, 'id_karyawan', 'id_karyawan');
    }
}
