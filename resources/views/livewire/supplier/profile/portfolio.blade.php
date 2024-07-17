<div>
    <style type="text/css">
        #tblCompanyPortfolio_processing{
            padding: 0.5rem 0;
        }
    </style>
    <form id="companyPortfolioFrm" method="POST" enctype="multipart/form-data" autocomplete="off" action="{{ route('supplier.members.store',Crypt::encrypt($supplierId)) }}" data-id="{{Crypt::encrypt($supplierId)}}">
        @csrf
        <input type="hidden" name="formType" value="portfolio" >
        <input type="hidden" name="userId" id="userId" value="{{$supplierId}}">
        <input type="hidden" name="id" id="portfolioId" value="">
        <input type="hidden" name="company_user_type_id" id="company_user_type_id" value="4">
        <input type="hidden" name="typeName" id="typeName" value="Company Portfolio">

        <div class="card mb-3">
            <div class="card-header d-flex align-items-center">
                <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/icon_portfolio.png')}}" alt="Portfolio" class="pe-2">
                    <span>{{__('admin.client_supplier_portfolio')}}</span>
                </h5>
            </div>
            <div class="card-body p-3 newtable_v2 ">
                <div class="d-flex align-items-center justify-content-end pe-1 mb-3">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#portfolio">{{__('admin.add')}}</button>
                </div>
                <div class="table-responsive">
                    @include('livewire.supplier.profile.portfolio-datatable')
                </div>
            </div>
        </div>
        <div class="text-end">
            <button class="btn btn-primary" onclick="$('#pills-Highlights-tab').click()" >{{__('admin.next')}}</button>
        </div>
        <div class="modal fade version2 error_res" id="portfolio" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 800px;" role="document">
                <div class="modal-content border-0">
                    <div class="modal-header p-3">
                        <h3 class="modal-title" style="color: white;">{{__('admin.add')}}</h3>
                        <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close" style="margin: -26px 4px -13px auto">
                            <img src="{{URL::asset('front-assets/images/icons/times.png')}}" alt="Close">
                        </button>
                    </div>
                    <div class="modal-body p-3" style="background-color: #ebedf1;">
                        <div class="card">
                            <div class="card-header align-items-center" style="background-color: #d7d9df;">
                                <h5 class="mb-0"><img src="{{URL::asset('assets/icons/icon_portfolio.png')}}" height="20px" alt="Portfolio Logo" class="pe-2"> Portfolio</h5>
                            </div>
                            <div class="card-body p-3 pb-1">
                                <div class=" rfqform_view">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="company_name" class="form-label">{{__('admin.company_name')}} <strong class="text-danger">*</strong></label>
                                            <input type="text" name="company_name" id="company_name" placeholder="{{__('admin.company_name_palacehoder')}}" class="form-control">
                                            <small id="company_nameError" class="errorValidate fw-normal text-danger"></small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="sector" class="form-label">{{__('admin.sector')}}<strong class="text-danger">*</strong></label>
                                            <select name="sector" id="sector" class="form-select">
                                                <option value="1">Primary Sector</option>
                                                <option value="2">Secondary Sector</option>
                                                <option value="3">Tertiary Sector</option>
                                            </select>
                                            <small id="sectorError" class="errorValidate fw-normal text-danger"></small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="registration_NIB" class="form-label">{{__('admin.registation_nib')}}</label>
                                            <input type="text" name="registration_NIB" id="registration_NIB" placeholder="{{__('admin.registation_nib_palceholder')}}" class="form-control input-number input-nib">
                                            <small id="registration_NIBError" class="errorValidate fw-normal text-danger"></small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="position" class="form-label">{{ __('admin.position')}}</label>
                                            <input type="text" name="position" id="position" class="form-control max-255" placeholder="{{__('admin.position_palceholder')}}">
                                            <small id="positionError" class="errorValidate fw-normal text-danger"></small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="website" class="form-label">{{ __('admin.type')}}<strong class="text-danger">*</strong></label>
                                            <div class="ps-4">
                                                <div class="form-check form-check-inline me-3 mb-0">
                                                    <input class="form-check-input mt-1 max-255" type="radio" name="portfolio_type" id="client_portfolio" value="1" >
                                                    <label class="form-check-label" for="client_portfolio">{{ __('admin.client_portfolio')}}</label>
                                                </div>
                                                <div class="form-check form-check-inline mb-0">
                                                    <input class="form-check-input mt-1 " type="radio" name="portfolio_type" id="supplier_portfolio" value="2">
                                                    <label class="form-check-label" for="supplier_portfolio">{{ __('admin.supplier_portfolio')}}</label>
                                                </div>
                                            </div>
                                            <small id="portfolio_typeError" class="errorValidate fw-normal text-danger" ></small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="" class="form-label mb-2"> {{ __('admin.logo')}}<strong class="text-danger">*</strong> </label>
                                            <div class="d-flex">
                                                <span class="">
                                                    <input type="file" name="portfolioImage" class="form-control" id="portfolioImage" accept=".jpg,.png,.gif,.jpeg" onchange="showPartnerImg(this)" hidden/>
                                                    <label  class="upload_btn" for="portfolioImage">{{ __('admin.upload_logo')}} </label>
                                                </span>
                                                <div id="file-portfolioImage"></div>
                                            </div>
                                            <small id="portfolioImageError" class="errorValidate fw-normal text-danger"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('admin.save')}}</button>
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">{{ __('admin.cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script type="text/javascript">

        var SnippetPortfoilo = function () {

            var processRecord = function () {
                    $('#companyPortfolioFrm').on('submit',function(e){
                        e.preventDefault();
                        $("#companyPortfolioFrm").find('.errorValidate').html('');
                        var formData = new FormData(this);
                        var url = $('#companyPortfolioFrm').attr('action');

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
                                    $('#portfolio').modal('toggle');
                                    $('#tblCompanyPortfolio').DataTable().ajax.reload(null,false);
                                } else {
                                    resetToastPosition();
                                    SnippetApp.toast.alert("{{ __('admin.warning') }}",data.message);
                                }
                            },
                            error:function(e){
                                if (e.status == 422){
                                    $("#companyPortfolioFrm .errorValidate").html('');
                                    $.each(e.responseJSON.errors, function(key,value) {
                                        $("#companyPortfolioFrm").find('#'+key+'Error').html(value);
                                    });
                                    $("#companyPortfolioFrm").find(".errorValidate:not(:empty)").parent().find("input:visible:empty").first().focus();
                                } else {
                                    resetToastPosition();
                                    SnippetApp.toast.alert("{{ __('admin.warning') }}","{{__('admin.something_went_wrong')}}");
                                }
                            }
                        });
                    });

                },

                edit = function () {
                    $(document).on('click','.editPortfolio', function() {
                        var id = $(this).data('id');
                        $("#portfolioId").val(id);
                        var url = "{{ route('supplier.members.edit',':id') }}";
                        url = url.replace(":id", id);
                        $.ajax({
                            url: url,
                            dataType: "JSON",
                            success: function(response) {
                                $("#companyPortfolioFrm #company_name").val(response.data.company_name);
                                $("#companyPortfolioFrm #sector").val(response.data.sector);
                                $("#companyPortfolioFrm #registration_NIB").val(response.data.registration_NIB);
                                $("#companyPortfolioFrm #position").val(response.data.position);
                                if(response.data.portfolio_type == "1"){
                                    $("#client_portfolio").prop("checked",true);
                                    $("#supplier_portfolio").prop("checked",false);
                                }else if(response.data.portfolio_type == "2"){
                                    $("#client_portfolio").prop("checked",false);
                                    $("#supplier_portfolio").prop("checked",true);
                                }
                                if(response.data.image != "" && response.data.image){
                                    var extensionLogo = response.data.image.split('.').pop();
                                    var imageFileName = response.data.member_image+'.'+extensionLogo;
                                    var closeIcon = '{{URL::asset("assets/icons/times-circle copy.png")}}';
                                    var imgString = '<input type="hidden" id="oldmemberImage" name="oldmemberImage" value="'+response.data.image+'">';
                                    imgString += '<span class="ms-2"><a href="javascript:void(0)" id="portfolioFileDownload" style="text-decoration: none;">'+imageFileName+'</a></span>';
                                    imgString += '<span class="portfolioFile" data-fileName="'+imageFileName+'" data-type="delete" data-id="'+response.data.id+'"><a href="#" title="Remove Image"><img src="'+closeIcon+'" class="ms-2"></a></span>';
                                    imgString += '<span class="ms-2 fs-6 portfolioFile" data-fileName="'+imageFileName+'" data-type="download" data-id="'+response.data.id+'"><a  href="javascript:void(0);" title="Download Image" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>';
                                    $("#file-portfolioImage").html(imgString);
                                }
                                $('#portfolio').modal('toggle');
                                $('#companyPortfolioFrm').attr('action','/supplier/members/update/'+id);

                            },
                            error:function(e) {
                                resetToastPosition();
                                SnippetApp.toast.alert("{{ __('admin.warning') }}","{{__('admin.something_went_wrong')}}");
                            }
                        });

                    })
                },

                removeFile = function () {
                    $(document).on("click", ".portfolioFile", function(e) {
                        e.preventDefault();
                        var id = $(this).data("id");
                        var type = $(this).data("type");
                        var data = {
                            id: $(this).attr("data-id"),
                            fileName: $(this).attr("data-fileName"),
                            imageType: 'portfolio',
                            type: type,
                            imageFileId: "#file-portfolioImage",
                            dataTableId: "#tblCompanyPortfolio",
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
                                    $("#file-portfolioImage").html('');
                                }
                            });
                        }

                    });

                },

                removeRecord = function () {
                    $(document).on("click", ".deletePortfolio", function(e) {
                        e.preventDefault();
                        var id = $(this).data("id");
                        var data = {
                            id: $(this).attr("data-id"),
                            type: 'deleteCompanyMembers',
                            company_user_type: '4',
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
                                    success: function(data) {
                                        if(data.success){
                                            resetToastPosition();
                                            SnippetApp.toast.success("{{ __('admin.success') }}",data.message)
                                            $('#tblCompanyPortfolio').DataTable().ajax.reload(null,false);
                                        }else{
                                            SnippetApp.toast.alert("{{ __('admin.warning') }}",data.message)
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
                    $('#companyPortfolioFrm')[0].reset();
                    $('#companyPortfolioFrm .errorValidate').html('');
                    $("#portfolioId").val('');
                    $('form#companyPortfolioFrm').find('#file-portfolioImage').html('');
                    var id = $('#companyPortfolioFrm').attr('data-id');
                    $('#companyPortfolioFrm').attr('action','/supplier/members/store/'+id)
                },

                showProfileImg = function () {
                    $(document).on('change', '#portfolioImage', function() {
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
                init:function () {
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
            SnippetPortfoilo.init();
        });
    </script>
</div>
