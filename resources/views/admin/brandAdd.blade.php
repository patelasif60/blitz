@extends('admin/adminLayout')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <div class="row">
            <div class="col-md-12 d-flex align-items-center mb-3">
                <h1 class="mb-0 h3">{{ __('admin.brands')}}</h1>
                <a href="{{ route('brands-list') }}" class="mb-2 backurl ms-auto btn-close"></a>
            </div>
            <div class="col-12">
                <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">{{ __('admin.add_brand')}}</button>
                    </li>
                </ul>
                <div class="tab-content pt-3 pb-0" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <form class="" method="POST" id="brandadd" enctype="multipart/form-data" action="{{ route('brand-create') }}" data-parsley-validate>
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <div class="card">
                                    <div class="card-header d-flex align-items-center">
                                            <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/icon_brand.png')}}" alt="Brand" class="pe-2"> <span>{{ __('admin.brand')}}</span></h5>
                                        </div>
                                        <div class="card-body p-3 pb-1">

                                            <!-- <div class="mb-2">
                                <a href="{{ route('brands-list') }}" class="mb-2 backurl" style="float:right;"><i class="fa fa-times"
                                    aria-hidden="true"></i></a>
                                </div>
                                 <h3 class="card-title">Add Brand</h3> -->
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="name" class="form-label">{{ __('admin.brand')}}<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="name" name="name" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="status" class="form-label">{{ __('admin.status')}}</label>
                                                    <div>
                                                        <input type="radio" value="1" name="status" checked> {{ __('admin.active')}}
                                                        <input type="radio" value="0" name="status"> {{ __('admin.deactive')}}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label for="description" class="form-label">{{ __('admin.description')}}</label>
                                                    <textarea class="form-control newtextarea" id="description" name="description"></textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">{{ __('admin.add')}}</button>
                                    <a href="{{ route('brands-list') }}" class="btn btn-cancel ms-3">
                                        {{ __('admin.cancel')}}
                                    </a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>



    </div>
</div>
@stop

@section('scripts')
<script>
    $("#brandadd").on('submit', function(event) {
        event.preventDefault();
        var formData = new FormData($("#brandadd")[0]);
        formData.append('description', tinyMCE.get('description').getContent());
        if ($('#brandadd').parsley().validate()) {
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
                            text: "{{__('admin.brands_added_alert')}}",
                            showHideTransition: "slide",
                            icon: "success",
                            loaderBg: "#f96868",
                            position: "top-right",
                        });
                        setTimeout(function() {
                            window.top.location = $(".backurl").attr('href')
                        }, 3000);
                    } else {
                        resetToastPosition();
                        $.toast({
                            heading: "{{__('admin.success')}}",
                            text: "{{__('admin.brand_exist')}}",
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
