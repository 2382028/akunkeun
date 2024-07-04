<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ref_rkakl_suboutput extends Model
{
    use HasFactory;
    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
   protected $fillable = [
       'ref_rkakl_output_id',
       'kode_suboutput',
       'nama_suboutput',
   ];

   public function getOutput()
    {
        return $this->belongsTo(Ref_rkakl_output::class, 'ref_rkakl_output_id', 'id');
    }
   public function getKegiatan()
    {
        return $this->getOutput->belongsTo(Ref_rkakl_kegiatan::class, 'ref_rkakl_kegiatan_id', 'id');
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
