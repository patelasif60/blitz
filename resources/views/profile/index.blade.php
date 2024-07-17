@extends('profile/layout')

@section('content')
<link href="{{ URL::asset('front-assets/js/front/crop/css/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ URL::asset('front-assets/css/front/colorpicker.css') }}">
<link href="{{ URL::asset('front-assets/js/front/crop/css/style-example.css') }}" rel="stylesheet">
<link href="{{ URL::asset('front-assets/css/front/style_role.css') }}" rel="stylesheet">
<link href="{{ URL::asset('front-assets/js/front/crop/css/jquery.Jcrop.min.css') }}" rel="stylesheet">
<link href="{{ asset('front-assets/css/front/croppie.min.css') }}" rel='stylesheet' />
<script type="text/javascript" src="{{ URL::asset('front-assets/js/front/crop/scripts/jquery.Jcrop.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('front-assets/js/front/crop/scripts/jquery.SimpleCropper.js') }}"></script>
    <script src='{{ asset("front-assets/js/front/croppie.js")}}'></script>
<script type="text/javascript" src="{{ URL::asset('front-assets/js/front/colorpicker.js') }}"></script>

    <style>
        .verify{ top: 26%; position: absolute; right: 20px;}
        .phoneverify .form-control{ padding-right: 30px !important;}
        .error {
            color: red;
        }

        #userCompanyDetailsForm .select2-hidden-accessible {
            width: 100% !important;
            height: 50px !important;
            position: relative !important;
            visibility: hidden;
        }

        #product_category_block .select2-container {
            width: 100% !important;
        }
        .swal-button--danger {
            background-color: #df4740 !important;
        }
        /* .iti--separate-dial-code .iti__selected-flag{font-size: 12px;  height: 38px;}
        @media (min-width: 1400px){
            .iti--separate-dial-code .iti__selected-flag{  height: 48px;}
        }
        @media (max-width: 991px){
            .iti--separate-dial-code .iti__selected-flag{  height: 27px;font-size: 10px;}
        } */
    </style>
@php
    $phoneNumber = $user->phone_code;
 @endphp

    <div class="col-lg-12 py-2">
        <div class=" border border-radius">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show " id="personal" role="tabpanel" aria-labelledby="personal-tab">
                    <div class="row mx-0">
                        <div class="col-md-4 col-lg-3 naviconsbtn px-0">
                            <ul class="nav nav-tabs d-block border-0" id="myTab" role="tablist">
                                @can('publish buyer personal info')
                                    <li class="nav-item changetab" role="presentation" id="change_personal_info">
                                        <button class="nav-link w-100 text-start " id="home-tab" data-bs-toggle="tab"
                                            data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                            aria-selected="true">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17.5"
                                                    height="20" viewBox="0 0 17.5 20">
                                                    <path id="icon_personal_pro"
                                                        d="M8.75,10a5,5,0,1,0-5-5A5,5,0,0,0,8.75,10Zm3.5,1.25H11.6a6.8,6.8,0,0,1-5.7,0H5.25A5.251,5.251,0,0,0,0,16.5v1.625A1.875,1.875,0,0,0,1.875,20h13.75A1.875,1.875,0,0,0,17.5,18.125V16.5A5.251,5.251,0,0,0,12.25,11.25Z" />
                                                </svg>
                                            </span>
                                            {{ __('profile.personal_info') }}
                                        </button>
                                    </li>
                                @endcan
                                @can('publish buyer change password')
                                    <li class="nav-item changetab" role="presentation" id="change_change_password">
                                        <button class="nav-link w-100 text-start" id="contact-tab" data-bs-toggle="tab"
                                            data-bs-target="#contact" type="button" role="tab" aria-controls="contact"
                                            aria-selected="false">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17.5" height="20" viewBox="0 0 17.5 20">
                                                    <path id="icon_lock_pro" d="M15.625,20H1.875A1.877,1.877,0,0,1,0,18.125v-7.5A1.877,1.877,0,0,1,1.875,8.75h.938V5.937a5.937,5.937,0,1,1,11.875,0V8.75h.938A1.877,1.877,0,0,1,17.5,10.625v7.5A1.877,1.877,0,0,1,15.625,20ZM9,13.983a1,1,0,0,0-1,1v2a1,1,0,1,0,2,0v-2A1,1,0,0,0,9,13.983ZM8.75,3.125A2.816,2.816,0,0,0,5.937,5.937V8.75h5.625V5.937A2.816,2.816,0,0,0,8.75,3.125Z" />
                                                </svg>
                                            </span>
                                            {{ __('profile.change_password') }}

                                        </button>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                        <div class="col-md-8 col-lg-9 bg-white">
                            <div class="tab-content bg-white" id="myTabContent">
                                <div class="tab-pane fade show " id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <form name="userDetailForm" id="userDetailForm" data-parsley-validate
                                        autocomplete="off" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row mx-0">
                                            <div class="col-md-4 p-4 text-center mt-md-2">
                                                <div class="d-flex justify-content-center mb-3">
                                                    <div class="position-relative userphoto">
                                                        <div class="user_info_photo radius_1 text-center verticle-middle border" title="{{ __('profile.change_avatar') }}">
                                                            @php
                                                                $imgpath = config('settings.profile_images_folder') . '/' . 'no_image.png';
                                                                 if ($user->profile_pic) {
                                                                    $imgpath = $user->profile_pic;
                                                                }
                                                            @endphp
                                                                @if($user->profile_pic)
                                                                <div class="ratio ratio-1x1">
                                                                    <img src="{{asset('storage/' . $imgpath) }}" id="userProfilePic" class="cover" />
                                                                </div>
                                                                @else
                                                                <div class="ratio ratio-1x1" style="display: none">
                                                                    <img src="{{asset('storage/' . $imgpath) }}" id="userProfilePic" class="cover" />
                                                                </div>
                                                                {{ strtoupper(substr(Auth::user()->firstname, 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname, 0, 1)) }}
                                                                @endif
                                                        </div>
                                                        <div class="userphotohover">{{ __('profile.change_avatar') }}</div>
                                                    </div>

                                                </div>
                                                <div class="input-group d-flex justify-content-center">
                                                    <div class="box">
                                                        <input type="file" name="user_pic" id="user_pic"
                                                            class="inputfile inputfile-3 d-none" accept=".jpg,.png,.jpeg"
                                                             />
                                                        <label for="user_pic"
                                                            class="border"><span>{{ __('profile.change_avatar') }}</span></label>
                                                            <span class="invalid-feedback d-block"></span>
                                                        <p> <small
                                                                class="text-muted">{{ __('profile.upload_jpg_png_text') }}</small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8 p-4 mt-md-2">
                                                <div class="row g-4 floatlables">
                                                    <h5 style="color: #0000FF; font-size: 1rem;">{{ __('profile.personal_information.title') }}</h5>
                                                    <div class="col-md-6">
                                                        <label class="form-label">{{ __('profile.first_name') }}<span class="text-danger">*</span></label>
                                                        <div class="d-flex">
                                                            <select class=" form-select dropdown-arrow border-end-0" id="salutation" name="salutation"
                                                                    style="width: 120px; background-color: rgba(0, 0, 0, 0.05);     background-size: 8px 8px; border-radius: 8px 0 0 8px; background-image: url(../../front-assets/images/dropdown-arrow.svg);">
                                                                <option value="1" {{(isset($user['salutation']) && $user['salutation'] == "1" ? 'selected' : '') }} >{{__('admin.salutation_mr')}}</option>
                                                                <option value="2" {{(isset($user['salutation']) && $user['salutation'] == "2" ? 'selected' : '') }}>{{__('admin.salutation_ms')}}</option>
                                                                <option value="3" {{(isset($user['salutation']) && $user['salutation'] == "3" ? 'selected' : '') }}>{{__('admin.salutation_mrs')}}</option>
                                                            </select>
                                                            <input type="text" class="form-control" style="border-radius: 0 8px 8px 0;" id="firstName" name="firstName" value="{{$user->firstname}}" data-parsley-error-message="{{ __('profile.required_error') }}" required data-parsley-errors-container="#firstname-error">
                                                        </div>
                                                        <div id="firstname-error"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label
                                                            class="form-label">{{ __('profile.last_name') }}<span style="color:red">*</span></label>
                                                        <input type="text" class="form-control" name="lastName"
                                                            value="{{ $user->lastname }}" data-parsley-error-message="{{ __('profile.required_error') }}" required>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label">{{ __('profile.Email') }}</label>
                                                        <input type="email" data-parsley-required-message="{{ __('frontFormValidationMsg.email') }}"  class="form-control bg-light" name="email" id="email"
                                                            value="{{ $user->email }}" {{ $user->is_active ? 'readonly':'' }}>
                                                            @if(!$user->is_active)
                                                            <a href="javascript:void(0);" class="verify js-resendemail" title="{{ __('profile.varify_your_mail') }}" >{{ __('profile.verify') }}</a>
                                                            @endif
                                                    </div>
                                                    <div class="col-md-6 position-relative phoneverify">
                                                        <label
                                                            class="form-label">{{ __('profile.mobile_number') }}<span style="color:red">*</span></label>
                                                        <input type="text" class="form-control" id="mobile" name="mobile"
                                                            value="{{ $user->mobile }}" required data-parsley-type="digits"
                                                               data-parsley-length="[9, 16]" data-parsley-length-message="It should be between 9 and 16 digit.">
                                                               <a href="/changemobile" class="verify" title="{{ __('profile.change_mobile_number') }}" >{{ __('profile.change') }}</i></a>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label
                                                            class="form-label">{{ __('profile.designation') }}</label>

                                                        <select name="designation" id="" class="form-select">
                                                            <option value="">{{__('profile.select_designation')}}</option>
                                                            @foreach ($designations as $designation)
                                                                <option {{ (isset($user->companyUserDetails[0]->designation) && $user->companyUserDetails[0]->designation == $designation->id) ? 'selected' : '' }} value="{{ $designation->id }}">
                                                                    {{ $designation->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label
                                                            class="form-label">{{ __('profile.department') }}</label>
                                                        <select name="department" id="" class="form-select">
                                                            <option value="">{{__('profile.select_department')}}</option>
                                                            @foreach ($departments as $department)
                                                                <option {{ (isset($user->companyUserDetails[0]->department) &&  $user->companyUserDetails[0]->department == $department->id) ? 'selected' : '' }} value="{{ $department->id }}">
                                                                    {{ $department->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <h5 style="color: #0000FF; font-size: 1rem;">{{ __('profile.other_information') }}</h5>
                                                    <div class="col-md-6">
                                                        <label class="form-label">{{ __('profile.religion.title') }}<span class="text-danger">*</span></label>
                                                        <select id="religion"  name="religion" id=""
                                                            class="form-select" data-parsley-error-message="{{ __('profile.required_error') }}" required>
                                                            <option value="">{{ __('profile.religion.select') }}</option>
                                                            @foreach($religions as $key=>$religion)
                                                                <option value="{{$religion->id}}"  {{(isset($user->userOtherInformation) && $user->userOtherInformation->religion == $religion->id ? 'selected' : '') }}>{{ $religion->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">{{ __('profile.marital_status.title') }}<span class="text-danger">*</span></label>
                                                        <select name="marital_status" id="marital_status" class="form-select" required data-parsley-error-message="{{ __('profile.required_error') }}">
                                                            <option value="">{{ __('profile.marital_status.select') }}</option>
                                                            <option value="1"  {{(isset($user->userOtherInformation) && $user->userOtherInformation->marital_status == "1" ? 'selected' : '') }}>KAWIN</option>
                                                            <option value="2"  {{(isset($user->userOtherInformation) && $user->userOtherInformation->marital_status == "2" ? 'selected' : '') }}>BELUM KAWIN</option>
                                                            <option value="3"  {{(isset($user->userOtherInformation) && $user->userOtherInformation->marital_status == "3" ? 'selected' : '') }}>CERAI MATI</option>
                                                            <option value="4"  {{(isset($user->userOtherInformation) && $user->userOtherInformation->marital_status == "4" ? 'selected' : '') }}>CERAI HIDUP</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">{{ __('profile.date_of_birth.title') }}<span class="text-danger">*</span></label>
                                                        <input type="text" required class="form-control calendericons select-date-age" readonly data-parsley-error-message="{{ __('profile.required_error') }}" value="{{ isset($user->userOtherInformation) ? $user->userOtherInformation->date_of_birth : ''}}" id="date_of_birth" name="date_of_birth" data-parsley-errors-container="">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">{{ __('profile.place_of_birth.title') }}<span class="text-danger">*</span></label>
                                                        <input type="text" required data-parsley-error-message="{{ __('profile.required_error') }}" class="form-control" id="place_of_birth" name="place_of_birth" value="{{ isset($user->userOtherInformation) ? $user->userOtherInformation->place_of_birth : ''}}" required="" data-parsley-errors-container="">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="" class="form-label">{{ __('profile.family_card_image.title') }}</label>
                                                        <div class="d-flex form-control">
                                                            <span class="">
                                                                <input type="file" data-parsley-errors-container="#family_card_image_error"  name="family_card_image" class="form-control" id="family_card_image" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                                <label id="upload_btn" for="family_card_image">{{ __('profile.browse') }}</label>
                                                            </span>
                                                            <div id="file-family_card_image" class="d-flex align-items-center">
                                                              <span class="d-flex">
                                                                    @if(isset($user->userOtherInformation) && $user->userOtherInformation->family_card_image != '')
                                                                    <a  class="text-decoration-none downloadPo d-flex align-items-center ms-2" href="{{ url($user->userOtherInformation->family_card_image?? '') }}"  download>{{$user->userOtherInformation->family_card_image_filename}}</a>
                                                                    <span class="removeFile"  data-id="{{$user->userOtherInformation->id}}" file-path="{{ $user->userOtherInformation->family_card_image }}" data-name="family_card_image" data-type="persnol_other">
                                                                        <a href="javascript:void(0);" title="{{ __('profile.remove_file') }}"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a>
                                                                    </span>
                                                                    <span class="ms-2">
                                                                        <a class="family_card_image" href="{{ url($user->userOtherInformation->family_card_image?? '') }}" title="{{ __('profile.download_file') }}" download><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a>
                                                                    </span>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div id="family_card_image_error"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="" class="form-label">{{ __('profile.ktp_image.title') }}</label>
                                                        <div class="d-flex form-control">
                                                            <span class="">
                                                                <input type="file" data-parsley-errors-container="#ktp_image_error"  name="ktp_image" class="form-control" id="ktp_image" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                                <label id="upload_btn" for="ktp_image">{{ __('profile.browse') }}</label>
                                                            </span>
                                                            <div id="file-ktp_image" class="d-flex align-items-center">
                                                               <span class="d-flex">
                                                                    @if(isset($user->userOtherInformation) && $user->userOtherInformation->ktp_image != '')
                                                                    <a  id="downloadPo" class="text-decoration-none downloadPo d-flex align-items-center ms-2" href="{{ url($user->userOtherInformation->ktp_image?? '') }}"  download>{{$user->userOtherInformation->ktp_image_filename}}</a>
                                                                    <span class="removeFile"  data-id="{{$user->userOtherInformation->id}}" file-path="{{ $user->userOtherInformation->ktp_image }}" data-name="ktp_image" data-type="persnol_other">
                                                                        <a href="javascript:void(0);" title="{{ __('profile.remove_file') }}"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a>
                                                                    </span>
                                                                    <span class="ms-2">
                                                                        <a class="ktp_image" href="{{ url($user->userOtherInformation->ktp_image?? '') }}" title="{{ __('profile.download_file') }}" download><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a>
                                                                    </span>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div id="ktp_image_error"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="" class="form-label">{{ __('profile.ktp_with_selfie_image.title') }}</label>
                                                        <div class="d-flex form-control">
                                                            <span class="">
                                                                <input type="file" data-parsley-errors-container="#ktp_with_selfie_image_error" name="ktp_with_selfie_image" class="form-control" id="ktp_with_selfie_image" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                                <label id="upload_btn" for="ktp_with_selfie_image">{{ __('profile.browse') }}</label>
                                                            </span>
                                                            <div id="file-ktp_with_selfie_image" class="d-flex align-items-center">
                                                                <span class="d-flex">
                                                                    @if(isset($user->userOtherInformation) && $user->userOtherInformation->ktp_with_selfie_image != '')
                                                                    <a  class="text-decoration-none downloadPo d-flex align-items-center ms-2" href="{{ url($user->userOtherInformation->ktp_with_selfie_image?? '') }}"  download>{{$user->userOtherInformation->ktp_with_selfie_image_filename}}</a>
                                                                    <span class="removeFile"  data-id="{{$user->userOtherInformation->id}}" file-path="{{ $user->userOtherInformation->ktp_with_selfie_image }}" data-name="ktp_with_selfie_image" data-type="persnol_other">
                                                                        <a href="javascript:void(0);" title="{{ __('profile.remove_file') }}"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a>
                                                                    </span>
                                                                    <span class="ms-2">
                                                                        <a class="ktp_with_selfie_image" href="{{ url($user->userOtherInformation->ktp_with_selfie_image?? '') }}" title="{{ __('profile.download_file') }}" download><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a>
                                                                    </span>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div id="ktp_with_selfie_image_error"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">{{ __('profile.ktpnik.title') }}<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control input-number ktpnik" id="ktp_nik" name="ktp_nik"  required  data-parsley-minlength="16" data-parsley-type="digits" data-parsley-required-message="{{ __('profile.required_error') }}" data-parsley-minlength-message="{{ __('profile.required_ktp_error') }}" value="{{ isset($user->userOtherInformation) ? $user->userOtherInformation->ktp_nik : ''}}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">{{ __('profile.gender.title') }}<span class="text-danger">*</span></label>
                                                        <select name="gender" id="gender" data-parsley-error-message="{{ __('profile.required_error') }}" required class="form-select">
                                                            <option value="">{{ __('profile.gender.select') }}</option>
                                                            <option value="1" {{(isset($user->userOtherInformation) && $user->userOtherInformation->gender == "1" ? 'selected' : '') }}>Male</option>
                                                            <option value="2" {{(isset($user->userOtherInformation) && $user->userOtherInformation->gender == "2" ? 'selected' : '') }}>Female</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-12 text-end">
                                                        <a data-boolean="false" class="btn btn-primary px-4 py-2 mb-1" id="saveUserDetailsBtn"
                                                            href="javascript:void(0)"><img
                                                                src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}"
                                                                alt="Post Requirement" class="pe-1">
                                                            {{ __('profile.save_changes') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                    <div class="row mx-0 justify-content-center">
                                        <div class="col-md-4 p-4 text-center mt-md-2">
                                            <img src="{{ URL::asset('front-assets/images/img_change_pass.png') }}" alt="">
                                        </div>
                                        <div class="col-md-8 p-4 mt-md-2">
                                            <form class="row g-4 floatlables" name="userPasswordChangeForm"
                                                id="userPasswordChangeForm" data-parsley-validate autocomplete="off">
                                                @csrf
                                                <div class="row g-4 floatlables">

                                                    <div class="col-md-12">
                                                        <label
                                                            class="form-label">{{ __('profile.current_password') }}</label>
                                                        <input type="password" class="form-control"
                                                            name="currentPassword" required>
                                                        <span class="error" id="wrongCurrentPassword"></span>
                                                        {{-- ekta If Login with socialite - show forget password link--}}
                                                        @php $socialiteUsers = checkUserLoginWithSocialite(auth()->user()->id); @endphp
                                                        @if(!empty($socialiteUsers) && ($socialiteUsers->password == ''))
                                                            <a href="{{ route('forget-password-get') }}" class="frgt-btn" target="_blank">{{ __('signup.forget_password') }} ?</a>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label">
                                                            {{ __('profile.new_password') }}</label>
                                                        <input type="password" class="form-control" name="newPassword"
                                                            id="newPassword" required>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label
                                                            class="form-label">{{ __('profile.confirm_password') }}</label>
                                                        <input type="password" class="form-control"
                                                            name="confirmPassword" id="confirmPassword"
                                                            data-parsley-equalto="#newPassword" required>
                                                    </div>

                                                    <div class="col-md-12 d-flex align-items-center">
                                                            <a data-boolean="false" class="btn btn-primary px-4 py-2 mb-1 ms-auto" href="javascript:void(0)"
                                                            id="changePasswordBtn"><img
                                                                src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}"
                                                                alt="Post Requirement" class="pe-1">
                                                            {{ __('profile.save_changes') }}</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- profile tab end -->

                <div class="tab-pane fade show position-relative " id="company" role="tabpanel" aria-labelledby="company-tab">
                    @can('publish buyer company info')
                        @php
                        $percentage = getUserWisePendingProfilePercentage(Auth::user()->id,Auth::user()->default_company);
                        @endphp
                        <!-- Profile percentage -->
                        <div class="text-danger profilestatus d-flex hide" id="hide_show_profile_bar"><small class="me-1 fw-bold propertxt">{{__('dashboard.profile_completion')}}</small>

                            <div class="progress" id="progress_bar_ajax">
                                <div class="progress-bar" role="progressbar" aria-label="Example with label" style="width: {{$percentage}};" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{$percentage}}</div>
                            </div>
                        </div>
                        <!-- Profile percentage -->
                    @endcan
                    <div class="row mx-0">
                        <div class="col-md-4 col-lg-3 naviconsbtn px-0">
                            <ul class="nav nav-tabs d-block border-0" id="myTab1" role="tablist">
                                @can('publish buyer preferences')
                                    <li class="nav-item changetab" role="presentation" id="change_Preferences">
                                        <button class="nav-link w-100 text-start " id="pre-tab" data-bs-toggle="tab"
                                                data-bs-target="#pre" type="button" role="tab" aria-controls="pre"
                                                aria-selected="true">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                                        <g id="icon_language_pro" transform="translate(-755 -335)">
                                                            <path id="Path_10" data-name="Path 10"
                                                                d="M960.289,409.956v13.259a.593.593,0,0,0-.031.091,1.74,1.74,0,0,1-.618,1.068,2.083,2.083,0,0,1-.911.4H949.8c.023-.031.044-.064.069-.093q1.205-1.379,2.412-2.757a1.876,1.876,0,0,0,.486-1.526q-.3-2.436-.6-4.87-.4-3.207-.8-6.414c-.031-.247-.058-.495-.086-.743a.693.693,0,0,1,.082-.013c2.39,0,4.78,0,7.17,0a1.7,1.7,0,0,1,1.365.676A2.192,2.192,0,0,1,960.289,409.956Zm-4.974,7.455c-.024.022-.055.054-.09.083-.391.322-.785.64-1.174.965a.583.583,0,1,0,.717.92c.1-.068.185-.143.275-.217l1.105-.908c.478.376.944.749,1.417,1.112a.576.576,0,0,0,.847-.088.584.584,0,0,0-.141-.841c-.224-.178-.455-.346-.678-.524s-.416-.343-.629-.519a7.614,7.614,0,0,0,1.387-2.47c.014-.043.1-.084.161-.088a.6.6,0,0,0,.605-.6.591.591,0,0,0-.639-.568c-.513,0-1.026,0-1.539,0-.062,0-.125-.006-.2-.009,0-.184,0-.352,0-.52a.587.587,0,1,0-1.169.006c0,.172,0,.344,0,.523h-.244c-.5,0-1,0-1.5,0a.583.583,0,1,0-.021,1.167l.134.006c.2.451.368.9.593,1.323S955.045,416.984,955.315,417.411Z"
                                                                transform="translate(-185.289 -69.775)" />
                                                            <path id="Path_11" data-name="Path 11"
                                                                d="M775,340.181a2.193,2.193,0,0,0-.394-.919,1.7,1.7,0,0,0-1.365-.676c-2.39-.008-4.78,0-7.17,0a.691.691,0,0,0-.082.013c.029.249.055.5.086.743q.4,3.207.8,6.414.3,2.435.6,4.87a1.876,1.876,0,0,1-.486,1.526q-1.211,1.374-2.412,2.757c-.025.029-.046.062-.069.093H755V335h20Zm-19.963,3.081q0,3.2,0,6.395a1.743,1.743,0,0,0,1.769,1.829c3.028.011,6.056,0,9.083,0a.224.224,0,0,0,.144-.031.766.766,0,0,0,.286-.665c-.118-.855-.217-1.713-.323-2.571q-.339-2.755-.678-5.51c-.244-1.978-.494-3.956-.734-5.935a1.789,1.789,0,0,0-1.816-1.741c-1.975-.007-3.95-.009-5.925.006a1.98,1.98,0,0,0-.806.179,1.7,1.7,0,0,0-1,1.666Q755.035,340.075,755.037,343.262Zm9.9,9.407h-3.069c.046.325.073.642.14.95a1.664,1.664,0,0,0,.868,1.138.188.188,0,0,0,.176-.018C763.674,354.063,764.292,353.38,764.936,352.67Z"
                                                                fill="#fff" />
                                                            <path id="Path_12" data-name="Path 12"
                                                                d="M1132.66,714.214a2.083,2.083,0,0,0,.911-.4,1.74,1.74,0,0,0,.618-1.068.6.6,0,0,1,.031-.091v1.56Z"
                                                                transform="translate(-359.22 -359.214)" fill="#fff" />
                                                            <path id="Path_13" data-name="Path 13"
                                                                d="M755.747,343.86q0-3.187,0-6.375a1.7,1.7,0,0,1,1-1.666,1.98,1.98,0,0,1,.806-.179c1.975-.015,3.95-.013,5.925-.006a1.789,1.789,0,0,1,1.816,1.741c.239,1.979.489,3.957.734,5.935q.34,2.755.678,5.51c.106.857.2,1.715.323,2.571a.766.766,0,0,1-.286.665.223.223,0,0,1-.144.031c-3.028,0-6.055.008-9.083,0a1.743,1.743,0,0,1-1.769-1.829Q755.743,347.058,755.747,343.86Zm6.1.629c.009.033.016.051.019.07.117.585.232,1.171.352,1.755a.586.586,0,1,0,1.148-.228q-.572-2.863-1.147-5.724a.61.61,0,0,0-.677-.558q-.516,0-1.033,0a.61.61,0,0,0-.677.559q-.576,2.862-1.147,5.724a.586.586,0,1,0,1.144.247c.034-.145.057-.293.087-.439q.141-.7.283-1.406Z"
                                                                transform="translate(-0.71 -0.598)" />
                                                            <path id="Path_14" data-name="Path 14"
                                                                d="M898.711,696.877c-.645.71-1.262,1.393-1.886,2.07a.188.188,0,0,1-.176.018,1.664,1.664,0,0,1-.868-1.138c-.067-.308-.093-.625-.14-.95Z"
                                                                transform="translate(-133.775 -344.207)" />
                                                            <path id="Path_15" data-name="Path 15"
                                                                d="M1061.75,542.928a5.45,5.45,0,0,1-.938-1.67h1.916A5.845,5.845,0,0,1,1061.75,542.928Z"
                                                                transform="translate(-290.88 -196.187)" />
                                                            <path id="Path_16" data-name="Path 16"
                                                                d="M851.863,447.693l.462-2.316h.256l.463,2.316Z"
                                                                transform="translate(-92.133 -104.988)" />
                                                        </g>
                                                    </svg>
                                                </span>
                                                {{ __('profile.Preferences') }}
                                            </button>
                                    </li>
                                @endcan
                                    @can('publish buyer company info')
                                        <li class="nav-item changetab" role="presentation" id="change_company_info">
                                            <button class="nav-link w-100 text-start" id="profile-tab" data-bs-toggle="tab"{{ __('profile.permission.Company Information') }}
                                                    data-bs-target="#profile" type="button" role="tab" aria-controls="profile"
                                                    aria-selected="false">
                                                    <span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="17.5"
                                                            height="20" viewBox="0 0 17.5 20">
                                                            <path id="icon_company_pro" d="M17.031,18.75H16.25V.938A.937.937,0,0,0,15.313,0H2.188A.937.937,0,0,0,1.25.938V18.75H.469A.469.469,0,0,0,0,19.219V20H17.5v-.781A.469.469,0,0,0,17.031,18.75ZM5,2.969A.469.469,0,0,1,5.469,2.5H7.031a.469.469,0,0,1,.469.469V4.531A.469.469,0,0,1,7.031,5H5.469A.469.469,0,0,1,5,4.531Zm0,3.75a.469.469,0,0,1,.469-.469H7.031a.469.469,0,0,1,.469.469V8.281a.469.469,0,0,1-.469.469H5.469A.469.469,0,0,1,5,8.281ZM7.031,12.5H5.469A.469.469,0,0,1,5,12.031V10.469A.469.469,0,0,1,5.469,10H7.031a.469.469,0,0,1,.469.469v1.563A.469.469,0,0,1,7.031,12.5ZM10,18.75H7.5V15.469A.469.469,0,0,1,7.969,15H9.531a.469.469,0,0,1,.469.469Zm2.5-6.719a.469.469,0,0,1-.469.469H10.469A.469.469,0,0,1,10,12.031V10.469A.469.469,0,0,1,10.469,10h1.563a.469.469,0,0,1,.469.469Zm0-3.75a.469.469,0,0,1-.469.469H10.469A.469.469,0,0,1,10,8.281V6.719a.469.469,0,0,1,.469-.469h1.563a.469.469,0,0,1,.469.469Zm0-3.75A.469.469,0,0,1,12.031,5H10.469A.469.469,0,0,1,10,4.531V2.969a.469.469,0,0,1,.469-.469h1.563a.469.469,0,0,1,.469.469Z" />
                                                        </svg>
                                                </span>
                                                {{ __('profile.permission.Company Information') }}
                                            </button>
                                        </li>
                                    @endcan
                                @can('publish buyer side invite')
                                    <li class="nav-item changetab" role="presentation" id="invite_supplier_list">
                                        <button class="nav-link w-100 text-start" id="invite_supplier_tab" data-bs-toggle="tab"
                                                data-bs-target="#invite_supplier" type="button" role="tab" aria-controls="invite_supplier"
                                                aria-selected="false">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 18 18"><defs><clipPath id="b"><rect width="18" height="18"/></clipPath></defs><g id="a" clip-path="url(#b)"><g transform="translate(0.01 0.005)"><path d="M64.422,231.5a.859.859,0,0,1-.592-.957c.032-.672-.025-1.354.073-2.015a5.092,5.092,0,0,1,3.2-4.128,4.919,4.919,0,0,1,2-.394c1.487,0,2.974-.007,4.461,0a5.224,5.224,0,0,1,5.142,4.117c.054.232.084.469.126.7V230.9a.883.883,0,0,1-.6.6Z" transform="translate(-60.839 -213.506)"/><path d="M132.914,0c.279.058.564.1.837.176a4.492,4.492,0,1,1-1.746-.145A1.159,1.159,0,0,0,132.141,0Z" transform="translate(-122.022)"/><path d="M0,114.805a.86.86,0,0,1,.957-.593c.421.019.843,0,1.292,0v-.175c0-.427,0-.855,0-1.282a.751.751,0,1,1,1.5-.007c.007.479,0,.959,0,1.465h.2c.427,0,.855,0,1.282,0a.753.753,0,0,1,.737.543.719.719,0,0,1-.311.825,1.049,1.049,0,0,1-.463.126c-.473.016-.946.006-1.442.006v.253c0,.4,0,.8,0,1.195a.752.752,0,1,1-1.5,0c0-.474,0-.948,0-1.446H1.9c-.322,0-.645-.014-.966,0a.831.831,0,0,1-.93-.6Z" transform="translate(0 -106.724)"/><path d="M371.25,371.847a.883.883,0,0,0,.6-.6v.6Z" transform="translate(-353.858 -353.858)" fill="#fff"/></g></g></svg>
                                            </span>
                                            {{ __('profile.invite_buyer_supplier') }}
                                        </button>
                                    </li>
                                @endcan
                                @can('publish buyer payment term')
                                    <li class="nav-item changetab" role="presentation" id="change_Payment_Term">
                                        <button class="nav-link w-100 text-start" id="per-tab" data-bs-toggle="tab"
                                            data-bs-target="#per" type="button" role="tab" aria-controls="per"
                                            aria-selected="false">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15"
                                                    height="20" viewBox="0 0 15 20">
                                                    <path id="file-invoice-dollar_pro"
                                                        d="M14.727,4.1,10.9.273A.937.937,0,0,0,10.238,0H10V5h5V4.762A.935.935,0,0,0,14.727,4.1ZM8.75,5.313V0H.938A.935.935,0,0,0,0,.938V19.063A.935.935,0,0,0,.938,20H14.063A.935.935,0,0,0,15,19.063V6.25H9.688A.94.94,0,0,1,8.75,5.313ZM2.5,2.813A.312.312,0,0,1,2.813,2.5H5.938a.312.312,0,0,1,.313.313v.625a.312.312,0,0,1-.312.313H2.813A.312.312,0,0,1,2.5,3.438Zm0,3.125V5.313A.312.312,0,0,1,2.813,5H5.938a.312.312,0,0,1,.313.313v.625a.312.312,0,0,1-.312.313H2.813A.312.312,0,0,1,2.5,5.938ZM8.125,16.245v.942a.312.312,0,0,1-.312.313H7.188a.312.312,0,0,1-.312-.312v-.949A2.237,2.237,0,0,1,5.65,15.8a.313.313,0,0,1-.022-.474l.459-.438a.321.321,0,0,1,.4-.029.94.94,0,0,0,.5.145h1.1a.491.491,0,0,0,.461-.515.51.51,0,0,0-.343-.5L6.441,13.46a1.768,1.768,0,0,1-1.234-1.695A1.74,1.74,0,0,1,6.875,10V9.063a.312.312,0,0,1,.313-.312h.625a.312.312,0,0,1,.313.313v.949a2.234,2.234,0,0,1,1.225.443.313.313,0,0,1,.022.474l-.459.438a.321.321,0,0,1-.4.029.937.937,0,0,0-.5-.145h-1.1a.491.491,0,0,0-.461.515.51.51,0,0,0,.343.5l1.758.527a1.768,1.768,0,0,1,1.234,1.695,1.739,1.739,0,0,1-1.667,1.761Z" />
                                                </svg>
                                            </span>
                                            {{ __('profile.Payment_Term') }}
                                        </button>
                                    </li>
                                @endcan
                                @can('publish buyer bank details')
                                    <li class="nav-item changetab" role="presentation" id="user_bank">
                                        <button class="nav-link w-100 text-start" id="userbank-tab" data-bs-toggle="tab" data-bs-target="#userbank" type="button" role="tab" aria-controls="userbank" aria-selected="false">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17.992" height="18.015" viewBox="0 0 17.992 18.015">
                                                    <g id="bank" transform="translate(-0.015 18.015)">
                                                        <path id="Path_1" data-name="Path 1" d="M8.5-17.92c-.134.056-1.94.792-4.013,1.64S.644-14.7.549-14.65a1.025,1.025,0,0,0-.468.549,3.6,3.6,0,0,0-.063,1.077c0,.894,0,.9.092,1.091a.957.957,0,0,0,.5.524L.82-11.3H17.2l.215-.109a.957.957,0,0,0,.5-.524c.092-.194.092-.2.092-1.109a3.365,3.365,0,0,0-.063-1.07,1.1,1.1,0,0,0-.468-.539c-.1-.049-1.961-.824-4.15-1.721L9.346-18l-.3-.011A.961.961,0,0,0,8.5-17.92Z"/>
                                                        <path id="Path_2" data-name="Path 2" d="M1.69-7.339V-4.4H5.034v-5.878H1.69Z"/>
                                                        <path id="Path_3" data-name="Path 3" d="M7.346-10.254c-.014.011-.025,1.334-.025,2.939V-4.4H10.7l-.007-2.932-.011-2.929-1.658-.011C8.114-10.275,7.357-10.268,7.346-10.254Z"/>
                                                        <path id="Path_4" data-name="Path 4" d="M12.989-7.339V-4.4h3.344v-5.878H12.989Z"/>
                                                        <path id="Path_5" data-name="Path 5" d="M.722-3.3a1.155,1.155,0,0,0-.655.679,8.889,8.889,0,0,0,0,1.887,1.054,1.054,0,0,0,.532.62L.817,0H17.206l.222-.116a1.054,1.054,0,0,0,.532-.62,8.889,8.889,0,0,0,0-1.887,1.13,1.13,0,0,0-.669-.679C17.121-3.358.88-3.358.722-3.3Z"/>
                                                    </g>
                                                </svg>
                                            </span>
                                            {{ __('profile.bank_details') }}
                                        </button>
                                    </li>
                                @endcan
                                @can('publish buyer users')
                                    <li class="nav-item changetab" role="presentation" id="invite_user">
                                        <button class="nav-link w-100 text-start custom-link-layout" id="usertabs-tab" data-target="{{ url('settings/buyer-user') }}" type="button" role="tab" aria-controls="usertabs" aria-selected="false">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16.123" height="18" viewBox="0 0 16.123 18"><g transform="translate(0.005 0.003)"><path d="M7.593,206.327c-.285-.032-.57-.061-.855-.1a8.836,8.836,0,0,1-3.016-.958,9.449,9.449,0,0,1-3.617-3.36.573.573,0,0,1-.034-.623A5.77,5.77,0,0,1,4,198.121c.142-.036.288-.056.431-.086a.927.927,0,0,1,.76.183,4.873,4.873,0,0,0,3.1.9,4.267,4.267,0,0,0,2.383-.754,1.673,1.673,0,0,1,1.575-.208,5.909,5.909,0,0,1,3.6,2.768c.356.594.368.6-.015,1.179a9.316,9.316,0,0,1-3.694,3.273,9.11,9.11,0,0,1-3.547.931.847.847,0,0,0-.119.024Z" transform="translate(0 -188.329)"/><path d="M73.625,9.346a4.706,4.706,0,0,1-4.676-4.686,4.683,4.683,0,0,1,9.366.015A4.7,4.7,0,0,1,73.625,9.346Z" transform="translate(-65.579 0)"/></g></svg>
                                            </span>
                                            {{ __('profile.users') }}
                                        </button>
                                    </li>
                                @endcan
                                @can('publish buyer roles and permissions')
                                    <li class="nav-item changetab" role="presentation" id="roles">
                                        <button class="nav-link w-100 text-start custom-link-layout" id="rolestabs-tab" data-target="{{ url('settings/roles') }}" type="button"
                                                role="tab" aria-controls="rolestabs" aria-selected="false">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17.5" height="20" viewBox="0 0 17.5 20">
                                                    <path id="icon_lock_pro" d="M15.625,20H1.875A1.877,1.877,0,0,1,0,18.125v-7.5A1.877,1.877,0,0,1,1.875,8.75h.938V5.937a5.937,5.937,0,1,1,11.875,0V8.75h.938A1.877,1.877,0,0,1,17.5,10.625v7.5A1.877,1.877,0,0,1,15.625,20ZM9,13.983a1,1,0,0,0-1,1v2a1,1,0,1,0,2,0v-2A1,1,0,0,0,9,13.983ZM8.75,3.125A2.816,2.816,0,0,0,5.937,5.937V8.75h5.625V5.937A2.816,2.816,0,0,0,8.75,3.125Z"></path>
                                                </svg>
                                            </span>
                                            {{ __('home_latest.roles_and_permission') }}
                                        </button>
                                    </li>
                                @endcan
                                @can('publish buyer approval configurations')
                                    <li class="nav-item changetab d-none" role="presentation" id="approval_config">
                                        <button class="nav-link w-100 text-start" id="approval-tab" data-bs-toggle="tab"
                                            data-bs-target="#approval" type="button" role="tab" aria-controls="approval"
                                            aria-selected="false">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="17.811" viewBox="0 0 18 17.811"><path d="M0,9.916V9.672c.059-.09.121-.178.178-.268.306-.482.615-.963.912-1.452a.363.363,0,0,0,.031-.27C.951,7.144.767,6.611.591,6.074a.611.611,0,0,1,.331-.857c.489-.284.981-.562,1.467-.851a.315.315,0,0,0,.138-.192q.148-.825.275-1.654c.071-.474.258-.642.74-.657.56-.019,1.119-.041,1.679-.071a.3.3,0,0,0,.2-.105c.348-.453.689-.912,1.031-1.37A.609.609,0,0,1,7.326.1c.516.244,1.029.494,1.548.73a.339.339,0,0,0,.255,0C9.654.587,10.171.336,10.693.088a.605.605,0,0,1,.852.217c.343.457.688.913,1.027,1.374A.264.264,0,0,0,12.8,1.8c.572.017,1.143.042,1.713.065a.633.633,0,0,1,.684.621q.13.846.277,1.689a.315.315,0,0,0,.138.192c.48.287.968.562,1.452.842a.618.618,0,0,1,.341.883c-.175.525-.348,1.049-.528,1.572a.317.317,0,0,0,.036.307c.307.474.608.954.91,1.431.058.09.119.178.178.268v.244a4.513,4.513,0,0,1-.319.4c-.394.389-.8.766-1.2,1.156a.349.349,0,0,0-.1.238c.036.553.081,1.105.131,1.657.042.467-.09.676-.53.8-.542.158-1.085.311-1.625.475a.349.349,0,0,0-.19.168c-.238.538-.462,1.081-.7,1.621a.577.577,0,0,1-.713.382c-.589-.114-1.175-.239-1.764-.351a.3.3,0,0,0-.214.053c-.447.357-.888.722-1.329,1.083a.613.613,0,0,1-.92,0c-.441-.363-.883-.727-1.329-1.083A.29.29,0,0,0,7,16.466c-.555.105-1.109.219-1.66.334a.631.631,0,0,1-.851-.445c-.219-.514-.433-1.031-.66-1.542a.349.349,0,0,0-.19-.168c-.528-.161-1.061-.311-1.593-.463a.654.654,0,0,1-.56-.835q.079-.817.129-1.64a.349.349,0,0,0-.1-.238c-.394-.39-.8-.767-1.2-1.156A4.049,4.049,0,0,1,0,9.916Zm8.28.385c-.063-.071-.109-.124-.156-.175q-.835-.848-1.671-1.7a.511.511,0,0,0-.834.005c-.194.2-.384.39-.577.587a.538.538,0,0,0-.005.878q.876.894,1.756,1.784c.351.358.7.722,1.058,1.071a.771.771,0,0,0,1.312-.1Q11.114,9.74,13.058,6.82a.525.525,0,0,0-.16-.8c-.224-.158-.452-.312-.677-.467a.531.531,0,0,0-.878.168L8.409,10.113Z" transform="translate(0 0.003)"/></svg>
                                            </span>
                                            {{ __('profile.approval_configuration') }}
                                        </button>
                                    </li>
                                @endcan

                                @can('publish buyer preferred supplier')
                                <li class="nav-item changetab" role="presentation" id="preferred_suppliers">
                                    <button class="nav-link w-100 text-start" id="preferred-suppliers-tab" data-bs-toggle="tab" data-bs-target="#preferredSuppliers" type="button" role="tab" aria-controls="preferredSuppliers" aria-selected="false">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="17.811"  viewBox="0 0 16 16">
                                                <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                                <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z" />
                                            </svg>
                                        </span>
                                        {{ __('profile.preferred_suppliers') }}</button>
                                </li>
                                @endcan
                            </ul>
                        </div>
                        <div class="col-md-8 col-lg-9">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show " id="pre" role="tabpanel" aria-labelledby="pre-tab">
                                    <div class="row mx-0">
                                        <div class="col-md-6 p-4 text-center mt-md-2">
                                            <img src="{{ URL::asset('front-assets/images/language.png') }}" alt="">
                                        </div>
                                        <div class="col-md-6 p-4 mt-md-2">
                                            <form class="row g-4 floatlables" name="saveLangcurrencyForm" id="saveLangcurrencyForm" data-parsley-validate
                                        autocomplete="off">
                                                @csrf
                                                <div class="col-md-12">
                                                    <label class="form-label">{{ __('profile.Language') }}</label>

                                                    <select class="form-select" name="language_id">
                                                        @foreach ($language as $lang)
                                                            <option {{ $user->language_id == $lang->id ? 'selected' : '' }}

                                                                value="{{ $lang->id }}" >
                                                                {{ $lang->description }} ({{ $lang->name }})
                                                            </option>
                                                        @endforeach
                                                        <!-- <option>{{ __('profile.English') }} (US)</option>
                                                        <option>{{ __('profile.Indonesian') }} (IN)</option> -->
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">{{ __('profile.Currency') }}</label>
                                                    <select class="form-select" name="currency_id">
                                                        @foreach ($currencies as $currency)
                                                            <option {{ $user->currency_id  == $currency->id ? 'selected' : '' }}

                                                                value="{{ $currency->id }}">
                                                                {{ $currency->name  }}
                                                            </option>
                                                        @endforeach
                                                        <!-- <option>{{ __('profile.US_Dollar') }}</option>
                                                        <option>{{ __('profile.Indonesian_Rp') }}</option> -->
                                                    </select>
                                                </div>
                                                <div class="col-12 text-end">

                                                    <a data-boolean="false" class="btn btn-primary px-4 py-2 mb-1" id="saveLangcurrencymBtn"
                                                            href="javascript:void(0)"><img
                                                            src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}"
                                                            alt="Post Requirement"
                                                            class="pe-1">{{ __('profile.save_changes') }}</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade " id="per" role="tabpanel" aria-labelledby="per">
                                    <div class="row mx-0">
                                        <div class="col-md-12 p-4 mt-md-2">
                                        <form name="saveUserPaymenttermForm" id="saveUserPaymenttermForm" data-parsley-validate
                                        autocomplete="off">
                                        @csrf

                                            <div class=" d-flex w-100">
                                            <div class="mb-3 d-flex w-100 align-item-center">
                                                        <h5>{{ __('order.payment_terms') }}</h5>
                                                    </div>
                                            <div class="dropdown ms-auto">

                                                    <button class="btn btn-transparent btn-sm border dropdown-toggle"
                                                        type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <span
                                                            class="text-muted">{{ __('profile.Group_by') }}:</span>
                                                            <span id="groupbyname">All</span>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end"
                                                        aria-labelledby="dropdownMenuButton1">
                                                        <li><a class="dropdown-item GroupByPayment"
                                                                href="javascript:void(0)" data-id="all" data-name="All">
                                                                All</a></li>
                                                        @foreach($PaymentGroups as $PaymentGroup)

                                                        <li><a class="dropdown-item GroupByPayment" data-name="{{$PaymentGroup->name}}"
                                                                href="javascript:void(0)"  data-id="{{ $PaymentGroup->id}}">
                                                                {{ $PaymentGroup->name }}</a></li>@endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="table_payment mt-3">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <div class="form-check mb-0">
                                                                    <input class="form-check-input select-all-checkbox" type="checkbox"
                                                                        value="" >
                                                                </div>
                                                            </th>
                                                            <th>{{ __('profile.Payment_Method') }}</th>
                                                            <th class="numeric">{{ __('profile.Detail') }}</th>
                                                            <th class="numeric">{{ __('profile.Group') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="paymenttermsbody">
                                                        @foreach($PaymentTerms as $PaymentTerm)
                                                            <tr>
                                                            <td data-title="Select">
                                                                <div class="form-check">
                                                                    <input class="form-check-input child-checkbox" type="checkbox" value="{{$PaymentTerm->id}}" name="payment_term_id[]" @if($PaymentTerm->userPaymentterms) @if($user->id == $PaymentTerm->userPaymentterms->user_id) @if($PaymentTerm->id == $PaymentTerm->userPaymentterms->payment_term_id) checked @endif @endif @endif >
                                                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                                                    <input type="hidden" name="pay_user_id"  @if($PaymentTerm->userPaymentterms) value="{{$PaymentTerm->userPaymentterms->user_id}}" @endif>
                                                                    <input type="hidden" name="pay_id" value="{{$PaymentTerm->id}}">
                                                                    <input type="hidden" name="user_term_id"  @if($PaymentTerm->userPaymentterms) value="{{$PaymentTerm->userPaymentterms->payment_term_id}}" @endif>
                                                                </div>
                                                                <td data-title="Payment Method">{{ $PaymentTerm->name  }}</td>
                                                                <td data-title="Detail" class="numeric">
                                                                    {{ strip_tags($PaymentTerm->description) }}</td>
                                                                <td data-title="Group" class="numeric getgroupid termgroupid{{$PaymentTerm->PaymentGroup->id}}"  data-groupid="{{$PaymentTerm->PaymentGroup->id}}">
                                                                    {{ $PaymentTerm->PaymentGroup->name }}</td>
                                                                </td>
                                                                </tr>
                                                            @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-12 text-end">
                                                <a data-boolean="false" class="btn btn-primary px-4 py-2 mb-1" id="saveUserPaymenttermBtn"
                                                            href="javascript:void(0)"><img
                                                        src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}"
                                                        alt="Post Requirement" class="pe-1">
                                                    {{ __('profile.save_changes') }}</a>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade profilepercentage" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                    <form name="userCompanyDetailsForm" id="userCompanyDetailsForm" data-parsley-validate
                                          autocomplete="off" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row mx-0">
                                            <div class="col-md-4 p-4 text-center mt-md-2">
                                                <div class="d-flex justify-content-center mb-3">
                                                    <div class="w-100">
                                                        @php
                                                            $imgpath = URL::asset("front-assets/images/front/logo.png");
                                                            if ($companyDetails->company_logo) {
                                                                $imgpath = asset("storage/") . '/'.$companyDetails->company_logo ;
                                                            }
                                                        @endphp
                                                        <div class="logobanner_section d-flex align-items-center justify-content-center w-100 p-3 bg_logo_image" id="logbannerId">

                                                            <img src="{{$imgpath}}" alt="company logo" class="mw-100" id="companyLogoPreview" name="companyLogoPreview">

                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="input-group d-flex justify-content-center">
                                                    <div class="box">
                                                        <input type="file" name="company_logo" id="company_logo"
                                                               class="inputfile inputfile-3 d-none"
                                                               onchange="loadCompanyLogo(event)" accept='.jpg,.png,.jpeg' /><span class="invalid-feedback d-block"></span>
                                                        <input type="hidden" name="background_logo" id="background_logo"
                                                               class="inputfile inputfile-3 d-none" />
                                                        <input type="hidden" name="background_colorPicker" id="background_colorPicker"
                                                               class="inputfile inputfile-3 d-none" />
                                                        <label for="company_logo" id="company_logo_label"
                                                               class="border"><span>{{ __('profile.upload_company_logo') }}</span></label>
                                                        <p> <small
                                                                class="text-muted">{{ __('profile.upload_jpg_png_text') }}</small>
                                                        </p>
                                                    </div>
                                                </div>
                                                {{-- Company banner display  --}}
                                                <div class="bg-light p-2">
                                                    @for($m = 1; $m <= $bannerNumber; $m++)
                                                    <a class="banner{{$m}} changebanner" data-containerId="{{$m}}" href="javascript:void(0);"></a>
                                                    @endfor
                                                    <a class="banner12 changebanner"data-containerId="12" href="javascript:void(0);">
                                                        <input type="color" class="banner-color-picker border-0 position-relative p-0" id="colorpicker" style="border-radius: 5px; height: 48px; width: 48px;">
                                                    </a>

                                                </div>
                                            </div>
                                            <div class="col-md-8 p-4 mt-md-2">
                                                <div class="row g-4 floatlables">
                                                    <h5 style="color: #0000FF; font-size: 1rem;">{{ __('profile.business.title') }}</h5>
                                                    <div class="col-md-12">
                                                        <label
                                                            class="form-label">{{ __('profile.company_name') }}<span style="color:#ff0000">*</span></label>
                                                        <input type="hidden" name="company_id"
                                                               value="{{ Auth::user()->default_company }}">
                                                        <input type="text" class="form-control" name="company_name"
                                                               value="{{ $companyDetails->company_name }}" data-parsley-error-message="{{ __('profile.required_error') }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">{{ __('profile.company_email') }}<span style="color:red">*</span></label>
                                                        <input type="text" class="form-control" name="company_email" id="company_email"
                                                               value="{{ $companyDetails->company_email }}" required data-parsley-email="" data-parsley-notequalto="#alternate_email" data-parsley-notequalto-message="This email must differ from the alternate email!" data-parsley-error-message="{{ __('profile.required_error') }}" >
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label
                                                            class="form-label">{{ __('profile.company_phone') }}<span style="color:red">*</span></label>
                                                        <input type="text" class="form-control" name="company_phone" id="company_phone"
                                                               value="{{ $companyDetails->company_phone }}" required data-parsley-required-message="{{ __('profile.required_error') }}" data-parsley-mobile="" data-parsley-type="digits"
                                                               data-parsley-length="[9, 16]" data-parsley-length-message="{{ __('profile.required_phone_number_error') }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label
                                                            class="form-label">{{ __('profile.alternative_email') }}</label>
                                                        <input type="text" class="form-control"
                                                               name="alternative_email" id="alternate_email"
                                                               value="{{ $companyDetails->alternative_email }}"  data-parsley-email="" data-parsley-notequalto="#company_email" data-parsley-notequalto-message="This email must differ from the company email!" data-parsley-error-message="{{ __('profile.required_error') }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label
                                                            class="form-label">{{ __('profile.alternative_phone') }}</label>
                                                        <input type="text" class="form-control" name="alternative_phone" id="alternative_phone"
                                                               value="{{ $companyDetails->alternative_phone }}" data-parsley-mobile="" data-parsley-type="digits"
                                                               data-parsley-length="[9, 16]" data-parsley-length-message="It should be between 9 and 16 digit." data-parsley-error-message="{{ __('profile.required_error') }}">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label
                                                            class="form-label">{{ __('profile.registration_nib') }}<span style="color:red">*</span></label>
                                                        <input type="text" class="form-control" name="registration_nib" id="registration_nib"
                                                               value="{{ $companyDetails->registrantion_NIB }}" required data-parsley-type="digits" data-parsley-required-message="{{ __('profile.required_error') }}"
                                                               data-parsley-length="[13, 13]" data-parsley-length-message="Value should be 13 digits only.">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="" class="form-label">{{ __('profile.nib_file') }}</label>
                                                        <div class="d-flex form-control" id="nib_file_image_div" name="nib_file_image_div">
                                                            <span class="">
                                                                <input type="file" name="nib_file" class="form-control" id="nib_file" accept=".jpg,.png,.gif,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                                <label id="upload_btn" for="nib_file">{{ __('profile.browse') }}</label></span>
                                                            <div id="file-nib_file" class="d-flex align-items-center">
                                                                <input type="hidden" class="form-control" id="old_nib_file" name="old_nib_file" value="{{ $companyDetails->nib_file }}">
                                                                @if ($companyDetails->nib_file)
                                                                    @php
                                                                        $nibFileTitle = Str::substr($companyDetails->nib_file, stripos($companyDetails->nib_file, "nib_file_") + 9);
                                                                        $extension_nib_file = getFileExtension($nibFileTitle);
                                                                        $nib_file_filename = getFileName($nibFileTitle);
                                                                        if(strlen($nib_file_filename) > 10){
                                                                            $nib_file_name = substr($nib_file_filename,0,10).'...'.$extension_nib_file;
                                                                        } else {
                                                                            $nib_file_name = $nib_file_filename.$extension_nib_file;
                                                                        }
                                                                    @endphp
                                                                    <input type="hidden" class="form-control" id="oldnib_file" name="oldnib_file" value="{{ $companyDetails->nib_file }}">
                                                                    <span class="ms-2">
                                                                    <a href="javascript:void(0);" id="nibFileDownload" onclick="downloadimg('{{ $companyDetails->company_id }}', 'nib_file', '{{ $nibFileTitle }}')"  title="{{ $nibFileTitle }}" style="text-decoration: none;"> {{ $nib_file_name }}</a>
                                                                    </span>
                                                                    <span class="removeFile" id="nibFile" data-id="{{ $companyDetails->company_id }}" file-path="{{ $companyDetails->nib_file }}" data-name="nib_file">
                                                                        <a href="javascript:void(0);" title="{{ __('profile.remove_file') }}"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a>
                                                                    </span>
                                                                    <span class="ms-2">
                                                                        <a class="nib_file" href="javascript:void(0);" title="{{ __('profile.download_file') }}" onclick="downloadimg('{{ $companyDetails->company_id }}', 'nib_file', '{{ $nibFileTitle }}')" style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="" class="form-label">{{ __('profile.npwp') }}<span style="color:red">*</span></label>
                                                        <input class="form-control" type="text" name="npwp" id="npwp" value="{{ $companyDetails->npwp }}" placeholder="11.222.333.4-555.666" required data-parsley-pattern="^(\d{2})*[.]{1}(\d{3})*[.]{1}(\d{3})*[.]{1}(\d{1})*[-]{1}(\d{3})*[.]{1}(\d{3})*$" data-parsley-minlength="20" data-parsley-maxlength="20" data-parsley-pattern-message="{{ __('profile.required_npwp_error') }}" data-parsley-required-message="{{ __('profile.required_error') }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="" class="form-label">{{ __('profile.npwp_file') }}</label>
                                                        <div class="d-flex form-control" id="npwp_file_image_div" id="npwp_file_image_div">
                                                            <span class=""><input type="file" name="npwp_file" class="form-control" id="npwp_file" accept=".jpg,.png,.gif,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                                <label id="upload_btn" for="npwp_file">{{ __('profile.browse') }}</label></span>
                                                            <div id="file-npwp_file" class="align-items-center">
                                                                <input type="hidden" class="form-control" id="old_npwp_file" name="old_npwp_file" value="{{ $companyDetails->npwp_file }}">
                                                                @if ($companyDetails->npwp_file)
                                                                    @php
                                                                        $npwpFileTitle = Str::substr($companyDetails->npwp_file, stripos($companyDetails->npwp_file, "npwp_file_") + 10);
                                                                        $extension_npwp_file = getFileExtension($npwpFileTitle);
                                                                        $npwp_file_filename = getFileName($npwpFileTitle);
                                                                        if(strlen($npwp_file_filename) > 10){
                                                                            $npwp_file_name = substr($npwp_file_filename,0,10).'...'.$extension_npwp_file;
                                                                        } else {
                                                                            $npwp_file_name = $npwp_file_filename.$extension_npwp_file;
                                                                        }
                                                                    @endphp
                                                                    <input type="hidden" class="form-control" id="oldnpwp_file" name="oldnpwp_file" value="{{ $companyDetails->npwp_file }}">
                                                                    <span class="ms-2">
                                                                    <a href="javascript:void(0);" id="npwpFileDownload" onclick="downloadimg('{{ $companyDetails->company_id }}', 'npwp_file', '{{ $npwpFileTitle }}')"  title="{{ $npwpFileTitle }}" style="text-decoration: none;"> {{ $npwp_file_name }}</a>
                                                                </span>
                                                                    <span class="removeFile" id="npwpFile" data-id="{{ $companyDetails->company_id }}" file-path="{{ $companyDetails->npwp_file }}" data-name="npwp_file">
                                                                    <a href="javascript:void(0);" title="{{ __('profile.remove_file') }}"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a>
                                                                </span>
                                                                    <span class="ms-2">
                                                                    <a class="npwp_file" href="javascript:void(0);" title="{{ __('profile.download_file') }}" onclick="downloadimg('{{ $companyDetails->company_id }}', 'npwp_file', '{{ $npwpFileTitle }}')" style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a>
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label
                                                            class="form-label">{{ __('profile.web_site') }}</label>
                                                        <input type="text" class="form-control" name="website" id="website"
                                                               value="{{ $companyDetails->web_site }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">{{ __('profile.company_establishment_date') }}</label>
                                                        <input type="text"  class="calendericons form-control" readonly   value="{{ $companyDetails->establish_in }}"   id="establish_in" name="establish_in" >
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label
                                                            class="form-label">{{ __('profile.owner_full_name.fullname') }} </label>
                                                        <input type="text" class="form-control" name="owner_full_name" id="owner_full_name"
                                                               value="{{ $companyDetails->owner_full_name }}">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label">{{ __('profile.address') }}</label>
                                                        <textarea class="form-control"
                                                                  name="address" id="address">{{ $companyDetails->address }}</textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="" class="form-label">{{ __('admin.commercial_tc') }}</label>
                                                        <div class="d-flex form-control" id="commercial_tc_image_div" name="commercial_tc_image_div">
                                                            <span class="">
                                                                <input type="file" name="termsconditions_file" class="form-control" id="termsconditions_file" accept=".jpg,.png,.gif,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                                <label id="upload_btn" for="termsconditions_file">{{ __('profile.browse') }}</label></span>
                                                            <div id="file-termsconditions_file" class="d-flex align-items-center">
                                                                <input type="hidden" class="form-control" id="old_termsconditions_file" name="old_termsconditions_file" value="{{ $companyDetails->termsconditions_file }}">
                                                                @if ($companyDetails->termsconditions_file)
                                                                    @php
                                                                        $termsconditionsFileTitle = Str::substr($companyDetails->termsconditions_file, stripos($companyDetails->termsconditions_file, "termsconditions_file_") + 21);
                                                                        $extension_termsconditions_file = getFileExtension($termsconditionsFileTitle);
                                                                        $termsconditions_file_filename = getFileName($termsconditionsFileTitle);
                                                                        if(strlen($termsconditions_file_filename) > 10){
                                                                            $termsconditions_file_name = substr($termsconditions_file_filename,0,10).'...'.$extension_termsconditions_file;
                                                                        } else {
                                                                            $termsconditions_file_name = $termsconditions_file_filename.$extension_termsconditions_file;
                                                                        }
                                                                    @endphp
                                                                    <input type="hidden" class="form-control" id="oldtermsconditions_file" name="oldtermsconditions_file" value="{{ $companyDetails->termsconditions_file }}">
                                                                    <span class="ms-2">
                                                                    <a href="javascript:void(0);" target="_blank" id="termsconditionsFileDownload" onclick="downloadimg('{{ $companyDetails->company_id }}', 'termsconditions_file', '{{ $termsconditionsFileTitle }}')"  title="{{ $termsconditionsFileTitle }}" style="text-decoration: none;"> {{ $termsconditions_file_name }}</a>
                                                                    </span>
                                                                    <span class="removeFile" id="termsconditionsFile" data-id="{{ $companyDetails->company_id }}" file-path="{{ $companyDetails->termsconditions_file }}" data-name="termsconditions_file">
                                                                        <a href="javascript:void(0);" title="{{ __('profile.remove_file') }}"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a>
                                                                    </span>
                                                                    <span class="ms-2">
                                                                        <a class="termsconditions_file" href="javascript:void(0);" title="{{ __('profile.download_file') }}" onclick="downloadimg('{{ $companyDetails->company_id }}', 'termsconditions_file', '{{ $termsconditionsFileTitle }}')" style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <h5 style="color: #0000FF; font-size: 1rem;">{{ __('profile.other_detail') }}</h5>
                                                    <div class="col-md-6">
                                                        <label class="form-label">{{ __('profile.number_of_employee.title') }}</label>
                                                        <select id="number_of_employee"  name="number_of_employee"class="form-select">
                                                           <option value="">{{ __('profile.number_of_employee.select') }}</option>
                                                            <option value="1"  {{(isset($user->companies->companyOtherInformation) && $user->companies->companyOtherInformation->number_of_employee== "1" ? 'selected' : '') }}>1-50</option>
                                                            <option value="2"  {{(isset($user->companies->companyOtherInformation) && $user->companies->companyOtherInformation->number_of_employee== "2" ? 'selected' : '') }}>50-200</option>
                                                            <option value="3"  {{(isset($user->companies->companyOtherInformation) && $user->companies->companyOtherInformation->number_of_employee== "3" ? 'selected' : '') }}>200-500</option>
                                                            <option value="4"  {{(isset($user->companies->companyOtherInformation) && $user->companies->companyOtherInformation->number_of_employee== "4" ? 'selected' : '') }}>500-1000</option>
                                                            <option value="5"  {{(isset($user->companies->companyOtherInformation) && $user->companies->companyOtherInformation->number_of_employee== "5" ? 'selected' : '') }}>1000+</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="" class="form-label">{{ __('profile.average_sales.title') }}</label>
                                                        <input type="text" class="form-control input-number limit" name="average_sales" id="average_sales" value="{{
                                                            isset($user->companies->companyOtherInformation)?$user->companies->companyOtherInformation->average_sales:''
                                                        }}"/>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="annual_sales" class="form-label">{{ __('profile.annual_sales') }}<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control input-number limit" name="annual_sales" id="annual_sales" data-parsley-error-message="{{ __('profile.required_error') }}" required value="{{
                                                            isset($user->companies->companyOtherInformation)?$user->companies->companyOtherInformation->annual_sales:''
                                                        }}"/>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="" class="form-label">{{ __('profile.financial_target') }}<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control input-number limit" name="financial_target" id="financial_target" data-parsley-error-message="{{ __('profile.required_error') }}" required value="{{
                                                            isset($user->companies->companyOtherInformation)?$user->companies->companyOtherInformation->financial_target:''
                                                        }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">{{ __('profile.business_type.title') }}</label>
                                                        <select class="form-select"  name="type" id="type"  value="">
                                                            <option value="">{{ __('profile.business_type.select') }}</option>
                                                            <option  {{(isset($user->companies->companyOtherInformation) && $user->companies->companyOtherInformation->type == "1" ? 'selected' : '') }} value="1">Individual</option>
                                                            <option  {{(isset($user->companies->companyOtherInformation) && $user->companies->companyOtherInformation->type == "2" ? 'selected' : '') }} value="2">PT</option>
                                                            <option  {{(isset($user->companies->companyOtherInformation) && $user->companies->companyOtherInformation->type == "3" ? 'selected' : '') }} value="3">CV</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 select2-block">
                                                        <label for="" class="form-label">{{ __('profile.Category') }}</label>
                                                        <select  class="form-select" id="category"  name="category">
                                                            <option value="">{{ __('profile.select_category') }}</option>
                                                            @foreach($professionalCategories as $key=>$professionalCategory)
                                                                <option value="{{$professionalCategory->id}}"  {{(isset($user->companies->companyOtherInformation) && $user->companies->companyOtherInformation->category == $professionalCategory->id ? 'selected' : '') }}>{{ $professionalCategory->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label">{{ __('profile.description') }}</label>
                                                        <textarea class="form-control" name="description" id="description">{{
                                                            isset($user->companies->companyOtherInformation)?$user->companies->companyOtherInformation->description:''
                                                        }}</textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="" class="form-label">{{ __('profile.license_image.title') }}</label>
                                                        <div class="d-flex form-control" id="license_image_div" name="license_image_div">
                                                            <span class="d-flex">
                                                                <input type="file" name="license_image" class="form-control" id="license_image" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                                <label id="upload_btn" for="license_image">{{ __('profile.browse') }}</label>
                                                            </span>
                                                            <div id="file-license_image" name="file-license_image" class="d-flex align-items-center">
                                                               <span class="d-flex">
                                                                    @if(isset($user->companies->companyOtherInformation) && $user->companies->companyOtherInformation->license_image != '')
                                                                    <a  class="text-decoration-none downloadPo d-flex align-items-center ms-2" href="{{ url($user->companies->companyOtherInformation->license_image?? '') }}"  download>{{$user->companies->companyOtherInformation->license_image_filename}}</a>
                                                                    <span class="removeFile" id="licenseImage" data-id="{{$user->companies->companyOtherInformation->id}}" file-path="{{ $user->companies->companyOtherInformation->license_image }}" data-name="license_image" data-type="other">
                                                                        <a href="javascript:void(0);" title="{{ __('profile.remove_file') }}"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a>
                                                                    </span>
                                                                    <span class="ms-2">
                                                                        <a class="license_image" href="{{ url($user->companies->companyOtherInformation->license_image?? '') }}" title="{{ __('profile.download_file') }}" download><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a>
                                                                    </span>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="" class="form-label">{{ __('profile.bank_statement_image.title') }}</label>
                                                        <div class="d-flex form-control" id="bank_statement_image_div" name="bank_statement_image_div">
                                                            <span class="">
                                                                <input type="file" name="bank_statement_image" class="form-control" id="bank_statement_image" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                                <label id="upload_btn" for="bank_statement_image">{{ __('profile.browse') }}</label>
                                                            </span>
                                                            <div id="file-bank_statement_image" name="file-bank_statement_image" class="d-flex align-items-center">
                                                                <span class="d-flex">
                                                                    @if(isset($user->companies->companyOtherInformation) && $user->companies->companyOtherInformation->bank_statement_image != '')
                                                                    <a  class="text-decoration-none downloadPo d-flex align-items-center ms-2" href="{{ url($user->companies->companyOtherInformation->bank_statement_image?? '') }}"  download>{{$user->companies->companyOtherInformation->bank_statement_image_filename}}</a>
                                                                    <span class="removeFile"  data-id="{{$user->companies->companyOtherInformation->id}}" file-path="{{ $user->companies->companyOtherInformation->bank_statement_image }}" data-name="bank_statement_image" data-type="other">
                                                                        <a href="javascript:void(0);" title="{{ __('profile.remove_file') }}"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a>
                                                                    </span>
                                                                    <span class="ms-2">
                                                                        <a class="bank_statement_image" href="{{ url($user->companies->companyOtherInformation->bank_statement_image?? '') }}" title="{{ __('profile.download_file') }}" download><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a>
                                                                    </span>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <span class="fw-bold">@if(isset($user->companies->companyOtherInformation) && $user->companies->companyOtherInformation->bank_image_updated_at != '' ) {{ __('profile.last_update_at') }}  {{ $user->companies->companyOtherInformation->bank_image_updated_at}} @endif</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="" class="form-label">{{ __('profile.annual_financial_statement') }}</label>
                                                        <div class="d-flex form-control" id="annual_financial_statement_image_div" name="annual_financial_statement_image_div">
                                                            <span class="">
                                                                <input type="file" name="annual_financial_statement_image" id="annual_financial_statement_image" class="form-control" id="annual_financial_statement_image" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                                <label id="upload_btn" for="annual_financial_statement_image">{{ __('profile.browse') }}</label>
                                                            </span>
                                                            <div id="file-annual_financial_statement_image" name="file-annual_financial_statement_image" class="d-flex align-items-center">
                                                                <span class="d-flex">
                                                                    @if(isset($user->companies->companyOtherInformation) && $user->companies->companyOtherInformation->annual_financial_statement_image != '')
                                                                    <a  class="text-decoration-none downloadPo d-flex align-items-center ms-2" href="{{ url($user->companies->companyOtherInformation->annual_financial_statement_image?? '') }}"  download>{{$user->companies->companyOtherInformation->annual_financial_statement_image_filename }}</a>
                                                                    <span class="removeFile"  data-id="{{$user->companies->companyOtherInformation->id}}" file-path="{{ $user->companies->companyOtherInformation->annual_financial_statement_image }}" data-name="annual_financial_statement_image" data-type="other">
                                                                        <a href="javascript:void(0);" title="{{ __('profile.remove_file') }}"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a>
                                                                    </span>
                                                                    <span class="ms-2">
                                                                        <a class="annual_financial_statement_image" href="{{ url($user->companies->companyOtherInformation->annual_financial_statement_image?? '') }}" title="{{ __('profile.download_file') }}" download><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a>
                                                                    </span>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <span class="fw-bold">@if(isset($user->companies->companyOtherInformation) && $user->companies->companyOtherInformation->annual_image_updated_at != '' )
                                                            {{ __('profile.last_update_at') }} {{ $user->companies->companyOtherInformation->annual_image_updated_at}} @endif</span>
                                                    </div>
                                                     <div class="col-md-6 select2-block">
                                                        <label for="" class="form-label">{{ __('profile.ownership.title') }}</label>
                                                        <input type="text" class="form-control input-decimal limit" name="ownership_percentage" id="ownership_percentage" value="{{
                                                            isset($user->companies->companyOtherInformation)?$user->companies->companyOtherInformation->ownership_percentage:''
                                                        }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="" class="form-label">{{ __('profile.siup_num') }}</label>
                                                        <input type="text" class="form-control charlength20" name="siup_number" id="siup_number"  value="{{
                                                            isset($user->companies->companyOtherInformation)?$user->companies->companyOtherInformation->siup_number:''}}">
                                                    </div>

                                                    <div class="co-12">
                                                        <div class="row">
                                                            <div class="col-md-12 my-3">
                                                                <h2 class="h6 pro_bg_light px-2 py-2 rounded">
                                                                    {{ __('profile.Company_Yearly_Consumption_Detail') }}
                                                                </h2>
                                                            </div>
                                                            <div class="col-md-12 mb-3 text-end border-0">
                                                                <button type="button"
                                                                        class="btn plusicon btn-sm btn-success"
                                                                        id="addInterestedBlock">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                                         height="12" viewBox="0 0 20 20">
                                                                        <path id="icon_plus_pro"
                                                                              d="M18.571,39.857H12.143V33.429A1.429,1.429,0,0,0,10.714,32H9.286a1.429,1.429,0,0,0-1.429,1.429v6.429H1.429A1.429,1.429,0,0,0,0,41.286v1.429a1.429,1.429,0,0,0,1.429,1.429H7.857v6.429A1.429,1.429,0,0,0,9.286,52h1.429a1.429,1.429,0,0,0,1.429-1.429V44.143h6.429A1.429,1.429,0,0,0,20,42.714V41.286A1.429,1.429,0,0,0,18.571,39.857Z"
                                                                              transform="translate(0 -32)" />
                                                                    </svg> {{ __('profile.Add') }} </button>
                                                            </div>
                                                            <div id="mainInterestedInHtml">
                                                                @if (count($company_consumptions))
                                                                    @php $k = 0 ; @endphp
                                                                    @foreach ($company_consumptions as $key => $company_consumption)
                                                                        <div class=" row interested_in_html">
                                                                            <div class="col-11">
                                                                                <div class="row">
                                                                                    <div class="col-xl-4 col-lg-12 mb-3">
                                                                                        <label
                                                                                            class="form-label">
                                                                                            {{ __('profile.Category') }}</label>
                                                                                        <select data-cloneid="{{$k}}" class="form-select addvalidation productcategory" name="ProductCategory[]">
                                                                                            <option value="">{{__('profile.select_category')}}</option>
                                                                                            @foreach ($category as $cat)
                                                                                                <option {{ $company_consumption->product_cat_id == $cat->id ? 'selected' : '' }} value="{{ $cat->id }}"> {{ $cat->name }} </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                        <span class="invalid-feedback d-block"></span>
                                                                                    </div>
                                                                                    <div class="col-xl-4 col-lg-6 mb-3">
                                                                                        <label class="form-label">{{ __('profile.Annual_Consumption') }}</label>
                                                                                        <input data-cloneid="{{$k}}" type="number"
                                                                                               class="form-control numeric-dot-only addvalidation annualconsumption"
                                                                                               name="ProductAnnualConsumption[]"
                                                                                               placeholder="0" data-parsley-type="number" min="0"
                                                                                               value="{{ $company_consumption->annual_consumption }}"
                                                                                        ><span class="invalid-feedback d-block"></span>
                                                                                    </div>
                                                                                    <div class="col-xl-4 col-lg-6 mb-3">
                                                                                        <label
                                                                                            class="form-label">{{ __('profile.Unit') }}</label>
                                                                                        <select data-cloneid="{{$k}}" class="form-select addvalidation unit"
                                                                                                name="ProductUnit[]" >
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
                                                                            </div>
                                                                            <div class="col-1"><button
                                                                                    class="btn btn-trash border trashicon {{ $key == 0 ? 'hidden' : '' }}"><svg
                                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                                        width="17.5" height="20"
                                                                                        viewBox="0 0 17.5 20">
                                                                                        <path id="icon_trash"
                                                                                              d="M16.875,1.25H12.187L11.82.519A.938.938,0,0,0,10.98,0H6.516a.927.927,0,0,0-.836.52l-.367.73H.625A.625.625,0,0,0,0,1.875v1.25a.625.625,0,0,0,.625.625h16.25a.625.625,0,0,0,.625-.625V1.875A.625.625,0,0,0,16.875,1.25ZM2.078,18.242A1.875,1.875,0,0,0,3.949,20h9.6a1.875,1.875,0,0,0,1.871-1.758L16.25,5h-15Z"
                                                                                              transform="translate(0 0)" />
                                                                                    </svg>
                                                                                </button></div>
                                                                        </div>
                                                                        @php $k++ @endphp
                                                                    @endforeach
                                                                @else
                                                                    <div class=" row interested_in_html">
                                                                        <div class="col-11">
                                                                            <div class="row ">
                                                                                <div class="col-xl-4 col-lg-12 mb-3">
                                                                                    <label
                                                                                        class="form-label">
                                                                                        {{ __('profile.Category') }}</label>
                                                                                    <select data-cloneid="0" class="form-select addvalidation productcategory"
                                                                                            name="ProductCategory[]" id="product_cat_id0">
                                                                                        <option value="">{{__('profile.select_category')}}</option>
                                                                                        @foreach ($category as $cat)
                                                                                            <option
                                                                                                value="{{ $cat->id }}">
                                                                                                {{ $cat->name }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                        <span class="invalid-feedback d-block"></span>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-xl-4 col-lg-6 mb-3">
                                                                                    <label
                                                                                        class="form-label">{{ __('profile.Annual_Consumption') }}</label>
                                                                                    <input data-cloneid="0" type="number"
                                                                                           class="form-control addvalidation annualconsumption"
                                                                                           name="ProductAnnualConsumption[]"
                                                                                           placeholder="0" data-parsley-type="number" min="0">
                                                                                    <span class="invalid-feedback d-block"></span>
                                                                                </div>
                                                                                <div class="col-xl-4 col-lg-6 mb-3">
                                                                                    <label
                                                                                        class="form-label">{{ __('profile.Unit') }}</label>
                                                                                    <select data-cloneid="0" class="form-select addvalidation unit"
                                                                                            name="ProductUnit[]">
                                                                                        <option value="">{{ __('profile.select_unit') }}</option>
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
                                                                        </div>
                                                                        <div class="col-1"><button
                                                                                class="btn btn-trash border trashicon hidden"><svg
                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                    width="17.5" height="20"
                                                                                    viewBox="0 0 17.5 20">
                                                                                    <path id="icon_trash"
                                                                                          d="M16.875,1.25H12.187L11.82.519A.938.938,0,0,0,10.98,0H6.516a.927.927,0,0,0-.836.52l-.367.73H.625A.625.625,0,0,0,0,1.875v1.25a.625.625,0,0,0,.625.625h16.25a.625.625,0,0,0,.625-.625V1.875A.625.625,0,0,0,16.875,1.25ZM2.078,18.242A1.875,1.875,0,0,0,3.949,20h9.6a1.875,1.875,0,0,0,1.871-1.758L16.25,5h-15Z"
                                                                                          transform="translate(0 0)" />
                                                                                </svg>
                                                                            </button></div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div id="companyYearlyContainer"></div>
                                                        </div>
                                                    </div>


                                                </div>
                                                <div class="col-12 text-end">

                                                    <a data-boolean="false" class="btn btn-primary px-4 py-2 mb-1" href="javascript:void(0)"
                                                       id="userCompanyDetailsBtn"><img
                                                            src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}"
                                                            alt="Post Requirement" class="pe-1">
                                                        {{ __('profile.save_changes') }}</a>
                                                </div>
                                            </div>
                                        </div>

                                    </form>

                                </div>
                                {{--  Invite supplier and friend popup  --}}
                                <div class="tab-pane fade" id="invite_supplier" role="tabpanel" aria-labelledby="invite_supplier_tab">
                                    <div class="row mx-0">
                                        <div class="col-md-12 p-4 mt-md-2">
                                            <div class="mb-3 d-flex w-100 align-item-center">
                                            <h5>{{ __('profile.invite_buyer_supplier') }}</h5>
                                                <button type="button" class="btn btn-success btn-sm plusicon ms-auto" style="" data-bs-toggle="modal" data-bs-target="#addinvitesupplier">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="12" height="12" viewBox="0 0 18 18"><defs><clipPath id="b"><rect width="18" height="18"/></clipPath></defs><g id="a" clip-path="url(#b)"><g transform="translate(0.01 0.005)"><path d="M64.422,231.5a.859.859,0,0,1-.592-.957c.032-.672-.025-1.354.073-2.015a5.092,5.092,0,0,1,3.2-4.128,4.919,4.919,0,0,1,2-.394c1.487,0,2.974-.007,4.461,0a5.224,5.224,0,0,1,5.142,4.117c.054.232.084.469.126.7V230.9a.883.883,0,0,1-.6.6Z" transform="translate(-60.839 -213.506)"/><path d="M132.914,0c.279.058.564.1.837.176a4.492,4.492,0,1,1-1.746-.145A1.159,1.159,0,0,0,132.141,0Z" transform="translate(-122.022)"/><path d="M0,114.805a.86.86,0,0,1,.957-.593c.421.019.843,0,1.292,0v-.175c0-.427,0-.855,0-1.282a.751.751,0,1,1,1.5-.007c.007.479,0,.959,0,1.465h.2c.427,0,.855,0,1.282,0a.753.753,0,0,1,.737.543.719.719,0,0,1-.311.825,1.049,1.049,0,0,1-.463.126c-.473.016-.946.006-1.442.006v.253c0,.4,0,.8,0,1.195a.752.752,0,1,1-1.5,0c0-.474,0-.948,0-1.446H1.9c-.322,0-.645-.014-.966,0a.831.831,0,0,1-.93-.6Z" transform="translate(0 -106.724)"/><path d="M371.25,371.847a.883.883,0,0,0,.6-.6v.6Z" transform="translate(-353.858 -353.858)" fill="#fff"/></g></g></svg>  {{ __('admin.invite') }}
                                                </button>
                                            </div>
                                            {{-- model open Add --}}
                                            <div class="modal fade" data-bs-backdrop="static" id="addinvitesupplier" tabindex="-1" aria-labelledby="addinvitesupplier" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="php artisan queue:work">{{ __('admin.invite_friend') }}</h5>
                                                            <button type="button" class="btn-close" id="closeInviteSupplier" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row floatlables pt-0 invite_front_section">

                                                                <form id="inviteSupplierForm" enctype="multipart/form-data" class="form-group row g-3"
                                                                      data-parsley-validate>
                                                                    @csrf
                                                                    <div class="col-sm-12 mb-3 mt-0">
                                                                        <div class="input-group">
                                                                            <div class="flex-grow-1">
                                                                                <input type="email" class="form-control form-control-sm py-2" name="user_email" id="user_email" placeholder="Email"
                                                                                       aria-label="Email" aria-describedby="button-addon2" data-parsley-uniqueemailuser required>
                                                                            </div>
                                                                            <select class="form-select form-select-sm py-2" name="role_id" id="role_id">
                                                                                <option value="2">{{ __('admin.as_a_buyer') }}</option>
                                                                                <option value="3">{{ __('admin.as_a_supplier') }}</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary btn-sm" id="saveInviteSupplierBtn"><img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}" alt="Save Changes" class="pe-1">{{ __('admin.invite') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- list view --}}
                                            <div class="table_payment table-responsive mt-3">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th hidden>{{ __('admin.id')}}</th>
                                                        <th width="50%">{{ __('admin.email')}}</th>
                                                        <th>{{ __('admin.type')}}</th>
                                                        <th>{{ __('admin.invited_by')}}</th>
                                                        <th class="text-center">{{ __('admin.status') }}</th>
                                                        <th>{{ __('admin.date') }}</th>
                                                        <th class="text-center">{{ __('admin.action')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if(isset($inviteSupplier) && sizeof($inviteSupplier) > 0)
                                                        @foreach($inviteSupplier as $supplier)
                                                            <tr>
                                                                <td hidden>{{ $supplier->id }}</td>
                                                                <td>{{ $supplier->user_email }}</td>
                                                                <td>
                                                                    @if($supplier->role_id == 2){{ __('admin.buyer') }}
                                                                    @else{{  __('admin.supplier') }}
                                                                    @endif
                                                                </td>
                                                                <td>{{ getUserName($supplier->added_by) }}</td>
                                                                @if($supplier->status == 0)<td class="text-center"><small class="badge bg-warning text-dark">{{ __('admin.pending') }}</small></td>
                                                                @elseif($supplier->status == 1)<td class="text-center"><small class="badge bg-success">{{ __('admin.active') }}</small></td>
                                                                @else<td class="text-center"><small class="badge bg-danger">{{ __('admin.link_expired')}}</small></td>
                                                                @endif
                                                                <td>{{ date('d-m-Y',strtotime($supplier->date)) }}</td>
                                                                <td class="text-center">
                                                                    @if ($supplier->status != 1)
                                                                        <a href="javascript:void(0)" id="resendInviteBuyer_{{ $supplier->id }}" class="resendInviteBuyer ps-2 me-1" data-toggle="tooltip" ata-placement="top" title="{{__('admin.resend')}}"><img height="12px" src="{{ URL::asset('front-assets/images/icons/icon_resend.png') }}"></a>
                                                                    @endif
                                                                    @if ($supplier->status != 1)
                                                                        <a href="" class="px-2 editInviteSupplierData" data-id="{{ $supplier->id }}" data-bs-toggle="modal"  data-bs-toggle="tooltip" data-bs-placement="top"   title="{{__('admin.edit')}}"><img height="12px" src="{{ URL::asset('front-assets/images/icons/icon_edit_add.png') }}"></a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="5" class="text-center">{{ __('dashboard.no_data_found') }}</td>
                                                        </tr>
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                            {{-- model open Edit --}}
                                            <div class="modal fade" data-bs-backdrop="static" id="editinvitesupplier" tabindex="-1" aria-labelledby="editinvitesupplier" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editinvitesupplier">Edit Invited</h5>
                                                            <button type="button" class="btn-close" id="closeInviteSupplier" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row floatlables pt-0 invite_front_section">

                                                                <form id="inviteSupplierFormEdit" enctype="multipart/form-data" class="form-group row g-3"
                                                                      data-parsley-validate>
                                                                    @csrf
                                                                    <input type="hidden" name="id" id="invite_supplier_editid">
                                                                    <div class="col-sm-12 mb-3 mt-0">
                                                                        <div class="input-group">
                                                                            <div class="flex-grow-1">
                                                                                <input type="email" class="form-control form-control-sm py-2" name="user_email" id="user_email_edit" placeholder="Email"
                                                                                       aria-label="Email" aria-describedby="button-addon2" data-parsley-uniqueemailuseredit required>
                                                                            </div>
                                                                            <select class="form-select form-select-sm py-2" name="role_id" id="role_id_edit">
                                                                                <option value="2">{{ __('admin.as_a_buyer') }}</option>
                                                                                <option value="3">{{ __('admin.as_a_supplier') }}</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary btn-sm" id="updateInviteSupplierBtn" data-boolean="false"><img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}" alt="Save Changes" class="pe-1">{{ __('profile.save_changes') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {{--  End Invite supplier and friend popup  --}}
                                <!--begin:User Bank UI-->
                                <div class="tab-pane fade" id="userbank" role="tabpanel" aria-labelledby="userbank">

                                    <div class="row mx-0">
                                        <div class="col-md-12 p-4 mt-md-2">
                                            <div class="mb-3 d-flex w-100 align-item-center">
                                                <h5>{{ __('order.bank_details') }}</h5>
                                                <button type="button" class="btn btn-success btn-sm plusicon ms-auto" style="" data-bs-toggle="modal" data-bs-target="#userBankModal" id="addUserBankDetail">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 20 20">
                                                        <path id="icon_plus_pro" d="M18.571,39.857H12.143V33.429A1.429,1.429,0,0,0,10.714,32H9.286a1.429,1.429,0,0,0-1.429,1.429v6.429H1.429A1.429,1.429,0,0,0,0,41.286v1.429a1.429,1.429,0,0,0,1.429,1.429H7.857v6.429A1.429,1.429,0,0,0,9.286,52h1.429a1.429,1.429,0,0,0,1.429-1.429V44.143h6.429A1.429,1.429,0,0,0,20,42.714V41.286A1.429,1.429,0,0,0,18.571,39.857Z" transform="translate(0 -32)"></path>
                                                    </svg> {{ __('profile.add') }}
                                                </button>
                                            </div>
                                            <div class="table_payment mt-3">
                                                <!--begin: User Bank Table-->
                                                <table class="table">
                                                    <thead>
                                                    <th class="w-auto text-nowrap">{{ __('profile.bank_logo') }}</th>
                                                    <th>{{ __('profile.bank_name') }}</th>
                                                    <th>{{ __('profile.bank_code') }}</th>
                                                    <th>{{ __('profile.bank_account_holder_name') }}</th>
                                                    <th>{{ __('profile.bank_account_number') }}</th>
                                                    <th>{{ __('profile.primary_secondary') }}</th>
                                                    <th class="text-nowrap">{{ __('profile.action') }}</th>
                                                    </thead>
                                                    <tbody>

                                                    @if(!empty($buyer_bank_list) && sizeof($buyer_bank_list) > 0)
                                                        @foreach($buyer_bank_list as $bank)
                                                            <tr>

                                                                <td>@if(!empty($bank->getAvailableBank()->logo)) <img src="{{asset($bank->getAvailableBank()->logo)}}" style="height: 30px;width: 30px;" alt="Bank Icon"> @endif</td>
                                                                <td>@if(!empty($bank->getAvailableBank())) {{ $bank->getAvailableBank()->name  }} @endif</td>
                                                                <td>@if(!empty($bank->getAvailableBank())) {{ $bank->getAvailableBank()->code  }} @endif</td>
                                                                <td>{{$bank->account_holder_name}}</td>
                                                                <td>{{$bank->account_number}}</td>

                                                                <td class="text-center" >
                                                                    <div class="form-check form-switch" style="display: inline-table;">
                                                                        <input class="form-check-input isPrimaryCheck" name="isPrimaryCheck" type="checkbox" role="switch" @if($bank->is_primary > 0) checked @endif data-id="{{ $bank->id }}">
                                                                    </div>
                                                                </td>
                                                                <td class="text-nowraplogobanner_section d-flex align-items-center justify-content-center w-100 p-3">
                                                                    <a href="javascript:void(0)" class="px-2 editUserBankDetails" data-id="{{ $bank->id }}" data-toggle="tooltip" ata-placement="top" title="Edit"><img height="12px" src="{{ URL::asset('front-assets/images/icons/icon_edit_add.png') }}"></a>
                                                                    <a href="javascript:void(0)" class="px-2 deleteUserBankDetails" data-id="{{ $bank->id }}" data-toggle="tooltip" ata-placement="top" title="Delete"><img height="12px" src="{{ URL::asset('front-assets/images/icons/icon_delete_add.png') }}"></a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td class="text-center" colspan="8">{{ __('dashboard.no_data_found') }}</td>
                                                        </tr>
                                                    @endif
                                                    </tbody>
                                                </table>
                                                <!--end: User Bank Table-->

                                                <!--begin: User Bank Add Model -->
                                                <form name="userBankDetailsForm" id="userBankDetailsForm" data-parsley-validate  autocomplete="off" method="POST" action="{{ url('/bank-details') }}">
                                                    @csrf
                                                    <div class="modal fade" id="userBankModal" tabindex="-1" aria-labelledby="userBankModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                            <div class="modal-content radius_1 shadow-lg">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="userBankModalTitle">{{ __('profile.add_bank') }}</h5>
                                                                    <button type="button" class="btn-close" id="closeUserBank" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body ">
                                                                    <div class="row floatlables pt-3">
                                                                        <div class="col-sm-6 mb-4">
                                                                            <label class="form-label">{{ __('profile.bank_name') }}<span style="color:red">*</span></label>

                                                                            <div class="d-flex">
                                                                                <select class="form-select" name="bankName" id="bankName" required >
                                                                                    <option value="">{{__('profile.select_bank')}}</option>
                                                                                    @foreach($bank_list as $bank)
                                                                                        <option value="{{ $bank->id }}" data-code="{{ !empty($bank->code) ? $bank->code : ''}}" data-logo="{{ !empty($bank->logo) ? $bank->logo : ''}}" data-code="{{ !empty($bank->code) ? $bank->code : ''}}" >{{ $bank->name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                <div class="p-1 pe-1 pt-2 ps-2"><img src="{{ URL('assets/icons/bank.png') }}" height="20px"
                                                                                                            width="20px" alt="bank Logo" id="bank-logo"></div>
                                                                            </div>
                                                                            <span id="bankNameError" class="text-danger"></span>

                                                                        </div>
                                                                        <div class="col-sm-6 mb-4">
                                                                            <label for="" class="form-label">{{ __('profile.bank_code') }}</label>
                                                                            <input type="text" name="bankCode" class="form-control" id="bankCode" placeholder="XXXXXXXXXX" disabled>
                                                                        </div>
                                                                        <div class="col-sm-6 mb-4">
                                                                            <label for="" class="form-label">{{ __('profile.bank_account_holder_name') }}<span style="color:red">*</span></label>
                                                                            <input type="text" name="bankAccountHolderName" class="form-control input-alpha" placeholder="Jhon Doe" id="bankAccountHolderName" required>
                                                                            <span id="bankAccountHolderNameError" class="text-danger"></span>
                                                                        </div>
                                                                        <div class="col-sm-6 mb-4">
                                                                            <label for="" class="form-label">{{ __('profile.bank_account_number') }}<span style="color:red">*</span></label>
                                                                            <input type="text" name="bankAccountNumber" class="form-control input-number" placeholder="XXXXXXXXXXXXXXXX" id="bankAccountNumber" required >
                                                                            <span id="bankAccountNumberError" class="text-danger"></span>
                                                                        </div>
                                                                        <div class="col-sm-12 mb-4">
                                                                            <label for="" class="form-label">{{ __('profile.description') }}</label>
                                                                            <textarea type="text" name="bankDescription" cols="30" rows="3" class="form-control input-filter" placeholder="{{ __('profile.description') }}" id="bankDescription" ></textarea>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="form-check" style="padding-left: 0.5em;">
                                                                                <input class="form-check-input" style="margin-left: 0px;"
                                                                                       type="checkbox" value="1" id="isPrimary" name="isPrimary" checked>
                                                                                <label class="form-check-label"
                                                                                       style="margin-left: 15px; position: relative;"
                                                                                       for="isPrimary"> {{ __('profile.set_as_primary') }}
                                                                                </label>
                                                                            </div>
                                                                            <span id="isPrimaryError" class="text-danger"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn plusicon btn-sm btn-primary" id="saveUserBank">
                                                                        <img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}" alt="Post Requirement" class="pe-1">
                                                                        {{ __('profile.save') }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <!--end: User Bank Add Model -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end:User Bank UI-->

                                    <!-- Preferred Supplier table -->
                                    <div class="tab-pane fade" id="preferredSuppliers" role="tabpanel" aria-labelledby="preferredSuppliers">
                                        <div class="row mx-0">
                                            <div class="col-md-12 p-4 mt-md-2">
                                            <div class="mb-3 d-flex w-100 align-item-center">
                                                <h5>{{ __('profile.preferred_suppliers') }}</h5>
                                            </div>
                                                <div class="table_payment mt-3">
                                                    <table class="table">
                                                        <thead>
                                                            <th class="w-auto text-nowrap">{{ __('home.company') }}</th>
                                                            <th>{{ __('profile.Email') }}</th>
                                                            <th>{{ __('admin.categories') }}</th>
                                                            <th class="text-center">{{ __('profile.status') }}</th>
                                                            {{-- <th>{{ __('profile.created_date') }}</th>  --}}
                                                            <th class="text-center">{{ __('profile.action') }}</th>
                                                        </thead>
                                                        <tbody>
                                                            @if(isset($preferredSuppliers) && sizeof($preferredSuppliers) > 0)
                                                                @foreach($preferredSuppliers as $supplier)
                                                                    <tr>
                                                                        <td class="w-auto">{{ $supplier->companyName }}</td>
                                                                        <td>{{ $supplier->contact_person_email }}</td>
                                                                        <td class="text-start">
                                                                            @if(isset($supplier->interested_in) && !empty($supplier->interested_in))
                                                                                @foreach(explode(",",$supplier->interested_in) as $supp_cat)
                                                                                    <small class="badge category-tags"> {{ $supp_cat }} </small>
                                                                                @endforeach
                                                                            @else
                                                                                <small> --- </small>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-center">
                                                                            @if((isset($supplier->is_active) && $supplier->is_active == 1))
                                                                                <small class="badge bg-success">Active</small>
                                                                            @else
                                                                                <small class="badge bg-danger">Inactive</small>
                                                                            @endif
                                                                        </td>
                                                                        {{-- <td></td>  --}}
                                                                        <td class="text-end me-2">
                                                                            <div class="form-check form-switch pt-1" style="display: inline-table;">
                                                                                <input class="form-check-input isSupplierActive" name="isSupplierActive" type="checkbox" role="switch" data-id="{{ $supplier->preferredSuppId }}" value="{{$supplier->is_active == 1 ? 1 : 0 }}" title="{{ $supplier->is_active == 1 ? __('admin.active') : __('admin.inactive')  }}" @if($supplier->is_active == 1) checked @endif >
                                                                            </div>

                                                                            <a class="px-2 deleteIcon deletePreferredSupplier" id="deleteSupplierData" data-id="{{ $supplier->preferredSuppId }}" data-toggle="tooltip" ata-placement="top" title="{{ __('admin.delete') }}"><img height="12px" src="{{ URL::asset('front-assets/images/icons/icon_delete_add.png') }}"></a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td colspan="5" class="text-center">{{ __('dashboard.no_data_found') }}</td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End -->

                                    <div class="tab-pane fade" id="usertabs" role="tabpanel" aria-labelledby="usertabs">
                                        <div class="row mx-0">
                                            <div class="col-md-12 p-4 mt-md-2">
                                                <div class=" d-flex w-100 justify-content-end">
                                                    <button type="button" class="btn btn-success btn-sm plusicon" style="" data-bs-toggle="modal" data-bs-target="#inviteUserModal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 20 20">
                                                            <path id="icon_plus_pro" d="M18.571,39.857H12.143V33.429A1.429,1.429,0,0,0,10.714,32H9.286a1.429,1.429,0,0,0-1.429,1.429v6.429H1.429A1.429,1.429,0,0,0,0,41.286v1.429a1.429,1.429,0,0,0,1.429,1.429H7.857v6.429A1.429,1.429,0,0,0,9.286,52h1.429a1.429,1.429,0,0,0,1.429-1.429V44.143h6.429A1.429,1.429,0,0,0,20,42.714V41.286A1.429,1.429,0,0,0,18.571,39.857Z" transform="translate(0 -32)"></path>
                                                        </svg> {{ __('profile.add') }}
                                                    </button>
                                                </div>
                                                <div class="table_payment table-responsive mt-3">

                                                    <table class="table">
                                                        <thead>
                                                            <!-- <th>{{ __('profile.username') }}</th> -->
                                                            <th class="w-auto text-nowrap">{{ __('profile.first_name') }}</th>
                                                            <th class="text-nowrap">{{ __('profile.last_name') }}</th>
                                                            <th>{{ __('profile.Email') }}</th>
                                                            <th>{{ __('profile.designation') }}</th>
                                                            <th class="text-center">{{ __('profile.status') }}</th>
                                                            <th>{{ __('profile.mobile') }}</th>
                                                            <th class="text-nowrap">{{ __('profile.created_date') }}</th>
                                                            <th>{{ __('profile.role') }}</th>
                                                            <th class="text-center">{{ __('profile.action') }}</th>
                                                        </thead>
                                                        <tbody>
                                                            @if(isset($invited_users) && sizeof($invited_users) > 0)
                                                                @foreach($invited_users as $user)
                                                                <tr>
                                                                    @php
                                                                        $type = 'Frontend User';
                                                                        if ($user->role_id == "1") {
                                                                            $type= 'Backend User';
                                                                        }
                                                                        if($status_class = $user->is_active == 1 ? 'badge bg-success' : 'badge bg-danger bg-opacity-25 text-dark');
                                                                        if($status = $user->is_active == 1 ? 'Active' : 'Verification Pending');
                                                                    @endphp
                                                                    <!-- <td>{{ $type }}</td> -->
                                                                    <td>{{$user->firstname}}</td>
                                                                    <td>{{$user->lastname}}</td>
                                                                    <td>{{$user->email}}</td>
                                                                    <td>{{$user->user_designation->name ?? ''}}</td>
                                                                    <td class="text-center"><small class="{{$status_class}}">{{$status}}</small></td>
                                                                    <td>{{$user->mobile}}</td>
                                                                    <td>{{ date('d-m-Y',strtotime($user->created_at)) }}</td>
                                                                    <td>@if(getRolePermissionAttribute($user->id)['custom_role_id'] != null)
                                                                        <a class="p-0 roleModalView text-black"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#rolePopUp" data-id="{{ getRolePermissionAttribute($user->id)['custom_role_id'] != null ? getRolePermissionAttribute($user->id)['custom_role_id'] : ''  }}" title="{{__('admin.view')}}">{{ getRolePermissionAttribute($user->id)['role'] != null ? getRolePermissionAttribute($user->id)['role'] : 'Approval / Consultant'  }}</a></td>
                                                                        @else
                                                                            {{ getRolePermissionAttribute($user->id)['role'] != null ? getRolePermissionAttribute($user->id)['role'] : 'Approval / Consultant'  }}
                                                                        @endif

                                                                        <td class="text-nowrap text-center">
                                                                        <a class="px-2 editInvitedUserData" data-id="{{ $user->id }}" data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}"><img height="12px" src="{{ URL::asset('front-assets/images/icons/icon_edit_add.png') }}"></a>
                                                                        <a href="" class="px-2 deleteInvitedUser" data-id="{{ $user->id }}" data-toggle="tooltip" ata-placement="top" title="{{__('admin.delete')}}"><img height="12px" src="{{ URL::asset('front-assets/images/icons/icon_delete_add.png') }}"></a>

                                                                        <!--begin: Edit user permission -->
                                                                        @if(getRolePermissionAttribute($user->id)['role'] != null)
                                                                            {{-- <a href="javascript:void(0);" class="px-2 permissionInvitedUser" data-id="{{ \Crypt::encrypt($user->id) }}" data-toggle="tooltip" ata-placement="top" title="Delete"><img height="12px" src="{{ URL::asset('front-assets/images/icons/icon_lock_pro.svg') }}"></a> --}}
                                                                        @endif
                                                                        <!--end: Edit user permission -->

                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td class="text-center" colspan="8">{{ __('dashboard.no_data_found') }}</td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>

                                                    <form name="inviteUserForm" id="inviteUserForm" data-parsley-validate  autocomplete="off">
                                                        @csrf
                                                        <div class="modal fade" id="inviteUserModal" tabindex="-1" aria-labelledby="inviteUserModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content radius_1 shadow-lg">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="">{{ __('profile.add_user') }}</h5>
                                                                        <button type="button" class="btn-close" id="closeInviteUser" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body ">
                                                                        <div class="row floatlables pt-3">
                                                                            <div class="col-sm-6 mb-4">
                                                                                <label for="" class="form-label">{{ __('profile.first_name') }}<span style="color:red">*</span></label>
                                                                                <input type="text" name="firstName" class="form-control" id="user_fn" required>
                                                                            </div>
                                                                            <div class="col-sm-6 mb-4">
                                                                                <label for="" class="form-label">{{ __('profile.last_name') }}<span style="color:red">*</span></label>
                                                                                <input type="text" name="lastName" class="form-control" id="user_ln" required>
                                                                            </div>
                                                                            <div class="col-sm-6 mb-4">
                                                                                <label for="" class="form-label">{{ __('profile.Email') }}<span style="color:red">*</span></label>
                                                                                <input type="email" name="email" class="form-control" id="user_email" required>
                                                                                <span id="showEmailError" class="text-danger"></span>
                                                                            </div>
                                                                            <div class="col-sm-6 mb-4">
                                                                                <label for="" class="form-label">{{ __('profile.mobile_number') }}<span style="color:red">*</span></label>
                                                                                <input type="tel" name="mobile" class="form-control" id="user_mobile"  data-parsley-type="digits"  data-parsley-length="[9, 16]" data-parsley-length-message="It should be between 9 and 16 digit." placeholder="XXXXXXXXXXX" required>
                                                                            </div>
                                                                            <div class="col-sm-6 mb-4">
                                                                                <label for="" class="form-label">{{ __('profile.designation') }}<span style="color:red">*</span></label>
                                                                                <select name="designation" id="user_designation" class="form-select" required>
                                                                                    <option value="">{{__('profile.select_designation')}}</option>
                                                                                    @foreach ($designations as $designation)
                                                                                        <option value="{{ $designation->id }}"> {{ $designation->name }} </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-sm-6 mb-4">
                                                                                <label for="" class="form-label">{{ __('profile.department') }}<span style="color:red">*</span></label>
                                                                                <select name="department" id="user_dept" class="form-select" required>
                                                                                    <option value="">{{__('profile.select_department')}}</option>
                                                                                    @foreach ($departments as $department)
                                                                                        <option value="{{ $department->id }}"> {{ $department->name }} </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label
                                                                                    class="form-label">{{ __('admin.role') }}<span style="color:red">*</span></label>
                                                                                <select name="role" id="role" class="form-select" required>
                                                                                <option value="">{{__('admin.select_role')}}</option>
                                                                                    @foreach ($customRoles as $role)
                                                                                        <option
                                                                                            {{ $user->department == $role->id ? 'selected' : '' }}
                                                                                            value="{{ $role->id }}">
                                                                                            {{ $role->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                    <option value="Approver">{{ __('profile.approver_consultant') }}</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn plusicon btn-sm btn-primary" id="saveInviteUserBtn">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 20 20">
                                                                                <path id="icon_plus_pro"
                                                                                d="M18.571,39.857H12.143V33.429A1.429,1.429,0,0,0,10.714,32H9.286a1.429,1.429,0,0,0-1.429,1.429v6.429H1.429A1.429,1.429,0,0,0,0,41.286v1.429a1.429,1.429,0,0,0,1.429,1.429H7.857v6.429A1.429,1.429,0,0,0,9.286,52h1.429a1.429,1.429,0,0,0,1.429-1.429V44.143h6.429A1.429,1.429,0,0,0,20,42.714V41.286A1.429,1.429,0,0,0,18.571,39.857Z"
                                                                                transform="translate(0 -32)" />
                                                                            </svg> {{ __('profile.Add') }}
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Remove -->

                                    <!-- Edit invited user details -->
                                    <div class="modal fade" id="inviteUserEditModal" tabindex="-1" aria-labelledby="inviteUserEditModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content radius_1 shadow-lg">

                                            </div>
                                        </div>
                                    </div>
                                    <!-- Popup End -->

                                    <div class="tab-pane fade d-none" id="approval" role="tabpanel" aria-labelledby="approval">
                                        <div class="row mx-0">
                                            <div class="col-md-12 p-4 mt-md-2">
                                                <div class="">
                                                    <div class="form-check form-switch">
                                                        <label class="form-check-label fw-bold" for="flexSwitchCheckDefault">{{ __('profile.required_approval_process')}} </label>
                                                        <input name="" class="form-check-input" type="checkbox" role="switch" data-boolean="false" id="flexSwitchCheckDefault">
                                                    </div>
                                                    <hr>
                                                </div>
                                                <div class=" d-flex w-100 justify-content-end">
                                                    <button type="button" class="btn btn-success btn-sm plusicon btnDisable" style="" data-bs-toggle="modal" data-bs-target="#addmemberModal">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 20 20">
                                                        <path id="icon_plus_pro" d="M18.571,39.857H12.143V33.429A1.429,1.429,0,0,0,10.714,32H9.286a1.429,1.429,0,0,0-1.429,1.429v6.429H1.429A1.429,1.429,0,0,0,0,41.286v1.429a1.429,1.429,0,0,0,1.429,1.429H7.857v6.429A1.429,1.429,0,0,0,9.286,52h1.429a1.429,1.429,0,0,0,1.429-1.429V44.143h6.429A1.429,1.429,0,0,0,20,42.714V41.286A1.429,1.429,0,0,0,18.571,39.857Z" transform="translate(0 -32)"></path>
                                                    </svg> {{ __('profile.add_member') }}
                                                    </button>
                                                </div>
                                                <input type="hidden" id="configUsersCount" value="{{isset($approvalConfigUsers) ? count($approvalConfigUsers) : 0}}" />
                                                <div class="table_payment mt-3">
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <th class="w-auto text-nowrap">{{ __('profile.first_name') }}</th>
                                                            <th>{{ __('profile.last_name') }}</th>
                                                            <th>{{ __('profile.Email') }}</th>
                                                            <th>{{ __('profile.designation') }}</th>
                                                            <th>{{ __('profile.type') }}</th>
                                                            <th>{{ __('profile.added_date') }}</th>
                                                            <th class="text-center">{{ __('profile.action') }}</th>
                                                        </thead>
                                                        <tbody>
                                                            @if(isset($approvalConfigUsers) && sizeof($approvalConfigUsers) > 0)
                                                                @foreach($approvalConfigUsers as $user)
                                                                <tr>
                                                                    <td>{{$user->firstname}}</td>
                                                                    <td>{{$user->lastname}}</td>
                                                                    <td>{{$user->email}}</td>
                                                                    <td>{{$user->user_designation->name ?? ''}}</td>
                                                                    <td>{{$user->user_type}}</td>
                                                                    <td>{{ date('d-m-Y',strtotime($user->app_created_at)) }}</td>
                                                                    @if(Auth::user()->id != $user->id)
                                                                    <td class="text-center">
                                                                        <a class="px-2 editIcon editAppConfigUser" id="editBtn1" data-id="{{ $user->id }}" data-toggle="tooltip" ata-placement="top" title="Edit"><img height="12px" src="{{ URL::asset('front-assets/images/icons/icon_edit_add.png') }}"></a>

                                                                        <a class="px-2 deleteIcon deleteAppConfigUser" id="deleteBtn1" data-id="{{ $user->id }}" data-toggle="tooltip" ata-placement="top" title="Delete"><img height="12px" src="{{ URL::asset('front-assets/images/icons/icon_delete_add.png') }}"></a>
                                                                    </td>
                                                                    @endif
                                                                </tr>
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td class="text-center" colspan="7">{{ __('dashboard.no_data_found') }}</td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>

                                                    <form name="approvalConfigForm" id="approvalConfigForm" data-parsley-validate autocomplete="off">
                                                        @csrf
                                                        <div class="modal fade" id="addmemberModal" tabindex="-1" aria-labelledby="addmemberModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content radius_1 shadow-lg">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="">{{ __('profile.add_approval_process') }}</h5>
                                                                        <button type="button" id="closeApprovalConfig" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row floatlables pt-3">
                                                                            <div class="col-sm-12 mb-4" id="product_category_block">
                                                                                <select id='member_approval' name="member_id" data-id="" class="form-control" required>
                                                                                    <option disabled selected>{{ __('profile.Email') }}</option>
                                                                                    @if(isset($invited_active_users))
                                                                                        @foreach($invited_active_users as $user)
                                                                                            <option data-category-id="{{ $user->id }}" data-category="{{ $user->email }}" value="{{ $user->id }}">{{ $user->email }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    @endif
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-sm-12  d-flex">
                                                                                <div class="form-check me-3">
                                                                                    <input class="form-check-input" type="radio" name="user_type" id="gridRadios1" value="Approver" checked>
                                                                                    <label class="form-check-label" for="gridRadios1">{{ __('profile.approver') }}</label>
                                                                                </div>
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" type="radio" name="user_type" id="gridRadios2" value="Consulted">
                                                                                    <label class="form-check-label" for="gridRadios2">{{ __('profile.Consulted') }}</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <a data-boolean="false" class="btn btn-primary px-4 py-2 mb-1" id="saveApprovalConfigBtn" href="javascript:void(0)">
                                                                            <img  src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}" alt="Save" class="pe-1"> {{ __('admin.save') }}
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Approval Configuration User Details -->
                                    <div class="modal fade" id="approvalUserEditModal" tabindex="-1" aria-labelledby="approvalUserEditModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content radius_1 shadow-lg">

                                            </div>
                                        </div>
                                    </div>
                                    <!-- Popup End -->

                                    <!-- On toggle on/off change show message -->
                                    <div class="modal fade" id="toggleOffMsg" tabindex="-1" aria-labelledby="toggleOffMsgLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content radius_1 shadow-lg">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="">{{ __('profile.user_confirmation') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row floatlables pt-3">
                                                        <div>{{ __('profile.config_user_msg') }}</div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('admin.cancel') }}</button>
                                                    <button type="button" class="btn btn-primary">{{ __('admin.ok') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End -->

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="mt-auto pt-3 text-center rowjustify-content-center">
        <div class="col-lg-12 bg-light border-top">
            <div class="py-3">
                <small><span style="font-family: arial ;">&copy;</span>  {{ date("Y") }} {{__('dashboard.all_rights_reserved')}}</small>
            </div>
        </div>
    </div>

    <!-- Upload profile image modal -->
    <div class="customscroll showpop">
        <div class="modal fade" id="UploadProfileImageModal" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 60%;">
                <div class="modal-content">
                    <div class="popup-marg">
                        <div class="modal-header">
                            <h5 class="modal-title text-center">{{ __('profile.change_avatar') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <button id="cropProfileImage" class="btn btn-primary crop-profile-picture  mt-0 save-btn save-btn-bg" ><img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}"  alt="Post Requirement" class="pe-1">Crop & Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Upload profile image modal -->

        <!-- ----------Role Click Popup --------------- -->
        <!-- Modal -->
        <div class="modal fade" id="rolePopUp" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered rolemodal">
                <div class="modal-content">
                    <div id="rolemodal-body">
                    </div>
                </div>
            </div>
        </div>

        <!-- ------------Role Pop Up- End---------------- -->
        <script type="text/javascript" src="{{ URL::asset('front-assets/js/front/color-picker.js') }}"></script>
<script>
    var $uploadProfileCrop,
    tempProfileFilename,
    rawProfileImg,
    imageProfileId;
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

            if( fileType == 'image' ) {
                console.log('in image');
                reader.onload = function (e) {
                $('#UploadProfileImageModal').modal('show');
                rawProfileImg = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                $("#imgInp").val("");
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
            console.log('jQuery bind complete');
        });
    });
    $('.crop-profile-picture').on('click', function (ev) {

        $uploadProfileCrop.croppie('result', {
            type: 'canvas',
            size: 'viewport',
            quality: 1
        }).then( function (img) {
            // console.log(img);
            $("#userProfilePic").closest('div.ratio.ratio-1x1').show();
            $("#userProfilePic").attr( 'src', img );
            $('#UploadProfileImageModal').modal('hide');
        });
    });
    $(document).ready(function(){
        var numberOfChildCheckBoxes = $('.child-checkbox').length;
        var checkedChildCheckBox = $('.child-checkbox:checked').length;
        if (checkedChildCheckBox == numberOfChildCheckBoxes)
            $(".select-all-checkbox").prop('checked', true);
        else
            $(".select-all-checkbox").prop('checked', false);

        //Approval Member
        $('#member_approval').select2({
            dropdownParent: $('#product_category_block'),
        });
       datepick();
    });
    //load date picker
    datepick = () => {
        var d = new Date();
        var year = d.getFullYear() - 18;
        maxBirthDate = new Date(d.setYear(year));
        d.setFullYear(year);
        $('.select-date-age').datepicker({
            onSelect: function(date) {
                let selectDateId = $(this).attr('id');
                $('#'+selectDateId).parsley().reset();
                $('#'+selectDateId+'Error').text('')

           },
           dateFormat: "yy-mm-dd",
           changeMonth: true,
           changeYear: true,
           maxDate: maxBirthDate,
           yearRange: '1930:' + year + '', defaultDate: d
       });
        $('#establish_in').datepicker({
            maxDate: new Date(),
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true,
            onSelect: function(date) {
                let selectDateId = $(this).attr('id');$('#'+selectDateId).parsley().reset();$('#'+selectDateId+'Error').text('')
            },
            yearRange: '1900:' + new Date().getFullYear() + '',
        });

    }
    $('#ownership_percentage').keypress(function(event) {
        if($(this).val()>=100)
        {
            event.preventDefault();
        }
        var text = $(this).val();

        if ((text.indexOf('.') != -1) &&
            (text.substring(text.indexOf('.')).length > 2) &&
            (event.which != 0 && event.which != 8) &&
            ($(this)[0].selectionStart >= text.length - 2)) {
            event.preventDefault();
        }
    })
    // function isNumberKey(txt, evt) {
    //     var charCode = (evt.which) ? evt.which : evt.keyCode;
    //     if (charCode == 46) {
    //         //Check if the text already contains the . character
    //         if (txt.value.indexOf('.') === -1) {
    //             return true;
    //         } else {
    //             return false;
    //         }
    //     } else {
    //         if (charCode > 31 &&
    //             (charCode < 48 || charCode > 57))
    //             return false;
    //     }
    //     return true;
    // }
    $(document).on('click', '.select-all-checkbox', function(e) {
        // alert('cdcd');
        var chk = this.checked;
        $('#paymenttermsbody').each(function() {
            $(this).find('tr').each(function() {
                validate = $(this);
                if (validate.is(":hidden")) {
                } else{
                    validate.find('.child-checkbox').prop('checked',chk);
                }
            });
        });
    });
    /*
        * Click on another checkbox can affect the select all checkbox
        */
    $(document).on('click', '.child-checkbox', function(e) {
        var chk = this.checked;
        var tthis = $(this);
        $('#paymenttermsbody').each(function() {
            $(this).find('tr').each(function() {
                validate = $(this);
                if (validate.is(":hidden")) {
                } else{
                    if (validate.find('.child-checkbox:checked').length == validate.find('.child-checkbox').length || !chk) {
                        validate.parents().parents().find('.select-all-checkbox').prop('checked', chk);
                    }

                    // validate.find('.child-checkbox').prop('checked',chk);
                }
            });
        });
    });
    $(document).on('click','.GroupByPayment',function(e){
        var Groupid = $(this).data('id');
        var Groupname = $(this).data('name');
        var termgroupid = $(".termgroupid"+Groupid).attr('data-groupid');

        $("#paymenttermsbody").find('tr').hide();
        if(termgroupid == Groupid){
            $("#groupbyname").text(Groupname);
            $(".termgroupid"+termgroupid).closest('tr').show();

        }else if(Groupid == "all"){
            $("#groupbyname").text("All");

            $("#paymenttermsbody").find('tr').show();
        }
        $('#paymenttermsbody').each(function() {
            var checkval = [];
            $(this).find('tr').each(function() {
                validate = $(this);
                var chk = validate.parents().parents().find('.select-all-checkbox').prop('checked');
                if (validate.is(":hidden")) {
                } else{
                    if (validate.find('.child-checkbox:checked').length == validate.find('.child-checkbox').length ) {
                        checkval.push(true);
                    }
                    else{
                        checkval.push(false);
                    }
                }
                console.log(checkval);
                if(checkval.includes(false)){
                    validate.parents().parents().find('.select-all-checkbox').prop('checked', false);
                }else{
                    validate.parents().parents().find('.select-all-checkbox').prop('checked', true);
                }
            });

        });
    });
    $("body").on('keyup blur','.numeric-dot-only', function() {
        $(this).val($(this).val().replace(/[^0-9.]/g, ''));
    });
    $("body").on('keyup blur','.addvalidation', function() {
        $(this).next("span").remove();
    });

    var loadFile = function(event) {
        var output = document.getElementById('userProfilePic');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };

    var loadCompanyLogo = function(event) {
        var output = document.getElementById('companyLogoPreview');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };

    $(document).ready(function() {
        // $('.nav-tabs .active').text();
        $('#product_intrested').select2({
            dropdownParent: $('#product_intrested_block'),
        });

        $(document).on('click', '#saveLangcurrencymBtn', function(e) {
            var langBoolean = $("#saveLangcurrencymBtn").data('boolean');
            e.preventDefault();
            $("#saveLangcurrencymBtn").attr('data-boolean','false');

            $(".nav-tabs").find('button').attr("data-bs-toggle","tab");
            var tabid = $(this).attr('data-nexttab');

            if ($('#saveLangcurrencyForm').parsley().validate()) {
                var formData = new FormData($('#saveLangcurrencyForm')[0]);
                if (langBoolean){
                    formData.append('changesUserNotification', 1);
                }
                $.ajax({
                    url: "{{ route('profile-update-user-language-currency') }}",
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function(successData) {
                        if (successData.success) {

                            new PNotify({
                                text: "{{ __('profile.lang_details_updated') }}",
                                //text: 'personal details updated',
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 1000
                            });
                            location.reload();
                            buttonclickoncancel(tabid);
                            $("#saveLangcurrencymBtn").removeAttr('data-nexttab');

                        }
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        });
        $(document).on('click', '#saveUserPaymenttermBtn', function(e) {
            var termBoolean = $("#saveUserPaymenttermBtn").data('boolean');
            e.preventDefault();
            $("#saveUserPaymenttermBtn").attr('data-boolean','false');

            $(".nav-tabs").find('button').attr("data-bs-toggle","tab");
            var tabid = $(this).attr('data-nexttab');

            if ($('#saveUserPaymenttermForm').parsley().validate()) {
                var formData = new FormData($('#saveUserPaymenttermForm')[0]);
                if (termBoolean){
                    formData.append('changesUserNotification', 1);
                }
                $.ajax({
                    url: "{{ route('profile-update-user-payment-term-ajax') }}",
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function(successData) {
                        if (successData.success) {

                            new PNotify({
                                text: "{{ __('profile.payment_details_updated') }}",
                                //text: 'personal details updated',
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 1000
                            });
                            buttonclickoncancel(tabid);
                            $("#saveUserPaymenttermBtn").removeAttr('data-nexttab');

                        }
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        });


        //Reset Parsley validation on button close
        $(document).on('click','#closeInviteUser', function(e) {
            $('#inviteUserForm').parsley().reset();
            $('#inviteUserForm')[0].reset();
            $('#showEmailError').hide();
        })

        $(document).on('click','#closeApprovalConfig', function(e) {
            $('#approvalConfigForm').parsley().reset();
            // $('#approvalConfigForm')[0].reset();
        })

        $(document).on('click','#closeInviteSupplier', function(e) {
            $('#inviteSupplierForm').parsley().reset();
            $('#inviteSupplierForm')[0].reset();
            $('#inviteSupplierFormEdit').parsley().reset();
            $('#inviteSupplierFormEdit')[0].reset();
            // $('#showEmailError').hide();
        })

        //Edit invited user details by user id
        $(document).on('click', '.editInvitedUserData', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            if (id) {
                $.ajax({
                    url: "{{ route('user-detail', '') }}" + "/" + id,
                    type: 'GET',
                    success: function(successData) {
                        $('#inviteUserEditModal').find('.modal-content').html(successData.returnHTML);
                        $('#inviteUserEditModal').modal('show');
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        });

        //Delete Invited User
        $(document).on('click', '.deleteInvitedUser', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var id = $(this).attr('data-id');
            swal({
                title: "{{ __('dashboard.are_you_sure') }}?",
                text: "{{ __('dashboard.delete_warning') }}",
                icon: "/assets/images/bin.png",
                buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{ route('delete-invited-user-ajax', '') }}" + "/" + id,
                        type: 'GET',
                        success: function(successData) {
                            new PNotify({
                                text: successData.msg,
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 2000
                            });
                            $('#inviteUserModal').modal('hide');
                            $('#usertabs-tab,#usertabs').addClass('active');
                            setTimeout(function () {
                                location.reload(true);
                            }, 2000);
                        },
                        error: function() {
                            console.log('error');
                        }
                    });
                    setTimeout(function () {
                        location.reload(true);
                    }, 2000);
                }
            });
        });

        //Get Approval Process Value
        setInterval(function() {
            if (JSON.parse(sessionStorage.getItem('profile-lastlocation'))['secondTab'] == 'approval_config') {
                getapprovalProcessValue();
            }
        }, 1000);

        //ekta change code only open approval configer page then run this code other wise not

        function getapprovalProcessValue() {
            var quoteId = $("#quoteIdValue").val();
            // console.log(quoteId,"latest");
            $.ajax({
                url: "{{ route('approval-process-value-ajax') }}",
                type: 'GET',
                success: function(result) {
                    if(result.processValue.approval_process == 1) {
                        $('#flexSwitchCheckDefault').prop('checked',true);
                        $("#flexSwitchCheckDefault").attr('data-boolean','true');
                        $('.btnDisable').prop('disabled', false);
                        $(".editIcon").addClass("editAppConfigUser");
                        $(".deleteIcon").addClass("deleteAppConfigUser");
                        var configUsersCount = $("#configUsersCount").val();
                        if(configUsersCount != 0) {
                            $("#backBtn").remove("changetab").attr("href","{{ route('dashboard') }}");
                        } else {
                            $("#backBtn").addClass("changetab").attr("href","javascript:void(0)");
                            $(".nav-tabs").find('button').removeAttr("data-bs-toggle","tab");
                        }
                    } else {
                        $('#flexSwitchCheckDefault').prop('checked',false);
                        $("#flexSwitchCheckDefault").attr('data-boolean','false');
                        $('.btnDisable').prop('disabled', true);
                        $('.editIcon').removeClass("editAppConfigUser");
                        $('.deleteIcon').removeClass("deleteAppConfigUser");
                        $("#backBtn").remove("changetab").attr("href","{{ route('dashboard') }}");
                    }
                },
                error: function() {
                    console.log('error');
                }
            });
        }

        //Keep checkbox state even after page refresh
        $('#flexSwitchCheckDefault').on('change', function() {
            if($(this).prop("checked") == true) {
                $('#flexSwitchCheckDefault').prop('checked',true);
                $("#flexSwitchCheckDefault").attr('data-boolean','true');
                updateToggleValue(1);
            } else {
                var configUsersCount = $("#configUsersCount").val();
                if(configUsersCount != 0) {
                    swal({
                        title: "{{ __('dashboard.are_you_sure') }}?",
                        text: "{{ __('profile.config_user_msg') }}",
                        icon: "/assets/images/bin.png",
                        buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            // Toggle Off
                            $('#flexSwitchCheckDefault').prop('checked',false);
                            $("#flexSwitchCheckDefault").attr('data-boolean','false');
                            $('#toggleOffMsg').modal('hide');
                            $(".nav-tabs").find('button').attr("data-bs-toggle","tab");
                            updateToggleValue(0);
                        } else {
                            // Toggle On
                            $('#flexSwitchCheckDefault').prop('checked',true);
                            $("#flexSwitchCheckDefault").attr('data-boolean','true');
                            $('#toggleOffMsg').modal('hide');
                            $(".nav-tabs").find('button').attr("data-bs-toggle","tab");
                            updateToggleValue(1);
                        }
                    });
                } else {
                        updateToggleValue(0);
                        $("#flexSwitchCheckDefault").attr('data-boolean','true');
                        buttonclickoncancel("approval_config");
                }
            }
        });

        function updateToggleValue(toggleValue) {
            $.ajax({
                url: "{{ route('update-approval-process-ajax') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "toggleValue": toggleValue
                },
                type: 'POST',
                dataType: "json",
                success: function(successData) {
                    $('#toggleOffMsg').modal('hide');
                    $('.btnDisable').prop('disabled', true);
                    $('.editIcon').removeClass("editAppConfigUser");
                    $('.deleteIcon').removeClass("deleteAppConfigUser");
                    $(".nav-tabs").find('button').attr("data-bs-toggle","tab");
                },
                error: function() {
                    console.log('error');
                }
            });
        }

        //Save user approval configuration data
        $(document).on('click', '#saveApprovalConfigBtn', function(e) {
            var approveBoolean = $("#saveApprovalConfigBtn").data('boolean');
            e.preventDefault();
            $("#saveApprovalConfigBtn").attr('data-boolean','false');

            $(".nav-tabs").find('button').attr("data-bs-toggle","tab");
            var tabid = $(this).attr('data-nexttab');

            if ($('#approvalConfigForm').parsley().validate()) {
                var formData = new FormData($('#approvalConfigForm')[0]);
                if (approveBoolean){
                    formData.append('changesUserNotification', 1);
                }
                $.ajax({
                    url: "{{ route('profile-approval-config-ajax') }}",
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function(successData) {
                        new PNotify({
                            text: successData.msg,
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });
                        $('#addmemberModal').modal('hide');
                        $('#approvalConfigForm').parsley().reset();
                        // $('#approval-tab,#approval').addClass('active');
                        //$('#approvalConfigForm')[0].reset();
                        //$('#member_approval').val('');
                        buttonclickoncancel(tabid);
                        $("#saveApprovalConfigBtn").removeAttr('data-nexttab');
                    },
                    error: function() {
                        console.log('error');
                    }
                });
                setTimeout(function () {
                    location.reload(true);
                }, 2000);
            }
        });

        //Edit approval configuration data by user id
        $(document).on('click', '.editAppConfigUser', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            if (id) {
                $.ajax({
                    url: "{{ route('approval-user-detail', '') }}" + "/" + id,
                    type: 'GET',
                    success: function(successData) {
                        $('#approvalUserEditModal').find('.modal-content').html(successData.returnHTML);
                        $('#approvalUserEditModal').modal('show');
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        });

        //Delete Invited User
        $(document).on('click', '.deleteAppConfigUser', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var id = $(this).attr('data-id');
            swal({
                title: "{{ __('dashboard.are_you_sure') }}?",
                text: "{{ __('dashboard.delete_warning') }}",
                icon: "/assets/images/bin.png",
                buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.delete') }}'],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{ route('delete-config-user-ajax', '') }}" + "/" + id,
                        type: 'GET',
                        success: function(successData) {
                            $('#inviteUserModal').modal('hide');
                            $('#approval-tab,#approval').addClass('active');
                        },
                        error: function() {
                            console.log('error');
                        }
                    });
                }
                setTimeout(function () {
                    location.reload(true);
                }, 1000);
            });
        });

        $(document).on('click', '#saveUserDetailsBtn', function(e) {

            e.preventDefault();
            $("#saveUserDetailsBtn").attr('disabled',true);
            var change = $('#saveUserDetailsBtn').data('boolean');
            $("#saveUserDetailsBtn").attr('data-boolean','false');
            $(".nav-tabs").find('button').attr("data-bs-toggle","tab");
            var tabid = $(this).attr('data-nexttab');

            if ($('#userDetailForm').parsley().validate()) {
                var formData = new FormData($('#userDetailForm')[0]);
                var imgsrc = $(".user_info_photo").find('img').attr('src');
                if(imgsrc){
                    formData.append('user_pic1',imgsrc);
                }

                if(change){
                    formData.append('changesUserNotification', 1);
                }
                $.ajax({
                    url: "{{ route('profile-update-personal-info-ajax') }}",
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                }).done(function(response){
                    if(response.status == 'success'){
                        new PNotify({
                            text: '{{ __('profile.personal_details_updated') }}',
                            //text: 'personal details updated',
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });

                        // var buttonid = $("#"+tabid).find('button').attr('id');
                        // $("#"+buttonid).trigger('click');
                        $(".user_info_photo").find('img').attr('id','userProfilePic');
                        buttonclickoncancel(tabid);
                        $('#file-family_card_image .removeFile, #file-family_card_image .downloadbtn').removeClass('hidden');
                        $('#file-ktp_image .removeFile, #file-ktp_image .downloadbtn').removeClass('hidden');
                        $('#file-ktp_with_selfie_image .removeFile, #file-ktp_with_selfie_image .downloadbtn').removeClass('hidden');
                        $("#saveUserDetailsBtn").removeAttr('data-nexttab');
                    } else {
                        $(".nav-tabs").find('button').removeAttr("data-bs-toggle","tab");
                        $("#saveUserDetailsBtn").attr('data-boolean','true');
                        $.each(response.errors, function(key, value) {
                            if(key == 'user_pic'){
                                $('.user_info_photo').parent().parent().next().find('.invalid-feedback').text(value[0]);
                            }
                        });

                    }
                    $("#saveUserDetailsBtn").attr('disabled',false);
                });

            }
        });

        //Function will be call when we click on any tab with class changetab
        $(document).on('click','.changetab',function(e) {
            setMainLocation();
            var numberOfChildCheckBoxes = $('.child-checkbox').length;
            var checkedChildCheckBox = $('.child-checkbox:checked').length;
            if (checkedChildCheckBox == numberOfChildCheckBoxes)
                $(".select-all-checkbox").prop('checked', true);
            else
                $(".select-all-checkbox").prop('checked', false);

            var imgsrc = $(".user_info_photo").find('img').attr('src');
            var imgid = $(".user_info_photo").find('img').attr('id');
            if(!imgid){
                if(imgsrc){
                    $(".nav-tabs").find('button').removeAttr("data-bs-toggle","tab");
                    $("#saveUserDetailsBtn").attr('data-boolean','true');
                }
            }

            //Get Value of attribute data-boolean
            var comapnydata = $("#userCompanyDetailsBtn").attr('data-boolean');
            var userdata = $("#saveUserDetailsBtn").attr('data-boolean');
            var ppassworddata = $("#changePasswordBtn").attr('data-boolean');
            var langdata = $("#saveLangcurrencymBtn").attr('data-boolean');
            var paymentdata = $("#saveUserPaymenttermBtn").attr('data-boolean');
            var flexSwitchToggle = $("#flexSwitchCheckDefault").attr('data-boolean');
            var configUsersCount = $("#configUsersCount").val();

            //Show popup if the toggle is on and configure user is 0
            if(flexSwitchToggle == 'true' && configUsersCount == 0) {
                swal({
                    title: "{{ __('validation.approval_members') }}",
                    icon: "/assets/images/info.png",
                    buttons: ["{{ __('admin.cancel') }}", "{{ __('admin.yes') }}"],
                    dangerMode: false,
                    closeOnClickOutside: false,
                }).then((changeit) => {
                    if (changeit) {
                        var tabid = $(this).attr('id');
                        //Change toggle status to 1 in "companies" table
                        updateToggleValue(1);
                        $("#flexSwitchCheckDefault").attr('data-boolean','true');
                        $('#addmemberModal').modal('show');
                    } else {
                        //Change toggle status to 0 in "companies" table
                        updateToggleValue(0);
                        $("#flexSwitchCheckDefault").attr('data-boolean','false');
                        //To enable clicks on other tab
                        $(".nav-tabs").find('button').attr("data-bs-toggle","tab");
                        var tabid = $(this).attr('id');
                        if(tabid == "backBtn") {
                            window.location.href = "{{ route('dashboard') }}"
                        } else {
                            buttonclickoncancel(tabid);
                        }

                    }
                });
            }

            if(userdata == 'true' || comapnydata == 'true' || ppassworddata == 'true' || langdata == 'true' || paymentdata == 'true'){
            //    alert('in0');
                swal({
                    title: "{{ __('profile.change_tab') }}",
                    icon: "/assets/images/info.png",
                    buttons: ["{{ __('profile.change_no') }}", "{{ __('profile.change_save') }}"],
                    dangerMode: false,
                }).then((changeit) => {
                    if (changeit) {
                        var tabid = $(this).attr('id');
                        if(userdata == 'true'){
                            $("#saveUserDetailsBtn").attr('data-nexttab',tabid);
                            $("#saveUserDetailsBtn").trigger('click');
                        }else if(comapnydata == 'true'){
                            $("#userCompanyDetailsBtn").attr('data-nexttab',tabid);
                            $("#userCompanyDetailsBtn").trigger('click');
                        }else if(ppassworddata == 'true'){
                            $("#changePasswordBtn").attr('data-nexttab',tabid);
                            $("#changePasswordBtn").trigger('click');
                        }else if(langdata == 'true'){
                            $("#saveLangcurrencymBtn").attr('data-nexttab',tabid);
                            $("#saveLangcurrencymBtn").trigger('click');
                        }else if(paymentdata == 'true'){
                            $("#saveUserPaymenttermBtn").attr('data-nexttab',tabid);
                            $("#saveUserPaymenttermBtn").trigger('click');
                        }
                    }else{
                        var companydetails = @json($companyDetails);
                        var user = @json($user);
                        if(companydetails.background_logo){
                            // alert(companydetails.background_logo);
                            $('.logobanner_section').css('background-image', companydetails.background_logo);
                        }
                        if(companydetails.company_logo){
                            $("#companyLogoPreview").attr('src','{{ asset("storage/") }}'+ '/'+ companydetails.company_logo);
                        }else{
                            $("#companyLogoPreview").attr('src','{{ URL::asset("front-assets/images/front/logo.png") }}');
                        }
                        if(user.profile_pic){
                            $(".user_info_photo").find('img').attr('src','{{ asset("storage/") }}'+ '/'+ user.profile_pic);
                        }
                        $(".user_info_photo").find('img').attr('id','userProfilePic');
                        $('#userDetailForm').trigger("reset");
                        $('#userCompanyDetailsForm').trigger("reset");
                        $('#userDetailForm').find('.invalid-feedback').text('');
                        $('#userCompanyDetailsForm').find('.invalid-feedback').text('');
                        $('#userCompanyDetailsForm').find('ul').find('li').text('');
                        $('#userPasswordChangeForm').trigger("reset");
                        $('#saveLangcurrencyForm').trigger("reset");
                        $('#saveUserPaymenttermForm').trigger("reset");
                        $("#saveUserDetailsBtn").attr('data-boolean','false');
                        $("#userCompanyDetailsBtn").attr('data-boolean','false');
                        $("#changePasswordBtn").attr('data-boolean','false');
                        $("#saveLangcurrencymBtn").attr('data-boolean','false');
                        $("#saveUserPaymenttermBtn").attr('data-boolean','false');
                        //To enable clicks on other tab
                        $(".nav-tabs").find('button').attr("data-bs-toggle","tab");
                        var tabid = $(this).attr('id');
                        buttonclickoncancel(tabid);
                    }
                })
            }else{
                // var tabid = $(this).attr('id');
                // buttonclickoncancel(tabid);
            }
            var tabid = $(this).attr('id');
            if(tabid == 'change_company_info'){
                $('#hide_show_profile_bar').removeClass('hide');
            }else{
                $('#hide_show_profile_bar').addClass('hide');
            }
        });
        function buttonclickoncancel(tabid){
            var buttonid = $("#"+tabid).find('button').attr('id');
            $("#"+buttonid).trigger('click');
        }

        //On click of any tab to stay on same page
        $("#userDetailForm").change(function() {
            $(".nav-tabs").find('button').removeAttr("data-bs-toggle","tab");
            $("#saveUserDetailsBtn").attr('data-boolean','true');
        });
        $("#userCompanyDetailsForm").change(function() {
            $(".nav-tabs").find('button').removeAttr("data-bs-toggle","tab");
            $("#userCompanyDetailsBtn").attr('data-boolean','true');
        });
        $("#userPasswordChangeForm").change(function() {
            $(".nav-tabs").find('button').removeAttr("data-bs-toggle","tab");
            $("#changePasswordBtn").attr('data-boolean','true');
        });
        $("#saveLangcurrencyForm").change(function() {
            $(".nav-tabs").find('button').removeAttr("data-bs-toggle","tab");
            $("#saveLangcurrencymBtn").attr('data-boolean','true');
        });
        $("#saveUserPaymenttermForm").change(function() {
            $(".nav-tabs").find('button').removeAttr("data-bs-toggle","tab");
            $("#saveUserPaymenttermBtn").attr('data-boolean','true');
        });
        $("#flexSwitchCheckDefault").change(function() {
            $(".nav-tabs").find('button').removeAttr("data-bs-toggle","tab");
            $("#flexSwitchCheckDefault").attr('data-boolean','true');
        });
        //End

        $(document).on('click', '#changePasswordBtn', function(e) {
            var passwordBoolean = $("#changePasswordBtn").data('boolean');
            $('#wrongCurrentPassword').html('');
            $("#changePasswordBtn").attr('data-boolean','false');

            $(".nav-tabs").find('button').attr("data-bs-toggle","tab");
            var tabid = $(this).attr('data-nexttab');
            e.preventDefault();
            if ($('#userPasswordChangeForm').parsley().validate()) {
                var formData = new FormData($('#userPasswordChangeForm')[0]);
                if(passwordBoolean){
                    formData.append('changesUserNotification', 1);
                }
                $.ajax({
                    url: "{{ route('profile-change-password-ajax') }}",
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function(successData) {
                        if (successData.success) {
                            new PNotify({
                                text: "{{ __('profile.password_updated') }}",
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 1000
                            });
                            buttonclickoncancel(tabid);
                            $("#changePasswordBtn").removeAttr('data-nexttab');

                            $('#userPasswordChangeForm')[0].reset();
                        } else {
                            $('#wrongCurrentPassword').html(successData.error);
                        }
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        });

        $(document).on('click', '#userCompanyDetailsBtn', function(e) {
            e.preventDefault();
            var comanyBoolean = $("#userCompanyDetailsBtn").data('boolean');
            if($('#userCompanyDetailsForm').find('company_email').parent().find('ul').find('parsley-email').length > 0  || $('#userCompanyDetailsForm').find('company_phone').parent().find('ul').length > 0){
                $(".nav-tabs").find('button').removeAttr("data-bs-toggle","tab");
                $("#userCompanyDetailsBtn").attr('data-boolean','true');
            }else{
                $("#userCompanyDetailsBtn").attr('data-boolean','false');
                $(".nav-tabs").find('button').attr("data-bs-toggle","tab");
            }

            var tabid = $(this).attr('data-nexttab');
            var a = 0;
            $("input[name='ProductAnnualConsumption[]']").each(function(){
                var ac = $(this).val();
                var jac = $(this);
                $('select[name="ProductCategory[]"]').each(function(){
                    var c = $(this).val();
                    var jc = $(this);
                    $('select[name="ProductUnit[]"]').each(function(){
                        var u = $(this).val();
                        var ju = $(this);
                        if(ac > 0  && c == "" && u == ""){

                            $(jc).next('span').remove();
                            $(jc).after('<span style="color:red;">This field is required</span>');
                            $(ju).next('span').remove();
                            $(ju).after('<span style="color:red;">This field is required</span>');
                            return  a =  1;
                        }
                        if(ac == 0 && c > 0 && u == ""){
                            $(jac).next('span').remove();
                            $(jac).after('<span style="color:red;">This field is required</span>');
                            $(ju).next('span').remove();
                            $(ju).after('<span style="color:red;">This field is required</span>');
                            return a = 1;

                        }
                        if(ac == 0 && c == "" && u > 0){
                            $(jc).next('span').remove();
                            $(jc).after('<span style="color:red;">This field is required</span>');
                            $(jac).next('span').remove();
                            $(jac).after('<span style="color:red;">This field is required</span>');
                            return a = 1;

                        }
                        if(ac == 0 && c > 0 && u > 0){
                            $(jac).next('span').remove();
                            $(jac).after('<span style="color:red;">This field is required</span>');
                            return a = 1;

                        }

                    });
                });
            });
            if(a == 1){
                return false;
            }else{
                var _this = $('#userCompanyDetailsForm');
                if ($('#userCompanyDetailsForm').parsley().validate()) {
                    var formData = new FormData($('#userCompanyDetailsForm')[0]);
                    if(comanyBoolean){
                        formData.append('changesUserNotification', 1);
                    }
                    $.ajax({
                        url: "{{ route('profile-change-companyinfo-ajax') }}",
                        data: formData,
                        type: 'POST',
                        contentType: false,
                        processData: false,
                        dataType: 'JSON',
                    }).done(function(response) {
                        if (response.status == "success") {
                            new PNotify({
                                text: "{{ __('profile.company_details_updated') }}",
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 1000
                            });
                            buttonclickoncancel(tabid);
                            $('#file-nib_file .removeFile, #file-nib_file .downloadbtn').removeClass('hidden');
                            $('#file-npwp_file .removeFile, #file-npwp_file .downloadbtn').removeClass('hidden');
                            $('#file-license_image .removeFile, #file-license_image .downloadbtn').removeClass('hidden');
                            $('#file-bank_statement_image .removeFile, #file-bank_statement_image .downloadbtn').removeClass('hidden');
                            $('#file-annual_financial_statement_image .removeFile, #file-annual_financial_statement_image .downloadbtn').removeClass('hidden');
                            $("#userCompanyDetailsBtn").removeAttr('data-nexttab');
                            $("*").removeClass('inputfocus');
                            $("#profile_percentage_message_ajex").html(response.data['percentage']);
                            $("#progress_bar_ajax").html(response.data['progressBar']);
                        }else{
                            $(".nav-tabs").find('button').removeAttr("data-bs-toggle","tab");
                            $("#userCompanyDetailsBtn").attr('data-boolean','true');
                            $.each(response.errors, function(key, value) {
                                    if(key == 'company_logo'){

                                        swal({
                                            icon: 'front-assets/images/format_not_support.png',
                                            title: "{{__('profile.mimetype')}}",
                                            showClass: {
                                                popup: 'animate__animated animate__fadeInDown'
                                            },
                                            hideClass: {
                                                popup: 'animate__animated animate__fadeOutUp'
                                            }
                                        });
                                    }else{

                                        var input = $('select[name=' + key + ']');
                                        var parent = input.next('.invalid-feedback').text(value[0]);
                                        input.addClass('is-invalid');
                                        var input = $('input[name=' + key + ']');
                                        var parent = input.next('.invalid-feedback').text(value[0]);
                                        input.addClass('is-invalid');
                                    }
                                //  }
                            });
                        }
                    });
                }

            }
        });

        //@ekta invite supplier and friend
        $(document).on('click', '#saveInviteSupplierBtn', function(e) {

            window.Parsley.addValidator('uniqueemailuser', {
                validateString: function (value) {
                    let res = false;
                    xhr = $.ajax({
                        url: '{{ route('check-invite-user-email-exist') }}',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
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
                    en: '{{ __('admin.email_already_exist') }}'
                }
            });

            e.preventDefault();
            //$("#saveInviteSupplierBtn").attr('data-boolean','false');

            //$(".nav-tabs").find('button').attr("data-bs-toggle","tab");
            //var tabid = $(this).attr('data-nexttab');

            if ($('#inviteSupplierForm').parsley().validate()) {
                var formData = new FormData($('#inviteSupplierForm')[0]);
                $.ajax({
                    url: "{{ route('profile-invite-supplier-ajax') }}",
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,

                    success: function(successData) {
                        if(successData) {
                            new PNotify({
                                text: "{{ __('admin.invite_buyer_send_alert') }}",
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 2000
                            });
                            $('#addinvitesupplier').modal('hide');
                            $('#inviteSupplierForm').parsley().reset();
                            //buttonclickoncancel(tabid);
                            //$("#saveInviteSupplierBtn").removeAttr('data-nexttab');

                            setTimeout(function () {
                                location.reload(true);
                            }, 2000);
                        }
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        });

        //Edit invite supplier and friend
        $(document).on('click', '.editInviteSupplierData', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            if (id) {
                $.ajax({
                    url: "{{ route('profile-invite-supplier-edit-ajax', '') }}" + "/" + id,
                    type: 'GET',
                    success: function(successData) {
                        //console.log(successData);
                        $("#invite_supplier_editid").val(successData.inviteBuyerEdit.id);
                        $("#user_email_edit").val(successData.inviteBuyerEdit.user_email);
                        $("#role_id_edit").val(successData.inviteBuyerEdit.role_id);
                        //$('#editinvitesupplier').find('.modal-content').html(successData.returnHTML);
                        $('#editinvitesupplier').modal('show');
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        });

        //update invite supplier and friend
        $(document).on('click', '#updateInviteSupplierBtn', function(e) {
        //$("#updateInviteSupplierBtn").click(function(e) {

            window.Parsley.addValidator('uniqueemailuseredit', {
                validateString: function (value) {
                    let res = false;
                    xhr = $.ajax({
                        url: '{{ route('check-invite-user-email-edit-exist') }}',
                        dataType: 'json',
                        method: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id: $('#inviteSupplierFormEdit input[name="id"]').val(),
                            email: value,
                        },
                        async: false,
                        success: function(data) {
                            res = data;
                        },
                    });
                    return res;
                },
                messages: {
                    en: '{{ __('admin.email_already_exist') }}'
                },
                priority: 32
            });

            e.preventDefault();
            $("#updateInviteSupplierBtn").attr('data-boolean','false');
            $(".nav-tabs").find('button').attr("data-bs-toggle","tab");
            var tabid = $(this).attr('data-nexttab');

            if ($('#inviteSupplierFormEdit').parsley().validate()) {
                var formData = new FormData($('#inviteSupplierFormEdit')[0]);
                $.ajax({
                    url: "{{ route('update-invite-supplier-ajax') }}",
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,

                    success: function(successData) {
                        if(successData) {
                            new PNotify({
                                text: "{{ __('admin.invite_buyer_update_alert') }}",
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 2000
                            });
                            $('#inviteUserEditModal').modal('hide');
                            $('#inviteSupplierFormEdit').parsley().reset();
                            buttonclickoncancel(tabid);
                            // $("#saveInviteSupplierBtn").removeAttr('data-nexttab');
                            $('#invite_supplier_tab,#invite_supplier').addClass('active');
                            //$('#user_fn,#user_ln,#user_email,#user_mobile1,#user_designation,#user_dept').val('');

                            setTimeout(function () {
                                location.reload(true);
                            }, 2000);
                        }
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        });

        //resend email invite supplier and friend
        $(document).on('click', '.resendInviteBuyer', function () {
            var id = $(this).attr('id').split("_")[1];
            swal({
                {{--title: "{{ __('admin.are_you_sure_to_send_invitation') }}",--}}
                text: "{{ __('admin.are_you_sure_to_send_invitation') }}",
                icon: "/assets/images/info.png",
                buttons: ['{{ __('admin.no') }}', '{{ __('admin.yes') }}'],
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
                            url: '{{ route('invite-buyer-resend') }}',
                            type: 'POST',
                            data: senddata,
                            success: function (successData) {
                                new PNotify({
                                    text: '{{ __('admin.invitation_send_successfully') }}',
                                    type: 'success',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 2000,
                                });
                                location.reload();

                            },
                            error: function () {
                                console.log('error');
                            }
                        });

                    }
                });
        });

        $(document).on('click', '#saveInviteSupplierHeaderBtn', function(e) {
            window.Parsley.addValidator('uniqueemailsupplier', {
                validateString: function (value) {
                    let res = false;
                    xhr = $.ajax({
                        url: '{{ route('check-invite-user-email-exist') }}',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
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
                    en: '{{ __('admin.email_already_exist') }}'
                }
            });

            e.preventDefault();
            if ($('#inviteSupplierFormHeader').parsley().validate()) {
                var formData = new FormData($('#inviteSupplierFormHeader')[0]);
                $.ajax({
                    url: "{{ route('profile-invite-supplier-ajax') }}",
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,

                    success: function(successData) {
                        if(successData) {
                            $('.navbar-collapse').collapse('hide');
                            new PNotify({
                                text: "{{ __('admin.invite_buyer_send_alert') }}",
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 2000
                            });
                            //$('#collapseinvite').modal('hide');
                            $('#inviteSupplierFormHeader').parsley().reset();

                            setTimeout(function () {
                                location.reload(true);
                            }, 2000);
                        }
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        });

        //@ekta end
        $(document).click(function (event) {
            /// If *navbar-collapse* is not among targets of event
            if (!$(event.target).is('.navbar-collapse *')) {
                /// Collapse every *navbar-collapse*
                $('.navbar-collapse').collapse('hide');
                $('#inviteSupplierFormHeader').parsley().reset();
                $('#inviteSupplierFormHeader')[0].reset();
            }
        });

        $(document).on('click', '#addInterestedBlock', function(e) {
            // var index = $("#companyYearlyContainer select").length + 1;
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

        $(document).on('click', '.trashicon', function() {

            $(this).closest('.interested_in_html').remove();
        });
        $(document).on('click', '#userCompanyDetailsForm', function(e) {
            $("*").removeClass('inputfocus');
        });
    });

    //Active / Inactive preferred supplier using toggle switch (Ronak M - 22/06/2022)
    $(document).on('click', '.isSupplierActive', function(e) {
        e.preventDefault();
        let preferredSuppId = $(this).attr('data-id');
        let status = $(this).val();

        swal({
            text: status == 1 ? "{{ __('admin.deactive_supplier_message') }}" : "{{ __('admin.active_supplier_message') }}",
            icon: "/assets/images/info.png",
            buttons: ['{{ __('admin.no') }}', '{{ __('admin.yes') }}'],
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: "{{ route('update-preferred-supplier-status-ajax') }}",
                    type: 'POST',
                    data: {
                        'preferredSuppId': preferredSuppId,
                        'status' : status,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(data) {
                        if (data.success) {
                            new PNotify({
                                text: "{{ __('admin.supplier_status_updated_success_message') }}",
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 2000
                            });
                            setTimeout(function () {
                                location.reload(true);
                            }, 2000);
                        }
                    },
                    error: function() {
                        new PNotify({
                            text: "{{ __('profile.something_went_wrong') }}",
                            type: 'error',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });
                    }
                });
            }
        });
    });

    //Soft delete preferred suppliers (Ronak M - 21/06/2022)
    $(document).on('click', '.deletePreferredSupplier', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var id = $(this).attr('data-id');
        swal({
            title: "{{ __('dashboard.are_you_sure') }}?",
            text: "{{ __('dashboard.delete_warning') }}",
            icon: "/assets/images/bin.png",
            buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.delete') }}'],
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: "{{ route('delete-preferred-supplier-ajax', '') }}" + "/" + id,
                    type: 'GET',
                    success: function(successData) { console.log(successData.success);
                        if(successData.success){
                            new PNotify({
                                text: "{{ __('validation.user_deleted') }}",
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 2000
                            });
                        }
                        $('#preferred-suppliers-tab,#preferredSuppliers').addClass('active');
                        setTimeout(function () {
                            location.reload(true);
                        }, 2000);
                    },
                    error: function() {
                        console.log('error');
                    }
                });
                setTimeout(function () {
                    location.reload(true);
                }, 2000);
            }
        });
    });


</script>

<script type="text/javascript">
    $(document).ready(function(){
        window.Parsley.addValidator("notequalto", {
            requirementType: "string",
            validateString: function(value, element) {
                return value !== $(element).val();
            }
        });

        $("#background_logo").val($(".logobanner_section").css('background-image'));
        var companydetails = @json($companyDetails);
        $("#background_colorPicker").val(companydetails.background_colorpicker);
        if(companydetails.background_logo){
            // alert(companydetails.background_logo);
            $('.logobanner_section').css('background-image', companydetails.background_logo);
        }
        if(companydetails.background_colorpicker) {
            $('.logobanner_section').css('background', companydetails.background_colorpicker);
        }

        /**
         * Company banner chnage functionlity
         */
        $('.changebanner').click(function(){
            var id = $(this).attr('data-containerId');
            $('.logobanner_section').css('background-image','');
            $("#logbannerId").removeClass();
            $("#logbannerId").addClass("logobanner_section d-flex align-items-center justify-content-center w-100 p-3 bg_logo_image")

            for($i = 1; $i <= 12; $i++) {
                if($i == id) {
                   $(".logobanner_section").addClass("container"+id);
                   continue;
                }
            }
            var backlogo = $(".logobanner_section").css('background-image');
            $("#background_logo").val(backlogo);

            if(id == 12){
                $("#background_colorPicker").val(companydetails.background_colorpicker);
            }else{
                $("#background_colorPicker").val('');
            }


        });
        //
        $('.banner12').on('change', function()  {
                $("#background_colorPicker").val($('.logobanner_section').css("background-color"));
        });


    });
    $('.banner12').click(function(){
        $('.logobanner_section').css('background', companydetails.background_colorpicker);
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
            let company_id = '{{ $companyDetails->company_id }}';
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

    function downloadimg(id, fieldName, name){
        event.preventDefault();
        var data = {
            id: id,
            fieldName: fieldName
        }
        $.ajax({
            url: "{{ route('profile-company-download-image-ajax') }}",
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

    $(document).on("click", ".removeFile", function(e) {
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
                url: "{{ route('profile-company-file-delete-ajax') }}",
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

    //@ekta Redirect to invite supplier list view
    function redirectToInvite(){
        sessionStorage.clear();
        sessionStorage.setItem("profile-lastlocation", JSON.stringify({
            mainTab: "company-tab",
            secondTab: "invite_supplier_list"
        }));
        window.location='<?php echo e(route("profile")); ?>';
    }
    //end @ekta
</script>

<script src="{{ URL::asset('front-assets/intlTelInput/js/intlTelInput.js') }}"></script>

{{ view('notification_js') }}



<script>


    var input4 = document.querySelector("#mobile");
    var iti4 = window.intlTelInput(input4, {
        initialCountry:"id",
        separateDialCode:true,
        dropdownContainer:null,
        preferredCountries:["id"],
        hiddenInput:"m_phone_code"
    });

    var input = document.querySelector("#user_mobile");
    var iti = window.intlTelInput(input, {
        initialCountry:"id",
        separateDialCode:true,
        dropdownContainer:null,
        preferredCountries:["id"],
        hiddenInput:"phone_code"
    });

    var input2 = document.querySelector("#company_phone");
    var iti2 = window.intlTelInput(input2, {
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

    $("#mobile").focusin(function(){
        let countryData = iti4.getSelectedCountryData();
        $('input[name="m_phone_code"]').val(countryData.dialCode);
    });
    $("#user_mobile").focusin(function(){
        let countryData = iti.getSelectedCountryData();
        $('input[name="phone_code"]').val(countryData.dialCode);
    });
    $("#company_phone").focusin(function(){
        let countryData = iti2.getSelectedCountryData();
        $('input[name="c_phone_code"]').val(countryData.dialCode);
    });
    $("#alternative_phone").focusin(function(){
        let countryData = iti3.getSelectedCountryData();
        $('input[name="a_phone_code"]').val(countryData.dialCode);
    });
    $(document).ready(function(){
        @php
            $phoneCode = $phoneNumber ?str_replace('+','',$phoneNumber):62;
            $country = $phoneNumber ?strtolower(getRecordsByCondition('countries',['phone_code'=>$phoneCode],'iso2',1)):'id';
            $cPhoneCode = $companyDetails->c_phone_code?str_replace('+','',$companyDetails->c_phone_code):62;
            $cCountry = $companyDetails->c_phone_code?strtolower(getRecordsByCondition('countries',['phone_code'=>$cPhoneCode],'iso2',1)):'id';
            $aPhoneCode = $companyDetails->a_phone_code?str_replace('+','',$companyDetails->a_phone_code):62;
            $aCountry = $companyDetails->a_phone_code?strtolower(getRecordsByCondition('countries',['phone_code'=>$aPhoneCode],'iso2',1)):'id';
        @endphp
        $('input[name="m_phone_code"]').val({{ $phoneCode }});
        iti4.setCountry('{{$country}}');
        $('input[name="c_phone_code"]').val({{ $cPhoneCode }});
        iti2.setCountry('{{$cCountry}}');
        $('input[name="a_phone_code"]').val({{ $aPhoneCode }});
        iti3.setCountry('{{$aCountry}}');
    });

    /****************************************begin:User Bank Details Operations***************************************/
    var SnippetProfileBankDetails = function(){

        var bankDetailsId = '';

        var bankDetails = function() {
            $('#saveUserBank').on("click", function(e) {
                e.preventDefault();
                removeAllErrors();
                let formData;

                if ($('#userBankDetailsForm').find(':checkbox:not(:checked)').val()) {
                    formData   =   $('#userBankDetailsForm').serializeArray();
                    formData.push({ name: "isPrimary", value: "0" });
                } else {
                    formData   =   $('#userBankDetailsForm').serialize();
                }
                let method      =   $('#userBankDetailsForm').attr('method');
                let action      =   $('#userBankDetailsForm').attr('action');

                $.ajax({
                    url: action,
                    data: formData,
                    type: method,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(data) {
                        if (data.success) {

                            if (method=='PUT') {
                                new PNotify({
                                    text: "{{ __('profile.bank_details_updated') }}",
                                    type: 'success',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 1000
                                });
                            } else {
                                new PNotify({
                                    text: "{{ __('profile.bank_added') }}",
                                    type: 'success',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 1000
                                });
                            }

                            $('#userBankModal').modal('hide');
                            setTimeout(function () {
                                location.reload(true);
                            }, 2000);

                        }

                    },
                    error: function(data) {
                        if( data.status === 422 ) {
                            var errors = $.parseJSON(data.responseText);
                            $.each(errors, function (key, value) {

                                if($.isPlainObject(value)) {
                                    $.each(value, function (key, value) {
                                        $('#'+key+'Error').html(value);
                                    });
                                }
                            });
                        } else {
                            new PNotify({
                                text: "{{ __('profile.something_went_wrong') }}",
                                type: 'error',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 1000
                            });
                        }

                    },
                });
            });
        },

        removeSingleError = function() {

            $('#userBankDetailsForm input[type="text"]').on('input', function (evt) {
                let inputId = $(this).attr('id');
                $('#'+inputId+'Error').html('');
            });

            $('#userBankDetailsForm select').on('change', function (evt) {
                let inputId = $(this).attr('id');
                $('#'+inputId+'Error').html('');
            });
        },

        removeAllErrors = function(){
            $('#userBankDetailsForm span.text-danger').html('');
        },

        bankDetailFormVaildation = function() {

            /**Bank account number max validation**/
            $("#bankAccountNumber").on("keypress paste change", function(event) {
                var value       = $(this).val();
                var maxLength   = 18;
                if (value.length > maxLength) {
                    event.preventDefault();
                    return false;
                }

            });

            $("#bankAccountHolderName").on("keypress paste change", function(event) {
                var value       = $(this).val();
                var maxLength   = 255;
                if (value.length > maxLength) {
                    event.preventDefault();
                    return false;
                }

            });

            $("#bankName").on("change", function(event) {

                $('#bankCode').val($(this).find(":selected").attr('data-code'));

            });

        },

        bankDetailEditModal = function() {
            $('.editUserBankDetails').on('click', function(e) {
                e.preventDefault();
                bankDetailsId = $(this).attr('data-id');
                if (bankDetailsId != '') {
                    $.ajax({
                        url: "{{ url('bank-details', '') }}" + "/" + bankDetailsId,
                        type: 'GET',
                        success: function(data) {

                            //Set all values by edit mode
                            if (data.success) {

                                $('#userBankModal').find('#userBankModalTitle').html('{{ __("profile.edit_bank") }}');
                                $('#userBankModal').find('#saveUserBank').html('<img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png')}}" alt="Post Requirement" class="pe-1"> {{ __("profile.save_changes") }}');

                                $('#userBankModal').find('#bankName').val(data.data.bank_id).change();
                                $('#userBankModal').find('#bankCode').val(data.data.bank_code);
                                $('#userBankModal').find('#bankAccountHolderName').val(data.data.account_holder_name);
                                $('#userBankModal').find('#bankAccountNumber').val(data.data.account_number);
                                $('#userBankModal').find('#bankDescription').val(data.data.description);
                                $('#userBankModal').find('#isPrimary').prop('checked',data.data.is_primary > 0 ? true : false);

                                $('#userBankDetailsForm').attr('method','PUT');
                                $('#userBankDetailsForm').attr('action','bank-details/'+bankDetailsId);

                                $('#userBankModal').modal('show');
                            }

                        },
                        error: function() {
                            console.log('error');
                        }
                    });
                }
            });
        },

        bankDetailAddModal = function() {

            $('#addUserBankDetail').on('click', function(e) {
                e.preventDefault();
                bankDetailsId = '';
                $('#userBankModal').find('#userBankModalTitle').html('{{ __("profile.add_bank") }}');
                $('#userBankModal').find('#saveUserBank').html('<img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}" alt="Post Requirement" class="pe-1"> {{ __("profile.save") }}');

                $('#userBankDetailsForm').attr('method','POST');
                $('#userBankDetailsForm').attr('action','bank-details');
            });

        },

        bankDetailDelete = function() {
            $('.deleteUserBankDetails').on('click', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                var id = $(this).attr('data-id');
                var responseCheckPrimary = '';

                responseCheckPrimary = checkPrimaryExist(id);      //Check is bank primary - show warning

                if (responseCheckPrimary == false) {
                    swal({
                        title: "{{ __('dashboard.are_you_sure') }}?",
                        text: "{{ __('dashboard.delete_warning') }}",
                        icon: "/assets/images/bin.png",
                        buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                        dangerMode: true,

                    }).then((confirm) => {
                        if (confirm) {
                            $.ajax({
                                url: "{{ url('bank-details', '') }}" + "/" + id,
                                type: 'DELETE',
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                success: function (data) {

                                    if (data.success) {
                                        new PNotify({
                                            text: data.message,
                                            type: 'success',
                                            styling: 'bootstrap3',
                                            animateSpeed: 'fast',
                                            delay: 2000
                                        });

                                        setTimeout(function () {
                                            location.reload(true);
                                        }, 1500);
                                    } else {
                                        new PNotify({
                                            text: "{{ __('profile.something_went_wrong') }}",
                                            type: 'error',
                                            styling: 'bootstrap3',
                                            animateSpeed: 'fast',
                                            delay: 1000
                                        });
                                    }
                                },
                                error: function () {
                                    console.log('error');
                                }
                            });
                        }
                    });
                }
            });
        },

        bankDetailFormClose = function() {

            $('#userBankModal').on('hidden.bs.modal', function () {
                removeAllErrors();
                $('#userBankDetailsForm').trigger('reset');
            });

        },

        bankDetailPrimaryChange = function() {

            $('.isPrimaryCheck').on('change', function() {

                if (!$(this).prop('checked')){

                    swal("{{ __('profile.primary_bank_message') }}!", {
                        icon: "/assets/images/warn.png",
                        timer: 5000
                    });

                    $(this).prop('checked', true);

                    return false

                }

                $("input[name=isPrimaryCheck]").not(this).each(function (index, checkbox){

                    checkbox.checked = false;

                });
                let bankId = $(this).attr('data-id');

                $.ajax({
                    url: "{{ route('bank-details.update.primary.bank') }}",
                    type: 'POST',
                    data: {'id': bankId},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(data) {
                        if (!data.success) {
                            new PNotify({
                                text: "{{ __('profile.something_went_wrong') }}",
                                type: 'error',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 1000
                            });

                            setTimeout(function () {
                                location.reload(true);
                            }, 2000);
                        }
                    },
                    error: function() {
                        new PNotify({
                            text: "{{ __('profile.something_went_wrong') }}",
                            type: 'error',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });
                    }
                });
            });
        },

        checkPrimaryExist = function(bankId){

            var response = "";
            $.ajax({
                url: "{{ route('bank-details.exist.primary.bank') }}",
                type: 'POST',
                async: false,
                data: {'id': bankId},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(data) {
                    if (data.success) {
                        swal({
                            text: data.message,
                            icon: "/assets/images/warn.png",
                            confirmButtonText: "{{ __('admin.ok') }}",
                        });
                    }
                    response = data.success ;

                },
                error: function() {
                    new PNotify({
                        text: "{{ __('profile.something_went_wrong') }}",
                        type: 'error',
                        styling: 'bootstrap3',
                        animateSpeed: 'fast',
                        delay: 1000
                    });
                }
            });

            return response;

        };

        return {
            init:function(){

                bankDetails(),
                removeSingleError(),
                bankDetailFormClose(),
                bankDetailFormVaildation(),
                bankDetailEditModal(),
                bankDetailAddModal(),
                bankDetailDelete(),
                bankDetailPrimaryChange()

            }
        }

    }(1);
    /****************************************end:User Bank Details Operations***************************************/


    /****************************************begin:Buyer Backend Sidebar***************************************/
    var SnippetSettingSidebar = function(){

        var rolesPermission = function() {
                $('.custom-link-layout').on('click', function(e){
                    e.preventDefault();
                    location.replace($(this).attr('data-target'));
                });
            };

        return {
            init: function () {
                rolesPermission()
            }
        }

    }(1);


    /****************************************end:Buyer Backend Sidebar***************************************/

    var SnippetInviteUsers = function(){

        var editPermission = function() {
            $(document).on( 'click', '.permissionInvitedUser', function () {
                let id = $(this).attr('data-id');
                let url = "{{ route('settings.users.permission' , ':user') }}";
                url         = url.replace(':user', id);

                location.replace(url);

            });
        };

        return {
            init: function () {
                editPermission()
            },
            //send mail
            sendEmailvarification:function (){
                let csrf =  $('meta[name="csrf-token"]').attr('content');
                formData = {"_token": csrf,'email':$('input[name="email"]').val()};
                $.post("sendemailvarification",formData, (res) => {
                    if(res.success){
                        location.reload();
                    }
                });
            },
        }

    }(1);

    var SnippetCompanyInformation = function(){
        var hideShowProgressbar = function() {
            if( $("#profile-tab").hasClass('active') ) {
                $('#hide_show_profile_bar').removeClass('hide');
            } else {
                $('#hide_show_profile_bar').addClass('hide');
            }
        };
        return {
            init: function () {
                hideShowProgressbar()
            }
        }
    }(1);

    jQuery(document).ready(function(){
        SnippetProfileBankDetails.init();
        SnippetSettingSidebar.init();
        SnippetInviteUsers.init();
        SnippetCompanyInformation.init();
    });
    // Role Permission popup
    $(document).on('click', '.roleModalView', function(e) {
        e.preventDefault();
        $("#rolemodal-body").html('');
        var id = $(this).attr('data-id');
        if (id) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('settings.users.rolePermissionPopup', '') }}" + "/" + id,
                type: 'POST',
                success: function(successData) {
                    $("#rolemodal-body").html(successData.rolePopupView)
                    $('#rolePopUp').modal('show');
                },
                error: function() {
                    console.log('error');
                }
            });
        }
    });
    // check email is already exists
    $(document).on("click", ".js-resendemail", function() {
        if ($('#email').parsley().isValid()) {
            xhr = $.ajax({
                url: '{{ route('check-invite-user-email-exist') }}',
                method: 'POST',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                     email: $('#email').val(),
                },
                async: false,
                success: function (data) {
                    if(!data){
                        swal({
                            title: "",
                            text: "{{ __('signup.user_already_registred') }}",
                            icon: "/assets/images/warn.png",
                            buttons: '{{ __('admin.ok') }}',
                            dangerMode: true,
                        });
                        return false;
                    }
                    else{
                        SnippetInviteUsers.sendEmailvarification();
                    }
                },
            });
        }else{
            swal({
                title: "",
                text: "{{ __('validation.emailvalid') }}",
                icon: "/assets/images/warn.png",
                buttons: '{{ __('admin.ok') }}',
                dangerMode: true,
            });
        }
    });
</script>

@stop
