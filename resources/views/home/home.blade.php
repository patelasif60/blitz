@extends('home/homeLayout')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row align-items-center">
                <div class="col-lg-6 d-flex align-items-center justify-content-center py-3 p-md-5 text-justify"
                    data-aos="fade-right">
                    <div>
                        <h1>{{__('home.welcome_head')}}</h1>
                        <p>{{__('home.welcome_para1')}}</p>
                        <p>{{__('home.welcome_para1_1')}}</p>
                        <p>{{__('home.welcome_para2')}}</p>
                        <p>{{__('home.welcome_para3_1')}}
                            <span class="fw-bold text-primary">blitznet</span> {{__('home.welcome_para3_2')}}</p>
                        <p>{{__('home.welcome_para4')}} <span
                                class="fw-bold text-primary">blitznet</span>.</p>
                    </div>
                </div>
                <div class="col-lg-6 px-0" data-aos="fade-left">
                    <div class="ratio ratio-1x1">
                        <video autoplay="" loop="" muted="muted" id="homevideo" title="Blitznet">
                            <source src="https://www.blitznet.co.id/front-assets/images/front/video_n.mp4" type="video/mp4">
                            {{-- <source src="{{ URL::asset('front-assets/images/front/video_n.mp4') }}" type="video/mp4"> --}}
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end section 1 -->
    <div class="row justify-content-center" id="aboutsection">
        <div class="col-md-12">
            <div class="row align-items-center">
                <div class="col-lg-6 px-0" data-aos="fade-up-right">
                    <div class="ratio ratio-1x1">
                        <img src="{{ URL::asset('front-assets/images/front/img_1.jpg') }}" alt="img">
                    </div>
                </div>
                <div class="col-lg-6 d-flex align-items-center justify-content-center py-3 p-md-5 text-justify"
                    data-aos="fade-up-left">
                    <div>
                        <h2 class="h1">{{__('home.b2b_head')}}</h2>
                        <p><span class="fw-bold text-primary">blitznet</span> {{__('home.b2b_para1')}}</p>
                        <p>{{__('home.b2b_para2_1')}}</p>
						<p>{{__('home.b2b_para2_2')}} <span
                                class="fw-bold text-primary">blitznet</span> {{__('home.b2b_para2_3')}}</p>
                        <p>{{__('home.b2b_para3')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end section 2 -->
    <div class="row home_offer_section justify-content-center" id="offer_section">
        <div class="col-lg-11 px-5">
            <h2 class="text-center h1 text-white mb-5" data-aos="zoom-in">{{__('home.our_offer_heading')}}</h2>
            <div class="row mt-md-3">
                <div class="col-lg col-md-4 py-3">
                    <div class="flip-card d-flex h-100">
                        <div class="flip-card-inner">
                            <div class="flip-card-front">
                                <div class="card text-center h-100 border-0" data-aos="flip-left">
                                    <div class="py-5 px-4">
                                        <img src="{{ URL::asset('front-assets/images/front/offer_icon_2.png') }}"
                                            class="w-100" alt="offer_icon_2">
                                    </div>
                                    <div class="card-footer mt-auto d-flex align-items-center justify-content-center">
                                        <p class="card-text">{{__('home.offer1_head')}}</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flip-card-back bg-primary d-flex h-100 p-4 align-items-center text-center justify-content-center">
                                <p>{{__('home.offer1_para')}} </p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg col-md-4 py-3">
                    <div class="flip-card d-flex h-100">
                        <div class="flip-card-inner">
                            <div class="flip-card-front">
                                <div class="card text-center  h-100 border-0" data-aos="flip-left">
                                    <div class="py-5 px-4">
                                        <img src="{{ URL::asset('front-assets/images/front/offer_icon_4.png') }}"
                                            class="w-100" alt="offer_icon_4">
                                    </div>
                                    <div class="card-footer mt-auto d-flex align-items-center justify-content-center">
                                        <p class="card-text">{{__('home.offer2_head')}}</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flip-card-back bg-primary d-flex h-100 p-4 align-items-center text-center justify-content-center">
                                <p>{{__('home.offer2_para')}} </p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg col-md-4 py-3">
                    <div class="flip-card d-flex h-100">
                        <div class="flip-card-inner">
                            <div class="flip-card-front">
                                <div class="card text-center h-100 border-0" data-aos="flip-left">
                                    <div class="py-5 px-4">
                                        <img src="{{ URL::asset('front-assets/images/front/offer_icon_1.png') }}"
                                            class="w-100" alt="offer_icon_1">
                                    </div>
                                    <div class="card-footer mt-auto d-flex align-items-center justify-content-center">
                                        <p class="card-text">{{__('home.offer3_head')}}</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flip-card-back bg-primary d-flex h-100 p-4 align-items-center text-center justify-content-center">
                                <p>{{__('home.offer3_para')}}</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg col-md-6 py-3">
                    <div class="flip-card d-flex h-100">
                        <div class="flip-card-inner">
                            <div class="flip-card-front">
                                <div class="card text-center h-100 border-0" data-aos="flip-left">
                                    <div class="py-5 px-4">
                                        <img src="{{ URL::asset('front-assets/images/front/offer_icon_3.png') }}"
                                            class="w-100" alt="offer_icon_3">
                                    </div>
                                    <div class="card-footer mt-auto d-flex align-items-center justify-content-center">
                                        <p class="card-text">{{__('home.offer4_head')}}</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flip-card-back bg-primary d-flex h-100 p-4 align-items-center text-center justify-content-center">
                                <p>{{__('home.offer4_para')}}</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg col-md-6 py-3">
                    <div class="flip-card d-flex h-100">
                        <div class="flip-card-inner">
                            <div class="flip-card-front">
                                <div class="card text-center h-100 border-0" data-aos="flip-left">
                                    <div class="py-5 px-4">
                                        <img src="{{ URL::asset('front-assets/images/front/offer_icon_5.png') }}"
                                            class="w-100" alt="offer_icon_5">
                                    </div>
                                    <div class="card-footer mt-auto d-flex align-items-center justify-content-center">
                                        <p class="card-text">{{__('home.offer5_head')}}</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flip-card-back bg-primary d-flex h-100 p-4 align-items-center text-center justify-content-center">
                                <p>{{__('home.offer5_para')}}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- offer section end -->
    <div class="row home_testimonial">
        <div class="col-lg-12 px-sm-5">
            <div class="container-xl position-relative">
                <div class="row">
                    <div class="col-xl-4 home_testimonial_head" data-aos="fade-right">
                        <h2 class="h1">{{__('home.testimonial_head')}}</h2>
                        <p>{{__('home.testimonial_para')}}</p>
                    </div>
                    <div class="col-12" data-aos="zoom-in-up">
                        <div id="carouselCaptions_test" class="carousel slide" data-bs-ride="carousel">

                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <div class="pb-5 animated pulse">
                                        <img src="{{ URL::asset('front-assets/images/front/testimonial_1.jpg') }}"
                                            class="d-block w-100" alt="...">
                                    </div>
                                    <div class="carousel-caption p-3 p-sm-5 animated bounce">
                                        <div>
                                            <h6>Head of Business Management: PT Generale Facility Solution</h6>
                                            <p class="text-black mb-0">{{__('home.testimonial_team_para1')}}</p>
												<p class="text-end text-black mb-0 fw-bold">- Adinda Prishandani</p>
                                        </div>
                                    </div>
                                </div>
								<div class="carousel-item">
									<div class="pb-5 animated pulse">
										<img src="{{ URL::asset('front-assets/images/front/testimonial_2.jpg') }}" class="d-block w-100" alt="...">
									</div>
									<div class="carousel-caption p-3 p-sm-5 animated bounce">
										<div class="">
											<h6>Chief Strategic Officer: PT Fajar Benua Indopak</h6>
											<p class="text-black mb-0">{{__('home.testimonial_team_para2')}}</p>
											<p class="text-end text-black mb-0 fw-bold">- Pande Kadek Yuda Bakti</p>
										</div>
									</div>
								</div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselCaptions_test"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselCaptions_test"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- testimonial section end -->
    <div class="row justify-content-center">
        <div class="col-md-11 px-5 home_process_section">
            <h2 class="text-center h1 pb-5 text-purple" data-aos="zoom-in">{{__('home.our_process')}}</h2>

            <div class="row g-0 pt-4">
                <div class="col-md-4">
                    <div class="row g-0">
                        <div class="col-6" data-aos="zoom-out-up"><img
                                src="{{ URL::asset('front-assets/images/front/icon_process_1.png') }}"
                                class="w-100 animated bounce_n" alt="">
                            <h5 class="text-center p-3">{{__('home.process1')}}</h5>
                        </div>
                        <div class="col-6" data-aos="zoom-out-up"><img
                                src="{{ URL::asset('front-assets/images/front/icon_process_2.png') }}"
                                class="w-100 animated bounce_n" alt="">
                            <h5 class="text-center p-3">{{__('home.process2')}}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row g-0">
                        <div class="col-6" data-aos="zoom-out-up"><img
                                src="{{ URL::asset('front-assets/images/front/icon_process_3.png') }}"
                                class="w-100 animated bounce_n" alt="">
                            <h5 class="text-center p-3">{{__('home.process3')}}</h5>
                        </div>
                        <div class="col-6" data-aos="zoom-out-up"><img
                                src="{{ URL::asset('front-assets/images/front/icon_process_4.png') }}"
                                class="w-100 animated bounce_n" alt="">
                            <h5 class="text-center p-3">{{__('home.process4')}}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row g-0">
                        <div class="col-6" data-aos="zoom-out-up"><img
                                src="{{ URL::asset('front-assets/images/front/icon_process_5.png') }}"
                                class="w-100 animated bounce_n" alt="">
                            <h5 class="text-center p-3">{{__('home.process5')}}</h5>
                        </div>
                        <div class="col-6" data-aos="zoom-out-up"><img
                                src="{{ URL::asset('front-assets/images/front/icon_process_6.png') }}"
                                class="w-100 animated bounce_n" alt="">
                            <h5 class="text-center p-3">{{__('home.process6')}}</h5>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <!-- Process section end -->
    <div id="home_subscribe" class="row" data-aos="fade-up">
        <div class="col-md-12 px-5 home_subscribe_section " id="home_subscribe_section">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h3 class="text-center pb-5 text-white" data-aos="zoom-in">{{__('home.subscribe_head')}}</h3>
                </div>
                <div class="col-lg-6" data-aos="fade-up">
                    <form data-parsley-validate autocomplete="off" name="home_subsribe_form" id="home_subsribe_form">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <input type="text" class="form-control form-control-lg" id="" placeholder="{{__('home.firstname')}}"
                                    name="firstname" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <input type="text" class="form-control form-control-lg" id="" placeholder="{{__('home.lastname')}}"
                                    name="lastname" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <input type="text" class="form-control form-control-lg" id="" placeholder="{{__('home.company')}}"
                                    name="company" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <input type="email" class="form-control form-control-lg" name="email" id=""
                                    placeholder="{{__('home.email')}}" required>
                            </div>
                            <div class="col-md-12">
                                <div class="row" id="userTypeRow">
                                    <div class="col-auto">
                                        <div class="mb-3 form-check ">
                                            <input type="checkbox" class="form-check-input" value="1" name="is_buyer"
                                                id="is_buyer">
                                            <label class="form-check-label text-white pr-4" for="is_buyer">{{__('home.buyer')}}</label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="mb-3 form-check ">
                                            <input type="checkbox" class="form-check-input" value="1" name="is_supplier"
                                                id="is_supplier">
                                            <label class="form-check-label text-white pr-4"
                                                for="is_supplier">{{__('home.supplier')}}</label>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary py-2 w-100">{{__('home.submit')}}</button>
										<p id="successMsg" class="mt-1  text-white text-center " style="border-radius: 16px; display:none"><small> {{__('home.subscribe_success')}}</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Subscribe section end -->

    <div class="row">
        <div class="col-md-12 px-5 dark_blue home_counter_section">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h3 class="text-center pb-5 text-white" data-aos="flip-up">{{__('home.target_section_head')}}
                    </h3>
                </div>
                <div id="counter-stats" data-aos="fade-up" data-wow-duration="1.4s">
                    <div class="container-fluid">
                        <div class="row text-center justify-content-center">
                            <div class=" col-6 col-md-2 stats stats-1 mb-3">
                                <img src="{{ URL::asset('front-assets/images/front/counter_icon_1.png') }}" alt="">
                                <div class="counting pt-4 pb-3" data-count="8000">0</div>
                                <h5><sup>*</sup>{{__('home.RFQ')}}</h5>
                            </div>

                            <div class="col-6 col-md-2 stats stats-2 mb-3">
                                <img src="{{ URL::asset('front-assets/images/front/counter_icon_2.png') }}" alt="">
                                <div class="counting pt-4 pb-3" data-count="100">0</div>
                                <h5><sup>*</sup>{{__('home.suppliers')}}</h5>
                            </div>

                            <div class="col-6 col-md-2 stats stats-3 mb-3">
                                <img src="{{ URL::asset('front-assets/images/front/counter_icon_3.png') }}" alt="">
                                <div class="counting pt-4 pb-3" data-count="50000">0</div>
                                <h5><sup>*</sup>{{__('home.MSME')}}</h5>
                            </div>

                            <div class="col-6 col-md-2 stats stats-4 mb-3">
                                <img src="{{ URL::asset('front-assets/images/front/counter_icon_4.png') }}" alt="">
                                <div class="counting pt-4 pb-3" data-count="500">0</div>
                                <h5><sup>*</sup>{{__('home.products')}}</h5>
                            </div>
                            <div class="col-6 col-md-2 stats stats-5 mb-3">
                                <img src="{{ URL::asset('front-assets/images/front/counter_icon_5.png') }}" alt="">
                                <div class="counting pt-4 pb-3" data-count="5000">0</div>
                                <h5><sup>*</sup>{{__('home.orders')}}</h5>
                            </div>


                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
    <!-- counter end -->

    <script>
        $(document).ready(function() {
                                $("#successMsg").hide();
            $('#home_subsribe_form').submit(function(e) {
                e.preventDefault();
                $('#home_subsribe_form .userTypeError').remove();
                if ($('#home_subsribe_form').parsley().isValid()) {
                    var is_buyer = $('#home_subsribe_form #is_buyer').prop("checked");
                    var is_supplier = $('#home_subsribe_form #is_supplier').prop("checked");
                    if (is_buyer || is_supplier) {
                        var formData = new FormData($("#home_subsribe_form")[0]);
                        $.ajax({
                            url: "{{ route('add-subsribe-user-ajax') }}",
                            data: formData,
                            type: "POST",
                            contentType: false,
                            processData: false,
                            success: function(successData) {
                                $("#successMsg").show();
                                $("#home_subsribe_form")[0].reset();

                            },
                            error: function() {
                                $("#successMsg").hide();
                                console.log("error");
                            },
                        });
                    } else {

                        $('#home_subsribe_form #userTypeRow').append(
                            '<p class="userTypeError">please select option</p>');
                    }

                }
            });
        });
    </script>
@stop
