@extends('admin/adminLayout')
@section('content')
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">
            <div class="d-flex align-items-center">
            <h4 class="card-title">{{ __('admin.designation')}}</h4>
            <div class="d-flex align-items-center justify-content-end ms-auto">
                <div class="pe-1 mb-3 clearfix">
                    @can('create designation')
                    <a href="{{ route('designation-add') }}" type="button" class="btn btn-primary btn-sm">{{__('admin.add')}}</a>
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
                                    <th>{{ __('admin.designation')}}</th>
                                    <th>{{ __('admin.status')}}</th>
                                    <th>{{ __('admin.added_by')}}</th>
                                    <th>{{ __('admin.updated_by')}}</th>
                                    @canany(['edit designation', 'delete designation'])
                                    <th class="text-end" data-orderable="false">{{ __('admin.action')}}</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($designation) > 0)
                                    @foreach ($designation as $desig)
                                        <tr>
                                            <td class="hidden">{{ $desig->id }}</td>
                                            <td>{{ $desig->name }}</td>
                                            <td>{{ $desig->status == 1 ? 'Active' : 'Inactive' }}</td>
                                            <td>
                                                @if( !empty( $desig->trackAddData ) )
                                                    {{ $desig->trackAddData->full_name }}
                                                @else
                                                    {{"-"}}
                                                @endif
                                            </td>
                                            <td>
                                                @if( !empty( $desig->trackUpdateData ) )
                                                    {{ $desig->trackUpdateData->full_name }}
                                                @else
                                                    {{"-"}}
                                                @endif
                                            </td>
                                            @canany(['edit designation', 'delete designation'])
                                            <td class="text-end text-nowrap">
                                                @can('edit designation')
                                                <a href="{{ route('designation-edit', ['id' => Crypt::encrypt($desig->id)]) }}"
                                                    class="show-icon" data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}"><i class="fa fa-edit" aria-hidden="true"></i>
                                                </a>
                                                @endcan
                                                @can('delete designation')
                                                <a href="javascript:void(0)" id="deleteDesignation_{{ $desig->id }}"
                                                    class="deleteDesignation show-icon ps-2" data-toggle="tooltip" ata-placement="top" title="{{__('admin.delete')}}"><i class="fa fa-trash"
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
            $(document).on('click', '.deleteDesignation', function() {
                var id = $(this).attr('id').split("_")[1];
                swal({
                        title: "{{__('admin.designation_delete_alert')}}",
                        text: "{{__('admin.designation_delete_alert_text')}}",
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
                                url: '{{ route('designation-delete') }}',
                                type: 'POST',
                                data: senddata,
                                success: function(successData) {
                                    new PNotify({
                                        text: '{{__('admin.designation_delete_alert_ok_text')}}',
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
