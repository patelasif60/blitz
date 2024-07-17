<div class="card border-0 h-100 rounded-0">
    <div class="message-right-content  px-3">
        <div class="col-md-12 d-flex align-items-center my-3 mb-4">
            @php if($header_name == "Rfq") { $header_title =  __('admin.rfq_info');}elseif($header_name == "Quote"){$header_title = __('admin.quote_info'); }; @endphp
            <h5 class=" fs-6 text-muted mb-0"
            >{{$header_title}}</h5>
            <button type="button"
                    class="btn-close cancel-button ms-auto" onclick="closebtn('{{$header_name}}')" style="font-size: 12px;"
                    data-bs-dismiss="offcanvas" aria-label="Close"></button>

        </div>
        <div class=" my-3">
            <div class="right-labels d-flex align-items-center">
                <div class="col-md-8 f-12 text-muted pe-2">
                    {{ __('admin.product_name') }}:
                </div>
                <div class="col-md-4  f-12 text-muted">
                    {{ __('admin.qty') }}:
                </div>
            </div>
            @if(!empty($productInfo))
                @foreach($productInfo as $productDetail)
                    @php
                        if($header_name == "Rfq"){
                            $productName = $productDetail->category."-".$productDetail->sub_category."-".$productDetail->product;
                            $productQty = $productDetail->quantity;
                            $units = (isset($productDetail->unit)) ? $productDetail->unit->name : '';
                        }elseif ($header_name == "Quote"){
                            $productName = $productDetail->rfqProduct->category."-".$productDetail->rfqProduct->sub_category."-".$productDetail->rfqProduct->product;
                            $productQty = $productDetail->rfqProduct->quantity;
                            $units = (isset($productDetail->rfqProduct->unit)) ? $productDetail->rfqProduct->unit->name : '';
                            $totalPrice = (isset($productDetail->quoteDetails)) ? $productDetail->quoteDetails->final_amount : '';
                        }
                    @endphp
                    <div class="right-product-details d-flex align-items-start pb-1 my-2">
                        <div class="col-md-8 f-13 right-details fw-bold pe-4">
                            {{$productName}}
                        </div>
                        <div class="col-md-4 f-13 me-4 fw-bold">{{$productQty.' '.$units}}</div>
                    </div>

                @endforeach
                    @if($header_name == "Quote")
                        <div class="right-labels d-flex align-items-center">
                            <div class="col-md-8 f-12 text-muted pe-2">
                                {{ __('rfqs.total_price') }}
                            </div>
                        </div>
                        <div class="right-product-details d-flex align-items-start pb-1 my-2">
                            <div class="col-md-8 f-13 right-details fw-bold pe-4">{{ 'Rp ' . number_format($totalPrice, 2) }}</div>
                        </div>
                    @endif
            @endif

        </div>
        @if($header_name == "Rfq")
            <div class="col-md-12 my-3 mt-4">
                <div class="right-comment">
                    <div class="right-product-comment f-12 text-muted">
                        {{ __('admin.comments') }}:
                    </div>
                </div>
                @if(!empty($productInfo))
                    @foreach($productInfo as $productDetail)
                <div class="product-comment my-3">
                    <div class="col-md-12 f-13 right-comments fw-bold">
                        {{$productDetail->product_description}}
                    </div>
                </div>
                    @endforeach
                @endif
            </div>
        @endif
        @if(Auth::user()->hasRole('admin'))
            <div class="col-md-12 my-3 mt-4">
                <div class="right-comment">
                    <div class="right-product-comment text-muted ">
                        {{ __('admin.supplier_company_list') }}
                    </div>
                </div>
                @if(!empty($supplierList))
                    @foreach($supplierList as $supplierName)
                        @php //$name = $supplierName->user->supplier()->pluck('name')->first();
                            if($header_name == "Rfq"){
                                $supplierId = $supplierName->supplier->id;
                                $supplieName = $supplierName->supplier->name;
                            }elseif ($header_name == "Quote"){
                                $supplierId = $supplierName->id;
                                $supplieName = $supplierName->name;
                            }
                        @endphp
                        @if (in_array($supplierId, $userSupplierList))
                        <div class="product-comment my-3">
                            <p class="col-md-12 right-comments fw-bold f-13">
                                {{$supplieName}}
                            </p>
                        </div>
                        @else
                            <div class="product-comment my-3">
                                <p class="col-md-12 right-comments fw-bold f-13">
                                    {{$supplieName}}<button class="btn btn-primary ms-2 p-1" title="{{ __('admin.invite_for_login') }}" id="inviteSupplierButton" onclick="chat.inviteSupplier('{{ route('invite-as-buyer','').'/'.$supplierId }}','{{$supplierId}}')" style="font-size: 10px;">{{__('admin.invite_for_login')}}</button>
                                    <img class="hidden" id="loaderinviteSupplier" height="16px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading">
                                </p>
                            </div>
                        @endif
                    @endforeach

                @endif
            </div>
        @endif
    </div>
</div>
