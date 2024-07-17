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
        <h4 class="card-title">{{ __('admin.transactions') }}</h4>
        <div class="row clearfix">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="orderTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th class="hidden">ID</th>
                                {{--<th>Order Id</th>--}}
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
                                {{-- <th>State</th> --}}
                                {{--<th>Status</th>--}}
                                <th>{{ __('admin.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($transactions) > 0)
                            @foreach ($transactions as $transaction)
                            <tr>
                                <td class="hidden">{{ $transaction->id }}</td>
                                <td>
                                    @if(!strpos($transaction->external_id,'|'))
                                    {{-- if single payment --}}
                                    <a href="javascript:void(0);" style="text-decoration: none; color: #000" class="getSingleOrderDetail hover_underline" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-id="{{ $transaction->order_id }}">{{ $transaction->external_id }}</a>
                                    @else
                                        {{-- else bulk payment --}}
                                        {{str_replace("|"," | ",$transaction->external_id)}}
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
                                    @php
                                        $isBulkPayment = strpos($transaction->external_id,'|');
                                    @endphp
                                    @if(in_array($transaction->order_id,$bulkPaymentOrderIds) && ($transaction->order_status==5 && (empty($transaction->disb_status) || $transaction->disb_status=='FAILED')))
                                        <a class="bg_icon ms-2" data-bs-toggle="" data-bs-target="#" data-toggle="tooltip" ata-placement="top" title="" data-bs-original-title="{{__('admin.disburse')}}" aria-label="{{__('admin.disburse')}}" href="javascript:void(0);" onclick="disbusementModel({{$transaction->order_id}})"><img src="{{URL::asset('assets/icons/Disbursement.png')}}" alt="disburse"></a>
                                    @else
                                        @if(!in_array($transaction->order_id,$bulkPaymentOrderIds) && $transaction->status=='PENDING')
                                            <a class="text-dark bg_icon" data-bs-toggle="" data-bs-target="#" data-toggle="tooltip" ata-placement="top" title="" data-bs-original-title="{{ __('admin.pay_now') }}" aria-label="{{ __('admin.pay_now') }}" href="{{$transaction->invoice_url}}" target="_blank"><img src="{{URL::asset('front-assets/images/icons/credit-card.png')}}" alt="paynow" srcset=""></a>
                                            <img class="hidden" id="invoice-loader{{$transaction->order_id}}" height="16px" src="{{URL('front-assets/images/icons/timer.gif')}}" title="Loading">
                                            <a class="bg_icon ms-2" data-bs-toggle="" data-bs-target="#" data-toggle="tooltip" ata-placement="top" title="" data-bs-original-title="{{__('order.cancel_invoice')}}" aria-label="{{__('order.cancel_invoice')}}" href="javascript:void(0);" onclick="cancelInvoice($(this),'{{Crypt::encrypt($transaction->order_id)}}',{{$transaction->order_id}})"><img src="{{URL::asset('assets/icons/cancelinvoice.png')}}" alt="cancelinvoice" srcset=""></a>
                                        @elseif(!in_array($transaction->order_id,$bulkPaymentOrderIds) &&
                                                !$isBulkPayment &&
                                                ($transaction->is_credit==0 && $transaction->status=='EXPIRED' && $xenditInvoiceSettings['max_invoice_generate']>$transaction->generated_link_count))
                                            <a class="generate-pay-link bg_icon ms-2" data-bs-toggle="" data-bs-target="#" data-toggle="tooltip" ata-placement="top" title="" data-bs-original-title="{{__('admin.generate_link')}}" aria-label="{{__('admin.generate_link')}}"  data-id="{{$transaction->order_id}}" href="javascript:void(0);"><img src="{{URL::asset('assets/icons/generate_pay_link.png')}}" alt="generate_pay_link" srcset=""></a>
                                        @elseif(Auth::user()->role_id==1 && $transaction->status=='EXPIRED' && in_array($transaction->order_status,[2,8,10])){{--admin can generate link--}}
                                            <a class="generate-pay-link bg_icon ms-2" data-bs-toggle="" data-bs-target="#" data-toggle="tooltip" ata-placement="top" title="" data-bs-original-title="{{__('admin.generate_link')}}" aria-label="{{__('admin.generate_link')}}" data-id="{{$transaction->order_id}}" href="javascript:void(0);"><img src="{{URL::asset('assets/icons/generate_pay_link.png')}}" alt="generate_pay_link" srcset=""></a>
                                        @endif
                                        @if(($transaction->status=='PAID' || $transaction->status=='SETTLED') && ($transaction->order_status==5 && (empty($transaction->disb_status) || $transaction->disb_status=='FAILED')))
                                                <a class="bg_icon ms-2" data-bs-toggle="" data-bs-target="#" data-toggle="tooltip" ata-placement="top" title="" data-bs-original-title="{{__('admin.disburse')}}" aria-label="{{__('admin.disburse')}}" href="javascript:void(0);" onclick="disbusementModel({{$transaction->order_id}})"><img src="{{URL::asset('assets/icons/Disbursement.png')}}" alt="disburse"></a>
                                        @endif
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

<div class="modal version2 fade" id="staticBackdrop" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="getSingleOrderDetail">
        </div>
    </div>
</div>

<div class="modal version2 fade" id="disbursementModal" tabindex="-1" aria-labelledby="disbursementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header py-3">
                    <h5 class="modal-title" id="disbursementModalLabel">{{ __('admin.disburse') }}                    </h5>
                    <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close">
                        <img src="{{URL::asset('front-assets/images/icons/times.png')}}" alt="Close">
                    </button>
                </div>
                    <div class="modal-body" id="disbursementModalBlock">
                    <form action="{{route('settlement')}}" method="post" id="disbursementForm">
                        @csrf
                        <input type="hidden" id="order_id" name="order_id">
                        <!-- Supplier Detail -->
                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5><img height="20px" src="{{URL::asset('assets/icons/people-carry-1.png')}}" alt="Supplier Details" class="pe-2"> {{ __('admin.supplier_detail') }}</h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <div class="row rfqform_view bg-white">
                                        <div class="col-md-4 pb-2">
                                            <label>{{ __('admin.supplier_company') }}</label>
                                            <div class="text-dark" id="supplier_company_name"></div>
                                        </div>
                                        <div class="col-md-4 pb-2">
                                            <label>{{ __('admin.supplier_name') }}</label>
                                            <div class="text-dark" id="supplier_name"></div>
                                        </div>
                                        <div class="col-md-4 pb-2">
                                            <label>{{ __('admin.supplier_email') }}</label>
                                            <div class="text-dark" id="supplier_email"></div>
                                        </div>
                                        <div class="col-md-4 pb-2">
                                            <label>{{ __('admin.supplier_phone') }}</label>
                                            <div class="text-dark" id="supplier_mobile"></div>
                                        </div>
                                        <div class="col-md-4 pb-2">
                                            <label>{{ __('admin.supplier_last_paid_date') }}</label>
                                            <div id="supplier_last_paid_date"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5><img height="20px" src="{{ URL('assets/icons/bank.png') }}" alt="Supplier Details" class="pe-2"> {{ __('admin.bank_detail') }}</h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <div class="row rfqform_view bg-white">
                                        <div class="col-md-4 pb-2">
                                            <label>{{ __('admin.bank_name') }}</label>
                                            <div class="text-dark">
                                                <img src="{{ URL('assets/icons/bank.png') }}" id="bank_logo" height="20px" width="20px" alt="bank Logo">
                                                <span id="supplier_bank"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 pb-2">
                                            <label>{{ __('admin.bank_account_holder_name') }}</label>
                                            <div class="text-dark" id="bank_ac_holder_name"></div>
                                        </div>
                                        <div class="col-md-4 pb-2">
                                            <label>{{ __('admin.bank_account_number') }}</label>
                                            <div class="text-dark" id="bank_ac_number"></div>
                                        </div>
                                        <div class="col-md-4 pb-2">
                                            <label>{{ __('admin.xen_platform_id') }}</label>
                                            <div class="text-dark" id="xen_platform_id"></div>
                                        </div>
                                        <div class="col-md-4 pb-2">
                                            <label>{{ __('admin.xen_platform_balance') }}</label>
                                            <div id="xen_balance"><img height="16px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 pb-3">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0">
                                        <img height="20px" src="{{URL::asset('front-assets/images/icons/boxes.png')}}" alt="Product Details" class="pe-2" />
                                        <span> {{ __('admin.order_details') }}</span>
                                    </h5>
                                </div>
                                <div class="card-body p-3 pb-2">
                                    <div class="row rfqform_view bg-white">
                                        <div class="col-md-4 pb-2">
                                            <label class="form-label">{{ __('admin.order_number') }}</label>
                                            <div id="order-number"></div>
                                        </div>
                                        <div class="col-md-8 pb-2">
                                            <label class="form-label">{{ __('admin.payment_received_date') }}</label>
                                            <div id="payment-received-date"></div>
                                        </div>

                                        <div class="col-md-12 pb-2">
                                            <div class="table-responsive" >
                                                <table class="table">
                                                    <tbody id="multiple_product_table_show">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0">
                                        <img height="20px" src="{{URL::asset('front-assets/images/icons/credit-card-black.png')}}" alt="Charges Details" class="pe-2" />
                                        <span> {{ __('admin.charges_details') }}</span>
                                    </h5>
                                </div>
                                <div class="card-body p-3 pb-2">
                                    <div class="table-responsive">
                                        <table class="table text-dark table-striped">
                                            <tbody id="charges">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                    <div class="modal-footer">
                        <span class="badge badge-pill badge-danger p-2" id="supplierNoAccount">{{ __('admin.no_primary_account_message') }}.</span>
                        <span class="badge badge-pill badge-danger p-2" id="amount-greater">{{ sprintf(__('admin.payable_amount_greater'),getMinDisbursementAmount()) }}.</span>
                        <button type="button" id="disbursementSubmitBtn" class="btn btn-primary">{{ __('admin.submit') }}</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.close') }}</button>
                    </div>
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

        $('.generate-pay-link').click(function(e) {
            e.preventDefault();
            let orderId = $(this).attr('data-id');
            $(this).hide();
            $(this).closest('td').append('<img src="{{ URL::asset("front-assets/images/icons/timer.gif") }}" alt="loading" class="glloader" style="width: 20px;height: 20px;">');
            let that = $(this);
            $.ajax({
                url: "{{ route('generate-pay-link', '') }}" + "/" + orderId,
                type: 'GET',
                success: function(successData) {
                    if (successData.success) {
                        location.reload();
                    }else{
                        that.show();
                        $('.glloader').remove();
                        swal({
                            text: successData.message,
                            icon: "warning",
                            dangerMode: true,
                        });
                    }
                },
                error: function(error) {
                    that.show();
                    $('.glloader').remove();
                    swal({
                        text: 'Something went wrong, please try later.',
                        icon: "warning",
                        dangerMode: true,
                    });
                    console.log(error);
                }
            });
        });

        $(document).on('click','input:checkbox[readonly]',function(){
            return false;
        });

        $(document).on('click','#disbursementSubmitBtn',function (e) {
            $(this).prop('disabled',true);
            $('#disbursementForm').submit();
        })

        function setTax(tax,amount) {
            return amount+((amount*tax)/100);
        }

        function disbusementModel(orderId) {
            $('#supplier_company_name').html('');
            $('#supplier_name').html('');
            $('#supplier_email').html('');
            $('#supplier_mobile').html('');
            $('#xen_platform_id').html('');
            $('#supplier_bank').html('');
            $('#bank_ac_holder_name').html('');
            $('#bank_ac_number').html('');
            $('#bank_logo').attr('src',bank_default_logo);
            $.ajax({
                url: "{{ route('order-charges','') }}" + "/" + orderId,
                type: 'GET',
                dataType: 'json',
                success: function (successData) {
                    //console.log(successData.data);
                    if(successData.success) {
                        $('#order_id').val(orderId);
                        let order = successData.data.order;
                        //get xen ac balance
                        let supplier = successData.data.supplier;
                        let supplier_bank = successData.data.supplier_bank;
                        let supplier_transaction_fees = successData.data.supplier_transaction_fees;
                        let last_supplier_transaction_date = successData.data.last_supplier_transaction_date;
                        $('#supplier_company_name').html(supplier.name?supplier.name:'');
                        $('#supplier_name').html(supplier.contact_person_name?supplier.contact_person_name:'');
                        $('#supplier_email').html(supplier.email?supplier.email:'');
                        $('#supplier_mobile').html(supplier.contact_person_phone?supplier.contact_person_phone:'');
                        $('#xen_platform_id').html(supplier.xen_platform_id);
                        $('#supplier_last_paid_date').html(last_supplier_transaction_date);
                        let tax = parseFloat(successData.data.order.quote.tax);
                        let platform_charges = successData.data.quotes_charges.platform_charges;
                        let logistic_charges = successData.data.quotes_charges.logistic_charges;
                        if (supplier_bank){
                            let img_src = supplier_bank.bank_detail.logo?(location.origin+'/'+supplier_bank.bank_detail.logo):bank_default_logo;
                            $('#bank_logo').attr('src',img_src);
                            $('#supplier_bank').html(supplier_bank.bank_detail.name);
                            $('#bank_ac_holder_name').html(supplier_bank.bank_account_name);
                            $('#bank_ac_number').html(supplier_bank.bank_account_number);
                            $('#disbursementSubmitBtn').show();
                            $('#supplierNoAccount').hide();
                        }else{
                            $('#disbursementSubmitBtn').hide();
                            $('#supplierNoAccount').show();
                        }
                        var multiHtml = "<tr class=\"bg-light\">\n" +
                            "<th class=\"fw-bold\">{{ __('admin.product') }}</th>\n" +
                            "<th class=\"fw-bold text-center\">{{ __('admin.qty') }}</th>\n" +
                            "<th class=\"text-end fw-bold\">{{ __('admin.total_amount') }} (Rp)</th>\n" +
                            "</tr>";
                        $.each(successData.data.quoteItems, function(key,val) {
                            multiHtml += '<tr>\n' +
                                '<td><span id="product-name'+val.id+'"></span>'+val.category+' - '+val.sub_category+' - '+val.product+'</td>\n' +
                                '<td class="text-center"><div id="product-qty'+val.id+'">'+val.product_quantity+'</div></td>\n' +
                                '<td class="text-end text-nowrap"><span class="calc" id="total-amount'+val.id+'" data-is_add="1" data-amount="'+calcProductAmount(val.product_price_per_unit*val.product_quantity,tax)+'">'+calcProductAmount(val.product_price_per_unit*val.product_quantity,tax)+'</span></td>\n' +
                                '</tr>';
                        });
                        $('#order-number').html(order.order_number);
                        $('#multiple_product_table_show').html(multiHtml);
                        /*$('#product-name').html(order.category_name+' - '+order.sub_category_name+' - '+order.product_name);
                        $('#product-qty').html(order.product_quantity+' '+order.unit_name);*/
                        $('#payment-received-date').html(successData.data.payment_received_date);
                        let product_amount = parseFloat(order.product_amount);
                        if (tax){
                            product_amount = setTax(tax,product_amount);
                        }
                        $('#total-amount').html(product_amount).attr('data-amount',product_amount);
                        let platform_charge_html = '';
                        if (platform_charges.length) {
                            platform_charge_html = '<tr class="bg-light"><th colspan="2" class="fw-bold"><input class="form-check-input mr-5 mt-0" type="checkbox" checked disabled>Platform Charges</th></tr>';
                            platform_charge_html = getChargesHtml(platform_charges,platform_charge_html,tax);
                        }
                        let logistic_charge_html = '';
                        if (logistic_charges.length) {
                            logistic_charge_html = '<tr class="bg-light"><th colspan="2" class="fw-bold"><input class="form-check-input mr-5 mt-0" type="checkbox" id="log_charges">Logistic Charges</th></tr>';
                            logistic_charge_html = getChargesHtml(logistic_charges,logistic_charge_html,tax,'logistic_charges[]',0);
                        }
                        let html = platform_charge_html+logistic_charge_html;

                        let transaction_charges = successData.data.quotes_charges.transaction_charges;
                        /*if (transaction_charges<10450 || supplier_transaction_fees>0) {
                            html += '<tr class="" style="background: lightgray;font-weight: bold;">\n' +
                                '  <td class="">{{ __("admin.payable_amount")}}</td>\n' +
                                '  <td align="right" class="" id="sub-payble-amount"></td>\n' +
                                '</tr>';
                        }
                        if (transaction_charges<10450) {
                            html += '<tr class="">\n' +
                                '  <td class="">{{ __("admin.disbursment_charge_xendit")}}</td>\n' +
                                '  <td align="right" class="">-' + disbursement_charge + '</td>\n' +
                                '</tr>';
                        }
                        if (supplier_transaction_fees>0) {
                            html += '<tr class="">\n' +
                                    '  <td class="">{{ __("admin.transaction_cost_xendit")}}</td>\n' +
                                    '  <td align="right" class="" id="supplier-charge" data-charge="'+supplier_transaction_fees+'">-'+supplier_transaction_fees+'</td>\n' +
                                    '</tr>';
                        }*/
                        html +='<tr class="grey_tab text-white">\n' +
                                    '<td class="text-white">{{ __("admin.total_payable_amount")}}</td>\n' +
                                    '<td align="right" class="text-white" id="payble-amount"></td>\n' +
                                '</tr>'
                        $('#charges').html(html);
                        calc(supplier_bank,transaction_charges);
                        $('#disbursementModal').modal('show');
                        getXenBalance(order.supplier_id);
                    }
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
        function getChargesHtml(data,html='',tax=0,input_name='',is_disabled=1) {
            let class_name = is_disabled?'':'add_remove_charge data-exclude';
            $.each(data, function(index, value) {
                console.log(value);
                html += '<tr class="'+(is_disabled?'':'strikethrough')+'">\n' +
                            '<td>\n' +
                                '<div class="form-check ms-5 my-0">\n' +
                                '<input class="form-check-input calc '+class_name+'" readonly type="checkbox" data-is_add="'+value.addition_substraction+'" data-amount="'+setTax(tax,value.charge_amount)+'" id="flexCheckChecked'+value.id+''+index+'" name="'+input_name+'" value="'+value.id+'" '+(is_disabled?'disabled checked':'')+'>\n' +
                                '<label class="form-check-label" for="flexCheckChecked'+value.id+''+index+'">'+value.charge_name+'</label>\n' +
                                '</div>\n' +
                            '</td>\n' +
                            '<td align="right ">\n' +(value.addition_substraction?'+':'-')+setTax(tax,value.charge_amount)+ '</td>\n' +
                        '</tr>'
            });
            return html;
        }
        $(document).on('click','#log_charges',function(){
            $(".add_remove_charge").prop('checked', $(this).prop('checked'));
            strikethrough($(this).prop('checked'));
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
        function calc(supplier_bank='',transaction_charges=0) {
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
            /*if (transaction_charges<10450) {
                tot = tot - disbursement_charge;
            }
            let supplierCharge = $('#supplier-charge').attr('data-charge');
            if (supplierCharge!=undefined && supplierCharge!=0) {
                tot = tot - supplierCharge;
            }*/
            $('#amount-greater').hide();
            if (supplier_bank) {
                $('#disbursementSubmitBtn').show();
                if (tot < 10000) {
                    $('#amount-greater').show();
                    $('#disbursementSubmitBtn').hide();
                }
            }
            $('#payble-amount').html(Math.round(tot));
        }
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
    </script>
@endpush

