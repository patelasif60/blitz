@extends('dashboard/layout/layout')

@section('content')

        <div class="container-fluid profile_sub_section">
            <div class="row gx-4 mx-lg-0">
                @yield('content')
            </div>
        </div>
    </div>
</body>
<!-- JavaScript Bundle with Popper -->
<script src="{{ URL::asset('front-assets/js/front/bootstrap.bundle.min.js') }}"></script>
{{--<!-- <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> -->--}}
<script src="{{ URL::asset('/assets/vendors/datatables.net/1.10.25/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/parsley.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/fastselect.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.buttons.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.nonblock.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/front/jquery.smartWizard.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/front/owl.carousel.min.js') }}"></script>
{{--<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->--}}
<script src="{{ URL::asset('/assets/vendors/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/front/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/custom/app.js') }}"></script>

<script>
    // setInterval(function() {
    //     loadUserActivityData();
    // }, 5000);
    function userLogout(){
        sessionStorage.clear();
    }

    function setMainLocation() {
        let mainTab = '';
        $( "#mainTab li" ).each(function( index ) {
            let btn = $( this ).find('button');
            if(btn.hasClass('active')) {
                mainTab = btn.attr('id')
                return false;
            }
        });
        let tabId = 'myTab';
        let secondTab = 'change_personal_info';
        if(mainTab=="company-tab"){
            tabId = 'myTab1';
            secondTab = 'change_Preferences';
        }
        //console.log(mainTab);
        //console.log(tabId);
        let notActive = true;
        $( "#"+tabId+" li" ).each(function( index ) {
            let btn = $( this ).find('button');
            if(btn.hasClass('active')) {
                secondTab = $( this ).attr('id');
                notActive = false;
                return false;
            }
        });
        sessionStorage.setItem("profile-lastlocation", JSON.stringify({
            "mainTab": mainTab,
            "secondTab": secondTab
        }));
        return notActive;
    }

    function loadUserActivityData() {

        $.ajax({
            url: "{{ route('dashboard-user-activity-ajax') }}",
            type: 'GET',
            success: function(successData) {
                console.log("loaded")
                $('#userActivitySection').html(successData.userActivityHtml);
            },
            error: function() {
                console.log('error');
            }
        });
    }
    window.parsley.addValidator('email', {
        validateString: function(data) {

            var mobileReg = /^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$/;
            var emailReg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if(data.match(emailReg)){
                return true;
            }
            return false;

        },
        messages: {
            en: 'Invalid Email',
        }
    });
    window.parsley.addValidator('mobile', {
        validateString: function(data) {

            var mobileReg = /^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$/;
            var emailReg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if(data.match(mobileReg)){
                return true;
            }
            return false;

        },
        messages: {
        en: 'Invalid Number',
        }
    });
    $(document).on('click', '#userActivityBtn', function() {
        loadUserActivityData();
    });

    if (sessionStorage.getItem("profile-lastlocation")){
        let obj = JSON.parse(sessionStorage.getItem("profile-lastlocation"));
        //console.log(obj);
        $('#'+obj.mainTab).click();
        $('#'+obj.secondTab+' button').click();
    }else {
        $('#personal-tab').click();
        $('#change_personal_info button').click();
        $('#change_Preferences button').click();
        setMainLocation();
    }
    $(document).ready(function() {
        $(document).on('click', '#mainTab>li>button', function(e) {
            if(setMainLocation()){
                let obj = JSON.parse(sessionStorage.getItem("profile-lastlocation"));
                $('#'+obj.secondTab+' button').click();
            }
        });
    });
</script>
@stop
@push('bottom_scripts')
    @include('dashboard.payment.payment_tab_js')
@endpush
