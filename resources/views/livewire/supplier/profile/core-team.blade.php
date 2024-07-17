<div>
    <style type="text/css">
        #tblCompanyCoreTeam_processing{
            padding: 0.5rem 0;
        }
    </style>
    <form id="coreTeamFrm" method="POST" enctype="multipart/form-data" autocomplete="off" action="{{ route('supplier.members.store',Crypt::encrypt($supplierId)) }}" data-id="{{Crypt::encrypt($supplierId)}}">
        @csrf
        <input type="hidden" name="formType" value="coreteam">
        <input type="hidden" name="id" id="coreTeamId" value="">
        <input type="hidden" name="userId" id="userId" value="{{$supplierId}}">
        <input type="hidden" name="company_user_type_id" id="company_user_type_id" value="1">
        <input type="hidden" name="typeName" id="typeName" value="Core Team">
        <div class="card mb-3">
        <div class="card-header d-flex align-items-center">
            <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/icon_Coreteam.png')}}" alt="Company" class="pe-2">
                <span>{{ __('admin.core_team_details') }}</span>
            </h5>
        </div>
        <div class="card-body p-3 newtable_v2 ">
            <div class="d-flex align-items-center justify-content-end pe-1 mb-3">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#coreteam">{{ __('admin.add')}}</button>
            </div>
            <div class="table-responsive">
                @include('livewire.supplier.profile.core-team-datatable')
            </div>
        </div>
    </div>
        <div class="text-end">
            <button class="btn btn-primary" onclick="$('#pills-Portfolio-tab').click()" >{{__('admin.next')}}</button>
        </div>
        <div class="modal fade version2 error_res" id="coreteam" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 800px;" role="document">
            <div class="modal-content border-0">
                <div class="modal-header p-3">
                    <h3 class="modal-title" style="color: white;"> {{ __('admin.core_team_details')}}
                    </h3>
                    <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close" style="margin: -26px 4px -13px auto">
                        <img src="{{URL::asset('front-assets/images/icons/times.png')}}" alt="Close">
                    </button>
                </div>
                <div class="modal-body p-3" style="background-color: #ebedf1;">
                    <div class="card">
                        <div class="card-header align-items-center" style="background-color: #d7d9df;">
                            <h5 class="mb-0"><img src="{{URL::asset('assets/icons/info.png')}}" height="20px" alt="Core Team Logo" class="pe-2"> {{ __('admin.core_teams')}}</h5>
                        </div>
                        <div class="card-body p-3 pb-1">
                            <div class=" rfqform_view">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="salutation" class="form-label">{{ __('admin.first_name')}} <strong class="text-danger">*</strong>
                                        </label>
                                        <div class="d-flex">
                                            <select name="salutation" class="form-select w100p border-end-0" id="salutation" style="border-radius: 0px;  background-color: rgba(0, 0, 0, 0.05); font-size: 0.875rem; width: 130px">
                                                <option value="Mr." selected="">{{ __('admin.salutation_mr')}}</option>
                                                <option value="Ms.">{{ __('admin.salutation_ms')}}</option>
                                                <option value="Mrs.">{{ __('admin.salutation_mrs')}}</option>
                                            </select>
                                            <input type="text" name="firstname" placeholder="{{ __('admin.first_name_palaceholder')}}" id="firstname" class="form-control input-alpha-numeric" >
                                        </div>
                                        <small id="firstname-error" class="text-danger errorValidate fw-normal"></small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="lastname" class="form-label">{{ __('admin.last_name')}} <strong class="text-danger">*</strong>
                                        </label>
                                        <input type="text" name="lastname" id="lastname" placeholder="{{ __('admin.last_name_placeholder')}}" class="form-control input-alpha-numeric" >
                                        <small id="lastname-error" class="text-danger errorValidate fw-normal"></small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">{{ __('admin.email')}} </label>
                                        <input type="text" name="email" placeholder="{{ __('admin.email_placeholder')}}" id="email"  class="form-control emailField" >
                                        <small id="email-error" class="text-danger errorValidate fw-normal"></small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label ">{{ __('admin.phone')}}</label>
                                        <input type="text" name="phone" id="phone"placeholder="XXXXXXXXXXX" class="form-control input-number">
                                        <small id="phone-error" class="text-danger errorValidate fw-normal"></small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="designation" class="form-label">{{ __('admin.designation')}} </label>
                                        <input type="text" name="designation" id="designation" placeholder="{{ __('admin.designation_placeholder')}}" class="form-control" >
                                        <small id="designation-error" class="text-danger errorValidate fw-normal"></small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="position" class="form-label">{{ __('admin.position')}}</label>
                                        <input type="text" name="position" placeholder="{{ __('admin.position_palceholder')}}" id="position" class="form-control">
                                        <small id="position-error"  class="text-danger errorValidate fw-normal"></small>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="coreTeamImage" class="form-label mb-2">{{ __('admin.photo')}} </label>
                                        <div class="d-flex">
                                            <span class="">
                                                <input type="file" name="coreTeamImage" class="form-control" id="coreTeamImage" accept=".jpg,.png,.gif,.jpeg" hidden/>
                                                <label  class="upload_btn" for="coreTeamImage">{{ __('admin.upload_photo')}} </label>
                                            </span>
                                            <div id="file-coreTeamImage"></div>
                                        </div>
                                        <small id="coreTeamImage-error" class="text-danger errorValidate fw-normal"></small>

                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="description" class="form-label"> {{ __('admin.about')}} <strong class="text-danger">*</strong></label>
                                        <textarea name="description" id="descriptionField" placeholder="{{ __('admin.about_placeholder')}}" class="form-control"></textarea>
                                        <small id="description-error" class="text-danger errorValidate fw-normal"></small>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('admin.save')}}</button>
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">{{ __('admin.cancel')}} </button>
                </div>
            </div>
        </div>
    </div>
    </form>
    <script type="text/javascript">
        var SnippetCoreTeam = function () {

            var processRecord = function () {
                $('#coreTeamFrm').on('submit',function(e){
                        e.preventDefault();
                        $("#coreTeamFrm").find('.errorValidate').html('');
                        var formData = new FormData(this);
                        formData.append('country_phone_code', '+'+iti.getSelectedCountryData().dialCode);
                        var url = $('#coreTeamFrm').attr('action');
                        $.ajax({
                            url: url,
                            data: formData,
                            type: "POST",
                            contentType: false,
                            processData: false,
                            success: function(data) {
                                if(data.success) {
                                    resetToastPosition();
                                    SnippetApp.toast.success("{{ __('admin.success') }}",data.message);

                                    resetForm();
                                    $('#coreteam').modal('toggle');
                                    $('#tblCompanyCoreTeam').DataTable().ajax.reload();
                                } else {
                                    resetToastPosition();
                                    SnippetApp.toast.alert("{{ __('admin.warning') }}","{{ __('admin.something_Went_wrong') }}");
                                }
                            },
                            error:function(e){
                                if (e.status == 422){
                                    $.each(e.responseJSON.errors, function(key,value) {
                                        $("#coreTeamFrm").find('#'+key+'-error').html('');
                                        $("#coreTeamFrm").find('#'+key+'-error').html(value);
                                    });
                                    $("#coreTeamFrm").find(".errorValidate:not(:empty)").parent().find("input:visible:empty,textarea:visible:empty").first().focus();
                                } else {
                                    SnippetApp.toast.alert("{{ __('admin.warning') }}","{{ __('admin.something_Went_wrong') }}");
                                }
                            }
                        });
                    });
            },

            edit = function () {
                $(document).on('click','.editCoreTeam', function() {
                    SnippetSupplierProfile.init();
                    var id = $(this).data('id');
                    $("#coreTeamId").val(id);
                    var url = "{{ route('supplier.members.edit',':id') }}";
                    url = url.replace(":id", id);
                    $.ajax({
                        url: url,
                        dataType: "JSON",
                        success: function(res) {
                            $("#coreTeamFrm #salutation").val(res.data.salutation);
                            $("#coreTeamFrm #firstname").val(res.data.firstname);
                            $("#coreTeamFrm #lastname").val(res.data.lastname);
                            $("#coreTeamFrm .emailField").val(res.data.email);
                            $("#coreTeamFrm #phone").val(res.data.phone);
                            $("#coreTeamFrm #designation").val(res.data.designation);
                            $("#coreTeamFrm #position").val(res.data.position);
                            $("#coreTeamFrm #descriptionField").val(res.data.description);
                            if(res.data.image != "" && res.data.image){
                                var extensionLogo = res.data.image.split('.').pop();
                                var imageFileName = res.data.member_image+'.'+extensionLogo;
                                var closeIcon = '{{URL::asset("assets/icons/times-circle copy.png")}}';
                                var imgString = '<input type="hidden" id="oldmemberImage" name="oldmemberImage" value="'+res.data.image+'">';
                                imgString += '<span class="ms-2"><a href="javascript:void(0)" id="coreTeamFileDownload" style="text-decoration: none;">'+imageFileName+'</a></span>';
                                imgString += '<span class="coreTeamFile" data-fileName="'+imageFileName+'" data-type="delete" data-id="'+res.data.id+'"><a href="#" title="Remove Image"><img src="'+closeIcon+'" class="ms-2"></a></span>';
                                imgString += '<span class="ms-2 fs-6 coreTeamFile" data-fileName="'+imageFileName+'" data-type="download" data-id="'+res.data.id+'"><a  href="javascript:void(0);" title="Download Image" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>';
                                $("#file-coreTeamImage").html(imgString);
                            }
                            $('#coreteam').modal('toggle');
                            $('#coreTeamFrm').attr('action','/supplier/members/update/'+id);
                        },
                        error:function(e){
                            resetToastPosition();
                            SnippetApp.toast.alert("{{ __('admin.warning') }}","{{ __('admin.something_Went_wrong') }}");
                        }
                    });

                })
            },

            removeFile = function () {
                $(document).on("click", ".coreTeamFile", function(e) {
                    e.preventDefault();
                    var id = $(this).data("id");
                    var type = $(this).data("type");
                    var data = {
                        id: $(this).attr("data-id"),
                        fileName: $(this).attr("data-fileName"),
                        imageType: 'coreTeam',
                        type: type,
                        imageFileId: "#file-coreTeamImage",
                        dataTableId: "#tblCompanyCoreTeam",
                        route: "{{ route('supplier.members.deletedownloadimages') }}",
                        responseType: (type == "download") ? "blob" : "json",
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    };
                    if(type == "download"){
                        deleteOrDownloadFunction(data);
                    }else{
                        swal({
                            title: "{{ __('admin.delete_sure_alert') }}",
                            text: "{{ __('admin.department_delete_alert_text') }}",
                            icon: "/assets/images/bin.png",
                            buttons: ['Cancel', 'Delete'],
                            dangerMode: true,
                        }).then((deleteit) => {
                            if (deleteit) {
                                $("#file-coreTeamImage").html('');
                            }
                        });
                    }

                });
            },

            removeRecord = function () {
                $(document).on("click", ".deleteCoreTeam", function(e) {
                    e.preventDefault();
                    var id = $(this).data("id");
                    var data = {
                        id: $(this).attr("data-id"),
                        type: 'deleteCompanyMembers',
                        company_user_type: '1',
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    };
                    swal({
                        title: "{{ __('admin.delete_sure_alert') }}",
                        text: "{{ __('admin.department_delete_alert_text') }}",
                        icon: "/assets/images/bin.png",
                        buttons: ['Cancel', 'Delete'],
                        dangerMode: true,
                    }).then((deleteit) => {
                        if (deleteit) {
                            $.ajax({
                                url: "{{ url('/admin/update-supplier-company-members') }}",
                                data: data,
                                type: "POST",
                                success: function(response) {
                                    if(response.success){
                                        resetToastPosition();
                                        $.toast({
                                            heading: "{{ __('admin.success') }}",
                                            text: response.message,
                                            showHideTransition: "slide",
                                            icon: "success",
                                            loaderBg: "#f96868",
                                            position: "top-right",
                                        });
                                        $('#tblCompanyCoreTeam').DataTable().ajax.reload(null,false);
                                    }else{
                                        swal("Error:", "Error in deleting core team.")
                                    }
                                },
                            });
                        }
                    });
                });
            },

            initCompo = function () {
                $('#coreteam').on('hidden.bs.modal', function (e) {
                    resetForm();
                });
            },

            resetForm = function() {
                $('#coreTeamFrm')[0].reset();
                $('#coreTeamFrm').find('.errorValidate').html('');
                $("#coreTeamId").val('');
                $('form#coreTeamFrm').find('#file-coreTeamImage').html('');
                var id = $('#coreTeamFrm').attr('data-id');
                $('#coreTeamFrm').attr('action','/supplier/members/store/'+id);

            },

            showCoreTeamImg = function () {
                $(document).on('change','#coreTeamImage',function () {
                    var input = this;
                    var file = input.files[0];
                    var size = Math.round((file.size / 1024))
                    if(size > 3000){
                        swal({
                            icon: 'error',
                            title: '',
                            text: '{{__('admin.file_size_under_3mb')}}',
                        })
                    } else {
                        var fileName = file.name;
                        var allowed_extensions = new Array("jpg", "png", "gif", "jpeg");
                        var file_extension = fileName.split('.').pop();
                        var text = '';
                        var supplier_id = '1';
                        var image_path = '{{URL::asset("assets/icons/times-circle copy.png")}}';
                        text = '{{ __('admin.only_upload_image_file') }}';
                        for (var i = 0; i < allowed_extensions.length; i++) {
                            if (allowed_extensions[i] == file_extension) {
                                valid = true;
                                var download_function = "'" + supplier_id + "', " + "'" + input.name + "', " + "'" + fileName + "'";
                                if(fileName.beforeLastIndex(".").length >= 10) {
                                    fileName = fileName.substring(0,10) +'....'+file_extension;
                                }
                                $('#file-' + input.name).html('');
                                $('#file-' + input.name).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.name + 'Download" style="text-decoration: none">' + fileName + '</a></span><span class="removeFile hide" id="' + input.name + 'File" data-name="' + input.name + '"><a href="#" title="Remove logo" style="text-decoration: none;"><img src="' + image_path + '" alt="CLose button" class="ms-2"></a></span><span class="ms-2"><a class="logoFile downloadbtn hide" href="javascript:void(0);" title="Download ' + input.name + '" onclick="downloadimg(' + download_function + ')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>');
                                return;
                            }
                        }
                        valid = false;
                        swal({
                            text: text,
                            icon: "warning",
                            buttons: ["{{ __('admin.no') }}", "{{ __('admin.yes') }}"],
                        })
                    }
                });
            };

            return {
                init:function () {
                    processRecord(),
                    edit(),
                    removeFile(),
                    removeRecord(),
                    initCompo(),
                    showCoreTeamImg()
                }


            }

        }(1);

        jQuery(document).ready(function(){
            SnippetCoreTeam.init();
        });
    </script>
</div>
