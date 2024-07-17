@if (count($quotes) > 0)
    @foreach ($quotes as $quote)
        <tr>
            <td class="hidden">{{ $quote->id }}</td>
            <td><a href="javascript:void(0);" style="text-decoration: none; color: #000" class="vieQuoteDetail hover_underline" data-bs-toggle="modal" data-bs-target="#viewRfqModalnew" data-id="{{ $quote->id }}">{{ $quote->quote_number }}</a></td>
            <td><a href="javascript:void(0);" style="text-decoration: none; color: #000" class="viewRfqDetail hover_underline" data-bs-toggle="modal" data-bs-target="#viewRfqModal" data-id="{{ $quote->rfq_id }}">{{ $quote->rfq_number }}</a></td>
            {{--<td>
                @if($quote->is_require_credit)
					<!-- <span class="badge badge-pill badge-danger">{{ __('admin.credit').' - '.$quote->approved_days }}</span> -->
                    <span class="badge badge-pill badge-danger">{{ __('admin.credit') }}</span>
				@else
					<span class="badge badge-pill badge-success">{{ __('admin.advanced') }}</span>
				@endif
            </td>--}}
            <td>{{ $quote->firstname . ' ' . $quote->lastname }}</td>
            <td>{{ $quote->category }}</td>{{--. ' / ' . $quote->sub_category --}}
            <td>{{ $quote->product }}</td>
            {{--<td>{{ $quote->product_description }}</td>--}}
            <td>{{ date('d-m-Y H:i:s', strtotime($quote->created_at)) }}</td>
            <td class="text-nowrap">{{ date('d-m-Y', strtotime($quote->valid_till)) }}</td>
            <td>
                @isset($quote->status_id)
                    @if(auth()->user()->role_id == 1 && $quote->status_id == 5)
                        {{__('rfqs.partial_quotation_received')}}
                    @else
                        {{__('rfqs.'.$quote->quoteStatus->backofflice_name)}}
                    @endif
                @else
                {{"-"}}
                @endisset
            </td>
            @canany(['publish quotes', 'edit quotes'])
            <td class="text-end text-nowrap">
                @if((($quote->status_id == 1 || $quote->status_id == 3 || $quote->status_id == 5) && (auth()->user()->role_id == 1 || Auth::user()->hasRole('agent') || Auth::user()->hasRole('jne'))) || ($quote->status_id == 5 && auth()->user()->role_id == 3 ))
                @can('edit quotes')
                <a href="{{ route('quotes-edit', ['id' => Crypt::encrypt($quote->id)]) }}"
                    class="ms-2 show-icon"  data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}"><i class="fa fa-edit" aria-hidden="true"></i>
                </a>
                @endcan
                @elseif($quote->status_id == 2 && auth()->user()->role_id == 1 && $quote->group_id == null)
                    @php
                        $order = $quote->order()->first(['payment_status']);
                    @endphp
                    @if(!empty($order) && $order->payment_status==0)
                        @can('edit qutoes')
                        <a href="{{ route('quotes-edit', ['id' => Crypt::encrypt($quote->id)]) }}"
                           class="ms-2 show-icon"  data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}"><i class="fa fa-edit" aria-hidden="true"></i>
                        </a>
                        @endcan
                    @endif
                @endif
                @can('publish quotes')
                <a class="ms-2 cursor-pointer vieQuoteDetail" data-id="{{ $quote->id }}" data-toggle="tooltip" ata-placement="top" title="{{__('admin.view')}}"><i class="fa fa-eye"></i></a>
                @endcan
                @if(!Auth::user()->hasRole('jne') && !Auth::user()->hasRole('agent'))
                    @if(!in_array($quote->status_id, [3,4,5]) && Request::segment(2) == 'quotes')
                        <a class=" cursor-pointer" onclick="chat.adminShowRfqChat('{{$quote->id}}', 'Quote', '{{ $quote->quote_number }}')" style="color: cornflowerblue;" data-id="" data-toggle="tooltip" data-placement="top" title="Chat"><i class="fa fa-comments"></i></a>
                    @endif
                @endif
                @if(Auth::user()->role_id == ADMIN)
                    <a class=" cursor-pointer viewFeedback" style="color: cornflowerblue;" data-id="{{$quote->id}}" data-toggle="tooltip" data-placement="top" title="{{ __('admin.feedback') }}" data-bs-original-title="{{ __('admin.feedback') }}" aria-label=""><i class="fa fa-commenting ms-2 text-info"></i></a>
                @endif
            </td>
            @endcanany
        </tr>
    @endforeach
@endif
