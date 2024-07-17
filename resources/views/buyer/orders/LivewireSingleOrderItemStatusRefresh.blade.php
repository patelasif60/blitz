@php
    $hideStatusIds = [7, 8, 9];
    if ($countProcess == 8){
        $showPrgressArray = ['1' => 6.4,'2'=>18.8,'3'=>31.5,'4'=>44,'5'=>55.2,'6'=>67.2,'8' => 68.5, '9'=>85.1,'10'=>100];
        $showPrgress = !empty($orderItem->order_item_status_id)?$showPrgressArray[$orderItem->order_item_status_id]:0;
    } else {
        $showPrgressArray = ['1' => 8.8,'2'=>25.8,'3'=>42.3,'4'=>55,'5'=>69.2,'6'=>84.1, '7' => 84.1, '8'=>84.1, '9'=>84.1, '10'=>100];
        $showPrgress = !empty($orderItem->order_item_status_id)?$showPrgressArray[$orderItem->order_item_status_id]:0;
    }
@endphp
    <td colspan="4" class="p-1">
        <div class="card" style="left: 0px;">
            <div class="card-body p-1 px-2">
                <div class="mb-2 d-flex align-items-center">
                        @if(!in_array($orderItemCategoryId,\App\Models\Category::SERVICES_CATEGORY_IDS) && ($orderlogisticProvided->logistic_check==1 && $orderlogisticProvided->logistic_provided ==0))
                        <div class="fw-bold" style="font-size: 12px;">{{ __('order.track_order') }} : {{ $order->order_number  }}</div>
                        @else
                            <div class="fw-bold" style="font-size: 12px;">{{ __('order.track_order') }} : {{ $orderItem->order_item_number  }}</div>
                    @endif
                </div>
                <div class="track p-1 py-2">
                    <div class="progress display-progress w-100 mt-4" >
                        <div id="dynamic" class="progress-bar progress-bar-striped progress-bar-animated  itemStatusProgress{{ $orderItem->order_id }}" role="progressbar" style="width: {{ $showPrgress }}%;" aria-valuenow="{{$showPrgress}}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-table w-100">
                        @foreach($allOrderStatus as $orderStatus)
                            @if (in_array($orderStatus->order_item_status_id, $hideStatusIds))
                                @php
                                    $class = 'invisible';
                                    $isHidden = 'd-none';
                                    if ($orderStatus->order_track_id) {
                                        $class = '';
                                        if (in_array($orderStatus->order_item_status_id, [7,8])){//'QC Failed', 'QC Passed'
                                           continue;
                                        }elseif ($orderStatus->order_item_status_id==9){
                                            $isHidden = '';
                                        }elseif ($orderStatus->order_item_status_id==$orderItem->order_item_status_id){
                                            $class = 'invisible';
                                        }
                                    }else{
                                        continue;
                                    }
                                @endphp
                                <div class="step d-table-cell {{$isHidden}}">
                                    <div class="icon">
                                        <i class="fa fa-flag {{ $class??'' }}"></i>
                                    </div>
                                    <div class="text">{{ __('order.'.trim($orderStatus->status_name)) }} <p class="text-center  text-muted">{{ $orderStatus->created_at ? newChangeDateTimeFormat($orderStatus->created_at,$order->orderDateSetting) : '' }}</p></div>
                                </div>
                            @else
                                @php
                                    $class = 'invisible';
                                    if ($orderStatus->order_track_id){
                                        $class = '';
                                        if ($orderStatus->order_item_status_id==10){
                                            $isHidden = '';
                                        }elseif ($orderStatus->order_item_status_id==$orderItem->order_item_status_id){
                                            $class = 'invisible';
                                        }
                                    }
                                    $isRadioBtnDisabled = 1;
                                    $radio1 = '';
                                    $radio2 = '';
                                @endphp
                                @if($orderStatus->order_item_status_id == 6)
                                        @php
                                            if(!in_array($orderItemCategoryId,\App\Models\Category::SERVICES_CATEGORY_IDS) && ($orderlogisticProvided->logistic_check==1 && $orderlogisticProvided->logistic_provided ==0)){
                                                if ($orderItem->order_item_status_id == 6) {
                                                    $isRadioBtnDisabled = 0;
                                                } else if ($orderItem->order_item_status_id == 10) {
                                                    $radio1 = "checked";
                                                } else if ($orderItem->order_item_status_id == 9) {
                                                    $radio2 = "checked";
                                                }
                                            }
                                            else
                                            {
                                                if(isset($order->orderItemTracksStatus[$orderItem->id]))
                                                {
                                                    $status_check= $order->orderItemTracksStatus[$orderItem->id];
                                                    if($status_check == 6) {
                                                        $isRadioBtnDisabled = 0;
                                                    }                                                                           else if ($status_check == 8) {
                                                        $radio1 = 'checked';
                                                    }
                                                    else if ($status_check == 7) {
                                                        $radio2 = 'checked';
                                                    }                 
                                                }
                                            }
                                        @endphp
                                   
                                    <div class="step d-table-cell">
                                        <div class="icon text-center"><i class="fa fa-flag {{ $class??'' }}"></i></div>
                                        <div class="text"><span>{{ __('order.'.trim($orderStatus->status_name)) }} </span>
                                            <span class="d-flex justify-content-center pb-1 {{Auth::user()->hasPermissionTo('edit buyer orders') != true ? 'd-none' : ''}}">
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input  radio-custom qcStatusUpdated"
                                                        type="radio" name="qcStatusOptions{{$orderItem->id}}"
                                                        id="qcpass{{$orderItem->id}}" data-upload-doc="{{$order->upload_order_doc}}" data-payment-type="{{$order->payment_type}}" data-order-id="{{$orderItem->order_id}}" data-orderitem-id="{{$orderItem->id}}" value="1"
                                                        {{$radio1}} {{$isRadioBtnDisabled?'disabled':''}} data-is-service="{{!in_array($orderItemCategoryId,\App\Models\Category::SERVICES_CATEGORY_IDS) && ($orderlogisticProvided->logistic_check==1 && $orderlogisticProvided->logistic_provided ==0) ? 1 : 0 }}">
                                                    <label class="form-check-label" for="">Pass</label>
                                                </div>
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input radio-custom qcStatusUpdated"
                                                        type="radio" name="qcStatusOptions{{$orderItem->id}}"
                                                        id="qcfail{{$orderItem->id}}" data-upload-doc="{{$order->upload_order_doc}}" data-payment-type="{{$order->payment_type}}" data-order-id="{{$orderItem->order_id}}" data-orderitem-id="{{$orderItem->id}}" value="2"
                                                        {{$radio2}} {{$isRadioBtnDisabled?'disabled':''}}>
                                                    <label class="form-check-label" for="">Fail</label>
                                                </div>
                                            </span>
                                            <p class="text-center  text-muted">{{ $orderStatus->created_at ? newChangeDateTimeFormat($orderStatus->created_at,$order->orderDateSetting) : '' }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="step d-table-cell text-center">
                                        <div class="icon text-center"><i class="fa fa-flag {{ $class??'' }}"></i></div>
                                        <div class="text">{{ __('order.'.trim($orderStatus->status_name)) }}
                                            @if($orderStatus->order_item_status_id == 3 && $orderItem->order_item_status_id >= 3 && !empty($orderItem->order_latter))
                                            <a href="javascript:void(0);"
                                               onclick="downloadimg({{ $orderItem->id }}, 'order_latter', '{{ Str::substr($orderItem->order_latter, stripos($orderItem->order_latter, 'order_latter_') + 13) }}')"
                                               style="cursor: pointer;"><img src="{{ URL::asset('front-assets/images/icons/file-order-letter.png') }}" height="12" title="Download Order Letter"></a>
                                            @endif
                                            <p class="text-center text-muted">{{ $orderStatus->created_at ? newChangeDateTimeFormat($orderStatus->created_at,$order->orderDateSetting): '' }} </p>
                                            @if(in_array($orderItemCategoryId,\App\Models\Category::SERVICES_CATEGORY_IDS) && ($orderlogisticProvided->logistic_check==1 && $orderlogisticProvided->logistic_provided ==0))
                                                @if($orderStatus->order_item_status_id == 2)
                                                    <button class="showAirwayBillModel btn btn-info btn-sm p-1 fw-normal @if($order->order_status != 4 || $orderItem->order_item_status_id!=1) d-none @endif" data-id="{{$orderItem->order_id}}" style="font-size: 10px;" id="generateAirwayBill{{$orderItem->order_id}}" type="button" role="button">
                                                        {{ __('admin.airway_bill_ganrate') }}</button>

                                                    @if(isset($orderAirwayBill))
                                                            <a class="btn btn-sm btn-warning px-2 ms-12 btn_airway_color text-nowrap downloadAirWayBill{{$orderAirwayBill->airwaybill_number}}"
                                                               style="font-size: 12px;"
                                                               onclick="downloadAirWayBill('{{ isset($orderAirwayBill->airwaybill_number) ? $orderAirwayBill->airwaybill_number : null }}')"
                                                               id="downloadAirWayBill{{$orderAirwayBill->airwaybill_number}}" title="{{__('admin.download_airwaybill')}}">{{$orderAirwayBill->airwaybill_number}}
                                                                <img
                                                                    src="{{ URL::asset('front-assets/images/icons/icon_download.png') }}"
                                                                    alt="{{__('admin.download_airwaybill')}}" class="pe-1" style="max-height: 12px;"></a>
                                                            <a id="shippingLabelPreview" download class="d-none"></a>
                                                    @endif
                                                @endif
                                             @endif
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </td>

