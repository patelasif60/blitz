<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta property="og:image" content="{{ URL::asset('front-assets/images/front/ogimage.png') }}"/>
    <meta name="description" content="blitznet merupakan Super Platform yang aman dan terpercaya untuk UMKM yang berbisnis bahan baku, dimana mempermudah pembeli dan pemasok untuk melakukan perdagangan, mendapatkan akses modal usaha dan mengautomatiskan rantai pasokan" />
    @if (Route::currentRouteName() == 'buyers')
        <meta name="description" content="Tunggu apalagi, mari temukan produk yang Anda butuhkan di sini! Tingkatkan daya tawar dan efisiensikan pengadaan dengan ikut bergabung bersama grup pembeli Temukan sumber pembiayaan untuk Purchase Order Anda melalui solusi keuangan kami.Kami akan membantu Anda untuk tetap terhubung dengan industri dan berita terkini" />
    @elseif (Route::currentRouteName() == 'suppliers')
        <meta name="description" content="Dapatkan seluruh informasi yang dibutuhkan: RFQ, pengumpulan pembayaran, logistik dan lainnya. Jangan khawatirkan penagihan pembayaran Anda. Atur risiko produksi Anda. Buat pekerjaan administrasi semudah mungkin" />
    @endif
    <meta name="keywords" content="Raw Materials, Suppliers, Buyers, Supply Chain, Business Development, Loans, Logistics, What is, Management, Procurement, e-Procurement, e-Purchasing, SaaS, Purchase process" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ URL::asset('home_design/images/favicon.png') }}">
    <!-- Bootstrap CSS -->
    <link href="{{ asset('/front-assets/css/front/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('home_design/css/aos.css') }}">
    <link href="{{ URL::asset('home_design/css/blitnet_style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('home_design/css/holiday_style.css') }}" rel="stylesheet">
    <title>blitznet</title>
    @include('home.tracking.headBottom')

</head>
<body>
<header id="navigatio_top" class="sticky-top" >
    <nav class="navbar navbar-expand-xl navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('home_design/images/blitznet-logo.svg') }}" alt="blitznet logo" class="mw-100 d-none d-sm-block">
                <img src="{{ asset('home_design/images/blitznet-logo-icon_mobile.svg') }}" alt="blitznet logo" class="mw-100 d-sm-none d-block">
            </a>
            <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                  data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                  aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button> -->
            <div class="rightmenu">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown language_drop mx-lg-3 mx-1 ">
                        <a class="nav-link dropdown-toggle nav-48f101c2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="d-inline-block">
                                <img src="{{ asset('home_design/images/icons/icon_nav_language.png') }}" alt="Language">
                            </span>
                            <span class="text-uppercase">{{App::getLocale()}}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{url('lang/id')}}">ID</a></li>
                            <li><a class="dropdown-item" href="{{url('lang/en')}}">EN</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<!-- header end -->
<!-- banner start -->
<div id="carouselCaptions" class="carousel slide position-relative" data-bs-ride="carousel" >
    <!-- santa -->
    <marquee>
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="350" height="400">
            <path fill="transparent" d="M0 0h350v400H0z" />
            <g class="plane">
                <rect x="215.747" y="157.738" width="25.511" height="43.645" rx="12.755" ry="12.755" fill="#711723" />
                <path fill="#f40009" d="M166.263 185.401h74.995v31.965h-74.995zM166.263 217.366h74.995a31.965 31.965 0 01-31.965 31.965h-43.03v-31.965z" />
                <g class="hand">
                    <rect x="136.437" y="152.836" width="26.365" height="9.113" rx="4.557" ry="4.557" transform="rotate(-120 149.62 157.393)" fill="#f6bfb1" />
                    <path fill="#f40009" d="M144.906 163.746l11.978-6.916 20.407 35.346-11.978 6.916z" />
                    <rect x="139.226" y="154.214" width="20.172" height="6.973" rx="3.486" ry="3.486" transform="rotate(-30 149.312 157.7)" fill="#e6e6e6" />
                </g>
                <path fill="#f6bfb1" d="M171.488 155.28h37.805v23.974h-37.805z" />
                <path d="M165.956 185.093v64.545h-12.602v-.024c-.406.015-.818.024-1.23.024a32.272 32.272 0 110-64.545c.412 0 .824.01 1.23.025v-.025z" fill="#711723" />
                <path fill="#300403" d="M161.345 185.093h4.918v64.545h-4.918z" />
                <path d="M113.376 210.296v11.987h-2.34v-.004a6.053 6.053 0 01-.23.004 5.993 5.993 0 110-11.987c.077 0 .154.002.23.005v-.005z" fill="#f40009" />
                <g fill="#300403">
                    <circle cx="155.505" cy="244.106" r="2.459" />
                    <circle cx="155.505" cy="190.933" r="2.459" />
                    <circle cx="155.505" cy="208.452" r="2.459" />
                    <circle cx="155.505" cy="226.586" r="2.459" />
                </g>
                <rect class="blade" x="113.244" y="167.266" width="6.762" height="98.354" rx="3.381" ry="3.381" fill="#300403" />
                <path d="M195.154 211.526h34.732a4.918 4.918 0 014.917 4.918 4.918 4.918 0 01-4.917 4.917h-34.732a4.918 4.918 0 01-4.917-4.917 4.918 4.918 0 014.917-4.918z" fill="#711723" />
                <g fill="#fff">
                    <rect x="174.148" y="171.282" width="15.925" height="40.192" rx="7.963" ry="7.963" />
                    <rect x="188.824" y="171.282" width="15.925" height="40.192" rx="7.963" ry="7.963" />
                    <rect x="180.862" y="167.691" width="15.925" height="51.21" rx="7.963" ry="7.963" transform="rotate(-90 188.824 193.296)" />
                    <path d="M161.55 180.896a7.963 7.963 0 016.42-9.252l20.066-3.625a7.963 7.963 0 019.251 6.42 7.963 7.963 0 01-6.42 9.251l-20.066 3.626a7.963 7.963 0 01-9.251-6.42z" />
                    <path d="M183.122 174.543a7.963 7.963 0 019.251-6.42l19.491 3.521a7.963 7.963 0 016.42 9.252 7.963 7.963 0 01-9.251 6.42l-19.491-3.522a7.963 7.963 0 01-6.42-9.25z" />
                </g>
                <rect x="167.185" y="151.899" width="6.455" height="27.355" rx="3.227" ry="3.227" fill="#711723" />
                <rect x="207.449" y="151.899" width="6.455" height="27.355" rx="3.227" ry="3.227" fill="#711723" />
                <circle cx="190.083" cy="165.883" r="3.842" fill="#e76160" />
                <circle cx="190.083" cy="179.868" r="6.454" />
                <path fill="#f40009" d="M167.185 148.21h46.718v7.069h-46.718zM213.903 145.137h-46.718a10.757 10.757 0 0110.757-10.758h25.204a10.757 10.757 0 0110.757 10.758z" />
                <path fill="#711723" d="M167.185 143.907h46.718v4.303h-46.718z" />
                <circle cx="181.016" cy="146.059" r="7.377" fill="#711723" />
                <circle cx="181.016" cy="146.059" r="5.62" fill="#300403" />
                <circle cx="200.072" cy="146.059" r="7.377" fill="#711723" />
                <circle cx="200.072" cy="146.059" r="5.62" fill="#300403" />
                <path d="M176.713 165.422s2.459-3.995 6.454 0M197.306 165.422s2.459-3.995 6.454 0" fill="none" stroke="#000" stroke-miterlimit="10" stroke-width="1.844" />
            </g>
        </svg>
    </marquee>
    <!-- santa end -->
    <div class="carousel-inner sky">
        <div class="carousel-item active">
            <img src="{{ asset('/home_design/images/holiday_image/christmas-gift-banner.svg') }}" class="d-block w-100" alt="blitznet contact">
            <div class="carousel-caption d-flex align-items-center flex-column w-100 christmas-banner">
                <div class="my-auto" >
                    <h2 class=" mainfonts">{{ __('home_latest.christmas_gift') }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- banner end -->
<!-- banner start -->
<div id="carouselCaptions" class="carousel slide aos-init aos-animate" data-bs-ride="carousel" >
    <div class="carousel-inner">
        <div class="carousel-item active">
            @if (App::getLocale() == 'en')
                <img src="{{ asset('/home_design/images/holiday_image/banner-02.png') }}" class="d-block w-100" alt="Blitznet Home">
            @else
                <img src="{{ asset('/home_design/images/holiday_image/banner-02_id.png') }}" class="d-block w-100" alt="Blitznet Home">
            @endif
            <div class="carousel-caption d-flex flex-column banner-size">
                <div class="my-auto aos-init aos-animate text-start main-banner">
                    <h1 >{!!__('home_latest.months_with_plenty_line1')!!} </h1>
                    <p >{!!__('home_latest.free_register_line2')!!}</p>
                    <a href="{{route('signup')}}" class="btn btn-primary px-3 px-md-5 py-2" >
                        <span>{{ __('home_latest.register_now') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- banner end -->
<div class="container">
    <div class="row justify-content-center py-3 py-lg-5 mycursor">
        <div class="col-md-12">
            <h3 class="text-center empower-header">{{ __('home_latest.empower') }}</h3>
            <p class="text_color text-center empower-text" >{{ __('home_latest.trade') }}</p>
        </div>
        <div class="col-md-12 mycursor my-md-5 my-3" >
            <div class="row justify-content-evenly">
            <div class="col-md-6 col-lg-3 EYB-circle text-center">
                <div>
                <p class="mb-0 text-white circle-Big-text pt-2">{{ __('home_latest.free_register') }}</p>
                <p class="mb-0 text-white circle-Small-text">{!!__('home_latest.become_part')!!} </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 buyers-rfq text-center mycursor">
                <div>
                <p class="mb-0 text-white circle-Big-text pt-2">{{ __('home_latest.buyer_rfq') }}</p>
                <p class="mb-0 text-white circle-Small-text">{!!__('home_latest.configure')!!} </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 blitznet-match text-center mycursor">
                <div>
                <p class="mb-0 text-white circle-Big-text pt-2">{{ __('home_latest.blitzmatch') }}</p>
                <p class="mb-0 text-white circle-Small-text">{!!__('home_latest.let_our')!!} </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3  text-center suppliers-quote mycursor">
                <div>
                <p class="mb-0 text-white circle-Big-text pt-2">{{ __('home_latest.supplier_quote') }}</p>
                <p class="mb-0 text-white circle-Small-text ">{!!__('home_latest.receive_list')!!} </p>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<!-- section end -->
<div class="container-fluid bg-light py-3 py-lg-5">
    <div class="row align-items-center justify-content-center">
        <div class="col-lg-8 col-md-8 col-sm-12 d-flex p-6">
            <div class=" w-100 centercont_img text-center">
                <h3 class=" center-header" >{!!__('home_latest.blitznet_collaborative')!!} </h3>
                <p class="center-text textwithp" >{{ __('home_latest.our_technology') }}</p>
            </div>
        </div>
    </div>
</div>
<div class="container py-3 py-lg-5">
    <div class="row">
        <div class="col-md-12 text-center p-4">
            <h3 class="center-header" >{{ __('home_latest.same_tool') }}</h3>
            <p class="center-text textwithp" >{{ __('home_latest.connecting_processes') }}</p>
        </div>
        <div class="col-md-12 text-center py-4 p-lg-4 threetextul">
            <ul class="d-flex justify-content-evenly border-line row mx-0 px-0" type="none" >
                <li class="col-4 col-lg-3">
                    <img src="{{ asset('/home_design/images/holiday_image/trade.svg') }}" class="trade-img" alt="">
                    <h5 class="fw-bolder list-banner-content">{{ __('home_latest.trade_name') }} <span class="blue-dot"></span>
                    </h5>
                    <p class="text-center small-dot">{!!__('home_latest.match_your_rfq')!!} </p>
                </li>
                <li class="col-4 col-lg-3">
                    <img src="{{ asset('/home_design/images/holiday_image/loan.svg') }}" class="loan-img" alt="">
                    <h5 class="fw-bolder list-banner-content">{{ __('home_latest.loan') }} <span class="blue-dot"></span>
                    </h5>
                    <p class="text-center small-dot">{!!__('home_latest.easy_access_trusted')!!} </p>
                </li>
                <li class="col-4 col-lg-3">
                    <img src="{{ asset('/home_design/images/holiday_image/deliver.svg') }}" class="logistic-img" alt="">
                    <h5 class="fw-bolder list-banner-content">{{ __('home_latest.logistics') }} <span class="blue-dot"></span>
                    </h5>
                    <p class="text-center small-dot"> {!!__('home_latest.best_choice_your_logi')!!} </p>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- section end -->
<div class="bg-light">
    <div class="container py-3 py-lg-5">
        <div class="row">
            <div class="col-md-6 p-lg-5 py-lg-5 py-3" >
                <h2 class="color-primary">{!!__('home_latest.improve_your_work')!!}</h2>
                <p class="my-3 fw-bold">{{ __('home_latest.highlights_of_bliztnet') }}</p>
                <ul class="mb-lg-5 mb-3 buyer_list m-0 p-0">
                    <li>{{ __('home_latest.trusted_qualified_suppliers') }} </li>
                    <li>{{ __('home_latest.best_price_effort') }}</li>
                    <li>{{ __('home_latest.logistic_first_partner') }}</li>
                    <li>{{ __('home_latest.access_to_financing') }}</li>
                </ul>
            </div>
            <div class="col-md-6 p-lg-5 py-lg-5 py-3 pe-lg-0 ms-auto" >
                <img src="{{ asset('home_design/images/buyer_image_5.jpg') }}" alt="Image" class="">
            </div>
        </div>
    </div>
</div>
<!-- section end -->
<div class="container py-3 py-lg-5">
    <div class="row justify-content-center py-3 mb-lg-5 mb-3">
        <div class="col-md-12 mb-2 text-center">
            <h3  class="fw-bolder">{{ __('home_latest.main_raw_material') }}</h3>
            <p class="textwithp">{{ __('home_latest.buy_sell_materials') }}</p>
        </div>
        <div class="row g-3">
            <div class="col-md-3 col-sm-6" >
                <div class="centercont_img">
                    <img src="{{ asset('/home_design/images/holiday_image/food.jpg') }}" alt="Image" class="mb-2 mb-lg-3 w-100">
                </div>
                <p class="fw-bold text-center material-text">{{ __('home_latest.food_agri_farm') }}</p>
                <p class="text-center">{!!__('home_latest.perishable_nonperishable')!!} </p>
            </div>
            <div class="col-md-3 col-sm-6" >
                <div class="centercont_img">
                    <img src="{{ asset('/home_design/images/holiday_image/wood.jpg') }}" alt="Image" class="mb-2 mb-lg-3 w-100">
                </div>
                <p class="fw-bold text-center material-text">{{ __('home_latest.wood') }}</p>
                <p class="text-center">{!!__('home_latest.timber_log')!!} </p>
            </div>
            <div class="col-md-3 col-sm-6" >
                <div class="centercont_img">
                    <img src="{{ asset('/home_design/images/holiday_image/chemicals.jpg') }}" alt="Image" class="mb-2 mb-lg-3 w-100">
                </div>
                <p class="fw-bold text-center material-text">{{ __('home_latest.chemicals') }}</p>
                <p class=" text-center">{!!__('home_latest.liquid_and_granular')!!} </p>
            </div>
            <div class="col-md-3 col-sm-6" >
                <div class="centercont_img">
                    <img src="{{ asset('/home_design/images/holiday_image/steel.jpg') }}" alt="Image" class="mb-2 mb-lg-3 w-100">
                </div>
                <p class="fw-bold text-center material-text">{{ __('home_latest.steel') }}</p>
                <p class=" text-center">{!!__('home_latest.stanly_mid_steel')!!}</p>
            </div>
        </div>
    </div>
</div>
<!-- section end -->
<div class="bg-light py-3 py-lg-3" >
    <section class="container Whatsapp-link d-flex justify-content-center">
        <a href="https://wa.me/6281119087006" class="d-flex text-dark" target="_blank">
            <img src="{{ asset('/home_design/images/holiday_image/whatsapp_icon.png') }}" class="whatsapp-image" width="60px" height="60px" alt="">
            <p class="mb-0 mt-2 ms-3 whatsapp-text fw-bold">{{ __('home_latest.dont_find_cat') }}</p>
        </a>
    </section>
</div>
<!-- section end -->
<div class="container">
    <div class="row py-3 py-lg-5">
        <div class="col-md-12">
            <h3 class="color-primary" >{{ __('home_latest.info_raw_materials') }}</h3>
        </div>
        <div class="accordion py-3" id="accordionExample"  >
            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="headingone">
                    <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseone" aria-expanded="false" aria-controls="collapseTwo">
                        <h6 class="fw-semibold  mb-0"> {{ __('home_latest.raw_material') }} </h6>
                    </button>
                </h2>
                <div id="collapseone" class="accordion-collapse collapse" aria-labelledby="headingone" data-bs-parent="#accordionExample">
                    <div class="accordion-body"> {{ __('home_latest.raw_material_ans') }} </div>
                </div>
            </div>
            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <h6 class="fw-semibold  mb-0"> {{ __('home_latest.products_listed_on_blitznet') }} </h6>
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                    <div class="accordion-body"> {{ __('home_latest.products_listed_on_blitznet_ans') }} </div>
                </div>
            </div>
            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <h6 class="fw-semibold  mb-0"> {{ __('home_latest.loans_for_working_capital') }} </h6>
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                    <div class="according-body">
                        <div class="accordion-body"> {{ __('home_latest.loans_for_working_capital_ans') }} </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        <h6 class="fw-semibold  mb-0"> {{__('home_latest.logistics_services')}} </h6>
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                    <div class="accordion-body"> {{__('home_latest.logistics_services_ans')}}  </div>
                </div>
            </div>
            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="headingSix">
                    <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                        <h6 class="fw-semibold  mb-0"> {{__('home_latest.loans_hire_logistics_as_independent_activites')}} </h6>
                    </button>
                </h2>
                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                    <div class="accordion-body"> {{__('home_latest.loans_hire_logistics_as_independent_activites_ans')}} </div>
                </div>
            </div>
            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="headingSeven">
                    <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                        <h6 class="fw-semibold  mb-0"> {{__('home_latest.how_to_register')}} </h6>
                    </button>
                </h2>
                <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                    <div class="accordion-body"> {{__('home_latest.how_to_register_ans')}} </div>
                </div>
            </div>
            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="headingEight">
                    <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                        <h6 class="fw-semibold  mb-0"> {{__('home_latest.customer_services_technical_support')}} </h6>
                    </button>
                </h2>
                <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#accordionExample">
                    <div class="accordion-body">{{__('home_latest.customer_services_technical_support_ans')}} </div>
                </div>
            </div>
            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="headingNine">
                    <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                        <h6 class="fw-semibold  mb-0"> {{__('home_latest.blitzmatch_qes')}} </h6>
                    </button>
                </h2>
                <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine" data-bs-parent="#accordionExample">
                    <div class="accordion-body"> {{__('home_latest.blitzmatch_qes_ans')}} </div>
                </div>
            </div>
            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="headingTen">
                    <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                        <h6 class="fw-semibold  mb-0"> {{__('home_latest.cost_to_use_blitznet')}} </h6>
                    </button>
                </h2>
                <div id="collapseTen" class="accordion-collapse collapse" aria-labelledby="headingTen" data-bs-parent="#accordionExample">
                    <div class="accordion-body"> {{__('home_latest.free_subscription')}}  <ol>
                            {!!__('home_latest.cost_to_use_blitznet_ans')!!}
                        </ol>
                    </div>
                </div>
            </div>
            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="headingEleven">
                    <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                        <h6 class="fw-semibold  mb-0"> {{__('home_latest.business_ecosystem_in_blitznet')}} </h6>
                    </button>
                </h2>
                <div id="collapseEleven" class="accordion-collapse collapse" aria-labelledby="headingEleven" data-bs-parent="#accordionExample">
                    <div class="accordion-body"> {{__('home_latest.business_ecosystem_in_blitznet_ans')}} </div>
                </div>
            </div>
            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="headingTwelve">
                    <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                        <h6 class="fw-semibold  mb-0"> {{__('home_latest.digitize_your_business')}}</h6>
                    </button>
                </h2>
                <div id="collapseTwelve" class="accordion-collapse collapse" aria-labelledby="headingTwelve" data-bs-parent="#accordionExample">
                    <div class="accordion-body"> {{__('home_latest.digitize_your_business_ans')}} </div>
                </div>
            </div>
            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="headingThirteen">
                    <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThirteen" aria-expanded="false" aria-controls="collapseThirteen">
                        <h6 class="fw-semibold  mb-0"> {{__('home_latest.trade_loan_and_logistics_tool')}} </h6>
                    </button>
                </h2>
                <div id="collapseThirteen" class="accordion-collapse collapse" aria-labelledby="headingThirteen" data-bs-parent="#accordionExample">
                    <div class="accordion-body">{{__('home_latest.trade_loan_and_logistics_tool_ans')}}</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row pb-5">
        <div class="col-md-12">
            <h3 class="color-primary" >{{__('home_latest.our_partner')}}</h3>
        </div>
        <div class="col-md-12 pt-3 " >
            <div class="row px-0 align-items-center Partners-list">
                <div class="jne col-6 col-md-auto flex-fill text-center">
                    <img src="{{ asset('/home_design/images/holiday_image/JNE.png') }}" class="partner-images mw-100" alt="">
                </div>
                <div class="jtr col-6 col-md-auto flex-fill text-center">
                    <img src="{{ asset('/home_design/images/holiday_image/JTR.png') }}" class="partner-images mw-100" alt="">
                </div>
                <div class="koinworks col-6 col-md-auto flex-fill text-center">
                    <img src="{{ asset('/home_design/images/holiday_image/KOINWORKS.png') }}" class="partner-images mw-100" alt="" style="background-size: cover;">
                </div>
                <div class="digiasia col-6 col-md-auto flex-fill text-center">
                    <img src="{{ asset('/home_design/images/holiday_image/DIGIASIA_BIOS.png') }}" class="partner-images mw-100" alt="">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- footer start here -->
@include('home/footer')

<script src="{{ URL::asset('front-assets/js/front/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('home_design/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('home_design/js/aos.js') }}"></script>
<script src="{{ asset('home_design/js/script.js') }}"></script>
<script src="{{ asset('home_design/js/snow.js') }}"></script>
</html>
