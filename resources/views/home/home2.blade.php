@extends('home/homeLayout2')
@section('content')
    <!-- banner start -->
    <div id="carouselCaptions" class="carousel slide bannersection aos-init aos-animate" data-bs-ride="carousel" data-aos="fade-down">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('/home_design/images/home_Main-banner.jpg') }}" class="d-block w-100" alt="Blitznet Home">
                <div class="carousel-caption d-flex align-items-center flex-column">
                    <div class="my-auto aos-init aos-animate text-start">
                        <h1 data-aos="flip-up">{{ __('frontend.connecting_supply_chain') }}</h1>
                        <p data-aos="flip-down">{{ __('frontend.b2b_plt') }}</p>
                        <a href="{{ $calendlyMeetingLink }}" class="btn btn-primary px-3 px-md-5 home-48f1038e" data-aos="fade-up-right">
                            <span>{{ __('frontend.schedule_callback') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- banner end -->
    <div class="container">
        <div class="row justify-content-center mt-3 mt-lg-5">
            <div class="col-md-12">
                <h3 data-aos="fade-up-right">{!! __('frontend.company_growing') !!}</h3>
            </div>
            <div class="col-md-9 pt-3 pt-lg-5" data-aos="fade-up">
                <div class="text-center mx-auto">
                    <img src="{{ asset('home_design/images/home_image_1.png') }}" alt="blitznet home about" class="">
                </div>
            </div>
            <div class="col-md-3 text-justify cornerefects" data-aos="fade-up-left">
                <p class="text_color">{{ __('frontend.home_prg_1') }}</p>
                <p class="text_color">{{ __('frontend.home_prg_2') }}</p>
            </div>
        </div>
    </div>
    <!-- section end -->
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 pe-lg-5 py-lg-5 py-3" data-aos="fade-up-right">
                <h2 class="color-primary">{!! __('frontend.found_pro_solu') !!}</h2>
                <p class="my-3 my-lg-4 text-justify">{{ __('frontend.home_prg_3') }}</p>
                <p class="mb-lg-4 mb-3">{{ __('frontend.home_prg_4') }}</p>
                <div class="col-md-12">
                    <a href="{{ $calendlyMeetingLink }}" class="btn btn-primary px-3 f-14 mb-1 home-48f11202" data-aos="fade-up-left">
                        <span>{{ __('frontend.schedule_callback') }}</span></a>
                    <a href="{{ route('signup') }}" class="btn btn-primary px-3 f-14 mb-1 home-48f116ee " data-aos="fade-up-right">
                        <span>{{ __('frontend.join_buyer') }}</span></a>
                    <a href="{{ route('signup-supplier') }}" class="btn btn-primary px-3 f-14 mb-1 home-48f114d2" data-aos="fade-up-right">
                        <span>{{ __('frontend.join_seller') }}</span></a>
                </div>
            </div>
            <div class="col-md-6 p-lg-5 py-lg-5 py-3 pe-lg-0 ms-auto" data-aos="fade-up-left">
                <img src="{{ asset('home_design/images/home_image_2.png') }}" alt="Image">
            </div>
        </div>
    </div>
    <!-- section end -->
    </div>
    <!-- section end -->
    <div class="container text-center mb-3 product_home_img">
        <div class="row my-lg-5">
            <div class="col-md-12 mb-3 mb-lg-5">
                <h3 data-aos="fade-up-right">{{ __('frontend.home_h3') }}</h3>
            </div>
            <div class="row owl-carousel owl-one">
                        <div class=" centercont_img mb-3 mb-lg-5">
                            <img src="{{ asset('home_design/images/icons/category_icon_1.jpg') }}" alt="Image" class="mb-2 mb-lg-3" data-aos="flip-up">
                            <p class="color-primary">{{ __('frontend.steel') }}</p>
                        </div>
                        <div class=" centercont_img mb-3 mb-lg-5">
                            <img src="{{ asset('home_design/images/icons/category_icon_2.jpg') }}" alt="Image" class="mb-2 mb-lg-3" data-aos="flip-up">
                            <p class="color-primary">{{ __('frontend.wood') }}</p>
                        </div>
                        <div class=" centercont_img mb-3 mb-lg-5">
                            <img src="{{ asset('home_design/images/icons/category_icon_3.jpg') }}" alt="Image" class="mb-2 mb-lg-3" data-aos="flip-up">
                            <p class="color-primary">{{ __('frontend.plastic') }}</p>
                        </div>
                        <div class=" centercont_img mb-3 mb-lg-5">
                            <img src="{{ asset('home_design/images/icons/category_icon_4.jpg') }}" alt="Image" class="mb-2 mb-lg-3" data-aos="flip-up">
                            <p class="color-primary">{{ __('frontend.fabric') }}</p>
                        </div>
                        <div class=" centercont_img mb-3 mb-lg-5">
                            <img src="{{ asset('home_design/images/icons/category_icon_5.jpg') }}" alt="Image" class="mb-2 mb-lg-3" data-aos="flip-up">
                            <p class="color-primary">{{ __('frontend.agriculture') }}</p>
                        </div>
                        <div class=" centercont_img mb-3 mb-lg-5">
                            <img src="{{ asset('home_design/images/icons/category_icon_6.jpg') }}" alt="Image" class="mb-2 mb-lg-3" data-aos="flip-up">
                            <p class="color-primary">{{ __('frontend.chemicals') }}</p>
                        </div>
                        <div class=" centercont_img mb-3 mb-lg-5">
                                <img src="{{ asset('home_design/images/icons/category_icon_7.jpg') }}" alt="Image" class="mb-2 mb-lg-3" data-aos="flip-up">
                                <p class="color-primary">{{ __('frontend.farming') }}</p>
                        </div>
                        <div class=" centercont_img mb-3 mb-lg-5">
                                    <img src="{{ asset('home_design/images/icons/category_icon_8.jpg') }}" alt="Image" class="mb-2 mb-lg-3" data-aos="flip-up">
                                    <p class="color-primary">{{ __('frontend.polymers') }}</p>
                         </div>
                        <div class=" centercont_img mb-3 mb-lg-5">
                                    <img src="{{ asset('home_design/images/icons/category_icon_9.jpg') }}" alt="Image" class="mb-2 mb-lg-3" data-aos="flip-up">
                                    <p class="color-primary">{{ __('frontend.rubber') }}</p>
                         </div>
                                <div class=" centercont_img mb-3 mb-lg-5">
                                    <img src="{{ asset('home_design/images/icons/category_icon_10.jpg') }}" alt="Image" class="mb-2 mb-lg-3" data-aos="flip-up">
                                    <p class="color-primary">{{ __('frontend.fishery') }}</p>
                                </div>
                                <div class=" centercont_img mb-3 mb-lg-5">
                                    <img src="{{ asset('home_design/images/icons/category_icon_11.jpg') }}" alt="Image" class="mb-2 mb-lg-3" data-aos="flip-up">
                                    <p class="color-primary">{{ __('frontend.yarn') }}</p>
                                </div>
                                <div class=" centercont_img mb-3 mb-lg-5">
                                    <img src="{{ asset('home_design/images/icons/category_icon_12.jpg') }}" alt="Image" class="mb-2 mb-lg-3" data-aos="flip-up">
                                    <p class="color-primary">{{ __('frontend.pulp_paper') }}</p>
                                </div>
            </div>
        </div>
    </div>
    <!-- section end -->
    <div class="container ">
        <div class="row py-3 py-lg-5">
            <div class="col-md-6 px-5 mb-5" data-aos="fade-up-right">
                <div class="card d-flex shadow border-0 rounded grey_bg cardfooterbtn">
                    <div class="position-relative">
                        <img src="{{ asset('/home_design/images/home_image_6.png') }}" class="card-img-top" alt="Image">
                        <div class="card-img-overlay centercont_img text-center bg-black bg-opacity-25 rounded">
                            <h3 class="card-title text-white">{{ __('frontend.raw_mate_buyer') }}</h3>
                        </div>
                    </div>
                    <div class="card-body d-flex">
                        <p class="text-center px-lg-5 px-3 my-auto">{{ __('frontend.home_prg_5') }}</p>
                    </div>
                    <div class="card-footer border-top-0 text-center">
                    <a href="{{route('signup')}}" class="btn btn-primary home-48f1055a" data-aos="fade-up-left">
                            <span>{{ __('frontend.become_buyer') }}</span></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 px-5 mb-5" data-aos="fade-up-left">
                <div class="card d-flex h-100 shadow border-0 rounded grey_bg cardfooterbtn">
                    <div class="position-relative">
                        <img src="{{ asset('home_design/images/home_image_7.png') }}" class="card-img-top" alt="Image">
                        <div class="card-img-overlay centercont_img text-center bg-black bg-opacity-25 rounded">
                            <h3 class="card-title text-white">{{ __('frontend.raw_mate_supplier') }}</h3>
                        </div>
                    </div>
                    <div class="card-body d-flex">
                        <p class="text-center px-lg-5 px-3 my-auto">{{ __('frontend.home_prg_6') }}</p>
                    </div>
                    <div class="card-footer border-top-0 text-center  ">
                    <a href="{{route('signup-supplier')}}" class="btn btn-primary home-48f10fbe" data-aos="fade-up-left">
                            <span>{{ __('frontend.become_supplier') }}</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- section end -->

    <div class="container">
        <div class="row justify-content-center py-3 mb-lg-5 mb-3">
            <div class="col-md-12 mb-3 mb-lg-5 text-center">
                <h3 data-aos="fade-up-right">{{ __('frontend.manage_supplie_chain') }}</h3>
            </div>
            <div class="row">
                <div class="col-md" data-aos="fade-up-right">
                    <div class="centercont_img">
                        <img src="{{ asset('/home_design/images/Image_text_1.svg') }}" alt="Image" class="mb-2 mb-lg-3">
                    </div>
                    <p class="fw-bold">{{ __('frontend.eprocurement') }}</p>
                    <p clas="f-14">{{ __('frontend.home_prg_7') }}</p>
                </div>

                <div class="col-md" data-aos="fade-up-right">
                    <div class="centercont_img">
                        <img src="{{ asset('home_design/images/Image_text_2.svg') }}" alt="Image" class="mb-2 mb-lg-3">
                    </div>
                    <p class="fw-bold">{{ __('frontend.digital_bry_grp') }}</p>
                    <p clas="f-14">{{ __('frontend.home_prg_8') }}</p>
                </div>
                <div class="col-md" data-aos="fade-up">
                    <div class="centercont_img">
                        <img src="{{ asset('home_design/images/Image_text_3.svg') }}" alt="Image" class="mb-2 mb-lg-3">
                    </div>
                    <p class="fw-bold">{{ __('frontend.paymentFetch') }}</p>
                    <p clas="f-14">{{ __('frontend.home_prg_9') }}</p>
                </div>
                <div class="col-md" data-aos="fade-up-left">
                    <div class="centercont_img" >
                        <img src="{{ asset('home_design/images/Image_text_4.svg') }}" alt="Image" class="mb-2 mb-lg-3">
                    </div>
                    <p class="fw-bold">{{ __('frontend.supplieChainLogi') }}</p>
                    <p clas="f-14">{{ __('frontend.home_prg_10') }}</p>
                </div>
                <div class="col-md" data-aos="fade-up-left">
                    <div class="centercont_img">
                        <img src="{{ asset('home_design/images/Image_text_5.svg') }}" alt="Image" class="mb-2 mb-lg-3">
                    </div>
                    <p class="fw-bold">{{ __('frontend.trusted_network') }}</p>
                    <p clas="f-14">{{ __('frontend.home_prg_11') }}</p>
                </div>
            </div>

            <div class="col-md-12">
                @if (App::getLocale() == 'en')
                    <a href="{{ asset('BN_Company_Deck_2022_English.pdf') }}" class="btn new_btn1 text-dark btn-secondary ms-auto px-4 fw-bold home-48f11900" data-aos="fade-up-left" download>
                        <span>{{ __('frontend.download_deck') }}</span>
                    </a>
                @else
                    <a href="{{ asset('BN_Company_Deck_2022_ID.pdf') }}" class="btn new_btn1 text-dark btn-secondary ms-auto px-4 fw-bold home-48f11900" data-aos="fade-up-left" download>
                        <span>{{ __('frontend.download_deck') }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
    <!-- section end -->
    <section class="buyer_banner_section">
        <div class="my-auto aos-init aos-animate text-start d-flex justify-content-end" data-aos="fade-up">
            <div class="card cardfooterbtn bg-white bg-opacity-50 border-0">
                <div class="card-body py-lg-5 py-3 px-4">
                    <h2 class="mb-lg-4">{{ __('frontend.we_are_stronger') }}</h2>
                    <p class="color-primary fw-bold mb-lg-3 mb-2">{{ __('frontend.join_digi_buyer_grp') }}</p>
                    <p class="mb-0 f-14 text-justify">{!! __('frontend.home_prg_12') !!}</p>
                </div>
                <div class="card-footer border-top-0 px-4">
                    <a href="{{ route('group-trading') }}" class="btn btn-primary home-48f11ad6" data-aos="fade-up-left">
                        <span>{{ __('frontend.explore_digi_bry_grp') }}</span></a>
                </div>
            </div>
        </div>
    </section>

    <!-- section end -->
    <div class="container py-3 py-lg-5">
        <div class="row">
            <div class="col-md-12 mb-3 mb-lg-5" data-aos="fade-down-right">
                <h3>{{ __('frontend.plt_opport') }}</h3>
                <h4>{{ __('frontend.tech_change_way') }}</h4>
            </div>
            <div class="d-flex flex-column position-relative">
                <img src="{{ asset('home_design/images/home_image_18.png') }}" alt="Image" class="mb-2 mb-lg-3">
                <a href="{{ route('group-trading-front') }}" class="btn new_btn text-dark btn-secondary ms-auto px-4 home-48f11cc0" data-aos="fade-up-left">
                    <span>{{ __('frontend.learn_more') }}</span></a>
            </div>
        </div>
    </div>
    <!-- section end -->
    <div class="container-fluid bg-blue">
        <div class="container mobile_img">
            <div class="row my-3 my-lg-5 py-3 py-lg-5">
                <div class="col-md-4 centercont_img">
                    <h3 class="text-white fw-bold" data-aos="fade-down-right">{!! __('frontend.need_workspace') !!}</h3>
                </div>
                <div class="col-md-8 row">
                    <div class="col-md-4 centercont_img text-center">
                        <img src="{{ asset('home_design/images/home_image_15.png') }}" alt="Image" data-aos="flip-left" class="mb-2 mb-lg-3">
                        <p class="text-white fw-bold">{!! __('frontend.secure_tran_grted') !!}</p>
                    </div>
                    <div class="col-md-4 centercont_img text-center">
                        <img src="{{ asset('home_design/images/home_image_16.png') }}" alt="Image" data-aos="flip-left" class="mb-2 mb-lg-3">
                        <p class="text-white fw-bold">{!! __('frontend.financing_solutions') !!}</p>
                    </div>
                    <div class="col-md-4 centercont_img text-center">
                        <img src="{{ asset('home_design/images/home_image_17.png') }}" alt="Image" data-aos="flip-left" class="mb-2 mb-lg-3">
                        <p class="text-white fw-bold">{!! __('frontend.expert_logistics_partnerts') !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- section end -->
    <div class="container">
        <div class="row my-3 my-lg-5 py-3 py-lg-5 ">
            <div class="col-md-12 d-flex justify-content-center align-items-center mb-3">
                <h3 data-aos="fade-up-right" class="me-auto">{{ __('frontend.late_up_blog') }}</h3>
               {{-- <a name="" id="" class="ms-auto text-dark vmtext" href="https://www.blitznet.co.id/blog" role="button" data-aos="fade-up-left">
                    {{ __('frontend.view_more') }}
                    <img src="{{ asset('home_design/images/icons/home_arrow.svg') }}" alt="Image" class="ms-2">
                </a>--}}
            </div>
            <div class="col-md-4 mb-3 mb-lg-5">
                <div class="card h-100 shadow border-0 rounded" data-aos="fade-up-right">
                    <img src="{{ asset('home_design/images/home_image_12.png') }}" alt="Image">
                    <div class="card-body px-4">
                        <div>{{ __('frontend.business') }}</div>
                        <div class="fw-bold mb-2">{{ __('frontend.What_Is_eProcurement') }}</div>
                        <p>{{ __('frontend.simply_buy_selling') }}</p>
                            {{--<a href="https://www.blitznet.co.id/blog" class="stretched-link"></a>--}}
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-lg-5">
                <div class="card h-100  shadow border-0 rounded" data-aos="fade-up">
                    <img src="{{ asset('home_design/images/home_image_13.png') }}" alt="Image">
                    <div class="card-body px-4">
                        <div>{{ __('frontend.business') }}</div>
                        <div class="fw-bold mb-2">{{ __('frontend.what_is_supplie_chain') }}</div>
                        <p>{{ __('frontend.supplie_chain_prg') }}</p>
                        {{--<a href="https://www.blitznet.co.id/blog" class="stretched-link"></a>--}}
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-lg-5">
                <div class="card h-100 shadow border-0 rounded" data-aos="fade-up-left">
                    <img src="{{ asset('home_design/images/home_image_14.png') }}" alt="Image">
                    <div class="card-body px-4">
                        <div>{{ __('frontend.business') }}</div>
                        <div class="fw-bold mb-2">{{ __('frontend.practice_raw_mate') }}</div>
                        <p>{{ __('frontend.practice_raw_mate_prg') }}</p>
                        {{--<a href="https://www.blitznet.co.id/blog" class="stretched-link"></a>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- section end -->
    <script src="{{ asset('home_design/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('home_design/js/aos.js') }}"></script>
    <script src="{{ asset('home_design/js/script.js') }}"></script>
@endsection
