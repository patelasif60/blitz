<div>
    <style type="text/css">
        #tblCompanyTestimonial_processing{
            padding: 0.5rem 0;
        }
        table#tblCompanyTestimonial tbody tr td {
            word-wrap: break-word;
            word-break: break-all;
        }
    </style>
    <form id="testimonialFrm" method="POST" enctype="multipart/form-data" autocomplete="off" action="{{ route('supplier.members.store',Crypt::encrypt($supplierId)) }}" data-id="{{Crypt::encrypt($supplierId)}}">
        @csrf
        <input type="hidden" name="formType" value="testimonial">
        <input type="hidden" name="id" id="testimonialId" value="">
        <input type="hidden" name="company_user_type_id" id="company_user_type_id" value="2">
        <input type="hidden" name="typeName" id="typeName" value="Testimonial">
        <input type="hidden" name="userId" id="userId" value="{{$supplierId}}">

        <div class="card mb-3">
            <div class="card-header d-flex align-items-center">
                <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/comment-alt-edit.png')}}" alt="Company" class="pe-2">
                    <span>{{__('admin.testimonial_details')}}</span>
                </h5>
            </div>
            <div class="card-body p-3 newtable_v2 ">
                <div class="d-flex align-items-center justify-content-end pe-1 mb-3">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-supplierId="{{Crypt::encrypt($supplierId)}}" data-bs-target="#testimonial" id="testimonialAdd">{{__('admin.add')}}</button>
                </div>
                <div class="table-responsive">
                    @include('livewire.supplier.profile.testimonial-datatable')
                </div>
            </div>
        </div>
        <div class="modal fade version2 error_res" id="testimonial" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 800px;" role="document">
                <div class="modal-content border-0">
                    <div class="modal-header p-3">
                        <h3 class="modal-title" style="color: white;">{{__('admin.testimonial_details')}}
                        </h3>
                        <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close" style="margin: -26px 4px -13px auto">
                            <img src="{{URL::asset('front-assets/images/icons/times.png')}}" alt="Close">
                        </button>
                    </div>
                    <div class="modal-body p-3" style="background-color: #ebedf1;">
                        <div class="card">
                            <div class="card-header align-items-center" style="background-color: #d7d9df;">
                                <h5 class="mb-0"><img src="{{URL::asset('assets/icons/comment-alt-edit.png')}}" height="20px" alt="Testimonial Logo" class="pe-2"> {{__('admin.testimonial') }} </h5>
                            </div>
                            <div class="card-body p-3 pb-1">
                                <div class=" rfqform_view">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="salutation" class="form-label"> {{__('admin.first_name') }} <strong class="text-danger">*</strong>
                                            </label>
                                            <div class="d-flex">
                                                <select name="salutation" class="form-select w100p border-end-0" id="salutation" style="border-radius: 0px;  background-color: rgba(0, 0, 0, 0.05); font-size: 0.875rem; width: 130px">
                                                    <option value="Mr." selected="">{{ __('admin.salutation_mr')}}</option>
                                                    <option value="Ms.">{{ __('admin.salutation_ms')}}</option>
                                                    <option value="Mrs.">{{ __('admin.salutation_mrs')}}</option>
                                                </select>
                                                <input type="text" name="firstname" id="firstname" placeholder="{{__('admin.first_name_palaceholder')}}" class="form-control input-alpha-numeric">
                                            </div>
                                            <small id="firstname_Error" class="errorValidate fw-normal text-danger"></small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="lastname" class="form-label">{{__('admin.last_name') }} <strong class="text-danger">*</strong>
                                            </label>
                                            <input type="text" name="lastname" id="lastname" placeholder="{{__('admin.lastname_placeholder')}}" class="form-control input-alpha-numeric" >
                                            <small id="lastname_Error"class="errorValidate fw-normal text-danger"></small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="company_name" class="form-label">{{__('admin.company_name') }} <strong class="text-danger">*</strong> </label>
                                            <input type="text" name="company_name" id="company_name" class="form-control" placeholder="{{__('admin.company_name_placeholder') }}" >
                                            <small id="company_name_Error" class="errorValidate fw-normal text-danger"></small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="designation" class="form-label">{{__('admin.position') }} </label>
                                            <input type="text" name="designation" id="designation" class="form-control" placeholder="{{__('admin.position_placeholder') }}" >
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="quote" class="form-label">{{__('admin.quote') }}  </label>
                                            <input type="text" name="quote" id="quote" class="form-control" placeholder="{{__('admin.quote_placeholder') }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="" class="form-label mb-2">{{__('admin.photo') }} <strong class="text-danger">*</strong> </label>
                                            <div class="d-flex">
                                                <span class="">
                                                    <input type="file" name="testimonialImage" class="form-control" id="testimonialImage" accept=".jpg,.png,.gif,.jpeg" onchange="showTestimonialImg(this)" hidden/>
                                                    <label  class="upload_btn" for="testimonialImage">{{__('admin.upload_photo') }}</label>
                                                </span>
                                                <div id="file-testimonialImage"></div>
                                            </div>
                                            <small id="testimonialImage_Error" class="errorValidate fw-normal text-danger"></small>

                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="description" class="form-label">{{__('admin.description') }}</label>
                                            <textarea name="description" id="descriptionField" class="form-control" placeholder="{{__('admin.description') }}"></textarea>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{__('admin.save')}}</button>
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">{{__('admin.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script type="text/javascript">
        var SnippetTestimonial = function() {

            var processRecord = function () {
                    $('#testimonialFrm').on('submit',function(e){
                        e.preventDefault();
                        $("#testimonialFrm").find('.errorValidate').html('');
                        var formData = new FormData(this);
                        var url = $('#testimonialFrm').attr('action');
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
                                    $('#testimonial').modal('toggle');
                                    $('#tblCompanyTestimonial').DataTable().ajax.reload(null,false);
                                } else {
                                    resetToastPosition();
                                    SnippetApp.toast.success("{{ __('admin.warning') }}", data.message);
                                }
                            },
                            error:function(e){
                                if (e.status == 422){
                                    $.each(e.responseJSON.errors, function(key,value) {
                                        $("#testimonialFrm").find('#'+key+'_Error').html('');
                                        $("#testimonialFrm").find('#'+key+'_Error').html(value);
                                    });
                                    $("#testimonialFrm").find(".errorValidate:not(:empty)").parent().find("input:visible:empty").first().focus();
                                } else {
                                    SnippetApp.toast.success("{{ __('admin.warning') }}","{{ __('admin.something_went_wrong') }}");

                                }
                            }
                        });
                    });

                },

                edit = function () {
                    $(document).on('click','.editTestimonial', function() {
                        var id = $(this).data('id');
                        $("#testimonialId").val(id);
                        var url = "{{ route('supplier.members.edit',':id') }}";
                        url = url.replace(":id", id);
                        $.ajax({
                            url: url,
                            dataType: "JSON",
                            success: function(response) {
                                $("#testimonialFrm #salutation").val(response.data.salutation);
                                $("#testimonialFrm #firstname").val(response.data.firstname);
                                $("#testimonialFrm #lastname").val(response.data.lastname);
                                $("#testimonialFrm #designation").val(response.data.designation);
                                $("#testimonialFrm #company_name").val(response.data.company_name);
                                $("#testimonialFrm #quote").val(response.data.quote);
                                $("#testimonialFrm #descriptionField").val(response.data.description);
                                if(response.data.image != "" && response.data.image){
                                    var extensionLogo = response.data.image.split('.').pop();
                                    var imageFileName = response.data.member_image+'.'+extensionLogo;
                                    var closeIcon = '{{URL::asset("assets/icons/times-circle copy.png")}}';
                                    var imgString = '<input type="hidden" id="oldmemberImage" name="oldmemberImage" value="'+response.data.image+'">';
                                    imgString += '<span class="ms-2"><a href="javascript:void(0)" id="testimonialFileDownload" style="text-decoration: none;">'+imageFileName+'</a></span>';
                                    imgString += '<span class="testimonialFile" data-fileName="'+imageFileName+'" data-type="delete" data-id="'+response.data.id+'"><a href="#" title="Remove Image"><img src="'+closeIcon+'" class="ms-2"></a></span>';
                                    imgString += '<span class="ms-2 fs-6 testimonialFile" data-fileName="'+imageFileName+'" data-type="download" data-id="'+response.data.id+'"><a  href="javascript:void(0);" title="Download Image" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>';
                                    $("#file-testimonialImage").html(imgString);
                                }
                                $('#testimonial').modal('toggle');
                                $('#testimonialFrm').attr('action','/supplier/members/update/'+id);

                            },
                            error:function(e){
                                resetToastPosition();
                                $.toast({
                                    heading: "{{ __('admin.warning') }}",
                                    text: 'Something went wrong!',
                                    showHideTransition: "slide",
                                    icon: "error",
                                    loaderBg: "#fc5661",
                                    position: "top-right",
                                });
                            }
                        });

                    })

                },

                removeFile = function () {
                    $(document).on("click", ".testimonialFile", function(e) {
                        e.preventDefault();
                        var id = $(this).data("id");
                        var type = $(this).data("type");
                        var data = {
                            id: $(this).attr("data-id"),
                            fileName: $(this).attr("data-fileName"),
                            imageType: 'testimonial',
                            type: type,
                            imageFileId: "#file-testimonialImage",
                            dataTableId: "#tblCompanyTestimonial",
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
                                    $("#file-testimonialImage").html('');
                                }
                            });
                        }

                    });

                },

                removeRecord = function () {
                    $(document).on("click", ".deleteTestimonial", function(e) {
                        e.preventDefault();
                        var id = $(this).data("id");
                        var data = {
                            id: $(this).attr("data-id"),
                            type: 'deleteCompanyMembers',
                            company_user_type: '2',
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
                                            $('#tblCompanyTestimonial').DataTable().ajax.reload(null,false);
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
                    $('#portfolio').on('hidden.bs.modal', function (e) {
                        resetForm();
                    });
                },

                resetForm = function() {
                    $('#testimonialFrm')[0].reset();
                    $("#testimonialId").val('');
                    $('#testimonialFrm').parsley().reset();
                    $('form#testimonialFrm').find('#file-testimonialImage').html('');
                    var id = $('#testimonialFrm').attr('data-id');
                    var url = "{{route('supplier.members.store',':id')}}";
                    url = url.replace(':id',id);
                    $('#testimonialFrm').attr('action',url);
                },

                showProfileImg = function () {
                    $(document).on('change', '#testimonialImage', function(){
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
                            var image_path = '{{URL::asset("assets/icons/times-circle copy.png")}}';
                            text = '{{ __('admin.only_upload_image_file') }}';
                            for (var i = 0; i < allowed_extensions.length; i++) {
                                if (allowed_extensions[i] == file_extension) {
                                    valid = true;
                                    if(fileName.beforeLastIndex(".").length >= 20) {
                                        fileName = fileName.substring(0,20) +'....'+file_extension;
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
            SnippetTestimonial.init();
        });
    </script>
</div>
