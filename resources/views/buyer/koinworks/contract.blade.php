@extends('buyer/layouts/backend/backend_single_layout')

@section('css')
    <link href="{{ URL::asset('front-assets/js/front/crop/css/style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/js/front/crop/css/style-example.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/js/front/crop/css/jquery.Jcrop.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/croppie.min.css') }}" rel='stylesheet' />
    <link href="{{ URL::asset('front-assets/js/datatables-bs4/dataTables.bootstrap4.css') }}" rel='stylesheet' />

@endsection

@section('content')
    <div class="main_section position-relative profile_section">
        <div class="container py-3 Loanapplication">
            <div class="row floatlables">
                <div class="header_top koin d-flex align-items-center pb-5 px-3">
                    <h1 class="mb-0">{{__('profile.limit_contract')}}</h1>
                </div>
                <div class="d-flex justify-content-center mt-lg-3">
                    <div class="text-center">
                        <form class="h-100 " id="contractUploadDocForm" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            @csrf
                            <p class="my-lg-4 my-3 fw-bold" style=" font-size: 14px;">{{__('profile.limit_contract_line')}}</p>
                            <a type="button" href="{{$contracts}}"  target="_blank" class="btn btn-info"><img class ="me-1" src="{{ URL::asset('front-assets/images/icons/downarrowkoinworks.png') }}" alt="" srcset="">{{__('profile.download')}}</a>
                            <p class="mt-lg-5 mt-3 mb-3 fw-bold" style=" font-size: 14px;">{{__('profile.upload_limit_contract')}}</p>
                            <div class="col-md-12">
                                <label for="" class="form-label">{{__('profile.upload')}}<span class="text-danger">*</span></label>
                                <div class="d-flex form-control">
                                    <span class="">
                                        <input type="file" name="uploadContract" class="form-control" id="uploadContract" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                        <label id="upload_btn" for="uploadContract">{{__('admin.browse')}}</label>
                                    </span>
                                    <div id="file-uploadContract" class="d-flex align-items-center">
                                        <input type="hidden" class="form-control" id="old_attachment_file" name="old_attachment_file" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-lg-5 my-3">
                                <button name="" id="contractFileUploade" class="btn btn-primary bluecolornext text-white px-3" role="button"><img class="me-1" src="{{ URL::asset('front-assets/images/icons/pageend_submit.png') }}" alt="arrow" srcset="">{{__('admin.save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        function showFile(input) {

            let file = input.files[0];
            let size = Math.round((file.size / 1024));
            if(size > 5200){
                swal({
                    icon: 'error',
                    title: '',
                    text: '{{ __('profile.file_size_under_5mb') }}',
                })
            } else {
                let fileName = file.name;
                let allowed_extensions = new Array("jpg", "png", "jpeg", "pdf");
                let file_extension = fileName.split('.').pop();
                let file_name_without_extension = fileName.replace(/\.[^/.]+$/, '');
                let image_path = '{{URL::asset("assets/icons/times-circle copy.png")}}';
                let text = '{{ __('profile.plz_upload_file') }}';

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
                    icon: "warning",
                    buttons: ["{{__('admin.no')}}", "{{__('admin.yes')}}"],
                })
            }
        }

        $('#contractFileUploade').click(function (e) {
            e.preventDefault();
            let id = '{{ Request::segment(3) }}';
            let formData = new FormData($("#contractUploadDocForm")[0]);
            formData.append('applicationId', id);
            $.ajax({
                url: "{{ route('settings.limit-contract-upload-ajax') }}",
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
                            let targetUrl = "{{ route('settings.credit-limit-status','') }}/"+id;
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
                    }
                }
            });
        });
    </script>
@stop
