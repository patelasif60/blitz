<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> {{$rfq->reference_number}} </title>
</head>
<body>
    <table width="100%" cellpadding="5" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;">
        <tr>
            <td align="center"  style="background-color: #002050; color: #fff;">
                <h3 style="margin-block-start: .8em; margin-block-end: .8em;">RFQ</h3>
            </td>

        </tr>
        <tr>
            <td>
            <table width="100%" cellpadding="10" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif;font-size: 14px;">
                <tr>
                    <td width="33%" style="vertical-align:top;">
                        <p style="margin-bottom:0;">
                            <strong>{{ __('rfqs.rfq_number')  }}:</strong><br>  {{$rfq->reference_number}} 
                        </p>
                    </td>
                    <td width="34%" style="vertical-align:top;">
                        <p style="margin-bottom:0;">
                            <strong>{{ __('rfqs.date') }}:</strong><br> {{ date('d-m-Y', strtotime($rfq->created_at)) }}
                        </p>
                    </td>
                    <td width="33%" style="vertical-align:top;">
                        <p style="margin-bottom:0;">
                            <strong>Status:</strong><br> {{ __('rfqs.' . $rfq->status_name) }}
                        </p>
                    </td>
                   
                </tr>
                <tr>
                    <td width="33%" style="vertical-align:top;">
                        <p style="margin-bottom:0;">
                            <strong>{{ __('rfqs.name')  }}:</strong><br>{{ $rfq->firstname . ' ' . $rfq->lastname }}
                        </p>
                    </td>
                    <td width="34%" style="vertical-align:top;">
                        <p style="margin-bottom:0;">
                            <strong>{{ __('rfqs.company') }}:</strong><br> {{ $rfq->company_name }}
                        </p>
                    </td>
                    <td width="33%" style="vertical-align:top;">
                        <p style="margin-bottom:0;">
                            <strong>{{ __('rfqs.mobile') }}:</strong><br>{{$rfq->mobile}}
                        </p>
                    </td>
                   
                </tr>
                <tr>
                    <td width="33%" style="vertical-align:top;">
                        <p style="margin-bottom:0;">
                            <strong>{{ __('rfqs.pincode')  }}:</strong><br>{{ $rfq->pincode }}
                        </p>
                    </td>
                    <td width="34%" style="vertical-align:top;">
                        <p style="margin-bottom:0;">
                            <strong>{{ __('rfqs.expected_delivery_date') }}:</strong><br> {{ date('d-m-Y', strtotime($rfq->expected_date)) }}
                        </p>
                    </td>
                    <td width="33%" style="vertical-align:top;">
                        <p style="margin-bottom:0;">
                            <strong>{{ __('rfqs.comment') }}:</strong><br>{{$rfq->comment}}
                        </p>
                    </td>
                   
                </tr>
                <tr>
                    <td width="33%" style="vertical-align:top;">
                        <p style="margin-bottom:0;">
                            <strong>{{ __('rfqs.payment_terms')  }}:</strong><br>
                            @if($rfq->is_require_credit)
                                {{ __('rfqs.credit') }}
                            @else
                                {{ __('rfqs.advance') }}
                            @endif
                        </p>
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
                            <th width="15%" style="background-color: #002050; color: #fff;  font-size: 12px;">{{ __('rfqs.quantity') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="background-color: #F8F9FA;  font-size: 12px;"> {{ $rfq->category . ' - ' . $rfq->sub_category . ' - ' . $rfq->product_name }}</td>
                            <td style="background-color: #F8F9FA;  font-size: 12px; text-align: left;">{{ $rfq->product_description }} </td>
                            <td style="background-color: #F8F9FA;  font-size: 12px; text-align: center;">{{ $rfq->quantity . ' ' . $rfq->unit_name }}</td>
                        </tr>
                       
                       
                    </tbody>
                </table>
            </td>
        </tr>
        
    </table>

</body>

</html>