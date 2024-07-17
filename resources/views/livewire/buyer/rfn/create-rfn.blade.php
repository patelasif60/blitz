<div wire:key="rfn-create-section">
    <style>
        .ui-autocomplete {
            max-height: 100px;
            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
        }
        .blink {
            animation-iteration-count: 1;
        }

    </style>
    <form class="row g-4 floatlables"  autocomplete="off" name="rfnForm" id="rfnForm" wire:submit.prevent="storeRfn" type="POST">
        @csrf
        <div class="col-md-12 mb-0 d-flex align-items-center mt-2">
            <h5 class="ps-0 mt-0 text-primary mb-0" style="font-family: 'europaNuova_re';font-size: 15px;">{{ __('rfqs.Product_Details') }}</h5>
            <div class="search_rfq mx-2 position-relative">
                <label class="form-label" for="Search_category" style="left: 10px;">{{__('admin.search_product')}}</label>
                <input type="text" name="productSearch" wire:model="productSearch" id="productSearch" class="form-control categorysearch" placeholder="{{__('admin.product_search')}}" />
            </div>
            <small class="ms-1 count" style="display: none"></small>
        </div>
        <div class="col-md-12" id="product_category_block">
            <label class="form-label" for="product_category">{{ __('dashboard.select_product_label') }}<span class="text-danger">*</span></label>
            <select id='buyerCategory' name="buyerCategory" data-id="" data-current-select="0" class="form-select" wire:model="buyerCategory" wire:change="categoryChange">
                <option disabled value="" selected="">{{ __('dashboard.select_product_category') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" >{{ $category->name }}</option>
                @endforeach
                <option value="0">Other</option>
            </select>
            @error('buyerCategory') <span class="text-danger" id="buyerCategoryError">{{ $message }}</span> @enderror

        </div>

        <div class="col-md-12">
            <div class="">
                <div class="row px-0">
                    <div class="col-md-12 mb-4 position-relative" id="productSubCategoryDiv">
                        <div class="">
                            <label for="product_sub_category" class="form-label mb-0 product_sub_category_for">{{ __('dashboard.Product Sub Category') }}<span class="text-danger">*</span></label>
                            <select id="buyerSubcategory" name="buyerSubcategory" data-id="" class="form-select"  wire:model="buyerSubcategory" wire:change="subcategoryChange">
                                <option disabled="" value="">{{ __('dashboard.Select Product Sub Category') }}</option>
                                @if(!empty($subCategories))
                                    @foreach($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                    @endforeach
                                @endif
                                <option value="0">Other</option>

                            </select>
                            <input type="hidden" class="form-control" id="product_sub_category_id" name="product_sub_category_id">
                            @error('buyerSubcategory')<span class="text-danger" id="buyerSubcategoryError">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-12 col-xl-8 mb-4">
                        <label class="form-label mb-0 product_name_for" for="product_name">{{ __('dashboard.Product_Name') }}<span class="text-danger">*</span></label>
                        <input type="text" class="form-control product_name" id="buyerProduct" name="buyerProduct"  placeholder="{{ __('dashboard.product_name_placeholder') }}" wire:model="buyerProduct" wire:input="productSearch">
                        <ul id="productResult" class="searchResult @if($productResultShow==false) d-none @endif">
                            @if(!empty($products))
                                @foreach($products as $product)
                                    <li wire:click="setProduct('{{ $product->name }}')">{{ $product->name }}</li>
                                @endforeach
                            @endif
                        </ul>
                        @error('buyerProduct')<span class="text-danger" id="buyerProductError">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-6 col-xl-2 mb-4">
                        <label for="quantity" class="form-label quantity_for">{{ __('dashboard.Quantity') }}
                            @if(isset($formType) && $formType!=2) <span class="text-danger">*</span> @endif
                        </label>
                        <input type="text" class="form-control qty_input_post input-decimal" wire:model="buyerProductQuantity" name="buyerProductQuantity" id="buyerProductQuantity"  placeholder="0"  @if(isset($formType) && $formType!=1) readonly value="0" @endif>
                        @error('buyerProductQuantity')<span class="text-danger" id="buyerProductQuantityError">{{ $message }}</span>@enderror

                    </div>
                    <div class="col-md-6 col-xl-2 mb-4">
                        <label class="form-label unit_for" for="unit">{{ __('dashboard.Unit') }}<span class="text-danger">*</span></label>
                        <select class="form-select" id="buyerProductUnit" name="buyerProductUnit" wire:model="buyerProductUnit" >
                            <option value="" disabled="">{{ __('dashboard.Select Unit') }}</option>
                            @if(!empty($units))
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id}}">{{ $unit->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('buyerProductUnit')<span class="text-danger" id="buyerProductUnitError">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-12 error_res ">
                        <label class="form-label mb-0 product_description_for" for="product_description">{{ __('dashboard.Product_Description') }}<span class="text-danger">*</span></label>
                        <textarea class="form-control" placeholder="{{ __('dashboard.Product_Description_Placeholder') }}" name="productDescription" id="productDescription"  wire:model="productDescription"></textarea>
                        @error('productDescription')<span class="text-danger" id="productDescriptionError">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="@if(isset($formType) && $formType==1) col-md-12 @else col-md-12 @endif">
            <label class="form-label" for="comment">{{ __('dashboard.Comment') }}</label>
            <textarea class="form-control" placeholder="{{ __('dashboard.Comment_Placeholder') }}" name="buyerComment" id="buyerComment" wire:model="buyerComment"></textarea>
        </div>
        <div class="col-md-6 col-xl-4 @if(isset($formType) && $formType!=1) d-none @endif">
            <label class="form-label" for="expectedDate">{{ __('dashboard.Expected_Delivery_Date') }}<span class="text-danger">*</span></label>
            <input type="text" class="form-control calendericons" name="expectedDate" id="expectedDate" placeholder="dd-mm-yyyy"  readonly="" data-toggle="tooltip" data-placement="top" title="{{ __('admin.expected_delivery_date') }}" wire:model="expectedDate">
            @error('expectedDate')<span class="text-danger" id="expectedDateError">{{ $message }}</span>@enderror
        </div>
        <div class="col-md-6 col-xl-4 @if(isset($formType) && $formType!=2) d-none @endif" >
            <label class="form-label" for="startDate">{{ __('dashboard.start_date') }}<span class="text-danger">*</span></label>
            <input type="text" class="form-control calendericons" name="startDate" id="startDate" wire:model="startDate" placeholder="dd-mm-yyyy"  readonly="" data-toggle="tooltip" data-placement="top" title="{{__('buyer.start_date_title')}}">
            @error('startDate')<span class="text-danger" id="startDateError">{{ $message }}</span>@enderror
        </div>
        <div class="col-md-6 col-xl-4 @if(isset($formType) && $formType!=2) d-none @endif">
            <label class="form-label" for="endDate">{{ __('dashboard.end_date') }}<span class="text-danger">*</span></label>
            <input type="text" class="form-control calendericons" name="endDate" id="endDate" wire:model="endDate" placeholder="dd-mm-yyyy"  readonly="" data-toggle="tooltip" data-placement="top" title="{{__('buyer.end_date_title')}}">
            @error('endDate')<span class="text-danger" id="endDateError">{{ $message }}</span>@enderror
        </div>

    </form>

</div>

@push('create-rf-search')
    <script type="text/javascript">
        var SnippetCreateRfn = function () {
            var product = function () {
                $('#buyerProduct').click(function () {
                    $('#productResult').removeClass('d-none');
                });
            },
            removeSingleError = function(){
                //company details validation remove
                $('#rfnForm input[type="text"]').on('input', function (evt) {
                    let inputId = $(this).attr('id');
                    window.livewire.emit('resetError',inputId,true);
                });
                $('#rfnForm select').on('change', function (evt) {
                    let inputId = $(this).attr('id');
                    window.livewire.emit('resetError',inputId,true);
                });
                $('#rfnForm textarea').on('input', function (evt) {
                    let inputId = $(this).attr('id');
                    window.livewire.emit('resetError',inputId,true);
                });
            },
            /** Remove All errors*/
            removeAllErrors = function(){
                $('#rfnForm span.text-danger').html('');
                window.livewire.emit('resetError');
            },

            fieldsInit = function () {
                $('#expectedDate').datepicker({
                    onSelect: function (date) {
                        @this.set('expectedDate', date);
                    },
                    dateFormat: "dd-mm-yy",
                    minDate: "+1d"
                });

                $('#startDate').datepicker({
                    onSelect: function (date) {
                        @this.set('startDate', date);
                    },
                    dateFormat: "dd-mm-yy",
                    minDate: "0d"
                });

                $('#endDate').datepicker({
                    onSelect: function (date) {
                        @this.set('endDate', date);
                    },
                    dateFormat: "dd-mm-yy",
                    minDate: "+1d"
                });
            };

            return {
                init: function () {
                    product(),fieldsInit(), removeSingleError()
                }
            }
        }(1);


        var SnippetSearchApp = function() {

            var searchAutocomplete = function () {
                $( "#productSearch" ).autocomplete({
                    minLength:3,
                    source: function( request, response ) {
                        // Fetch data
                        $.ajax({
                            url: "/search-product",
                            method:'POST',
                            dataType: "json",
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                data: request.term
                            },
                            success: function( data ) {
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

                        @this.set('buyerCategory', ui.item.categoryId);
                        @this.set('buyerSubcategory', ui.item.subcategoryId);
                        @this.set('buyerProduct', ui.item.product_name);
                        window.livewire.emit('setData');


                        $(".count").text('0 Result Found');
                        $('#buyerCategory').addClass('blink');
                        $('#buyerSubcategory').addClass('blink');
                        $("#buyerProduct").addClass('blink');
                        return false;
                    }
                });
            };

            return {
                init: function () {
                    searchAutocomplete()
                }
            }

        }(1);


        jQuery(document).ready(function(){
            SnippetCreateRfn.init(),
            SnippetSearchApp.init()
        });

    </script>

@endpush
