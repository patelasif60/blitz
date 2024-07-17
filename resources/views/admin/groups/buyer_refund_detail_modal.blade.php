<div class="modal-header py-3">
    <h5 class="modal-title d-flex align-items-center" id="staticBackdropLabel"><img height="24px" class="pe-2" src="{{URL::asset('assets/icons/order_detail_title.png')}}" alt="Order Details"> {{ $order->order_number }}  <span>
        <button type="button" class="btn btn-info btn-sm ms-2 text-white" style="border-radius: 20px"> BGRP - {{ $order->group_id }}</button>
    </span>
    </h5>


    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close">
        <img src="{{URL::asset('assets/icons/times.png')}}" alt="Close">
    </button>
</div>
<div class="modal-body p-3">
    <div id="viewQuoteDetailBlock">
        <div class="row align-items-stretch">
            <div class="col-md-12 pb-2">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h5><img height="20px"
                                 src="{{URL::asset('assets/icons/group_details.png')}}"
                                 alt="{{ __('admin.group_detail') }}" class="pe-2">{{ __('admin.group_detail') }}</h5>
                    </div>
                    <div class="card-body p-3 pb-1">
                        <div class="row rfqform_view bg-white">
                            <div class="col-md-6 pb-2">
                                <label>{{ __('admin.group_name') }}</label>
                                <div class="text-dark">
                                    <span id="">{{ $order->group->name }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 pb-2">
                                <label>{{ __('admin.refund_amount') }}</label>
                                <div class="text-blue" id="">Rp. {{$buyerRefundAmount}} <small class="text-danger p-1 mb-0">(-{{$disbursementCharge}} {{__('admin.disbursement_charge')}})</small></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 pb-2">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h5><img height="20px"
                                 src="{{ URL('assets/icons/bank.png') }}"
                                 alt="{{ __('admin.bank_detail') }}" class="pe-2"> {{ __('admin.bank_detail') }}</h5>
                    </div>
                    <div class="card-body p-3 pb-1">
                        <div class="row rfqform_view bg-white">
                            <div class="col-md-4 pb-2">
                                <label>{{ __('admin.bank_name') }}</label>
                                <div class="text-dark">
                                    @if(empty($bankDetails))
                                        <img src="{{ URL('assets/icons/bank.png') }}"
                                             id="bank_logo" height="20px" width="20px"
                                             alt="bank Logo">
                                    @else
                                        <img src="{{ URL($bankDetails->logo) }}"
                                             id="bank_logo" height="20px" width="20px"
                                             alt="bank Logo">
                                    @endif
                                    <span id="supplier_bank">{{ $bankDetails?$bankDetails->name:'-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 pb-2">
                                <label>{{ __('admin.bank_account_holder_name') }}</label>
                                <div class="text-dark" id="bank_ac_holder_name">{{ $buyerBank->account_holder_name??'-' }}
                                </div>
                            </div>
                            <div class="col-md-4 pb-2">
                                <label>{{ __('admin.bank_account_number') }}</label>
                                <div class="text-dark" id="bank_ac_number">{{ $buyerBank->account_number??'-' }}</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Buyer Detail -->
            <div class="col-md-12 pb-2">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h5><img height="20px"
                                 src="{{URL::asset('front-assets/images/icons/person-dolly-1.png')}}"
                                 alt="{{ __('admin.buyer_detail') }}" class="pe-2"> {{ __('admin.buyer_detail') }}</h5>
                    </div>
                    <div class="card-body p-3 pb-1">
                        <div class="row rfqform_view bg-white">
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.company_name') }}:</label>
                                <div class="text-dark">{{ $buyerCompany->name??'-' }}</div>
                            </div>
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.customer_name') }}:</label>
                                <div class="text-dark"> {{ $buyer->firstname .' '.  $buyer->lastname }}</div>
                            </div>
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.customer_email') }}:</label>
                                <div class="text-dark">{{ $buyer->email }}</div>
                            </div>
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.customer_phone') }}:</label>
                                <div class="text-dark">{{ countryCodeFormat($buyer->phone_code,$buyer->mobile) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
<div class="modal-footer px-3">
    @if(empty($bankDetails))
        <span class="badge badge-pill badge-danger p-2" style="{{empty($supplierBank)?'':'display:none'}}">{{ __('admin.buyer_no_primary_account_message') }}.</span>
    @elseif($buyerRefundAmount<$minDisbursementAmount)
        <span class="badge badge-pill badge-danger p-2" style="{{empty($supplierBank)?'':'display:none'}}">{{ sprintf(__('admin.payable_amount_greater'),$minDisbursementAmount) }}</span>
    @else
        <form action="{{route('buyer-refund')}}" method="post" id="disbursementForm" data-parsley-validate>
            @csrf
            <input type="hidden" name="order_id" value="{{$order->id}}">
            <button type="submit" id="buyerRefundSubmitBtn" class="btn btn-primary">{{ __('admin.submit') }}</button>
        </form>
    @endif
    <a class="btn btn-cancel" data-bs-dismiss="modal">Cancel</a>
</div>

