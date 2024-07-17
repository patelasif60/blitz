<script>
    sessionStorage.setItem("pd_orders", JSON.stringify([]));

        function accordion_click(){
            let orders = JSON.parse(sessionStorage.getItem("pd_orders"));
            $('.pd_section input.pd-order-check,.pd_section input.pd-select-all').prop('checked',false);
            sessionStorage.setItem("pd_orders", JSON.stringify([]));
            if(orders[0]!=undefined){
                let supplier_id = orders[0].supplier_id;
                pd_proceed_button(supplier_id);
                $('#collapseOne'+supplier_id+' #pd_transaction_charge'+supplier_id+' tr').removeClass('d-none');
                $('#collapseOne'+supplier_id+' #pd_transaction_charge'+supplier_id+' tr').addClass('d-none');
            }
        }


    function pd_order_check(supplier_id,selector) {
        add_remove_order(supplier_id,selector);
        pd_proceed_button(supplier_id);
    }

    function pd_select_all(supplier_id,selector){
        $("#collapseOne"+supplier_id+" input.pd-order-check").prop('checked',selector.prop('checked'));
        $("#collapseOne"+supplier_id+" input.pd-order-check").each(function () {
            add_remove_order(supplier_id,$(this));
        });
        pd_proceed_button(supplier_id);
    }

    function pd_checkout(supplier_id){
        $('#pd_order_list'+supplier_id).toggleClass('d-none');
        $('#pd_checkout'+supplier_id).toggleClass('d-none');
    }

    function cancel_payment(supplier_id,bulk_payment_id) {console.log(supplier_id,bulk_payment_id);
        if (supplier_id && bulk_payment_id) {

            swal({
                title: "{{  __('admin.charges_delete_alert') }}",
                icon: location.origin+"/front-assets/images/cancel_payment.png",
                buttons: ['{{ __('admin.no') }}', '{{ __('admin.yes') }}, {{ __('order.cancel_payment') }}'],
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        let senddata = {
                            supplier_id: supplier_id,
                            bulk_payment_id: bulk_payment_id,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        }
                        $.ajax({
                            url: '{{ route('dashboard-cancel-payment-ajax') }}',
                            type: 'POST',
                            data: senddata,
                            success: function(successData) {
                                console.log(successData);
                                if (successData.success) {
                                    paymentSection = successData.html;
                                    $('#mainContentSection').html(successData.html);
                                    $('#myPaymentCount').html(successData.supplierPaymentCount);
                                    $('html, body').animate({
                                        scrollTop: $("#mainContentSection").offset().top
                                    }, 50);
                                    $('.payment-accordion-btn'+supplier_id).trigger('click');
                                    total_payment_calc();
                                }else{
                                    swal({
                                        text: successData.message,
                                        icon: "warning",
                                        dangerMode: true,
                                    });
                                }
                            },
                            error: function() {
                                console.log('error');
                            }
                        });

                    }
                });
        }
    }

    function pd_pay_now(supplier_id) {
        let orders = JSON.parse(sessionStorage.getItem("pd_orders"));
        let supplier_order = orders.filter(function(item){ return item['supplier_id'] == supplier_id})
        let order_ids = supplier_order.map(item => item.order_id);
        if (supplier_id && order_ids.length) {
            $.ajax({
                url: '{{ route('dashboard-bulk-payment-ajax') }}',
                type: 'POST',
                data: {
                    supplier_id: supplier_id,
                    order_ids: order_ids,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (successData) {
                    console.log(successData);
                    if (successData.success) {
                        let remaining_order = orders.filter(function(item){ return item['supplier_id'] != supplier_id})
                        paymentSection = successData.html;
                        if (paymentSection){
                            sessionStorage.setItem("pd_orders",JSON.stringify(remaining_order?remaining_order:[]));
                            $('#mainContentSection').html(successData.html);
                            $('#myPaymentCount').html(successData.supplierPaymentCount);
                            $('html, body').animate({
                                scrollTop: $("#mainContentSection").offset().top
                            }, 50);
                            $('.payment-accordion-btn'+supplier_id).trigger('click');
                        }
                        total_payment_calc();
                        window.open(successData.payment_url,'_blank');
                    }else {
                        swal({
                            text: successData.message,
                            icon: "warning",
                            dangerMode: true,
                        });
                    }
                },
                error: function () {
                    console.log('error');
                }
            });
        }
    }

    function add_remove_order(supplier_id,selector){
        let order_id = selector.val();
        if (selector.prop('checked')){
            let orders = sessionStorage.getItem("pd_orders")?JSON.parse(sessionStorage.getItem("pd_orders")):[];
            orders.push({supplier_id:supplier_id,order_id:parseInt(order_id)});
            sessionStorage.setItem("pd_orders", JSON.stringify(orders));
            $('#collapseOne'+supplier_id+' #pd_order'+order_id).removeClass('d-none');
        }else{
            let orders = JSON.parse(sessionStorage.getItem("pd_orders"));
            let new_orders = orders.filter(function(item){ return item['supplier_id'] == supplier_id && item['order_id'] != order_id })
            sessionStorage.setItem("pd_orders", JSON.stringify(new_orders));
            $('#collapseOne'+supplier_id+' #pd_order'+order_id).addClass('d-none');
        }
        if($("#pd_order_list"+supplier_id+" input.pd-order-check:checked").length==$("#pd_order_list"+supplier_id+" input.pd-order-check").length){
            $("#pd_select_all"+supplier_id).prop('checked',true);
        }else{
            $("#pd_select_all"+supplier_id).prop('checked',false);
        }
    }

    function pd_proceed_button(supplier_id) {
        if ($("#collapseOne"+supplier_id+" input.pd-order-check:checked").length>0){
            $('#pd_proceed_to_checkout'+supplier_id).attr('disabled',false);
        }else{
            $('#pd_proceed_to_checkout'+supplier_id).attr('disabled',true);
        }
        pd_calc(supplier_id)
    }

    function pd_calc(supplier_id) {
        let transactions_charges = $('#pd_transaction_charge'+supplier_id).attr('data-transaction_charge');
        let tot = 0;
        let tot_orders = 0;
        $("#pd_transaction_charge"+supplier_id+" .pd_calc").each(function() {
            let amount = parseFloat($(this).attr('data-amount'));
            if(!$(this).hasClass('d-none')) {
                tot = tot + amount;
                tot_orders++;
            }
        });
        let tot_transactions_charges = SnippetPaymentTab.bulkOrderDiscount(supplier_id);

        $('#pd-subtotal-amount'+supplier_id).html('Rp '+ number_format(Math.round(tot),2));
        $('#pd-discount-amount'+supplier_id).html('Rp '+ number_format(Math.round(tot_transactions_charges),2));
        $('#pd-total-amount'+supplier_id).html('Rp '+ number_format(Math.round(tot-tot_transactions_charges),2));
    }

    function total_payment_calc() {
        $(".bp_total_payment").each(function() {
            let tot = parseFloat($(this).attr('data-amount'));
            let supplier_id = parseFloat($(this).attr('data-supplier_id'));
            $(".bp_bs_payment"+supplier_id).each(function() {
                let amount = parseFloat($(this).attr('data-amount'));
                tot = tot + amount;
            });
            $('#bp_total_payment'+supplier_id).html(number_format(Math.round(tot),2));
        });
    }

    var SnippetPaymentTab = function () {

            return {
                bulkOrderDiscount : function (supplierId) {
                    var count = 0, amount = 0, totalDiscount = 0, averageCharge = 0;
                    $("#pd_transaction_charge"+supplierId+" .pd_calc").each(function() {
                        let amount = parseFloat($(this).attr('data-transaction_charge'));
                        if(!$(this).hasClass('d-none')) {
                            count++;
                            totalDiscount = totalDiscount + amount;
                        }
                    });
                    averageCharge = parseFloat(totalDiscount/count);
                    totalDiscount = parseFloat(totalDiscount - averageCharge);

                    return totalDiscount;
                }
            }

    }(1);
</script>
