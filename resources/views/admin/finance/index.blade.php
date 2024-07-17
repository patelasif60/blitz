@extends('admin/adminLayout')
@section('content')

    @push('bottom_head')
        <link href="{{ URL::asset('/assets/css/admin/filter.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ URL::asset('/assets/vendors/ion-rangeslider/css/ion.rangeSlider.css') }}">
    @endpush
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">
            <div class="tab-content tab-transparent-content pb-0 dashboardcard">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body bg_Amount bg_Amount1" data-toggle="tooltip" title="{{ 'Rp '.number_format($totalOrderAmount,2) }}">
                                    <a class="stretched-link" href="#"></a>
                                    <div class="d-flex flex-wrap justify-content-between">
                                        <h4 class="card-title">{{__('admin.total_amount')}}</h4>
                                    </div>
                                    <div id="sales" class="carousel slide dashboard-widget-carousel position-static pt-2"
                                         data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <div class="d-flex flex-wrap align-items-baseline">
                                                    <h3 class="me-3">{{ numberToName((float)$totalOrderAmount) }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body bg_Amount bg_Amount1" data-toggle="tooltip" title="{{ 'Rp '.number_format($totalAmountReceived,2) }}">
                                    <a class="stretched-link" href="#"></a>
                                    <div class="d-flex flex-wrap justify-content-between">
                                        <h4 class="card-title">{{__('admin.received_amount')}}</h4>
                                    </div>
                                    <div id="sales" class="carousel slide dashboard-widget-carousel position-static pt-2"
                                         data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <div class="d-flex flex-wrap align-items-baseline">
                                                    <h3 class="me-3">{{ numberToName((float)$totalAmountReceived) }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body bg_Amount bg_Amount1" data-toggle="tooltip" title="{{ 'Rp '.number_format($totalAmountPending,2) }}">
                                    <a class="stretched-link" href="#"></a>
                                    <div class="d-flex flex-wrap justify-content-between">
                                        <h4 class="card-title">{{__('admin.pending_amount')}}</h4>
                                    </div>
                                    <div id="sales" class="carousel slide dashboard-widget-carousel position-static pt-2"
                                         data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <div class="d-flex flex-wrap align-items-baseline">
                                                    <h3 class="me-3">{{ numberToName((float)$totalAmountPending) }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body bg_Overdue bg_Overdue1" data-toggle="tooltip" title="{{ 'Rp '.number_format($totalOverdueAmount,2) }}">
                                    <a class="stretched-link" href="#" ></a>
                                    <div class="d-flex flex-wrap justify-content-between" >
                                        <h4 class="card-title">{{__('admin.overdue_amount')}}</h4>
                                    </div>
                                    <div id="sales" class="carousel slide dashboard-widget-carousel position-static pt-2"
                                         data-bs-ride="carousel" >
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <div class="d-flex flex-wrap align-items-baseline">
                                                    <h3 class="me-3" >{{ numberToName((float)$totalOverdueAmount) }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-12 col-md-12 col-xl-8 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body bg_xen bg_xen1" id="wallet-amount">
                                    <a class="stretched-link"
                                       href="#"></a>
                                    <div class="d-flex flex-wrap justify-content-between">
                                        <h4 class="card-title">{{__('admin.xen_platform_balance')}}</h4>
                                    </div>
                                    <div id="marketing"
                                         class="carousel slide dashboard-widget-carousel position-static pt-2"
                                         data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <div class="d-flex flex-wrap align-items-baseline">
                                                    <h3 class="me-3" id="xen_balance"><img height="16px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading"></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center pb-3">
                <h4 class="card-title pt-3 d-none">{{__('admin.finance')}}</h4>
                <div class="dropdown ms-auto me-1">
                    <div class="pe-1 mb-3 clearfix d-flex align-items-center">
						<span class="text-muted me-2" id="filter_count_one" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">
							0 {{__('admin.filter_applied')}}
						</span>
                        <div class="nav-item nav-settings" >
                            <a class="btn btn-dark btn-sm" style="padding: 0.25rem 0.5rem;" href="javascript:void(0)">{{__('admin.filter')}}</a>
                        </div>

                        <a href="{{ route('admin.finance.index.export') }}" class="btn btn-warning btn-sm ms-1" style="padding: 0.25rem 0.5rem;" type="button" id="dropdownMenuButton1" aria-expanded="false">
                            {{ __('admin.Export') }}
                        </a>
                    </div>
                </div>

                <!-- ---Filter---- -->
                <div id="right-sidebar" class="settings-panel filter shadow-lg"  style="z-index: 5;">
                    <div class="d-flex p-2 align-items-center position-sticky shadow">
                        <h4 class="mt-2 fw-bold">{{__('admin.filters')}}</h4>
                        <span class="badge badge-outline-primary ms-2" id="filter_count_two" style="padding: 0.125em 0.25em;">0</span>
                        <span class="ms-auto">
                            <a href="javascript:void(0)" id="clear_btn" class="btn btn-sm btn-dark  p-1 pe-1" style="font-size: 0.8em; margin-right: 0.2rem;" type="button" role="button">
                                {{__('admin.clear')}}
                            </a>
                            <a id="apply_filter_btn" href="javascript:void(0)" class="btn btn-primary btn-sm  p-1" style="font-size: 0.7em; margin-right: 1.75rem;" role="button">
                                {{__('admin.apply')}}
                            </a>
                        </span>
                        <a id="filter_close"><i class="settings-close mdi mdi-close"></i></a>
                    </div>

                    <div class="tab-content h-auto" id="setting-content" style="padding-top: 10px; ">
                        <div class="tab-pane fade show active scroll-wrapper" id="todo-section"
                             role="tabpanel" aria-labelledby="todo-section">
                            <div class="d-flex px-3 mb-0">
                                <form class="form w-100 d-none">
                                    <div class="form-group d-flex ">
                                        <input type="text" class="form-control todo-list-input" placeholder="{{__('admin.search')}}">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-search"></i></button>
                                    </div>
                                </form>
                            </div>
                            <div class="list-wrapper px-2 py-2" style="overflow-y: auto;">
                                <div class="accordion accordion-solid-header" id="accordion-4"
                                     role="tablist">
                                    <!--begin: Filter - order number list-->
                                    <div class="card filter-card">
                                        <div class="card-header" role="tab" id="heading-1">
                                            <h6 class="mb-0">
                                                <a data-bs-toggle="collapse" href="#collapse-1"
                                                   aria-expanded="false" aria-controls="collapse-1"
                                                   class="collapsed">
                                                    {{__('admin.order_number')}}
                                                </a>
                                            </h6>
                                        </div>
                                        <div id="collapse-1" class="collapse" role="tabpanel"
                                             aria-labelledby="heading-1" style="">
                                            <div class="card-body py-1 pe-1">
                                                <input class="form-control form-control-sm mb-2 filtersearch" id="order_number_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                                <div id="order_number_content">
                                                    @if($orders->count() > 0)
                                                        @foreach($orders as $order)
                                                            <div class="form-check d-flex align-items-center order_number_content">
                                                                <input type="checkbox" class="form-check-input order_number_checkbox" id="orderNumberCheck{{$order->id}}" data-id="{{$order->id}}">
                                                                <label class="form-check-label" for="orderNumberCheck{{$order->id}}">{{$order->order_number}}</label>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end: Filter - order number list-->

                                    <!--begin: Filter - company list-->
                                    <div class="card filter-card">
                                        <div class=" card-header" role="tab" id="heading-7">
                                            <h6 class="mb-0">
                                                <a class="collapsed" data-bs-toggle="collapse"
                                                   href="#collapse-7" aria-expanded="false"
                                                   aria-controls="collapse-7">
                                                    {{__('admin.customer_company_name')}}
                                                </a>
                                            </h6>
                                        </div>
                                        <div id="collapse-7" class="collapse" role="tabpanel"
                                             aria-labelledby="heading-7">
                                            <div class="card-body py-2">
                                                <input class="form-control form-control-sm mb-2 filtersearch"
                                                       id="buyer_company_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                                <div id="buyer_company_content">
                                                    @if($orders->count() > 0)
                                                        @foreach($companies as $company)
                                                            @if(!empty($company->companyDetails))
                                                                <div class="form-check d-flex align-items-center buyer_company_content">
                                                                    <input type="checkbox" class="form-check-input buyer_company_checkbox" id="buyerCompanyCheck{{$company->id}}" data-id="{{$company->company_id}}">
                                                                    <label class="form-check-label" for="buyerCompanyCheck{{$company->id}}">{{$company->companyDetails->name}}</label>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end: Filter - company list-->

                                    <!--begin: Filter - category list-->
                                    <div class="card filter-card">
                                        <div class=" card-header" role="tab" id="heading-9">
                                            <h6 class="mb-0">
                                                <a class="collapsed" data-bs-toggle="collapse"
                                                   href="#collapse-9" aria-expanded="false"
                                                   aria-controls="collapse-9">
                                                    {{__('admin.category')}}
                                                </a>
                                            </h6>
                                        </div>
                                        <div id="collapse-9" class="collapse" role="tabpanel"
                                             aria-labelledby="heading-9">
                                            <div class="card-body py-2">
                                                <input class="form-control form-control-sm mb-2 filtersearch"
                                                       id="category_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                                <div id="buyer_category_content">
                                                    @if($orders->count() > 0)
                                                        @foreach($categories as $category)
                                                            <div class="form-check d-flex align-items-center buyer_category_content">
                                                                <input type="checkbox" class="form-check-input buyer_category_checkbox" id="categoryCheck{{$category->id}}" data-id="{{$category->id}}">
                                                                <label class="form-check-label" for="categoryCheck{{$category->id}}">{{$category->name}}</label>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end: Filter - category list-->

                                    <!--begin: Filter - payment status list-->
                                    <div class="card filter-card">
                                        <div class=" card-header" role="tab" id="heading-10">
                                            <h6 class="mb-0">
                                                <a class="collapsed" data-bs-toggle="collapse"
                                                   href="#collapse-10" aria-expanded="false"
                                                   aria-controls="collapse-10">
                                                    {{__('admin.order_status')}}
                                                </a>
                                            </h6>
                                        </div>
                                        <div id="collapse-10" class="collapse" role="tabpanel"
                                             aria-labelledby="heading-10">
                                            <div class="card-body py-2">
                                                <div id="payment_status_content">
                                                    <div class="form-check d-flex align-items-center payment_status_content">
                                                        <input type="checkbox" class="form-check-input payment_status_checkbox" id="paymentStatusCheck1" data-id="1">
                                                        <label class="form-check-label" for="paymentStatusCheck1">{{__('admin.payment_pending')}}</label>
                                                    </div>
                                                    <div class="form-check d-flex align-items-center payment_status_content">
                                                        <input type="checkbox" class="form-check-input payment_status_checkbox" id="paymentStatusCheck2" data-id="2">
                                                        <label class="form-check-label" for="paymentStatusCheck2">{{__('admin.overdue')}}</label>
                                                    </div>
                                                    <div class="form-check d-flex align-items-center payment_status_content">
                                                        <input type="checkbox" class="form-check-input payment_status_checkbox" id="paymentStatusCheck3" data-id="3">
                                                        <label class="form-check-label" for="paymentStatusCheck3">{{__('admin.offline_paid')}}</label>
                                                    </div>
                                                    <div class="form-check d-flex align-items-center payment_status_content">
                                                        <input type="checkbox" class="form-check-input payment_status_checkbox" id="paymentStatusCheck4" data-id="4">
                                                        <label class="form-check-label" for="paymentStatusCheck4">{{__('admin.disbursement_pending')}}</label>
                                                    </div>
                                                    <div class="form-check d-flex align-items-center payment_status_content">
                                                        <input type="checkbox" class="form-check-input payment_status_checkbox" id="paymentStatusCheck5" data-id="5">
                                                        <label class="form-check-label" for="paymentStatusCheck5">{{__('admin.paid_to_supplier')}}</label>
                                                    </div>
                                                    <div class="form-check d-flex align-items-center payment_status_content">
                                                        <input type="checkbox" class="form-check-input payment_status_checkbox" id="paymentStatusCheck6" data-id="6">
                                                        <label class="form-check-label" for="paymentStatusCheck6">{{__('admin.cancelled')}}</label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end: Filter - payment status list-->

                                    <!--begin: Filter - price range-->
                                    <div class="card filter-card">
                                        <div class=" card-header" role="tab" id="heading-11">
                                            <h6 class="mb-0">
                                                <a class="collapsed" data-bs-toggle="collapse"
                                                   href="#collapse-11" aria-expanded="false"
                                                   aria-controls="collapse-11">
                                                    {{__('admin.price_range')}}
                                                </a>
                                            </h6>
                                        </div>
                                        <div id="collapse-11" class="collapse" role="tabpanel"
                                             aria-labelledby="heading-11">
                                            <div class="card-body py-2 p-3">
                                                <div class="mb-2">
                                                    <label for="filter_range_amount">{{__('admin.price_range')}}:</label>
                                                    <input type="text" id="filter_range_amount" readonly style="border:0; color:#25378b; font-weight:bold; width: 100%; font-size:12px">
                                                    <input type="hidden" id="filter_min_price" value="{{$orders->min('payment_amount')}}" data-value="{{$orders->min('payment_amount')}}">
                                                    <input type="hidden" id="filter_max_price" value="{{$orders->max('payment_amount')}}" data-value="{{$orders->max('payment_amount')}}"/>
                                                </div>

                                                <div class="slider-wrap">
                                                    <input type="text" id="filter_price_range" name="filter_price_range" value="" />
                                                </div>
                                                <!-- <div id="slider-range"></div> -->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end: Filter - price range-->

                                    <!--begin: Filter - received date-->
                                    <div class="card filter-card">
                                        <div class=" card-header" role="tab" id="heading-6">
                                            <h6 class="mb-0">
                                                <a class="collapsed" data-bs-toggle="collapse"
                                                   href="#collapse-6" aria-expanded="false"
                                                   aria-controls="collapse-6">
                                                    {{__('admin.order_received_date')}}
                                                </a>
                                            </h6>
                                        </div>
                                        <div id="collapse-6" class="collapse" role="tabpanel"
                                             aria-labelledby="heading-6">
                                            <div class="card-body py-2">
                                                <div class="input-group input-daterange">
                                                    <input type="text" class="form-control ps-2" value="" readonly id="filter_start_date">
                                                    <div class="input-group-addon ps-2 pe-2">to</div>
                                                    <input type="text" class="form-control pe-2" value="" readonly id="filter_end_date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end: Filter - received date-->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ---Filter---- -->

                <input type="hidden" value="{!! csrf_token() !!}" name="_token">
            </div>
            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-hover dataTable no-footer" style="width: 100%; height: 100%;" role="grid" aria-describedby="financeTable_info"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal version2 fade" id="staticBackdrop" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="getSingleOrderDetail">
            </div>
        </div>
    </div>

    @push('bottom_scripts')
        <script src="{{ URL::asset('/assets/vendors/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
        <script type="text/javascript">

        /******************begin: Jquery Module Initiate*********************/
        jQuery(document).ready(function(){
            SnippetFinanceTab.init();

        });
        /******************end: Jquery Module Initiate*********************/

        /****************************************begin:Buyer Backend Sidebar***************************************/
        var SnippetFinanceTab = function(){

            var financeDatatable = function () {

                var financeTable = $('.table').DataTable({
                    serverSide: !0,
                    paginate: !0,
                    processing: !0,
                    lengthMenu: [
                        [10, 25, 50],
                        [10, 25, 50],
                    ],
                    footer:!1,
                    ajax: {
                        url     : "{{route('admin.finance.index.json')}}",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data : function (data){
                            filterData = getFilterData();
                            data.filterData = filterData;
                        },
                        method  : "POST",
                    },
                    columns: [
                        {data: "order_number", title: "{{__('admin.order_number')}}"},
                        {data: "company_name", title: "{{__('admin.customer_company')}}"},
                        {data: "date", title: "{{ __('admin.date')}}"},
                        {data: "product", title: "{{ __('admin.product') }}"},
                        {data: "category", title: "{{ __('admin.category') }}"},
                        {data: "price", title: "{{ __('admin.price') }}", class: "text-nowrap"},
                        {data: "source", title: "{{ __('admin.source') }}", class: "badgesection text-center"},
                        {data: "payment_terms", title: "{{ __('admin.payment_terms') }}", class: "badgesection text-center"},
                        {data: "order_status", title: "{{ __('admin.order_status') }}", class: "badgesection text-center"},
                        {data: "overdue_date", title: "{{ __('admin.overdue_date') }}"},
                        {data: "actions", title: "{{ __('admin.actions') }}" , class: "text-nowrap text-end iconsection"}
                    ],
                    aoColumnDefs: [
                        { "bSortable": true, "aTargets": [0] },
                        { "bSortable": false, "aTargets": [ 3, 4, 6, 7, 8, 10 ] }
                    ],
                    language: {
                        search: "{{__('admin.search')}}",
                        loadingRecords: "{{__('admin.please_wait_loading')}}",
                        processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span> {{__('admin.loading')}}..</span> '
                    },
                    order: [[0, 'desc']],

                });

                return financeTable;
            },

            financeCashDatatable = function () {

                financeDatatable().draw();
            },

            initPriceRangeSlider = function (min, max) {

                $('#filter_min_price').val(min);
                $('#filter_max_price').val(max);

                $("#filter_price_range").ionRangeSlider({
                    type: "double",
                    min: min,
                    max: max,
                    grid: false,
                    hide_min_max: true,
                    hide_from_to: true,
                    onChange: function (data) {
                        $('#filter_min_price').val(data.from_pretty.replace( /\s/g, ''));
                        $('#filter_max_price').val(data.to_pretty.replace( /\s/g, ''));
                        let from_changed = parseInt(data.from_pretty.replace( /\s/g, ''));
                        let to_changed = parseInt(data.to_pretty.replace( /\s/g, ''));
                        $("#filter_range_amount").val(from_changed.toLocaleString() + " - " + to_changed.toLocaleString());
                    }
                });
                let init_from_changed = parseInt($('#filter_min_price').val().replace( /\s/g, ''));
                let init_to_changed = parseInt($('#filter_max_price').val().replace( /\s/g, ''));
                $("#filter_range_amount").val(init_from_changed.toLocaleString() + " - " + init_to_changed.toLocaleString());
            },

            setPriceRangeSlider = function () {

                $("#filter_range_amount").val($("#filter_min_price").val() + " - " + $("#filter_max_price").val());

                initPriceRangeSlider($("#filter_min_price").val(), $("#filter_max_price").val());
            },

            getFilterData = function (){

                var filterCount = 0;
                var data = {};

                let order_ids = [];
                let checkedOrder = $('.order_number_checkbox:checkbox:checked');
                $.each(checkedOrder, function(key, value){
                    order_ids.push($(value).attr('data-id'));
                });
                if(order_ids.length > 0){
                    data.order = order_ids;
                    filterCount++;
                }

                let company_ids = [];
                let checkedCompany = $('.buyer_company_checkbox:checkbox:checked');
                $.each(checkedCompany, function(key, value){
                    company_ids.push($(value).attr('data-id'));
                });
                if(company_ids.length > 0){
                    data.company = company_ids;
                    filterCount++;
                }

                let category_ids = [];
                let checkedCategory = $('.buyer_category_checkbox:checkbox:checked');
                $.each(checkedCategory, function(key, value){
                    category_ids.push($(value).attr('data-id'));
                });
                if(category_ids.length > 0){
                    data.category = category_ids;
                    filterCount++;
                }

                let payment_status_ids = [];
                let checkedPaymentStatus = $('.payment_status_checkbox:checkbox:checked');
                $.each(checkedPaymentStatus, function(key, value){
                    payment_status_ids.push($(value).attr('data-id'));
                });
                if(payment_status_ids.length > 0){
                    data.payment_status = payment_status_ids;
                    filterCount++;
                }

                let min_price = $('#filter_min_price').val();
                let max_price = $('#filter_max_price').val();
                let price_range = [];
                if(min_price && max_price){
                    data.min_price = min_price;
                    data.max_price = max_price;

                    if (data.min_price != $('#filter_min_price').attr('data-value') || data.max_price != $('#filter_max_price').attr('data-value')) {
                        filterCount++;
                    }

                }


                let start_date = $('#filter_start_date').val();
                let end_date = $('#filter_end_date').val();
                let date_range = [];
                if(start_date && end_date){
                    data.start_date = start_date;
                    data.end_date = end_date;
                    filterCount++;
                }
                $('#filter_count_one').text(filterCount+" {{__('admin.filter_applied')}}");
                $('#filter_count_two').text(filterCount);

                return data;

            },

            resetFilters = function () {
                /* clear search input and reset div elements */
                $("#order_number_filtersearch").val("");
                searchFilterContent($("#order_number_filtersearch"), '#order_number_content div');

                $("#buyer_company_filtersearch").val("");
                searchFilterContent($("#buyer_company_filtersearch"), '#buyer_company_content div');

                $("#category_filtersearch").val("");
                searchFilterContent($("#category_filtersearch"), '#buyer_category_content div');

                $(".order_number_checkbox").prop( "checked", false);
                $(".buyer_company_checkbox").prop( "checked", false);
                $(".buyer_category_checkbox").prop( "checked", false);
                $(".payment_status_checkbox").prop( "checked", false);
                $('#filter_count_one').text("0 {{__('admin.filter_applied')}}");
                $('#filter_count_two').text(0);

                /* clear date and reset datepicker */
                $("#filter_start_date").val("");
                $("#filter_end_date").val("");

                /* clear date and reset datepicker */

                $("#filter_price_range").data("ionRangeSlider").reset();
                $("#filter_range_amount").val($("#filter_min_price").attr('data-value') + " - " + $("#filter_max_price").attr('data-value'));
                $("#filter_min_price").val($("#filter_min_price").attr('data-value'));
                $("#filter_max_price").val($("#filter_max_price").attr('data-value'));
                // setPriceSlider();

                // datepickerReset();
                $('.input-daterange .form-control').datepicker('clearDates');
            },

            searchFilterContent = function (currentElement, ChildElement) {
                // Retrieve the input field text and reset the count to zero
                var filter = currentElement.val(), count = 0;
                // Loop through the comment list
                $(ChildElement).each(function() {
                    // If the list item does not contain the text phrase fade it out
                    if ($(this).find("label").text().search(new RegExp(filter, "i")) < 0) {
                        $(this).addClass('d-none');
                        $(this).removeClass('d-flex');
                        $(this).find("label").addClass('d-flex')
                        // Show the list item if the phrase matches and increase the count by 1
                    } else {
                        $(this).removeClass('d-none');
                        $(this).addClass('d-flex');
                        $(this).find("label").removeClass('d-flex')
                        count++;
                    }
                });
            },

            applyFilter = function () {
                $('#apply_filter_btn').on('click','', function(){
                    let data = getFilterData();
                    $(".table").DataTable().destroy();
                    financeCashDatatable();
                });
            },

            applyClear = function () {
                $('#clear_btn').on('click','', function(){
                    resetFilters();
                    $(".table").DataTable().destroy();
                    financeCashDatatable();
                });
            },

            searchFilter = function () {
                $("#order_number_filtersearch").keyup(function() {
                    searchFilterContent($(this), '#order_number_content div');
                });

                $("#buyer_company_filtersearch").keyup(function() {
                    searchFilterContent($(this), '#buyer_company_content div');
                });

                $("#category_filtersearch").keyup(function() {
                    searchFilterContent($(this), '#buyer_category_content div');
                });
            },

            viewOrderDetail = function () {
                $(document).on('click', '.getSingleOrderDetail', function() {
                    var orderId = $(this).attr('data-id');
                    $.ajax({
                        url: "{{ route('get-single-order-detail-ajax', '') }}" + "/" +
                            orderId,
                        type: 'GET',
                        success: function(successData) {
                            console.log(successData);
                            if (successData.html) {
                                $('#getSingleOrderDetail').html(successData.html);
                                $('#staticBackdrop').modal('show');
                            }
                        },
                        error: function() {
                            console.log('error');
                        }
                    });
                });
            },

            downloadZip = function () {
                $(document).on('click', '.getAttachment', function(e) {
                    e.preventDefault();
                    var orderId = $(this).attr('data-id');
                    var targetUrl = "{{url('admin/finance/attachment/zip')}}";
                    $.ajax({
                        url: targetUrl,
                        data: {id : orderId},
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        type: 'POST',
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function (data) {

                            var binaryData = [];
                            binaryData.push(data);
                            var blob = new Blob(binaryData, {type: "application/zip"});
                            var link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = "{{ __('admin.finance') }}";
                            link.click();

                        },
                        error: function() {
                            new PNotify({
                                text: "{{ __('admin.no_attachment_available') }}",
                                type: 'error',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 2000
                            });

                        }
                    });
                });
            },

            getXenBalance = function() {
                $.ajax({
                    url: "{{ route('admin.finance.xendit.balance') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#xen_balance').html('Rp ' + (data.data ? data.data : 0));
                    },
                    error: function (error) {
                        $('#xen_balance').html('Rp 0');
                    }
                });
            };


            return {
                init: function () {
                    financeCashDatatable(),
                    setPriceRangeSlider(),
                    getFilterData(),
                    applyFilter(),
                    applyClear(),
                    searchFilter(),
                    viewOrderDetail(),
                    downloadZip(),
                    getXenBalance()
                }
            }


        }(1);
        /**
         * Download function of Image
         *
         * */
        function downloadimg(id, fieldName, name){
            event.preventDefault();
            var data = {
                id: id,
                fieldName: fieldName
            }
            $.ajax({
                url: "{{ route('download-image-ajax') }}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: data,
                type: 'POST',
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (response) {
                    var blob = new Blob([response]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = name;
                    link.click();
                },
            });
        }

        /**
         * Download function of Order Letter
         *
         * */
        function downloadOrderLatter(id,name){
            let data = {
                id: id
            }
            let that = $(this);
            $.ajax({
                url: "{{ route('download-order-latter-ajax') }}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: data,
                type: 'POST',
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (response) {
                    let blob = new Blob([response]);
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = name;
                    link.click();
                    that.closest("form")[0].reset();
                },
            });
        }
        /****************************************end:Buyer Backend Sidebar***************************************/

    </script>
    @endpush
@stop
