@extends('admin/adminLayout')

@section('content')
<div class="row">
    <div class="col-12 grid-margin ">
        <div class="row">
            <div class="col-md-12 d-flex align-items-center mb-3">
                <h1 class="mb-0 h3">{{ __('admin.department')}}</h1>
                <a href="{{ route('department-list') }}" class="mb-2 backurl ms-auto btn-close"></a>
            </div>

            <div class="col-12">
                <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">{{__('admin.edit_department')}}
                        </button>
                    </li>

                </ul>
                <div class="tab-content pt-3 pb-1" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <form id="deptedit" class="" method="POST" enctype="multipart/form-data" action="{{ route('department-update') }}" data-parsley-validate>
                            @csrf
                            <input type="hidden" name="id" value="{{ $department->id }}">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center">
                                            <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/icon_designation.png')}}" alt="Designation Details" class="pe-2"> <span>{{__('admin.department_detail')}}</span></h5>
                                        </div>
                                        <div class="card-body p-3 pb-1">
                                            <!-- <div class="mb-3">
                                <a href="{{ route('designation-list') }}" class="mb-2 backurl" style="float:right;"><i class="fa fa-times" aria-hidden="true"></i></a>
                            </div>
                            <h3 class="card-title">Edit Designation</h3> -->
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="name" class="form-label">{{ __('admin.department')}}<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="name" name="name" required value="{{ $department->name }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="status" class="form-label">{{ __('admin.status')}}</label>
                                                    <div>
                                                        <input type="radio" value="1" name="status" {{ $department->status == 1 ? 'checked' : '' }}>
                                                        {{ __('admin.active')}}
                                                        <input type="radio" value="0" name="status" {{ $department->status == 0 ? 'checked' : '' }}>
                                                        {{ __('admin.deactive')}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">{{ __('admin.update')}}</button>
                                <a href="{{ route('department-list') }}" class="btn btn-cancel ms-3">{{ __('admin.cancel')}}</a>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



@stop

@section('scripts')
<script>
    $("#deptedit").on('submit', function(event) {
        event.preventDefault();
        var formData = new FormData($("#deptedit")[0]);
        if ($('#deptedit').parsley().validate()) {
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: formData,
                contentType: false,
                processData: false,

                success: function(r) {
                    if (r.success == true) {
                        resetToastPosition();
                        $.toast({
                            heading: "{{__('admin.success')}}",
                            text: "{{ __('admin.department_updated_alert')}}",
                            showHideTransition: "slide",
                            icon: "success",
                            loaderBg: "#f96868",
                            position: "top-right",
                        });
                        setTimeout(function() {
                            window.top.location = $(".backurl").attr('href')
                        }, 3000);
                    }
                },
                error: function(xhr) {
                    alert('{{__('admin.error_while_selecting_list')}}');
                }
            });

        }
    });
</script>
@stop
