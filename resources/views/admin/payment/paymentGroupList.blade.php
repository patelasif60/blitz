@extends('admin/adminLayout')
@section('content')
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">
            <div class="d-flex align-items-center">
            <h4 class="card-title">{{__('admin.payment_groups')}}</h4>
            <div class="d-flex align-items-center justify-content-end ms-auto">
                <div class="pe-1 mb-3 clearfix">
                    @can('create payment groups')
                    <a href="{{ route('payment-group-add') }}" type="button" class="btn btn-primary btn-sm">{{__('admin.add')}}</a>
                    @endcan
                </div>
                <input type="hidden" value="{!! csrf_token() !!}" name="_token">
            </div>
            </div>
            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="order-listing" class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="hidden">{{__('admin.id')}}</th>
                                    <th>{{__('admin.name')}}</th>
                                    <th>{{__('admin.description')}}</th>
                                    <th>{{__('admin.status')}}</th>
                                    @canany(['edit payment groups', 'delete payment groups'])
                                    <th class="text-end" data-orderable="false">{{__('admin.action')}}</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($payment_groups) > 0)
                                    @foreach ($payment_groups as $p_group)
                                        <tr>
                                            <td class="hidden">{{ $p_group->id }}</td>
                                            <td>{{ $p_group->name }}</td>
                                            <td>{!! $p_group->description !!}</td>
                                            <td>{{ $p_group->status == 1 ? 'Active' : 'Inactive' }}</td>
                                            @canany(['edit payment groups', 'delete payment groups'])
                                            <td class="text-end text-nowrap">
                                                @can('edit payment groups')
                                                <a href="{{ route('payment-group-edit', ['id' => Crypt::encrypt($p_group->id)]) }}"
                                                    class="show-icon" data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}"><i class="fa fa-edit" aria-hidden="true"></i>
                                                </a>
                                                @endcan
                                                @can('delete payment groups')
                                                <a href="javascript:void(0)" id="deletePaymentGroup_{{ $p_group->id }}"
                                                class="deletePaymentGroup show-icon ps-2" data-toggle="tooltip" ata-placement="top" title="{{__('admin.delete')}}"><i class="fa fa-trash"
                                                    aria-hidden="true"></i>
                                                </a>
                                                @endcan
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
        $(document).ready(function() {
            $(document).on('click', '.deletePaymentGroup', function() {
                var id = $(this).attr('id').split("_")[1];
                swal({
                        title: "{{  __('admin.payment_group_delete_alert') }}",
                        text: "{{  __('admin.payment_group_delete_alert_text') }}",
                        icon: "/assets/images/bin.png",
                        buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            // swal("Poof! Your imaginary file has been deleted!", {
                            //     icon: "success",
                            // });

                            var _token = $("input[name='_token']").val();
                            var senddata = {
                                id: id,
                                _token: _token
                            }
                            $.ajax({
                                url: '{{ route('payment-group-delete') }}',
                                type: 'POST',
                                data: senddata,
                                success: function(successData) {
                                    new PNotify({
                                        text: '{{__('admin.payment_group_delete_alert_ok_text')}}',
                                        type: 'success',
                                        styling: 'bootstrap3',
                                        animateSpeed: 'fast',
                                        delay: 1000
                                    });
                                    location.reload();

                                },
                                error: function() {
                                    console.log('error');
                                }
                            });

                        }
                    });

            });

        });
    </script>
@stop
