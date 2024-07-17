@extends('admin/adminLayout')
@section('content')
    @push('bottom_head')
        <link href="{{ URL::asset('/assets/css/admin/filter.css') }}" rel="stylesheet">
    @endpush
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">
            <div class="d-flex align-items-center pb-3">
                <h4 class="card-title pt-3">{{__('admin.lona')}}</h4>
                <div class="d-flex align-items-center justify-content-end ms-auto">
                    @if(auth()->user()->role_id == 1  || Auth::user()->hasRole('agent'))
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
                    @endif
                    <input type="hidden" value="{!! csrf_token() !!}" name="_token">
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
                                            <a data-bs-toggle="collapse" href="#collapse_order_number"
                                               aria-expanded="false" aria-controls="collapse_order_number"
                                               class="collapsed">
                                                {{__('admin.order_id')}}
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse_order_number" class="collapse" role="tabpanel"
                                         aria-labelledby="heading-1" style="">
                                        <div class="card-body py-1 pe-1">
                                            <input class="form-control form-control-sm mb-2 filtersearch" id="order_number_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                            <div id="order_number_content">
                                                @if($loans->count() > 0)
                                                    @foreach($loans as $loan)
                                                        <div class="form-check d-flex align-items-center order_number_content">
                                                            <input type="checkbox" class="form-check-input order_number_checkbox" id="orderNumberCheck{{$loan->orders->order_number}}" data-id="{{$loan->orders->order_number}}">
                                                            <label class="form-check-label" for="orderNumberCheck{{$loan->orders->order_number}}">{{$loan->orders->order_number}}</label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end: Filter - order number list-->

                                <!--begin: Filter - Loan number list-->
                                <div class="card filter-card">
                                    <div class="card-header" role="tab" id="heading-1">
                                        <h6 class="mb-0">
                                            <a data-bs-toggle="collapse" href="#collapse_loan_number"
                                               aria-expanded="false" aria-controls="collapse_loan_number"
                                               class="collapsed">
                                                {{__('admin.loan_id')}}
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse_loan_number" class="collapse" role="tabpanel"
                                         aria-labelledby="heading-1" style="">
                                        <div class="card-body py-1 pe-1">
                                            <input class="form-control form-control-sm mb-2 filtersearch" id="loan_number_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                            <div id="loan_number_content">
                                                @if($loans->count() > 0)
                                                    @foreach($loans as $loan)
                                                        <div class="form-check d-flex align-items-center loan_number_content">
                                                            <input type="checkbox" class="form-check-input loan_number_checkbox" id="loanNumberCheck{{$loan->loan_number}}" data-id="{{$loan->loan_number}}">
                                                            <label class="form-check-label" for="loanNumberCheck{{$loan->loan_number}}">{{$loan->loan_number}}</label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end: Filter - loan number list-->

                                <!--begin: Filter - Company name list-->
                                <div class="card filter-card">
                                    <div class="card-header" role="tab" id="heading-1">
                                        <h6 class="mb-0">
                                            <a data-bs-toggle="collapse" href="#collapse_company_name"
                                               aria-expanded="false" aria-controls="collapse_company_name"
                                               class="collapsed">
                                                {{__('admin.company_name')}}
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse_company_name" class="collapse" role="tabpanel"
                                         aria-labelledby="heading-1" style="">
                                        <div class="card-body py-1 pe-1">
                                            <input class="form-control form-control-sm mb-2 filtersearch" id="company_name_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                            <div id="company_name_content">
                                                @if($companies->count() > 0)
                                                    @foreach($companies as $company)
                                                        <div class="form-check d-flex align-items-center company_name_content">
                                                            <input type="checkbox" class="form-check-input company_name_checkbox" id="companyNameCheck{{$company->companies->name}}" data-id="{{$company->companies->name}}">
                                                            <label class="form-check-label" for="companyNameCheck{{$company->companies->name}}">{{$company->companies->name}}</label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end: Filter - Company name list-->

                                <!--begin: Filter - email list-->
                                <div class="card filter-card">
                                    <div class="card-header" role="tab" id="heading-1">
                                        <h6 class="mb-0">
                                            <a data-bs-toggle="collapse" href="#collapse_email"
                                               aria-expanded="false" aria-controls="collapse_email"
                                               class="collapsed">
                                                {{__('admin.email')}}
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse_email" class="collapse" role="tabpanel"
                                         aria-labelledby="heading-1" style="">
                                        <div class="card-body py-1 pe-1">
                                            <input class="form-control form-control-sm mb-2 filtersearch" id="email_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                            <div id="email_content">
                                                @if($loans->count() > 0)
                                                    @foreach($loans as $loan)
                                                        <div class="form-check d-flex align-items-center lemail_content">
                                                            <input type="checkbox" class="form-check-input email_checkbox" id="emailCheck{{$loan->loanApplicants->loanApplicantBusiness->email}}" data-id="{{$loan->loanApplicants->loanApplicantBusiness->email}}">
                                                            <label class="form-check-label" for="emailCheck{{$loan->loanApplicants->loanApplicantBusiness->email}}">{{$loan->loanApplicants->loanApplicantBusiness->email}}</label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end: Filter - email list-->

                                <!--begin: Filter - mobile list-->
                                <div class="card filter-card">
                                    <div class=" card-header" role="tab" id="heading-7">
                                        <h6 class="mb-0">
                                            <a class="collapsed" data-bs-toggle="collapse"
                                               href="#collapse_mobile" aria-expanded="false"
                                               aria-controls="collapse_mobile">
                                                {{__('admin.mobile')}}
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse_mobile" class="collapse" role="tabpanel"
                                         aria-labelledby="heading-7">
                                        <div class="card-body py-2">
                                            <input class="form-control form-control-sm mb-2 filtersearch"
                                                   id="mobile_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                            <div id="mobile_content">
                                                @if($mobiles->count() > 0)
                                                    @foreach($mobiles as $mobile)
                                                        <div class="form-check d-flex align-items-center mobile_content">
                                                            <input type="checkbox" class="form-check-input mobile_checkbox" id="mobileCheck{{$mobile->loanApplicants->loanApplicantBusiness->phone_number}}" data-id="{{$mobile->loanApplicants->loanApplicantBusiness->phone_number}}">
                                                            <label class="form-check-label" for="mobileCheck{{$mobile->loanApplicants->loanApplicantBusiness->phone_number}}">{{$mobile->loanApplicants->loanApplicantBusiness->phone_number}}</label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end: Filter - mobile list-->

                                <!--begin: Filter - loan status list-->
                                <div class="card filter-card">
                                    <div class=" card-header" role="tab" id="heading-10">
                                        <h6 class="mb-0">
                                            <a class="collapsed" data-bs-toggle="collapse"
                                               href="#collapse-10" aria-expanded="false"
                                               aria-controls="collapse-10">
                                                {{__('admin.loan_status')}}
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse-10" class="collapse" role="tabpanel"
                                         aria-labelledby="heading-10">
                                        <div class="card-body py-2">
                                            <input class="form-control form-control-sm mb-2 filtersearch" id="loan_status_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                            <div id="loan_status_content">
                                                @if($status->count() > 0)
                                                    @foreach($status as $status_name)
                                                        <div class="form-check d-flex align-items-center loan_status_content">
                                                            <input type="checkbox" class="form-check-input loan_status_checkbox" id="loanStatusCheck_{{$status_name->loanStatus->status_display_name}}" data-id="{{$status_name->loanStatus->status_display_name}}">

                                                            @if($status_name->status_id == '11')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->loanStatus->status_display_name}}">{{$status_name->loanStatus->status_display_name}}</label>
                                                            @elseif($status_name->status_id == '12')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->loanStatus->status_display_name}}">{{$status_name->loanStatus->status_display_name}}</label>
                                                            @elseif($status_name->status_id == '13')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->loanStatus->status_display_name}}">{{$status_name->loanStatus->status_display_name}}</label>
                                                            @elseif($status_name->status_id == '14')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->loanStatus->status_display_name}}">{{$status_name->loanStatus->status_display_name}}</label>
                                                            @elseif($status_name->status_id == '15')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->loanStatus->status_display_name}}">{{$status_name->loanStatus->status_display_name}}</label>
                                                            @elseif($status_name->status_id == '16')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->loanStatus->status_display_name}}">{{$status_name->loanStatus->status_display_name}}</label>
                                                            @elseif($status_name->status_id == '17')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->loanStatus->status_display_name}}">{{$status_name->loanStatus->status_display_name}}</label>
                                                            @elseif($status_name->status_id == '18')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->loanStatus->status_display_name}}">{{$status_name->loanStatus->status_display_name}}</label>
                                                            @elseif($status_name->status_id == '19')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->loanStatus->status_display_name}}">{{$status_name->loanStatus->status_display_name}}</label>
                                                            @elseif($status_name->status_id == '20')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->loanStatus->status_display_name}}">{{$status_name->loanStatus->status_display_name}}</label>
                                                            @elseif($status_name->status_id == '21')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->loanStatus->status_display_name}}">{{$status_name->loanStatus->status_display_name}}</label>
                                                            @elseif($status_name->status_id == '22')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->loanStatus->status_display_name}}">{{$status_name->loanStatus->status_display_name}}</label>
                                                            @elseif($status_name->status_id == '23')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->loanStatus->status_display_name}}">{{$status_name->loanStatus->status_display_name}}</label>
                                                            @elseif($status_name->status_id == '24')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->loanStatus->status_display_name}}">{{$status_name->loanStatus->status_display_name}}</label>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                <!--end: Filter - payment status list-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ---Filter---- -->

            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="loanTable" class="table table-hover dataTable no-footer" style="width: 100%; height: 100%;" role="grid" aria-describedby="loanTable_info"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <!-- <div class="modal fade version2" id="loanModal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div id="modelLodear"></div>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            </div>
        </div>
    </div> -->

    @push('bottom_scripts')
        <script type="text/javascript">
            $(document).on('click', '.loanModalView', function (e) {
                viewLoanDetails($(this).attr('data-id'));
            });
        /******************begin: Jquery Module Initiate*********************/
        jQuery(document).ready(function(){
            SnippetLoanTab.init();
        });
        /******************end: Jquery Module Initiate*********************/

        var SnippetLoanTab = function(){

                var loanDatatable = function () {

                    var loanTable = $('.table').DataTable({
                        scrollX: true,
                        serverSide: !0,
                        paginate: !0,
                        processing: !0,
                        lengthMenu: [
                            [10, 25, 50],
                            [10, 25, 50],
                        ],
                        footer:!1,
                        ajax: {
                            url : "{{  route('loans-index')  }}",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data : function (data){
                                filterData = getFilterData();
                                data.filterData = filterData;
                            },
                            method  : "GET",
                        },
                        columns: [
                            {data: "order_number", title: "{{__('admin.order_id')}}"},
                            {data: "loan_number", title: "{{__('admin.loan_id')}}"},
                            {data: "provider_loan_id", title: "{{__('admin.koinworks_loan_id')}}"},
                            {data: "company_name", title: "{{ __('admin.company_name') }}"},
                            {data: "email", title: "{{ __('admin.email') }}"},
                            {data: "mobile", title: "{{ __('admin.mobile') }}"},
                            {data: "senctioned_amount", title: "{{ __('admin.limit_amount') }}"},
                            {data: "loan_confirm_amount", title: "{{__('admin.loan_confirm_amount')}}"},
                            {data: "available_amount", title: "{{__('admin.available_amount')}}"},
                            {data: "status", title: "{{ __('admin.status') }}"},
                            {data: "action", title: "{{ __('admin.actions') }}" , class: "text-nowrap text-end"}
                        ],
                        aoColumnDefs: [
                            { "bSortable": false, "aTargets": [ 3, 4, 5, 8, 9, 10 ] }
                        ],
                        language: {
                            search: "{{__('admin.search')}}",
                            loadingRecords: "{{__('admin.please_wait_loading')}}",
                            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span> {{__('admin.loading')}}..</span> '
                        },
                        order: [[0, 'desc']],

                    });

                    return loanTable;
                },

                loanCashDatatable = function () {

                    loanDatatable().draw();
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

                    let loan_number_ids = [];
                    let checkedLoanNumber = $('.loan_number_checkbox:checkbox:checked');
                    $.each(checkedLoanNumber, function(key, value){
                        loan_number_ids.push($(value).attr('data-id'));
                    });
                    if(loan_number_ids.length > 0){
                        data.loan_number = loan_number_ids;
                        filterCount++;
                    }

                    let company_name_ids = [];
                    let checkedCompanyName = $('.company_name_checkbox:checkbox:checked');
                    $.each(checkedCompanyName, function(key, value){
                        company_name_ids.push($(value).attr('data-id'));
                    });
                    if(company_name_ids.length > 0){
                        data.company_name = company_name_ids;
                        filterCount++;
                    }

                    let email_ids = [];
                    let checkedEmail = $('.email_checkbox:checkbox:checked');
                    $.each(checkedEmail, function(key, value){
                        email_ids.push($(value).attr('data-id'));
                    });
                    if(email_ids.length > 0){
                        data.email = email_ids;
                        filterCount++;
                    }

                    let mobile_ids = [];
                    let checkedMobile = $('.mobile_checkbox:checkbox:checked');
                    $.each(checkedMobile, function(key, value){
                        mobile_ids.push($(value).attr('data-id'));
                    });
                    if(mobile_ids.length > 0){
                        data.mobile = mobile_ids;
                        filterCount++;
                    }

                    let loan_status_ids = [];
                    let checkedLoanStatus = $('.loan_status_checkbox:checkbox:checked');
                    $.each(checkedLoanStatus, function(key, value){
                        loan_status_ids.push($(value).attr('data-id'));
                    });
                    if(loan_status_ids.length > 0){
                        data.loan_status = loan_status_ids;
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

                    $("#loan_number_filtersearch").val("");
                    searchFilterContent($("#loan_number_filtersearch"), '#loan_number_content div');

                    $("#company_name_filtersearch").val("");
                    searchFilterContent($("#company_name_filtersearch"), '#company_name_content div');

                    $("#email_filtersearch").val("");
                    searchFilterContent($("#email_filtersearch"), '#email_content div');

                    $("#mobile_filtersearch").val("");
                    searchFilterContent($("#mobile_filtersearch"), '#mobile_content div');

                    $("#loan_status_filtersearch").val("");
                    searchFilterContent($("#loan_status_filtersearch"), '#loan_status_content div');

                    $(".order_number_checkbox").prop( "checked", false);
                    $(".loan_number_checkbox").prop( "checked", false);
                    $(".company_name_checkbox").prop( "checked", false);
                    $(".email_checkbox").prop( "checked", false);
                    $(".mobile_checkbox").prop( "checked", false);
                    $(".loan_status_checkbox").prop( "checked", false);
                    $('#filter_count_one').text("0 {{__('admin.filter_applied')}}");
                    $('#filter_count_two').text(0);

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
                        loanCashDatatable();
                    });
                },

                applyClear = function () {
                    $('#clear_btn').on('click','', function(){
                        resetFilters();
                        $(".table").DataTable().destroy();
                        loanCashDatatable();
                    });
                },

                searchFilter = function () {
                    $("#order_number_filtersearch").keyup(function() {
                        searchFilterContent($(this), '#order_number_content div');
                    });

                    $("#loan_number_filtersearch").keyup(function() {
                        searchFilterContent($(this), '#loan_number_content div');
                    });

                    $("#company_name_filtersearch").keyup(function() {
                        searchFilterContent($(this), '#company_name_content div');
                    });

                    $("#email_filtersearch").keyup(function() {
                        searchFilterContent($(this), '#email_content div');
                    });

                    $("#mobile_filtersearch").keyup(function() {
                        searchFilterContent($(this), '#mobile_content div');
                    });

                    $("#loan_status_filtersearch").keyup(function() {
                        searchFilterContent($(this), '#loan_status_content div');
                    });

                };

                return {
                    init: function () {
                        loanCashDatatable(),
                        getFilterData(),
                        applyFilter(),
                        applyClear(),
                        searchFilter()
                    }
                }
        }(1);

        $(document).on('click', '.loanModalView', function (e) {
            e.preventDefault();
            viewLoanDetails($(this).attr('data-id'));
        });

        $(document).on('click', '.viewOrderModal', function (e) {
            e.preventDefault();
            viewOrderDetails($(this).attr('data-id'));
        });
        </script>
    @endpush
@stop
