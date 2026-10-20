@extends('tampilan.main')

@section('content')
<div class="container-fluid py-4">
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- KOTAK PUTIH (CARD UTAMA) -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; background-color: #ffffff;">
        
        <!-- HEADER KOTAK -->
        <div class="card-header bg-white border-0 py-4" style="border-radius: 12px 12px 0 0;">
            <div class="row align-items-center">
                <!-- Judul Kiri -->
                <div class="col-md-6">
                    <h5 class="mb-0 text-dark font-weight-bold d-flex align-items-center">
                        <i class="ti-layers mr-2 text-primary" style="font-size: 20px;"></i>
                        Data Alternatif (Pupuk)
                    </h5>
                </div>
                
                <!-- Pencarian & Tombol Kanan -->
                <div class="col-md-6 d-flex justify-content-end align-items-center">
                    <div class="input-group mb-0 mr-3" style="width: 250px;">
                        <input type="text" class="form-control" placeholder="Cari nama pupuk..." style="height: 38px;">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" style="height: 38px; padding: 5px 12px;">
                                <i class="ti-search"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Tombol Tambah Data -->
                    <button class="btn btn-primary font-weight-bold" data-toggle="modal" data-target="#modalTambahPupuk" style="height: 38px; border-radius: 6px;">
                        + Tambah Data
                    </button>
                </div>
            </div>
        </div>

        <!-- ISI TABEL -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0" style="border-collapse: collapse;">
                    <thead style="background-color: #ffffff; border-bottom: 2px solid #f1f2f6;">
                        <tr>
                            <th class="text-dark font-weight-bold border-0 px-4 py-3" style="font-size: 14px;">No</th>
                            <th class="text-dark font-weight-bold border-0 py-3" style="font-size: 14px;">Kode</th>
                            <th class="text-dark font-weight-bold border-0 py-3" style="font-size: 14px;">Nama Pupuk</th>
                            <th class="text-dark font-weight-bold border-0 py-3" style="font-size: 14px;">Harga Estimasi</th>
                            <th class="text-dark font-weight-bold border-0 py-3 text-center" style="font-size: 14px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alternatifs as $index => $a)
                            <tr style="border-bottom: 1px solid #f1f2f6;">
                                <td class="border-0 px-4 py-3 text-muted">{{ $index + 1 }}</td>
                                <td class="border-0 py-3 font-weight-bold text-dark">{{ $a->kode }}</td>
                                <td class="border-0 py-3">
                                    <!-- Label nama dengan efek background ungu tipis seperti digambar -->
                                    <span style="background-color: #f3f0ff; color: #7367f0; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                        {{ $a->nama_pupuk }}
                                    </span>
                                </td>
                                <td class="border-0 py-3 text-muted">Rp {{ number_format($a->harga, 0, ',', '.') }}</td>
                                <td class="border-0 py-3 text-center">
                                    <!-- Tombol Edit (Kuning) -->
                                    <button class="btn btn-warning rounded-circle shadow-sm mr-1 text-white" data-toggle="modal" data-target="#modalEditPupuk{{ $a->id_pupuk }}" style="width: 35px; height: 35px; padding: 0; border: none;" title="Edit">
                                        <i class="ti-pencil-alt" style="font-size: 13px;"></i>
                                    </button>

                                    <!-- Tombol Hapus (Cyan) -->
                                    <form action="{{ route('alternatif.destroy', $a->id_pupuk) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-info rounded-circle shadow-sm" style="width: 35px; height: 35px; padding: 0; background-color: #00CFE8; border: none;" onclick="return confirm('Hapus pupuk ini?')" title="Hapus">
                                            <i class="ti-trash" style="font-size: 13px;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5 border-0">
                                    <i class="ti-info-alt mb-2" style="font-size: 24px;"></i><br>
                                    Belum ada data alternatif.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination / Jarak bawah tabel -->
            <div class="px-4 py-3 bg-white" style="border-radius: 0 0 12px 12px;"></div>
        </div>
    </div>

</div>

<!-- MODAL TAMBAH DATA -->
<div class="modal fade" id="modalTambahPupuk" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none;">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title font-weight-bold" id="modalLabel">Tambah Pupuk Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('alternatif.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4">
                    <div class="form-group mb-3">
                        <label class="form-label text-muted">Kode (Contoh: A1)</label>
                        <input type="text" name="kode" class="form-control" style="border-radius: 6px;" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label text-muted">Nama Pupuk</label>
                        <input type="text" name="nama_pupuk" class="form-control" style="border-radius: 6px;" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label text-muted">Harga Estimasi (Rp)</label>
                        <input type="number" name="harga" class="form-control" style="border-radius: 6px;" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 6px;">Batal</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 6px;">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT DATA PUPUK -->
@foreach($alternatifs as $a)
<div class="modal fade" id="modalEditPupuk{{ $a->id_pupuk }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none;">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title font-weight-bold">Edit Data Pupuk</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('alternatif.update', $a->id_pupuk) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body px-4">
                    <div class="form-group mb-3">
                        <label class="form-label text-muted">Kode</label>
                        <input type="text" name="kode" class="form-control" style="border-radius: 6px;" value="{{ $a->kode }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label text-muted">Nama Pupuk</label>
                        <input type="text" name="nama_pupuk" class="form-control" style="border-radius: 6px;" value="{{ $a->nama_pupuk }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label text-muted">Harga Estimasi (Rp)</label>
                        <input type="number" name="harga" class="form-control" style="border-radius: 6px;" value="{{ $a->harga }}" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 6px;">Batal</button>
                    <button type="submit" class="btn btn-warning text-white" style="border-radius: 6px;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection