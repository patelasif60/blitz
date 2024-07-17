<div>
    <style type="text/css">
        .loader{position: absolute; top: 0; bottom: 0; right: 0; left: 0; background: rgba(97, 100, 99, 0.5); z-index: 10;}
        .uploadBtnDisabled{ background-color: rgb(151 160 204) !important;pointer-events: none;}
    </style>
    <div class="card mb-3">
        <div class="card-header d-flex align-items-center">
            <h5 class="mb-0"><img height="20px" src="{{ URL::asset('assets/icons/icon_gallery.png') }}" alt="Gallery Details" class="pe-2">
                <span>{{ __('admin.gallery') }}</span>
            </h5>
        </div>
        <div class="card-body p-3 pb-1">
            <div class="row">
                <div class="col-md-4 col-lg-3 mb-3">
                    <form class="h-100 " id="groupImageEdit" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="supplier_id_gallary" value="{{ $supplierId }}">
                        <div class="d-flex p-3 flex-column align-items-center justify-content-center h-100">
                            <p class="text-primary text-center">{{__('admin.max_image_upload')}}</p>

                            <label for="image-order_latter" class="form-label align-items-center">{{ __('admin.upload_images') }}</label>
                            <span class="">
                                <input multiple type="file" name="group_image[]" id="image-group_image" accept=".jpg,.png" hidden="">
                                <label id="upload_btn" for="image-group_image">{{ __('admin.browse') }}</label>
                            </span>
                            <button type="button" class="btn btn-info btn-sm mt-3 w-100 d-none" id="uploadImages">Upload</button>
                        </div>
                    </form>

                </div>
                <div class="col-md-8 col-lg-9 mb-3">
                    <!-- thumb preview image upload -->
                    <div id="thumb-output" class="float-start">
                        <div id="lightgallery" class="lightGallery float-start w-auto"></div>
                        <div id="loaderDiv"></div>
{{--                        <div class="loader d-flex align-items-center justify-content-center" style="display: none"></div>--}}
                    </div>

                    <!--end thumb image upload -->
                    <!-- uploaded image gallery -->
{{--                    <div id="lightgallery"--}}
{{--                         class="lightGallery float-start w-auto">--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>
</div>
@push('gallery-scripts')
    <script type="text/javascript">
        $(function () {
            var SupplierGallaryImages = function() {
                showImages = function() {
                    $('#image-group_image').on('change', function(e) {
                        var countNoOfImage = (this.files.length) + ($("#lightgallery").find('.previewImageDiv').length);
                        if (countNoOfImage > 10) {
                            SnippetApp.swal.critical("Sorry","{{ __('admin.maximum_ten_images_upload') }}");
                        } else {
                            var fileList = this.files;
                            var anyWindow = window.URL || window.webkitURL;
                            for(var i = 0; i < fileList.length; i++){
                                //get a blob to play with
                                var objectUrl = anyWindow.createObjectURL(fileList[i]);
                                var previewString = '<div id="preview_images_0" class="position-relative previewImageDiv m-1 d-inline-block isNewImage"><div class="input-group-text bg-white p-0 border-0"><a href="javascript:void(0)" class="text-dacoration-none text-white bg-danger py-1 px-2 removePreviewImage" value="0" data-id="0" file-index="'+i+'" name="group_images[]"><i class="fa fa-close"></i></a></div><div class="lightgallery_img item" data-src="img/1.jpg"><img src="'+objectUrl+'" alt="image small"></div></div>';
                                $('#lightgallery').append(previewString);
                                window.URL.revokeObjectURL(fileList[i]);
                            }
                            $("#image-group_image").prop('readonly',true);
                            $("#upload_btn").addClass('uploadBtnDisabled');
                            $("#uploadImages").removeClass('d-none').addClass('d-block');
                            $("#uploadImages").fadeOut(300).fadeIn(200).fadeOut(200).fadeIn(300);
                        }
                    });
                },
                removeImage = function () {
                    $(document).on('click','.removePreviewImage',function () {
                        var imgId = $(this).data('id');
                        if(imgId > 0){
                            $.ajax({
                                url: "{{ route('admin.supplier.galleryImageDelete') }}",
                                type: "POST",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                beforeSend: function () {
                                    $("#loaderDiv").html('<div class="loader d-flex align-items-center justify-content-center"><img src="/assets/images/loader.gif" alt="load" height="80px"></div>');
                                },
                                data: {id:imgId},
                                success: function(successData) {
                                    if (successData.success == true) {
                                        $("#loaderDiv").html('');
                                        resetToastPosition();
                                        SnippetApp.toast.success("{{ __('admin.success') }}", "{{ __('admin.image_deleted') }}");
                                        $("#preview_images_"+imgId).remove();
                                    }
                                },
                                error: function() {
                                    console.log("error");
                                },
                            });
                        }else{
                            var index = $(this).attr('file-index');
                            var attachments = document.getElementById("image-group_image").files;
                            var fileBuffer = new DataTransfer();
                            for (let i = 0; i < attachments.length; i++) {
                                if (index != i){
                                    fileBuffer.items.add(attachments[i]);
                                }
                            }
                            document.getElementById("image-group_image").files = fileBuffer.files;
                            $(this).closest('.previewImageDiv').remove();
                        }
                        if($("#lightgallery").find('.previewImageDiv.isNewImage').length > 0){
                            $("#uploadImages").removeClass('d-none').addClass('d-block');
                            $("#image-group_image").prop('readonly',true);
                            $("#upload_btn").addClass('uploadBtnDisabled');
                        }else{
                            $("#uploadImages").removeClass('d-block').addClass('d-none');
                            $("#image-group_image").prop('readonly',false);
                            $("#upload_btn").removeClass('uploadBtnDisabled');
                        }
                    });
                },
                uploadMultipleImages = function() {
                    $(document).on('click','#uploadImages',function () {
                        var formData = new FormData($("#groupImageEdit")[0]);
                        $.ajax({
                            url: "{{ route('admin.supplier.gallaryImage') }}",
                            data: formData,
                            type: "POST",
                            contentType: false,
                            processData: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(successData) {
                                if (successData.supplierId) {
                                    supplier_id = successData.supplierId;
                                    SupplierGallaryImages.getAllSupplierImages(supplier_id);
                                    resetToastPosition();
                                    SnippetApp.toast.success("{{ __('admin.success') }}", "{{ __('admin.group_image_uploaded') }}");
                                    $("#groupImageEdit")[0].reset();
                                    $("#uploadImages").removeClass('d-block').addClass('d-none');
                                    $("#image-group_image").prop('readonly',false);
                                    $("#upload_btn").removeClass('uploadBtnDisabled');
                                }
                            },
                            error: function() {
                                console.log("error");
                            },
                        });
                    });
                }
                return{
                    init: function() {
                        showImages(),
                        removeImage(),
                        uploadMultipleImages()
                    },
                    getAllSupplierImages : function(id) {
                        $("#loaderDiv").html('<div class="loader d-flex align-items-center justify-content-center"><img src="/assets/images/loader.gif" alt="load" height="80px"></div>');
                        $.ajax({
                            url: "{{ route('admin.supplier.get-supplier-images-ajax', '') }}" + "/" + id,
                            type: "GET",
                            success: function (successData) {
                                var previewString = '';
                                for (var i = 0; i < successData.groupImages.length; i++) {
                                    let idx = successData.groupImages[i].id;
                                    let images = successData.groupImages[i].image;
                                    let imgPath = '{{ url('storage/')}}'+'/'+images;
                                    previewString += '<div id="preview_images_'+idx+'" class="position-relative previewImageDiv m-1 d-inline-block"><div class="input-group-text bg-white p-0 border-0"><a href="javascript:void(0)" class="text-dacoration-none text-white bg-danger py-1 px-2 removePreviewImage delete_all" value="' + idx + '" data-id= "' + idx + '" name="group_images[]"><i class="fa fa-close"></i></a></div><div class="lightgallery_img item" data-src="img/1.jpg"><img src="'+imgPath+'" alt="image small"></div></div>';
                                }
                                setTimeout(function(){
                                    $('#lightgallery').html(previewString);
                                    $("#loaderDiv").html('');
                                }, 200);
                                if($("#lightgallery").find('.previewImageDiv.isNewImage').length == 0){
                                    $("#uploadImages").removeClass('d-block').addClass('d-none');
                                }
                            },
                            error: function () {
                                console.log("error");
                            },
                        });
                    }
                }
            }(1);
            SupplierGallaryImages.init();
            SupplierGallaryImages.getAllSupplierImages($("#supplier_id_gallary").val());
        });


    </script>
@endpush
