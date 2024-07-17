<div class="modal-header py-3">
    <h5 class="modal-title" id="exampleModalLabel"><img height="24px" class="pe-2"
                                                        src="{{URL::asset('assets/icons/order_detail_title.png')}}"
                                                        alt="Order Details">{{$limitApplicantDetail->loan_application_number}}</h5>
    <button type="button" class="btn-close ms-0" data-bs-dismiss="modal"
            aria-label="Close"><img src="{{URL::asset('assets/icons/times.png')}}"
                                    alt="Close"></button>
</div>
<div class="modal-body p-3 pb-1">
    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0"><img height="20px"
                                          src="{{URL::asset('assets/icons/icon_company.png')}}"
                                          alt="Company" class="pe-2"> <span>{{ __('admin.buyer_choice') }}</span>
                    </h5>
                </div>
                <div class="card-body p-3 pb-1">
                    <div class="row rfqform_view">
                    <div class="col-md-6">
                            <label class="f-12" for="">{{__('admin.applied_amount')}}</label>
                            <div class="f-14 fw-bold text-dark">Rp {{$limitApplicantDetail->loan_limit != '' ? number_format($limitApplicantDetail->loan_limit,2):''}}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="f-12" for="">{{__('admin.approved_amount')}}</label>
                            <div class="f-14 fw-bold text-dark">{{$limitApplicantDetail->senctioned_amount != '' ? 'Rp '.number_format($limitApplicantDetail->senctioned_amount,2): __('admin.no_approved_yet')}}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="f-12" for="">{{__('admin.koinworks_limit_id')}}</label>
                            <div class="f-14 fw-bold text-dark">{{$limitApplicantDetail->provider_application_id}}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="f-12" for="">{{__('admin.user_id')}}</label>
                            <div class="f-14 fw-bold text-dark">{{$limitApplicantDetail->provider_user_id}}</div>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 mb-2">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0"><img height="20px"
                                          src="{{URL::asset('assets/icons/people-carry-1.png')}}"
                                          alt="Contact" class="pe-2"> <span>{{__('admin.buyer_information')}}</span>
                    </h5>
                </div>
                <div class="card-body rfqform_view p-3 pb-2">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('admin.name') }}</label>
                            <div class="f-14 fw-bold text-dark">{{ $limitApplicantDetail->applicant->first_name.' '.$limitApplicantDetail->applicant->last_name }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('home.email') }}</label>
                            <div class="f-14 fw-bold text-dark">{{$limitApplicantDetail->applicant->email}}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.phone.title') }}</label>
                            <div class="f-14 fw-bold text-dark">+{{$limitApplicantDetail->applicant->phone_code.' '.$limitApplicantDetail->applicant->phone_number}}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.gender.title') }}</label>
                            <div class="f-14 fw-bold text-dark">{{$limitApplicantDetail->applicant->gender == '1' ? 'Male': 'Female'}}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.marital_status.title') }}</label>
                            <div class="f-14 fw-bold text-dark">{{$limitApplicantDetail->applicant->marital_status_name}}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.ktpnik.title') }}</label>
                            <div class="f-14 fw-bold text-dark">{{$limitApplicantDetail->applicant->ktp_nik}}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.ktp_image.title') }}</label>
                            <div class="f-14 fw-bold">
                                @if($limitApplicantDetail->applicant->ktp_image)
                                <a class="text-decoration-none downloadPo d-flex align-items-center"
                                    data-id="{{$limitApplicantDetail->id}}" id="buyerktpImage" href="{{ url($limitApplicantDetail->applicant->ktp_image) }}" name="javascript:void(0);" download>Download
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
                                @else
                                    <div class="text-dark">-</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.ktp_with_selfie_image.title') }}</label>
                            <div class="f-14 fw-bold"><a
                                    class="text-decoration-none downloadPo d-flex align-items-center"
                                    data-id="{{$limitApplicantDetail->id}}" id="buyerKtpwithSelfieImage" href="{{ url($limitApplicantDetail->applicant->ktp_with_selfie_image) }}" name="javascript:void(0);" download>Download
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
                                </a></div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.family_card_image.title') }}</label>
                            <div class="f-14 fw-bold"><a
                                    class="text-decoration-none downloadPo d-flex align-items-center"
                                    data-id="{{$limitApplicantDetail->id}}" id="buyerfamilyCardImage" href="{{ url($limitApplicantDetail->applicant->family_card_image) }}" name="javascript:void(0);" download>Download
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
                                </a></div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.net_salary.title') }}</label>
                            <div class="f-14 fw-bold text-dark">Rp {{number_format($limitApplicantDetail->applicant->net_salary,2)}}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.other_income.title') }}</label>
                            <div class="f-14 fw-bold text-dark">Rp {{$limitApplicantDetail->applicant->total_other_income}}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.other_source_of_income.title') }}</label>
                            <div class="f-14 fw-bold text-dark">{{$limitApplicantDetail->applicant->other_source_income_name}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0"><img height="20px"
                                          src="{{URL::asset('assets/icons/people-carry-1.png')}}"
                                          alt="Contact" class="pe-2"> <span>{{__('admin.other_member_information')}}</span>
                    </h5>
                </div>
                <div class="card-body rfqform_view p-3 pb-2">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('admin.name') }}</label>
                            <div class="f-14 fw-bold text-dark">{{$limitApplicantDetail->loanApplicantSpouse->first_name.' '.$limitApplicantDetail->loanApplicantSpouse->last_name}}</div>
                        </div>
                      {{--  <div class="col-md-3">
                            <label class="f-12" for="">{{ __('home.email') }}</label>
                            <div class="f-14 fw-bold text-dark">{{$limitApplicantDetail->other_source_of_income}}</div>
                        </div>--}}
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.phone.title') }}</label>
                            <div class="f-14 fw-bold text-dark">+{{$limitApplicantDetail->loanApplicantSpouse->phone_code.' '.$limitApplicantDetail->loanApplicantSpouse->phone_number}}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.relationship_with_borrower.title') }}</label>
                            <div class="f-14 fw-bold text-dark">{{$limitApplicantDetail->loanApplicantSpouse->relationship_with_borrower_name}}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.ktpnik.title') }}</label>
                            <div class="f-14 fw-bold text-dark">{{$limitApplicantDetail->loanApplicantSpouse->ktp_nik}}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.ktp_image.title') }}</label>
                            <div class="f-14 fw-bold"><a
                                    class="text-decoration-none downloadPo d-flex align-items-center"
                                    data-id="{{$limitApplicantDetail->id}}" id="otherKtpImage"  href="{{ url($limitApplicantDetail->loanApplicantSpouse->ktp_image) }}"  name="javascript:void(0);" download>Download
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
                                </a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0"><img height="20px"
                                          src="{{URL::asset('assets/icons/people-carry-1.png')}}"
                                          alt="Contact" class="pe-2"> <span>{{ __('admin.business') }}</span>
                    </h5>
                </div>
                <div class="card-body rfqform_view p-3 pb-2">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="f-12" for="">{{__('profile.type')}}</label>
                            <div class="f-14 fw-bold text-dark">{{$limitApplicantDetail->loanApplicantBusiness->type_name}}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{__('admin.name')}}</label>
                            <div class="f-14 fw-bold text-dark">{{$limitApplicantDetail->loanApplicantBusiness->name}}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.npwp_image.title') }}</label>
                            <div class="f-14 fw-bold"><a
                                    class="text-decoration-none downloadPo d-flex align-items-center"
                                    data-id="{{$limitApplicantDetail->id}}" id="loanNpwpImage" href="{{ url($limitApplicantDetail->loanApplicantBusiness->npwp_image)}}"  name="javascript:void(0);" download>Download
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
                                </a></div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.license_image.title') }}</label>
                            <div class="f-14 fw-bold"><a
                                    class="text-decoration-none downloadPo d-flex align-items-center"
                                    data-id="{{$limitApplicantDetail->id}}" id="busniessLicence" href="{{ url($limitApplicantDetail->loanApplicantBusiness->license_image)}}" name="javascript:void(0);" download>Download
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
                                </a></div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.bank_statement_image.title') }}</label>
                            <div class="f-14 fw-bold"><a
                                    class="text-decoration-none downloadPo d-flex align-items-center"
                                    data-id="{{$limitApplicantDetail->id}}" id="bankstatementImage" href="{{ url($limitApplicantDetail->loanApplicantBusiness->bank_statement_image) }}" name="javascript:void(0);" download>Download
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
                                </a></div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.siup_number.title') }}</label>
                            <div class="f-14 fw-bold text-dark">{{$limitApplicantDetail->loanApplicantBusiness->siup_number}}</div>
                        </div>

                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.ownership.title') }} %*</label>
                            <div class="f-14 fw-bold text-dark">{{$limitApplicantDetail->loanApplicantBusiness->ownership_percentage}}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.Email') }} </label>
                            <div class="f-14 fw-bold text-dark">{{$limitApplicantDetail->loanApplicantBusiness->email}}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="f-12" for="">{{ __('profile.phone.title') }} </label>
                            <div class="f-14 fw-bold text-dark">+{{$limitApplicantDetail->loanApplicantBusiness->phone_code.' '.$limitApplicantDetail->loanApplicantBusiness->phone_number}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer p-3">
    <button type="button" class="btn  btn-cancel" data-bs-dismiss="modal">Close</button>
</div>

