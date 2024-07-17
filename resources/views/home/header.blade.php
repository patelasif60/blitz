
@php
    $route_name = Route::getCurrentRoute()->action['as'];
@endphp
<header id="navigatio_top" class="fixed-top" data-aos="fade-down">
    <nav class="navbar navbar-expand-xl navbar-light">
        <div class="container-xl">
            <a class="navbar-brand" href="{{ route('latest-home') }}">
                <img src="{{ asset('home_design/images/blitznet-logo.svg') }}" alt="blitznet logo" class="mw-100 d-none d-sm-block">
                <img src="{{ asset('home_design/images/blitznet-logo-icon_mobile.svg') }}" alt="blitznet logo" class="mw-100 d-sm-none d-block">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link nav-48f0e804 text-nowrap {{ ($route_name == 'latest-home' || $route_name == 'home') ? 'active' : '' }}" aria-current="page" href="{{route('latest-home')}}">{{__('home_latest.home')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-48f0ef3e text-nowrap {{ $route_name == 'buyers'? 'active' : '' }}" href="{{route('buyers')}}">{{__('home_latest.buyers')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-48f0f1c8 text-nowrap {{ $route_name == 'suppliers'? 'active' : '' }}" href="{{route('suppliers')}}">{{__('home_latest.suppliers')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-nowrap nav-48f0f3bc {{ $route_name == 'group-trading-front'? 'active' : '' }}" href="{{route('group-trading-front')}}">{{__('dashboard.group_trading')}}</a>
                    </li>

                    {{--<li class="nav-item">
                        <a class="nav-link nav-48f0f84e text-nowrap {{ $route_name == 'blog'? 'active' : '' }}" href="https://www.blitznet.co.id/blog/">{{__('home_latest.blog')}}</a>
                    </li>--}}
                    <li class="nav-item">
                        <a class="nav-link nav-48f0fa60 text-nowrap {{ $route_name == 'contact-us'? 'active' : '' }}" href="{{route('contact-us')}}">{{__('home_latest.contact_us_home')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-48f0fc2c text-nowrap {{ $route_name == 'about-us'? 'active' : '' }}" href="{{route('about-us')}}">{{__('home_latest.about_us')}}</a>
                    </li>
                </ul>
            </div>
            <div class="rightmenu">
                <ul class="navbar-nav">
                    @if(auth()->user())
                        <div class="btn-group ps-2 home_user">
                            <button type="button" class="dropdown-toggle border-0 bg-transparent" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ URL::asset('front-assets/images/front/icon_user.svg') }}"alt="user" width="28px">
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @php
                                    $route = Auth::user()->role_id == 2 ? route('dashboard') : route('admin-dashboard');
                                    $loginRoute = Auth::user()->role_id == 2 ? route('logout') : route('admin-logout');
                                @endphp
                                <li>
                                    <a class="dropdown-item" type="button" href="{{ $route }}">{{ __('admin.dashboard') }}</a>
                                </li>
                                <li class="bg-light">
                                    <a class="dropdown-item" type="button" href="{{ $loginRoute }}">{{ __('admin.logout') }}</a>
                                </li>
                            </ul>
                        </div>
                    @else
                    <li class="nav-item dropdown mx-lg-3 mx-1">
                            <a class="nav-link dropdown-toggle nav-48f0fe02" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="d-none d-md-inline-block">{{ __('frontend.login') }}</span>
                                <span class="d-block d-md-none">
                                    <img src="{{ asset('home_design/images/icons/arrow_left.svg') }}" alt="Login" width="28px" class="ms-2">
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li> <a class="dropdown-item" href="{{ route('signin') }}">{{__('admin.buyer')}}</a> </li>
                                <li> <a class="dropdown-item" href="{{ route('admin-login') }}">{{__('admin.supplier')}}</a> </li>
                            </ul>
                        </li>
                        <li class="nav-item mx-lg-3 mx-1 dropdown ">
                            <a name="" id="" class="text-white custombtn dropdown-toggle nav-48f0ffce" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="d-block d-md-none">
                                    <img src="{{ asset('home_design/images/icons/check.svg') }}" alt="REGISTER" width="28px">
                                </span>
                                <span class="d-none d-md-inline-block">{{ __('frontend.register') }}</span></a>
                            <ul class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ route('signup') }}">{{__('admin.buyer')}}</a></li>
                                <li><a class="dropdown-item" href="{{ route('signup-supplier') }}">{{__('admin.supplier')}}</a></li>
                            </ul>
                        </li>
                    @endif
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
    </nav>
</header>
