@extends('admin/adminLayout')
@section('content')
    <div class="col-12 grid-margin  h-100">

        <div class=" row">
            <div class="col-md-12 mb-3 d-flex align-items-center">
                <h1 class="mb-0 h3">{{$applications->loan_application_number}}</h1>
                <a href="{{route('limits-index')}}" class=" backurl btn-close mx-3 ms-auto"></a>
            </div>
            <div class="col-12">
                <ul class="nav nav-tabs bg-white newversiontabs ps-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab"
                           href="#home-1" role="tab" aria-controls="home-1"
                           aria-selected="true">{{__('admin.limit')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-0 ms-5" data-id="" id="activity-tab" data-bs-toggle="tab" href="#activity" role="tab" aria-controls="activity" aria-selected="false">{{ __('admin.activities') }}</a>
                    </li>
                </ul>
                @php
                        if(!empty($applications->senctioned_amount)){
                            if($applications->remaining_amount!='')
                            $usedLimit = $applications->senctioned_amount - $applications->remaining_amount;
                            else
                            $usedLimit = $applications->senctioned_amount - 0;
                            $unusedLimit = $applications->senctioned_amount- $usedLimit;
                        }
                    @endphp
                <div class="tab-content pb-0 pt-3">
                    <div class="tab-pane fade active show" id="home-1" role="tabpanel"
                         aria-labelledby="home-tab">
                        <form class="" id="editLimitform" method="POST" enctype="multipart/form-data"
                              action="{{ route('limit-update') }}" data-parsley-validate="" novalidate="">
                            @csrf
                            <input type="hidden" name="id" value="{{$applicant->id}}">
                            <div class="row">
                                <div class=" col-md-12 mb-2">
                                    <section id="contact_detail">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="mb-0"><img height="20px"
                                                                      src="{{URL::asset('assets/icons/comment-alt-edit.png')}}"
                                                                      alt="Charges" class="pe-2">
                                                    <span>{{__('admin.credit_info')}}</span></h5>
                                            </div>
                                            <div class="card-body p-3 pb-1 my-2">
                                                <div class="creditpage row g-3">
                                                <div class="col-md-3 align-items-center">
                                                        <div class="t-credit">
                                                            <label for="floatingInput"
                                                                   class=" text-dark">{{__('admin.applied_limit')}}</label>
                                                            <div>Rp {{$applications->loan_limit == ''? '':number_format($applications->loan_limit,2)}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 align-items-center">
                                                        <div class="t-credit">
                                                            <label for="floatingInput"
                                                                   class=" text-dark">{{__('admin.approved_limit')}}</label>
                                                            <div>{{$applications->senctioned_amount == ''?  __('admin.no_approved_yet'):'Rp '.number_format($applications->senctioned_amount,2)}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 align-items-center">
                                                        <div class="u-credit ">
                                                            <label for="floatingInput"
                                                                   class="text-dark">{{__('admin.used_credit')}}</label>
                                                            <div>{{ !empty($usedLimit) ?'Rp '.number_format($usedLimit,2) : 0.00  }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 align-items-center">
                                                        <div class="p-credit ">
                                                            <label for="floatingInput"
                                                                   class="text-dark">{{__('admin.pending_credit')}}</label>
                                                            <div>{{ !empty($unusedLimit) ?'Rp '. number_format($unusedLimit,2) :0.00 }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 align-items-center">
                                                        <div class="p-credit ">
                                                            <label for="floatingInput"
                                                                   class="text-dark">{{__('admin.current_status')}}</label>
                                                            <div>{{$limitStatusValue}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                {{--  Personal Information  --}}
                                <div class=" col-md-12 mb-2">
                                    <section id="contact_detail">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="mb-0"><img height="20px"
                                                                      src="{{URL::asset('front-assets/images/icons/comment-alt-edit.png')}}"
                                                                      alt="Charges" class="pe-2">
                                                    <span>{{__('admin.personal_information')}}</span></h5>
                                            </div>
                                            <div class="card-body p-3 pb-1">
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="firstname"
                                                               class="form-label">{{__('admin.firstname')}}<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="first_name"
                                                               name="first_name" disabled
                                                               value="{{$applicant->first_name??''}}">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="lastname"
                                                               class="form-label">{{__('admin.lastname')}}<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="last_name"
                                                               name="last_name" disabled
                                                               value="{{$applicant->last_name??''}}">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="mobile" class="form-label">{{__('admin.mobile')}}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="phone_number"
                                                               name="phone_number" disabled
                                                               value="{{$applicant->phone_number??''}}">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="email" class="form-label">{{__('admin.email')}}<span
                                                                class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" id="email" name="email"
                                                               disabled value="{{$applicant->email??''}}">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="" class="form-label">{{__('profile.gender.title')}}
                                                            <span class="text-danger">*</span></label>
                                                        <select class="form-select" aria-label="Default select example"
                                                                disabled>
                                                            <option selected>Select</option>
                                                            <option
                                                                value="1" {{$applicant->gender == "1" ? "selected" : ""}}>
                                                                Male
                                                            </option>
                                                            <option
                                                                value="2" {{$applicant->gender == "2" ? "selected" : ""}}>
                                                                Female
                                                            </option>
                                                            <option
                                                                value="3" {{$applicant->gender == "3" ? "selected" : ""}}>
                                                                Other
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="" class="form-label">{{__('profile.ktpnik.title')}}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="ktp_nik"
                                                               id="ktp_nik" disabled
                                                               value="{{$applicant->ktp_nik??''}}">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for=""
                                                               class="form-label">{{__('profile.ktp_image.title')}}</label>

                                                        @if($applications->status == '0ff092ed-86c8-49f2-b8bb-7384abbba233')
                                                            <div class="d-flex">
                                                                <span class="image-count"><input type="file"
                                                                                                 name="ktpImage"
                                                                                                 class="form-control"
                                                                                                 id="image-ktpImage"
                                                                                                 accept=".jpg,.png,.pdf"
                                                                                                 onchange="show(this)"
                                                                                                 hidden/><label
                                                                        id="upload_btn"
                                                                        for="image-ktpImage">{{ __('admin.browse') }}</label></span>
                                                                <div id="file-ktpImage">
                                                                    @php
                                                                        $ktp_image_name = strchr($applicant->ktp_image,"ktpImage");
                                                                    @endphp
                                                                    <input type="hidden" class="form-control"
                                                                           id="oldktpImage" name="oldktpImage"
                                                                           value="{{ $applicant->ktp_image }}">
                                                                    <span class="ms-2"><a href="javascript:void(0);"
                                                                                          id="ktpImageFileDownload"
                                                                                          onclick="downloadimg('{{ $applicant->id }}', 'ktp_image', '{{ url($applicant->ktp_image) }}')"
                                                                                          title="{{ $ktp_image_name }}"
                                                                                          style="text-decoration: none;"> {{ $ktp_image_name }}</a></span>
                                                                    <span class="ms-2"><a class="ktpImageFile"
                                                                                          data-id="{{$applicant->id}}"
                                                                                          id="ktpImage"
                                                                                          href="{{ url($applicant->ktp_image)}}"
                                                                                          name="javascript:void(0);"
                                                                                          download
                                                                                          title="Download Tax Receipt"
                                                                                          style="text-decoration: none;"><i
                                                                                class="fa fa-cloud-download"></i></a></span>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="f-14 fw-bold">
                                                                <a class="text-decoration-none downloadPo d-flex align-items-center"
                                                                   data-id="{{$applicant->id}}" id="ktp_image"
                                                                   href="{{ url($applicant->ktp_image)}}"
                                                                   name="javascript:void(0);" download>Download
                                                                    <svg id="Layer_1" width="10px" class="ms-1"
                                                                         fill="#0d6efd" data-name="Layer 1"
                                                                         xmlns="http://www.w3.org/2000/svg"
                                                                         viewBox="0 0 383.26 408.81">
                                                                        <path
                                                                            d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z"
                                                                            transform="translate(-64.37 -51.59)"></path>
                                                                        <path
                                                                            d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z"
                                                                            transform="translate(-64.37 -51.59)"></path>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for=""
                                                               class="form-label">{{__('profile.ktp_with_selfie_image.title')}}</label>
                                                        @if($applications->status == '0ff092ed-86c8-49f2-b8bb-7384abbba233')
                                                            <div class="d-flex">
                                                                <span class="image-count"><input type="file"
                                                                                                 name="ktpSelfiImage"
                                                                                                 class="form-control"
                                                                                                 id="image-ktpSelfiImage"
                                                                                                 accept=".jpg,.png,.pdf"
                                                                                                 onchange="show(this)"
                                                                                                 hidden/><label
                                                                        id="upload_btn"
                                                                        for="image-ktpSelfiImage">{{ __('admin.browse') }}</label></span>
                                                                <div id="file-ktpSelfiImage">
                                                                    @php
                                                                        $ktp_with_selfie_image_name = strchr($applicant->ktp_with_selfie_image,"ktpSelfiImage");
                                                                    @endphp
                                                                    <input type="hidden" class="form-control"
                                                                           id="oldktpSelfiImage" name="oldktpSelfiImage"
                                                                           value="{{ $applicant->ktp_with_selfie_image }}">
                                                                    <span class="ms-2"><a href="javascript:void(0);"
                                                                                          id="ktpSelfiImageFileDownload"
                                                                                          onclick="downloadimg('{{ $applicant->id }}', 'ktp_with_selfie_image', '{{ url($applicant->ktp_with_selfie_image) }}')"
                                                                                          title="{{ $ktp_with_selfie_image_name }}"
                                                                                          style="text-decoration: none;"> {{ $ktp_with_selfie_image_name }}</a></span>
                                                                    <span class="ms-2"><a class="ktpSelfiImageFile"
                                                                                          data-id="{{$applicant->id}}"
                                                                                          id="ktpSelfiImage"
                                                                                          href="{{ url($applicant->ktp_with_selfie_image)}}"
                                                                                          name="javascript:void(0);"
                                                                                          download
                                                                                          title="Download Tax Receipt"
                                                                                          style="text-decoration: none;"><i
                                                                                class="fa fa-cloud-download"></i></a></span>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="f-14 fw-bold">
                                                                <a class="text-decoration-none downloadPo d-flex align-items-center"
                                                                   data-id="{{$applicant->id}}"
                                                                   id="ktp_with_selfie_image"
                                                                   href="{{ url($applicant->ktp_with_selfie_image)}}"
                                                                   name="javascript:void(0);" download>Download
                                                                    <svg id="Layer_1" width="10px" class="ms-1"
                                                                         fill="#0d6efd" data-name="Layer 1"
                                                                         xmlns="http://www.w3.org/2000/svg"
                                                                         viewBox="0 0 383.26 408.81">
                                                                        <path
                                                                            d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z"
                                                                            transform="translate(-64.37 -51.59)"></path>
                                                                        <path
                                                                            d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z"
                                                                            transform="translate(-64.37 -51.59)"></path>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for=""
                                                               class="form-label">{{__('profile.family_card_image.title')}}</label>
                                                        @if($applications->status == '0ff092ed-86c8-49f2-b8bb-7384abbba233')
                                                            <div class="d-flex">
                                                                <span class="image-count"><input type="file"
                                                                                                 name="familyCardImage"
                                                                                                 class="form-control"
                                                                                                 id="image-familyCardImage"
                                                                                                 accept=".jpg,.png,.pdf"
                                                                                                 onchange="show(this)"
                                                                                                 hidden/><label
                                                                        id="upload_btn"
                                                                        for="image-familyCardImage">{{ __('admin.browse') }}</label></span>
                                                                <div id="file-familyCardImage">
                                                                    @php
                                                                        $family_card_image_name = strchr($applicant->family_card_image,"familyCardImage");
                                                                    @endphp
                                                                    <input type="hidden" class="form-control"
                                                                           id="oldfamilyCardImage"
                                                                           name="oldfamilyCardImage"
                                                                           value="{{ $applicant->family_card_image }}">
                                                                    <span class="ms-2"><a href="javascript:void(0);"
                                                                                          id="familyCardImageFileDownload"
                                                                                          onclick="downloadimg('{{ $applicant->id }}', 'family_card_image', '{{ url($applicant->family_card_image) }}')"
                                                                                          title="{{ $family_card_image_name }}"
                                                                                          style="text-decoration: none;"> {{ $family_card_image_name }}</a></span>
                                                                    <span class="ms-2"><a class="familyCardImageFile"
                                                                                          data-id="{{$applicant->id}}"
                                                                                          id="familyCardImage"
                                                                                          href="{{ url($applicant->family_card_image)}}"
                                                                                          name="javascript:void(0);"
                                                                                          download
                                                                                          title="Download Tax Receipt"
                                                                                          style="text-decoration: none;"><i
                                                                                class="fa fa-cloud-download"></i></a></span>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="f-14 fw-bold">
                                                                <a class="text-decoration-none downloadPo d-flex align-items-center"
                                                                   data-id="{{$applicant->id}}" id="family_card_image"
                                                                   href="{{ url($applicant->family_card_image)}}"
                                                                   name="javascript:void(0);" download>Download
                                                                    <svg id="Layer_1" width="10px" class="ms-1"
                                                                         fill="#0d6efd" data-name="Layer 1"
                                                                         xmlns="http://www.w3.org/2000/svg"
                                                                         viewBox="0 0 383.26 408.81">
                                                                        <path
                                                                            d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z"
                                                                            transform="translate(-64.37 -51.59)"></path>
                                                                        <path
                                                                            d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z"
                                                                            transform="translate(-64.37 -51.59)"></path>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label for=""
                                                               class="form-label">{{__('profile.date_of_birth.title')}}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="date_of_birth"
                                                               id="date_of_birth" disabled
                                                               value="{{changeDateFormat($applicant->date_of_birth??'')}}">
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label for=""
                                                               class="form-label">{{__('profile.place_of_birth.title')}}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="place_of_birth"
                                                               id="place_of_birth" disabled
                                                               value="{{$applicant->place_of_birth??''}}">
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label for=""
                                                               class="form-label">{{__('profile.marital_status.title')}}
                                                            <span class="text-danger">*</span></label>
                                                        <select class="form-select" aria-label="Default select example"
                                                                disabled>
                                                            <option value="">Select marital status</option>
                                                            <option
                                                                value="1" {{$applicant->marital_status == "1" ? "selected" : ""}}>
                                                                KAWIN
                                                            </option>
                                                            <option
                                                                value="2" {{$applicant->marital_status == "2" ? "selected" : ""}}>
                                                                BELUM KAWIN
                                                            </option>
                                                            <option
                                                                value="3" {{$applicant->marital_status == "3" ? "selected" : ""}}>
                                                                CERAI MATI
                                                            </option>
                                                            <option
                                                                value="4" {{$applicant->marital_status == "4" ? "selected" : ""}}>
                                                                CERAI HIDUP
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label for=""
                                                               class="form-label">{{__('profile.religion.title')}}<span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-select" aria-label="Default select example"
                                                                disabled>
                                                            <option value="">Select religion</option>
                                                            <option
                                                                value="1" {{$applicant->religion == "1" ? "selected" : ""}}>
                                                                ISLAM
                                                            </option>
                                                            <option
                                                                value="2" {{$applicant->religion == "2" ? "selected" : ""}}>
                                                                KATHOLIK
                                                            </option>
                                                            <option
                                                                value="3" {{$applicant->religion == "3" ? "selected" : ""}}>
                                                                KRISTEN
                                                            </option>
                                                            <option
                                                                value="4" {{$applicant->religion == "4" ? "selected" : ""}}>
                                                                BUDHA
                                                            </option>
                                                            <option
                                                                value="5" {{$applicant->religion == "5" ? "selected" : ""}}>
                                                                HINDU
                                                            </option>
                                                            <option
                                                                value="6" {{$applicant->religion == "6" ? "selected" : ""}}>
                                                                KONGHUCHU
                                                            </option>
                                                            <option
                                                                value="7" {{$applicant->religion == "7" ? "selected" : ""}}>
                                                                OTHER
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for=""
                                                               class="form-label">{{__('profile.education.title')}}<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="education"
                                                               id="education" disabled
                                                               value="{{$applicant->education??''}}">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for=""
                                                               class="form-label">{{__('profile.occupation.title')}}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="occupation"
                                                               id="occupation" disabled
                                                               value="{{$applicant->occupation??''}}">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for=""
                                                               class="form-label">{{__('profile.my_position.title')}}
                                                            <span class="text-danger">*</span></label>
                                                        <select class="form-select" aria-label="Default select example"
                                                                disabled>
                                                            <option value="">Select my position</option>
                                                            <option
                                                                value="1" {{$applicant->my_position == "1" ? "selected" : ""}}>
                                                                DIRECTOR
                                                            </option>
                                                            <option
                                                                value="2" {{$applicant->my_position == "2" ? "selected" : ""}}>
                                                                VICE DIRECTOR
                                                            </option>
                                                            <option
                                                                value="3" {{$applicant->my_position == "3" ? "selected" : ""}}>
                                                                MANAGER
                                                            </option>
                                                            <option
                                                                value="4" {{$applicant->my_position == "4" ? "selected" : ""}}>
                                                                COMMISIONER
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for=""
                                                               class="form-label">{{__('profile.net_salary.title')}}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="net_salary"
                                                               disabled value="{{$applicant->net_salary??''}}">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for=""
                                                               class="form-label">{{__('profile.other_income.title')}}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="net_salary"
                                                               disabled value="{{$applicant->total_other_income??''}}">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for=""
                                                               class="form-label">{{__('profile.other_source_of_income.title')}}
                                                            <span class="text-danger">*</span></label>
                                                        <select class="form-select" aria-label="Default select example"
                                                                disabled>
                                                            <option value="">Select other source of income</option>
                                                            <option
                                                                value="1" {{$applicant->other_source_of_income == "1" ? "selected" : ""}}>
                                                                BUSINESS REVENUE
                                                            </option>
                                                            <option
                                                                value="2" {{$applicant->other_source_of_income == "2" ? "selected" : ""}}>
                                                                FUND REVENUE
                                                            </option>
                                                            <option
                                                                value="3" {{$applicant->other_source_of_income == "3" ? "selected" : ""}}>
                                                                INHERIRTANCE
                                                            </option>
                                                            <option
                                                                value="4" {{$applicant->other_source_of_income == "4" ? "selected" : ""}}>
                                                                SALARY
                                                            </option>
                                                            <option
                                                                value="5" {{$applicant->other_source_of_income == "5" ? "selected" : ""}}>
                                                                PARENT/GUARDIAN
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                {{--  Other Member Information --}}
                                <div class=" col-md-12 mb-2">
                                    <section id="product_detail">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="mb-0"><img height="20px"
                                                                      src="{{URL::asset('assets/icons/boxes.png')}}"
                                                                      alt="Product Details" class="pe-2">
                                                    <span>{{__('admin.other_member_information')}}</span>
                                                </h5>
                                            </div>
                                            <div class="card-body p-3 pb-1">
                                                <div class="row g-4 mb-4">
                                                    <div class="col-md-4">
                                                        <label for="" class="form-label">{{__('admin.firstname')}}<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="spouse_first_name"
                                                               id="spouse_first_name" disabled
                                                               value="{{$applicantSpouses->first_name??''}}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="" class="form-label">{{__('admin.lastname')}}<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="spouse_last_name"
                                                               id="spouse_last_name" disabled
                                                               value="{{$applicantSpouses->last_name??''}}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="" class="form-label">{{__('admin.mobile')}}<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                               name="spouse_phone_number" id="spouse_phone_number"
                                                               disabled value="{{$applicantSpouses->phone_number??''}}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.relationship_with_borrower.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <select class="form-select" aria-label="Default select example"
                                                                disabled>
                                                            <option
                                                                value="">{{ __('profile.relationship_with_borrower.select') }}</option>
                                                            <option
                                                                value="1" {{$applicantSpouses->relationship_with_borrower == "1" ? "selected" : ""}}>
                                                                PARENT
                                                            </option>
                                                            <option
                                                                value="2" {{$applicantSpouses->relationship_with_borrower == "2" ? "selected" : ""}}>
                                                                SIBLING
                                                            </option>
                                                            <option
                                                                value="3" {{$applicantSpouses->relationship_with_borrower == "3" ? "selected" : ""}}>
                                                                SPOUSE
                                                            </option>
                                                            <option
                                                                value="4" {{$applicantSpouses->relationship_with_borrower == "4" ? "selected" : ""}}>
                                                                COLLEAGUE
                                                            </option>
                                                            <option
                                                                value="5" {{$applicantSpouses->relationship_with_borrower == "5" ? "selected" : ""}} >
                                                                PROFESSIONAL
                                                            </option>
                                                            <option
                                                                value="6" {{$applicantSpouses->relationship_with_borrower == "6" ? "selected" : ""}}>
                                                                OTHER
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="" class="form-label">{{__('profile.ktpnik.title')}}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{$applicantSpouses->ktp_nik}}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{__('profile.ktp_image.title')}}</label>
                                                        @if($applications->status == '0ff092ed-86c8-49f2-b8bb-7384abbba233')
                                                            <div class="d-flex">
                                                                <span class="image-count"><input type="file"
                                                                                                 name="otherKtpImage"
                                                                                                 class="form-control"
                                                                                                 id="image-otherKtpImage"
                                                                                                 accept=".jpg,.png,.pdf"
                                                                                                 onchange="show(this)"
                                                                                                 hidden/><label
                                                                        id="upload_btn"
                                                                        for="image-otherKtpImage">{{ __('admin.browse') }}</label></span>
                                                                <div id="file-otherKtpImage">
                                                                    @php
                                                                        $spouse_ktp_image_name = strchr($applicantSpouses->ktp_image,"otherKtpImage");
                                                                    @endphp
                                                                    <input type="hidden" class="form-control"
                                                                           id="oldotherKtpImage" name="oldotherKtpImage"
                                                                           value="{{ $applicantSpouses->ktp_image }}">
                                                                    <span class="ms-2"><a href="javascript:void(0);"
                                                                                          id="otherKtpImageFileDownload"
                                                                                          onclick="downloadimg('{{ $applicantSpouses->id }}', 'ktp_image', '{{ url($applicantSpouses->ktp_image) }}')"
                                                                                          title="{{ $spouse_ktp_image_name }}"
                                                                                          style="text-decoration: none;"> {{ $spouse_ktp_image_name }}</a></span>
                                                                    <span class="ms-2"><a class="otherKtpImageFile"
                                                                                          data-id="{{$applicantSpouses->id}}"
                                                                                          id="otherKtpImage"
                                                                                          href="{{ url($applicantSpouses->ktp_image)}}"
                                                                                          name="javascript:void(0);"
                                                                                          download
                                                                                          title="Download Tax Receipt"
                                                                                          style="text-decoration: none;"><i
                                                                                class="fa fa-cloud-download"></i></a></span>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="f-14 fw-bold">
                                                                <a class="text-decoration-none downloadPo d-flex align-items-center"
                                                                   data-id="{{$applicantSpouses->id}}"
                                                                   id="spouse_ktp_image"
                                                                   href="{{ url($applicantSpouses->ktp_image)}}"
                                                                   name="javascript:void(0);" download>Download
                                                                    <svg id="Layer_1" width="10px" class="ms-1"
                                                                         fill="#0d6efd" data-name="Layer 1"
                                                                         xmlns="http://www.w3.org/2000/svg"
                                                                         viewBox="0 0 383.26 408.81">
                                                                        <path
                                                                            d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z"
                                                                            transform="translate(-64.37 -51.59)"></path>
                                                                        <path
                                                                            d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z"
                                                                            transform="translate(-64.37 -51.59)"></path>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                {{--  Personal Address --}}
                                <div class="col-md-12 mb-2">
                                    <section id="delivery_detail">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="mb-0"><img height="20px"
                                                                      src="{{URL::asset('assets/icons/truck.png')}}"
                                                                      alt="Charges" class="pe-2">
                                                    <span>{{__('admin.personal_address')}}</span></h5>
                                            </div>
                                            <div class="card-body p-3 pb-1">
                                                <div class="row g-4 mb-4 pt-2">
                                                    <div class="col-md-6">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.address_line_1.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{$applicantAddress->address_line1}}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.address_line_2.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{$applicantAddress->address_line2}}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.sub_district.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{$applicantAddress->sub_district}}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.district.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{$applicantAddress->district}}">
                                                    </div>
                                                    @if($applicantAddress->provinces_id != -1)
                                                        <div class="col-md-4">
                                                            <label for=""
                                                                   class="form-label">{{ __('profile.provinces.title') }}
                                                                <span class="text-danger">*</span></label>
                                                            <select class="form-select"
                                                                    aria-label="Default select example" disabled>
                                                                <option disabled="" selected="">Select</option>
                                                                @foreach($applicantProvinces as $applicantProvince)
                                                                    <option
                                                                        value="{{ $applicantProvince->id }}" {{ $applicantProvince->id == $applicantAddress->provinces_id ? 'selected' : '' }} >{{ $applicantProvince->name??'' }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @else
                                                        <div class="col-md-2">
                                                            <label for=""
                                                                   class="form-label">{{ __('profile.provinces.title') }}
                                                                <span class="text-danger">*</span></label>
                                                            <select class="form-select"
                                                                    aria-label="Default select example" disabled>
                                                                {{--                                                                <option value="" >{{ __('admin.select_province') }}</option>--}}
                                                                <option value="-1">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="state"
                                                                   class="form-label">{{ __('admin.other_provinces') }}
                                                                <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="stateOther"
                                                                   id="stateOther" disabled
                                                                   value="{{$applicantAddress->other_provinces}}">
                                                        </div>
                                                    @endif

                                                    @if($applicantAddress->city_id != -1)
                                                        <div class="col-md-4">
                                                            <label for=""
                                                                   class="form-label">{{ __('profile.city.title') }}
                                                                <span class="text-danger">*</span></label>
                                                            <select class="form-select"
                                                                    aria-label="Default select example" disabled>
                                                                <option disabled="" selected="">Select</option>
                                                                @foreach($applicantCity as $applicantCities)
                                                                    <option
                                                                        value="{{ $applicantCities->id }}" {{ $applicantCities->id == $applicantAddress->city_id ? 'selected' : '' }} >{{ $applicantCities->name??'' }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @else
                                                        <div class="col-md-2">
                                                            <label for=""
                                                                   class="form-label">{{ __('profile.city.title') }}
                                                                <span class="text-danger">*</span></label>
                                                            <select class="form-select"
                                                                    aria-label="Default select example" disabled>
                                                                {{--                                                                <option value="">{{ __('rfqs.select_city') }}</option>--}}
                                                                <option value="-1">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for=""
                                                                   class="form-label">{{ __('rfqs.other_city') }}</label>
                                                            <input type="text" class="form-control" name="cityOther"
                                                                   id="cityOther" disabled
                                                                   value="{{$applicantAddress->other_city}}">
                                                        </div>
                                                    @endif
                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.country.title') }}<span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-select" aria-label="Default select example"
                                                                disabled>
                                                            <option disabled="" selected="">Select</option>
                                                            @foreach($applicantCountry as $applicantCountries)
                                                                <option
                                                                    value="{{ $applicantCountries->id }}" {{ $applicantCountries->id == $applicantAddress->country_id ? 'selected' : '' }} >{{ $applicantCountries->name??'' }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.postal_code.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{$applicantAddress->postal_code}}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.has_lived_here.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <select class="form-select" aria-label="Default select example"
                                                                disabled>
                                                            <option
                                                                value="">{{ __('profile.has_lived_here.select') }}</option>
                                                            <option
                                                                value="1" {{$applicantAddress->has_live_here == "1" ? "selected" : ""}}>
                                                                Ya
                                                            </option>
                                                            <option
                                                                value="2" {{$applicantAddress->has_live_here == "2" ? "selected" : ""}}>
                                                                Tidak
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.duration_of_stay.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{$applicantAddress->duration_of_stay}}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.home_ownership_status.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <select class="form-select" aria-label="Default select example"
                                                                disabled>
                                                            <option
                                                                value="">{{ __('profile.has_lived_here.select') }}</option>
                                                            <option
                                                                value="1" {{$applicantAddress->home_ownership_status == "1" ? "selected" : ""}}>
                                                                FAMILY/KELUARGA
                                                            </option>
                                                            <option
                                                                value="2" {{$applicantAddress->home_ownership_status == "2" ? "selected" : ""}}>
                                                                PARENT/ORANG TUA
                                                            </option>
                                                            <option
                                                                value="3" {{$applicantAddress->home_ownership_status == "3" ? "selected" : ""}}>
                                                                RENTAL/KOS
                                                            </option>
                                                            <option
                                                                value="4" {{$applicantAddress->home_ownership_status == "4" ? "selected" : ""}}>
                                                                OWNED/MILIK SENDIRI
                                                            </option>
                                                            <option
                                                                value="5" {{$applicantAddress->home_ownership_status == "5" ? "selected" : ""}}>
                                                                OFFICE RESIDENCE/RUMAH DINAS
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row d-none">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="payment_terms"
                                                               class="form-label d-block">{{__('admin.payment_terms')}}</label>
                                                        <span
                                                            class="badge badge-success pull-left ">{{__('admin.advance')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                {{-- Business--}}
                                <div class="col-md-12 mb-2">
                                    <section id="payment_details">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="mb-0"><img height="20px"
                                                                      src="{{URL::asset('assets/icons/credit-card.png')}}"
                                                                      alt="Payment Details"
                                                                      class="pe-2"><span>{{__('admin.business')}}</span>
                                                </h5>
                                            </div>
                                            <div class="card-body p-3 pb-1">
                                                <div class="row g-4 mb-4 pt-2">
                                                    <div class="col-md-4">
                                                        <label for="" class="form-label">{{__('admin.business_name')}}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{$applicantBusiness->name}}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="" class="form-label">{{__('admin.business_type')}}
                                                            <span class="text-danger">*</span></label>
                                                        <select class="form-select" aria-label="Default select example"
                                                                disabled>
                                                            <option value="">{{ __('profile.type_select') }}</option>
                                                            <option
                                                                value="1" {{$applicantBusiness->type == "1" ? "selected" : ""}}>
                                                                individual
                                                            </option>
                                                            <option
                                                                value="2" {{$applicantBusiness->type == "2" ? "selected" : ""}}>
                                                                pt
                                                            </option>
                                                            <option
                                                                value="3" {{$applicantBusiness->type == "3" ? "selected" : ""}}>
                                                                cv
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.website.title') }}</label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{$applicantBusiness->website}}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="" class="form-label">{{ __('profile.phone.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{$applicantBusiness->phone_number}}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="" class="form-label">{{ __('profile.Email') }}<span
                                                                class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" name="" id="" disabled
                                                               value="{{$applicantBusiness->email}}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.owner_full_name.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{$applicantBusiness->owner_first_name.' '. $applicantBusiness->owner_last_name}}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.average_sales.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{$applicantBusiness->average_sales}}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.siup_number.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{$applicantBusiness->siup_number}}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.establish_in.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{changeDateFormat($applicantBusiness->establish_in)}}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.number_of_employee.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <select class="form-select" aria-label="Default select example"
                                                                disabled>
                                                            <option
                                                                value="">{{ __('profile.number_of_employee.select') }}</option>
                                                            <option
                                                                value="1" {{$applicantBusiness->number_of_employee == "1" ? "selected" : ""}}>
                                                                1-50
                                                            </option>
                                                            <option
                                                                value="2" {{$applicantBusiness->number_of_employee == "2" ? "selected" : ""}}>
                                                                50-200
                                                            </option>
                                                            <option
                                                                value="3" {{$applicantBusiness->number_of_employee == "3" ? "selected" : ""}}>
                                                                200-500
                                                            </option>
                                                            <option
                                                                value="4" {{$applicantBusiness->number_of_employee == "4" ? "selected" : ""}}>
                                                                500-1000
                                                            </option>
                                                            <option
                                                                value="5" {{$applicantBusiness->number_of_employee == "5" ? "selected" : ""}}>
                                                                1000+
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.select_category') }}
                                                            <span class="text-danger">*</span></label>
                                                        <select class="form-select" aria-label="Default select example"
                                                                disabled>
                                                            <option
                                                                value="">{{ __('profile.select_category') }}</option>
                                                            <option
                                                                value="1" {{$applicantBusiness->category == "1" ? "selected" : ""}}>
                                                                Pertanian, perikanan & peternakan
                                                            </option>
                                                            <option
                                                                value="2" {{$applicantBusiness->category == "2" ? "selected" : ""}}>
                                                                Kecantikan, farmasi
                                                            </option>
                                                            <option
                                                                value="3" {{$applicantBusiness->category == "3" ? "selected" : ""}}>
                                                                Bahan bangunan
                                                            </option>
                                                            <option
                                                                value="4" {{$applicantBusiness->category == "4" ? "selected" : ""}}>
                                                                Konstruksi & desain interior
                                                            </option>
                                                            <option
                                                                value="5" {{$applicantBusiness->category == "5" ? "selected" : ""}}>
                                                                Jasa pengiriman, kurir
                                                            </option>
                                                            <option
                                                                value="6" {{$applicantBusiness->category == "6" ? "selected" : ""}}>
                                                                Dropshipper
                                                            </option>
                                                            <option
                                                                value="7" {{$applicantBusiness->category == "7" ? "selected" : ""}}>
                                                                Elektronik, komputer
                                                            </option>
                                                            <option
                                                                value="8" {{$applicantBusiness->category == "8" ? "selected" : ""}}>
                                                                Fashion, aksesoris
                                                            </option>
                                                            <option
                                                                value="9" {{$applicantBusiness->category == "9" ? "selected" : ""}}>
                                                                Makanan, minuman
                                                            </option>
                                                            <option
                                                                value="10" {{$applicantBusiness->category == "10" ? "selected" : ""}}>
                                                                Furnitur
                                                            </option>
                                                            <option
                                                                value="11" {{$applicantBusiness->category == "11" ? "selected" : ""}}>
                                                                Oleh-Oleh
                                                            </option>
                                                            <option
                                                                value="12" {{$applicantBusiness->category == "12" ? "selected" : ""}}>
                                                                Salon, spa, pusat kebugaran
                                                            </option>
                                                            <option
                                                                value="13" {{$applicantBusiness->category == "13" ? "selected" : ""}}>
                                                                Kerajinan tangan
                                                            </option>
                                                            <option
                                                                value="14" {{$applicantBusiness->category == "14" ? "selected" : ""}}>
                                                                Hotel dan penginapan
                                                            </option>
                                                            <option
                                                                value="15" {{$applicantBusiness->category == "15" ? "selected" : ""}}>
                                                                Keperluan rumah tangga
                                                            </option>
                                                            <option
                                                                value="16" {{$applicantBusiness->category == "16" ? "selected" : ""}}>
                                                                Jasa laundry
                                                            </option>
                                                            <option
                                                                value="17" {{$applicantBusiness->category == "17" ? "selected" : ""}}>
                                                                alat medis, sport, musik
                                                            </option>
                                                            <option
                                                                value="18" {{$applicantBusiness->category == "18" ? "selected" : ""}}>
                                                                Jasa fotografi
                                                            </option>
                                                            <option
                                                                value="19" {{$applicantBusiness->category == "19" ? "selected" : ""}}>
                                                                Tanaman, hewan peliharaan
                                                            </option>
                                                            <option
                                                                value="20" {{$applicantBusiness->category == "20" ? "selected" : ""}}>
                                                                Percetakan, ATK
                                                            </option>
                                                            <option
                                                                value="21" {{$applicantBusiness->category == "21" ? "selected" : ""}}>
                                                                Restoran, cafe
                                                            </option>
                                                            <option
                                                                value="22" {{$applicantBusiness->category == "22" ? "selected" : ""}}>
                                                                Pariwisata dan travel
                                                            </option>
                                                            <option
                                                                value="23" {{$applicantBusiness->category == "23" ? "selected" : ""}}>
                                                                Mainan
                                                            </option>
                                                            <option
                                                                value="24" {{$applicantBusiness->category == "24" ? "selected" : ""}}>
                                                                Bengkel, sparepart
                                                            </option>
                                                            <option
                                                                value="25" {{$applicantBusiness->category == "25" ? "selected" : ""}}>
                                                                Lainnya
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.ownership.title') }}
                                                            %<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{$applicantBusiness->ownership_percentage}}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.relationship_with_borrower.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <select class="form-select" aria-label="Default select example"
                                                                disabled>
                                                            <option
                                                                value="">{{ __('profile.relationship_with_borrower.select') }}</option>
                                                            <option
                                                                value="1" {{$applicantBusiness->relationship_with_borrower == "1" ? "selected" : ""}}>
                                                                PARENT
                                                            </option>
                                                            <option
                                                                value="2" {{$applicantBusiness->relationship_with_borrower == "2" ? "selected" : ""}}>
                                                                SIBLING
                                                            </option>
                                                            <option
                                                                value="3" {{$applicantBusiness->relationship_with_borrower == "3" ? "selected" : ""}}>
                                                                SPOUSE
                                                            </option>
                                                            <option
                                                                value="4" {{$applicantBusiness->relationship_with_borrower == "4" ? "selected" : ""}}>
                                                                COLLEAGUE
                                                            </option>
                                                            <option
                                                                value="5" {{$applicantBusiness->relationship_with_borrower == "5" ? "selected" : ""}} >
                                                                PROFESSIONAL
                                                            </option>
                                                            <option
                                                                value="6" {{$applicantBusiness->relationship_with_borrower == "6" ? "selected" : ""}}>
                                                                OTHER
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.npwp_image.title') }}</label>
                                                        @if($applications->status == '0ff092ed-86c8-49f2-b8bb-7384abbba233')
                                                            <div class="d-flex">
                                                                <span class="image-count"><input type="file"
                                                                                                 name="loanApplicantBusinessNpwpImage"
                                                                                                 class="form-control"
                                                                                                 id="image-loanApplicantBusinessNpwpImage"
                                                                                                 accept=".jpg,.png,.pdf"
                                                                                                 onchange="show(this)"
                                                                                                 hidden/><label
                                                                        id="upload_btn"
                                                                        for="image-loanApplicantBusinessNpwpImage">{{ __('admin.browse') }}</label></span>
                                                                <div id="file-loanApplicantBusinessNpwpImage">
                                                                    @php
                                                                        $npwp_image_name = strchr($applicantBusiness->npwp_image,"loanApplicantBusinessNpwpImage");
                                                                    @endphp
                                                                    <input type="hidden" class="form-control"
                                                                           id="oldloanApplicantBusinessNpwpImage"
                                                                           name="oldloanApplicantBusinessNpwpImage"
                                                                           value="{{ $applicantBusiness->npwp_image }}">
                                                                    <span class="ms-2"><a href="javascript:void(0);"
                                                                                          id="loanApplicantBusinessNpwpImageFileDownload"
                                                                                          onclick="downloadimg('{{ $applicantBusiness->id }}', 'npwp_image', '{{ url($applicantBusiness->npwp_image) }}')"
                                                                                          title="{{ $npwp_image_name }}"
                                                                                          style="text-decoration: none;"> {{ $npwp_image_name }}</a></span>
                                                                    <span class="ms-2"><a
                                                                            class="loanApplicantBusinessNpwpImageFile"
                                                                            data-id="{{$applicantBusiness->id}}"
                                                                            id="loanApplicantBusinessNpwpImage"
                                                                            href="{{ url($applicantBusiness->npwp_image)}}"
                                                                            name="javascript:void(0);" download
                                                                            title="Download Tax Receipt"
                                                                            style="text-decoration: none;"><i
                                                                                class="fa fa-cloud-download"></i></a></span>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="f-14 fw-bold">
                                                                <a class="text-decoration-none downloadPo d-flex align-items-center"
                                                                   data-id="{{$applicantBusiness->id}}" id="npwp_image"
                                                                   href="{{ url($applicantBusiness->npwp_image)}}"
                                                                   name="javascript:void(0);" download>Download
                                                                    <svg id="Layer_1" width="10px" class="ms-1"
                                                                         fill="#0d6efd" data-name="Layer 1"
                                                                         xmlns="http://www.w3.org/2000/svg"
                                                                         viewBox="0 0 383.26 408.81">
                                                                        <path
                                                                            d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z"
                                                                            transform="translate(-64.37 -51.59)"></path>
                                                                        <path
                                                                            d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z"
                                                                            transform="translate(-64.37 -51.59)"></path>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.license_image.title') }}</label>

                                                        @if($applications->status == '0ff092ed-86c8-49f2-b8bb-7384abbba233')
                                                            <div class="d-flex">
                                                                <span class="image-count"><input type="file"
                                                                                                 name="loanApplicantBusinessLicenceImage"
                                                                                                 class="form-control"
                                                                                                 id="image-loanApplicantBusinessLicenceImage"
                                                                                                 accept=".jpg,.png,.pdf"
                                                                                                 onchange="show(this)"
                                                                                                 hidden/><label
                                                                        id="upload_btn"
                                                                        for="image-loanApplicantBusinessLicenceImage">{{ __('admin.browse') }}</label></span>
                                                                <div id="file-loanApplicantBusinessLicenceImage">
                                                                    @php
                                                                        $license_image_name = strchr($applicantBusiness->license_image,"loanApplicantBusinessLicenceImage");
                                                                    @endphp
                                                                    <input type="hidden" class="form-control"
                                                                           id="oldloanApplicantBusinessLicenceImage"
                                                                           name="oldloanApplicantBusinessLicenceImage"
                                                                           value="{{ $applicantBusiness->license_image }}">
                                                                    <span class="ms-2"><a href="javascript:void(0);"
                                                                                          id="loanApplicantBusinessLicenceImageFileDownload"
                                                                                          onclick="downloadimg('{{ $applicantBusiness->id }}', 'license_image', '{{ url($applicantBusiness->license_image) }}')"
                                                                                          title="{{ $license_image_name }}"
                                                                                          style="text-decoration: none;"> {{ $license_image_name }}</a></span>
                                                                    <span class="ms-2"><a
                                                                            class="loanApplicantBusinessLicenceImageFile"
                                                                            data-id="{{$applicantBusiness->id}}"
                                                                            id="loanApplicantBusinessLicenceImage"
                                                                            href="{{ url($applicantBusiness->license_image)}}"
                                                                            name="javascript:void(0);" download
                                                                            title="Download Tax Receipt"
                                                                            style="text-decoration: none;"><i
                                                                                class="fa fa-cloud-download"></i></a></span>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="f-14 fw-bold">
                                                                <a class="text-decoration-none downloadPo d-flex align-items-center"
                                                                   data-id="{{$applicantBusiness->id}}"
                                                                   id="license_image"
                                                                   href="{{ url($applicantBusiness->license_image)}}"
                                                                   name="javascript:void(0);" download>Download
                                                                    <svg id="Layer_1" width="10px" class="ms-1"
                                                                         fill="#0d6efd" data-name="Layer 1"
                                                                         xmlns="http://www.w3.org/2000/svg"
                                                                         viewBox="0 0 383.26 408.81">
                                                                        <path
                                                                            d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z"
                                                                            transform="translate(-64.37 -51.59)"></path>
                                                                        <path
                                                                            d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z"
                                                                            transform="translate(-64.37 -51.59)"></path>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.bank_statement_image.title') }}</label>
                                                        @if($applications->status == '0ff092ed-86c8-49f2-b8bb-7384abbba233')
                                                            <div class="d-flex">
                                                                <span class="image-count"><input type="file"
                                                                                                 name="loanApplicantBankStatement"
                                                                                                 class="form-control"
                                                                                                 id="image-loanApplicantBankStatement"
                                                                                                 accept=".jpg,.png,.pdf"
                                                                                                 onchange="show(this)"
                                                                                                 hidden/><label
                                                                        id="upload_btn"
                                                                        for="image-loanApplicantBankStatement">{{ __('admin.browse') }}</label></span>
                                                                <div id="file-loanApplicantBankStatement">
                                                                    @php
                                                                        $bank_statement_image_name = strchr($applicantBusiness->bank_statement_image,"loanApplicantBankStatement");
                                                                    @endphp
                                                                    <input type="hidden" class="form-control"
                                                                           id="oldloanApplicantBankStatement"
                                                                           name="oldloanApplicantBankStatement"
                                                                           value="{{ $applicantBusiness->bank_statement_image }}">
                                                                    <span class="ms-2"><a href="javascript:void(0);"
                                                                                          id="loanApplicantBankStatementFileDownload"
                                                                                          onclick="downloadimg('{{ $applicantBusiness->id }}', 'bank_statement_image', '{{ url($applicantBusiness->bank_statement_image) }}')"
                                                                                          title="{{ $bank_statement_image_name }}"
                                                                                          style="text-decoration: none;"> {{ $bank_statement_image_name }}</a></span>
                                                                    <span class="ms-2"><a
                                                                            class="loanApplicantBankStatementFile"
                                                                            data-id="{{$applicantBusiness->id}}"
                                                                            id="loanApplicantBankStatement"
                                                                            href="{{ url($applicantBusiness->bank_statement_image)}}"
                                                                            name="javascript:void(0);" download
                                                                            title="Download Tax Receipt"
                                                                            style="text-decoration: none;"><i
                                                                                class="fa fa-cloud-download"></i></a></span>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="f-14 fw-bold">
                                                                <a class="text-decoration-none downloadPo d-flex align-items-center"
                                                                   data-id="{{$applicantBusiness->id}}"
                                                                   id="bank_statement_image"
                                                                   href="{{ url($applicantBusiness->bank_statement_image)}}"
                                                                   name="javascript:void(0);" download>Download
                                                                    <svg id="Layer_1" width="10px" class="ms-1"
                                                                         fill="#0d6efd" data-name="Layer 1"
                                                                         xmlns="http://www.w3.org/2000/svg"
                                                                         viewBox="0 0 383.26 408.81">
                                                                        <path
                                                                            d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z"
                                                                            transform="translate(-64.37 -51.59)"></path>
                                                                        <path
                                                                            d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z"
                                                                            transform="translate(-64.37 -51.59)"></path>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <!-- Same As Other Description Taken -->
                                                    <div class="col-md-12 ">
                                                        <label class="form-label mb-0 product_description_for"
                                                               for="">{{ __('profile.description') }}<span
                                                                class="text-danger">*</span></label>
                                                        <textarea class="form-control newtextarea" placeholder=""
                                                                  name="product_description"
                                                                  id="description">{{$applicantBusiness->description??''}}</textarea>
                                                    </div>
                                                    <!-- Same As Other Description Taken -->
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                {{--Business Address--}}
                                <div class="col-md-12 mb-2">
                                    <section id="payment_details">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="mb-0"><img height="20px"
                                                                      src="{{URL::asset('assets/icons/credit-card.png')}}"
                                                                      alt="Payment Details" class="pe-2">
                                                    <span>{{__('admin.business_address')}}</span>
                                                </h5>
                                            </div>
                                            <div class="card-body p-3 pb-1">
                                                <div class="row g-4 mb-4 pt-2">
                                                    <div class="col-md-6">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.address_line_1.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{$applicantBusinessAddress->address1??''}}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.address_line_2.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{$applicantBusinessAddress->address2??''}}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.sub_district.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{$applicantBusinessAddress->sub_district??''}}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.district.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{$applicantBusinessAddress->district??''}}">
                                                    </div>
                                                    @if($applicantBusinessAddress->provinces_id != -1)
                                                        <div class="col-md-4">
                                                            <label for=""
                                                                   class="form-label">{{ __('profile.provinces.select') }}
                                                                <span class="text-danger">*</span></label>
                                                            <select class="form-select"
                                                                    aria-label="Default select example" disabled>
                                                                <option disabled="" selected="">Select</option>
                                                                @foreach($businessProvinces as $businessProvince)
                                                                    <option
                                                                        value="{{ $businessProvince->id }}" {{ $businessProvince->id == $applicantBusinessAddress->provinces_id ? 'selected' : '' }} >{{ $businessProvince->name??'' }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @else
                                                        <div class="col-md-2">
                                                            <label for=""
                                                                   class="form-label">{{ __('profile.provinces.title') }}
                                                                <span class="text-danger">*</span></label>
                                                            <select class="form-select"
                                                                    aria-label="Default select example" disabled>
                                                                {{--                                                                <option value="" >{{ __('admin.select_province') }}</option>--}}
                                                                <option value="-1">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="state"
                                                                   class="form-label">{{ __('admin.other_provinces') }}
                                                                <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                   name="stateBusinessOther" id="stateBusinessOther"
                                                                   disabled
                                                                   value="{{$applicantBusinessAddress->other_provinces}}">
                                                        </div>
                                                    @endif

                                                    @if($applicantBusinessAddress->city_id != -1)
                                                        <div class="col-md-4">
                                                            <label for=""
                                                                   class="form-label">{{ __('profile.city.title') }}
                                                                <span class="text-danger">*</span></label>
                                                            <select class="form-select"
                                                                    aria-label="Default select example" disabled>
                                                                <option disabled="" selected="">Select</option>
                                                                @foreach($businessCity as $businessCities)
                                                                    <option
                                                                        value="{{ $businessCities->id }}" {{ $businessCities->id == $applicantBusinessAddress->city_id ? 'selected' : '' }} >{{ $businessCities->name??'' }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @else
                                                        <div class="col-md-2">
                                                            <label for=""
                                                                   class="form-label">{{ __('profile.city.title') }}
                                                                <span class="text-danger">*</span></label>
                                                            <select class="form-select"
                                                                    aria-label="Default select example" disabled>
                                                                {{--                                                                <option value="">{{ __('rfqs.select_city') }}</option>--}}
                                                                <option value="-1">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for=""
                                                                   class="form-label">{{ __('rfqs.other_city') }}</label>
                                                            <input type="text" class="form-control"
                                                                   name="cityBusinessOther" id="cityBusinessOther"
                                                                   disabled
                                                                   value="{{$applicantBusinessAddress->other_city}}">
                                                        </div>
                                                    @endif

                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.country.title') }}<span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-select" aria-label="Default select example"
                                                                disabled>
                                                            <option disabled="" selected="">Select</option>
                                                            @foreach($businessCountry as $businessCountries)
                                                                <option
                                                                    value="{{ $businessCountries->id }}" {{ $businessCountries->id == $applicantBusinessAddress->country_id ? 'selected' : '' }} >{{ $businessCountries->name??'' }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for=""
                                                               class="form-label">{{ __('profile.postal_code.title') }}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="" id="" disabled
                                                               value="{{$applicantBusinessAddress->postal_code??''}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                {{--Document Policy--}}
                                @if($applications->loan_limit > FIFTY_MILLION)
                                <div class="col-md-12 mb-2">
                                    <section id="payment_details">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="mb-0"><img height="20px"
                                                                      src="{{URL::asset('assets/icons/credit-card.png')}}"
                                                                      alt="Payment Details" class="pe-2">
                                                    <span>{{__('admin.document_policy')}} </span>
                                                </h5>
                                            </div>
                                            <div class="card-body p-3 pb-1">
                                                <div class="row g-4 mb-4 pt-2">
                                                    <div class="col-md-4">
                                                        <label for="" class="form-label">Policy</label>
                                                        @if($applications->status == 'badfa20d-3562-45eb-bf4a-79a1c59fd3e9' && $applications->verify_otp == 1)
                                                            <div class="d-flex">
                                                                <span class=""><input type="file" name="uploadContract"
                                                                                      class="form-control"
                                                                                      id="image-uploadContract"
                                                                                      accept=".jpg,.png,.pdf"
                                                                                      onchange="show(this)"
                                                                                      hidden/><label id="upload_btn"
                                                                                                     for="image-uploadContract">{{ __('admin.browse') }}</label></span>
                                                                <div id="file-uploadContract">
                                                                    @php
                                                                        $uploaded_contracts_name = strchr($applications->uploaded_contracts,"contract/");

                                                                        $extension_contract = substr($applications->uploaded_contracts, -4);
                                                                        $contract_filename = substr(Str::substr($applications->uploaded_contracts, stripos($applications->uploaded_contracts, 'contract_') + 9), 0, -4);
                                                                        if(strlen($contract_filename) > 10){
                                                                            $uploaded_contracts_d_name = substr($contract_filename,0,10).'...'.$extension_contract;
                                                                        } else {
                                                                            $uploaded_contracts_d_name = $contract_filename.$extension_contract;
                                                                        }
                                                                    @endphp
                                                                    <input type="hidden" class="form-control"
                                                                           id="olduploadContract"
                                                                           name="olduploadContract"
                                                                           value="{{ $applications->uploaded_contracts }}">
                                                                    @if(isset($applications->uploaded_contracts))
                                                                    <span class="ms-2"><a href="javascript:void(0);"
                                                                                          id="uploadContractFileDownload"
                                                                                          onclick="downloadimg('{{ $applications->id }}', 'uploaded_contracts', '{{ url($applications->uploaded_contracts) }}')"
                                                                                          title="{{ $uploaded_contracts_name }}"
                                                                                          style="text-decoration: none;"> {{ $uploaded_contracts_d_name }}</a></span>
                                                                    <span class="ms-2"><a class="uploadContractFile"
                                                                                          data-id="{{$applications->id}}"
                                                                                          id="uploadContract"
                                                                                          href=" {{ Storage::url($applications->uploaded_contracts) }} "
                                                                                          name="javascript:void(0);"
                                                                                          download
                                                                                          title="Download Tax Receipt"
                                                                                          style="text-decoration: none;"><i
                                                                                class="fa fa-cloud-download"></i></a></span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="f-14 fw-bold">
                                                                <a class="text-decoration-none downloadPo d-flex align-items-center"
                                                                   data-id="{{$applications->id}}"
                                                                   id="uploaded_contracts"
                                                                   href="{{ Storage::url($applications->uploaded_contracts)}}"
                                                                   name="javascript:void(0);" download>Download
                                                                    <svg id="Layer_1" width="10px" class="ms-1"
                                                                         fill="#0d6efd" data-name="Layer 1"
                                                                         xmlns="http://www.w3.org/2000/svg"
                                                                         viewBox="0 0 383.26 408.81">
                                                                        <path
                                                                            d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z"
                                                                            transform="translate(-64.37 -51.59)"></path>
                                                                        <path
                                                                            d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z"
                                                                            transform="translate(-64.37 -51.59)"></path>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                @endif
                                <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                                    @if(($applications->status == '0ff092ed-86c8-49f2-b8bb-7384abbba233') || ($applications->status == 'badfa20d-3562-45eb-bf4a-79a1c59fd3e9' && $applications->verify_otp == 1))
                                    <button type="submit" class="btn btn-primary">{{ __('admin.update')}}</button>
                                    @endif
                                    <a href="{{route('limits-index')}}" style="float: right;"
                                       class=" ms-3 btn btn-cancel">{{__('admin.cancel')}}</a>
                                </div>
                            </div>
                        </form>
                    </div>


                    <!--begin: Activities-->
                    <div class="tab-pane fade " id="activity" role="tabpanel" aria-labelledby="activity-tab">
                        @livewire('admin.limit.limit-apply-activity',['limit' => Crypt::encrypt($applications->id)])
                    </div>
                    <!--end: Activities-->

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        let editedFiles = 0;
        let status = '{{ $applications->status }}';
        $(document).ready(function () {
            tinymce.activeEditor.mode.set("readonly");
            setTimeout(function () {
                $('iframe#description_ifr').css('background', '#e9ecef');
            }, 1000);

            $("#editLimitform").on('submit', function (event) {
                event.preventDefault();
                var formData = new FormData($("#editLimitform")[0]);

                if(status == '0ff092ed-86c8-49f2-b8bb-7384abbba233'){
                    if(editedFiles != 7){
                        swal({
                            title: "Warning",
                            icon: "/assets/images/warn.png",
                            text: "Please Upload All Documents",
                            buttons: ['{{ __('admin.cancel') }}'],
                        });
                        return false;
                    }
                }

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (r) {
                        if (r.success == true) {
                            resetToastPosition();
                            $.toast({
                                heading: "{{__('admin.success')}}",
                                text: "Update Successfully",
                                showHideTransition: "slide",
                                icon: "success",
                                loaderBg: "#f96868",
                                position: "top-right",
                            });
                            setTimeout(function () {
                                window.top.location = $(".backurl").attr('href')
                            }, 3000);
                        } else {
                            resetToastPosition();
                            $.toast({
                                heading: "{{__('admin.error')}}",
                                text: "{{__('admin.something_error_message')}}",
                                showHideTransition: "slide",
                                icon: "error",
                                loaderBg: "#f96868",
                                position: "top-right",
                            });
                            setTimeout(function () {
                                window.top.location = $(".backurl").attr('href')
                            }, 3000);
                        }
                    },
                    error: function (xhr) {
                        alert('{{__('admin.error_while_selecting_list')}}');
                    }
                });

            });
        });

        String.prototype.beforeLastIndex = function (delimiter) {
            return this.split(delimiter).slice(0, -1).join(delimiter) || this + ""
        }



        function show(input) {
            var file = input.files[0];
            editedFiles++;
            var size = Math.round((file.size / 1024))
            if (size > 5200) {
                swal({
                    icon: 'error',
                    title: '',
                    text: '{{__('admin.file_size_under_5mb')}}',
                })
            } else {
                let fileName = file.name;
                let allowed_extensions = new Array("jpg","jpeg");
                let file_extension = fileName.split('.').pop();
                let file_name_without_extension = fileName.replace(/\.[^/.]+$/, '');
                let image_path = '{{URL::asset("assets/icons/times-circle copy.png")}}';
                var applicant_id = '{{ $applicant->id }}';
                for (let i = 0; i < allowed_extensions.length; i++) {
                    if (allowed_extensions[i] == file_extension) {
                        valid = true;
                        let download_function = "'" + input.name + "', " + "'" + fileName + "'";
                        if (file_name_without_extension.length >= 10) {
                            fileName = file_name_without_extension.substring(0, 10) + '....' + file_extension;
                        }
                        $('#file-' + input.name).html('');
                        $('#file-' + input.name).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.name + 'Download " style="text-decoration: none">' + fileName + '</a></span><span class="ms-2"><a class="' + input.name + ' downloadbtn hidden" href="javascript:void(0);" title="{{ __('profile.download_file') }}" onclick="downloadimg(' + download_function + ')" style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a></span>');
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
    </script>
@stop
