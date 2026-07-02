<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningKaryawan extends Model
{
    use HasFactory;

    protected $table = 'rekening_karyawans';
    protected $primaryKey = 'id_karyawan'; 
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false; 

    protected $fillable = [
        'id_karyawan', 'bank', 'no_rekening'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }
}
