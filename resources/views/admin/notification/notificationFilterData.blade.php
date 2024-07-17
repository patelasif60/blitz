<!-- loop start -->
@if(count($notifications)>0)
@foreach($notifications as $key => $values)

    <div class="col-md-12 mb-4">
        <h6 class="text-muted">{{ \Carbon\Carbon::today()->format('d-m-yy') == \Carbon\Carbon::parse($key)->format('d-m-yy') ? 'Today' : \Carbon\Carbon::parse($key)->format('d M Y') }}</h6>
        <div class="card">
            <div class="card-body p-3">
                @foreach($values as $value)
                    @php
                        if ($value->role_id == 3 || $value->role_id == 2){
                                $userName = $value->firstname.' '.$value->lastname;
                            } else {
                                $userName = 'blitznet team';
                            }
                    @endphp
                    <div class="row align-items-center notification_sections">
                                <div class="col-auto pe-0 d-flex align-items-center py-2">
                                    <i class="mdi mdi-bell" style="height: 15px;"></i>
                                </div>
                                <div class="col-auto d-md-flex flex-grow-1  align-items-center py-2 ">
                                    @if($value->notification_type == 'rfq')
                                    <p class="h6 mb-0"> <strong>{{ $userName }}</strong> {{ __('admin.'.$value->translation_key) }} {{ __('admin.notification_of') }}
                                        <a href="javascript:void(0);" style="text-decoration: none" class="viewRfqDetail hover_underline" data-bs-toggle="modal" data-bs-target="#viewRfqModal" data-id="{{ $value->notification_type_id }}">{{ 'BRFQ-'.$value->notification_type_id }}</a>
                                        {{--<a href="{{ route('rfq-list') }}" class="text-dark">{{ 'BRFQ-'.$value->notification_type_id }}</a>--}}
                                    </p>
                                    @endif
                                    @if($value->notification_type == 'quote')
                                        <p class="h6 mb-0"> <strong>{{ $userName }}</strong> {{ __('admin.'.$value->translation_key) }} {{ __('admin.notification_of') }}
                                           <a href="javascript:void(0);" style="text-decoration: none;" class="vieQuoteDetail hover_underline" data-bs-toggle="modal" data-bs-target="#viewRfqModalnew" data-id="{{ $value->notification_type_id }}">{{ 'BQTN-'.$value->notification_type_id }}</a>
                                            {{-- <a href="{{ route('quotes-list') }}" class="text-dark">{{ 'BQTN-'.$value->notification_type_id }}</a>--}}
                                        </p>
                                    @endif
                                    @if($value->notification_type == 'order')
                                        @if($value->user_activity == 'Generate PO')
                                            <p class="h6 mb-0"> <strong>{{ $userName }}</strong> {{ __('admin.'.$value->translation_key) }} : <a class="hover_underline" href="{{ route('order-edit', ['id' => Crypt::encrypt($value->notification_type_id)]) }}" style="text-decoration: none;" >{{ 'BORN-'.$value->notification_type_id }}</a></p>
                                        @endif
                                        @if($value->user_activity == 'Upload Order Letter' || $value->user_activity == 'Upload Tex Receipt' || $value->user_activity == 'Upload Invoice')
                                            <p class="h6 mb-0"> <strong>{{ $userName }}</strong> {!!  sprintf(__('admin.'.$value->translation_key), '<a style="text-decoration: none;" class="hover_underline" href="'.route('order-edit', ['id' => Crypt::encrypt($value->notification_type_id)]).'" >BORN-'.$value->notification_type_id.'</a>') !!}</p>
                                        @endif
                                        @if($value->user_activity == 'Place Order')
                                            <p class="h6 mb-0"> <strong>{{ $userName }}</strong> {{ __('admin.'.$value->translation_key) }} {{ __('admin.notification_of') }} <a style="text-decoration: none; " class="hover_underline" href="{{ route('order-edit', ['id' => Crypt::encrypt($value->notification_type_id)]) }}" >{{ 'BORN-'.$value->notification_type_id }}</a></p>
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
                                            <p class="h6 mb-0">
                                                <strong>{{ $userName }}</strong> {!!  sprintf(__('admin.'.$value->translation_key), '<a style="text-decoration: none; " class="hover_underline" href="'.route('order-edit', ['id' => Crypt::encrypt($value->notification_type_id)]).'" >BORN-'.$value->notification_type_id.'</a>') !!} {{ $old_key }} {{ __('admin.to').' '.$new_key }}
                                            </p>
                                        @endif
                                        @if($value->user_activity == 'Change Order Item Status')
                                                @php
                                                    $dataDecode = json_decode($value->common_data);
                                                    $orderItemStatus_change = $OrderStatusItems->sortBy('sort')->values()->pluck('name', 'id')->toArray();
                                                    $old_key = isset($dataDecode->old_key) && !empty($dataDecode->old_key) ? __('order.' . trim($orderItemStatus_change[$dataDecode->old_key])) : '';
                                                    $new_key = isset($dataDecode->new_key) && !empty($dataDecode->new_key) ? __('order.' . trim($orderItemStatus_change[$dataDecode->new_key])) : '';
                                                    $orderItemId = isset($dataDecode->order_item) && !empty($dataDecode->order_item) ? $dataDecode->order_item: '';
                                                @endphp
                                                <p class="h6 mb-0">
                                                    <strong>{{ $userName }}</strong> {!!  sprintf(__('admin.'.$value->translation_key), '<a style="text-decoration: none;" href="'.route('order-edit', ['id' => Crypt::encrypt($value->notification_type_id)]).'" class="hover_underline">'.$orderItemId.'</a>') !!} {{ $old_key }}
                                                    @if(!empty($old_key))
                                                        {{ __('admin.to').' '.$new_key }}
                                                    @else
                                                        {{ $new_key }}
                                                    @endif
                                                </p>
                                        @endif
                                         @if($value->user_activity == 'Manage order delivery separately')
                                            <p class="h6 mb-0""> <strong>{{ $userName }}</strong> {{ __('admin.'.$value->translation_key) }} {{ __('admin.notification_of') }} <a href="javascript:void(0);" style="text-decoration: none;" class="vieQuoteDetail hover_underline" data-bs-toggle="modal" data-bs-target="#viewRfqModalnew" data-id="{{ $value->notification_type_id }}">{{ 'BQTN-'.$value->notification_type_id }}</a></p>
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
                                            $lNumber = '';
                                            $aClass = '';
                                            if($value->notification_type == "loan"){
                                                $lNumber =  $dataDecode->loan_number ?? '';
                                                $aClass = 'viewLoanDetail';
                                            }elseif($value->notification_type == "limit"){
                                                $lNumber =  $dataDecode->limit_number;
                                                $aClass = 'viewLimitDetail';
                                            }
                                        @endphp
                                        
                                        <p class="h6 mb-0"> <strong>{{ $userName }}</strong> {{ __('admin.'.$value->translation_key) }} {{ __('admin.notification_of') }}
                                            <a href="javascript:void(0);" style="text-decoration: none;" class="{{$aClass}} hover_underline" data-bs-toggle="modal" data-bs-target="#viewLoanModal" data-id="{{Crypt::encrypt($value->notification_type_id) }}">{{ $lNumber }}</a>
                                        </p>
                                    @endif
                                    <p class="ms-auto mb-0"><small class="d-flex align-items-top">
                                        <i class="mdi mdi-clock-outline me-1"></i>
                                        {{ \Carbon\Carbon::parse($value->created_at)->format('d-m-Y H:i:s') }}
                                    </small></p>
                                </div>
                            </div>
                @endforeach
            </div>
        </div>
    </div>
@endforeach
@else
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row ">
                    <div class="col-auto d-flex flex-grow-1 justify-content-center ">
                        <p class="h6 mb-0">{{ __('admin.no_notification') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endif
<!-- loop end -->
