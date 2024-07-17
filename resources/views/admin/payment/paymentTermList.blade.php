@extends('admin/adminLayout')
@section('content')
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">
            <div class="d-flex align-items-center">
            <h4 class="card-title">{{__('admin.payment_terms')}}</h4>
            <div class="d-flex align-items-center justify-content-end ms-auto">
                <div class="pe-1 mb-3 clearfix">
                    @can('delete payment terms')
                    <a href="{{ route('payment-term-add') }}" type="button" class="btn btn-primary btn-sm">{{__('admin.add')}}</a>
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
                                    <th class="hidden">{{__('admin.id')}}Id</th>
                                    <th>{{__('admin.group_name')}}</th>
                                    <th>{{__('admin.name')}}</th>
                                    <th>{{__('admin.description')}}</th>
                                    <th>{{__('admin.status')}}</th>
                                    @canany(['edit payment terms', 'delete payment terms'])
                                    <th class="text-end" data-orderable="false">{{__('admin.action')}}</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($payment_terms) > 0)
                                    @foreach ($payment_terms as $p_term)
                                        <tr>
                                            <td class="hidden">{{ $p_term->id }}</td>
                                            <td>{{ $p_term->group_name }}</td>
                                            <td>{{ $p_term->name }}</td>
                                            <td>{!! $p_term->description !!}</td>
                                            <td>{{ $p_term->status == 1 ? 'Active' : 'Inactive' }}</td>
                                            @canany(['edit payment terms', 'delete payment terms'])
                                            <td class="text-end text-nowrap">
                                                @can('edit payment terms')
                                                <a href="{{ route('payment-term-edit', ['id' => Crypt::encrypt($p_term->id)]) }}"
                                                    class="show-icon" data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}"><i class="fa fa-edit" aria-hidden="true"></i>
                                                </a>
                                                @endcan
                                                @can('delete payment terms')
                                                <a href="javascript:void(0)" id="deletePaymentTerms_{{ $p_term->id }}"
                                                    class="deletePaymentTerms show-icon ps-2" data-toggle="tooltip" ata-placement="top" title="{{__('admin.delete')}}"><i class="fa fa-trash"
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
            $(document).on('click', '.deletePaymentTerms', function() {
                var id = $(this).attr('id').split("_")[1];
                swal({
                        title: "{{__('admin.payment_term_delete_alert')}}",
                        text: "{{__('admin.payment_term_delete_alert_text')}}",
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
                                url: '{{ route('payment-term-delete') }}',
                                type: 'POST',
                                data: senddata,
                                success: function(successData) {
                                    new PNotify({
                                        text: '{{__('admin.payment_term_delete_alert_ok_text')}}',
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
