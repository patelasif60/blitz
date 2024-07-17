<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- START: Google Analytics Script -->
    @if (config('app.env') == "live" || config('app.env') == "production")
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-6EPP6B3KLX"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date()); gtag('config', 'G-6EPP6B3KLX');
        </script>
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-TMKZNTK');</script>

        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-230580295-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'UA-230580295-1');
        </script>
        <!-- End Google Tag Manager -->
    @endif
    <!-- END: Google Analytics Script -->

    <!-- START: HotJar Analysis Script -->
    @if (config('app.env') == "live")
    <script>
        window._mfq = window._mfq || [];
        (function() {
            var mf = document.createElement("script");
            mf.type = "text/javascript"; mf.defer = true;
            mf.src = "//cdn.mouseflow.com/projects/e23b2581-094b-4fe3-a8d3-149417b0fbc5.js";
            document.getElementsByTagName("head")[0].appendChild(mf);
        })();
    </script>
    @endif
    <!-- END: HotJar Analysis Script -->

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
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous"> --}}
    <link href="{{ URL::asset('front-assets/css/front/bootstrap.min.css') }}" rel="stylesheet">
    {{--<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script> -->--}}
    <script src="{{ URL::asset('front-assets/js/front/jquery-3.6.0.min.js') }}"></script>
    <link href="{{ URL::asset('front-assets/css/front/style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/calender.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.buttons.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/smart_wizard_all.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('front-assets/css/front/animate.css') }}" />
    {{-- <link rel="stylesheet" href="{{ URL::asset('front-assets/css/front/base.css') }}" />
    <!-- <link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet"> -->--}}
    <link href="{{ URL::asset('front-assets/css/front/jquery.dataTables.min.css') }}" rel="stylesheet">
    {{--<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"> -->--}}
    <link href="{{ URL::asset('front-assets/library/OwlCarousel/dist/assets/owl.carousel.min.css') }}"
        rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/aos.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/blitznet.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/jquery-ui-1.12.1.custom/jquery-ui.min.css') }}" rel="stylesheet">




</head>
<style>
    .quote-btn {
        border-radius: 30px;
        margin-left: 15px;
    }

    .userTypeError {
        color: red;
    }

</style>

<body>

    <header id="navigatio_top" class="fixed-top">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-12 px-lg-4">
                    <nav class="navbar navbar-expand-xl navbar-dark" aria-label="Ninth navbar example"
                        data-aos="zoom-out">
                        <div class="container-fluid px-0 mx-0 px-lg-3 mx-lg-auto">
                            <a class="navbar-brand" href="#"><img
                                    src="{{ URL::asset('front-assets/images/front/header-logo.png') }}" alt=""
                                    class="mw-100"></a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarsXL" aria-controls="navbarsXL" aria-expanded="false"
                                aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse " id="navbarsXL">
                                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                                    <!-- <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                                    </li>-->
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="#aboutsection">{{ __('home.About blitznet') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="#home_subscribe">{{ __('home.Contact us') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('group-trading') }}">{{ __('dashboard.group_trading') }}</a>
                                    </li>
                                    @if (!Auth::user())
                                         <li class="nav-item px-xl-2 dropdown ">
                                            <button class="btn btn-light my-1 dropdown-toggle color_blue" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ __('home.Login') }}
                                            </button>
                                            <ul class="dropdown-menu arrowup" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item" href="{{ route('signin') }}">{{__('home.buyer')}}</a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin-login') }}">{{__('home.supplier')}}</a></li>
                                            </ul>
                                        </li>
                                        <li class="nav-item px-xl-2">
                                            <a class="btn btn-primary my-1"
                                                href="{{ route('signup') }}">{{ __('home.Sign Up') }}</a>
                                        </li>
                                    @endif
                                    <li class="nav-item pl-xl-2">
                                        <a class="btn btn-info my-1 color_blue" role="button"
                                            href="{{ route('get-a-quote') }}">{{ __('home.Get a quote') }}</a>
                                    </li>
                                </ul>
                            </div>
                            @if (Auth::user())
                                <div class="btn-group ps-2 home_user">
                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="{{ URL::asset('front-assets/images/front/icon_user.png') }}"
                                            alt="user">
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" type="button"
                                                href="{{ route('dashboard') }}">Dashboard</a></li>
                                        <li class="bg-light"><a class="dropdown-item" type="button"
                                                href="{{ route('logout') }}">{{ __('admin.logout') }}</a></li>

                                    </ul>
                                </div>
                            @endif
                            <div class="btn-group home_lenguage ps-2">

                                <button type="button" class="btn text-white dropdown-toggle" style="min-width: inherit;"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ strtoupper(str_replace('_', '-', app()->getLocale())) }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" style="min-width: inherit;">
                                    <li><a class="dropdown-item" href="lang/id">ID</a></li>
                                    <li><a class="dropdown-item" href="lang/en">EN</a></li>

                                </ul>

                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>

    </header>

    <banner>
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" data-bs-pause="false">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0"
                    class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3"
                    aria-label="Slide 4"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ URL::asset('front-assets/images/front/banner_1.jpg') }}" class="d-block w-100"
                        alt="...">
                    <div class="carousel-caption d-none d-md-block h-100">
                        <div class="d-flex align-items-center h-100">
                            <div class="animated bounce">
                                <h5>{{ __('home.slider_common_text') }}</h5>
                                <h4>{{ __('home.slider1_line1') }}<br>
                                    {{ __('home.slider1_line2') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ URL::asset('front-assets/images/front/banner_2.jpg') }}" class="d-block w-100"
                        alt="...">
                    <div class="carousel-caption d-none d-md-block h-100">
                        <div class="d-flex align-items-center h-100">
                            <div class="animated pulse">
                                <h5>{{ __('home.slider_common_text') }}</h5>
                                <h4>{{ __('home.slider2_line1') }}<br>
                                    {{ __('home.slider2_line2') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ URL::asset('front-assets/images/front/banner_3.jpg') }}" class="d-block w-100"
                        alt="...">
                    <div class="carousel-caption d-none d-md-block h-100">
                        <div class="d-flex align-items-center h-100">
                            <div class="animated bounceIn">
                                <h5>{{ __('home.slider_common_text') }}</h5>
                                <h4>{{ __('home.slider3_line1') }}<br>
                                    {{ __('home.slider3_line2') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ URL::asset('front-assets/images/front/banner_4.jpg') }}" class="d-block w-100"
                        alt="...">
                    <div class="carousel-caption d-none d-md-block h-100">
                        <div class="d-flex align-items-center h-100">
                            <div class="animated shake">
                                <h5>{{ __('home.slider_common_text') }}</h5>
                                <h4>{{ __('home.slider4_line1') }}<br>
                                    {{ __('home.slider4_line2') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button> -->
        </div>
    </banner>


    <div class="container-fluid overflow-hidden">

        @yield('content')

    </div>
    {{-- <footer>
        <h4>Here is footer</h4>
    </footer> --}}

    <footer class="sticky-footer" data-aos="fade-down">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 px-5 text-white pt-4">
                    <div class="row">
                        <div class="col-md-3 mb-4 mb-md-0">
                            <p class="mb-3"><img
                                    src="{{ URL::asset('front-assets/images/front/header-logo.png') }}" alt="logo">
                            </p>
                            <p><strong>blitznet</strong> {{ __('home.footer_about') }}</p>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-8  mb-4  mb-md-0">
                                    <h5 class="pb-3">{{ __('home.office_address') }}</h5>
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <address>
                                                <p><img src="{{ URL::asset('front-assets/images/flag_indonesia.png') }}"
                                                        alt=""><br> Gedung Centennial Tower<br> Lt. 29 Unit D Dan E<br>
                                                    Jl. Jend Gatot Subroto Kav.<br> 24 -25 Rt. 002 Rw. 002 <br>
                                                    Karet Semanggi, Setiabudi<br>
                                                    Jakarta Selatan DKI, Jakarta
                                                </p>
                                            </address>
                                        </div>
                                        <div class="col-xl-6">
                                            <address>
                                                <p><img src="{{ URL::asset('front-assets/images/flag_india.png') }}"
                                                        alt=""><br>401-SMIT<br>
                                                    Sarabhai IT Campus<br>
                                                    Alembic Rd, , Gorwa,
                                                    Vadodara,<br>
                                                    Gujarat 390023, India
                                                </p>
                                            </address>
                                        </div>
                                    </div>

                                </div>


                                <div class="col-md-4 mb-4  mb-md-0">
                                    <h5 class="pb-3">{{ __('home.about_us') }}</h5>
                                    <ul class="mb-3">
                                        <li><a href="#">{{ __('home.terms_condition') }}</a></li>
                                        <li><a href="#offer_section">{{ __('home.our_offer') }}</a></li>
                                        <li><a href="#">{{ __('home.faq') }} </a></li>

                                    </ul>
                                    <h5 class="___class_+?78___">{{ __('home.Contact us') }}</h5>
                                    <ul class="mb-4">
                                        <!-- <li><a href="#" ><span class="pr-1"><img src="images/icon_map.png" alt=""> </span> Jakarta, Indonesia</a></li>
                                        <li><a href="#" ><span class="pr-1"><img src="images/icon_call.png"> </span> 1123-258-2563</a></li> -->
                                        <li><a href="mailto:contact@blitznet.co.id" class="text-break"><span
                                                    class="pr-1"><img
                                                        src="{{ URL::asset('front-assets/images/front/icon_mail.png') }}"
                                                        alt=""></span>
                                                contact@blitznet.co.id</a></li>

                                    </ul>


                                </div>

                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <h5>{{ __('home.Newsletter') }}</h5>
                            <div class="card p-1 mb-4">
                                <form data-parsley-validate autocomplete="off" name="newsletter_form"
                                    id="newletter_form">
                                    @csrf
                                    <div class="input-group ">
                                        <label for="newsletter1" class="visually-hidden">Email address</label>
                                        <input required id="newsletter1" type="email" name="email"
                                            class="form-control border-0" placeholder="{{ __('home.email') }}">
                                        <button class="btn btn-primary"
                                            type="submit">{{ __('home.Subscribe') }}</button>
                                    </div>
                                </form>
                                <div id="news-success" style=" position: absolute; bottom: -19px; display:none"
                                    class="text-success">{{ __('home.newsletter_success') }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <h5>{{ __('home.follow_us') }}</h5>
                                    <a href="https://www.linkedin.com/company/74970780/admin/" target="_blank"><img
                                            src="{{ URL::asset('front-assets/images/front/icon_linkedin_s.png') }}"
                                            alt="linkedin"></a>
                                </div>
                                <div class="col-lg-6">
                                    <h5 class="mt-4 mt-lg-0">{{ __('home.Partnership') }}</h5>
                                    <p><img src="{{ URL::asset('front-assets/images/front/jne_logo.png') }}"
                                            alt="jne" class="mw-100"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid footerend">
            <div class="col-md-12 px-5 py-1 text-center text-white">
                <p class="mb-0"><b>Copyright <span style="font-family: arial;">©</span>


                        2021 Blitznet. {{__('dashboard.all_rights_reserved')}}</b></p>
            </div>
        </div>
    </footer>

    <!-- JavaScript Bundle with Popper -->
    <script src="{{ URL::asset('front-assets/js/front/bootstrap.bundle.min.js') }}"></script>
    {{--<!-- <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> -->--}}
    <script src="{{ URL::asset('/assets/vendors/datatables.net/1.10.25/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('front-assets/js/parsley.min.js') }}"></script>
    <script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.js') }}"></script>
    <script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.buttons.js') }}"></script>
    <script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.nonblock.js') }}"></script>
    <script src="{{ URL::asset('front-assets/js/front/jquery.smartWizard.js') }}"></script>
    <script src="{{ URL::asset('front-assets/js/front/parallax.min.js') }}"></script>
    <script src="{{ URL::asset('front-assets/js/front/wow.min.js') }}"></script>
    {{-- <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->--}}
    <script src="{{ URL::asset('/assets/vendors/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('front-assets/library/OwlCarousel/dist/owl.carousel.min.js') }}"></script>
    <script src="https://kit.fontawesome.com/206c5daa49.js" crossorigin="anonymous"></script>

    <script src="{{ URL::asset('front-assets/js/front/aos.js') }}"></script>
    <script src="{{ URL::asset('front-assets/js/front/script.js') }}"></script>
    <script src="{{ URL::asset('front-assets/js/front/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('front-assets/library/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>


    <script>
        $(document).ready(function() {
            $('#news-success').hide();
            $('#newletter_form').submit(function(e) {
                e.preventDefault();
                if ($('#newletter_form').parsley().isValid()) {
                    var formData = new FormData($("#newletter_form")[0]);
                    $.ajax({
                        url: "{{ route('add-newsletter') }}",
                        data: formData,
                        type: "POST",
                        contentType: false,
                        processData: false,
                        success: function(successData) {
                            $('#news-success').show();
                            $("#newletter_form")[0].reset();

                        },
                        error: function() {
                            console.log("error");
                        },
                    });

                }
            });
        });
    </script>

</body>

</html>
