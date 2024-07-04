<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class surtug_perjadinlangsung extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_perjadinlangsung',
        'paragraf_1',
        'paragraf_2',
        'paragraf_3',
    ];
}
