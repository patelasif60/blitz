@extends('buyer/layouts/backend/backend_layout')

    @section('css')
        <link href="{{ URL::asset('front-assets/js/front/crop/css/style.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/js/front/crop/css/style-example.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/js/front/crop/css/jquery.Jcrop.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('front-assets/css/front/croppie.min.css') }}" rel='stylesheet' />
        <link href="{{ URL::asset('front-assets/js/datatables-bs4/dataTables.bootstrap4.css') }}" rel='stylesheet' />
        <link href="{{ URL::asset('front-assets/css/front/style_role.css') }}" rel="stylesheet">
    @endsection

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
                <div class="tab-pane fade show" id="company" role="tabpanel" aria-labelledby="company-tab">
                    <div class="row mx-0">
                        @include('buyer/common/sidebar/backend/settings_sidebar')
                        <div class="col-md-8 col-lg-9">
                            <div class="tab-content" id="myTabContent">

                                 <!--begin: Profile Tab Users listing -->
                                 <div class="tab-pane fade active show" id="rolestabs" role="tabpanel" aria-labelledby="rolestabs">
                                    <div class="row mx-0">
                                        <div class="col-md-12 p-4 mt-md-2 ">
                                            <div class=" mb-3 d-flex align-items-center">
                                                <h5>{{ __('admin.users') }}</h5>
                                                <!-- <a href="{{route('settings.roles.create')}}" class="btn btn-success ms-auto btn-sm plusicon" id="addRoleBtn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 20 20">
                                                        <path id="icon_plus_pro" d="M18.571,39.857H12.143V33.429A1.429,1.429,0,0,0,10.714,32H9.286a1.429,1.429,0,0,0-1.429,1.429v6.429H1.429A1.429,1.429,0,0,0,0,41.286v1.429a1.429,1.429,0,0,0,1.429,1.429H7.857v6.429A1.429,1.429,0,0,0,9.286,52h1.429a1.429,1.429,0,0,0,1.429-1.429V44.143h6.429A1.429,1.429,0,0,0,20,42.714V41.286A1.429,1.429,0,0,0,18.571,39.857Z" transform="translate(0 -32)"></path>
                                                    </svg> {{__('admin.add')}}
                                                </a> -->

                                                <button type="button" class="btn btn-success ms-auto  btn-sm plusicon" id="addUserDetail" data-bs-toggle="modal" data-bs-target="#inviteUserModal">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 20 20">
                                                        <path id="icon_plus_pro" d="M18.571,39.857H12.143V33.429A1.429,1.429,0,0,0,10.714,32H9.286a1.429,1.429,0,0,0-1.429,1.429v6.429H1.429A1.429,1.429,0,0,0,0,41.286v1.429a1.429,1.429,0,0,0,1.429,1.429H7.857v6.429A1.429,1.429,0,0,0,9.286,52h1.429a1.429,1.429,0,0,0,1.429-1.429V44.143h6.429A1.429,1.429,0,0,0,20,42.714V41.286A1.429,1.429,0,0,0,18.571,39.857Z" transform="translate(0 -32)"></path>
                                                    </svg> {{ __('profile.add') }}
                                                </button>
                                            </div>
                                            <div class="tablepermission p-1">
                                                <div class="table-responsive">
                                                <table class="table table-fluid" style="width:100%"></table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end: Profile Tab Users listing -->

                            </div>
                        </div>
                    </div>
                </div>
                <!--end: Admin Settings Section-->
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>


<form name="inviteUserForm" id="inviteUserForm" autocomplete="off" method="POST" action="{{ route('settings.buyer-user.store') }}">
    @csrf
    <div class="modal fade" id="inviteUserModal" tabindex="-1" aria-labelledby="inviteUserModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content radius_1 shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalTitle">{{ __('profile.add_user') }}</h5>
                    <button type="button" class="btn-close" id="closeInviteUser" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row floatlables pt-3">
                        <div class="col-sm-6 mb-4">
                            <label for="" class="form-label">{{ __('profile.first_name') }}<span style="color:red">*</span></label>
                            <input type="text" name="firstName" class="form-control" id="firstName" value="" required>
                            <span id="firstNameError" class="text-danger"></span>
                        </div>

                        <div class="col-sm-6 mb-4">
                            <label for="" class="form-label">{{ __('profile.last_name') }}<span style="color:red">*</span></label>
                            <input type="text" name="lastName" class="form-control" id="lastName" value="" required>
                            <span id="lastNameError" class="text-danger"></span>
                        </div>

                        <div class="col-sm-6 mb-4">
                            <label for="" class="form-label">{{ __('profile.Email') }}<span style="color:red">*</span></label>
                            <input type="email" name="email" class="form-control" id="email" value="" required>
                            <span id="showEmailError" class="text-danger"></span>
                            <span id="emailError" class="text-danger"></span>
                        </div>

                        <div class="col-sm-6 mb-4">
                            <label for="" class="form-label">{{ __('profile.mobile_number') }}<span style="color:red">*</span></label>
                            <input type="tel" name="mobile" class="form-control" id="mobile" placeholder="XXXXXXXXXXX" value="" required>
                            <span id="mobileError" class="text-danger"></span>
                        </div>

                        <div class="col-sm-6 mb-4" id="user_designation_div">
                            <label for="" class="form-label">{{ __('profile.designation') }}<span style="color:red">*</span></label>
                            <select name="designation" id="designation" class="form-select designationClass" required>
                                <option value="">{{__('profile.select_designation')}}</option>
                                @foreach ($designations as $designation)
                                    <option value="{{ $designation->id }}"> {{ $designation->name }} </option>
                                @endforeach
                            </select>
                            <span id="designationError" class="text-danger"></span>
                        </div>

                        <div class="col-sm-6 mb-4" id="user_department_div">
                            <label for="" class="form-label">{{ __('profile.department') }}<span style="color:red">*</span></label>
                            <select name="department" id="department" class="form-select departmentClass" required>
                                <option value="">{{__('profile.select_department')}}</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"> {{ $department->name }} </option>
                                @endforeach
                            </select>
                            <span id="departmentError" class="text-danger"></span>
                        </div>

                        <div class="col-md-6" id="user_role_div">
                            <label class="form-label">{{ __('admin.role') }}<span style="color:red">*</span></label>
                            <select name="role" id="role" class="form-select roleClass" required>
                                <option value="">{{__('admin.select_role')}}</option>
                                @foreach ($customRoles as $role)
                                    <option
                                        {{ $user->department == $role->id ? 'selected' : '' }}
                                        value="{{ $role->id }}">
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                                <!-- <option value="Approver">{{ __('profile.approver_consultant') }}</option> -->
                            </select>
                            <span id="roleError" class="text-danger"></span>
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label for="" class="form-label">{{ __('buyer.branches') }}</label>
                            <input type="text" name="branch" class="form-control" id="branch" value="">
                        </div>

                    </div>
                </div>

                <input type="hidden" name="invitedUserId" id="invitedUserId" value="" />

                <div class="modal-footer">
                    <button type="button" class="btn plusicon btn-sm btn-primary" id="saveInviteUserBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 20 20">
                            <path id="icon_plus_pro" d="M18.571,39.857H12.143V33.429A1.429,1.429,0,0,0,10.714,32H9.286a1.429,1.429,0,0,0-1.429,1.429v6.429H1.429A1.429,1.429,0,0,0,0,41.286v1.429a1.429,1.429,0,0,0,1.429,1.429H7.857v6.429A1.429,1.429,0,0,0,9.286,52h1.429a1.429,1.429,0,0,0,1.429-1.429V44.143h6.429A1.429,1.429,0,0,0,20,42.714V41.286A1.429,1.429,0,0,0,18.571,39.857Z"
                            transform="translate(0 -32)" />
                        </svg> {{ __('profile.Add') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!--------------Approval User View Details ------------------->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="rm-view" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewUserTitle">View </h5>
                <div class="ms-auto"><small class="badge rounded-pill bg-success">{{ __('admin.active') }}</small></div>
                <button type="button" class="btn-close ms-1" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body approval-view-body">
                <div class="row rfqform_view bg-white py-3 px-3">
                    <div class="col-md-12 pb-3 d-flex">
                        <div class="fs-4" id="viewUserName"></div> <small style="margin: 12px 0 0 8px;" id="viewUserRole"></small>
                    </div>

                    <div class="col-md-6 pb-3">
                        <div class="ms-4 approval-view">{{ __('home_latest.email') }}</div>
                        <div class="view-content d-flex" >
                            <div><img src="{{ URL::asset('front-assets/images/icons/envelope.png') }}" alt="img" class="approval-images"></div>
                            <div class="ms-2" id="viewUserEmail">munir@yopmail.com</div>
                        </div>
                    </div>

                    <div class="col-md-6 pb-3" >
                        <div class="ms-4 approval-view">{{ __('admin.designation') }}</div>
                        <div class="view-content d-flex" >
                        <div><img src="{{ URL::asset('front-assets/images/icons/icon_rfq.png') }}" height="16px" alt="img" class="approval-images"></div>
                        <div class="ms-2"  id="viewUserDesignation">Test Engineer</div>
                    </div>
                    </div>

                    <div class="col-md-6 pb-3">
                        <div class="ms-4 approval-view">{{ __('home_latest.mobile') }}</div>
                        <div class="view-content d-flex" >
                        <div><img src="{{ URL::asset('front-assets/images/icons/phone-alt.png') }}" alt="img" class="approval-images" ></div>
                        <div class="ms-2" id="viewUserMobile">+54 9876543210</div>
                        </div>
                    </div>

                    <div class="col-md-6 pb-3">
                        <div class="ms-4 approval-view">{{ __('admin.department') }}</div>
                        <div class="view-content d-flex" >
                        <div><img src="{{ URL::asset('front-assets/images/icons/building.png') }}" alt="img" class="approval-images"></div>
                        <div class="ms-2" id="viewUserDepartment">Test Engineer</div>
                        </div>
                    </div>

                    <div class="col-md-6 pb-3">
                        <div class="ms-4 approval-view">{{ __('buyer.branches') }}</div>
                        <div class="view-content d-flex" >
                            <div><img src="{{ URL::asset('front-assets/images/icons/pin.png') }}" height="16px" width="16px" alt="img" class="approval-images"></div>
                            <div class="ms-2" id="viewUserbranch">Test Engineer</div>
                        </div>
                    </div>

                    <div class="col-md-6 pb-3">
                        <div class="ms-4 approval-view" >{{ __('profile.created_date') }}</div>
                        <div class="view-content d-flex" >
                        <div><img src="{{ URL::asset('front-assets/images/icons/Calendar-alt.png') }}" alt="img" class="approval-images"></div>
                        <div class="ms-2"id="viewUserCreatedDate">18-07-2022</div>
                        </div>
                    </div>

                    <!-- <div class="col-md-6 pb-3 ">
                        <div class="ms-4 approval-view" id="viewUserApprovalStatus">{{ __('profile.approval_status') }}</div>
                        <div class="view-content d-flex" >
                        <div><img src="{{ URL::asset('front-assets/images/icons/icon_product_details.png') }}" height="16px" alt="img" class="approval-images"></div>
                        <div class="ms-2"> Approve All</div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
<!--------------Approval User View Details ------------------->


<!-- ----------Role Click Popup --------------- -->
<div class="modal fade" id="rolePopUp" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered rolemodal">
        <div class="modal-content">
            <div id="rolemodal-body">
            </div>
        </div>
    </div>
</div>
<!-- ------------Role Pop Up- End---------------- -->

@include('buyer.common.footer.backend.footer')

@endsection

@section('script')
<!--begin: plugin js for this page -->
<script type="text/javascript" src="{{ URL::asset('front-assets/js/front/crop/scripts/jquery.Jcrop.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('front-assets/js/front/crop/scripts/jquery.SimpleCropper.js') }}"></script>
<script type="text/javascript" src='{{ URL::asset("front-assets/js/front/croppie.js")}}'></script>
<script type="text/javascript" src="{{ URL::asset('front-assets/js/datatables/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('front-assets/js/datatables-bs4/dataTables.bootstrap4.js') }}"></script>
<!--end: plugin js for this page -->

@endsection

@section('custom-script')
<script type="text/javascript">
    $(document).mouseup(function (e) {
        var container = $(".collapsemoreaction");

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.collapse('hide');
        }
    });

    /**************************************** begin:Profile Users Sidebar ***************************************/
    var SnippetAddedUsersTable = function() {

        var addedUsersDatatable = function () {
            $('.table').DataTable({
                serverSide: !0,
                paginate: !0,
                lengthMenu: [
                    [10, 25, 50],
                    [10, 25, 50],
                ],
                scrollX:!0,
                footer:!1,

                ajax: {
                    url : "{{ route('settings.buyer-user.index') }}",
                    method : "GET",
                },
                columns: [
                    {data: "firstname", title: "First Name", sortable:!0},
                    {data: "lastname", title: "Last Name", sortable:!1},
                    {data: "email", title: "Email", sortable:!1},
                    {data: "status", title: "Status", sortable:!1},
                    {data: "role_id", title: "Role", sortable:!1},
                    {data: "permission_id", title: "Permission", sortable:!1},
                    {data: "action", title: "Action", sortable:!1, width:150}
                ]
            });
        },

        removeSingleError = function() {
            $('#inviteUserForm input[type="text"]').on('input', function (evt) {
                let inputId = $(this).attr('id');
                $('#'+inputId+'Error').html('');
            });

            $('#inviteUserForm input[type="email"]').on('input', function (evt) {
                let inputId = $(this).attr('id');
                $('#'+inputId+'Error').html('');
            });

            $('#inviteUserForm input[type="tel"]').on('input', function (evt) {
                let inputId = $(this).attr('id');
                $('#'+inputId+'Error').html('');
            });

            $('#inviteUserForm select').on('change', function (evt) {
                let inputId = $(this).attr('id');
                $('#'+inputId+'Error').html('');
            });
        },

        removeAllErrors = function(){
            $('#inviteUserForm span.text-danger').html('');
        },


        addInvitedUser = function() {
            $('#saveInviteUserBtn').on("click", function(e) {
                var inviteBoolean = $("#saveInviteUserBtn").data('boolean');  //On click of save invite button, if there is any change then inviteBoolean = true
                e.preventDefault();
                $("#saveInviteUserBtn").prop('disabled', true);
                //removeAllErrors();
                $("#saveInviteUserBtn").attr('data-boolean','false');
                $(".nav-tabs").find('button').attr("data-bs-toggle","tab");
                let tabid = $(this).attr('data-nexttab');

                if ($('#inviteUserForm')) {
                    let formData =   $('#inviteUserForm').serializeArray();
                    if (inviteBoolean) {
                        formData.append('changesUserNotification', 1);
                    }
                    let method   =   $('#inviteUserForm').attr('method');
                    let action   =   $('#inviteUserForm').attr('action');

                    $.ajax({
                        url: action,
                        data: formData,
                        type: method,
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(data) {
                            //If "email id is already exist" then show below validation message
                            if(data.error != undefined && data.error == 'Email id is already exist') {
                                let emailExistMsg = "{{__('validation.email_already_exist')}}";
                                $("#showEmailError").html("<span class='showEmailErr'>"+emailExistMsg+"</span>");
                                $('#showEmailError').show();
                                $("#saveInviteUserBtn").prop('disabled', false);
                            }

                            //When new user added
                            if (data.success) {
                                if (method=='PUT') {
                                    new PNotify({
                                        text: "{{ __('validation.user_updated') }}",
                                        type: 'success',
                                        styling: 'bootstrap3',
                                        animateSpeed: 'fast',
                                        delay: 1000
                                    });
                                } else {
                                    new PNotify({
                                        text: "{{ __('validation.user_added') }}",
                                        type: 'success',
                                        styling: 'bootstrap3',
                                        animateSpeed: 'fast',
                                        delay: 1000
                                    });
                                }
                                $('#inviteUserModal').modal('hide');
                                $('#inviteUserForm').parsley().reset();
                                buttonclickoncancel(tabid);
                                $("#saveInviteUserBtn").removeAttr('data-nexttab');
                                $('#inviteUserModal').find("#email").attr('readonly', false);
                                setTimeout(function () {
                                    location.reload(true);
                                }, 2000);
                                $("#saveInviteUserBtn").prop('disabled', false);
                            }
                        },
                        error: function(data) {
                            if( data.status === 422 ) {
                                var errors = $.parseJSON(data.responseText);
                                $.each(errors, function (key, value) {

                                    if($.isPlainObject(value)) {
                                        $.each(value, function (key, value) {
                                            $('#'+key+'Error').html(value);
                                        });
                                    }
                                    $("#saveInviteUserBtn").prop('disabled', false);
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
                }
            });
        },

        showUserDetails = function() {
            $(document).on('click', '.viewUserDetails', function(e) {
                e.preventDefault();
                addedUserId = $(this).attr('data-id');
                if (addedUserId != '') {
                    $.ajax({
                        url: "{{ route('settings.buyer-user.show', '') }}" + "/" + addedUserId,
                        type: 'GET',
                        success: function(successData) {
                            if(successData.success) {
                                $('#userDetailsModal').find('#viewUserTitle').html('{{ __("admin.view") }}');

                                $('#userDetailsModal').find("#viewUserName").text(successData.successData.firstname + ' ' + successData.successData.lastname);
                                $('#userDetailsModal').find("#viewUserRole").text("("+successData.successData.userRole+")");
                                $('#userDetailsModal').find("#viewUserEmail").html(successData.successData.email);
                                $('#userDetailsModal').find("#viewUserDesignation").html(successData.successData.designationName ? successData.successData.designationName : '<span style="margin-left:41px">-<span>');
                                $('#userDetailsModal').find("#viewUserMobile").html(successData.successData.mobile);
                                $('#userDetailsModal').find("#viewUserDepartment").html(successData.successData.departmentName ? successData.successData.departmentName : '<span style="margin-left:41px">-<span>');
                                $('#userDetailsModal').find("#viewUserCreatedDate").html(successData.successData.created_date);
                                $('#userDetailsModal').find("#viewUserbranch").html(successData.successData.branches);

                                $('#userDetailsModal').modal('show');
                            }
                        }
                    });
                }
            });
        },

        editInvitedUser = function() {
            $(document).on('click', '.editInvitedUserData', function(e) {
                e.preventDefault();
                addedUserId = $(this).attr('data-id');
                if (addedUserId != '') {
                    $.ajax({
                        url: "{{ route('settings.buyer-user.show', '') }}" + "/" + addedUserId,
                        type: 'GET',
                        success: function(successData) {
                            if(successData.success) {
                                $('#inviteUserModal').find("#email").attr('readonly', true);
                                $('#inviteUserModal').find('#userModalTitle').html('{{ __("profile.edit_user") }}');
                                $('#inviteUserModal').find('#saveInviteUserBtn').html('<img src="{{ URL::asset("front-assets/images/icons/icon_post_require.png")}}" alt="{{ __("admin.update") }}" class="pe-1"> {{ __("admin.update") }}');

                                $('#inviteUserModal').find("#invitedUserId").val(successData.successData.id);
                                $('#inviteUserModal').find("#firstName").val(successData.successData.firstname);
                                $('#inviteUserModal').find("#lastName").val(successData.successData.lastname);
                                $('#inviteUserModal').find("#email").val(successData.successData.email);
                                $('#inviteUserModal').find("#mobile").val(successData.successData.mobile);
                                $('#inviteUserModal').find("div#user_designation_div select.designationClass option").each(function() {
                                    if($(this).val() == successData.successData.designation) {
                                        $(this).attr("selected","selected");
                                    }
                                });
                                $('#inviteUserModal').find("div#user_department_div select.departmentClass option").each(function() {
                                    if($(this).val() == successData.successData.department) {
                                        $(this).attr("selected","selected");
                                    }
                                });
                                $('#inviteUserModal').find("div#user_role_div select.roleClass option").each(function() {
                                    if($(this).val() == successData.successData.role) {
                                        $(this).attr("selected","selected");
                                    } else {
                                        $("select option[value='Approver']").attr("selected","selected");
                                    }
                                });
                                $('#inviteUserModal').find("#branch").val(successData.successData.branches);

                                $('#inviteUserForm').attr('method','PUT');
                                $('#inviteUserForm').attr('action','buyer-user/'+addedUserId);
                                $('#inviteUserModal').modal('show');

                            }
                        }
                    });
                }
            });
        },

        userDetails = function() {
            $('#addUserDetail').on('click', function(e) {
                e.preventDefault();
                addedUserId = '';
                $('#inviteUserModal').find("input, select").val("");
                $('#inviteUserModal').find('#userModalTitle').html('{{ __("profile.add_user") }}');
                $('#inviteUserModal').find('#saveInviteUserBtn').html('<img src="{{ URL::asset("front-assets/images/icons/plus_icon_white.png")}}" alt="{{ __("profile.Add") }}" class="pe-1"> {{ __("profile.Add") }}');

                $('#inviteUserForm').attr('method','POST');
                $('#inviteUserForm').attr('action','buyer-user');
            });
        },

        deleteInvitedUser = function() {
            $(document).on('click', '.deleteInvitedUser', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                var id = $(this).attr('data-id');

                swal({
                    title: "{{ __('dashboard.are_you_sure') }}?",
                    text: "{{ __('dashboard.delete_warning') }}",
                    icon: "/assets/images/bin.png",
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{ route('settings.buyer-user.destroy', '') }}" + "/" + id,
                            type: 'DELETE',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function (data) {
                                if (data.success) {
                                    new PNotify({
                                        text: data.message,
                                        type: 'success',
                                        styling: 'bootstrap3',
                                        animateSpeed: 'fast',
                                        delay: 2000
                                    });

                                    setTimeout(function () {
                                        location.reload(true);
                                    }, 1500);
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
                            error: function () {
                                console.log('error');
                            }
                        });
                    }
                });
            });
        },

        userFormClose = function() {
            $('#inviteUserModal').on('hidden.bs.modal', function () {
                removeAllErrors();
                $('#inviteUserForm').trigger('reset');
                $("#inviteUserForm option:selected").removeAttr("selected");
            });
        },

        // Role Permission popup
        showRolePermissionPopup = function() {
            $(document).on('click', '.roleModalView', function(e) {
                e.preventDefault();
                $("#rolemodal-body").html('');
                var id = $(this).attr('data-id');
                if (id) {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: "{{ route('settings.users.rolePermissionPopup', '') }}" + "/" + id,
                        type: 'POST',
                        success: function(successData) {
                            $("#rolemodal-body").html(successData.rolePopupView)
                            $('#rolePopUp').modal('show');
                        },
                        error: function() {
                            console.log('error');
                        }
                    });
                }
            });
        };

        return {
            init: function () {
                addedUsersDatatable(),
                userDetails(),
                //addUserFormVaildation(),
                showUserDetails(),
                addInvitedUser(),
                editInvitedUser(),
                removeSingleError(),
                deleteInvitedUser(),
                userFormClose(),
                showRolePermissionPopup()
            }
        }

    }(1);

    jQuery(document).ready(function(){
        SnippetAddedUsersTable.init();
    });

    function buttonclickoncancel(tabid){
        var buttonid = $("#"+tabid).find('button').attr('id');
        $("#"+buttonid).trigger('click');
    }
    /**************************************** end:Profile Users Sidebar ***************************************/

    //Reset Parsley validation on button close
    $(document).on('click','#closeInviteUser', function(e) {
        $('#inviteUserModal').find("#email").attr('readonly', false);
        $('#inviteUserForm').parsley().reset();
        $('#inviteUserForm')[0].reset();
        $('#showEmailError').hide();
    });

    //Edit invited user details by user id
    $(document).on('click', '.editInvitedUserData1', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        if (id) {
            $.ajax({
                url: "{{ route('user-detail', '') }}" + "/" + id,
                type: 'GET',
                success: function(successData) {
                    // $('#inviteUserEditModal').find('.modal-content').html(successData.returnHTML);
                    if(successData.success == true) {
                        $("#inviteUserModal").modal('show');
                        $("#userModalTitle").text("{{ __('profile.edit_user') }}");
                        $("#invitedUserId").val(successData.user.id);
                        $("#user_fn").val(successData.user.firstname);
                        $("#user_ln").val(successData.user.lastname);
                        $("#user_email_id").val(successData.user.email);
                        $("#user_mobile").val(successData.user.mobile);
                        $("div#user_designation_div select.designationClass option").each(function() {
                            if($(this).val() == successData.user.designation) {
                                $(this).attr("selected","selected");
                            }
                        });
                        $("div#user_department_div select.departmentClass option").each(function() {
                            if($(this).val() == successData.user.department) {
                                $(this).attr("selected","selected");
                            }
                        });
                        $("div#user_role_div select.roleClass option").each(function() {
                            if($(this).val() == successData.user.designation) {
                                $(this).attr("selected","selected");
                            }
                        });

                    }
                },
                error: function() {
                    console.log('error');
                }
            });
        }
    });

    //Delete Invited User
    $(document).on('click', '.deleteInvitedUser1', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var id = $(this).attr('data-id');
        swal({
            title: "{{ __('dashboard.are_you_sure') }}?",
            text: "{{ __('dashboard.delete_warning') }}",
            icon: "/assets/images/bin.png",
            buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: "{{ route('delete-invited-user-ajax', '') }}" + "/" + id,
                    type: 'GET',
                    success: function(successData) {
                        new PNotify({
                            text: successData.msg,
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 2000
                        });
                        $('#inviteUserModal').modal('hide');
                        $('#usertabs-tab,#usertabs').addClass('active');
                        setTimeout(function () {
                            location.reload(true);
                        }, 2000);
                    },
                    error: function() {
                        console.log('error');
                    }
                });
                setTimeout(function () {
                    location.reload(true);
                }, 2000);
            }
        });
    });
</script>

@endsection
