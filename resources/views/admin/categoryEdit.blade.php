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
                <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">{{ __('admin.edit_category')}}
                </button>
            </li>
        </ul>
        <div class="tab-content pt-3 pb-0" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <form class="" method="POST" enctype="multipart/form-data" action="{{ route('category-update') }}" data-parsley-validate id="categoryedit">
                    @csrf
                    <input type="hidden" name="id" value="{{ $category->id }}">
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
                                            <input type="text" class="form-control" id="name" name="name" required value="{{ $category->name }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="status" class="form-label">{{ __('admin.units')}}<span class="text-danger">*</span></label>
                                            <select class="js-example-basic-multiple w-100" name="units[]" id="units" multiple="multiple" required data-parsley-errors-container="#unit_error">
                                                {{-- <select multiple name="units[]" id="units" class="form-control" required>--}}
                                                <option disabled>{{__('admin.select_unit')}}</option>
                                                @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}" {{ in_array($unit->id, $categoryUnits) ? 'selected="selected"' : '' }}>
                                                    {{ $unit->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <div id="unit_error"></div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="description" class="form-label">{{ __('admin.description')}}</label>
                                            <textarea class="form-control newtextarea" id="description" name="description">{{ $category->description }}</textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="status" class="form-label">{{ __('admin.status')}}</label>
                                            <div>
                                                <input type="radio" value="1" name="status" {{ $category->status == 1 ? 'checked' : '' }}>
                                                {{ __('admin.active')}}
                                                <input type="radio" value="0" name="status" {{ $category->status == 0 ? 'checked' : '' }}>
                                                {{ __('admin.deactive')}}
                                            </div>
                                        </div>

                                    </div>


                                    <!-- <div class="col-12 mb-3">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div> -->
                                </div>

                            </div>

                        </div>

                        <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">{{ __('admin.update')}}</button>
                            <a href="{{ route('categories-list') }}" class="btn btn-cancel ms-3">{{ __('admin.cancel')}}</a>
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
    $("#categoryedit").on('submit', function(event) {
        event.preventDefault();
        var formData = new FormData($("#categoryedit")[0]);
        formData.append('description', tinyMCE.get('description').getContent());
        if ($('#categoryedit').parsley().validate()) {
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
                            text: "{{__('admin.categories_updated_alert')}}",
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
