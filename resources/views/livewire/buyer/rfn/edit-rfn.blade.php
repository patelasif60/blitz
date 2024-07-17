<div wire:key="edit-rfn-section">
    <style>
        .ui-autocomplete {
            max-height: 100px;
            overflow-y: auto;
            position: absolute;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
        }

        #ui-id-2 {
            z-index: 10000;
        }

        .ui-autocomplete > li.ui-state-focus {
            background-color: #FF6C00;
        }
        .blink {
            animation-iteration-count: 1;
        }

    </style>
    <form class="row g-4 floatlables"  autocomplete="off" name="editRfnForm" id="editRfnForm" wire:submit.prevent="updateRfn" type="POST">
        @csrf
        <div class="col-md-12 mb-0 d-flex align-items-center">
            <h5 class="ps-0 mt-0 text-primary mb-0" style="font-family: 'europaNuova_re';font-size: 15px;">{{ __('rfqs.Product_Details') }}</h5>
            <div class="search_rfq mx-2 position-relative">
                <label class="form-label" for="Search_category" style="left: 10px;">{{__('admin.search_product')}}</label>
                <input type="text" name="editproductSearch" wire:model="editproductSearch" id="editproductSearch" class="form-control categorysearch" placeholder="{{__('admin.product_search')}}" />
            </div>
            <small class="ms-1 editcount" style="display: none"></small>
        </div>
        <div class="col-md-12" id="product_category_block">
            <label class="form-label" for="product_category">{{ __('dashboard.select_product_label') }}<span class="text-danger">*</span></label>
            <select id='editBuyerCategory' name="editBuyerCategory" data-id="" data-current-select="0" class="form-select" wire:model="editBuyerCategory" wire:change="categoryChange">
                <option disabled value="" selected="">{{ __('dashboard.select_product_category') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" >{{ $category->name }}</option>
                @endforeach
                <option value="0">Other</option>
            </select>
            @error('editBuyerCategory') <span class="text-danger" id="editBuyerCategoryError">{{ $message }}</span> @enderror

        </div>
        <div class="col-md-12 d-none position-relative" id="productCategoryOtherDiv">
            <label>{{ __('dashboard.other_product_category') }}<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="other_category" name="other_category">
        </div>

        <div class="col-md-12">
            <div class="">
                <div class="row px-0">
                    <div class="col-md-12 mb-4 position-relative" id="productSubCategoryDiv">
                        <div class="">
                            <label for="product_sub_category" class="form-label mb-0 product_sub_category_for">{{ __('dashboard.Product Sub Category') }}<span class="text-danger">*</span></label>
                            <select id="editBuyerSubcategory" name="editBuyerSubcategory" data-id="" class="form-select"  wire:model="editBuyerSubcategory" wire:change="subcategoryChange">
                                <option disabled="" value="">{{ __('dashboard.Select Product Sub Category') }}</option>
                                @if(!empty($subCategories))
                                    @foreach($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                    @endforeach
                                @endif
                                <option value="0">Other</option>

                            </select>
                            <input type="hidden" class="form-control" id="product_sub_category_id" name="product_sub_category_id">
                            @error('editBuyerSubcategory')<span class="text-danger" id="editBuyerSubcategoryError">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-12 col-xl-8 mb-4">
                        <label class="form-label mb-0 product_name_for" for="product_name">{{ __('dashboard.Product_Name') }}<span class="text-danger">*</span></label>
                        <input type="text" class="form-control product_name" id="editBuyerProduct" name="editBuyerProduct"  placeholder="{{ __('dashboard.product_name_placeholder') }}" wire:model="editBuyerProduct" wire:input="productSearch">
                        <ul id="productResult" class="searchResult @if($productResultShow==false) d-none @endif">
                            @if(!empty($products))
                                @foreach($products as $product)
                                    <li wire:click="setProduct('{{ $product->name }}')">{{ $product->name }}</li>
                                @endforeach
                            @endif
                        </ul>
                        @error('editBuyerProduct')<span class="text-danger" id="editBuyerProductError">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-6 col-xl-2 mb-4">
                        <label for="quantity" class="form-label quantity_for">{{ __('dashboard.Quantity') }}
                            @if(isset($formType) && $formType!=2) <span class="text-danger">*</span>@endif
                        </label>
                        <input type="text" class="form-control qty_input_post input-decimal" wire:model.lazy="editBuyerProductQuantity" name="editBuyerProductQuantity" id="editBuyerProductQuantity"  placeholder="0"   @if(isset($formType) && $formType!=1) readonly value="0" @endif>
                        @error('editBuyerProductQuantity')<span class="text-danger" id="editBuyerProductQuantityError">{{ $message }}</span>@enderror

                    </div>
                    <div class="col-md-6 col-xl-2 mb-4">
                        <label class="form-label unit_for" for="unit">{{ __('dashboard.Unit') }}<span class="text-danger">*</span></label>
                        <select class="form-select" id="editBuyerProductUnit" name="editBuyerProductUnit" wire:model="editBuyerProductUnit" >
                            <option value="" disabled="">{{ __('dashboard.Select Unit') }}</option>
                            @if(!empty($units))
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id}}">{{ $unit->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('editBuyerProductUnit')<span class="text-danger" id="editBuyerProductUnitError">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-12 error_res ">
                        <label class="form-label mb-0 product_description_for" for="product_description">{{ __('dashboard.Product_Description') }}<span class="text-danger">*</span></label>
                        <textarea class="form-control" placeholder="{{ __('dashboard.Product_Description_Placeholder') }}" name="editProductDescription" id="editProductDescription"  wire:model="editProductDescription"></textarea>
                        @error('editProductDescription')<span class="text-danger" id="editProductDescriptionError">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <label class="form-label" for="comment">{{ __('dashboard.Comment') }}</label>
            <textarea class="form-control" placeholder="{{ __('dashboard.Comment_Placeholder') }}" name="editBuyerComment" id="editBuyerComment" wire:model="editBuyerComment"></textarea>
        </div>
        <div class="col-md-6 col-xl-4 @if(isset($formType) && $formType!=1) d-none @endif">
            <label class="form-label" for="editExpectedDate">{{ __('dashboard.Expected_Delivery_Date') }}<span class="text-danger">*</span></label>
            <input type="text" class="form-control calendericons" name="editExpectedDate" id="editExpectedDate" wire:model="editExpectedDate" placeholder="dd-mm-yyyy"  data-toggle="tooltip" data-placement="top" title="{{ __('admin.expected_delivery_date') }}" >
            @error('editExpectedDate')<span class="text-danger" id="editExpectedDateError">{{ $message }}</span>@enderror
        </div>
        <div class="col-md-6 col-xl-4 @if(isset($formType) && $formType!=2) d-none @endif" >
            <label class="form-label" for="editStartDate">{{ __('dashboard.start_date') }}<span class="text-danger">*</span></label>
            <input type="text" class="form-control calendericons" name="editStartDate" id="editStartDate" wire:model="editStartDate" placeholder="dd-mm-yyyy"  readonly="" data-toggle="tooltip" data-placement="top" title="{{__('buyer.start_date_title')}}">
            @error('editStartDate')<span class="text-danger" id="editStartDateError">{{ $message }}</span>@enderror
        </div>
        <div class="col-md-6 col-xl-4 @if(isset($formType) && $formType!=2) d-none @endif">
            <label class="form-label" for="editEndDate">{{ __('dashboard.end_date') }}<span class="text-danger">*</span></label>
            <input type="text" class="form-control calendericons" name="editEndDate" id="editEndDate" wire:model="editEndDate" placeholder="dd-mm-yyyy"  readonly="" data-toggle="tooltip" data-placement="top" title="{{__('buyer.end_date_title')}}">
            @error('editEndDate')<span class="text-danger" id="editEndDateError">{{ $message }}</span>@enderror
        </div>

    </form>

</div>

@push('update-rfn')
    <script type="text/javascript">

        var SnippetUpdateRfn = function () {

            var product = function () {
                    $('#editBuyerProduct').click(function () {
                        $('#productResult').removeClass('d-none');
                    });
                },

                removeSingleError = function(){

                    //company details validation remove
                    $('#editRfnForm input[type="text"]').on('input', function (evt) {
                        let inputId = $(this).attr('id');
                        window.livewire.emit('editResetError',inputId,true);
                    });
                    $('#editRfnForm select').on('change', function (evt) {
                        let inputId = $(this).attr('id');
                        window.livewire.emit('editResetError',inputId,true);
                    });
                    $('#editRfnForm textarea').on('input', function (evt) {
                        let inputId = $(this).attr('id');
                        window.livewire.emit('editResetError',inputId,true);
                    });
                },
                /** Remove All errors*/
                removeAllErrors = function(){
                    $('#editRfnForm span.text-danger').html('');
                    window.livewire.emit('editResetError');
                },

                fieldsInit = function () {

                    $('#editExpectedDate').datepicker({
                        onSelect: function (date) {
                            @this.set('editExpectedDate', date);
                        },
                        dateFormat: "dd-mm-yy",
                        minDate: "+1d"
                    });

                    $('#editStartDate').datepicker({
                        onSelect: function (date) {
                        @this.set('editStartDate', date);
                        },
                        dateFormat: "dd-mm-yy",
                        minDate: "0d"
                    });

                    $('#editEndDate').datepicker({
                        onSelect: function (date) {
                        @this.set('editEndDate', date);
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

        var SnippetEditSearchApp = function() {

            var searchAutocomplete = function () {
                $( "#editproductSearch" ).autocomplete({
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

                                $(".editcount").text(data.cnt+' Result Found');
                                $(".editcount").css("display", "block");
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

                    @this.set('editBuyerCategory', ui.item.categoryId);
                    @this.set('editBuyerSubcategory', ui.item.subcategoryId);
                    @this.set('editBuyerProduct', ui.item.product_name);
                        window.livewire.emit('editSetData');


                        $(".editcount").text('0 Result Found');
                        $('#editBuyerCategory').addClass('blink');
                        $('#editBuyerSubcategory').addClass('blink');
                        $("#editBuyerProduct").addClass('blink');
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
            SnippetUpdateRfn.init();
            SnippetEditSearchApp.init()

        });

    </script>
@endpush
