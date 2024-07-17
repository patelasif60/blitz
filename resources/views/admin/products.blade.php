@extends('admin/adminLayout')
@section('content')
    <style type="text/css">
        div.dataTables_wrapper div.dataTables_processing{
            padding: 0.7em 0;
        }
        div.dataTables_wrapper div.dataTables_filter input{
            padding: 8px 5px;
        }
        .btnPadding{
            padding: 0.25rem 0.5rem;
        }
    </style>
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">
            <div class="d-flex align-items-center">
            <h4 class="card-title">{{__('admin.products')}}</h4>
            <div class="d-flex align-items-center justify-content-end ms-auto">
                @if($authRoleId == 3)
                    <div class="pe-1 mb-3 clearfix">
                        <a href="{{ route('add-supplier-product') }}" type="button" class="btn btn-primary btn-sm btnPadding">{{__('admin.add')}}</a>
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
                        <table class="table table-hover user_datatable" id="productTable">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th style="max-width:150px">{{__('admin.category')}}</th>
                                    <th style="max-width:200px">{{__('admin.sub_category')}}</th>
                                    <th style="max-width:200px">{{__('admin.product')}}</th>
                                    <th style="max-width:70px">{{__('admin.status')}}</th>
                                    @if($authRoleId != 3)
                                    <th style="max-width:120px">{{__('admin.added_by')}}</th>
                                    <th style="max-width:120px">{{__('admin.updated_by')}}</th>
                                    @endif
                                    @canany(['edit products', 'delete products'])
                                    <th style="max-width:70px">{{__('admin.action')}}</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  <script type="text/javascript">
    jQuery(document).ready(function(){
        ProductsListTab.init();
    });
    var ProductsListTab = function () {
        ProductsList = function () {
            var tableId = "productTable";
            var table = $('#'+tableId).DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                order:[0,"DESC"],
                scrollX:true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                ajax: {
                    url: "{{ route('products-list') }}",
                    data: function (d) {
                        if (d.order.length > 0){
                            d.column_order = d.columns[d.order[0].column].name;
                            d.order_type = d.order[0].dir;
                        }
                    }
                },
                language:{
                    processing: "<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i> Loading...",
                    infoFiltered: ""
                },
                drawCallback: function() {
                    $('#'+tableId+' [data-bs-toggle="tooltip"]').tooltip();
                },
                columns: [
                    {data: 'id', name: 'id',visible:false},
                    {data: 'category_name', name: 'category_name', width:"150px"},
                    {data: 'sub_category_name', name: 'sub_category_name', width:"200px"},
                    {data: 'name', name: 'name', width:"200px"},
                    {
                        data: 'status',
                        name: 'status',
                        searchable: false,
                        width:"70px",
                        render: function (data,type,row) {
                            return (data == "1") ? 'Active' : 'Inactive';
                        },
                    },
                    @if($authRoleId != 3)
                    {data: 'addedBy', name: 'addedBy',orderable: false, searchable: false, width: "120px"},
                    {data: 'updatedBy', name: 'updatedBy',orderable: false, searchable: false, width: "120px"},
                    @endif
                    @canany(['edit products', 'delete products'])
                    {data: 'action', name: 'action', orderable: false, searchable: false, width: "70px"},
                    @endcanany
                ]
            });
        },
        DeleteProduct = function () {
            $(document).on('click', '.deleteProduct', function() {
                var id = $(this).attr('id').split("_")[1];
                var deleteStatus = $(this).attr('data-delete-status');
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
                                        SnippetApp.toast.success("{{ __('admin.success') }}","{{ __('admin.products_delete_alert_ok_text') }}");
                                        $('#productTable').DataTable().ajax.reload(null,false);
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
        },
        DeleteSupplierProductDetails = function () {
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
                                    SnippetApp.toast.success("{{ __('admin.success') }}","{{ __('admin.supplier_product_delete_success_message') }}");
                                    $('#productTable').DataTable().ajax.reload(null,false);
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
        };
        return {
            init:function(){
                ProductsList();
                DeleteProduct();
                DeleteSupplierProductDetails();
            }
        }
    }(1);
</script>

@stop
