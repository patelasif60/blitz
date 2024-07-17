@php
    $allProductsAmount = 0;
    $deliveryManageSeparately = $order->delivery_manage_separately;
    $changeOrderItemStatus = ($order->order_status===4);

    $logisticProvided = $quote->quoteItem->logistic_provided;
    $itemsManageSeparately = $order->items_manage_separately;
    $colspan = $itemsManageSeparately?6:5;
    $colspan = $deliveryManageSeparately?6:5;
    /*$isAllStatusSame = ($totalSameStatus===$orderItems->count());*/
    $logisticProvided = $quote->quoteItem->logistic_provided;
    $quotes = $order->quote()->first();
    $orderItemsIds = [];
@endphp

@if(isset($order_batches) && sizeof($order_batches) > 0)
    @foreach($order_batches as $batchData)
        @php
            $quoteItemsId = $order->orderItems()->where('order_batch_id',$batchData->id)->pluck('quote_item_id');
            $allProductsAmount = $order->quote->quoteItem()->whereIn('id',$quoteItemsId)->sum('product_amount');
            $batchitemsManageSeparately = $batchData->batch_item_manage_separately;
            $inbatchOrderItems = $order->orderItems()->where('order_batch_id',$batchData->id)->where('is_in_batch',1)->orderBy('order_item_number')->get();
            $batchtotalSameStatus = $order->orderItems()->where('order_batch_id',$batchData->id)->groupBy('order_item_status_id')->count();
            $isAllStatusSame = ($batchtotalSameStatus===$inbatchOrderItems->count());
            $batchUserRole = (\App\Models\User::find($batchData['created_by']))->roles->pluck('name')->first();
        @endphp
        @if($inbatchOrderItems->count()>0)
        <div class="accordion" id="accordionExample_{{ $batchData->id }}">
            <div class="accordion-item my-2">
                <h2 class="accordion-header" id="headingOne_{{ $batchData->id }}">
                    <button class="accordion-button orderBatchCollapse_{{ $batchData->id }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne_{{ $batchData->id }}" aria-expanded="true" aria-controls="collapseOne_{{ $batchData->id }}">
                        <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/people-carry-1.png')}}" alt="Supplier Details" class="pe-2">
                            <span>{{ $batchData->order_batch }}</span>
                        </h5>
                        <div class="ms-auto">
                            <small style=" font-size: 14px; font-weight: bold;">{{ __('admin.quantity') }}:
                                <span class="fw-normal" style=" font-size: 12px;">{{ count(json_decode($batchData->order_item_ids)) }} {{ __('admin.products') }}</span>
                            </small>
                        </div>
                        <div class="ms-3">
                            <small style=" font-size: 14px; font-weight: bold;">{{ __('admin.amount') }}:
                                <span class="fw-normal" style=" font-size: 12px;">Rp {{ number_format($allProductsAmount,2) ?? 0.00 }}</span>
                            </small>
                        </div>
                    </button>
                </h2>
                <div id="collapseOne_{{ $batchData->id }}" class="accordion-collapse collapse {{ ($loop->first) ? 'show' : '' }}" aria-labelledby="headingOne_{{ $batchData->id }}" data-bs-parent="#accordionExample_{{ $batchData->id }}">
                    <div class="accordion-body">
                        <div class="row rfqform_view bg-white pb-2 align-items-center">
                            <div class="quincus_chunk d-flex mb-3">
                                <div class="chunk-details col-md-6">
                                        @if(isset($batchData->getAirWayBillNumber->airwaybill_number))
                                            <div class="col-md-12 pb-2">
                                                <label>{{ __('admin.airwaybill_number') }}:</label>
                                                <div class="d-flex align-items-center">{{ isset($batchData->getAirWayBillNumber->airwaybill_number) ? $batchData->getAirWayBillNumber->airwaybill_number : '' }}
                                                    <a onclick="downloadAirWayBill('{{ isset($batchData->getAirWayBillNumber->airwaybill_number) ? $batchData->getAirWayBillNumber->airwaybill_number : null }}')" id="downloadAirWayBill{{ $batchData->getAirWayBillNumber->airwaybill_number }}" class="ms-2" title="{{__('admin.download_airwaybill')}}">
                                                        <svg id="Layer_1" width="16px" fill="#0000FF" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81">
                                                            <path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)">
                                                            </path>
                                                            <path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <a id="shippingLabelPreview" download class="d-none"></a>
                                                </div>
                                            </div>
                                            <div class="col-md-12 pb-2">
                                                <label>{{ __('admin.pickup_address') }}:</label>
                                                <div class="text-dark">
                                                    <a class="text-decoration-none btn btn-primary p-1 fw-bold"
                                                       href="{{ route('order-edit-download-airway-bill',$batchData->getAirWayBillNumber->id) }}">
                                                        <svg id="Layer_1" width="12px" fill="#fff"
                                                             data-name="Layer 1"
                                                             xmlns="http://www.w3.org/2000/svg"
                                                             viewBox="0 0 383.26 408.81">
                                                            <path
                                                                d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z"
                                                                transform="translate(-64.37 -51.59)"></path>
                                                            <path
                                                                d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z"
                                                                transform="translate(-64.37 -51.59)"></path>
                                                        </svg>
                                                        {{ __('admin.download_pick_up_address') }}
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    <div class="col-md-12 pb-2">
                                        <label>{{ __('dashboard.date_time') }}:</label>
                                        <div>{{ changeDateTimeFormat($batchData->order_pickup) }}</div>
                                    </div>
                                </div>
                                <div class="chunk-address col-md-6 pb-2">
                                    <label>{{ __('admin.Address') }}:</label>
                                    <div>
                                        @if(isset($batchUserRole) && ($batchUserRole=='buyer' || $batchUserRole=='sub-buyer'))
                                            <span>{{ $batchData->getUserAddress->address_line_1 . ' , ' . $batchData->getUserAddress->address_line_2 }}</span> <br>
                                            <span>{{ isset($batchData->getUserAddress->city_id) && $batchData->getUserAddress->city_id != '-1' ? $batchData->getUserAddress->getCity()->name : '' }}</span>
                                            <span>{{ isset($batchData->getUserAddress->state_id) && $batchData->getUserAddress->state_id != '-1' ? $batchData->getUserAddress->getState()->name : '' }}</span> <br>
                                            {{ $batchData->getUserAddress->sub_district . ' , ' . $batchData->getUserAddress->district . ' , ' . $batchData->getUserAddress->pincode }}
                                        @else
                                            <span>{{ $batchData->getSupplierAddress->address_line_1 . ' , ' . $batchData->getSupplierAddress->address_line_2 }}</span> <br>
                                            <span>{{ isset($batchData->getSupplierAddress->city_id) && $batchData->getSupplierAddress->city_id != '-1' ? $batchData->getSupplierAddress->getCity()->name : '' }}</span>
                                            <span>{{ isset($batchData->getSupplierAddress->state_id) && $batchData->getSupplierAddress->state_id != '-1' ? $batchData->getSupplierAddress->getState()->name : '' }}</span> <br>
                                            {{ $batchData->getSupplierAddress->sub_district . ' , ' . $batchData->getSupplierAddress->district . ' , ' . $batchData->getSupplierAddress->pincode }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if(empty($batchitemsManageSeparately))
                                <div class="col-md-4 pb-2">
                                    <label class="form-label" style="font-size: 0.8em;">{{__('admin.status_for_all_order_items')}}: </label>
                                    <div class="input-group flex-nowrap">
                                        <select id="all_items_status_change_{{$batchData->id}}"
                                                class="allItemsStatusChange all-items-status-change form-select ps-2 w-auto text-primary"
                                                data-order_latter="1"
                                                data-batch-id="{{ $batchData->id }}"
                                                data-order-id="{{$order->id}}"
                                                onchange="orderItemStatusChange($(this))"
                                                {{$changeOrderItemStatus?'':'disabled'}}
                                                {{$isAllStatusSame?'':'disabled'}}>
                                            @if(empty($inbatchOrderItems[0]->order_item_status_id))
                                                <option value="">{{__('admin.select')}}</option>
                                            @endif
                                            @foreach($orderItemStatuses as $orderItemStatus)
                                                @php
                                                    $isAllowToChangeStatus = isOrderItemStatusChangeAllow($orderItemStatus->id,$logisticProvided);
                                                    $activeOrderStatusID = $inbatchOrderItems[0]->order_item_status_id==6 ? [$inbatchOrderItems[0]->order_item_status_id + 1,$inbatchOrderItems[0]->order_item_status_id + 2] : [$inbatchOrderItems[0]->order_item_status_id + 1];
                                                @endphp
                                                <option value="{{$orderItemStatus->id}}" {{$inbatchOrderItems[0]->order_item_status_id==$orderItemStatus->id?'selected':''}} {{$isAllowToChangeStatus?'':'disabled'}} {{(in_array($orderItemStatus->id,$activeOrderStatusID)  || $inbatchOrderItems[0]->order_item_status_id==$orderItemStatus->id)?'':'disabled'}}>
                                                    {{__('order.'.trim($orderItemStatus->name))}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 pb-2">
                                    <label class="form-label" style="font-size: 0.8em;">{{__('admin.upload_order_letter')}}: </label>
                                    <div>
                                        <form method="post" id="batch_offerlattterform_{{$inbatchOrderItems[0]->order_batch_id}}" class="d-flex" action="{{ route('order-latter-upload') }}" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$inbatchOrderItems[0]->id}}">
                                            <input type="hidden" id="batch_id" name="batch_id" value="{{$inbatchOrderItems[0]->order_batch_id}}">
                                            <input type="hidden" name="is_in_batch" value="1">
                                            <span class="">
                                                <input type="file" name="order_latter" id="image-order_latter_{{$inbatchOrderItems[0]->order_batch_id}}" accept=".jpg,.png,.pdf" @if(Auth::user()->hasRole('jne')) disabled="disabled" @endif onchange="showOrderLatter(this)" hidden="" {{$changeOrderItemStatus?'':'disabled'}}>
                                                <label id="upload_btn" for="image-order_latter_{{$inbatchOrderItems[0]->order_batch_id}}" @if(Auth::user()->hasRole('jne')) style="background-color: rgb(112, 120, 137);" @endif>{{ __('admin.browse') }}</label>
                                            </span>
                                        <div id="file-order_latter_{{$inbatchOrderItems[0]->id}}">
                                            @if ($inbatchOrderItems[0]->order_latter)
                                                @php
                                                    $extension_order= substr($inbatchOrderItems[0]->order_latter, -4);
                                                    $filename = substr(Str::substr($inbatchOrderItems[0]->order_latter, stripos($inbatchOrderItems[0]->order_latter, 'order_latter_') + 13), 0, -4);
                                                    if(strlen($filename) >= 10){
                                                        $order_name = substr($filename,0,10).'...'.$extension_order;
                                                    } else {
                                                        $order_name = $filename.$extension_order;
                                                    }
                                                @endphp
                                                <input type="hidden" class="form-control" data-batch-id="{{$inbatchOrderItems[0]->order_batch_id}}" id="oldorder_latter{{$inbatchOrderItems[0]->id}}" name="oldorder_latter" value="{{ $inbatchOrderItems[0]->order_latter }}">
                                                <span class="ms-2"><a href="javascript:void(0);" id="order_latterFileDownload{{$inbatchOrderItems[0]->id}}" onclick="downloadOrderLatter('{{$inbatchOrderItems[0]->id}}', '{{ Str::substr($inbatchOrderItems[0]->order_latter, stripos($inbatchOrderItems[0]->order_latter, "order_latter_") + 13) }}')" title="{{ Str::substr($inbatchOrderItems[0]->order_latter, stripos($inbatchOrderItems[0]->order_latter, 'order_latter_') + 13) }}" style="text-decoration: none;"> {{ $order_name }} </a></span>

                                                <span onclick="removeOrderLatter({{$inbatchOrderItems[0]->id}},1)" style="{{ $inbatchOrderItems[0]->order_item_status_id <= 2 ? '' : 'display:none' }}" id="order_latterFile{{$inbatchOrderItems[0]->id}}"><a href="javascript:void(0)" title="Remove Order Latter"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a></span>

                                                <span class="ms-2"><a class="order_latterFile" href="javascript:void(0);" title="Download Order Latter" onclick="downloadOrderLatter('{{$inbatchOrderItems[0]->id}}', '{{ Str::substr($inbatchOrderItems[0]->order_latter, stripos($inbatchOrderItems[0]->order_latter, "order_latter_") + 13) }}')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>
                                            @endif
                                        </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-4 pb-2">
                                    @if((auth()->user()->role_id == 1 || Auth::user()->hasRole('agent')) && $order->order_status == 4 && $inbatchOrderItems[0]->order_item_status_id >= 6)
                                    <div class="d-flex justify-content-end">
                                        <button type="button" id="manageBatchItemsSeparately" class="btn btn-sm btn-primary text-white py-1" role="button" onclick="manageBatchItemsSeparately($(this))" data-batch-id="{{ $batchData->id }}"><i class="fa fa-object-ungroup" aria-hidden="true"></i> {{__('admin.manage_order_items_separately')}}</button>
                                    </div>
                                    @endif
                                </div>
                            @endif

                            <div class="table-responsive quincus_table my-2">
                                <table class="table text-dark table-striped">
                                    <thead class="bg-light" style="position:sticky; top: -2px; z-index: 5">
                                        <tr class="bg-light">
                                            <th>{{__('admin.item_number')}}</th>
                                            <th>{{__('admin.description')}}</th>
                                            <th>{{__('admin.status_of_order_item')}}</th>
                                            @if(!empty($batchitemsManageSeparately))
                                                <th>{{__('admin.upload_order_letter')}}</th>
                                            @endif
                                            <th>{{__('admin.price')}}</th>
                                            <th>{{__('admin.qty')}}</th>
                                            <th>{{__('admin.weight')}} <small>({{ __('admin.per_unit') }})</small></th>
                                            <th align="right" class="text-end">{{__('admin.amount')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $isDisabledOrderItemStatus = true;
                                            if ($order->is_credit==1 && in_array($order->order_status,[3,4,5,8])){//credit and (Order in Progress,Payment Due on %s,Payment Done,Order Completed)
                                                $isDisabledOrderItemStatus = false;
                                            }elseif ($order->is_credit==0 && !in_array($order->order_status,[1,2,10])){//advanced and not (Order Received, Order Confirmed & Payment Pending, Credit Rejected)
                                                $isDisabledOrderItemStatus = false;
                                            }
                                            $orderItemsIds = [];
                                        @endphp
                                        @foreach($inbatchOrderItems as $orderItem)
                                            @php
                                                $quoteItem = $orderItem->quoteItem()->first();
                                                $unit = get_unit_name($quoteItem->price_unit);
                                                $orderItemsIds[] = $orderItem->id;
                                                $activeOrderItemStatusID = $orderItem->order_item_status_id ==6? [$orderItem->order_item_status_id + 1,$orderItem->order_item_status_id + 2]:[$orderItem->order_item_status_id + 1];
                                            @endphp
                                            <tr>
                                                <td>{{$orderItem->order_item_number}}</td>
                                                <td>{{get_product_name_by_id($orderItem->rfq_product_id,1)}}</td>
                                                <td>
                                                    <div class="input-group flex-nowrap">
                                                        <select id="item_status_change_{{$orderItem->id}}"
                                                                class="orderItemStatusChange order-item-status-change form-select ps-2 w-auto text-primary"
                                                                style="padding-right: 30px;"
                                                                data-order_latter="1"
                                                                data-batch-id="{{ $batchData->id }}"
                                                                onchange="orderItemStatusChange($(this),{{$orderItem->id}})"
                                                            {{$batchitemsManageSeparately?'':'disabled'}}
                                                            {{$changeOrderItemStatus?'':'disabled'}}>
                                                            @if(empty($orderItem->order_item_status_id))
                                                                <option value="">{{__('admin.select')}}</option>
                                                            @endif
                                                            @foreach($orderItemStatuses as $orderItemStatus)
                                                                    @php
                                                                        $isAllowToChangeStatus = isOrderItemStatusChangeAllow($orderItemStatus->id,$logisticProvided);
                                                                    @endphp
                                                                <option value="{{$orderItemStatus->id}}" {{$orderItem->order_item_status_id==$orderItemStatus->id?'selected':''}} {{$isAllowToChangeStatus?'':'disabled'}} {{(in_array($orderItemStatus->id,$activeOrderItemStatusID) || $orderItem->order_item_status_id==$orderItemStatus->id)?'':'disabled'}}>
                                                                    {{__('order.'.$orderItemStatus->name)}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="input-group-text addon-wrapping {{$isDisabledOrderItemStatus?'':'bg-white'}}" onclick="{{$isDisabledOrderItemStatus?'':'orderItemModal('.$orderItem->id.')'}}"><i class="fa fa-eye hand"></i></span>
                                                    </div>
                                                </td>
                                                @if(!empty($batchitemsManageSeparately))
                                                <td>
                                                    <form method="post" id="offerlattterform{{$orderItem->order_batch_id}}" action="{{ route('order-latter-upload') }}" enctype="multipart/form-data">
                                                        <div class="d-flex">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{$orderItem->id}}">
                                                            <input type="hidden" name="batch_id" value="{{$orderItem->order_batch_id}}">
                                                            <input type="hidden" name="is_all_upload" value="0">
                                                            <input type="hidden" name="is_in_batch" value="0">
                                                            <span class="">
                                                                <input type="file" data-batch-id="{{$orderItem->order_batch_id}}" @if(Auth::user()->hasRole('jne')) disabled="disabled" @endif name="order_latter" id="image-order_latter{{$orderItem->id}}" accept=".jpg,.png,.pdf" onchange="showOrderLatter(this)" hidden="" {{$changeOrderItemStatus?'':'disabled'}}>
                                                                <label id="upload_btn" for="image-order_latter{{$orderItem->id}}" @if(Auth::user()->hasRole('jne')) style="background-color: rgb(112, 120, 137);" @endif>{{ __('admin.browse') }}</label>
                                                            </span>
                                                            <div id="file-order_latter{{$orderItem->id}}">
                                                                @if ($orderItem->order_latter)
                                                                    @php
                                                                        $extension_order= substr($orderItem->order_latter, -4);
                                                                        $filename = substr(Str::substr($orderItem->order_latter, stripos($orderItem->order_latter, 'order_latter_') + 13), 0, -4);
                                                                        if(strlen($filename) >= 10){
                                                                            $order_name = substr($filename,0,10).'...'.$extension_order;
                                                                        } else {
                                                                            $order_name = $filename.$extension_order;
                                                                        }
                                                                    @endphp
                                                                    <input type="hidden" class="form-control" id="oldorder_latter{{$orderItem->id}}" name="oldorder_latter" value="{{ $orderItem->order_latter }}">
                                                                    <span class="ms-2"><a href="javascript:void(0);" id="order_latterFileDownload{{$orderItem->id}}" onclick="downloadOrderLatter('{{$orderItem->id}}', '{{ Str::substr($orderItem->order_latter, stripos($orderItem->order_latter, "order_latter_") + 13) }}')" title="{{ Str::substr($orderItem->order_latter, stripos($orderItem->order_latter, 'order_latter_') + 13) }}" style="text-decoration: none;"> {{ $order_name }} </a></span>

                                                                    <span onclick="removeOrderLatter({{$orderItem->id}})" style="{{ $orderItem->order_item_status_id <= 2 ? '' : 'display:none' }}" id="order_latterFile{{$orderItem->id}}" data-id="{{$orderItem->id}}" file-path="{{ $orderItem->order_latter }}" data-name="order_latter"><a href="#" title="Remove Order Latter"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2" style="height: auto;width: auto;"></a></span>

                                                                    <span class="ms-2"><a class="order_latterFile" href="javascript:void(0);" title="Download Order Latter" onclick="downloadOrderLatter('{{$orderItem->id}}', '{{ Str::substr($orderItem->order_latter, stripos($orderItem->order_latter, "order_latter_") + 13) }}')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </form>
                                                </td>
                                                @endif
                                                <td class="text-nowrap">Rp {{number_format($quoteItem->product_price_per_unit,2)}} per {{$unit}}</td>
                                                <td class="text-nowrap">{{$quoteItem->product_quantity}} {{$unit}}</td>
                                                <td class="text-nowrap">{{$quoteItem->weights}} </td>
                                                <td class="text-nowrap" align="right">Rp {{number_format($quoteItem->product_amount,2)}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach
@endif
