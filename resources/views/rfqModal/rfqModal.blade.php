@extends('rfqModal/layout')
@section('rfqmodal')

    <style>
        .select2-hidden-accessible {
            width: 100% !important;
            height: 50px !important;
            position: relative !important;
            visibility: hidden;
        }

    </style>
    <div class="col-lg-10 py-2 quoteforms">
        <div class="header_top d-flex align-items-center pb-4">
            <h1 class="mb-0">{{ __('dashboard.Get a Quote') }}</h1>
            <a href="{{ route('home') }}" class="btn-close ms-auto"><img
                    src="{{ URL::asset('front-assets/images/icons/close.png') }}" alt=""></a>
        </div>
        <div class="card radius_1 mb-4">
            <div class="card-body floatlables ">
                <div class="row" data-bs-spy="scroll" data-bs-target="#list-example" data-bs-offset="0"
                    tabindex="0">

                    <div class="col-md-12 scrollspy-example">
                        <form data-parsley-validate autocomplete="off" name="quickrfq" id="quickrfq">
                            <div id="list-item-1" class="mb-4">

                                <div class="row g-4">
                                    @csrf
                                    <div class="col-md-12">
                                        <h5 class="mb-0 dark_blue">Contact Details</h5>
                                    </div>
                                    <div class="col-md-6 ">
                                        <label for="firstname">{{ __('dashboard.First_Name') }} *</label>
                                        <input type="text" class="form-control" name="firstname" id="firstname"
                                            required="required" value="{{ Auth::user() ? Auth::user()->firstname : '' }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="lastname">{{ __('dashboard.Last_Name') }} *</label>
                                        <input type="text" class="form-control" name="lastname" id="lastname"
                                            required="required" value="{{ Auth::user() ? Auth::user()->lastname : '' }}">
                                    </div>


                                    <div class="col-md-6">
                                        <input type="email" class="form-control" name="email" id="email"
                                            required="required" value="{{ Auth::user() ? Auth::user()->email : '' }}">
                                        <label for="email">{{ __('dashboard.Email') }} *</label>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="tel" class="form-control" name="mobilenumber" id="mob_number"
                                            required="required" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$"
                                            data-parsley-maxlength="20" data-parsley-minlength="10"
                                            placeholder="+XX XX XXXXXXX"
                                            value="{{ Auth::user() ? Auth::user()->mobile : '' }}">
                                        <label for="mob_number">{{ __('dashboard.Mobile_Number') }}
                                            *</label>
                                    </div>
                                </div>
                            </div>


                            <div class="mb-3">
                                <div class="row g-3">

                                    <div class="col-md-12">
                                        <h5 id="list-item-2" class="mb-0 dark_blue">Product Details</h5>
                                    </div>

                                    <div class="col-md-12" id="prod_cat_block">
                                        <label for="prod_cat">{{ __('dashboard.select_product_label') }} *</label>
                                        <select id='prod_cat' name="prod_cat" data-id=""
                                            class="form-control select2-hidden-accessible" required>
                                        </select>
                                    </div>

                                    <div class="col-md-12 d-none" id="productCategoryOtherDiv">
                                        <label>{{ __('dashboard.other_product_category') }} *</label>
                                        <input type="text" class="form-control" id="othercategory" name="othercategory">
                                    </div>
                                    <div class="col-12 d-none" id="prod_sub_cat_block">
                                        <label>Product Sub Category</label>
                                        <select class="form-control" id='prod_sub_cat' name="prod_sub_cat" required>
                                            <option selected disabled>Select Product Sub Category</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 d-none" id="productSubCategoryOtherDiv">
                                        <label>Other Product Sub-Category*</label>
                                        <input class="form-control" id="othersubcategory" type="text">
                                    </div>
                                    <div class="col-md-12">

                                        <input type="text" class="form-control" id="prod_name" name="prod_name"
                                            onkeyup="searchProduct(this.value)" required>
                                        <label>{{ __('dashboard.Product_Name') }} *</label>
                                    </div>
                                    <div class="col-md-12">
                                        {{-- <input type="text" class="form-control" id="prod_desc" name="prod_desc"
                                            onkeyup="searchProductDescription(this.value)" required> --}}
                                        <textarea class="form-control" rows="3"
                                            placeholder="{{ __('dashboard.Product_Description_Placeholder') }}"
                                            name="prod_desc" id="prod_desc" required></textarea>
                                        <label>{{ __('dashboard.Product_Description') }} *</label>

                                    </div>

                                    <div class="col-md-12">
                                        <textarea class="form-control" rows="3"
                                            placeholder="{{ __('dashboard.Comment_Placeholder') }}" name="comment"
                                            id="comment"></textarea>
                                        <label>{{ __('dashboard.Comment') }}</label>

                                    </div>

                                    <div class="col-md-4">

                                        <input type="number" class="form-control qty_input" id="quantity" name="quantity"
                                            min="1" placeholder="0" required="required" aria-invalid="true">
                                        <label for="quantity">{{ __('dashboard.Quantity') }} *</label>
                                        <!-- ngIf: searchForm.quantity.$touched||searchForm.$submitted -->
                                    </div>
                                    <div class="col-md-4">


                                        <select class="form-select mt--8" id="unit" name="unit" required>
                                            <option selected disabled>{{ __('dashboard.Select Unit') }}
                                            </option>
                                        </select>
                                        <label for="unit" class="form-label">{{ __('dashboard.Unit') }}
                                            *</label>
                                    </div>
                                </div>
                            </div>
                            <div id="fullrfq_step3">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <h5 id="list-item-3" class="mb-0 dark_blue">Delivery Details</h5>

                                    </div>


                                    <div class="col-md-6 col-lg-4">
                                        <input type="tel" class="form-control " id="delivery_pincode" name="pincode"
                                            maxlength="6" required="required" data-parsley-type="number">
                                        <label for="delivery_pincode">{{ __('dashboard.Delivery_Pincode') }}
                                            *</label>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <label class="form-label"
                                            for="expected_date">{{ __('dashboard.Expected_Delivery_Date') }}
                                            *</label>
                                        <input type="text" class="form-control calendericons" name="expected_date"
                                            id="expected_date" placeholder="dd-mm-yyyy" required>
                                    </div>
                                    <div class="col-md-12 d-flex">
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                name="need_rental_forklift" id="need_rental_forklift">
                                            <label class="form-check-label" for="need_rental_forklift">
                                                {{ __('dashboard.need_rental_forklift') }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                name="need_unloading_services" id="need_unloading_services">
                                            <label class="form-check-label" for="need_unloading_services">
                                                {{ __('dashboard.need_unloading_services') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end mt-3">
                                        <a class="btn btn-warning me-md-2 px-3 py-2 mb-1" href="javascript:void(0)"
                                            id="quickGoToFullFormBtn"><img
                                                src=" {{ URL::asset('front-assets/images/icons/icon_fillinmore.png') }}"
                                                alt="Fill In More Details"
                                                class="pe-1">{{ __('dashboard.Fill_in_more_details') }}</a>

                                        <button type="submit" class="btn btn-primary px-3 py-2 mb-1"
                                            id="submitQuickRfq"><img
                                                src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}"
                                                alt="Post Requirement"
                                                class="pe-1">{{ __('dashboard.post_requirement') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>



    <script>
        // function searchCategory(text) {
        //     $('#quickrfq #prod_name').val(null);
        //     $('#quickrfq #prod_name').attr('data-id', '');
        //     $('#quickrfq #prod_desc').val(null);
        //     $('#quickrfq #prod_desc').attr('data-id', '');
        //     $('#quickrfq #productSearchResult').remove();
        //     $('#quickrfq #prod_cat').attr('data-category', '');
        //     $('#quickrfq #prod_cat').attr('data-subcategory', '');
        //     $('#quickrfq #prod_cat').attr('data-id', '');
        //     $('#quickrfq #prod_cat').attr('data-category-id', '');
        //     if (text) {
        //         $.ajax({
        //             url: "{{ route('search-category-ajax', '') }}" + "/" + text,
        //             type: 'GET',
        //             success: function(successData) {
        //                 $('#quickrfq #categorySearchResult').remove();
        //                 var searchData = '<ul id="categorySearchResult" class="searchResult">';
        //                 if (successData.filterData.length) {
        //                     successData.filterData.forEach(function(data) {
        //                         searchData += '<li data-category-id="' + data.categories_id +
        //                             '" data-id="' + data.sub_categories_id + '" data-value="' +
        //                             data.name + '" class="searchCategory" data-category="' + data
        //                             .category_name + '" data-subcategory="' + data.sub_categories_name +
        //                             '">' +
        //                             data.name + '</li>';
        //                     });
        //                 }
        //                 searchData += '</ul>'
        //                 $('#quickrfq #prod_cat').after(searchData);
        //             },
        //             error: function() {
        //                 console.log('error');
        //             }
        //         });
        //     }
        // }

        function searchProduct(text) {
            var text = text.trim();
            var subCategoryId = $('#prod_sub_cat option:selected').attr('data-sub-category-id');
            var categoryId = $("#quickrfq #prod_cat option:selected").attr('data-category-id');
            var data = {
                product: text,
                subCategoryId: subCategoryId,
                categoryId: categoryId,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
            if (categoryId && categoryId != 0) {
                $.ajax({
                    url: '{{ route('search-product-ajax') }}',
                    data: data,
                    type: 'POST',
                    success: function(successData) {
                        $('#quickrfq #productSearchResult').remove();
                        var searchData = '<ul id="productSearchResult" class="searchResult">';
                        // $('#quickrfq #prod_sub_cat').val(0).trigger('change');
                        var dataArray = [];
                        if (successData.filterData.length) {
                            successData.filterData.forEach(function(data) {
                                if (!dataArray.includes(data.name)) {
                                    dataArray.push(data.name);
                                    searchData += '<li data-sub-cat-id="' + data.subcategory_id +
                                        '" data-id="' + data.id + '" data-value="' +
                                        data.name + '" class="searchProduct">' +
                                        data.name + '</li>';
                                }
                            });
                        }
                        searchData += '</ul>'
                        $('#quickrfq #prod_name').after(searchData);
                        var dbProduct = [];
                        $('#quickrfq #productSearchResult li').each(function() {
                            dbProduct.push($(this).text().toLowerCase());
                        });
                        if (dbProduct.includes(text.toLowerCase())) {
                            var dataSubCatId = $('#quickrfq #productSearchResult').find('li[data-value="' +
                                text.trim() +
                                '"]').attr('data-sub-cat-id');
                            if (dataSubCatId) {
                                var selectedSubCatValue = $('#quickrfq #prod_sub_cat').find(
                                        'option[data-sub-category-id="' + dataSubCatId + '"]')
                                    .val();
                                $('#quickrfq #prod_sub_cat').val(selectedSubCatValue).trigger('change');
                            } else {
                                $('#quickrfq #prod_sub_cat').val(0).trigger('change');
                            }
                        } else {
                            $('#quickrfq #prod_sub_cat').val(0).trigger('change');
                        }
                    },
                });
            }
        }

        function searchProductDescription(text) {
            var product = $('#quickrfq #prod_name').val();
            var data = {
                productDescription: text,
                product: product,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
            if (product.trim().length) {
                $.ajax({
                    url: "{{ route('search-product-description-ajax') }}",
                    data: data,
                    type: 'POST',
                    success: function(successData) {
                        $('#quickrfq #productDescriptionSearchResult').remove();
                        var dataArray = [];
                        var searchData =
                            '<ul id="productDescriptionSearchResult" class="descriptionSearchResult">';
                        if (successData.filterData.length) {
                            successData.filterData.forEach(function(data) {
                                if (data.description) {
                                    if (!dataArray.includes(data.description)) {
                                        dataArray.push(data.description);
                                        searchData += '<li data-id="' + data.id + '" data-value="' +
                                            data.description + '" class="searchProductDescription">' +
                                            data.description + '</li>';
                                    }
                                }
                            });
                        }
                        searchData += '</ul>'
                        $('#quickrfq #prod_desc').after(searchData);
                    },
                });
            }
        }


        function getUnit() {
            //var categoryId = $('#quickrfq #prod_cat option:selected').attr('data-category-id');
            var data = {
                _token: $('meta[name="csrf-token"]').attr('content')
            }
            $.ajax({
                url: '{{ route('get-unit-ajax') }}',
                data: data,
                type: 'POST',
                success: function(successData) {
                    var unitoptions =
                        "<option selected disabled>{{ __('dashboard.Select Unit') }}</option>";
                    if (successData.unit.length) {
                        successData.unit.forEach(function(data) {
                            unitoptions += '<option value="' + data.id + '">' +
                                data
                                .name + '</option>';
                        });
                    }
                    $('#quickrfq #unit').empty().append(unitoptions);
                },
            });

        }

        function getCategoryWithSubcat() {
            $.ajax({
                url: '{{ route('get-category-with-subcat-ajax') }}',
                type: 'GET',
                success: function(successData) {
                    var categoryWithSubCatOption =
                        "<option selected disabled>{{ __('dashboard.select_product_category') }}</option>";
                    if (successData.categoryWithSubCat.length) {
                        successData.categoryWithSubCat.forEach(function(data) {
                            categoryWithSubCatOption += '<option data-category-id="' + data
                                .id + '" data-category = "' + data.name + '" value="' + data
                                .name + '" > ' + data
                                .name + '</option>';
                        });
                    }
                    categoryWithSubCatOption += '<option data-category-id="0" value="0">Other</option>';
                    $('#quickrfq #prod_cat').empty().append(categoryWithSubCatOption);
                    // $("#quickrfq #prod_cat").select2("destroy");
                    //$("#quickrfq #prod_cat").select2();

                    $('#quickrfq #prod_cat').select2({
                        selectOnClose: false,
                        dropdownParent: $('#prod_cat_block'),
                    });
                },
            });
        }


        $(document).ready(function() {
            $('#quickrfq #prod_sub_cat').select2({
                selectOnClose: false,
                dropdownParent: $('#prod_sub_cat_block'),
            });
            $('#expected_date').datepicker({
                dateFormat: "dd-mm-yy",
                minDate: "+1d"
                // appendText: "dd-mm-yyyy"
            });

            // $('#quickrfq #prod_cat').select2({
            //     dropdownParent: $('#getaQuoteModal')
            // });
            getCategoryWithSubcat();
            getUnit();

            //localStorage.clear();
            $(document).on('click', '#quickGoToFullFormBtn', function(e) {
                e.stopImmediatePropagation();
                e.preventDefault();
                //localStorage.clear();
                localStorage.setItem('category', $('#quickrfq #prod_cat option:selected').attr(
                    'data-category-id'));
                localStorage.setItem('otherCategory', $('#quickrfq #othercategory').val());
                localStorage.setItem('sub_category', $('#quickrfq #prod_sub_cat option:selected').attr(
                    'data-sub-category-id'));
                localStorage.setItem('othersubcategory', $('#quickrfq #othersubcategory').val());
                localStorage.setItem('product', $('#quickrfq #prod_name').val());
                localStorage.setItem('product_description', $('#quickrfq #prod_desc').val());
                localStorage.setItem('quantity_post', $('#quickrfq #quantity').val());
                localStorage.setItem('unit_post', $('#quickrfq #unit').val());
                localStorage.setItem('delivery_pincode_post', $('#quickrfq #delivery_pincode').val());
                localStorage.setItem('firstname', $('#quickrfq #firstname').val());
                localStorage.setItem('lastname', $('#quickrfq #lastname').val());
                localStorage.setItem('email', $('#quickrfq #email').val());
                localStorage.setItem('mob_number', $('#quickrfq #mob_number').val());
                localStorage.setItem('comment', $('#quickrfq #comment').val());
                localStorage.setItem('expected_date', $('#quickrfq #expected_date').val());

                var url = "{{ route('get-a-quote') }}";
                window.location.replace(url);
            });


            $(document).on('submit', '#quickrfq', function(e) {
                e.stopImmediatePropagation();
                e.preventDefault();
                var categorySelected = $("#quickrfq #prod_cat").find(':selected')
                    .attr('data-category-id');
                var subCategorySelected = $("#quickrfq #prod_sub_cat").find(
                    ':selected').attr('data-sub-category-id');

                $('#productCategoryOtherDiv .error').remove()
                $('#productSubCategoryOtherDiv .error').remove();
                var formValidate = true;
                if (categorySelected == 0) {
                    var othercategory = $('#quickrfq #othercategory').val();
                    if (othercategory.trim().length == 0) {
                        $('#quickrfq #othercategory').after(
                            '<span class="error">This value is required.</span>');
                        formValidate = false;
                    } else {
                        $('#quickrfq #productCategoryOtherDiv .error').remove();
                    }
                }


                // if (subCategorySelected == 0) {
                //     var othersubcategory = $('#quickrfq  #othersubcategory').val();
                //     if (othersubcategory.trim().length == 0) {
                //         $('#quickrfq #othersubcategory').after(
                //             '<span class="error">This value is required.</span>');
                //         formValidate = false;
                //     } else {
                //         $('#quickrfq #productSubCategoryOtherDiv .error').remove();
                //     }
                // }

                if ($('#quickrfq').parsley().validate() && formValidate) {

                    var formValidate = true;
                    var need_rental_forklift = 0;
                    var need_unloading_services = 0;
                    if ($('#quickrfq #need_rental_forklift').prop("checked")) {
                        need_rental_forklift = 1;
                    }
                    if ($('#quickrfq #need_unloading_services').prop("checked")) {
                        need_unloading_services = 1;
                    }

                    $('#submitQuickRfq').attr('disabled', true);
                    var formData = new FormData($('#quickrfq')[0]);
                    if ($("#quickrfq #prod_cat").find(':selected').attr('data-category-id') == 0) {
                        formData.append("category", $("#quickrfq #othercategory").val());
                    } else {
                        formData.append('category', $('#quickrfq #prod_cat option:selected').attr(
                            'data-category'))
                    }

                    if ($("#quickrfq #prod_sub_cat").find(':selected').attr(
                            'data-sub-category-id') == 0) {
                        if ($("#quickrfq #othersubcategory").val() != undefined && $(
                                "#quickrfq #othersubcategory").val().length > 0) {
                            formData.append("subcategory", $("#quickrfq #othersubcategory")
                                .val());
                        } else {
                            formData.append("subcategory", 'Other');
                        }
                    } else {
                        formData.append('subcategory', $('#quickrfq #prod_sub_cat option:selected').val());
                    }

                    formData.append('rental_forklift', need_rental_forklift)
                    formData.append('unloading_services', need_unloading_services)

                    $.ajax({
                        url: '{{ route('quick-rfq-ajax') }}',
                        data: formData,
                        type: 'POST',
                        contentType: false,
                        processData: false,
                        success: function(successData) {
                            new PNotify({
                                text: "{{ __('dashboard.RFQ_Sent_successfully') }}",
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                animateSpeed: 'fast',
                                delay: 1000
                            });
                            $('#quickrfq').parsley().reset();
                            $('#quickrfq')[0].reset();
                            var url = "{{ route('dashboard') }}";
                            $('#submitQuickRfq').attr('disabled', false);
                            window.location.replace(url);
                        },
                        error: function() {
                            console.log('error');
                        }
                    });
                }
            });

            // $(document).on('click', '#prod_cat', function(e) {
            //     $('#quickrfq #categorySearchResult').removeClass('hidden');
            // });
            $(document).on('change', '#prod_cat', function(e) {
                $('#quickrfq #prod_name').val(null);
                $('#quickrfq #prod_name').attr('data-id', '');
                $('#quickrfq #productSearchResult').remove();
                var categoryId = $(this).find(':selected').attr('data-category-id');
                if (categoryId == 0) {
                    $('#quickrfq #productCategoryOtherDiv').removeClass('d-none');
                    $('#quickrfq #othercategory').val('');
                    var options = '<option selected disabled>Select Product Sub Category</option>';
                    options += '<option  data-sub-category-id="0" value="0">Other</option>';
                    $('#quickrfq #prod_sub_cat').empty().append(options);
                    //$('#quickrfq #prod_sub_cat_block').addClass('d-none');
                    //$('#quickrfq #productSubCategoryOtherDiv').removeClass('d-none');
                    $("#quickrfq #prod_sub_cat").val(0);
                } else {
                    $('#quickrfq  #productCategoryOtherDiv').addClass('d-none');
                    //$('#quickrfq  #productSubCategoryOtherDiv').addClass('d-none');
                    //$('#quickrfq #prod_sub_cat_block').removeClass('d-none');
                }
                if (categoryId && categoryId != 0) {
                    $.ajax({
                        url: "{{ route('get-subcategory-ajax', '') }}" + "/" + categoryId,
                        type: 'GET',
                        success: function(successData) {
                            var options =
                                '<option selected disabled>Select Product Sub Category</option>';
                            if (successData.subCategory.length) {
                                successData.subCategory.forEach(function(data) {
                                    options += '<option data-sub-category-id="' + data
                                        .id + '" value="' + data.name +
                                        '" data-text="' + data.name + '">' + data
                                        .name + '</option>';
                                });
                            }
                            options +=
                                '<option data-sub-category-id="0"  value="0">Other</option>';

                            // var unitoptions =
                            //     '<option selected disabled>*Unit</option>';
                            // if (successData.unit.length) {
                            //     successData.unit.forEach(function(data) {
                            //         unitoptions += '<option value="' + data.id + '">' +
                            //             data
                            //             .name + '</option>';
                            //     });
                            // }
                            $('#quickrfq #prod_sub_cat').empty().append(options);
                            //$('#quickrfq #unit').empty().append(unitoptions);
                        },
                        error: function() {
                            console.log('error');
                        }
                    });
                } else {
                    //getUnit();
                }
            });

            // $(document).on('change', '#prod_sub_cat', function(e) {
            //     $('#quickrfq #othersubcategory').val('');
            //     $('#quickrfq #prod_name').val('');
            //     $('#quickrfq #prod_desc').val('');
            //     $('#quickrfq #productSearchResult').remove();
            //     $('#quickrfq #productDescriptionSearchResult').remove();
            //     var subCategoryId = $(this).find(':selected').attr('data-sub-category-id');
            //     if (subCategoryId == 0) {
            //         $('#productSubCategoryOtherDiv').removeClass('d-none');
            //     } else {
            //         $('#productSubCategoryOtherDiv').addClass('d-none');
            //     }
            // });

            // $(document).on('click', '.searchCategory', function(e) {
            //     $('#quickrfq #prod_cat').val($(this).attr('data-value'));
            //     $('#quickrfq #prod_cat').attr('data-id', $(this).attr('data-id'));
            //     $('#quickrfq #prod_cat').attr('data-category', $(this).attr('data-category'));
            //     $('#quickrfq #prod_cat').attr('data-subcategory', $(this).attr('data-subcategory'));
            //     $('#quickrfq #prod_cat').attr('data-category-id', $(this).attr('data-category-id'));
            //     $('#quickrfq #categorySearchResult').addClass('hidden');
            //     $('#quickrfq #prod_name').val(null);
            //     $('#quickrfq #prod_name').attr('data-id', '');
            //     $('#quickrfq #prod_desc').val(null);
            //     $('#quickrfq #prod_desc').attr('data-id', '');
            //     getUnit();
            // });


            $(document).on('click', '#prod_name', function(e) {
                $('#quickrfq #productSearchResult').removeClass('hidden');
            });

            $(document).on('mousedown', '.searchProduct', function(e) {
                $('#quickrfq #prod_name').val($(this).attr('data-value'));
                $('#quickrfq #prod_name').attr('data-id', $(this).attr('data-id'));
                $('#quickrfq #productSearchResult').addClass('hidden');
                if ($(this).attr('data-sub-cat-id')) {
                    var selectedSubCatValue = $('#quickrfq #prod_sub_cat').find(
                        'option[data-sub-category-id="' + $(this).attr('data-sub-cat-id') + '"]').val();
                    $('#quickrfq #prod_sub_cat').val(selectedSubCatValue).trigger('change');
                    $('#quickrfq #othersubcategory').val('');
                }
            });

            $(document).on('focusout', '#prod_name', function(e) {
                $('#quickrfq #productSearchResult').addClass('hidden');
            });

            $(document).on('click', '#prod_desc', function(e) {
                $('#quickrfq #productDescriptionSearchResult').removeClass('hidden');
            });

            $(document).on('mousedown', '.searchProductDescription', function(e) {
                $('#quickrfq #prod_desc').val($(this).attr('data-value'));
                $('#quickrfq #prod_desc').attr('data-id', $(this).attr('data-id'));
                $('#quickrfq #productDescriptionSearchResult').addClass('hidden');
            });

            $(document).on('focusout', '#prod_desc', function(e) {
                $('#quickrfq #productDescriptionSearchResult').addClass('hidden');
            });
        });
    </script>
@stop
