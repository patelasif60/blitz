<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <title>Blitznet Invoice</title>

</head>

<body>
    @php
        $rfq = $order->rfq()->first();
        $quote = $order->quote()->first();
        $buyer = $order->user()->first();
        $buyer_company = $order->companyDetails()->first();
        $supplier = $order->supplier()->first();
        $buyerRefundAmount = getBuyerRefundAmountByOrder($order->group_id,$order->id,$order->user_id);
        $orderPo = $order->orderPo()->first();
        $orderCreditDay = $order->orderCreditDay()->first();
        $orderItems = $order->orderItems()->get();
        $finalAmount = [];
    @endphp
    <table width="100%" cellpadding="5" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif;">
        <tr>
            <td align="center" style="background-color: #002050; color: #fff;">
                <h3 style="margin-block-start: .8em; margin-block-end: .8em;">{{ __('order.purchase_order') }}</h3>
            </td>

        </tr>
    </table>

    <table width="100%" cellpadding="5" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif;">
        <tr>
            <td>
                <table width="100%" cellpadding="5" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif;">
                    <tr>
                        <td width="50%">
                            {{--<img src="https://www.blitznet.co.id/front-assets/images/logo-bg.png" alt="" height="40" width="120">--}}
                            @if(!empty($buyer_company->logo))
                                <img src="{{ public_path('storage/' . $buyer_company->logo) }}" alt="" height="40" width="auto">
                            @else
                                <span style="background-color: #0D6EFD;font-size: 1.5rem;font-family:Arial, Helvetica, sans-serif;color: #fff; padding: 10px; line-height: 20px; display: inline-block;">{{ genrateComanyShortName($buyer_company->name)  }}</span>
                            @endif
                        </td>
                        <td width="50%">
                            @if(isset($order->customer_reference_id) && !empty($order->customer_reference_id))
                                <div class="col-md-4 pb-2">
                                    <strong>{{ __('rfqs.customer_ref_id') }}: </strong>
                                    <div class="text-dark">{{ $order->customer_reference_id }}</div>
                                </div>
                            @endif
                            <strong>Blitznet {{ __('admin.po_number') }}:</strong> {{ $orderPo->po_number }}<br>
                            <strong>{{ __('admin.po_order_number') }}:</strong> {{ $order->order_number }}<br>
                            @if(isset($order->group_id) && $order->group_id)
                            <strong>{{ __('admin.group_number') }}:</strong>BGRP-{{ $order->group_id }} <br>
                            @endif
                            <strong>{{ __('admin.date') }}:</strong> {{ date('d-m-Y', strtotime($orderPo->created_at)) }}<br>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" valign="top">
                            <table width="90%" cellpadding="5">
                                <tr>
                                    <td style="border-bottom: 2px solid #000000;">{{ __('admin.company_info') }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><strong>{{ __('admin.buyer_company_info') }}: </strong>{{ $buyer_company->name }}</p>
                                        <p><strong>{{ __('admin.buyer_name') }}:</strong> {{ $buyer->firstname .' '. $buyer->lastname }}</p>
                                        <p><strong>{{ __('admin.phone_number') }}:</strong> {{ countryCodeFormat($rfq->phone_code,$rfq->mobile) }}</p>
                                        <p><strong>{{ __('signup.email') }}:</strong> {{ $buyer->email }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="50%" valign="top">
                            <table width="90%" cellpadding="5">
                                <tr>
                                    <td style="border-bottom: 2px solid #000000;">{{ __('admin.order_to') }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><strong>{{ __('order.company') }}:</strong> {{ $supplier->name }}</p>
                                        <p><strong>{{ __('admin.name') }}:</strong> {{ $supplier->contact_person_name.' '.$supplier->contact_person_last_name }}</p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr>
            <td>
                <table width="100%" cellpadding="5" cellspecing="0" border="0">
                    <thead>
                        <tr>
                            <th width="23%" style="background-color: #002050; color: #fff; font-size: 12px;">{{ __('admin.product') }}</th>
                            <th width="10%" style="background-color: #002050; color: #fff; font-size: 12px;">{{ __('admin.quantity') }}</th>
                            <th width="25%" style="background-color: #002050; color: #fff; font-size: 12px;">{{ __('admin.price') }}</th>
                            <th width="10%" style="background-color: #002050; color: #fff; font-size: 12px;">{{ __('admin.discount') }}</th>
                            <th width="10%" style="background-color: #002050; color: #fff; font-size: 12px;">{{ __('admin.tax') }}</th>
                            <th width="22%" style="background-color: #002050; color: #fff; font-size: 12px;">{{ __('admin.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($orderItems as $orderItem)
                        @php
                            $quoteItem = $orderItem->quoteItem()->first();
                            $unit = get_unit_name($quoteItem->price_unit);
                            $finalAmount[] = $quoteItem->product_price_per_unit * $quoteItem->product_quantity;
                        @endphp
                        <tr>
                            <td style="background-color: #F8F9FA; font-size: 12px;">{{ get_product_name_by_id($quoteItem->rfq_product_id, 1) }}</td>
                            <td style="background-color: #F8F9FA; text-align: center; font-size:12px;">{{ $quoteItem->product_quantity??'' }} {{ $unit??'' }}</td>
                            <td style="background-color: #F8F9FA; text-align: center; font-size:12px; white-space: nowrap;">{{ 'Rp ' . number_format($quoteItem->product_price_per_unit, 2) }} per {{ $unit }}</td>
                            <td style="background-color: #F8F9FA; text-align: center; font-size:12px;"> - </td>
                            <td style="background-color: #F8F9FA; text-align: center; font-size:12px;"> - </td>
                            <td style="background-color: #F8F9FA; text-align: right; font-size:12px; white-space: nowrap;">{{ 'Rp ' . number_format($quoteItem->product_amount, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #e6e4e4; font-size: 12px;">
                                <strong>Sub Total</strong>
                            </td>
                            <td style="text-align: right; border-top: 1px solid #000;border-bottom: 1px solid #e6e4e4; font-size: 12px; white-space: nowrap;">{{ 'Rp ' . number_format(array_sum($finalAmount), 2) }}</td>
                        </tr>

                        @foreach ($quotes_charges_with_amounts as $charges)
                            <tr>
                                @if ($charges->type == 0)
                                    <td colspan="5" style="text-align: right; border-bottom: 1px solid #e6e4e4; font-size: 12px;">
                                        @if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif {{ $charges->charge_name . ' ' . $charges->charge_value }}
                                        %
                                    </td>
                                @else
                                    <td colspan="5" style="text-align: right; border-bottom: 1px solid #e6e4e4; font-size: 12px;">
                                        @if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif {{ $charges->charge_name }}
                                    </td>
                                @endif
                                    <td style="text-align: right;border-bottom: 1px solid #e6e4e4; font-size: 12px; white-space: nowrap;">
                                    {{ $charges->addition_substraction == 0 ? '- ' : '+ ' }}{{ 'Rp ' . number_format($charges->charge_amount, 2) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" style="text-align: right; border-bottom: 1px solid #e6e4e4; font-size: 12px;">{{ __('admin.tax') }} {{ $quote->tax .'%'}}</td>
                            <td align="right" style="text-align: right; border-bottom: 1px solid #e6e4e4; font-size: 12px;">
                                + Rp
                                @if($user_role == 3)
                                    {{ number_format($quote->supplier_tex_value, 2) }}
                                @else
                                    {{number_format($quote->tax_value, 2)}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: right;border-bottom: 1px solid #e6e4e4; font-size: 12px;">
                                <strong>{{ __('admin.total') }}</strong>
                            </td>
                            <td style="text-align: right;border-bottom: 1px solid #e6e4e4; font-size: 12px; white-space: nowrap;">Rp
                                @if($user_role == 3)
                                    {{number_format($quote->supplier_final_amount, 2)}}
                                @else
                                    {{number_format($quote->final_amount, 2)}}
                                @endif
                            </td>
                        </tr>
                        @php
                            $billedAmount = $quote->final_amount;
                            $bulkOrderDiscount = 0;
                            if ($user_role != 3){
                               $bulkOrderDiscount = getBulkOrderDiscount($order->id);
                            }
                        @endphp
                        @if($bulkOrderDiscount>0)
                            @php
                                $billedAmount = $billedAmount-$bulkOrderDiscount;
                            @endphp
                            <tr>
                                <td colspan="5" style="text-align: right;border-bottom: 1px solid #e6e4e4; font-size: 12px;">
                                    {{ __('admin.bulk_payment_discount') }}
                                </td>
                                <td style="text-align: right;border-bottom: 1px solid #e6e4e4; font-size: 12px; white-space: nowrap;">
                                    {{ '- Rp ' . number_format($bulkOrderDiscount, 2) }}
                                </td>
                            </tr>
                        @endif
                        @if($buyerRefundAmount>0)
                            @php
                                $billedAmount = $billedAmount-$buyerRefundAmount;
                            @endphp
                            <tr>
                                <td colspan="5" style="text-align: right;border-bottom: 1px solid #e6e4e4; font-size: 12px;">
                                    {{ __('admin.group_refund') }}
                                </td>
                                <td style="text-align: right;border-bottom: 1px solid #e6e4e4; font-size: 12px; white-space: nowrap;">
                                    {{ '- Rp ' . number_format($buyerRefundAmount, 2) }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="5" style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #000; background-color: #f4f4f4; font-size: 12px;">
                                <strong>{{ __('admin.billed_amount') }}</strong>
                            </td>
                            <td style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #000;  background-color: #f4f4f4; font-size: 12px; white-space: nowrap;">
                                <strong>{{ 'Rp ' . number_format(floatval($billedAmount), 2) }}</strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif;">
                    <tr>
                        <td width="70%" valign="top">
                            <p><strong>{{ __('admin.note') }}:</strong></p>
                            <p>{{ $quote->note }}</p>
                            @if(isset($orderPo->comment) && !empty($orderPo->comment))
                            <p><strong>{{ __('admin.comments') }}:</strong></p>
                                <p>{{ $orderPo->comment }}</p>
                            @endif
                        </td>
                        @if(isset($approversList) && count($approversList) > 0)
                        <td width="30%" valign="bottom">
                            <table border="1" cellspacing="0" cellpadding="5">
                                <thead style="background-color: #d7d9df;">
                                    <tr style="font-size: 12px;">
                                        <th class="text-center" colspan="2">{{ __('rfqs.approved_by') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($approversList as $approver)
                                    <tr>
                                        <td width="200px">
                                            <div style="font-size: 10px;"><strong>{{ $approver->firstname . ' ' . $approver->lastname }}</strong> ({{ $approver->name }})</div>
                                        </td>
                                        @php
                                            if(isset($approver->feedback) && $approver->feedback == 0) {
                                                $feedback = public_path('front-assets/images/pending.png');
                                            } elseif ($approver->feedback == 1) {
                                                $feedback = public_path('front-assets/images/thumbs-up.png');
                                            } else {
                                                $feedback = public_path('front-assets/images/thumbs-down.png');
                                            }
                                        @endphp
                                        <td width="16px">
                                            <div><img src="{{ public_path('front-assets/images/thumbs-up.png') }}" alt="approved" height="14" width="14" srcset=""></div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                        @endif
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" cellpadding="3" cellspecing="0" border="0" style="font-family: Arial, Helvetica, sans-serif;">
        <tr>
            <td align="center" style="background-color: #72727e;  color: #d4d2df; height: 10px" >
                <p style="font-size: 14px;">Powered by Blitznet. Copyright Reserved.</p>
            </td>
        </tr>
    </table>
</body>

</html>
