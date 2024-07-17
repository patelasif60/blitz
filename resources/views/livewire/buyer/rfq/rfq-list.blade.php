<div>
@if (count($rfqs))
    @foreach ($rfqs as $key => $rfq)
        <div class="accordion-item radius_1 mb-2">
            <h2 class="accordion-header" id="headingOne{{$rfq->id}}">
                <button id="rfqupdatecollpse{{$rfq->id}}" class="js-accordian accordion-button justify-content-between collapsed Rfq_Id{{ $rfq->id }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $rfq->id }}" aria-expanded="true" aria-controls="collapse{{ $rfq->id }}" data-id="{{ $rfq->id }}">
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
                        {{ date('d-m-Y H:i', strtotime($rfq->created_at)) }}
                    </div>

                </button>
            </h2>
            <div id="collapse{{ $rfq->id }}" data-id="{{ $rfq->id }}" data-type="rfq" class="accordion-collapse collapse rfqCollapse" aria-labelledby="headingOne" data-bs-parent="#Rfq_accordian">
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
                        <div id="rfqform_view{{ $rfq->id }}" class="row rfqform_view bg-white py-3">
                            {{view('buyer/rfqs/livewireViewidRfq', ['rfq' => $rfq])}}
                        </div>
                    </div>
                    <div id="compare_rfq_quote{{ $rfq->id }}" class="compare_rfq_quote">
                        {{view('buyer/rfqs/livewirecompareQuote', ['rfq' => $rfq])}}
                    </div>
                    <div id="quoteDetails{{ $rfq->id }}" class="other_rfq_data quoteDetails">
                        {{view('buyer/rfqs/livewireListQuote', ['rfq' => $rfq])}}
                    </div>
                </div>
            </div>
        </div>
        @if($flag==1)
            <script type="text/javascript">
                jQuery(document).ready(function(){
                    var rfqId = {{ $rfq->id }}
                    $('#qouteListing'+rfqId).dataTable({searching: false, paging: false,"order": [],columnDefs: [{orderable: false,targets: "no-sort"}]});
                });
            </script>
        @endif
    @endforeach
    @if($rfqs->hasMorePages())
            <livewire:buyer.loadmore.load-more-items :page="$page" :perPage="$perPage" :favrfq="$favrfq" :searchedText="$searchedText" :status="$status" :total="$rfqs->totalRecord" :currentRecord="$rfqs->currentRecord" :key="'load-more-items-'.$page" />
    @else
        <div class="ms-auto text-end">
            <small class="text-muted">{{ __('order.showing') }}  1 {{ __('order.to') }} {{$rfqs->totalRecord}} {{ __('order.of') }} {{$rfqs->totalRecord}} {{ __('order.entries') }}</small>
        </div>
    @endif
@else
     <div class="col-md-12">
        <div class="alert alert-danger radius_1 text-center fs-6 fw-bold">{{ __('rfqs.No_rfq_found') }}</div>
    </div>
@endif
</div>
