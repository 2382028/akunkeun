<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
     // Nama tabel di database
    protected $table = 'invoice';

    // Primary key
    protected $primaryKey = 'id_invoice';

    // Kolom yang bisa diisi
    protected $fillable = [
        'url_invoice',
        'id_pembayaran'
    ];

    /**
     * Relasi ke Pembayaran (Many-to-One)
     */
      public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'id_pembayaran', 'id_pembayaran');
    }
}
