<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ URL::asset('assets/images/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ URL::asset('assets/images/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ URL::asset('assets/images/favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ URL::asset('assets/images/favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114"
        href="{{ URL::asset('assets/images/favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120"
        href="{{ URL::asset('assets/images/favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144"
        href="{{ URL::asset('assets/images/favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152"
        href="{{ URL::asset('assets/images/favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ URL::asset('assets/images/favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"
        href="{{ URL::asset('assets/images/favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ URL::asset('assets/images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96"
        href="{{ URL::asset('assets/images/favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ URL::asset('assets/images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ URL::asset('assets/images/favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ URL::asset('assets/images/favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <title>blitznet</title>

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="{{ URL::asset('assets/css/admin/dashboard.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/css/admin/style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/library/pnotify/dist/pnotify.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/library/pnotify/dist/pnotify.buttons.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/library/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">


</head>
<style>
    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }

    @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
    }

</style>

<body>
    @if(Auth::user())
    <header class="navbar navbar-light bg-light sticky-top flex-md-nowrap p-0">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Blitznet</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        {{-- <input class="form-control form-control-dark w-100" type="text" placeholder="{{ __('admin.search') }}" aria-label="Search"> --}}
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                @if (Auth::user())
                    <a class="nav-link px-3" href="{{ route('admin-logout') }}">Sign out</a>
                @endif
            </div>
        </div>
    </header>
    @endif

    <div class="container-fluid">
        <div class="row">
            @if(Auth::user())
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route('supplier-list') }}">
                                <span data-feather="home"></span>
                                Suppliers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route('categories-list') }}">
                                <span data-feather="home"></span>
                                Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route('sub-categories-list') }}">
                                <span data-feather="home"></span>
                                Sub Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route('products-list') }}">
                                <span data-feather="home"></span>
                                Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route('rfq-list') }}">
                                <span data-feather="home"></span>
                                Quote
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            @endif
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @yield('content')
            </main>
        </div>

    </div>
    {{-- <footer>
        <h4>Here is footer</h4>
    </footer> --}}

</body>

<script src="{{ URL::asset('assets/js/parsley.min.js') }}"></script>
<script src="{{ URL::asset('assets/library/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ URL::asset('assets/library/pnotify/dist/pnotify.buttons.js') }}"></script>
<script src="{{ URL::asset('assets/library/pnotify/dist/pnotify.nonblock.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</html>
