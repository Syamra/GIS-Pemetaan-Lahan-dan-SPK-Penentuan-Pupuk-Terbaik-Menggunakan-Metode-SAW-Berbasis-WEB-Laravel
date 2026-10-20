@extends('tampilan.main')

@section('content')
@php
    $hasilSAW = session('hasilPerhitunganSAW');

    // Tampilkan angka maksimal 4 desimal dan hilangkan nol di belakang koma.
    // Contoh: 1,0000 menjadi 1; 0,5000 menjadi 0,5; 0,333333 menjadi 0,3333.
    $formatAngka = function ($nilai, $maksDesimal = 4) {
        $angka = number_format((float) $nilai, $maksDesimal, ',', '.');

        if (strpos($angka, ',') !== false) {
            $angka = rtrim(rtrim($angka, '0'), ',');
        }

        return $angka;
    };
@endphp

<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <strong>Data belum valid.</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 12px; background-color: #ffffff;">
        <div class="card-header bg-white border-0 py-4" style="border-radius: 12px 12px 0 0;">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h5 class="mb-1 text-dark font-weight-bold">
                        <i class="ti-write mr-2 text-primary" style="font-size: 20px;"></i> Matriks Penilaian Lahan
                    </h5>
                    <p class="text-muted mb-0">Input skala (1-5) untuk <strong>{{ $lahan->nama_lahan }}</strong> ({{ $formatAngka($lahan->luas_lahan, 2) }} Ha)</p>
                </div>
                <a href="{{ route('pemetaan.index') }}" class="btn btn-light shadow-sm mt-2 mt-md-0" style="border-radius: 6px;">
                    <i class="ti-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <form action="{{ route('penilaian.simpan', $lahan->id_lahan) }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table mb-0" style="border-collapse: collapse;">
                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #f1f2f6; border-top: 1px solid #f1f2f6;">
                            <tr>
                                <th class="text-dark font-weight-bold border-0 px-4 py-3" style="width: 25%;">Alternatif (Pupuk)</th>
                                @foreach($kriteria as $k)
                                    <th class="text-dark font-weight-bold border-0 py-3 text-center">
                                        {{ $k->kode }}<br>
                                        <small class="text-muted" style="font-weight: normal;">{{ $k->nama_kriteria }}</small>
                                        <br><small class="text-primary">Bobot {{ $formatAngka($k->bobot, 2) }}%</small>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alternatif as $pupuk)
                                <tr style="border-bottom: 1px solid #f1f2f6;">
                                    <td class="border-0 px-4 py-3 align-middle">
                                        <span class="font-weight-bold text-dark">{{ $pupuk->kode }}</span> - <span class="text-muted">{{ $pupuk->nama_pupuk }}</span>
                                    </td>
                                    @foreach($kriteria as $k)
                                        @php
                                            $nilaiLama = isset($penilaianLama[$pupuk->id_pupuk])
                                                ? ($penilaianLama[$pupuk->id_pupuk]->where('id_kriteria', $k->id_kriteria)->first()->nilai ?? '')
                                                : '';

                                            // Nilai matriks adalah skala 1-5; hilangkan desimal .00 saat ditampilkan.
                                            $nilaiInput = old('nilai.'.$pupuk->id_pupuk.'.'.$k->id_kriteria, $nilaiLama);
                                            if ($nilaiInput !== '' && is_numeric($nilaiInput)) {
                                                $nilaiInput = rtrim(rtrim(number_format((float) $nilaiInput, 4, '.', ''), '0'), '.');
                                            }
                                        @endphp
                                        <td class="border-0 py-3 text-center align-middle">
                                            <input type="number"
                                                name="nilai[{{ $pupuk->id_pupuk }}][{{ $k->id_kriteria }}]"
                                                class="form-control text-center mx-auto"
                                                style="width: 78px; border-radius: 6px;"
                                                min="1" max="5" step="1"
                                                value="{{ $nilaiInput }}"
                                                required>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-white d-flex justify-content-end" style="border-radius: 0 0 12px 12px;">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius: 6px;">
                        <i class="ti-save mr-1"></i> Simpan Matriks
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4" style="border-radius: 12px; background: linear-gradient(135deg, #f3f0ff 0%, #ffffff 100%); border-left: 5px solid #7367f0 !important;">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap">
            <div class="pr-md-3">
                <h6 class="font-weight-bold text-dark mb-1">Sudah mengisi seluruh nilai?</h6>
                <p class="text-muted mb-0" style="font-size: 13px;">Hitung SAW untuk melihat matriks awal, normalisasi, perkalian bobot, nilai preferensi, dan ranking pupuk.</p>
            </div>
            <form action="{{ route('saw.hitung', $lahan->id_lahan) }}" method="POST" class="m-0 mt-3 mt-md-0">
                @csrf
                <button type="submit" class="btn text-white px-4 py-2 shadow-sm font-weight-bold" style="border-radius: 6px; background-color: #7367f0;">
                    <i class="ti-stats-up mr-2"></i> Hitung Rekomendasi SAW
                </button>
            </form>
        </div>
    </div>

    @if($hasilSAW)
        <div id="hasil-perhitungan" class="mt-5">
            <div class="d-flex justify-content-between align-items-start flex-wrap mb-3">
                <div>
                    <h4 class="font-weight-bold text-dark mb-1">Tahapan Perhitungan Metode SAW</h4>
                    <p class="text-muted mb-0">Lahan: <strong>{{ $hasilSAW['nama_lahan'] }}</strong> · Dihitung: {{ $hasilSAW['waktu'] }}</p>
                </div>
                <span class="badge badge-success p-2 mt-2">Perhitungan berhasil</span>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                <div class="card-header bg-white py-3"><h6 class="font-weight-bold mb-0">Tahap 1 — Bobot dan atribut kriteria</h6></div>
                <div class="card-body">
                    <p class="text-muted">Bobot yang digunakan pada nilai preferensi mengikuti data kriteria yang tersimpan di sistem. Bobot desimal = bobot persen ÷ 100.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="thead-light"><tr><th>Kode</th><th>Kriteria</th><th>Atribut</th><th class="text-right">Bobot (%)</th><th class="text-right">Bobot desimal</th><th>Rumus normalisasi</th></tr></thead>
                            <tbody>
                                @foreach($hasilSAW['kriteria'] as $c)
                                    <tr>
                                        <td>{{ $c['kode'] }}</td>
                                        <td>{{ $c['nama_kriteria'] }}</td>
                                        <td><span class="badge badge-{{ $c['atribut'] === 'benefit' ? 'success' : 'warning' }}">{{ strtoupper($c['atribut']) }}</span></td>
                                        <td class="text-right">{{ $formatAngka($c['bobot'], 2) }}%</td>
                                        <td class="text-right">{{ $formatAngka($c['bobot_desimal'], 4) }}</td>
                                        <td>{{ $c['atribut'] === 'benefit' ? 'Rij = Xij / MAX(Xj)' : 'Rij = MIN(Xj) / Xij' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="font-weight-bold"><tr><td colspan="3">Total bobot</td><td class="text-right">{{ $formatAngka(collect($hasilSAW['kriteria'])->sum('bobot'), 2) }}%</td><td class="text-right">{{ $formatAngka(collect($hasilSAW['kriteria'])->sum('bobot_desimal'), 4) }}</td><td></td></tr></tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                <div class="card-header bg-white py-3"><h6 class="font-weight-bold mb-0">Tahap 2 — Matriks keputusan awal (X)</h6></div>
                <div class="card-body">
                    <p class="text-muted">Matriks ini menampilkan nilai penilaian asli yang tersimpan untuk setiap alternatif terhadap setiap kriteria.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-center mb-0">
                            <thead class="thead-light"><tr><th>Alternatif</th>@foreach($hasilSAW['kriteria'] as $c)<th>{{ $c['kode'] }}<br><small>{{ $c['nama_kriteria'] }}</small></th>@endforeach</tr></thead>
                            <tbody>
                                @foreach($hasilSAW['alternatif'] as $pupuk)
                                    <tr><th class="text-left">{{ $pupuk['kode'] }} - {{ $pupuk['nama_pupuk'] }}</th>@foreach($hasilSAW['kriteria'] as $c)<td>{{ $formatAngka($hasilSAW['matriks_awal'][$pupuk['id_pupuk']][$c['id_kriteria']], 4) }}</td>@endforeach</tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                <div class="card-header bg-white py-3"><h6 class="font-weight-bold mb-0">Tahap 3 — Menentukan nilai MAX/MIN setiap kriteria</h6></div>
                <div class="card-body">
                    <p class="text-muted">Kriteria benefit menggunakan nilai maksimum pada kolom, sedangkan kriteria cost menggunakan nilai minimum pada kolom.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="thead-light"><tr><th>Kriteria</th><th>Atribut</th><th>Operasi</th><th class="text-right">Nilai pembanding</th></tr></thead>
                            <tbody>@foreach($hasilSAW['kriteria'] as $c)<tr><td>{{ $c['kode'] }} - {{ $c['nama_kriteria'] }}</td><td>{{ strtoupper($c['atribut']) }}</td><td>{{ $c['atribut'] === 'benefit' ? 'MAX(Xj)' : 'MIN(Xj)' }}</td><td class="text-right font-weight-bold">{{ $formatAngka($hasilSAW['pembanding'][$c['id_kriteria']], 4) }}</td></tr>@endforeach</tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                <div class="card-header bg-white py-3"><h6 class="font-weight-bold mb-0">Tahap 4 — Normalisasi matriks (R)</h6></div>
                <div class="card-body">
                    <p class="text-muted mb-2">Benefit: <code>Rij = Xij / MAX(Xj)</code>. Cost: <code>Rij = MIN(Xj) / Xij</code>.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-center mb-0">
                            <thead class="thead-light"><tr><th>Alternatif</th>@foreach($hasilSAW['kriteria'] as $c)<th>{{ $c['kode'] }}</th>@endforeach</tr></thead>
                            <tbody>
                                @foreach($hasilSAW['alternatif'] as $pupuk)
                                    <tr>
                                        <th class="text-left">{{ $pupuk['kode'] }} - {{ $pupuk['nama_pupuk'] }}</th>
                                        @foreach($hasilSAW['kriteria'] as $c)
                                            @php
                                                $nilaiX = $hasilSAW['matriks_awal'][$pupuk['id_pupuk']][$c['id_kriteria']];
                                                $pembanding = $hasilSAW['pembanding'][$c['id_kriteria']];
                                                $pembilang = $c['atribut'] === 'benefit' ? $nilaiX : $pembanding;
                                                $penyebut = $c['atribut'] === 'benefit' ? $pembanding : $nilaiX;
                                            @endphp
                                            <td>
                                                <small class="text-muted">{{ $formatAngka($pembilang, 4) }} ÷ {{ $formatAngka($penyebut, 4) }}</small><br>
                                                <strong>{{ $formatAngka($hasilSAW['normalisasi'][$pupuk['id_pupuk']][$c['id_kriteria']], 4) }}</strong>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="small text-muted mt-2">Contoh perhitungan ditampilkan pada tabel hasil normalisasi; nilai pembagi MAX/MIN setiap kolom dapat dilihat pada tahap sebelumnya.</div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                <div class="card-header bg-white py-3"><h6 class="font-weight-bold mb-0">Tahap 5 — Matriks normalisasi terbobot</h6></div>
                <div class="card-body">
                    <p class="text-muted mb-2">Setiap nilai normalisasi dikalikan dengan bobot desimal kriteria: <code>Yij = Rij × Wj</code>.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-center mb-0">
                            <thead class="thead-light"><tr><th>Alternatif</th>@foreach($hasilSAW['kriteria'] as $c)<th>{{ $c['kode'] }}<br><small>W={{ $formatAngka($c['bobot_desimal'], 4) }}</small></th>@endforeach</tr></thead>
                            <tbody>
                                @foreach($hasilSAW['alternatif'] as $pupuk)
                                    <tr>
                                        <th class="text-left">{{ $pupuk['kode'] }} - {{ $pupuk['nama_pupuk'] }}</th>
                                        @foreach($hasilSAW['kriteria'] as $c)
                                            <td>
                                                <small class="text-muted">{{ $formatAngka($hasilSAW['normalisasi'][$pupuk['id_pupuk']][$c['id_kriteria']], 4) }} × {{ $formatAngka($c['bobot_desimal'], 4) }}</small><br>
                                                <strong>{{ $formatAngka($hasilSAW['terbobot'][$pupuk['id_pupuk']][$c['id_kriteria']], 4) }}</strong>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                <div class="card-header bg-white py-3"><h6 class="font-weight-bold mb-0">Tahap 6 — Nilai preferensi dan perangkingan</h6></div>
                <div class="card-body">
                    <p class="text-muted">Nilai akhir setiap alternatif dihitung dengan menjumlahkan seluruh nilai normalisasi terbobot: <code>Vi = Σ (Rij × Wj)</code>. Nilai terbesar menjadi peringkat pertama.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="thead-light">
                                <tr><th>Ranking</th><th>Alternatif pupuk</th>@foreach($hasilSAW['kriteria'] as $c)<th class="text-right">{{ $c['kode'] }} × W</th>@endforeach<th>Penjumlahan nilai terbobot</th><th class="text-right">Nilai preferensi (Vi)</th></tr>
                            </thead>
                            <tbody>
                                @foreach($hasilSAW['ranking'] as $r)
                                    <tr class="{{ $r['peringkat'] === 1 ? 'table-success font-weight-bold' : '' }}">
                                        <td class="text-center">{{ $r['peringkat'] }}</td>
                                        <td>{{ $r['kode'] }} - {{ $r['nama_pupuk'] }}</td>
                                        @foreach($hasilSAW['kriteria'] as $c)
                                            <td class="text-right">{{ $formatAngka($hasilSAW['terbobot'][$r['id_pupuk']][$c['id_kriteria']], 4) }}</td>
                                        @endforeach
                                        <td><small>
                                            @foreach($hasilSAW['kriteria'] as $index => $c)
                                                @if($index > 0) + @endif{{ $formatAngka($hasilSAW['terbobot'][$r['id_pupuk']][$c['id_kriteria']], 4) }}
                                            @endforeach
                                        </small></td>
                                        <td class="text-right">{{ $formatAngka($r['nilai_preferensi'], 4) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(!empty($hasilSAW['ranking']))
                        @php $terbaik = $hasilSAW['ranking'][0]; @endphp
                        <div class="border rounded p-3 mt-4">
                            <h6 class="font-weight-bold text-dark mb-1"><i class="ti-cup mr-2 text-warning"></i>Rekomendasi peringkat pertama</h6>
                            <p class="mb-0">Berdasarkan data penilaian dan bobot yang tersimpan, alternatif dengan nilai SAW tertinggi adalah <strong>{{ $terbaik['kode'] }} - {{ $terbaik['nama_pupuk'] }}</strong> dengan nilai preferensi <strong>{{ $formatAngka($terbaik['nilai_preferensi'], 4) }}</strong>.</p>
                        </div>
                    @endif
                    <div class="alert alert-warning mt-3 mb-0 small">
                        <strong>Catatan:</strong> hasil rekomendasi bergantung pada nilai matriks, atribut benefit/cost, dan bobot kriteria yang tersimpan di sistem. Pastikan rating 1–5 dan bobot sudah ditetapkan berdasarkan aturan penelitian atau validasi pakar.
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var hasil = document.getElementById('hasil-perhitungan');
                if (hasil) {
                    hasil.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        </script>
    @endif
</div>
@endsection
