<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AkunPenyewa extends Authenticatable
{
    use HasFactory;

    protected $table = 'akun_penyewas';
    protected $primaryKey = 'id_akun_penyewa';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = ['id_akun_penyewa'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function penyewa()
    {
        return $this->belongsTo(Penyewa::class, 'id_penyewa');
    }

}
