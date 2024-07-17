@if (count($rfq->quotes))
<div class="accordion subaccordion_section" id="subaccordion">
    @foreach ($rfq->quotes as $quote)
    @php

        $isOrderPlaced = in_array(1, array_column($quote->toArray(), 'orderPlaced'));
        $quoteId =  $quote->quotes_id;
        $getQuoteProducts = $quote->rfqIdArray;
    @endphp
    <div class="accordion-item">
        <h2 class="accordion-header" id="sub_heading{{ $quote->quotes_id }}">
            <button class="accordion-button collapsed" id="showQuoteData{{ $quote->quotes_id }}" type="button" data-bs-toggle="collapse" data-bs-target="#sub_collapse{{ $quote->quotes_id }}" aria-expanded="false" aria-controls="sub_collapse{{ $quote->quotes_id }}" onclick="changeBtnName({{$quoteId}})">
                <div class="d-flex w-100">
                    <div class="flex-grow-1"> {{ __('rfqs.quote_number') }}: {{ $quote->quote_number }}
                        @if($quote->quote_status_name)
                        <!-- <span class="badge rounded-pill mt-1 {{ ($quote->quote_status_name == 'Quotation Received' ? 'bg-primary' : ($quote->quote_status_name == 'Quotation Accepted' ? 'bg-success' : ($quote->quote_status_name == 'Quotation Expired' ? 'purpole_color' : ($quote->quote_status_name == 'Quotation Rejected' ? 'quote_expire_color' : '')))) }} ">{{ $quote->quote_status_name  }}</span> -->

                        @if($quote->quote_status_name == 'Quotation Received')
                            <span class="badge rounded-pill mt-1 bg-primary">{{ __('rfqs.'.$quote->quote_status_name) }}</span>
                        @elseif ($quote->quote_status_name == 'Quotation Accepted')
                            <span class="badge rounded-pill mt-1 bg-success">{{ __('rfqs.'.$quote->quote_status_name) }}</span>
                        @elseif ($quote->quote_status_name == 'Quotation Expired')
                            <span class="badge rounded-pill mt-1 purpole_color">{{ __('rfqs.'.$quote->quote_status_name) }}</span>
                        @elseif ($quote->quote_status_name == 'Quotation Rejected')
                            <span class="badge rounded-pill mt-1 quote_expire_color">{{ __('rfqs.'.$quote->quote_status_name) }}</span>
                        @else
                            <span class="badge rounded-pill mt-1">{{ __('rfqs.'.$quote->quote_status_name) }}</span>
                        @endif

                        @endif
                    </div>
                     <div class="ms-auto">{{ __('dashboard.group_supplier_name') }}:
                        {{ $quote->supplier_company_name }} @if($quote->logistic_check == 1 && $quote->logistic_provided == 0) {{ __('rfqs.by_jne') }} @endif
                    </div>
                    <div class="ms-auto">{{ __('rfqs.total_price') }}:
                        {{ 'Rp ' . number_format($quote->final_amount, 2) }}
                    </div>
                    <div class="ms-auto px-3">{{ __('rfqs.date') }}:
                        {{ date('d-m-Y H:i', strtotime($quote->created_at)) }}
                    </div>
                </div>
            </button>
        </h2>
        <div id="sub_collapse{{ $quote->quotes_id }}" class="accordion-collapse collapse" aria-labelledby="sub_heading{{ $quote->quotes_id }}" data-bs-parent="#subaccordion">
            <div class="accordion-body p-2">

                <!-- Approval process toggle value -->
                <input type="hidden" name="appProcessValue" id="appProcess" value="{{ $rfq->processValue['approval_process'] }}" />
                <input type="hidden" name="progressPercentage" id="progressPercentage{{ $quote->quotes_id }}" value="" />
                <input type="hidden" name="quoteIdValue" id="quoteIdValue" value="" />
                <input type="hidden" name="quoteIdValue" id="quoteStatus" value="{{ $quote->status_id }}" />
                <input type="hidden" name="loginUserId" id="loginUserId" value="{{auth()->user()->id}}" />
                <input type="hidden" id="configUsersCount" value="{{ $rfq->countConfigUsers }}">
                <input type="hidden" id="isAuthUser" value="{{ $rfq->isAuthUser == 'true' ? 1 : 0 }}">

                <div class="row">
                    <div class="col-md-12">
                        <div class="row rfqform_view g-2">
                            <div class="col-md-4">
                                <div class="bg-white p-2 border-light">
                                    <label>{{ __('rfqs.quote_number') }}:</label>
                                    <div>{{ $quote->quote_number }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-white p-2 border-light">
                                    <label>{{ __('rfqs.date') }}:</label>
                                    <div>{{ date('d-m-Y H:i', strtotime($quote->created_at)) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-white p-2 border-light">
                                    <label>{{ __('rfqs.rfq_number') }}: </label>
                                    <div>{{ $quote->rfq_reference_number }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-white p-2 border-light">
                                    <label>{{ __('rfqs.valid_till') }}:</label>
                                    <div>{{ date('d-m-Y', strtotime($quote->valid_till)) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-white p-2 border-light">
                                    <label>{{ __('rfqs.total_price') }}:</label>
                                    <div>{{ 'Rp ' . number_format($quote->final_amount, 2) }} <small>(including
                                            all taxes)</small></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-white p-2 border-light">
                                    <label>{{ __('rfqs.status') }}:</label>
                                    <div>{{ $quote->quote_status_name  }} </div>
                                </div>
                            </div>
                            <div class="col-md-4 pb-2">
                                <div class="bg-white p-2 border-light">
                                    <label>{{ __('rfqs.product') }}:</label>
                                    <div class="multipleproducthover">
                                        <div href="javascript:voic(0);" class="tooltiphtml w-auto">
                                            <span class="text-primary"> {{count($getQuoteProducts)}} Products</span>
                                            <div class="tooltip_section text-dark text-start p-1 multi_pro_list">
                                                <table class="table mb-0 border">
                                                    <thead>
                                                    <tr>
                                                        <th>{{ __('rfqs.product') }}</th>
                                                        <th>{{ __('rfqs.product_description') }}</th>
                                                        <th class="text-end">{{ __('rfqs.quantity') }}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($rfq->all_products as $key => $value)
                                                        @php
                                                            $class = '';

                                                            if(!in_array($value->product_id, array_column($getQuoteProducts, 'rfq_product_id'))){
                                                                $class = 'class=opacity-25';
                                                            }
                                                        @endphp
                                                        <tr {{$class}}>
                                                            <td>{{ $value->category . ' - ' . $value->sub_category . ' - ' . $value->product }}</td>
                                                            <td>{{ $value->product_description }}</td>
                                                            <td class="text-end text-nowrap"> {{ $value->quantity }} {{ $value->unit_name }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Show Quote rejected results (Ronak M - 05-01-2023) -->
                            @if(isset($rfq->approvalRejectReason) && (!$rfq->approvalRejectReason->where('quote_id',$quote->quotes_id)->isEmpty()))
                                <div class="col-md-4 pb-2">
                                    <div class="bg-white p-2 border-light">
                                        <label for="reason_for_reject">{{ __('rfqs.quote_rejection_reason') }} :</label>
                                        <div class="multipleproducthover">
                                            <div href="javascript:voic(0);" class="tooltiphtml w-auto">
                                                <span class="text-primary">{{ count($rfq->approvalRejectReason->where('quote_id',$quote->quotes_id)) }} {{ __('profile.feedbacks') }}</span>
                                                <div class="tooltip_section text-dark text-start p-1 multi_pro_list">
                                                    <table class="table mb-0 border">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('profile.approver') }}</th>
                                                                <th>{{ __('admin.feedback') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($rfq->approvalRejectReason->where('quote_id',$quote->quotes_id) as $approvalReject)
                                                                <tr>
                                                                    <td style="max-width: 150px;">{{ getUserName($approvalReject->approval_person_id) }}</td>
                                                                    <td class="text-wrap">{{ isset($approvalReject->reason_text) ? $approvalReject->reason_text : ''  }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <!-- End -->
                            <div class="col-md-4">
                                <div class="bg-white p-2 border-light">
                                    <label>{{ __('rfqs.supplier_company_name') }}:</label>
                                    <div>
                                        @if($quote->supplier_profile_username)
                                            <a href="{{ route('supplier.professional.profile',$rfq->setting.$quote->supplier_profile_username) }}" target="_blank" style="text-decoration: none;">{{ $quote->supplier_company_name }}</a>
                                        @else
                                        {{ $quote->supplier_company_name }}
                                        @endIf
                                    </div>
                                </div>
                            </div>
                            @if(isset($quote->termsconditions_file) && !empty($quote->termsconditions_file))
                            <div class="col-md-4 pb-2">
                                <div class="bg-white p-2 border-light">
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
                                        <a href="{{ $quote->termsconditions_file? Storage::url($quote->termsconditions_file) :'javascript:void(0);' }}" class="text-decoration-none" target="_blank" title="{{$termsconditions_file_name}}">{{$termsconditions_file_name}}</a>
                                    </div>
                                </div>
                                <!-- </div> -->
                            </div>
                            @endif
                            @if(isset($quote->certificate_attachment) && !empty($quote->certificate_attachment))
                            <div class="col-md-4 pb-2">
                                <div class="bg-white p-2 border-light">
                                    @php
                                    $downloadProductCertificate = "downloadCertificate(".$quote->quotes_id.", 'attached_document','".$quote->quote_number."')";
                                    @endphp
                                    <label for="product_certificate">{{ __('admin.product_certificate') }} :</label>
                                    <div>
                                        <a class="text-decoration-none btn btn-primary p-1"   href="javascript:void(0);" id="productCerificateFileDownload" onclick="{{$downloadProductCertificate}}"  class="text-decoration-none" title="{{ __('admin.attachment') }}" style="text-decoration: none;font-size: 12px;" >
                                            <svg id="Layer_1" width="12px" fill="#fff" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg> {{ __('admin.attachment') }}</a>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Preferred Supplier Section (Ronak M - 04/07/2022)-->
                            @if(in_array($quote->supplier_id, $rfq->allSuppliersIds->toArray()))
                            <div class="col-md-4 ms-auto text-end" id="preferredSupplierDiv">
                                <span class="preffered-trusted badge bg-white border border-success text-success py-0 px-1">
                                    <img src="{{ URL::asset('front-assets/images/preferred_supp.png') }}" alt="" height="16px" class="mx-1 my-1">{{ __('dashboard.trusted_suppliers_label')}}
                                </span>
                            </div>
                            @else
                            <div class="col-md-4 ms-auto" id="noPreferredSupplierDiv">
                                <div class="preffered-trusted text-end">
                                    <input type="checkbox" class="form-check-input me-2" id="addPreferredSupplierChk" value="1" data-supplier-id="{{ $quote->supplier_id }}" data-quote-id="{{ $quote->quotes_id }}" data-rfq-id="{{ $quote->rfqId }}">
                                    <label class="form-check-label bg-transparent fs-6 text-dark" for="addPreferredSupplierChk"> {{ __('dashboard.no_trusted_suppliers')}} </label>
                                </div>
                            </div>
                            @endif
                            <!-- End -->
                        </div>
                        <div class="row mt-2 pe-2 pb-2">

                            <div class="col-md-12 d-flex ">
                                {{-- Set chat icon --}}
                                <div class="ps-2">
                                    @php
                                        $chatData = getChatDataForRfqById($quote->quotes_id, 'Quote');
                                    @endphp
                                    <a href="javascript:void(0)" onclick="chat.chatRfqViewData('{{ route('new-chat-create-view')  }}', '{{$chatData['group_chat_id']??""}}','Quote','{{$quote->quote_number}}', '{{ $quote->quotes_id??'' }}',$(this), 1)" data-id="{{ $quote->quotes_id }}" class=" btn px-3 py-1" style="font-size: 12px;background-color: #B7CFFF;">
                                        <img src="{{ URL::asset('front-assets/images/icons/chat_icon.png') }}"  style="max-height: 14px;" alt="View" title="{{ __('admin.chat')}}" class="pe-1"> @if(!empty($chatData) && $chatData['unread_message_count'] != 0)<span class="bg-warning text-black px-1 ms-1 fw-bold rounded" style="font-size: 10px;">{{ $chatData['unread_message_count'] }}</span>@endif
                                    </a>

                                    @if(!empty($chatData))
                                    <a class="d-none" href="javascript:void(0)" onclick="chat.chatQuoteHistoryData('{{ route('get-chat-quote-history-ajax')  }}', '{{$chatData['group_chat_id']??""}}','Quote','{{$quote->quote_number}}', '{{ $quote->quotes_id??'' }}',$(this))" data-id="{{ $quote->quotes_id }}" class=" btn px-3 py-1" style="font-size: 12px;background-color: #B7CFFF;">

                                        <img src="{{ URL::asset('front-assets/images/icons/chat_icon.png') }}"  style="max-height: 14px;" alt="View" class="pe-1"> Chat History
                                    </a>
                                    @endif
                                </div>
                                {{-- end chat icon--}}
                                <!-- Progress Bar Design -->
                                <div class="ms-auto myDIV d-flex align-items-center position-relative" id="myDIV{{ $quote->quotes_id }}" style="visibility: none;">
                                    <div class=" d-flex pt-1" style=" border-radius: 20px;">
                                        <div class="hide card progresscard shadow" style="position: absolute; min-width: 240px;">
                                            <div class="card-header card-header-1" style=" background-color: #1f1a5c; color: #fff;">
                                                {{ __('profile.approval_process') }}
                                            </div>
                                            <div class="card-body p-0 bg-white">
                                                <div class="row m-0 p-0 feedbackContent" id="feedbackContent{{ $quote->quotes_id }}">
                                                    <!-- User email and feedback will be show here using ajax -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress" id="progress{{ $quote->quotes_id }}" style="display: none;">
                                            <div class="progress-bar bg-success" id="yesbar{{ $quote->quotes_id }}" role="progressbar" aria-valuemin="0" aria-valuemax="100"><span id="yesProgressCount{{ $quote->quotes_id }}"></span></div>

                                            <div class="progress-bar bg-danger" id="nobar{{ $quote->quotes_id }}" role="progressbar" aria-valuemin="0" aria-valuemax="100"><span id="noProgressCount{{ $quote->quotes_id }}"></span></div>
                                        </div>
                                    </div>
                                    <div class="ps-3 pe-1 align-items-center UserCount" id="UserCount{{ $quote->quotes_id }}" style="display: none;">
                                        <span class="text-muted" style="font-size: 13px;"><span id="ApprovedUsers{{ $quote->quotes_id }}"></span>/<span id="totalNo{{ $quote->quotes_id }}"></span>
                                            <img src="i_3.png" class="ps-1 d-none" height="25px" width="28px" alt="" srcset="">
                                        </span>
                                    </div>

                                    <!-- Place Order BUtton -->
                                    @can('create buyer orders')
                                        <div class="placeOrderBtnDiv" id="placeOrderBtnDiv{{ $quote->quotes_id }}">
                                            @if (!$isOrderPlaced)
                                                @if($quote->quoteStatus->id == 1)
                                                    <img class="hidden" id="orderplaceloader{{ $quote->quotes_id }}" height="18px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading">&nbsp;
                                                <a href="javascript:void(0)" data-id="{{ $quote->quotes_id }}" class="btn btn-primary px-3 py-1 showPlaceOrderModal showPlaceOrderModal{{ $quote->quotes_id }}" data-bs-toggle="modal" data-bs-target="#placeorder"><img src="{{ URL::asset('front-assets/images/icons/icon_placeorder.png') }}" alt="Place order" class="pe-1">
                                                    {{ __('rfqs.place_order') }}</a>
                                                @endif
                                            @endif
                                        </div>
                                    @endcan
                                    <!-- End -->
                                </div>
                                <!-- Progess Bar end -->

                                @if(!$isOrderPlaced)
                                    @if($quote->quoteStatus->id == 1)
                                    <img class="hidden" id="loader{{ $quote->quotes_id }}" height="18px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading">&nbsp;

                                    <!-- Get Approval Button -->
                                    <div class="getApprovalBtnDiv pe-1" id="getApprovalBtnDiv{{ $quote->quotes_id }}">
                                        <button type="button" data-id="{{ $quote->quotes_id }}" id="sendQuoteApprovalBtn{{ $quote->quotes_id }}" class="btn btn-primary px-3 py-1 sendQuoteApprovalBtn" alt="{{ __('rfqs.get_approval') }}">{{ __('rfqs.get_approval') }}</button>
                                    </div>
                                    <!-- End -->

                                    <!-- Waiting for approval Button -->
                                    <!-- @php $display = (isset($appProcess) && $appProcess->quote_id == $quote->quotes_id) ? "block" : "none"; @endphp -->
                                    <div class="approvalWaitingBtn pe-1" id="approvalWaitingBtn{{ $quote->quotes_id }}" style="display: {{ $display }}">
                                        <button type="button" data-id="{{ $quote->quotes_id }}" id="appWaitingBtn" class="btn btn-primary px-3 py-1" alt="Waiting For Approval" disabled>{{ __('rfqs.approval_waiting') }}</button>
                                        </a>
                                    </div>
                                    <!-- END -->
                                    @endif
                                @endif

                                <div class="pe-1">
                                    <a href="javascript:void(0)" data-id="{{ $quote->quotes_id }}" class="showQuoteModal btn btn-info px-3 py-1">
                                        <img src="{{ URL::asset('front-assets/images/icons/eye.png') }}" alt="View" class="pe-1"> {{ __('rfqs.view') }}
                                    </a>
                                </div>

                                <div class="pe-1">
                                    <a href="{{ route('dashboard-get-rfq-quote-print',Crypt::encrypt($quote->quotes_id)) }}" target="_blank" class="btn btn-info btn_print_color px-3 py-1"><img src="{{ URL::asset('front-assets/images/icons/icon_print.png') }}" alt="Print" class="pe-1"> {{__('dashboard.print_icon')}}
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!-- </div> -->
    </div>
    @endforeach
</div>
@endif
<script type="text/javascript">
    $(document).ready(function() {
        getapprovalProcessValue();
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>