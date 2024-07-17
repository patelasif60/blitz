<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://www.blitznet.co.id/front-assets/css/front/bootstrap.min.css" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/blitznet_user.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.css') }}" rel="stylesheet">

    <title></title>
    <style>
        /* .paymenttable {
      max-width: 500px;
    }

    p,
    table, h1 {
     font-family: 'europaNuova_re';
    } */
        .ap-otp-input {
            padding: 10px;
            border: 1px solid #ccc;
            margin: 0 5px;
            width: 40px;
            font-weight: bold;
            text-align: center;
        }


        .ap-otp-input:focus {
            outline: none !important;
            border: 1px solid #1f6feb;
            transition: 0.12s ease-in;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        /* .securityinput{ padding: 10px 15px; border: 1px solid #ccc; width: 260px; height: 43px; font-weight: bold; text-align: left; background-repeat: no-repeat; background-position: center center; letter-spacing: 36px; } */
    </style>
</head>

<body>
    <header class="dark_blue_bg">
        <div class="px-3">
            <div class="row">
                <div class="col-auto p-3 py-2">
                    <img src="https://www.blitznet.co.id/front-assets/images/header-logo.png" alt="Blitznet">
                    <button class="btn btn-primary d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#userinfo" aria-expanded="true" aria-controls="userinfo">
                        <img src="images/icons/icon_navbar.png" alt="Nav">
                    </button>
                </div>
                <div class="col-auto ms-auto p-3 py-2">
                    <div class="btn-group home_lenguage ps-2">

                        <button type="button" class="btn text-white dropdown-toggle" style="min-width: inherit;" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ strtoupper(str_replace('_', '-', app()->getLocale())) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width: inherit;">
                            <li><a class="dropdown-item" href="{{url('lang/id')}}">ID</a></li>
                            <li><a class="dropdown-item" href="{{url('lang/en')}}">EN</a></li>

                        </ul>

                    </div>
                    <div class="btn-group notification_head">
                        <a type="button" class="btn btn-transparent dropdown-toggle none" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <img src="https://www.blitznet.co.id/front-assets/images/icons/icon_bell.png" alt="Account Updates">
                        </a>
                    </div>
                    <a href="group-trading.html" class="btn btn-warning radius_2 me-2 d-none"><img src="images/icons/group.png" alt=""> Group Trading</a>
                    <a href="{{ route('logout') }}" class="btn btn-danger radius_2 btn-sm">Logout</a>
                </div>
            </div>
        </div>
    </header>
    <!-- header end -->
    <div class="main_section position-relative">
        <div class="container-fluid">
            <div class="row gx-4 mx-lg-0 justify-content-center">

                <!-- left section end -->
                <div class="col-lg-6 col-xl-9 py-2">
                    <div class="header_top ">
                        <h1 class="mb-0 text-center">Invitaion </h1>
                    </div>
                    <div class="card radius_1 border-0">
                        <div class="card-body text-center">

                            <h5 class="mb-3">You've been invited by {{ $mainUser['firstname'] . ' ' . $mainUser['lastname'] }} to join Blitznet team.</h5>

                            <!-- <a href="" class="btn btn-primary btn-sm">Back To Home</a> -->
                            <div class="row justify-content-center">
                                <div class="col-md-12 text-center">
                                    <p class="fw-bold">Please enter the security code:</p>
                                </div>
                                <form method="post" action="{{ route('verify-user-otp') }}" id="verify_user_otp" autocomplete="off">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $userData->id }}">
                                    <input type="hidden" name="user_email" value="{{ $userData->email }}">
                                    
                                    <div class="col-auto" style="margin-left: auto; margin-right: auto;">
                                        <div class="input-group mb-3 justify-content-center">
                                            <div class="ap-otp-inputs">
                                                <input class="ap-otp-input" type="tel" name="otp1" pattern="[0-9]" maxlength="1" data-index="0">
                                                <input class="ap-otp-input" type="tel" name="otp2" pattern="[0-9]" maxlength="1" data-index="1">
                                                <input class="ap-otp-input" type="tel" name="otp3" pattern="[0-9]" maxlength="1" data-index="2">
                                                <input class="ap-otp-input" type="tel" name="otp4" pattern="[0-9]" maxlength="1" data-index="3">
                                                <input class="ap-otp-input" type="tel" name="otp5" pattern="[0-9]" maxlength="1" data-index="4">
                                                <input class="ap-otp-input" type="tel" name="otp6" pattern="[0-9]" maxlength="1" data-index="5">
                                            </div> 
                                            <span class="input-group-text ms-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Security Code "><img src="{{ URL::asset('front-assets/images/icons/exclamation.png') }}" alt="info"> </span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-center mb-3">
                                        <a href="javascript:void(0)" id="submit_otp" data-id="{{ $userData->id }}" class="btn btn-info"> Accept Invitaion</a>
                                        <!-- <a href="" class="btn btn-primary btn-sm"> Back to Home</a> -->
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ URL::asset('front-assets/js/front/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ URL::asset('front-assets/js/front/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.js') }}"></script>
    
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
    <script>
        $(document).ready(function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            $(document).on('click', '#submit_otp', function(e) {
                e.preventDefault();

                var otp = $('input[name="otp1"]').val() + $('input[name="otp2"]').val() + $('input[name="otp3"]').val() + $('input[name="otp4"]').val() + $('input[name="otp5"]').val() + $('input[name="otp6"]').val();
                
                var newFormdata = new FormData($("#verify_user_otp")[0]);
                newFormdata.append("user_otp", otp);
                $.ajax({
                    url: $('#verify_user_otp').attr('action'),
                    type: $('#verify_user_otp').attr('method'),
                    data: newFormdata,
                    contentType: false,
                    processData: false,
                    success: function(successDataOTP) {
                        if (successDataOTP.ErrorOTP) {
                            new PNotify({
                                text: "{{ __('Invalid Otp') }}",
                                type: 'error',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 1000
                            });
                        } else {
                            window.location = successDataOTP.return_url;   
                        }
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            });
            
            $('.ap-otp-input').keypress(function(e) {
                var charCode = (e.which) ? e.which : event.keyCode
                if (String.fromCharCode(charCode).match(/[^0-9]/g))
                    return false;
            });
        });

        const $inp = $(".ap-otp-input");

        $inp.on({
            paste(ev) { // Handle Pasting
                const clip = ev.originalEvent.clipboardData.getData('text').replaceAll(/\s/g, '').trim();
                // Allow numbers only
                if (!/\d{6}/.test(clip)) return ev.preventDefault(); // Invalid. Exit here
                // Split string to Array or characters
                const s = [...clip];
                // Populate inputs. Focus last input.
                $inp.val(i => s[i]).eq(5).focus();
            },
            input(ev) { // Handle typing
                const i = $inp.index(this);
                if (this.value) $inp.eq(i + 1).focus();
            },
            keydown(ev) { // Handle Deleting
                const i = $inp.index(this);
                if (!this.value && ev.key === "Backspace" && i) $inp.eq(i - 1).focus();
            }

        });
    </script>
</body>

</html>