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
                <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#address_listing" type="button" role="tab" aria-controls="address_listing" aria-selected="true">{{ __('admin.edit_address')}}
                </button>
            </li>
        </ul>

        <div class="tab-content pt-3 pb-0" id="myTabContent">
            <div class="tab-pane fade show active" id="add_address" role="tabpanel" aria-labelledby="home-tab">
                <form id="addSupplierAddress" class="" action="{{ route('supplier-address-update') }}" method="POST" enctype="multipart/form-data" autocomplete="off" data-parsley-validate="" novalidate="">
                    @csrf
                    <input type="hidden" name="id" value="{{ $address->id }}">
                    @if(isset($all_addresses))
                        <input type="text" id="defaultAddressValues" value="{{in_array('1',array_column($all_addresses->toArray(), 'default_address')) ? 1 : 0}}" style="display:none">
                    @endif
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
                                                <input type="text" name="address_name" class="form-control" id="address_name" value="{{ $address->address_name }}" required="">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="address_line_1" class="form-label">{{ __('rfqs.address_line1')}}<span class="text-danger">*</span></label>
                                                <input type="text" name="address_line_1" class="form-control" id="address_line_1" value="{{ $address->address_line_1 }}" required="">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="address_line_2" class="form-label">{{ __('rfqs.address_line2')}}</label>
                                                <input type="text" name="address_line_2" class="form-control" id="address_line_2" value="{{ $address->address_line_2 }}">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="SubDistrict" class="form-label">{{ __('rfqs.sub_district')}}<span class="text-danger">*</span></label>
                                                <input type="text" name="sub_district" class="form-control" id="sub_district" value="{{ $address->sub_district }}" required="">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="District" class="form-label">{{ __('rfqs.district')}}<span class="text-danger">*</span></label>
                                                <input type="text" name="district" class="form-control" id="district" value="{{ $address->district }}" required="">
                                            </div>

                                            <div class="col-md-3 mb-3 select2-block" id="countryId_block">
                                                <label for="countryId" class="form-label">{{ __('admin.country') }}<span class="text-danger">*</span></label>
                                                <select data-parsley-errors-container="#supplier_country_error" class="form-select select2-custom form-control" id="countryId" name="countryId" data-placeholder="{{ __('rfqs.select_country') }}">
                                                    <option value="">{{ __('rfqs.select_country') }}</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}" @if($address->country_id == $country->id) selected @endif>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div id="supplier_country_error"></div>
                                            </div>

                                            <div class="col-md-3 mb-3 select2-block" id="stateId_block">
                                                <label for="stateId" class="form-label">{{ __('admin.provinces') }}<span class="text-danger">*</span></label>
                                                <select class="form-select select2-custom" id="stateId" name="stateId" data-placeholder="{{ __('admin.select_province') }}" required>
                                                    <option value="" >{{ __('admin.select_province') }}</option>
                                                    @foreach ($states as $state)
                                                        <option value="{{ $state->id }}" @if($address->state_id == $state->id) selected @endif >{{ $state->name }}</option>
                                                    @endforeach
                                                    <option value="-1">Other</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3 mb-3 hide" id="state_block">
                                                <label for="state" class="form-label">{{ __('admin.other_provinces') }}<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="state" id="state" value="{{ $address->state }}" required>
                                            </div>

                                            <div class="col-md-3 mb-3 select2-block" id="cityId_block">
                                                <label for="cityId" class="form-label">{{ __('admin.city') }}<span class="text-danger">*</span></label>
                                                <select class="form-select select2-custom" id="cityId" name="cityId" data-placeholder="{{ __('admin.select_city') }}" data-selected-city="{{ $address->city_id }}" required>
                                                    <option value="">{{ __('admin.select_city') }}</option>
                                                    <option value="-1">Other</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3 mb-3 hide" id="city_block">
                                                <label for="city" class="form-label">{{ __('admin.other_city') }}<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="city" id="city" value="{{ $address->city }}">
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label for="pincode" class="form-label">{{ __('rfqs.pincode')}}<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="pincode" value="{{ $address->pincode }}" id="pincode" data-parsley-maxlength="10" data-parsley-minlength="5" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\.*?)\..*/g, '$1');" data-parsley-type="number" required="">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input account_is_primary" value="1" name="default_address" type="checkbox" role="switch" id="account_is_primary_{{$address->id}}" data-id="{{$address->id}}" value="{{ $address->default_address == 0 ? 0 : 1 }}" {{ $address->default_address == 0 ? '' : 'checked' }}>
                                                    <label class="form-check-label ps-2" style="display: inline-block; padding-top: 0.2rem" for="account_is_primary">{{ __('admin.default_address')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                            <button type="submit" id="update_address_btn" class="btn btn-primary">{{ __('admin.update')}}</button>
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
    //Make address primary (at a time only one address can be primary address)
    $(document).on('click', '.account_is_primary', function(e) {
        e.preventDefault();
        let id = $(this).attr("data-id");
        let is_checked = $(this).prop('checked');
        if (!is_checked) {
            swal("{{ __('admin.primary_address_msg') }}!", {
                icon: "warning",
            });
            return false;
        }

        let that = $(this);
        swal({
            title: "{{ __('admin.delete_sure_alert') }}",
            text: is_checked?"{{ __('admin.primary_address_set_msg') }}.":"{{ __('admin.secondary_address_msg') }}",
            icon: "/assets/images/bin.png",
            buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
				let _token = "{{ csrf_token() }}";
                let senddata = {
                    id: id,
                    _token: _token,
                    is_primary:(is_checked ? 1 : 0)
                }
                $.ajax({
                    url: "{{ route('supplier-address-status-update') }}",
                    type: 'POST',
                    data: senddata,
                    success: function(successData) {
                        if (successData.success) {
                            $.toast({
                                heading: '{{ __('admin.success') }}',
                                text: '{{ __('admin.supplier_address_change') }}.',
                                showHideTransition: 'slide',
                                icon: 'success',
                                loaderBg: '#f96868',
                                position: 'top-right'
                            });
                            if (is_checked){
                                $('.account_is_primary').prop('checked',false).attr('checked',false);
                                $('#account_is_primary_'+id).prop('checked',true).attr('checked',true);
                            }else{
                                $('#account_is_primary_'+id).prop('checked',false).attr('checked',false);
                            }
                        }else{
                            $.toast({
                                heading: '{{ __('admin.warning') }}',
                                text: '{{ __('admin.something_error_message') }}',
                                showHideTransition: 'slide',
                                icon: 'warning',
                                loaderBg: '#57c7d4',
                                position: 'top-right'
                            })
                        }
                    },
                    error: function() {
                        console.log('error');
                    }
                });

            }
        });
    });

    $("#addSupplierAddress").on('submit', function(event) {
        event.preventDefault();
        var formData = new FormData($("#addSupplierAddress")[0]);
        if ($('#addSupplierAddress').parsley().validate()) {
            $("#update_address_btn").attr("disabled",true);
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: formData,
                contentType: false,
                processData: false,

                success: function(result) {
                    if (result.success == true) {
                        resetToastPosition();
                        $.toast({
                            heading: "{{__('admin.success')}}",
                            text: "{{__('admin.update_address')}}",
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
                        setTimeout(function() {
                            window.top.location = $(".backurl").attr('href')
                        }, 3000);
                        $("#update_address_btn").removeAttr("disabled");
                    }
                },
                error: function(xhr) {
                    alert('{{__('admin.error_while_selecting_list')}}');
                    $("#update_address_btn").removeAttr("disabled");
                }
            });
        }
    });



    /*****begin: Edit Supplier Address Detail******/
    var SnippetEditSupplierAddress = function(){

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

                                        let selectedCity = $("#cityId").attr('data-selected-city');
                                        if (selectedCity != '' && selectedCity != null) {
                                            $('#cityId').val(selectedCity).trigger('change')
                                        } else {
                                            $('#cityId').val(null).trigger('change');
                                        }

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

                let state               =   $('#state').val();
                let selectedState       =   $('#stateId').val();

                if (state != null && state !='') {
                    $('#stateId').val('-1').trigger('change');
                }

                if (selectedState !='' && selectedState != null) {
                    $('#stateId').val(selectedState).trigger('change');
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
        SnippetEditSupplierAddress.init();
    });
    /*****end: Edit Supplier Address Detail******/
</script>
@stop
