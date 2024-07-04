<?php

namespace App\Models;

use App\Models\Ref_rkakl_program;
use App\Models\Ref_rkakl_kegiatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ref_rkakl_output extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ref_rkakl_kegiatan_id',
        'kode_output',
        'nama_output',
    ];

    public function getKegiatan()
    {
        return $this->belongsTo(Ref_rkakl_kegiatan::class, 'ref_rkakl_kegiatan_id', 'id');
    }
    public function getProgram()
    {
        return $this->getKegiatan->belongsTo(Ref_rkakl_program::class, 'ref_rkakl_program_id', 'id');
    }

    public function getSatker()
    {
        return $this->getProgram->belongsTo(Ref_rkakl_satker::class, 'ref_rkakl_satker_id', 'id');
    }
}

