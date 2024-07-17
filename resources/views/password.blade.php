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

    </style>
    <body>
        <section>
            <div class="registration-sec container-fluid">
                <div class="registration-blk row">
                    <div class="{{$userData->role_id == 2 ? 'registration-img' : 'supplier-Login-Image' }}   position-relative col-md-6 px-0 d-none d-md-flex">
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
                                            @if($userData->role_id == 2)
                                            <form autocomplete="off" id="LoginForm" action="{{ route('login') }}" method="POST">
                                            @else
                                            <form autocomplete="off" id="LoginForm" method="POST" action="{{ route('admin-login') }}">
                                            @endif

                                                @csrf
                                                <input type="hidden" name="email" value="{{$userData->email}}">
                                                <input type="hidden" name="frmtypo" value="multiple_type">
                                                <input type="hidden" name="id" value="{{$encyId}}">
                                                <div class="card border-0">
                                                    <div class="card-title mb-0">
                                                        <div class="mb-xxl-3 mb-3 mt-5">
                                                            <div class="reg-above_txt">{{ __('signup.verify_account') }} </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body ps-0">
                                                        <div class="col-md-12 my-2">
                                                            <a href="#" class="main_box text-decoration-none">
                                                                <h4 class="text-dark mb-1">Hi {{$userData->firstname}} {{$userData->lastname}}</h4>
                                                                <p class="text-muted mb-1" style="font-size: 16px;">
                                                                    {{$userData->email}}</p>
                                                            </a>
                                                        </div>
                                                        <div class="col-md-12 form-group mb-3">
                                                            <label class="form-label">Password<span class="text-danger">*</span></label>
                                                            <input type="password" class="form-control" name="password" id="password" style="padding: 0.375rem 3.5rem;">
                                                            <span id="passwordError" class="js-validation text-danger"></span>
                                                            <img class="input_img" src="{{ asset('/new_design/images/password_lock.png') }}">
                                                            <img class="input_img_2 me-2" id="togglePassword" data-src="{{ asset('/new_design/images/password_eye_close.svg') }}" src="{{ asset('/new_design/images/password_eye.svg') }}">
                                                        </div>
                                                        <div class="error ps-1 my-2" id="error ps-1">
                                                            @if (Session::get('status'))
                                                                <p> {{ Session::get('status') }}</p>
                                                            @endif
                                                        </div>                                              
                                                        <div class="forgot-password d-flex my-xxl-4 my-2">
                                                            @if($userData->role_id == 2)
                                                                <a href="{{route('getbuyeremails')}}" class="forgot">{{ __('signup.choose_account') }}</a>
                                                                <a href="{{route('forget-password-get')}}" class="ms-auto forgot">{{ __('signup.forget_password') }} ?</a>
                                                            @else
                                                                <a href="{{route('getemails')}}" class="forgot">{{ __('signup.choose_account') }}</a>
                                                                <a href="{{route('admin-forget-password-get')}}" class="ms-auto forgot">{{ __('signup.forget_password') }} ?</a>
                                                            @endif
                                                        </div>
                                                        <div class="mb-3">
                                                            <button type="submit" name="userLogin" id="buyerLoginInBtnId" class="btn btn-primary reg_btn">
                                                                {{ __('signup.verify') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
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
        <script type="text/javascript">
            var Loginemail = function(){
                togglePassword = function(){
                    $("#togglePassword").click(function(){
                        var d1 = $("#togglePassword").attr('data-src');
                        var d2 = $("#togglePassword").attr('src');
                        $("#togglePassword").attr('data-src', d2);
                        $(this).attr('src', d1);
                        var type = $("#password").attr('type');
                        $("#password").attr('type', type === "password" ? "text" : "password");
                    });
                },
                frmsubmit = function(){
                   $("#buyerLoginInBtnId").click(function(e) {
                        e.preventDefault();
                        var formData = new FormData($('#LoginForm')[0]);
                        $("#buyerLoginInBtnId").prop('disabled', true);
                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            contentType: false,
                            processData: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: '{{ route('login') }}',
                            data: formData,
                            success:function(successData) {
                                $(".js-validation").html('');
                                $("#LoginForm").submit();

                            },
                            error:function(xhr){
                                if (xhr.status == 422){
                                    $.each(xhr.responseJSON.errors, function(key,value) {
                                        $('#'+key+'Error').html('');
                                        $('#'+key+'Error').append('<small class="text-danger">'+value+'</small>');
                                    });
                                }
                                $("#buyerLoginInBtnId").prop('disabled', false);
                            }
                        }, "json");
                    });
                },
                errorRemove = function(){
                    $("#password").keypress(function() {
                        $('#passwordError').html('');
                    });
                };
                return {
                    init:function(){
                        togglePassword(),
                        frmsubmit(),
                        errorRemove();
                    }
                }
            }(1);
            jQuery(document).ready(function(){
                Loginemail.init()
            });
        </script>
    </body>
</html>
