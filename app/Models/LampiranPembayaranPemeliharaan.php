<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LampiranPembayaranPemeliharaan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pembayaran_pemeliharaan';
    public $incrementing = false;
    protected $keyType = 'int'; 

    public $timestamps = false;

    protected $fillable = [
        'id_pembayaran_pemeliharaan',
        'nama_file',
        'url_lampiran',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(
            PembayaranPemeliharaan::class,
            'id_pembayaran_pemeliharaan'
        );
    }
}

