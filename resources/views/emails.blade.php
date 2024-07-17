<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="apple-touch-icon" sizes="57x57" href="{{ URL::asset('front-assets/images/favicon/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ URL::asset('front-assets/images/favicon/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ URL::asset('front-assets/images/favicon/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ URL::asset('front-assets/images/favicon/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ URL::asset('front-assets/images/favicon/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ URL::asset('front-assets/images/favicon/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ URL::asset('front-assets/images/favicon/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ URL::asset('front-assets/images/favicon/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ URL::asset('front-assets/images/favicon/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ URL::asset('front-assets/images/favicon/android-icon-192x192.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ URL::asset('front-assets/images/favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ URL::asset('front-assets/images/favicon/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('front-assets/images/favicon/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ URL::asset('front-assets/images/favicon/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ URL::asset('front-assets/images/favicon/ms-icon-144x144.png') }}">
        <meta name="theme-color" content="#ffffff">
        <meta name="csrf-token" content="{!! csrf_token() !!}">
        <title>blitznet</title>
        <script src="{{ URL::asset('front-assets/js/front/jquery-3.6.0.min.js') }}"></script>
        <!-- CSS only -->
        <link href="{{ URL::asset('front-assets/css/front/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/css/front/signin.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/css/front/style.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/css/front/blitznet.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ URL::asset('front-assets/intlTelInput/css/intlTelInput.css')}}">
        <link rel="stylesheet" href="{{ URL::asset('/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('/new_design/css/Login_register_css.css') }}">
        <script src="{{ URL::asset('front-assets/js/front/select2.full.min.js') }}"></script>

    </head>
    @php
        if(Auth::user()) {
        $langVal = session()->get('locale');
        } else {
        $langVal = session()->get('localelogin');
        }
    @endphp
    <input type="hidden" name="langValue" id="langValue" value="{{ $langVal }}">
    <style type="text/css">
        .email-count{
            position: absolute;
            bottom: 0px;
            right: 20px;
        }

        .multiple_email .border-light{
            border-color: #e1e6fa !important;
        }
        .emailboxes:hover{ background-color: #f5f5f5; }

    </style>
    <body>
        <section>
            <div class="registration-sec container-fluid">
                <div class="registration-blk row">
                    <div class="{{$usertype == 2 ? 'registration-img' : 'supplier-Login-Image' }}   position-relative col-md-6 px-0 d-none d-md-flex">
                        <div class="ratio ratio-16x9"> </div>
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('/new_design/images/blitznet_logo.png') }}" class="brand_logo_reg" alt="" srcset="">
                        </a>
                    </div>
                    <div class="registration-form col-md-6 align-items-center justify-content-center new_page_reg">
                        <div class="btn-group home_lenguage p-2 ms-auto position-absolute  mt-2">
                            <a type="button" class="dropdown-toggle fw-bold text-dark text-decoration-none text-uppercase" style=" margin-top: 5px;" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ asset('/new_design/images/Language_img.svg') }}" height="28px" width="28px" alt="Translate" srcset="">
                                <span>{{App::getLocale()}}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown_new border-0 " style="min-width: inherit; ">
                                <li >
                                    <a class="dropdown-item d-flex align-items-center pe-1" href="{{url('lang/id')}}">
                                        <img class="me-2" src="{{ asset('/new_design/images/indonesia.png') }}" alt="" height="14px" srcset="">
                                        ID
                                    </a>
                                </li>
                                <li >
                                    <a class="dropdown-item d-flex align-items-center pe-1" href="{{url('lang/en')}}">
                                        <img class="me-2" src="{{ asset('/new_design/images/English.png') }}" alt="" height="14px" srcset="">
                                        EN
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="row align-items-center justify-content-center vh-100 new_page_reg ">
                            <div class="col-md-9 d-flex flex-column align-items-center justify-content-center">
                                <div class="w-100 px-md-4 px-2">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card border-0">
                                                <div class="card-title mb-0">
                                                    <div class="mb-xxl-3 mb-3 mt-5">
                                                        <div class="reg-above_txt">{{ __('signup.choose_account') }} </div>
                                                        <p class="mb-0 mt-2 fs-6">{{ __('signup.found_multiple') }} <span class="fw-bold fst-italic">Email's</span> {{ __('signup.registered_current_mobile_number') }}.</p>
                                                    </div>
                                                </div>
                                                <div class="card-body ps-0 multiple_email" >
                                                    <div class="row my-2 position-relative" style="max-height: 45vh; overflow: auto;">
                                                        @foreach($userData as $val)
                                                        <div class="col-md-12 ">
                                                            <a href="{{route('chooseemail', ['id' => Crypt::encrypt($val->id)])}}" class="main_box border text-decoration-none border-light p-2 d-block emailboxes">
                                                                <h6 class="text-dark mb-0">{{$val->firstname}}  {{$val->lastname}}</h6>
                                                                <p class="text-muted mb-1">{{$val->email}}</p>
                                                            </a>
                                                        </div>
                                                        @endforeach                                                
                                                    </div>
                                                </div>
                                                <div class="col-md-12 email-count">
                                                    <p class="mb-0 text-end text-dark fw-bold"> <span class="">{{count($userData)}} Email {{ __('signup.found') }}</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="{{ URL::asset('front-assets/js/front/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>