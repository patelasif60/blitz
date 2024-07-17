    <div class="row">
        <div class="col-md-6">
            <div class="detail_1">
                <p><strong>Name: </strong>{{ $quote->firstname . ' ' . $quote->lastname }}</p>
            </div>
            <p><strong>Company: </strong>{{ $quote->user_company_name }}</p>
            <p><strong>Pin Code: </strong>{{ $quote->pincode }}</p>
            <div class="detail_1">
                <p><strong>Mobile Number: </strong>{{ $quote->mobile }}</p>
                <p><strong>Valid Till: </strong>{{ date('d-m-Y', strtotime($quote->valid_till)) }}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="detail_1">
                <p><strong>Quote Number: </strong>{{ $quote->quote_number }}</p>
            </div>
            <p><strong>Date: </strong>{{ date('d-m-Y H:i:s', strtotime($quote->created_at)) }}</p>
            <div class="detail_1">
                <p><strong>RFQ Number: </strong>{{ $quote->reference_number }}</p>
            </div>
            <p><strong>Supplier Company: </strong>{{ $quote->supplier_company }}</p>
            <p><strong>Supplier Name: </strong>{{ $quote->supplier_name }}</p>
        </div>

    </div>

    <table class="border table">
        <tr>
            <th class="bg-light">Descriptions</th>
            <th class="bg-light">Price</th>
            <th class="bg-light">QTY</th>
            <th class="bg-light" align="right">Amount</th>
        </tr>
        <tr>
            <td>{{ $quote->category . ' ' . $quote->sub_category . ' ' . $quote->product . ' ' . $quote->product_description }}
            </td>
            <td>{{ 'Rp ' . number_format($quote->product_price_per_unit, 2) }} per {{ $quote->unit_name }}</td>
            <td>{{ $quote->product_quantity }} {{ $quote->unit_name }}</td>
            <td align="right">{{ 'Rp ' . number_format($quote->product_amount, 2) }}</td>
        </tr>

        @foreach ($quotes_charges_with_amounts as $charges)
            <tr>

                @if ($charges->type == 0)
                    <td colspan="3">@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif {{ $charges->charge_name . ' ' . $charges->charge_value }} %</td>
                @else
                    <td colspan="3">@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif{{ $charges->charge_name }}</td>
                @endif

                <td align="right">
                    {{ $charges->addition_substraction == 0 ? '- ' : '+ ' }}{{ 'Rp ' . number_format($charges->charge_amount, 2) }}
                </td>

            </tr>
        @endforeach



        <tr>
            <td colspan="3">Tax {{ $quote->tax }} %</td>
            <td align="right">+ {{ 'Rp ' . number_format($quote->tax_value, 2)}}</td>
        </tr>
        <tr>
            <td colspan="3"><b>Total</b></td>
            <td align="right"><b>{{ 'Rp ' . number_format($quote->final_amount, 2)}}</b></td>
        </tr>
        <tr>
            <td colspan="4">Deliver order in {{ $quote->min_delivery_days }} to {{ $quote->max_delivery_days }}
                Days*</td>
        </tr>
        <tr class="">
            <td colspan=" 3"><b>Note: {{ $quote->note }}</b></td>
        </tr>
        <tr class="">
            <td colspan=" 4"><b>Comment: </b>{{ $quote->comment }}</td>
        </tr>
        <tr class="">
            <td colspan=" 4">
            <b>Certificate: </b>
            @if ($quote->certificate)
                <a class="d-inline" href="{{ asset('storage/' . $quote->certificate) }}" target="_blank"
                    id="catalogFileDownload"
                    download="{{ Str::substr($quote->certificate, stripos($quote->certificate, 'certificate_') + 12) }}">
                    {{ Str::substr($quote->certificate, stripos($quote->certificate, 'certificate_') + 12) }}
                </a>
            @endif
            </td>
        </tr>
    </table>
