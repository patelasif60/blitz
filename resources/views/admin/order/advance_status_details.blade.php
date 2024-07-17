@php
    
    //'Order Returned','Order Cancelled'
    if($order->payment_type == 0)
    {
        $hideStatusIds = [6, 7,10,11,12];
    }
    else{
        $hideStatusIds = [6,7,10,2,12];
        if($order->full_quote_by == 3)
        {
            $hideStatusIds = [6,7,10,2,3,11];
        } 
    }                
@endphp
<div class="card-body p-3">
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
                        if ($order->order_status == 7)
                            $extraClass = '';
                    }elseif ($orderStatus->order_status_id == 7){//"Order Cancelled"
                        $extraClass = ' finalOrderStatusBlock';
                    }
                }else{
                    continue;
                }
                @endphp
                <li class="{{ $class }}">
                    <h6 class="mb-0 d-table">
                        <div class="d-table-cell align-top"><a href="javascript:void(0)">{{ __('order.'.trim($orderStatus->status_name)) }}</a></div>
                        @if($orderStatus->order_status_id == 3)
                        {!! paymentView($order) !!}
                        @endif
                    </h6>
                    <p class="mb-4">
                        <small>{{ $orderStatus->created_at && $class == 'active' ? changeDateTimeFormat($orderStatus->created_at) : '' }}</small>
                    </p>
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
                    if (($orderStatus->order_status_id == 5) && ($order->order_status == 6||$order->order_status == 7)){//'Delivered' && ('Order Returned' || 'Order Cancelled')
                        continue;
                    }
                    $class = '';
                    if ($order->order_status >= $orderStatus->order_status_id && $orderStatus->order_track_id) {
                        $class = 'active';
                    }elseif ($orderStatus->order_status_id==10 && $orderStatus->order_track_id){
                        $class = 'active';
                    }elseif ($orderStatus->order_status_id==11 && $orderStatus->order_track_id){
                        $class = 'active';
                    }elseif ($orderStatus->order_status_id==12 && $orderStatus->order_track_id){
                        $class = 'active';
                    }
                    elseif ($order->is_credit==0 && !isset($order->request_days_status) && $orderStatus->order_status_id==10){
                        continue;
                    }
                    elseif ($order->payment_type == 0 && $orderStatus->order_status_id==8){
                        continue;
                    }
                }
                @endphp
                <li class="{{ $class}}">
                    {{--<h6 class="mb-0 "><a href="javascript:void(0)">{{ $orderStatus->status_name }}</a></h6>--}}
                    <h6 class="mb-0 d-table">
                        <div class="d-table-cell align-top"><a href="javascript:void(0)">{{ __('order.'.trim($orderStatus->status_name)) }}</a>

                        @if($order->payment_type != 0 && $order->order_status >= 1 && ($orderStatus->order_status_id == 11 || $orderStatus->order_status_id == 12))
                            @if(!$order->upload_order_doc && (Auth::user()->role_id == 3 || Auth::user()->role_id == 1))
                            <a href="javascript:void(0)" class="js-openmodel" data-id="{{$order->id}}">
                                <img src="{{ URL::asset('front-assets/images/icons/upload.png') }}" width="14px" height="14px" class="ms-1" alt=""> 
                            </a>
                            @endif
                        @endif

                        @if($order->payment_type != 0  && ($orderStatus->order_status_id == 11 || $orderStatus->order_status_id == 12) && $order->upload_order_doc)
                            <a class="" href="{{ url($order->upload_order_doc?? '') }}" title="{{$order->upload_order_doc_filename}}" download><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a>
                            @if($order->closeflag <= 9)
                                 <span class="removeFile"  data-id="{{$order->id}}" file-path="{{$order->upload_order_doc}}" data-name="upload_order_doc" data-type="lo_doc">
                                        <a href="javascript:void(0);" title="{{ __('admin.docfile.remove') }}"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2">
                                        </a>
                                </span>
                            @endif
                        @endif    
                        </div>
                        @if($orderStatus->order_status_id  == 3)
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