<!-- loop start -->
@if(count($allnotification)>0)
    @foreach($allnotification as $key => $values)
        <p class="fw-bold mb-2" style="font-size: 0.9rem;">{{ \Carbon\Carbon::today()->format('d-m-yy') == \Carbon\Carbon::parse($key)->format('d-m-yy') ? 'Today' : \Carbon\Carbon::parse($key)->format('d M Y') }}</p>
        <div class="">
            <ul class="mb-0">
            @foreach($values as $value)
                @php
                    $commanData = json_decode($value->common_data);
                    $commanNumber = '';
                    $rfq_id = 0;
                    $icons = $commanData->icons??'fa-gear';
                    $userName = $commanData->updated_by??'';
                    if ($value->notification_type == 'rfq'){
                        $notificationFunction = "notificationStore('".trim($value->notification_type)."',".$value->notification_type_id.")";
                        if (!empty($commanData->rfq_number)){
                           $commanNumber = '<a href="javascript:Void(0)" onclick="'.$notificationFunction.'">'.$commanData->rfq_number.'</a>';
                        } else {
                            $commanNumber = '<a href="javascript:Void(0)" onclick="'.$notificationFunction.'">'.$commanData->quote_number.'</a>';
                        }
                    }
                    if ($value->notification_type == 'quote'){
                        $rfq_id = $commanData->rfq??0;
                        $id = $rfq_id ? $rfq_id :  $value->notification_type_id;
                        $type = $rfq_id? 'rfq' : trim($value->notification_type);
                        $notificationFunction = "notificationStore('".trim($type)."',".$id.",".$value->notification_type_id.")";
                        $commanNumber = '<a href="javascript:Void(0)" onclick="'.$notificationFunction.'">'.$commanData->quote_number.'</a>';
                        //$commanNumber = $commanData->quote_number;
                        $quoteValiteDate = $commanData->valid_till_date??'';
                    }
                    if ($value->notification_type == 'order'){
                        $notificationFunction = "notificationStore('".trim($value->notification_type)."',".$value->notification_type_id.")";
                        //$commanNumber = $commanData->order_number;
                        $commanNumber = '<a href="javascript:Void(0)" onclick="'.$notificationFunction.'">'.$commanData->order_number.'</a>';
                        $orderStatus = $commanData->order_status??'';
                        $orderDueDate = $commanData->payment_due_date??'';
                        $orderItemNumber = $commanData->order_item_number??'';
                    }
                    if ($value->notification_type == 'loan'){
                        $notificationFunction = "notificationStore('".trim($value->notification_type)."',".$value->notification_type_id.")";
                        //$commanNumber = '<a href="javascript:Void(0)" onclick="'.$notificationFunction.'">'.$commanData->loan_number.'</a>';
                        $loanStatus = $commanData->status??'';
                    }
                    if ($value->notification_type == 'limit'){
                        $notificationFunction = "notificationStore('".trim($value->notification_type)."',".$value->notification_type_id.")";
                        $loanStatus = $commanData->status??'';
                    }
                @endphp
                <li class="{{ ($value->is_show == 0)? 'recent': '' }}">
                    <div class="p-2 userActivityPageRedirect d-flex align-items-center" data-id="{{ $rfq_id ? $rfq_id : $value->notification_type_id }}" data-type="{{ $rfq_id ? 'rfq' : $value->notification_type }}" data-quote_id="{{ $rfq_id ? $value->notification_type_id : 0 }}">
                        <div class="pe-2 ">
                            <span class="icon_bg"><i class="fa {{ $icons }}"></i></span>
                        </div>
                        @if(isset($orderStatus) && !empty($orderStatus))
                            @if(empty($orderDueDate) && $value->translation_key == 'buyer_inner_status_change')
                                <div class="fw-bold"> {!! sprintf(__('dashboard.'.$value->translation_key), $commanNumber, $orderItemNumber, __('order.'.$orderStatus), $userName) !!}</div>
                            @else
                                <div class="fw-bold"> {!! sprintf(__('dashboard.'.$value->translation_key), $commanNumber, sprintf(__('order.'.$orderStatus), $orderDueDate), $userName) !!}</div>
                            @endif
                        @elseif (!empty($quoteValiteDate))
                            <div class="fw-bold"> {!! sprintf(__('dashboard.'.$value->translation_key), $commanNumber, $quoteValiteDate, $userName) !!} </div>
                        @elseif ($value->notification_type == 'other')
                            <div class="fw-bold"> {!! sprintf(__('dashboard.'.$value->translation_key), $userName) !!} </div>
                        @elseif ($value->notification_type == 'loan' || $value->notification_type == 'limit')
                        
                            @if($value->notification_type == 'loan')
                                <div class="fw-bold">{{__('admin.loan')}} <a href="javascript:void(0);" onclick="{{$notificationFunction}}">{{$commanData->loan_number}}</a> {!! sprintf(__('dashboard.'.$value->translation_key), $userName == "admin admin" ? "Blitznet Team" : $userName ) !!}</div>
                            @elseif($value->notification_type == 'limit')
                            
                                <div class="fw-bold">{{__('admin.limit')}} <a href="javascript:void(0);" onclick="{{$notificationFunction}}">{{$commanData->limit_number}}</a> {!! sprintf(__('dashboard.'.$value->translation_key), $userName == "admin admin" ? "Blitznet Team" : $userName ) !!}</div>
                            @endif
                        @else
                            <div class="fw-bold"> {!! sprintf(__('dashboard.'.$value->translation_key), $commanNumber, $userName) !!} </div>
                        @endif
                        <small class="fw-bold ms-auto"><span><i class="fa fa-clock-o pe-1"></i></span>{{ date('d-m-Y H:i:s', strtotime($value->created_at)) }}</small>
                    </div>
                </li>
            @endforeach
            </ul>
        </div>
    @endforeach
@else
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row ">
                    <div class="col-auto d-flex flex-grow-1 justify-content-center ">
                        <p class="h6 mb-0">{{ __('admin.no_notification') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endif
<!-- loop end -->
