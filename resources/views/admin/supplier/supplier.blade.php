@extends('admin/adminLayout')
@push('bottom_head')
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <link href="{{ URL::asset('/assets/css/admin/filter.css') }}" rel="stylesheet">
    <style>
        .color-gray {
            color: gray;
        }
        table td {
            word-wrap: break-word;
            max-width: 400px;
        }
        div.dataTables_wrapper div.dataTables_filter input{
            padding: 8px 5px;
        }
        #supplierTable td {
            white-space:inherit;
        }
        .loader {
            position: fixed;
            text-align: center;
            width: 100%;
            left: 49%;
            top: 49%;
        }
        .btnPadding{
            padding: 0.25rem 0.5rem;
        }
        div.dataTables_wrapper div.dataTables_processing{
            padding: 0.7em 0;
        }
    </style>

@endpush
@section('content')
    <div class="modal fade" id="SupplierCategoryModal" tabindex="-1" role="dialog"
         aria-labelledby="SupplierCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="SupplierCategoryModalLabel">{{ __('admin.category') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="SupplierCategoryModalBlock">

                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-success">Send Quote</button> --}}
                    <button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">
            <div class="d-flex align-items-center pb-3">
                <h4 class="card-title pt-3">{{ __('admin.suppliers') }}</h4>
                <div class="dropdown ms-auto me-1">
                    <div class="mb-3 clearfix d-flex align-items-center">
						<span class="text-muted me-2" id="filter_count_one"
                              style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">
							0 {{__('admin.filter_applied')}}
						</span>
                        <div class="nav-item nav-settings">
                            <a class="btn btn-dark btn-sm" style="padding: 0.25rem 0.5rem;" href="javascript:void(0)">Filter</a>
                        </div>
                        @if(\Auth::user()->hasRole('admin'))
                            <div id="exportSupplierBtn"></div>
                        @endif
                        @can('create supplier list')
                            <a href="{{ route('admin.supplier.create') }}" type="button" class="btn btn-primary btn-sm ms-1 btnPadding">{{ __('admin.add') }}</a>
                        @endcan
                        <input type="hidden" value="{!! csrf_token() !!}" name="_token">
                    </div>
                </div>
            </div>
            <!-- ---Filter---- -->
            <div id="right-sidebar" class="settings-panel filter shadow-lg" style="z-index: 5;">
                <div class="d-flex p-2 align-items-center position-sticky shadow">
                    <h4 class="mt-2 fw-bold">{{__('admin.filters')}}</h4>
                    <span class="badge badge-outline-primary ms-2" id="filter_count_two"
                          style="padding: 0.125em 0.25em;">0</span>
                    <span class="ms-auto">
							<a href="javascript:void(0)" id="clear_btn" class="btn btn-sm btn-dark  p-1 pe-1"
                               style="font-size: 0.8em; margin-right: 0.2rem;" type="button" role="button">
								{{__('admin.clear')}}
							</a>
							<a id="apply_btn" href="javascript:void(0)" class="btn btn-primary btn-sm  p-1"
                               style="font-size: 0.7em; margin-right: 1.75rem;" role="button">
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
                                    <input type="text" class="form-control todo-list-input"
                                           placeholder="{{__('admin.search')}}">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class="list-wrapper px-2 py-2" style="overflow-y: auto;">
                            <div class="accordion accordion-solid-header" id="accordion-1" role="tablist">
                                <div class="card filter-card">
                                    <div class=" card-header" role="tab" id="heading-1">
                                        <h6 class="mb-0">
                                            <a class="collapsed" data-bs-toggle="collapse"
                                               href="#collapse-1" aria-expanded="false"
                                               aria-controls="collapse-1">
                                                {{__('admin.category')}}
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse-1" class="collapse" role="tabpanel"
                                         aria-labelledby="heading-1">
                                        <div class="card-body py-2">
                                            <input class="form-control form-control-sm mb-2 filtersearch"
                                                   id="category_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                            <div id="category_content">
                                                @if(sizeof($categories) > 0)
                                                    @foreach($categories as $key => $value)
                                                        <div class="form-check d-flex align-items-center">
                                                            <input type="checkbox" class="form-check-input category_checkbox" id="category{{$value->id}}" data-id="{{$value->id}}" data-name="{{$value->name}}" onclick="SupplierFiltersTab.getSubcategoryByCategory()">
                                                            <label class="form-check-label" for="category{{$value->id}}"> {{$value->name}} </label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card filter-card">
                                    <div class=" card-header" role="tab" id="heading-2">
                                        <h6 class="mb-0">
                                            <a class="collapsed" data-bs-toggle="collapse"
                                               href="#collapse-2" aria-expanded="false"
                                               aria-controls="collapse-2">
                                                {{__('admin.sub_category')}}
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse-2" class="collapse" role="tabpanel"
                                         aria-labelledby="heading-2">
                                        <div class="card-body py-2 content">
                                            <input class="form-control form-control-sm mb-2 filtersearch"
                                                   id="sub_category_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                            <div id="sub_category_content">
                                                @if(sizeof($sub_category) > 0)
                                                    @foreach($sub_category as $key => $value)
                                                        <div class="form-check d-flex align-items-center">
                                                            <input type="checkbox" class="form-check-input sub_category_checkbox" id="sub_category{{$value->id}}" data-id="{{$value->id}}" data-name="{{$value->name}}" onclick="SupplierFiltersTab.getProductBySubcategory()">
                                                            <label class="form-check-label" for="sub_category{{$value->id}}"> {{$value->subcategory_name}} </label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card filter-card">
                                    <div class=" card-header" role="tab" id="heading-3">
                                        <h6 class="mb-0">
                                            <a class="collapsed" data-bs-toggle="collapse"
                                               href="#collapse-3" aria-expanded="false"
                                               aria-controls="collapse-3">
                                                {{__('admin.product_name')}}
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse-3" class="collapse" role="tabpanel"
                                         aria-labelledby="heading-3">
                                        <div class="card-body py-2">
                                            <input class="form-control form-control-sm mb-2 filtersearch"
                                                   id="product_filtersearch" type="text"
                                                   placeholder="{{__('admin.search')}}..">
                                            <div id="product_content">
                                                @if(sizeof($products) > 0)
                                                    @foreach($products as $key => $value)
                                                        <div class="form-check d-flex align-items-center">
                                                            <input type="checkbox"
                                                                   class="form-check-input product_checkbox"
                                                                   id="product{{$value->id}}" data-id="{{$value->id}}">
                                                            <label class="form-check-label"
                                                                   for="product{{$value->id}}"> {{$value->product_name}}  -
                                                                {{$value->subCatsName}}</label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card filter-card">
                                    <div class=" card-header" role="tab" id="heading-4">
                                        <h6 class="mb-0">
                                            <a class="collapsed" data-bs-toggle="collapse"
                                               href="#collapse-4" aria-expanded="false"
                                               aria-controls="collapse-4">
                                                {{__('admin.register')}} {{__('admin.date')}}
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse-4" class="collapse" role="tabpanel"
                                         aria-labelledby="heading-4">
                                        <div class="card-body py-2">
                                            <div class="input-group input-daterange">
                                                <input type="text" class="form-control ps-2" value="" readonly id="start_date">
                                                <div class="input-group-addon ps-2 pe-2">to</div>
                                                <input type="text" class="form-control pe-2" value="" readonly id="end_date">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card filter-card">
                                    <div class=" card-header" role="tab" id="heading-5">
                                        <h6 class="mb-0">
                                            <a class="collapsed" data-bs-toggle="collapse"
                                               href="#collapse-5" aria-expanded="false"
                                               aria-controls="collapse-5">
                                                {{__('admin.status')}}
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse-5" class="collapse" role="tabpanel"
                                         aria-labelledby="heading-5">
                                        <div class="card-body py-2">
                                            <div class="form-check d-flex align-items-center">
                                                <input type="checkbox" class="form-check-input status_checkbox" id="active_status"  data-value="1" value="1">
                                                <label class="form-check-label" for="active_status">{{__('admin.active')}}</label>
                                            </div>
                                            <div class="form-check d-flex align-items-center">
                                                <input type="checkbox" class="form-check-input status_checkbox" id="inactive_status" data-value="0"  value="0">
                                                <label class="form-check-label" for="inactive_status">{{__('admin.inactive')}}</label>
                                            </div>
                                        </div>
                                    </div>
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

                        <table id="supplierTable" class="table table-hover">
                            <thead>
                            <tr>
                                <th class="hidden">supplierId</th>
                                <th style="min-width: 110px">{{ __('admin.name') }}</th>
                                <th style="min-width: 110px">{{ __('admin.company_email') }}</th>
                                <th style="min-width: 90px">{{ __('admin.mobile') }}</th>
                                <th style="min-width: 90px">{{ __('admin.contact_person_email') }}</th>
                                <th style="min-width: 90px">{{ __('admin.category_subcategory') }}</th>
                                <th style="min-width: 60px">{{ __('admin.status') }}</th>
                                <th style="min-width: 70px">{{ __('admin.added_by') }}</th>
                                <th style="min-width: 70px">{{ __('admin.updated_by') }}</th>
                                <th style="min-width: 90px">{{ __('admin.added_date') }}</th>
                                <th class="hidden">Contact Person Name</th>
                                <th class="hidden">Contact Person Email</th>
                                <th class="hidden">Contact Person Phone Number</th>
                                <th class="hidden">Bank Name</th>
                                <th class="hidden">Bank Code</th>
                                <th class="hidden">Bank Account Name</th>
                                <th class="hidden">Bank Account Number</th>
                                @canany(['edit supplier list' , 'delete supplier list' , 'create supplier list' , 'publish supplier list'])
                                    <th class="text-center" style="min-width: 120px">{{ __('admin.actions') }}</th>
                                @endcan
                            </tr>
                            </thead>
                            <tbody id="supplierData"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- --- supplier --- -->
    <!-- Modal -->
    <div class="modal fade version2" id="supplierModal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div id="modelLodear"></div>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!-- --- supplier --- -->
    <div class="modal fade" id="xenditpopup" tabindex="-1" aria-labelledby="xenditpopup" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 640px;" role="document">
            <div class="modal-content border-0" style="background-color: #ebedf1;">
                <div class="modal-header p-3">
                    <h3 class="modal-title" style="color: white;" id="">{{ __('admin.xendit_account_details') }}</h3>
                    <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close">
                        <img src="{{ URL::asset('/front-assets/images/icons/times.png') }}" alt="Close">
                    </button>
                </div>
                <div class="modal-body p-3">
                    <div class="card">
                        <div class="card-header align-items-center d-flex" style="background-color: #d7d9df;">
                            <img src="{{ URL::asset('/front-assets/images/icons/xendit.png') }}" alt="" height="20px"
                                 width="25px" srcset="">
                            <h5 class="mb-0 ps-2">{{ __('admin.xendit_account_info') }}</h5>
                        </div>
                        <div class="card-body p-3 pb-1">
                            <form id="xenAccountForm" data-parsley-validate>
                                <div class="row">
                                    <div class="pb-3">
                                        <label class="form-label">{{ __('admin.company_name') }}</label>
                                        <div class="d-flex">
                                            <input type="hidden" id="supplier-id" disabled>
                                            <input type="text" class="form-control" id="xen-ac-name" disabled>
                                        </div>
                                    </div>
                                    <div class="pb-3">
                                        <label class="form-label">{{ __('admin.email') }}</label>
                                        <div class="">
                                            <input type="text" class="form-control" id="xen-email" required
                                                   data-parsley-uniqueemail>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pb-2 px-2" style="background-color: #f5f5f6;">
                    <button type="button" class="btn btn-primary" id="create-xen-account">{{ __('admin.create') }}</button>
                    <img class="hidden" id="loader" height="16px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function(){
            SupplierListTab.init();
            SupplierFiltersTab.init();
            SupplierListTab.getAllCheckedCategoriesArr();
            SupplierListTab.getAllCheckedSubCategoriesArr();
            SupplierListTab.getAllCheckedProductsArr();
            SupplierListTab.getAllCheckedStatusArr();
            SupplierFiltersTab.resetFilters();
        });
        var SupplierListTab = function(){
            supplierDataTable = function () {
                var profile_slug = '{{ getSettingValueByKey('slug_prefix') }}';
                var tableId = "supplierTable";
                var table = $('#'+tableId).DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    order:[0,"DESC"],
                    scrollX:true,
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    ajax: {
                        url: "{{ route('admin.supplier.list.json') }}",
                        data: function (d) {
                            d.category_ids = SupplierListTab.getAllCheckedCategoriesArr();
                            d.subcategory_ids = SupplierListTab.getAllCheckedSubCategoriesArr();
                            d.product_ids = SupplierListTab.getAllCheckedProductsArr();
                            d.status_ids = SupplierListTab.getAllCheckedStatusArr();
                            d.start_date = $('#start_date').val();
                            d.end_date = $('#end_date').val();
                            if (d.order.length > 0){
                                d.column_order = d.columns[d.order[0].column].name;
                                d.order_type = d.order[0].dir;
                            }
                        }
                    },
                    language:{
                        processing: "<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i> Loading...",
                        infoFiltered: ""
                    },
                    drawCallback: function() {
                        $('#'+tableId+' [data-bs-toggle="tooltip"]').tooltip();
                        var counterFilter = 0;
                        if(SupplierListTab.getAllCheckedCategoriesArr().length > 0){
                            counterFilter++;
                        }
                        if(SupplierListTab.getAllCheckedSubCategoriesArr().length > 0){
                            counterFilter++;
                        }
                        if(SupplierListTab.getAllCheckedProductsArr().length > 0){
                            counterFilter++;
                        }
                        if(SupplierListTab.getAllCheckedStatusArr().length > 0){
                            counterFilter++;
                        }
                        if($("#start_date").val() != "" && $("#start_date").val() != ""){
                            counterFilter++;
                        }
                        $("#filter_count_one").html(counterFilter + " {{__('admin.filter_applied')}}");
                        $("#filter_count_two").html(counterFilter)

                    },
                    columns: [
                        {data: 'id', name: 'id',visible:false},
                        {
                            data: 'name',
                            name: 'name',
                            width: '80px',
                            render: function (data,type,row) {
                                if(row.profile_username !== '' && row.profile_username){
                                    var previewUrl = "{{ route('supplier.professional.profile', [':slug']) }}";
                                    previewUrl = previewUrl.replace(":slug", profile_slug);
                                    return '<a href="'+previewUrl+(row.profile_username)+'" target="_blank" class="hover_underline" style="text-decoration: none; color: #000">'+data+'</a>';
                                }else{
                                    return data;
                                }
                            },
                        },
                        {
                            data: 'email',
                            name: 'email',
                            render: function (data,type,row) {
                                if(data !== '' && data){
                                    return data;
                                }else{
                                    return '-';
                                }
                            },
                        },
                        {
                            data: 'mobile',
                            name: 'mobile',
                            render: function (data,type,row) {
                                if(data !== '' && data){
                                    return row.c_phone_code+" "+data;
                                }else{
                                    return '-';
                                }
                            },
                            width: '80px'
                        },
                        {
                            data: 'contact_person_email',
                            name: 'contact_person_email',
                            render: function (data,type,row) {
                                if(data !== '' && data){
                                    return data;
                                }else{
                                    return '-';
                                }
                            },
                        },
                        {
                            data: 'categories',
                            name: 'categories',
                            orderable: false,
                            type:'html',
                            render: function (data,type,row) {
                                if(row.categories == '-'){
                                    return '<div class="categorysection_popup d-inline-block">'+row.categories+'</div>';
                                }else{
                                    return '<div class="categorysection_popup d-inline-block"> <a href="javascript:void(0);">'+row.categories+'</a><div class="categoryhover" data-supplier-id="'+row.id+'">'+(row.hoverCategory.replace(/&lt;/g, '<').replace(/&gt;/g, '>'))+'</div></div>';
                                }
                            },
                        },
                        {
                            data: 'status',
                            name: 'status',
                            render: function (data,type,row) {
                                if(type == "export"){
                                    return (data == "0") ? 'No' : 'Yes';
                                }else{
                                    if(data == "0"){
                                        return '<a href="javascript:void(0)" data-id="'+row.id+'" data-status="'+row.status+'" class="color-gray changeStatus" id="supplier'+row.id+'" data-toggle="tooltip" ata-placement="top" title={{__("admin.inactive")}}><i class="fa fa-user-circle"></i></a>';
                                    }else{
                                        return '<a href="javascript:void(0)" data-id="'+row.id+'" data-status="'+row.status+'" class="changeStatus" id="supplier'+row.id+'" data-toggle="tooltip" ata-placement="top" title={{__("admin.active")}}><i class="fa fa-user-circle"></i></a>';
                                    }
                                }
                            },
                        },
                        { data: 'addedBy',name: 'addedBy', orderable: false, searchable: false},
                        { data: 'updatedBy',name: 'updatedBy', orderable: false, searchable: false},
                        { data: 'added_at',name: 'added_at', orderable: false},
                        {
                            data: 'cpn',
                            name: 'cpn',
                            visible: false,
                            render: function (data,type,row) {
                                var person_name = '';
                                if(row.contact_person_name){
                                    person_name += row.contact_person_name;
                                }
                                if(row.contact_person_last_name){
                                    person_name += " "+row.contact_person_last_name;
                                }
                                return person_name;
                            },
                        },
                        { data: 'contact_person_email',name: 'contact_person_email',visible: false, searchable: false },
                        {
                            data: 'person_phone',
                            name: 'person_phone',
                            visible: false,
                            searchable: false,
                            render: function (data,type,row) {
                                if(row.cp_phone_code && row.contact_person_phone){
                                    return row.cp_phone_code+" "+row.contact_person_phone;
                                }else{
                                    return "";
                                }
                            },
                        },
                        { data: 'bank_name',name: 'bank_name',visible: false, searchable: false },
                        { data: 'bank_code',name: 'bank_code',visible: false, searchable: false },
                        { data: 'bank_account_name',name: 'bank_account_name',visible: false, searchable: false },
                        { data: 'bank_account_number',name: 'bank_account_number',visible: false, searchable: false },
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end text-nowrap iconsection', width: '140px'},
                    ],
                });
                var buttons = new $.fn.dataTable.Buttons(table, {
                    buttons: [
                        {
                            text: "Export",
                            extend: 'excelHtml5',
                            className: 'btn btn-warning btn-sm ms-1 btnPadding',
                            title:'',
                            filename:'Suppliers',
                            action: newexportaction,
                            exportOptions: {
                                format: {
                                    header: function ( data, columnIdx ) {
                                        if(columnIdx === 1){
                                            return "Company Name";
                                        }else if(columnIdx === 6){
                                            return "Is Active";
                                        }else if(columnIdx === 3){
                                            return "Company Mobile";
                                        }else{
                                            return data;
                                        }
                                    },
                                },
                                orthogonal: "export",
                                columns: [ 1, 2, 3, 10, 11, 12, 13, 14, 15, 16, 6 ]
                            },
                            customize: function(xlsx) {
                                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                $('row:nth-child(1) c', sheet).attr('s', '30');
                            },
                            init: function(api, node, config) {
                                $(node).removeClass('btn-secondary')
                            }
                        },
                    ]
                }).container().appendTo($('#exportSupplierBtn'));
            },
            viewSupplierDetails = function () {
                $(document).on('click', '.supplierModalView', function (e) {
                    $("#supplierModal").find(".modal-content").html('');
                    e.preventDefault();
                    var id = $(this).attr('data-id');
                    if (id) {
                        $.ajax({
                            url: "{{ route('supplier-detail-view', '') }}" + "/" + id,
                            type: 'GET',
                            success: function (successData) {
                                $("#supplierModal").find(".modal-content").html(successData.rfqview);
                                $('#supplierModal').modal('show');
                            },
                            error: function () {
                                console.log('error');
                            }
                        });
                    }
                });
            },
            createXenAccountFromAction = function () {
                $(document).on("click", ".create-xenaccount", function () {
                    $("#xenditpopup #supplier-id").val($(this).data('id'));
                    $("#xenditpopup #xen-ac-name").val($(this).data('name'));
                    $("#xenditpopup #xen-email").val($(this).data('email'));
                });
            },
            createXenAccountButtonSave = function () {
                $(document).on("click", "#create-xen-account", function () {
                    if ($('#xenAccountForm').parsley().validate()) {
                        $(this).toggleClass('hidden');
                        $('#loader').toggleClass('hidden');
                        let that = $(this);
                        let supplierId = $('#supplier-id').val();
                        $.ajax({
                            url: "{{ route('create-xen-account','') }}",
                            data: {id: supplierId, email: $('#xen-email').val()},
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            type: "POST",
                            dataType: 'json',
                            success: function (successData) {
                                if (successData.success) {
                                    SnippetApp.toast.success("{{ __('admin.success') }}","{{ __('admin.xen_create_success_message') }}");
                                    $('#create-xenaccount' + supplierId).remove();
                                    $('#xenditpopup').modal('hide');
                                } else {
                                    SnippetApp.toast.warning("{{ __('admin.warning') }}","{{ __('admin.something_error_message') }}");
                                }
                                that.toggleClass('hidden');
                                $('#loader').toggleClass('hidden');
                            },
                            error: function (error) {
                                SnippetApp.toast.warning("{{ __('admin.warning') }}",error.responseJSON.message);
                                that.toggleClass('hidden');
                                $('#loader').toggleClass('hidden');
                                console.log('error');
                            }
                        });
                    }
                });
            },
            deleteSupplier = function () {
                $(document).on('click', '.deleteSupplier', function () {
                    var id = $(this).attr('id').split("_")[1];
                    swal({
                        title: "{{ __('admin.delete_sure_alert') }}",
                        text: "{{ __('admin.supplier_delete_text') }}",
                        icon: "/assets/images/bin.png",
                        buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.delete') }}'],
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (willDelete) {
                            var _token = $("input[name='_token']").val();
                            var senddata = {
                                id: id,
                                _token: _token
                            }
                            $.ajax({
                                url: "{{ route('admin.supplier.delete') }}",
                                type: 'POST',
                                data: senddata,
                                success: function (successData) {
                                    if (successData.success == true) {
                                        SnippetApp.toast.success("{{ __('admin.success') }}","{{ __('admin.supplier_delete_success_message') }}");
                                        $('#supplierTable').DataTable().ajax.reload(null,false);
                                    } else {
                                        SnippetApp.swal.info("{{ __('admin.access_denied') }}","{{ __('admin.supplier_not_delete_hard') }}","{{ __('admin.ok') }}");
                                    }
                                },
                                error: function () {
                                    console.log('error');
                                }
                            });
                        }
                    });
                });
            },
            changeSupplierStatus = function () {
                $(document).on('click', '.changeStatus', function () {
                    var id = $(this).attr('data-id');
                    var status = $(this).attr('data-status');
                    var updateStatus;
                    var text = '';
                    if (status == 0) {
                        text = "{{ __('admin.active_supplier_message') }}";
                        updateStatus = 1;
                    } else {
                        text = "{{ __('admin.deactive_supplier_message') }}";
                        updateStatus = 0;
                    }
                    swal({
                        title: "{{ __('admin.delete_sure_alert') }}",
                        text: text,
                        icon: "/assets/images/info.png",
                        buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.yes') }}'],
                        dangerMode: true,
                    }).then((status) => {
                        if (status) {
                            var _token = $("input[name='_token']").val();
                            var senddata = { id: id, status: updateStatus, _token: _token}
                            $.ajax({
                                url: "{{ route('admin.supplier.changeStatus.ajax') }}",
                                type: 'POST',
                                data: senddata,
                                success: function (successData) {
                                    if (successData.success == true) {
                                        SnippetApp.toast.success("{{ __('admin.success') }}","{{ __('admin.supplier_status_updated_success_message') }}");
                                        $('#supplierTable').DataTable().ajax.reload(null,false);
                                    } else {
                                        if(successData.message!=''){
                                            SnippetApp.notify.alert(successData.message,2000);
                                        }else{
                                            SnippetApp.swal.info("","{{ __('admin.supplier_not_deactive_message') }}","{{ __('admin.ok') }}");
                                        }
                                    }
                                },
                                error: function () {
                                    console.log('error');
                                }
                            });
                        }
                    });
                });
            };
            return {
                init:function(){
                    supplierDataTable(),
                    viewSupplierDetails(),
                    deleteSupplier(),
                    changeSupplierStatus(),
                    createXenAccountFromAction(),
                    createXenAccountButtonSave()
                },
                getAllCheckedCategoriesArr : function () {
                    let category_ids = [];
                    let checkedCategories = $('.category_checkbox:checkbox:checked');
                    $.each(checkedCategories, function(key, value){
                        category_ids.push($(value).attr('data-id'));
                    });
                    return category_ids;
                },
                getAllCheckedSubCategoriesArr : function () {
                    let subcategory_ids = [];
                    let checkedSubCategories = $('.sub_category_checkbox:checkbox:checked');
                    $.each(checkedSubCategories, function(key, value){
                        subcategory_ids.push($(value).attr('data-id'));
                    });
                    return subcategory_ids;
                },
                getAllCheckedProductsArr : function () {
                    let product_ids = [];
                    let checkedProduct = $('.product_checkbox:checkbox:checked');
                    $.each(checkedProduct, function(key, value){
                        product_ids.push($(value).attr('data-id'));
                    });
                    return product_ids;
                },
                getAllCheckedStatusArr : function () {
                    let status_ids = [];
                    let checkedStatus = $('.status_checkbox:checkbox:checked');
                    $.each(checkedStatus, function(key, value){
                        status_ids.push($(value).attr('data-value'));
                    });
                    return status_ids;
                },
            }
        }(1);

        var SupplierFiltersTab = function(){
            btnApplyFilter = function(){
                $(document).on('click','#apply_btn', function(){
                    $('#supplierTable').DataTable().ajax.reload(null,false);
                });
            },
            btnResetFilter = function () {
                $(document).on('click','#clear_btn', function(){
                    SupplierFiltersTab.resetFilters();
                    $('#supplierTable').DataTable().ajax.reload(null,false);
                });
            },
            filtersKeyupEvents = function () {
                $("#category_filtersearch, #sub_category_filtersearch, #product_filtersearch").keyup(function() {
                    SupplierFiltersTab.searchFilterContent($(this), "#"+$(this).next().attr("id")+" div");
                });
            };
            return {
                init:function(){
                    btnApplyFilter(),
                    btnResetFilter(),
                    filtersKeyupEvents()
                },
                searchFilterContent : function (currentElement, ChildElement) {
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
                getSubcategoryByCategory : function () {
                    let category_ids = [];
                    let checkedCategories = $('.category_checkbox:checkbox:checked');
                    $.each(checkedCategories, function(key, value){
                        category_ids.push($(value).attr('data-id'));
                    });
                    if(category_ids.length > 0){
                        category_ids = category_ids;
                    }
                    $.ajax({
                        url: "{{ route('admin.supplier.getSupplierSubCategoryByCategory.ajax') }}",
                        method: 'POST',
                        data: {
                            categoriesArr: category_ids,
                            _token:"{{ csrf_token() }}"
                        },
                        success: function(successData) {
                            if(successData.success == true) {
                                var subCatHTML = '';
                                $.each(successData.subCategories, function(index, subCat) {
                                    subCatHTML = subCatHTML + "<div class='form-check'><input class='form-check-input sub_category_checkbox' type='checkbox' data-name='"+subCat.subCatName+"' data-id='"+subCat.id+"' id='sub_category"+subCat.id+"'  onclick='SupplierFiltersTab.getProductBySubcategory()'><label class='form-check-label pt-1 ' for='sub_category"+subCat.id+"'>"+subCat.subCatName+"</label></div>";
                                });
                                $("#sub_category_content").html(subCatHTML);
                                var productHTML = '';
                                $.each(successData.suppliersProducts, function(index, products) {
                                    productHTML = productHTML + "<div class='form-check'><input class='form-check-input product_checkbox' type='checkbox' data-name='"+products.name+"' data-id='"+products.id+"' id='product"+products.id+"'><label class='form-check-label pt-1 ' for='product"+products.id+"'>"+products.name+" - "+products.subcategory.name+"</label></div>";
                                });
                                $("#product_content").html(productHTML);
                            }
                        },
                        error: function() {
                            console.log('error');
                        }
                    });
                },
                getProductBySubcategory : function () {
                    let subcategory_ids = [];
                    let checkedSubCategories = $('.sub_category_checkbox:checkbox:checked');
                    $.each(checkedSubCategories, function(key, value){
                        subcategory_ids.push($(value).attr('data-id'));
                    });
                    if(subcategory_ids.length > 0){
                        subcategory_ids = subcategory_ids;
                    }

                    $.ajax({
                        url: "{{ route('admin.supplier.getProductBySubcategory.ajax') }}",
                        method: 'POST',
                        data: {
                            subCategoriesArr: subcategory_ids,
                            _token:"{{ csrf_token() }}"
                        },
                        success: function(successData) {
                            if(successData.success == true) {
                                var productHTML = '';
                                $.each(successData.subCategoriesProducts, function(index, products) {
                                    productHTML = productHTML + "<div class='form-check'><input class='form-check-input product_checkbox' type='checkbox' data-name='"+products.name+"' data-id='"+products.id+"' id='product"+products.id+"'><label class='form-check-label pt-1' for='product"+products.id+"'>"+products.name+" - "+products.name+"</label></div>";
                                });
                                $("#product_content").html(productHTML);
                            }
                        },
                        error: function() {
                            console.log('error');
                        }
                    });
                },
                resetFilters : function () {
                    /* clear search input and reset div elements */
                    $("#category_filtersearch").val("");
                    SupplierFiltersTab.searchFilterContent($("#category_filtersearch"), '#category_content div');

                    $("#sub_category_filtersearch").val("");
                    SupplierFiltersTab.searchFilterContent($("#sub_category_filtersearch"), '#sub_category_content div');

                    $("#product_filtersearch").val("");
                    SupplierFiltersTab.searchFilterContent($("#product_filtersearch"), '#product_content div');
                    /* clear search input and reset div elements */

                    $(".category_checkbox").prop( "checked", false);
                    $(".sub_category_checkbox").prop( "checked", false);
                    $(".product_checkbox").prop( "checked", false);
                    $(".status_checkbox").prop( "checked", false);
                    $('#filter_count_one').text("0 {{__('admin.filter_applied')}}");
                    $('#filter_count_two').text(0);

                    /* clear date and reset datepicker */
                    $("#start_date").val("");
                    $("#end_date").val("");
                    $('.input-daterange .form-control').datepicker('clearDates');
                    /* clear date and reset datepicker */
                    /*reset default Subcategory and Products*/
                    SupplierFiltersTab.getSubcategoryByCategory();
                }
            }
        }(1);

        $(function () {
            window.Parsley.addValidator('uniqueemail', {
                validateString: function (value) {
                    let res = false;
                    xhr = $.ajax({
                        url: '{{ route('check-xen-email-exist') }}',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        dataType: 'json',
                        method: 'POST',
                        data: {
                            email: value,
                        },
                        async: false,
                        success: function (data) {
                            res = data;
                        },
                    });
                    //console.log(res);
                    return res;
                },
                messages: {
                    en: 'This email already exists!'
                },
                priority: 32
            });
            $('#xenditpopup').on('hidden.bs.modal', function () {
                $("#xenAccountForm")[0].reset();
                $('#xenAccountForm').parsley().reset();
                $('#xenditpopup input').val('');
            });
        });

        function downloadimg(id, fieldName, name) {
            var data = {
                id: id,
                fieldName: fieldName
            }
            $.ajax({
                url: "{{ route('supplier-download-image-ajax') }}",
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
        function newexportaction(e, dt, button, config) {
            var self = this;
            var oldStart = dt.settings()[0]._iDisplayStart;
            dt.one('preXhr', function (e, s, data) {
                // Just this once, load all data from the server...
                data.start = 0;
                data.length = dt.page.info().recordsTotal;
                data.displayoption = "export";
                dt.one('preDraw', function (e, settings) {
                    // Call the original action function
                    if (button[0].className.indexOf('buttons-excel') >= 0) {
                        $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                    }
                    dt.one('preXhr', function (e, s, data) {
                        // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                        // Set the property to what it was before exporting.
                        settings._iDisplayStart = oldStart;
                        data.start = oldStart;
                    });
                    // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                    setTimeout(dt.ajax.reload, 0);
                    // Prevent rendering of the full data to the DOM
                    return false;
                });
            });
            // Requery the server with the new one-time export settings
            dt.ajax.reload();
        }
    </script>
@stop
