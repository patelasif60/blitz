@extends('admin/adminLayout')

<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
@section('content')
<link href="{{ URL::asset('front-assets/js/front/crop/css/style.css') }}" rel="stylesheet">
<link href="{{ URL::asset('front-assets/js/front/crop/css/style-example.css') }}" rel="stylesheet">
<link href="{{ URL::asset('front-assets/js/front/crop/css/jquery.Jcrop.min.css') }}" rel="stylesheet">
<link href="{{ asset('front-assets/css/front/croppie.min.css') }}" rel='stylesheet' />
<script type="text/javascript" src="{{ URL::asset('front-assets/js/front/crop/scripts/jquery.Jcrop.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('front-assets/js/front/crop/scripts/jquery.SimpleCropper.js') }}"></script>
<script src='{{ asset("front-assets/js/front/croppie.js")}}'></script>
<script src="{{ URL::asset('front-assets/intlTelInput/js/intlTelInput.js') }}"></script>

<style>
    .verify{ top: 26%; position: absolute; right: 20px;}
	.userphoto .user_info_photo {
    position: absolute;
	width: 100px;
    height: 100px;
}

.user_info_photo {
    width: 100px;
    height: 100px;
    line-height: 116px;
    cursor: pointer;

}
.user_info_photo {
    font-size: 1.5rem;
    font-family: 'europaNuova_b';
    color: #fff;
    border-radius: 4px;
    overflow: hidden;
}

.userphotohover {
    font-size: .8rem;
    text-align: center;
    width: 100px !important;
    height: 100px !important;
    top: 0;
    color: #fff;
    line-height: 116px;
    border-radius: 4px;
    font-weight: bold;
}

.inputfile+label {
            /* max-width: 80%; */
            font-size: 13px;
            font-weight: 600;
            background-color: rgb(241, 241, 241);
            text-overflow: ellipsis;
            white-space: nowrap;
            cursor: pointer;
            display: inline-block;
            overflow: hidden;
            padding: 0.225rem 0.725rem;
            border-radius: 8px;
        }

		.inputfile-3+label:hover {
            color: #0D6EFD;
        }

	.iti--separate-dial-code .iti__selected-flag {
		font-size: 12px;
		height: 100% !important;
	}

	.iti{
        display: block !important;
    }
    .iti--separate-dial-code .iti__selected-flag{font-size: 12px;  height: 48px;}
    .iti input, .iti input[type=text], .iti input[type=tel]{
        z-index: 0 !important;
    }
    .iti__country-list{z-index: 5000;}

	.prefix .form-select {
	font-size: 0.875rem;
}
ul.parsley-errors-list{margin-bottom:0}
</style>
    <div class="row">
        <div class="col-12 grid-margin ">
            <div class="row">
                <div class="col-md-12 d-flex align-items-center mb-3">
                    {{-- <h1 class="mb-0 h3">{{ __('admin.change_password') }}</h1> --}}
                    <h1>{{ __('profile.profile_details') }}</h1>
                </div>

                <div class="col-12">
                    <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#profile-tab" type="button" role="tab" aria-controls="home"
                                aria-selected="true">
								{{ __('admin.profile') }}
                            </button>
                        </li>
                        <li class="nav-item ps-3" role="presentation">
                            <button class="nav-link px-0" id="password-btn" data-bs-toggle="tab" data-bs-target="#password-tab" type="button" role="tab" aria-controls="home" aria-selected="true">
                                {{ __('admin.password')}}
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content pt-3 pb-0" id="myTabContent">
                        <div class="tab-pane show active" id="profile-tab" role="tabpanel" aria-labelledby="home-tab">
                            <form class="mb-0" method="POST" action="{{ route('admin-profile-update') }}" data-parsley-validate id="updateProfile" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <div class="card">
                                            <!-- Card header -->
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="mb-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" viewBox="0 0 17.5 20">
                                                        <path id="icon_personal_pro" d="M8.75,10a5,5,0,1,0-5-5A5,5,0,0,0,8.75,10Zm3.5,1.25H11.6a6.8,6.8,0,0,1-5.7,0H5.25A5.251,5.251,0,0,0,0,16.5v1.625A1.875,1.875,0,0,0,1.875,20h13.75A1.875,1.875,0,0,0,17.5,18.125V16.5A5.251,5.251,0,0,0,12.25,11.25Z">
                                                        </path>
                                                    </svg>
                                                    <span class="ps-2">{{ __('profile.profile_details') }}</span>
                                                </h5>
                                            </div>
                                            <!-- Card body -->
                                            <div class="card-body">
                                                <div class="d-lg-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center mb-4 mb-lg-0">

													<div class="d-flex justify-content-center mb-3">
														<div class="position-relative userphoto" style="overflow:hidden; height:100px">
															<div class="user_info_photo radius_1 text-center verticle-middle border" title="{{ __('profile.change_avatar') }}">

																@php
																	$static_image = asset('settings.profile_images_folder') . '/' . 'no_image.png';
																@endphp

																@if($profile_details['profile_pic'])
																<div class="ratio ratio-1x1">
																	<img name="userProfilePic" src="{{$profile_details['profile_pic']}}" id="userProfilePic" class="cover" />
																</div>
																@else
																<div class="ratio ratio-1x1">
																<img name="userProfilePic" src="{{ URL::asset('/assets/images/user.png') }}" alt="image" id="userProfilePic">
																</div>
																{{-- strtoupper(substr(Auth::user()->firstname, 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname, 0, 1)) --}}
																@endif
															</div>
															<div class="userphotohover">{{ __('profile.change_avatar') }}</div>
														</div>

													</div>
													<div class="input-group d-flex justify-content-center ps-3">
														<div class="box">
															<input type="file" name="user_pic" id="user_pic" class="inputfile inputfile-3 d-none" accept='.jpg,.png,.jpeg'/>
															<label for="user_pic" class="border"><span>{{ __('profile.change_avatar') }}</span></label>
															<span class="invalid-feedback d-block"></span>
															<p><small class="text-muted">{{__('profile.upload_jpg_png_text')}} </small></p>
														</div>
													</div>
													{{-- __('profile.upload_format_require') --}}

                                                        <!-- <div class="ps-3">
                                                            <div class="box">
                                                                <input type="file" name="profile_pic" id="profile_pic" class="inputfile inputfile-3 d-none" accept=".jpg,.png,.jpeg">
                                                                <label for="profile_pic" class="border">
																	<span>Change Avatar</span>
																</label>
                                                            </div>
                                                            <p class="mt-1 text-center"> Upload png or jpg.</p>
                                                        </div> -->

                                                    </div>
                                                    <div class="ms-auto">
                                                        <div id="top_firstname"><h5 class="mb-0">{{$profile_details['firstname'] ?? ''}} {{$profile_details['lastname'] ?? ''}}</h5></div>
                                                        <div id="top_email"><small><i class="fa fa-envelope pe-1"></i>{{$profile_details['email'] ?? ''}}</small></div>
                                                        <div id="top_mobile"><small><i class="fa fa-phone pe-1"></i>+{{$profile_details['phone_code'] ?? ''}}-{{$profile_details['mobile'] ?? ''}}</small>
														</div>
                                                    </div>
                                                </div>

                                            <hr class="my-3">
                                            <div>
                                                <div class="col-md-12 p-3 pb-2 ">
                                                    <div class="row g-3 floatlables">
                                                        <div class="col-md-6">
                                                            <label class="form-label">{{ __('profile.first_name') }}<span class="text-danger">*</span></label>
                                                            <div class="prefix d-flex">
                                                                <select class="form-select w100p border-end-0"
                                                                    name="salutation" id="salutation"
                                                                    style="border-radius: 0px;  background-color: rgba(0, 0, 0, 0.05);">
                                                                    <option value="1" {{(isset($profile_details['salutation']) && $profile_details['salutation'] == "1" ? 'selected' : '') }} >Mr.</option>
                                                                    <option value="2" {{(isset($profile_details['salutation']) && $profile_details['salutation'] == "2" ? 'selected' : '') }}>Ms.</option>
                                                                    <option value="3" {{(isset($profile_details['salutation']) && $profile_details['salutation'] == "3" ? 'selected' : '') }}>Mrs.</option>
                                                                </select>
                                                                <input type="text" class="form-control border-start-0" id="firstname" name="firstname" value="{{$profile_details['firstname'] ?? ''}}" required data-parsley-errors-container="#firstname-error">
                                                            </div>
															<div id="firstname-error"></div>
                                                        </div>

														<div class="col-md-6">
                                                            <label class="form-label">
                                                                {{ __('profile.last_name') }}<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="lastname" id="lastname" value="{{$profile_details['lastname'] ?? ''}}" required="">
                                                        </div>
                                                        <div class="col-md-6 position-relative">
                                                            <label class="form-label">
                                                                {{ __('profile.Email') }}<span class="text-danger">*</span></label>
                                                            <!-- <input type="email" class="form-control" name="email" id="email" value="{{$profile_details['email'] ?? ''}}" readonly> -->
                                                            <input type="email" data-parsley-required-message="{{ __('frontFormValidationMsg.email') }}"  class="form-control" name="email" id="email" value='{{ $profile_details["email"] }}' {{ $profile_details['is_active'] ? "readonly":"" }}>
                                                            @if(!$profile_details['is_active'])
                                                            <a href="javascript:void(0);" style="top: 36px !important;" class="verify js-resendemail" title="{{ __('profile.varify_your_mail') }}" >{{ __('profile.verify') }}</a>
                                                            @endif
                                                        </div>
                                                        @if(\Auth::user()->hasRole('supplier'))
                                                        <div class="col-md-6">
                                                            <label class="form-label">
                                                                {{ __('profile.alternative_email') }}
                                                            </label>
                                                            <input type="email" class="form-control" name="alternate_email" id="alternate_email" value="{{$profile_details['alternate_email'] ?? ''}}">
                                                        </div>
                                                        @endif
                                                        <div class="col-md-6 prefix">
                                                            <label class="form-label">
																{{ __('profile.designation') }}<span class="text-danger">*</span>
															</label>
                                                            <select name="designation" class="form-select"  id="designation" required>
                                                                <option value="">{{__('profile.select_designation')}}</option>
																@foreach($designation as $key => $value)
																	<option value="{{$value['id']}}" {{(isset($profile_details['designation']) && $profile_details['designation'] == $value['id'] ? 'selected' : '') }}>{{$value['name']}}</option>
																@endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 prefix">
                                                            <label class="form-label">
																{{ __('profile.department') }}<span class="text-danger">*</span>
															</label>
                                                            <select name="department" class="form-select" id="department" required>
                                                                <option value=""> {{__('profile.select_department')}}</option>
																@foreach($department as $key => $value)
																	<option value="{{$value['id']}}" {{(isset($profile_details['department']) && $profile_details['department'] == $value['id'] ? 'selected' : '') }}>{{$value['name']}}</option>
																@endforeach
                                                            </select>
                                                        </div>


														<div class="col-md-6">
                                                            <label class="form-label">
                                                                {{ __('profile.mobile_number') }}<span class="text-danger">*</span>
                                                            </label>
                                                            <div class="iti iti--allow-dropdown iti--separate-dial-code">
																<input type="text" class="form-control" id="mobile"
																name="mobile" readonly value="{{$profile_details['mobile']}}"
																required="" data-parsley-type="digits" data-parsley-length="[10, 16]"
																data-parsley-length-message="It should be between 10 and 16 digit."
																data-intl-tel-input-id="0" style="padding-left: 79px;" data-parsley-errors-container="#mobile-error">
                                                                 <a href="/admin/changemobile" class="verify" title="{{ __('profile.change_mobile_number') }}" >{{ __('profile.change') }}</i></a>
                                                            </div>
															<div id="mobile-error"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                                    <button type="submit" id="profile_update_btn" class="btn btn-primary">{{ __('admin.update') }}</button>
                                    <a href="{{route('admin-dashboard')}}" class="btn btn-cancel ms-3">
                                        {{ __('admin.cancel') }}
                                    </a>
                                </div>
                            </div>
                            </form>
                            {{-- profile tab --}}
                        </div>
                        <div class="tab-pane" id="password-tab" role="tabpanel" aria-labelledby="home-tab">
                            <form class="mb-0" method="POST" action="{{ route('admin-change-password-update') }}" data-parsley-validate id="updatePassword">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="mb-0">
                                                    <img src="{{URL::asset('assets/icons/icon_password.png')}}" alt="Password Details" class="pe-2">
                                                    <span>{{ __('admin.password_details') }}</span>
													<span class="ps-2">{{-- __('admin.password') --}}</span>
                                                </h5>
                                            </div>
                                            <div class="card-body p-3 pb-1">

                                                <div class="row">
                                                    <div class="col-md-4 d-flex justify-content-center ">
													<div>
													<img src="{{ URL::asset('assets/icons/password_change.png') }}"> </div> </div>
                                                    <div class="col-md-8">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-3">
                                                                <label for="" class="form-label">{{ __('admin.current_password') }}</label>
                                                                <input type="password" name="oldpassword" id="oldpassword" class="form-control" value="" required>
                                                                <span class="error" id="wrongCurrentPassword"></span>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <label for="" class="form-label">{{ __('admin.new_password') }} </label>
                                                                <input type="password" name="password" id="password" class="form-control" required>
																<span class="error" id="sameNewOldInvalid"></span>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <label for="" class="form-label">{{ __('admin.confirm_password') }}</label>
                                                                <input type="password" name="confirmpassword" id="confirmpassword" class="form-control" data-parsley-equalto="#password" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">{{ __('admin.update_password') }}</button>
                                        <a href="{{route('admin-dashboard')}}" class="btn btn-cancel ms-3">
                                            {{ __('admin.cancel') }}
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

	<!-- Upload profile image modal -->
    <div class="customscroll showpop">
      <div class="modal fade" id="UploadProfileImageModal" tabindex="-1" role="dialog"  aria-hidden="true">
         <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
               <div class="popup-marg">
                  <div class="modal-header">
                    <h3 class="modal-title text-center text-white">{{ __('profile.change_avatar') }}</h3>
                    <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close">
					<img src="{{URL::asset('front-assets/images/icons/times.png')}}" alt="Close">
    </button>
                  </div>
                  <div class="modal-body p-0">
                     <div class="row">
                        <div class="col-md-12 text-center mt-20">
                           <div id="profile_image_preview" class="mt-3"></div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer" style="justify-content: center;">
                  <button id="cropProfileImage" class="btn btn-primary crop-profile-picture  mt-0 save-btn save-btn-bg text-white">
					  Crop & Save</button>
               </div>
            </div>
         </div>
      </div>
    </div>
    <!-- Upload profile image modal -->
	<script>
		// croppie image related
		var $uploadProfileCrop,	tempProfileFilename, rawProfileImg,	imageProfileId;
		$uploadProfileCrop = $('#profile_image_preview').croppie({
			enableOrientation: true,
			enableZoom: true,
			showZoomer: true,
			enableExif: true,
			viewport: {
				width: 250,
				height: 250,
				// type: 'circle'
			},
			boundary: {
				width: 260,
				height: 260
			}
        });
		// croppie image related
	</script>
@stop

@section('scripts')
<script>
	// croppie image related
	$('#user_pic').on('change', function () {
		imageProfileId = $(this).data('id');
		tempProfileFilename = $(this).val();
		readProfileFile(this);
	});
	function readProfileFile(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			var file = input.files[0];
			var fileType = file.type.split('/')[0];
			var fileExtension = file.type.split('/')[1];
			var maxFileSize = 3000; //3mb
			var fileSize = Math.round((file.size / 1024));

			if(fileSize > maxFileSize){
				$("#user_pic").val("");
				swal({
					icon: 'error',
					title: '',
					text: '{{__('admin.file_size_under_3mb')}}',
				});
				return false;
			}


			if( fileType == 'image' && (fileExtension == 'png' || fileExtension == 'jpg' || fileExtension == 'jpeg') ) {
				console.log('in image');
				reader.onload = function (e) {
				$('#UploadProfileImageModal').modal('show');
				rawProfileImg = e.target.result;
				}
				reader.readAsDataURL(input.files[0]);
			} else {
				$("#user_pic").val("");
				swal({
				icon: 'front-assets/images/format_not_support.png',
				title: "Format not Supported!",
				showClass: {
					popup: 'animate__animated animate__fadeInDown'
				},
				hideClass: {
					popup: 'animate__animated animate__fadeOutUp'
				}
				});
			}
		} else {
			swal("Sorry - you're browser doesn't support the FileReader API");
		}
	}
	$('#UploadProfileImageModal').on('shown.bs.modal', function(){
		$uploadProfileCrop.croppie('bind', {
			url: rawProfileImg
		}).then(function(){
			// console.log('jQuery bind complete');
		});
	});
	$('.crop-profile-picture').on('click', function (ev) {
		$uploadProfileCrop.croppie('result', {
			type: 'canvas',
			size: 'viewport',
			quality: 1
		}).then( function (img) {
			// console.log(img);
			$("#userProfilePic").closest('div.ratio.ratio-1x1').show();
			$("#userProfilePic").attr( 'src', img );
			$('#UploadProfileImageModal').modal('hide');
		});
	});
	var loadFile = function(event) {
		var output = document.getElementById('userProfilePic');
		output.src = URL.createObjectURL(event.target.files[0]);
		output.onload = function() {
			URL.revokeObjectURL(output.src) // free memory
		}
	};
	// croppie image related


    $("#updatePassword").on('submit',function(event){
        $('#wrongCurrentPassword').html('');
		var isValidPass = validateOldNewPassword();
		if(!isValidPass){
			return false;
		}
        event.preventDefault();
        var formData = new FormData($("#updatePassword")[0]);
        if ($('#updatePassword').parsley().validate()) {
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
                            heading: r.heading,
                            text: r.message,
                            showHideTransition: "slide",
                            icon: r.icon,
                            loaderBg: r.loaderBg,
                            position: "top-right",
                        });
                        $("#updatePassword")[0].reset();
                    } else {
                        $('#wrongCurrentPassword').html(r.message);
                    }
                },
                error: function(xhr) {
                    alert('{{__('admin.error_while_selecting_list')}}');
                }
            });
        }
    });


    $("#updateProfile").on('submit',function(event){
        event.preventDefault();
        var formData = new FormData($("#updateProfile")[0]);
		var cropImageSrc = $(".user_info_photo").find('img').attr('src');
		// var cropImage = new Image();
		// cropImage.src = cropImageSrc;
		// document.body.appendChild(image);
		// formData.append('cropImage', cropImage);

		formData.append('cropImageSrc', cropImageSrc);
		// console.log(cropImageSrc);
        if ($('#updateProfile').parsley().validate()) {
			$('#profile_update_btn').prop('disabled', true);
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: formData,
                contentType: false,
                processData: false,
                success: function(r) {
					console.log(r);
					// return;
                    if (r.success == true) {
                        resetToastPosition();
                        $.toast({
                            heading: r.heading,
                            text: r.message,
                            showHideTransition: "slide",
                            icon: r.icon,
                            loaderBg: r.loaderBg,
                            position: "top-right",
                        });
                    } else {
                    }

					$('#alternate_email').val(r.data.alternate_email);
					$('#department').val(r.data.department);
					$('#designation').val(r.data.designation);
					// $('#email').val(r.data.email);
					$('#firstname').val(r.data.firstname);
					$('#lastname').val(r.data.lastname);
					$('#left_sidebar_name').html(r.data.firstname);
					// $('#left_sidebar_name').html(r.data.firstname+" "+r.data.lastname);
					$('#mobile').val(r.data.mobile);
					$('#alternate_email').val(r.data.alternate_email);
					$('#salutation').val(r.data.salutation);

					$('#top_firstname').html('<h5 class="mb-0">'+r.data.firstname+' '+r.data.lastname+'</h5>');
					// $('#top_email').html('<small><i class="fa fa-envelope pe-1"></i>'+ r.data.email +'</small>');
					$('#top_mobile').html('<small><i class="fa fa-phone pe-1"></i>+'+r.data.phone_code+'-'+r.data.mobile +'</small>');


					if(r.data.profile_pic){
						$('#userProfilePic').attr("src", r.data.profile_pic);
						$('#layout_profile_img').attr("src", r.data.profile_pic);
						var right_profile_top_image = '<img src="'+r.data.profile_pic+'" alt="image" id="profile_right_top_img">';
						$('#messageDropdown').html(right_profile_top_image);
					}
					bindCountryCode(r.data.phone_code, r.data.country_code);
					$('#user_pic').val('');
					$('#profile_update_btn').prop('disabled', false);
					console.log(r.data.designation_name);
					$('.left_sidebar_designation').html(r.data.designation_name);
                },
                error: function(xhr) {
                    // alert('{{__('admin.error_while_selecting_list')}}');
                }
            });
        }
		return false;
    });

    resetToastPosition = function() {
        $('.jq-toast-wrap').removeClass('bottom-left bottom-right top-left top-right mid-center'); // to remove previous position class
        $(".jq-toast-wrap").css({
            "top": "",
            "left": "",
            "bottom": "",
            "right": ""
        }); //to remove previous position style
    }

	$(document).ready(function(){
		bindCountryCode("{{$profile_details['phone_code']}}", "{{$profile_details['country_code']}}");

		$('#oldpassword').on('keyup', function(){
			validateOldNewPassword();
		});

		$('#password').on('keyup', function(){
			validateOldNewPassword();
		});
	});

	function bindCountryCode(phoneCode, countryCode){
		$('input[name="phone_code"]').val(phoneCode);
		iti.setCountry(countryCode);
	}

	function validateOldNewPassword(){
		$('#sameNewOldInvalid').html("");
		var new_password = $('#password').val();
		var old_password = $('#oldpassword').val();

		if(new_password.length > 0 && old_password.length > 0){
			if(new_password == old_password){
				$('#sameNewOldInvalid').html("{{__('profile.old_new_unique_validation_message')}}");
				// The old password and new password should not be same
				return 0
			}
		}
		return 1;
	}
    $(document).on("click", ".js-resendemail", function() {
        loginmail = $('#hemail').val()
        email = $('#email').val()
         if(loginmail == email){
            swal({
                title: "",
                text: "{{ __('admin.check_your_mail') }}",
                icon: "/assets/images/warn.png",
                buttons: '{{ __('admin.ok') }}',
                dangerMode: true,
            });
            return false;
         } 
            
        if ($('#email').parsley().isValid()) {
            xhr = $.ajax({
                url: '{{ route('check-invite-user-email-exist') }}',
                method: 'POST',
                dataType: 'json',
                async:false,
                data: {
                    "_token": "{{ csrf_token() }}",
                     email: $('#email').val(),
                },
                success: function (data) {
                    if(!data){
                        swal({
                            title: "",
                            text: "{{ __('signup.user_already_registred') }}",
                            icon: "/assets/images/warn.png",
                            buttons: '{{ __('admin.ok') }}',
                            dangerMode: true,
                        });
                        return false;
                    }
                    else{
                        $.ajax({
                            url: "{{ route('profile-invite-supplier-verify') }}",
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                 user_email: $('#email').val(),
                            },
                            success: function (successData) {
                                new PNotify({
                                    text: '{{ __('admin.invitation_send_successfully') }}',
                                    type: 'success',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'slow',
                                    delay: 3000,
                                });
                                location.reload();

                            },
                            error: function () {
                                console.log('error');
                            }
                        });
                    }
                },
            });
        }else{
            swal({
                title: "",
                text: "{{ __('validation.emailvalid') }}",
                icon: "/assets/images/warn.png",
                buttons: '{{ __('admin.ok') }}',
                dangerMode: true,
            });
        }
    })
    // itl phone code
        var input = document.querySelector("#mobile");
        var iti = window.intlTelInput(input, {
            initialCountry:"id",
            separateDialCode:true,
            dropdownContainer:null,
            preferredCountries:["id"],
            hiddenInput:"phone_code"
        });
        // console.log(iti.getSelectedCountryData());
        $("#mobile").focusin(function(){
            let countryData = iti.getSelectedCountryData();
            $('input[name="phone_code"]').val(countryData.dialCode);
        });
        // itl phone code

        $(document).ready(function(){
            @php
                $cPhoneCode = $profile_details['phone_code']?str_replace('+','',$profile_details['phone_code']):62;
                $cCountry = $profile_details['phone_code']?strtolower(getRecordsByCondition('countries',['phone_code'=>$cPhoneCode],'iso2',1)):'id';
            @endphp
            $('input[name="phone_code"]').val('{{$cPhoneCode}}');
            iti.setCountry('{{$cCountry}}');
        });
</script>
@stop
