<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Model;

class Penolakan extends Model
{
    protected $table = 'penolakans';
    public $timestamps = false; // matikan otomatis updated_at
    protected $fillable = [
        'entitas_type',
        'entitas_id',
        'alasan_penolakan',
        'created_at',
    ];

    public function entitas(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'entitas_type', 'entitas_id');
    }
}

