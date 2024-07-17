@extends('admin/adminLayout')
@push('bottom_head')
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <style>
        .downArrowIcon {
            float: right !important;
            /* margin-top: -30px; */
            margin-right: 5px !important;

            pointer-events: none !important;
            background-color: #fff !important;
            padding-right: 5px !important;
            position: absolute !important;
            right: 0 !important;
            top: 40px !important;
        }

        .parsley-errors-list + .downArrowIcon {
            top: 10px !important;
        }

        .selectBox {
            background-color: white !important;
            height: 40px !important;
        }

        .awesomplete {
            width: 100%;
        }

        .suplier-succ {
            color: green;
            font-size: 20px;
        }

        #group-steps ul {
            margin: 0 auto;
            /* margin-bottom: 40px; */
        }

        .contact_step a.nav-link {
            position: relative;
        }

        .contact_step .step_img {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            text-align: center;
        }

        .contact_step .icon {
            height: 60px;
            width: 60px;
            background: #fff;
            border: 1px solid #ddd;
            margin: 4px auto;
            border-radius: 100%;
            padding: 2px;
            position: relative;
            z-index: 10;
        }

        #group-steps ul:before {
            display: none;
        }

        .contact_step .icon .pen_icon {
            /* background: url(../../front-assets/images/pen_icon.png) no-repeat center center; */
            border: 1px solid #dae1e5;
            border-radius: 100%;
            background-color: #dae1e5;
            display: inline-block;
            height: 54px;
            width: 54px;
        }

        .contact_step .icon .pen_icon.group {
            background-image: url(../../front-assets/images/icons/icon_group_w.png);
            background-repeat: no-repeat;
            background-position: center center;
        }

        .contact_step .icon .pen_icon.buyer {
            background-image: url(../../front-assets/images/icons/icon_buyer_W.png);
            background-repeat: no-repeat;
            background-position: center center;
        }

        .contact_step .icon .pen_icon.gallery {
            background-image: url(../../front-assets/images/icons/icon_gallery_w.png);
            background-repeat: no-repeat;
            background-position: center center;
        }

        .nav-link.done .pen_icon {
            border: 2px solid #fff;
            background-image: url(../../front-assets/images/check_icon.png) no-repeat center center;
            background-color: #7cc576;
        }

        .nav-link.done .pen_icon.supplier, .nav-link.done .pen_icon.product, .nav-link.done .pen_icon.bank {
            border: 2px solid #fff;
            background-image: url(../../front-assets/images/check_icon.png) no-repeat center center;
            background-color: #7cc576;
        }

        .nav-link.active .pen_icon {
            border: 2px solid #fff;
            background-color: #09f;
        }

        .sw-theme-dots > .nav .nav-link::after {
            content: '';
            height: 3px;
            background: #dae1e5;
            width: 255px;
            position: absolute;
            right: -100%;
            top: 55%
        }

        .sw-theme-dots > .nav .nav-link:first-child::after {
            background: #dae1e5;
        }

        .sw-theme-dots > .nav .nav-link.active:after {
            background: #369ede;
        }

        .sw-theme-dots > .nav .nav-link::before {
            display: none;
        }

        .sw-theme-dots > .nav li:last-child .nav-link:first-child::after {
            display: none;
        }

        .contact_step .icon {
            z-index: 100 !important;
        }


        .d-none {
            display: none;
        }

        div.tagsinput {
            width: auto;
            border-color: #dee2e6;
            padding: 5px;
        }

        div.tagsinput span.tag {
            margin-bottom: 1px;
            margin-top: 2px;
        }

        div.tagsinput input {
            padding: 2px 5px;
            margin: 0 5px 2px 0;
        }

        .form-switch .form-check-input {
            margin-left: 2.5em;
        }

        /*.table td,*/
        /*th {*/
        /*    text-align: left;*/
        /*}*/

        .table td img {
            width: 20px;
            height: 20px;
            border-radius: 0px;
        }

        .form-control {
            padding: 0.7rem 0.375rem;
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

        .swal-icon--warning__body,
        .swal-icon--warning__dot {
            background-color: #e64942 !important;
        }

        .swal-icon--warning {
            border-color: #e64942 !important;
        }

        .upload_btn {
            background-color: rgb(37, 55, 139);
            color: white;
            border-radius: 0.3rem;
            padding: 3px 7px;
            cursor: pointer;
        }

        .parsley-errors-list li {
            font-weight: normal;
        }

        .date .parsley-errors-list {
            position: absolute;
            bottom: -30px;
        }

        /* .thumb {
            margin: 10px 5px 0 0;
            width: 100px;
            height: 100%;
        } */

        .lightGallery .lightgallery_img {
            background-color: #d7d9df;
            border: 1px solid #ccc;
            max-height: 150px;
            max-width: 150px;
            line-height: 146px;overflow: hidden;
        }
        .lightGallery .lightgallery_img img,
        .lightGallery>div img {
            max-width: 100%;
            max-height: 100%;
        }

        .lightGallery>div .image-tile {
            height: 100%;
            width: 100%;
            margin-bottom: 0;
            padding: 0;
        }

        .lightGallery .col-6 {
            position: relative;
        }

        .lightGallery .input-group-text {
            position: absolute;
            right: 0rem;
            top: 1px;
        }

        .lightGallery .active {
            border: 1px solid #23af47;
        }

        .lightGallery .inactive {
            border: 1px solid #1c2c42;
        }

        .select2-container--default .select2-selection--single {
            height: 40px !important;
        }

        .form-select{
            padding: 0.7rem 0.375rem;
        }

        .tab-content .selectBox {
    height: 40px !important;
}

        /* //new */

        #show-images div{ width: 150px; height: 150px; display: inline-block; background-color: #d7d9df;  border: 1px solid #ccc; position: relative;   line-height: 148px;}
        #show-images .thumb{ height: auto; width: auto; max-width: 100%; max-height: 100%;}
        .opacity-25{ opacity: .25;}
        #groupImageEdit{background-color: #fbfbfb; border: 1px dashed #ccc;}
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="row">
                <div class="col-md-12 d-flex align-items-center mb-3">
                    <h1 class="mb-0 h3">{{ __('admin.groups') }}</h1>
                    <a href="{{ route('groups-list') }}" class="mb-2 backurl ms-auto btn-close"></a>
                </div>

                <div class="col-12">
                    <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                    aria-selected="true">{{ __('admin.edit_group') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-0 ms-5" id="profile-tab" data-groupid="{{$groups->id}}" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false"> {{ __('admin.activities') }}
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content pt-3 pb-0 px-0" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div id="group-steps">
                                <ul class="nav col-md-6 contact_step">
                                    <li>
                                        <a class="nav-link" href="#step-1">{{ __('admin.group') }}
                                            <div class="icon"><i class="pen_icon group"></i></div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="nav-link" href="#step-2">{{ __('admin.gallery') }}
                                            <div class="icon"><i class="pen_icon gallery"></i></div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="nav-link" href="#step-3">{{ __('admin.buyer') }}
                                            <div class="icon"><i class="pen_icon buyer"></i></div>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content pt-3 pb-0 ">
                                    {{-- Group --}}
                                    <div id="step-1" class="tab-pane p-0" role="tabpanel" style="display: none;">
                                        <form class="" id="groupedit" method="POST" enctype="multipart/form-data" action="{{ route('group-update') }}" data-parsley-validate>
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $groups->id }}">
                                            @if($buyerRfqsCount > 0)
                                                <input type="hidden" id="supplier" name="supplier" value="{{ $groupSupplier[0]->supplier_id }}">
                                                <input type="hidden" id="category" name="category" value="{{ $groups['category_id'] }}">
                                                <input type="hidden" id="subcategory" name="sub_category" value="{{ $groups['subCategory_id'] }}">
                                                <input type="hidden" id="product_name" name="product_name" value="{{ $groups['product_id'] }}">
                                                <input type="hidden" id="unit_name_main" name="unit_name" value="{{ $groups['unit_id'] }}">
                                            @endif
                                            <div class="row">
                                                <div class="col-md-12 mb-2">
                                                    <div class="card">
                                                        <div class="card-header d-flex align-items-center">
                                                            <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/people-carry-1.png')}}" alt="Supplier Details" class="pe-2">
                                                                <span>{{ __('admin.supplier_detail')}}</span>
                                                            </h5>
                                                        </div>
                                                        <div class="card-body p-3 pb-1">
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="supplier" class="form-label align-items-center">{{ __('admin.supplier') }}<span class="text-danger">*</span></label>
                                                                    <select @if($buyerRfqsCount > 0) disabled @endif class="form-select selectBox" name="supplier" id="supplier" data-parsley-errors-container="#supplier_error" data-parsley-allselected="true" onchange="supplierProductCategoryChange(this.value)"  style="background-color:#e9ecef !important;" required {{ auth()->user()->role_id == 3? 'disabled': '' }}>
                                                                        <option value="-1" disabled selected>{{ __('admin.select_supplier') }}</option>
                                                                        @foreach($suppliers as $supplier)
                                                                            <option value="{{ $supplier['id'] }}" {{ $supplier['id'] == $groupSupplier[0]->supplier_id ? 'selected="selected"' : '' }} {{ auth()->user()->role_id == 3 && $supplier['id'] != $supplier['id']  ? 'disabled': '' }}">{{ $supplier['name'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div id="supplier_error"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <div class="card">
                                                        <div class="card-header d-flex align-items-center">
                                                            <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/group_details.png')}}" alt="Group Details" class="pe-2">
                                                                <span>{{ __('admin.group_detail') }}</span>
                                                            </h5>
                                                        </div>
                                                        <div class="card-body p-3 pb-1">
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <input type="hidden" id="grpName" value="{{ $groups['name'] }}" />
                                                                    <label for="name" class="form-label">{{ __('admin.group_name') }}<span class="text-danger">*</span></label>
                                                                    <input {{ $buyerRfqsCount > 0 ? 'readonly' : ''}}  type="text" id="name" name="name" class="form-control" pattern="/^[a-z0-9\s]+$/i" data-parsley-pattern="/^[a-z0-9\s]+$/i" onblur="duplicateGroupName(this)" required value="{{ $groups['name'] }}">
                                                                    <div id="duplicateErr" class="text-danger"></div>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="location_code" class="form-label">{{ __('admin.location') }} &nbsp;</label>
                                                                    <input {{ $buyerRfqsCount > 0 ? 'readonly' : ''}}  type="text" id="location_code" name="location_code" class="form-control" value="{{ $groups['location_code'] }}">
                                                                </div>

                                                                <div class="col-md-6 mb-3">
                                                                    <label for="category" class="form-label">{{ __('admin.product_category_text') }}<span class="text-danger">*</span></label>
                                                                    <select @if($buyerRfqsCount > 0) disabled style="background-color:#e9ecef !important;" @endif class="form-select selectBox" id="category" name="category" required="" data-parsley-errors-container="#category_error" onchange="supplierCategoryChange(this.value)">
                                                                        <option disabled="" selected="">{{ __('admin.select_category') }} eg. Steel, Wood, Yarn etc.*
                                                                        </option>
                                                                        @foreach($categories as $category)
                                                                            <option value="{{ $category->id }}" {{ $category->id == $groups['category_id'] ? 'selected' : '' }} >{{ $category->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div id="category_error"></div>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="subcategory selectBox" class="form-label">{{ __('admin.sub_category') }}<span class="text-danger">*</span></label>
                                                                    <select @if($buyerRfqsCount > 0) disabled style="background-color:#e9ecef !important;" @endif class="form-select selectBox resetFeildSubCat" id="subcategory" name="sub_category" required="" data-parsley-errors-container="#subcategory_error" onchange="supplierSubCategoryChange(this.value)">
                                                                        <option disabled>{{ __('admin.select_sub_category') }}</option>
                                                                        @foreach ($subCategories as $subCategory)
                                                                            <option value="{{ $subCategory['id'] }}" {{ $subCategory['id'] == $groups['subCategory_id'] ? 'selected="selected"' : '' }}>{{ $subCategory['name'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div id="subcategory_error"></div>
                                                                </div>

                                                                <div class="col-md-6 mb-3">
                                                                    <label for="product_name" class="form-label">{{ __('admin.product_name') }}<span class="text-danger">*</span></label>
                                                                    <select @if($buyerRfqsCount > 0) disabled style="background-color:#e9ecef !important;" @endif class="form-select selectBox resetFeild" id="product_name" name="product_name" data-parsley-errors-container="#product_error">
                                                                        <option disabled>{{ __('admin.select_product_name') }}</option>
                                                                        @foreach ($products as $product)
                                                                            <option value="{{ $product['id'] }}" {{ $product['id'] == $groups['product_id'] ? 'selected="selected"' : '' }}>{{ $product['name'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div id="product_error"></div>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="group_margin" class="form-label">{{ __('admin.group_margin') }}%<span class="text-danger">*</span></label>
                                                                    <input {{ auth()->user()->role_id == 1 ? '' : 'readonly'}} type="text" class="form-control" id="group_margin" name="group_margin" value="{{ $groups['group_margin'] }}" required data-parsley-errors-container="#group_margin_error" onkeypress="return isNumberKey(this, event);">
                                                                    <div id="group_margin_error"></div>
                                                                </div>
                                                                <div class="col-md-12 mb-3">
                                                                    <label for="product_description" class="form-label">{{ __('admin.product_description') }}<span class="text-danger">*</span></label>
                                                                    <textarea  {{ $buyerRfqsCount > 0 ? 'readonly' : ''}} class="form-control" id="product_description" name="product_description" placeholder="Product Description" rows="3" required data-parsley-errors-container="#product_description_error"> {{ $groups['product_description'] }} </textarea>
                                                                    <div id="product_description_error"></div>
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="unit_name" class="form-label">{{ __('admin.unit_name') }}<span class="text-danger">*</span></label>
                                                                    <select @if($buyerRfqsCount > 0) disabled style="background-color:#e9ecef !important;" @endif class="form-select selectBox resetFeild" id="unit_name_main" name="unit_name" required="" data-parsley-errors-container="#unit_name_error" onchange="unitChange($(this))">
                                                                        <option disabled="" selected="">{{ __('admin.select_unit') }}</option>
                                                                        @foreach($units as $unit)
                                                                            <option value="{{ $unit['id'] }}" {{ $unit['id'] == $groups['unit_id'] ? 'selected="selected"' : '' }}>{{ $unit['name'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div id="unit_name_error"></div>
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="price" class="form-label">{{ __('admin.price') }}<span class="text-danger">*</span></label>
                                                                    <div>
                                                                        <input {{ $buyerRfqsCount > 0 ? 'readonly' : ''}}  type="text" id="price" name="price" class="form-control resetFeild" required data-parsley-errors-container="#price_error" value="{{ $groups['price'] }}" min="1" onkeypress="return isNumberKey(this, event);">
                                                                        <div id="price_error"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 pb-2">
                                                                    <label for="exp_date" class="form-label">{{ __('admin.exp_date') }}&nbsp;<span class="text-danger">*</span></label>
                                                                    <div id="date" class="input-group date datepicker mb-4" style="position: relative;">
                                                                        <input type="text" id="exp_date" name="exp_date" class="form-control" style="border: 1px solid #dee2e6; " required="" value="{{ changeDateFormat($groups['end_date'],'d/m/Y') }}" autocomplete="off">
                                                                        <span class="input-group-addon input-group-append border-left">
                                                                            <span class="mdi mdi-calendar input-group-text  h-100" style="border: 1px solid #dee2e6; padding: 0.7rem"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="min_order_quantity" class="form-label">{{ __('admin.minimum_order_quantity') }}<span class="text-danger">*</span></label>
                                                                    <div>
                                                                        <input {{ auth()->user()->role_id == 3 && $buyerRfqsCount > 0 ? 'readonly' : ''}} type="text" id="min_order_quantity" name="min_order_quantity" class="form-control resetFeild" required value="{{ $groups['min_order_quantity'] }}" min="1" onchange="minOrderQty(this)" onkeypress="return isNumberKey(this, event);" data-parsley-type="digits">
                                                                        <div id="showErrMinOrder" name="showErrMinOrder"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="max_order_quantity" class="form-label">{{ __('admin.maximum_order_quantity') }}<span class="text-danger">*</span></label>
                                                                    <div>
                                                                        <input {{ auth()->user()->role_id == 3 && $buyerRfqsCount > 0 ? 'readonly' : ''}} type="text" id="max_order_quantity" name="max_order_quantity" class="form-control resetFeild" required value="{{ $groups['max_order_quantity'] }}"min="1" onchange="maxOrderQty(this)" onkeypress="return isNumberKey(this, event);" data-parsley-type="digits">
                                                                        <div id="showErrMaxOrder" name="showErrMaxOrder"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 pb-2">
                                                                    <label for="add_tags" class="form-label">{{ __('admin.add_a_tags') }} &nbsp;</label>
                                                                    <input  {{ $buyerRfqsCount > 0 ? 'readonly' : ''}} type="text" class="form-control" id="add_tags" name="add_tags" value=" {{ implode(",",array_column($groupTagsMultiple, 'tag')) }}">
                                                                </div>

                                                                <div class="col-md-12 mb-3">
                                                                    <label for="description" class="form-label">{{ __('admin.group') }} {{ __('admin.description') }}<span class="text-danger">*</span></label>
                                                                    <textarea {{ $buyerRfqsCount > 0 ? 'readonly' : ''}} class="form-control" id="description" name="description" placeholder="Description" rows="3" required> {{ $groups['description'] }} </textarea>
                                                                </div>

                                                                <div class="col-md-4 mb-3">
                                                                    <label for="group_status" class="form-label">{{ __('admin.group_status') }}<span class="text-danger">*</span></label>
                                                                    <select class="form-select {{$totalBuyerRefundCount?'':'selectBox'}}" id="group_status" name="group_status" required {{$totalBuyerRefundCount?'disabled':''}} title="{{ $totalBuyerRefundCount?__('admin.buyer_refund_initiated'):'' }}">
                                                                        <option disabled="" selected="">{{__('admin.select_group_status')}}</option>
                                                                        @foreach($groupStatus as $status)
                                                                            <option value="{{ $status['id'] }}" {{ $status['id'] == $groups['group_status'] ? 'selected="selected"' : '' }}>{{ $status['name'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mb-2">
                                                    <div class="card">
                                                        <div class="card-header d-flex align-items-center">
                                                            <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/boxes.png')}}" alt="Range Details" class="pe-2">
                                                                <span>{{ __('admin.range_details') }}<span class="icon ms-1"><a href="javascript:void(0)" id="btnClone" onclick="cloneDiv()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg></a></span></span>
                                                            </h5>
                                                        </div>
{{--                                                        <div class="card-body p-3 pb-1" id="removeCloanDiv">--}}
                                                        <div class="card-body p-3 pb-1">
                                                            <div id="mainDiv">
                                                                @php $key=0;
                                                                    $lastArrayKey = array_key_last($productDetailsMultiple);
                                                                @endphp
                                                                <div class="row productDiv" id="group_productDiv">
                                                                    <input type="hidden" id="s_id" name="s_id[]" value="{{ !empty($productDetailsMultiple) ? $productDetailsMultiple[0]['id'] : 0 }}">
                                                                    <input type="hidden" id="custom_add" name="custom_add[]" value="0">
                                                                    <div class="col-md-2 mb-3">
                                                                        <label for="min_qty" class="form-label min_qty">{{ __('admin.min_quantity') }}<span class="text-danger">*</span></label>
                                                                        <input {{$key == $lastArrayKey ? '' : 'readonly'}} type="text" id="min_qty" name="min_qty[]" class="form-control cloanMinQty resetFeild" onchange="minQty(this)" onkeypress="return isNumberKey(this, event);" required value="{{ !empty($productDetailsMultiple) ? $productDetailsMultiple[0]['min_quantity'] : 0 }}">
                                                                        <div id="showErrMin" name="showErrMin[]"></div>
                                                                    </div>
                                                                    <div class="col-md-2 mb-3">
                                                                        <label for="max_qty" class="form-label max_qty">{{ __('admin.max_quantity') }}<span class="text-danger">*</span></label>
                                                                        <input {{$key == $lastArrayKey ? '' : 'readonly'}} type="text" id="max_qty" name="max_qty[]" class="form-control cloanMaxQty resetFeild" onchange="maxQty(this)" onkeypress="return isNumberKey(this, event);" required value="{{ !empty($productDetailsMultiple) ? $productDetailsMultiple[0]['max_quantity'] : 0 }}">
                                                                        <div id="showErr" name="showErr[]"></div>
                                                                    </div>
                                                                    <div class="col-md-2 mb-3">
                                                                        <label for="unit" class="form-label unit">{{ __('admin.unit_name') }}<span class="text-danger">*</span></label>
                                                                        <select class="form-select unitVal resetFeild" id="unit" style="padding: 0.575rem 0.375rem;" name="unit[]" disabled>
                                                                            <option disabled selected>{{ __('admin.select_unit') }}</option>
                                                                            @foreach ($units as $unit)
                                                                                <option value="{{ $unit->id }}" {{ !empty($unit) && $unit->id == $productDetailsMultiple[0]['unit_id'] ? "selected" : '' }} >{{ $unit->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-2 mb-3">
                                                                        <label for="discount" class="form-label discount">{{ __('admin.discount') }}%<span class="text-danger">*</span></label>
                                                                        <input {{$key == $lastArrayKey ? '' : 'readonly'}} type="text" id="discount" name="discount[]" class="form-control discountVal resetFeild" required value="{{ !empty($productDetailsMultiple) ? $productDetailsMultiple[0]['discount'] : 0 }}" onkeypress="return isNumberKey(this, event);" min="0" max="100" data-parsley-trigger="keyup" data-parsley-type="number">
                                                                        <div id="showErrDiscount" name="showErrDiscount[]"></div>
                                                                    </div>
                                                                    <div class="col-md-3 mb-3">
                                                                        <label for="discount_price" class="form-label discount">{{ __('admin.discount_price') }}</label>
                                                                        <input type="text" id="discount_price" name="discount_price[]" class="form-control discountAmount resetFeild" value="{{ !empty($productDetailsMultiple) ? $productDetailsMultiple[0]['discount_price'] : 0.00 }}" readonly>
                                                                    </div>
                                                                    <div class="col-md-1 pt-1 mb-3 ">
                                                                        <label for="" class="form-label"></label>
                                                                        <div style="line-height: 38px;" id="deleteBtn" name="deleteBtn[]">
                                                                            <span class="icon deleteRange d-none"><a href="javascript:void(0)" id="deleteBtn" class="text-danger removeRange "><i class="fa fa-trash mt-3"></i></a></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="cloneDiv">
                                                                @if($productDetailsMultiple)
                                                                    {{-- @php $lastArrayKey = array_key_last($productDetailsMultiple); @endphp--}}
                                                                    @foreach($productDetailsMultiple as $key => $value)
                                                                        @if($key==0) @continue @endif
                                                                        <div class="row productDiv" id="group_productDiv">
                                                                            <input type="hidden" id="s_id{{$key}}" name="s_id[]" value="{{ $value['id'] }}">
                                                                            <input type="hidden" id="custom_add{{$key}}" name="custom_add[]" value="0">
                                                                            <div class="col-md-2 mb-3">
                                                                                <label for="min_qty" class="form-label min_qty resetFeild">{{ __('admin.min_quantity') }}<span class="text-danger">*</span></label>
                                                                                <input readonly type="text" id="min_qty{{$key}}" name="min_qty[]" class="form-control cloanMinQty" onchange="minQty(this)" onkeypress="return isNumberKey(this, event);" required value="{{ $value['min_quantity'] }}">
                                                                                <div id="showErrMin{{$key}}" name="showErrMin[]"></div>
                                                                            </div>
                                                                            <div class="col-md-2 mb-3">
                                                                                <label for="max_qty" class="form-label max_qty resetFeild">{{ __('admin.max_quantity') }}<span class="text-danger">*</span></label>
                                                                                <input {{$key == $lastArrayKey ? '' : 'readonly'}} type="text" id="max_qty{{$key}}" name="max_qty[]" class="form-control cloanMaxQty" onchange="maxQty(this)" onkeypress="return isNumberKey(this, event);" required value="{{ $value['max_quantity'] }}">
                                                                                <div id="showErr{{$key}}" name="showErr[]"></div>
                                                                            </div>
                                                                            <div class="col-md-2 mb-3">
                                                                                <label for="unit" class="form-label unit">{{ __('admin.unit_name') }}</label>
                                                                                <select class="form-select unitVal resetFeild" id="unit" name="unit[]" disabled>
                                                                                    <option disabled selected>{{ __('admin.select_unit') }}</option>
                                                                                    @foreach ($units as $unit)
                                                                                        <option value="{{ $unit->id }}" {{ !empty($unit) && $unit->id == $productDetailsMultiple[0]['unit_id'] ? "selected" : '' }}>{{ $unit->name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-2 mb-3">
                                                                                <label for="discount" class="form-label discount resetFeild">{{ __('admin.discount') }}%<span class="text-danger">*</span></label>
                                                                                <input {{$key == $lastArrayKey ? '' : 'readonly'}} type="text" id="discount{{$key}}" name="discount[]" class="form-control discountVal" required value="{{ $value['discount'] }}" onkeypress="return isNumberKey(this, event);" min="0" max="100" data-parsley-trigger="keyup" data-parsley-type="number">
                                                                                <div id="showErrDiscount{{$key}}" name="showErrDiscount[]"></div>
                                                                            </div>
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="discount_price" class="form-label discount">{{ __('admin.discount_price') }}</label>
                                                                                <input type="text" id="discount_price{{$key}}" name="discount_price[]" class="form-control discountAmount resetFeild" value="{{ $value['discount_price'] }}" readonly>
                                                                            </div>
                                                                            <div class="col-md-1 pt-1 mb-3 ">
                                                                                <label for="" class="form-label"></label>
                                                                                <div style="line-height: 38px;">
                                                                                    <span class="icon deleteRange {{$key == $lastArrayKey ? '' : 'd-none'}} " id="deleteBtn{{$key}}" name="deleteBtn[]">
                                                                                        <a href="javascript:void(0)" id="deleteBtn{{$key}}" class="text-danger removeRange "><i class="fa fa-trash mt-3"></i></a>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- Gallary --}}
                                    <div id="step-2" class="tab-pane p-0" role="tabpanel" style="display: none;">
                                        <div class="col-md-12 mb-2">
                                            <div class="card">
                                                <div class="card-header d-flex align-items-center">
                                                    <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/icon_gallery.png')}}" alt="Gallery Details" class="pe-2">
                                                        <span>{{__('admin.gallery_details')}}</span>
                                                    </h5>
                                                </div>
                                                <div class="card-body p-3 pb-1">
                                                    <div class="row">
                                                        <div class="col-md-4 col-lg-3 mb-3">
                                                            <form class="h-100 " id="groupImageEdit" method="POST" enctype="multipart/form-data" action="{{ route('group-update-images') }}" data-parsley-validate>
                                                                @csrf
                                                                <input type="hidden" name="id" value="{{ $groups->id }}">
                                                                <div class="d-flex p-3 flex-column align-items-center justify-content-center h-100">
                                                                    <label for="image-order_latter" class="form-label align-items-center">{{__('admin.upload_images')}}</label>
                                                                    <span class=""><input multiple type="file" name="group_image[]" id="image-group_image" accept=".jpg,.png,.pdf" hidden=""><label id="upload_btn" for="image-group_image">{{ __('admin.browse') }}</label></span>
                                                                    <button type="button" class="btn btn-info btn-sm mt-3 w-100 d-none" id="uploadImages">Upload</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="col-md-8 col-lg-9 mb-3">
                                                            <!-- thumb preview image upload -->
                                                            <div id="thumb-output" class="float-start">
                                                                <div id="show-images"></div>

                                                            </div>
                                                            <!--end thumb image upload -->

                                                            <!-- uploaded image gallery -->
                                                            <div id="lightgallery" class="lightGallery float-start w-auto">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Buyer --}}
                                    <div id="step-3" class="tab-pane p-0" role="tabpanel" style="display: none;">
                                        <div class="">
                                            <div class="card mb-3">
                                                <div class="card-header d-flex align-items-center">
                                                    <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/boxes.png')}}" alt="{{__('admin.quantity_details')}}" class="pe-2"><span>Quantity Details</span></h5>
                                                </div>
                                                <div class="card-body p-3 pb-1">
                                                    <div class="row rfqform_view bg-white">
                                                        @if(isset($buyerRfqs))
                                                            @foreach($buyerRfqs as $buyerRfq)
                                                            @endforeach

                                                        <div class="col-md-12 row">
                                                            <div class="col-md-3 pb-2">
                                                                <label>{{__('admin.target_quantity')}}</label>
                                                                <div class="text-dark">{{!empty($buyerRfq) ? ($buyerRfq->target_quantity.' '.$buyerRfq->units_name) :'0'}}</div>
                                                            </div>
                                                            <div class="col-md-3 pb-2">
                                                                <label>{{__('admin.quantity_reached')}} </label>
                                                                <div class="text-dark">{{!empty($buyerRfq) ? ($buyerRfq->reached_quantity.' '.$buyerRfq->units_name) :'0'}}</div>
                                                            </div>
                                                            <div class="col-md-3 pb-2">
                                                                <label>{{__('admin.achieved_quantity')}}</label>
                                                                <div class="text-dark">{{!empty($buyerRfq) ? ($buyerRfq->achieved_quantity.' '.$buyerRfq->units_name) :'0'}}</div>
                                                            </div>
                                                            <div class="col-md-3 pb-2">
                                                                <label>{{__('admin.prospect_discount')}}</label>
                                                                <div class="text-blue">{{ isset($prospectDiscount) ? $prospectDiscount . ' %' : '0%' }}</div>
                                                            </div>
                                                            <div class="col-md-3 pb-2">
                                                                <label>{{__('admin.achieved_discount')}}</label>
                                                                <div class="text-dark">{{ isset($discountData) ? $discountData->discount . ' %' : '0%' }}</div>
                                                            </div>
                                                            {{-- <div class="col-md-3 pb-2">
                                                                <label>{{__('admin.prospect_revenue')}}</label>
                                                                <div class="text-dark">{{ isset($groupProspectRevenue) ? $groupProspectRevenue : '0' }} IDR</div>
                                                            </div>
                                                            <div class="col-md-3 pb-2">
                                                                <label>{{__('admin.realised_revenue')}}</label>
                                                                <div class="text-dark">{{ isset($groupObtainedRevenue) ? $groupObtainedRevenue : '0' }} IDR</div>
                                                            </div> --}}
                                                            <div class="col-md-2 ms-auto pb-2 d-none">
                                                            @php
                                                                    $percentage = !empty($buyerRfq) ? ($buyerRfq->reached_quantity == 0 ? 0 : ($buyerRfq->reached_quantity  / $buyerRfq->target_quantity * 100)) :'0';
                                                                @endphp
                                                                <div class="d-flex">
                                                                    <div class="" style="font-size: 0.7em;">{{__('admin.quantity_reached')}}</div>
                                                                    <div class="ms-auto" style="font-size: 0.7em;">{{$percentage}} %</div>
                                                                </div>

                                                                <div class="text-dark mt-1">
                                                                    <div class="progress">
                                                                        <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: {{$percentage}}%" aria-valuenow="{{$percentage}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card mb-2">
                                                <div class="card-header">
                                                    <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/people-carry-1.png')}}" alt="Buyer Details" class="pe-2">Buyer Detail
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#addBuyerModal" title="Add buyers" class="text-decoration-none text-dark ps-1 d-none">
                                                            <svg style="width: 18px; height:18px; cursor: pointer;" viewBox="0 0 24 24">
                                                                <path fill="currentColor" d="M17,13H13V17H11V13H7V11H11V7H13V11H17M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
                                                            </svg>
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div class="card-body p-3 py-2 pb-1">
                                                    <div class="mt-2 newtable_v2">
                                                        <div class="table-responsive">
                                                            <table id="groupMemberTable" class="table table-hover border" style="width: 100%;">
                                                                <thead>
                                                                    <tr class="bg-light">
                                                                        <th scope="col">Sr No</th>
                                                                        <th scope="col">{{__('admin.rfq_number')}}</th>
                                                                        <th scope="col">{{__('admin.quote_number')}}</th>
                                                                        <th scope="col">{{__('admin.order_number')}}</th>
                                                                        <th scope="col">{{__('admin.customer_name')}}</th>
                                                                        <th scope="col">{{__('admin.company_name')}}</th>
                                                                        <th scope="col">{{__('admin.customer_address')}}</th>
                                                                        <th scope="col">{{__('admin.quantity')}}</th>
                                                                        <th scope="col">{{__('admin.status')}}</th>
                                                                        <th class="text-center" scope="col">{{__('admin.action')}}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="bg-white">
                                                                    @if($buyerRfqs)
                                                                        @php
                                                                            $sr_no = 1;
                                                                            $roleCheck = \App\Models\Role::SUPPLIER;
                                                                            $groupStatusCheck = \App\Models\Groups::CLOSED;
                                                                        @endphp
                                                                        @foreach($buyerRfqs as $buyerRfq)
                                                                            <tr>
                                                                                <td>{{$sr_no++}}</td>
                                                                                <td class="">
                                                                                    @if(isset($buyerRfq->rfq_number) && $buyerRfq->rfq_number)
                                                                                        <a href="javascript:void(0);" class="viewRfqDetail text-decoration-none" data-bs-toggle="modal" data-bs-target="#viewRfqModal" data-id="{{ $buyerRfq->group_members_rfq_id }}">{{$buyerRfq->rfq_number}}</a>
                                                                                    @else
                                                                                        <p class="text-center"> - </p>
                                                                                    @endif
                                                                                </td>
                                                                                <td class="">
                                                                                    @if(isset($buyerRfq->quote_number) && $buyerRfq->quote_number)
                                                                                        <a href="javascript:void(0);" class="vieQuoteDetail text-decoration-none" data-id="{{ $buyerRfq->quoteId }}" data-bs-toggle="modal" data-bs-target="#viewRfqModalnew">{{$buyerRfq->quote_number}}</a>
                                                                                    @else
                                                                                        <p class="text-center"> - </p>
                                                                                    @endif
                                                                                </td>
                                                                                <td class="">
                                                                                    @if(isset($buyerRfq->order_number) && $buyerRfq->order_number)
                                                                                        <a href="javascript:void(0);" class="getSingleOrderDetail text-decoration-none" data-id="{{ $buyerRfq->orderId }}" data-bs-toggle="modal" data-bs-target="#viewOrderModalnew">{{$buyerRfq->order_number}}</a>
                                                                                    @else
                                                                                        <p class="text-center"> - </p>
                                                                                    @endif
                                                                                </td>
                                                                                <td>{{$buyerRfq->firstname}} {{$buyerRfq->lastname}}</td>
                                                                                <td>{{$buyerRfq->company_name}}</td>
                                                                                <td>{{$buyerRfq->address_line_1}}  {{$buyerRfq->address_line_2}}</td>
                                                                                <td>{{$buyerRfq->quantity}} {{$buyerRfq->units_name}}</td>
                                                                                <td class="text-start">{{$buyerRfq->rfq_status_name}}</td>
                                                                                <td class="text-end text-nowrap">
                                                                                    @php
                                                                                    $buyerRefund = getBuyerRefundAmountByOrder($groups->id,$buyerRfq->orderId,$buyerRfq->buyer_user_id);
                                                                                    @endphp
                                                                                    @if(!isset($buyerRfq->order_number))
                                                                                        <a href="javascript:void(0)" id="deletegroupmember_{{ $buyerRfq->group_members_id }}"  class=" show-icon deleteBuyerGroup" data-toggle="tooltip" data-placement="top" title="{{__('admin.delete')}}" data-bs-original-title="{{__('admin.delete')}}"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                                                    @endif
                                                                                    @if(config('app.env')!='live' && auth()->user()->role_id != $roleCheck && $groups->group_status==$groupStatusCheck && $buyerRefund===0 && !empty($buyerRfq->orderId) && in_array($buyerRfq->orderId,$groupMembersDiscounts))
                                                                                        <a href="#viewRefundModal" id=""  data-id="{{ $buyerRfq->orderId }}" class="show-icon getBuyerRefundDetails" data-toggle="tooltip" data-bs-toggle="modal" data-placement="top" title="" data-bs-original-title="{{__('admin.buyer_refund')}}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                                                    @endif
                                                                                    @if($buyerRefund>0)
                                                                                        <span class="badge badge-pill badge-success">
                                                                                            {{__('admin.refund_done')}}
                                                                                        </span>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row">
                                <div class="col-md-12 pb-2">
                                    <div class="activityopen"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- RFQ Modal Popup -->
    <div class="modal version2 fade" id="viewRfqModal" tabindex="-1" role="dialog" aria-labelledby="viewRfqModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Quote Modal Popup -->
    <div class="modal version2 fade" id="viewRfqModalnew" tabindex="-1" role="dialog" aria-labelledby="viewRfqModalnewLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Quote Modal Popup -->
    <div class="modal version2 fade" id="viewOrderModalnew" tabindex="-1"  role="dialog" aria-labelledby="viewOrderModalnewLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Add Buyer Popup -->
    <div class="modal version2 fade show" id="addBuyerModal" tabindex="-1" role="dialog" aria-labelledby="addBuyerModalLabel" aria-modal="true" style="padding-right: 17px;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header py-3">
                    <h5 class="modal-title d-flex align-items-center" id="staticBackdropLabel"><img class="pe-2" height="24px" src="{{URL::asset("front-assets/images/icons/order_detail_title.png")}}" alt="View RFQ"> Add Buyer
                    </h5>

                    <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close">
                        <img src="{{ URL::asset("front-assets/images/icons/times.png")}}" alt="Close">
                    </button>
                </div>
                <div class="modal-body p-3">
                    <div id="viewQuoteDetailBlock">
                        <div class="row align-items-stretch">
                            <div class="col-md-12 pb-2">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center">
                                        <h5>
                                            <img src="{{URL::asset("front-assets/images/icons/comment-alt-edit.png")}}" alt="RFQ Detail " class="pe-2"> Buyer List
                                        </h5>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="row rfqform_view bg-white">
                                            <div class="mt-2 newtable_v2">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">
                                                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                                            </th>
                                                            <th scope="col">RFQ Number</th>
                                                            <th scope="col">Company Name</th>
                                                            <th scope="col">Customer Name</th>
                                                            <th scope="col">Customer Address</th>
                                                            <th scope="col">Quantity</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                                            </td>
                                                            <td class=""><a href="#" class="text-decoration-none">BRFQ-34</a>
                                                            <td>PT. inovasi pangan nusantara</td>
                                                            <td>Liquide Bond</td>
                                                            <td>INOVASI PANGAN NUSANTARA INOVASI</td>
                                                            </td>
                                                            <td>100 tons</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                                            </td>
                                                            <td class=""><a href="#" class="text-decoration-none">BRFQ-34</a>
                                                            <td>PT. inovasi pangan nusantara</td>
                                                            <td>Liquide Bond</td>
                                                            <td>INOVASI PANGAN NUSANTARA INOVASI</td>
                                                            </td>
                                                            <td>100 tons</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                                            </td>
                                                            <td class=""><a href="#" class="text-decoration-none">BRFQ-34</a>
                                                            <td>PT. inovasi pangan nusantara</td>
                                                            <td>Liquide Bond</td>
                                                            <td>INOVASI PANGAN NUSANTARA INOVASI</td>
                                                            </td>
                                                            <td>100 tons</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-3">
                    <a class="btn btn-primary" data-bs-dismiss="modal">Add</a>
                    <a class="btn btn-cancel" data-bs-dismiss="modal">Cancel</a>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Buyer Refund Popup -->
    <div class="modal version2 fade" id="viewRefundModal" tabindex="-1"  role="dialog" aria-labelledby="viewRefundModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!-- End -->

    <script>
        var groupId = {{ $groups->id }};
        //for check supplier change categories option value set
        var last_category = -1;
        //top index use for check value null or not
        var top_index = '{{ count($productDetailsMultiple)??0 }}';
        //get multiple image and count
        var ajaxImageCount = 0;
        // for use last maximum qty is less then the maxorder qty
        var lastMaxQtyVal = 0;
        // for use store all image file array
        var allImageFile = new DataTransfer();


        $(document).ready(function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
            //select to call when no buyer join in group
            var buyerRfqsCount = '{{$buyerRfqsCount}}';
            if(buyerRfqsCount == 0){
                $('#supplier').select2();
                //for check supplier change categories option value set
                $('#supplier').on('select2:selecting', function (evt) {
                    last_category = $('#supplier').val();
                });
                $('#category').select2();
                $('#subcategory').select2();
                $('#product_name').select2();
            }
            //date start
            var date = new Date();
            date.setDate(date.getDate() + 1);
            // console.log('date '+date + 1);
            $('#date').datepicker({
                startDate: date,
                format: 'dd-mm-yyyy'
            });
            $('#date').on('changeDate', function (ev) {
                $(this).datepicker('hide');
                $('#exp_date').parsley().reset();
            });
            // date end

            $('#add_tags').tagsInput({
                'height': '44px',
                'width': 'auto',
                'interactive': true,
                'removeWithBackspace': true,
                'placeholderColor': '#242635'
            });

            //onchange price - change discount amount
            $("#price").keyup(function () {
                priceChange();
            });

            //onchange discount% - change discount amount
            $(".discountVal").keyup(function () {
                var id = $(this).attr("id");
                var replace_id = id.replace('discount', '');
                $('#showErrDiscount'+replace_id).html('');
                setDiscountAmtVal($(this));
            });

            var customFinishBtn = $("<button></button>")
                .text("Finish")
                .addClass("btn btn-info hidden smartWizFinishBtn")
                .on("click", function () {
                    $(".smartWizFinishBtn").addClass("disabled");
                    var url = "{{ route('groups-list') }}";
                    //window.open(url);
                    window.location.replace(url);
                });

            // SmartWizard initialize
            $("#group-steps").smartWizard({
                selected: 0, // Initial selected step, 0 = first step
                theme: "dots", // theme for the wizard, related css need to include for other than default theme
                autoAdjustHeight: false,
                justified: true,
                backButtonSupport: true,
                enableURLhash: false,
                toolbarSettings: {
                    toolbarPosition: "bottom", // none, top, bottom, both
                    toolbarButtonPosition: "right", // left, right, center
                    showNextButton: true, // show/hide a Next button
                    showPreviousButton: true, // show/hide a Previous button
                    toolbarExtraButtons: [
                        customFinishBtn
                    ], // Extra buttons to show on toolbar, array of jQuery input/buttons elements
                },
                anchorSettings: {
                    anchorClickable: true, // Enable/Disable anchor navigation
                    enableAllAnchors: true, // Activates all anchors clickable all times
                    markDoneStep: true, // Add done state on navigation
                    markAllPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
                    removeDoneStepOnNavigateBack: false, // While navigate back done step after active step will be cleared
                    enableAnchorOnDoneStep: true // Enable/Disable the done steps navigation
                },
                keyboardSettings: {
                    keyNavigation: false, // Enable/Disable keyboard navigation(left and right keys are used if enabled)
                    keyLeft: [37], // Left key code
                    keyRight: [39] // Right key code
                },
            });

            $("#group-steps").on(
                "leaveStep",
                function (
                    e,
                    anchorObject,
                    currentStepIndex,
                    nextStepIndex,
                    stepDirection
                ) {
                    if (nextStepIndex == 0) {
                        $(".smartWizFinishBtn").addClass("hidden");
                    } else if (nextStepIndex == 2) {
                        //getXenBalance(groupId);
                        //buyer details
                    }
                    //$('#group-steps').smartWizard("stepState", [2], "disable");
                    if (currentStepIndex == 0) {
                        // $('#groupedit').parsley().on('form:validate', function () {
                        //     tinymce.triggerSave();
                        // });

                        if ($('#groupedit').parsley().validate()) {
                            //@ekta 27-040-------start checking for order max qty is equal to max qty
                            var checkMaxOrderQty = $("#max_order_quantity").val();
                            var lastMaxQty = 0 ;
                            var lastMaxQtyID = '';
                            var replace_id = '';
                            var data = $('.cloanMaxQty');
                            $.each(data, function(index, value) {
                                var isLastElement = index == data.length -1;
                                if (isLastElement) {
                                    lastMaxQty = $(this).val();
                                    lastMaxQtyID = $(this).attr('id');
                                }
                            });
                            replace_id = lastMaxQtyID.replace('max_qty', '');
                            var lastDiscountVal = $("#discount" + replace_id).val();
                            if(checkMaxOrderQty != lastMaxQty){
                                var err = '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">{{ __('admin.maximum_quantity_must_be_equal_to_maximum_order_quantity') }}</li></ul>'
                                // replace_id = lastMaxQtyID.replace('max_qty', '');
                                $('#showErr' + replace_id).html(err);
                                $("#"+lastMaxQtyID).val('');
                                return false;
                            }
                            if(lastDiscountVal==''){
                                var err = '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">This value is required.</li></ul>'
                                $('#showErrDiscount' + replace_id).html(err);
                                return false;
                            }
                            //end checking
                            updateGroup();
                            imagePreviewAll(groupId)
                            // $(".downloadbtn").removeClass("hide");
                            $(".smartWizFinishBtn").removeClass("hidden");
                            return true;
                        } else {
                            return false;
                        }
                    } else if (currentStepIndex == 1) {
                        if(allImageFile.items.length > 0){
                            e.preventDefault();
                            allImageFile = new DataTransfer();
                        }
                        var file = $('input[type="file"]').val().trim();
                        if (file) {
                            swal({
                                title: "Warning",
                                icon: "/assets/images/warn.png",
                                text: "Are you sure to Upload image",
                                buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.yes') }}'],
                            })
                            .then((willDelete) => {
                                if (willDelete) {
                                    var $fileUpload = $("input[type='file']");
                                    var totalImageCount = parseInt($fileUpload.get(0).files.length)+ajaxImageCount;
                                    if (totalImageCount > 5){
                                        swal({
                                            text: "{{ __('admin.maximum_five_images_upload') }}",
                                            icon: "/assets/images/warn.png",
                                            buttons: ('{{ __('admin.cancel') }}'),
                                            dangerMode: true,
                                        })
                                        //remove opacity class
                                    }else{
                                        uploadMultipleImages();
                                    }
                                    //uploadMultipleImages();
                                }
                            });
                            $(".smartWizFinishBtn").removeClass("hidden");
                            return true;
                        } else {
                            return true;
                        }
                    } else {
                        return true;
                    }
                }
            );

            //show images in div @ekta
            $('#image-group_image').on('change', function () {//alread user uploaded 5 images they cant select more
                if(ajaxImageCount == 5){
                    swal({
                        title: "Sorry",
                        text: "{{ __('admin.maximum_five_images_upload') }}",
                        icon: "/assets/images/warn.png",
                        buttons: ('{{ __('admin.cancel') }}'),
                        dangerMode: true,
                    })
                }else{
                    //check File API supported browser
                    if (window.File && window.FileReader && window.FileList && window.Blob) {
                        $('#uploadImages').removeClass('d-none');
                        //when new image upload set old image opacity
                        $('#lightgallery').addClass('opacity-25');
                        $('.delete_all').addClass('d-none');
                        //$('.remove-preview-image').addClass('d-none');

                        var data = $(this)[0].files; //this file data
                        var product_dynamic_id = 0;
                        $.each(data, function (index, file) { //loop though each file
                            //console.log(file);
                            if (/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)) { //check supported file type
                                var fRead = new FileReader(); //new filereader
                                fRead.onload = (function (file) { //trigger function on successful read
                                    return function (e) {
                                        let img = $('<img/>').addClass('thumb').attr('src', e.target.result); //create image element

                                        let elm = '<div class="m-1 text-center bg-light pip" ' +
                                            'id="pip_' + product_dynamic_id + '" data-name="'+file.name+'">' +
                                            '<a href="javascript:void(0)" class="text-dacoration-none text-white bg-danger py-1 px-2 remove-preview-image" ' +
                                            'style="position: absolute; top: 0; right: 0;line-height: 1; z-index: 1">' +
                                            '<i class="fa fa-close"></i> ' +
                                            '</a> <img src="' + e.target.result + '" class="thumb">' +
                                            '</div>';
                                        $('#show-images').append(elm); //append image to output element
                                        product_dynamic_id++;

                                        allImageFile.items.add(file);
                                    };
                                })(file);
                                fRead.readAsDataURL(file); //URL representing the file's data.
                            }
                        });
                    } else {
                        swal({
                            text: "Your browser doesn't support File",
                            buttons: ('{{ __('admin.cancel') }}'),
                        })
                    }
                }
            });

            $('#groupMemberTable').DataTable({
                "order": [
                    // [0, "desc"]
                ],
                "aLengthMenu": [
                    [10, 20, 50, -1],
                    [10, 20, 50, "All"]
                ],
                "iDisplayLength": 10,
                "columnDefs": [{
                    //"targets": [0],
                    "visible": false,
                    "searchable": false
                },]
            });
        });

        //delete append preview image
        $(document).on("click", ".remove-preview-image",function(){
            $(this).parent(".pip").remove();

            /**begin: Remove input files on delete**/
            let images  = $('input[type="file"]')[0];
            let name    = $(this).parent(".pip").attr('data-name');
            let attempt = 1;

            images.files = allImageFile.files;
            for (let i = 0; i < allImageFile.items.length; i++) {
                if (name === allImageFile.items[i].getAsFile().name && attempt == 1) {
                    allImageFile.items.remove(i);
                    attempt = 0;

                    continue;
                }
            }
            if(allImageFile.items.length == 0){
                $('#uploadImages').addClass('d-none');
            }
            $('input[type="file"]')[0].files = allImageFile.files;
            /**end: Remove input files on delete**/
        });

        //delete get database images remove
        $(document).on("click", ".delete_all", function (e) {
            var delteImageId = $(this).attr('data-id');
            var senddata = {
                id: delteImageId
            }
            $.ajax({
                url: "{{ route('group-images-delete') }}",
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: senddata,
                success: function (successData) {
                    if (successData.success == true) {
                        // if (successData.groupId) {
                        //     groupId = successData.groupId;
                        imagePreviewAll(groupId);
                        resetToastPosition();
                        $.toast({
                            heading: "{{ __('admin.success') }}",
                            text: "{{ __('admin.image_deleted') }}",
                            showHideTransition: "slide",
                            icon: "success",
                            loaderBg: "#f96868",
                            position: "top-right",
                        });
                    }
                },
                error: function () {
                    console.log("error");
                },
            });

        });

        //delete discount range cloan
        $('body').on('click', '.removeRange', function () {
            // var delSpanId = '';
            // var delIndex = $(this).attr('id');
            // var delIndex = $(this).parents('div').attr('id').replace('deleteBtn', '');

            //for get previous row index
            var delteId = $(this).attr('id');
            var delIndex = delteId.split("deleteBtn");
            $("#max_qty" + (delIndex[1] - 1)).attr("readonly", false);
            $("#discount" + (delIndex[1] - 1)).attr("readonly", false);
            $("#deleteBtn" + (delIndex[1] - 1)).removeClass('d-none');

            $(this).closest('.productDiv').remove();

            if ($("#cloneDiv select:last").length == 0) {
                $("#min_qty").attr("readonly", false);
                $("#max_qty").attr("readonly", false);
                $("#discount").attr("readonly", false);
            }
        });

        //upload button onclick groupimages
        $(document).on("click", "#uploadImages", function (e) {
            var $fileUpload = $("input[type='file']");
            var totalImageCount = parseInt($fileUpload.get(0).files.length)+ajaxImageCount;
            //if (parseInt($fileUpload.get(0).files.length) > 5){
            if (totalImageCount > 5){
                swal({
                    title: "Sorry",
                    text: "{{ __('admin.maximum_five_images_upload') }}",
                    icon: "/assets/images/warn.png",
                    buttons: ('{{ __('admin.cancel') }}'),
                    dangerMode: true,
                })
                //remove opacity class
            }else{
                uploadMultipleImages();
            }
        });

        //onclick rfq number open rfq details popup
        $(document).on('click', '.viewRfqDetail', function(e) {
            // $('#callRFQHistory').html('');
            // $('#message').html('');
            e.preventDefault();
            var id = $(this).attr('data-id');
            if (id) {
                $.ajax({
                    url: "{{ route('rfq-detail', '') }}" + "/" + id,
                    type: 'GET',
                    success: function(successData) {
                        $("#viewRfqModal").find(".modal-content").html(successData.rfqview);
                        $('#viewRfqModal').modal('show');
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        });

        //On click show quote details in popup (Ronak M - 28-04-2022)
        $(document).on('click', '.vieQuoteDetail', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            if (id) {
                $.ajax({
                    url: "{{ route('quote-detail', '') }}" + "/" + id,
                    type: 'GET',
                    success: function(successData) {
                        $('#viewRfqModalnew').find('.modal-content').html(successData.quoteHTML);
                        $('#viewRfqModalnew').modal('show');
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        });

        //On click show order details in popup (Ronak M - 02-05-2022)
        $(document).on('click', '.getSingleOrderDetail', function() {
            var orderId = $(this).attr('data-id');
            $.ajax({
                url: "{{ route('get-single-order-detail-ajax', '') }}" + "/" + orderId,
                type: 'GET',
                success: function(successData) {
                    if (successData.html) {
                        $('#viewOrderModalnew').find('.modal-content').html(successData.html);
                        $('#viewOrderModalnew').modal('show');
                    }
                },
                error: function() {
                    console.log('error');
                }
            });
        });

        //On click show buyer refund in popup (Munir M - 10-06-2022)
        $(document).on('click', '.getBuyerRefundDetails', function() {
            var orderId = $(this).attr('data-id');
            $.ajax({
                url: "{{ route('get-buyer-refund-detail-ajax', '') }}" + "/" + orderId,
                type: 'GET',
                success: function(successData) {
                    if (successData.html) {
                        $('#viewRefundModal').find('.modal-content').html(successData.html);
                        $('#viewRefundModal').modal('show');
                    }
                },
                error: function() {
                    console.log('error');
                }
            });
        });

        $(document).on('click', '.deleteBuyerGroup', function () {
            var id = $(this).attr('id').split("_")[1];
            swal({
                title: "{{  __('admin.categories_delete_alert') }}",
                text: "{{  __('admin.categories_delete_alert_text') }}",
                icon: "/assets/images/warn.png",
                buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {

                        var _token = $("input[name='_token']").val();
                        var senddata = {
                            id: id,
                            _token: _token
                        }
                        $.ajax({
                            url: '{{ route('group-member-delete') }}',
                            type: 'POST',
                            data: senddata,
                            success: function (successData) {
                                console.log(successData);
                                if(successData.success == false) {
                                    new PNotify({
                                        text: "{{ __('admin.buyer_place_rfq') }}",
                                        type: 'success',
                                        styling: 'bootstrap3',
                                        animateSpeed: 'fast',
                                        delay: 2000
                                    });
                                }else{
                                    new PNotify({
                                        text: "{{ __('admin.buyer_removed') }}",
                                        type: 'success',
                                        styling: 'bootstrap3',
                                        animateSpeed: 'fast',
                                        delay: 1000
                                    });
                                }
                                setInterval(function() {
                                    // window.location=('/admin/products');
                                    location.reload();
                                }, 2000);
                            },
                            error: function () {
                                console.log('error');
                            }
                        });

                    }
                });

        });

        //update groups
        function updateGroup(groupId) {
            var id = groupId;
            var formData = new FormData($("#groupedit")[0]);
            //formData.append("remove_products_ids", remove_products_ids);
            $.ajax({
                url: "{{ route('group-update') }}",
                data: formData,
                type: "POST",
                contentType: false,
                processData: false,
                success: function (successData) {
                    if (successData.groupId) {
                        resetToastPosition();
                        $.toast({
                            heading: "{{ __('admin.success') }}",
                            text: "{{ __('admin.group_updated') }}",
                            showHideTransition: "slide",
                            icon: "success",
                            loaderBg: "#f96868",
                            position: "top-right",
                        });
                    }
                },
                error: function () {
                    console.log("error");
                },
            });
        }

        //upload  groupimages
        function uploadMultipleImages() {
            var formData = new FormData($("#groupImageEdit")[0]);
            var file = $('input[type="file"]').length;
            //console.log(file);
            $.ajax({
                url: "{{ route('group-update-images') }}",
                data: formData,
                type: "POST",
                contentType: false,
                processData: false,
                success: function (successData) {
                    if (successData.groupId) {
                        groupId = successData.groupId;
                        imagePreviewAll(groupId);

                        resetToastPosition();
                        $.toast({
                            heading: "{{ __('admin.success') }}",
                            text: "{{ __('admin.group_image_uploaded') }}",
                            showHideTransition: "slide",
                            icon: "success",
                            loaderBg: "#f96868",
                            position: "top-right",

                        });
                        $("#groupImageEdit")[0].reset();
                        $('#show-images').html('');
                        $('#uploadImages').addClass('d-none');
                        //when new image upload set old image opacity
                        $('#lightgallery').removeClass('opacity-25');
                        $('.delete_all').addClass('d-none');
                        //$('.remove-preview-image').removeClass('d-none');
                    }
                },
                error: function () {
                    console.log("error");
                },
            });

        }

        //ajax get all database images
        function imagePreviewAll(groupId) {
            $.ajax({
                url: "{{ route('get-groups-images-ajax', '') }}" + "/" + groupId,
                type: "GET",
                success: function (successData) {
                    //console.log(successData);
                    ajaxImageCount = successData.storeImageCount;
                    if(ajaxImageCount >= 5){
                        $('#image-group_image').prop("disabled", true);
                    }else{
                        $('#image-group_image').prop("disabled", false);
                    }
                    $("#lightgallery").html('');

                    for (var i = 0; i < successData.groupImages.length; i++) {
                        let idx = successData.groupImages[i].id;
                        let images = successData.groupImages[i].image;
                        let imgPath = '{{ url('storage/')}}'+'/'+images;

                        //console.log(idx);

                        $("#lightgallery").append("<div id='preview_images_"+idx+"' class='position-relative m-1 d-inline-block'> " +
                            "<div class='input-group-text bg-white p-0 border-0'> " +
                            "<a href='javascript:void(0)' class='text-dacoration-none text-white bg-danger py-1 px-2 delete_all' value=" + idx + " data-id= " + idx + " name='group_images[]'> "+
                            "<i class='fa fa-close'></i></a>" +
                            // "<input class='form-check-input mt-0 tocheck' type='checkbox' value=" + idx + " data-id= " + idx + " name='group_images[]' aria-label=''> " +
                            "</div> " +
                            "<div class='lightgallery_img item' data-src='img/1.jpg'> " +
                            "<img src='"+imgPath+"' alt='image small'> " +
                            "</div> " +
                            "</div>" );
                    }
                },
                // src="http://127.0.0.1:8000/storage/uploads/group_image/04klwn1TkW_1649051109group_image_steel_img2.jpg"
                error: function () {
                    console.log("error");
                },
            });

        }

        //supplier categories
        function supplierProductCategoryChange(supplierId) {
            if(($('#supplier').val()) != last_category){
                swal({
                    title: "{{__('admin.designation_delete_alert')}}",
                    text: "{{ __('admin.change_supplier_alert') }}",
                    icon: "/assets/images/warn.png",
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        //set value null on supplier change;
                        $("#subcategory").html('').trigger("change"); $("#product_name").html('').trigger("change"); $("#unit_name").html('').trigger("change");
                        //$(".resetFeild").html('').trigger("change");
                        $(".unitVal").val(null).trigger("change");
                        $(".resetFeild").val('');
                        $(".resetFeildSubCat").val('');
                        $("#group_margin").val('');
                        $("#product_description").val('');
                        $("#min_qty #max_qty #discount").attr("readonly", false);
                        $('#showErrMaxOrder').html('');

                        $.ajax({
                            url: "{{ route('get-supplier-category-ajax', '') }}" + "/" + supplierId,
                            type: "GET",
                            success: function (successData) {
                                $('#group_margin_error').html('');
                                $("#group_margin").val(successData.groupMargine);
                                var options = "<option value='-1' selected disabled>{{ __('admin.select_category') }}</option>";
                                if (successData.supplierProductCategory.length) {
                                    successData.supplierProductCategory.forEach(function (data) {
                                        options += '<option value="' + data.id + '" data-text="' + data.name + '">' + data.name + "</option>";
                                    });
                                }
                                $("#category").empty().append(options);
                            },
                            error: function () {
                                console.log("error");
                            },
                        });
                    }else{
                        if($('#supplier').val() != last_category) {
                            $('#supplier').val(last_category).trigger("change");
                        }
                    }
                });
            }
        }

        //categories on change subcategories
        function supplierCategoryChange(categoryId) {
            //var categoryId = value;
            $("#subcategory").html('').trigger("change");
            $("#product_name").html('').trigger("change");
            //reset range effect
            $("#cloneDiv").html('');
            // #price , #min_order_quantity , #max_order_quantity , #group_margin  // #min_qty  #max_qty  #unit #discount #discount_price
            $(".resetFeild").val('');
            $(".resetFeildSubCat").val('');
            $("#product_description").val('');
            $("#min_qty").attr("readonly", false); $("#max_qty").attr("readonly", false); $("#discount").attr("readonly", false);
            $('#showErrMaxOrder').html('');
            $.ajax({
                url: "{{ route('get-subcategory-ajax', '') }}" + "/" + categoryId,
                type: "GET",
                success: function (successData) {
                    var options = "<option selected disabled>{{ __('admin.select_sub_category') }}</option>";
                    if (successData.subCategory.length) {
                        successData.subCategory.forEach(function (data) {
                            options += '<option value="' + data.id + '" data-text="' + data.name + '">' + data.name + "</option>";
                        });
                    }
                    $("#subcategory").empty().append(options);
                },
                error: function () {
                    console.log("error");
                },
            });
        }

        // subcategories on change product
        function supplierSubCategoryChange(subCategoryId) {
            $("#product_name").html('').trigger("change");
            //reset range effect
            $("#cloneDiv").html('');
            // #price , #min_order_quantity , #max_order_quantity , #group_margin  // #min_qty  #max_qty  #unit #discount #discount_price
            $(".resetFeild").val('');
            $("#product_description").val('');
            $("#min_qty").attr("readonly", false); $("#max_qty").attr("readonly", false); $("#discount").attr("readonly", false);
            $('#showErrMaxOrder').html('');

            if (subCategoryId) {
                var supplierId = $("#supplier").val();
                var url = "{{ route('get-supplier-product-list-ajax', [':subCategoryId', ':supplierId']) }}";
                url = url.replace(":subCategoryId", subCategoryId);
                url = url.replace(":supplierId", supplierId);
                $.ajax({
                    url:url,  //url: "{{ route('get-brand-grade-product-ajax', '') }}" + "/" + subCategoryId,
                    type: "GET",
                    success: function (successData) {
                        var product = "";
                        var productDiscription = "";

                        if (successData.products.length > 0) {
                            var productArray = [];
                            var productDescriptionArray = [];
                            product = '<option selected disabled>{{ __('admin.select_product_name') }}</option>';
                            successData.products.forEach(function (data) {
                                if (!productArray.includes(data.name)) {
                                    productArray.push(data.name);
                                    product += '<option value="' + data.id + '" data-text="' + data.name + '" >' + data.name + '</option>';
                                }

                            });
                            $("#product_name").empty().append(product);
                        } else {
                            $("#supplierProduct").empty().append(
                                '<option selected disabled>{{ __('admin.select_product_name') }}</option>'
                            );
                        }
                    },
                    error: function () {
                        console.log("error");
                    },
                });
            }

        }

        //unit change set all clone column unit
        function unitChange(index) {
            var unitID = $('#groupedit #unit_name').val();
            if (unitID) {
                $(".unitVal").val(unitID);
            }
        }

        function cloneDiv() {
            var index = '';
            if ($("#cloneDiv select:last").length == 0) {
                index = $("#cloneDiv select").length + 1;
            } else {
                index = $("#cloneDiv select").length + 1;
            }
            //check first range
            if (($('#min_qty').val() != '') && ($('#max_qty').val() != '') && ($('#discount').val() != '')) {
                //check all previous range
                $('#showErrDiscount' + top_index).html('');
                if (($('#min_qty' + top_index).val() != '') && ($('#max_qty' + top_index).val() != '') && ($('#discount' + top_index).val() != '')) {
                    var maxOrderDays  = parseInt($('#max_order_quantity').val());
                    if(index == 1){
                        lastMaxQtyVal = $("#max_qty").val();
                    }else{
                        lastMaxQtyVal = $("#max_qty" + (index - 1)).val();
                    }
                    //for check lastMaxQtyVal is less then the  maxOrderQty
                    if(maxOrderDays > lastMaxQtyVal){
                        $("#max_order_quantity").removeClass('border-danger');
                        if(index == 1){
                            $("#max_qty").removeClass('border-danger');
                        }else{
                            $("#max_qty" + (index - 1)).removeClass('border-danger');
                        }
                        groupClone(index);
                    }else{
                        //if both qty is equal then highlight
                        $("#max_order_quantity").addClass('border-danger');
                        var err = '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">You have reached to maximum quantity.</li></ul>'
                        $('#showErrMaxOrder').html(err);
                        if(index == 1){
                            $("#max_qty").addClass('border-danger');
                        }else {
                            $("#max_qty" + (index - 1)).addClass('border-danger');
                        }
                    }
                }else{
                    //empty textvalue show error message
                    var err = '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">This value is required.</li></ul>'
                    if($('#max_qty' + top_index).val() == ''){
                        $('#showErr' + top_index).html(err);
                    }
                    if($('#discount' + top_index).val() == ''){
                        $('#showErrDiscount' + top_index).html(err);
                    }
                }
            }else{
                $("#min_qty").parsley().validate();
                $("#max_qty").parsley().validate();
                $("#discount").parsley().validate();
            }
            // + all div set unit
            unitChange(index);
        }

        function groupClone(index) {
            var id = '';
            if (index == 1) {
                id = '#max_qty';
                $("#min_qty").attr("readonly", true);
                $("#max_qty").attr("readonly", true);
                $("#discount").attr("readonly", true);
            } else {
                id = '#max_qty' + (index - 1);
                $("#min_qty" + index).attr("readonly", true);
                $("#max_qty" + (index - 1)).attr("readonly", true);
                $("#discount" + (index - 1)).attr("readonly", true);
                $("#deleteBtn" + (index - 1)).addClass('d-none');
            }
            var $clone = $("#group_productDiv").clone(true);
            top_index = index;
            // var $clone = $("#mainDiv").clone(true);
            $clone.find("#s_id").attr("id", "s_id" + index).val(0);
            $clone.find("#custom_add").attr("id", "custom_add" + index).val(1);
            $clone.find("#min_qty").attr("id", "min_qty" + index).val(parseInt($(id).val()) + 1 || 0);
            $clone.find("#max_qty").attr("id", "max_qty" + index).attr("readonly", false).val('');
            $clone.find("#showErr").attr("id", "showErr" + index).val('');
            $clone.find("#showErrMin").attr("id", "showErrMin" + index).val('');
            $clone.find("#showErrDiscount").attr("id", "showErrDiscount" + index).val('');
            $clone.find("#unit").attr("id", "unit" + index).val('');
            $clone.find("#discount").attr("id", "discount" + index).attr("readonly", false).val('');
            $clone.find("#discount_price").attr("id", "discount_price" + index).val('');
            $clone.find("#deleteBtn").attr("id", "deleteBtn" + index).val('');
            $clone.find('.min_qty').attr('for', 'min_qty' + index);
            $clone.find('.max_qty').attr('for', 'max_qty' + index);
            $clone.find('.unit').attr('for', 'unit' + index);
            $clone.find('.discount').attr('for', 'discount' + index);
            $clone.find('.discount_price').attr('for', 'discount_price' + index);
            $clone.find('.deleteRange').removeClass('d-none');
            $clone.find('.deleteRange').show();
            $clone.appendTo($("#cloneDiv"));
            //for next cloan readonly prev
        }

        //check min qty is less then max qty
        function minQty(data) {
            //check minimum order qty is lessthen of maximum order qty
            var id = data.id;
            var replace_id = id.replace('min_qty', '');
            $('#showErrMin' + replace_id).html('');
            var minDays  = parseInt($('#min_qty' + replace_id).val());
            var maxDay = parseInt($('#max_qty' + replace_id).val());
            if (maxDay != '' && (maxDay < minDays)) {
                var err = '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">{{ __('admin.minimum_quantity_must_be_less_then_maximum_quantity') }}</li></ul>'
                $('#min_qty' + replace_id).val('');
                $('#showErrMin' + replace_id).html(err);
            }

            //check min_order_qty and min_qty equal to or not
            var minOrderDays  = parseInt($('#min_order_quantity').val());
            //console.log(minOrderDays);
            if(minOrderDays != '' && (minOrderDays != minDays)){
                var err = '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">{{ __('admin.minimum_order_quantity_must_be_equal_to_minimum_quantity') }}</li></ul>'
                $('#min_qty' + replace_id).val('');
                $('#showErrMin' + replace_id).html(err);
            }
        }

        //check max qty is bigger then min qty
        function maxQty(data) {
            var id = data.id;
            var replace_id = id.replace('max_qty', '');
            $('#showErr' + replace_id).html('');
            $('#showErrMaxOrder').html('');
            $("#max_order_quantity").removeClass('border-danger');
            $('#max_qty' + replace_id).removeClass('border-danger');
            var maxDay = parseInt($('#max_qty' + replace_id).val());
            var minDays = parseInt($('#min_qty' + replace_id).val());
            if (maxDay <= minDays) {
                var err = '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">{{ __('admin.maximum_quantity_must_be_higher_then_minimum_quantity') }}</li></ul>'
                $('#showErr' + replace_id).html(err);
                $('#max_qty' + replace_id).val('');
            }

            //check max_order_qty is equal oe less then max_qty
            var maxOrderDays  = parseInt($('#max_order_quantity').val());
            if (maxOrderDays !='' && (maxOrderDays < maxDay)) {
                var err = '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">{{ __('admin.maximum_quantity_must_be_equal_to_maximum_order_quantity') }}</li></ul>'
                $('#showErr' + replace_id).html(err);
                $('#max_qty' + replace_id).val('');
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

        function priceChange() {
            if ($('.discountVal').length) {
                $('.discountVal').each(function () {
                    if ($(this).val()) {
                        setDiscountAmtVal($(this));
                    }
                });
            }else{
                setDiscountAmtVal(0);
            }
        }

        function setDiscountAmtVal(selector) {
            let row = selector.closest('.row.productDiv');
            let value = parseFloat(row.find('.discountVal').val()?row.find('.discountVal').val():0);
            let price = parseFloat($('#price').val()?$('#price').val():0);
            let discountAmount = 0;
            discountAmount = price - ((price * value) / 100);
            if(isNaN(discountAmount)) {
                discountAmount = 0;
            }
            row.find('.discountAmount').val(Math.round(discountAmount));
        }

        if ($("#mainDiv #group_productDiv").length == 1) {
            $("#mainDiv #group_productDiv").find('.deleteRange').hide();
        }

        $('#showErr').html('');
        $('#showErrMin').html('');
        $('#showErrMinOrder').html('');
        $('#showErrMaxOrder').html('');
        $('#showErrDiscount').html('');

        //check minqty is equal to minorderqty
        function minOrderQty(data) {
            //check minimum order qty is lessthen of maximum order qty
            $('#showErrMinOrder').html('');
            var minOrderDays  = parseInt($('#min_order_quantity').val());
            var maxOrderDay = parseInt($('#max_order_quantity').val());
            if (maxOrderDay != '' && (maxOrderDay < minOrderDays)) {
                var err = '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">{{ __('admin.minimum_order_quantity_must_be_less_then_maximum_order_quantity') }}</li></ul>'
                $('#min_order_quantity').val('');
                $('#showErrMinOrder').html(err);
            }
            //after added min qty in discount range change min order qty
            $("#cloneDiv").html('');
            //if minimum order quantity change set minimum quantity
            $("#min_qty").val(minOrderDays?minOrderDays:'');
            $("#max_qty").val('');
            $("#discount").val('');
            $("#discount_price").val(0);
            $("#min_qty").attr("readonly", false);
            $("#max_qty").attr("readonly", false);
            $("#discount").attr("readonly", false);
            //remove parslay validation
            $('#min_qty').parsley().reset();
            $('#showErrMin').html('');
        }

        //if maxorderqty is changed to set and remove all clondiv
        function maxOrderQty(data) {
            $('#showErrMaxOrder').html('');
            $("#max_order_quantity").removeClass('border-danger');

            var maxOrderDays = parseInt($('#max_order_quantity').val());
            var minOrderDays = parseInt($('#min_order_quantity').val());
            if (maxOrderDays <= minOrderDays) {
                var err = '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">{{ __('admin.maximum_order_quantity_must_be_higher_then_minimum_order_quantity') }}</li></ul>'
                $('#showErrMaxOrder').html(err);
                $('#max_order_quantity').val('');
            }else {
                if ($('.cloanMinQty').length) {
                    $('.cloanMinQty').each(function () {
                        if (($(this).val()) > maxOrderDays) {
                            //console.log($(this).val());
                            $(this).parent().parent('.productDiv').remove();
                            $('#max_qty' + (($('.cloanMinQty').length) - 1)).val(maxOrderDays?maxOrderDays:'');
                            $('#max_qty' + (($('.cloanMinQty').length) - 1)).attr("readonly", false);
                            $('#discount' + (($('.cloanMinQty').length) - 1)).attr("readonly", false);
                            if ($('.cloanMinQty').length == 1) {
                                $('#max_qty').val(maxOrderDays?maxOrderDays:'');
                                $('#max_qty').attr("readonly", false);
                                $('#discount').attr("readonly", false);
                            }
                        }else{
                            // max order qty is grater them min qty then set same range max qty
                            $('#max_qty' + (($('.cloanMinQty').length) - 1)).val(maxOrderDays?maxOrderDays:'');
                            if ($('.cloanMinQty').length == 1) {
                                $('#max_qty').val(maxOrderDays?maxOrderDays:'');
                                $('#max_qty').attr("readonly", false);
                                $('#discount').attr("readonly", false);
                            }
                        }
                    });
                }
            }
            //remove parslay validation
            $('#max_qty').parsley().reset();
            $('#showErr').html('');
        }

        //activitiess
        $('#profile-tab').click( function() {
            var groupid = $(this).attr('data-groupid');
            $.ajax({
                url: "{{ route('admin-get-group-activity-ajax', '') }}" + "/" + groupid,
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

        //Check Group name is already exist or not (Ronak M - 23/05/22)
        function duplicateGroupName(element) {
            var groupName = $(element).val();
            if(groupName != $("#grpName").val()) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('check-groupname-exist-ajax') }}",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {groupName : groupName},
                    dataType: "json",
                    success: function(result) {
                        if(result.exists) {
                            $("#duplicateErr").html("Group name is already exist");
                            $(".sw-btn-next").prop('disabled',true);
                        } else {
                            $("#duplicateErr").html("");
                            $(".sw-btn-next").prop('disabled',false);
                        }
                    },
                    error: function (jqXHR, exception) {
                        console.log("Something Went Wrong");
                    }
                });
            }
        }

    </script>

    <link rel="stylesheet" href="{{ URL::asset('/assets/vendors/lightgallery/css/lightgallery.min.css') }}">
    <script src="{{ URL::asset('/assets/vendors/lightgallery/js/lightgallery-all.js') }}"></script>

    <script>
        (function ($) {
            'use strict';
            if ($("#lightgallery").length) {
                $("#lightgallery").lightGallery(
                    {
                        selector: '.item',
                    });
            }
        })(jQuery);
    </script>
@stop
