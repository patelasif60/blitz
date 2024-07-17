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
    <title>blitznet</title>

    <script src="{{ URL::asset('front-assets/js/front/jquery-3.6.0.min.js') }}"></script>
    <link href="{{ URL::asset('front-assets/css/front/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/signin.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/blitznet.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('front-assets/intlTelInput/css/intlTelInput.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/new_design/css/Login_register_css.css') }}">
</head>
 <!--sections starts here-->
    @php
        if(Auth::user()) {
        $langVal = session()->get('locale');
        } else {
        $langVal = session()->get('localelogin');
        }
    @endphp
    <input type="hidden" name="langValue" id="langValue" value="{{ $langVal }}">
<body>
<section>
    <!--registration section-->
    <div class="registration-sec container-fluid">
        <div class="registration-blk row">
            <div class="registration-img position-relative col-md-6 px-0 d-none d-md-flex">
                <div class="ratio ratio-16x9">
                </div>
                <a href="{{ route('home') }}">
                    <img src="{{ asset('/new_design/images/blitznet_logo.png') }}" class="brand_logo_reg" alt="" srcset="">
                </a>
            </div>
            <div class="registration-form col-md-6 align-items-center justify-content-center new_page_reg">
                <div class="btn-group home_lenguage p-2 ms-auto position-absolute mt-2" style="right: 0;">
                    <a type="button" class="dropdown-toggle fw-bold text-dark text-decoration-none text-uppercase" style=" margin-top: 5px;" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('/new_design/images/Language_img.svg') }}" height="28px" width="28px" alt="Translate" srcset="">
                        <span>{{App::getLocale()}}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown_new border-0" style="min-width: inherit;">
                        <li>
                            <a class="dropdown-item d-flex align-items-center pe-1" href="{{url('lang/id')}}">
                                <img class="me-2" src="{{ asset('/new_design/images/indonesia.png') }}" alt="" height="14px" srcset="">
                                ID
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center pe-1" href="{{url('lang/en')}}">
                                <img class="me-2" src="{{ asset('/new_design/images/English.png') }}" alt="" height="14px" srcset="">
                                EN
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="row align-items-center justify-content-center vh-100 new_page_reg">
                    <div class="col-md-9 d-flex align-items-center justify-content-center flex-column">
                        <div class="w-100 px-md-4 px-2">
                            <div class="mb-xxl-3 mb-3 mt-5">
                                <div class="reg-above_txt text-start" style="font-size: 1.5rem;"> {{ __('signup.forget_password') }} </div>
                            </div>

                            <form autocomplete="off" id="userLoginForm" method="POST">
                                @csrf
                                <div class="col-md-12 form-group mb-3 position-relative">
                                    <label class="form-label"> {{ __('signup.email') }} <span class="text-danger">*</span></label>
                                    @if(isset($user))
                                        <input type="email" style="padding: 0.375rem 0.75rem 0.375rem 3.5rem;" onkeypress="errorRemove(this)" required id="email" class="form-control" value="{{ $user->email }}" name="email">
                                        <span id="emailError" class="js-validation text-danger"></span>
                                    @else
                                        <input type="email" style="padding: 0.375rem 0.75rem 0.375rem 3.5rem;" onkeypress="errorRemove(this)" required id="email" class="form-control" name="email">
                                        <span id="emailError" class="js-validation text-danger"></span>
                                    @endif
                                    <img class="input_img" src="{{ asset('/new_design/images/email.png') }}">
                                </div>
                                <div class="error ps-1">
                                    @if (Session::get('status'))
                                        <p> {{ Session::get('status') }}</p>
                                    @endif
                                </div>
                                <div class="error ps-1">
                                    @if ($errors->has('email'))
                                        <p> {{ __('signup.email_error') }} </p>
                                    @endif
                                </div>

                                <div class="forgot-password d-flex my-xxl-4 my-2">
                                    <a href="{{ route('signin') }}" class="ms-auto forgot"> {{ __('signup.back_to_login') }} </a>
                                </div>

                                <div class="mb-3 ">
                                    <button type="button" id="addCompanyDetailsBtn" name="userLogin" class="btn btn-primary reg_btn">{{ __('home.submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--registration  section  ends-->
</section>
</body>
<script src="{{ URL::asset('front-assets/js/front/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/parsley.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/intlTelInput/js/intlTelInput.js') }}"></script>
<script>
    // show form custom validation message.
    $(document).ready(function() {
        $("#addCompanyDetailsBtn").click(function(e) {
            e.preventDefault();
            var formData = new FormData($('#userLoginForm')[0]);
            $.ajax({
                type: "POST",
                dataType: 'json',
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('forget-password-post') }}',
                data: formData,
                success:function(successData) {
                    // $(".js-validation").html('');
                    $("#userLoginForm").submit();
                },
                error:function(e){
                    if (e.status == 422){
                        $.each(e.responseJSON.errors, function(key,value) {
                            $('#'+key+'Error').html('');
                            $('#'+key+'Error').append('<small class="text-danger">'+value+'</small>');
                        });
                    }
                }
            });
        });
    });

    // remove error message after add input value
    errorRemove = (obj) => {
        if($('#' + obj.id).val() != '') {
            $('#'+obj.id+'Error').html('');
        }
    }
</script>
</html>
