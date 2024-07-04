<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pegawai extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function data_perjadinlangsung() {
        return $this->belongsTo(Data_perjadinlangsung::class);
    }

    protected $fillable = [
        'NIP_NIK',
        'nama_lengkap',
        'jenis_kelamin',
        'status',
        'golongan',
        'pangkat',
        'no_telp',
        'email',
        'password',
        'jabatan_id',
        'no_rekening',
        'is_aktif',
        'is_dinas',
    ];
}