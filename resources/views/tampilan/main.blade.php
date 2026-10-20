<!DOCTYPE html>
<html lang="id">
<head>
    <title>SPK Rekomendasi Pupuk - GIS Pemetaan Lahan</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/pages/waves/css/waves.min.css') }}" type="text/css" media="all">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/themify-icons/themify-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome-n.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.mCustomScrollbar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/icofont/css/icofont.css') }}">
    
    <!-- Tempat untuk menyisipkan CSS tambahan seperti Leaflet dari halaman lain -->
    @stack('styles')

</head>

<body>
    <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="loader-track">
            <div class="preloader-wrapper">
                <div class="spinner-layer spinner-blue">
                    <div class="circle-clipper left"><div class="circle"></div></div>
                    <div class="gap-patch"><div class="circle"></div></div>
                    <div class="circle-clipper right"><div class="circle"></div></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pre-loader end -->
    
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <nav class="navbar header-navbar pcoded-header">
                <div class="navbar-wrapper">
                    <div class="navbar-logo">
                    <a class="mobile-menu waves-effect waves-light" id="mobile-collapse" href="#!">
                        <i class="ti-menu"></i>
                    </a>
                    <!-- UBAH BAGIAN HREF DI BAWAH INI -->
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center">
                        <i class="ti-map-alt text-white ml-3 mr-2" style="font-size: 22px;"></i>
                        <b class="text-white" style="letter-spacing: 1px;">SPK SAW GIS</b>
                    </a>
                    </div>
                    
                    <div class="navbar-container container-fluid">
                        <ul class="nav-left">
                            <li>
                                <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
                            </li>
                            <li>
                                <a href="#!" onclick="javascript:toggleFullScreen()" class="waves-effect waves-light" title="Layar Penuh">
                                    <i class="ti-fullscreen"></i>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav-right">
                            <li class="user-profile header-notification">
                                <a href="#!" class="waves-effect waves-light d-flex align-items-center">
                                    <span class="font-weight-bold">{{ Auth::user()->name ?? 'Administrator' }}</span>
                                    <i class="ti-angle-down ml-2"></i>
                                </a>
                                <ul class="show-notification profile-notification">
                                    <li class="waves-effect waves-light">
                                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="waves-effect waves-light text-danger">
                                            <i class="ti-power-off"></i> Keluar 
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <nav class="pcoded-navbar">
                        <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
                        <div class="pcoded-inner-navbar main-menu">
                            <div class="main-menu-header text-center pt-4 pb-3">
                                <div class="user-details">
                                    <span id="more-details" class="font-weight-bold" style="font-size: 15px;">{{ Auth::user()->name ?? 'Administrator' }}</span>
                                    <!-- Menambahkan badge status Online -->
                                    <div class="mt-2">
                                        <span class="badge badge-pill badge-success" style="font-size: 11px; padding: 5px 10px;">
                                            <i class="ti-check-box mr-1"></i>Online
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Memanggil file sidebar -->
                            @include('tampilan.sidebar')
                        </div>
                    </nav>
                    
                    <div class="pcoded-content">
                        <!-- Menambahkan background subtle (opsional) agar card konten lebih menonjol -->
                        <div class="page-wrapper" style="background-color: #f4f7fa; min-height: 100vh;">
                            <div class="page-body">
                                <!-- Konten Halaman Utama -->
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Jquery -->
    <script type="text/javascript" src="{{ asset('assets/js/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery-ui/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/popper.js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/pages/waves/js/waves.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script src="{{ asset('assets/js/pcoded.min.js') }}"></script>
    <script src="{{ asset('assets/js/vertical/vertical-layout.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/script.js') }}"></script>

    <!-- Tempat untuk menyisipkan Script tambahan seperti Leaflet JS -->
    @stack('scripts')

</body>
</html>