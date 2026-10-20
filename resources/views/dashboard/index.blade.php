@extends('tampilan.main')

@section('content')
<div class="container-fluid py-4">

    <!-- BANNER SELAMAT DATANG -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: linear-gradient(135deg, #7367f0 0%, #9e95f5 100%); color: white;">
        <div class="card-body p-4 d-flex align-items-center">
            <div class="mr-4 d-none d-md-block">
                <div class="bg-white text-primary rounded-circle d-flex justify-content-center align-items-center shadow-sm" style="width: 70px; height: 70px;">
                    <i class="ti-map-alt" style="font-size: 30px;"></i>
                </div>
            </div>
            <div>
                <h4 class="font-weight-bold mb-1 text-white">Selamat Datang di Sistem SPK & GIS</h4>
                <p class="mb-0" style="font-size: 15px; opacity: 0.9;">
                    Sistem Pendukung Keputusan Penentuan Pupuk Terbaik (Metode SAW) yang terintegrasi dengan Pemetaan Lahan Geografis.
                </p>
            </div>
        </div>
    </div>

    <!-- KARTU STATISTIK (SUMMARY CARDS) -->
    <div class="row mb-3">
        <!-- Card Total Lahan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="rounded-circle d-flex justify-content-center align-items-center mr-3 shadow-sm" style="width: 55px; height: 55px; background-color: #e5f8ed; color: #28c76f;">
                        <i class="ti-map" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <h3 class="font-weight-bold mb-0 text-dark">{{ $totalLahan }}</h3>
                        <small class="text-muted font-weight-bold">Total Lahan (GIS)</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Total Kriteria -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="rounded-circle d-flex justify-content-center align-items-center mr-3 shadow-sm" style="width: 55px; height: 55px; background-color: #e1f9fc; color: #00cfe8;">
                        <i class="ti-view-list-alt" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <h3 class="font-weight-bold mb-0 text-dark">{{ $totalKriteria }}</h3>
                        <small class="text-muted font-weight-bold">Data Kriteria</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Total Alternatif -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="rounded-circle d-flex justify-content-center align-items-center mr-3 shadow-sm" style="width: 55px; height: 55px; background-color: #f3f0ff; color: #7367f0;">
                        <i class="ti-layers" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <h3 class="font-weight-bold mb-0 text-dark">{{ $totalPupuk }}</h3>
                        <small class="text-muted font-weight-bold">Alternatif Pupuk</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Lahan Dinilai -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="rounded-circle d-flex justify-content-center align-items-center mr-3 shadow-sm" style="width: 55px; height: 55px; background-color: #fff0e1; color: #ff9f43;">
                        <i class="ti-stats-up" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <h3 class="font-weight-bold mb-0 text-dark">{{ $lahanDinilai }}</h3>
                        <small class="text-muted font-weight-bold">Lahan Dinilai (SAW)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PANDUAN PENGGUNAAN SISTEM -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-header bg-white border-0 py-4" style="border-radius: 12px 12px 0 0;">
            <h6 class="mb-0 font-weight-bold text-dark"><i class="ti-info-alt text-primary mr-2"></i>Alur Penggunaan Aplikasi</h6>
        </div>
        <div class="card-body pt-0 pb-4">
            <div class="row text-center">
                <div class="col-md-4 mb-3">
                    <div class="p-3 border rounded" style="border-color: #f1f2f6 !important;">
                        <div class="rounded-circle bg-light text-primary mx-auto mb-3 d-flex justify-content-center align-items-center font-weight-bold" style="width: 40px; height: 40px;">1</div>
                        <h6 class="font-weight-bold">Kelola Master Data</h6>
                        <p class="text-muted small mb-0">Input Data Kriteria (Bobot & Atribut) dan Data Alternatif (Daftar Pupuk) pada menu Master Data.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="p-3 border rounded" style="border-color: #f1f2f6 !important;">
                        <div class="rounded-circle bg-light text-primary mx-auto mb-3 d-flex justify-content-center align-items-center font-weight-bold" style="width: 40px; height: 40px;">2</div>
                        <h6 class="font-weight-bold">Pemetaan Lahan GIS</h6>
                        <p class="text-muted small mb-0">Buka peta, gambar poligon lokasi lahan menggunakan *polygon tool*, lalu simpan data area lahan.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="p-3 border rounded" style="border-color: #f1f2f6 !important;">
                        <div class="rounded-circle bg-light text-primary mx-auto mb-3 d-flex justify-content-center align-items-center font-weight-bold" style="width: 40px; height: 40px;">3</div>
                        <h6 class="font-weight-bold">Penilaian & Hitung SAW</h6>
                        <p class="text-muted small mb-0">Klik tombol "Nilai" pada lahan, isi matriks skalanya, dan sistem akan merangking pupuk terbaik.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection