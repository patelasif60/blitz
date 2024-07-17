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

                                <!-- Add New Role with permission -->

                                <div class="col-md-12 addRoleSection p-4 mt-md-2 ">
                                    <form id="buyerRolePermissionForm" method="@if(isset($role)) PUT @else POST @endif" action="@if(isset($role)) {{ route('settings.roles.update', $encryptedRoleId) }} @else {{ route('settings.roles.store') }} @endif">
                                        @csrf

                                        <div class="d-flex align-items-center">
                                            <h5>@if(isset($role)) {{ __('profile.edit_role') }} @else {{ __('profile.add_role') }} @endif</h5>
                                            <button type="button" data-boolean="false" class="btn-close ms-auto d-none" id="" onclick="" aria-label="Close"></button>
                                        </div>
                                        <div class="mb-3 w-100 floatlables" style="background-color: #efefef; border-radius: 8px;">
                                            <div class="d-flex align-items-center col-md-6 p-3">
                                                <div class="">
                                                    <label for="exampleFormControlInput1" class="form-label mb-0" style="left: 8px; top: -7px;">{{__('profile.role')}} <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control input-alpha-numeric max-255" id="roleName" name="roleName" value="@if(isset($role)){{ $role->name }}@else{{ old('name') }}@endif" placeholder="{{ __('validation.custom.roleName.placeholder') }}" >
                                                    <span id="roleNameError" class="text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <span id="rolePermissionError" class="text-danger"></span>
                                        <div class="row mx-0 permission_head border border-bottom-0 bg-light px-2">
                                            <div class="col-md-1 my-2 fw-bold">
                                                <div class="form-check form-switch ms-1">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="selectAllModuleSwitch" >
                                                </div>
                                            </div>
                                            <div class="col-md-3 my-2 fw-bold">{{ __('profile.modules') }}</div>
                                            <div class="col-md-8 my-2 fw-bold">{{ __('profile.permissions') }}</div>
                                        </div>
                                        @foreach($permissionGroup as $group)
                                        <div class="row mx-0 d-flex align-items-center border-bottom-0 permission_list border p-2">
                                            <div class="col-md-1 my-2 fw-bold">
                                                <div class="form-check form-switch ms-1">
                                                    <input class="form-check-input selectModule" type="checkbox" role="switch" id="permissionGroup{{$group->id}}" data-id="{{$group->id}}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <span class="fw-bold" style="font-size: 0.875rem">{{ __('profile.module.'.$group->display_name) }} </span>
                                            </div>
                                            <div class="col-md-8 d-flex align-items-center flex-wrap" id="permissionBadgeDiv{{$group->id}}">
                                                @foreach($group->children() as $subGroup)
                                                    <div class="position-relative alerthover mb-1">
                                                        <div class="permissiondiv alert py-0 p-1 mb-0 me-2 @if(isset($role)) @if(!arr_compare(json_decode($subGroup->permissions), $role->permissions)) d-none @endif @else d-none @endif  {{ $subGroup->class_name }} permissionMainGroup{{$group->id}}" data-group="{{ $subGroup->id }}">{{ __('profile.permission.'.$subGroup->display_name)}}<span class="alert_hover position-absolute top-0 start-90 translate-middle badge rounded-pill bg-danger"> <a class="text-white text-decoration-none remove-badge" data-group="{{ $subGroup->id }}" href="#" type="button">x</a></span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <div class="position-relative ms-2">
                                                    <a class="permission-btn" id="addPermAnc{{$group->id}}" data-bs-toggle="collapse" href="#collapse{{$group->id}}" role="button" aria-expanded="false" aria-controls="collapse{{$group->id}}">
                                                        <span class="border-0"><i class="fa fa-plus" style="font-size: 1rem; color: blue;"></i></span>
                                                    </a>
                                                    <div class="collapse shadow border collapsecontentrole" id="collapse{{$group->id}}">
                                                        <div class="p-3 py-2">
                                                            @foreach($group->children() as $subGroup)
                                                            <div class="form-check d-flex align-items-center">
                                                                <input class="form-check-input role-check permissionGroup{{$group->id}}" type="checkbox" value="{{ $subGroup->permissions }}" id="rolePermission" name="rolePermission[]" data-permission="{{ $subGroup->id }}" data-group="{{ $subGroup->id }}" @if(isset($role)) @if(arr_compare(json_decode($subGroup->permissions), $role->permissions)) checked @endif @endif>
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
                                            <button type="submit" class="btn btn-primary me-2 btn-submit-role" id="saveRolePermission"> @if(isset($role)) {{ __('admin.update') }} @else {{ __('admin.add')}} @endif</button>
                                            <button type="button" class="btn btn-secondary ps-2" data-bs-target="{{ route('settings.roles.index') }}">{{ __('admin.cancel')}}</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Add New Role with permission -->
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

    <!-- Get count of approver (approval buyer approval configurations) permission -->
    <input type="hidden" name="approverPermissionData" id="approverPermissionData" value="{{ getCompanyWiseApproverPermission() }}" />
    <!-- End -->

    @include('buyer.common.footer.backend.footer')

    <script>

        var SnippetCreateRolePermissions = function () {

            var storeRolePermissions = function () {
                $('.btn-submit-role').on("click", function(e) {
                    e.preventDefault();
                    $('.btn-submit-role').attr("disabled", true);

                    storeUpdate(); // Role Permission store/update

                });
            },

            storeUpdate = function (approverToggle = null) {

                var isToggleOn = approverToggle!=false ? isApprovalToggleOn() : false;  //If true then showPopup else do not show popup

                let formData;

                formData   =   $('#buyerRolePermissionForm').serializeArray();

                formData.push({ name: "isToggleOn", value: isToggleOn });

                let method      =   $('#buyerRolePermissionForm').attr('method').replace(/\s/g, '');
                let action      =   $('#buyerRolePermissionForm').attr('action');

                $.ajax({
                    url: action,
                    data: formData,
                    type: method,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(data) {

                        //Call swalApproversInfo if "no approvers found"
                        if(data.success == true && data.response == false) {

                            swalApproversInfo();

                        } else if(data.success == true) {

                            new PNotify({
                                text: data.message,
                                type: data.success ? 'success' : 'error',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 2500
                            });

                            if (!data.success) {
                                $('.btn-submit-role').attr("disabled", false);
                            }

                            setTimeout(function () {
                                if (data.success) {
                                    location.replace("{{route('settings.roles.index')}}");
                                }
                            }, 3000);

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

                        $('.btn-submit-role').attr("disabled", false);

                    },
                });
            },

            removeAllErrors = function(){
                $('#buyerRolePermissionForm span.text-danger').html('');
            },

            removeSingleError = function(){

                $('input[type="text"]').on('click', function(){
                    if($('#'+$(this).attr('name')+'Error').length != 0) {
                        $('#'+$(this).attr('name')+'Error').html('');
                    }
                });

                $('input').on('keyup', function(){
                    $('#'+$(this).attr('name')+'Error').html('');
                });

                $('input').on('change', function(){
                    $('#'+$(this).attr('id')+'Error').html('');
                });

                $('#selectAllModuleSwitch').on('change', function(){
                    $('#rolePermissionError').html('');
                });

                $('.selectModule').on('change', function(){
                    $('#rolePermissionError').html('');
                });


            },

            permissionTags = function() {

                $('.form-check-input.role-check').on('click', function () {

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

                    manageSelectModuleSwitch();
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

                    manageSelectModuleSwitch();
                });
            },

            cancel = function () {

                $('.btn-secondary').on('click', function () {
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

            },

            isApprovalToggleOn = function () {

                var flatArray = SnippetApp.ArrayCombine($("#buyerRolePermissionForm input[name='rolePermission[]']:checked"));

                if(jQuery.inArray(317, flatArray) !== -1) { //317 id of Get Approval Permission
                    return true;
                }

                return false;
            },

            swalApproversInfo = function (){

                if(parseInt($('#approverPermissionData').val()) == 0) {
                    swal({
                        text: "{{ __('profile.no_approver_validation') }}",
                        icon: "/assets/images/info.png",
                        buttons: '{{ __('admin.ok') }}',
                        dangerMode: false,
                    }).then((changeit) => {
                        if (changeit) {
                            storeUpdate(false);
                        }
                    });
                }
            },

            getDependencyPermission = function () {
                $('.form-check-input.role-check').on('click', function () {
                    var permissionGroup = $(this).attr('data-permission');
                    $.ajax({
                        url: "{{ route('buyer.has.dependent.permission') }}",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {id:permissionGroup},
                        type: 'POST',
                        success: function (response) {
                            if (response.success && response.data!=null) {
                                setDependencyPermission(response.data);
                            }
                        },
                        error: function (response) {
                            SnippetApp.swal.critical("Uhh ohh !!", response.message,{{__('admin.ok')}});
                        }
                    })
                });
            },

            setDependencyPermission = function (data) {
                if (data!=null) {
                    data.map(function (permission) {
                        var query = document.querySelector('[data-permission="'+permission+'"]');
                        if (!query.checked) {
                            query.click();
                        }
                    })
                }
            };

            return {
                init: function () {
                    storeRolePermissions(),
                    permissionTags(),
                    removePermissionTag(),
                    removeSingleError(),
                    cancel(),
                    rolesPermissionTab(),
                    getDependencyPermission()
                }
            }

        }(1);

        jQuery(document).ready(function () {
            SnippetCreateRolePermissions.init();
        });

        // select permission of all modules
        $('#selectAllModuleSwitch').on('click', function(e){

            if ($(this).is(':checked')) {
                $('.selectModule').prop('checked', true);
                $('.role-check').prop('checked', true);
                $('.permissiondiv').removeClass('d-none');
                $('.permission-btn').addClass('d-none');
            }else{
                $('.selectModule').prop('checked', false);
                $('.role-check').prop('checked', false);
                $('.permissiondiv').addClass('d-none');
                $('.permission-btn').removeClass('d-none');
            }
        });
        // select permission of all modules

        // select permission of single module
        $('.selectModule').on('click', function(e){
            let permissionGroupId = $(this).attr('id');
            let id = $(this).attr('data-id');
            if ($(this).is(':checked')) {
                $('.'+permissionGroupId).prop('checked', true);
                $('.permissionMainGroup'+id).removeClass("d-none");
                $('#addPermAnc'+id).addClass("d-none");
            }else{
                $('.'+permissionGroupId).prop('checked', false);
                $('.permissionMainGroup'+id).addClass("d-none");
                $('#addPermAnc'+id).removeClass("d-none");
            }

            manageSelectAllModuleSwitch();
        });
        // select permission of single module

        function manageSelectAllModuleSwitch(){
            let uncheckedModule = $(".selectModule:not(:checked)").length;
            if(uncheckedModule > 0){
                $('#selectAllModuleSwitch').prop('checked', false);
            }else{
                $('#selectAllModuleSwitch').prop('checked', true);
            }
        }

        manageSelectModuleSwitch();

        // get selected switch on load
        function manageSelectModuleSwitch(){
            $('.selectModule').each(function(e){
                let permissionGrpId = 'permissionGroup'+$(this).attr('data-id');
                let uncheckedPermissionGroups = $('.'+permissionGrpId+':not(:checked)').length;
                if(uncheckedPermissionGroups > 0){
                    $('#permissionGroup'+$(this).attr('data-id')).prop('checked', false);
                    $('#addPermAnc'+$(this).attr('data-id')).removeClass('d-none');
                }else{
                    $('#permissionGroup'+$(this).attr('data-id')).prop('checked', true);
                    $('#addPermAnc'+$(this).attr('data-id')).addClass('d-none');
                }

                manageSelectAllModuleSwitch();
            });
        }
        $(document).ready(function(){
            $('div .permission_list').last().removeClass('border-bottom-0');
        });
        // get selected switch on load

    </script>

@stop
