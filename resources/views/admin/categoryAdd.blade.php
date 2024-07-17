@extends('admin/adminLayout')

@section('content')
<div class="row">
    <div class="col-md-12 d-flex align-items-center  mb-3">
        <h1 class="mb-0 h3">{{ __('admin.category')}}</h1>
        <a href="{{ route('categories-list') }}" class="mb-2 backurl ms-auto btn-close"></a>
    </div>

    <div class="col-12 mb-2">
        <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">{{ __('admin.add_category')}}
                </button>
            </li>
        </ul>

        <div class="tab-content pt-3 pb-0" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <!-- <div class="mb-2">
                    <a href="{{ route('categories-list') }}" class="mb-2 backurl" style="float:right;"><i class="fa fa-times"
                        aria-hidden="true"></i></a>
                </div>
                <h3 class="card-title">Add Category</h3> -->
                <form class="" method="POST" enctype="multipart/form-data" action="{{ route('category-create') }}" data-parsley-validate id="categoryadd">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0">
                                        <img src="{{URL::asset('assets/icons/boxes.png')}}" alt="Order Details" class="pe-2">
                                        <span>{{ __('admin.category_details')}}</span>
                                    </h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">{{ __('admin.category')}}<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="status" class="form-label">{{ __('admin.units')}}<span class="text-danger">*</span></label>
                                            <select class="js-example-basic-multiple w-100" name="units[]" id="units" multiple="multiple" required data-parsley-errors-container="#unit_error">
                                                {{-- <select multiple name="units[]" id="units" class="form-control" required>--}}
                                                <option disabled>{{__('admin.select_unit')}}</option>
                                                @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                @endforeach
                                            </select>
                                            <div id="unit_error"></div>
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
                                            <a class="btn btn-cancel ms-3" href="{{ route('categories-list') }}">
                                                {{ __('admin.cancel')}}
                                    </a>
                                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

    @stop

    @section('scripts')
    <script>
        $("#categoryadd").on('submit', function(event) {
            event.preventDefault();
            var formData = new FormData($("#categoryadd")[0]);
            formData.append('description', tinyMCE.get('description').getContent());
            if ($('#categoryadd').parsley().validate()) {
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
                                text: "{{__('admin.categories_added_alert')}}",
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
                                text: "{{__('admin.category_exist')}}",
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
