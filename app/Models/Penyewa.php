<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Penyewa extends Model
{
    use HasFactory;

    protected $table = 'penyewas'; 
    protected $primaryKey = 'id_penyewa';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = ['id_penyewa'];

    public function akun()
    {
        return $this->hasOne(AkunPenyewa::class, 'id_penyewa');
    }

    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class, 'id_penyewa');
    }
}
