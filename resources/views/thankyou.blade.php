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
        <meta name="msapplication-TileImage"
              content="{{ URL::asset('front-assets/images/favicon/ms-icon-144x144.png') }}">
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
    </head>
    <body>
    <div class="registration-sec container-fluid">
    <div class="registration-blk row">

        <div class="registration-form col-md-6 align-items-center justify-content-center position-relative">
            <a href="{{route('signin')}}" class="position-absolute" style="top: 5px; left: 5px;">
                <img src="{{ asset('home_design/images/blitznet-logo.svg') }}" class="img-fluid d-block me-3" style="max-height: 40px;" alt="image" />
                <img src="{{ asset('home_design/images/blitznet-logo-icon_mobile.svg') }}" alt="blitznet logo" class="mw-100 d-sm-none d-block">
            </a>
            <div class="btn-group home_lenguage p-2 ms-auto position-absolute" style="right: 0;">
                <div class="rightmenu">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown language_drop mx-lg-3 mx-1 ">
                            <a class="nav-link dropdown-toggle nav-48f101c2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="d-inline-block">
                                <img src="{{ asset('home_design/images/icons/icon_nav_language.png') }}" alt="Language">
                            </span>
                                <span class="text-uppercase">{{App::getLocale()}}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{url('lang/id')}}">ID</a></li>
                                <li><a class="dropdown-item" href="{{url('lang/en')}}">EN</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>

            </div>
            <!--  -->
            <div class="row align-items-center justify-content-center vh-100">
                <div class="col-md-9 d-flex flex-column align-items-center justify-content-center">

                    <div class="my-3 w-100">
                        <div class="card-body px-4">
                            <div class="col-md-12">
                                <h1 class="text-primary thank-you-header">{!!__('home_latest.thank_you_text')!!}</h1>
                                <p class="thank-you-subText">{!!__('home_latest.now_you_ready')!!}</p>
                            </div>
                            <div class="col-md-12">
                                <a href="{{route('signin')}}"
                                   class="btn btn-primary bannerbtn rounded-0">
                                    <span>{{ __('home_latest.click_here_to_login') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="registration-img1 col-md-6 px-0 d-none d-md-flex">

        </div>
    </div>
</div>
    </body>
    <!--section ends here-->
    <script src="{{ URL::asset('front-assets/js/front/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('front-assets/js/parsley.min.js') }}"></script>
    <script src="{{ URL::asset('front-assets/intlTelInput/js/intlTelInput.js') }}"></script>
    <script src="{{ URL::asset('/assets/vendors/sweetalert/sweetalert.min.js') }}"></script>
    <script>
        // Password Eye functionality.
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
            $("#supplierLogInBtn").click(function(e) {
                var formData = new FormData($('#supplierLogInForm')[0]);
                $("#supplierLogInBtn").prop('disabled', true);
                e.preventDefault();
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
                        $("#supplierLogInForm").submit();
                    },
                    error:function(xhr){
                        if (xhr.status == 422){
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                $('#'+key+'Error').html('');
                                $('#'+key+'Error').append('<small class="text-danger">'+value+'</small>');
                            });
                        }
                        $("#supplierLogInBtn").prop('disabled', false);

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
    </script>
</html>
