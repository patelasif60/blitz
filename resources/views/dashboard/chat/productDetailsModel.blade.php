<div class="card-header d-flex align-items-center">
    {{--onclick="chat.chatPreviewPage('#allchatlist')"--}}
    <div class="head text-white">
        <span class="pe-2 d-none">
            <i class="fa fa-chevron-left text-white"></i>
        </span>{{ $header_group_name }}
    </div>
    <div class="head_end_text text-white ms-auto">
        <a href="javascript:void(0)" onclick="chat.chatProductViewData()" id="closeproductinfo">
            <span>
                <img src="{{ URL::asset('chat/images/closewhite.png')}}" alt="" srcset="">
            </span>
        </a>
    </div>
</div>
<div class="card-body pb-2">
    <div class="content h-100 ">
        <div class="chatwithsuppsection m-2 px-0 " >
            <div class="main w-100 py-2"  style="overflow-x: auto; height: 295px;">
                <div class="px-3">
                    <div class="d-flex headindnameproduct ">
                        <div class="col-9 colorproductnamehead pe-2">{{ __('admin.product_name') }}</div>
                        <div class="col-3 colorproductnamehead">{{ __('admin.qty') }}</div>
                    </div>
                    @foreach($productList as $product)
                        @php
                        if($chat_type == "Rfq"){
                            $productName = $product->category."-".$product->sub_category."-".$product->product;
                            $productQty = $product->quantity;
                            $units = (isset($product->unit)) ? $product->unit->name : '';
                        }elseif ($chat_type == "Quote"){
                            $productName = $product->rfqProduct->category."-".$product->rfqProduct->sub_category."-".$product->rfqProduct->product;
                            $productQty = $product->rfqProduct->quantity;
                            $units = (isset($product->rfqProduct->unit)) ? $product->rfqProduct->unit->name : '';
                            $totalPrice = (isset($product->quoteDetails)) ? $product->quoteDetails->final_amount : '';
                            $supplierCompanyName = (isset($product->quoteDetails->supplier)) ? $product->quoteDetails->supplier->name : '';
                        }
                        @endphp
                    <div class="d-flex headindnameproductlist my-2">
                        <div class="col-9 colorproductnamelist pe-2">{{$productName}}</div>
                        <div class="col-3 colorproductnamelist ">{{$productQty.' '.$units}}</div>
                    </div>
                        @if($chat_type == "Quote")
                            <div class="d-flex headindnameproduct ">
                                <div class="col-9 colorproductnamehead pe-2">{{ __('rfqs.total_price') }}</div>
                            </div>
                            <div class="d-flex headindnameproductlist my-2">
                                <div class="col-9 colorproductnamelist pe-2">{{ 'Rp ' . number_format($totalPrice, 2) }}</div>
                            </div>
                            <div class="d-flex headindnameproduct ">
                                <div class="col-9 colorproductnamehead pe-2">{{ __('rfqs.supplier_company_name') }}</div>
                            </div>
                            <div class="d-flex headindnameproductlist my-2">
                                <div class="col-9 colorproductnamelist pe-2">{{$supplierCompanyName}}</div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card-footer">
    <div class="card-footer_text">{{ __('dashboard.authorised_by') }} <strong>blitz<em><i>net</i></em></strong>
    </div>
</div>
