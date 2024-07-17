<!-- msg -->
@can('publish buyer company info')
    @if(getUserWisePendingProfilePercentage(Auth::user()->id,Auth::user()->default_company) < 100)
        <div id="profile_percentage_message_ajex">
            <div class="toast align-items-center show w-100" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body flex-fill text-center py-1">
                        <span id="profile_percentage_message_ajex2">{{ __('dashboard.profile_percentage_message',['percentage' => getUserWisePendingProfilePercentage(Auth::user()->id,Auth::user()->default_company)]) }} </span><a href="javascript:void(0);" class="btn btn-warning btn-sm py-1" id="profileUpdateBtn"><small>{{__('dashboard.click_here_to_update')}}</small></a>
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
@endcan
<!-- msg end -->
<header class="newheadercolor">

    @php
        if(Auth::user()) {
            $langVal = session()->get('locale');
        } else {
            $langVal = session()->get('localelogin');
        }
    @endphp
    <input type="hidden" name="langValue" id="langValue" value="{{ $langVal }}">
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
                        <li><a class="dropdown-item video d-flex align-items-center" data-video-en="https://www.youtube.com/embed/3Nfd4LULfsU" data-video-id="https://www.youtube.com/embed/wtSv5nkMGlQ" data-video-title="{{ __('dashboard.how_to_place_rfq') }}" data-toggle="modal" data-target="#videoModal" href="javascript:void(0)"><img class="me-2" src="{{ URL::asset('front-assets/images/icons/play-button.png') }}" alt="" srcset="" height="16px">{{ __('dashboard.how_to_place_rfq') }}</a></li>

                        <li><a class="dropdown-item video d-flex align-items-center" data-video-en="https://www.youtube.com/embed/cKsYwpLAlYM" data-video-id="https://www.youtube.com/embed/Vmb0Yos8ETA" data-video-title="{{ __('dashboard.how_rfq_work') }}" data-toggle="modal" data-target="#videoModal" href="javascript:void(0)"><img class="me-2" src="{{ URL::asset('front-assets/images/icons/play-button.png') }}" alt="" srcset="" height="16px">{{ __('dashboard.how_rfq_work') }}</a></li>

                        <li><a class="dropdown-item video d-flex align-items-center" data-video-en="https://www.youtube.com/embed/d3sJ8HFiy2I" data-video-id="https://www.youtube.com/embed/TgeX487PMxo" data-video-title="{{ __('dashboard.how_to_place_order') }}" data-toggle="modal" data-target="#videoModal" href="javascript:void(0)"><img class="me-2" src="{{ URL::asset('front-assets/images/icons/play-button.png') }}" alt="" srcset="" height="16px">{{ __('dashboard.how_to_place_order') }}</a></li>

                        <li><a class="dropdown-item video d-flex align-items-center" data-video-en="https://www.youtube.com/embed/ueZ3OZK7Zzg" data-video-id="https://www.youtube.com/embed/rc59-Gtg9PQ" data-video-title="{{ __('dashboard.order_process') }}" data-toggle="modal" data-target="#videoModal" href="javascript:void(0)"><img class="me-2" src="{{ URL::asset('front-assets/images/icons/play-button.png') }}" alt="" srcset="" height="16px">{{ __('dashboard.order_process') }}</a></li>
                    </ul>
                </div>
                {{-- End --}}

                {{-- invite buyer and supplier--}}
                @if (Auth::user()->mobile_verified)
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
                @endif
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

{{--                <div class="btn-group notification_head" id="notification_head_icon">--}}
{{--                    <a type="button" class="btn btn-transparent dropdown-toggle none" id="userActivityBtn"--}}
{{--                        data-bs-toggle="dropdown" aria-expanded="false">--}}
{{--                        <img src="{{ URL::asset('front-assets/images/icons/icon_bell.png') }}"--}}
{{--                            alt="Account Updates">--}}
{{--                    </a>--}}
{{--                    <ul class="dropdown-menu dropdown-menu-end p-3 shadow-lg" id="userActivitySection">--}}
{{--                        --}}{{-- here userActivity are showing and data are fetching from ajax --}}
{{--                    </ul>--}}
{{--                </div>--}}
                @if (Auth::user()->mobile_verified)
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
                @endif
                @if (Auth::user())
                    <div class="btn-group ps-1 home_user">
                        <button type="button" class="btn  btn-sm  btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="
    border: 0;">
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
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel"></h5>
                <button type="button" class="btn-close" id="stopVideoNow" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="showVideoHere">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        </div>
    </div>
</div>
<!-- / End -->


<script>
    //Show Video Popup
    $(document).on("click", ".video", function () {
        let videoLink = ($("#langValue").val() == "en" ? $(this).attr("data-video-en") : $(this).attr("data-video-id"));
        let videoTitle = $(this).attr("data-video-title");
        let video = '<div class="embed-responsive embed-responsive-16by9">\
            <iframe id="buyerYoutube" class="embed-responsive-item" width="100%" height="500" src="'+videoLink+'" allowfullscreen></iframe>\
        </div>';
        $("#exampleModalToggleLabel").text(videoTitle);
        $('#showVideoHere').html(video);
        $("#videoModal").modal('show');
    });

    $(document).on("click", "#stopVideoNow", function () {
        $('#buyerYoutube').attr('src', '');
    });
    /*
    * @ekta
    * when click profile pending update button redirect to compnay ifornation
    */
    jQuery(document).ready(function(){
        SnippetProfilePercentage.init();

        let user_id = {{Auth::user()->id}};
        let company_id = {{Auth::user()->default_company}};

        let url = "{{ route('get-profile-pending-inputlist-ajax') }}";

        $.ajax({
            url: url,
            type: 'POST',
            data: { userId:user_id,companyId:company_id },
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function(response) {
                if (response) {
                    let field_id = response.data.field_ids;
                    $("#"+field_id).addClass('inputfocus');
                }
            }
        });
    });

    var SnippetProfilePercentage = function(){

        redirectToCompanyInfo = function(){

            $("#profileUpdateBtn").on('click', function(){
                sessionStorage.clear();
                sessionStorage.setItem("profile-lastlocation", JSON.stringify({
                    mainTab: "company-tab",
                    secondTab: "change_company_info"
                }));
                window.location='<?php echo e(route("profile")); ?>';
            });

        };

        return {
            init: function () {
                redirectToCompanyInfo()
            }
        }

    }(1);

</script>
