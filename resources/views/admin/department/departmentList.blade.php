@extends('admin/adminLayout')
@section('content')
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">
            <div class="d-flex align-items-center">
            <h4 class="card-title">{{ __('admin.department')}}</h4>
            <div class="d-flex align-items-center justify-content-end ms-auto">
                <div class="pe-1 mb-3 clearfix">
                    @can('create department')
                    <a href="{{ route('department-add') }}" type="button" class="btn btn-primary btn-sm">{{ __('admin.add')}}</a>
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
                                    <th class="hidden">{{ __('admin.id')}}</th>
                                    <th>{{ __('admin.department')}}</th>
                                    <th>{{ __('admin.status')}}</th>
                                    <th>{{ __('admin.added_by')}}</th>
                                    <th>{{ __('admin.updated_by')}}</th>
                                    @canany(['edit department', 'delete department'])
                                    <th class="text-end" data-orderable="false">{{ __('admin.action')}}</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($department) > 0)
                                    @foreach ($department as $dept)
                                        <tr>
                                            <td class="hidden">{{ $dept->id }}</td>
                                            <td>{{ $dept->name }}</td>
                                            <td>{{ $dept->status == 1 ? 'Active' : 'Inactive' }}</td>
                                            <td>
                                                @if( !empty( $dept->trackAddData ) )
                                                    {{ $dept->trackAddData->full_name }}
                                                @else
                                                    {{"-"}}
                                                @endif
                                            </td>
                                            <td>
                                                @if( !empty( $dept->trackUpdateData ) )
                                                    {{ $dept->trackUpdateData->full_name }}
                                                @else
                                                    {{"-"}}
                                                @endif
                                            </td>
                                            @canany(['edit department', 'delete department'])
                                            <td class="text-end text-nowrap">
                                                @can('edit department')
                                                <a href="{{ route('department-edit', ['id' => Crypt::encrypt($dept->id)]) }}"
                                                    class="show-icon" data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}"><i class="fa fa-edit" aria-hidden="true"></i>
                                                </a>
                                                @endcan
                                                @can('delete department')
                                                <a href="javascript:void(0)" id="deleteDepartment_{{ $dept->id }}"
                                                    class="deleteDepartment show-icon ps-2" data-toggle="tooltip" ata-placement="top" title="{{__('admin.delete')}}"><i class="fa fa-trash"
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
            $('#datatable').DataTable();
            $(document).on('click', '.deleteDepartment', function() {
                var id = $(this).attr('id').split("_")[1];
                swal({
                        title: "{{__('admin.department_delete_alert')}}",
                        text: "{{__('admin.department_delete_alert_text')}}",
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
                                url: '{{ route('department-delete') }}',
                                type: 'POST',
                                data: senddata,
                                success: function(successData) {
                                    new PNotify({
                                        text: '{{__('admin.department_delete_alert_ok_text')}}',
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
