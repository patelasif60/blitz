<div>

    @if(isset($rfqWithQuoteApproval) && count($rfqWithQuoteApproval) > 0)

        <!-- New Design by Aashish Bhai -->
        <div class="mb-3 d-flex">
            <div style="min-width: 150px;">

                <select class="form-select mt-2 py-2" name="approvalQuoteStatus" id="approvalQuoteStatus">
                    <option value="all">{{ __('rfqs.all') }}</option>
                    <option value="1">{{ __('profile.approved') }}</option>
                    <option value="2">{{ __('profile.rejected') }}</option>
                    <option value="3">{{ __('profile.pending') }}</option>
                </select>
            </div>

            <div class="ms-auto">
                <div class="d-flex align-items-center ">
                @if(count($rfqWithQuoteApproval) > 0)
                    <small class="text-nowrap me-2 showSearchResult">{{ count($rfqWithQuoteApproval) }} {{ __('rfqs.result_found') }}</small>
                @endif
                    <div id="grouptrading_search" class="input-group mb-0">
                        <input class="form-control form-control-sm" type="search" name="approvalCustomSearch" id="approvalCustomSearch" placeholder="{{ __('admin.search') }}" aria-label="{{ __('admin.search') }}">
                        <button class="btn bg-light text-white btn-sm search border approvalSearch" type="button">
                            <img src="{{ URL::asset('assets/images/icon_search_b.png') }}" alt="{{ __('admin.search') }}">
                        </button>
                    </div>
                </div>
                <div class="text-end">
                    <small class="text-danger" id="searchTextErr"></small>
                </div>
            </div>
        </div>
        <!-- End -->

        @foreach ($rfqWithQuoteApproval as $key => $feedbacks)

            <div class="accordion-item radius_1 mb-2">

                <h2 class="accordion-header" id="headingOne">

                    <button id="rfqupdatecollpse{{ $feedbacks->first()->rfqs->id }}" class="accordion-button justify-content-between rfqUpdateCollpse collapsed Rfq_Id{{ $feedbacks->first()->rfqs->id }}" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $feedbacks->first()->rfqs->id }}" aria-expanded="false" aria-controls="collapse{{ $feedbacks->first()->rfqs->id }}" data-id="{{ $feedbacks->first()->rfqs->id }}">

                        <div class="flex-grow-1 ">{{ __('rfqs.rfq_no') . ' ' . $feedbacks->first()->rfqs->reference_number }} <br>
                            @php
                                $badgeColor = $feedbacks->first()->rfqs->rfqStatus->name == 'RFQ Completed' ? 'bg-success': ($feedbacks->first()->rfqs->rfqStatus->name == 'RFQ Cancelled' ? 'bg-danger' : 'bg-primary');
                            @endphp

                            <span id="badgeColorSpan{{ $feedbacks->first()->rfqs->id }}" class="badge rounded-pill  mt-1 {{ $badgeColor }}">{{ __('rfqs.' . $feedbacks->first()->rfqs->rfqStatus->name) }}</span>
                        </div>

                        @if (isset($feedbacks) && !empty($feedbacks))
                            @php $approvedCount = $rejectCount = $pendingCount= 0 @endphp
                            <div class="font_sub px-3 datesection me-lg-3"><span class="mb-1 text-dark fw-bold text-center">{{$feedbacks->count()}} {{ __('rfqs.quote') }}</span>
                            @foreach ($feedbacks as $quote)
                                    @php
                                        $approvedCount += $quote->quotes->getUserQuoteFeedback->where('is_deleted',0)->where('user_id',$auth->id)->where('quote_id',$quote->quotes->id)->where('feedback',1)->count();
                                        $rejectCount += $quote->quotes->getUserQuoteFeedback->where('is_deleted',0)->where('user_id',$auth->id)->where('quote_id',$quote->quotes->id)->where('feedback',2)->count();
                                        $pendingCount += $quote->quotes->getUserQuoteFeedback->where('is_deleted',0)->where('user_id',$auth->id)->where('quote_id',$quote->quotes->id)->where('feedback',0)->count();
                                    @endphp
                            @endforeach
                                <span class="badge rounded-pill mt-1 bg-white border border-outline-success text-success d-inline-block fs12">{{ $approvedCount }} {{ __('profile.approved') }}</span>&nbsp;
                                <span class="badge rounded-pill mt-1 bg-white border border-outline-danger text-danger d-inline-block fs12">{{ $rejectCount}} {{ __('profile.rejected') }}</span>&nbsp;
                                <span class="badge rounded-pill mt-1 bg-white border border-outline-primary text-primary d-inline-block fs12">{{$pendingCount}} {{ __('profile.pending') }}</span>
                            </div>
                        @endif


                        @if(isset($feedbacks->first()->rfqs->groupName) && !empty($feedbacks->first()->rfqs->groupName))
                            <div>
                                <span class="badge rounded-pill alert-success align-middle fw-bold fs12" >{{ 'BGRP-' . $feedbacks->first()->rfqs->groupId . ' : ' . $feedbacks->first()->rfqs->groupName }}</span>
                            </div>
                        @endif

                        <div class="font_sub px-3 datesection me-lg-5">
                            <span class="mb-1">{{ __('rfqs.date') }}:</span>
                            {{ date('d-m-Y H:i', strtotime($feedbacks->first()->rfqs->created_at)) }}
                        </div>

                    </button>
                </h2>

                <div id="collapse{{ $feedbacks->first()->rfqs->id }}" data-id="{{ $feedbacks->first()->rfqs->id }}" data-type="rfq" class="accordion-collapse collapse rfqCollapse rfqSub_collapse{{ $feedbacks->first()->rfqs->id }}" aria-labelledby="headingOne" data-bs-parent="#Rfq_accordian">

                    <div class="accordion-body p-0">
                        <div class="container-fluid">
                            <div class="row rfqform_view py-3">
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('rfqs.customer_name') }}: </label>
                                    <div>{{ $feedbacks->first()->rfqs->firstname . ' ' . $feedbacks->first()->rfqs->lastname }}</div>
                                </div>
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('rfqs.created_by') }}:</label>
                                    <div class="text-primary">{{ !empty($feedbacks->first()->rfqs->rfqUser) ? getUserName($feedbacks->first()->rfqs->rfqUser->id) : '-'}} </div>
                                </div>
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('rfqs.product') }}:</label>
                                    <div class="multipleproducthover">
                                        <div href="javascript:voic(0);" class="tooltiphtml w-auto">

                                            <span class="text-primary">{{  $feedbacks->first()->rfqs->rfqProducts->count() }} {{ __('rfqs.products') }}</span>
                                            <div class="tooltip_section text-dark text-start p-1 multi_pro_list">
                                                <table class="table mb-0 border">
                                                    <thead>
                                                    <tr>
                                                        <th>{{ __('rfqs.name') }}</th>
                                                        <th>{{ __('rfqs.description') }}</th>
                                                        <th class="text-end">{{ __('rfqs.QTY') }}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($feedbacks->first()->rfqs->rfqProducts as $key => $value)
                                                        <tr>
                                                            <td>{{ $value->category . ' - ' . $value->sub_category . ' - ' . $value->product }}</td>
                                                            <td>{{ $value->product_description }}</td>
                                                            <td class="text-end text-nowrap">{{ $value->quantity }} {{ $value->unit->name }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('rfqs.expected_delivery_date') }}:</label>
                                    @if ($feedbacks->first()->rfqs->rfqProducts[0]->expected_date)
                                        <div> {{ date('d-m-Y', strtotime($feedbacks->first()->rfqs->rfqProducts[0]->expected_date)) }}</div>
                                    @else
                                        <div> - </div>
                                    @endif
                                </div>
                                <div class="col-md-6 pb-2">
                                    <label class="d-block">{{ __('rfqs.payment_terms') }}:</label>
                                    @if($feedbacks->first()->rfqs->payment_type==1)
                                        <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.credit') }} - {{$feedbacks->first()->rfqs->credit_days}}</span></div>
                                    @elseif($feedbacks->first()->rfqs->payment_type==0)
                                        <div class="text-dark"><span class="badge rounded-pill bg-success">{{ __('admin.advance') }}</span></div>
                                    @elseif($feedbacks->first()->rfqs->payment_type==3)
                                        <div class="text-dark"><span class="badge rounded-pill bg-danger">{{  __('admin.lc')  }}</span></div>
                                    @elseif($feedbacks->first()->rfqs->payment_type==4)
                                        <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.skbdn') }}</span></div>
                                    @else
                                        <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.credit') }}</span></div>
                                    @endif
                                </div>
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('rfqs.comment') }}:</label>
                                        <div>{{ $feedbacks->first()->rfqs->rfqProducts->first()->comment ? $feedbacks->first()->rfqs->rfqProducts->first()->comment : ' - ' }}</div>

                                </div>
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('rfqs.address') }}:</label>
                                    <div>
                                        {{ $feedbacks->first()->rfqs->address_line_1?($feedbacks->first()->rfqs->address_line_1.','):'' }} {{ $feedbacks->first()->rfqs->address_line_2?($feedbacks->first()->rfqs->address_line_2.','):'' }} {{ $feedbacks->first()->rfqs->sub_district?($feedbacks->first()->rfqs->sub_district.','):'' }} {{ $feedbacks->first()->rfqs->district?($feedbacks->first()->rfqs->district.','):'' }}
                                        {!! $feedbacks->first()->rfqs->city_id > 0 ? ("<br />".getCityName($feedbacks->first()->rfqs->city_id).',') : ("<br />".$feedbacks->first()->rfqs->city.',') !!}
                                        {!! $feedbacks->first()->rfqs->state_id > 0 ? ("<br />".getStateName($feedbacks->first()->rfqs->state_id).',') : ("<br />".$feedbacks->first()->rfqs->state.',') !!}
                                        {{ $feedbacks->first()->rfqs->pincode }}
                                    </div>
                                </div>
                                @if ($feedbacks->first()->rfqs->unloading_services || $feedbacks->first()->rfqs->rental_forklift)
                                    <div class="col-md-6 pb-2">
                                        <label>{{ __('rfqs.other_options') }}:</label>
                                        @if ($feedbacks->first()->rfqs->unloading_services)
                                            <br><span>{{ __('dashboard.need_unloading_services') }}</span>
                                        @endif
                                        @if ($feedbacks->first()->rfqs->rental_forklift)
                                            <br><span>{{ __('dashboard.need_rental_forklift') }}</span>
                                        @endif
                                    </div>
                                @endif
                                @if(isset($feedbacks->first()->rfqs->rfqAttachment) && $feedbacks->first()->rfqs->rfqAttachment->count() >=1)

                                    <div class="col-md-6 pb-2">
                                        <label>{{ __('rfqs.rfq_attachment') }}:</label>
                                        @php
                                            if($feedbacks->first()->rfqs->rfqAttachment->count() > 1){
                                                $downloadAttachment = "downloadAttachment(".$feedbacks->first()->rfqs->id.", 'attached_document','".$feedbacks->first()->rfqs->reference_number."')";
                                            }else{
                                                $rfqFileTitle = Str::substr($feedbacks->first()->rfqs->rfqAttachment[0]->attached_document,43);
                                                $extension_rfq_file = getFileExtension($rfqFileTitle);
                                                $rfq_file_filename = getFileName($rfqFileTitle);
                                                if(strlen($rfq_file_filename) > 10){
                                                    $rfq_file_name = substr($rfq_file_filename,0,10).'...'.$extension_rfq_file;
                                                } else {
                                                    $rfq_file_name = $rfq_file_filename.$extension_rfq_file;
                                                }
                                                $downloadAttachment = "downloadimg(".$feedbacks->first()->rfqs->rfqAttachment[0]->id.", 'attached_document', '".$rfq_file_name."')";
                                            }
                                        @endphp
                                        <div>
                                            <a class="text-decoration-none btn btn-primary p-1" href="javascript:void(0);"  data-id="{{ $feedbacks->first()->rfqs->id }}" id="RfqFileDownload" onclick="{{$downloadAttachment}}"  title="{{ __('rfqs.rfq_attachment') }}" style="text-decoration: none;font-size: 12px;"> <svg id="Layer_1" width="12px" fill="#fff" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg> {{ __('rfqs.rfq_attachment') }}</a>
                                        </div>
                                    </div>
                                @endif
                                @if(isset($feedbacks->first()->rfqs->termsconditions_file) && !empty($feedbacks->first()->rfqs->termsconditions_file))
                                    <div class="col-md-6 pb-2">
                                        @php
                                            $termsconditionsFileTitle = Str::substr($feedbacks->first()->rfqs->termsconditions_file,stripos($feedbacks->first()->rfqs->termsconditions_file, "termsconditions_file_") + 21);
                                            $extension_termsconditions_file = getFileExtension($termsconditionsFileTitle);
                                            $termsconditions_file_filename = getFileName($termsconditionsFileTitle);
                                            if(strlen($termsconditions_file_filename) > 10){
                                                $termsconditions_file_name = substr($termsconditions_file_filename,0,13).$extension_termsconditions_file;
                                            } else {
                                                $termsconditions_file_name = $termsconditions_file_filename.$extension_termsconditions_file;
                                            }
                                        @endphp
                                        <label for="termsconditions_file">{{ __('admin.commercial_terms') }}</label>
                                        <div>
                                            <a href="{{ $feedbacks->first()->rfqs->termsconditions_file? Storage::url($feedbacks->first()->rfqs->termsconditions_file) :'javascript:void(0);' }}" class="text-decoration-none" target="_blank" title="{{$termsconditions_file_name}}">{{$termsconditions_file_name}}</a></div>
                                    </div>
                                @endif
                                @if(isset($feedbacks->first()->rfqs->group) && !empty($feedbacks->first()->rfqs->group))
                                    <div class="col-md-6">
                                        <label>{{ __('admin.group_name') }}:</label>
                                        <div class="text-dark"><a href="{{ route('group-details',['id' => Crypt::encrypt($feedbacks->first()->rfqs->group_id)]) }}" target="_blank" class="text-decoration-none">{{ $feedbacks->first()->rfqs->group->name }}</a></div>
                                    </div>
                                @endif

                                <div class="col-md-12 d-flex pb-2">
                                    @php
                                        $chatData = getChatDataForRfqById($feedbacks->first()->rfqs->id, 'Rfq');
                                    @endphp
                                    @if($feedbacks->count() > 1 )
                                        <div class="ps-2">
                                            <div class="pe-1 ms-auto">
                                                @endif
                                                <a href="javascript:void(0)" onclick="chat.chatRfqViewData('{{ route('new-chat-create-view')  }}', '{{$chatData['group_chat_id']??""}}','Rfq','{{$feedbacks->first()->rfqs->reference_number}}', '{{ $feedbacks->first()->rfqs->id??'' }}',$(this), 1, '{{ $feedbacks->first()->rfqs->company_id??0 }}')" data-id="{{ $feedbacks->first()->rfqs->id }}" data-id="{{ $feedbacks->first()->rfqs->id }}" class=" btn px-3 py-1" style="font-size: 12px;background-color: #B7CFFF;">
                                                    <img src="{{ URL::asset('front-assets/images/icons/chat_icon.png') }}"  style="max-height: 14px;" alt="View" title="{{ __('admin.chat')}}" class="pe-1"> @if(!empty($chatData) && $chatData['unread_message_count'] != 0)<span class="bg-warning text-black px-1 ms-1 fw-bold rounded" style="font-size: 10px;">{{ $chatData['unread_message_count'] }}</span>@endif

                                                </a>
                                                @if($feedbacks->count() > 1 )
                                            </div>
                                        </div>
                                        <div class="border rounded-2 ps-2 pe-2 me-2 ms-auto">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input mt-2" type="checkbox"
                                                    role="switch" data-id="{{$feedbacks->first()->rfqs->id}}" id="quote_compare">
                                                <label class="form-check-label text-dark fw-bold mt-1"
                                                    for="flexSwitchCheckDefault">{{__('rfqs.compare_quote')}}</label>
                                            </div>
                                        </div>
                                        <div class="pe-1">
                                            @endif
                                            <a href="javascript:void(0)" data-id="{{ $feedbacks->first()->rfqs->id }}"
                                            class="ms-auto me-1 showRfqModal showRfqModal{{ $feedbacks->first()->rfqs->id }} btn btn-info px-3 py-1" style="font-size: 12px;">
                                                <img src="{{ URL::asset('front-assets/images/icons/eye.png') }}"
                                                    alt="View" class="pe-1"> {{ __('rfqs.view') }}
                                            </a>
                                            @if ($feedbacks->count() > 1 )
                                        </div>
                                        <div class="pe-1">
                                            @endif
                                            <a href="{{ route('dashboard-get-rfq-print', Crypt::encrypt($feedbacks->first()->rfqs->id)) }}" target="_blank" class="btn btn-info btn_print_color px-3 py-1"  style="font-size: 12px;"><img src="{{ URL::asset('front-assets/images/icons/icon_print.png') }}" alt="Print" class="pe-1" style="max-height: 12px;"> {{__('dashboard.print_icon')}}</a>
                                            @if ($feedbacks->count() > 1 )
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                        <div class="compare_rfq_quote">
                            <div class="compare-quote d-none" id="compare-quote">
                                <div class="card">
                                    <div class="px-3 py-1 bg-light border-bottom">
                                        <h5 class="modal-title d-flex w-100 align-items-center ">{{__('rfqs.compare_quote')}}
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table mb-0 table-bordered " id="quoteListing{{$feedbacks->first()->rfqs->id}}">
                                                <thead class="table-light">
                                                <tr>
                                                    <th class=" text-center QC-font text-nowrap">{{__('rfqs.quote')}}</th>
                                                    <th class=" text-center QC-font text-nowrap">{{__('rfqs.products')}}</th>
                                                    <th class=" text-center QC-font text-nowrap">{{__('rfqs.tot_amount')}} ( Rp )</th>
                                                    <th class=" text-center QC-font text-nowrap">{{__('rfqs.delivery_days')}}</th>
                                                    <th class=" text-center QC-font text-nowrap">{{__('rfqs.supplier_name')}}</th>
                                                    <th class=" text-center QC-font text-nowrap">{{ __('rfqs.valid_till') }}</th>
                                                    <th class=" text-center QC-font text-nowrap">{{__('admin.status')}}</th>
                                                    <th class=" text-center QC-font text-nowrap no-sort">{{__('rfqs.action')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if (isset($feedbacks) && !empty($feedbacks))
                                                    @foreach($feedbacks as $quote)
                                                        @php
                                                            $quoteId =  $quote->quotes->id;
                                                            $getQuoteProducts = \App\Models\QuoteItem::where('quote_id', $quoteId)->get(['rfq_product_id'])->toArray();
                                                        @endphp
                                                        <tr>
                                                            <th class=" text-center QC-font text-nowrap">BQTN-{{$quote->quotes->id}}</th>
                                                            <td class=" text-center QC-font text-nowrap">
                                                                <div class="multipleproducthover">
                                                                    <div href="javascript:voic(0);" class="tooltiphtml w-auto">
                                                                        <span class="text-primary"> {{count($getQuoteProducts)}} {{ __('rfqs.products') }}</span>
                                                                        <div class="tooltip_section text-dark text-start p-1 multi_pro_list">
                                                                            <table class="table mb-0 border w-100">
                                                                                <thead>
                                                                                <tr class="tableHead_color">
                                                                                    <th width="50%">{{ __('rfqs.product') }}</th>
                                                                                    <th width="25%">{{ __('rfqs.product_description') }}</th>
                                                                                    <th width="25%" class="text-end">{{ __('rfqs.quantity') }}</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                @foreach($feedbacks->first()->rfqs->rfqProducts as $key => $value)
                                                                                    @php
                                                                                        $class = '';
                                                                                        if(!in_array($value->id, array_column($getQuoteProducts, 'rfq_product_id'))){
                                                                                            $class = 'class=opacity-25';
                                                                                        }
                                                                                    @endphp
                                                                                    <tr {{$class}}>
                                                                                        <td class="text-wrap">{{ $value->category . ' - ' . $value->sub_category . ' - ' . $value->product }}</td>
                                                                                        <td class="text-wrap">{{ $value->product_description }}</td>
                                                                                        <td class="text-end text-nowrap"> {{ $value->quantity }} {{ $value->unit_name }}</td>
                                                                                    </tr>
                                                                                @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class=" text-center QC-font text-nowrap">{{number_format($quote->quotes->final_amount, 2)}}</td>
                                                            <td class=" text-center QC-font text-nowrap">{{$quote->quotes->quoteItem->max_delivery_days}}</td>
                                                            <td class=" text-center QC-font text-nowrap">{{$quote->quotes->supplier->name}}</td>
                                                            <td class=" text-center QC-font text-nowrap datehide"><span>{{ date('Ymd', strtotime($quote->quotes->valid_till)) }}</span>{{ date('d-m-Y', strtotime($quote->quotes->valid_till)) }}</td>
                                                            <td class="text-center QC-font text-nowrap">{{__('rfqs.'.$quote->quotes->quoteStatus->backofflice_name)}} </td>

                                                            <td class="text-center QC-font text-nowrap actionquotebtns approvalpopupleft">
                                                                @can('approval buyer approval configurations')
                                                                    @if (!empty($quote->quotes->getUserQuoteFeedback->where('is_deleted',0)->where('user_id',$auth->id)->where('quote_id',$quote->quotes->id)->first()))

                                                                        @if($quote->quotes->getUserQuoteFeedback->where('is_deleted',0)->where('user_id',$auth->id)->where('quote_id',$quote->quotes->id)->first()->feedback == 1)
                                                                        <button title="{{ __('profile.approved') }}" type="button" class="btn btn-outline-success btn-box rounded-2 no-btn text-dark newClass" data-rfq-id="{{ $feedbacks->first()->rfqs->id }}" data-quote-id="{{ $quote->quotes->id }}" data-feedback-value="1" data-bs-toggle="modal" data-bs-target="#approvedModal">
                                                                                <img src="{{ URL::asset('front-assets/images/thumbs-up.png') }}" alt="{{ __('profile.approved') }}" class="pe-1">
                                                                            </button>
                                                                        @elseif($quote->quotes->getUserQuoteFeedback->where('is_deleted',0)->where('user_id',$auth->id)->where('quote_id',$quote->quotes->id)->first()->feedback == 2)
                                                                        <button type="button" title="{{ __('profile.rejected') }}" class="btn btn-outline-danger btn-box rounded-2 no-btn text-dark newClass" data-rfq-id="{{ $feedbacks->first()->rfqs->id }}" data-quote-id="{{ $quote->quotes->id }}" data-feedback-value="2" data-bs-toggle="modal" data-bs-target="#rejectedModal">
                                                                                <img src="{{ URL::asset('front-assets/images/thumbs-down.png') }}" alt="{{ __('profile.rejected') }}" class="pe-1">
                                                                            </button>
                                                                        @elseif($quote->quotes->getUserQuoteFeedback->where('is_deleted',0)->where('user_id',$auth->id)->where('quote_id',$quote->quotes->id)->first()->feedback == 0)
                                                                            <button type="button" class="text-white btn-box rounded-2 no-btn" data-rfq-id="{{ $feedbacks->first()->rfqs->id }}" data-quote-id="{{ $quote->quotes->id }}" data-feedback-value="0" data-toggle="tooltip" ata-placement="bottom" title="{{ __('profile.pending') }}" disabled>
                                                                                <img src="{{ URL::asset('front-assets/images/pending.png') }}" alt="{{ __('profile.pending') }}" class="pe-1">
                                                                            </button>
                                                                        @endif
                                                                    @endif

                                                                @endcan
                                                                <a title="{{ __('rfqs.view') }}" href="javascript:void(0)" data-id="{{ $quote->quotes->id }}" class="showQuoteModal btn btn-box btn btn-outline-primary rounded-2">
                                                                    <img src="{{ URL::asset('front-assets/images/icons/eye.png') }}" style="margin-top: 2px;">
                                                                </a>
                                                                <a title="{{__('dashboard.print_icon')}}" href="{{ route('dashboard-get-rfq-quote-print',Crypt::encrypt($quote->quotes->id)) }}" target="_blank"  class="btn btn-outline-primary rounded-2 btn-box"  style="">
                                                                    <img src="{{ URL::asset('front-assets/images/icons/icon_print.png') }}" style="margin-top: 2px;">
                                                                </a>
                                                            </td>
                                                        </tr>

                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="other_rfq_data quoteDetails">
                            {{-- here list of quote are  displaying --}}
                            @if (isset($feedbacks) && !empty($feedbacks))
                                <div class="accordion subaccordion_section" id="subaccordion">
                                    @foreach ($feedbacks as $quote)
                                        @php
                                            $quoteId =  $quote->quotes->id;
                                            $getQuoteProducts = \App\Models\QuoteItem::where('quote_id', $quoteId)->get(['rfq_product_id'])->toArray();
                                        @endphp
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="sub_heading{{ $quote->quotes->id }}">
                                                <button class="accordion-button collapsed approvalQuoteCollapse" id="showQuoteData{{ $quote->quotes->id }}" data-rfq-id="{{ $feedbacks->first()->rfqs->id }}" data-quote-id="{{ $quote->quotes->id }}" type="button" data-bs-toggle="collapse" data-bs-target="#sub_collapse{{ $quote->quotes->id }}" aria-expanded="false" aria-controls="sub_collapse{{ $quote->quotes->id }}">
                                                    <div class="d-flex w-100">
                                                        <div class="flex-grow-1"> {{ __('rfqs.quote_number') }}: {{ $quote->quotes->quote_number }}
                                                            @if($quote->quotes->quoteStatus->name)
                                                                @if($quote->quotes->quoteStatus->name == 'Quotation Received')
                                                                    <span class="badge rounded-pill mt-1 bg-primary">{{ __('rfqs.'.$quote->quotes->quoteStatus->name) }}</span>
                                                                @elseif ($quote->quotes->quoteStatus->name == 'Quotation Accepted')
                                                                    <span class="badge rounded-pill mt-1 bg-success">{{ __('rfqs.'.$quote->quotes->quoteStatus->name) }}</span>
                                                                @elseif ($quote->quotes->quoteStatus->name == 'Quotation Expired')
                                                                    <span class="badge rounded-pill mt-1 purpole_color">{{ __('rfqs.'.$quote->quotes->quoteStatus->name) }}</span>
                                                                @elseif ($quote->quotes->quoteStatus->name == 'Quotation Rejected')
                                                                    <span class="badge rounded-pill mt-1 quote_expire_color">{{ __('rfqs.'.$quote->quotes->quoteStatus->name) }}</span>
                                                                @else
                                                                    <span class="badge rounded-pill mt-1">{{ __('rfqs.'.$quote->quotes->quoteStatus->name) }}</span>
                                                                @endif
                                                            @endif

                                                            @if (!empty($quote->quotes->getUserQuoteFeedback->where('is_deleted',0)->where('user_id',$auth->id)->where('quote_id',$quote->quotes->id)->first()))
                                                                <span id="feedbackStatus{{$quote->quotes->id}}">
                                                                    @if($quote->quotes->getUserQuoteFeedback->where('is_deleted',0)->where('user_id',$auth->id)->where('quote_id',$quote->quotes->id)->first()->feedback == 1)
                                                                        <span class="badge rounded-pill mt-1 bg-white border border-outline-success text-success">
                                                                            <img src="{{ URL::asset('front-assets/images/thumbs-up.png') }}" alt="{{ __('profile.approved') }}" class="pe-1" width="16"> {{ __('profile.approved') }}
                                                                        </span>
                                                                    @elseif($quote->quotes->getUserQuoteFeedback->where('is_deleted',0)->where('user_id',$auth->id)->where('quote_id',$quote->quotes->id)->first()->feedback == 2)
                                                                        <span class="badge rounded-pill mt-1 bg-white border border-outline-danger text-danger">
                                                                            <img src="{{URL::asset('front-assets/images/thumbs-down.png')}}" alt="{{ __('profile.rejected') }}" class="pe-1" width="16"> {{ __('profile.rejected') }}
                                                                        </span>
                                                                    @elseif($quote->quotes->getUserQuoteFeedback->where('is_deleted',0)->where('user_id',$auth->id)->where('quote_id',$quote->quotes->id)->first()->feedback == 0)
                                                                        <span class="badge rounded-pill mt-1 bg-white border border-outline-secondary text-primary">
                                                                            <img src="{{URL::asset('front-assets/images/pending.png')}}" alt="{{ __('profile.pending') }}" class="pe-1" width="16"> {{ __('profile.pending') }}
                                                                        </span>
                                                                    @endif
                                                                </span>
                                                            @endif

                                                            <span id="acceptRejectStatus{{$quote->quotes->id}}"></span>
                                                            <span id="pendingStatus{{$quote->quotes->id}}"></span>

                                                        </div>
                                                        <div class="ms-auto">{{ __('rfqs.total_price') }}:
                                                            {{ 'Rp ' . number_format($quote->quotes->final_amount, 2) }}
                                                        </div>
                                                        <div class="ms-auto px-3">{{ __('rfqs.date') }}:
                                                            {{ date('d-m-Y H:i', strtotime($quote->quotes->created_at)) }}
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="sub_collapse{{ $quote->quotes->id }}" class="accordion-collapse approvalQuoteSubCollapse collapse" aria-labelledby="sub_heading{{ $quote->quotes->id }}" data-bs-parent="#subaccordion">
                                                <div class="accordion-body p-2">
                                                    <!-- Approval process toggle value -->
                                                    <input type="hidden" name="progressPercentage" id="progressPercentage{{ $quote->quotes->id }}" value="" />
                                                    <input type="hidden" name="quoteIdValue" id="quoteIdValue" value="" />
                                                    <input type="hidden" name="quoteIdValue" id="quoteStatus" value="{{ $quote->quotes->status_id }}" />
                                                    <input type="hidden" name="loginUserId" id="loginUserId" value="{{auth()->user()->id}}" />
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row rfqform_view g-2">
                                                                <div class="col-md-4">
                                                                    <div class="bg-white p-2 border-light">
                                                                        <label>{{ __('rfqs.quote_number') }}:</label>
                                                                        <div>{{ $quote->quotes->quote_number }}</div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="bg-white p-2 border-light">
                                                                        <label>{{ __('rfqs.date') }}:</label>
                                                                        <div>{{ date('d-m-Y H:i', strtotime($quote->quotes->created_at)) }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="bg-white p-2 border-light">
                                                                        <label>{{ __('rfqs.rfq_number') }}: </label>
                                                                        <div>{{ $feedbacks->first()->rfqs->reference_number }}</div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="bg-white p-2 border-light">
                                                                        <label>{{ __('rfqs.valid_till') }}:</label>
                                                                        <div>{{ date('d-m-Y', strtotime($quote->quotes->valid_till)) }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="bg-white p-2 border-light">
                                                                        <label>{{ __('rfqs.total_price') }}:</label>
                                                                        <div>{{ 'Rp ' . number_format($quote->quotes->final_amount, 2) }} <small>(including
                                                                                all taxes)</small></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="bg-white p-2 border-light">
                                                                        <label>{{ __('rfqs.status') }}:</label>
                                                                        <div>{{ $quote->quotes->quoteStatus->name }} </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 pb-2">
                                                                    <div class="bg-white p-2 border-light">
                                                                        <label>{{ __('rfqs.product') }}:</label>
                                                                        <div class="multipleproducthover">
                                                                            <div href="javascript:voic(0);" class="tooltiphtml w-auto">
                                                                                <span class="text-primary"> {{count($getQuoteProducts)}} {{ __('rfqs.products') }}</span>
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

                                                                                        @foreach($feedbacks->first()->rfqs->rfqProducts as $key => $value)
                                                                                            @php
                                                                                                $class = '';
                                                                                                if(!in_array($value->id, array_column($getQuoteProducts, 'rfq_product_id'))){
                                                                                                    $class = 'class=opacity-25';
                                                                                                }
                                                                                            @endphp
                                                                                            <tr {{$class}}>
                                                                                                <td>{{ $value->category . ' - ' . $value->sub_category . ' - ' . $value->product }}</td>
                                                                                                <td>{{ $value->product_description }}</td>
                                                                                                <td class="text-end text-nowrap"> {{ $value->quantity }} {{ $value->unit->name}}</td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @if(isset($quote->quotes->termsconditions_file) && !empty($quote->quotes->termsconditions_file))
                                                                    <div class="col-md-4 pb-2">
                                                                        <div class="bg-white p-2 border-light">
                                                                            @php
                                                                                $termsconditionsFileTitle = Str::substr($quote->quotes->termsconditions_file,stripos($quote->quotes->termsconditions_file, "termsconditions_file_") + 21);
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
                                                                                <a href="{{ $quote->quotes->termsconditions_file? Storage::url($quote->quotes->termsconditions_file) :'javascript:void(0);' }}" class="text-decoration-none" target="_blank" title="{{$termsconditions_file_name}}">{{$termsconditions_file_name}}</a>
                                                                            </div>
                                                                        </div>
                                                                        <!-- </div> -->
                                                                    </div>
                                                                @endif
                                                                @if(isset($quote->quotes->quoteItem->certificate_attachment) && !empty($quote->quotes->quoteItem->certificate_attachment))
                                                                    <div class="col-md-4 pb-2">
                                                                        <div class="bg-white p-2 border-light">
                                                                            @php
                                                                                $downloadProductCertificate = "downloadCertificate(".$quote->quotes->id.", 'attached_document','".$quote->quotes->quote_number."')";
                                                                            @endphp
                                                                            <label for="product_certificate">{{ __('admin.product_certificate') }} :</label>
                                                                            <div>
                                                                                <a class="text-decoration-none btn btn-primary p-1"   href="javascript:void(0);" id="productCerificateFileDownload" onclick="{{$downloadProductCertificate}}"  class="text-decoration-none" title="{{ __('admin.attachment') }}" style="text-decoration: none;font-size: 12px;" >
                                                                                    <svg id="Layer_1" width="12px" fill="#fff" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg> {{ __('admin.attachment') }}</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                <!-- Show reason to get approval for this quote -->
                                                                @if(empty($quote->quotes->getUserQuoteFeedback->deleted_at))
                                                                    @if(isset($quote->quotes->getQuoteApprovalReason) && $quote->quotes->getQuoteApprovalReason->where('approval_person_id',auth()->user()->id))
                                                                        @php $approverData = $quote->quotes->getQuoteApprovalReason->where('approval_person_id',auth()->user()->id)->first(); @endphp
                                                                    
                                                                        @if(isset($approverData) && !empty($approverData->toArray()['reason_text']))
                                                                            <div class="col-md-4 pb-2">
                                                                                <div class="bg-white p-2 border-light">
                                                                                    <label for="reason_for_approval">{{ __('rfqs.reason_for_approval') }} :</label>
                                                                                    <div class="d-flex"> 
                                                                                        {{ strlen($approverData->toArray()['reason_text']) > 48 ? substr($approverData->toArray()['reason_text'],0,48) . '...' : $approverData->toArray()['reason_text']  }} 
                                                                                        <a id="quoteApprovalPopover" style="cursor: pointer;" class="ms-auto mt-auto" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-placement="bottom" title="{{ isset($approverData->toArray()['reason_text']) ? $approverData->toArray()['reason_text'] : '' }}"> @if(strlen($approverData->toArray()['reason_text']) > 48)<i class="fa fa-eye"></i>@endif </a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                                <!-- // End -->
                                                                
                                                            <!-- End -->
                                                            </div>
                                                            <div class="row mt-2 pe-2 pb-2">

                                                                <div class="col-md-12 d-flex ">
                                                                    {{-- Set chat icon --}}
                                                                    <div class="ps-2">
                                                                        @php
                                                                            $chatData = getChatDataForRfqById($quote->quotes->id, 'Quote');
                                                                        @endphp
                                                                        <a href="javascript:void(0)" onclick="chat.chatRfqViewData('{{ route('new-chat-create-view')  }}', '{{$chatData['group_chat_id']??""}}','Quote','{{$quote->quotes->quote_number}}', '{{ $quote->quotes->id??'' }}',$(this), 1)" data-id="{{ $quote->quotes->id }}" class=" btn px-3 py-1" style="font-size: 12px;background-color: #B7CFFF;">
                                                                            <img src="{{ URL::asset('front-assets/images/icons/chat_icon.png') }}"  style="max-height: 14px;" alt="View" title="{{ __('admin.chat')}}" class="pe-1"> @if(!empty($chatData) && $chatData['unread_message_count'] != 0)<span class="bg-warning text-black px-1 ms-1 fw-bold rounded" style="font-size: 10px;">{{ $chatData['unread_message_count'] }}</span>@endif
                                                                        </a>

                                                                        @if(!empty($chatData))
                                                                            <a class="d-none" href="javascript:void(0)" onclick="chat.chatQuoteHistoryData('{{ route('get-chat-quote-history-ajax')  }}', '{{$chatData['group_chat_id']??""}}','Quote','{{$quote->quotes->quote_number}}', '{{ $quote->quotes->id??'' }}',$(this))" data-id="{{ $quote->quotes->id }}" class=" btn px-3 py-1" style="font-size: 12px;background-color: #B7CFFF;">

                                                                                <img src="{{ URL::asset('front-assets/images/icons/chat_icon.png') }}"  style="max-height: 14px;" alt="View" class="pe-1"> Chat History
                                                                            </a>
                                                                        @endif
                                                                    </div>
                                                                    {{-- end chat icon--}}

                                                                    <div class="ms-auto d-flex">
                                                                        @can('approval buyer approval configurations')
                                                                            @if($quote->quotes->status_id != "3")
                                                                                <div class="pe-1">
                                                                                    <button type="button" class="btn btn-outline-success no-btn text-dark newClass approvalprocess{{$quote->quotes->id}} showAcceptQuoteModal px-3 py-1" data-rfq-id="{{ $feedbacks->first()->rfqs->id }}" data-quote-id="{{ $quote->quotes->id }}" data-feedback-value="1" data-toggle="tooltip" ata-placement="bottom" title="{{ __('admin.approve') }}">
                                                                                        <img src="{{ URL::asset('front-assets/images/thumbs-up.png') }}" alt="{{ __('admin.approve') }}" class="pe-1"> {{ __('admin.approve') }}
                                                                                    </button>
                                                                                </div>
                                                                                <div class="pe-1">
                                                                                    <button type="button" class="btn btn-outline-danger no-btn text-dark newClass approvalprocess{{$quote->quotes->id}} showRejectQuoteModal px-3 py-1" data-rfq-id="{{ $feedbacks->first()->rfqs->id }}" data-quote-id="{{ $quote->quotes->id }}" data-feedback-value="2" data-toggle="tooltip" ata-placement="bottom" title="{{ __('admin.reject') }}">
                                                                                        <img src="{{ URL::asset('front-assets/images/thumbs-down.png') }}" alt="{{ __('admin.reject') }}" class="pe-1"> {{ __('admin.reject') }}
                                                                                    </button>
                                                                                </div>
                                                                                @if($quote->quotes->status_id != "2")
                                                                                <div class="pe-1">
                                                                                    <button type="button" class="btn btn-danger text-white no-btn revokeapprovalprocess{{$quote->quotes->id}} revokeApprovalFeedback px-3 py-1" data-rfq-id="{{ $feedbacks->first()->rfqs->id }}" data-quote-id="{{ $quote->quotes->id }}" data-feedback-value="0" data-toggle="tooltip" ata-placement="bottom" title="{{ __('profile.revoke') }}">
                                                                                        <img src="{{ URL::asset('front-assets/images/revoke_white.png') }}" alt="{{ __('profile.revoke') }}" class="pe-1"> {{ __('profile.revoke') }}
                                                                                    </button>
                                                                                </div>
                                                                                @endif
                                                                            @endif
                                                                        @endcan

                                                                        <div class="pe-1">
                                                                            <a href="javascript:void(0)" data-id="{{ $quote->quotes->id }}" class="showQuoteModal btn btn-info px-3 py-1" data-toggle="tooltip" ata-placement="bottom" title="{{ __('rfqs.view') }}">
                                                                                <img src="{{ URL::asset('front-assets/images/icons/eye.png') }}" alt="View" class="pe-1"> {{ __('rfqs.view') }}
                                                                            </a>
                                                                        </div>

                                                                        <div class="pe-1">
                                                                            <a href="{{ route('dashboard-get-rfq-quote-print',Crypt::encrypt($quote->quotes->id)) }}" target="_blank" class="btn btn-info btn_print_color px-3 py-1" data-toggle="tooltip" ata-placement="bottom" title="{{__('dashboard.print_icon')}}">
                                                                                <img src="{{ URL::asset('front-assets/images/icons/icon_print.png') }}" alt="Print" class="pe-1"> {{__('dashboard.print_icon')}}
                                                                            </a>
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
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <input type="hidden" id="load-more" value="{{$hasMore ?? 0}}">

        @if($hasMore)
            <div class="flex items-center justify-center mt-4 text-center" id="loadMoreBtn" style="display: none">
                <button class="ldBtn btn btn-outline-primary text-dark px-3" wire:click="load">                    
                    {{ __('rfqs.load_more') }}
                </button>
            </div>
        @endif

    @else

        <!-- New Design by Aashish Bhai -->
        <div class="mb-3 d-flex">
            <div style="min-width: 150px;">
                <select class="form-select mt-2 py-2" name="approvalQuoteStatus" id="approvalQuoteStatus">
                    <option value="all">{{ __('rfqs.all') }}</option>
                    <option value="1">{{ __('profile.approved') }}</option>
                    <option value="2">{{ __('profile.rejected') }}</option>
                    <option value="3">{{ __('profile.pending') }}</option>
                </select>
            </div>
            <div class="ms-auto">
                <div class="d-flex align-items-center ">
                    <small class="text-nowrap me-2">0 {{ __('rfqs.result_found') }}</small>
                    <div id="grouptrading_search" class="input-group mb-0">
                        <input class="form-control form-control-sm" type="search" name="approvalCustomSearch" id="approvalCustomSearch" placeholder="Search" aria-label="Search">
                        <button class="btn bg-light text-white btn-sm search border approvalSearch" type="button">
                            <img src="{{ URL::asset('assets/images/icon_search_b.png') }}" alt="">
                        </button>
                    </div>
                </div>
                <div class="text-end">
                    <small class="text-danger" id="searchTextErr"></small>
                </div>
            </div>
        </div>
        <!-- End -->

        <p class="alert alert-danger text-center mb-0">{{ __('order.no') }} {{ __('rfqs.result_found') }}</p>

    @endif

</div>
