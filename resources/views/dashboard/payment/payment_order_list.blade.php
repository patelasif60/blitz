@php
    $ordersCount = count($supplier->orders);
    $supplierId = $supplier->supplier_id;
@endphp

<div class="accordion-body bg-light-gray p-1">
    <div class="row">
        <div class="col-lg-12 mainorderdetails d-flex align-content-stretch flex-wrap">
            <div id="pd_order_list{{$supplierId}}" class="card p-3 radius w-100" style="flex-shrink: 0;">
                <div class="card-body p-0">
                    <div class="row paymentform_view rfqform_view g-2">
                        <div class="col-md-12 d-flex">
                            <h5 class="dark_blue mb-0"><img src="{{ URL::asset('front-assets/images/icons/credit-card.png')}}" alt=""> {{ __('rfqs.payment_details') }}</h5>
                            @if($ordersCount)
                            <div class="ms-auto">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input pd-select-all" id="pd_select_all{{$supplierId}}" onclick="pd_select_all({{$supplierId}},$(this))">
                                    <label class="form-check-label"
                                           for="pd_select_all{{$supplierId}}">{{ __('order.select_all') }}</label>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="col-md-12">
                            @if(isset($bulkSupplierPayments[$supplierId]))
                                @foreach($bulkSupplierPayments[$supplierId] as $bulkPaymentId=>$bulkOrdersPayment)
                                    <table class="table border bg-light mb-2 bp_bs_payment{{$supplierId}}" data-amount="{{$bulkOrdersPayment['created_bulk_orders']->sum('payment_amount')}}">
                                        @foreach($bulkOrdersPayment['created_bulk_orders'] as $i=>$order)
                                            <tr>
                                                <td class="align-bottom text-center">
                                                    <input type="checkbox" class="form-check-input" checked disabled>
                                                </td>
                                                <td>
                                                    <label>{{ __('order.order_number') }}:</label>
                                                    <div>{{$order->order_number}}</div>
                                                </td>
                                                <td>
                                                    <label>{{ __('rfqs.product') }}:</label>
                                                    <div>
                                                        @php
                                                            $quoterItemsCount = $order->quote->quoteItems()->count();
                                                        @endphp
                                                        @if($quoterItemsCount==1)
                                                            @php
                                                                $quoteItem = $order->quote->quoteItem()->first(['rfq_product_id'])
                                                            @endphp
                                                            {{ get_product_name_by_id($quoteItem->rfq_product_id,1) }}
                                                        @else
                                                            {{$quoterItemsCount.' '.__('admin.products')}}
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <label>{{ __('dashboard.Quantity') }}:</label>
                                                    <div>{{$order->product_quantity . ' ' . $order->unit_name}}</div>
                                                </td>
                                                <td>
                                                    <label>{{ __('rfqs.payment_terms') }}:</label>
                                                    <div>
                                                        @if($order->is_credit)
                                                            <span class="badge rounded-pill bg-danger">{{ sprintf(__('rfqs.credit_due_on'),changeDateFormat($order->payment_due_date,'d/m/Y')) }}</span>
                                                        @else
                                                            <span class="badge rounded-pill bg-primary">{{ __('rfqs.cash') }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <label>{{ __('order.amount') }}:</label>
                                                    <div>{{ 'Rp ' . number_format($order->payment_amount, 2) }}</div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="mb-4 text-end">
                                        <a href="javascript:void(0);" class="btn btn-info border px-2 py-1 printmenu ms-2 cancel_payment{{$bulkPaymentId}}" onclick="cancel_payment({{$supplierId}},{{$bulkPaymentId}})" style="font-size: 12px;">
                                            <img src="{{ URL::asset('front-assets/images/icons/icon_cancel_1.png')}}" alt="Cancel" class="pe-1" style="max-height: 12px;">
                                            {{ __('order.cancel_payment') }}</a>
                                        <a href="{{$bulkOrdersPayment['invoice_url']}}" target="_blank" class="btn btn-primary px-2 py-1 printmenu ms-2 continue_pay{{$bulkPaymentId}}" style="font-size: 12px;">
                                            <img src="{{ URL::asset('front-assets/images/icons/icon_paynow.png')}}" alt="Pay" class="pe-1" style="max-height: 12px;">
                                            {{ __('order.continue_to_pay') }}</a>
                                    </div>
                                @endforeach
                            @endif

                            @if($ordersCount)
                            <table class="table border">
                                @foreach($supplier->orders as $i=>$order)
                                    <tr>
                                        <td class="align-bottom text-center">
                                            <input type="checkbox" class="form-check-input pd-order-check" onclick="pd_order_check({{$supplierId}},$(this))" name="order_ids[{{$supplierId}}][{{$i}}]" value="{{$order->id}}">
                                        </td>
                                        <td>
                                            <label>{{ __('order.order_number') }}:</label>
                                            <div>{{$order->order_number}}</div>
                                        </td>
                                        <td>
                                            <label>{{ __('rfqs.product') }}:</label>
                                            <div>
                                                @php
                                                    $quoterItemsCount = $order->quote->quoteItems()->count();
                                                @endphp
                                                @if($quoterItemsCount==1)
                                                    @php
                                                        $quoteItem = $order->quote->quoteItem()->first(['rfq_product_id'])
                                                    @endphp
                                                    {{ get_product_name_by_id($quoteItem->rfq_product_id,1) }}
                                                @else
                                                    {{$quoterItemsCount.' '.__('admin.products')}}
                                                @endif
                                            </div>
                                        </td>
                                        {{--<td>
                                            <label>{{ __('dashboard.Quantity') }}:</label>
                                            <div>{{$quoteItem->product_quantity . ' ' . get_unit_name($quoteItem->price_unit)}}</div>
                                        </td>--}}
                                        <td>
                                            <label>{{ __('rfqs.payment_terms') }}:</label>
                                            <div>
                                                @if($order->is_credit)
                                                    <span class="badge rounded-pill bg-danger">{{ sprintf(__('rfqs.credit_due_on'),changeDateFormat($order->payment_due_date,'d/m/Y')) }}</span>
                                                @else
                                                    <span class="badge rounded-pill bg-primary">{{ __('rfqs.cash') }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <label>{{ __('order.amount') }}:</label>
                                            <div>{{ 'Rp ' . number_format($order->payment_amount, 2) }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            @endif
                        </div>
                        @if($ordersCount)
                        <div class="col-md-12 text-end mt-0">
                            <button id="pd_proceed_to_checkout{{$supplierId}}" onclick="pd_checkout({{$supplierId}})" type="button" class="btn btn-warning px-3 py-1 printmenu ms-2 " style="font-size: 12px;" disabled>
                                <img src="{{ URL::asset('front-assets/images/icons/icon_credit_request.png') }}" alt="Pay Now" class="pe-1" style="max-height: 12px;">
                                {{ __('order.proceed_to_checkout') }}
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div id="pd_checkout{{$supplierId}}" class="card radius_1 w-100 p-3 d-none" style="flex-shrink: 0;">
                <div class="card-body  p-0">
                    <div class="row paymentform_view rfqform_view g-2">
                        <div class="col-md-12 d-flex">
                            <h5 class="dark_blue mb-0"><img
                                    src="{{ URL::asset('front-assets/images/icons/credit-card.png') }}" alt=""> {{ __('rfqs.payment_details') }}</h5>
                        </div>
                        <div class="col-md-12">
                            <table class="table mb-0 border">
                                <thead class="table-light h-100">
                                <tr>
                                    <th>{{ __('order.order_number') }}</th>
                                    <th>{{ __('rfqs.product') }}</th>
                                    {{--<th width="20%" class="text-center text-nowrap">{{ __('rfqs.QTY') }}</th>--}}
                                    <th width="20%" class="text-end text-nowrap">{{ __('order.amount') }}</th>
                                </tr>
                                </thead>
                                <tbody id="pd_transaction_charge{{$supplierId}}" data-transaction_charge="{{$transactionsCharges??0}}">
                                @foreach($supplier->orders as $i=>$order)
                                    <tr id="pd_order{{$order->id}}" class="d-none pd_calc" data-amount="{{ $order->payment_amount }}" data-transaction_charge="{{ isset($order->quote->quoteChargesWithAmounts) && !empty($order->quote->quoteChargesWithAmounts) ? $order->quote->quoteChargesWithAmounts->where('charge_id', \App\Models\OtherCharge::XENDIT)->pluck('charge_amount')->first() : 0 }}">
                                        <td class="text-start text-nowrap align-middle">{{$order->order_number}}</td>
                                        <td class="align-middle">
                                            @php
                                                $quoterItemsCount = $order->quote->quoteItems()->count();
                                            @endphp
                                            @if($quoterItemsCount==1)
                                                @php
                                                    $quoteItem = $order->quote->quoteItem()->first(['rfq_product_id'])
                                                @endphp
                                                {{ get_product_name_by_id($quoteItem->rfq_product_id,1) }}
                                            @else
                                                {{$quoterItemsCount.' '.__('admin.products')}}
                                            @endif
                                        </td>
                                        {{--<td class="text-center text-nowrap  align-middle">{{$order->product_quantity . ' ' . $order->unit_name}}</td>--}}
                                        <td class="text-end text-nowrap align-middle">{{ 'Rp ' . number_format($order->payment_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr class="bg-light">
                                    <td colspan="2" class="fw-bold text-end">{{ __('order.sub_total') }}</td>
                                    <td class="text-end text-nowrap fw-bold align-middle">
                                        <span class="text-end" id="pd-subtotal-amount{{$supplierId}}"></span>
                                    </td>
                                </tr>
                                <tr class="bg-white">
                                    <td colspan="2" class="fw-bold text-end text-danger">{{ __('order.discount_amount') }}</td>
                                    <td class="text-end text-nowrap fw-bold align-middle">
                                        <span class="text-end text-danger"> <strike id="pd-discount-amount{{$supplierId}}">Rp 0</strike></span>
                                    </td>
                                </tr>
                                <tr style="background-color:#dbdee1;">
                                    <td colspan="2" class="fw-bold text-end">{{ __('admin.total_amount')}}</td>
                                    <td class="text-end text-nowrap fw-bold align-middle">
                                        <span class="text-end" id="pd-total-amount{{$supplierId}}">Rp 0</span>
                                    </td>
                                </tr>

                                </tfoot>
                            </table>
                            <div class="pt-2 text-end">

                                <a href="javascript:void(0);" onclick="pd_checkout({{$supplierId}})" class="btn btn-info border px-2 py-1 printmenu ms-2" style="font-size: 12px;">
                                    <img src="{{ URL::asset('front-assets/images/icons/icon_cancel_1.png')}}" alt="Cancel" class="pe-1" style="max-height: 12px;">
                                    {{ __('admin.cancel') }}</a>
                                <a href="javascript:void(0);" onclick="pd_pay_now({{$supplierId}})" class="btn btn-primary px-3 py-1 printmenu ms-2" style="font-size: 12px;">
                                    <img src="{{ URL::asset('front-assets/images/icons/icon_paynow.png')}}" alt="Pay Now" class="pe-1" style="max-height: 12px;">{{ __('admin.pay_now') }}</a>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
