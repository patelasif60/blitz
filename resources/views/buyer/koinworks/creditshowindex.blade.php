@extends('buyer/layouts/backend/backend_single_layout')
@php
$authId = auth()->id();
@endphp

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
        /* .iti--separate-dial-code .iti__selected-flag{font-size: 12px;  height: 38px;}
        @media (min-width: 1400px){
            .iti--separate-dial-code .iti__selected-flag{  height: 48px;}
        }
        @media (max-width: 991px){
            .iti--separate-dial-code .iti__selected-flag{  height: 27px;font-size: 10px;}
        } */
    </style>
    @endsection

    @section('content')

        <div class="container Loanapplication py-3">
            <div class="row floatlabels">
                <div class=" d-flex align-items-center pb-5 px-2">
                    <div>
                        <h1 class="mb-0">Request For Loan</h1>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn btn-warning ms-auto btn-sm py-1" id="backBtn" style="padding-top: .1rem;padding-bottom: .1rem;">
                        <img src="{{ URL::asset('front-assets/images/icons/angle-left.png') }}" alt="">
                        {{ __('profile.back') }}
                    </a>
                </div>
                 <form id="loanApplicationDetailsForm" name="loanApplicationDetailsForm" enctype="multipart/form-data">
                            <div class="col-md-12 mt-lg-3">
                                <div class="card shadow  mb-3 mb-lg-5">
                                    <div class="card-header p-3 d-flex align-items-center bg-white">
                                        <div class="fw-bold">{{ __('profile.your_choice.title') }}</div>
                                        {{--<a class="ms-auto" href="#" ><img class="" src="{{ URL::asset('front-assets/images/icons/koinworkedit.png') }}" alt="arrow" srcset="">
                                        </a>--}}
                                        <a class="ms-auto" href="{{route('settings.loan-application-edit','f=1')}}"><img class="" src="{{ URL::asset('front-assets/images/icons/koinworkedit.png') }}" alt="arrow" srcset=""></a>
                                    </div>
                                    <div class="card-body">
                                        <div class="displaydetail row mx-0">
                                            <div class="col-md-6">
                                                <label class="f-12" for="">{{ __('profile.loan_amount.title') }}</label>
                                                <div class="f-14 fw-bold">{{$loanAmount}}</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="card shadow mb-3 mb-lg-5">
                                    <div class="card-header p-3 d-flex align-items-center bg-white">
                                        <div class="fw-bold">{{ __('profile.personal_information.title') }}</div>
                                        {{--<a class="ms-auto" href="#"><img class="" src="{{ URL::asset('front-assets/images/icons/koinworkedit.png') }}" alt="arrow" srcset="">
                                        </a>--}}
                                        <a class="ms-auto" href="{{route('settings.loan-application-edit','f=2')}}"><img class="" src="{{ URL::asset('front-assets/images/icons/koinworkedit.png') }}" alt="arrow" srcset=""></a>

                                    </div>
                                    <div class="card-body">
                                        <div class="displaydetail row mx-0 g-2 g-lg-4" >
                                            <div class="d-flex align-items-center">
                                                <img src="{{ URL::asset('front-assets/images/icons/koinworkicon1.png') }}" class="me-2" alt="" srcset="">
                                                <span class="bluetextcolorkoinworks fw-bold">{{ __('profile.your_information.title') }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.Name') }}</label>
                                                <div class="f-14 fw-bold">{{$first_name.' '.$last_name}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.Email') }}</label>
                                                <div class="f-14 fw-bold">{{$email}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.phone.title') }}</label>
                                                <div class="f-14 fw-bold">+{{$phone_code}} {{$phone_number}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.gender.title') }}</label>
                                                <div class="f-14 fw-bold">@if($gender == 2) Female @elseif($gender == 1) Male @endif</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.ktpnik.title') }}</label>
                                                <div class="f-14 fw-bold">{{$ktp_nik}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.ktp_image.title') }}</label>
                                                <div class="f-14 fw-bold"><a class="text-decoration-none downloadPo d-flex align-items-center" data-id="{{$authId}}" id="downloadPo{{$authId}}" href="{{ Storage::url($ktp_image) }}"  download>Download <svg id="Layer_1" width="10px" class="ms-1" fill="#0d6efd" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"></path><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"></path></svg></a></div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.ktp_with_selfie_image.title') }}</label>
                                                <div class="f-14 fw-bold"><a class="text-decoration-none downloadPo d-flex align-items-center" data-id="{{$authId}}" id="downloadPo{{$authId}}"  href="{{ Storage::url($ktp_with_selfie_image) }}"  download>Download <svg id="Layer_1" width="10px" class="ms-1" fill="#0d6efd" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"></path><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"></path></svg></a></div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.family_card_image.title') }}</label>
                                                <div class="f-14 fw-bold"><a class="text-decoration-none downloadPo d-flex align-items-center" data-id="{{$authId}}" id="downloadPo{{$authId}}"  href="{{ Storage::url($family_card_image) }}"  download>Download <svg id="Layer_1" width="10px" class="ms-1" fill="#0d6efd" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"></path><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"></path></svg></a></div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.date_of_birth.title') }}</label>
                                                <div class="f-14 fw-bold">{{$date_of_birth}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.place_of_birth.title') }}</label>
                                                <div class="f-14 fw-bold">{{$place_of_birth}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.marital_status.title') }}</label>
                                                <div class="f-14 fw-bold">@if($marital_status == 1) KAWIN @elseif($marital_status == 2) BELUM KAWIN @elseif($marital_status == 3) CERAI MATI @elseif($marital_status == 4) CERAI HIDUP @endif</div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.religion.title') }}</label>
                                                <div class="f-14 fw-bold">@if($religion == 1) ISLAM
                                                @elseif($religion == 2) KATHOLIK
                                                @elseif($religion == 3)KRISTEN
                                                @elseif($religion == 4)BUDHA
                                                @elseif($religion == 5)HINDU
                                                @elseif($religion == 6)KONGHUCHU
                                                @elseif($religion == 7)OTHER
                                                @endif</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.education.title') }}</label>
                                                <div class="f-14 fw-bold">{{$education}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.occupation.title') }}</label>
                                                <div class="f-14 fw-bold">{{$occupation}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.my_position.title') }}</label>
                                                <div class="f-14 fw-bold">
                                                    @if($myPosition=='1')DIRECTOR
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.net_salary.title') }}</label>
                                                <div class="f-14 fw-bold">{{$net_salary}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.other_income.title') }}</label>
                                                <div class="f-14 fw-bold">{{$total_other_income}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.other_source_of_income.title') }}</label>
                                                <div class="f-14 fw-bold">@if($other_source_of_income == 1) BUSINESS REVENUE
                                                @elseif($other_source_of_income == 2) FUND REVENUE
                                                @elseif($other_source_of_income == 3) INHERIRTANCE
                                                @elseif($other_source_of_income == 4) SALARY
                                                @elseif($other_source_of_income == 5) PARENT/GUARDIAN
                                                @endif</div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ URL::asset('front-assets/images/icons/koinworkicon1.png') }}" class="me-2" alt="" srcset="">
                                                <span class="bluetextcolorkoinworks fw-bold">{{ __('profile.other_member_information.title') }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.Name') }}</label>
                                                <div class="f-14 fw-bold">{{$other_first_name?? '' }} {{$other_last_name?? '' }}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.relationship_with_borrower.title') }}</label>
                                                <div class="f-14 fw-bold">@if($relationship_with_borrower == 1)PARENT
                                                @elseif($relationship_with_borrower == 2)SIBLING
                                                @elseif($relationship_with_borrower == 3)SPOUSE
                                                @elseif($relationship_with_borrower == 4)COLLEAGUE
                                                @elseif($relationship_with_borrower == 5)PROFESSIONAL
                                                @elseif($relationship_with_borrower == 6)OTHER
                                                @endif</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.ktpnik.title') }}</label>
                                                <div class="f-14 fw-bold">{{$other_ktp_nik?? '' }}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.ktp_image.title') }}</label>
                                                <div class="f-14 fw-bold"><a class="text-decoration-none downloadPo d-flex align-items-center" data-id="{{$authId}}" id="downloadPo{{$authId}}"  href="{{ Storage::url($other_ktp_image) }}"  download>Download <svg id="Layer_1" width="10px" class="ms-1" fill="#0d6efd" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"></path><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"></path></svg></a></div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.phone.title') }}</label>
                                                <div class="f-14 fw-bold">+{{$other_phone_code?? '' }} {{$other_phone_number?? '' }}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.Email') }}</label>
                                                <div class="f-14 fw-bold">{{$other_email ?? '' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="card shadow mb-3 mb-lg-5">
                                    <div class="card-header p-3 d-flex align-items-center bg-white">
                                        <div class="fw-bold">{{ __('profile.personal_address.title') }}</div>
                                        {{--<a class="ms-auto" href="#"><img class="" src="{{ URL::asset('front-assets/images/icons/koinworkedit.png') }}" alt="arrow" srcset="">
                                        </a>--}}
                                        <a class="ms-auto" href="{{route('settings.loan-application-edit','f=3')}}"><img class="" src="{{ URL::asset('front-assets/images/icons/koinworkedit.png') }}" alt="arrow" srcset=""></a>

                                    </div>
                                    <div class="card-body">
                                        <div class="displaydetail row mx-0 g-2 g-lg-4">
                                            <div class="col-md-12">
                                                <label class="f-12" for="">{{ __('profile.address') }}</label>
                                                <div class="f-14 fw-bold">{{$address_line1}}, {{$address_line2}} {{$city??''}} {{$provinces??''}} {{$loanApplicantPostalCode}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.has_lived_here.title') }}</label>
                                                <div class="f-14 fw-bold">@if($has_live_here == 1) Ya
                                                @elseif($has_live_here == 2) Tidak @endif</div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.duration_of_stay.title') }}</label>
                                                <div class="f-14 fw-bold">{{$duration_of_stay}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.home_ownership_status.title') }}</label>
                                                <div class="f-14 fw-bold">@if($home_ownership_status == 1) FAMILY/KELUARGA
                                                @elseif($home_ownership_status == 2) PARENT/ORANG TUA
                                                @elseif($home_ownership_status == 3)RENTAL/KOS
                                                @elseif($home_ownership_status == 4)OWNED/MILIK SENDIRI
                                                @elseif($home_ownership_status == 5)OFFICE RESIDENCE/RUMAH DINAS
                                                @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="card shadow mb-3 mb-lg-5">
                                    <div class="card-header p-3 d-flex align-items-center bg-white">
                                        <div class="fw-bold">{{ __('profile.business.title') }}</div>
                                        {{--<a class="ms-auto" href="#"><img class="" src="{{ URL::asset('front-assets/images/icons/koinworkedit.png') }}" alt="arrow" srcset="">
                                        </a>--}}
                                        <a class="ms-auto" href="{{route('settings.loan-application-edit','f=4')}}"><img class="" src="{{ URL::asset('front-assets/images/icons/koinworkedit.png') }}" alt="arrow" srcset=""></a>

                                    </div>
                                    <div class="card-body">
                                        <div class="displaydetail row mx-0 g-2 g-lg-4" >

                                            <div class="col-md-6">
                                                <label class="f-12" for="">{{ __('profile.type') }}</label>
                                                <div class="f-14 fw-bold">@if($type == 1) individual
                                                @elseif($type == 2) pt
                                                @elseif($type == 3)cv @endif</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="f-12" for="">{{ __('profile.business.title') }} {{ __('profile.Name') }}</label>
                                                <div class="f-14 fw-bold">{{$business_name?? '' }}</div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="f-12" for="">{{ __('profile.Description') }}</label>
                                                <div class="f-14 fw-bold">{{$description}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.website.title') }}</label>
                                                <div class="f-14 fw-bold">{{$website ?? '-'}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.Email') }}</label>
                                                <div class="f-14 fw-bold">{{$loanApplicantBusinessEmail}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.phone.title') }}</label>
                                                <div class="f-14 fw-bold">+{{$loanApplicantBusinessCode}} {{$loanApplicantBusinessPhone}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.owner_full_name.title') }}</label>
                                                <div class="f-14 fw-bold">{{$owner_first_name.' '.$owner_last_name}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.npwp_number.title') }}</label>
                                                <div class="f-14 fw-bold">{{ $business_npwp_number ?? '' }}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.npwp_file') }}</label>
                                                <div class="f-14 fw-bold"><a class="text-decoration-none downloadPo d-flex align-items-center" data-id="{{$authId}}" id="downloadPo{{$authId}}"  href="{{ Storage::url($npwp_image) }}"  download>Download <svg id="Layer_1" width="10px" class="ms-1" fill="#0d6efd" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"></path><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"></path></svg></a></div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.license_file') }}</label>
                                                <div class="f-14 fw-bold"><a class="text-decoration-none downloadPo d-flex align-items-center" data-id="{{$authId}}" id="downloadPo{{$authId}}"  href="{{ Storage::url($license_image) }}"  download>Download <svg id="Layer_1" width="10px" class="ms-1" fill="#0d6efd" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"></path><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"></path></svg></a></div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.average_sales.title') }}</label>
                                                <div class="f-14 fw-bold">{{$average_sales}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.establish_in.title') }}</label>
                                                <div class="f-14 fw-bold">{{$establish_in}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.number_of_employee.title') }}</label>
                                                <div class="f-14 fw-bold">@if($number_of_employee == 1) 1-50
                                                @elseif($number_of_employee == 2) 50-200
                                                @elseif($number_of_employee == 3)200-500
                                                @elseif($number_of_employee == 4)500-1000
                                                @elseif($number_of_employee == 5)1000+
                                                @endif</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.bank_statement_image.title') }}</label>
                                                <div class="f-14 fw-bold"><a class="text-decoration-none downloadPo d-flex align-items-center" data-id="{{$authId}}" id="downloadPo{{$authId}}"  href="{{ Storage::url($bank_statement_image) }}"  download>Download <svg id="Layer_1" width="10px" class="ms-1" fill="#0d6efd" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"></path><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"></path></svg></a></div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.siup_number.title') }}</label>
                                                <div class="f-14 fw-bold">{{$siup_number?? '' }}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.Category') }}</label>
                                                <div class="f-14 fw-bold">
                                                @if($category =="1")Pertanian, perikanan & peternakan
                                                    @elseif($category =="2")Kecantikan, farmasi
                                                    @elseif($category =="3")Bahan bangunan
                                                    @elseif($category =="4")Konstruksi & desain interior
                                                    @elseif($category =="5")Jasa pengiriman, kurir
                                                    @elseif($category =="6")Dropshipper
                                                    @elseif($category =="7")Elektronik, komputer
                                                    @elseif($category =="8")Fashion, aksesoris
                                                    @elseif($category =="9")Makanan, minuman
                                                    @elseif($category =="10")Furnitur
                                                    @elseif($category =="11")Oleh-Oleh
                                                    @elseif($category =="12")Salon, spa, pusat kebugaran
                                                    @elseif($category =="13")Kerajinan tangan
                                                    @elseif($category =="14")Hotel dan penginapan
                                                    @elseif($category =="15")Keperluan rumah tangga
                                                    @elseif($category =="16")Jasa laundry
                                                    @elseif($category =="17")alat medis, sport, musik
                                                    @elseif($category =="18")Jasa fotografi
                                                    @elseif($category =="19")Tanaman, hewan peliharaan
                                                    @elseif($category =="20")Percetakan, ATK
                                                    @elseif($category =="21")Restoran, cafe
                                                    @elseif($category =="22")Pariwisata dan travel
                                                    @elseif($category =="23")Mainan
                                                    @elseif($category =="24")Bengkel, sparepart
                                                    @elseif($category =="25")Lainnya
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.ownership.title') }}</label>
                                                <div class="f-14 fw-bold">{{$ownership_percentage??''}}</div>
                                            </div>
                                            {{--<div class="col-md-3">
                                                <label class="f-12" for="">{{ __('profile.relationship_with_borrower.title') }}</label>
                                                <div class="f-14 fw-bold">@if($relationship_with_borrower =="1")PARENT
                                                    @elseif($relationship_with_borrower =="2")SIBLING
                                                    @elseif($relationship_with_borrower =="3")SPOUSE
                                                    @elseif($relationship_with_borrower =="4")COLLEAGUE
                                                    @elseif($relationship_with_borrower =="5")PROFESSIONAL
                                                    @elseif($relationship_with_borrower =="6")OTHER
                                                    @endif
                                                </div>
                                            </div>--}}
                                        </div>
                                    </div>

                                </div>
                                <div class="card shadow mb-3 mb-lg-5">
                                    <div class="card-header p-3 d-flex align-items-center bg-white">
                                        <div class="fw-bold">{{ __('profile.business_address.title') }}</div>
                                        {{--<a class="ms-auto" href="#"><img class="" src="{{ URL::asset('front-assets/images/icons/koinworkedit.png') }}" alt="arrow" srcset="">
                                        </a>--}}
                                        <a class="ms-auto" href="{{route('settings.loan-application-edit','f=5')}}"><img class="" src="{{ URL::asset('front-assets/images/icons/koinworkedit.png') }}" alt="arrow" srcset=""></a>

                                    </div>
                                    <div class="card-body">
                                        <div class="displaydetail row mx-0 g-2 g-lg-4">
                                            <div class="col-md-12">
                                                <label class="f-12" for="">{{ __('profile.address') }}</label>
                                                <div class="f-14 fw-bold">{{ $business_address1 }}, {{ $business_address2 }}, {{ $business_sub_district }}, {{ $business_district }}, {{$business_city?? '' }} {{$business_provinces?? '' }} {{$business_postal_code?? '' }}</div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="d-flex justify-content-center my-3">
                                    <a name="" id="finalConfirm" name="finalConfirm"
                                        class="btn btn-primary bluecolornext text-white w-25 py-2 finalConfirm" href="javascript:void(0)"
                                        role="button"><img class="me-1"
                                        src="{{ URL::asset('front-assets/images/icons/pageend_submit.png') }}" alt="arrow" srcset="">
                                        {{ __('profile.confirm.title') }} </a>
                                </div>
                            </div>
                            </form>

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
    <script type="text/javascript">
    $(document).on('click', '.finalConfirm', function() {

        $("#finalConfirm").prop('disabled', true);

            $.ajax({
            url: "{{ route('settings.loan-application-confirm-ajax') }}",
            type: 'POST',
                    data:{ ConfirmSave: "ConfirmSave"} ,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(data) {
                        //console.log(data);
                        if (data.success) {
                            new PNotify({
                                text: data.message,
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 2000
                            });
                            setTimeout(function () {
                                let targetUrl = "{{ route('settings.credit-limit-status','') }}/" + data.data;
                                location.href = targetUrl;
                            },2000);
                            return;
                        }
                        new PNotify({
                            text: data.message,
                            type: 'error',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 2000
                        });
                        $("#finalConfirm").prop('disabled', false);
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

                    }
                });

        });
    function downloadimg(name){
        alert('test');
        event.preventDefault();
        var data = {
            name: name
        }
        $.ajax({
            url: "{{ route('settings.loan-application-download-ajax') }}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: data,
            type: 'POST',
            xhrFields: {
                responseType: 'blob'
            },
            success: function (response) {
                alert(response);
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = name;
                link.click();
            },
            error: function(data) {
                alert(data);
            }
        });
    }
    </script>
    @endsection
