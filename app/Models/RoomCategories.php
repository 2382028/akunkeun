<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomCategories extends Model
{
    use HasFactory;
    protected $table = 'kategori_kamar';
    protected $primaryKey = 'id_kategori_kamar';

    public function kamars()
    {
        return $this->hasMany(Kamar::class, 'id_kategori_kamar', 'id_kategori_kamar');
    }


}
