@if($orderactivities)
<div class="card mb-3">
<div class="card-header d-flex align-items-center">
                <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/icon_update_activities.png')}}" alt="Charges" class="pe-2"> <span>{{ __('admin.recent_activities') }}</span></h5>
</div>
<div class="card-body p-3 pb-1">
    <ul class="bullet-line-list">
    @foreach($orderactivities as $orderactivity)
        @php
            $orderItemNumber = $orderactivity->orderItem?$orderactivity->orderItem->order_item_number:'';
              $full_name = '';
              if ($orderactivity->user_type == \App\Models\User::class){
                $full_name =  $orderactivity->user->firstname .' '. $orderactivity->user->lastname;
              } else {
                $full_name = $orderactivity->user->name;
              }
        @endphp
            <li class="pb-3">

                @if($orderactivity->key_name == 'status')
                    @if ($orderactivity->new_value == 8 )
                        <p class="h6">{{$full_name}} updated status of {{ 'BORN-'.$orderactivity->order_id }} from {{ $orderStatus[$orderactivity->old_value] .' to '. ($order->payment_due_date?sprintf($orderStatus[$orderactivity->new_value],changeDateFormat($order->payment_due_date,'d/m/Y')):sprintf($orderStatus[$orderactivity->new_value],'DD/MM/YYYY')) }}.</p>
                    @elseif($orderactivity->old_value == 8)
                        <p class="h6">{{$full_name}} updated status of {{ 'BORN-'.$orderactivity->order_id }} from {{ ($order->payment_due_date?sprintf($orderStatus[$orderactivity->old_value],changeDateFormat($order->payment_due_date,'d/m/Y')):sprintf($orderStatus[$orderactivity->old_value],'DD/MM/YYYY')) .' to '. $orderStatus[$orderactivity->new_value] }}.</p>
                    @elseif($orderactivity->new_value == 'Manage order delivery separately')
                        <p class="h6">{{$full_name}} {{$orderactivity->new_value}} of order {{ 'BORN-'.$orderactivity->order_id }} .</p>
                    @else
                        @if(!empty($orderactivity->old_value) && !empty($orderactivity->new_value))
                            <p class="h6">{{$full_name}} updated status of {{ 'BORN-'.$orderactivity->order_id }} from {{ $orderStatus[$orderactivity->old_value] .' to '. $orderStatus[$orderactivity->new_value] }}.</p>
                        @endif
                    @endif
                @elseif($orderactivity->key_name == 'order_item_status')
                    <p class="h6">{{$full_name}} updated status of order item no. {{ $orderItemNumber }} {{ $orderactivity->old_value?('from '.$orderItemStatus[$orderactivity->old_value]):'change' }} to
                        {{$orderItemStatus[$orderactivity->new_value]}}.</p>
                @else
                    @php
                        $check = (!empty($orderactivity->old_value) || $orderactivity->old_value != 0) ? 1:0;
                    @endphp
                    @if($orderactivity->key_name == 'generate_po')
                        <p class="h6">{{$full_name}} {{ $orderactivity->new_value }}.</p>
                    @endif
                    @if($orderactivity->key_name == 'order_latter')
                        <p class="h6">{{$full_name}} Uploaded  Order Latter of order item no. {{ $orderItemNumber }} from @php  if(!$check) { echo  $orderactivity->new_value; } else { echo $orderactivity->old_value .' to '. $orderactivity->new_value; } @endphp.</p>
                    @elseif($orderactivity->key_name == 'tax_receipt')
                        <p class="h6">{{$full_name}} Uploaded Tax Receipt of {{ 'BORN-'.$orderactivity->order_id }} from @php  if(!$check) { echo  $orderactivity->new_value; } else { echo $orderactivity->old_value .' to '. $orderactivity->new_value; } @endphp.</p>
                    @elseif($orderactivity->key_name == 'invoice')
                        <p class="h6">{{$full_name}} Uploaded Invoice of {{ 'BORN-'.$orderactivity->order_id }} from @php  if(!$check) { echo  $orderactivity->new_value; } else { echo $orderactivity->old_value .' to '. $orderactivity->new_value; } @endphp.</p>
                    @endif
                @endif

                    <small class="d-flex align-items-top text-muted">
                        <i class="mdi mdi-clock-outline me-1"></i>
                        {{changeDateTimeFormat($orderactivity->created_at,'d-m-Y H:i:s')}}
                    </small>
            </li>
    @endforeach

    @if(isset($order))
        <li class="pb-3">
            @if( isset($order->user) && $order->user->role_id == 1 )
                <p class="h6">Blitznet Team created {{$rfq->order_number}} </p>
            @else
                <p class="h6">{{$order->user->full_name}} Order placed {{$order->order_number}}.</p>
            @endif
            <small class="d-flex align-items-top">
                <i class="mdi mdi-clock-outline me-1"></i>
                {{ changeDateTimeFormat($order->created_at,'d-m-Y H:i:s')}}
            </small>
        </li>
    @endif
    </ul>
</div>
</div>
@else

@endif
