<header class="newheadercolor">
    <div class="px-3">
        <div class="row">
            <div class="col-auto p-3 py-2">
                <a href="{{ route('home') }}"><img
                        src="{{ URL::asset('front-assets/images/front/header-logo.png') }}" alt="Blitznet"></a>
                <button class="btn btn-primary d-lg-none" type="button" data-bs-toggle="collapse"
                    data-bs-target="#userinfo" aria-expanded="true" aria-controls="userinfo">
                    <img src="{{ URL::asset('front-assets/images/icons/icon_navbar.png') }}" alt="Nav">
                </button>
            </div>
            <div class="col-auto ms-auto p-3 py-2">
                {{-- Video Section --}}
                <div class="btn-group me-3 video_tutorial">
                    <button type="button" class="btn text-white dropdown-toggle" style="min-width: inherit;" data-bs-toggle="dropdown" aria-expanded="false" data-bs-placement="left" data-toggle="tooltip" title="{{ __('dashboard.guide') }}">
                        <img src="{{ URL::asset('front-assets/images/icons/icon_video.png') }}" height="24px" alt="" srcset="">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width: inherit;">
                        <li><a class="dropdown-item video d-flex align-items-center" data-video="{{ URL::asset('front-assets/videos/02_Post_RFQ.mp4') }}" data-video-title="{{ __('dashboard.how_to_place_rfq') }}" data-toggle="modal" data-target="#videoModal" href="javascript:void(0)"><img class="me-2" src="{{ URL::asset('front-assets/images/icons/play-button.png') }}" alt="" srcset="" height="16px">{{ __('dashboard.how_to_place_rfq') }}</a></li>

                        <li><a class="dropdown-item video d-flex align-items-center" data-video="{{ URL::asset('front-assets/videos/03_RFQ_Page_1.mp4') }}" data-video-title="{{ __('dashboard.how_rfq_work') }}" data-toggle="modal" data-target="#videoModal" href="javascript:void(0)"><img class="me-2" src="{{ URL::asset('front-assets/images/icons/play-button.png') }}" alt="" srcset="" height="16px">{{ __('dashboard.how_rfq_work') }}</a></li>

                        <li><a class="dropdown-item video d-flex align-items-center" data-video="{{ URL::asset('front-assets/videos/03_RFQ_Page_2.mp4') }}" data-video-title="{{ __('dashboard.how_to_place_order') }}" data-toggle="modal" data-target="#videoModal" href="javascript:void(0)"><img class="me-2" src="{{ URL::asset('front-assets/images/icons/play-button.png') }}" alt="" srcset="" height="16px">{{ __('dashboard.how_to_place_order') }}</a></li>

                        <li><a class="dropdown-item video d-flex align-items-center" data-video="{{ URL::asset('front-assets/videos/04_Order_process.mp4') }}" data-video-title="{{ __('dashboard.order_process') }}" data-toggle="modal" data-target="#videoModal" href="javascript:void(0)"><img class="me-2" src="{{ URL::asset('front-assets/images/icons/play-button.png') }}" alt="" srcset="" height="16px">{{ __('dashboard.order_process') }}</a></li>
                    </ul>
                </div>
                {{-- End --}}

                {{-- invite buyer and supplier--}}
                <div class="btn-group invite_front_section">
                    @can('create buyer side invite')
                        <a class="btn btn-light btn-sm d-flex align-items-center" data-bs-toggle="collapse" href="#collapseinvite"
                        role="button" aria-expanded="false" aria-controls="collapseinvite">
                            <span class="me-1"><img src="{{ URL::asset('front-assets/images/icons/icon_invite_user.png') }}" alt="" width="13px"></span>
                            <span class="d-none d-md-inline-block" id="">{{ __('admin.invite') }}</span>
                        </a>
                    @endcan

                    <div class="collapse invite_front_form shadow-lg navbar-collapse" id="collapseinvite">
                        <div class="card card-body">
                            <h6><small>{{ __('admin.invite_your_friends_supplier') }}</small></h6>
                            <div class="">
                                <form id="inviteSupplierFormHeader" enctype="multipart/form-data" class="form-group row g-3" data-parsley-validate>
                                    @csrf
                                <div class="input-group mb-3">
                                    <div class="flex-grow-1">
                                        <input type="email" class="form-control form-control-sm py-2" name="user_email" id="user_email" placeholder="Email"
                                               aria-label="Email" aria-describedby="button-addon2" data-parsley-uniqueemailsupplier required>
                                    </div>
                                    <select class="form-select form-select-sm py-2" name="role_id" id="role_id">
                                        <option value="2">{{ __('admin.as_a_buyer') }}</option>
                                        <option value="3">{{ __('admin.as_a_supplier') }}</option>
                                    </select>
                                </div>
                                    <div class="d-flex align-items-center">

                                        @can('publish buyer side invite')
                                        <a href="javascript:void(0)" class="text-decoration-none invitelist" onclick="redirectToInvite()">{{ __('admin.invite_list') }}</a>
                                        @endcan
                                        <button type="button" class="btn btn-primary btn-sm d-flex align-items-center ms-auto" id="saveInviteSupplierHeaderBtn">
                                            <span class="me-1"><img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}" alt="Save Changes" width="13px"></span>{{ __('admin.invite') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('group-trading') }}" class="btn btn-warning btn-sm mx-2">
                    <span class="me-1"><img src="{{ URL::asset('front-assets/images/icons/group.png')}}" alt=""></span>
                    <span class="d-none d-md-inline-block" id="gt_btn">{{ __('dashboard.group_trading')}}</span>
                </a>

                <div class="btn-group home_lenguage">

                    <button type="button" class="btn text-white dropdown-toggle" style="min-width: inherit;"
                        data-bs-toggle="dropdown" aria-expanded="false">

                        {{ strtoupper(str_replace('_', '-', app()->getLocale())) }}

                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width: inherit;">
                        <li><a class="dropdown-item" href="{{url('lang/id')}}">ID</a></li>
                        <li><a class="dropdown-item" href="{{url('lang/en')}}">EN</a></li>
                    </ul>

                </div>

                <div class="btn-group notification_head1" id="notification_head1_icon">
                    <a type="button" class="btn btn-transparent dropdown-toggle none" id="userActivityBtn" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ URL::asset('front-assets/images/icons/icon_bell.png') }}" alt="Account Updates">
                    </a>
                    <div id="showCounterNotification">
                        @if(isset($userNotification) && !empty($userNotification))
                            <div class="counter">{{ $userNotification }}</div>
                        @endif
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end p-2 shadow-lg" id="userActivitySection" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 36px);" data-popper-placement="bottom-end">
                    </ul>
                </div>

                @if (Auth::user())
                    <div class="btn-group ps-1 home_user">
                        <button type="button" class="btn  btn-sm  btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="border: 0;">
                            <img src="{{ URL::asset('front-assets/images/front/icon_user.png') }}" width="18px" alt="user">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" type="button" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="bg-light"><a class="dropdown-item text-danger" type="button" href="{{ route('logout') }}">{{ __('admin.logout') }}</a></li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</header>


 <!-- Video Modal -->
 <div class="modal fade" id="videoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel"></h5>
                <button type="button" class="btn-close" id="stopVideoNow" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="showVideoHere">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <video width="100%" controls autoplay>
                    <source src="" id="rm1" type="video/mp4">
                </video>
            </div>
        </div>
    </div>
</div>
<!-- / End -->


<script>
    //Show Video Popup
    $(document).on("click", ".video", function () {
        let videoLink = $(this).attr("data-video");
        let videoTitle = $(this).attr("data-video-title");
        let video = '<video controls="" width="100%"><source src="'+videoLink+'" id="rm1" type="video/mp4"></video>';
        $("#exampleModalToggleLabel").text(videoTitle);
        $('#showVideoHere').html(video);
        $("#videoModal").modal('show');
    });

    $(document).on("click", "#stopVideoNow", function () {
        $('video').trigger('pause');
    });
    function loadDefaultData() {

        $.ajax({
            url: '{{ route('dashboard-default-ajax') }}',
            type: 'GET',
            success: function(successData) {
                defaultData = successData.html;
                $('#userActivitySection').html(successData.userActivityHtml);
            },
            error: function() {
                console.log('error');
            }
        });

    }

    $(document).on('click', '.userActivityPageRedirect', function(e) {
        e.preventDefault();
        let id = $(this).attr('data-id');
        let type = $(this).attr('data-type');
        e.stopImmediatePropagation();
        redirectTo(type,id);
    })

    //@ekta Redirect to invite supplier list view
    function redirectToInvite(){
        sessionStorage.clear();
        sessionStorage.setItem("profile-lastlocation", JSON.stringify({
            mainTab: "company-tab",
            secondTab: "invite_supplier_list"
        }));
        window.location='<?php echo e(route("profile")); ?>';
    }

    //@ekta invite supplier and friend
    $(document).on('click', '#saveInviteSupplierHeaderBtn', function(e) {
        window.Parsley.addValidator('uniqueemailsupplier', {
            validateString: function (value) {
                let res = false;
                xhr = $.ajax({
                    url: '{{ route('check-invite-user-email-exist') }}',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        email: value,
                    },
                    async: false,
                    success: function (data) {
                        res = data;
                    },
                });
                return res;
            },
            messages: {
                en: '{{ __('admin.email_already_exist') }}'
            }
        });

        e.preventDefault();
        if ($('#inviteSupplierFormHeader').parsley().validate()) {
            var formData = new FormData($('#inviteSupplierFormHeader')[0]);
            $.ajax({
                url: "{{ route('profile-invite-supplier-ajax') }}",
                data: formData,
                type: 'POST',
                contentType: false,
                processData: false,

                success: function(successData) {
                    if(successData) {
                        $('.navbar-collapse').collapse('hide');
                        new PNotify({
                            text: "{{ __('admin.invite_buyer_send_alert') }}",
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 2000
                        });
                        //$('#collapseinvite').modal('hide');
                        $('#inviteSupplierFormHeader').parsley().reset();

                        setTimeout(function () {
                            location.reload(true);
                        }, 2000);
                    }
                },
                error: function() {
                    console.log('error');
                }
            });
        }
    });

</script>
