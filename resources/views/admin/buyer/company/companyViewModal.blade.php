            <div class="modal-header py-3">
                <h5 class="modal-title" id="exampleModalLabel"><img height="24px" class="pe-2"
                                                                    src="{{URL::asset('assets/icons/order_detail_title.png')}}"
                                                                    alt="Order Details">{{ $buyer->company->name }}</h5>
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
                                                      alt="Company" class="pe-2"> <span>{{ __('admin.company_details') }}</span>
                                </h5>
                            </div>
                            <div class="card-body p-3 pb-1">
                                <div class="row rfqform_view">
                                    <div class="col-md-3 mb-3">
                                        <label for="name" class="form-label">{{ __('admin.company_name') }}:</label>
                                        <div>{{ $buyer->company->name }}</div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="email" class="form-label">{{ __('admin.company_email') }}:</label>
                                        <div class="text-dark"> {{ $buyer->company->company_email ?  $buyer->company->company_email : '-'}}</div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="email" class="form-label">{{__('admin.alternate_email')}}:</label>
                                        <div class="text-dark">{{$buyer->company->alternative_email ? $buyer->company->alternative_email : '-'}}</div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="email" class="form-label">{{__('admin.company_website')}}:</label>
                                        <div class="text-dark">{{$buyer->company->web_site ? $buyer->company->web_site : '-'}}</div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="mobile" class="form-label">{{__('profile.company_phone')}}:</label>
                                        <div class="text-dark">{{!empty(trim($buyer->company->company_phone)) ? $buyer->company->c_phone_code ." ".$buyer->company->company_phone : '-'}}</div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="mobile" class="form-label">{{__('profile.alternative_phone')}}:</label>
                                        <div class="text-dark">{{$buyer->company->alternative_phone ? $buyer->company->a_phone_code." ".$buyer->company->alternative_phone : '-'}}</div>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="email" class="form-label">{{__('profile.registration_nib')}}:</label>
                                        <div class="text-dark">{{$buyer->company->registrantion_NIB ? $buyer->company->registrantion_NIB : '-'}}</div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="mobile" class="form-label">{{ __('profile.nib_file') }}:</label>
                                        <div>
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
                                                    //dd($nib_file_name);
                                                @endphp

                                            <a class="text-decoration-none downloadPo btn btn-primary p-1" id="nibDownload"
                                               href="javascript:void(0);" id="downloadimg{{ $buyer->id }}" onclick="SnippetCompanyViewDetails.downloadimg('{{ $buyer->id }}', 'nib_file', '{{ $nib_file_name }}')"
                                               title="{{ $nib_file_name }}"
                                               style="text-decoration: none;font-size: 12px;">
                                                <svg id="Layer_1" width="12px" fill="#fff"
                                                     data-name="Layer 1"
                                                     xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 383.26 408.81">
                                                    <path
                                                        d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z"
                                                        transform="translate(-64.37 -51.59)"></path>
                                                    <path
                                                        d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z"
                                                        transform="translate(-64.37 -51.59)"></path>
                                                </svg> NIB File</a>
                                            @else
                                                <div class="text-dark">-</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="mobile" class="form-label">{{ __('profile.npwp') }}:</label>
                                        <div class="text-dark">{{$buyer->company->npwp ? $buyer->company->npwp : '-'}}</div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="email" class="form-label">{{ __('admin.npwp_file') }}:</label>
                                        <div>

                                            @if($buyer->company->npwp_file)
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
                                                <a class="text-decoration-none btn btn-primary p-1"
                                                   href="javascript:void(0);" id="inpwpFileDownload" onclick="SnippetCompanyViewDetails.downloadimg('{{ $buyer->id }}', 'npwp_file', '{{ $npwpFileTitle }}')"
                                                   title="{{ $npwpFileTitle }}"
                                                   style="text-decoration: none;font-size: 12px;">
                                                    <svg id="Layer_1" width="12px" fill="#fff"
                                                         data-name="Layer 1"
                                                         xmlns="http://www.w3.org/2000/svg"
                                                         viewBox="0 0 383.26 408.81">
                                                        <path
                                                            d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z"
                                                            transform="translate(-64.37 -51.59)"></path>
                                                        <path
                                                            d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z"
                                                            transform="translate(-64.37 -51.59)"></path>
                                                    </svg> NPWP File</a>
                                            @else
                                                <div class="text-dark">-</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-none mb-3">
                                        <label for="email" class="form-label">{{ __('profile.address') }}:</label>
                                        <div class="text-dark">{{$buyer->company ? $buyer->company->address : '-'}}</div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="email" class="form-label">{{ __('admin.commercial_terms') }}:</label>
                                        <div>

                                            @if($buyer->company->termsconditions_file)
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
                                                <a class="text-decoration-none btn btn-primary p-1"
                                                   href="javascript:void(0);" id="termsconditionsFileDownload" onclick="SnippetCompanyViewDetails.downloadimg('{{ $buyer->id }}', 'termsconditions_file', '{{ $termsconditionsFileTitle }}')"
                                                   title="{{ $termsconditionsFileTitle }}"
                                                   style="text-decoration: none;font-size: 12px;">
                                                    <svg id="Layer_1" width="12px" fill="#fff"
                                                         data-name="Layer 1"
                                                         xmlns="http://www.w3.org/2000/svg"
                                                         viewBox="0 0 383.26 408.81">
                                                        <path
                                                            d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z"
                                                            transform="translate(-64.37 -51.59)"></path>
                                                        <path
                                                            d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z"
                                                            transform="translate(-64.37 -51.59)"></path>
                                                    </svg> {{ __('admin.commercial_tc') }}</a>
                                            @else
                                                <div class="text-dark">-</div>
                                            @endif
                                        </div>
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
                                                      alt="Contact" class="pe-2"> <span>{{ __('admin.contact_details') }}</span></h5>
                            </div>
                            <div class="card-body rfqform_view p-3 pb-1">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="contactPersonName" class="form-label">{{ __('profile.first_name') }}:</label>
                                        <div class="text-dark">{{$buyer->firstname ? $buyer->firstname : '-'}}</div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="contactPersonName" class="form-label">{{ __('profile.last_name') }}:</label>
                                        <div class="text-dark">{{$buyer->lastname ? $buyer->lastname : '-'}}</div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="contactPersonEmail"
                                               class="form-label">{{ __('profile.Email') }}:</label>
                                        <div class="text-dark">{{ $buyer->email ? $buyer->email : '-' }}</div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="contactPersonMobile" class="form-label">{{ __('profile.mobile_number') }}:</label>
                                        <div class="text-dark">{{ !empty(trim($buyer->mobile)) ? $buyer->phone_code." ".$buyer->mobile : '-'}}</div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="contactPersonEmail"
                                               class="form-label">{{ __('profile.designation') }}:</label>
                                        <div class="text-dark">{{(!empty($buyer->companyUserDetails) && !empty($buyer->companyUserDetails[0]->designationDetails)) ? $buyer->companyUserDetails[0]->designationDetails->name : '-'}}</div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="contactPersonEmail"
                                               class="form-label">{{ __('profile.department') }}:</label>
                                        <div class="text-dark">{{(!empty($buyer->companyUserDetails) && !empty($buyer->companyUserDetails[0]->departmentDetails)) ? $buyer->companyUserDetails[0]->departmentDetails->name : '-'}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer p-3">
                <button type="button" class="btn  btn-cancel" data-bs-dismiss="modal">{{ __('admin.close') }}</button>
            </div>

            <script>
                var SnippetCompanyViewDetails = function (){
                    return {
                        init: function () {
                        },
                        /**  Download file **/
                        downloadimg: function (id, fieldName, name) {
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
                        }
                    }
                }(1);
                jQuery(document).ready(function () {
                    SnippetCompanyViewDetails.init();
                });

            </script>
