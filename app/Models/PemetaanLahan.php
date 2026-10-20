<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemetaanLahan extends Model
{
    protected $table = 'pemetaan_lahan';
    protected $primaryKey = 'id_lahan';
    protected $guarded = [];

    protected $fillable = ['id_pemilik', 'nama_lahan', 'luas_lahan', 'batas_polygon'];

    // Relasi ke Pemilik Lahan
    public function pemilik() {
        return $this->belongsTo(PemilikLahan::class, 'id_pemilik', 'id_pemilik');
    }

    public function penilaian() { 
        return $this->hasMany(Penilaian::class, 'id_lahan', 'id_lahan'); 
    }
    
    public function hasilSAW() { 
        return $this->hasMany(HasilSAW::class, 'id_lahan', 'id_lahan')->orderBy('peringkat', 'asc'); 
    }
}