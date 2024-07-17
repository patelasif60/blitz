
@foreach($orderItems as $orderItem)
    @php
        $quoteItem = $orderItem->quoteItem()->first();
        $unit = get_unit_name($quoteItem->price_unit);
    @endphp
    <tr>
        <td>{{$orderItem->order_item_number}}</td>
        <td>{{get_product_name_by_id($orderItem->rfq_product_id,1)}}</td>
        <td>Rp {{number_format($quoteItem->product_price_per_unit,2)}} per {{$unit}}</td>
        <td>{{$quoteItem->product_quantity}} {{$unit}}</td>
        <td>{{$quoteItem->weights}}</td>
        <td align="right">Rp {{number_format($quoteItem->product_amount,2)}}</td>
    </tr>
@endforeach
