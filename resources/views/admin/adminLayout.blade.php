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
    <link rel="stylesheet" href="{{ URL::asset('/assets/vendors/datatables.net-bs4/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/assets/vendors/jquery-toast-plugin/jquery.toast.min.css') }}">
    <link href="{{ URL::asset('front-assets/css/front/searchPanes.dataTables.min.css') }}" rel="stylesheet">
        {{--  <!-- <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css"> -->--}}
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

    <link rel="stylesheet" href="{{ URL::asset('assets/css/admin/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('front-assets/css/front/fontawesome_5.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('chat/css/adminChat.css') }}">

    <link rel="stylesheet" href="{{ URL::asset('/assets/css/admin/datetime/nmp_flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/assets/css/admin/datetime/flatpickr.min.css') }}">


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
            /* .fa-chain {
                background-color: #f6f6f6;
                width: 20px;
                height: 20px;
                text-align: center;
                border-radius: 4px;
                line-height: 20px;
            } */

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
                             {{ __('profile.mailnotification') }}
                             <a href="javascript:void(0);" style="top: 36px !important;" class="js-sendemail" title="{{ __('profile.varify_your_mail') }}" >{{ __('admin.resend') }} {{ __('profile.Email') }}</a>
                        </div>
                    </div>
                </div>
                @endif
                <div class="text-start navbar-brand-wrapper d-flex align-items-center justify-content-between">
                    <a class="navbar-brand brand-logo" href="{{ route('admin-dashboard') }}"><img
                            src="{{ URL::asset('/home_design/images/blitznet-logo_w.svg') }}" alt="logo" class="w-auto"/></a>
                    <a class="navbar-brand brand-logo-mini" href="{{ route('admin-dashboard') }}"><img
                            src="{{ URL::asset('/home_design/images/icons/blitznet-white.svg') }}" alt="logo" /></a>
                    <button class="navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                        <span class="mdi mdi-menu"></span>
                    </button>
                </div>

                @php
                    $notifications = getNotificationsCountAndView();
                @endphp
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

                            <!-- Listing for Videos -->
                            <li class="nav-item nav-search mx-1">
                                <div id="supplier_video_tutorial" class="position-relative">
                                <button type="button" class="btn dropdown-toggle p-4" style="min-width: inherit;" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('dashboard.guide') }}">
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
                            <!-- /End -->
                        @endif
                        <li class="nav-item nav-search d-none d-lg-flex ms-1">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="search">
                                        <i class="mdi mdi-magnify"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="{{__('admin.type_to_search')}}"
                                    aria-label="search" aria-describedby="search">
                            </div>
                        </li>
                        @can('publish notifications')
                        <li class="nav-item dropdown" onclick="counterRemove()">
                            <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                                <i class="mdi mdi-bell-outline mx-0 {{ $notifications['counts'] == 0 ? '': 'bell_animation' }}" id="bell_animation"></i>
                                <p class="notification-ripple notification-ripple-bg {{ $notifications['counts'] == 0 ? 'd-none': '' }}" id="notification_counts"><span data-count_notification="{{ $notifications['counts'] }}">{{ ChangeCount($notifications['counts']) }}</span></p>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                                <p class="mb-0 fw-normal float-left dropdown-header border-bottom">{{ __('admin.notification')}}</p>
                                <div id="dropdownListNotification">
                                    {!! $notifications['notificationDropDownView'] !!}
                                </div>
                            </div>
                        </li>
                        @endcan
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
                                    {{-- __('admin.change_password') --}}
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
            <div class="container-fluid page-body-wrapper">
                <!-- partial:partials/_settings-panel.html -->
                <div class="theme-setting-wrapper">
                    <div id="settings-trigger"><i class="mdi mdi-settings"></i></div>
                    <div id="theme-settings" class="settings-panel">
                        <i class="settings-close mdi mdi-close"></i>
                        <p class="settings-heading">SIDEBAR SKINS</p>
                        <div class="sidebar-bg-options" id="sidebar-light-theme">
                            <div class="img-ss rounded-circle bg-light border me-3"></div>
                            Light
                        </div>
                        <div class="sidebar-bg-options selected" id="sidebar-dark-theme">
                            <div class="img-ss rounded-circle bg-dark border me-3"></div>
                            Dark
                        </div>
                        <p class="settings-heading mt-2">HEADER SKINS</p>
                        <div class="color-tiles mx-0 px-4">
                            <div class="tiles success"></div>
                            <div class="tiles warning"></div>
                            <div class="tiles danger"></div>
                            <div class="tiles primary"></div>
                            <div class="tiles info"></div>
                            <div class="tiles dark"></div>
                            <div class="tiles default"></div>
                        </div>
                    </div>
                </div>
                <!-- partial -->
                <!-- partial:partials/_sidebar.html -->
                <nav class="sidebar sidebar-offcanvas" id="sidebar">
                    <ul class="nav h-100">
                        <li class="nav-item nav-profile">
                            <div class="nav-link d-flex">
                                <div class="profile-image">
									@if(auth()->check() && auth()->user()->profile_pic)
										<img src="{{ URL::asset('storage/'.auth()->user()->profile_pic) }}" alt="image" id="layout_profile_img">
									@else
										<img src="{{ URL::asset('/assets/images/user.png') }}" alt="image" id="layout_profile_img">
									@endif
                                </div>

								<div class="profile-name">
                                    <p class="name text-truncate" style="line-height: 1.1rem; width:130px" id="left_sidebar_name" style="width:130px">
                                        {{ucfirst(Auth::user()->firstname)}}
                                    </p>
                                    <p class="left_sidebar_designation mb-0" style="line-height: 1.1rem;">
										@php
											$designation = null;
											if(auth()->user()->designation){
												$des_record = App\Models\Designation::where('id', auth()->user()->designation)->get()->first();
												if($des_record){
													$designation = $des_record->name;
												}
											}
										@endphp

										@if(auth()->user()->role_id == 3)
											{{$designation ?? 'Supplier'}}
										@else
											{{$designation ?? 'Manager'}}
										@endif
                                    </p>
									<p class="company-name mb-0" style="line-height: 1.1rem;" id="left_sidebar_companyname">

										@if(auth()->check() && auth()->user()->role_id == 3)
											@if(auth()->user()->company && auth()->user()->company->name)
												{{auth()->user()->company->name}}
											@endif
										@else
											Blitznet
										@endif

                                    </p>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin-dashboard') }}">
                                <i class="mdi mdi-shield-check menu-icon"></i>
                                <span class="menu-title">{{ __('admin.dashboard') }}</span>
                            </a>
                        </li>
                        @if (Auth::user()->role_id == App\Models\Role::SUPPLIER)
                            @can('edit supplier list')
                                <li class="nav-item" @if( in_array(Route::getCurrentRoute()->action['as'], ['supplier.profile.index'])) data-active="active" @endif>
                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'supplier.profile.index') data-active="active" @endif href="{{ route('supplier.profile.index') }}">
                                        <i class="fa fa-user pe-2"></i>
                                        <span class="menu-title">{{ __('admin.supplier_details') }}</span>
                                    </a>
                                </li>
                            @endcan

                            @can('publish products')
                                <li class="nav-item" @if( in_array(Route::getCurrentRoute()->action['as'], ['supplier-porduct-list', 'edit-supplier-product', 'add-supplier-product'])) data-active="active" @endif>
                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'supplier-porduct-list' || Route::getCurrentRoute()->action['as'] == 'edit-supplier-product' || Route::getCurrentRoute()->action['as'] == 'add-supplier-product') data-active="active" @endif href="{{ route('supplier-porduct-list') }}">
                                        <i class="fa fa-shopping-cart pe-2"></i>
                                        <span class="menu-title">{{__('admin.products')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @canany(['publish invite supplier', 'publish invite buyer'])
                                <li class="nav-item" @if( in_array(Route::getCurrentRoute()->action['as'], ['invite-supplier-list', 'invite-buyer-list'])) data-active="active" @endif>
                                    <a class="nav-link" data-bs-toggle="collapse" href="#invite-nav" @if( in_array(Route::getCurrentRoute()->action['as'], ['invite-supplier-list', 'invite-buyer-list'])) aria-expanded="true" @endif>
                                        <i class="fa fa-user-plus pe-2"></i>
                                            <span class="menu-title">{{__('admin.invite')}}</span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="collapse" @if( in_array(Route::getCurrentRoute()->action['as'], ['invite-supplier-list', 'invite-buyer-list'])) data-collapse="show" @endif id="invite-nav">
                                      <ul class="nav flex-column sub-menu">
                                            @can('publish invite buyer')
                                                <li class="nav-item">
                                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'invite-buyer-list') data-active="active" @endif href="{{ route('invite-buyer-list') }}">
                                                        {{-- <i class="fa fa-user-plus pe-2"></i> --}}
                                                        <span>{{__('admin.invite_buyer')}}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                            @endcanany
                        @endif

                            @canany(['publish category', 'publish sub-category', 'publish products', 'publish brands', 'publish units', 'publish charges', 'publish available banks', 'publish department', 'publish designation', 'publish term-and-conditions', 'payment terms', 'payment groups', 'banks-list', 'department-list', 'designation-list'])
                                <li class="nav-item" @if( in_array(Route::getCurrentRoute()->action['as'], ['categories-list', 'sub-categories-list', 'products-list', 'brands-list', 'units-list', 'charges-list', 'banks-list', 'department-list', 'designation-list', 'terms-conditions', 'payment-term-list', 'payment-group-list'])) data-active="active" @endif >
                                    @if (Auth::user()->role_id != App\Models\Role::SUPPLIER)
                                        <a class="nav-link" data-bs-toggle="collapse" href="#masters" @if( in_array(Route::getCurrentRoute()->action['as'], ['categories-list','sub-categories-list', 'products-list', 'brands-list', 'units-list', 'charges-list'])) aria-expanded="true" @endif >
                                            <i class="fa fa-cogs pe-2"></i>
                                            <span class="menu-title">{{__('admin.masters')}}</span>
                                            <i class="menu-arrow" ></i>
                                        </a>
                                    @endif
                                    <div class="collapse" id="masters" @if( in_array(Route::getCurrentRoute()->action['as'], ['categories-list', 'category-edit', 'category-add', 'sub-categories-list', 'sub-category-add', 'sub-category-edit', 'products-list', 'product-add', 'product-edit', 'brands-list', 'brand-add', 'brand-edit', 'units-list', 'unit-add', 'unit-edit', 'charges-list', 'charge-add', 'charge-edit', 'banks-list', 'department-list', 'department-add', 'department-edit', 'designation-list', 'designation-add', 'designation-edit', 'terms-conditions', 'payment-term-list','payment-term-add','payment-term-edit', 'payment-group-list', 'payment-group-add', 'payment-group-edit'])) data-collapse="show" @endif  @if (Auth::user()->role_id == App\Models\Role::SUPPLIER) style="display:none" @endif>
                                        <ul class="nav flex-column sub-menu">
                                            @can('publish category')
                                                <li class="nav-item" data-name="Name">
                                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'categories-list' || Route::getCurrentRoute()->action['as'] == 'category-add' || Route::getCurrentRoute()->action['as'] == 'category-edit') data-active="active" @endif href="{{ route('categories-list') }}">
                                                        {{__('admin.categories')}}
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('publish sub-category')
                                                <li class="nav-item">
                                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'sub-categories-list' || Route::getCurrentRoute()->action['as'] == 'sub-category-add' || Route::getCurrentRoute()->action['as'] == 'sub-category-edit')  data-active="active" @endif href="{{ route('sub-categories-list') }}">
                                                        {{__('admin.sub_categories')}}
                                                    </a>
                                                </li>
                                            @endcan

                                            @if (Auth::user()->role_id != App\Models\Role::SUPPLIER)
                                                @can('publish products')
                                                    <li class="nav-item">
                                                        <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'products-list' || Route::getCurrentRoute()->action['as'] == 'product-add' || Route::getCurrentRoute()->action['as'] == 'product-edit') data-active="active" @endif href="{{ route('products-list') }}">
                                                            {{-- <i class="fa fa-shopping-cart pe-2"></i> --}}
                                                            <span>{{__('admin.products')}}</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            @endif


                                            @can('publish brands')
                                                <li class="nav-item">
                                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'brands-list' || Route::getCurrentRoute()->action['as'] == 'brand-add' || Route::getCurrentRoute()->action['as'] == 'brand-edit') data-active="active" @endif href="{{ route('brands-list') }}">
                                                        {{__('admin.brands')}}
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('publish units')
                                                <li class="nav-item">
                                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'units-list' || Route::getCurrentRoute()->action['as'] == 'unit-add' || Route::getCurrentRoute()->action['as'] == 'unit-edit') data-active="active" @endif href="{{ route('units-list') }}">
                                                        {{__('admin.units')}}
                                                    </a>
                                                </li>
                                            @endcan


                                            @can('publish charges')
                                                <li class="nav-item">
                                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'charges-list' || Route::getCurrentRoute()->action['as'] == 'charge-add' || Route::getCurrentRoute()->action['as'] == 'charge-edit') data-active="active" @endif href="{{ route('charges-list') }}">
                                                        {{__('admin.charges')}}
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('publish available banks')
                                                <li class="nav-item">
                                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'banks-list') data-active="active" @endif href="{{ route('banks-list') }}">
                                                        {{__('admin.available_banks')}}
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('publish department')
                                                <li class="nav-item">
                                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'department-list' || Route::getCurrentRoute()->action['as'] == 'department-add' || Route::getCurrentRoute()->action['as'] == 'department-edit') data-active="active" @endif href="{{ route('department-list') }}">
                                                        {{-- <i class="fa fa-users pe-2"></i> --}}
                                                        <span>{{__('admin.department')}}</span>
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('publish designation')
                                                <li class="nav-item">
                                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'designation-list' || Route::getCurrentRoute()->action['as'] == 'designation-add' || Route::getCurrentRoute()->action['as'] == 'designation-edit') data-active="active" @endif href="{{ route('designation-list') }}">
                                                        {{-- <i class="fa fa-address-card pe-2"></i> --}}
                                                        <span>{{__('admin.designation')}}</span>
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('publish term-and-conditions')
                                                <li class="nav-item">
                                                    <a class="nav-link text-wrap" @if(Route::getCurrentRoute()->action['as'] == 'terms-conditions') data-active="active" @endif href="{{ route('terms-conditions') }}">
                                                        {{-- <i class="fa fa-user pe-2"></i> --}}
                                                        <span>{{__('admin.commercial_terms')}}</span>
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('publish payment terms')
                                                <li class="nav-item">
                                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'payment-term-list' || Route::getCurrentRoute()->action['as'] == 'payment-term-add' || Route::getCurrentRoute()->action['as'] == 'payment-term-edit') data-active="active" @endif href="{{ route('payment-term-list') }}">
                                                        {{-- <i class="fa fa-legal pe-2"></i> --}}
                                                        <span>{{__('admin.payment_terms')}}</span>
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('publish payment groups')
                                                <li class="nav-item">
                                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'payment-group-list' || Route::getCurrentRoute()->action['as'] == 'payment-group-add' || Route::getCurrentRoute()->action['as'] == 'payment-group-edit') data-active="active" @endif href="{{ route('payment-group-list') }}">
                                                        {{-- <i class="fa fa-credit-card pe-2"></i> --}}
                                                        <span>{{__('admin.payment_groups')}}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                            @endcanany

                            @can('publish users')
                                <li class="nav-item" @if( in_array(Route::getCurrentRoute()->action['as'], ['user-list'])) data-active="active" @endif>
                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'user-list') data-active="active" @endif href="{{ route('user-list') }}">
                                        <i class="fa fa-user pe-2"></i>
                                        <span class="menu-title">{{__('admin.users')}}</span>
                                    </a>
                                </li>
                            @endcan

                            @canany(['publish invite supplier', 'publish invite buyer'])
                                <li class="nav-item" @if( in_array(Route::getCurrentRoute()->action['as'], ['invite-supplier-list', 'invite-supplier-edit', 'invite-buyer-list', 'invite-buyer-edit'])) data-active="active" @endif>
                                    @if (Auth::user()->role_id != App\Models\Role::SUPPLIER)

                                    <a class="nav-link" data-bs-toggle="collapse" href="#invite-nav" @if( in_array(Route::getCurrentRoute()->action['as'], ['invite-supplier-list', 'invite-buyer-list'])) aria-expanded="true" @endif>
                                        <i class="fa fa-user-plus pe-2"></i>
                                            <span class="menu-title">{{__('admin.invite')}}</span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    @endif
                                    <div class="collapse" @if( in_array(Route::getCurrentRoute()->action['as'], ['invite-supplier-list', 'invite-supplier-edit', 'invite-buyer-list', 'invite-buyer-edit'])) data-collapse="show" @endif id="invite-nav" @if (Auth::user()->role_id == App\Models\Role::SUPPLIER) style="display:none" @endif>
                                        <ul class="nav flex-column sub-menu">
                                            @can('publish invite supplier')
                                                <li class="nav-item">
                                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'invite-supplier-list' || Route::getCurrentRoute()->action['as'] == 'invite-supplier-edit') data-active="active" @endif href="{{ route('invite-supplier-list') }}">
                                                        {{-- <i class="fa fa-user-plus pe-2"></i> --}}
                                                        <span>{{__('admin.invite_supplier')}}</span>
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('publish invite buyer')
                                                <li class="nav-item">
                                                    @if (Auth::user()->role_id != App\Models\Role::SUPPLIER)
                                                        <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'invite-buyer-list' || Route::getCurrentRoute()->action['as'] == 'invite-buyer-edit') data-active="active" @endif href="{{ route('invite-buyer-list') }}">
                                                            {{-- <i class="fa fa-user-plus pe-2"></i> --}}
                                                            <span>{{__('admin.invite_buyer')}}</span>
                                                        </a>
                                                    @endif
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                            @endcanany

                            @canany(['publish supplier list', 'publish supplier transaction charges'])
                            <li class="nav-item" @if( in_array(Route::getCurrentRoute()->action['as'], ['admin.supplier.index', 'supplier-add', 'supplier-edit', 'supplier-charge-list'])) data-active="active" @endif>
                                    <a class="nav-link" data-bs-toggle="collapse" href="#shop-list-nav" @if( in_array(Route::getCurrentRoute()->action['as'], ['admin.supplier.index', 'supplier-charge-list'])) aria-expanded="true" @endif>
                                        <i class="fa fa-cubes pe-2"></i>
                                        <span class="menu-title">{{__('admin.suppliers')}}</span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="collapse" id="shop-list-nav" @if( in_array(Route::getCurrentRoute()->action['as'], ['admin.supplier.index', 'supplier-add', 'supplier-edit', 'supplier-charge-list'])) data-collapse="show" @endif style="">
                                        <ul class="nav flex-column sub-menu">
                                            @can('publish supplier list')
                                                <li class="nav-item">
                                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'admin.supplier.index' || Route::getCurrentRoute()->action['as'] == 'supplier-add' || Route::getCurrentRoute()->action['as'] == 'supplier-edit') data-active="active" @endif href="{{ route('admin.supplier.index') }}">
                                                        {{__('admin.suppliers_list')}}
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('publish supplier transaction charges')
                                                <li class="nav-item">
                                                    <a class="nav-link text-wrap" @if(Route::getCurrentRoute()->action['as'] == 'supplier-charge-list') data-active="active" @endif href="{{ route('supplier-charge-list') }}">{{__('admin.supplier_transaction_charges')}} </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                            @endcanany


                            @can('publish buyers')
                                <li class="nav-item" @if( in_array(Route::getCurrentRoute()->action['as'], ['buyer-list', 'buyer-edit'])) data-active="active" @endif>
                                    <a class="nav-link" style="height: 31px" data-bs-toggle="collapse" @if( in_array(Route::getCurrentRoute()->action['as'], ['buyer-list', 'buyer-edit'])) data-active="active" @endif href="#buyer-nav">
                                        <i class="mdi mdi-account-tie pe-2"></i>
                                            <span class="menu-title">{{__('admin.buyers')}}</span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="collapse" id="buyer-nav" @if( in_array(Route::getCurrentRoute()->action['as'], ['buyer-list', 'buyer-edit'])) data-collapse="show" @endif style="">
                                        <ul class="nav flex-column sub-menu">
                                            @can('publish buyer list')
                                                <li class="nav-item">
                                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'buyer-list' || Route::getCurrentRoute()->action['as'] == 'buyer-edit') data-active="active" @endif href="{{ route('admin.buyer.company.list') }}">
                                                        {{__('admin.company_list')}}
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                            @endcan

                        @if(Auth::user()->role_id == 1)
                            <li class="nav-item"  @if( in_array(Route::getCurrentRoute()->action['as'], ['limits-index', 'limit-edit', 'loans-index', 'loan-edit','admin.credit.disbursement', 'admin.payments.upcoming.payments'])) data-active="active" @endif>
                                <a class="nav-link"  style="height: 31px" data-bs-toggle="collapse" href="#limit-nav">
                                    <i class="mdi mdi-account-tie pe-2"></i>
                                    <span class="menu-title">{{__('admin.limit')}}</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="collapse" @if( in_array(Route::getCurrentRoute()->action['as'], ['limits-index', 'limit-edit', 'loans-index', 'loan-edit','admin.credit.disbursement', 'admin.payments.upcoming.payments'])) data-collapse="show" @endif id="limit-nav" style="">
                                    <ul class="nav flex-column sub-menu">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{route('limits-index')}}" @if(Route::getCurrentRoute()->action['as'] == 'limits-index' || Route::getCurrentRoute()->action['as'] == 'limit-edit') data-active="active" @endif>{{__('admin.limit_application')}}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{route('loans-index')}}" @if(Route::getCurrentRoute()->action['as'] == 'loans-index' || Route::getCurrentRoute()->action['as'] == 'loan-edit') data-active="active" @endif>{{__('admin.loan_application')}}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{route('admin.payments.upcoming.payments')}}" @if(Route::getCurrentRoute()->action['as'] == 'admin.payments.upcoming.payments') data-active="active" @endif>{{__('admin.upcoming_payments')}}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{route('admin.credit.disbursement')}}" @if(Route::getCurrentRoute()->action['as'] == 'admin.credit.disbursement') data-active="active" @endif>{{__('admin.disbursement')}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        <!-- <li class="nav-item">
                            <a class="nav-link" href="{{ route('products-list') }}">
                                <i class="fa fa-shopping-cart pe-2"></i>
                                <span class="menu-title">{{__('admin.products')}}</span>
                            </a>
                        </li> -->

                        @can('publish rfqs')
                            <li class="nav-item" @if( in_array(Route::getCurrentRoute()->action['as'], ['rfq-list', 'group-rfq-list'])) data-active="active" @endif>
                                <a class="nav-link"  data-bs-toggle="collapse" href="#rfqs" @if( in_array(Route::getCurrentRoute()->action['as'], ['rfq-list', 'group-rfq-list'])) aria-expanded="true" @endif>
                                    <i class="fa fa-truck pe-2"></i>
                                        <span class="menu-title">{{__('admin.rfqs')}}</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="collapse" id="rfqs" @if( in_array(Route::getCurrentRoute()->action['as'], ['rfq-list', 'rfq-edit', 'group-rfq-list'])) data-collapse="show" @endif>
                                    <ul class="nav flex-column sub-menu">
                                        <li class="nav-item">
                                            <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'rfq-list' || Route::getCurrentRoute()->action['as'] == 'rfq-edit') data-active="active" @endif href="{{ route('rfq-list') }}">
                                                <span>{{__('admin.rfqs')}}</span>
                                                @can('publish notifications')
                                                    <span class="badge badge-info ms-auto p-1 {{ $notifications['rfqs'] == 0 ? 'd-none': '' }}" id="rfqCounts">{{ $notifications['rfqs'] }}</span>
                                                @endcan
                                            </a>
                                        </li>
                                        @can('publish group rfqs')
                                            <li class="nav-item">
                                                <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'group-rfq-list') data-active="active" @endif href="{{ route('group-rfq-list') }}">
                                                    <span>{{__('admin.group_rfq')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                            </li>
                        @endcan

                        @can('publish quotes')
                            <li class="nav-item" @if(in_array(Route::getCurrentRoute()->action['as'], ['quotes-list'])) data-active="active" @endif @if( in_array(Route::getCurrentRoute()->action['as'], ['quotes-edit'])) data-active="active" @endif>
                                <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'quotes-list' || Route::getCurrentRoute()->action['as'] == 'quotes-edit') data-active="active" @endif href="{{ route('quotes-list') }}">
                                    <i class="fa fa-check-square-o pe-2"></i>
                                    <span class="menu-title">{{__('admin.quotes')}}</span>
                                    @can('publish notifications')
                                        <span class="badge badge-info ms-auto  {{ $notifications['quotes'] == 0 ? 'd-none': '' }}" id="quoteCounts">{{ $notifications['quotes'] }}</span>
                                    @endcan
                                </a>
                            </li>
                        @endcan
                        @if(auth()->user()->role_id == 1 || Auth::user()->hasRole('jne'))
                        @can('publish check-price')
                                <li class="nav-item" @if(in_array(Route::getCurrentRoute()->action['as'], ['check-price'])) data-active="active" @endif>
                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'check-price') data-active="active" @endif href="{{ route('check-price') }}">
                                        <i class="fa fa-calculator pe-2"></i>
                                        <span class="menu-title">{{__('admin.check_price')}}</span>
                                    </a>
                                </li>
                        @endcan
                        @can('publish shipping-label')
                                <li class="nav-item" @if(in_array(Route::getCurrentRoute()->action['as'], ['shipping-label'])) data-active="active" @endif>
                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'shipping-label') data-active="active" @endif href="{{ route('shipping-label') }}">
                                        <i class="fa fa-tag pe-2"></i>
                                        <span class="menu-title">{{__('admin.shipping_label')}}</span>
                                    </a>
                                </li>
                        @endcan
                        @endif
                        @if (Auth::user()->role_id == App\Models\Role::SUPPLIER)
                            @can('publish order list')
                            <li class="nav-item" @if( in_array(Route::getCurrentRoute()->action['as'], ['order-list', 'order-edit'])) data-active="active" @endif>
                                <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'order-list') data-active="active" @endif href="{{ route('order-list') }}">
                                    <i class="fa fa-truck pe-2"></i>
                                    <span class="menu-title">{{__('admin.orders')}}</span>
                                    <span class="badge badge-info ms-auto {{ $notifications['orders'] == 0 ? 'd-none': '' }}" id="orderCounts">{{ $notifications['orders'] }}</span>
                                </a>
                            </li>
                        @endcan
                        @endif
                        @can('publish orders')
                                <li class="nav-item submenu-parent" @if( in_array(Route::getCurrentRoute()->action['as'], ['order-list', 'transactions-list', 'group-transactions-list', 'disbursements-list'])) data-active="active" @endif aria-expanded="false">
                                    <a class="nav-link" data-bs-toggle="collapse" href="#order" @if( in_array(Route::getCurrentRoute()->action['as'], ['order-list', 'transactions-list', 'group-transactions-list', 'disbursements-list'])) aria-expanded="true" @endif>
                                        <i class="fa fa-truck pe-2"></i>
                                        <span class="menu-title">{{__('admin.orders')}}</span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="collapse" id="order" @if( in_array(Route::getCurrentRoute()->action['as'], ['order-list', 'transactions-list', 'group-transactions-list', 'disbursements-list'])) data-collapse="show" @endif style="">
                                        <ul class="nav flex-column sub-menu">
                                            @can('publish order list')
                                                <li class="nav-item">
                                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'order-list') data-active="active" @endif href="{{ route('order-list') }}"> {{__('admin.orders_list')}}
                                                        @can('publish notifications')
                                                            <span class="badge badge-info ms-auto {{ $notifications['orders'] == 0 ? 'd-none': '' }}" id="orderCounts">{{ $notifications['orders'] }}</span>
                                                        @endcan
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('publish transaction list')
                                                <li class="nav-item">
                                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'transactions-list') data-active="active" @endif href="{{ route('transactions-list') }}"> {{__('admin.transaction_list')}}</a>
                                                </li>
                                            @endcan

                                            @can('publish group transaction list')
                                            <li class="nav-item">
                                                <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'group-transactions-list') data-active="active" @endif href="{{ route('group-transactions-list') }}"> {{__('admin.group_transaction_list')}}</a>
                                            </li>
                                            @endcan

                                            @can('publish disbursement list')
                                                <li class="nav-item">
                                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'disbursements-list') data-active="active" @endif href="{{ route('disbursements-list') }}"> {{__('admin.disbursement_list')}} </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                            @endcan

                        @can('publish finance tab')
                            <li class="nav-item" @if(in_array(Route::getCurrentRoute()->action['as'], ['admin.finance.index'])) data-active="active" @endif @if( in_array(Route::getCurrentRoute()->action['as'], ['admin.finance.index'])) data-active="active" @endif>
                                <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'admin.finance.index') data-active="active" @endif href="{{ route('admin.finance.index') }}">
                                    <i class="fa fa-calculator pe-2"></i>
                                    <span class="menu-title">{{__('admin.finance')}}</span>
                                </a>
                            </li>
                        @endcan

                            @can('publish group trading')
                                <li class="nav-item" @if(in_array(Route::getCurrentRoute()->action['as'], ['groups-list'])) data-active="active" @endif>
                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'groups-list') data-active="active" @endif href="{{ route('groups-list') }}">
                                        <i class="fa fa-th-large pe-2"></i>
                                        <span class="menu-title">{{__('admin.groups')}}</span>
                                    </a>
                                </li>
                            @endcan

                            @canany(['publish subscribed users', 'publish newsletter users', 'publish contact'])
                                <li class="nav-item" @if( in_array(Route::getCurrentRoute()->action['as'], ['subscriber-list', 'newsletter-list', 'contact'])) data-active="active" @endif>
                                    <a class="nav-link"  data-bs-toggle="collapse" href="#subscribe" @if( in_array(Route::getCurrentRoute()->action['as'], ['subscriber-list', 'newsletter-list', 'contact'])) aria-expanded="true" @endif>
                                        <i class="fa fa-id-card-o pe-2"></i>
                                            <span class="menu-title">{{__('admin.subscribe')}}</span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="collapse" id="subscribe" @if( in_array(Route::getCurrentRoute()->action['as'], ['subscriber-list', 'newsletter-edit', 'newsletter-list', 'contact'])) data-collapse="show" @endif style="">
                                        <ul class="nav flex-column sub-menu">
                                            @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || Auth::user()->hasRole('agent'))
                                                @can('publish subscribed users')
                                                    <li class="nav-item">
                                                        <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'subscriber-list') data-active="active" @endif href="{{ route('subscriber-list') }}">
                                                            {{-- <i class="fa fa-user pe-2"></i> --}}
                                                            <span >{{__('admin.subscribed_users')}}</span>
                                                        </a>
                                                    </li>
                                                @endcan

                                                @can('publish newsletter users')
                                                    <li class="nav-item">
                                                        <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'newsletter-list' || Route::getCurrentRoute()->action['as'] == 'newsletter-edit') data-active="active" @endif href="{{ route('newsletter-list') }}">
                                                            {{-- <i class="fa fa-user pe-2"></i> --}}
                                                            <span>{{__('admin.newsletters_users')}}</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            @endif

                                            @can('publish contact')
                                                <li class="nav-item">
                                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'contact') data-active="active" @endif href="{{ route('contact') }}">
                                                        {{-- <i class="fa fa-envelope-open pe-2"></i> --}}
                                                        <span>{{__('admin.contact')}}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                            @endcanany


                        {{-- ekta --}}
                        @if (Auth::user()->role_id == App\Models\Role::SUPPLIER)
                            @can('publish supplier address')
                            <li class="nav-item" @if(in_array(Route::getCurrentRoute()->action['as'], ['supplier-address-list', 'add-address'])) data-active="active" @endif>
                                <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'supplier-address-list' || Route::getCurrentRoute()->action['as'] == 'add-address') data-active="active" @endif href="{{ route('supplier-address-list') }}">
                                    <i class="fa fa-map-marker pe-2"></i>
                                    <span class="menu-title">{{ __('admin.Address')}}</span>
                                </a>
                            </li>
                        @endcan
                        @endif

                        @can('publish notifications')
                            <li class="nav-item" @if(in_array(Route::getCurrentRoute()->action['as'], ['notifications-list'])) data-active="active" @endif>
                                <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'notifications-list') data-active="active" @endif href="{{ route('notifications-list') }}">
                                    <i class="fa fa-bell pe-2"></i>
                                    <span class="menu-title">{{ __('admin.notification')}}</span>
                                </a>
                            </li>
                        @endcan

                        @if(auth()->user()->role_id == 1)
                            @can('publish translations')
                                <li class="nav-item">
                                    <a class="nav-link" @if(Route::getCurrentRoute()->action['as'] == 'translations-view') data-active="active" @endif href="{{ route('translations-view') }}">
                                        <i class="fa fa-language pe-2"></i>
                                        <span class="menu-title">{{__('admin.translations')}}</span>
                                    </a>
                                </li>
                            @endcan
                        @endif
                        @php
                            $dotsCount = getUnreadMessageAdminCount();
                        @endphp
                        @can('publish group-chat')
                                <a href="{{ route('chat-view-ajax') }}" class="btn chat-bg mb-3" type="button">
{{--                                <a href="javascript:void(0)" onclick="chat.chatAdminChatTypeData('{{ route('chat-view') }}', 'Rfq')" class="btn chat-bg mb-3" type="button">--}}
                                    <img src="{{ URL::asset('/chat/images/Group22.png') }}" alt="">
                                    <span class="chatName text-white">
                                    <span class="newMessage">
                                    <span class="visually-hidden">New alerts</span>
                                    </span>{{__('admin.chat')}}</span>
                                    @if($dotsCount > 0)
                                        <span class="dots"></span>
                                    @endif
                                </a>
                        @endcan

                    </ul>
                </nav>

                <!-- partial -->
                <div class="main-panel">
                    <div class="content-wrapper">

                        @yield('content')

                    </div>
                    <!-- content-wrapper ends -->
                    <!-- partial:partials/_footer.html -->
                    <div class="footer-wrapper">
                        <footer class="footer">
                            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                                <span class="text-center text-sm-left d-block d-sm-inline-block">Copyright &copy; {{ date("Y") }}
                                    {{__('dashboard.all_rights_reserved')}} </span>
                            </div>
                        </footer>
                    </div>


                    <!-- partial -->
                    <!-- main-panel ends -->
                </div>
                <!-- page-body-wrapper ends -->
            </div>
        @else
            <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row"
                style="flex-direction: column !important;margin-top: 10px;">
                <div class="">
                    <img src="{{ URL::asset('/assets/images/front/site-logo.png') }}" alt="">
                </div>

            </nav>
            <div class="container-fluid page-body-wrapper" style="background-color: #F7C102; display:block;">
                <div class="">
                    <div class="content-wrapper">
                        @yield('content')
                    </div>
                    <!-- content-wrapper ends -->
                    <!-- partial:partials/_footer.html -->
                    <div class="footer-wrapper">
                        <footer class="footer">
                            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                                <span class="text-center text-sm-left d-block d-sm-inline-block">Copyright &copy; 2021
                                    {{__('dashboard.all_rights_reserved')}} </span>
                            </div>
                        </footer>
                    </div>
                    <!-- partial -->
                    <!-- main-panel ends -->
                </div>
        @endif

    </div>

    <div class="modal version2 fade" id="viewRfqModalnew" tabindex="-1" role="dialog"
         aria-labelledby="viewRfqModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <div class="modal version2 fade" id="viewRfqModal" tabindex="-1" role="dialog"
         aria-labelledby="viewRfqModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
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


    <!-- view loan modal -->
    <div class="modal version2 fade" id="viewLimitModal" tabindex="-1" role="dialog"
         aria-labelledby="viewLimitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!-- view loan modal -->

    <!-- view order modal -->
    <div class="modal version2 fade" id="staticBackdrop" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="singleOrderDetail">
            </div>
        </div>
    </div>
    <!-- view order modal -->

    @stack('top_scripts')

<script>
$(document).ready(function() {


    //$('[data-bs-toggle="tooltip"]').tooltip();

    @if ($message = Session::get('success'))
        $.toast({
            heading: 'Success',
            text: '{{ $message }}',
            icon: 'success',
            loaderBg: '#f96868',
            position: 'top-right'
        })
    @endif


    @if ($message = Session::get('error'))
        $.toast({
            heading: 'Error',
            text: '{{ $message }}',
            icon: 'error',
            loaderBg: '#f96868',
            position: 'top-right'
        })
    @endif

    @if ($message = Session::get('warning'))
        $.toast({
            heading: 'Warning',
            text: '{{ $message }}',
            icon: 'warning',
            loaderBg: '#f96868',
            position: 'top-right'
        })
    @endif

    @if ($message = Session::get('info'))
        $.toast({
            heading: 'Info',
            text: '{{ $message }}',
            icon: 'info',
            loaderBg: '#f96868',
            position: 'top-right'
        })
    @endif

    //getNotificationAjax();
    @if (config('app.env')=='live')
        var socket = io('https://blitznet.co.id:3000');
            @elseif(config('app.env')=='staging')
    var socket = io.connect("https://beta.blitznet.co.id:3000", { secure: true, reconnect: true, rejectUnauthorized : false });
            @else
        var socket = io('http://localhost:8890');
    @endif
    getSideCountAjax();
    socket.on("rfqs-notification-chanel:App\\Events\\rfqsEvent", function(){getNotificationAjax(); });
    socket.on("rfqs-count-chanel:App\\Events\\rfqsCountEvent", function(){getSideCountAjax('rfqs_count'); });
    socket.on("quotes-count-chanel:App\\Events\\quotesCountEvent", function(){getSideCountAjax('quotes_count'); });
    socket.on("orders-count-chanel:App\\Events\\ordersCountEvent", function(){getSideCountAjax('orders_count'); });
    socket.on("order-delivery-seprate:App\\Events\\OrderDeliverySeprate", function(){getSeprateNotificationAjax(); });
   // socket.on("airway-bill-notification:App\\Events\\AirwayBillNotification", function(){getAirwayBillNotificationAjax(); });

    $(document).on('click', '.vieQuoteDetail', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var id = $(this).attr('data-id');
        if (id) {
            $.ajax({
                url: "{{ route('quote-detail', '') }}" + "/" + id,
                type: 'GET',
                success: function(successData) {
                    $('#viewRfqModalnew').find('.modal-content').html(successData.quoteHTML);
                    $('#viewRfqModalnew').modal('show');
                },
                error: function() {
                    console.log('error');
                }
            });
        }
    });

    $(document).on('click', '.viewRfqDetail', function(e) {
        $('#callRFQHistory').html('');
        $('#message').html('');
        e.preventDefault();
        e.stopImmediatePropagation();
        var id = $(this).attr('data-id');
        console.log(id)
        if (id) {
            $.ajax({
                url: "{{ route('rfq-detail', '') }}" + "/" + id,
                type: 'GET',
                success: function(successData) {
                    $("#viewRfqModal").find(".modal-content").html(successData.rfqview);
                    $('#viewRfqModal').modal('show');
                },
                error: function() {
                    console.log('error');
                }
            });
        }
    });
});


function getNotificationAjax() {
    $.ajax({
        url: "{{ route('get-notification-count-ajax') }}" ,
        type: 'GET',
        dataType: 'json',
        success: function (successData) {
            var count, data_counter = '';
            if(successData.counts > 9){
                count = '9+';
                data_counter = successData.counts;
            } else {
                count = successData.counts;
                data_counter = successData.counts;
            }
            if(count != '') {
                $('#notification_counts').removeClass('d-none');
                $('#notification_counts span').html(count);
                $('#notification_counts span').attr('data-count_notification', data_counter)
                $('#dropdownListNotification').html(successData.notificationDropDownView)
            } else {
                $('#notification_counts span').attr('data-count_notification', data_counter)
                $('#dropdownListNotification').html(successData.notificationDropDownView)
            }
        }
    });
}

function getXenBalance(supplierId) {
    $.ajax({
        url: "{{ route('xen-balance','') }}" + "/" + supplierId,
        type: 'GET',
        dataType: 'json',
        success: function (successData) {
            $('#xen_balance').html('Rp '+(successData.data?successData.data:0));
        },
        error: function(error) {
            $('#xen_balance').html('Rp 0');
            $.toast({
                heading: 'Warning',
                text: error.responseJSON.message,
                showHideTransition: 'slide',
                icon: 'warning',
                loaderBg: '#57c7d4',
                position: 'top-right'
            })
            //console.log(error);
        }
    });
}
function getLoanBalance(supplierId) {
    $.ajax({
        url: "{{ route('loan-balance','') }}" + "/" + supplierId,
        type: 'GET',
        dataType: 'json',
        success: function (successData) {
            if(successData.data!=''){
                var amount = parseInt(successData.data).toLocaleString(undefined, {minimumFractionDigits: 2}) ;
            }else{
                amount = '0.00';
            }
            $('#loan_balance').html('Rp '+amount);
        },
        error: function(error) {
            $('#loan_balance').html('Rp 0.00');
            $.toast({
                heading: 'Warning',
                text: error.responseJSON.message,
                showHideTransition: 'slide',
                icon: 'warning',
                loaderBg: '#57c7d4',
                position: 'top-right'
            })
            //console.log(error);
        }
    });
}

function counterRemove() {
    if ($('#notification_counts span').attr('data-count_notification') != 0){
        $.ajax({
            url: "{{ route('remove-notification-count-ajax') }}" ,
            type: 'GET',
            dataType: 'json',
            success: function (successData) {
                $('#notification_counts').addClass('d-none');
                $('#notification_counts span').attr('data-count_notification', 0);
                $('#bell_animation').removeClass('bell_animation');
            }
        });
    }
}

function getSideCountAjax(count = 'all') {
    $.ajax({
        url: "{{ route('get-side-count-ajax','') }}" + "/" + count,
        type: 'GET',
        dataType: 'json',
        success: function (successData) {
            if (successData.rfqs != 0){
                $('#rfqCounts').removeClass('d-none');
                $('#rfqCounts').html('');
                $('#rfqCounts').html(successData.rfqs);
            }
            if (successData.quotes != 0){
                $('#quoteCounts').removeClass('d-none');
                $('#quoteCounts').html('');
                $('#quoteCounts').html(successData.quotes);
            }
            if (successData.orders != 0 ){
                $('#orderCounts').removeClass('d-none');
                $('#orderCounts').html('');
                $('#orderCounts').html(successData.orders);
            }

        },
        error: function(error) {

        }
    });
}
getSeprateNotificationAjax = () => {
    $.ajax({
        url: "{{ route('get-seprate-notification-ajax') }}" ,
        type: 'GET',
        dataType: 'json',
        success: function (successData) {
            var count, data_counter = '';
            if(successData.counts > 9){
                count = '9+';
                data_counter = successData.counts;
            } else {
                count = successData.counts;
                data_counter = successData.counts;
            }
            if(count != '') {
                $('#notification_counts').removeClass('d-none');
                $('#notification_counts span').html(count);
                $('#notification_counts span').attr('data-count_notification', data_counter)
                $('#dropdownListNotification').html(successData.notificationDropDownView)
            } else {
                $('#notification_counts span').attr('data-count_notification', data_counter)
                $('#dropdownListNotification').html(successData.notificationDropDownView)
            }
        }
    });
}
// getAirwayBillNotificationAjax = () => {
//     $.ajax({
//         url: "{{ route('get-airway-notification-ajax') }}" ,
//         type: 'GET',
//         dataType: 'json',
//         success: function (successData) {
//             var count, data_counter = '';
//             if(successData.counts > 9){
//                 count = '9+';
//                 data_counter = successData.counts;
//             } else {
//                 count = successData.counts;
//                 data_counter = successData.counts;
//             }
//             if(count != '') {
//                 $('#notification_counts').removeClass('d-none');
//                 $('#notification_counts span').html(count);
//                 $('#notification_counts span').attr('data-count_notification', data_counter)
//                 $('#dropdownListNotification').html(successData.notificationDropDownView)
//             } else {
//                 $('#notification_counts span').attr('data-count_notification', data_counter)
//                 $('#dropdownListNotification').html(successData.notificationDropDownView)
//             }
//         }
//     });
// }

</script>
    <!-- container-scroller -->
    <!-- base:js -->
    <script src="{{ URL::asset('/assets/vendors/base/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->

    <script src="{{ URL::asset('/assets/vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/vendors/flot/jquery.flot.js') }}"></script>
    <script src="{{ URL::asset('/assets/vendors/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ URL::asset('/assets/vendors/flot/curvedLines.js') }}"></script>
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="{{ URL::asset('/assets/js/off-canvas.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/template.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/settings.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/todolist.js') }}"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{ URL::asset('/assets/js/dashboard.js') }}"></script>
    <!-- End custom js for this page-->

    <!-- plugin js dataTables -->
    <script src="{{ URL::asset('/assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('/assets/vendors/datatables.net-bs4/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/vendors/datatables.net-bs4/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/vendors/datatables.net-bs4/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/vendors/datatables.net-bs4/buttons.html5.min.js') }}"></script>
    <!-- End plugin js for this page -->
    <!-- Custom js for dataTables-->
    <script src="{{ URL::asset('/assets/js/data-table.js') }}"></script>
    {{--<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->--}}
    <script src="{{ URL::asset('/assets/vendors/sweetalert/sweetalert.min.js') }}"></script>
    <!-- End datatable-->

    <script src="{{ URL::asset('assets/vendors/jquery-toast-plugin/jquery.toast.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/toastDemo.js') }}"></script>
    <script src="{{ URL::asset('assets/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <script src="{{ URL::asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/parsley.min.js') }}"></script>

    <script src="{{ URL::asset('assets/js/formpickers.js') }}"></script>
    <script src="{{ URL::asset('assets/js/x-editable.js') }}"></script>
    <script src="{{ URL::asset('assets/js/formpickers.js') }}"></script>
    {{-- <script src="{{ URL::asset('assets/vendors/jquery-steps/jquery.steps.min.js') }}"></script> --}}
    <script src="{{ URL::asset('assets/vendors/jquery-toast-plugin/jquery.toast.min.js') }}"></script>
    <script src="{{ URL::asset('front-assets/js/front/jquery.smartWizard.js') }}"></script>
    <script src="{{ URL::asset('assets/js/awesomplete.js') }}"></script>
    <script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.js') }}"></script>
    <script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.buttons.js') }}"></script>
    <script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.nonblock.js') }}"></script>

    <script src="{{ URL::asset('assets/js/filter-multi-select-bundle.min.js') }}"></script>
    {{--<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script> -->--}}
    <script src="{{ URL::asset('/assets/vendors/moment/moment.min.js') }}"></script>
    <script src="{{ URL::asset('assets/vendors/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
   {{--  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script> -->--}}
    <script src="{{ URL::asset('/assets/vendors/jquery-validation/jquery.validate.min.js') }}"></script>

    <script src="{{ URL::asset('front-assets/intlTelInput/js/intlTelInput.js') }}"></script>
    <script src="{{ URL::asset('front-assets/library/jquery-ui-1.12.1.custom/jquery-ui.js') }}"></script>

    <!---------------------------------------begin:App Common Js--------------------------------------------->
    <script src="{{ URL::asset('js/socket.js') }}"></script>
    <script>
        var group_chat_id_global = '';
        var userId = '{{ auth()->check() ? auth()->user()->id : 1 }}';
        var userRole = '{{  auth()->check() ? auth()->user()->role_id : 1 }}';
        var currentTime = '{{ changeTimeFormat(now()) }}';
        var adminNewChatStatusForRedirect = 0;
    </script>
    <script src="{{ URL::asset('js/moment.js') }}"></script>
    <script src="{{ URL::asset('assets/js/custom/app.js') }}"></script>
    <script src="{{ URL::asset('js/chat-feature.js') }}"></script>
    <script src="{{ URL::asset('assets/js/admin/datetime/flatpickr.js') }}"></script>
    <script src="{{ URL::asset('assets/js/admin/datetime/flatpickr.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/admin/datetime/npm_flatpickr.js') }}"></script>

    <!---------------------------------------begin:App Common Js--------------------------------------------->
    <script>
        flatpickr("input[type=datetime-local ]", {});
        $('.flatpickr').flatpickr({
            enableTime: true,
            // "plugins": [new confirmDatePlugin({})],
            // dateFormat: 'd.m.y h:i K'
        });

        function setIntlTelInput(selector,hiddenInput) {
            let mobileInput = document.querySelector(selector);
            return  window.intlTelInput(mobileInput, {
                        initialCountry: "id",
                        separateDialCode: true,
                        dropdownContainer: null,
                        preferredCountries: ["id"],
                        hiddenInput: hiddenInput
                    });
        }

        //cancel invoice form track status & popup
        function cancelInvoice(selector,encrypt_id,order_id=''){
            $('#invoice-loader'+order_id).toggleClass('hidden');
            selector.toggleClass('hidden');
            $.ajax({
                url: "{{ route('cancel-invoice','') }}/"+encrypt_id,
                type: 'GET',
                dataType: 'JSON',
                //data: {order_id:order_id,_token: $('meta[name="csrf-token"]').attr('content')},
                success: function (successData) {
                    if (successData.success==true){
                        if (order_id){
                            window.location.reload();
                        }
                        $('#orderStatusDetails').html(successData.html);
                    }else{
                        swal({
                            title: successData.message,
                            icon: "/assets/images/warn.png",
                            confirmButtonText : "{{__('admin.ok')}}",
                            dangerMode: false,
                        });
                        $('#invoice-loader'+order_id).toggleClass('hidden');
                        selector.toggleClass('hidden');
                    }
                },
                error: function () {
                    $('#invoice-loader'+order_id).toggleClass('hidden');
                    selector.toggleClass('hidden');
                    swal({
                        title: '{{ __('admin.something_error_message') }}',
                        icon: "/assets/images/warn.png",
                        confirmButtonText : "{{__('admin.ok')}}",
                        dangerMode: false,
                    });
                    console.log('error');
                }
            });
        }
        //generate pay link form track status & popup
        function generatePayLink(selector,orderId) {
            $('#invoice-loader').toggleClass('hidden');
            selector.toggleClass('hidden');
            $.ajax({
                url: "{{ route('generate-pay-link-track-status', '') }}/" + orderId,
                type: 'GET',
                dataType: 'JSON',
                success: function(successData) {
                    if (successData.success) {
                        $('#orderStatusDetails').html(successData.html);
                    }else{
                        $('#invoice-loader').toggleClass('hidden');
                        selector.toggleClass('hidden');
                        swal({
                            text: successData.message,
                            icon: "/assets/images/warn.png",
                            confirmButtonText : "{{__('admin.ok')}}",
                            dangerMode: true,
                        });
                    }
                },
                error: function(error) {
                    $('#invoice-loader').toggleClass('hidden');
                    selector.toggleClass('hidden');
                    swal({
                        title: '{{ __('admin.something_error_message') }}',
                        icon: "/assets/images/warn.png",
                        confirmButtonText : "{{__('admin.ok')}}",
                        dangerMode: true,
                    });
                    console.log(error);
                }
            });
            return false;
        }

    </script>
    @yield('scripts')
    @stack('bottom_scripts')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip({ trigger : 'hover'});

        });
        $(document).ready(function() {

        });
    </script>


<script src="{{ URL::asset('assets/vendors/tinymce/tinymce.min.js') }}"></script>
<script>

   $(document).ready(function(){
        SnippetAdminLayout.init();
    });

    var SnippetAdminLayout = function(){

        var removeActiveClass = function(){

            $('#sidebar .sidebar').each(function(){
                $(this).removeClass('active');
            });

            $('#sidebar .nav-link').each(function (){
                $(this).removeClass('active');
            });

            $('#sidebar .nav-item').each(function (){
                $(this).removeClass('active');
            });

            $('#sidebar .collapse').each(function (){
                $(this).removeClass('show');
            });

        },

        menuActive = function(){
            $('#sidebar .sidebar').each(function (){
                if($(this).attr('data-active') == "active") {
                    $(this).addClass('active');
                }
            });

            $('#sidebar .nav-link').each(function (){
                if ($(this).attr('data-active') == "active") {
                    $(this).addClass('active');
                }
            });

            $('#sidebar .nav-item').each(function (){
                if ($(this).attr('data-active') == "active") {
                    $(this).addClass('active');
                }
            });

            $('#sidebar .collapse').each(function (){
                if ($(this).attr('data-collapse') == "show") {
                    $(this).addClass('show');
                }
            });
        };

        checkup = function(){
            if ($('.nav-item submenu-parent').attr('aria-expanded') === "true") {
                $(".menu-arrow").addClass("menu-arrow-sorted-up");
            }else{
                $('.menu-arrow').addClass('menu-arrow-sorted-down');
            }
        }
        return {
            init:function(){
                removeActiveClass(),
                menuActive(),
                checkup()
            }
        }

    }(1);


    /*Tinymce editor*/
    if ($("#description").length) {
        tinymce.init({
            selector: '.newtextarea',
            height: 150,
            menubar: false,
            theme: 'silver',
            setup: function (ed) {
                ed.on('keyup', function (e) {
                    SnippetCustomAjaxValidation.validateTinyTextarea("address","address_error");
                    SnippetCustomAjaxValidation.validateTinyTextarea("description","description_error");

                });
            },
            plugins: [
                // 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                // 'searchreplace wordcount visualblocks visualchars code fullscreen',
                // 'insertdatetime media nonbreaking save table contextmenu directionality',
                // 'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
            ],
            toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            // toolbar2: 'print preview media | forecolor backcolor emoticons | codesample help',
            image_advtab: true,
            // templates: [{
            //     title: 'Test template 1',
            //     content: 'Test 1'
            // },
            // {
            //     title: 'Test template 2',
            //     content: 'Test 2'
            // }
            // ],
            content_css: []
        });
    }

    if ($("#product_description").length) {
        tinymce.init({
            selector: '.newtextarea',
            height: 150,
            menubar: false,
            theme: 'silver',
            plugins: [
                // 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                // 'searchreplace wordcount visualblocks visualchars code fullscreen',
                // 'insertdatetime media nonbreaking save table contextmenu directionality',
                // 'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
            ],
            toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            // toolbar2: 'print preview media | forecolor backcolor emoticons | codesample help',
            image_advtab: true,
            // templates: [{
            //     title: 'Test template 1',
            //     content: 'Test 1'
            // },
            // {
            //     title: 'Test template 2',
            //     content: 'Test 2'
            // }
            // ],
            content_css: []
        });
    }

    if ($("#comment").length) {
        tinymce.init({
            selector: '.newtextarea',
            height: 150,
            menubar: false,
            theme: 'silver',
            plugins: [
                // 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                // 'searchreplace wordcount visualblocks visualchars code fullscreen',
                // 'insertdatetime media nonbreaking save table contextmenu directionality',
                // 'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
            ],
            toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            // toolbar2: 'print preview media | forecolor backcolor emoticons | codesample help',
            image_advtab: true,
            // templates: [{
            //     title: 'Test template 1',
            //     content: 'Test 1'
            // },
            // {
            //     title: 'Test template 2',
            //     content: 'Test 2'
            // }
            // ],
            content_css: []
        });
    }
    if ($("#addressbuyer").length) {

        tinymce.init({
            selector: '.newtextarea1',
            height: 150,
            menubar: false,
            theme: 'silver',
            plugins: [
                // 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                // 'searchreplace wordcount visualblocks visualchars code fullscreen',
                // 'insertdatetime media nonbreaking save table contextmenu directionality',
                // 'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
            ],
            toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            // toolbar2: 'print preview media | forecolor backcolor emoticons | codesample help',
            image_advtab: true,
            // templates: [{
            //     title: 'Test template 1',
            //     content: 'Test 1'
            // },
            // {
            //     title: 'Test template 2',
            //     content: 'Test 2'
            // }
            // ],
            content_css: []
        });
    }

    var SnippetCustomAjaxValidation = function(){

        return {
            /**begin: tinyMCE textarea keyup validation*/
            validateTinyTextarea: function (inputId, inputErrorId) {

                if ($('#'+inputId).length) {
                    var body = tinymce.get(inputId).getBody();
                    var content = tinymce.trim(body.innerText || body.textContent);
                    var addressErrorClass = $("#"+inputErrorId).children().attr('class');
                    var addressErrorId = $("#"+inputErrorId).children().attr('id');

                    if (addressErrorClass=="parsley-errors-list filled") {
                        if (content.length > 0) {
                            $("#"+addressErrorId).addClass('d-none');
                        } else {
                            $("#"+addressErrorId).removeClass('d-none');
                        }

                    } else if (addressErrorClass=="parsley-errors-list filled d-none") {
                        if (content.length > 0) {
                            $("#"+addressErrorId).addClass('d-none');
                        } else {
                            $("#"+addressErrorId).removeClass('d-none');
                        }
                    }
                }

            }
            /**end: tinyMCE textarea keyup validation*/

        }


    }(1);

    //Show Video Popup
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

    $(document).on("click", "#stopVideoNow", function () {
        $('#suppYoutube').attr('src', '');
    });

    $(document).on('click', '.viewLimitDetail', function (e) {
        viewLimitDetails($(this).attr('data-id'));
    });

        function viewLimitDetails(limitId){
            if (limitId) {
                $("#viewLimitModal").find(".modal-content").html('');
                $.ajax({
                    url: "{{ route('limit-application-detail-view', '') }}" + "/" + limitId,
                    type: 'GET',
                    success: function (successData) {
                        $("#viewLimitModal").find(".modal-content").html(successData.limitView);
                        $('#viewLimitModal').modal('show');
                    },
                    error: function () {
                        console.log('error');
                    }
                });
            }
        }

        function viewLoanDetails(loanId){
            if (loanId) {
                $("#viewLimitModal").find(".modal-content").html('');
                $.ajax({
                    url: "{{ route('loan-view', '') }}" + "/" + loanId,
                    type: 'GET',
                    success: function (successData) {
                        $("#viewLimitModal").find(".modal-content").html(successData.loanView);
                        $('#viewLimitModal').modal('show');
                    },
                    error: function () {
                        console.log('error');
                    }
                });
            }
        }

        function viewOrderDetails(orderId){
            if (orderId) {
                $("#singleOrderDetail").find(".modal-content").html('');
                $.ajax({
                    url: "{{ route('get-single-order-detail-ajax', '') }}" + "/" + orderId,
                    type: 'GET',
                    success: function(successData) {
                        if (successData.html) {
                            $('#singleOrderDetail').html(successData.html);
                            $('#staticBackdrop').modal('show');
                        }
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        }
</script>
<script type="text/javascript">
    /******************begin: Init jQuery Modules*********************/
    jQuery(document).ready(function(){
        SnippetFrontDefaultInit.init();
    });
    /******************end: Init jQuery Modules*********************/
    /******************begin: Front Default Init**********************/
    var SnippetFrontDefaultInit = function () {

        var initTooltip = function () {
            $('[data-toggle="tooltip"]').tooltip({ trigger : 'hover'});
        };

        return {
            init: function () {
                initTooltip()
            }
        }
    }(1);
    /******************end: Front Default Init**********************/
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

</body>

</html>
