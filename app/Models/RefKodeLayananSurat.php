<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefKodeLayananSurat extends Model
{
    protected $table = 'ref_kode_layanan_surats';
    use HasFactory;
    protected $fillable = [
        'kode_layanan',
        'deskripsi_kode_layanan'
    ];
}
