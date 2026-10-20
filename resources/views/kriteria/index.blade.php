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

    <div class="card border-0 shadow-sm" style="border-radius: 12px; background-color: #ffffff;">
        <div class="card-header bg-white border-0 py-4" style="border-radius: 12px 12px 0 0;">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0 text-dark font-weight-bold d-flex align-items-center">
                        <i class="ti-view-list-alt mr-2 text-primary" style="font-size: 20px;"></i>
                        Data Kriteria SAW
                    </h5>
                </div>
                <div class="col-md-6 d-flex justify-content-end align-items-center">
                    <div class="input-group mb-0 mr-3" style="width: 250px;">
                        <input type="text" class="form-control" placeholder="Cari kriteria..." style="height: 38px;">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" style="height: 38px; padding: 5px 12px;">
                                <i class="ti-search"></i>
                            </button>
                        </div>
                    </div>
                    <button class="btn btn-primary font-weight-bold" data-toggle="modal" data-target="#modalTambahKriteria" style="height: 38px; border-radius: 6px;">
                        + Tambah Kriteria
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0" style="border-collapse: collapse;">
                    <thead style="background-color: #ffffff; border-bottom: 2px solid #f1f2f6;">
                        <tr>
                            <th class="text-dark font-weight-bold border-0 px-4 py-3">Kode</th>
                            <th class="text-dark font-weight-bold border-0 py-3">Nama Kriteria</th>
                            <th class="text-dark font-weight-bold border-0 py-3">Atribut</th>
                            <th class="text-dark font-weight-bold border-0 py-3 text-center">Bobot (%)</th>
                            <th class="text-dark font-weight-bold border-0 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kriterias as $k)
                            <tr style="border-bottom: 1px solid #f1f2f6;">
                                <td class="border-0 px-4 py-3 font-weight-bold text-dark">{{ $k->kode }}</td>
                                <td class="border-0 py-3">
                                    <span style="background-color: #f3f0ff; color: #7367f0; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                        {{ $k->nama_kriteria }}
                                    </span>
                                </td>
                                <td class="border-0 py-3">
                                    <span class="badge badge-pill {{ $k->atribut == 'benefit' ? 'badge-success' : 'badge-danger' }}" style="padding: 5px 10px;">
                                        {{ ucfirst($k->atribut) }}
                                    </span>
                                </td>
                                <td class="border-0 py-3 text-center font-weight-bold">{{ $k->bobot }}</td>
                                <td class="border-0 py-3 text-center">
                                    <!-- Tombol Edit (Kuning) -->
                                    <button class="btn btn-warning rounded-circle shadow-sm mr-1 text-white" data-toggle="modal" data-target="#modalEditKriteria{{ $k->id_kriteria }}" style="width: 35px; height: 35px; padding: 0; border: none;" title="Edit">
                                        <i class="ti-pencil-alt" style="font-size: 13px;"></i>
                                    </button>

                                    <!-- Tombol Hapus (Cyan) -->
                                    <form action="{{ route('kriteria.destroy', $k->id_kriteria) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-info rounded-circle shadow-sm" style="width: 35px; height: 35px; padding: 0; background-color: #00CFE8; border: none;" onclick="return confirm('Hapus kriteria ini?')" title="Hapus">
                                            <i class="ti-trash" style="font-size: 13px;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5 border-0">
                                    <i class="ti-info-alt mb-2" style="font-size: 24px;"></i><br>Belum ada data kriteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 bg-white" style="border-radius: 0 0 12px 12px;"></div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH DATA -->
<div class="modal fade" id="modalTambahKriteria" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none;">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title font-weight-bold">Tambah Kriteria Baru</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('kriteria.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4">
                    <div class="form-group mb-3">
                        <label class="form-label text-muted">Kode (Contoh: C1)</label>
                        <input type="text" name="kode" class="form-control" style="border-radius: 6px;" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label text-muted">Nama Kriteria</label>
                        <input type="text" name="nama_kriteria" class="form-control" style="border-radius: 6px;" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label text-muted">Atribut</label>
                        <select name="atribut" class="form-control" style="border-radius: 6px;" required>
                            <option value="benefit">Benefit (Makin besar makin baik)</option>
                            <option value="cost">Cost (Makin kecil makin baik)</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label text-muted">Bobot (%)</label>
                        <input type="number" step="0.01" name="bobot" class="form-control" style="border-radius: 6px;" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 6px;">Batal</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 6px;">Simpan Kriteria</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT DATA KRITERIA -->
@foreach($kriterias as $k)
<div class="modal fade" id="modalEditKriteria{{ $k->id_kriteria }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none;">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title font-weight-bold">Edit Kriteria</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('kriteria.update', $k->id_kriteria) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body px-4">
                    <div class="form-group mb-3">
                        <label class="form-label text-muted">Kode</label>
                        <input type="text" name="kode" class="form-control" style="border-radius: 6px;" value="{{ $k->kode }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label text-muted">Nama Kriteria</label>
                        <input type="text" name="nama_kriteria" class="form-control" style="border-radius: 6px;" value="{{ $k->nama_kriteria }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label text-muted">Atribut</label>
                        <select name="atribut" class="form-control" style="border-radius: 6px;" required>
                            <option value="benefit" {{ $k->atribut == 'benefit' ? 'selected' : '' }}>Benefit (Makin besar makin baik)</option>
                            <option value="cost" {{ $k->atribut == 'cost' ? 'selected' : '' }}>Cost (Makin kecil makin baik)</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label text-muted">Bobot (%)</label>
                        <input type="number" step="0.01" name="bobot" class="form-control" style="border-radius: 6px;" value="{{ $k->bobot }}" required>
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