@extends('tampilan.main')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
    <style>
        .leaflet-popup-content-wrapper { border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .leaflet-popup-content { margin: 15px; font-family: 'Open Sans', sans-serif; }
    </style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="font-weight-bold text-dark"><i class="ti-map-alt mr-2 text-primary"></i> Dashboard GIS Pemetaan Lahan</h4>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <div class="row">
        <!-- Kolom Peta -->
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 py-3" style="border-radius: 12px 12px 0 0;">
                    <h6 class="mb-0 font-weight-bold text-dark">Peta Interaktif (Mode Satelit)</h6>
                </div>
                <div class="card-body p-0">
                    <div id="map" style="height: 600px; width: 100%; z-index: 1; border-radius: 0 0 12px 12px;"></div>
                </div>
            </div>
        </div>

        <!-- Kolom Sidebar Lahan -->
        <div class="col-md-4">
            
            <!-- HANYA ADMIN YANG BISA MELIHAT FORM SIMPAN LAHAN -->
            @if(Auth::user()->role == 'admin')
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 py-3" style="border-radius: 12px 12px 0 0; border-bottom: 2px solid #f1f2f6 !important;">
                    <h6 class="mb-0 font-weight-bold text-dark">Simpan Lahan Tergambar</h6>
                </div>
                <div class="card-body">
                    <form id="formLahan">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label text-muted">Pemilik Lahan</label>
                            <select id="id_pemilik" class="form-control" style="border-radius: 6px;" required>
                                <option value="">-- Pilih Pemilik --</option>
                                @foreach($pemiliks as $pemilik)
                                    <option value="{{ $pemilik->id_pemilik }}">{{ $pemilik->nama_pemilik }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label class="form-label text-muted">Luas Lahan (Ha)</label>
                            <input type="number" step="0.01" id="luas_lahan" class="form-control" style="border-radius: 6px;" required>
                        </div>
                        <input type="hidden" id="batas_polygon">
                        <button type="submit" class="btn btn-success w-100 font-weight-bold" id="btnSimpan" style="border-radius: 6px;" disabled>
                            <i class="ti-save mr-1"></i> Simpan Polygon
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Daftar Lahan -->
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 py-3" style="border-radius: 12px 12px 0 0; border-bottom: 2px solid #f1f2f6 !important;">
                    <h6 class="mb-0 font-weight-bold text-dark">Daftar Lahan Terdaftar</h6>
                </div>
                <div class="card-body p-0" style="max-height: {{ Auth::user()->role == 'admin' ? '250px' : '550px' }}; overflow-y: auto;">
                    <ul class="list-group list-group-flush">
                        @forelse($lahans as $lahan)
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0" style="border-bottom: 1px solid #f1f2f6 !important; padding: 15px 20px;">
                                <div>
                                    <small class="text-muted d-block mb-1"><i class="ti-user text-success"></i> {{ $lahan->pemilik->nama_pemilik ?? 'Tanpa Pemilik' }}</small>
                                    <small class="text-muted"><i class="ti-ruler-alt"></i> {{ $lahan->luas_lahan }} Ha</small>
                                </div>
                                
                                <!-- HANYA ADMIN YANG BISA MELIHAT TOMBOL NILAI & HAPUS -->
                                @if(Auth::user()->role == 'admin')
                                <div class="d-flex">
                                    <a href="{{ route('penilaian.form', $lahan->id_lahan) }}" class="btn btn-warning text-dark shadow-sm mr-2 d-flex justify-content-center align-items-center" style="border-radius: 6px; padding: 5px 10px; font-size: 12px;" title="Beri Penilaian & Hitung SAW">
                                        <i class="ti-pencil-alt mr-1"></i> Nilai
                                    </a>
                                    <form action="{{ route('pemetaan.destroy', $lahan->id_lahan) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-info rounded-circle shadow-sm" style="width: 30px; height: 30px; padding: 0; background-color: #00CFE8; border: none;" onclick="return confirm('Hapus lahan?')" title="Hapus Lahan">
                                            <i class="ti-trash" style="font-size: 12px;"></i>
                                        </button>
                                    </form>
                                </div>
                                @endif

                            </li>
                        @empty
                            <li class="list-group-item border-0 text-center py-4 text-muted">
                                Belum ada lahan disimpan.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>
    <script>
        var map = L.map('map').setView([-0.900205, 101.383567], 17);

        L.tileLayer('http://{s}.google.com/vt?lyrs=s,h&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        }).addTo(map);

        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        @foreach($lahans as $lahan)
            @if($lahan->batas_polygon)
                var coords = {!! $lahan->batas_polygon !!};
                var latLngs = coords[0].map(coord => [coord[1], coord[0]]);
                
                var pColor = getRandomColor();
                var savedPolygon = L.polygon(latLngs, {
                    color: pColor, fillColor: pColor, fillOpacity: 0.6
                }).addTo(map);
                
                @php $rekomendasi = $lahan->hasilSAW->first(); @endphp
                
                var popupContent = "<div style='min-width: 160px;'>";
                popupContent += "<small class='text-muted d-block mb-1'><i class='ti-user'></i> {{ $lahan->pemilik->nama_pemilik ?? 'Tanpa Pemilik' }}</small>";
                popupContent += "<small class='text-muted'>Luas: {{ $lahan->luas_lahan }} Ha</small><hr class='my-2'>";
                
                @if($rekomendasi)
                    popupContent += "<small class='d-block text-success font-weight-bold mb-1'>Rekomendasi Pupuk:</small>";
                    popupContent += "<span style='background-color: #f3f0ff; color: #7367f0; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;'>{{ $rekomendasi->pupuk->nama_pupuk }}</span><br>";
                    popupContent += "<small class='text-muted mt-2 d-block'>Skor Preferensi: {{ rtrim(rtrim(number_format($rekomendasi->nilai_preferensi, 3, ',', ''), '0'), ',') }}</small>";
                @else
                    popupContent += "<small class='text-danger font-weight-bold'>Belum dihitung SAW</small>";
                @endif
                popupContent += "</div>";

                savedPolygon.bindPopup(popupContent);
            @endif
        @endforeach

        @if(Auth::user()->role == 'admin')
            map.pm.addControls({
                position: 'topleft',
                drawCircle: false, drawMarker: false, drawPolyline: false,
                drawRectangle: false, drawCircleMarker: false,
                drawPolygon: true, cutPolygon: false,
                editMode: true, dragMode: false, removalMode: true
            });

            let drawnPolygonLayer = null;

            function updatePolygonInput(layer) {
                var data = layer.toGeoJSON();
                document.getElementById('batas_polygon').value = JSON.stringify(data.geometry.coordinates);
                document.getElementById('btnSimpan').removeAttribute('disabled');
            }

            map.on('pm:create', function(e) {
                if (drawnPolygonLayer) {
                    map.removeLayer(drawnPolygonLayer);
                }
                drawnPolygonLayer = e.layer;
                updatePolygonInput(drawnPolygonLayer);
                
                drawnPolygonLayer.on('pm:edit', function(e) {
                    updatePolygonInput(e.layer);
                });
            });

            map.on('pm:remove', function(e) {
                if(e.layer === drawnPolygonLayer) {
                    document.getElementById('batas_polygon').value = '';
                    document.getElementById('btnSimpan').setAttribute('disabled', 'true');
                }
            });

            document.getElementById('formLahan').addEventListener('submit', function(e) {
                e.preventDefault();
                let btn = document.getElementById('btnSimpan');
                let originalText = btn.innerHTML;
                btn.innerHTML = 'Menyimpan...';
                btn.disabled = true;

                // Modifikasi Data yang dikirim: Tambahkan id_pemilik
                let formData = {
                    _token: '{{ csrf_token() }}',
                    id_pemilik: document.getElementById('id_pemilik').value,
                    luas_lahan: document.getElementById('luas_lahan').value,
                    batas_polygon: document.getElementById('batas_polygon').value
                };

                fetch('{{ route("pemetaan.store") }}', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
                    body: JSON.stringify(formData)
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message || "Terjadi kesalahan.");
                    return data;
                })
                .then(data => {
                    if(data.success) {
                        alert("Lahan berhasil disimpan!");
                        location.reload();
                    }
                })
                .catch(error => {
                    alert("GAGAL MENYIMPAN:\n" + error.message);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            });
        @endif
    </script>
@endpush