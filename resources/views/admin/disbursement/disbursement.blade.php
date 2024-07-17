@extends('admin/adminLayout')
@section('content')

    @push('bottom_head')
        <!-- <link href="{{ URL::asset('/assets/css/admin/filter.css') }}" rel="stylesheet"> -->
        <link rel="stylesheet" href="{{ URL::asset('/assets/vendors/ion-rangeslider/css/ion.rangeSlider.css') }}">
    @endpush

    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">

            <div class="d-flex align-items-center pb-3">
                <h4 class="card-title pt-3">{{__('admin.disbursement')}}</h4>
            </div>
            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-hover dataTable no-footer" style="width: 100%; height: 100%;" role="grid" aria-describedby="disburseTable_info"></table>
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
                        url     : "{{route('admin.credit.disbursement')}}",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data : function (data){
                            // filterData = getFilterData();
                            // data.filterData = filterData;
                        },
                        method  : "GET",
                    },
                    columns: [
                        {data: "id", title: "{{__('admin.id')}}"},
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
                        {data: "status", title: "{{ __('admin.status') }}"},
                        {data: "action", title: "{{ __('admin.action') }}" , class: "text-nowrap text-end"},
                    ],
                    aoColumnDefs: [
                        { "bSortable": false, "aTargets": [1, 4, 5, 6, 10, 12] },
                        { "visible": false, "aTargets": [0] }
                    ],
                    language: {
                        search: "{{__('admin.search')}}",
                        loadingRecords: "{{__('admin.please_wait_loading')}}",
                        processing: "<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i><span> {{__('admin.loading')}}..</span>"
                    },
                    order: [[0, 'desc']],

                });

                return financeTable;
            },

            paymentDatatableDraw = function () {
                paymentDueDatatable().draw();
            };


            return {
                init: function () {
                    paymentDatatableDraw()
                }
            }


        }(1);

        //disbursement to the supplier
        var disbusementToSupplier = function (selector,id) {
            selector.toggleClass('hidden');
            selector.after('<img id="disburse-loader'+id+'" class="ms-2" height="16px" src="{{URL('front-assets/images/icons/timer.gif')}}" title="Loading">');
            swal({
                title: "{{ __('dashboard.are_you_sure') }}?",
                text: '{{ sprintf(__('admin.do_you_want_to'),__('admin.disburse')) }}?',
                icon: "/assets/images/warn.png",
                buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                dangerMode: true,
            }).then((changeit) => {
                if (changeit) {
                    $.ajax({
                        url: "{{ route('admin.credit.supplier.disbursement','') }}/" + id,
                        type: 'GET',
                        dataType: 'JSON',
                        success: function (successData) {
                            $('#disburse-loader' + id).remove();
                            if (successData.success == true) {
                                selector.remove();
                                swal({
                                    title: successData.message,
                                    icon: "/assets/images/info.png",
                                    confirmButtonText: "{{__('admin.ok')}}",
                                    dangerMode: false,
                                });
                            } else {
                                swal({
                                    title: successData.message,
                                    icon: "/assets/images/warn.png",
                                    confirmButtonText: "{{__('admin.ok')}}",
                                    dangerMode: false,
                                });
                                selector.toggleClass('hidden');
                            }
                        },
                        error: function () {
                            $('#disburse-loader' + id).remove();
                            selector.toggleClass('hidden');
                            swal({
                                title: '{{ __('admin.something_error_message') }}',
                                icon: "/assets/images/warn.png",
                                confirmButtonText: "{{__('admin.ok')}}",
                                dangerMode: false,
                            });
                        }
                    });
                }else{
                    $('#disburse-loader' + id).remove();
                    selector.toggleClass('hidden');
                }
            });
        }

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
