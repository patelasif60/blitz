<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> {{ $order->order_number }}</title>



</head>

<body style="page-break-inside: avoid;">
    <table width="100%" cellpadding="5" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;">
        <tr>
            <td align="center"  style="background-color: #002050; color: #fff;">
                <h3 style="margin-block-start: .8em; margin-block-end: .8em;">{{__('order.order_print')}}</h3>
            </td>

        </tr>
        <tr>
            <td>
                <table width="100%" cellpadding="5" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif;">
                    <tr>
                        <td width="50%">
                            @if(!empty($order->supplier_logo))
                                <img src="{{ public_path('storage/' . $order->supplier_logo) }}" alt="" style="height: 70px; width: auto;"  >
                            @else
                                <span style="background-color: #0D6EFD;font-size: 1.5rem;font-family:Arial, Helvetica, sans-serif;color: #fff; padding: 10px; line-height: 25px; display: inline-block;">{{ genrateComanyShortName($order->supplier_company_name) }}</span>
                            @endif
                        </td>
                        <td width="50%">
                            @if(isset($order->customer_reference_id) && !empty($order->customer_reference_id))
                                <div class="col-md-4 pb-2">
                                    <strong>{{ __('rfqs.customer_ref_id') }}: </strong>
                                    <div class="text-dark">{{ $order->customer_reference_id }}</div>
                                </div>
                            @endif
                            <strong>{{__('order.order_no')}}:</strong> {{$order->order_number}}
                            @if(isset($order->group_id) && !empty($order->group_id)) <br>
                                <strong> {{ __('admin.group_number') }}:</strong> BGRP-{{ $order->group_id }}
                            @endif <br>
                            <strong>{{ __('order.Date') }}:</strong>  {{ date('d-m-Y H:i', strtotime($order->created_at)) }}<br>
                            @php
                                $status_name = __('order.' . trim($order->order_status_name));
                                if ($order->order_status == 8) {//'Payment Due DD/MM/YYYY'
                                    $status_name = sprintf($status_name,changeDateFormat($order->payment_due_date,'d/m/Y'));
                                }
                                if($order->order_status_name == 'Order Received'){
                                    $status_name = __('order.orderplaced');
                                }

                            @endphp
                            <strong>Status:</strong> {{ $status_name }}<br>
                            <strong>{{ __('order.payment_terms') }}:</strong>

                            @if($order->payment_type==0)
                                {{ __('rfqs.advance') }}
                            @elseif($order->payment_type==1)
                                {{ __('rfqs.credit') }} - {{$order->credit_days}}
                            @elseif($order->payment_type==2)
                               {{ __('admin.loan_provider_credit') }}
                            @elseif($order->payment_type==3)
                                {{  __('admin.lc')  }}
                            @else
                                {{  __('admin.skbdn')}}
                            @endif

                        </td>
                    </tr>
                    <tr>
                        <td width="50%" valign="top">
                            <table width="90%" cellpadding="5">
                                <tr>
                                    <td style="border-bottom: 2px solid #000000;">{{__('rfqs.our_info')}}</td>
                                </tr>
                                <tr>
                                    <td>
                                        {{--<p><strong>blitznet</strong></p>

                                        <p><strong>{{__('rfqs.mobile')}}:</strong> +62 818968400</p>
                                        <p><strong>{{__('rfqs.Email')}}:</strong> support@blitznet.co.id</p>--}}
                                            <p><strong>{{__('order.supplier_company_name')}}:</strong> {{ $order->supplier_company_name }}</p>
                                            <p><strong>{{__('order.supplier_name')}}:</strong> {{ $order->supplier_name }}</p>
                                            @if(auth()->user()->role_id != 2)
                                                <p><strong>{{__('order.mobile')}}:</strong> {{countryCodeFormat($order->supplier_phone_code,$order->supplier_phone)}}</p>
                                                <p><strong>{{__('order.Email')}}:</strong> {{$order->supplier_email}}</p>
                                            @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="50%" valign="top">
                            <table width="90%" cellpadding="5">
                                <tr>
                                    <td style="border-bottom: 2px solid #000000;">{{__('rfqs.customer_info')}}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><strong>{{ __('order.company') }}:</strong> {{ $order->company_name }}</p>
                                        <p><strong>{{ __('order.name') }}:</strong> {{ $order->firstname . ' ' . $order->lastname }}</p>
                                        @if(auth()->user()->role_id != 3)
                                         <p><strong>{{__('order.mobile')}}:</strong> {{ countryCodeFormat($order->user_phone_code,$order->rfq_mobile) }}</p>
                                         <p><strong>{{__('order.Email')}}:</strong> {{ $order->user_email }}</p>
                                        @endif
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
                            <th width="25%" style="background-color: #002050; color: #fff;  font-size: 12px;">{{ __('rfqs.product') }}</th>
                            <th width="35%" style="background-color: #002050; color: #fff;  font-size: 12px;">{{ __('rfqs.product_description') }}</th>
                            <th width="15%" style="background-color: #002050; color: #fff;  font-size: 12px;">{{ __('rfqs.price') }}</th>
                            <th width="15%" style="background-color: #002050; color: #fff;  font-size: 12px;">{{ __('rfqs.quantity') }}</th>
                            <th width="25%" style="background-color: #002050; color: #fff;  font-size: 12px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                        $finalAmount = 0;
                        $discount = 0;
                        $quote_items = \App\Models\QuoteItem::where('quote_id', $order->quote_id)->get();;
                    @endphp
                    @foreach($multiple_quote as $key => $value)
                        @php
                            $finalAmount += $value->product_price_per_unit * $value->product_quantity ;
                        @endphp
                        <tr>
                            <td style="background-color: #F8F9FA;  font-size: 12px;">{{ get_product_name_by_id($value->rfq_product_id, 0) }}</td>
                            <td style="background-color: #F8F9FA;  font-size: 12px; text-align: left;">{{ get_product_desc_by_id($value->rfq_product_id)}}</td>
                            <td style="background-color: #F8F9FA;  font-size: 12px; text-align: center; white-space: nowrap;">{{ 'Rp ' . number_format($value->product_price_per_unit, 2) }} per {{ $value->name }}</td>
                            <td style="background-color: #F8F9FA;  font-size: 12px; text-align: center;">{{ $value->product_quantity??'' }} {{ $value->name??'' }}</td>

                            <td style="background-color: #F8F9FA;  font-size: 12px; text-align: right; white-space: nowrap;">{{ 'Rp ' . number_format($value->product_amount, 2) }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                    <tfoot>
                        @isset($order->quotes_charges_with_amounts)
                            @php
                                $discount = 0;
                            @endphp
                            @foreach ($order->quotes_charges_with_amounts as $charges)
                            @if($charges->charge_amount)
                                <tr>
                                @if ($charges->type == 0)
                                    <td colspan="4" style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #e6e4e4;   font-size: 12px;"><strong>@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif {{ $charges->charge_name}} {{$charges->charge_value }} %</strong></td>
                                @else
                                    <td colspan="4" style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #e6e4e4;   font-size: 12px;"><strong>@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif {{ $charges->charge_name}}</strong></td>
                                @endif
                                @php
                                    if (auth()->user()->role_id == 3){
                                            if ($charges->charge_name != 'Discount') {
                                                if ($charges->addition_substraction == 0) {
                                                    $finalAmount = $finalAmount - $charges->charge_amount;
                                                } else {
                                                    $finalAmount = $finalAmount + $charges->charge_amount;
                                                }
                                            } else {
                                                $discount = $charges->charge_amount;
                                            }
                                        }
                                @endphp
                                <td style="text-align: right; border-top: 1px solid #000;border-bottom: 1px solid #e6e4e4;   font-size: 12px;white-space: nowrap;">
                                    {{ $charges->addition_substraction == 0 ? '- ' : '+ ' }}{{ 'Rp ' . number_format($charges->charge_amount, 2) }}
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        @endisset
                        @php
                            if (auth()->user()->role_id == 3){
                                $totalAmount = $finalAmount - $discount;
                                $taxamount = ($totalAmount * $order->tax) / 100;
                                $payamount = $totalAmount + $taxamount;
                            }
                            $tex = (auth()->user()->role_id == 3) ? $taxamount : $order->tax_value;
                            $lastamount = (auth()->user()->role_id == 3) ? $payamount : $order->product_final_amount;
                        @endphp
                        @if( $order->tax > 0 && $tex >0)

                        <tr>
                            <td colspan="4" style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #e6e4e4;   font-size: 12px;"><strong>{{ 'Tax ' . $order->tax }} % </strong></td>
                            <td style="text-align: right; border-top: 1px solid #000;border-bottom: 1px solid #e6e4e4;   font-size: 12px;white-space: nowrap;">+ {{ 'Rp ' . number_format($tex, 2) }}</td>
                        </tr>
                        @endif
                        @php
                            $billedAmount = $lastamount;
                            //$bulkOrderDiscount = getBulkOrderDiscount($order->id);
                        @endphp
                        {{--@if($bulkOrderDiscount>0)
                            @php
                                $billedAmount = $lastamount-$bulkOrderDiscount;
                            @endphp
                            <tr>
                                <td colspan="4" style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #e6e4e4;   font-size: 12px;">
                                    <strong>{{ __('admin.bulk_payment_discount') }}</strong>
                                </td>
                                <td style="text-align: right; border-top: 1px solid #000;border-bottom: 1px solid #e6e4e4;   font-size: 12px;white-space: nowrap;">
                                    {{ '- Rp ' . number_format($bulkOrderDiscount, 2) }}
                                </td>
                            </tr>
                        @endif--}}
                        <tr>
                            <td colspan="4" style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #000; background-color: #f4f4f4;   font-size: 12px;"><strong>{{ __('order.total') }}</strong></td>
                            <td style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #000;  background-color: #f4f4f4;   font-size: 12px;white-space: nowrap;"><strong>{{ 'Rp ' . number_format($billedAmount, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <p><strong>{{ __('order.address_details') }}:</strong></p>
                <p>{{ $order->address_line_1 }}<br>
                {{ $order->address_line_2 }}<br>
                {!! $order->sub_district?($order->sub_district.'<br>'):''  !!}
                {!! $order->district?($order->district.'<br>'):'' !!}
                {{ $order->city_id > 0 ? getCityName($order->city_id) : $order->city }}<br>
                {{ $order->state_id > 0 ? getStateName($order->state_id) : $order->state }}<br>
                {{ $order->pincode }}
                </p>
            </td>
        </tr>
        {{--<tr>
            <td>
                <p><strong>{{ __('order.bank_details') }}:</strong></p>
                <p>{{ __('order.bank_name') }}: {{$bankDetail->bank_name}}<br>
                {{ __('order.name') }}:{{$bankDetail->ac_name}}<br>
                {{ __('order.ac_no') }}: {{$bankDetail->ac_no}}<br>
                {{ __('order.bank_code') }}: {{$bankDetail->bank_code}}
                </p>
            </td>
        </tr>--}}

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
