@extends('dashboard/notification/layout')

@section('content')
<link href="{{ URL::asset('front-assets/js/front/crop/css/style.css') }}" rel="stylesheet">
<link href="{{ URL::asset('front-assets/js/front/crop/css/style-example.css') }}" rel="stylesheet">
<link href="{{ URL::asset('front-assets/js/front/crop/css/jquery.Jcrop.min.css') }}" rel="stylesheet">
<link href="{{ asset('front-assets/css/front/croppie.min.css') }}" rel='stylesheet' />
<script type="text/javascript" src="{{ URL::asset('front-assets/js/front/crop/scripts/jquery.Jcrop.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('front-assets/js/front/crop/scripts/jquery.SimpleCropper.js') }}"></script>
<script src='{{ asset("front-assets/js/front/croppie.js")}}'></script>
<style>
    .error {
        color: red;
    }

    #userCompanyDetailsForm .select2-hidden-accessible {
        width: 100% !important;
        height: 50px !important;
        position: relative !important;
        visibility: hidden;
    }

    #product_category_block .select2-container {
        width: 100% !important;
    }
    .swal-button--danger {
        background-color: #df4740 !important;
    }
    .notification_page .icon_bg{padding: 5px 8px; border-radius: 8px; background-color: #efefef;}
    .notification_page ul { padding-left: 0rem; }
    .notification_page ul li{ font-size: 12px; list-style: none; border-bottom: 1px solid #efefef; padding: 3px; }
    .notification_page ul li:hover{
        background-color: #eeeeee;
    }
    .notification_page ul li:last-child{ border-bottom: 0px solid #efefef !important; padding-bottom: 0px; }
    .notification_page small { font-size: .65rem; }
    .notification_page .recent { background-color: #e2eeff;}
    .notification_page .mark_read_btn{
        text-decoration: none; font-size: 11px; color: black;
        /* padding: 3px 8px; background-color: #efefef; border-radius: 10px; */
    }
</style>
<div class="main_section position-relative profile_section">
    <div class="header_top d-flex align-items-center pt-3 mt-1 mt-lg-0 px-5">
        <h1 class="mb-0 " style="font-size: 18px;">{{ __('dashboard.notification') }}</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-warning ms-auto btn-sm" id="backBtn" style="padding-top: .1rem;padding-bottom: .1rem;">
            <img src="{{ URL::asset('front-assets/images/icons/angle-left.png') }}" alt="">
            {{ __('profile.back') }}</a>
    </div>
    <div class="container-fluid ">
        <div class="row gx-4 mx-lg-0">
            <div class="col-lg-12 py-2">
                <div class="pt-2 d-flex justify-content-end mb-2 me-0">
                    <select class="form-select form-control-sm rounded-2 py-1" onchange="getBuyerNotificationFilterData()" id="notification_category" style="max-width: 120px;">
                        <option selected value="">{{ __('dashboard.notification_view_all') }}</option>
                        <option value="rfq">{{ __('admin.rfqs') }}</option>
                        <option value="quote">{{ __('admin.quotes') }}</option>
                        <option value="order">{{ __('admin.orders') }}</option>
                        <option value="loan">{{ __('admin.loans') }}</option>
                        {{--<option>Group</option>--}}
                        <option value="other">Others</option>
                    </select>
                </div>
                <div class="notification_page border border-radius">
                    <div class="not_header_dnd mt-2 d-none ">
                        <div class="d-flex align-items-center justify-content-sm-end">
                            <div class="ms-auto me-3 ">
                                <a href="#"  class="fw-bold mark_read_btn text-decoration-none " >Mark all as read</a>
                            </div>
                            <div class=" form-check  form-switch me-2 ">
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                <label class="form-check-label" for="flexSwitchCheckDefault"
                                       style="padding-top: 2px; font-size: 11px;">Do Not Disturb</label>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 pt-2">
                        <div id="notificationLists"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@stop

@push('bottom_scripts')
    <script>
        $(document).ready(function() {
            getBuyerNotificationFilterData();
        });

        function getBuyerNotificationFilterData(){
            var notificationCategory = $('#notification_category').val();
            $('#notificationLists').html('');
            var data = {
                "_token": "{{ csrf_token() }}",
                'notificationCategory': notificationCategory
            }
            //console.log(data);
            $.ajax({
                url: '{{ route('get-buyer-notification-filter-data-ajax') }}',
                data: data,
                type: 'POST',
                success: function(successData) {
                    $('#notificationLists').html(successData.notificationFilterDataView);
                },
            });
        }
    </script>
@endpush
