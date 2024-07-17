@php
$tax = $order->quote->tax??0;
@endphp
<div class="modal-header py-3">
    <h5 class="modal-title" id="disbursementModalLabel">{{ __('admin.disburse') }}</h5>
    <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close">
        <img src="{{URL::asset('front-assets/images/icons/times.png')}}" alt="Close">
    </button>
</div>
<div class="modal-body p-3" id="disbursementModalBlock">
    <form action="{{route('group-settlement')}}" method="post" id="disbursementForm" data-parsley-validate>
        @csrf
        <input type="hidden" name="order_id" value="{{$order->id}}">
        <!-- Supplier Detail -->
        <div class="col-md-12 pb-2">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5><img height="20px" src="{{URL::asset('assets/icons/people-carry-1.png')}}" alt="Supplier Details" class="pe-2"> {{ __('admin.supplier_detail') }}</h5>
                </div>
                <div class="card-body p-3 pb-1">
                    <div class="row rfqform_view bg-white">
                        <div class="col-md-4 pb-2">
                            <label>{{ __('admin.supplier_company') }}</label>
                            <div class="text-dark">{{$supplier->name}}</div>
                        </div>
                        <div class="col-md-4 pb-2">
                            <label>{{ __('admin.supplier_name') }}</label>
                            <div class="text-dark">{{$supplier->contact_person_name}}</div>
                        </div>
                        <div class="col-md-4 pb-2">
                            <label>{{ __('admin.supplier_email') }}</label>
                            <div class="text-dark">{{$supplier->email}}</div>
                        </div>
                        <div class="col-md-4 pb-2">
                            <label>{{ __('admin.supplier_phone') }}</label>
                            <div class="text-dark">{{$supplier->contact_person_phone}}</div>
                        </div>
                        <div class="col-md-4 pb-2">
                            <label>{{ __('admin.supplier_last_paid_date') }}</label>
                            <div>{{$lastSupplierTransactionDate}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 pb-2">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5><img height="20px" src="{{ URL('assets/icons/bank.png') }}" alt="Supplier Details" class="pe-2"> {{ __('admin.bank_detail') }}</h5>
                </div>
                <div class="card-body p-3 pb-1">
                    <div class="row rfqform_view bg-white">
                        <div class="col-md-4 pb-2">
                            <label>{{ __('admin.bank_name') }}</label>
                            <div class="text-dark">
                                <img src="{{ URL($supplierBank->bankDetail->logo??'assets/icons/bank.png') }}" id="bank_logo" height="20px" width="20px" alt="bank Logo">
                                <span>{{$supplierBank->bankDetail->name??''}}</span>
                            </div>
                        </div>
                        <div class="col-md-4 pb-2">
                            <label>{{ __('admin.bank_account_holder_name') }}</label>
                            <div class="text-dark">{{$supplierBank->bank_account_name??''}}</div>
                        </div>
                        <div class="col-md-4 pb-2">
                            <label>{{ __('admin.bank_account_number') }}</label>
                            <div class="text-dark">{{$supplierBank->bank_account_number??''}}</div>
                        </div>
                        <div class="col-md-4 pb-2">
                            <label>{{ __('admin.xen_platform_id') }}</label>
                            <div class="text-dark" id="xen_platform_id">{{$supplier->xen_platform_id}}</div>
                        </div>
                        <div class="col-md-4 pb-2">
                            <label>{{ __('admin.xen_platform_balance') }}</label>
                            <div id="xen_balance"><img height="16px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                                <span>{{$group->name}}</span>
                            </div>
                        </div>
                        <div class="col-md-6 pb-2">
                            <label>{{ __('admin.total_group_disburse_amount') }}</label>
                            <div class="text-dark" id="bank_ac_holder_name">Rp. {{$maxDisbursementAmount = getMaxDisbursementAmountForGroup($group)}}</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 pb-3">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0">
                        <img height="20px" src="{{URL::asset('front-assets/images/icons/boxes.png')}}" alt="{{ __('admin.order_details') }}" class="pe-2" />
                        <span> {{ __('admin.order_details') }}</span>
                    </h5>
                </div>
                <div class="card-body p-3 pb-2">
                    <div class="row rfqform_view bg-white">
                        <div class="col-md-4 pb-2">
                            <label class="form-label">{{ __('admin.order_number') }}</label>
                            <div id="order-number">{{$order->order_number}}</div>
                        </div>
                        <div class="col-md-8 pb-2">
                            <label class="form-label">{{ __('admin.payment_received_date') }}</label>
                            <div id="payment-received-date">{{$paymentReceivedDate}}</div>
                        </div>

                        <div class="col-md-12 pb-2">
                            <div class="table-responsive" >
                                <table class="table">
                                    <tbody id="multiple_product_table_show">
                                        <tr class="bg-light">
                                            <th class="fw-bold">{{ __('admin.product') }}</th>
                                            <th class="fw-bold text-center">{{ __('admin.qty') }}</th>
                                            <th class="text-end fw-bold">{{ __('admin.total_amount') }} (Rp)</th>
                                        </tr>
                                        @foreach($quoteItems as $quoteItem)
                                        <tr>
                                            <td><span id="product-name{{$quoteItem->id}}"></span>{{get_product_name_by_id($quoteItem->rfq_product_id)}}</td>
                                            <td class="text-center"><div id="product-qty{{$quoteItem->id}}">{{$quoteItem->product_quantity}}</div></td>
                                            @php
                                                $productAmount = calcProductAmountForDisburse($quoteItem,$tax);
                                            @endphp
                                            <td class="text-end text-nowrap"><span class="calc" id="total-amount{{$quoteItem->id}}" data-is_add="1" data-amount="{{$productAmount}}">{{$productAmount}}</span></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 pb-2">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0">
                        <img height="20px" src="{{URL::asset('front-assets/images/icons/credit-card-black.png')}}" alt="Charges Details" class="pe-2" />
                        <span> {{ __('admin.charges_details') }}</span>
                    </h5>
                </div>
                <div class="card-body p-3 pb-2">
                    <div class="table-responsive">
                        <table class="table text-dark table-striped">
                            <tbody id="charges">
                            @if(!empty($platformCharges->count()))
                                <tr class="bg-light"><th colspan="2" class="fw-bold"><input class="form-check-input mr-5 mt-0" type="checkbox" checked="" disabled="">{{ __('admin.supplier_other_charges') }}</th></tr>
                                @foreach($platformCharges as $platformCharge)
                                <tr class="">
                                    <td>
                                        <div class="form-check ms-5 my-0">
                                            <input class="form-check-input calc " readonly="" type="checkbox" data-is_add="{{$platformCharge->addition_substraction}}" data-amount="{{setTaxForDisburse($tax,$platformCharge->charge_amount)}}" id="flexCheckChecked{{$platformCharge->id}}" name="" value="{{$platformCharge->id}}" disabled="" checked="">
                                            <label class="form-check-label" for="flexCheckChecked{{$platformCharge->id}}">{{$platformCharge->charge_name}}</label>
                                        </div>
                                    </td>
                                    <td align="right ">{{$platformCharge->addition_substraction?'+':'-'}}{{setTaxForDisburse($tax,$platformCharge->charge_amount)}}</td>
                                </tr>
                                @endforeach
                            @endif
                            @if(!empty($logisticCharges->count()))
                                <tr class="bg-light"><th colspan="2" class="fw-bold"><input class="form-check-input mr-5 mt-0" type="checkbox" id="log_charges">{{ __('admin.logistic_charges') }}</th></tr>
                                @foreach($logisticCharges as $logisticCharge)
                                <tr class="strikethrough">
                                    <td>
                                        <div class="form-check ms-5 my-0">
                                            <input class="form-check-input calc add_remove_charge data-exclude" readonly="" type="checkbox" data-is_add="{{$logisticCharge->addition_substraction}}" data-amount="{{setTaxForDisburse($tax,$logisticCharge->charge_amount)}}" id="flexCheckChecked{{$logisticCharge->id}}" name="logistic_charges[]" value="{{$logisticCharge->id}}">
                                            <label class="form-check-label" for="flexCheckChecked{{$logisticCharge->id}}">{{$logisticCharge->charge_name}}</label>
                                        </div>
                                    </td>
                                    <td align="right ">{{$logisticCharge->addition_substraction?'+':'-'}}{{setTaxForDisburse($tax,$logisticCharge->charge_amount)}}</td>
                                </tr>
                                @endforeach
                            @endif
                                <tr class="" style="background: lightgray;font-weight: bold;">
                                    <td class="">{{ __("admin.payable_amount")}}</td>
                                    <td align="right" class="" id="sub-payble-amount">0</td>
                                </tr>
                            @if($transactionCharges<10450)
                                <tr class="">
                                    <td class="">{{ __("admin.disbursment_charge_xendit")}}</td>
                                    <td align="right" id="disbursment_charge_xendit" class="">-{{getDisbursementCharge()}}</td>
                                </tr>
                            @endif
                                <tr class="grey_tab text-white">
                                    <td class="text-white">{{ __("admin.total_payable_amount")}}</td>
                                    <td align="right" class="text-white" id="payble-amount">0</td>
                                </tr>
                            <tr id="refundable_amount_tr" class="d-none">
                                <td class="">
                                    <div class="form-check ms-5 my-0">
                                        <input class="form-check-input" type="checkbox" id="max_disbursement_amount" value="{{$maxDisbursementAmount}}" checked disabled>
                                        <label class="form-check-label" for="max_disbursement_Amount">{{ __("admin.refundable_amount_for_buyer")}}</label>
                                    </div>
                                </td>
                                <td align="right" class="" id="refundable_amount">-0</td>
                            </tr>
                            @if($supplierTransactionFees>0)
                            <tr class="">
                                <td class="">
                                    <div class="form-check ms-5 my-0">
                                        <input class="form-check-input" type="checkbox" data-is_add="0" data-amount="{{$supplierTransactionFees}}"
                                               id="supplier_transaction_fees" name="supplier_transaction_fees" value="{{$supplierTransactionFees}}" onchange="calc_disbursement_amount();setStrikethrough($(this));" checked>
                                        <label class="form-check-label" for="supplier_transaction_fees">{{ __("admin.transaction_cost_xendit")}}</label>
                                    </div>
                                </td>
                                <td align="right" class="" id="supplier-charge" data-charge="{{$supplierTransactionFees}}">-{{$supplierTransactionFees}}</td>
                            </tr>
                            @endif
                            <tr class="">
                                <td class="">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check ms-5 my-0" style="width: 200px;">
                                            <input class="form-check-input" type="checkbox" data-is_add="0" id="blitznet_commission" name="blitznet_commission" value="1" checked>
                                            <label class="form-check-label" for="">{{ __("admin.blitznet_commission")}}</label>
                                        </div>
                                        <div class=" ms-auto ps-2">
                                            <select class="form-select" id="blitznet_commission_type" name="blitznet_commission_type" style="width: 80px;">
                                                <option selected value="0">%</option>
                                                <option value="1">{{ __("admin.flat")}}</option>
                                            </select>
                                        </div>
                                        <div class="ps-2">
                                            <input type="text" class="form-control" style="text-align: end;" id="blitznet_commission_per" data-parsley-type="number" min="0" max="100" name="blitznet_commission_per" value="{{$group->group_margin}}" onkeypress="return isNumberKey(this, event);">
                                        </div>
                                    </div>

                                </td>
                                <td align="right" class="" style="width: 18%;">
                                    <span id="total_blitznet_commission_span" class="">-0</span>
                                    <div class="input-group d-none" id="blitznet_commission_amount_div">
                                        <span class="input-group-text" id="commission-minus">-</span>
                                        <input type="text" class="form-control" style="text-align: end;" id="blitznet_commission_amount" name="blitznet_commission_amount" placeholder="0" onkeypress="return isNumberKey(this, event);" aria-describedby="commission-minus">
                                    </div>
                                </td>
                            </tr>
                            <tr class="">
                                <td class="">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check ms-5 my-0" style="width: 200px;">
                                            <label class="form-check-label"
                                                   for="">{{ __("admin.disbursement_amount")}}</label>
                                        </div>
                                        <div class="ps-2 ms-auto">
                                            <select class="form-select" id="disbursement_amount_type" name="disbursement_amount_type" style="width: 80px;">
                                                <option value="0">%</option>
                                                <option selected value="1">{{ __("admin.flat")}}</option>
                                            </select>
                                        </div>
                                        <div class="ps-2">
                                            <input type="text" class="form-control d-none" style="text-align: end;" id="disbursement_amount_per" name="disbursement_amount_per" value="100" onkeypress="return isNumberKey(this, event);">
                                        </div>
                                    </div>

                                </td>
                                <td align="right" class="" style="width: 18%;">
                                    <span class="d-none" id="final_amount_span">-0</span>
                                    <div class="input-group" id="final_disburse_amount_div">
                                        <span class="input-group-text" id="final-disburse-minus">-</span>
                                        <input type="text" class="form-control" style="text-align: end;" id="final_disburse_amount" name="final_disburse_amount" placeholder="0" onkeypress="return isNumberKey(this, event);" aria-describedby="final-disburse-minus">
                                    </div>
                                </td>
                            </tr>
                            <tr class="grey_tab text-white">
                                <td class="text-white">{{ __("admin.total_amount")}}</td>
                                <td align="right" class="text-white" id="total_disbursement_amount">0</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <span class="badge badge-pill badge-danger p-2" id="supplierNoAccount" style="{{empty($supplierBank)?'':'display:none'}}">{{ __('admin.no_primary_account_message') }}.</span>
    <span class="badge badge-pill badge-danger p-2" id="amount-greater" style="">{{ sprintf(__('admin.payable_amount_greater'),getMinDisbursementAmount()) }}.</span>
    @if(!empty($supplierBank))
    <button type="button" id="disbursementSubmitBtn" class="btn btn-primary" style="display: none">{{ __('admin.submit') }}</button>
    @endif
    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">{{ __('admin.close') }}</button>
</div>
