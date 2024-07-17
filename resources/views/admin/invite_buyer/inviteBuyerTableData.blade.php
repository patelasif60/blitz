@if (count($invitebuyer) > 0)
    @foreach ($invitebuyer as $invitation)
        <tr>
            <td hidden>{{ $invitation->id }}</td>
            <td>{{ $invitation->user_email }}</td>
            @if(auth()->user()->role_id == 1 || Auth::user()->hasRole('agent'))
                <td>{{ $invitation->user_name }}</td>
                <td>{{ ucfirst($invitation->user_type_name) }}</td>
            @endif
            <td>
                @if($invitation->status == 0){{ __('admin.pending') }}
                @elseif($invitation->status == 1) {{ __('admin.active') }}
                @else{{ __('admin.link_expired')}}
                @endif
            </td>
            <td>{{ date('d-m-Y',strtotime($invitation->date)) }}</td>
            @canany(['create invite buyer', 'edit invite buyer'])
            <td class="text-end text-nowrap">
                @if ($invitation->status != 1)
                    @can('create invite buyer')
                    <a href="javascript:void(0)" id="resendInviteBuyer_{{ $invitation->id }}" class="resendInviteBuyer ps-2 me-1" data-toggle="tooltip" ata-placement="top" title="{{__('admin.resend')}}"><i class="fa fa-send" aria-hidden="true"></i></a>
                    @endcan
                @endif
                @if ($invitation->status != 1)
                    @can('edit invite buyer')
                    <a href="{{ route('invite-buyer-edit', ['id' => Crypt::encrypt($invitation->id)]) }}" class="show-icon" data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                    @endcan
                @endif
            </td>
            @endcanany
        </tr>
    @endforeach
@endif
