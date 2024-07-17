@php
    $itemsManageSeparately = $order->items_manage_separately;
    $deliveryManageSeparately = $order->delivery_manage_separately;
    //when order status order in progress then order item status allowed to change
    $changeOrderItemStatus = ($order->order_status===4);
    $colspan = $itemsManageSeparately?6:5;
    $colspan = $deliveryManageSeparately?6:5;
    //if all status is deleverd then disabled all order item status
    $isAllStatusSame = ($totalSameStatus===$orderItems->count());
    $logisticProvided = $quote->quoteItem->logistic_provided;
    $quotes = $order->quote()->first();
    $orderItemsIds = [];
    $activeOrderStatusID = $orderItems[0]->order_item_status_id + 1;
@endphp

<!-- New design for products and charges starts -->
<div class="table-responsive">
    <table class="table text-dark table-striped">
        <tbody>
            <tr class="bg-light">
                <th colspan="{{$colspan}}" class="fw-bold">{{ __('admin.product') }}</th>
                <th align="right" class="text-end fw-bold">{{ __('rfqs.total_price') }}</th>
            </tr>
            @php $allProductsAmount = 0; @endphp
            @foreach($orderItems as $orderItem)
                @php
                    $quoteItem = $orderItem->quoteItem()->first();
                    $unit = get_unit_name($quoteItem->price_unit);
                    $allProductsAmount += $quoteItem->product_amount;
                @endphp
            @endforeach
            <tr>
                <td colspan="{{$colspan}}">
                    <a href="javascript:void(0)" class="text-primary text-decoration-none" data-bs-toggle="modal" data-bs-target="#openProductsModal"
                    title="{{ __('dashboard.view_details') }}">{{ count($orderItems) }} {{ __('admin.product') }}</a>
                </td>
                <td align="right">Rp {{ number_format($allProductsAmount,2) ?? 0.00 }}</td>
            </tr>
            <!-- Common Charges -->
            @foreach ($amountDetails as $charges)
            <tr>
                @if ($charges->type == 0)
                    <td colspan="{{$colspan}}">{{ $charges->charge_name . ' ' . $charges->charge_value }}
                        %
                    </td>
                @else
                    <td colspan="{{$colspan}}">{{ $charges->charge_name }}</td>
                @endif
                <td align="right">
                    {{ $charges->addition_substraction == 0 ? '- ' : '+ ' }}{{ 'Rp ' . number_format($charges->charge_amount, 2) }}
                </td>
            </tr>
            @endforeach
            <tr>
                <td colspan="{{$colspan}}">{{ __('admin.tax') }} {{ $quote->tax .'%'}}</td>
                <td align="right">
                    + Rp
                    @if(auth()->user()->role_id == 3)
                        {{ number_format($quote->supplier_tex_value, 2) }}
                    @else
                        {{number_format($quote->tax_value, 2)}}
                    @endif
                </td>
            </tr>
            @php
                $billedAmount = $quote->final_amount;
                $bulkOrderDiscount = 0;
                if (auth()->user()->role_id != 3){
                    $bulkOrderDiscount = getBulkOrderDiscount($order->id);
                }
            @endphp
            @if($bulkOrderDiscount>0)
                @php
                    $billedAmount = $billedAmount-$bulkOrderDiscount;
                @endphp
                <tr>
                    <td colspan="{{$colspan}}">
                        {{ __('admin.bulk_payment_discount') }}
                    </td>
                    <td align="right">
                        {{ '- Rp ' . number_format($bulkOrderDiscount, 2) }}
                    </td>
                </tr>
            @endif
            <tr class="bg-secondary text-white">
                <td colspan="{{$colspan}}" class="text-white fw-bold">{{ __('admin.amount_to_pay') }}</td>
                <td align="right" class="text-white fw-bold">Rp
                    @if(auth()->user()->role_id == 3)
                        {{number_format($quote->supplier_final_amount, 2)}}
                    @else
                        {{number_format($billedAmount, 2)}}
                    @endif
                </td>
            </tr>
            <!-- Common Charges End -->
        </tbody>
    </table>
</div>

<!-- Show product details popup modal -->
<div class="modal version2 fade" id="openProductsModal" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-labelledby="openProductsModalLabel">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header py-3">
                <h5 class="modal-title">{{ __('admin.product_details') }}</h5>
                <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{URL::asset('front-assets/images/icons/times.png')}}" alt="Close">
                </button>
            </div>
            <div class="modal-body bg-white py-2">
                <div class="tooltip_section text-dark text-start p-1 multi_pro_list">
                    <div class="table-responsive">
                        <table class="table text-dark table-striped">
                            <thead>
                                <tr class="bg-light">
                                    <th>{{__('admin.item_number')}}</th>
                                    <th>{{__('admin.description')}}</th>
                                    <th>{{__('admin.price')}}</th>
                                    <th>{{__('admin.qty')}}</th>
                                    <th>{{__('admin.weight')}} <small>({{ __('admin.per_unit') }})</small></th>
                                    <th align="right" class="text-end text-nowrap">{{__('admin.amount')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderItems as $orderItem)
                                    @php
                                        $quoteItem = $orderItem->quoteItem()->first();
                                        $unit = get_unit_name($quoteItem->price_unit);
                                    @endphp
                                    <tr>
                                        <td>{{$orderItem->order_item_number}}</td>
                                        <td>{{get_product_name_by_id($orderItem->rfq_product_id,1)}}</td>
                                        <td>Rp {{number_format($quoteItem->product_price_per_unit,2)}} per {{$unit}}</td>
                                        <td>{{$quoteItem->product_quantity}} {{$unit}}</td>
                                        <td>{{$quoteItem->weights}}</td>
                                        <td align="right" class="text-nowrap">Rp {{number_format($quoteItem->product_amount,2)}}</td>
                                    </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer px-3">
                <a class="btn btn-cancel" data-bs-dismiss="modal">{{ __('admin.cancel') }}</a>
            </div>
        </div>
    </div>
</div>
<!-- Show product details popup modal End -->

<!-- Date-Time and pickup address popup -->
<div class="modal version2 fade" id="pickupAddressDateTimeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="pickupAddressDateTimeModalLabel" aria-hidden="true" style="overflow-y: auto">
    <div class="modal-dialog modal-dialog-scrollable modal-lg error_res ">
        <form action="" method="post" name="quincus_pickup_datetime_form" id="pickup_datetime_form" data-parsley-validate  autocomplete="off">
            @csrf
            <div class="modal-content">
                <div class="modal-header p-3" >
                    <h4 class="modal-title">{{ __('admin.date_time_header') }}</h4>
                    <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close" onclick="closePickupModal()">
                        <img src="{{URL::asset('assets/icons/times.png')}}" alt="Close">
                    </button>
                </div>
                <div class="modal-body bg-white p-4">
                    <label class="form-label">{{ __('admin.date_time_label') }}<span class="text-danger">*</span></label>
                    <div class="col-md-6">
                        <input class="form-control flatpickr" name="pickup_datetime" id="pickup_datetime" type="datetime-local" placeholder="{{ __('admin.date_time_label') }}" required>
                        <small class="text-danger" id="datetime_missing"></small>
                    </div>

                    <div class="col-md-12 py-3">
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/attachement.png')}}" alt="Supplier Details" class="pe-2">
                                    <span> {{ __('admin.delivery_detail') }}</span>
                                </h5>
                            </div>

                            <div class="card-body p-3 pb-1" id="activity-details-div">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">{{ __('rfqs.select_address') }} <span class="text-danger">*</span></label>
                                        <select class="form-select" id="supplier_address_id" onchange="changeDetailsSupplier(this)" name="supplier_address_id" required data-parsley-errors-container="#supplier_address_id_error">
                                            <option disabled>{{ __('rfqs.select_delivery_address') }}</option>
                                            @if(isset($supplierAddresses) && !empty($supplierAddresses))
                                                @foreach ($supplierAddresses as $item)
                                                    <option data-address_name="{{$item->address_name}}" data-address_line_2="{{$item->address_line_2}}" data-address_line_1="{{$item->address_line_1}}" data-sub_district="{{$item->sub_district}}" data-district="{{$item->district}}" data-city="{{$item->city}}" data-state="{{$item->state}}" data-state-id="{{$item->state_id ?? \App\Models\UserAddresse::OtherState}}" data-city-id="{{$item->city_id ?? \App\Models\UserAddresse::OtherCity}}" data-pincode="{{$item->pincode}}" value="{{ $item->id }}" {{ $item->address_name == $quotes->adderss_name? 'selected' :'' }}>{{ $item->address_name }}</option>
                                                @endforeach
                                                <option data-address-id="0" value="0">Other</option>
                                            @endif
                                        </select>
                                        <div id="supplier_address_id_error"></div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="address_name" class="form-label">{{ __('rfqs.address_name') }} <span style="color:red;">*</span></label>
                                        <input type="text" name="address_name" id="address_name" class="form-control" value="{{ isset($defaultAddress->address_name) ? $defaultAddress->address_name : '' }}" required>
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label for="address_line_1" class="form-label">{{ __('admin.address_line') }} 1<span style="color:red;">*</span></label>
                                        <input type="text" name="address_line_1" id="address_line_1" value="{{ $quotes->address_line_1 }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="address_line_2" class="form-label">{{ __('admin.address_line') }} 2<span style="color:red;"></span></label>
                                        <input type="text" name="address_line_2" id="address_line_2" value="{{ $quotes->address_line_2 }}" class="form-control">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label for="Sub district" class="form-label">{{ __('admin.sub_district') }}<span style="color:red;">*</span></label>
                                        <input type="text" id="sub_district" name="sub_district" value="{{ $quotes->sub_district }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label for="district" class="form-label">{{ __('admin.district') }}<span style="color:red;">*</span></label>
                                        <input type="text" name="district" id="district" value="{{ $quotes->district }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-2" id="stateId_block">
                                        <label for="stateId" class="form-label">{{ __('admin.provinces') }}<span style="color:red;">*</span></label>
                                        <select class="form-select select2-custom" id="stateId" name="stateId" data-placeholder="{{ __('admin.select_province') }}" required data-parsley-errors-container="#user_provinces">
                                            <option value="" >{{ __('admin.select_province') }}</option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}" @if($quotes->state_id == $state->id) selected @endif >{{ $state->name }}</option>
                                            @endforeach
                                            <option value="-1">Other</option>
                                        </select>
                                        <div id="user_provinces"></div>
                                    </div>
                                    <div class="col-md-3 mb-3 hide" id="state_block">
                                        <label for="provinces" class="form-label">{{ __('admin.other_provinces') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="provinces" id="provinces" value="{{ $quotes->provinces }}" >
                                    </div>

                                    <div class="col-md-6 mb-2" id="cityId_block">
                                        <label for="cityId" class="form-label">{{ __('admin.city') }}<span style="color:red;">*</span></label>
                                        <select class="form-select select2-custom" id="cityId" name="cityId" data-placeholder="{{ __('admin.select_city') }}" data-selected-city="{{ $quotes->city_id }}" required data-parsley-errors-container="#user_city">
                                            <option value="">{{ __('admin.select_city') }}</option>
                                            <option value="-1">Other</option>
                                        </select>
                                        <div id="user_city"></div>
                                    </div>
                                    <div class="col-md-3 mb-3 hide" id="city_block">
                                        <label for="city" class="form-label">{{ __('admin.other_city') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="city" id="city" value="{{ $quotes->city }}" >
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label for="pincode" class="form-label">{{ __('admin.pin_code') }}<span style="color:red;">*</span></label>
                                        <input type="text" pattern=".{5,10}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\.*?)\..*/g, '$1');" class="form-control" id="pincode" name="pincode" value="{{ $quotes->pincode }}" required>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- -------------------------- -->
                    <input type="hidden" name="supplier_id" id="supplier" value="{{ $order->supplier_id }}" />
                    <input type="hidden" name="order_id" id="supplier" value="{{ $order->id }}" />
                    <input type="hidden" name="order_item_ids" id="order_item_ids" value="" />
                </div>

                <div class="modal-footer p-2" style="background-color: #f6f6f6;">
                    <button type="button" class="btn btn-secondary" id="cancelPickupBtn" data-bs-dismiss="modal" onclick="closePickupModal()">{{ __('admin.cancel') }}</button>
                    <button type="button" class="btn btn-primary" id="confirmPickupBtn" onclick="orderPickUpBatch()">{{ __('order.confirm') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Date-Time and pickup address popup -->

<!-- New design for products and charges End -->
    @if(sizeof($batchOrderItems)>0)
        @if(empty($deliveryManageSeparately))
            <div class="row mt-2 pb-2 align-items-center">
            <div class="col-md-4 pb-2">
                <label class="form-label" style="font-size: 0.8em;">{{__('admin.status_for_all_order_items')}}: </label>
                <div class="input-group flex-nowrap">
                    <select id="all_items_status_change_{{$order->id}}"
                            class="allItemsStatusChange all-items-status-change form-select ps-2 w-auto text-primary"
                            data-order_latter="1"
                            data-batch-id=""
                            data-order-id="{{$order->id}}"
                            onchange="createNewBatch($(this))"
                            {{$changeOrderItemStatus?'':'disabled'}}
                            {{$isAllStatusSame?'':'disabled'}}>
                            {{$isAllStatusSame?'':'disabled'}}
                            @if(Auth::user()->hasRole('jne') && in_array($orderItems[0]->order_item_status_id, [1,7,8,9,10]))  readonly @endif
                            @if(Auth::user()->hasRole('supplier') && in_array($orderItems[0]->order_item_status_id, [7,8,9,10]))  readonly @endif

                    >
                        @if(empty($orderItems[0]->order_item_status_id))
                            {{$isAllStatusSame?'':'disabled'}}>
                            <option value="">{{__('admin.select')}}</option>
                        @endif
                        @foreach($orderItemStatuses as $orderItemStatus)
                            @php
                                $isAllowToChangeStatus = isOrderItemStatusChangeAllow($orderItemStatus->id,$logisticProvided);
                            @endphp
                            <option value="{{$orderItemStatus->id}}" {{$orderItems[0]->order_item_status_id==$orderItemStatus->id?'selected':''}} {{$isAllowToChangeStatus?'':'disabled'}}
                            {{($orderItemStatus->id == $activeOrderStatusID || $orderItems[0]->order_item_status_id==$orderItemStatus->id)?'':'disabled'}}>
                                {{__('order.'.trim($orderItemStatus->name))}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4 pb-2">
            </div>
            <div class="col-md-4 pb-2">
                @if((auth()->user()->role_id == 1 || Auth::user()->hasRole('agent') || Auth::user()->hasRole('supplier')) && $order->order_status == 4 && $orderItems[0]->order_item_status_id == 1)
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-primary text-white py-1" role="button" onclick="manageOrderDeliverySeparately()"><i class="fa fa-object-ungroup" aria-hidden="true"></i> {{__('admin.manage_order_delivery_separately')}}</button>
                </div>
                @endif
            </div>
        </div>
        @endif

        <div class="px-3 py-3">
            <div class="table-responsive" style="max-height:322px; overflow-y: auto; overflow-x: auto">
                <table class="table text-dark table-striped table-bordered">
                    <thead class="bg-light" style="position:sticky; top: -2px; z-index: 5">
                        <tr class="bg-light">
                            <th @if(!empty($deliveryManageSeparately) && (auth()->user()->role_id == 1 || Auth::user()->hasRole('agent') || Auth::user()->hasRole('supplier'))) class="" @else class="d-none" @endif>
                                <div class="form-check m-0">
                                    <input class="form-check-input" name="selectAllItems" type="checkbox" value="0" id="selectAllItems" style=" margin-left: 0px !important;">
                                </div>
                            </th>
                            <th>{{__('admin.item_number')}}</th>
                            <th>{{__('admin.description')}}</th>
                            <th>{{__('admin.status_of_order_item')}}</th>
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

                    @endphp
                    @foreach($batchOrderItems as $orderItem)
                    @php
                    $quoteItem = $orderItem->quoteItem()->first();
                    $unit = get_unit_name($quoteItem->price_unit);
                    $activeOrderStatusID = $orderItem->order_item_status_id + 1;
                    /*$orderItemsIds[] = $orderItem->id;*/
                    @endphp
                    <tr>
                        <td @if(!empty($deliveryManageSeparately) && (auth()->user()->role_id == 1 || Auth::user()->hasRole('agent') || Auth::user()->hasRole('supplier'))) class="" @else class="d-none" @endif>
                            <div class="form-check m-0">
                                <input class="form-check-input" name="selectItems" type="checkbox" value="{{$orderItem->id}}" id="selectItems{{$orderItem->id}}"  style=" margin-left: 0px !important;">
                            </div>
                        </td>
                        <td>{{$orderItem->order_item_number}}</td>
                        <td>{{get_product_name_by_id($orderItem->rfq_product_id,1)}}</td>
                        <td class="text-nowrap">
                            <div class="input-group flex-nowrap">
                                <select id="item_status_change_{{$orderItem->id}}"
                                        class="orderItemStatusChange order-item-status-change form-select ps-2 w-auto text-primary"
                                        data-order_latter="1"
                                        data-batch-id=""
                                        onchange="createNewBatch($(this),{{$orderItem->id}})"
                                    {{$deliveryManageSeparately?'':'disabled'}}
                                    {{$changeOrderItemStatus?'':'disabled'}}
                                    @if(Auth::user()->hasRole('jne') && in_array($orderItem->order_item_status_id, [1,7,8,9,10]))  readonly @endif
                                    @if(Auth::user()->hasRole('supplier') && in_array($orderItem->order_item_status_id, [7,8,9,10]))  readonly @endif>
                                    @if(empty($orderItem->order_item_status_id))
                                        <option value="">{{__('admin.select')}}</option>
                                    @endif
                                    @foreach($orderItemStatuses as $orderItemStatus)
                                            @php
                                                $isAllowToChangeStatus = isOrderItemStatusChangeAllow($orderItemStatus->id,$logisticProvided);
                                            @endphp
                                        <option value="{{$orderItemStatus->id}}" {{($deliveryManageSeparately==1 && ($orderItemStatus->id==$activeOrderStatusID || $orderItem->order_item_status_id==$orderItemStatus->id)) ?'':'disabled'}} {{$orderItem->order_item_status_id==$orderItemStatus->id?'selected':''}} {{$isAllowToChangeStatus?'':'disabled'}} >
                                            {{__('order.'.$orderItemStatus->name)}}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="input-group-text addon-wrapping {{$isDisabledOrderItemStatus?'':'bg-white'}}" onclick="{{$isDisabledOrderItemStatus?'':'orderItemModal('.$orderItem->id.')'}}"><i class="fa fa-eye hand"></i></span>
                            </div>
                        </td>
                        <td class="text-nowrap">Rp {{number_format($quoteItem->product_price_per_unit,2)}} per {{$unit}}</td>
                        <td class="text-nowrap">{{$quoteItem->product_quantity}} {{$unit}}</td>
                        <td class="text-nowrap">{{$quoteItem->weights}} </td>
                        <td class="text-nowrap" align="right">Rp {{number_format($quoteItem->product_amount,2)}}</td>
                    </tr>
                    @endforeach
                    <!-- Will Remove this commented section -->
                    {{-- @foreach ($amountDetails as $charges)
                            <tr>
                                @if ($charges->type == 0)
                                    <td colspan="{{$colspan}}">{{ $charges->charge_name . ' ' . $charges->charge_value }}
                                        %
                                    </td>
                                @else
                                    <td colspan="{{$colspan}}">{{ $charges->charge_name }}</td>
                                @endif
                                <td align="right">
                                    {{ $charges->addition_substraction == 0 ? '- ' : '+ ' }}{{ 'Rp ' . number_format($charges->charge_amount, 2) }}
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="{{$colspan}}">{{ __('admin.tax') }} {{ $quote->tax .'%'}}</td>
                                <td align="right">
                                    + Rp
                                    @if(auth()->user()->role_id == 3)
                                        {{ number_format($quote->supplier_tex_value, 2) }}
                                    @else
                                        {{number_format($quote->tax_value, 2)}}
                                    @endif
                                </td>
                            </tr>
                            @php
                                $billedAmount = $quote->final_amount;
                                $bulkOrderDiscount = 0;
                                if (auth()->user()->role_id != 3){
                                    $bulkOrderDiscount = getBulkOrderDiscount($order->id);
                                }
                            @endphp
                            @if($bulkOrderDiscount>0)
                                @php
                                    $billedAmount = $billedAmount-$bulkOrderDiscount;
                                @endphp
                                <tr>
                                    <td colspan="{{$colspan}}">
                                        {{ __('admin.bulk_payment_discount') }}
                                    </td>
                                    <td align="right">
                                        {{ '- Rp ' . number_format($bulkOrderDiscount, 2) }}
                                    </td>
                                </tr>
                            @endif
                            <tr class="bg-secondary text-white">
                                <td colspan="{{$colspan}}" class="text-white fw-bold">
                                    Amount to Pay</td>
                                <td align="right" class="text-white fw-bold">Rp
                                    @if(auth()->user()->role_id == 3)
                                        {{number_format($quote->supplier_final_amount, 2)}}
                                    @else
                                        {{number_format($billedAmount, 2)}}
                                    @endif
                                </td>
                            </tr>
                        --}}
                    <!-- Will Remove this commented section -->
                    </tbody>
                </table>
            </div>
        </div>
        @if(auth()->user()->role_id == 1 || Auth::user()->hasRole('agent') || Auth::user()->hasRole('supplier'))
        <div class="create-group justify-content-end mt-3 me-4 {{$deliveryManageSeparately ?'d-flex':'d-none'}}">
            <button type="button" class="btn btn-primary btn-sm " onclick="createNewBatch()" data-bs-toggle="modal">
                    {{__('admin.create_group')}}
            </button>
        </div>
        @endif
    @endif

    <!-- Confirm product items before create group Start -->
    <div class="modal version2 fade" id="confirmOrderProductsModal" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-labelledby="confirmOrderProductsModalLabel">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header py-3">
                    <h5 class="modal-title">{{ __('admin.product_details') }}</h5>
                    <button type="button" class="btn-close ms-0 d-flex cancelConfirmBatchBtn" data-bs-dismiss="modal" aria-label="Close">
                        <img src="{{URL::asset('front-assets/images/icons/times.png')}}" alt="Close">
                    </button>
                </div>
                <div class="modal-body bg-white py-2">
                    <div class="tooltip_section text-dark text-start p-1 multi_pro_list">
                        <div class="table-responsive">
                            <table class="table text-dark table-striped">
                                <thead>
                                    <tr class="bg-light">
                                        <th>{{__('admin.item_number')}}</th>
                                        <th>{{__('admin.description')}}</th>
                                        <th>{{__('admin.price')}}</th>
                                        <th>{{__('admin.qty')}}</th>
                                        <th>{{__('admin.weight')}} <small>({{ __('admin.per_unit') }})</small></th>
                                        <th align="right" class="text-end">{{__('admin.amount')}}</th>
                                    </tr>
                                </thead>
                                <tbody id="orderItemsTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer px-3 d-flex">
                    <div class="alert alert-danger d-flex align-items-center p-1" role="alert">
                        <div class="confirmOrderItemsNote justify-content-start">
                            <small class="text-danger align-item:self-bottom" id="confirmBatchTitle"></small>.
                            <small class="text-danger align-item:self-bottom" id="cannotRevertStatus">{{ __('admin.cannot_revert_status') }}</small>
                        </div>
                    </div>
                    <div class="ms-auto">
                        <a class="btn btn-cancel cancelConfirmBatchBtn" data-bs-dismiss="modal">{{ __('admin.cancel') }}</a>
                        <button type="button" class="btn btn-primary confirmOrderBatchBtn" id="">{{ __('order.confirm') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Confirm product items before create group End -->

    <script type="text/javascript">
        /******begin: Initiate SnippetEditPickupDetail********/
        jQuery(document).ready(function(){
            getSupplierAdressDetails($('#supplier').val());
            flatPickrValidations();
            SnippetEditPickupDetail.init();
        });
        /******end: Initiate SnippetEditPickupDetail*******/

        function getSupplierAdressDetails(supplier_id) {
            var old_tcdocument = '{{$quotes->termsconditions_file}}';
            $.ajax({
                url: "{{ route('getSupplierAddressById','') }}/"+supplier_id,
                type: 'GET',
                success: function (successData) {
                    if (successData.addresses) {
                        $('#supplier_address_id').empty();
                        $('#supplier_address_id').append("<option disabled >{{ __('rfqs.select_delivery_address') }}</option>");
                        var selected = '';
                        var selectdata = '{{ $quotes->address_name }}';
                        var stateId = '';
                        var cityId = '';
                        $.each(successData.addresses,function(index,address) {
                            if (address.address_name === selectdata){
                                selected = 'selected';
                            }
                            stateId     = address.state_id  ?? {{ \App\Models\UserAddresse::OtherState  }};
                            cityId      = address.city_id   ?? {{ \App\Models\UserAddresse::OtherCity  }};

                            $('#supplier_address_id').append('<option data-address_name="'+address.address_name+'" data-address_line_2="'+address.address_line_2+'" data-address_line_1="'+address.address_line_1+'" data-sub_district="'+address.sub_district+'" data-district="'+address.district+'" data-city="'+address.city+'" data-state="'+address.state+'" data-state-id="'+stateId+'" data-city-id="'+cityId+'" data-pincode="'+address.pincode+'" value="'+address.id+'" '+selected+'>'+address.address_name+'</option>');
                            selected = '';
                        });
                        $('#supplier_address_id').append('<option data-address-id="0" value="0">Other</option>');

                        changeDetailsSupplier($('#supplier_address_id'));
                    }
                    if(old_tcdocument==""){
                         if(successData.tc_document){
                            $('#file-termsconditions_file_exist').html(successData.tc_document);
                         }
                    }

                },error: function () {
                    console.log("error");
                }
            });
        }

        /*****begin: Quote Edit Pickup Detail******/
        var SnippetEditPickupDetail = function(){

            var selectStateGetCity = function(){

                    $('#stateId').on('change',function(){

                        let state = $(this).val();
                        let targetUrl = "{{ route('admin.city.by.state',":id") }}";
                        targetUrl = targetUrl.replace(':id', state);
                        var newOption = '';

                        // Add Remove Other State filed
                        if (state == -1) {
                            $('#stateId_block').removeClass('col-md-6');
                            $('#stateId_block').addClass('col-md-3');

                            $('#state_block').removeClass('hide');
                            $('#state').attr('required','required');

                            $('#cityId_block').removeClass('col-md-6');
                            $('#cityId_block').addClass('col-md-3');

                            $('#cityId').empty();

                            //set default options on other state mode
                            newOption = new Option('{{ __('admin.select_city') }}','', true, true);
                            $('#cityId').append(newOption).trigger('change');

                            newOption = new Option('Other','-1', true, true);
                            $('#cityId').append(newOption).trigger('change');


                        } else {
                            $('#stateId_block').removeClass('col-md-3');
                            $('#stateId_block').addClass('col-md-6');

                            $('#state_block').addClass('hide');
                            $('#state').removeAttr('required','required');

                            $('#city_block').addClass('hide');
                            $('#city').removeAttr('required','required');

                            //Fetch cities by state
                            if (state != '') {
                                $.ajax({
                                    url: targetUrl,
                                    type: 'POST',
                                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},

                                    success: function (response) {

                                        if (response.success) {

                                            $('#cityId').empty();

                                            newOption = new Option('{{ __('admin.select_city') }}', '', true, true);
                                            $('#cityId').append(newOption).trigger('change');

                                            for (let i = 0; i < response.data.length; i++) {
                                                newOption = new Option(response.data[i].name, response.data[i].id, true, true);
                                                $('#cityId').append(newOption).trigger('change');
                                            }

                                            newOption = new Option('Other', '-1', true, true);
                                            $('#cityId').append(newOption).trigger('change');

                                            /*******begin:Add and remove last null option for no conflict*******/
                                            newOption = new Option('0', '0', true, true);
                                            $('#cityId').append(newOption).trigger('change');
                                            $('#cityId').each(function () {
                                                $(this).find("option:last").remove();
                                            });
                                            /*******end:Add and remove last null option for no conflict*******/

                                            let selectedAddressCity = $('#supplier_address_id option:selected').attr('data-city-id');
                                            if (selectedAddressCity != null && selectedAddressCity != '') {
                                                $('#cityId').val(selectedAddressCity).trigger('change');
                                            } else {
                                                $('#cityId').val(null).trigger('change');
                                            }

                                        }

                                    },
                                    error: function () {

                                    }
                                });
                            } else {
                                $('#cityId').empty();

                                newOption = new Option('Other', '-1', true, true);
                                $('#cityId').append(newOption).trigger('change');

                                $('#cityId').val(null).trigger('change');

                            }

                        }

                    });

                },

                selectCitySetOtherCity = function(){

                    $('#cityId').on('change',function(){

                        let city = $(this).val();

                        // Add Remove Other City filed
                        if (city == -1) {
                            $('#cityId_block').removeClass('col-md-6');
                            $('#cityId_block').addClass('col-md-3');

                            $('#city_block').removeClass('hide');
                            $('#city').attr('required','required');

                            if ($('#stateId').val() <= 0) {
                                $('#stateId_block').removeClass('col-md-6');
                                $('#stateId_block').addClass('col-md-3');
                            }

                            if ($('#stateId').val() == '') {
                                $('#stateId_block').removeClass('col-md-3');
                                $('#stateId_block').addClass('col-md-6');
                            }

                        } else {
                            $('#cityId_block').removeClass('col-md-3');
                            $('#cityId_block').addClass('col-md-6');

                            $('#city_block').addClass('hide');
                            $('#city').removeAttr('required','required');

                        }

                    });

                },

                initiateCityState = function(){

                    let state               =   $('#provinces').val();
                    let selectedState       =   $('#stateId').val();

                    if (state != null && state !='') {
                        $('#stateId').val('-1').trigger('change');
                    }

                    if (selectedState !='' && selectedState != null) {
                        $('#stateId').val(selectedState).trigger('change');
                    }

                };

            return {
                init:function(){
                    selectStateGetCity(),
                    selectCitySetOtherCity(),
                    initiateCityState()
                }
            }

        }(1);
        /*****end: Quote Edit Pickup Detail******/


        function changeDetailsSupplier($this){
            let selected_option = $('option:selected', $this);
            let city = selected_option.attr('data-city') != 'null' ? selected_option.attr('data-city') : '';
            let provinces = selected_option.attr('data-state') != 'null' ? selected_option.attr('data-state') : '';
            let SupAddVal = selected_option.val();
            SupAddVal==0 ? $("#address_name").removeAttr('disabled') : $("#address_name").attr('disabled',true);
            SupAddVal==0 ?$("#address_line_1").removeAttr('disabled') : $("#address_line_1").attr('disabled',true);
            SupAddVal==0 ?$("#address_line_2").removeAttr('disabled') : $("#address_line_2").attr('disabled',true);
            SupAddVal==0 ?$("#sub_district").removeAttr('disabled') : $("#sub_district").attr('disabled',true);
            SupAddVal==0 ?$("#district").removeAttr('disabled') : $("#district").attr('disabled',true);
            SupAddVal==0 ?$("#cityId").removeAttr('disabled') : $("#cityId").attr('disabled',true);
            SupAddVal==0 ?$("#provinces").removeAttr('disabled') : $("#provinces").attr('disabled',true);
            SupAddVal==0 ?$("#pincode").removeAttr('disabled') : $("#pincode").attr('disabled',true);
            SupAddVal==0 ?$("#stateId").removeAttr('disabled') : $("#stateId").attr('disabled',true);


            $("#address_name").val(selected_option.attr('data-address_name') ?? '');
            $("#address_line_1").val(selected_option.attr('data-address_line_1') ?? '');
            $("#address_line_2").val(selected_option.attr('data-address_line_2') ?? '');
            $("#sub_district").val(selected_option.attr('data-sub_district') ?? '');
            $("#district").val(selected_option.attr('data-district') ?? '');
            $("#city").val(city ?? '');
            $("#provinces").val(provinces ?? '');
            $("#pincode").val(selected_option.attr('data-pincode') ?? '');
            $("#stateId").val(selected_option.attr('data-state-id') ?? '').trigger('change');
        }
    </script>
