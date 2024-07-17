@extends('home/homeLayout2')

@section('content')
    <!-- banner start -->
    <div id="carouselCaptions" class="carousel slide bannersection aos-init aos-animate" data-bs-ride="carousel" data-aos="fade-down">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('home_design/images/Suppliers_Main-banner.jpg') }}" class="d-block w-100" alt="Blitznet Supplier">
                <div class="carousel-caption d-flex align-items-center flex-column ">
                    <div class="my-auto aos-init text-start" data-aos="fade-up">
                        <h1 class="banner-color" data-aos="fade-up-right">{{ __('frontend.supplier_main_heading') }}</h1>
                        <p class="banner-color mb-1 mb-lg-3" data-aos="fade-up-left">{{ __('frontend.supplier_main_head_prg') }}</p>
                        <div class="mt-lg-5">
                            <a href="{{ route('signup-supplier') }}" class="btn btn-primary bannerbtn mb-1 suppliers-48f13c96" data-aos="fade-up-right">
                                <span>{{ __('frontend.join_now') }}</span>
                            </a>
                            <a href="{{ $calendlyMeetingLink }}" class="btn btn-primary bannerbtn ms-lg-4 mb-1 suppliers-48f13e6c" data-aos="fade-up-left">
                                <span>{{ __('frontend.schedule_callback') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- banner end -->

    <div class="container">
        <div class="row my-lg-5 my-3">
            <div class="row align-items-center my-3">
                <div class="col-md-5">
                    <h3 class="mb-lg-4 mb-3" data-aos="fade-up-right">{{ __('frontend.supplier_sec_heading') }}</h3>
                    <p class="text_color" data-aos="fade-up-right">{{ __('frontend.supplier_sec_head_prg') }}</p>
                    <p class="text_color" data-aos="fade-up-left">{{ __('frontend.supplier_sec_head_prg_2') }} </p>
                </div>
                <div class="col-md-7 pt-3 pt-lg-5" data-aos="fade-up-left">
                    <div class="text-center mx-auto">
                        <img src="{{ asset('home_design/images/supplier_image_1.png') }}" alt="blitznet home about">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row my-lg-5 my-3">
            <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel"
                data-aos="zoom-out-up">
                <div class="carousel-indicators d-flex justify-content-end mx-lg-5 mx-3 mb-1">
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="10000">
                        @if (App::getLocale() == 'en')
                            <img src="{{ asset('home_design/images/Supplier_img_1.png') }}" class="d-block w-100" alt="blitznet banner en 1">
                        @else
                            <img src="{{ asset('home_design/images/Supplier_img_ID_1.png') }}" class="d-block w-100" alt="blitznet banner id 1">
                        @endif
                    </div>
                    <div class="carousel-item" data-bs-interval="2000">
                        @if (App::getLocale() == 'en')
                            <img src="{{ asset('home_design/images/Supplier_img_2.png') }}" class="d-block w-100" alt="blitznet banner en 2">
                        @else
                            <img src="{{ asset('home_design/images/Supplier_img_ID_2.png') }}" class="d-block w-100" alt="blitznet banner id 2">
                        @endif
                    </div>
                    <div class="carousel-item">
                        @if (App::getLocale() == 'en')
                            <img src="{{ asset('home_design/images/Supplier_img_3.png') }}" class="d-block w-100" alt="blitznet banner en 3">
                        @else
                            <img src="{{ asset('home_design/images/Supplier_img_ID_3.png') }}" class="d-block w-100" alt="blitznet banner id 3">
                        @endif
                    </div>
                </div>
                <button class="carousel-control-prev suppliers-48f1404c" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next suppliers-48f1448e" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row my-lg-5 my-3">
            <div class="col-md-12">
                <h3 class="color-primary" data-aos="fade-up-right">{{ __('frontend.supplier_sup_plt') }}</h3>
                <h4 data-aos="fade-up-left">{{ __('frontend.new_supplier_chain') }}</h4>
            </div>
            <div class="row align-items-center  my-3">
                <div class="col-md-8" data-aos="fade-up-right">
                    <img src="{{ asset('home_design/images/supplier_image_2.png') }}" alt="blitznet home about">
                </div>
                <div class="col-md-4 text-justify cornerefects" data-aos="fade-up-left">
                    <p class="text_color">{{ __('frontend.supplier_prg_1') }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- section end -->

    <div class="container">
        <div class="row my-lg-5 my-3 buyer_color_card">
            <div class="col-md-12 text-center my-lg-5 my-3">
                <h4 class="fw-bold" data-aos="fade-up-right">{{ __('frontend.supplier_manage_proc') }}</h4>
            </div>
            <div class="col-md-4 mb-lg-5 mb-3">
                <div class="card h-100 border-0 ">
                    <div class="card-header py-1 cyan_card d-flex align-items-center justify-content-center">
                        <p class="mb-0 text-white f-14">{{ __('frontend.salesRFQOne') }}</p>
                    </div>
                    <div class="centercont_img triimage_1 grey_bg py-4">
                        <img src="{{ asset('home_design/images/supplier_image_3_1.svg') }}" alt="Image" class="mw-100" data-aos="flip-up"></div>
                    <div class="card-body centercont_img text-center mt-4 grey_bg ">
                        <p class="f-14">{{ __('frontend.salesRFQOne_prg_1') }}</p>
                        <p class="f-14">{{ __('frontend.salesRFQOne_prg_2') }}</p>
                        <p class="f-14 fw-bold">{{ __('frontend.salesRFQOne_prg_3') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-lg-5 mb-3">
                <div class="card h-100 border-0">
                    <div class="card-header lightblue_card d-flex align-items-center justify-content-center py-1">
                        <p class="mb-0 text-white f-14">{{ __('frontend.finance_secure_trans') }}</p>
                    </div>
                    <div class="centercont_img triimage_2 grey_bg py-4">
                        <img src="{{ asset('home_design/images/supplier_image_3_2.svg') }}" alt="Image" class="mw-100" data-aos="flip-up">
                    </div>
                    <div class="card-body centercont_img text-center mt-4 grey_bg">
                        <p class="f-14">{{ __('frontend.finance_secure_trans_prg_1') }}</p>
                        <p class="f-14">{{ __('frontend.finance_secure_trans_prg_2') }}</p>
                        <p class="f-14 fw-bold">{{ __('frontend.finance_secure_trans_prg_3') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-lg-5 mb-3">
                <div class="card h-100 border-0">
                    <div class="card-header primary_card d-flex align-items-center justify-content-center py-1">
                        <p class="mb-0 text-white f-14">{{ __('frontend.logistics_integrated_solution') }}</p>
                    </div>
                    <div class="centercont_img triimage_3 grey_bg py-4">
                        <img src="{{ asset('home_design/images/buyer_image_3_2.svg') }}" alt="Image" class="mw-100" data-aos="flip-up">
                    </div>
                    <div class="card-body centercont_img text-center mt-4 grey_bg">
                        <p class="f-14">{{ __('frontend.logistics_integrated_solution_prg_1') }}</p>
                        <p class="f-14">{{ __('frontend.logistics_integrated_solution_prg_2') }}</p>
                        <p class="f-14 fw-bold">{{ __('frontend.logistics_integrated_solution_prg_3') }}</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mx-0">
                <div class="col-md-4 col-lg-3 py-1">
                    <a href="{{ route('signup-supplier') }}" class="btn btn-primary px-3 w-100 suppliers-48f14696" data-aos="fade-up-right">
                        <span>{{ __('frontend.join_now') }}</span>
                    </a>
                </div>
                <div class="col-md-4 col-lg-3 py-1">
                    <a href="{{ $calendlyMeetingLink }}" class="btn btn-primary px-3 w-100 text-nowrap suppliers-48f1486c" data-aos="fade-up-left">
                        <span>{{ __('frontend.schedule_callback') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row my-lg-5 my-3">
            <div class="col-md-12 text-center">
                <h3 class="color-primary" data-aos="fade-up-right">{{ __('frontend.join_digi_buyer_grp') }}</h3>
                <h4 data-aos="fade-up-left">{{ __('frontend.join_digi_buyer_grp_heading') }}</h4>
            </div>
            <div class="row align-items-center my-3 p-lg-5 pe-lg-0">
                <div class="col-md-4" data-aos="fade-up-right">
                    <p class="text_color mb-3">{{ __('frontend.sup_join_digi_buyer_grp_prg_1') }}</p>
                    <p class="text_color mb-3">{{ __('frontend.sup_join_digi_buyer_grp_prg_2') }}</p>
                    <p class="text_color mb-3">{{ __('frontend.sup_join_digi_buyer_grp_prg_3') }}</p>
                    <a href="{{ route('group-trading') }}" class="btn new_btn1 text-dark btn-secondary px-3 fw-bold suppliers-48f14a60" data-aos="fade-up-left">
                        <span>{{ __('frontend.more_info') }}</span>
                    </a>
                </div>
                <div class="col-md-6 pt-3 pt-lg-5 ms-auto" data-aos="fade-up-left">
                    <div class="text-center mx-auto">
                        <img src="{{ asset('home_design/images/supplier_image_4.png') }}" alt="blitznet home about" class="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row my-lg-5 my-3">
            <div class="col-md-6 p-lg-5 py-lg-5 py-3" data-aos="fade-up-right">
                <h2 class="color-primary">{!! __('frontend.change_daily_work') !!}</h2>
                <p class="my-3 fw-bold">{{ __('frontend.change_daily_work_prg') }}</p>
                <ul class="mb-lg-5 mb-3 buyer_list m-0 p-0">
                    <li>{{ __('frontend.change_daily_work_prg_li_1') }}</li>
                    <li>{{ __('frontend.change_daily_work_prg_li_2') }}</li>
                    <li>{{ __('frontend.change_daily_work_prg_li_3') }}</li>
                </ul>
                <div class="col-md-12 d-flex">
                    <a href="{{ $calendlyMeetingLink }}" class="btn btn-primary px-3 suppliers-48f14c0e" data-aos="fade-up-left">
                        <span>{{ __('frontend.schedule_callback') }}</span>
                    </a>
                </div>

            </div>
            <div class="col-md-6 p-lg-5 py-lg-5 py-3 pe-lg-0 ms-auto" data-aos="flip-left">
                <img src="{{ asset('home_design/images/buyer_image_5.jpg') }}" alt="Image" class="">
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row my-lg-5 my-3">
            <div class="col-md-12 text-center">
                <h3 class="color-primary" data-aos="fade-up-right">{{ __('frontend.how_does_work_heading') }}</h3>
                <h4 data-aos="fade-up-left">{{ __('frontend.blitznet_support_chaine') }}</h4>
            </div>
            <section class="pattern container my-lg-5 my-3">
                <div class="row align-items-center justify-content-lg-center">
                    <div class="col-lg-5 ms-lg-auto col-2 order-lg-2 " data-aos="flip-up">
                        <img src="{{ asset('home_design/images/buyer_timeline_01.png') }}" alt="1">
                    </div>
                    <div class="col-lg-5 col-8 order-lg-1 text-end">
                        <div class="timelinecontent" data-aos="fade-up-left">
                            <div class="d-flex align-items-center justify-content-lg-end">
                                <span>1</span>
                                <div class="header-title">{{ __('frontend.receive_rfq') }}</div>
                            </div>
                            <div class="description">{!! __('frontend.receive_rfq_prg') !!}</div>
                        </div>
                    </div>
                </div>
                <!-- 1end -->
                <div class="row align-items-center justify-content-lg-center">
                    <div class="col-lg-5 col-2 text-end" data-aos="flip-up">
                        <img src="{{ asset('home_design/images/buyer_timeline_02.png') }}" alt="1">
                    </div>
                    <div class="col-lg-5 ms-lg-auto col-8">
                        <div class="timelinecontent" data-aos="fade-up-right">
                            <div class="d-flex align-items-center">
                                <div class="header-title">{{ __('frontend.receive_purchage_ord') }}</div>
                                <span>2</span>
                            </div>
                            <div class="description">{!! __('frontend.receive_purchage_ord_prg') !!}</div>
                        </div>
                    </div>
                </div>
                <!-- 2end -->
                <div class="row align-items-center justify-content-lg-center">
                    <div class="col-lg-5 ms-lg-auto col-2 order-lg-2" data-aos="flip-up">
                        <img src="{{ asset('home_design/images/buyer_timeline_03.png') }}" alt="1">
                    </div>
                    <div class="col-lg-5 col-8 order-lg-1 text-end">
                        <div class="timelinecontent" data-aos="fade-up-left">
                            <div class="d-flex align-items-center justify-content-lg-end">
                                <span>3</span>
                                <div class="header-title">{{ __('frontend.payment_notification') }}</div>
                            </div>
                            <div class="description">{!! __('frontend.payment_notification_prg') !!}</div>
                        </div>
                    </div>
                </div>
                <!-- 3end -->
                <div class="row align-items-center justify-content-lg-center">
                    <div class="col-lg-5 col-2 text-end" data-aos="flip-up">
                        <img src="{{ asset('home_design/images/buyer_timeline_04.png') }}" alt="1">
                    </div>
                    <div class="col-lg-5 ms-lg-auto col-8 ">
                        <div class="timelinecontent" data-aos="fade-up-right">
                            <div class="d-flex align-items-center">
                                <div class="header-title">{{ __('frontend.logistics') }}</div>
                                <span>4</span>
                            </div>
                            <div class="description">{!! __('frontend.logistics_prg') !!}</div>
                        </div>
                    </div>
                </div>
                <!-- 4end -->
                <div class="row align-items-center justify-content-lg-center">
                    <div class="col-lg-5 ms-lg-auto col-2 order-lg-2" data-aos="flip-up">
                        <img src="{{ asset('home_design/images/buyer_timeline_05.png') }}" alt="1">
                    </div>
                    <div class="col-lg-5 col-8 order-lg-1 text-end">
                        <div class="timelinecontent" data-aos="fade-up-left">
                            <div class="d-flex align-items-center justify-content-lg-end">
                                <span>5</span>
                                <div class="header-title">{{ __('frontend.receive_for_payment') }}</div>
                            </div>
                            <div class="description">{{ __('frontend.receive_for_payment_prg') }}</div>
                        </div>
                    </div>
                </div>
                <!-- 5end -->
                <div class="row align-items-center justify-content-lg-center">
                    <div class="col-lg-5 col-2 text-end" data-aos="flip-up">
                        <img src="{{ asset('home_design/images/buyer_timeline_06.png') }}" alt="1">
                    </div>
                    <div class="col-lg-5 ms-lg-auto col-8 ">
                        <div class="timelinecontent" data-aos="fade-up-right">
                            <div class="d-flex align-items-center">
                                <div class="header-title  ">{{ __('frontend.repeat') }}</div>
                                <span>6</span>
                            </div>
                            <div class="description">{{ __('frontend.repeat_prg') }}</div>
                        </div>
                    </div>
                </div>
                <!-- 6end -->
            </section>

            <div class="text-center">
                <a href="{{ $calendlyMeetingLink }}" class="btn btn-primary bannerbtn ms-lg-5 mb-1 suppliers-48f14dda" data-aos="fade-up-left">
                    <span>{{ __('frontend.schedule_callback') }}</span>
                </a>
            </div>
        </div>
    </div>
    <script src="{{ asset('home_design/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('home_design/js/aos.js') }}"></script>
    <script src="{{ asset('home_design/js/script.js') }}"></script>
@endsection

