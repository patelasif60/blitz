<div>
    <form id="basicDetailsFrm"  action="{{ $updateMode==true ? route('supplier.basic.details.update',Crypt::encrypt($supplier->id)) : route('supplier.basic.details.update',isset($supplier->id) ? Crypt::encrypt($supplier->id) : '') }}" method="POST">
        @csrf
        <input type="hidden" name="formType" value="basicDetails">
        <input type="hidden" name="user_id" id="user_id"  value="{{ isset($supplier->id) ? Crypt::encrypt($supplier->id) : '' }}">
        <input type="hidden" name="inputErrors" class="inputErrors" value="">
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center">
                <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/icon_company.png')}}" alt="Company" class="pe-2">
                    <span>{{ __('admin.company_basic_detail') }}</span>
                </h5>
            </div>
            <div class="card-body p-3 pb-1">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="business_description" class="form-label">{{ __('admin.description_of_the_business') }}</label>
                        <textarea name="business_description" class="form-control" placeholder="{{ __('admin.description_of_the_business_placeholder') }}" id="business_description" cols="30" rows="3" aria-hidden="true">{{$companyDetails->business_description ?? ''}}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="mission" class="form-label">{{ __('admin.mission') }}</label>
                        <textarea name="mission" class="form-control" id="mission" placeholder="{{ __('admin.mission_placeholder') }}" cols="30" rows="3" aria-hidden="true">{{$companyDetails->mission ?? ''}}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="vision" class="form-label">{{ __('admin.vision') }}</label>
                        <textarea name="vision" class="form-control" id="vision" cols="30" placeholder="{{ __('admin.vision_placeholder') }}" rows="3" aria-hidden="true">{{$companyDetails->vision ?? ''}}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="history_growth" class="form-label">{{ __('admin.history_expansion_growth') }} </label>
                        <textarea name="history_growth" class="form-control" id="history_growth" cols="30" placeholder="{{ __('admin.history_expansion_growth') }}" rows="3" aria-hidden="true">{{$companyDetails->history_growth ?? ''}}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="industry_information" class="form-label">{{ __('admin.industry_information') }}</label>
                        <textarea name="industry_information" class="form-control" id="industry_information" placeholder="{{ __('admin.industry_information_placeholder') }}" cols="30" rows="3" aria-hidden="true">{{$companyDetails->industry_information ?? ''}}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="public_relations" class="form-label">{{ __('admin.public_relations') }}</label>
                        <textarea name="public_relations" class="form-control" placeholder="{{ __('admin.public_relations_Placehoder') }}" id="public_relations" cols="30" rows="3" aria-hidden="true">{{$companyDetails->public_relations ?? ''}}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="advertising" class="form-label">{{ __('admin.ahep') }}</label>
                        <textarea name="policies" class="form-control" placeholder="{{ __('admin.ahep_paceholder') }}" id="policies" cols="30" rows="3" aria-hidden="true">{{$companyDetails->policies ?? ''}}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="policies" class="form-label">{{ __('admin.upload_ahep') }}</label>
                        <div class="d-flex">
                            <span class="">
                                <input type="file" name="policiesImage" class="form-control" id="policiesImage" accept=".jpg,.png,.gif,.jpeg,.pdf" hidden/>
                                <label  class="upload_btn" for="policiesImage">{{ __('admin.Upload_doc')}} </label>
                            </span>
                            <div id="file-policiesImage">
                                @if (isset($companyDetails) && !empty($companyDetails->policies_image))
                                @php
                                    $extension_policies = Str::substr($companyDetails->policies_image, -4);
                                    $policies_filename = substr(Str::substr($companyDetails->policies_image, stripos($companyDetails->policies_image, 'policies_') + 9), 0, -4);
                                    if (strlen($policies_filename) > 10) {
                                        $policies_name = substr($policies_filename, 0, 10) . '...' . $extension_policies;
                                    } else {
                                        $policies_name = $policies_filename . $extension_policies;
                                    }
                                @endphp
                                <input type="hidden" class="form-control" id="oldpolicies" name="oldpolicies" value="{{ $companyDetails->policies_image }}">
                                <span class="ms-2"><a href="javascript:void(0);" id="catalogFileDownload" onclick="downloadimg('{{ $supplier->id }}', 'policies', '{{ Str::substr($companyDetails->policies_image, stripos($companyDetails->policies_image, 'policies_') + 9) }}')" title="{{ Str::substr($companyDetails->policies_image, stripos($companyDetails->policies_image, 'policies_') + 9) }}" style="text-decoration: none;">{{ $policies_name }}</a></span>
                                <span class="removeFile" id="policiesFile" data-id="{{ $companyDetails->id }}" file-path="{{ $companyDetails->policies_image }}" data-name="policiesImage"><a href="#" title="Remove policies"><img src="{{ URL::asset('assets/icons/times-circle copy.png') }}" alt="CLose button" class="ms-2"></a></span>
                                <span class="ms-2"><a class="policiesFile" href="javascript:void(0);" title="Download policies" onclick="downloadimg('{{ $companyDetails->id }}', 'policies_image', '{{ Str::substr($companyDetails->policies_image, stripos($companyDetails->policies_image, 'policies_') + 9) }}')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="advertising" class="form-label">{{ __('admin.advertising') }}</label>
                        <textarea name="advertising" class="form-control" placeholder="{{ __('admin.advertising_placehoder') }}" id="advertising" cols="30" rows="3" aria-hidden="true">{{$companyDetails->advertising ?? ''}}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-end">

            <button class="btn btn-primary" type="submit" id="btnBasicDetailSubmit">{{ __('admin.save')}}</button>
        </div>
    </form>
    <script type="text/javascript">
        $('#basicDetailsFrm').on('submit',function(e){
            $('#basicDetailsFrm .inputErrors').val('');
            e.preventDefault();
            var formData = new FormData(this);
            var url = $('#basicDetailsFrm').attr('action');
            var method = $('#basicDetailsFrm').attr('method');
            var supplierId = $('#basicDetailsFrm #user_id').val();
            $.ajax({
                url: url,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: formData,
                type: method,
                contentType: false,
                processData: false,
                async:false,
                success: function(successData) {
                    if(successData.success) {
                        resetToastPosition();
                        SnippetApp.toast.success("{{ __('admin.success') }}",successData.message);
                        var updateAction = "{{ route('supplier.basic.details.update', [':id']) }}";
                        updateAction = updateAction.replace(":id", supplierId);

                        $('#basicDetailsFrm').attr('action',updateAction);
                    } else {
                        resetToastPosition();
                        SnippetApp.toast.alert("{{ __('admin.warning') }}",successData.message);
                    }
                },
                error:function(e){
                    if (e.status == 422){
                        var errorsArr = [];
                        $('#basicDetailsFrm .errorValidate').html('');
                        $.each(e.responseJSON.errors, function(key,value) {
                            $('#'+key+'Error').html('');
                            $('#'+key+'Error').append('<small class="text-danger">'+value+'</small>');
                            errorsArr.push(key);
                        });
                        $('#basicDetailsFrm .inputErrors').val(errorsArr);
                    } else {
                        resetToastPosition();
                        SnippetApp.toast.alert("{{ __('admin.warning') }}","{{ __('admin.something_went_wrong') }}");
                    }
                }
            });
        });
        $(document).on('change','#policiesImage',function () {
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
                var allowed_extensions = new Array("jpg", "png", "gif", "jpeg", "pdf");
                var file_extension = fileName.split('.').pop();
                var text = '';
                var supplier_id = '1';
                var image_path = '{{URL::asset("assets/icons/times-circle copy.png")}}';
                text = '{{ __('admin.upload_image_or_pdf') }}';
                for (var i = 0; i < allowed_extensions.length; i++) {
                    if (allowed_extensions[i] == file_extension) {
                        valid = true;
                        var download_function = "'" + supplier_id + "', " + "'" + input.name + "', " + "'" + fileName + "'";
                        $('#file-' + input.name).html('<span class="ms-2"><a href="javascript:void(0);" id="' + input.name + 'Download" style="text-decoration: none">' + fileName + '</a></span>');
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
    </script>
</div>
