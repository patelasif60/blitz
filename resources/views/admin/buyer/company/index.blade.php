@extends('admin/adminLayout')

@push('bottom_head')
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <style>
        .color-gray {
            color: gray;
        }

        .loader{
            position: fixed;
            text-align: center;
            width: 100%;
            left: 49%;
            top: 49%;
        }
    </style>

@endpush
@section('content')
    <div class="modal fade" id="BuyerCategoryModal" tabindex="-1" role="dialog"
         aria-labelledby="BuyerCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="BuyerCategoryModalLabel">{{ __('admin.category') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="BuyerCategoryModalBlock">

                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-success">Send Quote</button> --}}
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">
            <div class="d-flex align-items-center pb-3">
                <h4 class="card-title pt-3">{{ __('admin.companies') }}</h4>
                <div class="dropdown ms-auto me-1">
                    <div class="pe-1 mb-3 clearfix d-flex align-items-center">
                        <a href="{{ route('admin.buyer.company.list.company.excel') }}" class="btn btn-warning btn-sm ms-1" style="padding: 0.25rem 0.5rem;" type="button" id="dropdownMenuButton1" aria-expanded="false">
                            {{ __('admin.Export') }}
                        </a>
                    </div>
                </div>
                <input type="hidden" value="{!! csrf_token() !!}" name="_token">
            </div>
            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-hover dataTable no-footer" style="width: 100%; height: 100%;" role="grid" aria-describedby="companyTable_info"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- --- Buyer --- -->
    <!-- Modal -->
    <div class="modal fade version2" id="buyerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div id="modelLodear"></div>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            </div>
        </div>
    </div>
    @push('bottom_scripts')
        <script type="text/javascript">

            /******************begin: Jquery Module Initiate*********************/
            jQuery(document).ready(function(){
                SnippetCompanyTab.init();

            });
            /******************end: Jquery Module Initiate*********************/

            var SnippetCompanyTab = function(){
                    /** company List into Data Table **/
                var companyDatatable = function () {

                        var companyTable = $('.table').DataTable({
                            serverSide: !0,
                            paginate: !0,
                            processing: !0,
                            lengthMenu: [
                                [10, 25, 50],
                                [10, 25, 50],
                            ],
                            footer:!1,
                            ajax: {
                                url     : "{{route('admin.buyer.company.list.json')}}",
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                method  : "POST",
                            },
                            columns: [
                                {data: "user_name", title: "{{__('admin.name')}}"},
                                {data: "company_name", title: "{{__('admin.company_name')}}"},
                                {data: "company_email", title: "{{ __('admin.company_email')}}"},
                                {data: "company_phone", title: "{{ __('admin.company_phone') }}"},
                                {data: "contact_person_email", title: "{{ __('admin.contact_person_email') }}"},
                                {data: "category", title: "{{ __('admin.category') }}"},
                                {data: "status", title: "{{ __('admin.status') }}"},
                                {data: "register_on", title: "{{ __('admin.register_on') }}"},
                                {data: "updated_by", title: "{{ __('admin.updated_by') }}"},
                                {data: "actions", title: "{{ __('admin.actions') }}" , class: "text-nowrap text-end"}
                            ],
                            aoColumnDefs: [
                                { "bSortable": true, "aTargets": [0] },
                                { "bSortable": false, "aTargets": [ 3, 4, 6, 7, 8, 9 ] }
                            ],
                            language: {
                                search: "{{__('admin.search')}}",
                                loadingRecords: "{{__('admin.please_wait_loading')}}",
                                processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span> {{__('admin.loading')}}..</span> '
                            },
                            order: [[0, 'desc']],

                        });

                        return companyTable;
                    },

                    companyListDatatable = function () {
                        companyDatatable().draw();
                    },
                    /** View Company details **/
                    viewCompanyDetails = function () {    //View Popup for company details information
                        $(document).on('click', '.buyerModalView', function(e) {
                            $("#buyerModal").find(".modal-content").html('');
                            e.preventDefault();
                            var id = $(this).attr('data-id');
                            if (id) {
                                $.ajax({
                                    url : "{{ route('admin.buyer.company.view.company') }}",
                                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                    data :{id : id},
                                    type: 'POST',
                                    success: function(successData) {
                                        $("#buyerModal").find(".modal-content").html(successData.buyerView);
                                        $('#buyerModal').modal('show');
                                    },
                                    error: function() {
                                        console.log('error');
                                    }
                                });
                            }
                        });

                    },
                    /** company details change status **/
                    changeStatus = function () {
                        $(document).on('click', '.changeStatus', function() {
                            var id = $(this).attr('data-id');
                            var status = $(this).attr('data-status');
                            var updateStatus;
                            var text = '';
                            if (status == 0) {
                                text = '{{ __('admin.active_buyer_message') }}';
                                updateStatus = 1;
                            } else {
                                text = '{{ __('admin.deactive_buyer_message') }}';
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
                                            url: "{{ route('admin.buyer.company.list.update.status') }}",
                                            type: 'POST',
                                            data: senddata,
                                            success: function(successData) {
                                                if(successData.success == true){
                                                    SnippetApp.toast.success("{{ __('admin.success') }}",'{{ __('admin.buyer_status_updated_success_message') }}');
                                                    if (updateStatus == 1) {
                                                        $('#buyer' + id).removeClass('color-gray').attr(
                                                            'data-status', updateStatus);
                                                    } else {
                                                        $('#buyer' + id).addClass('color-gray').attr(
                                                            'data-status', updateStatus);
                                                    }
                                                } else {
                                                    swal({
                                                        text: '{{ __('admin.buyer_not_deactive_message') }}',
                                                        button: {
                                                            text: "{{ __('admin.ok') }}",
                                                            value: true,
                                                            visible: true,
                                                            className: "btn btn-primary"
                                                        }
                                                    })
                                                }

                                            },
                                            error: function() {
                                                console.log('error');
                                            }
                                        });

                                    }
                                });

                        });
                    },
                    /** Delete company details**/
                    deleteCompanyDetails = function () {
                        $(document).on('click', '.deleteBuyer', function() {
                            var id = $(this).attr('id').split("_")[1];
                            var companyId = $(this).attr('id').split("_")[2];

                            swal({
                                title: "{{ __('admin.delete_sure_alert') }}",
                                text: "{{ __('admin.supplier_delete_text') }}",
                                icon: "/assets/images/bin.png",
                                buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.delete') }}'],
                                dangerMode: true,
                            })
                                .then((willDelete) => {
                                    if (willDelete) {
                                        var _token = $("input[name='_token']").val();
                                        var senddata = {
                                            id: id,
                                            companyId :companyId,
                                            _token: _token
                                        }
                                        $.ajax({
                                            url: "{{ route('admin.buyer.company.list.remove') }}",
                                            type: 'POST',
                                            data: senddata,
                                            success: function(successData) {
                                                if(successData.success == true){
                                                    SnippetApp.toast.success("{{ __('admin.success') }}",'{{ __('admin.buyer_delete_success_message') }}');
                                                    location.reload();
                                                } else {
                                                    swal({
                                                        text: '{{ __('admin.buyer_not_delete_hard') }}',
                                                        button: {
                                                            text: "{{ __('admin.ok') }}",
                                                            value: true,
                                                            visible: true,
                                                            className: "btn btn-primary"
                                                        }
                                                    })
                                                }
                                            },
                                            error: function() {
                                                console.log('error');
                                            }
                                        });

                                    }
                                });

                        });
                    }


                return {
                    init: function () {
                        companyListDatatable(),
                        viewCompanyDetails(),
                        changeStatus(),
                        deleteCompanyDetails()
                    }
                }


            }(1);
    </script>
    @endpush
@stop
