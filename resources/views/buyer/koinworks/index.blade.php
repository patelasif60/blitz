@extends('buyer/layouts/backend/backend_single_layout')

    @section('css')
    <link href="{{ URL::asset('front-assets/js/front/crop/css/style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/js/front/crop/css/style-example.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/js/front/crop/css/jquery.Jcrop.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/croppie.min.css') }}" rel='stylesheet' />
    <link href="{{ URL::asset('front-assets/js/datatables-bs4/dataTables.bootstrap4.css') }}" rel='stylesheet' />

    @endsection

    @section('custom-css')
    <style>
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


    </style>
   @php
   if(isset($ktp_image) && $ktp_image != ''){
    $ktp_image_view=explode('/',$ktp_image);
    $ktp_image_view=$ktp_image_view[4];}
   

    if(isset($ktp_with_selfie_image) && $ktp_with_selfie_image != ''){
    $ktp_with_selfie_image_view=explode('/',$ktp_with_selfie_image);
    $ktp_with_selfie_image_view=$ktp_with_selfie_image_view[4];
    }

    if(isset($other_ktp_image) && $other_ktp_image != ''){
    $otherKtpImage_view=explode('/',$other_ktp_image);
    $otherKtpImage_view=$otherKtpImage_view[4];
    }

    if(isset($family_card_image) && $family_card_image != ''){
    $familyCardImage_view=explode('/',$family_card_image);
    $familyCardImage_view=$familyCardImage_view[4];
    }

    if(isset($npwp_image) && $npwp_image != ''){
    $loanApplicantBusinessNpwpImage_view=explode('/',$npwp_image);
    $loanApplicantBusinessNpwpImage_view=$loanApplicantBusinessNpwpImage_view[4];
    }

    if(isset($bank_statement_image) && $bank_statement_image != ''){
    $loanApplicantBankStatement_view=explode('/',$bank_statement_image);
    $loanApplicantBankStatement_view=$loanApplicantBankStatement_view[4];
    }

    if(isset($license_image) && $license_image != ''){
    $loanApplicantBusinessLicenceImage_view=explode('/',$license_image);
    $loanApplicantBusinessLicenceImage_view=$loanApplicantBusinessLicenceImage_view[4];
}   

@endphp

    @section('content')
        <div class="container Loanapplication py-3">
            <div class="row floatlabels">
                <div class="header_top d-flex align-items-center pb-5 px-4 pt-0">
                    <div>
                        <h1 class="mb-0">{{ __('profile.request_for_loan.title') }}</h1>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn btn-warning ms-auto btn-sm py-1" id="backBtn" style="padding-top: .1rem;padding-bottom: .1rem;">
                        <img src="{{ URL::asset('front-assets/images/icons/angle-left.png') }}" alt="">
                        {{ __('profile.back') }}
                    </a>
                </div>
                <div class="col-md-5">
                    <ul>
                        <li class="mb-3">{{ __('profile.request_for_loan.line1') }}</li>
                        <li class="mb-3 d-none">{{ __('profile.request_for_loan.line2') }}</li>
                        <li class="mb-3">{{ __('profile.instant_credit_approval_less_than_fifty_million') }}</li>
                        <li class="mb-3">{{ __('profile.it_will_take_three_to_five_on_more_than_fifty_million') }}</li>
                        <li class="mb-3">{{ __('profile.email_phone_info') }}</li>
                    </ul>
                </div>
                <div class="col-md-7 formpagesloan">
                    <!-- Page1 -->
                    <div class="card mb-lg-5 shadow"  @if(isset($form_type) && $form_type == '1') style="display:block" @elseif(isset($form_type) && $form_type != '1') style="display:none" @endif>
                        <div class="card-header bg-white">
                            <div class="d-flex align-items-center">
                                <div class="col-md-auto me-3">
                                    <div class="circle per-20">
                                        <div class="f-14 inner">1/5</div>
                                    </div>
                                </div>
                                <div class="col-md-auto">
                                    <div class="f-15 fw-bold">{{ __('profile.your_choice.title') }}</div>
                                    <div class="f-14">{{ __('profile.next.title') }}: {{ __('profile.personal_information.title') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="form1">
                                <div class="my-3">
                                    <label for="loanAmount" class="form-label">{{ __('profile.loan_amount.placeholder') }}<span class="text-danger">*</span></label>

                                    <input type="text" class="form-control input-number" name="loanAmount" id="loanAmount" value="{{$loanAmount ?? '5,000,000'}}" placeholder="5000000">

                                    <span id="loanAmountError" class="js-validation text-danger"></span>
                                </div>
                                <div class="sliderrange my-3 my-lg-5">
                                    <input onfocusout="setSlider(this)" type="range" class="w-100" min="5000000" max="{{$maxCreditLimit??''}}" value="5000000" step="0.01" name="loanAmountSlider" id="customRange3">
                                    <div class="d-flex">
                                        <div class="minrange fw-bold">5,000,000</div>
                                        <div class="maxrange fw-bold ms-auto">{{number_format($maxCreditLimit, 0)}}</div>
                                    </div>
                                </div>

                                <div class="pagefirstbtn d-flex align-items-center justify-content-center">
                                    <button class="btn btn-primary w-100 step" type="button" data-bs-target="#loan_applicant_details" data-bs-dismiss="card-body" >
                                        Get Started <img class="ms-2" src="{{ URL::asset('front-assets/images/icons/page1_arrow.png') }}" alt="arrow" srcset="">
                                    </button>
                                </div>
                                <input type="hidden" class="form-control" id="form_type" name="form_type" value="loanApplicantLimit">
                            </form>
                        </div>
                    </div>
                    <!-- Page1 -->
                    <!-- Page2 -->

                    <div class="card mb-lg-5 shadow"   @if(isset($form_type) && $form_type == '2') style="display:block" @elseif(isset($form_type) && $form_type != '2') style="display:none" @else style="display:none" @endif>
                        <div class="card-header bg-white">
                            <div class="d-flex align-items-center">
                                <div class="col-md-auto me-3">
                                    <div class="circle per-40">
                                        <div class="f-14 inner">2/5</div>
                                      </div>
                                </div>
                                <div class="col-md-auto">
                                    <div class="f-15 fw-bold">{{ __('profile.personal_information.title') }}</div>
                                    <div class="f-14">{{ __('profile.next.title') }}: {{ __('profile.personal_address.title') }}</div>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                        <form id="loanApplicantDetailsForm" name="loanApplicantDetailsForm" enctype="multipart/form-data">
                            <div class="formsection_1 ">
                                <div class="d-flex align-items-center mb-4">
                                    <img src="{{ URL::asset('front-assets/images/icons/koinworkicon1.png')}}" class="me-2" alt="" srcset="">
                                    <span class="bluetextcolorkoinworks fw-bold">{{ __('profile.your_information.title') }}</span>
                                </div>
                                <div class="row g-4 ">

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.first_name') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-alpha limit" name="firstName" id="firstName" value="{{$first_name ?? ''}}" placeholder="{{ __('profile.first_name_placeholder') }}">
                                        <span id="firstNameError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.last_name') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-alpha " name="lastName" id="lastName" value="{{$last_name ?? ''}}" placeholder="{{ __('profile.last_name_placeholder') }}">
                                         <span id="lastNameError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="" class="form-label">{{ __('profile.Email') }}<span class="text-danger">*</span></label>
                                        <input type="email" class="form-control " name="email" id="email" value="{{$email ?? ''}}" placeholder="{{ __('profile.enter_email') }}">
                                        <span id="emailError" class="js-validation text-danger"></span>
                                    </div>

<!--                                     <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.phoneCode.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-number" name="phoneCode" id="phoneCode" placeholder="{{ __('profile.phone.placeholder') }}">
                                        <span id="phoneCodeError" class="js-validation text-danger"></span>
                                    </div> -->

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.phone.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-number" name="phoneNumber" id="phoneNumber" min="9" max="16" value="{{$phone_number ?? ''}}" placeholder="{{ __('profile.phone.placeholder') }}">
                                        <span id="phoneNumberError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6 select2-block" id="gender_block">
                                        <label for="gender" class="form-label">{{ __('profile.gender.title') }}<span class="text-danger">*</span></label>
                                        <select class="form-select " data-placeholder="{{ __('profile.gender.select') }}" id="gender" name="gender" required>
                                            <option value="">{{ __('profile.gender.select') }}</option>
                                            <option value="1" {{(isset($gender) && $gender == "1" ? 'selected' : '') }}>Male</option>
                                            <option value="2" {{(isset($gender) && $gender == "2" ? 'selected' : '') }}>Female</option>
                                        </select>
                                        <span id="genderError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.ktpnik.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-number ktpnik" name="ktpNik" id="ktpNik" value="{{$ktp_nik ?? ''}}" aria-describedby="" placeholder="{{ __('profile.ktpnik.placeholder') }}">
                                        <span id="ktpNikError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.ktp_image.title') }}<span class="text-danger">*</span></label></label>
                                        <div class="d-flex form-control">
                                            <span class="">
                                                <input type="file" name="ktpImage" id="ktpImage" class="form-control" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="" value="{{$ktp_image ?? ''}}">
                                                <label id="upload_btn" for="ktpImage">{{ __('profile.browse') }}</label>
                                            </span>
                                            <div id="file-ktpImage" class="d-flex align-items-center">
                                            <span class="d-flex">
                                            @if(isset($ktp_image) && $ktp_image != '')
                                            <a  class="text-decoration-none downloadPo d-flex align-items-center ms-2" href="{{ Storage::url($ktp_image?? '') }}"  download>{{$ktp_image_view ?? ''}}</a>
                                            @endif
                                             </span><input type="hidden" class="form-control" id="old_ktpImage" name="old_ktpImage" value="{{$ktp_image ?? ''}}">
                                            </div>
                                        </div>
                                        <span id="ktpImageError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.ktp_with_selfie_image.title') }}<span class="text-danger">*</span></label></label>
                                        <div class="d-flex form-control">
                                            <span class="d-flex">
                                                <input type="file" name="ktpSelfiImage" class="form-control" id="ktpSelfiImage" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                <label id="upload_btn" for="ktpSelfiImage">{{ __('profile.browse') }}</label>
                                            </span>
                                            <div id="file-ktpSelfiImage" class="d-flex align-items-center">
                                            @if(isset($ktp_with_selfie_image) && $ktp_with_selfie_image != '')
                                            <a class="text-decoration-none downloadPo d-flex align-items-center ms-2" data-id="" id="downloadPo"  href="{{ Storage::url($ktp_with_selfie_image?? '') }}"  download>{{$ktp_with_selfie_image_view}} </a>
                                            @endif
                                            <input type="hidden" class="form-control" id="old_ktpSelfiImage" name="old_ktpSelfiImage" value="{{$ktp_with_selfie_image ?? ''}}">
                                            </div>
                                        </div>
                                        <span id="ktpSelfiImageError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.family_card_image.title') }}<span class="text-danger">*</span></label></label>
                                        <div class="d-flex form-control">
                                            <span class="d-flex">
                                                <input type="file" name="familyCardImage" class="form-control" id="familyCardImage" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                <label id="upload_btn" for="familyCardImage">{{ __('profile.browse') }}</label>
                                            </span>
                                            <div id="file-familyCardImage" class="d-flex align-items-center">
                                                <input type="hidden" class="form-control" id="old_familyCardImage" name="old_familyCardImage" value="{{$family_card_image ?? ''}}">
                                                @if(isset($family_card_image) && $family_card_image != '')
                                                <a class="text-decoration-none downloadPo d-flex align-items-center ms-2" data-id="" id="downloadPo"  href="{{ Storage::url($family_card_image?? '') }}"  download>{{$familyCardImage_view}}</a>
                                                @endif
                                            </div>
                                        </div>
                                        <span id="familyCardImageError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.date_of_birth.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control calendericons select-date-age" name="dateOfBirth" value="{{$date_of_birth ?? ''}}" id="dateOfBirth" placeholder="dd-mm-yy" autocomplete="off" readonly>
                                        <span id="dateOfBirthError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.place_of_birth.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-alpha limit" name="placeOfBirth" value="{{$place_of_birth ?? ''}}" id="placeOfBirth" placeholder="{{ __('profile.place_of_birth.placeholder') }}">
                                        <span id="placeOfBirthError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6 select2-block" id="maritalStatus_block">
                                        <label for="" class="form-label">{{ __('profile.marital_status.title') }}<span class="text-danger">*</span></label>
                                        <select class="form-select" data-placeholder="{{ __('profile.marital_status.select') }}" id="maritalStatus" name="maritalStatus">
                                            <option value="">{{ __('profile.marital_status.select') }}</option>
                                            <option value="1"  {{(isset($marital_status) && $marital_status == "1" ? 'selected' : '') }}>KAWIN</option>
                                            <option value="2"  {{(isset($marital_status) && $marital_status == "2" ? 'selected' : '') }}>BELUM KAWIN</option>
                                            <option value="3"  {{(isset($marital_status) && $marital_status == "3" ? 'selected' : '') }}>CERAI MATI</option>
                                            <option value="4"  {{(isset($marital_status) && $marital_status == "4" ? 'selected' : '') }}>CERAI HIDUP</option>

                                        </select>
                                        <span id="maritalStatusError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6 select2-block" id="religion_block">
                                        <label for="" class="form-label">{{ __('profile.religion.title') }}<span class="text-danger">*</span></label>
                                        <select class="form-select" data-placeholder="{{ __('profile.religion.select') }}" id="religion" name="religion">
                                            <option value="">{{ __('profile.religion.select') }}</option>
                                            <option value="1"  {{(isset($religion) && $religion == "1" ? 'selected' : '') }}>ISLAM</option>
                                            <option value="2"  {{(isset($religion) && $religion == "2" ? 'selected' : '') }}>KATHOLIK</option>
                                            <option value="3"  {{(isset($religion) && $religion == "3" ? 'selected' : '') }}>KRISTEN</option>
                                            <option value="4"  {{(isset($religion) && $religion == "4" ? 'selected' : '') }}>BUDHA</option>
                                            <option value="5"  {{(isset($religion) && $religion == "5" ? 'selected' : '') }}>HINDU</option>
                                            <option value="6"  {{(isset($religion) && $religion == "6" ? 'selected' : '') }}>KONGHUCHU</option>
                                            <option value="7"  {{(isset($religion) && $religion == "7" ? 'selected' : '') }}>OTHER</option>

                                        </select>
                                        <span id="religionError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.education.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-filter limit" name="education" id="education"  value="{{$education ?? ''}}" placeholder="{{ __('profile.education.placeholder') }}">
                                        <span id="educationError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.occupation.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-alpha limit" name="occupation" id="occupation" value="{{$occupation ?? ''}}" placeholder="{{ __('profile.occupation.placeholder') }}">
                                        <span id="occupationError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6 select2-block" id="myPosition_block">
                                        <label for="" class="form-label">{{ __('profile.my_position.title') }}<span class="text-danger">*</span></label>
                                        <select class="form-select" data-placeholder="{{ __('profile.my_position.select') }}" id="myPosition" name="myPosition">
                                            <option value="">{{ __('profile.my_position.select') }}</option>
                                            <option value="1"  {{(isset($myPosition) && $myPosition == "1" ? 'selected' : '') }}>DIRECTOR</option>
                                        </select>
                                        <span id="myPositionError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.net_salary.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-number" name="netSalary" id="netSalary" value="{{$net_salary ?? ''}}" placeholder="{{ __('profile.net_salary.placeholder') }}">
                                        <span id="netSalaryError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.other_income.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control charlength20 input-number" name="otherIncome" id="otherIncome" value="{{$total_other_income ?? ''}}" placeholder="{{ __('profile.other_income.placeholder') }}">
                                            <span id="otherIncomeError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6 select2-block" id="otherSourceOfIncome_block">
                                        <label for="" class="form-label">{{ __('profile.other_source_of_income.title') }}<span class="text-danger">*</span></label>
                                        <select class="form-select" data-placeholder="{{ __('profile.other_source_of_income.select') }}" value="{{$other_source_of_income ?? ''}}" id="otherSourceOfIncome" name="otherSourceOfIncome">
                                            <option value="">{{ __('profile.other_source_of_income.select') }}</option>
                                            <option  {{(isset($other_source_of_income) && $other_source_of_income == "4" ? 'selected' : '') }} value="4">SALARY</option>
                                        </select>
                                        <span id="otherSourceOfIncomeError" class="js-validation text-danger"></span>
                                    </div>

                                </div>
                                <div class="d-flex align-items-center my-4">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ URL::asset('front-assets/images/icons/koinworkicon1.png') }}" class="me-2" alt="" srcset="">
                                        <span class="bluetextcolorkoinworks fw-bold">{{ __('profile.other_member_information.title') }}</span>
                                    </div>
                                    <div class="ms-auto d-none">
                                        <a name="" id="" class="text-decoration-none fw-bold bluetextcolorkoinworks" href="#" >remove</a>
                                    </div>

                                </div>
                                <div class="row g-4 mb-4">

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.first_name') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-alpha" name="otherFirstName" id="otherFirstName" value="{{$other_first_name?? ''}}" placeholder="{{ __('profile.first_name_placeholder') }}">
                                        <span id="otherFirstNameError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.last_name') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-alpha" name="otherLastName" id="otherLastName" value="{{$other_last_name ?? ''}}" placeholder="{{ __('profile.last_name_placeholder') }}">
                                        <span id="otherLastNameError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="" class="form-label">{{ __('profile.Email') }}<span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="otherMemberEmail" id="otherMemberEmail" value="{{$other_email ?? ''}}" placeholder="{{ __('profile.enter_email') }}">
                                        <span id="otherMemberEmailError" class="js-validation text-danger"></span>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.phoneCode.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-number" name="otherMemberCode" id="otherMemberCode" placeholder="{{ __('profile.phone.placeholder') }}">
                                        <span id="otherMemberCodeError" class="js-validation text-danger"></span>
                                    </div> -->
                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.phone.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-number" name="otherMemberPhone" id="otherMemberPhone" value="{{$other_phone_number ?? ''}}" placeholder="{{ __('profile.phone.placeholder') }}">
                                        <span id="otherMemberPhoneError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6 select2-block" id="relationshipWithBorrower_block">
                                        <label for="" class="form-label">{{ __('profile.relationship_with_borrower.title') }}<span class="text-danger">*</span></label>
                                        <select class="form-select" aria-label="Default select example" data-placeholder="{{ __('profile.relationship_with_borrower.select') }}" id="relationshipWithBorrower" name="relationshipWithBorrower">
                                            <option value="">{{ __('profile.relationship_with_borrower.select') }}</option>
                                            <option value="1" {{(isset($relationship_with_borrower) && $relationship_with_borrower == "1" ? 'selected' : '') }}>PARENT</option>
                                            <option value="2" {{(isset($relationship_with_borrower) && $relationship_with_borrower == "2" ? 'selected' : '') }}>SIBLING</option>
                                            <option value="3" {{(isset($relationship_with_borrower) && $relationship_with_borrower == "3" ? 'selected' : '') }}>SPOUSE</option>
                                            <option value="4" {{(isset($relationship_with_borrower) && $relationship_with_borrower == "4" ? 'selected' : '') }}>COLLEAGUE</option>
                                            <option value="6" {{(isset($relationship_with_borrower) && $relationship_with_borrower == "6" ? 'selected' : '') }}>OTHER</option>
                                        </select>
                                        <span id="relationshipWithBorrowerError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.ktpnik.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-number ktpnik" name="otherKtpNik" id="otherKtpNik" value="{{$other_ktp_nik ?? ''}}" placeholder="{{ __('profile.ktpnik.placeholder') }}">
                                        <span id="otherKtpNikError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.ktp_image.title') }}<span class="text-danger">*</span></label></label>
                                        <div class="d-flex form-control">
                                            <span class="d-flex">
                                                <input type="file" name="otherKtpImage" class="form-control" id="otherKtpImage" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                <label id="upload_btn" for="otherKtpImage">{{ __('profile.browse') }}</label>

                                            </span>
                                            <div id="file-otherKtpImage" class="d-flex align-items-center">
                                                 @if(isset($other_ktp_image) && $other_ktp_image != '')
                                                <a class="text-decoration-none downloadPo d-flex align-items-center ms-2" data-id="" id="downloadPo"  href="{{ Storage::url($other_ktp_image?? '') }}"  download>{{$otherKtpImage_view}}</a>
                                                @endif
                                                <input type="hidden" class="form-control" id="old_otherKtpImage" name="old_otherKtpImage"  value="{{$other_ktp_image ?? ''}}">
                                            </div>
                                        </div>
                                        <span id="otherKtpImageError" class="js-validation text-danger"></span>
                                    </div>
                                </div>
                                <div class="row g-2 g-lg-4">
                                    <div class="col-md-6">
                                        <button class="btn btn-secondary greycolorback w-100 text-white back" type="button">
                                            <img class="me-2" src="{{ URL::asset('front-assets/images/icons/page2_arrow.png') }}" alt="arrow" srcset=""> {{__('profile.back')}}
                                        </button>
                                    </div>

                                    <div class="col-md-6">
                                        <button name="loan_applicant_details_submit" id="loan_applicant_details_submit" class="btn btn-primary bluecolornext w-100 text-white loan_applicant_details_submit step" type="button">
                                            {{__('profile.continue.title')}} <img class="ms-2" src="{{ URL::asset('front-assets/images/icons/page1_arrow.png') }}" alt="arrow" srcset="">
                                        </button>
                                    </div>

                                </div>
                            </div>
                            <input type="hidden" class="form-control" id="form_type" name="form_type" value="loanApplicantDetails">
                            </form>
                        </div>

                    </div>

                    <!-- Page2 -->
                    <!-- Page3 -->
                    <div class="card  mb-lg-5 shadow"  @if(isset($form_type) && $form_type == '3') style="display:block" @elseif(isset($form_type) && $form_type != '3') style="display:none"  @else style="display:none"@endif>
                        <div class="card-header bg-white">
                            <div class="d-flex align-items-center">
                                <div class="col-md-auto me-3">
                                    <div class="circle per-60">
                                        <div class="f-14 inner">3/5</div>
                                      </div>
                                </div>
                                <div class="col-md-auto">
                                    <div class="f-15 fw-bold">{{ __('profile.personal_address.title') }}</div>
                                    <div class="f-14">{{ __('profile.next.title') }}: {{ __('profile.business.title') }}</div>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                        <form id="loanApplicantAddressForm" name="loanApplicantAddressForm" enctype="multipart/form-data">
                            <div class="formsection_1 ">

                                <div class="row g-4 mb-4 pt-2">
                                    <div class="col-md-12">
                                        <label for="" class="form-label">{{ __('profile.address_name.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control  input-alpha limit" name="loanApplicantAddressName" id="loanApplicantAddressName" placeholder="{{ __('profile.address_name.placeholder') }}" value="{{$loanApplicantAddressName ?? ''}}">
                                        <span id="loanApplicantAddressNameError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="" class="form-label">{{ __('profile.address_line_1.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-filter limit" name="loanApplicantAddressLine1" id="loanApplicantAddressLine1" placeholder="{{ __('profile.address_line_1.placeholder') }}" value="{{$address_line1 ?? ''}}">
                                        <span id="loanApplicantAddressLine1Error" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="" class="form-label">{{ __('profile.address_line_2.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-filter limit" name="loanApplicantAddressLine2" id="loanApplicantAddressLine2" placeholder="{{ __('profile.address_line_2.placeholder') }}" value="{{$address_line2 ?? ''}}">
                                        <span id="loanApplicantAddressLine2Error" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.sub_district.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-alpha limit" name="subDistrict" id="subDistrict" placeholder="{{ __('profile.sub_district.placeholder') }}" value="{{$sub_district ?? ''}}">
                                        <span id="subDistrictError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.district.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-alpha limit" name="district" id="district" placeholder="{{ __('profile.district.placeholder') }}" value="{{$district ?? ''}}">
                                        <span id="districtError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-12 select2-block" id="loanApplicantCountryId_block">
                                        <label for="" class="form-label">{{ __('profile.country.title') }}<span class="text-danger">*</span></label>
                                        <select class="form-select" data-placeholder="{{ __('profile.country.select') }}" id="loanApplicantCountryId" name="loanApplicantCountryId">
                                            <option value="" >{{ __('profile.country.select') }}</option>
                                            <option  value="{{$country->id ?? ''}}" {{(isset($country->id)? 'selected' : '') }}>{{$country->name}}</option>
                                        </select>
                                        <span id="loanApplicantCountryIdError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-12" id="stateId_block">
                                        <label for="" class="form-label">{{ __('profile.provinces.title') }}<span class="text-danger">*</span></label>
                                        <select class="form-select" data-placeholder="{{ __('profile.provinces.select') }}" id="provincesId" name="provincesId">
                                            <option value="" >{{ __('profile.provinces.select') }}</option>
                                            @foreach ($states as $state)
                                                        <option  {{(isset($provinces_id) && $provinces_id == $state->id ? 'selected' : '') }} value="{{ $state->id }}" >{{ $state->name }}</option>
                                                    @endforeach
                                            <option value="-1">Other</option>
                                        </select>
                                        <span id="provincesIdError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6 mb-4 hide" id="state_block">
                                            <label for="state" class="form-label">Other Provinces<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="state" id="state" required value="{{$other_provinces ?? ''}}">
                                            <span id="stateError" class="js-validation text-danger"></span>
                                     </div>

                                    <div class="col-md-12 select2-block" id="cityId_block">
                                        <label for="" class="form-label">{{ __('profile.city.title') }}<span class="text-danger">*</span></label>
                                        <select class="form-select" data-placeholder="{{ __('profile.city.select') }}" id="cityId" name="cityId" data-selected-city="{{ $city_id ?? ''}}">
                                            <option value="">{{ __('profile.city.select') }}</option>
                                            <option value="-1">Other</option>
                                        </select>
                                        <span id="cityIdError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6 mb-4  hide" id="city_block">
                                             <label for="city" class="form-label">Other City<span class="text-danger">*</span></label>
                                             <input type="text" class="form-control" name="city" id="city" value="{{$other_city ?? ''}}">
                                            <span id="cityError" class="js-validation text-danger"></span>
                                     </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.postal_code.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-number" name="loanApplicantPostalCode" id="loanApplicantPostalCode" placeholder="{{ __('profile.postal_code.placeholder') }}" value="{{$loanApplicantPostalCode ?? ''}}"  maxlength="7">
                                        <span id="loanApplicantPostalCodeError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6 select2-block" id="loanApplicantHasLivedHere_block">
                                        <label for="" class="form-label">{{ __('profile.has_lived_here.title') }}<span class="text-danger">*</span></label>
                                        <select class="form-select" data-placeholder="{{ __('profile.has_lived_here.select') }}" id="loanApplicantHasLivedHere" name="loanApplicantHasLivedHere">
                                            <option value="" >{{ __('profile.has_lived_here.select') }}</option>
                                            <option value="1"  {{(isset($has_live_here) && $has_live_here == "1" ? 'selected' : '') }} >Ya</option>
                                            <option value="2"  {{(isset($has_live_here) && $has_live_here == "2" ? 'selected' : '') }}>Tidak</option>
                                        </select>
                                        <span id="loanApplicantHasLivedHereError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.duration_of_stay.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-number limit" name="loanApplicantDurationOfStay" id="loanApplicantDurationOfStay" placeholder="{{ __('profile.duration_of_stay.placeholder') }}" value="{{$duration_of_stay ?? ''}}">
                                        <span id="loanApplicantDurationOfStayError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6 select2-block" id="loanApplicanthomeOwnershipStatus_block">
                                        <label for="" class="form-label">{{ __('profile.home_ownership_status.title') }}<span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" data-placeholder="{{ __('profile.home_ownership_status.select') }}" name="loanApplicanthomeOwnershipStatus" id="loanApplicanthomeOwnershipStatus">
                                            <option value="">{{ __('profile.home_ownership_status.select') }}</option>
                                            <option value="3" {{(isset($home_ownership_status) && $home_ownership_status == "3" ? 'selected' : '') }}>RENTAL/KOS</option>
                                            <option value="4" {{(isset($home_ownership_status) && $home_ownership_status == "4" ? 'selected' : '') }}>OWNED/MILIK SENDIRI</option>
                                            <option value="5" {{(isset($home_ownership_status) && $home_ownership_status == "5" ? 'selected' : '') }}>OFFICE RESIDENCE/RUMAH DINAS</option>
                                        </select>
                                        <span id="loanApplicanthomeOwnershipStatusError" class="js-validation text-danger"></span>
                                    </div>


                                </div>

                                <div class="row g-2 g-lg-4">
                                    <div class="col-md-6">
                                        <button class="btn btn-secondary greycolorback w-100 text-white back" type="button">
                                            <img class="me-2" src="{{ URL::asset('front-assets/images/icons/page2_arrow.png') }}" alt="arrow" srcset=""> {{__('profile.back')}}
                                        </button>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="hidden" class="form-control" id="form_type" name="form_type" value="loanApplicantAddress">
                                        <button class="btn btn-primary bluecolornext w-100 text-white step loan_applicatnt_address_submit"  id="loan_applicatnt_address_submit" name="loan_applicatnt_address_submit" href="javascript:void(0)" type="button">
                                            {{__('profile.continue.title')}}<img class="ms-2" src="{{ URL::asset('front-assets/images/icons/page1_arrow.png') }}" alt="arrow" srcset="">
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                    <!-- Page3 -->
                    <!-- Page4 -->
                    <div class="card  mb-lg-5 shadow"  @if(isset($form_type) && $form_type == '4') style="display:block" @elseif(isset($form_type) && $form_type != '4') style="display:none" @else style="display:none" @endif>
                        <div class="card-header bg-white">
                            <div class="d-flex align-items-center">
                                <div class="col-md-auto me-3">
                                    <div class="circle per-80">
                                        <div class="f-14 inner">4/5</div>
                                      </div>
                                </div>
                                <div class="col-md-auto">
                                    <div class="f-15 fw-bold">{{ __('profile.business.title') }}</div>
                                    <div class="f-14">{{ __('profile.next.title') }}: {{ __('profile.business_address.title') }}</div>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                        <form name="loanApplicantBusinessForm" id="loanApplicantBusinessForm" enctype="multipart/form-data">
                            <div class="formsection_1 ">
                                <div class="row g-4 mb-4 pt-2">

                                    <div class="col-md-6 select2-block" id="loanApplicantBusinessType_block">
                                        <label for="" class="form-label">{{ __('profile.business_type.title') }}<span class="text-danger">*</span></label>
                                        <select class="form-select" data-placeholder="{{ __('profile.business_type.select') }}" name="loanApplicantBusinessType" id="loanApplicantBusinessType" required value="{{$loanApplicantPostalCode ?? ''}}">
                                            <option value="">{{ __('profile.business_type.select') }}</option>
                                            <option  {{(isset($type) && $type == "2" ? 'selected' : '') }} value="2">pt</option>
                                        </select>
                                            <span id="loanApplicantBusinessTypeError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.business_name.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control  limit" name="loanApplicantBusinessName" id="loanApplicantBusinessName" placeholder="{{ __('profile.business_name.placeholder') }}" required value="{{$business_name ?? ''}}">
                                        <span id="loanApplicantBusinessNameError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-12 ">
                                        <label class="form-label mb-0 product_description_for" for="">{{ __('profile.description') }}<span class="text-danger">*</span></label>
                                        <textarea class="form-control" placeholder="{{ __('profile.business_description.placeholder') }}" name="loanApplicantBusinessDescription" id="loanApplicantBusinessDescription" required value="">{{$description ?? ''}}</textarea>
                                        <span id="loanApplicantBusinessDescriptionError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="" class="form-label">{{ __('profile.website.title') }}<span class="text-danger"></span></label>
                                        <input type="text" class="form-control limit" name="loanApplicantBusinessWebsite" id="loanApplicantBusinessWebsite" placeholder="{{ __('profile.website.placeholder') }}" required value="{{$website ?? ''}}">
                                        <span id="loanApplicantBusinessWebsiteError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="" class="form-label">{{ __('profile.Email') }}<span class="text-danger">*</span></label>
                                        <input type="email" class="form-control limit " name="loanApplicantBusinessEmail" id="loanApplicantBusinessEmail" placeholder="{{ __('profile.enter_email') }}" required value="{{$loanApplicantBusinessEmail ?? ''}}">
                                        <span id="loanApplicantBusinessEmailError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.phone.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-number" name="loanApplicantBusinessPhone" id="loanApplicantBusinessPhone" placeholder="{{ __('profile.phone.placeholder') }}" required value="{{$loanApplicantBusinessPhone ?? ''}}">
                                        <span id="loanApplicantBusinessPhoneError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.owner_first_name.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-alpha limit" name="loanApplicantBusinessFirstName" id="loanApplicantBusinessFirstName" placeholder="{{ __('profile.owner_first_name.placeholder') }}" value="{{$owner_first_name ?? ''}}">
                                            <span id="loanApplicantBusinessFirstNameError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.owner_last_name.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-alpha limit" name="loanApplicantBusinessLastName" id="loanApplicantBusinessLastName" placeholder="{{ __('profile.owner_last_name.placeholder') }}" value="{{$owner_last_name ?? ''}}">
                                            <span id="loanApplicantBusinessLastNameError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.npwp_number.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-number limit" name="loanApplicantBusinessNpwpNumber" id="loanApplicantBusinessNpwpNumber" placeholder="{{ __('profile.npwp_number.placeholder') }}" value="{{Auth::user()->companies->npwp}}" disabled>
                                        <span id="loanApplicantBusinessNpwpNumberError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.npwp_file') }}<span class="text-danger">*</span></label>
                                        <div class="d-flex form-control">
                                            <span class="d-flex">
                                                <input type="file" name="loanApplicantBusinessNpwpImage" class="form-control" id="loanApplicantBusinessNpwpImage" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                <label id="upload_btn" for="loanApplicantBusinessNpwpImage">{{ __('profile.browse') }}</label>
                                              
                                            </span>
                                            <div id="file-loanApplicantBusinessNpwpImage" class="d-flex align-items-center">
                                            @if(isset($npwp_image) && $npwp_image != '')
                                                <a class="text-decoration-none downloadPo d-flex align-items-center ms-2" data-id="" id="downloadPo"  href="{{ Storage::url($npwp_image?? '') }}"  download>{{$loanApplicantBusinessNpwpImage_view}}</a>
                                                @endif
                                                <input type="hidden" class="form-control" id="old_loanApplicantBusinessNpwpImage_file" name="old_loanApplicantBusinessNpwpImage_file" value="{{$npwp_image ?? ''}}">
                                            </div>
                                        </div>
                                        <span id="loanApplicantBusinessNpwpImageError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.license_image.title') }}<span class="text-danger">*</span></label></label>
                                        <div class="d-flex form-control">
                                            <span class="d-flex">
                                                <input type="file" name="loanApplicantBusinessLicenceImage" class="form-control" id="loanApplicantBusinessLicenceImage" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                <label id="upload_btn" for="loanApplicantBusinessLicenceImage">{{ __('profile.browse') }}</label>
                                              
                                            </span>
                                            <div id="file-loanApplicantBusinessLicenceImage" class="d-flex align-items-center">
                                                @if(isset($license_image) && $license_image != '')
                                                <a class="text-decoration-none downloadPo d-flex align-items-center ms-2" data-id="" id="downloadPo"  href="{{ Storage::url($license_image?? '') }}"  download>{{$loanApplicantBusinessLicenceImage_view}} </a>
                                                @endif
                                                <input type="hidden" class="form-control" id="old_LoanApplicantBusinessLicenceImage_file" name="old_LoanApplicantBusinessLicenceImage_file" value="{{$license_image ?? ''}}">
                                            </div>
                                        </div>
                                        <span id="loanApplicantBusinessLicenceImageError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.average_sales.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-number limit" name="loanApplicantBusinessAverageSales" id="loanApplicantBusinessAverageSales" placeholder="{{ __('profile.average_sales.placeholder') }}" required value="{{$average_sales ?? ''}}">
                                            <span id="loanApplicantBusinessAverageSalesError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.establish_in.title') }}<span class="text-danger">*</span></label>

                                        <input type="text" class="form-control calendericons select-date" name="loanApplicantBusinessEstablish" id="loanApplicantBusinessEstablish" placeholder="dd-mm-yy" autocomplete="off" value="{{$establish_in ?? ''}}" readonly>

                                        <span id="loanApplicantBusinessEstablishError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6 select2-block" id="loanApplicantBusinessNoOfEmployee_block">
                                        <label for="" class="form-label">{{ __('profile.number_of_employee.title') }}<span class="text-danger">*</span></label>
                                            <select class="form-select " data-placeholder="{{ __('profile.number_of_employee.select') }}" name="loanApplicantBusinessNoOfEmployee" id="loanApplicantBusinessNoOfEmployee" >
                                                <option value="">{{ __('profile.number_of_employee.select') }}</option>
                                                <option value="1"  {{(isset($home_ownership_status) && $home_ownership_status == "1" ? 'selected' : '') }}>1-50</option>
                                                <option value="2"  {{(isset($home_ownership_status) && $home_ownership_status == "2" ? 'selected' : '') }}>50-200</option>
                                                <option value="3"  {{(isset($home_ownership_status) && $home_ownership_status == "3" ? 'selected' : '') }}>200-500</option>
                                                <option value="4"  {{(isset($home_ownership_status) && $home_ownership_status == "4" ? 'selected' : '') }}>500-1000</option>
                                                <option value="5"  {{(isset($home_ownership_status) && $home_ownership_status == "5" ? 'selected' : '') }}>1000+</option>
                                            </select>
                                            <span id="loanApplicantBusinessNoOfEmployeeError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.bank_statement_image.title') }}<span class="text-danger">*</span></label></label>
                                        <div class="d-flex form-control">
                                            <span class="d-flex">
                                                <input type="file" name="loanApplicantBankStatement" class="form-control" id="loanApplicantBankStatement" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                <label id="upload_btn" for="loanApplicantBankStatement">{{ __('profile.browse') }}</label>
                                               
                                            </span>
                                            <div id="file-loanApplicantBankStatement" class="d-flex align-items-center">
                                                @if(isset($bank_statement_image) && $bank_statement_image != '')
                                                <a class="text-decoration-none downloadPo d-flex align-items-center ms-2" data-id="" id="downloadPo"  href="{{ Storage::url($bank_statement_image?? '') }}"  download>{{$loanApplicantBankStatement_view}} </a>
                                                @endif
                                                <input type="hidden" class="form-control" id="old_loanApplicantBankStatement_file" name="old_loanApplicantBankStatement_file" value="{{$bank_statement_image ?? ''}}">
                                            </div>
                                        </div>
                                        <span id="loanApplicantBankStatementError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.siup_number.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control charlength20" name="loanApplicantSiupNumber" id="loanApplicantSiupNumber" placeholder="{{ __('profile.siup_number.placeholder') }}" value="{{Auth::user()->companies->registrantion_NIB}}" readonly>
                                        <span id="loanApplicantSiupNumberError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6 select2-block" id="loanApplicantCategory_block">
                                        <label for="" class="form-label">{{ __('profile.Category') }}<span class="text-danger">*</span></label>
                                        <select class="form-select" data-placeholder="{{ __('profile.select_category') }}" id="loanApplicantCategory" name="loanApplicantCategory">
                                            <option value="">{{ __('profile.select_category') }}</option>
                                            <option value="1"  {{(isset($category) && $category == "1" ? 'selected' : '') }}>Pertanian, perikanan & peternakan</option>
                                            <option value="2"  {{(isset($category) && $category == "2" ? 'selected' : '') }}>Kecantikan, farmasi</option>
                                            <option value="3"  {{(isset($category) && $category == "3" ? 'selected' : '') }}>Bahan bangunan</option>
                                            <option value="4"  {{(isset($category) && $category == "4" ? 'selected' : '') }}>Konstruksi & desain interior</option>
                                            <option value="5"  {{(isset($category) && $category == "5" ? 'selected' : '') }}>Jasa pengiriman, kurir</option>
                                            <option value="6"  {{(isset($category) && $category == "6" ? 'selected' : '') }}>Dropshipper</option>
                                            <option value="7"  {{(isset($category) && $category == "7" ? 'selected' : '') }}>Elektronik, komputer</option>
                                            <option value="8"  {{(isset($category) && $category == "8" ? 'selected' : '') }}>Fashion, aksesoris</option>
                                            <option value="9"  {{(isset($category) && $category == "9" ? 'selected' : '') }}>Makanan, minuman</option>
                                            <option value="10"  {{(isset($category) && $category == "10" ? 'selected' : '') }}>Furnitur</option>
                                            <option value="11"  {{(isset($category) && $category == "11" ? 'selected' : '') }}>Oleh-Oleh</option>
                                            <option value="12"  {{(isset($category) && $category == "12" ? 'selected' : '') }}>Salon, spa, pusat kebugaran</option>
                                            <option value="13"  {{(isset($category) && $category == "13" ? 'selected' : '') }}>Kerajinan tangan</option>
                                            <option value="14"  {{(isset($category) && $category == "14" ? 'selected' : '') }}>Hotel dan penginapan</option>
                                            <option value="15"  {{(isset($category) && $category == "15" ? 'selected' : '') }}>Keperluan rumah tangga</option>
                                            <option value="16" {{(isset($category) && $category == "16" ? 'selected' : '') }}> Jasa laundry</option>
                                            <option value="17"  {{(isset($category) && $category == "17" ? 'selected' : '') }}>alat medis, sport, musik</option>
                                            <option value="18"  {{(isset($category) && $category == "18" ? 'selected' : '') }}>Jasa fotografi</option>
                                            <option value="19"  {{(isset($category) && $category == "19" ? 'selected' : '') }}>Tanaman, hewan peliharaan</option>
                                            <option value="20"  {{(isset($category) && $category == "20" ? 'selected' : '') }}>Percetakan, ATK</option>
                                            <option value="21"  {{(isset($category) && $category == "21" ? 'selected' : '') }}>Restoran, cafe</option>
                                            <option value="22"  {{(isset($category) && $category == "22" ? 'selected' : '') }}>Pariwisata dan travel</option>
                                            <option value="23"  {{(isset($category) && $category == "23" ? 'selected' : '') }}>Mainan</option>
                                            <option value="24"  {{(isset($category) && $category == "24" ? 'selected' : '') }}>Bengkel, sparepart</option>
                                            <option value="25"  {{(isset($category) && $category == "25" ? 'selected' : '') }}>Lainnya</option>
                                        </select>
                                        <span id="loanApplicantCategoryError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6 select2-block" id="loanApplicantOwnership_block">
                                        <label for="" class="form-label">{{ __('profile.ownership.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-decimal limit" name="loanApplicantOwnership" id="loanApplicantOwnership" placeholder="{{ __('profile.ownership.placeholder') }}" required value="{{$ownership_percentage ?? ''}}">
                                        <span id="loanApplicantOwnershipError" class="js-validation text-danger"></span>
                                    </div>
                                    {{--<div class="col-md-6 select2-block" id="loanApplicantRelationshipWithBorrower_block">
                                        <label for="" class="form-label">{{ __('profile.relationship_with_borrower.title') }}<span class="text-danger">*</span></label>
                                        <select class="form-select" data-placeholder="{{ __('profile.relationship_with_borrower.select') }}" id="loanApplicantRelationshipWithBorrower" name="loanApplicantRelationshipWithBorrower" required>
                                            <option value="">{{ __('profile.relationship_with_borrower.select') }}</option>
                                            <option value="1"  {{(isset($home_ownership_status) && $home_ownership_status == "1" ? 'selected' : '') }}>PARENT</option>
                                            <option value="2"  {{(isset($home_ownership_status) && $home_ownership_status == "2" ? 'selected' : '') }}  >SIBLING</option>
                                            <option value="3"  {{(isset($home_ownership_status) && $home_ownership_status == "3" ? 'selected' : '') }}>SPOUSE</option>
                                            <option value="4"  {{(isset($home_ownership_status) && $home_ownership_status == "4" ? 'selected' : '') }}>COLLEAGUE</option>
                                            <option value="5"  {{(isset($home_ownership_status) && $home_ownership_status == "5" ? 'selected' : '') }}>PROFESSIONAL</option>
                                            <option value="6"  {{(isset($home_ownership_status) && $home_ownership_status == "6" ? 'selected' : '') }}>OTHER</option>
                                        </select>
                                        <span id="loanApplicantRelationshipWithBorrowerError" class="js-validation text-danger"></span>
                                    </div>--}}

                                </div>

                                <div class="row g-2 g-lg-4">
                                    <div class="col-md-6">
                                        <button class="btn btn-secondary greycolorback w-100 text-white back" type="button">
                                            <img class="me-2" src="{{ URL::asset('front-assets/images/icons/page2_arrow.png') }}" alt="arrow" srcset=""> {{__('profile.back')}}
                                        </button>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="hidden" class="form-control" id="form_type" name="form_type" value="loanApplicantBusiness">
                                        <input type="hidden" class="form-control" id="applicantId" name="applicantId" value="{{$applicantId ??''}}">
                                        <button class="btn btn-primary bluecolornext w-100 text-white step loan_applicatnt_business_submit" id="loan_applicatnt_business_submit" name="loan_applicatnt_business_submit" href="javascript:void(0)" type="button">
                                            {{__('profile.continue.title')}} <img class="ms-2" src="{{ URL::asset('front-assets/images/icons/page1_arrow.png') }}" alt="arrow" srcset="">
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                    <!-- Page4 -->
                    <!-- Page5 -->
                    <div class="card  mb-lg-5 shadow" @if(isset($form_type) && $form_type == '5') style="display:block" @elseif(isset($form_type) && $form_type != '5') style="display:none"  @else style="display:none"@endif>
                        <div class="card-header bg-white">
                            <div class="d-flex align-items-center">
                                <div class="col-md-auto me-3">
                                    <div class="circle per-100">
                                        <div class="f-14 inner">5/5</div>
                                      </div>
                                </div>
                                <div class="col-md-auto">
                                    <div class="f-15 fw-bold">{{ __('profile.business_address.title') }}</div>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                        <form name="loanBusinessAddressForm" id="loanBusinessAddressForm" enctype="multipart/form-data">
                            <div class="formsection_1">
                                <div class="row g-4 mb-4 pt-2">
                                    <div class="col-md-12">
                                        <label for="" class="form-label">{{ __('profile.address_line_1.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-filter limit" name="loanBusinessAddressLine1" id="loanBusinessAddressLine1" placeholder="{{ __('profile.address_line_1.placeholder') }}"  value="{{$business_address1 ?? ''}}">
                                        <span id="loanBusinessAddressLine1Error" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="" class="form-label">{{ __('profile.address_line_2.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-filter limit" name="loanBusinessAddressLine2" id="loanBusinessAddressLine2" placeholder="{{ __('profile.address_line_2.placeholder') }}"  value="{{$business_address2 ?? ''}}">
                                        <span id="loanBusinessAddressLine2Error" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.sub_district.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-alpha limit" name="loanBusinessAddressSubDistrict" id="loanBusinessAddressSubDistrict" placeholder="{{ __('profile.sub_district.placeholder') }}"  value="{{$business_sub_district ?? ''}}">
                                        <span id="loanBusinessAddressSubDistrictError" class="js-validation text-danger"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.district.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-alpha limit" name="loanBusinessAddressDistrict" id="loanBusinessAddressDistrict" placeholder="{{ __('profile.district.placeholder') }}"  value="{{$business_district ?? ''}}">
                                        <span id="loanBusinessAddressDistrictError" class="js-validation text-danger"></span>

                                    </div>

                                    <div class="col-md-12 select2-block" id="loanBusinessAddressCountry_block">
                                        <label for="" class="form-label">{{ __('profile.country.title') }}<span class="text-danger">*</span></label>
                                        <select class="form-select" data-placeholder="{{ __('profile.country.select') }}" id="loanBusinessAddressCountry" name="loanBusinessAddressCountry">
                                            <option value="" >{{ __('profile.country.select') }}</option>
                                            <option value="{{$country->id}}"  {{(isset($business_country) && $business_country == $country->id ? 'selected' : '') }}>{{$country->name}}</option>
                                        </select>
                                        <span id="loanBusinessAddressCountryError" class="js-validation text-danger"></span>
                                    </div>


                                    <div class="col-md-12 select2-block" id="loanBusinessAddressProvinces_block">
                                        <label for="" class="form-label">{{ __('profile.provinces.title') }}<span class="text-danger">*</span></label>
                                        <select class="form-select" data-placeholder="{{ __('profile.provinces.select') }}" id="loanBusinessAddressProvinces" name="loanBusinessAddressProvinces">
                                            <option value="" >{{ __('profile.provinces.select') }}</option>
                                            @foreach ($states as $state)
                                            <option value="{{ $state->id }}" @if(isset($business_provinces_id) && $state->id == $business_provinces_id) selected @endif>{{ $state->name }}</option>
                                            @endforeach
                                            <option value="-1" @if(isset($business_provinces_id) && '-1' == $business_provinces_id) selected @endif>Other</option>
                                        </select>
                                        <span id="loanBusinessAddressProvincesError" class="js-validation text-danger"></span>

                                    </div>
                                    <div class="col-md-6 mb-4 hide" id="state_block-business">
                                        <label for="state" class="form-label">Othe provinces<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="state_business" id="state_business" required  value="{{$business_other_provinces ?? ''}}">
                                        <span id="state_businessError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-12 select2-block" id="loanBusinessAddressCity_block">
                                        <label for="" class="form-label">{{ __('profile.city.title') }}<span class="text-danger">*</span></label>
                                        <select class="form-select" data-placeholder="{{ __('profile.city.select') }}" id="loanBusinessAddressCity" name="loanBusinessAddressCity" data-selected-city={{$business_city_id ??''}}>
                                            <option value="" >{{ __('profile.city.select') }}</option>
                                            <option value="-1"  @if(isset($business_city_id) && '-1' == $business_city_id) selected @endif>Other</option>
                                        </select>
                                        <span id="loanBusinessAddressCityError" class="js-validation text-danger"></span>

                                    </div>
                                    <div class="col-md-6 mb-4 hide" id="city_block-business">
                                        <label for="city" class="form-label">Other city<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="city_business" id="city_business"  value="{{$business_other_city ?? ''}}">
                                        <span id="city_businessError" class="js-validation text-danger"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{ __('profile.postal_code.title') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-number " name="loanBusinessAddressPostalCode" id="loanBusinessAddressPostalCode" placeholder="{{ __('profile.postal_code.placeholder') }}"  value="{{$business_postal_code ?? ''}}" maxlength="7">
                                        <span id="loanBusinessAddressPostalCodeError" class="js-validation text-danger"></span>
                                    </div>

                                    <input type="hidden" name="userphonecode" id="userphonecode" data-phone="{{ Auth::user()->phone_code?str_replace('+','',Auth::user()->phone_code):62 }}" data-country="{{ Auth::user()->phone_code?strtolower(getRecordsByCondition('countries',['phone_code'=>Auth::user()->phone_code?str_replace('+','',Auth::user()->phone_code):62],'iso2',1)):'id' }}">

                                </div>

                                <div class="row g-2 g-lg-4">
                                    <div class="col-md-6">
                                        <button class="btn btn-secondary greycolorback w-100 text-white back" type="button">
                                            <img class="me-2" src="{{ URL::asset('front-assets/images/icons/page2_arrow.png') }}" alt="arrow" srcset=""> {{__('profile.back')}}
                                        </button>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="hidden" class="form-control" id="form_type" name="form_type" value="loanApplicantBusinessAddress">
                                        <button  data-href="_blank" data-target="{{Route('settings.credit.show')}}" class="btn btn-primary bluecolornext w-100 text-white step loan_applicatnt_business_address_submit" id="loan_applicatnt_business_address_submit" name="loan_applicatnt_business_address_submit" href="javascript:void(0)" type="button" id="submit">
                                            {{__('profile.submit.title')}} <img class="ms-2" src="{{ URL::asset('front-assets/images/icons/page1_arrow.png') }}" alt="arrow" srcset="">
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                    <!-- Page5 -->
                </div>
            </div>
        </div>
        <!-- NIB and NPWP modal -->
        <div class="modal fade" id="NibNpwpModal" aria-hidden="true" aria-labelledby="NibNpwpModalLabel" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header d-none">
                        <h5 class="modal-title" id="NibNpwpModalLabel">{{ __('rfqs.Add NIP and NPWP') }}</h5>
                        <button type="button" class="btn-close" data-bs-toggle="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <img src="{{ URL::asset('front-assets/images/icons/ref_id_1.png') }}" alt="NIP and NPWP">
                        <h5 class="px-4 py-4">{{ __('rfqs.company_info_required_msg') }}</h5>

                        <button type="button" class="btn btn-primary" id="addNibNpwpBtn" >
                            <img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}" alt="Ok" class="pe-1">
                            {{ __('admin.ok') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endsection


    @section('script')
    <!--begin: plugin js for this page -->
    <script type="text/javascript" src="{{ URL::asset('front-assets/js/front/crop/scripts/jquery.Jcrop.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('front-assets/js/front/crop/scripts/jquery.SimpleCropper.js') }}"></script>
    <script type="text/javascript" src='{{ asset("front-assets/js/front/croppie.js")}}'></script>
    <script type="text/javascript" src="{{ URL::asset('front-assets/js/datatables/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('front-assets/js/datatables-bs4/dataTables.bootstrap4.js') }}"></script>
    <!--end: plugin js for this page -->

    @endsection

    @section('custom-script')
    <script src="{{ URL::asset('front-assets/intlTelInput/js/intlTelInput.js') }}"></script>
    <script type="text/javascript">
        var slider = document.getElementById("customRange3");
        var maxCreditLimit = "{{$maxCreditLimit}}";

        $('#loanApplicantOwnership').keypress(function(event) {
        if($(this).val()>=100)
        {
            event.preventDefault();
        }
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
            ((event.which < 48 || event.which > 57) &&
            (event.which != 0 && event.which != 8))) {
            event.preventDefault();
        }

        var text = $(this).val();

        if ((text.indexOf('.') != -1) &&
            (text.substring(text.indexOf('.')).length > 2) &&
            (event.which != 0 && event.which != 8) &&
            ($(this)[0].selectionStart >= text.length - 2)) {
            event.preventDefault();
        }

    });



        /****************************************begin:Credit Forms***************************************/
        var SnippetCreditForm = function(){

           var moveNext = function(){

                $('.step').on('click', function(){
                    let current_form = $(this).closest('.card.mb-lg-5.shadow');
                    let current_form_id=$(this).closest("form").attr('id');
                    var formData = new FormData($("#"+current_form_id)[0]);

                    $.ajax({
                    url: "{{ route('settings.loan-application-ajax') }}",
                    type: 'POST',
                            type: "POST",
                            data: formData,
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            contentType: false,
                            processData: false,
                            success: function(data) {
                                if(current_form_id=='loanBusinessAddressForm'){
                                window.location.href = "{{URL::to('/settings/credit-show')}}"
                                }
                                current_form.hide();
                                current_form.next().show();
                                $('.js-validation').html('')
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

                                }
                            }
                        });

                });

           },

           movePrevious = function(){

                $('.back').on('click', function(){
                    let current_form = $(this).closest('.card.mb-lg-5.shadow');
                    current_form.hide();
                    current_form.prev().show();
                });

           },

           removeSingleError = function() {

                $('#loanApplicantDetailsForm input[type="text"]').on('input', function (evt) {
                    let inputId = $(this).attr('id');
                    $('#'+inputId+'Error').html('');
                });

                $('#loanApplicantDetailsForm input[type="email"]').on('click', function (evt) {
                    let inputId = $(this).attr('id');
                    $('#'+inputId+'Error').html('');
                });

                $('#loanApplicantDetailsForm input[type="file"]').on('change', function (evt) {
                    let inputId = $(this).attr('id');
                    $('#'+inputId+'Error').html('');
                });

                $('#loanApplicantDetailsForm select').on('change', function (evt) {
                    let inputId = $(this).attr('id');
                    $('#'+inputId+'Error').html('');
                });

                $('#loanApplicantAddressForm input[type="text"]').on('input', function (evt) {
                    let inputId = $(this).attr('id');
                    $('#'+inputId+'Error').html('');
                });

                $('#loanApplicantAddressForm select').on('change', function (evt) {
                    let inputId = $(this).attr('id');
                    $('#'+inputId+'Error').html('');
                });

                $('#loanApplicantBusinessForm input[type="text"]').on('input', function (evt) {
                    let inputId = $(this).attr('id');
                    $('#'+inputId+'Error').html('');
                });

                $('#loanApplicantBusinessForm select').on('change', function (evt) {
                    let inputId = $(this).attr('id');
                    $('#'+inputId+'Error').html('');
                });
                $('#loanApplicantBusinessForm input[type="file"]').on('change', function (evt) {
                    let inputId = $(this).attr('id');
                    $('#'+inputId+'Error').html('');
                });

                $('#loanApplicantBusinessForm input[type="email"]').on('click', function (evt) {
                    let inputId = $(this).attr('id');
                    $('#'+inputId+'Error').html('');
                });

                $('#loanBusinessAddressForm input[type="text"]').on('input', function (evt) {
                    let inputId = $(this).attr('id');
                    $('#'+inputId+'Error').html('');
                });

                $('#loanBusinessAddressForm select').on('change', function (evt) {
                    let inputId = $(this).attr('id');
                    $('#'+inputId+'Error').html('');
                });
                 $('#loanApplicantBusinessDescription').on('input', function (evt) {
                    let inputId = $(this).attr('id');
                    $('#'+inputId+'Error').html('');
                });

           },

           characterValidation = function () {

                $('.charlength20').on('keypress',function(){
                    if($(this).val().length>20){
                        return false;
                    }
                });

                $('.charlength60').on('keypress',function(){
                    if($(this).val().length>60){
                        return false;
                    }
                });

                $('.charlength10').on('keypress',function(){
                    if($(this).val().length>9){
                        return false;
                    }
                });


                $('.charlength250').on('keypress',function(){
                    if($(this).val().length>250){
                        return false;
                    }
                });



           },

           valueValidation = function () {

                $('.valueLimit').on('keypress paste',function(){
                    if($(this).val()>=maxCreditLimit){
                        return false;
                    }
                });

           },

           validations = function () {

               /***********begin: Loan Amount Event Validation*************/

               $('#loanAmount').on('keypress keyup keydown ',function(){
                  // slider.value=  $(this).val();
+                  $('#loanAmount').val($(this).val().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    if( $(this).val()!='')
                    slider.value= $(this).val().replace(/,/g , '');

               });

              
               $('#loanAmount').on('focusout',function(){
                   if(parseInt($(this).val().replace(/,/g , '')) < 5000000){
                       $(this).val('5,000,000');
                       return false;
                   }

                   if($(this).val().replace(/,/g , '') == ''){
                       $(this).val('5,000,000');
                       return false;
                   }
                   slider.value= $(this).val().replace(/,/g , '');
                   if($(this).val().replace(/,/g , '')>maxCreditLimit){
                       return false;
                   }
               });
               /***********end: Loan Amount Event Validation*************/

               /***********begin: Phone Number Event Validation*************/
               $('#phoneNumber').on('keypress paste',function(event){
                   if($(this).val().length>=16){
                       event.preventDefault();
                       return false;
                   }
               });

               $('#loanApplicantBusinessPhone').on('keypress paste',function(event){
                   if($(this).val().length>=16){
                       event.preventDefault();
                       return false;
                   }
               });

               $('#otherMemberPhone').on('keypress paste',function(event){
                   if($(this).val().length>=16){
                       event.preventDefault();
                       return false;
                   }
               });
               /***********end: Phone Number Event Validation*************/

               /***********begin: KTPNIK Event Validation*************/
               $('#ktpNik').on('keypress paste',function(event){
                   if($(this).val().length>=16){
                       event.preventDefault();
                       return false;
                   }
               });

               $('#otherKtpNik').on('keypress paste',function(event){
                   if($(this).val().length>=16){
                       event.preventDefault();
                       return false;
                   }
               });
               /***********end: KTPNIK Event Validation*************/

               /***********begin: Net Salary Event Validation*************/
               $('#netSalary').on('keypress paste',function(event){
                   if($(this).val().length>=255){
                       event.preventDefault();
                       return false;
                   }
               });
               /***********end: Net Salary Event Validation*************/

               /***********begin: Other Income Event Validation*************/
               $('#otherIncome').on('keypress paste',function(event){
                   if($(this).val().length>=255){
                       event.preventDefault();
                       return false;
                   }
               });
               /***********end: Other Income Event Validation*************/

               /***********begin: Duration of Stay Event Validation*************/
               $('#loanApplicantDurationOfStay').on('keypress paste',function(event){
                   if($(this).val().length>=2){
                       event.preventDefault();
                       return false;
                   }
               });
               $('#loanAmount').on('focusout',function(){

                });
               /***********end: Duration of Stay Event Validation*************/

           },

           initiateDates = function(){
               var d = new Date();
               var year = d.getFullYear() - 18;
               d.setFullYear(year);
               $('.select-date').datepicker({


                        onSelect: function(date) {
                            let selectDateId = $(this).attr('id');
                            $('#'+selectDateId).parsley().reset();
                            $('#'+selectDateId+'Error').text('')

                        },
                        maxDate:"-1",
                        dateFormat: "dd-mm-yy",
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "-100:+0",
                        onChangeMonthYear: function(year, month, inst) {
                        var selectedDates =  $(this).val().split("-");
                        let currentDay = selectedDates[0];
                            if(month < 10)
                            {
                                month='0'+month;
                            }
                            $(this).val(currentDay + "-"+month + "-" + year);
                        }
                });

                $('.select-date-age').datepicker({
                    onSelect: function(date) {

                        let selectDateId = $(this).attr('id');
                        $('#'+selectDateId).parsley().reset();
                        $('#'+selectDateId+'Error').text('')

                   },
                   dateFormat: "dd-mm-yy",
                   changeMonth: true,
                   changeYear: true,
                   yearRange: '1930:' + year + '', defaultDate: d,  maxDate: '-18Y',
                   onChangeMonthYear: function(year, month, inst) {
                    var selectedDates =  $(this).val().split("-");
                    let currentDay = selectedDates[0];
                        if(month < 10)
                        {
                            month='0'+month;
                        }
                        $(this).val(currentDay + "-"+month + "-" + year);
                    }

               });


            },

           initPhoneCountryCodes = function(inputId, subInputId){
               var input = document.querySelector(inputId);
               var iti = window.intlTelInput(input, {
                   initialCountry:"id",
                   separateDialCode:true,
                   dropdownContainer:null,
                   preferredCountries:["id"],
                   hiddenInput:subInputId
               });

               let userPhoneCode = $('#userphonecode').attr('data-phone');
               let country       = $('#userphonecode').attr('data-country')

               $('input[name="'+subInputId+'"]').val(userPhoneCode).attr('iso2', country);

               iti.setCountry(country);


           },

           npwpExist = function(){
               let companyNpwp = $('#loanApplicantBusinessNpwpNumber').val();
               let companySiup = $('#loanApplicantSiupNumber').val();

               if (companyNpwp=='' || companySiup == '') {
                let companyNpwp = $('#loanApplicantBusinessNpwpNumber').val();
                let companyNib = $('#loanApplicantSiupNumber').val();
                    $('#NibNpwpModal').modal('show');
                    $('#NibNpwpModal').css({'pointer-events' : 'none'});

                    //Disable inputs
                    $('input[type="text"]').prop('disabled', true);
                }
           },

           redirectToCompanyInfo = function(){
               $("#addNibNpwpBtn").on('click', function(){
                   sessionStorage.clear();
                   sessionStorage.setItem("profile-lastlocation", JSON.stringify({
                       mainTab: "company-tab",
                       secondTab: "change_company_info"
                   }));
                   window.location='<?php echo e(route("profile")); ?>';
               });

           };

           return {
               init: function () {
                   moveNext(),
                   movePrevious(),
                   characterValidation(),
                   valueValidation(),
                   validations(),
                   removeSingleError(),
                   initiateDates(),
                   initPhoneCountryCodes("#phoneNumber", "phoneCode"),                                           //init phone code for phoneCode
                   initPhoneCountryCodes("#otherMemberPhone", "otherMemberCode")                                //init phone code for otherMemberCode
                   initPhoneCountryCodes("#loanApplicantBusinessPhone", "loanApplicantBusinessCode"),     //init phone code for otherMemberCode
                   npwpExist(),
                   redirectToCompanyInfo()
               }
            }

        }(1);

        /****************************************end:Credit Forms***************************************/

        /***********************************begin: Manage User Address Detail*******************************/
        var SnippetAddUserAddress = function(){

            var selectStateGetCity = function(){

                    $('#provincesId').on('change',function(){

                        let state = $(this).val();
                        let targetUrl = "{{ route('admin.city.by.state',":id") }}";
                        targetUrl = targetUrl.replace(':id', state);
                        var newOption = '';

                        // Add Remove Other State filed
                        if (state == -1) {

                            $('#state_block').removeClass('hide');
                            $('#state').attr('required','required');

                            $('#cityId').empty();

                            //set default options on other state mode
                            newOption = new Option('select city','', true, true);
                            $('#cityId').append(newOption).trigger('change');

                            newOption = new Option('Other','-1', true, true);
                            $('#cityId').append(newOption).trigger('change');


                        } else {

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

                                            newOption = new Option('{{ __('admin.select_city') }}', '');
                                            $('#cityId').append(newOption).trigger('change');

                                            for (let i = 0; i < response.data.length; i++) {
                                                newOption = new Option(response.data[i].name, response.data[i].id);
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

                                            let selectedAddressCity = $('#cityId').attr('data-selected-city');

                                            if (selectedAddressCity != null && selectedAddressCity != '') {
                                                $('#cityId').val(selectedAddressCity).trigger('change'); //Use for select2
                                            } else {
                                                $('#cityId').val(null).trigger('change');
                                            }

                                        }

                                    },
                                    error: function () {

                                    }
                                });
                            } else {
                            $('#cityId').empty();

                            //set default options on other state mode
                            newOption = new Option('{{ __('admin.select_city') }}','', true, true);
                            $('#cityId').append(newOption).trigger('change');

                            newOption = new Option('Other','-1', true, true);
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

                            $('#city_block').removeClass('hide');
                            $('#city').attr('required','required');

                        } else {

                            $('#city_block').addClass('hide');
                            $('#city').removeAttr('required','required');

                        }

                    });

                },

                initiateCityState = function(){

                    let state               =   $('#state').val();
                    let selectedState       =   $('#provincesId').val();
                    let selectedCity        =   $("#cityId").attr('data-selected-city');
                    if (state != null && state !='') {
                        $('#provincesId').val('-1').trigger('change');
                    }

                    if (selectedState !='' && selectedState != null) {
                        $('#provincesId').val(selectedState).trigger('change');
                    }

                    if (selectedCity !='' && selectedCity!=null ) {
                        $('#cityId').val(selectedCity).trigger('change')

                    }

                },

                select2Initiate = function(){

                    $('#provincesId1').select2({
                        dropdownParent  : $('#stateId_block1'),
                        placeholder:  $(this).attr('data-placeholder')

                    });

                    $('#cityId1').select2({
                        dropdownParent  : $('#cityId_block1'),
                        placeholder:  $(this).attr('data-placeholder')

                    });

                };

            return {
                init:function(){
                   // select2Initiate(),
                        selectStateGetCity(),
                        selectCitySetOtherCity(),
                        initiateCityState()
                }
            }

        }(1);
        /***********************************end: Manage User Address Detail*******************************/


        /*****begin: Add User Address Detail******/
        var SnippetAddUserAddress1 = function(){

            var selectStateGetCity = function(){

            $('#loanBusinessAddressProvinces').on('change',function(){
                let state = $(this).val();
                let targetUrl = "{{ route('admin.city.by.state',":id") }}";
                targetUrl = targetUrl.replace(':id', state);
                var newOption = '';
                // Add Remove Other State filed
                if (state == -1) {
                    $('#state_block-business').removeClass('hide');
                    $('#state_business').attr('required','required');

                    $('#loanBusinessAddressCity').empty();

                    //set default options on other state mode
                    newOption = new Option('{{ __('admin.select_city') }}','', true, true);
                    $('#loanBusinessAddressCity').append(newOption).trigger('change');

                    newOption = new Option('Other','-1', true, true);
                    $('#loanBusinessAddressCity').append(newOption).trigger('change');


                } else {

                    $('#state_block-business').addClass('hide');
                    $('#state_business').removeAttr('required','required');

                    $('#city_block-business').addClass('hide');
                    $('#city-business').removeAttr('required','required');

                    //Fetch cities by state
                    if (state != '') {
                        $.ajax({
                            url: targetUrl,
                            type: 'POST',
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},

                            success: function (response) {

                                if (response.success) {

                                    $('#loanBusinessAddressCity').empty();

                                    newOption = new Option('{{ __('admin.select_city') }}', '', true, true);
                                    $('#loanBusinessAddressCity').append(newOption).trigger('change');

                                    for (let i = 0; i < response.data.length; i++) {
                                        newOption = new Option(response.data[i].name, response.data[i].id, true, true);
                                        $('#loanBusinessAddressCity').append(newOption).trigger('change');
                                    }

                                    newOption = new Option('Other', '-1', true, true);
                                    $('#loanBusinessAddressCity').append(newOption).trigger('change');

                                    /*******begin:Add and remove last null option for no conflict*******/
                                    newOption = new Option('0', '0', true, true);
                                    $('#loanBusinessAddressCity').append(newOption).trigger('change');
                                    $('#loanBusinessAddressCity').each(function () {
                                        $(this).find("option:last").remove();
                                    });
                                    /*******end:Add and remove last null option for no conflict*******/

                                    let selectedAddressCity = $('#loanBusinessAddressCity').attr('data-selected-city');

                                    if (selectedAddressCity != null && selectedAddressCity != '') {
                                        $('#loanBusinessAddressCity').val(selectedAddressCity).trigger('change'); //Use for select2 city
                                    } else {
                                        $('#loanBusinessAddressCity').val(null).trigger('change');
                                    }

                                }

                            },
                            error: function () {

                            }
                        });
                    }else {
                        $('#loanBusinessAddressCity').empty();

                        //set default options on other state mode
                        newOption = new Option('{{ __('admin.select_city') }}','', true, true);
                        $('#loanBusinessAddressCity').append(newOption).trigger('change');

                        newOption = new Option('Other','-1', true, true);
                        $('#loanBusinessAddressCity').append(newOption).trigger('change');

                        $('#loanBusinessAddressCity').val(null).trigger('change');
                    }

                }

            });

        },

            selectCitySetOtherCity1 = function(){

                $('#loanBusinessAddressCity').on('change',function(){

                    let city = $(this).val();


                    // Add Remove Other City filed
                    if (city == -1) {
                        $('#city_block-business').removeClass('hide');
                        $('#city_business').attr('required','required');

                    } else {

                        $('#city_block-business').addClass('hide');
                        $('#city_business').removeAttr('required','required');

                    }

                });

            },

            initiateCityState = function(){

                let state               =   $('#state_business').val();
                let selectedState       =   $('#loanBusinessAddressProvinces').val();
                let selectedCity        =   $("#city_id").attr('data-selected-city');

                if (state != null && state !='') {
                    $('#loanBusinessAddressProvinces').val('-1').trigger('change');
                }

                if (selectedState !='' && selectedState != null) {
                    $('#loanBusinessAddressProvinces').val(selectedState).trigger('change');
                }

                if (selectedCity !='' && selectedCity!=null ) {

                    $('#loanBusinessAddressCity').val(selectedCity).trigger('change')

                }

            },

            select2Initiate = function(){

                $('#state_id').select2({
                    dropdownParent  : $('#stateId_block1'),
                    placeholder:  $(this).attr('data-placeholder')

                });

                $('#city_id').select2({
                    dropdownParent  : $('#cityId_block1'),
                    placeholder:  $(this).attr('data-placeholder')

                });

            };
            return {
                init:function(){
                    select2Initiate(),
                    selectStateGetCity(),
                    selectCitySetOtherCity1(),
                    initiateCityState()
                }
            }

        }(1);

        /*****end: Add User Address Detail******/

        jQuery(document).ready(function(){
            SnippetCreditForm.init();
            SnippetAddUserAddress.init();
            SnippetAddUserAddress1.init();

            //Submit form 5
            $(".submit").click(function () {
                window.document.location = $(this).data("target");
            });

        });
        slider.oninput = function() {
            $('#loanAmount').val(Math.round(this.value).toLocaleString());
        }
function setSlider(obj) {
    var sliderVal = parseInt(obj.value)
    slider.value= sliderVal;
}
function showFile(input) {

    let file = input.files[0];
    console.log(file);
    let size = Math.round((file.size / 1024));
    if(size > 5200){
        swal({
            icon: 'error',
            title: '',
            text: '{{ __('profile.file_size_under_5mb') }}',
        })
    } else {
        let fileName = file.name;
        let allowed_extensions = new Array("jpg","jpeg");
        let file_extension = fileName.split('.').pop();
        let file_name_without_extension = fileName.replace(/\.[^/.]+$/, '');
        let image_path = '{{URL::asset("assets/icons/times-circle copy.png")}}';
        let text = '{{ __('profile.plz_upload_image_file') }}';

        for (let i = 0; i < allowed_extensions.length; i++) {
            if (allowed_extensions[i] == file_extension) {
                valid = true;
                let download_function = "'" + input.name + "', " + "'" + fileName + "'";
                if(file_name_without_extension.length >= 10) {
                    fileName = file_name_without_extension.substring(0,10) +'....'+file_extension;
                }
                $('#file-' + input.name).html('');
                $('#file-' + input.name).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.name + 'Download " style="text-decoration: none">' + fileName + '</a></span><span class="ms-2"><a class="' + input.name + ' downloadbtn hidden" href="javascript:void(0);" title="{{ __('profile.download_file') }}" onclick="downloadimg(' + download_function + ')" style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a></span>');
                return;
            }
        }
        valid = false;
        swal({
            text: text,
            icon: "/assets/images/warn.png",
            title: '',
        })
    }
}



    </script>
    @endsection

