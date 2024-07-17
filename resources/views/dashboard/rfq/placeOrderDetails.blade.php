<!--begin: Place order details custom css-->
<style>

    select[readonly]{
        pointer-events: none;
        touch-action: none;
    }
    .table>:not(caption)>*>* {
    padding: 0.5rem 0.5rem;
    background-color: var(--bs-table-bg);
    border-bottom-width: 1px;
    box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
}
    .ap-otp-input{padding:10px;border:none;border-bottom:2px solid #000;margin:0 5px;width:40px;font-weight:700;text-align:center}
.ap-otp-input:focus{outline:0!important;border-bottom:1px solid #1f6feb;transition:.12s ease-in}
</style>
<!--end: Place order details custom css-->
@php
    $isGroupRfq = $group?1:0;
    $amount='';
    $multipleProduct=0;
    foreach($quote_items as $key => $value){
         $amount=$value->product_amount;
    }
    foreach($quote_items as $key => $value){
         $multipleProduct=$multipleProduct+$value->product_amount;
    }

    $validKoinworksAmount = 0;
    if(!empty($creditInfoDetail)){
        $validKoinworksAmount = 1;
        if($creditInfoDetail->remaining_amount!='')
        $usedLimit = $creditInfoDetail->senctioned_amount - $creditInfoDetail->remaining_amount;
        else
        $usedLimit = $creditInfoDetail->senctioned_amount -0;
        $unusedLimit = $creditInfoDetail->senctioned_amount - $usedLimit;
        if($quote->final_amount <= MIN_LOAN_AMOUNT || $quote->final_amount > $creditInfoDetail->senctioned_amount){
            $validKoinworksAmount = 0;
        }
        if($unusedLimit > 0 && $quote->final_amount > $unusedLimit){
            $validKoinworksAmount = 0;
        }
    }

@endphp
<div class="modal-header">
    <h5 class="modal-title d-flex w-100 align-items-center" id="viewModalLabel">{{ $quote->quote_number }} </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form data-parsley-validate autocomplete="off" id="placeOrderAddressForm" data-is_group_rfq="{{$isGroupRfq}}">
        <input type="hidden" name="final_amountjs" id="final_amountjs"
                value="{{number_format($quote->final_amount, 2) }}" />
        <input type="hidden" name="amount_rfq" id="amount_rfq"
                value="{{number_format($multipleProduct, 2) }}" />
        <input type="hidden" name="quote_id" id="quote_id"
                value="{{$quote->quotes_id}}" />
        <input type="hidden" name="paybal_amount" id="paybal_amount"
                value="" />
    <div class="modal-body">
        <div class="row rfqform_view g-3" id="main_div">
            <div class="col-md-4">
                <label>{{ __('rfqs.rfq_number') }}:</label>
                <div>{{ $quote->rfq_reference_number }}</div>
            </div>
            <div class="col-md-4">
                <label>{{ __('rfqs.date') }}:</label>
                <div>{{ date('d-m-Y H:i', strtotime($quote->created_at)) }}</div>
            </div>
            <div class="col-md-4">
                <label>{{ __('rfqs.status') }}:</label>
                <div> {{ $quote->status_name }}</div>
            </div>
        </div>
        <div class="row rfqform_view1 g-3 mb-3" style="display:none;">
            <div class="col-md-4">
                <label>{{ __('rfqs.rfq_number') }}:</label>
                <div>{{ $quote->rfq_reference_number }}</div>
            </div>
            <div class="col-md-4">
                <label>Payment Type:</label>
                <div>Loan</div>
            </div>
            <div class="col-md-4">
                <label>{{ __('rfqs.credit_type') }}:</label>
                <div>30 Days</div>
            </div>
            <div class="col-md-4">
                <label>{{ __('rfqs.amount') }}:</label>
                <div>{{'Rp ' . number_format($quote->final_amount, 2) }}</div>
            </div>
            <div class="col-md-4">
                <label>{{ __('rfqs.paybal_amount') }}:</label>
                <div id="paybalAMount"></div>
            </div>
        </div>
        <div class="card radius_1 my-3">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('rfqs.description') }}</th>
                        <th width="20%" class="text-center">{{ __('rfqs.QTY') }}</th>
                        <th width="20%" class="text-end">{{ __('rfqs.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($quote_items as $key => $value)
                    <tr>
                        <td>{{ get_product_name_by_id($value->rfq_product_id, 0) }}</td>
                        <td class="text-center">{{ $value->product_quantity??'' }} {{ $value->name??'' }}</td>
                        <td class="text-end">{{ 'Rp ' . number_format($value->product_amount, 2) }}</td>
                    </tr>

            @endforeach
            {{--@if($isGroupRfq)
                @php
                    $orderQty = $quote_items[0]->product_quantity;
                    $productRealPrice = $quote_items[0]->product_price_per_unit;
                    $newDiscount = \App\Models\Quote::getGroupDiscountAmount($group,$orderQty,$productRealPrice);
                @endphp
                @if($newDiscount->discount!=$quoteGroupDiscount)
                <tr>
                    <td colspan="3" class="p-0" >
                        <div class="alert alert-primary radius-0 mb-0 text-center fw-bold p-1 text-dark">At this time you can get more {{$newDiscount->discount-$quoteGroupDiscount}}% extra discount</div>
                    </td>
                </tr>
                @endif
            @endif--}}
                <tr class="bg-light">
                    <td colspan="2" class="fw-bold">{{ __('admin.include_total') }}</td>
                    <td class="text-end fw-bold">{{ 'Rp ' . number_format($quote->final_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

    </div>
        <div class="row g-3 mb-1 rfqform_view">
            <div class="col-md-12">
                <div class="row payment-type">
                    @if($quote->payment_type !=3 && $quote->payment_type!=4)
                    <div class="col-md-4 mb-2">
                        <div>
                            <label>{{ __('rfqs.payment_type') }}:</label>
                            <select class="form-select form-select-sm p-2 " id="is_credit" name="is_credit" onchange="setPaymentType()" required>
                                <option  value="">{{ __('rfqs.select_payment_type') }}</option>
                                <option {{ $quote->payment_type == 0 ? 'selected' : '' }} value="0">{{ __('rfqs.cash') }}</option>
                                @if(!$isGroupRfq)
                                <option {{ $quote->payment_type == 1 ? 'selected' : '' }} value="1">{{ __('rfqs.credit') }}</option>
                                @endif
                                @can('utilize buyer company credit')
                                    @if($loan!='' && !$isGroupRfq && $validKoinworksAmount == 1)
                                    <option {{ $quote->payment_type == 2 ? 'selected' : '' }} value="2">
                                    {{ __('rfqs.koinworkcredit') }}</option>
                                @endif
                                @endcan
                            </select>
                        </div>
                    </div>
                    @else
                    <div class="col-md-4 mb-2">
                        <div>
                            <label>{{ __('rfqs.payment_type') }}:</label>
                            <select readonly class="form-select form-select-sm p-2 " id="is_credit" name="is_credit" onchange="setPaymentType()" required>
                                <option {{ $quote->payment_type == 3 ? 'selected' : '' }} value="3">{{  __('admin.lc')  }}</option>
                                <option {{ $quote->payment_type == 4 ? 'selected' : '' }} value="4">{{  __('admin.skbdn')  }}</option>
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-4 mb-2 credit-type-div" style="display: none">
                        <label>{{ __('rfqs.credit_type') }}:</label>
                        <select class="form-select form-select-sm p-2 credit_type" id="credit_days_id" name="credit_days_id">
                        @foreach($creditDays as $i=>$creditDay)
                            @php
                                $credit_name = sprintf(__('rfqs.credit_type_name'),trim($creditDay->days));
                            @endphp
                                <option value="{{$creditDay->id}}" {{ $quote->credit_days == $creditDay->days ? 'selected' : '' }}>{{$credit_name}}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="col-md-4  mb-2 credit-type-div_2" style="display: none">
                        <label>Credit Type:</label>
                        <select disabled="disabled" class="form-select form-select-sm p-2 credit_type_2" id="credit_days_id_koinwork"name="credit_days_id_koinwork">
                        <option value="" selected="">{{ __('rfqs.for_30_days') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="cust_ref_id">{{ __('rfqs.customer_ref_id') }}</label>
                        <input type="text" name="cust_ref_id" id="cust_ref_id" class="form-control form-control-sm p-2" id="cust_ref_id" value="">
                        <small id="ref_msg"></small>
                    </div>
                    {{--@if($isGroupRfq)--}}
                    <div class="col-md-12">
                        <label for="comment">{{ __('rfqs.comment_for_po') }}</label>
                        <input type="text" name="comment" id="comment" class="form-control form-control-sm p-2" value="">
                    </div>
                    {{--@endif--}}
                </div>
            </div>

                <div class="col-md-12 address">
                    <div class="card radius_1">
                        <div class="card-body">
                            <div class="rfqform_view mb-3">
                                <label>@if(in_array($quote->category_id,\App\Models\Category::SERVICES_CATEGORY_IDS)) {{ __('admin.pickup_address') }} @else {{ __('rfqs.delivery_address') }} @endif:</label>
                            </div>
                                @csrf
                                <input type="hidden" name="quoteId" value="{{ $quote->quotes_id }}">
                                <div class="row floatlables">

                                        <div class="col-md-12 mb-3" id="address_block">
                                            <label class="form-label" for="useraddress_id">{{ __('rfqs.select_address') }}</label>
                                            <select id="useraddress_id" name="useraddress_id" data-id="" class="form-select" required>
                                                <option disabled>{{ __('rfqs.select_delivery_address') }}</option>
                                                @php
                                                    $isOtherSelected = 1;
                                                @endphp
                                                @foreach ($userAddress as $item)
                                                @if ($quote->rfq_pincode == $item->pincode)
                                                        @php
                                                            $addSelected = '';
                                                            if ($item->address_name==$quote->rfq_address_name && $item->address_line_1==$quote->rfq_address_line_1 && $item->address_line_2==$quote->rfq_address_line_2){
                                                                $addSelected = 'selected';
                                                                $isOtherSelected = 0;
                                                            }
                                                        @endphp
                                                    <option data-address_name="{{$item->address_name}}" data-address_line_2="{{$item->address_line_2}}" data-address_line_1="{{$item->address_line_1}}" data-sub_district="{{$item->sub_district}}" data-district="{{$item->district}}" data-city="{{$item->city}}" data-state="{{$item->state}}" value="{{ $item->id }}" {{$addSelected}}>{{ $item->address_name }}</option>
                                                @endif
                                                @endforeach
                                                <option data-address-id="0" value="0" {{$isOtherSelected?"selected":''}}>Other</option>
                                            </select>
                                        </div>

                                    <div class="col-sm-12 mb-3">
                                        <label for="address_name" class="form-label">{{ __('rfqs.address_name') }}<span class="text-danger">*</span></label>
                                        <input type="text" name="address_name" class="form-control" id="address_name" value="{{$quote->rfq_address_name}}" required>
                                    </div>
                                    <div class="col-sm-12 mb-3">
                                        <label for="addressLine1" class="form-label">{{ __('rfqs.address_line1') }}<span class="text-danger">*</span></label>
                                        <input type="text" name="addressLine1" class="form-control" id="addressLine1" value="{{$quote->rfq_address_line_1}}" required>
                                    </div>
                                    <div class="col-sm-12 mb-3">
                                        <label for="addressLine2" class="form-label">{{ __('rfqs.address_line2') }} </label>
                                        <input type="text" class="form-control" name="addressLine2" id="addressLine2" value="{{$quote->rfq_address_line_2}}">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label for="addressLine2" class="form-label">{{ __('rfqs.sub_district') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="sub_district" id="sub_district" value="{{$quote->rfq_sub_district}}"  required>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label for="addressLine2" class="form-label">{{ __('rfqs.district') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="district" id="district" value="{{$quote->rfq_district}}"  required>
                                    </div>

                                    <div class="col-md-4 mb-3 select2-block" id="stateId_block">
                                        <label for="stateId" class="form-label">{{ __('rfqs.provinces') }}<span class="text-danger">*</span></label>
                                        <select class="form-select select2-custom" id="stateId" name="stateId" data-placeholder="{{ __('rfqs.select_province') }}" required>
                                            <option value="" >{{ __('rfqs.select_province') }}</option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}" @if($quote->rfq_state_id == $state->id) selected @endif >{{ $state->name }}</option>
                                            @endforeach
                                            <option value="-1">Other</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3 hide" id="state_block">
                                        <label for="state" class="form-label">{{ __('rfqs.other_provinces') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="state" id="state" value="{{ $quote->rfq_state }}" required>
                                    </div>

                                    <div class="col-md-4 mb-3 select2-block" id="cityId_block">
                                        <label for="cityId" class="form-label">{{ __('rfqs.city') }}<span class="text-danger">*</span></label>
                                        <select class="form-select select2-custom" id="cityId" name="cityId" data-placeholder="{{ __('rfqs.select_city') }}" data-selected-city="{{ $quote->rfq_city_id }}" required>
                                            <option value="">{{ __('rfqs.select_city') }}</option>
                                            <option value="-1">Other</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3 hide" id="city_block">
                                        <label for="city" class="form-label">{{ __('rfqs.other_city') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="city" id="city" value="{{ $quote->rfq_city }}" >
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="pincode" class="form-label">{{ __('rfqs.pincode') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control bg-light" name="pincode" id="pincode" readonly value="{{ $quote->rfq_pincode }}" required>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 loan_div">
            <div class="card radius_1">
                <div class="card-body">
                    <div class="rfqform_view">
                        <label class="fs-5 ms-1 mb-2">{{ __('rfqs.credit') }}</label>
                        <input type="hidden" name="amountSent" id="amountSent"
                value="" />
                    </div>

                    <div class="row floatlables mx-1">

                        <table class="table border" id="calc">

                            <tbody>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            </div>
            <div class="col-md-12 otp_div">

<!--<form data-parsley-validate="" autocomplete="off" id="placeOrderOtpForm" data-is_group_rfq="0">-->

                <div class="text-center digit-group">
                    <div class="my-4 fw-bold">
                        <span class="fw-bold"> {{ __('rfqs.total_koinwork_amount') }}</span><span
                            class="text-primary ms-2" id="totalAmountOtp"></span>.
                    </div>
                    <p class="fw-bold mb-0" style=" font-size: 18px;">{{ __('rfqs.security_code') }}</p>
                    <div class="ap-otp-inputs">
                    <input class="ap-otp-input input-number" type="text" name="digit-1" id="digit-1" pattern="[0-9]" maxlength="1" data-index="0" data-next="digit-2">
                    <input class="ap-otp-input input-number" type="text" name="digit-2" id="digit-2" pattern="[0-9]" maxlength="1" data-index="1" data-next="digit-3" data-previous="digit-1">
                    <input class="ap-otp-input input-number" type="text" name="digit-3" id="digit-3" pattern="[0-9]" maxlength="1" data-index="2" data-next="digit-4" data-previous="digit-2">
                    <input class="ap-otp-input input-number" type="text" name="digit-4" id="digit-4" pattern="[0-9]" maxlength="1" data-index="3" data-next="digit-5" data-previous="digit-3">
                    <input class="ap-otp-input input-number" type="text" name="digit-5" id="digit-5" pattern="[0-9]" maxlength="1" data-index="4" data-next="digit-6" data-previous="digit-4">
                    <input class="ap-otp-input input-number" type="text" name="digit-6" id="digit-6" pattern="[0-9]" maxlength="1" data-index="5" data-previous="digit-5">

                    </div>
                    <div class="my-5">
                    <button type="button" id="OtpVerify" class="btn btn-primary OtpVerify"><img
                    src="{{ URL::asset('front-assets/images/icons/icon_placeorder.png') }}" alt="arrow
                    class="pe-1">{{ __('rfqs.place_order') }}</button>
                    </div>

                </div>



<!---  </form>--->
</div>
        </div>
            </div>

    </div>
    <div class="modal-footer d-flex error_res">
        <div class="terms">
            @if(!empty($quote->termsconditions_file) || (isset($tc_document) && !empty($tc_document->supplier_default_tcdoc)))
                <div class="form-check ps-4 d-flex align-items-center ">
                    <input class="form-check-input mt-0" type="checkbox" value="" data-parsley-errors-container="#terms_validate" name="terms" id="terms" data-parsley-error-message="{{__('admin.select_ckheckbox')}}" required>
                    <label class="form-check-label ms-2 text-dark" for="terms">
                        {{$quote->termsconditions_file ? (__('admin.supplieragree')) : (__('admin.blitznet_agree'))}} <a href="{{$quote->termsconditions_file ? Storage::url($quote->termsconditions_file) : Storage::url($tc_document->supplier_default_tcdoc)}}" target="_blank">{{__('admin.terms_condition')}}</a>
                    </label>
                </div>
                <span id="terms_validate" class="ps-4 d-inline-block"></span>
            @endif
        </div>
        <div class="ms-auto loan_order" style="display:none;">

                    <button type="button" id="nextOrderPage" class="btn btn-primary nextOrderPage"><img
                    src="{{ URL::asset('front-assets/images/icons/icon_placeorder.png') }}" alt="Continue"
                    class="pe-1">{{ __('rfqs.Continue') }}</button>

        </div>
        <div class="ms-auto loan_otp" style="display:none;">
            <button type="button" id="placeOrderBtnOtp" class="btn btn-primary placeOrderBtnOtp"><img
                    src="{{ URL::asset('front-assets/images/icons/icon_placeorder.png') }}" alt="Continue"
                    class="pe-1">{{ __('rfqs.Continue') }}</button>
            <button type="button" id="placeOrderBtnOtpback" class="btn btn-primary placeOrderBtnOtpback"><img
            src="{{ URL::asset('front-assets/images/icons/angle-left.png') }}"  alt="Back"
                    class="pe-1">Back</button>
        </div>
        <div class="ms-auto exist_order" style="display:none;">
            <button type="button" id="placeOrderBtn" class="btn btn-primary"><img
                    src="{{ URL::asset('front-assets/images/icons/icon_placeorder.png') }}" alt="Place order"
                    class="pe-1">{{ __('rfqs.place_order') }}</button>

        </div>
    </div>
</form>

<script>
    // $("#cust_ref_id").change(function(){
    $("#cust_ref_id").on("input", function() {
        $("#ref_msg").html('');
        $("#placeOrderBtn").prop('disabled', false);
    });


                $('.digit-group').find('input').each(function() {
                    $(this).attr('maxlength', 1);
                    $(this).on('keyup', function(e) {
                        var parent = $($(this).parent());

                        if(e.keyCode === 8 || e.keyCode === 37) {
                            var prev = parent.find('input#' + $(this).data('previous'));

                            if (prev.length) {
                                $(prev).select();
                            }

                        } else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                            if ($(this).val()!=null && $(this).val()!='') {
                                var next = parent.find('input#' + $(this).data('next'));

                                if (next.length) {
                                    $(next).select();
                                } else {
                                    if (parent.data('autosubmit')) {
                                        parent.submit();
                                    }
                                }
                            }
                        }
                    });
                });

    /*****begin: Place order Delivery Address******/
    var SnippetAddPlaceOrderDeliveryDetail = function(){

        var selectStateGetCity = function(){

                $('#stateId').on('change',function(){

                    let state = $(this).val();
                    let targetUrl = "{{ route('admin.city.by.state',":id") }}";
                    targetUrl = targetUrl.replace(':id', state);
                    var newOption = '';

                    // Add Remove Other State filed
                    if (state == -1) {
                        $('#stateId_block').removeClass('col-md-4');
                        $('#stateId_block').removeClass('col-md-6');
                        $('#stateId_block').addClass('col-md-3');

                        $('#state_block').removeClass('hide');
                        $('#state').attr('required','required');

                        $('#cityId_block').removeClass('col-md-4');
                        $('#cityId_block').addClass('col-md-3');

                        $('#cityId').empty();

                        //set default options on other state mode
                        newOption = new Option('{{ __('admin.select_city') }}','', true, true);
                        $('#cityId').append(newOption).trigger('change');

                        newOption = new Option('Other','-1', true, true);
                        $('#cityId').append(newOption).trigger('change');


                    } else {
                        $('#stateId_block').removeClass('col-md-3');
                        $('#stateId_block').addClass('col-md-4');

                        $('#state_block').addClass('hide');
                        $('#state').removeAttr('required','required');

                        $('#cityId_block').removeClass('col-md-6');
                        $('#cityId_block').addClass('col-md-4');

                        $('#city_block').addClass('hide');
                        $('#city').removeAttr('required','required');

                        //Fetch cities by state
                        if (state != '') {
                            $.ajax({
                                url: targetUrl,
                                type: 'POST',
                                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},

                                success: function (response) {

                                    if (response.success) {

                                        $('#cityId').empty();

                                        newOption = new Option('{{ __('admin.select_city') }}', '', true, true);
                                        $('#cityId').append(newOption).trigger('change');

                                        for (let i = 0; i < response.data.length; i++) {
                                            newOption = new Option(response.data[i].name, response.data[i].id, true, true);
                                            $('#cityId').append(newOption).trigger('change');
                                        }

                                        newOption = new Option('Other', '-1', true, true);
                                        $('#cityId').append(newOption).trigger('change');

                                        /*******begin:Add and remove last null option for no conflict*******/
                                        newOption = new Option('0', '0', true, true);
                                        $('#cityId').append(newOption).trigger('change');
                                        $('#cityId').each(function () {
                                            $(this).find("option:last").remove();
                                        });
                                        /*******end:Add and remove last null option for no conflict*******/

                                        let selectedAddressCity = $('#cityId').attr('data-selected-city');
                                        if (selectedAddressCity != null && selectedAddressCity != '') {
                                            $('#cityId').val(selectedAddressCity).trigger('change');
                                        } else {
                                            $('#cityId').val(null).trigger('change');
                                        }

                                    }

                                },
                                error: function () {

                                }
                            });
                        } else {
                            $('#cityId').empty();
                            //set default options on other state mode
                            newOption = new Option('{{ __('admin.select_city') }}','', true, true);
                            $('#cityId').append(newOption).trigger('change');
                            newOption = new Option('Other','-1', true, true);
                            $('#cityId').append(newOption).trigger('change');
                            $('#cityId').val(null).trigger('change');
                        }

                    }

                });

            },

            selectCitySetOtherCity = function(){

                $('#cityId').on('change',function(){

                    let city = $(this).val();

                    // Add Remove Other City filed
                    if (city == -1) {
                        $('#cityId_block').removeClass('col-md-4');
                        $('#cityId_block').addClass('col-md-3');

                        $('#city_block').removeClass('hide');
                        $('#city').attr('required','required');

                        $('#stateId_block').removeClass('col-md-4');
                        if ($('#stateId').val()>0) {
                            $('#stateId_block').addClass('col-md-4');
                        } else {
                            $('#stateId_block').addClass('col-md-3');
                        }

                    } else {
                        $('#cityId_block').removeClass('col-md-3');
                        $('#cityId_block').addClass('col-md-4');

                        $('#city_block').addClass('hide');
                        $('#city').removeAttr('required','required');

                    }

                });

            },

            initiateCityState = function(){

                let state               =   $('#state').val();
                let selectedState       =   $('#stateId').val();
                let selectedCity        =   $("#cityId").attr('data-selected-city');

                if (state != null && state !='') {
                    $('#stateId').val('-1').trigger('change');
                }

                if (selectedState !='' && selectedState != null) {
                    $('#stateId').val(selectedState).trigger('change');
                }

                if (selectedCity !='' && selectedCity!=null ) {

                    setTimeout(
                        function() {
                            $('#cityId').val(selectedCity).trigger('change')
                        },
                        500); //set delayed for 500 to run after city sync
                }

            },

            disableForm = function(){

                $('#placeOrderAddressForm').find('#useraddress_id').addClass('disable');
                $('#placeOrderAddressForm').find('#address_name').prop('readonly','readonly').addClass('disable');
                $('#placeOrderAddressForm').find('#sub_district').prop('readonly','readonly').addClass('disable');
                $('#placeOrderAddressForm').find('#district').prop('readonly','readonly').addClass('disable');
                $('#placeOrderAddressForm').find('#state').prop('readonly','readonly').addClass('disable');
                $('#placeOrderAddressForm').find('#stateId').addClass('disable');
                $('#placeOrderAddressForm').find('#city').prop('readonly','readonly').addClass('disable');
                $('#placeOrderAddressForm').find('#cityId').addClass('disable');
                $('#placeOrderAddressForm').find('#pincode').prop('readonly','readonly').addClass('disable');
                $('#placeOrderAddressForm').find('#useraddress_id').attr('readonly', true);
                $('#placeOrderAddressForm').find('#stateId').attr('readonly', true);
                $('#placeOrderAddressForm').find('#cityId').attr('readonly', true);


            };

        return {
            init:function(){
                selectStateGetCity(),
                selectCitySetOtherCity(),
                initiateCityState(),
                disableForm()
            }
        }

    }(1);jQuery(document).ready(function(){
        SnippetAddPlaceOrderDeliveryDetail.init();
    });
    /*****end: Place order Delivery Address******/

</script>


