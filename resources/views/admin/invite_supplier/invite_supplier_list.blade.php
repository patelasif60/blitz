@extends('admin/adminLayout')
@section('content')
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0 ">
            <div class="d-flex align-items-center">
                <h4 class="card-title">{{ __('admin.supplier') }}</h4>
                <div class="d-flex align-items-center justify-content-end ms-auto">
                    <div class="pe-1 mb-3 clearfix">
                        @can('create invite supplier')
                        <a href="{{ route('invite-supplier-add') }}" type="button"
                           class="btn btn-primary btn-sm">{{ __('admin.invite_supplier') }}</a>
                        @endcan
                    </div>
                    <input type="hidden" value="{!! csrf_token() !!}" name="_token">
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive ">
                        <table id="invite-supplier-listing" class="table table-hover">
                            <thead>
                            <tr>
                                <th hidden>{{ __('admin.id')}}</th>
                                <th>{{ __('admin.email')}}</th>
                                @if(auth()->user()->role_id == 1 || Auth::user()->hasRole('agent'))
                                    <th>{{ __('admin.buyer') }}</th>
                                @endif
                                <th>{{ __('admin.status') }}</th>
                                <th>{{ __('admin.date') }}</th>
                                @canany(['create invite supplier', 'edit invite supplier'])
                                <th class="text-center">{{ __('admin.action')}}</th>
                                @endcanany
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($invitebuyer) > 0)
                                @foreach ($invitebuyer as $invitation)
                                    <tr>
                                        <td hidden>{{ $invitation->id }}</td>
                                        <td>{{ $invitation->user_email }}</td>
                                        @if(auth()->user()->role_id == 1 || Auth::user()->hasRole('agent'))
                                            <td>{{ $invitation->firstname .' '. $invitation->lastname}}</td>
                                        @endif
                                        <td>
                                            @if($invitation->status == 0){{ __('admin.pending') }}
                                            @elseif($invitation->status == 1) {{ __('admin.active') }}
                                            @else{{ __('admin.link_expired')}}
                                            @endif
                                        </td>
                                        <td>{{ date('d-m-Y',strtotime($invitation->date)) }}</td>
                                        @canany(['create invite supplier', 'edit invite supplier'])
                                        <td class="text-end text-nowrap">
                                            @if ($invitation->status != 1)
                                                @can('create invite supplier')
                                                <a href="javascript:void(0)" id="resendInviteBuyer_{{ $invitation->id }}" class="resendInviteBuyer ps-2 me-1" data-toggle="tooltip" ata-placement="top" title="{{__('admin.resend')}}"><i class="fa fa-send" aria-hidden="true"></i></a>
                                                @endcan
                                            @endif

                                            @if ($invitation->status != 1)
                                                @can('edit invite supplier')
                                                <a href="{{ route('invite-supplier-edit', ['id' => Crypt::encrypt($invitation->id)]) }}" class="show-icon" data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                @endcan
                                            @endif
                                        </td>
                                        @endcanany
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#invite-supplier-listing').DataTable({
                "order": [
                    [0, "desc"]
                ],
            });
            $(document).on('click', '.resendInviteBuyer', function () {
                var id = $(this).attr('id').split("_")[1];
                swal({
                    text: "{{ __('admin.are_you_sure_to_send_invitation') }}",
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.yes') }}'],
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            var _token = $("input[name='_token']").val();
                            var senddata = {
                                id: id,
                                _token: _token
                            }
                            $.ajax({
                                url: '{{ route('invite-buyer-resend') }}',
                                type: 'POST',
                                data: senddata,
                                success: function (successData) {
                                    new PNotify({
                                        text: '{{ __('admin.invitation_send_successfully') }}',
                                        type: 'success',
                                        styling: 'bootstrap3',
                                        animateSpeed: 'slow',
                                        delay: 3000,
                                    });
                                    location.reload();

                                },
                                error: function () {
                                    console.log('error');
                                }
                            });

                        }
                    });

            });

        });
    </script>
@stop
