@extends('admin/adminLayout')
@push('bottom_head')
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <style>
        .date .parsley-errors-list {
            position: absolute;
            bottom: -40px;
        }

        select option{ color: black; font-size: 13px;}
        select option[disabled] {
            background: #dbdcdf;
        }

        .swal-button--confirm {
            color: #fff;
            background-color: #25378b;
            border-color: #25378b;
        }

        .swal-button--confirm:hover {
            background-color: #213175 !important;
            color: #fff !important;
        }

        .timee{
            position: absolute;
        }

        select[readonly] {
            background: #e9ecef;
            pointer-events: none;
            touch-action: none;
        }
        select[readonly].select2-hidden-accessible + .select2-container {
            pointer-events: none;
            touch-action: none;
        }
        .select2-selection {
            background: #e9ecef !important;
            box-shadow: none;
        }

        .select2-selection__arrow, select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
            display: none;
        }

        .add-airwaybill-download-process-cursor{
            cursor: progress;
            opacity: 0.5;
        }
    </style>
@endpush
@section('content')
    @php
        $rfq = $order->rfq()->first();
        $quote = $order->quote()->first();
        $buyer = $order->user()->first();
        $buyer_company = $order->companyDetails()->first();
        $supplier = $order->supplier()->first();
        $orderCreditDay = $order->orderCreditDay()->first(['approved_days']);
    @endphp
    <div class="row">
        <div class="col-md-12 d-flex align-items-center mb-3">
            <h1 class="mb-0 h3">{{$order->order_number}}</h1>
            @if(isset($group_id) && !empty($group_id))
                <span>
                    <button type="button" class="btn btn-info btn-sm ms-2 text-white" style="border-radius: 20px">BGRP-{{ $group_id }}</button>
                </span>
            @endif
            <a href="{{ route('order-list') }}" class="ms-auto">
                <button type="button" class="btn-close ms-auto"></button>
            </a>
        </div>
        <div class="col-md-12">
            <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab"
                            data-bs-target="#home" type="button" role="tab" aria-controls="home"
                            aria-selected="true">{{ __('admin.edit_order') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-0 ms-5" id="activity-tab" data-bs-toggle="tab" data-orderid="{{$order->id}}"
                            data-bs-target="#profile-1" type="button" role="tab" aria-controls="profile-1"
                            aria-selected="false">{{ __('admin.activities') }}
                    </button>
                </li>
            </ul>
            <div class="tab-content pt-3 pb-0 text-start" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel"
                     aria-labelledby="home-tab">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row align-items-stretch">
                                <!-- Order Detail -->
                                <div class="col-md-12 pb-2">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center">
                                            <h5 class="mb-0">
                                                <img src="{{URL::asset('assets/icons/shopping-cart.png')}}"
                                                     alt="Order Details" class="pe-2">
                                                <span>{{ __('admin.order_details') }}</span></h5>
                                        </div>
                                        <div class="card-body bg-white p-3">
                                            <div class="row rfqform_view bg-white">
                                                <div class="col-md-3 pb-2">
                                                    <label class="form-label">{{ __('admin.rfq_number') }}: </label>
                                                    <div>{{ $rfq->reference_number }}</div>
                                                </div>
                                                <div class="col-md-3 pb-2">
                                                    <label class="form-label">{{ __('admin.quote_number') }}: </label>
                                                    <div> {{ $quote->quote_number }}</div>
                                                </div>
                                                <div class="col-md-3 pb-2">
                                                    <label class="form-label">{{ __('admin.order_number') }}: </label>
                                                    <div>{{ $order->order_number }}</div>
                                                </div>
                                                <div class="col-md-3 pb-2">
                                                    <label class="form-label">{{ __('admin.date') }} : </label>
                                                    <div class="text-dark"> {{ changeDateTimeFormat($order->created_at) }}</div>
                                                </div>
                                                <div class="col-md-6 pb-2">

                                                    <label class="form-label">{{ __('admin.status_of_order') }}: </label>
                                                    <div id="order-status-div">
                                                        {!! $orderStatusDropDownHtml !!}
                                                    </div>
                                                </div>
                                                @if(isset($order->customer_reference_id) && !empty($order->customer_reference_id))
                                                    <div class="col-md-3">
                                                        <label class="form-label">{{ __('rfqs.customer_ref_id') }}: </label>
                                                        <div class="text-dark"> {{ isset($order) ? $order->customer_reference_id : '' }}</div>
                                                    </div>
                                                @endif
                                                <div class="col-md-3 pb-2">
                                                    <label class="form-label">{{ __('admin.payment_term') }}: </label>
                                                    @if($order->payment_type==1)
                                                        <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.credit') }} -{{ $order->credit_days}}</span></div>
                                                    @elseif( $order->payment_type==0)
                                                        <div class="text-dark"><span class="badge rounded-pill bg-success">{{ __('admin.advance') }}</span></div>
                                                    @elseif( $order->payment_type==3)
                                                        <div class="text-dark"><span class="badge rounded-pill bg-danger">{{  __('admin.lc')  }}</span></div>
                                                    @elseif( $order->payment_type==4)
                                                        <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.skbdn') }}</span></div>
                                                    @else
                                                        <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.credit') }}</span></div>
                                                    @endif
                                                </div>

                                            <!-- @if(isset($awb->airwaybill_number) && !empty($awb->airwaybill_number))
                                                <div class="col-md-3">
                                                    <label class="form-label">{{ __('admin.airwaybill_number') }} : </label>
                                                    <div class="text-dark"> {{ isset($awb) ? $awb->airwaybill_number : '-' }}</div>
                                                </div>
                                                @endif -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Buyer Detail -->
                                <div class="col-md-12 pb-2">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center">
                                            <h5 class="mb-0"><img height="20px"
                                                                  src="{{URL::asset('assets/icons/person-dolly-1.png')}}"
                                                                  alt="Buyer Details"
                                                                  class="pe-2">
                                                <span> {{ __('admin.buyer_detail') }}</span></h5>
                                        </div>
                                        <div class="card-body p-3 pb-1">
                                            <div class="row rfqform_view bg-white">
                                                <div class="col-md-3 pb-2">
                                                    <label class="form-label">{{ __('admin.company_name') }}: </label>
                                                    <div class="text-dark">{{ $buyer_company->name ?? '' }}</div>
                                                </div>
                                                <div class="col-md-3 pb-2">
                                                    <label class="form-label">{{ __('admin.customer_name') }}: </label>
                                                    @php
                                                        $name = '';
                                                        if(isset($buyer->firstname) && isset($buyer->lastname))
                                                        {
                                                            $name = $buyer->firstname.' '.$buyer->lastname;
                                                        }
                                                        else if(isset($buyer->firstname)) {
                                                            $name = $buyer->firstname;
                                                        }
                                                        else if(isset($buyer->lastname))
                                                        {
                                                            $name = $buyer->lastname;
                                                        }
                                                    @endphp
                                                    <div
                                                        class="text-dark">{{ $name }}
                                                    </div>
                                                </div>
                                                @if(auth()->user()->role_id != 3)
                                                    <div class="col-md-3 pb-2">
                                                        <label class="form-label">{{ __('admin.customer_phone') }}: </label>
                                                        <div class="text-dark text-nowrap"> {{ countryCodeFormat($buyer->phone_code, $buyer->mobile) }}</div>
                                                    </div>
                                                    <div class="col-md-auto flex-fill pb-2">
                                                        <label class="form-label">{{ __('admin.customer_email') }}: </label>
                                                        <div class="text-dark">{{ $buyer->email }}</div>
                                                    </div>
                                                @endif
                                                @if((auth()->user()->role_id == 1 || Auth::user()->hasRole('agent')) && !empty($orderPo))
                                                <div class="col-md-3 pb-2">
                                                    <label class="form-label">{{ __('admin.download_buyer_po') }}: </label>
                                                    <div class="text-dark">
                                                        @php
                                                            $downloadBuyerPoUrl = $order->order_status != 7 ? route('download-buyer-po-pdf', Crypt::encrypt($order->id)) : 'javascript:void(0)';
                                                        @endphp
                                                        <a class="text-decoration-none downloadPo btn btn-primary p-1 fw-bold" href="{{ $downloadBuyerPoUrl }}"> <svg id="Layer_1" width="12px" fill="#fff" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg>
                                                            {{ __('admin.download_buyer_po') }}</a>
                                                    </div>
                                                </div>
                                                @endif

                                                 @if((auth()->user()->role_id == 1 || Auth::user()->hasRole('agent')) && $order->order_status >=5 && ($order->order_status != 9 && $order->order_status != 10) && !empty($orderPo->inv_number))
                                                <div class="col-md-3 pb-2">
                                                    <label class="form-label">{{ __('admin.download_blitznet_invoice') }}: </label>
                                                    <div class="text-dark">

                                                        @php
                                                            $downloadblitznetInvoiceUrl = $order->order_status != 7 ? route('download-blitznet-invoice-pdf', Crypt::encrypt($order->id)) : 'javascript:void(0)';
                                                        @endphp
                                                        <a class="text-decoration-none downloadPo btn btn-primary p-1 fw-bold" href="{{ $downloadblitznetInvoiceUrl }}"> <svg id="Layer_1" width="12px" fill="#fff" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg>
                                                            {{ __('admin.download_blitznet_invoice') }}</a>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Supplier Detail -->
                                <div class="col-md-12 pb-2">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center">
                                            <h5 class="mb-0"><img height="20px"
                                                                  src="{{URL::asset('assets/icons/people-carry-1.png')}}"
                                                                  alt="Supplier Details"
                                                                  class="pe-2"> <span>{{ __('admin.supplier_detail') }}</span></h5>
                                        </div>
                                        <div class="card-body p-3 pb-1">
                                            <div class="row rfqform_view bg-white">
                                                <div class="col-md-3 pb-2">
                                                    <label class="form-label">{{ __('admin.supplier_company') }}: </label>
                                                    <div class="text-dark">{{ $supplier->name ?? ''}}</div>
                                                </div>
                                                <div class="col-md-2 pb-2">
                                                    <label class="form-label">{{ __('admin.supplier_name') }}: </label>
                                                    <div class="text-dark">{{ $supplier->contact_person_name }}</div>
                                                </div>
                                                <div class="col-md-2 pb-2">
                                                    <label class="form-label">{{ __('admin.supplier_phone') }}: </label>
                                                    <div class="text-dark">{{ countryCodeFormat($supplier->cp_phone_code, $supplier->contact_person_phone) }}</div>
                                                </div>
                                                <div class="col-md-5 flex-fill pb-2">
                                                    <label class="form-label">{{ __('admin.supplier_email')  }}: </label>
                                                    <div class="text-dark">{{ $supplier->contact_person_email }}</div>
                                                </div>
                                                    @if($orderBatches->count() > 0)
                                                        <div class="col-md-6 col-lg-5 col-xxl-4 pb-2">
                                                            <label class="form-label">{{ __('admin.airwaybill') }}: </label>
                                                            <div class="dropdown">
                                                                <button
                                                                    class="btn btn-primary dropdown-toggle btn-sm w-100 text-start text-white p-1 sellerDownloadAirWayBill"
                                                                    type="button" id="dropdownMenuButton1"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    {{ __('admin.download_airwaybill')  }}
                                                                </button>
                                                                @foreach($orderBatches as $orderBatche)
                                                                    @if($orderBatche->airwaybill_id != null)
                                                                        <ul class="dropdown-menu dropdown-menu-start w-100"
                                                                            style="max-height: 180px; overflow-y: auto;"
                                                                            aria-labelledby="dropdownMenuButton1">
                                                                            @foreach($orderBatches as $orderBatche)
                                                                                <li class="border-bottom"
                                                                                    style="cursor: pointer;">
                                                                                    <a class="dropdown-item p-2 d-flex"
                                                                                       onclick="downloadAirWayBill('{{$orderBatche->getAirWayBillNumber->airwaybill_number}}')">
                                                                                        {{ $orderBatche->order_batch }}
                                                                                        - {{ $orderBatche->getAirWayBillNumber->airwaybill_number }}
                                                                                        <span class="ms-auto">
                                                                                    <svg id="Layer_1" width="13px"
                                                                                         fill="#0000FF" data-name="Layer 1"
                                                                                         xmlns="http://www.w3.org/2000/svg"
                                                                                         viewBox="0 0 383.26 408.81">
                                                                                    <path
                                                                                        d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z"
                                                                                        transform="translate(-64.37 -51.59)">
                                                                                    </path>
                                                                                    <path
                                                                                        d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z"
                                                                                        transform="translate(-64.37 -51.59)">
                                                                                    </path>
                                                                                </svg>
                                                                                </span>
                                                                                    </a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 ps-md-0 pb-2">
                            <div class="card h-100">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0">
                                        <img src="{{URL::asset('assets/icons/truck-moving.png')}}" alt="Track Order" class="pe-2"> <span>{{ __('admin.track_order') }}</span>
                                    </h5>
                                </div>
                                <div id="orderStatusDetails">
                                    {!! $orderStatusHtml !!}
                                </div>
                            </div>
                        </div>

                        <!-- Payment Detail -->
                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center w-100">
                                    <h5 class="d-flex align-items-center w-100 mb-0"><img
                                            src="{{URL::asset('assets/icons/credit-card.png')}}"
                                            alt="Payment Detail"
                                            class="pe-2">
                                        <span>{{ __('admin.payment_detail') }} </span>
                                        <!-- <div class="ms-auto" id="orderCreditStatus" data-value="{{$order->is_credit}}">
                                            @php
                                                $isCreditStatus = __('admin.advance');
                                                if($order->is_credit == ORDER_IS_CREDIT['CREDIT']){
                                                    $isCreditStatus = __('admin.credit');
                                                }elseif ($order->is_credit == ORDER_IS_CREDIT['LOAN_PROVIDER_CREDIT']){
                                                    $isCreditStatus = __('admin.loan_provider_credit');
                                                }
                                            @endphp
                                            <small style=" font-size: 14px;">{{ __('admin.payment_terms') }}: <span class="badge rounded-pill {{ ($order->is_credit == 0) ? 'bg-success' : 'bg-danger' }}" style=" font-size: 12px;">{{ $isCreditStatus }}</span></small>
                                        </div> -->
                                    </h5>
                                </div>
                                <div class="card-body p-2" id="payment-detail-div">
                                    {!! $paymentDetailHtml !!}
                                </div>
                            </div>
                        </div>

                        <!-- Order Items Chunk wise product listing Starts (Ronak M - 26/08/2022 ) -->
                        {{--@if(!empty($orderBatchesHtml))--}}
                            <div class="col-md-12 pb-2 mt-2 {{isset($orderBatchesHtml) && !empty($orderBatchesHtml) ? '' : 'd-none'}}" id="ordersBatch">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center">
                                        <h5 class="mb-0">
                                            <img height="20px" src="{{URL::asset('assets/icons/attachement.png')}}" alt="Supplier Details" class="pe-2">
                                            <span>{{ __('admin.product') }} {{ __('admin.groups') }}</span>
                                        </h5>
                                    </div>

                                    <div class="card-body p-1 pb-1" id="batch-detail-div">
                                        {!! $orderBatchesHtml !!}
                                    </div>
                                </div> <!-- /Card -->
                            </div> <!-- /col-md-12 -->
                       {{-- @endif--}}
                        <!-- Order Items Chunk wise product listing End  -->

                        <!-- Activity Detail -->
                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/attachement.png')}}" alt="Supplier Details" class="pe-2">
                                        <span> {{ __('admin.attachment') }}</span></h5>
                                </div>
                                <div class="card-body p-3 pb-1" id="activity-detail-div">
                                    {!! $activityDetailHtml !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                            <a href="{{ route('order-list') }}">
                                <button type="button" class="btn btn-cancel ms-3">{{ __('admin.cancel') }}</button>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="profile-1" role="tabpanel" aria-labelledby="activity-tab" data-orderid="{{$order->id}}">
                    <div class="row">
                        <div class="col-md-12 pb-2">
                            <div class="activityopen"></div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="modal fade" id="generatePoModal" tabindex="-1" aria-labelledby="generatePoModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content  border-0">
                <div class="modal-header p-3 text-white" style="background-color: #13193a;">
                    <h5 class="modal-title text-white" id="generatePoModalLabel">{{ __('admin.generate_po') }}</h5>
                    <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close">
                        <img src="{{URL::asset('front-assets/images/icons/times.png')}}" alt="Close">
                    </button>
                </div>
                <div class="modal-body p-3" id="generatePoModalBlock" style="background-color: #ebedf1;">

                </div>
                <div class="modal-footer px-3" style="background-color: #f5f5f6;">
                    <button data-id="{{ $order->id }}" class="btn btn-primary sendPoToSupplier border-0">{{ __('admin.generate_po') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal version2 fade" id="pickup_dateTimeModal" tabindex="-1" role="dialog" aria-labelledby="pickup_dateTimeModalLabel" aria-modal="true" style="padding-right: 17px">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header py-3">
                    <h5 class="modal-title d-flex align-items-center text-white" id="pickup_dateTimeModalLabel">
                        <img height="24px" class="pe-2" src="{{URL::asset('front-assets/images/icons/order_detail_title.png')}}" alt="Ready To Dispatch"> {{ __('order.ready_to_dispatch') }}
                    </h5>
                    <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close">
                        <img src="{{URL::asset('front-assets/images/icons/times.png')}}" alt="Close">
                    </button>
                </div>

                <div class="modal-body p-3">
                    <div id="viewQuoteDetailBlock">
                        <div class="row align-items-stretch">
                            <form action="{{route('order-pickup-datetime-ajax')}}" method="post" name="pickup_datetime_form" id="pickup_datetime_form" data-parsley-validate  autocomplete="off">
                                @csrf
                                <div class="col-md-12 pb-2">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center">
                                            <h5><img src="{{URL::asset('front-assets/images/icons/truck.png')}}" alt="Order Details" class="pe-2"> {{ __('admin.delivery_details') }}</h5>
                                        </div>
                                        <!-- Buyer Details -->
                                        <div class="card-body p-3 pb-1">
                                            <div class="row rfqform_view bg-white">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">{{ __('profile.address') }}:</label>
                                                    <div class="text-dark">{{ $buyerData->address_name . ' ' . $buyerData->address_line_1 . ' ' . $buyerData->address_line_2 }}</div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">{{ __('admin.sub_district') }}:</label>
                                                    <div class="text-dark"></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">{{ __('admin.district') }}:</label>
                                                    <div class="text-dark">{{ $buyerData->state }}</div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">{{ __('admin.city') }}:</label>
                                                    <div class="text-dark">{{ $buyerData->city }}</div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">{{ __('admin.provinces') }}:</label>
                                                    <div class="text-dark">{{ $buyerData->state }}</div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">{{ __('admin.pin_code') }}:</label>
                                                    <div class="text-dark">{{ $buyerData->pincode }}</div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">{{ __('admin.expected_delivery_date') }}:</label>
                                                    <div class="text-dark"></div>
                                                </div>
                                                <div class="col-md-8 mb-3">
                                                    <label class="form-label">{{ __('admin.other_option') }}</label>
                                                    <div class="text-dark ps-4">
                                                        <div class="form-check form-check-inline my-0">
                                                            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1" disabled="">
                                                            <label class="form-check-label" for="inlineCheckbox1" readonly="">{{ __('admin.need_uploding_services') }}<i class="input-helper"></i></label>
                                                        </div>
                                                        <div class="form-check form-check-inline my-0">
                                                            <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2" disabled="">
                                                            <label class="form-check-label" for="inlineCheckbox2">{{ __('admin.need_rental_forklift') }}<i class="input-helper"></i></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Buyer Details -->

                                <!-- Supplier Detail -->
                                <div class="col-md-12 pb-0">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center">
                                            <h5><img height="20px" src="{{URL::asset('front-assets/images/icons/icon_pickup.png')}}"
                                                     alt="Supplier Details" class="pe-2"> Pickup Address
                                            </h5>
                                        </div>
                                        <div class="card-body p-3 pb-1">
                                            <div class="row rfqform_view bg-white">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">{{ __('profile.address') }}:</label>
                                                    <div class="text-dark">{{$order->supplier_address}}</div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">{{ __('admin.sub_district') }}:</label>
                                                    <div class="text-dark"></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">{{ __('admin.pin_code') }}:</label>
                                                    <div class="text-dark"></div>
                                                </div>
                                                <input type="hidden" name="order_id" id="order_id" value="{{ $order->id }}">
                                                <div class="col-md-4 mb-3">
                                                    <label for="maxDays" class="form-label">{{ __('admin.pickup_date') }}<span class="text-danger">*</span></label>
                                                    <div id="date" class="input-group date datepicker">
                                                        <input type="text" id="pickup_date" name="pickup_date" value="" placeholder="dd-mm-yyyy" class="form-control" style="border: 1px solid #dee2e6;" required>
                                                        <span class="input-group-addon input-group-append border-left">
                                                            <span class="mdi mdi-calendar input-group-text" style="padding: 0.7rem 0.75rem;border: 1px solid #dee2e6;"></span>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">{{ __('admin.pickup_time') }}<span class="text-danger">*</span></label>
                                                    <div id="datetimepickup" class="input-group ">
                                                        <input type="text" id="pickup_time" name="pickup_time" value="" placeholder="hh:mm" class="form-control" style="border: 1px solid #dee2e6;" required>
                                                        <span class="input-group-addon input-group-append border-left">
                                                            <span class="mdi mdi-clock input-group-text" style="padding: 0.7rem 0.75rem;border: 1px solid #dee2e6;"></span>
                                                        </span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Supplier End-->
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal-footer px-3">
                    <a class="btn btn-primary" id="orderPickUpBtn">{{ __('admin.update') }}</a>
                    <a class="btn btn-cancel" data-bs-dismiss="modal">{{ __('admin.cancel') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="orderItemModal" tabindex="-1" aria-labelledby="orderItemModalLabel"
         aria-hidden="true">
        <div class="modal-dialog"
             style="position: fixed; margin: auto; width: 330px; height: 100%; right: 0px;">
            <div class="modal-content" style="height: 100%;   border-radius: 0rem" id="orderItemModalContent">

            </div>
        </div>
    </div>
<div class="modal version2 fade show" id="UploadModal">
     <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header py-3">
                <h5 class="modal-title d-flex align-items-center">
                    <img class="pe-2" height="24px" src="https://beta.blitznet.co.id/front-assets/images/icons/order_detail_title.png"alt="View RFQ"> {{ __('admin.doc-panding') }}
                </h5>
                <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close">
                    <img src="https://beta.blitznet.co.id/front-assets/images/icons/times.png" alt="Close">
                </button>
            </div>
            <form name="uploadOrderDoc" id="uploadOrderDoc"autocomplete="off" enctype="multipart/form-data">
            @csrf
             <input type="hidden" name="orderdata" id="orderdata">
                <div class="modal-body p-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <h5 class="mb-0"><img src="{{URL::asset('front-assets/images/icons/file-alt.png')}}"
                                    alt="Upload Document" class="pe-2">
                            <span>{{ __('admin.upload-doc') }}</span></h5>
                            </div>
                            <div class="card-body bg-white p-3">
                                <div class="row rfqform_view bg-white">
                                    <div class="col-md-12 py-lg-2 text-center">
                                        <h4 class="">{{ __('admin.lc-doc') }}</h4>
                                        <p class="mb-3 ">{{ __('admin.qc-doc') }}</p>
                                        <div class="d-flex justify-content-center">
                                            <span class="">
                                                <input type="file" name="orderdoc" class="form-control" id="orderdoc"
                                                    accept=".jpg,.png,.pdf" hidden="">
                                                <label id="upload_btn" for="orderdoc">{{ __('profile.browse') }}</label>
                                            </span>
                                            <div>
                                                <span id="filenamedoc" class="ms-2"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-3 ">
                    <button type="button" class="btn btn-primary saveOrderdoc">{{ __('admin.submit') }}</button>
                    <button type="button" class="btn btn-cancel " data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    {{ view('admin.order.order_js',['order'=>$order,'orderStatusDropDownHtml'=>$orderStatusDropDownHtml,'paymentDetailHtml'=>$paymentDetailHtml,'activityDetailHtml'=>$activityDetailHtml,'orderBatchesHtml'=>$orderBatchesHtml,'manageDeliverySeparately'=>$order->delivery_manage_separately]) }}
@endsection
