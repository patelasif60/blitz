@extends('admin/adminLayout')

<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="row">
            <div class="col-md-12 d-flex align-items-center mb-3">
                <h1 class="mb-0 h3">{{__('admin.products')}}</h1>
                <a href="{{ route('products-list') }}" class="mb-2 backurl ms-auto btn-close"></a>
            </div>

            <div class="col-12">
                <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">{{__('admin.edit_product')}}
                        </button>
                    </li>

                </ul>
                <div class="tab-content pt-3 pb-1" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <form class="" method="POST" enctype="multipart/form-data" action="{{ (auth()->user()->role_id == 1 || Auth::user()->hasRole('agent')) ? route('product-update') : route('update-supplier-product-data') }}" data-parsley-validate id="productedit">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center">
                                            <h5 class="mb-0">
                                                <img src="{{URL::asset('assets/icons/shopping-cart.png')}}" alt="Order Details" class="pe-2">
                                                <span>{{__('admin.product_details')}}</span>
                                            </h5>
                                        </div>
                                        <div class="card-body p-3 pb-1">

                                            <input type="hidden" name="id" value="{{ $product[0]->id }}">
                                            <div class="row">
                                                <div class="col-md-6  mb-3">
                                                    <label for="name" class="form-label">{{__('admin.product')}}<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="name" name="name" required value="{{ $product[0]->name }}" {{auth()->user()->role_id == 3 ? 'readonly' : '' }}>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="units" class="form-label">{{__('admin.brands')}}</label>
                                                    <select class="js-example-basic-multiple w-100" name="brands[]" id="units" multiple="multiple" {{auth()->user()->role_id == 3 ? 'disabled' : '' }}>
                                                        <option disabled>Select Brands</option>
                                                        @foreach ($brands as $brand)
                                                        <option {{ in_array($brand->id, $productBrands) ? 'selected="selected"' : '' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label for="description" class="form-label">{{__('admin.description')}}</label>
                                                    <textarea rows="5" class="form-control newtextarea" id="description" name="description">{{ $product[0]->description }}</textarea>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="category" class="form-label">{{__('admin.category')}}<span class="text-danger">*</span></label>
                                                    <select name="category" id="category" class="form-select selectBox"  required {{auth()->user()->role_id == 3 ? 'disabled' : '' }}>
                                                        <option disabled selected>Select Category</option>
                                                        @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" {{ $category->id == $product[0]->category_id ? 'selected="selected"' : '' }}>{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="fa fa-chevron-down d-none"></i>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="subCategory" class="form-label">{{__('admin.sub_category')}}<span class="text-danger">*</span></label>
                                                    <select name="subCategory" id="subCategory" class="form-select selectBox"  required {{auth()->user()->role_id == 3 ? 'disabled' : '' }}>
                                                        <option disabled>Select Sub Category</option>
                                                        @foreach ($subCategories as $subCategory)
                                                        <option value="{{ $subCategory->id }}" {{ $subCategory->id == $product[0]->subcategory_id ? 'selected="selected"' : '' }}>
                                                            {{ $subCategory->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <i class="fa fa-chevron-down d-none"></i>
                                                </div>

                                                @if(auth()->user()->role_id == 3)
                                                    <input type="hidden" name="product_id" id="product_id" value="{{ $product[0]->product_id }}" />
                                                    <input type="hidden" name="supplier_id" id="supplier_id" value="{{ $product[0]->supplier_id }}" />
                                                    <input type="hidden" name="productRef" id="productRef" value="{{ $product[0]->product_ref }}" />

                                                    <div class="col-md-6 mb-3">
                                                        <label for="supplierProductMinQuantity" class="form-label">{{ __('admin.minimum_quantity') }}<span class="text-danger">*</span></label>
                                                        <input type="number" name="supplierProductMinQuantity" id="supplierProductMinQuantity" value="{{$product[0]->min_quantity}}" class="form-control" data-parsley-type="number" data-parsley-minlength="1" min="1" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="supplierProductUnit" class="form-label">{{ __('admin.select_unit') }}<span class="text-danger">*</span></label>
                                                        <div class="position-relative">
                                                            <select name="supplierProductUnit" id="supplierProductUnit" class="form-control selectBox" required>
                                                                <option selected disabled>*Select A Unit</option>
                                                                @foreach ($unit as $unitItem)
                                                                <option value="{{ $unitItem->id }}" {{ $unitItem->id == $product[0]->quantity_unit_id ? 'selected="selected"' : '' }}>{{ $unitItem->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="fa fa-chevron-down downArrowIcon"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="supplierProductPrice" class="form-label">{{ __('admin.price') }}<span class="text-danger">*</span></label>
                                                        <input type="text" name="supplierProductPrice" id="supplierProductPrice" value="{{$product[0]->price}}" class="form-control" data-parsley-type="number" required onkeyup="calculateDiscountedPrice()" onchange="calculateDiscountedPrice()">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="discount" class="form-label">{{ __('admin.discount') }}(%)<span class="text-danger">*</span></label>
                                                        <input type="text" name="discount" id="discount" class="form-control" value="{{$product[0]->discount}}" data-parsley-type="number" required onkeyup="calculateDiscountedPrice()" onchange="calculateDiscountedPrice()">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="discounted_price" class="form-label">{{ __('admin.discounted_price') }}<span class="text-danger">*</span></label>
                                                        <input type="text" name="discounted_price" id="discounted_price" value="{{$product[0]->discounted_price}}" class="form-control" data-parsley-type="number" required readonly>
                                                    </div>
                                                @endif
                                                <div class="col-md-12 mb-3">
                                                    <label for="status" class="form-label">{{__('admin.status')}}</label>
                                                    <div>
                                                        <input type="radio" value="1" name="status" {{ $product[0]->status == 1 ? 'checked' : '' }}> {{__('admin.active')}}
                                                        <input type="radio" value="0" name="status" {{ $product[0]->status == 0 ? 'checked' : '' }}> {{__('admin.deactive')}}
                                                    </div>
                                                </div>
                                                {{-- <div class="col-12">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>--}}
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                @if(auth()->user()->role_id == 1 || Auth::user()->hasRole('agent'))

                                <div class="col-md-12 pb-2">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center">
                                            <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/people-carry-1.png')}}" alt="Supplier Details" class="pe-2"> <span>{{__('admin.supplier_detail')}}</span></h5>
                                        </div>
                                        <div class="card-body p-3 pb-1">
                                            <div class="row">
                                                <div class="col-md-6 mb-3 position-relative">
                                                    <label class="form-label">{{__('admin.suppliers')}}</label>
                                                    <select id="all_suppliers" name="suppliers[]" class="form-control" multiple="multiple">
                                                        @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}" {{ in_array($supplier->id, $supplier_product) ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">{{__('admin.update')}}</button>
                                    <a href="{{ route('products-list') }}" class="btn btn-cancel ms-3">
                                        {{__('admin.cancel')}}
                                    </a>
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
    $(document).ready(function() {
        //    $("#suppliers").CreateMultiCheckBox({ width: '100%', defaultText : 'Select Suppliers', height:'250px' });

        // 	$('.MultiCheckBox').click(function() {
        // 		var selected = $("#suppliers :selected").map((_,e) => e.value).get();
        // 		alert(selected);
        // 	});
        // 	var selected = $("#suppliers :selected").map((_, e) => e.value).get();
        // alert(selected);
        //	var optAry = [];
        // $( "#suppliers option:selected" ).each(function() {
        // 	s = this;
        // //	optAry.push(this.value);

        // 	 $('.mulinput').each(function (index, obj) {
        // 	 	if (s.value === this.value) {
        // 	 		$(this).prop('checked', true);
        // 	 	}
        // 	 });
        // });
        // console.log(optAry);
        @if(auth()->user()->role_id == 1)
        var all_suppliers = $('#all_suppliers').filterMultiSelect({
            selectAllText: 'All',
            placeholderText: "{{ __('admin.select_supplier')}}",
            filterText: 'search',
            caseSensitive: false,
        });
        @endif
    });

    $(document).on("change", "#category", function(e) {
        var categoryId = $(this).find(":selected").val();
        if (categoryId) {
            $.ajax({
                url: "{{ route('get-subcategory-ajax', '') }}" +
                    "/" +
                    categoryId,
                type: "GET",
                success: function(successData) {
                    var options =
                        "<option selected disabled>{{ __('admin.select_sub_category')}}</option>";
                    if (successData.subCategory.length) {
                        successData.subCategory.forEach(function(data) {
                            options +=
                                '<option value="' +
                                data.id +
                                '" data-text="' +
                                data.name +
                                '">' +
                                data.name +
                                "</option>";
                        });
                    }
                    $("#subCategory").empty().append(options);
                },
                error: function() {
                    console.log("error");
                },
            });
        }
    });

    function calculateDiscountedPrice() {
        var supplierProductPrice = $('#productedit #supplierProductPrice').val();
        var discount = $('#productedit #discount').val();
        if (supplierProductPrice) {
            discount = discount.length ? discount : 0;
            var price = supplierProductPrice - ((supplierProductPrice * discount) / 100);
            $('#productedit #discounted_price').val(price);
        }
    }
</script>
@stop

@section('scripts')
<script>
    $("#productedit").on('submit', function(event) {
        event.preventDefault();
        var formData = new FormData($("#productedit")[0]);
        formData.append('description', tinyMCE.get('description').getContent());
        if ($('#productedit').parsley().validate()) {
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: formData,
                contentType: false,
                processData: false,

                success: function(r) {
                    if (r.success == true) {
                        resetToastPosition();
                        $.toast({
                            heading: "{{__('admin.success')}}",
                            text: "{{ __('admin.products_updated_alert')}}",
                            showHideTransition: "slide",
                            icon: "success",
                            loaderBg: "#f96868",
                            position: "top-right",
                        });
                        setTimeout(function() {
                            window.top.location = $(".backurl").attr('href')
                        }, 3000);
                    } else {
                        resetToastPosition();
                        $.toast({
                            heading: "{{__('admin.success')}}",
                            text: "{{__('admin.product_exist')}}",
                            showHideTransition: "slide",
                            icon: "error",
                            loaderBg: "#f96868",
                            position: "top-right",
                        });
                        setTimeout(function() {
                            window.top.location = $(".backurl").attr('href')
                        }, 3000);
                    }
                },
                error: function(xhr) {
                    alert('{{__('admin.error_while_selecting_list')}}');
                }
            });

        }
    });
</script>
@stop
