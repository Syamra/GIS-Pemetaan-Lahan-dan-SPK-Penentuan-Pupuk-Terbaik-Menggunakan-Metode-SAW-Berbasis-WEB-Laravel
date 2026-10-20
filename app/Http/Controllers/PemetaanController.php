<?php

namespace App\Http\Controllers;

use App\Models\PemetaanLahan;
use App\Models\PemilikLahan;
use Illuminate\Http\Request;

class PemetaanController extends Controller
{
    // Menampilkan halaman peta dan daftar lahan
    public function index()
    {
        // Ambil lahan beserta relasi pemilik dan hasil SAW
        $lahans = PemetaanLahan::with(['hasilSAW.pupuk', 'pemilik'])->get();
        
        // Ambil semua data pemilik untuk ditampilkan di dropdown form
        $pemiliks = PemilikLahan::orderBy('nama_pemilik', 'asc')->get();

        return view('pemetaan.index', compact('lahans', 'pemiliks'));
    }

    // Menampilkan halaman Landing Page untuk Masyarakat
    public function landing()
    {
        // Ambil semua data lahan beserta relasi rekomendasi SAW
        $lahans = PemetaanLahan::with('hasilSAW.pupuk')->get();
        
        // Hitung statistik untuk bagian "Tentang"
        $total_lahan = $lahans->count();
        $sum_luas_lahan = $lahans->sum('luas_lahan'); // Asumsi satuan Hektar (Ha)
        
        // Ambil data alternatif pupuk untuk bagian "Informasi Pupuk" (pengganti info tanah)
        $pupuks = \App\Models\Alternatif::all();

        return view('landing', compact('lahans', 'total_lahan', 'sum_luas_lahan', 'pupuks'));
    }

    // Menyimpan data polygon lahan baru dari Leaflet
    public function store(Request $request)
    {
        $request->validate([
            'id_pemilik' => 'required|exists:pemilik_lahan,id_pemilik',
            'luas_lahan' => 'required|numeric',
            'batas_polygon' => 'required',
        ]);

        PemetaanLahan::create([
            'id_pemilik' => $request->id_pemilik,
            'luas_lahan' => $request->luas_lahan,
            'batas_polygon' => $request->batas_polygon,
        ]);

        return response()->json(['success' => true]);
    }

    // Menghapus data lahan
    public function destroy($id)
    {
        $lahan = PemetaanLahan::findOrFail($id);
        $lahan->delete();

        return back()->with('success', 'Data lahan berhasil dihapus!');
    }
}