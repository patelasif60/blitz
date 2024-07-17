@extends('admin/adminLayout')

@push('bottom_head')
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <style>
        .date .parsley-errors-list {
            position: absolute;
            bottom: -40px;
        }

        select.form-control.selectBox.pay_charges[disabled] {
            background-color: #e9ecef !important;
        }
        .accordion-button{font-size: 13px !important;}

        .accordion-button:not(.collapsed) {color: black;background-color: #efefef;}

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
    </style>
@endpush
@section('content')
    <input type="hidden" value="{!! csrf_token() !!}" name="_token">
    <input type="hidden" value="{{ $quotes->id }}" name="id">
    {{-- Added by ekta --}}
    <input type="hidden" id="target_quantity" value="{{$quotes->target_quantity ?? null}}">
    <input type="hidden" id="achieved_quantity" value="{{$quotes->achieved_quantity ?? null }}">
    <input type="hidden" id="unit_name" name="unit_name" value="{{$quotes->unit_name ?? null}}">
    {{-- ended by ekta --}}
    <div class="row">
        <div class="col-md-12 d-flex align-items-center mb-3">
            <h1 class="mb-0">{{ $quotes->quote_number }}</h1>
            @if(isset($quotes->group_id) && !empty($quotes->group_id))
                <span>
                    <button type="button" class="btn btn-info btn-sm ms-2 text-white" style="border-radius: 20px">BGRP-{{ $quotes->group_id }}</button>
                </span>
            @endif
            <a href="{{ route('quotes-list') }}" class="ms-auto">
                <button type="button" class="btn-close ms-auto"></button>
            </a>
        </div>

        <input type="hidden" name="authUserRoleId" id="authUserRoleId" value="{{ auth()->user()->role_id }}" />

        <div class="col-md-12">
            <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">{{ __('admin.quote_edit') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-0 ms-5" id="profile-tab" data-quoteid="{{$quotes->id}}" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false"> {{ __('admin.activities') }}
                    </button>
                </li>
            </ul>
            <div class="tab-content pt-3 pb-1" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row align-items-stretch">
                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0">
                                        <img src="{{URL::asset('assets/icons/comment-alt-edit.png')}}" alt="RFQ Detail " class="pe-2"> {{ __('admin.rfq_detail') }}
                                    </h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <div class="row rfqform_view bg-white">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">{{ __('admin.rfq_number') }}</label>
                                            <div>{{ $quotes->reference_number }}</div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">{{ __('admin.rfq_date') }}</label>
                                            <div class="text-dark">{{ date('d-m-Y H:i:s', strtotime($quotes->rfqs_date)) }}</div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">{{ __('admin.status') }}</label>
                                            <div>{{ $quotes->status_name }}</div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">{{ __('admin.payment_term') }}</label>
                                            @if($quotes->payment_type==1)
                                                <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.credit') }} -{{ $quotes->credit_days}}</span></div>
                                            @elseif( $quotes->payment_type==0)
                                                <div class="text-dark"><span class="badge rounded-pill bg-success">{{ __('admin.advance') }}</span></div>
                                            @elseif( $quotes->payment_type==3)
                                                <div class="text-dark"><span class="badge rounded-pill bg-danger">{{  __('admin.lc')  }}</span></div>
                                            @elseif( $quotes->payment_type==4)
                                                <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.skbdn') }}</span></div>
                                            @else
                                                <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.credit') }}</span></div>
                                            @endif
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">{{ __('admin.company_of_buyer') }}</label>
                                            <div class="text-dark">{{ $quotes->company_name }}</div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">{{ __('admin.customer_name') }}</label>
                                            <div class="text-dark"> {{ $quotes->firstname }} {{ $quotes->lastname }}</div>
                                        </div>
                                        @if(auth()->user()->role_id != 3)
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">{{ __('admin.customer_email') }}</label>
                                            <div class="text-dark">{{ $quotes->email }}</div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">{{ __('admin.customer_phone') }}</label>
                                            <div class="text-dark"> {{ countryCodeFormat($quotes->user_phone_code, $quotes->mobile) }}</div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/truck.png')}}" alt="Delivery Detail " class="pe-2"> @if(in_array($quotes->category_id,\App\Models\Category::SERVICES_CATEGORY_IDS)) {{ __('dashboard.pickup_details') }} @else {{ __('admin.delivery_detail') }} @endif </h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <div class="row rfqform_view bg-white">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('rfqs.address') }}:</label>
                                            <div class="text-dark">{{$quotes->rfq_address_line_1 ?($quotes->rfq_address_line_1.','):'-'}} {{$quotes->rfq_address_line_2 ? $quotes->rfq_address_line_2:''}}</div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('admin.sub_district') }}:</label>
                                            <div class="text-dark">{{$quotes->rfq_sub_district ? $quotes->rfq_sub_district:'-'}}</div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('admin.district') }}:</label>
                                            <div class="text-dark">{{$quotes->rfq_district ? $quotes->rfq_district:'-'}}</div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('admin.city') }}:</label>
                                            <div class="text-dark">{{$quotes->rfq_city_id > 0 ? getCityName($quotes->rfq_city_id) : ($quotes->rfq_city ?? '-') }}</div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('admin.provinces') }}:</label>
                                            <div class="text-dark">{{$quotes->rfq_state_id > 0 ? getStateName($quotes->rfq_state_id) :  ($quotes->rfq_state ?? '-')}}</div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('admin.pin_code') }}</label>
                                            <div class="text-dark"> {{ $quotes->pincode }}</div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('admin.expected_delivery_date') }}</label>
                                            <div class="text-dark">{{ changeDateFormat($quotes->expected_date) }}</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ __('admin.other_option') }}</label>
                                            <div class="text-dark ps-4">
                                                <div class="form-check form-check-inline my-0">
                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1" disabled="" {{ $quotes->unloading_services ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="inlineCheckbox1" readonly="">{{ __('admin.need_uploding_services') }}</label>
                                                </div>
                                                <div class="form-check form-check-inline my-0">
                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2" {{ $quotes->rental_forklift ? 'checked' : '' }} disabled="">
                                                    <label class="form-check-label" for="inlineCheckbox2">{{ __('admin.need_rental_forklift') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Detail -->
                        <form id="quoteReply" class="" method="POST" action="{{ route('quotes-update') }}" enctype="multipart/form-data" data-parsley-validate>
                            @csrf
                            <input type="hidden" id="quote_id" name="quote_id" value="{{ $quotes->id }}" />
                            <input type="hidden" name="amount" id="amount" value="">
                            <input type="hidden" id="groupId" name="groupId" value="{{$quotes->group_id ?? null}}">
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
                                                    <select class="form-select border-0 ps-0 selectBox w-auto text-primary" name="supplier" id="supplier" data-parsley-errors-container="#supplier_error" data-parsley-allselected="true" style="border: 0 !important;" required {{ auth()->user()->role_id == 3? 'disabled': '' }} onchange="SupplierChange(this.value, {{$quotes->rfqId}})">

                                                        <option disabled selected>{{ __('admin.select_supplier') }}</option>
                                                        @foreach ($suppliers->unique('supplierName') as $supplier)
                                                            @if($quotes->status_id == 2)
                                                                @if($supplier->supplierId != $quotes->supplier_id)
                                                                    @continue
                                                                @endif
                                                                <option value="{{ $supplier->supplierId }}" selected>{{ $supplier->supplierName }}
                                                                </option>
                                                            @else
                                                                <option value="{{ $supplier->supplierId }}" {{ $supplier->supplierId == $quotes->supplier_id ? "selected" : '' }} {{ auth()->user()->role_id == 3 && $supplier->supplierId != $quotes->supplier_id  ? 'disabled': '' }}>{{ $supplier->supplierName }}
                                                                </option>
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
                                <div class="card">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/boxes.png')}}" alt="Product Detail" class="pe-2"> {{ __('admin.product_detail') }}</h5>
                                        <h5 class="mb-0 ms-auto px-3 d-none">{{ __('admin.total_product') }}: 3</h5>
                                        <h5 class="mb-0">{{ __('admin.total_amount') }}: <span class="text-primary">Rp</span> <span class="text-primary" id="total_amount_span">0.00</span></h5>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row rfqform_view bg-white" id="rfq_product_details_view">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 pb-2">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center">
                                        <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/icon_pickup.png')}}" alt="Charges" class="pe-2"> <span>@if(in_array($quotes->category_id,\App\Models\Category::SERVICES_CATEGORY_IDS)) {{ __('rfqs.delivery_address') }} @else {{ __('admin.pickup_address') }} @endif</span></h5>
                                    </div>
                                    <div class="card-body p-3 pb-1">
                                        <div class="row ">
                                            <div class="col-md-6 mb-3 address_block">
                                                <label class="form-label">{{ __('rfqs.select_address') }} <span class="text-danger">*</span></label>
                                                <select class="form-select" id="supplier_address_id" onchange="changeDetailsSupplier(this)" name="supplier_address_id" required data-parsley-errors-container="#supplier_address_id_error">
                                                    <option disabled>{{ __('rfqs.select_address') }}</option>
                                                    @if(isset($supplierAddresses) && !empty($supplierAddresses))
                                                        @foreach ($supplierAddresses as $item)
                                                            <option data-address_name="{{$item->address_name}}" data-address_line_2="{{$item->address_line_2}}" data-address_line_1="{{$item->address_line_1}}" data-sub_district="{{$item->sub_district}}" data-district="{{$item->district}}" data-city="{{$item->city}}" data-state="{{$item->state}}" data-country-id="{{$item->country_id}}" data-state-id="{{$item->state_id ?? \App\Models\UserAddresse::OtherState}}" data-city-id="{{$item->city_id ?? \App\Models\UserAddresse::OtherCity}}" data-pincode="{{$item->pincode}}" value="{{ $item->id }}" {{ $item->address_name == $quotes->adderss_name? 'selected' :'' }}>{{ $item->address_name }}</option>
                                                        @endforeach
                                                        <option data-address-id="0" value="0">Other</option>
                                                    @endif
                                                </select>
                                                <div id="supplier_address_id_error"></div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="address_name" class="form-label">{{ __('rfqs.address_name') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="address_name" id="address_name" class="form-control" value="{{ $quotes->address_name }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="address_line_1" class="form-label">{{ __('admin.address_line') }} 1<span class="text-danger">*</span></label>
                                                <input type="text" name="address_line_1" id="address_line_1" value="{{ $quotes->address_line_1 }}" class="form-control" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="address_line_2" class="form-label">{{ __('admin.address_line') }} 2<span style="color:red;"></span></label>
                                                <input type="text" name="address_line_2" id="address_line_2" value="{{ $quotes->address_line_2 }}" class="form-control">
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label for="Sub district" class="form-label">{{ __('admin.sub_district') }}<span class="text-danger">*</span></label>
                                                <input type="text" id="sub_district" name="sub_district" value="{{ $quotes->sub_district }}" class="form-control" required>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="district" class="form-label">{{ __('admin.district') }}<span class="text-danger">*</span></label>
                                                <input type="text" name="district" id="district" value="{{ $quotes->district }}" class="form-control" required>
                                            </div>

                                            <div class="col-md-6 mb-3 select2-block" id="countryId_block">
                                                <label for="countryId" class="form-label">{{ __('admin.country') }}<span class="text-danger">*</span></label>
                                                <select class="form-select select2-custom" id="countryId" name="countryId" data-placeholder="{{ __('admin.select_country') }}" required data-parsley-errors-container="#user_country">
                                                    <option value="" >{{ __('admin.select_country') }}</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}" @if($quotes->country_id == $country->id) selected @endif >{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div id="user_country"></div>
                                            </div>

                                            <div class="col-md-6 mb-3 select2-block" id="stateId_block">
                                                <label for="stateId" class="form-label">{{ __('admin.provinces') }}<span class="text-danger">*</span></label>
                                                <select class="form-select select2-custom" id="stateId" name="stateId" data-placeholder="{{ __('admin.select_province') }}" required data-parsley-errors-container="#user_provinces">
                                                    <option value="" >{{ __('admin.select_province') }}</option>
                                                    <option value="-1">Other</option>
                                                </select>
                                                <div id="user_provinces"></div>
                                            </div>

                                            <div class="col-md-3 mb-3 hide" id="state_block">
                                                <label for="provinces" class="form-label">{{ __('admin.other_provinces') }}<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="provinces" id="provinces" value="{{ $quotes->provinces }}" >
                                            </div>

                                            <div class="col-md-6 mb-3 select2-block" id="cityId_block">
                                                <label for="cityId" class="form-label">{{ __('admin.city') }}<span class="text-danger">*</span></label>
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

                                            <div class="col-md-6 mb-3">
                                                <label for="pincode" class="form-label">{{ __('admin.pin_code') }}<span style="color:red;">*</span></label>
                                                <input type="text" pattern=".{5,7}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\.*?)\..*/g, '$1');" class="form-control" id="pincode" name="pincode" value="{{ $quotes->pincode }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Group Discount Charges Ekta Patel 02-06-2022 -->
                            @if($quoteGroupDiscountCharges)
                                <div class="col-md-12 pb-2" data-id="group-discount-charges">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center">
                                            <h5 class="mb-0"><img height="20px" src="{{ URL::asset('assets/icons/group_details.png') }}" alt="Payment Fees" class="pe-2"> Group Discount</h5>
                                        </div>
                                        <div class="card-body p-3 pb-1">
                                            <p id="no_pay_charge" style="display: none">{{ __('admin.no_charges_added') }}</p>
                                            <div class="row pay_main_div">
                                                <div class="col-md-3">
                                                    <label for="pay_charges" class="form-label">{{ __('admin.charges') }}<span class="text-danger">*</span></label>
                                                    <input type="hidden" name="charges[]" class="form-control pay_charges" value="{{ $quoteGroupDiscountCharges[0]['charge_id'] }}">
                                                    <input type="text" id="group_discount" class="form-control" value="{{ $quoteGroupDiscountCharges[0]['charge_name'] }}" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="pay_type" class="form-label">{{ __('admin.type') }}</label>
                                                    <input type="text" name="chargeType[]" class="form-control pay_type" value="{{ $quoteGroupDiscountCharges[0]['type'] == 0 ? '%' : 'RP (Flat)' }}" readonly>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label for="pay_charges_value" class="form-label">{{ __('admin.charges_value') }}</label>
                                                    <input type="text" name="chargeValue[]" class="form-control pay_charges_value" value="{{ $quoteGroupDiscountCharges[0]['charge_value'] }}" placeholder="Charges Value" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="pay_charge_amount" class="form-label">{{ __('admin.charges_amount') }} (RP)</label>
                                                        <input type="text" name="charge_amount[]" class="form-control pay_charge_amount"  placeholder="Charges Amount" value="{{ $quoteGroupDiscountCharges[0]['charge_amount'] }}" data-plus-minus="{{ !empty($quotePlatformCharges[0]) ? $quotePlatformCharges[0]['addition_substraction'] : 0}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <!-- End -->
                            <div class="col-md-12 pb-2" data-id="supplier-other-charges">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center">
                                        <h5 class="mb-0 d-flex w-100 align-items-center"><img height="20px" src="{{URL::asset('assets/icons/platform_charges.png')}}" alt="{{ __('admin.supplier_other_charges') }} " class="pe-2"> {{ __('admin.supplier_other_charges') }}
                                            <span class="icon ms-1"><a href="javascript:void(0)" id="btnClone"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg></a></span>

                                            <span class="ms-auto"><input class="form-check-input" type="checkbox" id="logistic_check" name="logistic_check" onclick="removeLogisticCharges()" value="{{$quotes->logistic_provided??0}}" {{ $quotes->logistic_provided==1 ? 'checked' : '' }} style="margin-top: 0"> {{ __('admin.logistic_charges_included') }}</span> <span class="pe-2 ms-2" data-toggle="tooltip" data-placement="top" title="" data-bs-original-title="{{ __('admin.logistic_charges_message') }}" aria-label="{{ __('admin.logistic_charges_message') }}"><i class="fa fa-info-circle"></i></span>
                                            <span class="ms-3"><input class="form-check-input" type="checkbox" id="inclusive_tax_other" name="inclusive_tax_other" value="1" {{ $quotes->inclusive_tax_other==1 ? 'checked' : '' }} style="margin-top: 0"> {{ __('admin.inclusive_tax') }}</span>
                                            <span class="pe-2 ms-2" data-toggle="tooltip" data-placement="top" title="" data-bs-original-title="This charges is inclusive tax." aria-label="{{ __('admin.logistic_charges_message') }}"><i class="fa fa-info-circle"></i></span>
                                        </h5>
                                    </div>
                                    <div class="card-body p-3 pb-1">
                                        <div id="chargeMainDiv">
                                            <div class="row chargeDiv" id="chargeDiv">
                                                <div class="col-md-2 mb-3">
                                                    <label for="customChargeName" class="form-label">{{ __('admin.custom_charge_name') }}</label>
                                                    <input type="text" name="custom_charge_name[]" id="customChargeName" class="form-control customChargeName" value="{{ !empty($quotePlatformCharges) && $quotePlatformCharges[0]['custom_charge_name']? $quotePlatformCharges[0]['custom_charge_name'] : '' }}">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="charges" class="form-label">{{ __('admin.charges') }}<span class="text-danger">*</span></label>
                                                    <select name="charges[]" id="charges" class="form-select selectBox charges">
                                                        <option value="">{{ __('admin.select_charge') }}</option>
                                                        @foreach ($platformCharges as $charge)
                                                            <option data-plus-minus="{{ $charge->addition_substraction }}"
                                                                    charge-type="{{ $charge->type == 0 ? '%' : 'RP (Flat)' }}"
                                                                    data-value-on="{{ $charge->value_on == 0 ? 'Amount' : 'Quantity' }}"
                                                                    data-value="{{ $charge->charges_value }}"
                                                                    value="{{ $charge->id }}" {{ !empty($quotePlatformCharges) && $charge->id == $quotePlatformCharges[0]['charge_id'] ? "selected" : '' }}>{{ $charge->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label for="chargeType" class="form-label">{{ __('admin.type') }}</label>
                                                    <input type="text" name="chargeType[]" id="chargeType" value="{{ !empty($quotePlatformCharges) && $quotePlatformCharges[0]['type'] == 0 ? '%' : 'RP (Flat)' }}" class="form-control chargeType" readonly>
                                                </div>

                                                <div class="col-md-2  mb-3" style="{{ !empty($quotePlatformCharges) && $quotePlatformCharges[0]['type'] != 0 ? "display: none;" : '' }}">
                                                    <label for="amount" class="form-label">{{ __('admin.charges_value') }}<span class="text-danger">*</span></label>
                                                    <input type="text" name="chargeValue[]" id="chargeValue" class="form-control chargesVal" value="{{ !empty($quotePlatformCharges) && $quotePlatformCharges[0]['charge_value']? $quotePlatformCharges[0]['charge_value'] : 0 }}" onkeypress="return isNumberKey(this, event);" placeholder="Charges Value"><span class="invalid-feedback d-block"></span>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <div class="">
                                                        <label for="amount" class="form-label">{{ __('admin.charges_amount') }} (RP)<span class="text-danger">*</span></label>
                                                        <input type="text" data-parsley-myvalidator="" ref-name="charge_amount[]" name="charge_amount[]" id="charge_amount" class="form-control chargeAmount" value="{{ !empty($quotePlatformCharges) ? number_format($quotePlatformCharges[0]['charge_amount'], 2, '.', '') : 0 }}" placeholder="Charges Amount" onkeypress="return isNumberKey(this, event);" data-plus-minus="{{ !empty($quotePlatformCharges[0]) ? $quotePlatformCharges[0]['addition_substraction'] : 0}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-1 pt-1  mb-3">
                                                    <label for="" class="form-label" ></label>
                                                    <div style="line-height: 38px;" >
                                                        <span class="icon deleteCharge"><a href="javascript:void(0)" id="" class="text-danger removeCharge"><i class="fa fa-trash"></i></a></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="container">
                                            @if($quotePlatformCharges)
                                                @foreach($quotePlatformCharges as $key => $value)
                                                    @if($key==0) @continue @endif
                                                    <div class="row chargeDiv" id="chargeDiv">
                                                        <div class="col-md-2 mb-3">
                                                            <label for="customChargeName" class="form-label">{{ __('admin.custom_charge_name') }}</label>
                                                            <input type="text" name="custom_charge_name[]" id="customChargeName{{$key}}" class="form-control customChargeName" value="{{ $value['custom_charge_name'] }}">
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label for="charges" class="form-label">{{ __('admin.charges') }}<span class="text-danger">*</span></label>
                                                            <select name="charges[]" id="charges{{$key}}" class="form-control selectBox charges">
                                                                <option value="">{{ __('admin.select_charge') }}</option>

                                                                @foreach ($platformCharges as $charge)
                                                                    <option
                                                                        data-plus-minus="{{ $charge->addition_substraction }}"
                                                                        charge-type="{{ $charge->type == 0 ? '%' : 'RP (Flat)' }}"
                                                                        data-value-on="{{ $charge->value_on == 0 ? 'Amount' : 'Quantity' }}"
                                                                        data-value="{{ $charge->charges_value }}"
                                                                        value="{{ $charge->id }}" {{ $charge->id == $value['charge_id'] ? "selected" : '' }}>{{ $charge->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="fa fa-chevron-down"></i>
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <label for="chargeType" class="form-label" >{{ __('admin.type') }}</label>
                                                            <input type="text" name="chargeType[]" id="chargeType{{$key}}" value="{{ $value['type'] == 0 ? '%' : 'RP (Flat)' }}" class="form-control chargeType" readonly>
                                                        </div>
                                                        <div class="col-md-2  mb-3" style="{{  $value['type'] == 1 ? "display: none;" : '' }}">
                                                            <label for="chargeValue" class="form-label" >{{ __('admin.charges_value') }}</label>
                                                            <input type="text" name="chargeValue[]" id="chargeValue{{$key}}" value="{{ $value['charge_value'] }}" class="form-control chargesVal" onkeypress="return isNumberKey(this, event);" placeholder="Charges Value"><span class="invalid-feedback d-block"></span>
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <div class="">
                                                                <label for="amount" class="form-label" >{{ __('admin.charges_amount') }} (RP)*</label>
                                                                <input type="text" data-parsley-myvalidator="" name="charge_amount[]" id="charge_amount{{$key}}" class="form-control chargeAmount" placeholder="Charges Amount" value="{{ number_format($value['charge_amount'], 2, '.', '') }}" onkeypress="return isNumberKey(this, event);" data-plus-minus="{{ $value['addition_substraction'] }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 pt-1 mb-3">
                                                            <label for="" class="form-label"></label>
                                                            <div style="line-height: 38px;"><span class="icon deleteCharge"><a href="javascript:void(0)" id="" class="text-danger removeCharge"><i class="fa fa-trash"></i></a></span></div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(auth()->user()->role_id != 3)
                            <div class="col-md-12 pb-2" style="{{ auth()->user()->role_id == 3 ? 'display:none' : '' }}" id="logistic-charges">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center">
                                        <h5 class="mb-0 d-flex w-100 align-items-center"><img height="20px" src="{{URL::asset('assets/icons/logistic_charges.png')}}" alt="Logistic Charges " class="pe-2"> {{ __('admin.logistic_charges') }}
                                            <span class="icon ms-1"><a href="javascript:void(0)" id="btnLogClone"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg></a></span>
                                            <span class="ms-auto"><input class="form-check-input" type="checkbox" id="inclusive_tax_logistic" name="inclusive_tax_logistic" value="1" {{ $quotes->inclusive_tax_logistic==1 ? 'checked' : '' }} style="margin-top: 0"> {{ __('admin.inclusive_tax') }}</span>
                                            <span class="pe-2 ms-2" data-toggle="tooltip" data-placement="top" title="" data-bs-original-title="This charges is inclusive tax." aria-label="{{ __('admin.logistic_charges_message') }}"><i class="fa fa-info-circle"></i></span>
                                        </h5>

                                    </div>
                                    <div class="card-body p-3 pb-1">
                                        <div id="logchargeMainDiv">
                                            <div class="row chargeDiv" id="Log_chargeDiv">
                                                <div class="col-md-2 mb-3">
                                                    <label for="customChargeName" class="form-label">{{ __('admin.custom_charge_name') }}</label>
                                                    <input type="text" name="custom_charge_name[]" id="logCustomChargeName" class="form-control logCustomChargeName" value="{{ !empty($quoteLogisticCharges) && $quoteLogisticCharges[0]['custom_charge_name']? $quoteLogisticCharges[0]['custom_charge_name'] : '' }}">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="amount" class="form-label">{{ __('admin.charges') }}<span class="text-danger">*</span></label>
                                                    <select name="charges[]" id="logCharges" class="form-select selectBox logcharges">
                                                        <option value="">{{ __('admin.select_charge') }}</option>

                                                        @foreach ($logisticCharges as $charge)
                                                            <option data-plus-minus="{{ $charge->addition_substraction }}"
                                                                    charge-type="{{ $charge->type == 0 ? '%' : 'RP (Flat)' }}"
                                                                    data-value-on="{{ $charge->value_on == 0 ? 'Amount' : 'Quantity' }}"
                                                                    data-value="{{ $charge->charges_value }}"
                                                                    value="{{ $charge->id }}" {{ !empty($quoteLogisticCharges) && $charge->id == $quoteLogisticCharges[0]['charge_id'] ? "selected" : '' }}>{{ $charge->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="fa fa-chevron-down"></i>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label for="chargeType" class="form-label">{{ __('admin.type') }}</label>
                                                    <input type="text" name="chargeType[]" value="{{ !empty($quoteLogisticCharges) && $quoteLogisticCharges[0]['type'] == 0 ? '%' : 'RP (Flat)' }}" id="logChargeType" class="form-control chargeType" readonly>
                                                </div>

                                                <div class="col-md-2  mb-3" style="{{  !empty($quoteLogisticCharges)&& $quoteLogisticCharges[0]['type'] == 1 ? "display: none;" : '' }}">
                                                    <label for="amount" class="form-label">{{ __('admin.charges_value') }}<span class="text-danger">*</span></label>
                                                    <input type="text" name="chargeValue[]" id="logChargeValue" class="form-control chargesVal disVal" value="{{ !empty($quoteLogisticCharges)?number_format($quoteLogisticCharges[0]['charge_value'], 2, '.', '') : 0 }}" onkeypress="return isNumberKey(this, event);" placeholder="Charges Value">
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <div class="">
                                                        <label for="amount" class="form-label">{{ __('admin.charges_amount') }} (RP)<span class="text-danger">*</span></label>
                                                        <input type="text" ref-name="logCharge_amount[]"  name="charge_amount[]" id="logCharge_amount" value="{{ !empty($quoteLogisticCharges) ? number_format($quoteLogisticCharges[0]['charge_amount'], 2, '.', '') : 0 }}" class="form-control chargeAmount" placeholder="Charges Amount" onkeypress="return isNumberKey(this, event);" data-plus-minus="{{ !empty($quoteLogisticCharges) ?$quoteLogisticCharges[0]['addition_substraction'] : 0 }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-1 pt-1 mb-3">
                                                    <label for="" class="form-label"></label>
                                                    <div style="line-height: 38px;">
                                                    <span class="icon deleteCharge"><a
                                                            href="javascript:void(0)" id=""
                                                            class="text-danger removeCharge">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="container1">
                                            @if($quoteLogisticCharges)
                                                @foreach($quoteLogisticCharges as $key => $value)
                                                    @if($key==0) @continue @endif
                                                    <div class="row chargeDiv" id="Log_chargeDiv">
                                                        <div class="col-md-2 mb-3">
                                                            <label for="customChargeName" class="form-label">{{ __('admin.custom_charge_name') }}</label>
                                                            <input type="text" name="custom_charge_name[]" id="logCustomChargeName{{$key}}" class="form-control logCustomChargeName" value="{{ $value['custom_charge_name'] }}">
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label for="amount" class="form-label" >{{ __('admin.charges') }}<span class="text-danger">*</span></label>
                                                            <select name="charges[]" id="logCharges{{$key}}" class="form-control selectBox logCharges">
                                                                <option value="">Select charge</option>
                                                                @foreach ($logisticCharges as $charge)
                                                                    <option data-plus-minus="{{ $charge->addition_substraction }}"
                                                                            charge-type="{{ $charge->type == 0 ? '%' : 'RP (Flat)' }}"
                                                                            data-value-on="{{ $charge->value_on == 0 ? 'Amount' : 'Quantity' }}"
                                                                            data-value="{{ $charge->charges_value }}"
                                                                            value="{{ $charge->id }}" {{ $charge->id == $value['charge_id'] ? "selected" : '' }}>{{ $charge->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="fa fa-chevron-down"></i>
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <label for="chargeType" class="form-label">{{ __('admin.type') }}</label>
                                                            <input type="text" name="chargeType[]" id="logChargeType{{$key}}" value="{{ $value['charge_type'] == 0 ? '%' : 'RP (Flat)' }}" class="form-control chargeType" readonly>
                                                        </div>
                                                        <div class="col-md-2  mb-3" style="{{  $value['type'] == 1 ? "display: none;" : '' }}">
                                                            <label for="amount" class="form-label" >{{ __('admin.charges_value') }}<span class="text-danger">*</span></label>
                                                            <input type="text" name="chargeValue[]" id="logChargeValue{{$key}}" value="{{ $value['charge_value'] }}" class="form-control chargesVal disVal" onkeypress="return isNumberKey(this, event);"  placeholder="Charges Value">
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <div class="form-group">
                                                                <label for="amount" class="form-label" >{{ __('admin.charges_amount') }} (RP)<span class="text-danger">*</span></label>
                                                                <input type="text" name="charge_amount[]" id="logCharge_amount{{$key}}" value="{{ number_format($value['charge_amount'], 2, '.', '') }}" class="form-control chargeAmount"  placeholder="Charges Amount" onkeypress="return isNumberKey(this, event);" data-plus-minus="{{ $value['addition_substraction'] }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 pt-1 mb-3">
                                                            <label for="" class="form-label" ></label>
                                                            <div style="line-height: 38px;">
                                                                <span class="icon deleteCharge"><a href="javascript:void(0)" id="" class="text-danger removeCharge"><i class="fa fa-trash"></i></a></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
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
                                            @php $paymentChargesHtml = view('admin.quote.quote-edit-payment-charge',['paymentCharges'=>$paymentCharges, 'paymentFees' => $paymentFees])->render() @endphp
                                            {!! $paymentChargesHtml  !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-12 pb-2">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center">
                                        <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/other_calendar.png')}}" alt="Other Details " class="pe-2"> {{ __('admin.other_details') }} </h5>
                                    </div>
                                    <div class="card-body p-3 pb-1">
                                        <div class="row">
                                            <div class="col-md-12  mb-3">
                                                <label for="note" class="form-label">{{ __('admin.delivery_note') }}<span class="text-danger">*</span></label>
                                                <input type="text" name="note" id="note" value="{{ $quotes->note }}" class="form-control" required placeholder="Note">
                                            </div>
                                            <div class="col-md-12 pb-2 ">
                                                <label for="comment" class="form-label">{{ __('admin.comments') }}</label>
                                                <textarea name="comment" class="form-control" id="comment" cols="30" rows="3">{{ $quotes->comment }}</textarea>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="minDays" class="form-label">{{ __('admin.deliver_order_in_min_days') }}<span class="text-danger">*</span></label>
                                                <input type="text" name="minDays" id="minDays" value="{{ $quotes->min_delivery_days }}" class="form-control" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="maxDays" class="form-label">{{ __('admin.deliver_order_in_max_days') }}<span class="text-danger">*</span></label>
                                                <input type="text" name="maxDays" value="{{ $quotes->max_delivery_days }}" id="maxDays" class="form-control" required>
                                                <div id="showErr" class="mt-3"></div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="maxDays" class="form-label">{{ __('admin.valid_till') }}<span class="text-danger">*</span></label>
                                                <div id="date" class="input-group date datepicker">
                                                    <input type="text" id="valid_till" name="valid_till" value="{{ date('d-m-Y', strtotime($quotes->valid_till))}}" class="form-control" style="border: 1px solid #dee2e6;" required>
                                                    <span class="input-group-addon input-group-append border-left">
                                                    <span class="mdi mdi-calendar input-group-text" style="padding: 0.7rem 0.75rem;border: 1px solid #dee2e6;"></span>
                                                </span>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3" id="pickup_service_div">
                                                <label class="form-label">{{ __('admin.quincus_pickup_service') }}@if(!Auth::user()->hasRole('supplier'))<span class="text-danger">*</span> @endif</label>
                                                <select class="form-select" name="pickup_service" id="pickup_service">
                                                    <option disabled selected value="">{{ __('admin.select') }} {{ __('admin.quincus_pickup_service') }}</option>
                                                    <option value="Express" {{ $quotes->pickup_service == "Express" ? "selected" : '' }}>Express</option>
                                                    <option value="Trucking" {{ $quotes->pickup_service == "Trucking" ? "selected" : '' }}>Trucking</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3" id="pickup_fleet_div">
                                                <label class="form-label"> {{ __('admin.quincus_pickup_fleet') }} @if(!Auth::user()->hasRole('supplier'))<span class="text-danger">*</span> @endif</label>
                                                <select class="form-select" name="pickup_fleet" id="pickup_fleet">
                                                    <option disabled selected value="">{{ __('admin.select') }} {{ __('admin.quincus_pickup_fleet') }}</option>
                                                    <option class="Motorcycle" value="Motorcycle" {{ $quotes->pickup_service == "Trucking" ? "disabled" : '' }} {{ $quotes->pickup_fleet == "Motorcycle" ? "selected" : '' }}>Motorcycle</option>
                                                    <option class="Car" value="Car" {{ $quotes->pickup_service == "Trucking" ? "disabled" : '' }}{{ $quotes->pickup_fleet == "Car" ? "selected" : '' }}>Car</option>
                                                    <option class="Truck" value="Truck" {{ $quotes->pickup_service == "Express" ? "disabled" : '' }} {{ $quotes->pickup_fleet == "Truck" ? "selected" : '' }}>Truck</option>
                                                </select>
                                            </div>
                                            @if(isset($logistics_services) && sizeof($logistics_services) > 0)
                                            <div class="col-md-4 mb-3" id="logistics_services_div">
                                                <label class="form-label">{{ __('admin.quincus_services') }}@if(!Auth::user()->hasRole('supplier'))<span class="text-danger">*</span> @endif</label>
                                                <select class="form-select" name="logistics_service_code" id="logistics_services" data-parsley-allselected="true">
                                                    <option disabled selected value="">{{ __('admin.select_logistics_service') }}</option>
                                                    @foreach($logistics_services as $service)
                                                        <option value="{{ $service->service_code }}" {{ $service->service_code == $quotes->logistics_service_code ? "selected" : '' }} class="logistics_services" id="{{ $service->service_code }}">{{ $service->service_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endif
                                            <div class="col-md-4 mt-0 d-flex" id="wood_packing_div">
                                                <label  class="form-label">&nbsp;</label>
                                                <div class="d-flex align-items-center ps-4">
                                                    <div class="form-check pe-3 d-flex align-items-center ">
                                                        <input class="form-check-input" value="1" type="checkbox" id="insurance_flag" name="insurance_flag" checked value={{ $quotes->insurance_flag}} disabled>
                                                    <label class="ms-2">  {{ __('admin.insurance_flag') }}</label>
                                                    </div>

                                                    <div class="form-check ps-4 d-flex align-items-center ">
                                                        <input class="form-check-input" type="checkbox" id="wood_packing" {{ $quotes->wood_packing == 1 ? "checked" : '' }} name="wood_packing" value="1">
                                                    <label class="ms-2">  {{ __('admin.wood_packing') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                    <label for="" class="form-label">{{ __('admin.supplier') }} {{ __('admin.commercial_tc') }}</label>
                                                    <div class="d-flex py-2">
                                                        <span class="">
                                                            <input type="file" name="termsconditions_file" class="form-control" id="termsconditions_file" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                            <label id="upload_btn" for="termsconditions_file">{{ __('profile.browse') }}</label>
                                                        </span>
                                                        <div id="file-termsconditions_file_exist" class="d-flex align-items-center">
                                                            <input type="hidden" class="form-control" id="old_termsconditions_file" name="old_termsconditions_file" value="{{ Str::substr($quotes->termsconditions_file,17) }}">
                                                            @if(isset($quotes->termsconditions_file) && !empty($quotes->termsconditions_file))
                                                                @php
                                                                    $termsconditionsFileTitle = Str::substr($quotes->termsconditions_file,stripos($quotes->termsconditions_file, 'termsconditions_file_') + 21);
                                                                    $extension_termsconditions_file = getFileExtension($termsconditionsFileTitle);
                                                                    $termsconditions_file_filename = getFileName($termsconditionsFileTitle);
                                                                    if(strlen($termsconditions_file_filename) > 10){
                                                                        $termsconditions_file_name = substr($termsconditions_file_filename,0,10).'...'.$extension_termsconditions_file;
                                                                    } else {
                                                                        $termsconditions_file_name = $termsconditions_file_filename.$extension_termsconditions_file;
                                                                    }
                                                                @endphp
                                                                <input type="hidden" class="form-control" id="oldtermsconditions_file" name="oldtermsconditions_file" value="{{ $quotes->termsconditions_file }}">
                                                                <span class="ms-2">
                                                                    <a href="{{$quotes->termsconditions_file ? Storage::url($quotes->termsconditions_file) : 'javascript:void(0);'}}" id="TermsconditionsFileDownload" download  title="{{ $termsconditionsFileTitle }}" style="text-decoration: none;"> {{ $termsconditions_file_name }}</a>
                                                                </span>
                                                                <span class="removeQuoteFile" id="removeQuoteFile" data-id="{{ $quotes->id }}" data-quote_id="{{ $quotes->rfq_id }}" data-reference="{{$quotes->reference_number}}" data-name="termsconditions_file">
                                                                    <a href="javascript:void(0);" title="{{ __('profile.remove_file') }}"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a>
                                                                </span>
                                                                <span class="ms-2">
                                                                    <a class="termsconditions_file" href="{{$quotes->termsconditions_file ? Storage::url($quotes->termsconditions_file) : 'javascript:void(0);'}}" title="{{ __('profile.download_file') }}" download  style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a>
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div id="file-termsconditions_file" class="d-flex align-items-center"></div>
                                                    </div>
                                             </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 pb-2">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center">
                                        <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/tax.png')}}" alt="Tax " class="pe-2"> {{ __('admin.tax') }} </h5>
                                    </div>
                                    <div class="card-body p-3 pb-1">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="amount" class="form-label">{{ __('admin.tax') }} (%)<span class="text-danger">*</span></label>
                                                <input type="text" name="tax" id="tax" value="{{ $quotes->tax }}" class="form-control" onkeypress="return isNumberKey(this, event);" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="amount" class="form-label">{{ __('admin.tax_amount') }} (RP)<span class="text-danger">*</span></label>
                                                <input type="text" name="tax_amount" value="{{ number_format($quotes->tax_value, 2, '.', '') }}" id="tax_amount" class="form-control" onkeypress="return isNumberKey(this, event);">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">&nbsp;</label>
                                                <div>
                                                    <button type="button" class="btn btn-info btn-icon-text text-white py-2" id="calculateAmount">
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
                                        <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/total.png')}}" alt="Platform Charges " class="pe-2"> {{ __('admin.final_amount') }} (RP) ({{ __('admin.charge_discount_message') }}) </h5>
                                    </div>
                                    <div class="card-body p-3 pb-1">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                @php
                                                    $finalAmount = auth()->user()->role_id == 3 ? $quotes->supplier_final_amount : $quotes->final_amount;
                                                @endphp
                                                <input type="text" name="finalAmount" id="finalAmount" class="form-control form-lg bg-light" onkeypress="return isNumberKey(this, event);" value="{{ number_format($finalAmount, 2, '.', '')??'0' }}">
                                                <span id="finalAmountError" class="text-danger"></span>

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
                            <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                                <button type="button" id="submitQuote" class="btn btn-primary">{{ __('admin.submit') }}</button>
                                <a href="{{ route('quotes-list') }}"  class="btn btn-cancel ms-3">{{ __('admin.cancel') }}</a>
                            </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row">
                        <div class="col-md-12 pb-2">
                            <!-- <div class="card">
                                <div class="card-body"> -->
                                    <div class="activityopen"></div>
                                <!-- </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{--     <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>--}}
    <script src="{{ URL::asset('/assets/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
    <script type="text/javascript">

        /******begin: Initiate JQuery Modules********/
        jQuery(document).ready(function(){
            SnippetEditPickupDetail.init();
            ChangeLogisticCheck();
            SnippetQuoteEditRolePermission.init();
            SnippetQuoteEdit.init();
        });
        /******end: Initiate JQuery Modules*******/
        var checkedd = 0;
        $(document).on("click", ".removeQuoteFile", function(e) {
            e.preventDefault();
            let name = $(this).attr("data-name");
            let type = $(this).attr("data-type");
            let data = {
                fileName: name,
                id: $(this).attr("data-id"),
                _token: $('meta[name="csrf-token"]').attr("content"),
                type : type
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
                    url: "{{ route('quote-tc-file-delete-ajax') }}",
                    data: data,
                    type: "POST",
                        success: function(successData) {
                            $("#file-"+ name).html('');
                            $("#file-"+ name +"_exist").html('');
                        },
                        error: function() {
                            console.log("error");
                        },
                    });
                }
            });
        });
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
                var allowed_extensions = new Array("jpg", "png", "pdf");
                let image_path = '{{URL::asset("assets/icons/times-circle copy.png")}}';
                var file_extension = fileName.split('.').pop();
                for (var i = 0; i < allowed_extensions.length; i++) {
                    if (allowed_extensions[i] == file_extension) {
                        valid = true;
                        var tooltip = fileName;
                        if(fileName.length > 10) {
                            fileName = fileName.substring(0,10) +'....'+file_extension;
                        }
                        $('#file-' + input.id).html('');
                        $('#file-' + input.id).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.name + 'Download" title="'+tooltip+'" style="text-decoration: none">' + fileName + '</a></span><span class="removeSelectedFile" data-key="' + input.id +'" data-name="' + input.name + '"><a href="javascript:void(0)" title="{{ __('profile.remove_file') }}" style="text-decoration: none;"><img src="' + image_path + '" alt="CLose button" class="ms-2"></a></span>');
                        return;
                    }
                }
                valid = false;
                swal({
                    // title: "Rfq Update",
                    text: "{{ __('admin.upload_image_or_pdf') }}",
                    icon: "warning",
                    //buttons: true,
                    buttons: ["{{ __('admin.no') }}", "{{ __('admin.yes') }}"],
                    // dangerMode: true,
                })
            }
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
        //Attachment Document
        function showFile(input) {
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
                        let download_function = "'" + input.name + "', " + "attached_document" + + "'" + fileName + "'";
                        if(file_name_without_extension.length >= 10) {
                            fileName = file_name_without_extension.substring(0,10) +'....'+file_extension;
                        }
                        $('#file-' + input.name).html('');
                        $('#file-' + input.id + '_exist').addClass('d-none');
                        $('#file-' + input.id + '_exist').removeClass('d-flex');/* to show selected file and overwrite existing (By Vrutika 09-09-2022) */
                        $('#file-' + input.name).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.name + 'Download " style="text-decoration: none">' + fileName + '</a></span><span class="removeSelectedFile" data-key="' + input.name + '"><a href="javascript:void(0)" title="{{ __('profile.remove_file') }}" style="text-decoration: none;"><img src="' + image_path + '" alt="CLose button" class="ms-2"></a></span><span class="ms-2"><a class="' + input.name + ' downloadbtn hidden" href="javascript:void(0);" title="{{ __('profile.download_file') }}" onclick="downloadimg(' + download_function + ')" style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a></span>');
                        return;
                    }
                }
                valid = false;
                swal({
                    text: text,
                    icon: "warning",
                    buttons: ["{{ __('admin.no') }}", "{{ __('admin.yes') }}"],
                })
            }
        }
        function SupplierChange(supplier_id, rfq_id){
            $.ajax({
                url: "{{ route('supplier-xenaccount-exist','') }}/"+supplier_id,
                type: 'GET',
                success: function (successData) {
                    if (successData.success == false) {
                        swal({
                            text: successData.message,
                            icon: location.origin + "/front-assets/images/bank_not_found.png",
                            dangerMode: true,
                        })
                        $('#supplier').find('option').prop('selected', false);
                        //$('#supplier').select2();
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
            //Get supplier addresses by supplier id
            getSupplierAdressDetails(supplier_id);
        }

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

                            $('#supplier_address_id').append('<option data-address_name="'+address.address_name+'" data-address_line_2="'+address.address_line_2+'" data-address_line_1="'+address.address_line_1+'" data-sub_district="'+address.sub_district+'" data-district="'+address.district+'" data-city="'+address.city+'" data-state="'+address.state+'" data-state-id="'+address.state_id+'" data-country-id="'+address.country_id+'" data-city-id="'+address.city_id+'" data-pincode="'+address.pincode+'" value="'+address.id+'" '+selected+'>'+address.address_name+'</option>');
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

        function getProductDetails(supplier_id, rfq_id, quote_id){
            var url = "{{ route('get-rfq-product-reply', [':supplier_id', ':rfq_id', ':rfq_product']) }}";
            url = url.replace(":supplier_id", supplier_id);
            url = url.replace(":rfq_id", rfq_id);
            url = url.replace(":rfq_product", quote_id);
            console.log(url);
            $.ajax({
                url: url,
                type: 'GET',
                success: function (successData) {
                    if(successData.success == true){
                        $('#rfq_product_details_view').html('');
                        $('#rfq_product_details_view').html(successData.html);
                        totalCalulate();

                        SnippetQuoteEditRolePermission.init();

                        $('input.product-detail-check').each(function(index, element) {
                            if($(element).prop("checked") == true) {
                                removeDisabled(false, $(this).attr('data-key'));
                                addRequired(true, $(this).attr('data-key'));
                            }else{
                                remove_values($(this).attr('data-key'));
                                removeDisabled(true, $(this).attr('data-key'));
                                addRequired(false, $(this).attr('data-key'))
                            }
                        })

                    }
                },
                error: function () {
                    console.log("error");
                }
            });
        }

        function enabledDisabled(data, key){
            if ($('#supplier').val() == -1 && $(data).prop("checked") == true){
                swal({
                    icon: "/assets/images/info.png",
                    text: "{{ __('admin.alert_product_select_supplier') }}",
                    buttons: "{{ __('admin.ok') }}",
                }).then((willCheck) => {
                    $(data).prop("checked", false);
                });
            } else {
                if($(data).prop("checked") == true){
                    removeDisabled(false, key);
                    addRequired(true, key)
                } else if($(data).prop("checked") == false){

                    if ($('#weights'+key).val() != '' || $('#price'+key).val() != '' || $('#dimensions'+key).val() != '' || $('#length'+key).val() != '' || $('#width'+key).val() != '' || $('#height'+key).val() != '') {
                        swal({
                            text: "{{ __('admin.reset_clear_data_supplier_product') }}",
                            icon: "/assets/images/info.png",
                            //buttons: true,
                            buttons: ["{{ __('admin.no') }}", "{{ __('admin.yes') }}"],
                        }).then((willCheck) => {
                            if (willCheck) {
                                $(data).prop("checked", false);
                                remove_values(key);
                                removeDisabled(true, key);
                                addRequired(false, key);
                                totalCalulate();
                                calculateAmount();
                            } else {
                                $(data).prop("checked", true);
                            }
                        });
                    } else {
                        $(data).prop("checked", false);
                        remove_values(key);
                        removeDisabled(true, key);
                        addRequired(false, key)
                    }
                }
            }
        }

        function remove_values(key) {
            var values = 0;
            $('#weights'+key).val('');
            $('#change_weight_'+key).html(values)
            $('#dimensions'+key).val('');
            $('#length'+key).val('');
            $('#width'+key).val('');
            $('#height'+key).val('');
            $('#price'+key).val('');
            $('#change_amount_'+key).html(values.toFixed(2))
            $('#rfq_product_qty'+key).val('');//this fields is used when Quotation Accepted and admin want to change rfq product qty
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
            $('#rfq_product_qty'+key).prop("disabled", val);//this fields is used when Quotation Accepted and admin want to change rfq product qty
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
            $('#rfq_product_qty'+key).prop("required", val);//this fields is used when Quotation Accepted and admin want to change rfq product qty
        }

        function weightChange(val, qty, key){
            @if(auth()->user()->role_id == 1)
                qty = parseFloat($('#rfq_product_qty'+key).val()?$('#rfq_product_qty'+key).val():0);
            @endif

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

        function rfqProductQtyChange(original_qty, val, qty, key) {

            priceValueChange(val, qty, key);
            changeQuoteQuantity(val, qty, key);
        }

        function priceValueChange(val, qty, key){
            @if(auth()->user()->role_id == 1)
            //if quote status is Quotation Accepted and admin want to change product price or change qty
                val = parseFloat($('#price'+key).val()?$('#price'+key).val():0);
                qty = parseFloat($('#rfq_product_qty'+key).val()?$('#rfq_product_qty'+key).val():0);
            @endif
            if(val != ''){
                $('#change_amount_'+key).html(val*qty);
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
                removeDisabled(false, duplicate);
                //}
            });
            $('#total_amount_span').html(total);
            $('#amount').val(total);
            checkAmountFinal();
            priceChange();
            platformChargeAmountCal();
        }

        function checkAmountFinal(){
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
        }
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
        function changeDetailsSupplier($this){
            let selected_option = $('option:selected', $this);
            let city = selected_option.attr('data-city') != 'null' ? selected_option.attr('data-city') : '';
            let provinces = selected_option.attr('data-state') != 'null' ? selected_option.attr('data-state') : '';
            $("#address_name").val(selected_option.attr('data-address_name') ?? '');
            $("#address_line_1").val(selected_option.attr('data-address_line_1') ?? '');
            $("#address_line_2").val(selected_option.attr('data-address_line_2') ?? '');
            $("#sub_district").val(selected_option.attr('data-sub_district') ?? '');
            $("#district").val(selected_option.attr('data-district') ?? '');
            $("#city").val(city ?? '');
            $("#provinces").val(provinces ?? '');
            $("#pincode").val(selected_option.attr('data-pincode') ?? '');
            $("#countryId").val(selected_option.attr('data-country-id') ?? '').trigger('change');
            $("#stateId").val(selected_option.attr('data-state-id') ?? '');
            $("#cityId").val(selected_option.attr('data-city-id') ?? '');
        }

        $(document).ready(function() {
            setTimeout( function(){ $('#supplier').select2(); },200);
            $("#quoteReply").parsley();
            parsValidation();

            $('#supplier').change(function () {
                let that = $(this);
                $.ajax({
                    url: "{{ route('supplier-xenaccount-exist','') }}/"+that.val(),
                    type: 'GET',
                    success: function (successData) {
                        if (successData.success == false) {
                            swal({
                                text: successData.message,
                                icon: location.origin + "/front-assets/images/bank_not_found.png",
                                dangerMode: true,
                            })
                            that.find('option').prop('selected', false);
                            //that.select2();
                            return false;
                        }
                    },
                    error: function () {
                        console.log("error");
                    }
                });
            });
            Parsley.addValidator('allselected', {
                validateString: function(value) {
                    return (true==(value != '-1'));
                },
                messages: {
                    en: 'This value is required.',
                },

            });
            getSupplierAdressDetails($('#supplier').val());
            //  $("#supplier_address_id").trigger('change');

            if($("#chargeMainDiv #chargeDiv").length == 1){
                $("#chargeMainDiv #chargeDiv").find('.deleteCharge').hide();
            }
            if($("#logchargeMainDiv #Log_chargeDiv").length == 1){
                $("#logchargeMainDiv #Log_chargeDiv").find('.deleteCharge').hide();
            }
            $('#showErr').html('');

            var supplier_id = $('#supplier').val();
            var rfq_id = '{{ $quotes->rfq_id }}'
            var quote_id = '{{ $quotes->id }}'
            if (supplier_id != -1){
                getProductDetails(supplier_id, rfq_id, quote_id);
            }

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
            });
            $(".disabled").addClass("old");

           /* $("#price").keyup(function() {
                var amount = 0;
                amount = $("#price").val() * $("#qty").text();
                $('#amount').val(amount.toFixed(2));
                priceChange();
            });*/
            $("#tax").keyup(function() {
                //let amount = calculateAmount();
                let amount = newCalAmount();
                setTax(amount);
                $('#finalAmount').val(0);
            });

            $('.charges').on('change', function(e) {
                // console.log($(this).parent().parent());
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
                    // $("#quoteReply").parsley();

                    var finalAmount = $('#finalAmount').val();
                    $('#chargeValue' + currentSelectId).parent('div').show();

                    // console.log(currentSelectId);
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
                        chargeAmount = ($('#amount').val() * value) / 100;
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

            $('.pay_charges_value').on('change', function(e) {
                setPlatformChargesVal($(this));
            });

            $('.logcharges').on('change', function(e) {
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
                        chargeAmount = ($('#amount').val() * value) / 100;
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

            /*setPaymentIndex($("#payment-main-div .pay_main_div:first"));
            $(document).on('change','.pay_charges', function(e) {
                let row = $(this).closest('.row.pay_main_div');
                if($(this).val()){
                    row.find("input").attr("data-parsley-required", "true");
                    let selected_op = $(this).find('option:selected');
                    let type = selected_op.attr('charge-type');
                    //let value_on = $(this).attr('data-value-on');
                    let value = selected_op.attr('data-value')? selected_op.attr('data-value') : 0;
                    let id = selected_op.attr('value');
                    let add_sub = selected_op.attr('data-plus-minus');
                    let chargeAmount = 0;
                    row.find('.pay_charge_amount').attr("data-plus-minus", add_sub);
                    row.find('.pay_type').val(type);
                    //row.find('.value_on').val(value_on);
                    row.find('.pay_charges_value').val(value);
                    if (type == '%') {
                        row.find('.pay_charges_value').attr({
                            "max": 100,
                            "min": 0
                        });
                        row.find('.pay_charges_value').parent('div').show();
                        chargeAmount = ($('#amount').val() * value) / 100;
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
            });*/

            $(document).on("click","#btn-pay-add", function() {
                $('#no_pay_charge').hide();
                @if(auth()->user()->role_id != 3)
                $("#payment-main-div").append(@json($paymentChargesHtml));
                @endif
                setPaymentIndex($("#payment-main-div .pay_main_div:last"));
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
                checkAmountFinal();
            });

            $("#submitQuote").bind("click", function(e) {
                let maxDayError = $("#MaxDayError").html();
                if (maxDayError=='' || maxDayError==undefined) {
                    maxDayError = false;
                } else {
                    maxDayError = true;
                }
                $("#calculateAmount").trigger('click');
                if($("#quoteReply").parsley().validate() && maxDayError==false) {
                    /*
                    if($('#groupId').val()){
                        let rfq_qty = $('#rfq_product_qty1').val();//product[0].quantity;
                        let unit = $('#unit_name').val();
                        let achieved_qty = $('#achieved_quantity').val();
                        let target_qty = $('#target_quantity').val();
                        let totalQty = parseInt(rfq_qty) + parseInt(achieved_qty);
                        let remainingQty = parseInt(target_qty) - parseInt(achieved_qty);
                        if(totalQty > target_qty) {
                            let text = "{{__('admin.rfq_quantity_should_not_be_greater')}} " + remainingQty + ' ' + unit;
                            swal({
                                text: text,
                                icon: "warning",
                                // buttons: ["{{__('admin.no')}}", "{{__('admin.yes')}}"],
                                // buttons: ["{{__('admin.ok')}}"],
                                button: {
                                    text: "{{__('admin.ok')}}"
                                }
                            });
                            return false;
                        }

                    }
                    */
                    isJNE = false;
                    @if(Auth::user()->hasRole('jne'))
                        var lc = false;
                        var isJNE = true;

                    @elseif(auth()->user()->role_id == 1 || Auth::user()->hasRole('agent') )
                        var lc = ($('#logistic_check').prop('checked') == false && $('#logCharges').val() == '');

                    @else
                        var lc = ($('#logistic_check').prop('checked') == false || $('#logCharges').val() == '');
                    @endif

                    if (lc){
                        swal({
                            text: "{{ __('admin.quote_include_message') }}",
                            // icon: "warning",
                            //buttons: true,
                            buttons: ["{{ __('admin.no') }}", "{{ __('admin.yes') }}"],
                            closeOnClickOutside: false
                            // dangerMode: true,
                        }).then((willCheck) => {
                            if (willCheck) {
                                $("#logistic_check").prop("checked", true);
                                $('#logistic_check').val('1');
                                removeLogisticCharges();
                                submitForm(e);
                            } else {
                                @if(auth()->user()->role_id != 1 && Auth::user()->role_id != \App\Models\Role::AGENT && Auth::user()->role_id != \App\Models\Role::JNE)
                                    submitForm(e);
                                @endif
                            }
                        });
                    } else if(lc == false && isJNE == true && $('#logCharges :selected').val()==''){
                        swal({
                            text: "{{ __('admin.jne_quote_logistic_message') }}",
                            icon: "/assets/images/info.png",
                            buttons: "{{ __('admin.ok') }}",
                            closeOnClickOutside: false
                        });
                    } else {
                        submitForm(e);
                    }
                } else {
                    e.preventDefault();
                    parsValidation();
                }

            });
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
                    return false;
                }
                if ($("#finalAmount").val() > 0 && checkedd != 0) {

                    if (SnippetQuoteEdit.verifyFinalAmount()) {
                        swal({
                            title: "{{ __('admin.quote_submit') }}",
                            text: "{{ __('admin.quote_submit_message') }}",
                            // icon: "warning",
                            //buttons: true,
                            buttons: ["{{ __('admin.validate') }}", "{{ __('admin.confirm') }}"],
                            // dangerMode: true,
                        }).then((willDelete) => {
                            if (willDelete) {
                                  var newExpDate = $("#valid_till").val();
                                  var myDate = newExpDate.split("-");
                                  var newDate = new Date( myDate[2], myDate[1] - 1, myDate[0]);
                                  var ExpDate = new Date('{{$quotes->valid_till}}');
                                  var ExpStatus = '{{$quotes->status_id}}';
                                  if(ExpStatus==3){
                                      if(newDate.getTime() > ExpDate.getTime()){
                                          swal({
                                                title: "{{ __('admin.quote_submit') }}",
                                                text: "{{ __('admin.renew_quote') }}",
                                                icon: "/assets/images/info.png",
                                                buttons: true,
                                                buttons: ["{{ __('admin.no') }}", "{{ __('admin.yes') }}"],
                                            }).then((willValid) => {
                                                if (willValid) {
                                                    $("#quoteReply").submit();
                                                }});
                                      }else{
                                         swal({
                                            text: "{{ __('admin.valid_date_error') }}",
                                            icon: "/assets/images/info.png",
                                            buttons: "{{ __('admin.ok') }}",
                                        })
                                          return false;
                                      }
                                  }else{
                                      $("#quoteReply").submit();
                                  }

                            } else {
                                return false;
                            }
                        });
                    }

                } else {
                    e.preventDefault();
                    alert('{{ __('admin.final_amount_error_message') }}');
                    return false;
                }
            //}
        }

        /*function setFixTransactionFees(){
            let pay_charges = $('select.pay_charges:first');
            pay_charges.find('option[value="10"]').prop('selected', true).trigger('change');
            $('a.remove-payment-charge:first').remove();
            pay_charges.closest('.col-md-3').prepend("<input type='hidden' name='"+pay_charges.attr('name')+"' value='"+pay_charges.val()+"'>");
            pay_charges.attr('disabled',true);

            let pay_charges_bltzcmsn = $('select.pay_charges:last');
             @if(config('app.env')=='live')
                pay_charges_bltzcmsn.find('option[value="13"]').prop('selected', true).trigger('change');
             @else
                pay_charges_bltzcmsn.find('option[value="18"]').prop('selected', true).trigger('change');
             @endif
            $('a.remove-payment-charge:last').remove();
            pay_charges_bltzcmsn.closest('.col-md-3').prepend("<input type='hidden' name='"+pay_charges_bltzcmsn.attr('name')+"' value='"+pay_charges_bltzcmsn.val()+"'>");
            pay_charges_bltzcmsn.attr('disabled',true);
        }*/

        $(function() {
            //setFixTransactionFees();
            $($("#payment-main-div .pay_main_div")).each(function( index ) {
                chargePaymetCharge('pay_charges', index);
                $('select#pay_charges_'+index).attr('disabled',true);
            });
            $('#profile-tab').click( function() {
                var quoteid = $(this).attr('data-quoteid');
                $.ajax({
                    url: "{{ route('admin-get-quote-activity-ajax', '') }}" + "/" + quoteid,
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
            $("#btnClone").bind("click", function () {
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
                $('.charges').each(function (i) {
                    $clone.find("#charges").children('option[value="'+$(this).val()+'"]').attr('disabled', 'disabled');
                });
                $clone.find("input:text").val("").end();
                //$clone.find('label').hide();
                $clone.find("#customChargeName").attr("id", "customChargeName" + index);
                $clone.find("#charges").attr("id", "charges" + index);
                $clone.find("#chargeType").attr("id", "chargeType" + index);
                $clone.find("#valueOn").attr("id", "valueOn" + index);
                $clone.find("#chargeValue").attr("id", "chargeValue" + index);
                $clone.find("#charge_amount").attr("id", "charge_amount" + index);
                $clone.find("#chargeValue" + index).val('');
                $clone.find("#chargeValue" + index).removeAttr('value');
                $clone.find("#charge_amount" + index).removeAttr('value');
                $clone.find("#chargeType" + index).removeAttr('value');
                $clone.find("#customChargeName" + index).removeAttr('value');
                $clone.find('#charges' + index + ' option:selected').removeAttr("selected");
                $clone.find("#chargeValue" + index).parent('div').show();
                $clone.find(".deleteCharge").show();
                $clone.appendTo($("#container"));
            }

            $('#logistic_check').click(function() {
                if ($("#logistic_check").is(":checked") == true) {
                    $('#logistic_check').val('1');
                } else {
                    $('#logistic_check').val('0');
                }
            });

            $("#btnLogClone").bind("click", function() {
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
                $('.logcharges').each(function(i) {
                    $clone.find("#logCharges").children('option[value="'+$(this).val()+'"]').attr('disabled', 'disabled');
                });
                $clone.find("input:text").val("").end();
                $clone.find("#logCustomChargeName").attr("id", "logCustomChargeName" + index);
                $clone.find("#logCharges").attr("id", "logCharges" + index);
                $clone.find("#logChargeType").attr("id", "logChargeType" + index);
                $clone.find("#valueOn").attr("id", "valueOn" + index);
                $clone.find("#logChargeValue").attr("id", "logChargeValue" + index);
                $clone.find("#logCharge_amount").attr("id", "logCharge_amount" + index);
                $clone.find("#logChargeValue" + index).parent('div').show();
                $clone.find("#logChargeValue" + index).removeAttr('value');
                $clone.find("#logCharge_amount" + index).removeAttr('value');
                $clone.find("#logChargeType" + index).removeAttr('value');
                $clone.find("#logCustomChargeName" + index).removeAttr('value');
                $clone.find('#logCharges' + index + ' option:selected').removeAttr("selected");
                $clone.find("#logChargeValue" + index).val('');
                $clone.find(".deleteCharge").show();
                $clone.appendTo($("#container1"));
            }

            $(document).on('click', '.removeFile', function (e) {
                e.preventDefault();
                var element = $(this);
                var id = $(this).attr("data-id");
                var fileName = $(this).attr("id");
                var quote_id = '{{ $quotes->id }}';
                var filekeyId = $(this).attr("data-filekeyId");
                var dataName = $(this).attr("data-name")
                var data = {
                    fileName: fileName,
                    filePath: $(this).attr("file-path"),
                    id: $(this).attr("data-id"),
                    quote_id:quote_id,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                };
                swal({
                    title: "Are you sure?",
                    //text: "You want to change order status.",
                    icon: "warning",
                    buttons: ["Cancel", "Ok"],
                    dangerMode: false,
                }).then((changeit) => {
                    if (changeit) {
                        $.ajax({
                            url: "{{ route('quote-certificate-file-delete-ajax') }}",
                            data: data,
                            type: "POST",
                            success: function (successData) {
                                $("#file-certificate"+filekeyId).html('');
                            },
                            error: function () {
                                console.log("error");
                            },
                        });
                    }
                });
            });

            $(document).on('keyup', '.rfq_product_qty', function (e) {
                let text = $(this).attr('id');
                let key = text.replace("rfq_product_qty", "");
                let qty = $(this).val();
                let val = $('#price'+key).val();
                priceValueChange(val, qty, key);
                changeQuoteQuantity(val, qty, key);
            });
        });
        $(document).on('click', '.removeSelectedFile', function (e) {
                e.preventDefault();
                var element = $(this);
                var dataKey = $(this).attr("data-key");

                swal({
                    title: "Are you sure?",
                    icon: "warning",
                    buttons: ["Cancel", "Ok"],
                    dangerMode: false,
                }).then((changeit) => {
                    if (changeit) {
                        $('#file-'+dataKey).html('');
                        $('#'+dataKey).val('');
                    }
                });
        });
        function changeQuoteQuantity(val, qty, key){
                var wight = $('#weights'+key).val();
                if (qty) {
                    $('#qty_' + key).val(parseFloat(qty));
                    $('#change_weight_' + key).html(parseFloat(qty*wight));
                }else{
                    $('#qty_' + key).val(parseFloat(original_qty));
                    $('#change_weight_' + key).html(parseFloat(qty*wight));
                }
        }
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
            var amtVal = $("#amount").val();
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

        function setTax(amount){
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
                    chargeAmount = ($('#amount').val() * value) / 100;
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

        /*****begin: Quote Edit Pickup Detail******/
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
                    selectCountryGetState(),
                    selectStateGetCity(),
                    selectCitySetOtherCity(),
                    initiateCityState()
                }
            }

        }(1);
        /*****end: Quote Edit Pickup Detail******/

        /*****begin: Quote RoleWise Permissions**/
        var SnippetQuoteEditRolePermission = function(){
            var isJNE = function () {
                $.ajax({
                    url: "{{ route('admin.user.check.role') }}",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    success: function(successData) {
                        if (successData.success) {
                            //Freeze form inputs
                            $('input[type=text]').attr('readonly', 'true');
                            $('select').attr('readonly', 'true');
                            $('textarea').attr('readonly', 'true');
                            $('input[type=checkbox]').attr('disabled', 'true');
                            $('input[type=file]').attr('disabled', 'true');
                            $('#valid_till').css('pointer-events','none');
                            $('#valid_till').css('touch-action: none');
                            $('#btnClone').css('pointer-events','none');
                            $('#btnClone').css('touch-action: none');
                            $('#btn-pay-add').css('pointer-events','none');
                            $('#btn-pay-add').css('touch-action: none');
                            $('#inclusive_tax_logistic').removeAttr('disabled');

                            $('div[data-id=group-discount-charges]').css('pointer-events','none');
                            $('div[data-id=group-discount-charges]').css('touch-action','none');

                            $('div[data-id=supplier-other-charges]').css('pointer-events','none');
                            $('div[data-id=supplier-other-charges]').css('touch-action','none');

                            //Enable only logistic
                            $("#logCustomChargeName").attr("readonly", false);
                            $("#logChargeValue").attr("readonly", false);
                            $("#logCharge_amount").attr("readonly", false);
                            $("#logCharges").attr("readonly", false);
                            $('#pickup_service').attr("readonly", false);
                            $('#pickup_fleet').attr("readonly", false);
                            $('#logistics_services').attr("readonly", false);
                            $('#wood_packing').removeAttr('disabled');
                        }
                    },
                    error: function() {

                    }
                });
            };

            return {
                init: function () {
                    isJNE()
                }
            }
        }(1);
        /*****end: Quote RoleWise Permissions**/

        /***********begin: Quote Edit**********/
        var SnippetQuoteEdit = function () {
            // Remove single error message
            var removeSingleErrors = function () {
                errorRemoveRule().finalAmount();

            },
            // Set custom rules to remove errors
            errorRemoveRule = function () {

                return {
                    finalAmount : function () {
                        $('input[type="text"]').on('input focusout', function(){
                            var input = $('#finalAmount').val();
                            if (input == '' || input == 0) {
                                $('#finalAmountError').html('');
                            }
                        });
                    }
                }

            };

            return {
                // Verify final amount
                verifyFinalAmount : function () {
                    var quoteId = $('#quote_id').val();
                    var amount = $('#finalAmount').val();

                    var response = false;

                    $.ajax({
                        url: "{{route('admin.quote.verify.loan.amount')}}",
                        type: 'POST',
                        async: false,
                        data: {
                            id : quoteId,
                            amount : amount
                        },
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        success: function (data) {

                            data.success ? $('#finalAmountError').html(data.message) : $('#finalAmountError').html('');

                            response = data.success ?  false : true;

                        },
                        error: function () {
                            console.log('Code 400 | ErrorCode:B010');
                        }
                    });

                    return response;
                },

                init : function() {
                    removeSingleErrors()
                }
            }
        }(1);
        /***********end: Quote Edit**********/

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

    </script>

@stop
