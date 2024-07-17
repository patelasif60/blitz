@if (count($orders) > 0)
	@foreach ($orders as $order)
		<tr>
			<td class="hidden">{{ $order->id }}</td>
			<td><a href="javascript:void(0);" style="text-decoration: none; color: #000" class="viewRfqDetail hover_underline" data-bs-toggle="modal" data-bs-target="#viewRfqModal" data-id="{{ $order->rfq_id }}">{{ $order->reference_number }}</a></td>
			<td><a href="javascript:void(0);" style="text-decoration: none; color: #000" class="vieQuoteDetail hover_underline" data-bs-toggle="modal" data-bs-target="#viewRfqModalnew" data-id="{{ $order->quote_id }}">{{ $order->quote_number }}</a></td>
			<td><a href="javascript:void(0);" style="text-decoration: none; color: #000" class="getSingleOrderDetail hover_underline" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-id="{{ $order->id }}">{{ $order->order_number }}</a></td>
			<td>
				@if($order->is_credit == ORDER_IS_CREDIT['CREDIT'])
                    <span class="badge badge-pill badge-danger">{{ __('admin.credit').' - '.$order->approved_days }}</span>
                @elseif($order->is_credit == ORDER_IS_CREDIT['LOAN_PROVIDER_CREDIT'])
                    <span class="badge badge-pill badge-danger">{{ __('admin.loan_provider_credit') }}</span>
				@else
					<span class="badge badge-pill badge-success">{{ __('admin.advanced') }}</span>
				@endif
			</td>
			<td>{{ $order->company_name }}</td>
			<td>{{ $order->supplier_company_name }}</td>
			<td>
				{{ $order->product }}
			</td>
			@if(auth()->user()->role_id == 3)
				<td style="white-space: nowrap;">{{ 'Rp ' . number_format($order->supplier_final_amount, 2) }}</td>
			@else
                @php
                    $billedAmount = $order->final_amount;
                    $bulkOrderDiscount = getBulkOrderDiscount($order->id);
                @endphp
                @if($bulkOrderDiscount>0)
                    @php
                        $billedAmount = $billedAmount-$bulkOrderDiscount;
                    @endphp
                @endif
				<td style="white-space: nowrap;">{{ 'Rp ' . number_format($billedAmount, 2) }}</td>
			@endif
			<td>{{ date('d-m-Y H:i', strtotime($order->created_at)) }}</td>
			<td>
				@if($order->order_status==5)
					<span class="fs-11 bg-pre text-success text-nowrap"><i class="fa fa-check-circle-o" aria-hidden="true"></i>
                        {{ __('order.'.trim($order->order_status_name)) }}
                    </span>
				@elseif($order->order_status==10)
					<span class="fs-11 bg-pre text-danger text-nowrap"><i class="fa fa-times-circle-o" aria-hidden="true"></i>
                            {{ __('order.'.trim($order->order_status_name)) }}
                    </span>
				@elseif($order->order_status==8)
					<span class="fs-11 bg-pre text-recieved text-nowrap"><i class="fa fa-cart-arrow-down text-recieved" aria-hidden="true"></i>
                        {{ $order->payment_due_date?sprintf(__('order.'.$order->order_status_name),changeDateFormat($order->payment_due_date,'d/m/Y')):sprintf(__('order.'.$order->order_status_name),'DD/MM/YYYY') }}
                    </span>
				@elseif($order->order_status==7)
					<span class="fs-11 bg-pre text-danger text-nowrap"><i class="fa fa-times-circle-o" aria-hidden="true"></i>
                            {{ __('order.'.trim($order->order_status_name)) }}
                    </span>
                @else
					<span class="fs-11 bg-pre text-recieved text-nowrap"><i class="fa fa-cart-arrow-down text-recieved" aria-hidden="true"></i>
                        {{ __('order.'.trim($order->order_status_name)) }}
                    </span>
				@endif
			</td>
            @canany(['edit order list', 'publish order list'])
            <td class="text-end text-nowrap" id="oderActionRow{{ $order->id }}">
                @can('edit order list')
				<a href="{{ route('order-edit', ['id' => Crypt::encrypt($order->id)]) }}"
					class="pe-2 show-icon" data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}"><i class="fa fa-edit" aria-hidden="true"></i>
				</a>
                @endcan
				{{-- viewStatusDetail --}}
                @can('publish order list')
				<a class="pe-2 cursor-pointer getSingleOrderDetail" data-id="{{ $order->id }}" data-toggle="tooltip" ata-placement="top" title="{{__('admin.view')}}"><i class="fa fa-eye"></i></a>
                @endcan
                @if(Auth::user()->role_id == ADMIN)
                    <a class=" cursor-pointer viewFeedback" style="color: cornflowerblue;" data-id="{{ $order->id }}" data-toggle="tooltip" data-placement="top" title="{{ __('admin.feedback') }}" data-bs-original-title="{{ __('admin.feedback') }}" aria-label=""><i class="fa fa-commenting ms-2 text-info"></i></a>
                @endif
            </td>
            @endcanany
		</tr>
	@endforeach
@endif
