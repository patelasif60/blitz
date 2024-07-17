<!doctype html>
<html lang="en">

<head>
    <title>404</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS v5.0.2 -->
    <link href="{{ URL::asset('home_design/css/blitnet_style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/bootstrap.min.css') }}" rel="stylesheet">
</head>

<body>
    <header id="navigatio_top" class="aos-init aos-animate" data-aos="fade-down">
        <nav class="navbar navbar-expand-xl navbar-light">
            <div class="container-xl d-flex justify-content-center ">
                <a class="navbar-brand" href="{{url('/')}}">
                    <img src="{{ asset('home_design/images/blitznet-logo.svg') }}" alt="Blitznet Logo" class="mw-100 d-none d-sm-block" style="height: 50px">
                    <img src="{{ asset('home_design/images/blitznet-logo-icon_mobile.svg') }}" alt="Blitznet Logo" class="mw-100 d-sm-none d-block">
                </a>
            </div>
        </nav>
    </header>
    <div class="container mt-lg-5 mt-3">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-6 text-center">
                <h2 class="fw-bold mb-0 text-uppercase">{{__('home.page_not_found')}}</h2>
                <div class="">
                    <img src="{{ asset('assets/images/errors/error_404.svg') }}" class="w-75" alt="" srcset="">
                </div>
                @if(Auth::check())
                    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 3 || Auth::user()->role_id == 4 || Auth::user()->role_id == 5)
                        <a href="{{ route('admin-dashboard') }}" class="btn btn-primary btn-sm px-5 mt-2"><span>{{__('home.back_to_home')}}</span></a>
                    @elseif (Auth::user()->role_id == 2 || Auth::user()->role_id == 6)
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm px-5 mt-2"><span>{{__('home.back_to_home')}}</span></a>
                    @endif
                @else
                    <a href="{{ route('home') }}" class="btn btn-primary btn-sm px-5 mt-2"><span>{{__('home.back_to_home')}}</span></a>
                @endif
            </div>
        </div>
    </div>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="{{ URL::asset('front-assets/js/front/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
