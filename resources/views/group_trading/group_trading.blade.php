@extends('home/homeLayout2')
@section('content')
    <!-- banner start -->
    <div id="carouselCaptions" class="carousel slide bannersection aos-init aos-animate" data-bs-ride="carousel" data-aos="fade-down">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('home_design/images/Group_trading_banner.png') }}" class="d-block w-100" alt="Blitznet Supplier">
                <div class="carousel-caption d-flex align-items-center flex-column l-50">
                    <div class="my-auto aos-init text-start aos-animate" data-aos="fade-up">
                        <h1 class="text-white aos-init aos-animate" data-aos="fade-up-right">{{ __('frontend.grp_trd_main_title') }}</h1>
                        <p class="text-white mt-lg-3 mb-1 aos-init aos-animate" data-aos="fade-up-left">{{ __('frontend.grp_trd_main_title_prg') }}</p>
                        <div class="mt-lg-5 d-md-flex align-items-center">
                            <a href="{{ route('signup') }}" class="btn btn-primary bannerbtn mb-1 aos-init buyers-group-48f14fd8" data-aos="fade-up-right">
                                <span>{{ __('frontend.join_now') }}</span>
                            </a>
                            <a href="{{ $calendlyMeetingLink }}" class="btn btn-primary bannerbtn ms-lg-4 mb-1 aos-init buyers-group-48f1519a" data-aos="fade-up-left">
                                <span>{{ __('frontend.schedule_callback') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- banner end -->

    <div class="container mt-lg-5 mt-3 mobile_img">
        <div class="row py-3 py-lg-5">
            <div class="col-md-6 px-md-5 my-5" data-aos="fade-up-right">
                <div class="card d-flex shadow border-0 rounded grey_bg position-relative h-100">
                    <div class="card-body text-center  px-lg-5 px-3">
                        <div class="Grp_bs_img mb-2">
                            <img src="{{ asset('home_design/images/Group_trading_image_4.svg') }}" alt="Image">
                        </div>
                        <div class="my-3 text-center d-flex justify-content-center w-100">
                            <div class="supplier_grp text-white rounded-pill">{{ __('frontend.supplier') }}</div>
                        </div>
                        <p>{{ __('frontend.grp_trd_sup_prg_1') }}</p>
                        <p>{{ __('frontend.grp_trd_sup_prg_2') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 px-5 my-5" data-aos="fade-up-left">
                <div class="card d-flex shadow border-0 rounded grey_bg position-relative h-100">
                    <div class="card-body  text-center px-lg-5 px-3">
                        <div class="Grp_bs_img mb-2">
                            <img src="{{ asset('home_design/images/Group_trading_image_3.svg') }}" alt="Image">
                        </div>
                        <div class="my-3 text-center d-flex justify-content-center w-100">
                            <div class="buyer_grp text-white rounded-pill">{{ __('frontend.buyer') }}</div>
                        </div>
                        <p>{{ __('frontend.grp_trd_byr_prg_1') }}</p>
                        <p>{{ __('frontend.grp_trd_byr_prg_2') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row py-5 buyer_bg  align-items-center justify-content-center">
            <div class="col-lg-8 col-md-8 col-sm-12 d-flex p-6">
                <div class="quoteefects w-100 centercont_img">
                    <h3 data-aos="fade-up-right">{!! __('frontend.grp_trd_mid_title') !!}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row my-lg-5 my-3">
            <div class="col-md-12 text-center">
                <h3 class="color-primary" data-aos="fade-up-right">{{ __('frontend.join_digi_byr_grp') }}</h3>
                <h4 data-aos="fade-up-left">{{ __('frontend.join_digi_byr_grp_prg') }}</h4>
            </div>
            <div class="col-md-12 d-flex align-items-center justify-content-center">
                <img src="{{ asset('home_design/images/Group_trading_image_6.png') }}" alt="Image" class="mw-100" data-aos="flip-up">
            </div>
            <div class="d-flex justify-content-center my-3">
                <a href="{{ route('group-trading') }}" class="btn btn-primary buyers-group-48f15848" data-aos="fade-up-left">
                    <span>{{ __('frontend.explore_digi_bry_grp') }}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row my-lg-5 my-3">
            <div class="col-md-12">
                <h3 class="color-primary" data-aos="fade-up-right">{{ __('frontend.frq_qus_ans') }}</h3>
            </div>
            <div class="accordion py-3" id="accordionExample" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="1000">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingone">
                        <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseone" aria-expanded="false" aria-controls="collapseTwo">
                            <h6 class="fw-semibold mb-0">{{ __('frontend.grp_trd_q_1') }}</h6>
                        </button>
                    </h2>
                    <div id="collapseone" class="accordion-collapse collapse" aria-labelledby="headingone" data-bs-parent="#accordionExample">
                        <div class="accordion-body">{{ __('frontend.grp_trd_a_1') }}</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h6 class="fw-semibold  mb-0">{{ __('frontend.grp_trd_q_2') }}</h6>
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                        <div class="accordion-body">{{ __('frontend.grp_trd_a_2') }}</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <h6 class="fw-semibold mb-0">{{ __('frontend.grp_trd_q_3') }}</h6>
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="according-body">
                            <div class="accordion-body">{{ __('frontend.grp_trd_a_3') }}</div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            <h6 class="fw-semibold  mb-0">{{ __('frontend.grp_trd_q_4') }}</h6>
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                        <div class="accordion-body">{{ __('frontend.grp_trd_a_4') }}</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFive">
                        <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            <h6 class="fw-semibold mb-0">{{ __('frontend.grp_trd_q_5') }}</h6>
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                        <div class="accordion-body">{{ __('frontend.grp_trd_a_5') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('home_design/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('home_design/js/aos.js') }}"></script>
    <script src="{{ asset('home_design/js/script.js') }}"></script>
@stop
@push('bottom_scripts')
@include('dashboard.payment.payment_tab_js')
@endpush
