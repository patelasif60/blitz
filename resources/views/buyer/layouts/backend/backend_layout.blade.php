<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ URL::asset('front-assets/intlTelInput/css/intlTelInput.css')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
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

    <!--begin: css-->
    <link href="{{ URL::asset('front-assets/css/front/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/component.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/blitznet_user.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/calender.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/swal-style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.buttons.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/smart_wizard_all.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/fastselect.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/jquery-ui-1.12.1.custom/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/group_trading.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/sweetalert2.min.css') }}" rel="stylesheet">
    <!--end: css-->
    @yield('css')

    <style>
        /* new phone demo */
        .iti{ display: block;}
        .iti .iti--allow-dropdown{width: 100% !important;}
        .iti__flag-container{position: absolute !important;}
        .iti__country-list{ overflow-x: hidden; max-width: 360px;}
    </style>
    @yield('custom-css')

    <!--begin: Header JavaScript Bundle with Popper -->
    <script src="{{ URL::asset('front-assets/js/front/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/vendors/sweetalert/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/vendors/sweetalert/sweetalert2@11.js') }}"></script>
    {{-- socket.io init --}}
    <script src="{{ mix('js/app.js') }}"></script>
    <!--end: Header JavaScript Bundle with Popper -->
    @yield('header-custom-script')


</head>

<body>
@include('buyer.common.header.backend.header')
<div class="main_section position-relative profile_section">
    <div class="header_top d-flex align-items-center pb-5 px-4">
        <div>
            <ul class="nav nav-tabs border-0" id="mainTab" role="tablist">
                <li class="nav-item mx-3 changetab" role="presentation" id="header_profile_tab">
                    <button class="nav-link" id="personal-tab" type="button" aria-controls="personal" aria-selected="false" data-target="{{ route('profile') }}">
                        {{ __('profile.profile') }}
                    </button>
                </li>
                <li class="nav-item mx-3 changetab" role="presentation" id="header_admin_setting">
                    <button class="nav-link" id="company-tab" data-bs-toggle="tab" data-bs-target="#company" type="button" role="tab" aria-controls="company" aria-selected="true">
                        {{ __('profile.Admin_Setting') }}
                    </button>
                </li>

            </ul>
        </div>
        <a href="@if( in_array(Route::getCurrentRoute()->action['as'], ['settings.roles.create', 'settings.roles.edit', 'settings.users.permission'])){{URL::previous()}}@else{{ route('dashboard') }}@endif" class="btn btn-warning ms-auto btn-sm" id="backBtn" style="padding-top: .1rem;padding-bottom: .1rem;">
            <img src="{{ URL::asset('front-assets/images/icons/angle-left.png') }}" alt="">
            {{ __('profile.back') }}
        </a>
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

<script src="{{ URL::asset('front-assets/js/parsley.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/fastselect.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.buttons.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.nonblock.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/front/jquery.smartWizard.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/front/owl.carousel.min.js') }}"></script>
<script src="{{ URL::asset('/assets/vendors/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/front/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/custom/app.js') }}"></script>
@yield('script')

@yield('custom-script')
<script type="text/javascript">
    function userLogout(){
        sessionStorage.clear();
    }

    /****************************************begin:Buyer Backend Sidebar***************************************/
    var SnippetBuyerBackendSettingSidebar = function(){

        var removedMenuLinks = function(){

            $('li.de-active-settings').on('click', function(){

                setSession('company-tab', $(this).attr('id'));

                location.replace($('#personal-tab').attr('data-target'));
            });

        },

        removedHeaderBarLinks = function() {
            $('#personal-tab').on('click', function(e){
                e.preventDefault();

                setSession('personal-tab', 'change_personal_info');

                location.replace($(this).attr('data-target'));
            });
        },

        setSession = function(primaryTab, secondaryTab){
            sessionStorage.clear();
            sessionStorage.setItem("profile-lastlocation", JSON.stringify({
                mainTab: primaryTab,
                secondTab: secondaryTab
            }));
        },

        defaultTabSet = function (){
            sessionStorage.clear();
            $('#company-tab').click();
        },
        
        btnRedirect = function (){
            $('.btn-target').on('click', function(e){
                e.preventDefault();
                location.replace($(this).attr('data-target'));
            });
        };

        return {
            init: function () {
                removedMenuLinks(),
                removedHeaderBarLinks(),
                defaultTabSet(),
                btnRedirect()
            },

            setSessionGlobal: function(primaryTab, secondaryTab) {
                setSession(primaryTab, secondaryTab);
            }
        }


    }(1);

    jQuery(document).ready(function(){
        SnippetBuyerBackendSettingSidebar.init();
    });
    /****************************************end:Buyer Backend Sidebar***************************************/

</script>
</html>
