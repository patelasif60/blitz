@extends('admin/adminLayout')

@push('bottom_head')
<style>
    .bullet-line-list a {
        color: #25378b;
        text-decoration: none;
    }

    .bullet-line-list li.active h6 a {
        color: #23af47;
    }

    .bullet-line-list li.active:before {
        border: 4px solid #23af47;
    }

    .strikethrough{
        text-decoration: line-through;
    }
    .mr-5{
        margin-right: 5px !important;
    }
    .modal.version2 .form-check .form-check-input {
        margin-top: 1px;
    }
    .form-check-input:read-only{filter: brightness(90%); }
</style>
<meta name="csrf-token" content="{!! csrf_token() !!}">
@endpush
@section('content')

<div class="card h-100 bg-transparent newtable_v2">
    <div class="card-body p-0">
        <h4 class="card-title">{{ __('admin.group') }} {{ __('admin.transactions') }}</h4>
        <div class="row clearfix">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="orderTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th class="hidden">ID</th>
                                {{--<th>Order Id</th>--}}
                                <th>{{ __('admin.quote_number') }}</th>
                                <th>{{ __('admin.order_number') }}</th>
                                <th>{{ __('admin.invoice_id') }}</th>
                                <th>{{ __('admin.supplier_company') }}</th>
                                <th>{{ __('admin.status') }}</th>
                                <th>{{ __('admin.invoice_created_at') }}</th>
                                <th>{{ __('admin.invoice_expiry_at') }}</th>
                                <th>{{ __('admin.client_name') }}</th>
                                <th>{{ __('admin.payment_method') }}</th>
                                <th>{{ __('admin.payment_channel') }}</th>
                                <th>{{ __('admin.amount') }}</th>
                                <th>{{ __('admin.adjusted_received_amount') }}</th>
                                <th>{{ __('admin.paid_at') }}</th>
                                <th>{{ __('admin.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($transactions) > 0)
                            @foreach ($transactions as $transaction)
                            <tr>
                                <td class="hidden">{{ $transaction->id }}</td>
                                <td>
                                    <a href="javascript:void(0);" style="text-decoration: none; color: #000" class="vieQuoteDetail hover_underline" data-bs-toggle="modal" data-bs-target="#viewQuoteModal" data-id="{{ $transaction->quote_id }}">{{ $transaction->external_id }}</a>
                                </td>
                                <td>
                                    @if($transaction->order_id)
                                    <a href="javascript:void(0);" style="text-decoration: none; color: #000" class="getSingleOrderDetail hover_underline" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-id="{{ $transaction->order_id }}">{{ $transaction->order_number }}</a>
                                    @endif
                                </td>
                                <td>{{ $transaction->invoice_id }}</td>
                                <td>{{ $transaction->supplier_company?$transaction->supplier_company:$transaction->merchant_name }}</td>
                                <td>
                                    @if($transaction->status=='PENDING')
                                        <span class="badge badge-pill badge-info">{{ ucwords(strtolower($transaction->status)) }}</span>
                                    @elseif($transaction->status=='PAID' || $transaction->status=='SETTLED')
                                        <span class="badge badge-pill badge-success">{{ ucwords(strtolower($transaction->status)) }}</span>
                                    @else
                                        <span class="badge badge-pill badge-danger">{{ ucwords(strtolower($transaction->status)) }}</span>
                                    @endif
                                </td>
                                <td>{{ $transaction->created?changeDateTimeFormat($transaction->created):'' }}</td>
                                <td>{{ $transaction->expiry_date?changeDateTimeFormat($transaction->expiry_date):'' }}</td>
                                <td>{{ $transaction->customer?(json_decode($transaction->customer,true)['given_names']):'' }}</td>
                                <td>{{ ucwords(str_replace('_',' ',strtolower($transaction->payment_method))) }}</td>
                                <td>{{ $transaction->payment_channel }}</td>
                                <td class="text-nowrap">{{ 'Rp ' . number_format($transaction->amount, 2) }}</td>
                                <td class="text-nowrap">{{ 'Rp ' . number_format($transaction->adjusted_received_amount, 2) }}</td>
                                <td>{{ $transaction->paid_at?changeDateTimeFormat($transaction->paid_at):'' }}</td>
                                <td class="text-end text-nowrap">
                                    @if(($transaction->status=='PAID' || $transaction->status=='SETTLED')&&(empty($transaction->disb_status)))
                                        <a class="bg_icon ms-2" data-bs-toggle="" data-bs-target="#" data-toggle="tooltip" data-placement="top" title="" data-bs-original-title="{{__('admin.disburse')}}" aria-label="{{__('admin.disburse')}}" href="javascript:void(0);" onclick="disbusementModel({{$transaction->group_id}},{{$transaction->order_id}})"><img src="{{URL::asset('assets/icons/Disbursement.png')}}" alt="disburse"></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal version2 fade" id="viewQuoteModal" tabindex="-1" role="dialog"
     aria-labelledby="viewRfqModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>
<div class="modal version2 fade" id="staticBackdrop" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="getSingleOrderDetail">
        </div>
    </div>
</div>

<div class="modal version2 fade" id="disbursementModal" tabindex="-1" aria-labelledby="disbursementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
                <div class="modal-content" id="disbursement-modal-content">

                </div>
            </div>
    </div>
@stop
{{--</div>--}}
@push('bottom_scripts')
    <script>
        let bank_default_logo = "{{ URL('assets/icons/bank.png') }}";
        let disbursement_charge = {{getDisbursementCharge()}};
        $(document).ready(function() {
            $('#orderTable').DataTable({
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
            });

        });

        $(document).on('click', '.vieQuoteDetail', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            if (id) {
                $.ajax({
                    url: "{{ route('quote-detail', '') }}" + "/" + id,
                    type: 'GET',
                    success: function(successData) {
                        $('#viewQuoteModal').find('.modal-content').html(successData.quoteHTML);
                        $('#viewQuoteModal').modal('show');
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        });

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

        $(document).on('click','input:checkbox[readonly]',function(){
            return false;
        });

        $(document).on('click','#disbursementSubmitBtn',function () {
            let formData = new FormData($('#disbursementForm')[0]);
            $.ajax({
                url: "{{ route('group-settlement') }}",
                data: formData,
                type: 'POST',
                contentType: false,
                processData: false,
                dataType: 'JSON',
                success: function(successData) {
                    removeValidationMessage();
                    $('#disbursementForm').submit();
                },
                error: function(error) {
                    removeValidationMessage();
                    console.log(error.responseJSON.errors);
                    $.each(error.responseJSON.errors, function(key, value) {
                            let selector = $('#'+ key);
                            selector.after('<span id="'+key+'_error" class="invalid-feedback d-block text-danger bk-validation-error">'+value+'</span>');
                    });
                }
            });
        });

        let removeValidationMessage = function () {
            $('#disbursementForm .bk-validation-error').remove();
        }

        function setTax(tax,amount) {
            return amount+((amount*tax)/100);
        }

        function disbusementModel(groupId,orderId) {
            $.ajax({
                url: "{{ route('group-order-charges','') }}",
                type: 'POST',
                data: {group_id:groupId,order_id: orderId,_token:$('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                success: function (successData) {
                    //console.log(successData.data);
                    if(successData.success) {
                        let order = successData.order;
                        getXenBalance(order.supplier_id);
                        $('#disbursementModal').modal('show');
                        $('#disbursement-modal-content').html(successData.html);
                        calc();
                        blitznet_commission();
                    }
                }
            });
        }

        function getXenBalance(supplierId) {
            $.ajax({
                url: "<?php echo e(route('xen-balance','')); ?>" + "/" + supplierId,
                type: 'GET',
                dataType: 'json',
                success: function (successData) {
                    $('#xen_balance').html('Rp '+(successData.data?successData.data:0));
                },
                error: function(error) {
                    $('#xen_balance').html('Rp 0');
                    $.toast({
                        heading: 'Warning',
                        text: error.responseJSON.message,
                        showHideTransition: 'slide',
                        icon: 'warning',
                        loaderBg: '#57c7d4',
                        position: 'top-right'
                    })
                    //console.log(error);
                }
            });
        }

        function calcProductAmount(product_amount,tax){
            if (tax){
                return product_amount+((product_amount*tax)/100);
            }
            return product_amount;
        }
        $('#disbursementModal').on('hidden.bs.modal', function () {
            $('#xen_balance').html('<img height="16px" src="'+'{{ URL::asset('front-assets/images/icons/timer.gif') }}'+'" title="Loading">');
        });

        $(document).on('click','#log_charges',function(){
            $(".add_remove_charge").prop('checked', $(this).prop('checked'));
            strikethrough($(this).prop('checked'));
            blitznet_commission();
        });
        function strikethrough(checked) {
            if(checked) {
                $('.add_remove_charge').removeClass('data-exclude');
                $('.add_remove_charge').closest('tr').removeClass('strikethrough')
            } else {
                $('.add_remove_charge').addClass('data-exclude');
                $('.add_remove_charge').closest('tr').addClass('strikethrough');
            }
            calc();
        }
        $(document).on('change', "select#blitznet_commission_type",function () {
            if ($(this).val()=='0'){
                $('input#blitznet_commission_per').removeClass('d-none');
                $('span#total_blitznet_commission_span').removeClass('d-none');
                $('div#blitznet_commission_amount_div').addClass('d-none');
                blitznet_commission();
            }else{
                $('input#blitznet_commission_per').addClass('d-none');
                $('span#total_blitznet_commission_span').addClass('d-none');
                $('input#blitznet_commission_amount').val(0);
                $('div#blitznet_commission_amount_div').removeClass('d-none');
            }
            calc_disbursement_amount();
        });

        let setStrikethrough = function(selector) {
            if(selector.prop('checked')) {
                selector.closest('tr').find('select, input:not([type="checkbox"])').prop('disabled',false);
                selector.closest('tr').removeClass('strikethrough')
            } else {
                selector.closest('tr').find('select, input:not([type="checkbox"])').prop('disabled',true);
                selector.closest('tr').addClass('strikethrough');
            }
        }
        $(document).on('change', "input#blitznet_commission",function () {
            setStrikethrough($(this));
            blitznet_commission();
        });
        $(document).on('change', "input#blitznet_commission_per, input#blitznet_commission_amount",function () {
            blitznet_commission();
        });
        let calc = function() {
            let tot = 0;
            $(".calc").each(function() {
                let amount = parseFloat($(this).attr('data-amount'));
                let is_add = parseInt($(this).attr('data-is_add'));
                if(!$(this).hasClass('data-exclude')) {
                    if (is_add) {
                        tot = tot + amount;
                    } else {
                        tot = tot - amount;
                    }
                }
            });
            $('#sub-payble-amount').html(Math.round(tot));
            if ($('#disbursment_charge_xendit').length) {
                tot = tot - disbursement_charge;
            }

            $('#payble-amount').html(Math.round(tot));
        }
        let final_amount = function () {
            let tot = parseFloat($('#payble-amount').text());

            if ($('#supplier_transaction_fees').length && $('#supplier_transaction_fees').prop('checked')) {
                tot = tot - parseFloat($('#supplier_transaction_fees').val());
            }
            return Math.round(tot);
        }
        let blitznet_commission = function () {
            let payble_amount = parseInt($('#sub-payble-amount').text()?$('#sub-payble-amount').text():0);
            let blitznet_comm = 0;
            if (payble_amount>0) {
                let commission_per = 0;
                if ($('#blitznet_commission_type').val() == '0') {
                    commission_per = parseFloat($('#blitznet_commission_per').val() ? $('#blitznet_commission_per').val() : 0);
                    blitznet_comm = (payble_amount*commission_per)/100;
                }else{
                    blitznet_comm = parseFloat($('#blitznet_commission_amount').val());
                    let disbursable_final_amount = final_amount();
                    if (disbursable_final_amount<blitznet_comm){
                        blitznet_comm = disbursable_final_amount;
                    }
                }
                blitznet_comm = Math.round(blitznet_comm);
                $('#blitznet_commission_amount').val(blitznet_comm);
                $('#total_blitznet_commission_span').html('-'+blitznet_comm);
            }
            calc_disbursement_amount();
        }
        $(document).on('change', "select#disbursement_amount_type",function () {
            if ($(this).val()=='0'){
                $('input#disbursement_amount_per').removeClass('d-none');
                $('span#final_amount_span').removeClass('d-none');
                $('div#final_disburse_amount_div').addClass('d-none');
            }else{
                $('input#disbursement_amount_per').addClass('d-none');
                $('span#final_amount_span').addClass('d-none');
                $('div#final_disburse_amount_div').removeClass('d-none');
            }
            calc_disbursement_amount();
        });
        $(document).on('change', "input#disbursement_amount_per",function () {
            calc_disbursement_amount();
        });
        $(document).on("focusin","input#blitznet_commission_amount, input#final_disburse_amount, input#blitznet_commission_per, input#disbursement_amount_per",function(){
            $(this).val("");
        });
        $(document).on("keyup","input#blitznet_commission_per, input#disbursement_amount_per",function(){
            if ($(this).val() > 100){
                $(this).val(100);
            }
        });
        let calc_disbursement_amount = function(is_return=0){
            let disbursement_amount = parseInt($('#payble-amount').text()?$('#payble-amount').text():0);
            let blitznet_commission = 0;
            let supplier_transaction_fees = 0;
            if ($('#supplier_transaction_fees').length && $('#supplier_transaction_fees').prop('checked')) {
                supplier_transaction_fees = parseInt($('#supplier_transaction_fees').val()?$('#supplier_transaction_fees').val():0);
            }
            disbursement_amount = disbursement_amount-supplier_transaction_fees;
            if ($('#blitznet_commission').prop('checked')) {
                blitznet_commission = parseInt($('#blitznet_commission_amount').val()?$('#blitznet_commission_amount').val():0);
            }
            disbursement_amount = disbursement_amount-blitznet_commission;
            if ($('#disbursement_amount_type').val()!=1) {
                let disbursement_amount_per = parseFloat($('#disbursement_amount_per').val());
                if (disbursement_amount_per>100){
                    disbursement_amount_per = 100;
                    $('#disbursement_amount_per').val(disbursement_amount_per);
                }else if (disbursement_amount_per<1){
                    disbursement_amount_per = 1;
                    $('#disbursement_amount_per').val(disbursement_amount_per);
                }
                disbursement_amount = (disbursement_amount*disbursement_amount_per)/100;
            }
            disbursement_amount = Math.round(disbursement_amount?disbursement_amount:0);
            if (is_return) {
                return disbursement_amount;
            }
            check_disbursement_amount_valid(disbursement_amount);
        }
        let check_disbursement_amount_valid = function(disbursement_amount){
            let max_disbursement_amount = parseInt($('#max_disbursement_amount').val());
            $('#refundable_amount_tr').addClass('d-none');
            //if(max_disbursement_amount<disbursement_amount){
                let payble_amount = parseInt($('#payble-amount').text()?$('#payble-amount').text():0);
                let refundable_amount = payble_amount-max_disbursement_amount;
                //console.log(refundable_amount);
                if(refundable_amount>0) {
                    $('#refundable_amount').html('-' + refundable_amount);
                    $('#refundable_amount_tr').removeClass('d-none');
                    disbursement_amount = disbursement_amount - refundable_amount;
                }
            //}

            $('#final_disburse_amount').val(disbursement_amount);
            $('#final_amount_span').html('-'+disbursement_amount);
            $('#total_disbursement_amount').html(disbursement_amount);
            $('#disbursementSubmitBtn').show();
            $('#amount-greater').hide();
            removeValidationMessage();
            if (disbursement_amount < {{getMinDisbursementAmount()}}) {
                $('#amount-greater').show();
                $('#disbursementSubmitBtn').hide();
            }
        }
        $(document).on('change', "input#final_disburse_amount",function () {
            let payble_amount = parseFloat($(this).val()?$(this).val():0);
            let tot_disbursement_amount = calc_disbursement_amount(1);
            if (tot_disbursement_amount<payble_amount){
                payble_amount = tot_disbursement_amount;
            }
            payble_amount = Math.round(payble_amount);
            check_disbursement_amount_valid(payble_amount);
        });
        let isNumberKey = function (txt, evt) {
            let charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode == 46) {
                //Check if the text already contains the . character
                if (txt.value.indexOf('.') === -1) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if (charCode > 31 &&
                    (charCode < 48 || charCode > 57))
                    return false;
            }
            return true;
        }

    </script>
@endpush

