<!-- Role Permission modal -->
        <div class="modal-header py-3">
            <h5 class="modal-title" id="exampleModalLabel"><img height="24px" class="pe-2"
                                                                src="{{URL::asset('assets/icons/order_detail_title.png')}}"
                                                                alt="User Details">{{ $userDetails->companies->name }}
            </h5>
            <button type="button" class="btn-close ms-0" data-bs-dismiss="modal"
                    aria-label="Close"><img src="{{URL::asset('assets/icons/times.png')}}"
                                            alt="Close"></button>
        </div>
    <form id="buyerUserForm" enctype="multipart/form-data" class="form-group">
        @csrf
                <div class="modal-body p-3 pb-1 rfqform_view ">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0"><img height="20px"
                                                          src="{{URL::asset('assets/icons/icon_company.png')}}"
                                                          alt="Company" class="pe-2">
                                        <span>Company Details</span>
                                    </h5>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row ">
                                        <div class="col-sm-6 mb-2">
                                            <label for="" class="form-label">{{ __('profile.first_name') }}<span
                                                    style="color:red">*</span></label>
                                            <input type="text" name="firstName" class="form-control input-alpha"
                                                   id="firstName" value="{{ $userDetails->firstname }}" required >
                                            <span id="firstNameError" class="text-danger"></span>
                                        </div>

                                        <div class="col-sm-6 mb-2">
                                            <label for="" class="form-label">{{ __('profile.last_name') }}<span
                                                    style="color:red">*</span></label>
                                            <input type="text" name="lastName" class="form-control input-alpha"
                                                   id="lastName" value="{{$userDetails->lastname }}" required >
                                            <span id="lastNameError" class="text-danger"></span>
                                        </div>

                                        <div class="col-sm-6 mb-2">
                                            <label for="" class="form-label">{{ __('profile.Email') }}<span
                                                    style="color:red">*</span></label>
                                            <input type="email" name="email" class="form-control"
                                                   id="email" value="{{ $userDetails->email }}"  readonly>
                                            <span id="showEmailError" class="text-danger"></span>
                                            <span id="emailError" class="text-danger"></span>
                                        </div>

                                        <div class="col-sm-6 mb-2">
                                            <label for="" class="form-label">{{ __('profile.mobile_number') }}<span
                                                    style="color:red">*</span></label>
                                            <input type="tel" name="mobile" class="form-control input-number"
                                                   id="mobile" placeholder="XXXXXXXXXXX" value="{{ $userDetails->mobile }}"
                                                   required >
                                            <span id="mobileError" class="text-danger"></span>
                                        </div>

                                        <div class="col-sm-6 mb-2" id="user_designation_div">
                                            <label for="" class="form-label">{{ __('profile.designation') }}<span
                                                    style="color:red">*</span></label>
                                            <select name="designation" id="designation"
                                                    class="form-select designationClass" required="">
                                                <option value="">{{__('profile.select_designation')}}</option>
                                                @foreach ($designations as $designation)
                                                    <option
                                                        {{ $userDetails->designation == $designation->id ? 'selected' : '' }}
                                                        value="{{ $designation->id }}">
                                                        {{ $designation->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span id="designationError" class="text-danger"></span>
                                        </div>

                                        <div class="col-sm-6 mb-2" id="user_department_div">
                                            <label for="" class="form-label">{{ __('profile.department') }}<span
                                                    style="color:red">*</span></label>
                                            <select name="department" id="department"
                                                    class="form-select departmentClass" required="">
                                                <option value="">{{__('profile.select_department')}}</option>
                                                @foreach ($departments as $department)
                                                    <option
                                                        {{ $userDetails->department == $department->id ? 'selected' : '' }}
                                                        value="{{ $department->id }}">
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span id="departmentError" class="text-danger"></span>
                                        </div>

                                        <div class="col-md-6" id="user_role_div">
                                            <label class="form-label">{{ __('admin.role') }}<span
                                                    style="color:red">*</span></label>
                                            <select name="role" id="role" class="form-select roleClass"
                                                    required="">
                                                <option value="">{{__('admin.select_role')}}</option>
                                                @foreach ($customRoles as $role)
                                                    <option value="{{ $role->id }}">
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span id="roleError" class="text-danger"></span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-primary" id="saveUserData">{{ __('admin.save') }}</button>
                    <button type="button" class="btn  btn-cancel" data-bs-dismiss="modal">Close</button>
                </div>
    </form>

<!-- Role Permission modal end-->
<script>
    var SnippetCompanyUsersDetails = function(){

        var usersDatatable = function () {

                var userTable = $('.dataTable').DataTable({
                    serverSide: !0,
                    paginate: !0,
                    processing: !0,
                    lengthMenu: [
                        [10, 25, 50],
                        [10, 25, 50],
                    ],
                    footer:!1,
                    ajax: {
                        url     : "{{route('admin.buyer.company.companyUserList')}}",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data : {company_id : company_id, buyerId : buyerId},
                        method  : "POST",
                    },
                    columns: [
                        {data: "buyer_name", title: "{{__('admin.name')}}"},
                        {data: "buyer_email", title: "{{ __('admin.contact_person_email') }}"},
                        {data: "buyer_phone", title: "{{ __('admin.mobile')}}"},
                        {data: "buyer_designation", title: "{{ __('admin.designation') }}"},
                        {data: "buyer_role", title: "{{ __('profile.role') }}"},
                        {data: "buyer_joining", title: "{{ __('admin.joining_date') }}"},
                        {data: "actions", title: "{{ __('admin.actions') }}" , class: "text-nowrap text-end"}
                    ],
                    aoColumnDefs: [
                        { "bSortable": true, "aTargets": [0] },
                        { "bSortable": false, "aTargets": [3,4,6] }
                    ],
                    language: {
                        search: "{{__('admin.search')}}",
                        loadingRecords: "{{__('admin.please_wait_loading')}}",
                        processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span> {{__('admin.loading')}}..</span> '
                    },
                    order: [[0, 'desc']],

                });

                return userTable;
            },

            companyUserListDatatable = function () {
                usersDatatable().draw();
            },
            /** User edit Popup **/
            rolePopUpEdit = function () {
                $(document).on('click', '.companyRolePopup', function(e) { // company User Popup
                    $("#companyRoleModal").find(".modal-content").html('');
                    e.preventDefault();
                    var id = $(this).attr('data-id');
                    if (id) {
                        $.ajax({
                            url : "{{ route('admin.buyer.company.companyUserList.edit') }}",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data :{id : id},
                            type: 'POST',
                            success: function(successData) {
                                $("#companyRoleModal").find(".modal-content").html(successData.buyerView);

                                $('#companyRoleModal').find("div#user_role_div select.roleClass option").each(function() {
                                    if($(this).val() == successData.role) {
                                        $(this).attr("selected","selected");
                                    } else {
                                        $("select option[value='Approver']").attr("selected","selected");
                                    }
                                });
                                $('#companyRoleModal').modal('show');
                            },
                            error: function() {
                                console.log('error');
                            }
                        });
                    }
                });

            },
            /**  Save User details **/
            userDetailSave = function () {
                $(document).on('click', '#saveUserData', function(e) {
                    removeAllErrors();
                    let formData =  $('#buyerUserForm').serializeArray();
                    $.ajax({
                        url : "{{ route('admin.buyer.company.companyUserList.update') }}",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data : formData,
                        type: 'POST',
                        success: function(data) {
                            resetToastPosition();
                            $.toast({
                                heading: "{{ __('admin.success') }}",
                                text: "{{ __('admin.buyer_updated_successfully') }}",
                                showHideTransition: "slide",
                                icon: "success",
                                loaderBg: "#f96868",
                                position: "top-right",
                            });
                            return false;
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
                                });
                            } else {
                                resetToastPosition();
                                $.toast({
                                    heading: "{{__('admin.success')}}",
                                    text: "{{__('profile.something_went_wrong')}}",
                                    showHideTransition: "slide",
                                    icon: "error",
                                    loaderBg: "#f96868",
                                    position: "top-right",
                                });
                            }
                        }
                    });

                });
            },

            removeSingleError = function() {
                $('input[type="text"]').on('input', function (evt) {
                    let inputId = $(this).attr('id');
                    $('#'+inputId+'Error').html('');
                });

                $('#buyerUserForm select').on('change', function (evt) {
                    let inputId = $(this).attr('id');
                    $('#'+inputId+'Error').html('');
                });
            },

            removeAllErrors = function(){
                $('#buyerUserForm span.text-danger').html('');
            }

        return {
            init: function () {
                    rolePopUpEdit(),
                    userDetailSave(),
                    removeSingleError()

            }
        }
    }(1);

    jQuery(document).ready(function(){
        SnippetCompanyUsersDetails.init();
    });
</script>
