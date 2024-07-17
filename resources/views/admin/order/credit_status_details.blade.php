@php
    //'Order Returned','Order Cancelled'
    $hideStatusIds = [6,7,11,12];
@endphp
<div  class="card-body p-3">
    <ul class="bullet-line-list mb-0" id="orderStatusViewBlock{{ $orderId }}">
        @foreach ($order->orderAllStatus as $orderStatus)
            @if (in_array($orderStatus->order_status_id, $hideStatusIds))
                @php
                    $class = '';
                    $extraClass = '';
                    if ($orderStatus->order_track_id) {
                        $class = 'active';
                        if ($orderStatus->order_status_id == 6) {//'Order Returned'
                            $extraClass = ' finalOrderStatusBlock';
                            if ($order->order_status == 7)//"Order Cancelled"
                                $extraClass = '';
                        }elseif ($orderStatus->order_status_id == 7){//"Order Cancelled"
                            $extraClass = ' finalOrderStatusBlock';
                        }
                    }else{
                        continue;
                    }
                @endphp
                <li class="{{ $class.$extraClass }}">
                    <h6 class="mb-0 d-table">
                        <div class="d-table-cell align-top"><a href="javascript:void(0)">{{ __('order.'.trim($orderStatus->status_name)) }}</a></div>
                        @if($orderStatus->order_status_id == 3)
                            {!! paymentView($order) !!}
                        @endif
                    </h6>
                    <p><small>{{ $orderStatus->created_at && $class == 'active' ? changeDateTimeFormat($orderStatus->created_at) : '' }}</small></p>
                </li>
            @else
                @php
                    $class = '';
                    if ($order->order_status == 7){
                        $show_listing = $orderTracks->toArray();
                        if (in_array($orderStatus->order_status_id, $show_listing)){
                            $class = 'active';
                        } else {
                            continue;
                        }
                    } else{
                        if (($orderStatus->order_status_id == 3 || $orderStatus->order_status_id == 5 || $orderStatus->order_status_id == 8) &&
                            ($order->order_status == 6||$order->order_status == 7)){//('Payment Done' || 'Order Completed' || 'Payment Due DD/MM/YYYY') && ('Order Returned' || 'Order Cancelled')
                             continue;
                        }elseif ($order->request_days_status==1 && $orderStatus->order_status_id==10){
                            continue;
                        }
                        $class = '';
                        if ($orderStatus->order_track_id) {
                            $class = 'active';
                        } else {
                            if ($orderStatus->order_status_id == 3) {//'Payment Done'
                                $class = 'paymentDoneBlock';
                                $paymentDone = 0;
                            } else {
                                $class = '';
                            }
                        }
                        $status_name = $orderStatus->status_name;
                        if ($orderStatus->order_status_id == 8) {//'Payment Due DD/MM/YYYY'
                            $orderStatus->status_name = $order->payment_due_date?sprintf(__('order.'.$status_name),changeDateFormat($order->payment_due_date,'d/m/Y')):sprintf(__('order.'.$status_name),'DD/MM/YYYY');
                        }
                    }
                @endphp

                <li class="{{ $class }}">
                    <h6 class="mb-0 d-table">
                        @if ($orderStatus->order_status_id == 8)
                            <div class="d-table-cell align-top"><a href="javascript:void(0)">{{ trim($orderStatus->status_name) }}</a></div>
                        @else
                            <div class="d-table-cell align-top"><a href="javascript:void(0)">{{ __('order.'.trim($orderStatus->status_name)) }}</a></div>
                        @endif
                        @if($orderStatus->order_status_id == 3)
                            {!! paymentView($order) !!}
                        @endif
                    </h6>
                    <p>
                        <small>{{ $orderStatus->created_at && $class == 'active' ? changeDateTimeFormat($orderStatus->created_at) : '' }}</small>
                    </p>
                </li>
            @endif
        @endforeach
    </ul>
</div>
