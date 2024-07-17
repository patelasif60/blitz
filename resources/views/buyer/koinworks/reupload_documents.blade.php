@extends('buyer/layouts/backend/backend_single_layout')
@php
    $authId = auth()->id();
@endphp

@section('css')
    <link href="{{ URL::asset('front-assets/js/front/crop/css/style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/js/front/crop/css/style-example.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/js/front/crop/css/jquery.Jcrop.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/croppie.min.css') }}" rel='stylesheet' />
    <link href="{{ URL::asset('front-assets/js/datatables-bs4/dataTables.bootstrap4.css') }}" rel='stylesheet' />

@endsection

@section('custom-css')
    <style>
        .error {
            color: red;
        }

        #userCompanyDetailsForm .select2-hidden-accessible {
            width: 100% !important;
            height: 50px !important;
            position: relative !important;
            visibility: hidden;
        }

        #product_category_block .select2-container {
            width: 100% !important;
        }
        .swal-button--danger {
            background-color: #df4740 !important;
        }
        /* .iti--separate-dial-code .iti__selected-flag{font-size: 12px;  height: 38px;}
        @media (min-width: 1400px){
            .iti--separate-dial-code .iti__selected-flag{  height: 48px;}
        }
        @media (max-width: 991px){
            .iti--separate-dial-code .iti__selected-flag{  height: 27px;font-size: 10px;}
        } */
    </style>
@endsection

@section('content')
        <div class="container Loanapplication py-3">
            <div class="row floatlables">
                <div class="header_top koin d-flex align-items-center pb-5 px-3 pt-xl-2 pt-0">
                    <h1 class="mb-0">{{ __('profile.reupload_documents') }}</h1>
                </div>
                <div class="col-md-5">
                    <ul>
                        <li>{{ __('profile.please_reupload_documents') }}</li>
                    </ul>
                </div>
                <div class="col-md-7 formpagesloan">
                    <form id="reuploadDocForm" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{request()->route('id')}}">
                    <div class="card mb-lg-5 shadow">
                        <div class="card-header bg-white p-3">
                            <div class="col-md-auto ">
                                <div class="f-15 fw-bold">{{ __('profile.documents') }}</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4 py-2">
                                <div class="col-md-6">
                                    <label for="" class="form-label">{{ __('profile.ktp_image.title') }}<span
                                            class="text-danger">*</span></label>
                                    <div class="d-flex form-control">
                                                    <span class="">
                                                        <input type="file" name="ktpImage" class="form-control" id="ktpImage" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                        <label id="upload_btn" for="ktpImage">{{ __('profile.browse') }}</label>
                                                    </span>
                                        <div id="file-ktpImage" class="d-flex align-items-center">
                                            <input type="hidden" class="form-control" id="old_attachment_file" name="old_attachment_file" value="">
                                        </div>
                                    </div>
                                    <span id="ktpImageError" class="js-validation text-danger"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">{{ __('profile.ktp_with_selfie_image.title') }}<span
                                            class="text-danger">*</span></label>
                                    <div class="d-flex form-control">
                                                    <span class="">
                                                        <input type="file" name="ktpSelfiImage" class="form-control" id="ktpSelfiImage" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                        <label id="upload_btn" for="ktpSelfiImage">{{ __('profile.browse') }}</label>
                                                    </span>
                                        <div id="file-ktpSelfiImage" class="d-flex align-items-center">
                                            <input type="hidden" class="form-control" id="old_attachment_file" name="old_attachment_file" value="">
                                        </div>
                                    </div>
                                    <span id="ktpSelfiImageError" class="js-validation text-danger"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">{{ __('profile.npwp_file') }}<span
                                            class="text-danger">*</span></label>
                                    <div class="d-flex form-control">
                                                    <span class="">
                                                        <input type="file" name="loanApplicantBusinessNpwpImage" class="form-control" id="loanApplicantBusinessNpwpImage" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                        <label id="upload_btn" for="loanApplicantBusinessNpwpImage">{{ __('profile.browse') }}</label>
                                                    </span>
                                        <div id="file-loanApplicantBusinessNpwpImage" class="d-flex align-items-center">
                                            <input type="hidden" class="form-control" id="old_attachment_file" name="old_attachment_file" value="">
                                        </div>
                                    </div>
                                    <span id="loanApplicantBusinessNpwpImageError" class="js-validation text-danger"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">{{ __('profile.bank_statement_image.title') }}<span
                                            class="text-danger">*</span></label>
                                    <div class="d-flex form-control">
                                                    <span class="">
                                                        <input type="file" name="loanApplicantBankStatement" class="form-control" id="loanApplicantBankStatement" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                        <label id="upload_btn" for="loanApplicantBankStatement">{{ __('profile.browse') }}</label>
                                                    </span>
                                        <div id="file-loanApplicantBankStatement" class="d-flex align-items-center">
                                            <input type="hidden" class="form-control" id="old_attachment_file" name="old_attachment_file" value="">
                                        </div>
                                    </div>
                                    <span id="loanApplicantBankStatementError" class="js-validation text-danger"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">{{ __('profile.family_card_image.title') }}<span
                                            class="text-danger">*</span></label>
                                    <div class="d-flex form-control">
                                                    <span class="">
                                                        <input type="file" name="familyCardImage" class="form-control" id="familyCardImage" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                        <label id="upload_btn" for="familyCardImage">{{ __('profile.browse') }}</label>
                                                    </span>
                                        <div id="file-familyCardImage" class="d-flex align-items-center">
                                            <input type="hidden" class="form-control" id="old_attachment_file" name="old_attachment_file" value="">
                                        </div>
                                    </div>
                                    <span id="familyCardImageError" class="js-validation text-danger"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">{{ __('profile.other_ktp_image.title') }}<span
                                            class="text-danger">*</span></label>
                                    <div class="d-flex form-control">
                                                    <span class="">
                                                        <input type="file" name="otherKtpImage" class="form-control" id="otherKtpImage" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                        <label id="upload_btn" for="otherKtpImage">{{ __('profile.browse') }}</label>
                                                    </span>
                                        <div id="file-otherKtpImage" class="d-flex align-items-center">
                                            <input type="hidden" class="form-control" id="old_attachment_file" name="old_attachment_file" value="">
                                        </div>
                                    </div>
                                    <span id="otherKtpImageError" class="js-validation text-danger"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">{{ __('profile.license_image.title') }}<span
                                            class="text-danger">*</span></label>
                                    <div class="d-flex form-control">
                                                    <span class="">
                                                        <input type="file" name="loanApplicantBusinessLicenceImage" class="form-control" id="loanApplicantBusinessLicenceImage" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                        <label id="upload_btn" for="loanApplicantBusinessLicenceImage">{{ __('profile.browse') }}</label>
                                                    </span>
                                        <div id="file-loanApplicantBusinessLicenceImage" class="d-flex align-items-center">
                                            <input type="hidden" class="form-control" id="old_attachment_file" name="old_attachment_file" value="">
                                        </div>
                                    </div>
                                    <span id="loanApplicantBusinessLicenceImageError" class="js-validation text-danger"></span>
                                </div>
                            </div>
                            <div class="pagefirstbtn d-flex align-items-center justify-content-center mt-2">
                                <button class="btn btn-primary w-100" href="javascript:void(0)" role="button" id="reupload_doc_submit">
                                    <img class="ms-2" src="{{ URL::asset('front-assets/images/icons/pageend_submit.png') }}" alt="arrow" srcset="">
                                    {{ __('admin.submit') }} </button>
                            </div>

                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
@endsection

@section('script')
    <!--begin: plugin js for this page -->
    <script type="text/javascript" src="{{ URL::asset('front-assets/js/front/crop/scripts/jquery.Jcrop.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('front-assets/js/front/crop/scripts/jquery.SimpleCropper.js') }}"></script>
    <script type="text/javascript" src='{{ asset("front-assets/js/front/croppie.js")}}'></script>
    <script type="text/javascript" src="{{ URL::asset('front-assets/js/datatables/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('front-assets/js/datatables-bs4/dataTables.bootstrap4.js') }}"></script>
    <!--end: plugin js for this page -->
@endsection

@section('custom-script')
<script>

    function showFile(input) {

        let file = input.files[0];
        console.log(file);
        let size = Math.round((file.size / 1024));
        if(size > 5200){
            swal({
                icon: 'error',
                title: '',
                text: '{{ __('profile.file_size_under_5mb') }}',
            })
        } else {
            let fileName = file.name;
            let allowed_extensions = new Array("jpg","jpeg");
            let file_extension = fileName.split('.').pop();
            let file_name_without_extension = fileName.replace(/\.[^/.]+$/, '');
            let image_path = '{{URL::asset("assets/icons/times-circle copy.png")}}';
            let text = '{{ __('profile.plz_upload_image_file') }}';

            for (let i = 0; i < allowed_extensions.length; i++) {
                if (allowed_extensions[i] == file_extension) {
                    valid = true;
                    let download_function = "'" + input.name + "', " + "'" + fileName + "'";
                    if(file_name_without_extension.length >= 10) {
                        fileName = file_name_without_extension.substring(0,10) +'....'+file_extension;
                    }
                    $('#file-' + input.name).html('');
                    $('#file-' + input.name).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.name + 'Download " style="text-decoration: none">' + fileName + '</a></span><span class="ms-2"><a class="' + input.name + ' downloadbtn hidden" href="javascript:void(0);" title="{{ __('profile.download_file') }}" onclick="downloadimg(' + download_function + ')" style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a></span>');
                    return;
                }
            }
            valid = false;
            swal({
                text: text,
                icon: "/assets/images/warn.png",
                title: '',
            })
        }
    }

    $('#reupload_doc_submit').click(function () {
        let that = $(this);
        that.prop('disabled', true);
        let formData = new FormData($("#reuploadDocForm")[0]);

        $.ajax({
            url: "{{ route('settings.limit-reupload-document-ajax') }}",
            type: "POST",
            data: formData,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            contentType: false,
            processData: false,
            success: function(data) {
                if (data.success) {
                    new PNotify({
                        text: data.message,
                        type: 'success',
                        styling: 'bootstrap3',
                        animateSpeed: 'fast',
                        delay: 2000
                    });
                    setTimeout(function () {
                        let targetUrl = "{{ route('settings.credit-limit-status','') }}/" + data.data;
                        location.href = targetUrl;
                    },2000);
                    return;
                }
                new PNotify({
                    text: data.message,
                    type: 'error',
                    styling: 'bootstrap3',
                    animateSpeed: 'fast',
                    delay: 2000
                });
                that.prop('disabled', false);
                $('.js-validation').html('')
            },
            error: function(data) {
                that.prop('disabled', false);
                if( data.status === 422 ) {
                    var errors = $.parseJSON(data.responseText);
                    $.each(errors, function (key, value) {

                        if($.isPlainObject(value)) {
                            $.each(value, function (key, value) {
                                $('#'+key+'Error').html(value);
                            });
                        }
                    });
                }
            }
        });
    });

    $('#reuploadDocForm input[type="file"]').on('change', function (evt) {
        let inputId = $(this).attr('id');
        $('#'+inputId+'Error').html('');
    });

</script>
@endsection
