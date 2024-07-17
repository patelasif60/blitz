@extends('admin/adminLayout')
@section('content')
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0 ">
            <div class="d-flex align-items-center">
                <h4 class="card-title">{{ __('admin.categories')}}</h4>
                <div class="d-flex align-items-center justify-content-end ms-auto">
                    <div class="pe-1 mb-3 clearfix">
                        @can('create category')
                        <a href="{{ route('category-add') }}" type="button"
                           class="btn btn-primary btn-sm">{{ __('admin.add')}}
                        </a>
                        @endcan
                    </div>
                    <input type="hidden" value="{!! csrf_token() !!}" name="_token">
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive ">
                        <table id="order-listing" class="table table-hover">
                            <thead>
                            <tr>
                                <th class="hidden">{{ __('admin.id')}}</th>
                                <th>{{ __('admin.category')}}</th>
                                <th>{{ __('admin.status')}}</th>
                                <th>{{ __('admin.added_by')}}</th>
                                <th>{{ __('admin.updated_by')}}</th>
                                @canany(['edit category' , 'delete category' ])
                                    <th class="text-end" data-orderable="false">{{ __('admin.action')}}</th>
                                @endcanany
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($categories) > 0)
                                @foreach ($categories as $category)
                                    <tr>
                                        <td class="hidden">{{ $category->id }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->status == 1 ? 'Active' : 'Inactive' }}</td>
                                        <td>
                                            @if( !empty( $category->trackAddData ) )

                                                {{ $category->trackAddData->full_name }}
                                            @else {{"-"}}@endif
                                        </td>
                                        <td>
                                            @if( !empty( $category->trackUpdateData ) )

                                                {{ $category->trackUpdateData->full_name }}
                                            @else {{"-"}}@endif
                                        </td>
                                        @canany(['edit category' , 'delete category' ])
                                        <td class="text-end text-nowrap">
                                            @can('edit category')
                                                <a href="{{ route('category-edit', ['id' => Crypt::encrypt($category->id)]) }}"
                                                class="show-icon" data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}"><i
                                                        class="fa fa-edit" aria-hidden="true"></i>
                                                </a>
                                            @endcan

                                            @can('delete category')
                                                <a href="javascript:void(0)" id="deleteCategory_{{ $category->id }}"
                                                class="deleteCategory show-icon ps-2" data-toggle="tooltip"
                                                ata-placement="top" title="{{__('admin.delete')}}"><i class="fa fa-trash"
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
        $(document).ready(function () {
            $(document).on('click', '.deleteCategory', function () {
                var id = $(this).attr('id').split("_")[1];
                swal({
                    title: "{{  __('admin.categories_delete_alert') }}",
                    text: "{{  __('admin.categories_delete_alert_text') }}",
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
                                url: '{{ route('category-delete') }}',
                                type: 'POST',
                                data: senddata,
                                success: function (successData) {
                                    if (successData.success) {
                                        resetToastPosition();
                                        $.toast({
                                            heading: "{{__('admin.success')}}",
                                            text: "{{__('admin.categories_delete_alert_ok_text')}}",
                                            showHideTransition: "slide",
                                            icon: "success",
                                            loaderBg: "#f96868",
                                            position: "top-right",
                                        });
                                        setTimeout(function() {
                                            location.reload();
                                        }, 3000);

                                    } else {
                                        swal({
                                            title: successData.message,
                                            icon: "/assets/images/info.png",
                                            button: {
                                            text: "{{ __('admin.ok') }}",
                                            value: true,
                                            visible: true,
                                            className: "btn btn-primary"
                                            },
                                            dangerMode: false,
                                        });
                                    }

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
