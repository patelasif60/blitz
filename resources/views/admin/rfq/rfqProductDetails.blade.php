<div class="accordion error_res" id="accordionExample">
    @foreach($total_rfq_products as $key => $value)
        <div class="accordion-item mb-1 ">
            
                @php
                  $quote_item = [];
                    if (!empty($edit_product) && $edit_product != 0){
                        $quote_item = get_quote_item_by_id($value['id'], $edit_product);
                    }
                @endphp
                <h2 class="accordion-header d-flex bg-light" id="heading{{$key}}">
                    <div style=" box-shadow: inset 0 -1px 0 rgb(0 0 0 / 13%);">
                        <input type="checkbox" class="form-check product-detail-check ms-3 productCheckbox {{ in_array($value['product_id'], $product_lists) ? '' : 'assignProduct' }} blink_me" id="checkrfq_{{$key+1}}" data-key="{{$key+1}}" data-product-details="{{ json_encode($value) }}" {{ !empty($quote_item['id'])? 'checked': ''}} name="checkrfq[]" onclick="enabledDisabled(this, {{$key+1}})">
                        <input type="hidden" id="product_id_{{$key+1}}" name="product_id[]" value="{{ $value['product_id'] }}" disabled>
                    </div>
                    <button class="accordion-button px-2 py-2 collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{$key}}" aria-expanded="false" aria-controls="collapse_{{$key}}" style=" box-shadow: inset 0 -1px 0 rgb(0 0 0 / 13%);">
                        <div class="flex-grow-1">{{ $key+1 }}. {{ $value['category'] }} - {{ $value['sub_category'] }} - {{ $value['product'] }}</div>
                        {{--<div class="px-2"><span class="fw-normal text-muted">{{ __('admin.qty') }}:</span> {{ $value['quantity'] }} {{ $value['name'] }}</div>--}}
                        <div class="px-2"><span class="fw-normal text-muted">{{ __('admin.amount') }}:</span> Rp <span id="change_amount_{{$key+1}}">{{ $quote_item->product_amount??0.00 }}</span></div>
                    </button>
                </h2>
                <div id="collapse_{{$key}}" class="accordion-collapse collapse" aria-labelledby="heading{{$key}}"
                     data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row mb-3">
                            <div class="col-md-5">
                                <div class="row g-3">
                                    @if(!empty($value['product_description']))
                                    <div class="col-md-12 mb-2">
                                        <label for="">{{ __('admin.description') }}</label>
                                        <div>{{ $value['product_description']}}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row g-3">
                                    <div class="col-md-3 mb-2">
                                        @if($quote != null && auth()->user()->role_id == 1)
                                            <label class="form-label" for="">{{ __('admin.quantity') }}<small>({{ $value['name'] }})</small><span class="text-danger">*</span></label>
                                            <div class="">
                                                <input type="text"
                                                                name="rfq_products[{{ $value['id'] }}][quantity]"
                                                                id="rfq_product_qty{{$key+1}}"
                                                                class="form-control rfq_product_qty"
                                                                value="{{ $value['quantity'] }}"
                                                                min="1"
                                                                onkeyup="rfqProductQtyChange({{ $value['quantity'] }},{{ !empty($quote_item['id'])? $quote_item['product_price_per_unit']:'' }} , this.value, {{ $key+1 }})"
                                                                onkeypress="return isNumberKey(this, event);"
                                                                disabled>
                                            </div>
                                        @elseif($quote != null && auth()->user()->role_id == 3)
                                            <label class="form-label" for="">{{ __('admin.quantity') }}</label>
                                            <div class="">{{ $value['quantity'] }} {{ $value['name'] }}</div>
                                        @else
                                            <label class="form-label" for="">{{ __('admin.quantity') }}({{ $value['name'] }})<span class="text-danger">*</span></label>
                                            <div class="">
                                                <input type="text"
                                                    name="rfq_products[{{ $value['id'] }}][quantity]"
                                                    id="rfq_product_qty{{$key+1}}"
                                                    class="form-control rfq_product_qty"
                                                    value="{{ $value['quantity'] }}"
                                                    min="1"
                                                    onkeyup="rfqProductQtyChange({{ $value['quantity'] }},{{ !empty($quote_item['id'])? $quote_item['product_price_per_unit']:'' }} , this.value, {{ $key+1 }})"
                                                    onkeypress="return isNumberKey(this, event);"
                                                    disabled>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3 col-lg-3 mb-0 d-none">
                                        <label class="form-label">{{ __('admin.product_dimensions') }}</label>
                                        <div>
                                            <input type="text" name="dimensions[]" id="dimensions{{$key+1}}" class="form-control" value="{{ !empty($quote_item['id'])? $quote_item['dimensions']:'' }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3 mb-0">
                                        <label class="form-label">{{ __('admin.length') }} <small>({{ __('admin.cm') }})</small>@if(Auth::user()->role_id != 3)<span class="text-danger">*</span> @endif</label>
                                        <div>
                                            <input type="text" name="length[]" id="length{{$key+1}}" onkeypress="return isNumberKey(this, event);" class="form-control" min="1" value="{{ (!empty($quote_item['id']) && $quote_item['length'] != 0 ) ? $quote_item['length'] : '1' }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3 mb-0">
                                        <label class="form-label">{{ __('admin.width') }} <small>({{ __('admin.cm') }})</small>@if(Auth::user()->role_id != 3)<span class="text-danger">*</span> @endif</label>
                                        <div>
                                            <input type="text" name="width[]" id="width{{$key+1}}" onkeypress="return isNumberKey(this, event);" class="form-control" min="1" value="{{ (!empty($quote_item['id']) && $quote_item['width'] != 0 ) ? $quote_item['width'] : '1' }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3 mb-0">
                                        <label class="form-label">{{ __('admin.height') }} <small>({{ __('admin.cm') }})</small>@if(Auth::user()->role_id != 3)<span class="text-danger">*</span> @endif</label>
                                        <div>
                                            <input type="text" name="height[]" id="height{{$key+1}}" onkeypress="return isNumberKey(this, event);" class="form-control" min="1" value="{{ (!empty($quote_item['id']) && $quote_item['height'] != 0 ) ? $quote_item['height'] : '1' }}" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5">
                                    <div class="row g-3">
                                        <div class="col-md-6 col-lg-6 mb-2 pe-1">
                                            <label class="form-label">{{ __('admin.product_estimated_weight') }} <small>({{ __('admin.per_unit') }})</small><span class="text-danger">*</span></label>
                                            <div class="proEst_input">
                                                <input type="text" name="weights[]" id="weights{{$key+1}}" min="0.1" class="form-control" disabled onkeyup="weightChange(this.value, {{ $value['quantity'] }},{{ $key+1 }})" value="{{ !empty($quote_item['id'])? $quote_item['weights']:'' }}" onblur="isDecimalNumberKey(this.value,{{ $key+1 }});" onkeypress="return isNumberKey(this, event);">
                                                <!-- <span class="input-group-text bg-white border-start-0" id="basic-addon2">Kg</span> -->
                                                <input type="hidden" id="qty_{{$key+1}}" name="qty[]" value="{{ $value['quantity'] }}" disabled>
                                                <input type="hidden" id="unit_id_{{$key+1}}" name="unit_id[]" value="{{ $value['unit_id'] }}" disabled>
                                                <input type="hidden" id="rfq_product_id{{$key+1}}" name="rfq_product_id[]" value="{{ $value['id'] }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 mb-2 ps-3">
                                            <label class="form-label">{{ __('admin.total_estimated_weight') }}</label>
                                            <div class="text-dark mt-1"> <span id="change_weight_{{$key+1}}">{{ !empty($quote_item['id'])? $quote_item['weights']*$quote_item['product_quantity']:0 }}</span> Kg</div>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row g-3">
                                    <div class="col-md-6 col-lg-6 mb-2">
                                        <label class="form-label">{{ __('admin.price') }} <small>({{ __('admin.per_unit_in_rp') }})</small><span class="text-danger">*</span></label>
                                        <div>
                                        @if($groupId != null)
                                            <span>{{ !empty($quote_item['id'])? $quote_item['product_price_per_unit']:'' }}</span>
                                            <input type="hidden" autocomplete="off" name="price[]" id="price{{$key+1}}" min="1" class="form-control" {{ !empty($quote_item['id'])? 'required': ''}}  disabled  onkeyup="priceValueChange(this.value, {{ $value['quantity'] }}, {{ $key+1 }})" onkeypress="return isNumberKey(this, event);" value="{{ !empty($quote_item['id'])? $quote_item['product_price_per_unit']:'' }}">
                                        @else
                                            <input type="text" autocomplete="off" name="price[]" id="price{{$key+1}}" min="1" class="form-control" {{ !empty($quote_item['id'])? 'required': ''}}  disabled  onkeyup="priceValueChange(this.value, {{ $value['quantity'] }}, {{ $key+1 }})" onkeypress="return isNumberKey(this, event);" value="{{ !empty($quote_item['id'])? $quote_item['product_price_per_unit']:'' }}">
                                        @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 mb-3">
                                        <label class="form-label">{{ __('admin.product_certificate') }}</label>
                                        <div class="d-flex">
                                            <span class=""><input disabled type="file" name="certificate[]" id="certificate{{$key+1}}" accept=".jpg,.png,.pdf" onchange="show(this, {{ $key+1 }})" hidden=""><label id="upload_btn" for="certificate{{$key+1}}">{{ __('admin.browse') }}</label></span>
                                            <div id="file-certificate{{$key+1}}">

                                                @if (!empty($quote_item) && !empty($quote_item['certificate']))
                                                    <span class="ms-2"><a href="javascript:void(0);" id="certificateFileDownload" onclick="downloadCertificate('{{ $quote_item['id'] }}', 'certificate', '{{ Str::substr($quote_item['certificate'], stripos($quote_item['certificate'], 'certificate_') + 12) }}')" title="{{ Str::substr($quote_item['certificate'], stripos($quote_item['certificate'], 'certificate_') + 12) }}" style="text-decoration: none;"> {{ Str::substr($quote_item['certificate'], stripos($quote_item['certificate'], 'certificate_') + 12) }} </a></span>
                                                    @if(!Auth::user()->hasRole('jne'))
                                                        <span class="removeFile" id="certificatetFile" data-filekeyId="{{ $key+1 }}" data-id="{{ $quote_item['id'] }}" file-path="{{ $quote_item['certificate'] }}" data-name="certificate"><a href="#" title="Remove Certificate"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a></span>
                                                    @endif
                                                    <a href="javascript:void(0);" id="certificateFileDownload" onclick="downloadCertificate('{{ $quote_item['id'] }}', 'certificate', '{{ Str::substr($quote_item['certificate'], stripos($quote_item['certificate'], 'certificate_') + 12) }}')"><i class="fa fa-cloud-download"></i></a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
        </div>
    @endforeach
</div>
