<div>
    @if (count($orders))
        @foreach ($orders as $key => $order)
            @php
                $isGroupOrder = $order->group_id?1:0;
                $orderItemCategory = $order->orderItemCategory;
                $orderItemCategoryId = $order->orderItemCategoryId;
                $orderlogisticProvided = $order->orderlogisticProvided;
                $orderAirwayBill = $order->orderAirwayBill;
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
                                $status_name = __('order.' . trim($order->orderStatus->name));
                                if ($order->order_status == 8) {//'Payment Due DD/MM/YYYY'
                                    $status_name = sprintf($status_name,newChangeDateFormat($order->payment_due_date,'d/m/Y'));
                                }
                            @endphp
                            <span class="badge rounded-pill bg-primary mt-1 {{ $order->order_status == 5 ? 'bg-success' : ($order->order_status == 7 ? 'bg-danger' : '') }}"
                                id="orderStatusNameBlock{{ $order->id }}"> 
                            {{ $status_name == 'Order Received' ?  __('order.orderplaced') : $status_name }} </span>
                        </div>
                        <div class="font_sub px-3 datesection me-lg-5"><span
                                class="mb-1">{{ __('order.Date') }}:</span>
                            {{ date('d-m-Y H:i', strtotime($order->created_at)) }}</div>
                    </button>
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
                                                <h5 class="dark_blue mb-0 "><img src="{{ URL::asset('front-assets/images/icons/shopping-cart.png') }}" alt=""> {{ __('order.order_detail') }}</h5>
                                                    <div class="ms-auto">
                                                        <a href="javascript:void(0)" class="btn btn-info px-2 py-1" style="border-radius: 4px; font-size: 12px;" onclick="refreshAllOrderStautsData('{{$order->id}}', event)"><img src="{{ URL::asset('front-assets/images/icons/icon_refresh.png') }}" class="me-1" style="max-height: 12px;" alt="refresh" id="refreshOrderImg{{ $order->id }}">{{ __('order.refresh') }}</a>
                                                        <a class="btn btn-info px-2 py-1 ms-12 orderbutton btn_print_color text-nowrap" style="font-size: 12px;" href="{{route('dashboard-print-order',Crypt::encrypt($order->id))}}"  target="_blank" ><img src="{{ URL::asset('front-assets/images/icons/icon_print.png') }}" alt="Print" class="pe-1" style="max-height: 12px;">Print</a>
                                                        @if(in_array($orderItemCategoryId,\App\Models\Category::SERVICES_CATEGORY_IDS) && ($orderlogisticProvided->logistic_check==1 && $orderlogisticProvided->logistic_provided ==0))
                                                            @if(isset($orderAirwayBill))
                                                                <a class="btn btn-warning px-2 py-1 ms-12 btn_airway_color text-nowrap downloadAirWayBill{{$orderAirwayBill->airwaybill_number}}"
                                                                   style="font-size: 12px;"
                                                                   onclick="downloadAirWayBill('{{ isset($orderAirwayBill->airwaybill_number) ? $orderAirwayBill->airwaybill_number : null }}')"
                                                                   id="downloadAirWayBill{{$orderAirwayBill->airwaybill_number}}" title="{{__('admin.download_airwaybill')}}">
                                                                        <img
                                                                        src="{{ URL::asset('front-assets/images/icons/icon_download.png') }}"
                                                                        alt="{{__('admin.download_airwaybill')}}" class="pe-1"
                                                                        style="max-height: 12px;">{{__('admin.airwaybill')}}
                                                                </a>
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
                                                <div class="text-primary"> {{ $order->assigned_to }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('order.order_number') }}:</label>
                                                <div>
                                                    <span class="tooltiphtml text-primary">
                                                        <div>{{ $order->order_number }}</div>
                                                        <div class="tooltip_section_RQO card card-body shadow p-0">
                                                            <div class="dark_blue_bg text-white p-2">Details</div>
                                                            <ul class="p-1 px-2 mb-0 ">
                                                                <li>RFQ No: <a class="showRfqModal" href="javascript:void(0);" data-id={{ $order->rfq_id }} role="button">{{ "BRFQ-" . $order->rfq_id }}</a></li>
                                                                <li class="text-center"><i class="fa fa-arrow-down" aria-hidden="true"></i></li>
                                                                <li>Quote No: <a class="showQuoteModal" href="javascript:void(0);" data-id="{{ $order->quote_id }}" role="button">{{ "BQTN-" . $order->quote_id }}</a></li>
                                                                <li class="text-center"><i class="fa fa-arrow-down" aria-hidden="true"></i></li>
                                                                <li>Order No: <a name="" class="text-primary" id="" >{{ $order->order_number }}</a></li>
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
                                                <div>{{ countryCodeFormat($order->rfq->phone_code ,$order->rfq->mobile) }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('order.supplier_company_name') }}:</label>
                                                <div>
                                                    @if($order->supplier_profile_username)
                                                    <a href="{{ route('supplier.professional.profile','blitz-'.$order->supplier_profile_username) }}" target="_blank" style="text-decoration: none;">{{ $order->supplier_company_name }}</a>
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
                                                        <span id="payment_terms{{ $order->id }}" data-payment_terms="1" data-payment_ststus="{{ $order->order_status==9?9:8 }}" class="badge rounded-pill mt-1 bg-danger">{{ __('order.credit') }} -{{$order->credit_days}}</span>
                                                    @elseif($order->payment_type==0)
                                                        <span id="payment_terms{{ $order->id }}" data-payment_terms="0" data-payment_ststus="2" class="badge rounded-pill mt-1 bg-success">{{ __('order.advance') }}</span>
                                                    @elseif($order->payment_type==3)
                                                        <span id="payment_terms{{ $order->id }}" data-payment_terms="0" data-payment_ststus="2" class="badge rounded-pill mt-1 bg-danger">{{  
                                                        __('admin.lc')  }}</span>
                                                    @elseif($order->payment_type==4)
                                                        <span id="payment_terms{{ $order->id }}" data-payment_terms="0" data-payment_ststus="2" class="badge rounded-pill mt-1 bg-danger">{{  
                                                        __('admin.skbdn')  }}</span>
                                                    @else
                                                        <span id="payment_terms{{ $order->id }}" data-payment_terms="0" data-payment_ststus="2" class="badge rounded-pill mt-1 bg-danger">{{  
                                                            __('admin.credit')  }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('order.address') }}:</label>
                                                <div>{{ $order->address_line_1 .', '. $order->address_line_2 }}, {!! $order->sub_district?($order->sub_district):'' !!}, {!! $order->district?($order->district):'' !!}, {{ $order->city }}, {{$order->state}}, {{ $order->pincode }}</div>
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
                                                <a href="javascript:void(0);" id="refreshOrderStautsData{{ $order->id }}" data-id="{{ $order->id }}" class="refreshOrderStautsData" onclick="refreshOrderStautsData('{{$order->id}}', event)">
                                                    <img src="{{ URL::asset('front-assets/images/icons/icon_refresh.png') }}" alt="refresh" id="refreshOrderImg{{ $order->id }}">
                                                </a>
                                            </div>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="bullet-line-list mb-0" id="orderStatusViewBlock{{ $order->id }}">
                                            @foreach ($order->orderAllStatus as $orderStatus)
                                                @if(isset($order->orderTracksIds[$orderStatus->order_status_id]))
                                                    @php
                                                        $orderStatus->order_track_id = $order->orderTracksIds[$orderStatus->order_status_id];
                                                        $orderStatus->created_at = $order->orderTracksdate[$orderStatus->order_status_id];
                                                    @endphp
                                                @else
                                                    @php
                                                        $orderStatus->order_track_id = null;
                                                        $orderStatus->created_at = null;
                                                    @endphp
                                                @endif
                                            @endforeach

                                            @if($order->payment_type == 1 || $order->payment_type == 2)
                                                {{view('buyer/orders/livewireCreditOrderStatusRefresh', ['order' => $order, 'orderTracks' => $order->orderTracks,'orderItemCategory'=>$orderItemCategory,'orderItemCategoryId'=>$orderItemCategoryId,'orderlogisticProvided'=>$orderlogisticProvided,'orderAirwayBill'=>$orderAirwayBill])}}
                                            @else
                                                {{view('buyer/orders/livewireOrderStatusRefresh', ['order' => $order, 'orderTracks' => $order->orderTracks,'orderItemCategory'=>$orderItemCategory,'orderItemCategoryId'=>$orderItemCategoryId,'orderlogisticProvided'=>$orderlogisticProvided,'orderAirwayBill'=>$orderAirwayBill])}}
                                            @endif
                                        </ul>
                                        <a href="javascript:void(0)" id="repeatRfq_{{$order->rfq_id}}" class="repeatRfq w-100 btn btn-outline-primary mt-2"  data-isRepeatOrder="1" data-rfq_id="{{$order->rfq_id}}" title="{{ __('dashboard.repeat_order')}}" >{{ __('dashboard.repeat_order')}}</a>
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
                                            <div class="d-flex align-items-center position-relative">Rp {{getBuyerRefundAmount($order->quote_id)}}<span class="ms-auto me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('order.credit_amount_explain') }}"><img src="{{ URL::asset('front-assets/images/icons/icon_explain.png') }}" width="12px"></span> </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="bg-light p-1 h-100">
                                            <label>{{ __('order.avail_discount') }}</label>
                                            <div class="text-dark d-flex align-items-center"> {{($order->group_members_discount) ? $order->group_members_discount->avail_discount : '0'}}% <span class="ms-auto me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('order.avail_discount_explain') }}"><img src="{{ URL::asset('front-assets/images/icons/icon_explain.png') }}" width="12px"></span> </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="bg-light p-1">
                                            <label>{{ __('order.achieved_discount') }}</label>
                                            <div class="d-flex align-items-center"> {{($order->group_members_discount) ? $order->group_members_discount->achieved_discount : '0'}}% <span class="ms-auto me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('order.achieved_discount_explain') }}"><img src="{{ URL::asset('front-assets/images/icons/icon_explain.png') }}" width="12px"></span> </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="bg-light p-1">
                                            <label>{{ __('order.prospect_discount') }}</label>
                                            <div class="text-dark d-flex align-items-center"> {{ ($order->group_members_discount) ? $order->group_members_discount->prospect_discount : '0'}}% <span class="ms-auto me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('order.prospect_discount_explain') }}"><img src="{{ URL::asset('front-assets/images/icons/icon_explain.png') }}" width="12px"></span> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="card radius_1 my-1 w-100">
                            <div id="view_order_sub_status_refresh{{$order->id}}">
                                {{view('buyer/orders/livewireViewOrderSubStatusAllRefresh', ['order' => $order,'orderItemCategory'=>$orderItemCategory,'orderItemCategoryId'=>$orderItemCategoryId,'orderlogisticProvided'=>$orderlogisticProvided])}}
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
        @if($orders->hasMorePages())
                <livewire:buyer.loadmore.load-more-items :page="$page" :perPage="$perPage" :favrfq="$favrfq" :searchedText="$searchedText" :status="$status"  :total="$orders->totalRecord" :currentRecord="$orders->currentRecord" :key="'load-more-items-'.$page" />
        @else
            <div class="ms-auto text-end">
                <small class="text-muted">{{ __('order.showing') }} 1 {{ __('order.to') }} {{$orders->totalRecord}} {{ __('order.of') }} {{$orders->totalRecord}} {{ __('order.entries') }}</small>
            </div>
        @endif
    @else
        <div class="col-md-12">
            <div class="alert alert-danger radius_1 text-center fs-6 fw-bold">{{ __('rfqs.No_order_found') }}</div>
        </div>
    @endif

</div>