@extends('admin/adminLayout')
@push('bottom_head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .suplier-succ {
            color: green;
            font-size: 20px;
        }

        #buyer-steps ul {
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

        #buyer-steps ul:before {
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
            background-image: url(../../front-assets/images/icons/people-carry-1_white.png);
            background-repeat: no-repeat;
            background-position: center center;
        }

        .contact_step .icon .pen_icon.product {
            background-image: url(../../front-assets/images/icons/boxes_white.png);
            background-repeat: no-repeat;
            background-position: center center;
        }

        .contact_step .icon .pen_icon.bank {
            background-image: url(../../front-assets/images/icons/icon_bank_white.png);
            background-repeat: no-repeat;
            background-position: center center;
        }

        .contact_step .icon .pen_icon.users {
            background-image: url(/front-assets/images/icons/company_users.png);
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

        .img_section_buyer {
            display: grid;
            place-items: center;
        }

        .image_section_user_admin {
            height: 80px;
            width: 80px;
            /* background-color: gray; */
        }

        .contact_step .icon .pen_icon.company {
            background-image: url(/assets/images/icon_contact_info.png);
            background-repeat: no-repeat;
            background-position: center center;
        }

        .contact_step .icon .pen_icon.buyer {
            background-image: url(/assets/images/icon_buyer_W.png);
            background-repeat: no-repeat;
            background-position: center center;
        }

        .logobanner_section {
            background: url(/front-assets/images/logo_background.png) center center;
            height: 200px;
            background-position: cover;
        }

        .logobanner_section img {
            max-height: 120px;
        }

        .iti--separate-dial-code .iti__selected-flag {
            font-size: 12px;
            height: 100% !important;
        }

        .sw-theme-dots > .nav .nav-link::after {
            left: 10px !important;
        }

    </style>
@endpush

@section('content')
    <link href="{{ URL::asset('front-assets/js/front/crop/css/style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/js/front/crop/css/style-example.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/js/front/crop/css/jquery.Jcrop.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front-assets/css/front/croppie.min.css') }}" rel='stylesheet'/>
    <script type="text/javascript"
            src="{{ URL::asset('front-assets/js/front/crop/scripts/jquery.Jcrop.js') }}"></script>
    <script type="text/javascript"
            src="{{ URL::asset('front-assets/js/front/crop/scripts/jquery.SimpleCropper.js') }}"></script>
    <script src='{{ asset("front-assets/js/front/croppie.js")}}'></script>
    <script src="{{ URL::asset('front-assets/intlTelInput/js/intlTelInput.js') }}"></script>
    @php
        $checkNotAdminRole = (Auth::user()->role_id == \App\Models\Role::ADMIN)? 0 : 1;
    @endphp
    <div class="row">
        <div class="col-md-12 d-flex align-items-center mb-3">
            <h1 class="mb-0 h3">{{ __('order.company') }}</h1>
            <a href="{{ route('admin.buyer.company.list') }}" class="mb-2 backurl ms-auto btn-close"></a>
        </div>

        <div class="col-12">
            <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                            type="button" role="tab" aria-controls="home"
                            aria-selected="true">{{ __('admin.edit_company') }}</button>
                </li>
            </ul>
            <div class="tab-content pt-3 pb-0 px-0" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div id="buyer-steps" class="sw sw-theme-dots sw-justified">
                        <ul class="nav col-md-6 contact_step">
                            <li>
                                <a class="nav-link active" href="#step-1">
                                    {{__('order.company')}}
                                    <div class="icon">
                                        <i class="pen_icon company"></i>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="nav-link" href="#step-2">
                                    {{ __('admin.company_admin') }}
                                    <div class="icon">
                                        <i class="pen_icon buyer"></i>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="nav-link" href="#step-3">
                                    {{ __('admin.company_users') }}
                                    <div class="icon">
                                        <i class="pen_icon users"></i>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content pt-3 pb-0 ">

                            <div id="step-1" class="tab-pane p-0" role="tabpanel" style="display: block;">
                                <form class="" id="buyerEditForm" method="POST" enctype="multipart/form-data"
                                      action="{{ route('admin.buyer.company.list.update.company') }}">
                                    @csrf
                                    <input type="hidden" name="formType" value="companyDetails">
                                    <input type="hidden" name="inputErrors" class="inputErrors" value="">
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <div class="card">
                                                <div class="card-header d-flex align-items-center">
                                                    <h5 class="mb-0"><img
                                                            src="{{URL::asset('assets/icons/icon_company.png')}}"
                                                            alt="Company" class="pe-2" height="20px">
                                                        <span>{{ __('admin.company_details') }}</span></h5>
                                                </div>
                                                <div class="card-body p-3 pb-1">
                                                    <div class="row">
                                                        <div class="col-md-4 col-lg-4">
                                                            <div class="mb-3"
                                                                 style="display: grid; place-items: center;">
                                                                <div class="w-100" style="max-width: 350px">
                                                                    @php
                                                                        $imgpath = URL::asset("front-assets/images/front/logo.png");
                                                                        if ($buyer->company->logo) {
                                                                            $imgpath = asset("storage/") . '/'.$buyer->company->logo ;
                                                                        }
                                                                    @endphp
                                                                    <input type="hidden" name="background_logo"
                                                                           id="background_logo"
                                                                           class="inputfile inputfile-3 d-none"/>
                                                                    <div
                                                                        class="logobanner_section d-flex align-items-center justify-content-center w-100 p-3 container5">

                                                                        <img src="{{$imgpath}}" alt="company logo"
                                                                             class="mw-100" id="companyLogoPreview">

                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="text-center">
                                                                <div class="">
                                                                    <span class=""><input type="file" name="logo"
                                                                                          class="form-control" id="logo"
                                                                                          accept=".jpg,.png,.jpeg"
                                                                                          onchange="loadCompanyLogo(event)"
                                                                                          hidden><label
                                                                            class="upload_btn"
                                                                            for="logo">{{__('admin.upload_log')}}</label></span>

                                                                </div>
                                                                <div class="text-muted mt-1">
                                                                    <small>{{__('profile.upload_jpg_png_text') }}</small>

                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-8 col-lg-8">
                                                            <div class="row g-3">
                                                                <div class="col-md-6 ">
                                                                    <label for="name"
                                                                           class="form-label">{{ __('admin.company_name') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="hidden" name="company_id"
                                                                           id="company_id"
                                                                           value="{{ $buyer->company->id }}">
                                                                    <input type="hidden" name="user_id" id="user_id"
                                                                           value="{{ $buyer->id }}">
                                                                    <input type="text" class="form-control max-255" id="name"
                                                                           name="name"
                                                                           value="{{ $buyer->company->name }}" required>
                                                                    <span id="nameError"
                                                                          class="text-danger companyvalidate"></span>
                                                                </div>
                                                                <div class="col-md-6 ">
                                                                    <label for="website"
                                                                           class="form-label">{{ __('admin.company_website') }}</label>
                                                                    <input type="text" class="form-control max-255" id="website"
                                                                           name="website"
                                                                           value="{{ $buyer->company->web_site }}">
                                                                    <span id="websiteError"
                                                                          class="text-danger companyvalidate"></span>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="email"
                                                                           class="form-label max-255">{{ __('profile.company_email') }} @if($checkNotAdminRole)
                                                                            <span class="text-danger">*</span> @endif
                                                                    </label>
                                                                    <input type="email" class="form-control max-255"
                                                                           id="company_email" name="company_email"
                                                                           value="{{ $buyer->company->company_email }}"
                                                                           @if($checkNotAdminRole) required @endif>
                                                                    <span id="company_emailError"
                                                                          class="text-danger companyvalidate"></span>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="email"
                                                                           class="form-label">{{ __('profile.alternative_email') }}</label>
                                                                    <input type="email" class="form-control max-255"
                                                                           id="alternative_email"
                                                                           name="alternative_email"
                                                                           value="{{ $buyer->company->alternative_email }}">
                                                                    <span id="alternative_emailError"
                                                                          class="text-danger companyvalidate"></span>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="mobile"
                                                                           class="form-label">{{ __('profile.company_phone') }} @if($checkNotAdminRole)
                                                                            <span class="text-danger">*</span> @endif
                                                                    </label>
                                                                    <input type="text" class="form-control input-number max-255"
                                                                           id="company_phone" name="company_phone"
                                                                           value="{{ $buyer->company->company_phone }}"

                                                                           @if($checkNotAdminRole) required @endif>
                                                                    <span id="company_phoneError"
                                                                          class="text-danger companyvalidate"></span>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label
                                                                        class="form-label">{{ __('profile.alternative_phone') }}</label>
                                                                    <input type="text" class="form-control input-number max-255"
                                                                           name="alternative_phone"
                                                                           id="alternative_phone"
                                                                           value="{{ $buyer->company->alternative_phone }}">
                                                                    <span id="alternative_phoneError"
                                                                          class="text-danger companyvalidate"></span>
                                                                </div>
                                                                <div class="col-12 mb-1 textbox_error">
                                                                    <label for="addressbuyer"
                                                                           class="form-label">{{ __('admin.company_address') }} </label>
                                                                    <textarea name="addressbuyer"
                                                                              class="form-control newtextarea1"
                                                                              id="addressbuyer" cols="30" rows="3"
                                                                    >{{ $buyer->company->address }}</textarea>
                                                                    <span id="addressbuyerError"
                                                                          class="text-danger companyvalidate"></span>
                                                                </div>
                                                                <div class="col-md-6 mb-0">
                                                                    <label for="nib"
                                                                           class="form-label">{{ __('profile.registration_nib') }} @if($checkNotAdminRole)
                                                                            <span class="text-danger">*</span> @endif
                                                                    </label>

                                                                    <input type="text" class="form-control input-number"
                                                                           id="registration_nib" name="registration_nib"
                                                                           value="{{ $buyer->company->registrantion_NIB }}"
                                                                           @if($checkNotAdminRole) required @endif >
                                                                    <span id="registration_nibError"
                                                                          class="text-danger companyvalidate"></span>
                                                                </div>
                                                                <div class="col-md-6 mb-0">
                                                                    <label for=""
                                                                           class="form-label">{{ __('profile.nib_file') }} </label>
                                                                    <div class="d-flex align-items-center">
                                                                                            <span class="">
                                                                                                <input type="file"
                                                                                                       name="nib_file"
                                                                                                       class="form-control"
                                                                                                       id="nib_file"
                                                                                                       accept=".jpg,.png,.gif,.jpeg,.pdf"
                                                                                                       onchange="SnippetCompanyUsersDetails.showFile(this)"
                                                                                                       hidden="">
                                                                                                <label
                                                                                                    class="upload_btn"
                                                                                                    for="nib_file">{{ __('profile.browse') }}</label></span>
                                                                        <div id="file-nib_file">
                                                                            <input type="hidden" class="form-control"
                                                                                   id="old_nib_file" name="old_nib_file"
                                                                                   value="{{ $buyer->company->nib_file }}">
                                                                            @if ($buyer->company->nib_file)
                                                                                @php
                                                                                    $nibFileTitle = Str::substr($buyer->company->nib_file, stripos($buyer->company->nib_file, "nib_file_") + 9);
                                                                                    $extension_nib_file = getFileExtension($nibFileTitle);
                                                                                    $nib_file_filename = getFileName($nibFileTitle);
                                                                                    if(strlen($nib_file_filename) > 10){
                                                                                        $nib_file_name = substr($nib_file_filename,0,10).'...'.$extension_nib_file;
                                                                                    } else {
                                                                                        $nib_file_name = $nib_file_filename.$extension_nib_file;
                                                                                    }
                                                                                @endphp
                                                                                <input type="hidden"
                                                                                       class="form-control"
                                                                                       id="oldnib_file"
                                                                                       name="oldnib_file"
                                                                                       value="{{ $buyer->company->nib_file }}">
                                                                                <span class="ms-2">
                                                                                <a href="javascript:void(0);"
                                                                                   id="nibFileDownload"
                                                                                   onclick="SnippetCompanyUsersDetails.downloadimg('{{ $buyer->id }}', 'nib_file', '{{ $nibFileTitle }}')"
                                                                                   title="{{ $nibFileTitle }}"
                                                                                   style="text-decoration: none;"> {{ $nib_file_name }}</a>
                                                                                </span>
                                                                                <span class="removeFile" id="nibFile"
                                                                                      data-id="{{ $buyer->company->id }}"
                                                                                      file-path="{{ $buyer->company->nib_file }}"
                                                                                      data-name="nib_file">
                                                                                <a href="javascript:void(0);"
                                                                                   title="{{ __('profile.remove_file') }}"> <img
                                                                                        src="{{URL::asset('assets/icons/times-circle copy.png')}}"
                                                                                        alt="CLose button" class="ms-0"></a>
                                                                                </span>
                                                                                <span class="ms-0">
                                                                                <a class="nib_file"
                                                                                   href="javascript:void(0);"
                                                                                   title="{{ __('profile.download_file') }}"
                                                                                   onclick="SnippetCompanyUsersDetails.downloadimg('{{ $buyer->id }}', 'nib_file', '{{ $nibFileTitle }}')"
                                                                                   style="text-decoration: none;"><i
                                                                                        class="fa fa-cloud-download"></i></a>
                                                                                </span>
                                                                            @endif

                                                                            <div id="file-nib_file">
                                                                                <input type="hidden"
                                                                                       class="form-control"
                                                                                       id="old_nib_file"
                                                                                       name="old_nib_file" value="">
                                                                                <span id="nib_fileError"
                                                                                      class="text-danger companyvalidate"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="npwp"
                                                                           class="form-label">{{ __('profile.npwp') }} @if($checkNotAdminRole)
                                                                            <span class="text-danger">*</span> @endif
                                                                    </label>
                                                                    <input type="text" class="form-control input-npwp" id="npwp"
                                                                           name="npwp" value="{{ $buyer->company->npwp }}"
                                                                           placeholder="11.222.333.4-555.666"
                                                                           @if($checkNotAdminRole) required @endif>
                                                                    <span id="npwpError"
                                                                          class="text-danger companyvalidate"></span>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="name"
                                                                           class="form-label">{{ __('profile.npwp_file') }}</label>
                                                                    <div class="d-flex align-items-center">
                                                                                            <span class=""><input
                                                                                                    type="file"
                                                                                                    name="npwp_file"
                                                                                                    class="form-control"
                                                                                                    id="npwp_file"
                                                                                                    accept=".jpg,.png,.gif,.jpeg,.pdf"
                                                                                                    onchange="SnippetCompanyUsersDetails.showFile(this)"
                                                                                                    hidden="">
                                                                                                <label
                                                                                                    class="upload_btn"
                                                                                                    for="npwp_file">{{ __('profile.browse') }}</label></span>
                                                                        <div id="file-npwp_file">
                                                                            <input type="hidden" class="form-control"
                                                                                   id="old_npwp_file"
                                                                                   name="old_npwp_file"
                                                                                   value="{{ $buyer->company->npwp_file }}">
                                                                            @if ($buyer->company->npwp_file)
                                                                                @php
                                                                                    $npwpFileTitle = Str::substr($buyer->company->npwp_file, stripos($buyer->company->npwp_file, "npwp_file_") + 10);
                                                                                    $extension_npwp_file = getFileExtension($npwpFileTitle);
                                                                                    $npwp_file_filename = getFileName($npwpFileTitle);
                                                                                    if(strlen($npwp_file_filename) > 10){
                                                                                        $npwp_file_name = substr($npwp_file_filename,0,10).'...'.$extension_npwp_file;
                                                                                    } else {
                                                                                        $npwp_file_name = $npwp_file_filename.$extension_npwp_file;
                                                                                    }
                                                                                @endphp
                                                                                <input type="hidden"
                                                                                       class="form-control"
                                                                                       id="oldnpwp_file"
                                                                                       name="oldnpwp_file"
                                                                                       value="{{ $buyer->company->npwp_file }}">
                                                                                <span class="ms-2">
                                                                    <a href="javascript:void(0);" id="npwpFileDownload"
                                                                       onclick="SnippetCompanyUsersDetails.downloadimg('{{ $buyer->id }}', 'npwp_file', '{{ $npwpFileTitle }}')"
                                                                       title="{{ $npwpFileTitle }}"
                                                                       style="text-decoration: none;"> {{ $npwp_file_name }}</a>
                                                                </span>
                                                                                <span class="removeFile" id="npwpFile"
                                                                                      data-id="{{ $buyer->company->id }}"
                                                                                      file-path="{{ $buyer->company->npwp_file }}"
                                                                                      data-name="npwp_file">
                                                                    <a href="javascript:void(0);"
                                                                       title="{{ __('profile.remove_file') }}"> <img
                                                                            src="{{URL::asset('assets/icons/times-circle copy.png')}}"
                                                                            alt="CLose button" class="ms-0"></a>
                                                                </span>
                                                                                <span class="ms-0">
                                                                    <a class="npwp_file" href="javascript:void(0);"
                                                                       title="{{ __('profile.download_file') }}"
                                                                       onclick="SnippetCompanyUsersDetails.downloadimg('{{ $buyer->id }}', 'npwp_file', '{{ $npwpFileTitle }}')"
                                                                       style="text-decoration: none;"><i
                                                                            class="fa fa-cloud-download"></i></a>
                                                                </span>
                                                                            @endif
                                                                            <span id="npwp_fileError"
                                                                                  class="text-danger companyvalidate"></span>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="name"
                                                                           class="form-label">{{ __('admin.commercial_terms') }}</label>
                                                                    <div class="d-flex align-items-center">
                                                                        <span class="">
                                                                            <input type="file"
                                                                                   name="termsconditions_file"
                                                                                   class="form-control"
                                                                                   id="termsconditions_file"
                                                                                   accept=".jpg,.png,.gif,.jpeg,.pdf"
                                                                                   onchange="SnippetCompanyUsersDetails.showFile(this)" hidden="">
                                                                            <label class="upload_btn"
                                                                                   for="termsconditions_file">{{ __('profile.browse') }}</label>
                                                                        </span>

                                                                        <div id="file-termsconditions_file">
                                                                            <input type="hidden" class="form-control"
                                                                                   id="old_termsconditions_file"
                                                                                   name="old_termsconditions_file"
                                                                                   value="{{ $buyer->company->termsconditions_file }}">
                                                                            @if ($buyer->company->termsconditions_file)
                                                                                @php
                                                                                    $termsconditionsFileTitle = Str::substr($buyer->company->termsconditions_file, stripos($buyer->company->termsconditions_file, "termsconditions_file_") + 21);
                                                                                    $extension_termsconditions_file = getFileExtension($termsconditionsFileTitle);
                                                                                    $termsconditions_file_filename = getFileName($termsconditionsFileTitle);
                                                                                    if(strlen($termsconditions_file_filename) > 10){
                                                                                        $termsconditions_file_name = substr($termsconditions_file_filename,0,10).'...'.$extension_termsconditions_file;
                                                                                    } else {
                                                                                        $termsconditions_file_name = $termsconditions_file_filename.$extension_termsconditions_file;
                                                                                    }
                                                                                @endphp
                                                                                <input type="hidden"
                                                                                       class="form-control"
                                                                                       id="oldtermsconditions_file"
                                                                                       name="oldtermsconditions_file"
                                                                                       value="{{ $buyer->company->termsconditions_file }}">
                                                                                <span class="ms-2">
                                                                                        <a href="javascript:void(0);"
                                                                                           id="termsconditionsFileDownload"
                                                                                           onclick="SnippetCompanyUsersDetails.downloadimg('{{ $buyer->id }}', 'termsconditions_file', '{{ $termsconditionsFileTitle }}')"
                                                                                           title="{{ $termsconditionsFileTitle }}"
                                                                                           style="text-decoration: none;"> {{ $termsconditions_file_name }}</a>
                                                                                    </span>
                                                                                <span class="removeFile" id="npwpFile"
                                                                                      data-id="{{ $buyer->company->id }}"
                                                                                      file-path="{{ $buyer->company->termsconditions_file }}"
                                                                                      data-name="termsconditions_file">
                                                                                        <a href="javascript:void(0);"
                                                                                           title="{{ __('profile.remove_file') }}"> <img
                                                                                                src="{{URL::asset('assets/icons/times-circle copy.png')}}"
                                                                                                alt="CLose button"
                                                                                                class="ms-0"></a>
                                                                                    </span>
                                                                                <span class="ms-0">
                                                                                        <a class="termsconditions_file"
                                                                                           href="javascript:void(0);"
                                                                                           title="{{ __('profile.download_file') }}"
                                                                                           onclick="SnippetCompanyUsersDetails.downloadimg('{{ $buyer->id }}', 'termsconditions_file', '{{ $termsconditionsFileTitle }}')"
                                                                                           style="text-decoration: none;"><i
                                                                                                class="fa fa-cloud-download"></i></a>
                                                                                    </span>
                                                                            @endif
                                                                        </div>
                                                                        <span id="termsconditions_fileError"
                                                                              class="text-danger companyvalidate"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mb-2">
                                            <div class="card">
                                                <div class="card-header d-flex align-items-center">
                                                    <h5 class="mb-0"><img src="{{URL::asset('assets/icons/boxes.png')}}"
                                                                          alt="Company" class="pe-2" height="20px">
                                                        <span> {{ __('profile.Company_Yearly_Consumption_Detail') }}
                                                                                    <a href="#" id="addInterestedBlock"
                                                                                       data-bs-toggle="modal"
                                                                                       data-bs-target="#exampleModal"
                                                                                       title="Add"
                                                                                       class="text-decoration-none text-dark ps-1"><svg
                                                                                            style="width: 18px; height:18px; cursor: pointer;"
                                                                                            viewBox="0 0 24 24">
                                                                                            <path fill="currentColor"
                                                                                                  d="M17,13H13V17H11V13H7V11H11V7H13V11H17M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z">
                                                                                            </path>
                                                                                        </svg></a></span></h5>
                                                </div>
                                                <div class="card-body p-3 pb-1" id="mainInterestedInHtml">
                                                    @if (count($company_consumptions))
                                                        @php $k = 0 ; @endphp
                                                        @foreach ($company_consumptions as $key => $company_consumption)
                                                            <div class="row align-items-center interested_in_html clone_{{$k}}">
                                                                <div class="col-md-11 d-flex">
                                                                    <div class="col-md-4 mb-2">
                                                                        <label
                                                                            class="form-label">{{ __('profile.Category') }}</label>
                                                                        <select data-cloneid="0"
                                                                                class="form-select addvalidation productcategory"
                                                                                name="ProductCategory[]">
                                                                            <option
                                                                                value="">{{__('profile.select_category')}}</option>
                                                                            @foreach ($category as $cat)
                                                                                @php @endphp
                                                                                <option
                                                                                    {{ $company_consumption->product_cat_id == $cat->id ? 'selected' : '' }}
                                                                                    value="{{ $cat->id }}">
                                                                                    {{ $cat->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-4 mb-2 ps-3">
                                                                        <label
                                                                            class="form-label">{{ __('profile.Annual_Consumption') }}</label>
                                                                        <input data-cloneid="{{$k}}" type="number"
                                                                               class="form-control addvalidation annualconsumption"
                                                                               name="ProductAnnualConsumption[]"
                                                                               value="{{ $company_consumption->annual_consumption }}"
                                                                               placeholder="0"
                                                                               data-parsley-type="number" min="0">
                                                                        <span class="invalid-feedback d-block"></span>
                                                                    </div>
                                                                    <div class="col-md-4 mb-2 ps-3">
                                                                        <label
                                                                            class="form-label">{{ __('profile.Unit') }}</label>
                                                                        <select data-cloneid="1"
                                                                                class="form-select addvalidation unit"
                                                                                name="ProductUnit[]">
                                                                            <option
                                                                                value="">{{ __('profile.select_unit') }}</option>
                                                                            @foreach ($units as $unit)
                                                                                <option
                                                                                    {{ $company_consumption->unit_id == $unit->id ? 'selected' : '' }}
                                                                                    value="{{ $unit->id }}">
                                                                                    {{ $unit->name }}
                                                                                </option>
                                                                            @endforeach

                                                                        </select>
                                                                        <span class="invalid-feedback d-block"></span>

                                                                    </div>
                                                                </div>
                                                                <div class="col-md-1 ">

                                                                                    <span
                                                                                        class="text-danger trashicon {{ $k == 0 ? 'hidden' : '' }}"> <i
                                                                                            class="fa fa-trash"></i>
                                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @php $k++ @endphp
                                                        @endforeach
                                                    @else
                                                        <div class="row align-items-center clone_0 interested_in_html">
                                                            <div class="col-md-11 d-flex">
                                                                <div class="col-md-4 mb-2">
                                                                    <label
                                                                        class="form-label">{{ __('profile.Category') }}</label>
                                                                    <select data-cloneid="0"
                                                                            class="form-select addvalidation productcategory"
                                                                            name="ProductCategory[]">
                                                                        <option
                                                                            value="">{{__('profile.select_category')}}</option>
                                                                        @foreach ($category as $cat)
                                                                            <option
                                                                                value="{{ $cat->id }}">
                                                                                {{ $cat->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4 mb-2 ps-3">
                                                                    <label
                                                                        class="form-label">{{ __('profile.Annual_Consumption') }}</label>
                                                                    <input data-cloneid="0" type="number"
                                                                           class="form-control addvalidation annualconsumption"
                                                                           name="ProductAnnualConsumption[]"
                                                                           placeholder="0" data-parsley-type="number"
                                                                           min="0">
                                                                    <span class="invalid-feedback d-block"></span>
                                                                </div>
                                                                <div class="col-md-4 mb-2 ps-3">
                                                                    <label
                                                                        class="form-label">{{ __('profile.Unit') }}</label>
                                                                    <select data-cloneid="1"
                                                                            class="form-select addvalidation unit"
                                                                            name="ProductUnit[]">
                                                                        <option
                                                                            value="">{{ __('profile.select_unit') }}</option>
                                                                        @foreach ($units as $unit)
                                                                            <option
                                                                                value="{{ $unit->id }}">
                                                                                {{ $unit->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <span class="invalid-feedback d-block"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label class="invisible">&nbsp;</label>
                                                                <div>
                                                                                        <span
                                                                                            class="text-danger trashicon hidden">
                                                                                            <i class="fa fa-trash"></i>
                                                                                        </span>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div id="companyYearlyContainer" class="card-body p-3 pb-0 pt-0"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <div class="card">
                                                <div class="card-header d-flex align-items-center">
                                                    <h5 class="mb-0"><img
                                                            src="{{URL::asset('assets/icons/platform_charges.png')}}"
                                                            alt="Company" class="pe-2" height="20px">
                                                        <span>{{ __('admin.platform_charges') }}</span></h5>
                                                </div>
                                                <div class="card-body p-3" id="mainInterestedInHtml">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="table-responsive newtable_v2 ">
                                                                <table class="table border">
                                                                    <thead>
                                                                    <tr>
                                                                        <th class="text-center"
                                                                            width="60px">{{--<input class="form-check-input mt-0" type="checkbox" onclick="checkListAll(this)" @if(count($platform_charges) == $count_other_charges) checked @endif>--}}</th>
                                                                        <th width="30%">{{ __('admin.charges') }}</th>
                                                                        <th width="20%">{{ __('admin.type') }}</th>
                                                                        <th>{{ __('admin.charges_amount') }} (RP)</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($platform_charges as $key => $value)
                                                                        <tr>
                                                                            <td class="text-center"><input
                                                                                    class="form-check-input checkbox-platform mt-0 @if($value->id == 10 || $value->editable == 0) checkbox-nochecked @endif"
                                                                                    type="checkbox"
                                                                                    name="platform_charges[]"
                                                                                    id="platform_charges_{{$value->id}}"
                                                                                    value="{{ $value->id }}"
                                                                                    @if(!empty($value->company)) checked
                                                                                    @endif @if($value->id == 10 || $value->editable == 0) disabled
                                                                                    @endif onclick="SnippetCompanyUsersDetails.changeValueEnableDisable(this, '{{ $value->id }}', '{{ $value->name }}')">
                                                                            </td>
                                                                            <td> {{ $value->name }}</td>
                                                                            @if($value->editable == 1 && $value->id != 10 && $value->company != NULL)
                                                                                <td>
                                                                                    <select
                                                                                        class="form-select selectBox"
                                                                                        name="type[{{$value->id}}]"
                                                                                        id="type_{{$value->id}}"
                                                                                        onchange="SnippetCompanyUsersDetails.ChangeValue(this, '{{$value->id}}')">
                                                                                        <option
                                                                                            {{ $value->xenditCommisionFee['type']== 0 ? 'selected="selected"' : '' }}  value="0">
                                                                                            %
                                                                                        </option>
                                                                                        <option
                                                                                            {{ $value->xenditCommisionFee['type']== 1 ? 'selected="selected"' : '' }}  value="1">
                                                                                            RP (Flat)
                                                                                        </option>
                                                                                    </select>
                                                                                    <i class="fa fa-chevron-down"></i>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text"
                                                                                           name="chargeValue[{{$value->id}}]"
                                                                                           id="chargeValue_{{$value->id}}"
                                                                                           class="form-control w-auto"
                                                                                           style="height: auto;"
                                                                                           required
                                                                                           value="{{ $value->xenditCommisionFee['charges_value'] }}">
                                                                                </td>
                                                                            @else
                                                                                <td>
                                                                                    <select
                                                                                        class="form-select selectBox"
                                                                                        name="type[{{$value->id}}]"
                                                                                        id="type_{{$value->id}}"
                                                                                        onchange="SnippetCompanyUsersDetails.ChangeValue(this, '{{$value->id}}')"
                                                                                        @if($value->id == 10 || $value->editable == 0 || (!empty($value->xenditCommisionFee['is_delete']) && $value->xenditCommisionFee['is_delete'] == 1)) disabled @endif>
                                                                                        <option
                                                                                            {{ $value->type== 0 ? 'selected="selected"' : '' }}  value="0">
                                                                                            %
                                                                                        </option>
                                                                                        <option
                                                                                            {{ $value->type== 1 ? 'selected="selected"' : '' }}  value="1">
                                                                                            RP (Flat)
                                                                                        </option>
                                                                                    </select>
                                                                                    <i class="fa fa-chevron-down"></i>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text"
                                                                                           name="chargeValue[{{$value->id}}]"
                                                                                           id="chargeValue_{{$value->id}}"
                                                                                           class="form-control w-auto"
                                                                                           style="height: auto;"
                                                                                           required
                                                                                           value="{{ $value->charges_value }}"
                                                                                           @if($value->id == 10 || $value->editable == 0 || (!empty($value->xenditCommisionFee['is_delete']) && $value->xenditCommisionFee['is_delete'] == 1)) disabled @endif>
                                                                                </td>
                                                                            @endif
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
                                </form>
                            </div>
                            <div id="step-2" class="tab-pane p-0" role="tabpanel" style="display: none;">
                                <form class="" id="buyerProfileEditForm" method="POST" enctype="multipart/form-data"
                                      action="{{ route('admin.buyer.company.list.update.company') }}">
                                    @csrf
                                    <input type="hidden" name="formType" value="buyerProfile">
                                    <input type="hidden" name="buyerid" value="{{ $buyer->id }}">
                                    <input type="hidden" name="buyerinputErrors" class="buyerinputErrors" value="">
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <div class="card">
                                                <div class="card-header d-flex align-items-center">
                                                    <h5 class="mb-0">
                                                                            <span class="pe-2">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="17.5" height="20"
                                                                                     viewBox="0 0 17.5 20">
                                                                                    <path id="icon_personal_pro"
                                                                                          d="M8.75,10a5,5,0,1,0-5-5A5,5,0,0,0,8.75,10Zm3.5,1.25H11.6a6.8,6.8,0,0,1-5.7,0H5.25A5.251,5.251,0,0,0,0,16.5v1.625A1.875,1.875,0,0,0,1.875,20h13.75A1.875,1.875,0,0,0,17.5,18.125V16.5A5.251,5.251,0,0,0,12.25,11.25Z">
                                                                                    </path>
                                                                                </svg>
                                                                            </span>
                                                        <span>{{__('admin.company_admin_details')}}</span></h5>
                                                </div>
                                                <div class="card-body p-3 pb-1">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class=" col-md-4 mb-3 img_section_buyer"
                                                                 title="{{ __('admin.update_image') }}">
                                                                @php
                                                                    $static_image = asset('settings.profile_images_folder') . '/' . 'no_image.png';
                                                                @endphp
                                                                <div class="user_info_photo text-center">
                                                                    @if($buyer->profile_pic)
                                                                        <div class="ratio ratio-1x1">
                                                                            <img
                                                                                src="{{asset('storage/' . $buyer->profile_pic) }}"
                                                                                name="userProfilePic"
                                                                                id="userProfilePic" height="80"
                                                                                width="80"
                                                                                class="avatar-xl rounded-circle" alt="">
                                                                        </div>
                                                                    @else
                                                                        <div class="ratio ratio-1x1">
                                                                            <img
                                                                                src="{{ URL::asset('/assets/images/user.png') }}"
                                                                                name="userProfilePic"
                                                                                id="userProfilePic" height="80"
                                                                                width="80"
                                                                                class="avatar-xl rounded-circle" alt="">
                                                                        </div>
                                                                    @endif
                                                                    <div class="mt-2" style="border-radius: 50px;">
                                                                        <input type="file" name="user_pic"
                                                                               class="form-control" id="user_pic"
                                                                               accept=".jpg,.png,.jpeg" hidden="">
                                                                        <label id="upload_btn"
                                                                               for="user_pic">{{ __('admin.update_image') }}</label>
                                                                    </div>


                                                                    <div class="text-muted mt-2">
                                                                        <small>{{ __('profile.upload_jpg_png_text') }}</small>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                            <div class="col-md-8">
                                                                <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label
                                                                            for="contactPersonName"
                                                                            class="form-label">{{ __('profile.first_name') }} @if($checkNotAdminRole)
                                                                                <span class="text-danger">*</span>@endif
                                                                        </label>
                                                                        <div class="d-flex">
                                                                            <select name="salutation"
                                                                                    class="form-select w100p border-end-0"
                                                                                    id="salutation"
                                                                                    style="border-radius: 0px;  background-color: rgba(0, 0, 0, 0.05); font-size: 0.875rem;">
                                                                                <option
                                                                                    value="1" {{(isset($buyer['salutation']) && $buyer['salutation'] == "1" ? 'selected' : '') }} >{{__('admin.salutation_mr')}}</option>
                                                                                <option
                                                                                    value="2" {{(isset($buyer['salutation']) && $buyer['salutation'] == "2" ? 'selected' : '') }}>{{__('admin.salutation_ms')}}</option>
                                                                                <option
                                                                                    value="3" {{(isset($buyer['salutation']) && $buyer['salutation'] == "3" ? 'selected' : '') }}>{{__('admin.salutation_mrs')}}</option>
                                                                            </select>
                                                                            <input type="text" name="firstName"
                                                                                   id="firstName" class="form-control  input-alpha-numeric max-255"
                                                                                   value="{{ $buyer->firstname }}"
                                                                                   @if($checkNotAdminRole) required
                                                                                   @endif>
                                                                        </div>
                                                                        <span id="firstName-error" class="text-danger buyervalidate"></span>
                                                                    </div>

                                                                    <div class="col-md-6 mb-3">
                                                                        <label
                                                                            for="contactPersonLastName"
                                                                            class="form-label">{{ __('profile.last_name') }} @if($checkNotAdminRole)
                                                                                <span
                                                                                    class="text-danger">*</span> @endif
                                                                        </label>
                                                                        <input type="text" name="lastName" id="lastName"
                                                                               class="form-control input-alpha-numeric max-255"
                                                                               value="{{ $buyer->lastname }}"
                                                                               @if($checkNotAdminRole) required
                                                                               @endif>
                                                                        <span id="lastName-error" class="text-danger buyervalidate"></span>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label
                                                                            for="contactPersonEmail"
                                                                            class="form-label">{{ __('profile.Email') }} @if($checkNotAdminRole)
                                                                                <span
                                                                                    class="text-danger">*</span> @endif
                                                                        </label>
                                                                        <input type="email"
                                                                               name="email"
                                                                               id="email"
                                                                               class="form-control"
                                                                               value="{{ $buyer->email }}"
                                                                               readonly>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label
                                                                            for="contactPersonMobile"
                                                                            class="form-label">{{ __('profile.mobile_number') }} @if($checkNotAdminRole)
                                                                                <span
                                                                                    class="text-danger">*</span> @endif
                                                                        </label>
                                                                        <input type="text" class="form-control input-number max-255"
                                                                               id="mobile" name="mobile"
                                                                               value="{{ $buyer->mobile }}"
                                                                               @if($checkNotAdminRole) required
                                                                               @endif>
                                                                        <span id="mobile-error" class="text-danger buyervalidate"></span>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label
                                                                            class="form-label">{{ __('profile.designation') }}</label>
                                                                        <select name="designation" id="designation"
                                                                                class="form-select">
                                                                            <option
                                                                                value="">{{__('profile.select_designation')}}</option>
                                                                            @foreach ($designations as $designation)
                                                                                <option {{ (isset($buyer->companyUserDetails[0]->designation) &&  $buyer->companyUserDetails[0]->designation == $designation->id) ? 'selected' : '' }} value="{{ $designation->id }}">
                                                                                    {{ $designation->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <span id="designation-error" class="text-danger buyervalidate"></span>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label
                                                                            class="form-label">{{ __('profile.department') }}</label>
                                                                        <select name="department" id="department"
                                                                                class="form-select">
                                                                            <option
                                                                                value="">{{__('profile.select_department')}}</option>
                                                                            @foreach ($departments as $department)
                                                                                <option {{ (isset($buyer->companyUserDetails[0]->department) &&  $buyer->companyUserDetails[0]->department == $department->id) ? 'selected' : '' }} value="{{ $department->id }}">
                                                                                    {{ $department->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <span id="department-error" class="text-danger buyervalidate"></span>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="step-3" class="tab-pane p-0" role="tabpanel"
                                 style="display: none;">
                                <div class="row">
                                    <div class="col-md-12 newtable_v2 mb-2">
                                        <div class="card ">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="mb-0"><img height="20px"
                                                                      src="http://beta.blitznet.co.id/assets/icons/people-carry-1.png"
                                                                      alt="Contact" class="pe-2">
                                                    <span>{{__('admin.company_users_details')}}</span></h5>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover dataTable no-footer"
                                                               style="width: 100%; height: 100%;" role="grid"
                                                               aria-describedby="companyTable_info"></table>
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
    <!-- Upload profile image modal -->
    <div class="customscroll showpop">
        <div class="modal fade" id="UploadProfileImageModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="popup-marg">
                        <div class="modal-header">
                            <h3 class="modal-title text-center text-white">{{ __('admin.update_image') }}</h3>
                            <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close">
                                <img src="{{URL::asset('front-assets/images/icons/times.png')}}" alt="Close">
                            </button>
                        </div>
                        <div class="modal-body p-0">
                            <div class="row">
                                <div class="col-md-12 text-center mt-20">
                                    <div id="profile_image_preview" class="mt-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="justify-content: center;">
                        <button id="cropProfileImage"
                                class="btn btn-primary crop-profile-picture  mt-0 save-btn save-btn-bg text-white">
                            Crop & Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Upload profile image modal -->
    <!-- Role Popup Modal start -->
    <form id="buyerUserForm" enctype="multipart/form-data" class="form-group">
        @csrf
        <div class="modal fade version2" id="companyRoleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div id="modelLodear"></div>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Role Permission modal -->
                    <div class="modal-header py-3">
                        <h5 class="modal-title" id="buyerCompanyName"></h5>
                        <button type="button" class="btn-close ms-0" data-bs-dismiss="modal"
                                aria-label="Close"><img src="{{URL::asset('assets/icons/times.png')}}"
                                                        alt="Close"></button>
                    </div>


                    <div class="modal-body p-3 pb-1 rfqform_view ">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center">
                                        <h5 class="mb-0"><img height="20px"
                                                              src="{{URL::asset('assets/icons/icon_company.png')}}"
                                                              alt="Company" class="pe-2">
                                            <span>Company Details</span>
                                        </h5>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row ">
                                            <div class="col-sm-6 mb-2">
                                                <label for="" class="form-label">{{ __('profile.first_name') }}<span
                                                        style="color:red">*</span></label>
                                                <input type="text" name="firstName" class="form-control input-alpha"
                                                       id="firstName" required>
                                                <span id="firstNameError" class="text-danger"></span>
                                            </div>

                                            <div class="col-sm-6 mb-2">
                                                <label for="" class="form-label">{{ __('profile.last_name') }}<span
                                                        style="color:red">*</span></label>
                                                <input type="text" name="lastName" class="form-control input-alpha"
                                                       id="lastName" required>
                                                <span id="lastNameError" class="text-danger"></span>
                                            </div>

                                            <div class="col-sm-6 mb-2">
                                                <label for="" class="form-label">{{ __('profile.Email') }}<span
                                                        style="color:red">*</span></label>
                                                <input type="email" name="email" class="form-control"
                                                       id="email" readonly>
                                                <span id="showEmailError" class="text-danger"></span>
                                                <span id="emailError" class="text-danger"></span>
                                            </div>

                                            <div class="col-sm-6 mb-2">
                                                <label for="" class="form-label">{{ __('profile.mobile_number') }}<span
                                                        style="color:red">*</span></label>
                                                <input type="tel" name="mobile" class="form-control input-number"
                                                       id="mobile" placeholder="XXXXXXXXXXX"
                                                       required>
                                                <span id="mobileError" class="text-danger"></span>
                                            </div>

                                            <div class="col-sm-6 mb-2" id="user_designation_div">
                                                <label for="" class="form-label">{{ __('profile.designation') }}<span
                                                        style="color:red">*</span></label>
                                                <select name="designation" id="designation"
                                                        class="form-select designationClass" required="">
                                                    <option value="">{{__('profile.select_designation')}}</option>
                                                    @foreach ($designations as $designation)
                                                        <option
                                                            value="{{ $designation->id }}">
                                                            {{ $designation->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span id="designationError" class="text-danger"></span>
                                            </div>

                                            <div class="col-sm-6 mb-2" id="user_department_div">
                                                <label for="" class="form-label">{{ __('profile.department') }}<span
                                                        style="color:red">*</span></label>
                                                <select name="department" id="department"
                                                        class="form-select departmentClass" required="">
                                                    <option value="">{{__('profile.select_department')}}</option>
                                                    @foreach ($departments as $department)
                                                        <option
                                                            value="{{ $department->id }}">
                                                            {{ $department->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span id="departmentError" class="text-danger"></span>
                                            </div>

                                            <div class="col-md-6" id="user_role_div">
                                                <label class="form-label">{{ __('admin.role') }}<span
                                                        style="color:red">*</span></label>
                                                <select name="role" id="role" class="form-select roleClass"
                                                        required="">
                                                    <option value="">{{__('admin.select_role')}}</option>
                                                    {{-- {{dd($customRoles)}}--}}
                                                    @foreach ($customRoles as $role)
                                                        <option value="{{ $role->id }}">
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span id="roleError" class="text-danger"></span>
                                            </div>
                                            <div class="col-sm-6 mb-2">
                                                <label for="" class="form-label">{{ __('buyer.branch') }}</label>
                                                <input type="text" name="branch" class="form-control"
                                                       id="branch">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="companyUserId" id="companyUserId" value=""/>
                    <input type="hidden" name="defaultCompanyId" id="defaultCompanyId" value=""/>
                    <div class="modal-footer p-3">
                        <button type="button" class="btn btn-primary" id="saveUserData">{{ __('admin.save') }}</button>
                        <button type="button" class="btn  btn-cancel" data-bs-dismiss="modal">Close</button>
                    </div>
                    <!-- Role Permission modal end-->
                </div>
            </div>
        </div>
    </form>
    <!-- Role Popup Modal end -->
    <script src="{{ URL::asset('front-assets/intlTelInput/js/intlTelInput.js') }}"></script>
    <script>
        var buyerId = {{ $buyer->id }};
        var company_id = {{$buyer->company->id }};

        var loadFile = function (event) {
            var output = document.getElementById('userProfilePic');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function () {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
        var loadCompanyLogo = function (event) {
            var output = document.getElementById('companyLogoPreview');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function () {
                URL.revokeObjectURL(output.src) // free memory
            }
        };

        $("#background_logo").val($(".logobanner_section").css('background-image'));
        var companydetails = @json($buyer->company);
        if (companydetails.background_logo) {
            $('.logobanner_section').css('background-image', companydetails.background_logo);
        }
        if (companydetails.background_colorpicker) {
            $('.logobanner_section').css('background', companydetails.background_colorpicker);
        }
        if (companydetails.logo) {
            $("#companyLogoPreview").attr('src', '{{ asset("storage/") }}' + '/' + companydetails.logo);
        } else {
            $("#companyLogoPreview").attr('src', '{{ URL::asset("front-assets/images/front/logo.png") }}');
        }

        var input = document.querySelector("#company_phone");
        var iti = window.intlTelInput(input, {
            initialCountry: "id",
            separateDialCode: true,
            dropdownContainer: null,
            preferredCountries: ["id"],
            hiddenInput: "c_phone_code"
        });
        var input3 = document.querySelector("#alternative_phone");
        var iti3 = window.intlTelInput(input3, {
            initialCountry: "id",
            separateDialCode: true,
            dropdownContainer: null,
            preferredCountries: ["id"],
            hiddenInput: "a_phone_code"
        });
        var input2 = document.querySelector("#mobile");
        var iti2 = window.intlTelInput(input2, {
            initialCountry: "id",
            separateDialCode: true,
            dropdownContainer: null,
            preferredCountries: ["id"],
            hiddenInput: "phone_code"
        });

        $("#company_phone").focusin(function () {
            let countryData = iti.getSelectedCountryData();
            $('input[name="c_phone_code"]').val(countryData.dialCode);
        });
        $("#alternative_phone").focusin(function () {
            let countryData = iti3.getSelectedCountryData();
            $('input[name="a_phone_code"]').val(countryData.dialCode);
        });
        $("#mobile").focusin(function () {
            let countryData = iti2.getSelectedCountryData();
            $('input[name="phone_code"]').val(countryData.dialCode);
        });


        $(document).ready(function () {

            @php
                $cPhoneCode = $buyer->company->c_phone_code?str_replace('+','',$buyer->company->c_phone_code):62;
                $cCountry = $buyer->company->c_phone_code?strtolower(getRecordsByCondition('countries',['phone_code'=>$cPhoneCode],'iso2',1)):'id';
                $aPhoneCode = $buyer->company->a_phone_code?str_replace('+','',$buyer->company->a_phone_code):62;
                $aCountry = $buyer->company->a_phone_code?strtolower(getRecordsByCondition('countries',['phone_code'=>$aPhoneCode],'iso2',1)):'id';
                $phoneCode = $buyer->phone_code?str_replace('+','',$buyer->phone_code):62;
                $country = $buyer->phone_code?strtolower(getRecordsByCondition('countries',['phone_code'=>$phoneCode],'iso2',1)):'id';
            @endphp


            $('input[name="c_phone_code"]').val({{ $cPhoneCode }});
            iti.setCountry('{{$cCountry}}');
            $('input[name="a_phone_code"]').val({{ $aPhoneCode }});
            iti3.setCountry('{{$aCountry}}');
            $('input[name="phone_code"]').val({{ $phoneCode }});
            iti2.setCountry('{{$country}}');

            /** SmartWizard code start */
            var customFinishBtn = $("<button></button>")
                .text("Finish")
                .addClass("btn btn-info hidden smartWizFinishBtn")
                .on("click", function () {
                    if ($('#buyerProfileEditForm').parsley().validate()) {
                        SnippetCompanyUsersDetails.updateBuyerProfile();
                        $(".smartWizFinishBtn").addClass("disabled");
                        var url = "{{ route('admin.buyer.company.list') }}";
                        setTimeout(function () {
                            window.location.href = url
                        }, 2000);
                    }
                });

            // SmartWizard initialize
            $("#buyer-steps").smartWizard({
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

            $("#buyer-steps").on(
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
                        SnippetCompanyUsersDetails.updateBuyerProfile();
                        if ($("#buyerProfileEditForm").find('.buyerinputErrors').val() != "") {
                            $("#buyerProfileEditForm").find(".buyervalidate:not(:empty)").parent().find("textarea:visible,input[value='']:visible").focus()
                            return false;
                        } else {
                            SnippetCompanyUsersDetails.companyUserListDatatable();
                        }

                    }
                    if (currentStepIndex == 0) {
                        $("#buyerEditForm").on('form:validate', function () {
                            tinymce.triggerSave();
                        });
                        if($(".btn sw-btn-next").click()) { // click on next page
                            SnippetCompanyUsersDetails.updateBuyer();
                            if ($("#buyerEditForm").find('.inputErrors').val() != "") {
                                $("#buyerEditForm").find(".companyvalidate:not(:empty)").parent().find("textarea:visible,input[value='']:visible").focus()
                                return false;
                            } else {
                                $(".removeFile").removeClass("hide");
                                $(".downloadbtn").removeClass("hide");
                                $(".smartWizFinishBtn").removeClass("hidden");
                                return true;
                            }
                        }

                    } else if (currentStepIndex == 1) {
                        return true;
                    } else {
                        return true;
                    }
                }
            );

            /** SmartWizard code End */
        });

        function checkListAll(check) {
            var checkedList = $(check).prop("checked") ? 1 : 0;
            if (checkedList) {
                $(".checkbox-platform").prop("checked", true);
            } else {
                $(".checkbox-platform").prop("checked", false);
            }
            $('.checkbox-nochecked').prop("checked", true);
        }

        /*** Datatable Implementation for the User company***/

        var SnippetCompanyUsersDetails = function () {


                /** Remove Sigle error */
              var  removeSingleError = function () {
                    // remove text field error
                    $('input[type="text"]').on('input', function () {
                        let inputId = $(this).attr('id');
                        $('#' + inputId + 'Error').html('');
                    });
                    // remove email  field error
                    $('input[type="email"]').on('input', function () {
                        let inputId = $(this).attr('id');
                        $('#' + inputId + 'Error').html('');
                    });
                    // remove error of nib file upload
                    $('#nib_file input[type="file"]').on('change', function (evt) {
                        let inputId = $(this).attr('id');
                        $('#'+inputId+'_Error').html('');
                    });
                    // remove error of npwp file upload
                    $('#npwp_file input[type="file"]').on('change', function (evt) {
                        let inputId = $(this).attr('id');
                        $('#'+inputId+'_Error').html('');
                    });
                    // remove error of term condition file upload
                    $('#termsconditions_file input[type="file"]').on('change', function (evt) {
                        let inputId = $(this).attr('id');
                        $('#'+inputId+'_Error').html('');
                    });

                    // remove text field error on buyer Admin
                    $('#buyerProfileEditForm input[type="text"]').on('input', function () {
                        let inputId = $(this).attr('id');
                        $('#' + inputId + '-error').html('');
                    });

                    $('#buyerUserForm select').on('change', function () {
                        let inputId = $(this).attr('id');
                        $('#' + inputId + 'Error').html('');
                    });
                },
                /** Remove All errors */
                removeAllErrors = function () {
                    $('#buyerUserForm span.text-danger').html('');
                },
                /** On close button remove All errors */
                userDetailFormClose = function () {

                    $('#companyRoleModal').on('hidden.bs.modal', function () {
                        removeAllErrors();
                        $('#buyerUserForm').trigger('reset');
                        $("#buyerUserForm option:selected").removeAttr("selected");
                    });

                },
                /** Delete user from company users */
                deleteUser = function () {
                    $(document).on('click', '.deleteUser', function (e) {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        var id = $(this).attr('data-id');
                        var ownerUserId = $(this).attr('data-owner');

                        swal({
                            title: "{{ __('admin.delete_sure_alert') }}",
                            text: "{{ __('admin.supplier_delete_text') }}",
                            icon: "/assets/images/bin.png",
                            buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.delete') }}'],
                            dangerMode: true,
                        })
                            .then((willDelete) => {
                                if (willDelete) {
                                    $.ajax({
                                        url: "{{ route('admin.buyer.company.companyUserList.delete') }}",
                                        type: 'POST',
                                        data: {id: id, ownerUserId: ownerUserId},
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        success: function (data) {
                                            if (data.success) {
                                                SnippetApp.toast.success("{{ __('admin.success') }}", data.message);

                                                setTimeout(function () {
                                                    SnippetCompanyUsersDetails.companyUserListDatatable();
                                                }, 2000);
                                            } else {
                                                SnippetApp.toast.success("{{ __('admin.warning') }}", "{{ __('admin.something_went_wrong') }}");
                                            }
                                        },
                                        error: function () {
                                            console.log('error');
                                        }
                                    });
                                }
                            });

                    });
                },
                /** NPWP Field **/
                npwpField =function () {
                    $("input[name='npwp']").keyup(function () {
                        let numbers = $(this).val().replace(/\D/g, '');
                        if (numbers.length > 15) {
                            $(this).val($(this).val().slice(0, 20));
                            return;
                        }
                        let char = {0: '', 2: '.', 5: '.', 8: '.', 9: '-', 12: '.'};
                        let value = '';
                        for (var i = 0; i < numbers.length; i++) {
                            value += (char[i] || '') + numbers[i];
                        }
                        $(this).val(value);
                    });
                },
                /** Remove files **/
                removeFile = function() {
                    $(document).on("click", ".removeFile", function (e) {
                        //e.preventDefault();
                        let name = $(this).attr("data-name");
                        let data = {
                            fileName: name,
                            id: $(this).attr("data-id"),
                            _token: $('meta[name="csrf-token"]').attr("content"),
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
                                    url: "{{ route('admin.buyer.company.list.companyFileDelete') }}",
                                    data: data,
                                    type: "POST",
                                    success: function (successData) {
                                        $("#file-" + name).html('');
                                    },
                                    error: function () {
                                        console.log("error");
                                    },
                                });
                            }
                        });
                    });
                },

                /** Company Consumption Trash Icon click  **/
                CompanyConsumptionTrashIcon = function() {
                    $(document).on('click', '.trashicon', function () {
                        $(this).closest('.interested_in_html').remove();
                    });
                },
                /**  Add company consumtion block clone **/
                AddCompanyConsumptionBlock = function() {
                    $('#addInterestedBlock').click(function (e) {
                        var index = parseInt($(".interested_in_html select[name='ProductCategory[]']").length - 1) + 1;
                        var $clone = $('#mainInterestedInHtml .interested_in_html').first().clone(true).removeClass('clone_0').addClass('removewhileleft clone_'+index);
                        $clone.find(':selected').removeAttr('selected');
                        $clone.find("input").val("").end();
                        $clone.find(".trashicon").removeClass('hidden');
                        // var indexnew = $clone.find(".productcategory").attr('data-cloneid') + 1;
                        $clone.find(".productcategory").attr('data-cloneid', index);
                        $clone.find(".annualconsumption").attr('data-cloneid', index);
                        $clone.find(".unit").attr('data-cloneid', index);
                        var cloneId = $(".interested_in_html select[name='ProductCategory[]']").last().attr("data-cloneid");

                        if($(".interested_in_html.clone_"+cloneId+" select[name='ProductCategory[]']  ").val() != ""  && $(".interested_in_html.clone_"+cloneId+" select[name='ProductUnit[]']").val() !=""){
                            $clone.appendTo($("#companyYearlyContainer"));
                            $("html, body").animate({scrollTop: $(document).height()}, 1000);
                        }else{
                            if($(".interested_in_html.clone_"+cloneId+" select[name='ProductCategory[]']  ").val() == ""){
                                $(".interested_in_html.clone_"+cloneId+" select[name='ProductCategory[]']").focus();
                            }else{
                                $(".interested_in_html.clone_"+cloneId+" select[name='ProductUnit[]'] ").focus();
                            }


                        }

                    });
                }


            return {
                init: function () {
                        //companyUserListDatatable(),
                        userDetailFormClose(),
                        deleteUser(),
                        removeSingleError(),
                        npwpField(),
                        removeFile(),
                        CompanyConsumptionTrashIcon(),
                        AddCompanyConsumptionBlock()

                },

                usersDatatable : function () {
                    var userTable = $('.dataTable').DataTable({
                        serverSide: !0,
                        paginate: !0,
                        processing: !0,
                        lengthMenu: [
                            [10, 25, 50],
                            [10, 25, 50],
                        ],
                        footer: !1,
                        ajax: {
                            url: "{{route('admin.buyer.company.companyUserList')}}",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data: {company_id: company_id, buyerId: buyerId},
                            method: "POST",
                        },
                        columns: [
                            {data: "buyer_name", title: "{{__('admin.name')}}"},
                            {data: "buyer_email", title: "{{ __('admin.contact_person_email') }}"},
                            {data: "buyer_phone", title: "{{ __('admin.mobile')}}"},
                            {data: "buyer_designation", title: "{{ __('admin.designation') }}"},
                            {data: "buyer_role", title: "{{ __('profile.role') }}"},
                            {data: "buyer_joining", title: "{{ __('admin.joining_date') }}"},
                            {data: "actions", title: "{{ __('admin.actions') }}", class: "text-nowrap text-end"}
                        ],
                        aoColumnDefs: [
                            {"bSortable": true, "aTargets": [0, 3, 4]},
                            {"bSortable": false, "aTargets": [1, 2, 3, 5, 6]}
                        ],
                        language: {
                            search: "{{__('admin.search')}}",
                            loadingRecords: "{{__('admin.please_wait_loading')}}",
                            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span> {{__('admin.loading')}}..</span> '
                        },
                        order: [[0, 'desc']],
                        "bDestroy": true

                    });
                    return userTable;
                },
                companyUserListDatatable : function () {
                    SnippetCompanyUsersDetails.usersDatatable().draw();
                },


                /**
                 * when checkbox checked or unchecked then show swal message for conformation
                 * @param $this
                 * @param id
                 * @param chargeName
                 */
                changeValueEnableDisable: function ($this, id, chargeName) {
                    var messageText = ($this.checked == true) ? "{{ __('admin.change_editable_value_buyer_message_ckecked') }}" : "{{ __('admin.change_editable_value_buyer_message_unchecked') }}";
                    swal({
                        text: chargeName + ' ' + messageText + ' {{$buyer->company->name}}',
                        icon: "/assets/images/info.png",
                        buttons: ["{{ __('admin.no') }}", "{{ __('admin.yes') }}"],
                        closeOnClickOutside: false
                    }).then((willCheck) => {
                        if (willCheck) {
                            $("#platform_charges_" + id).prop("checked", $this.checked == true ? true : false);
                        } else {
                            $("#platform_charges_" + id).prop("checked", $this.checked == true ? false : true);
                        }
                        SnippetCompanyUsersDetails.commanAjaxChangeValue($this.checked, id, '{{ $buyer->company->id }}');
                    });
                },
                /**
                 * common function for update flat fees
                 * @param check
                 * @param id
                 * @param company_id
                 */
                commanAjaxChangeValue: function (check, id, company_id) {
                    $.ajax({
                        url: "{{ route('admin.buyer.company.companyFlatFee.update') }}",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {charge_id: id, check: check, company_id: company_id},
                        type: 'POST',
                        success: function (response) {
                            if (response.success == true && check == false) {
                                $('#type_' + id).prop('disabled', true).val(response.data.type).change();
                                $('#chargeValue_' + id).prop('disabled', true).val(response.data.charges_value);
                            } else {
                                $('#type_' + id).prop('disabled', false).val(response.data.type).change();
                                $('#chargeValue_' + id).prop('disabled', false).val(response.data.charges_value);
                            }
                        },
                    });
                },
                /**
                 * when change type then set value 0
                 * @param $this
                 * @param id
                 * @constructor
                 */
                ChangeValue: function ($this, id) {
                    $('#chargeValue_' + id).val('0');
                },
                /**  Save User details **/
                userDetailSave: function () {
                    $(document).on('click', '#saveUserData', function (e) {
                        removeAllErrors();
                        $("#saveUserData").prop('disabled', true);
                        let formData = $('#buyerUserForm').serializeArray();
                        $.ajax({
                            url: "{{ route('admin.buyer.company.companyUserList.update') }}",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data: formData,
                            type: 'POST',
                            success: function (data) {
                                if (data.success) {
                                    resetToastPosition();
                                    SnippetApp.toast.success("{{ __('admin.success') }}", data.message);
                                    $('#companyRoleModal').modal('hide');
                                    setTimeout(function () {
                                        SnippetCompanyUsersDetails.companyUserListDatatable();
                                    }, 2000);
                                    $("#saveUserData").prop('disabled', false);
                                    return false;
                                }
                            },
                            error: function (data) {
                                if (data.status === 422) {
                                    var errors = $.parseJSON(data.responseText);
                                    $.each(errors, function (key, value) {

                                        if ($.isPlainObject(value)) {
                                            $.each(value, function (key, value) {
                                                $('#' + key + 'Error').html(value);
                                            });
                                        }
                                    });
                                    $("#saveUserData").prop('disabled', false);
                                } else {
                                    resetToastPosition();
                                    SnippetApp.toast.success("{{ __('admin.warning') }}", "{{ __('admin.something_went_wrong') }}");
                                }
                            }
                        });

                    });
                },

                /** check files size in client side and display **/
                showFile : function(input) {
                    let file = input.files[0];
                    let size = Math.round((file.size / 1024))
                    if (size > 3000) {
                        swal({
                            icon: 'error',
                            title: '',
                            text: '{{ __('profile.file_size_under_3mb') }}',
                        })
                    } else {
                        let fileName = file.name;
                        let allowed_extensions = new Array("jpg", "png", "gif", "jpeg", "pdf");
                        let file_extension = fileName.split('.').pop();
                        let file_name_without_extension = fileName.replace(/\.[^/.]+$/, '');
                        let text = '{{ __('profile.plz_upload_file') }}';
                        let company_id = '{{ $buyer->company->id }}';
                        let image_path = '{{URL::asset("assets/icons/times-circle copy.png")}}';

                        for (let i = 0; i < allowed_extensions.length; i++) {
                            if (allowed_extensions[i] == file_extension) {
                                valid = true;
                                let download_function = "'" + company_id + "', " + "'" + input.name + "', " + "'" + fileName + "'";
                                if (file_name_without_extension.length >= 10) {
                                    fileName = file_name_without_extension.substring(0, 10) + '....' + file_extension;
                                }
                                $('#file-' + input.name).html('');
                                $('#file-' + input.name).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.name + 'Download" style="text-decoration: none">' + fileName + '</a></span><span class="removeFile hidden" data-id="' + company_id + '" data-name="' + input.name + '"><a href="javascript:void(0)" title="{{ __('profile.remove_file') }}" style="text-decoration: none;"><img src="' + image_path + '" alt="CLose button" class="ms-2"></a></span><span class="ms-2"><a class="' + input.name + ' downloadbtn hidden" href="javascript:void(0);" title="{{ __('profile.download_file') }}" onclick="SnippetCompanyUsersDetails.downloadimg(' + download_function + ')" style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a></span>');
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
                },

                /**  Download file **/
                downloadimg : function(id, fieldName, name) {
                    var data = {
                        id: id,
                        fieldName: fieldName
                    }
                    $.ajax({
                        url: "{{ route('admin.buyer.company.list.downloadBuyerImageAdmin') }}",
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
                },
                /** Update company details **/
                updateBuyer : function(){
                    $('#buyerEditForm .inputErrors').val('');
                    var formData = new FormData($("#buyerEditForm")[0]);
                    formData.append('company_id', company_id);
                    formData.append('addressbuyer', tinyMCE.get('addressbuyer').getContent({format: 'text'}));
                    $.ajax({
                        url: "{{ route('admin.buyer.company.list.update.company') }}",
                        data: formData,
                        type: "POST",
                        contentType: false,
                        processData: false,
                        async:false,
                        success: function (successData) {
                            if (successData) {
                                resetToastPosition();
                                SnippetApp.toast.success("{{ __('admin.success') }}", "{{ __('admin.buyer_updated_successfully') }}");
                            }
                        },
                        error: function (successData) {
                            if (successData.status === 422) {
                                var errors = $.parseJSON(successData.responseText);
                                var errorsArr = [];
                                $('#buyerEditForm .companyvalidate').html('');
                                $.each(errors, function (key, value) {
                                    if ($.isPlainObject(value)) {
                                        $.each(value, function (key, value) {
                                            $('#' + key + 'Error').html(value);
                                            errorsArr.push(key);
                                        });
                                    }
                                });
                                $('#buyerEditForm .inputErrors').val(errorsArr);
                            } else {
                                resetToastPosition();
                                SnippetApp.toast.success("{{ __('admin.warning') }}", "{{ __('admin.something_went_wrong') }}");
                            }


                        }
                    });
                },

                /** Update company Admin details **/
                updateBuyerProfile : function() {
                $('#buyerProfileEditForm .buyerinputErrors').val('');
                var formData = new FormData($("#buyerProfileEditForm")[0]);
                var cropImageSrc = $(".user_info_photo").find('img').attr('src');
                formData.append('cropImageSrc', cropImageSrc);
                formData.append('id', buyerId);
                $.ajax({
                    url: "{{ route('admin.buyer.company.list.update.company') }}",
                    data: formData,
                    type: "POST",
                    contentType: false,
                    processData: false,
                    async:false,
                    success: function (successData) {
                        if (successData) {
                            resetToastPosition();
                            $.toast({
                                heading: "{{ __('admin.success') }}",
                                text: "{{ __('admin.buyer_updated_successfully') }}",
                                showHideTransition: "slide",
                                icon: "success",
                                loaderBg: "#f96868",
                                position: "top-right",
                            });
                        }
                    },
                    error: function (successData) {
                        if (successData.status === 422) {
                            var errors = $.parseJSON(successData.responseText);
                            var errorsArr = [];
                            $('#buyerProfileEditForm .buyervalidate').html('');
                            $.each(errors, function (key, value) {
                                if ($.isPlainObject(value)) {
                                    $.each(value, function (key, value) {
                                        $('#' + key + '-error').html(value);
                                        errorsArr.push(key);
                                    });
                                }
                            });
                            $('#buyerProfileEditForm .buyerinputErrors').val(errorsArr);
                        } else {
                            resetToastPosition();
                            SnippetApp.toast.success("{{ __('admin.warning') }}", "{{ __('admin.something_went_wrong') }}");
                        }


                    }
                });

            },
                /** User edit Popup **/
                userEditPopUp : function () {
                    $(document).on('click', '.companyRolePopup', function (e) { // company User Popup
                        e.preventDefault();
                        var id = $(this).attr('data-id');
                        var ownerUserId = $(this).attr('data-owner');
                        if (id) {
                            $.ajax({
                                url: "{{ route('admin.buyer.company.companyUserList.edit') }}",
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                data: {id: id, ownerUserId: ownerUserId},
                                type: 'POST',
                                success: function (successData) {
                                    $('#companyRoleModal').find('#buyerCompanyName').html('<img height="24px" class="pe-2" src="{{URL::asset('assets/icons/order_detail_title.png')}}" alt="User Details">' + successData.userDetails.companyName);
                                    $('#companyRoleModal').find('#firstName').val(successData.userDetails.firstname);
                                    $('#companyRoleModal').find('#lastName').val(successData.userDetails.lastname);
                                    $('#companyRoleModal').find('#email').val(successData.userDetails.email);
                                    $('#companyRoleModal').find('#mobile').val(successData.userDetails.mobile);
                                    $('#companyRoleModal').find('#designation').val(successData.userDetails.designation).change();
                                    $('#companyRoleModal').find('#department').val(successData.userDetails.department).change();
                                    $('#companyRoleModal').find("div#user_role_div select.roleClass option").each(function () {
                                        if ($(this).val() == successData.userDetails.role) {
                                            $(this).attr("selected", "selected");
                                        }
                                    });
                                    $('#companyRoleModal').find('#companyUserId').val(successData.userDetails.companyUserId);
                                    $('#companyRoleModal').find('#defaultCompanyId').val(successData.userDetails.defaultCompanyId);
                                    $('#companyRoleModal').find('#branch').val(successData.userDetails.branches);
                                    $('#companyRoleModal').modal('show');
                                },
                                error: function () {
                                    console.log('error');
                                }
                            });
                        }
                    });
                },

            }
        }(1);

        /** Snippet for Crop image in company Admin  **/
        var SnippetBuyerProfileCropImage = function () {

            var tempProfileFilename, rawProfileImg, imageProfileId;

            /** Upload profile Crop image **/
            var uploadProfileCrop = $('#profile_image_preview').croppie({
                enableOrientation: true,
                enableZoom: true,
                showZoomer: true,
                enableExif: true,
                viewport: {
                    width: 250,
                    height: 250,
                    // type: 'circle'
                },
                boundary: {
                    width: 260,
                    height: 260
                }
            });

            /** Crop image for Buyer profile**/
            var UploadProfileImageModal = function () {
                $('#UploadProfileImageModal').on('shown.bs.modal', function () {
                    uploadProfileCrop.croppie('bind', {
                        url: rawProfileImg
                    }).then(function () {
                        // console.log('jQuery bind complete');
                    });
                });
            },

            /** crop buyer profle Picture **/
            cropBuyerProflePicture = function() {
                $('.crop-profile-picture').on('click', function (ev) {
                    uploadProfileCrop.croppie('result', {
                        type: 'canvas',
                        size: 'viewport',
                        quality: 1
                    }).then(function (img) {
                        $("#userProfilePic").closest('div.ratio.ratio-1x1').show();
                        $("#userProfilePic").attr('src', img);
                        $('#UploadProfileImageModal').modal('hide');
                    });
                });
            },

            /** Admin user Image button changes **/
            companyAdminImage = function() {
                $('#user_pic').on('change', function () {
                    imageProfileId = $(this).data('id');
                    tempProfileFilename = $(this).val();
                    readProfileFile(this);
                });
            },
            readProfileFile = function(input) {
                 if (input.files && input.files[0]) {
                     var reader = new FileReader();

                     var file = input.files[0];
                     var fileType = file.type.split('/')[0];
                     var fileExtension = file.type.split('/')[1];
                     var maxFileSize = 3000; //3mb
                     var fileSize = Math.round((file.size / 1024));

                     if (fileSize > maxFileSize) {
                         $("#user_pic").val("");
                         swal({
                             icon: 'error',
                             title: '',
                             text: '{{__('admin.file_size_under_3mb')}}',
                         });
                         return false;
                     }


                     if (fileType == 'image' && (fileExtension == 'png' || fileExtension == 'jpg' || fileExtension == 'jpeg')) {
                         console.log('in image');
                         reader.onload = function (e) {
                             $('#UploadProfileImageModal').modal('show');
                             rawProfileImg = e.target.result;
                         }
                         reader.readAsDataURL(input.files[0]);
                     } else {
                         $("#user_pic").val("");
                         swal({
                             icon: 'front-assets/images/format_not_support.png',
                             title: "Format not Supported!",
                             showClass: {
                                 popup: 'animate__animated animate__fadeInDown'
                             },
                             hideClass: {
                                 popup: 'animate__animated animate__fadeOutUp'
                             }
                         });
                     }
                 } else {
                     //SnippetApp.swal.message("Sorry - you're browser doesn't support the FileReader API");
                 }
             }

            return {
                init: function () {
                        UploadProfileImageModal(),
                        cropBuyerProflePicture(),
                        companyAdminImage()
                }
            }
        }(1);

        jQuery(document).ready(function () {
            SnippetCompanyUsersDetails.init();
            SnippetCompanyUsersDetails.userDetailSave();
            SnippetCompanyUsersDetails.userEditPopUp();
            SnippetBuyerProfileCropImage.init();

        });


    </script>

@endsection
