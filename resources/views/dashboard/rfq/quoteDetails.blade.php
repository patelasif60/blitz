<div class="modal-header">
    <h5 class="modal-title d-flex w-100 align-items-center" id="viewModalLabel">{{ $quote->quote_number }} <small
            class="text-muted font_sub fs-6 ps-2"> ({{ __('rfqs.Details') }}) </small>
      {{-- old code for product  Certificate
        @if ($quote->certificate)
            <a href="javascript:void(0);" onclick="downloadCertificate('{{ $quote->quotes_id }}', 'certificate', '{{ Str::substr($quote->certificate, stripos($quote->certificate, 'certificate_') + 12) }}')" id="catalogFileDownload" target="_blank" class="ms-auto me-3 d-flex" >
                <img src="{{ URL::asset('front-assets/images/icons/certificate_download.png') }}" alt="PDF"></a>
        @endif--}}
    </h5>
    <a href="{{ route('dashboard-get-rfq-quote-print',Crypt::encrypt($quote->quotes_id)) }}" target="_blank"  class="btn px-3 py-1 "><img src="{{ URL::asset('front-assets/images/icons/icon_print.png') }}" alt="Print" class="pe-2"></a>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">

    <div class="row rfqform_view g-3">
        <div class="col-md-4">
            <label>{{ __('rfqs.rfq_number') }}:</label>
            <div>{{ $quote->rfq_reference_number }}</div>
        </div>
        <div class="col-md-4">
            <label>{{ __('rfqs.date') }}:</label>
            <div>{{ date('d-m-Y H:i', strtotime($quote->created_at)) }}</div>
        </div>
        <div class="col-md-4">
            <label>{{ __('rfqs.valid_till') }}:</label>
            <div>{{ date('d-m-Y', strtotime($quote->valid_till)) }}</div>
        </div>

        <div class="col-md-4">
            <label>{{ __('rfqs.name') }}:</label>
            <div class="text-capitalized">{{ $quote->rfq_firstname . ' ' . $quote->rfq_lastname }}</div>
        </div>
        <div class="col-md-4">
            <label>{{ __('rfqs.company') }}:</label>
            <div>{{ $quote->user_company_name }}</div>
        </div>
        <div class="col-md-4">
            <label>{{ __('rfqs.mobile') }}:</label>
            <div>{{ countryCodeFormat($quote->user_phone_code, $quote->rfq_mobile) }}</div>
        </div>
        <div class="col-md-4">
            <label>{{ __('rfqs.pincode') }}:</label>
            <div>{{ $quote->rfq_pincode }}</div>
        </div>
        <div class="col-md-4">
            <label>{{ __('rfqs.expected_delivery_date') }}:</label>
            @if ($quote->expected_date)
                <div> {{ date('d-m-Y', strtotime($quote->expected_date)) }}</div>
            @else
                <div> - </div>
            @endif
        </div>
        <div class="col-md-4">
            <label>{{ __('rfqs.comment') }}:</label>
            <div>{{ $quote->rfq_comment ? $quote->rfq_comment : ' - ' }}</div>
        </div>
        <div class="col-md-4">
            <label>{{ __('rfqs.supplier_company_name') }}:</label>
            <div>
                @if($quote->supplier_profile_username)
                <a href="{{ route('supplier.professional.profile',getSettingValueByKey('slug_prefix').$quote->supplier_profile_username) }}" target="_blank" style="text-decoration: none;">{{ $quote->supplier_company_name }}</a>
                @else
                {{ $quote->supplier_company_name }}
                @endIf
            </div>
        </div>
        <div class="col-md-4">
            <label>{{ __('rfqs.supplier_name') }}:</label>
            <div>
                {{ $quote->supplier_name }}
            </div>
        </div>

        @if(isset($quote->termsconditions_file) && !empty($quote->termsconditions_file))
            <div class="col-md-4">
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
                    <label for="termsconditions_file">{{ __('admin.commercial_tc') }}</label>
                    <div>
                        <a href="{{ $quote->termsconditions_file? Storage::url($quote->termsconditions_file) :'javascript:void(0);' }}" class="text-decoration-none" target="_blank" title="{{$termsconditions_file_name }}">{{$termsconditions_file_name}}</a>

                    </div>
            </div>
        @endif
        <div class="col-md-4">
            <label class="d-block">{{ __('rfqs.payment_terms') }}:</label>
            @if($quote->payment_type==0)
                <div class="text-dark"><span class="badge rounded-pill bg-success">{{ __('admin.advance') }}</span></div>
            @elseif($quote->payment_type==1)
                <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.credit') }}</span></div>
            @elseif($quote->payment_type==3)
                <div class="text-dark"><span class="badge rounded-pill bg-danger">{{  __('admin.lc')  }}</span></div>
            @elseif($quote->payment_type==4)
                <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.skbdn') }}</span></div>
            @else
                <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.credit') }}</span></div>
            @endif
        </div>
        @if(isset($quote->group_id) && !empty($quote->group_id))
            <div class="col-md-4">
                <label>{{ __('rfqs.group_number') }}:</label>
                <div>
                    {{'BGRP-'.$quote->group_id}}
                </div>
            </div>
        @endif


    </div>
    <div class="card radius_1 my-3">
        @php
            $finalAmount = 0;
            $discount = 0;
            $flag = 0;
            $flagLogistic = 0;
            $flagSuppler = 0;
        @endphp
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th>{{ __('rfqs.description') }}</th>
                    <th width="20%" class="text-center text-nowrap">{{ __('rfqs.price') }}</th>
                    <th width="20%" class="text-center text-nowrap">{{ __('rfqs.QTY') }}</th>
                    <th width="20%" class="text-end text-nowrap">{{ __('rfqs.amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quote_items as $key => $value)
                @php
                    $finalAmount = $finalAmount + $value->product_amount;
                    $isLogisticChecked = $value->logistic_check;
                    $isLogisticProvided = $value->logistic_provided;
                @endphp
                    <tr>
                        <td>{{ get_product_name_by_id($value->rfq_product_id, 1) }}
                        </td>
                        <td class="text-nowrap text-center">{{ 'Rp ' . number_format($value->product_price_per_unit, 2) }} per {{ $value->name }}</td>
                        <td class="text-nowrap text-center">{{ $value->product_quantity??'' }} {{ $value->name??'' }}</td>
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
                                @if($isLogisticChecked==1 && $isLogisticProvided==0 && $charges->charge_type == 1) by JNE @endif
                                <small class="fw-bold text-blue"> ({{ $charges->charge_type == 1 ? __('admin.logistic_charges') : __('admin.supplier_other_charges') }})</small>
                                @endif
                            </td>

                         @else
                            @if($charges->charge_type == 1)
                                @php
                                    $flagLogistic = 1;
                                @endphp
                                <td colspan="3">@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif {{ $charges->charge_name }} @if($isLogisticChecked==1 && $isLogisticProvided==0 && $charges->charge_type == 1) by JNE @endif<small class="fw-bold text-blue">({{ __('admin.logistic_charges') }})</small></td>
                            @elseif($charges->charge_type == 2)
                                <td colspan="3">@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif {{ $charges->charge_name }}</td>

                            @else
                                @php
                                    $flagSuppler = 1;
                                @endphp
                                <td colspan="3">@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif {{ $charges->charge_name }} <small class="fw-bold text-blue">({{ __('admin.supplier_other_charges') }})</small></td>
                            @endif
                        @endif
                        <td class="text-end text-nowrap">
                            {{ $charges->addition_substraction == 0 ? '- ' : '+ ' }}{{ 'Rp ' . number_format($charges->charge_amount, 2) }}
                        </td>
                    </tr>
                    @endif

                @endforeach
                @if($quote->tax > 0 && $quote->tax_value > 0)
                <tr>
                    <td colspan="3">{{ 'Tax ' . $quote->tax }} %

                        <small class="fw-bold text-blue">({{ __('admin.product') }})</small>
                            @if( $quote->inclusive_tax_other==0 && $flagSuppler == 1 )
                               + <small class="fw-bold text-blue">({{ __('admin.supplier_other_charges') }})</small>
                            @endif
                            @if( $quote->inclusive_tax_logistic==0 && $flagLogistic == 1)
                                + <small class="fw-bold text-blue">({{ __('admin.logistic_charges') }})</small>
                            @endif

                            @if( $flag != 0 )
                                - <small class="fw-bold text-blue">({{ __('admin.discount') }})</small>
                            @endif
                    </td>
                    <td class="text-end text-nowrap">+ {{ 'Rp ' . number_format($quote->tax_value, 2) }}</td>
                </tr>
                @endif
                <tr class="bg-light">
                    <td colspan="3" class="fw-bold">Total</td>
                    <td class="text-end text-nowrap fw-bold">{{ 'Rp ' . number_format($quote->final_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
        <small class="p-2">{{ 'Deliver order in ' . $quote_items[0]->min_delivery_days . ' to ' . $quote_items[0]->max_delivery_days . ' Days' }}*</small>
    </div>
    <div class="row rfqform_view g-3">
        <div class="d-flex">
            <div class="col-md-8">
                <div class="col-md-12">
                    <label>{{ __('rfqs.note') }}:</label>
                    <div>{{ $quote->note ? $quote->note : ' - ' }}</div>
                </div>
                <div class="col-md-12">
                    <label>{{ __('rfqs.comment') }}:</label>
                    <div>{{ $quote->comment ? $quote->comment : ' - ' }}</div>
                </div>
                @if(isset($quote->certificate) && !empty($quote->certificate))
                    @php
                        $downloadProductCertificate = "downloadCertificate(".$quote->quotes_id.", 'attached_document','".$quote->quote_number."')";
                    @endphp
                <div class="col-md-12">
                    <label>{{ __('admin.product_certificate') }} :</label>
                    <div><a class="text-decoration-none btn btn-primary p-1"   href="javascript:void(0);" id="productCerificateFileDownload" onclick="{{$downloadProductCertificate}}"  class="text-decoration-none" title="{{ __('admin.attachment') }}" style="text-decoration: none;font-size: 12px;" >
                            <svg id="Layer_1" width="12px" fill="#fff" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg> {{ __('admin.attachment') }}</a>
                    </div>
                </div>
                @endif
            </div>

            @php
                if(isset($approversList) && $ConfigUsersCount > 0) {
                    $feedbackCount = $pendingFeedback = 0;
                    foreach($approversList as $list) {
                        $feedbackCount += ($list->feedback != 0);
                        $pendingFeedback += ($list->feedback == 0);
                    }
                } elseif(isset($approversList) && $ConfigUsersCount == 0) {
                    $feedbackCount = 0;
                } else {
                    $feedbackCount = 0;
                }

            @endphp

            <!-- User can see Approver's feedback here -->
            @if($toggleSwitch['approval_process'] == 1 && $quote->status_id <= 2)
            <div class="col-md-4">
                @if(isset($approversList) && $ConfigUsersCount > 0)
                <table class="table table-bordered {{ ($feedbackCount != '' || $pendingFeedback != ''  ) ? '' : 'd-none'  }}">
                    <thead style="background-color: #d7d9df;">
                        <tr style="font-size: 12px;">
                            <th class="text-center" colspan="2">{{ __('rfqs.reviewd_by') }} </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($approversList as $approver)
                        <tr>
                            <td width="90%">
                                <div style="font-size: 10px;"><label>{{ $approver->firstname . ' ' . $approver->lastname }}</label> {{ isset($approver->name)? '('. $approver->name.')' : " " }}</div>
                            </td>
                            @php
                                if(isset($approver->feedback) && $approver->feedback == 0) {
                                    $feedback = URL::asset('front-assets/images/pending.png');
                                } else if ($approver->feedback == 1) {
                                    $feedback = URL::asset('front-assets/images/thumbs-up.png');
                                } else if ($approver->feedback == 2) {
                                    $feedback = URL::asset('front-assets/images/thumbs-down.png');
                                }
                            @endphp
                            <td width="10%">
                                <div><img src="{{ $feedback }}" height="14" width="14" alt="Thumbs Up" srcset=""></div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

@if($quote->status_id == 1)
    @if(!empty($isAuthUser) && $isAuthUser == 1)
        <div class="modal-footer">
            <a href="javascript:void(0);" id="placeOrderBtn" data-id="{{ $quote->quotes_id }}" class="btn btn-primary showPlaceOrderModal">
                <img src="{{ URL::asset('front-assets/images/icons/icon_placeorder.png') }}" alt="{{ __('rfqs.place_order') }}" class="pe-1">{{ __('rfqs.place_order') }}
            </a>
        </div>
    @else

        @if($toggleSwitch['approval_process'] == 1)

            @if($ConfigUsersCount == 0)
                <div class="modal-footer">
                    <img class="hidden" id="loader{{ $quote->quotes_id }}" height="16px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading">
                    <button type="button" data-id="{{ $quote->quotes_id }}" class="btn btn-primary pe-1 sendQuoteApprovalBtn" alt="Get Approval" >{{ __('rfqs.get_approval') }}</button>
                </div>
            @else
                @if($ConfigUsersCount == $feedbackCount)

                    @if (!$quote->orderPlaced && $quote->status_id == 1)

                        @can('create buyer orders'))
                            <div class="modal-footer">
                                <a href="javascript:void(0);" id="placeOrderBtn" data-id="{{ $quote->quotes_id }}" class="btn btn-primary showPlaceOrderModal">
                                    <img src="{{ URL::asset('front-assets/images/icons/icon_placeorder.png') }}" alt="{{ __('rfqs.place_order') }}" class="pe-1">{{ __('rfqs.place_order') }}
                                </a>
                            </div>
                        @endcan

                    @endif

                @else
                    @if($feedbackCount == 0 && $pendingFeedback == 0)

                        <div class="modal-footer">
                            <img class="hidden" id="loader{{ $quote->quotes_id }}" height="16px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading">
                            <button type="button" data-id="{{ $quote->quotes_id }}" class="btn btn-primary pe-1 sendQuoteApprovalBtn" alt="Get Approval" >{{ __('rfqs.get_approval') }}</button>
                        </div>

                    @else

                        <div class="modal-footer">
                            <button class="btn btn-primary pe-1" alt="Waiting For Approval" disabled>{{ __('rfqs.approval_waiting') }}</button>
                        </div>

                    @endif

                @endif

            @endif

        @else
            @can('create buyer orders')
                <div class="modal-footer">
                    <a href="javascript:void(0);" id="placeOrderBtn" data-id="{{ $quote->quotes_id }}" class="btn btn-primary showPlaceOrderModal">
                        <img src="{{ URL::asset('front-assets/images/icons/icon_placeorder.png') }}" alt="{{ __('rfqs.place_order') }}" class="pe-1">{{ __('rfqs.place_order') }}
                    </a>
                </div>
            @endcan
        @endif

    @endif
@endif
