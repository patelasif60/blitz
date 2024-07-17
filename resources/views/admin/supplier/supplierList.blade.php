@extends('admin/adminLayout')
@push('bottom_head')
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <link href="{{ URL::asset('/assets/css/admin/filter.css') }}" rel="stylesheet">
    <style>
        .color-gray {
            color: gray;
        }

        .loader {
            position: fixed;
            text-align: center;
            width: 100%;
            left: 49%;
            top: 49%;
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
                    <div class="pe-1 mb-3 clearfix d-flex align-items-center">
						<span class="text-muted me-2" id="filter_count_one"
                              style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">
							0 {{__('admin.filter_applied')}}
						</span>
                        <div class="nav-item nav-settings">
                            <a class="btn btn-dark btn-sm" style="padding: 0.25rem 0.5rem;" href="javascript:void(0)">Filter</a>
                        </div>
                        @if(\Auth::user()->hasRole('admin'))
                        <a href="javascript:void(0)" id="exportSuppliers" class="btn btn-warning btn-sm ms-1"
                               style="padding: 0.25rem 0.5rem;" role="button">
								 {{ __('admin.Export') }}
                        </a>
                        @endif
                        @can('create supplier list')
                           {{-- <a href="{{ route('supplier-add') }}" type="button"
                               class="btn btn-primary btn-sm ms-1">{{ __('admin.add') }}</a>--}}
                            <a href="{{ route('admin.supplier.create') }}" type="button"
                               class="btn btn-primary btn-sm ms-1">{{ __('admin.add') }}</a>
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
                                                            <input type="checkbox" class="form-check-input category_checkbox" id="category{{$value->id}}" data-id="{{$value->id}}" data-name="{{$value->name}}" onclick="getSubcategoryByCategory()">
                                                            <label class="form-check-label" for="category{{$value->id}}"> {{$value->category_name}} </label>
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
                                                            <input type="checkbox" class="form-check-input sub_category_checkbox" id="sub_category{{$value->id}}" data-id="{{$value->id}}" data-name="{{$value->name}}" onclick="getProductBySubcategory()">
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
                                <th>{{ __('admin.name') }}</th>
                                <th>{{ __('admin.company_email') }}</th>
                                <th>{{ __('admin.mobile') }}</th>
                                <th>{{ __('admin.contact_person_email') }}</th>
                                <th>{{ __('admin.category') }}</th>
                                <th>{{ __('admin.status') }}</th>
                                <th>{{ __('admin.added_by') }}</th>
                                <th>{{ __('admin.updated_by') }}</th>
                                <th>{{ __('admin.added_date') }}</th>
                                @canany(['edit supplier list' , 'delete supplier list' , 'create supplier list' , 'publish supplier list'])
                                    <th class="text-center">{{ __('admin.actions') }}</th>
                                @endcan
                            </tr>
                            </thead>
                            <tbody id="supplierData">

                            </tbody>
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
                    <button type="button" class="btn btn-primary"
                            id="create-xen-account">{{ __('admin.create') }}</button>
                    <img class="hidden" id="loader" height="16px"
                         src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading">
                    <button type="button" class="btn btn-cancel"
                            data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            //filter
            var supplierDataHtml = @json($supplierDataHtml??[]);
            $('#supplierData').html(supplierDataHtml);

            $('#supplierTable').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "aLengthMenu": [
                    [10, 20, 50, -1],
                    [10, 20, 50, "All"]
                ],
                "iDisplayLength": 10,
                'columnDefs': [{
                    'targets': [1, 7, 8, 9], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }, {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                }]
            });
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
            {{---- Remove loader in supplier @ekta  24/02/22 ----}}
            $(document).on('click', '.supplierModalView', function (e) {
                $("#supplierModal").find(".modal-content").html('');
                <!--$("#modelLodear").html('<img src="{{ URL::asset('assets/images/front/loader.gif') }}" style="height: 50px;width: 50px;" aria-hidden="true" class="hidden loader">');-->
                // $('#modelLodear .loader').toggleClass('hidden');
                e.preventDefault();
                var id = $(this).attr('data-id');
                if (id) {
                    $.ajax({
                        url: "{{ route('supplier-detail-view', '') }}" + "/" + id,
                        type: 'GET',
                        success: function (successData) {
                            // $('#modelLodear .loader').toggleClass('hidden');
                            $("#supplierModal").find(".modal-content").html(successData.rfqview);
                            $('#supplierModal').modal('show');
                        },
                        error: function () {
                            console.log('error');
                        }
                    });
                }
            });
            $(document).on('click', '.deleteSupplier', function () {
                var id = $(this).attr('id').split("_")[1];
                swal({
                    title: "{{ __('admin.delete_sure_alert') }}",
                    text: "{{ __('admin.supplier_delete_text') }}",
                    icon: "/assets/images/bin.png",
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.delete') }}'],
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            // swal("Poof! Your imaginary file has been deleted!", {
                            //     icon: "success",
                            // });

                            var _token = $("input[name='_token']").val();
                            var senddata = {
                                id: id,
                                _token: _token
                            }
                            $.ajax({
                                url: "{{ route('supplier-delete') }}",
                                type: 'POST',
                                data: senddata,
                                success: function (successData) {
                                    if (successData.success == true) {
                                        $.toast({
                                            heading: '{{ __('admin.success') }}',
                                            text: '{{ __('admin.supplier_delete_success_message') }}',
                                            showHideTransition: 'slide',
                                            icon: 'success',
                                            loaderBg: '#f96868',
                                            position: 'top-right'
                                        })
                                        location.reload();
                                    } else {
                                        swal({
                                            //  title: 'Supplier ',
                                            text: '{{ __('admin.supplier_not_delete_hard') }}',
                                            button: {
                                                text: "{{ __('admin.ok') }}",
                                                value: true,
                                                visible: true,
                                                className: "btn btn-primary"
                                            }
                                        })
                                    }
                                    //   location.reload();

                                },
                                error: function () {
                                    console.log('error');
                                }
                            });

                        }
                    });

            });

            $(document).on('click', '.changeStatus', function () {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                var updateStatus;
                var text = '';
                if (status == 0) {
                    text = "{{ __('admin.active_supplier_message') }}?";
                    updateStatus = 1;
                } else {
                    text = "{{ __('admin.deactive_supplier_message') }}?";
                    updateStatus = 0;
                }

                swal({
                    title: "{{ __('admin.delete_sure_alert') }}",
                    text: text,
                    icon: "/assets/images/info.png",
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.yes') }}'],
                    dangerMode: true,
                })
                    .then((status) => {
                        if (status) {
                            var _token = $("input[name='_token']").val();
                            var senddata = {
                                id: id,
                                status: updateStatus,
                                _token: _token
                            }
                            $.ajax({
                                url: "{{ route('supplier-status-change-ajax') }}",
                                type: 'POST',
                                data: senddata,
                                success: function (successData) {

                                    if (successData.success == true) {
                                        $.toast({
                                            heading: '{{ __('admin.success') }}',
                                            text: '{{ __('admin.supplier_status_updated_success_message') }}.',
                                            showHideTransition: 'slide',
                                            icon: 'success',
                                            loaderBg: '#f96868',
                                            position: 'top-right'
                                        });
                                        if (updateStatus == 1) {
                                            $('#supplier' + id).removeClass('color-gray').attr(
                                                'data-status', updateStatus);
                                            $('#create-xenaccount' + id).show();
                                        } else {
                                            $('#supplier' + id).addClass('color-gray').attr(
                                                'data-status', updateStatus);
                                            $('#create-xenaccount' + id).hide();
                                        }
                                        //location.reload();
                                    } else {
                                        if(successData.message!=''){
                                         new PNotify({
                                            text: successData.message,
                                            type: 'error',
                                            styling: 'bootstrap3',
                                            animateSpeed: 'fast',
                                            delay: 2000
                                        });
                                    }else{
                                        swal({
                                            //  title: 'Supplier ',
                                            text: '{{ __('admin.supplier_not_deactive_message') }}',
                                            button: {
                                                text: "{{ __('admin.ok') }}",
                                                value: true,
                                                visible: true,
                                                className: "btn btn-primary"
                                            }
                                        })
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

            $(document).on('click', '.showAllCategory', function (e) {
                e.preventDefault();
                var supplierId = $(this).attr('data-supplier-id');
                if (supplierId) {
                    var _token = $("input[name='_token']").val();
                    var senddata = {
                        id: supplierId,
                        _token: _token
                    }
                    $.ajax({
                        url: "{{ route('supplier-get-all-category-ajax') }}",
                        type: 'POST',
                        data: senddata,
                        success: function (successData) {
                            var dataArray = [];
                            var html = '';
                            html += '<ul class="list-group list-group-flush">';
                            $.each(successData.pieces, function (index, value) {
                                if (!dataArray.includes(value)) {
                                    dataArray.push(value);
                                    html += '<li class="list-group-item">' + value
                                        + '</li>';
                                }
                            });
                            html += '</ul>';
                            $('#SupplierCategoryModalBlock').html(html);
                            $('#SupplierCategoryModal').modal('show');
                            // console.log(successData);
                            //location.reload();

                        },
                        error: function () {
                            console.log('error');
                        }
                    });
                }

            })
            $('#xenditpopup').on('hidden.bs.modal', function () {
                $("#xenAccountForm")[0].reset();
                $('#xenAccountForm').parsley().reset();
                $('#xenditpopup input').val('');
            });
        });

        /*****************begin: filter functions  *********************/
        //Export Supplier Data
        $(document).on('click','#exportSuppliers', function(){
            let filterData = getSupplierRequestData();
            $.ajax({
                url: "{{  route('export-excel-supplier-ajax')}}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'POST',
                data: filterData,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (response) {
                    var blob = new Blob([response], { type: "application/vnd.ms-excel" });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'suppliers.xlsx';
                    link.click();

                },
                error: function () {
                    console.log('error');
                }
            });
        });
        //Clear filters
        $(document).on('click','#clear_btn', function(){
            resetFilters();
            let emptyData = {};
            reinitializeDataTable(emptyData);
        });

        //reset filters
        function resetFilters(){
            /* clear search input and reset div elements */
            $("#category_filtersearch").val("");
            searchFilterContent($("#category_filtersearch"), '#category_content div');

            $("#sub_category_filtersearch").val("");
            searchFilterContent($("#sub_category_filtersearch"), '#sub_category_content div');

            $("#product_filtersearch").val("");
            searchFilterContent($("#product_filtersearch"), '#product_content div');

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
            getSubcategoryByCategory();
        }

        //get Filter data
        function getSupplierRequestData(){
            let data = {};
            let filterCount = 0;

            let category_ids = [];
            let checkedCategories = $('.category_checkbox:checkbox:checked');
            $.each(checkedCategories, function(key, value){
                category_ids.push($(value).attr('data-id'));
            });
            if(category_ids.length > 0){
                data.category_ids = category_ids;
                filterCount++;
            }

            let subcategory_ids = [];
            let checkedSubCategories = $('.sub_category_checkbox:checkbox:checked');
            $.each(checkedSubCategories, function(key, value){
                subcategory_ids.push($(value).attr('data-id'));
            });
            if(subcategory_ids.length > 0){
                data.subcategory_ids = subcategory_ids;
                filterCount++;
            }

            let product_ids = [];
            let checkedProduct = $('.product_checkbox:checkbox:checked');
            $.each(checkedProduct, function(key, value){
                product_ids.push($(value).attr('data-id'));
            });
            if(product_ids.length > 0){
                data.product_ids = product_ids;
                filterCount++;
            }

            let status_ids = [];
            let checkedStatus = $('.status_checkbox:checkbox:checked');
            $.each(checkedStatus, function(key, value){
                status_ids.push($(value).attr('data-value'));
            });
            if(status_ids.length > 0){
                data.status_ids = status_ids;
                filterCount++;
            }

            let start_date = $('#start_date').val();
            let end_date = $('#end_date').val();
            let date_range = [];
            if(start_date && end_date){
                data.start_date = start_date;
                data.end_date = end_date;
                filterCount++;
            }
            $('#filter_count_one').text(filterCount+" {{__('admin.filter_applied')}}");
            $('#filter_count_two').text(filterCount);
            return data;
        }

        //get Subcategory By Category
        function getSubcategoryByCategory() {
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
                            subCatHTML = subCatHTML + "<div class='form-check'><input class='form-check-input sub_category_checkbox' type='checkbox' data-name='"+subCat.subCatName+"' data-id='"+subCat.id+"' id='sub_category"+subCat.id+"'  onclick='getProductBySubcategory()'><label class='form-check-label pt-1 ' for='sub_category"+subCat.id+"'>"+subCat.subCatName+"</label></div>";
                        });
                        $("#sub_category_content").html(subCatHTML);
                        var productHTML = '';
                        $.each(successData.suppliersProducts, function(index, subCatProducts) {
                           productHTML = productHTML + "<div class='form-check'><input class='form-check-input product_checkbox' type='checkbox' data-name='"+subCatProducts.subCatProductsName+"' data-id='"+subCatProducts.subCatProductsid+"' id='product"+subCatProducts.subCatProductsid+"'><label class='form-check-label pt-1 ' for='product"+subCatProducts.subCatProductsid+"'>"+subCatProducts.subCatProductsName+" - "+subCatProducts.subCatsName+"</label></div>";
                        });
                        $("#product_content").html(productHTML);
                    }
                },
                error: function() {
                    console.log('error');
                }
            });
        }

        //get Product By Subcategory
        function getProductBySubcategory() {
            let subcategory_ids = [];
            let checkedSubCategories = $('.sub_category_checkbox:checkbox:checked');
            $.each(checkedSubCategories, function(key, value){
                subcategory_ids.push($(value).attr('data-id'));
            });
            if(subcategory_ids.length > 0){
                subcategory_ids = subcategory_ids;
            }

            $.ajax({
                url: "{{ route('get-supplier-product-by-subcategory-ajax') }}",
                method: 'POST',
                data: {
                    subCategoriesArr: subcategory_ids,
                    _token:"{{ csrf_token() }}"
                },
                success: function(successData) {
                    if(successData.success == true) {
                        var productHTML = '';
                        $.each(successData.subCategoriesProducts, function(index, subCatProducts) {
                            productHTML = productHTML + "<div class='form-check'><input class='form-check-input product_checkbox' type='checkbox' data-name='"+subCatProducts.subCatProductsName+"' data-id='"+subCatProducts.id+"' id='product"+subCatProducts.id+"'><label class='form-check-label pt-1' for='product"+subCatProducts.id+"'>"+subCatProducts.subCatProductsName+" - "+subCatProducts.subCatsName+"</label></div>";
                        });
                        $("#product_content").html(productHTML);
                    }
                },
                error: function() {
                    console.log('error');
                }
            });
        }
        //Apply filters
        $(document).on('click','#apply_btn', function(){
            let data = getSupplierRequestData();
            reinitializeDataTable(data);
        });
        //Reinitialize table
        function reinitializeDataTable(data){
            $.ajax({
                url: "{{ route('admin.supplier.supplier-list1') }}",
                data: data,
                type: 'GET',
                success: function(successData) {
                    // console.log(successData);
                    $('#supplierTable').DataTable().destroy();
                    $('#supplierData').html(successData);
                    $('#supplierTable').DataTable({
                        "order": [
                            [0, "desc"]
                        ],
                        "aLengthMenu": [
                            [10, 20, 50, -1],
                            [10, 20, 50, "All"]
                        ],
                        "iDisplayLength": 10,
                        "columnDefs": [{
                            "targets": [0],
                            "visible": false,
                            "searchable": false
                        }, ]
                    }).draw();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        //Search filter content
        function searchFilterContent(currentElement, ChildElement){
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
        }

        $("#category_filtersearch").keyup(function() {
            searchFilterContent($(this), '#category_content div');
        });

        $("#sub_category_filtersearch").keyup(function() {
            searchFilterContent($(this), '#sub_category_content div');
        });

        $("#product_filtersearch").keyup(function() {
            searchFilterContent($(this), '#product_content div');
        });

        /*****************end: filter functions  *********************/

        $(document).on("click", ".create-xenaccount", function () {
            $("#xenditpopup #supplier-id").val($(this).data('id'));
            $("#xenditpopup #xen-ac-name").val($(this).data('name'));
            $("#xenditpopup #xen-email").val($(this).data('email'));
        });

        function downloadimg(id, fieldName, name) {
            event.preventDefault();
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
                            $.toast({
                                heading: '{{ __('admin.success') }}',
                                text: '{{ __('admin.xen_create_success_message') }}',
                                showHideTransition: 'slide',
                                icon: 'success',
                                loaderBg: '#f96868',
                                position: 'top-right'
                            });
                            $('#create-xenaccount' + supplierId).remove();
                            $('#xenditpopup').modal('hide');
                        } else {
                            $.toast({
                                heading: '{{ __('admin.warning') }}',
                                text: '{{ __('admin.something_error_message') }}',
                                showHideTransition: 'slide',
                                icon: 'warning',
                                loaderBg: '#57c7d4',
                                position: 'top-right'
                            })
                        }
                        that.toggleClass('hidden');
                        $('#loader').toggleClass('hidden');
                    },
                    error: function (error) {
                        $.toast({
                            heading: '{{ __('admin.warning') }}',
                            text: error.responseJSON.message,
                            showHideTransition: 'slide',
                            icon: 'warning',
                            loaderBg: '#57c7d4',
                            position: 'top-right'
                        })
                        that.toggleClass('hidden');
                        $('#loader').toggleClass('hidden');
                        console.log('error');
                    }
                });
            }
        });
    </script>
@stop
