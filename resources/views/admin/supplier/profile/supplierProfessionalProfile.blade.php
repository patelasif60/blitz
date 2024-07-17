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

        .contact_step .icon .pen_icon.supplier {
            background-image: url(../../../front-assets/images/icons/people-carry-1_white.png);
            background-repeat: no-repeat;
            background-position: center center;
        }

        .contact_step .icon .pen_icon.product {
            background-image: url(../../../front-assets/images/icons/boxes_white.png);
            background-repeat: no-repeat;
            background-position: center center;
        }

        .contact_step .icon .pen_icon.bank {
            background-image: url(../../../front-assets/images/icons/icon_bank_white.png);
            background-repeat: no-repeat;
            background-position: center center;
        }

        .nav-link.done .pen_icon {
            border: 2px solid #fff;
            background-image: url(../../../front-assets/images/check_icon.png) no-repeat center center;
            background-color: #7cc576;
        }

        .nav-link.done .pen_icon.supplier,
        .nav-link.done .pen_icon.product,
        .nav-link.done .pen_icon.bank {
            border: 2px solid #fff;
            background-image: url(../../../front-assets/images/check_icon.png) no-repeat center center;
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

        .sw-theme-dots>.nav li:last-child .nav-link:first-child::after {
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

        .table td,
        th {
            text-align: left;
        }

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

        #parsley-id-multiple-companyType {
            margin-top: 32px !important;
            margin-left: 10px !important;
        }
        #pkp_fileError {
            margin-left: 1rem !important;
        }
        #supplier-steps .toolbar.toolbar-bottom{
            display: none;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="row">
                <div class="col-md-12 d-flex align-items-center mb-3">
                    <h1 class="mb-0 h3">
                        {{ __('admin.supplier_details') }}
                    </h1>

                    @if(auth()->user()->role_id != 3)
                        <a href="{{ route('admin.supplier.index') }}" class="ms-3 backurl btn-close ms-auto"></a>
                    @endif
                </div>
                <div class="col-12">
                    <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                type="button" role="tab" aria-controls="home"
                                aria-selected="true">{{ __('admin.edit_supplier') }}</button>
                        </li>
                        <li class="nav-item ms-auto  me-3 mt-2">
                            @if (!empty($supplier->profile_username))
                                <a href="{{ route('supplier.professional.profile', getSettingValueByKey('slug_prefix') . $supplier->profile_username) }}"
                                    target="_blank" id="previewBtn" class="btn btn-primary btn-sm">Preview</a>
                            @else
                                <a href="{{ route('supplier.professional.profile', getSettingValueByKey('slug_prefix') . \Illuminate\Support\Str::replace(' ', '-', \Illuminate\Support\Str::lower($supplier->name))) }}"
                                    target="_blank" id="previewBtn" class="btn btn-primary btn-sm">Preview</a>
                            @endif
                        </li>
                    </ul>
                    <div class="tab-content pt-3 pb-0 px-0" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
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
                                        <a class="nav-link" id="productTab" href="#step-2">
                                            {{ __('admin.product') }}<div class="icon">
                                                <i class="pen_icon product"></i>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="nav-link" id="accountDetailsTab" href="#step-3">
                                            {{ __('admin.account_details') }}
                                            <div class="icon">
                                                <i class="pen_icon bank"></i>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content pt-3 pb-3">
                                    <div id="step-1" class="tab-pane p-0" role="tabpanel" style="display: none;">
                                        <div class="p-3 bg-white shadow supplierProfessionalPro">
                                            <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab"
                                                role="tablist">
                                                <li class="nav-item supProfileTabValidate" role="presentation">
                                                    <button class="nav-link active" id="pills-CompanyDetails-tab"
                                                        data-bs-toggle="pill" data-bs-target="#pills-CompanyDetails"
                                                        type="button" role="tab" aria-controls="pills-CompanyDetails"
                                                        aria-selected="true">
                                                        <span>1</span>
                                                        <div>{{ __('admin.account_detail') }}</div>
                                                    </button>
                                                </li>
                                                <li class="nav-item supProfileTabValidate" role="presentation">
                                                    <button class="nav-link" id="pills-BusinessDetails-tab"
                                                        data-bs-toggle="pill" data-bs-target="#pills-BusinessDetails"
                                                        type="button" role="tab" aria-controls="pills-BusinessDetails"
                                                        aria-selected="false">
                                                        <span>2</span>
                                                        <div>{{ __('admin.business_detail') }}</div>
                                                    </button>
                                                </li>
                                                <li class="nav-item supProfileTabValidate" role="presentation">
                                                    <button class="nav-link" id="pills-BasicDetails-tab"
                                                        data-bs-toggle="pill" data-bs-target="#pillsBasicDetails"
                                                        type="button" role="tab" aria-controls="pillsBasicDetails"
                                                        aria-selected="false">
                                                        <span>3</span>
                                                        <div>{{ __('admin.company_basic_detail') }} </div>
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="pills-CoreTeam-tab" data-bs-toggle="pill"
                                                        data-bs-target="#pills-CoreTeam" type="button" role="tab"
                                                        aria-controls="pills-CoreTeam" aria-selected="false">
                                                        <span>4</span>
                                                        <div>{{ __('admin.core_team_detail') }} </div>
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="pills-Portfolio-tab"
                                                        data-bs-toggle="pill" data-bs-target="#pills-Portfolio"
                                                        type="button" role="tab" aria-controls="pills-Portfolio"
                                                        aria-selected="false">
                                                        <span>5</span>
                                                        <div>{{ __('admin.client_&_supplier_portfolio') }} </div>
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="pills-Highlights-tab"
                                                        data-bs-toggle="pill" data-bs-target="#pills-Highlights"
                                                        type="button" role="tab" aria-controls="pills-Highlights"
                                                        aria-selected="false">
                                                        <span>6</span>
                                                        <div>{{ __('admin.company_achievement') }} </div>
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="pills-others-tab" data-bs-toggle="pill"
                                                        data-bs-target="#pills-others" type="button" role="tab"
                                                        aria-controls="pills-others" aria-selected="false">
                                                        <span>7</span>
                                                        <div>{{ __('admin.other_detail') }} </div>
                                                    </button>
                                                </li>
                                            </ul>
                                            <div class="tab-content p-0 " id="pills-tabContent">
                                                <!-- Section 1 Start -->
                                                <div class="tab-pane fade show active bg-white" id="pills-CompanyDetails"
                                                    role="tabpanel" aria-labelledby="pills-CompanyDetails-tab"
                                                    tabindex="0">
                                                    <form class="" id="supplierEditForm"  name="supplierEditForm" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="id" id="supplierId"
                                                            value="{{ $supplier->id }}">
                                                        <input type="hidden" name="is_supplier"
                                                            value="{{ auth()->user()->role_id == 3 ? 1 : 0 }}">
                                                        <input type="hidden" name="inputErrors" class="inputErrors"
                                                            value="">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-2">
                                                                <div class="card mb-3">
                                                                    <div class="card-header d-flex align-items-center">
                                                                        <h5 class="mb-0"><img height="20px"
                                                                                src="{{ URL::asset('assets/icons/icon_company.png') }}"
                                                                                alt="Company" class="pe-2">
                                                                            <span>{{ __('admin.company_details') }}</span>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="card-body p-3 pb-1">
                                                                        <div class="row">
                                                                            <div class="col-md-4 mb-3">
                                                                                <label for="name" class="form-label">{{ __('admin.company_name') }} <span class="text-danger">*</span></label>
                                                                                <input type="text" class="form-control max-255" id="name" name="name" value="{{ $supplier->name }}" placeholder="{{ __('admin.company_name_placeholder') }}" onkeypress="return preventQuote();" >
                                                                                <small id="nameError" class="text-danger errorValidate"></small>
                                                                            </div>
                                                                            <div class="col-md-4 mb-3">
                                                                                <label for="profile_username"
                                                                                    class="form-label">{{ __('admin.profile_username') }}
                                                                                    <span
                                                                                        class="text-danger">*</span></label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span
                                                                                            class="input-group-text">{{ $slug_prefix }}</span>
                                                                                    </div>
                                                                                    <input type="text"
                                                                                        class="form-control input-slug "
                                                                                        name="profile_username"
                                                                                        placeholder="{{ __('admin.profile_username') }}"
                                                                                        aria-label="profile_username"
                                                                                        id="profile_username"
                                                                                        value="{{ \Illuminate\Support\Str::replace(' ', '-', \Illuminate\Support\Str::lower($supplier->profile_username)) ?? \Illuminate\Support\Str::lower($supplier->name) }}">
                                                                                    <span
                                                                                        id="profileUsernameValidationDiv"></span>
                                                                                </div>
                                                                                <small id="profile_usernameError"
                                                                                    class="text-danger errorValidate"></small>
                                                                            </div>
                                                                            <div class="col-md-4 mb-3">
                                                                                <label for="logo"
                                                                                    class="form-label">{{ __('admin.company_logo') }}</label>
                                                                                <div class="d-flex">
                                                                                    <span class=""><input
                                                                                            type="file" name="logo"
                                                                                            class="form-control"
                                                                                            id="logo"
                                                                                            accept=".jpg,.png,.gif,.jpeg"
                                                                                            onchange="show(this)"
                                                                                            hidden /><label
                                                                                            class="upload_btn"
                                                                                            for="logo">{{ __('admin.upload_logo') }}</label></span>
                                                                                    <div id="file-logo">
                                                                                        @if ($supplier->logo)
                                                                                            @php
                                                                                                $extension_logo = substr($supplier->logo, -4);
                                                                                                $logo_filename = substr(Str::substr($supplier->logo, stripos($supplier->logo, 'logo_') + 5), 0, -4);
                                                                                                if (strlen($logo_filename) > 10) {
                                                                                                    $logo_name = substr($logo_filename, 0, 10) . '...' . $extension_logo;
                                                                                                } else {
                                                                                                    $logo_name = $logo_filename . $extension_logo;
                                                                                                }
                                                                                            @endphp
                                                                                            <input type="hidden"
                                                                                                class="form-control"
                                                                                                id="oldlogo"
                                                                                                name="oldlogo"
                                                                                                value="{{ $supplier->logo }}">
                                                                                            <span class="ms-2"><a
                                                                                                    href="javascript:void(0);"
                                                                                                    id="logoFileDownload"
                                                                                                    onclick="downloadimg('{{ $supplier->id }}', 'logo', '{{ Str::substr($supplier->logo, stripos($supplier->logo, 'logo_') + 5) }}')"
                                                                                                    title="{{ Str::substr($supplier->logo, stripos($supplier->logo, 'logo_') + 5) }}"
                                                                                                    style="text-decoration: none;">
                                                                                                    {{ $logo_name }}</a></span>
                                                                                            <span class="removeFile"
                                                                                                id="logoFile"
                                                                                                data-id="{{ $supplier->id }}"
                                                                                                file-path="{{ $supplier->logo }}"
                                                                                                data-name="logo"><a
                                                                                                    href="#"
                                                                                                    title="Remove logo">
                                                                                                    <img src="{{ URL::asset('assets/icons/times-circle copy.png') }}"
                                                                                                        alt="CLose button"
                                                                                                        class="ms-2"></a></span>
                                                                                            <span class="ms-2"><a
                                                                                                    class="logoFile"
                                                                                                    href="javascript:void(0);"
                                                                                                    title="Download logo"
                                                                                                    onclick="downloadimg('{{ $supplier->id }}', 'logo', '{{ Str::substr($supplier->logo, stripos($supplier->logo, 'logo_') + 5) }}')"
                                                                                                    style="text-decoration: none;"><i
                                                                                                        class="fa fa-cloud-download"></i></a></span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                                <small class="text-danger errorValidate"
                                                                                    id="logoError"></small>
                                                                            </div>
                                                                            <div class="col-md-4 mb-3">
                                                                                <label for="email"
                                                                                    class="form-label">{{ __('admin.company_email') }}
                                                                                </label>
                                                                                <input type="email"
                                                                                    class="form-control max-255"
                                                                                    id="email" name="email"
                                                                                    value="{{ $supplier->email }}"
                                                                                    placeholder="{{ __('admin.company_email_placeholder') }}" />
                                                                                <small id="emailError"
                                                                                    class="text-danger errorValidate"></small>
                                                                            </div>
                                                                            <div class="col-md-4 mb-3">
                                                                                <label for="mobile"
                                                                                    class="form-label">{{ __('admin.company_mobile') }}
                                                                                </label>
                                                                                <input type="text"
                                                                                    class="form-control input-number max-255"
                                                                                    id="mobile" name="mobile"
                                                                                    value="{{ $supplier->mobile }}"
                                                                                    placeholder="XXXXXXXXXXX" maxlength="16">
                                                                                <small id="mobileError"
                                                                                    class="text-danger errorValidate"></small>
                                                                            </div>
                                                                            <div class="col-md-4 mb-3">
                                                                                <label for="company_alternative_phone" class="form-label">{{ __('admin.company_alternative_phone')}}</label>
                                                                                <input type="text" class="form-control input-number max-255" id="company_alternative_phone" name="company_alternative_phone" value="{{ $supplier->company_alternative_phone }}" placeholder="XXXXXXXXXXX" maxlength="16">
                                                                                <small id="company_alternative_phoneError" class="text-danger errorValidate"></small>
                                                                            </div>
                                                                            <div class="col-md-4 mb-3">
                                                                                <label for="website"
                                                                                    class="form-label">{{ __('admin.company_website') }}</label>
                                                                                <input type="text"
                                                                                    class="form-control max-255"
                                                                                    id="website" name="website"
                                                                                    placeholder="{{ __('admin.company_website_placeholder') }}"
                                                                                    value="{{ $supplier->website }}">
                                                                                <small id="websiteError"
                                                                                    class="text-danger errorValidate"></small>

                                                                            </div>
                                                                            <div class="col-md-3 mb-3 d-none">
                                                                                <label for="interested_in"
                                                                                    class="form-label">{{ __('admin.dealing_with_categories') }}</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="interested_in"
                                                                                    name="interested_in"
                                                                                    placeholder="{{ __('admin.dealing_with_categories') }}"
                                                                                    value="{{ $supplier->interested_in }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <div class="card mb-3">
                                                                    <div class="card-header d-flex align-items-center">
                                                                        <h5 class="mb-0"><img height="20px"
                                                                                src="{{ URL::asset('assets/icons/icon_contact_details.png') }}"
                                                                                alt="Contact" class="pe-2">
                                                                            <span>{{ __('admin.contact_details') }}</span>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="card-body p-3 pb-1">
                                                                        <div class="row">
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="contactPersonName"
                                                                                    class="form-label">
                                                                                    {{ __('admin.contact_person_first_name') }}</label>
                                                                                <div class="d-flex">
                                                                                    <select name="salutation"
                                                                                        class="form-select border-end-0"
                                                                                        id="inputGroupSelect01"
                                                                                        style="border-radius: 0px;  background-color: rgba(0, 0, 0, 0.05);width: 135px;">
                                                                                        <option value="1"
                                                                                            {{ $supplier->salutation == '1' ? 'selected' : '' }}>
                                                                                            {{ __('admin.salutation_mr') }}
                                                                                        </option>
                                                                                        <option value="2"
                                                                                            {{ $supplier->salutation == '2' ? 'selected' : '' }}>
                                                                                            {{ __('admin.salutation_ms') }}
                                                                                        </option>
                                                                                        <option value="3"
                                                                                            {{ $supplier->salutation == '3' ? 'selected' : '' }}>
                                                                                            {{ __('admin.salutation_mrs') }}
                                                                                        </option>
                                                                                    </select>
                                                                                    <input type="text"
                                                                                        name="contactPersonName"
                                                                                        id="contactPersonName"
                                                                                        placeholder="{{ __('admin.first_name_placeholder') }}"
                                                                                        class="form-control input-alpha-numeric max-255"
                                                                                        value="{{ $supplier->contact_person_name }}">
                                                                                </div>
                                                                                <small id="contactPersonNameError"
                                                                                    class="text-danger errorValidate"></small>
                                                                            </div>
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="contactPersonLastName"
                                                                                    class="form-label">
                                                                                    {{ __('admin.contact_person_last_name') }}</label>
                                                                                <input type="text"
                                                                                    name="contactPersonLastName"
                                                                                    id="contactPersonLastName"
                                                                                    class="form-control input-alpha-numeric max-255"
                                                                                    placeholder="{{ __('admin.contact_person_last_name') }}"
                                                                                    value="{{ $supplier->contact_person_last_name }}">
                                                                                <small id="contactPersonLastNameError"
                                                                                    class="text-danger errorValidate"></small>
                                                                            </div>
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="contactPersonEmail" class="form-label">{{ __('admin.contact_person_email') }}<span class="text-danger">*</span></label>
                                                                                <input type="email" name="contactPersonEmail" id="contactPersonEmail" placeholder="{{ __('admin.email_placeholder') }}" class="form-control max-255" value="{{ $supplier->contact_person_email }}" readonly>
                                                                                <small id="contactPersonEmailError" class="text-danger errorValidate"></small>
                                                                            </div>
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="contactPersonEmail"
                                                                                    class="form-label">{{ __('admin.altenate_email') }}</label>
                                                                                <input type="email"
                                                                                    name="alternate_email"
                                                                                    id="alternate_email"
                                                                                    class="form-control max-255"
                                                                                    value="{{ $supplier->alternate_email }}"
                                                                                    placeholder="{{ __('admin.email_placeholder') }}">
                                                                                <small id="alternate_emailError"
                                                                                    class="text-danger errorValidate"></small>

                                                                            </div>
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="contactPersonMobile"
                                                                                    class="form-label">{{ __('admin.contact_person_phone') }}</label>
                                                                                <input type="text"
                                                                                    class="form-control input-number max-255"
                                                                                    id="contactPersonMobile"
                                                                                    name="contactPersonMobile"
                                                                                    placeholder="XXXXXXXXXXX"
                                                                                    value="{{ $supplier->contact_person_phone }}"
                                                                                    maxlength="16">
                                                                                <small id="contactPersonMobileError"
                                                                                    class="text-danger errorValidate"></small>

                                                                            </div>
                                                                            @if (isset($asBuyer) && $asBuyer == 0 && !empty($supplier->contact_person_email))
                                                                                <div class="col-md-3 mb-3 inviteBuyer">
                                                                                    <label
                                                                                        class="form-label invisible">&nbsp;</label>
                                                                                    <div>
                                                                                        <a class="btn btn-primary"
                                                                                            id="inviteSupplierButton"
                                                                                            href="javascript:void(0);"
                                                                                            onclick="inviteAsBuyer('{{ $supplier->id }}')">{{ __('admin.invite_as_supplier') }}</a>
                                                                                        <img class="hidden"
                                                                                            id="loaderinviteSupplier"
                                                                                            height="16px"
                                                                                            src="{{ URL::asset('front-assets/images/icons/timer.gif') }}"
                                                                                            title="Loading">
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <div class="card mb-3">
                                                                    <div class="card-header d-flex align-items-center">
                                                                        <h5 class="mb-0"><img height="20px"
                                                                                src="{{ URL::asset('assets/icons/icon_company.png') }}"
                                                                                alt="Contact" class="pe-2">
                                                                            <span>{{ __('admin.company_Other_information') }}</span>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="card-body p-3 pb-1">
                                                                        <div class="row">
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="nib" class="form-label">{{ __('admin.nib') }} </label>
                                                                                <input type="text" class="form-control input-number" id="nib" name="nib" value="{{ $supplier->nib }}"  >
                                                                                <small id="nibError" class="text-danger errorValidate"></small>
                                                                            </div>
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for=""
                                                                                    class="form-label">{{ __('admin.nib_file') }}
                                                                                </label>
                                                                                <div class="d-flex">
                                                                                    <span class="">
                                                                                        <input type="file"
                                                                                            name="nib_file"
                                                                                            class="form-control"
                                                                                            id="nib_file"
                                                                                            accept=".jpg,.png,.gif,.jpeg, .pdf"
                                                                                            onchange="show(this)" hidden />
                                                                                        <label class="upload_btn"
                                                                                            for="nib_file">{{ __('admin.upload_nib_file') }}</label>
                                                                                    </span>
                                                                                    <div id="file-nib_file">
                                                                                        <input type="hidden"
                                                                                            class="form-control"
                                                                                            id="old_nib_file"
                                                                                            name="old_nib_file"
                                                                                            value="{{ $supplier->nib_file }}">
                                                                                        @if ($supplier->nib_file)
                                                                                            @php
                                                                                                $nibFileTitle = Str::substr($supplier->nib_file, stripos($supplier->nib_file, 'nib_file_') + 9);
                                                                                                $extension_nib_file = getFileExtension($nibFileTitle);
                                                                                                $nib_file_filename = getFileName($nibFileTitle);
                                                                                                if (strlen($nib_file_filename) > 10) {
                                                                                                    $nib_file_name = substr($nib_file_filename, 0, 10) . '...' . $extension_nib_file;
                                                                                                } else {
                                                                                                    $nib_file_name = $nib_file_filename . $extension_nib_file;
                                                                                                }
                                                                                            @endphp
                                                                                            <input type="hidden"
                                                                                                class="form-control"
                                                                                                id="oldnib_file"
                                                                                                name="oldnib_file"
                                                                                                value="{{ $supplier->nib_file }}">
                                                                                            <span class="ms-2">
                                                                                                <a href="javascript:void(0);"
                                                                                                    id="nibFileDownload"
                                                                                                    onclick="downloadimg('{{ $supplier->id }}', 'nib_file', '{{ $nibFileTitle }}')"
                                                                                                    title="{{ $nibFileTitle }}"
                                                                                                    style="text-decoration: none;">
                                                                                                    {{ $nib_file_name }}</a>
                                                                                            </span>
                                                                                            <span class="removeFile"
                                                                                                id="nibFile"
                                                                                                data-id="{{ $supplier->id }}"
                                                                                                file-path="{{ $supplier->nib_file }}"
                                                                                                data-name="nib_file">
                                                                                                <a href="javascript:void(0);"
                                                                                                    title="Remove Nib File">
                                                                                                    <img src="{{ URL::asset('assets/icons/times-circle copy.png') }}"
                                                                                                        alt="CLose button"
                                                                                                        class="ms-2"></a>
                                                                                            </span>
                                                                                            <span class="ms-2">
                                                                                                <a class="nib_file"
                                                                                                    href="javascript:void(0);"
                                                                                                    title="Download nib_file"
                                                                                                    onclick="downloadimg('{{ $supplier->id }}', 'nib_file', '{{ $nibFileTitle }}')"
                                                                                                    style="text-decoration: none;"><i
                                                                                                        class="fa fa-cloud-download"></i></a>
                                                                                            </span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="npwp"
                                                                                    class="form-label">{{ __('admin.npwp') }}
                                                                                </label>
                                                                                <input type="text"
                                                                                    class="form-control input-number"
                                                                                    id="npwp" name="npwp"
                                                                                    value="{{ $supplier->npwp }}"
                                                                                    placeholder="11.222.333.4-555.666">
                                                                                <small id="npwpError"
                                                                                    class="text-danger errorValidate"></small>

                                                                            </div>
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="name"
                                                                                    class="form-label">{{ __('admin.npwp_file') }}</label>
                                                                                <div class="d-flex">
                                                                                    <span class=""><input
                                                                                            type="file"
                                                                                            name="npwp_file"
                                                                                            class="form-control"
                                                                                            id="npwp_file"
                                                                                            accept=".jpg,.png,.gif,.jpeg, .pdf"
                                                                                            onchange="show(this)" hidden />
                                                                                        <label class="upload_btn"
                                                                                            for="npwp_file">{{ __('admin.upload_npwp_file') }}</label></span>
                                                                                    <div id="file-npwp_file">
                                                                                        <input type="hidden"
                                                                                            class="form-control"
                                                                                            id="old_npwp_file"
                                                                                            name="old_npwp_file"
                                                                                            value="{{ $supplier->npwp_file }}">
                                                                                        @if ($supplier->npwp_file)
                                                                                            @php
                                                                                                $npwpFileTitle = Str::substr($supplier->npwp_file, stripos($supplier->npwp_file, 'npwp_file_') + 10);
                                                                                                $extension_npwp_file = getFileExtension($npwpFileTitle);
                                                                                                $npwp_file_filename = getFileName($npwpFileTitle);
                                                                                                if (strlen($npwp_file_filename) > 10) {
                                                                                                    $npwp_file_name = substr($npwp_file_filename, 0, 10) . '...' . $extension_npwp_file;
                                                                                                } else {
                                                                                                    $npwp_file_name = $npwp_file_filename . $extension_npwp_file;
                                                                                                }
                                                                                            @endphp
                                                                                            <input type="hidden"
                                                                                                class="form-control"
                                                                                                id="oldnpwp_file"
                                                                                                name="oldnpwp_file"
                                                                                                value="{{ $supplier->npwp_file }}">
                                                                                            <span class="ms-2">
                                                                                                <a href="javascript:void(0);"
                                                                                                    id="npwpFileDownload"
                                                                                                    onclick="downloadimg('{{ $supplier->id }}', 'npwp_file', '{{ $npwpFileTitle }}')"
                                                                                                    title="{{ $npwpFileTitle }}"
                                                                                                    style="text-decoration: none;">
                                                                                                    {{ $npwp_file_name }}</a>
                                                                                            </span>
                                                                                            <span class="removeFile"
                                                                                                id="npwpFile"
                                                                                                data-id="{{ $supplier->id }}"
                                                                                                file-path="{{ $supplier->npwp_file }}"
                                                                                                data-name="npwp_file">
                                                                                                <a href="javascript:void(0);"
                                                                                                    title="Remove Nib File">
                                                                                                    <img src="{{ URL::asset('assets/icons/times-circle copy.png') }}"
                                                                                                        alt="CLose button"
                                                                                                        class="ms-2"></a>
                                                                                            </span>
                                                                                            <span class="ms-2">
                                                                                                <a class="npwp_file"
                                                                                                    href="javascript:void(0);"
                                                                                                    title="Download npwp_file"
                                                                                                    onclick="downloadimg('{{ $supplier->id }}', 'npwp_file', '{{ $npwpFileTitle }}')"
                                                                                                    style="text-decoration: none;"><i
                                                                                                        class="fa fa-cloud-download"></i></a>
                                                                                            </span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label">
                                                                                    {{ __('admin.company_type') }}
                                                                                </label>
                                                                                <div
                                                                                    class="d-flex ms-2 align-items-center">
                                                                                    <div class="form-check ms-4">
                                                                                        <input
                                                                                            class="form-check-input non-pkp-btn"
                                                                                            type="radio"
                                                                                            name="companyType"
                                                                                            id="exampleRadios2"
                                                                                            value="2"
                                                                                            {{ $supplier->company_type == 2 ? 'checked' : '' }}>
                                                                                        <label class="form-check-label"
                                                                                            for="exampleRadios2">
                                                                                            NON-PKP
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="form-check ms-4">
                                                                                        <input
                                                                                            class="form-check-input pkp-btn"
                                                                                            type="radio"
                                                                                            name="companyType"
                                                                                            id="exampleRadios1"
                                                                                            value="1"
                                                                                            {{ $supplier->company_type == 1 ? 'checked' : '' }}>
                                                                                        <label class="form-check-label"
                                                                                            for="exampleRadios1">
                                                                                            PKP
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="uploadbutton ms-2 my-2 d-flex"
                                                                                        id="upload">
                                                                                        <span class="">
                                                                                            <input type="file"
                                                                                                name="pkp_file"
                                                                                                class="form-control pkp-btn-non"
                                                                                                id="pkp-doc"
                                                                                                accept=".jpg,.png,.jpeg,.pdf"
                                                                                                onchange="show(this)"
                                                                                                hidden=""
                                                                                                {{ $supplier->company_type == 1 && $supplier->pkp_file == '' ? '' : '' }}>
                                                                                            @if ($supplier->company_type == 1)
                                                                                                <span
                                                                                                    class="brows-lable"><label
                                                                                                        style="z-index: 1;"
                                                                                                        class="upload_btn pkp_doc_option"
                                                                                                        for="pkp-doc">{{ __('admin.upload_pkp_file') }}</label></span>
                                                                                            @else
                                                                                                <span
                                                                                                    class="brows-lable"><label
                                                                                                        style="display:none; z-index: 1;"
                                                                                                        class="upload_btn pkp_doc_option"
                                                                                                        for="pkp-doc">{{ __('admin.upload_pkp_file') }}</label></span>
                                                                                            @endif
                                                                                        </span>
                                                                                        <input type="hidden"
                                                                                            class="form-control"
                                                                                            id="oldpkp_file"
                                                                                            name="oldpkp_file"
                                                                                            value="{{ $supplier->pkp_file }}">
                                                                                        <div id="file-pkp_file">
                                                                                            @if ($supplier->company_type == 1 && $supplier->pkp_file)
                                                                                                <span class="ms-2">
                                                                                                    <a href="javascript:void(0);"
                                                                                                        id="pkpFileDownload"
                                                                                                        onclick="downloadimg('{{ $supplier->id }}', 'pkp_file', '{{ $pkpFileTitle }}')"
                                                                                                        title="{{ $pkpFileTitle }}"
                                                                                                        style="text-decoration: none;">{{ $pkp_file_name }}</a>
                                                                                                </span>
                                                                                                <span class="removeFile"
                                                                                                    id="pkp_file"
                                                                                                    data-id="{{ $supplier->id }}"
                                                                                                    file-path="{{ $supplier->pkp_file }}"
                                                                                                    data-name="pkp_file">
                                                                                                    <a href="javascript:void(0);"
                                                                                                        title="Remove pkp File">
                                                                                                        <img src="{{ URL::asset('assets/icons/times-circle copy.png') }}"
                                                                                                            alt="CLose button"
                                                                                                            class="ms-2"></a>
                                                                                                </span>
                                                                                                <span class="ms-2">
                                                                                                    <a class="pkp_file"
                                                                                                        href="javascript:void(0);"
                                                                                                        title="Download pkp_file"
                                                                                                        onclick="downloadimg('{{ $supplier->id }}', 'pkp_file', '{{ $pkpFileTitle }}')"
                                                                                                        style="text-decoration: none;"><i
                                                                                                            class="fa fa-cloud-download"></i></a>
                                                                                                </span>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                    <small class="text-danger" style="font-weight: 400" id="pkp_fileError"></small>
                                                                                </div>
                                                                                <small id="companyTypeError"
                                                                                    class="text-danger errorValidate"></small>

                                                                            </div>
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="group_margin" class="form-label">{{ __('admin.group_margin') }} % </label>
                                                                                <input type="text" class="form-control" id="group_margin" name="group_margin" value="{{ $supplier->group_margin }}" onkeypress="return isNumberKey(this, event);">
                                                                                <small id="group_marginError" class="text-danger errorValidate"></small>
                                                                            </div>
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="licence"
                                                                                    class="form-label">{{ __('admin.licence') }}</label>
                                                                                <input type="text"
                                                                                    class="form-control max-255 input-number"
                                                                                    id="licence" name="licence"
                                                                                    value="{{ $supplier->licence }}"
                                                                                    placeholder="{{ __('admin.licence_placehoder') }}">
                                                                                <small id="licenceError"
                                                                                    class="text-danger errorValidate"></small>

                                                                            </div>
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="facebook"
                                                                                    class="form-label">{{ __('admin.facebook') }}</label>
                                                                                <input type="text"
                                                                                    class="form-control max-255"
                                                                                    id="facebook" name="facebook"
                                                                                    value="{{ $supplier->facebook }}"
                                                                                    placeholder="{{ __('admin.facebook_placehoder') }}">
                                                                                <small id="facebookError"
                                                                                    class="text-danger errorValidate"></small>
                                                                            </div>
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="twitter"
                                                                                    class="form-label">{{ __('admin.twitter') }}</label>
                                                                                <input type="text"
                                                                                    class="form-control max-255"
                                                                                    id="twitter" name="twitter"
                                                                                    value="{{ $supplier->twitter }}"
                                                                                    placeholder="{{ __('admin.twitter_placeholder') }}">
                                                                                <small id="twitterError"
                                                                                    class="text-danger errorValidate"></small>
                                                                            </div>
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="linkedIn"
                                                                                    class="form-label">{{ __('admin.linkedIn') }}</label>
                                                                                <input type="text"
                                                                                    class="form-control max-255"
                                                                                    id="linkedin" name="linkedin"
                                                                                    value="{{ $supplier->linkedin }}"
                                                                                    placeholder="{{ __('admin.linkedIn_placeholder') }}">
                                                                                <small id="linkedinError"
                                                                                    class="text-danger errorValidate"></small>
                                                                            </div>
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="youtube"
                                                                                    class="form-label">{{ __('admin.youtube') }}</label>
                                                                                <input type="text"
                                                                                    class="form-control max-255"
                                                                                    id="youtube" name="youtube"
                                                                                    value="{{ $supplier->youtube }}"
                                                                                    placeholder="{{ __('admin.youtube_url_placeholder') }}">
                                                                                <small id="youtubeError"
                                                                                    class="text-danger errorValidate"></small>
                                                                            </div>
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="instagram"
                                                                                    class="form-label">{{ __('admin.instagram') }}</label>
                                                                                <input type="text"
                                                                                    class="form-control max-255"
                                                                                    id="instagram" name="instagram"
                                                                                    value="{{ $supplier->instagram }}"
                                                                                    placeholder="{{ __('admin.instagram_placeholder') }}">
                                                                                <small id="instagramError"
                                                                                    class="text-danger errorValidate"></small>
                                                                            </div>
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="expected_date"
                                                                                    class="form-label">{{ __('admin.established') }}</label>
                                                                                <div id="date"
                                                                                    class="input-group date datepicker">
                                                                                    <input type="text" readonly=""
                                                                                        id="established_date"
                                                                                        placeholder="{{ __('admin.established_placeholder') }}"
                                                                                        name="established_date"
                                                                                        class="form-control"
                                                                                        style="border: 1px solid #dee2e6;background-color:white;"
                                                                                        value="{{ !empty($supplier->established_date) ? date('d-m-Y', strtotime($supplier->established_date)) : '' }}">
                                                                                    <span
                                                                                        class="input-group-addon input-group-append border-left">
                                                                                        <span
                                                                                            class="mdi mdi-calendar input-group-text"
                                                                                            style="border: 1px solid #dee2e6;"></span>
                                                                                    </span>
                                                                                    <small id="established_dateError"
                                                                                        class="text-danger errorValidate"></small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <div class="card mb-3">
                                                                    <div class="card-header d-flex align-items-center">
                                                                        <h5 class="mb-0"><img height="20px"
                                                                                src="{{ URL::asset('assets/icons/icon_address.png') }}"
                                                                                alt="Charges" class="pe-2">
                                                                            <span>{{ __('admin.location') }}</span>
                                                                            <!-- Code commented by sachin sanchania for removing multiple address add for supplier profile
                                                                            <span class="icon ms-1">
                                                                                <a href="javascript:void(0)" data-toggle="tooltip" ata-placement="top" title="{{__('admin.add_more')}}"
                                                                                    id="locationBtnClone">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        width="16" height="16"
                                                                                        fill="currentColor"
                                                                                        class="bi bi-plus-circle"
                                                                                        viewBox="0 0 16 16">
                                                                                        <path
                                                                                            d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z">
                                                                                        </path>
                                                                                        <path
                                                                                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z">
                                                                                        </path>
                                                                                    </svg>
                                                                                </a>
                                                                            </span>
                                                                            -->
                                                                        </h5>
                                                                    </div>

                                                                    <div id="mainLocationInHtml"
                                                                        class="card-body p-3 pb-1">
                                                                        @if (!empty($supplierAddress))
                                                                            @php $k = 0 ; @endphp
                                                                            @foreach ($supplierAddress as $key => $address)
                                                                                <div class="row location_in_html">
                                                                                    <div class="col-md-12 mb-3">
                                                                                        <label for="address"
                                                                                            class="form-label">{{ __('admin.company_address') }}
                                                                                        </label>
                                                                                        <span
                                                                                            class="icon float-end supplierCompanyDelete trashicon {{ $k == 0 ? 'hidden' : '' }}"
                                                                                            style=""><a
                                                                                                href="javascript:void(0)"
                                                                                                id=""
                                                                                                class="text-danger"><i
                                                                                                    class="fa fa-trash"></i></a></span>
                                                                                        <textarea name="address[]" data-cloneid="{{ $k }}" class="form-control" cols="30" rows="3">{{ !empty($address['address']) ? strip_tags($address['address']) : '' }}</textarea>
                                                                                        <small id="addressError"
                                                                                            class="text-danger"></small>
                                                                                    </div>
                                                                                </div>
                                                                                @php $k++ @endphp
                                                                            @endforeach
                                                                        @else
                                                                            <div class="row location_in_html">
                                                                                <div class="col-md-12 mb-3">
                                                                                    <label for="address" class="form-label">{{ __('admin.company_address') }} </label>
                                                                                    <span class="icon float-end supplierCompanyDelete trashicon hidden" style=""><a href="javascript:void(0)"  class="text-danger"><i class="fa fa-trash"></i></a></span>
                                                                                    <textarea name="address[]"  data-cloneid="0" class="form-control"  cols="30" rows="3" ></textarea>
                                                                                    <small id="addressError" class="text-danger errorValidate"></small>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div id="locationContainer"
                                                                        class="card-body p-3 pb-0 pt-0"></div>
                                                                </div>

                                                            </div>

                                                            <div class="col-md-12 mb-2">
                                                                <div class="card mb-3">
                                                                    <div class="card-header d-flex align-items-center">
                                                                        <h5 class="mb-0"><img height="20px"
                                                                                src="{{ URL::asset('assets/icons/attachement.png') }}"
                                                                                alt="Charges" class="pe-2">
                                                                            <span>{{ __('admin.attachment') }}</span></h5>
                                                                    </div>
                                                                    <div class="card-body p-3 pb-1">
                                                                        <div class="row">
                                                                            <div class="col-md-3 mb-3 cursor-pointer">
                                                                                <label for="catalog"
                                                                                    class="form-label">{{ __('admin.catalog') }}</label>
                                                                                <div class="d-flex">
                                                                                    <span class=""><input
                                                                                            type="file" name="catalog"
                                                                                            class="form-control"
                                                                                            id="catalog"
                                                                                            accept=".jpg,.png,.gif,.jpeg,.pdf"
                                                                                            onchange="show(this)"
                                                                                            hidden /><label
                                                                                            class="upload_btn"
                                                                                            for="catalog">{{ __('admin.upload_file') }}</label></span>
                                                                                    <div id="file-catalog">
                                                                                        @if ($supplier->catalog)
                                                                                            @php
                                                                                                $extension_catalog = substr($supplier->catalog, -4);
                                                                                                $catalog_filename = substr(Str::substr($supplier->catalog, stripos($supplier->catalog, 'catalog_') + 8), 0, -4);
                                                                                                if (strlen($catalog_filename) > 10) {
                                                                                                    $catalog_name = substr($catalog_filename, 0, 8) . '...' . $extension_catalog;
                                                                                                } else {
                                                                                                    $catalog_name = $catalog_filename . $extension_catalog;
                                                                                                }
                                                                                            @endphp
                                                                                            <input type="hidden"
                                                                                                class="form-control"
                                                                                                id="oldcatalog"
                                                                                                name="oldcatalog"
                                                                                                value="{{ $supplier->catalog }}">
                                                                                            <span class="ms-2"><a
                                                                                                    href="javascript:void(0);"
                                                                                                    id="catalogFileDownload"
                                                                                                    onclick="downloadimg('{{ $supplier->id }}', 'catalog', '{{ Str::substr($supplier->catalog, stripos($supplier->catalog, 'catalog_') + 8) }}')"
                                                                                                    title="{{ Str::substr($supplier->catalog, stripos($supplier->catalog, 'catalog_') + 8) }}"
                                                                                                    style="text-decoration: none;">
                                                                                                    {{ $catalog_name }}</a></span>
                                                                                            <span class="removeFile"
                                                                                                id="catalogFile"
                                                                                                data-id="{{ $supplier->id }}"
                                                                                                file-path="{{ $supplier->catalog }}"
                                                                                                data-name="catalog"><a
                                                                                                    href="#"
                                                                                                    title="Remove catalog">
                                                                                                    <img src="{{ URL::asset('assets/icons/times-circle copy.png') }}"
                                                                                                        alt="CLose button"
                                                                                                        class="ms-2"></a></span>
                                                                                            <span class="ms-2"><a
                                                                                                    class="catalogFile"
                                                                                                    href="javascript:void(0);"
                                                                                                    title="Download catalog"
                                                                                                    onclick="downloadimg('{{ $supplier->id }}', 'catalog', '{{ Str::substr($supplier->catalog, stripos($supplier->catalog, 'catalog_') + 8) }}')"
                                                                                                    style="text-decoration: none;"><i
                                                                                                        class="fa fa-cloud-download"></i></a></span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="pricing"
                                                                                    class="form-label">{{ __('admin.pricing') }}</label>
                                                                                <div class="d-flex">
                                                                                    <span class=""><input
                                                                                            type="file" name="pricing"
                                                                                            class="form-control"
                                                                                            id="pricing"
                                                                                            accept=".jpg,.png,.gif,.jpeg, .pdf"
                                                                                            onchange="show(this)"
                                                                                            hidden /><label
                                                                                            class="upload_btn"
                                                                                            for="pricing">{{ __('admin.upload_file') }}</label></span>
                                                                                    <div id="file-pricing">
                                                                                        <input type="hidden"
                                                                                            class="form-control"
                                                                                            id="oldpricing"
                                                                                            name="oldpricing"
                                                                                            value="{{ $supplier->pricing }}">
                                                                                        @if ($supplier->pricing)
                                                                                            @php
                                                                                                $extension_pricing = substr($supplier->pricing, -4);
                                                                                                $pricing_filename = substr(Str::substr($supplier->pricing, stripos($supplier->pricing, 'pricing_') + 8), 0, -4);
                                                                                                if (strlen($pricing_filename) > 10) {
                                                                                                    $pricing_name = substr($pricing_filename, 0, 8) . '...' . $extension_pricing;
                                                                                                } else {
                                                                                                    $pricing_name = $pricing_filename . $extension_pricing;
                                                                                                }
                                                                                            @endphp
                                                                                            <input type="hidden"
                                                                                                class="form-control"
                                                                                                id="oldpricing"
                                                                                                name="oldpricing"
                                                                                                value="{{ $supplier->pricing }}">
                                                                                            <span class="ms-2"><a
                                                                                                    href="javascript:void(0);"
                                                                                                    id="pricingFileDownload"
                                                                                                    onclick="downloadimg('{{ $supplier->id }}', 'pricing', '{{ Str::substr($supplier->pricing, stripos($supplier->pricing, 'pricing_') + 8) }}')"
                                                                                                    title="{{ Str::substr($supplier->pricing, stripos($supplier->pricing, 'pricing_') + 8) }}"
                                                                                                    style="text-decoration: none;">
                                                                                                    {{ $pricing_name }}</a></span>
                                                                                            <span class="removeFile"
                                                                                                id="pricingFile"
                                                                                                data-id="{{ $supplier->id }}"
                                                                                                file-path="{{ $supplier->pricing }}"
                                                                                                data-name="pricing"><a
                                                                                                    href="#"
                                                                                                    title="Remove pricing">
                                                                                                    <img src="{{ URL::asset('assets/icons/times-circle copy.png') }}"
                                                                                                        alt="CLose button"
                                                                                                        class="ms-2"></a></span>
                                                                                            <span class="ms-2"><a
                                                                                                    class="pricingFile"
                                                                                                    href="javascript:void(0);"
                                                                                                    title="Download pricing"
                                                                                                    onclick="downloadimg('{{ $supplier->id }}', 'pricing', '{{ Str::substr($supplier->pricing, stripos($supplier->pricing, 'pricing_') + 8) }}')"
                                                                                                    style="text-decoration: none;"><i
                                                                                                        class="fa fa-cloud-download"></i></a></span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="product"
                                                                                    class="form-label">{{ __('admin.product') }}</label>
                                                                                <div class="d-flex">
                                                                                    <span class=""><input
                                                                                            type="file" name="product"
                                                                                            class="form-control"
                                                                                            id="product"
                                                                                            accept=".jpg,.png,.gif,.jpeg, .pdf"
                                                                                            onchange="show(this)"
                                                                                            hidden /><label
                                                                                            class="upload_btn"
                                                                                            for="product">{{ __('admin.upload_file') }}</label></span>
                                                                                    <div id="file-product">
                                                                                        <input type="hidden"
                                                                                            class="form-control"
                                                                                            id="oldproduct"
                                                                                            name="oldproduct"
                                                                                            value="{{ $supplier->product }}">
                                                                                        @if ($supplier->product)
                                                                                            @php
                                                                                                $extension_product = substr($supplier->product, -4);
                                                                                                $product_filename = substr(Str::substr($supplier->product, stripos($supplier->product, 'product_') + 8), 0, -4);
                                                                                                if (strlen($product_filename) > 10) {
                                                                                                    $product_name = substr($product_filename, 0, 8) . '...' . $extension_product;
                                                                                                } else {
                                                                                                    $product_name = $product_filename . $extension_product;
                                                                                                }
                                                                                            @endphp
                                                                                            <input type="hidden"
                                                                                                class="form-control"
                                                                                                id="oldproduct"
                                                                                                name="oldproduct"
                                                                                                value="{{ $supplier->product }}">
                                                                                            <span class="ms-2"><a
                                                                                                    href="javascript:void(0);"
                                                                                                    id="productFileDownload"
                                                                                                    onclick="downloadimg('{{ $supplier->id }}', 'product', '{{ Str::substr($supplier->product, stripos($supplier->product, 'product_') + 8) }}')"
                                                                                                    title="{{ Str::substr($supplier->product, stripos($supplier->product, 'product_') + 8) }}"
                                                                                                    style="text-decoration: none;">
                                                                                                    {{ $product_name }}</a></span>
                                                                                            <span class="removeFile"
                                                                                                id="productFile"
                                                                                                data-id="{{ $supplier->id }}"
                                                                                                file-path="{{ $supplier->product }}"
                                                                                                data-name="product"><a
                                                                                                    href="#"
                                                                                                    title="Remove product">
                                                                                                    <img src="{{ URL::asset('assets/icons/times-circle copy.png') }}"
                                                                                                        alt="CLose button"
                                                                                                        class="ms-2"></a></span>
                                                                                            <span class="ms-2"><a
                                                                                                    class="productFile"
                                                                                                    href="javascript:void(0);"
                                                                                                    title="Download product"
                                                                                                    onclick="downloadimg('{{ $supplier->id }}', 'product', '{{ Str::substr($supplier->product, stripos($supplier->product, 'product_') + 8) }}')"
                                                                                                    style="text-decoration: none;"><i
                                                                                                        class="fa fa-cloud-download"></i></a></span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3 mb-3">
                                                                                <label for="commercialCondition"
                                                                                    class="form-label">{{ __('admin.commercial_conditions') }}</label>
                                                                                <div class="d-flex">
                                                                                    <span class=""><input
                                                                                            type="file"
                                                                                            name="commercialCondition"
                                                                                            class="form-control"
                                                                                            id="commercialCondition"
                                                                                            accept=".jpg,.png,.gif,.jpeg, .pdf"
                                                                                            onchange="show(this)"
                                                                                            hidden /><label
                                                                                            class="upload_btn"
                                                                                            for="commercialCondition">{{ __('admin.upload_file') }}</label></span>
                                                                                    <div id="file-commercialCondition">
                                                                                        <input type="hidden"
                                                                                            class="form-control"
                                                                                            id="oldproduct"
                                                                                            name="oldproduct"
                                                                                            value="{{ $supplier->product }}">
                                                                                        @if ($supplier->commercialCondition)
                                                                                            @php
                                                                                                $extension_commercialCondition = substr($supplier->commercialCondition, -4);
                                                                                                $commercialCondition_filename = substr(Str::substr($supplier->commercialCondition, stripos($supplier->commercialCondition, 'termsconditions_file_') + 21), 0, -4);
                                                                                                if (strlen($commercialCondition_filename) > 10) {
                                                                                                    $commercialCondition_name = substr($commercialCondition_filename, 0, 8) . '...' . $extension_commercialCondition;
                                                                                                } else {
                                                                                                    $commercialCondition_name = $commercialCondition_filename . $extension_commercialCondition;
                                                                                                }
                                                                                            @endphp
                                                                                            <input type="hidden"
                                                                                                class="form-control"
                                                                                                id="oldcommercialCondition"
                                                                                                name="oldcommercialCondition"
                                                                                                value="{{ $supplier->commercialCondition }}">
                                                                                            <span class="ms-2"><a
                                                                                                    href="javascript:void(0);"
                                                                                                    id="commercialConditionFileDownload"
                                                                                                    onclick="downloadimg('{{ $supplier->id }}', 'commercialCondition', '{{ Str::substr($supplier->commercialCondition, stripos($supplier->commercialCondition, 'termsconditions_file_') + 21) }}')"
                                                                                                    title="{{ Str::substr($supplier->commercialCondition, stripos($supplier->commercialCondition, 'termsconditions_file_') + 21) }}"
                                                                                                    style="text-decoration: none;">
                                                                                                    {{ $commercialCondition_name }}</a></span>
                                                                                            <span class="removeFile"
                                                                                                id="commercialConditionFile"
                                                                                                data-id="{{ $supplier->id }}"
                                                                                                file-path="{{ $supplier->commercialCondition }}"
                                                                                                data-name="commercialCondition"><a
                                                                                                    href="#"
                                                                                                    title="Remove commercial condition">
                                                                                                    <img src="{{ URL::asset('assets/icons/times-circle copy.png') }}"
                                                                                                        alt="CLose button"
                                                                                                        class="ms-2"></a></span>
                                                                                            <span class="ms-2"><a
                                                                                                    class="commercialConditionFile"
                                                                                                    href="javascript:void(0);"
                                                                                                    title="Download commercialCondition"
                                                                                                    onclick="downloadimg('{{ $supplier->id }}', 'commercialCondition', '{{ Str::substr($supplier->commercialCondition, stripos($supplier->commercialCondition, 'termsconditions_file_') + 21) }}')"
                                                                                                    style="text-decoration: none;"><i
                                                                                                        class="fa fa-cloud-download"></i></a></span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @if (Auth::user())
                                                                                <div class="col-12">
                                                                                    <div
                                                                                        class="form-check form-check-flat form-check-primary">
                                                                                        <label
                                                                                            class="form-check-label ms-4">
                                                                                            <input type="checkbox" disabled
                                                                                                checked
                                                                                                class="form-check-input"
                                                                                                name="terms"
                                                                                                id="terms">
                                                                                            {{ __('admin.agree') }}
                                                                                            <i class="input-helper"></i><a
                                                                                                href="javascript:void(0)"
                                                                                                id="showtc">{{ __('admin.terms_and_conditions') }}</a></label>
                                                                                    </div>
                                                                                </div>
                                                                            @else
                                                                                <div class="col-12">
                                                                                    <div
                                                                                        class="form-check form-check-flat form-check-primary">
                                                                                        <label class="form-check-label">
                                                                                            <input type="checkbox"
                                                                                                class="form-check-input"
                                                                                                name="terms"
                                                                                                id="terms">
                                                                                            {{ __('admin.agree') }}
                                                                                            <i class="input-helper"></i><a
                                                                                                href="javascript:void(0)"
                                                                                                id="showtc">{{ __('admin.terms_and_conditions') }}</a></label>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="text-end">
                                                                    <button class="btn btn-primary" type="submit"
                                                                        class="companyDetailCls"
                                                                        id="companydetailsSubmit">{{ __('admin.save') }}</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!-- Section 1 End -->
                                                <!-- Section 2 Start -->
                                                <div class="tab-pane fade bg-white" id="pills-BusinessDetails"
                                                    role="tabpanel" aria-labelledby="pills-BusinessDetails-tab"
                                                    tabindex="0">
                                                    @livewire('supplier.profile.business-details', ['supplierId' => $supplier->id, 'categories' => $categories])

                                                    @livewireScripts
                                                </div>
                                                <!-- Section 2 End -->
                                                <!-- Section 3 Start -->
                                                <div class="tab-pane fade bg-white" id="pillsBasicDetails"
                                                    role="tabpanel" aria-labelledby="pills-BasicDetails-tab"
                                                    tabindex="0">
                                                    @livewire('supplier.profile.basic-details', ['supplierId' => $supplier->id])
                                                </div>
                                                <!-- Section 3 End -->
                                                <!-- Section 4 Start -->
                                                <div class="tab-pane fade bg-white" id="pills-CoreTeam" role="tabpanel"
                                                    aria-labelledby="pills-CoreTeam-tab" tabindex="0">
                                                    @livewire('supplier.profile.core-team', ['supplierId' => $supplier->id, 'company_user_type' => 1, 'slug' => 'coreteam', 'typeName' => 'Core Team'])
                                                </div>
                                                <!-- Section 4 End -->
                                                <!-- Section 5 Start -->
                                                <div class="tab-pane fade bg-white" id="pills-Portfolio" role="tabpanel"
                                                    aria-labelledby="pills-Portfolio-tab" tabindex="0">
                                                    @livewire('supplier.profile.portfolio', ['supplierId' => $supplier->id, 'companyDetails' => $companyDetails])
                                                </div>
                                                <!-- Section 5 End -->
                                                <!-- Section 6 Start -->
                                                <div class="tab-pane fade bg-white" id="pills-Highlights" role="tabpanel"
                                                    aria-labelledby="pills-Highlights-tab" tabindex="0">
                                                    @livewire('supplier.profile.company-highlights', ['supplierId' => $supplier->id])
                                                </div>
                                                <!-- Section 6 End -->
                                                <!-- Section 7 Start -->
                                                <div class="tab-pane fade bg-white" id="pills-others" role="tabpanel"
                                                    aria-labelledby="pills-others-tab" tabindex="0">
                                                    @livewire('supplier.profile.testimonial', ['supplierId' => $supplier->id, 'company_user_type' => 2, 'slug' => 'testimonial', 'typeName' => 'Testimonial'])
                                                    <!--  -->
                                                    @livewire('supplier.profile.company-partner', ['supplierId' => $supplier->id, 'company_user_type' => 3, 'slug' => 'partner', 'typeName' => 'Company Partner'])
                                                    <!--  -->
                                                    @livewire('supplier.profile.gallery', ['supplierId' => $supplier->id])
                                                    @stack('gallery-scripts')
                                                    <!--  -->
                                                    <div class="text-end">
                                                        <button class="btn btn-primary"
                                                            onclick="$('#productTab').click()">{{ __('admin.go_to_product') }}</button>
                                                    </div>
                                                </div>
                                                <!-- Section 7 End -->
                                            </div>
                                        </div>
                                    </div>
                                    <div id="step-2" class="tab-pane p-0" role="tabpanel" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-12 mb-2">
                                                <div class="card">
                                                    <div class="card-header d-flex align-items-center">
                                                        <h5 class="mb-0"><img height="20px"
                                                                src="{{ URL::asset('assets/icons/credit-card.png') }}"
                                                                alt="Charges" class="pe-2">
                                                            <span>{{ __('admin.product_details') }}</span></h5>
                                                    </div>
                                                    <div class="card-body p-3 pb-1">
                                                        <div class="row">
                                                            <div class="d-flex align-items-center justify-content-end">
                                                                <div class="pe-1 mb-3 clearfix">
                                                                    <a class="btn btn-info btn-sm"
                                                                        data-bs-toggle="collapse" href="#tableFilters"
                                                                        role="button" aria-expanded="false"
                                                                        aria-controls="tableFilters">{{ __('admin.filters') }}</a>

                                                                    <button type="button" id="addProductModalBtn"
                                                                        class="btn btn-primary btn-sm"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#addProductModal">{{ __('admin.add') }}</button>
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
                                                                    <table id="supplierProductTable"
                                                                        class="table border table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>{{ __('admin.category') }}</th>
                                                                                <th>{{ __('admin.sub_category') }}</th>
                                                                                <th>{{ __('admin.product') }}</th>
                                                                                <th class="text-center">
                                                                                    {{ __('admin.actions') }}</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($supplierProduct as $product)
                                                                                <tr
                                                                                    id="supplierProductDetailsRow_{{ $product->supplier_products_id }}">
                                                                                    <td
                                                                                        id="t_category_name_{{ $product->supplier_products_id }}">
                                                                                        {{ $product->category_name }}</td>
                                                                                    <td
                                                                                        id="t_sub_category_name_{{ $product->supplier_products_id }}">
                                                                                        {{ $product->sub_category_name }}
                                                                                    </td>
                                                                                    <td
                                                                                        id="t_product_name_{{ $product->supplier_products_id }}">
                                                                                        {{ $product->product_name }}</td>
                                                                                    <td class="text-end text-nowrap">
                                                                                        <a href="javascript:void(0)"
                                                                                            data-id="{{ $product->supplier_products_id }}"
                                                                                            class="editSupplierProductDetails"
                                                                                            data-toggle="tooltip"
                                                                                            ata-placement="top"
                                                                                            title="{{ __('admin.edit') }}"><i
                                                                                                class="fa fa-edit"></i></a>
                                                                                        <a href="javascript:void(0)"
                                                                                            data-id="{{ $product->supplier_products_id }}"
                                                                                            class="ps-2 deleteSupplierProductDetails"
                                                                                            data-toggle="tooltip"
                                                                                            ata-placement="top"
                                                                                            title="{{ __('admin.delete') }}">
                                                                                            <i
                                                                                                class="fa fa-trash"></i></a>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>

                                                                    </table>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button class="btn btn-primary" onclick="$('#accountDetailsTab').click()">{{ __('admin.go_to_account_details') }}</button>
                                        </div>
                                    </div>
                                    <div id="step-3" class="tab-pane p-0" role="tabpanel" style="display: none;">
                                        <div class="row">
                                            @php $xenClass = ''; @endphp
                                            @if (empty($supplier->xen_platform_id))
                                                @php $xenClass = 'hidden'; @endphp
                                                @php $xenStyle=''; @endphp
                                                @if ($supplier->status == 0)
                                                    @php $xenStyle='display:none'; @endphp
                                                @endif
                                                <div class="col-md-12 mb-3 d-flex justify-content-end cursor-pointer">
                                                    <button type="button" class="btn btn-primary"
                                                        style="{{ $xenStyle }}" data-bs-toggle="modal"
                                                        data-bs-target="#xenditpopup" id="create-xenaccount">
                                                        Create Xendit Account
                                                    </button>
                                                </div>
                                            @endif
                                            <div class="col-md-12 pb-3 {{ $xenClass }}" id="xen-div">
                                                <div class="card">
                                                    <div class="card-header d-flex align-items-center">
                                                        <div>
                                                            <h5><img height="20px"
                                                                    src="{{ URL::asset('front-assets/images/icons/xendit.png') }}"
                                                                    alt="Supplier Details" class="pe-2">
                                                                {{ __('admin.xen_account_detail') }}</h5>
                                                        </div>
                                                        <div class="ms-auto">
                                                            <a href="#xenditpopupemail" data-bs-toggle="modal" data-bs-target="#xenditpopupemail" class="" id="xen-email-edit-btn">
                                                                <i class="fa fa-edit" data-toggle="tooltip" ata-placement="top" title="" data-bs-original-title="{{__('admin.edit')}}" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-3 pb-1">
                                                        <div class="row rfqform_view bg-white">
                                                            <div class="col-md-3 pb-2">
                                                                <label>{{ __('admin.xen_platform_id') }}</label>
                                                                <div class="text-dark" id="xen_platform_id"></div>
                                                            </div>
                                                            <div class="col-md-3 pb-2">
                                                                <label>{{ __('admin.xen_platform_balance') }}</label>
                                                                <div id="xen_balance"><img height="16px"
                                                                        src="{{ URL::asset('front-assets/images/icons/timer.gif') }}"
                                                                        title="Loading"></div>
                                                            </div>
                                                            <div class="col-md-3 pb-2">
                                                                <label>{{ __('admin.company_name') }}</label>
                                                                <div class="text-dark" id="supplier_company_name"></div>
                                                            </div>
                                                            <div class="col-md-3 pb-2">
                                                                <label>{{ __('admin.email') }}</label>
                                                                <div class="text-dark" id="supplier_email"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <div class="card">
                                                    <div class="card-header d-flex align-items-center">
                                                        <h5 class="mb-0"><img height="20px"
                                                                src="{{ URL::asset('assets/icons/credit-card.png') }}"
                                                                alt="Charges" class="pe-2">
                                                            <span>{{ __('admin.bank_details') }}</span></h5>
                                                    </div>
                                                    <div class="card-body p-3 pb-1">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-3 newtable_v2">
                                                                <div
                                                                    class="d-flex align-items-center justify-content-end pe-1 mb-3">
                                                                    <button type="button"
                                                                        class="btn btn-primary btn-sm"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#bankdetail">{{ __('admin.add') }}</button>
                                                                </div>
                                                                <div class="table-responsive">
                                                                    <table id="supplierBankTable"
                                                                        class="table table-hover border"
                                                                        style="width: 100%;">
                                                                        <thead>
                                                                            <tr class="bg-light">
                                                                                <th>{{ __('admin.bank_logo') }}</th>
                                                                                <th>{{ __('admin.bank_name') }}</th>
                                                                                <th>{{ __('admin.bank_code') }}</th>
                                                                                <th>{{ __('admin.bank_account_name') }}
                                                                                </th>
                                                                                <th>{{ __('admin.bank_account_number') }}
                                                                                </th>
                                                                                <th>{{ __('admin.primary_secondary') }}
                                                                                </th>
                                                                                <th class="text-center">
                                                                                    {{ __('admin.actions') }}</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody class="bg-white">
                                                                            @foreach ($supplierBanks as $supplierBank)
                                                                                @php
                                                                                    $bankDetail = $supplierBank->bankDetail()->first(['name', 'code', 'logo']);
                                                                                @endphp
                                                                                <tr
                                                                                    id="addbankdetails_{{ $supplierBank->id }}">
                                                                                    {{--
                                                                                <td class="t_bank_id">{{$supplierBank->id}}</td>
                                                                                --}}
                                                                                    <td>
                                                                                        <div class="p-1 pe-1">
                                                                                            @if ($bankDetail->logo)
                                                                                                <img src="{{ URL($bankDetail->logo) }}"
                                                                                                    class="t_bank_logo"
                                                                                                    height="20px"
                                                                                                    width="20px"
                                                                                                    alt="bank Logo">
                                                                                            @else
                                                                                                <img src="{{ URL('assets/icons/bank.png') }}"
                                                                                                    class="t_bank_logo"
                                                                                                    height="20px"
                                                                                                    width="20px"
                                                                                                    alt="bank Logo">
                                                                                            @endif
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <span
                                                                                            class="t_bank_name ps-1">{{ $bankDetail->name }}</span>
                                                                                    </td>
                                                                                    <td class="t_bank_code">
                                                                                        {{ $bankDetail->code }}</td>
                                                                                    <td class="t_bank_account_name">
                                                                                        {{ $supplierBank->bank_account_name }}
                                                                                    </td>
                                                                                    <td class="t_bank_account_number">
                                                                                        {{ $supplierBank->bank_account_number }}
                                                                                    </td>
                                                                                    <td class="t_primary_others">
                                                                                        <div class="form-check form-switch"
                                                                                            style="margin-left: 0px;">
                                                                                            <input
                                                                                                class="form-check-input account_is_primary"
                                                                                                data-id="{{ $supplierBank->id }}"
                                                                                                type="checkbox"
                                                                                                id="account_is_primary_{{ $supplierBank->id }}"
                                                                                                {{ $supplierBank->is_primary ? 'checked' : '' }}>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td class="text-end text-nowrap"> <a
                                                                                            href="javascript:void(0)"
                                                                                            data-id="{{ $supplierBank->id }}"
                                                                                            class="editSupplierBankDetails"
                                                                                            data-toggle="tooltip"
                                                                                            ata-placement="top"
                                                                                            title="{{ __('admin.edit') }}"><i
                                                                                                class="fa fa-edit"></i></a>
                                                                                        <a href="javascript:void(0)"
                                                                                            data-id="{{ $supplierBank->id }}"
                                                                                            class="deletebankdetails ps-2"
                                                                                            data-toggle="tooltip"
                                                                                            ata-placement="top"
                                                                                            title="{{ __('admin.delete') }}">
                                                                                            <i
                                                                                                class="fa fa-trash"></i></a>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Step-2 --}}
    <div class="modal fade error_res version2" id="addProductModal" data-bs-backdrop="static" tabindex="-1"
        role="dialog" aria-labelledby="addProductLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-0">
                <div class="modal-header py-3">
                    <h5 class="modal-title" id="addProductLabel" style="color: white;">{{ __('admin.product') }}
                    </h5>
                    <button type="button" class="btn-close ms-0 d-flex resetModel" data-bs-dismiss="modal"
                        aria-label="Close">
                        <img src="{{ URL('front-assets/images/icons/times.png') }}" alt="Close">
                    </button>
                </div>
                <div class="modal-body p-3 pb-1">
                    <div class="">
                        <form id="supplierProductForm" name="supplierProductForm" enctype="multipart/form-data" class="form-group row">
                            @csrf
                            <div class="col-md-12 pb-2">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center">
                                        <h5 class="mb-0"><img src="{{ URL('front-assets/images/icons/shopping-cart.png') }}" alt="Order Details" class="pe-2"> {{ __('admin.category_details') }}
                                        </h5>
                                    </div>
                                    <div class="card-body p-3 pb-1">
                                        <div class="row rfqform_view bg-white">
                                            <div class="col-md-12 mb-3">

                                                    <div class="search_rfq">
                                                        <label class="form-label d-flex justify-content-between"  for="Search_category" style="left: 10px;">{{__('admin.search_product')}} <span class="ms-1 count fw-bold" style="display: none"></span></label>
                                                        <input type="text" name="tags" id="tags" class="form-control categorysearch" placeholder="{{__('admin.product_search')}}" />
                                                    </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="supplierCategory"
                                                    class="form-label">{{ __('admin.category') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="position-relative">
                                                    <select name="supplierCategory" id="supplierCategory"
                                                        class="form-control selectBox">
                                                        <option selected disabled>{{ __('admin.select_category') }}
                                                        </option>
                                                        @foreach ($category as $categoryItem)
                                                            <option value="{{ $categoryItem->id }}"
                                                                data-text="{{ $categoryItem->name }}">
                                                                {{ $categoryItem->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="fa fa-chevron-down downArrowIcon"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="supplierSubCategory"
                                                    class="form-label">{{ __('admin.sub_category') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="position-relative">
                                                    <select name="supplierSubCategory" id="supplierSubCategory"
                                                        class="form-control selectBox">
                                                        <option selected disabled>
                                                            {{ __('admin.select_product_sub_category') }}</option>
                                                    </select>
                                                    <i class="fa fa-chevron-down downArrowIcon"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3" id="">
                                                <label for="supplierProduct"
                                                    class="form-label">{{ __('admin.product_name') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="position-relative">
                                                    <select name="supplierProduct" id="supplierProduct"
                                                        class="form-control selectBox">
                                                        <option selected disabled>{{ __('admin.select_product_name') }}
                                                        </option>
                                                    </select>
                                                    <i class="fa fa-chevron-down downArrowIcon"></i>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 pb-2">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center">
                                        <h5 class="mb-0"><img height="20px"
                                                src="{{ URL('front-assets/images/icons/people-carry-1.png') }}"
                                                alt="Supplier Details" class="pe-2">
                                            {{ __('admin.product_detail') }}</h5>
                                    </div>
                                    <div class="card-body p-3 pb-1">
                                        <div class="row rfqform_view bg-white">

                                            <div class="col-md-6 mb-3">
                                                <label for="productRef"
                                                    class="form-label">{{ __('admin.product_code_specification') }}<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="productRef" id="productRef"
                                                    class="form-control">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label
                                                    class="form-label">{{ __('admin.upload_product_images') }}</label>
                                                <div class="d-flex text-center" style="align-items: center;">
                                                    <span class=""><input type="file" name="productImages"
                                                            id="productImages" accept="image/*" onchange="show(this)"
                                                            hidden /><label class="upload_btn text-white"
                                                            for="productImages">{{ __('admin.browse') }}</label></span>
                                                    <div id="file-productImages">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-3" id="">
                                                <label for="supplierProductDiscription"
                                                    class="form-label">{{ __('admin.product_description') }}<span
                                                        class="text-danger">*</span></label>
                                                <input name="supplierProductDiscription" id="supplierProductDiscription"
                                                    class="form-control" type="text"
                                                    list="supplierProductDiscriptionList" />
                                                <datalist id="supplierProductDiscriptionList">
                                                    @foreach ($products->unique('description') as $productsItem)
                                                        <option>{{ $productsItem->description }}</option>
                                                    @endforeach
                                                </datalist>
                                            </div>

                                            <div class="col-md-6  mb-3" id="brandBlock">
                                                <label for="supplierProductBrand"
                                                    class="form-label">{{ __('admin.brand') }}({{ __('admin.comma_seprated') }})</label>
                                                <input data-list="{{ $brand }}" name="supplierProductBrand"
                                                    id="supplierProductBrand" data-multiple class="form-control" />
                                            </div>
                                            <div class="col-md-6 mb-3" id="gradeBlock">
                                                <label for="supplierProductGrade"
                                                    class="form-label">{{ __('admin.grade') }}({{ __('admin.comma_seprated') }})</label>
                                                <input data-list="{{ $grade }}" name="supplierProductGrade"
                                                    id="supplierProductGrade" data-multiple class="form-control" />
                                            </div>


                                            <div class="col-md-6  mb-3">
                                                <label for="supplierProductPrice"
                                                    class="form-label">{{ __('admin.price') }}<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="supplierProductPrice"
                                                    id="supplierProductPrice" class="form-control"
                                                    onkeypress="return isNumberKey(this, event);">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="supplierProductUnit"
                                                    class="form-label">{{ __('admin.select_unit') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="position-relative">
                                                    <select name="supplierProductUnit" id="supplierProductUnit"
                                                        class="form-control selectBox" onchange="unitChange($(this))">
                                                        <option selected disabled>{{ __('admin.select_unit') }}</option>
                                                        @foreach ($unit as $unitItem)
                                                            <option value="{{ $unitItem->id }}">{{ $unitItem->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <i class="fa fa-chevron-down downArrowIcon"></i>
                                                </div>
                                            </div>


                                            <div class="col-md-6  mb-3">
                                                <label for="supplierProductMinQuantity"
                                                    class="form-label">{{ __('admin.minimum_order_quantity') }}<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="supplierProductMinQuantity"
                                                    id="supplierProductMinQuantity" class="form-control"
                                                    min="1" onchange="minOrderQty(this)"
                                                    onkeypress="return isNumberKey(this, event);">
                                                <div id="showErrMinOrder" name="showErrMinOrder"></div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="supplierProductMaxQuantity"
                                                    class="form-label">{{ __('admin.maximum_order_quantity') }}<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="supplierProductMaxQuantity"
                                                    id="supplierProductMaxQuantity" class="form-control"
                                                    data-parsley-type="number" data-parsley-minlength="1"
                                                    min="1" onchange="maxOrderQty(this)"
                                                    onkeypress="return isNumberKey(this, event);">
                                                <div id="showErrMaxOrder" name="showErrMaxOrder"></div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 ">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center">
                                        <h5 class="mb-0"><img height="20px"
                                                src="{{ URL::asset('assets/icons/boxes.png') }}" alt="Product Range"
                                                class="pe-2">
                                            <span>Product Range <span class="icon ms-1"><a href="javascript:void(0)"
                                                        id="btnClone" onclick="cloneDiv()"><svg
                                                            xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-plus-circle" viewBox="0 0 16 16">
                                                            <path
                                                                d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                            <path
                                                                d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                                        </svg></a></span></span>
                                        </h5>
                                    </div>
                                    <div class="card-body p-3 pb-1" id="removeCloanDiv">
                                        <div id="mainDiv">
                                            <div class="row productDiv g-2" id="group_productDiv">
                                                <div class="col-md-2 mb-3">
                                                    <label for="min_qty"
                                                        class="form-label min_qty">{{ __('admin.min_quantity') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="min_qty" name="min_qty[]"
                                                        class="form-control cloanMinQty" onchange="minQty(this)"
                                                        onkeypress="return isNumberKey(this, event);">
                                                    <div id="showErrMin" name="showErrMin[]"></div>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label for="max_qty"
                                                        class="form-label max_qty">{{ __('admin.max_quantity') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="max_qty" name="max_qty[]"
                                                        class="form-control cloanMaxQty" onchange="maxQty(this)"
                                                        onkeypress="return isNumberKey(this, event);">
                                                    <div id="showErr" name="showErr[]"></div>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label for="unit"
                                                        class="form-label unit">{{ __('admin.select_unit') }}</label>
                                                    <select class="form-select unitVal" id="unit" name="unit[]"
                                                        disabled style="height: 40px;">
                                                        <option disabled selected>{{ __('admin.select_unit') }}</option>
                                                        @foreach ($unit as $unitItem)
                                                            <option value="{{ $unitItem->id }}">{{ $unitItem->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label for="discount"
                                                        class="form-label discount">{{ __('admin.discount') }}%<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="discount" name="discount[]"
                                                        class="form-control discountVal"
                                                        onkeypress="return isNumberKey(this, event);">
                                                    <div id="showErrDiscount" name="showErrDiscount[]"></div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="discount_price"
                                                        class="form-label discount">{{ __('admin.discount_price') }}</label>
                                                    <input type="text" id="discount_price" name="discount_price[]"
                                                        class="form-control discountAmount" readonly>
                                                </div>
                                                <div class="col-md-1 pt-1 mb-3 ">
                                                    <label for="" class="form-label"></label>
                                                    <div style="line-height: 38px;" id="deleteBtn" name="deleteBtn[]">
                                                        <span class="icon deleteRange d-none"><a
                                                                href="javascript:void(0)" id="deleteBtn"
                                                                class="text-danger removeRange "><i
                                                                    class="fa fa-trash mt-3"></i></a></span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="cloneDiv"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-primary"
                        id="AddProductData">{{ __('admin.save') }}</button>
                    <button type="button" class="btn btn-cancel resetModel"
                        data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Stpe-2 ends --}}
    {{-- Stpe-3 --}}
    <div class="modal fade version2 error_res" id="bankdetail" tabindex="-1" role="dialog"
        aria-labelledby="addBankLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 800px;" role="document">
            <div class="modal-content border-0">
                <div class="modal-header p-3">
                    <h3 class="modal-title" style="color: white;" id="addBankLabel">{{ __('admin.bank_details') }}
                    </h3>
                    <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close">
                        <img src="{{ URL('front-assets/images/icons/times.png') }}" alt="Close">
                    </button>
                </div>
                <div class="modal-body p-3" style="background-color: #ebedf1;">
                    <div class="card">
                        <div class="card-header align-items-center" style="background-color: #d7d9df;">
                            <h5 class="mb-0"><img src="{{ URL('front-assets/images/icons/info.png') }}"
                                    height="20px" alt="Bank Logo" class="pe-2"> {{ __('admin.bank_info') }}</h5>
                        </div>
                        <div class="card-body p-3 pb-1">
                            <div class=" rfqform_view">
                                <form id="supplierBankForm" class="row" enctype="multipart/form-data"
                                    class="form-group" data-parsley-validate>
                                    @csrf
                                    <input type="hidden" name="id" id="supplier-bank-id" value="0">
                                    <!-- <div class="d-flex pb-2"> -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('admin.bank_name') }}<span
                                                class="text-danger">*</span></label>
                                        <div class="d-flex error_ui">
                                            <select class="form-select w-100 text-primary" name="bank_id"
                                                id="bank_id" onchange="bankChange($(this))" required="">
                                                <option value="">Select Bank</option>
                                                @foreach ($banks as $bank)
                                                    <option value="{{ $bank['id'] }}"
                                                        data-code="{{ $bank['code'] ?? '' }}"
                                                        data-logo="{{ $bank['logo'] ?? '' }}">{{ $bank['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="p-1 pe-2 "><img src="{{ URL('assets/icons/bank.png') }}"
                                                    height="20px" width="20px" alt="bank Logo" id="bank-logo">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ps-3  mb-3">
                                        <!-- <div class="error_ui"> -->
                                        <label for="bank-code" class="form-label">{{ __('admin.bank_code') }} </label>
                                        <input type="text" class="form-control" id="bank-code" value="XXXXXXXX"
                                            disabled>
                                        <!-- </div> -->
                                    </div>
                                    <!-- <div class="d-flex pb-2"> -->
                                    <div class="col-md-6  mb-3">
                                        <!-- <div class="error_ui"> -->
                                        <label for="bank-name"
                                            class="form-label">{{ __('admin.bank_account_holder_name') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="bank-name"
                                            name="bank_account_name" data-parsley-pattern="^[a-zA-Z ]+$"
                                            placeholder="John Doe" required="">
                                        <!-- </div> -->
                                    </div>
                                    <div class="col-md-6 ps-3  mb-3">
                                        <!-- <div class="error_ui"> -->
                                        <label for="bank-account"
                                            class="form-label">{{ __('admin.bank_account_number') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-alpha-numeric" id="bank-account"
                                            name="bank_account_number" data-parsley-pattern="[0-9]+" maxlength="18"
                                            data-parsley-length="[8, 18]" placeholder="XXXXXXXXXXXXXX" required="">
                                        <!-- </div> -->
                                    </div>
                                    <!-- </div> -->
                                    <div class="col-md-12 pb-2  mb-3">
                                        <!-- <div class="error_ui"> -->
                                        <label for="description"
                                            class="form-label">{{ __('admin.description') }}</label>
                                        <textarea class="form-control" placeholder="Description" id="description" name="description"></textarea>
                                        <!-- </div> -->
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input mt-1" style="margin-left: 0px;"
                                                type="checkbox" value="1" id="is_primary" name="is_primary"
                                                checked>
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
                    <button type="button" class="btn btn-cancel"
                        data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Stpe-3 ends --}}
    @if (empty($supplier->xen_platform_id))
        <div class="modal fade" id="xenditpopup" tabindex="-1" aria-labelledby="xenditpopup" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 640px;" role="document">
                <div class="modal-content border-0" style="background-color: #ebedf1;">
                    <div class="modal-header p-3">
                        <h3 class="modal-title" style="color: white;" id="">
                            {{ __('admin.xendit_account_details') }}</h3>
                        <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal"
                            aria-label="Close">
                            <img src="{{ URL::asset('/front-assets/images/icons/times.png') }}" alt="Close">
                        </button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="card">
                            <div class="card-header align-items-center d-flex" style="background-color: #d7d9df;">
                                <img src="{{ URL::asset('/front-assets/images/icons/xendit.png') }}" alt=""
                                    height="20px" width="25px" srcset="">
                                <h5 class="mb-0 ps-2">{{ __('admin.xendit_account_info') }}</h5>
                            </div>
                            <div class="card-body p-3 pb-1">
                                <form id="xenAccountForm" data-parsley-validate>
                                    <div class="row">
                                        <div class="pb-3">
                                            <label class="form-label">{{ __('admin.company_name') }}</label>
                                            <div class="d-flex">
                                                <input type="text" class="form-control" id="xen-ac-name" disabled>
                                            </div>
                                        </div>
                                        <div class="pb-3">
                                            <label class="form-label">{{ __('admin.email') }}</label>
                                            <div class="">
                                                <input type="email" class="form-control" id="xen-email"
                                                    data-parsley-uniqueemail>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer pb-2 px-2" style="background-color: #f5f5f6;">
                        <button type="submit" id="create-xen-account"
                            class="btn btn-primary">{{ __('admin.create') }}</button>
                        <img class="hidden" id="loader" height="16px"
                            src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading">
                        <button type="button" class="btn btn-cancel"
                            data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="modal fade" id="xenditpopupemail" tabindex="-1" aria-labelledby="xenditpopupemail"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 640px;" role="document">
            <div class="modal-content border-0" style="background-color: #ebedf1;">
                <div class="modal-header p-3">
                    <h3 class="modal-title" style="color: white;" id="">
                        {{ __('admin.xendit_account_details') }}</h3>
                    <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close">
                        <img src="{{ URL::asset('/front-assets/images/icons/times.png') }}" alt="Close">
                    </button>
                </div>
                <div class="modal-body p-3">
                    <div class="card">
                        <div class="card-header align-items-center d-flex" style="background-color: #d7d9df;">
                            <img src="{{ URL::asset('/front-assets/images/icons/xendit.png') }}" alt=""
                                height="20px" width="25px" srcset="">
                            <h5 class="mb-0 ps-2">{{ __('admin.xendit_account_info') }}</h5>
                        </div>
                        <div class="card-body p-3 pb-1">
                            <form id="xenEmailForm" data-parsley-validate>
                                <div class="row">
                                    <div class="pb-3 col-md-6">
                                        <label class="form-label">{{ __('admin.xen_platform_id') }}</label>
                                        <div class="d-flex">
                                            <input type="text" class="form-control" id="xen-id"
                                                disabled="">
                                        </div>
                                    </div>
                                    <div class="pb-3 col-md-6">
                                        <label class="form-label">{{ __('admin.xen_platform_balance') }}</label>
                                        <div class="d-flex">
                                            <input type="text" class="form-control" id="xen-balance"
                                                disabled="">
                                        </div>
                                    </div>
                                    <div class="pb-3 col-md-6">
                                        <label class="form-label">{{ __('admin.company_name') }}</label>
                                        <div class="d-flex">
                                            <input type="text" class="form-control" id="xen-name"
                                                disabled="">
                                        </div>
                                    </div>
                                    <div class="pb-3 col-md-6">
                                        <label class="form-label">{{ __('admin.email') }}</label>
                                        <div class="">
                                            <input type="email" class="form-control" id="xen-email2"
                                                data-parsley-uniqueemail>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pb-2 px-2" style="background-color: #f5f5f6;">
                    <button type="button" class="btn btn-primary"
                        id="update-xen-account">{{ __('admin.update') }}</button>
                    <img class="hidden" id="loader2" height="16px"
                        src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading">
                    <button type="button" class="btn btn-cancel"
                        data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var supplierId = {{ $supplier->id }};
        var editSupplierProductId = '';

        $("input[name='npwp']").keyup(function() {
            let numbers = $(this).val().replace(/\D/g, '');
            if (numbers.length > 15) {
                $(this).val($(this).val().slice(0, 20));
                return;
            }
            let char = {
                0: '',
                2: '.',
                5: '.',
                8: '.',
                9: '-',
                12: '.'
            };
            let value = '';
            for (var i = 0; i < numbers.length; i++) {
                value += (char[i] || '') + numbers[i];
            }
            $(this).val(value);
        });

        function updateSupplier() {
            var formData = new FormData($("#supplierEditForm")[0]);
            formData.append('description', tinyMCE.get('description').getContent());
            localStorage.setItem('contactPersionEmail_' + '{{ $supplier->id }}', $('#contactPersonEmail').val());
            $.ajax({
                url: "{{ route('admin.supplier.update') }}",
                data: formData,
                type: "POST",
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.success) {
                        if (data.supplierId) {
                            //supplierId = successData.supplierId;
                            if (data.changeXenCompany) {
                                $('#supplier_company_name').html(
                                    '<img height="16px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading">'
                                    );
                                updateXenAccountCompany();
                                $("#previewBtn").attr('href', "/" + $("#profile_username").val());
                            }
                            resetToastPosition();
                            SnippetApp.toast.success("{{ __('admin.success') }}",
                                "{{ __('admin.supplier_updated_successfully') }}");

                            var companyType = $('input[name=companyType]:checked').val();
                            if (companyType == 1) {
                                $("#oldpkp_file").val('setvalue');
                            } else if (companyType == 2) {
                                $("#oldpkp_file").val('');
                            }
                            return false;
                        }
                    } else {
                        resetToastPosition();
                        SnippetApp.toast.success("{{ __('admin.warning') }}", data.message);

                    }
                },
                error: function() {
                    console.log("error");
                },
            });
            return false;
        }

        function supplierCategoryChange(categoryId, subCategoryId = null) {
            $('#supplierProductForm #productRef').val('');
            var productOptions = '<option selected disabled>*Select Product Name</option>';
            $('#supplierProductForm #supplierProduct').empty().append(productOptions);
            $('#supplierProductForm #supplierProductDiscription').val('');
            $('#supplierProductForm #supplierProductBrand').val('');
            $('#supplierProductForm #supplierProductGrade').val('');
            if (subCategoryId != null || subCategoryId != 0) {
                var select = 'selected="selected"';
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
                                if (data.id == subCategoryId) {
                                    var selected = select;
                                } else {
                                    selected = '';
                                }
                                options +=
                                    '<option value="' +
                                    data.id +
                                    '" data-text="' +
                                    data.name +
                                    '"' + selected + '>' +
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

        function supplierSubCategoryChange(subCategoryId, productId) {
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
                                    if (data.id == productId) {
                                        var selected = 'selected="selected"';
                                    } else {
                                        selected = '';
                                    }
                                    if (!productArray.includes(data.name)) {
                                        productArray.push(data.name);
                                        product += '<option value="' + data.id +
                                            '" data-text="' + data.name + '" ' + selected +
                                            '>' + data
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
            if (data.is_primary) {
                $('#supplierBankForm #is_primary').prop('checked', true);
            } else {
                $('#supplierBankForm #is_primary').prop('checked', false);
            }
            if (data.logo) {
                $("#supplierBankForm #bank-logo").attr('src', location.origin + '/' + data.logo);
            } else {
                $("#supplierBankForm #bank-logo").attr('src', defaultBankLogo);
            }
        }

        function preventQuote() {
            if(event.keyCode == 39 || event.keyCode == 34) {
                event.keyCode = 0;
                return false;
            }
        }

        $(document).ready(function() {
            getXenDetails();
            getXenBalance(supplierId);
            $('#xenditpopup').on('hidden.bs.modal', function() {
                $("#xenAccountForm")[0].reset();
                $('#xenAccountForm').parsley().reset();
            });
            $('#xenditpopupemail').on('hidden.bs.modal', function() {
                $("#xenEmailForm")[0].reset();
                $('#xenEmailForm').parsley().reset();
            });
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
                }, ]
            });
            $('#interested_in').tagsInput({
                'height': '44px',
                'width': 'auto',
                'interactive': true,
                'removeWithBackspace': true,
                'placeholderColor': '#242635'
            });
            var filterIndexes = [0, 1, 2];


            $('#filterOptions').append(
                ' <div class="col-md-3 d-flex align-items-end "><div class="form-group mb-3"><button class="btn btn-outline-info btn-sm" id="resetFilterOptions">Reset</button></div></div>'
            );
            $(document).on('click', '#resetFilterOptions', function() {

                $("#filterOptions select").each(function(index) {
                    $(this).prop("selectedIndex", 0);
                });
                var table = $('#supplierProductTable').DataTable({
                    retrieve: true
                });
                table
                    .search('')
                    .columns().search('')
                    .draw();

            });

            var customFinishBtn = $("<button></button>")
                .text("Preview")
                .addClass("btn btn-info hidden smartWizFinishBtn")
                .on("click", function() {
                    $(".smartWizFinishBtn").addClass("disabled");
                    var url = "/" + $("#profile_username").val();
                    //window.open(url);
                    window.open(url, '_blank');
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
                    showNextButton: false, // show/hide a Next button
                    showPreviousButton: false, // show/hide a Previous button
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
                    if (nextStepIndex == 0) {
                        $(".smartWizFinishBtn").addClass("hidden");
                    } else if (nextStepIndex == 1) {
                        getXenBalance(supplierId);
                    }
                    if (currentStepIndex == 0) {
                        var currentActiveTab = $("#pills-tab .nav-link.active").attr('id');
                        if (currentActiveTab == "pills-CompanyDetails-tab") {
                            $("#companydetailsSubmit").click();
                            if($("#supplierEditForm").find('.inputErrors').val() != ""){
                                if($("#supplierEditForm").find('.inputErrors').val() == "pkp_file"){
                                    $("#exampleRadios1").focus();
                                }else{
                                    $("#supplierEditForm").find(".errorValidate:not(:empty)").parent().find("textarea:visible,input[value='']:visible").first().focus();
                                }
                                return false;
                            } else {
                                $(".removeFile").removeClass("hide");
                                $(".downloadbtn").removeClass("hide");
                                $(".smartWizFinishBtn").removeClass("hidden");
                                return true;
                            }
                        } else if (currentActiveTab == "pills-BusinessDetails-tab") {
                            $("#btnBusinessDetailSubmit").click();
                            if($("#businessDetailsFrm").find('.inputErrors').val() != ""){
                                $("#businessDetailsFrm").find(".errorValidate:not(:empty)").parent().find("textarea:visible,input:visible").first().focus();
                                return false;
                            } else {
                                return true;
                            }
                        } else if (currentActiveTab == "pills-BasicDetails-tab") {
                            $("#btnBasicDetailSubmit").click();
                            if($("#basicDetailsFrm").find('.inputErrors').val() != ""){
                                $("#basicDetailsFrm").find(".errorValidate:not(:empty)").parent().find("textarea:visible,input[value='']:visible").focus()
                                return false;
                            }else{
                                $(".removeFile").removeClass("hide");
                                $(".downloadbtn").removeClass("hide");
                                return true;
                            }
                        } else {
                            return true;
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
            window.Parsley.addValidator('uniqueemail', {
                validateString: function(value) {
                    let res = false;
                    xhr = $.ajax({
                        url: '{{ route('check-xen-email-exist') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        method: 'POST',
                        data: {
                            id: $('#supplierEditForm input[name="id"]').val(),
                            email: value,
                        },
                        async: false,
                        success: function(data) {
                            res = data;
                        },
                    });
                    //console.log(res);
                    return res;
                },
                messages: {
                    en: 'This email already exists!'
                },
                priority: 32
            });
            window.Parsley.addValidator('uniqueemailcontactpersion', {
                validateString: function(value) {
                    let res = false;
                    xhr = $.ajax({
                        url: '{{ route('check-user-email-exist') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        method: 'POST',
                        data: {
                            id: $('#supplierEditForm input[name="id"]').val(),
                            email: value,
                        },
                        async: false,
                        success: function(data) {
                            res = data;
                        },
                    });
                    //console.log(res);
                    return res;
                },
                messages: {
                    en: 'This email already exists!'
                },
                priority: 32
            });
            $("#showtc").click(function() {
                if ($("#supplierEditForm").parsley().isValid()) {
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
                                $("#file-" + name).html('');
                                GetFileName(name);
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
                                $("#file-" + name).html('');
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
                    var lastMaxQty = 0;
                    var lastMaxQtyID = '';
                    var replace_id = '';
                    var data = $('.cloanMaxQty');
                    $.each(data, function(index, value) {
                        var isLastElement = index == data.length - 1;
                        if (isLastElement) {
                            lastMaxQty = $(this).val();
                            lastMaxQtyID = $(this).attr('id');
                        }
                    });
                    replace_id = lastMaxQtyID.replace('max_qty', '');
                    var lastDiscountVal = $("#discount" + replace_id).val();
                    if (checkMaxOrderQty != lastMaxQty) {
                        var err =
                            '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">{{ __('admin.maximum_quantity_must_be_equal_to_maximum_order_quantity') }}</li></ul>'
                        // replace_id = lastMaxQtyID.replace('max_qty', '');
                        $('#showErr' + replace_id).html(err);
                        $("#" + lastMaxQtyID).val('');
                        return false;
                    }
                    if (lastDiscountVal == '') {
                        var err =
                            '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">This value is required.</li></ul>'
                        $('#showErrDiscount' + replace_id).html(err);
                        return false;
                    }
                    //end checking

                    var formData = new FormData($("#supplierProductForm")[0]);
                    formData.append("supplier_id", supplierId);
                    if (editSupplierProductId) {
                        formData.append("editSupplierProductId", editSupplierProductId);
                    }
                    formData.append("_token", $('meta[name="csrf-token"]').attr("content"));
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
                                formData.append("_token", $('meta[name="csrf-token"]').attr("content"));
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
                                                $('#supplierProductForm #supplierProduct').removeClass('blink');
                                                $('#supplierProductForm #supplierCategory').removeClass('blink');
                                                $("#supplierProductForm #supplierSubCategory").removeClass('blink');
                                                $("#cloneDiv").html('');
                                                $("#min_qty").attr("readonly",
                                                    false);
                                                $("#min_qty").val('');
                                                $("#max_qty").attr("readonly",
                                                    false);
                                                $("#max_qty").val('');
                                                $("#discount").attr("readonly",
                                                    false);
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
                                                $('#supplierProductForm #supplierProduct').removeClass('blink');
                                                $('#supplierProductForm #supplierCategory').removeClass('blink');
                                                $("#supplierProductForm #supplierSubCategory").removeClass('blink');
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
                                                $("#min_qty").attr("readonly",
                                                    false);
                                                $("#min_qty").val('');
                                                $("#max_qty").attr("readonly",
                                                    false);
                                                $("#max_qty").val('');
                                                $("#discount").attr("readonly",
                                                    false);
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
                                editSupplierProductDetails(supplierProduct, successData
                                    .supplierProductBrands, successData
                                    .supplierProductGrades);
                                top_index = (successData.spdrCount - 1);
                                $('#supplierProductForm #removeCloanDiv').html(successData
                                .html);
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
                            $('#supplierProductForm #supplierProduct').removeClass('blink');
                            $('#supplierProductForm #supplierCategory').removeClass('blink');
                            $("#supplierProductForm #supplierSubCategory").removeClass('blink');

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
                                    if (successData.success == true) {
                                        $.toast({
                                            heading: '{{ __('admin.success') }}',
                                            text: '{{ __('admin.supplier_product_delete_success_message') }}',
                                            showHideTransition: 'slide',
                                            icon: 'success',
                                            loaderBg: '#f96868',
                                            position: 'top-right'
                                        })
                                        $('#supplierProductTable').DataTable()
                                            .row(
                                                "#supplierProductDetailsRow_" +
                                                id).remove()
                                            .draw();
                                    } else {
                                        swal({
                                            text: '{{ __('admin.supplier_product_not_delete_hard') }}',
                                            button: {
                                                text: "{{ __('admin.ok') }}",
                                                value: true,
                                                visible: true,
                                                className: "btn btn-primary"
                                            }
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
            let defaultBankLogo = "{{ URL('assets/icons/bank.png') }}";

            function bankChange(selector) {
                $('#bank-code').val(selector.find('option:selected').data('code'));
                if (selector.find('option:selected').data('logo')) {
                    $("#bank-logo").attr('src', location.origin + '/' + selector.find('option:selected').data(
                        'logo'));
                } else {
                    $("#bank-logo").attr('src', defaultBankLogo);
                }
            }
            $(document).on("click", ".editSupplierBankDetails", function(e) {
                $("#supplierBankForm")[0].reset();
                editSupplierBankId = $(this).attr("data-id");
                if (editSupplierBankId) {
                    $.ajax({
                        url: "{{ route('get-supplier-bank', '') }}" + "/" + editSupplierBankId,
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
                $('#supplierBankForm').parsley().reset();
                $('#supplierBankForm #supplier-bank-id').val(0);
                $("#bank-logo").attr('src', defaultBankLogo);
            });
            $('button[data-bs-toggle="pill"]').on('show.bs.tab', function(e) {
                var previousClickedTab = $(e.relatedTarget).attr('id');
                var currentClickedTab = $(e.target).attr('id');
                if (previousClickedTab == "pills-CompanyDetails-tab") {
                    $("#companydetailsSubmit").click();
                    if($("#supplierEditForm").find('.inputErrors').val() != ""){
                        $("#supplierEditForm").find(".errorValidate:not(:empty)").parent().find("input:visible").first().focus();
                        return false;
                    } else {
                        $(".removeFile").removeClass("hide");
                        $(".downloadbtn").removeClass("hide");
                        $(".smartWizFinishBtn").removeClass("hidden");
                        return true;
                    }
                } else if (previousClickedTab == "pills-BusinessDetails-tab") {
                    $("#btnBusinessDetailSubmit").click();
                    if($("#businessDetailsFrm").find('.inputErrors').val() != ""){
                        $("#businessDetailsFrm").find(".errorValidate:not(:empty)").parent().find("input:visible").first().focus();
                        return false;
                    } else {
                        return true;
                    }
                } else if (previousClickedTab == "pills-BasicDetails-tab") {
                    $("#btnBasicDetailSubmit").click();
                    if ($("#basicDetailsFrm").find('.inputErrors').val() != "") {
                        $("#basicDetailsFrm").find("textarea:empty:visible,input[value='']:visible").first()
                            .focus();
                        return false;
                    } else {
                        $(".removeFile").show("hide");
                        $(".downloadbtn").show("hide");
                        return true;
                    }
                } else {
                    return true;
                }

                $("form").find("textarea:empty:visible,input[value='']:visible").first().focus();
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
                        icon: "/assets/images/bin.png",
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
                                        text: '{{ __('admin.bank_deleted_successfully') }}.',
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
                if (!is_checked && parseInt($('#supplier-bank-id').val()) > 0) {
                    e.preventDefault();
                    swal("{{ __('admin.primary_bank_message') }}!", {
                        // icon: "warning",
                        icon: "/assets/images/warn.png",
                    });
                    return false;
                }
            })
            $(document).on('click', '.account_is_primary', function(e) {
                e.preventDefault();
                let id = $(this).attr("data-id");
                let is_checked = $(this).prop('checked');

                if (!is_checked) {
                    swal("{{ __('admin.primary_bank_message') }}!", {
                        // icon: "warning",
                        icon: "/assets/images/warn.png",
                    });
                    return false;
                }
                let that = $(this);
                swal({
                        title: "{{ __('admin.delete_sure_alert') }}",
                        text: is_checked ? "{{ __('admin.primary_account_message') }}." :
                            "{{ __('admin.secondary_bank_account_message') }}",
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
                                is_primary: (is_checked ? 1 : 0)
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
                                        if (is_checked) {
                                            $('.account_is_primary').prop('checked', false)
                                                .attr('checked', false);
                                            $('#account_is_primary_' + id).prop('checked',
                                                true).attr('checked', true);
                                        } else {
                                            $('#account_is_primary_' + id).prop('checked',
                                                false).attr('checked', false);
                                        }
                                    } else {
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
            $("#supplierProductPrice").keyup(function() {
                priceChange();
            });

            $(document).on('keyup', '.discountVal', function(e) {
                var id = $(this).attr("id");
                var replace_id = id.replace('discount', '');
                $('#showErrDiscount' + replace_id).html('');
                setDiscountAmtVal($(this));
            });

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
                            $("#file-" + name).html('');
                            GetFileName(fileName);
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
                            $("#file-" + name).html('');
                        },
                        error: function() {
                            console.log("error");
                        },
                    });
                }
            });
        });

        let defaultBankLogo = "{{ URL('assets/icons/bank.png') }}";

        function bankChange(selector) {
            $('#bank-code').val(selector.find('option:selected').data('code'));
            if (selector.find('option:selected').data('logo')) {
                $("#bank-logo").attr('src', location.origin + '/' + selector.find('option:selected').data('logo'));
            } else {
                $("#bank-logo").attr('src', defaultBankLogo);
            }
        }

        function addUpdateSupplierBank(formData) {
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
                $('.account_is_primary').prop('checked', false).attr('checked', false);
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
                $tr.find('.t_bank_logo').attr('src', (supplier_bank_details.logo ? (location.origin + '/' +
                    supplier_bank_details.logo) : defaultBankLogo));
                $tr.find('.t_bank_code').text(supplier_bank_details.code);
                $tr.find('.t_bank_account_name').text(supplier_bank_details.bank_account_name);
                $tr.find('.t_bank_account_number').text(supplier_bank_details.bank_account_number);
                $('#account_is_primary_' + supplier_bank_details.id).prop('checked', (supplier_bank_details.is_primary ==
                    1)).attr('checked', (supplier_bank_details.is_primary == 1));
                //$tr.find('.account_is_primary').attr('checked',(supplier_bank_details.is_primary==1));

            } else {
                $.toast({
                    heading: "{{ __('admin.success') }}",
                    text: "{{ __('admin.supplier_bank_added_success') }}",
                    showHideTransition: "slide",
                    icon: "success",
                    loaderBg: "#f96868",
                    position: "top-right",
                });
                var edit = '{{ __('admin.edit') }}';
                var deleteKey = '{{ __('admin.delete') }}';
                let tableData = '<tr id="addbankdetails_' + supplier_bank_details.id + '" class="odd">\n' +
                    '                                    <td class="" id="t_bank_name_' + supplier_bank_details.id +
                    '">\n' +
                    '                                        <div class="p-1 pe-1 ">\n' +
                    '                                             <img src="' + (supplier_bank_details.logo ? (location
                        .origin + '/' + supplier_bank_details.logo) : defaultBankLogo) +
                    '" class="t_bank_logo" height="20px" width="20px" alt="bank Logo">\n' +
                    '                                        </div>\n' +
                    '                                    </td>\n' +
                    '                                    <td>\n' +
                    '                                        <span class="t_bank_name ps-1">' + supplier_bank_details.name +
                    '</span>\n' +
                    '                                    </td>' +
                    '                                    <td class="t_bank_code" id="t_bank_code_' + supplier_bank_details
                    .id + '">' + supplier_bank_details.code + '</td>\n' +
                    '                                    <td class="t_bank_account_name" id="t_bank_account_name_' +
                    supplier_bank_details.id + '">' + supplier_bank_details.bank_account_name + '</td>\n' +
                    '                                    <td class="t_bank_account_number" id="t_bank_account_number_' +
                    supplier_bank_details.id + '">' + supplier_bank_details.bank_account_number + '</td>\n' +
                    '                                    <td class="t_primary_others" id="t_primary_others_' +
                    supplier_bank_details.id + '">\n' +
                    '                                        <div class="form-check form-switch" style="margin-left: 0px;">\n' +
                    '                                            <input class="form-check-input account_is_primary" type="checkbox" data-id="' +
                    supplier_bank_details.id + '" id="account_is_primary_' + supplier_bank_details.id + '" ' + (
                        supplier_bank_details.is_primary ? 'checked' : '') + '>\n' +
                    '                                        </div>\n' +
                    '                                    </td>\n' +
                    '                                    <td id="t_action_' + supplier_bank_details.id +
                    '" class="text-end text-nowrap"> <a href="javascript:void(0)" data-id="' + supplier_bank_details.id +
                    '" class="editSupplierBankDetails" data-toggle="tooltip" ata-placement="top" title="' + edit +
                    '"><i class="fa fa-edit"></i></a>\n' +
                    '                                        <a href="javascript:void(0)" data-id="' + supplier_bank_details
                    .id + '" class="deletebankdetails ps-2" data-toggle="tooltip" ata-placement="top" title="' + deleteKey +
                    '"> <i class="fa fa-trash"></i></a>\n' +
                    '                                    </td>\n' +
                    '                                </tr>';

                $("#supplierBankTable")
                    .DataTable()
                    .row.add($(tableData))
                    .draw();
            }
            $("#bankdetail").modal("hide");
        }

        String.prototype.beforeLastIndex = function(delimiter) {
            return this.split(delimiter).slice(0, -1).join(delimiter) || this + ""
        }

        function show(input) {
            var file = input.files[0];
            var size = parseFloat(file.size / 1024).toFixed(2);
            var maxSize = (input.id == "catalog") ? 20600 : 3072;
            var textKey = (input.id == "catalog") ? '{{ __('admin.file_size_under_20mb') }}' : '{{ __('admin.file_size_under_3mb') }}';
            if (size >= maxSize) {
                swal({
                    icon: 'error',
                    title: '',
                    text: textKey,
                });
                GetFileName(input.name);
            } else {
                if (input.name == 'pkp_file') {
                    $('#pkp_fileError').html('');
                }
                var fileName = file.name;
                var allowed_extensions = new Array("jpg", "png", "gif", "jpeg", "pdf");
                var file_extension = fileName.split('.').pop();
                var text = '';
                var supplier_id = '{{ $supplier->id }}';
                var image_path = '{{ URL::asset('assets/icons/times-circle copy.png') }}';
                if (input.name == 'logo' || input.name == 'productImages') {
                    allowed_extensions = allowed_extensions.filter(function(value) {
                        return value != 'pdf'
                    });
                    text = '{{ __('admin.only_upload_image_file') }}';
                } else {
                    text = '{{ __('admin.upload_image_or_pdf') }}';
                }
                for (var i = 0; i < allowed_extensions.length; i++) {
                    if (allowed_extensions[i] == file_extension) {
                        valid = true;
                        var download_function = "'" + supplier_id + "', " + "'" + input.name + "', " + "'" + fileName + "'";
                        if (fileName.beforeLastIndex(".").length >= 10) {
                            fileName = fileName.substring(0, 10) + '....' + file_extension;
                        }
                        $('#file-' + input.name).html('');
                        $('#file-' + input.name).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input
                            .name + 'Download" style="text-decoration: none">' + fileName +
                            '</a></span><span class="removeFile hide" id="' + input.name + 'File" data-id="' +
                            supplier_id + '" data-name="' + input.name +
                            '"><a href="#" title="Remove logo" style="text-decoration: none;"><img src="' + image_path +
                            '" alt="CLose button" class="ms-2"></a></span><span class="ms-2"><a class="logoFile downloadbtn hide" href="javascript:void(0);" title="Download ' +
                            input.name + '" onclick="downloadimg(' + download_function +
                            ')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>');
                        return;
                    }
                }
                valid = false;
                swal({
                    // title: "Rfq Update",
                    text: text,
                    icon: "warning",
                    //buttons: true,
                    buttons: ["{{ __('admin.no') }}", "{{ __('admin.yes') }}"],
                    // dangerMode: true,
                })
            }
        }

        function downloadimg(id, fieldName, name) {
            event.preventDefault();
            var data = {
                id: id,
                fieldName: fieldName
            }
            $.ajax({
                url: "{{ route('supplier-download-image-ajax') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
                type: 'POST',
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    var blob = new Blob([response]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = name;
                    link.click();
                },
            });
        }

        function downloaproductdimg(id, fieldName, name) {
            event.preventDefault();
            var data = {
                id: id,
                fieldName: fieldName
            }
            $.ajax({
                url: "{{ route('supplier-download-product-image-ajax') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
                type: 'POST',
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
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

        function getSupplierDetails(new_email = '') {
            $.ajax({
                url: "{{ route('supplier-details', '') }}" + "/" + supplierId,
                type: 'GET',
                dataType: 'json',
                success: function(successData) {
                    if (successData.success) {
                        if (new_email == '') {
                            $("#xenditpopup #xen-ac-name").val(successData.data.name);
                            $("#xenditpopup #xen-email").val(successData.data.contact_person_email);
                        } else {
                            if (successData.data.xen_platform_id != '' && new_email != successData.data
                                .contact_person_email) {
                                swal({
                                    //title: "Are you sure?",
                                    text: "{{ __('admin.xen_change_email_address_message') }}",
                                    icon: "/assets/images/info.png",
                                    buttons: ['{{ __('admin.keep_old') }}.',
                                        '{{ __('admin.change_it') }}'
                                    ],
                                    dangerMode: false,
                                }).then((change) => {
                                    if (change) {
                                        updateXenAccountEmail(new_email);
                                    }
                                });
                            }
                        }
                    }
                },
                error: function() {
                    console.log('error');
                }
            });
        }

        function getXenDetails() {
            $.ajax({
                url: "{{ route('xen-account-details', '') }}" + "/" + supplierId,
                type: 'GET',
                dataType: 'json',
                success: function(successData) {
                    if (successData.success) {
                        setXenDetails(successData.data);
                    }
                },
                error: function(error) {
                    $.toast({
                        heading: '{{ __('admin.warning') }}',
                        text: error.responseJSON.message,
                        showHideTransition: 'slide',
                        icon: 'warning',
                        loaderBg: '#57c7d4',
                        position: 'top-right'
                    })
                    console.log(error);
                }
            });
        }

        function setXenDetails(data) {
            $('#xen_platform_id').html(data.xen_platform_id);
            $('#supplier_company_name').html(data.business_name);
            $('#supplier_email').html(data.email);

            $('#xen-id').val(data.id);
            $('#xen-balance').val('Rp ' + (data.balance ? data.balance : 0));
            $('#xen-name').val(data.business_name);
            $('#xen-email2').val(data.email);
        }

        function updateXenAccountEmail(new_email, type = 1) { //1 for email change event, 2 for popup
            $.ajax({
                url: "{{ route('update-xen-account') }}",
                data: {
                    id: supplierId,
                    email: new_email
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                success: function(successData) {
                    if (successData.success) {
                        $.toast({
                            heading: '{{ __('admin.success') }}',
                            text: '{{ __('admin.xen_account_update_successfully') }}.',
                            showHideTransition: 'slide',
                            icon: 'success',
                            loaderBg: '#f96868',
                            position: 'top-right'
                        });
                        $('#supplier_email').html(new_email);
                        if (type == 2) {
                            $("#update-xen-account").toggleClass('hidden');
                            $('#loader2').toggleClass('hidden');
                            $('#xenditpopupemail').modal('hide');
                        }
                    } else {
                        $.toast({
                            heading: '{{ __('admin.warning') }}',
                            text: '{{ __('admin.something_error_message') }}',
                            showHideTransition: 'slide',
                            icon: 'warning',
                            loaderBg: '#57c7d4',
                            position: 'top-right'
                        })
                        if (type == 2) {
                            $("#update-xen-account").toggleClass('hidden');
                            $('#loader2').toggleClass('hidden');
                        }
                    }
                },
                error: function(error) {
                    $.toast({
                        heading: '{{ __('admin.warning') }}',
                        text: error.responseJSON.message,
                        showHideTransition: 'slide',
                        icon: 'warning',
                        loaderBg: '#57c7d4',
                        position: 'top-right'
                    })
                    if (type == 2) {
                        $("#update-xen-account").toggleClass('hidden');
                        $('#loader2').toggleClass('hidden');
                    }
                    console.log(error);
                },
            });
        }

        function updateXenAccountCompany() {
            $.ajax({
                url: "{{ route('update-xen-account') }}",
                data: {
                    id: supplierId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                success: function(successData) {
                    if (successData.success) {
                        if (successData.xenAccount) {
                            $('#supplier_company_name').html(successData.xenAccount.public_profile
                                .business_name);
                        }
                    } else {
                        $('#supplier_company_name').html('');
                        $.toast({
                            heading: '{{ __('admin.warning') }}',
                            text: '{{ __('admin.something_error_xen_message') }}',
                            showHideTransition: 'slide',
                            icon: 'warning',
                            loaderBg: '#57c7d4',
                            position: 'top-right'
                        })
                    }
                },
                error: function(error) {
                    $('#supplier_company_name').html('');
                    $.toast({
                        heading: '{{ __('admin.warning') }}',
                        text: error.responseJSON.message,
                        showHideTransition: 'slide',
                        icon: 'warning',
                        loaderBg: '#57c7d4',
                        position: 'top-right'
                    })
                    console.log(error);
                },
            });
        }

        $(document).on("click", "#xen-email-edit-btn", function() {
            $('#xen-id').val($('#xen_platform_id').text());
            $('#xen-balance').val($('#xen_balance').text() ? $('#xen_balance').text() : 'Rp 0');
            $('#xen-email2').val($('#supplier_email').text());
            $('#xen-name').val($('#name').val());
        });

        $(document).on("click", "#create-xenaccount", function() {
            getSupplierDetails();
        });

        $(document).on("click", "#create-xen-account", function() {
            if ($('#xenAccountForm').parsley().validate()) {
                $(this).toggleClass('hidden');
                $('#loader').toggleClass('hidden');
                let that = $(this);
                $.ajax({
                    url: "{{ route('create-xen-account', '') }}",
                    data: {
                        id: supplierId,
                        email: $('#xen-email').val()
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    dataType: 'json',
                    success: function(successData) {
                        if (successData.success) {
                            $.toast({
                                heading: '{{ __('admin.success') }}',
                                text: '{{ __('admin.xen_create_success_message') }}',
                                showHideTransition: 'slide',
                                icon: 'success',
                                loaderBg: '#f96868',
                                position: 'top-right'
                            });
                            setXenDetails(successData.data);
                            $('#create-xenaccount').remove();
                            $('#xen-div').toggleClass('hidden');
                            $('#xenditpopup').modal('hide');
                        } else {
                            $.toast({
                                heading: '{{ __('admin.warning') }}',
                                text: '{{ __('admin.something_error_message') }}',
                                showHideTransition: 'slide',
                                icon: 'warning',
                                loaderBg: '#57c7d4',
                                position: 'top-right'
                            })
                        }
                        that.toggleClass('hidden');
                        $('#loader').toggleClass('hidden');
                    },
                    error: function(error) {
                        $.toast({
                            heading: '{{ __('admin.warning') }}',
                            text: error.responseJSON.message,
                            showHideTransition: 'slide',
                            icon: 'warning',
                            loaderBg: '#57c7d4',
                            position: 'top-right'
                        })
                        that.toggleClass('hidden');
                        $('#loader').toggleClass('hidden');
                        console.log('error');
                    }
                });
            }
        });
        $(document).on("click", "#update-xen-account", function() {
            if ($('#xenEmailForm').parsley().validate()) {
                $(this).toggleClass('hidden');
                $('#loader2').toggleClass('hidden');
                updateXenAccountEmail($('#xen-email2').val(), 2);
            }
        });

        $(document).on("keyup", "#contactPersonEmail", function(e) {
            if ($('#contactPersonEmail').val() != localStorage.getItem('contactPersionEmail_' +
                    '{{ $supplier->id }}')) {
                $('.inviteBuyer').hide();
            } else {
                $('.inviteBuyer').show();
            }
        });

        /** Supplier Professional profile snippet start*/
        var SupplierProfileTab = function() {
            /**Start pkp non-pkp use to hide show functionality*/
            GetFileName = function(FileName) {
                    if (FileName == 'pkp_file') {
                        $('#pkp-doc').attr('required', 'true');
                        $('#oldpkp_file').val('');
                    }

                    if (FileName == 'pkp_fileFile') {
                        $('#pkp-doc').val('');
                        $('#pkp-doc').attr('required', 'true');
                        $('#oldpkp_file').val('');
                    }
                },

                pkpClassFunction = function() {
                    $('.pkp-btn').on('click', function() {
                        $('.pkp_doc_option').show();
                        var pkpValue = $('#oldpkp_file').val();
                        if (pkpValue) {
                            $('#pkp_file').show();
                            $('#file-pkp_file').show();
                        } else {
                            $('#pkp-doc').attr('required', 'true');
                        }
                    });
                };

            nonPkpClassFunction = function() {
                    $('.non-pkp-btn').on('click', function() {
                        var pkpValue = $('#oldpkp_file').val();
                        $('.pkp_doc_option').hide();
                        $('#pkp-doc').removeAttr('required');
                        if (pkpValue) {
                            $('#pkp_file').hide();
                            $('#file-pkp_file').hide();
                        } else {
                            $('#pkp_file').val('');
                            $('#file-pkp_file').html('');
                            $('#pkp_fileError').html('');
                        }
                    });
                },
                /**End pkp use to pkp hide show functionalit End*/
                /** remove single error on validation */
                removeSingleError = function() {
                    //company details valdiation remove
                    $('#supplierEditForm input[type="text"]').on('input', function(evt) {
                        let inputId = $(this).attr('id');
                        $('#' + inputId + 'Error').html('');
                    });
                    $('#supplierEditForm input[type="email"]').on('input', function(evt) {
                        let inputId = $(this).attr('id');
                        $('#' + inputId + 'Error').html('');
                    });

                    $('#supplierEditForm select').on('change', function(evt) {
                        let inputId = $(this).attr('id');
                        $('#' + inputId + 'Error').html('');
                    });
                    // core Team validation remove
                    $('#coreTeamFrm input[type="text"]').on('input', function(evt) {
                        let inputId = $(this).attr('id');
                        $('#' + inputId + '-error').html('');
                    });
                    //client & supplier portfolio
                    $('#companyPortfolioFrm input[type="text"]').on('input', function(evt) {
                        let inputId = $(this).attr('id');
                        $('#' + inputId + 'Error').html('');
                    });
                    $('#companyPortfolioFrm input[type="radio"]').on('change', function(evt) {
                        let inputId = $(this).attr('name');
                        $('#' + inputId + 'Error').html('');
                    });
                    //hightlights validation remove
                    $('#highlightsFrm input[type="text"]').on('input', function(evt) {
                        let inputId = $(this).attr('id');
                        $('#' + inputId + '_Error').html('');
                    });
                    $('#highlightsFrm select').on('change', function(evt) {
                        let inputId = $(this).attr('id');
                        $('#' + inputId + '_Error').html('');
                    });
                    //Testimonial validation remove
                    $('#testimonialFrm input[type="text"]').on('input', function(evt) {
                        let inputId = $(this).attr('id');
                        $('#' + inputId + '_Error').html('');
                    });
                    //company partner validation remove
                    $('#companyPartnerFrm input[type="text"]').on('input', function(evt) {
                        let inputId = $(this).attr('id');
                        $('#' + inputId + 'Error').html('');
                    });
                },
                /** Remove All errors*/
                removeAllErrors = function() {
                    $('#coreTeamFrm span.text-danger').html('');
                    $('#testimonialFrm span.text-danger').html('');
                },
                /** Reset form on closed button */
                formClose = function() {
                    // core Team validation remove
                    $('#coreTeamFrm').on('hidden.bs.modal', function() {
                        removeAllErrors();
                        $('#coreTeamFrm').trigger('reset');
                    });
                    // Testimonial validation remove
                    $('#testimonialFrm').on('hidden.bs.modal', function() {
                        removeAllErrors();
                        $('#testimonialFrm').trigger('reset');
                    });

                }

            return {
                init: function() {
                    pkpClassFunction(),
                        nonPkpClassFunction(),
                        removeSingleError(),
                        removeAllErrors(),
                        formClose()
                },
                /** Location clone add */
                locationCloneAdd: function() {
                    $(document).on('click', '#locationBtnClone', function(e) {
                        var index = parseInt($(
                                "#mainLocationInHtml .location_in_html textarea[name='address[]']")
                            .length - 1) + 1;
                        var $clone = $('#mainLocationInHtml .location_in_html').first().clone(true)
                            .addClass('removewhileleft');
                        $clone.find("textarea").val("").end();
                        $clone.find(".trashicon").removeClass('hidden');
                        $clone.find("textarea").attr('data-cloneid', index);
                        $clone.appendTo($("#locationContainer"));
                    });
                },
                /** location remove */
                locationCloneRemove: function() {
                    $(document).on('click', '.trashicon', function() {
                        $(this).closest('.location_in_html').remove();
                    });
                },
                /** checking the profile-user slug already exist */
                checkProfileUsernameUnique: function() {
                    var timeout = null;
                    var delay = 1000;
                    $('#profile_username').on('keyup', function() {
                        $(this).val($(this).val().toLowerCase().replace(/ /g, "-"));
                        clearTimeout(timeout);
                        $("#profileUsernameValidationDiv").html(
                            "<span class='input-group-text bg-transparent h-100' id='basic-addon1'><img src={{ URL::asset('assets/icons/spinner-2.gif') }} class='' height='14px'></span>"
                            );
                        timeout = setTimeout(() => {
                            $value = $(this).val();
                            if ($value != "") {
                                $.ajax({
                                    type: 'POST',
                                    url: '{{ route('admin.supplier.checkProfileUsernameUnique') }}',
                                    data: {
                                        'value': $value,
                                        'id': $("#supplierId").val()
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                            'content')
                                    },
                                    async: false,
                                    dataType: "JSON",
                                    success: function(response) {
                                        if (response.success) {
                                            $("#profileUsernameValidationDiv").html(
                                                "<span class='input-group-text bg-transparent h-100' id='basic-addon1'><i class='fa fa-check-square text-success'></span>"
                                                );
                                            $("#profile_usernameError").html(
                                                "<span class='text-danger'>" +
                                                response.message + "</span>");
                                        } else {
                                            $("#profileUsernameValidationDiv").html(
                                                "<span class='input-group-text bg-transparent h-100' id='basic-addon1'><i class='fa fa-times-rectangle text-danger'></span>"
                                                );
                                            $("#profile_usernameError").html(
                                                "<span class='text-danger'>" +
                                                response.message + "</span>");
                                        }
                                    }
                                });
                            } else {
                                $("#profileUsernameValidationDiv").html("");
                            }
                        }, delay);
                    });
                },
                /** company details save data*/
                companydetailsSubmit: function() {
                    $(document).on("click", "#companydetailsSubmit", function(e) {
                        e.preventDefault();
                        $('#supplierEditForm .inputErrors').val('');
                        var formData = new FormData($("#supplierEditForm")[0]);
                        localStorage.setItem('contactPersionEmail_' + '{{ $supplier->id }}', $(
                            '#contactPersonEmail').val());
                        $.ajax({
                            url: "{{ route('admin.supplier.update') }}",
                            data: formData,
                            type: "POST",
                            processData: false,
                            contentType: false,
                            async: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                if (data.success) {
                                    if (data.supplierId) {
                                        if (data.changeXenCompany) {
                                            $('#supplier_company_name').html(
                                                '<img height="16px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading">'
                                                );
                                            updateXenAccountCompany();
                                        }
                                        resetToastPosition();
                                        SnippetApp.toast.success("{{ __('admin.success') }}",
                                            "{{ __('admin.account_details_updated_successfully') }}"
                                            );
                                        updatePreviewBtnLink($("#profile_username").val());
                                        var companyType = $('input[name=companyType]:checked')
                                            .val();
                                        if (companyType == 1) {
                                            $("#oldpkp_file").val('setvalue');
                                        } else if (companyType == 2) {
                                            $("#oldpkp_file").val('');
                                        }
                                        return false;
                                    }
                                } else {
                                    resetToastPosition();
                                    SnippetApp.toast.alert("{{ __('admin.warning') }}", data
                                        .message);
                                }
                            },
                            error: function(successData) {
                                if (successData.status === 422) {
                                    var errors = $.parseJSON(successData.responseText);
                                    var errorsArr = [];
                                    $('#supplierEditForm .errorValidate').html('');
                                    $.each(errors, function(key, value) {
                                        if ($.isPlainObject(value)) {
                                            $.each(value, function(key, value) {
                                                $('#' + key + 'Error').html(
                                                    value);
                                                errorsArr.push(key);
                                            });
                                        }
                                    });
                                    $('#supplierEditForm .inputErrors').val(errorsArr);
                                    if($("#supplierEditForm").find('.inputErrors').val() == "pkp_file"){
                                        $("#exampleRadios1").focus();
                                    }else{
                                        $("#supplierEditForm").find(".errorValidate:not(:empty)").parent().find("input:visible").first().focus();
                                    }
                                } else {
                                    SnippetApp.notify.alert(
                                        "{{ __('profile.something_went_wrong') }}");
                                }

                            },
                        });
                        return false;
                    });
                },
            }
        }(1);

        function inviteAsBuyer(id) {
            $('#inviteSupplierButton').toggleClass('hidden');
            $('#loaderinviteSupplier').toggleClass('hidden');
            $.ajax({
                url: "{{ route('invite-as-buyer', '') }}" + "/" + id,
                type: 'GET',
                dataType: 'json',
                success: function(successData) {
                    swal({
                        //title: "Are you sure?",
                        text: successData.message,
                        icon: successData.icon,
                        buttons: successData.buttons,
                        dangerMode: false,
                    }).then((change) => {
                        if (change) {
                            if (successData.success == false) {
                                $('#inviteSupplierButton').toggleClass('hidden');
                                $('#loaderinviteSupplier').toggleClass('hidden');
                            }
                        } else {
                            $('#inviteSupplierButton').toggleClass('hidden');
                            $('#loaderinviteSupplier').toggleClass('hidden');
                        }

                        if (successData.success) {
                            $('.inviteBuyer').remove();
                        }
                    });
                }
            });
        }

        jQuery(document).ready(function() {

            SupplierProfileTab.init();
            SupplierProfileTab.locationCloneAdd();
            SupplierProfileTab.companydetailsSubmit();
            SupplierProfileTab.locationCloneRemove();
            SupplierProfileTab.checkProfileUsernameUnique();
        });
    </script>

@stop
@section('scripts')
    {{ view('admin.supplier.supplier_product_range_js') }}
@endsection
@push('bottom_scripts')
    <script src="{{ URL::asset('assets/js/custom/supplierProfile.js') }}"></script>
    <script>
        var input = document.querySelector("#mobile");
        var iti = window.intlTelInput(input, {
            initialCountry: "id",
            separateDialCode: true,
            dropdownContainer: null,
            preferredCountries: ["id"],
            hiddenInput: "c_phone_code"
        });

        $("#mobile").focusin(function() {
            let countryData = iti.getSelectedCountryData();
            $('input[name="c_phone_code"]').val(countryData.dialCode);
        });

        var input = document.querySelector("#contactPersonMobile");
        var iti2 = window.intlTelInput(input, {
            initialCountry: "id",
            separateDialCode: true,
            dropdownContainer: null,
            preferredCountries: ["id"],
            hiddenInput: "cp_phone_code"
        });

        $("#contactPersonMobile").focusin(function() {
            let countryData = iti2.getSelectedCountryData();
            $('input[name="cp_phone_code"]').val(countryData.dialCode);
        });

        var input_company_alternative_phone = document.querySelector("#company_alternative_phone");
        var iti3 = window.intlTelInput(input_company_alternative_phone, {
            initialCountry: "id",
            separateDialCode: true,
            dropdownContainer: null,
            preferredCountries: ["id"],
            hiddenInput: "company_alternative_phone_code"
        });

        $("#company_alternative_phone").focusin(function() {
            let countryData = iti3.getSelectedCountryData();
            $('input[name="company_alternative_phone_code"]').val(countryData.dialCode);
        });
        $(document).ready(function() {
            @php
                $cPhoneCode = $supplier->c_phone_code ? str_replace('+', '', $supplier->c_phone_code) : 62;
                $cpPhoneCode = $supplier->cp_phone_code ? str_replace('+', '', $supplier->cp_phone_code) : 62;
                $capPhoneCode = $supplier->company_alternative_phone_code ? str_replace('+', '', $supplier->company_alternative_phone_code) : 62;
                $cCountry = $supplier->c_phone_code ? strtolower(getRecordsByCondition('countries', ['phone_code' => $cPhoneCode], 'iso2', 1)) : 'id';
                $cpCountry = $supplier->cp_phone_code ? strtolower(getRecordsByCondition('countries', ['phone_code' => $cpPhoneCode], 'iso2', 1)) : 'id';
                $capCountry = $supplier->company_alternative_phone_code ? strtolower(getRecordsByCondition('countries', ['phone_code' => $capPhoneCode], 'iso2', 1)) : 'id';
            @endphp
            $('input[name="c_phone_code"]').val('{{ $cPhoneCode }}');
            $('input[name="cp_phone_code"]').val('{{ $cpPhoneCode }}');
            $('input[name="company_alternative_phone_code"]').val('{{ $capPhoneCode }}');

            iti.setCountry('{{ $cCountry }}');
            iti2.setCountry('{{ $cpCountry }}');
            iti3.setCountry('{{ $capCountry }}');
        });
        $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function(e) {
            $("form").find("textarea:empty:visible,input[value='']:visible").first().focus();
        });
        $('#established_date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            endDate: new Date()
        });

        function updatePreviewBtnLink(slug_name) {
            var url = "{{ route('supplier.professional.profile', ':url') }}";
            var prefixUrl = "{{ getSettingValueByKey('slug_prefix') }}";
            url = url.replace(":url", prefixUrl + slug_name);
            $("#previewBtn").attr('href', url);
        }
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
            $( "#tags" ).bind("keyup.autocomplete", function(event) {
                if(event.keyCode == $.ui.keyCode.BACKSPACE || event.keyCode == $.ui.keyCode.DELETE) {
                    $(".count").text('0 Result Found');
                    event.stopPropagation();
                }
            });
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
                            $('#supplierProductForm #supplierProduct').removeClass('blink');
                            $('#supplierProductForm #supplierCategory').removeClass('blink');
                            $("#supplierProductForm #supplierSubCategory").removeClass('blink');
                            $(".count").text(data.cnt+' Result Found');
                            $(".count").css("display", "block");
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
                appendTo: "#addProductModal",
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
                    $('#supplierProductForm #supplierCategory ').removeAttr('data-parsley-id');
                    $('#supplierProductForm #supplierCategory option[value="'+categoryId+'"]').attr('selected','selected');
                    $('#supplierProductForm #supplierCategory option[value="'+categoryId+'"]').prop('selected',true);
                    supplierCategoryChange(categoryId,subcategoryId);
                    supplierSubCategoryChange(subcategoryId,productId);
                    $('#supplierProductForm #supplierProduct').addClass('blink');
                    $('#supplierProductForm #supplierCategory').addClass('blink');
                    $("#supplierProductForm #supplierSubCategory").addClass('blink');
                    $(".count").text('0 Result Found');
                    $("#supplierProductForm #tags").val('');
                    return false;
                }
            })

        });
        $(document).on('click', '#addProductModalBtn', function() {
            var productOptions = '<option selected disabled>*Select Product Name</option>';
            $('#supplierProductForm #supplierProduct').empty().append(productOptions);
            var CatOptions = '<option selected disabled>*Select Product Name</option>';
            $('#supplierProductForm #supplierSubCategory').empty().append(productOptions);
           if($('#supplierProductForm #supplierCategory').val() > 0) {
               $('#supplierProductForm #supplierCategory option').attr('selected', false);
               $("#supplierCategory").val($("#supplierCategory option:first").val());
           }
           // $('#supplierProductForm #supplierCategory option[value=""]').attr('selected','selected');
            $('#supplierProductForm #supplierProduct').removeClass('blink');
            $('#supplierProductForm #supplierCategory').removeClass('blink');
            $("#supplierProductForm #supplierSubCategory").removeClass('blink');
        });
    </script>

@endpush
