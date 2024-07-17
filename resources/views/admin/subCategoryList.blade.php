@extends('admin/adminLayout')
@section('content')
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">
            <div class="d-flex align-items-center">
            <h4 class="card-title">{{ __('admin.sub_categories')}}</h4>
            <div class="d-flex align-items-center justify-content-end ms-auto">
                <div class="pe-1 mb-3 clearfix">
                    <a href="{{ route('sub-category-add') }}" type="button" class="btn btn-primary btn-sm">{{ __('admin.add')}}</a>
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
                                    <th>{{ __('admin.category')}}</th>
                                    <th>{{ __('admin.sub_category')}}</th>
                                    <th>{{ __('admin.status')}}</th>
                                    <th>{{ __('admin.added_by')}}</th>
                                    <th>{{ __('admin.updated_by')}}</th>
                                    <th class="text-end" data-orderable="false">{{ __('admin.action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($subCategories) > 0)
                                    @foreach ($subCategories as $subCategory)
                                        <tr>
                                            <td class="hidden">{{ $subCategory->id }}</td>
                                            <td>{{ $subCategory->category_name }}</td>
                                            <td>{{ $subCategory->name }}</td>
											<td>{{ $subCategory->status == 1 ? 'Active' : 'Inactive' }}</td>
                                            <td>
                                                @if( !empty( $subCategory->trackAddData ) )

                                                {{ $subCategory->trackAddData->full_name }}
                                                @else {{"-"}}@endif
                                            </td>
                                            <td>
                                                @if( !empty( $subCategory->trackUpdateData ) )

                                                {{ $subCategory->trackUpdateData->full_name }}
                                                @else {{"-"}}@endif
                                            </td>
                                            <td class="text-end text-nowrap">
                                                <a href="{{ route('sub-category-edit', ['id' => Crypt::encrypt($subCategory->id)]) }}"
                                                class="show-icon" data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}"><i class="fa fa-edit" aria-hidden="true"></i>
                                                </a>

                                                <a href="javascript:void(0)" id="deleteCategory_{{ $subCategory->id }}"
                                                    class="deleteSubCategory show-icon ps-2" data-toggle="tooltip" ata-placement="top" title="{{__('admin.delete')}}"><i class="fa fa-trash"
                                                        aria-hidden="true"></i>
                                                </a>
                                            </td>
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
            $(document).on('click', '.deleteSubCategory', function() {
                var id = $(this).attr('id').split("_")[1];
                swal({
                        title: "{{  __('admin.sub_categories_delete_alert') }}",
                        text: "{{  __('admin.sub_categories_delete_alert_text') }}",
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
                                url: '{{ route('sub-category-delete') }}',
                                type: 'POST',
                                data: senddata,
                                success: function(successData) {
                                    if (successData.success) {
                                        resetToastPosition();
                                        $.toast({
                                            heading: "{{__('admin.success')}}",
                                            text: "{{__('admin.sub_categories_delete_alert_ok_text')}}",
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
                                            text: "{{  utf8_decode(__('admin.sub_category_already_exist_in_supplier')) }}",
                                            icon: "/assets/images/info.png",
                                            buttons: ['{{ __('admin.ok') }}'],
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
