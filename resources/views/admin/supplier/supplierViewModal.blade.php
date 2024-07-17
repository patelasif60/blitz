<div class="modal-header py-3">
    <h5 class="modal-title" id="exampleModalLabel"><img height="24px" class="pe-2" src="{{URL::asset('assets/icons/order_detail_title.png')}}" alt="Order Details"> {{ $supplier->name }}</h5>
    <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"><img src="{{URL::asset('assets/icons/times.png')}}" alt="Close"></button>
</div>
<div class="modal-body p-3 pb-1">
    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/icon_company.png')}}" alt="Company" class="pe-2"> <span>{{ __('admin.company_details') }}</span></h5>
                </div>
                <div class="card-body p-3 pb-1">
                    <div class="row rfqform_view">
                        <div class="col-md-4 mb-3">
                            <label for="name" class="form-label">{{ __('admin.company_name') }}:</label>
                            <div>{{ $supplier->name }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label">{{ __('admin.company_email') }}:</label>
                            <div class="text-dark">{{ $supplier->email }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="mobile" class="form-label">{{ __('admin.company_mobile') }}:</label>
                            <div class="text-dark">{{ $supplier->c_phone_code.' '.$supplier->mobile }}</div>
                        </div>
                        @if(!empty($supplier->website))
                        <div class="col-md-4 mb-3">
                            <label for="website" class="form-label">{{ __('admin.') }}:</label>
                            <div class="text-dark">{{ $supplier->website }}</div>
                        </div>
                        @endif
                        @if(!empty($supplier->interested_in))
                        <div class="col-md-8 mb-3">
                            <label for="interested_in" class="form-label">{{ __('admin.dealing_with_categories') }}:</label>
                            <div class="text-dark">{{ $supplier->interested_in }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/people-carry-1.png')}}" alt="Contact" class="pe-2"> <span>{{ __('admin.contact_details') }}</span></h5>
                </div>
                <div class="card-body rfqform_view p-3 pb-1">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="contactPersonName" class="form-label">{{ __('admin.contact_person_name') }}:</label>
                            <div class="text-dark">{{ $supplier->contact_person_name }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="contactPersonEmail" class="form-label">{{ __('admin.contact_person_email') }}:</label>
                            <div class="text-dark">{{ $supplier->contact_person_email }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="contactPersonMobile" class="form-label">{{ __('admin.contact_person_phone') }}:</label>
                            <div class="text-dark">{{ $supplier->contact_person_phone }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(!empty($supplier->catalog) || !empty($supplier->pricing) || !empty($supplier->product) || !empty($supplier->commercialCondition))
        <div class="col-md-12 mb-2">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/attachement.png')}}" alt="Charges" class="pe-2"> <span>{{ __('admin.attachment') }}</span></h5>
                </div>
                <div class="card-body rfqform_view p-3 pb-1">
                    <div class="row">
                        @if($supplier->catalog)
                        <div class="col-md-3 mb-3 cursor-pointer">
                            <label for="catalog" class="form-label">{{ __('admin.catalog') }}:</label>
                            <div>
                                <a href="javascript:void(0);" title="Download Catalog" onclick="downloadimg('{{ $supplier->id }}', 'catalog', '{{ Str::substr($supplier->catalog, stripos($supplier->catalog, 'catalog_') + 8) }}')" class="text-decoration-none"><img src="{{URL::asset('assets/icons/file-order-letter.png')}}" alt="Order Details" class="pe-2">Download <svg id="Layer_1" width="10px" fill="#0d6efd" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg></a>
                            </div>
                        </div>
                        @endif
                        @if($supplier->pricing)
                        <div class="col-md-3 mb-3">
                            <label for="pricing" class="form-label">{{ __('admin.pricing') }}:</label>
                            <div>
                                <a href="javascript:void(0);" title="Download Pricing" onclick="downloadimg('{{ $supplier->id }}', 'pricing', '{{ Str::substr($supplier->pricing, stripos($supplier->pricing, 'pricing_') + 8) }}')" class="text-decoration-none"><img src="{{URL::asset('assets/icons/file-order-letter.png')}}" alt="Order Details" class="pe-2">Download <svg id="Layer_1" width="10px" fill="#0d6efd" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg></a>
                            </div>
                        </div>
                        @endif
                        @if($supplier->product)
                        <div class="col-md-3 mb-3">
                            <label for="product" class="form-label">{{ __('admin.product') }}:</label>
                            <div>
                                <a href="javascript:void(0);" title="Download Product" onclick="downloadimg('{{ $supplier->id }}', 'product', '{{ Str::substr($supplier->product, stripos($supplier->product, "product_") + 8) }}')" class="text-decoration-none"><img src="{{URL::asset('assets/icons/file-order-letter.png')}}" alt="Order Details" class="pe-2">Download <svg id="Layer_1" width="10px" fill="#0d6efd" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg></a>
                            </div>
                        </div>
                        @endif
                        @if($supplier->commercialCondition)
                        <div class="col-md-3 mb-3">
                            <label for="commercialCondition" class="form-label">{{ __('admin.commercial_conditions') }}:</label>
                            <div>
                                <a href="javascript:void(0);" title="Download Commercial Conditions" onclick="downloadimg('{{ $supplier->id }}', 'commercialCondition', '{{ Str::substr($supplier->commercialCondition, stripos($supplier->commercialCondition, "commercialCondition_") + 20) }}')" class="text-decoration-none"><img src="{{URL::asset('assets/icons/file-order-letter.png')}}" alt="Order Details" class="pe-2">Download <svg id="Layer_1" width="10px" fill="#0d6efd" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg></a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(!empty($xen_platform) || $banks != '')
        <div class="col-md-12 mb-2">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/credit-card.png')}}" alt="Charges" class="pe-2"> <span>{{ __('admin.account_details') }}</span></h5>
                </div>
                <div class="card-body rfqform_view p-3 pb-1">
                    @if($banks != '')
                    @php
                        $bankDetail = $banks->bankDetail()->first(['name','code','logo']);
                    @endphp
                    <div class="row">
                        <div class="col-md-3 mb-3 cursor-pointer">
                            <label for="contactPersonMobile" class="form-label">{{ __('admin.bank_name') }}:</label>
                            <div class="text-dark">{{ $bankDetail->name }}</div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="contactPersonMobile" class="form-label">{{ __('admin.bank_code') }}:</label>
                            <div class="text-dark">{{ $bankDetail->code }}</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="contactPersonMobile" class="form-label">{{ __('admin.bank_account_name') }}:</label>
                            <div class="text-dark">{{ $banks->bank_account_name }}</div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="contactPersonMobile" class="form-label">{{ __('admin.bank_account_number') }}:</label>
                            <div class="text-dark">{{ $banks->bank_account_number }}</div>
                        </div>
                        <div class="col-md-1 mb-3 px-md-0">
                            <label for="contactPersonMobile" class="form-label d-none d-md-block">&nbsp;</label>
                            <div class="">
                                <small class="badge badge-pill bg-success"><i class="fa fa-check" style="font-size: 7px;"></i> {{ __('admin.primary') }}</small>
                            </div>
                        </div>

                    </div>
                    @endif
{{---- Remove loader in supplier @ekta  24/02/22 ----}}

{{--                    @if(!empty($xen_platform))--}}
{{--                    <div class="row">--}}
{{--                        @if(!empty($xen_platform->xen_platform_id))--}}
{{--                        <div class="col-md-5 mb-3 cursor-pointer">--}}
{{--                            <label for="contactPersonMobile" class="form-label">Xen Platform ID:</label>--}}
{{--                            <div class="text-dark">{{ $xen_platform->xen_platform_id }}</div>--}}
{{--                        </div>--}}
{{--                        @endif--}}
{{--                        @if(isset($xen_platform->balance) && !empty($xen_platform->balance))--}}
{{--                        <div class="col-md-3 mb-3">--}}
{{--                            <label for="contactPersonMobile" class="form-label">Xen Platform Balance:</label>--}}
{{--                            <div class="text-dark">Rp {{ $xen_platform->balance }}</div>--}}
{{--                        </div>--}}
{{--                        @endif--}}
{{--                        @if(!empty($xen_platform->email))--}}
{{--                        <div class="col-md-4 mb-3">--}}
{{--                            <label for="contactPersonMobile" class="form-label">Email:</label>--}}
{{--                            <div class="text-dark">{{ $xen_platform->email }}</div>--}}
{{--                        </div>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                    @endif--}}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
<div class="modal-footer p-3">
    <button type="button" class="btn  btn-cancel" data-bs-dismiss="modal">{{ __('admin.close') }}</button>
</div>
