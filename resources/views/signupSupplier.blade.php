<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/front-assets/images/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('/front-assets/images/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/front-assets/images/favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/front-assets/images/favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/front-assets/images/favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/front-assets/images/favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('/front-assets/images/favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/front-assets/images/favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/front-assets/images/favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('/front-assets/images/favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/front-assets/images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('/front-assets/images/favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/front-assets/images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href=" {{ asset('/front-assets/images/favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content=" {{ asset('/front-assets/images/favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="LQGIwhow1K1Z3vgBmXwMA3M7K0rgmp23LwKyuYMw">
    <title>blitznet</title>
    <!-- base:css -->
    <link rel="stylesheet" href="{{ URL::asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/vendors/base/vendor.bundle.base.css') }}">
    <script src="{{ asset('front-assets/js/front/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ URL::asset('front-assets/js/front/select2.full.min.js') }}"></script>

    <!-- CSS only -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ URL::asset('assets/css/vertical-layout-light/style.css') }}">
    <link href="{{ asset('/front-assets/css/front/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href=" {{ asset('/front-assets/intlTelInput/css/intlTelInput.css') }}">
    <link rel="stylesheet" href=" {{ asset('/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link href="{{ URL::asset('front-assets/css/front/style.css') }}" rel="stylesheet">
    <!-- endinject -->
    <link rel="icon" type="image/png" href="{{ URL::asset('home_design/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ URL::asset('front-assets/intlTelInput/css/intlTelInput.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('/new_design/css/Login_register_css.css') }}">
</head>

@php
    if(Auth::user()) {
        $langVal = session()->get('locale');
    } else {
        $langVal = session()->get('localelogin');
    }
@endphp

<!--sections starts here-->
<input type="hidden" name="langValue" id="langValue" value="{{ $langVal }}">

<body>
    <!-- Modal -->
    <section>
        <!--registration section-->
        <div class="registration-sec container-fluid">
            <div class="registration-blk row" style="min-height: 100vh;">
                <div class="supplier-registration-img position-relative col-md-6 px-0 d-none d-md-flex">
                    <div class="ratio ratio-16x9"> </div>
                    <a href="{{ route('home') }}"><img src="{{ asset('new_design/images/blitznet_logo.png') }}" class="brand_logo_reg"></a>
                </div>

                <div class="registration-form col-md-6 align-items-center justify-content-center new_page_reg">
                    <div class="btn-group home_lenguage p-2 ms-auto position-absolute  mt-2" style="right: 0px;">
                        <a type="button" class="dropdown-toggle fw-bold text-dark text-decoration-none text-uppercase" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('/new_design/images/Language_img.svg') }}" height="28px" width="28px" alt="Translate" srcset="">
                            <span>{{App::getLocale()}}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown_new border-0" style="min-width: inherit; ">
                            <li >
                                <a class="dropdown-item d-flex align-items-center" href="{{url('lang/id')}}">
                                    <img class="me-2" src="{{ asset('/new_design/images/indonesia.png') }}" alt="" height="14px" srcset="">
                                    ID
                                </a>
                            </li>
                            <li >
                                <a class="dropdown-item d-flex align-items-center" href="{{url('lang/en')}}">
                                    <img class="me-2" src="{{ asset('/new_design/images/English.png') }}" alt="" height="14px" srcset="">
                                    EN
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="row align-items-center justify-content-center new_page_reg h-100">
                        <div class="col-md-9 d-flex flex-column align-items-center justify-content-center">
                            <div class="w-100 px-md-4  px-2 suppside">
                                <div class="mb-xl-3 mb-2 mt-5">
                                    <div class="reg-above_txt">{{ __('signup.supplier_register') }}</div>
                                </div>
                                <form method="POST" id="SupplierRegisterForm" action="{{ route('add-supplier') }}" data-parsley-validate>
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 col-lg-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label">{{ __('signup.first_name') }}<span class="text-danger">*</span></label>
                                                <div class="d-flex">
                                                    <select class="form-select reg_salute border-end-0" id="salutation" name="salutation" style="">
                                                        <option value="1">{{__('admin.salutation_mr')}}</option>
                                                        <option value="2">{{__('admin.salutation_ms')}}</option>
                                                        <option value="3">{{__('admin.salutation_mrs')}}</option>
                                                    </select>
                                                    <input type="text"  data-parsley-errors-container="#firstnameError" required data-parsley-required-message="{{ __('frontFormValidationMsg.firstName') }}" class="form-control border-start-0 input-alpha" id="firstname" name="firstname">
                                                    <div id="firstnameError" class="col-md-12 mb-3 js-validation text-danger"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label"> {{ __('signup.last_name') }} <span class="text-danger">*</span></label>
                                                <input data-parsley-errors-container="#lastnameError" required data-parsley-required-message="{{ __('frontFormValidationMsg.lastName') }}"  type="text" class="form-control input-alpha"  id="lastname" name="lastname">
                                                <div id="lastnameError" class="col-md-12 mb-3 js-validation text-danger"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 form-group mb-3 position-relative">
                                        <label class="form-label">{{ __('signup.company_name') }}<span class="text-danger">*</span></label>
                                        <input type="text" style="padding: 0.375rem 0.75rem 0.375rem 3.5rem;" data-parsley-errors-container="#companyNameError" required data-parsley-required-message="{{ __('frontFormValidationMsg.companyName') }}" class="form-control" name="companyName" id="companyName">
                                        <span id="companyNameError" class="text-danger"></span>
                                        <img class="input_img" src="{{ asset('/new_design/images/company_logo.png') }}" alt="" srcset="">
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label class="form-label">{{ __('signup.mobile_no') }}<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control border-start-0" name="mobile" id="mobile" data-parsley-errors-container="#mobileError" required data-parsley-length="[9, 16]" data-parsley-checkmobile data-parsley-checkmobile-message="mobile number already exits"data-parsley-required-message="{{ __('frontFormValidationMsg.mobile') }}" data-parsley-length-message="{{ __('profile.required_phone_number_error') }}" data-parsley-type="digits" data-parsley-type-message="{{ __('frontFormValidationMsg.numeric') }}" >
                                            <div id="mobileError" class="text-danger"></div>
                                    </div>
                                    <div class="col-md-12 form-group mb-3 position-relative">
                                        <label class="form-label">{{ __('signup.email') }}<span class="text-danger">*</span></label>
                                        @if(isset($_GET['email']))
                                            <input type="email" id="email" data-parsley-errors-container="#emailError" required data-parsley-required-message="{{ __('frontFormValidationMsg.email') }}" data-parsley-checkemail data-parsley-checkemail-message="{{ __('frontFormValidationMsg.emailunique')}}" class="form-control" style="padding: 0.375rem 0.75rem 0.375rem 3.5rem;background: #f2f2f2" name="email" value="{{ Crypt::decryptString($_GET['email']) }}" readonly>
                                            <span id="emailError" class="text-danger"></span>
                                            <input type="token" id="contact_person_token" class="form-control" name="token" hidden value="{{ $_GET['token'] }}">
                                        @else
                                            <input type="email" class="form-control form-control-lg" data-parsley-errors-container="#emailError" required data-parsley-required-message="{{ __('frontFormValidationMsg.email') }}" data-parsley-checkemail data-parsley-checkemail-message="{{ __('frontFormValidationMsg.emailunique')}}" id="email" name="email" style="padding: 0.375rem 0.75rem 0.375rem 3.5rem;">
                                            <span id="emailError" class="text-danger"></span>
                                            <input type="token" id="contact_person_token" class="form-control" name="token" hidden value="">
                                            <input type="token" id="register_supplier_flag" class="form-control" name="register_supplier_flag" hidden value="1">
                                        @endif
                                        <img class="input_img" src="{{ asset('/new_design/images/email.png') }}" alt="" srcset="">
                                    </div>
                                    <div class="col-md-12 form-group mb-4">
                                        <label class="form-label">{{ __('signup.Password') }}<span class="text-danger">*</span></label>
                                        <input data-parsley-errors-container="#passwordError" required data-parsley-required-message="{{ __('frontFormValidationMsg.password') }}"  type="password" style="padding: 0.375rem 3.5rem;"  class="form-control" name="password" id="password">
                                        <span id="passwordError" class="text-danger"></span>
                                        <img class="input_img" src="{{ asset('/new_design/images/password_lock.png') }}">
                                        <img class="input_img_2" id="togglePassword" data-src="{{ asset('/new_design/images/password_eye_close.svg') }}" src="{{ asset('/new_design/images/password_eye.svg') }}">
                                    </div>

                                    <div class="mb-3 ">
                                        <button type="button" id="userRegister" class="btn btn-primary reg_btn">{{ __('signup.supplier_Register') }}</button>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12">
                                        <a href="javascript:void(0)" class="registrationVideo d-flex align-items-center text-decoration-none" data-video-en="https://www.youtube.com/embed/fPzR5Gd_qiY" data-video-id="https://www.youtube.com/embed/-JDV8AJIUzk" data-video-title="{{ __('dashboard.how_to_register') }}" data-toggle="modal" data-target="#registrationVideoModal">
                                            <img class="me-2" src="{{ asset('/new_design/images/play-button.png') }}" height="38px" alt="" srcset="">
                                            <span class="video_txt"> {{ __('signup.how_to_register') }} </span>
                                        </a>
                                    </div>
                                    <div class="col-lg-6 col-md-12 d-flex align-items-center justify-content-lg-end">
                                        <span class="login_txt text-nowrap me-2 {{ Route::current()->getName() == 'signin' ? 'active' : '' }}">{{ __('signup.already_account') }}</span>
                                        <a href="{{ route('admin-login-form') }}" class="d-flex text-nowrap align-items-center text-decoration-none">
                                            <span class="video_txt">{{ __('signup.Login') }}</span>
                                        </a>
                                    </div>
                                    <div class=" d-flex justify-content-center mb-2"  style="margin-top: 50px;">
                                        <a href="{{ route('signup') }}" class="d-flex text-nowrap  align-items-center text-decoration-none">
                                            <span class="video_txt">{{ __('signup.buyer_register') }}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
      <!-- Video Modal -->
    <div class="modal fade" id="registrationVideoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-xl">
          <div class="modal-content border-0">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalToggleLabel"></h5>
                  <button type="button" class="btn-close" id="stopVideoNow" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body" id="showVideoHere">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
          </div>
      </div>
    </div>
   <!-- Modal -->
    <div class="modal fade" id="supplierRegSuccessModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-radius">
                <div class="modal-body ">
                    <div class="text-center">
                        <div class="success-icon">
                            <div class="success-icon__tip"></div>
                            <div class="success-icon__long"></div>
                        </div>
                        <h4 class="text-success">{{ __('signup.Success') }} !</h4>
                        <h6>{{ __('signup.registerSuccessTitle') }}</h6>

                        @if (isset($_GET['email']))
                            <p>{{ __('signup.activate_successfully') }}</p>
                        @else
                            <p>{{ __('signup.registerSuccessMessage') }}</p>
                        @endif

                        <div class="text-center">
                            <a href="{{ route('admin-login-form') }}" class="btn btn-primary"> {{ __('signup.Continue') }} </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Otpmodal" tabindex="-1" aria-labelledby="OtpmodalLabel"  data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center p-2 rounded-0 pop-up-header">
                    <img src="{{ asset('/new_design/images/Modal_logo.svg') }}" class="ms-2" height="32px" alt="">
                    <h5 class="modal-title ms-auto me-auto text-uppercase text-white" id="exampleModalLabel">{{__('validation.verifyotp') }}</h5>
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
<!-- / End -->

<!--section ends here-->
<script src="{{ asset('/front-assets/js/front/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/front-assets/js/parsley.min.js') }}"></script>
<script src="{{ asset('/front-assets/intlTelInput/js/intlTelInput.js') }}"></script>
<script src="{{ asset('/assets/vendors/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/custom/app.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ URL::asset('assets/js/custom/otp.js') }}"></script>

<script>
    var input = document.querySelector("#mobile");
    var iti = window.intlTelInput(input, {
        initialCountry:"id",
        separateDialCode:true,
        dropdownContainer:null,
        preferredCountries:["id"],
        hiddenInput:"phone_code"
    });

    $("#mobile").focusin(function(){
        let countryData = iti.getSelectedCountryData();
        $('input[name="phone_code"]').val(countryData.dialCode);
    });

    $(document).ready(function() {
        $('input[name="phone_code"]').val('62');
        window.Parsley.addValidator('uniqueemail', {
            validateString: function (value) {
                let res = false;
                xhr = $.ajax({
                    url: '{{ route('check-supplier-registration-email-exist') }}',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    dataType: 'json',
                    method: 'POST',
                    data: {
                        email: value,
                    },
                    async: false,
                    success: function(data) {
                        res = data;
                    },
                });
                //console.log(res);
                return res;
            },
            messages: {
                en: 'This email already exists!'
            },
            priority: 32
        });
    });

    // input only number in mobile number filds validation.
    function isNumberKey(txt, evt) {
        // remove error message from input fields/
        errorRemove(txt);

        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode == 46) {
            //Check if the text already contains the . character
            if (txt.value.indexOf('.') === -1) {
                return true;
            } else {
                return false;
            }
        } else {
            if (charCode > 31 &&
                (charCode < 48 || charCode > 57))
                return false;
        }
        return true;
    }

     //Show Registration Video Popup
    $(document).on("click", ".registrationVideo", function() {
        let videoLink = ($("#langValue").val() == "en" ? $(this).attr("data-video-en") : $(this).attr("data-video-id"));
        let videoTitle = $(this).attr("data-video-title");
        let video = '<div class="embed-responsive embed-responsive-16by9">\
            <iframe id="buyerSignUpYoutube" class="embed-responsive-item" width="100%" height="500" src="' +
            videoLink + '" allowfullscreen></iframe>\
        </div>';

        $("#exampleModalToggleLabel").text(videoTitle);
        $('#showVideoHere').html(video);
        $("#registrationVideoModal").modal('show');
    });
    $(document).on("click", "#stopVideoNow", function() {
        $('#buyerSignUpYoutube').attr('src', '');
    });

    // Password Eye icon functionality.
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

    // // remove error message after add input value
    errorRemove = (obj) => {
        if($('#' + obj.id).val() != '') {
            $('#'+obj.id+'Error').html('');
        }
    }
    // submit form
    $("#userRegister").click(function(xhr) {
        if ($('#SupplierRegisterForm').parsley().validate()) {
            $('#userRegister').prop('disabled', true);
            OtpApp.sendotp();
            //Otp.varifyOtp(); //To run on local env without checking otp
        }
    });

    var Otp = function(){
        return {
            init:function(){
               // frontMenuRedirection()
            },
            varifyOtp:function(){
                var formData = new FormData($('#SupplierRegisterForm')[0]);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'))
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    url:$('#SupplierRegisterForm').attr('action'),
                    data: formData,
                    success:function(successData) {
                        $(".js-validation").html('');
                        if (successData) {
                                $('#Otpmodal').modal('hide');
                                $("#SupplierRegisterForm")[0].reset();
                                $('input[name="phone_code"]').val('62');
                                $('#supplierRegSuccessModal').modal('show');
                            }else {
                                $('#message').html(
                                    'Something went wrong, please try later.'
                                ).css('color', 'red');
                            }
                            $("#userRegister").removeClass('d-none');
                            $('#timer-sign').addClass('d-none');
                            $('#userRegister').attr('disabled', false);
                    },
                    error:function(xhr){
                        if (xhr.status == 422){
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                $('#'+key+'Error').html('');
                                $('#'+key+'Error').append('<small class="text-danger">'+value+'</small>');
                            });
                        }
                    }
                }, "json");
            }
        }
    }(1);
    jQuery(document).ready(function(){
        Otp.init()
    });
</script>
</body>
</html>
