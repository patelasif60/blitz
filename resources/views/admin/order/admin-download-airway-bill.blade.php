<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <title>Pickup-request-{{ $orderBatchDetail['orderBatch'] }}</title>

</head>

<body style="page-break-inside: avoid;">
<table width="100%" cellpadding="5" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif;">
    <tr>
        <td align="center" style="background-color: #002050; color: #fff;">
            <img alt=""
                 src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIYAAAApCAYAAADwM1GqAAAABHNCSVQICAgIfAhkiAAABVxJREFUeF7tWjtyFUEMbKUkQAIh5gTgE2DHUIV9AuAEfC4APgFwAiAjAwJIMSfA5FRhMogwJxDVW9KWdt9+/XafvcNMFVX47Xw0Uo/U0oyoquKcNxGRcy5icuJJBkZyNp1kQxkYk6gxvUkyMNKz6SQ7ysCYRI3pTZKBkZ5NJ9lRBsYkakxvkgyM9Gw6yY6GAOMxgKNJVmue5CaA513z5zrGjNpvmXoIMHZF5HAu0VR1B8DnDIy5NHy6eZMEhqo+A6AicjBGLar6BMAFAG9F5LuPVdVHnA/AGxE5GTPnUvumCoyizD82BKnqbwBXANwRkY+cw0Dhoe6xiLxYqrHHyJ2BEbTVAoz7AF5ZtxVgqCrD7CMRmZOHjbHpqL6qegnAOwBfRISetmgZGD3AMK+xB+CSiLyOWldVEuevAGblYaMsPbKzqjrwDzIwWpTX5DG69KyqDCsPFw6M9wDuAtgXEf7///EYdiquAdgCcAzgQ5PrbwOGk1kjn8eWSd0CwNPGOelJOK83klT2K0hwB7iKfvXvqso57xlPOjB3z7/5O9uRiLxpm9fG09jsz1DBNSprBdk8fMQ9/Fw7lAQljXRiZffetxbRxQ1ZJDwl2Lb46QqNw1+ICGs0ZesAhhu3CBmm1Kcdsni/vrcujSFIVRm6GPd/kr8Yx6GBY3stIg9q8rMPiTIB29TIhV4a0P50AnbAe4zO+DlASX22JOlhLWOyFvbEk0JQvLQiHRVHXlCcRp70qNwRwOCc/Of1l3oRkCf6xA5NfV80NE8zjX6zKf0NOmWfiwD+AmDY4n5odI5n23bPZ3yH8nCPHMf+JMT8m2vSw7HtAyBhph4IQIbCb9bHZT2ewmPQFXWdnj6DzwkMKnSnHjYC4aJsJfCHAsM3FAA4iHzWinmlUesKUlWP+/zEEFDxAKrKWgoBUxBG8wAEBY290p+ThDkZRgkI/uYcaWVM6sCoMO2a2+Wp4SkqlTInMMx4zGDoaVrlMoPRM5ATNR4aS5EpuwPDDycBUxi9oRE0BMKJiFy2dVwHK/KkDozWkxzcdan8mYHhp/ObiNBIrS14okqmEDwVQ8QNzyRU1YHU552L7174C55nRU+pA+NyWwk7hJPSUHMBY2gIsVNc3h21VW5jCDMe4URyyBXAMesxNQJ6vZ4dpQ6MM/cYtRDSW1IPgG0LIxXgRNCNuQII4/6KSD3jWb/yec6zkkMR2W0gd1TED2PspbFO4THchUcCyxQ1xnEnkoNIdhchNI/ilcro6TwtflCvzobww8pteQHYEkqdq+xO4TGI4HXTzc58/xS3pHE+Fm5o/EIp4W6AMjNr2QrfVi7RbEyljhGUXSFvwT0XpzDUI7gOU9OVYlYDaFsJocnixovZhY/hHslLymcSJhNTUgKK34o7nQAMptas9/A35y7bawNjENvpJlqTv8cIMZjVQa9Z+CVXJH4VcncKjxFTda8N0BsVmU4gdzRY0yUbTz1rDGXrIoRtmYRVOjk/U1g2X8/rLfyN4Nxz0NRSdq93sH/hiZIGBmOunQwq35VGJX1puhFV1V8ArgK4LSKfgmdo9BhmqFhz8LlpABa4+iqflfDSRwhtPQ9fdVDTqASqHwQX34tjrPRW3pLU6iUV2ZMERpODClVIsvJelz7GE5pB6YmKiueYsXP0DXsl1+l8DmDehqCqyP7fAGMOA6Q8ZwZGytZdY28ZGGsoL+WhGRgpW3eNvQ0BBhn9nO8Z/XKndRtjKnpr6CIPDRoYAowzV1gGxuZNkIGxeZ0vYsUMjEWYafNCZmBsXueLWDEDYxFm2ryQGRib1/kiVszAWISZNi9kBsbmdb6IFf8B7ssY53yQ9kIAAAAASUVORK5CYII="/>
        </td>
    </tr>
</table>

<table width="100%" cellpadding="5" cellspecing="0"
       style="page-break-inside: avoid;font-family: Arial, Helvetica, sans-serif;">
    <tr>
        <td>
            <table width="100%" cellpadding="5" cellspecing="0"
                   style="page-break-inside: avoid;font-family: Arial, Helvetica, sans-serif;">
                <tr>
                    <td width="50%" valign="top">
                        <table width="90%" cellpadding="5">
                            @if(in_array($orderBatchDetail['orderItemCategoryId'],\App\Models\Category::SERVICES_CATEGORY_IDS))
                                <tr>
                                    <td style="border-bottom: 2px solid #000000;">
                                        <strong>{{ __('admin.receiver_info') }}</strong></td>
                                </tr>
                            @else
                                <tr>
                                    <td style="border-bottom: 2px solid #000000;">
                                        <strong>{{ __('admin.supplier_info') }}</strong></td>
                                </tr>
                            @endif
                            <tr>
                                <td>
                                    @if(in_array($orderBatchDetail['orderItemCategoryId'],\App\Models\Category::SERVICES_CATEGORY_IDS))
                                        <p>
                                            <strong>
                                                {{ __('admin.receiver_company') }}:
                                            </strong>{{ $orderBatchDetail['receiptance']['receiverCompanyName'] }}
                                        </p>
                                        <p>
                                            <strong>
                                                {{ __('admin.receiver_name') }}:
                                            </strong>{{ $orderBatchDetail['receiptance']['receiverName'] }}</p>
                                        <p>
                                            <strong>
                                                {{ __('admin.receiver_email') }}:
                                            </strong>{{ $orderBatchDetail['receiptance']['receiverEmail'] }}
                                        </p>
                                        <p>
                                            <strong>
                                                {{ __('admin.receiver_pic_phone') }}:
                                            </strong>{{ $orderBatchDetail['receiptance']['receiverPhone'] }}
                                        </p>
                                    @else
                                        <p>
                                            <strong>
                                                {{ __('admin.supplier_company') }}:
                                            </strong>{{ $orderBatchDetail['receiptance']['supplierName'] }}
                                        </p>
                                        <p>
                                            <strong>
                                                {{ __('admin.supplier_name') }}:
                                            </strong>{{ $orderBatchDetail['receiptance']['supplierCompanyName'] }}</p>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%">
                        <p><strong>{{ __('admin.order_number') }}: </strong>{{ $orderBatchDetail['orderBatch'] }}</p>
                        <p><strong>{{ __('admin.airwaybill_number') }}
                                : </strong> {{ $orderBatchDetail['airwaybillNumber'] }}</p>
                        <p><strong>{{__('admin.quincus_services')}}
                                : </strong>{{ $orderBatchDetail['otherDetails']['logisticsServices'] }}
                        </p>
                        <p><strong>{{__('admin.quincus_pickup_service')}}
                                : </strong>{{ $orderBatchDetail['otherDetails']['serviceType'] }}</p>
                        <p><strong>{{__('admin.date_time_label')}}
                                : </strong>{{ $orderBatchDetail['pickupDateAndTime'] }} </p>
                    </td>
                </tr>
                <tr>
                    <td width="50%" valign="top">
                        <table width="90%" cellpadding="5">
                            <tr>
                                <td style="border-bottom: 2px solid #000000;">
                                    <strong>{{__('admin.pickup_address')}}</strong></td>
                            </tr>
                            <tr>
                                <td>
                                    @if($orderBatchDetail['servicePickupAddressKeyName'])
                                        <p style="line-height: 1.4;">
                                            @if($orderBatchDetail[$orderBatchDetail['servicePickupAddressKeyName']]['address_name']) {{ $orderBatchDetail[$orderBatchDetail['servicePickupAddressKeyName']]['address_name'] }}
                                            <br> @endif
                                            @if($orderBatchDetail[$orderBatchDetail['servicePickupAddressKeyName']]['address_line_1']) {{ $orderBatchDetail[$orderBatchDetail['servicePickupAddressKeyName']]['address_line_1'] }}
                                            <br> @endif
                                            @if($orderBatchDetail[$orderBatchDetail['servicePickupAddressKeyName']]['address_line_2']) {{ $orderBatchDetail[$orderBatchDetail['servicePickupAddressKeyName']]['address_line_2'] }}
                                            <br> @endif
                                            @if($orderBatchDetail[$orderBatchDetail['servicePickupAddressKeyName']]['district']) {{ $orderBatchDetail[$orderBatchDetail['servicePickupAddressKeyName']]['district'] }}
                                            <br> @endif
                                            @if($orderBatchDetail[$orderBatchDetail['servicePickupAddressKeyName']]['sub_district']) {{ $orderBatchDetail[$orderBatchDetail['servicePickupAddressKeyName']]['sub_district'] }}
                                            <br> @endif
                                            @if($orderBatchDetail[$orderBatchDetail['servicePickupAddressKeyName']]['cityName']) {{ $orderBatchDetail[$orderBatchDetail['servicePickupAddressKeyName']]['cityName'] }} @endif
                                            , {{ $orderBatchDetail[$orderBatchDetail['servicePickupAddressKeyName']]['stateName'] }} {{ $orderBatchDetail[$orderBatchDetail['servicePickupAddressKeyName']]['pincode'] }}
                                        </p>
                                    @else
                                        <p style="line-height: 1.4;">
                                            @if($orderBatchDetail['pickupAddress']['address_name']) {{ $orderBatchDetail['pickupAddress']['address_name'] }}
                                            <br> @endif
                                            @if($orderBatchDetail['pickupAddress']['address_line_1']) {{ $orderBatchDetail['pickupAddress']['address_line_1'] }}
                                            <br> @endif
                                            @if($orderBatchDetail['pickupAddress']['address_line_2']) {{ $orderBatchDetail['pickupAddress']['address_line_2'] }}
                                            <br> @endif
                                            @if($orderBatchDetail['pickupAddress']['district']) {{ $orderBatchDetail['pickupAddress']['district'] }}
                                            <br> @endif
                                            @if($orderBatchDetail['pickupAddress']['sub_district']) {{ $orderBatchDetail['pickupAddress']['sub_district'] }}
                                            <br> @endif
                                            @if($orderBatchDetail['pickupAddress']['cityName']) {{ $orderBatchDetail['pickupAddress']['cityName'] }} @endif
                                            , {{ $orderBatchDetail['pickupAddress']['stateName'] }} {{ $orderBatchDetail['pickupAddress']['pincode'] }}
                                        </p>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%" valign="top">
                        <table width="90%" cellpadding="5">
                            <tr>
                                <td style="border-bottom: 2px solid #000000;">
                                    <strong>{{__('admin.drop_address')}}</strong></td>
                            </tr>
                            <tr>
                                <td>
                                    @if($orderBatchDetail['serviceDropAddressKeyName'])
                                        <p style="line-height: 1.4;">
                                            @if($orderBatchDetail[$orderBatchDetail['serviceDropAddressKeyName']]['address_name'])
                                                {{$orderBatchDetail[$orderBatchDetail['serviceDropAddressKeyName']]['address_name']}}
                                                <br> @endif
                                            @if($orderBatchDetail[$orderBatchDetail['serviceDropAddressKeyName']]['address_line_1'])
                                                {{$orderBatchDetail[$orderBatchDetail['serviceDropAddressKeyName']]['address_line_1']}}
                                                <br> @endif
                                            @if($orderBatchDetail[$orderBatchDetail['serviceDropAddressKeyName']]['address_line_2'])
                                                {{$orderBatchDetail[$orderBatchDetail['serviceDropAddressKeyName']]['address_line_2']}}
                                                <br> @endif
                                            @if($orderBatchDetail[$orderBatchDetail['serviceDropAddressKeyName']]['district'])
                                                {{$orderBatchDetail[$orderBatchDetail['serviceDropAddressKeyName']]['district']}}
                                                <br> @endif
                                            @if($orderBatchDetail[$orderBatchDetail['serviceDropAddressKeyName']]['sub_district'])
                                                {{$orderBatchDetail[$orderBatchDetail['serviceDropAddressKeyName']]['sub_district']}}
                                                <br> @endif
                                            @if($orderBatchDetail[$orderBatchDetail['serviceDropAddressKeyName']]['cityName'])
                                                {{$orderBatchDetail[$orderBatchDetail['serviceDropAddressKeyName']]['cityName']}} @endif
                                            , {{ $orderBatchDetail[$orderBatchDetail['serviceDropAddressKeyName']]['stateName'] }} {{ $orderBatchDetail[$orderBatchDetail['serviceDropAddressKeyName']]['pincode'] }}

                                        </p>
                                    @else
                                        <p style="line-height: 1.4;">
                                            @if($orderBatchDetail['dropAddress']['address_name'])
                                                {{$orderBatchDetail['dropAddress']['address_name']}} <br> @endif
                                            @if($orderBatchDetail['dropAddress']['address_line_1'])
                                                {{$orderBatchDetail['dropAddress']['address_line_1']}} <br> @endif
                                            @if($orderBatchDetail['dropAddress']['address_line_2'])
                                                {{$orderBatchDetail['dropAddress']['address_line_2']}} <br> @endif
                                            @if($orderBatchDetail['dropAddress']['district'])
                                                {{$orderBatchDetail['dropAddress']['district']}} <br> @endif
                                            @if($orderBatchDetail['dropAddress']['sub_district'])
                                                {{$orderBatchDetail['dropAddress']['sub_district']}} <br> @endif
                                            @if($orderBatchDetail['dropAddress']['cityName'])
                                                {{$orderBatchDetail['dropAddress']['cityName']}} @endif
                                            , {{ $orderBatchDetail['dropAddress']['stateName'] }} {{ $orderBatchDetail['dropAddress']['pincode'] }}

                                        </p>
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
            <table width="100%" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif;">
                <tr>
                    <td><strong>{{__('admin.goods_type')}}
                            :</strong><br> {{ $orderBatchDetail['otherDetails']['goodsType'] }}</td>
                    <td><strong>{{__('admin.quincus_pickup_fleet')}}
                            :</strong><br> {{ $orderBatchDetail['otherDetails']['fleetType'] }}</td>
                    <td><strong>{{__('admin.goods_value')}}:</strong><br> {{ $orderBatchDetail['productAmount'] }}</td>
                    <td><strong>{{__('admin.insurance')}}:</strong><br> Yes</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" cellpadding="5" cellspecing="0" border="0">
                <thead>
                <tr>
                    <th width="23%" style="background-color: #002050; color: #fff; font-size: 14px;">
                        {{__('admin.description')}}
                    </th>
                    <th width="10%" style="background-color: #002050; color: #fff; font-size: 14px;">
                        {{__('admin.quantity')}}
                    </th>
                    <th width="25%" style="background-color: #002050; color: #fff; font-size: 14px;">
                        {{__('admin.weight')}}
                    </th>
                    <th width="10%" style="background-color: #002050; color: #fff; font-size: 14px;">
                        {{__('admin.lwh')}}
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($orderBatchDetail['receiptance']['orderItems'] as $productDetail)
                    <tr>
                        <td style="background-color: #F8F9FA; font-size: 14px;">
                            {{ $productDetail['productDescription'] }}
                        </td>
                        <td style="background-color: #F8F9FA; text-align: center; font-size:14px; white-space: nowrap;">
                            {{ $productDetail['product_quantity'] }}
                        </td>
                        <td
                            style="background-color: #F8F9FA; text-align: center; font-size:14px; white-space: nowrap;">
                            {{ $productDetail['weights'] }}
                        </td>
                        <td style="background-color: #F8F9FA; text-align: center; font-size:14px; white-space: nowrap;">
                            {{ $productDetail['length'] }}*{{ $productDetail['width'] }}*{{ $productDetail['height'] }}
                        </td>

                    </tr>
                @endforeach

                </tbody>

            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif;">
                <tr>
                    <td>
                        <p><strong>{{__('admin.goods_description')}}:</strong><br>
                            {{ $orderBatchDetail['otherDetails']['goodsDescription'] }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="100%" cellpadding="3" cellspecing="0" border="0" style="font-family: Arial, Helvetica, sans-serif;">
    <tr>
        <td align="center" style="background-color: #72727e;  color: #d4d2df; height: 10px">
            <p style="font-size: 14px;">Powered by Blitznet. Copyright Reserved.</p>
        </td>
    </tr>
</table>
</body>

</html>
