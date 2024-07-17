@if(($order->order_status==5 && !empty($disbursement)))
    <span class="badge badge-success">{{ __('admin.settled_payment') }}</span>
@else
    @php
    $totalOrderItems = $orderItems->count();
    $totalDeliveredItems = $orderItems->where('order_item_status_id',10)->count();
    $isAllItemDelivered = ($totalOrderItems===$totalDeliveredItems);
    $isStatusChangeDisabled = false;
    if($order->payment_type < 3){
        $isStatusChangeDisabled = ($order->order_status>=4 &&!$isAllItemDelivered);
    }

    @endphp

    @if($order->payment_type==1){{--supplier credit--}}
        <select id="creditOrderStatusChange"
                class="order-status-change form-select ps-2 w-100 text-primary"
                data-order-id="{{ $order->id }}"
                data-pickup-date="{{ isset($order->pickup_date) ? 1 : 0 }}"
                data-pickup-time="{{ isset($order->pickup_time) ? 1 : 0 }}"
                onchange="creditOrderStatusChange($(this))" {{($isStatusChangeDisabled||$order->order_status==5)?'disabled':''}}>
            @foreach ($creditOrderStatus as $status)
                @php
                $is_disabled = isCreditStatusChangeAllow($order->order_status,$status->id,$status->credit_sorting)?'':'disabled';
                $statusName = __('order.'.trim($status->name));
                @endphp
                @if($orderCreditDay->status==0 && $status->credit_sorting>4 && $status->credit_sorting!=10)
                    @continue
                @elseif($status->id==11 || $status->id ==12)
                    @continue
                @endif
                <option value="{{ $status->id }}" {{ $order->order_status == $status->id ? 'selected' : '' }} {{$is_disabled}}>
                    @if($status->id==8)
                        {{ $order->payment_due_date?sprintf($statusName,changeDateFormat($order->payment_due_date,'d/m/Y')):sprintf($statusName,'DD/MM/YYYY') }}
                    @else
                        {{ $statusName }}
                    @endif
                </option>
            @endforeach
        </select>
    @elseif($order->payment_type==2){{--koinworks credit--}}
        <select id="creditOrderStatusChange"
                class="order-status-change form-select ps-2 w-100 text-primary"
                data-order-id="{{ $order->id }}"
                data-pickup-date="{{ isset($order->pickup_date) ? 1 : 0 }}"
                data-pickup-time="{{ isset($order->pickup_time) ? 1 : 0 }}"
                onchange="creditOrderStatusChange($(this))" {{($isStatusChangeDisabled||($isAllItemDelivered&&$order->order_status==4)||$order->order_status==5)?'disabled':''}}>
            @foreach ($creditOrderStatus as $status)
                @if($isCreditApproved==0 && $status->credit_sorting>4 && $status->id!=7)
                    @continue
                @elseif($status->id==11 || $status->id ==12)
                    @continue
                @endif
                @php
                    $is_disabled = isCreditStatusChangeAllow($order->order_status,$status->id,$status->credit_sorting)?'':'disabled';
                    $statusName = __('order.'.trim($status->name));
                @endphp
                <option value="{{ $status->id }}" {{ $order->order_status == $status->id ? 'selected' : '' }} {{$is_disabled}}>
                        {{ $statusName }}
                </option>
            @endforeach
        </select>
    @else
        <select id="orderStatusChange"
                class="order-status-change form-select ps-2 w-100 text-primary"
                data-order-id="{{ $order->id }}"
                data-pickup-date="{{ isset($order->pickup_date) ? 1 : 0 }}"
                data-pickup-time="{{ isset($order->pickup_time) ? 1 : 0 }}"
                onchange="orderStatusChange($(this))" {{(($isStatusChangeDisabled && $order->order_status!=10)||$order->order_status==5)?'disabled':''}}>
                @php
                if($order->payment_type == 0)
                {
                    $hideStatus = [6,8,9,10,11,12];
                }
                else{
                    $hideStatus = [6,8,9,10,2,12];
                    if(isset($order->quote->getUser->role_id) && $order->quote->getUser->role_id == 3)
                    {
                        $hideStatus = [3,6,8,9,10,2,11];
                    }
                }
                @endphp
            @foreach ($orderStatus as $status)
                @php
                    $is_disabled = isAdvanceStatusChangeAllow($order->order_status,$status->id,$status->show_order_id)?'':'disabled';
                    $statusName = __('order.'.trim($status->name));
                @endphp
                @if(in_array($status->id, $hideStatus))
                    @continue
                @else
                    @if( $order->order_status == 10 && $status->id == 2 )
                        <option value="{{ $status->id }}" selected {{$is_disabled}}>
                            {{ $statusName }}
                        </option>
                    @else
                    <option value="{{ $status->id }}" {{ $order->order_status == $status->id ? 'selected' : '' }} {{$is_disabled}}>
                        {{ $statusName }}
                    </option>
                    @endif
                @endif
            @endforeach
        </select>
    @endif
@endif
