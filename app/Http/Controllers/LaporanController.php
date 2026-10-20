<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PemetaanLahan;
use App\Models\HasilSAW;
use App\Models\Kriteria;
use App\Models\Alternatif;
use App\Models\Penilaian;

class LaporanController extends Controller
{
    public function index()
    {
        $lahans = PemetaanLahan::has('hasilSAW')->with('hasilSAW.pupuk')->get();
        return view('laporan.index', compact('lahans'));
    }

    public function cetakSaw($id_lahan)
    {
        $lahan = PemetaanLahan::with('hasilSAW.pupuk')->findOrFail($id_lahan);
        
        // Ambil data pendukung untuk perhitungan detail
        $kriteria = Kriteria::orderBy('kode', 'asc')->get();
        $alternatif = Alternatif::orderBy('kode', 'asc')->get();
        $penilaian = Penilaian::where('id_lahan', $id_lahan)->get();

        // Format array Kriteria
        $kriteriaArr = [];
        foreach($kriteria as $k) {
            $kriteriaArr[] = [
                'id_kriteria' => $k->id_kriteria,
                'kode' => $k->kode,
                'nama_kriteria' => $k->nama_kriteria,
                'atribut' => $k->atribut,
                'bobot' => $k->bobot,
                'bobot_desimal' => $k->bobot / 100
            ];
        }

        // Format array Alternatif
        $alternatifArr = [];
        foreach($alternatif as $a) {
            $alternatifArr[] = [
                'id_pupuk' => $a->id_pupuk,
                'kode' => $a->kode,
                'nama_pupuk' => $a->nama_pupuk
            ];
        }

        // 1. Matriks Awal
        $matriks_awal = [];
        foreach ($alternatif as $alt) {
            foreach ($kriteria as $krit) {
                $nilai = $penilaian->where('id_pupuk', $alt->id_pupuk)->where('id_kriteria', $krit->id_kriteria)->first();
                $matriks_awal[$alt->id_pupuk][$krit->id_kriteria] = $nilai ? $nilai->nilai : 0;
            }
        }

        // 2. Nilai Pembanding (Max/Min)
        $pembanding = [];
        foreach ($kriteria as $krit) {
            $nilai_kriteria = [];
            foreach ($alternatif as $alt) {
                $nilai_kriteria[] = $matriks_awal[$alt->id_pupuk][$krit->id_kriteria];
            }
            $pembanding[$krit->id_kriteria] = $krit->atribut == 'benefit' ? max($nilai_kriteria) : min($nilai_kriteria);
        }

        // 3. Normalisasi & Terbobot
        $normalisasi = [];
        $terbobot = [];
        foreach ($alternatif as $alt) {
            foreach ($kriteria as $krit) {
                $x = $matriks_awal[$alt->id_pupuk][$krit->id_kriteria];
                $p = $pembanding[$krit->id_kriteria];
                
                if ($krit->atribut == 'benefit') {
                    $norm = $p != 0 ? $x / $p : 0;
                } else {
                    $norm = $x != 0 ? $p / $x : 0;
                }
                
                $normalisasi[$alt->id_pupuk][$krit->id_kriteria] = $norm;
                $terbobot[$alt->id_pupuk][$krit->id_kriteria] = $norm * ($krit->bobot / 100);
            }
        }

        // Susun Data Sesuai Format Hasil Hitung
        $hasilSAW = [
            'kriteria' => $kriteriaArr,
            'alternatif' => $alternatifArr,
            'matriks_awal' => $matriks_awal,
            'pembanding' => $pembanding,
            'normalisasi' => $normalisasi,
            'terbobot' => $terbobot,
            'ranking' => $lahan->hasilSAW->map(function($h) {
                return [
                    'peringkat' => $h->peringkat,
                    'id_pupuk' => $h->id_pupuk,
                    'kode' => $h->pupuk->kode,
                    'nama_pupuk' => $h->pupuk->nama_pupuk,
                    'nilai_preferensi' => $h->nilai_preferensi
                ];
            })->toArray()
        ];

        return view('laporan.cetak_saw', compact('lahan', 'hasilSAW'));
    }
}