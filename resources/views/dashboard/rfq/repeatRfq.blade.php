<div class="accordion" id="Rfq_accordian">
    @if (count($userRfq))
        @foreach ($userRfq as $rfq)
            @php
                 $all_products = get_rfqProducts($rfq->id);
            @endphp
            <div class="accordion-item radius_1 mb-2">
                <h2 class="accordion-header d-flex bg-light" id="headingOne">
                    <button id="rfqupdatecollpse" class="accordion-button justify-content-between Rfq_Id{{ $rfq->id }} collapsed"
                            type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $rfq->id }}" aria-expanded="false"
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
                        <div class="font_sub px-3 datesection me-lg-5">
                            <span class="mb-1">{{ __('rfqs.date') }}:</span>
                            {{ date('d-m-Y H:i', strtotime($rfq->created_at)) }}
                        </div>
                    </button>
                    <div class="d-flex align-items-center p-2">
                        <a href="javascript:void(0)" id="repeatRfq_{{$rfq->id}}" class="repeatRfq btn btn-info btn-sm fs12"  data-rfq_id="{{$rfq->id}}" title="{{ __('dashboard.repeat_rfq')}}" role="button" data-isRepeatOrder="0">
                            {{__('dashboard.repeat')}}
                        </a>
                    </div>
                </h2>
                <div id="collapse{{ $rfq->id }}" data-id="{{ $rfq->id }}" data-type="rfq" class="accordion-collapse rfqCollapse collapse"
                     aria-labelledby="headingOne" data-bs-parent="#Rfq_accordian" style="">
                    <div class="accordion-body p-0">
                        <div class="container-fluid">
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
                                    <label>{{ __('rfqs.product') }}:</label>
                                    <div class="multipleproducthover">
                                        <div href="javascript:voic(0);"
                                             class="tooltiphtml w-auto">
                                            <span class="text-primary">{{ $all_products->count() }} {{ __('rfqs.products') }}</span>
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
                                                        @foreach($all_products as $key => $value)
                                                            <tr>
                                                                <td>{{ $value->category . ' - ' . $value->sub_category . ' - ' . $value->product }}</td>
                                                                <td>{{ $value->product_description }}</td>
                                                                <td class="text-end text-nowrap">{{ $value->quantity }} {{ $value->unit_name }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 pb-2">
                                    <label class="d-block">{{ __('rfqs.payment_terms') }}:</label>
                                    @if($rfq->is_require_credit)
                                        <span class="badge rounded-pill mt-1 bg-danger">{{ __('rfqs.credit') }}</span>
                                    @else
                                        <span class="badge rounded-pill mt-1 bg-success">{{ __('rfqs.advance') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('rfqs.comment') }}:</label>
                                    <div> {{ $rfq->comment ? $rfq->comment : ' - ' }}</div>
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
                            </div>
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
