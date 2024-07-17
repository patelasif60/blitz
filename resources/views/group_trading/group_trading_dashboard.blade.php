@extends('dashboard/layout/layout')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
<style>
    #social-links ul{
        padding: 0px 20px;
        display: grid;
        grid-template-columns: auto auto auto auto auto;
    }
    #social-links ul li {
        padding: 5px 20px;
        list-style: none;
    }
    #social-links ul li a {
        padding: 6px;
        border-radius: 5px;
        margin: 1px;
        font-size: 36px;
    }
    #social-links .fa-facebook{
        color: #0d6efd;
    }
    #social-links .fa-twitter{
        color: deepskyblue;
    }
    #social-links .fa-linkedin{
        color: #0e76a8;
    }
    #social-links .fa-whatsapp{
        color: #25D366
    }
    #social-links .fa-reddit{
        color: #FF4500;;
    }
    #social-links .fa-telegram{
        color: #0088cc;
    }
    /*
    .fa.fa-instagram {
      color: transparent;
      background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
      background: -webkit-radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
      background-clip: text;
      -webkit-background-clip: text;
    } */
</style>
@if(!isset(auth()->user()->role_id))
    <div id="carouselCaptions" class="carousel slide bannersection py-3" data-bs-ride="carousel" data-aos="fade-down"></div>
@endif
<div id="group_section" class="row gx-4 mx-lg-0 group_section @if(!\Auth::check()) pt-5 mt-5 @endif">
    @if(isset($groups) && count($groups) > 0)
    <!-- Left section Start -->
    <div id="userinfo" class="col-lg-3 col-xl-3 py-3 collapse collapse-horizontal show">
        <div class="container-fluid m-0 p-0">
            <div class="row mb-3">
                <div class="card border-0 shadow-lg bg-white categoryfilter m-0 p-0">
                    <div class="card-header align-items-center d-flex">
                        <h5 class="dark_blue mb-0">{{ __('admin.filters') }} <small>( <b id="filterApplyCount">0</b> filters applied )</small></h5>
                        <!-- <button type="button" class="btn btn-white btn-sm ms-auto border" style="font-size: 0.6rem;"><b>4</b>
                            Filters Applied <span class="align-items-center"><img src="{{ URL::asset('front-assets/images/icons/times-circle.png') }}" alt=""></span>
                        </button> -->
                        <button type="button" class="btn btn-danger btn-sm ms-auto border ps-1" id="clearFilterBtn" style="font-size: 0.6rem;"><span> <img src="{{ URL::asset('front-assets/images/icons/icon_clear.png') }}" alt="Clear All"> Clear All</span></button>
                    </div>
                    <div class="card-body">
                        <div class="col-lg-12 col-xl-12 filterselect mb-2" id="groupFiltersSpace"></div>
                        <div>
                            <!-- Category Filter -->
                            <div class="pt-2 filterselect_sections">
                                <h6 class="filter_category">
                                    <a data-bs-toggle="collapse" href="#collapseCategory1" role="button" aria-expanded="true" aria-controls="collapseCategory1">Select {{ __('admin.category') }}</a>
                                </h6>
                                <div class="collapse show" id="collapseCategory1">
                                    <div class="px-1">
                                        <input class="form-control form-control-sm mb-2 filtersearch" attr-name="catSearch"  type="text" placeholder="Search..">
                                    </div>
                                    <div id="filterselect_catSearch" class="filterselect_Subsections">
                                        @if(isset($categories) && count($categories) > 0)
                                            @foreach($categories as $category)
                                            <div class="form-check">
                                                <input class="form-check-input checkbox category groupFilter" name="groupFilter[]" type="checkbox" attr-name="{{ $category->category_name }}" value="{{ $category->id }}" id="cat{{ $category->id }}">
                                                <label class="form-check-label" for="cat{{ $category->id }}">{{ $category->category_name }}</label>
                                            </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Category Filter End -->

                            <!-- Sub-Category Filter -->
                            <div class="pt-2 filterselect_sections">
                                <h6 class="filter_category">
                                    <a data-bs-toggle="collapse" href="#collapseCategory2" role="button" aria-expanded="true" aria-controls="collapseCategory2">Select {{ __('admin.sub_category') }} </a>
                                </h6>
                                <div class="collapse show" id="collapseCategory2">
                                    <div class="px-1">
                                        <input class="form-control form-control-sm mb-2 filtersearch" attr-name="subCatSearch"  type="text" placeholder="Search..">
                                    </div>
                                    <!-- Sub Category will be load here by ajax -->
                                    <div id="filterselect_subCatSearch" class="filterselect_Subsections">
                                        @if(isset($subCategories) && count($subCategories) > 0)
                                            @foreach($subCategories as $subCategory)
                                                <div class="form-check">
                                                    <input class="form-check-input checkbox groupFilter subCatHTML sub_category" name="groupFilter[]" type="checkbox" attr-name="{{ $subCategory->subCategoryName }}" value="{{ $subCategory->id }}" id="subcat{{ $subCategory->id }}">
                                                    <label class="form-check-label" for="subcat{{ $subCategory->id }}">{{ $subCategory->subCategoryName }}</label>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Sub-Category Filter End -->

                            <!-- Product Filter -->
                            <div class="pt-2 filterselect_sections">
                                <h6 class="filter_category">
                                    <a data-bs-toggle="collapse" href="#collapseCategory3" role="button" aria-expanded="true" aria-controls="collapseCategory3">Select {{ __('admin.product') }} </a>
                                </h6>
                                <div class="collapse show" id="collapseCategory3">
                                    <div class="px-1">
                                        <input class="form-control form-control-sm mb-2 filtersearch" attr-name="productSearch"  type="text" placeholder="Search..">
                                    </div>
                                    <!-- Product will be load here by ajax -->
                                    <div id="filterselect_productSearch" class="filterselect_Subsections">
                                        @if(isset($products) && count($products) > 0)
                                            @foreach($products as $product)
                                                <div class="form-check">
                                                    <input class="form-check-input checkbox groupFilter groupFilter product" name="groupFilter[]" type="checkbox" attr-name="{{ $product->productName }}" value="{{ $product->id }}" id="product{{ $product->id }}">
                                                    <label class="form-check-label" for="product{{ $product->id }}">{{ $product->productName }}</label>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Product Filter End -->

                            <!-- Group Discount Filter -->
                            <div class="pt-2 filterselect_sections">
                                <h6 class="filter_category">
                                    <a data-bs-toggle="collapse" href="#collapseCategory4" role="button" aria-expanded="true" aria-controls="collapseCategory4">{{ __('admin.discount') }} (%)</a>
                                </h6>
                                <div class="collapse show" id="collapseCategory4">
                                    <div class="px-1">
                                        <input class="form-control form-control-sm mb-2 filtersearch" attr-name="discountSearch" type="text" placeholder="Search..">
                                    </div>
                                    <div id="filterselect_discountSearch" class="filterselect_Subsections">
                                        @if(isset($discounts) && count($discounts) > 0)
                                            @foreach($discounts as $dis)
                                            <div class="form-check mb-1">
                                                <input class="form-check-input checkbox groupFilter discountVal" name="groupFilter" type="checkbox" attr-name="{{ $dis->discount }}" value="{{ $dis->discount }}" data-id="{{ $dis->id }}" id="discount{{ $dis->id }}">
                                                <label class="form-check-label" for="discount{{ $dis->id }}"> More than {{ $dis->discount . ' %' }}</label>
                                            </div>
                                            @endforeach
                                        @endif
                                        {{--<div class="form-check mb-1">
                                            <input class="form-check-input checkbox discountVal" name="groupFilter" type="checkbox" attr-name="3-5" value="3-5" id="discount3">
                                            <label class="form-check-label" for="discount3">3-5</label>
                                        </div>
                                        <div class="form-check mb-1">
                                            <input class="form-check-input checkbox discountVal" name="groupFilter" type="checkbox" attr-name="6-10" value="6-10" id="discount5">
                                            <label class="form-check-label" for="discount5">6-10</label>
                                        </div>
                                        <div class="form-check mb-1">
                                            <input class="form-check-input checkbox discountVal" name="groupFilter" type="checkbox" attr-name="11-15" value="11-15" id="discount6">
                                            <label class="form-check-label" for="discount6">11-15</label>
                                        </div>
                                        <div class="form-check mb-1">
                                            <input class="form-check-input checkbox discountVal" name="groupFilter" type="checkbox" attr-name="16-20" value="16-20" id="discount6">
                                            <label class="form-check-label" for="discount6">16-20</label>
                                        </div>--}}
                                    </div>
                                </div>
                            </div>
                            <!-- Group Discount Filter End -->

                            {{--<div class="pt-2 filterselect_sections">
                                <h6 class="filter_category">{{ __('dashboard.group_size') }}</h6>
                                <div class="filterselect_Subsections">
                                    @if(isset($groupMembers) && count($groupMembers) > 0)
                                        @foreach($groupMembers as $member)
                                        <div class="form-check mb-1">
                                            <input class="form-check-input checkbox grpSize" name="groupFilter" type="checkbox" attr-name="{{ $member->totalMembers }}" value="{{ $member->totalMembers }}" id="grpSize{{ $member->id }}">
                                            <label class="form-check-label" for="grpSize{{ $member->id }}"> Less than {{ $member->totalMembers }}</label>
                                        </div>
                                        @endforeach
                                    @endif
                                    <div class="form-check mb-1">
                                        <input class="form-check-input checkbox grpSize" name="groupFilter" type="checkbox" attr-name="1-5 Members" value="1-5" id="grpSize01">
                                        <label class="form-check-label" for="grpSize01"> 1 - 5 Members</label>
                                    </div>
                                    <div class="form-check mb-1">
                                        <input class="form-check-input checkbox grpSize" name="groupFilter" type="checkbox" attr-name="6-10 Members" value="6-10" id="grpSize02">
                                        <label class="form-check-label" for="grpSize02"> 6 - 10 Members</label>
                                    </div>
                                </div>
                            </div>--}}

                            <!-- Remaining Days Filter -->
                            <div class="pt-2 filterselect_sections">
                                <h6 class="filter_category">
                                    <a data-bs-toggle="collapse" href="#collapseCategory5" role="button" aria-expanded="true" aria-controls="collapseCategory5">{{ __('dashboard.days_remaining')}}</a>
                                </h6>
                                <div class="collapse show" id="collapseCategory5">
                                    <div class="px-1">
                                        <input class="form-control form-control-sm mb-2 filtersearch" attr-name="daysSearch" type="text" placeholder="Search..">
                                    </div>
                                    <div id="filterselect_daysSearch" class="filterselect_Subsections">
                                        @if(isset($remainingDays) && count($remainingDays) > 0)
                                            @foreach($remainingDays as $days)
                                            @php
                                                $expDate = (strtotime($days->end_date) - strtotime(now()->format('Y-m-d'))) / (60 * 60 * 24);
                                            @endphp
                                            <div class="form-check mb-1">
                                                <input class="form-check-input checkbox groupFilter remainingDays" name="groupFilter" type="checkbox" attr-name="{{ $expDate }}" value="{{ $expDate }}" id="remainingDays{{ $days->id }}" data-id="{{ $days->id }}">
                                                <label class="form-check-label" for="remainingDays{{ $days->id }}"> Less than {{ $expDate > 1 ? $expDate . ' ' . __('dashboard.days_remaining') : $expDate . ' ' . __('dashboard.day_remaining') }} </label>
                                            </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Remaining Days Filter End -->

                            <div class="pt-2 filterselect_sections d-none" id="filter_no_image_section">
                                <h6 class="filter_category">Range</h6>
                                <div class="mb-2">
                                    <label for="amount">Price range:</label>
                                    <input type="text" id="amount" readonly style="border:0; color:#1d0fff; font-weight:bold;">
                                </div>

                                <div id="slider-range"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- left section end -->

    <!-- Group Section -->
    <div class="col-md-12 col-lg-9 col-xl-9 py-2">
        <div class="container-fluid m-0 ">
            <div class="header_top d-flex align-items-center pt-md-0">
                <h1 class="mb-3 color-primary">{{ __('dashboard.group_trading') }}</h1>
                <!-- <form method="post" id="grouptrading_search" class="input-group mb-3 ms-auto" autocomplete="off"> -->
                <div id="grouptrading_search" class="input-group mb-3 ms-auto">
                    <input class="form-control form-control-sm" type="search" name="group_search" id="group_search" placeholder="{{ __('admin.type_to_search') }}" aria-label="Search">
                    <button class="btn bg-dark text-white btn-sm search  f-14" type="button" id="groupSearchBtn">{{ __('dashboard.search') }}</button>
                </div>
                <!-- <a href="{{ route('dashboard') }}" class="btn btn-warning btn-sm" style="padding-top: .1rem;padding-bottom: .1rem;">
                    <img src="{{ URL::asset('front-assets/images/icons/angle-left.png') }}" alt="Back"> {{ __('profile.back') }}</a>
                </a> -->
                <!-- </form> -->
            </div>
            <div class="row mb-3">
                <div id="groupsTradingData">
                    <div class="row">
                        <!-- Groups Data will be show here -->
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-12 text-center">
                <div class="loading" style="display: none;">
                    <img src="{{ URL::asset('assets/images/front/loader.gif') }}" style="height: 50px;width: 50px;" aria-hidden="true" class="loader">
                </div>
            </div> -->
        </div>
    </div>
    <!-- Group Section End -->
    @else
    <div class="col-md-12 py-2">
        <div class="container-fluid m-0 p-0">
            <div class="header_top">
                <h1 class="mb-0 text-center">{{ __('dashboard.group_trading') }}</h1>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <img src="{{ URL::asset('front-assets/images/nogroup.png') }}" alt="No Group Available">
                    <h5>{{ __('dashboard.no_group_msg') }}<br>{{ __('dashboard.come_back_later') }}</h5>
                    <!-- <a href="{{ route('home')}}" class="btn btn-primary"><span>{{ __('order.back_to_home') }}</span></a> -->
                    <div class="col-12 text-center pb-3 pb-lg-5 mt-4">
                        @if(!isset(auth()->user()->id))
                            <a href="{{route('signup')}}" class="btn btn-primary mb-2 aos-init aos-animate"
                            data-aos="fade-up-right"><span>{{__('home_latest.free_register_buyer')}}</span></a>
                        @endif
                        <a href="{{route('contact-us')}}#contact_us_main_div"
                        class="btn btn-primary mb-2 aos-init aos-animate" data-aos="fade-up-left"><span>{{__('home_latest.contact_us_home')}}</span></a>
                        @if(!isset(auth()->user()->id))
                            <a href="{{route('signup-supplier')}}"
                            class="btn btn-primary mb-2 aos-init aos-animate" data-aos="fade-up-left"><span>{{__('home_latest.free_register_supplier')}}
                            </span></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    let isPaginateEnable = true;
    let query = '';
    $(document).ready(function() {
        let paginate = 0;


        //Disable search button if search textbox is empty
        if($("#group_search").val().length == 0) {
            $("#groupSearchBtn").prop('disabled', true);
        }

        //Disable "Clear All" button if no filter is selected
        if($("#filterApplyCount").val().length == 0) {
            // $("#clearFilterBtn").prop('disabled', true);
            $("#clearFilterBtn").addClass('d-none');
        } else {
            // $("#clearFilterBtn").prop('disabled', false);
            $("#clearFilterBtn").removeClass('d-none');
        }

        //Fetch groups by pagination
        // fetch_group_trading_data(paginate, query = '');
        searchByFilters(paginate,categoriesArr,subCategoriesArr,productsArr,discountArr,daysRemainingArr,query,false);

        //get all sub category when no category is selected
        //getSubCategoriesByCategoryId(categoriesArr = []);

        //get all product by category and sub category
        //getProductsByCatSubCatId(categoriesArr = [], subCategoriesArr = []);

        //On page scroll, get load more groups
        $(window).scroll(function() {
            // console.log('isPaginateEnable '+isPaginateEnable);
            if(isPaginateEnable){
                if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                    paginate++;
                    // fetch_group_trading_data(paginate, query = '');
                    searchByFilters(paginate,categoriesArr,subCategoriesArr,productsArr,discountArr,daysRemainingArr,query,false);
                }
            }
        });
    });

    //Global Defined variable and array
    var paginate = 0;
    var categoriesArr = [];
    var subCategoriesArr = [];
    var productsArr = [];
    var discountArr = [];
    var daysRemainingArr = [];
    var group_filters = [];

    /**
     * Developed by Ronak Bhabhor
     * removeItem: first param array, second param you want to delete as string
     */
    function removeItem(array, item){
        for(var i in array){
            if(array[i]==item){
                array.splice(i,1);
                return array;
            }
        }
    }

    //Enable / Disable "Clear All" btn
    function clearAppliedFilters() {
        if(group_filters.length == 0) {
            // $("#clearFilterBtn").prop('disabled', true);
            $("#clearFilterBtn").addClass('d-none');
        } else {
            // $("#clearFilterBtn").prop('disabled', false);
            $("#clearFilterBtn").removeClass('d-none');
        }
    }

    // removeItem(array, 'seven');

    //On checkbox checked search groups by category id (30-03-2022 Ronak)
    $(document).on('click', '.category', function() {
        $(this).prop('checked');
        if($(this).prop('checked') == true) {
            // var query = $("#group_search").val();
            group_filters.push($(this).val());
            $("#groupFiltersSpace").append("<button type='button' class='badge alert-primary me-1 mb-1 border clearFilter cat' id='categoryId"+$(this).val()+"' style='font-size: 13px;' value='"+$(this).val()+"'>" +$(this).attr('attr-name')+ " <span class='align-items-center'> <img src='{{ URL::asset('front-assets/images/icons/times-circle.png') }}' alt=''></span></button>");
            $("#filterApplyCount").text(group_filters.length);
            categoriesArr.push($(this).val());
        } else {
            group_filters.pop();
            $("#filterApplyCount").text(group_filters.length);
            $("#categoryId"+$(this).val()).remove();
            categoriesArr = removeItem(categoriesArr, $(this).val()); // remove current category
        }
        // console.log(query);
        //Call function
        searchByFilters(paginate,categoriesArr,subCategoriesArr,productsArr,discountArr,daysRemainingArr,query,true);

        //Get all subcategories by parent category id (Ronak - 08/04-2022)
        if(categoriesArr.length > 0) {
            getSubCategoriesByCategoryId(categoriesArr);
        }
        clearAppliedFilters();
    });

    //On checkbox checked search groups by sub category id (30-03-2022 Ronak)
    $(document).on('click', '.sub_category', function() {
        $(this).prop('checked');
        if($(this).prop('checked') == true) {
            group_filters.push($(this).val());
            $("#groupFiltersSpace").append("<button type='button' class='badge alert-primary me-1 mb-1 border clearFilter subCat' id='subCatId"+$(this).val()+"' style='font-size: 13px;' value='"+$(this).val()+"'>" +$(this).attr('attr-name')+ " <span class='align-items-center'> <img src='{{ URL::asset('front-assets/images/icons/times-circle.png') }}' alt=''></span></button>");
            $("#filterApplyCount").text(group_filters.length);
            subCategoriesArr.push($(this).val());
        } else {
            group_filters.pop();
            $("#filterApplyCount").text(group_filters.length);
            $("#subCatId"+$(this).val()).remove();
            subCategoriesArr = removeItem(subCategoriesArr, $(this).val()); // remove current sub_category
        }

        //Call function
        searchByFilters(paginate,categoriesArr,subCategoriesArr,productsArr,discountArr,daysRemainingArr,query,true);

        //Get all subcategories by parent category id (Ronak - 08/04-2022)
        if(subCategoriesArr.length > 0) {
            getProductsByCatSubCatId(categoriesArr, subCategoriesArr);
        }
        clearAppliedFilters();
    });

    //On checkbox checked search groups by product id (13-04-2022 Ronak)
    $(document).on('click', '.product', function() {
        $(this).prop('checked');
        if($(this).prop('checked') == true) {
            group_filters.push($(this).val());

            $("#groupFiltersSpace").append("<button type='button' class='badge alert-primary me-1 mb-1 border clearFilter prod' id='productId"+$(this).val()+"' style='font-size: 13px;' value='"+$(this).val()+"'>" +$(this).attr('attr-name')+ " <span class='align-items-center'> <img src='{{ URL::asset('front-assets/images/icons/times-circle.png') }}' alt=''></span></button>");
            $("#filterApplyCount").text(group_filters.length);
            productsArr.push($(this).val());
        } else {
            group_filters.pop();
            $("#filterApplyCount").text(group_filters.length);
            $("#productId"+$(this).val()).remove();
            productsArr = removeItem(productsArr, $(this).val()); // remove current product
        }

        //Call function
        searchByFilters(paginate,categoriesArr,subCategoriesArr,productsArr,discountArr,daysRemainingArr,query,true);
        clearAppliedFilters();
    });

    //On checkbox checked search groups by discount(13-04-2022 Ronak)
    $(document).on('click', '.discountVal', function() {
        $(this).prop('checked');
        if($(this).prop('checked') == true) {
            group_filters.push($(this).val());

            $("#groupFiltersSpace").append("<button type='button' class='badge alert-primary me-1 mb-1 border clearFilter discountRate' id='discountId"+$(this).val()+"' style='font-size: 13px;' value='"+$(this).val()+"' data-id='"+$(this).attr('data-id')+"'>" +$(this).attr('attr-name')+ " <span class='align-items-center'> <img src='{{ URL::asset('front-assets/images/icons/times-circle.png') }}' alt=''></span></button>");

            $("#filterApplyCount").text(group_filters.length);
            discountArr.push($(this).val());
        } else {
            group_filters.pop();
            $("#filterApplyCount").text(group_filters.length);
            $("#discountId"+$(this).val()).remove();
            discountArr = removeItem(discountArr, $(this).val()); // remove current discount value
        }

        //Call function
        searchByFilters(paginate,categoriesArr,subCategoriesArr,productsArr,discountArr,daysRemainingArr,query,true);
        clearAppliedFilters();
    });

    //On checkbox checked search groups by remaining days(13-04-2022 Ronak)
    $(document).on('click', '.remainingDays', function() {
        $(this).prop('checked');
        if($(this).prop('checked') == true) {
            group_filters.push($(this).val());

            $("#groupFiltersSpace").append("<button type='button' class='badge alert-primary me-1 mb-1 border clearFilter days' style='font-size: 13px;' id='daysId"+$(this).val()+"' value='"+$(this).val()+"' data-id='"+$(this).attr('data-id')+"'>" +$(this).attr('attr-name')+ " <span class='align-items-center'> <img src='{{ URL::asset('front-assets/images/icons/times-circle.png') }}' alt=''></span></button>");

            $("#filterApplyCount").text(group_filters.length);
            daysRemainingArr.push($(this).val());
        } else {
            group_filters.pop();
            $("#filterApplyCount").text(group_filters.length);
            $("#daysId"+$(this).val()).remove();
            daysRemainingArr = removeItem(daysRemainingArr, $(this).val()); // remove current dayys remaining days
        }

        //Call function
        searchByFilters(paginate,categoriesArr,subCategoriesArr,productsArr,discountArr,daysRemainingArr,query,true);
        clearAppliedFilters();
    });


    //Clear Individual filter by click on cancle button (30-03-2022 Ronak)
    $(document).on('click', '.clearFilter', function() {

        $(this).remove();

        if($(this).hasClass('cat')) {
            $("#cat"+$(this).val()).prop('checked', false);
            group_filters.pop();
            $("#filterApplyCount").text(group_filters.length);
            categoriesArr = removeItem(categoriesArr, $(this).val()); // remove current category
        }

        if($(this).hasClass('subCat')) {
            $("#subcat"+$(this).val()).prop('checked', false);
            group_filters.pop();
            $("#filterApplyCount").text(group_filters.length);
            subCategoriesArr = removeItem(subCategoriesArr, $(this).val()); // remove current sub_category
        }

        if($(this).hasClass('prod')) {
            $("#product"+$(this).val()).prop('checked', false);
            group_filters.pop();
            $("#filterApplyCount").text(group_filters.length);
            productsArr = removeItem(productsArr, $(this).val()); // remove current product
        }
        if($(this).hasClass('discountRate')) {
            discount_id = $(this).attr("data-id");
            $("#discount"+discount_id).prop('checked', false);
            group_filters.pop();
            $("#filterApplyCount").text(group_filters.length);
            discountArr = removeItem(discountArr, discount_id); // remove current discount value
        }
        if($(this).hasClass('days')) {
            days_id = $(this).attr("data-id");
            $("#remainingDays"+days_id).prop('checked', false);
            group_filters.pop();
            $("#filterApplyCount").text(group_filters.length);
            daysRemainingArr = removeItem(daysRemainingArr, days_id); // remove current dayys remaining days
        }
        //console.log(categoriesArr);
        searchByFilters(paginate,categoriesArr,subCategoriesArr,productsArr,discountArr,daysRemainingArr,query,true);
        clearAppliedFilters();
    });

    //On keyup search by individual category
    $(".filtersearch").on("keyup", function () {
        var attrName = $(this).attr('attr-name');
        var value = $(this).val().toLowerCase();
        $("#filterselect_"+attrName+ " .form-check").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    //on click search button (25-03-22 Ronak)
    $(document).on('click', '#groupSearchBtn', function(e) {
        if($("#group_search").val().length == 0) {
            var paginate = 0;
            $('#groupsTradingData').html('');
            fetch_group_trading_data(paginate, query = '');
        } else {
            searchByKeyword(e);
        }

    });

    function searchByKeyword(e){
        if($("#group_search").val().length == 0) {
            e.stopImmediatePropagation();
            e.preventDefault();
        } else {
            paginate = 0;
            $('#groupsTradingData').html('');
            query = $("#group_search").val();
            // fetch_group_trading_data(paginate, query);
            searchByFilters(paginate,categoriesArr,subCategoriesArr,productsArr,discountArr,daysRemainingArr,query,true);
            $("input:checkbox[name=groupFilter]").prop('checked',false);
            group_filters = [];
            $("#groupFiltersSpace").empty();
            /* $(window).scroll(function() {
                if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                    paginate++;
                    fetch_group_trading_data(paginate, query);
                }
            }); */
        }
    }

    //On clear searched text, we will get all the data (25-03-22 Ronak)
    $('input[type=search]').on('search', function () {
        var paginate = 0;
        $('#groupsTradingData').html('');
        fetch_group_trading_data(paginate, query = '');
    });

    $('#group_search').keyup(function(e){
        if(e.keyCode == 13) {
            if($("#group_search").val().length == 0) {
                var paginate = 0;
                $('#groupsTradingData').html('');
                fetch_group_trading_data(paginate, query = '');
            } else {
                searchByKeyword(e);
            }
        }
    });

    //On enter key press search for the groups (25-03-22 Ronak)
    $('input[type=search]').keypress(function (e) {
        $("#groupSearchBtn").prop('disabled', false);
        var key = e.which;
        if (event.keyCode === 10 || event.keyCode === 13) {
            e.stopImmediatePropagation();
            e.preventDefault();
            // var paginate = 0;
            // $('#groupsTradingData').html('');
            // var query = $("#group_search").val();
            // fetch_group_trading_data(paginate, query);
            $(".groupFilter").prop('checked',false);
            // group_filters = [];
            // $("#groupFiltersSpace").empty();
        }
        // console.log($("#group_search").val().length);
    });

    //Clear All selected checkboxes on button click (30-03-2022 Ronak)
    $("#clearFilterBtn").click(function() {
        window.location.reload();
    });

    //Pass category , sub category and product data to post rfq form (25-03-22 Ronak)
    localStorage.clear();
    $(document).on('click', '.group_join_btn', function(e) {
        var grpId = $(this).attr("data-group-id");
        e.stopImmediatePropagation();
        e.preventDefault();
        localStorage.clear();
        var groupId = $("#groupId_"+grpId).val();
        var grpCatId = $("#categoryId_"+grpId).val();
        var grpSubCatId = $("#subCategoryId_"+grpId).val();
        var grpProdId = $("#grpProdId_"+grpId).val();
        var productName = $("#productName_"+grpId).val();
        var grpUnitId = $("#unitId_"+grpId).val();
        var unitName = $("#unitName_"+grpId).val();
        var achievedQuantity = $("#achievedQuantity_"+grpId).val();
        var targetQuantity = $("#targetQuantity_"+grpId).val();
        var groupProductDesc = $("#groupProdDesc_"+grpId).val();

        localStorage.setItem('groupId', groupId);
        localStorage.setItem('category', grpCatId);
        localStorage.setItem('sub_category', grpSubCatId);
        localStorage.setItem('GroupProductId', grpProdId);
        localStorage.setItem('product', productName);
        localStorage.setItem('unit_post', grpUnitId);
        localStorage.setItem('unitName', unitName);
        localStorage.setItem('achievedQuantity', achievedQuantity);
        localStorage.setItem('targetQuantity', targetQuantity);
        localStorage.setItem('groupProductDesc', groupProductDesc);

        var isUserLogin = $("#is_user_login").val();
        if(isUserLogin == 1) {
            window.location.replace("{{ route('get-a-quote') }}");
        } else {
            window.location.replace("{{ route('signin')}}");
        }
    });

    //Get group by search filter or all groups
    function fetch_group_trading_data(paginate, query = '') {
        // return false;
        // console.log(query);
        //console.log('filter init');
        //console.log(paginate, query)
        $.ajax({
            url: "{{ route('group-trading-ajax') }}",
            method: 'POST',
            data: {
                query: query,
                paginate:paginate,
                _token:"{{ csrf_token() }}"
            },
            beforeSend: function() {
                $('.loading').show();
            }
        }).done(function(result) {
            if(result.groups.length < result.group_count) {
                isPaginateEnable = false;
            }
            if(result.groups.length == 0 && result.query == null) {
                $('.loading').html("<img src='{{ URL::asset('front-assets/images/nogroup.png') }}' alt='No Group Available'><h5 class='text-danger'>No more records found</h5>");
                return;
            } else {
                if(result.groups.length != 0 && result.success == true) {
                    $('.loading').hide();
                    $('#groupsTradingData').append(result.returnHTML);
                    return;
                } else {
                    isPaginateEnable = false;
                }
                // comment by ronak
                // else if(result.groups.length == 0 && result.query != null) {
                //     $('.loading').hide();
                //     // $('.loading').html("<img src='{{ URL::asset('front-assets/images/nogroup.png') }}' alt='No Group Available'>'<h5 class='text-danger'>No more records found</h5>");
                //     return;
                //     // e.stopImmediatePropagation();
                // }
                $(window).scroll(function() {
                    if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                        paginate++;
                        fetch_group_trading_data(paginate, query = '');
                        //console.log(paginate,query = '');
                    }
                });
            }
        });
        // paginate++;
    }

    //Get all subcategories by parent category ids (Ronak - 08/04-2022)
    function getSubCategoriesByCategoryId(categoriesArr,subCategoriesArr) {
        $.ajax({
            url: "{{ route('get-subcategory-by-category-ajax') }}",
            method: 'POST',
            data: {
                categoriesArr: categoriesArr,
                subCategoriesArr: subCategoriesArr,
                _token:"{{ csrf_token() }}"
            },
            success: function(successData) {
                if(successData.success == true) {
                    var subCatHTML = '';
                    $.each(successData.subCategories, function(index, subCat) {
                        subCatHTML = subCatHTML + "<div class='form-check'><input class='form-check-input checkbox groupFilter subCatHTML sub_category' name='groupFilter' type='checkbox' attr-name='"+subCat.subCatName+"' value='"+subCat.id+"' id='subcat"+subCat.id+"'><label class='form-check-label' for='subcat"+subCat.id+"'>"+subCat.subCatName+"</label></div>";
                    });
                    $("#filterselect_subCatSearch").html(subCatHTML);
                }
            },
            error: function() {
                console.log('error');
            }
        });
    }

    //Get products by category and sub category ids (Ronak - 08/04-2022)
    function getProductsByCatSubCatId(categoriesArr, subCategoriesArr) {
        $.ajax({
            url: "{{ route('get-product-by-category-subcategory-ajax') }}",
            method: 'POST',
            data: {
                categoriesArr: categoriesArr,
                subCategoriesArr: subCategoriesArr,
                _token:"{{ csrf_token() }}"
            },
            success: function(successData) {
                //console.log(successData.products);
                if(successData.success == true && successData.products.length != 0) {
                    var productHTML = '';
                    $.each(successData.products, function(index, product) {
                        productHTML = productHTML + '<div class="form-check"><input class="form-check-input checkbox groupFilter product" name="groupFilter" type="checkbox" attr-name="'+product.productName+'" value="'+product.id+'" id="product'+product.id+'"><label class="form-check-label" for="product'+product.id+'">'+product.productName+'</label></div>';
                    });
                    $("#filterselect_productSearch").html(productHTML);
                }
            },
            error: function() {
                console.log('error');
            }
        });
    }

    //Side filters and main filter function
    function searchByFilters(paginate,categoriesArr,subCategoriesArr,productsArr,discountArr,daysRemainingArr,query,clearDiv=false) {
        //console.log('searchByFilters');
        //console.log(paginate,categoriesArr,subCategoriesArr,productsArr,discountArr,daysRemainingArr,query,clearDiv);
        // var query = $("#group_search").val();
        $.ajax({
            url: "{{ route('group-category-filter-ajax') }}",
            method: 'POST',
            data: {
                query:query,
                paginate:paginate,
                categoriesArr: categoriesArr,
                subCategoriesArr: subCategoriesArr,
                productsArr: productsArr,
                discountArr: discountArr,
                daysRemainingArr: daysRemainingArr,
                _token:"{{ csrf_token() }}"
            },
            //dataType:'json',
            success: function(result) {
                //console.log('paginate '+paginate, clearDiv);
                //console.log('final ajax response');
                //console.log(result.groups.length);
                if (result.groups.length != 0 && result.success == true) {
                    if(clearDiv){
                        $('#groupsTradingData').html('');
                    }

                    $('#groupsTradingData').append(result.returnHTML);
                // } else if(result.groups.length == 0 && query != '') {
                } else if(result.groups.length == 0 && clearDiv == true) {
                    $('#groupsTradingData').html('');
                    $('#groupsTradingData').html(result.returnHTML);
                    $('.loading').html("<img src='{{ URL::asset('front-assets/images/nogroup.png') }}' alt='No Group Available'>'<h5 class='text-danger'>No more records found</h5>");
                    // $('#groupsTradingData').html(result.returnHTML);
                } else {

                    // $('#groupsTradingData').html('');
                    // $('#groupsTradingData').html(result.returnHTML);
                    // $('.loading').html("<img src='{{ URL::asset('front-assets/images/nogroup.png') }}' alt='No Group Available'>'<h5 class='text-danger'>No more records found</h5>");
                }
            }
        });
    }

    $(document).on('click', '.shr_btn', function() {
        $('#social-btn-html').html($(this).attr('data-share-group-link-html'));
        $('#groupLink').val($(this).attr('data-share-group-link'));
        $('#exampleModal').modal('show');
    });

    $(document).on('click', '#copyBtn', function() {
        var copyText = document.getElementById("groupLink");
        copyText.select();
        navigator.clipboard.writeText(copyText.value);
        $(".copied").text("Copied to clipboard").show().fadeOut(2000);
    });


    //Buyer Notification (Arun's code)
    function loadUserActivityData() {
        $.ajax({
            url: "{{ route('dashboard-user-activity-ajax') }}",
            type: 'GET',
            success: function(successData) {
                //console.log("loaded")
                $('#userActivitySection').html(successData.userActivityHtml);
            },
            error: function() {
                console.log('error');
            }
        });
    }
</script>

@stop
@push('bottom_scripts')
@include('dashboard.payment.payment_tab_js')
@endpush
