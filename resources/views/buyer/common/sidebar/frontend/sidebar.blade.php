    <style>

        .iti {
            display: block !important;
        }

        .iti .iti--allow-dropdown {
            width: 100% !important;
        }

        .iti__flag-container {
            position: absolute !important;
        }

        .iti__country-list {
            overflow-x: hidden;
            max-width: 360px;
        }

        #myBtn {
            left: 30px !important;
        }
    </style>
           @can('create buyer company credits')
                @if(isCreditApplied()==0)
                    <div class="loan_button">
                        <a href="{{ route('settings.credit.apply.step1') }}"
                           class="btn btn-primary">{{__('profile.apply_for_loan')}}</a>
                    </div>
                @endif
            @endcan
    <div id="userinfo" class="col-lg-4 col-xl-3 py-3 collapse collapse-horizontal show">
        <div class="card border-0 shadow-lg">

            <div class="logobanner_section d-flex align-items-center justify-content-center w-100 p-3">
                @if(isset(Auth::user()->defaultCompany) && isset(Auth::user()->defaultCompany->logo) )
                    <img src="{{ url('storage/'.Auth::user()->defaultCompany->logo) }}" class="mw-100" alt="company logo">

                @elseif(isset(Auth::user()->load('defaultCompany')->companies->background_colorpicker))
                    <img src="{{ URL::asset('front-assets/images/front/logo.png') }}" style="background-color:'{{Auth::user()->load('defaultCompany')->companies->background_colorpicker}}'" class="mw-100" alt="company logo">

                @else
                    <img src="{{ URL::asset('front-assets/images/front/logo.png') }}" class="mw-100" alt="company logo">
                @endif
            </div>
            <div class="card-body p-0 ">
                <div class="user_info p-4 pb-0 position-relative">
                    <div class="user_info_top d-flex">
                        <div>
                            <div class="user_info_photo radius_1 text-center verticle-middle">
                                @if(Auth::user()->profile_pic)
                                    <div class="ratio ratio-1x1">
                                        <div><img class="cover" alt="user"
                                                  src="{{asset('storage/' .Auth::user()->profile_pic) }}">
                                        </div>
                                    </div>
                                @else
                                    {{ strtoupper(substr(Auth::user()->firstname, 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname, 0, 1) )}}
                                @endif
                            </div>
                        </div>
                        @canany(['publish buyer profile', 'publish buyer personal info', 'publish buyer preferences', 'publish buyer approval configurations', 'publish buyer profile', 'publish buyer payment term', 'publish buyer users', 'publish buyer roles and permissions', 'publish buyer company info', 'publish buyer side invite', 'publish buyer change password', 'publish buyer settings'])
                            <div class="mt-auto ms-auto user_edit ">
                                <a href="{{ route('profile') }}" class="btn bg-white border border-info p-2 pt-1 " data-toggle="tooltip" title="{{ __('dashboard.edit_profile')}}">
                                    <img src="{{ URL::asset('front-assets/images/icons/cog.png') }}" alt="edit">
                                </a>
                            </div>
                        @endcanany
                    </div>

                    <div class="d-flex">
                        <div>
                            <h5 class="pt-2 pb-1 mb-0">{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}</h5>
                            {{ getBuyerRole(Auth::user()) }}
                        </div>
                        @php
                            $profilePercentage = getUserWisePendingProfilePercentage(Auth::user()->id,Auth::user()->default_company)??0
                        @endphp
                        @can('publish buyer company info')
                        <div class="pro_pie ms-auto">
                            <div class="circle" style="background-image:conic-gradient(#00a126 {{$profilePercentage}},#ccc 0)">
                                <div class="inner">{{$profilePercentage}}</div>
                            </div>
                        </div>
                        @endcan
                    </div>
                    <div class="row pb-2">
                        <div class="col-md-12">
                            <img src="{{ URL::asset('front-assets/images/icons/envelope.png') }}" style="width: 23px;"
                                 alt="email"
                                 class="p-2 ps-0">{{ Auth::user()->email }}
                        </div>
                        <div class="col-md-12 position-relative d-flex align-items-center">
                            <img src="{{ URL::asset('front-assets/images/icons/building.png') }}" alt="company"
                                 class="p-2 ps-0">
                            <span>{{ Auth::user()->defaultCompany->name ?? '-' }}</span>
                            <a class="ms-auto  switchcompanybtn" title="{{ __('dashboard.switch_company') }}" data-bs-toggle="collapse"
                               href="#collapseExample" role="button" aria-expanded="false"
                               aria-controls="collapseExample" data-toggle="tooltip">
                                <i class="fa fa-random" style="font-size: 1rem"></i>

                            </a>
                            <div class="collapse " id="collapseExample">
                                <div class="shadow-lg card switchcompany ">
                                    <div class="card-header fw-bold" style="color: #002050;">{{ __('dashboard.companies_list') }}</div>
                                    <div class="card-body p-0" id="companyData"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <img src="{{ URL::asset('front-assets/images/icons/phone-alt.png') }}" alt="phone"
                                 class="p-2 ps-0">{{ Auth::user()->mobile ? countryCodeFormat(Auth::user()->phone_code, Auth::user()->mobile) : '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- User info end -->
        @canany(['create buyer rfqs', 'publish buyer rfqs', 'publish buyer orders', 'create buyer join group', 'delete buyer join group', 'publish buyer payments', 'publish buyer address','approval buyer approval configurations','buyer rfn publish','buyer global rfn publish'])
        <div class="card border-0 mt-2 shadow-lg sticky-top">
                <div class="card-body">
                    <div class="row maincontrols">
                        @can('create buyer rfqs')
                            <div class="col-md-12 pb-2">
                                <button  type="button" id="postRequirementSection" class="hide"></button>
                                <a href="{{route('dashboard')}}" class="btn bg-warning bg-opacity-50 border w-100 d-flex align-items-center radius_1 btn-sm dash-menus">
                                    <img src="{{ URL::asset('front-assets/images/icons/icon_user_edit.png') }}"  class="p-2">{{ __('dashboard.post_a_new_requirement') }}
                                </a>
                            </div>
                        @endcan
                        @can('publish buyer rfqs')
                            <div class="col-md-12 pb-2">
                                <a href="{{route('rfqs-ls')}}" class="btn bg-light w-100 d-flex align-items-center radius_1 btn-sm dash-menus front-menu"> <img src="{{ URL::asset('front-assets/images/icons/icon_rfq.png') }}" class="p-2">{{ __('dashboard.my_rfqs') }}
                                    <span class="not_dot_main_control_section ms-2 d-none" id="buyerRfqNotification"></span>
                                    <span class="badge text-dark border border-warning ms-auto radius_2" id="myRfqCount"></span>
                                </a>
                            </div>
                        @endcan
                        @can('publish buyer orders')
                            <div class="col-md-12 pb-2">
                                <a href="{{route('order-ls')}}" class="btn bg-light w-100 d-flex align-items-center radius_1 btn-sm dash-menus front-menu"> <img src="{{ URL::asset('front-assets/images/icons/icon_order.png') }}" class="p-2">{{ __('dashboard.my_orders') }}
                                    <span class="not_dot_main_control_section ms-2 d-none" id="buyerOrderNotification"></span>
                                    <span class="badge text-dark border border-warning ms-auto radius_2" id="myOrderCount"></span>
                                </a>
                            </div>
                        @endcan
                        @canany(['publish buyer join group', 'delete buyer join group'])
                            <div class="col-md-12 pb-2">
                                <button type="button" class="btn bg-light w-100 d-flex align-items-center radius_1 btn-sm dash-menus front-menu" id="myGroupsSection" data-target-menu="group">
                                    <img src="{{ URL::asset('front-assets/images/icons/group.png')}}" class="p-2 opacity-75"> {{ __('admin.groups') }}
                                    <span class="badge text-dark border border-warning ms-auto radius_2" id="myGroupsCount"></span>
                                </button>
                            </div>
                        @endcanany
                        @can('approval buyer approval configurations')
                            <div class="col-md-12 pb-2">
                                <a type="button" class="btn bg-light w-100 d-flex align-items-center radius_1 btn-sm dash-menus btn-sm menu-link @if( in_array(Route::getCurrentRoute()->action['as'], ['approvals.index'])) btnactive @endif" id="myApprovalSection" href="{{ route('approvals.index') }}">
                                    <img src="{{ URL::asset('front-assets/images/icons/approve.png') }}" class="p-2 opacity-75"> {{ __('profile.approvals_tab') }}
                                    <span class="badge text-dark border border-warning ms-auto radius_2" id="myApprovalCount">0</span>
                                </a>
                            </div>
                        @endcan
                        @can('publish buyer payments')
                            <div class="col-md-12 pb-2">
                                <button type="button" class="btn bg-light w-100 d-flex align-items-center radius_1 dash-menus btn-sm front-menu" id="paymentSection" data-target-menu="payment">
                                    <img src="{{ URL::asset('front-assets/images/icons/icon_credit_request.png') }}" class="p-2 opacity-75"> {{ __('dashboard.payment') }}
                                    <span class="badge text-dark border border-warning ms-auto radius_2" id="myPaymentCount">0</span>
                                </button>
                            </div>
                        @endcan
                        @can('utilize buyer company credit')
                            @if(isCreditApplied()==1)
                                <div class="col-md-12 pb-2">
                                    <a type="button" class="btn bg-light w-100 d-flex align-items-center radius_1 dash-menus btn-sm @if( in_array(Route::getCurrentRoute()->action['as'], ['credit.wallet.index', 'credit.wallet.transactions'])) btnactive @endif" id="creditSection" href="{{route('credit.wallet.index')}}">
                                        <img src="{{ URL::asset('front-assets/images/icons/credit_buyerside.png') }}" class="p-2 opacity-75"> {{ __('profile.Credit') }}
                                    </a>
                                </div>
                            @endif
                        @endcan
                        @can('publish buyer address')
                            <div class="col-md-12">
                                <button type="button" class="btn bg-light w-100 d-flex align-items-center radius_1  btn-sm dash-menus front-menu" id="addressSection" data-target-menu="address">
                                    <img src="{{ URL::asset('front-assets/images/icons/icon_address.png') }}" class="p-2">
                                    {{ __('dashboard.my_address') }}
                                    <span class="badge text-dark border border-warning ms-auto radius_2" id="myAddressCount"></span>
                                </button>
                            </div>
                        @endcan
                        @canany(['buyer rfn publish','buyer global rfn publish'])
                            <div class="col-md-12 pb-2">
                                <a type="button" class="btn bg-light w-100 d-flex align-items-center radius_1 dash-menus btn-sm @if( in_array(Route::getCurrentRoute()->action['as'], ['rfn.index'])) btnactive @endif" id="rfnSection" href="{{ route('rfn.index') }}">
                                    <img src="{{ URL::asset('front-assets/images/icons/icon_rfq.png') }}" class="p-2 opacity-75">
                                    {{ __('buyer.rfn') }}
                                    <span class="badge text-dark border border-warning ms-auto radius_2" id="myrfnCount">0</span>
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        @endcanany
    </div>
@push('bottom_scripts')
    @include('dashboard.payment.payment_tab_js')
@endpush
