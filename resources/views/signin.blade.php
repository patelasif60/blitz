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
            <div class="registration-sec container-fluid">
                <div class="registration-blk row">
                    <div class="registration-img position-relative col-md-6 px-0 d-none d-md-flex">
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
                        <div class="row align-items-center justify-content-center vh-100 new_page_reg">
                            <div class="col-md-9 d-flex flex-column align-items-center justify-content-center">
                                <div class="w-100 px-md-4 px-2">
                                    <div class="mb-xxl-3 mb-3 mt-5">
                                        <div class="reg-above_txt">
                                            {!! __('signup.signin_main_title') !!}
                                        </div>
                                    </div>
                                    <div class="success">
                                        @if (Session::get('success'))
                                            <p> {{ Session::get('success') }}</p>
                                        @endif
                                    </div>
                                    <div id="passwordLogin">
                                        <form autocomplete="off" id="userLoginForm" action="{{ route('login') }}" method="POST">
                                            @csrf
                                            <div class="col-md-12 form-group mb-3 position-relative">
                                                <label class="form-label">{{ __('signup.email') }}<span class="text-danger">*</span></label>
                                                <input type="text" novalidate style="padding: 0.375rem 0.75rem 0.375rem 3.5rem;" onkeypress="errorRemove(this)" id="email" class="form-control" name="email">
                                                <span id="emailError" class="js-validation text-danger"></span>
                                                <img class="input_img" src="{{ asset('/new_design/images/email.png') }}">
                                            </div>
                                            <div class="col-md-12 form-group mb-3">
                                                <label class="form-label">{{ __('signup.Password') }}<span class="text-danger">*</span></label>
                                                <input type="password" style="padding: 0.375rem 3.5rem;" onkeypress="errorRemove(this)" class="form-control" name="password" id="password">
                                                <span id="passwordError" class="js-validation text-danger"></span>
                                                <img class="input_img" src="{{ asset('/new_design/images/password_lock.png') }}">
                                                <img class="input_img_2" id="togglePassword" data-src="{{ asset('/new_design/images/password_eye_close.svg') }}" src="{{ asset('/new_design/images/password_eye.svg') }}">
                                            </div>
                                            <div id="loginMessage" style="text-align: left;"></div>
                                            <div class="error ps-1" id="error ps-1">
                                                @if (Session::get('status'))
                                                    <p> {{ Session::get('status') }}</p>
                                                @endif
                                            </div>
                                            <div class="forgot-password d-flex my-xxl-4 my-2">
                                                <a href="javascript:void(0)" class="forgot" id="Mobiletext">{{ __('signup.login_mobile') }}</a>
                                                <a href="{{ route('forget-password-get') }}" class="ms-auto forgot">{{ __('signup.forget_password') }} ?</a>
                                            </div>
                                            <div class="mb-3">
                                                <button type="submit" name="userLogin" id="buyerLoginInBtnId" class="btn btn-primary reg_btn">
                                                    {{ __('home.Login') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div id="OtpLogin" class="hidden">
                                        <form autocomplete="off" action="{{route('loginwithotp')}}" name="mobileLoginform" id="mobileLoginform" method="POST" class="h-100" data-parsley-validate>
                                            @csrf
                                            <input type="hidden" name="frmType" id="frmType" value="frontend">
                                            <div class="col-md-12 form-group mb-3">
                                                <label class="form-label">{{ __('profile.mobile_number') }}
                                                    <span style="color:red">*</span>
                                                </label>
                                                <input type="text" class="form-control" id="mobile" name="mobile" required data-parsley-type="digits" data-parsley-required-message="{{ __('frontFormValidationMsg.mobile') }}" data-parsley-length="[9, 16]" data-parsley-length-message="{{ __('profile.required_phone_number_error') }}" data-parsley-type="digits" data-parsley-type-message="{{ __('frontFormValidationMsg.numeric') }}" data-parsley-checkmobilenotexist data-parsley-checkmobilenotexist-message="{{ __('frontFormValidationMsg.checkmobilenotexist') }}">
                                                <div id="mobileError" class="col-md-12 mb-3 js-val text-danger"></div>
                                            </div>
                                            <div class="forgot-password d-flex my-xxl-4 my-2">
                                                <a href="javascript:void(0)" class="forgot" id="Emailtext">{{ __('signup.login_email') }}</a>
                                            </div>
                                            <div class="mb-3">
                                                <button type="button" id="userRegister" class="btn btn-primary reg_btn" role="button">{{ __('signup.verify') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                        
                                    <div class="row">
                                        <div class="col-md-12 d-md-flex align-items-center justify-content-center">
                                            <span class="login_txt  me-2">{{__('signup.dont_account')}}</span>
                                            <a href="{{ route('signup') }}" class="text-decoration-none">
                                            <span class="video_txt">{{__('signup.create_account')}}</span></a>
                                        </div>
                                    </div>
                                    <div class="row mt-3 mb-3">
                                        <div class="col-md-12 d-flex align-items-center justify-content-center">
                                            <span class="login_with">{{__('signup.or_login_with')}}</span>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-12 d-flex align-items-center justify-content-center">
                                            <a href="{{ url('signin/facebook') }}" class="d-flex mx-3 align-items-center text-decoration-none">
                                            <img src="{{ asset('/new_design/images/facebook.svg') }}" alt=""></a>
                                            <a href="{{ url('signin/google') }}" class="d-flex mx-3 align-items-center text-decoration-none">
                                            <img src="{{ asset('/new_design/images/google.svg') }}" alt=""></a>
                                            <a href="{{ url('signin/linkedin') }}" class="d-flex mx-3 align-items-center text-decoration-none">
                                            <img src="{{ asset('/new_design/images/linkedin.png') }}" height="32px" alt=""></a>
                                        </div>
                                    </div>
                                    <div class=" d-flex justify-content-center mt-3 mt-lg-5" >
                                        <a href="{{ route('admin-login-form') }}"
                                            class="d-flex text-nowrap  align-items-center text-decoration-none">
                                        <span class="video_txt">{{__('signup.supplier_login')}}</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="modal fade" id="Otpmodal" tabindex="-1" aria-labelledby="OtpmodalLabel"  data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center p-2 rounded-0 pop-up-header">
                        <img src="{{ asset('/new_design/images/Modal_logo.svg') }}" class="ms-2" height="32px" alt="">
                        <h5 class="modal-title ms-auto me-auto text-uppercase " id="exampleModalLabel">{{__('validation.verifyotp') }}</h5>
                        <a href="#" type="button" class="px-2" data-bs-dismiss="modal" aria-label="Close">
                            <img src="{{ asset('/new_design/images/cancel.png') }}" alt="">
                        </a>
                    </div>
                    <div class="modal-body text-center py-5" id="otpBody">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary rounded-1" id="subForm">{{ __('profile.submit.title') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
<!--section ends here-->
<script src="{{ URL::asset('front-assets/js/front/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/parsley.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/intlTelInput/js/intlTelInput.js') }}"></script>
<script>
</script>
<script src="{{ URL::asset('/assets/vendors/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/custom/otp.js') }}"></script>
<script>
    // Password eye functionality.
    $(document).ready(function(){
        $("#togglePassword").click(function(){
            var d1 = $("#togglePassword").attr('data-src');
            var d2 = $("#togglePassword").attr('src');
            $("#togglePassword").attr('data-src', d2);
            $(this).attr('src', d1);
            var type = $("#password").attr('type');
            $("#password").attr('type', type === "password" ? "text" : "password");
        });
    });


    // show form custom validation message.
    $(document).ready(function() {
        $("#buyerLoginInBtnId").click(function(e) {
            e.preventDefault();
            var formData = new FormData($('#userLoginForm')[0]);
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
                    $("#userLoginForm").submit();

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
    });

    // remove error message after add input value
    errorRemove = (obj) => {
        if($('#' + obj.id).val() != '') {
            $('#'+obj.id+'Error').html('');
        }
    }
    //input country code
    var input4 = document.querySelector("#mobile");
    var iti4 = window.intlTelInput(input4, {
        initialCountry:"id",
        separateDialCode:true,
        dropdownContainer:null,
        preferredCountries:["id"],
        hiddenInput:"phone_code"
    });
    $("#mobile").focusin(function(){
        let countryData = iti4.getSelectedCountryData();
        $('input[name="phone_code"]').val(countryData.dialCode);
    });

    $(document).ready(function() {
        $("#userRegister").click(function(xhr) {
            if ($('#mobileLoginform').parsley().validate()) {
                $('#userRegister').prop('disabled', true);
                OtpApp.sendotp();
            }
        });
    });

    var Otp = function(){
         mobiletext = function(){
                $("#Mobiletext").click(function () {
                    $("#OtpLogin").removeClass("hidden")
                    $("#passwordLogin").addClass("hidden")
                });
            },
            emailtext = function(){
                $("#Emailtext").click(function () {
                    $("#OtpLogin").addClass("hidden")
                    $("#passwordLogin").removeClass("hidden")
                })
            },
            otpBtn = function(){
               $("#OtpBtn").click(function () {
                    $("#mobile_number").prop("disabled", true)
                })
            };
        return {
            init:function(){
                mobiletext(),
                emailtext(),
                otpBtn();
            },
            varifyOtp:function(){
                $("#mobileLoginform").submit();
            },
        }
    }(1);

    jQuery(document).ready(function(){
        Otp.init()
    });
</script>
</html>