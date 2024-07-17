@extends('admin/adminLayout')
@section('content')
    @push('bottom_head')
        <link href="{{ URL::asset('/assets/css/admin/filter.css') }}" rel="stylesheet">
    @endpush
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">
            <div class="d-flex align-items-center pb-3">
                <h4 class="card-title pt-3">{{__('admin.limit')}}</h4>
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

                                <!--begin: Filter - Application number list-->
                                <div class="card filter-card">
                                    <div class="card-header" role="tab" id="heading-1">
                                        <h6 class="mb-0">
                                            <a data-bs-toggle="collapse" href="#collapse_application_number"
                                               aria-expanded="false" aria-controls="collapse_application_number"
                                               class="collapsed">
                                                {{__('admin.application_no')}}
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse_application_number" class="collapse" role="tabpanel"
                                         aria-labelledby="heading-1" style="">
                                        <div class="card-body py-1 pe-1">
                                            <input class="form-control form-control-sm mb-2 filtersearch" id="application_number_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                            <div id="application_number_content">
                                                @if($limits->count() > 0)
                                                    @foreach($limits as $limit)
                                                        <div class="form-check d-flex align-items-center application_number_content">
                                                            <input type="checkbox" class="form-check-input application_number_checkbox" id="applicationNumberCheck{{$limit->loan_application_number}}" data-id="{{$limit->loan_application_number}}">
                                                            <label class="form-check-label" for="applicationNumberCheck{{$limit->loan_application_number}}">{{$limit->loan_application_number}}</label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end: Filter - Application number list-->

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
                                                            <input type="checkbox" class="form-check-input company_name_checkbox" id="companyNameCheck{{$company->company->name}}" data-id="{{$company->company->name}}">
                                                            <label class="form-check-label" for="companyNameCheck{{$company->company->name}}">{{$company->company->name}}</label>
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
                                                @if($limits->count() > 0)
                                                    @foreach($limits as $limit)
                                                        <div class="form-check d-flex align-items-center email_content">
                                                            <input type="checkbox" class="form-check-input email_checkbox" id="emailCheck{{$limit->loanApplicantBusiness->email}}" data-id="{{$limit->loanApplicantBusiness->email}}">
                                                            <label class="form-check-label" for="emailCheck{{$limit->loanApplicantBusiness->email}}">{{$limit->loanApplicantBusiness->email}}</label>
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
                                                            <input type="checkbox" class="form-check-input mobile_checkbox" id="mobileCheck{{$mobile->loanApplicantBusiness->phone_number}}" data-id="{{$mobile->loanApplicantBusiness->phone_number}}">
                                                            <label class="form-check-label" for="mobileCheck{{$mobile->loanApplicantBusiness->phone_number}}">{{$mobile->loanApplicantBusiness->phone_number}}</label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end: Filter - mobile list-->
                                  <!--begin: Filter - buyer Name-->
                                  <div class="card filter-card">
                                    <div class=" card-header" role="tab" id="heading-7">
                                        <h6 class="mb-0">
                                            <a class="collapsed" data-bs-toggle="collapse"
                                               href="#collapse_buyerName" aria-expanded="false"
                                               aria-controls="collapse_buyerName">
                                                {{__('admin.buyer_name')}}
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse_buyerName" class="collapse" role="tabpanel"
                                         aria-labelledby="heading-7">
                                        <div class="card-body py-2">
                                            <input class="form-control form-control-sm mb-2 filtersearch"
                                                   id="buyerName_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                            <div id="buyerName_content">
                                                @if($buyerNames->count() > 0)
                                                    @foreach($buyerNames as $buyerName)
                                                        <div class="form-check d-flex align-items-center buyerName_content">
                                                        <input type="checkbox" class="form-check-input buyerName_checkbox" id="buyerNameCheck{{$buyerName->applicant->first_name}} {{$buyerName->applicant->last_name}}" data-id="{{$buyerName->applicant->first_name}} {{$buyerName->applicant->last_name}}">

                                                           <label class="form-check-label" for="buyerNameCheck{{$buyerName->applicant->first_name}} {{$buyerName->applicant->last_name}}"> {{$buyerName->applicant->first_name}}  {{$buyerName->applicant->last_name}}</label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end: Filter - mobile list-->

                                <!--begin: Filter - Limit Amount list-->
                                <div class="card filter-card">
                                    <div class="card-header" role="tab" id="heading-1">
                                        <h6 class="mb-0">
                                            <a data-bs-toggle="collapse" href="#collapse_loan_limit"
                                               aria-expanded="false" aria-controls="collapse_loan_limit"
                                               class="collapsed">
                                                {{__('admin.limit_amount')}}
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse_loan_limit" class="collapse" role="tabpanel"
                                         aria-labelledby="heading-1" style="">
                                        <div class="card-body py-1 pe-1">
                                            <input class="form-control form-control-sm mb-2 filtersearch" id="loan_limit_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                            <div id="loan_limit_content">
                                                @if($amounts->count() > 0)
                                                    @foreach($amounts as $amount)
                                                        <div class="form-check d-flex align-items-center loan_limit_content">
                                                            <input type="checkbox" class="form-check-input loan_limit_checkbox" id="loanNumberCheck{{$amount->loan_limit}}" data-id="{{$amount->loan_limit}}">
                                                            <label class="form-check-label" for="loanNumberCheck{{$amount->loan_limit}}">{{$amount->loan_limit}}</label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end: Filter - Limit Amount list-->

                                <!--begin: Filter - limit status list-->
                                <div class="card filter-card">
                                    <div class=" card-header" role="tab" id="heading-10">
                                        <h6 class="mb-0">
                                            <a class="collapsed" data-bs-toggle="collapse"
                                               href="#collapse-10" aria-expanded="false"
                                               aria-controls="collapse-10">
                                                {{__('admin.status')}}
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse-10" class="collapse" role="tabpanel"
                                         aria-labelledby="heading-10">
                                        <div class="card-body py-2">
                                            <input class="form-control form-control-sm mb-2 filtersearch"
                                                   id="status_name_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                            <div id="status_name_content">
                                                @if($status->count() > 0)
                                                    @foreach($status as $status_name)
                                                        <div class="form-check d-flex align-items-center status_name_content">
                                                            <input type="checkbox" class="form-check-input status_name_checkbox" id="loanStatusCheck_{{$status_name->status_name}}" data-id="{{$status_name->status_name}}">

                                                            @if($status_name->status == null)
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->status_name}}">Pending</label>
                                                            @elseif($status_name->status == '54648454-8fbd-4492-8749-f86e14aa81f6')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->status_name}}">{{$status_name->status_name}}</label>
                                                            @elseif($status_name->status == 'c9e1cd60-7252-44ef-a360-6307b7ec5f92')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->status_name}}">{{$status_name->status_name}}</label>
                                                            @elseif($status_name->status == 'badfa20d-3562-45eb-bf4a-79a1c59fd3e9')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->status_name}}">{{$status_name->status_name}}</label>
                                                            @elseif($status_name->status == '3a8fd367-cfa8-4a1d-89e5-85effce998bc')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->status_name}}">{{$status_name->status_name}}</label>
                                                            @elseif($status_name->status == 'eb29fb79-f7bf-486a-b926-e14a87c23df8')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->status_name}}">{{$status_name->status_name}}</label>
                                                            @elseif($status_name->status == '390cc42c-0b52-40e8-9a28-ba9c1717ac03')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->status_name}}">{{$status_name->status_name}}</label>
                                                            @elseif($status_name->status == '0ff092ed-86c8-49f2-b8bb-7384abbba233')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->status_name}}">{{$status_name->status_name}}</label>
                                                            @elseif($status_name->status == '2ac0ad02-4b73-4848-b6ae-6658109dcbaf')
                                                                <label class="form-check-label text-start" for="loanStatusCheck_{{$status_name->status_name}}">{{$status_name->status_name}}</label>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end: Filter - limit status list-->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ---Filter---- -->
            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="limitTable" class="table table-hover dataTable no-footer" style="width: 100%; height: 100%;" role="grid" aria-describedby="limitTable_info"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->


    <script>
        $(document).ready(function () {
            $(document).on('click', '.limitModalView', function (e) {
                e.preventDefault();
                viewLimitDetails($(this).attr('data-id'));
            });

            SnippetLoanTab.init();
        });

        $(document).on('click', '.resendCreateApi', function () {
            let that = $(this);
            let id = that.attr('data-id');
            that.prop('disabled',true);
            swal({
                {{--title: "{{ __('admin.are_you_sure_to_send_invitation') }}",--}}
                text: "Are you sure you want to call API for this user?",
                icon: "/assets/images/info.png",
                buttons: ['{{ __('admin.no') }}', '{{ __('admin.yes') }}'],
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        var _token = $("input[name='_token']").val();
                        var senddata = {
                            id: id,
                            _token: _token
                        }
                        $.ajax({
                            url: '{{ route('limit-resent-application-ajax', '') }}',
                            type: 'POST',
                            data: senddata,
                            success: function (successData) {
                                if (successData.success) {
                                    new PNotify({
                                        text: successData.message,
                                        type: 'success',
                                        styling: 'bootstrap3',
                                        animateSpeed: 'slow',
                                        delay: 3000,
                                    });
                                    location.reload();
                                    return;
                                }
                                new PNotify({
                                    text: successData.message,
                                    type: 'error',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 2000
                                });
                                that.prop('disabled',false);
                            },
                            error: function () {
                                that.prop('disabled',false);
                                console.log('error');
                            }
                        });
                    }else{
                        that.prop('disabled',false);
                    }
                });
        });

        var SnippetLoanTab = function(){

            var limitDatatable = function () {

                    var limitTable = $('.table').DataTable({
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
                            url : "{{  route('limits-index')  }}",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data : function (data){
                                filterData = getFilterData();
                                data.filterData = filterData;
                            },
                            method  : "GET",
                        },
                        columns: [
                            {data: "loan_application_number", title: "{{__('admin.application_no')}}"},
                            {data: "company_name", title: "{{ __('admin.company_name')}}"},
                            {data: "buyer_name", title: "{{ __('admin.buyer_name') }}"},
                            {data: "email", title: "{{ __('admin.email') }}"},
                            {data: "mobile", title: "{{ __('admin.mobile') }}"},
                            {data: "apllied_limit", title: "{{ __('admin.applied_amount') }}"},
                            {data: "approved_limit", title: "{{ __('admin.approved_amount') }}"},
                            {data: "created_at", title: "{{ __('admin.application_date') }}"},
                            {data: "verify_otp", title: "{{ __('admin.otp_verify') }}"},
                            {data: "status", title: "{{ __('admin.status') }}"},
                            {data: "action", title: "{{ __('admin.actions') }}" , class: "text-nowrap text-end"}
                        ],
                        "aoColumnDefs": [
                            { "bSortable": false, "aTargets": [ 1, 2, 3, 4, 7, 8, 9 ] }
                        ],
                        language: {
                            search: "{{__('admin.search')}}",
                            loadingRecords: "{{__('admin.please_wait_loading')}}",
                            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span> {{__('admin.loading')}}..</span> '
                        },
                        order: [[6, 'desc']],

                    });

                    return limitTable;
                },

                loanCashDatatable = function () {

                    limitDatatable().draw();
                },

                getFilterData = function (){

                    var filterCount = 0;
                    var data = {};

                    let application_ids = [];
                    let checkedApplication = $('.application_number_checkbox:checkbox:checked');
                    $.each(checkedApplication, function(key, value){
                        application_ids.push($(value).attr('data-id'));
                    });
                    if(application_ids.length > 0){
                        data.ApplicationNo = application_ids;
                        filterCount++;
                    }

                    let loan_limit_ids = [];
                    let checkedLoanNumber = $('.loan_limit_checkbox:checkbox:checked');
                    $.each(checkedLoanNumber, function(key, value){
                        loan_limit_ids.push($(value).attr('data-id'));
                    });
                    if(loan_limit_ids.length > 0){
                        data.loan_limit = loan_limit_ids;
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

                    let buyername_ids = [];
                    let checkedbuyerName = $('.buyerName_checkbox:checkbox:checked');
                    $.each(checkedbuyerName, function(key, value){
                        buyername_ids.push($(value).attr('data-id'));
                    });
                    if(buyername_ids.length > 0){
                        data.buyername = buyername_ids;
                        filterCount++;
                    }

                    let status_name_ids = [];
                    let checkedLoanStatus = $('.status_name_checkbox:checkbox:checked');
                    $.each(checkedLoanStatus, function(key, value){
                        status_name_ids.push($(value).attr('data-id'));
                    });
                    if(status_name_ids.length > 0){
                        data.limit_status = status_name_ids;
                        filterCount++;
                    }

                    $('#filter_count_one').text(filterCount+" {{__('admin.filter_applied')}}");
                    $('#filter_count_two').text(filterCount);

                    return data;

                },

                resetFilters = function () {
                    /* clear search input and reset div elements */
                    $("#application_number_filtersearch").val("");
                    searchFilterContent($("#application_number_filtersearch"), '#application_number_content div');

                    $("#loan_limit_filtersearch").val("");
                    searchFilterContent($("#loan_limit_filtersearch"), '#loan_limit_content div');

                    $("#company_name_filtersearch").val("");
                    searchFilterContent($("#company_name_filtersearch"), '#company_name_content div');

                    $("#email_filtersearch").val("");
                    searchFilterContent($("#email_filtersearch"), '#email_content div');

                    $("#mobile_filtersearch").val("");
                    searchFilterContent($("#mobile_filtersearch"), '#mobile_content div');

                    $("#buyerName_filtersearch").val("");
                    searchFilterContent($("#buyerName_filtersearch"), '#buyerName_content div');

                    $("#status_name_filtersearch").val("");
                    searchFilterContent($("#status_name_filtersearch"), '#status_name_content div');

                    $(".application_number_checkbox").prop( "checked", false);
                    $(".loan_limit_checkbox").prop( "checked", false);
                    $(".company_name_checkbox").prop( "checked", false);
                    $(".email_checkbox").prop( "checked", false);
                    $(".mobile_checkbox").prop( "checked", false);
                    $(".buyerName_checkbox").prop( "checked", false);
                    $(".status_name_checkbox").prop( "checked", false);
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
                    $("#application_number_filtersearch").keyup(function() {
                        searchFilterContent($(this), '#application_number_content div');
                    });

                    $("#loan_limit_filtersearch").keyup(function() {
                        searchFilterContent($(this), '#loan_limit_content div');
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

                    $("#buyerName_filtersearch").keyup(function() {
                        searchFilterContent($(this), '#buyerName_content div');
                    });

                    $("#status_name_filtersearch").keyup(function() {
                        searchFilterContent($(this), '#status_name_content div');
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

    </script>
@stop
