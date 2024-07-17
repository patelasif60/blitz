@if (count($rfqs) > 0)
	@foreach ($rfqs as $rfq)
		@if(!empty($preferredSuppliersRfq))
			@if($preferredSuppliersRfq->count()>0)
				@if($preferredSuppliersRfq->contains($rfq->id)===false && $rfq->is_preferred_supplier==1)
					@continue
				@endif
			@elseif($rfq->is_preferred_supplier==1)
				@continue
			@endif
		@endif

		@php
			$userType = 'Guest';
			if (in_array($rfq->id, $rfq_user)) {
				$userType = 'User';
			}
		@endphp
		<tr>
			<td class="hidden">{{ $rfq->id }} </td>
			<td><a href="javascript:void(0);" style="text-decoration: none; color: #000" class="viewRfqDetail hover_underline" data-bs-toggle="modal" data-bs-target="#viewRfqModal" data-id="{{ $rfq->id }}">{{ $rfq->reference_number }}</a></td>
			@if(isset($rfq->group_id))
				<td><a href="javascript:void(0);" style="text-decoration: none; color: #000" class="viewGroupDetail hover_underline" data-id="{{ isset($rfq->group_id) ? $rfq->group_id : '' }}" data-toggle="tooltip" data-placement="top" title="{{ __('admin.view') }}">{{isset($rfq->group_id) ? 'BGRP-'.$rfq->group_id : ''}}</a></td>
			@endif
			<td>
				@if($rfq->is_require_credit)
					<span class="badge badge-pill badge-danger d-block">{{ __('admin.credit') }}</span>
				@else
					<span class="badge badge-pill badge-success d-block">{{ __('admin.advanced') }}</span>
				@endif
			</td>
			<td>{{ $userType }} </td>
			<td>{{ $rfq->firstname . ' ' . $rfq->lastname }}</td>
			<td>{{ $rfq->category }}</td>{{-- . ' / ' . $rfq->sub_category --}}
			<td>{{ $rfq->product }}</td>
			{{--td>{{ $rfq->product_description }}</td>--}}
			<td>{{ date('d-m-Y H:i:s', strtotime($rfq->created_at)) }}</td>
			<td>{{ __('admin.'.trim($rfq->status_name)) }}</td>
            @canany(['publish rfqs', 'edit rfqs'])
			<td class="text-end text-nowrap">@if($rfq->rfq_status_id == 1 && $rfq->rfq_status_id != 3 && auth()->user()->role_id != 3)
                @can('edit rfqs')
				<a href="{{ route('rfq-edit', ['id' => Crypt::encrypt($rfq->id)]) }}" class="pe-2 show-icon d-inline-block"  data-toggle="tooltip" ata-placement="top" title="{{ __('admin.edit') }}">
                    <i class="fa fa-edit" aria-hidden="true"></i>
				</a>
                @endcan
				@endif
                @if(!in_array($rfq->rfq_status_id, [2,4]) && Request::segment(2) == 'rfq')
                    <a class="pe-2 cursor-pointer d-inline-block" onclick="chat.adminShowRfqChat('{{$rfq->id}}', 'Rfq', '{{ $rfq->reference_number }}')" style="color: cornflowerblue;" data-id="" data-toggle="tooltip" data-placement="top" title="Chat"><i class="fa fa-comments"></i></a>
                @endif
                @can('publish rfqs')
                <a class="cursor-pointer viewRfqDetail d-inline-block" data-id="{{ $rfq->id }}" data-toggle="tooltip" data-placement="top" title="{{ __('admin.view') }}"><i class="fa fa-eye"></i></a>
                @endcan
                @if(Auth::user()->role_id == ADMIN)
                    <a class="cursor-pointer viewFeedback" style="color: cornflowerblue;" data-id="{{$rfq->id}}"  data-toggle="tooltip" data-placement="top" title="{{ __('admin.feedback') }}" data-bs-original-title="{{ __('admin.feedback') }}" aria-label=""><i class="fa fa-commenting ms-2 text-info"></i></a>
                @endif
            </td>
            @endcanany
		</tr>
	@endforeach
@endif
