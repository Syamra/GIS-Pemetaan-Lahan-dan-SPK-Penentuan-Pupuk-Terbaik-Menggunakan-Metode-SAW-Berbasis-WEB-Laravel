<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemilikLahan extends Model
{
    protected $table = 'pemilik_lahan';
    protected $primaryKey = 'id_pemilik';
    
    protected $fillable = [
        'nama_pemilik', 
        'alamat', 
        'no_hp'
    ];

    public function pemetaanLahan()
    {
        return $this->hasMany(PemetaanLahan::class, 'id_pemilik', 'id_pemilik');
    }
}