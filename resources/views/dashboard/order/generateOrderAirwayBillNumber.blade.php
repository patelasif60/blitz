<div class="modal-header">
    <h1 class="modal-title fs-5"
        id="AirwaybillModalLabel{{$order->id}}">{{ __('admin.airway_bill_ganrate') }}  {{ strtolower(__('admin.for')) }} {{$order->order_number}}</h1>
    <button type="button" class="btn-close closeAirwaybillModel" data-bs-dismiss="modal" aria-label="Close"
            data-id="{{$order->id}}" id="closeAirwaybillModalLabel{{$order->id}}"></button>
</div>
<div class="modal-body" style="max-height: 700px;" id="AirwayBillData{{$order->id}}">
    <div class="overflow-hidden">
        <!--multisteps-form-->
        <div class="multisteps-form">
            <!--progress bar-->
            <div class="row">
                <div class="col-12 ml-auto mr-auto mb-4 mt-1" id="{{$order->id}}">
                    <div class="multisteps-form__progress">
                        <button class="multisteps-form__progress-btn" id="pickupInfo{{$order->id}}" type="button"
                                title="{{__('admin.pickup_address')}}">
                            {{__('admin.pickup_address')}}
                        </button>
                        <button class="multisteps-form__progress-btn" id="dropAddress{{$order->id}}" type="button"
                                title="{{__('dashboard.drop_address')}}">
                            {{__('dashboard.drop_address')}}
                        </button>
                        <button class="multisteps-form__progress-btn" id="receiptanceDetail{{$order->id}}" type="button"
                                title="{{__('admin.product_details')}}">{{__('admin.product_details')}}
                        </button>
                        <button class="multisteps-form__progress-btn" id="otherDetails{{$order->id}}" type="button"
                                title="{{__('admin.other_detail')}}">
                            {{__('admin.other_detail')}}
                        </button>
                        <button class="multisteps-form__progress-btn" id="dateTime{{$order->id}}" type="button"
                                title="{{__('dashboard.receiptance_detail')}}">
                            {{__('dashboard.receiptance_detail')}}
                        </button>
                    </div>
                </div>
            </div>
            <!--form panels-->
            <div class="row">
                <div class="col-12 m-auto">
                    <div class="d-flex justify-content-center position-absolute d-none" id="airwayBillPopupLodder{{ $order->id }}"
                         style="z-index: 10000;top: 50%;left: 50%">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <form method="post"  style="min-height: 450px" class="multisteps-form__form" data-parsley-validate autocomplete="off" id="AirwaybillForm{{$order->id}}">
                        <!--single form panel-->
                        @csrf
                        @php
                            $productAmount = 0;$orderItemIds= [];
                        @endphp
                        <div class="multisteps-form__panel bg-white js-active"  data-animation="scaleIn"
                             id="pickupAddress{{ $order->id }}">
                            <h6 class="multisteps-form__title mb-3 text-primary">{{__('admin.pickup_address')}}</h6>
                            <div class="multisteps-form__content floatlables">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="">{{__('dashboard.Address_Name')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="address_name"
                                               value="{{ $order->rfq->address_name ? $order->rfq->address_name : '' }}"
                                               readonly
                                               class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">{{__('dashboard.Address_Line_1')}}<span
                                                class="text-danger">*</span></label>
                                        <input name="address_line_1"
                                               value="{{ $order->rfq->address_line_1 ? $order->rfq->address_line_1 : '' }}"
                                               readonly class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">{{__('dashboard.Address_Line_2')}}</label>
                                        <input class="form-control" name="address_line_2"
                                               value="{{ $order->rfq->address_line_2 ? $order->rfq->address_line_2 : '' }}"
                                               readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">{{__('admin.sub_district')}}<span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" name="sub_district"
                                               value="{{ $order->rfq->sub_district ? $order->rfq->sub_district : '' }}"
                                               readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">{{__('admin.district')}}<span class="text-danger">*</span></label>
                                        <input class="form-control" name="district"
                                               value="{{ $order->rfq->district ? $order->rfq->district : '' }}"
                                               readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">{{__('admin.provinces')}}<span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" name="state_id"
                                               value="{{ $order->rfq->state_id ? getStateName($order->rfq->state_id) : '' }}"
                                               readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">{{__('admin.city')}}<span class="text-danger">*</span></label>
                                        <input class="form-control" name="city_id"
                                               value="{{ $order->rfq->city_id ? getCityName($order->rfq->city_id) : '' }}"
                                               readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">{{__('admin.pin_code')}}<span class="text-danger">*</span></label>
                                        <input class="form-control" name="pincode"
                                               value="{{ $order->rfq->pincode ? $order->rfq->pincode : '' }}" readonly>
                                    </div>

                                </div>
                                <div class="button-row text-end">
                                    <button class="btn btn-primary btn-sm ml-auto js-btn-next " type="button"
                                            title="{{__('admin.next')}}">
                                        {{__('admin.next')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!--single form panel-->
                        <div class="multisteps-form__panel bg-white" data-animation="scaleIn"
                             id="dropAddress{{ $order->id }}">
                            <h6 class="multisteps-form__title mb-3 text-primary">{{__('dashboard.drop_address')}}</h6>
                            <div class="multisteps-form__content floatlables">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="">{{__('dashboard.Address_Name')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="address_name"
                                               value="{{ $order->quote->address_name ? $order->quote->address_name : '' }}"
                                               readonly class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">{{__('dashboard.Address_Line_1')}}<span
                                                class="text-danger">*</span></label>
                                        <input name="address_line_1"
                                               value="{{ $order->quote->address_line_1 ? $order->quote->address_line_1 : '' }}"
                                               readonly class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">{{__('dashboard.Address_Line_2')}}</label>
                                        <input class="form-control" name="address_line_2"
                                               value="{{ $order->quote->address_line_2 ? $order->quote->address_line_2 : '' }}"
                                               readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">{{__('admin.sub_district')}}<span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" name="sub_district"
                                               value="{{ $order->quote->sub_district ? $order->quote->sub_district : '' }}"
                                               readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">{{__('admin.district')}}<span class="text-danger">*</span></label>
                                        <input class="form-control" name="district"
                                               value="{{ $order->quote->district ? $order->quote->district : '' }}"
                                               readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="">{{__('admin.provinces')}}<span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" name="quotesProvinces"
                                               value="{{ $order->quote->state_id ? getStateName($order->quote->state_id) : '' }}"
                                               readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">{{__('admin.city')}}<span class="text-danger">*</span></label>
                                        <input class="form-control" name="city_id"
                                               value="{{ $order->quote->city_id ? getCityName($order->quote->city_id) : '' }}"
                                               readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">{{__('admin.pin_code')}}<span class="text-danger">*</span></label>
                                        <input class="form-control" name="pincode"
                                               value="{{ $order->quote->pincode ? $order->quote->pincode : '' }}"
                                               readonly>
                                    </div>

                                </div>
                                <div class="button-row text-end">
                                    <button class="btn btn-secondary btn-sm js-btn-prev" type="button"
                                            title="{{__('dashboard.Previous')}}">
                                        {{__('dashboard.Previous')}}
                                    </button>
                                    <button class="btn btn-primary btn-sm ml-auto js-btn-next ms-2" type="button"
                                            title="{{__('admin.next')}}">{{__('admin.next')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!--single form panel-->
                        <div class="multisteps-form__panel bg-white" data-animation="scaleIn"
                             id="receiptanceDetails{{ $order->id }}">
                            <h6 class="multisteps-form__title mb-3 text-primary">{{__('admin.product_details')}}</h6>
                            <div class="multisteps-form__content floatlables">
                                <div class="row">
                                    @if(!empty($order->orderItems))
                                        @foreach($order->orderItems as $key => $quotesProduct)
                                            @php
                                                 array_push($orderItemIds , $quotesProduct->id);
                                            @endphp
                                        <div class="accordion mb-3" id="accordionExample{{$quotesProduct->id}}">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header " id="headingOne{{$quotesProduct->id}}">
                                                    <button class="accordion-button collapsed p-2 bg-light" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseOne{{$quotesProduct->id}}"
                                                            aria-expanded="false" aria-controls="collapseOne{{$quotesProduct->id}}">
                                                        {{ $quotesProduct->order_item_number.' - '.get_product_name_by_id($quotesProduct->rfq_product_id) }}
                                                    </button>
                                                </h2>
                                                <div id="collapseOne{{$quotesProduct->id}}" class="accordion-collapse collapse @if($key == 0) show @endif"
                                                     aria-labelledby="headingOne{{$quotesProduct->id}}" data-bs-parent="#accordionExample{{$quotesProduct->id}}">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-3">
                                                                <label for="">{{__('admin.description')}}<span class="text-danger">*</span></label>
                                                                <input class="form-control" name="description" value="{{ get_product_desc_by_id($quotesProduct->rfq_product_id) }}" readonly>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="">{{__('admin.quantity')}}<span class="text-danger">*</span></label>
                                                                <input class="form-control" name="quantity" value="{{ $quotesProduct->quoteItem->product_quantity }}" readonly>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="">{{__('admin.weight')}} <span class="text-danger">*</span></label>
                                                                <input class="form-control" name="weight" value="{{ $quotesProduct->quoteItem->weights }}" readonly>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="">{{__('admin.lenghth')}} <span class="text-danger">*</span></label>
                                                                <input class="form-control" name="lenghth" value="{{ $quotesProduct->quoteItem->length }}" readonly>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="">{{__('admin.width')}} <span class="text-danger">*</span></label>
                                                                <input class="form-control" name="width" value="{{ $quotesProduct->quoteItem->width }}" readonly>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="">{{__('admin.height')}} <span class="text-danger">*</span></label>
                                                                <input class="form-control" name="height" value="{{ $quotesProduct->quoteItem->height }}" readonly>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                                    <div class="button-row text-end">
                                        <button class="btn btn-secondary btn-sm js-btn-prev" type="button"
                                                title="{{__('dashboard.Previous')}}">
                                            {{__('dashboard.Previous')}}
                                        </button>
                                        <button class="btn btn-primary btn-sm ml-auto js-btn-next ms-1" type="button"
                                                title="{{__('admin.next')}}">{{__('admin.next')}}
                                        </button>
                                    </div>
                            </div>
                        </div>
                        <!--single form panel-->
                        <div class="multisteps-form__panel bg-white" data-animation="scaleIn"
                             id="otherDetails{{ $order->id }}">
                            <h6 class="multisteps-form__title mb-3 text-primary">{{__('admin.other_detail')}}</h6>
                            <div class="multisteps-form__content floatlables">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="">{{__('admin.quincus_services')}}<span class="text-danger">*</span></label>
                                        <input class="form-control" name="logistics_service_code"
                                               value="{{ $order->QuoteItem->first()->logistics_service_code }}"
                                               readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for=""> {{__('admin.goods_type')}}<span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" name="goods_type" value="SHTPC" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">{{__('admin.quincus_pickup_service')}} <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" name="pickup_service"
                                               value="{{ $order->QuoteItem->first()->pickup_service }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">{{__('admin.quincus_pickup_fleet')}} <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" name="pickup_fleet"
                                               value="{{ $order->QuoteItem->first()->pickup_fleet }}" readonly>
                                    </div>
                                    @foreach($order->QuoteItem as $quoteItem)
                                    @php
                                        $productAmount = $productAmount + ($quoteItem->product_amount);
                                    @endphp
                                    @endforeach
                                    <div class="col-md-6 mb-3">
                                        <label for="">{{__('admin.goods_value')}} <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" name="goods_value" value="{{ $productAmount }}"
                                               readonly>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-center mb-3 ">
                                        <div class="form-check">
                                            <input class="form-check-input" name="insurance" type="checkbox" value="" id="insurance" checked="" disabled>
                                            <label class="form-check-label" for="insurance">
                                                {{__('admin.insurance')}}
                                            </label>
                                        </div>
                                        <div class="form-check ms-2">
                                            <input class="form-check-input" name="wood_packing" disabled type="checkbox" value="YES" id="wood_packing"
                                                   @if($order->QuoteItem->first()->wood_packing==1) checked @endif>
                                            <label class="form-check-label" for="wood_packing">
                                                {{__('admin.wood_packing')}}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">{{__('admin.goods_description')}} <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" readonly name="goods_description">{{ get_product_desc_by_id($order->QuoteItem->first()->rfq_product_id) }}</textarea>
                                    </div>

                                </div>
                                <div class="button-row text-end">
                                    <button class="btn btn-secondary btn-sm js-btn-prev" type="button"
                                            title="{{__('dashboard.Previous')}}">
                                        {{__('dashboard.Previous')}}
                                    </button>
                                    <button class="btn btn-primary btn-sm ml-auto js-btn-next ms-1" type="button"
                                            title="{{__('admin.next')}}">{{__('admin.next')}}
                                    </button>
                                </div>

                            </div>
                        </div>
                        <!--single form panel-->
                        <div class="multisteps-form__panel bg-white" data-animation="scaleIn"
                             id="dateTime{{ $order->id }}">
                            <h6 class="multisteps-form__title text-primary mb-3 px-3">{{__('dashboard.receiptance_detail')}}</h6>
                            <div class="multisteps-form__content floatlables">
                                <div class="row mx-0">
                                    <div class="col-md-6 mb-4">
                                        <label>{{__('admin.receiver_company_name')}}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="{{__('admin.receiver_company_name')}}" name="receiver_company_name" id="receiver_company_name{{ $order->id }}" value="" required>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label>{{__('admin.receiver_name')}}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="{{__('admin.receiver_name')}}" name="receiver_name" id="receiver_name{{ $order->id }}" value="" required>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label>{{__('admin.receiver_pic_phone')}}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control receiver_pic_phone" placeholder="{{__('admin.receiver_pic_phone')}}" maxlength="16" name="receiver_pic_phone" id="receiver_pic_phone{{ $order->id }}" value="" required>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label>{{__('admin.receiver_email')}}<span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" placeholder="{{__('admin.receiver_email')}}" name="receiver_email_address" id="receiver_email_address{{ $order->id }}" value="" required>
                                    </div>
                                   <div class="col-md-6">
                                       <label>{{__('admin.date_time_label')}}<span class="text-danger">*</span></label>
                                        <div id="date" class="input-group date datepicker">
                                            <input type="text" id="pickup_date{{ $order->id }}" name="pickup_datetime"
                                                   data-id="{{$order->id}}" value="" placeholder="dd-mm-yyyy"
                                                   class="form-control flatpickr"
                                                   >
                                        </div>
                                   </div>
                                    <div class="col-md-12">
                                       <small class="text-danger" id="datetime_missing{{ $order->id }}"></small>
                                    </div>
                                </div>
                                <div class="button-row text-end">
                                    <input type="hidden" name="supplier_address_id" id="supplier_address_id{{ $order->id }}" value="{{ $order->rfq->address_id }}">
                                    <input type="hidden" name="order_item_ids" id="order_item_ids{{ $order->id }}" value="{{ json_encode($orderItemIds,TRUE) }}">
                                    <input type="hidden" name="order_id" id="order_id{{ $order->id }}" value="{{ $order->id }}">
                                    <input type="hidden" name="supplier_id" id="supplier_id{{ $order->id }}" value="{{ $order->supplier_id }}">
                                    <button class="btn btn-secondary btn-sm js-btn-prev" id="cancelPickupBtn{{ $order->id }}" type="button" title="{{__('dashboard.Previous')}}">
                                        {{__('dashboard.Previous')}}
                                    </button>
                                    <button class="btn btn-primary btn-sm ml-auto ms-1 generateAirwaybill" id="submitAirwaybillForm{{$order->id}}" data-id="{{$order->id}}" type="button" title="{{__('admin.airway_bill_ganrate')}}">
                                        {{__('admin.airway_bill_ganrate')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.flatpickr').flatpickr({
            enableTime: true,
             dateFormat: 'd-m-y h:i K',
            minDate: "today",
        });
    });
    $('.flatpickr').on('change', function (ev) {
        var orderID = {{ $order->id }};
        $('#datetime_missing' + orderID).html('');
    });

    (function($) {
        $.fn.inputFilter = function(callback, errMsg) {
            return this.on("input keydown keyup mousedown mouseup select contextmenu drop focusout", function(e) {
                if (callback(this.value)) {
                    // Accepted value
                    if (["keydown","mousedown","focusout"].indexOf(e.type) >= 0){
                        $(this).removeClass("input-error");
                        this.setCustomValidity("");
                    }
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    // Rejected value - restore the previous one
                    $(this).addClass("input-error");
                    this.setCustomValidity(errMsg);
                    this.reportValidity();
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                } else {
                    // Rejected value - nothing to restore
                    this.value = "";
                }
            });
        };
    }(jQuery));

    $(".receiver_pic_phone").inputFilter(function(value) {
        return /^\d*$/.test(value);    // Allow digits only, using a RegExp
    },"Only digits allowed");

</script>
