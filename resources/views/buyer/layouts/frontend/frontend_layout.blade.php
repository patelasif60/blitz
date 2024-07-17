<!DOCTYPE html>
<html lang="en">
@if(isset(auth()->user()->role_id))
    <head>
        @stack('top_head')
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta id="metaTitle" property="og:title" content="">
        <meta id="metaImage" property="og:image" itemprop="image" content="">
        <link id="thumbnailUrl" itemprop="thumbnailUrl" href="">
        <meta property="og:image:width" content="950"/>
        <meta property="og:image:height" content="950"/>

        {{-- Hotjar Tracking Code for https://www.blitznet.co.id/ --}}
        @if (config('app.env') == "live")
            <script>
                (function(h,o,t,j,a,r){
                    h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                    h._hjSettings={hjid:3083366,hjsv:6};
                    a=o.getElementsByTagName('head')[0];
                    r=o.createElement('script');r.async=1;
                    r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                    a.appendChild(r);
                })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
            </script>
            {{-- <script src="//run.louassist.com/v2.5.1-m?id=601044422567"></script> --}}
        @endif
        {{-- End Hotjar Tracking Code --}}

        <meta id="metaDescription" name="description" content="">
        <meta id="metaAuthor" name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Hugo 0.84.0">
        <link rel="apple-touch-icon" sizes="57x57"   href="{{ URL::asset('front-assets/images/favicon/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60"   href="{{ URL::asset('front-assets/images/favicon/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72"   href="{{ URL::asset('front-assets/images/favicon/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76"   href="{{ URL::asset('front-assets/images/favicon/apple-icon-76x76.png') }}">
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
        <meta name="msapplication-TileImage" content="{{ URL::asset('front-assets/images/favicon/ms-icon-144x144.png') }}">
        <meta name="theme-color" content="#ffffff">
        <meta name="csrf-token" content="{!! csrf_token() !!}">
        <title>blitznet</title>

        {{--begin: CSS --}}
        <link href="{{ URL::asset('front-assets/css/front/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('/assets/css/admin/filter_multi_select.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('/assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/css/front/blitznet_user.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/css/front/koinworks.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/css/front/calender.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/css/front/swal-style.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.buttons.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/css/front/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/css/front/smart_wizard_all.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/css/front/owl.carousel.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/css/front/jquery.dataTables.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/js/datatables-bs4/dataTables.bootstrap4.css') }}" rel='stylesheet' />
        <link href="{{ URL::asset('front-assets/css/front/select2.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/library/jquery-ui-1.12.1.custom/jquery-ui.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/css/front/group_trading.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/css/front/fontawesome_5.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/intlTelInput/css/intlTelInput.css')}}" rel="stylesheet">
        <link href="{{ URL::asset('chat/css/chat.css')}}" rel="stylesheet">
        <link href="{{ URL::asset('/assets/vendors/jquery-toast-plugin/jquery.toast.min.css') }}" rel="stylesheet">
       <link rel="stylesheet" href="{{ URL::asset('assets/css/admin/datetime/nmp_flatpickr.min.css') }}">
       <link rel="stylesheet" href="{{ URL::asset('assets/css/admin/datetime/flatpickr.min.css') }}">

        <!--end: CSS -->
        @yield('css')

        {{--begin: JS --}}
        <script src="{{ URL::asset('front-assets/js/front/jquery-3.6.0.min.js') }}"></script>
        <script src="{{ mix('js/app.js') }}"></script> {{-- socket.io init --}}
        {{--end: JS --}}
        @yield('header-script')


        @stack('bottom_head')
        @livewireStyles
    </head>
@else
    @include('home/home_header_css')
    <link href="{{ URL::asset('front-assets/css/front/group_trading.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('front-assets/css/front/fontawesome_5.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('front-assets/css/front/group_trading_front.css') }}">
@endif

<body>
@if(isset(auth()->user()->role_id))
    @include('buyer.common.header.backend.header')
@else
    @include('home/header')
@endif

<div class="main_section position-relative group_section">
    <div class="@if(isset(auth()->user()->role_id)) container-fluid @else container-xl @endif">
        <div class="row gx-4 mx-lg-0">
            @include('buyer.common.sidebar.frontend.sidebar')

            @canany(['create buyer rfqs', 'publish buyer rfqs', 'publish buyer orders', 'create buyer join group', 'delete buyer join group', 'publish buyer payments', 'publish buyer address','approval buyer approval configurations','buyer rfn publish','buyer global rfn publish'])

                @yield('content')

            @else
               {{-- </div>--}}{{-- Extra div for Role permission condition--}}
                <div class="col-lg-8 col-xl-9 py-2 header_top d-flex align-items-center">
                    <div class="text-center w-100">
                        <h1>{{__('dashboard.no_permission_message')}}</h1>
                        <img src="{{ URL::asset('front-assets/images/no_permission.png') }}"
                             class="mw-100 mt-5">
                    </div>
                </div>
            @endcanany

            <button onclick="topFunction()" id="myBtn" title="Go to top" class="bg-warning shadow"><img
                    src="{{ URL::asset('front-assets/images/icons/uparrow.png') }}" alt=""></button>
        </div>
    </div>
</div>

@if(isset(auth()->user()->id))
    <div class="pt-3 text-center rowjustify-content-center sticky-bottom">
        <div class="col-lg-12 bg-light border-top">
            <div class="py-3">
                <small><span style="font-family: arial ;">&copy;</span>  {{ date("Y") }} {{__('dashboard.all_rights_reserved')}}</small>
            </div>
        </div>
    </div>
@endif

@if(!isset(auth()->user()->role_id))
    @include('home/footer')
@endif

@stack('top_scripts')
{{-- JavaScript Bundle with Popper --}}
<script src="{{ URL::asset('front-assets/js/front/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('/assets/vendors/datatables.net/1.10.25/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/parsley.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.buttons.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.nonblock.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/front/jquery.smartWizard.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/front/owl.carousel.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/admin/datetime/flatpickr.js') }}"></script>
<script src="{{ URL::asset('assets/js/admin/datetime/flatpickr.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/admin/datetime/npm_flatpickr.js') }}"></script>
<script src="{{ URL::asset('/assets/vendors/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/front/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/filter-multi-select-bundle.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/intlTelInput/js/intlTelInput.js') }}"></script>
<script src="{{ URL::asset('assets/vendors/jquery-toast-plugin/jquery.toast.min.js') }}"></script>

{{-- Share social media --}}
<script src="{{ URL::asset('front-assets/js/front/share.js') }}"></script>

{{---------------------------------------begin:App Common Js---------------------------------------------}}
<script src="{{ URL::asset('assets/js/custom/app.js') }}"></script>
{{---------------------------------------begin:App Common Js---------------------------------------------}}
<script src="{{ URL::asset('js/socket.js') }}"></script>
@yield('script')

<script>
    var group_chat_id_global = '';
    var userId = '{{ auth()->check() ? auth()->user()->id : 1 }}';
    var userRole = '{{ auth()->check() ? auth()->user()->role_id : 1 }}';
    var currentTime = '{{ changeTimeFormat(now()) }}';
</script>
<script src="{{ URL::asset('js/moment.js') }}"></script>
<script src="{{ URL::asset('js/chat-feature.js') }}"></script>
<script src="{{ URL::asset('home_design/js/aos.js') }}"></script>
<script src="{{ URL::asset('home_design/js/script.js') }}"></script>

@yield('custom-script')
<script type="text/javascript">
    /******************begin: Init jQuery Modules*********************/
    jQuery(document).ready(function(){
        SnippetFrontendMenus.init();
        SnippetFrontProfile.init();
        SnippetFrontDefaultInit.init();
    });
    /******************end: Init jQuery Modules*********************/

    /******************begin: Front Menus**********************/
    var SnippetFrontendMenus = function () {

        var userSettingsPermission = function () {
                $(document).ready(function () {
                    $.ajax({
                        url: '{{route('profile.redirection')}}',
                        type: 'POST',
                        data: {
                            '_token' : "{{csrf_token()}}"
                        },
                        success: function(data) {
                            setProfileSession(data.tab.mainTab, data.tab.secondTab);
                        },
                        error: function () {
                            console.log("Code - 500 | ErrorCode:001");
                        }

                    });
                });
            },
            setProfileSession = function(primaryTab, secondaryTab) {
                sessionStorage.clear();
                sessionStorage.setItem("profile-lastlocation", JSON.stringify({
                    mainTab: primaryTab,
                    secondTab: secondaryTab
                }));
            },
            frontendMenuLinks = function() {
                $(document).on('click','.front-menu',function (){
                    var primaryTab = $(this).attr('data-target-menu');

                    setFrontendSession(primaryTab);

                    location.replace('{{route('dashboard')}}')
                });
            },
            setFrontendSession = function(primaryTab) {
                sessionStorage.clear('redirect-type');
                sessionStorage.setItem("redirect-type",primaryTab);
            },
            loadDataCount = function () {
                $.ajax({
                    url: '{{ route('dashboard-default-ajax') }}',
                    type: 'GET',
                    success: function(successData) {
                        $('#myRfqCount').html(successData.rfqCount);
                        $('#myOrderCount').html(successData.ordersCount);
                        $('#myAddressCount').html(successData.addressCount);
                        $('#myPaymentCount').html(successData.supplierPaymentCount);
                        $('#myGroupsCount').html(successData.buyerGroupsCount);
                        $('#myApprovalCount').html(successData.approvalTabCount);
                        $('#myApprovalCount').html(successData.approvalTabCount);
                        $('#myrfnCount').html(successData.rfnList);
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            },
            getSideCountBuyerNotification = function (sideNotification = 'All') {
            $.ajax({
                url: "{{ route('get-side-buyer-count-ajax','') }}" + "/" + sideNotification,
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
        };

        return {
            init: function () {
                userSettingsPermission(),
                frontendMenuLinks(),
                loadDataCount(),
                getSideCountBuyerNotification()
            }
        }

    }(1);
    /******************end: Front Menus**********************/

    /******************begin: Front Profile**********************/
    var SnippetFrontProfile = function () {

        var companiesList = function() {
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
                                    html += '<span class="ms-auto alert alert-success mb-0 p-0 px-1" style="font-size: 0.8rem;" role="alert">' + '{{ __('admin.active')}}' + '</span>';
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

        },
        switchCompany = function() {
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
            });

        };

        return {
            init: function () {
                companiesList(),
                switchCompany()
            }
        }
    }(1);
    /******************end: Front Profile**********************/

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


</script>
<script>


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

    function userLogout(){
        sessionStorage.clear();
    }

    $(document).on('click', '.userActivityPageRedirect', function(e) {
        e.preventDefault();
        let id = $(this).attr('data-id');
        let type = $(this).attr('data-type');
        e.stopImmediatePropagation();
    })

    function backTo(url,type='',id='') {
        if(type && id){
            sessionStorage.setItem("redirect-type", type);
            sessionStorage.setItem("redirect-id", id);
        }
        location.href = url;
    }

    $(document).ready(function () {

        //@ekta invite supplier and friend
        $(document).on('click', '#saveInviteSupplierHeaderBtn', function(e) {
            window.Parsley.addValidator('uniqueemailsupplier', {
                validateString: function (value) {
                    let res = false;
                    xhr = $.ajax({
                        url: '{{ route('check-invite-user-email-exist') }}',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            email: value,
                        },
                        async: false,
                        success: function (data) {
                            res = data;
                        },
                    });
                    return res;
                },
                messages: {
                    en: '{{ __('admin.email_already_exist') }}'
                }
            });

            e.preventDefault();
            if ($('#inviteSupplierFormHeader').parsley().validate()) {
                var formData = new FormData($('#inviteSupplierFormHeader')[0]);
                $.ajax({
                    url: "{{ route('profile-invite-supplier-ajax') }}",
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,

                    success: function(successData) {
                        if(successData) {
                            $('.navbar-collapse').collapse('hide');
                            new PNotify({
                                text: "{{ __('admin.invite_buyer_send_alert') }}",
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 2000
                            });
                            //$('#collapseinvite').modal('hide');
                            $('#inviteSupplierFormHeader').parsley().reset();

                            setTimeout(function () {
                                location.reload(true);
                            }, 2000);
                        }
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        });

        $(document).click(function (event) {
            /// If *navbar-collapse* is not among targets of event
            if (!$(event.target).is('.navbar-collapse *')) {
                /// Collapse every *navbar-collapse*
                $('.navbar-collapse').collapse('hide');
                $('#inviteSupplierFormHeader').parsley().reset();
                $('#inviteSupplierFormHeader')[0].reset();
            }
        });
        @if(Request::segment(1) == 'dashboard')
        if(sessionStorage.getItem("redirectGetData")) {
            var redirectDetails = JSON.parse(sessionStorage.getItem("redirectGetData"));
            if (redirectDetails != '') {
                let type = redirectDetails.type;
                let id = redirectDetails.id;
                let quote_id = redirectDetails.quote_id;

            }
            sessionStorage.setItem("redirectGetData", '');
        }
        @endif
    })

    function number_format(number, decimals, dec_point, thousands_sep) {
        // Strip all characters but numerical ones.
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    //@ekta Redirect to invite supplier list view
    function redirectToInvite(){
        sessionStorage.clear();
        sessionStorage.setItem("profile-lastlocation", JSON.stringify({
            mainTab: "company-tab",
            secondTab: "invite_supplier_list"
        }));
        window.location='<?php echo e(route("profile")); ?>';
    }

    function addSubscribeUserAPI(formData){
        $.ajax({
            url: "{{ route('subsribe-user-ajax') }}",
            data: formData,
            type: "POST",
            contentType: false,
            processData: false,
            success: function(successData) {
                console.log(successData);
                return successData;

            },
            error: function() {
                console.log("error");
            },
        });
    }

    $(document).ready(function () {

        var companydetails = @json(Auth::user()->load('defaultCompany')->companies ?? '');
        if (companydetails.background_logo) {
            $('.logobanner_section').css('background-image', companydetails.background_logo);
        }

        $('body').click(function (e) {
            if (!$(e.target).is('#companyData')) {
                $("#collapseExample").collapse('hide');
            }
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
    });
    function topFunction() {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
</script>
@section('scripts')
    {{ view('notification_js') }}
@endsection
@yield('scripts')
@stack('bottom_scripts')
</body>
</html>
