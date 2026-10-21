<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIG & SPK Rekomendasi Pupuk</title>
    
    <!-- Bootstrap CSS & FontAwesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        html { scroll-behavior: smooth; }
        .nav-bar { background-color: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .carousel-item img { object-fit: cover; }
        .leaflet-popup-content { font-family: sans-serif; }
    </style>
</head>
<body>

    <!-- Navbar Start -->
    <div class="container-fluid position-relative nav-bar p-0 sticky-top">
        <div class="position-relative" style="z-index: 9;">
            <nav class="navbar navbar-expand-lg bg-white navbar-light py-3 py-lg-0 pl-3 pl-lg-5">
                <a href="#" class="navbar-brand">
                    <h1 class="m-0 text-secondary">
                        <span class="text-success"><i class="fa fa-leaf mr-2"></i>SPK Pupuk</span>
                    </h1>
                </a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between px-3" id="navbarCollapse">
                    <div class="navbar-nav ml-auto py-0">
                        <a href="#home" class="nav-item nav-link font-weight-bold text-dark">Home</a>
                        <a href="#tentang" class="nav-item nav-link font-weight-bold text-dark">Tentang</a>
                        <a href="#infopupuk" class="nav-item nav-link font-weight-bold text-dark">Informasi Pupuk</a>
                        <a href="#potensi" class="nav-item nav-link font-weight-bold text-dark">Pemetaan Lahan</a>
                        <a href="{{ route('login') }}" class="py-4 px-lg-5 text-success" title="Login Sistem Admin/Pimpinan"><i class="fa fa-user fa-lg"></i></a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

    <!-- Carousel Start -->
    <div id="home" class="container-fluid p-0">
        <div id="header-carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <!-- Gunakan URL gambar background pertanian -->
                    <img style="height: 600px; width: 100%;" src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Image">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center" style="background: rgba(0,0,0,0.5); top: 0; bottom: 0; left: 0; right: 0;">
                        <div class="p-3" style="max-width: 900px;">
                            <h4 class="text-white text-uppercase mb-md-3">Sistem Pendukung Keputusan</h4>
                            <h1 class="display-4 text-white mb-md-4 font-weight-bold text-uppercase">Rekomendasi Pupuk &<br> Pemetaan Lahan</h1>
                            <a href="#potensi" class="btn btn-success py-md-3 px-md-5 mt-2 rounded-pill font-weight-bold shadow">Lihat Peta Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->

    <!-- Tentang Start -->
    <div id="tentang" class="container-fluid pt-5 pb-3 bg-light">
        <div class="container">
            <h6 class="text-success text-uppercase text-center font-weight-medium mb-3">Tentang</h6>
            <h1 class="display-4 text-center mb-5">Sistem Pemetaan</h1>
            <div class="row align-items-center">
                <div class="col-lg-8 m-0 my-lg-2 pt-0 pr-lg-5">
                    <p style="font-size: 18px; line-height: 1.8;">
                        Website ini merupakan Sistem Informasi Geografis (SIG) yang terintegrasi dengan Sistem Pendukung Keputusan (SPK) menggunakan metode <b>Simple Additive Weighting (SAW)</b>. Masyarakat dapat memantau secara langsung lahan pertanian yang terdata beserta rekomendasi pupuk terbaik yang dihasilkan oleh sistem.
                    </p>
                </div>
                <div class="col-lg-4 m-0 my-lg-2 pt-0 pr-lg-5 border-left border-success">
                    <h5 class="font-weight-bold mb-4">Statistik Pemetaan</h5>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h6 class="text-muted">Total Lahan Terdata:</h6>
                            <h2 class="text-success font-weight-bold">{{ $total_lahan }} <small class="text-dark" style="font-size: 16px;">Area</small></h2>
                        </div>
                        <div class="col-12 mb-3">
                            <h6 class="text-muted">Total Luas Lahan:</h6>
                            <h2 class="text-success font-weight-bold">{{ number_format($sum_luas_lahan, 2) }} <small class="text-dark" style="font-size: 16px;">Hektar (Ha)</small></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tentang End -->

    <!-- Info Pupuk Start (Menggantikan Info Tanah) -->
    <div id="infopupuk" class="container-fluid pt-5 pb-3">
        <div class="container">
            <h6 class="text-success text-uppercase text-center font-weight-medium mb-3">Alternatif</h6>
            <h1 class="display-4 text-center mb-5">Informasi Pupuk</h1>
            <div class="row justify-content-center">
                @foreach ($pupuks as $pupuk)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="bg-light text-center mb-2 pt-4 pb-4 rounded shadow-sm border-bottom border-success" style="border-width: 4px !important;">
                        <div class="d-inline-flex flex-column align-items-center justify-content-center rounded-circle shadow-sm mt-2 mb-3 bg-white" style="width: 80px; height: 80px; border: 3px solid #28a745;">
                            <h4 class="text-success m-0">{{ $pupuk->kode }}</h4>
                        </div>
                        <h5 class="font-weight-bold text-dark px-2">{{ $pupuk->nama_pupuk }}</h5>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Info Pupuk End -->

    <!-- Peta Lahan Start -->
    <div id="potensi" class="container-fluid pt-5 pb-5 bg-light">
        <div class="container-fluid px-lg-5">
            <h6 class="text-success text-uppercase text-center font-weight-medium mb-3">Pemetaan</h6>
            <h1 class="display-4 text-center mb-5">Peta Interaktif Lahan</h1>
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <div class="shadow-sm rounded overflow-hidden border">
                        <div id="map" style="height: 650px; width: 100%; z-index: 1;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Peta Lahan End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white py-4 px-sm-3 px-md-5">
        <p class="m-0 text-center text-white">
            &copy; 2026 <a class="text-success font-weight-bold" href="#">SPK Rekomendasi Pupuk & Pemetaan Lahan</a>. All Rights Reserved.
        </p>
    </div>
    <!-- Footer End -->

    <!-- Leaflet JS & Bootstrap Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        // Inisialisasi Peta
        var map = L.map('map').setView([-0.900205, 101.383567], 15);
        L.tileLayer('http://{s}.google.com/vt?lyrs=s,h&x={x}&y={y}&z={z}',{
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3']
        }).addTo(map);

        // Fungsi Warna Acak
        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        // Render Poligon dari Database SPK
        @foreach($lahans as $lahan)
            @if($lahan->batas_polygon)
                var coords = {!! $lahan->batas_polygon !!};
                
                // Konversi dari format GeoJSON (Lng, Lat) ke Leaflet (Lat, Lng)
                var latLngs = coords[0].map(coord => [coord[1], coord[0]]);
                
                var polygon = L.polygon(latLngs, {
                    color: getRandomColor(),
                    fillColor: getRandomColor(),
                    fillOpacity: 0.6,
                    weight: 2
                }).addTo(map);

                @php $rekomendasi = $lahan->hasilSAW->first(); @endphp

                // Konten Popup
                var popupContent = "<div style='min-width: 220px;'>";
                popupContent += "<h5 class='font-weight-bold text-dark border-bottom pb-2 mb-2'>{{ $lahan->pemilik->nama_pemilik }}</h5>";
                popupContent += "<p class='mb-2'><b>Luas Area:</b> {{ $lahan->luas_lahan }} Ha</p>";
                
                @if($rekomendasi)
                    popupContent += "<div class='bg-success text-white p-2 rounded text-center'>";
                    popupContent += "<small class='d-block mb-1'>Rekomendasi Pupuk:</small>";
                    popupContent += "<h6 class='m-0 font-weight-bold'>{{ $rekomendasi->pupuk->nama_pupuk }}</h6>";
                    popupContent += "</div>";
                @else
                    popupContent += "<div class='bg-warning text-dark p-2 rounded text-center font-weight-bold'>Belum Dianalisis</div>";
                @endif
                popupContent += "</div>";

                polygon.bindPopup(popupContent);
            @endif
        @endforeach
    </script>
</body>
</html>