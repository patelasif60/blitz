<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blitznet Invoice</title>
</head>

<body style="page-break-inside: avoid;">
    @php
        $rfq = $order->rfq()->first();
        $quote = $order->quote()->first();
        $buyer = $order->user()->first();
        $buyer_company = $order->companyDetails()->first();
        $orderPo = $order->orderPo()->first();
        $finalAmount = 0;
        $subTotal = 0;$FinalactualAmount=0;
        $ppn = 0;$actualAmount=0;
          if(auth()->user()->role_id == 3){
              $ppn = $quote->supplier_tex_value;
          }else{
              $ppn = ($quote->tax_value) - ($quote->supplier_tex_value);
          }

    @endphp
    <table width="100%" cellpadding="5" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;  padding: 0px;">
        <tr>
            <td align="center" style="background-color: #002050; color: #fff;">
                <h3 style="margin-block-start: .8em; margin-block-end: .8em;">{{ __('admin.title_invoice') }}</h3>
            </td>
        </tr>
        <tr>
            <td style=" padding: 0px;">
                <table width="100%" cellpadding="5" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif;">
                    <tr>
                        <td width="50%">
                            @if(!empty($buyer_company->logo))
                                <img src="{{ public_path('storage/' . $buyer_company->logo) }}" alt="" height="40" width="auto">
                            @else
                                <span style="background-color: #0D6EFD;font-size: 1.5rem;font-family:Arial, Helvetica, sans-serif;color: #fff; padding: 10px; line-height: 20px; display: inline-block;">{{ genrateComanyShortName($buyer_company->name)  }}</span>
                            @endif
                        </td>
                        <td width="50%">
                            <h4 style="margin: 0px;">{{ __('admin.invoice') }}</h4>
                            <p style="margin: 0px;">{{ $orderPo->inv_number}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" valign="top">
                            <table width="100%" cellpadding="0">
                                <tr>
                                    <td>
                                        <p><strong>{{ __('admin.for') }}</strong></p>
                                        <p>{{ __('admin.purchase_date') }}<strong> : {{ date('d M Y', strtotime($orderPo->created_at)) }}</strong></p>
                                        <p>{{ __('admin.customer_company') }}<strong> : {{ $buyer_company->name }}</strong></p>
                                        <p>{{ __('admin.pic_name') }}<strong>: {{ $buyer->firstname .' '. $buyer->lastname }}</strong></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="50%" valign="top">
                            <table width="100%" cellpadding="0">
                                <tr>
                                    <td>
                                        <p>{{ __('admin.customer_address') }} <strong>:</strong> <br>
                                            <strong>{{ $order->address_line_1.','}}{{ $order->address_line_2.','}}{!! $order->sub_district?($order->sub_district.','):''  !!}
                                                        {!! $order->district?($order->district.','):'' !!}
                                                        {{ $order->city_id > 0 ? getCityName($order->city_id) : $order->city.','}}
                                                        {{ $order->state_id > 0 ? getStateName($order->state_id) : $order->state.','}}
                                                        {{ $order->pincode }}
                                                        </strong>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style=" padding: 0px;">
                <table width="100%" cellpadding="3" cellspecing="0" border="0">
                    <thead>
                        <tr>
                            <th
                                style="border-bottom: 2px solid #000000; border-top: 2px solid #000000; background-color: #002050; color: #FFF; text-align: start; padding: 5px;">
                                <strong>{{ __('admin.item_description') }} </strong>
                            </th>
                            <th align="right"
                                style="border-bottom: 2px solid #000000; border-top: 2px solid #000000; background-color: #002050; color: #FFF; padding: 5px;">
                                <strong style="display: flex; justify-content: end;">{{__('admin.price')}} <p
                                        style="margin: 0px; font-style: normal;">({{ __('admin.before') }}  {{__('admin.tax')}})</p></strong>
                            </th>
                            <th
                                style="border-bottom: 2px solid #000000; border-top: 2px solid #000000; background-color: #002050; color: #FFF; text-align: start; padding: 5px;">
                                <strong>{{__('admin.tax')}} </strong>
                            </th>
                            <th align="right"
                                style="border-bottom: 2px solid #000000; border-top: 2px solid #000000; background-color: #002050; color: #FFF; padding: 5px;">
                                <strong style="display: flex; justify-content: end;">{{__('admin.price')}} <p
                                        style="margin: 0px; font-style: normal;">( {{ __('admin.including') }} {{__('admin.tax')}})</p></strong>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                    @endphp
                     @foreach ($quotes_charges_with_amounts as $charges)
                         @php
                           if ($charges->charge_type == 2){
                                 $taxPercentage = (int)11;
                                 $totalAmount = $charges->charge_amount;
                                 $actualAmount = ($charges->charge_amount * 100) / (11 + 100);
                                 $taxValue = 0;
                            }  else{
                                $taxPercentage =  $quote->tax;
                                $actualAmount = $charges->charge_amount;
                                $taxValue = (int)$quote->tax_value - (int)$quote->supplier_tex_value;
                                $totalAmount = ($charges->charge_amount *  $taxPercentage) / 100;
                                $totalAmount = $actualAmount + $totalAmount;
                            }
                            if ($charges->addition_substraction == 0){
                                $subTotal = ($subTotal - $totalAmount);
                            }else{
                                $subTotal = ($subTotal + $totalAmount);
                            }

                         @endphp
                        <tr>
                            <td style="background-color: #ffffff; width: 55%; border-bottom: 1px solid #e6e4e4;padding: 5px; ">{{ $charges->charge_name}}</td>
                             <td style="background-color: #ffffff; border-bottom: 1px solid #e6e4e4; padding: 5px; text-align: right;">
                                {{ $charges->addition_substraction == 0 ? '- ' : '+ ' }}{{ 'Rp ' . number_format($actualAmount, 2) }}
                            </td>
                            <td style="background-color: #ffffff;  border-bottom: 1px solid #e6e4e4;padding: 5px; text-align:center;">{{ $taxPercentage .'%'}}  </td>
                            <td style="background-color: #ffffff; border-bottom: 1px solid #e6e4e4; padding: 5px; text-align: right;">
                                {{ $charges->addition_substraction == 0 ? '- ' : '+ ' }}{{ 'Rp ' . number_format($totalAmount, 2) }}
                            </td>
                        </tr>
                     @endforeach
                    @php
                        $billedAmount = $subTotal;
                        $bulkOrderDiscount = 0;
                        $bulkOrderDiscount = getBulkOrderDiscount($order->id);
                    @endphp
                    @if($bulkOrderDiscount>0)
                        @php
                            $billedAmount = $billedAmount-$bulkOrderDiscount;
                        @endphp
                        <tr>
                            <td style="background-color: #ffffff; width: 55%; border-bottom: 1px solid #e6e4e4;padding: 5px; ">
                                {{ __('admin.bulk_payment_discount') }}
                            </td>
                            <td colspan="3" style="background-color: #ffffff; border-bottom: 1px solid #e6e4e4; padding: 5px; text-align: right;">
                                {{ '- Rp ' . number_format($bulkOrderDiscount, 2) }}
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style=" padding: 0px;">
                <table width="100%" cellpadding="3" cellspecing="0" border="0">
                    <tbody>

                        <tr>
                            <td style="border-top: 1px solid #000; width: 75%; border-bottom: 1px solid #000; background-color: #f4f4f4; padding: 5px; text-align: right;">
                                <h4 style="margin: 1px;">{{__('admin.total')}}</h4>
                            </td>
                            <td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000; background-color: #f4f4f4; padding: 5px; ">
                                <p style="margin: 0px; font-weight: bold;">Rp {{number_format($billedAmount, 2)}}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%" cellpadding="5" cellspecing="0" border="0">
                    <tr>
                        <td>
                            {!! __('admin.invoice_note') !!}
                        </td>
                        <td align="right" style="font-style:italic ;">
                            <p style="margin: 0px;">{{ __('admin.last_updated') }}: {{ date('d M Y h:i', strtotime($orderPo->updated_at)) }} WIB</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
