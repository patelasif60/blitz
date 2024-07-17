@extends('buyer/layouts/backend/backend_layout')

    @section('content')
    <div class="col-lg-12 py-2">
        <div class=" border border-radius">
            <div class="tab-content" id="myTabContent">
                <!--begin: Profile Section-->
                <div class="tab-pane fade show " id="personal" aria-labelledby="personal-tab">
                    <div class="row mx-0">
                        @include('buyer/common/sidebar/backend/profile_sidebar')
                    </div>
                </div>
                <!--end: Profile Section-->

                <!--begin: Admin Settings Section-->
                <div class="tab-pane fade show " id="company" role="tabpanel" aria-labelledby="company-tab">
                    <div class="row mx-0">
                        @include('buyer/common/sidebar/backend/settings_sidebar')
                        <div class="col-md-8 col-lg-9">
                            <div class="tab-content" id="myTabContent">

                                <!-- User Role and permission -->
                                <div class="col-md-12 addRoleSection p-4 mt-md-2 ">
                                    <form id="buyerRolePermissionForm" method="POST" action="{{ route('settings.users.permission.update') }}">
                                        @csrf

                                        <div class="d-flex align-items-center mb-3 w-100">
                                            <h5>{{ __('profile.edit_permission_for').' '.$user->fullname }}</h5>
                                            <div class="ms-auto col-md-2 border role-name" style="font-size: 0.9rem; padding: 5px; font-weight: bold; border-radius: 8px;" data-role="@if(!empty($role)){{\Crypt::encrypt($role->id)}}@endif" data-permission="@if(!empty($customPermission)){{\Crypt::encrypt($customPermission->id)}}@endif">
                                                @if(isset($role)){{ $role->name }}@else Custom @endif</div>
                                        </div>

                                        <span id="rolePermissionError" class="text-danger"></span>
                                        <div class="row mx-0 permission_head border border-bottom-0 bg-light">
                                            <div class="col-md-3 my-2 fw-bold">{{ __('profile.modules') }}</div>
                                            <div class="col-md-9 my-2 fw-bold">{{ __('profile.permissions') }}</div>
                                        </div>
                                        @foreach($permissionGroup as $group)
                                        <div class="row mx-0 permission_list mt-1 border p-2">
                                            <div class="col-md-3">
                                                <span class="fw-bold" style="font-size: 0.875rem">{{ __('profile.module.'.$group->display_name) }}</span>
                                            </div>
                                            <div class="col-md-9 d-flex align-items-center">
                                                @foreach($group->children() as $subGroup)
                                                    <div class="position-relative alerthover">
                                                        <div class="permissiondiv alert py-0 p-1 mb-0 me-2 @if(!empty($permissions)) @if(!arr_compare(json_decode($subGroup->permissions), $permissions)) d-none @endif @else d-none @endif  {{ $subGroup->class_name }}" data-group="{{ $subGroup->id }}">
                                                            {{ __('profile.permission.'.$subGroup->display_name) }}
                                                            <span class="alert_hover position-absolute top-0 start-90 translate-middle badge rounded-pill bg-danger">
                                                                <a class="text-white text-decoration-none remove-badge" data-group="{{ $subGroup->id }}" href="#" type="button">x</a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <div class="position-relative ms-2">
                                                    <a class="permission-btn" data-bs-toggle="collapse" href="#collapse{{$group->id}}" role="button" aria-expanded="false" aria-controls="collapse{{$group->id}}">
                                                        <span class="border-0 " style=""><i class="fa fa-plus " style="font-size: 1rem; color: #0000ff;"></i></span>
                                                    </a>
                                                    <div class="collapse shadow border collapsecontentrole" id="collapse{{$group->id}}">
                                                        <div class="p-3 py-2">
                                                            @foreach($group->children() as $subGroup)
                                                            <div class="form-check d-flex align-items-center">
                                                                <input class="form-check-input" type="checkbox" value="{{ $subGroup->permissions }}" id="rolePermission" name="rolePermission[]" data-group="{{ $subGroup->id }}" @if(!empty($permissions)) @if(arr_compare(json_decode($subGroup->permissions), $permissions)) checked @endif @endif>
                                                                <label class="form-check-label ps-2" style="font-size: 12px !important; font-weight: bold !important; font-family: 'europaNuova_b';" for="flexCheckDefault">{{ __('profile.permission.'.$subGroup->display_name) }}</label>
                                                            </div>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="d-flex justify-content-end mt-2">
                                            <button type="submit" class="btn btn-sm btn-success me-2 btn-submit-role" id="saveRolePermission"> <img src="{{\URL::asset('front-assets/images/icons/icon_post_require.png') }}" alt="Post Requirement" class="pe-1" height="12px"> {{ __('admin.save') }}</button>
                                            <button type="button" class="btn btn-secondary ps-2" data-bs-target="{{ route('profile') }}">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- User Role and permission -->
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>

    </div>
    </div>
    </div>
    </div>
    </div>
    @include('buyer.common.footer.backend.footer')

    <script>

        var SnippetCreateRolePermissions = function () {

            var storeRolePermissions = function () {
                $('.btn-submit-role').on("click", function(e) {
                    e.preventDefault();
                    let formData;
                    let segment             = window.location.href.split( '/' ).slice(-2)[0];
                    let role_segment        = $('.role-name').attr('data-role');
                    let permission_segment  = $('.role-name').attr('data-permission');


                    formData   =   $('#buyerRolePermissionForm').serializeArray();
                    formData.push({ name: "segment", value: segment });
                    formData.push({ name: "roleSegment", value: role_segment });
                    formData.push({ name: "permissionSegment", value: permission_segment });

                    let method      =   $('#buyerRolePermissionForm').attr('method').replace(/\s/g, '');
                    let action      =   $('#buyerRolePermissionForm').attr('action');

                    $.ajax({
                        url: action,
                        data: formData,
                        type: method,
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(data) {

                                new PNotify({
                                    text: data.message,
                                    type: data.success ? 'success' : 'error',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 2500
                                });

                                setTimeout(function () {
                                    if (data.success) {
                                        SnippetBuyerBackendSettingSidebar.setSessionGlobal('company-tab','invite_user');
                                        location.replace("{{route('profile')}}");
                                    }
                                }, 3000);

                        },
                        error: function(data) {
                            if( data.status === 422 ) {

                                removeAllErrors();

                                $('html, body').animate({
                                    scrollTop: ($('.text-danger').offset().top - 300)
                                });
                                var errors = $.parseJSON(data.responseText);
                                var cursorPoint;
                                $.each(errors, function (key, value) {
                                    if($.isPlainObject(value)) {
                                        $.each(value, function (key, value) {
                                            cursorPoint = key;
                                            return false;
                                        });
                                    }
                                });

                                $('input[name='+cursorPoint+']').focus();

                                $.each(errors, function (key, value) {

                                    if($.isPlainObject(value)) {
                                        $.each(value, function (key, value) {
                                            $('#'+key+'Error').html(value);
                                        });
                                    }
                                });


                            } else {
                                new PNotify({
                                    text: "{{ __('profile.something_went_wrong') }}",
                                    type: 'error',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 1000
                                });
                            }

                        },
                    });
                });
            },

            removeAllErrors = function(){
                $('#buyerRolePermissionForm span.text-danger').html('');
            },

            removeSingleError = function(){

                $('input').on('click', function(){
                    $('#'+$(this).attr('name')+'Error').html('');
                });

                $('input').on('keyup', function(){
                    $('#'+$(this).attr('name')+'Error').html('');
                });

                $('input').on('change', function(){
                    $('#'+$(this).attr('id')+'Error').html('');
                });

            },

            permissionTags = function() {

                $('.form-check-input').on('click', function () {

                    let checkedPermission = $(this);

                    $('.permissiondiv').each(function () {

                        if ($(this).attr('data-group') == checkedPermission.attr('data-group')) {

                            if(checkedPermission.prop('checked') == true){
                                $(this).removeClass('d-none');
                            } else {
                                $(this).addClass('d-none');
                            }
                        }

                    });

                });

            },

            removePermissionTag = function () {

                $('.remove-badge').on('click', function (e) {
                    e.preventDefault();

                    let removeBadge = $(this);

                    $('.form-check-input').each(function () {

                        if ($(this).attr('data-group') == removeBadge.attr('data-group')) {
                            $(this).prop('checked', false);
                        }

                    });

                    $('.permissiondiv').each(function () {

                        if ($(this).attr('data-group') == removeBadge.attr('data-group')) {

                            $(this).addClass('d-none');

                        }

                    });

                });
            },

            cancel = function () {

                $('.btn-secondary').on('click', function () {
                    sessionStorage.clear();
                    sessionStorage.setItem("profile-lastlocation", JSON.stringify({
                        mainTab: "personal-tab",
                        secondTab: "invite_supplier_list"
                    }));

                    location.replace($(this).attr('data-bs-target'));
                });

                $(document).mouseup(function()
                {
                    $('.collapsecontentrole').each(function(e){
                        if (!$(this).is(e.target) && $(this).has(e.target).length === 0) {
                            $(this).collapse('hide');
                        }
                    });

                });


            },

            rolesPermissionTab = function() {

                $('#rolestabs-tab').on('click', function(e){
                    e.preventDefault();
                    location.replace($(this).attr('data-target'));
                });

            };



            return {
                init: function () {
                    storeRolePermissions(),
                    permissionTags(),
                    removePermissionTag(),
                    removeSingleError(),
                    cancel(),
                    rolesPermissionTab()
                }
            }

        }(1);

        jQuery(document).ready(function () {
            SnippetCreateRolePermissions.init();
            var segments = window.location.href.split( '/' ).slice(-2)[0];


        });

    </script>

@stop
