@extends('admin/adminLayout')

@section('content')

<style>
    .form-switch .form-check-input {
        margin-left: 0em !important;
    }
</style>

<div class="row">
    <div class="col-md-12 d-flex align-items-center  mb-3">
        <h1 class="mb-0 h3">{{ __('admin.Address')}}</h1>
        <a href="{{ route('supplier-address-list') }}" class="mb-2 backurl ms-auto btn-close"></a>
    </div>

    <div class="col-12 mb-2">
        <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#address_listing" type="button" role="tab" aria-controls="address_listing" aria-selected="true">{{ __('dashboard.add_address')}}
                </button>
            </li>
        </ul>

        <div class="tab-content pt-3 pb-0" id="myTabContent">
            <div class="tab-pane fade show active" id="add_address" role="tabpanel" aria-labelledby="home-tab">
                <form id="addSupplierAddress" class="" action="{{ route('supplier-address-create') }}" method="POST" enctype="multipart/form-data" autocomplete="off" data-parsley-validate="" novalidate="">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0"><img height="20px" src="{{ URL::asset('front-assets/images/icons/icon_newaddress.png') }}"
                                            alt="Designation Details" class="pe-2">
                                        <span>{{ __('order.address_details')}}</span>
                                    </h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <div class="row floatlables">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="address_name" class="form-label">{{ __('rfqs.address_name')}}<span class="text-danger">*</span></label>
                                                <input type="text" name="address_name" class="form-control" id="address_name" required="">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-">
                                                <label for="address_line_1" class="form-label">{{ __('rfqs.address_line1')}}<span class="text-danger">*</span></label>
                                                <input type="text" name="address_line_1" class="form-control" id="address_line_1" required="">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="address_line_2" class="form-label">{{ __('rfqs.address_line2')}}</label>
                                                <input type="text" name="address_line_2" class="form-control" id="address_line_2">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="SubDistrict" class="form-label">{{ __('rfqs.sub_district')}}<span class="text-danger">*</span></label>
                                                <input type="text" name="sub_district" class="form-control" id="sub_district" required="">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="District" class="form-label">{{ __('rfqs.district')}}<span class="text-danger">*</span></label>
                                                <input type="text" name="district" class="form-control" id="district" required="">
                                            </div>

                                            <div class="col-md-3 mb-3 select2-block" id="countryId_block">
                                                <label for="countryId" class="form-label">{{ __('admin.country') }}<span style="color:red;">*</span></label>
                                                <select required data-parsley-errors-container="#supplier_country_error" class="form-select select2-custom" id="countryId" name="countryId" data-placeholder="{{ __('admin.select_country') }}">
                                                    <option value="">{{ __('admin.select_country') }}</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div id="supplier_country_error"></div>
                                            </div>

                                            <div class="col-md-3 mb-3 select2-block" id="stateId_block">
                                                <label for="stateId" class="form-label">{{ __('rfqs.provinces') }}<span class="text-danger">*</span></label>
                                                <select  required data-parsley-errors-container="#supplier_state_error" class="form-select select2-custom" id="stateId" name="stateId" data-placeholder="{{ __('rfqs.select_province') }}">
                                                    <option value="" >{{ __('rfqs.select_province') }}</option>
                                                    <option value="-1">Other</option>
                                                </select>
                                                <div id="supplier_state_error"></div>
                                            </div>

                                            <div class="col-md-3 mb-3 hide" id="state_block">
                                                <label for="state" class="form-label">{{ __('rfqs.other_provinces') }}<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="state" id="state" required>
                                            </div>

                                            <div class="col-md-3 mb-3 select2-block" id="cityId_block">
                                                <label for="cityId" class="form-label">{{ __('rfqs.city') }}<span class="text-danger">*</span></label>
                                                <select  required data-parsley-errors-container="#supplier_city_error" class="form-select select2-custom" id="cityId" name="cityId" data-placeholder="{{ __('rfqs.select_city') }}" required>
                                                    <option value="">{{ __('rfqs.select_city') }}</option>
                                                </select>
                                                <div id="supplier_city_error"></div>
                                            </div>

                                            <div class="col-md-3 mb-3 hide" id="city_block">
                                                <label for="city" class="form-label">{{ __('rfqs.other_city') }}<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="city" id="city" >
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label for="pincode" class="form-label">{{ __('rfqs.pincode')}}*</label>
                                                <input type="text" class="form-control" name="pincode" id="pincode" data-parsley-maxlength="7" data-parsley-minlength="5" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\.*?)\..*/g, '$1');" data-parsley-type="number" required>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" value="1" name="default_address" type="checkbox" role="switch" id="is_primary" checked>
                                                    <label class="form-check-label ps-2" style="display: inline-block; padding-top: 0.2rem" for="is_primary">{{ __('admin.default_address')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" id="add_address_btn">{{ __('admin.add')}}</button>
                            <a href="{{ route('supplier-address-list') }}" class="btn btn-cancel ms-3">{{ __('admin.cancel')}}</a>
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
    $("#addSupplierAddress").on('submit', function(event) {
        event.preventDefault();
        var formData = new FormData($("#addSupplierAddress")[0]);
        if ($('#addSupplierAddress').parsley().validate()) {
            $("#add_address_btn").attr("disabled",true);
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: formData,
                contentType: false,
                processData: false,

                success: function(result) {
                    console.log(result);
                    if (result.success == true) {
                        resetToastPosition();
                        $.toast({
                            heading: "{{__('admin.success')}}",
                            text: "{{__('admin.address_added')}}",
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
                            heading: "{{__('admin.error')}}",
                            text: "{{__('admin.address_exist')}}",
                            showHideTransition: "slide",
                            icon: "error",
                            loaderBg: "#fc5661",
                            position: "top-right",
                        });
                         $("#add_address_btn").removeAttr("disabled");
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

    /*****begin: Add Supplier Address Detail******/
    var SnippetAddSupplierAddress = function(){

        var selectCountryGetState = function() {
            $("#countryId").on('change', function() {
                let country = $(this).val();
                let targetUrl = "{{ route('admin.state.by.country', ":id") }}";
                targetUrl = targetUrl.replace(':id', country);
                var newOptions = '';

                if (country != '') {
                    $.ajax({
                        url: targetUrl,
                        type: 'POST',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        success: function(response) {
                            if (response.success) {
                                $("#stateId").empty();
                                $("#cityId").empty();

                                newOption = new Option('{{ __('admin.select_province') }}', '', true, true);
                                    $('#stateId').append(newOption);

                                    for (let i = 0; i < response.data.length; i++) {
                                        newOption = new Option(response.data[i].name, response.data[i].id, true, true);
                                        $('#stateId').append(newOption);
                                    }

                                    newOption = new Option('Other', '-1', true, true);
                                    $('#stateId').append(newOption);

                                    /*******begin:Add and remove last null option for no conflict*******/
                                    newOption = new Option('0', '0', true, true);
                                    $('#stateId').append(newOption);
                                    $('#stateId').each(function () {
                                        $(this).find("option:last").remove();
                                    });
                                    /*******end:Add and remove last null option for no conflict*******/
                                    $('#stateId').val(null).trigger('change');
                            }
                        }
                    });
                }
            });
        },

        selectStateGetCity = function(){

                $('#stateId').on('change',function(){

                    let state = $(this).val();
                    let targetUrl = "{{ route('admin.city.by.state',":id") }}";
                    targetUrl = targetUrl.replace(':id', state);
                    var newOption = '';

                    // Add Remove Other State filed
                    if (state == -1) {
                        $('#stateId_block').removeClass('col-md-4');
                        $('#stateId_block').removeClass('col-md-6');
                        $('#stateId_block').addClass('col-md-3');

                        $('#state_block').removeClass('hide');
                        $('#state').attr('required','required');

                        $('#cityId_block').removeClass('col-md-4');
                        $('#cityId_block').addClass('col-md-3');

                        $('#cityId').empty();

                        //set default options on other state mode
                        newOption = new Option('{{ __('admin.select_city') }}','', true, true);
                        $('#cityId').append(newOption).trigger('change');

                        newOption = new Option('Other','-1', true, true);
                        $('#cityId').append(newOption).trigger('change');


                    } else {
                        $('#state_block').addClass('hide');
                        $('#state').removeAttr('required','required');
                        $('#city_block').addClass('hide');
                        $('#city').removeAttr('required','required');

                        //Fetch cities by state
                        if (state != '') {
                            $.ajax({
                                url: targetUrl,
                                type: 'POST',
                                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},

                                success: function (response) {

                                    if (response.success) {

                                        $('#cityId').empty();

                                        newOption = new Option('{{ __('admin.select_city') }}', '', true, true);
                                        $('#cityId').append(newOption).trigger('change');

                                        for (let i = 0; i < response.data.length; i++) {
                                            newOption = new Option(response.data[i].name, response.data[i].id, true, true);
                                            $('#cityId').append(newOption).trigger('change');
                                        }

                                        newOption = new Option('Other', '-1', true, true);
                                        $('#cityId').append(newOption).trigger('change');

                                        /*******begin:Add and remove last null option for no conflict*******/
                                        newOption = new Option('0', '0', true, true);
                                        $('#cityId').append(newOption).trigger('change');
                                        $('#cityId').each(function () {
                                            $(this).find("option:last").remove();
                                        });
                                        /*******end:Add and remove last null option for no conflict*******/


                                        $('#cityId').val(null).trigger('change');

                                    }

                                },
                                error: function () {

                                }
                            });
                        }

                    }

                });

            },

            selectCitySetOtherCity = function(){

                $('#cityId').on('change',function(){

                    let city = $(this).val();

                    // Add Remove Other City filed
                    if (city == -1) {
                        $('#cityId_block').removeClass('col-md-4');
                        $('#cityId_block').addClass('col-md-3');

                        $('#city_block').removeClass('hide');
                        $('#city').attr('required','required');


                    } else {

                        $('#city_block').addClass('hide');
                        $('#city').removeAttr('required','required');

                    }

                });

            },

            initiateCityState = function(){

                let country = $("#country").val();
                let state               =   $('#state').val();
                let selectedState       =   $('#stateId').val();
                let selectedCity        =   $("#cityId").attr('data-selected-city');

                if (country != null && country != '') {
                    $("#countryId").val(country).trigger('change');
                }

                if (state != null && state !='') {
                    $('#stateId').val('-1').trigger('change');
                }

                if (selectedState !='' && selectedState != null) {
                    $('#stateId').val(selectedState).trigger('change');
                }

                if (selectedCity !='' && selectedCity!=null ) {

                    setTimeout(
                        function() {
                            $('#cityId').val(selectedCity).trigger('change')
                        },
                        500); //set delayed for 500 to run after city sync
                }

            };

        return {
            init:function(){
                selectCountryGetState(),
                selectStateGetCity(),
                selectCitySetOtherCity(),
                initiateCityState()
            }
        }

    }(1);jQuery(document).ready(function(){
        SnippetAddSupplierAddress.init();
    });
    /*****end: Add Supplier Address Detail******/
</script>
@stop
