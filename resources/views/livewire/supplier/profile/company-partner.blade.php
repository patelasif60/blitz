<div>
    <style type="text/css">
        #tblCompanyPartner_processing{
            padding: 0.5rem 0;
        }
    </style>
    <form id="companyPartnerFrm" method="POST" enctype="multipart/form-data" autocomplete="off" action="{{ route('supplier.members.store',Crypt::encrypt($supplierId)) }}" data-id="{{Crypt::encrypt($supplierId)}}">
        @csrf
        <input type="hidden" name="formType" value="companyPartner" >
        <input type="hidden" name="id" id="partnerId" value="">
        <input type="hidden" name="company_user_type_id" id="company_user_type_id" value="3">
        <input type="hidden" name="typeName" id="typeName" value="Company Partner">
        <input type="hidden" name="userId" id="userId" value="{{$supplierId}}">

        <div class="card mb-3">
            <div class="card-header d-flex align-items-center">
                <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/icon_partners.png')}}" alt="Partner" class="pe-2">
                    <span>{{__('admin.company_partners')}}</span>
                </h5>
            </div>
            <div class="card-body p-3 newtable_v2 ">
                <div class="d-flex align-items-center justify-content-end pe-1 mb-3">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#partner">{{__('admin.add')}}</button>
                </div>
                <div class="table-responsive">
                    @include('livewire.supplier.profile.company-partner-datatable')
                </div>
            </div>
        </div>
        <div class="modal fade version2 error_res" id="partner" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 800px;" role="document">
                <div class="modal-content border-0">
                    <div class="modal-header p-3">
                        <h3 class="modal-title" style="color: white;">{{__('admin.company_partners')}}</h3>
                        <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close" style="margin: -26px 5px -18.5px auto">
                            <img src="{{URL::asset('front-assets/images/icons/times.png')}}" alt="Close">
                        </button>
                    </div>
                    <div class="modal-body p-3" style="background-color: #ebedf1;">
                        <div class="card">
                            <div class="card-header align-items-center" style="background-color: #d7d9df;">
                                <h5 class="mb-0"><img src="{{URL::asset('assets/icons/icon_portfolio.png')}}" height="20px" alt="Partners Logo" class="pe-2"> {{__('admin.partners')}}</h5>
                            </div>
                            <div class="card-body p-3 pb-1">
                                <div class=" rfqform_view">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="company_name" class="form-label">{{__('admin.company_name')}} <strong class="text-danger">*</strong></label>
                                            <input type="text" name="company_name" placeholder="{{__('admin.company_name_placeholder')}}" id="company_name" class="form-control">
                                            <small id="company_nameError" class="errorValidate text-danger fw-normal"></small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="" class="form-label mb-2">{{__('admin.logo')}} <strong class="text-danger">*</strong></label>
                                            <div class="d-flex">
                                                <span class="">
                                                    <input type="file" name="partnerImage" class="form-control" id="partnerImage" accept=".jpg,.png,.gif,.jpeg" onchange="showPartnerImg(this)" hidden/>
                                                    <label  class="upload_btn" for="partnerImage">{{__('admin.upload_logo')}}</label>
                                                </span>
                                                <div id="file-partnerImage"></div>
                                            </div>
                                            <small id="partnerImageError" class="errorValidate text-danger fw-normal"></small>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="description" class="form-label">{{__('admin.description')}}</label>
                                            <textarea name="description" id="descriptionField" placeholder="{{__('admin.description')}}" class="form-control" rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{__('admin.save')}}</button>
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">{{__('admin.cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script type="text/javascript">

        var SnippetCompanyPartner = function() {

            var processRecord = function () {
                    $('#companyPartnerFrm').on('submit',function(e){
                        e.preventDefault();
                        $("#companyPartnerFrm #partnerImageError").html('');
                        $('#companyPartnerFrm').find('.errorValidate').html('');
                        var formData = new FormData(this);
                        var url = $('#companyPartnerFrm').attr('action');
                        $.ajax({
                            url: url,
                            data: formData,
                            type: "POST",
                            contentType: false,
                            processData: false,
                            success: function(data) {
                                if(data.success) {
                                    resetToastPosition();
                                    SnippetApp.toast.success("{{ __('admin.success') }}",data.message)
                                    resetCompanyPartnerForm();
                                    $('#partner').modal('toggle');
                                    $('#tblCompanyPartner').DataTable().ajax.reload(null,false);
                                } else {
                                    resetToastPosition();
                                    SnippetApp.toast.alert("{{ __('admin.warning') }}",data.message)
                                }
                            },
                            error:function(e){
                                if (e.status == 422){
                                    $.each(e.responseJSON.errors, function(key,value) {
                                        $("#companyPartnerFrm").find('#'+key+'Error').html('');
                                        $("#companyPartnerFrm").find('#'+key+'Error').html(value);
                                    });
                                    $("#companyPartnerFrm").find(".errorValidate:not(:empty)").parent().find("input:visible:empty").first().focus();
                                } else {
                                    SnippetApp.toast.alert("{{ __('admin.warning') }}","{{__('admin.something_went_wrong')}}");
                                }
                            }
                        });
                    });


                },

                edit = function () {
                    $(document).on('click','.editPartner', function() {
                        var id = $(this).data('id');
                        $("#partnerId").val(id);
                        var url = "{{ route('supplier.members.edit',':id') }}";
                        url = url.replace(":id", id);
                        $.ajax({
                            url: url,
                            dataType: "JSON",
                            success: function(response) {
                                $("#companyPartnerFrm #salutation").val(response.data.salutation);
                                $("#companyPartnerFrm #company_name").val(response.data.company_name);
                                $("#companyPartnerFrm #descriptionField").val(response.data.description);
                                if(response.data.image != "" && response.data.image){
                                    var extensionLogo = response.data.image.split('.').pop();
                                    var imageFileName = response.data.member_image+'.'+extensionLogo;
                                    var closeIcon = '{{URL::asset("assets/icons/times-circle copy.png")}}';
                                    var imgString = '<input type="hidden" id="oldmemberImage" name="oldmemberImage" value="'+response.data.image+'">';
                                    imgString += '<span class="ms-2"><a href="javascript:void(0)" id="partnerFileDownload" style="text-decoration: none;">'+imageFileName+'</a></span>';
                                    imgString += '<span class="partnerFile" data-fileName="'+imageFileName+'" data-type="delete" data-id="'+response.data.id+'"><a href="#" title="Remove Image"><img src="'+closeIcon+'" class="ms-2"></a></span>';
                                    imgString += '<span class="ms-2 fs-6 partnerFile" data-fileName="'+imageFileName+'" data-type="download" data-id="'+response.data.id+'"><a  href="javascript:void(0);" title="Download Image" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>';
                                    $("#file-partnerImage").html(imgString);
                                }
                                $('#partner').modal('toggle');
                                $('#companyPartnerFrm').attr('action','/supplier/members/update/'+id);

                            },
                            error:function(e){
                                resetToastPosition();
                                SnippetApp.toast.alert("{{ __('admin.warning') }}","{{__('admin.something_went_wrong')}}");

                            }
                        });

                    });
                },

                removeFile = function () {
                    $(document).on("click", ".partnerFile", function(e) {
                        e.preventDefault();
                        var id = $(this).data("id");
                        var type = $(this).data("type");
                        var data = {
                            id: $(this).attr("data-id"),
                            fileName: $(this).attr("data-fileName"),
                            imageType: 'partner',
                            type: type,
                            imageFileId: "#file-partnerImage",
                            dataTableId: "#tblCompanyPartner",
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
                                    $("#file-partnerImage").html('');
                                }
                            });
                        }

                    });

                },

                removeRecord = function () {
                    $(document).on("click", ".deletePartner", function(e) {
                        e.preventDefault();
                        var id = $(this).data("id");
                        var data = {
                            id: $(this).attr("data-id"),
                            type: 'deleteCompanyMembers',
                            company_user_type: '3',
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
                                            $('#tblCompanyPartner').DataTable().ajax.reload(null,false);
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
                    $('#partner').on('hidden.bs.modal', function (e) {
                        resetCompanyPartnerForm();
                    });
                },

                resetCompanyPartnerForm = function() {
                    $('#companyPartnerFrm')[0].reset();
                    $('#companyPartnerFrm').find('.errorValidate').html('');
                    $("#partnerId").val('');
                    $('form#companyPartnerFrm').find('#file-partnerImage').html('');
                    $("form#companyPartnerFrm #partnerImageError").html('');
                    var id = $('#companyPartnerFrm').attr('data-id');
                    $('#companyPartnerFrm').attr('action','/supplier/members/store/'+id);

                },

                showProfileImg = function () {
                    $(document).on('change', '#partnerImage', function() {
                        var input = this;
                        var file = input.files[0];
                        var size = Math.round((file.size / 1024))
                        if (size > 3000) {
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
                            var image_path = '{{URL::asset("assets/icons/times-circle copy.png")}}';
                            text = '{{ __('admin.only_upload_image_file') }}';
                            for (var i = 0; i < allowed_extensions.length; i++) {
                                if (allowed_extensions[i] == file_extension) {
                                    valid = true;
                                    if (fileName.beforeLastIndex(".").length >= 30) {
                                        fileName = fileName.substring(0, 30) + '....' + file_extension;
                                    }
                                    $('#file-' + input.name).html('');
                                    $('#file-' + input.name).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.name + 'Download" style="text-decoration: none">' + fileName + '</a></span>');
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
                init:function(){
                    processRecord(),
                    edit(),
                    removeFile(),
                    removeRecord(),
                    initCompo(),
                    showProfileImg()
                }
            }

        }(1);

        jQuery(document).ready(function(){
            SnippetCompanyPartner.init();
        });
    </script>
</div>
