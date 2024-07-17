@extends('admin/adminLayout')
@section('content')
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">
            <div class="d-flex align-items-center">
            <h4 class="card-title">{{__('admin.products')}}</h4>
            <div class="d-flex align-items-center justify-content-end ms-auto">
                @if(auth()->user()->role_id == 3)
                    <div class="pe-1 mb-3 clearfix">
                        <a href="{{ route('add-supplier-product') }}" type="button" class="btn btn-primary btn-sm">{{__('admin.add')}}</a>
                    </div>
                @else
                @can('create products')
                <div class="pe-1 mb-3 clearfix">
                    <a href="{{ route('product-add') }}" type="button" class="btn btn-primary btn-sm">{{__('admin.add')}}</a>
                </div>
                @endcan
                @endif
                <input type="hidden" value="{!! csrf_token() !!}" name="_token">
            </div>
            </div>
            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="order-listing" class="table table-hover">
                            <thead>
                                <tr>
                                    {{--<th>{{__('admin.id')}}</th>--}}
                                    <th>{{__('admin.category')}}</th>
                                    <th>{{__('admin.sub_category')}}</th>
                                    <th>{{__('admin.product')}}</th>
                                    <th>{{__('admin.status')}}</th>
                                    <th>{{__('admin.added_by')}}</th>
                                    <th>{{__('admin.updated_by')}}</th>
                                    @canany(['edit products', 'delete products'])
                                    <th data-orderable="false" class="text-end">{{__('admin.action')}}</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($products) > 0)
                                    @foreach ($products as $product)
                                        <tr>
                                            {{--<td>{{ $product->id }}</td>--}}
                                            <td>{{ $product->category_name }}</td>
                                            <td>{{ $product->sub_category_name }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->status == 1 ? 'Active' : 'Inactive' }}</td>
                                            <td>
                                                @if( !empty( $product->trackAddData ) )

                                                {{ $product->trackAddData->full_name }}
                                                @else {{"-"}}@endif
                                            </td>
                                            <td>
                                                @if( !empty( $product->trackUpdateData ) )

                                                {{ $product->trackUpdateData->full_name }}
                                                @else {{"-"}}@endif
                                            </td>
                                            <td class="text-end text-nowrap">
                                            @if(auth()->user()->role_id == 1 || Auth::user()->hasRole('agent'))
                                                @can('edit products')
                                                <a href="{{ route('product-edit', ['id' => Crypt::encrypt($product->id)]) }}"
                                                    class="show-icon"  data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @endcan
                                                @can('delete products')
                                                <a href="javascript:void(0)"
                                                    data-delete-status="{{ $product->canBeDeleted }}"
                                                    id="deleteProduct_{{ $product->id }}"
                                                    class="ps-2 deleteProduct show-icon" data-toggle="tooltip" ata-placement="top" title="{{__('admin.delete')}}">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                @endcan
                                            @else
                                                <a href="{{ route('edit-supplier-product', ['id' => Crypt::encrypt($product->supp_prod_id)]) }}"
                                                    class="show-icon"  data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <a href = "javascript:void(0)" data-id="{{$product->supp_prod_id}}" class="ps-2 deleteSupplierProductDetails" data-toggle="tooltip" ata-placement="top" title="{{__('admin.delete')}}">
                                                    <i class="fa fa-trash"></i>
                                                </a>

                                            @endif
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
            $('#categoriesTable').DataTable();
            $(document).on('click', '.deleteProduct', function() {
                var id = $(this).attr('id').split("_")[1];
                var deleteStatus = $(this).attr('data-delete-status');
                console.log(deleteStatus)
                if (deleteStatus != 0) {
                    swal({
                            title: "{{  __('admin.products_delete_alert') }}",
                            text: "{{  __('admin.products_delete_alert_text') }}",
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
                                    url: '{{ route('product-delete') }}',
                                    type: 'POST',
                                    data: senddata,
                                    success: function(successData) {
                                        $.toast({
                                            heading: 'Success',
                                            text: '{{  __('admin.products_delete_alert_ok_text') }}',
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
                } else {
                    swal({
                        title: "{{  __('admin.products_can_not_delete_alert') }}",
                        text: "{{  utf8_decode(__('admin.products_can_not_delete_alert_text')) }}",
                        icon: "/assets/images/info.png",
                        buttons: "{{ __('admin.ok') }}",
                        dangerMode: false,
                    });
                }

            });

            $(document).on('click', '.deleteSupplierProductDetails', function() {
                var id = $(this).attr("data-id");
                swal({
                    title: "{{ __('admin.delete_sure_alert') }}",
                    text: "{{ __('admin.department_delete_alert_text') }}",
                    icon: "/assets/images/bin.png",
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        var _token = $("input[name='_token']").val();
                        var senddata = {
                            id: id,
                            _token: _token
                        }
                        $.ajax({
                            url: "{{ route('supplier-product-delete-ajax') }}",
                            type: 'POST',
                            data: senddata,
                            success: function(successData) {
                                if(successData.success == true){
                                    $.toast({
                                        heading: '{{ __('admin.success') }}',
                                        text: '{{ __('admin.supplier_product_delete_success_message') }}',
                                        showHideTransition: 'slide',
                                        icon: 'success',
                                        loaderBg: '#f96868',
                                        position: 'top-right'
                                    })
                                    location.reload();
                                }else{
                                    swal({
                                        text: '{{ __('admin.supplier_product_not_delete_hard') }}',
                                        button: {
                                            text: "{{ __('admin.ok') }}",
                                            value: true,
                                            visible: true,
                                            className: "btn btn-primary"
                                        }
                                    })
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
