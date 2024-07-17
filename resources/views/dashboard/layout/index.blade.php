@extends('dashboard/layout/layout')

@section('content')
    <link rel="stylesheet" href="{{ URL::asset('front-assets/intlTelInput/css/intlTelInput.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('chat/css/chat.css')}}">
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

        /* .iti--separate-dial-code .iti__selected-flag{font-size: 12px;  height: 38px;} */
        #myBtn {
            left: 30px !important;
        }
    </style>
        @if($showCredit==0)
                @can('create buyer company credits')
                    <div class="loan_button">
                        <a href="{{ route('settings.credit.apply.step1') }}"
                           class="btn btn-primary">{{__('profile.apply_for_loan')}}</a>
                    </div>
                @endcan
            @endif
    <div id="userinfo" class="col-lg-4 col-xl-3 py-3 collapse collapse-horizontal show">
        <div class="card border-0 shadow-lg">

            <div class="logobanner_section d-flex align-items-center justify-content-center w-100 p-3">
                @if(isset($usercompany) && isset($usercompany->logo) )
                    <img src="{{ url('storage/'.$usercompany->logo) }}" class="mw-100" alt="company logo">
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
                                {{-- {{ substr(Auth::user()->firstname, 0, 1) }}{{ substr(Auth::user()->lastname, 0, 1) }} --}}
                            </div>
                        </div>
                        @canany(['publish buyer profile', 'publish buyer personal info', 'publish buyer preferences', 'publish buyer approval configurations', 'publish buyer profile', 'publish buyer payment term', 'publish buyer users', 'publish buyer roles and permissions', 'publish buyer company info', 'publish buyer side invite', 'publish buyer change password', 'publish buyer settings'])
                            <div class="mt-auto ms-auto user_edit ">
                                <a href="{{ route('profile') }}" class="btn bg-white border border-info p-2 pt-1 "
                                   title="{{ __('dashboard.edit_profile')}}">
                                    <img src="{{ URL::asset('front-assets/images/icons/cog.png') }}" alt="edit">
                                </a>
                            </div>
                        @endcanany
                    </div>

                    <div class="d-flex">
                        <div>
                            <h5 class="pt-2 pb-1 mb-0">{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}</h5>
                            {{$buyerRole ? $buyerRole :'Approval / consultant'}}
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
                            <span>{{ $user->defaultCompany->name ?? '-' }}</span>
                            <a class="ms-auto  switchcompanybtn" title="{{ __('dashboard.switch_company') }}" data-bs-toggle="collapse"
                               href="#CompanySwitchcollapse" role="button" aria-expanded="false"
                               aria-controls="collapseExample">
                                <i class="fa fa-random" style="font-size: 1rem"></i>

                            </a>
                            <div class="collapse " id="CompanySwitchcollapse">
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
                                <button type="button"
                                        class="btn bg-light w-100 d-flex align-items-center radius_1 btn-sm dash-menus"
                                        id="myGroupsSection">
                                    <img src="{{ URL::asset('front-assets/images/icons/group.png')}}"
                                         class="p-2 opacity-75"> {{ __('admin.groups') }}
                                    <span class="badge text-dark border border-warning ms-auto radius_2"
                                          id="myGroupsCount"></span>
                                </button>
                            </div>
                        @endcanany
                        @can('approval buyer approval configurations')
                            <div class="col-md-12 pb-2">
                                <a type="button" class="btn bg-light w-100 d-flex align-items-center radius_1 btn-sm dash-menus btn-sm menu-link" id="myApprovalSection" href="{{ route('approvals.index') }}">
                                    <img src="{{ URL::asset('front-assets/images/icons/approve.png') }}" class="p-2 opacity-75"> {{ __('profile.approvals_tab') }}
                                    <span class="badge text-dark border border-warning ms-auto radius_2" id="myApprovalCount">0</span>
                                </a>
                            </div>
                        @endcan
                        @can('publish buyer payments')
                            <div class="col-md-12 pb-2">
                                <button type="button"
                                        class="btn bg-light w-100 d-flex align-items-center radius_1 dash-menus btn-sm"
                                        id="paymentSection">
                                    <img src="{{ URL::asset('front-assets/images/icons/icon_credit_request.png') }}"
                                         class="p-2 opacity-75"> {{ __('dashboard.payment') }}
                                    <span class="badge text-dark border border-warning ms-auto radius_2"
                                          id="myPaymentCount">0</span>
                                </button>
                            </div>
                        @endcan
                        @can('utilize buyer company credit')
                            @if($showCredit == 1)
                                <div class="col-md-12 pb-2">
                                    <a type="button" class="btn bg-light w-100 d-flex align-items-center radius_1 dash-menus btn-sm menu-link" id="creditSection" href="{{ route('credit.wallet.index') }}">
                                        <img src="{{ URL::asset('front-assets/images/icons/credit_buyerside.png') }}" class="p-2 opacity-75">
                                        {{ __('profile.Credit') }}
                                    </a>
                                </div>
                            @endif
                        @endcan
                        @can('publish buyer address')
                            <div class="col-md-12">
                                <button type="button"
                                        class="btn bg-light w-100 d-flex align-items-center radius_1  btn-sm dash-menus"
                                        id="addressSection">
                                    <img src="{{ URL::asset('front-assets/images/icons/icon_address.png') }}"
                                         class="p-2">{{ __('dashboard.my_address') }} <span
                                        class="badge text-dark border border-warning ms-auto radius_2"
                                        id="myAddressCount"></span>
                                </button>
                            </div>
                        @endcan
                        @canany(['buyer rfn publish','buyer global rfn publish'])
                                <div class="col-md-12 pb-2">
                                    <a type="button" class="btn bg-light w-100 d-flex align-items-center radius_1 dash-menus btn-sm menu-link" id="rfnSection" href="{{ route('rfn.index') }}">
                                        <img src="{{ URL::asset('front-assets/images/icons/icon_rfq.png') }}" class="p-2 opacity-75">
                                        {{ __('buyer.rfn') }}
                                        <span class="badge text-dark border border-warning ms-auto radius_2" id="myrfnCount">0</span>
                                    </a>
                                </div>
                        @endcan
                    </div>
                </div>
            </div>
    </div>
    <div class="col-lg-8 col-xl-9 py-2" id="mainContentSection">
        {{-- here page content are showing --}}
    </div>
    @else
    </div>{{-- Extra div for Role permission condition--}}
    <div class="col-lg-8 col-xl-9 py-2 header_top d-flex align-items-center">
        <div class="text-center w-100">
            <h1>{{__('dashboard.no_permission_message')}}</h1>
            <img src="{{ URL::asset('front-assets/images/no_permission.png') }}"
                 class="mw-100 mt-5">
        </div>
    </div>

    @endcanany
    <!-- <div class="mt-auto pt-5 text-center row justify-content-end mx-0 ">
        <div class="col-lg-8 col-xl-9 mb-3 pe-0 ps-1 bg-light">
            <div class="py-3">
                <small><span style="font-family: arial ;">&copy;</span>  {{ date("Y") }} {{__('dashboard.all_rights_reserved')}}</small>
            </div>
        </div>
    </div> -->
    <!-- Chat section start -->
    <div class="randomdiv">
        <div class="chat_icon" data-bs-toggle="collapse" href="#chatPopup" role="button" aria-expanded="false" aria-controls="chatPopup" class="btn text-white dropdown-toggle" title="{{ __('admin.chat')}}"  onclick="chat.clickChatIcon('{{ route('group-chattype-count-ajax') }}' , $(this))" id="frontendChatPopupClick">
            @if($dots > 0 && Auth::user()->hasAnyPermission(['list-all buyer rfqs', 'list-all buyer quotes']))
                <span class="dots" id="removeDots"></span>
            @endif
        </div>

        <div class="collapse d-none" id="chatPopup">
            <!-- page1 -->
            <div class="card chat_section d-none border-0" id="chatgrouplist">

                <div class="card-header">
                    <div class="head text-white">{{ __('admin.chat')}}</div>
                </div>
                <div class="card-body">
                    <div class="content">
                        <a href="javascript:void(0)" id="rfqchatgroup" onclick="chat.getChatData('{{ route('group-chat-list-ajax') }}', '{{ Crypt::encrypt("Rfq") }}', 'Rfq')" data-name="rfq"  class="rfqchat m-2 mb-3 px-3 d-flex align-items-center">
                            <div class="text-start">
                                <div class=""><img src="{{ URL::asset('chat/images/icon_rfq.png')}}" class="icon_bg ">
                                    <span class="blue_text_color ps-2">{{ __('dashboard.my_rfqs') }}</span></div>
                            </div>
                            <div class="ms-auto main-chattype-count" id="rfq_chat_count"></div>
                        </a>
                        <a href="javascript:void(0)" id="quotechatgroup" onclick="chat.getChatData('{{ route('group-chat-list-ajax') }}', '{{ Crypt::encrypt("Quote") }}', 'Quote')" data-name="quote" class="quotechat m-2 mb-3 px-3 d-flex align-items-center">
                            <div class="text-start">
                                <div class=""><img src="{{ URL::asset('chat/images/icon_product_details.png')}}" class="icon_bg ">
                                    <span class="blue_text_color ps-2">{{ __('admin.quotes') }}</span>
                                </div>
                            </div>
                            <div class="ms-auto main-chattype-count" id="quote_chat_count"></div>
                        </a>
                        <a href="javascript:void(0)" id="supportchatgroup" onclick="chat.viewSupportChatData('{{ route('support-chat-create-view') }}', '{{ $supportChatId??'' }}', '{{ Auth::user()->full_name }}', $(this), '{{ Auth::user()->default_company??NULL }}')" data-name="support" class="rfqchat m-2 mb-3 px-3 d-flex align-items-center">
                            <div class="text-start">
                                <div class=""><img src="{{ URL::asset('chat/images/icon_product_details.png')}}" class="icon_bg ">
                                    <span class="blue_text_color ps-2">{{ __('dashboard.support') }}</span>
                                </div>
                            </div>
                            <div class="ms-auto main-chattype-count" id="support_chat_count"></div>
                        </a>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="card-footer_text">{{ __('dashboard.authorised_by') }} <strong><em>blitznet</em></strong>
                    </div>
                </div>
            </div>
            <!-- page1 -->
            <div class="card chat_section d-none border-0" id="allchatlist"></div>
            <!-- page3 -->
            <div class="card chat_section d-none border-0" id="chatsection"></div>
            <!-- page3 -->
            <!-- page4 -->
            <div class="card chat_section d-none  border-0"  id="rfqproductdetail"></div>
            <!-- page4 -->
            <!-- New user with no RFq -->
            <div class="card chat_section d-none border-0" onclick="chat.chatPreviewPage('#rfqproductdetail')" id="newuserchatsection"></div>
            <!-- New user with no RFq -->
            <!-- blank page with no group -->
            <div class="card chat_section d-none border-0" id="blankpage">
                <div class="card-header d-flex align-items-center">
                    <div class="head text-white chatnametitle">
                    <span class="pe-2">
                        <i class="fa fa-chevron-left text-white"></i>
                    </span>RFQ
                    </div>
                    <div class="head_end_text text-white ms-auto">New Chat</div>
                </div>
                <div class="card-body">
                    <div class="content ">
                        <div class="searchbar m-2 px-2 d-flex align-items-center">

                            <div class="">
                                <input class="form-control form-control-sm searchchatrfq" style="width: 288px; height: 40px;" attr-name="catSearch" type="text" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="allrfqchatlist">
                        <div class="blankchatpage">
                            <img src="{{URL::asset('chat/images/blank_image.png')}}" alt="" srcset="">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="card-footer_text">Authorised by <strong><em>blitznet</em></strong>
                    </div>
                </div>
            </div>
            <!-- blank page with no group -->
        </div>
    </div>
    <!-- Chat section end -->

    <button onclick="topFunction()" id="myBtn" title="Go to top" class="bg-warning shadow"><img
            src="{{ URL::asset('front-assets/images/icons/uparrow.png') }}" alt=""></button>
    <script>
        var addressSection = '';
        var rfqSection = '';
        var orderSection = '';
        var defaultData = '';
        var addAddressForm = '';
        var paymentSection = '';
        var myGroupsSection = '';
        $(document).ready(function () {

            var companydetails = @json(Auth::user()->load('defaultCompany')->companies ?? '');
            if (companydetails.background_logo) {
                $('.logobanner_section').css('background-image', companydetails.background_logo);
            }
            if(companydetails.background_colorpicker) {
                $('.logobanner_section').css('background', companydetails.background_colorpicker);
            }

            // set dashboard default redirection based on permission

            sessionStorage.setItem("dashboard-lastlocation", "{{$sectionName}}");

            $('body').click(function (e) {
                if (!$(e.target).is('#companyData')) {
                    $("#CompanySwitchcollapse").collapse('hide');
                }
            });
            // Company switch by user
            $(document).on('click', '.switchcompanybtn', function (e) {
                $.ajax({
                    url: '{{ route('settings.company.list') }}',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    success: function (response) {
                        html = '';
                        userIconHtml = '';
                        if (response.success == true) {
                            $.each(response.data.company, function (key, value) {
                                let companyName = value.name;
                                let companySplit = companyName.split(" ");
                                let companyInitials = null;
                                let style = '';

                                if (companySplit.length > 1) {
                                    companyInitials = companySplit[0].charAt(0).toUpperCase() + companySplit[1].charAt(0).toUpperCase();
                                } else {
                                    style = 'style="padding:2px 8px !important";';
                                    companyInitials = companySplit[0].charAt(0).toUpperCase();
                                }

                                html += '<div class="d-flex align-items-center px-2 py-2 changeDefaultCmpId" style="cursor:pointer" data-cmp-id=' + value.id + '>';
                                html += '<span class="company_namelist me-2" ' + style + '>' + companyInitials + '</span>\
                                        <a type="button" class="text-decoration-none text-dark">' + value.name + '</a>';
                                userIconHtml = '<img class="ms-2 text-primary" src="{{URL::asset('front-assets/images/manager-avatar.png')}}" title="{{ __('dashboard.owner') }}" height="16px" alt="" srcset="">';
                                if (response.data.default_company == value.id) {
                                    html += '<span class="ms-auto alert alert-success mb-0 p-0 px-1" style="font-size: 0.8rem;" role="alert">'+'{{ __('admin.active')}}'+'</span>';
                                    if (response.data.user_id == value.owner_user) {
                                        html += userIconHtml;
                                    } else {
                                        html += '';
                                    }
                                }
                                if (response.data.default_company != value.id && response.data.user_id == value.owner_user) {
                                    html += '<span class="ms-auto alert  mb-0 p-0 px-1" style="font-size: 0.8rem;" role="alert">' + userIconHtml + '</span>';
                                }


                                html += '</div>';
                            });
                            $('#companyData').html(html);

                        }
                    }
                });
            });

            $(document).on('click', '.changeDefaultCmpId', function (e) {
                let cmpID = $(this).attr('data-cmp-id');
                $.ajax({
                    url: '{{ route('settings.company.switch') }}',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    data: {cmpID: cmpID},
                    success: function (response) {
                        if (response.success == true) {
                            location.reload(true);
                            var url = "{{ route('dashboard') }}";
                            window.location.replace(url);
                        }
                    }
                });
            })

            $(document).on('click', '#postRequirementSection', function (e) {
                sessionStorage.setItem("dashboard-lastlocation", "postRequirementSection");
                $('button.dash-menus').removeClass('btnactive');
                // $(this).addClass('btnactive');
                e.stopImmediatePropagation();
                $('#isRepeatRfq').val(localStorage.getItem("dashboard-isRepeatRfq"));
                let isRepeatRfqId = '';
                if(localStorage.getItem("dashboard-repeatRfqId") != ''){
                    isRepeatRfqId = localStorage.getItem("dashboard-repeatRfqId");
                }
                localStorage.clear();
                $.ajax({
                    url: '{{ route('dashboard-default-ajax') }}',
                    type: 'GET',
                    data:{'isRepeatRfqId':isRepeatRfqId},
                    success: function (successData) {
                        $('#RepeatrfqModal').modal('hide');
                        defaultData = successData.html;
                        $('#mainContentSection').html(successData.html);
                        $('#userActivitySection').html(successData.userActivityHtml);
                        $('#myRfqCount').html(successData.rfqCount);
                        $('#myOrderCount').html(successData.ordersCount);
                        $('#myAddressCount').html(successData.addressCount);
                        $('#myPaymentCount').html(successData.supplierPaymentCount);
                        $('#myGroupsCount').html(successData.buyerGroupsCount);
                        $('#myApprovalCount').html(successData.approvalTabCount);
                        $('#myrfnCount').html(successData.rfnList);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });

            $(document).on('click', '#addressSection', function (e) {
                sessionStorage.setItem("dashboard-lastlocation", "addressSection");
                $('button.dash-menus').removeClass('btnactive');

                $(this).addClass('btnactive');
                e.stopImmediatePropagation();

                $.ajax({
                    url: '{{ route('dashboard-list-address-ajax') }}',
                    type: 'GET',
                    success: function (successData) {
                        addressSection = successData.html;
                        $('#mainContentSection').html(successData.html);
                        $('html, body').animate({
                            scrollTop: $("#mainContentSection").offset()
                        }, 50);
                    },
                    error: function () {
                        console.log('error');
                    }
                });
            });


            $(document).on('click', '#rfqSection', function (e) {
                localStorage.setItem("dashboard-lastlocation", "rfqSection");
                $('button.dash-menus').removeClass('btnactive');
                var isFavRfqSectionClicked = $('.favorite_0').attr('data-isFavRfqSectionClicked');
                var isRfqSectionClicked = $('#rfqSection').attr('data-isRfqSectionClicked');
                $(this).addClass('btnactive');
                e.stopImmediatePropagation();let favoriteRfq = 0;
                if(isRfqSectionClicked==1 && isFavRfqSectionClicked == 1){
                     favoriteRfq = localStorage.getItem("dashboard-is_favouriteRfq");
                }else{
                    favoriteRfq = 0;
                    $(".addtofavouriteRfq_0").removeClass("fa-star").addClass("fa-star-o");
                    $(".favorite_0").removeClass("btn-danger").addClass("btn-outline-danger");
                    $('#is_favouriteRfq_0').val(favoriteRfq);
                }
                $.ajax({
                    url: '{{ route('dashboard-list-rfq-ajax') }}',
                    type: 'GET',
                    data: { favoriteRfq: favoriteRfq},
                    success: function (successData) {
                        rfqSection = successData.html;
                        $('#mainContentSection').html(successData.html);
                        $('html, body').animate({
                            scrollTop: $("#mainContentSection").offset()
                        }, 50);
                        $('#buyerRfqNotification').addClass('d-none');
                        $('#is_favouriteRfq_0').val(favoriteRfq);
                        if(favoriteRfq==1){
                            $(".addtofavouriteRfq_0").toggleClass("fa-star-o fa-star");
                            $(".favorite_0").toggleClass("btn-outline-danger btn-danger");
                            $('#no_rfq_alert').html('{{ __('rfqs.No_favourite_rfq_found') }}');
                        }else{
                            $('#no_rfq_alert').html('{{ __('rfqs.No_rfq_found') }}')
                        }
                    },
                    error: function () {
                        console.log('error');
                    }
                });
            });

            $(document).on('click', '#orderSection', function (e) {
                sessionStorage.setItem("dashboard-lastlocation", "orderSection");
                $('button.dash-menus').removeClass('btnactive');
                $(this).addClass('btnactive');
                e.stopImmediatePropagation();
                $.ajax({
                    url: '{{ route('dashboard-list-order-ajax') }}',
                    type: 'GET',
                    success: function (successData) {
                        orderSection = successData.html;
                        $('#mainContentSection').html(successData.html);
                        $('html, body').animate({
                            scrollTop: $("#mainContentSection").offset()
                        }, 50);
                        $('#buyerOrderNotification').addClass('d-none');
                    },
                    error: function () {
                        console.log('error');
                    }
                });
            });
        });

        $(document).on('click', '#paymentSection', function (e) {
            sessionStorage.setItem("dashboard-lastlocation", "paymentSection");
            $('button.dash-menus').removeClass('btnactive');

            $(this).addClass('btnactive');
            e.stopImmediatePropagation();

            $.ajax({
                url: '{{ route('dashboard-list-payment-ajax') }}',
                type: 'GET',
                success: function (successData) {
                    paymentSection = successData.html;
                    $('#mainContentSection').html(successData.html);
                    $('#myPaymentCount').html(successData.supplierPaymentCount);
                    $('html, body').animate({
                        scrollTop: $("#mainContentSection").offset()
                    }, 50);
                    total_payment_calc();
                },
                error: function () {
                    console.log('error');
                }
            });
        });

        //On click of my groups, shows groups data
        $(document).on('click', '#myGroupsSection', function (e) {
            sessionStorage.setItem("dashboard-lastlocation", "myGroupsSection");
            $('button.dash-menus').removeClass('btnactive');

            $(this).addClass('btnactive');
            e.stopImmediatePropagation();
            // var data = {
            //     "_token": "{{ csrf_token() }}",
            // }
            $.ajax({
                url: '{{ route('dashboard-group-listing') }}',
                type: 'GET',
                // data: data,
                success: function (successData) {
                    myGroupsSection = successData.html;
                    $('#mainContentSection').html(successData.html);
                    $('#myGroupsCount').html(successData.buyerGroupsCount);
                    $('html, body').animate({
                        scrollTop: $("#mainContentSection").offset()
                    }, 50);
                },
                error: function () {
                    console.log('error');
                }
            });
        });


        //Get the button:
        mybutton = document.getElementById("myBtn");

        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function () {
            scrollFunction()
        };

        function scrollFunction() {
            if (document.body.scrollTop > 600 || document.documentElement.scrollTop > 600) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        }




    </script>
@stop
@push('bottom_scripts')
    @include('dashboard.payment.payment_tab_js')
@endpush
