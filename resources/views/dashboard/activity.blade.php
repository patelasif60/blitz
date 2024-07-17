@if ($userActivity)
    <div class="notification_cont d-flex pt-1 fw-bold mb-2 "><h6>{{ __('admin.notification') }}</h6>
        <div class="ms-auto form-check d-none form-switch">
            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
            <label class="form-check-label" for="flexSwitchCheckDefault"
                   style="padding-top: 2px; font-size: 11px;">Do Not Disturb</label>
        </div>
    </div>
    @foreach ($userActivity->take(5) as $activity)
        @php
            $commanData = json_decode($activity->common_data);
            $commanNumber = '';
            $rfq_id = 0;
            $icons = $commanData->icons??'';
            $userName = $commanData->updated_by??'';
            if ($activity->notification_type == 'rfq'){
                $commanNumber = $commanData->rfq_number??'';
            }
            if ($activity->notification_type == 'quote'){
                $commanNumber = $commanData->quote_number;
                $quoteValiteDate = $commanData->valid_till_date??'';
                $rfq_id = $commanData->rfq??0;
            }
            if ($activity->notification_type == 'order'){
                $commanNumber = $commanData->order_number;
                $orderStatus = $commanData->order_status??'';
                $orderDueDate = $commanData->payment_due_date??'';
                $orderItemNumber = $commanData->order_item_number??'';
            }
            if ($activity->notification_type == 'loan'){
                $commanNumber = $commanData->loan_number??'';
                $loanStatus = $commanData->status??'';
            }
            if ($activity->notification_type == 'limit'){
                $commanNumber = $commanData->limit_number??'';
                $loanStatus = $commanData->status??'';
            }

        @endphp
        <li class="{{ ($activity->is_show == 0)? 'recent': '' }}">
            <div class="p-2 userActivityPageRedirect d-flex align-items-center" data-id="{{ $rfq_id ? $rfq_id : $activity->notification_type_id }}" data-type="{{ $rfq_id ? 'rfq' : $activity->notification_type }}" data-quote_id="{{ $rfq_id ? $activity->notification_type_id : 0 }}" data-notification_id="{{ $activity->id }}">
                <div class="pe-2 ">
                    <span class="icon_bg"><i class="fa {{ $icons }}"></i></span>
                </div>
                @if(isset($orderStatus) && !empty($orderStatus))
                    @if(empty($orderDueDate) && $activity->translation_key == 'buyer_inner_status_change')
                        <div class="fw-bold"> {!! sprintf(__('dashboard.'.$activity->translation_key), $commanNumber, $orderItemNumber, __('order.'.$orderStatus), $userName) !!} <br> <small class="text-muted">{{ date('d-m-Y H:i:s', strtotime($activity->created_at)) }}</small></div>
                    @else
                        <div class="fw-bold"> {!! sprintf(__('dashboard.'.$activity->translation_key), $commanNumber, sprintf(__('order.'.$orderStatus), $orderDueDate), $userName) !!} <br> <small class="text-muted">{{ date('d-m-Y H:i:s', strtotime($activity->created_at)) }}</small></div>
                    @endif
                @elseif (!empty($quoteValiteDate))
                    <div class="fw-bold"> {!! sprintf(__('dashboard.'.$activity->translation_key), $commanNumber, $quoteValiteDate, $userName) !!} <br> <small class="text-muted">{{ date('d-m-Y H:i:s', strtotime($activity->created_at)) }}</small></div>
                @elseif ($activity->notification_type == 'other')
                    <div class="fw-bold"> {!! sprintf(__('dashboard.'.$activity->translation_key), $userName) !!} <br> <small class="text-muted">{{ date('d-m-Y H:i:s', strtotime($activity->created_at)) }}</small></div>
                @elseif ($activity->notification_type == 'loan' || $activity->notification_type == 'limit')
                    @if($activity->notification_type == 'loan')
                        <div class="fw-bold"> {{__('admin.loan')}} - {{$commanNumber}} {!! sprintf(__('dashboard.'.$activity->translation_key), $userName == "admin admin" ? "Blitznet Team" : $userName ) !!} <br> <small class="text-muted">{{ date('d-m-Y H:i:s', strtotime($activity->created_at)) }}</small></div>
                    @elseif($activity->notification_type == 'limit')
                        <div class="fw-bold"> {{$commanNumber}} {!! sprintf(__('dashboard.'.$activity->translation_key), $userName == "admin admin" ? "Blitznet Team" : $userName ) !!} <br> <small class="text-muted">{{ date('d-m-Y H:i:s', strtotime($activity->created_at)) }}</small></div>
                    @endif
                @else
                    <div class="fw-bold"> {!! sprintf(__('dashboard.'.$activity->translation_key), $commanNumber, $userName) !!} <br> <small class="text-muted">{{ date('d-m-Y H:i:s', strtotime($activity->created_at)) }}</small></div>
                @endif
            </div>
        </li>
    @endforeach
    <div class="notification_cont1_end d-flex pt-2">
        <a href="javascript:void(0)" class="fw-bold mark_read_btn text-decoration-none" onclick="markAsAll(event)" >Mark all as read</a>
        <a href="{{ route('notification') }}" class="fw-bold ms-auto pe-2 text-decoration-none mark_read_btn1" >View All</a>
    </div>
@else
    <div class="no_account_update">
        <img class="empty hide_mobile" src="{{ URL::asset('assets/images/front/updates_icon.png') }}" alt="">
        <p class="na">No New Updates</p>
    </div>
@endif

