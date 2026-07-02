<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefGolonganPangkat extends Model
{
    protected $table = 'ref_golongan_pangkats';

    // Sesuaikan primary key
    protected $primaryKey = 'id_ref_golongan_pangkat';
    public $timestamps = false;
    protected $fillable = ['golongan', 'pangkat'];

    public function karyawans()
    {
        // Foreign key di tabel karyawans adalah id_golongan
        return $this->hasMany(Karyawan::class, 'id_ref_golongan_pangkat', 'id_ref_golongan_pangkat');
    }
}

