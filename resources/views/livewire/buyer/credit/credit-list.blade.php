<div wire:loading.delay.class="opacity-50" wire:loading.delay.longest>

    <input type="hidden" >
    @foreach($orders as $order)
        <div class="accordion pd_section order_section mt-2 @if($loop->last) wire-end-accordion @endif" id="accordion_pd">
            <div class="accordion-item radius_1 mb-2">
                <h2 class="accordion-header d-flex bp_total_payment" id="accordion-title-{{$order->order_number}}">
                    <button
                        class="accordion-button justify-content-between bp-accordion-btn payment-accordion-btn collapsed"
                        type="button" data-bs-toggle="collapse" data-bs-target="#accordion-tab-{{$order->order_number}}"
                        aria-expanded="true" aria-controls="accordion-tab-{{$order->order_number}}" data-supplier_id="62">
                        <div class="flex-grow-1" style="font-size: 13px">{{__('admin.credit_number')}}: {{$order->loanApply->loan_number ?? '-'}}</div>
                        <div class="pe-3"><span>{{__('admin.date')}}: {{changeDateFormat($order->loanApply->created_at) ?? '-'}}</span></div>
                    </button>
                </h2>
                <div id="accordion-tab-{{$order->order_number}}" class="accordion-collapse collapse" aria-labelledby="accordion"
                     data-bs-parent="#accordion_pd">
                    <div class="accordion-body bg-light-gray p-1">
                        <div class="row">
                            <div class="col-lg-12 mainorderdetails d-flex align-content-stretch flex-wrap">
                                <div id="pd_order_list62" class="card p-3 radius w-100"
                                     style="flex-shrink: 0;">
                                    <div class="card-body p-0">
                                        <div class="creditpage row paymentform_view rfqform_view g-2">
                                            <div class="col-md-3">
                                                <label>{{__('admin.credit_number')}}:</label>
                                                <div>{{$order->loanApply->loan_number ?? '-'}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label>{{__('admin.payment')}}:</label>
                                                <div> {{'Rp '.number_format($order->loanApply->loan_repay_amount,2) ?? 'Rp 0.00'}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label>{{__('admin.tenure_days')}}:</label>
                                                <div>{{ $order->loanApply->tenure_days.' '.__('admin.days') ?? '-' }}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label>{{__('admin.due_date')}}:</label>
                                                <div>{{changeDateFormat($order->loanApply->due_date,'d/m/Y')??'-'}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label>{{__('admin.customer_name')}}:</label>
                                                <div>{{$order->loanApply->user->full_name ?? '-'}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label>{{__('admin.order_number')}}:</label>
                                                <div>{{$order->order_number}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label>{{__('admin.company_name')}}:</label>
                                                <div>{{$order->companyDetails->name}}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label>{{__('profile.mobile_number')}}:</label>
                                                <div>{{$order->loanApply->user->mobile_number ?? '-'}}</div>
                                            </div>

                                            <div class="col-md-12 mt-4">
                                                <div class="koinwork-table" style="border:1px solid #E2DEDE; max-height: 200px; overflow-y: auto; z-index: 5;">
                                                    <table class="table border border-bottom-0 mb-0">
                                                        <thead class="bg-light"
                                                               style="position: sticky; top: -3px;">
                                                        <tr>
                                                            <th>{{__('admin.item_number')}}</th>
                                                            <th>{{__('admin.description')}}</th>
                                                            <th>{{__('admin.price')}}</th>
                                                            <th>{{__('admin.qty')}}</th>
                                                            <th class="text-end">{{__('admin.amount')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        @foreach($order->quote->quoteItems as $item)
                                                            <tr>
                                                                <td>{{ $item->quote_item_number }}</td>
                                                                <td>{{ $item->rfqProduct->product_name_desc }}</td>
                                                                <td>{{ 'Rp '.number_format($item->product_price_per_unit,2) }}</td>
                                                                <td>{{ $item->product_quantity.' '.get_unit_name($item->price_unit) ?? '' }}</td>
                                                                <td class="text-end">{{ 'Rp '.number_format($item->product_amount,2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>

                                                    </table>
                                                </div>
                                                <table class="table border botder-top-0">
                                                    <tfoot>

                                                    @foreach($order->quote->quoteChargesWithAmounts as $charge)
                                                        <tr>

                                                            <td colspan="3">
                                                                @if ($charge->type == 0)
                                                                    {{ __('admin.'.Str::snake($charge->charge_name)) }} {{$charge->charge_value }} %
                                                                @else
                                                                    {{ __('admin.'.Str::snake($charge->charge_name)) }}
                                                                @endif
                                                            </td>

                                                            <td class="text-end text-nowrap">
                                                                {{ $charge->addition_substraction == 0 ? '- ' : '+ ' }}{{ 'Rp ' . number_format($charge->charge_amount, 2) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                    <tr>
                                                        <td colspan="3">{{ __('admin.tax') }} {{ $order->quote->tax .'%'}}</td>
                                                        <td class="text-end text-nowrap">
                                                            + Rp {{number_format($order->quote->tax_value, 2)}}
                                                        </td>
                                                    </tr>

                                                    @foreach($order->loanTransactions->where('transaction_type_id', TRANSACTION_TYPES['CHARGES'])->sortBy('created_at') as $transaction)
                                                        <tr>
                                                            <td colspan="3">
                                                                {{ __('admin.'.\Str::snake($transaction->loanCharge->loanProviderChargesType->name)) }}
                                                                @if ($transaction->loanCharge->amount_type == 0)
                                                                    {{ $transaction->loanCharge->day_charge_desc }}
                                                                @endif
                                                            </td>

                                                            <td class="text-end text-nowrap">
                                                                + Rp {{number_format($transaction->transaction_amount , 2)}}
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                    <tr class="bg-light">
                                                        <td colspan="3" class="fw-bold">{{ __('admin.total_payable') }}</td>
                                                        <td class="text-end text-nowrap fw-bold">
                                                            Rp {{number_format($order->loanApply->loan_repay_amount, 2)}}
                                                        </td>
                                                    </tr>

                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="col-md-12 text-end mt-2">
                                                @if(!empty($order->loanApply->paymentLink->where('status',0)->first()))
                                                    <a name="repayment" id="repayment" class="btn btn-sm btn-primary px-3 payment-link" data-id="{{$order->enctypeLoanId}}" href="{{$order->loanApply->paymentLink->where('status',0)->first()->payment_link}}" role="button" data-toggle="tooltip" title="{{__('admin.payback')}}" target="_blank"><img src="{{asset('assets/images/page1_arrow.png')}}" alt="{{__('admin.payback')}}" class="pe-1" style="max-height: 12px;">{{__('admin.payback')}}</a>

                                                @else
                                                    <a name="generatePayment" id="generatePayment" class="btn btn-sm btn-primary px-3 generate-payment-link" data-id="{{$order->enctypeLoanId}}" href="javascript:void(0)" role="button" data-toggle="tooltip" title="{{__('admin.generate_payment_link')}}">
                                                        <span class="generate-payment-link-text">{{__('admin.generate_payment_link')}}</span>
                                                        <div class="spinner-border d-none" role="status">
                                                            <span class="sr-only">{{__('admin.loading')}}...</span>
                                                        </div></a>
                                                    <a name="repayment" id="repayment" class="btn btn-sm btn-primary px-3 payment-link d-none" data-id="{{$order->enctypeLoanId}}" href="#" role="button"><img src="{{asset('assets/images/page1_arrow.png')}}" alt="{{__('admin.payback')}}" class="pe-1" data-toggle="tooltip" title="{{__('admin.payback')}}" style="max-height: 12px;" target="_blank">{{__('admin.payback')}}</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <input type="hidden" id="load-more" value="{{$hasMore ?? 0}}">

</div>
