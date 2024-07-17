
<script>
    let payment_detail_html = {!! json_encode($paymentDetailHtml) !!};
    let activity_detail_html = {!! json_encode($activityDetailHtml) !!};
    let order_status_dropdown_html = {!! json_encode($orderStatusDropDownHtml) !!};
    let orderBatchesHtml = {!! json_encode($orderBatchesHtml) !!};
    let manageDeliverySeparately = {{$manageDeliverySeparately }};
    function show(input) {
        var file = input.files[0];
        var size = Math.round((file.size / 1024))
        if(size > 3000){
            swal({
                icon: 'error',
                title: '',
                text: '{{__('admin.file_size_under_3mb')}}',
            })
        } else {
            var fileName = file.name;
            var allowed_extensions = new Array("jpg","png", "pdf");
            var file_extension = fileName.split('.').pop();
            for(var i = 0; i < allowed_extensions.length; i++)
            {
                if(allowed_extensions[i]==file_extension)
                {
                    valid = true;
                    $.ajax({
                        url: $("#editOrderform").attr('action'),
                        type: $("#editOrderform").attr('method'),
                        data: new FormData($("#editOrderform")[0]),
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data) {
                            if (data.success == true) {
                                activity_detail_html = data.activityDetailHtml;
                                $('#activity-detail-div').html(activity_detail_html);
                                $.toast({
                                    heading: "{{__('admin.success')}}",
                                    text: "{{__('admin.order_updated_success_alert')}}",
                                    showHideTransition: "slide",
                                    icon: "success",
                                    loaderBg: "#f96868",
                                    position: "top-right",
                                });
                            }else{
                                $('#activity-detail-div').html(activity_detail_html);
                            }
                        },
                    });
                    return;
                }
            }
            valid = false;
            swal({
                // title: "Rfq Update",
                text: "{{__('admin.upload_image_or_pdf')}}",
                icon: "/assets/images/info.png",
                //buttons: true,
                buttons: ["No", "Yes"],
                // dangerMode: true,
            })
        }
    }

    function downloadimg(id, fieldName, name){
        var data = {
            id: id,
            fieldName: fieldName
        }
        $.ajax({
            url: "{{ route('download-image-ajax') }}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: data,
            type: 'POST',
            xhrFields: {
                responseType: 'blob'
            },
            success: function (response) {
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = name;
                link.click();
                $("#editOrderform")[0].reset();
            },
        });
    }

    function showOrderLatter(input) {
        let that = $(input);
        let file = input.files[0];
        let size = Math.round((file.size / 1024))
        let formId = that.closest("form").attr('id');
        let batchId = $("#" + formId + " input[name='batch_id']").val()
        if(size > 3000){
            swal({
                icon: 'error',
                title: '',
                text: '{{__('admin.file_size_under_3mb')}}',
            })
        } else {
            let fileName = file.name;
            let allowed_extensions = new Array("jpg","png", "pdf");
            let file_extension = fileName.split('.').pop();
            for(let i = 0; i < allowed_extensions.length; i++)
            {
                if(allowed_extensions[i]==file_extension)
                {
                    $.ajax({
                        url: that.closest("form").attr('action'),
                        type: that.closest("form").attr('method'),
                        data: new FormData(that.closest("form")[0]),
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data) {
                            if (data.success == true) {
                                payment_detail_html = data.paymentDetailHtml;
                                $('#payment-detail-div').html(payment_detail_html);
                                orderBatchesHtml = data.orderBatchesHtml;
                                $('#batch-detail-div').html(orderBatchesHtml);
                                $.toast({
                                    heading: "{{__('admin.success')}}",
                                    text: "{{__('admin.order_letter_uploaded_successfully')}}",
                                    showHideTransition: "slide",
                                    icon: "success",
                                    loaderBg: "#f96868",
                                    position: "top-right",
                                });
                            }else{
                                $('#payment-detail-div').html(payment_detail_html);
                                $('#batch-detail-div').html(orderBatchesHtml);
                            }
                            $(".accordion-button").addClass('collapsed');
                            $(".accordion-collapse").removeClass('show');
                            $(".orderBatchCollapse_"+batchId).removeClass('collapsed');
                            $("#collapseOne_"+batchId).addClass('show');
                        },
                    });
                    return;
                }
            }
            swal({
                // title: "Rfq Update",
                text: "{{__('admin.upload_image_or_pdf')}}",
                icon: "/assets/images/info.png",
                //buttons: true,
                buttons: ["No", "Yes"],
                // dangerMode: true,
            })
        }
    }

    function downloadOrderLatter(id,name){
        let data = {
            id: id
        }
        let that = $(this);
        $.ajax({
            url: "{{ route('download-order-latter-ajax') }}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: data,
            type: 'POST',
            xhrFields: {
                responseType: 'blob'
            },
            success: function (response) {
                let blob = new Blob([response]);
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = name;
                link.click();
                that.closest("form")[0].reset();
            },
        });
    }

    function removeOrderLatter(id,is_all_remove=0) {
        let data = {
            id: id,
            is_all_remove:is_all_remove,
            _token: $('meta[name="csrf-token"]').attr("content"),
        };
        swal({
            title: "{{ __('admin.delete_sure_alert') }}",
            text: "{{ sprintf(__('admin.do_you_want_to'),strtolower(__('admin.remove_order_letter'))) }}?",
            icon: "/assets/images/bin.png",
            buttons: ["Cancel", "Ok"],
            dangerMode: false,
        }).then((changeit) => {
            if (changeit) {
                $.ajax({
                    url: "{{ route('delete-order-latter-ajax') }}",
                    data: data,
                    type: "POST",
                    success: function (successData) {
                        if (successData.success == true) {
                            payment_detail_html = successData.paymentDetailHtml;
                            orderBatchesHtml = successData.orderBatchesHtml;
                            $('#payment-detail-div').html(payment_detail_html);
                            $('#batch-detail-div').html(orderBatchesHtml);
                        }else{
                            $('#payment-detail-div').html(payment_detail_html);
                            $('#batch-detail-div').html(orderBatchesHtml);
                        }

                    },
                    error: function () {
                        console.log("error");
                        $('#payment-detail-div').html(payment_detail_html);
                        $('#batch-detail-div').html(orderBatchesHtml);
                    },
                });
            }
        });
    }

    function downloadCertificate(id, fieldName, name) {
        event.preventDefault();
        var data = {
            id: id,
            fieldName: fieldName
        }
        $.ajax({
            url: "{{ route('quote-download-certificate-ajax') }}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: data,
            type: 'POST',
            xhrFields: {
                responseType: 'blob'
            },
            success: function (response) {
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = name;
                link.click();
            },
        });
    }

    function orderItemModal(order_item_id){
        $.ajax({
            url: "{{ route('order-item-status-ajax') }}",
            type: 'POST',
            dataType: 'JSON',
            data: {order_id:{{$order->id}},order_item_id:order_item_id,_token: $('meta[name="csrf-token"]').attr('content')},
            success: function (successData) {
                if (successData.html) {
                    $('#orderItemModalContent').html(successData.html);
                    $('#orderItemModal').modal('show');
                }
            },
            error: function () {
                console.log('error');
            }
        });
    }
    /** Manage order delivery separately
    *   Vrutika Rana - 25/08/2022
    */

    function manageOrderDeliverySeparately() {
        swal({
            title: "{{ __('admin.delete_sure_alert') }}",
            text: "{{ sprintf(__('admin.do_you_want_to'),strtolower(__('admin.manage_order_delivery_separately'))) }}?",
            icon: "/assets/images/info.png",
            buttons: ["Cancel", "Ok"],
            dangerMode: false,
        }).then((changeit) => {
            if (changeit) {
                $.ajax({
                    url: "{{ route('manage-order-delivery-separately-ajax','') }}/{{$order->id}}",
                    type: 'GET',
                    dataType: 'JSON',
                    success: function (successData) {
                        console.log(successData);
                        if (successData.success == true) {
                            payment_detail_html = successData.paymentDetailHtml;
                            orderBatchesHtml = successData.orderBatchesHtml;
                            $('#payment-detail-div').html(payment_detail_html);
                            $('#batch-detail-div').html(orderBatchesHtml);
                            manageDeliverySeparately = 1;
                        } else {
                            $('#payment-detail-div').html(payment_detail_html);
                            $('#batch-detail-div').html(orderBatchesHtml);
                            swal({
                                title: successData.message,
                                icon: "/assets/images/info.png",
                                confirmButtonText: "{{__('admin.ok')}}",
                                dangerMode: false,
                            });
                        }
                    },
                    error: function () {
                        console.log('error');
                        $('#payment-detail-div').html(payment_detail_html);
                        $('#batch-detail-div').html(orderBatchesHtml);
                        swal({
                            title: '{{ __('admin.something_error_message') }}',
                            icon: "/assets/images/info.png",
                            confirmButtonText: "{{__('admin.ok')}}",
                            dangerMode: false,
                        });
                    }
                });
            }
        });
    }


    /** Manage batch items separately
    *   Vrutika Rana - 25/08/2022
    */
    function manageBatchItemsSeparately(selectedBatch) {
        let orderId = {{$order->id}}; console.log($(this));
        let batchId = selectedBatch.data("batch-id");
        swal({
            title: "{{ __('admin.delete_sure_alert') }}",
            text: "{{ sprintf(__('admin.do_you_want_to'),strtolower(__('admin.manage_order_items_separately'))) }}?",
            icon: "/assets/images/info.png",
            buttons: ["Cancel", "Ok"],
            dangerMode: false,
        }).then((changeit) => {
            if (changeit) {
                $.ajax({
                    url: "{{ route('manage-batch-items-separately-ajax') }}",
                    type: 'GET',
                    dataType: 'JSON',
                    data:{orderId:orderId,batchId:batchId},
                    success: function (successData) {
                        console.log(successData);
                        if (successData.success == true) {
                            payment_detail_html = successData.paymentDetailHtml;
                            orderBatchesHtml = successData.orderBatchesHtml;
                            $('#payment-detail-div').html(payment_detail_html);
                            $('#batch-detail-div').html(orderBatchesHtml);
                        } else {
                            $('#payment-detail-div').html(payment_detail_html);
                            $('#batch-detail-div').html(orderBatchesHtml);
                            swal({
                                title: successData.message,
                                icon: "/assets/images/info.png",
                                confirmButtonText: "{{__('admin.ok')}}",
                                dangerMode: false,
                            });
                        }
                    },
                    error: function () {
                        console.log('error');
                        $('#payment-detail-div').html(payment_detail_html);
                        $('#batch-detail-div').html(orderBatchesHtml);
                        swal({
                            title: '{{ __('admin.something_error_message') }}',
                            icon: "/assets/images/info.png",
                            confirmButtonText: "{{__('admin.ok')}}",
                            dangerMode: false,
                        });
                    }
                });
            }
        });
    }
    $(document).on('click','#selectAllItems',function() {
        if ($('input:checkbox[name=selectAllItems]').val() == 0){
            $('input:checkbox[name=selectAllItems]').val('1');
            $("input:checkbox[name=selectItems]").prop("checked", true);
        } else {
            $('input:checkbox[name=selectAllItems]').val('0');
            $("input:checkbox[name=selectItems]").prop('checked', false);
        }
    });
    $(document).on('click','input:checkbox[name=selectItems]',function() {
        if ($('input:checkbox[name=selectItems]').filter(':checked').length == $('input:checkbox[name=selectItems]').length){
            $('input:checkbox[name=selectAllItems]').val('1');
            $('#selectAllItems').prop('checked', true);
        }else{
            if($(this).prop("checked") == false){
            $('#selectAllItems').prop('checked', false);
            $('input:checkbox[name=selectAllItems]').val('0');
           }
        }
    });
    function manageOrderItemsSeparately() {
        swal({
            title: "{{ __('admin.delete_sure_alert') }}",
            text: "{{ sprintf(__('admin.do_you_want_to'),strtolower(__('admin.manage_order_items_separately'))) }}?",
            icon: "/assets/images/info.png",
            buttons: ["Cancel", "Ok"],
            dangerMode: false,
        }).then((changeit) => {
            if (changeit) {
                $.ajax({
                    url: "{{ route('manage-order-items-separately-ajax','') }}/{{$order->id}}",
                    type: 'GET',
                    dataType: 'JSON',
                    success: function (successData) {
                        console.log(successData);
                        if (successData.success == true) {
                            payment_detail_html = successData.paymentDetailHtml;
                            orderBatchesHtml = successData.orderBatchesHtml;
                            $('#payment-detail-div').html(payment_detail_html);
                            $('#batch-detail-div').html(orderBatchesHtml);
                        } else {
                            $('#payment-detail-div').html(payment_detail_html);
                            $('#batch-detail-div').html(orderBatchesHtml);
                            swal({
                                title: successData.message,
                                icon: "/assets/images/info.png",
                                confirmButtonText: "{{__('admin.ok')}}",
                                dangerMode: false,
                            });
                        }


                    },
                    error: function () {
                        console.log('error');
                        $('#payment-detail-div').html(payment_detail_html);
                        $('#batch-detail-div').html(orderBatchesHtml);
                        swal({
                            title: '{{ __('admin.something_error_message') }}',
                            icon: "/assets/images/info.png",
                            confirmButtonText: "{{__('admin.ok')}}",
                            dangerMode: false,
                        });
                    }
                });
            }
        });
    }

    function orderStatusChange(selector,is_validate=0){
        let selectedStatus = selector.val();
        let orderId = {{$order->id}};

        let data = {
            selectedStatusID: selectedStatus,
            orderId: orderId,
            is_validate:is_validate,
            is_backend_request:1,
            _token: $('meta[name="csrf-token"]').attr('content')
        }
        $.ajax({
            url: "{{ route('order-status-change-ajax') }}",
            data: data,
            type: 'POST',
            dataType: 'JSON',
            success: function (successData) {
                console.log(successData);
                if (successData.success==true){
                    if (successData.valid !== undefined && successData.valid==1) {
                        swal({
                            title: "{{ __('admin.delete_sure_alert') }}",
                            text: "{{ __('admin.order_status_change_text') }}",
                            icon: "/assets/images/info.png",
                            buttons: ["No", "Yes"],
                            dangerMode: false,
                        }).then((isConfirm) => {
                            if (isConfirm) {
                                orderStatusChange(selector,successData.valid);
                            }else{
                                $('#order-status-div').html(order_status_dropdown_html);
                            }
                        });
                    }else {
                        order_status_dropdown_html = successData.orderStatusDropDownHtml;
                        $('#order-status-div').html(order_status_dropdown_html);
                        $('#orderStatusDetails').html(successData.orderStatusHtml);
                        if (successData.paymentDetailHtml) {
                            payment_detail_html = successData.paymentDetailHtml;
                            $('#payment-detail-div').html(payment_detail_html);
                        }
                        if(selectedStatus == 7){
                            $('#image-tax_receipt').attr('disabled', true);
                            $('#image-invoice').attr('disabled', true);
                        }
                        new PNotify({
                            text: 'Order status changed successfully',
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });
                    }
                    if(successData.poHtml != ''){
                            //$('#activity-detail-div').html('');
                            $('#activity-detail-div').html(successData.poHtml);
                    }
                }else{
                    swal(successData.swal).then((isConfirm) => {
                        if (isConfirm) {
                            if (successData.trigger) {
                                if (successData.trigger=='generatePO') {
                                    $(document).find('a.generatePo').trigger('click');
                                }
                            }
                        }
                    });
                    $('#order-status-div').html(order_status_dropdown_html);
                }
            },
            error: function () {
                console.log('error');
                $('#order-status-div').html(order_status_dropdown_html);
                swal({
                    title: '{{ __('admin.something_error_message') }}',
                    icon: "/assets/images/info.png",
                    confirmButtonText : "{{__('admin.ok')}}",
                    dangerMode: false,
                });
            }
        });
    }

    function creditOrderStatusChange(selector,is_validate=0){
        let selectedStatus = selector.val();
        let orderId = {{$order->id}};

        let data = {
            selectedStatusID: selectedStatus,
            orderId: orderId,
            is_validate:is_validate,
            is_backend_request:1,
            _token: $('meta[name="csrf-token"]').attr('content')
        }
        $.ajax({
            url: "{{ route('credit-order-status-change-ajax') }}",
            data: data,
            type: 'POST',
            dataType: 'JSON',
            success: function (successData) {
                if (successData.success==true){
                    if (successData.valid !== undefined && successData.valid==1) {
                        swal({
                             title: "{{ __('admin.delete_sure_alert') }}",
                            text: "{{ __('admin.order_status_change_text') }}",
                            icon: "/assets/images/info.png",
                            buttons: ["No", "Yes"],
                            dangerMode: false,
                        }).then((isConfirm) => {
                            if (isConfirm) {
                                creditOrderStatusChange(selector,successData.valid);
                            }else{
                                $('#order-status-div').html(order_status_dropdown_html);
                            }
                        });
                    }else {
                        $('#order-status-div').html(successData.orderStatusDropDownHtml);
                        order_status_dropdown_html = successData.orderStatusDropDownHtml;
                        $('#orderStatusDetails').html(successData.orderStatusHtml);
                        if (successData.paymentDetailHtml) {
                            payment_detail_html = successData.paymentDetailHtml;
                            $('#payment-detail-div').html(payment_detail_html);
                        }
                        if(selectedStatus == 7){
                            $('#image-tax_receipt').attr('disabled', true);
                            $('#image-invoice').attr('disabled', true);
                        }
                        new PNotify({
                            text: 'Order status changed successfully',
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });

                    }
                    if(successData.poHtml!=''){
                        //$('#activity-detail-div').html('');
                        $('#activity-detail-div').html(successData.poHtml);
                        }
                }else{
                    swal(successData.swal).then((isConfirm) => {
                        if (isConfirm) {
                            if (successData.trigger) {
                                if (successData.trigger=='generatePO') {
                                    $(document).find('a.generatePo').trigger('click');
                                }
                            }
                        }
                    });
                    $('#order-status-div').html(order_status_dropdown_html);
                }
            },
            error: function () {
                console.log('error');
                $('#order-status-div').html(order_status_dropdown_html);
                swal({
                    title: '{{ __('admin.something_error_message') }}',
                    icon: "/assets/images/info.png",
                    confirmButtonText : "{{__('admin.ok')}}",
                    dangerMode: false,
                });
            }
        });
    }

    /**
     * Vrutika rana 02-09-2022
     * creategroup: Create new batch for selected products
     */
    function createNewBatch(selector=[],orderItemId=0,is_validate=0){
        let orderItemIds = [];
        let cTitle ='';
        if(selector != ''){

            let selectedStatus = selector.val();
            sessionStorage.setItem('order_itm_status_selector',selector.attr('id'));
            sessionStorage.setItem('order_itm_status_selector_value',selectedStatus);
            sessionStorage.setItem('order_itm_status_itemId',orderItemId);
            sessionStorage.setItem('order_itm_status_is_validate',is_validate);
            if(selectedStatus==2) {
                if(orderItemId!=0) {
                    $('input:checkbox[name=selectAllItems]').val('0');
                    $('input:checkbox').prop('checked', false);
                    $('#selectItems'+orderItemId).prop('checked', true);
                     cTitle = "{{__('admin.selected_product_added_to_batch')}}";
                }else{
                    $('input:checkbox[name=selectAllItems]').val('1');
                    $('input:checkbox').prop('checked', true);
                     cTitle = "{{__('admin.all_products_added_to_batch')}}";
                }
                $("input:checkbox[name=selectItems]:checked").each(function() {
                    orderItemIds.push($(this).val());
                });
                $('#order_item_ids').val(orderItemIds);
                showProductDetails(cTitle);
            }else{
                orderItemStatusChange(selector,orderItemId);
            }
        }else{
            if ($('input:checkbox[name=selectItems]').filter(':checked').length < 1){
                swal({
                    title: "{{ __('profile.atleast_one_checkbox_checked') }}",
                    icon: "/assets/images/info.png",
                    confirmButtonText : "{{__('admin.ok')}}",
                    dangerMode: false,
                });
                $('#payment-detail-div').html(payment_detail_html);
                $('#batch-detail-div').html(orderBatchesHtml);
            }else{
                sessionStorage.setItem('order_itm_status_selector','');
                sessionStorage.setItem('order_itm_status_selector_value',2);
                sessionStorage.setItem('order_itm_status_itemId',orderItemId);
                sessionStorage.setItem('order_itm_status_is_validate',is_validate);
                $("input:checkbox[name=selectItems]:checked").each(function() {
                    orderItemIds.push($(this).val());
                });
                cTitle = "{{__('admin.verify_product_for_batch')}}";
                $('#order_item_ids').val(orderItemIds);
                showProductDetails(cTitle);
            }
        }

    }
    /**
     * Vrutika rana 05-09-2022
     * productDetails: show products for batch
     */
    function showProductDetails(cTitle='') {

        var selectedItemIds = $('#order_item_ids').val();
        var orderId = $("#order_id").val();
        let data = {
            orderId : orderId,
            selectedItemIds : selectedItemIds,
            _token: $('meta[name="csrf-token"]').attr('content')
        }
        $.ajax({
            url: "{{ route('order-items-details-ajax') }}",
            type: "POST",
            data: data,
            dataType: 'JSON',
            success: function (successData) {
                if (successData.success == true) {
                    $("#orderItemsTableBody").html(successData.orderItemsDetails);
                    $("#confirmBatchTitle").html(cTitle);
                    $('#confirmOrderProductsModal').modal('show');
                }
                //Click on cancle button can revert the status
                $(".cancelConfirmBatchBtn").click(function(){
                    /*if(manageDeliverySeparately ==0){
                        $('#payment-detail-div').html(payment_detail_html);
                    }*/
                    $('#payment-detail-div').html(payment_detail_html);
                    $('#batch-detail-div').html(orderBatchesHtml);
                    $('#pickupAddressDateTimeModal').parsley().reset();
                });
                //Click on confirm button, hide items details and show pickup data modal
                $(".confirmOrderBatchBtn").click(function(){
                    $('#confirmOrderProductsModal').modal('hide');
                    $("#pickupAddressDateTimeModal").modal('show');
                });
            }
        });
    }

    function orderItemStatusChange(selector,orderItemId=0,is_validate=0){
        let selectedStatus = '';
        let batchId= '';
        let selectedItemIds = $('#order_item_ids').val();

        if (typeof selector === 'string') {
            selectedStatus = selector;
            batchId = '';
        }else{
            selectedStatus = selector.val();
            batchId = selector.attr("data-batch-id");
        }
        let orderId = {{$order->id}};
        let data = {
            selectedStatusID: selectedStatus,
            orderId: orderId,
            batchId:batchId,
            orderItemId: orderItemId,
            selectedItemIds: selectedItemIds,
            is_validate:is_validate,
            is_backend_request:1,
            _token: $('meta[name="csrf-token"]').attr('content')
        }
        $.ajax({
            url: "{{ route('order-item-status-change-ajax') }}",
            data: data,
            type: 'POST',
            dataType: 'JSON',
            success: function (successData) {
                if (successData.success==true){
                    if (successData.valid !== undefined && successData.valid==1) {
                        if(!sessionStorage.getItem('is_confirm_pickup') || sessionStorage.getItem('is_confirm_pickup')==0){
                            swal({
                                title: "{{ __('admin.delete_sure_alert') }}",
                                text: "You want to change order item status.",
                                icon: "/assets/images/info.png",
                                buttons: ["No", "Yes"],
                                dangerMode: false,
                            }).then((isConfirm) => {
                                if (isConfirm) {
                                    orderItemStatusChange(selector, orderItemId, successData.valid);
                                     if(selectedStatus == 8 || selectedStatus == 7)
                                    {
                                        location.reload();
                                    }
                                } else {
                                    $('#payment-detail-div').html(payment_detail_html);
                                    $('#batch-detail-div').html(orderBatchesHtml);
                                    $(".accordion-button").addClass('collapsed');
                                    $(".accordion-collapse").removeClass('show');
                                    $(".orderBatchCollapse_"+batchId).removeClass('collapsed');
                                    $("#collapseOne_"+batchId).addClass('show');
                                }
                            });
                        }else{
                            orderItemStatusChange(selector, orderItemId, successData.valid);
                            sessionStorage.setItem('is_confirm_pickup',0);
                            sessionStorage.setItem('order_itm_status_itemId',0);
                            $(".accordion-button").addClass('collapsed');
                            $(".accordion-collapse").removeClass('show');
                            $(".orderBatchCollapse_"+batchId).removeClass('collapsed');
                            $("#collapseOne_"+batchId).addClass('show');
                        }
                    }else {
                        payment_detail_html = successData.paymentDetailHtml;
                        orderBatchesHtml = successData.orderBatchesHtml;
                        if(selectedStatus>=2){
                            $('#ordersBatch').removeClass('d-none');
                        }
                        $('#payment-detail-div').html(payment_detail_html);
                        $('#batch-detail-div').html(orderBatchesHtml);
                        $(".accordion-button").addClass('collapsed');
                        $(".accordion-collapse").removeClass('show');
                        $(".orderBatchCollapse_"+batchId).removeClass('collapsed');
                        $("#collapseOne_"+batchId).addClass('show');
                        if (successData.orderStatusDropDownHtml){
                            order_status_dropdown_html = successData.orderStatusDropDownHtml;
                            $('#order-status-div').html(order_status_dropdown_html);
                        }
                        if (successData.orderStatusHtml){
                            $('#orderStatusDetails').html(successData.orderStatusHtml);
                        }
                        new PNotify({
                            text: 'Order item status changed successfully',
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });
                    }
                }else{
                    if(successData.swal == 'lcdoc'){
                        $('#UploadModal').modal('show')
                        $('#orderdata').val(orderId);
                    }
                    else{
                        swal(successData.swal).then((isConfirm) => {
                        if (isConfirm) {
                            if (successData.trigger) {
                                if (successData.trigger=='generatePO') {
                                    $(document).find('a.generatePo').trigger('click');
                                }
                            }
                        }
                        });
                    }
                    $('#payment-detail-div').html(payment_detail_html);
                    $('#batch-detail-div').html(orderBatchesHtml);
                    $(".accordion-button").addClass('collapsed');
                    $(".accordion-collapse").removeClass('show');
                    $(".orderBatchCollapse_"+batchId).removeClass('collapsed');
                    $("#collapseOne_"+batchId).addClass('show');
                }
            },
            error: function () {
                console.log('error');
                $('#payment-detail-div').html(payment_detail_html);
                $('#batch-detail-div').html(orderBatchesHtml);
                $(".accordion-button").addClass('collapsed');
                $(".accordion-collapse").removeClass('show');
                $(".orderBatchCollapse_"+batchId).removeClass('collapsed');
                $("#collapseOne_"+batchId).addClass('show');
                swal({
                    title: '{{ __('admin.something_error_message') }}',
                    icon: "/assets/images/info.png",
                    confirmButtonText : "{{__('admin.ok')}}",
                    dangerMode: false,
                });
            }
        });
    }

    $(function() {
        $('#activity-tab').click( function() {
            var orderId = $(this).attr('data-orderid');
            $.ajax({
                url: "{{ route('admin-get-order-activity-ajax', '') }}" + "/" + orderId,
                type: 'GET',
                success: function(successData) {
                    if (successData.activityhtml) {
                        $('.activityopen').html(successData.activityhtml);
                    }

                },
                error: function() {
                    console.log('error');
                }
            });
        });
        $(document).on('click', '.removeFile', function (e) {
            e.preventDefault();
            var element = $(this);
            var id = $(this).attr("data-id");
            var fileName = $(this).attr("id");
            var dataName = $(this).attr("data-name")
            if(dataName == 'tax_receipt'){
                text = "{{ sprintf(__('admin.do_you_want_to'),strtolower(__('admin.remove_tax_receipt'))) }}?";
            }
            if(dataName == 'invoice'){
                text  ="{{ sprintf(__('admin.do_you_want_to'),strtolower(__('admin.remove_invoice'))) }}?";
            }
            var data = {
                fileName: fileName,
                filePath: $(this).attr("file-path"),
                id: $(this).attr("data-id"),
                _token: $('meta[name="csrf-token"]').attr("content"),
            };
            swal({
                title: "{{ __('admin.delete_sure_alert') }}",
                text: text,
                icon: "/assets/images/bin.png",
                buttons: ["Cancel", "Ok"],
                dangerMode: false,
            }).then((changeit) => {
                if (changeit) {
                    $.ajax({
                        url: "{{ route('order-file-delete-ajax') }}",
                        data: data,
                        type: "POST",
                        success: function (successData) {
                            element.remove();
                            $("#" + fileName + "Download").remove();
                            $('.'+fileName).remove();
                            console.log('f '+ id);
                            console.log('d '+ dataName);
                            $('#status_change_' + id).attr('data-'+dataName, 1);
                        },
                        error: function () {
                            console.log("error");
                        },
                    });
                }
            });
        });
    });

    $(document).ready(function () {
        //Datepicker function
        var date = new Date();
        date.setDate(date.getDate());
        //$('#pickup_date').datepicker('setDate', date);
        $('#pickup_date').datepicker({
            startDate: date,
            format: 'dd-mm-yyyy',
        });

        $('#pickup_date').on('changeDate', function(ev) {
            $(this).datepicker('hide');
        });
        $(".disabled").addClass("old");

        /******begin: Initiate JQuery Modules********/
        SnippetOrderEditRolePermission.init();
        /******end: Initiate JQuery Modules********/

    });


    //Update Order pickup data
    function orderPickUpBtn(that,selectedStatus) {
        $(document).on('click','#orderPickUpBtn', function(e) {
            if ($('#pickup_datetime_form').parsley().validate()) {
                var formData = new FormData($('#pickup_datetime_form')[0]);
                $.ajax({
                    url: "{{ route('order-pickup-datetime-ajax') }}",
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,

                    success: function(successData) {
                        new PNotify({
                            text: successData.msg,
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 2000
                        });
                        $('#pickup_dateTimeModal').modal('hide');
                        $('#pickup_dateTimeModal').parsley().reset();

                        that.find('option[value="' + selectedStatus + '"]').prop('selected', true);
                        that.change();
                        return false;
                    } //new
                });
            }
        });
    }

    $(document).on('click', '.generatePo', function () {
        var orderId = $(this).attr('data-id');
        $.ajax({
            url: "{{ route('get-order-details-ajax', '') }}" + "/" +
                orderId,
            type: 'GET',
            success: function (successData) {
                if (successData.html) {
                    $('#generatePoModalBlock').html(successData.html);
                    $('#generatePoModal').modal('show');
                }
            },
            error: function () {
                console.log('error');
            }
        });
    });

    //Generate PO first (Only if order status == 2)
    $(document).on('click', '.generatePoFirst', function () {
        var orderId = $(this).attr('data-id');
        var orderStatus= $(this).attr('data-status');
        if(orderStatus != 7){
            var title = '{{__('admin.wait_for_supplier')}}';
        }else{
            var title = '{{__('admin.order_already_cancelled')}}';
        }
        swal({
            title: title,
            icon: "/assets/images/info.png",
            buttons: [, '{{ __('admin.ok') }}'],
            dangerMode: false,
        });
    });


    $(document).on('click', '.sendPoToSupplier', function () {
        $(this).attr('disabled', true);
        let orderId = $(this).attr('data-id');
        $.ajax({
            url: "{{ route('send-po-to-supplier-ajax') }}",
            type: 'POST',
            data: {
                id: orderId,
                comment: $(this).closest(":has(textarea)").find('#comment').val(),
                _token: $("input[name='_token']").val()
            },
            success: function (successData) {
                if(successData.success) {
                    new PNotify({
                        text: '{{__('admin.po_generated_success_alert')}}',
                        type: 'success',
                        styling: 'bootstrap3',
                        animateSpeed: 'fast',
                        delay: 1000
                    });
                    activity_detail_html = successData.activityDetailHtml;
                    $('#activity-detail-div').html(activity_detail_html);
                    $('#generatePoModal').modal('hide');
                }else{
                    $('#activity-detail-div').html(activity_detail_html);
                }
            },
            error: function () {
                $('#activity-detail-div').html(activity_detail_html);
                console.log('error');
            }
        });
    });

    $(document).on('click', '.getSingleOrderDetail', function () {
        var orderId = $(this).attr('data-id');
        $.ajax({
            url: "{{ route('get-single-order-detail-ajax', '') }}" + "/" +
                orderId,
            type: 'GET',
            success: function (successData) {
                console.log(successData);
                if (successData.html) {
                    $('#showDownloadPO').append(successData.html);
                    $('#staticBackdrop').modal('show');
                }
            },
            error: function () {
                console.log('error');
            }
        });
    });
    //Create group after adding pickup date-time and address
    function orderPickUpBatch() {
        var orderId = $("#order_id").val();
        var supplierId = $("#supplier_id").val();
        var order_item_ids = $('#order_item_ids').val();
        var isSupplier = "{{ \Auth::user()->role_id }}";
        if ($('#pickup_datetime_form').parsley().validate()) {

            var formData = new FormData($('#pickup_datetime_form')[0]);
            formData.append('order_item_ids',$('#order_item_ids').val());
            $('#confirmPickupBtn').prop('disabled', true);
            $('#cancelPickupBtn').prop('disabled', true);
            $.ajax({
                url: "{{ route('order-pickup-batch-ajax') }}",
                data: formData,
                type: 'POST',
                contentType: false,
                processData: false,

                success: function(successBatchData) {
                    if(successBatchData.isSupplierProvideLogistics==1){
                        new PNotify({
                            text: "{{__('admin.batch_generated_successfully')}}",
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 2000
                        });
                        $('#confirmPickupBtn').prop('disabled', false);
                        $('#cancelPickupBtn').prop('disabled', false);
                        $('#pickupAddressDateTimeModal').modal('hide');
                        $('#pickupAddressDateTimeModal').parsley().reset();
                        $('#payment-detail-div').html(payment_detail_html);
                        $('#batch-detail-div').html(orderBatchesHtml);
                        $('#order_item_ids').val(order_item_ids);
                        sessionStorage.setItem('is_confirm_pickup',1);
                        var selector=sessionStorage.getItem('order_itm_status_selector');
                        var selectorValue=sessionStorage.getItem('order_itm_status_selector_value');
                        var orderItemId=sessionStorage.getItem('order_itm_status_itemId');
                        var is_validate=sessionStorage.getItem('order_itm_status_is_validate');
                        if(selectorValue){
                            orderItemStatusChange(selectorValue,orderItemId,is_validate);
                        }
                    }else{
                        if(successBatchData.success == true) {
                            //Generate AirWayBill Number
                            $.ajax({
                                url: "{{ route('generate-airwaybill-ajax') }}",
                                type: 'POST',
                                data: {
                                    orderId: orderId,
                                    supplierId: supplierId,
                                    batch_id: successBatchData.id,
                                    pickup_date: successBatchData.order_pickup,
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (successData) {
                                        $('#confirmPickupBtn').prop('disabled', false);
                                        $('#pickupAddressDateTimeModal').modal('hide');
                                        $('#pickupAddressDateTimeModal').parsley().reset();
                                        $('#payment-detail-div').html(payment_detail_html);
                                        $('#batch-detail-div').html(orderBatchesHtml);
                                        $('#order_item_ids').val(order_item_ids);
                                    if(successData.success == true) {
                                        new PNotify({
                                            text: successData.msg,
                                            type: 'success',
                                            styling: 'bootstrap3',
                                            animateSpeed: 'fast',
                                            delay: 2000
                                        });
                                        sessionStorage.setItem('is_confirm_pickup',1);
                                        var selector=sessionStorage.getItem('order_itm_status_selector');
                                        var selectorValue=sessionStorage.getItem('order_itm_status_selector_value');
                                        var orderItemId=sessionStorage.getItem('order_itm_status_itemId');
                                        var is_validate=sessionStorage.getItem('order_itm_status_is_validate');
                                        if(selectorValue){
                                            orderItemStatusChange(selectorValue,orderItemId,is_validate);
                                        }
                                    }else{
                                        new PNotify({
                                            text: successData.msg,
                                            type: 'error',
                                            styling: 'bootstrap3',
                                            animateSpeed: 'fast',
                                            delay: 2000
                                        });
                                        if(successData.msg == 'Charged weight should not be more than 1999 kg'){
                                            new PNotify({
                                                text: "{{__('admin.batch_generated_successfully')}}",
                                                type: 'success',
                                                styling: 'bootstrap3',
                                                animateSpeed: 'fast',
                                                delay: 2000
                                            });
                                            $('#confirmPickupBtn').prop('disabled', false);
                                            $('#cancelPickupBtn').prop('disabled', false);
                                            $('#pickupAddressDateTimeModal').modal('hide');
                                            $('#pickupAddressDateTimeModal').parsley().reset();
                                            $('#payment-detail-div').html(payment_detail_html);
                                            $('#batch-detail-div').html(orderBatchesHtml);
                                            $('#order_item_ids').val(order_item_ids);
                                            sessionStorage.setItem('is_confirm_pickup',1);
                                            var selector=sessionStorage.getItem('order_itm_status_selector');
                                            var selectorValue=sessionStorage.getItem('order_itm_status_selector_value');
                                            var orderItemId=sessionStorage.getItem('order_itm_status_itemId');
                                            var is_validate=sessionStorage.getItem('order_itm_status_is_validate');
                                            if(selectorValue){
                                                orderItemStatusChange(selectorValue,orderItemId,is_validate);
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    }

                }
            });
        }
    }

    //On close pickup data modal, revert back to previous status
    function closePickupModal() {
        /*if(manageDeliverySeparately ==0){
            $('#payment-detail-div').html(payment_detail_html);
        }*/
        $('#payment-detail-div').html(payment_detail_html);
        $('#pickupAddressDateTimeModal').parsley().reset();
        $('#batch-detail-div').html(orderBatchesHtml);
    }

    //Validations for flat pickr date-time js
    function flatPickrValidations() {
        $('#pickup_datetime').flatpickr({
            allowInput: true,
            enableTime: true,
            minDate: 'today',
            dateFormat: 'd-m-Y H:i',
            locale: {
                'firstDayOfWeek': 1 // start week on Monday
            }
        });
    }

     /*****begin: Order RoleWise Permissions**/
    var SnippetOrderEditRolePermission = function(){
        var isJNE = function () {
            $.ajax({
                url: "{{ route('admin.user.check.role') }}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'POST',
                success: function(successData) {
                    if (successData.success) {
                        //Freeze form inputs
                        $('input[type=text]').attr('readonly', true);
                        $('select').attr('readonly', true);
                        $('textarea').attr('readonly', true);
                        $('input[type=checkbox]').attr('disabled', true);
                        $('input[type=file]').attr('disabled', true);
                        $('input[type=file]').next('label').css('background-color','#707889');

                        //Enable all item status manage by role requirement
                        if (jQuery.inArray(parseInt($('.allItemsStatusChange :selected').val()), [2,3,4,5,6]) !== -1) {
                                $('.allItemsStatusChange').attr('readonly', false);
                        }

                        //Enable single item status manage by role requirement
                        $('.orderItemStatusChange').each(function(index, value) {

                            let orderItemStatus = $(this).attr('id');

                            if (jQuery.inArray(parseInt($(this).children("option:selected").val()), [2,3,4,5,6]) !== -1) {
                                $('#'+orderItemStatus).attr('readonly', false);
                            }
                        });

                    }
                },
                error: function() {

                }
            });
        };

        return {
            init: function () {
                isJNE()
            }
        }
    }(1);
    /*****end: Quote RoleWise Permissions**/

    /*****begin: Order Trubleshoot Edit**/
    var SnippetOrderTroubleshootEdit = function () {

        // Is order adjusted or not after troubleshoot
        var verifyOrderAdjusted = function () {
            let orderId = $(location).attr('href').split("/").splice(5, 6).join("/");
            let status = false;
            $.ajax({
                url:     "{{ route('admin.order.adjustment.amount.verify') }}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type:    'POST',
                data :   { id : orderId},
                async: false,
                success: function (data) {
                    if (data.success == true) {
                        confirmOrderAdjustmentAmount();
                    } else {
                        status = true;
                    }

                },
                error: function () {
                }
            });

            return status;

        },

        // Display Order Troubleshooting custom input
        renderOrderTroubleshooting = function () {
            var form = document.createElement("div");
            form.innerHTML = `
                <div class="col-md-12" style="text-align: initial;">
                    <label for="adjustment_amount" class="form-label">{{__('admin.adjustment_amount')}}<span class="text-danger">*</span></label>
                    <input type="text" class="form-control input-decimal" name="adjustment_amount" id="adjustment_amount" placeholder="{{__('admin.adjustment_amount_placeholder')}}" required><br>
                    <span id="adjustment_amount_error" class="text-danger d-none">{{__('admin.adjustment_amount_error')}}</span>

                </div>
            `;
            swal({
                text: "{{__('admin.please_confirm_adjustment_amount')}}",
                content: form,
                icon: "/assets/images/info.png",
                buttons: ["{{ __('admin.cancel') }}", "{{ __('admin.ok') }}"]
            }).then((response) => {
                if (response) {
                    var amount = $('#adjustment_amount').val();
                    updateOrderAdjustmentAmount(amount);


                } else {
                    confirmOrderAdjustmentAmount();

                }
            });

        },

        // Update Order adjustment amount
        updateOrderAdjustmentAmount = function (adjustmentAmount) {
            var orderId = $(location).attr('href').split("/").splice(5, 6).join("/");
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                data: {id: orderId, amount: adjustmentAmount},
                url : "{{route('admin.order.adjustment.amount.update')}}",
                success: function (data){
                    if (data.success == false) {
                        new PNotify({
                            text: data.message,
                            type: 'error',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 3000
                        });
                    } else {
                        orderItemStatusChange($('select[name="allItemsStatusChange"] :selected'),0,1);
                    }
                },
                error: function (data){
                    new PNotify({
                        text: "{{__('admin.something_went_wrong')}}",
                        type: 'error',
                        styling: 'bootstrap3',
                        animateSpeed: 'fast',
                        delay: 3000
                    });
                }
            });
        },

        // Display final value as adjustment amount
        confirmOrderAdjustmentAmount = function () {
            var finalAmount = $('#paybleAmount').attr('data-value');
            swal({
                text: "{{__('admin.please_confirm_adjustment_amount')}}",
                title: "Rp "+finalAmount,
                icon: "/assets/images/info.png",
                buttons: ["{{ __('admin.no') }}", "{{ __('admin.yes') }}"]
            }).then((response) => {
                if (!response) {
                    renderOrderTroubleshooting();

                } else {
                    updateOrderAdjustmentAmount(finalAmount);
                }
            });

        };

        return {

            isOrderAdjusted : function () {
                return verifyOrderAdjusted();
            }
        }
    }(1);
    /*****end: Order Trubleshoot Edit**/
    //open model
     $(document).on('click', '.js-openmodel', function(e) {
        $('#filenamedoc').html('');
        id = $(this).attr('data-id');
        $('#UploadModal').modal('show')
        $('#orderdata').val(id);
    })
     //save doc
    $(document).on('click', '.saveOrderdoc', function(e) {
        var formData = new FormData($('#uploadOrderDoc')[0]);
        $.ajax({
            url: "{{ route('upload-order-doc-ajx') }}",
            data: formData,
            type: 'POST',
            contentType: false,
            processData: false,
            success: function(successData) {
                $('#UploadModal').modal('hide')
                $('#filenamedoc').html(successData.name);
            },
            error: function() {
                console.log('error');
            }
        });
    });
    //show name
    $(document).on("change", "#orderdoc", function(e) {
        var $input = $(this);
        var inputFiles = this.files;
        let file = inputFiles[0];
        let size = Math.round((file.size / 1024))
        if(size > 3000){
            swal({
                icon: 'error',
                title: '',
                text: '{{ __('profile.file_size_under_3mb') }}',
            })
            return false;
        }
        let fileName = file.name;
        $('#filenamedoc').html(fileName);
        valid = false;
    })
    //remove file
    $(document).on("click", ".removeFile", function(e) {
        e.preventDefault();
        let name = $(this).attr("data-name");
        let type = $(this).attr("data-type");
        let data = {
            fileName: name,
            id: $(this).attr("data-id"),
            _token: $('meta[name="csrf-token"]').attr("content"),
            type : type
        };
        swal({
            title: '{{ __('profile.are_you_sure') }}',
            text: '{{ __('profile.once_deleted_you_will') }}',
            icon: "/assets/images/bin.png",
            buttons: ['{{ __('profile.change_no') }}', '{{ __('profile.delete') }}'],
            dangerMode: true,
        }).then((deleteit) => {
            if (deleteit) {
                $.ajax({
                url: "{{ route('profile-company-file-delete-ajax-admin') }}",
                data: data,
                type: "POST",
                    success: function(successData) {
                        $("#file-"+ name).html('');
                    },
                    error: function() {
                        console.log("error");
                    },
                });
            }
        });
    });

    function downloadAirWayBill(airWayBillNumber){
            let clickedLinkName = '#downloadAirWayBill'+airWayBillNumber;
        if(airWayBillNumber){
                $.ajax({
                    url:"{{ route('get-shipping-label', '') }}" + "/" + airWayBillNumber,
                    type: 'GET',
                    beforeSend:function(){
                        $(clickedLinkName).addClass('add-airwaybill-download-process-cursor');
                        $('.sellerDownloadAirWayBill').attr('disabled','true');
                        $('#downloadAirWayBillDiv').addClass('pointer-event-none');
                    },
                    success: function (response) {
                        if(response.status == true){
                            $('#shippingLabelPreview').attr('href',response.pdfUrl);
                            $('#shippingLabelPreview')[0].click();
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
                        $(clickedLinkName).removeClass('add-airwaybill-download-process-cursor');
                        $('.sellerDownloadAirWayBill').removeAttr('disabled');
                    },
                    error: function() {
                        console.log('error');
                        $(clickedLinkName).removeClass('add-airwaybill-download-process-cursor');
                    }
                });
        }else{
            return ''
        }
    }
</script>
