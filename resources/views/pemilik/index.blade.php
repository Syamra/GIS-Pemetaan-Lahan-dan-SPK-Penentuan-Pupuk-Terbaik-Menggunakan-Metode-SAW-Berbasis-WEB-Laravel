@extends('tampilan.main')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <!-- Header Kartu (Kotak Putih Atas) -->
        <div class="card-header bg-white py-4" style="border-radius: 12px 12px 0 0;">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <!-- Judul -->
                <h5 class="mb-0 font-weight-bold text-dark d-flex align-items-center">
                    <i class="ti-user text-primary mr-2" style="font-size: 20px;"></i> Master Data Pemilik Lahan
                </h5>
                
                <!-- Pencarian & Tombol Tambah di dalam Kotak Putih -->
                <div class="d-flex align-items-center mt-2 mt-md-0">
                    <!-- Kotak Pencarian Rapi -->
                    <div class="position-relative mr-3" style="width: 250px;">
                        <input type="text" id="cariPemilik" class="form-control" placeholder="Cari pemilik..." style="border-radius: 6px; padding-left: 35px; font-size: 13px; height: 38px;">
                        <i class="ti-search text-muted position-absolute" style="top: 12px; left: 12px; font-size: 13px;"></i>
                    </div>
                    <button type="button" class="btn btn-primary shadow-sm font-weight-bold px-3 py-2" data-toggle="modal" data-target="#modalTambah" style="border-radius: 6px; font-size: 13px;">
                        + Tambah Pemilik
                    </button>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
            </div>
        @endif

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0" id="tabelPemilik">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="border-0 px-4 py-3 text-muted font-weight-bold" style="width: 8%;">No</th>
                            <th class="border-0 py-3 text-muted font-weight-bold">Nama Pemilik</th>
                            <th class="border-0 py-3 text-muted font-weight-bold">Alamat</th>
                            <th class="border-0 py-3 text-muted font-weight-bold">No. HP / Kontak</th>
                            <th class="border-0 py-3 text-muted font-weight-bold text-center" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemiliks as $index => $p)
                        <tr style="border-bottom: 1px solid #f1f2f6;">
                            <td class="px-4 py-3 align-middle">{{ $index + 1 }}</td>
                            <td class="py-3 align-middle font-weight-bold text-dark">{{ $p->nama_pemilik }}</td>
                            <td class="py-3 align-middle text-muted">{{ $p->alamat }}</td>
                            <td class="py-3 align-middle text-muted">{{ $p->no_hp }}</td>
                            <td class="py-3 align-middle text-center">
                                <!-- Tombol Edit Bulat Kuning -->
                                <button type="button" class="btn btn-warning rounded-circle shadow-sm mr-1 text-white" data-toggle="modal" data-target="#modalEdit{{ $p->id_pemilik }}" style="width: 32px; height: 32px; padding: 0; border: none;" title="Edit">
                                    <i class="ti-pencil" style="font-size: 12px;"></i>
                                </button>
                                <!-- Tombol Hapus Bulat Biru Muda -->
                                <form action="{{ route('pemilik.destroy', $p->id_pemilik) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn rounded-circle shadow-sm text-white" style="width: 32px; height: 32px; padding: 0; background-color: #00CFE8; border: none;" onclick="return confirm('Hapus data pemilik ini?')" title="Hapus">
                                        <i class="ti-trash" style="font-size: 12px;"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="modalEdit{{ $p->id_pemilik }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="{{ route('pemilik.update', $p->id_pemilik) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-content border-0 shadow" style="border-radius: 12px;">
                                        <div class="modal-header bg-light">
                                            <h5 class="modal-title font-weight-bold">Edit Pemilik Lahan</h5>
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                        </div>
                                        <div class="modal-body text-left">
                                            <div class="form-group mb-3">
                                                <label class="text-muted">Nama Pemilik</label>
                                                <input type="text" name="nama_pemilik" class="form-control" value="{{ $p->nama_pemilik }}" required style="border-radius: 6px;">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="text-muted">Alamat</label>
                                                <input type="text" name="alamat" class="form-control" value="{{ $p->alamat }}" required style="border-radius: 6px;">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="text-muted">No. HP / Kontak</label>
                                                <input type="text" name="no_hp" class="form-control" value="{{ $p->no_hp }}" required style="border-radius: 6px;">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 6px;">Batal</button>
                                            <button type="submit" class="btn btn-primary" style="border-radius: 6px;">Simpan Perubahan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada data pemilik lahan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('pemilik.store') }}" method="POST">
            @csrf
            <div class="modal-content border-0 shadow" style="border-radius: 12px;">
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold">Tambah Pemilik Lahan Baru</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body text-left">
                    <div class="form-group mb-3">
                        <label class="text-muted">Nama Pemilik</label>
                        <input type="text" name="nama_pemilik" class="form-control" placeholder="Masukkan nama lengkap..." required style="border-radius: 6px;">
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-muted">Alamat</label>
                        <input type="text" name="alamat" class="form-control" placeholder="Masukkan alamat..." required style="border-radius: 6px;">
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-muted">No. HP / Kontak</label>
                        <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 081234567890" required style="border-radius: 6px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 6px;">Batal</button>
                    <button type="submit" class="btn btn-success" style="border-radius: 6px;">Simpan Data</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('cariPemilik').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#tabelPemilik tbody tr');
        
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>
@endpush
@endsection