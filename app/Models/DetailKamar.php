<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailKamar extends Pivot
{
    use HasFactory;

    protected $table = 'detail_kamar';

    protected $fillable = [
        'id_kamar',
        'id_fasilitas_sewa',
        'jumlah',
    ];

        public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar');
    }

    
}
