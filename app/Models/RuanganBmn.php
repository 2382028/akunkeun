<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuanganBmn extends Model
{
    use HasFactory;

    protected $table = 'ruangan_bmns';
    protected $primaryKey = 'id_ruangan_bmn';
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'kode_ruangan',
        'nama_ruangan',
    ];

    public function pemeliharaans()
    {
        return $this->morphMany(Pemeliharaan::class, 'bmn', 'bmn_type', 'bmn_id', 'id_ruangan_bmn');
    }
}
