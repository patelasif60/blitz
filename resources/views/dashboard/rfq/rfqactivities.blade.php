@if($rfqactivities)
    @foreach($rfqactivities as $rfqactivity)
    @if($rfqactivity->key_name !== 'sub category')
    <div class="tl-item active">
        <div class="tl-dot b-primary"><img src="{{ URL::asset('front-assets/images/icons/icon_edit_add.png')}}"
                alt=""></div>
        <div class="tl-content">
            @if( isset($rfqactivity->rfquserName) && $rfqactivity->rfquserName->role_id == 1 )
                @if($rfqactivity->key_name == 'rental forklift')
                    @if($rfqactivity->new_value == 1)
                        <div class=""><strong class="">Blitznet Team</strong> added <span class="text-primary">Rental Forklift of </span><strong class="">{{$rfq->reference_number}} </strong> </div>
                    @else
                        <div class=""><strong class="">Blitznet Team</strong> removed <span class="text-primary">Rental Forklift of </span><strong class="">{{$rfq->reference_number}}</strong> </div>
                    @endif
                @elseif($rfqactivity->key_name == 'unloading services')
                    @if($rfqactivity->new_value == 1)
                        <div class=""><strong class="">Blitznet Team</strong> added <span class="text-primary">Unloading Services of </span><strong class="">{{$rfq->reference_number}} </strong></div>
                    @else
                        <div class=""><strong class="">Blitznet Team</strong> removed <span class="text-primary">Unloading Services of </span><strong class="">{{$rfq->reference_number}} </strong></div>
                    @endif
                @elseif($rfqactivity->key_name == 'is require credit')
                    @if($rfqactivity->new_value == 1)
                        <div class=""><strong class="">Blitznet Team</strong> added <span class="text-primary">Require Credit of </span><strong class="">{{$rfq->reference_number}}</strong>  </div>
                    @else
                        <div class=""><strong class="">Blitznet Team</strong> removed <span class="text-primary">Require Credit of </span><strong class="">{{$rfq->reference_number}}</strong> </div>
                    @endif
                @else
                    <div class=""><strong class="">Blitznet Team</strong> updated <span class="text-primary">{{$rfqactivity->key_name}} of </span><strong class="">{{$rfq->reference_number}} </strong> <span class="text-primary">from {{$rfqactivity->old_value}} to {{$rfqactivity->new_value}}</span>

                    </div>
                @endif
            @else
                @if($rfqactivity->key_name == 'rental forklift')
                    @if($rfqactivity->new_value == 1)
                        <div class=""><strong class="">{{$rfqactivity->rfquserName->full_name}}</strong> added <span class="text-primary">Rental Forklift of  </span><strong class="">{{$rfq->reference_number}}</strong></div>
                        @else
                        <div class=""><strong class="">{{$rfqactivity->rfquserName->full_name}}</strong> removed <span class="text-primary">Rental Forklift of  </span><strong class="">{{$rfq->reference_number}}</strong></div>
                    @endif
                @elseif($rfqactivity->key_name == 'unloading services')
                    @if($rfqactivity->new_value == 1)
                        <div class=""><strong class="">{{$rfqactivity->rfquserName->full_name}}</strong> added <span class="text-primary">Unloading Services of  </span><strong class="">{{$rfq->reference_number}}</strong> </div>
                    @else
                        <div class=""><strong class="">{{$rfqactivity->rfquserName->full_name}}</strong> removed <span class="text-primary">Unloading Services of </span><strong class="">{{$rfq->reference_number}} </strong></div>
                    @endif
                @elseif($rfqactivity->key_name == 'is require credit')
                    @if($rfqactivity->new_value == 1)
                        <div class=""><strong class="">{{$rfqactivity->rfquserName->full_name}}</strong> added <span class="text-primary">Require Credit of </span><strong class="">{{$rfq->reference_number}} </strong> </div>
                    @else
                        <div class=""><strong class="">{{$rfqactivity->rfquserName->full_name}}</strong> removed <span class="text-primary">Require Credit of </span><strong class="">{{$rfq->reference_number}}</strong> </div>
                    @endif
                @elseif($rfqactivity->key_name == 'Remove Product')
                    <div class=""><strong class="">{{$rfqactivity->rfquserName->full_name}}</strong> <span class="text-primary">{{$rfqactivity->key_name}} of </span><strong class="">{{$rfq->reference_number}}</strong>  <span class="text-primary"> to {{$rfqactivity->new_value}}</span></div>
                @elseif($rfqactivity->key_name == 'New Product')
                    <div class=""><strong class="">{{$rfqactivity->rfquserName->full_name}}</strong> <span class="text-primary">Add {{$rfqactivity->key_name}} of </span><strong class="">{{$rfq->reference_number}}</strong>  <span class="text-primary"> to {{$rfqactivity->new_value}}</span></div>
                @else
                    @if($rfqactivity->key_name == 'unit')
                            <div class=""><strong class="">{{$rfqactivity->rfquserName->full_name}}</strong> updated <span class="text-primary">{{$rfqactivity->key_name}} of </span><strong class="">{{$rfq->reference_number}}</strong>  <span class="text-primary">from {{get_unit_name($rfqactivity->old_value)}} to {{get_unit_name($rfqactivity->new_value)}}</span></div>
                    @else
                        <div class=""><strong class="">{{$rfqactivity->rfquserName->full_name}}</strong> updated <span class="text-primary">{{$rfqactivity->key_name}} of </span><strong class="">{{$rfq->reference_number}}</strong>  <span class="text-primary">from {{$rfqactivity->old_value}} to {{$rfqactivity->new_value}}</span></div>
                    @endif
                @endif

            @endif
            <div class="tl-date text-muted mt-1">{{\Carbon\Carbon::parse($rfqactivity->created_at)->format('d-m-Y H:i:s')}} </div>

        </div>
    </div>
    @endif
    @endforeach
    @if(isset($user_rfqs))
        <div class="tl-item active">
            <div class="tl-dot b-primary"><img src="{{ URL::asset('front-assets/images/icons/icon_edit_add.png')}}"
                    alt=""></div>
            <div class="tl-content">
                @if( isset($user_rfqs->userrfqName) && $user_rfqs->userrfqName->role_id == 1 )
                    <div class=""><strong class="">Blitznet Team</strong> created <span class="text-primary"> {{$rfq->reference_number}}</span>

                        </div>
                @else
                    <div class=""><strong class="">{{$user_rfqs->userrfqName->full_name}}</strong> created <span class="text-primary">{{$rfq->reference_number}} </span>

                    </div>
                @endif

                <div class="tl-date text-muted mt-1">{{\Carbon\Carbon::parse($user_rfqs->created_at)->format('d-m-Y H:i:s')}} </div>
            </div>
        </div>
    @endif
@else


@endif
