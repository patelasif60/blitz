<form name="configUserForm" id="configUserForm" data-parsley-validate autocomplete="off">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="">{{ __('profile.edit_user') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row floatlables pt-3">
            <div class="col-sm-12 mb-4">
                <label for="" class="form-label">{{ __('profile.Email') }}<span style="color:red">*</span></label>
                <input type="email" name="email" class="form-control" id="user_email" value="{{ $user[0]->email }}" readonly required>
            </div>
            <div class="col-sm-12  d-flex">
                <div class="form-check me-3">
                    <input class="form-check-input" type="radio" name="user_type" id="gridRadios1" value="Approver" {{$user[0]->user_type == 'Approver' ? 'checked' : ''}}>
                    <label class="form-check-label" for="gridRadios1">Approver</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="user_type" id="gridRadios2" value="Consulted" {{$user[0]->user_type == 'Consulted' ? 'checked' : ''}}>
                    <label class="form-check-label" for="gridRadios2">Consulted</label>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="configUserId" value="{{$user[0]->id}}" />
    <div class="modal-footer">
        <a data-boolean="false" class="btn btn-primary px-4 py-2 mb-1" id="updateApprovalUserBtn" href="javascript:void(0)">
            <img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}" alt="Save Changes" class="pe-1"> {{__('profile.save_changes')}}
        </a>
    </div>
</form>

<script>
    $(document).on('click', '#updateApprovalUserBtn', function(e) {
        var updateAproveBoolean = $("#updateApprovalUserBtn").data('boolean');
        e.preventDefault();
        $("#updateApprovalUserBtn").attr('data-boolean','false');

        $(".nav-tabs").find('button').attr("data-bs-toggle","tab");
        var tabid = $(this).attr('data-nexttab');

        if ($('#configUserForm').parsley().validate()) {
            var formData = new FormData($('#configUserForm')[0]);
            if(updateAproveBoolean){
                formData.append('changesUserNotification', 1);
            }
            $.ajax({
                url: "{{ route('update-usertype-ajax') }}",
                data: formData,
                type: 'POST',
                contentType: false,
                processData: false,

                success: function(successData) {
                    new PNotify({
                        text: '{{ __('profile.personal_details_updated') }}',
                        type: 'success',
                        styling: 'bootstrap3',
                        animateSpeed: 'fast',
                        delay: 1000
                    });
                    $('#inviteUserEditModal').modal('hide');
                    $('#usertabs-tab,#usertabs').addClass('active');
                    $('#user_fn,#user_ln,#user_email,#user_mobile,#user_designation,#user_dept').val('');

                    setTimeout(function () {
                        location.reload(true);
                    }, 2000);
                },
                error: function() { console('false');
                    console.log('error');
                }
            });
        }
    });
</script>
