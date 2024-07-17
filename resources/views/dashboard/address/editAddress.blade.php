<!--begin:Required CSS for Address Modal-->
<script src="{{ URL::asset('assets/vendors/jquery-toast-plugin/jquery.toast.min.js') }}"></script>
<link rel="stylesheet" href="{{ URL::asset('/assets/vendors/jquery-toast-plugin/jquery.toast.min.css') }}">
<link href="{{ URL::asset('front-assets/css/front/select2.min.css') }}" rel="stylesheet">

<!--end:Required CSS Address Modal-->

<!--begin:Custom css for Address Modal-->
<style>

    .select2-container {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--single, .select2-container--default .select2-selection--multiple{
        height: 47px !important;
    }

</style>
<!--end:Custom css for Address Modal-->

<div class="modal-header">
    <h5 class="modal-title d-flex w-100 align-items-center" id="viewModalLabel">{{ __('dashboard.edit_a_address') }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row  g-3">
        <div class="col-md-12 pt-3">
            <form id="edit_address_form" data-parsley-validate autocomplete="off">
                @csrf
                <div class="row floatlables">
                    <div class="col-sm-12 mb-4">
                        <input type="hidden" name="id" value="{{ $userAddress->id }}">
                        <label for="address_name" class="form-label">{{ __('dashboard.Address_Name') }}<span class="text-danger">*</span></label>
                        <input type="text" name="address_name" class="form-control" id="address_name"
                            value="{{ $userAddress->address_name }}" required>
                    </div>
                    <div class="col-sm-12 mb-4">
                        <label for="address_line_1" class="form-label">{{ __('dashboard.Address_Line_1') }}<span class="text-danger">*</span></label>
                        <input type="text" name="address_line_1" class="form-control" id="address_line_1"
                            value="{{ $userAddress->address_line_1 }}" required>
                    </div>
                    <div class="col-sm-12 mb-4">
                        <label for="address_line_2" class="form-label">{{ __('dashboard.address_line_2') }}</label>
                        <input type="text" class="form-control" name="address_line_2" id="address_line_2"
                            value="{{ $userAddress->address_line_2 }}">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="SubDistrict" class="form-label">{{ __('rfqs.sub_district') }}<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="sub_district" id="sub_district"
                               value="{{ $userAddress->sub_district }}" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="District" class="form-label">{{ __('rfqs.district') }}<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="district" id="district"
                               value="{{ $userAddress->district }}" required>
                    </div>

                    <div class="col-md-6 mb-4" id="stateIdEdit_block">
                        <label for="stateEdit_id" class="form-label">{{ __('rfqs.provinces') }}<span class="text-danger">*</span></label>
                        <select class="form-select select2-custom" id="stateEdit_id" name="stateEdit_id" data-id="stateEdit_id" data-placeholder="{{ __('rfqs.select_province') }}" required>
                            <option value="" >{{ __('rfqs.select_province') }}</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->id }}" @if($state->id == $userAddress->state_id) selected @endif>{{ $state->name }}</option>
                            @endforeach
                            <option value="-1">Other</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-4 hide" id="stateEdit_block">
                        <label for="stateEdit" class="form-label">{{ __('rfqs.other_provinces') }}<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value="{{ $userAddress->state }}" name="stateEdit" id="stateEdit" required>
                    </div>

                    <div class="col-md-6 mb-4" id="cityIdEdit_block">
                        <label for="cityEdit_id" class="form-label">{{ __('rfqs.city') }}<span class="text-danger">*</span></label>
                        <select class="form-select select2-custom" id="cityEdit_id" name="cityEdit_id" data-placeholder="{{ __('rfqs.select_city') }}" data-selected-city="{{ $userAddress->city_id }}" required>
                            <option value="">{{ __('rfqs.select_city') }}</option>
                            <option value="-1">Other</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-4 hide" id="cityEdit_block">
                        <label for="cityEdit" class="form-label">{{ __('rfqs.other_city') }}<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="cityEdit" value="{{ $userAddress->city }}" id="cityEdit" >
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="pincode" class="form-label">{{ __('dashboard.pincode') }}<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="pincode" id="pincode"
                               data-parsley-minlength="5" data-parsley-maxlength="6" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\.*?)\..*/g, '$1');"
                            value="{{ $userAddress->pincode }}" required>
                    </div>
					<div class="col-md-12 mb-3">
						<div class="form-check form-switch">
                            @php $addrees_primary = !empty($userAddress->is_user_primary) ? json_decode($userAddress->is_user_primary) : []; @endphp
                            @if($userAddress->user_id == Auth::user()->id)
							    <input class="form-check-input" value="1" name="default_address" type="checkbox" role="switch" id="account_is_primary" data-id="{{$userAddress->id}}" value="{{ (in_array($userAddress->user_id,$addrees_primary)) ? 1 : 0}}" {{ ((in_array($userAddress->user_id,$addrees_primary)) ? 1 : 0) == 0 ? '' : 'checked' }}>
                            @else
                                <input class="form-check-input" value="1" name="default_address" type="checkbox" role="switch" id="account_is_primary" data-id="{{$userAddress->id}}" value="{{ (in_array($userAddress->user_id,$addrees_primary)) ? 1 : 0}}" {{ ((in_array($userAddress->user_id,$addrees_primary)) ? 1 : 0) == 0 ? '' : 'checked' }} disabled>
                            @endif
                                <label class="form-check-label ps-2" style="display: inline-block; padding-top: 0.2rem" for="account_is_primary">{{ __('admin.default_address')}}</label>
						</div>
					</div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" id="submitEditAddressForm" class="btn btn-primary">
        <img src="{{ URL::asset('front-assets/images/icons/icon_add_add_btn.png') }}" alt="Add Address"
            class="pe-1">{{ __('dashboard.save') }}
        {{-- <img src="{{ URL::asset('assets/images/front/loader.gif') }}"
            style="height: 25px;width: 25px;" aria-hidden="true" class="hidden loader"> --}}
    </button>
</div>

<!--begin:Required Mix Js for Address Modal-->
<script src="{{ URL::asset('front-assets/js/front/select2.full.min.js') }}"></script>
<!--end:Required Mix Js for Address Modal-->

<script>
    $(document).ready(function() {

		// $(this).prop('checked', true);
				// let is_checked = $(this).prop('checked');
				// if (!is_checked) {
				// 	swal("{{ __('admin.primary_address_msg') }}!", {
				// 		icon: "/front-assets/images/icons/ref_id_1.png",
				// 	});
				// }

		$('#account_is_primary').on('click', function(e){
		// $(document).on('click', '#account_is_primary', function(e) {
			// e.preventDefault();
			var user_defaultAddress = "{{$primaryAddressId}}";
			var default_addressList = "{{$userAddress->id}}";
            var is_default = (user_defaultAddress == default_addressList) ? 1 : 0;

			if(is_default == 1){
				// $(this).prop('checked', true);
				// let is_checked = $(this).is(':checked');;
				// if (is_checked) {
					swal("{{ __('admin.primary_address_msg') }}!", {
						icon: "/front-assets/images/icons/ref_id_1.png",
					});
				// }
				return false;
			}else{
				// console.log('no');
				// var checkBoxes = $(this);
				// console.log(checkBoxes);
				// checkBoxes.prop("checked", !checkBoxes.prop("checked"));
			}



		});


        $(document).on('click', '#submitEditAddressForm', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            /** Validation remove of state and city while onchnage - start **/
            SnippetEditUserAddress.parsleyValidationRemoveForStateCity();
            /** Validation remove of state and city while onchnage - end **/
            if ($('#edit_address_form').parsley().validate()) {
                var formData = new FormData($('#edit_address_form')[0]);
                $.ajax({
                    url: '{{ route('dashboard-update-address-ajax') }}',
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function(successData) {

						// $.toast({
                        //     heading: "{{__('admin.success')}}",
                        //     text: "{{__('admin.update_address')}}",
                        //     showHideTransition: "slide",
                        //     icon: "success",
                        //     loaderBg: "#f96868",
                        //     position: "top-right",
                        // });

						new PNotify({
							text: "{{__('admin.update_address')}}",
							type: 'success',
							styling: 'bootstrap3',
							animateSpeed: 'fast',
							delay: 1000
						});

                        addressSection = successData.html;
                        $('#editAddress').modal('hide');
                        $('#mainContentSection').html(addressSection);
                        //loadUserActivityData();
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        });
    })

    /*****begin: Edit User Address Detail******/
    var SnippetEditUserAddress = function(){

        var editSelectStateGetCity = function(){

                $('#stateEdit_id').on('change', function () {

                    let state = $(this).val();
                    let targetUrl = "{{ route('admin.city.by.state',":id") }}";
                    targetUrl = targetUrl.replace(':id', state);
                    var newOption = '';

                    // Add Remove Other State filed
                    if (state == -1) {

                        $('#stateEdit_block').removeClass('hide');
                        $('#stateEdit').attr('required', 'required');

                        $('#cityEdit_id').empty();

                        //set default options on other state mode
                        newOption = new Option('{{ __('admin.select_city') }}', '', true, true);
                        $('#cityEdit_id').append(newOption).trigger('change');

                        newOption = new Option('Other', '-1', true, true);
                        $('#cityEdit_id').append(newOption).trigger('change');


                    } else {
                        $('#stateEdit_block').addClass('hide');
                        $('#stateEdit').removeAttr('required', 'required');

                        $('#cityEdit_block').addClass('hide');
                        $('#cityEdit').removeAttr('required', 'required');

                        //Fetch cities by state
                        if (state != '') {
                            $.ajax({
                                url: targetUrl,
                                type: 'POST',
                                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},

                                success: function (response) {

                                    if (response.success) {

                                        $('#cityEdit_id').empty();

                                        newOption = new Option('{{ __('admin.select_city') }}', '', true, true);
                                        $('#cityEdit_id').append(newOption).trigger('change');

                                        for (let i = 0; i < response.data.length; i++) {
                                            newOption = new Option(response.data[i].name, response.data[i].id, true, true);
                                            $('#cityEdit_id').append(newOption).trigger('change');
                                        }

                                        newOption = new Option('Other', '-1', true, true);
                                        $('#cityEdit_id').append(newOption).trigger('change');

                                        /*******begin:Add and remove last null option for no conflict*******/
                                        newOption = new Option('0', '0', true, true);
                                        $('#cityEdit_id').append(newOption).trigger('change');
                                        $('#cityEdit_id').each(function () {
                                            $(this).find("option:last").remove();
                                        });
                                        /*******end:Add and remove last null option for no conflict*******/


                                        $('#cityEdit_id').val(null).trigger('change');

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

                $('#cityEdit_id').on('change',function(){

                    let city = $(this).val();

                    // Add Remove Other City filed
                    if (city == -1) {

                        $('#cityEdit_block').removeClass('hide');
                        $('#cityEdit').attr('required','required');


                    } else {

                        $('#cityEdit_block').addClass('hide');
                        $('#cityEdit').removeAttr('required','required');

                    }

                });

            },

            initiateCityState = function(){

                let state               =   $('#stateEdit').val();
                let selectedState       =   $('#stateEdit_id').val();
                let selectedCity        =   $("#cityEdit_id").attr('data-selected-city');

                if (state != null && state !='') {
                    $('#stateEdit_id').val('-1').trigger('change');
                }

                if (selectedState !='' && selectedState != null) {
                    $('#stateEdit_id').val(selectedState).trigger('change');
                }

                if (selectedCity !='' && selectedCity!=null ) {

                    setTimeout(
                        function() {
                            $('#cityEdit_id').val(selectedCity).trigger('change')
                        },
                        400); //set delayed for 500 to run after city sync
                }

            },

            select2Initiate = function(){

                $('#stateEdit_id').select2({
                    dropdownParent  : $('#stateIdEdit_block'),
                    placeholder:  $(this).attr('data-placeholder')

                });

                $('#cityEdit_id').select2({
                    dropdownParent  : $('#cityIdEdit_block'),
                    placeholder:  $(this).attr('data-placeholder')

                });

            }

        return {
            init:function(){
                select2Initiate(),
                editSelectStateGetCity(),
                selectCitySetOtherCity(),
                initiateCityState()
            },
            parsleyValidationRemoveForStateCity : function () {
                window.Parsley.on('form:validated', function(){
                    $('select').on('select2:select', function(evt) {
                        $("#stateEdit_id").parsley().validate();
                        $("#cityEdit_id").parsley().validate();
                    });
                });
            }
        }

    }();jQuery(document).ready(function(){
        SnippetEditUserAddress.init();
    });
    /*****end: Edit User Address Detail******/

</script>
