<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HasilSAW extends Model
{
    protected $table = 'hasil_saw';
    protected $primaryKey = 'id_hasil';
    protected $guarded = [];

    public function lahan() { 
        return $this->belongsTo(PemetaanLahan::class, 'id_lahan', 'id_lahan'); 
    }
    public function pupuk() { 
        return $this->belongsTo(Alternatif::class, 'id_pupuk', 'id_pupuk'); 
    }
}