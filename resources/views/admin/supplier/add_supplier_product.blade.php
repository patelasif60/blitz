@extends('admin/adminLayout')

@push('bottom_head')
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <style>
        .upload_btn {
            background-color: rgb(37, 55, 139);
            color: white;
            border-radius: 0.3rem;
            padding: 3px 7px;
            cursor: pointer;
        }

        .parsley-errors-list li {
            font-weight: normal;
        }

        .awesomplete {
            display: block;
        }
    </style>
@endpush

@section('content')

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="row">
                <div class="col-md-12 d-flex align-items-center mb-3">
                    <h1 class="mb-0 h3">{{ __('admin.product') }}</h1>
                    <a href="{{ route('products-list') }}" class="mb-2 backurl ms-auto btn-close"></a>
                </div>

                <div class="col-12">
                    <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                                {{__('admin.add_product')}}
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content pt-3 pb-0" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <form id="supplierProductForm" enctype="multipart/form-data" class="" data-parsley-validate>
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 pb-2">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center">
                                                <h5><img src="{{ URL('front-assets/images/icons/shopping-cart.png') }}" alt="Order Details" class="pe-2"> {{ __('admin.category_details') }}</h5>
                                            </div>
                                            <div class="card-body p-3 pb-1">
                                                <div class="row rfqform_view bg-white align-items-center">
                                                    <div class="col-md-6 mb-3">
                                                                <div class="search_rfq position-relative">
                                                                    <label class="form-label" for="Search_category">{{__('admin.search_product')}}</label>
                                                                    <input type="text" name="tags" id="tags" class="form-control categorysearch" placeholder="{{__('admin.product_search')}}" />
                                                                </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3"><small class="fw-bold text-muted mt-3 count" style="display: none"></small></div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="supplierCategory" class="form-label">{{ __('admin.category') }}<span class="text-danger">*</span></label>
                                                        <div class="position-relative">
                                                            <select name="supplierCategory" id="supplierCategory" class="form-select selectBox" required>
                                                                <option selected disabled>{{__('admin.select_category') }}</option>
                                                                @foreach ($category as $categoryItem)
                                                                    <option value="{{ $categoryItem->id }}" data-text="{{ $categoryItem->name }}">
                                                                        {{ $categoryItem->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <i class="fa fa-chevron-down downArrowIcon"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="supplierSubCategory" class="form-label">{{ __('admin.sub_category') }}<span class="text-danger">*</span></label>
                                                        <div class="position-relative">
                                                            <select name="supplierSubCategory" id="supplierSubCategory" class="form-select selectBox" required>
                                                                <option selected disabled>{{__('admin.select_sub_category')}}</option>
                                                            </select>
                                                            <i class="fa fa-chevron-down downArrowIcon"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3" id="">
                                                        <label for="supplierProduct" class="form-label">{{ __('admin.product_name') }}<span class="text-danger">*</span></label>
                                                        <div class="position-relative">
                                                            <select name="supplierProduct" id="supplierProduct" class="form-select selectBox" required>
                                                                <option selected disabled>{{ __('admin.select_product_name') }}</option>
                                                            </select>
                                                            <i class="fa fa-chevron-down downArrowIcon"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 pb-2">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center">
                                                <h5><img height="20px" src="{{ URL('front-assets/images/icons/people-carry-1.png') }}" alt="Supplier Details" class="pe-2"> {{ __('admin.product_detail') }}</h5>
                                            </div>
                                            <div class="card-body p-3 pb-1">
                                                <div class="row rfqform_view bg-white">

                                                    <div class="col-md-6 mb-3">
                                                        <label for="productRef" class="form-label">{{ __('admin.product_code_specification') }}</label>
                                                        <input type="text" name="productRef" id="productRef" class="form-control">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">{{ __('admin.upload_product_images') }}</label>
                                                        <div class="d-flex text-center" style="align-items: center;">
                                                        <span class="">
                                                            <input type="file" name="productImages" id="productImages" accept="image/*" onchange="show(this)" hidden />
                                                            <label class="upload_btn text-white" for="productImages">{{ __('admin.browse') }}</label>
                                                        </span>
                                                            <div id="file-productImages"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3" id="">
                                                        <label for="supplierProductDiscription" class="form-label">{{ __('admin.product_description') }}<span class="text-danger">*</span></label>
                                                        <textarea rows="5" class="form-control newtextarea" id="description" name="supplierProductDiscription" required data-parsley-errors-container="#description_error"></textarea>
                                                        <div id="description_error"></div>
                                                    </div>

                                                    <div class="col-md-6 mb-3" id="brandBlock">
                                                        <label for="supplierProductBrand" class="form-label">{{ __('admin.brand') }}({{ __('admin.comma_seprated') }})</label>
                                                        <input data-list="{{ $brand }}" name="supplierProductBrand" id="supplierProductBrand" data-multiple class="form-control" />
                                                    </div>
                                                    <div class="col-md-6 mb-3" id="gradeBlock">
                                                        <label for="supplierProductGrade" class="form-label">{{ __('admin.grade') }}({{ __('admin.comma_seprated') }})</label>
                                                        <input data-list="{{ $grade }}" name="supplierProductGrade" id="supplierProductGrade" data-multiple class="form-control" />
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="supplierProductPrice" class="form-label">{{ __('admin.price') }}<span class="text-danger">*</span></label>
                                                        <input type="text" name="supplierProductPrice" id="supplierProductPrice" class="form-control" data-parsley-type="number" onkeypress="return isNumberKey(this, event);" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="supplierProductUnit" class="form-label">{{ __('admin.select_unit') }}<span class="text-danger">*</span></label>
                                                        <div class="position-relative">
                                                            <select name="supplierProductUnit" id="supplierProductUnit" class="form-select selectBox" onchange="unitChange($(this))" required>
                                                                <option selected disabled>{{__('admin.select_unit')}}</option>
                                                                @foreach ($unit as $unitItem)
                                                                    <option value="{{ $unitItem->id }}">{{ $unitItem->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="fa fa-chevron-down downArrowIcon"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="supplierProductMinQuantity" class="form-label">{{ __('admin.minimum_order_quantity') }}<span class="text-danger">*</span></label>
                                                        <input type="text" name="supplierProductMinQuantity" id="supplierProductMinQuantity" class="form-control" data-parsley-type="number" data-parsley-minlength="1" min="1" onchange="minOrderQty(this)" onkeypress="return isNumberKey(this, event);" required>
                                                        <div id="showErrMinOrder" name="showErrMinOrder"></div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="supplierProductMaxQuantity" class="form-label">{{ __('admin.maximum_order_quantity') }}<span class="text-danger">*</span></label>
                                                        <input type="text" name="supplierProductMaxQuantity" id="supplierProductMaxQuantity" class="form-control" data-parsley-type="number" data-parsley-minlength="1" min="1" onchange="maxOrderQty(this)" onkeypress="return isNumberKey(this, event);" required>
                                                        <div id="showErrMaxOrder" name="showErrMaxOrder"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 pb-2">
                                        {{-- <div class="col-md-12 mb-2">--}}
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/boxes.png')}}" alt="Product Range" class="pe-2">
                                                    <span>Product Range <span class="icon ms-1"><a href="javascript:void(0)" id="btnClone" onclick="cloneDiv()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg></a></span></span>
                                                </h5>
                                            </div>
                                            <div class="card-body p-3 pb-1" id="removeCloanDiv">
                                                <div id="mainDiv">
                                                    <div class="row productDiv" id="group_productDiv">
                                                        <div class="col-md-2 mb-3">
                                                            <label for="min_qty" class="form-label min_qty">{{ __('admin.min_quantity') }}<span class="text-danger">*</span></label>
                                                            <input type="text" id="min_qty" name="min_qty[]" class="form-control cloanMinQty" onchange="minQty(this)" onkeypress="return isNumberKey(this, event);" required>
                                                            <div id="showErrMin" name="showErrMin[]"></div>
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <label for="max_qty" class="form-label max_qty">{{ __('admin.max_quantity') }}<span class="text-danger">*</span></label>
                                                            <input type="text" id="max_qty" name="max_qty[]" class="form-control cloanMaxQty" onchange="maxQty(this)" onkeypress="return isNumberKey(this, event);" required>
                                                            <div id="showErr" name="showErr[]"></div>
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <label for="unit" class="form-label unit">{{ __('admin.select_unit') }}</label>
                                                            <select class="form-select unitVal" id="unit" name="unit[]" disabled>
                                                                <option disabled selected>{{ __('admin.select_unit') }}</option>
                                                                @foreach ($unit as $unitItem)
                                                                    <option value="{{ $unitItem->id }}">{{ $unitItem->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <label for="discount" class="form-label discount">{{ __('admin.discount') }}%<span class="text-danger">*</span></label>
                                                            <input type="text" id="discount" name="discount[]" class="form-control discountVal" required onkeypress="return isNumberKey(this, event);">
                                                            <div id="showErrDiscount" name="showErrDiscount[]"></div>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label for="discount_price" class="form-label discount">{{ __('admin.discount_price')  }}</label>
                                                            <input type="text" id="discount_price" name="discount_price[]" class="form-control discountAmount" readonly>
                                                        </div>
                                                        <div class="col-md-1 pt-1 mb-3 ">
                                                            <label for="" class="form-label"></label>
                                                            <div style="line-height: 38px;" id="deleteBtn" name="deleteBtn[]"><span class="icon deleteRange d-none"><a href="javascript:void(0)" id="deleteBtn" class="text-danger removeRange "><i class="fa fa-trash mt-3"></i></a></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="cloneDiv"></div>
                                                <div class="col-md-12 bg-white d-flex justify-content-end" id="warningDiv"></div>
                                                <input type="hidden" name="supplierId" id="supplierId" value="{{$supplier->supplier_id}}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary" id="AddProductData">{{ __('admin.add')}}</button>
                                        <a class="btn btn-cancel ms-3" href="{{ route('products-list') }}">{{ __('admin.cancel')}}</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var supplierId = $("#supplierId").val();
        var editSupplierProductId = '';
        var top_index = 0 ;
        // for use last maximum qty is less then the maxorder qty
        var lastMaxQtyVal = 0;

        $(document).ready(function() {

            //Get Supplier Brands
            new Awesomplete('#supplierProductBrand', {
                filter: function(text, input) {
                    return Awesomplete.FILTER_CONTAINS(text, input.match(/[^,]*$/)[0]);
                },
                item: function(text, input) {
                    return Awesomplete.ITEM(text, input.match(/[^,]*$/)[0]);
                },
                replace: function(text) {
                    var before = this.input.value.match(/^.+,\s*|/)[0];
                    this.input.value = before + text + ", ";
                }
            });

            //Get Supplier Grades
            new Awesomplete('#supplierProductGrade', {
                filter: function(text, input) {
                    return Awesomplete.FILTER_CONTAINS(text, input.match(/[^,]*$/)[0]);
                },
                item: function(text, input) {
                    return Awesomplete.ITEM(text, input.match(/[^,]*$/)[0]);
                },
                replace: function(text) {
                    var before = this.input.value.match(/^.+,\s*|/)[0];
                    this.input.value = before + text + ", ";
                }
            });

            /*---------------- @ekta 1804 ------------*/
            //onchange price - change discount amount
            $("#supplierProductPrice").keyup(function () {
                priceChange();
            });

            $(document).on('keyup', '.discountVal', function(e) {
                var id = $(this).attr("id");
                var replace_id = id.replace('discount', '');
                $('#showErrDiscount'+replace_id).html('');
                setDiscountAmtVal($(this));
            });
        });

        $(document).on("change", "#supplierCategory", function(e) {
            var categoryId = $(this).find(":selected").val();
            if (categoryId) {
                supplierCategoryChange(categoryId);
            }
        });

        $(document).on("change", "#supplierSubCategory", function(e) {
            var subCategoryId = $(this).find(":selected").val();
            if (subCategoryId) {
                supplierSubCategoryChange(subCategoryId);
            }
        });

        $(document).on("click", "#AddProductData", function(e) {
            e.preventDefault();
            $("#supplierProductForm").parsley().on('form:validate', function() {
                tinymce.triggerSave();
            });
            if ($("#supplierProductForm").parsley().validate()) {
                //@ekta 27-040-------start checking for order max qty is equal to max qty
                var checkMaxOrderQty = $("#supplierProductMaxQuantity").val();
                var lastMaxQty = 0 ;
                var lastMaxQtyID = '';
                var replace_id = '';
                var data = $('.cloanMaxQty');
                $.each(data, function(index, value) {
                    var isLastElement = index == data.length -1;
                    if (isLastElement) {
                        lastMaxQty = $(this).val();
                        lastMaxQtyID = $(this).attr('id');
                    }
                });
                replace_id = lastMaxQtyID.replace('max_qty', '');
                var lastDiscountVal = $("#discount" + replace_id).val();
                if(checkMaxOrderQty != lastMaxQty){
                    var err = '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">{{ __('admin.maximum_quantity_must_be_equal_to_maximum_order_quantity') }}</li></ul>'
                    // replace_id = lastMaxQtyID.replace('max_qty', '');
                    $('#showErr' + replace_id).html(err);
                    $("#"+lastMaxQtyID).val('');
                    return false;
                }
                if(lastDiscountVal==''){
                    var err = '<ul class="parsley-errors-list filled" id="parsley-id-31" aria-hidden="false"><li class="parsley-required">This value is required.</li></ul>'
                    $('#showErrDiscount' + replace_id).html(err);
                    return false;
                }
                //end checking--------------@ekta

                var formData = new FormData($("#supplierProductForm")[0]);
                formData.append("supplier_id", supplierId);
                if (editSupplierProductId) {
                    formData.append("editSupplierProductId", editSupplierProductId);
                }
                $("#AddProductData").prop('disabled', true);
                $.ajax({
                    url: "{{ route('supplier-product-check-ajax') }}",
                    data: formData,
                    type: "POST",
                    contentType: false,
                    processData: false,
                    success: function(successData) {
                        if (successData.supplierProductCount == 0) {
                            var formData = new FormData($("#supplierProductForm")[0]);
                            formData.append("supplier_id", supplierId);
                            if (editSupplierProductId) {
                                formData.append("editSupplierProductId",
                                    editSupplierProductId);
                                $.ajax({
                                    url: "{{ route('supplier-product-update-ajax') }}",
                                    data: formData,
                                    type: "POST",
                                    contentType: false,
                                    processData: false,
                                    success: function(successData) {
                                        if (successData.success) {
                                            resetToastPosition();
                                            $.toast({
                                                heading: "{{ __('admin.success') }}",
                                                text: "{{ __('admin.supplier_product_updated_successfully') }}",
                                                showHideTransition: "slide",
                                                icon: "success",
                                                loaderBg: "#f96868",
                                                position: "top-right",
                                            });
                                            var supplierProduct = successData
                                                .supplierProduct;
                                            $('#t_category_name_' +
                                                supplierProduct.id).text($(
                                                "#supplierProductForm #supplierCategory option:selected"
                                            ).text());

                                            $('#t_sub_category_name_' +
                                                supplierProduct.id).text($(
                                                "#supplierProductForm #supplierSubCategory option:selected"
                                            ).text());

                                            $('#t_product_name_' +
                                                supplierProduct.id).text($(
                                                "#supplierProductForm #supplierProduct option:selected"
                                            ).text());

                                            // $("#addProductModal").modal("hide");
                                        }
                                    },
                                    error: function() {
                                        console.log("error");
                                    },
                                });
                            } else {
                                formData.append('supplierProductDiscription', tinyMCE.get('description').getContent());
                                $.ajax({
                                    url: "{{ route('supplier-product-create-ajax') }}",
                                    data: formData,
                                    type: "POST",
                                    contentType: false,
                                    processData: false,
                                    success: function(successData) {
                                        if (successData.success) {
                                            resetToastPosition();
                                            $.toast({
                                                heading: "{{ __('admin.success') }}",
                                                text: "{{ __('admin.supplier_product_added_successfully') }}",
                                                showHideTransition: "slide",
                                                icon: "success",
                                                loaderBg: "#f96868",
                                                position: "top-right",
                                            });
                                            $("#supplierProductForm")[0].reset();
                                            setInterval(function() {
                                                window.location = ('/admin/products');
                                            }, 2000);

                                            var supplierProduct = successData.supplierProduct;
                                            var tableData =
                                                '<tr id="supplierProductDetailsRow_' +
                                                supplierProduct.id +
                                                '">';
                                            tableData +=
                                                '<td id="t_category_name_' +
                                                supplierProduct.id +
                                                '">' +
                                                $(
                                                    "#supplierProductForm #supplierCategory option:selected"
                                                ).text() +
                                                "</td>";
                                            tableData +=
                                                '<td id="t_sub_category_name_' +
                                                supplierProduct.id +
                                                '">' +
                                                $(
                                                    "#supplierProductForm #supplierSubCategory option:selected"
                                                ).text() +
                                                "</td>";
                                            tableData +=
                                                '<td id="t_product_name_' +
                                                supplierProduct.id +
                                                '">' + supplierProduct.product_name +
                                                "</td>";
                                            tableData +=
                                                '<td class="text-end text-nowrap" id="action_' +
                                                supplierProduct.id +
                                                '"> <a href="javascript:void(0)" data-id="' +
                                                supplierProduct.id +
                                                '" class="editSupplierProductDetails" ><i class="fa fa-edit"></i></a> <a href = "javascript:void(0)" data-id="' +
                                                supplierProduct.id +
                                                '" class = "ps-2 deleteSupplierProductDetails" > <i class="fa fa-trash"></i></a> </td>';
                                            tableData += "</tr>";

                                            $("#supplierProductTable")
                                                .DataTable()
                                                .row.add($(tableData))
                                                .draw();

                                            // $("#addProductModal").modal("hide");
                                        }
                                    },
                                    error: function() {
                                        console.log("error");
                                    },
                                });
                            }
                        } else {
                            $('#productAddedWarning').remove();
                            $('#warningDiv').prepend('<p class="text-danger" id="productAddedWarning">Product already added</p>');
                        }
                    },
                    error: function() {
                        console.log("error");
                    },
                });

            }
        });

        function supplierCategoryChange(categoryId,subCategoryId=null) {
            if(subCategoryId==null){
                $("#supplierProductForm #searchProductCategory").val('');
            }
            $('#supplierProductForm #productRef').val('');
            var selectProduct = "{{__('admin.select_product_name')}}";
            var selectSubcategory = "{{__('admin.select_sub_category')}}";
            var productOptions = '<option selected disabled>'+selectProduct+'</option>';
            $('#supplierProductForm #supplierProduct').empty().append(productOptions);
            $('#supplierProductForm #description').val('');
            $('#supplierProductForm #supplierProductBrand').val('');
            $('#supplierProductForm #supplierProductGrade').val('');
            if(subCategoryId!=null || subCategoryId!=0)
                {
                    var select='selected="selected"';
                }
            $.ajax({
                url: "{{ route('get-subcategory-ajax', '') }}" + "/" + categoryId,
                type: "GET",
                success: function(successData) {
                    var options = "<option selected disabled>"+selectSubcategory+"</option>";
                    if (successData.subCategory.length) {
                        successData.subCategory.forEach(function(data) {
                        if(data.id==subCategoryId){
                            var selected=select;
                        }
                        else{
                            selected='';
                        }
                            options += '<option value="' + data.id + '" data-text="' + data.name + '"'+selected+'>' + data.name + "</option>";
                        });
                    }

                    $("#supplierSubCategory").empty().append(options);
                },
                error: function() {
                    console.log("error");
                },
            });
        }

        function supplierSubCategoryChange(subCategoryId,productId) {
            $('#supplierProductForm #productRef').val('');
            var selectProduct = "{{__('admin.select_product_name')}}";
            var productOptions = '<option selected disabled>'+selectProduct+'</option>';
            $('#supplierProductForm #supplierProduct').empty().append(productOptions);
            $('#supplierProductForm #description').val('');
            $('#supplierProductForm #supplierProductBrand').val('');
            $('#supplierProductForm #supplierProductGrade').val('');
            if (subCategoryId) {
                $.ajax({
                    url: "{{ route('get-brand-grade-product-ajax', '') }}" + "/" + subCategoryId,
                    type: "GET",
                    success: function(successData) {
                        var brand = "";
                        var grade = "";
                        var product = "";
                        var productDiscription = "";
                        if (successData.products.length > 0) {
                            var productArray = [];
                            var productDescriptionArray = [];
                            product =
                                '<option selected disabled>'+selectProduct+'</option>';
                            successData.products.forEach(function(data) {
                                if(data.id==productId){
                                    var selected='selected="selected"';
                                }
                                else{
                                    selected='';
                                }
                                if (!productArray.includes(data.name)) {
                                    productArray.push(data.name);
                                    product += '<option value="' + data.id +
                                        '" data-text="' + data.name + '" '+selected+'>' + data
                                            .name + '</option>';
                                }
                                if (data.description) {
                                    if (!productDescriptionArray.includes(data
                                        .description)) {
                                        productDescriptionArray.push(data
                                            .description);
                                        productDiscription += '<option>' + data
                                            .description + '</option>';
                                    }
                                }
                            });
                            $("#supplierProduct").empty().append(product);
                            $("#supplierProductDiscriptionList").empty().append(
                                productDiscription);
                        } else {
                            $("#supplierProduct").empty().append(
                                '<option selected disabled>'+selectProduct+'</option>'
                            );
                        }
                    },
                    error: function() {
                        console.log("error");
                    },
                });
            }

        }

        String.prototype.beforeLastIndex = function(delimiter) {
            return this.split(delimiter).slice(0, -1).join(delimiter) || this + ""
        }

        function show(input) {
            var file = input.files[0];
            console.log(file);
            var size = Math.round((file.size / 1024))
            if (size > 3000) {
                swal({
                    icon: 'error',
                    title: '',
                    text: '{{__('admin.file_size_under_3mb ')}}',
                })
            } else {
                var fileName = file.name;
                var allowed_extensions = new Array("jpg", "png", "gif", "jpeg", "pdf");
                var file_extension = fileName.split('.').pop();
                var text = '';
                var supplier_id = '{{ $supplier->id }}';
                var image_path = '{{URL::asset("assets/icons/times-circle copy.png")}}';
                if (input.name == 'logo' || input.name == 'productImages') {
                    allowed_extensions = allowed_extensions.filter(function(value) {
                        return value != 'pdf'
                    });
                    text = '{{ __('admin.only_upload_image_file ') }}';
                } else {
                    text = '{{ __('admin.upload_image_or_pdf ') }}';
                }
                for (var i = 0; i < allowed_extensions.length; i++) {
                    if (allowed_extensions[i] == file_extension) {
                        valid = true;
                        var download_function = "'" + supplier_id + "', " + "'" + input.name + "', " + "'" + fileName + "'";
                        if (fileName.beforeLastIndex(".").length >= 10) {
                            fileName = fileName.substring(0, 10) + '....' + file_extension;
                        }
                        $('#file-' + input.name).html('');
                        $('#file-' + input.name).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.name + 'Download" style="text-decoration: none">' + fileName + '</a></span><span class="removeFile hide" id="' + input.name + 'File" data-id="' + supplier_id + '" data-name="' + input.name + '"><a href="#" title="Remove logo" style="text-decoration: none;"><img src="' + image_path + '" alt="CLose button" class="ms-2"></a></span><span class="ms-2"><a class="logoFile downloadbtn hide" href="javascript:void(0);" title="Download ' + input.name + '" onclick="downloadimg(' + download_function + ')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>');
                        return;
                    }
                }
                valid = false;
                swal({
                    // title: "Rfq Update",
                    text: text,
                    icon: "warning",
                    //buttons: true,
                    buttons: ["{{ __('admin.no') }}", "{{ __('admin.yes') }}"],
                    // dangerMode: true,
                })
            }
        }

        $('#description').on('change', function(e) {
            $('#description').parsley().reset();
        });

        var SnippetGetProductCategoryDetails = function(){

            var getProductList = function(){
            $(document).on('keyup','#searchProductCategory',function(){
                if ($(this).val().length >= 3) {
                    $.ajax({
                        url: "/search-product",
                        method:'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {'data' : $(this).val()},
                        success : function(response){

                            if (response.success) {
                                setResult(response.data);
                            } else {
                                resetResult()
                            }

                        },
                    });
                } else {
                    resetResult();
                }
                });

            },
            setResult = function(data){
                var searchResult = '';
                data.forEach(function (data) {
                    searchResult += '<li  data-value="'+ data.productName+ '"data-product-id="' + data.productId +'" data-category-id="' + data.categoryId + '" data-subcategory-id="' + data.subcategoryId + '" ' +
                        ' class="list-group-item list-item-pointer listProductCat">'+
                        data.productName+'</li>';
                });
                $('#searchGroup').html(searchResult);
                selectProductList();
            },

            resetResult = function(){
                $('#searchGroup').html('');
            },

            selectProductList = function(){
                $(document).on('mousedown', '.listProductCat', function (e) {
                var txt = $(this).parent().attr('id');
                var str =$(this).attr('data-value');
                const prodcutArray = str.split("-");
                    $("#supplierProductForm #searchProductCategory").val($("<b>").html($(this).attr('data-value')).text());

                    $('#supplierProductForm #supplierCategory option[value="'+$(this).attr('data-category-id')+'"]').attr('selected','selected');
                    supplierCategoryChange($(this).attr('data-category-id'),$(this).attr('data-subcategory-id'));
                    supplierSubCategoryChange($(this).attr('data-subcategory-id'),$(this).attr('data-product-id'));
                    resetResult();

                   // $("#supplierProductForm #searchProductCategory").val('');

                });

            };

            return {
                init: function () {
                    getProductList()
                // selectProductList()
                }
            }


            }(1);

            jQuery(document).ready(function(){
                SnippetGetProductCategoryDetails.init();
            });

    </script>
    <script>
        // CSRF Token
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function(){
            $( "#tags" ).bind("keyup.autocomplete", function(event) {
                if(event.keyCode == $.ui.keyCode.BACKSPACE || event.keyCode == $.ui.keyCode.DELETE) {
                    $(".count").text('0 Result Found');
                    event.stopPropagation();
                }
            });
            $( "#tags" ).autocomplete({
                minLength:3,
                source: function( request, response ) {
                    // Fetch data
                    $.ajax({
                        url: "/search-product",
                        method:'POST',
                        dataType: "json",
                        data: {
                            _token: CSRF_TOKEN,
                            data: escape(request.term)
                        },
                        success: function( data ) {
                            $('#supplierProductForm #supplierProduct').removeClass('blink');
                            $('#supplierProductForm #supplierCategory').removeClass('blink');
                            $("#supplierProductForm #supplierSubCategory").removeClass('blink');
                            $(".count").text(data.cnt+' Result Found');
                            $(".count").css("display", "block");
                            response( $.map( data.data, function( item ) {
                                return {
                                    label: item.productName,
                                    categoryName: item.categoryName,
                                    product_name:item.productTextName,
                                    productId:item.productId,
                                    categoryId:item.categoryId,
                                    subcategoryId:item.subcategoryId,
                                    subcategoryName:item.subcategoryName,
                                }
                            }));
                        },

                    });
                },
                select: function (event, ui) {
                    var productName = ui.item.label;
                    var product_name = ui.item.product_name;
                    var categoryName = ui.item.categoryName;
                    var categoryId = ui.item.categoryId;
                    var productId = ui.item.productId;
                    var subcategoryId = ui.item.subcategoryId;
                    var subcategoryName = ui.item.subcategoryName;
                    const prodcutArray = productName.split("-");
                    var product = JSON.parse(localStorage.getItem("product")) || [];
                    $("#supplierProductForm #tags").val('');
                    $('#supplierProductForm #supplierCategory option[value="'+categoryId+'"]').attr('selected','selected');
                    supplierCategoryChange(categoryId,subcategoryId);
                    supplierSubCategoryChange(subcategoryId,productId);
                    $('#supplierProductForm #supplierProduct').addClass('blink');
                    $('#supplierProductForm #supplierCategory').addClass('blink');
                    $("#supplierProductForm #supplierSubCategory").addClass('blink');
                    $(".count").text('0 Result Found');
                    return false;
                }
            })

        });
    </script>

@section('scripts')
    {{ view('admin.supplier.supplier_product_range_js') }}
@endsection

@stop
