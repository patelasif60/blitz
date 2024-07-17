@extends('admin/adminLayout')

@section('content')

<div class="row">
    <div class="col-12 ">
        <div class="row">
            <div class="col-md-12 d-flex align-items-center mb-3">
                <h1 class="mb-0 h3"> {{ __('admin.sub_categories')}}</h1>
                <a href="{{ route('sub-categories-list') }}" class="mb-2 backurl ms-auto btn-close"></a>
            </div>

            <div class="col-12 ">
                <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">{{ __('admin.add_sub_category')}}
                        </button>
                    </li>
                </ul>
                <div class="tab-content pt-3 pb-0" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <form method="POST" enctype="multipart/form-data" action="{{ route('sub-category-create') }}" data-parsley-validate id="subcatadd">
                            @csrf

                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <div class="card">
                                        <div class="card-body p-3 pb-1">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="name" class="form-label">{{ __('admin.sub_category')}}<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="name" name="name" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="status" class="form-label">{{ __('admin.category')}}<span class="text-danger">*</span></label>
                                                    <select name="category" id="category" class="form-select selectBox" required>
                                                        <option disabled selected>{{__('admin.select_category')}}</option>
                                                        @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="fa fa-chevron-down"></i>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label for="description" class="form-label">{{ __('admin.description')}}</label>
                                                    <textarea class="form-control newtextarea" id="description" name="description"></textarea>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label for="status" class="form-label">{{ __('admin.status')}}</label>
                                                    <div>
                                                        <input type="radio" value="1" name="status" checked> {{ __('admin.active')}}
                                                        <input type="radio" value="0" name="status"> {{ __('admin.deactive')}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">{{ __('admin.add')}}</button>
                                    <a class="btn btn-cancel ms-3" href="{{ route('sub-categories-list') }}">
                                        {{ __('admin.cancel')}}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- <div class="mb-2">
                            <a href="{{ route('sub-categories-list') }}" class="mb-2 backurl" style="float:right;"><i class="fa fa-times"
                                aria-hidden="true"></i></a>
                        </div>
                        <h3 class="card-title">Edit Sub Category</h3> -->


            </div>
        </div>
    </div>
</div>



@stop

@section('scripts')
<script>
    $("#subcatadd").on('submit', function(event) {
        event.preventDefault();
        var formData = new FormData($("#subcatadd")[0]);
        formData.append('description',tinyMCE.get('description').getContent());
        if ($('#subcatadd').parsley().validate()) {
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
                            text: "{{__('admin.sub_categories_added_alert')}}",
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
                            text: "{{__('admin.subcategory_exist')}}",
                            showHideTransition: "slide",
                            icon: "error",
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
