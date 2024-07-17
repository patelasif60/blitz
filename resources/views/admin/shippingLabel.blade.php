@extends('admin/adminLayout')
@section('content')
    <div class="card h-100 bg-transparent ">
        <div class="card-body p-0 ">
            <div class="d-flex align-items-center">
                <h4 class="card-title">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">{{__('admin.shipping_label')}}</font>
                    </font>
                </h4>
            </div>
            <div class="row clearfix">
                <div class="col-12">
                    <form class="" method="GET" id="shippingLabel" enctype="multipart/form-data" data-parsley-validate>
                            @csrf
                        <div class="card bg-white mb-2">
                            <div class="card-body p-3">
                                <div class="row error_res">
                                    <div class="col-md-4 mb-3">
                                        <label for="name" class="form-label">{{__('admin.airwaybill_number')}}<span class="text-danger">*</span></label>
                                        <select name="airwaybill" id="airwaybill" class="form-select">
                                            <option disabled selected value="">{{__('admin.select_airwaybill')}}</option>
                                            @foreach ($airwayBillNumbers as $awb)
                                                <option value="{{ $awb->airwaybill_number }}">{{ $awb->airwaybill_number }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <a id="shippingLabelPreview" target="_blank" class="d-none">{{ $awb->airwaybill_number }}</a>
                                </div>
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
        $(document).ready(function(){
            let selectTwoPlaceHolder = '{{__('admin.select_airwaybill')}}'
            console.log(selectTwoPlaceHolder);

            $('#airwaybill').select2({
                placeholder: selectTwoPlaceHolder
            });
        });

        $("#airwaybill").on('change', function (event) {
            event.preventDefault();
            var awbno = $(this).val();
            if(awbno){
                if ($('#shippingLabel').parsley().validate()) {
                    $('#loader').show();
                    $.ajax({
                        url:"{{ route('get-shipping-label', '') }}" + "/" + awbno,
                        type: 'GET',
                        success: function (response) {
                            if(response.status == true){
                                $('#shippingLabelPreview').attr('href',response.pdfUrl);
                                $('#shippingLabelPreview')[0].click();
                                /***** will need this commented code in future ****/
                                /*$.ajax({
                                     url:"{//{ route('delete-shipping-label', '') }}" + "/" + response.pdfFileName,
                                     type: 'GET',
                                     success: function (deleteResponse) {
                                         if(deleteResponse.success == true){
                                            console.log(response.message);
                                         }
                                     },
                                     error: function() {
                                         console.log('error');
                                     }
                                });*/

                            }else{
                                $.toast({
                                    heading: "{{__('admin.danger')}}",
                                    text: "{{__('admin.something_error_message')}}",
                                    showHideTransition: "slide",
                                    icon: "error",
                                    loaderBg: "#f96868",
                                    position: "top-right",
                                });
                            }
                            $('#airwaybill').val('');
                            $('#loader').hide();
                            $('#airwaybill').val(null).trigger('change');
                        },
                        error: function() {
                            console.log('error');
                        }
                    });
                }
            }else{
                return ''
            }
        });

    </script>
@stop
