@extends('dashboard/layout/layout')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('front-assets/intlTelInput/css/intlTelInput.css') }}">
<style>
	.iti {
    	display: block !important;
	}

	.iti .iti--allow-dropdown {
		width: 100% !important;
	}

	.iti__flag-container {
		position: absolute !important;
	}

	.iti__country-list {
		overflow-x: hidden;
		max-width: 360px;
	}

	/* .iti--separate-dial-code .iti__selected-flag{font-size: 12px;  height: 38px;} */
	#myBtn {
		left: 30px !important;
	}
	.ap-otp-input{padding:10px;border:none;border-bottom:2px solid #000;margin:0 5px;width:40px;font-weight:700;text-align:center}
.ap-otp-input:focus{outline:0!important;border-bottom:1px solid #1f6feb;transition:.12s ease-in}
.pop-up-header {
    background-color: #0000FF;
    color: #FFFFFF;
    font-weight: 500;
}

</style>
<div class="col-lg-8 col-xl-9 py-2 mx-auto" >
    <div class="header_top d-flex align-items-center">
        <h1 class="mb-0 text-center w-100">{{__('validation.mobile_varify') }}</h1>
    </div>
    <form id="mobileVarify" method="POST" class="h-100" data-parsley-validate>
    <div class="row floatlables mb-3">
    	<div class="col-md-12 text-center my-4">
            <img src="{{ URL::asset('front-assets/images/mob-verfication.png') }}" alt="Blitznet Verification">
        </div>
        <div class="col-md-5 mx-auto">
            <div class="mb-3">
	            <label class="form-label"> {{ __('signup.mobile_no') }} <span class="text-danger">*</span></label>
	            <input value="{{ $userData->mobile }}" data-parsley-errors-container="#otpManyerror" required data-parsley-length="[9, 16]" data-parsley-checkmobile data-parsley-checkmobile-message="{{ __('validation.mobileexits') }}"data-parsley-required-message="{{ __('frontFormValidationMsg.mobile') }}" data-parsley-length-message="{{ __('profile.required_phone_number_error') }}" data-parsley-type="digits" data-parsley-type-message="{{ __('frontFormValidationMsg.numeric') }}"  type="mobile" class="form-control border-start-0" name="mobile" id="mobile">
                <div id="otpManyerror" class="col-md-12 mb-3 js-validation text-danger"></div>

	        </div>
        </div>
    </div>
    <div class="col-md-12 text-center mt-3">
        <button type="button" class="btn btn-primary px-3 py-1 mb-1" id="userRegister">
            <img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}" alt="Submit" class="pe-1"> {{ __('profile.submit.title') }}
        </button>
        <a href="{{ route('profile') }}" class="btn btn-secondary ms-3 px-3 py-1 mb-1"> <img src="{{ URL::asset('front-assets/images/icons/cancel.png') }}" width="16px" alt="Submit" class="pe-1"> {{ __('admin.cancel')}}</a>

    </div>
</form>
</div>
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
               // frontMenuRedirection()
            }, 
            varifyOtp:function(){
                        formData = {"_token": $('meta[name="csrf-token"]').attr('content'),'phone_number':$('#mobile').val(),'phone_code':$('input[name="phone_code"]').val()};

                 $.post("mobilevarify",formData, (res) => {
                    if(res.success){
                        $('#Otpmodal').modal('hide');
                        window.location='{{ route("dashboard") }}'
                    }
                });
            },
        }
    }(1);
    
jQuery(document).ready(function(){
    Otp.init()
});
</script>
@stop