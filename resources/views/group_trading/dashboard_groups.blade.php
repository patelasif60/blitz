<div id="group_section" class="row gx-4 mx-lg-0 group_section">
    @if(isset($groups) && count($groups) > 0)
    <!-- Group Section -->
    <div class="container-fluid m-0 p-0">
        <div class="header_top d-flex align-items-center pt-md-0">
            <h1 class="mb-0 ">{{ __('admin.groups') }}</h1>
            <!-- <a href="{{ route('dashboard') }}" class="btn btn-warning ms-auto btn-sm" style="padding-top: .1rem;padding-bottom: .1rem;">
                <img src="{{ URL::asset('front-assets/images/icons/angle-left.png') }}" alt="Back"> {{ __('profile.back') }}</a>
            </a> -->

            <form id="grouptrading_search" class="input-group mb-3 ms-auto" autocomplete="off">
                <input class="form-control form-control-sm" type="search" name="group_search" id="group_search" placeholder="{{ __('admin.type_to_search') }}" aria-label="Search">
                <button class="btn bg-dark text-white btn-sm search" type="button" id="groupSearchBtn">{{ __('dashboard.search') }}</button>
            </form>
        </div>
        <!-- Dynamic data will be load here -->
        <div class="row mb-3">
            <div id="groupsTradingDashboardData">
                <div class="row">
                    <!-- Groups Data will be show here -->
                </div>
            </div>
        </div>
        <!-- / End-->
    </div>
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

        function fetch_group_trading_data(query = '') {
            $.ajax({
                url: "{{ route('dashboard-list-groups-ajax') }}",
                method: 'POST',
                data: {
                    query: query,
                    _token:"{{ csrf_token() }}"
                },
                //dataType:'json',
                success: function(result) {
                    if (result.success == true) {
                        $('#groupsTradingDashboardData').html('');
                        $('#groupsTradingDashboardData').html(result.returnHTML);
                    }
                }
            });
        }

        //on click search button (25-03-22 Ronak)
        $(document).on('click', '#groupSearchBtn', function() {
            var query = $("#group_search").val();
            fetch_group_trading_data(query);
        });

        //On clear searched text, we will get all the data (25-03-22 Ronak)
        $('input[type=search]').on('search', function () {
            fetch_group_trading_data();
        });

        //Prevent further events (25-03-22 Ronak)
        $('input[type=search]').keypress(function (e) {
            if (e.keyCode === 10 || e.keyCode === 13) {
                if($("#group_search").val().length == 0) {
                    e.preventDefault();
                    fetch_group_trading_data(query = '');
                } else {
                    e.preventDefault();
                    fetch_group_trading_data($("#group_search").val());
                }
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
    });

</script>



