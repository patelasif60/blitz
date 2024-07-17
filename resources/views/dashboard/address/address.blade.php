<!--begin:Required Mix Js/CSS for Address Modal-->
<script src="{{ URL::asset('assets/vendors/jquery-toast-plugin/jquery.toast.min.js') }}"></script>
<link rel="stylesheet" href="{{ URL::asset('/assets/vendors/jquery-toast-plugin/jquery.toast.min.css') }}">
<link href="{{ URL::asset('front-assets/css/front/select2.min.css') }}" rel="stylesheet">
<!--end:Required Mix Js/CSS Address Modal-->

<!--begin:Custom css for Address Modal-->
<style>

    .select2-container {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--single, .select2-container--default .select2-selection--multiple{
        height: 47px !important;
    }

    .select2-container .select2-selection--single .select2-selection__rendered{
        padding-top: 3px !important;
    }
</style>
<!--end:Custom css for Address Modal-->
<div class="header_top d-flex align-items-center">
    <h1 class="mb-0">{{ __('dashboard.Addresses') }}</h1>
    @can('create buyer address')
    <a href="javascript:void(0);" class="btn btn-warning ms-auto btn-sm"
       id="openNewAddress" style="padding-top: .1rem;padding-bottom: .1rem;" data-bs-toggle="modal" data-bs-target="#addnewaddress"><img
            src="{{ URL::asset('front-assets/images/icons/icon_newaddress.png') }}" alt="" height="16px">
        {{ __('dashboard.add_a_new_address') }}</a>
    @endcan

</div>
<div class="row g-3 addresses_section">
    @if (count($userAddress) || count($otherUserAddress))
    @if (count($userAddress))
    @foreach ($userAddress as $address)
    <div class="col-md-6" id="addressSectionBlock{{ $address->id }}">
        <div class="card radius_1 h-100">
            <div class="card-header py-2 d-flex card_headertext">
                <div class="pe-3">{{ $address->address_name }}</div>
                <div class="actions ms-auto no-wrap d-flex">
                    <div class="col-md-5 d-flex justify-content-end">
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input account_is_primary me-2" id="account_is_primary_{{$address->id}}" data-id="{{$address->id}}" value="{{($address->id == $primaryAddressId) ? 1 : 0}}" name="default_address" type="checkbox" role="switch" {{ (($address->id == $primaryAddressId) ? 1 : 0) == 0 ? '' : 'checked' }}>
                        </div>
                    </div>
                    @can('edit buyer address')
                    <a class="edit_address ms-1" data-id="{{ $address->id }}" href="javascript:void(0);"><img
                            src="{{ URL::asset('front-assets/images/icons/icon_edit_add.png') }}"
                            alt="Edit" title="{{__('admin.edit')}}"></a>
                    @endcan
                    @can('delete buyer address')
                    <a class="delete_address ms-2" data-id="{{ $address->id }}" href="javascript:void(0);"
                       class="ms-4"><img
                            src="{{ URL::asset('front-assets/images/icons/icon_delete_add.png') }}"
                            alt="Delete" title="{{__('admin.delete')}}"></a>
                    @endcan

                </div>
            </div>
            <div class="card-body ">
                {{ $address->address_line_1 }}, {{ $address->address_line_2 }}, {{ $address->sub_district?($address->sub_district.','):'' }} {{ $address->district?($address->district.','):'' }}
                <br>{{ $address->city_id > 0 ? (isset($address->getCity()->name) ? $address->getCity()->name : '-' ) : $address->city }} ,
                {{ $address->state_id > 0 ? (isset($address->getState()->name) ? $address->getState()->name : '-' ) : $address->state }}
                {{ $address->pincode }}
            </div>
        </div>
    </div>
    @endforeach
    @endif
    @if(count($otherUserAddress))
            <div>
                <div class="header_top d-flex align-items-center mt-3">
                    <h1 class="mb-0">{{ __('dashboard.other_ddresses') }}</h1>
                </div>
                <div class="row g-3 addresses_section">
        @foreach ($otherUserAddress as $address)

                    <div class="col-md-6" id="addressSectionBlock{{ $address->id }}">
                        <div class="card radius_1 h-100">
                            <div class="card-header py-2 d-flex card_headertext">
                                <div class="pe-3">{{$address->address_name}}</div>
                                <div class="actions ms-auto no-wrap d-flex">
                                    <div class="col-md-5 d-flex justify-content-end">
                                        <div class="form-check form-switch m-0">
                                           {{-- <input class="form-check-input account_is_primary me-2" id="account_is_primary_{{ $address->id }}" data-id="{{ $address->id }}" value="{{($address->id == $primaryAddressId) ? 1 : 0}}" name="default_address" type="checkbox" role="switch" {{ (($address->id == $primaryAddressId) ? 1 : 0) == 0 ? '' : 'checked' }}>--}}
                                        </div>
                                    </div>
                                    @can('edit buyer address')
                                    <a class="edit_address ms-1" data-id="{{ $address->id }}" href="javascript:void(0);"><img src="{{ URL::asset('front-assets/images/icons/icon_edit_add.png') }}" alt="Edit" title="{{__('admin.edit')}}"></a>
                                    @endcan
                                    @can('delete buyer address')
                                        <a class="delete_address ms-2" data-id="{{ $address->id }}" href="javascript:void(0);"><img src="{{ URL::asset('front-assets/images/icons/icon_delete_add.png') }}" alt="Delete" title="{{__('admin.delete')}}"></a>
                                    @endcan
                                </div>
                            </div>
                            <div class="card-body ">
                                {{ $address->address_line_1 }}, {{ $address->address_line_2 }}, {{ $address->sub_district?($address->sub_district.','):'' }} {{ $address->district?($address->district.','):'' }}
                                <br>{{ $address->city_id > 0 ? (isset($address->getCity()->name) ? $address->getCity()->name : '-' ) : $address->city }} ,
                                {{ $address->state_id > 0 ? (isset($address->getState()->name) ? $address->getState()->name : '-' ) : $address->state }}
                                {{ $address->pincode }}
                            </div>
                            <div class="card-footer" style="/* font-size: 2px; */"><small style="font-size: 11px !important;">{{__('rfqs.created_by')}} : {{ getUserName($address->user_id) }}
                                    @php $addrees_primary = !empty($address->is_user_primary) ? json_decode($address->is_user_primary) : []; @endphp
                                    @if(in_array($address->user_id,$addrees_primary))
                                    <span class="alert alert-primary p-1 mx-1">{{__('admin.primary')}}</span>
                                    @endif
                                </small></div>
                        </div>
                    </div>

        @endforeach
                </div>
            </div>
    @endif
    @else
    <div class="col-md-12">
        <div class="alert alert-danger radius_1 text-center fs-6 fw-bold font_sub">
            {{ __('dashboard.no_address_found') }}</div>
    </div>
    @endif

{{-- Address list end --}}
<!-- Add Address popup -->
<div class="modal fade" id="addnewaddress" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content radius_1 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title d-flex w-100 align-items-center" id="viewModalLabel">
                    {{ __('dashboard.add_a_new_address') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row  g-3">
                    <div class="col-md-12 pt-3">
                        <form data-parsley-validate autocomplete="off" id="add_address_form">
                            @csrf
                            <div class="row floatlables">
                                <div class="col-sm-12 mb-4">
                                    <label for="address_name"
                                           class="form-label">{{ __('dashboard.Address_Name') }}<span class="text-danger">*</span></label>
                                    <input type="text" name="address_name" class="form-control" id="address_name"
                                           required>
                                </div>
                                <div class="col-sm-12 mb-4">
                                    <label for="address_line_1"
                                           class="form-label">{{ __('dashboard.Address_Line_1') }}<span class="text-danger">*</span></label>
                                    <input type="text" name="address_line_1" class="form-control" id="address_line_1"
                                           required>
                                </div>
                                <div class="col-sm-12 mb-4">
                                    <label for="address_line_2"
                                           class="form-label">{{ __('dashboard.address_line_2') }}</label>
                                    <input type="text" class="form-control" name="address_line_2" id="address_line_2">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="SubDistrict" class="form-label">{{ __('rfqs.sub_district') }}<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="sub_district" id="sub_district"
                                           required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="District" class="form-label">{{ __('rfqs.district') }}<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="district" id="district"
                                           required>
                                </div>

                                <div class="col-md-6 mb-4" id="stateId_block">
                                    <label for="state_id" class="form-label">{{ __('rfqs.provinces') }}<span class="text-danger">*</span></label>
                                    <select class="form-select" id="state_id" name="state_id" data-placeholder="{{ __('rfqs.select_province') }}" required>
                                        <option value="" >{{ __('rfqs.select_province') }}</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}" >{{ $state->name }}</option>
                                        @endforeach


                                        <option value="-1">Other</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-4 hide" id="state_block">
                                    <label for="state" class="form-label">{{ __('rfqs.other_provinces') }}<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="state" id="state" required>
                                </div>

                                <div class="col-md-6 mb-4" id="cityId_block">
                                    <label for="city_id" class="form-label">{{ __('rfqs.city') }}<span class="text-danger">*</span></label>
                                    <select class="form-select" id="city_id" name="city_id" data-placeholder="{{ __('rfqs.select_city') }}" required>
                                        <option value="">{{ __('rfqs.select_city') }}</option>
                                        <option value="-1">Other</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-4 hide" id="city_block">
                                    <label for="city" class="form-label">{{ __('admin.other_city') }}<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="city" id="city" >
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="pincode"
                                           class="form-label">{{ __('dashboard.pincode') }}<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="pincode" id="pincode" data-parsley-maxlength="6"
                                           data-parsley-minlength="5" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\.*?)\..*/g, '$1');"
                                           data-parsley-type="number" required>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="default_address" id="default_address" checked>
                                        <label class="form-check-label"	style="margin-top: 0.125rem;" for="default_address">Set as Primary</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submitAddressForm" class="btn btn-primary">
                    <img src="{{ URL::asset('front-assets/images/icons/icon_add_add_btn.png') }}"
                         alt="{{ __('dashboard.add_address') }}"
                         class="pe-1">{{ __('dashboard.add_address') }}
                </button>
            </div>
        </div>
    </div>
</div>
{{-- End Add Address popup --}}

{{-- Edit Address --}}
<div class="modal fade" id="editAddress" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content radius_1 shadow-lg" id="editAddressModalBlock">

        </div>
    </div>
</div>
{{-- END edit address --}}

<!--begin:Required Mix Js for Address Modal-->
<script src="{{ URL::asset('front-assets/js/front/select2.full.min.js') }}"></script>
<!--end:Required Mix Js for Address Modal-->

<script>
    // test toast
    // $.toast({
    // 	heading: '{{ __('admin.success') }}',
    // 	text: '{{ __('admin.supplier_address_change') }}.',
    // 	showHideTransition: 'slide',
    // 	icon: 'success',
    // 	loaderBg: '#f96868',
    // 	position: 'top-right'
    // });
    // test toast

    $(document).on('click', '#openNewAddress', function(e) {
        $('#add_address_form').parsley().reset();
    });

    $(document).on('click', '#submitAddressForm', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        /** Validation remove of state and city while onchnage - start **/
        SnippetAddUserAddress.parsleyValidationRemoveForStateCity();
        /** Validation remove of state and city while onchnage - end **/
        if ($('#add_address_form').parsley().validate()) {
            var formData = new FormData($('#add_address_form')[0]);
            formData.append('changesUserNotification', 1);
            $.ajax({
                url: '{{ route('dashboard-create-address-ajax') }}',
                data: formData,
                type: 'POST',
                contentType: false,
                processData: false,
                success: function(successData) {
                    // $.toast({
                    // 	heading: "{{__('admin.success')}}",
                    // 	text: "{{__('admin.address_added')}}",
                    // 	showHideTransition: "slide",
                    // 	icon: "success",
                    // 	loaderBg: "#f96868",
                    // 	position: "top-right",
                    // });

                    new PNotify({
                        text: "{{__('admin.address_added')}}",
                        type: 'success',
                        styling: 'bootstrap3',
                        animateSpeed: 'fast',
                        delay: 1000
                    });


                    $('#addnewaddress').modal('hide');
                    addressSection = successData.html;
                    $('#myAddressCount').html(successData.userAddressCount);
                    $('#mainContentSection').html(addressSection);
                    //loadUserActivityData();
                },
                error: function() {
                    console.log('error');
                }
            });
        }
    });

    $(document).on('click', '.edit_address', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var id = $(this).attr('data-id');
        $.ajax({
            url: "{{ route('dashboard-edit-address-ajax', '') }}" + "/" + id,
            type: 'GET',
            success: function(successData) {
                $('#editAddressModalBlock').html(successData.html);
                $('#editAddress').modal('show');
            },
            error: function() {
                console.log('error');
            }
        });
    });

    $(document).on('click', '.delete_address', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var id = $(this).attr('data-id');
        let is_checked = $("#account_is_primary_"+id).prop('checked');
        if (is_checked) {
            swal("{{ __('admin.primary_address_msg') }}!", {
                icon: "/front-assets/images/icons/ref_id_1.png",
            });
            return false;
        }
       swal({
            title: "{{ __('dashboard.are_you_sure') }}?",
            text: "{{ __('dashboard.delete_warning') }}",
            icon: "/assets/images/bin.png",
            buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{ route('dashboard-delete-address-ajax', '') }}" + "/" + id,
                        type: 'GET',
                        success: function(successData) {
                            // $.toast({
                            //     heading: '{{ __('admin.success') }}',
                            //     text: '{{ __('admin.address_deleted') }}.',
                            //     showHideTransition: 'slide',
                            //     icon: 'success',
                            //     loaderBg: '#f96868',
                            //     position: 'top-right'
                            // });

                            new PNotify({
                                text: "{{ __('admin.address_deleted') }}",
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 1000
                            });

                            $('#myAddressCount').html(successData.userAddressCount);
                            $('#mainContentSection').html(successData.html);
                        },
                        error: function() {
                            console.log('error');
                        }
                    });
                }
            });
    });

    // $(document).on('click', '.account_is_primary', function(e) {
    // 	e.preventDefault();
    // 	let id = $(this).attr("data-id");
    // 	let is_checked = $(this).prop('checked');
    // 	if($("#addressCount").val() > 1) {
    // 		if (!is_checked) {
    // 			swal("{{ __('admin.primary_address_msg') }}!", {
    // 				icon: "warning",
    // 			});
    // 			return false;
    // 		}
    // 	}
    // 	let that = $(this);
    // 	swal({
    // 		title: "{{ __('admin.delete_sure_alert') }}",
    // 		text: is_checked?"{{ __('admin.primary_address_set_msg') }}.":"{{ __('admin.secondary_address_msg') }}",
    // 		icon: "warning",
    // 		buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
    // 		dangerMode: true,
    // 	})
    // 	.then((willDelete) => {
    // 		if (willDelete) {
    // 			let _token = $('meta[name="csrf-token"]').attr("content");
    // 			let senddata = {
    // 				id: id,
    // 				_token: _token,
    // 				is_primary:(is_checked ? 1 : 0)
    // 			}
    // 			$.ajax({
    // 				url: "{{ route('supplier-address-status-update') }}",
    // 				type: 'POST',
    // 				data: senddata,
    // 				success: function(successData) {
    // 					if (successData.success) {
    // 						$.toast({
    // 							heading: '{{ __('admin.success') }}',
    // 							text: '{{ __('admin.supplier_address_change') }}.',
    // 							showHideTransition: 'slide',
    // 							icon: 'success',
    // 							loaderBg: '#f96868',
    // 							position: 'top-right'
    // 						});
    // 						if (is_checked){
    // 							$('.account_is_primary').prop('checked',false).attr('checked',false);
    // 							$('#account_is_primary_'+id).prop('checked',true).attr('checked',true);
    // 						}else{
    // 							$('#account_is_primary_'+id).prop('checked',false).attr('checked',false);
    // 						}
    // 					}else{
    // 						$.toast({
    // 							heading: '{{ __('admin.warning') }}',
    // 							text: '{{ __('admin.something_error_message') }}',
    // 							showHideTransition: 'slide',
    // 							icon: 'warning',
    // 							loaderBg: '#57c7d4',
    // 							position: 'top-right'
    // 						})
    // 					}
    // 				},
    // 				error: function() {
    // 					console.log('error');
    // 				}
    // 			});

    // 		}
    // 	});
    // });

    $(document).on('click', '.account_is_primary', function(e) {
        e.preventDefault();
        let id = $(this).attr("data-id");
        let is_checked = $(this).prop('checked');
        if (!is_checked) {
            swal("{{ __('admin.primary_address_msg') }}!", {
                icon: "/front-assets/images/icons/ref_id_1.png",
            });
            return false;
        }

        let that = $(this);
        swal({
            title: "{{ __('admin.delete_sure_alert') }}",
            text: is_checked?"{{ __('admin.primary_address_set_msg') }}.":"{{ __('admin.secondary_address_msg') }}",
            icon: "/front-assets/images/icons/ref_id_1.png",
            buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    // let _token = $('meta[name="csrf-token"]').attr("content");
                    let _token = "{{ csrf_token() }}";
                    let senddata = {
                        id: id,
                        _token: _token,
                        is_primary:(is_checked ? 1 : 0),
                        changesUserNotification: 1,
                    }
                    $.ajax({
                        url: "{{ route('address-status-update') }}",
                        type: 'POST',
                        data: senddata,
                        success: function(successData) {
                            if (successData.success) {
                                // $.toast({
                                // 	heading: '{{ __('admin.success') }}',
                                // 	text: '{{ __('admin.address_change') }}.',
                                // 	showHideTransition: 'slide',
                                // 	icon: 'success',
                                // 	loaderBg: '#f96868',
                                // 	position: 'top-right'
                                // });

                                new PNotify({
                                    text: "{{ __('admin.address_change') }}",
                                    type: 'success',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 1000
                                });

                                if (is_checked){
                                    $('.account_is_primary').prop('checked',false).attr('checked',false);
                                    $('#account_is_primary_'+id).prop('checked',true).attr('checked',true);
                                }else{
                                    $('#account_is_primary_'+id).prop('checked',false).attr('checked',false);
                                }
                            }else{
                                // $.toast({
                                // 	heading: '{{ __('admin.warning') }}',
                                // 	text: '{{ __('admin.something_error_message') }}',
                                // 	showHideTransition: 'slide',
                                // 	icon: 'warning',
                                // 	loaderBg: '#57c7d4',
                                // 	position: 'top-right'
                                // })

                                new PNotify({
                                    text: "{{ __('admin.something_error_message') }}",
                                    type: 'warning',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 1000
                                });
                            }
                        },
                        error: function() {
                            console.log('error');
                        }
                    });

                }
            });
    });
    function select2Initiate(){

        $('#state_id').select2({
            dropdownParent  : $('#stateId_block'),
            placeholder:  $(this).attr('data-placeholder')

        });

        $('#city_id').select2({
            dropdownParent  : $('#cityId_block'),
            placeholder:  $(this).attr('data-placeholder')

        });

    }

    $( document ).ready(function() {

        select2Initiate();
    });

	/*****begin: Add User Address Detail******/
    var SnippetAddUserAddress = function(){

        var selectStateGetCity = function(){

                $('#state_id').on('change',function(){

                    let state = $(this).val();
                    let targetUrl = "{{ route('admin.city.by.state',":id") }}";
                    targetUrl = targetUrl.replace(':id', state);
                    var newOption = '';

                    // Add Remove Other State filed
                    if (state == -1) {

                        $('#state_block').removeClass('hide');
                        $('#state').attr('required','required');

                        $('#city_id').empty();

                        //set default options on other state mode
                        newOption = new Option('{{ __('admin.select_city') }}','', true, true);
                        $('#city_id').append(newOption).trigger('change');

                        newOption = new Option('Other','-1', true, true);
                        $('#city_id').append(newOption).trigger('change');


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

                                        $('#city_id').empty();

                                        newOption = new Option('{{ __('admin.select_city') }}', '', true, true);
                                        $('#city_id').append(newOption).trigger('change');

                                        for (let i = 0; i < response.data.length; i++) {
                                            newOption = new Option(response.data[i].name, response.data[i].id, true, true);
                                            $('#city_id').append(newOption).trigger('change');
                                        }

                                        newOption = new Option('Other', '-1', true, true);
                                        $('#city_id').append(newOption).trigger('change');

                                        /*******begin:Add and remove last null option for no conflict*******/
                                        newOption = new Option('0', '0', true, true);
                                        $('#city_id').append(newOption).trigger('change');
                                        $('#city_id').each(function () {
                                            $(this).find("option:last").remove();
                                        });
                                        /*******end:Add and remove last null option for no conflict*******/


                                        $('#city_id').val(null).trigger('change');

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

                $('#city_id').on('change',function(){

                    let city = $(this).val();

                    // Add Remove Other City filed
                    if (city == -1) {

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
                let selectedState       =   $('#state_id').val();
                let selectedCity        =   $("#city_id").attr('data-selected-city');

                if (state != null && state !='') {
                    $('#state_id').val('-1').trigger('change');
                }

                if (selectedState !='' && selectedState != null) {
                    $('#state_id').val(selectedState).trigger('change');
                }

                if (selectedCity !='' && selectedCity!=null ) {

                    setTimeout(
                        function() {
                            $('#city_id').val(selectedCity).trigger('change')
                        },
                        500); //set delayed for 500 to run after city sync
                }

            },

            select2Initiate = function(){

                $('#state_id').select2({
                    dropdownParent  : $('#stateId_block'),
                    placeholder:  $(this).attr('data-placeholder')

                });

                $('#city_id').select2({
                    dropdownParent  : $('#cityId_block'),
                    placeholder:  $(this).attr('data-placeholder')

                });

            }

        return {
            init:function(){
                select2Initiate(),
                selectStateGetCity(),
                selectCitySetOtherCity(),
                initiateCityState()
            },
            parsleyValidationRemoveForStateCity : function () {
                window.Parsley.on('form:validated', function(){
                    $('select').on('select2:select', function(evt) {
                        $("#state_id").parsley().validate();
                        $("#city_id").parsley().validate();
                    });
                });
            }
        }

    }(1);jQuery(document).ready(function(){
        SnippetAddUserAddress.init();
    });
    /*****end: Add User Address Detail******/

</script>
