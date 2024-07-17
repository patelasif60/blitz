@extends('admin/adminLayout')
@section('content')

    @push('bottom_head')
        <link href="{{ URL::asset('/assets/css/admin/filter.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ URL::asset('/assets/vendors/ion-rangeslider/css/ion.rangeSlider.css') }}">
    @endpush

    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">

            <div class="d-flex align-items-center pb-3">
                <h4 class="card-title pt-3">{{__('admin.upcoming_payments')}}</h4>
                <div class="dropdown ms-auto me-1">
                    <div class="pe-1 mb-3 clearfix d-flex align-items-center">
						<span class="text-muted me-2" id="filter_count_one" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">
							0 {{__('admin.filter_applied')}}
						</span>
                        <div class="nav-item nav-settings" >
                            <a class="btn btn-dark btn-sm" style="padding: 0.25rem 0.5rem;" href="javascript:void(0)">{{__('admin.filters')}}</a>
                        </div>
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
                        <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
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
                                <div class="accordion accordion-solid-header" id="accordion-4" role="tablist">

                                    <!--begin: Filter - loan number list-->
                                    <div class="card filter-card">
                                        <div class="card-header" role="tab" id="heading-1">
                                            <h6 class="mb-0">
                                                <a data-bs-toggle="collapse" href="#collapseLoanNumber" aria-expanded="false" aria-controls="collapseLoanNumber" class="collapsed">
                                                    {{__('admin.loan_id')}}
                                                </a>
                                            </h6>
                                        </div>
                                        <div id="collapseLoanNumber" class="collapse" role="tabpanel" aria-labelledby="heading-1">
                                            <div class="card-body py-1 pe-1">
                                                <input class="form-control form-control-sm mb-2 filtersearch" id="loan_number_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                                <div id="loan_number_content">
                                                    @if($loanNumbers->count() > 0)
                                                        @foreach($loanNumbers as $loanNumber)
                                                            <div class="form-check d-flex align-items-center loan_number_content">
                                                                <input type="checkbox" class="form-check-input loan_number_checkbox" id="loanNumberCheck{{$loanNumber->loan_number}}" data-id="{{$loanNumber->loan_number}}">
                                                                <label class="form-check-label" for="loanNumberCheck{{$loanNumber->loan_number}}">{{$loanNumber->loan_number}}</label>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end: Filter - loan number list-->

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
                                        <div id="collapse-1" class="collapse" role="tabpanel" aria-labelledby="heading-1">
                                            <div class="card-body py-1 pe-1">
                                                <input class="form-control form-control-sm mb-2 filtersearch" id="order_number_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                                <div id="order_number_content">
                                                    @if($orders->count() > 0)
                                                        @foreach($orders as $order)
                                                            <div class="form-check d-flex align-items-center order_number_content">
                                                                <input type="checkbox" class="form-check-input order_number_checkbox" id="orderNumberCheck{{$order->orders->id}}" data-id="{{$order->orders->id}}">
                                                                <label class="form-check-label" for="orderNumberCheck{{$order->orders->id}}">{{$order->orders->order_number}}</label>
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
                                                    @if($companies->count() > 0)
                                                        @foreach($companies as $company)
                                                            @if(!empty($company->companies))
                                                                <div class="form-check d-flex align-items-center buyer_company_content">
                                                                    <input type="checkbox" class="form-check-input buyer_company_checkbox" id="buyerCompanyCheck{{$company->company_id}}" data-id="{{$company->company_id}}" data-name="{{$company->companies->name}}">
                                                                    <label class="form-check-label" for="buyerCompanyCheck{{$company->company_id}}">{{$company->companies->name}}</label>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end: Filter - company list-->

                                    <!--begin: Filter - due date-->
                                    <div class="card filter-card">
                                        <div class=" card-header" role="tab" id="heading-6">
                                            <h6 class="mb-0">
                                                <a class="collapsed" data-bs-toggle="collapse"
                                                   href="#collapse-6" aria-expanded="false"
                                                   aria-controls="collapse-6">
                                                    {{__('admin.due_date')}}
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
                                    <!--begin: Filter - due date-->

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

    @push('bottom_scripts')
        <script src="{{ URL::asset('/assets/vendors/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
        <script type="text/javascript">

        /******************begin: Jquery Module Initiate*********************/
        jQuery(document).ready(function(){
            paymentDueTab.init();
        });
        /******************end: Jquery Module Initiate*********************/

        /****************************************begin:Buyer Backend Sidebar***************************************/
        var paymentDueTab = function(){

            var paymentDueDatatable = function () {

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
                        url     : "{{route('admin.payments.upcoming.payments')}}",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data : function (data){
                            filterData = getFilterData();
                            data.filterData = filterData;
                        },
                        method  : "GET",
                    },
                    columns: [
                        {data: "loan_number", title: "{{__('admin.loan_id')}}"}, // loan_number key
                        {data: "provider_loan_id", title: "{{__('admin.koinworks_loan_id')}}"}, // loan_number key
                        {data: "provider_user_id", title: "{{ __('admin.user_id')}}"},
                        {data: "order_number", title: "{{ __('admin.order_number') }}"},
                        {data: "buyer_name", title: "{{ __('admin.buyer_name') }}"},
                        {data: "email", title: "{{ __('admin.email') }}"},
                        {data: "loan_amount", title: "{{ __('admin.amount_requested') }}"},
                        {data: "loan_confirm_amount", title: "{{ __('admin.disburse_amount') }}"},
                        {data: "loan_repay_amount", title: "{{ __('admin.repayment_amount') }}"},
                        {data: "due_date", title: "{{ __('admin.due_date') }}"},
                        {data: "actions", title: "{{ __('admin.actions') }}" , class: "text-nowrap text-end"}
                    ],
                    aoColumnDefs: [
                        { "bSortable": true, "aTargets": [0] },
                        { "bSortable": false, "aTargets": [4, 5, 9, 10] }
                    ],
                    language: {
                        search: "{{__('admin.search')}}",
                        loadingRecords: "{{__('admin.please_wait_loading')}}",
                        processing: "<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i><span> {{__('admin.loading')}}..</span>"
                    },
                    order: [[0, 'desc']],
                    fnInitComplete: function() {
                        SnippetFrontDefaultInit.init();
                    },

                    fnDrawCallback: function() {
                        SnippetFrontDefaultInit.init();
                    }


                });

                return financeTable;
            },

            paymentDatatableDraw = function () {
                paymentDueDatatable().draw();
            },

            getFilterData = function (){

                var filterCount = 0;
                var data = {};

                let loan_ids = [];
                let checkedLoanIds = $('.loan_number_checkbox:checkbox:checked');
                $.each(checkedLoanIds, function(key, value){
                    loan_ids.push($(value).attr('data-id'));
                });

                if(loan_ids.length > 0){
                    data.loan_ids = loan_ids;
                    filterCount++;
                }

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
                    company_ids.push($(value).attr('data-name'));
                });
                if(company_ids.length > 0){
                    data.company = company_ids;
                    filterCount++;
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
                $("#loan_number_filtersearch").val("");
                searchFilterContent($("#loan_number_filtersearch"), '#loan_number_content div');

                $("#order_number_filtersearch").val("");
                searchFilterContent($("#order_number_filtersearch"), '#order_number_content div');

                $("#buyer_company_filtersearch").val("");
                searchFilterContent($("#buyer_company_filtersearch"), '#buyer_company_content div');

                $(".loan_number_checkbox").prop( "checked", false);
                $(".order_number_checkbox").prop( "checked", false);
                $(".buyer_company_checkbox").prop( "checked", false);
                $('#filter_count_one').text("0 {{__('admin.filter_applied')}}");
                $('#filter_count_two').text(0);

                /* clear date and reset datepicker */
                $("#filter_start_date").val("");
                $("#filter_end_date").val("");

                /* clear date and reset datepicker */
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
                    paymentDatatableDraw();
                });
            },

            applyClear = function () {
                $('#clear_btn').on('click','', function(){
                    resetFilters();
                    $(".table").DataTable().destroy();
                    paymentDatatableDraw();
                });
            },

            searchFilter = function () {

                $("#loan_number_filtersearch").keyup(function() {
                    searchFilterContent($(this), '#loan_number_content div');
                });

                $("#order_number_filtersearch").keyup(function() {
                    searchFilterContent($(this), '#order_number_content div');
                });

                $("#buyer_company_filtersearch").keyup(function() {
                    searchFilterContent($(this), '#buyer_company_content div');
                });
            },

            getPayLink = function () {
                $(document).on('click','.generate-payment-link',function(){

                    var paymentObj = $(this);

                    paymentObj.children('img.generate-payment-link-img').addClass('d-none');
                    paymentObj.children('img.generate-payment-link-img').removeAttr('title');
                    paymentObj.addClass('custom-disable');
                    paymentObj.children('div.link-loading').removeClass('d-none');

                    $.ajax({
                        url:'{{ route('admin.payments.link.build') }}',
                        method:'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {'id' : $(this).attr('data-id')},
                        success : function(data){

                            if (data.success) {
                                $('.repayment[data-id="'+paymentObj.attr('data-id')+'"]').removeClass('d-none');

                                paymentObj.addClass('d-none');
                                paymentObj.closest('a').next('.payment-link').attr('href',data.data);
                                paymentObj.closest('a').next('.payment-link').removeClass('d-none');

                            } else {
                                new PNotify({
                                    text: data.message,
                                    type: 'error',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 3000
                                });
                                paymentObj.children('span.generate-payment-link-text').removeClass('text-vertical-center');
                                paymentObj.removeClass('custom-disable');
                                paymentObj.children('div.spinner-border').addClass('d-none');
                            }

                        },

                        error : function () {
                            new PNotify({
                                text: "{{__('admin.something_went_wrong')}}",
                                type: 'error',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 3000
                            });
                            paymentObj.children('span.generate-payment-link-text').removeClass('text-vertical-center');
                            paymentObj.removeClass('custom-disable');
                            paymentObj.children('div.spinner-border').addClass('d-none');
                        }
                    });
                });
            };


            return {
                init: function () {
                    paymentDatatableDraw(),
                    getFilterData(),
                    applyFilter(),
                    applyClear(),
                    searchFilter(),
                    getPayLink()
                }
            }


        }(1);

        $(document).on('click', '.loanModalView', function (e) {
            e.preventDefault();
            viewLoanDetails($(this).attr('data-id'));
        });
    </script>
    @endpush
@stop
