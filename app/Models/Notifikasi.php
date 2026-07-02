<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;
    protected $table = 'notifications';
    public $timestamps = false;
    protected $fillable = [
        'id_kegiatan',
        'from',
        'to',
        'role',
        'header',
        'message',
        'route',
        'is_read',
        'versi_id',
        'created_at',
    ];
}
