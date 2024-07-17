<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$loanApply->loan_number}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
    </div>
    <div class="modal-body p-2">
        <div class="row rfqform_view g-3 mb-3 mx-1">
            <div class="col-md-4">
                <label>Loan ID:</label>
                <div>{{$loanApply->loan_number}}</div>
            </div>
            <div class="col-md-4">
                <label>Order Number:</label>
                <div>{{$loanApply->orders->order_number}}</div>
            </div>
            <div class="col-md-4">
                <label>Amount Paid:</label>
                <div>{{$loanApply->paid_amount!=''?'Rp '.number_format($loanApply->paid_amount,2):''}}</div>
            </div>
            <div class="col-md-4">
                <label>Paid Date:</label>
                <div class="text-capitalized">{{$paid_date==''?'':$paid_date}}</div>
            </div>
        </div>
        <div class="row floatlables mx-1">

            <table class="table  table-bordered">

                <tbody>
                <tr class="bg-light">
                    <th>{{__('admin.item_number')}}</th>
                    <th>{{__('admin.description')}}</th>
                    <th>{{__('admin.price')}}</th>
                    <th>{{__('admin.qty')}}</th>
                    <th align="right" class="text-end">{{__('admin.amount')}}</th>
                </tr>
                {{--start items--}}
                @foreach ($loanApply->orderItems as $orderItems)
                    @php
                        $unit = get_unit_name($orderItems->quoteItem->price_unit);
                    @endphp
                    <tr>
                        <td>{{$orderItems->order_item_number}}</td>
                        <td>{{ get_product_name_by_id($orderItems->rfq_product_id,1) }}</td>
                        <td>Rp {{number_format($orderItems->quoteItem->product_price_per_unit,2)}} per {{$unit}}</td>
                        <td>{{$orderItems->quoteItem->product_quantity}} {{$unit}}</td>
                        <td align="right">Rp {{number_format($orderItems->quoteItem->product_amount,2)}}</td>
                    </tr>
                @endforeach

                @foreach ($amountDetails as $charges)
                    <tr>
                        @if ($charges->type == 0)
                            <td colspan="4">{{ $charges->charge_name . ' ' . $charges->charge_value." %" }}</td>
                        @else
                            <td colspan="4">{{ $charges->charge_name }}</td>
                        @endif
                        <td align="right">
                            {{ $charges->addition_substraction == 0 ? '- ' : '+ ' }}{{ 'Rp ' . number_format($charges->charge_amount, 2) }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4">{{ __('admin.tax') }} {{ $quote->tax .'%'}}</td>
                    <td align="right">+ Rp {{number_format($quote->tax_value, 2)}}</td>
                </tr>
                <tr class="bg-secondary text-white">
                    <td colspan="4" class="text-white fw-bold">{{__('admin.total_amount')}}</td>
                    <td align="right" class="text-white fw-bold">Rp {{number_format($quote->final_amount, 2)}}</td>
                </tr>
                <tr>
                    <td colspan="4">2% Interest for 30 Days</td>
                    <td align="right">+ Rp {{number_format($loanIntrestCal['interest_amount'],2)}}</td>
                </tr>
                <tr>
                    <td colspan="4">{{__('admin.repayment_charges')}}</td>
                    <td align="right">+ Rp {{number_format($loanIntrestCal['repayment_charges'],2)}}</td>
                </tr>
                @if($loanIntrestCal['internal_transfer_charge_count'])
                    <tr>
                        <td colspan="4">{{$loanIntrestCal['internal_transfer_charge_count'] .' '. __('admin.internal_transfer_charge')}}</td>
                        <td align="right">+ Rp {{number_format($loanIntrestCal['total_internal_transfer_charge'],2)}}</td>
                    </tr>
                @endif
                @if($loanIntrestCal['origination_charge_count'])
                    <tr>
                        <td colspan="4">{{$loanIntrestCal['origination_charge_count'] .' '. __('admin.origination_charge')}}</td>
                        <td align="right">+ Rp {{number_format($loanIntrestCal['total_origination_charge'],2)}}</td>
                    </tr>
                @endif
                @if($loanIntrestCal['vat'])
                    <tr>
                        <td colspan="4">{{$loanIntrestCal['vat'] .'% '. __('admin.vat')}}</td>
                        <td align="right">+ Rp {{number_format($loanIntrestCal['total_vat'],2)}}</td>
                    </tr>
                @endif
                @if($loanIntrestCal['late_fee_count'])
                    <tr>
                        <td colspan="4">{{$loanIntrestCal['late_fee_count'] .' '. __('admin.late_fee')}}</td>
                        <td align="right">+ Rp {{number_format($loanIntrestCal['total_late_fee'],2)}}</td>
                    </tr>
                @endif
                <tr class="bg-secondary text-white">
                    <td colspan="4" class="text-white fw-bold">{{__('admin.payable_amount')}}</td>
                    <td align="right" class="text-white fw-bold">Rp {{number_format($loanIntrestCal['payable_amount'],2)}}</td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>
