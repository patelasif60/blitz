@extends('admin/adminLayout')

<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
@section('content')

<div class="row">
    <div class="col-12 grid-margin ">
        <div class="row">
            <div class="col-md-12 d-flex align-items-center mb-3">
                <h1 class="mb-0 h3">{{__('admin.products')}}</h1>
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
                    <form class="mb-0" method="POST" enctype="multipart/form-data" action="{{ route('product-create') }}"
                    data-parsley-validate id="productadd">
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
                                            <div class="row">
                                                <div class="col-md-6  mb-3">
                                                <label for="name" class="form-label">{{__('admin.product')}}<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="name" name="name" required>
                                                </div>
                                                <div class="col-md-6">
                                                <label for="units" class="form-label">{{__('admin.brands')}}</label>
                                                <select class="js-example-basic-multiple w-100" name="brands[]" id="units"  multiple="multiple">
                                                    <option disabled>Select Brands</option>
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                    @endforeach
                                                </select>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label for="description" class="form-label">{{__('admin.description')}}</label>
                                                    <textarea rows="5" class="form-control newtextarea" id="description" name="description"></textarea>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="category" class="form-label">{{__('admin.category')}}<span class="text-danger">*</span></label>
                                                    <select name="category" id="category" class="form-select selectBox" required>
                                                        <option disabled selected>{{__('admin.select_category') }}</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="fa fa-chevron-down d-none"></i>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="subCategory" class="form-label">{{__('admin.sub_category')}}<span class="text-danger">*</span></label>
                                                    <select name="subCategory" id="subCategory" class="form-select selectBox" required>
                                                        <option disabled selected>{{__('admin.select_sub_category')}}</option>
                                                    </select>
                                                    <i class="fa fa-chevron-down d-none"></i>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                <label for="status" class="form-label">{{__('admin.status')}}</label>
                                                <div>
                                                    <input type="radio" value="1" name="status" checked> {{__('admin.active')}}
                                                    <input type="radio" value="0" name="status"> {{__('admin.deactive')}}
                                                    </div>
                                                </div>

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
                                                    <select multiple name="suppliers[]" id="all_suppliers">
                                                            @foreach ($suppliers as $supplier)
                                                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                                @else
                                <input type="hidden" name="suppliers[]" value="{{ $suppliers }} ">
                                @endif

                                <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary productadd">{{__('admin.add')}}</button>
                                    <a href="{{ route('products-list') }}" class="btn btn-cancel ms-3">
                                        {{__('admin.cancel')}}
                                    </a>
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
        $(document).ready(function () {
            var all_suppliers = $('#all_suppliers').filterMultiSelect({
                selectAllText: 'All',
                placeholderText: 'Select Suppliers',
                filterText: 'search',
                caseSensitive: false,
            });
        });

		$(document).on("change", "#category", function (e) {
        var categoryId = $(this).find(":selected").val();
        if (categoryId) {
            console.log(categoryId);
            $.ajax({
                url:
                    "{{ route('get-subcategory-ajax', '') }}" +
                    "/" +
                    categoryId,
                type: "GET",
                success: function (successData) {
                    console.log('sucess');
                    var options =
                        "<option selected disabled>{{ __('admin.select_sub_category')}}</option>";
                    if (successData.subCategory.length) {
                        successData.subCategory.forEach(function (data) {
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
                error: function () {
                    console.log("error");
                },
            });
        }
    });
    </script>
@stop

@section('scripts')
<script>
    $("#productadd").on('submit',function(event){
        event.preventDefault();
        $(".productadd").prop('disabled', true); // disable button
        var formData = new FormData($("#productadd")[0]);
        formData.append('description', tinyMCE.get('description').getContent());
        if ($('#productadd').parsley().validate()) {
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data : formData,
                contentType: false,
                processData: false,
                beforeSend: function() { 
                  $(".productadd").prop('disabled', true); // disable button
                },
                success: function (r) {
                    if(r.success == true) {
                        resetToastPosition();
                        $.toast({
                            heading: "{{__('admin.success')}}",
                            text: "{{ __('admin.products_added_alert')}}",
                            showHideTransition: "slide",
                            icon: "success",
                            loaderBg: "#f96868",
                            position: "top-right",
                        });
                        setTimeout(function(){window.top.location=$(".backurl").attr('href')} , 3000);
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
                error: function (xhr) {
                    alert('{{__('admin.error_while_selecting_list')}}');
                }
            });

        }
    });
</script>
@stop
