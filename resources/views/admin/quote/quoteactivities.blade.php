@if($quoteActivies)
<div class="card mb-3">
<div class="card-header d-flex align-items-center">
                <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/icon_update_activities.png')}}" alt="Charges" class="pe-2"> <span>{{ __('admin.recent_activities') }}</span></h5>
</div>
<div class="card-body p-3 pb-1">
    <ul class="bullet-line-list">
    @foreach($quoteActivies as $quoteactivity)
            <li class="pb-3">
                @if($quoteactivity->key_name == 'product_price_per_unit')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Product price per unit of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $quoteactivity->old_value .' to '. $quoteactivity->new_value }}.</p>
                @endif
                @if($quoteactivity->key_name == 'valid_till')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Valid Till of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ date('d-m-Y', strtotime($quoteactivity->old_value)) .' to '. \Carbon\Carbon::createFromFormat('Y-d-m', $quoteactivity->new_value)->format('d-m-Y')}}.</p>
                @endif
                @if($quoteactivity->key_name == 'note')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Delivery Note of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $quoteactivity->old_value .' to '. $quoteactivity->new_value }}.</p>
                @endif
                @if($quoteactivity->key_name == 'certificate')
                    @if($quoteactivity->old_value == '' && empty($quoteactivity->old_value))
                        <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} Added Certificate {{ $quoteactivity->new_value }} of {{ 'BQTN-'.$quoteactivity->quote_id }}.</p>
                    @elseif($quoteactivity->new_value == '' && empty($quoteactivity->new_value))
                        <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} Remove Certificate {{ $quoteactivity->old_value }} of {{ 'BQTN-'.$quoteactivity->quote_id }}.</p>
                    @else
                        <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Certificate of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $quoteactivity->old_value .' to '. $quoteactivity->new_value }}.</p>
                    @endif
                @endif
                @if($quoteactivity->key_name == 'comment')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Comment of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $quoteactivity->old_value .' to '. $quoteactivity->new_value }}.</p>
                @endif
                @if($quoteactivity->key_name == 'minDays')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Deliver Order in Min. Days of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $quoteactivity->old_value .' to '. $quoteactivity->new_value }}.</p>
                @endif
                @if($quoteactivity->key_name == 'maxDays')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Deliver Order in Max. Days of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $quoteactivity->old_value .' to '. $quoteactivity->new_value }}.</p>
                @endif
                @if($quoteactivity->key_name == 'tax')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Tax of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $quoteactivity->old_value .' to '. $quoteactivity->new_value }}.</p>
                @endif
                @if($quoteactivity->key_name == 'tax_amount')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Tax Amount of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ number_format($quoteactivity->old_value,2) .' to '. number_format($quoteactivity->new_value,2) }}.</p>
                @endif
                @if($quoteactivity->key_name == 'price')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Price of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ number_format($quoteactivity->old_value,2) .' to '. number_format($quoteactivity->new_value,2) }}.</p>
                @endif
                @if($quoteactivity->key_name == 'amount')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Amount of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $quoteactivity->old_value .' to '. $quoteactivity->new_value }}.</p>
                @endif
                @if($quoteactivity->key_name == 'finalAmount')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Final Amount  of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ number_format($quoteactivity->old_value,2) .' to '. number_format($quoteactivity->new_value,2) }}.</p>
                @endif
                @if($quoteactivity->key_name == 'charges_deleted')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} Remove {{ $quoteactivity->new_value }} of {{ 'BQTN-'.$quoteactivity->quote_id }}.</p>
                @endif
                @if($quoteactivity->key_name == 'remove_product_value')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} Remove {{ get_product_name_by_id($quoteactivity->new_value) }} of {{ 'BQTN-'.$quoteactivity->quote_id }}.</p>
                @endif
                @if($quoteactivity->key_name == 'charges_updated')
                    @php
                        $charge_name_old = explode('-', $quoteactivity->old_value);
                        $charge_name_new = explode('-', $quoteactivity->new_value);
                    @endphp
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated {{ $charge_name_old[0] }}  of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $charge_name_old[1] .' to '. $charge_name_new[1] }}.</p>
                @endif
                @if($quoteactivity->key_name == 'charges_added')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} Added {{ $quoteactivity->new_value }} of {{ 'BQTN-'.$quoteactivity->quote_id }}.</p>
                @endif
                @if($quoteactivity->key_name == 'add_product_value')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} Added {{ get_product_name_by_id($quoteactivity->new_value) }} of {{ 'BQTN-'.$quoteactivity->quote_id }}.</p>
                @endif
                @if($quoteactivity->key_name == 'supplier')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Comment of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ getSupplierById($quoteactivity->old_value)['name'] .' to '. getSupplierById($quoteactivity->new_value)['name'] }}.</p>
                @endif
                @if($quoteactivity->key_name == 'logistic_check')
                        <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Logistic Charge Checkbox of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ ($quoteactivity->old_value == 0) ?"Unchecked":"Checked"}}  to  {{ ($quoteactivity->new_value == 0)?"Unchecked":"Checked" }}.</p>
                @endif
                @if($quoteactivity->key_name == 'address_name')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Address Name of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $quoteactivity->old_value .' to '. $quoteactivity->new_value }}.</p>
                @endif
                @if($quoteactivity->key_name == 'address_line_1')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Address Line 1 of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $quoteactivity->old_value .' to '. $quoteactivity->new_value }}.</p>
                @endif
                @if($quoteactivity->key_name == 'address_line_2')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Address Line 2 of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $quoteactivity->old_value .' to '. $quoteactivity->new_value }}.</p>
                @endif
                @if($quoteactivity->key_name == 'district')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated District of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $quoteactivity->old_value .' to '. $quoteactivity->new_value }}.</p>
                @endif
                @if($quoteactivity->key_name == 'sub_district')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Sub District of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $quoteactivity->old_value .' to '. $quoteactivity->new_value }}.</p>
                @endif
                @if($quoteactivity->key_name == 'city')
                    @php
                        if(empty($quoteactivity->old_value)){ $displayOldCityname = '-'; }
                        else{ $displayOldCityname = $quoteactivity->old_value; }

                        if(empty($quoteactivity->new_value)){ $displayNewCityname = '-'; }
                        else{ $displayNewCityname = $quoteactivity->new_value; }
                    @endphp

                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated City of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $displayOldCityname .' to '. $displayNewCityname }}.</p>
                @endif
                @if($quoteactivity->key_name == 'provinces')
                    @php
                        if(empty($quoteactivity->old_value)){ $displayOldStatename = '-'; }
                        else{ $displayOldStatename = $quoteactivity->old_value; }

                        if(empty($quoteactivity->new_value)){ $displayNewStatename = '-'; }
                        else{ $displayNewStatename = $quoteactivity->new_value; }
                    @endphp
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Provinces of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $displayOldStatename .' to '. $displayNewStatename }}.</p>
                @endif
                @if($quoteactivity->key_name == 'pincode')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Pin Code of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $quoteactivity->old_value .' to '. $quoteactivity->new_value }}.</p>
                @endif
                @if($quoteactivity->key_name == 'weights')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Weights of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $quoteactivity->old_value .' to '. $quoteactivity->new_value }}.</p>
                @endif
                @if($quoteactivity->key_name == 'dimensions')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Dimensions of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $quoteactivity->old_value .' to '. $quoteactivity->new_value }}.</p>
                @endif

                @if($quoteactivity->key_name == 'cityId')
                    @php
                        if($quoteactivity->old_value == -1){ $displayOldCity = 'Other'; }
                        elseif (empty($quoteactivity->old_value)) { $displayOldCity = '-'; }
                        else{ $displayOldCity = $cities[$quoteactivity->old_value]; }

                        if($quoteactivity->new_value == -1){ $displayNewCity = 'Other'; }
                        elseif (empty($quoteactivity->new_value)) { $displayNewCity = '-'; }
                        else{ $displayNewCity = $cities[$quoteactivity->new_value]; }
                    @endphp
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated city of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $displayOldCity .' to '. $displayNewCity }}.</p>
                @endif

                @if($quoteactivity->key_name == 'stateId')
                    @php
                        if($quoteactivity->old_value == -1){ $displayOldState = 'Other'; }
                        elseif (empty($quoteactivity->old_value)) { $displayOldState = '-'; }
                        else{ $displayOldState = $states[$quoteactivity->old_value]; }

                        if($quoteactivity->new_value == -1){ $displayNewState = 'Other'; }
                        elseif (empty($quoteactivity->new_value)) { $displayNewState = '-'; }
                        else{ $displayNewState = $states[$quoteactivity->new_value]; }
                    @endphp
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated state of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $displayOldState .' to '. $displayNewState }}.</p>
                @endif
                @if($quoteactivity->key_name == 'countryId')
                    @php
                        if($quoteactivity->old_value == -1){ $displayOldCountry = 'Other'; }
                        elseif (empty($quoteactivity->old_value)) { $displayOldCountry = '-'; }
                        else{ $displayOldCountry = $countries[$quoteactivity->old_value]; }

                        if($quoteactivity->new_value == -1){ $displayNewCountry = 'Other'; }
                        elseif (empty($quoteactivity->new_value)) { $displayNewCountry = '-'; }
                        else{ $displayNewCountry = $countries[$quoteactivity->new_value]; }
                    @endphp
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated country of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $displayOldCountry .' to '. $displayNewCountry }}.</p>
                @endif
                @if($quoteactivity->key_name == 'qty')
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Quantity of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $quoteactivity->old_value .' to '. $quoteactivity->new_value }}.</p>
                @endif
                @if($quoteactivity->key_name == 'inclusive_tax_logistic')
                    @php
                        if($quoteactivity->new_value == 1){ $displayLogistic = 'Add Inclusive Logistic charge'; }
                        else{ $displayLogistic = 'Remove Inclusive Logistic Charge'; }
                    @endphp
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}}  {{ $displayLogistic }} of {{ 'BQTN-'.$quoteactivity->quote_id }}.</p>
                @endif
                @if($quoteactivity->key_name == 'inclusive_tax_other')
                    @php
                        if($quoteactivity->new_value == 1){ $displayOtherCharges = 'Add Inclusive Other charge'; }
                        else{ $displayOtherCharges = 'Remove Inclusive Other Charge'; }
                    @endphp
                    <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}}  {{ $displayOtherCharges }} of {{ 'BQTN-'.$quoteactivity->quote_id }}.</p>
                @endif
                @if($quoteactivity->key_name == 'termsconditions_file')
                    @php
                        if(empty($quoteactivity->old_value)){ $displayOldTC = '-'; }
                        else{ $displayOldTC = Str::substr($quoteactivity->old_value, stripos($quoteactivity->old_value, 'termsconditions_file_') + 21); }

                        if(empty($quoteactivity->new_value)){ $displayNewTC = '-'; }
                        else{ $displayNewTC = Str::substr($quoteactivity->new_value, stripos($quoteactivity->new_value, 'termsconditions_file_') + 21); }
                    @endphp
                    @if(($quoteactivity->old_value == '' && empty($quoteactivity->old_value)) && $quoteactivity->new_value != '')
                        <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} updated Term Conditions of {{ 'BQTN-'.$quoteactivity->quote_id }} to {{ $displayNewTC}}.</p>
                    @elseif($quoteactivity->new_value == '' && empty($quoteactivity->new_value))
                        <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}} Remove Term Conditions {{ $quoteactivity->old_value }} of {{ 'BQTN-'.$quoteactivity->quote_id }}.</p>
                    @else
                        <p class="h6">{{$quoteactivity->rfquserName->firstname .' '. $quoteactivity->rfquserName->lastname}}  updated Term Conditions of {{ 'BQTN-'.$quoteactivity->quote_id }} from {{ $displayOldTC .' to '. $displayNewTC }}.</p>
                    @endif
                @endif
                <small class="d-flex align-items-top text-muted">
                    <i class="mdi mdi-clock-outline me-1"></i>
                    {{  \Carbon\Carbon::parse($quoteactivity->created_at)->format('d-m-Y H:i:s')}}
                </small>
            </li>
    @endforeach

    @if(isset($user))

        <li class="pb-3">

            <p class="h6">{{$user->firstname .' '. $user->lastname }} Generate Quote {{$quote->quote_number}}.</p>
            <small class="d-flex align-items-top">
                <i class="mdi mdi-clock-outline me-1"></i>
                {{  \Carbon\Carbon::parse($quote->created_at)->format('d-m-Y H:i:s')}}
            </small>
        </li>
    @endif
    </ul>
</div>
@else

@endif
