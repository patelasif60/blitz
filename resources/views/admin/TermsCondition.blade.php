@extends('admin/adminLayout')
@section('content')
<div class="row">
    <div class="col-md-12 d-flex align-items-center  mb-3">
        <h1 class="mb-0 h3">{{__('admin.terms_condition')}}</h1>
        <a href="{{route('admin-dashboard')}}" class="mb-2 backurl ms-auto btn-close"></a>
    </div>

    <div class="col-12 mb-2">
        <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                    {{__('admin.update_tc')}}
                </button>
            </li>
        </ul>
        @if (Session::has('status'))
            <p class="alert suplier-succ"> {{ Session::get('status') }}</p>
        @endif
        <div class="tab-content pt-3 pb-0" id="myTabContent">

            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <form class="" method="POST" enctype="multipart/form-data" action="{{ route('tcdoc-update') }}" data-parsley-validate name="defaultTermsConditionform" id="defaultTermsConditionform">
                    @csrf
                    <input type="hidden" name="id" value="{{ isset($default_tcdoc) ? $default_tcdoc->id : ''; }}">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0">
                                        <img src="{{ URL::asset("assets/icons/boxes.png") }}" alt="Blitznet T&C" class="pe-2">
                                        <span>{{__('admin.details')}}</span>
                                    </h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <div class="row error_res">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{__('admin.rfq')}} {{__('admin.commercial_tc')}} <small>({{__('admin.from')}} {{__('admin.buyer')}})</small></label>
                                            <div class="d-flex">
                                                <span class="">
                                                    <input type="file" name="buyer_default_tcdoc"  class="form-control" id="buyer_default_tcdoc" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                    <label id="upload_btn" for="buyer_default_tcdoc">{{ __('profile.browse') }}</label>
                                                </span>

                                                <div id="file-buyer_default_tcdoc" class="d-flex align-items-center" data-oldbuyertcdoc="{{ isset($default_tcdoc->buyer_default_tcdoc) && !empty($default_tcdoc->buyer_default_tcdoc)?1:0 }}">
                                                    @if(isset($default_tcdoc->buyer_default_tcdoc) && !empty($default_tcdoc->buyer_default_tcdoc))
                                                        @php
                                                            $buyerDefaultFileTitle = Str::substr($default_tcdoc->buyer_default_tcdoc,33);
                                                            $extension_buyer_default_tcdoc = getFileExtension($buyerDefaultFileTitle);
                                                            $buyer_default_tcdoc_filename = getFileName($buyerDefaultFileTitle);
                                                            if(strlen($buyer_default_tcdoc_filename) > 10){
                                                                $buyer_default_tcdoc_name = substr($buyer_default_tcdoc_filename,0,13).$extension_buyer_default_tcdoc;
                                                            } else {
                                                                $buyer_default_tcdoc_name = $buyer_default_tcdoc_filename.$extension_buyer_default_tcdoc;
                                                            }
                                                        @endphp

                                                        <input type="hidden" class="form-control" id="oldbuyer_default_tcdoc" name="oldbuyer_default_tcdoc" value="{{ $default_tcdoc->buyer_default_tcdoc }}">
                                                        <span class="ms-2">
                                                            <a href="{{Storage ::url($default_tcdoc->buyer_default_tcdoc)}}" id="buyerDefaultFileDownload" download title="{{ $buyerDefaultFileTitle }}" style="text-decoration: none;"> {{ $buyer_default_tcdoc_name }}</a>
                                                        </span>
                                                        <span class="removeFile" id="buyer_default_tcdoc" data-id="{{ $default_tcdoc->id }}" file-path="{{ $default_tcdoc->buyer_default_tcdoc }}" data-name="buyer_default_tcdoc">
                                                            <a href="javascript:void(0);" title="{{ __('profile.remove_file') }}"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a>
                                                        </span>
                                                        <span class="ms-2">
                                                            <a class="buyerDefault_file" href="{{Storage ::url($default_tcdoc->buyer_default_tcdoc)}}" title="{{ __('profile.download_file') }}" download  style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a>
                                                        </span>
                                                    @endif
                                                </div>

                                            </div>
                                            <span id="rfq_validate"></span>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{__('admin.quote')}} {{__('admin.commercial_tc')}} <small>({{__('admin.from')}} {{__('admin.supplier')}})</small></label>
                                            <div class="d-flex">
                                                <span class="">
                                                    <input type="file" name="supplier_default_tcdoc" class="form-control" id="supplier_default_tcdoc" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                    <label id="upload_btn" for="supplier_default_tcdoc">{{ __('profile.browse') }}</label>
                                                </span>
                                                <div id="file-supplier_default_tcdoc" class="d-flex align-items-center" data-oldsuppliertcdoc ='{{ isset($default_tcdoc->supplier_default_tcdoc) && !empty($default_tcdoc->supplier_default_tcdoc)? 1 : 0 }}'>
                                                    @if(isset($default_tcdoc->supplier_default_tcdoc) && !empty($default_tcdoc->supplier_default_tcdoc))
                                                        @php
                                                            $supplierDefaultFileTitle = Str::substr($default_tcdoc->supplier_default_tcdoc,33);
                                                            $extension_supplier_default_tcdoc = getFileExtension($supplierDefaultFileTitle);
                                                            $supplier_default_tcdoc_filename = getFileName($supplierDefaultFileTitle);
                                                            if(strlen($supplier_default_tcdoc_filename) > 10){
                                                                $supplier_default_tcdoc_name = substr($supplier_default_tcdoc_filename,0,13).$extension_supplier_default_tcdoc;
                                                            } else {
                                                                $supplier_default_tcdoc_name = $supplier_default_tcdoc_filename.$extension_supplier_default_tcdoc;
                                                            }
                                                        @endphp

                                                        <input type="hidden" class="form-control" id="oldsupplier_default_tcdoc" name="oldsupplier_default_tcdoc" value="{{ $default_tcdoc->supplier_default_tcdoc }}" >
                                                        <span class="ms-2">
                                                            <a href="{{Storage ::url($default_tcdoc->supplier_default_tcdoc)}}" id="supplierDefaultFileDownload" download title="{{ $supplierDefaultFileTitle }}" style="text-decoration: none;"> {{ $supplier_default_tcdoc_name }}</a>
                                                        </span>
                                                        <span class="removeFile" id="supplier_default_tcdoc" data-id="{{ $default_tcdoc->id }}" file-path="{{ $default_tcdoc->supplier_default_tcdoc }}" data-name="supplier_default_tcdoc">
                                                            <a href="javascript:void(0);" title="{{ __('profile.remove_file') }}"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a>
                                                        </span>
                                                        <span class="ms-2">
                                                            <a class="supplierDefault_file" href="{{Storage ::url($default_tcdoc->supplier_default_tcdoc)}}" download title="{{ __('profile.download_file') }}"  style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                             <span id="quote_validate"></span>
                                        </div>


                                    </div>


                                    <!-- <div class="col-12 mb-3">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div> -->
                                </div>

                            </div>

                        </div>

                        <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" id="submitTermsConditionform">{{__('admin.update')}}</button>
                            <a href="{{route('admin-dashboard')}}" class="btn btn-cancel ms-3">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $("#defaultTermsConditionform").on('submit',function(event){
                event.preventDefault();
                var oldbuyertcdoc = $('#file-buyer_default_tcdoc').data('oldbuyertcdoc');
                var oldsuppliertcdoc = $('#file-supplier_default_tcdoc').data('oldsuppliertcdoc');
                var formData = new FormData($("#defaultTermsConditionform")[0]);

                if(oldbuyertcdoc == 0 && oldsuppliertcdoc == 0){
                    if($('#buyer_default_tcdoc')[0].files[0] == undefined && $('#supplier_default_tcdoc')[0].files[0] == undefined)
                    {
                        $('#buyer_default_tcdoc').prop('required','true');
                        $('#buyer_default_tcdoc').attr('data-parsley-errors-container','#rfq_validate');
                        $('#supplier_default_tcdoc').prop('required','true');
                        $('#supplier_default_tcdoc').attr('data-parsley-errors-container','#quote_validate');
                    }else{
                        /*$('#buyer_default_tcdoc').attr('required','false');
                        $('#supplier_default_tcdoc').attr('required','false');*/
                        $('#rfq_validate').html('');
                        $('#quote_validate').html('');
                        saveDataToDatabase(formData, $(this).attr('action'), $(this).attr('method'));

                    }
                }else{
                    $('#rfq_validate').html('');
                    $('#quote_validate').html('');
                    saveDataToDatabase(formData, $(this).attr('action'), $(this).attr('method'));
                }
            });
        function saveDataToDatabase(formData, action, type){
                $.ajax({
                    url: action,
                    type: type,
                    data : formData,
                    contentType: false,
                    processData: false,
                    success: function (r) {
                        if(r.success == true){
                            resetToastPosition();
                            $.toast({
                                heading: "{{__('admin.success')}}",
                                text: "{{__('admin.tcdoc_updated_successfully')}}",
                                showHideTransition: "slide",
                                icon: "success",
                                loaderBg: "#f96868",
                                position: "top-right",
                            });
                            window.setTimeout(function(){location.reload()},3000);
                        }

                    },
                    error: function (xhr) {
                        alert('{{__('admin.error_while_updating_document')}}');
                    }
                });
            }

        //Attachment Document
            function showFile(input) {
                let file = input.files[0];
                let size = Math.round((file.size / 1024));
                if(size > 3000){
                    swal({
                        icon: 'error',
                        title: '',
                        text: '{{ __('profile.file_size_under_3mb') }}',
                    })
                } else {
                    let fileName = file.name;
                    let allowed_extensions = new Array("jpg", "png", "jpeg", "pdf");
                    let file_extension = fileName.split('.').pop();
                    let file_name_without_extension = fileName.replace(/\.[^/.]+$/, '');
                    let text = '{{ __('profile.plz_upload_file') }}';
                    let image_path = '{{URL::asset("assets/icons/times-circle copy.png")}}';

                    for (let i = 0; i < allowed_extensions.length; i++) {
                        if (allowed_extensions[i] == file_extension) {
                            valid = true;
                            let download_function = "'" + input.name + "', " + input.name + + "'" + fileName + "'";
                            if(file_name_without_extension.length >= 10) {
                                fileName = file_name_without_extension.substring(0,10) +'....'+file_extension;
                            }
                            $('#file-' + input.name).html('');
                            $('#file-' + input.name).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.name + 'Download " style="text-decoration: none">' + fileName + '</a></span>');
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

            //Delete RFQ attachment file
            $(document).on('click', '.removeFile', function (e) {
                e.preventDefault();
                var element = $(this);
                var id = $(this).attr("data-id");
                var fileName = $(this).attr("id");
                var dataName = $(this).attr("data-name");
                var data = {
                    fileName: fileName,
                    filePath: $(this).attr("file-path"),
                    id: $(this).attr("data-id"),
                    _token: "{{ csrf_token() }}"
                };
                swal({
                    title: "{{ __('admin.delete_sure_alert') }}",
                    icon: "warning",
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                    dangerMode: false,
                }).then((changeit) => {
                    if (changeit) {
                        $.ajax({
                            url: "{{ route('tc-document-delete-ajax') }}",
                            data: data,
                            type: "POST",
                            success: function (successData) {
                                $("#file-"+ dataName).html('');

                                    $.toast({
                                        heading: '{{ __('admin.success') }}',
                                        text: '{{ __('admin.tcdoc_removed_successfully') }}',
                                        showHideTransition: 'slide',
                                        icon: 'success',
                                        loaderBg: '#f96868',
                                        position: 'top-right'
                                    })
                                window.setTimeout(function(){location.reload()},3000);
                            },
                            error: function () {
                                console.log("error");
                                 $.toast({
                                     heading: '{{ __('admin.success') }}',
                                     text: '{{ __('admin.error_while_removing_document') }}',
                                     showHideTransition: 'slide',
                                     icon: 'success',
                                     loaderBg: '#f96868',
                                     position: 'top-right'
                                 })

                            },
                        });
                    }
                });
            });
    </script>
@endsection
