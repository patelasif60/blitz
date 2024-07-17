<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('home/home_header_css')

<body>
@if (config('app.env') == "live" || config('app.env') == "production")
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TMKZNTK"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
@endif
    @include('home/header')
    <!-- <div class="container-fluid overflow-hidden"> -->
    @yield('content')
    <!-- </div> -->
    <!-- footer start here -->
    @include('home/footer')

      <!-- JavaScript Bundle with Popper -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script src="{{ asset('home_design/js/bootstrap.bundle.min.js') }}"></script>
    {{--  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>--}}
    <script src="{{ URL::asset('front-assets/js/parsley.min.js') }}"></script>
    <script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.js') }}"></script>
    <script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.buttons.js') }}"></script>
    <script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.nonblock.js') }}"></script>
    <script src="{{ URL::asset('front-assets/js/front/jquery.smartWizard.js') }}"></script>
    {{--<link rel="stylesheet" href="{{ asset('home_design/css/blitnet_style.css') }}"> --}}
    <script src="{{ URL::asset('home_design/js/aos.js') }}"></script>
    <script src="{{ URL::asset('home_design/js/script.js') }}"></script>
    <script src="{{ URL::asset('home_design/js/owl.carousel.min.js') }}"></script>


    <!-- Calendly badge widget begin -->

    <!-- <script src="https://assets.calendly.com/assets/external/widget.js" type="text/javascript" async></script>
    <script type="text/javascript">
        window.onload = function() {
            Calendly.initBadgeWidget({ url: 'https://calendly.com/contact-6546/blitznet-demo', text: 'Schedule time with me', color: '#0069ff', textColor: '#ffffff', branding: true });
        }
    </script> -->
    <!-- Calendly badge widget end -->
    <!-- LOU Assist -->
    <script src="//run.louassist.com/v2.5.1-m?id=711938338414"></script>
    <!-- SVG image script -->
    <script src='//in.fw-cdn.com/30460018/254200.js' chat='true'></script>

    <script>
        function addSubscribeUserAPI(formData){
             $.ajax({
                url: "{{ route('subsribe-user-ajax') }}",
                data: formData,
                type: "POST",
                contentType: false,
                processData: false,
                success: function(data) {

                    let type = data.success == true ? 'success' : 'error' ;

                    new PNotify({
                        text: data.message,
                        type: type,
                        styling: 'bootstrap3',
                        animateSpeed: 'fast',
                        delay: 1000
                    });

                },
                error: function() {
                    console.log("error");
                },
            });
        }
        $(document).ready(function() {


        });


    </script>
  {{-- <!-- https://www.jqueryscript.net/other/Convert-SVG-Images-Into-Inline-SVG-Elements-jQuery-SVG-Convert.html#google_vignette
<script src="js/svgConvert.min.js"></script>
<script>
$(document)
    .ready(
        function(){
        $('.svg')
            .svgConvert(
                {
                    onComplete: function() {
                        console.log("Finished Converting SVG's");
                    }
                }
            );
        }
    );
</script> -->
    <!-- SVG image script end--> --}}
</body>
</html>
