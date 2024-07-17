@php
    //'QC Failed', 'QC Passed', 'Order Troubleshooting'
    $hideStatusIds = [7, 8, 9];
@endphp
<div class="modal-header d-flex"
     style="background-color: #d7d9df; padding: 0.5rem 0.5rem; ">
    <div class="p-2 verticle-center">
        <img src="{{URL::asset('assets/icons/truck-moving.png')}}"
             alt="Track Order" class="pe-2"><strong class="text-dark ps-2">{{$orderItem->order_item_number}}</strong>
    </div>
    <div class="ms-auto">
        <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
    </div>
</div>
<div class="modal-body p-3" >
<h4 class="mb-3">{{get_product_name_by_id($orderItem->rfq_product_id,1)}}</h4>
<ul class="bullet-line-list mb-0 order-item-status-view" id="orderStatusViewBlock{{$orderItem->id}}">
    @foreach($allOrderStatus as $orderStatus)
        @if (in_array($orderStatus->order_item_status_id, $hideStatusIds))
            @php
                $class = '';
                $extraClass = '';
                if ($orderStatus->order_track_id) {
                    $class = 'active';
                    if (in_array($orderStatus->order_item_status_id, [7,8])){//'QC Failed', 'QC Passed'
                       continue;
                    }
                }else{
                    continue;
                }
            @endphp
            <li class="{{ $class }}">
                <h6 class="mb-0 d-table">
                    <div class="d-table-cell align-top"><a href="javascript:void(0)">{{ __('order.'.trim($orderStatus->status_name)) }}</a></div>
                </h6>
                <p>
                    <small>{{ $orderStatus->created_at && $class == 'active' ? changeDateTimeFormat($orderStatus->created_at) : '' }}</small>
                </p>
            </li>
        @else
            @php
                $class = '';
                if ($orderStatus->order_track_id){
                    $class = 'active';
                }
            @endphp
            <li class="{{$class}}">
                <h6 class="mb-0 d-table">
                    <div class="d-table-cell align-top"><a href="javascript:void(0)">{{ __('order.'.trim($orderStatus->status_name)) }}</a></div>
                    @if($orderStatus->order_item_status_id == 6)
                        {!! underQcView($orderItem->id) !!}
                    @endif
                </h6>
                <p><small>{{ $orderStatus->created_at && $class == 'active' ? changeDateTimeFormat($orderStatus->created_at) : '' }}</small></p>
            </li>
        @endif
    @endforeach
</ul>
</div>
