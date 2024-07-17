@if($notifications)
    @foreach($notifications as $key => $value)
        @php

            $href = 'javascript:void(0)';
            if ($value->notification_type == 'order'){
                $href = route('notifications-list');
            }
            if ($value->notification_type == 'rfq'){
               /* $target = '#viewRfqModal';
                $targetId = $value->notification_type_id;*/
                $attr = 'data-bs-toggle=modal data-bs-target=#viewRfqModal data-id='.$value->notification_type_id;
                $class = 'viewRfqDetail';
            }
            if ($value->notification_type == 'quote'){
                //$targetId = $value->notification_type_id;
                $attr = 'data-bs-toggle=modal data-bs-target=#viewRfqModalnew data-id='.$value->notification_type_id;
                $class = 'vieQuoteDetail';
            }
            if ($value->notification_type == 'loan'){
                //$targetId = $value->notification_type_id;
                $attr = 'data-bs-toggle=modal data-bs-target=#viewRfqModalnew data-id='.$value->notification_type_id;
                $class = 'vieQuoteDetail';
            }
            if ($value->role_id == 3 || $value->role_id == 2){
                $userName = $value->firstname.' '.$value->lastname;
            } else {
                $userName = 'blitznet team';
            }
        @endphp
        <a href="{{ $href }}" class="dropdown-item preview-item {{ $class??'' }}" {{ $attr??'' }}>
            <div class="preview-item-content">
                @if($value->notification_type == 'rfq')
                    <p class="preview-subject fw-normal"> <strong>{{ $userName }}</strong> {{ __('admin.'.$value->translation_key) }} {{ __('admin.notification_of') }} {{ 'BRFQ-'.$value->notification_type_id }}</p>
                @endif
                @if($value->notification_type == 'quote')
                    <p class="preview-subject fw-normal"> <strong>{{ $userName }}</strong> {{ __('admin.'.$value->translation_key) }} {{ __('admin.notification_of') }} {{ 'BQTN-'.$value->notification_type_id }}</p>
                @endif
                @if($value->notification_type == 'order')
                    @if($value->user_activity == 'Generate PO')
                        <p class="preview-subject fw-normal"> <strong>{{ $userName }}</strong> {{ __('admin.'.$value->translation_key) }} : {{ 'BORN-'.$value->notification_type_id }}</p>
                    @endif
                    @if($value->user_activity == 'Upload Order Letter' || $value->user_activity == 'Upload Tex Receipt' || $value->user_activity == 'Upload Invoice')
                        <p class="preview-subject fw-normal"> <strong>{{ $userName }}</strong> {!!  sprintf(__('admin.'.$value->translation_key), 'BORN-'.$value->notification_type_id) !!}</p>
                    @endif
                    @if($value->user_activity == 'Place Order')
                        <p class="preview-subject fw-normal"> <strong>{{ $userName }}</strong> {{ __('admin.'.$value->translation_key) }} {{ __('admin.notification_of') }} {{ 'BORN-'.$value->notification_type_id }}</p>
                    @endif
                    @if($value->user_activity == 'Change Order Status')
                        @php
                            $dataDecode = json_decode($value->common_data);
                            $orderStatus_change = [];
                            if ($dataDecode->is_credit){
                                $orderStatus_change = $orderStatus->sortBy('credit_sorting')->values()->pluck('name', 'id')->toArray();
                            } else {
                                $orderStatus_change = $orderStatus->sortBy('show_order_id')->values()->pluck('name', 'id')->toArray();
                            }
                            $old_key = isset($dataDecode->old_key) && !empty($dataDecode->old_key) ? __('order.' . trim($orderStatus_change[$dataDecode->old_key])) : '';
                            $new_key = isset($dataDecode->new_key) && !empty($dataDecode->new_key) ? __('order.' . trim($orderStatus_change[$dataDecode->new_key])) : '';

                            if ((isset($dataDecode->old_key) && !empty($dataDecode->old_key) && $dataDecode->old_key == 8) || (isset($dataDecode->new_key) && !empty($dataDecode->new_key) && $dataDecode->new_key == 8)){
                                $orderDueDate = \App\Models\Order::find($value->notification_type_id)->payment_due_date;
                                $old_key = ($dataDecode->old_key == 8) ? sprintf(__('order.' . trim($orderStatus_change[$dataDecode->old_key])), changeDateFormat($orderDueDate,'d/m/Y')) : $old_key;
                                $new_key = ($dataDecode->new_key == 8) ? sprintf(__('order.' . trim($orderStatus_change[$dataDecode->new_key])),changeDateFormat($orderDueDate,'d/m/Y')) : $new_key;
                            }
                        @endphp
                        <p class="preview-subject fw-normal"><strong>{{ $userName }}</strong> {!!  sprintf(__('admin.'.$value->translation_key), 'BORN-'.$value->notification_type_id) !!} {{ $old_key }} {{ __('admin.to').' '.$new_key }}</p>
                    @endif
                    @if($value->user_activity == 'Change Order Item Status')
                        @php
                            $dataDecode = json_decode($value->common_data);
                            $orderItemStatus_change = $OrderStatusItems->sortBy('sort')->values()->pluck('name', 'id')->toArray();
                            $old_key = isset($dataDecode->old_key) && !empty($dataDecode->old_key) ? __('order.' . trim($orderItemStatus_change[$dataDecode->old_key])) : '';
                            $new_key = isset($dataDecode->new_key) && !empty($dataDecode->new_key) ? __('order.' . trim($orderItemStatus_change[$dataDecode->new_key])) : '';
                            $orderItemId = isset($dataDecode->order_item) && !empty($dataDecode->order_item) ? $dataDecode->order_item: '';
                        @endphp
                        <p class="preview-subject fw-normal">
                            <strong>{{ $userName }}</strong> {!!  sprintf(__('admin.'.$value->translation_key), $orderItemId) !!} {{ $old_key }}
                            @if(!empty($old_key))
                                {{ __('admin.to').' '.$new_key }}
                            @else
                                {{ $new_key }}
                            @endif
                        </p>
                    @endif
                    @if($value->user_activity == 'Manage order delivery separately')
                        <p class="preview-subject fw-normal"> <strong>{{ $userName }}</strong> {{ __('admin.'.$value->translation_key) }} {{ __('admin.notification_of') }} {{ 'BORN-'.$value->notification_type_id }}</p>
                    @endif
                    @if($value->user_activity == 'Airway bill ganreted')
                        @php
                            $dataDecode = json_decode($value->common_data);
                            $old_key = $dataDecode->old_key;
                            $new_key = $dataDecode->new_key;
                        @endphp
                        <p class="preview-subject fw-normal"> <strong>{{ $userName }}</strong> {{ __('admin.'.$value->translation_key) }}  {{$new_key}} {{ __('admin.against') }} {{ $old_key }} : {{ 'BORN-'.$value->notification_type_id }}</p>
                    @endif
                @endif

                @if($value->notification_type == 'loan' || $value->notification_type == 'limit')
                    @php
                        $dataDecode = json_decode($value->common_data);
                        $loan_number = $dataDecode->loan_number??'';
                        $limit_number = $dataDecode->limit_number??'';
                    @endphp

                    @if($value->notification_type == 'loan')
                        <p class="preview-subject fw-normal"> <strong>{{ $userName }}</strong> {{ __('admin.'.$value->translation_key) }} {{ __('admin.notification_of') }} {{ $loan_number }}</p>
                    @elseif($value->notification_type == 'limit')
                        <p class="preview-subject fw-normal"> <strong>{{ $userName }}</strong> {{ __('admin.'.$value->translation_key) }} {{ __('admin.notification_of') }} {{ $limit_number }}</p>
                    @endif
                @endif

                <p class="fw-light small-text mb-0 text-muted">
                    {{ \Carbon\Carbon::parse($value->created_at)->format('d-m-Y H:i:s') }}
                </p>
            </div>
        </a>
    @endforeach
    <a href="{{ route('notifications-list') }}" class="dropdown-item preview-item">
        <div class="preview-item-content w-100">
            <p class="fw-light small-text mb-0 text-muted text-center ">
                View All
            </p>
        </div>
    </a>
@endif
