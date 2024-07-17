<script src="{{ URL::asset('assets/js/custom/permissions.js') }}"></script>
<script src="{{ URL::asset('assets/js/custom/app.js') }}"></script>


<div class="modal fade" id="rfqQuoteDetailsModal" data-bs-backdrop="true" tabindex="-1"
    aria-labelledby="rfqQuoteDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content radius_1 shadow-lg" id="rfqQuoteDetailsModalBlock">
            {{-- here quote detail shown from js --}}
        </div>
    </div>
</div>
<div class="modal right fade editmodal" id="editModal" data-bs-backdrop="static"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">

        <div class="modal-content showeditmodal">

        </div>
    </div>
</div>
<div class="modal fade" id="placeOrderModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="placeOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content radius_1 shadow-lg error_res" id="placeOrderModalBlock">

        </div>
    </div>
</div>
<div class="modal fade" id="placeOrderNextModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="placeOrderNextModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content radius_1 shadow-lg error_res" id="placeOrderNextModalBlock">

        </div>
    </div>
</div>

<!-- Customer Reference Id -->
<div class="modal fade" id="customerRefIdModel" aria-hidden="true" aria-labelledby="customerRefIdModelLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-none">
                <h5 class="modal-title" id="customerRefIdModelLabel">Add Customer Reference ID</h5>
                <button type="button" class="btn-close" data-bs-target="#placeOrderModal" data-bs-toggle="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <img src="{{ URL::asset('front-assets/images/icons/ref_id_1.png') }}" alt="Customer Reference ID">
                <h5 class="px-4 py-4">{{ __ ('dashboard.customer_ref_warning') }}</h5>
                <button class="btn btn-secondary" id="orderPlaceBtn" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{ URL::asset('front-assets/images/icons/icon_placeorder.png') }}" alt="Place order" class="pe-1"> {{ __('admin.no') }}
                </button>
                <button class="btn btn-primary" id="addCustIdBtn" data-bs-target="#placeOrderModal" data-bs-toggle="modal">
                    <img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}" alt="Yes" class="pe-1"> {{ __('admin.yes') }}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Customer Reference Id End -->
<input type="hidden" name="user_nib" id="user_nib" value="{{ $companyData->registrantion_NIB ?? null }}" />
<input type="hidden" name="user_npwp" id="user_npwp" value="{{ $companyData->npwp ?? null }}" />
<input type="hidden" name="company_id" id="company_id" value="{{ $companyData->id ?? null }}" />



<!-- NIB and NPWP modal -->
<div class="modal fade" id="NibNpwpModal" aria-hidden="true" aria-labelledby="NibNpwpModalLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-none">
                <h5 class="modal-title" id="NibNpwpModalLabel">{{ __('rfqs.Add NIP and NPWP') }}</h5>
                <button type="button" class="btn-close" data-bs-toggle="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <img src="{{ URL::asset('front-assets/images/icons/ref_id_1.png') }}" alt="NIP and NPWP">
                @if($isOwner || Auth::user()->hasPermissionTo('publish buyer company info'))
                    <h5 class="px-4 py-4">{{ __('rfqs.nip_npwp_msg') }}</h5>
                @else
                    <h5 class="px-4 py-4">{{ __('rfqs.buyeradmin_nip_npwp_msg') }}</h5>
                @endif
                <button class="btn btn-secondary" id="notNowBtn" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{ URL::asset('front-assets/images/icons/icon_placeorder.png') }}" alt="Cancel" class="pe-1"> {{ __('admin.cancel') }}
                </button>
                @if($isOwner || Auth::user()->hasPermissionTo('publish buyer company info'))
                <button type="button" class="btn btn-primary" id="addNibNpwpBtn" onclick="redirectToProfile()">
                @else
                <button type="button" class="btn btn-primary" id="addNibNpwpBtn" data-bs-dismiss="modal" aria-label="Close">
                @endif
                    <img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}" alt="Ok" class="pe-1">
                    {{ __('admin.ok') }}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- NIB and NPWP modal end -->

<div class="modal fade" id="rfqDetailsModal" data-bs-backdrop="true" tabindex="-1"
    aria-labelledby="rfqDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content radius_1 shadow-lg" id="rfqDetailsModalBlock"></div>
    </div>
</div>
<div class="header_top d-flex align-items-center">
    <h1 class="mb-0">{{ __('dashboard.my_rfqs') }}</h1>

    <div class="dropdown ms-auto favbtn">
        <a href="javascript:void(0);" class="addtofavRfq favorite_0 btn btn-outline-danger py-1 me-1" data-rfq_id="" role="button" data-isFavRfqSectionClicked = "1" style="padding: 0.125rem 0.4rem; font-size: .8rem;">
            <span>
                <i class="addtofavouriteRfq_0 fa fa-star-o  me-1"></i>
            </span>
            {{ __('dashboard.favourite') }}
        </a>
        <input type="hidden" name="is_favouriteRfq" id="is_favouriteRfq_0" value="0">
        <button class="btn btn-warning btn-sm dropdown-toggle" style="padding: 0.125rem 0.4rem;" type="button"
            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"> {{ __('admin.Export') }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end p-0 shadow-lg"
            aria-labelledby="dropdownMenuButton1">
            <li class="border-bottom "><a class="dropdown-item text-dark p-1 ps-2"
                    style="font-size: 0.9em;" href="{{ route('export-excel-rfq-ajax') }}"><span><img src="{{ URL::asset('front-assets/images/icons/excel-file.png') }}" class="me-1"
                            alt="" height="14px" srcset=""></span>
                            {{ __('dashboard.my_rfqs') }}</a></li>
            <li class=""><a class="dropdown-item text-dark p-1 ps-2" style="font-size: 0.9em;"
                    href="{{ route('export-excel-rfq-quotes-ajax') }}"><span><img src="{{ URL::asset('front-assets/images/icons/excel-file.png') }}" class="me-1" alt="" height="14px"
                            srcset=""></span>
                            {{ __('admin.rfq_quotes') }}</a></li>

        </ul>
    </div>
</div>

<!-- RFQ accordion -->
<div class="accordion" id="Rfq_accordian">
    <div class="col-md-12 mb-3 d-flex">
        <div class="col-md-3">
            <select class="form-select py-2"  name="customStatusSearch" id="customStatusSearch">
                <option value="all">{{ __('rfqs.all') }}</option>
                @if(count($rfqStatus))
                    @foreach($rfqStatus as $value)
                        <option value="{{$value->id}}" {{ $value->id == $statusSelected ? 'selected="selected"' : '' }}>{{ __('rfqs.' . $value->name) }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="ms-auto d-flex align-items-center">
            <small class="text-nowrap me-2 mt-2 showSearchData"><span id="searchResultCount">0</span> {{ __('rfqs.result_found') }}</small>
            <div id="grouptrading_search" class="input-group mb-0">
                <input class="form-control form-control-sm" type="search" name="customSearch" placeholder="{{ __('admin.search') }}" value="{{!empty($customSearch) ? $customSearch : '' }}" id="customSearch"  aria-label="{{ __('admin.search') }}" autocomplete="off">
                <button class="btn bg-light text-white btn-sm search border customSearch" type="button">
                    <img src="{{ URL::asset('assets/images/icon_search_b.png') }}" alt="{{ __('admin.search') }}">
                </button>
            </div>

            <div class="text-end">
                <small class="text-danger" id="searchTextErr"></small>
            </div>
        </div>
    </div>
    @if (count($userRfq))

        @foreach ($userRfq as $rfq)
            <div class="accordion-item radius_1 mb-2">
                <h2 class="accordion-header" id="headingOne">
                    <button id="rfqupdatecollpse" class="accordion-button justify-content-between collapsed Rfq_Id{{ $rfq->id }}" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $rfq->id }}" aria-expanded="false"
                        aria-controls="collapse{{ $rfq->id }}" data-id="{{ $rfq->id }}">
                        <div class="flex-grow-1 ">{{ __('rfqs.rfq_no') . ' ' . $rfq->reference_number }} <br>

                            @php
                              $badgeColor = $rfq->status_name == 'RFQ Completed' ? 'bg-success': ($rfq->status_name == 'RFQ Cancelled' ? 'bg-danger' : 'bg-primary');
                            @endphp
                                <span id="badgeColorSpan{{ $rfq->id }}" class="badge rounded-pill  mt-1 {{ $badgeColor }}">{{ __('rfqs.' . $rfq->status_name) }}</span>

                        </div>
                        @if(isset($rfq->groupName) && !empty($rfq->groupName))
                        <div>
                            <span class="badge rounded-pill alert-success align-middle fw-bold" style="font-size: 12px;">{{ 'BGRP-' . $rfq->groupId . ' : ' . $rfq->groupName }}</span>
                        </div>
                        @endif

                        <div class="ms-3 @if($rfq->is_favourite == 0) d-none @endif" id="showFavourite_{{$rfq->id}}">
                            <a class="text-decoration-none" href="javascript:void(0);" role="button"><img src="{{ URL::asset('front-assets/images/icons/icon_fav.png') }}" height="20px"></a>
                        </div>

                        <div class="font_sub px-3 datesection me-lg-5"><span
                                class="mb-1">{{ __('rfqs.date') }}:</span>
                            {{ date('d-m-Y H:i', strtotime($rfq->created_at)) }}</div>

                    </button>
                </h2>
                <div id="collapse{{ $rfq->id }}" data-id="{{ $rfq->id }}" data-type="rfq"
                    class="accordion-collapse collapse rfqCollapse" aria-labelledby="headingOne"
                    data-bs-parent="#Rfq_accordian">
                    <div class="accordion-body p-0">
                        <div class="container-fluid">
                            @canany(['edit buyer rfqs', 'delete buyer rfqs'])
                                <div class="editmenu_section">
                                    <div class="btn-group">
                                        <button type="button" class="btn dropdown-toggle btn-sm h1"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            &#8942;
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end py-0">
                                            @can('edit buyer rfqs')
                                                <li><a href="javascript:void(0)" class="dropdown-item edittab openeditmodal py-2" data-rfq_id="{{$rfq->id}}"> <img src="{{ URL::asset('front-assets/images/icons/icon_edit_add.png') }}" alt="Edit" style="max-height: 13px;" class="pe-1"> {{ __('rfqs.edit') }}</a></li>
                                            @endcan
                                            @php
                                                $cancelRfq = $rfq->status_id == 1 ? '':'style=display:none';
                                            @endphp
                                            @can('edit buyer rfqs')
                                                <li>
                                                    <a href="javascript:void(0)" class="dropdown-item py-2 addtofavRfq favorite_{{$rfq->id}}" data-rfq_id="{{$rfq->id}}" @if($rfq->is_favourite == 0) title = "{{__('dashboard.add_favourite')}}" @else title = "{{__('dashboard.remove_favourite')}}" @endif>
                                                        <span>
                                                            <i class="addtofavouriteRfq_{{$rfq->id}} @if($rfq->is_favourite == 0) fa fa-star-o @else fa fa-star @endif me-1"></i>
                                                        </span> {{ __('dashboard.favourite') }}
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('create buyer rfqs')
                                                <li>
                                                    <a href="javascript:void(0)" id="repeatRfq_{{$rfq->id}}" class="repeatRfq dropdown-item py-2" title="{{ __('dashboard.repeat_rfq')}}" data-rfq_id="{{$rfq->id}}" data-isRepeatOrder="0">
                                                        <img src="{{ URL::asset('front-assets/images/icons/repeat_rfq.png') }}" alt="{{ __('dashboard.repeat_rfq')}}" style="max-height: 13px;" class="pe-1">
                                                        {{ __('dashboard.repeat_rfq') }}
                                                    </a>
                                                </li>
                                            @endcan
                                             @can('delete buyer rfqs')
                                                <li {{$cancelRfq}} id="cancelrfq_remove{{$rfq->id}}"><a href="javascript:void(0)"  class="dropdown-item cancelrfq py-2" data-id="{{$rfq->id}}" data-rfq_id="{{Crypt::encrypt($rfq->id)}}"> <img src="{{ URL::asset("front-assets/images/icons/icon_delete_add.png") }}" alt="Cancel" style="max-height: 13px;" class="pe-1"> {{ __("admin.cancel") }}</a></li>
                                             @endcan
                                        </ul>
                                    </div>
                                </div>
                            @endcanany
                            <div class="row rfqform_view bg-white py-3">
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('rfqs.customer_name') }}:</label>
                                    <div>{{ $rfq->firstname . ' ' . $rfq->lastname }}</div>
                                </div>
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('rfqs.created_by') }}:</label>
                                    <div class="text-primary">{{ getUserName($rfq->created_by) }}</div>
                                </div>
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('rfqs.expected_delivery_date') }}:</label>
                                    @if ($rfq->expected_date)
                                        <div> {{ date('d-m-Y', strtotime($rfq->expected_date)) }}</div>
                                    @else
                                        <div> - </div>
                                    @endif
                                </div>
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('rfqs.product') }}:</label>
                                    <div>
                                        {{ $rfq->category . ' - ' . $rfq->sub_category . ' - ' . $rfq->product_name }}
                                    </div>
                                </div>
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('rfqs.quantity') }}:</label>
                                    <div>{{ $rfq->quantity . ' ' . $rfq->unit_name }}</div>
                                </div>
                                <div class="col-md-6category pb-2">
                                    <label>{{ __('rfqs.product_description') }}:</label>
                                    <div>{{ $rfq->product_description }} </div>
                                </div>
                                <div class="col-md-6 pb-2">
                                    <label class="d-block">{{ __('rfqs.payment_terms') }}:</label>
                                    @if($rfq->payment_type==1)
                                        <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.credit') }} - {{$rfq->credit_days}}</span></div>
                                    @elseif($rfq->payment_type==0)
                                        <div class="text-dark"><span class="badge rounded-pill bg-success">{{ __('admin.advance') }}</span></div>
                                    @elseif($rfq->payment_type==3)
                                        <div class="text-dark"><span class="badge rounded-pill bg-danger">{{  __('admin.lc')  }}</span></div>
                                    @elseif($rfq->payment_type==4)
                                        <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.skbdn') }}</span></div>
                                    @else
                                        <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.credit') }}</span></div>
                                    @endif
                                    <!-- @if($rfq->is_require_credit)
                                        <span class="badge rounded-pill mt-1 bg-danger">{{ __('rfqs.credit') }}</span>
                                    @else
                                        <span class="badge rounded-pill mt-1 bg-success">{{ __('rfqs.advance') }}</span>
                                    @endif -->
                                </div>
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('rfqs.comment') }}:</label>
                                    <div>{{ $rfq->comment ? $rfq->comment : ' - ' }}</div>
                                </div>
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('rfqs.address') }}:</label>
                                    <div>
                                        {{ $rfq->address_line_1?($rfq->address_line_1.','):'' }} {{ $rfq->address_line_2?($rfq->address_line_2.','):'' }} {{ $rfq->sub_district?($rfq->sub_district.','):'' }} {{ $rfq->district?($rfq->district.','):'' }}
                                        {!! $rfq->city_id > 0 ? ("<br />".getCityName($rfq->city_id).',') : ("<br />".$rfq->city.',') !!}
                                        {!! $rfq->state_id > 0 ? ("<br />".getStateName($rfq->state_id).',') : ("<br />".$rfq->state.',') !!}
                                        {{ $rfq->pincode }}
                                    </div>
                                </div>
                                @if ($rfq->unloading_services || $rfq->rental_forklift)
                                    <div class="col-md-6 pb-2">
                                        <label>{{ __('rfqs.other_options') }}:</label>
                                        @if ($rfq->unloading_services)
                                            <br><span>{{ __('dashboard.need_unloading_services') }}</span>
                                        @endif
                                        @if ($rfq->rental_forklift)
                                            <br><span>{{ __('dashboard.need_rental_forklift') }}</span>
                                        @endif
                                    </div>
                                @endif
                                @if(isset($rfq->attached_document) && !empty($rfq->attached_document))
                                @php
                                    $rfqFileTitle = Str::substr($rfq->attached_document,34);
                                    $extension_rfq_file = getFileExtension($rfqFileTitle);
                                    $rfq_file_filename = getFileName($rfqFileTitle);
                                    if(strlen($rfq_file_filename) > 10){
                                        $rfq_file_name = substr($rfq_file_filename,0,10).'...'.$extension_rfq_file;
                                    } else {
                                        $rfq_file_name = $rfq_file_filename.$extension_rfq_file;
                                    }
                                @endphp
                                    <div class="col-md-6 pb-2">
                                        <label>{{ __('rfqs.rfq_attachment') }}:</label>
                                        <div>
                                            <a class="text-decoration-none btn btn-primary p-1" href="javascript:void(0);" data-id="{{ $rfq->id }}" id="RfqFileDownload" onclick="downloadimg('{{ $rfq->id }}', 'attached_document', '{{ $rfq_file_name }}')"  title="{{ $rfqFileTitle }}" style="text-decoration: none;font-size: 12px;"> <svg id="Layer_1" width="12px" fill="#fff" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg> {{ __('rfqs.rfq_attachment') }}</a>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-12 d-flex pb-2">
                                    @php
                                        $chatData = getChatDataForRfqById($rfq->id, 'Rfq');
                                    @endphp

                                    <a href="javascript:void(0)" onclick="chat.chatRfqViewData('{{ route('new-chat-create-view')  }}', '{{$chatData['group_chat_id']??""}}','Rfq','{{$rfq->reference_number}}', '{{ $rfq->id??'' }}',$(this), 1, {{ $rfq->company_id??0 }})" data-id="{{ $rfq->id }}" class=" btn px-3 py-1" style="font-size: 12px;background-color: #B7CFFF;">
                                        <img src="{{ URL::asset('front-assets/images/icons/chat_icon.png') }}"  style="max-height: 14px;" alt="View" title="{{ __('admin.chat')}}" class="pe-1">  @if(!empty($chatData) && $chatData['unread_message_count'] != 0)<span class="bg-warning text-black px-1 ms-1 fw-bold rounded" style="font-size: 10px;">{{ $chatData['unread_message_count'] }}</span>@endif

                                    </a>
                                    <a href="javascript:void(0)" data-id="{{ $rfq->id }}"
                                    class="ms-auto me-1 showRfqModal showRfqModal{{ $rfq->id }} btn btn-info px-3 py-1" style="font-size: 12px;">
                                        <img src="{{ URL::asset('front-assets/images/icons/eye.png') }}"
                                        alt="View" class="pe-1"> {{ __('rfqs.view') }}
                                    </a>
                                    <a target="_blank" href="{{ route('dashboard-get-rfq-print',Crypt::encrypt($rfq->id)) }}"  class="btn btn-info btn_print_color px-3 py-1" style="font-size: 12px;"><img src="{{ URL::asset('front-assets/images/icons/icon_print.png') }}" alt="Print" class="pe-1" style="max-height: 12px;"> {{__('dashboard.print_icon')}}</a>
                                </div>
                            </div>
                        </div>
                        <div class="compare_rfq_quote"> </div>
                        <div class="other_rfq_data quoteDetails">
                            {{-- here list of quote are  displaying --}}
                        </div>
                    </div>
                </div>
            </div>

        @endforeach
    @else
        <div class="col-md-12">
            <div class="alert alert-danger radius_1 text-center fs-6 fw-bold" id="no_rfq_alert">{{ __('rfqs.No_rfq_found') }}</div>
        </div>
    @endif
</div>
<!-- RFQ accordion -->

<script>

    //Download attachment document
    function downloadimg(rfq_id,fieldName, name){
        event.preventDefault();
        var data = {
            rfq_id:rfq_id,
            fieldName: fieldName
        }
        $.ajax({
            url: "{{ route('rfq-attachment-download-ajax') }}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: data,
            type: 'POST',
            xhrFields: {
                responseType: 'blob'
            },
            success: function (response) {
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = name;
                link.click();
            },
        });
    }

    function downloadCertificate(id, fieldName, name) {
        event.preventDefault();
        var data = {
            id: id,
            fieldName: fieldName
        }
        $.ajax({
            url: "{{ route('dashboard-download-certificate-ajax') }}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: data,
            type: 'POST',
            xhrFields: {
                responseType: 'blob'
            },
            success: function (response) {
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = name;
                link.click();
            },
        });
    }
    $(document).on('click','.openeditmodal',function(){
        var rfqId = $(this).attr('data-rfq_id');
        $.ajax({
            url: "{{ route('dashboard-get-rfq-editmodal-ajax', '') }}" + "/" + rfqId,
            type: 'GET',
            success: function(successData) {
                if (successData.html) {
                    $(".showeditmodal").html(successData.html);
                    $(".rfqCollapse").collapse("hide");
                    $(".editmodal").modal('show');
                }

            }
        });
    });

    $(document).on('click','.cancelrfq',function(){
        var rfqId = $(this).attr('data-rfq_id'), derfqId = $(this).attr('data-id');
        if(rfqId){
            swal({
                text: "{{  __('admin.cancel_rfq') }}",
                icon: "/assets/images/warn.png",
                buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                dangerMode: true,
            })
            .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{ route('cancel-rfq-ajax') }}",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data: {'rfqId':rfqId},
                            type: 'POST',
                            responseType: 'json',
                            success: function(successData) {
                                if (successData.success==true) {
                                    $('#badgeColorSpan' + derfqId).removeClass('bg-primary');
                                    $('#badgeColorSpan' + derfqId).addClass('bg-danger');
                                    $('#badgeColorSpan' + derfqId).html(successData.rfqStatus);
                                    $('#cancelrfq_remove' + derfqId).html('');
                                    new PNotify({
                                        text: "{{ __('admin.rfq_cancelled_successfully') }}",
                                        type: 'danger',
                                        styling: 'bootstrap3',
                                        animateSpeed: 'fast',
                                        delay: 1000
                                    });
                                }
                            }
                        });
                    } else {
                        return false;
                    }
                });
        }

    });
    //Set Focus on customer reference id field
    $(document).on('click','#addCustIdBtn',function(){
        $("#cust_ref_id").css({'border-color':'#86b7fe','outline': '0','box-shadow': '0 0 0 0.25rem rgb(13 110 253 / 25%)'});
    });


    $(document).on('show.bs.modal','.editmodal', function (event) {
        $(this).find('.nav a:first').tab('show');
    });
    function setPaymentType(){
        $('.exist_order').show();
        $('.loan_order').hide();
      //  $('#final_amount').show();
        let selector = $('#is_credit');
        if(selector.val()=='1'){
            selector.closest('.row.payment-type').find('.credit-type-div').show();
            selector.closest('.row.payment-type').find('.credit_type').prop('disabled', false);
            selector.closest('.row.payment-type').find('.credit-type-div_2').hide();
            selector.closest('.row.payment-type').find('.credit_type_2').prop('disabled', false);
            $('.address').show();
            $('.loan_div').hide();
            $('.exist_order').show();
            $('.loan_order').hide();
            $('.terms').show();
            $('.otp_div').hide();
            $('.credit-type-div_2').hide();
        }else if(selector.val()=='2'){

            $('.address').hide();
            $('.loan_div').show();
            $('.exist_order').hide();
            $('.loan_order').show();
            $('.terms').hide();
            $('.otp_div').hide();
            $('.credit-type-div_2').show();
            selector.closest('.row.payment-type').find('.credit-type-div').hide();
            selector.closest('.row.payment-type').find('.credit_type').prop('disabled', false);
            selector.closest('.row.payment-type').find('.credit-type-div_2').show();
            selector.closest('.row.payment-type').find('.credit_type_2').prop('disabled', true);
            var quote_id=$('#quote_id').val();
            var amount=$('#amount_rfq').val();
            $.ajax({
                url: "{{ route('settings.credit.loan.apply.calculation.json','') }}" + "/" +
                quote_id ,
                type: 'GET',
                dataType: 'json',
                success: function(successData) {
                    $('#calc').empty();
                    $.each(successData, function(index, value) {
                      if(index=='Total Amount')
                      {
                        $('#amountSent').val(value.replace('Rp',''));
                      }
                      if(index=='Product')
                      {
                        value='Rp'+' '+amount;
                      }
                      if(index=='Paybal Amount'){
                            $('#paybal_amount').val(value);
                        }
                     if(index=='Total Amount' || index=='Paybal Amount')
                      {
                        var style="style='padding: 0.5rem 0.5rem;background-color:#e5e5e5;font-weight: bold;'";
                      }
                      else{
                        var style="class='fw-normal' style='padding: 0.5rem 0.5rem;background-color: var(--bs-table-bg);'";
                      }
                       var data="<tr><th colspan='12' "+style+">"+index+"</th><th scope='col' class='text-end fw-normal'"+style+">"+value+"</th></tr>";
                        $('#calc').append(data);

                        });
                },
                error: function() {
                    console.log('error');
                }
            });

        }
        else{
            selector.closest('.row.payment-type').find('.credit-type-div').hide();
            selector.closest('.row.payment-type').find('.credit_type').prop('disabled', true);
            selector.closest('.row.payment-type').find('.credit-type-div_2').hide();
            selector.closest('.row.payment-type').find('.credit_type_2').prop('disabled', true);
            $('.address').show();
            $('.loan_div').hide();
            $('.exist_order').show();
            $('.loan_order').hide();
            $('.terms').show();
            $('.otp_div').hide();
            $('.credit-type-div_2').hide();
        }
    }

    $('.rfqCollapse').on('show.bs.collapse', function(e) {
        var rfqId = $(this).attr('data-id');
       var customSearchVal =  @json($customSearch);
        if ($(this).is(e.target)) {
            $.ajax({
                url: "{{ route('dashboard-get-rfq-quotes-ajax', '') }}",
                data: { rfqId : rfqId,customSearch : customSearchVal},
                type: 'GET',
                success: function(successData) {
                    if (successData.rfqhtml) {
                        $('#collapse' + rfqId + ' .rfqform_view').html(successData.rfqhtml);
                    }
                    if (successData.html) {
                        $('#collapse' + rfqId + ' .quoteDetails').html(successData.html);
                    }
                    if (successData.returnCompareHTML){
                        if(successData.flagCount > 0){
                            $('#collapse' + rfqId + ' .compare_rfq_quote').html(successData.returnCompareHTML);
                            $('#qouteListing'+rfqId).dataTable({searching: false, paging: false,"order": [],columnDefs: [{orderable: false,targets: "no-sort"}]});
                        }
                    }
                },
                error: function() {
                    console.log('error');
                }
            });

        }
    });
    $(document).ready(function() {
        $(document).on('click', '.showQuoteModal', function() {
            var quoteId = $(this).attr('data-id');
            $.ajax({
                url: "{{ route('dashboard-get-rfq-quotes-details-ajax', '') }}" + "/" +
                    quoteId,
                type: 'GET',
                success: function(successData) {
                    if (successData.html) {
                        $('#rfqQuoteDetailsModalBlock').html(successData.html);
                        $('#rfqQuoteDetailsModal').modal('show');
                    }
                },
                error: function() {
                    console.log('error');
                }
            });
        });
        $(document).on('click', '.showRfqModal', function() {
            $('#rfqDetailsModal').modal('hide');
            var rfqId = $(this).attr('data-id');
            $.ajax({
                url: "{{ route('dashboard-get-rfq-details-ajax', '') }}" + "/" +
                    rfqId,
                type: 'GET',
                success: function(successData) {
                    if (successData.html) {
                        $('#rfqDetailsModalBlock').html(successData.html);
                        $('#rfqDetailsModal').modal('show');
                    }
                },
                error: function() {
                    console.log('error');
                }
            });
        });
        $('#useraddress_id').select2({
            dropdownParent: $('#address_block'),
        });
        $('#useraddress_id_next').select2({
            dropdownParent: $('#address_block_2'),
        });
        $(document).on('change','#useraddress_id',function(){
            let is_edit = $(this).attr('data-isEdit');
            let selected_option = $('option:selected', this);
            if (is_edit==1){
                $(".address_name").val(selected_option.attr('data-address_name'));
                $(".addressLine1").val(selected_option.attr('data-address_line_1'));
                $(".addressLine2").val(selected_option.attr('data-address_line_2'));
                $(".sub_district").val(selected_option.attr('data-sub_district'));
                $(".district").val(selected_option.attr('data-district'));
                $(".city").val(selected_option.attr('data-city'));
                $(".state").val(selected_option.attr('data-state'));
                $(".pincode").val(selected_option.attr('data-pincode'));


                $("#stateEditId").val(selected_option.attr('data-state-id')).trigger('change');

            }
        });
        $(document).on('change','#useraddress_id_next',function(){
            let is_edit = $(this).attr('data-isEdit');
            let selected_option = $('option:selected', this);
            if (is_edit==1){
                $(".address_name").val(selected_option.attr('data-address_name'));
                $(".addressLine1").val(selected_option.attr('data-address_line_1'));
                $(".addressLine2").val(selected_option.attr('data-address_line_2'));
                $(".sub_district").val(selected_option.attr('data-sub_district'));
                $(".district").val(selected_option.attr('data-district'));
                $(".city").val(selected_option.attr('data-city'));
                $(".state").val(selected_option.attr('data-state'));
                $(".pincode").val(selected_option.attr('data-pincode'));


                $("#stateEditId").val(selected_option.attr('data-state-id')).trigger('change');

            }
        });
        $(document).on('click', '.showPlaceOrderModal', function() {
            var user_nib = $("#user_nib").val();
            var user_npwp = $("#user_npwp").val();
            if(user_nib.length == '' || user_npwp.length == '' ) {
                $("#NibNpwpModal").modal('show');
                $('#rfqQuoteDetailsModal').modal('hide');
            } else {
                $('#rfqQuoteDetailsModal').modal('hide');
                var quoteId = $(this).attr('data-id');
                $.ajax({
                    url: "{{ route('dashboard-get-place-order-details-ajax', '') }}" + "/" +
                        quoteId,
                    type: 'GET',
                    success: function(successData) {
                        if (successData.html) {
                            $('#placeOrderModalBlock').html(successData.html);
                            $('#placeOrderModal').modal('show');
                            setPaymentType();
                        }
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        });
        $(document).on('click', '.nextOrderPage', function() {
            $('.address').show();
            $('.loan_div').hide();
            $('.payment-type').hide();
            $('.my-3').hide();
            $('.loan_main_div').show();
            $('.terms').hide();
            $('.rfqform_view1').show();
            $('#main_div').hide();
            $('.terms').show();
            $('.loan_order').hide();
            $('.loan_otp').show();
            $('.otp_div').hide();
            $("#paybalAMount").html($('#paybal_amount').val());
            $("#placeOrderBtnOtp").prop('disabled', false);
        });
        $(document).on('click', '.placeOrderBtnOtpback', function() {
            $('.address').hide();
            $('.loan_div').show();
            $('.payment-type').show();
            $('.my-3').show();
            $('.loan_main_div').show();
            $('.rfqform_view1').hide();
            $('#main_div').show();
            $('.terms').hide();
            $('.loan_order').show();
            $('.loan_otp').hide();
            $('.otp_div').hide();
        });
        $(document).on('click', '#placeOrderBtnOtp', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            if ($('#placeOrderAddressForm').parsley().validate()) {
                $("#placeOrderBtnOtp").prop('disabled', true);
                var ajaxLoading = false;
                var formData = {
                    quoteId:$('#quote_id').val(),
                    amount:$('#amountSent').val()

                }
                if(!ajaxLoading) {
                    ajaxLoading = true;
            $.ajax({
                        url: "{{ route('settings.credit.loan.create.json') }}",
                        type: 'POST',
                        data:formData,
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            ajaxLoading = false;
                            if (response.success) {
                                $('.address').hide();
                                $('.loan_div').hide();
                                $('.payment-type').hide();
                                $('.my-3').hide();
                                $('.loan_main_div').hide();
                                $('.terms').hide();
                                $('.rfqform_view1').hide();
                                $('#main_div').hide();
                                $('.terms').hide();
                                $('.loan_order').hide();
                                $('.loan_otp').hide();
                                $('.otp_div').show();
                                $("#totalAmountOtp").html('Rp.'+$('#amountSent').val());
                            }
                            new PNotify({
                                text: response.message,
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 2000
                            });
                        },
                        error: function() {
                            new PNotify({
                                text: "{{ __('profile.something_went_wrong') }}",
                                type: 'error',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 2000
                            });
                        }
                    });
                }

            }
        });


        $(document).on('click', '.OtpVerify', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            if ($('#placeOrderAddressForm').parsley().validate()) {
                $("#OtpVerify").prop('disabled', true);
                let codes = [];
                for (let i = 1; i < 7; i++) {
                        codes.push($("#digit-"+i).val());
                    }

                var formData = {
                    addressLine1:$('#addressLine1').val(),
                    address_name:$('#address_name').val(),
                    addressLine2:$('#addressLine2').val(),
                    district:$('#district').val(),
                    sub_district:$('#sub_district').val(),
                    stateId:$('#stateId').val(),
                    state:$('#state').val(),
                    cityId:$('#cityId').val(),
                    city:$('#city').val(),
                    pincode:$('#pincode').val(),
                    quoteId:$('#quote_id').val(),
                    addressLine1:$('#addressLine1').val(),
                    code:codes,
                    is_credit:$('#is_credit').val(),
                    cust_ref_id:$('#cust_ref_id').val(),
                    comment:$('#comment').val(),

                }
            $.ajax({
                        url: "{{ route('settings.credit.loan.verify.otp.json') }}",
                        type: 'POST',
                        data: formData,
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            if (response.success) {

                                new PNotify({
                                text: "{{ __('dashboard.order_place_success_message') }}",
                                    type: 'success',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 1000
                                });

                                $("#placeOrderAddressForm")[0].reset();
                                $('#placeOrderModal').modal('hide');
                                $('.showPlaceOrderModal' + $('#quote_id').val()).remove();
                                $('#myOrderCount').html(response.ordersCount);
                                $('#mainContentSection').html(response.html);
                                $('html, body').animate({
                                    scrollTop: $("#mainContentSection").offset().top
                                }, 50);
                                $('#collapse' + response.lastOrderId).collapse('show');
                                $('#rfqSection').removeClass('btnactive');
                                $('#addressSection').removeClass('btnactive');
                                $('#postRequirementSection').removeClass('btnactive');
                                $('#orderSection').addClass('btnactive');


                            }else{
                                new PNotify({
                                text: response.message,
                                type: 'warning',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 1000
                             });
                             for (let i = 1; i < 7; i++) {
                                $("#digit-"+i).val('');
                            }
                             $("#OtpVerify").prop('disabled', false);
                            }
                        },
                        error: function() {
                            new PNotify({
                                text: "{{ __('profile.something_went_wrong') }}",
                                type: 'error',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 2000
                            });
                            $("#OtpVerify").prop('disabled', false);
                        }
                    });

            }
        });


        $(document).on('click', '#placeOrderBtn', function(e) {

            e.preventDefault();
            e.stopImmediatePropagation();
            var user_nib = $("#user_nib").val();
            var user_npwp = $("#user_npwp").val();
            var custRefId = $("#cust_ref_id").val();
            if(user_nib.length == '' || user_npwp.length == '' ) {
                $('#rfqQuoteDetailsModal').modal('hide');
                $("#NibNpwpModal").modal('show');
            } else {

                if ($('#placeOrderAddressForm').parsley().validate()) {
                    //address missing in form need to add
                    if($("#cust_ref_id").val() == 0) {
                        $('#placeOrderModal').modal('hide');
                        $('#customerRefIdModel').modal('show');
                    } else {
                        if(custRefId != null) {
                            $.ajax({
                                url: "{{ route('check-customer-refId-ajax') }}",
                                type:"POST",
                                data:{
                                    "custRefId":custRefId,
                                    _token: "{{ csrf_token() }}"
                                },
                                success:function(response){
                                    if(response.ref_exist == true) {
                                        $("#ref_msg").css("color","red").html('PO customer reference id is already exist');
                                        $("#placeOrderBtn").prop('disabled', true);
                                    } else {
                                        $("#placeOrderBtn").prop('disabled', true);
                                        var formData = new FormData($('#placeOrderAddressForm')[0]);
                                        var quoteId = $('#placeOrderAddressForm input[name="quoteId"]').val();
                                        let is_group_rfq = $('#placeOrderAddressForm').attr('data-is_group_rfq');
                                        let url = "{{ route('dashboard-place-order-ajax') }}";
                                        if (is_group_rfq==1){
                                            if (!SnippetAppPermission.hasPermission('create buyer payments')) {
                                                SnippetApp.swal.info("{{ __('admin.access_denied') }}","{{ __('admin.user_not_has_permission') }}","{{ __('admin.ok') }}");
                                                return false;
                                            }

                                            url = "{{ route('dashboard-group-place-order-ajax') }}"

                                        }
                                        $.ajax({
                                            url: url,
                                            data: formData,
                                            type: 'POST',
                                            contentType: false,
                                            processData: false,
                                            success: function(successData) {
                                                $('#placeOrderBtn').attr('disabled', false);
                                                if (successData.success) {
                                                    if (is_group_rfq==1){
                                                        location.href = successData.invoice_url;
                                                        return;
                                                    }
                                                    new PNotify({
                                                        text: "{{ __('dashboard.order_place_success_message') }}",
                                                        type: 'success',
                                                        styling: 'bootstrap3',
                                                        animateSpeed: 'fast',
                                                        delay: 1000
                                                    });

                                                    $("#placeOrderAddressForm")[0].reset();
                                                    $('#placeOrderModal').modal('hide');
                                                    $('.showPlaceOrderModal' + quoteId).remove();
                                                    $('#myOrderCount').html(successData.ordersCount);
                                                    $('#mainContentSection').html(successData.html);
                                                    $('html, body').animate({
                                                        scrollTop: $("#mainContentSection").offset().top
                                                    }, 50);
                                                    $('#collapse' + successData.lastOrderId).collapse('show');
                                                    $('#rfqSection').removeClass('btnactive');
                                                    $('#addressSection').removeClass('btnactive');
                                                    $('#postRequirementSection').removeClass('btnactive');
                                                    $('#orderSection').addClass('btnactive');
                                                    // $('#orderSection').trigger('click');
                                                    //loadUserActivityData();
                                                }else{
                                                    if (is_group_rfq==1 || successData.message) {
                                                        new PNotify({
                                                            text: successData.message,
                                                            type: 'warning',
                                                            styling: 'bootstrap3',
                                                            animateSpeed: 'fast',
                                                            delay: 3000
                                                        });
                                                    }else{
                                                        new PNotify({
                                                            text: "{{ __('admin.something_went_wrong') }}",
                                                            type: 'warning',
                                                            styling: 'bootstrap3',
                                                            animateSpeed: 'fast',
                                                            delay: 3000
                                                        });
                                                    }
                                                }
                                            },
                                            error: function() {
                                                $('#placeOrderBtn').attr('disabled', false);
                                                console.log('error');
                                            }
                                        });
                                    }
                                },
                                error: function(error) {
                                    console.log(error);
                                }
                            });
                        } else {
                            $(this).attr('disabled', true);
                            $("#placeOrderBtn").prop('disabled', true);
                            var formData = new FormData($('#placeOrderAddressForm')[0]);
                            var quoteId = $('#placeOrderAddressForm input[name="quoteId"]').val();
                            let is_group_rfq = $('#placeOrderAddressForm').attr('data-is_group_rfq');
                            let url = "{{ route('dashboard-place-order-ajax') }}";
                            if (is_group_rfq==1){
                                if (!SnippetAppPermission.hasPermission('create buyer payments')) {
                                    SnippetApp.swal.info("{{ __('admin.access_denied') }}","{{ __('admin.user_not_has_permission') }}","{{ __('admin.ok') }}");
                                    return false;

                                }
                                url = "{{ route('dashboard-group-place-order-ajax') }}"
                            }
                            $.ajax({
                                url: url,
                                data: formData,
                                type: 'POST',
                                contentType: false,
                                processData: false,
                                success: function(successData) {
                                    $('#placeOrderBtn').attr('disabled', false);
                                    if (successData.success) {
                                        if (is_group_rfq==1){
                                            location.href = successData.invoice_url;
                                            return;
                                        }
                                        new PNotify({
                                            text: "{{ __('dashboard.order_place_success_message') }}",
                                            type: 'success',
                                            styling: 'bootstrap3',
                                            animateSpeed: 'fast',
                                            delay: 1000
                                        });
                                        $("#placeOrderAddressForm")[0].reset();
                                        $('#placeOrderModal').modal('hide');
                                        $('.showPlaceOrderModal' + quoteId).remove();
                                        $('#myOrderCount').html(successData.ordersCount);
                                        $('#mainContentSection').html(successData.html);
                                        $('html, body').animate({
                                            scrollTop: $("#mainContentSection").offset().top
                                        }, 50);
                                        $('#collapse' + successData.lastOrderId).collapse('show');
                                        $('#rfqSection').removeClass('btnactive');
                                        $('#addressSection').removeClass('btnactive');
                                        $('#postRequirementSection').removeClass('btnactive');
                                        $('#orderSection').addClass('btnactive');
                                        // $('#orderSection').trigger('click');
                                        //loadUserActivityData();
                                    }else{
                                        if (is_group_rfq==1 || successData.message) {
                                            new PNotify({
                                                text: successData.message,
                                                type: 'warning',
                                                styling: 'bootstrap3',
                                                animateSpeed: 'fast',
                                                delay: 3000
                                            });
                                        }else{
                                            new PNotify({
                                                text: "{{ __('admin.something_went_wrong') }}",
                                                type: 'warning',
                                                styling: 'bootstrap3',
                                                animateSpeed: 'fast',
                                                delay: 3000
                                            });
                                        }
                                    }
                                },
                                error: function() {
                                    $('#placeOrderBtn').attr('disabled', false);
                                    console.log('error');
                                }
                            });
                        }
                    }
                }
            }
        });

        $(document).on('click', '#orderPlaceBtn', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            if ($('#placeOrderAddressForm').parsley().validate()) {
                var quoteId = $('#placeOrderAddressForm input[name="quoteId"]').val();
                let that = $(this);
                that.attr('disabled', true);
                $("#placeOrderBtn").prop('disabled', true);
                $('.showPlaceOrderModal'+quoteId).hide();
                $('#orderplaceloader'+quoteId).removeClass('hidden');
                var formData = new FormData($('#placeOrderAddressForm')[0]);
                var quoteId = $('#placeOrderAddressForm input[name="quoteId"]').val();
                let is_group_rfq = $('#placeOrderAddressForm').attr('data-is_group_rfq');
                let url = "{{ route('dashboard-place-order-ajax') }}";
                if (is_group_rfq==1){
                    if (!SnippetAppPermission.hasPermission('create buyer payments')) {
                        SnippetApp.swal.info("{{ __('admin.access_denied') }}","{{ __('admin.user_not_has_permission') }}","{{ __('admin.ok') }}");
                        return false;

                    }
                    url = "{{ route('dashboard-group-place-order-ajax') }}"
                }
                $.ajax({
                    url: url,
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function(successData) {
                        $('#placeOrderBtn').attr('disabled', false);
                        $('.showPlaceOrderModal'+quoteId).show();
                        $('#orderplaceloader'+quoteId).addClass('hidden');
                        that.attr('disabled', false);
                        if (successData.success) {
                            if (is_group_rfq==1){
                                location.href = successData.invoice_url;
                                return;
                            }
                            new PNotify({
                                text: "{{ __('dashboard.order_place_success_message') }}",
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 1000
                            });
                            $("#placeOrderAddressForm")[0].reset();
                            $('#placeOrderModal').modal('hide');
                            $('.showPlaceOrderModal' + quoteId).remove();
                            $('#myOrderCount').html(successData.ordersCount);
                            $('#mainContentSection').html(successData.html);
                            $('html, body').animate({
                                scrollTop: $("#mainContentSection").offset().top
                            }, 50);
                            $('#collapse' + successData.lastOrderId).collapse('show');
                            $('#rfqSection').removeClass('btnactive');
                            $('#addressSection').removeClass('btnactive');
                            $('#postRequirementSection').removeClass('btnactive');
                            $('#orderSection').addClass('btnactive');
                        }else{
                            if (is_group_rfq==1 || successData.message) {
                                new PNotify({
                                    text: successData.message,
                                    type: 'warning',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 3000
                                });
                            }else{
                                new PNotify({
                                    text: "{{ __('admin.something_went_wrong') }}",
                                    type: 'warning',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 3000
                                });
                            }
                        }
                    },
                    error: function() {
                        that.attr('disabled', false);
                        $('#placeOrderBtn').attr('disabled', false);
                        $('.showPlaceOrderModal'+quoteId).show();
                        $('#orderplaceloader'+quoteId).addClass('hidden');
                        console.log('error');
                    }
                });
            }
        });
        SnippetSearchList.init()
    });

    //Redirect to profile - company info tab
    function redirectToProfile() {
        sessionStorage.clear();
        sessionStorage.setItem("profile-lastlocation", JSON.stringify({
            mainTab: "company-tab",
            secondTab: "change_company_info"
        }));
        window.location='{{ route("profile") }}'
    }

    $(document).ready(function () {
        $('.editmenu_section .btn-group').hover(function () {
            $(this).find('.dropdown-menu').first().stop(true, true).slideDown(150);
        }, function () {
            $(this).find('.dropdown-menu').first().stop(true, true).slideUp(105)
        });
    });


    // Add as a "preferred supplier" script (Ronak M - 04/07/2022)
    $(document).on('click', '#addPreferredSupplierChk', function (e) {
        let checkBoxValue = $(this).val();
        let supplierId = $(this).attr('data-supplier-id');
        let rfqId = $(this).attr('data-rfq-id');
        var data = {
            checkBoxValue : checkBoxValue,
            supplierId : supplierId,
            _token: $('meta[name="csrf-token"]').attr("content"),
        };
        swal({
            title: "{{ __('admin.delete_sure_alert') }}",
            text: "{{ __('admin.added_as_preferred_supplier') }}?",
            icon: "/assets/images/info.png",
            buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
            dangerMode: false,
        }).then((changeit) => {
            if (changeit) {
                $.ajax({
                    url: "{{ route('add-preferred-supplier-ajax') }}",
                    data: data,
                    type: "POST",
                    success: function (successData) {
                        new PNotify({
                            text: successData.response,
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 2000
                        });
                        setTimeout(function() {
                            $(".Rfq_Id"+rfqId).trigger('click');
                        }, 2000);
                    },
                    error: function () {
                        console.log("error");
                    },
                });
                $(".Rfq_Id"+rfqId).trigger('click');
            } else {
                $(this).prop('checked', false);
            }
        });
    });
    $('body').on('click', '#quote_compare', function() {
        var rfqId = $(this).attr('data-id');
        if($(this).prop('checked'))
        {
            $('#collapse' + rfqId + ' .quoteDetails').find("#subaccordion").addClass("d-none");
            $('#collapse' + rfqId + ' .compare_rfq_quote').find("#compare-quote").removeClass('d-none')
        }
        else{
            $('#collapse' + rfqId + ' .quoteDetails').find("#subaccordion").removeClass("d-none");
            $('#collapse' + rfqId + ' .compare_rfq_quote').find("#compare-quote").addClass('d-none')
        }
    });
    $('body').on('click', '.getApprovalCompare', function() {
        $(this).attr("disabled",true)
    });
    var SnippetSearchList = function () {

            //Show result section
            $(".showSearchData").hide();

            // Input box search with custom text
            searchData = function() {
                // $(document).on('click', '.approvalSearch', function(e) {
                $('.customSearch').click(function() {
                    let customSearch = $('#customSearch').val();
                    let customStatusSearch = $('#customStatusSearch').val();
                    if(customSearch || customStatusSearch) {
                        $.ajax({
                            url: "{{ route('dashboard-list-rfq-ajax') }}",
                            data: { customStatusSearch : customStatusSearch,customSearch : customSearch},
                            type: 'GET',
                            success: function (successData) {
                                addressSection = successData.html;
                                $('#mainContentSection').html(successData.html);
                                $('html, body').animate({
                                    scrollTop: $("#mainContentSection").offset()
                                }, 50);

                                //Show custom search count
                                if((successData.searchDataCount)) {
                                    $("#searchResultCount").html((successData.searchDataCount));
                                }

                                //Show result section
                                $(".showSearchData").show();

                            },
                            error: function () {
                                console.log('error');
                            }
                        });
                        $("#searchTextErr").html("");
                    } else {
                        $("#searchTextErr").html("Please enter valid keyword");
                        return false;
                    }
                });

                $('#customSearch').keypress(function(e) {
                    $("#searchTextErr").html("");
                    //Enter key pressed
                    if(e.keyCode == 13) {
                        $('.customSearch').click();       //Trigger search button click event
                    }
                });
            },
            //End

            //On clear searched text (cancel icon), we will get all the data
            clearSearchData = function() {
                $('input[type=search]').on('search', function () {
                    $('.customSearch').click();
                });
            },

            //Search by quote status dropdown
                customStatusSearch = function() {
                $('#customStatusSearch').change(function() {
                    let customStatusSearch = $('#customStatusSearch').val();
                    //alert('ssd');
                    //Call "searchData" function on change of quote status
                    $('.customSearch').click();

                });
            };

        return {
            init: function () {
                    searchData(),
                    clearSearchData(),
                    customStatusSearch()
            },
        }
    }(1);


</script>

