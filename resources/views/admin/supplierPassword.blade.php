<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    {{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>blitznet</title>
    <!-- base:css -->
    <link rel="stylesheet" href="{{ URL::asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/vendors/base/vendor.bundle.base.css') }}">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.buttons.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ URL::asset('assets/css/vertical-layout-light/style.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/admin/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon-32x32.png') }}" />
    <style>
        .content-wrapper{ background-color: #002050;}
        .mainloginbg{ background-image: url(../assets/images/admin_login_bg.jpg); background-repeat:no-repeat; background-position: center center; background-size: cover;}
        .auth .auth-form-light{background: transparent;}
        .auth form .form-group .form-control { padding: 1rem 0.5rem; border-radius: 4px;}
        .auth form .auth-form-btn{ background-color: #0151c8;}
        .mainheading{position: relative;}
        .mainheading::before, .mainheading::after{position: absolute; top: 9px;}
        .mainheading::before{ content:''; display: inline-block; width: 40%; height: 1px; background: rgb(255,255,255); background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%); left: 0;}
        .mainheading::after{ content:''; display: inline-block; width: 40%; height: 1px; background: rgb(255,255,255); background: linear-gradient(90deg, rgba(255,255,255,1) 0%, rgba(255,255,255,0) 100%); right: 0;}
    </style>
</head>

<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth p-0">
            <div class="row w-100 mx-0 h-100">
                <div class="col-lg-7 mainloginbg d-none d-lg-flex"></div>
                <div class="col-lg-5 d-flex align-items-center justify-content-center">
                    <div class="auth-form-light text-start py-5 px-md-4  w-75">
                        <div class="brand-logo text-center">
                            <img src="{{ URL::asset('assets/images/login_header_logo.jpg') }}" alt="Blitznet" class="w-auto mw-100">
                        </div>
                        <h6 class="fw-light d-none">Sign in to continue.</h6>
                        <h3 class="text-center text-white mb-5 mainheading" >Login</h3>
                        <form id="updatePassword" method="POST" action="{{ route('admin-change-password-update') }}" data-parsley-validate>
                            @csrf
                            <div class="form-group">
                                <input type="password" required class="form-control form-control-lg" name="oldpassword" placeholder="Current Password" style="background-color: #e8f0f8">
                            </div>
                            <div class="form-group">
                                {{--<input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password" style="background-color: #e8f0f8">--}}
                                <input type="password" required class="form-control form-control-lg" name="password" id="password" placeholder="New Password" style="background-color: #e8f0f8">
                            </div>
                            <div class="form-group">
                                {{--<input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password" style="background-color: #e8f0f8">--}}
                                <input type="password" required class="form-control form-control-lg" name="confirmpassword" placeholder="Confirm Password"  data-parsley-equalto="#password" style="background-color: #e8f0f8">
                            </div>

                            <div class="error">
                            </div>
                            <div class="mt-3 text-end">
                                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Update Password</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->
<!-- base:js -->
<script src="{{ URL::asset('assets/vendors/base/vendor.bundle.base.js') }}"></script>
<!-- endinject -->
<!-- inject:js -->
<script src="{{ URL::asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ URL::asset('assets/js/hoverable-collapse.js') }}"></script>
<script src="{{ URL::asset('assets/js/template.js') }}"></script>
<script src="{{ URL::asset('assets/js/settings.js') }}"></script>
<script src="{{ URL::asset('assets/js/todolist.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/parsley.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.buttons.js') }}"></script>
<script src="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.nonblock.js') }}"></script>
<script src="{{ URL::asset('assets/vendors/jquery-toast-plugin/jquery.toast.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/toastDemo.js') }}"></script>
<script>
    $("#updatePassword").on('submit',function(event){
        event.preventDefault();
        var formData = new FormData($("#updatePassword")[0]);
        if ($('#updatePassword').parsley().validate()) {

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data : formData,
                contentType: false,
                processData: false,

                success: function (r) {
                     new PNotify({
                            text: r.message,
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });
                    if(r.success){
                        setTimeout(function(){window.top.location= r.url} , 3000);
                    }
                }
            });

        }
    });

    resetToastPosition = function() {
        $('.jq-toast-wrap').removeClass('bottom-left bottom-right top-left top-right mid-center'); // to remove previous position class
        $(".jq-toast-wrap").css({
            "top": "",
            "left": "",
            "bottom": "",
            "right": ""
        }); //to remove previous position style
    }
</script>
<!-- endinject -->
</body>

</html>
