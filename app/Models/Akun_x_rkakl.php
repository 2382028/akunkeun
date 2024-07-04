<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akun_x_rkakl extends Model
{
    use HasFactory;
    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
   protected $fillable = [
    'akun_id',
    'ref_sub_komponen_id',
    ];

    
    public function getAkun()
    {
        return $this->belongsTo(Akun::class, 'akun_id', 'id');
    }
    public function getSubKomponen()
    {
        return $this->belongsTo(Ref_rkakl_sub_komponen::class, 'ref_sub_komponen_id', 'id');
    }
    public function getKomponen()
    {
        return $this->getSubKomponen->belongsTo(Ref_rkakl_komponen::class, 'ref_rkakl_komponen_id', 'id');
    }
    public function getSuboutput()
    {
        return $this->getKomponen->belongsTo(Ref_rkakl_suboutput::class, 'ref_rkakl_suboutput_id', 'id');
    }
    public function getOutput()
    {
        return $this->getSuboutput->belongsTo(Ref_rkakl_output::class, 'ref_rkakl_output_id', 'id');
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
