<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> --}}
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <link rel="apple-touch-icon" sizes="57x57"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"
        href="{{ URL::asset('front-assets/images/favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ URL::asset('front-assets/images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96"
        href="{{ URL::asset('front-assets/images/favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ URL::asset('front-assets/images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ URL::asset('front-assets/images/favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage"
        content="{{ URL::asset('front-assets/images/favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <title>blitznet</title>

    <!-- CSS only --> 
    <link href="{{ URL::asset('front-assets/css/front/bootstrap.min.css') }}" rel="stylesheet">
    {{--<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script> -->--}}
    <script src="{{ URL::asset('front-assets/js/front/jquery-3.6.0.min.js') }}"></script>
    <link href="{{ URL::asset('front-assets/css/front/blitznet_user.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/calender.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/swal-style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.buttons.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/smart_wizard_all.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/owl.carousel.min.css') }}" rel="stylesheet">
    {{--<!-- <link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet"> -->--}}
    <link href="{{ URL::asset('front-assets/css/front/jquery.dataTables.min.css') }}" rel="stylesheet">
    {{--<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">-->--}}
    <link href="{{ URL::asset('front-assets/css/front/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/jquery-ui-1.12.1.custom/jquery-ui.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('front-assets/intlTelInput/css/intlTelInput.css')}}">
    <link href="{{ URL::asset('front-assets/css/front/group_trading.css') }}" rel="stylesheet">
    
    <style>
        .main_section::before {
            height: 90px;
        }

        .iti{
            display: block !important;
        }
        .iti .iti--allow-dropdown{width: 100% !important;}
        .iti__flag-container{position: absolute !important;}
        .iti__country-list{ overflow-x: hidden; max-width: 360px;}
        .iti--separate-dial-code .iti__selected-flag{font-size: 12px;}
    </style>

</head>

<body>
    <header class="dark_blue_bg">
        <div class="px-3">
            <div class="row">
                <div class="col-auto p-3 py-2">
                    <a href="{{ route('home') }}"><img
                            src="{{ URL::asset('front-assets/images/front/header-logo.png') }}" alt="Blitznet"></a>
                    <button class="btn btn-primary d-lg-none" type="button" data-bs-toggle="collapse"
                        data-bs-target="#userinfo" aria-expanded="true" aria-controls="userinfo">
                        <img src="{{ URL::asset('front-assets/images/icons/icon_navbar.png') }}" alt="Nav">
                    </button>
                </div>
                <div class="col-auto ms-auto p-3 py-2">
                    <div class="btn-group home_lenguage ps-2">

                        <button type="button" class="btn text-white dropdown-toggle" style="min-width: inherit;"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ strtoupper(str_replace('_', '-', app()->getLocale())) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width: inherit;">
                            <li><a class="dropdown-item" href="{{url('lang/id')}}">ID</a></li>
                            <li><a class="dropdown-item" href="{{url('lang/en')}}">EN</a></li>

                        </ul>

                    </div>
                    @if (Auth::user())
                        <div class="btn-group notification_head">
                            <a type="button" class="btn btn-transparent dropdown-toggle none" id="userActivityBtn"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ URL::asset('front-assets/images/icons/icon_bell.png') }}"
                                    alt="Account Updates">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end p-3 shadow-lg" id="userActivitySection">
                                {{-- here userActivity are showing and data are fetching from ajax --}}
                            </ul>
                        </div>

                        <a href="{{ route('logout') }}"
                            class="btn btn-danger radius_2">{{ __('dashboard.logout') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </header>
    @php
        $segment = Request::segment(1);
        $segment_array = array('supplier-order-status', 'view-accept-order-details');
        if (in_array($segment, $segment_array) ){
            $class = 'container-fluid';
        } else {
            $class = 'container';
        }
    @endphp
    <div class="main_section position-relative {{ $class != 'container-fluid' ? 'bg-light' : '' }} ">
        <div class="{{ $class }}">
            <div class="row gx-4 mx-lg-0 {{ $class == 'container-fluid' ? 'justify-content-center' : '' }}">
                @yield('content')
            </div>
        </div>
    </div>
</body>
<!-- JavaScript Bundle with Popper -->
<script src="{{ URL::asset('front-assets/js/front/bootstrap.bundle.min.js') }}"></script>
{{--<!-- <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> -->--}}
<script src="{{ URL::asset('/assets/vendors/datatables.net/1.10.25/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/parsley.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.buttons.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.nonblock.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/front/jquery.smartWizard.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/front/owl.carousel.min.js') }}"></script>
{{--<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->--}}
<script src="{{ URL::asset('/assets/vendors/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/front/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>


<script>
    // setInterval(function() {
    //     loadUserActivityData();
    // }, 5000);

    function loadUserActivityData() {

        $.ajax({
            url: "{{ route('dashboard-user-activity-ajax') }}",
            type: 'GET',
            success: function(successData) {
                console.log("loaded")
                $('#userActivitySection').html(successData.userActivityHtml);
            },
            error: function() {
                console.log('error');
            }
        });
    }

    $(document).on('click', '#userActivityBtn', function() {
        loadUserActivityData();
    });
</script>

</html>
