@extends('buyer/layouts/backend/backend_single_layout')
@section('content')
    <div class="main_section position-relative profile_section">
        <div class="container py-3 Loanapplication">
            <div class="row">
                <div class="header_top koin d-flex align-items-center pb-5 px-3">
                    <h1 class="mb-0">{{__('dashboard.credit_limit')}}</h1>
                </div>
                <form id="limitOtpForm" class="digit-group" method="POST" enctype="multipart/form-data" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                    @csrf
                    <div class="col-md-12 mt-lg-3">
                        <div class="text-center">
                            <p class="mb-lg-5 mb-3 fw-bold" style=" font-size: 36px; color: #00AE5D;">{{__('profile.your_credit_limit_approved')}}</p>
                            <p class="my-lg-3 my-3 fw-bold" style=" font-size: 18px;">{{__('profile.security_code')}}</p>
                            <p class="my-lg-5 my-3 otp-sent" style=" margin-top: 0rem!important; font-size: 14px;" id="">{{__('profile.otp_sent_number')}} {{$businessNumber}}</p>
                            <div class="ap-otp-inputs" >
                                <input class="ap-otp-input input-number" type="text" name="digit-1" id="digit-1" pattern="[0-9]" maxlength="1" data-index="0" data-next="digit-2">
                                <input class="ap-otp-input input-number" type="text" name="digit-2" id="digit-2" pattern="[0-9]" maxlength="1" data-index="1" data-next="digit-3" data-previous="digit-1">
                                <input class="ap-otp-input input-number" type="text" name="digit-3" id="digit-3" pattern="[0-9]" maxlength="1" data-index="2" data-next="digit-4" data-previous="digit-2">
                                <input class="ap-otp-input input-number" type="text" name="digit-4" id="digit-4" pattern="[0-9]" maxlength="1" data-index="3" data-next="digit-5" data-previous="digit-3">
                                <input class="ap-otp-input input-number" type="text" name="digit-5" id="digit-5" pattern="[0-9]" maxlength="1" data-index="4" data-next="digit-6" data-previous="digit-4">
                                <input class="ap-otp-input input-number" type="text" name="digit-6" id="digit-6" pattern="[0-9]" maxlength="1" data-index="5" data-previous="digit-5">
                            </div>
                            <div class="my-lg-5 my-3">
                                <button id="limitOtpVerify" class="btn btn-primary bluecolornext text-white px-5 submitedOtp"  role="button"><img class="me-1" src="{{ URL::asset('front-assets/images/icons/pageend_submit.png') }}" alt="arrow" srcset=""> {{__('profile.verify')}} </button>
                            </div>
                            <div class="resend-otp-btn">
                                 {{ __('profile.didnt_get_otp') }}  &nbsp;<a id="limit_otp_resend" class="fw-bold text-decoration-none" style="color: #0178FF;" href="javascript:void(0);" role="">{{__('profile.resend_otp')}}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
    <script type="text/javascript">

        /****************************************begin: Credit OTP **************************/
        var SnippetCreditOtp = function () {

            var animateOtp = function () {
                $('.digit-group').find('input').each(function() {
                    $(this).attr('maxlength', 1);
                    $(this).on('keyup', function(e) {
                        var parent = $($(this).parent());

                        if(e.keyCode === 8 || e.keyCode === 37) {
                            var prev = parent.find('input#' + $(this).data('previous'));

                            if (prev.length) {
                                $(prev).select();
                            }

                        } else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                            if ($(this).val()!=null && $(this).val()!='') {
                                var next = parent.find('input#' + $(this).data('next'));

                                if (next.length) {
                                    $(next).select();
                                } else {
                                    if (parent.data('autosubmit')) {
                                        parent.submit();
                                    }
                                }
                            }
                        }
                    });
                });
            },

            processOtp = function() {
                $('#limitOtpVerify').on('click', function(e){
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    let codes = [];
                    let id = $(location).attr('href').split("/").splice(5, 6).join("/");

                    for (let i = 1; i < 7; i++) {
                        codes.push($("#digit-"+i).val());
                    }

                    $.ajax({
                        url: "{{ route('settings.limit-otp-verify-ajax') }}",
                        type: 'POST',
                        data: { code: codes,id: id },
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            if (response.success) {
                                let targetUrl = "{{ route('settings.credit-limit-status','') }}/" + id;
                                location.href = targetUrl;
                                return;
                            }
                            new PNotify({
                                text: response.message,
                                type: 'error',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 2000
                            });
                        },
                        error: function() {
                            new PNotify({
                                text: "{{ __('profile.something_went_wrong') }}",
                                type: 'error',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 2000
                            });
                        }
                    });


                });
            },

            resendOtp = function() {
                $('#limit_otp_resend').on('click', function () {

                    let id = $(location).attr('href').split("/").splice(5, 6).join("/");

                    $.ajax({
                        url: "{{ route('settings.credit.credit-limit.otp.resend') }}",
                        type: 'POST',
                        data: {id : id},
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            if (response.success) {
                                resendOtpCountDown(119);
                                $('.otp-sent').html(response.message);

                            }else {
                                new PNotify({
                                    text: response.message,
                                    type: 'error',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 2000
                                });
                            }
                        },
                        error: function() {
                            new PNotify({
                                text: "{{ __('profile.something_went_wrong') }}",
                                type: 'error',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 2000
                            });
                        }
                    });
                });
            },

            resendCountDownMessage = function(count) {
                emptyOtp();
                if (count!=0) {
                    $('.resend-otp-btn').html('{{ __('profile.resend_otp_message') }} <span id="progressBar">15</span> {{ __('profile.seconds') }}');

                    setTimeout(function setResendOtpBtn() {
                        $('.resend-otp-btn').html('Didnt get the OTP ?  &nbsp;&nbsp;<a id="limit_otp_resend" class="fw-bold text-decoration-none" style="color: #0178FF;" href="javascript:void(0);" role="">{{__('profile.resend_otp')}}</a>');
                    },(count*1000)+1000);

                }
            },

            emptyOtp = function(){

                $('input[type="text"]').val('');

            },

            resendOtpCountDown = function (countdown) {

                var countfrom = countdown;
                var seconds = setInterval(function(){
                    if(countdown <= 0){
                        clearInterval(seconds);
                    }
                   $("#progressBar").html(countdown);
                    countdown -= 1;
                }, 1000);

                resendCountDownMessage(countdown);

            };

            return {
                init:function(){

                    animateOtp(),
                    processOtp(),
                    resendOtp()

                }
            }

        }(1);jQuery(document).ready(function(){
            SnippetCreditOtp.init()
        });

        /****************************************end: Credit OTP **************************/


    </script>
@endsection

