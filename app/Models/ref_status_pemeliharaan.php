<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ref_status_pemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'ref_status_pemeliharaans';
    protected $primaryKey = 'id_ref_status_pemeliharaan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_ref_status_pemeliharaan',
        'deskripsi_status'
    ];
}

