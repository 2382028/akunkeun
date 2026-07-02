<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class InventarisBmn extends Model
{
    use HasFactory;

    protected $table = 'inventaris_bmns';
    protected $primaryKey = 'id_inventaris_bmn';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'kode_bmn',
        'nup_bmn',
        'nama_bmn',
        'merk_bmn',
        'kategori_bmn',
        'tahun_beli',
        'id_ruangan_bmn',
        'periode_pemeliharaan',
        'jadwal_pemeliharaan'
    ];

    public function ruangan()
    {
        return $this->belongsTo(RuanganBmn::class, 'id_ruangan_bmn', 'id_ruangan_bmn');
    }

    public function pemeliharaans()
    {
        return $this->morphMany(Pemeliharaan::class, 'bmn', 'bmn_type', 'bmn_id', 'id_inventaris_bmn');
    }
}
