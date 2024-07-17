<div>
    <style>
        table#tblCompanyHighlights tbody tr td {
            word-wrap: break-word;
            word-break: break-all;
        }
        #tblCompanyHighlights_processing{
            padding: 0.5rem 0;
        }
    </style>
    <form id="highlightsFrm" method="POST" enctype="multipart/form-data" autocomplete="off" action="{{ route('supplier.highlight.store',Crypt::encrypt($supplierId)) }}" data-id="{{Crypt::encrypt($supplierId)}}">
        @csrf
        <input type="hidden" name="formType" value="highlights">
        <input type="hidden" name="id" id="highLightId" value="">
        <input type="hidden" name="userId" id="userId" value="{{$supplierId}}">
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center">
                <h5 class="mb-0">
                    <img height="20px" src="{{URL::asset('assets/icons/icon_highlights.png')}}" alt="Company Highlights" class="pe-2">
                    <span>{{ __('admin.company_achievement')}}</span>
                </h5>
            </div>
            <div class="card-body p-3 newtable_v2 ">
                <div class="d-flex align-items-center justify-content-end pe-1 mb-3">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#Highlights">{{__('admin.add')}}</button>
                </div>
                <div class="table-responsive">
                @include('livewire.supplier.profile.company-highlights-datatable')
                </div>
            </div>
        </div>
        <div class="text-end">
            <button class="btn btn-primary" onclick="$('#pills-others-tab').click()" >{{__('admin.next')}}</button>
        </div>
        <div class="modal fade version2 error_res" id="Highlights" tabindex="-1" role="dialog" aria-labelledby="Highlights" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 800px;" role="document">
                <div class="modal-content border-0">
                    <div class="modal-header p-3">
                        <h3 class="modal-title" style="color: white;" id="Highlights">{{__('admin.company_achievement')}} </h3>
                        <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close" style="margin: -26px 5px -18.5px auto">
                            <img src="{{URL::asset('front-assets/images/icons/times.png')}}" alt="Close">
                        </button>
                    </div>
                    <div class="modal-body p-3" style="background-color: #ebedf1;">
                        <div class="card">
                            <div class="card-header align-items-center" style="background-color: #d7d9df;">
                                <h5 class="mb-0">
                                    <img src="{{URL::asset('assets/icons/icon_highlights.png')}}" height="20px" class="pe-2"> {{__('admin.company_achievement')}}</h5>
                            </div>
                            <div class="card-body p-3 pb-1">
                                <div class=" rfqform_view">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="contactPersonEmail" class="form-label"> {{ __('admin.achievement_category')}} <span class="text-danger">*</span> </label>
                                            <select class="form-select" name="category" id="category" >
                                                <option value="">{{__('admin.select_achievement_category')}}</option>
                                                <option value="1">{{__('admin.awards')}}</option>
                                                <option value="2">{{__('admin.certificate')}}</option>
                                                <option value="3">{{__('admin.media_recognition')}}</option>
                                            </select>
                                            <small id="category_Error" class="errorValidate text-danger fw-normal" ></small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">{{ __('admin.name')}}<span class="text-danger">*</span> </label>
                                            <input type="text" name="name" id="name" placeholder="{{ __('admin.name_placeholder')}}" class="form-control">
                                            <small id="name_Error" class="errorValidate text-danger fw-normal" ></small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="number" class="form-label">{{ __('admin.number')}}</label>
                                            <input type="text" name="number" id="number" placeholder="{{__('admin.number_placehoder')}}"  class="form-control" value="">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="image" class="form-label mb-2">{{__('admin.photo')}} <span class="text-danger">*</span></label>
                                            <div class="d-flex">
                                                <span class="">
                                                    <input type="file" name="highlightImage" class="form-control" id="highlightImage" accept=".jpg,.png,.gif,.jpeg" onchange="showHighlightImg(this)" hidden/>
                                                    <label  class="upload_btn" for="highlightImage">{{ __('admin.upload_image')}}</label>
                                                </span>
                                                <div id="file-highlightImage"></div>
                                            </div>
                                            <small id="highlightImage_Error" class="errorValidate text-danger fw-normal"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="saveHighlightData">{{ __('admin.save')}}</button>
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">{{ __('admin.cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script type="text/javascript">
        $(function () {
            $('#Highlights').on('hidden.bs.modal', function (e) {
                resetHighlightsForm();
            });
            $(document).on("click", ".highLightFile", function(e) {
                e.preventDefault();
                var id = $(this).data("id");
                var type = $(this).data("type");
                var data = {
                    id: $(this).attr("data-id"),
                    fileName: $(this).attr("data-fileName"),
                    imageType: 'highLights',
                    type: type,
                    imageFileId: "#file-highlightImage",
                    dataTableId: "#tblCompanyHighlights",
                    route: "{{ route('supplier.highlight.deletedownloadimages') }}",
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
                            $("#file-highlightImage").html('')
                        }
                    });
                }

            });
            $(document).on('click','.editHighlight', function() {
                var id = $(this).data('id');
                $("#highLightId").val(id);
                var url = "{{ route('supplier.highlight.edit',':id') }}";
                url = url.replace(":id", id);
                $.ajax({
                    url: url,
                    dataType: "JSON",
                    success: function(response) {
                        $("#highlightsFrm #category").val(response.data.category);
                        $("#highlightsFrm #name").val(response.data.name);
                        $("#highlightsFrm #number").val(response.data.number);
                        if(response.image != "" && response.data.image){
                            var extensionLogo = response.data.image.split('.').pop();
                            var imageFileName = response.data.highlight_image+'.'+extensionLogo;
                            var closeIcon = '{{URL::asset("assets/icons/times-circle copy.png")}}';
                            var imgString = '<input type="hidden" id="oldhighlightImage" name="oldhighlightImage" value="'+response.data.image+'">';
                            imgString += '<span class="ms-2"><a href="javascript:void(0)" id="logoFileDownload" style="text-decoration: none;">'+imageFileName+'</a></span>';
                            imgString += '<span class="highLightFile" data-fileName="'+imageFileName+'" data-type="delete" data-id="'+response.data.id+'"><a href="#" title="Remove Image"><img src="'+closeIcon+'" class="ms-2"></a></span>';
                            imgString += '<span class="ms-2 fs-6 highLightFile" data-fileName="'+imageFileName+'" data-type="download" data-id="'+response.data.id+'"><a  href="javascript:void(0);" title="Download Image" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>';
                            $("#file-highlightImage").html(imgString);
                        }
                        $('#Highlights').modal('toggle');
                        $('#highlightsFrm').attr('action','/supplier/highlight/update/'+id);
                    },
                    error:function(e){
                        resetToastPosition();
                        SnippetApp.toast.alert("{{ __('admin.warning') }}","{{__('admin.something_went_wrong')}}");

                    }
                });

            })
            $('#highlightsFrm').on('submit',function(e){
                e.preventDefault();
                $("#highlightsFrm").find('.errorValidate').html('');
                var formData = new FormData(this);
                var url = $('#highlightsFrm').attr('action');
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
                            resetHighlightsForm();
                            $('#Highlights').modal('toggle');
                            $('#tblCompanyHighlights').DataTable().ajax.reload(null,false);
                        } else {
                            resetToastPosition();
                            SnippetApp.toast.alert("{{ __('admin.warning') }}","{{__('admin.something_went_wrong')}}");

                        }
                    },
                    error:function(e){
                        if (e.status == 422){
                            $('form#highlightsFrm').find('#highlightImage_Error').html('');
                            $.each(e.responseJSON.errors, function(key,value) {
                                $('#'+key+'_Error').html('');
                                $('#'+key+'_Error').append(value);
                            });
                            $("#highlightsFrm").find(".errorValidate:not(:empty)").parent().find("input:visible:empty").first().focus();
                        } else {
                            SnippetApp.toast.alert("{{ __('admin.warning') }}","{{__('admin.something_went_wrong')}}");

                        }
                    }
                });
            });
            $(document).on("click", ".deleteHighlight", function(e) {
                e.preventDefault();
                var id = $(this).data("id");
                var data = {
                    id: $(this).attr("data-id"),
                    type: 'deleteHighlight',
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
                            url: "{{ url('/admin/update-supplier-company-highlights') }}",
                            data: data,
                            type: "POST",
                            success: function(response) {
                                if(response.result){
                                    resetToastPosition();
                                    $.toast({
                                        text: response.message,
                                        showHideTransition: "slide",
                                        heading: "{{ __('admin.success') }}",
                                        icon: "success",
                                        loaderBg: "#f96868",
                                        position: "top-right",
                                    });
                                    $('#tblCompanyHighlights').DataTable().ajax.reload(null,false);
                                }else{
                                    swal("Error:", "Error in deleting highlight.")
                                }
                            },
                        });

                    }
                });
            });
        });
        function showHighlightImg(input) {
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
        }

        function resetHighlightsForm() {
            $('#highlightsFrm')[0].reset();
            $("#highLightId").val('');
            $('#highlightsFrm').parsley().reset();
            $('form#highlightsFrm').find('#file-highlightImage').html('');
            $('form#highlightsFrm').find('.errorValidate').html('');
            var id = $('#highlightsFrm').attr('data-id');
            var url = "{{route('supplier.highlight.store',':id')}}";
            url = url.replace(':id',id);
            $('#highlightsFrm').attr('action',url);
        }
    </script>
</div>
