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
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12 d-flex align-items-center mb-3">
            <h1 class="mb-0 h3">{{$order->order_number}}</h1>
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
                    <button class="nav-link px-0 ms-5" id="profile-tab" data-bs-toggle="tab" data-orderid="{{$order->id}}"
                            data-bs-target="#profile-1" type="button" role="tab" aria-controls="profile-1"
                            aria-selected="false">{{ __('admin.activities') }}
                    </button>
                </li>
            </ul>
            <div class="tab-content pt-3 pb-0" id="myTabContent">
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
                                                    <div>{{ $order->rfq_reference_number }}</div>
                                                </div>
                                                <div class="col-md-3 pb-2">
                                                    <label class="form-label">{{ __('admin.quote_number') }}: </label>
                                                    <div> {{ $order->quote_number }}</div>
                                                </div>
                                                <div class="col-md-3 pb-2">
                                                    <label class="form-label">{{ __('admin.order_number') }}: </label>
                                                    <div>{{ $order->order_number }}</div>
                                                </div>
                                                <div class="col-md-3 pb-2">
                                                    <label class="form-label">{{ __('admin.date') }} : </label>
                                                    <div class="text-dark"> {{ $order->created_at }}</div>
                                                </div>
                                                <div class="col-md-9 pb-2">

                                                    <label class="form-label">{{ __('admin.status_of_order') }}: </label>
                                                    <div>
                                                        @if(($order->order_status==14 && !empty($order->disbursement_status)))
                                                            <span class="badge badge-success">{{ __('admin.settled_payment') }}</span>
                                                        @else
                                                            @if($order->is_credit)

                                                                <select id="status_change_{{ $order->id }}"
                                                                        class="creditOrderStatusChange order-status-change form-select ps-2 w-auto text-primary"
                                                                        data-pogenerated="{{$order->oder_po?0:1}}"
                                                                        data-order_latter="{{$order->order_latter?0:1}}"
                                                                        data-tax_receipt="{{$order->tax_receipt?0:1}}"
                                                                        data-invoice="{{$order->invoice?0:1}}"
                                                                        data-laststatus="{{$order->order_status}}"
                                                                        data-order-id="{{ $order->id }}">
                                                                    @foreach ($creditOrderStatus as $status)
                                                                        @php $is_disabled = '' @endphp
                                                                        @if($order->request_days_status==0 && $status->credit_sorting>4)
                                                                            @continue
                                                                        @elseif($order->request_days_status==1 && $status->id==19)
                                                                            @continue
                                                                        @elseif($order->request_days_status==1 && $status->credit_sorting<5)
                                                                            @php $is_disabled = 'disabled' @endphp
                                                                        @elseif($order->order_status==17)
                                                                            @php $is_disabled = 'disabled' @endphp
                                                                        @elseif($status->id==3)
                                                                            @php $is_disabled = 'disabled' @endphp
                                                                        @elseif($order->order_status==3 && ($status->credit_sorting<=16 || $status->id==15 || $status->id==16))
                                                                            @php $is_disabled = 'disabled' @endphp
                                                                        @elseif($order->order_status!=3 && $status->id==14)
                                                                            @php $is_disabled = 'disabled' @endphp
                                                                        @else
                                                                            @php $is_disabled = '' @endphp
                                                                        @endif
                                                                        {{-- supplier only disble qc fail, qc pass, Order Troubleshooting, credit approve, credit reject--}}
                                                                        @if(auth()->user()->role_id == 3 && in_array($status->id,[3,10,11,12,13,14,17,18,19]))
                                                                            @php $is_disabled = 'disabled' @endphp
                                                                        @endif
                                                                        @if(auth()->user()->role_id == 3 && in_array($status->id,[5,6,7,8]) && $order->logistic_provided == 0)
                                                                            @php $is_disabled = 'disabled' @endphp
                                                                        @endif
                                                                        <option
                                                                            value="{{ $status->id }}" {{ ($status->id==18 || $status->id==19)?'data-request_days='.$order->request_days.'':''  }} {{ $order->order_status == $status->id ? 'selected' : '' }} {{$is_disabled}}>
                                                                            @if($status->id==17)
                                                                                {{ $order->payment_due_date?sprintf($status->name,changeDateFormat($order->payment_due_date,'d/m/Y')):sprintf($status->name,'DD/MM/YYYY') }}
                                                                            @elseif($status->id==18)
                                                                                {{ sprintf($status->name,$order->approved_days) }}
                                                                            @else
                                                                                {{ $status->name }}
                                                                            @endif
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            @else
                                                                <select id="status_change_{{ $order->id }}"
                                                                    class="orderStatusChange order-status-change form-select ps-2 w-auto text-primary"
                                                                    data-pogenerated="{{$order->oder_po?0:1}}"
                                                                    data-order_latter="{{$order->order_latter?0:1}}"
                                                                    data-tax_receipt="{{$order->tax_receipt?0:1}}"
                                                                    data-invoice="{{$order->invoice?0:1}}"
                                                                    data-laststatus="{{$order->order_status}}"
                                                                    data-order-id="{{ $order->id }}">
                                                                @foreach ($orderStatus as $status)
                                                                    @if($status->id==19 && !isset($order->request_days_status))
                                                                        @continue
                                                                    @endif
                                                                    @php $is_disabled = '' @endphp
                                                                    @if(in_array($order->order_status,[1,2,19]) && $status->show_order_id>2)
                                                                        @php $is_disabled = 'disabled' @endphp
                                                                    @elseif($order->order_status!=1 && $status->show_order_id<5)
                                                                        @php $is_disabled = 'disabled' @endphp
                                                                    @endif
                                                                    {{-- supplier only disble qc fail, qc pass, Order Troubleshooting--}}
                                                                    @if(auth()->user()->role_id == 3 && in_array($status->id,[3,10,11,12,13,14,17]))
                                                                        @php $is_disabled = 'disabled' @endphp
                                                                    @endif
                                                                    @if(auth()->user()->role_id == 3 && in_array($status->id,[6,7,8,9]) && $order->logistic_provided == 0)
                                                                        @php $is_disabled = 'disabled' @endphp
                                                                    @endif
                                                                    <option
                                                                        value="{{ $status->id }}" {{ $order->order_status == $status->id ? 'selected' : '' }} {{$is_disabled}}>
                                                                        {{ $status->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                                {{--<div class="col-md-3">
                                                    <label class="form-label">AirWayBill Number : </label>
                                                    <div class="text-dark"> {{ isset($awb) ? $awb->airwaybill_number : '-' }}</div>
                                                </div>--}}
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
                                                    <div class="text-dark">{{ $order->company_name }}</div>
                                                </div>
                                                <div class="col-md-2 pb-2">
                                                    <label class="form-label">{{ __('admin.customer_name') }}: </label>
                                                    <div
                                                        class="text-dark">{{ $order->firstname .' '. $order->lastname }}</div>
                                                </div>
                                                @if(auth()->user()->role_id != 3)
                                                <div class="col-md-2 pb-2">
                                                    <label class="form-label">{{ __('admin.customer_phone') }}: </label>
                                                    <div class="text-dark"> {{ $order->user_mobile }}</div>
                                                </div>
                                                <div class="col-md-auto pb-2">
                                                    <label class="form-label">{{ __('admin.customer_email') }}: </label>
                                                    <div class="text-dark">{{ $order->user_email }}</div>
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
                                                    <div class="text-dark">{{ $order->supplier_company_name }}</div>
                                                </div>
                                                <div class="col-md-2 pb-2">
                                                    <label class="form-label">{{ __('admin.supplier_name') }}: </label>
                                                    <div class="text-dark">{{ $order->supplier_name }}</div>
                                                </div>
                                                <div class="col-md-2 pb-2">
                                                    <label class="form-label">{{ __('admin.supplier_phone') }}: </label>
                                                    <div class="text-dark">{{ $order->supplier_mobile }}</div>
                                                </div>
                                                 <div class="col-md-auto flex-fill pb-2">
                                                    <label class="form-label">{{ __('admin.supplier_email')  }}: </label>
                                                    <div class="text-dark">{{ $order->supplier_email }}</div>
                                                </div>
                                            </div>
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
                                                <div class="ms-auto">
                                                    <small style=" font-size: 14px;">{{ __('admin.payment_terms') }}: <span class="badge rounded-pill {{ ($order->is_credit == 0) ? 'bg-success' : 'bg-danger' }}" style=" font-size: 12px;">{{ ($order->is_credit == 0)? __('admin.advance') :  __('admin.credit').' - '.$order->approved_days }}</span></small>
                                                </div>
                                            </h5>
                                        </div>

                                    </div>
                                </div>
                                <!-- Activity Detail -->
                                <div class="col-md-12 pb-2">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center">
                                            <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/attachement.png')}}" alt="Supplier Details" class="pe-2">
                                                <span> {{ __('admin.attachment') }}</span></h5>
                                        </div>
                                        <div class="card-body p-3 pb-1">
                                            <form method="post" id="editOrderform" action="{{ route('order-update') }}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $order->id }}">
                                                <div class="row rfqform_view bg-white">
                                                <div class="col-md-3 pb-2 showDownloadPo">
                                                    @if (!$order->oder_po)
                                                        <label class="form-label">{{ __('admin.generate_po') }}: </label>
                                                        @if(auth()->user()->role_id == 3)
                                                            <div>{{ __('admin.po_pending') }}</div>
                                                        @else
                                                        <div><a href="javascript:void(0);" class="text-decoration-none generatePo btn btn-primary p-1 fw-bold" data-id="{{ $order->id }}" id="generatePo{{ $order->id }}">{{ __('admin.generate_po') }}</a></div>
                                                        @endif
                                                    @else
                                                        <label class="form-label">{{ __('admin.download_po') }}: </label>
                                                        <div><a class="text-decoration-none downloadPo btn btn-primary p-1 fw-bold" data-id="{{ $order->id }}" id="downloadPo{{ $order->id }}" href="{{ route('download-po-pdf', Crypt::encrypt($order->id)) }}"> <svg id="Layer_1" width="12px" fill="#fff" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg> {{ __('admin.download_po') }}</a></div>
                                                    @endif
                                                </div>
                                                <div class="col-md-3 pb-2">
                                                    <label class="form-label">{{ __('admin.upload_order_letter') }}: </label>
                                                    <div class="d-flex">
                                                        <span class=""><input type="file" name="order_latter" id="image-order_latter" accept=".jpg,.png,.pdf" onchange="show(this)" hidden/><label id="upload_btn" for="image-order_latter">{{ __('admin.browse') }}</label></span>
                                                        <div id="file-order_latter">
                                                            @if ($order->order_latter)
                                                                @php
                                                                    $extension_order= substr($order->order_latter, -4);
                                                                    $filename = substr(Str::substr($order->order_latter, stripos($order->order_latter, 'order_latter_') + 13), 0, -4);
                                                                    if(strlen($filename) >= 10){
                                                                        $order_name = substr($filename,0,10).'...'.$extension_order;
                                                                    } else {
                                                                        $order_name = $filename.$extension_order;
                                                                    }
                                                                @endphp
                                                                <input type="hidden" class="form-control" id="oldorder_latter" name="oldorder_latter" value="{{ $order->order_latter }}">
                                                                <span class="ms-2"><a href="javascript:void(0);" id="order_latterFileDownload" onclick="downloadimg('{{ $order->id }}', 'order_latter', '{{ Str::substr($order->order_latter, stripos($order->order_latter, "order_latter_") + 13) }}')" title="{{ Str::substr($order->order_latter, stripos($order->order_latter, 'order_latter_') + 13) }}" style="text-decoration: none;"> {{ $order_name }} </a></span>
                                                                {{--@if($order->order_status <= 5)--}}
                                                                    <span class="removeFile" style="{{ $order->order_status <= 5 ? '' : 'display:none' }}" id="order_latterFile" data-id="{{ $order->id }}" file-path="{{ $order->order_latter }}" data-name="order_latter"><a href="#" title="Remove Order Latter"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a></span>
                                                                {{--@endif--}}
                                                                <span class="ms-2"><a class="order_latterFile" href="javascript:void(0);" title="Download Order Latter" onclick="downloadimg('{{ $order->id }}', 'order_latter', '{{ Str::substr($order->order_latter, stripos($order->order_latter, "order_latter_") + 13) }}')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 pb-2">
                                                    <label class="form-label">{{ __('admin.upload_tax_receipt') }}: </label>
                                                    <div class="d-flex">
                                                        <span class=""><input type="file" name="tax_receipt" class="form-control" id="image-tax_receipt" accept=".jpg,.png,.pdf" onchange="show(this)" hidden {{ $order->order_status == 7 ?  'disabled' : '' }}/><label id="upload_btn" for="image-tax_receipt">{{ __('admin.browse') }}</label></span>
                                                        <div id="file-tax_receipt">
                                                        @if ($order->tax_receipt)
                                                            @php
                                                                $extension_tax_receipt= substr($order->tax_receipt, -4);
                                                                $tax_receipt_filename = substr(Str::substr($order->tax_receipt, stripos($order->tax_receipt, 'tax_receipt_') + 12), 0, -4);
                                                                if(strlen($tax_receipt_filename) > 10){
                                                                    $tax_receipt_name = substr($tax_receipt_filename,0,10).'...'.$extension_tax_receipt;
                                                                } else {
                                                                    $tax_receipt_name = $tax_receipt_filename.$extension_tax_receipt;
                                                                }
                                                            @endphp
                                                            <input type="hidden" class="form-control" id="oldtax_receipt" name="oldtax_receipt" value="{{ $order->tax_receipt }}">
                                                            <span class="ms-2"><a href="javascript:void(0);" id="tax_receiptFileDownload" onclick="downloadimg('{{ $order->id }}', 'tax_receipt', '{{ Str::substr($order->tax_receipt, stripos($order->tax_receipt, 'tax_receipt_') + 12) }}')"  title="{{ Str::substr($order->tax_receipt, stripos($order->tax_receipt, 'tax_receipt_') + 12) }}" style="text-decoration: none;"> {{ $tax_receipt_name }}</a></span>
                                                            {{--@if($order->order_status < 14)--}}
                                                                <span class="removeFile" style="{{ $order->order_status < 14 ? '' : 'display:none' }}" id="tax_receiptFile" data-id="{{ $order->id }}" file-path="{{ $order->tax_receipt }}" data-name="tax_receipt"><a href="#" title="Remove Tax Receipt"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a></span>
                                                            {{--@endif--}}
                                                                <span class="ms-2"><a class="tax_receiptFile" href="javascript:void(0);" title="{{ __('admin.download_tax_receipt') }}" onclick="downloadimg('{{ $order->id }}', 'tax_receipt', '{{ Str::substr($order->tax_receipt, stripos($order->tax_receipt, 'tax_receipt_') + 12) }}')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>
                                                        @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 pb-2">
                                                <label class="form-label">{{ __('admin.upload_invoice') }}: </label>
                                                <div class="d-flex">
                                                    <span class=""><input type="file" name="invoice" class="form-control" id="image-invoice" accept=".jpg,.png,.pdf" onchange="show(this)" hidden {{ $order->order_status == 7 ?  'disabled' : '' }}/><label id="upload_btn" for="image-invoice">{{ __('admin.browse') }}</label></span>
                                                    <div id="file-invoice">
                                                    @if ($order->invoice)
                                                        @php
                                                            $extension_invoice = substr($order->invoice, -4);
                                                            $invoice_filename = substr(Str::substr($order->invoice, stripos($order->invoice, 'invoice_') + 8), 0, -4);
                                                            if(strlen($invoice_filename) > 10){
                                                                $invoice_name = substr($invoice_filename,0,10).'...'.$extension_invoice;
                                                            } else {
                                                                $invoice_name = $invoice_filename.$extension_invoice;
                                                            }
                                                        @endphp
                                                        <input type="hidden" class="form-control" id="oldinvoice" name="oldinvoice" value="{{ $order->invoice }}">
                                                        <span class="ms-2"><a href="javascript:void(0);" id="invoiceFileDownload" onclick="downloadimg('{{ $order->id }}', 'invoice', '{{ Str::substr($order->invoice, stripos($order->invoice, "invoice_") + 8) }}')"  title="{{ Str::substr($order->invoice, stripos($order->invoice, 'invoice_') + 8) }}" style="text-decoration: none;"> {{ $invoice_name }}</a></span>
                                                        {{--@if($order->order_status < 14)--}}
                                                        @if(!Auth::user()->hasRole('jne'))
                                                            <span class="removeFile" style="{{ $order->order_status < 14 ? '' : 'display:none' }}" id="invoiceFile" data-id="{{ $order->id }}" file-path="{{ $order->invoice }}" data-name="invoice"><a href="#" title="Remove Invoice"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a></span>
                                                        @endif
                                                         {{--@endif--}}

                                                        <span class="ms-2"><a class="invoiceFile" href="javascript:void(0);" title="{{ __('admin.download_invoice') }}" onclick="downloadimg('{{ $order->id }}', 'invoice', '{{ Str::substr($order->invoice, stripos($order->invoice, "invoice_") + 8) }}')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>
                                                    @endif
                                                    </div>
                                                </div>
                                            </div>
                                                </div>
                                            </form>
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

                        <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                            <a href="{{ route('order-list') }}" ><button type="button" class="btn btn-primary">{{ __('admin.update') }}</button></a>
                            <a href="{{ route('order-list') }}" >
                                <button type="button" class="btn btn-cancel ms-3">{{ __('admin.cancel') }}</button>
                            </a>
                            {{--<a href="{{ route('order-list') }}" >
                                <button type="button" class="btn btn-cancel ms-3">Cancel</button>
                            </a>--}}
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="profile-1" role="tabpanel" aria-labelledby="profile-tab" data-orderid="{{$order->id}}">
                    <div class="row">
                        <div class="col-md-12 pb-2">
                            <!-- <div class="card">

                                <div class="card-body p-0"> -->
                                    <div class="activityopen"></div>
                                <!-- </div>
                            </div> -->
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

    <!-- <div class="modal version2 fade" id="pickup_dateTimeModal" tabindex="-1" role="dialog" aria-labelledby="pickup_dateTimeModalLabel" aria-modal="true" style="padding-right: 17px">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header py-3">
                    <h5 class="modal-title d-flex align-items-center text-white" id="pickup_dateTimeModalLabel">
                        <img height="24px" class="pe-2" src="{{URL::asset('front-assets/images/icons/order_detail_title.png')}}" alt="Ready To Dispatch"> Ready To Dispatch
                    </h5>
                    <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close">
                        <img src="{{URL::asset('front-assets/images/icons/times.png')}}" alt="Close">
                    </button>
                </div>

                <div class="modal-body p-3">
                    <div id="viewQuoteDetailBlock">
                        <div class="row align-items-stretch">

                <div class="modal-footer px-3">
                    <a class="btn btn-primary" id="orderPickUpBtn">Update</a>
                    <a class="btn btn-cancel" data-bs-dismiss="modal">Cancel</a>
                </div>
            </div>
        </div>
     </div> -->

@endsection

@section('scripts')
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

Include Moment.js CDN
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>

Include Bootstrap DateTimePicker CDN
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script> -->

    <script>

        function show(input) {
            var file = input.files[0];
            var size = Math.round((file.size / 1024))
            if(size > 3000){
                swal({
                    icon: 'error',
                    title: '',
                    text: '{{__('admin.file_size_under_3mb')}}',
                })
            } else {
                var fileName = file.name;
                var allowed_extensions = new Array("jpg","png", "pdf");
                var file_extension = fileName.split('.').pop();
                for(var i = 0; i < allowed_extensions.length; i++)
                {
                    if(allowed_extensions[i]==file_extension)
                    {
                        valid = true;
                        $.ajax({
                            url: $("#editOrderform").attr('action'),
                            type: $("#editOrderform").attr('method'),
                            data: new FormData($("#editOrderform")[0]),
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                if (data.success == true) {
                                    $("#editOrderform")[0].reset();
                                    $('#status_change_' + data.id).attr('data-' + input.name, 0);
                                    $('#file-'+data.key).empty();
                                    $('#file-'+data.key).html(data.html);
                                    $.toast({
                                        heading: "{{__('admin.success')}}",
                                        text: "{{__('admin.order_updated_success_alert')}}",
                                        showHideTransition: "slide",
                                        icon: "success",
                                        loaderBg: "#f96868",
                                        position: "top-right",
                                    });
                                }
                            },
                        });
                        return;
                    }
                }
                valid = false;
                swal({
                    // title: "Rfq Update",
                    text: "{{__('admin.upload_image_or_pdf')}}",
                    icon: "warning",
                    //buttons: true,
                    buttons: ["{{ __('admin.no') }}", "{{ __('admin.yes') }}"],
                    // dangerMode: true,
                })
            }

        }

        function downloadimg(id, fieldName, name){
            var data = {
                id: id,
                fieldName: fieldName
            }
            $.ajax({
                url: "{{ route('download-image-ajax') }}",
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
                    $("#editOrderform")[0].reset();
                },
            });
        }

        function downloadCertificate(id, fieldName, name) {
            event.preventDefault();
            var data = {
                id: id,
                fieldName: fieldName
            }
            $.ajax({
                url: "{{ route('quote-download-certificate-ajax') }}",
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

        $(function() {
            $('#profile-tab').click( function() {
                var orderId = $(this).attr('data-orderid');
                $.ajax({
                    url: "{{ route('admin-get-order-activity-ajax', '') }}" + "/" + orderId,
                    type: 'GET',
                    success: function(successData) {
                        if (successData.activityhtml) {
                            $('.activityopen').html(successData.activityhtml);
                        }

                    },
                    error: function() {
                        console.log('error');
                    }
                });
            });
            $(document).on('click', '.removeFile', function (e) {
                e.preventDefault();
                var element = $(this);
                var id = $(this).attr("data-id");
                var fileName = $(this).attr("id");
                var dataName = $(this).attr("data-name")
                var data = {
                    fileName: fileName,
                    filePath: $(this).attr("file-path"),
                    id: $(this).attr("data-id"),
                    _token: $('meta[name="csrf-token"]').attr("content"),
                };
                swal({
                    title: "Are you sure?",
                    //text: "You want to change order status.",
                    icon: "warning",
                    buttons: ["{{ __('admin.cancel') }}", "{{ __('admin.ok') }}"],
                    dangerMode: false,
                }).then((changeit) => {
                    if (changeit) {
                        $.ajax({
                            url: "{{ route('order-file-delete-ajax') }}",
                            data: data,
                            type: "POST",
                            success: function (successData) {
                                element.remove();
                                $("#" + fileName + "Download").remove();
                                $('.'+fileName).remove();
                                console.log('f '+ id);
                                console.log('d '+ dataName);
                                $('#status_change_' + id).attr('data-'+dataName, 1);
                            },
                            error: function () {
                                console.log("error");
                            },
                        });
                    }
                });
            });
        });

        $(document).ready(function () {
            $(document).on('click', '.viewStatusDetail', function () {
                var orderId = $(this).attr('data-id');
                $.ajax({
                    url: "{{ route('get-order-status-details-ajax', '') }}" + "/" +
                        orderId,
                    type: 'GET',
                    success: function (successData) {
                        if (successData.html) {
                            $('#changeStatusModalBlock').html(successData.html);
                            $('#changeStatusModal').modal('show');
                        }
                    },
                    error: function () {
                        console.log('error');
                    }
                });
            });

            //Datepicker function
            var date = new Date();
            date.setDate(date.getDate());
            //$('#pickup_date').datepicker('setDate', date);
            $('#pickup_date').datepicker({
                startDate: date,
                format: 'dd-mm-yyyy',
            });

            $('#pickup_date').on('changeDate', function(ev) {
                $(this).datepicker('hide');
            });
            $(".disabled").addClass("old");

            //TimePicker


            $(document).on('change', '.orderStatusChange', function () {
                let that = $(this);
                let selectedStatus = $(this).val();
                let orderId = $(this).attr('data-order-id');
                let batchId = $(this).attr('data-batch-id');
                let pogenerated = $(this).attr('data-pogenerated');
                let lastStatus = $(this).attr('data-laststatus');
                let order_latter = $(this).attr('data-order_latter');
                let tax_receipt = $(this).attr('data-tax_receipt');
                let invoice = $(this).attr('data-invoice');
                let message = '';

                                if ((selectedStatus > 2 && selectedStatus != 18 && selectedStatus != 19) && pogenerated == 1) {
                                    let gpoBtn = $(document).find('a.generatePo')
                                    @if(auth()->user()->role_id == 3)
                                    swal({
                                        title: "{{ __('admin.contact_blitznet_team_and_generate_po') }}",
                                        //text: "You want to change order status.",
                                        icon: "/assets/images/info.png",
                                        buttons: ["{{ __('admin.cancel') }}", "{{ __('admin.ok') }}"],
                                        dangerMode: false,
                                    });
                                    @else
                                    swal({
                                        title: "{{ __('admin.po_error_popup') }}",
                                        //text: "You want to change order status.",
                                        icon: "/assets/images/info.png",
                                        buttons: ["{{ __('admin.cancel') }}", "{{ __('admin.ok') }}"],
                                        dangerMode: false,
                                    }).then((changeit) => {
                                        if (changeit) {
                                            gpoBtn.trigger('click');
                                        }
                                    });
                                    @endif
                                    that.removeAttr('selected').find('option[value="' + lastStatus + '"]').prop('selected', true);
                                    return false;
                                }

                                if ((selectedStatus > 5 && selectedStatus != 18 && selectedStatus != 19 && selectedStatus != 15 && selectedStatus != 16 || selectedStatus == 6) && order_latter == 1) {
                                    //let gpoBtn = $(document).find('a.generatePo')
                                    swal({
                                        title: "{{__('admin.first_upload_order_letter')}}",
                                        //text: "You want to change order status.",
                                        icon: "/assets/images/info.png",
                                        buttons: ["{{ __('admin.cancel') }}", "{{ __('admin.ok') }}"],
                                        dangerMode: false,
                                    }).then((changeit) => {
                                        if (changeit) {
                                            $(document).find('#image-order_latter').trigger("focus");
                                        }
                                    });
                                    that.removeAttr('selected').find('option[value="' + lastStatus + '"]').prop('selected', true);
                                    $(".orderBatchCollapse_"+batchId).trigger('click');
                                    return false;
                                }

                                if ((selectedStatus > 13 && selectedStatus != 18 && selectedStatus != 19 && selectedStatus != 15 && selectedStatus != 16 || selectedStatus == 14) && (tax_receipt == 1 || invoice == 1)) {
                                    if (invoice == 1 && tax_receipt == 1) {
                                        message += '{{ __('admin.invoice_and_tax_receipt') }}';
                                    } else if (invoice == 1) {
                                        message += '{{ __('admin.invoice') }}';
                                    } else {
                                        message += '{{ __('admin.tax_receipt') }}';
                                    }
                                    swal({
                                        title: "{{ __('admin.first_upload_message') }}" + message + "!",
                                        //text: "You want to change order status.",
                                        icon: "/assets/images/info.png",
                                        buttons: ["{{ __('admin.cancel') }}", "{{ __('admin.ok') }}"],
                                        dangerMode: false,
                                    }).then((changeit) => {
                                        if (changeit) {
                                            if (changeit) {
                                                if (invoice == 1 && tax_receipt == 1) {
                                                    $(document).find('#image-tax_receipt').trigger("focus");
                                                } else if (invoice == 1) {
                                                    $(document).find('#image-invoice').trigger("focus");
                                                } else {
                                                    $(document).find('#tax_receipt').trigger("focus");
                                                }
                                            }
                                        }
                                    });
                                    that.removeAttr('selected').find('option[value="' + lastStatus + '"]').prop('selected', true);
                                    return false;
                                }

                                swal({
                                    title: "{{ __('admin.order_status_change_title') }}",
                                    text: "{{ __('admin.order_status_change_text') }}",
                                    icon: "/assets/images/info.png",
                                    buttons: ["No", "Yes"],
                                    dangerMode: false,
                                })
                                .then((changeit) => {
                                    if (changeit) {
                                        var data = {
                                            selectedStatusID: selectedStatus,
                                            orderId: orderId,
                                            lastStatus: lastStatus,
                                            _token: $('meta[name="csrf-token"]').attr('content')
                                        }
                                        $.ajax({
                                            url: "{{ route('order-status-change-ajax') }}",
                                            data: data,
                                            type: 'POST',
                                            success: function (successData) {
                                                if (successData.success==false){
                                                    swal({
                                                        text: successData.message,
                                                        icon: location.origin+"/front-assets/images/bank_not_found.png",
                                                        dangerMode: true,
                                                    })
                                                    that.removeAttr('selected').find('option[value="' + lastStatus + '"]').prop('selected', true);
                                                    return false;
                                                }
                                                new PNotify({
                                                    text: '{{__('admin.order_status_change_success')}}',
                                                    type: 'success',
                                                    styling: 'bootstrap3',
                                                    animateSpeed: 'fast',
                                                    delay: 1000
                                                });
                                                if (selectedStatus == 2) {
                                                    that.find('option').prop('disabled', true);
                                                }
                                                if (selectedStatus >= 6 && order_latter == 0) {
                                                    $('#order_latterFile').hide();
                                                } else {
                                                    $('#order_latterFile').show();
                                                }
                                                if (selectedStatus >= 14 && tax_receipt == 0) {
                                                    $('#tax_receiptFile').hide();
                                                } else {
                                                    $('#tax_receiptFile').show();
                                                }
                                                if (selectedStatus >= 14 && invoice == 0) {
                                                    $('#invoiceFile').hide();
                                                } else {
                                                    $('#invoiceFile').show();
                                                }
                                                /*if (selectedStatus == 13) {
                                                    that.attr('data-laststatus', 14);
                                                    that.removeAttr('selected').find('option[value="14"]').prop('selected', true);
                                                }*/

                                                $('#orderStatusDetails').html('');
                                                $('#orderStatusDetails').html(successData.orderStatusHtml);

                                            },
                                        });
                                        that.attr('data-laststatus', selectedStatus);
                                    } else {
                                        that.removeAttr('selected').find('option[value="' + lastStatus + '"]').prop('selected', true);
                                    }
                                });

                            //}
                        //}); // Done end
                    //}
                //});
            });

            $(document).on('change', '.creditOrderStatusChange', function () {

                let that = $(this);
                let selectedStatus = $(this).val();
                let orderId = $(this).attr('data-order-id');
                let pogenerated = $(this).attr('data-pogenerated');
                 let batchId = $(this).attr('data-batch-id');
                let lastStatus = $(this).attr('data-laststatus');
                let order_latter = $(this).attr('data-order_latter');
                let tax_receipt = $(this).attr('data-tax_receipt');
                let invoice = $(this).attr('data-invoice');
                let message = '';

                                if ((selectedStatus > 2 && selectedStatus != 18 && selectedStatus != 19) && pogenerated == 1) {
                                    @if(auth()->user()->role_id == 3)
                                    swal({
                                        title: "{{__('admin.contact_blitznet_team_and_generate_po')}}",
                                        //text: "You want to change order status.",
                                        icon: "/assets/images/info.png",
                                        buttons: ["{{ __('admin.cancel') }}", "{{ __('admin.ok') }}"],
                                        dangerMode: false,
                                    });
                                    @else
                                    let gpoBtn = $(document).find('a.generatePo')
                                    swal({
                                        title: "{{__('admin.po_error_popup')}}",
                                        //text: "You want to change order status.",
                                        icon: "/assets/images/info.png",
                                        buttons: ["{{ __('admin.cancel') }}", "{{ __('admin.ok') }}"],
                                        dangerMode: false,
                                    }).then((changeit) => {
                                        if (changeit) {
                                            gpoBtn.trigger('click');
                                        }
                                    });
                                    @endif
                                    that.removeAttr('selected').find('option[value="' + lastStatus + '"]').prop('selected', true);
                                    return false;
                                }

                                if (((selectedStatus > 5 || (selectedStatus != 2 && selectedStatus !=4 && selectedStatus !=5)) && selectedStatus != 18 && selectedStatus != 19 && selectedStatus != 15 && selectedStatus != 16 || selectedStatus == 6) && order_latter == 1) {
                                    //let gpoBtn = $(document).find('a.generatePo')
                                    swal({
                                        title: "{{__('admin.first_upload_order_letter')}}",
                                        //text: "You want to change order status.",
                                        icon: "/assets/images/info.png",
                                        buttons: ["{{ __('admin.cancel') }}", "{{ __('admin.ok') }}"],
                                        dangerMode: false,
                                    }).then((changeit) => {
                                        if (changeit) {
                                            $(document).find('#image-order_latter').trigger("focus");

                                        }
                                    });

                                    that.removeAttr('selected').find('option[value="' + lastStatus + '"]').prop('selected', true);
                                    return false;
                                }

                                if ((selectedStatus > 13 && selectedStatus != 18 && selectedStatus != 19 && selectedStatus != 15 && selectedStatus != 16 || selectedStatus == 14) && (tax_receipt == 1 || invoice == 1)) {
                                    if (invoice == 1 && tax_receipt == 1) {
                                        message += '{{ __('admin.invoice_and_tax_receipt') }}';
                                    } else if (invoice == 1) {
                                        message += '{{ __('admin.invoice') }}';
                                    } else {
                                        message += '{{ __('admin.tax_receipt') }}';
                                    }
                                    swal({
                                        title: "{{ __('admin.first_upload_message') }} " + message + "!",
                                        //text: "You want to change order status.",
                                        icon: "/assets/images/info.png",
                                        buttons: ["{{ __('admin.cancel') }}", "{{ __('admin.ok') }}"],
                                        dangerMode: false,
                                    }).then((changeit) => {
                                        if (changeit) {
                                            if (invoice == 1 && tax_receipt == 1) {
                                                $(document).find('#image-tax_receipt').trigger("focus");
                                            } else if (invoice == 1) {
                                                $(document).find('#image-invoice').trigger("focus");
                                            } else {
                                                $(document).find('#tax_receipt').trigger("focus");
                                            }
                                        }
                                    });
                                    that.removeAttr('selected').find('option[value="' + lastStatus + '"]').prop('selected', true);
                                    return false;
                                }

                                let data = {
                                    selectedStatusID: selectedStatus,
                                    orderId: orderId,
                                    lastStatus: lastStatus,
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                }
                                if (selectedStatus < 18) {
                                    swal({
                                        title: "{{__('admin.order_status_change_title')}}",
                                        text: "{{__('admin.order_status_change_text')}}",
                                        icon: "/assets/images/info.png",
                                        buttons: ["{{ __('admin.cancel') }}", "{{ __('admin.ok') }}"],
                                        dangerMode: false,
                                    }).then((changeit) => {
                                        if (changeit) {
                                            creditOrderStatusChange(data, that, lastStatus);
                                            that.attr('data-laststatus', selectedStatus);
                                        } else {
                                            that.removeAttr('selected').find('option[value="' + lastStatus + '"]').prop('selected', true);
                                        }
                                    });
                                } else {
                                    let approveBtn = true;
                                    let rejectBtn = false;
                                    let swal_title = '{{__('admin.approve')}}';
                                    let swal_icon = 'info';
                                    let swal_text = '{{__('admin.approve_credit_message')}}';
                                    if (selectedStatus == 19) {
                                        approveBtn = false;
                                        rejectBtn = true;
                                        swal_title = '{{ __('admin.reject') }}';
                                        swal_icon = 'warning';
                                        swal_text = "{{__('admin.reject_credit_message')}}";
                                    }
                                    let request_days = $(this).find('option[value="' + selectedStatus + '"]').data('request_days');
                                    swal({
                                        title: swal_title,
                                        text: swal_text,
                                        icon: swal_icon,
                                        buttons: {
                                            cancel: "Cancel!",
                                            Approve: {
                                                text: "{{__('admin.approve')}}",
                                                value: 1,
                                                visible: approveBtn,
                                                className: "swal-button btn-success text-white"
                                            },
                                            Reject: {
                                                text: '{{ __('admin.reject') }}',
                                                value: 2,
                                                visible: rejectBtn,
                                                className: "swal-button btn-danger text-white"
                                            },
                                        },
                                        dangerMode: false,
                                    }).then((value) => {
                                        if (value == 1) {//Approved
                                            data.is_approved = 1;
                                            creditOrderStatusChange(data, that, lastStatus);
                                        } else if (value == 2) {//Reject
                                            data.is_approved = 0;
                                            creditOrderStatusChange(data, that, lastStatus);
                                        } else {
                                            that.removeAttr('selected').find('option[value="' + lastStatus + '"]').prop('selected', true);
                                        }
                                    });
                                }
                            //}
                        //}); // Done end
                    //}
                //});
            });

            function creditOrderStatusChange(data, selector, lastStatus) { console.log(data,"data");
                $.ajax({
                    url: "{{ route('credit-order-status-change-ajax') }}",
                    data: data,
                    type: 'POST',
                    success: function (successData) {
                        if (successData.success==false){
                            swal({
                                text: successData.message,
                                icon: location.origin+"/front-assets/images/bank_not_found.png",
                                dangerMode: true,
                            })
                            selector.removeAttr('selected').find('option[value="' + lastStatus + '"]').prop('selected', true);
                            return false;
                        }
                        if (data.selectedStatusID >= 18) {
                            if (data.selectedStatusID == 18) {
                                new PNotify({
                                    text: '{{__('admin.credit_approve_success_message')}}',
                                    type: 'success',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 1000
                                });
                            } else {
                                new PNotify({
                                    text: '{{__('admin.credit_approve_failed_message')}}',
                                    type: 'notice',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 1000
                                });
                            }
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000)
                        } else {
                            new PNotify({
                                text: '{{ __('admin.order_status_change_success') }}',
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 1000
                            });
                            if (data.selectedStatusID >= 6 && data.order_latter == 0) {
                                $('#order_latterFile').hide();
                            } else {
                                $('#order_latterFile').show();
                            }
                            if (data.selectedStatusID >= 14 && data.tax_receipt == 0) {
                                $('#tax_receiptFile').hide();
                            } else {
                                $('#tax_receiptFile').show();
                            }
                            if (data.selectedStatusID >= 14 && data.invoice == 0) {
                                $('#invoiceFile').hide();
                            } else {
                                $('#invoiceFile').show();
                            }
                            if (data.selectedStatusID == 13) {
                                selector.attr('data-laststatus', 17);
                                selector.removeAttr('selected').find('option[value="17"]').prop('selected', true);
                            }

                            $('#orderStatusDetails').html('');
                            $('#orderStatusDetails').html(successData.orderStatusHtml);
                        }
                    },
                });
            }

            $(document).on('click', '.generatePo', function () {
                var orderId = $(this).attr('data-id');
                $.ajax({
                    url: "{{ route('get-order-details-ajax', '') }}" + "/" +
                        orderId,
                    type: 'GET',
                    success: function (successData) {
                        if (successData.html) {
                            $('#generatePoModalBlock').html(successData.html);
                            $('#generatePoModal').modal('show');
                        }
                    },
                    error: function () {
                        console.log('error');
                    }
                });
            });

            $(document).on('click', '.sendPoToSupplier', function () {
                $(this).attr('disabled', true);
                let orderId = $(this).attr('data-id');
                $.ajax({
                    url: "{{ route('send-po-to-supplier-ajax') }}",
                    type: 'POST',
                    data: {
                        id: orderId,
                        comment: $(this).closest('tbody').find('#comment').val(),
                        _token: $("input[name='_token']").val()
                    },
                    success: function (successData) {
                        new PNotify({
                            text: '{{__('admin.po_generated_success_alert')}}',
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });
                        $('#status_change_' + orderId).attr('data-pogenerated', 0);
                        $('.showDownloadPo').html('')
                        $('.showDownloadPo').append(successData.html);
                        $('#generatePoModal').modal('hide');
                        $('#generatePo' + orderId).remove();

                    },
                    error: function () {
                        console.log('error');
                    }
                });
            });

            $(document).on('click', '.getSingleOrderDetail', function () {
                var orderId = $(this).attr('data-id');
                $.ajax({
                    url: "{{ route('get-single-order-detail-ajax', '') }}" + "/" +
                        orderId,
                    type: 'GET',
                    success: function (successData) {
                        console.log(successData);
                        if (successData.html) {
                            $('#showDownloadPO').append(successData.html);
                            $('#staticBackdrop').modal('show');
                        }
                    },
                    error: function () {
                        console.log('error');
                    }
                });
            });

        });
    </script>
@endsection
