@extends('home/homeLayout2')
@section('content')
    <!-- banner start -->
    <div id="carouselCaptions" class="carousel slide bannersection" data-bs-ride="carousel" data-aos="fade-down">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ URL::asset('home_design/images/blitznet-web_contact.jpg') }}" class="d-block w-100" alt="blitznet contact">
                <div class="carousel-caption d-flex align-items-center flex-column w-100" style="left: inherit">
                    <div class="my-auto" data-aos="fade-up">
                        <h2 class="h1">{{__('home_latest.hello')}};</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- banner end -->

    <div class="container-xl" id="contact_us_main_div">
        <div class="row my-3 my-lg-5 justify-content-center">
            <div class="col-md-9 col-lg-11   py-3 py-lg-5" data-aos="fade-up">
              <div class="row  align-items-center">
                  <div class="col-lg-6">
                    <h1 class="color-primary mb-lg-5">{{__('home_latest.contact_us')}}</h1>

                    <h5 class="mt-lg-5">{{__('home_latest.average_response_time')}}: {{__('home_latest.business_hours')}}</h5>
                  </div>
                  <div class="col-lg-6 text-center text-lg-end">
                      <img src="{{ URL::asset('home_design/images/contact_graphics.gif') }}" class="mw-100" alt="contact us">
                  </div>
              </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
            <strong>Errors!</strong> <br>
            <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
            </ul>
            </div>
        @endif
        <form data-parsley-validate autocomplete="off" name="contact_us_form" id="contact_us_form">
            @csrf
            <div class="row justify-content-center mt-lg-3">
                <div class="col-lg-11">
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="fullname" name="fullname" required placeholder="{{__('home_latest.name')}}">
                                <label for="fullname">{{__('home_latest.name')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="company_name"  name="company_name"
                                    placeholder="{{__('home_latest.company_name')}}" required>
                                <label for="company_name">{{__('home_latest.company_name')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="form-floating ">
                                <input type="text" class="form-control" id="mobile" name="mobile" minlength="9" maxlength="13" data-parsley-type="digits"
                                    placeholder="{{__('home_latest.mobile')}}" required>
                                <label for="mobile">{{__('home_latest.mobile')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email"  name="email"
                                    placeholder="{{__('home_latest.email')}}" required>
                                <label for="email">{{__('home_latest.email')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-4">
                            <div class="form-floating textareafloat">
                               <textarea name="message" id="message" class="form-control" placeholder="{{__('home_latest.message')}}" required></textarea>
                                <label for="message">{{__('home_latest.message')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-4">
                            <div class="form-check form-check-inline">
                                <input required  data-parsley-errors-container="#errortarget" class="form-check-input" type="checkbox" name="user_type[]" value="Buyer" checked>
                                <label class="form-check-label" for="inlineCheckbox1">Buyer</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="user_type[]" value="Supplier">
                                <label class="form-check-label" for="inlineCheckbox2">Supplier</label>
                            </div>
                            <div id="errortarget" class="position-relative"></div>
                        </div>
                        <div class="col-lg-12 mb-4">
                            <button type="submit" class="btn new_btn1 text-dark btn-secondary ms-auto px-5 fw-bold contact-us-48f163ec" id="contact_sbmt_btn"><span>{{__('home_latest.send')}}</span></button>
                        </div>
                        <!-- start: google reCAPTCHA -->
                        <div class="col-lg-12 mb-4 js-captcha d-none">
                            <div class="form-floating d-flex justify-content-center" id="googleRecatchaId"></div>
                        </div>
                    <!-- end: google reCAPTCHA -->
                    </div>
                </div>
            </div>
        </form>

        <div class="row mt-lg-3 ms-lg-3 ms-2">
            <div class="col-lg-9">
                <div class="row">
                    <div class="col-md-12 pt-2 pt-lg-5" data-aos="zoom-in-left">
                        <h3 class="h1  color-primary">{{__('home_latest.consumer_protection')}}</h3>
                        <b>PT Blitznet Upaya Indonesia</b> <br>
                        <b>Email: </b> <a href="mailto:contact@blitznet.co.id">contact@blitznet.co.id</a>
                    </div>
                    <table class="table mt-3">
                        <tbody>
                            <tr>
                                <!-- <td scope="row" class="border-0">{{__('home_latest.address')}}</td> -->
                                <td class="border-0">Direktorat Jenderal Perlindungan Konsumen dan Tertib Niaga
                                <br>{{ __('home_latest.ministry_of_trade') }}
                                <br>{{ __('home_latest.rep_of_indo') }}
                                </td>
                            </tr>
                            <tr>
                                <!-- <td scope="row" class="border-0">{{__('home_latest.phone')}}</td> -->
                                <td class="border-0">
                                    <span>0853 1111 1010 (Whatsapp)</span>
                                    <!-- <span class="ms-2"> +62-21-3451692</span> -->
                                </td>
                            </tr>
                            <!-- <tr>
                                <td scope="row" class="border-0">{{__('home_latest.fax')}}</td>
                                <td class="border-0">
                                    <span>: +62-21-3858205,</span>
                                    <span class="ms-2"> +62-21-3842531</span>
                                </td>
                            </tr>
                            <tr>
                                <td scope="row" class="border-0">{{__('home_latest.email')}}</td>
                                <td class="border-0">: <a href="mailto:contact@blitznet.co.id">contact@blitznet.co.id</a>
                                </td>
                            </tr> -->
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

<script>
   //only [A-Z ,a-z] space and . and '  allowed
    $(document).ready(function(){
        $('#message').on('keypress paste',function(){
            var inputValue = event.charCode;
            if(!(inputValue >= 65 && inputValue <= 122) && (inputValue != 32 && inputValue != 0 && inputValue !=46 && inputValue !=44 && inputValue !=39)){
                event.preventDefault();
            }
            else if(inputValue >=91  && inputValue <= 96 ){
                event.preventDefault();
            }
        });
    });
    var verifyCallback = function(response) {
            submitForm();
        };

    function submitForm(){
        $('#contact_sbmt_btn').prop('disabled', true);
            var formData = new FormData($("#contact_us_form")[0]);

            $.ajax({
                url: "{{ route('contact-us-ajax') }}",
                data: formData,
                type: "POST",
                contentType: false,
                processData: false,
                success: function(successData) {
                    $("#contact_us_form")[0].reset();
                    $('#contact_sbmt_btn').prop('disabled', false);

                    if(successData.inserted == true){
                        new PNotify({
                            text: "{{ __('home_latest.contact_us_success') }}",
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });
                    }else{
                        new PNotify({
                            text: "{{ __('home_latest.something_went_wrong') }}",
                            type: 'warning',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });
                    }

                   location.reload();
                },
                error: function() {
                    console.log("error");
                },
            });
    }
    var onloadCallback = function() {
        var googleSiteKey = '{{ $googleCaptcha }}';
            grecaptcha.render('googleRecatchaId', {
            'sitekey' : googleSiteKey,
            'callback' : verifyCallback,
            'theme' : 'dark'
            });
        };

    $('#contact_us_form').submit(function(e) {
        e.preventDefault();
        var Url = "[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=]+$";
        WebUrl = $('#message').val();
        var isURL = WebUrl.match(Url);
        if (isURL != null) {
                new PNotify({
                text: "{{ __('home_latest.something_went_wrong') }}",
                type: 'warning',
                styling: 'bootstrap3',
                animateSpeed: 'fast',
                delay: 1000
            });
            return false;
        }
        var recaptchaToken = grecaptcha.getResponse();
        if(recaptchaToken){
            $('.recaptcha-checkbox-checkmark').empty();
        }

        if ($('#contact_us_form').parsley().isValid()) {
            companyName = $("#company_name").val();
            fullname = $("#fullname").val();

            if( companyName.toLowerCase() == 'crytobon' || companyName.toLowerCase() == 'bonasync' || fullname.toLowerCase() == 'crytobon' || fullname.toLowerCase() == 'bonasync' ){
                new PNotify({
                    text: "{{ __('home_latest.something_went_wrong') }}",
                    type: 'warning',
                    styling: 'bootstrap3',
                    animateSpeed: 'fast',
                    delay: 1000
                });
                return false;
            }
            $(".js-captcha").removeClass('d-none');
            $('#contact_sbmt_btn').text("{{ __('home_latest.processing') }}");
            $('#contact_sbmt_btn').attr('disabled', true);
        }
    });

</script>
@endsection
