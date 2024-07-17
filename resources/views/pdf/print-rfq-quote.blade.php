<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> {{ $quote->quote_number }}</title>
</head>

<body>
    <table width="100%" cellpadding="5" cellspecing="0"
        style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;page-break-inside: avoid;">
        <tr>
            <td align="center" style="background-color: #002050; color: #fff;">
                <h3 style="margin-block-start: .8em; margin-block-end: .8em;">{{__('rfqs.quote')}}</h3>
            </td>
        </tr>
        <tr>
            <td>

                <table width="100%" cellpadding="5" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif;">
                    <tr>
                        <td width="50%">
                            @if(!empty($quote->supplier_logo))
                                {{--public_path('/front-assets/images/logo-bg.png')--}}
                                <img src="{{ public_path('storage/' . $quote->supplier_logo) }}" alt="" height="50">
                            @else
                                <span style="background-color: #0D6EFD;font-size: 1.5rem;font-family:Arial, Helvetica, sans-serif;color: #fff; padding: 10px; line-height: 25px; display: inline-block;">{{ genrateComanyShortName($quote->supplier_company_name) }}</span>
                            @endif
                        </td>
                        <td width="50%">
                            <strong>{{ __('rfqs.quote_number') }}:</strong> {{ $quote->quote_number }}<br>
                            <strong>{{ __('rfqs.rfq_number') }}:</strong> {{ $quote->rfq_reference_number }}
                            @if(isset($quote->group_id) && !empty($quote->group_id)) <br>
                                <strong> {{ __('admin.group_number') }}:</strong> BGRP-{{ $quote->group_id }}
                            @endif <br>
                            <strong>{{ __('admin.quotation_date') }}:</strong> {{ date('d-m-Y H:i', strtotime($quote->created_at)) }}<br>
                            <strong>{{ __('rfqs.valid_till') }}:</strong> {{ date('d-m-Y', strtotime($quote->valid_till)) }}<br>
                            <strong>{{ __('rfqs.status') }}:</strong> {{ __('rfqs.'.$quote->quote_status_name) ?? ''}}
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
                                        <p><strong>{{__('rfqs.mobile')}}:</strong>  +62 818968400</p>
                                        <p><strong>{{__('rfqs.Email')}}:</strong> support@blitznet.co.id</p>--}}
                                        <p><strong>{{__('rfqs.supplier_company_name')}}:</strong> {{ $quote->supplier_company_name }}</p>
                                        <p><strong>{{__('rfqs.supplier_name')}}:</strong> {{ $quote->supplier_name }}</p>
                                        @if(auth()->user()->role_id != 2)
                                            <p><strong>{{__('rfqs.mobile')}}:</strong> {{ countryCodeFormat($quote->supplier_phone_code,$quote->supplier_phone)}}</p>
                                            <p><strong>{{__('rfqs.Email')}}:</strong> {{$quote->supplier_email}}</p>
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

                                        <p><strong>{{__('rfqs.company')}}:</strong> {{ $quote->user_company_name }}</p>
                                        <p><strong>{{__('rfqs.name')}}:</strong> {{ $quote12->firstname . ' ' . $quote12->lastname }}</p>
                                        @if(auth()->user()->role_id != 3)
                                        <p><strong>{{__('rfqs.mobile')}}:</strong> {{ countryCodeFormat($quote->user_phone_code,$quote12->mobile)}}</p>
                                        <p><strong>{{__('rfqs.Email')}}:</strong> {{$quote12->email}}</p>
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
                            <th width="25%" style="background-color: #002050; color: #fff;  font-size: 12px;">{{ __('rfqs.product') }}
                            </th>
                            <th width="35%" style="background-color: #002050; color: #fff;  font-size: 12px;">{{ __('rfqs.product_description') }}
                            </th>
                            <th width="15%" style="background-color: #002050; color: #fff;  font-size: 12px;">{{ __('rfqs.price') }}</th>
                            <th width="15%" style="background-color: #002050; color: #fff;  font-size: 12px;">{{ __('rfqs.quantity') }}</th>
                            <th width="25%" style="background-color: #002050; color: #fff;  font-size: 12px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                        $finalAmount = 0;
                        $discount = 0;
                        $taxAmt = 0;
                    @endphp
                    @foreach($quote_items as $key => $value)
                        @php
                            $finalAmount = $finalAmount + $value->product_amount;
                            $taxAmt = $taxAmt + $value->product_amount;
                        @endphp
                        <tr>
                            <td style="background-color: #F8F9FA;  font-size: 12px;">{{ get_product_name_by_id($value->rfq_product_id, 0) }}</td>
                            <td style="background-color: #F8F9FA;  font-size: 12px;text-align: left;">{{ get_product_desc_by_id($value->rfq_product_id)}}</td>
                            <td style="background-color: #F8F9FA;  font-size: 12px; text-align: center; white-space: nowrap;">{{ 'Rp ' . number_format($value->product_price_per_unit, 2) }} per {{ $value->name }}</td>
                            <td style="background-color: #F8F9FA;  font-size: 12px; text-align: center;">{{ $value->product_quantity??'' }} {{ $value->name??'' }}</td>
                            <td style="background-color: #F8F9FA;  font-size: 12px; text-align: right; white-space: nowrap;">{{ 'Rp ' . number_format($value->product_amount, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>

                        @foreach ($quotes_charges_with_amounts as $charges)
                            @if($charges->charge_amount > 0)
                            <tr>
                                @if ($charges->type == 0)
                                    <td colspan="4"
                                    style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #e6e4e4;   font-size: 12px;">
                                    <strong>@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif {{ $charges->charge_name . ' ' . $charges->charge_value }}%</strong></td>

                                @else
                                <td colspan="4"
                                    style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #e6e4e4;   font-size: 12px;">
                                    <strong>@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif {{ $charges->charge_name }}</strong></td>
                                @endif
                                @php
                                    if (auth()->user()->role_id == 3){
                                        if ($charges->charge_name != 'Discount') {
                                            if ($charges->addition_substraction == 0) {
                                                $finalAmount = $finalAmount - $charges->charge_amount;
                                            } else {
                                                $finalAmount = $finalAmount + $charges->charge_amount;
                                            }
                                            if($quote->inclusive_tax_other == 0)
                                            {
                                                if($charges->addition_substraction == 0){
                                                    $taxAmt = $taxAmt - $charges->charge_amount;
                                                }
                                                else{
                                                    $taxAmt = $taxAmt + $charges->charge_amount;
                                                }
                                            }

                                        }
                                        else
                                        {
                                            $discount = $charges->charge_amount;
                                        }
                                    }
                                @endphp
                                <td
                                style="text-align: right; border-top: 1px solid #000;border-bottom: 1px solid #e6e4e4; font-size: 12px;white-space: nowrap;">
                                    {{ $charges->addition_substraction == 0 ? '- ' : '+ ' }}{{ 'Rp ' . number_format($charges->charge_amount, 2) }}
                                </td>
                            </tr>
                            @endif

                        @endforeach
                        @php
                        if (auth()->user()->role_id == 3){
                                $totalAmount = $finalAmount - $discount;
                                $newTexAmt = $taxAmt - $discount;
                                $taxamount = ($newTexAmt * $quote->tax) / 100;
                                $payamount = $totalAmount + $taxamount;
                            }
                            $tex = (auth()->user()->role_id == 3) ? $taxamount : $quote->tax_value;
                            $lastamount = (auth()->user()->role_id == 3) ? $payamount : $quote->final_amount;
                        @endphp
                        @if($quote->tax > 0 && $tex > 0)
                        <tr>
                            <td colspan="4"
                                style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #e6e4e4;   font-size: 12px;">
                                <strong>{{ __('admin.ppn').' '.$quote->tax }}% </strong></td>
                            <td
                                style="text-align: right; border-top: 1px solid #000;border-bottom: 1px solid #e6e4e4;   font-size: 12px;white-space: nowrap;">
                                + {{ 'Rp ' . number_format($tex, 2) }}</td>
                        </tr>
                        @endif
                        <!-- <tr>
                            <td colspan="4" style="text-align: right;border-bottom: 1px solid #e6e4e4;   font-size: 12px;">Supplier Discount 10 %	 </td>
                            <td style="text-align: right;border-bottom: 1px solid #e6e4e4;   font-size: 12px;white-space: nowrap;">- Rp 5,000.00</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: right;border-bottom: 1px solid #e6e4e4;   font-size: 12px;">Transaction fees	</td>
                            <td style="text-align: right;border-bottom: 1px solid #e6e4e4;   font-size: 12px;white-space: nowrap;">+ Rp 500.00</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: right;border-bottom: 1px solid #e6e4e4;   font-size: 12px;">Insurance 2 %</td>
                            <td style="text-align: right;border-bottom: 1px solid #e6e4e4;   font-size: 12px;white-space: nowrap;">+ Rp 2,502.50</td>
                        </tr> -->

                        <tr>
                            <td colspan="4"
                                style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #000; background-color: #f4f4f4;   font-size: 12px;">
                                <strong>Total</strong></td>
                            <td
                                style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #000;  background-color: #f4f4f4;   font-size: 12px;white-space: nowrap;">
                                <strong>{{ 'Rp ' . number_format($lastamount, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%" cellpadding="5" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif;">
                    <tr>
                        <td width="70%">
                            <table width="100%" cellpadding="5" cellspecing="0"
                                style="font-family: Arial, Helvetica, sans-serif;font-size: 14px;">
                                <tr>
                                    <td>{{ __('rfqs.Deliver_order_in') }} {{ $quote_items[0]->min_delivery_days }} {{strtolower(__('admin.to'))}} {{$quote_items[0]->max_delivery_days}} {{__('admin.days')}}*</td>

                                </tr>
                                @if ($quote12->unloading_services || $quote12->rental_forklift)
                                <tr>
                                    <td>
                                        <strong>{{ __('rfqs.other_options') }}:</strong><br>
                                        @if ($quote12->unloading_services)
                                        {{ __('dashboard.need_unloading_services') }}<br>
                                        @endif
                                        @if ($quote12->rental_forklift)
                                            {{ __('dashboard.need_rental_forklift') }}
                                        @endif

                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="2">
                                        <p style="font-family: Arial, Helvetica, sans-serif; font-size: 14px; ">
                                            <strong>{{ __('rfqs.comment') }}:</strong><br>
                                            {{ $quote->comment ? $quote->comment : ' - ' }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="font-family: Arial, Helvetica, sans-serif; font-size: 14px; margin-top: 0;">
                                            <strong>{{ __('rfqs.note') }}:</strong><br>{{ $quote->note ? $quote->note : ' - ' }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        @php
                            if(isset($approversList) && count($ConfigUsersCount) > 0) {
                                $feedbackCount = $pendingFeedback = 0;
                                foreach($approversList as $list) {
                                    $feedbackCount += ($list->feedback != 0);
                                    $pendingFeedback += ($list->feedback == 0);
                                }
                            } else if(isset($approversList) && count($ConfigUsersCount) == 0) {
                                $feedbackCount = 0;
                            } else {
                                $feedbackCount = 0;
                            }
                        @endphp

                        @if(isset($approversList) && count($ConfigUsersCount) > 0 && $toggleSwitch['approval_process'] == 1 && $quote->status_id == 1)
                        <td width="30%" valign="bottom" padding-bottom="10">
                            @if($feedbackCount != '' || $pendingFeedback != '')
                            <table border="1" cellspacing="0" cellpadding="5">
                                <thead style="background-color: #d7d9df;">
                                    <tr style="font-size: 12px;">
                                        <th class="text-center" colspan="2">{{ __('rfqs.reviewd_by') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($approversList as $approver)
                                    <tr>
                                        <td width="90%">
                                            <div style="font-size: 10px;"><strong>{{ $approver->firstname . ' ' . $approver->lastname }}</strong> {{ isset($approver->name)? '('. $approver->name.')' : " " }}</div>
                                        </td>
                                        @php
                                            if(isset($approver->feedback) && $approver->feedback == 0) {
                                                $feedback = public_path('front-assets/images/pending.png');
                                            } elseif ($approver->feedback == 1) {
                                                $feedback = public_path('front-assets/images/thumbs-up.png');
                                            } else if ($approver->feedback == 2) {
                                                $feedback = public_path('front-assets/images/thumbs-down.png');
                                            }
                                        @endphp
                                        <td width="10%">
                                            <div><img src="{{ $feedback }}" alt="approved" height="14" width="14" alt="Thumbs Up" srcset=""></div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                        </td>
                        <!-- @endif -->
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
