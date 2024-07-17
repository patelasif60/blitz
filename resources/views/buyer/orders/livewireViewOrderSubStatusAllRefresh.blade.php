<table class="table mb-0">
    <thead class="table-light h-100">
    <tr>
        <th>{{ __('order.description') }}</th>
        <th width="20%" class="text-center text-nowrap">{{ __('order.qty') }}</th>
        <th width="20%" class="text-center text-nowrap">{{ __('order.status') }}</th>
        <th width="20%" class="text-end text-nowrap">{{ __('order.amount') }}</th>
    </tr>
    </thead>
<tbody>
@php
    $quote_items = $order->quoteItems;
    $countOrderItems = 0;
    $countOrderItemsAmount = 0;
    $i=0;
@endphp
@if(!empty($quote_items))
    @foreach($quote_items as $key => $value)
        @php
            $orderItem = $value->orderItem;
            $orderAirwayBill = $order->orderAirwayBill;
            $i++;
            if (!empty($orderItem)){
                $countOrderItems += 1;
                $countOrderItemsAmount += $orderItem->product_amount;
            }
            foreach ($order->orderItemStatus as $orderItemStat)
            {
                if(isset($order->orderItemTracksId[$orderItem->id]))
                {
                    $orderItemStat->order_track_id = $order->orderItemTracksId[$orderItem->id];
                    $orderItemStat->created_at = $order->orderItemTracksdate[$orderItem->id];
                }
                else
                {
                    $orderItemStat->order_track_id = '';
                    $orderItemStat->created_at = '';
                }
            }
            $hideStatusIds = [7, 8, 9];
            $countProcess =$order->orderItemStatus->where('order_track_id', '<>',null)->count();

            if ($countProcess == 8){
                $showPrgressArray = ['1' => 7.5,'2'=>20.5,'3'=>35,'4'=>46.5,'5'=>56.5,'6'=>68.5, '8' => 68.5, '9'=>83.5,'10'=>100];
                $showPrgress = !empty($orderItem->order_item_status_id)?$showPrgressArray[$orderItem->order_item_status_id]:0;
            } else {
                $showPrgressArray = ['1' => 8,'2'=>24.5,'3'=>41.5,'4'=>55,'5'=>67,'6'=>82, '7' => 82, '8'=>82, '9'=>82, '10'=>100];
                $showPrgress = !empty($orderItem->order_item_status_id)?$showPrgressArray[$orderItem->order_item_status_id]:0;
            }
        @endphp
          @if(in_array($orderItemCategoryId,\App\Models\Category::SERVICES_CATEGORY_IDS)  && ($orderlogisticProvided->logistic_check==1 && $orderlogisticProvided->logistic_provided ==0) && $i==1)
            <tr>
                {{view('buyer/orders/LivewireSingleOrderItemStatusRefresh', ['orderItem'=>$orderItem,'orderAirwayBill'=>$orderAirwayBill,'orderItemCategory'=>$orderItemCategory,'orderItemCategoryId'=>$orderItemCategoryId,'orderlogisticProvided'=>$orderlogisticProvided, 'allOrderStatus'=>get_order_sub_status($orderItem->id),'order' => $order,'countProcess'=>$countProcess])}}
            </tr>
        @endif

        <tr class="b-none" id="openProduct{{$orderItem->id}}">
            <td class="align-middle">
                <div class="d-flex">
                @if(in_array($orderItemCategoryId,\App\Models\Category::SERVICES_CATEGORY_IDS) && ($orderlogisticProvided->logistic_check==1 && $orderlogisticProvided->logistic_provided ==1) || !in_array($orderItemCategoryId,\App\Models\Category::SERVICES_CATEGORY_IDS))
                    <button data-bs-toggle="collapse" type="button" onclick="productWiseRefresh({{$orderItem->id}})" href="#collapseExample{{$orderItem->id}}" role="button" aria-expanded="false" aria-controls="collapseExample" class="border-0 plus_minus bg-transparent collapsed"></button>
                @endif
                <span class="prod_name">{{  $value->rfqProduct->category .' - '. $value->rfqProduct->sub_category .' - '. $value->rfqProduct->product .' - '. $value->rfqProduct->product_description }}</span>
                </div>
            </td>
            <td class="text-center text-nowrap  align-middle">{{ $value->product_quantity??'' }} {{ $value->unit_name??'' }}</td>
            <td class="text-center text-nowrap  align-middle">
                <span class="badge bg-primary" style="font-size: .7em;" id="orderItemStatusNameBlock{{$orderItem->id}}"> 
                    @if($value->orderItem->orderItemStatus)
                    {{ __('order.'.$value->orderItem->orderItemStatus->name) }}
                    @else
                     Not Ready At
                    @endif
                </span>
            </td>
            <td class="text-end text-nowrap  align-middle">Rp {{ number_format($value->product_price_per_unit, 2) }}</td>
        </tr>
        @if((in_array($orderItemCategoryId,\App\Models\Category::SERVICES_CATEGORY_IDS) && ($orderlogisticProvided->logistic_check==1 && $orderlogisticProvided->logistic_provided ==1)) || !in_array($orderItemCategoryId,\App\Models\Category::SERVICES_CATEGORY_IDS))
        <tr class="tab_col collapse" id="collapseExample{{$orderItem->id}}">
            {{view('buyer/orders/LivewireSingleOrderItemStatusRefresh', ['orderItem'=>$orderItem,'orderAirwayBill'=>$orderAirwayBill,'orderItemCategory'=>$orderItemCategory,'orderlogisticProvided'=>$orderlogisticProvided, 'allOrderStatus'=>get_order_sub_status($orderItem->id),'orderItemCategoryId'=>$orderItemCategoryId,'order' => $order,'countProcess'=>$countProcess])}}
        </tr>
         @endif
    @endforeach
@endif
</tbody>
<tfoot>
<tr class="bg-light">
    <td  colspan="3" class="fw-bold">{{ __('order.total') }}</td>
    <td class="text-end text-nowrap fw-bold align-middle">
        @php
            $billedAmount = $order->product_final_amount;
            $bulkOrderDiscount = $order->bulk_discount;
        @endphp
        @if($bulkOrderDiscount>0)
            @php
                $billedAmount = $billedAmount-$bulkOrderDiscount;
            @endphp
        @endif
        <span class="align-middle">{{ 'Rp ' . number_format($billedAmount, 2) }}</span>
        <span href="javascript:voic(0);" class="tooltiphtml">
            <img src="{{ URL::asset('front-assets/images/icons/eye_blue.png') }}" alt="" class="align-middle">
            <div class="tooltip_section text-dark text-start">
                <div class="row mx-0">
                    <div class="col-12">
                        <div class="row dark_blue_bg text-white py-1">
                            <div class="col-12 text-wrap d-flex p-2">
                                <div> {{ $countOrderItems }} Products</div>
                                <div class="ms-auto"> Rp. {{ number_format( $countOrderItemsAmount, 2) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        @isset($order->quotes_charges_with_amounts)
                            @foreach ($order->quotes_charges_with_amounts as $charges)
                                @if($charges->charge_amount > 0)
                                <div class="row">
                                        @if ($charges->type == 0)
                                        <div class="col-6 py-1 text-wrap">@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif {{ $charges->charge_name . ' ' . $charges->charge_value }} %</div>
                                    @else
                                        <div class="col-6 py-1 text-wrap">@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif {{ $charges->charge_name }}
                                        </div>
                                    @endif
                                        <div class="col-6 py-1 text-end">
                                            {{ $charges->addition_substraction == 0 ? '- ' : '+ ' }}{{ 'Rp ' . number_format($charges->charge_amount, 2) }}
                                        </div>
                                </div>
                                @endif
                            @endforeach
                        @endisset
                        @if($order->tax > 0 && $order->tax_value > 0)
                        <div class="row">
                            <div class="col-6 py-1">{{ __('admin.tax') }} {{ $order->tax }} % </div>
                            <div class="col-6 py-1 text-end">+ {{ 'Rp ' . number_format($order->tax_value, 2) }}</div>
                        </div>
                        @endif
                        @if($bulkOrderDiscount>0)
                        <div class="row">
                            <div class="col-6 py-1">{{ __('admin.bulk_payment_discount') }}</div>
                            <div class="col-6 py-1 text-end">- Rp {{ number_format($bulkOrderDiscount, 2) }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="row mx-0">
                        <hr class="my-1">
                    <div class="col-12 fw-bold pb-1">
                        <div class="row">
                            <div class="col-6 py-1">{{ __('order.total') }}</div>
                            <div class="col-6 py-1 text-end">{{ 'Rp ' . number_format($billedAmount, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </span>
    </td>
</tr>
</tfoot>
</table>
