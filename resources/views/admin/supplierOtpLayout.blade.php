<!DOCTYPE html>
<!--<html lang="en">-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @stack('top_head')
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> --}}
    <title>blitznet</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ URL::asset('front-assets/js/front/jquery-3.6.0.min.js') }}"></script>
    @if(auth()->user()->role_id == 3)
        <!-- Commented due to deactivation of LOU asist -->
        {{-- <script src="//run.louassist.com/v2.5.1-m?id=601044422567"></script> --}}

    @endif
    <!-- Hotjar Tracking Code for https://www.blitznet.co.id/ -->
    @if (config('app.env') == "live")

        <script type="text/javascript">
            window._mfq = window._mfq || [];
            (function() {
            var mf = document.createElement("script");
            mf.type = "text/javascript"; mf.defer = true;
            mf.src = "//cdn.mouseflow.com/projects/e23b2581-094b-4fe3-a8d3-149417b0fbc5.js";
            document.getElementsByTagName("head")[0].appendChild(mf);
            })();
      </script>

    @endif
    <!-- End Hotjar Tracking Code -->

    <!-- base:css -->
    <link rel="stylesheet" href="{{ URL::asset('/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/assets/vendors/base/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ URL::asset('/assets/vendors/jquery-tags-input/jquery.tagsinput.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/assets/css/vertical-layout-light/style.css') }}">
    <!-- endinject -->
    <link rel="icon" type="image/png" href="{{ URL::asset('home_design/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ URL::asset('/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/assets/vendors/jquery-toast-plugin/jquery.toast.min.css') }}">

    <link href="{{ URL::asset('front-assets/css/front/searchPanes.dataTables.min.css') }}" rel="stylesheet">

    <link href="{{ URL::asset('front-assets/css/front/select.dataTables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('/assets/css/admin/style.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/assets/css/admin/filter_multi_select.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/assets/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
    <link rel="stylesheet"href="{{ URL::asset('/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link href="{{ URL::asset('front-assets/css/front/smart_wizard_all.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/assets/css/admin/awesomplete.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link href="{{ URL::asset('front-assets/css/front/smart_wizard_all.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/css/admin/awesomplete.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.buttons.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('front-assets/intlTelInput/css/intlTelInput.css')}}">
    <script src="{{ URL::asset('js/socket.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <link rel="stylesheet" href="{{ URL::asset('front-assets/css/front/fontawesome_5.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('chat/css/adminChat.css') }}">
    <link href="{{ URL::asset('front-assets/css/front/swal-style.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ URL::asset('/assets/css/admin/datetime/nmp_flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/assets/css/admin/datetime/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/new_design/css/Login_register_css.css') }}">


        <style>

            .hide,
            .hidden {
                display: none !important;
            }

            .swal-button--cancel {
                background-color: #5c5c76;
                color: rgb(255, 255, 255);
            }


            .swal-button--cancel:hover {
                background-color: #4a4a4a !important;
                color: #fff !important;
            }
            .modal-header {
                background-color: #13193a;
                color: #fff;
            }

            .modal-body {
                background-color: #ebedf1;
            }

            .modal-lg {
                max-width: 900px;
            }
            .iti{
                display: block !important;
            }
            .iti--separate-dial-code .iti__selected-flag{font-size: 12px;  height: 38px;}
            .bg_xen{background-repeat: no-repeat; background-position: 100% center;background-size: auto 90%;background-image: url(../assets/images/xen_icon.png);}
            .navbar-nav .dropdown-menu{position: absolute;}
			#profile_right_top_img{
				height: 30px;
				width:30px;
				border-radius: 50%;
			}
            /* -------------------------------- */
            .bell_animation {
                -webkit-animation: ring 4s .20s ease-in-out infinite;
                -webkit-transform-origin: 50% 4px;
                -moz-animation: ring 4s .20s ease-in-out infinite;
                -moz-transform-origin: 50% 4px;
                animation: ring 4s .20s ease-in-out infinite;
                transform-origin: 50% 4px;
            }

            @-webkit-keyframes ring {
                0% {-webkit-transform: rotateZ(0);}
                1% {-webkit-transform: rotateZ(30deg);}
                3% {-webkit-transform: rotateZ(-28deg);}
                5% {-webkit-transform: rotateZ(34deg);}
                7% {-webkit-transform: rotateZ(-32deg);}
                9% {-webkit-transform: rotateZ(30deg);}
                11% {-webkit-transform: rotateZ(-28deg);}
                13% {-webkit-transform: rotateZ(26deg);}
                15% {-webkit-transform: rotateZ(-24deg);}
                17% {-webkit-transform: rotateZ(22deg);}
                19% {-webkit-transform: rotateZ(-20deg);}
                21% {-webkit-transform: rotateZ(18deg);}
                23% {-webkit-transform: rotateZ(-16deg);}
                25% {-webkit-transform: rotateZ(14deg);}
                27% {-webkit-transform: rotateZ(-12deg);}
                29% {-webkit-transform: rotateZ(10deg);}
                31% {-webkit-transform: rotateZ(-8deg);}
                33% {-webkit-transform: rotateZ(6deg);}
                35% {-webkit-transform: rotateZ(-4deg);}
                37% {-webkit-transform: rotateZ(2deg);}
                39% {-webkit-transform: rotateZ(-1deg);}
                41% {-webkit-transform: rotateZ(1deg);}
                43% {-webkit-transform: rotateZ(0);}
                100% {-webkit-transform: rotateZ(0);}
            }

            .notification_select .form-select {
                font-size: 12px;
            }

            .notification_sections:hover {
                background-color: #f6f7fc;
            }

            .sidebar .nav .nav-item .nav-link .badge {
                font-size: 11px;
            }

            .notification-ripple-bg.notification-ripple {
                height: 15px;
                width: 18px;
                position: absolute;
                right: -10px;
                top: -2px;
                line-height: inherit !important;
                border-radius: 8px !important;
                z-index: 1;
                font-size: 0.6em;
                color: white;
            }
            .notification-ripple-bg {
                background-color: #2c2f41 !important;
            }
        </style>
    @stack('bottom_head')

    @php
        if(Auth::user()) {
            $langVal = session()->get('locale');
        } else {
            $langVal = session()->get('localelogin');
        }
    @endphp
    <input type="hidden" name="langValue" id="langValue" value="{{ $langVal }}">
</head>

<body style="{{ Auth::user()->is_active == 0  && Auth::user()->role_id == 3 ? 'padding-top:25px':''}}" >
    <div class="container-scroller">

        <!-- partial:partials/_navbar.html -->
        @if (Auth::user())
            <input type="hidden" id="hemail" name="hemail" value="{{Auth::user()->email}}">
            <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
                @if(Auth::user()->is_active == 0 && Auth::user()->role_id == 3)
                <div class="toast align-items-center show w-100 js-email bg-light w-100" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body flex-fill text-center py-1">
                             {{ __('profile.mailnotification') }} <a href="javascript:void(0);" style="top: 36px !important;" class="verify js-sendemail" title="{{ __('profile.varify_your_mail') }}" >{{ __('admin.resend') }} {{ __('profile.Email') }}</a>
                        </div>
                    </div>
                </div>
                @endif
                <div class="text-start navbar-brand-wrapper d-flex align-items-center justify-content-between">
                    <a class="navbar-brand brand-logo" href="{{ route('admin-dashboard') }}"><img
                            src="{{ URL::asset('/home_design/images/blitznet-logo_w.svg') }}" alt="logo" class="w-auto"/></a>
                    <a class="navbar-brand brand-logo-mini" href="{{ route('admin-dashboard') }}"><img
                            src="{{ URL::asset('/home_design/images/icons/blitznet-white.svg') }}" alt="logo" /></a>
                </div>
                <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                    <ul class="navbar-nav navbar-nav-right">
                        @if(auth()->user()->role_id == 3)
                        <!-- Listing for Documents -->
                            <li class="nav-item nav-search mx-1">
                                <div id="supplier_video_tutorial" class="position-relative">
                                    <button type="button" class="btn dropdown-toggle p-2" style="min-width: inherit;" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('admin.tutorials') }}">
                                        <b> <i class="fa fa-file-text me-1"></i> {{ __('admin.tutorials') }}</b>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" style="min-width: inherit;">
                                        <li> 
                                            <a href="{{ route('download-supplier-doc', 'Guidance_to_have_a_professional_profile.pdf') }}" class="dropdown-item supplierDocs d-flex align-items-center">
                                                <img class="me-2" src="{{ URL::asset('front-assets/images/icons/icon_download.png') }}" alt="" srcset="" height="16px">{{ __('admin.professional_profile_guidance') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <!-- /End -->
                            <li class="nav-item nav-search me-1" >
                                <div id="supplier_video_tutorial" class="position-relative">
                                <button type="button" class="btn dropdown-toggle" style="min-width: inherit;" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('dashboard.guide') }}">
                                <!-- <img src="{{ URL::asset('front-assets/images/icons/icon_video_black.png') }}" height="24px" alt="" srcset=""> -->
                                <i class="fa fa-file-video-o"></i>
                            </button>
                                    <ul class="dropdown-menu dropdown-menu-end" style="min-width: inherit;">
                                        <li><a class="dropdown-item supplierVideo d-flex align-items-center" data-video-en="https://www.youtube.com/embed/Bh0wrgF-0bY" data-video-id="https://www.youtube.com/embed/It6pcM3Mmhw" data-video-title="{{ __('admin.how_supplier_details') }}" data-toggle="modal" data-target="#videoModal" href="javascript:void(0)"><img class="me-2" src="{{ URL::asset('front-assets/images/icons/play-button.png') }}" alt="" srcset="" height="16px">{{ __('admin.how_supplier_details') }}</a></li>

                                        <li><a class="dropdown-item supplierVideo d-flex align-items-center" data-video-en="https://www.youtube.com/embed/NNUGlfOE0KI" data-video-id="https://www.youtube.com/embed/a1lPE9rSNNA" data-video-title="{{ __('admin.how_to_add_supplier_address') }}" data-toggle="modal" data-target="#videoModal" href="javascript:void(0)"><img class="me-2" src="{{ URL::asset('front-assets/images/icons/play-button.png') }}" alt="" srcset="" height="16px">{{ __('admin.how_to_add_supplier_address') }}</a></li>

                                        <li><a class="dropdown-item supplierVideo d-flex align-items-center" data-video-en="https://www.youtube.com/embed/_sdqLw87hEg" data-video-id="https://www.youtube.com/embed/5sg0UZwSH4c" data-video-title="{{ __('admin.how_supplier_product') }}" data-toggle="modal" data-target="#videoModal" href="javascript:void(0)"><img class="me-2" src="{{ URL::asset('front-assets/images/icons/play-button.png') }}" alt="" srcset="" height="16px">{{ __('admin.how_supplier_product') }}</a></li>


                                        <li><a class="dropdown-item supplierVideo d-flex align-items-center" data-video-en="https://www.youtube.com/embed/b8xh9UHRq64" data-video-id="https://www.youtube.com/embed/26DCiHait2M" data-video-title="{{ __('admin.how_supplier_rfq_rfqReply') }}" data-toggle="modal" data-target="#videoModal" href="javascript:void(0)"><img class="me-2" src="{{ URL::asset('front-assets/images/icons/play-button.png') }}" alt="" srcset="" height="16px">{{ __('admin.how_supplier_rfq_rfqReply') }}</a></li>

                                        <li><a class="dropdown-item supplierVideo d-flex align-items-center" data-video-en="https://www.youtube.com/embed/LkIg5QFUCjE" data-video-id="https://www.youtube.com/embed/gG-ij46nupk" data-video-title="{{ __('admin.how_supplier_quote') }}" data-toggle="modal" data-target="#videoModal" href="javascript:void(0)"><img class="me-2" src="{{ URL::asset('front-assets/images/icons/play-button.png') }}" alt="" srcset="" height="16px">{{ __('admin.how_supplier_quote') }}</a></li>

                                        <li><a class="dropdown-item supplierVideo d-flex align-items-center" data-video-en="https://www.youtube.com/embed/na3WBC2Z5zg" data-video-id="https://www.youtube.com/embed/33n4exHU42Q" data-video-title="{{ __('admin.how_supplier_order') }}" data-toggle="modal" data-target="#videoModal" href="javascript:void(0)"><img class="me-2" src="{{ URL::asset('front-assets/images/icons/play-button.png') }}" alt="" srcset="" height="16px">{{ __('admin.how_supplier_order') }}</a></li>

                                        <li><a class="dropdown-item supplierVideo d-flex align-items-center" data-video-en="https://www.youtube.com/embed/rgEZ6wSq5fU" data-video-id="https://www.youtube.com/embed/cNFqogtYMP4" data-video-title="{{ __('admin.how_supplier_invite_buyer') }}" data-toggle="modal" data-target="#videoModal" href="javascript:void(0)"><img class="me-2" src="{{ URL::asset('front-assets/images/icons/play-button.png') }}" alt="" srcset="" height="16px">{{ __('admin.how_supplier_invite_buyer') }}</a></li>

                                    </ul>
                                </div>
                            </li>
                        @endif
                        <li class="nav-item  d-lg-flex">
                            <div class="btn-group home_lenguage">

                                <button type="button" class="btn dropdown-toggle px-2" style="min-width: inherit;"
                                        data-bs-toggle="dropdown" aria-expanded="false">

                                    {{ strtoupper(str_replace('_', '-', app()->getLocale())) }}

                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" style="min-width: inherit;">
                                    <li><a class="dropdown-item" href="{{url('lang/id')}}">ID</a></li>
                                    <li><a class="dropdown-item" href="{{url('lang/en')}}">EN</a></li>

                                </ul>

                            </div>
                        </li>
                        <li class="nav-item dropdown align-items-center d-lg-flex">
                            <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center"
                                id="messageDropdown" href="#" data-bs-toggle="dropdown">
								@if(auth()->check() && auth()->user()->profile_pic)
									<img src="{{ URL::asset('storage/'.auth()->user()->profile_pic) }}" alt="image" id="profile_right_top_img">
								@else
									<i class="fa fa-user-circle"></i>
								@endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="appDropdown">
                                @if(auth()->id() != 1)
                                    <a class="dropdown-item" href="{{ route('reset-password') }}">
                                        <i class="mdi mdi-account text-primary"></i>
                                        {{ __('profile.profile_details') }}
                                    </a>
                                @endif
                                @if (Auth::user())
                                    <a class="dropdown-item" href="{{ route('admin-logout') }}">
                                        <i class="mdi mdi-logout text-primary"></i>
                                        {{ __('admin.logout') }}
                                    </a>
                                @endif
                            </div>
                        </li>
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                        data-bs-toggle="offcanvas">
                        <span class="mdi mdi-menu"></span>
                    </button>
                </div>
            </nav>
            <div class="content-wrapper">
                @yield('content')
            </div>
            <div class="footer-wrapper">
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-center text-sm-left d-block d-sm-inline-block">Copyright &copy; {{ date("Y") }}
                            {{__('dashboard.all_rights_reserved')}} </span>
                    </div>
                </footer>
            </div>
        </div>
        <!-- Supplier Video Modal -->
        <div class="modal fade" id="videoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header">
                        <h5 class="modal-title text-white" id="exampleModalToggleLabel"></h5>
                        <button type="button" class="btn-close btn-close-white" id="stopVideoNow" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="showVideoHere">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                </div>
            </div>
        </div>
    <!-- / Supplier Video Modal -->
        <script src="{{ URL::asset('/assets/vendors/base/vendor.bundle.base.js') }}"></script>
        <script src="{{ URL::asset('/assets/vendors/sweetalert/sweetalert.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/parsley.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/custom/otp.js') }}"></script>
        <script type="text/javascript">
            $(document).on("click", ".supplierVideo", function () {
                let videoLink = ($("#langValue").val() == "en" ? $(this).attr("data-video-en") : $(this).attr("data-video-id"));
                let videoTitle = $(this).attr("data-video-title");
                let video = '<div class="embed-responsive embed-responsive-16by9">\
                    <iframe id="suppYoutube" class="embed-responsive-item suppYoutube" allowscriptaccess="always" width="100%" height="500" src="'+videoLink+'" allowfullscreen></iframe>\
                </div>';
                $("#exampleModalToggleLabel").text(videoTitle);
                $('#showVideoHere').html(video);
                $("#videoModal").modal('show');
            });
            //resend mail
            $(document).on("click", ".js-sendemail", function() {
                $.ajax({
                    url: "{{ route('profile-invite-supplier-verify') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                         user_email: $('#hemail').val(),
                    },
                    success: function (successData) {
                        new PNotify({
                            text: '{{ __('admin.invitation_send_successfully') }}',
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'slow',
                            delay: 3000,
                        });
                        location.reload();

                    },
                    error: function () {
                        console.log('error');
                    }
                });
            })
        </script>
        @endif
    </body>
</html>
