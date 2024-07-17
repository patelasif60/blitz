@extends('home/homeLayout2')

@section('content')
    <!-- banner start -->
    <div id="carouselCaptions" class="carousel slide bannersection" data-bs-ride="carousel" data-aos="fade-down">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ URL::asset('home_design/images/blitznet-web-about-banner.jpg') }}" class="d-block w-100" alt="blitznet about">
                <div class="carousel-caption d-flex align-items-center flex-column w-100" style="left: 0;">
                    <div class="my-auto" data-aos="fade-up">
                        <h2 class="h1">#SWPO</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- banner end -->

    <div class="container-xl">
        <div class="row my-3 my-lg-5">
            <div class="col-md-9 col-lg-6 home_first_content  py-3 py-lg-5" data-aos="fade-up">
                <h1 class="color-primary mb-lg-5">{{__('home_latest.hard_work_pays_off')}}</h1>
                <p>{{__('home_latest.support_the_local_economy')}}</p>
                <h5 class="mt-lg-5"><i>{{__('home_latest.stronger_together')}}</i></h5>
            </div>
        </div>
    </div>

<!-- section end -->
    <div class="container-xl">
        <div class="row my-3 my-lg-5 align-items-center justify-content-center">
            <div class="col-md-11 py-3 py-lg-5" data-aos="fade-up">
                <div class="row text-center">
                    <div class="col-md-4">
                        <img src="{{ URL::asset('home_design/images/icons/jj.svg') }}" alt="Juan José Caldera" class="mw-100">
                        <h2 class="h1 color-primary my-3">{!!__('home_latest.juan_jose_caldera')!!}</h2>
                            <p>{{__('home_latest.ceo_supply_chain')}}</p>
                            <p><a href="https://www.linkedin.com/in/juan-jose-caldera-barboza/" target="_blank" class="text-dark">{{__('home_latest.more')}} _</a></p>
                    </div>
                    <div class="col-md-4">
                        <img src="{{ URL::asset('home_design/images/icons/jj-1.svg') }}" alt="Barboza" class="mw-100">
                        <h2 class="h1 color-primary my-3">{!!__('home_latest.akshesh_panchal')!!} </h2>
                            <p>{{__('home_latest.ceo_tech_development')}}</p>
                                <p><a href="https://www.linkedin.com/in/akshesh-panchal-9903605/" target="_blank" class="text-dark">{{__('home_latest.more')}} _</a></p>
                    </div>
                    <div class="col-md-4">
                        <img src="{{ URL::asset('home_design/images/icons/pande.svg') }}" alt="Pande Yuda Bakti Kadek" class="mw-100">
                        <h2 class="h1 color-primary my-3">{!!__('home_latest.pande')!!}</h2>
                            <p>{{__('home_latest.cso_commissioner')}} </p>
                                <p><a href="https://www.linkedin.com/in/pandekyb108/" target="_blank" class="text-dark">{{__('home_latest.more')}} _</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
