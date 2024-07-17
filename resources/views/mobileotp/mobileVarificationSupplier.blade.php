@extends('admin/supplierOtpLayout')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('front-assets/intlTelInput/css/intlTelInput.css') }}">
<style>

.otp-image{
    background: url(../image/Two-factor.gif) center center no-repeat;
    background-size: 350px 350px;
    height: 50vh;
    width: 30vw;
}
.OTP_Section{
    margin-top: 5%;
    height: 650px;
}

.form-control:focus, .pop-up-header{
    color: #0000FF;
}

.input-size{
    width: 7%;
    margin: 5px;
} 

.form-control, .form-select{
    border: none;
    border-bottom: 1px solid #0000FF;
    border-bottom-left-radius: 0px;
    border-bottom-right-radius: 0px;
}
.input-group>.form-control, .input-group>.form-select{
    text-align: center;
}

.btn-cancel{
    background-color: #5c5c76;
    color: rgb(255, 255, 255);
}

.form-select, .form-control{
    font-size: 16px;
}
.modal-body {
     background-color: #fff !important;
}
@media screen and (max-width:768px) {
    .otp-image{
        background-size:150px 150px;
        order: 1;
        height: 30vh;
        width: 100%;
        border-right:none;
        /* border-top: 1px solid #4545ff69; */
        /* margin-top: 20px; */
    }

    .OTP_Section{
        margin-top: 20%;
        height: 650px;
    }

    .container-scroller{
        height: 790px !important;
    }
    

    .input-size{
        width: 9%;
        margin: 5px;
    }

    .form-select, .form-control{
        font-size: 18px;
    }

    h5{
        font-size: 1.5rem !important;
    }

    p{
        font-size: 1rem !important;
    }
}

</style>
<section>
    <div class="row OTP_Section bg-white justify-content-center mx-0">
        <div class="col-md-6 align-self-center">
            <div class="text-center">
                <img src="{{ URL::asset('assets/images/Two-factor.gif') }}" class="mw-100">
            </div>
        </div>
        <div class="col-md-6 align-self-center">
            <h5 class="card-title text-center mb-2 text-uppercase align-self-center">{{__('validation.mobile_varify') }}</h5>

            <div class="row my-4">
                <form id="mobileVarify" method="POST" class="h-100" data-parsley-validate>
                    <input type="hidden" name="email" id="email" value="{{ $userData->email }}">
                    <div class="col-md-12">
                        <div class="mb-3 col-md-6 mx-auto">
                            <input value="{{ $userData->mobile }}" data-parsley-errors-container="#otpManyerror" required data-parsley-length="[9, 16]" data-parsley-checkmobile data-parsley-checkmobile-message="{{ __('validation.mobileexits') }}"data-parsley-required-message="{{ __('frontFormValidationMsg.mobile') }}" data-parsley-length-message="{{ __('profile.required_phone_number_error') }}" data-parsley-type="digits" data-parsley-type-message="{{ __('frontFormValidationMsg.numeric') }}"  type="mobile" class="form-control border border-dark input-number" name="mobile" id="mobile">
                            <div id="otpManyerror" class="mt-2 position-relative text-danger"></div>
                        </div>
                    </div>
                    <div class="col-md-12 d-flex justify-content-center my-2">
                        <div class="submit-btn col-md- text-center">
                            <button type="button" class="btn btn-primary align-items-center fw-semibold" id="userRegister">{{ __('profile.submit.title') }}</button>
                            <a href="{{ url()->previous() }}" class="btn btn-cancel fw-semibold " id="otpResend">{{ __('admin.cancel')}}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>
<div class="modal fade" id="Otpmodal" tabindex="-1" aria-labelledby="OtpmodalLabel"  data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
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
<script src="{{ URL::asset('front-assets/intlTelInput/js/intlTelInput.js') }}"></script>
<script type="text/javascript">
     // show mobile code

    (function(){
      var mobile = document.getElementById('mobile');
      mobile.addEventListener('keypress',function(event){
          if(event.keyCode == 13) {
              event.preventDefault();
          }
      });
    }());

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
   
    $('input[name="phone_code"]').val({{ $phoneCode }});
    iti.setCountry('{{$country}}');
    
    $("#userRegister").click(function(xhr) {
        if ($('#mobileVarify').parsley().validate()) {
            $('#userRegister').prop('disabled', true);
            OtpApp.sendotp();
        }
    });
    // call ajax for varify otp
    var Otp = function(){ 
        return { 
            init:function(){
               
            }, 
            varifyOtp:function(){
                        formData = {"_token": $('meta[name="csrf-token"]').attr('content'),'phone_number':$('#mobile').val(),'phone_code':$('input[name="phone_code"]').val()};

                 $.post("mobilevarify",formData, (res) => {
                    if(res.success){
                        $('#Otpmodal').modal('hide');
                        window.location='{{ route("admin-dashboard") }}'
                    }
                });
            },
        }
    }(1);
    
jQuery(document).ready(function(){
    Otp.init()
});
$(document).on('input','.input-number',function (evt) {
    this.value = this.value.replace(/[^0-9]/g, '');
    if ((evt.which < 48 || evt.which > 57))
    {
        evt.preventDefault();
    }
});
</script>
@stop