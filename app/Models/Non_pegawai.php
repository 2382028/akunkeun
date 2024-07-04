<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Non_pegawai extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function data_perjadinlangsung() {
        return $this->hasMany(Data_perjadinlangsung::class);
    }
}
