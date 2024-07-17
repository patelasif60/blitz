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

        .sw-theme-dots>.nav li:last-child .nav-link:first-child::after {
            display: none;
        }

        .contact_step .icon {
            z-index: 100 !important;
        }



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

        #parsley-id-multiple-companyType {
            margin-top: 32px !important;
            margin-left: 10px !important;
        }

        #parsley-id-32 {
            margin-left: 70px !important;
        }
        .date{position: relative;}
            .date    .parsley-errors-list {
            position: absolute;
            bottom: -32px;;
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
                    <a href="{{ route('admin-dashboard') }}" class="ms-auto backurl btn-close"></a>
                    @if(auth()->user()->role_id != 3)
                    <a href="{{ route('admin.supplier.index') }}" class="mb-2 backurl ms-auto btn-close"></a>
                    @endif
                </div>

                <div class="col-12">
                    <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">{{ __('admin.profile') }}</button>
                        </li>
                    </ul>
                    <div class="tab-content pt-3 pb-0 px-0" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div id="supplier-steps">
                                <ul class="nav col-md-4 contact_step">
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
                                            {{ __('admin.account_details') }}<div class="icon">
                                            <!-- <img src="{{ URL('assets/icons/bank.png') }}" height="35px" width="30px" alt=""> -->
                                                <i class="pen_icon bank"></i>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content pt-3 pb-0 ">
                                    <div id="step-1" class="tab-pane p-0" role="tabpanel" style="display: none;">
                                        <form class="" id="supplierEditForm" method="POST"
                                              enctype="multipart/form-data" action="{{ route('supplier-update') }}"
                                              data-parsley-validate>
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $supplier->id }}">
                                            <input type="hidden" name="is_supplier" value="{{ (auth()->user()->role_id == 3) ? 1 : 0 }}">

                                            <div class="row">
                                                <div class="col-md-12 mb-2">
                                                    <div class="card">
                                                        <div class="card-header d-flex align-items-center">
                                                            <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/icon_company.png')}}" alt="Company" class="pe-2"> <span>{{ __('admin.company_details') }}</span></h5>
                                                        </div>
                                                        <div class="card-body p-3 pb-1">
                                                            <div class="row">

                                                                <div class="col-md-3 mb-3">
                                                                    <label for="name" class="form-label">{{ __('admin.company_name') }} <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" id="name" name="name"
                                                                           value="{{ $supplier->name }}" required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="logo" class="form-label">{{ __('admin.company_logo') }}</label>
                                                                    {{--<input type="file" class="form-control file-upload-info" id="logo" name="logo" accept="image/*" data-parsley-max-file-size="2">
                                                                    <input type="hidden" class="form-control" id="oldlogo" name="oldlogo" value="{{ $supplier->logo }}">
                                                                    @if ($supplier->logo)
                                                                        <a href="{{ asset('storage/' . $supplier->logo) }}" id="logoFileDownload" download="{{ Str::substr($supplier->logo, stripos($supplier->logo, 'logo_') + 5) }}">{{ Str::substr($supplier->logo, stripos($supplier->logo, 'logo_') + 5) }}<i class="fa fa-cloud-download"></i></a>
                                                                        <span class="removeFile" id="logoFile" data-id="{{ $supplier->id }}" file-path="{{ $supplier->logo }}"><i class="fa fa-trash"></i></span>
                                                                    @endif--}}
                                                                    <div class="d-flex">
                                                                        <span class=""><input type="file" name="logo" class="form-control" id="logo" accept=".jpg,.png,.gif,.jpeg" onchange="show(this)" hidden/><label  class="upload_btn" for="logo">{{ __('admin.browse') }}</label></span>
                                                                        <div id="file-logo">
                                                                            @if ($supplier->logo)
                                                                                @php
                                                                                    $extension_logo = substr($supplier->logo, -4);
                                                                                    $logo_filename = substr(Str::substr($supplier->logo, stripos($supplier->logo, 'logo_') + 5), 0, -4);
                                                                                    if(strlen($logo_filename) > 10){
                                                                                        $logo_name = substr($logo_filename,0,10).'...'.$extension_logo;
                                                                                    } else {
                                                                                        $logo_name = $logo_filename.$extension_logo;
                                                                                    }
                                                                                @endphp
                                                                                <input type="hidden" class="form-control" id="oldlogo" name="oldlogo" value="{{ $supplier->logo }}">
                                                                                <span class="ms-2"><a href="javascript:void(0);" id="logoFileDownload" onclick="downloadimg('{{ $supplier->id }}', 'logo', '{{ Str::substr($supplier->logo, stripos($supplier->logo, "logo_") + 5) }}')"  title="{{ Str::substr($supplier->logo, stripos($supplier->logo, 'logo_') + 5) }}" style="text-decoration: none;"> {{ $logo_name }}</a></span>
                                                                                <span class="removeFile" id="logoFile" data-id="{{ $supplier->id }}" file-path="{{ $supplier->logo }}" data-name="logo"><a href="#" title="Remove logo"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a></span>
                                                                                <span class="ms-2"><a class="logoFile" href="javascript:void(0);" title="Download logo" onclick="downloadimg('{{ $supplier->id }}', 'logo', '{{ Str::substr($supplier->logo, stripos($supplier->logo, "logo_") + 5) }}')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3 mb-3">
                                                                    <label for="email" class="form-label">{{ __('admin.company_email') }} <span class="text-danger">*</span></label>
                                                                    <input type="email" class="form-control" id="email" name="email"
                                                                           value="{{ $supplier->email }}" required />
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="mobile" class="form-label">{{ __('admin.company_mobile') }} <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" id="mobile" name="mobile"
                                                                           value="{{ $supplier->mobile }}" required data-parsley-type="digits" data-parsley-maxlength="20"
                                                                           data-parsley-minlength="10" placeholder="XXXXXXXXXXX">
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="website" class="form-label">{{ __('admin.company_website') }}</label>
                                                                    <input type="text" class="form-control" id="website" name="website"
                                                                           value="{{ $supplier->website }}">
                                                                </div>

                                                                <div class="col-md-3 mb-3">
                                                                    <label for="interested_in" class="form-label">{{ __('admin.dealing_with_categories') }}</label>
                                                                    <input type="text" class="form-control" id="interested_in" name="interested_in"
                                                                           value="{{ $supplier->interested_in }}">
                                                                </div>

                                                                <div class="col-md-3 mb-3">
                                                                    <label for="nib" class="form-label">{{ __('admin.nib') }} <span class="text-danger">*</span></label>

                                                                    <input type="text" class="form-control" id="nib" name="nib"
                                                                           value="{{ $supplier->nib }}" required data-parsley-type="digits"
                                                                           data-parsley-minlength="13" data-parsley-maxlength="13">

                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label for=""  class="form-label">{{ __('admin.nib_file') }} </label>
                                                                    <div class="d-flex">
                                                    <span class="">
                                                        <input type="file" name="nib_file" class="form-control" id="nib_file" accept=".jpg,.png,.gif,.jpeg, .pdf" onchange="show(this)" hidden/>
                                                        <label class="upload_btn" for="nib_file">{{ __('admin.browse') }}</label></span>
                                                                        <div id="file-nib_file">
                                                                            <input type="hidden" class="form-control" id="old_nib_file" name="old_nib_file" value="{{ $supplier->nib_file }}">
                                                                            @if ($supplier->nib_file)
                                                                                @php
                                                                                    $nibFileTitle = Str::substr($supplier->nib_file, stripos($supplier->nib_file, "nib_file_") + 9);
                                                                                    $extension_nib_file = getFileExtension($nibFileTitle);
                                                                                    $nib_file_filename = getFileName($nibFileTitle);
                                                                                    if(strlen($nib_file_filename) > 10){
                                                                                        $nib_file_name = substr($nib_file_filename,0,10).'...'.$extension_nib_file;
                                                                                    } else {
                                                                                        $nib_file_name = $nib_file_filename.$extension_nib_file;
                                                                                    }
                                                                                @endphp
                                                                                <input type="hidden" class="form-control" id="oldnib_file" name="oldnib_file" value="{{ $supplier->nib_file }}">
                                                                                <span class="ms-2">
                                                                    <a href="javascript:void(0);" id="nibFileDownload" onclick="downloadimg('{{ $supplier->id }}', 'nib_file', '{{ $nibFileTitle }}')"  title="{{ $nibFileTitle }}" style="text-decoration: none;"> {{ $nib_file_name }}</a>
                                                                </span>
                                                                                <span class="removeFile" id="nibFile" data-id="{{ $supplier->id }}" file-path="{{ $supplier->nib_file }}" data-name="nib_file">
                                                                    <a href="javascript:void(0);" title="Remove Nib File"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a>
                                                                </span>
                                                                                <span class="ms-2">
                                                                    <a class="nib_file" href="javascript:void(0);" title="Download nib_file" onclick="downloadimg('{{ $supplier->id }}', 'nib_file', '{{ $nibFileTitle }}')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a>
                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3 mb-3">
                                                                    <label for="npwp" class="form-label">{{ __('admin.npwp') }}<span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" id="npwp" name="npwp" value="{{ $supplier->npwp }}" placeholder="11.222.333.4-555.666" required
                                                                           data-parsley-pattern="^(\d{2})*[.]{1}(\d{3})*[.]{1}(\d{3})*[.]{1}(\d{1})*[-]{1}(\d{3})*[.]{1}(\d{3})*$">
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="name" class="form-label">{{ __('admin.npwp_file') }}</label>
                                                                    <div class="d-flex">
                                                            <span class=""><input type="file" name="npwp_file" class="form-control" id="npwp_file" accept=".jpg,.png,.gif,.jpeg, .pdf" onchange="show(this)" hidden/>
                                                                <label class="upload_btn" for="npwp_file">{{ __('admin.browse') }}</label></span>
                                                                        <div id="file-npwp_file">
                                                                            <input type="hidden" class="form-control" id="old_npwp_file" name="old_npwp_file" value="{{ $supplier->npwp_file }}">
                                                                            @if ($supplier->npwp_file)
                                                                                @php
                                                                                    $npwpFileTitle = Str::substr($supplier->npwp_file, stripos($supplier->npwp_file, "npwp_file_") + 10);
                                                                                    $extension_npwp_file = getFileExtension($npwpFileTitle);
                                                                                    $npwp_file_filename = getFileName($npwpFileTitle);
                                                                                    if(strlen($npwp_file_filename) > 10){
                                                                                        $npwp_file_name = substr($npwp_file_filename,0,10).'...'.$extension_npwp_file;
                                                                                    } else {
                                                                                        $npwp_file_name = $npwp_file_filename.$extension_npwp_file;
                                                                                    }
                                                                                @endphp
                                                                                <input type="hidden" class="form-control" id="oldnpwp_file" name="oldnpwp_file" value="{{ $supplier->npwp_file }}">
                                                                                <span class="ms-2">
                                                                    <a href="javascript:void(0);" id="npwpFileDownload" onclick="downloadimg('{{ $supplier->id }}', 'npwp_file', '{{ $npwpFileTitle }}')"  title="{{ $npwpFileTitle }}" style="text-decoration: none;"> {{ $npwp_file_name }}</a>
                                                                </span>
                                                                                <span class="removeFile" id="npwpFile" data-id="{{ $supplier->id }}" file-path="{{ $supplier->npwp_file }}" data-name="npwp_file">
                                                                    <a href="javascript:void(0);" title="Remove Nib File"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a>
                                                                </span>
                                                                                <span class="ms-2">
                                                                    <a class="npwp_file" href="javascript:void(0);" title="Download npwp_file" onclick="downloadimg('{{ $supplier->id }}', 'npwp_file', '{{ $npwpFileTitle }}')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a>
                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label class="form-label">
                                                                    {{ __('admin.company_type') }}<span
                                                                            class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="d-flex ms-2">
                                                                        <div class="form-check ms-4">
                                                                            <input class="form-check-input non-pkp-btn"
                                                                                type="radio"
                                                                                name="companyType"
                                                                                id="exampleRadios2"
                                                                                value="2"
                                                                                required
                                                                                {{ ($supplier->company_type == 2) ? "checked" : "" }}
                                                                                >
                                                                            <label class="form-check-label"
                                                                                for="exampleRadios2">
                                                                                NON-PKP
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check ms-4">
                                                                            <input class="form-check-input pkp-btn" type="radio" name="companyType" id="exampleRadios1" value="1"  required {{ ($supplier->company_type == 1) ? "checked" : "" }}>
                                                                            <label class="form-check-label" for="exampleRadios1"> PKP
                                                                            </label>
                                                                        </div>
                                                                        <div class="uploadbutton ms-2 my-2 d-flex" id="upload">
                                                                            <span class="">
                                                                            <input type="file" name="pkp_file" class="form-control pkp-btn-non" id="pkp-doc" accept=".jpg,.png,.jpeg,.pdf" onchange="show(this)" hidden="" {{ ($supplier->company_type == 1 && $supplier->pkp_file == '') ? "required" : "" }}>
                                                                                @if ($supplier->company_type==1)
                                                                                <span class="brows-lable"><label style="z-index: 1;" class="upload_btn pkp_doc_option" for="pkp-doc">Browse</label></span>
                                                                                @else
                                                                                <span class="brows-lable"><label style="display:none; z-index: 1;" class="upload_btn pkp_doc_option" for="pkp-doc">Browse</label></span>
                                                                                @endif
                                                                            </span>
                                                                            <input type="hidden" class="form-control" id="oldpkp_file" name="oldpkp_file" value="{{ $supplier->pkp_file }}">
                                                                            <div id="file-pkp_file">
                                                                            @if ($supplier->company_type==1 && $supplier->pkp_file)
                                                                                <span class="ms-2">
                                                                                    <a href="javascript:void(0);" id="pkpFileDownload" onclick="downloadimg('{{ $supplier->id }}', 'pkp_file', '{{ $pkpFileTitle }}')"  title="{{ $pkpFileTitle }}" style="text-decoration: none;">{{ $pkp_file_name }}</a>
                                                                                </span>
                                                                                <span class="removeFile" id="pkp_file" data-id="{{ $supplier->id }}" file-path="{{ $supplier->pkp_file }}" data-name="pkp_file">
                                                                                    <a href="javascript:void(0);" title="Remove pkp File"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a>
                                                                                </span>
                                                                                <span class="ms-2">
                                                                                    <a class="pkp_file" href="javascript:void(0);" title="Download pkp_file" onclick="downloadimg('{{ $supplier->id }}', 'pkp_file', '{{ $pkpFileTitle }}')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a>
                                                                                </span>
                                                                            @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3 mb-3">
                                                                    <label for="group_margin"
                                                                        class="form-label">Group Margin
                                                                        % <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text"
                                                                    class="form-control"
                                                                    id="group_margin"
                                                                    name="group_margin" value="{{ $supplier->group_margin }}"
                                                                    required=""
                                                                    onkeypress="return isNumberKey(this, event);"
                                                                    readonly="">
                                                                </div>

                                                                <div class="col-12 mb-3">
                                                                    <label for="address" class="form-label">{{ __('admin.company_address') }} <span class="text-danger">*</span></label>
                                                                    <textarea name="address" class="form-control newtextarea" id="address" cols="30" rows="3"
                                                                              required data-parsley-errors-container="#address_error">{{ $supplier->address }}</textarea>
                                                                    <div id="address_error"></div>

                                                                    {{-- <input type="text" class="form-control" id="address" name="address" value="{{ $supplier->address }}" required> --}}
                                                                </div>

                                                                <div class="col-12 mb-3">
                                                                    <label for="description" class="form-label">{{ __('admin.company_description') }}</label>
                                                                    <textarea name="description" class="form-control newtextarea" id="description" cols="30"
                                                                              rows="3" >{{ $supplier->description }}</textarea>

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
                                                                <!-- <div class="col-md-3 mb-3">
                                                                    <label for="contactPersonName" class="form-label">{{ __('admin.contact_person_name') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" name="contactPersonName" id="contactPersonName"
                                                                           class="form-control" value="{{ $supplier->contact_person_name }}" required>
                                                                </div> -->

																<div class="col-md-3 mb-3">
                                                                    <label for="contactPersonName" class="form-label"> {{__('admin.contact_person_first_name')}}
                                                                    <span class="text-danger">*</span></label>
                                                                    <div class="d-flex date ">
                                                                        <select name="salutation" class="form-select w100p border-end-0" id="inputGroupSelect01" style="border-radius: 0px;  background-color: rgba(0, 0, 0, 0.05);">
                                                                            <option value="1"  {{$supplier->salutation == "1" ? "selected" : ""}}>{{__('admin.salutation_mr')}}</option>
                                                                            <option value="2" {{$supplier->salutation == "2" ? "selected" : ""}}>{{__('admin.salutation_ms')}}</option>
                                                                            <option value="3"  {{$supplier->salutation == "3" ? "selected" : ""}}>{{__('admin.salutation_mrs')}}</option>
                                                                        </select>
                                                                        <input type="text" name="contactPersonName" id="contactPersonName"
                                                                        class="form-control" value="{{ $supplier->contact_person_name }}" required >
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3 mb-3">
                                                                    <label for="contactPersonLastName" class="form-label"> {{__('admin.contact_person_last_name')}}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" name="contactPersonLastName" id="contactPersonLastName"
                                                                           class="form-control" value="{{ $supplier->contact_person_last_name }}" required >
                                                                </div>

                                                                <div class="col-md-3 mb-3">
                                                                    <label for="contactPersonEmail" class="form-label">{{ __('admin.contact_person_email') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="email" name="contactPersonEmail" id="contactPersonEmail"
                                                                           class="form-control" value="{{ $supplier->contact_person_email }}" required data-parsley-uniqueemailcontactpersion readonly>
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="contactPersonEmail" class="form-label">{{ __('admin.altenate_email') }}
                                                                    </label>
                                                                    <input type="email" name="alternate_email" id="alternate_email"
                                                                           class="form-control" value="{{ $supplier->alternate_email }}" >
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="contactPersonMobile" class="form-label">{{ __('admin.contact_person_phone') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" id="contactPersonMobile"
                                                                           name="contactPersonMobile" required data-parsley-pattern="^[\d]*$"
                                                                           data-parsley-maxlength="20" data-parsley-minlength="10" placeholder="XXXXXXXXXXX"
                                                                           value="{{ $supplier->contact_person_phone }}">
                                                                </div>
                                                                {{--@if(isset($asBuyer) && $asBuyer == 0)
                                                                    <div class="col-md-3 mb-3 inviteBuyer">
                                                                        <label class="form-label invisible">&nbsp;</label>
                                                                        <div>
                                                                            <a class="btn btn-primary" id="inviteSupplierButton" href="javascript:void(0);" onclick="inviteAsBuyer('{{ $supplier->id }}')">Invite as Supplier</a>
                                                                            <img class="hidden" id="loaderinviteSupplier" height="16px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading">
                                                                        </div>
                                                                    </div>
                                                                @endif--}}
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
                                                                <div class="col-md-3 mb-3 cursor-pointer">
                                                                    <label for="catalog" class="form-label">{{ __('admin.catalog') }}</label>
                                                                    <div class="d-flex">
                                                                        <span class=""><input type="file" name="catalog" class="form-control" id="catalog" accept=".jpg,.png,.gif,.jpeg, .pdf" onchange="show(this)" hidden/><label class="upload_btn" for="catalog">{{ __('admin.browse') }}</label></span>
                                                                        <div id="file-catalog">
                                                                            @if ($supplier->catalog)
                                                                                @php
                                                                                    $extension_catalog = substr($supplier->catalog, -4);
                                                                                    $catalog_filename = substr(Str::substr($supplier->catalog, stripos($supplier->catalog, 'catalog_') + 8), 0, -4);
                                                                                    if(strlen($catalog_filename) > 10){
                                                                                        $catalog_name = substr($catalog_filename,0,10).'...'.$extension_catalog;
                                                                                    } else {
                                                                                        $catalog_name = $catalog_filename.$extension_catalog;
                                                                                    }
                                                                                @endphp
                                                                                <input type="hidden" class="form-control" id="oldcatalog" name="oldcatalog" value="{{ $supplier->catalog }}">
                                                                                <span class="ms-2"><a href="javascript:void(0);" id="catalogFileDownload" onclick="downloadimg('{{ $supplier->id }}', 'catalog', '{{ Str::substr($supplier->catalog, stripos($supplier->catalog, "catalog_") + 8) }}')"  title="{{ Str::substr($supplier->catalog, stripos($supplier->catalog, 'catalog_') + 8) }}" style="text-decoration: none;"> {{ $catalog_name }}</a></span>
                                                                                <span class="removeFile" id="catalogFile" data-id="{{ $supplier->id }}" file-path="{{ $supplier->catalog }}" data-name="catalog"><a href="#" title="Remove catalog"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a></span>
                                                                                <span class="ms-2"><a class="catalogFile" href="javascript:void(0);" title="Download catalog" onclick="downloadimg('{{ $supplier->id }}', 'catalog', '{{ Str::substr($supplier->catalog, stripos($supplier->catalog, "catalog_") + 8) }}')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="pricing" class="form-label">{{ __('admin.pricing') }}</label>
                                                                    <div class="d-flex">
                                                                        <span class=""><input type="file" name="pricing" class="form-control" id="pricing" accept=".jpg,.png,.gif,.jpeg, .pdf" onchange="show(this)" hidden/><label class="upload_btn" for="pricing">{{ __('admin.browse') }}</label></span>
                                                                        <div id="file-pricing">
                                                                            <input type="hidden" class="form-control" id="oldpricing" name="oldpricing" value="{{ $supplier->pricing }}">
                                                                            @if ($supplier->pricing)
                                                                                @php
                                                                                    $extension_pricing = substr($supplier->pricing, -4);
                                                                                    $pricing_filename = substr(Str::substr($supplier->pricing, stripos($supplier->pricing, 'pricing_') + 8), 0, -4);
                                                                                    if(strlen($pricing_filename) > 10){
                                                                                        $pricing_name = substr($pricing_filename,0,10).'...'.$extension_pricing;
                                                                                    } else {
                                                                                        $pricing_name = $pricing_filename.$extension_pricing;
                                                                                    }
                                                                                @endphp
                                                                                <input type="hidden" class="form-control" id="oldpricing" name="oldpricing" value="{{ $supplier->pricing }}">
                                                                                <span class="ms-2"><a href="javascript:void(0);" id="pricingFileDownload" onclick="downloadimg('{{ $supplier->id }}', 'pricing', '{{ Str::substr($supplier->pricing, stripos($supplier->pricing, "pricing_") + 8) }}')"  title="{{ Str::substr($supplier->pricing, stripos($supplier->pricing, 'pricing_') + 8) }}" style="text-decoration: none;"> {{ $pricing_name }}</a></span>
                                                                                <span class="removeFile" id="pricingFile" data-id="{{ $supplier->id }}" file-path="{{ $supplier->pricing }}" data-name="pricing"><a href="#" title="Remove pricing"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a></span>
                                                                                <span class="ms-2"><a class="pricingFile" href="javascript:void(0);" title="Download pricing" onclick="downloadimg('{{ $supplier->id }}', 'pricing', '{{ Str::substr($supplier->pricing, stripos($supplier->pricing, "pricing_") + 8) }}')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="product" class="form-label">{{ __('admin.product') }}</label>
                                                                    <div class="d-flex">
                                                                        <span class=""><input type="file" name="product" class="form-control" id="product" accept=".jpg,.png,.gif,.jpeg, .pdf" onchange="show(this)" hidden/><label class="upload_btn" for="product">{{ __('admin.browse') }}</label></span>
                                                                        <div id="file-product">
                                                                            <input type="hidden" class="form-control" id="oldproduct" name="oldproduct" value="{{ $supplier->product }}">
                                                                            @if ($supplier->product)
                                                                                @php
                                                                                    $extension_product = substr($supplier->product, -4);
                                                                                    $product_filename = substr(Str::substr($supplier->product, stripos($supplier->product, 'product_') + 8), 0, -4);
                                                                                    if(strlen($product_filename) > 10){
                                                                                        $product_name = substr($product_filename,0,10).'...'.$extension_product;
                                                                                    } else {
                                                                                        $product_name = $product_filename.$extension_product;
                                                                                    }
                                                                                @endphp
                                                                                <input type="hidden" class="form-control" id="oldproduct" name="oldproduct" value="{{ $supplier->product }}">
                                                                                <span class="ms-2"><a href="javascript:void(0);" id="productFileDownload" onclick="downloadimg('{{ $supplier->id }}', 'product', '{{ Str::substr($supplier->product, stripos($supplier->product, "product_") + 8) }}')"  title="{{ Str::substr($supplier->product, stripos($supplier->product, 'product_') + 8) }}" style="text-decoration: none;"> {{ $product_name }}</a></span>
                                                                                <span class="removeFile" id="productFile" data-id="{{ $supplier->id }}" file-path="{{ $supplier->product }}" data-name="product"><a href="#" title="Remove product"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a></span>
                                                                                <span class="ms-2"><a class="productFile" href="javascript:void(0);" title="Download product" onclick="downloadimg('{{ $supplier->id }}', 'product', '{{ Str::substr($supplier->product, stripos($supplier->product, "product_") + 8) }}')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="commercialCondition" class="form-label">{{ __('admin.commercial_conditions') }}</label>
                                                                    <div class="d-flex">
                                                                        <span class=""><input type="file" name="commercialCondition" class="form-control" id="commercialCondition" accept=".jpg,.png,.gif,.jpeg, .pdf" onchange="show(this)" hidden/><label class="upload_btn" for="commercialCondition">{{ __('admin.browse') }}</label></span>
                                                                        <div id="file-commercialCondition">
                                                                            <input type="hidden" class="form-control" id="oldproduct" name="oldproduct" value="{{ $supplier->product }}">
                                                                            @if ($supplier->commercialCondition)
                                                                                @php
                                                                                    $extension_commercialCondition = substr($supplier->commercialCondition, -4);
                                                                                    $commercialCondition_filename = substr(Str::substr($supplier->commercialCondition, stripos($supplier->commercialCondition, 'termsconditions_file_') + 21), 0, -4);
                                                                                    if(strlen($commercialCondition_filename) > 10){
                                                                                        $commercialCondition_name = substr($commercialCondition_filename,0,10).'...'.$extension_commercialCondition;
                                                                                    } else {
                                                                                        $commercialCondition_name = $commercialCondition_filename.$extension_commercialCondition;
                                                                                    }
                                                                                @endphp
                                                                                <input type="hidden" class="form-control" id="oldcommercialCondition" name="oldcommercialCondition" value="{{ $supplier->commercialCondition }}">
                                                                                <span class="ms-2"><a href="javascript:void(0);" id="commercialConditionFileDownload" onclick="downloadimg('{{ $supplier->id }}', 'commercialCondition', '{{ Str::substr($supplier->commercialCondition, stripos($supplier->commercialCondition, "termsconditions_file_") + 21) }}')"  title="{{ Str::substr($supplier->commercialCondition, stripos($supplier->commercialCondition, 'termsconditions_file_') + 21) }}" style="text-decoration: none;"> {{ $commercialCondition_name }}</a></span>
                                                                                <span class="removeFile" id="commercialConditionFile" data-id="{{ $supplier->id }}" file-path="{{ $supplier->commercialCondition }}" data-name="commercialCondition"><a href="#" title="Remove commercial condition"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a></span>
                                                                                <span class="ms-2"><a class="commercialConditionFile" href="javascript:void(0);" title="Download commercialCondition" onclick="downloadimg('{{ $supplier->id }}', 'commercialCondition', '{{ Str::substr($supplier->commercialCondition, stripos($supplier->commercialCondition, "termsconditions_file_") + 21) }}')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>
                                                                            @endif
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
                                                                                       required>
                                                                                {{ __('admin.agree') }}
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
                                            @php $xenClass = ''; @endphp
                                            @if(empty($supplier->xen_platform_id))
                                                @php $xenClass = 'hidden'; @endphp
                                                @php $xenStyle=''; @endphp
                                                @if($supplier->status==0)
                                                    @php $xenStyle='display:none'; @endphp
                                                @endif
                                                <div class="col-md-12 mb-3 d-flex justify-content-end cursor-pointer">
                                                    <button type="button" class="btn btn-primary" style="{{$xenStyle}}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#xenditpopup" id="create-xenaccount">
                                                        Create Xendit Account
                                                    </button>
                                                </div>
                                            @endif
                                            <div class="col-md-12 pb-3 {{$xenClass}}" id="xen-div">
                                                <div class="card">
                                                    <div class="card-header d-flex align-items-center">
                                                        <div>
                                                            <h5><img height="20px" src="{{URL::asset('front-assets/images/icons/xendit.png')}}" alt="Supplier Details" class="pe-2"> {{ __('admin.xen_account_detail') }}</h5>
                                                        </div>
                                                        <div class="ms-auto">
                                                            <a href="#xenditpopupemail" data-bs-toggle="modal" data-bs-target="#xenditpopupemail" class="" id="xen-email-edit-btn">
                                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-3 pb-1">
                                                        <div class="row rfqform_view bg-white">
                                                            <div class="col-md-3 pb-2">
                                                                <label>{{ __('admin.xen_platform_id') }}</label>
                                                                <div class="text-dark"
                                                                     id="xen_platform_id"></div>
                                                            </div>
                                                            <div class="col-md-3 pb-2">
                                                                <label>{{ __('admin.xen_platform_balance') }}</label>
                                                                <div id="xen_balance"><img height="16px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading"></div>
                                                            </div>
                                                            <div class="col-md-3 pb-2">
                                                                <label>{{ __('admin.company_name') }}</label>
                                                                <div class="text-dark"
                                                                     id="supplier_company_name"></div>
                                                            </div>
                                                            <div class="col-md-3 pb-2">
                                                                <label>{{ __('admin.email') }}</label>
                                                                <div class="text-dark"
                                                                     id="supplier_email"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <div class="card">
                                                    <div class="card-header d-flex align-items-center">
                                                        <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/credit-card.png')}}" alt="Charges" class="pe-2"> <span>{{ __('admin.bank_details') }}</span></h5>
                                                    </div>
                                                    <div class="card-body p-3 pb-1">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-3 newtable_v2">
                                                                <div class="d-flex align-items-center justify-content-end pe-1 mb-3">
                                                                    <button type="button" class="btn btn-primary btn-sm"
                                                                            data-bs-toggle="modal" data-bs-target="#bankdetail">{{ __('admin.add') }}</button>
                                                                </div>
                                                                <div class="table-responsive">
                                                                    <table id="supplierBankTable" class="table table-hover border" style="width: 100%;">
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
                                                                    @foreach($supplierBanks as $supplierBank)
                                                                        @php
                                                                            $bankDetail = $supplierBank->bankDetail()->first(['name','code','logo']);
                                                                        @endphp
                                                                        <tr id="addbankdetails_{{$supplierBank->id}}">
                                                                            {{--<td class="t_bank_id">{{$supplierBank->id}}</td>--}}
                                                                            <td>
                                                                                <div class="p-1 pe-1">
                                                                                    @if($bankDetail->logo)
                                                                                        <img src="{{ URL($bankDetail->logo) }}" class="t_bank_logo" height="20px" width="20px" alt="bank Logo">
                                                                                    @else
                                                                                        <img src="{{ URL('assets/icons/bank.png') }}" class="t_bank_logo" height="20px" width="20px" alt="bank Logo">
                                                                                    @endif
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <span class="t_bank_name ps-1">{{$bankDetail->name}}</span>
                                                                            </td>
                                                                            <td class="t_bank_code">{{$bankDetail->code}}</td>
                                                                            <td class="t_bank_account_name">{{$supplierBank->bank_account_name}}</td>
                                                                            <td class="t_bank_account_number">{{$supplierBank->bank_account_number}}</td>
                                                                            <td class="t_primary_others">
                                                                                <div class="form-check form-switch"
                                                                                     style="margin-left: 0px;">
                                                                                    <input class="form-check-input account_is_primary" data-id="{{$supplierBank->id}}" type="checkbox" id="account_is_primary_{{$supplierBank->id}}" {{$supplierBank->is_primary?'checked':''}}>
                                                                                </div>
                                                                            </td>
                                                                            <td class="text-end text-nowrap"> <a href="javascript:void(0)" data-id="{{$supplierBank->id}}" class="editSupplierBankDetails" data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}"><i class="fa fa-edit" ></i></a>
                                                                                <a href="javascript:void(0)" data-id="{{$supplierBank->id}}" class="deletebankdetails ps-2" data-toggle="tooltip" ata-placement="top" title="{{__('admin.delete')}}"> <i class="fa fa-trash" ></i></a>
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

    {{-- Stpe-3 --}}
    <div class="modal fade version2 error_res" id="bankdetail" tabindex="-1" role="dialog" aria-labelledby="addBankLabel" aria-hidden="true">
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
                            <div class=" rfqform_view">
                                <form id="supplierBankForm" class="row" enctype="multipart/form-data" class="form-group" data-parsley-validate>
                                    @csrf
                                    <input type="hidden" name="id" id="supplier-bank-id" value="0">
                                    <!-- <div class="d-flex pb-2"> -->
                                    <div class="col-md-6 mb-2">
                                            <label class="form-label">{{ __('admin.bank_name') }}<span class="text-danger">*</span></label>
                                            <div class="d-flex error_ui">
                                                <select class="form-select w-100 text-primary" name="bank_id" id="bank_id" required onchange="bankChange($(this))">
                                                    <option value="">Select Bank</option>
                                                    @foreach($banks as $bank)
                                                        <option value="{{$bank['id']}}" data-code="{{$bank['code']??''}}" data-logo="{{$bank['logo']??''}}">{{$bank['name']}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="p-1 pe-2 "><img src="{{ URL('assets/icons/bank.png') }}" height="20px" width="20px" alt="bank Logo" id="bank-logo"></div>
                                            </div>
                                    </div>
                                    <div class="col-md-6 ps-3  mb-2">
                                            <!-- <div class="error_ui"> -->
                                                <label for="bank-code" class="form-label">{{ __('admin.bank_code') }} </label>
                                                <input type="text" class="form-control" id="bank-code" value="XXXXXXXX" disabled>
                                            <!-- </div> -->
                                    </div>
                                    <!-- <div class="d-flex pb-2"> -->
                                        <div class="col-md-6  mb-2">
                                            <!-- <div class="error_ui"> -->
                                                <label for="bank-name" class="form-label">{{ __('admin.bank_account_holder_name') }}<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="bank-name" name="bank_account_name" required data-parsley-pattern="^[a-zA-Z ]+$"
                                                       placeholder="John Doe">
                                            <!-- </div> -->
                                        </div>
                                        <div class="col-md-6 ps-3  mb-2">
                                            <!-- <div class="error_ui"> -->
                                                <label for="bank-account" class="form-label">{{ __('admin.bank_account_number') }}<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="bank-account" name="bank_account_number" data-parsley-pattern="[0-9]+" data-parsley-length="[8, 18]" required
                                                       placeholder="XXXXXXXXXXXXXX">
                                            <!-- </div> -->
                                        </div>
                                    <!-- </div> -->
                                    <div class="col-md-12 pb-2  mb-2">
                                        <!-- <div class="error_ui"> -->
                                            <label for="description"
                                                   class="form-label">{{ __('admin.description') }}</label>
                                            <textarea class="form-control" placeholder="Description"
                                                      id="description" name="description"></textarea>
                                        <!-- </div> -->
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

    @if(empty($supplier->xen_platform_id))
        <div class="modal fade" id="xenditpopup" tabindex="-1" aria-labelledby="xenditpopup" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 640px;" role="document">
                <div class="modal-content border-0" style="background-color: #ebedf1;">
                    <div class="modal-header p-3">
                        <h3 class="modal-title" style="color: white;" id="">{{ __('admin.xendit_account_details') }}</h3>
                        <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close">
                            <img src="{{ URL::asset('/front-assets/images/icons/times.png') }}" alt="Close">
                        </button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="card">
                            <div class="card-header align-items-center d-flex" style="background-color: #d7d9df;">
                                <img src="{{ URL::asset('/front-assets/images/icons/xendit.png') }}" alt="" height="20px" width="25px" srcset="">
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
                                                <input type="email" class="form-control" id="xen-email" required data-parsley-uniqueemail>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer pb-2 px-2" style="background-color: #f5f5f6;">
                        <button type="submit" id="create-xen-account" class="btn btn-primary">{{ __('admin.create') }}</button>
                        <img class="hidden" id="loader" height="16px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading">
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="modal fade" id="xenditpopupemail" tabindex="-1" aria-labelledby="xenditpopupemail" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 640px;" role="document">
            <div class="modal-content border-0" style="background-color: #ebedf1;">
                <div class="modal-header p-3">
                    <h3 class="modal-title" style="color: white;" id="">{{ __('admin.xendit_account_details') }}</h3>
                    <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal"
                            aria-label="Close">
                        <img src="{{ URL::asset('/front-assets/images/icons/times.png') }}" alt="Close">
                    </button>
                </div>
                <div class="modal-body p-3">
                    <div class="card">
                        <div class="card-header align-items-center d-flex"
                             style="background-color: #d7d9df;">
                            <img src="{{ URL::asset('/front-assets/images/icons/xendit.png') }}" alt="" height="20px"
                                 width="25px" srcset="">
                            <h5 class="mb-0 ps-2">{{ __('admin.xendit_account_info') }}</h5>
                        </div>
                        <div class="card-body p-3 pb-1">
                            <form id="xenEmailForm" data-parsley-validate>
                                <div class="row">
                                    <div class="pb-3 col-md-6">
                                        <label class="form-label">{{ __('admin.xen_platform_id') }}</label>
                                        <div class="d-flex">
                                            <input type="text" class="form-control" id="xen-id" disabled="">
                                        </div>
                                    </div>
                                    <div class="pb-3 col-md-6">
                                        <label class="form-label">{{ __('admin.xen_platform_balance') }}</label>
                                        <div class="d-flex">
                                            <input type="text" class="form-control" id="xen-balance" disabled="">
                                        </div>

                                    </div>
                                    <div class="pb-3 col-md-6">
                                        <label class="form-label">{{ __('admin.company_name') }}</label>
                                        <div class="d-flex">
                                            <input type="text" class="form-control" id="xen-name" disabled="">
                                        </div>
                                    </div>
                                    <div class="pb-3 col-md-6">
                                        <label class="form-label">{{ __('admin.email') }}</label>
                                        <div class="">
                                            <input type="email" class="form-control" id="xen-email2" required data-parsley-uniqueemail>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pb-2 px-2" style="background-color: #f5f5f6;">
                    <button type="button" class="btn btn-primary" id="update-xen-account">{{ __('admin.update') }}</button>
                    <img class="hidden" id="loader2" height="16px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var supplierId = {{ $supplier->id }};
        var editSupplierProductId = '';

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
        function updateSupplier() {
            var formData = new FormData($("#supplierEditForm")[0]);
            formData.append('address', tinyMCE.get('address').getContent());
            formData.append('description', tinyMCE.get('description').getContent());
            localStorage.setItem('contactPersionEmail_'+'{{$supplier->id}}', $('#contactPersonEmail').val());
            $.ajax({
                url: "{{ route('supplier-update') }}",
                data: formData,
                type: "POST",
                contentType: false,
                processData: false,
                success: function(successData) {
                    if(successData.success) {
                        if (successData.supplierId) {
                            //supplierId = successData.supplierId;
                            if (successData.changeXenCompany) {
                                $('#supplier_company_name').html('<img height="16px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading">');
                                updateXenAccountCompany();
                            }
                            resetToastPosition();
                            $.toast({
                                heading: "{{ __('admin.success') }}",
                                text: "{{ __('admin.supplier_updated_successfully') }}",
                                showHideTransition: "slide",
                                icon: "success",
                                loaderBg: "#f96868",
                                position: "top-right",
                            });

                            var  companyType = $('input[name=companyType]:checked').val();
                            if(companyType==1){
                                $("#oldpkp_file").val('setvalue');
                            }else if(companyType==2){
                                $("#oldpkp_file").val('AgainPkpOption');
                                $('input[name=pkp_file]').val('');
                            }
                            return false;
                        }
                    } else {
                        resetToastPosition();
                        $.toast({
                            heading: "{{ __('admin.warning') }}",
                            text: successData.messages,
                            showHideTransition: "slide",
                            icon: "warning",
                            loaderBg: "#57c7d4",
                            position: "top-right",
                        });

                    }
                },
                error: function() {
                    console.log("error");
                },
            });
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
            getXenDetails();
            $('#xenditpopup').on('hidden.bs.modal', function () {
                $("#xenAccountForm")[0].reset();
                $('#xenAccountForm').parsley().reset();
            });
            $('#xenditpopupemail').on('hidden.bs.modal', function () {
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
                },]
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
                .text("Finish")
                .addClass("btn btn-info hidden smartWizFinishBtn")
                .on("click", function() {
                    $(".smartWizFinishBtn").addClass("disabled");
                    var url = "{{ route('admin-dashboard') }}";
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
                    }else if (nextStepIndex==1){
                        getXenBalance(supplierId);
                    }
                    if (currentStepIndex == 0) {
                        $("#supplierEditForm").parsley().on('form:validate', function() {
                            tinymce.triggerSave();
                        });
                        if ($('#supplierEditForm').parsley().validate()) {
                            updateSupplier();
                            $(".removeFile").removeClass("hide");
                            $(".downloadbtn").removeClass("hide");
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
            window.Parsley.addValidator('uniqueemail', {
                validateString: function (value) {
                    let res = false;
                    xhr = $.ajax({
                        url: '{{ route('check-xen-email-exist') }}',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
                validateString: function (value) {
                    let res = false;
                    xhr = $.ajax({
                        url: '{{ route('check-user-email-exist') }}',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
                $('#supplierBankForm').parsley().reset();
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
                if (!is_checked && parseInt($('#supplier-bank-id').val())>0){
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

                if (!is_checked){
                    swal("{{ __('admin.primary_bank_message') }}!", {
                       // icon: "warning",
                       icon: "/assets/images/warn.png",
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
                            $("#file-"+ name).html('');
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
                var edit = '{{ __("admin.edit") }}';
                var deleteKey = '{{ __("admin.delete") }}';
                let tableData = '<tr id="addbankdetails_'+supplier_bank_details.id+'" class="odd">\n' +
                    '                                    <td class="" id="t_bank_name_'+supplier_bank_details.id+'">\n' +
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
                    '                                    <td id="t_action_'+supplier_bank_details.id+'" class="text-end text-nowrap"> <a href="javascript:void(0)" data-id="'+supplier_bank_details.id+'" class="editSupplierBankDetails" data-toggle="tooltip" ata-placement="top" title="'+edit+'"><i class="fa fa-edit"></i></a>\n' +
                    '                                        <a href="javascript:void(0)" data-id="'+supplier_bank_details.id+'" class="deletebankdetails ps-2" data-toggle="tooltip" ata-placement="top" title="'+deleteKey+'"> <i class="fa fa-trash"></i></a>\n' +
                    '                                    </td>\n' +
                    '                                </tr>';

                $("#supplierBankTable")
                    .DataTable()
                    .row.add($(tableData))
                    .draw();
            }
            $("#bankdetail").modal("hide");
        }

        String.prototype.beforeLastIndex = function (delimiter) {
            return this.split(delimiter).slice(0,-1).join(delimiter) || this + ""
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
                var supplier_id = '{{ $supplier->id }}';
                var image_path = '{{URL::asset("assets/icons/times-circle copy.png")}}';
                if (input.name == 'logo' || input.name == 'productImages') {
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
                        var download_function = "'" + supplier_id + "', " + "'" + input.name + "', " + "'" + fileName + "'";
                        if(fileName.beforeLastIndex(".").length >= 10) {
                            fileName = fileName.substring(0,10) +'....'+file_extension;
                        }
                        $('#file-' + input.name).html('');
                        $('#file-' + input.name).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.name + 'Download" style="text-decoration: none">' + fileName + '</a></span><span class="removeFile hide" id="' + input.name + 'File" data-id="' + supplier_id + '" data-name="' + input.name + '"><a href="#" title="Remove logo" style="text-decoration: none;"><img src="' + image_path + '" alt="CLose button" class="ms-2"></a></span><span class="ms-2"><a class="logoFile downloadbtn hide" href="javascript:void(0);" title="Download ' + input.name + '" onclick="downloadimg(' + download_function + ')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>');
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

        function downloadimg(id, fieldName, name){
            event.preventDefault();
            var data = {
                id: id,
                fieldName: fieldName
            }
            $.ajax({
                url: "{{ route('supplier-download-image-ajax') }}",
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

        function getSupplierDetails(new_email='') {
            $.ajax({
                url: "{{ route('supplier-details','') }}" + "/" + supplierId,
                type: 'GET',
                dataType: 'json',
                success: function (successData) {
                    if(successData.success) {
                        if (new_email=='') {
                            $("#xenditpopup #xen-ac-name").val(successData.data.name);
                            $("#xenditpopup #xen-email").val(successData.data.contact_person_email);
                        }else{
                            if (successData.data.xen_platform_id!='' && new_email!=successData.data.contact_person_email){
                                swal({
                                    //title: "Are you sure?",
                                    text: "{{ __('admin.xen_change_email_address_message') }}",
                                    icon: "/assets/images/info.png",
                                    buttons: ['{{ __('admin.keep_old') }}.', '{{ __('admin.change_it') }}'],
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
                url: "{{ route('xen-account-details','') }}" + "/" + supplierId,
                type: 'GET',
                dataType: 'json',
                success: function (successData) {
                    if(successData.success) {
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
            $('#xen-balance').val('Rp '+(data.balance?data.balance:0));
            $('#xen-name').val(data.business_name);
            $('#xen-email2').val(data.email);
        }

        function updateXenAccountEmail(new_email,type=1){//1 for email change event, 2 for popup
            $.ajax({
                url: "{{ route('update-xen-account') }}",
                data: {id:supplierId,email:new_email},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                success: function(successData) {
                    if(successData.success) {
                        $.toast({
                            heading: '{{ __('admin.success') }}',
                            text: '{{ __('admin.xen_account_update_successfully') }}.',
                            showHideTransition: 'slide',
                            icon: 'success',
                            loaderBg: '#f96868',
                            position: 'top-right'
                        });
                        $('#supplier_email').html(new_email);
                        if(type==2){
                            $("#update-xen-account").toggleClass('hidden');
                            $('#loader2').toggleClass('hidden');
                            $('#xenditpopupemail').modal('hide');
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
                        if(type==2){
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
                    if(type==2){
                        $("#update-xen-account").toggleClass('hidden');
                        $('#loader2').toggleClass('hidden');
                    }
                    console.log(error);
                },
            });
        }

        function updateXenAccountCompany(){
            $.ajax({
                url: "{{ route('update-xen-account') }}",
                data: {id:supplierId},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                success: function(successData) {
                    if(successData.success) {
                        if (successData.xenAccount) {
                            $('#supplier_company_name').html(successData.xenAccount.public_profile.business_name);
                        }
                    }else{
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

        $(document).on("click", "#xen-email-edit-btn", function () {
            $('#xen-id').val($('#xen_platform_id').text());
            $('#xen-balance').val($('#xen_balance').text()?$('#xen_balance').text():'Rp 0');
            $('#xen-email2').val($('#supplier_email').text());
            $('#xen-name').val($('#name').val());
        });

        $(document).on("click", "#create-xenaccount", function () {
            getSupplierDetails();
        });

        $(document).on("click", "#create-xen-account", function () {
            if ($('#xenAccountForm').parsley().validate()) {
                $(this).toggleClass('hidden');
                $('#loader').toggleClass('hidden');
                let that = $(this);
                $.ajax({
                    url: "{{ route('create-xen-account','') }}",
                    data: {id: supplierId, email: $('#xen-email').val()},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    dataType: 'json',
                    success: function (successData) {
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
                    error: function (error) {
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
        $(document).on("click", "#update-xen-account", function () {
            if ($('#xenEmailForm').parsley().validate()) {
                $(this).toggleClass('hidden');
                $('#loader2').toggleClass('hidden');
                updateXenAccountEmail($('#xen-email2').val(),2);
            }
        });

        /**Start pkp non-pkp use to hide show functionality*/
        var PkpNonPkpTab = function(){

            GetFileName = function (FileName){
                if(FileName=='pkp_file'){
                $('#pkp-doc').attr('required', 'true');
                $('#oldpkp_file').val('');
                }


                if(FileName=='pkp_fileFile'){
                $('#pkp-doc').val('');
                $('#pkp-doc').attr('required', 'true');
                $('#oldpkp_file').val('');
                }
            },

            pkpClassFunction = function(){
                $('.pkp-btn').on('click',function(){
                    $('.pkp_doc_option').show();
                    var pkpValue = $('#oldpkp_file').val();
                    if(pkpValue && (pkpValue!='AgainPkpOption')){
                        $('#pkp_file').show();
                        $('#file-pkp_file').show();
                    }else{
                        if(pkpValue=='AgainPkpOption'){
                           $('#file-pkp_file').html('');
                           $('#file-pkp_file').show();
                        }
                        $('#pkp-doc').attr('required', 'true');
                        $('#parsley-id-32').show();
                    }
                });
            };

            nonPkpClassFunction = function(){
                $('.non-pkp-btn').on('click',function(){
                    var pkpValue = $('#oldpkp_file').val();
                    $('.pkp_doc_option').hide();
                    $('#pkp-doc').removeAttr('required');
                    if(pkpValue && (pkpValue!='AgainPkpOption')){
                        $('#pkp_file').hide();
                        $('#file-pkp_file').hide();
                    }else{
                        $('#pkp_file').val('');
                        $('#file-pkp_file').html('');
                        $('#parsley-id-32').hide();
                    }
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
            @php
                $cPhoneCode = $supplier->c_phone_code?str_replace('+','',$supplier->c_phone_code):62;
                $cpPhoneCode = $supplier->cp_phone_code?str_replace('+','',$supplier->cp_phone_code):62;
                $cCountry = $supplier->c_phone_code?strtolower(getRecordsByCondition('countries',['phone_code'=>$cPhoneCode],'iso2',1)):'id';
                $cpCountry = $supplier->cp_phone_code?strtolower(getRecordsByCondition('countries',['phone_code'=>$cpPhoneCode],'iso2',1)):'id';
            @endphp
            $('input[name="c_phone_code"]').val('{{$cPhoneCode}}');
            $('input[name="cp_phone_code"]').val('{{$cpPhoneCode}}');

            iti.setCountry('{{$cCountry}}');
            iti2.setCountry('{{$cpCountry}}');
        });
    </script>
@endpush
