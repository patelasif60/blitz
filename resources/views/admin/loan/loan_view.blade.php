<div class="modal-header py-3">
    <h5 class="modal-title" id="exampleModalLabel">
        <img height="24px" class="pe-2" src="{{URL::asset('assets/icons/order_detail_title.png')}}" alt="Order Details">{{$loanApply->loan_number}}
    </h5>
    <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"><img src="{{URL::asset('assets/icons/times.png')}}" alt="Close"></button>
</div>
<div class="modal-body p-3 pb-1">
    <div class="row">
        <div class=" col-md-12 mb-2">
            <section id="contact_detail">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="mb-0"><img height="20px"
                                              src="{{URL::asset('assets/icons/comment-alt-edit.png')}}"
                                              alt="Charges" class="pe-2"> <span>Loan
                                                    Info</span></h5>
                    </div>
                    <div class="card-body p-3 pb-1 my-2">
                        <div class="creditpage row g-3">
                            <div class="col-md-4">
                                <label for="text-dark">{{__('admin.koinworks_loan_id')}}</label>
                                <div>{{$loanApply->provider_loan_id}}</div>
                            </div>
                            <div class="col-md-4">
                                <label for="" class="text-dark">{{__('admin.user_id')}}</label>
                                <div>{{$loanApply->provider_user_id}}</div>
                            </div>

                            <div class="col-md-4">
                                <label for="" class=" text-dark">{{__('admin.order_number')}}</label>
                                <div>{{$loanApply->orders->order_number}}</div>
                            </div>
                            <div class="col-md-4">
                                <label for="" class=" text-dark">{{__('admin.limit_amount')}}</label>
                                <div>{{$loanApply->loanApplications->senctioned_amount}}</div>
                            </div>
                            <div class="col-md-4">
                                <label for="" class="text-dark">{{__('admin.loan_amount')}}</label>
                                <div>{{$loanApply->loan_confirm_amount}}</div>
                            </div>
                            <div class="col-md-4">
                                <label for="" class="text-dark">{{__('admin.available_amount')}}</label>
                                <div>{{($loanApply->loanApplications->remaining_amount)}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class=" col-md-12 mb-2">
            <section id="contact_detail">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/comment-alt-edit.png')}}" alt="Charges" class="pe-2">
                            <span>{{ __('admin.buyer_detail') }}</span>
                        </h5>
                    </div>
                    <div class="card-body p-3 pb-1">

                        <div class="row">
                            <div class="col-md-3 pb-2">
                                <label class="form-label">{{ __('admin.company_name') }}: </label>
                                <div class="text-dark">{{$loanApply->companies->name}}</div>
                            </div>
                            <div class="col-md-2 pb-2">
                                <label class="form-label">{{ __('admin.customer_name') }}:</label>
                                <div class="text-dark">{{$loanApply->loanApplicants->first_name .' '.$loanApply->loanApplicants->last_name}}</div>
                            </div>
                            <div class="col-md-2 pb-2">
                                <label class="form-label">{{ __('admin.customer_phone') }}:</label>
                                <div class="text-dark text-nowrap"> +62 {{$loanApply->loanApplicants->loanApplicantBusiness->phone_number}}</div>
                            </div>
                            <div class="col-md-auto flex-fill pb-2">
                                <label class="form-label">{{ __('admin.customer_email') }}:</label>
                                <div class="text-dark">{{$loanApply->loanApplicants->loanApplicantBusiness->email}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class=" col-md-12 mb-2">
            <section id="product_detail">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="mb-0"><img
                                src="{{URL::asset('assets/icons/credit-card.png')}}"
                                alt="Payment Detail"
                                class="pe-2">
                            <span>{{ __('admin.payment_detail') }}</span>
                        </h5>
                    </div>
                    <div class="card-body p-3 pb-1">
                        <div class="row g-4 mb-4">


                            <div class="table-responsive">
                                <table class="table text-dark table-striped">
                                    <tbody>
                                        <tr class="bg-light">
                                            <th>{{__('admin.item_number')}}</th>
                                            <th>{{__('admin.description')}}</th>
                                            <th>{{__('admin.price')}}</th>
                                            <th>{{__('admin.qty')}}</th>
                                            <th align="right" class="text-end">{{__('admin.amount')}}</th>
                                        </tr>
                                        {{--start items--}}
                                        @foreach ($loanApply->orderItems as $orderItems)
                                            @php
                                                $unit = get_unit_name($orderItems->quoteItem->price_unit);
                                            @endphp
                                            <tr>
                                                <td>{{$orderItems->order_item_number}}</td>
                                                <td>{{ get_product_name_by_id($orderItems->rfq_product_id,1) }}</td>
                                                <td>Rp {{number_format($orderItems->quoteItem->product_price_per_unit,2)}} per {{$unit}}</td>
                                                <td>{{$orderItems->quoteItem->product_quantity}} {{$unit}}</td>
                                                <td align="right">Rp {{number_format($orderItems->quoteItem->product_amount,2)}}</td>
                                            </tr>
                                        @endforeach

                                        {{--end items--}}
                                        @foreach ($amountDetails as $charges)
                                            <tr>
                                                @if ($charges->type == 0)
                                                    <td colspan="4">{{ $charges->charge_name . ' ' . $charges->charge_value." %" }}</td>
                                                @else
                                                    <td colspan="4">{{ $charges->charge_name }}</td>
                                                @endif
                                                <td align="right">
                                                    {{ $charges->addition_substraction == 0 ? '- ' : '+ ' }}{{ 'Rp ' . number_format($charges->charge_amount, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4">{{ __('admin.tax') }} {{ $quote->tax .'%'}}</td>
                                            <td align="right">{{number_format($quote->tax_value, 2)}}</td>
                                        </tr>
                                        <tr class="bg-secondary text-white">
                                            <td colspan="4" class="text-white fw-bold">{{__('admin.total_amount')}}</td>
                                            <td align="right" class="text-white fw-bold">Rp {{number_format($quote->final_amount, 2)}}</td>
                                        </tr>
                                        <!-- This section will hidden from supplier -->
                                        <tr>
                                            <td colspan="4">2% Interest for 30 Days</td>
                                            <td align="right">+ Rp {{number_format($loanIntrestCal['interest_amount'],2)}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">{{__('admin.repayment_charges')}}</td>
                                            <td align="right">+ Rp {{number_format($loanIntrestCal['repayment_charges'],2)}}</td>
                                        </tr>
                                        @if($loanIntrestCal['internal_transfer_charge_count'])
                                            <tr>
                                                <td colspan="4">{{$loanIntrestCal['internal_transfer_charge_count'] .' '. __('admin.internal_transfer_charge')}}</td>
                                                <td align="right">+ Rp {{number_format($loanIntrestCal['total_internal_transfer_charge'],2)}}</td>
                                            </tr>
                                        @endif
                                        @if($loanIntrestCal['origination_charge_count'])
                                            <tr>
                                                <td colspan="4">{{$loanIntrestCal['origination_charge_count'] .' '. __('admin.origination_charge')}}</td>
                                                <td align="right">+ Rp {{number_format($loanIntrestCal['total_origination_charge'],2)}}</td>
                                            </tr>
                                        @endif
                                        @if($loanIntrestCal['vat'])
                                            <tr>
                                                <td colspan="4">{{$loanIntrestCal['vat'] .'% '. __('admin.vat')}}</td>
                                                <td align="right">+ Rp {{number_format($loanIntrestCal['total_vat'],2)}}</td>
                                            </tr>
                                        @endif
                                        @if($loanIntrestCal['late_fee_count'])
                                            <tr>
                                                <td colspan="4">{{$loanIntrestCal['late_fee_count'] .' '. __('admin.late_fee')}}</td>
                                                <td align="right">+ Rp {{number_format($loanIntrestCal['total_late_fee'],2)}}</td>
                                            </tr>
                                        @endif
                                        <tr class="bg-secondary text-white">
                                            <td colspan="4" class="text-white fw-bold">{{__('admin.payable_amount')}}</td>
                                            <td align="right" class="text-white fw-bold">Rp {{number_format($loanIntrestCal['payable_amount'],2)}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<div class="modal-footer p-3">
    <button type="button" class="btn  btn-cancel" data-bs-dismiss="modal">{{__('admin.close')}}</button>
</div>

