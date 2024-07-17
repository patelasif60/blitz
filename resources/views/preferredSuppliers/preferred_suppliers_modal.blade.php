@if(Auth::user()->role_id == 2)
    <ul class="list-group">
        @if(isset($preferredSuppliers) && sizeof($preferredSuppliers) > 0)
            @foreach($preferredSuppliers as $supplier)
            <li class="list-group-item border-0 border-bottom">
                <input type="checkbox" class="form-check-input me-1 suppCheckBox" name="supplier_chk" id="supp_{{ $supplier->preferredSuppId }}" value="{{ $supplier->preferredSuppId }}" checked>
                {{ $supplier->companyName }}
            </li>
            @endforeach
        @endif
    </ul>
    <!-- <div class="px-1 text-center text-danger" id="status"></div> -->
@else 
    <ul class="list-group">
    @if(isset($preferredSuppliers) && sizeof($preferredSuppliers) > 0)
        @foreach($preferredSuppliers as $supplier)
            <li class="list-group-item border-0 border-bottom">
                <div class="row align-items-center">
                    <div class="prefer_supplier col-6">
                        <input type="checkbox" class="form-check-input me-1 suppCheckBox" name="supplier_chk" id="supp_{{ $supplier->id }}" value="{{ $supplier->id }}" checked disabled>
                        <a href="{{ route('supplier-edit', ['id' => Crypt::encrypt($supplier->id)]) }}" target="_blank" class="hover_underline" style="text-decoration: none; color: #000"> {{ $supplier->name }}</a>
                    </div>
                    <div class="dealing-rfq col-3">
                        <div class="{{ (isset($supplier['products']) && sizeof($supplier['products']) > 0) ? 'text-dark' : 'text-danger' }} fw-bold text-center">{{ (isset($supplier['products']) && sizeof($supplier['products']) > 0) ? 'Yes' : 'No' }} </div>
                    </div>
                    <div class="dealing-rfq col-3">
                        <div class="text-dark fw-bold text-center">
                            <button class="btn btn-default btn-sm {{ (isset($supplier['products']) && sizeof($supplier['products']) > 0) ? 'border-primary p-1' : ''}} " title="{{ $supplier['products']->implode('name',', ') }}">{{ (isset($supplier['products']) && sizeof($supplier['products']) > 0) ? count($supplier['products']) : '-' }}</button>
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    @endif
    </ul>
    <!-- <div class="px-1 text-center text-danger" id="status"></div> -->
@endif