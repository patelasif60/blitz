<!DOCTYPE html>
<html lang="en">
@if(isset(auth()->user()->role_id))
    <head>
        @stack('top_head')
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> --}}

        <meta id="metaTitle" property="og:title" content="">
        <meta id="metaImage" property="og:image" itemprop="image" content="">
        <link id="thumbnailUrl" itemprop="thumbnailUrl" href="">
        <meta property="og:image:width" content="950"/>
        <meta property="og:image:height" content="950"/>

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
        {{--LOU Code
            <!-- <script src="//run.louassist.com/v2.5.1-m?id=601044422567"></script> -->

        @elseif(config('app.env') == "staging")
            <!-- <script src="//run.louassist.com/v2.5.1-m?id=601044422567"></script> -->
        @elseif(config('app.env') == "local")
            <!-- <script src="//run.louassist.com/v2.5.1-m?id=601044422567"></script> -->
        @endif --}}

        <!-- End Hotjar Tracking Code -->

        <meta id="metaDescription" name="description" content="">
        <meta id="metaAuthor" name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
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

        <!-- CSS only -->
        <link href="{{ URL::asset('front-assets/css/front/bootstrap.min.css') }}" rel="stylesheet">
        {{--<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script> -->--}}
        <script src="{{ URL::asset('front-assets/js/front/jquery-3.6.0.min.js') }}"></script>
        <link rel="stylesheet" href="{{ URL::asset('/assets/css/admin/filter_multi_select.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
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
        {{--<!-- <link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet"> -->--}}
        <link href="{{ URL::asset('front-assets/css/front/jquery.dataTables.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/js/datatables-bs4/dataTables.bootstrap4.css') }}" rel='stylesheet' />
        {{--<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"> -->--}}
        <link href="{{ URL::asset('front-assets/css/front/select2.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/library/jquery-ui-1.12.1.custom/jquery-ui.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/css/front/group_trading.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ URL::asset('front-assets/css/front/fontawesome_5.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('assets/css/admin/datetime/nmp_flatpickr.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('assets/css/admin/datetime/flatpickr.min.css') }}">
        <!-- Share social media -->
        {{--<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/> -->--}}
        {{-- socket.io init --}}
        <script src="//run.louassist.com/v2.5.1-m?id=653988744203"></script>
        <script src="{{ mix('js/app.js') }}"></script>
        @stack('bottom_head')
    </head>
@else
    @include('home/home_header_css')
    <link href="{{ URL::asset('front-assets/css/front/group_trading.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('front-assets/css/front/fontawesome_5.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('front-assets/css/front/group_trading_front.css') }}">
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
@endif

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
@if (config('app.env') == "live" || config('app.env') == "production")
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TMKZNTK"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
@endif
    @if(isset(auth()->user()->role_id))
        @include('dashboard/layout/front_header')
    @else
        @include('home/header')
    @endif

    <div class="main_section position-relative">
        <div class="@if(isset(auth()->user()->role_id)) container-fluid @else container @endif">
            <div class="row gx-4 mx-lg-0">
                @yield('content')
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
<!-- JavaScript Bundle with Popper -->
<script src="{{ URL::asset('front-assets/js/front/bootstrap.bundle.min.js') }}"></script>
{{--<!-- <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> -->--}}
<!-- <script src="{{ URL::asset('/assets/vendors/datatables.net/1.10.25/jquery.dataTables.min.js') }}"></script> -->

<script type="text/javascript" src="{{ URL::asset('front-assets/js/datatables/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('front-assets/js/datatables-bs4/dataTables.bootstrap4.js') }}"></script>

<script src="{{ URL::asset('front-assets/js/parsley.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.buttons.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.nonblock.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/front/jquery.smartWizard.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/front/owl.carousel.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/admin/datetime/flatpickr.js') }}"></script>
<script src="{{ URL::asset('assets/js/admin/datetime/flatpickr.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/admin/datetime/npm_flatpickr.js') }}"></script>
{{--<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></scrip SnippetRFQDeliveryDetailAddresst> -->--}}
<script src="{{ URL::asset('/assets/vendors/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/front/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/filter-multi-select-bundle.min.js') }}"></script>

<script src="{{ URL::asset('front-assets/intlTelInput/js/intlTelInput.js') }}"></script>

<!-- Share social media -->
<script src="{{ URL::asset('front-assets/js/front/share.js') }}"></script>

<!---------------------------------------begin:App Common Js--------------------------------------------->
<script src="{{ URL::asset('assets/js/custom/app.js') }}"></script>
<script src="{{ URL::asset('assets/js/custom/otp.js') }}"></script>
<script src="{{ URL::asset('assets/js/custom/permissions.js') }}"></script>

<!---------------------------------------begin:App Common Js--------------------------------------------->
    <script src="{{ URL::asset('js/socket.js') }}"></script>
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
<script>

    $(function () {
            $('[data-toggle="tooltip"]').tooltip({ trigger : 'hover'});

        });
        $(document).ready(function() {

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

    // setInterval(function() {
    //     loadUserActivityData();
    // }, 5000);
    function userLogout(){
        sessionStorage.clear();
    }

    /*setInterval(function() {
        //getUserActivityNewDataCount();
    }, 5000);*/
   /* window.Echo.channel('buyer-notification-chanel').listen('.listen', (e) => {
        getUserActivityNewDataCount();
    });*/

    $(window).on("load", function() {
        getSideCountBuyerNotification();
    });

    function getSideCountBuyerNotification(sideNotification = 'All') {
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
    }



    /*function markAsAll(e) {
        e.preventDefault()
        e.stopImmediatePropagation();
        $.ajax({
            url: "{{ route('buyer-mark-as-all-ajax') }}",
            type: 'GET',
            success: function(successData) {
                $('#showCounterNotification').html('');
                $('#userActivitySection').html(successData.userActivityHtml);
            },
            error: function() {
                console.log('error');
            }
        });
    }*/

    /*function loadUserActivityData() {

        $.ajax({
            url: "{{ route('dashboard-user-activity-ajax') }}",
            type: 'GET',
            success: function(successData) {
                $('#showCounterNotification').html('');
                $('#userActivitySection').html(successData.userActivityHtml);
            },
            error: function() {
                console.log('error');
            }
        });
    }*/

    /*function getUserActivityNewDataCount() {
        $.ajax({
            url: "{{ route('dashboard-user-activity-new-data-count-ajax') }}",
            type: 'GET',
            success: function(successData) {
                if (successData.userActivityNewDataCount != 0) {
                    $('#showCounterNotification').html('');
                    $('#showCounterNotification').html('<div class="counter">'+successData.userActivityNewDataCount+'</div>');
                }
            },
            error: function() {
                console.log('error');
            }
        });
    }*/

    /*$(document).on('click', '#userActivityBtn', function() {
        loadUserActivityData();
    });*/


    $(document).on('click', '.userActivityPageRedirect', function(e) {
        e.preventDefault();
        let id = $(this).attr('data-id');
        let type = $(this).attr('data-type');
        e.stopImmediatePropagation();
        redirectTo(type,id);
    })

    function backTo(url,type='',id='') {
        if(type && id){
            sessionStorage.setItem("redirect-type", type);
            sessionStorage.setItem("redirect-id", id);
        }
        location.href = url;
    }

    $(document).ready(function () {
        let type = sessionStorage.getItem("redirect-type");
        let id = sessionStorage.getItem("redirect-id");
        if(type){
            sessionStorage.removeItem('redirect-type');
            sessionStorage.removeItem('redirect-id');
            redirectTo(type,id);
        }else{
            loadDefaultData();
        }

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
                setTimeout(function () {
                    redirectTo(type, id, quote_id);
                }, 600);
            }
            sessionStorage.setItem("redirectGetData", '');
        }
        @endif
    })

    function redirectTo(type,id, quote_id = 0) {
        loadDataCount();
        if (type == 'order') {
            sessionStorage.setItem("dashboard-lastlocation", "orderSection");
            $('#rfqSection').removeClass('btnactive');
            $('#addressSection').removeClass('btnactive');
            $('#postRequirementSection').removeClass('btnactive');

            $('#orderSection').addClass('btnactive');
            $.ajax({
                url: '{{ route('dashboard-list-order-ajax') }}',
                type: 'GET',
                success: function(successData) {
                    orderSection = successData.html;
                    $('#mainContentSection').html(successData.html);
                    if (id!=null && id!="null") {
                        $('#collapse' + id).collapse('show');
                        var top = $("#collapse" + id).offset().top;
                        $('html, body').animate({
                            scrollTop: top - 100
                        }, 100);
                    }
                },
                error: function() {
                    console.log('error');
                }
            });
        } else if (type == 'address') {
            sessionStorage.setItem("dashboard-lastlocation", "addressSection");
            $('#orderSection').removeClass('btnactive');
            $('#rfqSection').removeClass('btnactive');
            $('#postRequirementSection').removeClass('btnactive');

            $('#addressSection').addClass('btnactive');

            $.ajax({
                url: '{{ route('dashboard-list-address-ajax') }}',
                type: 'GET',
                success: function(successData) {
                    addressSection = successData.html;
                    $('#mainContentSection').html(successData.html);
                    if (id!=null && id!="null") {
                        var top = $("#addressSectionBlock" + id).offset().top;
                        $('html, body').animate({
                            scrollTop: top - 100
                        }, 100);
                    }
                },
                error: function() {
                    console.log('error');
                }
            });
        } else if (type == 'rfq') {
            sessionStorage.setItem("dashboard-lastlocation", "rfqSection");
            $('#orderSection').removeClass('btnactive');
            $('#addressSection').removeClass('btnactive');
            $('#postRequirementSection').removeClass('btnactive');

            $('#rfqSection').addClass('btnactive');

            $.ajax({
                url: '{{ route('dashboard-list-rfq-ajax') }}',
                type: 'GET',
                success: function(successData) {
                    rfqSection = successData.html;
                    $('#mainContentSection').html(successData.html);
                    if (id!=null && id!="null") {
                        $('#collapse' + id).collapse('show');
                        var top = $("#collapse" + id).offset().top;
                        $('html, body').animate({
                            scrollTop: top - 100
                        }, 100);

                        if (quote_id) {
                            setTimeout(function () {
                                $('#sub_collapse' + quote_id).collapse('show');
                            }, 800);
                        }
                    }
                },
                error: function() {
                    console.log('error');
                }
            });
        } else if (type == 'quote') {
            sessionStorage.setItem("dashboard-lastlocation", "rfqSection");
            $('#orderSection').removeClass('btnactive');
            $('#addressSection').removeClass('btnactive');
            $('#postRequirementSection').removeClass('btnactive');

            $('#rfqSection').addClass('btnactive');

            $.ajax({
                url: '{{ route('dashboard-list-rfq-ajax') }}',
                type: 'GET',
                success: function(successData) {
                    rfqSection = successData.html;
                    $('#mainContentSection').html(successData.html);
                    if (id!=null && id!="null") {
                        $('#collapse' + id).collapse('show');
                        var top = $("#collapse" + id).offset().top;
                        $('html, body').animate({
                            scrollTop: top - 100
                        }, 100);
                    }
                },
                error: function() {
                    console.log('error');
                }
            });
        } else if (type == 'payment') {
            sessionStorage.setItem("dashboard-lastlocation", "paymentSection");
            $('#orderSection').removeClass('btnactive');
            $('#addressSection').removeClass('btnactive');
            $('#postRequirementSection').removeClass('btnactive');

            $('#paymentSection').addClass('btnactive');

            $.ajax({
                url: '{{ route('dashboard-list-payment-ajax') }}',
                type: 'GET',
                success: function(successData) {
                    paymentSection = successData.html;
                    $('#mainContentSection').html(successData.html);
                    if (id!=null && id!="null") {
                        $('#collapse' + id).collapse('show');
                        var top = $("#collapse" + id).offset().top;
                        $('html, body').animate({
                            scrollTop: top - 100
                        }, 100);
                    }

                },
                error: function() {
                    console.log('error');
                }
            });
        } else if (type == 'group') {
            sessionStorage.setItem("dashboard-lastlocation", "myGroupsSection");
            $('#orderSection').removeClass('btnactive');
            $('#addressSection').removeClass('btnactive');
            $('#postRequirementSection').removeClass('btnactive');

            $('#myGroupsSection').addClass('btnactive');

            $.ajax({
                url: '{{ route('dashboard-group-listing') }}',
                type: 'GET',
                success: function(successData) {
                    myGroupsSection = successData.html;
                    $('#mainContentSection').html(successData.html);
                    if (id!=null && id!="null") {
                        $('#collapse' + id).collapse('show');
                        var top = $("#collapse" + id).offset().top;
                        $('html, body').animate({
                            scrollTop: top - 100
                        }, 100);
                    }

                },
                error: function() {
                    console.log('error');
                }
            });
        }

    }

    let loadDataCount = function () {
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
                $('#myrfnCount').html(successData.rfnList);
            },
            error: function() {
                console.log('error');
            }
        });
    }

    function loadDefaultData() {
        if(sessionStorage.getItem("dashboard-lastlocation")){
            loadDataCount();
            setTimeout(function(){
                $('#'+sessionStorage.getItem("dashboard-lastlocation")).click();
            },200);
        }else {
            $.ajax({
                url: '{{ route('dashboard-default-ajax') }}',
                type: 'GET',
                success: function(successData) {
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
                error: function() {
                    console.log('error');
                }
            });
        }
    }

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

    /******************begin: Profile Redirection**********************/

    var SnippetProfileRedirection = function () {

        var userSettingsPermission = function () {
            $(document).ready(function () {
                $.ajax({
                    url: '{{ route('profile.redirection') }}',
                    type: 'POST',
                    data: {
                        '_token' : "{{csrf_token()}}"
                    },
                    success: function(data) {
                        setSession(data.tab.mainTab, data.tab.secondTab);
                    },
                    error: function () {
                        console.log("error");
                    }

                });
            });
        },
        setSession = function(primaryTab, secondaryTab) {
            sessionStorage.clear();
            sessionStorage.setItem("profile-lastlocation", JSON.stringify({
                mainTab: primaryTab,
                secondTab: secondaryTab
            }));
        };


        return {
            init: function () {
                userSettingsPermission()
            }
        }

    }(1);

    jQuery(document).ready(function(){
        SnippetProfileRedirection.init();
    });

    /******************end: Profile Redirection**********************/

    LOU.identify(userId, {
        company: 'Example Company', // Replace this object with any user data for defining segments
        permissions: 'admin',
        plan: 'premium',
    })
</script>
@section('scripts')
    {{ view('notification_js') }}
@endsection
@yield('scripts')
@stack('bottom_scripts')
</body>
</html>
