@php
    $checkIsNotAdmin = Auth::user()->role_id == \App\Models\Role::ADMIN ? 0: 1;
    $tranchrg = 0;
@endphp
@foreach ($paymentCharges as $key => $charge)
<div class="row pay_main_div" id="pay_main_div_{{$key}}">
    <div class="col-md-3">
        <label for="pay_charges_{{$key}}" class="form-label">{{ __('admin.charges') }}<span class="text-danger">*</span></label>
        <select name="payment_charges[{{ $key }}][charges]" class="form-control selectBox pay_charges" id="pay_charges_{{$key}}">
            <option value="">Select charge</option>
                <option selected data-plus-minus="{{ $charge['addition_substraction'] }}"
                        charge-type="{{ ( $charge['editable'] == 0 ? ($charge['type'] == 0 ? '%' : 'RP (Flat)') : ($charge['xendit_commision_fee']['type'] == 0 ? '%' : 'RP (Flat)')) }}"
                        data-value-on="{{ $charge['value_on'] == 0 ? 'Amount' : 'Quantity' }}"
                        data-value="{{ ($charge['editable'] == 0 ? ($charge['charges_value']) : ($charge['xendit_commision_fee']['charges_value'])) }}"
                        value="{{ $charge['id'] }}">{{ $charge['name'] }}</option>
                     {{ $tranchrg = $charge['id'] }}
        </select>
        <i class="fa fa-chevron-down"></i>
        {{-- Added by ekta --}}
        <input type="hidden" name="payment_charges[{{ $key }}][charges]" class="form-control pay_charges_{{ $key }}" value="{{ $tranchrg }}">
        {{-- end by ekta --}}
    </div>
    <div class="col-md-2">
        <label for="pay_type" class="form-label">{{ __('admin.type') }}</label>
        <input type="text" name="payment_charges[{{ $key }}][charge_type]" class="form-control pay_type" value="" readonly>
    </div>
    <div class="col-md-3 form-group">
        <label for="pay_charges_value" class="form-label">{{ __('admin.charges_value') }}</label>
        <input type="text" name="payment_charges[{{ $key }}][charge_value]" class="form-control pay_charges_value" value="" placeholder="Charges Value" @if($checkIsNotAdmin) readonly @endif>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="pay_charge_amount" class="form-label">{{ __('admin.charges_amount') }} (RP)</label>
            <input type="text" name="payment_charges[{{ $key }}][charge_amount]" class="form-control pay_charge_amount"  placeholder="Charges Amount" value="" @if($checkIsNotAdmin || $charge['id']==10) readonly @endif>
        </div>
    </div>
    <div class="col-md-1 pt-1">
        {{--<label for="" class="form-label"></label>
        <div style="line-height: 38px;">
            <span class="icon deleteCharge">
                <a href="javascript:void(0)" id="" class="text-danger remove-payment-charge"><i class="fa fa-trash"></i></a>
            </span>
        </div>--}}
    </div>
</div>
    @php
        $tranchrg++;
    @endphp
@endforeach
