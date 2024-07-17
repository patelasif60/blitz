{{--<div class="card-body p-3 pb-1">--}}
    @if($supplierProductDiscountRanges->count() > 0)
        <div id="mainDiv">
            @php $key=0;
            //dd($supplierProductDiscountRanges);
            //$lastArrayKey = array_key_last($supplierProductDiscountRanges);
            $lastArrayKey = $supplierProductDiscountRanges->count() -1;
            @endphp
            <div class="row productDiv g-2" id="group_productDiv">
                <input type="hidden" id="s_id" name="s_id[]" value="{{ !empty($supplierProductDiscountRanges) ? $supplierProductDiscountRanges[0]->id : 0 }}">
                <input type="hidden" id="custom_add" name="custom_add[]" value="0">
                <div class="col-md-2 mb-3">
                    <label for="min_qty" class="form-label min_qty">{{ __('admin.min_quantity') }}<span class="text-danger">*</span></label>
                    <input {{$key == $lastArrayKey ? '' : 'readonly'}} type="text" id="min_qty" name="min_qty[]" class="form-control cloanMinQty" onchange="minQty(this)" onkeypress="return isNumberKey(this, event);" required value="{{ !empty($supplierProductDiscountRanges) ? $supplierProductDiscountRanges[0]->min_qty : 0 }}">
                    <div id="showErrMin" name="showErrMin[]"></div>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="max_qty" class="form-label max_qty">{{ __('admin.max_quantity') }}<span class="text-danger">*</span></label>
                    <input {{$key == $lastArrayKey ? '' : 'readonly'}} type="text" id="max_qty" name="max_qty[]" class="form-control cloanMaxQty" onchange="maxQty(this)" onkeypress="return isNumberKey(this, event);" required value="{{ !empty($supplierProductDiscountRanges) ? $supplierProductDiscountRanges[0]->max_qty : 0 }}">
                    <div id="showErr" name="showErr[]"></div>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="unit" class="form-label unit">{{ __('admin.select_unit') }}<span class="text-danger">*</span></label>
                    <select class="form-select unitVal" id="unit" name="unit[]" disabled>
                        <option disabled selected>{{ __('admin.select_unit') }}</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" {{ !empty($unit) && $unit->id == $supplierProductDiscountRanges[0]->unit_id ? "selected" : '' }} >{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="discount" class="form-label discount">{{ __('admin.discount') }}%<span class="text-danger">*</span></label>
                    <input {{$key == $lastArrayKey ? '' : 'readonly'}} type="text" id="discount" name="discount[]" class="form-control discountVal" required value="{{ !empty($supplierProductDiscountRanges) ? $supplierProductDiscountRanges[0]->discount : 0 }}" onkeypress="return isNumberKey(this, event);">
                    <div id="showErrDiscount" name="showErrDiscount[]"></div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="discount_price" class="form-label discount">Discount Price</label>
                    <input type="text" id="discount_price" name="discount_price[]" class="form-control discountAmount" value="{{ !empty($supplierProductDiscountRanges) ? $supplierProductDiscountRanges[0]->discounted_price : 0 }}" readonly>
                </div>
                <div class="col-md-1 pt-1 mb-3 ">
                    <label for="" class="form-label"></label>
                    <div style="line-height: 38px;" id="deleteBtn" name="deleteBtn[]">
                        <span class="icon deleteRange d-none"><a href="javascript:void(0)" id="deleteBtn" class="text-danger removeRange "><i class="fa fa-trash mt-3"></i></a></span>
                    </div>
                </div>
            </div>
        </div>
        <div id="cloneDiv">
            @if($supplierProductDiscountRanges)
                {{-- @php $lastArrayKey = array_key_last($supplierProductDiscountRanges); @endphp--}}
                @foreach($supplierProductDiscountRanges as $key => $value)
                    @if($key==0) @continue @endif
                    <div class="row productDiv g-2" id="group_productDiv">
                        <input type="hidden" id="s_id{{$key}}" name="s_id[]" value="{{ $value->id }}">
                        <input type="hidden" id="custom_add{{$key}}" name="custom_add[]" value="0">
                        <div class="col-md-2 mb-3">
                            <label for="min_qty" class="form-label min_qty">{{ __('admin.min_quantity') }}<span class="text-danger">*</span></label>
                            <input readonly type="text" id="min_qty{{$key}}" name="min_qty[]" class="form-control cloanMinQty" onchange="minQty(this)" onkeypress="return isNumberKey(this, event);" required value="{{ $value->min_qty }}">
                            <div id="showErrMin{{$key}}" name="showErrMin[]"></div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="max_qty" class="form-label max_qty">{{ __('admin.max_quantity') }}<span class="text-danger">*</span></label>
                            <input {{$key == $lastArrayKey ? '' : 'readonly'}} type="text" id="max_qty{{$key}}" name="max_qty[]" class="form-control cloanMaxQty" onchange="maxQty(this)" onkeypress="return isNumberKey(this, event);" required value="{{ $value->max_qty }}">
                            <div id="showErr{{$key}}" name="showErr[]"></div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="unit" class="form-label unit">{{ __('admin.select_unit') }}</label>
                            <select class="form-select unitVal" id="unit" name="unit[]" disabled>
                                <option disabled selected>{{ __('admin.select_unit') }}</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" {{ !empty($unit) && $unit->id == $value->unit_id ? "selected" : '' }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="discount" class="form-label discount">{{ __('admin.discount') }}%<span class="text-danger">*</span></label>
                            <input {{$key == $lastArrayKey ? '' : 'readonly'}} type="text" id="discount{{$key}}" name="discount[]" class="form-control discountVal" required value="{{ $value->discount }}" onkeypress="return isNumberKey(this, event);">
                            <div id="showErrDiscount{{$key}}" name="showErrDiscount[]"></div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="discount_price" class="form-label discount">Discount Price</label>
                            <input type="text" id="discount_price{{$key}}" name="discount_price[]" class="form-control discountAmount" value="{{ !empty($supplierProductDiscountRanges) ? $value->discounted_price : 0 }}" readonly>
                        </div>
                        <div class="col-md-1 pt-1 mb-3 ">
                            <label for="" class="form-label"></label>
                            <div style="line-height: 38px;">
                            <span class="icon deleteRange {{$key == $lastArrayKey ? '' : 'd-none'}} " id="deleteBtn{{$key}}" name="deleteBtn[]">
                                <a href="javascript:void(0)" id="deleteBtn{{$key}}" class="text-danger removeRange "><i class="fa fa-trash mt-3"></i></a>
                            </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    @else
        <div id="mainDiv">
            <div class="row productDiv g-2" id="group_productDiv">
                <input type="hidden" id="s_id" name="s_id[]" value="0">
                <input type="hidden" id="custom_add" name="custom_add[]" value="1">
                <div class="col-md-2 mb-3">
                    <label for="min_qty" class="form-label min_qty">{{ __('admin.min_quantity') }}<span class="text-danger">*</span></label>
                    <input type="text" id="min_qty" name="min_qty[]" class="form-control cloanMinQty" onchange="minQty(this)" onkeypress="return isNumberKey(this, event);" required>
                    <div id="showErrMin" name="showErrMin[]"></div>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="max_qty" class="form-label max_qty">{{ __('admin.max_quantity') }}<span class="text-danger">*</span></label>
                    <input type="text" id="max_qty" name="max_qty[]" class="form-control cloanMaxQty" onchange="maxQty(this)" onkeypress="return isNumberKey(this, event);" required>
                    <div id="showErr" name="showErr[]"></div>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="unit" class="form-label unit">{{ __('admin.select_unit') }}<span class="text-danger">*</span></label>
                    <select class="form-select unitVal" id="unit" name="unit[]" disabled>
                        <option disabled selected>{{ __('admin.select_unit') }}</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="discount" class="form-label discount">{{ __('admin.discount') }}%<span class="text-danger">*</span></label>
                    <input type="text" id="discount" name="discount[]" class="form-control discountVal" required onkeypress="return isNumberKey(this, event);">
                    <div id="showErrDiscount" name="showErrDiscount[]"></div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="discount_price" class="form-label discount">Discount Price</label>
                    <input type="text" id="discount_price" name="discount_price[]" class="form-control discountAmount"  readonly>
                </div>
                <div class="col-md-1 pt-1 mb-3 ">
                    <label for="" class="form-label"></label>
                    <div style="line-height: 38px;" id="deleteBtn" name="deleteBtn[]">
                        <span class="icon deleteRange d-none"><a href="javascript:void(0)" id="deleteBtn" class="text-danger removeRange "><i class="fa fa-trash mt-3"></i></a></span>
                    </div>
                </div>
            </div>
        </div>
        <div id="cloneDiv"></div>
    @endif
{{--</div>--}}
