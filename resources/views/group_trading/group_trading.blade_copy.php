@extends('dashboard/layout/layout')

@section('content')

<div id="group_section" class="row gx-4 mx-lg-0 group_section">
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
                        <button type="button" class="btn btn-danger btn-sm ms-auto border" id="clearFilterBtn" style="font-size: 0.6rem;">Clear All</button>
                    </div>
                    <div class="col-lg-12 col-xl-12" id="groupFiltersSpace" ></div>
                    <div class="card-body">
                        <div>
                            <div class="pb-2">
                                <h6 class="filter_category">{{ __('admin.category') }}</h6>
                                <div class="form-check mb-1">
                                    <input class="form-check-input checkbox category" name="groupFilter" type="checkbox" attr-name="Steel" value="1" id="cat1">
                                    <label class="form-check-label" for="cat1">Steel</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input checkbox category" name="groupFilter" type="checkbox" attr-name="Yarn" value="2" id="cat2">
                                    <label class="form-check-label" for="cat2">Yarn</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input checkbox category" name="groupFilter" type="checkbox" attr-name="Wood" value="6" id="cat3">
                                    <label class="form-check-label" for="">Wood</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input checkbox category" name="groupFilter" type="checkbox" attr-name="Plastic" value="7" id="cat4">
                                    <label class="form-check-label" for="">Plastic</label>
                                </div>
                            </div>
                            <div class="pb-2">
                                <h6 class="filter_category">{{ __('admin.discount') }} %</h6>
                                <div class="form-check mb-1">
                                    <input class="form-check-input checkbox discountVal" name="groupFilter" type="checkbox" attr-name="5" value="5" id="discount1">
                                    <label class="form-check-label" for="discount1">5</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input checkbox discountVal" name="groupFilter" type="checkbox" attr-name="10" value="10" id="discount2">
                                    <label class="form-check-label" for="discount2">10</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input checkbox discountVal" name="groupFilter" type="checkbox" attr-name="15" value="15" id="discount3">
                                    <label class="form-check-label" for="discount3">15</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input checkbox discountVal" name="groupFilter" type="checkbox" attr-name="20" value="20" id="discount4">
                                    <label class="form-check-label" for="discount4">20</label>
                                </div>
                            </div>
                            <div class="pb-2">
                                <h6 class="filter_category">{{ __('dashboard.group_size') }}</h6>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="Categories" id="">
                                    <label class="form-check-label" for=""> Less than 10</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="Categories" id="">
                                    <label class="form-check-label" for=""> Less than 20</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="Categories" id="">
                                    <label class="form-check-label" for=""> Less than 30</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="Categories" id="">
                                    <label class="form-check-label" for=""> Less than 40</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="Categories" id="">
                                    <label class="form-check-label" for=""> Less than 50</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="Categories" id="">
                                    <label class="form-check-label" for=""> Less than 60</label>
                                </div>
                            </div>
                            <div class="pb-2">
                                <h6 class="filter_category">{{ __('dashboard.days_remaining')}}</h6>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="Categories" id="">
                                    <label class="form-check-label" for="">5 days remaining</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="Categories" id="">
                                    <label class="form-check-label" for=""> 10 days remaining</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="Categories" id="">
                                    <label class="form-check-label" for=""> 15 days remaining</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="Categories" id="">
                                    <label class="form-check-label" for=""> 20 days remaining</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="Categories" id="">
                                    <label class="form-check-label" for=""> 25 days remaining</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="Categories" id="">
                                    <label class="form-check-label" for=""> 30 days remaining</label>
                                </div>
                            </div>
                            <div class="pb-2 d-none">
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
        <div class="container-fluid m-0 p-0">
            <div class="header_top d-flex align-items-center">
                <h1 class="mb-0 ">{{ __('dashboard.group_trading') }}</h1>
                <a href="{{ route('dashboard') }}" class="btn btn-warning ms-auto btn-sm" style="padding-top: .1rem;padding-bottom: .1rem;">
                    <img src="{{ URL::asset('front-assets/images/icons/angle-left.png') }}" alt="Back"> {{ __('profile.back') }}</a>
                </a>
            </div>
            <form id="grouptrading_search" class="input-group mb-3" autocomplete="off">
                <input class="form-control form-control-sm" type="search" name="group_search" id="group_search" placeholder="{{ __('admin.type_to_search') }}" aria-label="Search">
                <button class="btn bg-dark text-white btn-sm search" type="button" id="groupSearchBtn">{{ __('dashboard.search') }}</button>
            </form>
            <div class="row mb-3">
                <div id="groupsTradingData">
                    <div class="row">
                        <!-- Groups Data will be show here -->
                    </div>
                </div>
            </div>
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
                    <a href="{{ route('home')}}" class="btn btn-primary">{{ __('order.back_to_home') }}</a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    $(document).ready(function() {
        fetch_group_trading_data();

        //Get group by search filter or all groups
        function fetch_group_trading_data(query = '') {
            $.ajax({
                url: "{{ route('group-trading-ajax') }}",
                method: 'POST',
                data: {
                    query: query,
                    _token:"{{ csrf_token() }}"
                },
                //dataType:'json',
                success: function(result) {
                    if (result.success == true) {
                        $('#groupsTradingData').html('');
                        $('#groupsTradingData').html(result.returnHTML);
                    }
                }
            });
        }

        //on click search button (25-03-22 Ronak)
        $(document).on('click', '#groupSearchBtn', function() {
            var query = $("#group_search").val();
            fetch_group_trading_data(query);
            $("input:checkbox[name=groupFilter]").prop('checked',false);
            group_filters = [];
        });

        //On clear searched text, we will get all the data (25-03-22 Ronak)
        $('input[type=search]').on('search', function () {
            fetch_group_trading_data();
        });

        //Prevent further events on enter key press (25-03-22 Ronak)
        $('input[type=search]').keypress(function (e) { 
            var key = e.which;
            if (event.keyCode === 10 || event.keyCode === 13) {
                event.preventDefault();
            }
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
            var productName = $("#productName_"+grpId).val();
            var grpUnitId = $("#unitId_"+grpId).val();

            localStorage.setItem('groupId', groupId);
            localStorage.setItem('category', grpCatId);
            localStorage.setItem('sub_category', grpSubCatId);
            localStorage.setItem('product', productName);
            localStorage.setItem('unit_post', grpUnitId);

            var isUserLogin = $("#is_user_login").val();
            if(isUserLogin == 1){
                window.location.replace("{{ route('get-a-quote') }}");
            } else {
                window.location.replace("{{ route('signin')}}");
            }
        });

        //On checkbox checked search groups (30-03-2022 Ronak)
        $('.checkbox').on('click',function() { 
            var group_filters = [];

            var categoriesArr = []
            var discountArr = [];

            $("#groupFiltersSpace").empty();
            $("input:checkbox[name=groupFilter]:checked").each(function() {
                $(this).prop('checked',true);
                group_filters.push($(this).val());
                $("#groupFiltersSpace").append("<button type='button' class='btn btn-white ms-auto border clearFilter category' style='font-size: 0.6rem;' value='"+$(this).val()+"'>" +$(this).attr('attr-name')+ " <span class='align-items-center'> <img src='{{ URL::asset('front-assets/images/icons/times-circle.png') }}' alt=''></span></button>")

                if($(this).hasClass('category')) {
                    // window.categoriesArr = function(){
                        var value = $(this).val();
                        categoriesArr('push',value);
                    // }
                    //categoriesArr.push($(this).val());
                } else {
                    // discountArr.push($(this).val());
                    categoriesArr('pop',$(this).val());

                }
            });
            console.log("Cat Array", categoriesArr);
            // console.log("Discount Array", discountArr);

            $("#filterApplyCount").text(group_filters.length);
        });

        //Clear All selected checkboxes on button click (30-03-2022 Ronak)
        $("#clearFilterBtn").click(function(){
            $("input:checkbox[name=groupFilter]").prop('checked',false);
            group_filters = [];
            fetch_group_trading_data();
            $("#groupFiltersSpace").empty();
            $("#filterApplyCount").text(0);
        });
    });
   
    //Clear Individual filter by click on cancle button (30-03-2022 Ronak)
    $(document).on('click', '.clearFilter', function() {
        $(this).remove();
        $("input:checkbox[name=groupFilter][value="+$(this).val()+"]").prop('checked',false);
        $("#filterApplyCount").text($("input:checkbox[name=groupFilter]:checked").length);

        if($(this).hasClass('category')) {
            // categoriesArr.pop($(this).val());
            categoriesArr('pop',$(this).val());
        }
        // } else {
        //     discountArr.pop($(this).val());
        // }
    });

    function categoriesArr(arrMode,filterValue) { alert(arrMode);
        if(arrMode == 'push') {
            categoriesArr.push(filterValue);
        } else {
            categoriesArr.pop($(this).val());
        }
    }

    

    
</script>

@stop
@push('bottom_scripts')
@include('dashboard.payment.payment_tab_js');
@endpush