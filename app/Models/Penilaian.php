<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $table = 'penilaian';
    protected $primaryKey = 'id_penilaian';
    protected $guarded = [];

    public function lahan() { 
        return $this->belongsTo(PemetaanLahan::class, 'id_lahan', 'id_lahan'); 
    }
    public function pupuk() { 
        return $this->belongsTo(Alternatif::class, 'id_pupuk', 'id_pupuk'); 
    }
    public function kriteria() { 
        return $this->belongsTo(Kriteria::class, 'id_kriteria', 'id_kriteria'); 
    }
}