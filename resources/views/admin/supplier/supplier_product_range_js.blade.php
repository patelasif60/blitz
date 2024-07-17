<script>
    //@ekta 18-04 textbox value numeric set
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

    //unit change set all clone column unit
    function unitChange(index) {
        //console.log('hello');
        var unitID = $('#supplierProductUnit').val();
        if (unitID) {
            $(".unitVal").val(unitID);
        }
    }

    function cloneDiv() {
        var index = '';
        if ($("#cloneDiv select:last").length == 0) {
            index = $("#cloneDiv select").length + 1;
        } else {
            index = $("#cloneDiv select").length + 1;
        }
        //check first range
        if (($('#min_qty').val() != '') && ($('#max_qty').val() != '') && ($('#discount').val() != '')) {
            //check all previous range
            $('#showErrDiscount' + top_index).html('');
            if (($('#min_qty' + top_index).val() != '') && ($('#max_qty' + top_index).val() != '') && ($('#discount' + top_index).val() != '')) {
                var maxOrderDays  = parseInt($('#supplierProductMaxQuantity').val());
                if(index == 1){
                    lastMaxQtyVal = $("#max_qty").val();
                }else{
                    lastMaxQtyVal = $("#max_qty" + (index - 1)).val();
                }
                //for check lastMaxQtyVal is less then the  maxOrderQty
                if(maxOrderDays > lastMaxQtyVal){
                    $("#supplierProductMaxQuantity").removeClass('border-danger');
                    if(index == 1){
                        $("#max_qty").removeClass('border-danger');
                    }else{
                        $("#max_qty" + (index - 1)).removeClass('border-danger');
                    }
                    groupClone(index);
                }else{
                    //if both qty is equal then highlight
                    $("#supplierProductMaxQuantity").addClass('border-danger');
                    if(index == 1){
                        $("#max_qty").addClass('border-danger');
                    }else {
                        $("#max_qty" + (index - 1)).addClass('border-danger');
                    }
                }
            }else{
                //empty textvalue show error message
                var err = '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">This value is required.</li></ul>'
                if($('#max_qty' + top_index).val() == ''){
                    $('#showErr' + top_index).html(err);
                }
                if($('#discount' + top_index).val() == ''){
                    $('#showErrDiscount' + top_index).html(err);
                }
            }
        }else{
            $("#min_qty").parsley().validate();
            $("#max_qty").parsley().validate();
            $("#discount").parsley().validate();
        }
        // + all div set unit
        unitChange(index);
    }

    function groupClone(index) {
        var id = '';
        if(index == 1){
            id = '#max_qty';
            $("#min_qty").attr("readonly", true);
            $("#max_qty").attr("readonly", true);
            $("#discount").attr("readonly", true);
        } else {
            id = '#max_qty'+(index-1);
            $("#min_qty" + index).attr("readonly", true);
            $("#max_qty" + (index - 1)).attr("readonly", true);
            $("#discount" + (index - 1)).attr("readonly", true);
            $("#deleteBtn" + (index - 1)).addClass('d-none');
        }
        //var $clone = $("#mainDiv").clone(true);
        var $clone = $("#group_productDiv").clone(true);
        top_index = index;
        //next 2 use for edit s_id , custom_add;
        $clone.find("#s_id").attr("id", "s_id" + index).val(0);
        $clone.find("#custom_add").attr("id", "custom_add" + index).val(1);
        $clone.find("#min_qty").attr("id", "min_qty" + index).val(parseInt($(id).val())+1||0);
        $clone.find("#max_qty").attr("id", "max_qty" + index).attr("readonly", false).val('');
        $clone.find("#showErr").attr("id", "showErr" + index).val('');
        $clone.find("#showErrMin").attr("id", "showErrMin" + index).val('');
        $clone.find("#showErrDiscount").attr("id", "showErrDiscount" + index).val('');
        $clone.find("#unit").attr("id", "unit" + index).val('');
        $clone.find("#discount").attr("id", "discount" + index).attr("readonly", false).val('');
        $clone.find("#discount_price").attr("id", "discount_price" + index).val('');
        $clone.find("#deleteBtn").attr("id", "deleteBtn" + index).val('');
        $clone.find('.min_qty').attr('for', 'min_qty' + index);
        $clone.find('.max_qty').attr('for', 'max_qty' + index);
        $clone.find('.unit').attr('for', 'unit' + index);
        $clone.find('.discount').attr('for', 'discount' + index);
        $clone.find('.discount_price').attr('for', 'discount_price' + index);
        $clone.find('.deleteRange').removeClass('d-none');
        $clone.find('.deleteRange').show();
        $clone.appendTo($("#cloneDiv"));
    }

    //check min qty is less then max qty
    function minQty(data) {
        //check minimum order qty is lessthen of maximum order qty
        var id = data.id;
        var replace_id = id.replace('min_qty', '');
        $('#showErrMin' + replace_id).html('');
        var minDays  = parseInt($('#min_qty' + replace_id).val());
        var maxDay = parseInt($('#max_qty' + replace_id).val());
        if (maxDay != '' && (maxDay < minDays)) {
            var err = '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">{{ __('admin.minimum_quantity_must_be_less_then_maximum_quantity') }}</li></ul>'
            $('#min_qty' + replace_id).val('');
            $('#showErrMin' + replace_id).html(err);
        }

        //check min_order_qty and min_qty equal to or not
        var minOrderDays  = parseInt($('#supplierProductMinQuantity').val());
        //console.log(minOrderDays);
        if(minOrderDays != '' && (minOrderDays != minDays)){
            var err = '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">{{ __('admin.minimum_order_quantity_must_be_equal_to_minimum_quantity') }}</li></ul>'
            $('#min_qty' + replace_id).val('');
            $('#showErrMin' + replace_id).html(err);
        }
    }

    //check max qty is bigger then min qty
    function maxQty(data) {
        var id = data.id;
        var replace_id = id.replace('max_qty', '');
        $('#showErr' + replace_id).html('');
        var maxDay = parseInt($('#max_qty' + replace_id).val());
        var minDays = parseInt($('#min_qty' + replace_id).val());
        if (maxDay <= minDays) {
            var err = '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">{{ __('admin.maximum_quantity_must_be_higher_then_minimum_quantity') }}</li></ul>'
            $('#showErr' + replace_id).html(err);
            $('#max_qty' + replace_id).val('');
        }

        //check max_order_qty is equal oe less then max_qty
        var maxOrderDays  = parseInt($('#supplierProductMaxQuantity').val());
        if (maxOrderDays !='' && (maxOrderDays < maxDay)) {
            var err = '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">{{ __('admin.maximum_quantity_must_be_equal_to_maximum_order_quantity') }}</li></ul>'
            $('#showErr' + replace_id).html(err);
            $('#max_qty' + replace_id).val('');
        }
    }

    function priceChange() {
        if ($('.discountVal').length) {
            $('.discountVal').each(function () {
                if ($(this).val()) {
                    setDiscountAmtVal($(this));
                }
            });
        }else{
            setDiscountAmtVal(0);
        }
    }

    function setDiscountAmtVal(selector) {
        let row = selector.closest('.row.productDiv');
        let value = parseFloat(row.find('.discountVal').val()?row.find('.discountVal').val():0);
        let price = parseFloat($('#supplierProductPrice').val()?$('#supplierProductPrice').val():0);
        let discountAmount = 0;
        discountAmount = price - ((price * value) / 100);
        if(isNaN(discountAmount)) {
            discountAmount = 0;
        }
        row.find('.discountAmount').val(Math.round(discountAmount));
        // if(price){
        //
        // }
        // if (value) {
        //     discountAmount = price - ((price * value) / 100);
        // } else {
        //     discountAmount = 0 ;
        // }
        // row.find('.discountAmount').val(Math.round(discountAmount));
        // row.find('.discountAmount').val(parseFloat(discountAmount).toFixed(2));
        //$('#finalAmount').val(0);
    }

    if ($("#mainDiv #group_productDiv").length == 1) {
        $("#mainDiv #group_productDiv").find('.deleteRange').hide();
    }

    $('#showErr').html('');
    $('#showErrMin').html('');
    $('#showErrMinOrder').html('');
    $('#showErrMaxOrder').html('');
    $('#showErrDiscount').html('');

    //check minqty is equal to minorderqty
    function minOrderQty(data) {
        //check minimum order qty is lessthen of maximum order qty
        $('#showErrMinOrder').html('');
        var minOrderDays  = parseInt($('#supplierProductMinQuantity').val());
        var maxOrderDay = parseInt($('#supplierProductMaxQuantity').val());
        if (maxOrderDay != '' && (maxOrderDay < minOrderDays)) {
            var err = '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">{{ __('admin.minimum_order_quantity_must_be_less_then_maximum_order_quantity') }}</li></ul>'
            $('#supplierProductMinQuantity').val('');
            $('#showErrMinOrder').html(err);
        }
        //after added min qty in discount range change min order qty
        $("#cloneDiv").html('');
        $("#min_qty").attr("readonly", false);
        //if minimum order quantity change set minimum quantity
        $("#min_qty").val(minOrderDays?minOrderDays:0);
        $("#max_qty").val('');
        $("#discount").val('');
        $("#discount_price").val(0);
        $("#max_qty").attr("readonly", false);
        $("#discount").attr("readonly", false);
        //remove parslay validation
        $('#min_qty').parsley().reset();
        $('#showErrMin').html('');
    }

    //if maxorderqty is changed to set and remove all clondiv
    function maxOrderQty(data) {
        $('#showErrMaxOrder').html('');
        var maxOrderDays = parseInt($('#supplierProductMaxQuantity').val());
        var minOrderDays = parseInt($('#supplierProductMinQuantity').val());

        //after added max qty in discount range change max order qty
        if(minOrderDays >= maxOrderDays){
            var err = '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">{{ __('admin.maximum_order_quantity_must_be_higher_then_minimum_order_quantity') }}</li></ul>'
            $('#showErrMaxOrder').html(err);
            $('#supplierProductMaxQuantity').val('');
        }else {
            if ($('.cloanMinQty').length) {
                $('.cloanMinQty').each(function () {
                    if (($(this).val()) > maxOrderDays) {
                        console.log($(this).val());
                        $(this).parent().parent('.productDiv').remove();
                        $('#max_qty' + (($('.cloanMinQty').length) - 1)).val(maxOrderDays?maxOrderDays:0);
                        $('#max_qty' + (($('.cloanMinQty').length) - 1)).attr("readonly", false);
                        $('#discount' + (($('.cloanMinQty').length) - 1)).attr("readonly", false);
                        if ($('.cloanMinQty').length == 1) {
                            $('#max_qty').val(maxOrderDays?maxOrderDays:0);
                            $('#max_qty').attr("readonly", false);
                            $('#discount').attr("readonly", false);
                        }
                    }else{
                        // max order qty is grater them min qty then set same range max qty
                        $('#max_qty' + (($('.cloanMinQty').length) - 1)).val(maxOrderDays?maxOrderDays:0);
                        if ($('.cloanMinQty').length == 1) {
                            $('#max_qty').val(maxOrderDays?maxOrderDays:0);
                            $('#max_qty').attr("readonly", false);
                            $('#discount').attr("readonly", false);
                        }
                    }
                });
            }
        }
        //remove parslay validation
        $('#max_qty').parsley().reset();
        $('#showErr').html('');
    }

    //delete discount range cloan
    $('body').on('click', '.removeRange', function () {
        //for get previous row index
        var delteId = $(this).attr('id');
        var delIndex = delteId.split("deleteBtn");
        $("#max_qty" + (delIndex[1] - 1)).attr("readonly", false);
        $("#discount" + (delIndex[1] - 1)).attr("readonly", false);
        $("#deleteBtn" + (delIndex[1] - 1)).removeClass('d-none');
        $(this).closest('.productDiv').remove();
        if ($("#cloneDiv select:last").length == 0) {
            $("#min_qty").attr("readonly", false);
            $("#max_qty").attr("readonly", false);
            $("#discount").attr("readonly", false);
        }
    });

    $(document).on('click','.resetModel', function(e) {
        $('#supplierProductForm').parsley().reset();
        $('#supplierProductForm')[0].reset();
        $("#cloneDiv").html('');
        $("#min_qty").val('');
        $("#max_qty").val('');
        $("#discount").val('');
        $("#discount_price").val(0);
        $("#min_qty").attr("readonly", false);
        $("#max_qty").attr("readonly", false);
        $("#discount").attr("readonly", false);
        $("#unit").val('');
        $("#supplierProductMaxQuantity").removeClass('border-danger');
    })

    /*end ekta*/

</script>
