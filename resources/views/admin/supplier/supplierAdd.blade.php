@extends('admin/adminLayout')

@section('content')
    <style>
        .downArrowIcon {
            float: right !important;
            /* margin-top: -30px; */
            margin-right: 5px !important;
            color: #000;
            pointer-events: none !important;
            background-color: #fff !important;
            padding-right: 5px !important;
            position: absolute !important;
            right: 0 !important;
            top: 40px !important;
        }

        .parsley-errors-list+.downArrowIcon {
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

        .hide,
        .hidden {
            display: none;
        }

        #supplier-steps ul {
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

        #supplier-steps ul:before {
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
        .contact_step .icon .pen_icon.supplier{ background-image: url(../../front-assets/images/icons/people-carry-1_white.png); background-repeat: no-repeat; background-position: center center;}
        .contact_step .icon .pen_icon.product{ background-image: url(../../front-assets/images/icons/boxes_white.png);background-repeat: no-repeat; background-position: center center;}
        .contact_step .icon .pen_icon.bank{background-image: url(../../front-assets/images/icons/icon_bank_white.png);background-repeat: no-repeat; background-position: center center;}
        .nav-link.done .pen_icon {
            border: 2px solid #fff;
            background-image:  url(../../front-assets/images/check_icon.png) no-repeat center center;
            background-color: #7cc576;
        }
        .nav-link.done .pen_icon.supplier, .nav-link.done .pen_icon.product, .nav-link.done .pen_icon.bank {
            border: 2px solid #fff;
            background-image:  url(../../front-assets/images/check_icon.png) no-repeat center center;
            background-color: #7cc576;
        }
        .nav-link.active .pen_icon {
            border: 2px solid #fff;
            background-color: #09f;
        }

        .sw-theme-dots>.nav .nav-link::after {
            content: '';
            height: 3px;
            background: #dae1e5;
            width: 255px;
            position: absolute;
            right: -100%;
            top: 55%
        }

        .sw-theme-dots>.nav .nav-link:first-child::after {
            background: #dae1e5;
        }

        .sw-theme-dots>.nav .nav-link.active:after {
            background: #369ede;
        }

        .sw-theme-dots>.nav .nav-link::before {
            display: none;
        }

        .hidden {
            display: none !important;
        }

        .sw-theme-dots>.nav li:last-child .nav-link:first-child::after {
            display: none;
        }

        .contact_step .icon {
            z-index: 100 !important;
        }

        .d-none {
            display: none;
        }
        /* tag input */
        div.tagsinput{ width: auto; border-color: #dee2e6; padding: 5px;}
        div.tagsinput span.tag{ margin-bottom: 1px; margin-top: 2px;}
        div.tagsinput input{    padding: 2px 5px; margin: 0 5px 2px 0;}

        .form-switch .form-check-input {
            margin-left: 2.5em;

        }

        .table td,
        th {
            text-align: left;
        }

        .table td img {
            width: 20px;
            height: 20px;
            border-radius: 0px;
        }

        .modal-header {
            background-color: #13193a;
            color: #fff;
        }
        .modal-body {
            background-color: #ebedf1;
        }

        .modal-lg {
            max-width: 900px;
        }

        .form-control {
            padding: 0.7rem 0.375rem;
        }
        .modal-body {
            background-color: #ebedf1;
        }

        .modal-lg {
            max-width: 900px;
        }

        .form-control {
            padding: 0.7rem 0.375rem;
        }
        .upload_btn{
            background-color: rgb(37, 55, 139);
            color: white;
            border-radius: 0.3rem;
            padding: 3px 7px;
            cursor: pointer;
        }
        .parsley-errors-list li {
            font-weight: normal;
        }
        #parsley-id-32 {
            margin-left: 70px !important;
        }
    </style>
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    @php
        $checkNotAdminRole = (Auth::user()->role_id == \App\Models\Role::ADMIN)? 0 : 1;
    @endphp
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="row">
                <div class="col-md-12 d-flex align-items-center mb-3">
                    @if (Auth::user())
                        <h1 class="mb-0 h3">{{ __('admin.supplier') }}</h1>
                        <a href="{{ route('admin.supplier.index') }}" class="mb-2 backurl ms-auto btn-close"></a>
                    @endif
                    @if (Session::get('status'))
                        <p class="suplier-succ"> {{ Session::get('status') }}</p>
                    @endif
                </div>
                <div class="col-12">
                    <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">{{ __('admin.add_supplier') }}</button>
                        </li>
                    </ul>
                    <div class="tab-content pt-3 pb-0 px-0" id="myTabContent">
                        <div id="supplier-steps">
                            <ul class="nav col-md-6 contact_step">
                                <li>
                                    <a class="nav-link" href="#step-1">
                                        {{ __('admin.supplier') }}
                                        <div class="icon">
                                            <i class="pen_icon supplier"></i>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link" href="#step-2">
                                        {{ __('admin.product') }}<div class="icon">
                                            <i class="pen_icon product"></i>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link" href="#step-3">
                                        {{ __('admin.account_details') }}<div class="icon">
                                        <!-- <img src="{{ URL('assets/icons/bank.png') }}" height="35px" width="30px" alt=""> -->
                                            <i class="pen_icon bank"></i>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content pt-3 pb-0">
                                <div id="step-1" class="tab-pane p-0" role="tabpanel" style="display: none;">
                                    <form class="" id="supplierAddForm" method="POST"
                                          enctype="multipart/form-data" action="{{ route('supplier-create') }}"
                                          data-parsley-validate>
                                        @csrf

                                        <div class="row">
                                            <div class="col-md-12 mb-2">
                                                <div class="card">
                                                    <div class="card-header d-flex align-items-center">
                                                        <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/icon_company.png')}}" alt="Company" class="pe-2"> <span>{{ __('admin.company_details') }}</span></h5>
                                                    </div>
                                                    <div class="card-body p-3 pb-1">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="name" class="form-label">{{ __('admin.company_name') }} <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" id="name" name="name" required>
                                                            </div>

                                                            <div class="col-md-6 mb-3">
                                                                <label for="logo" class="form-label">{{ __('admin.company_logo') }}</label>
                                                                {{--<input type="file" class="form-control file-upload-info" id="logo" name="logo"  data-parsley-max-file-size="2">
                                                                <span class="removeFile hide" id="logoFile" data-id="logo">
                                                                    <i class="fa fa-trash-o"></i>
                                                                </span>--}}

                                                                <div class="d-flex">
                                                                    <span class=""><input type="file" name="logo" id="logo" accept="image/*" onchange="show(this)" hidden/><label id="upload_btn text-white" class="upload_btn text-white" for="logo">{{ __('admin.browse') }}</label></span>
                                                                    <div id="file-logo">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3 mb-3">
                                                                <label for="email" class="form-label">{{ __('admin.company_email') }} @if($checkNotAdminRole)<span class="text-danger">*</span>@endif </label>
                                                                <input type="email" class="form-control" id="email" name="email" @if($checkNotAdminRole) required @endif />
                                                            </div>

                                                            <div class="col-md-3 mb-3">
                                                                <label for="mobile" class="form-label">{{ __('admin.company_mobile') }} @if($checkNotAdminRole)<span class="text-danger">*</span> @endif </label>
                                                                <input type="text" class="form-control" id="mobile" name="mobile" @if($checkNotAdminRole) required @endif data-parsley-type="digits"
                                                                       data-parsley-length="[9, 16]" data-parsley-length-message="It should be between 9 and 16 digit." placeholder="XXXXXXXXXXX">
                                                            </div>

                                                            <div class="col-md-3 mb-3">
                                                                <label for="website" class="form-label">{{ __('admin.company_website') }}</label>
                                                                <input type="text" class="form-control" id="website" name="website">
                                                            </div>

                                                            <div class="col-md-3 mb-3">
                                                                <label for="interested_in" class="form-label">{{ __('admin.dealing_with_categories') }}</label>
                                                                <input type="text" class="form-control" id="interested_in" name="interested_in"
                                                                       value="">
                                                            </div>
                                                            {{--@if(auth()->user()->role_id == 3)--}}
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="name" class="form-label">{{ __('admin.nib') }} @if($checkNotAdminRole)<span class="text-danger">*</span> @endif </label>

                                                                    <input type="text" class="form-control" id="nib" name="nib" value="" @if($checkNotAdminRole) required @endif data-parsley-type="digits"
                                                                           data-parsley-length="[13, 13]" data-parsley-length-message="Value should be 13 digits only.">

                                                                </div>
                                                            {{--@else
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="name" class="form-label">{{ __('admin.nib') }} </label>

                                                                    <input type="text" class="form-control" id="nib" name="nib" value=""  data-parsley-type="digits"
                                                                           data-parsley-length="[13, 13]" data-parsley-length-message="Value should be 13 digits only.">

                                                                </div>
                                                            @endif--}}
                                                            <div class="col-md-3 mb-3">
                                                                <label for=""  class="form-label">{{ __('admin.nib_file') }} </label>
                                                                <div class="d-flex">
                                                        <span class=""><input type="file" name="nib_file" id="nib_file" accept=".jpg,.png,.gif,.jpeg, .pdf" onchange="show(this)" hidden/>
                                                        <label class="upload_btn text-white" for="nib_file">{{ __('admin.browse') }}</label></span>
                                                                    <div id="file-nib_file">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                           {{-- @if(auth()->user()->role_id == 3)--}}
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="name" class="form-label">{{ __('admin.npwp') }}  @if($checkNotAdminRole) <span class="text-danger">*</span> @endif </label>

                                                                    <input type="text" class="form-control" id="npwp" name="npwp" value="" placeholder="11.222.333.4-555.666" @if($checkNotAdminRole) required @endif
                                                                           data-parsley-pattern="^(\d{2})*[.]{1}(\d{3})*[.]{1}(\d{3})*[.]{1}(\d{1})*[-]{1}(\d{3})*[.]{1}(\d{3})*$" data-parsley-minlength="20" data-parsley-maxlength="20">
                                                                </div>
                                                            {{--@else
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="name" class="form-label">{{ __('admin.npwp') }} </label>

                                                                    <input type="text" class="form-control" id="npwp" name="npwp" value="" placeholder="11.222.333.4-555.666"
                                                                           data-parsley-pattern="^(\d{2})*[.]{1}(\d{3})*[.]{1}(\d{3})*[.]{1}(\d{1})*[-]{1}(\d{3})*[.]{1}(\d{3})*$" data-parsley-minlength="20" data-parsley-maxlength="20">
                                                                </div>
                                                            @endif--}}

                                                            <div class="col-md-3 mb-3">
                                                                <label for=""  class="form-label">{{ __('admin.npwp_file') }} </label>
                                                                <div class="d-flex">
                                                    <span class=""><input type="file" name="npwp_file" class="form-control" id="npwp_file" accept=".jpg,.png,.gif,.jpeg, .pdf" onchange="show(this)" hidden/>
                                                        <label class="upload_btn text-white" for="npwp_file">{{ __('admin.browse') }}</label></span>
                                                                    <div id="file-npwp_file">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">
                                                                {{ __('admin.company_type') }}
                                                                </label>
                                                                <div class="d-flex ms-2">
                                                                    <div class="form-check ms-4">
                                                                        <input class="form-check-input non-pkp-btn"
                                                                            type="radio"
                                                                            name="companyType"
                                                                            id="exampleRadios2"
                                                                            value="2"
                                                                            >
                                                                        <label class="form-check-label"
                                                                            for="exampleRadios2">
                                                                            NON-PKP
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check ms-4">
                                                                        <input class="form-check-input pkp-btn" type="radio" name="companyType" id="exampleRadios1" value="1">
                                                                        <label class="form-check-label" for="exampleRadios1"> PKP
                                                                        </label>
                                                                    </div>
                                                                    <div class="uploadbutton ms-2 my-2 d-flex" id="upload">
                                                                            <span class="">
                                                                            <input type="file" name="pkp_file" class="form-control pkp-btn-non" id="pkp-doc" accept=".jpg,.png,.jpeg,.pdf" onchange="show(this)" hidden="">

                                                                            <span class="brows-lable"><label style="display:none; z-index: 1;" class="upload_btn pkp_doc_option" for="pkp-doc">Browse</label></span>
                                                                            </span>

                                                                            <input type="hidden" class="form-control" id="oldpkp_file" name="oldpkp_file">
                                                                            <div id="file-pkp_file">
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3 mb-3">
                                                                <label for="group_margin" class="form-label">Group Margin % @if($checkNotAdminRole) <span class="text-danger">*</span> @endif </label>
                                                                <input type="text" class="form-control" id="group_margin" name="group_margin" value="0" @if($checkNotAdminRole) required @endif onkeypress="return isNumberKey(this, event);">
                                                            </div>


                                                            <div class="col-12 mb-3">
                                                                <label for="address" class="form-label">{{ __('admin.company_address') }} @if($checkNotAdminRole)<span class="text-danger">*</span>@endif</label>
                                                                <textarea name="address" class="form-control newtextarea" id="address" cols="30" rows="3"
                                                                          @if($checkNotAdminRole) required @endif data-parsley-errors-container="#address_error"></textarea>
                                                                <div id="address_error"></div>
                                                                {{-- <input type="text" class="form-control" id="address" name="address" required> --}}
                                                            </div>

                                                            <div class="col-12 mb-3">
                                                                <label for="description" class="form-label">{{ __('admin.company_description') }}</label>
                                                                <textarea name="description" class="form-control newtextarea" id="description" cols="30"
                                                                          rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-2">
                                                <div class="card">
                                                    <div class="card-header d-flex align-items-center">
                                                        <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/people-carry-1.png')}}" alt="Contact" class="pe-2"> <span>{{ __('admin.contact_details') }}</span></h5>
                                                    </div>
                                                    <div class="card-body p-3 pb-1">
                                                        <div class="row">

                                                            <div class="col-md-3 mb-3 error_per_ab">
                                                                <label for="contactPersonName" class="form-label">Contact Person First Name
                                                                    @if($checkNotAdminRole) <span class="text-danger">*</span> @endif </label>
                                                                <div class="d-flex">
                                                                    <select name="salutation" class="form-select w100p border-end-0" id="inputGroupSelect01" style="border-radius: 0px;  background-color: rgba(0, 0, 0, 0.05);">
                                                                        <option value="1">{{__('admin.salutation_mr')}}</option>
                                                                        <option value="2">{{__('admin.salutation_ms')}}</option>
                                                                        <option value="3">{{__('admin.salutation_mrs')}}</option>
                                                                    </select>
                                                                    <input type="text" name="contactPersonName" id="contactPersonName"
                                                                           class="form-control" value="" @if($checkNotAdminRole) required @endif data-parsley-errors-container="startTimeErrorContainer">
                                                                </div>
                                                                <div id="startTimeErrorContainer"></div>
                                                            </div>

                                                            <div class="col-md-3 mb-3 ">
                                                                <label for="contactPersonLastName" class="form-label">{{ __('admin.contact_person_name') }}
                                                                    @if($checkNotAdminRole) <span class="text-danger">*</span> @endif </label>
                                                                <input type="text" name="contactPersonLastName" id="contactPersonLastName"
                                                                       class="form-control" @if($checkNotAdminRole) required @endif>
                                                            </div>
                                                            <div class="col-md-3 mb-3">
                                                                <label for="contactPersonEmail" class="form-label">{{ __('admin.contact_person_email') }}
                                                                    <span class="text-danger">*</span> </label>
                                                                <input type="email" name="contactPersonEmail" id="contactPersonEmail"
                                                                       class="form-control" required data-parsley-notequalto="#alternate_email" data-parsley-notequalto-message="{{ __('admin.not_equal_email') }}">
                                                            </div>
                                                            <div class="col-md-3 mb-3">
                                                                <label for="contactPersonEmail" class="form-label">{{ __('admin.alternate_email') }}
                                                                </label>
                                                                <input type="email" name="alternate_email" id="alternate_email"
                                                                       class="form-control" data-parsley-notequalto="#contactPersonEmail" data-parsley-notequalto-message="{{ __('admin.not_equal_email') }}">
                                                            </div>
                                                            <div class="col-md-3 mb-3">
                                                                <label for="contactPersonMobile" class="form-label">{{ __('admin.contact_person_phone') }}
                                                                    @if($checkNotAdminRole) <span class="text-danger">*</span> @endif </label>
                                                                <input type="text" class="form-control" id="contactPersonMobile"
                                                                       name="contactPersonMobile" data-parsley-type="digits"
                                                                       data-parsley-length="[9, 16]" @if($checkNotAdminRole) required @endif data-parsley-length-message="It should be between 9 and 16 digit." placeholder="XXXXXXXXXXX">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-2">
                                                <div class="card">
                                                    <div class="card-header d-flex align-items-center">
                                                        <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/attachement.png')}}" alt="Charges" class="pe-2"> <span>{{ __('admin.attachment') }}</span></h5>
                                                    </div>
                                                    <div class="card-body p-3 pb-1">
                                                        <div class="row">
                                                            <div class="col-md-3 mb-3">
                                                                <label for="catalog" class="form-label">{{ __('admin.catalog') }}</label>
                                                                {{--<input type="file" class="form-control file-upload-info" id="catalog" name="catalog"
                                                                    accept="application/msword,application/pdf, application/vnd.ms-excel,.xlsx,.xls,.doc,.docx,.pdf"
                                                                    data-parsley-max-file-size="10">
                                                                <span class="removeFile hide" id="catalogFile" data-id="catalog">
                                                                    <i class="fa fa-trash-o"></i>
                                                                </span>--}}
                                                                <div class="d-flex">
                                                                    <span class=""><input type="file" name="catalog" id="catalog" accept="image/*, application/pdf" onchange="show(this)" hidden/><label id="upload_btn" for="catalog">{{ __('admin.browse') }}</label></span>
                                                                    <div id="file-catalog">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3  mb-3">
                                                                <label for="pricing" class="form-label">{{ __('admin.pricing') }}</label>
                                                                {{--<input type="file" class="form-control file-upload-info" id="pricing" name="pricing"
                                                                    accept="application/msword,application/pdf, application/vnd.ms-excel,.xlsx,.xls,.doc,.docx,.pdf"
                                                                    data-parsley-max-file-size="10">
                                                                <span class="removeFile hide" id="pricingFile" data-id="pricing">
                                                                    <i class="fa fa-trash-o"></i>
                                                                </span>--}}
                                                                <div class="d-flex">
                                                                    <span class=""><input type="file" name="pricing" id="pricing" accept="image/*, application/pdf" onchange="show(this)" hidden/><label id="upload_btn" for="pricing">{{ __('admin.browse') }}</label></span>
                                                                    <div id="file-pricing">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3  mb-3">
                                                                <label for="product" class="form-label">{{ __('admin.product') }}</label>
                                                                {{--<input type="file" class="form-control file-upload-info" id="product" name="product"
                                                                    accept="application/msword,application/pdf, application/vnd.ms-excel,.xlsx,.xls,.doc,.docx,.pdf"
                                                                    data-parsley-max-file-size="10">
                                                                <span class="removeFile hide" id="productFile" data-id="product">
                                                                    <i class="fa fa-trash-o"></i>
                                                                </span>--}}
                                                                <div class="d-flex">
                                                                    <span class=""><input type="file" name="product" id="product" accept="image/*, application/pdf" onchange="show(this)" hidden/><label id="upload_btn" for="product">{{ __('admin.browse') }}</label></span>
                                                                    <div id="file-product">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3  mb-3">
                                                                <label for="commercialCondition" class="form-label">{{ __('admin.commercial_conditions') }}</label>
                                                                {{--<input type="file" class="form-control file-upload-info" id="commercialCondition"
                                                                    name="commercialCondition"
                                                                    accept="application/msword,application/pdf, application/vnd.ms-excel,.xlsx,.xls,.doc,.docx,.pdf"
                                                                    data-parsley-max-file-size="10">
                                                                <span class="removeFile hide" id="commercialConditionFile"
                                                                    data-id="commercialCondition">
                                                                    <i class="fa fa-trash-o"></i>
                                                                </span>--}}
                                                                <div class="d-flex">
                                                                    <span class=""><input type="file" name="commercialCondition" id="commercialCondition" accept="image/*, application/pdf" onchange="show(this)" hidden/><label id="upload_btn" for="commercialCondition">{{ __('admin.browse') }}</label></span>
                                                                    <div id="file-commercialCondition">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            @if (Auth::user())
                                                                <div class="col-12">
                                                                    <div class="form-check form-check-flat form-check-primary">
                                                                        <label class="form-check-label ms-4">
                                                                            <input type="checkbox" disabled checked class="form-check-input"
                                                                                   name="terms" id="terms"
                                                                                   data-parsley-error-message="Please agree to the Terms and Conditions"
                                                                                   required>{{ __('admin.agree') }}
                                                                            <i class="input-helper"></i><a href="javascript:void(0)" id="showtc">{{ __('admin.terms_and_conditions') }}</a></label>

                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="col-12">
                                                                    <div class="form-check form-check-flat form-check-primary">
                                                                        <label class="form-check-label">
                                                                            <input type="checkbox" class="form-check-input" name="terms" id="terms"
                                                                                   data-parsley-error-message="Please agree to the Terms and Conditions"
                                                                                   required>
                                                                            {{ __('admin.agree') }}
                                                                            <i class="input-helper"></i><a href="javascript:void(0)" id="showtc">{{ __('admin.terms_and_conditions') }}</a></label>

                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>











                                    </form>
                                </div>
                                <div id="step-2" class="tab-pane p-0" role="tabpanel" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <div class="card">
                                                <div class="card-header d-flex align-items-center">
                                                    <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/credit-card.png')}}" alt="Charges" class="pe-2"> <span>{{ __('admin.product_details') }}</span></h5>
                                                </div>
                                                <div class="card-body p-3 pb-1">
                                                    <div class="row">
                                                        <div class="d-flex align-items-center justify-content-end">
                                                            <div class="pe-1 mb-3 clearfix">
                                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">{{ __('admin.add') }}</button>
                                                            </div>
                                                        </div>
                                                        <div class="collapse" id="tableFilters">
                                                            <div class="card card-body pb-0 mb-3 pt-3">
                                                                <div class="row" id="filterOptions">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 mb-3 newtable_v2 ">
                                                            <div class="table-responsive">
                                                                <table id="supplierProductTable" class="table border table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>{{ __('admin.category') }}</th>
                                                                        <th>{{ __('admin.sub_category') }}</th>
                                                                        <th>{{ __('admin.product') }}</th>
                                                                        <th class="text-center">{{ __('admin.actions') }}</th>
                                                                    </tr>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="step-3" class="tab-pane p-0" role="tabpanel" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <div class="card">
                                                <div class="card-header d-flex align-items-center">
                                                    <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/credit-card.png')}}" alt="Charges" class="pe-2"> <span>{{ __('admin.bank_details') }}</span></h5>
                                                </div>
                                                <div class="card-body p-3 pb-1">
                                                    <div class="row">
                                                        <div class="d-flex align-items-center justify-content-end">
                                                            <div class="pe-1 mb-3 clearfix">
                                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bankdetail">{{ __('admin.add') }}</button>
                                                            </div>
                                                        </div>
                                                        <div class="collapse" id="tableFilters">
                                                            <div class="card card-body pb-0 mb-3 pt-3">
                                                                <div class="row" id="filterOptions">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 mb-3 newtable_v2 ">
                                                            <div class="table-responsive">
                                                                <table id="supplierBankTable" class="table" style="width: 100%;">
                                                                    <thead>
                                                                    <tr class="bg-light">
                                                                        {{--<th>ID</th>--}}
                                                                        <th>{{ __('admin.bank_logo') }}</th>
                                                                        <th>{{ __('admin.bank_name') }}</th>
                                                                        <th>{{ __('admin.bank_code') }}</th>
                                                                        <th>{{ __('admin.bank_account_name') }}</th>
                                                                        <th>{{ __('admin.bank_account_number') }}</th>
                                                                        <th>{{ __('admin.primary_secondary') }}</th>
                                                                        <th class="text-center">{{ __('admin.actions') }}</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody class="bg-white">

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
                            </div>
                        </div>
                    </div>

                <!-- <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                @if (Auth::user())
                    <div class="mb-2">
                        < !-- <a href="{{ route('supplier-list') }}" class="mb-2"><i class="fa fa-arrow-circle-left"
                                aria-hidden="true"></i> Back</a> -- >
                        <a href="{{ route('supplier-list') }}" class="mb-2" style="float:right;"><i class="fa fa-times"
                                aria-hidden="true"></i></a>
                    </div>
                @endif
                    <h3 class="card-title">Add Supplier</h3>
@if (Session::get('status'))
                    <p class="suplier-succ"> {{ Session::get('status') }}</p>
                @endif


                    </div>
                </div>
            </div> -->
                </div>
            </div>
        </div>
    </div>

    {{-- Stpe-2 --}}
    <div class="modal error_res fade version2" id="addProductModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProductLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-0">
                <div class="modal-header py-3">
                    <h5 class="modal-title" id="addProductLabel" style="color: white;">{{ __('admin.product') }}</h5>
                    <button type="button" class="btn-close ms-0 d-flex resetModel" data-bs-dismiss="modal" aria-label="Close">
                        <img src="{{ URL('front-assets/images/icons/times.png') }}" alt="Close">
                    </button>
                </div>
                <div class="modal-body p-3">
                    <div class="">
                        <form id="supplierProductForm" enctype="multipart/form-data" class="form-group row g-3" data-parsley-validate>
                            @csrf
                            <div class="col-md-12 pb-2">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center">
                                        <h5 class="mb-0 d-flex align-items-center"><img src="{{ URL('front-assets/images/icons/shopping-cart.png') }}" alt="Order Details" class="pe-2"> {{ __('admin.category_details') }}</h5>
                                    </div>
                                    <div class="card-body p-3 pb-1">
                                        <div class="row rfqform_view bg-white">
                                            <div class="d-flex pb-2">
                                                <div class="col-md-12">
                                                    <div class="search_rfq position-relative">
                                                        <label class="form-label" for="Search_category">{{__('admin.search_product')}}</label>
                                                        <input type="text" name="searchProductCategory" id="searchProductCategory" class="form-control categorysearch" placeholder="{{__('admin.product_search')}}" />
                                                        <ul class="list-group searchGroup" id="searchGroup"></ul>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="search_rfq mx-2">
                                                        <label class="form-label" for="Search_category" style="left: 10px;">{{__('admin.search_product')}}</label>
                                                        <input type="text" name="tags" id="tags" class="form-control " placeholder="{{__('admin.product_search')}}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 ">
                                                    <label for="supplierCategory" class="form-label">{{ __('admin.category') }}<span class="text-danger">*</span></label>
                                                    <div class="position-relative">
                                                        <select name="supplierCategory" id="supplierCategory" class="form-control selectBox" required>
                                                            <option selected disabled>{{__('admin.select_category')}}</option>
                                                            @foreach ($category as $categoryItem)
                                                                <option value="{{ $categoryItem->id }}"
                                                                        data-text="{{ $categoryItem->name }}">
                                                                    {{ $categoryItem->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i class="fa fa-chevron-down downArrowIcon"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 ps-3">
                                                    <label for="supplierSubCategory" class="form-label">{{ __('admin.sub_category') }}<span class="text-danger">*</span></label>
                                                    <div class="position-relative">
                                                        <select name="supplierSubCategory" id="supplierSubCategory"
                                                                class="form-control selectBox" required>
                                                            <option selected disabled>{{__('admin.select_product_sub_category')}}</option>
                                                        </select>
                                                        <i class="fa fa-chevron-down downArrowIcon"></i>
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
                                        <h5 class="mb-0 d-flex align-items-center"><img height="20px" src="{{ URL('front-assets/images/icons/people-carry-1.png') }}"  alt="Supplier Details" class="pe-2"> {{ __('admin.product_detail') }}</h5>
                                    </div>
                                    <div class="card-body p-3 pb-1">
                                        <div class="row rfqform_view bg-white">
                                            <div class="d-flex pb-2">
                                                <div class="col-md-4">
                                                    <label for="productRef" class="form-label">{{ __('admin.product_code_specification') }}<span class="text-danger">*</span></label>
                                                    <input type="text" name="productRef" id="productRef" class="form-control">
                                                </div>
                                                <div class="col-md-4 ps-3" id="">
                                                    <label for="supplierProduct" class="form-label">{{ __('admin.product_name') }}<span class="text-danger">*</span></label>
                                                    <div class="position-relative">
                                                        <select name="supplierProduct" id="supplierProduct" class="form-control selectBox" required>
                                                            <option selected disabled>{{__('admin.select_product_name')}}</option>
                                                        </select>
                                                        <i class="fa fa-chevron-down downArrowIcon"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 ps-3">
                                                    <label class="form-label">{{ __('admin.upload_product_images') }}</label>
                                                    <div class="d-flex text-center" style="align-items: center;">
                                                        <span class=""><input type="file" name="productImages" id="productImages" accept="image/*" onchange="show(this)" hidden/><label id="upload_btn" for="productImages">{{ __('admin.browse') }}</label></span>
                                                        <div id="file-productImages">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 pb-2" id="">
                                                <label for="supplierProductDiscription" class="form-label">{{ __('admin.product_description') }}<span class="text-danger">*</span></label>
                                                <input name="supplierProductDiscription" id="supplierProductDiscription" class="form-control" required type="text" list="supplierProductDiscriptionList" />
                                                <datalist id="supplierProductDiscriptionList">
                                                    @foreach ($products->unique('description') as $productsItem)
                                                        <option>{{ $productsItem->description }}</option>
                                                    @endforeach
                                                </datalist>
                                            </div>
                                            <div class="d-flex pb-2">
                                                <div class="col-md-6" id="brandBlock">
                                                    <label for="supplierProductBrand" class="form-label">{{ __('admin.brand') }}({{ __('admin.comma_seprated') }})</label>
                                                    <input data-list="{{ $brand }}" name="supplierProductBrand" id="supplierProductBrand" data-multiple class="form-control" />
                                                </div>
                                                <div class="col-md-6 ps-3" id="gradeBlock">
                                                    <label for="supplierProductGrade" class="form-label">{{ __('admin.grade') }}({{ __('admin.comma_seprated') }})</label>
                                                    <input data-list="{{ $grade }}" name="supplierProductGrade" id="supplierProductGrade" data-multiple class="form-control" />
                                                </div>
                                            </div>
                                            <div class="d-flex pb-2">
                                                <div class="col-md-6">
                                                    <label for="supplierProductPrice" class="form-label">{{ __('admin.price') }}<span class="text-danger">*</span></label>
                                                    <input type="text" name="supplierProductPrice" id="supplierProductPrice" class="form-control" data-parsley-type="number" onkeypress="return isNumberKey(this, event);" required>
                                                </div>
                                                <div class="col-md-6 ps-3">
                                                    <label for="supplierProductUnit" class="form-label">{{ __('admin.select_unit') }}<span class="text-danger">*</span></label>
                                                    <div class="position-relative">
                                                        <select name="supplierProductUnit" id="supplierProductUnit" class="form-control selectBox" onchange="unitChange($(this))" required>
                                                            <option selected disabled>{{__('admin.select_unit')}}</option>
                                                            @foreach ($unit as $unitItem)
                                                                <option value="{{ $unitItem->id }}">{{ $unitItem->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i class="fa fa-chevron-down downArrowIcon"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex pb-2">
                                                <div class="col-md-6">
                                                    <label for="supplierProductMinQuantity" class="form-label">{{ __('admin.minimum_order_quantity') }}<span class="text-danger">*</span></label>
                                                    <input type="text" name="supplierProductMinQuantity" id="supplierProductMinQuantity" class="form-control" data-parsley-type="number" data-parsley-minlength="1" min="1" onchange="minOrderQty(this)" onkeypress="return isNumberKey(this, event);" required>
                                                    <div id="showErrMinOrder" name="showErrMinOrder"></div>
                                                </div>
                                                <div class="col-md-6 ps-3">
                                                    <label for="supplierProductMaxQuantity" class="form-label">{{ __('admin.maximum_order_quantity') }}<span class="text-danger">*</span></label>
                                                    <input type="text" name="supplierProductMaxQuantity" id="supplierProductMaxQuantity" class="form-control" data-parsley-type="number" data-parsley-minlength="1" min="1" onchange="maxOrderQty(this)" onkeypress="return isNumberKey(this, event);" required>
                                                    <div id="showErrMaxOrder" name="showErrMaxOrder"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 pb-2">
                                {{-- <div class="col-md-12 mb-2">--}}
                                <div class="card">
                                    <div class="card-header d-flex align-items-center">
                                        <h5 class="mb-0 d-flex align-items-center"><img height="20px" src="{{URL::asset('assets/icons/boxes.png')}}" alt="Product Range" class="pe-2">
                                            <span>Product Range <span class="icon ms-1"><a href="javascript:void(0)" id="btnClone" onclick="cloneDiv()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg></a></span></span>
                                        </h5>
                                    </div>
                                    <div class="card-body p-3 pb-1" id="removeCloanDiv">
                                        <div id="mainDiv">
                                            <div class="row productDiv g-2" id="group_productDiv">
                                                <div class="col-md-2 mb-3">
                                                    <label for="min_qty" class="form-label min_qty">{{ __('admin.min_quantity') }}<span class="text-danger">*</span></label>
                                                    <input type="text" id="min_qty" name="min_qty[]" class="form-control cloanMinQty" onchange="minQty(this)" onkeypress="return isNumberKey(this, event);" required>
                                                    <div id="showErrMin" name="showErrMin[]"></div>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label for="max_qty" class="form-label max_qty">{{ __('admin.max_quantity') }}<span class="text-danger">*</span></label>
                                                    <input type="text" id="max_qty" name="max_qty[]" class="form-control cloanMaxQty" onchange="maxQty(this)" onkeypress="return isNumberKey(this, event);" required>
                                                    <div id="showErr" name="showErr[]"></div>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label for="unit" class="form-label unit">{{ __('admin.select_unit') }}</label>
                                                    <select class="form-select unitVal" id="unit" name="unit[]" disabled style="height: 40px;">
                                                        <option disabled selected>{{ __('admin.select_unit') }}</option>
                                                        @foreach ($unit as $unitItem)
                                                            <option value="{{ $unitItem->id }}">{{ $unitItem->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label for="discount" class="form-label discount">{{ __('admin.discount') }}%<span class="text-danger">*</span></label>
                                                    <input type="text" id="discount" name="discount[]" class="form-control discountVal" required onkeypress="return isNumberKey(this, event);">
                                                    <div id="showErrDiscount" name="showErrDiscount[]"></div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="discount_price" class="form-label discount">{{ __('admin.discount_price')  }}</label>
                                                    <input type="text" id="discount_price" name="discount_price[]" class="form-control discountAmount" readonly>
                                                </div>
                                                <div class="col-md-1 pt-1 mb-3 ">
                                                    <label for="" class="form-label"></label>
                                                    <div style="line-height: 38px;" id="deleteBtn" name="deleteBtn[]"><span class="icon deleteRange d-none"><a href="javascript:void(0)" id="deleteBtn" class="text-danger removeRange "><i class="fa fa-trash mt-3"></i></a></span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="cloneDiv"></div>
                                    </div>
                                </div>
                                {{--                                </div>--}}
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="AddProductData">{{ __('admin.save') }}</button>
                    <button type="button" class="btn btn-cancel resetModel" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Stpe-2 ends --}}

    {{-- Stpe-3 --}}
    <div class="modal fade version2 error_res " id="bankdetail" tabindex="-1" role="dialog" aria-labelledby="addBankLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 800px;" role="document">
            <div class="modal-content border-0">
                <div class="modal-header p-3">
                    <h3 class="modal-title" style="color: white;" id="addBankLabel">{{ __('admin.bank_details') }}</h3>
                    <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close">
                        <img src="{{ URL('front-assets/images/icons/times.png') }}" alt="Close">
                    </button>
                </div>
                <div class="modal-body p-3" style="background-color: #ebedf1;">
                    <div class="card">
                        <div class="card-header align-items-center" style="background-color: #d7d9df;">
                            <h5 class="mb-0"><img src="{{ URL('front-assets/images/icons/info.png') }}"  height="20px" alt="Bank Logo" class="pe-2"> {{ __('admin.bank_info') }}</h5>
                        </div>
                        <div class="card-body p-3 pb-1">
                            <div class="row rfqform_view">
                                <form id="supplierBankForm" enctype="multipart/form-data" class="form-group" data-parsley-validate>
                                    @csrf
                                    <input type="hidden" name="id" id="supplier-bank-id" value="0">
                                    <div class="d-flex pb-2">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">{{ __('admin.bank_name') }}<span class="text-danger">*</span></label>
                                            <div class="d-flex error_ui">
                                                <select class="form-select w-100 text-primary" name="bank_id" id="bank_id" required onchange="bankChange($(this))">
                                                    <option value="">{{__('admin.select_bank')}}</option>
                                                    @foreach($banks as $bank)
                                                        <option value="{{$bank['id']}}" data-code="{{$bank['code']??''}}" data-logo="{{$bank['logo']??''}}">{{$bank['name']}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="p-1 pe-2 "><img src="{{ URL('assets/icons/bank.png') }}" height="20px"
                                                                            width="20px" alt="bank Logo" id="bank-logo"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 ps-3  mb-2">
                                            <div class="">
                                                <label for="bank-code" class="form-label">{{ __('admin.bank_code') }} </label>
                                                <input type="text" class="form-control" id="bank-code" value="XXXXXXXX" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex pb-2">
                                        <div class="col-md-6  mb-2">
                                            <div class="error_ui">
                                                <label for="bank-name" class="form-label">{{ __('admin.bank_account_holder_name') }}<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="bank-name" name="bank_account_name" required data-parsley-pattern="^[a-zA-Z ]+$"
                                                       placeholder="John Doe">
                                            </div>
                                        </div>
                                        <div class="col-md-6 ps-3  mb-2">
                                            <div class="error_ui">
                                                <label for="bank-account" class="form-label">{{ __('admin.bank_account_number') }}<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="bank-account" name="bank_account_number" data-parsley-pattern="[0-9]+" data-parsley-length="[8, 18]" required
                                                       placeholder="XXXXXXXXXXXXXX">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 pb-2">
                                        <div class="">
                                            <label for="description"
                                                   class="form-label">{{ __('admin.description') }}</label>
                                            <textarea class="form-control" placeholder="{{ __('admin.description') }}"
                                                      id="description" name="description"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input mt-1" style="margin-left: 0px;"
                                                   type="checkbox" value="1" id="is_primary" name="is_primary" checked>
                                            <label class="form-check-label"
                                                   style="margin-left: 20px; position: relative; top: 2px;"
                                                   for="is_primary"> {{ __('admin.set_as_primary') }}
                                            </label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="saveBankData">{{ __('admin.save') }}</button>
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Stpe-3 ends --}}
    <script>
        var supplierId = '';
        var editSupplierProductId = '';

        var top_index = 0 ;
        // for use last maximum qty is less then the maxorder qty
        var lastMaxQtyVal = 0;

        $("input[name='npwp']").keyup(function() {
            let numbers = $(this).val().replace(/\D/g, '');
            if (numbers.length>15){
                $(this).val($(this).val().slice(0, 20));
                return;
            }
            let char = {0:'',2:'.',5:'.',8:'.',9:'-',12:'.'};
            let value = '';
            for (var i = 0; i < numbers.length; i++) {
                value += (char[i]||'') + numbers[i];
            }
            $(this).val(value);
        });

        function addSupplier() {
            var formData = new FormData($("#supplierAddForm")[0]);
            formData.append('address', tinyMCE.get('address').getContent());
            formData.append('description', tinyMCE.get('description').getContent());
            $.ajax({
                url: "{{ route('supplier-create-ajax') }}",
                data: formData,
                type: "POST",
                contentType: false,
                processData: false,
                success: function(successData) {
                    if (successData.supplierId) {
                        supplierId = successData.supplierId;
                        resetToastPosition();
                        $.toast({
                            heading: "{{ __('admin.success') }}",
                            text: "{{ __('admin.supplier_added_successfully') }}",
                            showHideTransition: "slide",
                            icon: "success",
                            loaderBg: "#f96868",
                            position: "top-right",
                        });
                    }
                },
                error: function() {
                    console.log("error");
                },
            });
        }

        function updateSupplier() {
            var formData = new FormData($("#supplierAddForm")[0]);
            formData.append('id', supplierId);
            formData.append('address', tinyMCE.get('address').getContent());
            formData.append('description', tinyMCE.get('description').getContent());
            $.ajax({
                url: "{{ route('supplier-update') }}",
                data: formData,
                type: "POST",
                contentType: false,
                processData: false,
                success: function(successData) {
                    if (successData.supplierId) {
                        //supplierId = successData.supplierId;
                        resetToastPosition();
                        $.toast({
                            heading: "{{ __('admin.success') }}",
                            text: "{{ __('admin.supplier_updated_successfully') }}",
                            showHideTransition: "slide",
                            icon: "success",
                            loaderBg: "#f96868",
                            position: "top-right",
                        });
                    }
                },
                error: function() {
                    console.log("error");
                },
            });
        }

        function supplierCategoryChange(categoryId,subCategoryId=null) {
            $('#supplierProductForm #productRef').val('');
            var productOptions = '<option selected disabled>*Select Product Name</option>';
            $('#supplierProductForm #supplierProduct').empty().append(productOptions);
            $('#supplierProductForm #supplierProductDiscription').val('');
            $('#supplierProductForm #supplierProductBrand').val('');
            $('#supplierProductForm #supplierProductGrade').val('');
            if(subCategoryId!=null || subCategoryId!=0)
                {
                    var select='selected="selected"';
                }
            return new Promise(resolve => {
                $.ajax({
                    url: "{{ route('get-subcategory-ajax', '') }}" +
                        "/" +
                        categoryId,
                    type: "GET",
                    success: function(successData) {
                        var options =
                            "<option selected disabled>Select Product Sub Category</option>";
                        if (successData.subCategory.length) {
                            successData.subCategory.forEach(function(data) {
                                if(data.id==subCategoryId){
                                    var selected=select;
                                }
                                else{
                                    selected='';
                                }
                                options +=
                                    '<option value="' +
                                    data.id +
                                    '" data-text="' +
                                    data.name +
                                    '"'+selected+'>' +
                                    data.name +
                                    "</option>";
                            });
                        }

                        $("#supplierSubCategory").empty().append(options);
                        // $("#supplierProductUnit").empty().append(unitoptions);
                        resolve('resolved');
                    },
                    error: function() {
                        console.log("error");
                    },
                });
            });
        }

        function supplierSubCategoryChange(subCategoryId) {
            $('#supplierProductForm #productRef').val('');
            var productOptions = '<option selected disabled>*Select Product Name</option>';
            $('#supplierProductForm #supplierProduct').empty().append(productOptions);
            $('#supplierProductForm #supplierProductDiscription').val('');
            $('#supplierProductForm #supplierProductBrand').val('');
            $('#supplierProductForm #supplierProductGrade').val('');
            if (subCategoryId) {
                return new Promise(resolve => {
                    $.ajax({
                        url: "{{ route('get-brand-grade-product-ajax', '') }}" +
                            "/" +
                            subCategoryId,
                        type: "GET",
                        success: function(successData) {
                            var brand = "";
                            var grade = "";
                            var product = "";
                            var productDiscription = "";

                            if (successData.products.length > 0) {
                                var productArray = [];
                                var productDescriptionArray = [];
                                product =
                                    '<option selected disabled>*Select Product Name</option>';
                                successData.products.forEach(function(data) {
                                    if (!productArray.includes(data.name)) {
                                        productArray.push(data.name);
                                        product += '<option value="' + data.id +
                                            '" data-text="' + data.name + '" >' + data
                                                .name + '</option>';
                                    }
                                    if (data.description) {
                                        if (!productDescriptionArray.includes(data
                                            .description)) {
                                            productDescriptionArray.push(data
                                                .description);
                                            productDiscription += '<option>' + data
                                                .description + '</option>';
                                        }
                                    }
                                });
                                $("#supplierProduct").empty().append(product);
                                $("#supplierProductDiscriptionList").empty().append(
                                    productDiscription);
                            } else {
                                $("#supplierProduct").empty().append(
                                    '<option selected disabled>*Select Product Name</option>'
                                );
                            }



                            // if (successData.brands.length > 0) {
                            //     $("#brandBlock").removeClass("hidden");
                            //     successData.brands.forEach(function(data) {
                            //         brand +=
                            //             '<option value="' +
                            //             data.id +
                            //             '" data-text="' +
                            //             data.name +
                            //             '" >' +
                            //             data.name +
                            //             "</option>";
                            //     });
                            //     $("#supplierProductBrand").empty().append(brand);
                            // } else {
                            //     $("#brandBlock").addClass("hidden");
                            // }

                            // if (successData.grades.length) {
                            //     $("#gradeBlock").removeClass("hidden");
                            //     successData.grades.forEach(function(data) {
                            //         grade +=
                            //             '<option  value="' +
                            //             data.id +
                            //             '"  data-text="' +
                            //             data.name +
                            //             '">' +
                            //             data.name +
                            //             "</option>";
                            //     });
                            //     $("#supplierProductGrade").empty().append(grade);
                            // } else {
                            //     $("#gradeBlock").addClass("hidden");
                            // }
                            resolve('resolved');
                        },
                        error: function() {
                            console.log("error");
                        },
                    });
                });
            }

        }

        async function editSupplierProductDetails(supplierProduct, supplierProductBrands, supplierProductGrades) {
            $('#supplierProductForm #supplierCategory').val(supplierProduct
                .category_id);
            // $('#supplierCategory').trigger("change");
            await supplierCategoryChange(supplierProduct.category_id);
            await getPrimaryImage(supplierProduct.id)
            $('#supplierProductForm #supplierSubCategory').val(
                supplierProduct.sub_category_id);
            await supplierSubCategoryChange(supplierProduct.sub_category_id);
            $('#supplierProductForm #supplierProduct').val(supplierProduct.product_id);
            await supplierProductChange();
            $('#supplierProductForm #supplierProductDiscription').val(supplierProduct.product_description);
            $('#supplierProductForm #supplierProductPrice').val(
                supplierProduct.price);
            $('#supplierProductForm #supplierProductMinQuantity').val(
                supplierProduct.min_quantity);
            $('#supplierProductForm #supplierProductMaxQuantity').val(
                supplierProduct.max_quantity);
            $('#supplierProductForm #supplierProductUnit').val(
                supplierProduct.quantity_unit_id);
            $('#supplierProductForm #productTerms').val(supplierProduct
                .product_terms);
            $('#supplierProductForm #productRef').val(supplierProduct
                .product_ref);
            if (supplierProduct.product_catalog) {
                $('#supplierProductForm #oldproductCataog').val(
                    supplierProduct.product_catalog);
                //$('#supplierProductForm #productCataogFile').removeClass('hide');
            }

            if (supplierProductBrands) {
                $('#supplierProductForm #supplierProductBrand').val(supplierProductBrands);
            }

            if (supplierProductGrades) {
                $('#supplierProductForm #supplierProductGrade').val(supplierProductGrades);

            }
        }

        async function supplierProductChange(params) {
            $('#supplierProductForm #supplierProductDiscription').val('');
            $('#supplierProductForm #supplierProductBrand').val('');
            $('#supplierProductForm #supplierProductGrade').val('');
        }

        function editSupplierBankDetails(data) {
            $('#supplierBankForm #supplier-bank-id').val(data.id);
            $('#supplierBankForm #bank_id').val(data.bank_id);
            $('#supplierBankForm #bank-code').val(data.code);
            $('#supplierBankForm #bank-name').val(data.bank_account_name);
            $('#supplierBankForm #bank-account').val(data.bank_account_number);
            $('#supplierBankForm #description').val(data.description);
            if(data.is_primary){
                $('#supplierBankForm #is_primary').prop('checked',true);
            }else{
                $('#supplierBankForm #is_primary').prop('checked',false);
            }
            if (data.logo) {
                $("#supplierBankForm #bank-logo").attr('src', location.origin + '/' + data.logo);
            }else{
                $("#supplierBankForm #bank-logo").attr('src', defaultBankLogo);
            }
        }

        $(document).ready(function() {
            $('#supplierBankTable').DataTable({
                "order": [
                    [0, "desc"]
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
            $('#interested_in').tagsInput({
                'height': '60%',
                'width': 'auto',
                'interactive': true,
                'removeWithBackspace': true,
                'placeholderColor': '#242635'
            });
            // $('#filterOptions').append(
            //     ' <div class="col-md-3 d-flex align-items-end "><div class="form-group mb-3"><button class="btn btn-outline-info btn-sm" id="resetFilterOptions">Reset</button></div></div>'
            // );
            var filterIndexes = [0, 1, 2];
            $('#supplierProductTable').DataTable({
                initComplete: function() {
                    this.api().columns().every(function(colIdx) {
                        if ($.inArray(colIdx, filterIndexes) != -1) {
                            var column = this;
                            var select = $(
                                '<select  class="form-control form-control-lg tableFilters" style="font-size: .8rem;" id="selectBox' +
                                this.header().textContent.replace(/\s/g, '') +
                                '"><option value="">Select ' +
                                this.header()
                                    .textContent + '</option></select> ')
                                .appendTo('#filterOptions')
                                .on('change', function() {
                                    // var val = $.fn.dataTable.util.escapeRegex(
                                    //     $(this).val()
                                    // );
                                    // column
                                    //     .search(val ? '^' + val + '$' : '', true, false)
                                    //     .draw();
                                    var val = $(this).val();
                                    column
                                        .search(val)
                                        .draw();
                                });

                            column.data().unique().sort().each(function(d, j) {
                                select.append('<option value="' + d + '">' + d +
                                    '</option>')
                            });

                            select.wrap(
                                '<div class="col-md-3"><div class="form-group mb-3"></div></div>'
                            );
                            $('<label for="selectBox' +
                                this.header().textContent + '">' + this.header()
                                    .textContent + '</label>').insertBefore(select);
                            //<div class="col-md-4"><div class="form-group">
                            // $('</div></div>').insertAfter(select);

                        }
                        var table = $('#supplierProductTable').DataTable();
                        // buildSelect(table);
                        // table.on('draw', function() {
                        //     buildSelect(table);
                        // });
                    });
                }
            });

            $('#filterOptions').append(
                ' <div class="col-md-3 d-flex align-items-end "><div class="form-group mb-3"><button class="btn btn-outline-info btn-sm" id="resetFilterOptions">Reset</button></div></div>'
            );
            $(document).on('click', '#resetFilterOptions', function() {
                $("#filterOptions").find("select").prop("selectedIndex", 0).trigger('change');
            });
            var customFinishBtn = $("<button></button>")
                .text("Finish")
                .addClass("btn btn-info hidden smartWizFinishBtn")
                .on("click", function() {
                    $(".smartWizFinishBtn").addClass("disabled");
                    var url = "{{ route('admin.supplier.index') }}";
                    //window.open(url);
                    window.location.replace(url);
                });
            // SmartWizard initialize
            $("#supplier-steps").smartWizard({
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

            $("#supplier-steps").on(
                "leaveStep",
                function(
                    e,
                    anchorObject,
                    currentStepIndex,
                    nextStepIndex,
                    stepDirection
                ) {
                    if (nextStepIndex==0) {
                        $(".smartWizFinishBtn").addClass("hidden");
                    }
                    if (currentStepIndex == 0) {
                        $("#supplierAddForm").parsley().on('form:validate', function() {
                            tinymce.triggerSave();
                        });
                        if ($('#supplierAddForm').parsley().validate()) {
                            if (supplierId) {
                                updateSupplier();
                            } else {
                                addSupplier();
                            }
                            $(".smartWizFinishBtn").removeClass("hidden");
                            return true;
                        } else {
                            $("#exampleRadios2").focus();
                            return false;
                        }
                    } else if (currentStepIndex == 1) {
                        return true;
                    } else {
                        return true;
                    }
                }
            );

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

            window.Parsley.addValidator("notequalto", {
                requirementType: "string",
                validateString: function(value, element) {
                    return value !== $(element).val();
                }
            });
            $("#showtc").click(function() {
                if ($("#supplierAddForm").parsley().isValid()) {
                    var name = $("#contactPersonName").val();
                    var companyName = $("#name").val();
                    var url =
                        "{{ route('supplier-term-and-condition', [':name', ':companyName']) }}";
                    url = url.replace(":name", name);
                    url = url.replace(":companyName", companyName);
                    window.open(url, "_blank");
                } else {
                    var url = "{{ route('supplier-term-and-condition-blank') }}";
                    window.open(url, "_blank");
                }
            });

            $(document).on("change", ".file-upload-info", function(e) {
                if ($(this).val()) {
                    var id = $(this).attr("id");
                    $("#" + id + "File").removeClass("hide");
                }
            });

            $(document).on("click", ".removeFile", function(e) {
                e.preventDefault();
                var element = $(this);
                var fileName = $(this).attr("id");
                var name = $(this).attr("data-name");
                var data = {
                    fileName: fileName,
                    filePath: $(this).attr("file-path"),
                    id: $(this).attr("data-id"),
                    _token: $('meta[name="csrf-token"]').attr("content"),
                };
                swal({
                    title: "{{ __('admin.delete_sure_alert') }}",
                    text: "{{ __('admin.department_delete_alert_text') }}",
                    icon: "/assets/images/bin.png",
                    buttons: ['Cancel', 'Delete'],
                    dangerMode: true,
                }).then((deleteit) => {
                    if (deleteit) {
                        $.ajax({
                            url: "{{ route('supplier-file-delete-ajax') }}",
                            data: data,
                            type: "POST",
                            success: function(successData) {
                                $("#file-"+ name).html('');
                            },
                            error: function() {
                                console.log("error");
                            },
                        });
                    }
                });
            });

            $(document).on("click", ".removeProductFile", function(e) {
                e.preventDefault();
                var element = $(this);
                var fileName = $(this).attr("id");
                var name = $(this).attr("data-name");
                var data = {
                    fileName: fileName,
                    filePath: $(this).attr("file-path"),
                    id: $(this).attr("data-id"),
                    _token: $('meta[name="csrf-token"]').attr("content"),
                };
                swal({
                    title: "{{ __('admin.delete_sure_alert') }}",
                    text: "{{ __('admin.department_delete_alert_text') }}",
                    icon: "/assets/images/bin.png",
                    buttons: ['Cancel', 'Delete'],
                    dangerMode: true,
                }).then((deleteit) => {
                    if (deleteit) {
                        $.ajax({
                            url: "{{ route('supplier-product-image-delete-ajax') }}",
                            data: data,
                            type: "POST",
                            success: function(successData) {
                                $("#file-"+ name).html('');
                            },
                            error: function() {
                                console.log("error");
                            },
                        });
                    }
                });
            });

            $(document).on("change", "#supplierCategory", function(e) {
                var categoryId = $(this).find(":selected").val();
                if (categoryId) {
                    supplierCategoryChange(categoryId);
                }
            });

            $(document).on("change", "#supplierSubCategory", function(e) {
                var subCategoryId = $(this).find(":selected").val();
                if (subCategoryId) {
                    supplierSubCategoryChange(subCategoryId);
                }
            });

            $(document).on("change", "#supplierProduct", function(e) {
                supplierProductChange();
            });


            $(document).on("click", "#AddProductData", function(e) {
                e.preventDefault();
                if ($("#supplierProductForm").parsley().validate()) {
                    //@ekta 27-040-------start checking for order max qty is equal to max qty
                    var checkMaxOrderQty = $("#supplierProductMaxQuantity").val();
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

                    var formData = new FormData($("#supplierProductForm")[0]);
                    formData.append("supplier_id", supplierId);
                    if (editSupplierProductId) {
                        formData.append("editSupplierProductId", editSupplierProductId);
                    }
                    $.ajax({
                        url: "{{ route('supplier-product-check-ajax') }}",
                        data: formData,
                        type: "POST",
                        contentType: false,
                        processData: false,
                        success: function(successData) {
                            if (successData.supplierProductCount == 0) {
                                var formData = new FormData($("#supplierProductForm")[0]);
                                formData.append("supplier_id", supplierId);
                                if (editSupplierProductId) {
                                    formData.append("editSupplierProductId",
                                        editSupplierProductId);
                                    $.ajax({
                                        url: "{{ route('supplier-product-update-ajax') }}",
                                        data: formData,
                                        type: "POST",
                                        contentType: false,
                                        processData: false,
                                        success: function(successData) {
                                            if (successData.success) {
                                                resetToastPosition();
                                                $.toast({
                                                    heading: "{{ __('admin.success') }}",
                                                    text: "{{ __('admin.supplier_product_updated_successfully') }}",
                                                    showHideTransition: "slide",
                                                    icon: "success",
                                                    loaderBg: "#f96868",
                                                    position: "top-right",
                                                });
                                                var supplierProduct = successData
                                                    .supplierProduct;
                                                $('#t_category_name_' +
                                                    supplierProduct.id).text($(
                                                    "#supplierProductForm #supplierCategory option:selected"
                                                ).text());

                                                $('#t_sub_category_name_' +
                                                    supplierProduct.id).text($(
                                                    "#supplierProductForm #supplierSubCategory option:selected"
                                                ).text());

                                                $('#t_product_name_' +
                                                    supplierProduct.id).text($(
                                                    "#supplierProductForm #supplierProduct option:selected"
                                                ).text());

                                                $("#addProductModal").modal("hide");
                                                $('#addProductModal').on('hidden.bs.modal', function (e) {
                                                    $(this)
                                                        .find("input,textarea,select")
                                                        .val('')
                                                        .end()
                                                        .find("input[type=checkbox], input[type=radio]")
                                                        .prop("checked", "")
                                                        .end();
                                                })
                                                $("#cloneDiv").html('');
                                                $("#min_qty").attr("readonly", false);
                                                $("#min_qty").val('');
                                                $("#max_qty").attr("readonly", false);
                                                $("#max_qty").val('');
                                                $("#discount").attr("readonly", false);
                                                $("#discount").val('');
                                                $("#discount_price").val(0);
                                            }
                                        },
                                        error: function() {
                                            console.log("error");
                                        },
                                    });
                                } else {
                                    $.ajax({
                                        url: "{{ route('supplier-product-create-ajax') }}",
                                        data: formData,
                                        type: "POST",
                                        contentType: false,
                                        processData: false,
                                        success: function(successData) {
                                            if (successData.success) {
                                                resetToastPosition();
                                                $.toast({
                                                    heading: "{{ __('admin.success') }}",
                                                    text: "{{ __('admin.supplier_product_added_successfully') }}",
                                                    showHideTransition: "slide",
                                                    icon: "success",
                                                    loaderBg: "#f96868",
                                                    position: "top-right",
                                                });
                                                var supplierProduct = successData
                                                    .supplierProduct;

                                                var tableData =
                                                    '<tr id="supplierProductDetailsRow_' +
                                                    supplierProduct.id +
                                                    '">';
                                                tableData +=
                                                    '<td id="t_category_name_' +
                                                    supplierProduct.id +
                                                    '">' +
                                                    $(
                                                        "#supplierProductForm #supplierCategory option:selected"
                                                    ).text() +
                                                    "</td>";
                                                tableData +=
                                                    '<td id="t_sub_category_name_' +
                                                    supplierProduct.id +
                                                    '">' +
                                                    $(
                                                        "#supplierProductForm #supplierSubCategory option:selected"
                                                    ).text() +
                                                    "</td>";
                                                tableData +=
                                                    '<td id="t_product_name_' +
                                                    supplierProduct.id +
                                                    '">' +
                                                    $(
                                                        "#supplierProductForm #supplierProduct option:selected"
                                                    )
                                                        .text() +
                                                    "</td>";
                                                tableData +=
                                                    '<td class="text-end text-nowrap" id="action_' +
                                                    supplierProduct.id +
                                                    '"> <a href="javascript:void(0)" data-id="' +
                                                    supplierProduct.id +
                                                    '" class="editSupplierProductDetails" ><i class="fa fa-edit"></i></a> <a href = "javascript:void(0)" data-id="' +
                                                    supplierProduct.id +
                                                    '" class = "ps-2 deleteSupplierProductDetails" > <i class="fa fa-trash"></i></a> </td>';
                                                tableData += "</tr>";

                                                $("#supplierProductTable")
                                                    .DataTable()
                                                    .row.add($(tableData))
                                                    .draw();

                                                $("#addProductModal").modal("hide");
                                                $('#addProductModal').on('hidden.bs.modal', function (e) {
                                                    $(this)
                                                        .find("input,textarea,select")
                                                        .val('')
                                                        .end()
                                                        .find("input[type=checkbox], input[type=radio]")
                                                        .prop("checked", "")
                                                        .end();
                                                })
                                                $("#cloneDiv").html('');
                                                $("#min_qty").attr("readonly", false);
                                                $("#min_qty").val('');
                                                $("#max_qty").attr("readonly", false);
                                                $("#max_qty").val('');
                                                $("#discount").attr("readonly", false);
                                                $("#discount").val('');
                                                $("#discount_price").val(0);
                                            }
                                        },
                                        error: function() {
                                            console.log("error");
                                        },
                                    });
                                }
                            } else {
                                $('#productAddedWarning').remove();
                                $('#addProductModal .modal-footer').prepend(
                                    '<p class="text-danger" id="productAddedWarning">Product already added</p>'
                                );
                            }
                        },
                        error: function() {
                            console.log("error");
                        },
                    });

                }
            });

            $(document).on("click", ".editSupplierProductDetails", function(e) {
                $('#productAddedWarning').remove();
                $("#supplierProductForm")[0].reset();
                editSupplierProductId = $(this).attr("data-id");
                if (editSupplierProductId) {
                    $.ajax({
                        url: "{{ route('get-supplier-product-ajax', '') }}" +
                            "/" +
                            editSupplierProductId,
                        type: "GET",
                        success: function(successData) {
                            if (successData.supplierProduct) {
                                var supplierProduct = successData.supplierProduct[0];
                                editSupplierProductDetails(supplierProduct, successData.supplierProductBrands, successData.supplierProductGrades);
                                top_index = (successData.spdrCount - 1);
                                $('#supplierProductForm #removeCloanDiv').html(successData.html);
                            }

                            if (successData.supplierProductImages) {
                                var supplierProductImages = successData
                                    .supplierProductImages[
                                    0];
                                if (supplierProductImages) {
                                    $('#supplierProductForm #oldproductImages')
                                        .val(
                                            supplierProductImages.image);
                                }
                            }

                            $('#AddProductData').text('Update');
                            $("#addProductModal").modal("show");

                        },
                        error: function() {
                            console.log("error");
                        },
                    });
                }
                $("#companyPortfolioFormModal").modal("show");
            });

            $(document).on('click', '.deleteSupplierProductDetails', function() {
                var id = $(this).attr("data-id");
                swal({
                    title: "{{ __('admin.delete_sure_alert') }}",
                    text: "{{ __('admin.department_delete_alert_text') }}",
                    icon: "/assets/images/bin.png",
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            // swal("Poof! Your imaginary file has been deleted!", {
                            //     icon: "success",
                            // });

                            var _token = $('meta[name="csrf-token"]').attr("content");
                            var senddata = {
                                id: id,
                                _token: _token
                            }
                            $.ajax({
                                url: "{{ route('supplier-product-delete-ajax') }}",
                                type: 'POST',
                                data: senddata,
                                success: function(successData) {
                                    $.toast({
                                        heading: '{{ __('admin.success') }}',
                                        text: '{{ __('admin.supplier_product_details_deleted_successfully') }}.',
                                        showHideTransition: 'slide',
                                        icon: 'success',
                                        loaderBg: '#f96868',
                                        position: 'top-right'
                                    });
                                    $('#supplierProductTable').DataTable()
                                        .row(
                                            "#supplierProductDetailsRow_" +
                                            id).remove()
                                        .draw();

                                },
                                error: function() {
                                    console.log('error');
                                }
                            });

                        }
                    });

            });

            $('#addProductModal').on('hide.bs.modal', function(e) {
                $('#AddProductData').text('Add');
                $("#supplierProductForm")[0].reset();
                editSupplierProductId = '';
            });

            new Awesomplete('#supplierProductBrand', {
                filter: function(text, input) {
                    return Awesomplete.FILTER_CONTAINS(text, input.match(/[^,]*$/)[0]);
                },

                item: function(text, input) {
                    return Awesomplete.ITEM(text, input.match(/[^,]*$/)[0]);
                },

                replace: function(text) {
                    var before = this.input.value.match(/^.+,\s*|/)[0];
                    this.input.value = before + text + ", ";
                }
            });

            new Awesomplete('#supplierProductGrade', {
                filter: function(text, input) {
                    return Awesomplete.FILTER_CONTAINS(text, input.match(/[^,]*$/)[0]);
                },

                item: function(text, input) {
                    return Awesomplete.ITEM(text, input.match(/[^,]*$/)[0]);
                },

                replace: function(text) {
                    var before = this.input.value.match(/^.+,\s*|/)[0];
                    this.input.value = before + text + ", ";
                }
            });


            $(document).on("click", ".editSupplierBankDetails", function(e) {
                $("#supplierBankForm")[0].reset();
                editSupplierBankId = $(this).attr("data-id");
                if (editSupplierBankId) {
                    $.ajax({
                        url: "{{ route('get-supplier-bank', '') }}" + "/" +editSupplierBankId,
                        type: "GET",
                        success: function(successData) {
                            if (successData.success) {
                                editSupplierBankDetails(successData.data);
                                $("#bankdetail").modal("show");
                            }
                        },
                        error: function() {
                            console.log("error");
                        },
                    });
                }
            });

            $('#bankdetail').on('hide.bs.modal', function(e) {
                $("#supplierBankForm")[0].reset();
                $('#supplierBankForm #supplier-bank-id').val(0);
                $("#bank-logo").attr('src',defaultBankLogo);
            });

            $(document).on("click", "#saveBankData", function(e) {
                e.preventDefault();
                if ($("#supplierBankForm").parsley().validate()) {
                    let formData = new FormData($("#supplierBankForm")[0]);
                    formData.append("supplier_id", supplierId);
                    addUpdateSupplierBank(formData);
                }
            });
            $(document).on('click', '.deletebankdetails', function() {
                var id = $(this).attr("data-id");
                swal({
                    title: "{{ __('admin.delete_sure_alert') }}",
                    text: "{{ __('admin.department_delete_alert_text') }}",
                    icon: "warning",
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            var _token = $('meta[name="csrf-token"]').attr("content");
                            var senddata = {
                                id: id,
                                _token: _token
                            }
                            $.ajax({
                                url: "{{ route('supplier-bank-delete') }}",
                                type: 'POST',
                                data: senddata,
                                success: function(successData) {
                                    $.toast({
                                        heading: '{{ __('admin.success') }}',
                                        text: '{{ __('admin.supplier_product_details_deleted_successfully') }}.',
                                        showHideTransition: 'slide',
                                        icon: 'success',
                                        loaderBg: '#f96868',
                                        position: 'top-right'
                                    });
                                    $('#supplierBankTable').DataTable().row(
                                        "#addbankdetails_" + id).remove()
                                        .draw();

                                },
                                error: function() {
                                    console.log('error');
                                }
                            });

                        }
                    });

            });

            $(document).on('click', '#is_primary', function(e) {
                let is_checked = $(this).prop('checked');
                if (!is_checked){
                    e.preventDefault();
                    swal("{{ __('admin.primary_bank_message') }}!", {
                        icon: "warning",
                    });
                    return false;
                }
            })
            $(document).on('click', '.account_is_primary', function(e) {
                e.preventDefault();
                let id = $(this).attr("data-id");
                let is_checked = $(this).prop('checked');
                if (!is_checked){
                    swal("{{ __('admin.primary_bank_message') }}!", {
                        icon: "warning",
                    });
                    return false;
                }
                let that = $(this);
                swal({
                    title: "{{ __('admin.delete_sure_alert') }}",
                    text: is_checked?"{{ __('admin.primary_account_message') }}.":"{{ __('admin.secondary_bank_account_message') }}",
                    icon: "/assets/images/bin.png",
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            let _token = $('meta[name="csrf-token"]').attr("content");
                            let senddata = {
                                id: id,
                                _token: _token,
                                is_primary:(is_checked?1:0)
                            }
                            $.ajax({
                                url: "{{ route('supplier-bank-status-update') }}",
                                type: 'POST',
                                data: senddata,
                                success: function(successData) {
                                    if (successData.success) {
                                        $.toast({
                                            heading: '{{ __('admin.success') }}',
                                            text: '{{ __('admin.supplier_bank_status_change_message') }}.',
                                            showHideTransition: 'slide',
                                            icon: 'success',
                                            loaderBg: '#f96868',
                                            position: 'top-right'
                                        });
                                        if (is_checked){
                                            $('.account_is_primary').prop('checked',false).attr('checked',false);
                                            $('#account_is_primary_'+id).prop('checked',true).attr('checked',true);
                                        }else{
                                            $('#account_is_primary_'+id).prop('checked',false).attr('checked',false);
                                        }
                                    }else{
                                        $.toast({
                                            heading: '{{ __('admin.warning') }}',
                                            text: '{{ __('admin.something_error_message') }}',
                                            showHideTransition: 'slide',
                                            icon: 'warning',
                                            loaderBg: '#57c7d4',
                                            position: 'top-right'
                                        })
                                    }
                                },
                                error: function() {
                                    console.log('error');
                                }
                            });

                        }
                    });

            });

            /*---------------- @ekta 1804 ------------*/
            //onchange price - change discount amount
            $("#supplierProductPrice").keyup(function () {
                priceChange();
            });

            $(document).on('keyup', '.discountVal', function(e) {
                var id = $(this).attr("id");
                var replace_id = id.replace('discount', '');
                $('#showErrDiscount'+replace_id).html('');
                setDiscountAmtVal($(this));
            });

        });
        let defaultBankLogo = "{{ URL('assets/icons/bank.png') }}";
        function bankChange(selector) {
            $('#bank-code').val(selector.find('option:selected').data('code'));
            if (selector.find('option:selected').data('logo')) {
                $("#bank-logo").attr('src', location.origin + '/' + selector.find('option:selected').data('logo'));
            }else{
                $("#bank-logo").attr('src', defaultBankLogo);
            }
        }
        function addUpdateSupplierBank(formData){
            $.ajax({
                url: "{{ route('save-supplier-bank') }}",
                data: formData,
                type: "POST",
                contentType: false,
                processData: false,
                success: function(successData) {
                    if (successData.success) {
                        resetToastPosition();
                        setSupplierDataOnDatatable(successData.data);
                        $("#bankdetail").modal("hide");
                    }
                },
                error: function() {
                    console.log("error");
                },
            });
        }
        function setSupplierDataOnDatatable(data) {
            let supplier_bank_details = data.supplier_bank_details;
            if (supplier_bank_details.is_primary) {
                $('.account_is_primary').prop('checked',false).attr('checked', false);
            }
            if (data.is_edit) {
                $.toast({
                    heading: "{{ __('admin.success') }}",
                    text: "{{ __('admin.supplier_bank_update_successs') }}",
                    showHideTransition: "slide",
                    icon: "success",
                    loaderBg: "#f96868",
                    position: "top-right",
                });
                let $tr = $('#addbankdetails_' + supplier_bank_details.id)
                $tr.find('.t_bank_name').text(supplier_bank_details.name);
                $tr.find('.t_bank_logo').attr('src',(supplier_bank_details.logo?(location.origin + '/' +supplier_bank_details.logo):defaultBankLogo));
                $tr.find('.t_bank_code').text(supplier_bank_details.code);
                $tr.find('.t_bank_account_name').text(supplier_bank_details.bank_account_name);
                $tr.find('.t_bank_account_number').text(supplier_bank_details.bank_account_number);
                $('#account_is_primary_'+supplier_bank_details.id).prop('checked',(supplier_bank_details.is_primary==1)).attr('checked',(supplier_bank_details.is_primary==1));
                //$tr.find('.account_is_primary').attr('checked',(supplier_bank_details.is_primary==1));

            }else{
                $.toast({
                    heading: "{{ __('admin.success') }}",
                    text: "{{ __('admin.supplier_bank_added_success') }}",
                    showHideTransition: "slide",
                    icon: "success",
                    loaderBg: "#f96868",
                    position: "top-right",
                });
                let tableData = '<tr id="addbankdetails_'+supplier_bank_details.id+'" class="odd">\n' +
                    '                                    <td class="d-inline" id="t_bank_name_'+supplier_bank_details.id+'">\n' +
                    '                                        <div class="p-1 pe-1 ">\n' +
                    '                                             <img src="'+(supplier_bank_details.logo?(location.origin + '/' +supplier_bank_details.logo):defaultBankLogo)+'" class="t_bank_logo" height="20px" width="20px" alt="bank Logo">\n' +
                    '                                        </div>\n' +
                    '                                    </td>\n' +
                    '                                    <td>\n' +
                    '                                        <span class="t_bank_name ps-1">'+supplier_bank_details.name+'</span>\n' +
                    '                                    </td>' +
                    '                                    <td class="t_bank_code" id="t_bank_code_'+supplier_bank_details.id+'">'+supplier_bank_details.code+'</td>\n' +
                    '                                    <td class="t_bank_account_name" id="t_bank_account_name_'+supplier_bank_details.id+'">'+supplier_bank_details.bank_account_name+'</td>\n' +
                    '                                    <td class="t_bank_account_number" id="t_bank_account_number_'+supplier_bank_details.id+'">'+supplier_bank_details.bank_account_number+'</td>\n' +
                    '                                    <td class="t_primary_others" id="t_primary_others_'+supplier_bank_details.id+'">\n' +
                    '                                        <div class="form-check form-switch" style="margin-left: 0px;">\n' +
                    '                                            <input class="form-check-input account_is_primary" type="checkbox" data-id="'+supplier_bank_details.id+'" id="account_is_primary_'+supplier_bank_details.id+'" '+(supplier_bank_details.is_primary?'checked':'')+'>\n' +
                    '                                        </div>\n' +
                    '                                    </td>\n' +
                    '                                    <td class="text-end text-nowrap" id="t_action_'+supplier_bank_details.id+'"> <a href="javascript:void(0)" data-id="'+supplier_bank_details.id+'" class="editSupplierBankDetails"><i class="fa fa-edit" style="color: #3f80ea;"></i></a>\n' +
                    '                                        <a href="javascript:void(0)" data-id="'+supplier_bank_details.id+'" class="deletebankdetails ps-2"> <i class="fa fa-trash" style="color: #d9534f;"></i></a>\n' +
                    '                                    </td>\n' +
                    '                                </tr>';

                $("#supplierBankTable")
                    .DataTable()
                    .row.add($(tableData))
                    .draw();
            }
            $("#bankdetail").modal("hide");
        }

        function show(input) {
            var file = input.files[0];
            var size = Math.round((file.size / 1024))
            if(size > 3000){
                swal({
                    icon: 'error',
                    title: '',
                    text: '{{__('admin.file_size_under_3mb')}}',
                });
                $('input[name='+input.name+']').val('');
            } else {
                var fileName = file.name;
                var allowed_extensions = new Array("jpg", "png", "gif", "jpeg", "pdf");
                var file_extension = fileName.split('.').pop();
                var text = '';
                if (input.name == 'logo') {
                    allowed_extensions = allowed_extensions.filter(function (value) {
                        return value != 'pdf'
                    });
                    text = '{{ __('admin.only_upload_image_file') }}';
                } else {
                    text = '{{ __('admin.upload_image_or_pdf') }}';
                }
                for (var i = 0; i < allowed_extensions.length; i++) {
                    if (allowed_extensions[i] == file_extension) {
                        valid = true;
                        var tooltip = fileName;
                        if(fileName.length > 10) {
                            fileName = fileName.substring(0,10) +'....'+file_extension;
                        }
                        $('#file-' + input.name).html('');
                        $('#file-' + input.name).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.name + 'Download" title="'+tooltip+'" style="text-decoration: none">' + fileName + '</a></span>');
                        return;
                    }
                }
                valid = false;
                swal({
                    // title: "Rfq Update",
                    text: text,
                    icon: "warning",
                    //buttons: true,
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                    // dangerMode: true,
                })
            }
        }

        function downloaproductdimg(id, fieldName, name) {
            event.preventDefault();
            var data = {
                id: id,
                fieldName: fieldName
            }
            $.ajax({
                url: "{{ route('supplier-download-product-image-ajax') }}",
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

        function getPrimaryImage(id) {
            return new Promise(resolve => {
                $.ajax({
                    url: "{{ route('get-product-image-ajax', '') }}" + "/" + id,
                    type: "GET",
                    success: function(successData) {

                        $('#file-productImages').html('');
                        $('#file-productImages').html(successData.activityhtml)
                        resolve('resolved');
                    },
                    error: function() {
                        console.log("error");
                    },
                });
            });
        }
        //textbox value numeric set @ekta 18-04
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

        /**Start pkp non-pkp use to hide show functionality*/
        var PkpNonPkpTab = function(){

           pkpClassFunction = function(){
               $('.pkp-btn').on('click',function(){
                   $('.pkp_doc_option').show();
                   $('#pkp-doc').attr('required', 'true');
               });
           };

           nonPkpClassFunction = function(){
               $('.non-pkp-btn').on('click',function(){
                   $('.pkp_doc_option').hide();
                   $('#pkp-doc').removeAttr('required');
                   $('#pkp_file').val('');
               });
           };

           return {
               init:function(){
                   pkpClassFunction(),
                   nonPkpClassFunction()
               }
           }

       }(1);
       /**End pkp use to pkp hide show functionalit End*/
       jQuery(document).ready(function(){
           PkpNonPkpTab.init();
       });
    </script>

@section('scripts')
    {{ view('admin.supplier.supplier_product_range_js') }}
@endsection

@stop

@push('bottom_scripts')
    <script>

        var input = document.querySelector("#mobile");
        var iti = window.intlTelInput(input, {
            initialCountry:"id",
            separateDialCode:true,
            dropdownContainer:null,
            preferredCountries:["id"],
            hiddenInput:"c_phone_code"
        });

        $("#mobile").focusin(function(){
            let countryData = iti.getSelectedCountryData();
            $('input[name="c_phone_code"]').val(countryData.dialCode);
        });

        var input = document.querySelector("#contactPersonMobile");
        var iti2 = window.intlTelInput(input, {
            initialCountry:"id",
            separateDialCode:true,
            dropdownContainer:null,
            preferredCountries:["id"],
            hiddenInput:"cp_phone_code"
        });

        $("#contactPersonMobile").focusin(function(){
            let countryData = iti2.getSelectedCountryData();
            $('input[name="cp_phone_code"]').val(countryData.dialCode);
        });
        $(document).ready(function(){
            $('input[name="c_phone_code"]').val('62');
            $('input[name="cp_phone_code"]').val('62');

            $('#address').on('change', function(e) {
                $('#address').parsley().reset();
            });

        });
        var SnippetGetProductCategoryDetails = function(){
var getProductList = function(){
$(document).on('keyup','#searchProductCategory',function(){
    if ($(this).val().length >= 3) {
        $.ajax({
            url: "/search-product",
            method:'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {'data' : $(this).val()},
            success : function(response){
                if (response.success) {
                    setResult(response.data);
                } else {
                    resetResult()
                }
            },
        });
    } else {
        resetResult();
    }
    });
},
setResult = function(data){
    var searchResult = '';
    data.forEach(function (data) {
        searchResult += '<li  data-value="'+ data.productName+ '"data-product-id="' + data.productId +'" data-category-id="' + data.categoryId + '" data-subcategory-id="' + data.subcategoryId + '" ' +
            ' class="list-group-item list-item-pointer listProductCat">'+
            data.productName+'</li>';
    });
    $('#searchGroup').html(searchResult);
    selectProductList();
},

resetResult = function(){
    $('#searchGroup').html('');
},

selectProductList = function(){
    $(document).on('mousedown', '.listProductCat', function (e) {
    var txt = $(this).parent().attr('id');
    var str =$(this).attr('data-value');
    const prodcutArray = str.split("-");
        $("#supplierProductForm #searchProductCategory").val($("<b>").html($(this).attr('data-value')).text());
        $('#supplierProductForm #supplierCategory option[value="'+$(this).attr('data-category-id')+'"]').attr('selected','selected');
        supplierCategoryChange($(this).attr('data-category-id'),$(this).attr('data-subcategory-id'));
        supplierSubCategoryChange($(this).attr('data-subcategory-id'),$(this).attr('data-product-id'));
        resetResult();
       // $("#supplierProductForm #searchProductCategory").val('');

    });

};

return {
    init: function () {
        getProductList()
    // selectProductList()
    }
}


}(1);

jQuery(document).ready(function(){
    SnippetGetProductCategoryDetails.init();
});
    </script>
    <script>
        // CSRF Token
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function(){

            $( "#tags" ).autocomplete({
                minLength:3,
                source: function( request, response ) {
                    // Fetch data
                    $.ajax({
                        url: "/search-product",
                        method:'POST',
                        dataType: "json",
                        data: {
                            _token: CSRF_TOKEN,
                            data: escape(request.term)
                        },
                        success: function( data ) {
                            response( $.map( data.data, function( item ) {
                                return {
                                    label: item.productName,
                                    categoryName: item.categoryName,
                                    product_name:item.productTextName,
                                    productId:item.productId,
                                    categoryId:item.categoryId,
                                    subcategoryId:item.subcategoryId,
                                    subcategoryName:item.subcategoryName,
                                }
                            }));
                        },

                    });
                },
                select: function (event, ui) {
                    var productName = ui.item.label;
                    var product_name = ui.item.product_name;
                    var categoryName = ui.item.categoryName;
                    var categoryId = ui.item.categoryId;
                    var productId = ui.item.productId;
                    var subcategoryId = ui.item.subcategoryId;
                    var subcategoryName = ui.item.subcategoryName;
                    const prodcutArray = productName.split("-");
                    var product = JSON.parse(localStorage.getItem("product")) || [];

                    $('#supplierProductForm #supplierCategory option[value="'+categoryId+'"]').attr('selected','selected');
                    supplierCategoryChange(categoryId,subcategoryId);
                    supplierSubCategoryChange(subcategoryId,productId);

                    return false;
                }
            })

        });
    </script>

@endpush
