<div class="modal-content">
    <div class="modal-header py-3">
        <h5 class="modal-title d-flex align-items-center" id="">
            <img class="pe-2" height="24px" src="{{ URL::asset('front-assets/images/icons/icon_pickup1.png') }}" alt="View Address"> {{ $addressData->address_name }}
        </h5>

        <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close">
            <img src="{{ URL('front-assets/images/icons/times.png') }}" alt="Close">
        </button>
    </div>
    <div class="modal-body p-3">
        <div id="viewQuoteDetailBlock">
            <div class="row align-items-stretch">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h5><img height="20px" src="{{ URL('front-assets/images/icons/icon_newaddress.png') }}" alt="Address Detail" class="pe-2">
                            {{ __('order.address_details')}} </h5>
                        </div>
                        <div class="card-body p-3 pb-1">
                            <div class="row rfqform_view bg-white">
                                <div class="col-md-6 pb-3">
                                    <label>{{ __('rfqs.address_line1')}}:</label>
                                    <div class="text-dark">{{ $addressData->address_line_1 }}</div>
                                </div>
                                <div class="col-md-6 pb-3">
                                    <label>{{ __('rfqs.address_line2')}}:</label>
                                    <div class="text-dark">{{ $addressData->address_line_2 }}</div>
                                </div>
                                <div class="col-md-3 pb-3">
                                    <label>{{ __('rfqs.sub_district')}}:</label>
                                    <div class="text-dark">{{ $addressData->sub_district }}</div>
                                </div>
                                <div class="col-md-3 pb-3">
                                    <label>{{ __('rfqs.district')}}:</label>
                                    <div class="text-dark">{{ $addressData->district }}</div>
                                </div>
                                <div class="col-md-3 pb-3">
                                    <label>{{ __('rfqs.city')}}:</label>
                                    <div class="text-dark">{{ $addressData->city }}</div>
                                </div>
                                <div class="col-md-3 pb-3">
                                    <label>{{ __('rfqs.province')}}:</label>
                                    <div class="text-dark">{{ $addressData->state }}</div>
                                </div>
                                <div class="col-md-3 pb-3">
                                    <label>{{ __('rfqs.pincode')}}:</label>
                                    <div class="text-dark">{{ $addressData->pincode }}</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer px-3">
        <a class="btn btn-cancel" data-bs-dismiss="modal">Cancel</a>
    </div>
    
</div>