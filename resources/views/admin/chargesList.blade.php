@extends('admin/adminLayout')
@section('content')
<div class="card h-100 bg-transparent newtable_v2">
            <div class="card-body p-0">
              <div class="d-flex align-items-center">
              <h4 class="card-title">{{ __('admin.charges')}}</h4>
                <div class="d-flex align-items-center justify-content-end ms-auto">
                  <div class="pe-1 mb-3 clearfix">
                      @can('create charges')
                      <a href="{{ route('charge-add') }}" type="button" class="btn btn-primary btn-sm">{{ __('admin.add')}}</a>
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
							<th>{{ __('admin.charges_type')}}</th>
							<th>{{ __('admin.charges')}}</th>
                            <th>{{ __('admin.value')}}</th>
                            <th>{{ __('admin.added_by')}}</th>
                            <th>{{ __('admin.updated_by')}}</th>
                            @canany(['edit charges' , 'delete charges' ])
                            <th class="text-end" data-orderable="false">{{ __('admin.action')}}</th>
                            @endcanany
                        </tr>
                      </thead>
                      <tbody>
					  @if (count($charges) > 0)
						@foreach ($charges as $charge)
						@php
							$ctype =  'Supplier Other Charges';
							if ($charge->charges_type == 1) {
								$ctype = 'Logistic';
							}elseif ($charge->charges_type == 2) {
								$ctype = 'Platform';
							}
						@endphp
							<tr>
								<td class="hidden">{{ $charge->id }}</td>
								<td>{{ $ctype }}</td>
								<td>{{ $charge->name }}</td>
                                @if ($charge->type == 0)
                                    <td>{{ $charge->charges_value . '%' }}</td>
                                @else
                                    <td>{{ 'Rp ' . number_format($charge->charges_value, 2) }}</td>
                                @endif
                                <td>
                                    @if( !empty( $charge->trackAddData ) )

                                    {{ $charge->trackAddData->full_name }}
                                    @else {{"-"}}@endif
                                </td>
                                <td>
                                    @if( !empty( $charge->trackUpdateData ) )

                                    {{ $charge->trackUpdateData->full_name }}
                                    @else {{"-"}}@endif
                                </td>
                                @canany(['edit charges' , 'delete charges' ])
                                <td class="text-end text-nowrap">
                                    @can('edit charges')
                                    <a href="{{ route('charge-edit', ['id' => Crypt::encrypt($charge->id)]) }}" class="show-icon" data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}"><i class="fa fa-edit" aria-hidden="true"></i>
									</a>
                                    @endcan
                                    @can('delete charges')
                                    <a href="javascript:void(0)" id="deleteCharge_{{ $charge->id }}"
                                    class="deleteCharge show-icon ps-2" data-toggle="tooltip" ata-placement="top" title="{{__('admin.delete')}}"><i class="fa fa-trash"
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
            $(document).on('click', '.deleteCharge', function() {
                var id = $(this).attr('id').split("_")[1];
                swal({
                    title: "{{  __('admin.charges_delete_alert') }}",
                    text: "{{  __('admin.charges_delete_alert_text') }}",
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
                                url: '{{ route('charge-delete') }}',
                                type: 'POST',
                                data: senddata,
                                success: function(successData) {
                                    $.toast({
                                        heading: 'Success',
                                        text: '{{  __('admin.charges_delete_alert_ok_text') }}',
                                        //showHideTransition: 'slide',
                                        icon: 'success',
                                        loaderBg: '#f96868',
                                        position: 'top-right'
                                    })
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
