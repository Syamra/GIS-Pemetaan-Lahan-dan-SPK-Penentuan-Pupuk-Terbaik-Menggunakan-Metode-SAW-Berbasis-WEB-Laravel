<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PemetaanLahan;
use App\Models\Kriteria;
use App\Models\Alternatif;
use App\Models\HasilSAW;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil total data statistik untuk ditampilkan di Card
        $totalLahan = PemetaanLahan::count();
        $totalKriteria = Kriteria::count();
        $totalPupuk = Alternatif::count();
        
        // Menghitung berapa lahan yang sudah memiliki nilai/rekomendasi
        $lahanDinilai = HasilSAW::select('id_lahan')->distinct()->count();

        return view('dashboard.index', compact(
            'totalLahan', 
            'totalKriteria', 
            'totalPupuk', 
            'lahanDinilai'
        ));
    }
}