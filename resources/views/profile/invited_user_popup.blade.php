<form name="inviteUserForm2" id="inviteUserForm2" data-parsley-validate autocomplete="off">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="">{{ __('profile.edit_user') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row floatlables pt-3">
            <div class="col-sm-6 mb-4">
                <label for="" class="form-label">{{ __('profile.first_name') }}<span style="color:red">*</span></label>
                <input type="text" name="firstName" class="form-control" id="user_fn" value="{{ $user[0]->firstname }}" required>
            </div>
            <div class="col-sm-6 mb-4">
                <label for="" class="form-label">{{ __('profile.last_name') }}<span style="color:red">*</span></label>
                <input type="text" name="lastName" class="form-control" id="user_ln" value="{{ $user[0]->lastname }}" required>
            </div>
            <div class="col-sm-6 mb-4">
                <label for="" class="form-label">{{ __('profile.Email') }}<span style="color:red">*</span></label>
                <input type="email" name="email" class="form-control" id="user_email" value="{{ $user[0]->email }}" required readonly>
                <span id="showEmailErr" class="text-danger"></span>
            </div>
            <div class="col-sm-6 mb-4">
                <label for="" class="form-label">{{ __('profile.mobile_number') }}<span style="color:red">*</span></label>
                <input type="text" name="mobile" class="form-control" id="user_mobile1" value="{{ $user[0]->mobile }}" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$"
                data-parsley-maxlength="20" data-parsley-minlength="10" placeholder="+XX XX XXXXXXX" required>
            </div>
            <div class="col-sm-6 mb-4">
                <label for="" class="form-label">{{ __('profile.designation') }}<span style="color:red">*</span></label>
                <select name="designation" id="user_designation" class="form-select" required>
                    <option value="">{{__('profile.select_designation')}}</option>
                    @foreach ($designations as $designation)
                        <option
                            {{ $user[0]->designation == $designation->id ? 'selected' : '' }}
                            value="{{ $designation->id }}">
                            {{ $designation->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 mb-4">
                <label for="" class="form-label">{{ __('profile.department') }}<span style="color:red">*</span></label>
                <select name="department" id="user_dept" class="form-select" required>
                    <option value="">{{__('profile.select_department')}}</option>
                    @foreach ($departments as $department)
                        <option
                            {{ $user[0]->department == $department->id ? 'selected' : '' }}
                            value="{{ $department->id }}">
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('admin.role') }}<span style="color:red">*</span></label>
                <select name="role" id="role" class="form-select" required>
                    <option value="">{{__('admin.select_role')}}</option>
                    @foreach ($customRoles as $role)
                        <option value="{{ $role->id }}" {{in_array($role->id ,$customRoleIds) == true ? 'selected' : 'none';}}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                    <!-- <option value="Approver" @if(getRolePermissionAttribute($user[0]->id)['role'] == null) selected @endif>{{ __('profile.approver_consultant') }}</option> -->
                </select>
            </div>
        </div>
    </div>
    <input type="hidden" name="invitedUserId" value="{{$user[0]->id}}" />
    <div class="modal-footer">
        <a data-boolean="false" class="btn btn-primary px-4 py-2 mb-1" id="updateInviteUserBtn" href="javascript:void(0)">
            <img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}" alt="Save Changes" class="pe-1"> {{__('profile.save_changes')}}
        </a>
    </div>
</form>
<script>
    var input = document.querySelector("#user_mobile1");
    var iti = window.intlTelInput(input, {
        initialCountry:"id",
        separateDialCode:true,
        dropdownContainer:null,
        preferredCountries:["id"],
        hiddenInput:"phone_code"
    });

    $("#user_mobile1").focusin(function(){
        let countryData = iti.getSelectedCountryData();
        $('input[name="phone_code"]').val(countryData.dialCode);
    });
    @php
    $phoneCode = $user[0]->phone_code ?str_replace('+','',$user[0]->phone_code):62;
    $country = $user[0]->phone_code ?strtolower(getRecordsByCondition('countries',['phone_code'=>$phoneCode],'iso2',1)):'id';
    @endphp
    $(document).ready(function(){
        $('input[name="phone_code"]').val({{ $phoneCode }});
        iti.setCountry('{{$country}}');
    });
    //$(document).on('click', '#updateInviteUserBtn', function(e) {
    $("#updateInviteUserBtn").click(function(e) {
        e.preventDefault();
        $("#updateInviteUserBtn").attr('data-boolean','false');

        $(".nav-tabs").find('button').attr("data-bs-toggle","tab");
        var tabid = $(this).attr('data-nexttab');
        if ($('#inviteUserForm2').parsley().validate()) {
            $("#updateInviteUserBtn").hide();
            var formData = new FormData($('#inviteUserForm2')[0]);
            $.ajax({
                url: "{{ route('update-user-info-ajax') }}",
                data: formData,
                type: 'POST',
                contentType: false,
                processData: false,

                success: function(successData) {
                    if(successData.msg == 'Email id is already exist') {
                        $("#showEmailErr").html("<span class='showEmailErr'>"+successData.msg+"</span>");
                        $('#showEmailErr').show();
                    } else {
                        new PNotify({
                            text: '{{ __('validation.user_updated') }}',
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });
                        $('#inviteUserEditModal').modal('hide');
                        $('#usertabs-tab,#usertabs').addClass('active');
                        $('#user_fn,#user_ln,#user_email,#user_mobile1,#user_designation,#user_dept').val('');

                        setTimeout(function () {
                            location.reload(true);
                        }, 2000);
                    }
                    $("#updateInviteUserBtn").show();
                },
                error: function() {
                    console.log('error');
                }
            });
        }
    });
</script>
