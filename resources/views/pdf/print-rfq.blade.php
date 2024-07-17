<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> {{$rfq->reference_number}} </title>
</head>
<body style="page-break-inside: avoid;">
    <table width="100%" cellpadding="0" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;">
        <tr>
            <td align="center"  style="background-color: #002050; color: #fff;">
                <h3 style="margin-block-start: .8em; margin-block-end: .8em;">RFQ</h3>
            </td>

        </tr>
        <tr>
            <td>
                <table width="100%" cellpadding="5" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif;">
                    <tr>
                        <td width="50%"><img src="{{public_path('/front-assets/images/logo-bg.png')}}" alt="" height="50" width="149"></td>
                        <td width="50%">
                            <strong>{{ __('rfqs.rfq_no')  }}</strong> {{$rfq->reference_number}}
                            @if(isset($rfq->group_id) && !empty($rfq->group_id)) <br>
                                <strong> {{ __('admin.group_number') }}:</strong> BGRP-{{ $rfq->group_id }}
                            @endif <br>
                            <strong>{{ __('rfqs.date') }}:</strong> {{ date('d-m-Y H:i', strtotime($rfq->created_at)) }}<br>
                            <strong>Status:</strong> {{ __('rfqs.' . $rfq->status_name) }}<br>
                            <strong>{{ __('rfqs.payment_terms') }}:</strong>
                            @if($rfq->payment_type==0)
                                {{ __('rfqs.advance') }}
                            @elseif($rfq->payment_type==1)
                                {{ __('rfqs.credit') }} - {{$rfq->credit_days}}
                            @elseif($rfq->payment_type==2)
                                {{ __('rfqs.credit') }}
                            @elseif($rfq->payment_type==3)
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
                                        <p><strong>blitznet</strong></p>

                                        <p><strong>{{__('rfqs.mobile')}}:</strong>  +62 818968400</p>
                                        <p><strong>{{__('rfqs.Email')}}:</strong> support@blitznet.co.id</p>
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

                                        <p><strong>{{__('rfqs.company')}}:</strong> {{$comp->company_name ?? ''}}</p>
                                        <p><strong>{{__('rfqs.name')}}:</strong> {{ $rfq->firstname . ' ' . $rfq->lastname }}</p>
                                        @if(auth()->user()->role_id != 3)
                                         <p><strong>{{__('rfqs.mobile')}}:</strong> {{ countryCodeFormat($rfq->phone_code,$rfq->mobile)}}</p>
                                         <p><strong>{{__('rfqs.Email')}}:</strong> {{$rfq->email}}</p>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>

            </td>
        </tr>
        @php
            $i=0;
        @endphp
        <tr>
            <td>
                <table width="100%" cellpadding="5" cellspecing="0" border="0" >
                    <thead>
                        <tr>
                            <th style="background-color: #002050; color: #fff;  font-size: 12px;width: 50px">{{ __('rfqs.sr_no') }}</th>
                            <th width="25%" style="background-color: #002050; color: #fff;  font-size: 12px;">{{ __('rfqs.product') }}</th>
                            <th width="35%" style="background-color: #002050; color: #fff;  font-size: 12px;">{{ __('rfqs.product_description') }}</th>
                            <th width="15%" style="background-color: #002050; color: #fff;  font-size: 12px;">{{ __('rfqs.quantity') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                    @foreach($all_products as $key => $value)
                        @php
                            $i++;$rfqCategory = $value->category;
                            $rfqCategoryId = $value->category_id;
                        @endphp
                        <tr>
                            <td style="background-color: #F8F9FA;  font-size: 12px;height: 30px;"> {{ $i }}</td>
                            <td style="background-color: #F8F9FA;  font-size: 12px;height: 30px;"> {{ $value->category . ' - ' . $value->sub_category . ' - ' . $value->product }}</td>
                            <td style="background-color: #F8F9FA;  font-size: 12px; text-align: left;">{{ $value->product_description }}</td>
                            <td style="background-color: #F8F9FA;  font-size: 12px; text-align: center;">{{ $value->quantity }} {{ $value->unit_name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <table width="100%" cellpadding="5" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif;font-size: 14px;page-break-inside:avoid;">
                <tr>
                    <td colspan="2">
                        <p style="margin-bottom:0;"><strong>@if(in_array($rfqCategoryId,\App\Models\Category::SERVICES_CATEGORY_IDS))  {{__('admin.pickup_address')}} @else {{__('rfqs.delivery_address')}} @endif:</strong>
                            {{ $rfq->address_line_1?($rfq->address_line_1.','):'' }} {{ $rfq->address_line_2?($rfq->address_line_2.','):'' }} {{ $rfq->sub_district?($rfq->sub_district.','):'' }} {{ $rfq->district?($rfq->district.','):'' }} {!! $rfq->city_id > 0 ? getCityName($rfq->city_id).',' : ( empty($rfq->city) ? '' : $rfq->city.',')  !!}
                            {!! $rfq->state_id > 0 ? getStateName($rfq->state_id).',' : ( empty($rfq->state) ? '' : $rfq->state.',') !!}
                            {{ $rfq->pincode }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td width="50%" style="vertical-align:top;">
                     <p style="margin-bottom:0;">
                        <strong>{{ __('rfqs.expected_delivery_date') }}:</strong> {{ date('d-m-Y', strtotime($rfq->expected_date)) }}
                    </p>
                    </td>

                </tr>
                @if ($rfq->unloading_services || $rfq->rental_forklift)
                <tr>
                    <td style="vertical-align:top;"><strong>
                        <p style="margin-bottom:0;">
                        {{ __('rfqs.other_options') }}:</strong>
                        @if ($rfq->unloading_services)
                           {{ __('dashboard.need_unloading_services') }}<br>
                        @endif
                        @if ($rfq->rental_forklift)
                            {{ __('dashboard.need_rental_forklift') }}
                        @endif
                        </p>
                    </td>
                </tr>
                @endif
                <tr>
                    <td colspan="2">
                        <p style="margin-top:0;"><strong>{{ __('rfqs.comment') }}:</strong><br>
                        {{ $rfq->comment ? $rfq->comment : ' - ' }}</p>
                    </td>

                </tr>
            </table>
        </tr>
        <!-- <tr>
            <td>
                <table width="100%" cellpadding="10" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif;font-size: 14px;">
                    <tr>


                    </tr>

                </table>

            </td>
        </tr> -->
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
