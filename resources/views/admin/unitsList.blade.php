@extends('admin/adminLayout')
@section('content')
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">
            <div class="d-flex align-items-center">
            <h4 class="card-title">{{ __('admin.units')}}</h4>
            <div class="d-flex align-items-center justify-content-end ms-auto">
                <div class="pe-1 mb-3 clearfix">
                    @can('create units')
                    <a href="{{ route('unit-add') }}" type="button" class="btn btn-primary btn-sm">{{ __('admin.add')}}</a>
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
                                    <th>{{ __('admin.unit')}}</th>
                                    <th>{{ __('admin.status')}}</th>
                                    <th>{{ __('admin.added_by')}}</th>
                                    <th>{{ __('admin.updated_by')}}</th>
                                    @canany(['edit units' , 'delete units' ])
                                    <th class="text-end" data-orderable="false">{{ __('admin.action')}}</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($units) > 0)
                                    @foreach ($units as $unit)
                                        <tr>
                                            <td class="hidden">{{ $unit->id }}</td>
                                            <td>{{ $unit->name }}</td>
                                            <td>{{ $unit->status == 1 ? 'Active' : 'Inactive' }}</td>
                                            <td>
                                                @if( !empty( $unit->trackAddData ) )

                                                {{ $unit->trackAddData->full_name }}
                                                @else {{"-"}}@endif
                                            </td>
                                            <td>
                                                @if( !empty( $unit->trackUpdateData ) )

                                                {{ $unit->trackUpdateData->full_name }}
                                                @else {{"-"}}@endif
                                            </td>
                                            @canany(['edit units' , 'delete units' ])
                                            <td class="text-end text-nowrap">
                                                @can('edit units')
                                                <a href="{{ route('unit-edit', ['id' => Crypt::encrypt($unit->id)]) }}"
                                                    class="show-icon" data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}"><i class="fa fa-edit" aria-hidden="true"></i>
                                                </a>
                                                @endcan
                                                @can('delete units')
                                                <a href="javascript:void(0)" id="deleteUnit_{{ $unit->id }}"
                                                    class="deleteUnit show-icon ps-2" data-toggle="tooltip" ata-placement="top" title="{{__('admin.delete')}}"><i class="fa fa-trash"
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
            $(document).on('click', '.deleteUnit', function() {
                var id = $(this).attr('id').split("_")[1];
                swal({
                        title: "{{  __('admin.units_delete_alert') }}",
                        text: "{{  __('admin.units_delete_alert_text') }}",
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
                                url: '{{ route('unit-delete') }}',
                                type: 'POST',
                                data: senddata,
                                success: function(successData) {
                                    resetToastPosition();
                                    $.toast({
                                        heading: "{{__('admin.success')}}",
                                        text: "{{__('admin.units_delete_alert_ok_text')}}",
                                        showHideTransition: "slide",
                                        icon: "success",
                                        loaderBg: "#f96868",
                                        position: "top-right",
                                    });
                                    setTimeout(function() {
                                        location.reload();
                                    }, 3000);

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
