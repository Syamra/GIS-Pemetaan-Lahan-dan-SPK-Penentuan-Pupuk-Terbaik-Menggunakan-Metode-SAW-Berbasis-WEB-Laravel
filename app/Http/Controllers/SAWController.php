<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Alternatif;
use App\Models\Penilaian;
use App\Models\HasilSAW;
use App\Models\PemetaanLahan;
use Illuminate\Http\Request;

class SAWController extends Controller
{
    // 1. Menampilkan Form Input Penilaian Matriks
    public function formPenilaian($id_lahan)
    {
        $lahan = PemetaanLahan::findOrFail($id_lahan);
        $kriteria = Kriteria::orderBy('id_kriteria')->get();
        $alternatif = Alternatif::orderBy('id_pupuk')->get();

        // Ambil penilaian sebelumnya agar nilai tetap tampil pada form.
        $penilaianLama = Penilaian::where('id_lahan', $id_lahan)
            ->get()
            ->groupBy('id_pupuk');

        return view('penilaian.form', compact(
            'lahan',
            'kriteria',
            'alternatif',
            'penilaianLama'
        ));
    }

    // 2. Menyimpan Data Matriks Penilaian
    public function simpanPenilaian(Request $request, $id_lahan)
    {
        $request->validate([
            'nilai' => ['required', 'array'],
            'nilai.*' => ['required', 'array'],
            'nilai.*.*' => ['required', 'numeric', 'min:1', 'max:5'],
        ], [
            'nilai.required' => 'Matriks penilaian belum diisi.',
            'nilai.*.*.required' => 'Semua kriteria harus diisi.',
            'nilai.*.*.numeric' => 'Nilai kriteria harus berupa angka.',
            'nilai.*.*.min' => 'Nilai kriteria minimal 1.',
            'nilai.*.*.max' => 'Nilai kriteria maksimal 5.',
        ]);

        // Hapus nilai lama hanya setelah data baru lolos validasi.
        Penilaian::where('id_lahan', $id_lahan)->delete();

        $dataInsert = [];
        foreach ($request->input('nilai', []) as $id_pupuk => $nilaiKriteria) {
            foreach ($nilaiKriteria as $id_kriteria => $nilai) {
                $dataInsert[] = [
                    'id_lahan'    => $id_lahan,
                    'id_pupuk'    => $id_pupuk,
                    'id_kriteria' => $id_kriteria,
                    'nilai'       => $nilai,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
        }

        if (!empty($dataInsert)) {
            Penilaian::insert($dataInsert);
        }

        return back()->with('success', 'Matriks penilaian berhasil disimpan! Silakan klik Hitung Rekomendasi SAW.');
    }

    // 3. Eksekusi SAW sekaligus menyiapkan detail semua tahap perhitungan.
    public function hitung($id_lahan)
    {
        $lahan = PemetaanLahan::findOrFail($id_lahan);
        $kriteria = Kriteria::orderBy('id_kriteria')->get();
        $alternatif = Alternatif::orderBy('id_pupuk')->get();
        $penilaian = Penilaian::where('id_lahan', $id_lahan)->get();

        if ($kriteria->isEmpty()) {
            return back()->with('error', 'Data kriteria belum tersedia. Tambahkan kriteria terlebih dahulu.');
        }

        if ($alternatif->isEmpty()) {
            return back()->with('error', 'Data alternatif pupuk belum tersedia. Tambahkan alternatif terlebih dahulu.');
        }

        if ($penilaian->isEmpty()) {
            return back()->with('error', 'Data penilaian untuk lahan ini belum diinput. Isi dan simpan matriks terlebih dahulu.');
        }

        // Susun lookup X berdasarkan alternatif dan kriteria.
        $lookupNilai = [];
        foreach ($penilaian as $item) {
            $lookupNilai[$item->id_pupuk][$item->id_kriteria] = (float) $item->nilai;
        }

        // Pastikan seluruh kombinasi alternatif-kriteria mempunyai nilai yang valid.
        foreach ($alternatif as $pupuk) {
            foreach ($kriteria as $k) {
                if (!isset($lookupNilai[$pupuk->id_pupuk][$k->id_kriteria])) {
                    return back()->with(
                        'error',
                        'Nilai untuk alternatif ' . $pupuk->nama_pupuk . ' pada kriteria ' . $k->kode .
                        ' belum tersedia. Silakan lengkapi dan simpan kembali matriks penilaian.'
                    );
                }

                $nilai = $lookupNilai[$pupuk->id_pupuk][$k->id_kriteria];
                if ($nilai <= 0) {
                    return back()->with(
                        'error',
                        'Nilai pada ' . $pupuk->nama_pupuk . ' untuk kriteria ' . $k->kode .
                        ' harus lebih besar dari 0 agar dapat dinormalisasi.'
                    );
                }
            }
        }

        // Siapkan informasi kriteria dan matriks keputusan awal (X).
        $detail = [
            'nama_lahan' => $lahan->nama_lahan,
            'waktu' => now()->format('d-m-Y H:i:s'),
            'kriteria' => [],
            'alternatif' => [],
            'matriks_awal' => [],
            'pembanding' => [],
            'normalisasi' => [],
            'terbobot' => [],
            'preferensi' => [],
            'ranking' => [],
        ];

        foreach ($kriteria as $k) {
            $atribut = strtolower(trim((string) $k->atribut));
            if (!in_array($atribut, ['benefit', 'cost'], true)) {
                return back()->with(
                    'error',
                    'Atribut kriteria ' . $k->kode . ' belum benar. Gunakan atribut benefit atau cost.'
                );
            }

            $bobotPersen = (float) $k->bobot;
            $detail['kriteria'][] = [
                'id_kriteria' => $k->id_kriteria,
                'kode' => $k->kode,
                'nama_kriteria' => $k->nama_kriteria,
                'atribut' => $atribut,
                'bobot' => $bobotPersen,
                'bobot_desimal' => $bobotPersen / 100,
            ];
        }

        foreach ($alternatif as $pupuk) {
            $detail['alternatif'][] = [
                'id_pupuk' => $pupuk->id_pupuk,
                'kode' => $pupuk->kode,
                'nama_pupuk' => $pupuk->nama_pupuk,
            ];

            foreach ($kriteria as $k) {
                $detail['matriks_awal'][$pupuk->id_pupuk][$k->id_kriteria] =
                    $lookupNilai[$pupuk->id_pupuk][$k->id_kriteria];
            }
        }

        // Tahap 1: cari MAX untuk benefit dan MIN untuk cost.
        $nilaiPembanding = [];
        foreach ($kriteria as $k) {
            $nilaiKolom = [];
            foreach ($alternatif as $pupuk) {
                $nilaiKolom[] = $detail['matriks_awal'][$pupuk->id_pupuk][$k->id_kriteria];
            }

            $atribut = strtolower(trim((string) $k->atribut));
            $nilaiPembanding[$k->id_kriteria] = $atribut === 'benefit'
                ? max($nilaiKolom)
                : min($nilaiKolom);

            $detail['pembanding'][$k->id_kriteria] = $nilaiPembanding[$k->id_kriteria];
        }

        // Tahap 2 dan 3: normalisasi R dan hasil normalisasi dikali bobot.
        $hasilAkhir = [];
        foreach ($alternatif as $pupuk) {
            $idPupuk = $pupuk->id_pupuk;
            $skorPreferensi = 0;

            foreach ($kriteria as $k) {
                $idKriteria = $k->id_kriteria;
                $nilaiX = $detail['matriks_awal'][$idPupuk][$idKriteria];
                $pembanding = $nilaiPembanding[$idKriteria];
                $atribut = strtolower(trim((string) $k->atribut));
                $bobotDesimal = (float) $k->bobot / 100;

                $nilaiNormalisasi = $atribut === 'benefit'
                    ? $nilaiX / $pembanding
                    : $pembanding / $nilaiX;

                $nilaiTerbobot = $nilaiNormalisasi * $bobotDesimal;

                $detail['normalisasi'][$idPupuk][$idKriteria] = $nilaiNormalisasi;
                $detail['terbobot'][$idPupuk][$idKriteria] = $nilaiTerbobot;
                $skorPreferensi += $nilaiTerbobot;
            }

            $hasilAkhir[$idPupuk] = $skorPreferensi;
            $detail['preferensi'][$idPupuk] = $skorPreferensi;
        }

        // Tahap 4: urutkan skor preferensi dari terbesar ke terkecil.
        arsort($hasilAkhir, SORT_NUMERIC);

        // Simpan hasil SAW yang baru ke database.
        HasilSAW::where('id_lahan', $id_lahan)->delete();

        $peringkat = 1;
        foreach ($hasilAkhir as $idPupuk => $skor) {
            HasilSAW::create([
                'id_lahan'         => $id_lahan,
                'id_pupuk'         => $idPupuk,
                'nilai_preferensi' => round($skor, 4),
                'peringkat'        => $peringkat,
                'tgl_perhitungan'  => now(),
            ]);

            $dataAlternatif = $alternatif->firstWhere('id_pupuk', $idPupuk);
            $detail['ranking'][] = [
                'peringkat' => $peringkat,
                'id_pupuk' => $idPupuk,
                'kode' => $dataAlternatif->kode ?? ('A' . $peringkat),
                'nama_pupuk' => $dataAlternatif->nama_pupuk ?? 'Alternatif ' . $idPupuk,
                'nilai_preferensi' => $skor,
            ];

            $peringkat++;
        }

        // Redirect ke halaman form yang sama; hasil detail ditampilkan melalui flash session.
        return back()
            ->with('success', 'Perhitungan SAW berhasil. Seluruh tahapan dan ranking ditampilkan di bawah halaman.')
            ->with('hasilPerhitunganSAW', $detail);
    }
}
