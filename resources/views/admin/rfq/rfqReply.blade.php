@extends('admin/adminLayout')
@section('content')
    <style>
        .date .parsley-errors-list {
            position: absolute;
            bottom: -44px;
        }
        select.form-control.selectBox.pay_charges[disabled] {
            background-color: #e9ecef !important;
        }
        .accordion-button{
            font-size: 13px !important;
        }

        .accordion-button:not(.collapsed) {

            color: black;
            background-color: #efefef;
        }
    </style>
    <div class="row">

        <div class="col-md-12 d-flex align-items-center mb-3">
            <h1 class="mb-0">{{ $rfq->reference_number }}</h1>
            <a href="{{ route('rfq-list') }}" class="ms-auto">
                <button type="button"  class="btn-close ms-auto"></button>
            </a>
        </div>
        <div class="col-md-12">
            <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab"
                            data-bs-target="#home" type="button" role="tab" aria-controls="home"
                            aria-selected="true">{{ __('admin.rfq_reply') }}</button>
                </li>
            </ul>

            <div class="tab-content pt-3 pb-0" id="myTabContent">
                <input type="hidden" name="authUserRoleId" id="authUserRoleId" value="{{ auth()->user()->role_id }}" />
                <div class="tab-pane fade show active" id="home" role="tabpanel"
                     aria-labelledby="home-tab">
                    <div class="row align-items-stretch">
                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0"><img src="{{URL::asset('assets/icons/comment-alt-edit.png')}}" alt="RFQ Detail" class="pe-2"> {{ __('admin.rfq_detail') }} </h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <div class="row rfqform_view bg-white">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">{{ __('admin.rfq_number') }}</label>
                                            <div>{{ $rfq->reference_number }}</div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">{{ __('admin.date') }}</label>
                                            <div class="text-dark">{{ date('d-m-Y H:i:s', strtotime($rfq->created_at)) }}</div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">{{ __('admin.status') }}</label>
                                            <div>{{ __('admin.'.trim($rfq->status_name)) }}</div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">{{ __('admin.payment_term') }}</label>
                                            @if($rfq->payment_type==0)
                                                <div class="text-dark"><span class="badge rounded-pill bg-success">{{ __('admin.advance') }}</span></div>
                                            @elseif($rfq->payment_type==1)
                                               <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.credit') }} -{{$rfq->credit_days}}</span></div>
                                            @elseif($rfq->payment_type==3)
                                                <div class="text-dark"><span class="badge rounded-pill bg-danger">{{  __('admin.lc')  }}</span></div>
                                            @elseif($rfq->payment_type==4)
                                                <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.skbdn') }}</span></div>
                                            @else
                                                <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.credit') }}</span></div>
                                            @endif
                                           <!--  <div class="text-dark"><span class="badge rounded-pill bg-{{ $rfq->is_require_credit?'danger':'success' }}">{{ $rfq->is_require_credit?'Credit':'Advance' }}</span>
                                            </div> -->
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">{{ __('admin.company_of_buyer') }}</label>
                                            <div class="text-dark">{{ $rfq->company_name }}</div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">{{ __('admin.customer_name') }}</label>
                                            <div class="text-dark"> {{ $rfq->firstname }} {{ $rfq->lastname }}</div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">{{ __('admin.customer_email') }}</label>
                                            <div class="text-dark">{{ $rfq->email }}</div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">{{ __('admin.customer_phone') }}</label>
                                            <div class="text-dark"> {{ countryCodeFormat($rfq->phone_code, $rfq->mobile) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0">
                                        <img height="20px" src="{{URL::asset('assets/icons/truck.png')}}" alt="Delivery Detail " class="pe-2">
                                        @if(in_array($total_rfq_products[0]['category_id'],\App\Models\Category::SERVICES_CATEGORY_IDS)) {{ __('dashboard.pickup_details') }} @else {{ __('admin.delivery_detail') }} @endif
                                    </h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <div class="row rfqform_view bg-white">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('rfqs.address') }}:</label>
                                            <div class="text-dark">{{$rfq->address_line_1 ?($rfq->address_line_1.','):'-'}} {{$rfq->address_line_2 ? $rfq->address_line_2:''}}</div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('admin.sub_district') }}:</label>
                                            <div class="text-dark">{{$rfq->sub_district ? $rfq->sub_district:'-'}}</div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('admin.district') }}:</label>
                                            <div class="text-dark">{{$rfq->district ? $rfq->district:'-'}}</div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('admin.city') }}:</label>
                                            <div class="text-dark">{{$rfq->city_id > 0 ? getCityName($rfq->city_id) : ($rfq->city ? $rfq->city : '-')}}</div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('admin.provinces') }}:</label>
                                            <div class="text-dark">{{$rfq->state_id > 0 ? getStateName($rfq->state_id): ($rfq->state ? $rfq->state : '-')}}</div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('admin.pin_code') }}:</label>
                                            <div class="text-dark">{{ $rfq->pincode }}</div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('admin.expected_delivery_date') }}</label>
                                            <div class="text-dark">{{ changeDateFormat($rfq->expected_date) }}</div>
                                        </div>
                                        <div class="col-md-8 mb-3">
                                            <label class="form-label">{{ __('admin.other_option') }}</label>
                                            <div class="text-dark ps-4">
                                                <div class="form-check form-check-inline my-0">
                                                    <input class="form-check-input" type="checkbox"
                                                           id="inlineCheckbox1" value="option1" disabled=""
                                                           {{ $rfq->unloading_services == 1 ? 'checked' : ''  }}>
                                                    <label class="form-check-label"
                                                           for="inlineCheckbox1" readonly="">{{ __('admin.need_uploding_services') }}</label>
                                                </div>
                                                <div class="form-check form-check-inline my-0">
                                                    <input class="form-check-input" type="checkbox"
                                                           id="inlineCheckbox2" value="option2"
                                                           {{ $rfq->rental_forklift == 1 ? 'checked' : ''  }}
                                                           disabled="" >
                                                    <label class="form-check-label"
                                                           for="inlineCheckbox2">{{ __('admin.need_rental_forklift') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Product Detail -->
                        <form id="quoteReply" class="" method="POST" action="{{ route('quote-create') }}" enctype="multipart/form-data" data-parsley-validate>
                        @csrf

                        <input type="hidden" name="rfq_id" value="{{ $rfq->id }}" />
                        <input type="hidden" name="amount" id="amount" value="">
                        <input type="hidden" name="payment_type" id="payment_type" value="{{$rfq->payment_type}}">
                        <input type="hidden" name="credit_days" id="credit_days" value="{{$rfq->credit_days}}">
                        <div class="row">
                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/boxes.png')}}" alt="Product Detail" class="pe-2"> {{ __('admin.supplier_detail') }}</h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <div class="row rfqform_view bg-white">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('admin.supplier') }} <span class="text-danger">*</span></label></label>
                                            <div class="text-primary">

                                                <select class="form-select border-0 ps-0 selectBox w-auto text-primary" name="supplier" id="supplier" data-parsley-errors-container="#supplier_error" data-parsley-allselected="true" required  style="border: 0 !important;" onchange="SupplierChange(this.value, {{$rfq->id}})">
                                                    <option value="-1" {{ auth()->user()->role_id == 3 ? 'disabled' : '' }}>{{ __('admin.select_supplier') }}</option>
                                                    @foreach ($suppliers->unique('supplierName') as $supplier)
                                                        @if(auth()->user()->role_id == 3 && (isset(auth()->user()->supplierId()->supplier_id) && auth()->user()->supplierId()->supplier_id == $supplier->supplierId))
                                                            <option value="{{ $supplier->supplierId }}" {{ ((isset(auth()->user()->supplierId()->supplier_id) && auth()->user()->supplierId()->supplier_id == $supplier->supplierId) ? 'selected' : 'disabled')  }}>{{ $supplier->supplierName }}</option>
                                                        @elseif(auth()->user()->role_id == 1 || Auth::user()->hasRole('agent'))
                                                            @if(isset($supplier->grpSuppId) && !empty($supplier->grpSuppId))
                                                                @if(in_array($supplier->grpSuppId,$suppliers->pluck('supplierId')->toArray()))
                                                                    <option value="{{ $supplier->supplierId }}" {{ ((isset($supplier->grpSuppId) && $supplier->grpSuppId == $supplier->supplierId) ? 'selected' : 'disabled')  }} >{{ $supplier->supplierName }}</option>
                                                                @else
                                                                    <option value="{{ $supplier->supplierId }}" {{ ((isset($supplier->grpSuppId) && $supplier->grpSuppId == $supplier->supplierId) ? 'selected' : '')  }} >{{ $supplier->supplierName }}</option>
                                                                @endif
                                                            @else
                                                            <option value="{{ $supplier->supplierId }}">{{ $supplier->supplierName }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <div id="supplier_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 pb-2">
                            <!-- Group RFQ along with Single RFQ Section (Ronak M - 12-05-2022) -->
                            @if(isset($groupOrderData) && !empty($groupOrderData))
                                <div class="card">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/boxes.png')}}" alt="Product Detail" class="pe-2"> {{ __('admin.product_detail') }}</h5>
                                        <h5 class="mb-0 ms-auto px-3 d-none">{{ __('admin.total_product') }}: 3</h5>
                                        <h5 class="mb-0">{{ __('admin.total_amount') }}: <span class="text-primary">Rp</span> <span class="text-primary" id="total_amount_span">{{ $groupOrderData->original_price * $groupOrderData->rfqQty }}</span></h5>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row rfqform_view bg-white" id="rfq_product_details_view1">
                                            <div class="accordion" id="accordionExample1">
                                                <div class="accordion-item mb-1">
                                                    <h2 class="accordion-header d-flex bg-light" id="heading">
                                                        <div style=" box-shadow: inset 0 -1px 0 rgb(0 0 0 / 13%);">
                                                            <input type="checkbox" class="form-check ms-3 productCheckbox" id="checkrfq_0" name="checkrfq[]" checked>
                                                            <input type="hidden" id="product_id_0" name="product_id[]" value="{{ $rfq->product_id }}">
                                                            <input type="hidden" id="qty_0" name="qty[]" value="{{ $rfq->quantity }}" >
                                                            <input type="hidden" id="unit_id_0" name="unit_id[]" value="{{ $rfq->unit_id }}" >
                                                            <input type="hidden" id="rfq_product_id0" name="rfq_product_id[]" value="{{ $rfq->rfq_product_id }}" >
                                                        </div>
                                                        <button class="accordion-button px-2 py-2 collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="false" aria-controls="collapse" style=" box-shadow: inset 0 -1px 0 rgb(0 0 0 / 13%);">
                                                            <div class="flex-grow-1">1. {{ $groupOrderData->category }} - {{ $groupOrderData->sub_category }} - {{ $groupOrderData->product }} </div>
                                                            <div class="px-2"><span class="fw-normal text-muted">{{ __('admin.qty') }}:</span> {{ $groupOrderData->rfqQty }} {{ $groupOrderData->unitName }} </div>
                                                            <div class="px-2"><span class="fw-normal text-muted">{{ __('admin.amount') }}:</span> Rp <span id="change_amount">{{ $groupOrderData->original_price * $groupOrderData->rfqQty }}</span></div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse1" class="accordion-collapse collapse" aria-labelledby="heading" data-bs-parent="#accordionExample1">
                                                        <div class="accordion-body row g-3">
                                                            @if(!empty($groupOrderData->product_description))
                                                            <div class="col-md-12 mb-2">
                                                                <label for="">{{ __('admin.description') }}</label>
                                                                <div>{{ strip_tags($groupOrderData->product_description) }}</div>
                                                            </div>
                                                            @endif
                                                            <div class="col-md-6 col-lg-2 mb-2 pe-1">
                                                                <label class="form-label">{{ __('admin.product_estimated_weight') }} <small>({{ __('admin.per_unit') }})</small><span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <input type="text" name="weights[]" id="weights" data-parsley-errors-container="#weights-errors" min="0.1" class="form-control border-end-0" onkeyup="changeWeightGroup(this.value, {{ $groupOrderData->quantity }})" onkeypress="return isNumberKey(this, event);" onblur="isDecimalNumberKey(this.value)";  required>
                                                                    <span class="input-group-text bg-white border-start-0" id="basic-addon2">Kg</span>
                                                                </div>
                                                                <div id="weights-errors"></div>
                                                            </div>
                                                            <div class="col-md-6 col-lg-2 mb-2">
                                                                <label class="form-label">{{ __('admin.total_estimated_weight') }}</label>
                                                                <div class="text-dark mt-1"> <span id="change_weight">0</span> Kg</div>
                                                            </div>
                                                            <div class="col-md-6 col-lg-2 mb-2">
                                                                <label class="form-label">{{ __('admin.product_dimensions') }}</label>
                                                                <div>
                                                                    <input type="text" name="dimensions[]" id="dimensions" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-lg-3 mb-2">
                                                                <label class="form-label">{{ __('admin.price') }} <small>({{ __('admin.per_unit_in_rp') }})</small><span class="text-danger">*</span></label>
                                                                <div>
                                                                    <input type="text" name="price[]" id="price" min="1" class="form-control" value="{{ $groupOrderData->original_price }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-lg-3 mb-3">
                                                                <label class="form-label">{{ __('admin.product_certificate') }}</label>
                                                                <div class="d-flex">
                                                                    <span class=""><input type="file" name="certificate[]" id="certificate" accept=".jpg,.png,.pdf" onchange="show(this)" hidden=""><label id="upload_btn" for="certificate">{{ __('admin.browse') }}</label></span>
                                                                    <div id="file-certificate"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- End -->
                            @else
                            <!-- Multiple RFQ Section -->
                            <div class="card">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/boxes.png')}}" alt="Product Detail" class="pe-2"> {{ __('admin.product_detail') }}</h5>
                                    <h5 class="mb-0 ms-auto px-3 d-none">{{ __('admin.total_product') }}: 3</h5>
                                    <h5 class="mb-0">{{ __('admin.total_amount') }}: <span class="text-primary">Rp</span> <span class="text-primary" id="total_amount_span">0.00</span></h5>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row rfqform_view bg-white" id="rfq_product_details_view">
                                        <div class="accordion" id="accordionExample">
                                            @foreach($total_rfq_products as $key => $value)
                                                <div class="accordion-item mb-1">
                                                    <h2 class="accordion-header d-flex bg-light" id="heading{{$key}}">
                                                        <div style=" box-shadow: inset 0 -1px 0 rgb(0 0 0 / 13%);">
                                                            <input  type="checkbox" class="form-check ms-3 productCheckbox" id="checkrfq_{{$key+1}}" name="checkrfq_{{$key+1}}" data-product-details="{{ json_encode($value) }}" onclick="enabledDisabled(this, {{$key+1}})">
                                                        </div>
                                                        <button class="accordion-button px-2 py-2 collapsed bg-light" type="button" data-bs-toggle="" data-bs-target="#collapse_{{$key}}" aria-expanded="false" aria-controls="collapse_{{$key}}" style=" box-shadow: inset 0 -1px 0 rgb(0 0 0 / 13%);">
                                                            <div class="flex-grow-1">{{ $key+1 }}. {{ $value['category'] }} - {{ $value['sub_category'] }} - {{ $value['product'] }}</div>
                                                            <div class="px-2"><span class="fw-normal text-muted">{{ __('admin.qty') }}:</span> {{ $value['quantity'] }} {{ $value['name'] }}</div>
                                                            <div class="px-2"><span class="fw-normal text-muted">{{ __('admin.amount') }}:</span> Rp <span id="change_amount_{{$key+1}}">0.00</span></div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse_{{$key}}" class="accordion-collapse collapse" aria-labelledby="heading{{$key}}" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body row">
                                                            @if(!empty($value['product_description']))
                                                            <div class="col-md-12 mb-2">
                                                                <label for="">{{ __('admin.description') }}</label>
                                                                <div>{{ $value['product_description']}}</div>
                                                            </div>
                                                            @endif
                                                            <div class="col-md-6 col-lg-2 mb-0">
                                                                <label class="form-label">{{ __('admin.product_estimated_weight') }} <small>({{ __('admin.per_unit') }})</small><span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <input type="text" name="weights" id="weights{{$key+1}}" min="0.1" class="form-control border-end-0" disabled onkeyup="weightChange(this.value, {{ $value['quantity'] }},{{ $key+1 }})" onblur="isDecimalNumberKey(this.value,{{ $key+1 }});">
                                                                    <span class="input-group-text bg-white border-start-0" id="basic-addon2">Kg</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-lg-2 mb-0">
                                                                <label class="form-label">{{ __('admin.total_estimated_weight') }}</label>
                                                                <div class="text-dark mt-1"> <span id="change_weight_{{$key+1}}">0</span> Kg</div>
                                                            </div>
                                                            <div class="col-md-6 col-lg-2 mb-0">
                                                                <label class="form-label">{{ __('admin.product_dimensions') }}</label>
                                                                <div>
                                                                    <input type="text" name="dimensions[]" id="dimensions{{$key+1}}" class="form-control" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-lg-3 mb-0">
                                                                <label class="form-label">{{ __('admin.price') }} <small>({{ __('admin.per_unit_in_rp') }})</small><span class="text-danger">*</span></label>
                                                                <div>
                                                                    <input type="text" name="price[]" id="price{{$key+1}}" min="1" class="form-control" disabled onkeyup="priceChange(this.value, {{ $value['quantity'] }},{{ $key+1 }})" onkeypress="return isNumberKey(this, event);">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-lg-3 mb-3">
                                                                <label class="form-label">{{ __('admin.product_certificate') }}</label>
                                                                <div class="d-flex">
                                                                    <span class=""><input disabled type="file" name="certificate[]" id="certificate{{$key+1}}" accept=".jpg,.png,.pdf" onchange="show(this)" hidden=""><label id="upload_btn" for="certificate{{$key+1}}">{{ __('admin.browse') }}</label></span>
                                                                    <div id="file-certificate{{$key+1}}"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End -->
                            @endif
                        </div>

                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0">
                                        <img height="20px" src="{{URL::asset('assets/icons/icon_pickup.png')}}" alt="Charges" class="pe-2">
                                        <span>@if(in_array($total_rfq_products[0]['category_id'],\App\Models\Category::SERVICES_CATEGORY_IDS)) {{ __('rfqs.delivery_address') }} @else {{ __('admin.pickup_address') }} @endif</span>
                                    </h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <div class="row">
                                        <div class="col-md-6 mb-3 address_block">
                                            <label class="form-label">{{ __('rfqs.select_address') }} <span class="text-danger">*</span></label>
                                            <select class="form-select" id="supplier_address_id" name="supplier_address_id" required data-parsley-errors-container="#supplier_address_id_error">
                                                <option disabled selected>{{ __('rfqs.select_address') }}</option>
                                                @if(isset($supplierAddresses) && !empty($supplierAddresses))
                                                    @foreach ($supplierAddresses as $item)
                                                        <option data-address_name="{{$item->address_name}}" data-address_line_2="{{$item->address_line_2}}" data-address_line_1="{{$item->address_line_1}}" data-sub_district="{{$item->sub_district}}" data-district="{{$item->district}}" data-city="{{$item->city}}" data-city-id="{{$item->city_id}}" data-state="{{$item->state}}" data-state-id="{{$item->state_id}}" data-country-id="{{$item->country_id}}"  data-pincode="{{$item->pincode}}" value="{{ $item->id }}" {{ isset($defaultAddress->address_name) && $defaultAddress->address_name == $item->address_name ? 'selected' : ''  }}>{{ $item->address_name }}</option>
                                                        @endforeach
                                                    <option data-address-id="0" value="0">Other</option>
                                                @endif
                                            </select>
                                            <div id="supplier_address_id_error"></div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="address_name" class="form-label">{{ __('rfqs.address_name') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="address_name" id="address_name" class="form-control" value="{{ isset($defaultAddress->address_name) ? $defaultAddress->address_name : '' }}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="address_line_1" class="form-label">{{ __('admin.address_line') }} 1<span class="text-danger">*</span></label>
                                            <input type="text" name="address_line_1" id="address_line_1" class="form-control" value="{{ isset($defaultAddress->address_line_1) ? $defaultAddress->address_line_1 : '' }}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="address_line_2" class="form-label">{{ __('admin.address_line') }} 2<span style="color:red;"></span></label>
                                            <input type="text" name="address_line_2" id="address_line_2" class="form-control" value="{{ isset($defaultAddress->address_line_2) ? $defaultAddress->address_line_2 : '' }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="Sub district" class="form-label">{{ __('admin.sub_district') }}<span class="text-danger">*</span></label>
                                            <input type="text" id="sub_district" name="sub_district" class="form-control" value="{{ isset($defaultAddress->sub_district) ? $defaultAddress->sub_district : '' }}" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="district" class="form-label">{{ __('admin.district') }}<span class="text-danger">*</span></label>
                                            <input type="text" name="district" id="district" class="form-control" value="{{ isset($defaultAddress->district) ? $defaultAddress->district : '' }}" required>
                                        </div>

                                        <div class="col-md-6 mb-3 select2-block" id="countryId_block">
                                            <label for="countryId" class="form-label">{{ __('admin.country') }}<span class="text-danger">*</span></label>
                                            <select class="form-select select2-custom" id="countryId" name="countryId" data-placeholder="{{ __('admin.select_country') }}" required data-parsley-errors-container="#user_country">
                                                <option value="">{{ __('admin.select_country') }}</option>
                                                @foreach ($countrys as $country)
                                                    <option value="{{ $country->id }}" @if(isset($defaultAddress->country_id) && $defaultAddress->country_id == $country->id) selected @endif >{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                            <div id="user_country"></div>
                                        </div>

                                        <div class="col-md-6 mb-3 select2-block" id="stateId_block">
                                            <label for="stateId" class="form-label">{{ __('admin.provinces') }}<span style="color:red;">*</span></label>
                                            <select class="form-select select2-custom" id="stateId" name="stateId" data-placeholder="{{ __('admin.select_province') }}" data-selected-state="{{ isset($defaultAddress->state_id) ? $defaultAddress->state_id : '' }}" required data-parsley-errors-container="#user_provinces">
                                                <option value="" >{{ __('admin.select_state') }}</option>
                                                <option value="-1">Other</option>
                                            </select>
                                            <div id="user_provinces"></div>
                                        </div>
                                        <div class="col-md-3 mb-3 hide" id="state_block">
                                            <label for="provinces" class="form-label">{{ __('admin.other_provinces') }}<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="provinces" id="provinces" value="{{ isset($defaultAddress->state) ? $defaultAddress->state : '' }}" required >
                                        </div>
                                        <div class="col-md-6 mb-3 select2-block" id="cityId_block">
                                            <label for="cityId" class="form-label">{{ __('admin.city') }}<span class="text-danger">*</span></label>
                                            <select class="form-select select2-custom" id="cityId" name="cityId" data-placeholder="{{ __('admin.select_city') }}" data-selected-city="{{ isset($defaultAddress->city_id) ? $defaultAddress->city_id : '' }}" required data-parsley-errors-container="#user_city">
                                                <option value="">{{ __('admin.select_city') }}</option>
                                                <option value="-1">Other</option>
                                            </select>
                                            <div id="user_city"></div>
                                        </div>
                                        <div class="col-md-3 mb-3 hide" id="city_block">
                                            <label for="city" class="form-label">{{ __('admin.other_city') }}<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="city" id="city" value="{{ isset($defaultAddress->city) ? $defaultAddress->city : '' }}" >
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="pincode" class="form-label">{{ __('admin.pin_code') }}<span style="color:red;">*</span></label>
                                            <input type="text" pattern=".{5,7}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\.*?)\..*/g, '$1');" class="form-control" id="pincode" name="pincode" value="{{ isset($defaultAddress->pincode) ? $defaultAddress->pincode : '' }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- / End -->

                        <!-- Group Discount Charges (Ronak M - 12-05-2022) -->
                        @if(!empty($groupDiscount) && !empty($groupDiscountPrice))
                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0"><img height="20px" src="{{ URL::asset('assets/icons/group_details.png') }}"
                                        alt="Payment Fees" class="pe-2"> Group Discount
                                    </h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <p id="no_pay_charge" style="display: none">{{ __('admin.no_charges_added') }}</p>
                                    <div class="row pay_main_div">
                                        <div class="col-md-3">
                                            <label for="pay_charges" class="form-label">{{ __('admin.charges') }}<span class="text-danger">*</span></label>
                                            <input type="hidden" name="charges[]" class="form-control pay_charges" value="{{ $group_discount_charges->id }}">
                                            <input type="text" id="group_discount" class="form-control" value="{{ $group_discount_charges->name }}" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="pay_type" class="form-label">{{ __('admin.type') }}</label>
                                            <input type="text" name="chargeType[]" class="form-control pay_type" value="{{ $group_discount_charges->type == 0 ? '%' : 'RP (Flat)' }}" readonly>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="pay_charges_value" class="form-label">{{ __('admin.charges_value') }}</label>
                                            <input type="text" name="chargeValue[]" class="form-control pay_charges_value" value="{{ $groupDiscount }}" placeholder="Charges Value" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="pay_charge_amount" class="form-label">{{ __('admin.charges_amount') }} (RP)</label>
                                                <input type="text" name="charge_amount[]" class="form-control pay_charge_amount"  placeholder="Charges Amount" value="{{ $groupDiscountPrice }}" data-plus-minus="{{ $group_discount_charges->addition_substraction }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <!-- End -->

                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0 d-flex w-100 align-items-center"><img height="20px" src="{{URL::asset('assets/icons/platform_charges.png')}}" alt="{{ __('admin.supplier_other_charges') }} " class="pe-2"> {{ __('admin.supplier_other_charges') }}
                                        <span class="icon ms-1"><a href="javascript:void(0)" id="btnClone"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" /><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" /></svg></a></span>

                                        {{--@if(auth()->user()->role_id == 3)--}}
                                        <span class="ms-auto"><input class="form-check-input" type="checkbox" id="logistic_check" name="logistic_check" onclick="removeLogisticCharges()"  style="margin-top: 0"> {{ __('admin.logistic_charges_included') }}</span>

                                        <span class="ms-3"><input class="form-check-input" type="checkbox" id="inclusive_tax_other" name="inclusive_tax_other" value="1"  style="margin-top: 0"> {{ __('admin.inclusive_tax') }}</span>
                                        <span class="ms-1"  data-bs-toggle="tooltip" data-bs-placement="top" title="This charges is inclusive tax"> <i class="fa fa-info-circle"></i></span>


                                        {{--@endif--}}
                                    </h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <div id="chargeMainDiv">
                                        <div class="row chargeDiv" id="chargeDiv">
                                            <div class="col-md-2 mb-3">
                                                <label for="customChargeName" class="form-label">{{ __('admin.custom_charge_name') }}</label>
                                                <input type="text" name="custom_charge_name[]" id="customChargeName" class="form-control customChargeName">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="charges" class="form-label">{{ __('admin.charges') }} <span class="text-danger">*</span></label>
                                                <select name="charges[]" id="charges" class="form-select selectBox charges">
                                                    <!-- <option value="0">Select charge</option> -->
                                                    <option value="">{{ __('admin.select_charge') }}</option>
                                                    @foreach ($platformCharges as $charge)
                                                        <option data-plus-minus="{{ $charge->addition_substraction }}"
                                                            charge-type="{{ $charge->type == 0 ? '%' : 'RP (Flat)' }}"
                                                            data-value-on="{{ $charge->value_on == 0 ? 'Amount' : 'Quantity' }}"
                                                            data-value="{{ $charge->charges_value }}"
                                                            value="{{ $charge->id }}">{{ $charge->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <i class="fa fa-chevron-down"></i>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label for="chargeType" class="form-label">{{ __('admin.type') }}</label>
                                                <input type="text" name="chargeType[]" id="chargeType" class="form-control chargeType" readonly>
                                            </div>

                                            <div class="col-md-2 mb-3">
                                                <label for="amount" class="form-label">{{ __('admin.charges') }} Value <span class="text-danger">*</span></label>
                                                <input type="text" name="chargeValue[]" id="chargeValue" class="form-control chargesVal"
                                                    onkeypress="return isNumberKey(this, event);" placeholder="Charges Value">
                                                <div class="chargeVal" style="display: none"></div>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <div class="">
                                                    <label for="amount" class="form-label">{{ __('admin.charges_amount') }} (RP) <span class="text-danger">*</span></label>
                                                    <input type="text" ref-name="charge_amount[]" name="charge_amount[]" id="charge_amount" class="form-control chargeAmount"
                                                           placeholder="Charges Amount" onkeypress="return isNumberKey(this, event);" data-plus-minus="">
                                                    <div class="chargeAmount" style="display: none"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-1 pt-1  mb-3">
                                                <label for="" class="form-label"></label>
                                                <div style="line-height: 28px;">
                                                    <span class="icon deleteCharge">
                                                        <a href="javascript:void(0)" id="" class="text-danger removeCharge">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(auth()->user()->role_id != 3)
                        <div class="col-md-12 pb-2" id="logistic-charges">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0  d-flex w-100 align-items-center">
                                        <img height="20px" src="{{URL::asset('assets/icons/logistic_charges.png')}}" alt="Logistic Charges " class="pe-2"> {{ __('admin.logistic_charges') }} <span class="icon ms-1">
                                            <a href="javascript:void(0)" id="btnLogClone">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                                </svg>
                                            </a>
                                        </span>
                                        <span class="ms-auto"><input class="form-check-input" type="checkbox" id="inclusive_tax_logistic" name="inclusive_tax_logistic" value="1" style="margin-top: 0"> {{ __('admin.inclusive_tax') }}</span>
                                        <span class="pe-2 ms-2" data-toggle="tooltip" data-placement="top" title="" data-bs-original-title="This charges is inclusive tax." aria-label="{{ __('admin.logistic_charges_message') }}"><i class="fa fa-info-circle"></i></span>
                                    </h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <div id="logchargeMainDiv">
                                        <div class="row chargeDiv" id="Log_chargeDiv">
                                            <div class="col-md-2 mb-3">
                                                <label for="customChargeName" class="form-label">{{ __('admin.custom_charge_name') }}</label>
                                                <input type="text" name="custom_charge_name[]" id="logCustomChargeName" class="form-control logCustomChargeName">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="charges" class="form-label">{{ __('admin.charges') }} <span class="text-danger">*</span></label>
                                                <select name="charges[]" id="logCharges" class="form-select selectBox logcharges">
                                                    <option value="">{{ __('admin.select_charge') }}</option>
                                                    @foreach ($logisticCharges as $charge)
                                                        <option data-plus-minus="{{ $charge->addition_substraction }}"
                                                                charge-type="{{ $charge->type == 0 ? '%' : 'RP (Flat)' }}"
                                                                data-value-on="{{ $charge->value_on == 0 ? 'Amount' : 'Quantity' }}"
                                                                data-value="{{ $charge->charges_value }}"
                                                                value="{{ $charge->id }}">{{ $charge->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <i class="fa fa-chevron-down"></i>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label for="chargeType" class="form-label">{{ __('admin.type') }}</label>
                                                <input type="text" name="chargeType[]" id="logChargeType" class="form-control chargeType" readonly>
                                            </div>

                                            <div class="col-md-2 mb-3">
                                                <label for="chargeValue" class="form-label">{{ __('admin.charges_value') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="chargeValue[]" id="logChargeValue" class="form-control chargesVal disVal" onkeypress="return isNumberKey(this, event);" placeholder="Charges Value">
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <div class="">
                                                    <label for="charge_amount" class="form-label">{{ __('admin.charges_amount') }} (RP) <span class="text-danger">*</span></label>
                                                    <input type="text" ref-name="logCharge_amount[]" name="charge_amount[]" id="logCharge_amount" class="form-control chargeAmount" placeholder="Charges Amount"
                                                    onkeypress="return isNumberKey(this, event);" data-plus-minus="">
                                                </div>
                                            </div>
                                            <div class="col-md-1 pt-1 mb-3">
                                                <label for="" class="form-label"></label>
                                                <div style="line-height: 28px;">
                                                    <span class="icon deleteCharge">
                                                        <a href="javascript:void(0)" id="" class="text-danger removeCharge">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="container1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/platform_charges.png')}}"
                                        alt="{{ __('admin.platform_charges') }}" class="pe-2"> {{ __('admin.platform_charges') }}
                                    </h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <p id="no_pay_charge" style="display: none">{{ __('admin.no_charges_added') }}</p>
                                    <div id="payment-main-div">
                                        @php $paymentChargesHtml = view('admin.rfq.rfq-reply-payment-charge',['paymentCharges'=>$paymentCharges])->render() @endphp
                                        {!! $paymentChargesHtml  !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0">
                                        <img height="20px" src="{{URL::asset('assets/icons/other_calendar.png')}}" alt="Other Details " class="pe-2">
                                        {{ __('admin.other_details') }}
                                    </h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="amount" class="form-label">{{ __('admin.delivery_note') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="note" id="note" class="form-control" required placeholder="Note">
                                        </div>
                                        <div class="col-md-12 mb-3 ">
                                            <label for="comment" class="form-label">{{ __('admin.comment') }}</label>
                                            <textarea name="comment" class="form-control" id="comment" cols="30" rows="3"></textarea>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="minDays" class="form-label">{{ __('admin.deliver_order_in_min_days') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="minDays" id="minDays" onkeypress="return isNumberKey(this, event);"
                                                   class="form-control" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="maxDays" class="form-label">{{ __('admin.deliver_order_in_max_days') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="maxDays" id="maxDays" onkeypress="return isNumberKey(this, event);"
                                                   class="form-control" required>
                                            <div id="showErr"></div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="maxDays" class="form-label">{{ __('admin.valid_till') }} <span class="text-danger">*</span></label>
                                            <div id="date" class="input-group date datepicker">
                                                <input type="text" id="valid_till" name="valid_till" class="form-control" style="border: 1px solid #dee2e6;" required autocomplete="off">
                                                <span class="input-group-addon input-group-append border-left">
                                                    <span class="mdi mdi-calendar input-group-text" style="padding: 0.7rem 0.75rem;border: 1px solid #dee2e6;"></span>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3" id="pickup_service_div">
                                            <label class="form-label">{{ __('admin.quincus_pickup_service') }}@if(!Auth::user()->hasRole('supplier'))<span class="text-danger">*</span> @endif</label>
                                            <select class="form-select" name="pickup_service" id="pickup_service">
                                                <option disabled selected value="">{{ __('admin.select') }} {{ __('admin.quincus_pickup_service') }}</option>
                                                <option value="Express">Express</option>
                                                <option value="Trucking">Trucking</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3" id="pickup_fleet_div">
                                            <label class="form-label"> {{ __('admin.quincus_pickup_fleet') }} @if(!Auth::user()->hasRole('supplier'))<span class="text-danger">*</span> @endif</label>
                                            <select class="form-select" name="pickup_fleet" id="pickup_fleet">
                                                <option disabled selected value="">{{ __('admin.select') }} {{ __('admin.quincus_pickup_fleet') }}</option>
                                                <option class="Motorcycle" value="Motorcycle">Motorcycle</option>
                                                <option class="Car" value="Car">Car</option>
                                                <option class="Truck" value="Truck">Truck</option>
                                            </select>
                                        </div>
                                        @if(isset($logistics_services) && sizeof($logistics_services) > 0)
                                        <div class="col-md-4 mb-3" id="logistics_services_div">
                                            <label class="form-label">{{ __('admin.quincus_services') }}@if(!Auth::user()->hasRole('supplier'))<span class="text-danger">*</span> @endif</label>
                                            <select class="form-select " name="logistics_service_code" id="logistics_services">
                                                <option disabled selected value="">{{ __('admin.select_logistics_service') }}</option>
                                                @foreach($logistics_services as $service)
                                                    <option value="{{ $service->service_code }}" class="logistics_services" id="{{ $service->service_code }}">{{ $service->service_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif

                                        <div class="col-md-4 mt-0 d-flex" id="wood_packing_div">
                                            <label>&nbsp;</label>
                                            <div class="form-check ps-4 pe-2 d-flex align-items-center ">
                                                <input class="form-check-input" value="1" type="checkbox" id="insurance_flag" name="insurance_flag" checked  disabled>
                                               <label class="ms-2">  {{ __('admin.insurance_flag') }}</label>
                                            </div>

                                             <div class="form-check ps-4 d-flex align-items-center ">
                                                <input class="form-check-input" type="checkbox" id="wood_packing" name="wood_packing" value="1">
                                               <label class="ms-2">  {{ __('admin.wood_packing') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{__('admin.supplier')}} {{__('admin.commercial_tc')}} </label>
                                            <div class="d-flex">
                                                <span class="">
                                                    <input type="file" name="termsconditions_file" class="form-control" id="termsconditions_file" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                    <label id="upload_btn" for="termsconditions_file">{{ __('profile.browse') }}</label>
                                                </span>
                                                <div id="file-termsconditions_file" class="d-flex align-items-center">
                                                    <input type="hidden" class="form-control" id="old_termsconditions_file" name="old_termsconditions_file" >
                                                </div>
                                                <div id="file-termsconditions_file_exist" class="d-flex align-items-center">
                                                    @if (!empty($supplierTermsDocument->commercialCondition))
                                                        @php
                                                            $termsconditionsFileTitle = Str::substr($supplierTermsDocument->commercialCondition, stripos($supplierTermsDocument->commercialCondition, "termsconditions_file_") + 21);
                                                            $extension_termsconditions_file = getFileExtension($termsconditionsFileTitle);
                                                            $termsconditions_file_filename = getFileName($termsconditionsFileTitle);
                                                            if(strlen($termsconditions_file_filename) > 10){
                                                                $termsconditions_file_name = substr($termsconditions_file_filename,0,10).'...'.$extension_termsconditions_file;
                                                            } else {
                                                                $termsconditions_file_name = $termsconditions_file_filename.$extension_termsconditions_file;
                                                            }
                                                        @endphp
                                                        <input type="hidden" class="form-control" id="oldtermsconditions_file" name="oldtermsconditions_file" value="{{ $supplierTermsDocument->commercialCondition }}">
                                                        <span class="ms-2">
                                                            <a href="{{$supplierTermsDocument->commercialCondition ? Storage::url($supplierTermsDocument->commercialCondition) : 'javascript:void(0)';}}" target="_blank" id="termsconditionsFileDownload" download title="{{ $termsconditionsFileTitle }}" style="text-decoration: none;"> {{ $termsconditions_file_name }}</a>
                                                        </span>
                                                        <span class="ms-2">
                                                            <a class="termsconditions_file" href="{{$supplierTermsDocument->commercialCondition ? Storage::url($supplierTermsDocument->commercialCondition) : 'javascript:void(0)';}}" title="{{ __('profile.download_file') }}" download style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0">
                                        <img height="20px" src="{{URL::asset('assets/icons/tax.png')}}" alt="Tax" class="pe-2">
                                        {{ __('admin.tax') }}
                                    </h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="amount" class="form-label">{{ __('admin.tax') }} (%) <span class="text-danger">*</span></label>
                                            <input type="text" name="tax"  data-parsley-type="number" value="0" id="tax" class="form-control"
                                                   onkeypress="return isNumberKey(this, event);" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="amount" class="form-label">{{ __('admin.tax_amount') }} (RP) <span class="text-danger">*</span></label>
                                            <input type="text" name="tax_amount" id="tax_amount"
                                                   class="form-control" readonly
                                                   onkeypress="return isNumberKey(this, event);">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">&nbsp;</label>
                                            <div>
                                                <button type="button"
                                                        class="btn btn-info btn-icon-text text-white py-2"
                                                        id="calculateAmount">
                                                    <i class="mdi mdi-calculator btn-icon-append"></i>
                                                    {{ __('admin.calculate_final_amount') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/total.png')}}"
                                                          alt="Platform Charges " class="pe-2"> {{ __('admin.final_amount') }} (RP) ({{ __('admin.charge_discount_message') }}) </h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <input type="text" name="finalAmount" id="finalAmount" class="form-control form-lg bg-light" onkeypress="return isNumberKey(this, event);" value="0">
                                        </div>
                                        <div class="col-md-8 mb-3 text-danger d-flex align-items-center">
                                            @if(isset($paymentCharges[0]['charges_value']) && $paymentCharges[0]['charges_value']<10450)
                                                {{--<small class="alert alert-danger p-1 mb-0">
                                                    {!! sprintf(__('admin.transaction_charges_will_be_deducted'),"<strong>Rp ".getDisbursementCharge()."</strong>") !!}
                                                </small>--}}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 bg-white py-3 d-flex align-items-center error_res">
                            @if((!empty($rfq->termsconditions_file) || file_exists($rfq->termsconditions_file)) || (isset($tc_document) && (!empty($tc_document->buyer_default_tcdoc) || file_exists($tc_document->buyer_default_tcdoc))))
                            <div>
                                <div class="form-check ps-4 d-flex align-items-center ">
                                    <input class="form-check-input mt-0" type="checkbox" value="" name="terms" id="terms" data-parsley-errors-container="#terms_validate" data-parsley-error-message="{{__('admin.select_ckheckbox')}}" required>
                                    <label class="form-check-label ms-2" for="terms">
                                        {{$rfq->termsconditions_file ? (__('admin.buyeragree')) : (__('admin.blitznet_agree'))}} <a href="{{$rfq->termsconditions_file ? Storage::url($rfq->termsconditions_file) : Storage::url($tc_document->buyer_default_tcdoc)}}" target="_blank">{{__('admin.terms_condition')}}</a>
                                    </label>
                                </div>
                                <div id="terms_validate" class="ps-4"></div>
                            </div>
                            @endif
                            <div class="ms-auto">
                                <button type="button" id="submitQuote" class="btn btn-primary">{{ __('admin.submit') }}</button>
                                <a href="{{ route('rfq-list') }}" class="btn btn-cancel ms-3">{{ __('admin.cancel') }}</a>
                            </div>

                        </div>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row">
                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-body"> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Show confirmation pop to tag product to supplier (Ronak M - 10-01-2023) --> 
    <div class="modal version2 fade" data-bs-backdrop="static" data-bs-keyboard="false" id="productTagModal" tabindex="-1" role="dialog" aria-labelledby="productTagModalLabel" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header py-3">
                    <h5 class="modal-title d-flex align-items-center" id="staticBackdropLabel">
                        <img class="pe-2" height="24px" src="{{ URL::asset('assets/icons/order_detail_title.png') }}" alt="RFQ">
                        <span id="show_rfq_number"></span>
                    </h5>

                    <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close" id="dismiss_product_tag_btn">
                        <img src="{{ URL::asset('front-assets/images/icons/times.png') }}" alt="Close">
                    </button>
                </div>
                <div class="modal-body p-3">
                    <div class="row">
                        
                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5><img src="{{ URL::asset('front-assets/images/icons/comment-alt-edit.png') }}" alt="RFQ Detail" class="pe-2">{{ __('admin.product_details') }}</h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <div class="row rfqform_view bg-white">
                                        <div class="col-md-4 pb-2">
                                            <label for="category_label">{{ __('admin.category') }}:</label>
                                            <div id="category_name_id"></div>
                                        </div>
                                        <div class="col-md-4 pb-2">
                                            <label for="sub_category_label">{{ __('admin.sub_category') }}:</label>
                                            <div id="sub_category_name_id"></div>
                                        </div>
                                        <div class="col-md-4 pb-2">
                                            <label for="product_name_label">{{ __('admin.product_name') }}:</label>
                                            <div id="product_name_id"></div>
                                        </div>
                                     </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="p-3">
                                <h4 class="text-center mb-0 text-wrap" style="color: #7b29ff;">{{ (auth()->user()->role_id == 3) ? __('admin.not_tag_product_supplier') : __('admin.not_tag_product_line') }}. Do you want to tag "<span id="cat_subcat_product_data"></span>" to send a quote?</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="rfq_product_key" value="" />
                <input type="hidden" id="rfq_product_data" value="" />

                <div class="modal-footer px-3">
                    <button type="button" id="close_product_tag_btn" class="btn btn-cancel">{{ __('admin.no') }}</button>
                    <button type="button" id="tag_supplier_prod_btn" class="btn btn-primary" >{{ __('admin.yes') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->


    {{--<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script> --}}
    <script src="{{ URL::asset('/assets/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
    <script type="text/javascript">
        /****begin:Initiate SnippetEditPickupDetail Function****/
        jQuery(document).ready(function(){
            SnippetEditPickupDetail.init();
            ChangeLogisticCheck();
        });
        /****end:Initiate SnippetEditPickupDetail Function****/

        var checkedd = 0;
        function show(input, key) {
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
                var allowed_extensions = new Array("jpg", "png", "pdf");
                var file_extension = fileName.split('.').pop();
                let image_path = '{{URL::asset("assets/icons/times-circle copy.png")}}';
                for (var i = 0; i < allowed_extensions.length; i++) {
                    if (allowed_extensions[i] == file_extension) {
                        valid = true;
                        var tooltip = fileName;
                        if(fileName.length > 13) {
                            fileName = fileName.substring(0,10) +'....'+file_extension;
                        }
                        $('#file-' + input.name.replace('[]','')+key).html('');
                        $('#file-' + input.name.replace('[]','')+key).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.name.replace('[]','')+key + 'Download" title="'+tooltip+'" style="text-decoration: none">' + fileName + '</a></span><span class="removeSelectedFile" data-name="' + input.name.replace('[]','')+key + '"><a href="javascript:void(0)" title="{{ __('profile.remove_file') }}" style="text-decoration: none;"><img src="' + image_path + '" alt="CLose button" class="ms-2"></a></span>');
                        return;
                    }
                }
                valid = false;
                swal({
                    // title: "Rfq Update",
                    text: "{{ __('admin.upload_image_or_pdf') }}",
                    icon: "/assets/images/info.png",
                    //buttons: true,
                    buttons: ["{{ __('admin.no') }}", "{{ __('admin.yes') }}"],
                    // dangerMode: true,
                })
            }
        }

        function showFile(input) {
            $('#file-termsconditions_file_exist').hide().removeClass('d-flex');

            let file = input.files[0];
            let size = Math.round((file.size / 1024));
            if(size > 3000){
                swal({
                    icon: 'error',
                    title: '',
                    text: '{{ __('profile.file_size_under_3mb') }}',
                })
            } else {
                let fileName = file.name;
                let allowed_extensions = new Array("jpg", "png", "jpeg", "pdf");
                let file_extension = fileName.split('.').pop();
                let file_name_without_extension = fileName.replace(/\.[^/.]+$/, '');
                let text = '{{ __('profile.plz_upload_file') }}';
                let image_path = '{{URL::asset("assets/icons/times-circle copy.png")}}';

                for (let i = 0; i < allowed_extensions.length; i++) {
                    if (allowed_extensions[i] == file_extension) {
                        valid = true;
                        let download_function = "'" + input.name + "', " + "'" + fileName + "'";
                        if(file_name_without_extension.length >= 10) {
                            fileName = file_name_without_extension.substring(0,10) +'....'+file_extension;
                        }
                        $('#file-' + input.name).html('');
                        $('#file-' + input.name).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.name + 'Download " style="text-decoration: none">' + fileName + '</a></span><span class="ms-2"><a class="' + input.name + ' downloadbtn hidden" href="javascript:void(0);" title="{{ __('profile.download_file') }}" onclick="downloadimg(' + download_function + ')" style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a></span><span class="removeSelectedFile" data-name="' + input.name + '"><a href="javascript:void(0)" title="{{ __('profile.remove_file') }}" style="text-decoration: none;"><img src="' + image_path + '" alt="CLose button" class="ms-2"></a></span>');
                        return;
                    }
                }
                valid = false;
                swal({
                    text: text,
                    icon: "warning",
                    buttons: ["No", "Yes"],
                })
            }
        }
        function SupplierChange(supplier_id, rfq_id){
            $.ajax({
                url: "{{ route('supplier-xenaccount-exist','') }}/"+supplier_id,
                type: 'GET',
                success: function (successData) {
                    if (successData.success == false) {
                        if(supplier_id!='-1'){  
                            swal({
                                text: successData.message,
                                icon: location.origin + "/front-assets/images/bank_not_found.png",
                                dangerMode: true,
                            })
                        }
                        $('#supplier').find('option').prop('selected', false);
                        $('#supplier').select2();
                        return false;
                    } else {
                        // this call for product details data
                        getProductDetails(supplier_id, rfq_id);
                    }
                },
                error: function () {
                    console.log("error");
                }
            });

            /* Get supplier addresses by supplier id while Admin Login. */
            $.ajax({
                url: "{{ route('getSupplierAddressById','') }}/"+supplier_id,
                type: 'GET',
                success: function (successData) {
                    if (successData.addresses) {
                        $('#supplier_address_id').empty();
                        $('#supplier_address_id').append("<option disabled selected>{{ __('rfqs.select_delivery_address') }}</option>");
                        let stateId = '';
                        let cityId = '';
                        let defaultAddValue = '';
                        $.each(successData.addresses,function(index,address) {
                            stateId     = address.state_id > 0 ? address.state_id : {{ \App\Models\UserAddresse::OtherState  }};
                            cityId      = address.city_id > 0 ? address.city_id : {{ \App\Models\UserAddresse::OtherCity  }};
                            $('#supplier_address_id').append('<option data-address_name="'+address.address_name+'" data-address_line_2="'+address.address_line_2+'" data-address_line_1="'+address.address_line_1+'" data-sub_district="'+address.sub_district+'" data-district="'+address.district+'" data-city="'+address.city+'" data-state="'+address.state+'" data-city-id="'+address.city_id+'" data-state-id="'+address.state_id+'" data-country-id="'+address.country_id+'" data-pincode="'+address.pincode+'" value="'+address.id+'">'+address.address_name+'</option>');
                            if(address.default_address == 1)
                            {
                                defaultAddValue = address.id
                            }
                        });
                        $('#supplier_address_id').append('<option data-address-id="0" value="0">Other</option>');
                        //Clear pickup detail form
                        SnippetEditPickupDetail.formClear();
                        $('#supplier_address_id').val(defaultAddValue).trigger('change');
                    }
                    if(successData.tc_document){
                        $('#file-termsconditions_file_exist').html(successData.tc_document);
                    }
                },error: function () {
                    console.log("error");
                }
            });
        }

        function getProductDetails(supplier_id, rfq_id){
            var url = "{{ route('get-rfq-product-reply', [':supplier_id', ':rfq_id', ':rfq_product']) }}";
            url = url.replace(":supplier_id", supplier_id);
            url = url.replace(":rfq_id", rfq_id);
            url = url.replace(":rfq_product", 0);
            $.ajax({
                url: url,
                type: 'GET',
                success: function (successData) {
                    if(successData.success == true){
                        $('#rfq_product_details_view').html('')
                        $('#rfq_product_details_view').html(successData.html);
                    }
                },
                error: function () {
                    console.log("error");
                }
            });
        }

        function enabledDisabled(data, key) {
            //Get all product details
            var objData = $(data).attr("data-product-details");
            var prodObjData = objData;
            var prodDataArr = $.parseJSON(prodObjData);

            //If supplier dropdown option is not selected
            if ($('#supplier').val() == -1 && $(data).prop("checked") == true) {
                $(data).removeClass('blink_me');
                swal({
                    icon: "/assets/images/info.png",
                    text: "{{ __('admin.alert_product_select_supplier') }}",
                    buttons: "{{ __('admin.ok') }}",
                }).then((willCheck) => {
                    $(data).prop("checked", false);
                });

            //If product is not in supplier listing
            } else if($(data).prop("checked") == true && $(data).hasClass('assignProduct')) {
                $(data).removeClass('blink_me');
                $("#show_rfq_number").html('BRFQ-'+prodDataArr.rfq_id);
                $("#category_name_id").html(prodDataArr.category);
                $("#sub_category_name_id").html(prodDataArr.sub_category);
                $("#product_name_id").html(prodDataArr.product);
                $("#cat_subcat_product_data").html(prodDataArr.category + '-' + prodDataArr.sub_category + '-' + prodDataArr.product);

                $("#rfq_product_key").val(key);
                $("#rfq_product_data").val(objData);

                //Enable "tag_supplier_prod_btn" whenever the popup is added
                $("#tag_supplier_prod_btn").prop('disabled',false);
                $("#productTagModal").modal('show');
                return false;

            } else {
                if($(data).prop("checked") == true) {
                    $(data).removeClass('blink_me');
                    removeDisabled(false, key);
                    addRequired(true, key);
                
                } else if($(data).prop("checked") == false) {
                    
                    enableDisableProductData(data, key);

                }
            }
        }

        //On check product checkbox, below functionality should be done
        function enableDisableProductData(data, key) {
            if ($('#weights'+key).val() != '' || $('#price'+key).val() != '' || $('#dimensions'+key).val() != '' || $('#length'+key).val() != '' || $('#width'+key).val() != '' || $('#height'+key).val() != '') {
                swal({
                    text: "{{ __('admin.reset_clear_data_supplier_product') }}",
                    icon: "/assets/images/info.png",
                    //buttons: true,
                    buttons: ["{{ __('admin.no') }}", "{{ __('admin.yes') }}"],
                    // dangerMode: true,
                }).then((willCheck) => {
                    if (willCheck) {
                        $(data).prop("checked", false);
                        $(data).addClass("blink_me");
                        remove_values(key);
                        removeDisabled(true, key);
                        addRequired(false, key)
                        totalCalulate();
                    } else {
                        $(data).prop("checked", true);
                    }
                });
            } else {
                $(data).prop("checked", false);
                $(data).addClass("blink_me");
                remove_values(key);
                removeDisabled(true, key);
                addRequired(false, key)
            }
        }

        function remove_values(key) {
            var values = 0;
            $('#weights'+key).val('');
            $('#change_weight_'+key).html(values.toFixed(2))
            $('#dimensions'+key).val('');
            $('#length'+key).val('');
            $('#width'+key).val('');
            $('#height'+key).val('');
            $('#price'+key).val('');
            $('#change_amount_'+key).html(values.toFixed(2))
            $('#file-certificate'+key).html('');
        }

        function removeDisabled(val, key){
            $('#weights'+key).prop("disabled", val);
            $('#dimensions'+key).prop("disabled", val);
            $('#length'+key).prop("disabled", val);
            $('#width'+key).prop("disabled", val);
            $('#height'+key).prop("disabled", val);
            $('#price'+key).prop("disabled", val);
            $('#product_id_'+key).prop("disabled", val);
            $('#unit_id_'+key).prop("disabled", val);
            $('#qty_'+key).prop("disabled", val);
            $('#rfq_product_id'+key).prop("disabled", val);
            $('#certificate'+key).prop("disabled", val);
        }

        function addRequired(val, key){
            $('#weights'+key).prop("required", val);
            //$('#dimensions'+key).prop("required", val);
            @if(auth()->user()->role_id != 3)
                $('#length'+key).prop("required", val);
                $('#width'+key).prop("required", val);
                $('#height'+key).prop("required", val);
            @endif
            $('#price'+key).prop("required", val);
        }

        function weightChange(val, qty, key){
            if(val != ''){
                var calculate_charege = 0;
                if(isNaN(parseFloat(val*qty))){
                    calculate_charege = 0;
                } else {
                     calculate_charege = parseFloat(val*qty).toFixed(2);
                }

                $('#change_weight_'+key).html(calculate_charege);
            } else {
                $('#change_weight_'+key).html(0);
            }
        }

        function priceValueChange(val, qty, key){
            if(val != ''){
                $('#change_amount_'+key).html(parseFloat(val*qty).toFixed(2));
                totalCalulate();
            } else {
                $('#change_amount_'+key).html(0);
                totalCalulate();
            }
        }

        function totalCalulate(){
            var total = 0;
            var price = [];
            var ids = [];
            $(':checkbox[name="checkrfq[]"]:checked').each (function () {
                checkedd = 1;
                var duplicate = this.id.replace("checkrfq_", "");
                //if(ids.indexOf(duplicate) < 0) {
                    total += parseInt($('#change_amount_'+duplicate).html());
                //}
            });
            $('#total_amount_span').html(total);
            $('#amount').val(total);
            priceChange();
            platformChargeAmountCal();
        }

        $(document).ready(function() {
            $("#quoteReply").parsley();
            parsValidation();
            Parsley.addValidator('allselected', {
                validateString: function(value) {
                    return (true==(value != '-1'));
                },
                messages: {
                    en: 'This value is required.',
                },
            });

            $(document).on('change','#supplier_address_id',function() {
                let selected_option = $('option:selected', this);
                let city = selected_option.attr('data-city') != 'null' ? selected_option.attr('data-city') : '';
                let provinces = selected_option.attr('data-state') != 'null' ? selected_option.attr('data-state') : ''
                $("#address_name").val(selected_option.attr('data-address_name'));
                $("#address_line_1").val(selected_option.attr('data-address_line_1'));
                $("#address_line_2").val(selected_option.attr('data-address_line_2'));
                $("#sub_district").val(selected_option.attr('data-sub_district'));
                $("#district").val(selected_option.attr('data-district'));
                $("#countryId").val(selected_option.attr('data-country-id')).trigger('change');
                $("#stateId").val(selected_option.attr('data-state-id'));
                $("#cityId").val(selected_option.attr('data-city-id'));
                $("#provinces").val(provinces);
                $("#city").val(city);
                $("#pincode").val(selected_option.attr('data-pincode'));
            });

            if($("#chargeMainDiv #chargeDiv").length == 1){
                $("#chargeMainDiv #chargeDiv").find('.deleteCharge').hide();
            }
            if($("#logchargeMainDiv #Log_chargeDiv").length == 1){
                $("#logchargeMainDiv #Log_chargeDiv").find('.deleteCharge').hide();
            }
            $('#showErr').html('');

            @if(auth()->user()->role_id == 3)
                var supplier_id = $('#supplier').val();
                var rfq_id = '{{ $rfq->id }}'
                if (supplier_id != -1){
                    getProductDetails(supplier_id, rfq_id);
                }
            @endif

            window.Parsley.addValidator("maxFileSize", {
                validateString: function(_value, maxSize, parsleyInstance) {
                    if (!window.FormData) {
                        alert(
                            "Upgrade your browser!"
                        );
                        return true;
                    }
                    var files = parsleyInstance.$element[0].files;
                    return (
                        files.length != 1 || files[0].size <= maxSize * (1024 * 1024)
                    );
                },
                requirementType: "integer",
                messages: {
                    en: "This file should not be larger than %s MB",
                    fr: "Ce fichier est plus grand que %s MB.",
                },
            });

            localStorage.setItem("addCharge", $("#chargeMainDiv").html());
            localStorage.setItem("addlogCharge", $("#logchargeMainDiv").html());
            $('body').on('click', '.removeCharge', function() {
                    $(this).closest('.chargeDiv').remove();
                    if ($.trim($("#chargeMainDiv").html()) == "") {
                        $('#no_charge').show();
                    } else {
                        $('#no_charge').hide();
                    }
                    if ($.trim($("#logchargeMainDiv").html()) == "") {
                        $('#no_log_charge').show();
                    } else {
                        $('#no_log_charge').hide();
                    }
                // }
            });
            var date = new Date();
            date.setDate(date.getDate());

            $('#date').datepicker({
                startDate: date,
                format: 'dd-mm-yyyy'
            });
            $('#date').on('changeDate', function(ev) {
                $(this).datepicker('hide');
                $('#valid_till').parsley().reset();
            });
            $(".disabled").addClass("old");

            /*$("#price").keyup(function() {
                var amount = 0;
                amount = $("#price").val() * $("#qty").text();
                //$('#amount').val(amount.toFixed(2));
                priceChange();
            });*/
            $("#tax").keyup(function() {
                let amount = calculateAmount();
                setTax(amount);
                $('#finalAmount').val(0);
            });

            $('.charges').on('change', function(e) {
                // console.log($(this).parent().parent());
                //console.log($('option:selected', this).val());
                var currentSelectId = $(this).attr('id').replace('charges', '');
                if( $('option:selected', this).val() == ""){
                    if(currentSelectId){
                        $('#chargeValue'+currentSelectId).parent('div').show();
                        $('#chargeValue'+currentSelectId).val('');
                        $('#charge_amount'+currentSelectId).val('');
                        $('#chargeType'+currentSelectId).val('');
                        $('#charge_amount'+currentSelectId).parent('div').show();
                    }else{
                        $('#chargeValue').parent('div').show();
                        $('#chargeValue').val('');
                        $('#charge_amount').val('');
                        $('#chargeType').val('');
                        $('#charge_amount').parent('div').show();

                    }
                    $(this).parent().parent().find(".parsley-errors-list").find("li").text('');

                    $(this).parent().parent().find("input").attr("data-parsley-required", "false");
                    $('#customChargeName').removeAttr("data-parsley-required");
                }else{
                    // $("#quoteReply").parsley().destroy();
                    $(this).parent().parent().find("input").attr("data-parsley-required", "true");
                    //$("#quoteReply").parsley();
                    var finalAmount = $('#finalAmount').val();
                    $('#chargeValue' + currentSelectId).parent('div').show();

                    var type = $('option:selected', this).attr('charge-type');
                    var valueOn = $('option:selected', this).attr('data-value-on');
                    var value = $('option:selected', this).attr('data-value');
                    var id = $('option:selected', this).attr('value');
                    var add_sub = $('option:selected', this).attr('data-plus-minus');
                    $('#charge_amount' + currentSelectId).attr("data-plus-minus", add_sub);

                    var chargeAmount = 0;
                    $('#chargeType' + currentSelectId).val(type);
                    $('#valueOn' + currentSelectId).val(valueOn);
                    $('#chargeValue' + currentSelectId).val(value);
                    if (type == '%') {
                        $('#chargeValue' + currentSelectId).attr({
                            "max": 100,
                            "min": 0
                        });
                        $('#chargeValue' + currentSelectId).parent('div').show();
                        chargeAmount = (parseInt($('#total_amount_span').html()) * value) / 100;
                    } else {
                        $('#chargeValue' + currentSelectId).removeAttr('max');
                        $('#chargeValue' + currentSelectId).removeAttr('min');
                        $('#chargeValue' + currentSelectId).parent('div').hide();
                        chargeAmount = value;
                    }
                    $('#charge_amount' + currentSelectId).val(parseFloat(chargeAmount).toFixed(2));
                    $('#customChargeName').removeAttr("data-parsley-required");
                }
            });
            $('.chargesVal').on('change', function(e) {
                setChargesVal($(this));
            });

            $('#logCharges').on('change', function(e) {
                var currentSelectId = $(this).attr('id').replace('logCharges', '');
                if( $('option:selected', this).val() == ""){
                    if(currentSelectId){

                        $('#logChargeValue'+currentSelectId).parent('div').show();
                        $('#logChargeValue'+currentSelectId).val('');
                        $('#logCharge_amount'+currentSelectId).val('');
                        $('#logChargeType'+currentSelectId).val('');
                        $('#logCharge_amount'+currentSelectId).parent('div').show();
                    }else{
                        $('#logChargeValue').parent('div').show();
                        $('#logChargeValue').val('');
                        $('#logCharge_amount').val('');
                        $('#logChargeType').val('');
                        $('#logCharge_amount').parent('div').show();
                    }

                    $(this).parent().parent().find(".parsley-errors-list").find("li").text('');
                    $(this).parent().parent().find("input").attr("data-parsley-required", "false");
                    $('#logCustomChargeName').removeAttr("data-parsley-required");
                }else{
                    // $("#quoteReply").parsley().destroy();
                    $(this).parent().parent().find("input").attr("data-parsley-required", "true");
                    // $("#quoteReply").parsley();
                    $('#logChargeValue' + currentSelectId).parent('div').show();
                    var type = $('option:selected', this).attr('charge-type');
                    var valueOn = $('option:selected', this).attr('data-value-on');
                    var value = $('option:selected', this).attr('data-value');
                    var id = $('option:selected', this).attr('value');
                    var add_sub = $('option:selected', this).attr('data-plus-minus');
                    $('#logCharge_amount' + currentSelectId).attr("data-plus-minus", add_sub);
                    var chargeAmount = 0;
                    $('#logChargeType' + currentSelectId).val(type);
                    $('#valueOn' + currentSelectId).val(valueOn);
                    $('#logChargeValue' + currentSelectId).val(value);
                    if (type == '%') {
                        $('#logChargeValue' + currentSelectId).attr({
                            "max": 100,
                            "min": 0
                        });
                        $('#logChargeValue' + currentSelectId).parent('div').show();
                        chargeAmount = (parseInt($('#total_amount_span').html()) * value) / 100;
                    } else {
                        $('#logChargeValue' + currentSelectId).removeAttr('max');
                        $('#logChargeValue' + currentSelectId).removeAttr('min');
                        $('#logChargeValue' + currentSelectId).parent('div').hide();
                        chargeAmount = value;
                    }

                    $('#logCharge_amount' + currentSelectId).val(chargeAmount);
                    $('#finalAmount').val(0);
                     $('#logCustomChargeName').removeAttr("data-parsley-required");
                }
            });

            $('.pay_charges_value').on('change', function(e) {
                setPlatformChargesVal($(this));
            });

            $(document).on("click","#btn-pay-add", function() {
                $('#no_pay_charge').hide();
                @if(auth()->user()->role_id != 3)
                $("#payment-main-div").append(@json($paymentChargesHtml));
                @endif
                //setPaymentIndex($("#payment-main-div .pay_main_div:last"));
            });

            $(document).on('click', '.remove-payment-charge', function() {
                $(this).closest('.row.pay_main_div').remove();
                $("#calculateAmount").trigger('click');
                if ($(".pay_main_div").length) {
                    $('#no_pay_charge').hide();
                } else {
                    $('#no_pay_charge').show();
                }
            });

            $('.chargeAmount').on('change', function(e) {
                $('#finalAmount').val(0);
            });

            $("#calculateAmount").bind("click", function() {
                let finalAmount = 0;
                let newAmt = calculateAmount();
                let newTaxAmt = newCalAmount();
                let taxAmount = setTax(newTaxAmt);

                finalAmount = parseFloat(newAmt) + parseFloat(taxAmount);
                if (finalAmount) {
                    finalAmount = finalAmount + getPaymentCharges();
                    $("#finalAmount").val(Math.round(finalAmount));
                }else{
                    $("#finalAmount").val(0);
                }
            });
            function newCalAmount() {
                var amtVal = parseInt($("#total_amount_span").html());
                var add_sub = 0;
                if($('#inclusive_tax_other').is(':checked') == false)
                {
                    $('input[ref-name="charge_amount[]"]').map(function() {
                        if ($(this).val() != '') {
                            add_sub = $(this).attr('data-plus-minus');
                            if (add_sub == 0) {
                                amtVal = (amtVal - $(this).val());
                            } else {
                                amtVal = (parseFloat(amtVal) + parseFloat($(this).val()));
                            }
                        }
                    });
                }
                else{
                    $('input[ref-name="charge_amount[]"]').map(function() {
                        if ($(this).val() != '') {
                            add_sub = $(this).attr('data-plus-minus');
                            if (add_sub == 0) {
                                amtVal = (amtVal - $(this).val());
                            }
                        }
                    });
                }
                if($('#inclusive_tax_logistic').is(':checked') == false)
                {
                    $('input[ref-name="logCharge_amount[]"]').map(function() {
                        if ($(this).val() != '') {
                            add_sub = $(this).attr('data-plus-minus');
                            if (add_sub == 0) {
                                amtVal = (amtVal - $(this).val());
                            } else {
                                amtVal = (parseFloat(amtVal) + parseFloat($(this).val()));
                            }
                        }
                    });
                }
                else{
                    $('input[ref-name="logCharge_amount[]"]').map(function() {
                        if ($(this).val() != '') {
                            add_sub = $(this).attr('data-plus-minus');
                            if (add_sub == 0) {
                                amtVal = (amtVal - $(this).val());
                            }
                        }
                    });
                }
                return amtVal;
            }

            $("#submitQuote").bind("click", function(e) {
                let maxDayError = $("#MaxDayError").html();
                if (maxDayError=='' || maxDayError==undefined) {
                    maxDayError = false;
                } else {
                    maxDayError = true;
                }
                $("#calculateAmount").trigger('click');
                if ($("#quoteReply").parsley().validate() && maxDayError==false) {
                    $('#submitQuote').attr('disabled', true);
                    let lc = ($('#logistic_check').prop('checked') == false || $('#logCharges').val() == '');
                    @if(auth()->user()->role_id == 1)
                     lc = ($('#logistic_check').prop('checked') == false && $('#logCharges').val() == '');
                    @endif
                    if (lc){
                        swal({
                            text: "{{ __('admin.quote_include_message') }}",
                            icon: "/assets/images/info.png",
                            //buttons: true,
                            buttons: ["{{ __('admin.no') }}", "{{ __('admin.yes') }}"],
                            closeOnClickOutside: false
                            // dangerMode: true,
                        }).then((willCheck) => {
                            if (willCheck) {
                                $("#logistic_check").prop("checked", true);
                                removeLogisticCharges();
                                submitForm(e);
                            } else {
                                @if(auth()->user()->role_id != 1)
                                    submitForm(e);
                                @endif
                            }
                             $('#submitQuote').attr('disabled', false);
                        });
                    } else {
                        submitForm(e);
                    }
                } else {
                    if(!$('#weights').val()){
                        $("#collapse1").collapse({toggle: true});
                        $('#weights').parsley().validate();
                        window.location = "#collapse1" ;
                    }

                    e.preventDefault();
                    parsValidation();

                }
            });

            //When RFQ is belongs to any group, below conditions will get apply
            @if(isset($groupOrderData) && !empty($groupOrderData))
                checkedd = 1;
                removeDisabled(false, 0);
                var perUnitPrice = parseInt($("#total_amount_span").html());
                $('#amount').val(perUnitPrice);
            @endif

        });

        function parsValidation() {
            window.Parsley.on('field:error', function () {
                if (this.$element.attr('name') == 'weights[]' || this.$element.attr('name') == 'price[]' || this.$element.attr('name') == 'length[]' || this.$element.attr('name') == 'width[]' || this.$element.attr('name') == 'height[]'){
                    var weightName = this.$element.attr('id').replace('weights','')-1;
                    var priceName = this.$element.attr('id').replace('price','')-1;
                    var lengthName = this.$element.attr('id').replace('length','')-1;
                    var widthName = this.$element.attr('id').replace('width','')-1;
                    var heightName = this.$element.attr('id').replace('height','')-1;
                    if(weightName != null || priceName != null || lengthName != null || widthName != null || heightName != null){
                        $('#heading'+weightName).find('collapsed').removeClass('collapsed')
                        $('#heading'+priceName).find('collapsed').removeClass('collapsed')
                        $('#heading'+lengthName).find('collapsed').removeClass('collapsed')
                        $('#heading'+widthName).find('collapsed').removeClass('collapsed')
                        $('#heading'+heightName).find('collapsed').removeClass('collapsed')
                        $('#collapse_'+weightName).addClass('show');
                        $('#collapse_'+priceName).addClass('show');
                        $('#collapse_'+lengthName).addClass('show');
                        $('#collapse_'+widthName).addClass('show');
                        $('#collapse_'+heightName).addClass('show');
                    }
                }
                // This global callback will be called for any field
                //  that fails validation.
                //console.log('Validation failed for: ', this.$element.attr('id'));
            });
        }

        function submitForm(e) {
            //if ($("#quoteReply").parsley().validate()) {
                let flag = 0 ;
                $('.productCheckbox').each(function(){
                    if ($(this).is(':checked') == true) {
                        flag = 1 ;
                    }
                 });
                if(flag == 0) {
                    e.preventDefault();
                    swal({
                        //title: "{{ __('admin.quote_submit') }}",
                        text: "{{ __('admin.quote_select_any_product_details') }}",
                        icon: "/assets/images/info.png",
                        buttons: "{{ __('admin.ok') }}",
                        // dangerMode: true,
                    })
                     $('#submitQuote').attr('disabled', false);
                    return false;
                }
                if ($("#finalAmount").val() > 0 && checkedd != 0) {
                    swal({
                        title: "{{ __('admin.quote_submit') }}",
                        text: "{{ __('admin.quote_submit_message') }}",
                        icon: "/assets/images/info.png",
                        //buttons: true,
                        buttons: ["{{ __('admin.validate') }}", "{{ __('admin.confirm') }}"],
                        // dangerMode: true,
                    }).then((willDelete) => {
                            if (willDelete) {
                                $("#quoteReply").submit();
                            } else {
                                $('#submitQuote').attr('disabled', false);
                                return false;
                            }
                        });
                } else {
                    e.preventDefault();
                    swal({
                        //title: "{{ __('admin.quote_submit') }}",
                        text: "{{ __('admin.quote_select_any_product_details') }}",
                        icon: "/assets/images/info.png",
                        buttons: "{{ __('admin.ok') }}",
                        // dangerMode: true,
                    })
                    //alert('{{ __('admin.final_amount_error_message') }}');
                    $('#submitQuote').attr('disabled', false);
                        return false;
                }
            //}
        }

        $(function() {
            $($("#payment-main-div .pay_main_div")).each(function( index ) {
                chargePaymetCharge('pay_charges', index);
                $('select#pay_charges_'+index).attr('disabled',true);
            });
            $("#btnClone").bind("click", function() {
                var optionCount = $('#charges option').length;
                var chargeCount = $('.charges').length+2;
                var index = '';
                if($("#container select:last").length == 0){
                    index = $("#container select").length + 1;
                } else {
                    index = parseInt($("#container select:last").attr('id').replace('charges', '')) + 1;
                }
                if ($.trim($("#chargeMainDiv").html()) == "") {
                    $('#no_charge').hide();
                    $("#chargeMainDiv").html(localStorage.getItem('addCharge'))
                } else {
                    if(index !== '' && index > 1 ){
                        if($('#charges'+ parseInt(index-1)).val() !== '' && $('#charges'+ parseInt(index-1)).val() != undefined){
                            if(optionCount >= chargeCount) {
                                clone_charges(index);
                            }
                        }
                    } else {
                        if(optionCount >= chargeCount) {
                            clone_charges(index);
                        }
                    }
                }
            });

            function clone_charges(index){
                var $clone = $("#chargeDiv").clone(true);
                $('.charges').each(function(i) {
                    $clone.find("#charges").children('option[value="'+$(this).val()+'"]').attr('disabled', 'disabled');
                });
                $clone.find("input:text").val("").end();
                //$clone.find('label').hide();
                $clone.removeData('Parsley');
                $clone.find("#customChargeName").attr("id", "customChargeName" + index);
                $clone.find("#charges").attr("id", "charges" + index);
                $clone.find("#chargeType").attr("id", "chargeType" + index);
                $clone.find("#valueOn").attr("id", "valueOn" + index);
                $clone.find("#chargeValue").attr("id", "chargeValue" + index);
                $clone.find("#charge_amount").attr("id", "charge_amount" + index);
                $clone.find("#chargeValue" + index).attr('required', true);
                $clone.find("#chargeValue" + index).val('');
                $clone.find("#chargeValue" + index).parent('div').show();
                $clone.find(".deleteCharge").show();
                /*$clone.find("#chargeValue" + index).attr('data-parsley-chargeval'+index, true);
                $clone.find("#chargeValue" + index).attr('data-parsley-errors-container',"#chargeval" + index);
                $clone.find('.chargeVal').attr('id', 'chargeval'+index).show();*/
                $clone.appendTo($("#container"));
            }

            $("#btnLogClone").bind("click", function() {
                //var index = $("#container1 select").length + 1;
                var logOptionCount = $('#logCharges option').length;
                var logChargeCount = $('.logcharges').length+2;
                var index = '';
                if($("#container1 select:last").length == 0){
                    index = $("#container1 select").length + 1;
                } else {
                    index = parseInt($("#container1 select:last").attr('id').replace('logCharges', '')) + 1;
                }
                if ($.trim($("#logchargeMainDiv").html()) == "") {
                    $('#no_log_charge').hide();
                    $("#logchargeMainDiv").html(localStorage.getItem('addlogCharge'))
                } else {
                    if(index !== '' && index > 1 ){
                        if($('#logCharges'+ parseInt(index-1)).val() !== '' && $('#logCharges'+ parseInt(index-1)).val() != undefined){
                            if(logOptionCount >= logChargeCount) {
                                clone_log_charges(index);
                            }
                        }
                    } else {
                        if(logOptionCount >= logChargeCount) {
                            clone_log_charges(index);
                        }
                    }
                }
            });

            function clone_log_charges(index) {
                var $clone = $("#Log_chargeDiv").clone(true);
                $('.logcharges').each(function (i) {
                    $clone.find("#logCharges").children('option[value="' + $(this).val() + '"]').attr('disabled', 'disabled');
                });
                $clone.find("input:text").val("").end();
                //$clone.find('label').hide();
                $clone.find("#logCustomChargeName").attr("id", "logCustomChargeName" + index);
                $clone.find("#logCharges").attr("id", "logCharges" + index);
                $clone.find("#logChargeType").attr("id", "logChargeType" + index);
                $clone.find("#valueOn").attr("id", "valueOn" + index);
                $clone.find("#logChargeValue").attr("id", "logChargeValue" + index);
                $clone.find("#logCharge_amount").attr("id", "logCharge_amount" + index);
                $clone.find("#logChargeValue" + index).parent('div').show();
                $clone.find("#logChargeValue" + index).val('');
                $clone.find(".deleteCharge").show();
                $clone.appendTo($("#container1"));
            }

        });
        $('.disVal').keyup(function() {
            if ($(this).val() > 100) {
                alert("No numbers above 100");
                $(this).val('0');
            }
        });


        $('#minDays').on('change', function(e) {
            var maxDay = parseInt($('#maxDays').val());
            var minDays = parseInt($('#minDays').val());
            if (maxDay != '' && (maxDay < minDays)) {
                $('#maxDays').val('');
                $('#showErr').html(err);
            }
            if(maxDay == minDays || (minDays < maxDay)) {
                $('#MaxDayError').html("");
            }
        });

        $('#maxDays').on('change', function(e) {
            $('#showErr').html('');
            var maxDay = parseInt($('#maxDays').val());
            var minDays = parseInt($('#minDays').val());
            if (maxDay < minDays) {
                var err =
                    '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required" id="MaxDayError">Maximum days must be higher than the Minimum days.</li></ul>'
                $('#showErr').html(err);
            }
        });

        function calculateAmount() {
            var amtVal = parseInt($("#total_amount_span").html());
            var add_sub = 0;
            $('input[name="charge_amount[]"]').map(function() {
                if ($(this).val() != '') {
                    add_sub = $(this).attr('data-plus-minus');
                    if (add_sub == 0) {
                        amtVal = (amtVal - $(this).val());
                    } else {
                        amtVal = (parseFloat(amtVal) + parseFloat($(this).val()));
                    }
                }
            });
            return amtVal;
        }
        function isDecimalNumberKey(weight,key='') {
            if(weight!= 0.0 || weight>0){
                var num = weight*1;
                num = num.toFixed(2);
                $('#weights'+key).val(num);
            }
        }
        function isNumberKey(txt, evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode == 46) {
                //Check if the text already contains the . character
                if (txt.value.indexOf('.') === -1) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if (charCode > 31 &&
                    (charCode < 48 || charCode > 57))
                    return false;
            }
            return true;
        }

        function getPaymentCharges(){
            let total = 0;
            $("#payment-main-div .pay_main_div").each(function() {
                let amount = parseFloat($(this).find('input.pay_charge_amount').val());
                total = total + amount;
            });
            return total;
        }

        let payment_index = 0;
        function setPaymentIndex(selector){
            selector.find('.pay_charges').attr('name','payment_charges['+payment_index+'][charges]');
            selector.find('.pay_type').attr('name','payment_charges['+payment_index+'][charge_type]');
            selector.find('.pay_charges_value').attr('name','payment_charges['+payment_index+'][charge_value]');
            selector.find('.pay_charge_amount').attr('name','payment_charges['+payment_index+'][charge_amount]');
            payment_index++;
        }

        function setTax(amount = 0){
            var taxAmount = (amount * $("#tax").val()) / 100;
            if(isNaN(taxAmount))
            {
                taxAmount = 0;
            }
            $('#tax_amount').val(taxAmount.toFixed(2));
            return taxAmount;
        }

        function priceChange(){
            if($('.chargesVal').length) {
                $('.chargesVal').each(function () {
                    if ($(this).val()) {
                        setChargesVal($(this));
                    }
                });
                $("#tax").trigger('keyup');
            }
        }

        function setChargesVal(selector){
            let row = selector.closest('.row.chargeDiv');
            let type = row.find('.chargeType').val();
            let value = row.find('.chargesVal').val();
            let chargeAmount = 0;
            if(type != ''){
                if (type == '%') {
                    chargeAmount = (parseInt($('#total_amount_span').html()) * value) / 100;
                } else {
                    chargeAmount = row.find('.chargeAmount').val();
                }
            }
            row.find('.chargeAmount').val(parseFloat(chargeAmount).toFixed(2));
            $('#finalAmount').val(0);
        }

        function chargePaymetCharge(selector, i) {
            let row = $('.'+selector+'_'+i).closest('#pay_main_div_'+i);
            if($('.'+selector+'_'+i).val()){
                row.find("input").attr("data-parsley-required", "true");
                let selected_op = $('#'+selector+'_'+i).find('option:selected');
                let type = selected_op.attr('charge-type');
                let value = selected_op.attr('data-value');
                let id = selected_op.attr('value');
                let add_sub = selected_op.attr('data-plus-minus');
                let chargeAmount = 0;
                row.find('.pay_charge_amount').attr("data-plus-minus", add_sub);
                row.find('.pay_type').val(type);
                row.find('.pay_charges_value').val(value);
                if (type == '%') {
                    row.find('.pay_charges_value').attr({
                        "max": 100,
                        "min": 0
                    });
                    row.find('.pay_charges_value').parent('div').show();
                    chargeAmount = (parseInt($('#total_amount_span').html()) * value) / 100;
                } else {
                    row.find('.pay_charges_value').removeAttr('max');
                    row.find('.pay_charges_value').removeAttr('min');
                    row.find('.pay_charges_value').parent('div').hide();
                    chargeAmount = value;
                }
                row.find('.pay_charge_amount').val(chargeAmount);
                $("#calculateAmount").trigger('click');
            }else{
                row.find('.pay_charges_value').parent('div').show();
                row.find('input').val('');
                row.find('.pay_charge_amount').parent('div').show();
                row.find(".parsley-errors-list").find("li").text('');
                row.find("input").attr("data-parsley-required", "false");
            }
        }

        function platformChargeAmountCal() {
            $($("#payment-main-div .pay_main_div")).each(function( index ) {
                chargePaymetCharge('pay_charges', index);
                $('select#pay_charges_'+index).attr('disabled',true);
                let row = $('.pay_charges_'+index).closest('#pay_main_div_'+index);
                if($('.pay_charges_'+index).val()){
                    row.find("input").attr("data-parsley-required", "true");
                    let selected_op = $('#pay_charges_'+index).find('option:selected');
                    let type = selected_op.attr('charge-type');
                    let value = selected_op.attr('data-value');
                    let id = selected_op.attr('value');
                    let add_sub = selected_op.attr('data-plus-minus');
                    let chargeAmount = 0;
                    row.find('.pay_charge_amount').attr("data-plus-minus", add_sub);
                    row.find('.pay_type').val(type);
                    row.find('.pay_charges_value').val(value);
                    if (type == '%') {
                        row.find('.pay_charges_value').attr({
                            "max": 100,
                            "min": 0
                        });
                        row.find('.pay_charges_value').parent('div').show();
                        chargeAmount = (parseInt($('#total_amount_span').html()) * value) / 100;
                    } else {
                        row.find('.pay_charges_value').removeAttr('max');
                        row.find('.pay_charges_value').removeAttr('min');
                        row.find('.pay_charges_value').parent('div').hide();
                        chargeAmount = value;
                    }
                    row.find('.pay_charge_amount').val(chargeAmount);
                    $("#calculateAmount").trigger('click');
                }
            });
        }

        function setPlatformChargesVal(selector){
            let row = selector.closest('.row.pay_main_div');
            let type = row.find('.pay_type').val();
            let value = row.find('.pay_charges_value').val();
            let chargeAmount = 0;

            if (type == '%') {
                chargeAmount = ($('#amount').val() * value) / 100;
            } else {
                chargeAmount = row.find('.pay_charge_amount').val();
            }
            row.find('.pay_charge_amount').val(parseFloat(chargeAmount).toFixed(2));
            $('#finalAmount').val(0);
        }

        //Change weight as per the group rfq quantity
        function changeWeightGroup(val, qty){
            if(val != '') {
                var calculate_weight = 0;
                if(isNaN(parseFloat(val*qty))) {
                    calculate_weight = 0;
                } else {
                    calculate_weight = parseFloat(val*qty).toFixed(2);
                }
                $('#change_weight').html(calculate_weight);
            } else {
                $('#change_weight').html(0);
            }
        }
        /*****begin: RFQ Reply Pickup Detail******/
        var SnippetEditPickupDetail = function(){
            var selectCountryGetState = function() {
                $("#countryId").on('change', function() {
                    $("#stateId").empty();
                    $("#cityId").empty();

                    let country = $(this).val();
                    let targetUrl = "{{ route('admin.state.by.country', ":id") }}";
                    targetUrl = targetUrl.replace(':id', country);
                    var newOptions = '';

                    if (country != '') {
                        $.ajax({
                            url: targetUrl,
                            type: 'POST',
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            success: function(response) {
                                if (response.success) {
                                    $("#stateId").empty();
                                    $("#cityId").empty();

                                    newOption = new Option('{{ __('admin.select_province') }}', '', true, true);
                                    $('#stateId').append(newOption);

                                    for (let i = 0; i < response.data.length; i++) {
                                        newOption = new Option(response.data[i].name, response.data[i].id, true, true);
                                        $('#stateId').append(newOption);
                                    }

                                    /*******begin:Add and remove last null option for no conflict*******/
                                    newOption = new Option('0', '0', true, true);
                                    $('#stateId').append(newOption);
                                    $('#stateId').each(function () {
                                        $(this).find("option:last").remove();
                                    });
                                    /*******end:Add and remove last null option for no conflict*******/

                                    newOption = new Option('Other', '-1', true, true);
                                    $('#stateId').append(newOption);

                                    let selectedAddressState = $('#supplier_address_id option:selected').attr('data-state-id');
                                    if (selectedAddressState != null && selectedAddressState != '') {
                                        $('#stateId').val(selectedAddressState).trigger('change');
                                    } else {
                                        $('#stateId').val(null).trigger('change');
                                    }
                                }
                            }
                        });
                    }
                });
            },

            selectStateGetCity = function(){
                $('#stateId').on('change',function(){
                    let state = $(this).val();
                    let targetUrl = "{{ route('admin.city.by.state',":id") }}";
                    targetUrl = targetUrl.replace(':id', state);
                    var newOption = '';

                    // Add Remove Other State filed
                    if (state == -1) {
                        $('#stateId_block').removeClass('col-md-6');
                        $('#stateId_block').addClass('col-md-3');

                        $('#cityId_block').removeClass('col-md-6');
                        $('#cityId_block').addClass('col-md-3');

                        $('#state_block').removeClass('hide');
                        $('#provinces').attr('required','required');

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
                        $('#provinces').removeAttr('required','required');

                        $('#cityId_block').removeClass('col-md-3');
                        $('#cityId_block').addClass('col-md-6');

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

                    } else {

                        $('#cityId_block').removeClass('col-md-3');
                        $('#cityId_block').addClass('col-md-6');


                        $('#city_block').addClass('hide');
                        $('#city').removeAttr('required','required');

                    }

                });

            },

            initiateCountryState = function() {
                $("#supplier").select2();
                $("#supplier_address_id").select2();

                let country = $("#countryId").val();
                if (country != null && country != '') {
                    setTimeout(() => {
                        $('#countryId').val(country).trigger('change');
                    }, 0000);
                }
            },

            initiateCityState = function(){
                $('#supplier').select2();
                $('#supplier_address_id').select2();

                let state               =   $('#provinces').val();
                let selectedState       =   $('#stateId').val();

                if (state != null && state !='') {
                    $('#stateId').val('-1').trigger('change');
                }

                if (selectedState !='' && selectedState != null) {
                    $('#stateId').val(selectedState).trigger('change');
                }
            },
                
            //close product tagging modal and uncheck selected product checkbox
            closeProductTaggingModal = function() {
                $(document).on('click', '#close_product_tag_btn, #dismiss_product_tag_btn', function() {
                    var rfqProductKey = $("#rfq_product_key").val();
                    $("#checkrfq_"+rfqProductKey).prop('checked', false);
                    $("#productTagModal").modal('hide');
                    $("#checkrfq_"+rfqProductKey).addClass('blink_me');
                });
            },

            disbussProductTaggingModal = function() {
                var rfqProductKey = $("#rfq_product_key").val();
                $("#checkrfq_"+rfqProductKey).prop('checked', false);
                $("#productTagModal").modal('hide');
            },

            //On click of "tag_supplier_prod_btn", save product details in "supplier_product" data
            tagSupplierProduct = function() {
                $(document).on('click', '#tag_supplier_prod_btn', function() {
                    //Disabled button after click it
                    $(this).prop('disabled', true);
                    var rfqProdData = $.parseJSON($("#rfq_product_data").val());
                    var key = $("#rfq_product_key").val();
                    var supplier_id = $('#supplier').val();

                    $.ajax({
                        url: "{{ route('supplier-prod-tagging-ajax') }}",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {rfqProdData:rfqProdData, supplier_id:supplier_id},
                        type: 'POST',
                        success: function (response) { console.log(response.success + " - " +response.class);
                            if(response.success == true & response.class == 'success') {
                                $("#collapse_"+key).toggleClass('active');
                                removeDisabled(false, key);
                                addRequired(true, key);
                                $("#productTagModal").modal('hide');
                                new PNotify({
                                    text: response.message,
                                    type: 'success',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 2000
                                });
                                $(this).prop('disabled', false);
                                $('#collapse_'+(key-1)).collapse('show');
                            } else {
                                $(rfqProdData).prop("checked", false);
                                $("#productTagModal").modal('hide');
                                new PNotify({
                                    text: response.message,
                                    type: 'error',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 2000
                                });
                            }
                        },
                    });

                });
            }

            return {
                init:function(){
                    selectCountryGetState(),
                    selectStateGetCity(),
                    selectCitySetOtherCity(),
                    initiateCityState(),
                    initiateCountryState(),
                    closeProductTaggingModal(),
                    tagSupplierProduct()
                },

                formClear:function(){
                    $('#address_name').val('');
                    $('#address_line_1').val('');
                    $('#address_line_2').val('');
                    $('#sub_district').val('');
                    $('#district').val('');
                    $('#state').val('');
                    $('#city').val('');
                    $("#countryId").val("").trigger('change');
                    $('#stateId').val('').trigger('change');
                    $('#cityId').val('').trigger('change');
                    $('#pincode').val('');
                }
            }

        }(1);
        /*****end: RFQ Reply Pickup Detail******/

         /***
         * Select Pickup fleet based on pickup delivery(Vrutika - 26/09/2022)
        ***/
         $(document).on('change','#pickup_service',function() {
             $('#pickup_fleet').val('');
             $('#logistics_services').val('');
             let selected_option = $('option:selected', this);
             let pickupService = selected_option.val();
             if(pickupService=="Express"){
                 $('.Truck').attr('disabled',true);
                  $('.Motorcycle').attr('disabled',false);
                 $('.Car').attr('disabled',false);
                 $('.logistics_services').attr('disabled',false);
             }else if(pickupService=="Trucking"){
                 $('.Truck').attr('disabled',false);
                 $('.Car').attr('disabled',true);
                 $('.Motorcycle').attr('disabled',true);
                 $('.logistics_services').attr('disabled',true);
                 $('#JTR18').removeAttr('disabled');
             }else{
                 $('.Car').attr('disabled',false);
                 $('.Motorcycle').attr('disabled',false);
                 $('.Truck').attr('disabled',false);
                 $('.logistics_services').attr('disabled',false);
             }
        });
       /***
         * Logistics check onchange function (Ronak M - 10/09/2022)
        ***/
       function ChangeLogisticCheck() {
            let authUserRoleId = $('#authUserRoleId').val();
            let logisticsCheck = $('#logistic_check').is(':checked') ? 1 : 0; //1 => Checked , 0 => Unchecked
            if(authUserRoleId == 3) {
                showHideLogisticService(logisticsCheck);
            } else {
                $("#logistics_services_div").removeClass('d-none');
                $('#pickup_service_div').removeClass('d-none');
                $('#pickup_fleet_div').removeClass('d-none');
                $('#wood_packing_div').removeClass('d-none');
                $('#wood_packing_div').addClass('d-flex');
                showHideLogisticService(logisticsCheck);
            }
       }
       /***
         * Logistics check onchange function (Vrutika - 14/12/2022)
        ***/
       function showHideLogisticService(logisticsCheck){
            if(logisticsCheck == 1) {
                $('#logistics_services').attr('required', false);
                $('#pickup_service').attr('required', false);
                $('#pickup_fleet').attr('required', false);
                $("#logistics_services_div").addClass('d-none');
                $('#pickup_service_div').addClass('d-none');
                $('#pickup_fleet_div').addClass('d-none');
                $('#wood_packing_div').addClass('d-none');
                $('#wood_packing_div').removeClass('d-flex');
                $('#logistic-charges').find('input, textarea, button, select').attr('disabled', 'disabled');
                $('#logistic-charges').find('input, textarea, button, select').val('');
                $('#logistic-charges').find('input, textarea, select').removeAttr("data-parsley-required");
                $("#logistics_services_div").find('input, select').val('');
                $('#pickup_service_div').find('input, select').val('');
                $('#pickup_fleet_div').find('input, select').val('');
                $('#wood_packing').prop('checked', false);
                $('#container1').html('');
                $('#btnLogClone').addClass('d-none');
            } else {
                if($('#authUserRoleId').val() != 3) {
                    $('#logistics_services').attr('required', true);
                    $('#pickup_service').attr('required', true);
                    $('#pickup_fleet').attr('required', true);
                }
                $("#logistics_services_div").removeClass('d-none');
                $('#pickup_service_div').removeClass('d-none');
                $('#pickup_fleet_div').removeClass('d-none');
                $('#logistic-charges').find('input, button, select').removeAttr('disabled');
                $('#btnLogClone').removeClass('d-none');
                $('#wood_packing_div').addClass('d-flex');
                $('#wood_packing').prop('checked', false);
            }
       }
       function removeLogisticCharges(){
           var existingLogisticCharge =$('#logCharge_amount').val();
           if(existingLogisticCharge && existingLogisticCharge != "" && existingLogisticCharge != "0.00"){
                swal({
                    title: '{{ __('profile.are_you_sure') }}',
                    text: '{{ __('admin.logistic_charges_will_deleted') }}',
                    icon: "/assets/images/bin.png",
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.yes') }}'],
                    dangerMode: true,
                }).then((changeIt) => {
                    if (changeIt) {
                        ChangeLogisticCheck()
                    }else{
                        $('#logistic_check').prop('checked',false);
                    }
                });
            }else{
               ChangeLogisticCheck();
           }
       }
        $(document).on('click', '.removeSelectedFile', function (e) {
                e.preventDefault();
                var element = $(this);
                var dataName = $(this).attr("data-name");

                swal({
                    title: "Are you sure?",
                    icon: "warning",
                    buttons: ["Cancel", "Ok"],
                    dangerMode: false,
                }).then((changeit) => {
                    if (changeit) {
                        $('#file-'+dataName).html('');
                        $('#'+dataName).val('');
                    }
                });
        });
    </script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
    </script>
@stop
