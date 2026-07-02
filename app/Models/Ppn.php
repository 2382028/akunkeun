<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ppn extends Model
{
    use HasFactory;

    protected $table = 'ppn';
    protected $primaryKey = 'nilai_ppn';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'nilai_ppn',
    ];
}