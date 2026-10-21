<!DOCTYPE html>
<html lang="id">
<head>
    <title>Cetak Laporan SAW - {{ $lahan->nama_lahan }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/css/bootstrap.min.css') }}">
    <style>
        body { background-color: #fff; color: #000; font-family: 'Times New Roman', Times, serif; font-size: 13px; }
        .kop-surat { border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        table { width: 100%; margin-bottom: 1.5rem; color: #000 !important; }
        .table-bordered th, .table-bordered td { border: 1px solid #000 !important; padding: 5px 8px; vertical-align: middle; }
        .thead-light th { background-color: #e9ecef !important; color: #000 !important; -webkit-print-color-adjust: exact; }
        .section-title { font-weight: bold; font-size: 14px; margin-top: 25px; margin-bottom: 10px; background-color: #f8f9fa; padding: 5px 10px; border-left: 4px solid #000; -webkit-print-color-adjust: exact; }
        .avoid-break { page-break-inside: avoid; }
        @media print { 
            @page { size: A4; margin: 1.5cm; } 
            .btn-print { display: none; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body class="p-3" onload="window.print()">
    @php
        $formatAngka = function ($nilai, $maksDesimal = 4) {
            $angka = number_format((float) $nilai, $maksDesimal, ',', '.');
            if (strpos($angka, ',') !== false) {
                $angka = rtrim(rtrim($angka, '0'), ',');
            }
            return $angka;
        };
    @endphp

    <button class="btn btn-primary btn-print mb-4 shadow-sm" onclick="window.print()">Cetak PDF / Print</button>

    <div class="kop-surat text-center">
        <h4 class="font-weight-bold">SISTEM PENDUKUNG KEPUTUSAN REKOMENDASI PUPUK</h4>
        <h5 class="mb-0">DETAIL PERHITUNGAN METODE SIMPLE ADDITIVE WEIGHTING (SAW)</h5>
    </div>

    <div class="mb-4">
        <table>
            <tr><td width="150"><b>Nama Pemilik</b></td><td>: {{ $lahan->pemilik->nama_pemilik }}</td></tr>
            <tr><td><b>Luas Lahan</b></td><td>: {{ $lahan->luas_lahan }} Ha</td></tr>
            <tr><td><b>Tanggal Cetak</b></td><td>: {{ date('d F Y') }}</td></tr>
        </table>
    </div>

    <!-- Tahap 1 -->
    <div class="avoid-break">
        <div class="section-title">Tahap 1 — Bobot & Atribut Kriteria</div>
        <table class="table table-bordered table-sm">
            <thead class="thead-light text-center">
                <tr><th>Kode</th><th>Kriteria</th><th>Atribut</th><th>Bobot (%)</th><th>Bobot Desimal</th></tr>
            </thead>
            <tbody>
                @foreach($hasilSAW['kriteria'] as $c)
                <tr>
                    <td class="text-center">{{ $c['kode'] }}</td>
                    <td>{{ $c['nama_kriteria'] }}</td>
                    <td class="text-center">{{ strtoupper($c['atribut']) }}</td>
                    <td class="text-center">{{ $formatAngka($c['bobot'], 2) }}%</td>
                    <td class="text-center">{{ $formatAngka($c['bobot_desimal'], 4) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Tahap 2 & 3 -->
    <div class="avoid-break">
        <div class="section-title">Tahap 2 & 3 — Matriks Awal & Nilai Pembanding (Max/Min)</div>
        <table class="table table-bordered table-sm text-center">
            <thead class="thead-light">
                <tr>
                    <th>Alternatif</th>
                    @foreach($hasilSAW['kriteria'] as $c)
                        <th>{{ $c['kode'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($hasilSAW['alternatif'] as $pupuk)
                <tr>
                    <th class="text-left">{{ $pupuk['kode'] }} - {{ $pupuk['nama_pupuk'] }}</th>
                    @foreach($hasilSAW['kriteria'] as $c)
                        <td>{{ $formatAngka($hasilSAW['matriks_awal'][$pupuk['id_pupuk']][$c['id_kriteria']], 4) }}</td>
                    @endforeach
                </tr>
                @endforeach
                <tr style="background-color: #f8f9fa;">
                    <th class="text-left">Nilai Pembanding (MAX/MIN)</th>
                    @foreach($hasilSAW['kriteria'] as $c)
                        <th class="font-weight-bold">{{ $formatAngka($hasilSAW['pembanding'][$c['id_kriteria']], 4) }}</th>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Tahap 4 -->
    <div class="avoid-break">
        <div class="section-title">Tahap 4 — Normalisasi Matriks (R)</div>
        <table class="table table-bordered table-sm text-center">
            <thead class="thead-light">
                <tr><th>Alternatif</th>@foreach($hasilSAW['kriteria'] as $c)<th>{{ $c['kode'] }}</th>@endforeach</tr>
            </thead>
            <tbody>
                @foreach($hasilSAW['alternatif'] as $pupuk)
                <tr>
                    <th class="text-left">{{ $pupuk['kode'] }}</th>
                    @foreach($hasilSAW['kriteria'] as $c)
                        <td>{{ $formatAngka($hasilSAW['normalisasi'][$pupuk['id_pupuk']][$c['id_kriteria']], 4) }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Tahap 5 -->
    <div class="avoid-break">
        <div class="section-title">Tahap 5 — Matriks Normalisasi Terbobot (Y)</div>
        <table class="table table-bordered table-sm text-center">
            <thead class="thead-light">
                <tr><th>Alternatif</th>@foreach($hasilSAW['kriteria'] as $c)<th>{{ $c['kode'] }} <br><small>W={{ $formatAngka($c['bobot_desimal'], 4) }}</small></th>@endforeach</tr>
            </thead>
            <tbody>
                @foreach($hasilSAW['alternatif'] as $pupuk)
                <tr>
                    <th class="text-left">{{ $pupuk['kode'] }}</th>
                    @foreach($hasilSAW['kriteria'] as $c)
                        <td>{{ $formatAngka($hasilSAW['terbobot'][$pupuk['id_pupuk']][$c['id_kriteria']], 4) }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Tahap 6 -->
    <div class="avoid-break">
        <div class="section-title">Tahap 6 — Nilai Preferensi & Perangkingan Akhir</div>
        <table class="table table-bordered table-sm">
            <thead class="thead-light text-center">
                <tr><th>Peringkat</th><th>Alternatif Pupuk</th><th>Nilai Preferensi Akhir (Vi)</th></tr>
            </thead>
            <tbody>
                @foreach($hasilSAW['ranking'] as $r)
                <tr>
                    <td class="text-center"><b>{{ $r['peringkat'] }}</b></td>
                    <td>{{ $r['kode'] }} - {{ $r['nama_pupuk'] }}</td>
                    <td class="text-center font-weight-bold">{{ $formatAngka($r['nilai_preferensi'], 4) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4 mb-5" style="border: 1px solid #000; padding: 15px; background-color: #f8f9fa;">
        <h6 class="font-weight-bold">KESIMPULAN:</h6>
        <p class="mb-0">Berdasarkan perhitungan SAW di atas, pupuk yang paling direkomendasikan untuk lahan <b>{{ $lahan->nama_lahan }}</b> adalah <b>{{ $hasilSAW['ranking'][0]['nama_pupuk'] }}</b> dengan nilai preferensi tertinggi yaitu <b>{{ $formatAngka($hasilSAW['ranking'][0]['nilai_preferensi'], 4) }}</b>.</p>
    </div>

    <div class="mt-5" style="width: 250px; float: right; text-align: center; page-break-inside: avoid;">
        <p>Mengetahui, <br><b>Pimpinan</b></p>
        <br><br><br>
        <p>( ....................................... )</p>
    </div>
</body>
</html>