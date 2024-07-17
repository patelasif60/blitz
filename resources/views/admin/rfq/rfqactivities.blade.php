@if($rfqactivities)
<div class="card mb-3">
<div class="card-header d-flex align-items-center">
                <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/icon_update_activities.png')}}" alt="Charges" class="pe-2"> <span>{{ __('admin.recent_activities') }}</span></h5>
</div>
<div class="card-body p-3 pb-1">
<ul class="bullet-line-list">
    @foreach($rfqactivities as $rfqactivity)
            <li class="pb-3">

                @if( isset($rfqactivity->rfquserName) && $rfqactivity->rfquserName->role_id == 1 )
                    @if($rfqactivity->key_name == 'rental forklift')
                        @if($rfqactivity->new_value == 1)
                            <p class="h6">Blitznet Team added Rental Forklift of {{$rfq->reference_number}} </p>
                        @else
                            <p class="h6">Blitznet Team removed Rental Forklift of {{$rfq->reference_number}} </p>
                        @endif
                    @elseif($rfqactivity->key_name == 'unloading services')
                        @if($rfqactivity->new_value == 1)
                            <p class="h6">Blitznet Team added Unloading Services of {{$rfq->reference_number}} added</p>
                        @else
                            <p class="h6">Blitznet Team removed Unloading Services of {{$rfq->reference_number}} </p>
                        @endif
                    @elseif($rfqactivity->key_name == 'is require credit')
                        @if($rfqactivity->new_value == 1)
                            <p class="h6">Blitznet Team added Require Credit of {{$rfq->reference_number}} added</p>


                        @else
                            <p class="h6">Blitznet Team removed Require Credit of {{$rfq->reference_number}} </p>

                        @endif
                    {{--@elseif($rfqactivity->key_name == 'product description' || $rfqactivity->key_name == 'comment')
                        <p class="h6">Blitznet Team updated {{$rfqactivity->key_name}} of {{$rfq->reference_number}} from {{ strip_tags($rfqactivity->old_value)}} to {{strip_tags($rfqactivity->new_value)}}</p>--}}
                    @else
                        <p class="h6">Blitznet Team updated {{$rfqactivity->key_name}} of {{$rfq->reference_number}} from {{$rfqactivity->old_value}} to {{$rfqactivity->new_value}}</p>
                    @endif



                @else
                    @if($rfqactivity->key_name == 'rental forklift')
                        @if($rfqactivity->new_value == 1)
                            <p class="h6">{{$rfqactivity->rfquserName->full_name}} added Rental Forklift of {{$rfq->reference_number}} </p>
                        @else
                            <p class="h6">{{$rfqactivity->rfquserName->full_name}} removed Rental Forklift of {{$rfq->reference_number}} </p>
                        @endif
                    @elseif($rfqactivity->key_name == 'unloading services')
                        @if($rfqactivity->new_value == 1)
                            <p class="h6">{{$rfqactivity->rfquserName->full_name}} added Unloading Services of {{$rfq->reference_number}} </p>
                        @else
                            <p class="h6">{{$rfqactivity->rfquserName->full_name}} removed Unloading Services of {{$rfq->reference_number}} </p>
                        @endif
                    @elseif($rfqactivity->key_name == 'is require credit')
                        @if($rfqactivity->new_value == 1)
                            <p class="h6">{{$rfqactivity->rfquserName->full_name}} added Require Credit of {{$rfq->reference_number}} </p>


                        @else
                            <p class="h6">{{$rfqactivity->rfquserName->full_name}}  removed Require Credit of {{$rfq->reference_number}} </p>

                        @endif
                    @else
                        <p class="h6">{{$rfqactivity->rfquserName->full_name}} updated {{$rfqactivity->key_name}} of {{$rfq->reference_number}} from {{$rfqactivity->old_value}} to {{$rfqactivity->new_value}}</p>
                    @endif
                @endif

                    <small class="d-flex align-items-top">
                        <i class="mdi mdi-clock-outline me-1"></i>
                        {{  \Carbon\Carbon::parse($rfqactivity->created_at)->format('d-m-Y H:i:s')}}
                    </small>

            </li>
    @endforeach

    @if(isset($user_rfqs))
            <li class="pb-3">


                @if( isset($user_rfqs->userrfqName) && $user_rfqs->userrfqName->role_id == 1 )
                    <p class="h6">Blitznet Team created {{$rfq->reference_number}} </p>
                @else
                    <p class="h6">{{$user_rfqs->userrfqName->full_name}} created {{$rfq->reference_number}} </p>
                @endif
                    <small class="d-flex align-items-top">
                        <i class="mdi mdi-clock-outline me-1"></i>
                        {{  \Carbon\Carbon::parse($user_rfqs->created_at)->format('d-m-Y H:i:s')}}
                    </small>
            </li>
    @endif
    </ul>
</div>
</div>

@else

@endif
