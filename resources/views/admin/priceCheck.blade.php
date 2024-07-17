@extends('admin/adminLayout')
@section('content')
    <div class="card h-100 bg-transparent ">
        <div class="card-body p-0 ">
            <div class="d-flex align-items-center">
                <h4 class="card-title">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">{{__('admin.check_price')}}</font>
                    </font>
                </h4>
            </div>
            <div class="row clearfix">
                <div class="col-12">
                    <form class="" method="POST" id="checkprice" enctype="multipart/form-data" action="{{ route('send-price-data') }}" data-parsley-validate>
                            @csrf
                        <div class="card bg-white mb-2">
                            <div class="card-body p-3">
                                <div class="row error_res">
                                    <div class="col-md-3 mb-3">
                                        <label for="name" class="form-label">{{__('admin.service_provider')}}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="tpl" name="tpl" required value="JNE" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="name" class="form-label">{{__('admin.origin_shipment_pincode')}}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" maxlength="6" onkeypress="return isNumberKey(this, event);" id="from" name="from" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="name" class="form-label">{{__('admin.destination_shipment_pincode')}}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" maxlength="6" onkeypress="return isNumberKey(this, event);" id="thru" name="thru" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="name" class="form-label">{{__('admin.amount')}}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="goods_amount" name="goods_amount" onkeypress="return isNumberKey(this, event);" required >
                                    </div>
                                    <div class="d-none" id="edit_id" data-id=""></div>
                                    <div class="col-md-3 mb-3">
                                        <label for="name" class="form-label">{{__('admin.quantity')}}<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" step="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="name" class="form-label">{{__('admin.weight')}}<span class="text-danger">*</span>
                                            <small>({{__('admin.per_unit')}})</small></label>
                                        <div class="input-group">
                                            <input type="text" name="weight" min="1" id="weight" class="form-control border-end-0" data-parsley-errors-container="#weights-errors" onkeypress="return isNumberKey(this, event);" required>
                                            <span class="input-group-text bg-white border-start-0" id="totalweight1"> Kg</span>
                                        </div>
                                        <div id="weights-errors"></div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="name" class="form-label">{{__('admin.length')}} ({{ __('admin.cm') }})</label>
                                        <input type="text" min="1" onkeypress="return isNumberKey(this, event);" class="form-control" id="length" name="length">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="name" class="form-label">{{__('admin.height')}} ({{ __('admin.cm') }})</label>
                                        <input type="text" min="1" onkeypress="return isNumberKey(this, event);" class="form-control" id="height" name="height">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="name" class="form-label">{{__('admin.width')}} ({{ __('admin.cm') }})</label>
                                        <input type="text" min="1" onkeypress="return isNumberKey(this, event);" class="form-control" id="width" name="width">
                                    </div>
                                    <div class="col-md-3 mb-0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" checked style=" margin-left: 0;opacity: 1" name="insurance_flag_old" type="checkbox" id="insurance_flag_old" value="Y" disabled>
                                            <label class="form-check-label" style=" margin-left: 2.5rem;opacity: 1" for="insurance_flag">{{__('admin.insurance_require')}}</label>
                                            <input type="hidden" name="insurance_flag" id="insurance_flag" value="Y">
                                        </div>
                                    </div>
                                    <div class="col-md-9 d-flex align-items-center justify-content-end  mb-3">
                                        <a id="add_product_btn" onclick="AddProduct()" class="btn btn-sm btn-primary" role="button">
                                            <span id="add_edit_name_change">{{ __('admin.add') }}</span>
                                        </a>
                                        <a id="cancel_product_btn" onclick="CancelsProduct()" class="btn btn-sm btn-cancel ms-2" href="#" role="button">
                                            <span>{{ __('admin.cancel') }}</span>
                                        </a>
                                        <a id="reset_btn" class="btn btn-sm btn-primary ms-2" href="{{ route('check-price') }}" role="button">
                                            <span>{{ __('admin.reset') }}</span>
                                        </a>
                                    </div>

                                    <div class="col-md-12 newtable_v2">
                                        <div class="text-center text-danger" id="five_ptoduct_validation_msg"></div>
                                        <div class="table-responsive border"  style="overflow-y: auto;">
                                            <table class="table" id="product_table">
                                                <thead class="bg-light position-sticky" style="top: 0;" >
                                                    <tr>
                                                        <th>{{__('admin.quantity')}}</th>
                                                        <th>{{__('admin.weight')}}</th>
                                                        <th>{{__('admin.length')}}</th>
                                                        <th>{{__('admin.height')}}</th>
                                                        <th>{{__('admin.width')}}</th>
                                                        <th class="text-end">{{__('admin.action')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr id="no_data">
                                                        <td colspan="6" class="text-center">{{ __('admin.no_data_available') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white py-2 d-flex justify-content-end">
                                <button type="submit" class="btn btn-sm btn-primary">{{__('admin.check')}}</button>
                            </div>
                        </div>
                    </form>
                    <div style="display: grid; place-items: center">
                            <img src="{{ URL::asset('/assets/images/front/loader.gif') }}"  id="loader" style="display: none;height: 64px;width: 64px">
                        </div>
                    <div id="resultData">


                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        var max_product = '{{ $max_product??5 }}';
        var last_category = -1;
        var addedProduct = [];
        $(document).ready(function() {
            $('#check-price-listing').DataTable();
            localStorage.clear();
        });
        function AddProduct() {
            if ($('#checkprice').parsley().validate()) {
                var edit_id = $('#edit_id').attr('data-id');
                var product = JSON.parse(localStorage.getItem("product")) || [];
                if ($('#quantity').val() != '' && $('#weight').val() != '' && product.length != max_product) {
                    if (edit_id == '') {
                        localstoreProduct();
                    } else {
                        upadteProduct(edit_id);
                    }
                } else if (edit_id != '' && product.length == max_product) {
                    upadteProduct(edit_id);
                } else {
                    if ($('#quantity').val() == '' && $('#weight').val() == '') {
                        alert('{{ __("admin.alert_product_weight_qty") }}');
                    } else if ($('#weight').val() == '' || $('#quantity').val() == '') {
                        if ($('#quantity').val() == '') {
                            alert('{{ __("admin.alert_product_qty") }}');
                        } else if ($('#weight').val() == '') {
                            alert('{{ __("admin.alert_product_weight") }}');
                        }
                    } else if (product.length == max_product) {
                        alert('{{ sprintf(__('admin.multiple_product_add'),$max_product??5) }}')
                    }
                }
            }
        }

        function localstoreProduct() {
            var product = JSON.parse(localStorage.getItem("product")) || [];

            var last_id = 0;
            if (product.length == 0) {
                last_id = 1;
            } else {
                last_id = parseInt(product.slice(-1)[0]['id']) + 1;
            }
            product.push({
                id: last_id,
                weight: $('#weight').val(),
                quantity: $('#quantity').val(),
                length: $('#length').val(),
                width: $('#width').val(),
                height: $('#height').val(),
            });

            addedProduct = product;

            window.localStorage.setItem('product', JSON.stringify(product));
            $('#no_data').hide();
            //remove required validation
            removeOrAddValidation(false);
            var buttons = '<a href="javascript:void(0);" class="show-icon" onclick="editProduct(' + last_id + ')" data-toggle="tooltip" data-placement="top" title="{{ __('admin.edit') }}"><i class="fa fa-edit" aria-hidden="true"></i></a> <a href="javascript:void(0);"class="show-icon ps-2 deleteProduct" onclick="deleteProduct(' + last_id + ')" data-toggle="tooltip" data-placement="top" title="{{ __('admin.delete') }}"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            var edit_id = 'edit_' + last_id;
            $('#product_table > tbody:last-child').append(
                '<tr id="' + edit_id + '">'
                + '<td>' + $("#quantity").val() + '</td>'
                + '<td>' + $("#weight").val() + '</td>'
                + '<td>' + $("#length").val() + '</td>'
                + '<td>' + $('#height').val() + '</td>'
                + '<td>' + $('#width').val() + '</td>'
                + '<td class="text-end text-nowrap">' + buttons + '</td>'
                + '</tr>');

            resetData();
            if (product.length == max_product) {
                disableAllFields(true);
            }
        }

        function disableAllFields(val, text) {
            if (val) {
                $('#five_ptoduct_validation_msg').html('<small>{{ sprintf(__('admin.multiple_product_add'),$max_product??5) }} </small>');
            } else {
                if (text != 'edit') {
                    $('#five_ptoduct_validation_msg').html('');
                }
            }
            $("#quantity").attr("disabled", val);
            $("#weight").attr("disabled", val);
            $("#length").attr("disabled", val);
            $("#height").attr("disabled", val);
            $("#width").attr("disabled", val);
            $("#add_product_btn").attr("disabled", val);
            $("#cancel_product_btn").attr("disabled", val);

        }

        function upadteProduct(id) {
            var product = JSON.parse(localStorage.getItem("product")) || [];
            product[id]['quantity'] = $('#quantity').val();
            product[id]['weight'] = $('#weight').val();
            product[id]['length'] = $('#length').val();
            product[id]['height'] = $('#height').val();
            product[id]['width'] = $('#width').val();
            window.localStorage.setItem('product', JSON.stringify(product));
            var data_id = product[id]['id'];
            var dispaly_edit = 'edit_' + data_id;
            var buttons = '<a href="javascript:void(0);" class="show-icon" onclick="editProduct(' + data_id + ')" data-toggle="tooltip" data-placement="top" title="{{ __('admin.edit') }}"><i class="fa fa-edit" aria-hidden="true"></i></a> <a href="javascript:void(0);"class="show-icon ps-2 deleteProduct" onclick="deleteProduct(' + data_id + ')" data-toggle="tooltip" data-placement="top" title="{{ __('admin.delete') }}"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            $('#' + dispaly_edit).html('');
            $('#' + dispaly_edit).append('<td>' + $("#quantity").val() + '</td>'
                + '<td>' + $("#weight").val() + '</td>'
                + '<td>' + $("#length").val() + '</td>'
                + '<td>' + $('#height').val() + '</td>'
                + '<td>' + $('#width').val() + '</td>'
                + '<td class="text-end text-nowrap">' + buttons + '</td>')
            resetData();
            $('#resultData').html('');
            addedProduct = product; // added by ronak bhabhor
            // console.log('updatedProduct');
            // console.log(addedProduct);
            if (product.length == max_product) {
                disableAllFields(true);
            }
        }

        function CancelsProduct() {
            if ($('#quantity').val() != '' || $('#weight').val() != '' || $('#length').val() != '' || $('#height').val() != '' || $('#width').val() != '') {
                swal({
                    title: "{{ __('dashboard.are_you_sure') }}?",
                    text: "{{ __('admin.reset_data_cancel') }}",
                    icon: "/assets/images/bin.png",
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                    dangerMode: true,
                }).then((willChange) => {
                    if (willChange) {
                        resetData();
                        var product = JSON.parse(localStorage.getItem("product")) || [];
                        if (product.length == max_product) {
                            disableAllFields(true);
                        }
                    }
                });
            }
        }

        function removeOrAddValidation(value) {
            $("#quantity").attr("required", value);
            $("#weight").attr("required", value);
            /*$("#length").attr("required", value);
            $("#height").attr("required", value);
            $("#width").attr("required", value);*/
        }

        function resetData() {
            $('#edit_id').attr('data-id', '');
            $('#quantity').val('');
            $('#weight').val('');
            $('#length').val('');
            $('#height').val('');
            $('#width').val('');
            $('#product_table').find('.edit').removeClass('edit');
            $('#add_edit_name_change').text('{{ __("admin.add") }}')
        }

        function deleteProduct(id) {
            swal({
                title: "{{ __('dashboard.are_you_sure') }}?",
                text: "{{ __('dashboard.delete_warning') }}",
                icon: "/assets/images/bin.png",
                buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                dangerMode: true,
            }).then((willChange) => {
                if (willChange) {
                    var product = JSON.parse(localStorage.getItem("product")) || [];
                    $('#edit_' + id).remove();
                    for (let i = 0; i < product.length; i++) {
                        if (product[i]['id'] === id) {
                            product.splice(i, 1);
                        }
                    }
                    window.localStorage.setItem('product', JSON.stringify(product));
                    if (product.length == 0) {
                        resetData();
                        removeOrAddValidation(true);
                        if($('#product_table > tbody:last-child')){
                            $('#no_data').show();
                        }
                    }
                    if (product.length != max_product) {
                        disableAllFields(false);
                    }
                }
            });
        }

        function editProduct(id) {
            var product = JSON.parse(localStorage.getItem("product")) || [];
            if (product.length == max_product) {
                disableAllFields(false, 'edit');
            }
            for (let i = 0; i < product.length; i++) {
                if (product[i]['id'] === id) {
                    $('#edit_id').attr('data-id', i);
                    $('#edit_' + id).addClass('edit');
                    $('#quantity').val(product[i]['quantity']);
                    $('#weight').val(product[i]['weight']);
                    $('#length').val(product[i]['length'])
                    $('#height').val(product[i]['height'])
                    $('#width').val(product[i]['width']);
                    $('#add_edit_name_change').text('{{ __("admin.rfq_save") }}')
                } else {
                    //$('#add_edit_name_change').text('{{ __("admin.add") }}')
                    $('#edit_' + product[i]['id']).removeClass('edit');
                }

            }
        }
        $("#checkprice").on('submit', function (event) {
            event.preventDefault();
            var product = JSON.parse(localStorage.getItem("product")) || [];
            var formData = new FormData($("#checkprice")[0]);
            formData.append('product_details', JSON.stringify(product));
            if ($('#checkprice').parsley().validate() && product.length != 0) {
                $('#loader').show();
                $.ajax({
                    url: $(this).attr('action'),
                    data: formData,
                    type: $(this).attr('method'),
                    contentType: false,
                    processData: false,
                    success: function (successData) {
                        if(successData.success){
                            $('#resultData').html(successData.response);
                            $('#change_weight').html('0');
                            $('#total_estimated_weight').val(0);
                        } else {
                            $('#resultData').html('');
                            $('#resultData').html('');
                            $('#change_weight').html('0');
                            swal({
                                title: '{{ __('admin.verify_data') }}',
                                text: successData.response.message,
                                icon: "/assets/images/info.png",
                                buttons: '{{ __('admin.ok') }}',
                                dangerMode: false,
                            });
                        }
                        $('#loader').hide();
                    }
                });
            }
        });
        function isNumberKey(txt, evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode == 46) {
                //Check if the text already contains the . character
                if (txt.value.indexOf('.') === -1) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if (charCode > 31 &&
                    (charCode < 48 || charCode > 57))
                    return false;
            }
            return true;
        }

        //Change weight as per the quantity
        /*function changeWeight(){
            var val = $('#weight').val();
            var qty = $('#quantity').val();
            if(val != '') {
                var calculate_weight = 0;
                if(isNaN(parseFloat(val*qty))) {
                    calculate_weight = 0;
                } else {
                    calculate_weight = parseFloat(val*qty);
                }
                $('#total_estimated_weight').val(calculate_weight);
            } else {
                $('#total_estimated_weight').val(0);
            }
        }*/
    </script>
@stop
