@extends('admin/adminLayout')
@push('bottom_head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .suplier-succ {
            color: green;font-size: 20px;
        }
        #buyer-steps ul {
            margin: 0 auto;
            /* margin-bottom: 40px; */
        }
        .contact_step a.nav-link {
            position: relative;
        }
        .contact_step .step_img {
            position: absolute;top: 0;
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
            background-image: url(../../assets/images/icon_contact_info.png);
            background-repeat: no-repeat;
            background-position: center center;
        }
        .contact_step .icon .pen_icon.buyer {
            background-image: url(../../assets/images/icon_buyer_W.png);
            background-repeat: no-repeat;
            background-position: center center;
        }
        .logobanner_section {
            background: url(../../front-assets/images/logo_background.png) center center;
            height: 200px;
            background-position: cover;
        }
        .logobanner_section img {
            max-height: 120px;
        }
        .iti--separate-dial-code .iti__selected-flag{font-size: 12px;  height: 100% !important;}
        .sw-theme-dots > .nav .nav-link::after {left: 10px !important;}

    </style>
@endpush

@section('content')
    <link href="{{ URL::asset('front-assets/js/front/crop/css/style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/js/front/crop/css/style-example.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/js/front/crop/css/jquery.Jcrop.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front-assets/css/front/croppie.min.css') }}" rel='stylesheet' />
    <script type="text/javascript" src="{{ URL::asset('front-assets/js/front/crop/scripts/jquery.Jcrop.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('front-assets/js/front/crop/scripts/jquery.SimpleCropper.js') }}"></script>
    <script src='{{ asset("front-assets/js/front/croppie.js")}}'></script>
    <script src="{{ URL::asset('front-assets/intlTelInput/js/intlTelInput.js') }}"></script>
    @php
        $checkNotAdminRole = (Auth::user()->role_id == \App\Models\Role::ADMIN)? 0 : 1;
    @endphp
    <div class="row">
        <div class="col-md-12 d-flex align-items-center mb-3">
            <h1 class="mb-0 h3">{{ __('admin.buyer') }}</h1>
            <a href="{{ route('buyer-list') }}" class="mb-2 backurl ms-auto btn-close"></a>
        </div>

        <div class="col-12">
            <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">{{ __('admin.edit_buyer') }}</button>
                </li>
            </ul>
            <div class="tab-content pt-3 pb-0 px-0" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div id="buyer-steps" class="sw sw-theme-dots sw-justified">
                        <ul class="nav col-md-4 contact_step">
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
                                    {{ __('admin.buyer') }}
                                    <div class="icon">
                                        <i class="pen_icon buyer"></i>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content pt-3 pb-0 ">

                            <div id="step-1" class="tab-pane p-0" role="tabpanel" style="display: block;">
                                <form class="" id="buyerEditForm" method="POST" enctype="multipart/form-data"
                                      action="{{ route('buyer-update') }}" data-parsley-validate>
                                        @csrf
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <div class="card">
                                                <div class="card-header d-flex align-items-center">
                                                    <h5 class="mb-0"><img src="{{URL::asset('assets/icons/icon_company.png')}}" alt="Company" class="pe-2" height="20px">
                                                        <span>{{ __('admin.company_details') }}</span></h5>
                                                </div>
                                                <div class="card-body p-3 pb-1">
                                                    <div class="row">
                                                        <div class="col-md-4 col-lg-4">
                                                            <div class="mb-3" style="display: grid; place-items: center;">
                                                                <div class="w-100" style="max-width: 350px">
                                                                    @php
                                                                        $imgpath = URL::asset("front-assets/images/front/logo.png");
                                                                        if ($buyer->company_logo) {
                                                                            $imgpath = asset("storage/") . '/'.$buyer->company_logo ;
                                                                        }
                                                                    @endphp
                                                                    <input type="hidden" name="background_logo" id="background_logo"
                                                                           class="inputfile inputfile-3 d-none" />
                                                                    <div class="logobanner_section d-flex align-items-center justify-content-center w-100 p-3 container5">

                                                                        <img src="{{$imgpath}}" alt="company logo" class="mw-100" id="companyLogoPreview" >

                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="text-center">
                                                                <div class="">
                                                                    <span class=""><input type="file" name="logo" class="form-control" id="logo" accept=".jpg,.png,.jpeg"
                                                                                          onchange="loadCompanyLogo(event)" hidden><label class="upload_btn" for="logo">{{__('admin.upload_log')}}</label></span>

                                                                </div>
                                                                <div class="text-muted mt-1">
                                                                    <small>{{__('profile.upload_jpg_png_text') }}</small>

                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-8 col-lg-8">
                                                            <div class="row g-3">
                                                                <div class="col-md-6 ">
                                                                    <label for="name" class="form-label">{{ __('admin.company_name') }} <span class="text-danger">*</span></label>
                                                                    <input type="hidden" name="company_id" id="company_id"
                                                                           value="{{ $buyer->company_id }}">
                                                                    <input type="hidden" name="user_id" id="user_id"
                                                                           value="{{ $buyer->id }}">
                                                                    <input type="text" class="form-control" id="name" name="name" value="{{ $buyer->company_name }}" required>
                                                                </div>
                                                                <div class="col-md-6 ">
                                                                    <label for="website" class="form-label">{{ __('admin.company_website') }}</label>
                                                                    <input type="text" class="form-control" id="website" name="website" value="{{ $buyer->web_site }}">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="email" class="form-label">{{ __('profile.company_email') }} @if($checkNotAdminRole) <span class="text-danger">*</span> @endif </label>
                                                                    <input type="email" class="form-control" id="company_email" name="company_email" value="{{ $buyer->company_email }}" @if($checkNotAdminRole) required @endif data-parsley-email=""
                                                                           data-parsley-notequalto="#alternate_email" data-parsley-notequalto-message="This email must differ from the alternate email!">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="email" class="form-label">{{ __('profile.alternative_email') }}</label>
                                                                    <input type="email" class="form-control" id="alternative_email" name="alternative_email" value="{{ $buyer->compy_alt_email }}">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="mobile" class="form-label">{{ __('profile.company_phone') }} @if($checkNotAdminRole) <span class="text-danger">*</span> @endif </label>
                                                                    <input type="text" class="form-control" id="company_phone" name="company_phone" value="{{ $buyer->company_phone }}"
                                                                           @if($checkNotAdminRole) required @endif data-parsley-mobile="" data-parsley-type="digits"
                                                                           data-parsley-length="[9, 16]" data-parsley-length-message="It should be between 9 and 16 digit.">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">{{ __('profile.alternative_phone') }}</label>
                                                                    <input type="text" class="form-control" name="alternative_phone" id="alternative_phone" value="{{ $buyer->alternative_phone }}" data-parsley-mobile="" data-parsley-type="digits"
                                                                           data-parsley-length="[9, 16]" data-parsley-length-message="It should be between 9 and 16 digit.">
                                                                </div>
                                                                <div class="col-12 mb-1 textbox_error">
                                                                    <label for="addressbuyer" class="form-label">{{ __('admin.company_address') }} </label>
                                                                    <textarea name="addressbuyer" class="form-control newtextarea1" id="addressbuyer" cols="30" rows="3"
                                                                              >{{ $buyer->address }}</textarea>
                                                                   </div>
                                                                <div class="col-md-6 mb-0">
                                                                    <label for="nib" class="form-label">{{ __('profile.registration_nib') }} @if($checkNotAdminRole) <span class="text-danger">*</span> @endif </label>

                                                                    <input type="text" class="form-control" id="registration_nib" name="registration_nib" value="{{ $buyer->registrantion_NIB }}" @if($checkNotAdminRole) required @endif data-parsley-type="digits"
                                                                           data-parsley-length="[13, 13]" data-parsley-length-message="Value should be 13 digits only.">

                                                                </div>
                                                                <div class="col-md-6 mb-0">
                                                                    <label for="" class="form-label">{{ __('profile.nib_file') }} </label>
                                                                    <div class="d-flex align-items-center">
                                                                                            <span class="">
                                                                                                <input type="file" name="nib_file" class="form-control" id="nib_file" accept=".jpg,.png,.gif,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                                                                <label class="upload_btn" for="nib_file">{{ __('profile.browse') }}</label></span>
                                                                        <div id="file-nib_file">
                                                                        <input type="hidden" class="form-control" id="old_nib_file" name="old_nib_file" value="{{ $buyer->nib_file }}">
                                                                        @if ($buyer->nib_file)
                                                                            @php
                                                                                $nibFileTitle = Str::substr($buyer->nib_file, stripos($buyer->nib_file, "nib_file_") + 9);
                                                                                $extension_nib_file = getFileExtension($nibFileTitle);
                                                                                $nib_file_filename = getFileName($nibFileTitle);
                                                                                if(strlen($nib_file_filename) > 10){
                                                                                    $nib_file_name = substr($nib_file_filename,0,10).'...'.$extension_nib_file;
                                                                                } else {
                                                                                    $nib_file_name = $nib_file_filename.$extension_nib_file;
                                                                                }
                                                                            @endphp
                                                                                <input type="hidden" class="form-control" id="oldnib_file" name="oldnib_file" value="{{ $buyer->nib_file }}">
                                                                                <span class="ms-2">
                                                                                <a href="javascript:void(0);" id="nibFileDownload" onclick="downloadimg('{{ $buyer->id }}', 'nib_file', '{{ $nibFileTitle }}')"  title="{{ $nibFileTitle }}" style="text-decoration: none;"> {{ $nib_file_name }}</a>
                                                                                </span>
                                                                                <span class="removeFile" id="nibFile" data-id="{{ $buyer->company_id }}" file-path="{{ $buyer->nib_file }}" data-name="nib_file">
                                                                                <a href="javascript:void(0);" title="{{ __('profile.remove_file') }}"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-0"></a>
                                                                                </span>
                                                                                <span class="ms-0">
                                                                                <a class="nib_file" href="javascript:void(0);" title="{{ __('profile.download_file') }}" onclick="downloadimg('{{ $buyer->id }}', 'nib_file', '{{ $nibFileTitle }}')" style="text-decoration: none;"><i class="fa fa-cloud-download" ></i></a>
                                                                                </span>
                                                                        @endif

                                                                        <div id="file-nib_file">
                                                                            <input type="hidden" class="form-control" id="old_nib_file" name="old_nib_file" value="">
                                                                        </div>
                                                                    </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="npwp" class="form-label">{{ __('profile.npwp') }} @if($checkNotAdminRole) <span class="text-danger">*</span> @endif </label>
                                                                    <input type="text" class="form-control" id="npwp" name="npwp" value="{{ $buyer->npwp }}" placeholder="11.222.333.4-555.666" @if($checkNotAdminRole) required @endif
                                                                           data-parsley-pattern="^(\d{2})*[.]{1}(\d{3})*[.]{1}(\d{3})*[.]{1}(\d{1})*[-]{1}(\d{3})*[.]{1}(\d{3})*$" data-parsley-minlength="20" data-parsley-maxlength="20">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="name" class="form-label">{{ __('profile.npwp_file') }}</label>
                                                                    <div class="d-flex align-items-center">
                                                                                            <span class=""><input type="file" name="npwp_file" class="form-control" id="npwp_file" accept=".jpg,.png,.gif,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                                                                <label class="upload_btn" for="npwp_file">{{ __('profile.browse') }}</label></span>
                                                                        <div id="file-npwp_file">
                                                                            <input type="hidden" class="form-control" id="old_npwp_file" name="old_npwp_file" value="{{ $buyer->npwp_file }}">
                                                                            @if ($buyer->npwp_file)
                                                                                @php
                                                                                    $npwpFileTitle = Str::substr($buyer->npwp_file, stripos($buyer->npwp_file, "npwp_file_") + 10);
                                                                                    $extension_npwp_file = getFileExtension($npwpFileTitle);
                                                                                    $npwp_file_filename = getFileName($npwpFileTitle);
                                                                                    if(strlen($npwp_file_filename) > 10){
                                                                                        $npwp_file_name = substr($npwp_file_filename,0,10).'...'.$extension_npwp_file;
                                                                                    } else {
                                                                                        $npwp_file_name = $npwp_file_filename.$extension_npwp_file;
                                                                                    }
                                                                                @endphp
                                                                                <input type="hidden" class="form-control" id="oldnpwp_file" name="oldnpwp_file" value="{{ $buyer->npwp_file }}">
                                                                                <span class="ms-2">
                                                                    <a href="javascript:void(0);" id="npwpFileDownload" onclick="downloadimg('{{ $buyer->id }}', 'npwp_file', '{{ $npwpFileTitle }}')"  title="{{ $npwpFileTitle }}" style="text-decoration: none;"> {{ $npwp_file_name }}</a>
                                                                </span>
                                                                                <span class="removeFile" id="npwpFile" data-id="{{ $buyer->company_id }}" file-path="{{ $buyer->npwp_file }}" data-name="npwp_file">
                                                                    <a href="javascript:void(0);" title="{{ __('profile.remove_file') }}"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-0"></a>
                                                                </span>
                                                                                <span class="ms-0">
                                                                    <a class="npwp_file" href="javascript:void(0);" title="{{ __('profile.download_file') }}" onclick="downloadimg('{{ $buyer->id }}', 'npwp_file', '{{ $npwpFileTitle }}')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a>
                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="name" class="form-label">{{ __('admin.commercial_terms') }}</label>
                                                                    <div class="d-flex align-items-center">
                                                                        <span class="">
                                                                            <input type="file" name="termsconditions_file" class="form-control" id="termsconditions_file" accept=".jpg,.png,.gif,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                                            <label class="upload_btn" for="termsconditions_file">{{ __('profile.browse') }}</label>
                                                                        </span>
                                                                            <div id="file-termsconditions_file">
                                                                                <input type="hidden" class="form-control" id="old_termsconditions_file" name="old_termsconditions_file" value="{{ $buyer->termsconditions_file }}">
                                                                                @if ($buyer->termsconditions_file)
                                                                                    @php
                                                                                        $termsconditionsFileTitle = Str::substr($buyer->termsconditions_file, stripos($buyer->termsconditions_file, "termsconditions_file_") + 21);
                                                                                        $extension_termsconditions_file = getFileExtension($termsconditionsFileTitle);
                                                                                        $termsconditions_file_filename = getFileName($termsconditionsFileTitle);
                                                                                        if(strlen($termsconditions_file_filename) > 10){
                                                                                            $termsconditions_file_name = substr($termsconditions_file_filename,0,10).'...'.$extension_termsconditions_file;
                                                                                        } else {
                                                                                            $termsconditions_file_name = $termsconditions_file_filename.$extension_termsconditions_file;
                                                                                        }
                                                                                    @endphp
                                                                                    <input type="hidden" class="form-control" id="oldtermsconditions_file" name="oldtermsconditions_file" value="{{ $buyer->termsconditions_file }}">
                                                                                    <span class="ms-2">
                                                                                        <a href="javascript:void(0);" id="termsconditionsFileDownload" onclick="downloadimg('{{ $buyer->id }}', 'termsconditions_file', '{{ $termsconditionsFileTitle }}')"  title="{{ $termsconditionsFileTitle }}" style="text-decoration: none;"> {{ $termsconditions_file_name }}</a>
                                                                                    </span>
                                                                                    <span class="removeFile" id="npwpFile" data-id="{{ $buyer->company_id }}" file-path="{{ $buyer->termsconditions_file }}" data-name="termsconditions_file">
                                                                                        <a href="javascript:void(0);" title="{{ __('profile.remove_file') }}"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-0"></a>
                                                                                    </span>
                                                                                    <span class="ms-0">
                                                                                        <a class="termsconditions_file" href="javascript:void(0);" title="{{ __('profile.download_file') }}" onclick="downloadimg('{{ $buyer->id }}', 'termsconditions_file', '{{ $termsconditionsFileTitle }}')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a>
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mb-2">
                                            <div class="card">
                                                <div class="card-header d-flex align-items-center">
                                                    <h5 class="mb-0"><img src="{{URL::asset('assets/icons/boxes.png')}}" alt="Company" class="pe-2" height="20px">
                                                        <span> {{ __('profile.Company_Yearly_Consumption_Detail') }}
                                                                                    <a href="#"  id="addInterestedBlock"  data-bs-toggle="modal" data-bs-target="#exampleModal" title="Add" class="text-decoration-none text-dark ps-1"><svg style="width: 18px; height:18px; cursor: pointer;" viewBox="0 0 24 24">
                                                                                            <path fill="currentColor" d="M17,13H13V17H11V13H7V11H11V7H13V11H17M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z">
                                                                                            </path>
                                                                                        </svg></a></span></h5>
                                                </div>
                                                <div class="card-body p-3 pb-1" id="mainInterestedInHtml">
                                                    @if (count($company_consumptions))
                                                        @php $k = 0 ; @endphp
                                                        @foreach ($company_consumptions as $key => $company_consumption)
                                                    <div class="row align-items-center interested_in_html">
                                                        <div class="col-md-11 d-flex">
                                                            <div class="col-md-4 mb-2">
                                                                <label class="form-label">{{ __('profile.Category') }}</label>
                                                                <select data-cloneid="0" class="form-select addvalidation productcategory" name="ProductCategory[]">
                                                                    <option value="">{{__('profile.select_category')}}</option>
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
                                                                <label class="form-label">{{ __('profile.Annual_Consumption') }}</label>
                                                                <input data-cloneid="{{$k}}" type="number" class="form-control addvalidation annualconsumption" name="ProductAnnualConsumption[]"
                                                                       value="{{ $company_consumption->annual_consumption }}"placeholder="0" data-parsley-type="number" min="0">
                                                                <span class="invalid-feedback d-block"></span>
                                                            </div>
                                                            <div class="col-md-4 mb-2 ps-3">
                                                                <label class="form-label">{{ __('profile.Unit') }}</label>
                                                                <select data-cloneid="1" class="form-select addvalidation unit" name="ProductUnit[]">
                                                                    <option value="">{{ __('profile.select_unit') }}</option>
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

                                                                                    <span class="text-danger trashicon {{ $k == 0 ? 'hidden' : '' }}"> <i class="fa fa-trash"></i>
                                                                                    </span>
                                                        </div>
                                                    </div>
                                                            @php $k++ @endphp
                                                        @endforeach
                                                    @else
                                                    <div class="row align-items-center interested_in_html">
                                                        <div class="col-md-11 d-flex">
                                                            <div class="col-md-4 mb-2">
                                                                <label class="form-label">{{ __('profile.Category') }}</label>
                                                                <select data-cloneid="0" class="form-select addvalidation productcategory" name="ProductCategory[]">
                                                                    <option value="">{{__('profile.select_category')}}</option>
                                                                    @foreach ($category as $cat)
                                                                        <option
                                                                            value="{{ $cat->id }}">
                                                                            {{ $cat->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4 mb-2 ps-3">
                                                                <label class="form-label">{{ __('profile.Annual_Consumption') }}</label>
                                                                <input data-cloneid="0" type="number" class="form-control addvalidation annualconsumption" name="ProductAnnualConsumption[]" placeholder="0" data-parsley-type="number" min="0">
                                                                <span class="invalid-feedback d-block"></span>
                                                            </div>
                                                            <div class="col-md-4 mb-2 ps-3">
                                                                <label class="form-label">{{ __('profile.Unit') }}</label>
                                                                <select data-cloneid="1" class="form-select addvalidation unit" name="ProductUnit[]">
                                                                    <option value="">{{ __('profile.select_unit') }}</option>
                                                                    @foreach ($units as $unit)
                                                                        <option
                                                                            value="{{ $unit->id }}">
                                                                            {{ $unit->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="invalid-feedback d-block" ></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label class="invisible">&nbsp;</label>
                                                            <div>
                                                                                        <span class="text-danger trashicon hidden">
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
                                                    <h5 class="mb-0"><img src="{{URL::asset('assets/icons/platform_charges.png')}}" alt="Company" class="pe-2" height="20px">
                                                        <span>{{ __('admin.platform_charges') }}</span></h5>
                                                </div>
                                                <div class="card-body p-3" id="mainInterestedInHtml">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="table-responsive newtable_v2 ">
                                                                <table class="table border">
                                                                    <thead>
                                                                    <tr>
                                                                        <th class="text-center" width="60px">{{--<input class="form-check-input mt-0" type="checkbox" onclick="checkListAll(this)" @if(count($platform_charges) == $count_other_charges) checked @endif>--}}</th>
                                                                        <th width="30%">{{ __('admin.charges') }}</th>
                                                                        <th width="20%">{{ __('admin.type') }}</th>
                                                                        <th>{{ __('admin.charges_amount') }} (RP)</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($platform_charges as $key => $value)
                                                                    <tr>
                                                                        <td class="text-center"><input class="form-check-input checkbox-platform mt-0 @if($value->id == 10 || $value->editable == 0) checkbox-nochecked @endif" type="checkbox" name="platform_charges[]" id="platform_charges_{{$value->id}}" value="{{ $value->id }}"  @if(!empty($value->company)) checked @endif @if($value->id == 10 || $value->editable == 0) disabled @endif onclick="changeValueEnableDisable(this, '{{ $value->id }}', '{{ $value->name }}')"></td>
                                                                        <td> {{ $value->name }}</td>
                                                                        @if($value->editable == 1 && $value->id != 10 && $value->company != NULL)
                                                                            <td>
                                                                                <select class="form-select selectBox" name="type[{{$value->id}}]" id="type_{{$value->id}}" onchange="ChangeValue(this, '{{$value->id}}')">
                                                                                    <option {{ $value->xenditCommisionFee['type']== 0 ? 'selected="selected"' : '' }}  value="0">%</option>
                                                                                    <option {{ $value->xenditCommisionFee['type']== 1 ? 'selected="selected"' : '' }}  value="1">RP (Flat)</option>
                                                                                </select>
                                                                                <i class="fa fa-chevron-down"></i>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="chargeValue[{{$value->id}}]" id="chargeValue_{{$value->id}}" class="form-control w-auto" style="height: auto;" required value="{{ $value->xenditCommisionFee['charges_value'] }}">
                                                                            </td>
                                                                        @else
                                                                            <td>
                                                                                <select class="form-select selectBox" name="type[{{$value->id}}]" id="type_{{$value->id}}" onchange="ChangeValue(this, '{{$value->id}}')"  @if($value->id == 10 || $value->editable == 0 || $value->xenditCommisionFee['is_delete'] == 1) disabled @endif>
                                                                                    <option {{ $value->type== 0 ? 'selected="selected"' : '' }}  value="0">%</option>
                                                                                    <option {{ $value->type== 1 ? 'selected="selected"' : '' }}  value="1">RP (Flat)</option>
                                                                                </select>
                                                                                <i class="fa fa-chevron-down"></i>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="chargeValue[{{$value->id}}]" id="chargeValue_{{$value->id}}" class="form-control w-auto" style="height: auto;" required value="{{ $value->charges_value }}" @if($value->id == 10 || $value->editable == 0 || $value->xenditCommisionFee['is_delete'] == 1) disabled @endif>
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
                                      action="{{ route('buyer-update') }}" data-parsley-validate>
                                    @csrf
                                    <input type="hidden" name="buyerid" value="{{ $buyer->id }}">
                                    <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="mb-0">
                                                                            <span class="pe-2">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="17.5" height="20" viewBox="0 0 17.5 20">
                                                                                    <path id="icon_personal_pro" d="M8.75,10a5,5,0,1,0-5-5A5,5,0,0,0,8.75,10Zm3.5,1.25H11.6a6.8,6.8,0,0,1-5.7,0H5.25A5.251,5.251,0,0,0,0,16.5v1.625A1.875,1.875,0,0,0,1.875,20h13.75A1.875,1.875,0,0,0,17.5,18.125V16.5A5.251,5.251,0,0,0,12.25,11.25Z">
                                                                                    </path>
                                                                                </svg>
                                                                            </span>
                                                    <span>{{__('admin.contact_detail')}}</span></h5>
                                            </div>
                                            <div class="card-body p-3 pb-1">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class=" col-md-4 mb-3 img_section_buyer" title="{{ __('admin.update_image') }}">
                                                            @php
                                                                /*$imgpath = config('settings.profile_images_folder') . '/' . 'no_image.png';
                                                                 if ($buyer->profile_pic) {
                                                                    $imgpath = $buyer->profile_pic;
                                                                }*/
                                                            @endphp
                                                            @php
                                                                $static_image = asset('settings.profile_images_folder') . '/' . 'no_image.png';
                                                            @endphp
                                                            <div class="user_info_photo text-center">
                                                                @if($buyer->profile_pic)
                                                                    <div class="ratio ratio-1x1">
                                                                    <img src="{{asset('storage/' . $buyer->profile_pic) }}"  name="userProfilePic" id="userProfilePic"  height="80" width="80" class="avatar-xl rounded-circle" alt="">
                                                                    </div>
                                                                @else
                                                                    <div class="ratio ratio-1x1">
                                                                    <img src="{{ URL::asset('/assets/images/user.png') }}" name="userProfilePic" id="userProfilePic"  height="80" width="80" class="avatar-xl rounded-circle" alt="">
                                                                    </div>
                                                                @endif
                                                                <div class="mt-2"  style="border-radius: 50px;">
                                                                    <input type="file" name="user_pic" class="form-control" id="user_pic"
                                                                           accept=".jpg,.png,.jpeg"  hidden="">
                                                                    <label id="upload_btn" for="user_pic">{{ __('admin.update_image') }}</label>
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
                                                                        class="form-label">{{ __('profile.first_name') }} @if($checkNotAdminRole)<span class="text-danger">*</span>@endif</label>
                                                                    <div class="d-flex">
                                                                        <select name="salutation" class="form-select w100p border-end-0" id="salutation" style="border-radius: 0px;  background-color: rgba(0, 0, 0, 0.05); font-size: 0.875rem;">
                                                                            <option value="1" {{(isset($buyer['salutation']) && $buyer['salutation'] == "1" ? 'selected' : '') }} >{{__('admin.salutation_mr')}}</option>
                                                                            <option value="2" {{(isset($buyer['salutation']) && $buyer['salutation'] == "2" ? 'selected' : '') }}>{{__('admin.salutation_ms')}}</option>
                                                                            <option value="3" {{(isset($buyer['salutation']) && $buyer['salutation'] == "3" ? 'selected' : '') }}>{{__('admin.salutation_mrs')}}</option>
                                                                        </select>
                                                                        <input type="text" name="firstName" id="firstName" class="form-control"
                                                                               value="{{ $buyer->firstname }}" @if($checkNotAdminRole) required @endif data-parsley-errors-container="#firstname-error">
                                                                    </div>
                                                                    <div id="firstname-error"></div>
                                                                </div>

                                                                <div class="col-md-6 mb-3">
                                                                    <label
                                                                        for="contactPersonLastName"
                                                                        class="form-label">{{ __('profile.last_name') }} @if($checkNotAdminRole) <span class="text-danger">*</span> @endif </label>
                                                                    <input type="text" name="lastName" id="lastName" class="form-control"
                                                                           value="{{ $buyer->lastname }}" @if($checkNotAdminRole) required @endif data-parsley-errors-container="#lastname-error">
                                                                    <div id="lastname-error"></div>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label
                                                                        for="contactPersonEmail"
                                                                        class="form-label">{{ __('profile.Email') }} @if($checkNotAdminRole) <span class="text-danger">*</span> @endif</label>
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
                                                                        class="form-label">{{ __('profile.mobile_number') }} @if($checkNotAdminRole) <span class="text-danger">*</span> @endif </label>
                                                                    <input type="text" class="form-control" id="mobile" name="mobile" value="{{ $buyer->mobile }}" @if($checkNotAdminRole) required @endif data-parsley-type="digits"
                                                                           data-parsley-length="[9, 16]" data-parsley-length-message="It should be between 9 and 16 digit.">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label
                                                                        class="form-label">{{ __('profile.designation') }}</label>
                                                                    <select name="designation" id="designation" class="form-select">
                                                                        <option value="">{{__('profile.select_designation')}}</option>
                                                                        @foreach ($designations as $designation)
                                                                            <option
                                                                                {{ $buyer->designation == $designation->id ? 'selected' : '' }}
                                                                                value="{{ $designation->id }}">
                                                                                {{ $designation->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label
                                                                        class="form-label">{{ __('profile.department') }}</label>
                                                                    <select name="department" id="department" class="form-select">
                                                                        <option value="">{{__('profile.select_department')}}</option>
                                                                        @foreach ($departments as $department)
                                                                            <option
                                                                                {{ $buyer->department == $department->id ? 'selected' : '' }}
                                                                                value="{{ $department->id }}">
                                                                                {{ $department->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
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
                            <div id="step-3" class="tab-pane p-0 d-none" role="tabpanel" style="display: none;">
                                <div class="row">
                                    <div class="col-md-12 pb-3 d-none" id="xen-div">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center">
                                                <div>
                                                    <h5><img src="{{URL::asset("front-assets/images/icons/xendit.png")}}" alt="Supplier Details" class="pe-2" height="20px">
                                                        Xen Account Detail</h5>
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
                                                        <label>Xen Platform ID</label>
                                                        <div class="text-dark" id="xen_platform_id">
                                                            626b7e7a9e71a973fb2d8e7e</div>
                                                    </div>
                                                    <div class="col-md-3 pb-2">
                                                        <label>Xen Platform Balance</label>
                                                        <div id="xen_balance">Rp 0.00</div>
                                                    </div>
                                                    <div class="col-md-3 pb-2">
                                                        <label>Company Name</label>
                                                        <div class="text-dark" id="supplier_company_name">Amul
                                                            dairy products</div>
                                                    </div>
                                                    <div class="col-md-3 pb-2">
                                                        <label>E-mail</label>
                                                        <div class="text-dark" id="supplier_email">
                                                            shetepoonam049@gmail.com</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="mb-0"><img src="{{URL::asset("assets/icons/credit-card.png")}}" alt="Charges" class="pe-2" height="20px"> <span>Bank
                                                                                Details</span></h5>
                                            </div>
                                            <div class="card-body p-3 pb-1">
                                                <div class="row">
                                                    <div class="col-md-12 mb-3 newtable_v2">
                                                        <div class="d-flex align-items-center justify-content-end pe-1 mb-3">
                                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bankdetail">Add</button>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <div id="supplierBankTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                                                <div class="row">
                                                                    <div class="col-sm-12 col-md-6">
                                                                        <div class="dataTables_length" id="supplierBankTable_length">
                                                                            <label>Show <select name="supplierBankTable_length" aria-controls="supplierBankTable" class="custom-select custom-select-sm form-control form-control-sm">
                                                                                    <option value="10">
                                                                                        10
                                                                                    </option>
                                                                                    <option value="20">
                                                                                        20
                                                                                    </option>
                                                                                    <option value="50">
                                                                                        50
                                                                                    </option>
                                                                                    <option value="-1">
                                                                                        All
                                                                                    </option>
                                                                                </select>
                                                                                entries</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12 col-md-6">
                                                                        <div id="supplierBankTable_filter" class="dataTables_filter">
                                                                            <label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="supplierBankTable"></label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <table id="supplierBankTable" class="table table-hover border dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="supplierBankTable_info">
                                                                            <thead>
                                                                            <tr class="bg-light" role="row">
                                                                                <th class="sorting sorting_desc" tabindex="0" aria-controls="supplierBankTable" rowspan="1" colspan="1" aria-sort="descending" aria-label="Bank Logo: activate to sort column ascending" style="width: 80.075px;">
                                                                                    Bank
                                                                                    Logo
                                                                                </th>
                                                                                <th class="sorting" tabindex="0" aria-controls="supplierBankTable" rowspan="1" colspan="1" aria-label="Bank Name: activate to sort column ascending" style="width: 128.075px;">
                                                                                    Bank
                                                                                    Name
                                                                                </th>
                                                                                <th class="sorting" tabindex="0" aria-controls="supplierBankTable" rowspan="1" colspan="1" aria-label="Bank code: activate to sort column ascending" style="width: 106.075px;">
                                                                                    Bank
                                                                                    code
                                                                                </th>
                                                                                <th class="sorting" tabindex="0" aria-controls="supplierBankTable" rowspan="1" colspan="1" aria-label="Bank Account Name: activate to sort column ascending" style="width: 144.075px;">
                                                                                    Bank
                                                                                    Account
                                                                                    Name
                                                                                </th>
                                                                                <th class="sorting" tabindex="0" aria-controls="supplierBankTable" rowspan="1" colspan="1" aria-label="Bank Account Number: activate to sort column ascending" style="width: 158.075px;">
                                                                                    Bank
                                                                                    Account
                                                                                    Number
                                                                                </th>
                                                                                <th class="sorting" tabindex="0" aria-controls="supplierBankTable" rowspan="1" colspan="1" aria-label="Primary/Secondary: activate to sort column ascending" style="width: 138.075px;">
                                                                                    Primary/Secondary
                                                                                </th>
                                                                                <th class="sorting" tabindex="0" aria-controls="supplierBankTable" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" style="width: 60.075px;">
                                                                                    Actions
                                                                                </th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="bg-white">

                                                                            <tr id="addbankdetails_97" class="odd">

                                                                                <td class="sorting_1">
                                                                                    <div class="p-1 pe-1">
                                                                                        <img src="{{URL::asset("assets/icons/bank.png")}}" class="t_bank_logo" alt="bank Logo" width="20px" height="20px">
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                                                <span class="t_bank_name ps-1">Bank
                                                                                                                    Dinar
                                                                                                                    Indonesia</span>
                                                                                </td>
                                                                                <td class="t_bank_code">
                                                                                    DINAR_INDONESIA
                                                                                </td>
                                                                                <td class="t_bank_account_name">
                                                                                    ps</td>
                                                                                <td class="t_bank_account_number">
                                                                                    12356789
                                                                                </td>
                                                                                <td class="t_primary_others">
                                                                                    <div class="form-check form-switch" style="margin-left: 0px;">
                                                                                        <input class="form-check-input account_is_primary" data-id="97" type="checkbox" id="account_is_primary_97" checked="">
                                                                                    </div>
                                                                                </td>
                                                                                <td class="text-end text-nowrap">
                                                                                    <a href="javascript:void(0)" data-id="97" class="editSupplierBankDetails"><i class="fa fa-edit" style="color: #3f80ea;"></i></a>
                                                                                    <a href="javascript:void(0)" data-id="97" class="deletebankdetails ps-2">
                                                                                        <i class="fa fa-trash" style="color: #d9534f;"></i></a>
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-12 col-md-5">
                                                                        <div class="dataTables_info" id="supplierBankTable_info" role="status" aria-live="polite">
                                                                            Showing 1 to 1 of 1
                                                                            entries</div>
                                                                    </div>
                                                                    <div class="col-sm-12 col-md-7">
                                                                        <div class="dataTables_paginate paging_simple_numbers" id="supplierBankTable_paginate">
                                                                            <ul class="pagination">
                                                                                <li class="paginate_button page-item previous disabled" id="supplierBankTable_previous">
                                                                                    <a href="#" aria-controls="supplierBankTable" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                                                                                </li>
                                                                                <li class="paginate_button page-item active">
                                                                                    <a href="#" aria-controls="supplierBankTable" data-dt-idx="1" tabindex="0" class="page-link">1</a>
                                                                                </li>
                                                                                <li class="paginate_button page-item next disabled" id="supplierBankTable_next">
                                                                                    <a href="#" aria-controls="supplierBankTable" data-dt-idx="2" tabindex="0" class="page-link">Next</a>
                                                                                </li>
                                                                            </ul>
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
        </div>

    </div>
    <!-- Upload profile image modal -->
    <div class="customscroll showpop">
        <div class="modal fade" id="UploadProfileImageModal" tabindex="-1" role="dialog"  aria-hidden="true">
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
                        <button id="cropProfileImage" class="btn btn-primary crop-profile-picture  mt-0 save-btn save-btn-bg text-white" >
                            Crop & Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Upload profile image modal -->
    <script src="{{ URL::asset('front-assets/intlTelInput/js/intlTelInput.js') }}"></script>
    <script>
        var buyerId = {{ $buyer->id }};
        var company_id = {{$buyer->company_id }};

        // croppie image related
        var $uploadProfileCrop,	tempProfileFilename, rawProfileImg,	imageProfileId;
        $uploadProfileCrop = $('#profile_image_preview').croppie({
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
        $('#user_pic').on('change', function () {
            imageProfileId = $(this).data('id');
            tempProfileFilename = $(this).val();
            readProfileFile(this);
        });
        function readProfileFile(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                var file = input.files[0];
                var fileType = file.type.split('/')[0];
                var fileExtension = file.type.split('/')[1];
                var maxFileSize = 3000; //3mb
                var fileSize = Math.round((file.size / 1024));

                if(fileSize > maxFileSize){
                    $("#user_pic").val("");
                    swal({
                        icon: 'error',
                        title: '',
                        text: '{{__('admin.file_size_under_3mb')}}',
                    });
                    return false;
                }


                if( fileType == 'image' && (fileExtension == 'png' || fileExtension == 'jpg' || fileExtension == 'jpeg') ) {
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
                swal("Sorry - you're browser doesn't support the FileReader API");
            }
        }
      $('#UploadProfileImageModal').on('shown.bs.modal', function(){
            $uploadProfileCrop.croppie('bind', {
                url: rawProfileImg
            }).then(function(){
                // console.log('jQuery bind complete');
            });
        });

        var loadFile = function(event) {
            var output = document.getElementById('userProfilePic');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };

        // croppie image related

        var loadCompanyLogo = function(event) {
            var output = document.getElementById('companyLogoPreview');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };

        $("#background_logo").val($(".logobanner_section").css('background-image'));
        var companydetails = @json($buyer);
        if(companydetails.background_logo){
            $('.logobanner_section').css('background-image', companydetails.background_logo);
        }
        if(companydetails.background_colorpicker) {
            $('.logobanner_section').css('background', companydetails.background_colorpicker);
        }
        if(companydetails.company_logo){
            $("#companyLogoPreview").attr('src','{{ asset("storage/") }}'+ '/'+ companydetails.company_logo);
        }else{
            $("#companyLogoPreview").attr('src','{{ URL::asset("front-assets/images/front/logo.png") }}');
        }

        var input = document.querySelector("#company_phone");
        var iti = window.intlTelInput(input, {
            initialCountry:"id",
            separateDialCode:true,
            dropdownContainer:null,
            preferredCountries:["id"],
            hiddenInput:"c_phone_code"
        });
        var input3 = document.querySelector("#alternative_phone");
        var iti3 = window.intlTelInput(input3, {
            initialCountry:"id",
            separateDialCode:true,
            dropdownContainer:null,
            preferredCountries:["id"],
            hiddenInput:"a_phone_code"
        });
        var input2 = document.querySelector("#mobile");
        var iti2 = window.intlTelInput(input2, {
            initialCountry:"id",
            separateDialCode:true,
            dropdownContainer:null,
            preferredCountries:["id"],
            hiddenInput:"phone_code"
        });

        $("#company_phone").focusin(function(){
            let countryData = iti.getSelectedCountryData();
            $('input[name="c_phone_code"]').val(countryData.dialCode);
        });
        $("#alternative_phone").focusin(function(){
            let countryData = iti3.getSelectedCountryData();
            $('input[name="a_phone_code"]').val(countryData.dialCode);
        });
        $("#mobile").focusin(function(){
            let countryData = iti2.getSelectedCountryData();
            $('input[name="phone_code"]').val(countryData.dialCode);
        });

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
        $(document).ready(function() {
            @php
                $cPhoneCode = $buyer->c_phone_code?str_replace('+','',$buyer->c_phone_code):62;
                $cCountry = $buyer->c_phone_code?strtolower(getRecordsByCondition('countries',['phone_code'=>$cPhoneCode],'iso2',1)):'id';
                $aPhoneCode = $buyer->a_phone_code?str_replace('+','',$buyer->a_phone_code):62;
                $aCountry = $buyer->a_phone_code?strtolower(getRecordsByCondition('countries',['phone_code'=>$aPhoneCode],'iso2',1)):'id';
                $phoneCode = $buyer->phone_code?str_replace('+','',$buyer->phone_code):62;
                $country = $buyer->phone_code?strtolower(getRecordsByCondition('countries',['phone_code'=>$phoneCode],'iso2',1)):'id';
            @endphp
            $('#UploadProfileImageModal').on('shown.bs.modal', function(){
                $uploadProfileCrop.croppie('bind', {
                    url: rawProfileImg
                }).then(function(){
                    // console.log('jQuery bind complete');
                });
            });
            $('.crop-profile-picture').on('click', function (ev) {
                $uploadProfileCrop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport',
                    quality: 1
                }).then( function (img) {
                    console.log(img);
                    $("#userProfilePic").closest('div.ratio.ratio-1x1').show();
                    $("#userProfilePic").attr( 'src', img );
                    $('#UploadProfileImageModal').modal('hide');
                });
            });
            $('input[name="c_phone_code"]').val({{ $cPhoneCode }});
            iti.setCountry('{{$cCountry}}');
            $('input[name="a_phone_code"]').val({{ $aPhoneCode }});
            iti3.setCountry('{{$aCountry}}');
            $('input[name="phone_code"]').val({{ $phoneCode }});
            iti2.setCountry('{{$country}}');

            var customFinishBtn = $("<button></button>")
                .text("Finish")
                .addClass("btn btn-info hidden smartWizFinishBtn")
                .on("click", function () {
                    if ($('#buyerProfileEditForm').parsley().validate()) {
                        updateBuyerProfile();
                        $(".smartWizFinishBtn").addClass("disabled");
                        var url = "{{ route('buyer-list') }}";
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
                function(
                    e,
                    anchorObject,
                    currentStepIndex,
                    nextStepIndex,
                    stepDirection
                ) {
                    if (nextStepIndex==0) {
                        $(".smartWizFinishBtn").addClass("hidden");
                    }else if (nextStepIndex==2){
                    }
                    if (currentStepIndex == 0) {
                        $("#buyerEditForm").parsley().on('form:validate', function() {
                            tinymce.triggerSave();
                        });
                        if ($('#buyerEditForm').parsley().validate()) {
                            updateBuyer();
                            $(".removeFile").removeClass("hide");
                            $(".downloadbtn").removeClass("hide");
                            $(".smartWizFinishBtn").removeClass("hidden");
                            return true;
                        } else {
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
                validateString: function (_value, maxSize, parsleyInstance) {
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
                validateString: function (value, element) {
                    return value !== $(element).val();
                }
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
                            id: $('#buyerEditForm input[name="id"]').val(),
                            email: value,
                        },
                        async: false,
                        success: function (data) {
                            res = data;
                        },
                    });
                    //console.log(res);
                    return res;
                },
                messages: {
                    en: "{{__('admin.email_already_exist')}}"
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
                            id: $('#buyerEditForm input[name="id"]').val(),
                            email: value,
                        },
                        async: false,
                        success: function (data) {
                            res = data;
                        },
                    });
                    return res;
                },
                messages: {
                    en: "{{__('admin.email_already_exist')}}"
                },
                priority: 32
            });


            $(document).on("click", ".removeFile", function(e) {
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
                            url: "{{ route('buyer-company-file-delete-ajax') }}",
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
           // company yearly Consumption part
            $(document).on('click', '.trashicon', function() {
                $(this).closest('.interested_in_html').remove();
            });
            $('#addInterestedBlock').click(function(e) {
                var index = parseInt($(".interested_in_html select[name='ProductCategory[]']").length - 1) + 1 ;
                var $clone = $('#mainInterestedInHtml .interested_in_html').first().clone(true).addClass('removewhileleft');
                $clone.find(':selected').removeAttr('selected');
                $clone.find("input").val("").end();
                $clone.find(".trashicon").removeClass('hidden');
                // var indexnew = $clone.find(".productcategory").attr('data-cloneid') + 1;
                $clone.find(".productcategory").attr('data-cloneid',index);
                $clone.find(".annualconsumption").attr('data-cloneid', index);
                $clone.find(".unit").attr('data-cloneid', index);
                $clone.appendTo($("#companyYearlyContainer"));
                $("html, body").animate({ scrollTop: $(document).height() }, 1000);
            });
        });

        function InterestedBlockClone(index){
            var $clone = $("#mainInterestedInHtml").clone(true);
            $clone.find(".trashicon11").removeClass('hidden');
            $clone.appendTo($("#companyYearlyContainer"));
        }
        function updateBuyer() {
            var formData = new FormData($("#buyerEditForm")[0]);
            formData.append('company_id',company_id);
            formData.append('addressbuyer', tinyMCE.get('addressbuyer').getContent({ format: 'text' }));
            $.ajax({
                url: "{{ route('buyer-update') }}",
                data: formData,
                type: "POST",
                contentType: false,
                processData: false,
                success: function(successData) {
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
                error: function() {
                    console.log("error");
                },
            });
        }
        function updateBuyerProfile(){
            var formData = new FormData($("#buyerProfileEditForm")[0]);
            var cropImageSrc = $(".user_info_photo").find('img').attr('src');
            formData.append('cropImageSrc', cropImageSrc);
            formData.append('id', buyerId);
            $.ajax({
                url: "{{ route('buyer-update') }}",
                data: formData,
                type: "POST",
                contentType: false,
                processData: false,
                success: function(successData) {
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
                error: function() {
                    console.log("error");
                },
            });

        }
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
                var allowed_extensions = new Array("jpg", "png", "gif", "jpeg");
                var file_extension = fileName.split('.').pop();
                var text = '';
                var buyer_id = '{{ $buyer->id }}';
                var image_path = '{{URL::asset("assets/icons/times-circle copy.png")}}';
                if (input.name == 'logo' || input.name == 'user_pic') {
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
                        var download_function = "'" + buyer_id + "', " + "'" + input.name + "', " + "'" + fileName + "'";
                        if(fileName.beforeLastIndex(".").length >= 10) {
                            fileName = fileName.substring(0,10) +'....'+file_extension;
                        }
                        $('#file-' + input.name).html('');
                        $('#file-' + input.name).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.name + 'Download" style="text-decoration: none">' + fileName + '</a></span><span class="removeFile hide" id="' + input.name + 'File" data-id="' + buyer_id + '" data-name="' + input.name + '"><a href="#" title="Remove logo" style="text-decoration: none;"><img src="' + image_path + '" alt="CLose button" class="ms-2"></a></span><span class="ms-2"><a class="logoFile downloadbtn hide" href="javascript:void(0);" title="Download ' + input.name + '" onclick="downloadimg(' + download_function + ')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>');
                        return;
                    }
                }
                valid = false;
                swal({
                    text: text,
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
                url: "{{ route('buyer-download-image-ajax') }}",
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
        function showFile(input) {
            let file = input.files[0];
            let size = Math.round((file.size / 1024))
            if(size > 3000){
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
                let company_id = '{{ $buyer->company_id }}';
                let image_path = '{{URL::asset("assets/icons/times-circle copy.png")}}';

                for (let i = 0; i < allowed_extensions.length; i++) {
                    if (allowed_extensions[i] == file_extension) {
                        valid = true;
                        let download_function = "'" + company_id + "', " + "'" + input.name + "', " + "'" + fileName + "'";
                        if(file_name_without_extension.length >= 10) {
                            fileName = file_name_without_extension.substring(0,10) +'....'+file_extension;
                        }
                        $('#file-' + input.name).html('');
                        $('#file-' + input.name).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.name + 'Download" style="text-decoration: none">' + fileName + '</a></span><span class="removeFile hidden" data-id="' + company_id + '" data-name="' + input.name + '"><a href="javascript:void(0)" title="{{ __('profile.remove_file') }}" style="text-decoration: none;"><img src="' + image_path + '" alt="CLose button" class="ms-2"></a></span><span class="ms-2"><a class="' + input.name + ' downloadbtn hidden" href="javascript:void(0);" title="{{ __('profile.download_file') }}" onclick="downloadimg(' + download_function + ')" style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a></span>');
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

        function checkListAll(check) {
            var checkedList = $(check).prop("checked") ? 1 : 0;
            if(checkedList){
                $(".checkbox-platform").prop("checked", true);
            } else {
                $(".checkbox-platform").prop("checked", false);
            }
            $('.checkbox-nochecked').prop("checked", true);
        }
        /*
        * when change type then set value 0
        * */
        function ChangeValue($this, id) {
           $('#chargeValue_'+id).val('0');
        }
        /*
        * when checkbox checked or unchecked then show swal message for conformation
        * */
        function changeValueEnableDisable($this, id, chargeName) {
            var messageText = ($this.checked == true) ? "{{ __('admin.change_editable_value_buyer_message_ckecked') }}": "{{ __('admin.change_editable_value_buyer_message_unchecked') }}";
            swal({
                text: chargeName +' '+ messageText +' {{$buyer->company_name}}',
                icon: "/assets/images/info.png",
                buttons: ["{{ __('admin.no') }}", "{{ __('admin.yes') }}"],
                closeOnClickOutside: false
            }).then((willCheck) => {
                if (willCheck) {
                    $("#platform_charges_"+id).prop("checked", $this.checked == true ? true :false);
                } else {
                    $("#platform_charges_"+id).prop("checked", $this.checked == true ? false :true);
                }
                commanAjaxChangeValue($this.checked, id, '{{ $buyer->company_id }}');
            });
        }
        /*
        * common function for update flat fees
        * */
        function commanAjaxChangeValue(check, id, company_id) {
            $.ajax({
                url: "{{ route('flat-fee-update') }}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {charge_id:id, check:check, company_id:company_id},
                type: 'POST',
                success: function (response) {
                    if(response.success == true && check == false){
                        $('#type_'+id).prop('disabled', true).val(response.data.type).change();
                        $('#chargeValue_'+id).prop('disabled', true).val(response.data.charges_value);
                    } else {
                        $('#type_'+id).prop('disabled', false).val(response.data.type).change();
                        $('#chargeValue_'+id).prop('disabled', false).val(response.data.charges_value);
                    }
                },
            });
        }
    </script>

@endsection
