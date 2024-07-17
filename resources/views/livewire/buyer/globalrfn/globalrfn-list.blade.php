<div wire:key="global-rfn-list-section">
    {{-- The best athlete wants his opponent at his best. --}}

    @if(isset($rfnList) && count($rfnList) > 0)
        @foreach($rfnList as $rfnData)
            <div class="accordion-item radius_1 mb-2 rfnGlobalListingcollpse">
                <h2 class="accordion-header" id="headingOne{{$rfnData->id}}">
                    <button id="rfnGlobalListingcollpse{{$rfnData->id}}" rfn-id="{{$rfnData->id}}" class="accordion-button justify-content-between rfnGlobalCollapseToggle collapsed Rfq_Id{{$rfnData->id}}" type="button" data-bs-toggle="collapse" data-bs-target="#globalcollapse{{$rfnData->id}}" aria-expanded="false" aria-controls="collapse{{$rfnData->id}}">
                        <div class="flex-grow-1 text-muted">
                            <span class=" text-dark">{{isset($rfnData->rfnItem->product_fullname) ? $rfnData->rfnItem->product_fullname : ''}}</span>
                            <span id="badgeColorSpan4014" class="badge rounded-pill ms-2 mt-1 {{ $rfnData->status_color }}"> {{ $rfnData->status_name }}</span>
                        </div>
                        <div class="text-dark mx-1"><span class="badge rounded-pill bg-danger">{{$rfnData->remaining_days .' '.__('buyer.days_remaining')}} </span></div>

                        <div class="text-dark mx-1"><span class="badge rounded-pill bg-success">{{ $rfnData->strictResponses->count().' '.__('buyer.people_joined') }}</span></div>
                        <div class="text-dark ms-1 me-2">
                            <span class="badge rounded-pill bg-info">{{isset($rfnData->rfnItems) ? $rfnData->rfnItems->sum('quantity') : '0'}} {{isset($rfnData->rfnItem->unit->name) ? $rfnData->rfnItem->unit->name : ''}}</span>
                        </div>
                    </button>
                </h2>
                <div id="globalcollapse{{$rfnData->id}}" data-id="{{$rfnData->id}}" data-type="rfn" class="accordion-collapse collapse rfnglobalCollapse" aria-labelledby="headingOne{{$rfnData->id}}" data-bs-parent="#Rfn_accordian" wire:ignore.self>
                    <div class="accordion-body p-0">
                        <div class="container-fluid">
                            <div class="editmenu_section">
                                @if($rfnData->status ==\App\Models\Rfn::PENDING_STATUS)
                                @canany('buyer global rfn update','buyer global rfn cancel')
                                    <div class="btn-group">
                                        <button type="button" class="btn dropdown-toggle btn-sm h1 editGlobalRfnBtn" data-id="{{$rfnData->id}}" data-ref="{{$rfnData->reference_number}}" data-bs-toggle="dropdown" aria-expanded="false" >&#8942;</button>
                                        <ul class="dropdown-menu dropdown-menu-end py-0">
                                            @can('buyer global rfn update')
                                                <li><a href="javascript:void(0)" class="dropdown-item py-2" data-id="{{$rfnData->id}}"  data-bs-toggle="modal" data-bs-target="#editRfnModal" id="editGlobalRfnBtn"> <img src="{{ URL::asset('front-assets/images/icons/icon_edit_add.png') }}" alt="Edit" style="max-height: 13px;" class="pe-1"> {{ __('rfqs.edit') }}</a></li>
                                            @endcan
                                            @can('buyer global rfn cancel')
                                                <li><a href="javascript:void(0)" class="dropdown-item py-2 cancelGlobalRfnBtn" data-id="{{$rfnData->id}}" id="cancelGlobalRfnBtn"> <img src="{{ URL::asset('front-assets/images/icons/icon_delete_add.png') }}" alt="Cancel" style="max-height: 13px;" class="pe-1"> {{ __('rfqs.cancel') }}</a></li>
                                            @endcan
                                        </ul>
                                    </div>
                                @endcan
                                @endif
                            </div>
                            <div class="row rfqform_view py-3">
                                <div class="col-md-4 my-2">
                                    <label>{{__('buyer.created_by')}}: </label>
                                    <div class="text-wrap">{{isset($rfnData->userable) ? $rfnData->userable->full_name : ''}}</div>
                                </div>

                                <div class="col-md-4 my-2">
                                    <label>{{__('buyer.start_date')}}: </label>
                                    <div>{{isset($rfnData->start_date) ? \Carbon\Carbon::parse($rfnData->start_date)->format('d-m-Y') : ''}}</div>
                                </div>
                                <div class="col-md-4 my-2">
                                    <label>{{__('buyer.end_date')}}: </label>
                                    <div>{{isset($rfnData->end_date) ? \Carbon\Carbon::parse($rfnData->end_date)->format('d-m-Y') : '' }}</div>
                                </div>
                                <div class="col-md-4 my-2">
                                    <label class="d-block">{{__('admin.total_days_left')}}:</label>
                                    <div class="text-dark">{{ $rfnData->remaining_days }}</div>
                                </div>
                                <div class="col-md-4 my-2">
                                    <label class="d-block">{{__('admin.total_people_joined')}}:</label>
                                    <div class="text-dark">{{ $rfnData->strictResponses->count().' '.__('buyer.joined') }}</div>
                                </div>
                                <div class="col-md-4 my-2">
                                    <label class="d-block">{{__('admin.total_quantity')}}:</label>
                                    <div class="text-dark">{{isset($rfnData->rfnItems) ? $rfnData->rfnItems->sum('quantity') : '0'}} {{isset($rfnData->rfnItem->unit->name) ? $rfnData->rfnItem->unit->name : ''}}
                                    </div>
                                </div>

                                <div class="col-md-4 my-2">
                                    <label>{{__('admin.comment')}}: </label>
                                    <div class="text-wrap">{{ isset($rfnData->comment) ? $rfnData->comment : '' }}</div>
                                </div>

                                <div class="col-md-8 my-2">
                                    <label>{{__('admin.product_description')}}: </label>
                                    <div class="text-wrap">{{ isset($rfnData->rfnItem->item_description) ? $rfnData->rfnItem->item_description : '' }}</div>
                                </div>
                                @canany(['buyer List RFR Request','buyer List All RFR Request'])
                                <div class="row mt-2 pe-0">
                                    <div class="col-md-12 pe-0">

                                        <table class="table table-striped-columns table-hover border rfn_list">
                                            <thead class="bg-light">
                                                <tr>
                                                    @if(isset($rfnData->rfnResponses) && count($rfnData->rfnResponses)>0 && $rfnData->status == 1)
                                                        @canany(['buyer global rfn multi convert to rfq','buyer global rfn to rfq'])
                                                            <th style="width: 25px;">
                                                                <div class="form-check mb-0" style="min-height: 1.2rem; ">
                                                                    <input class="form-check-input selectAllItems" type="checkbox" rfn-id="{{$rfnData->id}}" name="selectAllItems{{$rfnData->id}}" value='0' id="selectAllItems{{$rfnData->id}}">
                                                                </div>
                                                            </th>
                                                        @endcanany
                                                    @endif
                                                    <th>{{__('buyer.branches')}}</th>
                                                    <th>{{__('buyer.rfn_item_reference_no')}}</th>
                                                    <th class="text-end">{{__('buyer.quantity')}}</th>
                                                    <th class="text-end">{{__('buyer.expected_date')}}</th>
                                                    <th class="text-end">{{__('buyer.comment')}}</th>
                                                    <th  class="text-end pe-3">{{__('buyer.status')}}</th>
                                                    @if($rfnData->status == \App\Models\Rfn::PENDING_STATUS)
                                                    <th class="text-end">{{__('buyer.action')}}</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @if(isset($rfnData->rfnResponses) && count($rfnData->rfnResponses)>0)
                                            @foreach($rfnData->rfnResponses as $branchlist)
                                                <tr>

                                                    @if(isset($rfnData->rfnResponses) && count($rfnData->rfnResponses)>0)
                                                    @canany(['buyer global rfn multi convert to rfq','buyer global rfn to rfq'])
                                                    @if(empty($rfnData->rfq_id) && $rfnData->status == \App\Models\Rfn::PENDING_STATUS)
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input globalRfnItems" type="checkbox" name="globalRfnItems{{$rfnData->id}}" id="selectItems{{$branchlist->id}}" rfn-id="{{$rfnData->id}}" value="{{$branchlist->id}}">
                                                        </div>
                                                    </td>
                                                    @endif
                                                     @endcanany

                                                    <td>{{isset($branchlist->defaultCompanyUser->first()->branches) ? $branchlist->defaultCompanyUser->first()->branches : ''}}</td>
                                                    @endif
                                                    <td>{{isset($branchlist->rfnItem) ? $branchlist->rfnItem->reference_number : '-'}} </td>
                                                    <td class="text-end">{{(!empty($branchlist->rfnItem) ? $branchlist->rfnItem->quantity : '').' '.(!empty($branchlist->rfnItem->unit) ? $branchlist->rfnItem->unit->name : '')}} </td>
                                                    <td class="text-end">{{isset($branchlist->expected_date) ? \Carbon\Carbon::parse($branchlist->expected_date)->format('d-m-Y') : '-'}} </td>
                                                    <td class="text-end">{{isset($branchlist->rfnItem) ? $branchlist->rfnItem->item_description : '-'}} </td>
                                                    <td class="text-end">
                                                         <span class="badge rounded-pill bg-primary mt-1 {{ $branchlist->status_color }}" id="badgeColorSpan{{ $branchlist->id }}">
                                                             @if($branchlist->status == 3) Cancel @elseif($branchlist->status == 2) Approved @else Pending @endif
                                                         </span>
                                                    </td>
                                                    @if(empty($rfnData->rfq_id) && $rfnData->status == \App\Models\Rfn::PENDING_STATUS )
                                                    <td class="text-end">
                                                        <a href="javascript:void(0)" class="px-2 joinrfnEdit"  data-toggle="tooltip" ata-placement="top" title="Edit" data-rfnProductname="{{($rfnData->rfnItem->product_fullname)}}" data-rfn_reference="{{$rfnData->reference_number}}" data-rfn_id ="{{!empty($rfnData->id) ? $rfnData->id : ''}}" data-id="{{!empty($branchlist->rfnItem) ? $branchlist->rfnItem->id : ''}}" data-response_id="{{!empty($branchlist->id) ? $branchlist->id : ''}}" data-bs-toggle="modal" data-bs-target="#joinRfnModal" id="joinRfnModalBtn">
                                                            <img height="12px" src="{{ URL::asset('front-assets/images/icons/icon_edit_add.png') }}"></a>
                                                        <a href="javascript:void(0)" class="px-2 removeRfnRequest" data-rfn-id="{{!empty($branchlist->rfn_id) ? $branchlist->rfn_id : ''}}" data-response-id="{{!empty($branchlist->id) ? $branchlist->id : ''}}" data-toggle="tooltip" ata-placement="top" title="Delete">
                                                            <img height="12px" src="{{ URL::asset('front-assets/images/icons/icon_delete_add.png') }}">
                                                        </a>
                                                        <input type="hidden" name="countResponse" id="countResponse{{$branchlist->id}}" value="{{count($rfnData->rfnResponses)}}">
                                                    </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            @else
                                                <tr>
                                                    <td class="text-center" colspan="5"> {{__('buyer.no_response_found')}}</td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                        <div class="col-md-12 d-flex justify-content-between p-0">
                                            <div class="redirect_rfn ms-auto">
                                              @can(['buyer global rfn to rfq', 'create buyer rfqs'])
                                                @if(isset($rfnData->rfnResponses) && count($rfnData->rfnResponses)>0 && $rfnData->status == 1)
                                                <a href="javascript:void(0);" class="btn btn-warning btn-sm ms-2 generateMultiRfq" rfn-id="{{$rfnData->id}}" name="generateMultiRfq">
                                                    <span class="me-1">
                                                        <img src="{{ URL::asset('front-assets/images/icons/icon_rfq.png') }}" height="16px">
                                                    </span>
                                                    <span class=" d-md-inline-block" id="gt_btn">{{__('buyer.generate_rfq')}}</span>
                                                </a>
                                                @endif
                                              @endcan
                                              @can('buyer request global rfn')
                                                @if($rfnData->rfnResponses->where('user_id',Auth::user()->id)->count() == 0 && $rfnData->status == \App\Models\Rfn::PENDING_STATUS && $rfnData->remaining_days != 0)
                                                    <a href="#" class="btn btn-primary btn-sm ms-2 joinRfnRequest"  data-bs-toggle="modal" data-id="{{$rfnData->id}}" data-rfnProductname="{{($rfnData->rfnItem->product_fullname)}}" data-rfn_reference="{{$rfnData->reference_number}}"  data-bs-target="#joinRfnModal" id="joinRfnModalBtn">
                                                        <span class="me-1">
                                                            <img src="{{ URL::asset('front-assets/images/icons/layer-group.png') }}" alt="">
                                                        </span>
                                                        <span class=" d-md-inline-block" id="gt_btn">{{__('buyer.join_rfn')}}</span>
                                                    </a>
                                                @endif
                                              @endcan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endcanany
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-md-12">
            <div class="alert alert-danger radius_1 text-center fs-6 fw-bold" id="no_rfq_alert">{{ __('rfqs.no_rfn_found') }}</div>
        </div>
    @endif
</div>

@push('globalrfn-list')
    <script type="text/javascript">
        var SnippetGlobalRfnList = function () {
            var deleteRfnRequest = function () {
                $(document).on('click', '.removeRfnRequest', function () {
                    var rfnResponseId = $(this).data('response-id');
                    var rfnId = $(this).data('rfn-id');
                    var responseCount = $('#countResponse'+rfnResponseId).val();
                    if(responseCount <= 1){
                        var text = '{{__('buyer.delete_last_rfn_request_alert')}}';
                    }else{
                        var text = '{{__('buyer.delete_rfn_request_alert')}}';
                    }
                    swal({
                        title: "{{ __('dashboard.are_you_sure') }}?",
                        text: text,
                        icon: "/assets/images/bin.png",
                        buttons: ['{{ __('admin.no') }}', '{{ __('admin.yes') }}'],
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            window.livewire.emit('deleteRfnRequest',rfnId,rfnResponseId);

                            responseCount = responseCount - 1;
                            $('#countResponse'+rfnResponseId).val(responseCount);
                        }
                    });
                });
            },
            cancelGlobalRfn = function () {
                $(document).on('click', '.cancelGlobalRfnBtn', function () {
                    var rfnId = $(this).data('id');
                    swal({
                        title: "{{ __('dashboard.are_you_sure') }}?",
                        text: '{{__('buyer.cancel_rfn_alert')}}',
                        icon: "/assets/images/bin.png",
                        buttons: ['{{ __('admin.no') }}', '{{ __('admin.yes') }}'],
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            window.livewire.emit('cancelGlobalRfn',rfnId);
                        }
                    });
                });
            },
             /*** Click on All checkbox to select all checkboxes*/
            getCheckedAllResponseData = function () {
                $(document).on('click', '.selectAllItems', function () {
                    var RfnId = $(this).attr('rfn-id');
                    if ($('input:checkbox[name=selectAllItems'+RfnId+']').val() == 0) {
                        $('input:checkbox[name=selectAllItems'+RfnId+']').val('1');
                        $('input:checkbox[name=globalRfnItems'+RfnId+']').prop("checked", true);
                    } else {
                        $('input:checkbox[name=selectAllItems'+RfnId+']').val('0');
                        $('input:checkbox[name=globalRfnItems'+RfnId+']').prop('checked', false);
                    }
                });
            },
            /*** Click on Single checkbox*/
            getCheckedSingleResponseData = function () {
                $(document).on('click', '.globalRfnItems', function () {
                    var RfnId = $(this).attr('rfn-id');
                    if ($('input:checkbox[name=globalRfnItems'+RfnId+']').filter(':checked').length == $('input:checkbox[name=globalRfnItems'+RfnId+']').length) {
                        $('input:checkbox[name=selectAllItems'+RfnId+']').val('1');
                        $('#selectAllItems'+RfnId).prop('checked', true);
                    } else {
                        if ($(this).prop("checked") == false) {
                            $('#selectAllItems'+RfnId).prop('checked', false);
                            $('input:checkbox[name=selectAllItems'+RfnId+']').val('0');
                        }
                    }
                })
            },
            /**
             * Global Rfn refresh data
             */
            globalRefreshData = function (){
                $(document).on('click', '.rfnGlobalCollapseToggle', function () {
                    var RfnId = $(this).attr('rfn-id');
                    if (RfnId != '') {
                        $('.rfnglobalCollapse').removeClass('show');
                        $('#rfnGlobalListingcollpse'+RfnId).removeClass('collapsed');
                        $('#rfnGlobalListingcollpse'+RfnId).toggleClass('show');
                        window.livewire.emit('globalRefreshData');
                    }

                });
            },
            generateMultiRfq = function () {
                $('.generateMultiRfq').on('click', function () {
                    var RfnId = $(this).attr('rfn-id');
                    var RfnItemIds = [];
                    $("input:checkbox[name=globalRfnItems"+RfnId+"]:checked").each(function() {
                        RfnItemIds.push($(this).val());
                    });
                    if(RfnItemIds.length == 0){
                        swal({
                            title: '{{ __('admin.verify_data') }}',
                            text: 'Select atlease one checkbox',
                            icon: "/assets/images/info.png",
                            buttons: '{{ __('admin.ok') }}',
                            dangerMode: false,
                        });
                        return false;
                    }else{
                        swal({
                            title: "{{ __('dashboard.are_you_sure') }}?",
                            text: '{{__('buyer.generate_rfq_text')}}',
                            icon: "/assets/images/info.png",
                            buttons: ['{{ __('admin.no') }}', '{{ __('admin.yes') }}'],
                            dangerMode: true,
                        }).then((res) => {
                            if (res) {
                                window.livewire.emit('generateMultiRfq',RfnId,RfnItemIds);
                                sessionStorage.clear();
                            } else {
                                return false;
                                sessionStorage.clear();
                            }
                        });
                    }

                });
            };

            return {
                init: function () {
                    deleteRfnRequest(),
                    cancelGlobalRfn(),generateMultiRfq(),
                    getCheckedAllResponseData(),
                    getCheckedSingleResponseData(),
                    globalRefreshData()
                }
            }
        }(1);

        jQuery(document).ready(function () {
            SnippetGlobalRfnList.init();
        });
    </script>
@endpush
