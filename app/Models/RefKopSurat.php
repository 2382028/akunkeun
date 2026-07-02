<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefKopSurat extends Model
{
    protected $table = 'ref_kop_surats';
    use HasFactory;
    protected $fillable = [
        'nama_kop',
        'url_kop',
        'pemilik',
        'is_aktif'
    ];
}
