<div class="pcoded-navigation-label">Menu Utama</div>
<ul class="pcoded-item pcoded-left-item">
    <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
        <!-- UBAH BAGIAN HREF DI BAWAH INI -->
        <a href="{{ route('dashboard') }}" class="waves-effect waves-dark">
            <span class="pcoded-micon"><i class="ti-home text-primary"></i><b>D</b></span>
            <span class="pcoded-mtext">Dashboard</span>
        </a>
    </li>
</ul>

<div class="pcoded-navigation-label">GIS & SPK</div>
<ul class="pcoded-item pcoded-left-item">
    <li class="{{ request()->segment(1) == 'pemetaan' ? 'active' : '' }}">
        <a href="{{ route('pemetaan.index') }}" class="waves-effect waves-dark">
            <span class="pcoded-micon"><i class="ti-map-alt"></i><b>P</b></span>
            <span class="pcoded-mtext">Pemetaan Lahan</span>
            <span class="pcoded-mcaret"></span>
        </a>
    </li>
</ul>

@if(Auth::user()->role == 'admin')
<div class="pcoded-navigation-label">Master Data</div>
<ul class="pcoded-item pcoded-left-item">
    <li class="{{ request()->segment(1) == 'pemilik' ? 'active' : '' }}">
        <a href="{{ route('pemilik.index') }}" class="waves-effect waves-dark">
            <span class="pcoded-micon"><i class="ti-user text-success"></i><b>P</b></span>
            <span class="pcoded-mtext">Pemilik Lahan</span>
        </a>
    </li>
    <li class="{{ request()->segment(1) == 'kriteria' ? 'active' : '' }}">
        <a href="{{ route('kriteria.index') }}" class="waves-effect waves-dark">
            <span class="pcoded-micon"><i class="ti-view-list-alt"></i><b>K</b></span>
            <span class="pcoded-mtext">Data Kriteria</span>
        </a>
    </li>
    <li class="{{ request()->segment(1) == 'alternatif' ? 'active' : '' }}">
        <a href="{{ route('alternatif.index') }}" class="waves-effect waves-dark">
            <span class="pcoded-micon"><i class="ti-layers"></i><b>A</b></span>
            <span class="pcoded-mtext">Alternatif (Pupuk)</span>
        </a>
    </li>
</ul>
@endif

<!-- Tampil untuk ADMIN dan PIMPINAN -->
@if(in_array(Auth::user()->role, ['admin', 'pimpinan']))
<div class="pcoded-navigation-label">Laporan & Evaluasi</div>
<ul class="pcoded-item pcoded-left-item">
    <li class="{{ request()->segment(1) == 'laporan' ? 'active' : '' }}">
        <a href="{{ route('laporan.index') }}" class="waves-effect waves-dark">
            <span class="pcoded-micon"><i class="ti-printer text-primary"></i><b>L</b></span>
            <span class="pcoded-mtext">Laporan SAW</span>
        </a>
    </li>
</ul>
@endif