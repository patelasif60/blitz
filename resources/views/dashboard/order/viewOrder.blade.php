@push('bottom_head')
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <style>

        @media (max-width: 1400px) {
            .main_section > .container-fluid {
                max-width: 90%;
            }

            .bullet-line-list li.active h6 a {
                font-size: 13px;
            }

        }

        .airwayBillPopupLodder-show{
            display: block;
        }

        .processing-status{
            pointer-events: none !important;
            opacity: 0.5 !important;
        }
    </style>
@endpush
<div class="header_top d-flex align-items-center">
    <h1 class="mb-0">{{ __('order.order') }}</h1>
    <a href="{{ route('export-excel-ajax') }}" class="btn btn-warning btn-sm ms-auto" style="padding: 0.125rem 0.4rem;"
       id="dropdownMenuButton1" aria-expanded="false">
        {{ __('admin.Export') }}
    </a>
</div>

<div class="accordion order_section" id="Order_accordian">
    <div class="col-md-12 mb-3 d-flex">

        <div class="col-md-5">
            <select class="form-select py-2"  name="customStatusSearch" id="customStatusSearch">
                <option value="all">{{ __('rfqs.all') }}</option>
                @if(count($allOrderStatus))
                    @foreach($allOrderStatus as $value)
                        <option
                            value="{{$value->id}}" {{ $value->id == $statusSelected ? 'selected="selected"' : '' }}>{{ __('order.' . $value->name) }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="ms-auto d-flex align-items-center">
            <small class="text-nowrap me-2 mt-2 showSearchData"><span
                    id="searchResultCount">0</span> {{ __('rfqs.result_found') }}</small>
            <div id="grouptrading_search" class="input-group mb-0">
                <input class="form-control form-control-sm" type="search" name="customSearch"
                       value="{{!empty($customSearch) ? $customSearch : '' }}" id="customSearch"
                       placeholder="{{ __('admin.search') }}" aria-label="{{ __('admin.search') }}" autocomplete="off">
                <button class="btn bg-light text-white btn-sm search border customSearch" type="button">
                    <img src="{{ URL::asset('assets/images/icon_search_b.png') }}" alt="{{ __('admin.search') }}">
                </button>
            </div>
            <div class="text-end">
                <small class="text-danger" id="searchTextErr"></small>
            </div>
        </div>
    </div>
    @if (count($orders))
        @foreach ($orders as $key => $order)
            @php
                $isGroupOrder = $order->group_id?1:0;//check is group order or not
                $orderItemCategory = $order->orderItems->first()->quoteItem->rfqProduct->category;
                $orderItemCategoryId = $order->orderItems->first()->quoteItem->rfqProduct->category_id;
                $orderlogisticProvided = $order->orderItems->first()->quoteItem;
                $orderAirwayBill = $order->orderItems->first()->orderAirwayBillNumber;
            @endphp
            <div class="accordion-item radius_1 mb-2">
                <h2 class="accordion-header d-flex" id="heading{{ $order->id }}">
                    <button class="accordion-button justify-content-between collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $order->id }}" aria-expanded="true"
                            aria-controls="collapse{{ $order->id }}">
                        <div class="flex-grow-1 ">
                            {{-- {{ $order->category_name . ' - ' . $order->sub_category_name . ' - ' . $order->product_name }} --}}

                            {{ __('order.order_no') . ' ' . $order->order_number }}
                            <br>
                            @php
                                $status_name = __('order.' . trim($order->order_status_name));
                                if ($order->order_status == 8) {//'Payment Due DD/MM/YYYY'
                                    $status_name = sprintf($status_name,changeDateFormat($order->payment_due_date,'d/m/Y'));
                                }
                            @endphp
                            <span
                                class="badge rounded-pill bg-primary mt-1 {{ $order->order_status == 5 ? 'bg-success' : ($order->order_status == 7 ? 'bg-danger' : '') }}"
                                id="orderStatusNameBlock{{ $order->id }}"> {{ $status_name }} </span>
                        </div>
                        <div class="font_sub px-3 datesection me-lg-5"><span
                                class="mb-1">{{ __('order.Date') }}:</span>
                            {{ date('d-m-Y H:i', strtotime($order->created_at)) }}</div>
                    </button>
                    {{--<div class="d-flex align-items-center p-2 bg-light border-bottom ">
                        <a href="javascript:void(0)" data-id="{{ $order->id }}" class="showTrackOrderModal btn btn-info orderbutton text-nowrap">
                            <img src="{{ URL::asset('front-assets/images/icons/icon_refresh.png') }}" alt="refresh" id="refreshOrderImg{{ $order->id }}">{{ __('order.track_order') }}
                        </a>
                    </div>--}}
                </h2>
                <div id="collapse{{ $order->id }}" class="accordion-collapse collapse order_product_progress"
                     aria-labelledby="heading{{ $order->id }}" data-bs-parent="#Order_accordian">
                    <div class="accordion-body  bg-light-gray p-1">
                        <div class="row">
                            <div class="col-lg-8 pe-lg-1 d-flex align-content-stretch flex-wrap">
                                <div class="card p-3 radius w-100">
                                    <div class="card-body p-0">
                                        <div class="row rfqform_view g-2">
                                            <div class="col-md-12 d-flex align-items-center mt-1">
                                                <h5 class="dark_blue mb-0 "><img
                                                        src="{{ URL::asset('front-assets/images/icons/shopping-cart.png') }}"
                                                        alt=""> {{ __('order.order_detail') }}</h5>
                                                <div class="ms-auto">
                                                    <a href="javascript:void(0)" class="btn btn-info px-2 py-1"
                                                       style="border-radius: 4px; font-size: 12px;"
                                                       onclick="refreshAllOrderStautsData('{{$order->id}}', event)"><img
                                                            src="{{ URL::asset('front-assets/images/icons/icon_refresh.png') }}"
                                                            class="me-1" style="max-height: 12px;" alt="refresh"
                                                            id="refreshOrderImg{{ $order->id }}">{{ __('order.refresh') }}
                                                    </a>
                                                    <a class="btn btn-info px-2 py-1 ms-12 orderbutton btn_print_color text-nowrap"
                                                       style="font-size: 12px;"
                                                       href="{{route('dashboard-print-order',Crypt::encrypt($order->id))}}"
                                                       target="_blank"><img
                                                            src="{{ URL::asset('front-assets/images/icons/icon_print.png') }}"
                                                            alt="Print" class="pe-1" style="max-height: 12px;">Print</a>
                                                     @if(in_array($orderItemCategoryId,\App\Models\Category::SERVICES_CATEGORY_IDS) && ($orderlogisticProvided->logistic_check==1 && $orderlogisticProvided->logistic_provided ==0))
                                                        @if(isset($orderAirwayBill))
                                                            <a class="btn btn-warning px-2 py-1 ms-12 btn_airway_color text-nowrap downloadAirWayBill{{$orderAirwayBill->airwaybill_number}}"
                                                               style="font-size: 12px;"
                                                               onclick="downloadAirWayBill('{{ isset($orderAirwayBill->airwaybill_number) ? $orderAirwayBill->airwaybill_number : null }}')"
                                                               id="downloadAirWayBill{{$orderAirwayBill->airwaybill_number}}" title="{{__('admin.download_airwaybill')}}">
                                                                <img
                                                                    src="{{ URL::asset('front-assets/images/icons/icon_download.png') }}"
                                                                    alt="{{__('admin.download_airwaybill')}}" class="pe-1"
                                                                    style="max-height: 12px;">{{__('admin.airwaybill')}}</a>
                                                            <a id="shippingLabelPreview" download class="d-none"></a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('order.customer_name') }}:</label>
                                                <div> {{ $order->firstname . ' ' . $order->lastname }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('order.assigned_to') }}:</label>
                                                <div class="text-primary"> {{ getUserName($order->assigned_to) }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('order.order_number') }}:</label>
                                                <div>
                                                    <span class="tooltiphtml text-primary">
                                                        <div>{{ $order->order_number }}</div>
                                                        <div class="tooltip_section_RQO card card-body shadow p-0">
                                                            <div class="dark_blue_bg text-white p-2">Details</div>
                                                            <ul class="p-1 px-2 mb-0 ">
                                                                <li>RFQ No: <a class="showRfqModal"
                                                                               href="javascript:void(0);"
                                                                               data-id={{ $order->rfq_id }} role="button">{{ "BRFQ-" . $order->rfq_id }}</a></li>
                                                                <li class="text-center"><i class="fa fa-arrow-down"
                                                                                           aria-hidden="true"></i></li>
                                                                <li>Quote No: <a class="showQuoteModal"
                                                                                 href="javascript:void(0);"
                                                                                 data-id="{{ $order->quote_id }}"
                                                                                 role="button">{{ "BQTN-" . $order->quote_id }}</a></li>
                                                                <li class="text-center"><i class="fa fa-arrow-down"
                                                                                           aria-hidden="true"></i></li>
                                                                <li>Order No: <a name="" class="text-primary"
                                                                                 id="">{{ $order->order_number }}</a></li>
                                                            </ul>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('order.company_name') }}:</label>
                                                <div>{{ $order->company_name }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('order.mobile_number') }}:</label>
                                                <div>{{ countryCodeFormat($order->phone_code ,$order->rfq_mobile) }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('order.supplier_company_name') }}:</label>
                                                <div>
                                                    @if($order->supplier_profile_username)
                                                        <a href="{{ route('supplier.professional.profile',getSettingValueByKey('slug_prefix').$order->supplier_profile_username) }}"
                                                           target="_blank"
                                                           style="text-decoration: none;">{{ $order->supplier_company_name }}</a>
                                                    @else
                                                        {{ $order->supplier_company_name }}
                                                    @endIf
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('order.supplier_name') }}:</label>
                                                <div>{{ $order->supplier_name }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('order.payment_terms') }}:</label>
                                                <div>
                                                    @if($order->payment_type==1)
                                                        <span id="payment_terms{{ $order->id }}" data-payment_terms="1"
                                                              data-payment_ststus="{{ $order->order_status==9?9:8 }}"
                                                              class="badge rounded-pill mt-1 bg-danger">{{ __('order.credit') }} -{{$order->credit_days}}</span>
                                                    @elseif($order->payment_type==0)
                                                        <span id="payment_terms{{ $order->id }}" data-payment_terms="0"
                                                              data-payment_ststus="2"
                                                              class="badge rounded-pill mt-1 bg-success">{{ __('order.advance') }}</span>
                                                    @elseif($order->payment_type==3)

                                                        <span id="payment_terms{{ $order->id }}" data-payment_terms="0"
                                                              data-payment_ststus="2"
                                                              class="badge rounded-pill mt-1 bg-danger">{{
                                                        __('admin.lc')  }}</span>
                                                    @elseif($order->payment_type==4)
                                                        <span id="payment_terms{{ $order->id }}" data-payment_terms="0"
                                                              data-payment_ststus="2"
                                                              class="badge rounded-pill mt-1 bg-danger">{{
                                                        __('admin.skbdn')  }}</span>
                                                    @else
                                                        <span id="payment_terms{{ $order->id }}" data-payment_terms="0"
                                                              data-payment_ststus="2"
                                                              class="badge rounded-pill mt-1 bg-danger">{{
                                                            __('admin.credit')  }}</span>
                                                @endif<!--
                                                    @if($order->is_credit == ORDER_IS_CREDIT['CREDIT'])
                                                    <span id="payment_terms{{ $order->id }}" data-payment_terms="1" data-payment_ststus="{{ $order->order_status==9?9:8 }}" class="badge rounded-pill mt-1 bg-danger">{{ __('order.credit') }}</span>
                                                    @elseif($order->is_credit == ORDER_IS_CREDIT['LOAN_PROVIDER_CREDIT'])
                                                    <span id="payment_terms{{ $order->id }}" data-payment_terms="1" data-payment_ststus="{{ $order->order_status==9?9:8 }}" class="badge rounded-pill mt-1 bg-danger">{{ __('admin.loan_provider_credit') }}</span>
                                                    @else
                                                    <span id="payment_terms{{ $order->id }}" data-payment_terms="0" data-payment_ststus="2" class="badge rounded-pill mt-1 bg-success">{{ __('order.advance') }}</span>
                                                    @endif -->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('order.address') }}:</label>
                                                <div>{{ $order->address_line_1 .', '. $order->address_line_2 }}
                                                    , {!! $order->sub_district?($order->sub_district):'' !!}
                                                    , {!! $order->district?($order->district):'' !!}
                                                    , {{ $order->city_id > 0 ? getCityName($order->city_id) : $order->city }}
                                                    , {{ $order->state_id > 0 ? getStateName($order->state_id) : $order->state}}
                                                    , {{ $order->pincode }}</div>
                                            </div>
                                            @if(isset($order->customer_reference_id) && !empty($order->customer_reference_id))
                                                <div class="col-md-6">
                                                    <label>{{ __('rfqs.customer_ref_id') }}: </label>
                                                    <div>{{ $order->customer_reference_id }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 ps-lg-0">
                                <div class="card radius_1 h-100">
                                    <div class="card-header bg-white">
                                        <h5 class="dark_blue d-flex align-items-center mb-0 ">
                                            <img src="{{ URL::asset('front-assets/images/icons/truck-moving.png') }}"
                                                 class="pe-1" alt="">{{ __('order.track_order') }}
                                            <div class="refreshicon ms-auto">
                                                <a href="javascript:void(0);" data-id="{{ $order->id }}"
                                                   id="refreshOrderStautsData{{ $order->id }}"
                                                   class="refreshOrderStautsData"
                                                   onclick="refreshOrderStautsData('{{$order->id}}', event)">
                                                    <img
                                                        src="{{ URL::asset('front-assets/images/icons/icon_refresh.png') }}"
                                                        alt="refresh" id="refreshOrderImg{{ $order->id }}">
                                                </a>
                                            </div>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="bullet-line-list mb-0" id="orderStatusViewBlock{{ $order->id }}">

                                        <!-- @if($order->is_credit)
                                            {{view('dashboard/order/creditOrderStatusRefresh', ['order' => $order, 'orderTracks' => $order->orderTracks,'orderItemCategory'=>$orderItemCategory,'orderItemCategoryId'=>$orderItemCategoryId,'orderlogisticProvided'=>$orderlogisticProvided,'orderAirwayBill'=>$orderAirwayBill])}}
                                        @else
                                            {{view('dashboard/order/orderStatusRefresh', ['order' => $order, 'orderTracks' => $order->orderTracks,'orderItemCategory'=>$orderItemCategory,'orderItemCategoryId'=>$orderItemCategoryId,'orderlogisticProvided'=>$orderlogisticProvided,'orderAirwayBill'=>$orderAirwayBill])}}
                                        @endif -->


                                            @if($order->payment_type == 1 || $order->payment_type == 2)
                                                {{view('dashboard/order/creditOrderStatusRefresh', ['order' => $order, 'orderTracks' => $order->orderTracks,'orderItemCategory'=>$orderItemCategory,'orderItemCategoryId'=>$orderItemCategoryId,'orderlogisticProvided'=>$orderlogisticProvided,'orderAirwayBill'=>$orderAirwayBill])}}
                                            @else
                                                {{view('dashboard/order/orderStatusRefresh', ['order' => $order, 'orderTracks' => $order->orderTracks,'orderItemCategory'=>$orderItemCategory,'orderItemCategoryId'=>$orderItemCategoryId,'orderlogisticProvided'=>$orderlogisticProvided,'orderAirwayBill'=>$orderAirwayBill])}}
                                            @endif
                                        </ul>
                                        <a href="javascript:void(0)" id="repeatRfq_{{$order->rfq_id}}"
                                           class="repeatRfq w-100 btn btn-outline-primary mt-2" data-isRepeatOrder="1"
                                           data-rfq_id="{{$order->rfq_id}}"
                                           title="{{ __('dashboard.repeat_order')}}">{{ __('dashboard.repeat_order')}}</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @if($isGroupOrder)
                            <div class="card radius_1 my-1 w-100">
                                <div class="card-body p-2">
                                    <div class="row rfqform_view g-2">
                                        <div class="col-md-3">
                                            <div class="bg-success bg-opacity-10 p-1 ">
                                                <label>{{ __('order.credit_amount') }}</label>
                                                <div class="d-flex align-items-center position-relative">
                                                    Rp {{getBuyerRefundAmount($order->quote_id)}}<span
                                                        class="ms-auto me-1" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ __('order.credit_amount_explain') }}"><img
                                                            src="{{ URL::asset('front-assets/images/icons/icon_explain.png') }}"
                                                            width="12px"></span></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="bg-light p-1 h-100">
                                                <label>{{ __('order.avail_discount') }}</label>
                                                <div
                                                    class="text-dark d-flex align-items-center"> {{($order->group_members_discount) ? $order->group_members_discount->avail_discount : '0'}}
                                                    % <span class="ms-auto me-1" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="{{ __('order.avail_discount_explain') }}"><img
                                                            src="{{ URL::asset('front-assets/images/icons/icon_explain.png') }}"
                                                            width="12px"></span></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="bg-light p-1">
                                                <label>{{ __('order.achieved_discount') }}</label>
                                                <div
                                                    class="d-flex align-items-center"> {{($order->group_members_discount) ? $order->group_members_discount->achieved_discount : '0'}}
                                                    % <span class="ms-auto me-1" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="{{ __('order.achieved_discount_explain') }}"><img
                                                            src="{{ URL::asset('front-assets/images/icons/icon_explain.png') }}"
                                                            width="12px"></span></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="bg-light p-1">
                                                <label>{{ __('order.prospect_discount') }}</label>
                                                <div
                                                    class="text-dark d-flex align-items-center"> {{ ($order->group_members_discount) ? $order->group_members_discount->prospect_discount : '0'}}
                                                    % <span class="ms-auto me-1" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="{{ __('order.prospect_discount_explain') }}"><img
                                                            src="{{ URL::asset('front-assets/images/icons/icon_explain.png') }}"
                                                            width="12px"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="card radius_1 my-1 w-100">
                            <div id="view_order_sub_status_refresh{{$order->id}}">
                                {{view('dashboard/order/viewOrderSubStatusAllRefresh', ['order' => $order,'orderItemCategory'=>$orderItemCategory,'orderItemCategoryId'=>$orderItemCategoryId,'orderlogisticProvided'=>$orderlogisticProvided])}}
                            </div>
                        </div>
                        <div class="row px-0 mx-0 w-100">
                            <div class="row px-0 mx-0 w-100">
                                <div class="col-md-12 px-0">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="row rfqform_view g-2">
                                                <div class="col-md-12 text-danger">
                                                    <p class="mb-0"><small>
                                                            {{ __('order.transaction_note1') }}<br>
                                                            {{ __('order.transaction_note2') }}
                                                        </small>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-md-12">
            <div class="alert alert-danger radius_1 text-center fs-6 fw-bold">{{ __('rfqs.No_order_found') }}</div>
        </div>
    @endif
</div>


<!-- Modal -->
<div class="modal" tabindex="-1" id="paymentDoneModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('order.payment_confirm_head') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h6 class="mb-4">{{ __('order.confirm_message') }}</h6>
                <button type="button" class="btn btn-primary"
                        data-bs-dismiss="modal">{{ __('order.confirm') }}</button>
                <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">{{ __('order.Close') }}</button>
            </div>

        </div>
    </div>
</div>

{{-- show rfq details on hover --}}
<div class="modal fade" id="rfqDetailsModal" data-bs-backdrop="true" tabindex="-1"
     aria-labelledby="rfqDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content radius_1 shadow-lg" id="rfqDetailsModalBlock"></div>
    </div>
</div>

{{-- show quote details on hover --}}
<div class="modal fade" id="rfqQuoteDetailsModal" data-bs-backdrop="true" tabindex="-1"
     aria-labelledby="rfqQuoteDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content radius_1 shadow-lg" id="rfqQuoteDetailsModalBlock">
            {{-- here quote detail shown from js --}}
        </div>
    </div>
</div>

{{-- Airway Bill Model Start--}}
<div class="modal fade" id="AirwaybillModal" tabindex="-1" aria-labelledby="AirwaybillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" id="generateAirwaybillNumberBlock">

        </div>
    </div>
</div>
{{-- Airway Bill Model End--}}
<div class="modal fade" id="trackorderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title flex-grow-1" id="exampleModalLabel">{{ __('order.track_order') }}</h5>
                <div id="trackorderModalOrderNumber"></div>
                <div class="refreshicon  mx-3">
                    <a href="javascript:void(0);" data-id="" class="refreshOrderStautsDataFromTrackModal"><img
                            src="{{ URL::asset('front-assets/images/icons/icon_refresh.png') }}" alt="refresh"
                            id="refreshOrderImgTrack" class="">
                    </a>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <ul class="bullet-line-list" id="orderStatusTrackBlock">

                </ul>
            </div>

        </div>
    </div>
</div>
<div class="modal" tabindex="-1" id="UploadModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.doc-panding') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <form name="uploadOrderDoc" data-parsley-validate id="uploadOrderDoc" autocomplete="off"
                      enctype="multipart/form-data">
                    @csrf
                    <h6 class="mb-4">{{ __('admin.lc-doc') }}</h6>
                    <h6 class="mb-4">{{ __('admin.qc-doc') }}</h6>
                    <div class="row mb-4 justify-content-center">
                        <div class="col-md-10">
                            <!-- <label for="" class="form-label">NIB File</label> -->
                            <div class="d-flex justify-content-center">
                                <span class="">
                                    <input type="file" data-parsley-required-message="{{ __('admin.select_file') }}"
                                           data-parsley-errors-container="#image_error" required name="orderdoc"
                                           id="orderdoc" class="form-control" accept=".jpg,.png,.gif,.jpeg,.pdf"
                                           hidden="">
                                    <label id="upload_btn" for="orderdoc">{{ __('profile.browse') }} </label>
                                </span>
                                <div id="file-orderdoc" class="d-flex align-items-center">
                                    <span id="filenamedoc" class="ms-2"></span>
                                </div>
                            </div>
                            <div id="image_error"></div>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <a data-boolean="false" class="btn btn-primary px-4 py-2 mb-1 saveOrderdoc"
                           href="javascript:void(0)" id="saveOrderdoc"><img
                                src="{{ URL::asset('front-assets/images/upload-arrow.png') }}" width="20px"
                                alt="Post Requirement" class="pe-1">
                            {{ __('admin.Upload_doc') }}</a>
                    </div>
                    <input type="hidden" name="orderdata" id="orderdata">
                    <span id="filenamedoc1" class="ms-2 hide"></span>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ URL::asset('front-assets/js/nextpre.js') }}" defer></script>
<script>
    function downloadimg(id, fieldName, name) {
        event.preventDefault();
        var data = {
            id: id,
            fieldName: fieldName
        }
        $.ajax({
            url: "{{ route('dashboard-download-image-ajax') }}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: data,
            type: 'POST',
            xhrFields: {
                responseType: 'blob'
            },
            success: function (response) {
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = name;
                link.click();
            },
        });
    }

    //open model
    $(document).on('click', '.js-openmodel', function (e) {
        id = $(this).attr('data-id');
        $('#UploadModal').modal('show')
        $('#orderdata').val(id);
        $('#filenamedoc').html('');
    })
    // save doc
    $(document).on('click', '.saveOrderdoc', function (e) {
        if ($('#uploadOrderDoc').parsley().validate()) {
            $('.saveOrderdoc').prop('disabled', true);
            var formData = new FormData($('#uploadOrderDoc')[0]);
            $.ajax({
                url: "{{ route('upload-order-doc') }}",
                data: formData,
                type: 'POST',
                contentType: false,
                processData: false,
                success: function (successData) {
                    $('#UploadModal').modal('hide')
                    $('.qcStatusUpdated').attr('data-upload-doc', 1)
                    new PNotify({
                        text: '{{ __('admin.upload.doc') }}',
                        type: 'success',
                        styling: 'bootstrap3',
                        animateSpeed: 'fast',
                        delay: 1000
                    });
                    $('.saveOrderdoc').prop('disabled', false);
                    refreshAllOrderStautsData($('#orderdata').val(), e)
                    refreshOrderStautsData($('#orderdata').val(), e);
                    $('#uploadOrderDoc').trigger("reset");
                },
                error: function () {
                    console.log('error');
                }
            });
        }
    });
    $(document).on('click', '.qcStatusUpdated', function (e) {
        let orderId = $(this).attr('data-order-id');
        if ($(this).attr('data-payment-type') > 2) {
            if ($(this).attr('data-upload-doc') == '') {
                $('#UploadModal').modal('show')
                $('#orderdata').val(orderId);
                $('#filenamedoc').html('');
                return false;
            }
        }
        let orderItemId =[];
         if ($(this).attr('data-is-service') == 1){
             orderItemId = orderItemId;
             var isServiceOrder = 1;
         }else{
             orderItemId = $(this).attr('data-orderitem-id');
             var isServiceOrder = 0;
         }

        let qcStatus = $(this).val();
        let icon = "front-assets/images/icon_smiley1.png";
        let title = "{{ __('order.quality_good_title') }}";
        let text = "{{ __('order.quality_confirm_message_new') }}";
        let selectedStatus = 8;

        if (qcStatus == 2) {
            selectedStatus = 7;
            icon = "front-assets/images/icon_sad1.png";
            title = "{{ __('order.quality_not_good_title') }}";
            text = "{{ __('order.quality_not_good_confirm_message_new') }}";
        }
        swal({
            title: title,
            text: text,
            icon: icon,
            buttons: ["{{ __('order.no') }}", "{{ __('order.confirm') }}"],
            dangerMode: false,
        })
            .then((changeit) => {
                if (changeit) {
                    var data = {
                        selectedStatusID: selectedStatus,
                        orderId: orderId,
                        orderItemId: orderItemId,
                        isServiceOrder:isServiceOrder,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }
                    $.ajax({
                        url: "{{ route('dashboard-order-item-status-change-ajax') }}",
                        data: data,
                        type: 'POST',
                        success: function (successData) {
                            if (successData.success) {
                                if (successData.is_all_item_delivered) {
                                    //$('#refreshOrderStautsData'+orderId).trigger('click');
                                }
                                new PNotify({
                                    text: "{{ __('dashboard.order_status_change_success_message') }}",
                                    type: 'success',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 1000
                                });
                                if (isServiceOrder != 1) {
                                    $('#collapseExample' + orderItemId).html(successData.orderItemStatusHtml);
                                    $('#orderItemStatusNameBlock' + orderItemId).html(successData.order_status_name);
                                }
                            } else {
                                new PNotify({
                                    text: "{{ __('admin.something_went_wrong') }}",
                                    type: 'warning',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 1000
                                });
                            }
                            if (isServiceOrder == 1) {
                                $('#orderSection').trigger('click');
                                setTimeout(function () {
                                    $('#collapse' + orderId).collapse('show')
                                }, 5000)
                            }
                        },
                    });
                } else {
                    $(this).prop('checked', false);
                }
            });
    });

    $(document).on('click', '.refreshOrderStautsData', function (e) {
        e.stopImmediatePropagation();
        e.preventDefault();
        //$('#mainContentSection').html(defaultData);
        var orderId = $(this).attr('data-id');
        $('#refreshOrderImg' + orderId).addClass('rotate');
        $.ajax({
            url: "{{ route('dashboard-refresh-order', '') }}" + "/" + orderId,
            type: 'GET',
            success: function (successData) {
                defaultData = successData.html;
                $('#orderStatusViewBlock' + orderId).html(successData.html);
                countDownTimer(orderId);
                $('#orderStatusViewBlock' + orderId + ' .qcStatusUpdated').attr('name',
                    'orderStatusViewBlockRadioBtn' + Math.random());

                $('#orderStatusNameBlock' + orderId).html(successData.order_status_name);
                $('#orderStatusNameBlock' + orderId).removeClass('bg-primary').removeClass('bg-success');
                //$('#pay' + orderId).attr('href', 'javascript:void(0)').hide();
                if (successData.order_status == 5) {
                    $('#orderStatusNameBlock' + orderId).addClass('bg-success');
                } else {
                    if (successData.order_status == 10) {
                        $('#payment_terms' + orderId).removeClass('bg-danger').addClass('bg-success').html('{{ __('order.advance') }}');
                    }
                    $('#orderStatusNameBlock' + orderId).addClass('bg-primary');
                }

                setTimeout(function () {
                    $('#refreshOrderImg' + orderId).removeClass('rotate');
                }, 500);

            },
            error: function () {
                console.log('error');
            }
        });
    });

    $(document).on('click', '.refreshOrderStautsDataFromTrackModal', function (e) {
        e.stopImmediatePropagation();
        e.preventDefault();
        //$('#mainContentSection').html(defaultData);
        var orderId = $(this).attr('data-id');
        $('#refreshOrderImgTrack').addClass('rotate');
        $.ajax({
            url: "{{ route('dashboard-refresh-order', '') }}" + "/" + orderId,
            type: 'GET',
            success: function (successData) {
                defaultData = successData.html;
                $('#orderStatusTrackBlock').html(successData.html);
                $('#orderStatusNameBlock' + orderId).html(successData.order_status_name);
                setTimeout(function () {
                    $('#refreshOrderImgTrack').removeClass('rotate');
                }, 500);

            },
            error: function () {
                console.log('error');
            }
        });
    });

    $(document).on('click', '.showTrackOrderModal', function (e) {
        e.stopImmediatePropagation();
        e.preventDefault();
        var orderId = $(this).attr('data-id');
        $('#orderStatusViewBlock' + orderId + ' .qcStatusUpdated').attr('name', 'orderStatusViewBlockRadioBtn' +
            Math.random());
        $('.refreshOrderStautsDataFromTrackModal').attr('data-id', orderId);
        $('#refreshOrderImgTrack').addClass('rotate');
        $.ajax({
            url: "{{ route('dashboard-refresh-order', '') }}" + "/" + orderId,
            type: 'GET',
            success: function (successData) {
                defaultData = successData.html;
                // $('#orderStatusViewBlock' + orderId).html(successData.html);
                $('#trackorderModal #orderStatusTrackBlock').html(successData.html);
                $('#orderStatusNameBlock' + orderId).html(successData.order_status_name);
                setTimeout(function () {
                    $('#refreshOrderImgTrack').removeClass('rotate');
                }, 500);
                $('#trackorderModalOrderNumber').html(successData.order_number);
                $('#trackorderModal').modal('show');
            },
            error: function () {
                console.log('error');
            }
        });
    });

    $(document).on('click', '.generate-pay-link', function (e) {
        e.preventDefault();

        let orderId = $(this).attr('data-id');
        $(this).html('<img src="{{ URL::asset("front-assets/images/icons/icon_refresh.png") }}" alt="refresh" class="rotate">');
        let that = $(this);
        $.ajax({
            url: "{{ route('generate-pay-link', '') }}" + "/" + orderId,
            type: 'GET',
            success: function (successData) {
                if (successData.success) {
                    $('#refreshOrderImg' + orderId).trigger('click');
                } else {
                    that.html("{{ __('order.pay_generate_button') }}");
                    swal({
                        text: successData.message,
                        icon: "warning",
                        dangerMode: true,
                    });
                }
            },
            error: function () {
                that.html("{{ __('order.pay_generate_button') }}");
                console.log('error');
            }
        });
    });

    function countDownTimer(orderId) {
        let selector = $('#remainingTime' + orderId);
        let refreshOrder = $('#refreshOrderImg' + orderId);
        let duration = parseInt(selector.attr('data-remaining-seconds'));
        let timer = duration, hours, minutes, seconds;
        setInterval(function () {
            hours = parseInt((timer / 3600) % 48, 10)
            minutes = parseInt((timer / 60) % 60, 10)
            seconds = parseInt(timer % 60, 10);

            hours = hours < 10 ? "0" + hours : hours;
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            if (hours == 0 && minutes == 0 && seconds == 0) {
                refreshOrder.trigger('click');
            }

            selector.text(hours + ":" + minutes + ":" + seconds);

            --timer;
        }, 1000);
    }

    $(function () {
        $('.remainingTime').each(function () {
            countDownTimer($(this).attr('data-id'))
        });
    });

    function refreshOrderStautsData(orderId, e) {
        e.stopImmediatePropagation();
        e.preventDefault();
        //$('#mainContentSection').html(defaultData);
        //var orderId = data.attr('data-id');
        $('#refreshOrderImg' + orderId).addClass('rotate');
        $.ajax({
            url: "{{ route('dashboard-refresh-order', '') }}" + "/" + orderId,
            type: 'GET',
            success: function (successData) {
                defaultData = successData.html;
                $('#orderStatusViewBlock' + orderId).html(successData.html);
                countDownTimer(orderId);
                $('#orderStatusViewBlock' + orderId + ' .qcStatusUpdated').attr('name',
                    'orderStatusViewBlockRadioBtn' + Math.random());

                $('#orderStatusNameBlock' + orderId).html(successData.order_status_name);
                $('#orderStatusNameBlock' + orderId).removeClass('bg-primary').removeClass('bg-success');
                //$('#pay' + orderId).attr('href', 'javascript:void(0)').hide();
                if (successData.order_status == 5) {
                    $('#orderStatusNameBlock' + orderId).addClass('bg-success');
                } else {
                    if (successData.order_status == 10) {
                        $('#payment_terms' + orderId).removeClass('bg-danger').addClass('bg-success').html('{{ __('order.advance') }}');
                    }
                    $('#orderStatusNameBlock' + orderId).addClass('bg-primary');
                }

                setTimeout(function () {
                    $('#refreshOrderImg' + orderId).removeClass('rotate');
                }, 500);

            },
            error: function () {
                console.log('error');
            }
        });
    }

    function refreshOrdersSubStatus(orderId, e) {
        e.stopImmediatePropagation();
        e.preventDefault();
        $.ajax({
            url: "{{ route('dashboard-refresh-order-sub-status', '') }}" + "/" + orderId,
            type: 'GET',
            success: function (successData) {
                if (successData.success) {
                    $('#view_order_sub_status_refresh' + orderId).html('');
                    $('#view_order_sub_status_refresh' + orderId).html(successData.html);
                }

                setTimeout(function () {
                    $('#refreshOrderImg' + orderId).removeClass('rotate');
                }, 500);

            },
            error: function () {
                console.log('error');
            }
        });
    }

    /*********start: Advance/LC/SKBDN status change from buyer.**************/
    function orderStatusChange(selector, is_validate = 0) {
        let selectedStatus = selector.attr('data-status-id');
        let orderId = selector.attr('data-order-id');
        $('#orderStatusChange' + orderId).css('pointer-events', 'none');
        let data = {
            selectedStatusID: selectedStatus,
            orderId: orderId,
            is_validate: is_validate,
            is_backend_request: 0,
            _token: $('meta[name="csrf-token"]').attr('content')
        }
        $.ajax({
            url: "{{ route('buyer-order-status-change-ajax') }}",
            data: data,
            type: 'POST',
            dataType: 'JSON',
            success: function (successData) {
                console.log(successData);
                if (successData.success == true) {
                    if (successData.valid !== undefined && successData.valid == 1) {
                        swal({
                            title: "{{ __('admin.delete_sure_alert') }}",
                            text: "{{ __('admin.order_status_change_text') }}",
                            icon: "/assets/images/info.png",
                            buttons: ["No", "Yes"],
                            dangerMode: false,
                        }).then((isConfirm) => {
                            if (isConfirm) {
                                $('#orderStatusChange_loading' + orderId).removeClass('d-none');
                                $('#orderStatusChange_loading' + orderId).addClass('d-flex');
                                orderStatusChange(selector, successData.valid);
                            } else {
                                $('#refreshOrderStautsData' + orderId).trigger('click');
                            }
                        });$('#refreshOrderStautsData' + orderId).trigger('click');
                    } else {
                        $('#refreshOrderStautsData' + orderId).trigger('click');
                        new PNotify({
                            text: 'Order status changed successfully',
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });
                    }
                } else {
                    $('#refreshOrderStautsData' + orderId).trigger('click');
                }
                $('#orderStatusChange_loading' + orderId).addClass('d-none');
                $('#orderStatusChange_loading' + orderId).removeClass('d-flex');
            },
            error: function () {
                console.log('error');
                $('#refreshOrderStautsData' + orderId).trigger('click');
                swal({
                    title: '{{ __('admin.something_error_message') }}',
                    icon: "/assets/images/info.png",
                    confirmButtonText: "{{__('admin.ok')}}",
                    dangerMode: false,
                });
            }
        });
    }

    /*********end: Advance/LC/SKBDN status change from buyer.**************/
    /*********start: Credit from buyer.**************/
    function creditOrderStatusChange(selector, is_validate = 0) {
        let selectedStatus = selector.attr('data-status-id');
        let orderId = selector.attr('data-order-id');
        $('#creditOrderStatusChange' + orderId).css('pointer-events', 'none');
        let data = {
            selectedStatusID: selectedStatus,
            orderId: orderId,
            is_validate: is_validate,
            is_backend_request: 0,
            _token: $('meta[name="csrf-token"]').attr('content')
        }
        $.ajax({
            url: "{{ route('buyer-credit-order-status-change-ajax') }}",
            data: data,
            type: 'POST',
            dataType: 'JSON',
            success: function (successData) {
                if (successData.success == true) {
                    if (successData.valid !== undefined && successData.valid == 1) {
                        swal({
                            title: "{{ __('admin.delete_sure_alert') }}",
                            text: "{{ __('admin.order_status_change_text') }}",
                            icon: "/assets/images/info.png",
                            buttons: ["No", "Yes"],
                            dangerMode: false,
                        }).then((isConfirm) => {
                            if (isConfirm) {
                                $('#creditOrderStatusChange_loading' + orderId).removeClass('d-none');
                                $('#creditOrderStatusChange_loading' + orderId).addClass('d-flex');
                                creditOrderStatusChange(selector, successData.valid);
                            } else {
                                $('#refreshOrderStautsData' + orderId).trigger('click');
                            }
                        });
                    } else {
                        $('#refreshOrderStautsData' + orderId).trigger('click');
                        new PNotify({
                            text: 'Order status changed successfully',
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });
                    }
                } else {
                    $('#refreshOrderStautsData' + orderId).trigger('click');
                }
                $('#creditOrderStatusChange_loading' + orderId).addClass('d-none');
                $('#creditOrderStatusChange_loading' + orderId).removeClass('d-flex');
            },
            error: function () {
                console.log('error');
                $('#refreshOrderStautsData' + orderId).trigger('click');
                swal({
                    title: '{{ __('admin.something_error_message') }}',
                    icon: "/assets/images/info.png",
                    confirmButtonText: "{{__('admin.ok')}}",
                    dangerMode: false,
                });
            }
        });
    }

    /*********end: Credit status change from buyer.**************/
    function refreshAllOrderStautsData(OrderId, e) {
        refreshOrderStautsData(OrderId, e);
        refreshOrdersSubStatus(OrderId, e);
    }

    function productWiseRefresh(OrderId, QuoteItemId, e) {
        /*e.stopImmediatePropagation();
        e.preventDefault();*/
        var url = "{{ route('dashboard-refresh-single-order-item-status', [':orderId', ':quoteItemId']) }}";
        url = url.replace(':orderId', OrderId);
        url = url.replace(':quoteItemId', QuoteItemId);
        $.ajax({
            url: url,
            type: 'GET',
            success: function (successData) {
                if (successData.success) {
                    $('#collapseExample' + QuoteItemId).html('');
                    $('#collapseExample' + QuoteItemId).html(successData.html);
                    $('#openProduct' + OrderId).toggleClass('tab_col');
                }
            },
            error: function () {
                console.log('error');
            }
        });
    }

    $(document).on('click', '.showRfqModal', function () {
        $('#rfqDetailsModal').modal('hide');
        var rfqId = $(this).attr('data-id');
        $.ajax({
            url: "{{ route('dashboard-get-rfq-details-ajax', '') }}" + "/" +
                rfqId,
            type: 'GET',
            success: function (successData) {
                if (successData.html) {
                    $('#rfqDetailsModalBlock').html(successData.html);
                    $('#rfqDetailsModal').modal('show');
                }
            },
            error: function () {
                console.log('error');
            }
        });
    });

    $(document).on('click', '.showQuoteModal', function () {
        var quoteId = $(this).attr('data-id');
        $.ajax({
            url: "{{ route('dashboard-get-rfq-quotes-details-ajax', '') }}" + "/" +
                quoteId,
            type: 'GET',
            success: function (successData) {
                if (successData.html) {
                    $('#rfqQuoteDetailsModalBlock').html(successData.html);
                    $('#rfqQuoteDetailsModal').modal('show');
                }
            },
            error: function () {
                console.log('error');
            }
        });
    });
    //name show
    $(document).on("change", "#orderdoc", function (e) {
        var $input = $(this);
        var inputFiles = this.files;
        let file = inputFiles[0];
        let size = Math.round((file.size / 1024))
        if (size > 3000) {
            swal({
                icon: 'error',
                title: '',
                text: '{{ __('profile.file_size_under_3mb') }}',
            })
            return false;
        }
        let fileName = file.name;
        $('#filenamedoc').html(fileName);
    })
    //remove file
    $(document).on("click", ".removeFile", function (e) {
        e.preventDefault();
        orderId = $(this).attr("data-id");
        let name = $(this).attr("data-name");
        let type = $(this).attr("data-type");
        let data = {
            fileName: name,
            id: $(this).attr("data-id"),
            _token: $('meta[name="csrf-token"]').attr("content"),
            type: type
        };
        swal({
            title: '{{ __('profile.are_you_sure') }}',
            text: '{{ __('profile.once_deleted_you_will') }}',
            icon: "/assets/images/bin.png",
            buttons: ['{{ __('profile.change_no') }}', '{{ __('profile.delete') }}'],
            dangerMode: true,
        }).then((deleteit) => {
            if (deleteit) {
                $.ajax({
                    url: "{{ route('profile-company-file-delete-ajax') }}",
                    data: data,
                    type: "POST",
                    success: function (successData) {
                        $("#file-" + name).html('');
                        refreshAllOrderStautsData(orderId, e)
                        refreshOrderStautsData(orderId, e);
                    },
                    error: function () {
                        console.log("error");
                    },
                });
            }
        });
    });

    $(document).ready(function () {
        SnippetSearchList.init()
        $('#pickup_date').datepicker({
            startDate: date,
            format: 'dd-mm-yyyy',
        });
        $('#pickup_date').on('changeDate', function (ev) {
            $(this).datepicker('hide');
        });
    });

    var SnippetSearchList = function () {

        //Show result section
        $(".showSearchData").hide();

        // Input box search with custom text
        searchData = function () {
            // $(document).on('click', '.approvalSearch', function(e) {
            $('.customSearch').click(function () {
                let customSearch = $('#customSearch').val();
                let customStatusSearch = $('#customStatusSearch').val();
                if (customSearch || customStatusSearch) {
                    $.ajax({
                        url: "{{ route('dashboard-list-order-ajax') }}",
                        data: {customStatusSearch: customStatusSearch, customSearch: customSearch},
                        type: 'GET',
                        success: function (successData) {
                            orderSection = successData.html;
                            $('#mainContentSection').html(successData.html);
                            $('html, body').animate({
                                scrollTop: $("#mainContentSection").offset()
                            }, 50);
                            $('#buyerOrderNotification').addClass('d-none');

                            //Show custom search count
                            if ((successData.searchDataCount)) {
                                $("#searchResultCount").html((successData.searchDataCount));
                            }

                            //Show result section
                            $(".showSearchData").show();

                        },
                        error: function () {
                            console.log('error');
                        }
                    });
                    $("#searchTextErr").html("");
                } else {
                    $("#searchTextErr").html("Please enter valid keyword");
                    return false;
                }
            });

            $('#customSearch').keypress(function (e) {
                $("#searchTextErr").html("");
                //Enter key pressed
                if (e.keyCode == 13) {
                    $('.customSearch').click();       //Trigger search button click event
                }
            });
        },
            //End

            //On clear searched text (cancel icon), we will get all the data
            clearSearchData = function () {
                $('input[type=search]').on('search', function () {
                    $('.customSearch').click();
                });
            },

            //Search by quote status dropdown
            customStatusSearch = function () {
                $('#customStatusSearch').change(function () {
                    let customStatusSearch = $('#customStatusSearch').val();
                    //alert('ssd');
                    //Call "searchData" function on change of quote status
                    $('.customSearch').click();

                });
            };

        return {
            init: function () {
                searchData(),
                    clearSearchData(),
                    customStatusSearch()
            },
        }
    }(1);

    /* Show AirwayBill generate model */
    $(document).on('click', '.showAirwayBillModel', function () {
        var orderId = $(this).attr('data-id');
        $.ajax({
            url: "{{ route('dashboard-get-order-quote-details-ajax', '') }}" + "/" +
                orderId,
            type: 'GET',
            success: function (successData) {
                if (successData.html) {
                    $.getScript("{{ URL::asset('front-assets/js/nextpre.js') }}");
                    $('#generateAirwaybillNumberBlock').html(successData.html);
                    $('#pickupInfo' + orderId).addClass('js-active').trigger("click");
                    $('#pickupAddress' + orderId).addClass('js-active');

                    $('#AirwaybillModal').modal('show');
                }
            },
            error: function () {
                console.log('error');
            }
        });
    });
    $(document).on('click', '.closeAirwaybillModel', function () {
        $('.showAirwayBillModel').off('click');
        $('#generateAirwaybillNumberBlock').html('');
        $('#AirwaybillModal').modal('hide');
    });

    //Create group after adding pickup date-time and address
    $(document).on('click', '.generateAirwaybill', function (e) {
        var orderId = $(this).attr('data-id');
        var supplierId = $("#supplier_id" + orderId).val();
        var order_item_ids = $('#order_item_ids' + orderId).val();
        $('#submitAirwaybillForm' + orderId).prop('disabled', true);
        $('#cancelPickupBtn' + orderId).prop('disabled', true);
            if ($('#AirwaybillForm' + orderId).parsley().validate()){
                $('#datetime_missing'+ orderId).html('');
                var formData = new FormData($('#AirwaybillForm' + orderId)[0]);
                formData.append('order_item_ids', $('#order_item_ids' + orderId).val());
                $.ajax({
                    url: "{{ route('dashboard-order-pickup-batch-ajax') }}",
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    beforeSend:function(){
                        $('#AirwaybillForm'+orderId).css('pointer-events','none');
                        $('#AirwaybillForm'+orderId).css('opacity','0.5');
                        $('#airwayBillPopupLodder'+orderId).removeClass('d-none');
                        $('#airwayBillPopupLodder'+orderId).addClass('airwayBillPopupLodder-show');
                    },
                    success: function (successBatchData) {
                        if (successBatchData.success == true) {
                            //Generate AirWayBill Number
                            $.ajax({
                                url: "{{ route('dashboard-generate-airwaybill-ajax') }}",
                                type: 'POST',
                                data: {
                                    orderId: orderId,
                                    supplierId: supplierId,
                                    batch_id: successBatchData.id,
                                    pickup_date: successBatchData.order_pickup,
                                    receiver_name: $('#receiver_name'+orderId).val(),
                                    receiver_company_name: $('#receiver_company_name'+orderId).val(),
                                    receiver_email_address: $('#receiver_email_address'+orderId).val(),
                                    receiver_pic_phone: $('#receiver_pic_phone'+orderId).val(),
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (successData) {
                                    $('#AirwaybillModal').modal('hide');
                                    $('#submitAirwaybillForm' + orderId).prop('disabled', false);
                                    $('#cancelPickupBtn' + orderId).prop('disabled', false);
                                    $('#AirwaybillForm' + orderId).parsley().reset();
                                    $('#order_item_ids').val(order_item_ids);
                                    var selectorValue = 2;
                                    var orderItemId = successBatchData.id;
                                    if (successData.success == true) {
                                        new PNotify({
                                            text: successData.msg,
                                            type: 'success',
                                            styling: 'bootstrap3',
                                            animateSpeed: 'fast',
                                            delay: 2000
                                        });
                                        orderItemStatusChange(selectorValue, orderItemId, orderId);
                                    } else {
                                        if(successData.msg == 'Charged weight should not be more than 1999 kg'){
                                            new PNotify({
                                                text: successData.msg,
                                                type: 'error',
                                                styling: 'bootstrap3',
                                                animateSpeed: 'fast',
                                                delay: 2000
                                            });
                                            new PNotify({
                                                text: "{{__('admin.batch_generated_successfully')}}",
                                                type: 'success',
                                                styling: 'bootstrap3',
                                                animateSpeed: 'fast',
                                                delay: 2000
                                            });
                                            orderItemStatusChange(selectorValue, orderItemId, orderId);
                                        }
                                    }
                                    $('#submitAirwaybillForm' + orderId).prop('disabled', false);
                                    $('#cancelPickupBtn' + orderId).prop('disabled', false);
                                }
                            });
                        }
                    }
                });
            }else{
                var pickupDateTime = $('#pickup_date'+orderId).val();
                if(pickupDateTime == ''){
                    $('#datetime_missing'+ orderId).html('{{__('dashboard.this_field_required')}}')
                }else{
                    $('#datetime_missing'+ orderId).html('');
                }
                $('#submitAirwaybillForm' + orderId).prop('disabled', false);
                $('#cancelPickupBtn' + orderId).prop('disabled', false);
            }
    });

    function orderItemStatusChange(selector, orderItemId = 0, orderId, is_validate = 0) {
        var selectedItemIds = $('#order_item_ids' + orderId).val();
        var selectedStatus = selector;
        var batchId = orderItemId;
        var orderId = orderId;
        var data = {
            selectedStatusID: selectedStatus,
            orderId: orderId,
            batchId: batchId,
            orderItemId: [],
            selectedItemIds: selectedItemIds,
            is_validate: is_validate,
            is_backend_request: 0,
            _token: $('meta[name="csrf-token"]').attr('content')
        }
        $.ajax({
            url: "{{ route('dashboard-order-item-status-change-ajax') }}",
            data: data,
            type: 'POST',
            dataType: 'JSON',
            success: function (successData) {
                if (successData.success == true) {
                    if (successData.valid !== undefined && successData.valid == 1) {
                        orderItemStatusChange(selector, orderItemId, orderId, successData.valid);
                    } else {
                        new PNotify({
                            text: 'Order item status changed successfully',
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });
                    }
                }
                $('#orderSection').trigger('click');
                setTimeout(function(){
                    $('#collapse'+orderId).collapse('show')
                },5000)
                $('#submitAirwaybillForm' + orderId).prop('disabled', false);
                $('#cancelPickupBtn' + orderId).prop('disabled', false);
                $('#AirwaybillForm'+orderId).removeClass('processing-status');
                $('#airwayBillPopupLodder'+orderId).removeClass('d-none');
                $('#airwayBillPopupLodder'+orderId).addClass('airwayBillPopupLodder-show');
                $('#AirwaybillForm'+orderId).css('opacity','1');
                $('#AirwaybillForm'+orderId).css('pointer-events','all');
            },
            error: function () {
                console.log('error');
                swal({
                    title: '{{ __('admin.something_error_message') }}',
                    icon: "/assets/images/info.png",
                    confirmButtonText: "{{__('admin.ok')}}",
                    dangerMode: false,
                });

                $('#orderSection').trigger('click');
                setTimeout(function(){
                    $('#collapse'+orderId).collapse('show')
                },5000)
                $('#submitAirwaybillForm' + orderId).prop('disabled', false);
                $('#cancelPickupBtn' + orderId).prop('disabled', false);
            }
        });
    }

    function downloadAirWayBill(airWayBillNumber) {
        let clickedLinkName = '.downloadAirWayBill' + airWayBillNumber;
        if (airWayBillNumber) {
            $.ajax({
                url: "{{ route('dashboard-get-shipping-label', '') }}" + "/" + airWayBillNumber,
                type: 'GET',
                beforeSend: function () {
                    $(clickedLinkName).addClass('add-airwaybill-download-process-cursor');
                    $('#downloadAirWayBillDiv').addClass('pointer-event-none');
                },
                success: function (response) {
                    if (response.status == true) {
                        $('#shippingLabelPreview').attr('href', response.pdfUrl);
                        $('#shippingLabelPreview')[0].click();
                    } else {
                        $.toast({
                            heading: "{{__('admin.danger')}}",
                            text: "{{__('admin.something_error_message')}}",
                            showHideTransition: "slide",
                            icon: "error",
                            loaderBg: "#f96868",
                            position: "top-right",
                        });
                    }
                    $(clickedLinkName).removeClass('add-airwaybill-download-process-cursor');
                },
                error: function () {
                    console.log('error');
                    $(clickedLinkName).removeClass('add-airwaybill-download-process-cursor');
                }
            });
        } else {
            return ''
        }
    }
</script>
