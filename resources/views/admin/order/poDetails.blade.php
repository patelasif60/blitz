<div class="rfq_detail" >
    <div class="row" >
        <div class="col-md-12 pb-2">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0">
                        <img src="{{ URL::asset('assets/icons/comment-alt-edit.png') }}" alt="Order Details"
                             class="pe-2">
                        <span>{{ __('admin.order_details') }}: </span>
                    </h5>
                </div>
                <div class="card-body p-3 pb-1">
                    <div class="bg-white d-flex">
                        <div class="col-md-{{isset($order->order_number) ? 3 : 4}} pb-2">
                            <label>{{ __('admin.order_number') }}:</label>
                            <div>{{ $order->order_number }}</div>
                        </div>
                        <div class="col-md-{{isset($order->quote_number) ? 3 : 4}} pb-2 ps-3">
                            <label>{{ __('admin.quote_number') }}:</label>
                            <div>{{ $order->quote_number }}</div>
                        </div>
                        <div class="col-md-{{isset($order->created_at) ? 3 : 4}} pb-2 ps-3">
                            <label>{{ __('admin.date') }}:</label>
                            <div class="text-dark">{{ date('d-m-Y H:i', strtotime($order->created_at)) }}</div>
                        </div>
                        @if(isset($order->customer_reference_id) && !empty($order->customer_reference_id))
                            <div class="col-md-{{isset($order->customer_reference_id) ? 3 : 4}}">
                                <label class="form-label">{{ __('rfqs.customer_ref_id') }}: </label>
                                <div class="text-dark"> {{ isset($order) ? $order->customer_reference_id : '' }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 pb-2">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0">
                        <img src="{{ URL::asset('assets/icons/people-carry-1.png') }}" alt="Order Details"
                             class="pe-2">
                        <span>{{ __('admin.supplier_detail') }}: </span>
                    </h5>
                </div>
                <div class="card-body p-3 pb-1">
                    <div class="bg-white d-flex">
                        <div class="col-md-3 pb-2">
                            <label>{{ __('admin.supplier_company') }}:</label>
                            <div>{{ $order->supplier_company }}</div>
                        </div>
                        <div class="col-md-3 pb-2 ps-3">
                            <label>{{ __('admin.supplier_name') }}:</label>
                            <div>{{ $order->supplier_name }}</div>
                        </div>
                        <div class="col-md-3 pb-2 ps-3">
                            <label>{{ __('admin.supplier_email') }}:</label>
                            <div>{{ $order->supplier_email }}</div>
                        </div>
                        <div class="col-md-3 pb-2 ps-3">
                            <label>{{ __('admin.supplier_phone') }}:</label>
                            <div>{{ countryCodeFormat($order->supplier_phone_code, $order->supplier_phone) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 pb-2">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0">
                        <img src="{{ URL::asset('assets/icons/boxes.png') }}" alt="Product Details"
                             class="pe-2">
                        <span>{{ __('admin.product_details') }}: </span>
                    </h5>
                </div>
                @php
                    $finalAmount = 0;
                    $discount = 0;
                    $quote_items = \App\Models\QuoteItem::where('quote_id', $order->quotes_id)->get();;
                @endphp
                <div class="table-responsive p-3">
                    <table class="table text-dark ">
                        <tr class="bg-light" style="color: black;">
                            <th>{{ __('admin.description') }}</th>
                            <th>{{ __('admin.price') }}</th>
                            <th>{{ __('admin.qty') }}</th>
                            <th align="right" class="text-end">{{ __('admin.amount') }}</th>
                        </tr>
                        @foreach($quote_items as $key => $value)
                            @php
                                $finalAmount += $value->product_price_per_unit * $value->product_quantity ;
                            @endphp
                            <tr>
                                <td>{{ get_product_name_by_id($value->rfq_product_id, 1) }}</td>
                                <td>{{ 'Rp ' . number_format($value->product_price_per_unit, 2) }} per {{ $order->unit_name }}</td>
                                <td>{{ $value->product_quantity??'' }} {{ $quote->unit_name??'' }}</td>
                                <td align="right">{{ 'Rp ' . number_format($value->product_amount, 2) }}</td>
                            </tr>
                        @endforeach
                        @foreach ($quotes_charges_with_amounts as $charges)
                            <tr>
                                @if ($charges->type == 0)
                                    <td colspan="3">@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif {{ $charges->charge_name . ' ' . $charges->charge_value }} %</td>
                                @else
                                    <td colspan="3">@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif{{ $charges->charge_name }}</td>
                                @endif
                                @php
                                    if ($charges->charge_name != 'Discount') {
                                        if ($charges->addition_substraction == 0) {
                                            $finalAmount = $finalAmount - $charges->charge_amount;
                                        } else {
                                            $finalAmount = $finalAmount + $charges->charge_amount;
                                        }
                                    } else {
                                        $discount = $charges->charge_amount;
                                    }
                                @endphp
                                <td align="right">
                                    {{ $charges->addition_substraction == 0 ? '- ' : '+ ' }}{{ 'Rp ' . number_format($charges->charge_amount, 2) }}
                                </td>
                            </tr>
                        @endforeach
                        @php
                            $totalAmount = $finalAmount - $discount;
                            $taxamount = ($totalAmount * $order->tax) / 100;
                        @endphp
                        <tr>
                            <td colspan="3">{{ __('admin.total') }}</td>
                            <td align="right">{{ 'Rp ' . number_format($totalAmount, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3">{{ __('admin.tax') }} {{ $order->tax . '%' }}</td>
                            <td align="right"> + {{ 'Rp ' . number_format($taxamount, 2) }}</td>
                        </tr>
                        <tr class="bg-secondary">
                            <td colspan="3" style="color: white;">{{ __('admin.amount_to_pay') }}</td>
                            <td align="right" style="color: white;">
                                {{ 'Rp ' . number_format($totalAmount + $taxamount, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="border-0">
                                <small>{{ __('admin.deliver_order_in') }} {{ $order->min_delivery_days }} {{ __('admin.to') }}
                                    {{ $order->max_delivery_days }} {{ __('admin.days') }}<span class="text-danger">*</span></small>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="border-0">
                                <b>{{ __('admin.note') }}: {{ $order->note }}</b><br /><br />

                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="border-0">
                                <p>
                                    <strong for="comment">{{ __('admin.comments') }}</strong><br />
                                    <textarea class="form-control" name="comment" id="comment" cols="60" rows="3"
                                    placeholder="Enter your comment"></textarea>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <!-- Show Approvers List -->
        @if(isset($approversList) && count($approversList) > 0)
        <div class="col-md-12 pb-2">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0">
                        <img src="{{ URL::asset('front-assets/images/icons/approve.png') }}" alt="Approver Details" class="pe-2">
                        <span>{{ __('admin.approver_details') }} : </span>
                    </h5>
                </div>
                <div class="card-body p-3 pb-1">
                    <div class="bg-white row d-flex mb-3">
                        @foreach($approversList as $approver)
                        @php
                            if(isset($approver->feedback) && $approver->feedback == 0) {
                                $feedback = URL::asset('front-assets/images/pending.png');
                            } elseif ($approver->feedback == 1) {
                                $feedback = URL::asset('front-assets/images/thumbs-up.png');
                            } else {
                                $feedback = URL::asset('front-assets/images/thumbs-down.png');
                            }
                        @endphp
                        <div class="col-md-6" style="font-size: 14px;"><img src="{{ $feedback }}" height="14" width="14" alt="Thumbs-up" srcset="">
                        <strong>{{ $approver->firstname . ' ' . $approver->lastname }}</strong> ({{ $approver->name }})
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
            <button data-id="{{ $order->order_id }}"
                class="btn btn-primary sendPoToSupplier border-0">{{ __('admin.generate_po') }}</button>
        </div> --}}
    </div>
</div>
{{-- <div class="col-md-6">
    <div class="detail_1">
        <p><strong>{{ __('admin.order_number') }}: </strong>{{ $order->order_number }}</p>
        <p><strong>{{ __('admin.quote_number') }}: </strong>{{ $order->quote_number }}</p>
        <p><strong>{{ __('admin.date') }}: </strong>{{ date('d-m-Y H:i', strtotime($order->created_at)) }}</p>
    </div>
</div>
<div class="col-md-6">
    <div class="detail_1">
        <p><strong>{{ __('admin.supplier_company') }}: </strong>{{ $order->supplier_company }}</p>
        <p><strong>{{ __('admin.supplier_name') }}: </strong>{{ $order->supplier_name }}</p>
        <p><strong>{{ __('admin.supplier_email') }}: </strong>{{ $order->supplier_email }}</p>
        <p><strong>{{ __('admin.supplier_phone') }}: </strong>{{ $order->supplier_phone }}</p>
    </div>
</div> --}}

