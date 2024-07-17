<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ URL::asset('front-assets/intlTelInput/css/intlTelInput.css')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> --}}
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    {{--LOU Code
        @if (config('app.env') == "live")    
            <!-- <script src="//run.louassist.com/v2.5.1-m?id=601044422567"></script> -->
        @elseif(config('app.env') == "staging")
            <!-- <script src="//run.louassist.com/v2.5.1-m?id=601044422567"></script> -->
        @elseif(config('app.env') == "local")
            <!-- <script src="//run.louassist.com/v2.5.1-m?id=601044422567"></script> -->
        @endif--}}

    <link rel="apple-touch-icon" sizes="57x57"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"
        href="{{ URL::asset('front-assets/images/favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ URL::asset('front-assets/images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96"
        href="{{ URL::asset('front-assets/images/favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ URL::asset('front-assets/images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ URL::asset('front-assets/images/favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage"
        content="{{ URL::asset('front-assets/images/favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <title>blitznet</title>

    <!-- CSS only -->
    <link href="{{ URL::asset('front-assets/css/front/bootstrap.min.css') }}" rel="stylesheet">
    {{--<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script> -->--}}
    <script src="{{ URL::asset('front-assets/js/front/jquery-3.6.0.min.js') }}"></script>
    <link href="{{ URL::asset('front-assets/css/front/component.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/blitznet_user.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/calender.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/swal-style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.buttons.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link href="{{ URL::asset('front-assets/css/front/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/smart_wizard_all.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/owl.carousel.min.css') }}" rel="stylesheet">
    <!-- <link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet"> -->
    <link href="{{ URL::asset('front-assets/css/front/jquery.dataTables.min.css') }}" rel="stylesheet">
    {{--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">--}}
    <link href="{{ URL::asset('front-assets/css/front/fastselect.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/jquery-ui-1.12.1.custom/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/group_trading.css') }}" rel="stylesheet">
    {{--<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css"> --> --}}
    <link href="{{ URL::asset('front-assets/css/front/sweetalert2.min.css') }}" rel="stylesheet">
    {{-- <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.js"></script> -->--}}
    <script src="{{ URL::asset('/assets/vendors/sweetalert/sweetalert2.min.js') }}"></script>
    {{--<!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->--}}
    <script src="{{ URL::asset('/assets/vendors/sweetalert/sweetalert2@11.js') }}"></script>

    {{-- socket.io init --}}
    <script src="{{ mix('js/app.js') }}"></script>


<style>

    /* new phone demo */
    .iti{ display: block;}
    .iti .iti--allow-dropdown{width: 100% !important;}
    .iti__flag-container{position: absolute !important;}
    .iti__country-list{ overflow-x: hidden; max-width: 360px;}
</style>
</head>

<body>
@if(isset(auth()->user()->id) && auth()->user()->is_active == 0 )
        <div class="toast align-items-center show w-100 js-email bg-purple" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body flex-fill text-center py-1">
                    {{ __('profile.mailnotification') }} <!-- <a href="javascript:void(0);" class="btn btn-warning btn-sm py-1 js-varifyEmail"><small>Verify</small></a> -->
                </div>
                <!-- <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> -->
            </div>
        </div>
    @endif
@include('dashboard/layout/front_header')
    <div class="main_section position-relative profile_section">
        <div class="header_top d-flex align-items-center pb-5 px-4">
            <div>
                <ul class="nav nav-tabs border-0" id="mainTab" role="tablist">
                    @can('publish buyer profile')
                        <li class="nav-item mx-3 changetab" role="presentation" id="header_profile_tab">
                            <button class="nav-link" id="personal-tab" data-bs-toggle="tab"
                                data-bs-target="#personal" type="button" role="tab" aria-controls="personal"
                                aria-selected="true">{{ __('profile.profile') }}</button>
                        </li>
                    @endcan
                    @can('publish buyer settings')
                        <li class="nav-item mx-3 changetab" role="presentation" id="header_admin_setting">
                            <button class="nav-link" id="company-tab" data-bs-toggle="tab" data-bs-target="#company"
                                type="button" role="tab" aria-controls="company" aria-selected="false">{{ __('profile.Admin_Setting') }}</button>
                        </li>
                    @endcan

                </ul>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-warning ms-auto btn-sm" id="backBtn"
                style="padding-top: .1rem;padding-bottom: .1rem;"><img
                    src="{{ URL::asset('front-assets/images/icons/angle-left.png') }}" alt="">
                {{ __('profile.back') }}</a>
        </div>
        <div class="container-fluid profile_sub_section">
            <div class="row gx-4 mx-lg-0">
                @yield('content')
            </div>
        </div>
    </div>
</body>
<!-- JavaScript Bundle with Popper -->
<script src="{{ URL::asset('front-assets/js/front/bootstrap.bundle.min.js') }}"></script>
{{--<!-- <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> -->--}}
<script src="{{ URL::asset('/assets/vendors/datatables.net/1.10.25/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/parsley.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/fastselect.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.buttons.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.nonblock.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/front/jquery.smartWizard.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/front/owl.carousel.min.js') }}"></script>
{{--<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->--}}
<script src="{{ URL::asset('/assets/vendors/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/front/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/custom/app.js') }}"></script>

<script src="{{ URL::asset('js/socket.js') }}"></script>

<script>
    // setInterval(function() {
    //     loadUserActivityData();
    // }, 5000);
    function userLogout(){
        sessionStorage.clear();
    }

    function setMainLocation() {
        let mainTab = '';
        $( "#mainTab li" ).each(function( index ) {
            let btn = $( this ).find('button');
            if(btn.hasClass('active')) {
                mainTab = btn.attr('id')
                return false;
            }
        });
        let tabId = 'myTab';
        let secondTab = 'change_personal_info';
        if(mainTab=="company-tab"){
            tabId = 'myTab1';
            secondTab = 'change_Preferences';
        }
        //console.log(mainTab);
        //console.log(tabId);
        let notActive = true;
        $( "#"+tabId+" li" ).each(function( index ) {
            let btn = $( this ).find('button');
            if(btn.hasClass('active')) {
                secondTab = $( this ).attr('id');
                notActive = false;
                return false;
            }
        });
        sessionStorage.setItem("profile-lastlocation", JSON.stringify({
            "mainTab": mainTab,
            "secondTab": secondTab
        }));
        return notActive;
    }

    function loadUserActivityData() {

        $.ajax({
            url: "{{ route('dashboard-user-activity-ajax') }}",
            type: 'GET',
            success: function(successData) {
                console.log("loaded")
                $('#userActivitySection').html(successData.userActivityHtml);
            },
            error: function() {
                console.log('error');
            }
        });
    }
    window.parsley.addValidator('email', {
        validateString: function(data) {

            var mobileReg = /^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$/;
            var emailReg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if(data.match(emailReg)){
                return true;
            }
            return false;

        },
        messages: {
            en: 'Invalid Email',
        }
    });
    window.parsley.addValidator('mobile', {
        validateString: function(data) {

            var mobileReg = /^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$/;
            var emailReg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if(data.match(mobileReg)){
                return true;
            }
            return false;

        },
        messages: {
        en: 'Invalid Number',
        }
    });
    $(document).on('click', '#userActivityBtn', function() {
        loadUserActivityData();
    });

    if (sessionStorage.getItem("profile-lastlocation")){
        let obj = JSON.parse(sessionStorage.getItem("profile-lastlocation"));
        //console.log(obj);
        $('#'+obj.mainTab).click();
        $('#'+obj.secondTab+' button').click();
    }else {
        $('#personal-tab').click();
        $('#change_personal_info button').click();
        $('#change_Preferences button').click();
        setMainLocation();
    }
    $(document).ready(function() {
        $(document).on('click', '#mainTab>li>button', function(e) {
            if(setMainLocation()){
                let obj = JSON.parse(sessionStorage.getItem("profile-lastlocation"));
                $('#'+obj.secondTab+' button').click();
            }
        });
    });
</script>
</html>
