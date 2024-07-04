<?php

namespace App\Models;

use App\Models\Ref_rkakl_program;
use App\Models\Ref_rkakl_satker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ref_rkakl_kegiatan extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ref_rkakl_program_id',
        'kode_kegiatan',
        'nama_kegiatan',
    ];

    public function getProgram()
    {
        return $this->BelongsTo(Ref_rkakl_program::class, 'ref_rkakl_program_id', 'id');
    }

    public function getSatker()
    {
        return $this->getProgram->belongsTo(Ref_rkakl_satker::class, 'ref_rkakl_satker_id', 'id');
    }

}
