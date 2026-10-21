@extends('tampilan.main')
@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-header bg-white py-4" style="border-radius: 12px 12px 0 0;">
            <h5 class="mb-0 font-weight-bold text-dark"><i class="ti-printer text-primary mr-2"></i> Laporan Hasil Rekomendasi SAW</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="border-0 px-4 py-3">Nama Pemilik</th>
                            <th class="border-0 py-3">Luas Lahan</th>
                            <th class="border-0 py-3">Pupuk Terbaik (Peringkat 1)</th>
                            <th class="border-0 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lahans as $lahan)
                        @php $rekomendasi = $lahan->hasilSAW->first(); @endphp
                        <tr>
                            <td class="px-4 py-3 font-weight-bold">{{ $lahan->pemilik->nama_pemilik }}</td>
                            <td class="py-3">{{ $lahan->luas_lahan }} Ha</td>
                            <td class="py-3 text-success font-weight-bold">{{ $rekomendasi->pupuk->nama_pupuk ?? '-' }}</td>
                            <td class="py-3 text-center">
                                <a href="{{ route('laporan.cetak_saw', $lahan->id_lahan) }}" target="_blank" class="btn btn-primary shadow-sm" style="border-radius: 6px;">
                                    <i class="ti-printer mr-1"></i> Cetak Dokumen
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4">Belum ada lahan yang dihitung SAW.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection