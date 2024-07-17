<style>
    .QC-font {
        font-size: 13px;
        vertical-align: middle;
    }
    .datehide span {
       display:none; 
    }
    .progresscard {
        left: -130px !important;
        bottom: 28px !important;
    }

    .actionquotebtns .btn-box {
        width: 26px;
        height: 26px;
        padding: 4px;
        margin: 2px;
        text-align: center;
        line-height: 1;
    }

    .actionquotebtns .btn-box img {
        max-width: 90%;
        max-height: 90%;
    }

    .progresscard::after {
        content: ''; 
        left: 58%; 
    }
    .progresscard::before {
        content: ''; 
        left: 58%; 
    }
    .btn-outline-primary:hover{
        background-color: transparent !important;
    }
    table.dataTable.no-footer {
    border-bottom: inherit;
}
table.table-bordered.dataTable th:first-child, table.table-bordered.dataTable td:first-child {
    border-left-width: 1px;
}
table.table-bordered.dataTable thead th{border-top-width: 1px;}
</style>
<div class="compare-quote d-none" id="compare-quote">
    <div class="card">
        <div class="px-3 py-1 bg-light border-bottom">
            <h5 class="modal-title d-flex w-100 align-items-center">{{__('rfqs.compare_quote')}}
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table mb-0 table-bordered " id="qouteListing{{$rfq->id}}">
                <thead class="table-light">
                    <tr>
                        <th class=" text-center QC-font text-nowrap">{{__('rfqs.quote')}}</th>
                        <th class=" text-center QC-font text-nowrap">{{__('rfqs.products')}}</th>
                        <th class=" text-center QC-font text-nowrap">{{__('rfqs.tot_amount')}} ( Rp )</th>
                        <th class=" text-center QC-font text-nowrap">{{__('rfqs.delivery_days')}}</th>
                        <th class=" text-center QC-font text-nowrap">{{__('rfqs.supplier_name')}}</th>
                        <th class=" text-center QC-font text-nowrap">{{ __('rfqs.valid_till') }}</th>
                        <th class=" text-center QC-font text-nowrap">{{__('admin.status')}}</th>
                        <th class=" text-center QC-font text-nowrap no-sort">{{__('rfqs.action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rfq->compareQuote as $quote)
                        <tr>
                            <th class=" text-center QC-font text-nowrap">BQTN-{{$quote->id}}</th>
                            <td class=" text-center QC-font text-nowrap">
                                <div class="multipleproducthover">
                                        <div href="javascript:voic(0);" class="tooltiphtml w-auto">
                                            <span class="text-primary"> {{$quote->number_of_products}}</span>
                                                <div class="tooltip_section text-dark text-start p-1 multi_pro_list">
                                                    <table class="table mb-0 border w-100">
                                                        <thead>
                                                        <tr class="tableHead_color">
                                                            <th width="50%">{{ __('rfqs.product') }}</th>
                                                            <th width="25%">{{ __('rfqs.product_description') }}</th>
                                                            <th width="25%" class="text-end">{{ __('rfqs.quantity') }}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($rfq->all_products as $key => $value)
                                                            @php
                                                                $class = '';

                                                                if(!in_array($value->product_id, array_column($quote->rfqIdArray, 'rfq_product_id'))){
                                                                    $class = 'class=opacity-25';
                                                                }
                                                            @endphp
                                                            <tr {{$class}}>
                                                                <td class="text-wrap">{{ $value->category . ' - ' . $value->sub_category . ' - ' . $value->product }}</td>
                                                                <td class="text-wrap">{{ $value->product_description }}</td>
                                                                <td class="text-end text-nowrap"> {{ $value->quantity }} {{ $value->unit_name }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                    </div>
                                </div>
                            </td>
                            <td class=" text-center QC-font text-nowrap">{{number_format($quote->final_amount, 2)}}</td>
                            <td class=" text-center QC-font text-nowrap">{{$quote->max_delivery_days}}</td>
                            <td class=" text-center QC-font text-nowrap">{{$quote->supplier_company_name}} @if($quote->logistic_check == 1 && $quote->logistic_provided == 0) {{ __('rfqs.by_jne') }} @endif</td>
                            <td class=" text-center QC-font text-nowrap datehide"><span>{{ date('Ymd', strtotime($quote->valid_till)) }}</span>{{ date('d-m-Y', strtotime($quote->valid_till)) }}</td>
                            <td class="text-center QC-font text-nowrap">{{__('rfqs.'.$quote->quote_status_name)}} </td> 
                            <td class=" text-center QC-font text-nowrap actionquotebtns approvalpopupleft">
                                @if($quote->orderPlaced <= 0 )
                                    @if($quote->status_id == 1)
                                        @if($rfq->isAuthUser)
                                            <a href="javascript:void(0)" title="{{ __('rfqs.place_order') }}" data-id="{{ $quote->id }}" class="btn btn-outline-secondary rounded-2 btn-box ms-auto showPlaceOrderModal showPlaceOrderModal{{ $quote->id }} PlaceBtn" data-bs-toggle="modal" data-bs-target="#placeorder"><img src="{{ URL::asset('front-assets/images/icons/icon_product_details.png') }}"></a>
                                        @else
                                            @if($rfq->approvalProcess==0)
                                                @can('create buyer orders')
                                                    <a href="javascript:void(0)" title="{{ __('rfqs.place_order') }}" data-id="{{ $quote->id }}" class="btn btn-outline-secondary rounded-2 btn-box ms-auto showPlaceOrderModal showPlaceOrderModal{{ $quote->id }} PlaceBtn" data-bs-toggle="modal" data-bs-target="#placeorder"><img src="{{ URL::asset('front-assets/images/icons/icon_product_details.png') }}"></a>
                                                 @endcan
                                            @else
                                                @if($quote->percCount != "100%")
                                                    
                                                    <div class="ms-auto myDIV align-items-center position-relative d-inline-block" id="myDIV{{ $quote->id }}" style="visibility: none; justify-content: center;">
                                                        <div class=" d-flex pt-1" style=" border-radius: 20px;">
                                                            <div class="hide card progresscard shadow" style="position: absolute; min-width: 240px;">
                                                                <div class="card-header" style=" background-color: #1F1A5C; color: #fff; font-size:12px;">
                                                                    {{ __('profile.approval_process') }}
                                                                </div>
                                                                <div class="card-body p-0 bg-white">
                                                                    <div class="row m-0 p-0 feedbackContent" id="feedbackContentCompare{{ $quote->id }}">
                                                                        @if($quote->totalUser !=0)
                                                                        @foreach($quote->userData as $val)
                                                                        <div class="col-12 border-bottom">
                                                                            <div class="d-flex mb-1 pt-1 align-items-center">
                                                                                <div class="col-md-auto" style="font-size: 12px;">
                                                                                    {{$val->email}}
                                                                                </div>
                                                                                <div class="col-md-auto ms-auto">
                                                                                    @if($val->feedback == 0)
                                                                                        <span class="pe-2">
                                                                                            <a href="javascript:void(0)" onclick="configUserResendMail({{$val->id}},{{$quote->id}},1)"><img src='{{ URL::asset("front-assets/images/resendMailBtn.png") }}' height="20px" width="20px" alt="" srcset=""></a>
                                                                                        </span>
                                                                                        <img src='{{ URL::asset("front-assets/images/pending.png") }}' height="14" width="14" alt="Pending" srcset="">
                                                                                    @elseif($val->feedback == 1)
                                                                                        <span class="pe-2"></span>
                                                                                        <img src='{{ URL::asset("front-assets/images/thumbs-up.png") }}' height="14" width="14" alt="Pending" srcset="">
                                                                                    @else
                                                                                        <span class="pe-2">
                                                                                            <a href="javascript:void(0)" onclick="configUserResendMail({{$val->id}},{{$quote->id}},0)"><img src='{{ URL::asset("front-assets/images/resendMailBtn.png") }}' height="20px" width="20px" alt="" srcset=""></a>
                                                                                        </span>
                                                                                        <img src='{{ URL::asset("front-assets/images/thumbs-down.png") }}' height="14" width="14" alt="Pending" srcset="">
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @endforeach
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="approvalWaitingBtn pe-1 {{$quote->totalUser <= 0 ? 'hide' : ''}}" id="approvalWaitingBtncompare{{ $quote->id }}"> 
                                                            <a href="javascript:void(0)" class="btn btn-outline-secondary rounded-2 btn-box" style="font-size: 10px;">
                                                                <img src="{{ URL::asset('front-assets/images/icons/timer.gif') }}">
                                                            </a>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class=" d-inline-block getApprovalBtnDiv pe-1 {{$quote->totalUser > 0 ? 'hide' : ''}}" id="getApprovalBtnDivCompare{{ $quote->id }}">
                                                        <button  data-id="{{ $quote->id }}" class="btn btn-outline-secondary rounded-2 btn-box ms-auto sendQuoteApprovalBtn getApprovalCompare{{$quote->id}} getappbtn" id="" data-bs-toggle="modal" data-bs-target="#placeOrder" title="{{ __('rfqs.get_approval') }}">
                                                            <img src="{{ URL::asset('front-assets/images/icons/icon_product_details.png') }}">
                                                        </button>
                                                    </div>
                                                    
                                                @else
                                                    <a href="javascript:void(0)" title="{{ __('rfqs.place_order') }}" data-id="{{ $quote->id }}" class="btn btn-outline-secondary rounded-2 btn-box ms-auto showPlaceOrderModal showPlaceOrderModal{{ $quote->id }}" data-bs-toggle="modal" data-bs-target="#placeorder"><img src="{{ URL::asset('front-assets/images/icons/icon_product_details.png') }}"></a>
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                @endif
                                <a title="{{ __('rfqs.view') }}" href="javascript:void(0)" data-id="{{ $quote->id }}" class="showQuoteModal btn btn-box btn btn-outline-secondary rounded-2">
                                    <img src="{{ URL::asset('front-assets/images/icons/eye.png') }}" >
                                </a>
                                <a title="{{__('dashboard.print_icon')}}" href="{{ route('dashboard-get-rfq-quote-print',Crypt::encrypt($quote->id)) }}" target="_blank"  class="btn btn-outline-secondary rounded-2 btn-box"  style="">
                                    <img src="{{ URL::asset('front-assets/images/icons/icon_print.png') }}">
                                </a>

                            </td>    
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
