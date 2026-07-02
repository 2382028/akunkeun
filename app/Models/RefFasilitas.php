<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefFasilitas extends Model
{
    use HasFactory;

    protected $table = 'ref_fasilitas';
    protected $fillable = ['nama_fasilitas', 'satuan', 'status'];
}