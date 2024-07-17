<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://www.blitznet.co.id/front-assets/css/front/bootstrap.min.css" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/blitznet_user.css') }}" rel="stylesheet">

    <title></title>
    <style>
        /* .paymenttable {
          max-width: 500px;
        }

        p,
        table, h1 {
         font-family: 'europaNuova_re';
        } */
        .bg-light { background-color: #eef1ff !important;}
        small {color: #0067ff;}
        .main_section::before {height: 54px !important;}
        .ap-otp-input{padding: 10px;border: 1px solid #ccc;margin: 0 5px;width: 40px;font-weight: bold;text-align: center;}
        .ap-otp-input:focus{outline: none !important;border:1px solid #1f6feb;transition: 0.12s ease-in;}
        input::-webkit-outer-spin-button,input::-webkit-inner-spin-button {-webkit-appearance: none;margin: 0;}
        input[type=number] {-moz-appearance: textfield;}
    </style>
</head>

<body>
<header class="dark_blue_bg">
    <div class="px-3">
        <div class="row">
            <div class="col-auto p-3 py-2">
                <img src="https://www.blitznet.co.id/front-assets/images/header-logo.png" alt="Blitznet">
                <button class="btn btn-primary d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#userinfo"
                        aria-expanded="true" aria-controls="userinfo">
                    <img src="images/icons/icon_navbar.png" alt="Nav">
                </button>
            </div>
            <div class="col-auto ms-auto p-3 py-2">
                <div class="btn-group home_lenguage ps-2">

                    <button type="button" class="btn text-white dropdown-toggle" style="min-width: inherit;"
                            data-bs-toggle="dropdown" aria-expanded="false">
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
                <a href="group-trading.html" class="btn btn-warning radius_2 me-2 d-none"><img src="images/icons/group.png"
                                                                                               alt=""> Group Trading</a>
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
                    <h1 class="mb-0 text-center">Link Expired</h1>
                </div>
                <div class="card radius_1 border-0">
                    <div class="card-body text-center">
                        <div class="text-center"><img src="{{ URL::asset('front-assets/images/icons/link_expired.png') }}" alt="Link Expired"></div>
                        <h5 class="mb-3">Oops! This link you followed was expired.</h5>
                        <!-- <p>Please check your mail for your login credential with blitznet.</p> -->
                        <!-- <a href="" class="btn btn-primary btn-sm">Back To Home</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ URL::asset('front-assets/js/front/jquery-3.6.0.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/front/bootstrap.bundle.min.js') }}"></script>
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
</body>

</html>
