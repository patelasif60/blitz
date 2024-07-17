
<div class="modal-header py-3">
    <h5 class="modal-title d-flex align-items-center" id="viewRfqModalLabel">
        <img height="24px" class="pe-2" src="{{URL::asset('front-assets/images/icons/order_detail_title.png')}}" alt="View Quote"> {{ $quote->quote_number ?? '' }}
    </h5>
    @if(isset($quote->group_id))
        <span>
            <button type="button" class="btn btn-info btn-sm ms-2 text-white" style="border-radius: 20px">BGRP-{{$quote->group_id}}</button>
        </span>
    @endif
    <span class="btn btn-warning rounded-pill printicon ms-auto" data-toggle="tooltip" data-placement="bottom" title="{{ __('admin.print') }}"><a target="_blank" href="{{ route('dashboard-get-rfq-quote-print',Crypt::encrypt($quote->id)) }}"><i class="fa fa-print"></i></a></span>
    <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal"
        aria-label="Close">
        <img src="{{URL::asset('front-assets/images/icons/times.png')}}" alt="Close">
    </button>
</div>
<div class="modal-body p-3">
    <div id="viewQuoteDetailBlock">
        <div class="row align-items-stretch">
            <div class="col-md-12 pb-2">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h5><img src="{{URL::asset('front-assets/images/icons/shopping-cart.png')}}" alt="Order Details"
                                class="pe-2"> {{ __('admin.quote_details') }}</h5>
                    </div>
                    <div class="card-body p-3 pb-1">
                        <div class="row rfqform_view bg-white">
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.quote_number') }}:</label>
                                <div>{{ $quote->quote_number ?? ''}}</div>
                            </div>
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.quotation_date') }}:</label>
                                <div class="text-dark">{{ date('d-m-Y H:i:s', strtotime($quote->created_at)) }}</div>
                            </div>
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.rfq_number') }}:</label>
                                <div>{{ $quote->reference_number ?? ''}}</div>
                            </div>
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.rfq_date') }}:</label>
                                <div class="text-dark">{{ date('d-m-Y H:i:s', strtotime($quote->rfq_date)) }}</div>
                            </div>
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.valid_till') }}:</label>
                                <div class="text-dark">{{ date('d-m-Y', strtotime($quote->valid_till)) }}</div>
                            </div>
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.status') }}:</label>
                                <div>{{$quote->quoteStatus->backofflice_name ? __('rfqs.'. $quote->quoteStatus->backofflice_name) : ''}}</div>
                            </div>
                            @if(isset($quote->termsconditions_file) && !empty($quote->termsconditions_file))
                                <div class="col-md-3 pb-2">
                                        @php
                                            $termsconditionsFileTitle = Str::substr($quote->termsconditions_file,stripos($quote->termsconditions_file, "termsconditions_file_") + 21);
                                            $extension_termsconditions_file = getFileExtension($termsconditionsFileTitle);
                                            $termsconditions_file_filename = getFileName($termsconditionsFileTitle);
                                            if(strlen($termsconditions_file_filename) > 10){
                                                $termsconditions_file_name = substr($termsconditions_file_filename,0,10).'...'.$extension_termsconditions_file;
                                            } else {
                                                $termsconditions_file_name = $termsconditions_file_filename.$extension_termsconditions_file;
                                            }
                                        @endphp
                                        <label for="termsconditions_file">{{ __('admin.commercial_terms') }}</label>
                                        <div>
                                            <a href="{{ $quote->termsconditions_file? Storage::url($quote->termsconditions_file) :'javascript:void(0);' }}" class="text-decoration-none" target="_blank" title="{{$termsconditions_file_name}}">{{$termsconditions_file_name}}</a></div>
                                        </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- Buyer Detail -->
            <div class="col-md-12 pb-2">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                    <h5><img height="20px" src="{{URL::asset('front-assets/images/icons/person-dolly-1.png')}}" alt="Buyer Details" class="pe-2"> {{ __('admin.buyer_detail') }}</h5>
                    </div>
                    <div class="card-body p-3 pb-1">
                        <div class="row rfqform_view bg-white">
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.company_of_buyer') }}:</label>
                                <div class="text-dark">{{ $quote->user_company_name ?? '' }}</div>
                            </div>
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.customer_name') }}:</label>
                                <div class="text-dark"> {{ $quote->fullname ?? ''}}</div>
                            </div>
                            @if(auth()->user()->role_id != 3)
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.customer_email') }}:</label>
                                <div class="text-dark">{{ $quote->email ?? '' }}</div>
                            </div>
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.customer_phone') }}:</label>
                                <div class="text-dark"> {{ countryCodeFormat($quote->buyer_phone_code,$quote->mobile) ?? ''}}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supplier Detail -->
            <div class="col-md-12 pb-2">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                    <h5><img height="20px" src="{{URL::asset('front-assets/images/icons/people-carry-1.png')}}" alt="Supplier Details" class="pe-2"> {{ __('admin.supplier_detail') }}</h5>
                    </div>
                    <div class="card-body p-3 pb-1">
                        <div class="row rfqform_view bg-white">
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.supplier_company') }}:</label>
                                <div class="text-dark"> {{ $quote->supplier_company ?? ''}}</div>
                            </div>
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.supplier_name') }}:</label>
                                <div class="text-dark"> {{ $quote->supplier_name ?? '' }}</div>
                            </div>
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.supplier_email') }}:</label>
                                <div class="text-dark"> {{ $quote->suppliers_email ?? '' }}</div>
                            </div>
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.supplier_phone') }}:</label>
                                <div class="text-dark"> {{ countryCodeFormat($quote->supplier_phone_code, $quote->suppliers_mobile) ?? ''}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- product table -->
            <div class="col-md-12 pb-2">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                    <h5 height="20px" class="d-flex align-items-center"><img src="{{URL::asset('front-assets/images/icons/file-alt.png')}}" alt="Product Detail" class="pe-2">  {{ __('admin.product_detail') }} </h5>
                    </div>
                    @php
                        $finalAmount = 0;
                        $discount = 0;
                        $taxAmt = 0;
                        $flag=0;
                        $flagLogistic = 0;
                        $flagSuppler = 0;
                    @endphp
                    <div class="card-body p-0 pb-1">
                        <div class="table-responsive p-2 pb-0">
                            <table class="table text-dark table-striped">
                                <tbody>
                                    <tr class="bg-light">
                                        <th>{{ __('admin.description') }}</th>
                                        <th class="text-start">{{ __('admin.price') }}</th>
                                        <th class="text-start">{{ __('admin.qty') }}</th>
                                        <!-- align="right" -->
                                        <th class="text-center">{{ __('admin.amount') }}</th>
                                    </tr>
                                    @foreach($quote_items as $key => $value)
                                        @php
                                            $finalAmount = $finalAmount + $value->product_amount;
                                            $taxAmt = $taxAmt + $value->product_amount;
                                        @endphp
                                    <tr>
                                        <td>{{ get_product_name_by_id($value->rfq_product_id, 1) }}
                                        </td>
                                        <td class="text-nowrap text-start">{{ 'Rp ' . number_format($value->product_price_per_unit, 2) }} per {{ $value->quote_unit_name }}</td>
                                        <td class="text-nowrap text-start">{{ $value->product_quantity??'' }} {{ $value->quote_unit_name??'' }}</td>
                                        <td class="text-nowrap" align="right">{{ 'Rp ' . number_format($value->product_amount, 2) }}</td>
                                    </tr>
                                    @endforeach
                                    @foreach ($quotes_charges_with_amounts as $charges)
                                        @if($charges->charge_amount>0)
                                        <tr>

                                            @if ($charges->type == 0)
                                                @php
                                                    if($charges->charge_name == 'Discount')
                                                    { $flag = 1; }
                                                    $flagSuppler = 1;
                                                @endphp
                                                <td colspan="3">@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif {{ $charges->charge_name . ' ' . $charges->charge_value }} %
                                                    @if($charges->charge_type != 2)
                                                        <small class="fw-bold text-blue">
                                                            ({{ $charges->charge_type == 1 ? __('admin.logistic_charges') : __('admin.supplier_other_charges') }})
                                                        </small>
                                                    @endif
                                                </td>
                                            @else
                                                @if($charges->charge_type == 2)
                                                    <td colspan="3">{{ $charges->charge_name }} <small class="fw-bold text-blue">{{--({{ __('admin.platform_charges') }})--}}</small></td>
                                                @elseif($charges->charge_type == 1)
                                                     @php
                                                        $flagLogistic = 1;
                                                    @endphp
                                                    <td colspan="3">@if($charges->custom_charge_name) {{$charges->custom_charge_name .' -'}} @endif {{ $charges->charge_name }} <small class="fw-bold text-blue">({{ __('admin.logistic_charges') }})</small></td>
                                                @else
                                                    @php
                                                        $flagSuppler = 1;
                                                    @endphp
                                                    <td colspan="3">@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif {{ $charges->charge_name }} <small class="fw-bold text-blue">({{ __('admin.supplier_other_charges') }})</small></td>
                                                @endif
                                            @endif

                                                @php
                                                    if (auth()->user()->role_id == 3)
                                                    {
                                                        if ($charges->charge_name != 'Discount') {
                                                            if ($charges->addition_substraction == 0) {
                                                                $finalAmount = $finalAmount - $charges->charge_amount;
                                                            } else {
                                                                $finalAmount = $finalAmount + $charges->charge_amount;
                                                            }

                                                            if($quote->inclusive_tax_other == 0)
                                                            {
                                                                if($charges->addition_substraction == 0){
                                                                    $taxAmt = $taxAmt - $charges->charge_amount;
                                                                }
                                                                else{
                                                                    $taxAmt = $taxAmt + $charges->charge_amount;
                                                                }
                                                            }

                                                        } else {
                                                            $discount = $charges->charge_amount;
                                                        }
                                                    }
                                                @endphp

                                            <td align="right" class="text-nowrap">
                                                {{ $charges->addition_substraction == 0 ? '- ' : '+ ' }}{{ 'Rp ' . number_format($charges->charge_amount, 2) }}
                                            </td>

                                        </tr>
                                        @endif
                                    @endforeach
                                    @php

                                        if (auth()->user()->role_id == 3){
                                            $totalAmount = $finalAmount - $discount;
                                            $newTexAmt = $taxAmt - $discount;
                                            $taxamount = ($newTexAmt * $quote->tax) / 100;
                                            $payamount = $totalAmount + $taxamount;
                                        }
                                        $tex = (auth()->user()->role_id == 3) ? $taxamount : $quote->tax_value;
                                        $lastamount = (auth()->user()->role_id == 3) ? $payamount : $quote->final_amount;
                                    @endphp
                                    @if($quote->tax > 0 && $tex > 0)
                                    <tr>
                                        <td colspan="3">Tax {{ $quote->tax }} %
                                                <small class="fw-bold text-blue">({{ __('admin.product') }})</small>
                                            @if( $quote->inclusive_tax_other==0 && $flagSuppler == 1 )
                                               + <small class="fw-bold text-blue">({{ __('admin.supplier_other_charges') }})</small>
                                            @endif
                                            @if( $quote->inclusive_tax_logistic==0 && $flagLogistic == 1 )
                                                + <small class="fw-bold text-blue">({{ __('admin.logistic_charges') }})</small>
                                            @endif

                                            @if( $flag != 0 )
                                                - <small class="fw-bold text-blue">({{ __('admin.discount') }})</small>
                                            @endif

                                        </td>

                                        <td align="right" class="text-nowrap">+ {{ 'Rp ' . number_format($tex, 2)}}</td>
                                    </tr>
                                    @endif

                                    <tr class="bg-secondary text-white">
                                        <td colspan="3" class="text-white">{{ __('admin.total') }}</td>
                                        <td align="right" class="text-white text-nowrap">{{ 'Rp ' . number_format($lastamount, 2)}}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="p-3 py-2 fs_14">
                            <strong>{{ __('admin.disclaimer') }}:</strong>  {{ __('admin.deliver_order_in') }} {{ $quote->min_delivery_days }} to {{ $quote->max_delivery_days }} Days*
                        </div>
                        <div class="p-3 py-2 fs_14">
                            <strong>{{ __('admin.note') }}:</strong>  {{ $quote->note ??''}}
                        </div>
                        <div class="p-3 py-2 fs_14">
                            <strong>{{ __('admin.comment') }}:</strong> {{ $quote->comment ??''}}
                        </div>
                        <div class="p-3 py-2 fs_14">
                            @if (isset($certificate_attachment) && !empty($certificate_attachment))
                                @php
                                    $downloadProductCertificate = "downloadProductCertificateZip(".$quote->id.", 'attached_document','".$quote->quote_number."')";
                                @endphp
                                <strong>{{ __('admin.product_certificate') }}:</strong>
                                <a class="text-decoration-none btn btn-primary p-1"  href="javascript:void(0);" onclick="{{$downloadProductCertificate}}" id="catalogFileDownload" data-toggle="tooltip" data-placement="bottom" title="{{ __('admin.attachment') }}">
                                    <svg id="Layer_1" width="12px" fill="#fff" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg> {{ __('admin.attachment') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Show Approvers List -->
            @if(isset($approversList) && count($approversList) > 0)
            <div class="col-md-12 pb-2">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="mb-0">
                            <img src="{{ URL::asset('front-assets/images/icons/approve.png') }}" alt="Approver Details" class="pe-2">
                            <span>{{ __('admin.approver_details') }} : </span>
                        </h5>
                    </div>
                    <div class="card-body p-3 pb-1">
                        <div class="bg-white row d-flex mb-3">
                            @foreach($approversList as $approver)
                            @php
                                if(isset($approver->feedback) && $approver->feedback == 0) {
                                    $feedback = URL::asset('front-assets/images/pending.png');
                                } elseif ($approver->feedback == 1) {
                                    $feedback = URL::asset('front-assets/images/thumbs-up.png');
                                } else {
                                    $feedback = URL::asset('front-assets/images/thumbs-down.png');
                                }
                            @endphp
                            <div class="col-md-6" style="font-size: 14px;"><img src="{{ $feedback }}" height="14" width="14" alt="Thumbs-up" srcset="">
                                <strong>{{ $approver->firstname . ' ' . $approver->lastname }}</strong> ({{ $approver->name }})
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<div class="modal-footer px-3">
    <a class="btn btn-cancel" data-bs-dismiss="modal">{{ __('admin.cancel') }}</a>
</div>

<div style="text-align: -webkit-right" id="viewRfqButtons"></div>

<div id="callRFQ" style="display:none">
    <form class="form-group row g-3" id="rfqCallComment" method="POST"
        action="{{ route('rfq-call-comment') }}" data-parsley-validate>
        @csrf

        <div class="col-12">
            <label for="comment" class="form-label">{{ __('admin.call_comments') }} <span class="text-danger">*</span></label>
            <textarea name="comment" class="form-control" id="comment" cols="30" rows="3"
                required></textarea>
        </div>
        <input type="hidden" id="call_rfq_id" name="rfq_id" />

        <div class="col-12">
            <button type="submit" class="btn btn-primary">{{ __('admin.save') }}</button>
        </div>
    </form>
    <div id="message"></div>
</div>
<div id="callRFQHistory"></div>
<script>
    $(document).ready(function() {
        $(function () {
            $('[data-toggle="tooltip"]').tooltip({
                trigger : 'hover'
            });
        })
    });
</script>
